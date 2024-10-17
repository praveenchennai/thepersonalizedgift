<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>
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
          <td nowrap class="naH1">{$smarty.request.sId} <input type="hidden" name="limit"  value="{$smarty.request.limit}"/></td> 
          <td nowrap align="right" class="titleLink">&nbsp;</td> 
        </tr>
        <tr>
          <td nowrap>{$CATEGORY_PATH}</td>
          <td nowrap align="right" class="titleLink">
		  {if $STORE_PERMISSION.add == 'Y'}	
		  <a href="{makeLink mod=$MOD pg=$PG}act=form&sId={$smarty.request.sId}&mId={$MID}&fId={$smarty.request.fId}&category_id={$smarty.request.category_id}&limit={$smarty.request.limit}{/makeLink}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}">Add New {$smarty.request.sId} </a>
		   {/if}
		     {if $smarty.request.storename eq ''}	&nbsp;&nbsp;&nbsp;<a class="linkOneActive" href="#" onclick="fnClick();">Duplicate {$smarty.request.sId}</a>
			
		
		  <a href="{makeLink mod=$MOD pg=$PG}act=store_product_form&sId={$smarty.request.sId}&mId={$MID}&fId={$smarty.request.fId}&category_id={$smarty.request.category_id}&limit={$smarty.request.limit}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}{/makeLink}">Add New {$smarty.request.sId} </a>
		   {/if}
		  </td>
        </tr> 
      </table></td> 
  </tr>

  <tr>
   <td valign=top class="naGrid1"><table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td>
	{if $STORE_PERMISSION.delete == 'Y' }	

		{if count($PRODUCT_LIST) > 0 }<a class="linkOneActive" href="#" onclick="javascript: document.frm_product.action='{makeLink mod=$MOD pg=$PG}act=delete&fId={$smarty.request.fId}&sId={$smarty.request.sId}&category_id={$smarty.request.category_id}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}&limit={$smarty.request.limit}{/makeLink}'; document.frm_product.submit();">Delete</a>{/if}
	
	{elseif  $SHOW_DELETE eq 'Y'}
		<a class="linkOneActive" href="#" onclick="javascript: document.frm_product.action='{makeLink mod=$MOD pg=$PG}act=deletestore_product&fId={$smarty.request.fId}&sId={$smarty.request.sId}&category_id={$smarty.request.category_id}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}&limit={$smarty.request.limit}{/makeLink}'; document.frm_product.submit();">Delete2</a>
	  	
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
	<td align="center">Category&nbsp;:</td>
	  <td>
	 	 <select name=category_id onchange="window.location.href='{makeLink mod=$MOD pg=$PG}act=list{/makeLink}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&brandid={$smarty.request.brandid}&product_search={$smarty.request.product_search}&zoneid={$smarty.request.zoneid}&limit='+document.frm_product.limit.value+'&category_id='+this.value">
     	   <option value="{$smarty.request.category_id}">{$SELECT_DEFAULT}</option>
      	  {html_options values=$CATEGORY_PARENT.category_id output=$CATEGORY_PARENT.category_name selected=`$smarty.request.category_id`}
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
	      <td width="4%" align="center" nowrap class="naGridTitle">{if $STORE_PERMISSION.delete == 'Y'}<input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_product,'product_id[]')">{/if}</td>
          {if $FIELDS.1==Y}
		  <td width="28%" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=name display="Product Name"}act=list&subact=list&category_id={$smarty.request.category_id}&zoneid={$smarty.request.zoneid}&brandid={$smarty.request.brandid}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}&limit={$smarty.request.limit}& {/makeLink}</td> 
          {if ($PRODUCTCODE=='Y')}<td width="13%" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=name display="Product Code"}act=list&subact=list&category_id={$smarty.request.category_id}&zoneid={$smarty.request.zoneid}&brandid={$smarty.request.brandid}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}&limit={$smarty.request.limit}& {/makeLink}</td>{/if}
          {/if}	
		  {if $FIELDS.38==Y}
		  <td width="18%" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=cart_name display="Cart Name"}act=list&subact=list&category_id={$smarty.request.category_id}&zoneid={$smarty.request.zoneid}&brandid={$smarty.request.brandid}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}&limit={$smarty.request.limit}& {/makeLink}</td> 
          {/if}	
			{if $FIELDS.2==Y}
		  <td width="10%" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=brand_name display="Brand"}act=list&subact=list&category_id={$smarty.request.category_id}&zoneid={$smarty.request.zoneid}&brandid={$smarty.request.brandid}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}&limit={$smarty.request.limit}& {/makeLink}</td>
         	{/if}
		  {if $FIELDS.3==Y}
		  <td width="10%" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=Made display="Made In"}act=list&subact=list&category_id={$smarty.request.category_id}&zoneid={$smarty.request.zoneid}&brandid={$smarty.request.brandid}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}&limit={$smarty.request.limit}& {/makeLink}</td>
		  {/if}
		   {if $FIELDS.15==Y}
		  <td width="10%" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=display_order display="Display Order"}act=list&subact=list&category_id={$smarty.request.category_id}&zoneid={$smarty.request.zoneid}&brandid={$smarty.request.brandid}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}&limit={$smarty.request.limit}& {/makeLink}</td>
		  {/if}
		  {if $FIELDS.47==Y}
		  <td width="10%" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=quantity display="quantity"}act=list&subact=list&category_id={$smarty.request.category_id}&zoneid={$smarty.request.zoneid}&brandid={$smarty.request.brandid}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}&limit={$smarty.request.limit}& {/makeLink}</td>
		  {/if}
		  {if $FIELDS.22==Y}
		  <td width="10%" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=weight display="Weight"}act=list&subact=list&category_id={$smarty.request.category_id}&zoneid={$smarty.request.zoneid}&brandid={$smarty.request.brandid}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}&limit={$smarty.request.limit}& {/makeLink}</td>
          {/if}
		  {if $FIELDS.12==Y}
		  <td width="10%" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=price display="Price"}act=list&subact=list&category_id={$smarty.request.category_id}&zoneid={$smarty.request.zoneid}&brandid={$smarty.request.brandid}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}&limit={$smarty.request.limit}& {/makeLink}</td>
		  {/if}
		  {if $FIELDS.29==Y}
		  <td width="10%" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=active display="Active"}act=list&subact=list&category_id={$smarty.request.category_id}&zoneid={$smarty.request.zoneid}&brandid={$smarty.request.brandid}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}&limit={$smarty.request.limit}&{/makeLink}</td>
	      {/if}
		</tr>
		
        {foreach from=$PRODUCT_LIST item=product}
        <tr class="{cycle values="naGrid1,naGrid2"}">
          <td valign="middle" align="center">{if $STORE_PERMISSION.delete == 'Y' or $product->owner_id eq $STORE_ID}<input type="checkbox" name="product_id[]" id="product_id[]" value="{$product->id}">{/if}</td> 
          {if $FIELDS.1==Y}
		  <td align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act={if $product->owner_id eq $STORE_ID}form{else}form{/if}&id={$product->id}&category_id={$smarty.request.category_id}&brandid={$smarty.request.brandid}&zoneid={$smarty.request.zoneid}&product_search={$smarty.request.product_search}&limit={$smarty.request.limit}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&orderBy={$ORDERBY}&pageNo={$smarty.request.pageNo}{/makeLink}">{$product->name}</a></td> 
          {if ($PRODUCTCODE=='Y')}<td align="left" valign="middle">{$product->product_id}</td>{/if}
          {/if}
		  {if $FIELDS.38==Y}
		 <td align="left" valign="middle">{$product->cart_name}</td>
		  {/if}
		  
		  {if $FIELDS.2==Y}
		  <td align="left" valign="middle">{if $product->brand_id > 0}{$product->brand_name}{else}No Brand{/if}</td>
          {/if}
		  {if $FIELDS.3==Y}
		  <td align="left" valign="middle">{if $product->Made}{$product->Made}{else}No Made{/if}</td>
		  {/if}
		  {if $FIELDS.15==Y}
		  <td align="center" valign="middle">{$product->display_order}</td>
		  {/if}
		  {if $FIELDS.47==Y}
		  <td align="left" valign="middle">{$product->quantity}</td>
		  {/if}
		  {if $FIELDS.22==Y}
		  <td align="left" valign="middle">{$product->weight}&nbsp;lbs</td>
          {/if}
		  {if $FIELDS.12==Y}
		  <td align="left" valign="middle">$&nbsp;{$product->price}</td>
		  {/if}
		  {if $FIELDS.29==Y}
		  <td align="left" valign="middle">{if $product->active.gif eq 'Y' } 
			<img border="0" title="Activate"  src="{$smarty.const.SITE_URL}/framework/includes/images/active{$product->active.gif}.gif"/>
			<a href="{makeLink mod=$MOD pg=$PG}act=active&id={$product->id}&stat={$product->active}&category_id={$smarty.request.category_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}&aid={$smarty.request.aid}{/makeLink}&product_search={$smarty.request.product_search}&limit={$smarty.request.limit}&pageNo={$smarty.request.pageNo}"><img border="0" title="Deactivate"  src="{$smarty.const.SITE_URL}/framework/includes/images/deactive{$product->active.gif}.gif"/></a>
		{else}
			<a href="{makeLink mod=$MOD pg=$PG}act=active&id={$product->id}&stat={$product->active}&category_id={$smarty.request.category_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}&aid={$smarty.request.aid}{/makeLink}&product_search={$smarty.request.product_search}&limit={$smarty.request.limit}&pageNo={$smarty.request.pageNo}"><img border="0" title="Activate"  src="{$smarty.const.SITE_URL}/framework/includes/images/active{$product->active.gif}.gif"/></a>
			<img border="0" title="Deactivate"  src="{$smarty.const.SITE_URL}/framework/includes/images/deactive{$product->active.gif}.gif"/>
		{/if}</td>
		  
		  
          {/if}
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
 
 
 {if $STORE_PERMISSION.edit == 'Y'}	
 {if $FIELDS.48=='Y'}
 <table width="100%" border="0" cellpadding="5" cellspacing="0"  class=naBrDr align="center">
	 <tr class='naGrid2'>
               <td colspan="2" class="naGridTitle"><span class="group_style">&nbsp;Mass Updates</span></td>
			   <td class="naGridTitle" align="right"><table align="right" class="naGridTitle" border="0"><tr><Td><input name="Append" type="checkbox" id="Append" value="Y" checked></Td><td><strong>Append  to the existing</strong></td>
			   </tr></table></td>
    </tr>
	  <tr class="naGrid1">
	    <td colspan=3 valign="center"><table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="10" colspan="5" align="left" valign="middle"><div></div></td>
                </tr>
                <tr>
				{if $FIELDS.2==Y}
                  <td width="17%" height="19" align="left" valign="middle"> Brand</td>
                  <td height="19" colspan="2" align="left" valign="top"><select name="brand_id">
	  <option value="0">--- No Brand ---</option>
	{html_options values=$BRAND.brand_id output=$BRAND.brand_name}
	  </select></td>
	  {/if}
	   {if $FIELDS.12==Y}
                  <td width="19%" height="19" align="left" valign="top">Base Price</td>
                  <td width="31%" height="19"><input type="text" name="price" class="formText"  maxlength="15"></td>
				  
                  {/if}
                </tr>
                <tr>
                  <td height="10" colspan="5">&nbsp;</td>
                </tr>
                <tr>
				 {if $FIELDS.22==Y}
                  <td height="19"> Weight</td>
                  <td height="19" colspan="2"><input type="text" name="weight" class="formText"  maxlength="150"></td>
				  {/if}
				   {if $FIELDS.13==Y}
                  <td height="19">Price Type </td>
                  <td height="19"><select name="price_type" style="width:200">
				  	<option value="0"> ---No Change--- </option>
		 			{html_options values=$PRODUCT_PRICES.id output=$PRODUCT_PRICES.name}
	             </select></td>
				 {/if}
                </tr>
                <tr>
                  <td height="19">&nbsp;</td>
                  <td height="19" colspan="2">&nbsp;</td>
                  <td height="19">&nbsp;</td>
                  <td height="19">&nbsp;</td>
                </tr>
                <tr>
				{if $FIELDS.17==Y}
                  <td height="19">Display related </td>
                  <td height="19" colspan="2"><input type=checkbox name="display_related" value="Y"></td>
				  {/if}
				    {if $FIELDS.14==Y}
                  <td height="19">Price</td>
                  <td height="19"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr class=naGrid1>
                      <td width="15%"><input type="text" name="prices" id="prices" value="" class="formText"  style="width:50" maxlength="10"></td>
                      <td width="6%" valign="middle"><input type="checkbox" id="is_percentage" name="is_percentage" value="Y"></td>
                      <td width="50%" valign="middle" align="left">Is Percentage</td>
                      <td width="29%"><div id="calculated_price" style="float:left">&nbsp;</div></td>
					  
                    </tr>
                  </table></td>{/if}
                </tr>
                <tr>
                  <td height="19">&nbsp;</td>
                  <td height="19" colspan="2">&nbsp;</td>
                  <td height="19">&nbsp;</td>
                  <td height="19">&nbsp;</td>
                </tr>
                <tr>
				 {if $FIELDS.29==Y}
                  <td height="19">Active</td>
                  <td height="19" colspan="2"><input type=checkbox name="active" value="Y" checked></td>
				  {/if}
				  {if $FIELDS.16==Y}
                  <td height="19">Display Gross sell </td>
                  <td height="19"><input type=checkbox name="display_gross" value="Y" ></td>
				  {/if}
				 
                </tr>
				 <tr>
                   <td height="19">&nbsp;</td>
                   <td height="19" colspan="2">&nbsp;</td>
                   <td height="19">&nbsp;</td>
                   <td height="19">&nbsp;</td>
                 </tr>
                 <tr>
				  {if $FIELDS.3==Y}
                   <td height="19">Made In</td>
                   <td height="19" colspan="2"><select name="zone_id"  style="width:120">
        <option value="0">--- No Made In ---</option>
   {html_options values=$ZONE.id output=$ZONE.name selected=$MADE_IN.zone_id}
	  </select></td>
	   {/if}
	  {if $FIELDS.37==Y}
                   <td height="19">Display Name</td>
                   <td height="19"><input type="text" name="display_name" class="formText"  maxlength="150"></td>
                 {/if}
				 
				 </tr>
				
                 <tr>
                  <td height="19">&nbsp;</td>
                  <td height="19" colspan="2">&nbsp;</td>
                  <td height="19">&nbsp;</td>
                  <td height="19">&nbsp;</td>
                </tr>

                <tr>
				{if $FIELDS.4==Y}
                  <td height="30" align="left" valign="top"> Category</td>
                  <td height="30" colspan="2"><select name="category[]" size="10" multiple style="width:250">
				  <option value=''>-- Select Category --</option>
	 {html_options values=$CATEGORY.category_id output=$CATEGORY.category_name onClick=alert(fff)}
	   </select><img src="{$GLOBAL.tpl_url}/images/icon_small_info.gif" width="17" height="16" align="top" {popup text="[Press Ctrl to select/unselect multiple categories]" fgcolor="#eeffaa"} /></td>
	   {/if}
	    {if $FIELDS.5==Y}
                  <td height="30" align="left" valign="top">Description</td>
                  <td height="30"><textarea name="description" cols="40" rows="9" class="formText"></textarea></td>
				  {/if}
                </tr>
              </table></td>
          </tr>
        </table></td></tr>
	
	<tr class=naGrid1 align="left">
	 {if $FIELDS.30==Y}
    <td colspan="3" align="left" valign=top><strong>{$FOLDER_NAME}</strong></td>
    </tr>
	{foreach from=$GROUP item=gp name=gp_loop}
	<tr class=naGrid1>
    <td align="left" valign=top>{$gp.group.group_name}</td>
    <td align="left" valign=top>:</td>
    <td><select name="new_group_id" id="new_group_id" style="width:200" onChange="ViewGroup('{$gp.group.id}',this.value,'0','All');">
	<option value="0">---Select Group---</option>
     {html_options values=$gp.group.category_id output=$gp.group.category_name}
	          </select>&nbsp;<a href="#"  onClick="javascript:popUp2('{$gp.group.id}','0'); return false;">(New Category)</a></td>
		  
	</tr>
	 <tr class=naGrid1>
	 
	
    <td colspan="3" align="center" valign=top>
	<div align="center" id="grp_All_{$gp.group.id}" style="display:inline"></div>
	</td>
    </tr>
  {/foreach}
  {/if}	
  
	<tr class=naGrid1 align="left">
		<td colspan="3" valign=top>&nbsp;</td>
	</tr>
	
	{if $FIELDS.30==Y}
	<tr class=naGrid1>
	  <td colspan="3" valign=top><table width="90%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="46%"><strong>Options Exclude Items</strong></td>
          <td width="4%" align="left">:</td>
          <td width="50%">
		<select name="ex_accessory[]" id="ex_accessory" size="10" multiple style="width:378" tabindex="7" >
			{html_options values=$EXCLUDED_ACCESSORY.group_id output=$EXCLUDED_ACCESSORY.group_name selected=$EXCLUDEDIDS.group_id}
		</select>
		<A href="#" onclick="javascript:popUpexclude(); return false;">(Add New Exclude Item)</A> 		  </td>
        </tr>
      </table></td>
  </tr>
  {/if}
  
  {if $FIELDS.44==Y}
  <tr class=naGrid1>
	  <td colspan="3" valign=top><table width="90%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="20%">Page Title: </td>
          <td width="80%"><textarea name="page_title" class="formText"  style="width:378" maxlength="50" tabindex="22">{$PRODUCT.page_title}</textarea></td>
        </tr>
      </table></td>
  </tr>
  {/if}
  {if $FIELDS.45==Y}
  <tr class=naGrid1>
	  <td colspan="3" valign=top><table width="90%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="20%">Meta Description: </td>
          <td width="80%"><textarea name="meta_description" class="formText"  style="width:378" maxlength="50" tabindex="22">{$PRODUCT.meta_description}</textarea></td>
        </tr>
      </table></td>
  </tr>
  {/if}
  {if $FIELDS.46==Y}
    <tr class=naGrid1>
	  <td colspan="3" valign=top><table width="90%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="20%">Meta Keywords: </td>
          <td width="80%"><textarea name="meta_keywords" class="formText"  style="width:378" maxlength="50" tabindex="22">{$PRODUCT.meta_keywords}</textarea></td>
        </tr>
      </table></td>
  </tr>
  {/if}

