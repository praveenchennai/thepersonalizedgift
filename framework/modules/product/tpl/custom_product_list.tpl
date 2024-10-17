<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>

<link type="text/css" href="{$smarty.const.SITE_URL}/templates/default/css/pop-up.css" rel="stylesheet" />	

{literal}
	<script language="javascript">
	function newSelect(accessory_id)
		{
		var elm= eval("document.getElementById('accessory_"+accessory_id+"')");
		var e= document.getElementsByName('accessory[]');
		for(i=0;i<e.length;i++)
				{
				if(e[i].id=='accessory_'+accessory_id)
					{
					if(elm.checked==true)
						e[i].checked=true;
					else
						e[i].checked=false;
					}
				//alert(e[i].id+'->'+e[i].checked);
				}
		//alert(e[0].id);
		}
function popUp1(id) {
	//alert(url);
	{/literal}
	window.open('{makeLink mod=product pg=Popaccessory}act=edit{/makeLink}&id='+id, "name_of_window", "width=380,height=180,left=100,top=200,scrollbars=yes,menubar=no, resizable=no,location=no,toolbar=no");
	{literal}
}
function popUpColor(id) {
	//alert(url);
	{/literal}
	window.open('{makeLink mod=product pg=Popaccessory}act=editColor{/makeLink}&id='+id, "name_of_window", "width=380,height=180,left=100,top=200,scrollbars=yes,menubar=no, resizable=no,location=no,toolbar=no");
	{literal}
}

	
		
		function serverCall(grpid, from) {
		var req = newXMLHttpRequest();
		document.getElementById("div_append").style.display='inline';
		if(from=='remove' || from=='remove_all')
			document.getElementById("div_append").style.display='none';
		req.onreadystatechange = getReadyStateHandler(req, serverResponse);
		if(from=='accessory_group' || from=='clear_All' || from=='remove_all')
			{
			//document.getElementById("group_id").selectedIndex=0;
			//document.getElementById("div_append").style.display='none';
			for (var i=0;i<document.frm_product.elements.length;i++)
				{
				var e = document.frm_product.elements[i];
				//alert(e.name);
				if(e.name!='active' && e.name!='is_percentage' && e.name!='select_all' && e.name!='product_id[]' )
					{e.checked=false;}
				
				}
			}
		{/literal}
		//alert(product_id);
		//alert("{makeLink mod=product pg=ajaxList}{/makeLink}&grpid="+grpid+"&from="+from);
		req.open("GET", "{makeLink mod=product pg=ajaxList}{/makeLink}&grpid="+grpid+"&from="+from);
		{literal}
		req.send(null);
	return false;
	}
	function serverResponse(_var) {
	_var = _var.split('|');
	eval(_var[0]);
		
		}
function ViewGroup(grpid, category_id,product_id,store) 
	{
	//alert(grpid);
	////alert(category_id);
	//alert(product_id);
	var req = newXMLHttpRequest();
	req.onreadystatechange = getReadyStateHandler(req, display_accessory);
	{/literal}
	req.open("GET", "{makeLink mod=product pg=ajax_accessory}{/makeLink}&store="+store+"&grpid="+grpid+"&category_id="+category_id+"&product_id="+product_id);
	{literal}
	req.send(null);
	}
	function display_accessory(_var) {
	//alert(_var);
		_var = _var.split('|');
		var e= eval("document.getElementById('grp_All_"+_var[0]+"')");
		e.innerHTML=_var[1];
		}
function popUp2(grp_id,prd_id) {
	
	
{/literal}
windowReference=window.open('{makeLink mod=product pg=PopnewCategory}act=category{/makeLink}&grp_id='+grp_id+'&prd_id='+prd_id, "name_of_window", "width=500,height=300,left=100,top=200,scrollbars=yes,menubar=no, resizable=no,location=no,toolbar=no");
if (!windowReference.opener)
windowReference.opener = self;
{literal}
}

function popUpexclude() {
{/literal}
windowReference=window.open('{makeLink mod=accessory pg=accessory}act=settingsAddPop&sId=Accessory Exclude{/makeLink}&fId={$smarty.request.fId}', "name_of_window", "width=550,height=600,left=100,top=50,scrollbars=yes,menubar=no, resizable=no,location=no,toolbar=no");
if (!windowReference.opener)
windowReference.opener = self;
{literal}
}


function popUp3(grp_id,cat_id,prd_id) {
	//alert(url);
{/literal}
windowReference=window.open('{makeLink mod=product pg=PopnewCategory}act=accessory{/makeLink}&cat_id='+cat_id+'&grp_id='+grp_id+'&prd_id='+prd_id, "name_of_window", "width=500,height=300,left=100,top=200,scrollbars=yes,menubar=no, resizable=no,location=no,toolbar=no");
if (!windowReference.opener)
windowReference.opener = self;
{literal}
}
		</script>
{/literal}

