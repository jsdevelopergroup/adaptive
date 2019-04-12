window.addEventListener('load',showImage,false);

function showImage(){
    var object_id = document.querySelectorAll('.object_id');
    for(var i=0;i<object_id.length;i++){
        object_id[i].onmouseover = function(){
            var dataPath = 'img-'+this.getAttribute("data-path");
            document.getElementById(dataPath).style.display = 'block';
        } 
        object_id[i].onmouseout = function(){
            for(var i=0;i<object_id.length;i++){
               var dataPath = 'img-' + object_id[i].getAttribute("data-path");
               document.getElementById(dataPath).style.display = 'none';
            } 
        }       
    }
}

function getResponse(v,n,cb){
  var xhr = new XMLHttpRequest();
  var body = v + '=' + encodeURIComponent(n);
  xhr.open("POST", '/request/', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
  xhr.send(body);
  xhr.onreadystatechange = function() { 
  if(xhr.readyState == 4) {
     if(xhr.status == 200) {
        cb( xhr.responseText );             
     } 
  } 
 }
}

function show(state,data){
  getResponse('product',data, function(response){
    
     alert(response);
     /*  
     document.getElementById('window').style.display = state;			
     document.getElementById('wrap').style.display = state;
     
     document.getElementById('title-product').innerHTML = response.title;
     
     document.getElementById('title').value = response.title;
     document.getElementById('url').value = response.url;
     document.getElementById('text').innerHTML = response.text;
     */
     
  });
}

function request(targetDiv,name,vars){
  var xhr = new XMLHttpRequest();
  var body = vars + '=' + encodeURIComponent(name);
  xhr.open("POST", '/request/', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
 
  xhr.send(body);
  xhr.onreadystatechange = function() 
  { if(xhr.readyState == 4){
    
     
    
    if(xhr.status == 200) {
    
    document.getElementById(targetDiv).innerHTML = xhr.responseText;
  
  } } }
}

function createBackup(){

  request('indicat',true,'create_backup'); 

}
