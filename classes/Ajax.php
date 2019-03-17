<?
class Ajax extends Mysql {

 public $imageNofound = '/icons/image404.gif';

 public function __construct(){

     #1 LOAD IMAGES NAMES
     if(isset($_POST['start_loader']) AND isset($_POST['end_loader']) AND isset($_POST['object_parent']) AND isset($_POST['sort_by']) AND isset($_POST['mobile'])):
        $this->loadNames();
     endif;

     #2 LOAD CART DATA
     if(isset($_GET['product_id']) AND empty($_GET['one_id']) AND empty($_GET['property_id']) AND isset($_GET['imageHeight']) AND isset($_GET['imageWidth'])):
        $this->loadCartData();
     endif;
    
     #3 LOAD PRICES
     if(isset($_GET['one_id']) AND isset($_GET['product_id']) AND isset($_GET['property_id'])):
        $this->drawingPrices();
     endif;
 }

 public function printJson($array_data){
     header('Content-type: application/json');
     echo json_encode($array_data);
 }

 public function drawingPrices(){
    
     $oneId = (int)$_GET['one_id'];
     $productId = (int)$_GET['product_id'];
     $propertyId = (int)$_GET['property_id'];
     $subSql = "SELECT `__option`.`id`,
                        `__option`.`title`,
                        `__option`.`bydef`,
                         `__price`.`price`
                    FROM `__option`,`__price`
                  WHERE `__option`.`product` = $productId
                    AND `__option`.`property` = $propertyId
                     AND `__price`.`one`= $oneId
                     AND `__price`.`two`=`__option`.`id` AND `__option`.`visib` = 1";
      $array = $this->getArray($subSql);
      $resultArray = array();
      foreach($array as $item):
              $resultArray[] = array('id'=>$item['id'],'title'=>$item['title'],'bydef'=>$item['bydef'],'price'=>$item['price']);
      endforeach;
      return $this->printJson($resultArray);
 }

