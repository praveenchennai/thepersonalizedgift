<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>
{literal}
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
function show_state(opt_name,country_id,state_name) 
{
	document.getElementById('div_state').innerHTML="Loading....";
	var req1 = newXMLHttpRequest();
	req1.onreadystatechange = getReadyStateHandler(req1, serverRese);
	str="opt_name="+opt_name+"&country_id="+country_id+"&state_name="+state_name;
	{/literal}
	req1.open("POST", "{$smarty.const.SITE_URL}/index.php?mod=member&pg=ajax_state");
	{literal}
	req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	req1.send(str);
	}
	function serverRese(_var) {
	_var = _var.split('|');
	//alert(_var);
	document.getElementById('div_state').innerHTML=_var[0];
}

function Validate(form) {
    var v = new RegExp();
    v.compile("^[A-Za-z]+://[A-Za-z0-9-_]+\\.[A-Za-z0-9-_%&\?\/.=]+\\.[A-Za-z0-9-_%&\?\/.=]+$");
    if (!v.test(form["redirect_url"].value)) {
        alert("You must supply a valid URL.");
        return false;
    }else
	{
	return true;
	}
}
function chkform(form)
{
			var str2=document.chpwd.password.value;
			var str3=document.chpwd.confirm_pass.value;
			
	if(chk(form))
	{
			/*if (str2.length<6)
			{
				alert("Password length is too short");
				return false;		
			}
			else if(str2!=str3)
			{
				alert("Passwords are not matching");
				return false;		
			}
			else
			{*/
				return true;
			//}	
	}
	else
	{
	return false;
	}

}
function fillText(val)
{

var sp=val.split("//");
var ar=sp[1].split(".");
//alert(ar.length);
var str="";
for(var i=1;i<ar.length;i++)
{
 if(i==1)
 {
 str=str+ar[i];
 }else
 {
 str=str+"."+ar[i];
 }
}

 document.getElementById("div_dom_name").innerHTML="";
  document.getElementById("div_store_name").innerHTML="";
	document.getElementById("div_store_name").innerHTML=ar[0];
	if(ar[0]=="www"){
	document.getElementById("div_dom").style.display="inline";
	document.getElementById("td_dom").style.display="inline";
	document.getElementById("div_dom_name").innerHTML=str;
	}else
	{
	document.getElementById("div_dom").style.display="none";
	document.getElementById("td_dom").style.display="none";
	}
}
function getKeyCode(e) {
 if (window.event)
    return window.event.keyCode;
 else if (e)
    return e.which;
 else
    return null;
}
function _keyCheck(e) {

	 key = getKeyCode(e);
	 var url_value = document.getElementById('redirect_url').value
	 var url_check = url_value.search('http:')
	
	if ( key==null || key==0 || key==8 || key==9 || key==13 || key==27 )
		return true;
	else if (url_check==0 && key==58)
		return false;	
	else if ((key > 96 && key < 123) || (key > 45 && key <= 58))
		return true;
	else if (key==45)
		return true;		
	else
		return false;
}


function showPopup(static) {
//window.open( "http://thepersonalizedgift.com/index.php?mod=cms&pg=cms&act=popup&static="+static, "", 
//"status = 1, height = 400,scrollbars=1, width = 600,left=300,top=190, resizable = 0" )

window.open( "http://192.168.1.254/thepersonalizedgift/index.php?mod=cms&pg=cms&act=urlpopup&static="+static+"&url="+document.chpwd.redirect_url.value, "", 
"status = 1, height = 400,scrollbars=1, width = 600,left=300,top=190, resizable = 0" )
}



</SCRIPT>
{/literal}
{literal}
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
	//var fields=new Array('reg_pack','first_name','last_name','email','username','password','confirm_pass','address1','country','postalcode');
	//var msgs=new Array('Registration Package','First Name','Last Name','Email','Username','Password','Confirm Password','Address','Country','Zip Code');
	
	var fields=new Array('first_name','last_name','password','confirm_pass','address1','country','postalcode','telephone','memmail');
	var msgs=new Array('First Name','Last Name','Password','confirm password','Address','Country','Zip Code','Phone Number','E-mail');	