<!-- ----------assign product to store (jscript copied from product form.tpl)----------  -->
<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>
{literal}
<script language="javascript" type="text/javascript">

function ViewGroup1(grpid, category_id,product_id,store) {
	var req = newXMLHttpRequest();
	req.onreadystatechange = getReadyStateHandler(req, display_accessory1);
	{/literal}
	//alert(store);
	{if $smarty.request.manage=='manage'}
	req.open("GET", "{makeLink mod=store pg=product_store_ajax_accessory}{/makeLink}&store="+store+"&grpid="+grpid+"&category_id="+category_id+"&product_id="+product_id);
	{else}
	req.open("GET", "{makeLink mod=product pg=store_ajax_accessory}{/makeLink}&store="+store+"&grpid="+grpid+"&category_id="+category_id+"&product_id="+product_id);
	{/if}
	{literal}
	req.send(null);
}

function display_accessory1(_var) {
	_var = _var.split('|');
	var e= eval("document.getElementById('grp_"+_var[2]+"_"+_var[0]+"')");
	e.innerHTML=_var[1];
}



</script>
{/literal}
{literal}
	<script language="javascript">
	function newSelect(accessory_id)
		{
		var elm= eval("document.getElementById('accessory_"+accessory_id+"')");
		var e= document.getElementsByName('accessory[]');
		for(i=0;i<e.length;i++)
				{
				if(e[i].id=='accessory_'+accessory_id)
					{
					if(elm.checked==true)
						e[i].checked=true;
					else
						e[i].checked=false;
					}
				//alert(e[i].id+'->'+e[i].checked);
				}
		//alert(e[0].id);
		}
	function selectCheck(category_id,accessory)
		{
		if(accessory.length>0)
			{
			var e= eval("document.getElementById('sel_all_"+category_id+"')");
			var accessoryIDS=accessory.split(",");
			for( var sIndex in accessoryIDS )
				{
					var accessory_id = accessoryIDS[sIndex];
					var elm= eval("document.getElementById('accessory_"+category_id+"_"+accessory_id+"')");
					if(e.checked==true)
						elm.checked=true;
					else
						elm.checked=false;
				newSelect(elm.value);
				}
			}
			else
			{
			return false;
			}
		}
	function serverCall(grpid, from, product_id) {
	var req = newXMLHttpRequest();
	document.getElementById("div_append").style.display='inline';
	if(from=='remove' || from=='remove_all')
			document.getElementById("div_append").style.display='none';
	req.onreadystatechange = getReadyStateHandler(req, serverResponse);
	if(from=='accessory_group' || from=='clear_All' || from=='remove_all')
		{
		//document.getElementById("group_id").selectedIndex=0;
		for (var i=0;i<document.admFrm.elements.length;i++)
			{
			var e = document.admFrm.elements[i];
			//alert(e.name);
			if(e.name!='personalise_with_monogram' && e.name!='active' && e.name!='is_percentage')
				{e.checked=false;}
			
			}
		}
	{/literal}
	//alert(product_id);
	req.open("GET", "{makeLink mod=product pg=ajax}{/makeLink}&grpid="+grpid+"&from="+from+"&product_id="+product_id);
	{literal}
	req.send(null);
	
	}
	function serverResponse(_var) {
	_var = _var.split('|');
	eval(_var[0]);
		}
	function showdiv(divname)
		{
		//alert (divname);
		var e= eval("document.getElementById('cat_"+divname+"')");
		//alert(e.id)
		//e.style.visibility=visible;
		//alert(e.style.display);
		if(e.style.display=='inline')
			e.style.display='none';
		else
			e.style.display='inline'
		//eval("document.getElementById('cat_"+divname+"').style.display=inline")
		//document.getElementById(divname).style.visibility=visible; 
		//document.getElementById(divname).style.display="inline";
		
		}
	function calculate_price(txt_name,div_name,check_percent)
		{
		//variable declartions
		var base_price;
		var new_amount;
		base_price	=	document.getElementById("base_price").value;
		new_amount	=	document.getElementById(txt_name).value
		
		//document.getElementById(div_name).innerHTML = document.getElementById(txt_name).value;
		if(base_price)
			{
			if(IsNumeric(base_price))
				{
						if(IsNumeric(new_amount))
							{
							document.getElementById(div_name).innerHTML=new_amount;
							//alert(document.getElementById(check_percent).value)
								if(document.getElementById(check_percent).checked==true)
									{
									//alert(document.getElementById(check_percent).value)
									//display in amount
									document.getElementById(div_name).innerHTML='[ $' + Math.round(((base_price*new_amount)/100)*100)/100 + ' ]';
									}
								else
									{
									//display in percentage
									document.getElementById(div_name).innerHTML='[ ' + Math.round(((new_amount*100)/base_price)*100)/100 + '% ]';
									}
							}//f(IsNumeric(document.getElementById(txt_name).value))
						else
							{
							document.getElementById(div_name).innerHTML="---Enter Valid Price---";
							}//f(IsNumeric(document.getElementById(txt_name).value))
						}//if(IsNumeric(base_price))
					else
						{
						document.getElementById(div_name).innerHTML="---Enter Valid Base Price---";
						}//if(IsNumeric(base_price))
				}//if(base_price)
			else
				{
				document.getElementById(div_name).innerHTML="---Enter Base Price---";
				}//if(base_price)
		}
