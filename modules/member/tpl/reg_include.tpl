
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/jquery-latest.pack.js"></script>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/jpopup.js"></script>
 <link href="{$GLOBAL.tpl_url}/css/popgeneral.css" rel="stylesheet" type="text/css">
 <SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
{literal}

function validMyname(fname,type,lname)
{
		
		{/literal}
		
		document.getElementById("first_name_error").innerHTML="<img src='{$GLOBAL.tpl_url}/images/loading.gif' border='0' height='23'/>";
		var req1 = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverResponse11);
		str="name="+fname+"&lname="+lname;
		req1.open("POST", "{makeLink2 mod=member pg=ajax_registration}act=valid_name{/makeLink2}&"+Math.random());
		{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
}
function serverResponse11(_var) { 

	if(_var.trim() == 'IMG')
		{  //alert("hhhhhhhh");
		document.getElementById('first_name_holder').style.display='block';
		document.getElementById('first_name_hint').style.display = 'none';
		document.getElementById('first_name_error').style.display = 'block';
		//document.getElementById('first_name_hint').style.display='block';
		window.setTimeout(function() { 
		document.getElementById('first_name_error').innerHTML="<img src='{/literal}{$GLOBAL.tpl_url}{literal}/images/checkbullet.gif' border='0'/>";
		}, 500);
		 window.setTimeout(function(){document.getElementById('first_name_error').style.display='none'; }, 1000);
		 //document.getElementById("myname_main").className = "ctrlHolder";
		 document.getElementById("first_name_left").className = "form_wrap";
		 document.getElementById("validation_sucess").value = "Y";
		 //document.frmReg.validation_sucess.value='Y';	
		 	
		 
	}
	
	else if(_var.trim() == "NONE")
	{	
		 document.getElementById('first_name_holder').style.display='none';
		document.getElementById('first_name_hint').style.display = 'none';
		document.getElementById('first_name_error').style.display = 'none';
		// document.getElementById("myname_main").className = "ctrlHolder";
		 document.getElementById("first_name_left").className = "form_wrap";
		 document.getElementById("validation_sucess").value = "Y";
		 //document.frmReg.validation_sucess.value='Y';	
	}
	else
	{	
		//document.getElementById('first_name_holder').style.display='none';
		document.getElementById('first_name_error').innerHTML=_var;
		document.getElementById('first_name_holder').style.display='block';
		document.getElementById('first_name_hint').style.display = 'none';
		document.getElementById('first_name_error').style.display = 'block';
		//document.getElementById("myname_main").className = "ctrlHolder_error";
		document.getElementById("first_name_left").className = "form_wrap_error";
		document.getElementById("validation_sucess").value = "N";
		//document.frmReg.validation_sucess.value='N';	
		
	}

}

function validlname(fname,type)
{
		
		{/literal}
		
		document.getElementById("first_name_error").innerHTML="<img src='{$GLOBAL.tpl_url}/images/loading.gif' border='0' height='23'/>";
		var req1 = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverResponse11);
		str="name="+fname;
		req1.open("POST", "{makeLink2 mod=member pg=ajax_registration}act=valid_lname{/makeLink2}&"+Math.random());
		{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
}

 // email validation
function validEmail(email,type)
{
		{/literal}
		document.getElementById('email_error').style.display='block';
		document.getElementById("email_error").innerHTML="<img src='{$GLOBAL.tpl_url}/images/loading.gif' border='0' height='23'/>";
		var req1 = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverResponse12);
		
		str="email="+email+"&retailer=1";
		{if $GLOBAL.store_id ne ''}
			str=str+"&store_id="+{$GLOBAL.store_id};
		{/if}
		
		//req1.open("POST", "{makeLink mod=member pg=ajax_registration}act=valid_email{/makeLink}&"+Math.random());
		
		
		
		req1.open("POST", "{makeLink2 mod=member pg=ajax_store}act=valid_email{/makeLink2}&"+Math.random());
		{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
}
function serverResponse12(_var) { 

	
	if(_var.trim() == "IMG")
	{
	    
		document.getElementById('email_holder').style.display='block';
		document.getElementById('email_hint').style.display='none';
		document.getElementById('email_error').style.display='block';
		 window.setTimeout(function() { 
		 document.getElementById('email_error').innerHTML="<img src='{/literal}{$GLOBAL.tpl_url}{literal}/images/checkbullet.gif' border='0'/>" ;
		 }, 500);
		 window.setTimeout(function(){document.getElementById('email_error').style.display='none'; }, 1000);
		 
		// document.getElementById("myemail_main").className = "ctrlHolder";
		 document.getElementById("validation_sucess").value = "Y";
		 //document.frmReg.validation_sucess.value='Y';	
		  document.getElementById("email_left").className = "form_wrap";
		  document.getElementById("email_valid").value = "Y";
	}
	else if(_var.trim() == "NONE")
	{	
		document.getElementById('email_holder').style.display='none';
		document.getElementById('email_hint').style.display='none';
		document.getElementById('email_error').style.display='none';
		 document.getElementById("email_left").className = "form_wrap";
		 // document.getElementById("myemail_main").className = "ctrlHolder";
		document.getElementById("validation_sucess").value = "Y";
		 //document.frmReg.validation_sucess.value='Y';	
		 document.getElementById("email_valid").value = "N";
	}
	else
	{
		document.getElementById('email_holder').style.display='block';
		document.getElementById('email_hint').style.display='none';
		document.getElementById('email_error').style.display='block';
		document.getElementById('email_error').innerHTML=_var;
		document.getElementById("email_left").className = "form_wrap_error";
		document.getElementById("validation_sucess").value = "N";
		document.getElementById("email_valid").value = "N";
		
				
	}
}

function validUsername(username,type)
{  
	
  if (document.getElementById('username')){
		{/literal}
		
		document.getElementById('username_name_error').style.display='block';
		document.getElementById("username_name_error").innerHTML="<img src='{$GLOBAL.tpl_url}/images/loading.gif' border='0' height='23'/>";
		var req1 = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverResponse14);
		str="username="+username+"&type="+type;
		
		req1.open("POST", "{makeLink2 mod=member pg=ajax_registration}act=valid_username{/makeLink2}&"+Math.random());
		{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
	}
}
function serverResponse14(_var) { 
	if(_var.trim() == "IMG")
	{
		document.getElementById('username_name_holder').style.display='block';
		document.getElementById('username_name_hint').style.display='none';
		document.getElementById('username_name_error').style.display='block';
		window.setTimeout(function() { 
		document.getElementById('username_name_error').innerHTML="<img src='{/literal}{$GLOBAL.tpl_url}{literal}/images/checkbullet.gif' border='0'/>" ;
		 }, 500);
		 window.setTimeout(function(){document.getElementById('username_name_error').style.display='none'; }, 1000);
		document.getElementById("username_name_left").className = "form_wrap";
		document.getElementById("validation_sucess").value = "Y";
		document.getElementById("username_valid").value = "Y";
		//document.frmReg.validation_sucess.value='Y';	
	}
	else if(_var.trim() == "NONE")
	{	
		document.getElementById('username_name_hint').style.display='none';
		document.getElementById('username_name_holder').style.display='none';
		document.getElementById('username_name_error').style.display='none';
		document.getElementById("validation_sucess").value = "Y";
		document.getElementById("username_name_error").className = "form_error_message";
		document.getElementById("username_name_left").className = "form_wrap_error";
		//document.frmReg.validation_sucess.value='Y';	
		document.getElementById("username_valid").value = "N";
	}
	else
	{
		
		document.getElementById('username_name_holder').style.display='block';
		document.getElementById('username_name_hint').style.display='none';
		document.getElementById('username_name_error').style.display='block';
		document.getElementById('username_name_error').innerHTML=_var;
		document.getElementById("username_name_error").className = "form_error_message";
		document.getElementById("validation_sucess").value = "N";
		document.getElementById("username_valid").value = "N";
		document.getElementById("username_name_left").className = "form_wrap_error";
		//document.frmReg.validation_sucess.value='N';	
		
				
	}
}

