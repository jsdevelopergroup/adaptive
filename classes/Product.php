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

       $id = (int)$_GET['id'];
       $sql = 'SELECT object_id,folder,object_smalls,object_picture_banner,object_picture_icon,object_width,object_height FROM __picture WHERE object_active = 1 AND object_id = '.$id;
       // 0.0004
       // $sql = 'SELECT * FROM object WHERE object_id = '.$id;
       // 0.0005
       
       
       $object = $this->getOneRow($sql);
       if(count($object) > 1):

          $name = str_replace('-',' ',$_GET['name']);
          $nameRecord = $this->getOneRow("SELECT object_name,c_id FROM __celebrity WHERE object_name = '$name' AND active = 1 LIMIT 1");
          if(count($nameRecord) < 2) $nameRecord = $this->getOneRow("SELECT object_name,c_id FROM __celebrity WHERE object_dash = '$name' AND active = 1 LIMIT 1");

          $categoryId = $nameRecord['c_id'];
          $categoryRecord = $this->getOneRow("SELECT name,url FROM __category WHERE id = '$categoryId' LIMIT 1");

          $this->categoryUrl = $categoryRecord['url'];
          $this->categoryName = $categoryRecord['name'];
          $this->fullName = $nameRecord['object_name'];

          $this->imgSrcBg = '/img/bigs/'.$object['folder'].'/'.$object['object_picture_banner'];
          $this->imgSrcSm = '/img/smalls/'.$object['object_smalls'].'/'.$object['object_picture_icon'];

          $this->imageName = $object['object_picture_banner'];

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
             if($object['object_width'] > $object['object_height']):
                $this->imgWBig = $object['object_width'];
                $this->imgHBig = $object['object_height'];
             else:
                $this->imgWBig = $object['object_height'];
                $this->imgHBig = $object['object_width'];
             endif;
          endif;

          // vertical
          if($this->imgHSm > $this->imgWSm):
             if($object['object_height'] > $object['object_width']):
                $this->imgWBig = $object['object_width'];
                $this->imgHBig = $object['object_height'];
             else:
                $this->imgWBig = $object['object_height'];
                $this->imgHBig = $object['object_width'];
             endif;
          endif;

          // square
          if($this->imgWSm == $this->imgHSm):
             $this->imgWBig = $object['object_width'];
             $this->imgHBig = $object['object_height'];
          endif;

          // $imgWBig = ; $this->imgWBig
          // $imgHBig = ; $this->imgHBig
          $this->titlePage = $this->fullName.' <span>print</span> #<id>'.$id.'</id> - W:'.$this->imgWSm.' x H:'.$this->imgHSm.' '.$this->imgWBig.'x'.$this->imgHBig.' '.$this->imageName;
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
