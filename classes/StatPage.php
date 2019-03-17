<?

class StatPage {
    
    
    public $titlePage;
    
    
    public $metaArray;
        
    public $typePage;
    
    public $sitemapsLiks;
    
    public function __construct($mysql){
        
        
           $page = mysql_real_escape_string($_GET['static']);
        
           $staticRecord = $mysql->getOneRow("SELECT * FROM category WHERE url = '$page' LIMIT 1");
           
           $this->dataPage = $staticRecord['category_text'];
           
           if($this->dataPage):
              $this->typePage = 'static';
           else:
           
              if($page == 'contact-us'):
              
              
                 $this->typePage = 'contact-us';
              endif;
           
           
              if($page == 'sitemap'):
              
              
                 $this->typePage = 'sitemap';
              endif;          
           
            
           endif;
    }
    
    public function getSiteMapLinks($site){
        $flag_p = false;
        $flag_c = false;
        $categories = array();
        $letters = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z"); 
        $statics = $this->GetArray("SELECT category_name,url FROM category WHERE active = 1");
        $products = $this->GetArray("SELECT product,url_product FROM products WHERE active = 1 ORDER BY rating ASC");
        $alps_array = array(1,3,2);
        
          // CATEGORIES
          $categories[] = '<div class="title_section">Our categories</div>';
          $menus = $this->GetArray("SELECT id,url,c_id FROM menu WHERE active = 1 AND id < 10");
             foreach($menus as $item):
                  $link = $site.$item['url'];
                  $ankor = ucfirst(str_replace('/','',str_replace('-',' ',$item['url']))); 
                  $categories[] = '<li class="on-lev"><a href="'.$site.$item['url'].'">'.$ankor.'</a></li>';
                  $names = $this->GetArray("SELECT object_name,object_parent,c_id FROM object_name WHERE c_id = '".$item['c_id']."' ORDER BY id DESC LIMIT 5");
                    foreach($names as $item):
                            $uri_name = $site.'/'.str_replace(' ','_',$item['object_name']).'/';
                            $categories[] = '<li class="tw-lev"><a href="'.$uri_name.'">'.$item['object_name'].' posters and prints</a></li>';
                            if($item['c_id'] == 2):
                               if($flag_p == false):
                                  $object_id = $this->getRowValue("SELECT object_id FROM object WHERE object_parent = '".$item['object_parent']."' ORDER By object_id DESC LIMIT 1","object_id");
                                  foreach($products as $items):
                                          $categories[] = '<li class="th-lev"><a href="'.$uri_name.$object_id.'_'.$item['object_name'].'_'.$items['url_product'].'.html">'.$item['object_name'].' '.$items['product'].' #'.$object_id.'</a></li>';
                                  endforeach;
                                  $flag_p = true;
                               endif;      
                            endif;
                    endforeach;
             endforeach;
          $flag_p = false;
             
          // NEW PRODUCTS  // до 10 находятся категории с именами только
          $categories[] = '<div class="title_section">Last products</div>';
          $categories[] = '<li class="on-lev"><a href="'.$site.'/new-posters/">New posters and prints</a></li>';
             $news = $this->GetArray("SELECT DISTINCT object_parent FROM object ORDER BY object_id DESC LIMIT 10");
             foreach($news as $itemnew):
                     $object_name = $this->getname($itemnew['object_parent']);
                     $links[] = array('object_name'=>$object_name,'object_parent'=>$itemnew['object_parent']);
             endforeach;
             foreach($links as $item):
                     $categories[] = '<li class="tw-lev"><a href="'.$site.'/'.$item['object_name'].'/">'.str_replace('_',' ',$item['object_name']).' posters and prints</a></li>';
                      if($flag_p == false):
                         $object_id = $this->getRowValue("SELECT object_id FROM object WHERE object_parent = '".$item['object_parent']."' ORDER By object_id DESC LIMIT 1","object_id");
                           foreach($products as $items):
                                   $uri_name = $site.'/'.$item['object_name'].'/'.$object_id.'_'.str_replace(' ','_',$item['object_name']).'_'.$items['url_product'].'.html';
                                   $categories[] = '<li class="th-lev"><a href="'.$uri_name.'">'.str_replace('_',' ',$item['object_name']).' '.$items['product'].' #'.$object_id.'</a></li>';
                           endforeach;
                         $flag_p = true;
                      endif;             
             endforeach;
          $flag_p = false;
             
          // CUSTOM PRINTING AND PHONE CASES
          $categories[] = '<div class="title_section">Custom products</div>';
          $categories[] = '<li class="on-lev"><a href="'.$site.'/custom-printing/">Custom printing</a></li>';
          $categories[] = '<li class="on-lev"><a href="'.$site.'/create-cases/">Create your own phone case</a></li>';
            $customs = $this->GetArray("SELECT title,url FROM customs");
               foreach($customs as $item):
                       $uri_name = $site.'/create-cases/'.$item['url'].'/';
                       $categories[] = '<li class="tw-lev"><a href="'.$uri_name.'">'.$item['title'].'</a></li>';
               endforeach;
          $flag_p = false;
                          
          // TOP 20    
          $top20 = $this->GetArray("SELECT object_id,object_name FROM home_products");
          $categories[] = '<div class="title_section">TOP 30 most popular celebrity posters and prints</div>';
          foreach($top20 as $item):
                  $url_name = str_replace(' ','_',$item['object_name']);
                  $url_full = $site.'/'.$url_name.'/';
                  $categories[] = '<li class="tw-lev"><a href="'.$url_full.'">'.$item['object_name'].' posters and prints</a></li>';
                     if($flag_p == false):
                        foreach($products as $items):
                                $categories[] = '<li class="th-lev"><a href="'.$url_full.$item['object_id'].'_'.$url_name.'_'.$items['url_product'].'.html">'.$item['object_name'].' '.$items['product'].' #'.$item['object_id'].'</a></li>';
                        endforeach;
                        $flag_p = true;
                     endif;           
          endforeach;
          $flag_p = false;
          
          // ALPHABET PAGES
          foreach($alps_array as $item):
                  $count_pages = 0;
                  $category_url = $this->getcategory($item);
                  $category_name = ucfirst(str_replace('-',' ',$category_url));
                  //$category_total = $this->getRowValue("SELECT COUNT(*) as count FROM object_name WHERE c_id = '$item'","count");
                  $categories[] = '<div class="title_section">Full '.$category_name.' catalog</div>';
                  $counter = 0;
           
                  for($i=0;$i<count($letters);$i++):
                      $sql = "SELECT object_name,object_parent FROM object_name WHERE object_name LIKE '".$letters[$i]."%' AND active = 1 AND c_id = '$item'";
                      $res = mysql_query($sql);
                      $total = mysql_num_rows($res);
                      $pages = ceil($total / 100);
                      $count_pages += $pages;                 
                  endfor;
              
                  $count_pages = $count_pages + 11;
                  $tmp = ceil($count_pages / 3);
                  
                  for($i=0;$i<count($letters);$i++):
                      $sql = "SELECT object_name,object_parent FROM object_name WHERE object_name LIKE '".$letters[$i]."%' AND active = 1 AND c_id = '$item'";
                      $res = mysql_query($sql);
                      $total = mysql_num_rows($res);
                      $pages = ceil($total / 100);
                     
                        for($j=1;$j<=$pages;$j++):
                             $counter++;
                             
                             $link = $site.'/'.$category_url.'/Posters_'.strtolower($letters[$i]).$j.'/';
                             
                             if($counter == 1):
                                $categories[] = '<div class="column-third">';
                             endif;
                             
                             $categories[] = '<li class="ft-lev"><a href="'.$link.'">'.$category_name.' "'.strtoupper($letters[$i]).'" page '.$j.'</a></li>';
                             
                             if(($counter % $tmp) == 0):
                                 $categories[] = '</div><div class="column-third">';
                             endif;
                             
                             if($counter == $count_pages):
                                $categories[] = '</div>';
                             endif;
                             
                             
                              if($flag_c == false):  
                                 while($row = mysql_fetch_array($res)):
                                      $uri_name = $site.'/'.str_replace(' ','_',$row['object_name']).'/';
                                      $categories[] = '<li class="tw-lev"><a href="'.$uri_name.'">'.$row['object_name'].' posters and prints</a></li>';
                                      $counter++;
                                          if($flag_p == false):
                                             $object_id = $this->getRowValue("SELECT object_id FROM object WHERE object_parent = '".$row['object_parent']."' ORDER By object_id DESC LIMIT 1","object_id");
                                             foreach($products as $items):
                                                     $pro_url = $site.'/'.str_replace(' ','_',$row['object_name']).'/'.$object_id.'_'.str_replace(' ','_',$row['object_name']).'_'.$items['url_product'].'.html';
                                                     $categories[] = '<li class="th-lev"><a href="'.$pro_url.'">'.$row['object_name'].' '.$items['product'].' #'.$object_id.'</a></li>';
                                                     $counter++;
                                             endforeach;
                                             $flag_p = true;
                                          endif;   
                                  $flag_c = true;
                                  break;
                                endwhile;
                              endif;  
                         endfor;
                      unset($res); 
                  endfor;
                 // echo $ns.'<br>';
                  $flag_c = false;
                  $flag_p = false;
         endforeach;
         // STATIC PAGES
         $categories[] = '<div class="title_section">Information</div>';
         foreach($statics as $item):
                 $link = $site.$item['url'];
                 if($ankor == '')$ankor = 'Home';
                 
                 if($item['url'] == 'sitemap'):
                   // $categories[] = '<spam class="ft-lev">'.$item['category_name'].'</span>';
                 else:
                    $categories[] = '<li class="ft-lev"><a href="'.$site.'/'.$item['url'].'/">'.$item['category_name'].'</a></li>';
                 endif;
                 
                 
         endforeach;
         $categories[] = '<li class="ft-lev"><a href="https://www.facebook.com/pages/IDposter/167053363358973">Facebook</a></li>';
         return $categories;    
    }

    
}