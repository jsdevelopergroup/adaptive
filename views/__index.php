<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" lang="en-IN" prefix="og: http://ogp.me/ns#">

<head>

 <meta charset="UTF-8" />

 <title><?=$meta->title?></title>

 <meta name="description" content="<?=$meta->description?>" />

 <meta name="keywords" content="<?=$meta->keywords?>" />

 <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1" />

 <meta http-equiv="X-UA-Compatible" content="IE=edge" />

 <meta name="HandheldFriendly" content="true" />

 <meta name="format-detection" content="telephone=no" />

 <meta name="google-site-verification" content="7henS72N4VjbMnDlOe8ru7-9tKn3c63BJO2BKgPSxs8" />

 <meta name="ROBOTS" content="<?=$meta->robotsContent;?>" />

 <meta property="og:type" content="website" />

 <meta property="og:title" content="<?=$meta->title?>" />

 <meta property="og:description" content="<?=$meta->description?>" />

 <meta property="og:url" content="<?=$meta->ogUrl?>" />

 <meta property="og:image" content="<?=$meta->ogImage?>" />

 <link type="image/x-icon" rel="shortcut icon" href="<?=SITE_NAME?>/favicon.ico" />

 <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans|Rubik|Spicy+Rice|Nanum+Gothic|Roboto+Condensed|PT+Sans+Narrow|Abel" />

 <?if($meta->canonical):?>

 <link rel="canonical" href="<?=$meta->canonical?>" />

 <?endif;?>

 <link type="text/css" rel="stylesheet" href="/css/__index.css" />

 <link type="text/css" rel="stylesheet" href="/css/__menu.css" />

 <link type="text/css" rel="stylesheet" href="/css/__bread.css" />

 <link type="text/css" rel="stylesheet" href="/css/__block.css" />

 <link type="text/css" rel="stylesheet" href="/css/__navi.css" />

 <link type="text/css" rel="stylesheet" href="/css/__sort.css" />

 <link type="text/css" rel="stylesheet" href="/css/__cart-product.css" />

 <link type="text/css" rel="stylesheet" href="/css/__items-product.css" />

 <link type="text/css" rel="stylesheet" href="/css/__<?=$typePage?>.css" />

 <link type="text/css" rel="stylesheet" href="/css/__media.css" />

</head>

<script type="application/ld+json">

{

  "@context" : "http://schema.org",

  "@type" : "Organization",

  "url" : "<?=SITE_NAME?>",

  "logo" : "<?=SITE_NAME?>/logo.png"

}

</script>

<script type="application/ld+json">

{

  "@context" : "http://schema.org",

  "@type" : "Organization",

  "name" : "idPoster",

  "url" : "<?=SITE_NAME?>",

  "sameAs":[

    "https://www.facebook.com/IDposter-167053363358973/"

  ]

}

</script>

<body>

<!--<div class="ad_discount"><?=$index->discMessage?></div>-->

<div class="wrap"></div>



 <div class="center_logo">

   <div class="block-header box_posters">

   

     <div class="block_logo">

        <div class="block_btn_menu"></div>

        <div class="logo">

            <a href="<?=SITE_NAME?>"><span class="color_id">id</span><span class="color_poster">Poster</span></a>

            <span class="slog"><?=$index->logoMessage?><i class="icon icon-print"></i></span>

        </div>

        <!--<img src="/images/halloween.png" style="height: 50px;" />-->

     </div>



      <div class="block_search">

        <div class="search_block">

          <div class="inp_line">

           <div class="inp_block">

             <input type="text" id="search-text" class="search-text" maxlength="50" value=""/>

             <div id="suggesstion-box"></div>

           </div>

           <div class="btn_block">

             <div class="button-search">Search</div>

           </div>

          </div>

          <div class="exp_line">

            <span class="example_line">Exalmple:&nbsp;

              <span class="rand_name"><?=$index->randName?></span>

            </span>

          </div>

        </div>

       </div>



     <div class="block_cart">

      <div class="block_cart_items">

        <a href="<?=SITE_NAME;?>/shopping-cart/" rel="nofollow" class="link_cart">

          <div class="icon-cart"></div>

            <?if($index->itemsTotal == 0):?>

            <div class="link-cart"><span>Cart : </span><?=$index->itemsTotal?></div>

            <?endif;?>

        </a>

      </div>

     </div>

     

   </div>

  </div>



  <div class="navi">

    <div class="close_block"></div>

      <div class="navi_title">Categories</div>

      <div class="box_posters">

      <ul class="navi-list">

       <?$n=0;foreach($index->topMenu as $item):$n++;

       $end = ($n == 12) ? 'endline' : '';?>

       <li><a href="<?=SITE_NAME?><?=$item['url']?>" class="<?=$end?>"><?=$item['name']?> <?=$item['title']?></a></li>

       <?if($item['id']=='4'):?>

         <li><a href="#" title="Other posters" class="disactive">Other posters</a>

            <ul>

          <?endif;?>

          <?if($item['id']=='8'):?>

            </ul>

         </li>

       <?endif;?>

       <?endforeach;?>

     </ul>

    </div>

   </div>

 

 <div class="all_content">

  <? include ('__'.$typePage.'.php');?>

 </div>

 

 <div class="footer">

  <div class="footer_links">

   <ul class="links">

    <?foreach($index->bottomMenu as $item):

     if($item['site']==1):

        $item['value']=SITE_NAME.'/'.$item['value'];

     endif;?>

     <li><a href="<?=$item['value']?>" <?=$item['ads']?>><?=$item['name']?></a></li>

    <?endforeach;?>

   </ul>

  </div>

  <img src="/icons/payments.gif" />

 <br /><?=$index->bottomMessage?>

</div>



</body>

 <script type="text/javascript" src="/js/jquery.js"></script>

 <script type="text/javascript" src="/js/device.js"></script>

 <script type="text/javascript" src="/js/__main.js"></script>

 <script type="text/javascript" src="/js/__<?=$typePage?>.js"></script>

</html>