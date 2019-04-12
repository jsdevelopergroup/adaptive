<?

class Products extends Mysql {
    
    public $productsList;
    public $propertyList;
    public $optionList;
    public $propertyData;
    public $optionData;
    
    public $checkedDef;
    public $checkedVisib;
    
    public $optionsListOne;
    public $optionsListTwo;
  
    
    public $optionsTitleOne;
    public $optionsTitleTwo;
 
    public function __construct(){
        
           $product_id = (int)$_GET['product'];
        
           /*
           if($_GET['mod']=='options' || $_GET['mod']=='prices') 
           else $this->productsList = $this->getArray("SELECT `id`,`title` FROM `__products` ORDER By `rating` ASC");
           */
           
           $this->productsList = $this->getArray("SELECT `id`,`title`,`rating` FROM `__products` WHERE `visib` = 1 ORDER By `rating` ASC");
           $this->propertyList = $this->getArray("SELECT * FROM `__property` WHERE `product` = $product_id ORDER By rating ASC");
           
           $title = (isset($_POST['title'])) ? mysql_real_escape_string($_POST['title']) : '';
           $description = (isset($_POST['description'])) ? mysql_real_escape_string($_POST['description']) : '';
           $rating = (isset($_POST['rating'])) ? mysql_real_escape_string($_POST['rating']) : '';
           $visib = ($_POST['visib'] == 1) ? 1 : 0;
           
           $property_id = (int)$_GET['property'];
           
           // ADD PROPERTY 1
           if(isset($_POST['done_property']) AND empty($_GET['property'])):
              $sql = "INSERT INTO  `__property` (
                     `id`,
                     `product`,
                     `title`,
                     `description`,
                     `rating`,
                     `visib`
                     ) VALUES (
                     NULL,  
                     $product_id,  
                     '$title',
                     '$description',
                     $rating,
                     $visib)";
              if($this->getQuery($sql)):
                 $this->redirect('add');
              endif;
           endif;
            
           // EDIT PROPERTY 1-2
           if(isset($_POST['done_property']) AND $property_id != 0):
              $sql = "UPDATE `__property` SET  `title` =  '$title',
                     `description` =  '$description',
                     `rating` =  $rating,
                     `visib` =  $visib 
                      WHERE  
                     `__property`.`id` = $property_id";
              if($this->getQuery($sql)):
                 $this->redirect('edit');
              endif;
           endif;
            
           if($property_id != 0):
              $this->propertyData = $this->getOneRow("SELECT * FROM `__property` WHERE `id` = $property_id AND `product` = $product_id LIMIT 1");
              $this->checked = ($this->propertyData['visib'] == 1) ? 'checked' : '';
           endif;
           
           // ADD OPTION 2
           if($_GET['mod'] == 'options'):
              $this->propertyList = $this->getArray("SELECT * FROM `__property` WHERE `product` = $product_id AND `visib` = 1 ORDER By rating ASC");
              
              if(isset($_GET['product']) AND isset($_GET['property'])):
                
                 $property_id = (int)$_GET['property'];                        
                 $sql = "SELECT * FROM `__option` WHERE `product` = $product_id AND `property` = $property_id ORDER By rating ASC";
                 $this->optionList = $this->getArray($sql);
                 $this->checkedDef = ($_POST['bydef'] == 1) ? 1 : 0;
                  
