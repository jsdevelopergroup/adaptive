<style type="text/css">
.show_response {
    text-align:center;
    font-size:18px;
    color:#fc5300;
    width:400px;
    height:300px;
    margin: 0 auto;
    display: none;
}

.contact-form {
   
    width:600px;
    margin: 0 auto;
   
}

.data-line {
   width:100%;
   height: 65px;
   border-top: 1px solid #ccc;
    
  
}

.title-inp, .html-inp {
    display: inline-block;
   
}

.title-inp {
    float: left;
    font-size: 16px;
    color:#111;
    width:130px;
    text-align: right;
    margin:16px;
    font-family:  'Open Sans', sans-serif;
}

.html-inp {
    float: right;
    font-size: 16px;
    color:#111;
    width:410px;
    text-align: right;
    margin:10px;
}

.name, .email, .message, .amount {
    width:388px;
    padding: 10px;
    font-size: 16px;
    font-family:  'Open Sans', sans-serif;
    border: 1px solid #999;
}

.topic {
    width:410px;
        padding: 10px;
    font-size: 16px;
    border: 1px solid #999;
}

select option {
   font-size: 16px; 
}

.amount {
    width:100px;
    text-align: left;
}

.mess-line {
    height: 300px;
}

.mess-heig {
    height: 250px; 
}
.html-heig {
     height: 280px;
}

.al {
    text-align: left;
}

.amount, .code, .refresh {
       display: inline-block;
}

.refresh {
    cursor: pointer;
    text-decoration: underline;
    color: #0080FF;
}

.send-message {
    padding:10px;
    font-size:16px;
    background-color: #DF4B13;
    cursor: pointer;
   border: 1px solid transparent;
   color: #ffffff;
}

.result-mess {
    padding:20px;
    border: 1px solid #999;
    font-size:20px;
    text-align: center;
    color: #008040;
     width:560px;
     margin: 0 auto;
     line-height: 30px;
     display: none;
     margin-bottom:20px;
}

.send-more {
    margin-top: 30px;
    cursor: pointer;
    text-decoration: underline;
    color: #D55509;
}



</style>

<h1 align="center">Contact us:</h1>

<div class="result-mess">

<div class="data-mess"> </div>
<div class="send-more">Send again</div>
</div>

<div class="contact-form">

<form id="contactForm" method="post">

<div class="data-line">
<div class="title-inp">Your name:</div>
<div class="html-inp"><input type="text" class="name" maxlength="20" /></div>
</div>

<div class="data-line">
<div class="title-inp">Topic:</div>
<div class="html-inp">
<select class="topic">
<option value="1">My Orders</option>
<option value="2">Technical problems</option>
<option value="3">Other questions</option>
</select>
</div>
</div>

<div class="data-line"> 
<div class="title-inp">Your e-mail:</div>
<div class="html-inp"><input  type="text" class="email" maxlength="30" /></div>
</div>

<div class="data-line mess-line">
<div class="title-inp">Your message:</div>
<div class="html-inp html-heig"><textarea class="message mess-heig"></textarea></div>
</div>

<div class="data-line">
<div class="title-inp">Enter amount:</div>
<div class="html-inp al">

<div class="code"></div>
<input type="text" class="amount" maxlength="3" />
</div>
</div>

<div class="data-line">
<div> </div>
<div class="html-inp"><input type="submit" class="send-message" value="Send message" /></div>
</div>

</form>
</div>