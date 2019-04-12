<?

 if(($product_id == 1 OR $product_id == 2) AND $n == 1):
                     // ме оноюдюер онд дхюоюгнм
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
                      // оноюдюер онд дхюоюгнм
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

?>