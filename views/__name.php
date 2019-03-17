<!-- BreadCrumb Line Top -->
<div class="bread_n_sort">
 <div class="bread_n_sort_wr">
  <!-- Breadcrumb -->
  <div class="breadcrumb_block">
  <ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">

  <li itemscope itemtype="http://schema.org/ListItem" itemprop="itemListElement">
   <a itemprop="item" href="<?=SITE_NAME?>">
    <span itemprop="name">Home</span>
    <meta itemprop="position" content="1" />
   </a>
  </li>

<li itemscope itemtype="http://schema.org/ListItem" itemprop="itemListElement">
 <a itemprop="item" href="<?=$name->categoryUrl?>">
   <span itemprop="name"><?=$name->categoryName?> posters</span>
   <meta itemprop="position" content="2" />
 </a>
</li>

<li itemscope itemtype="http://schema.org/ListItem" itemprop="itemListElement" class="breadcrumb_name">
 <span itemprop="name"><?=$name->objectName?> posters and prints</span>
 <meta itemprop="position" content="3" />
</li>

</ol>
</div>
<!-- END Breadcrumb -->

<!-- Sorting -->
<div class="sort">
<div class="sort_block">
<span class="sort_span">Sort by:&nbsp;</span>
<select class="sort_select" onchange="top.location.href=this.options[this.selectedIndex].value;">
<?foreach($name->arraySorting as $value):?>
<?if($value['value'] == 'new'):?>
  <option value="<?=SITE_NAME?>/<?=$name->fullUrlName?>/"<?if($_SESSION['sort']==$value['value']){?> selected<?}?>>
     <?=$value['name']?> (<?=$value['count']?>)
  </option>
<?else:?>
  <option value="<?=SITE_NAME?>/<?=$name->fullUrlName?>/?sortby=<?=$value['value']?>"<?if($_SESSION['sort']==$value['value']){?> selected<?}?>>
     <?=$value['name']?> (<?=$value['count']?>)
  </option>
<?endif;?>
<?endforeach;?>
</select>
</div>
</div>
<!-- END Sorting -->
</div>
</div>

<!-- BreadCrumb Line Top -->
<!-- Main block -->
<div class="box_posters">
<!-- Title H1 -->
<h1><?=$name->titlePage?></h1>
<!-- END Title H1 -->
<?if($name->seoText):?>
<!-- Text -->
<div class="text_style noselect <?=$dis_style?>">
<?=$name->seoText?>
</div>
<br />
<!-- END Text -->
<?endif;?>

<div id="object_parent" style="display: none;"><?=$name->objectParent?></div>
<div id="url_name" style="display: none;"><?=$name->objectName_delim?></div>
<div id="total_items" style="display: none;"><?=$name->totalItems?></div>
<div id="sort_by" style="display: none;"><?=$_SESSION['sort']?></div>
<div id="site-name" style="display: none;"><?=SITE_NAME?></div>

<div id="products_list" style="display: none;">
<?=$name->listJsonProducts?>
</div>
<!-- Posters List -->
<div class="posters_list clearfix">

<!--
<div class="block_names">
 <span class="item_id"></span>
  <div class="block_names__inner">
    <div class="block_names__image">
      <a href="http://idposter/Roger_Federer/195610_Roger_Federer_poster.html">
      <img src="/Core/Design/Icons/image404.gif" 
           alt="Roger Federer picture id162756.jpg" 
          title="Roger Federer poster #195610" border="0" 
             id="" 
          class="img-shadow" 
      undefined="" /></a>
    </div>
    <div class="prodlinks_block">
      <div class="prlist_block">
        <a class="prlist_link" href="http://idposter/Roger_Federer/195610_Roger_Federer_poster.html" title="Roger Federer Poster #195610">
          <span class="prlist_link__title">Poster</span>
        </a>
      </div>
    </div>
  </div>
</div>
-->

<div class="load_content">Loading...</div>
</div>
<div class="result-json"></div>
<div class="load_more">Load More <span></span></div>
<!-- END Posters List -->
</div>
<!-- END Main block -->