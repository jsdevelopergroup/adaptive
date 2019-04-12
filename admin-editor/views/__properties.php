<h4 class="text-center pb-3 pt-3">Properties</h4>

<div class="alert alert-danger" role="alert" style="display: none;"></div>
<?if($_GET['result']=='add'):?>
  <div class="alert alert-success" role="alert">Property successfully added</div>
<?endif;?>
<?if($_GET['result']=='edit'):?>
  <div class="alert alert-info" role="alert">Property successfully edited</div>
<?endif;?>

<form method="POST" action="" id="form-property">

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
  
  <div class="form-group row">
    <label for="inputTitle" class="col-sm-2 col-form-label">Title</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputTitle" name="title" value="<?=$obj->propertyData['title']?>" placeholder="Title" />
    </div>
  </div>
  
  <div class="form-group row">
    <label for="inputDescription" class="col-sm-2 col-form-label">Description</label>
    <div class="col-sm-10">
      <textarea class="form-control" id="inputDescription" name="description" rows="5" placeholder="Description"><?=$obj->propertyData['description']?></textarea>
    </div>  
  </div>
  
  <div class="form-group row">
    <label for="inputRating" class="col-sm-2 col-form-label">Rating</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputRating" name="rating" value="<?=$obj->propertyData['rating']?>" placeholder="Rating" />
    </div>
  </div>
  
  <div class="form-group row">
    <div class="col-sm-2">Visibility</div>
    <div class="col-sm-10">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="visib" value="1" id="gridCheck1" <?=$obj->checked?>>
        <label class="form-check-label" for="gridCheck1"></label>
      </div>
    </div>
  </div>
  
  <div class="form-group row">
    <div class="col-sm-12">
      <button type="submit" class="btn btn-primary float-right" name="done_property">Save</button>
    </div>
  </div>
  
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
     var btnPrimary = document.querySelector('.btn-primary');
     var alertBlock = document.querySelector('.alert');
     
     var adminUrl = 'http://adaptive.idposter.loc/admin-editor/index.php';
     var curentUrl = adminUrl + '?page=products&mod=properties';
     
     listProducts.onchange = function(){
         if(this.value != 0){
            window.location = curentUrl+'&product='+this.value;
         }else{
            window.location = curentUrl
         }
     }
     
     btnPrimary.onclick = function(){
         var title = document.getElementById('inputTitle').value;
         var description = document.getElementById('inputDescription').value;
         var rating = document.getElementById('inputRating').value;
         var errorMessage = '';
         if(rating == ''){
            errorMessage = 'Enter Rating';
         }
         if(description == ''){
            errorMessage = 'Enter Description';
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
     
     listProperties.onchange = function(){
         var product = getGet('product');
         if(listProperties.value != 0){
            
            
            
          //  alert(curentUrl+'&product='+product+'&property='+listProperties.value);
            
            window.location = curentUrl+'&product='+product+'&property='+listProperties.value;
            
         } else {
            
          //  alert(curentUrl+'&product='+product);
            
            window.location = curentUrl+'&product='+product;
         }
     }
     
  }
</script>        