function ViewGroup(grpid, category_id,product_id,store) 
	{
	//alert(grpid);
	////alert(category_id);
	//alert(product_id);
	var req = newXMLHttpRequest();
	req.onreadystatechange = getReadyStateHandler(req, display_accessory);
	{/literal}
	{if $smarty.request.manage=='manage'}
	req.open("GET", "{makeLink mod=store pg=product_ajax_accessory}{/makeLink}&store="+store+"&grpid="+grpid+"&category_id="+category_id+"&product_id="+product_id);
	{else}
	req.open("GET", "{makeLink mod=product pg=ajax_accessory}{/makeLink}&store="+store+"&grpid="+grpid+"&category_id="+category_id+"&product_id="+product_id);
	{/if}
	{literal}
	req.send(null);
	}
	function display_accessory(_var) {
	//alert(_var);
		_var = _var.split('|');
		var e= eval("document.getElementById('grp_All_"+_var[0]+"')");
		e.innerHTML=_var[1];
		}
function ViewBottomGroup(store_id) 
	{
	var elm= eval("document.getElementById('store_"+store_id+"')");
	if(elm.style.display == "block") {
		elm.style.display = "none";
		}
	else {
		elm.style.display = "block";
		}
	//alert(store_id);
	//alert(category_id);
	//alert(product_id);
	var req = newXMLHttpRequest();
	req.onreadystatechange = getReadyStateHandler(req, display_store);
	{/literal}
	//alert(store_id);
	{if $smarty.request.manage=='manage'}
	req.open("GET", "{makeLink mod=store pg=product_ajax_store}{/makeLink}&store_id="+store_id+"&product_id=0");
	{else}
	req.open("GET", "{makeLink mod=product pg=ajax_store}{/makeLink}&store_id="+store_id+"&product_id=0");
	{/if}
	{literal}
	req.send(null);
	}	
function display_store(_var) {
//alert(_var);
	_var = _var.split('|');
	var e= eval("document.getElementById('store_"+_var[0]+"')");
	e.innerHTML=_var[1];
	}	
	</script>
<script language="javascript">
////functions for cross sell and product to store..............
function chkval11()
{
	var rit=document.forms[0].products_related.options;
	for (var i=0;i<rit.length;i++)
	{
		if (i>0)
		{
			document.forms[0].hf1.value=document.forms[0].hf1.value + "," + rit[i].value;
		}
		else
		{
			document.forms[0].hf1.value=rit[i].value;
		}		
	}
}
function addSrcToDestList(id) 
	{	
		if(id==1)
		{
		destList	= window.document.forms[0].products_related;
		srcList 	= window.document.forms[0].products_all; 
		}
	
	var len = destList.length;
	for(var i = 0; i < srcList.length; i++) 
	{
	if ((srcList.options[i] != null) && (srcList.options[i].selected)) {
	
	var found = false;
	for(var count = 0; count < len; count++) 
	{
		if (destList.options[count] != null) 
		{
			if (srcList.options[i].text == destList.options[count].text) 
			{
			found = true;
			break;				
			}
		}
	}
	
	if (found != true) 
	{
	destList.options[len] = new Option(srcList.options[i].text,srcList.options[i].value); 
	len++;
	
	
				 }
		  }
	   }
	  return false;
	}
		
	function deleteFromDestList(id) 
	{	
		if(id==1)
		{
		var destList  = window.document.forms[0].products_related;
		}
	var len = destList.options.length;
	for(var i = (len-1); i >= 0; i--) {
	if ((destList.options[i] != null) && (destList.options[i].selected == true)) {
	destList.options[i] = null;
		  }
	   }
	     return false;
	}

