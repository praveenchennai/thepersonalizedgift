<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/popup.js"></script>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/modules/member/includes/reg_include.js"></script>

{literal}
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
function show_state(opt_name,country_id,state_name) 
{
	document.getElementById('div_state').innerHTML="Loading....";
	//alert(document.getElementById('country').label)
	//country_name=document.getElementById('country')[document.getElementById('country').selectedIndex].innerHTML;
	var req1 = newXMLHttpRequest();
	req1.onreadystatechange = getReadyStateHandler(req1, serverRese);
	str="opt_name="+opt_name+"&country_id="+country_id+"&state_name="+state_name;
	if(country_id == 840 || country_id == 36 || country_id == 124 || country_id == 356)
	str=str+"&classname5=form_field_listmenu";
	else
	str=str+"&classname5=form_field_wrap_input";
	
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
	
function checkStore(mem_type)
{

/*
	if (document.frmReg.mem_type.selectedIndex==2)
	{
		document.getElementById("store_div").style.display="inline";
	}
	else
	{
		document.getElementById("store_div").style.display="none";
	}*/
	//mem_type
	document.getElementById('reg_div').innerHTML="<strong class='lbClass'>Loading...</strong>";
	var req1 = newXMLHttpRequest();
	req1.onreadystatechange = getReadyStateHandler(req1, serverResponse4);
	
	{/literal}
	str="mem_type="+mem_type+"&selected={$smarty.request.reg_pack}";
	req1.open("POST", "{makeLink mod=member pg=ajax_store}act=reg_pack{/makeLink}&"+Math.random());
	{literal}
	req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	req1.send(str);
}
function serverResponse4(result) {
	result = result.split('~');
	document.getElementById('reg_div').innerHTML=result[0];
	{/literal}
	var reg_pack_id="{$smarty.request.reg_pack}";
	{literal}
	
	if (result[1]==0)
	{
		document.getElementById('subs_div').innerHTML ="<strong class='greenClass'>FREE</strong>";
		//document.frmReg.btn_save.value="Register";
		document.frmReg.txt_payment.value="N";
	}
	else
	{
		document.getElementById('subs_div').innerHTML ="<select name='sub_pack' id='sub_type' class='input' style='width:180px'></select>";
		//document.frmReg.btn_save.value="Continue";
		document.frmReg.txt_payment.value="Y";
	}
	reg_pack_id =11;
	if (reg_pack_id!="")
	{
		loadSub(reg_pack_id);
	}
}

var reg_pack_id;

function loadSub(reg_pack)
{
	
	reg_pack_id=reg_pack;
	
	//mem_type
	document.getElementById('subs_div').innerHTML="<strong class='lbClass'>Loading...</strong>";
	var req1 = newXMLHttpRequest();
	req1.onreadystatechange = getReadyStateHandler(req1, serverResponse5);
	
	{/literal}
	str="reg_pack="+reg_pack+"&selected={$smarty.request.sub_pack}";
	//alert("{$smarty.request.sub_pack}")
	
	req1.open("POST", "{makeLink mod=member pg=ajax_store}act=sub_pack{/makeLink}&"+Math.random());
	{literal}
	req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	req1.send(str);
}
function serverResponse5(result) {
	
	document.getElementById('subspack_div').style.display='block';
	
	result = result.split('~');
	//alert(result);
	
	document.getElementById('subs_div').innerHTML=result[0];
	if (result[1]==0)
	{
		document.getElementById('subs_div').innerHTML ="<strong class='greenClass'>FREE</strong>";
	}
	regPackPrice(reg_pack_id);
}


function validUname(uname)
{
	if (uname.length>4)
	{
		{/literal}
		//document.getElementById("uname_div").innerHTML="<img src='{$GLOBAL.tpl_url}/images/blue_light.gif' border='0' height='23'/><strong style='color:#990000'>CHECKING...</strong>";
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
		document.getElementById("uname_div").innerHTML = "<strong class='lbClass'>(Minimum 5 characters)</strong>";
	}
}
function serverResponse3(_var) {
	document.getElementById('uname_div').innerHTML=_var;
}
function fillText(val)
{
	document.getElementById("div_store_name").innerHTML=val;
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
	else if ((key > 96 && key < 123) || (key > 47 && key < 58))
		return true;
	else
		return false;
}

function storeValues(){
	var first_name 		= document.frmReg.first_name.value;
	var last_name	    = document.frmReg.last_name.value;
	var email		    = document.frmReg.email.value;
	var username	    = document.frmReg.username.value;
	var password	    = document.frmReg.password.value;
	var confirm_pass	= document.frmReg.confirm_pass.value;
		
	var address1	    = document.frmReg.address1.value;
	var address2	    = document.frmReg.address2.value;
	var city 			= document.frmReg.city.value;
	var country		    = document.frmReg.country.value;
	var state 			= document.frmReg.state.value;
	var postalcode	    = document.frmReg.postalcode.value;
	var telephone 		= document.frmReg.telephone.value;
	
	var store_name		= document.frmReg.store_name.value;
	var heading 		= document.frmReg.heading.value;
	var heading1 		= document.frmReg.heading1.value;
	var heading2 		= document.frmReg.heading2.value;	
	var reg_pack 		= document.frmReg.reg_pack.value;	
	var sub_pack 		= document.frmReg.sub_pack.value;
	
	
		
		var req10 = newXMLHttpRequest();
		req10.onreadystatechange = getReadyStateHandler(req10, serverResStoreValues);
		str="first_name="+first_name+'&last_name='+last_name+'&email='+email+'&username='+username+'&password='+password;
		str =str+'&confirm_pass='+confirm_pass+'&address1='+address1+'&address2='+address2+'&city='+city;
		str = str+'&country='+country+'&state='+state+'&postalcode='+postalcode+'&telephone='+telephone+'&store_name='+store_name;
		str = str+'&heading='+heading+'&heading1='+heading1+'&heading2='+heading2+'&reg_pack='+reg_pack+'&sub_pack='+sub_pack;
		{/literal}
		req10.open("POST", "{makeLink mod=member pg=ajax_store}act=store_reg_sess{/makeLink}&"+Math.random());
		{literal}
		req10.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req10.send(str);
}
function serverResStoreValues(_var){
	if(_var)
	{
		 if(document.getElementById('reg_amt') && document.getElementById('subpack_amt')  )
		 {
			 var store_name = document.frmReg.store_name.value;
			 
			if(document.getElementById('tot_amt').value)
			{
				 var tot_amt =document.getElementById('tot_amt').value
				 document.getElementById('al').value= tot_amt;
			 }
			 else{
			 	    var var1 = parseFloat(document.getElementById('reg_amt').value);
					var var2 = parseFloat(document.getElementById('subpack_amt').value);
					var var3 = (var1+var2);
				 	document.getElementById('al').value= var3;
			 }
			 
			 {/literal}
				var return_url= "{makeLink mod='member' pg='register'}act=payment_succes&surl="+store_name+"{/makeLink}";
			 {literal}
			 document.getElementById('return_url').value=return_url;
		 }
		 document.getElementById('item_number').value=_var;
		 document.frmReg.submit();
	}
	else
		return false;
}

function loadSubsPackIds(sub_pack)
{
		
	var req1 = newXMLHttpRequest();
	req1.onreadystatechange = getReadyStateHandler(req1, serverResloadSubsPackIds);
	
	{/literal}
	str="sub_pack="+sub_pack;
	req1.open("POST", "{makeLink mod=member pg=ajax_store}act=subs_pack_vals{/makeLink}&"+Math.random());
	{literal}
	req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	req1.send(str);
	
}
function serverResloadSubsPackIds(_var)
{
	document.getElementById('subs_pack_ids').innerHTML =_var;
}





</SCRIPT>
{/literal}
{if ($smarty.request.id=="")}
{literal}
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >


	var fields=new Array('first_name','last_name','email','username','password','password','address1','city','country','state','postalcode','telephone','store_name','heading','reg_pack','sub_pack');
	var msgs=new Array('First Name','Last Name','Email','Username','Password','Confirm Password','Address','City','Country','State','Zip Code','Telephone','store URL','store name','Registration Fee','Subscription Fee');
	
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
			else if(!document.frmReg.terms.checked)
			{
				alert("You must accept the terms and condition to register");
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
	var fields=new Array('first_name','last_name','email','address1','city','country','state','postalcode');
	var msgs=new Array('First Name','Last Name','Email','Address','City','Country','State','Zip Code');
	
	var emails=new Array('email');
	var email_msgs=new Array('Invalid Email')

	var nums=new Array('postalcode');
	var nums_msgs=new Array('Postal Code should be a number');
	function checkLength()
	

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
{if file_exists("`$smarty.const.SITE_PATH`/modules/member/tpl/reg_include.tpl")} 
{include file="`$smarty.const.SITE_PATH`/modules/member/tpl/reg_include.tpl}{/if}			

{if $smarty.request.first_name eq ''}
							{assign var="firstname_default" value='First Name'}
							{assign var="firstname_class" value="form_field_wrap_input_small_grey"}
  {else}
							{assign var="firstname_default" value=$smarty.request.first_name}
							{assign var="firstname_class" value="form_field_wrap_input_small"}
  {/if}
 
 
  {if $smarty.request.last_name eq ''}
							      {assign var="last_name_default" value='Last Name'}
							       {assign var="lastname_class" value="form_field_wrap_input_small_grey"}
  {else}
							       {assign var="last_name_default" value=$smarty.request.last_name}
							       {assign var="lastname_class" value="form_field_wrap_input_small"}
  {/if}
  
   {if $smarty.request.email eq ''}
							{assign var="email_default" value=''}
							{assign var="email_class" value="form_field_wrap_input"}
	{else}
							{assign var="email_default" value=$smarty.request.email}
							{assign var="email_class" value="form_field_wrap_input"}
   {/if}   
   
     {if $smarty.request.username eq ''}
							{assign var="username_default" value=''}
							{assign var="username_class" value="form_field_wrap_input"}
	{else}
							{assign var="username_default" value=$smarty.request.username}
							{assign var="username_class" value="form_field_wrap_input"}
	{/if}
	
	 {if $smarty.request.password eq ''}
							{assign var="password_default" value=''}
							{assign var="password_class" value="form_field_wrap_password"}
	{else}
							{assign var="password_default" value=$smarty.request.password}
							{assign var="password_class" value="form_field_wrap_password"}
	{/if}
	
	 {if $smarty.request.confirm_pass eq ''}
							{assign var="confirm_pass_default" value=''}
							{assign var="confirm_pass_class" value="form_field_wrap_input"}
	{else}
							{assign var="confirm_pass_default" value=$smarty.request.confirm_pass}
							{assign var="confirm_pass_class" value="form_field_wrap_input"}
	{/if}
	
	 {if $smarty.request.address1 eq ''}
							{assign var="address1_default" value=''}
							{assign var="address1_class" value="form_field_wrap_input"}
	{else}
							{assign var="address1_default" value=$smarty.request.address1}
							{assign var="address1_class" value="form_field_wrap_input"}
	{/if}
	
	 {if $smarty.request.address2 eq ''}
							{assign var="address2_default" value=''}
							{assign var="address2_class" value="form_field_wrap_input"}
	{else}
							{assign var="address2_default" value=$smarty.request.address1}
							{assign var="address2_class" value="form_field_wrap_input"}
	{/if}
	
	
	
	
	 {if $smarty.request.city eq ''}
							{assign var="city_default" value=''}
							{assign var="city_class" value="form_field_wrap_input"}
	{else}
							{assign var="city_default" value=$smarty.request.city}
							{assign var="city_class" value="form_field_wrap_input"}
	{/if}
	
	 {if $smarty.request.state eq ''}
							{assign var="state_default" value=''}
							{assign var="state_class" value="input_field"}
	{else}
							{assign var="state_default" value=$smarty.request.state}
							{assign var="state_class" value="input_field"}
	{/if}
	
	 {if $smarty.request.postalcode eq ''}
							{assign var="zip_default" value=''}
							{assign var="zip_class" value="form_field_wrap_input"}
	{else}
							{assign var="zip_default" value=$smarty.request.postalcode}
							{assign var="zip_class" value="form_field_wrap_input"}
	{/if}
	
	{if $smarty.request.telephone eq ''}
							{assign var="telephone_default" value=''}
							{assign var="telephone_class" value="form_field_wrap_input"}
	{else}
							{assign var="telephone_default" value=$smarty.request.telephone}
							{assign var="telephone_class" value="form_field_wrap_input"}
	{/if}
	
	{if $smarty.request.store_name eq ''}
							{assign var="store_name_default" value=''}
							{assign var="store_name_class" value="form_field_wrap_input"}
	{else}
							{assign var="store_name_default" value=$smarty.request.store_name}
							{assign var="store_name_class" value="form_field_wrap_input"}
	{/if}

	{if $smarty.request.heading eq ''}
								{assign var="heading_default" value=''}
								{assign var="heading_class" value="form_field_wrap_input"}
	{else}
								{assign var="heading_default" value=$smarty.request.heading}
								{assign var="heading_class" value="form_field_wrap_input"}
	{/if}
	
	{if $smarty.request.heading1 eq ''}
								{assign var="heading1_default" value=''}
								{assign var="heading1_class" value="form_field_wrap_input"}
	{else}
								{assign var="heading1_default" value=$smarty.request.heading1}
								{assign var="heading1_class" value="form_field_wrap_input"}
	{/if}
	{if $smarty.request.heading2 eq ''}
								{assign var="heading2_default" value=''}
								{assign var="heading2_class" value="form_field_wrap_input"}
	{else}
								{assign var="heading2_default" value=$smarty.request.heading2}
								{assign var="heading2_class" value="form_field_wrap_input"}
	{/if}
	
	{if $smarty.request.promo_code eq ''}
								{assign var="promo_code_default" value=''}
								{assign var="promo_code_class" value="form_field_wrap_input"}
	{else}
								{assign var="promo_code_default" value=$smarty.request.promo_code}
								{assign var="promo_code_class" value="form_field_wrap_input"}
	{/if}



{if $PAYPAL_TEST_MODE eq 'Y'}
<form  name="frmReg" action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='POST'>
{else}
<form name="frmReg" action='https://www.paypal.com/cgi-bin/webscr' method='POST'>
{/if}


<input type="hidden" name="validation_sucess" id="validation_sucess" >
<input name="tot_amt" type="hidden" id="tot_amt" value="" >

<input type="hidden" name="username_valid" id="username_valid" >
<input type="hidden" name="email_valid" id="email_valid" >
<input type="hidden" name="storename_valid" id="storename_valid" >
<input name="txt_payment" type="hidden" id="txt_payment" value="">
<input type="hidden" name="promo_valid" id="promo_valid" value="Y" />


<div  >
	<div class="greyboldext" >{if $smarty.request.id}Account Details{else}Retail Web-Store Registration{/if}</div>
	 <br>
	<div class="hrline"></div>
	<br />
	<div class="formtext_12">
  <table width="685" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2">Thank you for expressing interest in setting up a thepersonalizedgift.com Retail Web Store. Please click on the FAQ link below to review the Frequently Asked Questions before continuing with the web-store registration process. </td>
    </tr>
  <tr>
    <td width="228">&nbsp;</td>
    <td width="298">&nbsp;</td>
  </tr>
  <tr>
    <td><a class="bodytext" href="javascript:void(0);" onClick="window.open('{makeLink mod=cms pg=display}data=faq&hval=y&print=y{/makeLink}','name','height=675,width=800,left=250,top=100,toolbar=no,scrollbars=yes');"><u>FAQ - Frequently Asked Questions:</u></a></td>
    <td align="right"><strong>Note:*Required Field </strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</div>
</div>
<div style="width:auto; "  >
	<div class="bodytext">{messageBox}</div><br>
     <div class="formbold_text form_height">Your Contact Information<br /><br /></div>
	 
	      <div class="form_row_field">
					<div class="form_wrap" id="first_name_left" >
						<div class="form_name_wrap">Name:*</div>
					  <div class="form_field_wrap_01">
					    <input name="first_name" id="first_name" type="text"  class="{$firstname_class}"  value="{$firstname_default}"  onFocus="javascript:changeDefault(this.value,'first_name','First Name','form_field_wrap_input_small','form_field_wrap_input_small');showTips('first_name_holder','first_name_hint','first_name_error','first_name_left');" onClick="javascript:changeDefault(this.value,'first_name','First Name','form_field_wrap_input_small','form_field_wrap_input');" onBlur="hideTips('first_name_holder');checkDefault(this.value,'first_name','First Name','form_field_wrap_input_small_grey');"  />
						 <input type="hidden" name="store_id" value="{$STORE_ID}">
					  </div>
					  
					   <div class="form_field_wrap_01">
					   <input name="last_name" id="last_name" type="text"  class="{$lastname_class}"  value="{$last_name_default}" onfocus="javascript:changeDefault(this.value,'last_name','Last Name','form_field_wrap_input_small','form_field_wrap_input');showTips('first_name_holder','first_name_hint','first_name_error','first_name_left');" onClick="javascript:changeDefault(this.value,'last_name','Last Name','form_field_wrap_input_small','form_field_wrap_input');" onBlur="hideTips('first_name_holder');checkDefault(this.value,'last_name','Last Name','form_field_wrap_input_small_grey');" />
					  </div>
					</div>
					
					<div class="form_message_holder" id="first_name_holder">
						<span id="first_name_hint"  style="display:none;"  class="form_hint">Enter the first name and last name here<span class="form_hint-pointer">&nbsp;</span></span>					</div>
					<div class="form_message_holder">
						<div class="form_error_message" id="first_name_error" style="display:none"></div>
					</div>	
                </div>
			    <div class="clear"></div>
				
	<div class="form_row_field">
					<div class="form_wrap"  id="email_left">
						<div class="form_name_wrap"  >Email Address:*</div>
					    <div class="form_field_wrap">
					   <input name="email" id="email" type="text"  class="{$email_class}" value="{$email_default}"  onFocus="javascript:changeDefault(this.value,'email','','form_field_wrap_input','input_field_null');showTips('email_holder','email_hint','email_error','email_left');" onClick="javascript:changeDefault(this.value,'email','','form_field_wrap_input','input_field_null');" onBlur="hideTips('email_holder');checkDefault(this.value,'email','','form_field_wrap_input');validEmail(this.value);"/>
					  </div>
					</div>
					
					<div class="form_message_holder" id="email_holder">
					<div id="myemail2" style="display:none; z-index:15"  class="errMsg"></div>
						<span  style="display:none;"  id="email_hint"  class="form_hint">Enter a vaild E-mail address here.<span class="form_hint-pointer">&nbsp;</span></span>					</div>
					<div class="form_message_holder">
						<div class="form_error_message" id="email_error" style="display:none"></div>
					</div>	
                </div>
			    <div class="clear"></div>
				
	{if ($smarty.request.id=="")}
		<div class="form_row_field">
					<div class="form_wrap" id="username_name_left" >
						<div class="form_name_wrap"  >Username:*</div>
					    <div class="form_field_wrap">
					   <input name="username" id="username"  class="{$username_class}"  value="{$username_default}" type="text"  onFocus="javascript:changeDefault(this.value,'username','','form_field_wrap_input','form_field_wrap_input');showTips('username_name_holder','username_name_hint','username_name_error','username_name_left');" onClick="javascript:changeDefault(this.value,'username','','form_field_wrap_input','form_field_wrap_input');" onBlur="hideTips('username_name_holder');checkDefault(this.value,'username','','form_field_wrap_input');validUsername(this.value,'A')" />
					  </div>
					</div>
					
					<div class="form_message_holder" id="username_name_holder">
						<span style="display:none;"  id="username_name_hint"   class="form_hint">Enter the username here. (Minimum 5 characters)<span class="form_hint-pointer">&nbsp;</span></span>					</div>
				<div class="form_message_holder">
						<div class="form_error_message" id="username_name_error" style="display:none"></div>
					</div>			
						
                </div>
		   		 <div class="clear"></div>
	
	 <div class="form_row_field">
					<div class="form_wrap" id="password_left" >
						<div class="form_name_wrap">Password:*</div>
					    <div class="form_field_wrap">
					  <div class="form_field_wrap">
					    <input  name="password" type="password"  id="password"  class="{$password_class}" style="width:180px; float:left;" onBlur="javascript:validPassword(this.value,'S');hideTips('password_holder');" onFocus="showTips('password_holder','password_hint','password_error','password_left');" onKeyUp="passwordStrength(this.value)"/>
						<div class="pasword_str" >Password Strength
							<div class="block" id="pass1"><!-- --></div>
							<div class="block"  id="pass2"><!-- --></div>
							<div class="block"  id="pass3"><!-- --></div>
							<div class="block"  id="pass4"><!-- --></div>
					  </div>
					  </div>
					</div>
					
					<div class="form_message_holder" id="password_holder">
						<span style="display:none;"  id="password_hint" class="form_hint">Enter the password here. (Minimum 6 characters)<span class="form_hint-pointer">&nbsp;</span></span></div>
				<div class="form_message_holder">
						<div class="form_error_message" id="password_error" style="display:none"></div>
					</div>	
								
                </div>
				</div>
				
				 <div class="clear"></div>
				 
	<div class="form_row_field">
					<div class="form_wrap" id="confirm_pass_left">
						<div class="form_name_wrap">Confirm Password:*</div>
					    <div class="form_field_wrap">
					    <input name="confirm_pass" id="confirm_pass" type="password" class="{$confirm_pass_class}"  value="{$confirm_pass_default}" onBlur="javascript:validConfirmPassword(this.value,'S');hideTips('confirm_pass_holder');" onFocus="showTips('confirm_pass_holder','confirm_pass_hint','confirm_pass_error','confirm_pass_left');" />
					  </div>
					</div>
					
					<div class="form_message_holder" id="confirm_pass_holder">
						<span style="display:none;" id="confirm_pass_hint" class="form_hint">Enter the confirm password here.<span class="form_hint-pointer">&nbsp;</span></span>					</div>
						<div class="form_message_holder">
						<div class="form_error_message" id="confirm_pass_error" style="display:none"></div>
					</div>
					
  </div>
				 <div class="clear"></div>
	 {/if}
		
			<div class="form_row_field">
					<div class="form_wrap" id="address1_left">
						<div class="form_name_wrap">Address:*</div>
					    <div class="form_field_wrap">
					    <input name="address1" id="address1" type="text" class="{$address1_class}"  value="{$address1_default}"  onBlur="javascript:hideTips('address1_holder');checkDefault(this.value,'address1','','form_field_wrap_input','form_field_wrap_input')"  onClick="javascript:changeDefault(this.value,'address1','','form_field_wrap_input','form_field_wrap_input');" onFocus="javascript:changeDefault(this.value,'address1','','form_field_wrap_input','form_field_wrap_input');showTips('address1_holder','address1_hint','address1_error','address1_left');" />
					  </div>
					</div>
					
					<div class="form_message_holder" id="address1_holder">
						<span style="display:none;" id="address1_hint" class="form_hint">Enter the address here.<span class="form_hint-pointer">&nbsp;</span></span>					</div>
						<div class="form_message_holder">
						<div class="form_error_message" id="address1_error" style="display:none"></div>
					</div>
                </div>
				 <div class="clear"></div>
				 
		 <div class="form_row_field">
					<div class="form_wrap" id="address2_left">
						<div class="form_name_wrap"></div>
					    <div class="form_field_wrap">
					    <input name="address2" id="address2" type="text" class="{$address2_class}"  value="{$address2_default}" onBlur="javascript:hideTips('address2_holder');checkDefault(this.value,'address2','','form_field_wrap_input','form_field_wrap_input')"  onClick="javascript:changeDefault(this.value,'address2','','form_field_wrap_input','form_field_wrap_input');" onFocus="javascript:changeDefault(this.value,'address2','','form_field_wrap_input','form_field_wrap_input');showTips('address2_holder','address2_hint','address2_error','address2_left');" />
					  </div>
					</div>
					
					<div class="form_message_holder" id="address2_holder">
						<span style="display:none;" id="address2_hint" class="form_hint">Enter the address details here.<span class="form_hint-pointer">&nbsp;</span></span>					</div>
						<div class="form_message_holder">
						<div class="form_error_message" id="address2_error" style="display:none"></div>
					</div>
                </div>
				 <div class="clear"></div>
				 
		 <div class="form_row_field">
					<div class="form_wrap" id="city_left">
						<div class="form_name_wrap">City:*</div>
					    <div class="form_field_wrap">
					    <input name="city" id="city" type="text" class="{$city_class}"  value="{$city_default}"  onBlur="javascript:hideTips('city_holder');checkDefault(this.value,'city','','form_field_wrap_input','form_field_wrap_input')"  onClick="javascript:changeDefault(this.value,'city','','form_field_wrap_input','form_field_wrap_input');" onFocus="javascript:changeDefault(this.value,'city','','form_field_wrap_input','form_field_wrap_input');showTips('city_holder','city_hint','city_error','city_left');"  />
					  </div>
					</div>
					
					<div class="form_message_holder" id="city_holder">
						<span style="display:none;"  id="city_hint"  class="form_hint">Enter the City.<span class="form_hint-pointer">&nbsp;</span></span>					</div>
						
					<div class="form_message_holder">
						<div class="form_error_message" id="city_error" style="display:none"></div>
					</div>
						
                </div>
				 <div class="clear"></div>
				 
	<div class="form_row_field">
					<div class="form_wrap" id="country_left">
						<div class="form_name_wrap">Country:*</div>
				      <div class="form_field_wrap">
				      <select name="country" class="form_field_listmenu" id="country" onFocus="javascript:setDropdownDefault('country_left','country_error','form_wrap');" onChange="javascript: show_state('state',this.value,'');" >
				              <option value="">--Select a Country--</option>
							 {if $smarty.request.country}
							{html_options values=$COUNTRY_LIST.country_id output=$COUNTRY_LIST.country_name selected=$smarty.request.country}
							{else}
							{html_options values=$COUNTRY_LIST.country_id output=$COUNTRY_LIST.country_name selected=840}
							{/if}
			            </select>
				      
				      </div>
					</div>
					
					<div class="form_message_holder" id="country_holder">
						<span style="display:none;" id="country_hint" class="form_hint"><span class="form_hint-pointer">&nbsp;</span></span>					</div>
						<div class="form_message_holder">
						<div class="form_error_message" id="country_error" style="display:none"></div>
					</div>
					
                </div>
				<div class="clear"></div>
		
	 
					 <div class="form_row_field">
					<div class="form_wrap" id="state_left">
						<div class="form_name_wrap">State:*</div>
					    <div class="form_field_wrap" id="div_state">
					    <input name="state" id="state" type="text" class="form_field_wrap_input"  onBlur="javascript:hideTips('state_holder');checkDefault(this.value,'state','','form_field_wrap_input','form_field_wrap_input')"  onClick="javascript:changeDefault(this.value,'state','','form_field_wrap_input','form_field_wrap_input');" onFocus="javascript:changeDefault(this.value,'state','','form_field_wrap_input','form_field_wrap_input');showTips('state_holder','state_hint','state_error','state_left');"  />
					  </div>
					</div>
					
					<div class="form_message_holder" id="state_holder">
						<span style="display:none;" id="state_hint" class="form_hint">Enter the State.<span class="form_hint-pointer">&nbsp;</span></span>					</div>
						<div class="form_message_holder">
						<div class="form_error_message" id="state_error" style="display:none"></div>
					</div>
                </div>
				  <div class="clear"></div>
				  
	{if $smarty.request.country}
	
		  <SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >show_state('state',{$smarty.request.country},'{$smarty.request.state}');</SCRIPT>
  	
	 
	
	{/if}
				 <div class="form_row_field">
					<div class="form_wrap" id="postalcode_left">
						<div class="form_name_wrap">Zip Code:* </div>
					    <div class="form_field_wrap">
					    <input name="postalcode" id="postalcode" type="text" class="{$zip_class}"  value="{$zip_default}"  onBlur="javascript:hideTips('postalcode_holder');checkDefault(this.value,'postalcode','','form_field_wrap_input','form_field_wrap_input')"  onClick="javascript:changeDefault(this.value,'postalcode','','form_field_wrap_input','form_field_wrap_input');" onFocus="javascript:changeDefault(this.value,'postalcode','','form_field_wrap_input','form_field_wrap_input');showTips('postalcode_holder','postalcode_hint','postalcode_error','postalcode_left');" />
					  </div>
					</div>
					
					<div class="form_message_holder" id="postalcode_holder">
						<span style="display:none;" id="postalcode_hint" class="form_hint">Enter the Zip Code.<span class="form_hint-pointer">&nbsp;</span></span>					</div>
	<div class="form_message_holder">
						<div class="form_error_message" id="postalcode_error" style="display:none"></div>
					</div>
										
                </div>
				  <div class="clear"></div>
				  
	
				 <div class="form_row_field">
					<div class="form_wrap" id="telephone_left">
						<div class="form_name_wrap">Telephone:*</div>
					    <div class="form_field_wrap">
					    <input name="telephone" id="telephone" type="text" class="{$telephone_class}"  value="{$telephone_default}"   onBlur="javascript:hideTips('telephone_holder');checkDefault(this.value,'telephone','','form_field_wrap_input','form_field_wrap_input')"  onClick="javascript:changeDefault(this.value,'telephone','','form_field_wrap_input','form_field_wrap_input');" onFocus="javascript:changeDefault(this.value,'telephone','','form_field_wrap_input','form_field_wrap_input');showTips('telephone_holder','telephone_hint','telephone_error','telephone_left');" />
					  </div>
					</div>
					
					<div class="form_message_holder" id="telephone_holder">
						<span style="display:none;" id="telephone_hint" class="form_hint">Enter the Telephone number details.<span class="form_hint-pointer">&nbsp;</span></span>					</div>
						<div class="form_message_holder">
						<div class="form_error_message" id="telephone_error" style="display:none"></div>
					</div>
					
                </div>
				 <div class="clear"></div>
				 
	<div class="formbold_text form_height">Your Retail Store Information<br /><br /></div>
	 <div class="form_row_field">
					<div class="form_wrap" id="heading_left">
						<div class="form_name_wrap">Store Name:*</div>
					    <div class="form_field_wrap">
					    <input name="heading" id="heading" type="text" class="{$heading_class}"  value="{$heading_default}" onBlur="javascript:hideTips('heading_holder');checkDefault(this.value,'heading','','form_field_wrap_input','form_field_wrap_input')"  onClick="javascript:changeDefault(this.value,'heading','','form_field_wrap_input','form_field_wrap_input');" onFocus="javascript:changeDefault(this.value,'heading','','form_field_wrap_input','form_field_wrap_input');showTips('heading_holder','heading_hint','heading_error','heading_left');"  />
					  </div>
					</div>
					
					<div class="form_message_holder" id="heading_holder">
						<span style="display:none;" id="heading_hint" class="form_hint">
Enter your store name or business name above. Example: 'Mary's Personalized Gifts'. This name will be used in for email correspondence with your customers.<span class="form_hint-pointer">&nbsp;</span></span>					</div>
						<div class="form_message_holder">
						<div class="form_error_message" id="heading_error" style="display:none"></div>
					</div>			
					
                </div>
				 <div class="clear"></div>
					<div class="form_row_field">
					<div class="form_wrap" id="store_name_left">
						<div class="form_name_wrap">Store URL Name:*</div>
					    <div class="form_field_wrap">
					    <input name="store_name" id="store_name" type="text" class="{$store_name_class}"  value="{$store_name_default}" onBlur="javascript:hideTips('store_name_holder');checkDefault(this.value,'store_name','','form_field_wrap_input','form_field_wrap_input');validStore(this.value);"  onClick="javascript:changeDefault(this.value,'store_name','','form_field_wrap_input','form_field_wrap_input');" onFocus="javascript:changeDefault(this.value,'store_name','','form_field_wrap_input','form_field_wrap_input');showTips('store_name_holder','store_name_hint','store_name_error','store_name_left');" onPaste="return false" onDrop="return false" onDrag="return false"   onkeypress="return _keyCheck(event);"  autocomplete="off"  onKeyUp="fillText(this.value);" oncontextmenu="return false" />
					  </div>
					</div>
					
					<div class="form_message_holder" id="store_name_holder">
						<span style="display:none;" class="form_hint" id="store_name_hint">Enter your 'Web-Store Name' without spaces. This name will be used to create your new web-store address.<br /> Examples: If your business name is 'Mary's Personalized Gift', you can enter maryspersonalizedgifts, or marysgifts here. Using the last example, marysgifts, your new web-store address would be: <p> www.thepersonalizedgift.com/<br />marysgifts</p><p>(Note: After your web-store has been set-up, you can redirect your own URL (website name) to your web-store if you have one.)</p><span class="form_hint-pointer">&nbsp;</span></span>					</div>
			<div class="form_message_holder">
						<div class="form_error_message" id="store_name_error" style="display:none"></div>
					</div>			
						
                </div>
				 <div class="clear"></div>
				 	<div class="form_row_field height20">
					<div class="form_wrap">
						<div class="form_name_wrap"></div>
					    <div class="form_field_wrap"><span class="formsmall_link">{$smarty.const.SITE_URL}/<span id="div_store_name"></span></span></div>
					</div>
					
					<div class="form_message_holder">
						<span style="display:none;" class="form_hint"><span class="form_hint-pointer">&nbsp;</span></span>					</div>
                </div>
	 <div class="clear"></div>
	 <div class="form_row_field">
					<div class="form_wrap" id="heading1_left">
						<div class="form_name_wrap">Heading 1*: </div>
				      <div class="form_field_wrap">
					    <input name="heading1" id="heading1" type="text" class="{$heading1_class}"  value="{$heading1_default}"  onBlur="javascript:hideTips('heading1_holder');checkDefault(this.value,'heading1','','form_field_wrap_input','form_field_wrap_input')"  onClick="javascript:changeDefault(this.value,'heading1','','form_field_wrap_input','form_field_wrap_input');" onFocus="javascript:changeDefault(this.value,'heading1','','form_field_wrap_input','form_field_wrap_input');showTips('heading1_holder','heading1_hint','heading1_error','heading1_left');" />
					  </div>
					</div>
					
					<div class="form_message_holder" id="heading1_holder">
						<span style="display:none;" id="heading1_hint" class="form_hint">Headings will display in large letters at the top right section of your website. It is suggested that you enter your business name in Heading 1 and a small headline in Heading 2.
Examples: Heading 1: 'Mary's Personalized Gifts', and Heading 2: 'Designed by You!'<span class="form_hint-pointer">&nbsp;</span></span>					</div>
						<div class="form_message_holder">
						<div class="form_error_message" id="heading1_error" style="display:none"></div>
					</div>
					
                </div>
				 <div class="clear"></div>
				 
				 <div class="form_row_field">
					<div class="form_wrap" id="heading2_left">
					<div class="form_name_wrap">Heading 2:*</div>
					    <div class="form_field_wrap">
					    <input name="heading2" id="heading2" type="text" class="{$heading2_class}"  value="{$heading2_default}"  onBlur="javascript:hideTips('heading2_holder');checkDefault(this.value,'heading2','','form_field_wrap_input','form_field_wrap_input')"  onClick="javascript:changeDefault(this.value,'heading2','','form_field_wrap_input','form_field_wrap_input');" onFocus="javascript:changeDefault(this.value,'heading2','','form_field_wrap_input','form_field_wrap_input');showTips('heading2_holder','heading2_hint','heading2_error','heading2_left');" />
					  </div>
					</div>
					<div class="form_message_holder" id="heading2_holder">
						<span style="display:none;"  id="heading2_hint" class="form_hint">Headings will display in large letters at the top right section of your website. It is suggested that you enter your business name in Heading 1 and a small headline in Heading 2.
Examples: Heading 1: 'Mary's Personalized Gifts', and Heading 2: 'Designed by You!'<span class="form_hint-pointer">&nbsp;</span></span>					</div>
<div class="form_message_holder">
						<div class="form_error_message" id="heading2_error" style="display:none"></div>
					</div>
                </div>
	
	<br>
	<div class="formbold_text form_height">Registration Information<br /><br /></div>
	 		
	 <div class="form_row_field">
					<div class="form_wrap" id="reg_pack_left">
						<div class="form_name_wrap">Registration Fee:*</div>
				      <div class="form_field_wrap"  id="reg_div">
				        <select name="reg_pack" id="reg_pack" onChange="return checkReg();regPackPrice();" class="form_field_listmenu" onFocus="javascript:setDropdownDefault('reg_pack_left','reg_pack_error','form_wrap');"  >
						<option value="">&nbsp;</option></select>
                        </select>
				      </div>
					</div>
					
					<div class="form_message_holder" id="reg_pack_holder">
						<span style="display:none;" id="reg_pack_hint" class="form_hint"><span class="form_hint-pointer">&nbsp;</span></span>					</div>
						<div class="form_message_holder">
						<div class="form_error_message" id="reg_pack_error" style="display:none"></div>
					</div>
					
                </div>
				 <div class="clear"></div>

	<div class="form_row_field" style="display:{if $smarty.request.sub_pack}block{else}none{/if}" id="subspack_div">
					<div class="form_wrap" id="sub_pack_left">
						<div class="form_name_wrap">Subscription Fee:* </div>
				      <div class="form_field_wrap"  id="subs_div">
				        <select name="sub_pack" id="sub_pack" class="form_field_listmenu" >
                        </select>
				      </div>
					</div>
					
					<div class="form_message_holder" id="sub_pack_holder">
						<span style="display:none;" id="sub_pack_hint" class="form_hint"><span class="form_hint-pointer">&nbsp;</span></span>					</div>
						<div class="form_message_holder">
						<div class="form_error_message" id="sub_pack_error" style="display:none"></div>
					</div>
                </div>
				 <div class="clear"></div>
				 
				 
				 
				 	<div class="form_row_field">
					<div class="form_wrap" id="promo_code_left">
						<div class="form_name_wrap">Coupon Code: </div>
				      <div class="form_field_wrap">
				        <input name="promo_code" id="promo_code" type="text"  class="{$promo_code_class}"  value="{$promo_code_default}"  onBlur="javascript:hideTips('promo_code_holder');checkDefault(this.value,'promo_code','','form_field_wrap_input','form_field_wrap_input');"  onClick="javascript:changeDefault(this.value,'promo_code','','form_field_wrap_input','form_field_wrap_input');" onFocus="javascript:changeDefault(this.value,'promo_code','','form_field_wrap_input','form_field_wrap_input');showTips('promo_code_holder','promo_code_hint','promo_code_error','promo_code_left');" onkeyup="showLinkValid(this.value)" />
						<br /><span id="loadinginfo" style="color:#C4120E"></span>
						
							
				      </div>
					</div>
					
					
						<div class="form_message_holder" id="promo_code_holder">
						<span style="display:none;" class="form_hint" id="promo_code_hint">Enter the Coupon Code details.</p><span class="form_hint-pointer">&nbsp;</span></span>					</div>
			<div class="form_message_holder">
						<div class="form_error_message" id="promo_code_error" style="display:none"></div>
					</div>			
                </div>
				
					
				 <div class="clear"></div>
				 	<div class="form_row_field" style="display:none;" id="valid_coupon_link">
					<div class="form_wrap">
						<div class="form_name_wrap"></div>
				     <div class="" ><a href="javascript:void(0);" onClick="validatePromoCode();" class="form_link_text">Validate Coupon</a></div>
					</div>
					
					<div class="form_message_holder">
						<span style="display:none;" class="form_hint"><span class="form_hint-pointer">&nbsp;</span></span>					</div>
                </div>
				 <div class="clear"></div>
				 	<div >
					<div class="form_wrap">
						<div class="form_name_wrap"></div>
				     <div class="" ><div style="display:none" id="hid_div">
									<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td width="37%" align="right" valign="top"><strong>Set-Up  Discount</strong>&nbsp;&nbsp;<span style="width:10px;">&nbsp;</span></td>
    <td width="63%" align="left" valign="top"><div id="totaldeduction_setup" style="font-weight:bold;">$0.00</div></td>
  </tr>
  <tr>
    <td align="right" valign="top"><strong>Subscription Discount</strong>&nbsp;&nbsp;<span style="width:10px;">&nbsp;</span></td>
    <td align="left" valign="top"><div id="totaldeduction_subscription" style="font-weight:bold; ">$0.00</div></td>
  </tr>
  <tr>
    <td align="right" valign="top"><strong>Total Discount</strong>&nbsp;&nbsp;<span style="width:10px;">&nbsp;</span></td>
    <td align="left" valign="top"><div id="totaldeduction" style="font-weight:bold; ">$0.00</div></td>
  </tr>
  <tr>
    <td align="right" valign="top"><strong>Total Fee</strong>&nbsp;&nbsp;<span style="width:10px;">&nbsp;</span></td>
    <td align="left" valign="top"><div id="totalblock" style="font-weight:bold; ">${$TOT_AMT|string_format:"%.2f"}</div></td>
  </tr>
  
</table>

									
							</div></div>
					</div>
					
					<div class="form_message_holder">
						<span style="display:none;" class="form_hint"><span class="form_hint-pointer">&nbsp;</span></span>					</div>
                </div>
				
				
				 <div class="clear"></div>
				
				<div class="form_row_field" style="display:none" id="chk_accept_left_error">
					<div class="form_wrap" id="promo_code_left">
						<div class="form_name_wrap">&nbsp;</div>
				      <div class="form_field_wrap">
				    <span style="font:normal 11px Verdana, Arial, Helvetica, sans-serif;
; color:#C4120E;
"> You must accept the terms and condition to register</span>
					</div>
                </div>
				</div>
				 <div class="clear"></div>
				 
				<div class="form_row_field height70">
					<div class="form_wrap_big"  id="chk_accept_left">
						<div class="form_name_wrap">Terms of Service: *  </div>
			          <div class="form_field_wrap_big">
			           
			           <table width="490" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="25" align="left" valign="top"><label>
      <input type="checkbox" name="terms" id="terms" value="checkbox" onClick="javascript:setDropdownDefault('chk_accept_left','reg_pack_error','form_wrap_big');document.getElementById('chk_accept_left_error').style.display = 'none';" />
    </label></td>
    <td width="465" align="left" valign="top">
	I have read and agree to The Personalized Gift <a href="javascript:void(0);" onClick="window.open('{makeLink mod=cms pg=display}data=terms&print=y{/makeLink}','name','height=675,width=800,left=250,top=100,toolbar=no,scrollbars=yes');" class="form_link_text_blue">Terms of Service</a> and to receive important communications electronically.
	</td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <!--
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td height="30" align="left" valign="middle"><a href="javascript:void(0);" onClick="window.open('{makeLink mod=cms pg=display}data=faq&hval=y&print=y{/makeLink}','name','height=675,width=800,left=250,top=100,toolbar=no,scrollbars=yes');" class="form_link_text">Print FAQ</a> | <a href="javascript:void(0);" onClick="window.open('{makeLink mod=cms pg=display}data=terms	&print=y{/makeLink}','name','height=675,width=800,left=250,top=100,toolbar=no,scrollbars=yes');" class="form_link_text">PRINT TOS</a></td>
  </tr>
  -->
</table>

			          
			          </div>
					</div>
					
					
                </div>
				
	
	</div>
	
	
	
				 	<div class="form_row_field">
					<div class="form_wrap">
						<div class="form_name_wrap"></div>
				     <div class="" >{include file="`$smarty.const.SITE_PATH`/modules/member/tpl/payment_paypal.tpl}</div>
					</div>
					
					
                </div>
				<div class="form_row_field">
					<div class="form_wrap">
						<div class="form_name_wrap"></div>
				     <div class="" ></div>
					</div>
					
					
                </div>
				

</form>
<script language="javascript">
checkStore(2);

</script>

