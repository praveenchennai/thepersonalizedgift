<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></script>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>


{literal}
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
/*
show search details 
*/
function show_state(opt_name,country_id,state_name) 
{
	document.getElementById('div_state').innerHTML="Loading....";
	var req1 = newXMLHttpRequest();
	req1.onreadystatechange = getReadyStateHandler(req1, serverRese);
	str="opt_name="+opt_name+"&country_id="+country_id+"&state_name="+state_name;
	{/literal}
	req1.open("POST", "{makeLink mod=member pg=ajax_state}{/makeLink}&"+Math.random());
	{literal}
	req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	req1.send(str);
	}
	function serverRese(_var) {
	_var = _var.split('|');
	document.getElementById('div_state').innerHTML=_var[0];
}


function show_state(opt_name,country_id,state_name) 
{
	document.getElementById('div_state').innerHTML="Loading....";
	//alert(document.getElementById('country').label)
	//country_name=document.getElementById('country')[document.getElementById('country').selectedIndex].innerHTML;
	var req1 = newXMLHttpRequest();
	req1.onreadystatechange = getReadyStateHandler(req1, serverRese);
	str="opt_name="+opt_name+"&country_id="+country_id+"&state_name="+state_name;
	{/literal}
	req1.open("POST", "{makeLink mod=member pg=ajax_state}{/makeLink}&"+Math.random());
	{literal}
	req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	req1.send(str);
	}
	function serverRese(_var) {
	//alert(_var);
	_var = _var.split('|');
	document.getElementById('div_state').innerHTML=_var[0];
}
	 

function serverResponse4(result) {
	result = result.split('~');
	document.getElementById('reg_div').innerHTML=result[0];
	{/literal}
	var reg_pack_id="{$smarty.request.reg_pack}";
	{literal}
	
	if (result[1]==0)
	{
		document.getElementById('sub_div').innerHTML ="<strong class='greenClass'>FREE</strong>";
		document.frmReg.btn_save.value="Register";
		document.frmReg.txt_payment.value="N";
	}
	else
	{
		document.getElementById('sub_div').innerHTML ="<select name='sub_pack' id='sub_type' class='input' style='width:180px'></select>";
		document.frmReg.btn_save.value="Continue";
		document.frmReg.txt_payment.value="Y";
	}
	if (reg_pack_id!="")
	{
		loadSub(reg_pack_id);
	}
}
function loadSub(reg_pack)
{
	//mem_type
	document.getElementById('sub_div').innerHTML="<strong class='lbClass'>Loading...</strong>";
	var req1 = newXMLHttpRequest();
	req1.onreadystatechange = getReadyStateHandler(req1, serverResponse5);
	
	{/literal}
	str="reg_pack="+reg_pack+"&selected={$smarty.request.sub_pack}";;
	//alert("{$smarty.request.sub_pack}")
	req1.open("POST", "{makeLink mod=member pg=ajax_store}act=sub_pack{/makeLink}&"+Math.random());
	{literal}
	req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	req1.send(str);
}
function serverResponse5(result) {

	result = result.split('~');
	document.getElementById('sub_div').innerHTML=result[0];
	if (result[1]==0)
	{
		document.getElementById('sub_div').innerHTML ="<strong class='greenClass'>FREE</strong>";
	}
	
}


function serverResponse1(_var) {
	document.getElementById('msg_div').innerHTML=_var;
}



function validEmail(email){
	if (emailCheck(email))
	{
		{/literal}
		document.getElementById("email_div").innerHTML="<img src='{$GLOBAL.tpl_url}/images/blue_light.gif' border='0' height='23'/><strong style='color:#990000'>CHECKING...</strong>";
		var req1 = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverResponse2);
		str="email="+email;
		
		req1.open("POST", "{makeLink mod=member pg=ajax_store}act=valid_email{/makeLink}&"+Math.random());
		{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
	}
	else
	{
		document.getElementById("email_div").innerHTML="<strong class='redClass'>Invalid Email</strong>";
	}
}
function serverResponse2(_var) {
	document.getElementById('email_div').innerHTML=_var;
}
function validUname(uname){
	if (uname.length>4)
	{
		{/literal}
		document.getElementById("uname_div").innerHTML="<img src='{$GLOBAL.tpl_url}/images/blue_light.gif' border='0' height='23'/><strong style='color:#990000'>CHECKING...</strong>";
		var req1 = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverResponse3);
		str="uname="+uname;
		
		req1.open("POST", "{makeLink mod=member pg=ajax_store}act=valid_uname{/makeLink}&"+Math.random());
		{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
	}
	else
	{
		document.getElementById("uname_div").innerHTML = "<strong class='lbClass'>(Minimum 4 characters)</strong>";
	}
}
function serverResponse3(_var) {
	document.getElementById('uname_div').innerHTML=_var;
}


