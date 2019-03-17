<?

class Name extends Mysql {
    
   public $typePage; 

   public $titlePage;
   
   public $categoryUrl;
   public $categoryName;
   public $objectName;
   public $objectName_delim;
   public $objectParent;
   
   public $arraySorting;
   public $fullUrlName;
   
   public $totalItems;
 
   public $metaArray = array();
   public $seoText;
   public $listJsonProducts;
   
   public function __construct(){

   $page_num = '';
   $sorter = '';
   
   // phpinfo();
   // name=Katy-Perry
   // REQUEST_URI /Katy-Perry-posters-and-prints/
   
   if($_GET['sortby'] != ''):
      $sort_num = '| sortby: '.str_replace('_',' ',$_GET['sortby']).' inch';
      $sortid = $_GET['sortby'];
   endif;
   
   $page = (int)$_GET['page'];
   $str_uri = SITE_NAME.$_SERVER['REQUEST_URI'];
   
   // /Emily-Ratajkowski-posters-and-prints/
   $redirect_data = '-posters-and-prints';
   $redirect_url = str_replace('_','-',str_replace(' ','-',$_SERVER['REDIRECT_URL']));
   
   $tmp = str_replace('/','',$redirect_url);
   
   if(!stristr($str_uri, $redirect_data) === FALSE):
      $flag = false;
      $str_redirect = SITE_NAME.$redirect_url;
   else:
      $flag = true;
      $str_redirect = SITE_NAME.'/'.$tmp.$redirect_data.'/';
   endif;
   
   if(!stristr($str_uri, '%20') === FALSE):
      $str_uri = str_replace('%20','-',$str_uri);
      header("HTTP/1.1 301 Moved Permanently");
      header("Location: ".$str_uri."");
      exit;
   endif;
   if(!stristr($str_uri, '_') === FALSE):
      $str_uri = str_replace('_','-',$str_uri);
      header("HTTP/1.1 301 Moved Permanently");
      header("Location: ".$str_uri."");
      exit;
   endif;
   
   if($page > 0):
      // _SERVER["REQUEST_URI"]
      // /Sofia_Vergara/?sortby=old&page=2
      // _SERVER["REDIRECT_URL"]
      // /Sofia_Vergara/  
     if($sortid != ''):
        if($sortid == 'new'):
           header("HTTP/1.1 301 Moved Permanently");
           header("Location: ".$str_redirect."");
           exit;         
        else:
           $str_redirect .= '?sortby='.$sortid;
           header("HTTP/1.1 301 Moved Permanently");
           header("Location: ".$str_redirect."");
           exit;            
        endif;
     else:   
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ".$str_redirect."");
        exit;        
     endif;
   else:
     if($sortid != ''):
        if($sortid == 'new'):
           header("HTTP/1.1 301 Moved Permanently");
           header("Location: ".$str_redirect."");
           exit;
        else:
           if($flag == true):
              $str_redirect .= '?sortby='.$sortid;
              header("HTTP/1.1 301 Moved Permanently");
              header("Location: ".$str_redirect."");
              exit;
           endif; 
        endif;
     else:
      //
     endif;
   endif;
   if($flag == true):
      header("HTTP/1.1 301 Moved Permanently");
      header("Location: ".$str_redirect."");
      exit;    
   endif;
   
   $query_posters = str_replace($redirect_data,'',$_GET["name"]);
   $query_posters = str_replace('-',' ',$query_posters);
   $query_posters = str_replace('_',' ',$query_posters);
   
   // Emily Ratajkowski
   $query_posters = addslashes($query_posters);
   
   // Emily_Ratajkowski
   $url_name = str_replace('-','_',str_replace($redirect_data,'',$_GET["name"]));
   $this->objectName_delim = $url_name;
   
   // Emily-Ratajkowski-posters-and-prints
   $this->fullUrlName = str_replace('_','-',$url_name).$redirect_data; 
 
   $main_sql = "SELECT id,object_parent,object_name,title,description,text,c_id,views FROM __celebrity WHERE active=1 AND object_name='".$query_posters."' LIMIT 1";
   $resArray = $this->getOneRow($main_sql);
   
   // echo count($resArray).' - '.$main_sql;
   // print_r($resArray);
   if(count($resArray) < 2):
      $main_sql = "SELECT id,object_parent,object_name,title,description,text,c_id,views FROM __celebrity WHERE active=1 AND object_dash='".$query_posters."' LIMIT 1";
      $resArray = $this->getOneRow($main_sql);
   endif;
   
   if(count($resArray) > 1):
   
      // $last_modified_time = strtotime($element["time_update"]);
      $object_name = $resArray["object_name"];
      $this->objectParent = $resArray["object_parent"];
      $object_view = $resArray['views']+1;
      $object_id = $resArray["id"];
      $object_category = $resArray["c_id"];
      
      $tmpProducts = $this->getArray('SELECT hash,title FROM __products WHERE visib = 1 ORDER By rating ASC');
      $this->listJsonProducts = array();
      
      foreach($tmpProducts as $item):
              $this->listJsonProducts[] = array('url_product'=>$item['hash'],'product'=>$item['title']);
      endforeach;
      
      $this->listJsonProducts = json_encode($this->listJsonProducts);
    
      $sql_update = "UPDATE object_name SET views = '$object_view' WHERE id = '$object_id'";
      $this->getQuery($sql_update);
      
      // og data
      $object_og_row = $this->getOneRow("SELECT object_picture_banner,folder FROM __picture WHERE object_parent = $this->objectParent AND object_active = 1 ORDER BY object_view DESC LIMIT 1");
      $og_image = SITE_NAME.'/img/bigs/'.$object_og_row['folder'].'/'.$object_og_row['object_picture_banner'];             
      $og_url = $str_uri;
              
      // category data
      $element_category = $this->getOneRow("SELECT name,url FROM __category WHERE id=$object_category");
      $this->categoryUrl = $element_category['url'];
      $this->categoryName = $element_category['name'];
      
      if(!strstr($_SERVER['HTTP_REFERER'],'?sortby')===FALSE):
      
         if(!strstr($_SERVER['HTTP_REFERER'],'&page')===FALSE):
            $this->categoryUrl = $_SERVER['HTTP_REFERER'];
         else:
            $this->categoryUrl = $_SERVER['HTTP_REFERER'];
         endif;
         
      else:
      
         $this->categoryUrl = SITE_NAME.'/'.$this->categoryUrl.'/';
         
      endif;
      
      if($sortid != ''):
      
         $_SESSION['sort'] = $sortid;
         
         if($_SESSION['sort'] == 'new') $_SESSION['sortid'] = 'ORDER BY object_id DESC';
         if($_SESSION['sort'] == 'old') $_SESSION['sortid'] = 'ORDER BY object_id ASC';
         if($_SESSION['sort'] == 'most_popular') $_SESSION['sortid'] = 'ORDER BY object_view DESC';

         if($_SESSION['sort'] == '42x60'): 
            $_SESSION['sortid'] = 'AND (((object_height >= "4000") OR (object_width >= "4000")) OR (object_height >= "2800" AND object_width >= "2800")) ORDER BY object_view ASC';
         endif;

         if($_SESSION['sort'] == '36x56'):
            $_SESSION['sortid'] = 'AND (((object_height >= "3800") OR (object_width >= "3800")) OR (object_height >= "2400" AND object_width >= "2400")) ORDER BY object_view ASC';
         endif;

         if($_SESSION['sort'] == '32x46'): 
            $_SESSION['sortid'] = 'AND (((object_height >= "3200") OR (object_width >= "3200")) OR (object_height >= "2200" AND object_width >= "2200")) ORDER BY object_view ASC';
         endif;
      
         if($_SESSION['sort'] == '24x36'): 
            $_SESSION['sortid'] = 'AND (((object_height >= "2400") OR (object_width >= "2400")) OR (object_height >= "1600" AND object_width >= "1600")) ORDER BY object_view ASC';
         endif;
      
         if($_SESSION['sort'] == '18x24'):
            $_SESSION['sortid'] = 'AND (((object_height >= "1500") OR (object_width >= "1500")) OR (object_height >= "1100" AND object_width >= "1100")) ORDER BY object_view ASC';
         endif;
         
         $canonical = SITE_NAME.'/'.$this->fullUrlName.'/';
         
      else:
      
         if($sortid == ''):
            $_SESSION['sort'] = 'new';
            $_SESSION['sortid'] = 'ORDER BY object_id DESC';
            $this->seoText = $resArray["text"];
            $canonical = NULL;             
         endif;
         
      endif;
  
      $count_items = $this->getRowValue("SELECT COUNT(*) as count FROM __picture WHERE object_parent=$this->objectParent AND object_active=1","count");
      $this->totalItems = $this->getRowValue("SELECT COUNT(*) as count FROM __picture WHERE object_parent=$this->objectParent AND object_active=1 ".$_SESSION['sortid'],"count");
      
      $array_sorting = array();
      $array_sorting[] = array('name'=>'NEW','value'=>'new','count'=>$count_items,'type'=>'sort');
      $array_sorting[] = array('name'=>'OLD','value'=>'old','count'=>$count_items,'type'=>'sort');
      $array_sorting[] = array('name'=>'POPULAR','value'=>'most-popular','count'=>$count_items,'type'=>'sort');
      
      // 18x24
      $s = "SELECT object_id FROM __picture WHERE object_parent=$this->objectParent AND (((object_height >= 1500) OR (object_width >= 1500)) OR (object_height >= 1100 AND object_width >= 1100))";
      $r = mysql_query($s);
      if(mysql_num_rows($r)>0):
         $n=mysql_num_rows($r);
         $array_sorting[] = array("name"=>"18x24","value"=>"18x24","count"=>$n,"type"=>"filt");
      endif;
           
      // 24x36
      $s = "SELECT object_id FROM __picture WHERE object_parent=$this->objectParent AND (((object_height >= 2400) OR (object_width >= 2400)) OR (object_height >= 1600 AND object_width >= 1600))";
      $r = mysql_query($s);if(mysql_num_rows($r)>0){$n=mysql_num_rows($r);$array_sorting[] = array("name"=>"24x36","value"=>"24x36","count"=>$n,"type"=>"filt");}
      // 32x46
      $s = "SELECT object_id FROM __picture WHERE object_parent=$this->objectParent AND (((object_height >= 3200) OR (object_width >= 3200)) OR (object_height >= 2200 AND object_width >= 2200))";
      $r = mysql_query($s);if(mysql_num_rows($r)>0){$n=mysql_num_rows($r);$array_sorting[] = array("name"=>"32x46","value"=>"32x46","count"=>$n,"type"=>"filt");}  
      // 36x56
      $s = "SELECT object_id FROM __picture WHERE object_parent=$this->objectParent AND (((object_height >= 3800) OR (object_width >= 3800)) OR (object_height >= 2400 AND object_width >= 2400))";
      $r = mysql_query($s);if(mysql_num_rows($r)>0){$n=mysql_num_rows($r);$array_sorting[] = array("name"=>"36x56","value"=>"36x56","count"=>$n,"type"=>"filt");}  
      // 42x60
      $s = "SELECT object_id FROM __picture WHERE object_parent=$this->objectParent AND (((object_height >= 4000) OR (object_width >= 4000)) OR (object_height >= 2800 AND object_width >= 2800))";
      $r = mysql_query($s);if(mysql_num_rows($r)>0){$n=mysql_num_rows($r);$array_sorting[] = array("name"=>"42x60","value"=>"42x60","count"=>$n,"type"=>"filt");}
 
      $string_sizes = '';
      $this->arraySorting = array_reverse($array_sorting);

      $t=0;
      foreach($array_sorting as $item):
         if($item['type']=='filt'):
            $t++;
         endif;
      endforeach;
      if($t>0):
         $d=0;
         foreach($array_sorting as $item):
            if($item['type']=='filt'):
               $d++;
               $delim = ($d == $t) ? '' : ', ';
               $string_sizes .= $item['name'].$delim;
            endif;
         endforeach;
         $sizes_description = ' (Photo printing size: '.$string_sizes.' inches)';
      endif;
 
      $items = ($n>1) ? 'items' : 'item';
      $all_items = ($sort_num != '') ? $n : $count_items; 
      $all_items = ' : '.$all_items.' '.$items;
 
      if($element["title"]==''):
         $title = $object_name.' posters and prints at idPoster.com '.$sort_num.$all_items;
      else:
         $title = $element["title"].' '.$sort_num.$all_items;
      endif;
      
      if($element["description"]!=''):
         $description = $element["description"].' '.$sort_num.$all_items;
      else:
         $description = $object_name.' posters and prints at IdPoster.com. Buy '.$object_name.' posters'.$sizes_description.', photos, puzzles, mousepads, magnets, t-shirts, pillows, images, mugs, cases for iPhone, iPad, Samsung '.$sort_num.$all_items;
      endif;
      
      $keywords = $object_name.', posters, prints';
      $this->objectName = $object_name;
      $sort_size = ($_SESSION['sort'] != 'new' AND $_SESSION['sort'] != 'old' AND $_SESSION['sort'] != 'most-popular') ? '('.$_SESSION['sort'].') ' : '';
      
      $this->titlePage = $object_name.' posters and prints : '.$sort_size.$this->totalItems.' items';      
      $this->typePage = 'name';
      
      $this->metaArray[] = $title;
      $this->metaArray[] = $description;
      $this->metaArray[] = $keywords;
      
      $this->metaArray[] = $og_image;
      $this->metaArray[] = $og_url;
      $this->metaArray[] = 'index, follow';
      
      if($canonical):
         $this->metaArray[] = $canonical;
      endif;
      
   else:
      $this->typePage = '404';
   endif;
   
   }
}