<?

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
          
       //   echo $sql;
          ?>