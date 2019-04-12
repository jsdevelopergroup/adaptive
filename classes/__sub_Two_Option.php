<?

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
                          
                          ?>