                 // &mod=options&product=1&property=1
                 if(isset($_POST['done_option']) and empty($_GET['option'])):
                    $sql = "INSERT INTO  `__option` (
                           `id` ,
                           `product` ,
                           `property` ,
                           `title` ,
                           `rating` ,
                           `visib`,
                           `bydef` ) VALUES (NULL, $product_id, $property_id, '$title', $rating, $visib, $this->checkedDef)";
                    if($this->getQuery($sql)):
                       $this->redirect('add');
                    endif;              
                 endif;
              
                            
                 if(isset($_GET['option'])):
                 
                    $option_id = (int)$_GET['option'];
                    $this->optionData = $this->getOneRow("SELECT `title`,`rating`,`visib`,`bydef` FROM `__option` WHERE `id`=$option_id AND `product` = $product_id AND `property` = $property_id LIMIT 1"); 
                 
                    $this->checkedDef = ($this->optionData['bydef'] == 1) ? 'checked' : '';
                    $this->checkedVisib = ($this->optionData['visib'] == 1) ? 'checked' : '';
                    // EDIT OPTION
                    if(isset($_POST['done_option'])):
                    
                       if($_POST['bydef'] == 1):
                          $this->getQuery("UPDATE `__option` SET `bydef` = NULL WHERE `__option`.`property` = $property_id");
                          $sql = "UPDATE  `idposter_new`.`__option` SET  
                                       `title` = '$title', 
                                       `rating` = '$rating',
                                       `visib` = '$visib',
                                       `bydef` = 1  
                                        WHERE `__option`.`id` = $option_id";
                       else:
                          $sql = "UPDATE  `__option` SET  
                                       `title` = '$title', 
                                       `rating` = '$rating',
                                       `visib` = '$visib',
                                       `bydef` = 0
                                        WHERE `__option`.`id` = $option_id";                       
                       endif;
                    
                       if($this->getQuery($sql)):
                          $this->redirect('edit');
                       endif;  

                    endif;
                 endif;
              endif;
           endif;
           
           // ADD PRICE 3
           if($_GET['mod'] == 'prices'):
              if(isset($_GET['product'])):
              
                 $dataOne = $this->getOneRow("SELECT * FROM `__property` WHERE `product` = $product_id AND `rating` = 1 AND `visib` = 1 LIMIT 1");
                 $dataTwo = $this->getOneRow("SELECT * FROM `__property` WHERE `product` = $product_id AND `rating` = 2 AND `visib` = 1 LIMIT 1");
                 
                 $this->optionsTitleOne = $dataOne['title'];
                 $this->optionsTitleTwo = $dataTwo['title'];
                 
                 $propertyOneId = $dataOne['id'];
                 $propertyTwoId = $dataTwo['id'];
                 
                 $this->optionsListOne = $this->getArray("SELECT * FROM `__option` WHERE `product` = $product_id AND `property` = $propertyOneId AND `visib` = 1 ORDER By `rating` ASC");
                 $this->optionsListTwo = $this->getArray("SELECT * FROM `__option` WHERE `product` = $product_id AND `property` = $propertyTwoId AND `visib` = 1 ORDER By `rating` ASC");
           
                 if(($_POST['optionsOne']!=0) AND ($_POST['optionsTwo']!=0)):
                     $one = $_POST['optionsOne'];
                     $two = $_POST['optionsTwo'];
                     $price = $_POST['price'];
              
                     if($this->getCount("SELECT * FROM __price WHERE product = $product_id AND one = $one AND two = $two")>0):
                        
                        if($price < 0.1):
                           $sql = "DELETE FROM `__price` WHERE `product`='$product_id' AND `one`='$one' AND `two`='$two'";
                        else:
                           $sql = "UPDATE `__price` SET `price`='$price' WHERE `product`='$product_id' AND `one`='$one' AND `two`='$two'";
                        endif;
                        
                     else:
                        $sql = "INSERT INTO `__price` (`id`,`product`,`one`,`two`,`price`) VALUES (NULL,'$product_id','$one','$two','$price')";
                     endif;
                     if($this->getQuery($sql)):
                        $this->redirect('add');
                     endif;  
                 endif;
               endif;
           endif;
    } 
   
    public function getPrice($product_id,$one,$two){
           return $this->getRowValue("SELECT `price` FROM `__price` WHERE `product` = $product_id AND `one` = $one AND `two` = $two","price");
    }
   
    public function redirect($msg){
           $currentUrl = str_replace('&result=add','',ADMIN_DOMAIN.$_SERVER['REQUEST_URI']);
           $currentUrl = str_replace('&result=edit','',ADMIN_DOMAIN.$_SERVER['REQUEST_URI']);
           header('Location: '.$currentUrl.'&result='.$msg);
    }
}