/* from store */
function _interchange(frm, _all, flag) {
	if(flag == 'add') {
		var _from = document.getElementById("all_categories");
		
	} else {
		var _to   = document.getElementById("all_categories");
		
	}
	if(_all) {
		for(var i = 0;i < _from.length;i++){
			_from.options[i].selected = true;
		}
	}
	for(var i = 0;i < _from.length;i++){
		if(_from.options[i].selected == true) {
			_to.options[_to.length] = new Option(_from.options[i].text, _from.options[i].value);
			_from.options[i] = null;
			i--;
		}
	}
}
function _submit(frm) {
	cat = document.getElementById("store_categories");
	for(var i=0; i<cat.options.length; i++) {
		cat.options[i].selected = true;
	}
	return true;
}




	function chkval()
  {
 //document.admFrm.new_html_desc.value=areaedit_editors.html_desc.getHTML();
	//var rit=document.forms[0].products_related.options;
	/*var rit1=document.forms[0].store_related.options;
	for (var i=0;i<rit1.length;i++)
	{
		if (i>0)
		{
			document.forms[0].hf2.value=document.forms[0].hf2.value + "," + rit1[i].value;
		}
		else
		{
			document.forms[0].hf2.value=rit1[i].value;
		}		
	}*/
	/// checking wether fields are selected 20-07-07
	
	

	var chk_flag = 0;
	if(document.frm_product.mass_field1.checked)
	{var chk_flag = 1;  } 
	if(document.frm_product.mass_field2.checked)
	{var chk_flag = 1;  } 
	if(document.frm_product.mass_field3.checked)
	{var chk_flag = 1;  } 
	if(document.frm_product.mass_field4.checked)
	{var chk_flag = 1;  } 
	if(document.frm_product.mass_field5.checked)
	{var chk_flag = 1;  } 
	if(document.frm_product.mass_field6.checked)
	{var chk_flag = 1;  } 
	if(document.frm_product.mass_field7.checked)
	{var chk_flag = 1;  } 
	if(document.frm_product.mass_field8.checked)
	{var chk_flag = 1;  } 
	if(document.frm_product.mass_field9.checked)
	{var chk_flag = 1;  } 
	if(document.frm_product.mass_field10.checked)
	{var chk_flag = 1;  } 
	if(document.frm_product.mass_field11.checked)
	{var chk_flag = 1;  } 
	if(document.frm_product.mass_field13.checked)
	{var chk_flag = 1;  } 
	if(document.frm_product.mass_field20.checked)
	{var chk_flag = 1;  } 
	if(document.frm_product.mass_field21.checked)
	{var chk_flag = 1;  } 
	if(document.frm_product.mass_field22.checked)
	{var chk_flag = 1;  } 
		
	
	if(document.frm_product.mass_field14) {
	if(document.frm_product.mass_field14.checked)
	{var chk_flag = 1;  } 
	}
	
	if(document.frm_product.mass_field15) {
	if(document.frm_product.mass_field15.checked)
	{var chk_flag = 1;  } 
	}
	
	if(document.frm_product.mass_field121) {
	if(document.frm_product.mass_field121.checked)
	{var chk_flag = 1;  } 
	}
	
	
    i=2;
	dyn_mass_fileld	= 'mass_field12' + i;
	
	while ( document.getElementById(dyn_mass_fileld) )	{
	   if(document.getElementById(dyn_mass_fileld).checked)
	   {var chk_flag = 1;  } 
	   i++;
	   dyn_mass_fileld	= 'mass_field12' + i;
	}
	
	
		
	if(chk_flag == 0 )
	{
		 alert("Please select atleast one field for massupdate");
		 return false; 
	} 
	else
	{
		return true;
	}
	
	
	
}


function fnChange1(){
	if(document.frm_product.Remove.checked){
	
	  document.frm_product.Remove.checked = true;
	  document.frm_product.Append_product_store.checked= false;
	}
}

function fnChange2(){
	if(document.frm_product.Append_product_store.checked){
	
	  document.frm_product.Remove.checked = false;
	  document.frm_product.Append_product_store.checked= true;
	}
}




function deleteSelected()
{
	var count1=0;

		for (var i=0;i<frm_product.elements.length;i++)

			{

			var e = frm_product.elements[i];

					

					if(e.name=='product_id[]')

					{

						if(e.checked==true)

						{

						count1++;

						}

					}

				

			

			}

		if(count1==0){

		alert("Please select one custom product(s)");

		return false;

		}

	if(confirm('Are you sure to delete selected custom product(s)?'))

	{
		document.frm_product.action='{/literal}{makeLink mod=$MOD pg=$PG}act=del_custom_product&fId={$smarty.request.fId}&sId={$smarty.request.sId}&category_id={$smarty.request.category_id}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}&limit={$smarty.request.limit}{/makeLink}{literal}'; 
		document.frm_product.submit();

	}

}