</SCRIPT>
{/literal}
{if ($smarty.request.id=="")}

{literal}
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >


	//var fields=new Array('reg_pack','first_name','last_name','email','username','password','confirm_pass','address1','country','postalcode');
	//var msgs=new Array('Registration Package','First Name','Last Name','Email','Username','Password','Confirm Password','Address','Country','Zip Code');
	
	var fields=new Array('mem_type','first_name','last_name','email','username','password','confirm_pass','address1','country','postalcode');
	var msgs=new Array('Member Type','First Name','Last Name','Email','Username','Password','Confirm Password','Address','Country','Zip Code');
	
	var emails=new Array('email');
	var email_msgs=new Array('Invalid Email')

	function checkLength()
	{

		if (chk(document.frmReg))
		{	 			

			var str1=document.frmReg.username.value;
			var str2=document.frmReg.password.value;
			var str3=document.frmReg.confirm_pass.value;
			if (str1.length<4)
			{
				alert("Username length is too short");
				return false;		
			}
			else if (str2.length<6)
			{
				alert("Password length is too short");
				return false;		
			}
			else if (str2!=str3)
			{
				alert("Passwords are not matching");
				return false;		
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}		
	}
</SCRIPT>
{/literal}
{else}
{literal}
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
	var fields=new Array('mem_type','first_name','last_name','email','address1','country','postalcode');
	var msgs=new Array('Member Type','First Name','Last Name','Email','Address','Country','Zip Code');
	
	var emails=new Array('email');
	var email_msgs=new Array('Invalid Email')

	var nums=new Array('postalcode');
	var nums_msgs=new Array('Postal Code should be a number');
	function checkLength()
	{

		if (chk(document.frmReg))
		{	 			

			var str1=document.frmReg.username.value;
			var str2=document.frmReg.password.value;
			var str3=document.frmReg.confirm_pass.value;
			if (str1.length<4)
			{
				alert("Username length is too short");
				return false;		
			}
			else if (str2.length<6)
			{
				alert("Password length is too short");
				return false;		
			}
			else if (str2!=str3)
			{
				alert("Passwords are not matching");
				return false;		
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}		
	}
	
	
</SCRIPT>
{/literal}
{/if}

<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr>
	<form action="" method="post" enctype="multipart/form-data" name="frmReg" id="frmReg" onSubmit="return checkLength()"> 
	<tr> 
		<td nowrap class="naH1" colspan="2">User Registration</td>
		<td align="right">&nbsp;<!-- <a href="{makeLink mod="$MOD" pg="$PG"}act=list{/makeLink}">List Mailing Lists</a> --></td> 
	</tr>
	{if isset($MESSAGE)}
	<tr class=naGrid2>
		<td valign=top colspan=3 align="center">
			<span class="naError">{$MESSAGE}</span>
		</td>
	</tr>
	{/if}
	<tr> 
		<td colspan=3 class="naGridTitle"><span class="group_style">User Details</span></td>
	</tr>
	<tr class=naGrid2> 
		<td width=40% align="left" valign=top colspan="3"><strong>( * Mandatory Fields )</strong></td>
	</tr>
	<tr class=naGrid2> 
    	<td width=40% align="right" valign=top>Member Type</td> 
    	<td width=1% valign=top>:*</td> 
    	<td width="59%">
			<select name="mem_type" id="mem_type" class="input" style="width:180px">
				<option value="">Select a member type</option>
				{html_options values=$MEM_TYPE.id output=$MEM_TYPE.type selected=$smarty.request.mem_type}
            </select>
		</td> 
    </tr>
	<tr class=naGrid2> 
    	<td width=40% align="right" valign=top>First Name</td>
    	<td width=1% valign=top>:*</td> 
    	<td width="59%"><input name="first_name" type="text" class="input" id="first_name" value="" size="30"/></td> 
    </tr>
	<tr class=naGrid2> 
    	<td width=40% align="right" valign=top>Last Name</td> 
    	<td width=1% valign=top>:*</td> 
    	<td width="59%"><input name="last_name" type="text" class="input" id="last_name" value="" size="30"/></td> 
    </tr>
	<tr class=naGrid2> 
    	<td width=40% align="right" valign=top>Email Address</td> 
    	<td width=1% valign=top>:*</td> 
    	<td width="59%"><input name="email" type="text" class="input" id="email" value="" size="30" {if ($smarty.request.id!="")}  {else} onBlur="validEmail(this.value)" {/if}/><div id="email_div"></div></td> 
    </tr>
	{if ($smarty.request.id=="")}
	<tr class=naGrid2> 
    	<td width=40% align="right" valign=top>Username</td> 
    	<td width=1% valign=top>:*</td> 
   	  <td width="59%">
			<input name="username" type="text" class="input" id="username" value="" size="30" onBlur="validUname(this.value)" />
            <div id="uname_div"><strong class="lbClass">(Minimum 5 characters)</strong></div>
		</td> 
    </tr>
	<tr class=naGrid2> 
    	<td width=40% align="right" valign=top>Password</td> 
    	<td width=1% valign=top>:*</td> 
    	<td width="59%">
			<input name="password" type="password" class="input" id="password" size="30"/><br>
            <strong>(Minimum 6 characters)</strong>
		</td> 
    </tr>
	<tr class=naGrid2> 
    	<td width=40% align="right" valign=top>Confirm Password</td> 
    	<td width=1% valign=top>:*</td> 
    	<td width="59%"><input name="confirm_pass" type="password" class="input" id="confirm_pass" size="30"/></td> 
    </tr>
	{/if}
	<tr class=naGrid2> 
    	<td width=40% align="right" valign=top>Address</td> 
    	<td width=1% valign=top>:*</td> 
    	<td width="59%"><textarea name="address1"  rows="5" class="input" id="address1" style="width:180px"></textarea></td> 
    </tr>
	<tr class=naGrid2> 
    	<td width=40% align="right" valign=top>City</td>
    	<td width=1% valign=top>:</td> 
    	<td width="59%"><input name="city" type="text" class="input" id="city" value="" size="30" /></td> 
    </tr>
	<tr class=naGrid2> 
    	<td width=40% align="right" valign=top>Country</td> 
    	<td width=1% valign=top>:*</td> 
    	<td width="59%">
			<select name="country" class="input" id="country" style="width:180px " onChange="javascript: show_state('state',this.value,'');">
				<option value="">---Select a Country---</option>
                {html_options values=$COUNTRY_LIST.country_id output=$COUNTRY_LIST.country_name selected=$smarty.request.country}
			</select>
		</td> 
    </tr>
    <tr class=naGrid2> 
    	<td width=40% align="right" valign=top>State</td> 
    	<td width=1% valign=top>:</td>
		{if $HEALTH_CARE eq 1}
	  		<td>
				<select name="state" class="input" id="state" style="width:195px ">
					<option>---Select a State---</option>
						{foreach from=$STATE_LIST item=state_list}
							{html_options values=$state_list.value output=$state_list.value selected=$smarty.request.state}
						{/foreach}
				</select>
			</td> 
		{else}
	      	<td width="59%"><div id="div_state" class="bodytext"><input name="state" type="text" class="input" id="state" value="{$smarty.request.state}" size="30" /></div></td> 
		{/if}
    </tr>
    {if $smarty.request.state}
		<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >show_state('state',{$smarty.request.country},'{$smarty.request.state}');</SCRIPT>
	{/if}
	<tr class=naGrid2> 
    	<td width=40% align="right" valign=top>Zipcode</td> 
    	<td width=1% valign=top>:*</td> 
    	<td width="59%"><input name="postalcode" type="text" class="input" id="postalcode" value="" size="30" /></td> 
    </tr>
	<tr class=naGrid2> 
    	<td width=40% align="right" valign=top>Telephone</td> 
    	<td width=1% valign=top>:</td> 
    	<td width="59%"><input name="telephone" type="text" class="input" id="telephone" value="" size="30" /></td> 
    </tr>
	<tr class=naGrid2> 
    	<td width=40% align="right" valign=top>Mobile</td> 
    	<td width=1% valign=top>:</td> 
    	<td width="59%"><input name="mobile" type="text" class="input" id="mobile" value="" size="30" /></td> 
    </tr>
    <tr class=naGrid2> 
    	<td width=40% align="right" valign=top>&nbsp;</td> 
    	<td width=1% valign=top>&nbsp;</td> 
    	<td width="59%"><input name="txt_payment" type="hidden" id="txt_payment" value="N"></td> 
    </tr>
	<tr class="naGridTitle"> 
    	<td colspan=3 valign=center>
	  		<div align=center> 
          		<input name="btn_save" type="submit"  id="btn_save"  value="Register" class="button_class" style="width:75">&nbsp; 
          		<input type="button" name="Button" value="Cancel"  class="button_class" onClick="javascript: history.go(-1)" style="width:75">
        	</div>
		</td> 
    </tr>
</form>
</table>
<script language="javascript">
{if ($smarty.request.id=="")}
	{if $smarty.request.mem_type==2}
		document.getElementById("store_div").style.display="inline";
	{/if}
{/if}
</script>
{if ($smarty.request.id=="")}
{if $smarty.request.mem_type!=""}

{/if}
{/if}