<!--  ------------ for product to store mass update------------ -->
{if $FIELDS.30==Y}
  <tr><td colspan="3">
  
 <table width="100%"  border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td colspan="2" class="naGridTitle">Assign {$PRODUCT_DISPLAY_NAME} and {$ACCESSORY_DISPLAY_NAME} to store</td>
        <td colspan="2" class="naGridTitle"><table align="right" class="naGridTitle" border="0"><tr>
		<Td><input name="Remove" type="radio" id="Remove" value="Y" onClick="fnChange1();" ></Td><td><strong>Remove</strong></td>
		<Td><input type="radio" id="Append_product_store" onClick="fnChange2();" name="Append_product_store" value="Y"    checked ></Td><td><strong>Append {$FOLDER_NAME} to the existing</strong></td></tr></table></td>
        </tr>
	  {if $smarty.request.manage==''}
	  <tr class="{cycle name=bg values="naGrid1,naGrid2"}">
        <td width="5%"><input type="checkbox" name="mainstore" value="0" CHECKED></td>
        <td width="37%"><a href="#" onClick="javascript:ViewBottomGroup('0');return false;">Main Store</a></td>
        <td width="38%">&nbsp;</td>
        <td width="16%"><!--{$MAINSTORE}--></td>
      </tr>
	   <tr>
        <td colspan="4"><div id="store_0" style="display:inline"></div></td>
        </tr>
	{/if}
	  {foreach from=$STORE item=stores name=store_loop}
      <tr class="{cycle name=bg values="naGrid1,naGrid2"}">
        <td width="5%">{html_checkboxes name='stores_id' values=$stores.id  selected=$stores.id}</td>
        <td width="37%" align="left"><a href="#" onClick="javascript:ViewBottomGroup('{$stores.id}');return false;">{$stores.name}</a></td>
        <td width="38%">&nbsp;</td>
        <td width="16%"><!--{$stores.status}--></td>
      </tr>
	    <tr>
        <td colspan="4"><div id="store_{$stores.id}" style="display:inline"></div></td>
        </tr>
	  {/foreach}
    </table>
  
  
  </td></tr>
  {/if}