// password strength meter
function passwordStrength(password)
{
	
	    var score   = 0;
        //if password bigger than 5 give 1 point
        if (password.length > 5) score++;

        //if password has both lower and uppercase characters give 1 point      
        if ( ( password.match(/[a-z]/) ) && ( password.match(/[A-Z]/) ) ) score++;

        //if password has at least one number give 1 point
        if (password.match(/\d+/)) score++;

        //if password has at least one special caracther give 1 point
        if ( password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) ) score++;
        //if password bigger than 12 give another 1 point
      //  if (password.length > 12) score++;
	  
     //document.getElementById("div_strength").innerHTML = "<img src='{/literal}{$GLOBAL.tpl_url}/images/password_metor"+score+".jpg{literal}' border='0' height='23' width='85'/>";
	 
	 for (i=1; i<5; i++)
	 {
     if (score>=i)	
	   document.getElementById('pass'+i).className = 'block1';
	   else
	   document.getElementById('pass'+i).className = 'block';
	 }
	 
	 
}
// end password strength meter

// password validation 
function validPassword(password,type)
{
		{/literal}
		document.getElementById('password_error').style.display='block';
		document.getElementById("password_error").innerHTML="<img src='{$GLOBAL.tpl_url}/images/loading.gif' border='0' height='23'/>";
		var req1 = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverResponse15);
		str="password="+password+"&type="+type;
		
		req1.open("POST", "{makeLink2 mod=member pg=ajax_registration}act=valid_password{/makeLink2}&"+Math.random());
		{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
}
function serverResponse15(_var) { 
	if(_var.trim() == "IMG")
	{
		document.getElementById('password_error').style.display='block';
		window.setTimeout(function() { 
		document.getElementById('password_error').innerHTML="<img src='{/literal}{$GLOBAL.tpl_url}{literal}/images/checkbullet.gif' border='0'/>" ;
		 }, 500);
		 window.setTimeout(function(){document.getElementById('password_error').style.display='none'; }, 1000);
		
		document.getElementById("password_left").className = "form_wrap";
		
		document.getElementById("validation_sucess").value = "Y";
			
	}
	else if(_var.trim() == "NONE")
	{	
		document.getElementById('password_holder').style.display = 'none';
		document.getElementById('password_hint').style.display = 'none';
		document.getElementById('password_error').style.display = 'none';
		
		document.getElementById("password_left").className = "form_wrap";
		
		document.getElementById("validation_sucess").value = "Y";
		
	}
	else
	{
		//document.getElementById('mypassword_msg').style.display='none';
		document.getElementById('password_holder').style.display = 'block';
		document.getElementById('password_hint').style.display = 'none';
		document.getElementById('password_error').style.display = 'block';
		document.getElementById('password_error').innerHTML=_var;
		//document.getElementById("mypassword_main").className = "ctrlHolder_error";
		document.getElementById("password_left").className = "form_wrap_error";
		
		document.getElementById("validation_sucess").value = "N";
		//document.frmReg.validation_sucess.value='N';	
	}
}

// confirm password validation 
function validConfirmPassword(con_password,type)
{
		{/literal}
		password=document.getElementById('password').value;
		document.getElementById('confirm_pass_error').style.display='block';
		document.getElementById("confirm_pass_error").innerHTML="<img src='{$GLOBAL.tpl_url}/images/loading.gif' border='0' height='23'/>";
		var req1 = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverResponse16);
		str="con_password="+con_password+"&password="+password+"&type="+type;
		
		req1.open("POST", "{makeLink2 mod=member pg=ajax_registration}act=valid_confirmpassword{/makeLink2}&"+Math.random());
		{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
}
function serverResponse16(_var) { 
	if(_var.trim() == "IMG")
	{
		document.getElementById('confirm_pass_error').style.display='block';
		window.setTimeout(function() { 
		document.getElementById('confirm_pass_error').innerHTML="<img src='{/literal}{$GLOBAL.tpl_url}{literal}/images/checkbullet.gif' border='0'/>" ;
		 }, 500);
		 window.setTimeout(function(){document.getElementById('confirm_pass_error').style.display='none'; }, 1000);
		//document.getElementById("confirm_mypassword_main").className = "ctrlHolder";
		document.getElementById("confirm_pass_left").className = "form_wrap";
		
		document.getElementById("validation_sucess").value = "Y";
		//document.frmReg.validation_sucess.value='Y';	
	}
	else if(_var.trim() == "NONE")
	{	
		document.getElementById('confirm_pass_error').style.display = 'none';
		document.getElementById("confirm_pass_left").className = "form_wrap";
		document.getElementById("validation_sucess").value = "Y";
		
	}
	else
	{
		
		document.getElementById('confirm_pass_holder').style.display = 'block';
		document.getElementById('confirm_pass_hint').style.display = 'none';
		document.getElementById('confirm_pass_error').style.display = 'block';
		document.getElementById('confirm_pass_error').innerHTML=_var;
		document.getElementById("confirm_pass_left").className = "form_wrap_error";
		document.getElementById("validation_sucess").value = "N";
		//document.frmReg.validation_sucess.value='N';	
	}
}

// Code validation 
function validCode(code,type)
{
 if (document.getElementById('security_code')){
		{/literal}
		
			document.getElementById('security_code_error').style.display='block';
			document.getElementById("security_code_error").innerHTML="<img src='{$GLOBAL.tpl_url}/images/loading.gif' border='0' height='23'/>";
			var req1 = newXMLHttpRequest();
			req1.onreadystatechange = getReadyStateHandler(req1, serverResponse24);
			str="code="+code+"&type="+type;
			
			req1.open("POST", "{makeLink2 mod=member pg=ajax_registration}act=valid_code{/makeLink2}&"+Math.random());
			{literal}
			req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");		
			req1.send(str);
	    }
		
}
function serverResponse24(_var) { 
	if(_var.trim() == "IMG")
	{		
		document.getElementById('security_code_error').style.display='block';
		window.setTimeout(function() { 
		document.getElementById('security_code_error').innerHTML="<img src='{/literal}{$GLOBAL.tpl_url}{literal}/images/checkbullet.gif' border='0'/>" ;
		 }, 500);
		 window.setTimeout(function(){document.getElementById('security_code_error').style.display='none'; }, 1000);
		
		 document.getElementById("security_code_left").className = "form_wrap";		
		
		 document.getElementById("validation_sucess").value = "Y";		 
	   
				
		
	}
	else if(_var.trim() == "NONE")
	{	
		document.getElementById('security_code_hint').style.display = 'none';
		document.getElementById('security_code_holder').style.display = 'none';
		document.getElementById('security_code_error').style.display = 'none';
	 	//document.getElementById("mycode_main").className = "ctrlHolder";
		document.getElementById("security_code_left").className = "form_wrap";
		document.getElementById("validation_sucess").value = "Y";
		//document.frmReg.validation_sucess.value='Y';			 
	}
	else
	{
		document.getElementById('security_code_holder').style.display = 'block';
		document.getElementById('security_code_error').style.display = 'block';
		document.getElementById('security_code_hint').style.display = 'none';
		document.getElementById('security_code_error').innerHTML=_var;
		
		
		document.getElementById("security_code_left").className = "form_wrap_error";
		document.getElementById("validation_sucess").value = "N";
	    //document.frmReg.validation_sucess.value='N';	
		
		
	}
	}