</script>
{/literal}
<script language="javascript">
{literal}
function popUp1(id,prd_id) {
	//alert(url);
{/literal}
//alert(id+'Hello'+prd_id);
window.open('{makeLink mod=product pg=Popaccessory}act=edit{/makeLink}&id='+id+'&prd_id='+prd_id, "name_of_window", "width=540,height=180,left=100,top=200,scrollbars=yes,menubar=no, resizable=no,location=no,toolbar=no");
{literal}
}
function popUp2(grp_id,prd_id) {
{/literal}
windowReference=window.open('{makeLink mod=product pg=PopnewCategory}act=category{/makeLink}&grp_id='+grp_id+'&prd_id='+prd_id, "name_of_window", "width=500,height=300,left=100,top=200,scrollbars=yes,menubar=no, resizable=no,location=no,toolbar=no");
if (!windowReference.opener)
windowReference.opener = self;
{literal}
}
function popUp3(grp_id,cat_id,prd_id) {
{/literal}
windowReference=window.open('{makeLink mod=product pg=PopnewCategory}act=accessory{/makeLink}&cat_id='+cat_id+'&grp_id='+grp_id+'&prd_id='+prd_id, "name_of_window", "width=500,height=300,left=100,top=200,scrollbars=yes,menubar=no, resizable=no,location=no,toolbar=no");
if (!windowReference.opener)
windowReference.opener = self;
{literal}
}
function popUp4(store_id,product_id) {
{/literal}
windowReference=window.open('{makeLink mod=product pg=pop_access_store}act=form{/makeLink}&store_id='+store_id+'&product_id='+product_id, "name_of_window", "width=700,height=600,left=150,top=100,scrollbars=yes,menubar=no,resizable=yes,location=no,toolbar=no");
if (!windowReference.opener)
windowReference.opener = self;
{literal}
}
function display(theSelect){
		var timesChoice= theSelect;		
		
		switch(timesChoice) {
			case "fixed" :
				document.getElementById('hidetimes').style.display="inline";
			break;
			case "one" :
				document.getElementById('hidetimes').style.display="none";
			break;
			case "unlimit" :
				document.getElementById('hidetimes').style.display="none";
			break;
			case "giftcertificate" :
			if(document.getElementById('is_giftcertificate').checked==true)
					document.getElementById('giftcertificate').style.display="inline";
				else
					document.getElementById('giftcertificate').style.display="none";
			break;
		}	
}

function fnClick(){
	document.frm_product.action=('{/literal}{makeLink mod=product pg=index}act=form&sId={$smarty.request.sId}&category_id={$smarty.request.category_id}&fId={$smarty.request.fId}&limit={$smarty.request.limit}&product_search={$smarty.request.product_search}&flag=1&pageNo={$smarty.request.pageNo}{/makeLink}{literal}');
	//document.frm_product.check.value="yes";
	document.frm_product.submit();
	}
	
var ns=(document.layers);
var ie=(document.all);
var w3=(document.getElementById && !ie);
var calunit=ns? "" : "px";
	

function htmlpopup(id)
{
	if(!ns && !ie && !w3) return;
	if(ie)		adDiv=eval('document.all.'+id+'.style');
	else if(ns)	adDiv=eval('document.layers["'+id+'"]');
	else if(w3)	adDiv=eval('document.getElementById("'+id+'").style');
	
        if (ie||w3){
        adDiv.visibility="visible";
		
		adDiv.display="block";
		document.getElementById(id).style.display="block";
		
        }else{
        adDiv.visibility ="show";}
}

function closeAd_new(id){
if (ie||w3){
	//adDiv.display="none";
	document.getElementById(id).style.display="none";
}else{
	document.getElementById(id).style.visibility ="hide";}
}

{/literal}
</script>
<!-- ----------assign product to store (jscript copied from product form.tpl)----------  -->
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0"> 
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_product" method="post" action="" enctype="multipart/form-data" style="margin: 0px;" >
<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
	<input type="hidden" name="check" value="no">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">{$smarty.request.sId} &nbsp;&nbsp;<a href="javascript:void(0);" onclick="htmlpopup('html_popup');" ><img src="{$smarty.const.SITE_URL}/templates/blue/images/icon_small_info.gif" title="" border="0" ></a> <input type="hidden" name="limit"  value="{$smarty.request.limit}"/>
		  
		  		<div class="shadow01" style="display:none;" id="html_popup" >
		<div class="container">
		<div class="close_bar">
			<a href="javascript:void(0);" onClick="closeAd_new('html_popup');"><img src="{$smarty.const.SITE_URL}/templates/default/images/close_new.png" width="80" height="29" border="0" class="fr" /></a>		</div>
		<div class="image_holder" id="htmlLoader"  style="overflow:auto">
		
		<table width="100%" border="0" cellpadding="2" cellspacing="2">
  <tr>
    <td><b>Other Gifts</b></td>
  </tr>
  <tr>
    <td> 
The "Other Gifts" feature provides you with the ability to sell any other items in your web-store. Yes, you can sell other gifts and other items too.</td>
  </tr>
 
  
  
    <tr>
    <td><b>Other Gifts Details:</b></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellpadding="2" cellspacing="2">
  <tr>
    <td width="2%" valign="top">1.</td>
	 <td width="98%">You can specify the Product Title, Description, Price, Sale Price, Shipping Price and upload a Photo of your item.  </td>
  </tr>
  <tr>
    <td width="2%" valign="top">2.</td>
	 <td width="98%"> Other Gifts will be displayed in the "Other Gifts" category under the Special Occasions section of your webstore. </td>
  </tr>
  <tr>
    <td width="2%" valign="top">3.</td>
	 <td width="98%">Other Gifts can be displayed in multiple Special Occasion categories. </td>
  </tr>
  <tr>
    <td width="2%" valign="top">4.</td>
	 <td width="98%">Other Gifts can be displayed as a Featured Gift on your home page via the "Featured Gifts Display Order".   </td>
  </tr>
   
 
