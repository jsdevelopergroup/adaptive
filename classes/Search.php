<?
class Search extends Mysql{
    
    public $typePage;
    public $categoryUrl;
    public $perPage = 24;
    
    public $categoryH1;
    public $arraySorting = array();
    
    public $metaArray = array();
    public $categoryNames = array();
    public $sortVar = '&';
    public $categoryBreadName; 
    public $end;
    public $page;
    public $sortby;
    public $count;
    public $delim;
    public $img404 = '/icons/image404.gif';
        
    public function __construct(){
        
        $searchq = str_replace('+',' ',$_GET["searchQuery"]);
        $searchq = mysql_real_escape_string($searchq);
        
        $searchqUrl = str_replace(' ','+',$searchq);
        $this->categoryUrl = '?searchQuery='.$searchqUrl;
        
        $tmp_array = explode(' ',$searchq);
        $new_array = array();
      
        if(count($tmp_array)>0):
           $countWords=0;
           for($i=0;$i<count($tmp_array);$i++):
               if(strlen($tmp_array[$i])>1):
                  $new_array[]=$tmp_array[$i];
                  $countWords++;
               endif;
           endfor;
        endif;        
        
        $this->page = (isset($_GET["page"])) ? (int)$_GET["page"] : 1;
        $start = ($this->page == 1) ? 0 : ($this->page - 1) * $this->perPage;
           
        if(isset($_GET["sortby"])):
           $_SESSION['sort'] = $_GET["sortby"];
           if($_SESSION['sort'] == 'new') $_SESSION['sortid'] = 'ORDER BY celebrity_id DESC';
           if($_SESSION['sort'] == 'old') $_SESSION['sortid'] = 'ORDER BY celebrity_id ASC';
           if($_SESSION['sort'] == 'a_z') $_SESSION['sortid'] = 'ORDER BY celebrity_name ASC';
           if($_SESSION['sort'] == 'z_a') $_SESSION['sortid'] = 'ORDER BY celebrity_name DESC';
           if($_SESSION['sort'] == 'popular') $_SESSION['sortid'] = 'ORDER BY celebrity_view DESC';
           $this->sortby = mysql_real_escape_string($_GET["sortby"]);
        else:
           $_SESSION['sort'] = 'new';
           $_SESSION['sortid'] = 'ORDER BY celebrity_id DESC';
           $this->sortby = $_SESSION['sort'];
        endif;
           
        $this->categoryBreadName = 'Search';
        $sql = $this->getSearchSql($searchq);
        $this->count = $this->getCount($sql);
        $this->end = ceil($this->count / $this->perPage);
        $this->delim = '&'; 
           
        if($this->count==1):
           $records = 'record';
        else:
           $records = 'records';
        endif;
         
        $this->categoryH1 = 'Search query  :  &laquo; <span>'.$searchq.'</span> &raquo; found ('.$this->count.') '.$records;
           
        if($this->count > 0):
           $sql .= " ".$_SESSION['sortid']." LIMIT $start, $this->perPage";
           $currentArray = $this->getArray($sql);
           $c = 0;
           foreach($currentArray as $item):
                   $imgPath = './img/bigs/'.$item['object_folder'].'/'.$item['object_image'];
                   $this->categoryNames[$c]['img_src'] = (file_exists($imgPath)) ? SITE_NAME.'/'.$imgPath : $this->img404;
                   $this->categoryNames[$c]['a_link'] = SITE_NAME.'/'.str_replace(' ','_',$item['celebrity_name']).'/';
                   $this->categoryNames[$c]['a_title'] = $item['celebrity_name'].' posters and prints';
                   $this->categoryNames[$c]['a_alt'] = $item['celebrity_name'].' posters and prints';
                   $celebrityInner = $item['celebrity_name'];
                   for($i=0;$i<count($new_array);$i++):
                       $celebrityInner = str_replace($new_array[$i], '<span>'.$new_array[$i].'</span>',$celebrityInner);
                       $celebrityInner = str_replace(strtolower($new_array[$i]), '<span>'.strtolower($new_array[$i]).'</span>',$celebrityInner);
                       $celebrityInner = str_replace(strtoupper($new_array[$i]), '<span>'.strtoupper($new_array[$i]).'</span>',$celebrityInner);
                       $celebrityInner = str_replace(ucfirst($new_array[$i]), '<span>'.ucfirst($new_array[$i]).'</span>',$celebrityInner);
                   endfor;
                   $this->categoryNames[$c]['span_title'] = '<b>'.$celebrityInner.'</b><br /> posters and prints';
                   $c++;
           endforeach;
           $this->arraySorting[] = array('name'=>'NEW','value'=>'new');
           $this->arraySorting[] = array('name'=>'OLD','value'=>'old');
           $this->arraySorting[] = array('name'=>'A-Z','value'=>'a_z');
           $this->arraySorting[] = array('name'=>'Z-A','value'=>'z_a');
           $this->arraySorting[] = array('name'=>'POPULAR','value'=>'popular');
           $this->typePage = 'category';
        else:
           $this->typePage = '404';
        endif;
    }
    
