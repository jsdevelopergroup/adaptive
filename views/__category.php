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
          <span itemprop="name"><?=$category->categoryName?> posters and prints</span>
          <meta itemprop="position" content="2" />
        </li>
      </ol>
    </div>
    <!-- END Breadcrumb -->

    <!-- Sorting -->

    <div class="sort">
      <div class="sort_block">
        <span class="sort_span">Sort by:&nbsp;</span>
        <select class="sort_select" onchange="top.location.href=this.options[this.selectedIndex].value;">
        <?foreach($category->arraySorting as $value):?>
          <?if($value['value'] == 'new'):?>
          <option value="<?=SITE_NAME?>/<?=$category->categoryUrl?>/"<?if($_SESSION['sort']==$value['value']){?> selected<?}?>>
            <?=$value['name']?>
          </option>
          <?else:?>
          <option value="<?=SITE_NAME?>/<?=$category->categoryUrl?>/?sortby=<?=$value['value']?>"<?if($_SESSION['sort']==$value['value']){?> selected<?}?>>
            <?=$value['name']?>
          </option>
          <?endif;?>
        <?endforeach;?>
        </select>
      </div>
    </div>
    <!-- END Sorting -->
  </div>
</div>


<div class="box_posters">
<h1><?=$category->titlePage?></h1>
 <div class="box_posters__list clearfix">
   <?foreach($category->categoryNames as $item):?>
   <div class="block_names">
       <div class="block_names__image">
          <a href="<?=$item['a_link']?>">
             <img src="<?=$item['img_src']?>"
                title="<?=$item['a_title']?>"
                  alt="<?=$item['a_alt']?>"
                class="img-shadow" />
          </a>
       </div>
      <div class="block_names__title">
         <a href="<?=$item['a_link']?>">
            <span class="title_name"><?=$item['span_title']?></span>
         </a>
      </div>
     </div>
    <?endforeach;?>
  </div>
 <?=$navi->navigation?>
 <div class="article">
   <?=$category->seoText?>
 </div>
</div>