</table>
</td>
  </tr>
  
  
  
  
    <tr>
    <td><br/><b> Creating Other Gifts:</b> </td>
  </tr>
  <tr>
    <td> <table width="100%" border="0" cellpadding="2" cellspacing="2">
  <tr>
    <td width="2%" valign="top">1.</td>
	 <td width="98%">Click on the "Add New Other Gifts" link shown in the upper right</td>
  </tr>
  <tr>
    <td width="2%" valign="top">2.</td>
	 <td width="98%">Gift Category: The "Other Gifts" category is required but you can display your gift in multiple categories by holding down the shift key, and clicking on the desired categories.
</td>
  </tr>
  
  <tr>
    <td width="2%" valign="top">3.</td>
	 <td width="98%">Product Title: Enter a unique product title. Example: "Bible Verse: Three Crosses." Required.
</td>
  </tr>
  
  <tr>
    <td width="2%" valign="top">4.</td>
	 <td width="98%">Description: Enter the product description and optional instructions for your customer as needed. Required.
</td>
  </tr><tr>
    <td width="2%" valign="top">5.</td>
	 <td width="98%">Image: Click on the Browse button to upload a .JPG image/photo of your product. Required.
</td>
  </tr><tr>
    <td width="2%" valign="top">6.</td>
	 <td width="98%">Base Price: Enter the retail price for your gift. Required.
</td>
  </tr><tr>
    <td width="2%" valign="top">7.</td>
	 <td width="98%">Sale Price: Enter a sale price for your gift. Optional. 
</td>
  </tr><tr>
    <td width="2%" valign="top">8.</td>
	 <td width="98%">Active: Activate to display or Inactivate to halt display in your store. 
</td>
  </tr><tr>
    <td width="2%" valign="top">9.</td>
	 <td width="98%">Custom Fields: Custom Fields are optional but will allow you to accept input from your customer. Select the Field Type, enter the Field Name (e.g. "First Name") And press the Create button. Optionally check the Mandatory box if the input field is required.
</td>
  </tr><tr>
    <td width="2%" valign="top">10.</td>
	 <td width="98%">Meta Details: Enter a Title, Description and Keyword(s) to help the search engines learn more about your gift. Optional.
</td>
  </tr><tr>
    <td width="2%" valign="top">11.</td>
	 <td width="98%">Shipping Price Details - Domestic: Enter the Shipping Price for the First Gift and each Additional Gift. Required. 
</td>
  </tr><tr>
    <td width="2%" valign="top">12.</td>
	 <td width="98%">Shipping Price Details - International: Enter the Shipping Price for the First Gift and each Additional Gift. Required. Even if you do not offer international shipping at this time, these fields are required. You can determine whether or not you accept International orders by your Webstore Settings Menu under Shipping Details. 
</td>
  </tr><tr>
    <td width="2%" valign="top">13.</td>
	 <td width="98%">Submit: Be sure to enter all required fields and upload your product image BEFORE pressing the Submit button. Press the Submit button to save your gift.
</td>
  </tr>
  
  
</table></td>
  </tr>
 
    <tr>
    <td><br/><b>Important Notes:</b> </td>
  </tr>
  <tr>
    <td> <table width="100%" border="0" cellpadding="2" cellspacing="2">
  <tr>
    <td width="2%" valign="top">1.</td>
	 <td width="98%">Images depicting Personal Touch art and intellectual property must include a large "copyright" watermark.  </td>
  </tr>
  <tr>
    <td width="2%" valign="top">2.</td>
	 <td width="98%">Other Gifts are considered unique with regard to shipping costs and are based solely upon your entries on the Other Gifts detail page. 
</td>
  </tr>
    <tr>
    <td width="2%" valign="top">3.</td>
	 <td width="98%"> Personal Touch Products, Inc. reserves the right to remove any items that it deems to be inappropriate at any time and for any reason. 
</td>
  </tr>
  
</table></td>
  </tr>
  
  
