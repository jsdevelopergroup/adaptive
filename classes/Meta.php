<?
class Meta {
    
    public $title;
    public $description;
    public $keywords;
    public $ogImage;
    public $ogUrl;
    public $robotsContent;
    public $canonical;
   
    function __construct($metaArray) {
        
           $this->title = $metaArray[0];
           $this->description = $metaArray[1];
           $this->keywords = $metaArray[2];
           $this->ogImage = $metaArray[3];
           $this->ogUrl = $metaArray[4];
           $this->robotsContent = $metaArray[5];
           $this->canonical = $metaArray[6];
    }
}