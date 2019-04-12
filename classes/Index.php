<?

class Index extends Mysql {
    
  public $logoMessage;
  public $randName; 
  public $topMenu; 
  public $bottomMenu;
  public $bottomMessage;
  public $discMessage;
  public $category = 1;
  public $itemsTotal = 0;
  
  public function __construct(){
    
         $this->logoMessage = 'Online print store';
         $this->randName = $this->getRowValue("SELECT celebrity_name FROM __celebrity WHERE celebrity_category_id = $this->category AND celebrity_active=1 ORDER BY RAND() LIMIT 1","celebrity_name");
         $this->topMenu = $this->getArray("SELECT category_id,category_url,category_name,category_h1,category_tail,category_rating FROM __category WHERE category_type = 1 AND category_active = 1 ORDER By category_rating ASC");
         $this->bottomMenu = $this->getArray("SELECT category_url,category_name FROM __category WHERE category_type = 2 AND category_active = 1 ORDER By category_rating ASC");
         $this->bottomMessage = 'Online print store &copy; 2009-'.date('Y').' idPoster.com';
         $this->discMessage = 'Christmas Discount | - 25 % OFF | coupon: SAVE25';
  }
}
