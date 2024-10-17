<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>


{literal}
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
function show_state(opt_name,country_id,state_name) 
{
	document.getElementById('div_state').innerHTML="Loading....";
	//alert(document.getElementById('country').label)
	//country_name=document.getElementById('country')[document.getElementById('country').selectedIndex].innerHTML;
	var req1 = newXMLHttpRequest();
	req1.onreadystatechange = getReadyStateHandler(req1, serverRese);
	str="opt_name="+opt_name+"&country_id="+country_id+"&state_name="+state_name+"&classname=ajaxinput";
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
	if (reg_pack_id!="")
	{
		loadSub(reg_pack_id);
	}
}
function loadSub(reg_pack)
{
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
	
}

function validStore(store_name)
{
	var str1 = document.frmReg.store_name.value
	if(str1.length>=5)
	{
		{/literal}
		//document.getElementById("msg_div").innerHTML="<img src='{$GLOBAL.tpl_url}/images/blue_light.gif' border='0' height='23'/><strong style='color:#990000'>CHECKING...</strong>";
		var req1 = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverResponse1);
		str="store_name="+store_name;
		
		req1.open("POST", "{makeLink mod=member pg=ajax_store}act=validstore{/makeLink}&"+Math.random());
		{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
	}
	else
	{
		document.getElementById("msg_div").innerHTML="<strong class='lbClass'>eg: MyStore (Minimum 5 Characters)</strong>";
		document.frmReg.valStr.value = "false";
	}
}
function serverResponse1(_var) {
	document.getElementById('msg_div').innerHTML=_var;
}