function validFields()
{
	

	var fname			=	document.frmReg.first_name.value;
	var lname			=	document.frmReg.last_name.value;
	
	var email			=	document.frmReg.email.value;
	
	if (document.getElementById('username')){
	var username		=	document.frmReg.username.value;
	}
	var password		=	document.frmReg.password.value;
	var confirm_pass	=	document.frmReg.confirm_pass.value;
	var address1		=	document.frmReg.address1.value;
	var city			=	document.frmReg.city.value;
	
	var country			=	document.frmReg.country.value;
	var state			=	document.frmReg.state.value;
	
	
	var postalcode		=	document.frmReg.postalcode.value;
	var telephone		=	document.frmReg.telephone.value;
	
	var store_name		=	document.frmReg.store_name.value;
	
	var heading			=	document.frmReg.heading.value;
	var heading1		=	document.frmReg.heading1.value;
	var heading2		=	document.frmReg.heading2.value;
	
	var reg_pack		=	document.frmReg.reg_pack.value;
	var sub_pack		=	document.frmReg.sub_pack.value;
	
	
	
		
	if(fname =='' || fname=='First Name' )	{ 
		var focus_field	=	'first_name';
		document.frmReg.validation_sucess.value='N';
	 }
	if(lname =='' || lname=='Last Name')	{ 
		var focus_field	=	'last_name';
		document.frmReg.validation_sucess.value='N';
	 }
	
		
	
	if(password =='')	{ 
	var focus_field	=	'password';
	document.frmReg.validation_sucess.value='N'; }
	if(confirm_pass =='')	{ 
	var focus_field	=	'confirm_pass';
	document.frmReg.validation_sucess.value='N'; }
	
	if(password != confirm_pass ){
		var focus_field	=	'password';
		document.frmReg.validation_sucess.value='N';
	}
	if(username ==''){
	var focus_field	=	'username';
	 document.frmReg.validation_sucess.value='N'; }
	 else
	{
		if (document.getElementById('username')){
		var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>? ";
		for (var i = 0; i < username.length; i++) 
		{
			if (iChars.indexOf(username.charAt(i)) != -1) 
			{
				var focus_field	=	'username';
				document.frmReg.validation_sucess.value='N';
			} 
		}
		
		}
		
	}	
	
	if(email =='')	{ 
	var focus_field	=	'email';
	document.frmReg.validation_sucess.value='N'; }
	else
	{
	 var filter=/^.+@.+\..{2,3}$/
	 if (!filter.test(email))
	 {
		 var focus_field	=	'email';
		document.frmReg.validation_sucess.value='N'; 
	 }
	}
	
	if(address1 =='')	{ 
	var focus_field	=	'address1';
	document.frmReg.validation_sucess.value='N'; 
	}
	if(city =='')	{ 
	var focus_field	=	'city';
	document.frmReg.validation_sucess.value='N'; 
	}
	if(country =='')	{ 
	var focus_field	=	'country';
	document.frmReg.validation_sucess.value='N'; 
	}
	if(state =='')	{ 
	var focus_field	=	'state';
	document.frmReg.validation_sucess.value='N'; 
	}
	if(postalcode =='')	{ 
	var focus_field	=	'postalcode';
	document.frmReg.validation_sucess.value='N'; 
	}
	if(telephone =='')	{ 
	var focus_field	=	'telephone';
	document.frmReg.validation_sucess.value='N'; 
	}
	
	if(store_name =='')	{ 
	var focus_field	=	'store_name';
	document.frmReg.validation_sucess.value='N'; 
	}
	
	if(heading =='')	{ 
	var focus_field	=	'heading';
	document.frmReg.validation_sucess.value='N'; 
	}
	
	if(heading1 =='')	{ 
	var focus_field	=	'heading1';
	document.frmReg.validation_sucess.value='N'; 
	}
	
	if(heading2 =='')	{ 
	var focus_field	=	'heading2';
	document.frmReg.validation_sucess.value='N'; 
	}
	
	if(reg_pack =='')	{ 
	var focus_field	=	'reg_pack';
	document.frmReg.validation_sucess.value='N'; 
	}
	
	if(sub_pack =='')	{ 
	var focus_field	=	'sub_pack';
	document.frmReg.validation_sucess.value='N'; 
	}
	
	//alert(document.frmReg.validation_sucess.value);
	
	if(!document.frmReg.terms.checked) {	
		document.frmReg.validation_sucess.value='N';
  		document.getElementById('chk_accept_left').className = "form_wrap_big_error";
		document.getElementById('chk_accept_left_error').style.display = 'block';
		
		
	} 
		
	//alert(document.getElementById("validation_sucess").value);	
		
	window.location.href="#e1"; 
		
	
	
	validMyname(fname,'A',lname);
   	validEmail(email,'A');
	validUsername(username,'A');
	validPassword(password,'A');
	validConfirmPassword(confirm_pass,'A');
	
	
	validAddress(address1,'A');
	validCity(city,'A');
	validCountry(country,'A');
	validState(state,'A');
	
	validZipCode(postalcode,'A');
	validTelephone(telephone,'A');
	
	validHeading1(heading1,'A');
	validHeading2(heading2,'A');
	
	validStore(store_name,'A');
	validStoreName(heading,'A');
	validRegPack(reg_pack,'A');
	
	setTimeout(function(){
	var x = document.frmReg.getElementsByClassName('form_wrap_error');
	
	if(document.frmReg.promo_code.value !='' && typeof x == 'undefined')
	{	document.getElementById("promo_valid").value = 'N';
		checkPromoCode(document.frmReg.promo_code,0);
	}
	else
	document.getElementById("promo_valid").value = 'Y';
	
	validSubPack(sub_pack,'A');
	  
   if(document.getElementById("promo_valid").value == 'N'){
	var focus_field	=	'promo_code';
	document.frmReg.validation_sucess.value='N'; 
   }	
   
   
   if(document.getElementById("username_valid").value != 'Y'){
   
   	document.frmReg.validation_sucess.value='N'; 
   }	
   if(document.getElementById("email_valid").value != 'Y'){
   
   	document.frmReg.validation_sucess.value='N'; 
   }
   if(document.getElementById("storename_valid").value != 'Y'){
   	document.frmReg.validation_sucess.value='N'; 
   }
   
   if(document.getElementById("storeheading_valid").value != 'Y'){
   
   	document.frmReg.validation_sucess.value='N'; 
   }	
	submit_form();
	},17000); 
	/*if(document.frmReg.promo_code.value !='')
	{
		document.getElementById("promo_valid").value = 'N';
		checkPromoCode(document.frmReg.promo_code,0);
	}
	else
	document.getElementById("promo_valid").value = 'Y';
	
	validSubPack(sub_pack,'A');
	  
   if(document.getElementById("promo_valid").value == 'N'){
	var focus_field	=	'promo_code';
	document.frmReg.validation_sucess.value='N'; 
   }	
   
   
   if(document.getElementById("username_valid").value != 'Y'){
   
   	document.frmReg.validation_sucess.value='N'; 
   }	
   if(document.getElementById("email_valid").value != 'Y'){
   
   	document.frmReg.validation_sucess.value='N'; 
   }
   if(document.getElementById("storename_valid").value != 'Y'){
   
   	document.frmReg.validation_sucess.value='N'; 
   }
   
   if(document.getElementById("storeheading_valid").value != 'Y'){
   
   	document.frmReg.validation_sucess.value='N'; 
   }	
     
	 
	setTimeout("submit_form()",15000); */
	 
	
	
	
}

