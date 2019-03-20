<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="content-type" content="text/html" />
  <meta name="author" content="acdc" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
 <title>ACDC</title>
</head>
<body>
 <?if(isset($_COOKIE['time']) && isset($_COOKIE['name']) && check() == TRUE):?>
 <div class="container">
  <div class="row">
   <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <ul class="nav nav-pills">
      <li class="nav-item"><a class="nav-link active" href="<?=ADMIN_NAME?>">Admin</a></li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Upload CSV</a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="<?=ADMIN_NAME?>?mod=csv">Images</a> 
          <?if($_COOKIE['name'] == 'boss'):?>
          <a class="dropdown-item" href="<?=ADMIN_NAME?>?mod=descriptions">Descriptions</a> 
          <?endif;?>
        </div>
      </li> 
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Tools</a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="<?=ADMIN_NAME?>?mod=image_checkers">Image Checker</a> 
          <a class="dropdown-item" href="<?=ADMIN_NAME?>?mod=descriptions">Descriptions</a> 
          <a class="dropdown-item" href="<?=ADMIN_NAME?>?mod=images">Hidden</a> 
          <a class="dropdown-item" href="<?=ADMIN_NAME?>?mod=moving">Moving</a> 
          <a class="dropdown-item" href="<?=ADMIN_NAME?>?mod=sitemap">SiteMap</a>
          <a class="dropdown-item" href="<?=ADMIN_NAME?>?mod=text-celebrity">Celebrity</a>   
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Products</a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="<?=ADMIN_NAME?>?page=products&mod=setting">Setting</a>
          <a class="dropdown-item" href="<?=ADMIN_NAME?>?page=products&mod=properties">Properties</a> 
          <a class="dropdown-item" href="<?=ADMIN_NAME?>?page=products&mod=options">Options</a> 
          <a class="dropdown-item" href="<?=ADMIN_NAME?>?page=products&mod=prices">Prices</a> 
           
        </div>
      </li>
      <?if($_COOKIE['name'] == 'boss'):?>
      <li class="nav-item"><a class="nav-link" href="<?=ADMIN_NAME?>/index.php?page=options">Prices</a></li>
      <?endif;?>
      
      <li class="nav-item"><a class="nav-link disabled" href="#">Hello, <?=$_COOKIE['name']?></a></li>
      <li class="nav-item"><a class="nav-link" href="<?=ADMIN_NAME?>?quit">Exit</a></li>
     </ul>
    </nav>
   </div>
  </div>
  <?endif;?>
 <div class="container"><?include($template)?></div>
 <script type="text/javascript" src="/js/application.js"></script>
</body>
</html>