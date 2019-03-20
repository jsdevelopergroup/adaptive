<style type="text/css">
.color-tshirt {
    width:30px;
    height:30px;
    border: 1px solid #ccc;
}
</style>
<h4 class="text-center pb-3 pt-3">Prices</h4>
<div class="alert alert-danger" role="alert" style="display: none;"></div>
<form method="POST" action="<?=$_SERVER['REQUEST_URI']?>" id="form-price">
  <?if(count($obj->productsList)>0):?>
   <div class="form-group row">
    <label for="selectProduct" class="col-sm-2 col-form-label">Product</label>
    <div class="col-sm-10">
      <select id="selectProduct" class="form-control">
        <option value="0">Select Product</option>
         <?foreach($obj->productsList as $item):
         $selected = ($_GET['product'] == $item['id']) ? ' selected' : '';?>
         <option value="<?=$item['id']?>"<?=$selected?>><?=$item['title']?></option>
        <?endforeach;?>
      </select>
    </div>
   </div>
  <?endif;?>
  <?if(count($obj->optionsListTwo)>0 AND count($obj->optionsListOne)>0):?> 
   <div class="form-group row">
    <label for="optionsOne" class="col-sm-2 col-form-label label-one"><?=$obj->optionsTitleOne?></label>
    <div class="col-sm-10">
      <select id="optionsOne" class="form-control" name="optionsOne">
        <option value="0">Select <?=$obj->optionsTitleOne?></option>
         <?foreach($obj->optionsListOne as $item):?>
         <option value="<?=$item['id']?>"><?=$item['title']?></option>
        <?endforeach;?>
      </select>
    </div>
   </div>
   <div class="form-group row">
    <label for="optionsTwo" class="col-sm-2 col-form-label label-two"><?=$obj->optionsTitleTwo?></label>
    <div class="col-sm-10">
      <select id="optionsTwo" class="form-control" name="optionsTwo">
        <option value="0">Select <?=$obj->optionsTitleTwo?></option>
         <?foreach($obj->optionsListTwo as $item):?>
         <option value="<?=$item['id']?>"><?=$item['title']?></option>
        <?endforeach;?>
      </select>
    </div>
   </div>
   <div class="form-group row">
    <label for="inputPrice" class="col-sm-2 col-form-label">Price</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputPrice" name="price" value="" placeholder="Price" />
    </div>
   </div>   
   <div class="form-group row">
    <div class="col-sm-12">
      <button type="submit" class="btn btn-primary float-right" name="done_price">Save</button>
    </div>
  </div>
  <?endif;?>
</form>
<?if(count($obj->optionsListTwo)>0 AND count($obj->optionsListOne)>0):?>
  <table class="table table-striped">
  <thead>
    <tr>
      <td>#</td>
      <th scope="col"></th>
      <?if($_GET['product'] == 4):?>
       <?foreach($obj->optionsListTwo as $item):?>
        <th scope="col"><div class="color-tshirt" style="background-color: <?=$item['title']?>;" title="<?=$item['title']?>"></div></th>
       <?endforeach;?>       
      <?else:?>
       <?foreach($obj->optionsListTwo as $item):?>
        <th scope="col"><?=$item['title']?></th>
       <?endforeach;?>      
      <?endif;?>
    </tr>
  </thead>
  <tbody>
  <?$n=1;?>
  <?foreach($obj->optionsListOne as $one):?>
    <tr>
      <td><?=$n?></td>
      <th scope="row"><?=$one['title']?></th>
        <?foreach($obj->optionsListTwo as $two):
          $price = ($obj->getPrice($_GET['product'],$one['id'],$two['id'])) ? $obj->getPrice($_GET['product'],$one['id'],$two['id']) : '---'
        ?>
        <td style="cursor: pointer;" class="price-tab" data-one="<?=$one['id']?>" data-two="<?=$two['id']?>"><?=$price?></td>
      <?endforeach;?>  
    </tr>
  <?$n++;endforeach;?>
  </tbody>
  </table>
<?endif;?>
<script type="text/javascript">
  window.addEventListener('load',productEvents,false);
  function getGet(name) {
     var s = window.location.search;
     s = s.match(new RegExp(name + '=([^&=]+)'));
     return s ? s[1] : false;
  }
  
  function productEvents(){
     var listProducts = document.getElementById('selectProduct');
     var priceTab = document.querySelectorAll('.price-tab');
     var btnPrimary = document.querySelector('.btn-primary');
     var alertBlock = document.querySelector('.alert');
     
     var adminUrl = 'http://adaptive.idposter.loc/acdc/index.php';
     var curentUrl = adminUrl + '?page=products&mod=prices';
     
     listProducts.onchange = function(){
         if(this.value != 0){
            window.location = curentUrl+'&product='+this.value;
         }else{
            window.location = curentUrl
         }
     }
     
     for(var i = 0; i < priceTab.length; i ++){
         priceTab[i].onclick = function(){
             document.querySelector('#optionsOne').value = this.getAttribute("data-one");
             document.querySelector('#optionsTwo').value = this.getAttribute("data-two");
             var currHtml = (this.innerHTML == '---') ? '' : this.innerHTML
             document.querySelector('#inputPrice').value = currHtml;
         } 
     }
     
     btnPrimary.onclick = function(){
        
         var optionsOne = document.getElementById('optionsOne');
         var optionsTwo = document.getElementById('optionsTwo');
         var errorMessage = '';
         
         if(optionsOne.value == 0){
            errorMessage = 'Select ' + document.querySelector('.label-one').innerHTML;
         }
         
         if(optionsTwo.value == 0){
            errorMessage = 'Select ' + document.querySelector('.label-two').innerHTML;
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