function submit_form()
{

if(document.getElementById("validation_sucess").value == 'Y')	
	{
	   storeValues();
	}else
	 {
	  return false;
	 } 

}
/*function show_state(opt_name,country_id,state_name,css_select,css_text) 
{
	document.getElementById('div_state').innerHTML="Loading....";
	//alert(document.getElementById('country').label)
	//country_name=document.getElementById('country')[document.getElementById('country').selectedIndex].innerHTML;
	var req1 = newXMLHttpRequest();
	req1.onreadystatechange = getReadyStateHandler(req1, serverRese);
	str="opt_name="+opt_name+"&country_id="+country_id+"&state_name="+state_name+"&css_select="+css_select+"&css_text="+css_text;
	{/literal}
	req1.open("POST", "{makeLink mod=member pg=ajax_state}{/makeLink}&"+Math.random());
	{literal}
	req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	req1.send(str);
	}
	function serverRese(_var) {
	_var = _var.split('|');
	document.getElementById('div_state').innerHTML=_var[0];
}*/

function submitFrm()
{
		if(document.getElementById("validation_sucess").value == 'Y')	
	{
	  //alert(document.getElementById("validation_sucess").value);
	   storeValues();
	}else
	 {
	  return false;
	 } 
}


function validStore(store_name)
{
	    var str1 = document.frmReg.store_name.value
	
		{/literal}
		//document.getElementById("msg_div").innerHTML="<img src='{$GLOBAL.tpl_url}/images/blue_light.gif' border='0' height='23'/><strong style='color:#990000'>CHECKING...</strong>";
		var req1 = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverResponse1);
		str="store_name="+str1;
		req1.open("POST", "{makeLink2 mod=member pg=ajax_store}act=valid_store{/makeLink2}&"+Math.random());
		{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
	
}
function serverResponse1(_var) {
		if(_var.trim() == "IMG")
	{
	    
		document.getElementById('store_name_holder').style.display='block';
		document.getElementById('store_name_hint').style.display='none';
		document.getElementById('store_name_error').style.display='block';
		 window.setTimeout(function() { 
		 document.getElementById('store_name_error').innerHTML="<img src='{/literal}{$GLOBAL.tpl_url}{literal}/images/checkbullet.gif' border='0'/>" ;
		 }, 500);
		 window.setTimeout(function(){document.getElementById('store_name_error').style.display='none'; }, 1000);
		 
		// document.getElementById("myemail_main").className = "ctrlHolder";
		 document.getElementById("validation_sucess").value = "Y";
		 document.getElementById("storename_valid").value = "Y";
		 //document.frmReg.validation_sucess.value='Y';	
		  document.getElementById("store_name_left").className = "form_wrap";
	}
	else if(_var.trim() == "NONE")
	{	
		document.getElementById('store_name_holder').style.display='none';
		document.getElementById('store_name_hint').style.display='none';
		document.getElementById('store_name_error').style.display='none';
		 document.getElementById("store_name_left").className = "form_wrap";
		 // document.getElementById("myemail_main").className = "ctrlHolder";
		document.getElementById("validation_sucess").value = "Y";
		 //document.frmReg.validation_sucess.value='Y';	
		 document.getElementById("storename_valid").value = "N";
	}
	else
	{
		document.getElementById('store_name_holder').style.display='block';
		document.getElementById('store_name_hint').style.display='none';
		document.getElementById('store_name_error').style.display='block';
		document.getElementById('store_name_error').innerHTML=_var;
		document.getElementById("store_name_left").className = "form_wrap_error";
		document.getElementById("validation_sucess").value = "N";
		document.getElementById("storename_valid").value = "N";
		
				
	}
}


function validAddress(addr,type)
{
		
		{/literal}
		
		document.getElementById("address1_error").innerHTML="<img src='{$GLOBAL.tpl_url}/images/loading.gif' border='0' height='23'/>";
		var req1 = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverResponseValidAddr);
		str="address="+addr+"&type="+type;
		req1.open("POST", "{makeLink2 mod=member pg=ajax_registration}act=valid_address{/makeLink2}&"+Math.random());
		{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
}
function serverResponseValidAddr(_var) { 

	if(_var.trim() == 'IMG')
		{ 
		
		document.getElementById('address1_holder').style.display='block';
		document.getElementById('address1_hint').style.display = 'none';
		document.getElementById('address1_error').style.display = 'block';
		//document.getElementById('first_name_hint').style.display='block';
		window.setTimeout(function() { 
		document.getElementById('address1_error').innerHTML="<img src='{/literal}{$GLOBAL.tpl_url}{literal}/images/checkbullet.gif' border='0'/>";
		}, 500);
		 window.setTimeout(function(){document.getElementById('address1_error').style.display='none'; }, 1000);
		 //document.getElementById("myname_main").className = "ctrlHolder";
		 document.getElementById("address1_left").className = "form_wrap";
		 document.getElementById("validation_sucess").value = "Y";		 	
		 
	}
	
	else if(_var.trim() == "NONE")
	{	
		 document.getElementById('address1_holder').style.display='none';
		document.getElementById('address1_hint').style.display = 'none';
		document.getElementById('address1_error').style.display = 'none';
		 document.getElementById("address1_left").className = "form_wrap";
		 document.getElementById("validation_sucess").value = "Y";
	}
	else
	{	
		document.getElementById('address1_error').innerHTML=_var;
		document.getElementById('address1_holder').style.display='block';
		document.getElementById('address1_hint').style.display = 'none';
		document.getElementById('address1_error').style.display = 'block';
		document.getElementById("address1_left").className = "form_wrap_error";
		document.getElementById("validation_sucess").value = "N";
	}

}

function validCity(val,type)
{
		
		{/literal}
		
		document.getElementById("city_error").innerHTML="<img src='{$GLOBAL.tpl_url}/images/loading.gif' border='0' height='23'/>";
		var req1 = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverResponseValidCity);
		str="city="+val+"&type="+type;
		req1.open("POST", "{makeLink2 mod=member pg=ajax_registration}act=valid_city{/makeLink2}&"+Math.random());
		{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
}
function serverResponseValidCity(_var) { 

	if(_var.trim() == 'IMG')
		{ 
		
		document.getElementById('city_holder').style.display='block';
		document.getElementById('city_hint').style.display = 'none';
		document.getElementById('city_error').style.display = 'block';
		//document.getElementById('first_name_hint').style.display='block';
		window.setTimeout(function() { 
		document.getElementById('city_error').innerHTML="<img src='{/literal}{$GLOBAL.tpl_url}{literal}/images/checkbullet.gif' border='0'/>";
		}, 500);
		 window.setTimeout(function(){document.getElementById('city_error').style.display='none'; }, 1000);
		 //document.getElementById("myname_main").className = "ctrlHolder";
		 document.getElementById("city_left").className = "form_wrap";
		 document.getElementById("validation_sucess").value = "Y";		 	
		 
	}
	
	else if(_var.trim() == "NONE")
	{	
		 document.getElementById('city_holder').style.display='none';
		document.getElementById('city_hint').style.display = 'none';
		document.getElementById('city_error').style.display = 'none';
		 document.getElementById("city_left").className = "form_wrap";
		 document.getElementById("validation_sucess").value = "Y";
	}
	else
	{	
		document.getElementById('city_error').innerHTML=_var;
		document.getElementById('city_holder').style.display='block';
		document.getElementById('city_hint').style.display = 'none';
		document.getElementById('city_error').style.display = 'block';
		document.getElementById("city_left").className = "form_wrap_error";
		document.getElementById("validation_sucess").value = "N";
	}

}


