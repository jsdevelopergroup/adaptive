<?php

session_start();

/* START NEW ENGINE SITE */
//if($_SERVER["argc"] == 0):

//if((isset($_COOKIE['testing_mode']) AND $_COOKIE['testing_mode']=='gwie12s3') OR ($_SERVER['HTTP_HOST']=='adaptive.idposter.com')):

   include 'config.php';
 

   require_once("classes/MySql.php");
   require_once("classes/Index.php");

   $mysql = new Mysql(DB_HOST,DB_USER,DB_PASSWORD,DB_BASE_NAME);
   $mysql->getQuery('SET NAMES utf8');

   $index = new Index();
   $metaArray = NULL;

   // AJAX
   if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'):
      require_once("classes/Ajax.php");
      $ajax = new Ajax();
      exit;
   endif;
 
   // index, follow, noindex, nofollow
   // 1. HOME PAGE
   if($_SERVER["argc"] == 0):
      require_once("classes/Home.php");
      $home = new Home();
      $typePage = 'home';
      $metaArray = $home->metaArray;
   else:
   
    // phpinfo();

      //2. CATEGORY
      if(isset($_GET['category'])):
         require_once("classes/Category.php");
         require_once("classes/Navi.php");
         $category = new Category();
         $navi = new Navi($category->categoryUrl,$category->page,$category->end,$category->sortby);
         $typePage = $category->typePage;
         $metaArray = $category->metaArray;
      endif;

      //3. NAME
      if(isset($_GET['posters']) or isset($_GET['name'])):
         require_once("classes/Name.php");
         $name = new Name($mysql);
         $typePage = $name->typePage;
         $metaArray = $name->metaArray;
      endif;

      // 4. PRODUCT
      if(isset($_GET['view']) AND isset($_GET['postersone']) AND isset($_GET['posterstwo']) AND isset($_GET['act'])):
         $name = str_replace('_','-',$_GET['postersone']);
         $tmp = explode('_',$_GET['posterstwo']);
         $id = $tmp[0];
         $product = $_GET['act'];
         header('Location: '.SITE_NAME.'/'.$name.'-posters-and-prints/'.$name.'-print-'.$id.'#'.$product);
      endif;

      if(isset($_GET['name']) AND isset($_GET['name_two']) AND isset($_GET['id']) AND isset($_GET['id'])):
         require_once("classes/Product.php");
         $product = new Product($mysql);
         $typePage = $product->typePage;
         $metaArray = $product->metaArray;
      endif;
      
      // 5. STATIC (1.About Us; 2.Products & Materials; 3.Payments; 4.Shipping & Returns; 5.Discounts; 6.Contact Us; 7.Cart; 8.Payment Success; 9.Sitemap)
      if(isset($_GET['static'])):
      
         require_once("classes/StatPage.php");
         $static = new StatPage($mysql);
         
         $typePage = $static->typePage;
         $metaArray = $static->metaArray; 
         
                 
         
      endif;
      
   endif;

   // phpinfo();
   // $metaArray
   // 1. $title
   // 2. $description
   // 3. $keywords
   // 4. $ogImage
   // 5. $ogUrl
   // 6. $robotsContent
   // 7. $canonical

   require_once("classes/Meta.php");
   $meta = new Meta($metaArray);


   // 6. CUSTOM
   // 7. CONSTRUCTOR
   // 8. INSTAGRAM
   // 9. LOG-IN / ACCOUNT

   include('views/__index.php');
 
?>