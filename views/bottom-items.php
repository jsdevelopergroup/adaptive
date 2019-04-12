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

    if(($item['hash'] == 'mousepad') || ($item['hash'] == 'case')) {
     if($imgW < $imgH) {
        $subCssClass .= ' pro-image-v';
        $imgSwitsh = true;
      }elseif($imgW > $imgH) {
        $subCssClass .= ' pro-image-h';
      }else{
        $subCssClass .= ' pro-image-sq';
      }
    }

    if(($item['hash'] == 'mousepad') || ($item['hash'] == 'pillow') || ($item['hash'] == 'case')) {
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
      <a href="#<?=$item['hash']?>" class="pro-title" data-item="<?=$item['item']?>" data-hash="<?=$item['hash']?>" data-id="<?=$item['id']?>"><?=$item['title']?></a>
    </div>
  <?endforeach;?>
  </div>