function validCountry(val,type)
{
		
		{/literal}
		
		document.getElementById("country_error").innerHTML="<img src='{$GLOBAL.tpl_url}/images/loading.gif' border='0' height='23'/>";
		var req1 = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverResponseValidCountry);
		str="country="+val+"&type="+type;
		req1.open("POST", "{makeLink2 mod=member pg=ajax_registration}act=valid_country{/makeLink2}&"+Math.random());
		{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
}
function serverResponseValidCountry(_var) { 

	if(_var.trim() == 'IMG')
		{ 
		
		document.getElementById('country_holder').style.display='block';
		document.getElementById('country_hint').style.display = 'none';
		document.getElementById('country_error').style.display = 'block';
		//document.getElementById('first_name_hint').style.display='block';
		window.setTimeout(function() { 
		document.getElementById('country_error').innerHTML="<img src='{/literal}{$GLOBAL.tpl_url}{literal}/images/checkbullet.gif' border='0'/>";
		}, 500);
		 window.setTimeout(function(){document.getElementById('country_error').style.display='none'; }, 1000);
		 //document.getElementById("myname_main").className = "ctrlHolder";
		 document.getElementById("country_left").className = "form_wrap";
		 document.getElementById("validation_sucess").value = "Y";		 	
		 
	}
	
	else if(_var.trim() == "NONE")
	{	
		 document.getElementById('country_holder').style.display='none';
		document.getElementById('country_hint').style.display = 'none';
		document.getElementById('country_error').style.display = 'none';
		 document.getElementById("country_left").className = "form_wrap";
		 document.getElementById("validation_sucess").value = "Y";
	}
	else
	{	
		document.getElementById('country_error').innerHTML=_var;
		document.getElementById('country_holder').style.display='block';
		document.getElementById('country_hint').style.display = 'none';
		document.getElementById('country_error').style.display = 'block';
		document.getElementById("country_left").className = "form_wrap_error";
		document.getElementById("validation_sucess").value = "N";
	}

}


function validState(val,type)
{
		
		{/literal}
		var state			=	document.frmReg.state.value;
		
		
		document.getElementById("state_error").innerHTML="<img src='{$GLOBAL.tpl_url}/images/loading.gif' border='0' height='23'/>";
		var req1 = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverResponseValidState);
		str="state="+state+"&type="+type;
		req1.open("POST", "{makeLink2 mod=member pg=ajax_registration}act=valid_state{/makeLink2}&"+Math.random());
		{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
}
function serverResponseValidState(_var) { 

	if(_var.trim() == 'IMG')
		{ 
		
		document.getElementById('state_holder').style.display='block';
		document.getElementById('state_hint').style.display = 'none';
		document.getElementById('state_error').style.display = 'block';
		//document.getElementById('first_name_hint').style.display='block';
		window.setTimeout(function() { 
		document.getElementById('state_error').innerHTML="<img src='{/literal}{$GLOBAL.tpl_url}{literal}/images/checkbullet.gif' border='0'/>";
		}, 500);
		 window.setTimeout(function(){document.getElementById('state_error').style.display='none'; }, 1000);
		 //document.getElementById("myname_main").className = "ctrlHolder";
		 document.getElementById("state_left").className = "form_wrap";
		 document.getElementById("validation_sucess").value = "Y";		 	
		 
	}
	
	else if(_var.trim() == "NONE")
	{	
		 document.getElementById('state_holder').style.display='none';
		document.getElementById('state_hint').style.display = 'none';
		document.getElementById('state_error').style.display = 'none';
		 document.getElementById("state_left").className = "form_wrap";
		 document.getElementById("validation_sucess").value = "Y";
	}
	else
	{	
		document.getElementById('state_error').innerHTML=_var;
		document.getElementById('state_holder').style.display='block';
		document.getElementById('state_hint').style.display = 'none';
		document.getElementById('state_error').style.display = 'block';
		document.getElementById("state_left").className = "form_wrap_error";
		document.getElementById("validation_sucess").value = "N";
	}

}

function validZipCode(val,type)
{
		
		{/literal}
		
		document.getElementById("postalcode_error").innerHTML="<img src='{$GLOBAL.tpl_url}/images/loading.gif' border='0' height='23'/>";
		var req1 = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverResponsevalidZipCode);
		str="zip="+val+"&type="+type;
		req1.open("POST", "{makeLink2 mod=member pg=ajax_registration}act=valid_zip{/makeLink2}&"+Math.random());
		{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
}
function serverResponsevalidZipCode(_var) { 

	if(_var.trim() == 'IMG')
		{ 
		
		document.getElementById('postalcode_holder').style.display='block';
		document.getElementById('postalcode_hint').style.display = 'none';
		document.getElementById('postalcode_error').style.display = 'block';
		//document.getElementById('first_name_hint').style.display='block';
		window.setTimeout(function() { 
		document.getElementById('postalcode_error').innerHTML="<img src='{/literal}{$GLOBAL.tpl_url}{literal}/images/checkbullet.gif' border='0'/>";
		}, 500);
		 window.setTimeout(function(){document.getElementById('postalcode_error').style.display='none'; }, 1000);
		 //document.getElementById("myname_main").className = "ctrlHolder";
		 document.getElementById("postalcode_left").className = "form_wrap";
		 document.getElementById("validation_sucess").value = "Y";		 	
		 
	}
	
	else if(_var.trim() == "NONE")
	{	
		 document.getElementById('postalcode_holder').style.display='none';
		document.getElementById('postalcode_hint').style.display = 'none';
		document.getElementById('postalcode_error').style.display = 'none';
		 document.getElementById("postalcode_left").className = "form_wrap";
		 document.getElementById("validation_sucess").value = "Y";
	}
	else
	{	
		document.getElementById('postalcode_error').innerHTML=_var;
		document.getElementById('postalcode_holder').style.display='block';
		document.getElementById('postalcode_hint').style.display = 'none';
		document.getElementById('postalcode_error').style.display = 'block';
		document.getElementById("postalcode_left").className = "form_wrap_error";
		document.getElementById("validation_sucess").value = "N";
	}

}

	function validTelephone(val,type)
{
		
		{/literal}
		
		document.getElementById("telephone_error").innerHTML="<img src='{$GLOBAL.tpl_url}/images/loading.gif' border='0' height='23'/>";
		var req1 = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverResponseValidTelephone);
		str="phone="+val+"&type="+type;
		req1.open("POST", "{makeLink2 mod=member pg=ajax_registration}act=valid_telephone{/makeLink2}&"+Math.random());
		{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
}
function serverResponseValidTelephone(_var) { 

	if(_var.trim() == 'IMG')
		{ 
		
		document.getElementById('telephone_holder').style.display='block';
		document.getElementById('telephone_hint').style.display = 'none';
		document.getElementById('telephone_error').style.display = 'block';
		//document.getElementById('first_name_hint').style.display='block';
		window.setTimeout(function() { 
		document.getElementById('telephone_error').innerHTML="<img src='{/literal}{$GLOBAL.tpl_url}{literal}/images/checkbullet.gif' border='0'/>";
		}, 500);
		 window.setTimeout(function(){document.getElementById('telephone_error').style.display='none'; }, 1000);
		 //document.getElementById("myname_main").className = "ctrlHolder";
		 document.getElementById("telephone_left").className = "form_wrap";
		 document.getElementById("validation_sucess").value = "Y";		 	
		 
	}
	
	else if(_var.trim() == "NONE")
	{	
		 document.getElementById('telephone_holder').style.display='none';
		document.getElementById('telephone_hint').style.display = 'none';
		document.getElementById('telephone_error').style.display = 'none';
		 document.getElementById("telephone_left").className = "form_wrap";
		 document.getElementById("validation_sucess").value = "Y";
	}
	else
	{	
		document.getElementById('telephone_error').innerHTML=_var;
		document.getElementById('telephone_holder').style.display='block';
		document.getElementById('telephone_hint').style.display = 'none';
		document.getElementById('telephone_error').style.display = 'block';
		document.getElementById("telephone_left").className = "form_wrap_error";
		document.getElementById("validation_sucess").value = "N";
	}

}


