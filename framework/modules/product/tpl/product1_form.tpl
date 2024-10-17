{literal}
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
		var fields	=	new Array('name','category');
		var msgs  	=	new Array('Product Name','Category');	
</SCRIPT>
{/literal}
<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>
{literal}
<script language="javascript" type="text/javascript">

function removeImge(product_id,field) {
	var req = newXMLHttpRequest();
	req.onreadystatechange = getReadyStateHandler(req, display_remove);
	{/literal}
	//alert(store);
	if(confirm ("Are you sure want to delete the image"))
	{literal}{{/literal}
		{if $smarty.request.manage=='manage'}
		req.open("GET", "{makeLink mod=store pg=product_ajax_removeImage}{/makeLink}&field="+field+"&product_id="+product_id);
		{else}
		req.open("GET", "{makeLink mod=product pg=ajax_removeImage}{/makeLink}&field="+field+"&product_id="+product_id);
		{/if}
	{literal}}{/literal}
	else
		return false;	
	{literal}
	req.send(null);
	
}  
   
function display_remove(_var) {
	_var = _var.split('|');
	//alert(_var[0]);
	//alert(_var[1]);
	
	if(_var[0]=="0")
	{
	var e= eval("document.getElementById('Image_"+_var[1]+"')");
	//alert(e.innerHTML);
	e.innerHTML="";
	}
	else
		{
		
		}
	
}

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
	
	if(_var[2]!="" && _var[0]!=""){
		var e= eval("document.getElementById('grp_"+_var[2]+"_"+_var[0]+"')");
		e.innerHTML=_var[1];
	}
	
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
					//alert("category_id"+category_id)
					if(e.checked==true)
					{//alert('True'+accessory_id);
						elm.checked=true;
						}
					else
					{//alert('False'+accessory_id);
						elm.checked=false;
						}
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
function ViewBottomGroup(store_id,product_id) 
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
	{if $smarty.request.manage=='manage'}
	req.open("GET", "{makeLink mod=store pg=product_ajax_store}{/makeLink}&store_id="+store_id+"&product_id="+product_id);
	{else}
	req.open("GET", "{makeLink mod=product pg=ajax_store}{/makeLink}&store_id="+store_id+"&product_id="+product_id);
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
function chkval()
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


/*{/literal}
windowReference=window.open('{makeLink mod=product pg=PopnewCategory}act=category{/makeLink}&$CATEGORY.category_id='+cat_id+'&prd_id='+prd_id, "name_of_window", "width=500,height=300,left=100,top=200,scrollbars=yes,menubar=no, resizable=no,location=no,toolbar=no");
if (!windowReference.opener)
windowReference.opener = self;
{literal}
}*/


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

