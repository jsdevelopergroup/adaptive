  addEventListener('load', function() {
     getDataProduct();
  }, false);
  
  addEventListener('click', function() {
     optionLineClick();
  }, false);

  function roundPlus(x, n) {
     var m = Math.pow(10, n);
     return Math.round(x * m) / m;
  }

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

  function updatePrices(oneId, productId, propertyId, optionBlock, propertyTitle, currentQty, i) {
    optionBlock.innerHTML = '';
    var xhr = createXHR();
    xhr.onreadystatechange = function() {
      if(xhr.readyState == 4) {
        if((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304) {
          var subListJson = JSON.parse(xhr.responseText);
          for(var j = 0; j < subListJson.length; j++) {
              showDataSubBlock(subListJson, j, i, optionBlock, propertyTitle, currentQty);
          }
        } else {
          alert("Request was unsuccesfull: " + xhr.status);
        }
      }
    };
    xhr.open("get", "/index.php?one_id=" + oneId + "&product_id=" + productId + "&property_id=" + propertyId, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.send(null);
  }

  // #2 - 36" x 56" or Semi-Gloss $35.99 or Grayscale : OPTION LINE CLICK
  function optionLineClick() {

    var cartBlock = document.querySelector('.cart-block');
    var optionLine = cartBlock.querySelectorAll('.option-line');
    var optionLength = optionLine.length - 1;

    var propertyLine = document.querySelectorAll('.prorerty-line');
    var propertyLength = propertyLine.length - 1;
    var currentQty = +propertyLine[propertyLength].childNodes[1].innerHTML;

    for(var i = 0; i < optionLine.length; i++) {
        
       (function(i) {optionLine[i].onclick = function() {
        
          var currentBlock = this.parentNode.querySelectorAll('.option-line');
          for(var j = 0; j < currentBlock.length; j++) {
              currentBlock[j].childNodes[1].style.fontWeight = 'normal';
              currentBlock[j].childNodes[2].style.fontWeight = 'normal';
          }

          this.childNodes[0].childNodes[0].checked = true;
          this.childNodes[1].style.fontWeight = 'bold';
          this.childNodes[2].style.fontWeight = 'bold';

          var bydefId = this.getAttribute("data-id");

          this.parentNode.previousElementSibling.childNodes[1].innerHTML = this.childNodes[1].innerHTML;
          this.parentNode.previousElementSibling.childNodes[1].setAttribute('bydef-id', bydefId);
          this.parentNode.style.display = 'none';

          var prevParentId = this.parentNode.previousElementSibling.getAttribute('num-id');

          // SIZE CLICK
          if(prevParentId == 0) {
             var oneId = this.getAttribute('data-id');
             var productId = document.querySelector('.cart-block').getAttribute('product-id');
             var propertyId = this.parentNode.nextElementSibling.getAttribute('property-id');
             var prorertyBlock = this.parentNode.nextElementSibling.nextElementSibling;
             var propertyTitle = this.parentNode.nextElementSibling.childNodes[0].innerHTML;
             updatePrices(oneId, productId, propertyId, prorertyBlock, propertyTitle, currentQty, i);
          }

          // PAPER CLICK
          if(prevParentId == 1) {
             var currentPrice = +this.childNodes[2].innerHTML.replace('$','');
             document.querySelector('.input-price').setAttribute("data-price", currentPrice);
             document.querySelector('.per-price-value').innerHTML = currentPrice;
             var currentPriceQty = currentQty * currentPrice;
             document.querySelector('.input-price').innerHTML = roundPlus(currentPriceQty, 2);
          }

          // QTY CLICK
          if(this.parentNode.previousElementSibling.childNodes[0].innerHTML == 'Qty') {
             var currentPrice = document.querySelector('.input-price').getAttribute('data-price');
             var qty = +this.childNodes[1].innerHTML;
             var resQty = currentPrice * qty;
             var resQty = roundPlus(resQty, 2);
             document.querySelector('.input-price').innerHTML = resQty;
          }
        }
      })(i);
    }
  }

  // #1 - Size or Paper or Grayscale or Qty : PROPERTY LINE CLICK
  function propertyLineClick(flagDefaults) {

    var cartBlock = document.querySelector('.cart-block');
    var prorertyLine = cartBlock.querySelectorAll('.prorerty-line');
    var optionBlock = cartBlock.querySelectorAll('.option-block');
    var paddTop = 39;
    
    if(flagDefaults == false){
       optionBlock[0].childNodes[0].childNodes[0].childNodes[0].checked = true;
    }       
 
    for (var i = 0; i < prorertyLine.length; i++) {
      (function(i) {
        prorertyLine[i].onclick = function() {
          for (var j = 0; j < optionBlock.length; j++) {
            if (j != i) {
              optionBlock[j].style.display = 'none';
            }
          }
          optionBlock[i].style.borderBottom = '1px solid #bbbaba';
          var styleTop = ((i + 1) * paddTop);
          optionBlock[i].style.top = styleTop + 'px';
          optionBlock[i].style.display = (optionBlock[i].style.display == 'block') ? 'none' : 'block';
          prorertyLine[i].childNodes[2].childNodes[0].innerHTML = (optionBlock[i].style.display == 'block') ? '&#9650;' : '&#9660;';
        }
      })(i);
    }
  }

  function showDataSubBlock(listJsonData, j, i, prorertyBlock, propertyTitle, currentQty) {

    // alert(currentQty);
    var optionLine = document.createElement("div");
    optionLine.setAttribute("class", "option-line");
    optionLine.setAttribute("data-id", listJsonData[j].id);
    // ���������� ����� � ����
    prorertyBlock.appendChild(optionLine);

    // #1.1 �����
    var radioOptionLine = document.createElement("div");
    var radioHtml = document.createElement("input");
    radioHtml.setAttribute("type", "radio");
    radioHtml.setAttribute("value", listJsonData[j].title);
    radioHtml.setAttribute("data-id", listJsonData[j].id);
    radioHtml.setAttribute("class", "radio-html");
    radioHtml.setAttribute("class", "radio-html");

    radioHtml.setAttribute("name", propertyTitle);

    radioOptionLine.setAttribute("class", "radio-line");
    // ���������� ����� ��� ����� � �����
    optionLine.appendChild(radioOptionLine);
    // ���������� ����� � ����
    radioOptionLine.appendChild(radioHtml);

    // #1.2 Tile (Value)
    var valueOptionLine = document.createElement("div");
    valueOptionLine.setAttribute("class", "value-line");
    valueOptionLine.innerHTML = listJsonData[j].title;
    optionLine.appendChild(valueOptionLine);

    // #1.3 Price
    var priceOptionLine = document.createElement("div");
    priceOptionLine.setAttribute("class", "price-line");
    
    priceOptionLine.innerHTML = (listJsonData[j].price != null) ? '$'+listJsonData[j].price : '';
    
    // priceOptionLine.setAttribute("line-price", "price-line");
    optionLine.appendChild(priceOptionLine);
    priceOptionLine.style.fontWeight = (listJsonData[j].bydef == 1) ? 'bold' : 'normal';
    valueOptionLine.style.fontWeight = (listJsonData[j].bydef == 1) ? 'bold' : 'normal';

    // SET PRICE
    if(listJsonData[j].bydef == 1) {
      if(listJsonData[j].price != null) {
         var currentPrice = listJsonData[j].price;
         var resQty = currentPrice * currentQty;
         var currentPriceQty = roundPlus(resQty, 2);
         document.querySelector('.input-price').innerHTML = currentPriceQty;
         document.querySelector('.input-price').setAttribute("data-price", currentPrice);
         document.querySelector('.per-price-value').innerHTML = currentPrice;
       }
    }
    radioHtml.checked = (listJsonData[j].bydef == 1) ? true : false;
  }

  function showDataCart(dataHash, imageWidth, imageHeight) {

    var xhr = createXHR();
    xhr.onreadystatechange = function() {
      if(xhr.readyState == 4) {
        if((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304) {

          //console.log(xhr.responseText);
          var listJson = JSON.parse(xhr.responseText);
          var cartBlock = document.querySelector('.cart-block');
          var prorertyLine = cartBlock.querySelectorAll('.prorerty-line');
          var optionBlock = cartBlock.querySelectorAll('.option-block');
          var optionLine = cartBlock.querySelectorAll('.option-line');
          
          var flagDefaults = false;
          var currentQty = 1;

          cartBlock.innerHTML = '';

          for (var i = 0; i < listJson.length; i++) {

            // #1 ��� ������
            var prorertyLine = document.createElement("div");
            prorertyLine.setAttribute("class", "prorerty-line");
            prorertyLine.setAttribute("num-id", i);
            prorertyLine.setAttribute("property-id", listJson[i].id);
            prorertyLine.setAttribute("property-data", listJson[i].title);
            // ���������� � ���� �����
            cartBlock.appendChild(prorertyLine);

            // #2 Size
            var prorertyTitle = document.createElement('div');
            prorertyTitle.setAttribute("class", "prorerty-title");
            prorertyTitle.innerHTML = listJson[i].title;
            // ���������� Size � �����
            prorertyLine.appendChild(prorertyTitle);

            // #3 42" x 60"
            var prorertyBydef = document.createElement('div');
            prorertyBydef.setAttribute("class", "prorerty-bydef");
            prorertyBydef.innerHTML = listJson[i].bydef;
            prorertyBydef.setAttribute("bydef-id", listJson[i].bydef_id);
            // ���������� 42" x 60" � �����
            prorertyLine.appendChild(prorertyBydef);

            // #4 ����������� ������� ����
            var prorertyIcon = document.createElement('div');
            prorertyIcon.setAttribute("class", "prorerty-icon");
            
            var prorertyIconSub = document.createElement('div');
            prorertyIconSub.setAttribute("class", "prorerty-icon-sub");
            prorertyIconSub.innerHTML = '&#9660;';
        
            prorertyLine.appendChild(prorertyIcon);
            prorertyIcon.appendChild(prorertyIconSub);

            if (listJson[i].options.length > 0) {
              // ������������� ����(� ������, �������) �� ����������
              var prorertyBlock = document.createElement('div');
              prorertyBlock.setAttribute("class", "option-block");
              cartBlock.appendChild(prorertyBlock);
              
              for(var j = 0; j < listJson[i].options.length; j++) {
                   showDataSubBlock(listJson[i].options, j, i, prorertyBlock, listJson[i].title, currentQty);
                   if(i == 0 && flagDefaults == false){
                      if(listJson[0].options[j].bydef == 1){
                        flagDefaults = true;
                      }
                   }
                }
             }
          }
          propertyLineClick(flagDefaults);
        } else {
          alert("Request was unsuccesfull: " + xhr.status);
        }
      }
    };
    xhr.open("get", "/index.php?product_id=" + dataHash + "&imageWidth=" + imageWidth + "&imageHeight=" + imageHeight, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.send(null);
  }

  function setSpan(product, dataId) {
    var elemProduct = document.querySelector('h1 span');
    var lastItem = document.querySelector('.lastItem product');
    document.querySelector('.cart-block').setAttribute('product-id', dataId);
    var productDread = product;
    if (product == 'tank-top') productDread = 'tank top';
    if (product == 'calendar') productDread = 'calendar 2019';
    if (product == 'mousepad') productDread = 'mouse pad';
    elemProduct.innerHTML = productDread;
    lastItem.innerHTML = productDread;
  }

  function getHashValue(key, index) {
    var matches = location.hash.match(new RegExp(key + '([^&]*)'));
    return matches ? matches[index] : null;
  }

  function getDataProduct() {
    var product = getHashValue('#', 1);
    var itemProduct = document.querySelectorAll('.pro-title');
    var flagHash = (product) ? false : true;

    var imageHeight = document.querySelector('.image-height').innerHTML;
    var imageWidth = document.querySelector('.image-width').innerHTML;
 
    for(var i = 0; i < itemProduct.length; i++) {
      if(product) {
         var dataHash = itemProduct[i].getAttribute('data-hash');
         var dataId = itemProduct[i].getAttribute('data-id');
         if(dataHash == product) {
            setSpan(product, dataId);
            showDataCart(product, imageWidth, imageHeight);
            flagHash = true;
         }
      }
      
      itemProduct[i].onclick = function() {
        
         for(var j = 0; j < itemProduct.length; j++){
             itemProduct[j].style.backgroundColor = '#EDCCB8';
             itemProduct[j].style.color = '#333333';
         }        
         
         this.style.backgroundColor = '#d86f41';
         this.style.color = '#ffffff';
        
         var dataHash = this.getAttribute('data-hash');
         var dataId = this.getAttribute('data-id');
         setSpan(dataHash, dataId);
         window.location.hash = dataHash;
         showDataCart(dataId, imageWidth, imageHeight);
         
      }
    }
    
    if(flagHash == false) {
       window.location = location.href.replace(location.hash, "");
    }
    
  }