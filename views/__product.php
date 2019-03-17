<!-- BreadCrumb Line Top -->
<div class="bread_n_sort">
  <div class="bread_n_sort_wr">
      <!-- Breadcrumb -->
      <div class="breadcrumb_block breadcrumb_block-product">
        <ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
          <li itemscope itemtype="http://schema.org/ListItem" itemprop="itemListElement">
            <a itemprop="item" href="<?=SITE_NAME?>">
              <span itemprop="name">Home</span>
              <meta itemprop="position" content="1" />
            </a>
          </li>
          <li itemscope itemtype="http://schema.org/ListItem" itemprop="itemListElement">
            <a itemprop="item" href="<?=SITE_NAME?>/<?=$product->categoryUrl?>/">
              <span itemprop="name"><?=$product->categoryName?> posters</span>
              <meta itemprop="position" content="2" />
            </a>
          </li>
          <li itemscope itemtype="http://schema.org/ListItem" itemprop="itemListElement" class="breadcrumb_name">
           <a itemprop="item" href="<?=SITE_NAME?>/<?=$product->refererUrl?>">
            <span itemprop="name"><?=$product->fullName?> posters and prints</span>
            <meta itemprop="position" content="3" />
           </a>
          </li>
          <li itemscope itemtype="http://schema.org/ListItem" itemprop="itemListElement" class="breadcrumb_product">
            <span itemprop="name" class="lastItem"><?=$product->breadPage?></span>
            <meta itemprop="position" content="4" />
          </li>
        </ol>
      </div>
      <!-- END Breadcrumb -->
  </div>
</div>

 <div class="box_posters">
  <h1><?=$product->titlePage?></h1>
  <div class="image-cart-block">
    <div class="image-left">
      <div class="image-block"><img src="<?=$product->imgSrcBg?>" class="image-big" /></div>
    </div>
    <div class="cart-right">
      <div class="price-button-block">
        <div class="price-block f-left">
         <div class="input-price-block">
           <div class="input-price-currency">$</div><div class="input-price"></div>
         </div>
         <div class="input-price-line"></div>
         <div class="per-price">
           <div class="per-price-data">
             <div class="per-price-currency">$</div><div class="per-price-value"></div>
           </div>
           <div class="per-price-product">per poster</div>
         </div>
        </div>
       <div class="button-block f-right"><button class="add-to-cart">Add to cart</button></div>
      </div>
     <div class="cart-block"></div>
    </div>
    <div class="cart-json-response"></div>
  </div>
  <h2>More products(<?=count($product->productsList)?>) with <span><?=$product->fullName?></span> #<?=$product->id?></h2>
  <div class="pro-items-list">
  <?
  $imgW = $product->imgWSm;
  $imgH = $product->imgHSm;
  $imgWb = $product->imgWBig;
  $imgHb = $product->imgHBig;
  foreach($product->productsList as $item):

    $subCssClass = "pro-image-".$item['hash'];
    $imgBg = false;
    $imgSwitsh = false;

    if ( ($item['hash'] == 'mousepad') || ($item['hash'] == 'case')) {
      if ($imgW < $imgH) {
        $subCssClass .= ' pro-image-v';
        $imgSwitsh = true;
      } else if ($imgW > $imgH) {
        $subCssClass .= ' pro-image-h';
      } else {
        $subCssClass .= ' pro-image-sq';
      }
    }

    if (($item['hash'] == 'mousepad') || ($item['hash'] == 'pillow') || ($item['hash'] == 'case')) {
      $imgBg = true;
      $subCssClass .= ' pro-image-bg';
    }
    
    // $this->imgSrcBg - BIG IMAGE
  ?>
    <div class="pro-item">
      <div class="pro-image-area" data-wbig="<?=$imgWb?>" data-hbig="<?=$imgHb?>" data-wsm="<?=$imgW?>" data-hsm="<?=$imgH?>">
        <?php if ($item['hash'] == 'puzzle') { ?>
        <div class="mask <?=$item['hash']?>" style="width:<?=$imgW/2;?>px; height:<?=$imgH/2;?>px"></div>
        <?php } elseif ( ($item['hash'] == 'case') && !$imgSwitsh) { ?>
          <div class="mask image-h <?=$item['hash']?>"></div>
        <?php } else { ?>
        <div class="mask <?=$item['hash']?>"></div>
        <?php }
        if ($imgBg) {
          if ( $item['hash'] == 'case') { 
            if ( $imgSwitsh) {?>
            <div class="pro-image <?=$subCssClass?>" style="background-image: url(<?=$product->imgSrcSm?>);"></div>
            <?php } else { ?>
              <img class="pro-image <?=$subCssClass?>" src="<?=$product->imgSrcSm?>" alt="#">
            <?php }/*$imgSwitsh*/
          } else { ?>
          <div class="pro-image <?=$subCssClass?>" style="background-image: url(<?=$product->imgSrcBg?>);"></div>
          <?php } /*case*/
        } else { ?>
        <img class="pro-image <?=$subCssClass?>" src="<?=$product->imgSrcSm?>" alt="#">
        <?php } /*$imgBg*/ ?>
      </div>
      <div class="pro-title" data-item="<?=$item['item']?>" data-hash="<?=$item['hash']?>" data-id="<?=$item['id']?>"><?=$item['title']?></div>
    </div>
  <?endforeach;?>
  </div>
</div>

<!--  <pre> -->
<!--  </pre> -->
<div class="current_url"><?=$product->currentUrl?></div>
<div class="referer_url"><?=$product->refererUrl?></div>
<div class="image-height"><?=$product->imgHBig?></div>
<div class="image-width"><?=$product->imgWBig?></div>
