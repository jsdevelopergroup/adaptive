<?

class Home extends Mysql {
    
    public $seoText;
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
        
         
         $thisHomeProducts = $this->getArray("SELECT * FROM __product_home WHERE object_act = 1 ORDER BY object_category ASC");
         $this->seoText = $this->getRowValue("SELECT seo_text FROM __category WHERE url = '/' AND active = '1'","seo_text");
         
         $this->metaArray[] = 'Posters, Prints, best choice of celebrities at online print store idPoster.com';
         $this->metaArray[] = 'Over 780.000 unique Posters & Prints. Celebrity posters, photos, prints on mugs, pillows, iPhone cases, magnets, puzzles, mousepads, T-shirts, etc. at idPoster.com';
         $this->metaArray[] = "Posters, Photos, Prints, Mousepads, T-Shirts, Magnets, Puzzles, Pillows, Mugs, Cups, Calendars, Cases for iPhone, iPad, Samsung Galaxy";
        
         $this->metaArray[] = SITE_NAME.'/logo.png';
         $this->metaArray[] = SITE_NAME;
         $this->metaArray[] = 'index, follow';
         
         $n=0;
          foreach($thisHomeProducts as $item):
                  
                 $this->homeProducts[$n]['img_src'] = (file_exists($item['object_path'])) ? SITE_NAME.'/'.$item['object_path'] : $this->img404;
                 $this->homeProducts[$n]['name'] = $item['object_name'];
                 $this->homeProducts[$n]['a_link'] = SITE_NAME.'/'.str_replace(' ','_',$item['object_name']).'/';
                 $this->homeProducts[$n]['a_title'] = $item['object_name'].' posters and prints';
                 $this->homeProducts[$n]['a_alt'] = $item['object_name'].' posters and prints';
                 $this->homeProducts[$n]['object_category'] = $item['object_category'];
                 $this->homeProducts[$n]['span_title'] = '<b>'.$item['object_name'].'</b><br /> posters and prints';
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
     
    public function getCategoryName($id){
        return $this->getRowValue("SELECT name FROM __category WHERE id = $id","name");
    }
    
}

















