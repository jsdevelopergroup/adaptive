<?

class Product extends Mysql {

    public $typePage;
    public $metaArray;
    public $titlePage;
    
    public $breadPage;
  
    public $productsList = array();

    public $imgSrc;

    public $imgWSm;
    public $imgHSm;

    public $imgWBig;
    public $imgHBig;

    public $currentUrl;
    public $refererUrl;

    public $fullName;

    public $categoryUrl;
    public $categoryName;

    public $imageName;

    public $id;
    // REFERER referer

    public function __construct(){
       // https://idposter.com/Nikita_Kucherov/919214_Nikita_Kucherov_poster.html
       // https://idposter.com/Nikita-Kucherov-posters-and-prints/Nikita-Kucherov-print-919214#poster

       $id = intval($_GET['id']);
       $sql = 'SELECT picture_id,picture_small,picture_dir_small,picture_big,picture_dir_big,picture_width,picture_height FROM __picture WHERE picture_active = 1 AND picture_id = '.$id;
       
       $object = $this->getOneRow($sql);
       if(count($object) > 1):

          $name = str_replace('-',' ',mysql_real_escape_string($_GET['name']));
          
          $nameRecord = $this->getOneRow("SELECT celebrity_name,celebrity_category_id FROM __celebrity WHERE celebrity_name = '$name' AND celebrity_active = 1 LIMIT 1");
          if(count($nameRecord) < 2) $nameRecord = $this->getOneRow("SELECT celebrity_name,celebrity_category_id FROM __celebrity WHERE celebrity_dash = '$name' AND celebrity_active = 1 LIMIT 1");

          $categoryId = $nameRecord['celebrity_category_id'];
          $categoryRecord = $this->getOneRow("SELECT category_name,category_url FROM __category WHERE category_id = '$categoryId' LIMIT 1");

          $this->categoryUrl = $categoryRecord['category_url'];
          $this->categoryName = $categoryRecord['category_name'];
          $this->fullName = $nameRecord['celebrity_name'];

          $this->imgSrcBg = '/img/bigs/'.$object['picture_dir_big'].'/'.$object['picture_big'];
          $this->imgSrcSm = '/img/smalls/'.$object['picture_dir_small'].'/'.$object['picture_small'];

          $this->imageName = $object['picture_big'];

          if(!file_exists('.'.$this->imgSrcBg)):
             $this->imgSrcBg = '/icons/image404big.jpg';
          endif;
          
          if(!file_exists('.'.$this->imgSrcSm)):
             $this->imgSrcSm = '/icons/image404.gif';
          endif;
          
          $size = getimagesize('.'.$this->imgSrcBg);

          $this->imgWSm = $size[0]; // width
          $this->imgHSm = $size[1]; // height

          // gorizontal
          if($this->imgWSm > $this->imgHSm):
             if($object['picture_width'] > $object['picture_height']):
                $this->imgWBig = $object['picture_width'];
                $this->imgHBig = $object['picture_height'];
             else:
                $this->imgWBig = $object['picture_height'];
                $this->imgHBig = $object['picture_width'];
             endif;
          endif;

          // vertical
          if($this->imgHSm > $this->imgWSm):
             if($object['picture_height'] > $object['picture_width']):
                $this->imgWBig = $object['picture_width'];
                $this->imgHBig = $object['picture_height'];
             else:
                $this->imgWBig = $object['picture_height'];
                $this->imgHBig = $object['picture_width'];
             endif;
          endif;

          // square
          if($this->imgWSm == $this->imgHSm):
             $this->imgWBig = $object['picture_width'];
             $this->imgHBig = $object['picture_height'];
          endif;

          // $imgWBig = ; $this->imgWBig
          // $imgHBig = ; $this->imgHBig
          $this->titlePage = $this->fullName.' <span>print</span> #<id>'.$id.'</id>';
          $this->breadPage = $this->fullName.' <product></product> #<id>'.$id.'</id>';
          $this->id = $id;
          $sql = "SELECT * FROM __products WHERE visib = 1 ORDER By rating ASC";

          $this->productsList = $this->getArray($sql);
          $this->typePage = 'product';
          $this->currentUrl = SITE_NAME.$_SERVER['REQUEST_URI'];
          
          if($_SERVER['HTTP_REFERER']):
             $this->refererUrl = $_SERVER['HTTP_REFERER'];
             $this->refererUrl = str_replace('https://idposter.com/','',$this->refererUrl);
             $this->refererUrl = str_replace('http://idposter.com/','',$this->refererUrl);
             $this->refererUrl = str_replace('http://idposter/','',$this->refererUrl);
          else:
             $requestArray = explode('/',$_SERVER['REQUEST_URI']);
             $this->refererUrl = $requestArray[1].'/';
          endif;
       else:
          $this->typePage = '404';
       endif;
    }
}