</table>


		</div>
		</div>
		</div>
		  
		  
		  </td> 
          <td nowrap align="right" class="titleLink">&nbsp;</td> 
        </tr>
        <tr>
           <!-- <td nowrap>{$CATEGORY_PATH}</td>-->
          <td nowrap align="right" class="titleLink">
		  
			
		 {if $smarty.request.storename ne ''}	
		  <a href="{makeLink mod=$MOD pg=$PG}act=add_custom_product&sId={$smarty.request.sId}&mId={$MID}&fId={$smarty.request.fId}&category_id={$smarty.request.category_id}&limit={$smarty.request.limit}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}{/makeLink}">Add New {$smarty.request.sId} </a>
		   {/if}
		  </td>
        </tr> 
      </table></td> 
  </tr>


  <tr>
   <td valign=top class="naGrid1"><table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td>

		{if count($PRODUCT_LIST) > 0}
		<a class="linkOneActive" href="#"  onclick="javascript:deleteSelected();" >Delete</a>
		{/if}
		
	

	</td>
	  {if $FIELDS.2==Y}
	 <td width="100%">&nbsp;</td>
	  <td width="100%">Brand :</td>
	
	    <td>
	 	 <select name=brandid onchange="window.location.href='{makeLink mod=$MOD pg=$PG}act=list{/makeLink}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&category_id={$smarty.request.category_id}&product_search={$smarty.request.product_search}&zoneid={$smarty.request.zoneid}&limit='+document.frm_product.limit.value+'&brandid='+this.value" style="width:130">
     	   <option value="">{$SELECT_DEFAULT1}</option>
      	  {html_options values=$BRAND.brand_id output=$BRAND.brand_name selected=`$smarty.request.brandid`}
		 </select>
		 </td>
		 {else}
		 <td colspan="2"></td>
		 {/if}
		 {if $FIELDS.3==Y}
		  <td width="100%">Made In :</td>
		 
	  <td>
	 	 <select name=zoneid onchange="window.location.href='{makeLink mod=$MOD pg=$PG}act=list{/makeLink}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&brandid={$smarty.request.brandid}&product_search={$smarty.request.product_search}&category_id={$smarty.request.category_id} &limit='+document.frm_product.limit.value+'&zoneid='+this.value" style="width:140">
     	   <option value="">{$SELECT_DEFAULT2}</option>
      	   {html_options values=$MADE.id output=$MADE.name selected=`$smarty.request.zoneid`}

		 </select>
		 </td>
		 {else}
		 <td colspan="2"></td>
		 {/if}
	<td align="center" width="80">Category&nbsp;:</td>
	  <td>
	 	 <select name=category_id onchange="window.location.href='{makeLink mod=$MOD pg=$PG}act=custom_product_list{/makeLink}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&brandid={$smarty.request.brandid}&product_search={$smarty.request.product_search}&zoneid={$smarty.request.zoneid}&limit='+document.frm_product.limit.value+'&category_id='+this.value">
     	   <option value="">{$SELECT_DEFAULT}</option>
      	  {html_options values=$CATEGORY_PARENT.category_id output=$CATEGORY_PARENT.category_name selected=`$smarty.request.category_id`}
				  	<option value="306"{if $smarty.request.category_id eq 306} selected="selected"{/if}>Other Gifts</option>
	
		 </select>
		 </td>
		 
    <td><input type="text" name="product_search" value="{$PRODUCT_SEARCH_TAG}" size="15"></td>
	<td><input name="btn_search" type="submit" class="naBtn" value="Search"></td>
	 <td>&nbsp;</td>
    <td nowrap>Results per page:</td>
	<td>{$PRODUCT_LIMIT}</td>
	
  </tr>
</table></td>
    </tr> 
	
  <tr> 
    <td>
	<table width=100% border=0 cellpadding="2" cellspacing="0">
	{if count($PRODUCT_LIST) > 0}
	<tr><td>
	<table width=100% border=0 cellpadding="2" cellspacing="0"> 
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_product,'product_id[]')"></td>
		  <td width="28%" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=name display="Product Title"}act=list&subact=list&category_id={$smarty.request.category_id}&zoneid={$smarty.request.zoneid}&brandid={$smarty.request.brandid}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}&limit={$smarty.request.limit}& {/makeLink}</td> 
          {if ($PRODUCTCODE=='Y')}<td width="13%" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=name display="Product Code"}act=list&subact=list&category_id={$smarty.request.category_id}&zoneid={$smarty.request.zoneid}&brandid={$smarty.request.brandid}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}&limit={$smarty.request.limit}& {/makeLink}</td>{/if}
		  {if $FIELDS.38==Y}
		  <td width="18%" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=cart_name display="Cart Name"}act=list&subact=list&category_id={$smarty.request.category_id}&zoneid={$smarty.request.zoneid}&brandid={$smarty.request.brandid}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}&limit={$smarty.request.limit}& {/makeLink}</td> 
          {/if}	
			{if $FIELDS.2==Y}
		  <td width="10%" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=brand_name display="Brand"}act=list&subact=list&category_id={$smarty.request.category_id}&zoneid={$smarty.request.zoneid}&brandid={$smarty.request.brandid}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}&limit={$smarty.request.limit}& {/makeLink}</td>
         	{/if}
		  {if $FIELDS.3==Y}
		  <td width="10%" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=Made display="Made In"}act=list&subact=list&category_id={$smarty.request.category_id}&zoneid={$smarty.request.zoneid}&brandid={$smarty.request.brandid}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}&limit={$smarty.request.limit}& {/makeLink}</td>
		  {/if}
		  {if $FIELDS.47==Y}
