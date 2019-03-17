<?

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

class Category extends Mysql{
    
    public $typePage;

    public $nameCounts;
    public $categoryId;
    public $categoryUrl;
    public $categoryName;
    public $perPage = 24;
    
    public $titlePage;
    public $arraySorting = array();
    
    public $metaArray = array();
    public $categoryNames = array();
    public $seoText;
    
    public $end;
    public $page;
    public $sortby;
    public $count;
    
    public $img404 = '/icons/image404.gif';
        
    public function __construct(){
        
        $categoryGet = mysql_real_escape_string($_GET["category"]).'-posters';
        
        echo $categoryGet; 
        
        $categoryRow = $this->getOneRow("SELECT * FROM __category WHERE url = '$categoryGet'");
        
        if(count($categoryRow) > 1):
        
           $this->nameCounts = $categoryRow['name_counts'];
           $this->categoryId = $categoryRow['id'];
           $this->categoryUrl = $categoryRow['url'];
           
           $this->categoryName = $categoryRow['name'];

           $this->page = (isset($_GET["page"])) ? (int)$_GET["page"] : 1;
           $start = ($this->page == 1) ? 0 : ($this->page - 1) * $this->perPage;
           
           $count_sql = "SELECT COUNT(*) as count FROM __celebrity WHERE c_id = $this->categoryId AND active=1";
           $this->count = $this->getRowValue($count_sql,"count");
           $this->end = ceil($this->count / $this->perPage);
           
           $this->titlePage = $categoryRow['title_page'].' : '.$this->count.' '.$this->nameCounts;
           
           if(isset($_GET["sortby"])){
                 $_SESSION['sort'] = $_GET["sortby"];
                 if($_SESSION['sort'] == 'new') $_SESSION['sortid'] = 'ORDER BY id DESC';
                 if($_SESSION['sort'] == 'old') $_SESSION['sortid'] = 'ORDER BY id ASC';
                 if($_SESSION['sort'] == 'a_z') $_SESSION['sortid'] = 'ORDER BY object_name ASC';
                 if($_SESSION['sort'] == 'z_a') $_SESSION['sortid'] = 'ORDER BY object_name DESC';
                 if($_SESSION['sort'] == 'popular') $_SESSION['sortid'] = 'ORDER BY views DESC';
                 $canonical = SITE_NAME.'/'.$this->categoryUrl.'/';
                 $tail = '; sorting: '.str_replace('_','-',$_GET["sortby"]);
                 if(isset($page)):
                    $tail .= '; page: '.$page. '';
                 endif;
                 $this->sortby = mysql_real_escape_string($_GET["sortby"]);
           }else{
                 $_SESSION['sort'] = 'new';
                 $_SESSION['sortid'] = 'ORDER BY id DESC';
                 $this->sortby = $_SESSION['sort'];
                 $this->seoText = $categoryRow['seo_text'];
                 $canonical = NULL;
           }
           
           $mainSql = "SELECT object_name,object_parent as parent, (SELECT object_picture_banner FROM __picture WHERE object_parent = parent ORDER BY object_view DESC LIMIT 1) as object_image, (SELECT folder FROM __picture WHERE object_parent = parent ORDER BY object_view DESC LIMIT 1) as object_folder FROM __celebrity WHERE c_id = $this->categoryId AND active=1 ".$_SESSION['sortid']." LIMIT $start, $this->perPage";
           $currentArray = $this->getArray($mainSql);
           
           $c = 0;
           foreach($currentArray as $item):
               
              $imgPath = './img/bigs/'.$item['object_folder'].'/'.$item['object_image'];
              
              $this->categoryNames[$c]['img_src'] = (file_exists($imgPath)) ? SITE_NAME.'/'.$imgPath : $this->img404;
              $this->categoryNames[$c]['a_link'] = SITE_NAME.'/'.str_replace(' ','_',$item['object_name']).'/';
              $this->categoryNames[$c]['a_title'] = $item['object_name'].' posters and prints';
              $this->categoryNames[$c]['a_alt'] = $item['object_name'].' posters and prints';
              $this->categoryNames[$c]['span_title'] = '<b>'.$item['object_name'].'</b><br /> posters and prints';
              $c++;
           
           endforeach;
           
          // print_r($this->categoryNames);
           
           if(count($this->categoryNames) > 0):          
           
              $this->arraySorting[] = array('name'=>'NEW','value'=>'new');
              $this->arraySorting[] = array('name'=>'OLD','value'=>'old');
              $this->arraySorting[] = array('name'=>'A-Z','value'=>'a_z');
              $this->arraySorting[] = array('name'=>'Z-A','value'=>'z_a');
              $this->arraySorting[] = array('name'=>'POPULAR','value'=>'popular');
              
              $this->metaArray[] = $categoryRow['title'];
              $this->metaArray[] = $categoryRow['description'];
              $this->metaArray[] = $categoryRow['name'].' posters, prints';

              $object_parent = $this->getRowValue("SELECT object_parent FROM __celebrity WHERE c_id = $this->categoryId AND active = 1 ORDER BY id DESC LIMIT 1","object_parent");          
              $object_picture = $this->getOneRow("SELECT object_picture_banner,folder FROM __picture WHERE object_parent = $object_parent AND object_active = 1 ORDER BY object_view DESC LIMIT 1");        
           
              $this->metaArray[] = SITE_NAME.'/img/bigs/'.$object_picture['folder'].'/'.$object_picture['object_picture_banner'];
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