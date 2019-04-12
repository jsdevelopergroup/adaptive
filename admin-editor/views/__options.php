<h4 class="text-center pb-3 pt-3">Options</h4>

<div class="alert alert-danger" role="alert" style="display: none;"></div>
 
 <?if($_GET['result']=='add'):?>
  <div class="alert alert-success" role="alert">Option successfully added</div>
 <?endif;?>
 <?if($_GET['result']=='edit'):?>
  <div class="alert alert-info" role="alert">Option successfully edited</div>
 <?endif;?>

 <form method="POST" action="<?=$_SERVER['REQUEST_URI']?>" id="form-option">

  <?if(count($obj->productsList)>0):?>
   <div class="form-group row">
    <label for="selectProduct" class="col-sm-2 col-form-label">Product</label>
    <div class="col-sm-10">
      <select id="selectProduct" class="form-control">
        <option value="0">Select Product</option>
         <?foreach($obj->productsList as $item):
         $selected = ($_GET['product'] == $item['id']) ? ' selected' : '';?>
         <option value="<?=$item['id']?>"<?=$selected?>><?=$item['rating']?>. <?=$item['title']?></option>
        <?endforeach;?>
      </select>
    </div>
   </div>
  <?endif;?>
  
  <?if(count($obj->propertyList)>0):?> 
   <div class="form-group row">
    <label for="selectProperties" class="col-sm-2 col-form-label">Property</label>
    <div class="col-sm-10">
      <select id="selectProperties" class="form-control">
        <option value="0">Select Property</option>
         <?foreach($obj->propertyList as $item):
         $selected = ($_GET['property'] == $item['id']) ? ' selected' : '';?>
         <option value="<?=$item['id']?>"<?=$selected?>><?=$item['rating']?>. <?=$item['title']?></option>
        <?endforeach;?>
      </select>
    </div>
   </div>
  <?endif;?>
  
  <?if(count($obj->optionList)>0):?> 
   <div class="form-group row">
    <label for="selectOptions" class="col-sm-2 col-form-label">Option</label>
    <div class="col-sm-10">
      <select id="selectOptions" class="form-control">
        <option value="0">Select Option</option>
         <?foreach($obj->optionList as $item):
         $selected = ($_GET['option'] == $item['id']) ? ' selected' : '';
         $default = ($item['bydef'] == 1) ? ' default' : '';
         ?>
         <option value="<?=$item['id']?>"<?=$selected?>><?=$item['rating']?>. <?=$item['title']?> <?=$default?></option>
        <?endforeach;?>
      </select>
    </div>
   </div>
  <?endif;?>
  
  <?//if($obj->optionData['title'] != '' AND $obj->optionData['rating'] != ''):?>
  <div class="form-group row">
    <label for="inputTitle" class="col-sm-2 col-form-label">Title</label>
    <div class="col-sm-10">
      <input type='text' class='form-control' id='inputTitle' name='title' value='<?=$obj->optionData['title']?>' placeholder='Title' />
    </div>
  </div>
 
  <div class="form-group row">
    <label for="inputRating" class="col-sm-2 col-form-label">Rating</label>
    <div class="col-sm-10">
      <input type='text' class='form-control' id='inputRating' name='rating' value='<?=$obj->optionData['rating']?>' placeholder='Rating' />
    </div>
  </div>
  
  <div class="form-group row">
    <div class="col-sm-2">Default</div>
    <div class="col-sm-10">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="bydef" value="1" id="gridCheck1" <?=$obj->checkedDef?>>
        <label class="form-check-label" for="gridCheck1"></label>
      </div>
    </div>
  </div>
  
  <div class="form-group row">
    <div class="col-sm-2">Visibility</div>
    <div class="col-sm-10">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="visib" value="1" id="gridCheck2" <?=$obj->checkedVisib?>>
        <label class="form-check-label" for="gridCheck2"></label>
      </div>
    </div>
  </div>
  
  <div class="form-group row">
    <div class="col-sm-12">
      <button type="submit" class="btn btn-primary float-right" name="done_option">Save</button>
    </div>
  </div>
  
  <?//endif;?>
  </form>

<script type="text/javascript">

  window.addEventListener('load',productEvents,false);
  
  function getGet(name) {
     var s = window.location.search;
     s = s.match(new RegExp(name + '=([^&=]+)'));
     return s ? s[1] : false;
  }
  
  function productEvents(){
    
     var listProducts = document.getElementById('selectProduct');
     var listProperties = document.getElementById('selectProperties');
     var listOptions = document.getElementById('selectOptions');
     
     var btnPrimary = document.querySelector('.btn-primary');
     var alertBlock = document.querySelector('.alert');
     
     var adminUrl = 'http://adaptive.idposter.loc/admin-editor/index.php';
     var curentUrl = adminUrl + '?page=products&mod=options';
     
     listProducts.onchange = function(){
         if(this.value != 0){
            window.location = curentUrl+'&product='+this.value;
         } else {
            window.location = curentUrl
         }
     }
     
     listProperties.onchange = function(){
         var product = getGet('product');
         if(listProperties.value != 0){
            window.location = curentUrl+'&product='+product+'&property='+listProperties.value;
         } else {
            window.location = curentUrl+'&product='+product;
         }
     }
     
     listOptions.onchange = function(){
         var product = getGet('product');
         var property = getGet('property');
         if(listOptions.value != 0){
            window.location = curentUrl+'&product='+product+'&property='+property+'&option='+listOptions.value;
         } else {
            window.location = curentUrl+'&product='+product+'&property='+property;
         }
     }
     
     btnPrimary.onclick = function(){
        
         var title = document.getElementById('inputTitle').value;
         var rating = document.getElementById('inputRating').value;
         var errorMessage = '';
         
         if(rating == ''){
            errorMessage = 'Enter Rating';
         }
         if(title == ''){
            errorMessage = 'Enter Title';
         }        
         if(listProducts.value == 0){
            errorMessage = 'Select Product';
         }
         if(errorMessage == ''){
            return true; 
         }else{
            alertBlock.style.display = 'block';
            alertBlock.innerHTML = errorMessage;
            return false;
         } 
     }
  }
</script> 