function validEmail(email)
{
	if (emailCheck(email))
	{
		{/literal}
		//document.getElementById("email_div").innerHTML="<img src='{$GLOBAL.tpl_url}/images/blue_light.gif' border='0' height='23'/><strong style='color:#990000'>CHECKING...</strong>";
		var req1 = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverResponse2);
		str="email="+email+"&retailer=1";
		{if $GLOBAL.store_id ne ''}
			str=str+"&store_id="+{$GLOBAL.store_id};
		{/if}
		 
		req1.open("POST", "{makeLink mod=member pg=ajax_store}act=validemail{/makeLink}&"+Math.random());
		{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
	}
	else
	{
		document.getElementById("email_div").innerHTML="<font color='red'><strong>Invalid Email</strong></font>";
	}
}
function serverResponse2(_var) {
	document.getElementById('email_div').innerHTML=_var;
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
		
		req1.open("POST", "{makeLink mod=member pg=ajax_store}act=validuname{/makeLink}&"+Math.random());
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

</SCRIPT>
{/literal}
{if ($smarty.request.id=="")}
{literal}
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >


	var fields=new Array('first_name','last_name','email','username','password','confirm_pass','address1','city','country','state','postalcode','telephone','store_name','heading','reg_pack','sub_pack');
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
	{alert("one");

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
<form name="frmReg" id="frmReg" method="post" action="" onSubmit="return checkLength()" enctype="multipart/form-data">
<div style="width:auto; ">
	<div class="greyboldext" >{if $smarty.request.id}Account Details{else}Retail Web-Store Registration{/if}</div>
	 <br>
	<div class="hrline"></div>
	<div><span class="smalltext" style="color:#FF0000;"><strong><br>{messageBox}&nbsp; </strong></span></div>
	<div class="bodytext">Thank you for expressing interest in setting up a thepersonalizedgift.com Retail Web-Store. Please click on the
FAQ link below to review the Frequently Asked Questions before continuing with the web-store registration process.</div><br>
<div class="bodytext"><strong><a href="#" onclick="window.open('{makeLink mod=cms pg=display}data=faq{/makeLink}','name','height=675,width=800,left=250,top=100,toolbar=no,scrollbars=yes');"  class="footerlink" ><u>FAQ - Frequently Asked Questions: </u></a></strong></div><br>
	<div class="bodytext"><strong>NOTE: * Required Fields</strong></div><br>
	<div class="bodytext">{messageBox}</div><br>

	<div class="row">
		<span class="label">First Name: *</span>
		<span id="sub_div" class="formw">
		 <input name="first_name" type="text" class="input" id="first_name" value="{$smarty.request.first_name}" size="30"/>
         <input type="hidden" name="store_id" value="{$STORE_ID}">
		</span>
	</div><br>
	<div class="row">
		<span class="label">Last Name : *</span>
		<span id="sub_div" class="formw">
		 <input name="last_name" type="text" class="input" id="last_name" value="{$smarty.request.last_name}" size="30"/>
                     
		</span>
	</div><br>
	<div class="row">
		<span class="label">Email Address: *</span>
		<span id="sub_div" class="formw">
		 <input name="email" type="text" class="input" id="email" value="{$smarty.request.email}" size="30" {if ($smarty.request.id!="")} readonly {else}onBlur="validEmail(this.value)"{/if}/> &nbsp;<span id="email_div">&nbsp;</span>
                    
		</span>
	</div><br>
	{if ($smarty.request.id=="")}
	<div class="row">
		<span class="label">Username: *</span>
		<span id="sub_div" class="formw">
		<input name="username" type="text" class="input" id="username" value="{$smarty.request.username}" size="30" onBlur="validUname(this.value)"/>
                        <span id="uname_div"> <strong>(Minimum 5 characters)</strong></span>
  	 </span>
	</div><br>
	
	<div class="row">
		<span class="label">Password: *</span>
		<span id="sub_div" class="formw">
		<input name="password" type="password" class="input" id="password" size="30"/>
                        <strong>(Minimum 6 characters)</strong>
  	 </span>
	</div><br>
	<div class="row">
		<span class="label">Confirm Password: *</span>
		<span id="sub_div" class="formw">
		<input name="confirm_pass" type="password" class="input" id="confirm_pass" size="30"/>
  	 </span>
	</div><br>
	 {/if}
		<!--<div class="row">
		<span class="label">Secret Question: *</span>
		<span id="sub_div" class="formw">
		<select name="sec_qn" class="input" id="sec_qn" style="width:195px ">
                        <option value="">---Select a secret question---</option>                        
								{html_options values=$QN_LST.id output=$QN_LST.qn selected=$smarty.request.sec_qn}
                        </select>
  	 </span>
	</div><br>
	<div class="row">
		<span class="label">Secret Answer: *</span>
		<span id="sub_div" class="formw">
		<input name="sec_ans" type="text" class="input" id="sec_ans" value="{$smarty.request.sec_ans}" size="30" />
  	 </span>
	</div><br>
	
		<div class="row">
		<span class="label">Gender:</span>
		<span id="sub_div" class="label">
		<input name="gender" type="radio" class="checkbox" value="male" {if $smarty.request.gender=='male'} checked {/if} />Male
		<input name="gender" type="radio" class="checkbox" value="female" {if $smarty.request.gender=='female'} checked {/if} />Female
  	 </span>
	</div><br>-->
	<div class="row">
		<span class="label">Address: *</span>
		<span id="sub_div" class="formw">
		<input type="text" name="address1"  class="input" id="address1" size="30" value="{$smarty.request.address1}"/>
  	 </span>
	</div><br>
		<div class="row">
		<span class="label">&nbsp;</span>
		<span id="sub_div" class="formw">
		<input type="text" name="address2"  class="input" id="address2" size="30" value="{$smarty.request.address2}"/>
  	 </span>
	</div><br>
	<div class="row">
		<span class="label">&nbsp;</span>
		<span id="sub_div" class="formw">
		<input type="text" name="address3"  class="input" id="address3" size="30" value="{$smarty.request.address3}"/>
  	 </span>
	</div><br>
	<div class="row">
		<span class="label">City: *</span>
		<span id="sub_div" class="formw">
		<input name="city" type="text" class="input" id="city" value="{$smarty.request.city}" size="30" />
  	 </span>
	</div><br>
	<div class="row">
		<span class="label">Country: *</span>
		<span id="sub_div" class="formw">
		<select name="country" class="ajaxinput" id="country" onChange="javascript: show_state('state',this.value,'');">
                        <option>---Select a Country---</option>
							{html_options values=$COUNTRY_LIST.country_id output=$COUNTRY_LIST.country_name selected=$smarty.request.country}
                    </select>
  	 </span>
	</div><br>
		
	 
	<div class="row">
		<span class="label">State:*</span>
		<span id="sub_div" class="formw">
		<div id="div_state" class="bodytext"><input name="state" type="text" class="ajaxinput" id="state" value="{$smarty.request.state}" size="30" /></div>
  	 </span>
	 
	</div><br>
	{if $smarty.request.country}
	
		  <SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >show_state('state',{$smarty.request.country},'{$smarty.request.state}');</SCRIPT>
  	
	 
	
	{/if}
	<div class="row">
		<span class="label">Zipcode: *</span>
		<span id="sub_div" class="formw">
		<input name="postalcode" type="text" class="input" id="postalcode" value="{$smarty.request.postalcode}" size="30" />
  	 </span>
	 
	</div><br>
	
	<div class="row">
		<span class="label">Telephone:*</span>
		<span id="sub_div" class="formw">
		<input name="telephone" type="text" class="input" id="telephone" value="{$smarty.request.telephone}" size="30" />
  	 </span>
	 
	</div><br>
	<div class="bodytext"><strong>Store Information</strong></div><br>
	<div><span id="sub_div" class="formw" style="padding-left:120px">{$smarty.const.SITE_URL}/<strong><span id="div_store_name"></span></strong></span></div>
	
	<div class="row" style="margin-top:8px"><span class="label">Store URL Name: *</span>
		<input name="store_name" type="text" class="input" id="store_name" value="{$smarty.request.store_name}" size="30" onBlur="validStore(this.value);"  onPaste="return false" onDrop="return false" onDrag="return false"   onkeypress="return _keyCheck(event);"  autocomplete="off"  onKeyUp="fillText(this.value);" oncontextmenu="return false"/>
	</div>
	<div style="padding-left:120px; margin-top:8px"><span id="msg_div" class="rowfortext" style="text-align:right;">Enter your store name without spaces: eg: 'marysgifts'. Valid characters: a-z and 0-9 only, lowercase. no spaces. (Min. 5 Characters)</span>
  	 </div>
	 
	<div class="rowfortext" style="padding-left:120px">
		<span id="" class="formw1">
		<br><span class="" style="text-align:right; ">The above 'Store URL' will become your new website address. If you already have, or plan to register your own website domain name, you can easily 'Forward' your website traffic to the above Store URL. You can also 'Mask' your website address to display your own website domain name as the URL. Contact your Domain Registrar (such as godaddy.com) for assistance with 'Domain Registration', 'Forwarding' and 'Masking'.</span>
  	 </span>
	 
	</div><br>
	<div class="row">
		<span class="label">Store Name: *</span>
		<span id="sub_div" class="formw"><input name="heading" type="text" class="input" id="heading" value="{$smarty.request.heading}" size="30" />
  	 </span>
	</div>
	<div style="padding-left:120px;padding-top:8px "><span id="" class="" style="text-align:right; ">Enter your store name or business name above. eg: 'Mary's Personalized Gifts'</span></div>
	<div class="row"></div>
	
	<div class="row">
		<span class="label">Website Heading 1: </span>
		<span id="sub_div" class="formw">
		<input name="heading1" type="text" class="input" id="heading1" value="{$smarty.request.heading1}" size="30" maxlength="37" />
  	 </span>
	</div><br>
	
	<div class="row">
		<span class="label">Website Heading 2: </span>
		<span id="sub_div" class="formw">
		<input name="heading2" type="text" class="input" id="heading2" value="{$smarty.request.heading2}" size="30" maxlength="50" />
  	 </span>
	</div>
	
	<div class="rowfortext" style="padding-left:120px; margin-top:8px">
		<span id="" class="formw1">
		<span class="" style="text-align:right; ">
The above headings will display in large letters at the top right section of your website. It is suggested that you enter your business name in Heading 1 and a small headline in Heading 2.  
eg: 'Mary's Personalized Gifts' in Heading 1, and 'Designed by You!' in Heading 2..</span>
  	 </span>
	 
	</div><br>
	<!--<div class="row">
		<span class="label">Home Page Content: *</span>
		<span id="sub_div" class="formw">
		<textarea id="content" name="content" rows="5" cols="40">{$smarty.request.content}</textarea>
  	 </span>
	 
	</div><br>
	<div class="row">
		<span class="label">Store Logo: *</span>
		<span id="sub_div" class="formw">
		<input name="store_logo" id="store_logo" type="file" class="input" size="15"  />
		&nbsp;516 Pixels * 65 Pixels
  	 </span>
	 
	</div><br>-->
	 		
	<div class="row">
		<span class="label">Registration Fee: *</span>
		<span id="sub_div" class="formw">
		<div id="store_div" style="display:inline;"></div>
			<div id="reg_div" style="height:18px">
		<select name="reg_pack" id="reg_pack" value="" onChange="return checkReg()" style="width:220px " >
                     <option value="">&nbsp;</option></select></div>
  	 </span>
	 
	</div>
	<br>
	<div class="row" style="display:{if $smarty.request.sub_pack}block{else}none{/if}" id="subspack_div">
		<span class="label">Subscription Fee: *</span>
		<span id="sub_div" class="formw">
		<div id="subs_div" style="height:18px"><select name="sub_pack" id="sub_pack" class="ajaxinput" value="" style="width:300px" >
                     <option value="">&nbsp;</option></select></div> 
  	 </span>
	 
	</div>
	<br>
	<div class="row">
		<span class="label">License Agreement &nbsp;*</span>
		<span id="sub_div" class="formw">
			<input name="terms" type="checkbox" class="input" id="terms" value="{$smarty.request.terms}" size="30" />
			<span>Click this checkbox to acknowledge that you have read and agree to abide by the terms and conditions of this License Agreement below.</span>
  	 	</span>	 
	</div><br>
	<!--<div class="row">
		<span class="label">Mobile:</span>
		<span id="sub_div" class="formw">
		<input name="mobile" type="text" class="input" id="mobile" value="{$smarty.request.mobile}" size="30" />
  	 </span>
	 
	</div><br>-->
	</div>
	<br>
	<div style=" padding-left:120px ">
	<div class="row" style="width:545px; height:200px; style=padding-left:120px;">
	 <div style="padding:10px;overflow:auto; width:545px; height:180px; border:1px solid">{$TERMS_STROE_REG.content}</div>
	</div>
	</div><br>
	<div class="row">
		<span class="label">&nbsp;</span>
		<span id="sub_div" class="formw">
		<!--<input type="submit" class="button_class" value="Submit" style="height:22;width:80" /><input type="button" class="button_class" value="Cancel" style="height:22;width:80" onClick="javascript: history.go(-1)" />-->
		<input name="txt_payment" type="hidden" id="txt_payment" value="">
	  <input  name="" type="image" src="{$GLOBAL.tpl_url}/images/submit.jpg" >
<img src="{$GLOBAL.tpl_url}/images/cancel.jpg" onclick="javascript: history.go(-1)" border="0">
  	 </span>
	 
	</div><br>
	
		
</div>
</form>
<script language="javascript">
checkStore(2);
</script>