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
</style>

<form id="contactForm" action="<?=SITE_NAME?>/send_message/" method="post">
<h1 align="center">Contact us:</h1>
<div class="show_response"></div>
<table class="contactForm">
<tr>
<td align="right">First name:</td>
<td>
<input type="text" name="name" maxlength="20" style="width:335px;" value="" />
</td>
</tr>
<tr>
<td align="right">E-Mail address:</td>
<td>
<input  type="text" name="email" maxlength="30" style="width:335px;" value="" />
</td>
</tr>
<tr>
<td align="right">Enquiry:</td>
<td>
<textarea  name="enquiry" cols="40" rows="10"></textarea>
</td>
</tr>
<tr>
<td></td>
<td align="left">
<div id="code" class="code"></div>
<a href="javascript:void(0);" id="refresh" class="refresh">Refresh</a>
</td>
</tr>
<tr>
<td align="right">Enter the sum of numbers:</td>
<td align="left">
<input type="text" id="capcha" class="capcha" maxlength="6" name="captcha" value="" />
</td>
</tr>
<tr>
<td></td>
<td align="left" height="70px">
<input type="submit" class="button" style="width:350px;" value="Send message" />
</td>
</tr>
</table>
</form>