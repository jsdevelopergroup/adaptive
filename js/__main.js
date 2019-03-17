addEventListener("resize", function(event) {
    
    
 // console.log('resized');
  
  
  document.getElementById('search-text').value = document.documentElement.clientWidth;
  
}, false);