function validStoreName(val,type)
{
		
		{/literal}
		
		document.getElementById("heading_error").innerHTML="<img src='{$GLOBAL.tpl_url}/images/loading.gif' border='0' height='23'/>";
		var req1 = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverResponseValidStorename);
		str="store_name="+val+"&type="+type;
		req1.open("POST", "{makeLink2 mod=member pg=ajax_registration}act=valid_store_name{/makeLink2}&"+Math.random());
		{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
}
function serverResponseValidStorename(_var) { 

	if(_var.trim() == 'IMG')
		{ 
		
		document.getElementById('heading_holder').style.display='block';
		document.getElementById('heading_hint').style.display = 'none';
		document.getElementById('heading_error').style.display = 'block';
		//document.getElementById('first_name_hint').style.display='block';
		window.setTimeout(function() { 
		document.getElementById('heading_error').innerHTML="<img src='{/literal}{$GLOBAL.tpl_url}{literal}/images/checkbullet.gif' border='0'/>";
		}, 500);
		 window.setTimeout(function(){document.getElementById('heading_error').style.display='none'; }, 1000);
		 //document.getElementById("myname_main").className = "ctrlHolder";
		 document.getElementById("heading_left").className = "form_wrap";
		 document.getElementById("validation_sucess").value = "Y";	
		 document.getElementById("storeheading_valid").value='Y'; 	
		 
	}
	
	else if(_var.trim() == "NONE")
	{	
		 document.getElementById('heading_holder').style.display='none';
		document.getElementById('heading_hint').style.display = 'none';
		document.getElementById('heading_error').style.display = 'none';
		 document.getElementById("heading_left").className = "form_wrap";
		 document.getElementById("validation_sucess").value = "Y";
		 document.getElementById("storeheading_valid").value='N';
	}
	else
	{	
		document.getElementById('heading_error').innerHTML=_var;
		document.getElementById('heading_holder').style.display='block';
		document.getElementById('heading_hint').style.display = 'none';
		document.getElementById('heading_error').style.display = 'block';
		document.getElementById("heading_left").className = "form_wrap_error";
		document.getElementById("validation_sucess").value = "N";
		document.getElementById("storeheading_valid").value='N'	; 
	}

}

function validRegPack(val,type)
{
		
		{/literal}
		
		document.getElementById("reg_pack_error").innerHTML="<img src='{$GLOBAL.tpl_url}/images/loading.gif' border='0' height='23'/>";
		var req1 = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverResponseValidRegPack);
		str="package="+val+"&type="+type;
		req1.open("POST", "{makeLink2 mod=member pg=ajax_registration}act=valid_package{/makeLink2}&"+Math.random());
		{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
}
function serverResponseValidRegPack(_var) { 

	if(_var.trim() == 'IMG')
		{ 
		
		document.getElementById('reg_pack_holder').style.display='block';
		document.getElementById('reg_pack_hint').style.display = 'none';
		document.getElementById('reg_pack_error').style.display = 'block';
		//document.getElementById('first_name_hint').style.display='block';
		window.setTimeout(function() { 
		document.getElementById('reg_pack_error').innerHTML="<img src='{/literal}{$GLOBAL.tpl_url}{literal}/images/checkbullet.gif' border='0'/>";
		}, 500);
		 window.setTimeout(function(){document.getElementById('reg_pack_error').style.display='none'; }, 1000);
		 //document.getElementById("myname_main").className = "ctrlHolder";
		 document.getElementById("reg_pack_left").className = "form_wrap";
		 document.getElementById("validation_sucess").value = "Y";		 	
		 
	}
	
	else if(_var.trim() == "NONE")
	{	
		 document.getElementById('reg_pack_holder').style.display='none';
		document.getElementById('reg_pack_hint').style.display = 'none';
		document.getElementById('reg_pack_error').style.display = 'none';
		 document.getElementById("reg_pack_left").className = "form_wrap";
		 document.getElementById("validation_sucess").value = "Y";
	}
	else
	{	
		document.getElementById('reg_pack_error').innerHTML=_var;
		document.getElementById('reg_pack_holder').style.display='block';
		document.getElementById('reg_pack_hint').style.display = 'none';
		document.getElementById('reg_pack_error').style.display = 'block';
		document.getElementById("reg_pack_left").className = "form_wrap_error";
		document.getElementById("validation_sucess").value = "N";
	}

}

