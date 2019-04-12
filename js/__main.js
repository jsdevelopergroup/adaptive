addEventListener("resize", function(event) {
  document.querySelector('.search-text').value = document.documentElement.clientWidth;
}, false);

window.addEventListener('load',topSearch,false);

//createXHR
//cssUpDown
//getSearchResults
//topSearch

function createXHR() {
    if (typeof XMLHttpRequest != "undefined") {
      return new XMLHttpRequest();
    } else if (typeof ActiveXObject != "undefined") {
      if (typeof arguments.callee.activeXString != "string") {
        var versions = ["MSXML2.XMLHttp.6.0", "MSXML2.XMLHttp.3.0", "MSXML2.XMLHttp"],
          i, len;
        for (i = 0, len = versions.lenght; i < len; i++) {
          try {
            new ActiveXObject(versions[i]);
            arguments.callee.activeXString = versions[i];
            break;
          } catch (ex) {}
        }
      }
      return new ActiveXObject(arguments.callee.activeXString);
    } else {
      throw new Error("No XHR object available");
    }
}
  
function cssUpDown(state,updownData){
      updownElem.style.display = state;
      if(updownData == 'TOP'){
        updownIcon = '&#8593;'
      }else{
        updownIcon = '&#8595;'
      }
      updownElem.querySelector('.updown-data').innerHTML = updownData;
      updownElem.querySelector('.updown-icon').innerHTML = updownIcon;    
   }
    
   var updownElem = document.getElementById('updown');
   var pageYLabel = 0;

   updownElem.onclick = function() {
      var pageY = window.pageYOffset || document.documentElement.scrollTop;
      switch (this.querySelector('.updown-data').innerHTML) {
        case 'TOP':
          pageYLabel = pageY;
          window.scrollTo(0, 0);
          cssUpDown('block','BACK');
          break;
       case 'BACK':
          window.scrollTo(0, pageYLabel);
      }
   }

   window.onscroll = function() {
      var pageY = window.pageYOffset || document.documentElement.scrollTop;
      var innerHeight = document.documentElement.clientHeight + 200;
      switch (updownElem.querySelector('.updown-data').innerHTML) {
        case '':
          if(pageY > innerHeight) {
             cssUpDown('block','TOP');
          }
          break;
         case 'TOP':
          if(pageY < innerHeight) {
             cssUpDown('none','TOP');
          } else {
             cssUpDown('block','TOP');
          }
          break;
         case 'BACK':
          if (pageY > innerHeight) {
              cssUpDown('block','TOP');
          }
         break;
       }
    }
    