{/literal}
</script>
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0" >
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;" onSubmit="return chk(this);"> 

  <table border=0 width=80% cellpadding=5 cellspacing=0 class=naBrDr>
    <tr align="left">
      <td colspan=3 valign=top><table width="100%" align="center">
          <tr>
            <td nowrap class="naH1">{$smarty.request.sId}</td>
            <td nowrap align="right" class="titleLink"><a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=list{/makeLink}&sId={$SUBNAME}&mId={$MID}&fId={$smarty.request.fId}&category_id={$smarty.request.category_id}&limit={$smarty.request.limit}">{$smarty.request.sId} List</a></td>
          </tr>
      </table></td>
    </tr>
   <tr>
    <td colspan=3 class="naGridTitle"><span class="group_style">Product Details</span></td>
  </tr>
  <tr class=naGrid1>
    <td height="10" valign=top>&nbsp;</td>
    <td valign=top>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  {if $FIELDS.0==Y}
  <tr class=naGrid1>
    <td height="10" valign=top>
	<div align=right class="element_style">Product id</div></td>
    <td valign=top>:</td>
    <td><input type="text" name="product_id" value="{$PRODUCT.product_id}" class="formText" style="width:200" maxlength="150" tabindex="1"></td>
  </tr>
  {/if}
  {if $FIELDS.1==Y}
  <tr class=naGrid1>
    <td valign=top width=40%><div align=right class="element_style">Name</div></td>
    <td width=1 valign=top>:</td>
    <td><input type="text" name="name" value="{$PRODUCT.name|escape:'html'}"   tabindex="2" size="60">
    </td>
  </tr>
  {/if}
  
  {if $FIELDS.37==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Display Name</td>
    <td valign=top>:</td>
    <td><input type="text" name="display_name" value="{$PRODUCT.display_name|escape:'html'}" class="formText"  size="60" tabindex="3"></td>
  </tr>
   {/if}
  {if $FIELDS.38==Y}
   <tr class=naGrid1>
     <td align="right" valign=top>Cart Name </td>
     <td valign=top>&nbsp;</td>
     <td><input type="text" name="cart_name" value="{$PRODUCT.cart_name|escape:'html'}" class="formText" size="60" tabindex="4"></td>
   </tr>
   {/if}
   {if $FIELDS.2==Y}
   <tr class=naGrid1>
    <td align="right" valign=top>Brand</td>
    <td valign=top>:</td>
    <td><select name="brand_id"  style="width:378" tabindex="5" >
        <option value="0">--- No Brand ---</option>
   {html_options values=$BRAND.brand_id output=$BRAND.brand_name selected=$PRODUCT.brand_id}
	  </select></td>
  </tr>
  {/if}
  {if $FIELDS.3==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Made In</td>
    <td valign=top>:</td>
    <td><select name="zone_id"  style="width:378" tabindex="6">
        <option value="0">--- No Made In ---</option>
   {html_options values=$ZONE.id output=$ZONE.name selected=$MADE_IN.zone_id}
	  </select></td>
  </tr>
  {/if}
  {if $FIELDS.4==Y}
  <tr class=naGrid1>
    <td valign=top><div align=right class="element_style">Category</div></td>
    <td valign=top>:</td>
    <td><select name="category[]" id="category" size="10" multiple style="width:378" tabindex="7"  >
      
        
	 {html_options values=$CATEGORY.category_id output=$CATEGORY.category_name selected=$SELECTEDIDS.category_id}
	  
      
    </select>      &nbsp;
        <img src="{$GLOBAL.tpl_url}/images/icon_small_info.gif" width="17" height="16" align="top" {popup text="[Press Ctrl to select multiple categories]" fgcolor="#eeffaa"} /></td>
  </tr>
 
  
  {if $FIELDS.5==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Description</td>
    <td align="center" valign=top>:</td>
    <td><textarea id="description" name="description" cols="59" rows="9" class="formText" tabindex="8">{$PRODUCT.description|escape:'html'}</textarea></td>
  </tr>
  {/if}
  
  {if $FIELDS.6==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Out of Stock </td>
    <td align="center" valign=top>&nbsp;</td>
    <td><input type=checkbox name="out_stock" value="Y" {if $PRODUCT.out_stock=='Y'} checked{/if} tabindex="9"></td>
  </tr>
  {/if}
  {if $FIELDS.7==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Out of Stock Message </td>
    <td align="center" valign=top>&nbsp;</td>
    <td><textarea id="out_message" name="out_message" cols="59" rows="9" class="formText" tabindex="10">{$PRODUCT.out_message|escape:'html'}</textarea></td>
  </tr>
  {/if}
  
  {if $FIELDS.8==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Image</td>
    <td align="center" valign=top>: </td>
    <td><input name="image_extension" type="file" id="image_extension" tabindex="11" size="60" >
	
	<!--<a href="{makeLink mod=product pg=index}act=removeimage&id={$PRODUCT.id}&fld=image_extension&module=product{/makeLink}">Remove</a>--></td>
  </tr>
  {/if}  
  {if $PRODUCT.image_extension ne ''}
  <tr class=naGrid1>
    <td valign=top align="center">&nbsp;</td>
    <td valign=top>&nbsp;</td>
    <td valign=top align="left"><div id="Image_image_extension">
	{assign var='desc_image' value=","|explode:$GLOBAL.product_desc_image}
	{assign var='list_image' value=","|explode:$GLOBAL.product_list_image}
	{assign var='thumb_image' value=","|explode:$GLOBAL.product_thumb_image}
	{assign var='thumb2_image' value=","|explode:$GLOBAL.product_thumb2_image}
	{assign var='thumb3_image' value=","|explode:$GLOBAL.product_thumb3_image}
	{if $thumb_image.0 > 0 && $thumb_image.1 > 0}
	<img src="{$smarty.const.SITE_URL}/modules/product/images/thumb/{$PRODUCT.id}.{$PRODUCT.image_extension}?{$DATE}">
	{elseif $thumb2_image.0 > 0 && $thumb2_image.1 > 0}
	<img src="{$smarty.const.SITE_URL}/modules/product/images/thumb/{$PRODUCT.id}_thumb2_.{$PRODUCT.image_extension}?{$DATE}">
	{elseif $thumb3_image.0 > 0 && $thumb3_image.1 > 0}
	<img src="{$smarty.const.SITE_URL}/modules/product/images/thumb/{$PRODUCT.id}_thumb3_.{$PRODUCT.image_extension}?{$DATE}">
	{elseif $list_image.0 > 0 && $list_image.1 > 0}
	<img src="{$smarty.const.SITE_URL}/modules/product/images/thumb/{$PRODUCT.id}_List_.{$PRODUCT.image_extension}?{$DATE}">
	{elseif $desc_image.0 > 0 && $desc_image.1 > 0}
	<img src="{$smarty.const.SITE_URL}/modules/product/images/thumb/{$PRODUCT.id}_des_.{$PRODUCT.image_extension}?{$DATE}">
	{else}
	<img src="{$smarty.const.SITE_URL}/modules/product/images/{$PRODUCT.id}.{$PRODUCT.image_extension}?{$DATE}" width="60" height="60">
	{/if}
	&nbsp;{if $STORE_PERMISSION.edit=='Y'}<a href="#" onClick="javascript: removeImge('{$smarty.request.id}','image_extension'); return false;">Remove</a>{/if}</div></td>
  </tr>
  {/if}
  {if $FIELDS.9==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Upload SWF</td>
    <td align="center" valign=top>:</td>
    <td><input name="image_swf" type="file" id="image_swf" tabindex="12" size="60"></td>
  </tr>
  {/if}
{if $PRODUCT.swf ne ''}  
  <tr class=naGrid1>
    <td align="right" valign=top>&nbsp;</td>
    <td align="center" valign=top>&nbsp;</td>
    <td>
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="150" height="150">
          <param name="movie" value="{$GLOBAL.mod_url}/images/swf_{$PRODUCT.id}.{$PRODUCT.swf}" />
          <param name="quality" value="high" />
          <embed src="{$GLOBAL.mod_url}/images/swf_{$PRODUCT.id}.{$PRODUCT.swf}" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="150" height="150"></embed>
        </object>	
	</td>
  </tr>
  {/if}  

   {if $FIELDS.10==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>2D Image</td>
    <td align="center" valign=top>:</td>
    <td><input name="two_d_image" type="file" id="two_d_image" tabindex="13"size="60" ></td>
  </tr>
 {/if}
  {if $PRODUCT.two_d_image ne ''}
  <tr class=naGrid1>
	<td valign=top align="center">&nbsp;</td>
	<td valign=top>&nbsp;</td>
	<td valign=top align="left"><div id="Image_two_d_image"><img src="{$smarty.const.SITE_URL}/modules/product/images/2D_{$PRODUCT.id}.{$PRODUCT.two_d_image}?{$DATE}" width="150" height="150">&nbsp;{if $STORE_PERMISSION.edit=='Y'}<a href="#" onClick="javascript: removeImge('{$smarty.request.id}','two_d_image'); return false;">Remove</a>{/if}</div></td>
  </tr>
  {/if}
  
  {if $FIELDS.11==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Overlay Image 1</td>
    <td align="center" valign=top>:</td>
    <td><input name="overlay" type="file" id="overlay" size="60"></td>
  </tr>
	  {if $PRODUCT.overlay ne ''}
		  <tr class=naGrid1>
			<td valign=top align="center">&nbsp;</td>
			<td valign=top>&nbsp;</td>
			<td valign=top align="left"><div id="Image_overlay"><img src="{$smarty.const.SITE_URL}/modules/product/images/OV_{$PRODUCT.id}.{$PRODUCT.overlay}?{$DATE}" width="120" height="120">&nbsp;{if $STORE_PERMISSION.edit=='Y'}<a href="#" onClick="javascript: removeImge('{$smarty.request.id}','overlay'); return false;">Remove</a>{/if}</div></td>
		  </tr>
	  {/if}
 {/if}
 
 
  
  {if $FIELDS.39==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Overlay Image 2</td>
    <td align="center" valign=top>:</td>
    <td><input name="overlay2" type="file" id="overlay2" size="60"></td>
  </tr>
	   {if $PRODUCT.overlay2 ne ''}
		  <tr class=naGrid1>
			<td valign=top align="center">&nbsp;</td>
			<td valign=top>&nbsp;</td>
			<td valign=top align="left"><div id="Image_overlay2"><img src="{$smarty.const.SITE_URL}/modules/product/images/OV2_{$PRODUCT.id}.{$PRODUCT.overlay2}?{$DATE}" width="120" height="120">&nbsp;{if $STORE_PERMISSION.edit=='Y'}<a href="#" onClick="javascript: removeImge('{$smarty.request.id}','overlay2'); return false;">Remove</a>{/if}</div></td>
		  </tr>
	  {/if}
 {/if}
 {if $FIELDS.40==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Overlay Image 3</td>
    <td align="center" valign=top>:</td>
    <td><input name="overlay3" type="file" id="overlay3" size="60"></td>
  </tr>
	   {if $PRODUCT.overlay3 ne ''}
		  <tr class=naGrid1>
			<td valign=top align="center">&nbsp;</td>
			<td valign=top>&nbsp;</td>
			<td valign=top align="left"><div id="Image_overlay3"><img src="{$smarty.const.SITE_URL}/modules/product/images/OV3_{$PRODUCT.id}.{$PRODUCT.overlay3}?{$DATE}" width="120" height="120">&nbsp;{if $STORE_PERMISSION.edit=='Y'}<a href="#" onClick="javascript: removeImge('{$smarty.request.id}','overlay3'); return false;">Remove</a>{/if}</div></td>
		  </tr>
	  {/if}
 {/if}
{if $FIELDS.41==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Overlay Image 4</td>
    <td align="center" valign=top>:</td>
    <td><input name="overlay4" type="file" id="overlay4" size="60"></td>
  </tr>
	   {if $PRODUCT.overlay4 ne ''}
		  <tr class=naGrid1>
			<td valign=top align="center">&nbsp;</td>
			<td valign=top>&nbsp;</td>
			<td valign=top align="left"><div id="Image_overlay4"><img src="{$smarty.const.SITE_URL}/modules/product/images/OV4_{$PRODUCT.id}.{$PRODUCT.overlay4}?{$DATE}" width="120" height="120">&nbsp;{if $STORE_PERMISSION.edit=='Y'}<a href="#" onClick="javascript: removeImge('{$smarty.request.id}','overlay4'); return false;">Remove</a>{/if}</div></td>
		  </tr>
	  {/if}
 {/if}
  {if $FIELDS.42==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Overlay Image 5</td>
    <td align="center" valign=top>:</td>
    <td><input name="overlay5" type="file" id="overlay5" size="60"></td>
  </tr>
	   {if $PRODUCT.overlay5 ne ''}
		  <tr class=naGrid1>
			<td valign=top align="center">&nbsp;</td>
			<td valign=top>&nbsp;</td>
			<td valign=top align="left"><div id="Image_overlay5"><img src="{$smarty.const.SITE_URL}/modules/product/images/OV5_{$PRODUCT.id}.{$PRODUCT.overlay5}?{$DATE}" width="120" height="120">&nbsp;{if $STORE_PERMISSION.edit=='Y'}<a href="#" onClick="javascript: removeImge('{$smarty.request.id}','overlay5'); return false;">Remove</a>{/if}</div></td>
		  </tr>
	  {/if}
 {/if}
   {if $FIELDS.35==Y}
   <tr class=naGrid1>
    <td align="right" valign=top>PDF File</td>
    <td align="center" valign=top>:</td>
    <td><input name="pdf_file" type="file" id="pdf_file" tabindex="15" size="60"></td>
  </tr>
  {/if}
   {if $FIELDS.36==Y}
   <tr class=naGrid1>
    <td align="right" valign=top>PSD File</td>
    <td align="center" valign=top>:</td>
    <td><input name="psd_file" type="file" id="psd_file" tabindex="16" size="60"></td>
  </tr>
  {/if}
   {if $FIELDS.34==Y}
   <tr class=naGrid1>
    <td align="right" valign=top>AI Image</td>
    <td align="center" valign=top>:</td>
    <td><input name="ai_image" type="file" id="ai_image" tabindex="17" size="60"></td>
  </tr>
  {/if}
  
  {if $FIELDS.12==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Base Price</td>
    <td align="center" valign=top>:</td>
    <td><input type="text" name="price" value="{$PRODUCT.price}" id="base_price" class="formText"  style="width:378" maxlength="10" tabindex="18"></td>
  </tr>
  {/if}
  {if $FIELDS.13==Y}
  {if count($PRODUCT_PRICES) >0}
  <tr class=naGrid1>
    <td align="right" valign=top>Select Price Type</td>
    <td align="center" valign=top>:</td>
    <td><select name="price_type" style="width:378" tabindex="19">
		 {html_options values=$PRODUCT_PRICES.id output=$PRODUCT_PRICES.name selected=$PRODUCT_PRICE.type_id}
	  </select></td>
  </tr>
  {/if}
  {/if}
  {if $FIELDS.14==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Price</td>
    <td align="center" valign=top>:</td>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
       <tr class=naGrid1>
           <td width="15%"><input type="text" name="prices" id="prices" value="{$PRODUCT_PRICE.price}" class="formText"  style="width:50" maxlength="10" onKeyUp="javascript: calculate_price('prices','calculated_price','is_percentage')" tabindex="20"></td>
          <td width="6%" valign="middle"><input type="checkbox" id="is_percentage" name="is_percentage"  tabindex="21" value="is_percentage" {if $PRODUCT_PRICE.is_percentage=='Y'} checked{/if} onClick="javascript: calculate_price('prices','calculated_price','is_percentage')"></td>
          <td width="30%" valign="middle" align="left">Is Percentage</td>
		  <td width="100%"><div id="calculated_price" style="float:left">&nbsp;</div></td>
        </tr>
		</table></td>
  </tr>
  {/if}
  
  {if $FIELDS.15==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Display Order </td>
    <td align="center" valign=top>:</td>
    <td><input type="text" name="display_order" value="{$PRODUCT.display_order}" class="formText"  style="width:378" maxlength="50" tabindex="22"></td>
  </tr>
  {/if}
  {if $FIELDS.16==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Display Cross Sell </td>
    <td align="center" valign=top>&nbsp;</td>
    <td><input type=checkbox name="display_gross" value="Y" {if $PRODUCT.display_gross=='Y'} checked{/if} tabindex="23"></td>
  </tr>
  {/if}
  {if $FIELDS.17==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Dsiplay Related Products </td>
    <td align="center" valign=top>&nbsp;</td>
    <td><input type=checkbox name="display_related" value="Y" {if $PRODUCT.display_related=='Y'} checked{/if} tabindex="24"></td>
  </tr>
  {/if}
  
  {if $FIELDS.18==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Personalize with Monogram required </td>
    <td align="center" valign=top>:</td>
    <td><input type=checkbox name="personalise_with_monogram" value="Y" {if $PRODUCT.personalise_with_monogram=='Y'} checked{/if} tabindex="25"></td>
  </tr>
  {/if}
  {if $FIELDS.19==Y}
  {if $smarty.request.id eq ''}
  <tr class=naGrid1>
    <td align="center" valign=top colspan="3"><table align="center" cellpadding="0" cellspacing="0" border="0" width="50%"><tr class=naGrid1>
    <td align="left" valign=top width=31%>Is Gift Certificate </td>
    <td align="left" valign=top width=4%>:</td>
    <td align="left"><input id="is_giftcertificate" type=checkbox name="is_giftcertificate" value="Y" {if $PRODUCT.is_giftcertificate=='Y'} checked{/if}  onClick="return display('giftcertificate')" tabindex="26"></td>
				  </tr></table><div id="giftcertificate" style="display:none"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
				  <tr class=naGrid1>
					<td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="40%">&nbsp;</td>
						<td width="4%" align="center">&nbsp;</td>
						<td width="56%">&nbsp;</td>
					  </tr>
					  <tr class=naGrid1>
						<td align="right">Number of time the Gift Certificate can use </td>
						<td align="center">:</td>
						<td><select name="coupon_options" onChange="return display(this.value)">
					   <option value="one" {if $COUPON.coupon_options=='one'} selected{/if}>One Time</option>
					   <option value="fixed"{if $COUPON.coupon_options=='fixed'} selected{/if}>Fixed</option>
					   <option value="unlimit"{if $COUPON.coupon_options=='unlimit'} selected{/if}>Unlimit</option>
					 </select><div id="hidetimes" style="display:none">
					 <table width="100%"  border="0" cellpadding="0" cellspacing="0"  class=naGrid1>
					   <tr class=naGrid1>
						 <td width="47%">No of Times</td>
						 <td width="53%"><input name="one_times" type="text" class="input" id="one_times"  value="" size="2"/></td>
					   </tr>
					 </table>
					 </div></td>
					  </tr>
					  <tr class=naGrid1>
						<td align="right">Duration in days </td>
						<td align="center">:</td>
						<td><input name="duration" type="text" id="duration" style="width:378"></td>
					  </tr>
					  <tr>
						<td>&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td>&nbsp;</td>
					  </tr>
					</table></td>
				  </tr>
				</table></div></td>
    </tr>
  
 {/if}
 {/if}
  
  {if $FIELDS.20==Y}
  <tr class=naGrid1>
    <td align="right" valign=top> Height</td>
    <td align="center" valign=top>:</td>
    <td><input type="text" name="thickness" value="{$PRODUCT.thickness}" class="formText"  style="width:378" maxlength="150" tabindex="30"></td>
  </tr>
  {/if}
  
  {if $FIELDS.21==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Width</td>
    <td align="center" valign=top>:</td>
    <td><input type="text" name="width" value="{$PRODUCT.width}" id="width" class="formText"  style="width:378" tabindex="31"></td>
  </tr>
  {/if}
  
  
  {if $FIELDS.22==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Weight</td>
    <td align="center" valign=top>:</td>
    <td><input type="text" id="weight" name="weight" value="{$PRODUCT.weight}" class="formText"  style="width:378" maxlength="10" tabindex="32"></td>
  </tr>
  {/if}
  
  {if $FIELDS.23==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Image Area Height </td>
    <td align="center" valign=top>&nbsp;</td>
    <td><input name="image_area_height" type="text" id="image_area_height" size="13" value="{$PRODUCT.image_area_height}" tabindex="33" style="width:378"></td>
  </tr>
  {/if}
  
  {if $FIELDS.24==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Image Area Width</td>
    <td align="center" valign=top>&nbsp;</td>
    <td><input name="image_area_width" type="text" id="image_area_width" size="13" value="{$PRODUCT.image_area_width}" tabindex="34" style="width:378"></td>
  </tr>
  {/if}
  
  {if $FIELDS.25==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>No of Names</td>
    <td align="center" valign=top>:</td>
    <td><input type="text" name="x_co" value="{$PRODUCT.x_co}" id="x_co" class="formText"  style="width:378" tabindex="35" style="width:378"></td>
  </tr>
  {/if}
   {if $FIELDS.26==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Y - Co-ordinate</td>
    <td align="center" valign=top>:</td>
    <td><input type="text" name="y_co" value="{$PRODUCT.y_co}" id="y_co" class="formText"  style="width:378" tabindex="36"></td>
  </tr>
  {/if}
  
   {if $FIELDS.27==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Size</td>
    <td align="center" valign=top>:</td>
    <td><input type="text" name="size" value="{$PRODUCT.size}" id="size" class="formText"  style="width:378" tabindex="37"></td>
  </tr>
  {/if}
  
  

  {if $FIELDS.28==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Hide in main store</td>
    <td align="center" valign=top>:</td>
    <td><input id="hide_in_mainstore" type=checkbox name="hide_in_mainstore" value="Y" {if $PRODUCT.hide_in_mainstore=='Y'} checked{/if} tabindex="38"></td>
  </tr>
  {/if}

 {if $FIELDS.43==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Dual Side</td>
    <td align="center" valign=top>:</td>
    <td><input id="dual_side" type=checkbox name="dual_side" value="Y" {if $smarty.request.id}{if $PRODUCT.dual_side=='Y'} checked{/if}{/if}></td>
  </tr>
  {/if}
  
  {if $FIELDS.29==Y}
  <tr class=naGrid1>
    <td align="right" valign=top>Active</td>
    <td align="center" valign=top>:</td>
    <td><input id="active" type=checkbox name="active" value="Y" {if $smarty.request.id}{if $PRODUCT.active=='Y'} checked{/if}{else}checked{/if} tabindex="39"></td>
  </tr>
  {/if}
  
  {if $FIELDS.30==Y}
    <tr class=naGrid1>
    <td colspan="3" align="center" valign=top><strong>{$FOLDER_NAME}</strong></td>
    </tr>
	{foreach from=$GROUP item=gp name=gp_loop}
	<tr class=naGrid1>
    <td align="right" valign=top>{$gp.group.group_name}</td>
    <td align="center" valign=top>:</td>
    <td><select name="new_group_id" id="new_group_id_{$gp.group.id}" style="width:378" onChange="ViewGroup('{$gp.group.id}',this.value,'{if $smarty.request.id}{$smarty.request.id}{else}0{/if}','All');">
	<option value="0">---Select Group---</option>
     {html_options values=$gp.group.category_id output=$gp.group.category_name}
	          </select>&nbsp;<a href="#"  onClick="javascript:popUp2('{$gp.group.id}','{$smarty.request.id}'); return false;">{if $STORE_PERMISSION.add=='Y'}(New Category)</a>{/if}</td>
			  
	</tr>
	
	 <tr class=naGrid1>
    <td colspan="3" align="center" valign=top>
	<div align="center" id="grp_All_{$gp.group.id}" style="display:inline"></div>
	</td>
    </tr>
  {/foreach}
 
  {/if}
  {if $FIELDS.32==Y}
   <tr class=naGrid2>
    <td colspan="3" align="center" valign=top><table width="100%"  border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td colspan="4" class="naGridTitle">Assign {$PRODUCT_DISPLAY_NAME} and {$ACCESSORY_DISPLAY_NAME} to store</td>
        </tr>
		{if $smarty.request.manage==''}
	  <tr class="{cycle name=bg values="naGrid1,naGrid2"}">
        <td width="5%"><input type="checkbox" name="mainstore" value="0" {if $smarty.request.id}{if $MAINSTORE eq 'YES' }CHECKED{/if}{else}CHECKED{/if}></td>
        <td width="20%"><a href="#" onClick="javascript:ViewBottomGroup('0','{if $smarty.request.id}{$smarty.request.id}{else}0{/if}');return false;">Main Store</a></td>
        <td width="50%">&nbsp;</td>
        <td width="25%">{$MAINSTORE}</td>
      </tr>
	   <tr>
        <td colspan="4"><div id="store_0" style="display:inline"></div></td>
        </tr>
		{/if}
		 {foreach from=$STORE item=stores name=store_loop}
      <tr class="{cycle name=bg values="naGrid1,naGrid2"}">
        <td width="5%">{if $smarty.request.id}{html_checkboxes name='stores_id' values=$stores.id selected=$RELSTORE.id}{else}{html_checkboxes name='stores_id' values=$stores.id selected=$stores.id}{/if}</td>
        <td width="20%"><a href="#" onClick="javascript:ViewBottomGroup('{$stores.id}','{if $smarty.request.id}{$smarty.request.id}{else}0{/if}');return false;">{$stores.name}</a></td>
        <td width="50%">&nbsp;</td>
        <td width="25%">{$stores.status}</td>
      </tr>
	    <tr>
        <td colspan="4"><div id="store_{$stores.id}" style="display:inline"></div></td>
        </tr>
	  {/foreach}
	  
    </table></td>
    </tr>
{/if}
  <!-- cross sell and product to store-->
  {if $FIELDS.31==Y}
  <tr>
      <td colspan=3 valign=center>
	 <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="3" class="naGridTitle">Related Products</td>
        </tr>
        <tr class="naGrid1">
          <td width="43%">&nbsp;</td>
          <td width="11%">&nbsp;</td>
          <td width="46%">&nbsp;</td>
        </tr>
        <tr class="naGrid1">
          <td align="right">All Products </td>
          <td>&nbsp;</td>
          <td>Related Products </td>
        </tr>
        <tr class="naGrid1">
          <td align="right"><span class="element_style">
            <select name="products_all" size="10" multiple id="products_all" style="width:200">
   				{html_options values=$PRODUCT_LIST.id output=$PRODUCT_LIST.name selected=$SELECTEDIDS.id}
	   		</select>
          </span></td>
          <td align="center" valign="middle"><table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="center"><input type="button" class="naBtn" name="add" value="Add &gt;&gt;" style="width:100px; " onClick="return addSrcToDestList(1)"></td>
            </tr>
            <tr>
              <td height="5" align="center">&nbsp;</td>
            </tr>
            <tr>
              <td align="center"><input type="button" class="naBtn" name="remove" value="&lt;&lt; Remove" style="width:100px; " onClick=" return deleteFromDestList(1)"></td>
            </tr>
          </table></td>
          <td><span class="element_style">
            <select name="products_related" size="10" multiple id="products_related" style="width:200">
              
          
	{html_options values=$RELPRODUCT.id output=$RELPRODUCT.name selected=$SELECTEDIDS.id}
	   
        
            
            </select>
			<input type="hidden" name="hf1" value="">
          </span></td>
        </tr>
       
		</tr>
        </table>
	 </td>
    </tr>{/if}
<tr> 
      <td colspan=3 valign=center><div align=center>
	  {if $STORE_PERMISSION.edit=='Y'}		

	       <input type=submit value="Submit" class="naBtn"  onClick="chkval()">&nbsp; 
          <input type=reset value="Reset" class="naBtn"><!--&nbsp; 
          <input name="clone" type=submit class="naBtn"  onClick="chkval()" value="Make a Clone">-->
      </div>
	  {/if} 

	  </td> 
 </tr>
	<tr><td colspan=3 valign=center>&nbsp;</td></tr>
</table>
<input type="hidden" name="category_id" value="{$smarty.request.category_id}">
</form>
 {literal}
	<script language="javascript">
	{/literal}
{if $GROUP_ACC && $smarty.request.id}

	{foreach from=$GROUP_ACC item=acc name=acc_loop}
	ViewGroup('{$acc.group_id}','{$acc.category_id}','{$smarty.request.id}','{$acc.store_id}');
	{/foreach}
 {/if}
	{if $SELECTEDSTORE }
	
		{foreach from=$SELECTEDSTORE item=stores name=store_loop}
			ViewBottomGroup('{$stores.store_id}','{if $smarty.request.id}$smarty.request.id{else}0{/if}');
			{foreach from=$stores item=others name=store_loop}
		 		ViewGroup1('{$others.group_id}','{$others.category_id}','{$smarty.request.id}','{$stores.store_id}');
			{/foreach}
		{/foreach}
	{/if}
	document.forms[0].elements[0].focus();
	{literal}
	
	</script>
	{/literal}
	
