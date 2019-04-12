var yName = document.querySelector('.name');
var tOpic = document.querySelector('.topic');
var yEmail = document.querySelector('.email');
var yMess = document.querySelector('.message');
var aMount = document.querySelector('.amount');

window.addEventListener('load',trainMessage,false);
window.addEventListener('load',sendMore,false);

//sendMore
//sendMessage
//randomInteger
//validateEmail
//trainMessage

function sendMore(){
     var sendMore = document.querySelector('.send-more');
     sendMore.onclick = function(){  
        window.location = location.href;   
     }
}
  
function sendMessage(yName, tOpic, yEmail, yMess) {
    var xhr = createXHR();
    xhr.onreadystatechange = function() {
      if(xhr.readyState == 4) {
        if((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304) {
            document.querySelector('.result-mess').style.display = 'block';
            document.querySelector('.contact-form').style.display = 'none';
            var dataMess;
            if(xhr.responseText == 1){
               dataMess = 'Thank you for contacting us! You will receive a response from our team within 12 hours';
            } else {
               dataMess = 'Your message has not been sent!';
               document.querySelector('.data-mess').style.color = 'red';
            } 
            document.querySelector('.data-mess').innerHTML = dataMess;
        } else {
          alert("Request was unsuccesfull: " + xhr.status);
        }
      }
    };
    xhr.open("get", "/index.php?yName=" + yName + "&tOpic=" + tOpic + "&yEmail=" + yEmail + "&yMess=" + yMess, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.send(null);
} 
  
function randomInteger(min, max) {
    var rand = min - 0.5 + Math.random() * (max - min + 1)
    rand = Math.round(rand);
    return rand;
}   

function validateEmail(email) {
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}

function trainMessage(){
    var fInt = randomInteger(1, 9);
    var sInt = randomInteger(1, 9);
    var amRes = fInt + sInt;
    document.querySelector('.code').innerHTML = fInt + ' + ' + sInt + ' = ';  
    var sendMesBtn = document.querySelector('.send-message');
    sendMesBtn.onclick = function(){
        var dataFlag = false;
        var intFields = [yName, tOpic, yEmail, yMess, aMount];
        for(var i = 0; i < intFields.length; i++) {
            if(intFields[i].value == ''){
               intFields[i].style.border = '1px solid red';
               dataFlag = true;
            }else{
               intFields[i].style.border = '1px solid #999999';  
            }
        }
        if(aMount.value != amRes){
           aMount.style.border = '1px solid red';
           dataFlag = true;            
        }
        if(validateEmail(yEmail.value)==false){
           yEmail.style.border = '1px solid red';
           dataFlag = true;    
        }
        if(dataFlag == false){
           sendMessage(yName, tOpic, yEmail, yMess);
           for(var i = 0; i < intFields.length; i++) {
               intFields[i].style.border = '1px solid #999999';
           }
           return false;
        }
        return false;
      }
      return false;
   }