function getSearchResults(inpTextValue){
    var xhr = createXHR();
    xhr.onreadystatechange = function() {
      if(xhr.readyState == 4) {
        if((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304) {
            
            if(xhr.responseText){
            
            var listJson = JSON.parse(xhr.responseText);
            var suggesstionBox = document.querySelector('.suggesstion-box');
            var resultColor;
            suggesstionBox.innerHTML='';
            console.log(xhr.responseText);
            
            if(listJson.length == 0){
               suggesstionBox.style.display = 'none'; 
            }else{
               suggesstionBox.style.display = 'block';
               
               
             
               
               if(listJson[0]['picture']=='picture' || listJson[0]['picture']=='numeric'){
                 

                 
                 var objectPicture = document.createElement("img");
                 objectPicture.setAttribute("data-link", listJson[0].object_link);
                 objectPicture.setAttribute("class", "ajax-img-data");
                 objectPicture.setAttribute("src", listJson[0].object_picture); 
                 
                 var objectName = document.createElement("div");
                 objectName.setAttribute("class", "ajax-img-name");
                 objectName.innerHTML = listJson[0].object_name; 
                 
                 var objectId = document.createElement("div");
                 objectId.setAttribute("class", "ajax-img-id");
                 
                 if(listJson[0]['picture']=='numeric'){
                    objectId.innerHTML = 'Image: '+listJson[0].picture_name;
                 }
                 if(listJson[0]['picture']=='picture'){
                     
                    objectId.innerHTML = 'Item: '+listJson[0].object_id;
                 }                 
                                                 
                 
                 suggesstionBox.appendChild(objectPicture);
                 suggesstionBox.appendChild(objectId);
                 suggesstionBox.appendChild(objectName);
                 
               }else{
                 for (var i = 0; i < listJson.length; i++) {
                 var resultLine = document.createElement("div");
                 resultLine.setAttribute("class", "result-line");
                 resultLine.setAttribute("celebrity-data", listJson[i].celebrity_data);
                 
                 resultColor = 'res-f-line';
                 if(i % 2 == 0) resultColor = 'res-s-line';
                 
                 
                 
                 resultLine.setAttribute("class", "result-line " + resultColor);
                 resultLine.innerHTML = listJson[i].celebrity_inner;
                 
                 if(listJson[i].celebrity_data == 'seemore'){
                    resultLine.style.fontWeight = 'bold';
                 }
                 
                 suggesstionBox.appendChild(resultLine);                
                 } 
               }
            }
         }  
            
        } else {
          alert("Request was unsuccesfull: " + xhr.status);
        }
      }
    };
    xhr.open("get", "/index.php?inpTextValue=" + inpTextValue, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.send(null);
}
    
function topSearch(){
      var inpText = document.querySelector('.search-text');
          inpText.onkeyup = function(e){

          //  alert(e.keyCode);
            
            if(e.keyCode == 27) {
               document.querySelector('.suggesstion-box').style.display = 'none';
               inpText.value = '';
               inpText.focus(); 
                
            }else if(e.keyCode == 13){
               
               
          
               if(document.querySelector('.ajax-img-data')){
                  window.location = document.querySelector('.ajax-img-data').getAttribute("data-link");
               }
               
               var celebrityDataList = document.querySelectorAll('.result-line');
               if(celebrityDataList.length > 0){
                  window.location = 'http://adaptive.idposter.loc/?searchQuery='+inpText.value.replace(" ", "+");
               }
                
               
               
            }else{
                if(inpText.value.length == 0){
                   document.querySelector('.suggesstion-box').style.display = 'none'; 
                } else {
                   if(inpText.value.length > 1){
                      getSearchResults(inpText.value);
                   }              
                }           
            }
             
            
       }
       
      var btnSearch = document.querySelector('.button-search');
      btnSearch.onclick = function(event) {
               if(document.querySelector('.ajax-img-data')){
                  window.location = document.querySelector('.ajax-img-data').getAttribute("data-link");
               }
                var celebrityDataList = document.querySelectorAll('.result-line');
               if(celebrityDataList.length > 0){
                  window.location = 'http://adaptive.idposter.loc/?searchQuery='+inpText.value.replace(" ", "+");
               }              
      }  
 
      var suggesstionBox = document.querySelector('.suggesstion-box');
           suggesstionBox.onclick = function(event) {
           
           if(event.target.className == 'ajax-img-name'){
              window.location = 'http://adaptive.idposter.loc/' + event.target.innerHTML.replace(" ", "_") + '/';
           }
           
           if(event.target.className == 'ajax-img-data'){
              window.location = event.target.getAttribute("data-link"); 
           } 
            
           if(event.target.className == 'result-line res-f-line' || event.target.className == 'result-line res-s-line'){
            
              var celebrityData = event.target.getAttribute("celebrity-data");
               
              if(celebrityData == 'seemore'){
                 window.location = 'http://adaptive.idposter.loc/?searchQuery='+inpText.value.replace(" ", "+");
              }else{
                 window.location = 'http://adaptive.idposter.loc/' + event.target.getAttribute("celebrity-data").replace(" ", "_").replace("%20", "_") + '/';
              }
              
           } 
      } 
}
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    