<!--		  <td width="10%" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=quantity display="quantity"}act=list&subact=list&category_id={$smarty.request.category_id}&zoneid={$smarty.request.zoneid}&brandid={$smarty.request.brandid}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}&limit={$smarty.request.limit}& {/makeLink}</td>
-->		  {/if}
		  {if $FIELDS.22==Y}
		  <td width="10%" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=weight display="Weight"}act=list&subact=list&category_id={$smarty.request.category_id}&zoneid={$smarty.request.zoneid}&brandid={$smarty.request.brandid}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}&limit={$smarty.request.limit}& {/makeLink}</td>
          {/if}
		  <td width="10%" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=price display="Sale Price"}act=list&subact=list&category_id={$smarty.request.category_id}&zoneid={$smarty.request.zoneid}&brandid={$smarty.request.brandid}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}&limit={$smarty.request.limit}& {/makeLink}</td>
		  <td width="10%" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=price display="Base Price"}act=list&subact=list&category_id={$smarty.request.category_id}&zoneid={$smarty.request.zoneid}&brandid={$smarty.request.brandid}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}&limit={$smarty.request.limit}& {/makeLink}</td>
		  <td width="10%" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=active display="Active"}act=list&subact=list&category_id={$smarty.request.category_id}&zoneid={$smarty.request.zoneid}&brandid={$smarty.request.brandid}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}&limit={$smarty.request.limit}&{/makeLink}</td>
		</tr>
		
        {foreach from=$PRODUCT_LIST item=product}
        <tr class="{cycle values="naGrid1,naGrid2"}">
          <td valign="middle" align="center">{if $STORE_PERMISSION.delete == 'Y' or $product->owner_id eq $STORE_ID}<input type="checkbox" name="product_id[]" id="product_id[]" value="{$product->id}">{/if}</td> 
		  <td align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act=add_custom_product&id={$product->id}&category_id={$smarty.request.category_id}&brandid={$smarty.request.brandid}&zoneid={$smarty.request.zoneid}&product_search={$smarty.request.product_search}&limit={$smarty.request.limit}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&orderBy={$ORDERBY}&pageNo={$smarty.request.pageNo}{/makeLink}">{$product->name}</a></td> 
          {if ($PRODUCTCODE=='Y')}<td align="left" valign="middle">{$product->product_id}</td>{/if}
		  {if $FIELDS.38==Y}
		 <td align="left" valign="middle">{$product->cart_name}</td>
		  {/if}
		  
		  {if $FIELDS.2==Y}
		  <td align="left" valign="middle">{if $product->brand_id > 0}{$product->brand_name}{else}No Brand{/if}</td>
          {/if}
		  {if $FIELDS.3==Y}
		  <td align="left" valign="middle">{if $product->Made}{$product->Made}{else}No Made{/if}</td>
		  {/if}
<!--		  <td align="center" valign="middle">{$product->display_order}</td>
-->		  {if $FIELDS.47==Y}
		  <td align="left" valign="middle">{$product->quantity}</td>
		  {/if}
		  {if $FIELDS.22==Y}
		  <td align="left" valign="middle">{$product->weight}&nbsp;lbs</td>
          {/if}
		  
		   <td align="left" valign="middle"> {if $product->discount_price neq $product->price}$&nbsp;{$product->discount_price}{/if}</td>
		   
		  <td align="left" valign="middle">$&nbsp;{$product->price}</td>
		
		  <td align="left" valign="middle">{if $product->active.gif eq 'Y' } 
			<img border="0" title="Activate"  src="{$smarty.const.SITE_URL}/framework/includes/images/active{$product->active.gif}.gif"/>
			<a href="{makeLink mod=$MOD pg=$PG}act=active_custom&id={$product->id}&stat={$product->active}&category_id={$smarty.request.category_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}&aid={$smarty.request.aid}{/makeLink}&product_search={$smarty.request.product_search}&limit={$smarty.request.limit}&pageNo={$smarty.request.pageNo}"><img border="0" title="Deactivate"  src="{$smarty.const.SITE_URL}/framework/includes/images/deactive{$product->active.gif}.gif"/></a>
		{else}
			<a href="{makeLink mod=$MOD pg=$PG}act=active_custom&id={$product->id}&stat={$product->active}&category_id={$smarty.request.category_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}&aid={$smarty.request.aid}{/makeLink}&product_search={$smarty.request.product_search}&limit={$smarty.request.limit}&pageNo={$smarty.request.pageNo}"><img border="0" title="Activate"  src="{$smarty.const.SITE_URL}/framework/includes/images/active{$product->active.gif}.gif"/></a>
			<img border="0" title="Deactivate"  src="{$smarty.const.SITE_URL}/framework/includes/images/deactive{$product->active.gif}.gif"/>
		{/if}</td>
		  
		
		</tr> 
        {/foreach}
		</table>
	<td></tr>
        <tr> 
          <td class="msg" align="center" height="30">{$PRODUCT_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr>

 <tr><td colspan="6">
 
 
  </td></tr>
  </table>
</form>