function validSubPack(val,type)
{
		
		{/literal}
		
		document.getElementById("reg_pack_error").innerHTML="<img src='{$GLOBAL.tpl_url}/images/loading.gif' border='0' height='23'/>";
		var req1 = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverResponsevalidSubPack);
		str="subpack="+val+"&type="+type;
		req1.open("POST", "{makeLink2 mod=member pg=ajax_registration}act=valid_subpack{/makeLink2}&"+Math.random());
		{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
}
function serverResponsevalidSubPack(_var) { 

	if(_var.trim() == 'IMG')
		{ 
		
		document.getElementById('sub_pack_holder').style.display='block';
		document.getElementById('sub_pack_hint').style.display = 'none';
		document.getElementById('sub_pack_error').style.display = 'block';
		//document.getElementById('first_name_hint').style.display='block';
		window.setTimeout(function() { 
		document.getElementById('sub_pack_error').innerHTML="<img src='{/literal}{$GLOBAL.tpl_url}{literal}/images/checkbullet.gif' border='0'/>";
		}, 500);
		 window.setTimeout(function(){document.getElementById('sub_pack_error').style.display='none'; }, 1000);
		 //document.getElementById("myname_main").className = "ctrlHolder";
		 document.getElementById("sub_pack_left").className = "form_wrap";
		 document.getElementById("validation_sucess").value = "Y";		 	
		 
	}
	
	else if(_var.trim() == "NONE")
	{	
		 document.getElementById('sub_pack_holder').style.display='none';
		document.getElementById('sub_pack_hint').style.display = 'none';
		document.getElementById('sub_pack_error').style.display = 'none';
		 document.getElementById("sub_pack_left").className = "form_wrap";
		 document.getElementById("validation_sucess").value = "Y";
	}
	else
	{	
		document.getElementById('sub_pack_error').innerHTML=_var;
		document.getElementById('sub_pack_holder').style.display='block';
		document.getElementById('sub_pack_hint').style.display = 'none';
		document.getElementById('sub_pack_error').style.display = 'block';
		document.getElementById("sub_pack_left").className = "form_wrap_error";
		document.getElementById("validation_sucess").value = "N";
	}
			
			//document.getElementById('submit_div').innerHTML='<a href="javascript:void(0);" onclick="validFields();"><img src="https://www.paypal.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" /></a>';
			//  $.unblockUI();
			  disablePopup();

}
function validHeading1(val,type)
{
		
		{/literal}
		
		document.getElementById("heading1_error").innerHTML="<img src='{$GLOBAL.tpl_url}/images/loading.gif' border='0' height='23'/>";
		var req1 = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverResponsevalidHeading1);
		str="heading1="+val+"&type="+type;
		req1.open("POST", "{makeLink2 mod=member pg=ajax_registration}act=valid_heading1{/makeLink2}&"+Math.random());
		{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
}
function serverResponsevalidHeading1(_var) { 

	if(_var.trim() == 'IMG')
		{ 
		
		document.getElementById('heading1_holder').style.display='block';
		document.getElementById('heading1_hint').style.display = 'none';
		document.getElementById('heading1_error').style.display = 'block';
		//document.getElementById('first_name_hint').style.display='block';
		window.setTimeout(function() { 
		document.getElementById('heading1_error').innerHTML="<img src='{/literal}{$GLOBAL.tpl_url}{literal}/images/checkbullet.gif' border='0'/>";
		}, 500);
		 window.setTimeout(function(){document.getElementById('heading1_error').style.display='none'; }, 1000);
		 //document.getElementById("myname_main").className = "ctrlHolder";
		 document.getElementById("heading1_left").className = "form_wrap";
		 document.getElementById("validation_sucess").value = "Y";		 	
		 
	}
	
	else if(_var.trim() == "NONE")
	{	
		 document.getElementById('heading1_holder').style.display='none';
		document.getElementById('heading1_hint').style.display = 'none';
		document.getElementById('heading1_error').style.display = 'none';
		 document.getElementById("heading1_left").className = "form_wrap";
		 document.getElementById("validation_sucess").value = "Y";
	}
	else
	{	
		document.getElementById('heading1_error').innerHTML=_var;
		document.getElementById('heading1_holder').style.display='block';
		document.getElementById('heading1_hint').style.display = 'none';
		document.getElementById('heading1_error').style.display = 'block';
		document.getElementById("heading1_left").className = "form_wrap_error";
		document.getElementById("validation_sucess").value = "N";
	}

}


function validHeading2(val,type)
{
		
		{/literal}
		
		document.getElementById("heading2_error").innerHTML="<img src='{$GLOBAL.tpl_url}/images/loading.gif' border='0' height='23'/>";
		var req1 = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverResponsevalidHeading2);
		str="heading2="+val+"&type="+type;
		req1.open("POST", "{makeLink2 mod=member pg=ajax_registration}act=valid_heading2{/makeLink2}&"+Math.random());
		{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
}
function serverResponsevalidHeading2(_var) { 

	if(_var.trim() == 'IMG')
		{ 
		
		document.getElementById('heading2_holder').style.display='block';
		document.getElementById('heading2_hint').style.display = 'none';
		document.getElementById('heading2_error').style.display = 'block';
		//document.getElementById('first_name_hint').style.display='block';
		window.setTimeout(function() { 
		document.getElementById('heading2_error').innerHTML="<img src='{/literal}{$GLOBAL.tpl_url}{literal}/images/checkbullet.gif' border='0'/>";
		}, 500);
		 window.setTimeout(function(){document.getElementById('heading2_error').style.display='none'; }, 1000);
		 //document.getElementById("myname_main").className = "ctrlHolder";
		 document.getElementById("heading2_left").className = "form_wrap";
		 document.getElementById("validation_sucess").value = "Y";		 	
		 
	}
	
	else if(_var.trim() == "NONE")
	{	
		 document.getElementById('heading2_holder').style.display='none';
		document.getElementById('heading2_hint').style.display = 'none';
		document.getElementById('heading2_error').style.display = 'none';
		 document.getElementById("heading2_left").className = "form_wrap";
		 document.getElementById("validation_sucess").value = "Y";
	}
	else
	{	
		document.getElementById('heading2_error').innerHTML=_var;
		document.getElementById('heading2_holder').style.display='block';
		document.getElementById('heading2_hint').style.display = 'none';
		document.getElementById('heading2_error').style.display = 'block';
		document.getElementById("heading2_left").className = "form_wrap_error";
		document.getElementById("validation_sucess").value = "N";
	}

}


function validatePromoCode()
{
	checkPromoCode(document.frmReg.promo_code,1);
}

function convertNumberToDecimal(amount, precision)
{
	var num = new Number(amount);
	return num.toFixed(precision);
}


function checkPromoCode(PromoElementObj,validate_only_coupon)
{
	
	 var sub_pack 		= document.frmReg.sub_pack.value;
	 
	 var reg_pack   	 = document.frmReg.reg_pack.value;
	 
	 
	 
	
	 promo_code	=	PromoElementObj.value.trim();
	
	
	validRegPack(reg_pack,'A');
	validSubPack(sub_pack,'A');
	validCouponCode(promo_code,'A');
	
	

	
	if(promo_code!='' && sub_pack !='' && reg_pack !='')
	{
	
	//document.getElementById('loadinginfo').innerHTML	=	'Validating Coupon Code..';
	
	//	document.frmReg.tot_amt.value			=	document.frmReg.orig_tot_amt.value;
	//document.frmReg.UPGRADE_TOT_AMT.value	=	document.frmReg.orig_UPGRADE_TOT_AMT.value;
	
	//document.getElementById('totalblock').innerHTML		=	'$' + convertNumberToDecimal(document.frmReg.orig_tot_amt.value, 2);
	
	//document.getElementById('deductblock1').style.display	=	'none';
	//document.getElementById('deductblock2').style.display	=	'none';
	
	
	
	
	
	//document.getElementById('btn_save').disabled		=	'disabled';
	
	{/literal}
	XHRObj	=	newXMLHttpRequest();
	XHRObj.onreadystatechange = getReadyStateHandler(XHRObj, promoCodeResponse);
	Params	=	'PromotionCode=' + promo_code +'&sub_pack=' +sub_pack;
	{literal}
	if(reg_pack)
	{
		Params=Params+'&reg_pack='+reg_pack;
	}
	if(validate_only_coupon)
	{
	Params=Params+'&validate_only_coupon=1';
	}
	{/literal}
	
	
	
	XHRObj.open("POST", "{makeLink mod=member pg=ajax_store}act=check_promocode{/makeLink}&" + Math.random());
	XHRObj.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	
	XHRObj.send(Params);
	{literal}
	}
}

