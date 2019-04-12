<h1 align="center">Site Map</h1>
<ul class="sitemap_block">
<?$n=1;foreach($static->getSiteMapLinks() as $item):?>
<?=$item?> 
<?$n++;endforeach;?>
</ul>
<br />