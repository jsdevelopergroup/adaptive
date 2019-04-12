<?

class StaticPage extends Mysql {
    
    public $titlePage;
    public $metaArray;
        
    public $typePage;
    public $dataPage;
    public $sitemapsLiks;
    
    public function __construct(){
        
           $page = mysql_real_escape_string($_GET['static']);
           $staticRecord = $this->getOneRow("SELECT * FROM __category WHERE category_url = '$page' AND category_active = 1 LIMIT 1");
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
    
    public function getName($celebrityParent){
        $sql = "SELECT celebrity_name FROM __celebrity WHERE celebrity_parent = '$objectParent' LIMIT 1";
        $celebrityName = $this->getRowValue($sql,'celebrity_name');
        return str_replace(' ','_',$celebrityName);     
    }
    
  
    public function getSiteMapLinks(){
        
           $site = SITE_NAME;
        
           $flag_p = false;
           $flag_c = false;
           $categories = array();
           //$letters = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z"); 
           $statics = $this->getArray("SELECT category_url,category_name FROM __category WHERE category_type = 2 AND category_active = 1 ORDER By category_rating ASC");
           $products = $this->getArray("SELECT title,hash FROM __products WHERE visib = 1 ORDER BY rating ASC");
           
           $categories[] = '<div class="title_section">Our categories</div>';
           $menus = $this->GetArray("SELECT category_id,category_url,category_name,category_tail FROM __category WHERE category_id < 14 AND category_active = 1 ORDER By category_rating ASC"); #OK
               foreach($menus as $item):
                  $link = $site.$item['url'];
                  //$ankor = ucfirst(str_replace('/','',str_replace('-',' ',$item['url']))); 
                  $categories[] = '<li class="on-lev"><a href="'.$site.'/'.$item['category_url'].'/">'.$item['category_name'].' '.$item['category_tail'].'</a></li>';
                  $names = $this->getArray("SELECT celebrity_name,celebrity_parent,celebrity_category_id FROM __celebrity WHERE celebrity_category_id = '".$item['category_id']."' ORDER BY celebrity_id DESC LIMIT 5"); #OK
                    foreach($names as $item):
                            
                            $uri_name = $site.'/'.str_replace(' ','_',$item['celebrity_name']).'/';
                            $categories[] = '<li class="tw-lev"><a href="'.$uri_name.'">'.$item['celebrity_name'].' posters and prints</a></li>';
                            
                            if($item['c_id'] == 2):
                               if($flag_p == false):
                               
                                  $object_id = $this->getRowValue("SELECT object_id FROM __picture WHERE object_parent = '".$item['object_parent']."' ORDER By object_id DESC LIMIT 1","object_id");#OK
                                
                                  foreach($products as $items):
                                          $categories[] = '<li class="th-lev"><a href="'.$uri_name.$object_id.'_'.$item['object_name'].'_'.$items['hash'].'.html">'.$item['object_name'].' '.$items['title'].' #'.$object_id.'</a></li>';
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
             $news = $this->getArray("SELECT DISTINCT picture_parent FROM __picture ORDER BY picture_id DESC LIMIT 10");
             foreach($news as $itemnew):
                     $celebrity_name = $this->getName($itemnew['picture_parent']);
                     $links[] = array('celebrity_name'=>$celebrity_name,'picture_parent'=>$itemnew['picture_parent']);
             endforeach;
             foreach($links as $item):
                     $categories[] = '<li class="tw-lev"><a href="'.$site.'/'.$item['celebrity_name'].'/">'.str_replace('_',' ',$item['celebrity_name']).' posters and prints</a></li>';
                      if($flag_p == false):
                         $picture_id = $this->getRowValue("SELECT picture_id FROM __picture WHERE picture_parent = '".$item['picture_parent']."' ORDER By picture_id DESC LIMIT 1","picture_id");
                           foreach($products as $items):
                                   $uri_name = $site.'/'.$item['celebrity_name'].'/'.$picture_id.'_'.str_replace(' ','_',$item['celebrity_name']).'_'.$items['hash'].'.html';
                                   $categories[] = '<li class="th-lev"><a href="'.$uri_name.'">'.str_replace('_',' ',$item['celebrity_name']).' '.$items['title'].' #'.$picture_id.'</a></li>';
                           endforeach;
                         $flag_p = true;
                      endif;             
             endforeach;
           $flag_p = false;
             
           // CUSTOM PRINTING AND PHONE CASES
           /*
           $categories[] = '<div class="title_section">Custom products</div>';
           $categories[] = '<li class="on-lev"><a href="'.$site.'/custom-printing/">Custom printing</a></li>';
           $categories[] = '<li class="on-lev"><a href="'.$site.'/create-cases/">Create your own phone case</a></li>';
            $customs = $this->GetArray("SELECT title,url FROM customs");
               foreach($customs as $item):
                       $uri_name = $site.'/create-cases/'.$item['url'].'/';
                       $categories[] = '<li class="tw-lev"><a href="'.$uri_name.'">'.$item['title'].'</a></li>';
               endforeach;
           $flag_p = false;*/
                          
           // TOP 30    
           $top20 = $this->GetArray("SELECT home_celebrity,home_picture_id FROM __home_celebrity LIMIT 30");
           $categories[] = '<div class="title_section">TOP 30 most popular celebrity posters and prints</div>';
           foreach($top20 as $item):
                   $url_name = str_replace(' ','_',$item['home_celebrity']);
                   $url_full = $site.'/'.$url_name.'/';
                   $categories[] = '<li class="tw-lev"><a href="'.$url_full.'">'.$item['home_celebrity'].' posters and prints</a></li>';
                     if($flag_p == false):
                        foreach($products as $items):
                                $categories[] = '<li class="th-lev"><a href="'.$url_full.$item['home_picture_id'].'_'.$url_name.'_'.$items['hash'].'.html">'.$item['home_celebrity'].' '.$items['title'].' #'.$item['home_picture_id'].'</a></li>';
                        endforeach;
                        $flag_p = true;
                     endif;           
           endforeach;
           $flag_p = false;
          
           // STATIC PAGES
           $categories[] = '<div class="title_section">Information</div>';
           foreach($statics as $item):
                  // $link = $site.$item['static_url'];
                   if($ankor == '')$ankor = 'Home';
                 
                   if($item['category_url'] == 'sitemap'):
                   // $categories[] = '<spam class="ft-lev">'.$item['category_name'].'</span>';
                   else:
                      $categories[] = '<li class="ft-lev"><a href="'.$site.'/'.$item['category_url'].'/">'.$item['category_name'].'</a></li>';
                   endif;
           endforeach;
           $categories[] = '<li class="ft-lev"><a href="https://www.facebook.com/pages/IDposter/167053363358973">Facebook</a></li>';
           return $categories;    
    }
}