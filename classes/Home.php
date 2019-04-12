<?

class Home extends Mysql {
    
    public $categoryText;
    public $categoryH1;
    public $homeProducts = array();
    public $metaArray = array();
    public $img404 = '/icons/image404.gif';
   
    /* 
    public $totalNamesCel;
    public $totalItemsCel;
    
    public $totalNamesMod;
    public $totalItemsMod;
    
    public $totalNamesMov;
    public $totalItemsMov;
    
    public $celebrities = array();
    public $movies = array();
    public $models = array();
    */
    
    function __construct() {
        
         
        
         $homeRecord = $this->getOneRow("SELECT category_h1,category_text,category_title,category_description FROM __category WHERE category_url = '/' AND category_active=1");
         
         $this->categoryText = $homeRecord['category_text'];
         $this->categoryH1 = $homeRecord['category_h1'];
         $this->metaArray[] = $homeRecord['category_title'];
         $this->metaArray[] = $homeRecord['category_description'];
         
         $this->metaArray[] = "Posters, Photos, Prints, Mousepads, T-Shirts, Magnets, Puzzles, Pillows, Mugs, Cups, Calendars, Cases for iPhone, iPad, Samsung Galaxy";
        
         $this->metaArray[] = SITE_NAME.'/logo.png';
         $this->metaArray[] = SITE_NAME;
         $this->metaArray[] = 'index, follow';
         
         $thisHomeProducts = $this->getArray("SELECT home_celebrity,home_picture_path,home_category_id FROM __home_celebrity WHERE home_active = 1 ORDER BY home_category_id ASC");
         
         $n=0;
         foreach($thisHomeProducts as $item):
                 $this->homeProducts[$n]['home_picture_path'] = (file_exists($item['home_picture_path'])) ? SITE_NAME.'/'.$item['home_picture_path'] : $this->img404;
                 $this->homeProducts[$n]['home_celebrity'] = $item['home_celebrity'];
                 $this->homeProducts[$n]['link_href'] = SITE_NAME.'/'.str_replace(' ','_',$item['home_celebrity']).'/';
                 $this->homeProducts[$n]['link_title'] = $item['home_celebrity'].' posters and prints';
                 $this->homeProducts[$n]['link_alt'] = $item['home_celebrity'].' posters and prints';
                 $this->homeProducts[$n]['home_category_id'] = $item['home_category_id'];
                 $this->homeProducts[$n]['span_title'] = '<b>'.$item['home_celebrity'].'</b><br /> posters and prints';
                 $n++; 
         endforeach;
     
      /*
      $celebrity_a = array();
      $models_a = array();
      $movie_a = array();

      $celebrity_array = array();
      $models_array = array();
      $movie_array = array();
      
      $this->totalNamesCel = $mysql->getRowValue("SELECT COUNT(*) as count FROM object_name WHERE c_id = 11 AND active=1","count");
      $this->totalItemsCel = $mysql->getRowValue("SELECT COUNT(*) as count FROM object,object_name WHERE object.object_parent = object_name.object_parent AND object_name.c_id = 11","count");
      
      $this->totalNamesMod = $mysql->getRowValue("SELECT COUNT(*) as count FROM object_name WHERE c_id = 2 AND active=1","count");
      $this->totalItemsMod = $mysql->getRowValue("SELECT COUNT(*) as count FROM object,object_name WHERE object.object_parent = object_name.object_parent AND object_name.c_id = 2","count");
      
      $this->totalNamesMov = $mysql->getRowValue("SELECT COUNT(*) as count FROM object_name WHERE c_id = 3 AND active=1","count");
      $this->totalItemsMov = $mysql->getRowValue("SELECT COUNT(*) as count FROM object,object_name WHERE object.object_parent = object_name.object_parent AND object_name.c_id = 3","count");

      $celebrity_sql = "SELECT COUNT(*) AS count, object_word FROM object_name WHERE active = 1 AND c_id = 11 GROUP BY object_word HAVING count > 1";
      $celebrity_array = $mysql->getArray($celebrity_sql);
      foreach($celebrity_array as $item):
        if(!is_numeric($item['object_word'])):
            $pages = ceil($item['count'] / 100);
            for($j=1;$j<=$pages;$j++):
                $this->celebrities[] = strtoupper($item['object_word']).$j;
            endfor;
        endif;
      endforeach;

      $models_sql = "SELECT COUNT(*) AS count, object_word FROM object_name WHERE active = 1 AND c_id = 2 GROUP BY object_word HAVING count > 1";
      $models_array = $mysql->getArray($models_sql);
      foreach($models_array as $item):
        if(!is_numeric($item['object_word'])):
            $pages = ceil($item['count'] / 100);
            for($j=1;$j<=$pages;$j++):
                $this->models[] = strtoupper($item['object_word']).$j;
            endfor;
        endif;
     endforeach;

     $movie_sql = "SELECT COUNT(*) AS count, object_word FROM object_name WHERE active = 1 AND c_id = 3 GROUP BY object_word HAVING count > 1";
     $movie_array = $mysql->getArray($movie_sql);
     foreach($movie_array as $item):
        if(!is_numeric($item['object_word'])):
            $pages = ceil($item['count'] / 100);
            for($j=1;$j<=$pages;$j++):
                $this->movies[] = strtoupper($item['object_word']).$j;
            endfor;
        endif;
     endforeach;
     */
    }
     
    public function getCategoryName($category_id){
        return $this->getRowValue("SELECT category_name FROM __category WHERE category_id = $category_id","category_name");
    }
    
}

















