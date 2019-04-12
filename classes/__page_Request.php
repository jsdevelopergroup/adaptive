<?

   
        $product = mysql_real_escape_string($_GET['product_id']);
        
        // POSTER OR CALENDAR OR CANVAS
        if($product == 'poster' OR $product == 'calendar' OR $product == 'canvas'):
        
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
           
       //    echo $sqlCurrentMaxOptionId; 
           
           
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
                     AND __option.bydef = 1 ORDER BY __property.rating ASC"; 
           endif;
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
                  AND __products.id = __property.product 
                  AND __property.visib = 1
                  AND __option.product = __property.product
                  AND __option.property = __property.id 
                  AND __option.bydef = 1 ORDER BY __property.rating ASC";             
         endif;     
     ?>