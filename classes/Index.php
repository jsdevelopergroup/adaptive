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
         $this->randName = $this->getRowValue("SELECT object_name FROM __celebrity WHERE c_id = $this->category AND active=1 ORDER BY RAND() LIMIT 1","object_name");
         $this->topMenu = $this->getArray("SELECT * FROM __menu_header");
         $this->bottomMenu = $this->getArray("SELECT * FROM __menu_footer");
         $this->bottomMessage = 'Online print store &copy; 2009-'.date('Y').' idPoster.com';
         $this->discMessage = 'Christmas Discount | - 25 % OFF | coupon: SAVE25';
         
         
                   if(isset($_SESSION['cart'])):
                    $ItemTotalPrice=0;
                    $abc = $_SESSION['cart'];
                    if(count($abc)>0):
                        foreach($abc as $base_key => $base_value):
                                $ItemTotalPrice += $abc[$base_key][0];
                        endforeach;
                    endif;
                  else:
                    $ItemTotalPrice=0;
                  endif;
         
  }
  
}
?>