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
	if ( key==null || key==0 || key==8 || key==9 || key==13 || key==27 )
		return true;
	else if ((key > 96 && key < 123) || (key > 45 && key < 58))
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
	 <tr class="naGrid2"> 
      <td colspan=3 >&nbsp;</td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid1>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span>
      </td>
    </tr>
    {/if}
	
	
	<!-- <tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style">Redirect URL&nbsp;&nbsp;&nbsp;&nbsp;</div></td> 
      <td width=1 valign=top>:</td> 
	  <td><input name="redirect_url"  type="text" class="input" id="redirect_url" value="{if $REURL eq '' } http://{else}{$REURL}{/if}"  style="width:269px; " /> &nbsp;&nbsp;&nbsp;&nbsp;<!-- <a href="#" onClick="showPopup('forward_masking');">What is this?</a>  </td>
	</tr> -->

 <tr class="naGrid1" > 
      <td valign=top width=18%><strong><font size="2" face="Arial"><span>Redirect URL :&nbsp;&nbsp;&nbsp;&nbsp;</span></font></strong></td> 
      
	  <td align="left"><input name="redirect_url"  type="text" class="input" id="redirect_url" value="{if $REURL eq '' } http://{else}{$REURL}{/if}"  style="width:269px; "  onkeypress="return _keyCheck(event);"  autocomplete="off"  onKeyUp="fillText(this.value);" /> &nbsp;&nbsp;&nbsp;&nbsp;<!-- <a href="#" onClick="showPopup('forward_masking');">What is this?</a>  --></td>
	</tr>
	
		<tr class="naGrid2" ><td colspan="3">
				<p><font size="2" face="Arial"><span style="font-size: 10pt; font-family: Arial;"><strong><span style="font-weight: bold;"></span></strong>  </span></font><font size="2" face="Arial"><span style="font-size: 10pt; font-family: Arial;">Enter your redirect URL in the format <em>http://www.yoursite.com</em></span></font></p><p><font size="2" face="Arial"><span style="font-size: 10pt; font-family: Arial;"></span></font><strong><font size="2" face="Arial"><span style="font-weight: bold; font-size: 10pt; font-family: Arial;">How to point your URL to your web-store:</span></font></strong><font size="2" face="Arial"><span style="font-size: 10pt; font-family: Arial;"><br /></span></font><font size="2" face="Arial"><span style="font-size: 10pt; font-family: Arial;">To
properly redirect your existing website traffic to your web-store, you
should contact your domain registrar and ask for assistance with creating the
CNAME records explained below.</span></font></p><p><font size="2" face="Arial"><span style="font-size: 10pt; font-family: Arial;"><strong><span style="font-weight: bold;"></span></strong><strong><span style="font-weight: bold;">Important Note:</span></strong> Some domain registrars (including godaddy.com) utilize a <em>shortcut</em>
approach to redirecting website traffic which will prevent some areas of
your web-store from functioning properly. With that understanding, be
sure to explain the following to your registrar. <br /></span></font><font size="2" face="Arial"><span style="font-size: 10pt; font-family: Arial;">1.
If your existing domain URL is set up with a DNS A (host) record that points to your registrar's IP address, that <strong>A (host) record must be removed</strong>. <br /></span></font><font size="2" face="Arial"><span style="font-size: 10pt; font-family: Arial;">2. The below CNAME records must be created as specified or some elements of your web-store will not function properly.<br /></span></font></p>

				</td>
		</tr>		
				
	<tr class="naGrid1" >
	<td colspan="3">
	
	<table width="100%" border="0">
	<tr><td  colspan="3">
	<font size="2" face="Arial"><span><strong><span>CNAME records to create: </span></strong>(provide this information to your domain registrar)<br /></td></tr>
	<td width="50%"> 1. Alias Name: <strong><span id="div_store_name">{$ALIAS}</span></strong></td>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
	          <td>   Pointing To Host Name: <strong>thepersonalizedgift.com</strong><br />
				 
				 </td></tr>
				 <tr><td width="50%">
	<div id="div_dom"  {if $ALIAS eq "www"} style="display:block;" {else} style="display:none;" {/if}> 
	2. Alias Name: <strong><span id="div_dom_name">{$DOMAIN}</span></strong>  </td>
	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
	<td  id="td_dom" {if $ALIAS eq 'www'} style="display:block;" {else} style="display:none;" {/if}>
	 Pointing To Host Name: <strong>thepersonalizedgift.com</strong> </td> </div></tr></font>
	 </table>
	</td>
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
</table>
</form> 
<br /><br />