 public function loadCartData(){

      $imageHeight = (int)$_GET['imageHeight'];
      $imageWidth = (int)$_GET['imageWidth'];

      if($imageWidth > $imageHeight):

         $imageHeight = (int)$_GET['imageWidth'];
         $imageWidth = (int)$_GET['imageHeight'];

      endif;

      $h = $imageHeight;
      $w = $imageWidth;
     
      $flagEnterSizes = 0;

      // #1 CLICK REQUEST
      if(is_numeric($_GET['product_id'])):
         $product_id = (int)$_GET['product_id'];

         // POSTER OR CALENDAR
         if($product_id == 1 OR $product_id == 2):

             $sql = "SELECT __property.id AS id,
                       __property.title AS title,
                         __option.title AS bydef,
                            __option.id AS bydef_id,
                        __option.rating AS rating,
                         __option.bydef AS bydef_value
                  FROM __property, __option
                 WHERE __property.product = $product_id
                   AND __property.visib = 1
                   AND (( $h >= __option.height OR $w >= __option.height) OR ($h >= __option.width AND $w >= __option.width) OR __option.bydef = 1)
                   AND __option.product = __property.product
                   AND __option.property = __property.id";

              $tmpArray = $this->getArray($sql);
           
              // id максимального размера
              $sqlCurrentMaxOptionId = "SELECT id FROM __option WHERE product = $product_id AND (($h >= height OR $w >= height) OR ($h >= width AND $w >= width)) ORDER By rating LIMIT 1";
              $currentMaxOptionId = $this->getRowValue($sqlCurrentMaxOptionId,"id");
              
              #1 когда не попадает в диапазон
              if($tmpArray[0]['bydef_value'] == 1 AND $tmpArray[0]['bydef_id'] != $currentMaxOptionId):
                 #POSTER or CALENDAR
                 $flagEnterSizes = true;  
                 if($product_id == 1 or $product_id == 2):
                 
                    if($product_id == 1)$endLimit = 4;
                    if($product_id == 2)$endLimit = 6;
                 
                    $sql = "SELECT __property.id AS id,
                       __property.title AS title,
                         __option.title AS bydef,
                            __option.id AS bydef_id,
                        __option.rating AS rating,
                         __option.bydef AS bydef_value
                    FROM __property, __option
                   WHERE __property.product = $product_id
                     AND __property.visib = 1
                    AND (__option.id = $currentMaxOptionId OR __option.bydef = 1)
                     AND __option.product = __property.product
                     AND __option.property = __property.id LIMIT 1, $endLimit";          
                 endif;  
              #2 когда попадает в диапазон или равно дипазону
              else:      
                  $sql = "SELECT __property.id AS id,
                           __property.title AS title,
                             __option.title AS bydef,
                                __option.id AS bydef_id,
                            __option.rating AS rating,
                            __option.bydef AS bydef_value
                       FROM __property, __option
                      WHERE __property.product = $product_id
                        AND __property.visib = 1
                        AND __option.product = __property.product
                        AND __option.property = __property.id
                        AND __option.bydef = 1";                    
              endif;
          // OTHER PRODUCTS
          else:
              $sql = "SELECT __property.id AS id,
                    __property.title AS title,
                      __option.title AS bydef,
                      __option.id AS bydef_id,
                      __option.rating AS rating,
                      __option.bydef AS bydef_value
                    FROM __property, __option
                   WHERE __property.product = $product_id
                     AND __property.visib =1
                     AND __option.product = __property.product
                     AND __option.property = __property.id
                     AND __option.bydef = 1";
          endif;
          
     // #2 LOAD PAGE REQUEST
     else:
        $product = mysql_real_escape_string($_GET['product_id']);
        // POSTER OR CALENDAR
        if($product == 'poster' OR $product == 'calendar'):
        
           $sql = "SELECT __property.id AS id,
                     __property.title AS title,
                     __option.title AS bydef,
                     __option.id AS bydef_id,
                     __option.product AS product,
                     __option.bydef AS bydef_value
                FROM __products,
                     __property,
                     __option
                WHERE __products.hash = '$product'
                  AND __property.product = __products.id
                  AND __property.visib = 1
                  AND (( $h >= __option.height OR $w >= __option.height) OR ($h >= __option.width AND $w >= __option.width) OR __option.bydef = 1)
                  AND __option.product = __property.product
                  AND __option.property = __property.id";
           
           $tmpArray = $this->getArray($sql);
              
           // id максимального размера
           $sqlCurrentMaxOptionId = "SELECT __option.id FROM __option,__products WHERE __products.hash = '$product' AND __products.id = __option.product AND (($h >= __option.height OR $w >= __option.height) OR ($h >= __option.width AND $w >= __option.width)) ORDER By __option.rating LIMIT 1";
           $currentMaxOptionId = $this->getRowValue($sqlCurrentMaxOptionId,"id");
           
           #1 когда не попадает в диапазон
           if($tmpArray[0]['bydef_value'] == 1 AND $tmpArray[0]['bydef_id'] != $currentMaxOptionId):
              #POSTER or CALENDAR
              $flagEnterSizes = true;
                 
              if($product == 'poster' or $product == 'calendar'):
                 
                 if($product == 'poster')$endLimit = 4;
                 if($product == 'calendar')$endLimit = 6;
                 
                 $sql = "SELECT __property.id as id,
                        __property.title as title,
                        __option.title as bydef,
                        __option.id as bydef_id,
                        __option.product as product,
                        __option.bydef as bydef_value
                   FROM __products,
                        __property,
                        __option
                   WHERE __products.hash = '$product'
                     AND __property.product = __products.id
                     AND __property.visib = 1
                     AND (__option.id = $currentMaxOptionId OR __option.bydef = 1)
                       AND __option.product = __property.product
                       AND __option.property = __property.id LIMIT 1, $endLimit";  
              endif;  
           #2 когда попадает в диапазон или равно дипазону
           else:      
              $sql = "SELECT __property.id AS id,
                        __property.title AS title,
                        __option.title AS bydef,
                        __option.id AS bydef_id,
                        __option.product AS product,
                        __option.bydef AS bydef_value
                   FROM __products,
                        __property,
                        __option
                   WHERE __products.hash = '$product'
                     AND __property.product = __products.id
                     AND __property.visib = 1
                     AND __option.product = __property.product
                     AND __option.property = __property.id
                     AND __option.bydef = 1"; 
           endif;
        // OTHER PRODUCTS   
        else:
           $sql = "SELECT __property.id AS id,
                     __property.title AS title,
                     __option.title AS bydef,
                     __option.id AS bydef_id,
                     __option.product AS product,
                     __option.bydef AS bydef_value
                FROM __products,
                     __property,
                     __option
                WHERE  __products.hash = '$product' 
                AND    __products.id = __property.product 
                AND    __property.visib = 1
                AND    __option.product = __property.product
                AND    __option.property = __property.id 
                AND    __option.bydef = 1";             
         endif;     
      endif;

      $array = $this->getArray($sql);

      $_SESSION['opeOpt']='';
      $n = 1;
      $resultArray = array();
      $optArray = array();

      foreach($array as $item):
              $property_id = $item['id'];
              $property_name = $item['title'];
              $resOptArray = array();

              if(!$product_id):
                  $product_id = $item['product'];
              endif;

              // #1,3,4 FOR OTHER
              if($n != 2):
                 
                 if(($product_id == 1 OR $product_id == 2) AND $n == 1):
                     // НЕ ПОПАДАЕТ ПОД ДИАПАЗОН
                     if($flagEnterSizes == true):
                        $subSql = "SELECT `id`,
                               `title`,
                               `bydef`
                                FROM `__option`
                               WHERE `product` = $product_id
                                 AND `property` = $property_id
                                 AND ( ( $h >= __option.height  OR  $w >= __option.height ) OR ( $h >= __option.width AND $w >= __option.width ) )
                                 AND `visib` = 1
                            ORDER BY `rating` ASC";
                      // ПОПАДАЕТ ПОД ДИАПАЗОН
                      else:
                         $subSql = "SELECT `id`,
                               `title`,
                               `bydef`
                                FROM `__option`
                               WHERE `product` = $product_id
                                 AND `property` = $property_id
                                 AND ( ( $h >= __option.height  OR  $w >= __option.height ) OR ( $h >= __option.width AND $w >= __option.width ) OR __option.bydef = 1 )
                                 AND `visib` = 1
                            ORDER BY `rating` ASC"; 
                      endif;
                 else:
                     $subSql = "SELECT `id`,
                               `title`,
                               `bydef`
                          FROM `__option`
                         WHERE `product` = $product_id
                           AND `property` = $property_id
                           AND `visib` = 1
                      ORDER BY `rating` ASC"; 
                 endif;
                 
              // #2 FOR PAPERS !!!PRICES
              else:
              
                 $one = $_SESSION['opeOpt'];
                 $subSql = "SELECT `__option`.`id`,
                             `__option`.`title`,
                             `__option`.`bydef`,
                              `__price`.`price`
                        FROM `__option`,`__price`
                       WHERE `__option`.`product` = $product_id
                         AND `__option`.`property` = $property_id
                          AND `__price`.`one`='".$one."'
                          AND `__price`.`two`=`__option`.`id` AND `__option`.`visib` = 1";
                          
                  //echo $subSql;
                // exit;         
              endif;
              
              $optArray = $this->getArray($subSql);
              
              $k=0;
              foreach($optArray as $opt):
                      if($n == 1 AND $k == 0):
                         if($opt['bydef'] == 1):
                            if(empty($_SESSION['opeOpt'])):
                               $_SESSION['opeOpt'] = $opt['id'];
                            endif;
                         else:   
                            if(empty($_SESSION['opeOpt'])):
                              $_SESSION['opeOpt'] = $optArray[0]['id'];  
                            endif;
                         endif;    
                      endif;
                      $resOptArray[] = array('id'=>$opt['id'],'title'=>$opt['title'],'bydef'=>$opt['bydef'],'price'=>$opt['price']);
              $k++;        
              endforeach;
              
              $resultArray[] = array('id'=>$item['id'],'title'=>$item['title'],'bydef_id'=>$item['bydef_id'],'bydef'=>$item['bydef'],'options'=>$resOptArray);
              $n++;
              
        endforeach;
     return $this->printJson($resultArray);
 }