function promoCodeResponse(responsePromoStatus)
{
	var	ResultArray		=	responsePromoStatus.split('|');
	
	promostatus			=	ResultArray[0].trim();
	deductionamount		=	convertNumberToDecimal(parseFloat(ResultArray[1].trim()),2);
	RegDeductionamount	=	convertNumberToDecimal(parseFloat(ResultArray[2].trim()),2);
			
	if(promostatus == 'YES') {
			 
		packageamount	=	parseFloat(document.getElementById('subpack_amt').value);
		packageamount	=	convertNumberToDecimal((packageamount - deductionamount), 2);
		
		regamount		=	parseFloat(document.getElementById('reg_amt').value);
		regamount		=	convertNumberToDecimal((regamount - RegDeductionamount), 2);
		subpackamount	=	parseFloat(document.getElementById('subpack_amt').value);
		subpackamount	=	convertNumberToDecimal((subpackamount - deductionamount), 2);
		newtotalAmt		=	convertNumberToDecimal((parseFloat(subpackamount) + parseFloat(regamount)), 2)
		totalDeduction	=	convertNumberToDecimal((parseFloat(deductionamount) + parseFloat(RegDeductionamount)), 2)
				
		
		document.getElementById('totaldeduction_setup').innerHTML		=	'$' + RegDeductionamount;
		document.getElementById('totaldeduction_subscription').innerHTML=	'$' + deductionamount;
		document.getElementById('totaldeduction').innerHTML				=	'$' + totalDeduction;
		document.getElementById('totalblock').innerHTML					=	'$' + newtotalAmt;
		document.getElementById('loadinginfo').innerHTML		=	'';	
		document.getElementById('promo_valid').value='Y';
		
		
		/*document.frmReg.subscription_discount.value		=	convertNumberToDecimal(deductionamount, 2);
		document.frmReg.tot_amt.value					=	convertNumberToDecimal(newtotalAmt, 2);
		document.frmReg.reg_amt.value					=	convertNumberToDecimal(regamount, 2);
		document.frmReg.subpack_amt.value				=	convertNumberToDecimal(subpackamount, 2);
		document.frmReg.UPGRADE_TOT_AMT.value			=	convertNumberToDecimal(packageamount, 2);*/
		
		document.getElementById('tot_amt').value= convertNumberToDecimal(newtotalAmt, 2);
		document.getElementById('hid_div').style.display = 'inline';
		
		document.getElementById('promo_code_holder').style.display='block';
		document.getElementById('promo_code_hint').style.display = 'none';
		document.getElementById('promo_code_error').style.display = 'block';
		document.getElementById('first_name_hint').style.display='block';
		
		
		
		
		window.setTimeout(function() { 
		document.getElementById('promo_code_error').innerHTML="<img src='{/literal}{$GLOBAL.tpl_url}{literal}/images/checkbullet.gif' border='0'/>";
		}, 500);
		 window.setTimeout(function(){document.getElementById('promo_code_error').style.display='none'; }, 1000);
		 //document.getElementById("myname_main").className = "ctrlHolder";
		 document.getElementById("promo_code_left").className = "form_wrap";
		 
		
		 if(!ResultArray[3]){
				submitFrm();
		}
	
	}
	
	if(promostatus == 'NO') {
	    //document.getElementById('loadinginfo').innerHTML	=	'Enter Valid Coupon Code..';
		document.getElementById('promo_code_error').innerHTML='Enter Valid Coupon Code.';
		document.getElementById('promo_code_holder').style.display='block';
		document.getElementById('promo_code_hint').style.display = 'none';
		document.getElementById('promo_code_error').style.display = 'block';
		document.getElementById("promo_code_left").className = "form_wrap_error";
		//document.getElementById("validation_sucess").value = "N";
		
		document.getElementById('promo_valid').value='N';
		
		//document.frmReg.promo_code.value					=	'';	
		//document.frmReg.subscription_discount.value			=	'';	
		
		document.getElementById('totaldeduction_setup').innerHTML		=	'$0.00';
		document.getElementById('totaldeduction_subscription').innerHTML=	'$0.00';
		document.getElementById('totaldeduction').innerHTML				=	'$0.00';
		document.getElementById('hid_div').style.display = 'none';
		/*document.frmReg.tot_amt.value					=	{/literal}{$TOT_AMT}{literal};
		document.frmReg.reg_amt.value					=	{/literal}{$REGISTRATION_AMT}{literal};
		document.frmReg.subpack_amt.value				=	{/literal}{$SUBDCRIPTION_AMT}{literal};*/
		document.getElementById('tot_amt').value= '';
	}
	//document.getElementById('btn_save').disabled		=	'';
		//alert(document.getElementById('promo_valid').value);
}


  function regPackPrice(reg_pack){
  
  		var req1 = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverResRegPackPrice);
		str="id="+reg_pack;
		{/literal}	
		req1.open("POST", "{makeLink mod=member pg=ajax_store}act=load_reg_pack_price{/makeLink}&"+Math.random());
		{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
  }
  
  function serverResRegPackPrice(_var)
  {
	document.getElementById('reg_amt_div').innerHTML	= _var;
  }
  
  
  function validCouponCode(val,type)
{
		
		{/literal}
		
		document.getElementById("promo_code_error").innerHTML="<img src='{$GLOBAL.tpl_url}/images/loading.gif' border='0' height='23'/>";
		var req1 = newXMLHttpRequest();
		req1.onreadystatechange = getReadyStateHandler(req1, serverResponseValidCouponCode);
		str="promo_code="+val+"&type="+type;
		req1.open("POST", "{makeLink mod=member pg=ajax_registration}act=valid_promo_code{/makeLink}&"+Math.random());
		{literal}
		req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		req1.send(str);
}
function serverResponseValidCouponCode(_var) { 

	if(_var.trim() == 'IMG')
		{ 
		//document.getElementById('promo_code_holder').style.display='block';
		//document.getElementById('promo_code_hint').style.display = 'none';
		//document.getElementById('promo_code_error').style.display = 'block';
		/*//document.getElementById('first_name_hint').style.display='block';
		window.setTimeout(function() { 
		document.getElementById('promo_code_error').innerHTML="<img src='{/literal}{$GLOBAL.tpl_url}{literal}/images/checkbullet.gif' border='0'/>";
		}, 500);
		 window.setTimeout(function(){document.getElementById('promo_code_error').style.display='none'; }, 1000);
		 //document.getElementById("myname_main").className = "ctrlHolder";
		 document.getElementById("promo_code_left").className = "form_wrap";
		 document.getElementById("validation_sucess").value = "Y";	*/	 	
		 
	}
	
	else if(_var.trim() == "NONE")
	{	
		 document.getElementById('promo_code_holder').style.display='none';
		document.getElementById('promo_code_hint').style.display = 'none';
		document.getElementById('promo_code_error').style.display = 'none';
		 document.getElementById("promo_code_left").className = "form_wrap";
		 document.getElementById("promo_code_sucess").value = "Y";
	}
	else
	{	
		document.getElementById('promo_code_error').innerHTML=_var;
		document.getElementById('promo_code_holder').style.display='block';
		document.getElementById('promo_code_hint').style.display = 'none';
		document.getElementById('promo_code_error').style.display = 'block';
		document.getElementById("promo_code_left").className = "form_wrap_error";
		document.getElementById("validation_sucess").value = "N";
	}

}


 function showLinkValid(val)
 {
 	if(val!=''){
	document.getElementById('valid_coupon_link').style.display='block';
	}
	else
	document.getElementById('valid_coupon_link').style.display='none';
 }	
	
	

	
	
var defPreload		=   '<div style=\"width:40px; height:32px; float:left;\"><img alt=\"\" src=\"{/literal}{$GLOBAL.tpl_url}{literal}/images/loading1.gif\" width=\"32\" height=\"32\"></div><div style=\"float:left; margin-top:10px;\" class=\"messageblockui\"  align=\"left\">Loading....ll Please wait</div>';

<!--var defPreload		=   '<div style=\"width:400px; height:192px; float:left;\"><img alt=\"\" src=\"{/literal}{$GLOBAL.tpl_url}{literal}/images/loading06.gif\" width=\"400\" height=\"192\"></div>';-->
<!-- $(document).ready(function(){
	//$.blockUI.defaults.message = defPreload; 
//});-->
 </script>
  
  
{/literal}




  
					