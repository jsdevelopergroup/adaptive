<?
class Category extends Mysql{
    
    public $categoryId;
    public $categoryUrl;
    public $categoryName;
    public $categoryCounts;
    public $categoryH1;
    public $categoryBreadName;
    public $categoryText;
    
    public $metaArray = array();
    public $arraySorting = array();
    public $categoryNames = array();
    
    public $end;
    public $page;
    public $sortby;
    public $count;
    
    public $sortVar = '?';
    public $perPage = 24;
    public $img404 = '/icons/image404.gif';
    
    public $typePage;
        
    public function __construct(){
        
        $categoryGet = mysql_real_escape_string($_GET["category"]).'-posters';
        $categoryRecord = $this->getOneRow("SELECT * FROM __category WHERE category_url = '$categoryGet' AND category_active=1");
      
        // _SERVER["REQUEST_SCHEME"] : http
        // _SERVER["SERVER_NAME"]    : adaptive.idposter.loc
        // _SERVER["HTTP_HOST"]      : adaptive.idposter.loc
        // _SERVER["REDIRECT_URL"]   : /celebrity-posters/  
        
        if(count($categoryRecord) > 1):
        
           if($_GET['page'] == 1 AND $_GET['sortby'] != ''):
              $str_redirect = $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["HTTP_HOST"].$_SERVER["REDIRECT_URL"].'?sortby='.$_GET['sortby'];
              header("HTTP/1.1 301 Moved Permanently");
              header("Location: ".$str_redirect."");
           endif;
           
           if($_GET['page'] > 0 AND $_GET['sortby'] == ''):
              $str_redirect = $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["HTTP_HOST"].$_SERVER["REDIRECT_URL"];
              header("HTTP/1.1 301 Moved Permanently");
              header("Location: ".$str_redirect."");
           endif;          
           
           $this->categoryId = $categoryRecord['category_id'];
           $this->categoryUrl = $categoryRecord['category_url'].'/';
           $this->categoryName = $categoryRecord['category_name'];
           $this->categoryCounts = $categoryRecord['category_counts'];

           $this->page = (isset($_GET["page"])) ? (int)$_GET["page"] : 1;
           $start = ($this->page == 1) ? 0 : ($this->page - 1) * $this->perPage;
           
           if($categoryGet == 'new-posters'):
              $count_sql = "SELECT COUNT(*) as count FROM __celebrity WHERE celebrity_active=1";       
           else:
              $count_sql = "SELECT COUNT(*) as count FROM __celebrity WHERE celebrity_category_id = $this->categoryId AND celebrity_active=1";
           endif;
           
           $this->count = $this->getRowValue($count_sql,"count");
           $this->end = ceil($this->count / $this->perPage);  
           
           $this->categoryH1 = $categoryRecord['category_h1'].' : '.$this->count.' '.$this->categoryCounts;
           $this->categoryBreadName = $this->categoryName.' posters and prints';
           
           if(isset($_GET["sortby"])):
              $_SESSION['sort'] = $_GET["sortby"];
              if($_SESSION['sort'] == 'new') $_SESSION['sortid'] = 'ORDER BY celebrity_id DESC';
              if($_SESSION['sort'] == 'old') $_SESSION['sortid'] = 'ORDER BY celebrity_id ASC';
              if($_SESSION['sort'] == 'a_z') $_SESSION['sortid'] = 'ORDER BY celebrity_name ASC';
              if($_SESSION['sort'] == 'z_a') $_SESSION['sortid'] = 'ORDER BY celebrity_name DESC';
              if($_SESSION['sort'] == 'popular') $_SESSION['sortid'] = 'ORDER BY celebrity_view DESC';
              $canonical = SITE_NAME.'/'.$this->categoryUrl.'/';
              $tail = '; sorting: '.str_replace('_','-',$_GET["sortby"]);
              if(isset($page)):
                 $tail .= '; page: '.$page. '';
              endif;
              $this->sortby = mysql_real_escape_string($_GET["sortby"]);
           else:
              $_SESSION['sort'] = 'new';
              $_SESSION['sortid'] = 'ORDER BY celebrity_id DESC';
              $this->sortby = $_SESSION['sort'];
              $this->seoText = $categoryRecord['category_text'];
              $canonical = NULL;
           endif;

           if($categoryGet == 'new-posters'):
              $mainSql = "SELECT celebrity_name,celebrity_parent as parent, 
                         (SELECT picture_big FROM __picture WHERE picture_parent = parent ORDER BY picture_view DESC LIMIT 1) as picture_big, 
                         (SELECT picture_dir_big FROM __picture WHERE picture_parent = parent ORDER BY picture_view DESC LIMIT 1) as picture_big_dir 
                         FROM __celebrity 
                         WHERE celebrity_active=1 ".$_SESSION['sortid']." LIMIT $start, $this->perPage";
           else:
              $mainSql = "SELECT celebrity_name,celebrity_parent as parent, 
                         (SELECT picture_big FROM __picture WHERE picture_parent = parent ORDER BY picture_view DESC LIMIT 1) as picture_big, 
                         (SELECT picture_dir_big FROM __picture WHERE picture_parent = parent ORDER BY picture_view DESC LIMIT 1) as picture_big_dir 
                         FROM __celebrity 
                         WHERE celebrity_category_id = $this->categoryId 
                         AND celebrity_active=1 ".$_SESSION['sortid']." LIMIT $start, $this->perPage";
           endif;
           
         //  echo $mainSql;
           
           $currentArray = $this->getArray($mainSql);
           
           $c = 0;
           foreach($currentArray as $item):
              $imgPath = './img/bigs/'.$item['picture_dir_big'].'/'.$item['picture_big'];
              $this->categoryNames[$c]['img_src'] = (file_exists($imgPath)) ? SITE_NAME.'/'.$imgPath : $this->img404;
              $this->categoryNames[$c]['a_link'] = SITE_NAME.'/'.str_replace(' ','_',$item['celebrity_name']).'/';
              $this->categoryNames[$c]['a_title'] = $item['celebrity_name'].' posters and prints';
              $this->categoryNames[$c]['a_alt'] = $item['celebrity_name'].' posters and prints';
              $this->categoryNames[$c]['span_title'] = '<b>'.$item['celebrity_name'].'</b><br /> posters and prints';
              $c++;
           endforeach;
           
           if(count($this->categoryNames) > 0):          
           
              $this->arraySorting[] = array('name'=>'NEW','value'=>'new');
              $this->arraySorting[] = array('name'=>'OLD','value'=>'old');
              $this->arraySorting[] = array('name'=>'A-Z','value'=>'a_z');
              $this->arraySorting[] = array('name'=>'Z-A','value'=>'z_a');
              $this->arraySorting[] = array('name'=>'POPULAR','value'=>'popular');
              
              $this->metaArray[] = $categoryRecord['category_title'];
              $this->metaArray[] = $categoryRecord['category_description'];
              $this->metaArray[] = $categoryRecord['category_name'].' posters, prints';

              if($categoryGet == 'new-posters'):
                 $celebrity_parent = $this->getRowValue("SELECT celebrity_parent FROM __celebrity WHERE celebrity_active = 1 ORDER BY celebrity_id DESC LIMIT 1","celebrity_parent");          
              else:
                 $celebrity_parent = $this->getRowValue("SELECT celebrity_parent FROM __celebrity WHERE celebrity_category_id = $this->categoryId AND celebrity_active = 1 ORDER BY celebrity_id DESC LIMIT 1","celebrity_parent");          
              endif;
              
              $pictureRecord = $this->getOneRow("SELECT picture_big,picture_dir_big FROM __picture WHERE picture_parent = $celebrity_parent AND picture_active = 1 ORDER BY picture_view DESC LIMIT 1");        
           
              $this->metaArray[] = SITE_NAME.'/img/bigs/'.$pictureRecord['picture_dir_big'].'/'.$pictureRecord['picture_big'];
              $this->metaArray[] = SITE_NAME.$_SERVER['REQUEST_URI'];
              $this->metaArray[] = 'index, follow';
      
              if($canonical):
                 $this->metaArray[] = $canonical;
              endif;
              
              $this->typePage = 'category';
        
           else:
              $this->typePage = '404';
           endif;

        else:
           $this->typePage = '404';
        endif;
    }
}