<!--  ------------ for product to store mass update------------ -->
  
 </table>
 
 
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="25" colspan="3" class="naGridTitle">&nbsp;Update selected fields </td>
  </tr>
  
  <tr>
    {if $FIELDS.2==Y}
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field1" value="Y">&nbsp;Brand </td>
	{/if}
	 {if $FIELDS.12==Y}
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field2" value="Y">&nbsp;Base Price</td>
	{/if}
	 {if $FIELDS.22==Y}
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field3" value="y">&nbsp;Weight</td>
	{/if}
  </tr>
  
  
  
  
   <tr>
    {if $FIELDS.13==Y}
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field4" value="y">&nbsp;Price Type</td>
	{/if}
   {if $FIELDS.17==Y}
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field5" value="Y">&nbsp;Display Related</td>
	{/if}
	 {if $FIELDS.14==Y}
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field6" value="Y">&nbsp;Price </td>
	{/if}
  </tr>
  
  
   <tr>
    {if $FIELDS.29==Y}
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field7" value="Y">&nbsp;Active</td>
	{/if}
	  {if $FIELDS.16==Y}
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field8" value="Y">&nbsp;Display Gross Sell </td>
	{/if}   
    {if $FIELDS.3==Y}
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field9" value="Y">&nbsp;Made In </td>
	{/if}
  </tr>
  
  <tr>
   {if $FIELDS.37==Y}
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field13" value="Y">&nbsp;Display name </td>
	{/if}
  {if $FIELDS.4==Y}
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field10" value="Y">&nbsp;Category </td>
	{/if}
	 {if $FIELDS.5==Y}
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field11" value="y">&nbsp;Discription</td>
	{/if}
	
  </tr>
  
  <tr>
   {if $FIELDS.44==Y}
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field20" value="Y">&nbsp;Page Title </td>
	{/if}
  {if $FIELDS.45==Y}
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field21" value="Y">&nbsp;Meta Description </td>
	{/if}
	 {if $FIELDS.46==Y}
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field22" value="y">&nbsp;Meta Keywords</td>
	{/if}	
  </tr>
  
  {if $FIELDS.30==Y}
  <tr>
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field15" value="Y">&nbsp;Options Exclude Items </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  {/if}
 
  
  <tr class="naGrid2">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  
  <tr>
    <td colspan="3">
	
      <table width="100%" cellpadding="0" cellspacing="0">
	   {if $FIELDS.30==Y}
	   {assign var="col2" value="1"} 
       {assign var="var1" value="0"}

		<tr>
		{foreach from=$GROUP item=gp name=gp_loop}
		{assign var="var1" value=$var1+1}
		 {if $smarty.foreach.gp_loop.index is div by 3}
		</tr>
		<tr>
		{ /if }
			 <td width="25%" >&nbsp;<input type="checkbox" id="mass_field12{$col2}" name="mass_field12{$col2}" value="Y">&nbsp;{$gp.group.group_name} </td>
			 {assign var="col2" value=$col2+1} 
		  {/foreach}
			</tr>	  
	  {/if}
	  </table>	
	
	</td>
   </tr>
  
  
  <!--
  {if $FIELDS.30==Y}
{assign var="col2" value="1"} 
{assign var="var1" value="0"}
<tr>
  	{foreach from=$GROUP item=gp name=gp_loop}
	 {assign var="var1" value=$var1+1}
 
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field12{$col2}" value="Y">&nbsp;{$gp.group.group_name} </td>
     {assign var="col2" value=$col2+1} 
	  {/foreach}
	 </tr>
	  {/if}
	  -->
{if $FIELDS.30==Y}
  <tr class="naGrid2">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
	   <tr>
  
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field14" value="Y">&nbsp;Assign {$PRODUCT_DISPLAY_NAME} and {$ACCESSORY_DISPLAY_NAME} to store </td>
	
	</tr>
{/if}
	  <input type="hidden" name="cnt" value="{$var1}">
</table>
 
 
 
 
 
 </td>
    </tr>
	  <tr class="naGridTitle">
    <td colspan="3" valign="center"><div align=center><input name="btn_submit" type=submit class="naBtn" value="Mass Update" onclick="return chkval();">&nbsp;<input name="reset" type=reset class="naBtn" value="Reset"></div></td>
  </tr></table>
  {/if}
  {/if}  
  </td></tr>
  </table>
</form>
