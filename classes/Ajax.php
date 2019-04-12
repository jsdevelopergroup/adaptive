<?
class Ajax extends Mysql {

 public $imageNofound = '/icons/image404.gif';
 public $pictureType;
 
 public $startLoad;
 public $endLoad;
 public $parent;
 public $sortBy;
 public $device;
 
 public $sqlParametr;
 public $searchInpValue;
 public $sortSql;

 public function __construct(){
    
     $this->startLoad = $this->getPostValue('start_loader','int');
     $this->endLoad = $this->getPostValue('end_loader','int');
     $this->parent = $this->getPostValue('object_parent','int');
     $this->sortBy = $this->getPostValue('sort_by','string');
     $this->device = $this->getPostValue('mobile','string');
     $this->pictureType = ($this->device == 'false') ? 'big' : 'small';
     
     $this->device = $this->getPostValue('mobile','string');
     $this->searchInpValue = mysql_real_escape_string(trim(strval($_GET['inpTextValue'])));
    
     if($this->sortBy):
        $this->getSqlParametrs($this->sortBy);
     endif;
     
     #1 LOAD IMAGES NAMES
     if($this->endLoad AND $this->parent AND $this->sortBy AND $this->device):
         $this->loadNames();
     endif;
     
     #2 SEARCH AJAX
     if($this->searchInpValue):
        $this->searchAjax();
     endif;
     
     #3 SEND MESSAGE // yName, tOpic, yEmail, yMess
     if(isset($_GET['yName']) AND isset($_GET['tOpic']) AND isset($_GET['yEmail']) AND isset($_GET['yMess'])):
        $this->sendMessage();
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
 
 public function printJson($arrayData){
     header('Content-type: application/json');
     echo json_encode($arrayData);
 }
 
 public function getPostValue($postKey,$postType){
     if($postType == 'int') return (isset($_POST[$postKey])) ? intval($_POST[$postKey]) : false;
     if($postType == 'string') return (isset($_POST[$postKey])) ? strval($_POST[$postKey]) : false;
 }
 
 #1.2 getSqlParametrs
 public function getSqlParametrs($sort_by){
     if($sort_by == 'new') $this->sqlParametr = 'ORDER BY picture_id DESC';
     if($sort_by == 'old') $this->sqlParametr = 'ORDER BY picture_id ASC';
     if($sort_by == 'most_popular') $this->sqlParametr = 'ORDER BY picture_view DESC';
     if($sort_by == '42x60') $this->sqlParametr = 'AND (((picture_height >= "4000") OR (picture_width >= "4000")) OR (picture_height >= "2800" AND picture_width >= "2800")) ORDER BY picture_view ASC';
     if($sort_by == '36x56') $this->sqlParametr = 'AND (((picture_height >= "3800") OR (picture_width >= "3800")) OR (picture_height >= "2400" AND picture_width >= "2400")) ORDER BY picture_view ASC';
     if($sort_by == '32x46') $this->sqlParametr = 'AND (((picture_height >= "3200") OR (picture_width >= "3200")) OR (picture_height >= "2200" AND picture_width >= "2200")) ORDER BY picture_view ASC';
     if($sort_by == '24x36') $this->sqlParametr = 'AND (((picture_height >= "2400") OR (picture_width >= "2400")) OR (picture_height >= "1600" AND picture_width >= "1600")) ORDER BY picture_view ASC';
     if($sort_by == '18x24') $this->sqlParametr = 'AND (((picture_height >= "1500") OR (picture_width >= "1500")) OR (picture_height >= "1100" AND picture_width >= "1100")) ORDER BY picture_view ASC';
 }
 
 #1.1 Pictures Data
 public function picturesData($sqlQuery){
     $result = $this->getQuery($sqlQuery);
     while($row = mysql_fetch_assoc($result)):
           $object_url = '/img/'.$this->pictureType.'s/'.$row['picture_dir_'.$this->pictureType].'/'.$row['picture_'.$this->pictureType];
           $image_url = (!file_exists('..'.$object_url)) ? $this->imageNofound : $object_url;
           $array_data[] = array(
             'picture_id'=>$row['picture_id'],
             'picture_big'=>$row['picture_'.$this->pictureType],
             'picture_path'=>$image_url,
             'picture_adult'=>$row['picture_adult']
           );
     endwhile;
     return $array_data;
 }
 
 #1 LOAD IMAGES NAMES
 public function loadNames() {
     $sqlQuery = "SELECT picture_id,
                         picture_$this->pictureType,
                         picture_dir_$this->pictureType,
                         picture_adult 
                    FROM __picture 
                    WHERE picture_active=1 
                    AND picture_parent=$this->parent $this->sqlParametr 
                    LIMIT $this->startLoad, $this->endLoad";
     return $this->printJson($this->picturesData($sqlQuery)); 
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
      // #2 LOAD PAGE REQUEST
      else:
         $product = mysql_real_escape_string($_GET['product_id']);
         $product_id = $this->getRowValue("SELECT id FROM __products WHERE hash = '$product'","id");
      endif;
      
      if($product == 'poster' OR $product == 'calendar' OR $product == 'canvas' OR $product == 'photo'):
      
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
                  AND __option.property = __property.id ORDER BY __property.rating ASC";
          
          $tmpArray = $this->getArray($sql);
          $sqlCurrentMaxOptionId = "SELECT __option.id FROM __option,__products WHERE __products.hash = '$product' AND __products.id = __option.product AND (($h >= __option.height OR $w >= __option.height) OR ($h >= __option.width AND $w >= __option.width)) ORDER By __option.rating LIMIT 1";
          $currentMaxOptionId = $this->getRowValue($sqlCurrentMaxOptionId,"id");
           
           #1 когда не попадает в диапазон
           if($tmpArray[0]['bydef_value'] == 1 AND $tmpArray[0]['bydef_id'] != $currentMaxOptionId):
              #POSTER or CALENDAR
              $flagEnterSizes = true;
                 
              if($product == 'poster')$endLimit = 4;
              if($product == 'calendar')$endLimit = 6;
              if($product == 'canvas')$endLimit = 4;
                 
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
                     AND __option.property = __property.id ORDER BY __property.rating ASC LIMIT 1, $endLimit";  
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
                     AND __option.bydef = 1 ORDER BY __property.rating ASC"; 
           endif;
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
                     AND __option.bydef = 1 ORDER BY __property.rating ASC";        
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
                 include '__sub_All_Options.php'; 
              // #2 FOR PAPERS !!!PRICES
              else:
                include '__sub_Two_Option.php';
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
 
 public function searchAjax(){
     $searchq = $this->searchInpValue;
     $pos = strpos($searchq, '.jpg');
     if($pos === false):
       
        if(is_numeric($_GET['inpTextValue'])):

           $object_id = $this->getOneRow("SELECT picture_id, picture_parent, picture_big, picture_dir_big FROM __picture WHERE picture_id = '$searchq'");
           $picture_id = $object_id["picture_id"];
           $object_parent = $object_id["picture_parent"];
           $object_name = $this->getRowValue("SELECT celebrity_name FROM __celebrity WHERE celebrity_parent = $object_parent","celebrity_name");
           $object_picture = "/img/bigs/".$object_id["picture_dir_big"]."/".$object_id["picture_big"];
           $object_link = SITE_NAME.'/'.str_replace(' ','_',$object_name).'/'.$picture_id.'_'.str_replace(' ','_',$object_name).'_poster.html';
           $resultArray[] = array("picture"=>"numeric","object_id"=>$picture_id,"object_name"=>$object_name,"object_picture"=>$object_picture,"object_link"=>$object_link,"picture_name"=>$object_id["picture_big"]);
           return $this->printJson($resultArray); 
        
        else:
      
           $sql = "SELECT celebrity_name FROM __celebrity WHERE celebrity_name = '".$searchq."' AND celebrity_active = 1";
           $res = mysql_query($sql);
           $counts_names = mysql_num_rows($res);
           $tmp_array = explode(' ',$searchq);
           $new_array = array();
      
           if(count($tmp_array)>0):
              $countWords=0;
                for($i=0;$i<count($tmp_array);$i++):
                  if(strlen($tmp_array[$i])>1):
                    if($tmp_array[$i+1] != ''):
                       $stAn .= "AND celebrity_name LIKE '%".$tmp_array[$i+1]."%'";
                       $stOr .= "OR celebrity_name LIKE '%".$tmp_array[$i+1]."%'";
                    endif;
                    $new_array[]=$tmp_array[$i];
                    $countWords++;
                  endif;
                endfor;
           endif;  
      
           if($counts_names > 0):
              if($countWords==1):
                 $sql = "(SELECT celebrity_name FROM __celebrity WHERE celebrity_name = '".$searchq."' AND celebrity_active = 1)
                          UNION
                         (SELECT celebrity_name FROM __celebrity WHERE celebrity_name LIKE '".$searchq."%' AND celebrity_active = 1 ORDER BY celebrity_view DESC) 
                          UNION 
                         (SELECT celebrity_name FROM __celebrity WHERE celebrity_name LIKE '%".$searchq."%' AND celebrity_active = 1 ORDER BY celebrity_view DESC)";
              endif;
              if($countWords>1):
                 $sql = "(SELECT celebrity_name FROM __celebrity WHERE celebrity_name = '".$searchq."' AND celebrity_active = 1 ORDER BY celebrity_view DESC)
                          UNION
                         (SELECT celebrity_name FROM __celebrity WHERE celebrity_name LIKE '".$searchq."%' AND celebrity_active = 1 ORDER BY celebrity_view DESC)
                          UNION
                         (SELECT celebrity_name FROM __celebrity WHERE celebrity_name LIKE '%".$searchq."%' AND celebrity_active = 1 ORDER BY celebrity_view DESC)
                          UNION
                         (SELECT celebrity_name FROM __celebrity WHERE celebrity_name LIKE '%".$new_array[0]."%' ".$stAn." AND celebrity_active = 1 ORDER BY celebrity_view DESC)
                          UNION
                         (SELECT celebrity_name FROM __celebrity WHERE celebrity_name LIKE '%".$new_array[0]."%' ".$stOr." AND celebrity_active = 1 ORDER BY celebrity_view DESC)";         
              endif;
           else:
              if(count($tmp_array)>0):
                     for($i=0;$i<count($tmp_array);$i++):
                         if(strlen($tmp_array[$i])==1):
                            $searchq = trim(str_replace($tmp_array[$i],'',$searchq));
                         endif;
                     endfor;   
               endif;
               
               if($countWords==1):
                  $sql = "(SELECT celebrity_name FROM __celebrity WHERE celebrity_name LIKE '".$searchq."%' AND celebrity_active = 1 ORDER BY celebrity_view DESC) 
                           UNION 
                          (SELECT celebrity_name FROM __celebrity WHERE celebrity_name LIKE '%".$searchq."%' AND celebrity_active = 1 ORDER BY celebrity_view DESC)";
               endif;      
            
               if($countWords>1):
                  $sql = "(SELECT celebrity_name FROM __celebrity WHERE celebrity_name LIKE '".$searchq."%' AND celebrity_active = 1 ORDER BY celebrity_view DESC) 
                           UNION
                          (SELECT celebrity_name FROM __celebrity WHERE celebrity_name LIKE '%".$searchq."%' AND celebrity_active = 1 ORDER BY celebrity_view DESC)
                           UNION
                          (SELECT celebrity_name FROM __celebrity WHERE celebrity_name LIKE '%".$new_array[0]."%' ".$stAn." AND celebrity_active = 1 ORDER BY celebrity_view DESC)
                           UNION
                          (SELECT celebrity_name FROM __celebrity WHERE celebrity_name LIKE '%".$new_array[0]."%' ".$stOr." AND celebrity_active = 1 ORDER BY celebrity_view DESC)";       
               endif;
            endif; 
         
         $result = mysql_query($sql);
         $count_rows = mysql_num_rows($result);
         $resultArray = array();
         $count = 0;
         
         while($replaceRow = mysql_fetch_array($result)):
               $celebrityInner = $replaceRow['celebrity_name'];
               for($i=0;$i<count($new_array);$i++):
                   $celebrityInner = str_replace($new_array[$i], '<span>'.$new_array[$i].'</span>',$celebrityInner);
                   $celebrityInner = str_replace(strtolower($new_array[$i]), '<span>'.strtolower($new_array[$i]).'</span>',$celebrityInner);
                   $celebrityInner = str_replace(strtoupper($new_array[$i]), '<span>'.strtoupper($new_array[$i]).'</span>',$celebrityInner);
                   $celebrityInner = str_replace(ucfirst($new_array[$i]), '<span>'.ucfirst($new_array[$i]).'</span>',$celebrityInner);
               endfor;
               $resultArray[$count]=array('celebrity_data'=>$replaceRow['celebrity_name'],'celebrity_inner'=>$celebrityInner);
               $count++;
               if($count == 20):
                  break;
               endif; 
         endwhile;     
     
         if($count_rows > 21):
            $moreResults = $count_rows - 20;
            $resultArray[]=array('celebrity_data'=>'seemore','celebrity_inner'=>'SEE MORE RESULTS ('.$moreResults.')');
         endif;
      
         if(count($resultArray) > 0):
            $this->printJson($resultArray);
         endif;       
       endif;
     else:
       $object_picture = $this->getOneRow("SELECT picture_id, picture_parent, picture_big, picture_dir_big FROM __picture WHERE picture_big = '$searchq'");
       if($object_picture):
          $object_id = $object_picture["picture_id"];
          $object_parent = $object_picture["picture_parent"];
          $celebrity_name = $this->getRowValue("SELECT celebrity_name FROM __celebrity WHERE celebrity_parent = $object_parent","celebrity_name");
          $object_picture = "/img/bigs/".$object_picture["picture_dir_big"]."/".$object_picture["picture_big"];
          $object_link = SITE_NAME.'/'.str_replace(' ','_',$celebrity_name).'/'.$object_id.'_'.str_replace(' ','_',$celebrity_name).'_poster.html';
          $resultArray[] = array("picture"=>"picture","object_id"=>$object_id,"celebrity_name"=>$celebrity_name,"object_picture"=>$object_picture,"object_link"=>$object_link);
          return $this->printJson($resultArray);
       else:
          // echo 0;
       endif;
     endif;
 }
 
 public function sendMessage(){
     $name = mysql_real_escape_string($_POST['yName']);
     $topic = mysql_real_escape_string($_POST['tOpic']);
     $email = mysql_real_escape_string($_POST['yEmail']);
     $message = mysql_real_escape_string($_POST['yMess']);
         
     if($topic == 1):
        $topic = 'My Orders';
     elseif($topic == 2):
        $topic = 'Technical';
     else:
        $topic = 'All question';
     endif;
     
     $headers  = 'MIME-Version: 1.0' . "\r\n";
     $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
     $headers .= 'From: '.$email.'<'.$email.'>' . "\r\n";
     $headers .= 'Reply-To: '.$name.' <'.$email.'>' . "\r\n";
     $to = 'globalsetfix@gmail.com';
                  
     if(mail($to,$topic,$message,$headers)):
        echo 1;
     else:
        echo 0;
     endif;
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
}