    public function getSearchSql($searchq){
      
        $sqlRea = "SELECT celebrity_name FROM __celebrity WHERE celebrity_name = '".$searchq."' AND celebrity_active = 1";
        $res = mysql_query($sqlRea);
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
      
        $mainBodySql = "SELECT celebrity_name,celebrity_parent as parent, 
                     (SELECT picture_big FROM __picture WHERE picture_parent = parent ORDER BY picture_view DESC LIMIT 1) as object_image, 
                     (SELECT picture_dir_big FROM __picture WHERE picture_parent = parent ORDER BY picture_view DESC LIMIT 1) as object_folder
                      FROM __celebrity WHERE celebrity_active = 1 AND "; 
      
        if($counts_names > 0):
        
           if($countWords == 1):
                 
              $sqlTwoFoun = "SELECT celebrity_name FROM __celebrity WHERE celebrity_name LIKE '%".$searchq."%'";      
              $countRowsTwo = $this->getCount($sqlTwoFoun);                  
              if($countRowsTwo > 0):
                 $sql = $mainBodySql."(celebrity_name = '".$searchq."' OR celebrity_name LIKE '%".$searchq."%')";                   
              endif;
                  
              $sqlOneFoun = "SELECT celebrity_name FROM __celebrity WHERE celebrity_name LIKE '".$searchq."%'";      
              $countRowsOne = $this->getCount($sqlOneFoun);
              if($countRowsOne > 0):
                 $sql = $mainBodySql."(celebrity_name = '".$searchq."' OR celebrity_name LIKE '".$searchq."%')";                   
              endif; 
                  
              if($countRowsTwo > 0 AND $countRowsOne > 0):
                 $sql = $mainBodySql."celebrity_name = '".$searchq."' OR celebrity_name LIKE '".$searchq."%' OR celebrity_name LIKE '%".$searchq."%'";                
              endif;
              
           endif;
              
           if($countWords > 1):
  
              $sqlTwoFounWord = "SELECT celebrity_name FROM __celebrity WHERE celebrity_name LIKE '%".$searchq."%' AND celebrity_active = 1";      
              $countRowsTwo = $this->getCount($sqlTwoFounWord);
              if($countRowsTwoWord > 0):
                 $sql = $mainBodySql."(celebrity_name = '".$searchq."' OR celebrity_name LIKE '%".$searchq."%')";
              endif;                      
                         
              $sqlOneFounWord = "SELECT celebrity_name FROM __celebrity WHERE celebrity_name LIKE '".$searchq."%' AND celebrity_active = 1";      
              $countRowsOne = $this->getCount($sqlOneFounWord);
              if($countRowsOneWord > 0):
                 $sql = $mainBodySql."(celebrity_name = '".$searchq."' OR celebrity_name LIKE '".$searchq."%')";
              endif;
                  
              if($countRowsTwoWord > 0 AND $countRowsOneWord > 0):        
                 $sql = $mainBodySql." (celebrity_name = '".$searchq."' OR celebrity_name LIKE '".$searchq."%' OR celebrity_name LIKE '%".$searchq."%')";                 
              else:
                 $sqlTheFounWord = "SELECT celebrity_name FROM __celebrity WHERE celebrity_name LIKE '%".$new_array[0]."%' ".$stOr." AND celebrity_active = 1";
                 $countRowsTheWord = $this->getCount($sqlTheFounWord);                        
                 if($countRowsTheWord > 0):
                    $sql = $mainBodySql."celebrity_name = '".$searchq."' OR celebrity_name LIKE '%".$new_array[0]."%' ".$stOr."";
                 endif;
              endif;
              
           endif;
            
        else:
          
           if($countWords==1):
              
              $sqlTwo = "SELECT celebrity_name FROM __celebrity WHERE celebrity_name LIKE '%".$searchq."%' AND celebrity_active = 1";
              $countRowsTwo = $this->getCount($sqlTwo);
              if($countRowsTwo > 0):
                 $sql = $mainBodySql."celebrity_name LIKE '%".$searchq."%'";
              endif;
                  
              $sqlOne = "SELECT celebrity_name FROM __celebrity WHERE celebrity_name LIKE '".$searchq."%' AND celebrity_active = 1";
              $countRowsOne = $this->getCount($sqlOne);
              if($countRowsOne > 0):
                 $sql = $mainBodySql."celebrity_name LIKE '".$searchq."%'";
              endif;
                  
              if($countRowsOne > 0 AND $countRowsTwo > 0):
                 $sql = $mainBodySql."(celebrity_name LIKE '".$searchq."%' OR celebrity_name LIKE '%".$searchq."%')";                     
              endif;
              
           endif;      
            
              if($countWords>1):
                 $sqlTwo = "SELECT celebrity_name FROM __celebrity WHERE celebrity_name LIKE '%".$searchq."%' AND celebrity_active = 1";
                 $countRows = $this->getCount($sqlTwo);
                 if($countRowsOne > 0):
                    $sql = $mainBodySql."celebrity_name LIKE '%".$searchq."%'";
                 endif;
                     
                 $sqlOne = "SELECT celebrity_name FROM __celebrity WHERE celebrity_name LIKE '".$searchq."%' AND celebrity_active = 1";
                 $countRows = $this->getCount($sqlOne);
                 if($countRowsTwo > 0):
                    $sql = $mainBodySql."celebrity_name LIKE '".$searchq."%'";
                 endif;
                  
                 if($countRowsOne > 0 AND $countRowsTwo > 0):
                    $sql = $mainBodySql."(celebrity_name LIKE '".$searchq."%' OR celebrity_name LIKE '%".$searchq."%')";                     
                 endif;
                  
                 $sqlThe = "SELECT celebrity_name FROM __celebrity WHERE celebrity_name LIKE '%".$new_array[0]."%' ".$stAn." AND celebrity_active = 1";
                 $countRows = $this->getCount($sqlThe);                        
                 if($countRows > 0):
                    $sql = $mainBodySql."celebrity_name LIKE '%".$new_array[0]."%' ".$stAn."";
                 else:
                    $sqlFou = "SELECT celebrity_name FROM __celebrity WHERE celebrity_name LIKE '%".$new_array[0]."%' ".$stOr." AND celebrity_active = 1";
                    $countRows = $this->getCount($sqlFou);                        
                    if($countRows > 0):
                       $sql = $mainBodySql."celebrity_name LIKE '%".$new_array[0]."%' ".$stOr."";
                    endif; 
                 endif;
                  
              endif;
              
          endif;
          
          return $sql;
    }
}