/*
        case 'movie-posters':
        $title_page = 'Movie posters catalog';
        $title = 'Buy classic movie Posters for sale'.$ads;
        $description = 'Buy famous movie posters at cheap rates on idPoster.com, the largest website for posters in the United States. Quality and Delivery Guaranteed'.$ads;
        break;
        case 'celebrity-posters':
        $title_page = 'Celebrity stars posters catalog';
        $title = 'Celebrity stars Posters at idPoster.com'.$ads;
        $description = 'Do you want posters of movie and film stars? Come and place online order at idPoster.com'.$ads;
        break;
        case 'cars-posters':
        $title_page = 'Cars posters & prints';
        $title = 'Cars Posters &amp; Photos idPoster.com'.$ads;
        $description = 'Find your favorite posters of cars at the largest site about cars photo in US'.$ads;
        break;
        case 'games-posters':
        $title = 'Cool video game posters online'.$ads;
        $description = 'You can buy posters of games that you like at idPoster.com'.$ads;
        $title_page = 'Video gaming posters';
        break;
        case 'nature-posters':
        $title_page = 'Nature posters catalog';
        $title = 'Large nature posters and prints'.$ads;
        $description = 'You can buy posters of nature places all over the world that you love at idPoster.com'.$ads;
        break;
        case 'art-posters':
        $title = 'Pop Art Posters for sale';
        $title_page = 'Art posters';
        $description = 'Buy high quality nouveau and art deco posters on idPoster.com. We keep adding to our huge collection of new posters.';
        break;
        case 'anime-posters':
        $title_page = 'Anime posters';
        $title = 'Cheap anime posters online on idposter.com'.$ads;
        $description = 'Best collection posters of anime, photos, puzzles, magnets, calendars, mousepads, t-shirts, pillows, mugs, cases for iPhone 4/4s, 5/5s, 6/6s, iPad 2/3/4, Samsung Galaxy S4, S5 at idPoster.com'.$ads;
        break;
        case 'models-posters':
        $title_page = 'Models posters catalog';
        $title = 'Models posters online on idposter.com'.$ads;
        $description = 'Best beautiful Models posters, photos and prints on mugs, pillows, iPhone cases, magnets, puzzles, mousepad, T-shirts at idPoster.com'.$ads;
        break;
        case 'events-posters':
        $title_page = 'HQ celebrity events posters';
        // Cheap fashion posters, beach posters, other events posters online on idposter.com
        // Cheap fashion posters, beach posters, other events posters online on idposter.com
        // HQ celebrity events posters, fashion posters, beach posters, other events posters online on idposter.com
        // $title = 'Fashion posters, beach posters, other events posters online on idposter.com';
        $title = 'HQ celebrity events posters, fashion posters, beach posters, other events posters online on idposter.com';
        $description = 'Events posters, photos and prints on mugs, pillows, iPhone cases, magnets, puzzles, mousepad, T-shirts at idPoster.com';
        break;
*/        

