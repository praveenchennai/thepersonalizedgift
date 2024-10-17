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



function showPopup(static) {
window.open( "https://thepersonalizedgift.com/index.php?mod=cms&pg=cms&act=popup&static="+static, "",
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
<form method="POST" name="chpwd" action="" enctype="multipart/form-data" onSubmit="return chkform(this)">
<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
  <input type="hidden" name="id" value="{$MEM_DET.id}">
    <tr> 
      <td colspan=3 class="naH1">Home Page Details</td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid1>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span>
      </td>
    </tr>
    {/if}
	
	
	
	
	 <tr class=naGrid2> 
      <td valign="top" width="303"><div class="element_style" align="right"> Home page Welcome Text:</div></td>
      <td width="3" valign="top">&nbsp;</td> 
	  <td width="426"><textarea id="description" name="description" rows="5" cols="40" style="width:269px; " >{$STORE.description|default:"Design a thoughtful personalized gift in just a couple of minutes! Personalizing a gift for your special occasion is as easy as 1, 2, 3. Choose from any of the gift types below to get started"}</textarea></td>
	</tr>
	<tr class=naGrid1> 
      <td valign=top width=303><div align=right class="element_style">Home Page  Heading 1:</div></td> 
      <td width="3" valign=top>&nbsp;</td> 
	  <td><input name="heading1" type="text" class="input" id="heading1" value="{$STORE.heading1}" size="30" maxlength="37" /></td>
	</tr>
	<tr class=naGrid2> 
      <td valign=top width=303><div align=right class="element_style">Home Page Heading 2:</div></td> 
      <td width="3" valign=top>&nbsp;</td> 
	  <td><input name="heading2" type="text" class="input" id="heading2" value="{$STORE.heading2}" size="30" maxlength="50" /></td>
	</tr>
	<!-- <tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style">Redirect URL&nbsp;&nbsp;&nbsp;&nbsp;</div></td> 
      <td width=1 valign=top>:</td> 
	  <td><input name="redirect_url" type="text" class="input" id="redirect_url" value="{if $STORE.redirect_url eq '' }http://{else}{$STORE.redirect_url}{/if}"  style="width:269px; " /> &nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="showPopup('forward_masking');">What is this?</a>  </td>
	</tr> -->
	 <tr> 
   
	  
	   
          <td class="naGridTitle"  colspan="3" align="left" height="25" nowrap="nowrap">Search Engine Page Title and Meta Tags</td>
		  
	    
    </tr> 
	
	<tr class=naGrid2> 
      <td  width=303 valign="top"><div class="element_style" align="right"> Page Title:</div></td> 
      <td width="3" valign="top">&nbsp;</td> 
	  <td><textarea id="page_title" name="page_title" rows="5" cols="40" style="width:269px; " >{$STORE.page_title}</textarea></td>
	</tr>
	<tr class=naGrid1> 
      <td valign="top" width=303><div align=right class="element_style"> Meta Keyword:</div></td> 
      <td width="3" valign="top">&nbsp;</td> 
	  <td><textarea id="meta_keywords" name="meta_keywords" rows="5" cols="40" style="width:269px; " >{$STORE.meta_keywords}</textarea></td>
	</tr>
	<tr class=naGrid2> 
      <td valign="top" width=303><div align=right class="element_style"> Meta Description:</div></td> 
      <td width="3" valign="top">&nbsp;</td> 
	  <td><textarea id="meta_description" name="meta_description" rows="5" cols="40" style="width:269px; " >{$STORE.meta_description}</textarea></td>
	</tr>
	
	
	<!-- <tr class=naGrid2> 
      <td valign=top width=40%><div align=right class="element_style">Home Page Content</div></td> 
      <td width=1 valign=top>:</td> 
	  <td><textarea id="content" name="content" rows="20" cols="40">{$STORE.content}</textarea></td>
	</tr> -->
	 <!-- <tr class=naGrid2>
      <td valign=top align="right">Store Logo </td>
      <td valign=top>:</td>
      <td><input type="file" name="image" />
      (size= width:201 height:92)</td>
    </tr>
	{if file_exists("`$smarty.const.SITE_PATH`/modules/store/images/thumb/`$STORE.id`.jpg")}
    <tr class=naGrid1>
      <td valign=top align="right">&nbsp;</td>
      <td valign=top>&nbsp;</td>
      <td><img src="{$GLOBAL.mod_url}/images/thumb/{$STORE.id}.{$STORE.image_extension}?{$DATE}" /></td>
    </tr>
	{/if} -->
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr>
	<tr><td colspan=3 valign=center>&nbsp;</td></tr> 
</table>
</form> 