</SCRIPT>
{/literal}	
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form method="POST" name="chpwd" action="" enctype="multipart/form-data" onSubmit="return Validate(this);">
<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
  <input type="hidden" name="id" value="{$MEM_DET.id}">
    <tr> 
      <td colspan=3 class="naH1">Redirect URL Info</td> 
    </tr> 
	 <tr class="naGridTitle"> 
      <td colspan=3 ><strong>You can redirect your own URL (website address) to your web-store by following these instructions.</strong></td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid1>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span>
      </td>
    </tr>
    {/if}
	
	
	

 <tr class="naGrid1" > 
      <td valign=top width=17% align="right"><strong><span>Redirect URL:</span></strong></td> 
      
	  <td valign=top width=2% align="right">&nbsp;</td>
<td  align="left"><input name="redirect_url"  type="text" class="input" id="redirect_url" value="{if $REURL eq '' }http://{else}{$REURL}{/if}"  style="width:269px; "  onkeypress="return _keyCheck(event);"  autocomplete="off"  onKeyUp="fillText(this.value);" /> &nbsp;&nbsp;&nbsp;&nbsp;<!-- <a href="#" onClick="showPopup('forward_masking');">What is this?</a>  --></td>
	</tr>
	
		<tr class="naGrid2" > <td valign=top colspan="3" class="naGrid1"> 
         <table border="0" width="100%" cellpadding="0" cellspacing="0"><tr>
		 <td width="16%">&nbsp;</td>
		 <td width="84%">
		   <p  align="left"><font size="2" face="Arial"><span style="font-size: 10pt; font-family: Arial;">Enter the URL website
	        name that you are redirecting to your web-store.<br /> Format: <em><em><font face="Arial"><span style="font-family: Arial;"><strong>http://www.yoursite.com</strong></span></font></em></em></span></font></p></td> 
         </tr>
         </table>
			
			</td>
			<tr class="naGrid2"><td colspan="3" align="justify">
			<table><tr ><td>&nbsp;</td><td>
<strong><font size="2" face="Arial"><span style="font-size: 11pt; font-family: Arial; font-weight: bold;">How to
point your URL to your web-store:</span></font></strong><font size="2" face="Arial"><span style="font-size: 10pt; font-family: Arial;"><br />
To properly redirect your URL website traffic to your web-store, you
will need to make the following DNS changes in your website domain
control panel. Contact your domain registrar for assistance as needed.</span></font></td></tr>
</table>
</td></tr>
<tr class="naGrid2"><td colspan="3">
<table border="0"><tr><td>&nbsp;</td><td><strong><font size="2" face="Arial"><span style="font-size: 11pt; font-family: Arial; font-weight: bold;">Important
Note:</span></font></strong><font size="2" face="Arial"><span style="font-size: 10pt; font-family: Arial;"> Do not attempt to <em><span style="font-style: italic;">Forward and Mask</span></em> your URL to
your webstore as some elements of your webstore will not function as
intended. While logged into your domain registrar account (i.e.
godaddy.com) go to your domain DNS control page and follow these
instructions.</span></font></td></tr>
</table>
</td></tr>

<tr class="naGrid2"><td colspan="3">
<font size="2" face="Arial"><span style="font-size: 10pt; font-family: Arial;"><o:p /></span></font>
<ol type="1" start="1" style="margin-top: 0in;"><li style="margin-bottom: 6pt;" class="MsoNormal"><font size="2" face="Arial"><span style="font-size: 10pt; font-family: Arial;">If your
existing URL is currently set up with a DNS A (host) record that points
to your registrar's IP address, remove that A (host) record. <o:p /></span></font></li><li style="margin-bottom: 6pt;" class="MsoNormal"><font size="2" face="Arial"><span style="font-size: 10pt; font-family: Arial;">Add a new
A (Host) record as follows: <br />
A Host Name = <strong><span style="font-weight: bold;">@</span></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Pointing to IP address: <strong><span style="font-weight: bold;">216.154.209.42</span></strong><o:p /></span></font></li><li style="margin-bottom: 6pt;" class="MsoNormal"><font size="2" face="Arial"><span style="font-size: 10pt; font-family: Arial;">Add a new
CNAME records as follows: <br />
Alias Name = <strong><span style="font-weight: bold;">www</span></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Pointing to Host Name: <strong><span style="font-weight: bold;">thepersonalizedgift.com</span></strong></span></font></li></ol>
</td></tr>
				</td>
		</tr>		
	<tr class="naGrid2">
	    <td colspan="2">&nbsp;</td>
	
	</tr>
	</tr>
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr>
	<tr><td colspan=4 valign=center height="25">&nbsp;</td></tr> 
</table>
</form> 
<br /><br />