 public function loadNames() {

     $start_loader = (int)$_POST['start_loader'];
     $end_loader = (int)$_POST['end_loader'];
     $object_parent = (int)$_POST['object_parent'];
     $sort_by = $_POST['sort_by'];

     if($sort_by == 'new') $sort_by_data = 'ORDER BY object_id DESC';
     if($sort_by == 'old') $sort_by_data = 'ORDER BY object_id ASC';
     if($sort_by == 'most_popular') $sort_by_data = 'ORDER BY object_view DESC';
     if($sort_by == '42x60') $sort_by_data = 'AND (((object_height >= "4000") OR (object_width >= "4000")) OR (object_height >= "2800" AND object_width >= "2800")) ORDER BY object_view ASC';
     if($sort_by == '36x56') $sort_by_data = 'AND (((object_height >= "3800") OR (object_width >= "3800")) OR (object_height >= "2400" AND object_width >= "2400")) ORDER BY object_view ASC';
     if($sort_by == '32x46') $sort_by_data = 'AND (((object_height >= "3200") OR (object_width >= "3200")) OR (object_height >= "2200" AND object_width >= "2200")) ORDER BY object_view ASC';
     if($sort_by == '24x36') $sort_by_data = 'AND (((object_height >= "2400") OR (object_width >= "2400")) OR (object_height >= "1600" AND object_width >= "1600")) ORDER BY object_view ASC';
     if($sort_by == '18x24') $sort_by_data = 'AND (((object_height >= "1500") OR (object_width >= "1500")) OR (object_height >= "1100" AND object_width >= "1100")) ORDER BY object_view ASC';

     $field_image = 'object_picture_icon';
     $field_folder = 'object_smalls';
     $dir_folder = 'smalls';

     if($_POST['mobile'] == 'false'):
        $field_image = 'object_picture_banner';
        $field_folder = 'folder';
        $dir_folder = 'bigs';
     endif;

     $filed_image = ($_POST['mobile'] == 'false') ? 'object_picture_banner' : 'object_picture_icon';
     $filed_folder = ($_POST['mobile'] == 'false') ? 'object_picture_banner' : 'object_picture_icon';
     $filed_image = ($_POST['mobile'] == 'false') ? 'object_picture_banner' : 'object_picture_icon';

     $sql = "SELECT object_id,$filed_image,$field_folder,adult FROM __picture WHERE object_active=1 AND object_parent=".$object_parent." $sort_by_data LIMIT $start_loader, $end_loader";

     $result = $this->getQuery($sql);
     $array_data = array();

     while($row = mysql_fetch_assoc($result)):
           $object_url = '/img/'.$dir_folder.'/'.$row[$field_folder].'/'.$row[$filed_image];
           $image_url = (!file_exists('..'.$object_url)) ? $this->imageNofound : $object_url;
           $array_data[] = array(
             'object_id'=>$row['object_id'],
             'object_banner'=>$row[$field_image],
             'object_url'=>$image_url,
             'object_adult'=>$row['adult']
           );
     endwhile;
     
     return $this->printJson($array_data);
     
   }
}