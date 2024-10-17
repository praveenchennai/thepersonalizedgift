<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/scripts/validator.js"></SCRIPT>
<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>
<script type="text/javascript" src="{$smarty.const.SITE_URL}/scripts/swfobject.js"></script>
<link type="text/css" href="{$smarty.const.SITE_URL}/templates/default/css/pop-up.css" rel="stylesheet" />	
{literal}
<style type="text/css">

.graytext_fordefault {


color:#808080;

}

.blacktext_fordefault {


color:#000000;

}


</style>

<script language="javascript">
var ns=(document.layers);
var ie=(document.all);
var w3=(document.getElementById && !ie);
var calunit=ns? "" : "px";

function showPopup(static) {
window.open( "https://www.paypal.com/cgi-bin/webscr?cmd=p/sell/mc/mc_wa-outside", "", 
"status = 1, height = 400,scrollbars=1, width = 600,left=300,top=190, resizable = 0" )

}
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
function popupCommon(acc_id,id){
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

	document.getElementById('imageLoader').innerHTML="Loading....";
	
	
	var req1 = newXMLHttpRequest();
	req1.onreadystatechange = getReadyStateHandler(req1, serverRese);
	str="id="+acc_id;
	{/literal}
	req1.open("POST", "{makeLink mod=store pg=art_preview_index}act=preview_new{/makeLink}&"+Math.random());
	{literal}
	req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	req1.send(str);
}
function popup()
{
	acc_id= document.getElementById("art_id").value;
	if(acc_id > 0)
    popupCommon(acc_id,'flash_popup');
//	w=window.open("{/literal}{makeLink mod=store pg=art_preview_index}act=preview&id={literal}"+acc_id+"{/literal}{/makeLink}{literal}", 'w', 'width=740,height=680,scrollbars=yes');
//	w.focus();	

}
function serverRese(_var) { 
	_var = _var.split('~^~');
	var id = _var[1];//alert(_var[1])
	var ext = _var[2];
	document.getElementById('imageLoader').innerHTML=_var[0];
	var flashvars = {prm:id+"."+ext};
		var params = {
		  allowscriptaccess : "always"
		};
		var attributes = {};
		swfobject.embedSWF("{/literal}{$smarty.const.SITE_URL}/{literal}imageloader.swf", "imageLoader", "515", "410", "9.0.0", "expressInstall.swf", flashvars, params, attributes);
	//displayImage11(id,ext)
}
function displayImage(prm)
{
//alert(prm)
document.getElementById('imageLoader').sendTextFromHtml('{/literal}{$smarty.const.SITE_URL}{literal}/modules/product/images/accessory/'+prm);
}

function  showMatPopup()
{
	acc_id= document.getElementById("mat_id").value;
	if(acc_id > 0)
    popupCommon(acc_id,'flash_popup');
//	w=window.open("{/literal}{makeLink mod=store pg=art_preview_index}act=preview&id={literal}"+acc_id+"{/literal}{/makeLink}{literal}", 'w', 'width=740,height=680,scrollbars=yes');
//	w.focus();	

}
function  showFramePopup()
{
	acc_id= document.getElementById("frame_id").value;
	if(acc_id > 0)
	popupCommon(acc_id,'flash_popup');
//	w=window.open("{/literal}{makeLink mod=store pg=art_preview_index}act=preview&id={literal}"+acc_id+"{/literal}{/makeLink}{literal}", 'w', 'width=740,height=680,scrollbars=yes');
//	w.focus();	

}
function  showWoodFramePopup()
{
	acc_id= document.getElementById("frame_id").value;
	if(acc_id > 0)
	 popupCommon(acc_id,'flash_popup');
//	w=window.open("{/literal}{makeLink mod=store pg=art_preview_index}act=preview&id={literal}"+acc_id+"{/literal}{/makeLink}{literal}", 'w', 'width=740,height=680,scrollbars=yes');
//	w.focus();	

}


function  showPoemPopup()
{
	acc_id= document.getElementById("poem_id").value;
	if(acc_id > 0)
	popupCommon(acc_id,'flash_popup');
//	w=window.open("{/literal}{makeLink mod=store pg=art_preview_index}act=preview&id={literal}"+acc_id+"{/literal}{/makeLink}{literal}", 'w', 'width=740,height=680,scrollbars=yes');
//	w.focus();	
	

}

function showAccessory(product_id)
{
	
	
	if(product_id==501)
	{
		document.getElementById("poem_id").value='';
		document.getElementById("poem_select").style.display='none';
		document.getElementById("art_select").style.display='none';
	}
	else
	{
		document.getElementById("art_select").style.display='block';
	}
	
	
	
	if(product_id==496)
	{
		document.getElementById("poem_select").style.display='block';
	}
	else
	{
		document.getElementById("poem_select").style.display='none';
	}
	
	
	
	if(product_id==494 || product_id==495 )
	{
	
		//showFieldsByProduct(product_id,'');
		//document.frmGift.submit();
		// return true;
		document.getElementById("poem_id").value='';

	}
	else
		document.getElementById("show_fields").style.display='none';	
	
}

	var fields=new Array('category','product_id','product_title','product_description','product_basic_price','product_sale_price');
	var msgs=new Array('Gift Category','Gift Type','Product Title','Product Description','Basic price','Sale price');
	

	var nums=new Array('product_basic_price','product_sale_price');
	var nums_msgs=new Array('Basic price should be a number','Sale price should be a number');
	

function frmSubmit()
{
	if (chk(document.frmGift))
		return true;
	else
		return false;
	
}

function CancelArt()
{
	document.getElementById("art_selected").innerHTML = '';
	document.frmGift.art_id.value = '';
	document.getElementById("art_preview").style.visibility= 'hidden';
	document.getElementById("art_sel_btn").style.display='block';
	document.getElementById("art_cal_btn").style.display='none';
}
function CancelMat()
{
		document.getElementById("mat_selected").innerHTML = '';
		document.frmGift.mat_id.value = '';
		document.getElementById("mat_preview").style.visibility= 'hidden';
		document.getElementById("mat_sel_btn").style.display='block';
		document.getElementById("mat_cal_btn").style.display='none';
}

function CanceLFrame()
{
		document.getElementById("frame_selected").innerHTML = '';
		document.frmGift.frame_id.value = '';
		document.frmGift.frame_type.value = '';
		document.getElementById("frame_preview").style.visibility= 'hidden';
		document.getElementById("frame_sel_btn").style.display='block';
		document.getElementById("frame_cal_btn").style.display='none';
}

function canceLWoodFrame()
{
		document.getElementById("woodframe_selected").innerHTML = '';
		document.frmGift.frame_id.value = '';
		document.frmGift.frame_type.value = '';
		document.getElementById("woodframe_preview").style.visibility= 'hidden';
		document.getElementById("woodframe_sel_btn").style.display='block';
		document.getElementById("woodframe_cal_btn").style.display='none';
}



function CanceLPoem()
{
		document.getElementById("poem_selected").innerHTML = '';
		document.frmGift.poem_id.value = '';
		document.getElementById("poem_preview").style.visibility= 'hidden';
		document.getElementById("poem_sel_btn").style.display='block';
		document.getElementById("poem_cal_btn").style.display='none';
		document.getElementById("show_fields").style.display='none';
}


/*function showFieldsByProduct(product_id,acc_id)
{
	var req1  = newXMLHttpRequest();
	
	req1.onreadystatechange = getReadyStateHandler(req1, serverRese3);
	str	 = "product_id="+product_id+"&acc_id="+acc_id;
	//prompt("s",str);
	{/literal}
	req1.open("POST", "{makeLink mod=product pg=index}act=predef_inc_fields{/makeLink}&"+Math.random());
	{literal}
	req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	req1.send(str);
}
function serverRese3(_var) {
		
		document.getElementById('show_fields').innerHTML=_var;
		document.getElementById("show_fields").style.display='block';
	
}*/

function showFieldsByProduct(product_id,acc_id)
{
	
	window.location.href='{/literal}{makeLink mod=store pg=product_index}act=predef_gift&id={$smarty.request.id}{/makeLink}{literal}&product_id='+frmGift.product_id.value+'&category='+document.frmGift.category.value+'&poem_id='+document.frmGift.poem_id.value+'&art_id='+document.frmGift.art_id.value+'&mat_id='+document.frmGift.mat_id.value+'&frame_id='+document.frmGift.frame_id.value;

}

function frmSub()
{
	var str,reurl;
	var url;	
	var s1='';
	var s2='';
	var k=1;
	var m=1;
	if(document.frmGift.opt){
	
	
		var len=document.getElementsByName("opt").length;
		
		for(var i=0;i< len;i++)
		{
			str =encodeURIComponent(document.getElementById("opt"+i).value);
			s1=s1+"&op"+k+"="+str;
			k++;
		}
		document.getElementById("hdOpLine").value=s1;
	}
	if(document.frmGift.col){
		var len2=document.getElementsByName("col").length;
		for(var i=0;i< len2;i++)
		{
			str =encodeURIComponent(document.getElementById("col"+i).value);
			s2=s2+"&cl"+m+"="+str;
			m++;
		}
	}
	if(s1 && s2)
	{
		var opt_val=s1+s2;
		//alert(opt_val);
	}
	else if(s1){
		var opt_val=s1;
	}
	else if	(s2){
		var opt_val=s2;
	}
	alert(opt_val);
	
}

function closeAd_new(id){
if (ie||w3){
	//adDiv.display="none";
	document.getElementById(id).style.display="none";
}else{
	document.getElementById(id).style.visibility ="hide";}
}
function doClear(theText) {
     if (theText.value == theText.defaultValue) {
         theText.value = ""
		 theText.className = "blacktext_fordefault";
     }
 }
 function doReload(theText) {
     if (theText.value == "") {
       theText.value = theText.defaultValue
		 theText.className = "graytext_fordefault";
    }
 }


</script>
{/literal}

<table width="820" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table width="80%"  border="0" cellpadding="0" cellspacing="0">
<tr>
    <td valign="top">
    <form action="" method="post" enctype="multipart/form-data" name="frmGift" >
<table width="100%" border=0 align="center" cellpadding="0" cellspacing="1" class=naBrDr> 


	<input type="hidden" name="art_id" id="art_id" value="{$smarty.request.art_id}" />
	<input type="hidden" name="mat_id" id="mat_id" value="{$smarty.request.mat_id}" />
	<input type="hidden" name="frame_id" id="frame_id" value="{$smarty.request.frame_id}" />
	<input type="hidden" name="poem_id" id="poem_id" value="{$smarty.request.poem_id}" />
	<input type="hidden" name="frame_type" id="frame_type" value="{$smarty.request.frame_type}" />

    <tr align="left">
      <td colspan=3 valign=top>
          <table width="100%" align="center">
        <tr>
          <td width="6%" nowrap class="naH1">Predefined Gift&nbsp;
		  
		  <div class="shadow01" style="display:none;" id="html_popup">
		<div class="container">
		<div class="close_bar">
			<a href="javascript:void(0);" onClick="closeAd_new('html_popup');"><img src="{$smarty.const.SITE_URL}/templates/default/images/close_new.png" width="80" height="29" border="0" class="fr" /></a>		</div>
		<div class="image_holder" id="htmlLoader" style="overflow:auto">
		
		<table width="470" border="0" cellspacing="1" cellpadding="0">
		  <tr>
		    <td colspan="2"><b>Pre-defined Gifts</b></td>
  </tr>
		  <tr>
		    <td colspan="2">&nbsp;</td>
  </tr>
		  <tr>
		    <td colspan="2"><p>The pre-defined gifts feature will provide you the flexibility to design gifts and display them in your web-store. Your customers will then be able to quickly choose a pre-designed gift, add their personalization, and check-out.</p></td>
  </tr>
		  <tr>
		    <td colspan="2">&nbsp;</td>
  </tr>
		  <tr>
			<td colspan="2"><b>Pre-defined Gifts Details</b></td>
		  </tr>
		   <tr>
			<td colspan="2">&nbsp;</td>
			 <tr>
			<td width="16" valign="top">1. </td>
		    <td width="454" valign="top">Create Single Name gifts, Double Name Gifts, and Poetry Gifts.</td>
		  </tr>
		   <tr>
			<td valign="top">2. </td>
		    <td><p>Choose art backgrounds, mats and or frames. Select the first names, poems and the personalization too.</p></td>
	      </tr>
		  <tr>
			<td valign="top">3. </td>
		    <td>Create and display as many pre-defined gifts as you like for each of the Special Occasion gift categories.</td>
		  </tr>
		  <tr>
			<td valign="top">4. </td>
		    <td>Set the price and optionally the sales price for predefined gifts.</td>
		  </tr>
		  <tr>
			<td colspan="2">&nbsp;</td>
		  </tr>
		  <tr>
			<td colspan="2"><b>Important Notes:</b></td>
		  </tr>
		  <tr>
			<td colspan="2">&nbsp;</td>
		  </tr>
		  <tr>
			<td colspan="2" align="justify"><p>Your predesigned gifts will display in the Special Occasion category that you specified.</p>
<p>
You can also choose to display any of the pre-defined gifts in the Featured Products section by selecting them in "Featured Gifts Display Order" menu item. Change the gifts and the display order for all of the Featured Products gifts section. Target the upcoming holiday by displaying appropriate gifts at the top of this section. Valentines Day, Mother's Day, Father's Day, Graduation, etc... the choice is yours.</p>
<p>
The shipping cost for pre-defined gifts is the same as other gifts. It is determined by adding the "shipping price" for all of the components used to create that gift. Example: For an 11x14 framed and matted gift, the web-store will add up the gift-shipping-price +  mat-shipping-price + 11x14-frame-shipping-price.</p>
<p>
Be sure to design some new gifts, then visit your web-store store, place an order, and proceed to the checkout page to verify that your pre-defined gifts are working as anticipated.</p></td>
		  </tr>
</table>

		</div>
		</div>
		</div>		  </td>
          <td align="left" valign="middle" width="17%" nowrap  ><a href="javascript:void(0);" onclick="htmlpopup('html_popup');" ><img src="{$smarty.const.SITE_URL}/templates/blue/images/icon_small_info.gif" title="" border="0" ></a></td>
          <td width="77%" align="right"  class="titleLink">&nbsp;<a href="{makeLink mod=store pg=product_index}act=predef_gift_list{/makeLink}&store_id={$smarty.request.store_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}&product_search={$smarty.request.product_search}&category_id={$smarty.request.category_id}&limit={$smarty.request.limit}&pageNo={$smarty.request.pageNo}">List</a></td>
        </tr>
      </table></td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3>
		<div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>      </td>
    </tr>
    {/if}
	<tr valign="middle" >
      <td class="naGridTitle" height="25" nowrap="nowrap" colspan=3 >&nbsp;<strong>Gift</strong></td>
    </tr>
	  <tr valign="middle"  class="naGrid1">
      <td width="308" height="25"  align="right"><span class="fieldname">Gift Category:</span></td>
      <td width="15" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <select name="category"  style="width:200px">
	 	<option value="">-Select-</option>
		{foreach from=$CATEGORY item=giftcat}
			<option value="{$giftcat->category_id}" {if $smarty.request.category eq $giftcat->category_id} selected="selected"{/if} >{$giftcat->category_name}</option>
		{/foreach}
	</select>
      </span></td>
    </tr>
    <tr valign="middle" class="naGrid2">
      <td width="308" height="25"  align="right"><span class="fieldname">Gift Type:</span></td>
      <td width="15" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <select name="product_id"  style="width:200px" onchange=" showAccessory(this.value);window.location.href='{makeLink mod=store pg=product_index}act=predef_gift&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}&product_id='+this.value+'&category='+document.frmGift.category.value+'&art_id='+document.frmGift.art_id.value+'&mat_id='+document.frmGift.mat_id.value+'&frame_id='+document.frmGift.frame_id.value+'&poem_id='+document.frmGift.poem_id.value+'&product_title='+document.frmGift.product_title.value+'&product_description='+document.frmGift.product_description.value+'&product_basic_price='+document.frmGift.product_basic_price.value+'&product_sale_price='+document.frmGift.product_sale_price.value{if $smarty.request.id}+'&id='+{$smarty.request.id}{/if};">
	 	<option value="">-Select-</option>
		{foreach from=$PRODUCTS item=temp}
			<option value="{$temp->id}" {if $smarty.request.product_id eq $temp->id} selected="selected"{/if}>{$temp->name}</option>
		{/foreach}
	</select>
      </span></td>
	  <tr valign="middle" >
      <td class="naGridTitle" height="25" nowrap="nowrap" colspan=3 >&nbsp;<strong>Gift-&nbsp;Sub Products</strong></td>
    </tr>
	 <tr  class="{cycle values="naGrid1,naGrid2"}">
	 		<td colspan="3"  ><div style="display:{if $POEM_DET}block{elseif $smarty.request.product_id eq 496}block{else}none{/if}" id="poem_select">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
			 <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Select a Poem:</span></td>
      <td width="15" height="25">&nbsp;</td>
      <td width="442" height="25" align="left">
	  
	  <table width="90%" border="0" cellpadding="0"  cellspacing="1" >
  <tr>
    <td width="400">
	 <div id="poem_selected" style="float:left">&nbsp;{$POEM_DET.name}</div></td>
    <td><a href="javascript:void(0);" onclick="showPoemPopup();" id="poem_preview" style="visibility:{if $POEM_DET}visible{else}hidden{/if};"><img src="{$GLOBAL.tpl_url}/images/grid/explore_next_level.gif" title="Preview" border="0" ></a></td>
    <td><input type="button" class="naBtn" value="Select"  id="poem_sel_btn" onclick="w=window.open('{makeLink mod=store pg=accessory_popup_index}act=artbackground_list&aid=174{/makeLink}', 'w', 'width=850,height=730,scrollbars=yes');w.focus();" style="display:{if $POEM_DET}none{else}block{/if}; width:62px"  />
	 <input type="button" id="poem_cal_btn"  class="naBtn" value="Cancel " onclick="CanceLPoem();"   style="display:{if $POEM_DET}block{else}none{/if}"   />
	
	</td>
  </tr>
</table>

	 </td>
    </tr>
			</table></div></td>
	 </tr> 
	  
	<tr>
		<td  colspan="3" style="position:relative;"><div style="display:block" id="art_select">
                <table border="0" width="100%" cellpadding="0" cellspacing="1"  >


		<tr valign="middle"  class="{cycle values="naGrid1,naGrid2"}" >
      <td width="308" height="25"  align="right"><span class="fieldname">Select an Art Background:</span></td>
      <td width="15" height="25" >&nbsp;</td>
      <td width="442" height="25" >
	  <table width="90%" border="0" cellpadding="0"  cellspacing="1"  >
  <tr>
    <td width="400">
	 <div id="art_selected" style="float:left">&nbsp;{$ART_DET.name}</div></td>
    <td><a href="javascript:void(0);" onclick="popup();" id="art_preview" style="visibility:{if $ART_DET}visible{else}hidden{/if};"><img src="{$GLOBAL.tpl_url}/images/grid/explore_next_level.gif" title="Preview" border="0" ></a></td>
    <td><input id="art_sel_btn" type="button" class="naBtn" value="Select" onclick="w=window.open('{makeLink mod=store pg=accessory_popup_index}act=artbackground_list&aid=203{/makeLink}', 'w', 'width=850,height=730,scrollbars=yes');w.focus();" style="display:{if $ART_DET}none{else}block{/if}; width:61px"   />
	<input id="art_cal_btn" style="display:{if $ART_DET}block{else}none{/if}; width:61px" type="button" class="naBtn" value="Cancel "  onclick="CancelArt();"  />
	
	</td>
  </tr>
</table>
	
    </tr>
		</table></div> 
		
		<div class="shadow" style="display:none;" id="flash_popup">
		<div class="container">
		<div class="close_bar">
			<a href="javascript:void(0);" onClick="closeAd_new('flash_popup');"><img src="{$smarty.const.SITE_URL}/templates/default/images/close_new.png" width="80" height="29" border="0" class="fr" /></a>
		</div>
		<div class="image_holder" id="imageLoader"></div>
		</div>
		</div>
		
		</td>	
	</tr>
	
	
	 <tr >
		<td  colspan="3"  ><div style="display:block" id="art_select">
                <table border="0" width="100%" cellpadding="0" cellspacing="1">
		<tr valign="middle"  class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Select a Mat:</span></td>
      <td width="15" height="25"  >&nbsp;</td>
      <td width="442" height="25" >
	  <table width="90%" border="0" cellpadding="0"  cellspacing="0" >
  <tr>
    <td width="400"> <div id="mat_selected" style="float:left">&nbsp;{$MAT_DET.name}</div></td>
    <td><a href="javascript:void(0);" onclick="showMatPopup();" id="mat_preview" style="visibility:{if $MAT_DET}visible{else}hidden{/if};"><img src="{$GLOBAL.tpl_url}/images/grid/explore_next_level.gif" title="Preview" border="0" ></a></td>
    <td><input id="mat_sel_btn" type="button" class="naBtn" value="Select" onclick="w=window.open('{makeLink mod=store pg=accessory_popup_index}act=artbackground_list&aid=235{/makeLink}', 'w', 'width=850,height=730,scrollbars=yes');w.focus();" style="display:{if $MAT_DET}none{else}block{/if}; width:62px"   />
	
	<input id="mat_cal_btn" style="display:{if $MAT_DET}block{else}none{/if}"  type="button" class="naBtn" value="Cancel " onclick="CancelMat();"  />
	
	</td>
  </tr>
</table>
	
    </tr>
		</table></div></td>	
	</tr>

	 <tr>
		<td  colspan="3"  ><div style="display:block" id="art_select">
                <table border="0" width="100%" cellpadding="0" cellspacing="1">
		<tr valign="middle"  class="{cycle values="naGrid1,naGrid2"}" >
      <td width="308" height="25"  align="right"><span class="fieldname">Select a Wood/Plaque Frame - 8.5x11:</span></td>
      <td width="15" height="25"  >&nbsp;</td>
      <td width="442" height="25" >
	  <table width="90%" border="0" cellpadding="0"  cellspacing="0" >
  <tr>
    <td width="400"> <div id="frame_selected" style="float:left">&nbsp;{$FRAME_DET.name}</div></td>
    <td><a href="javascript:void(0);" onclick="showFramePopup();" id="frame_preview" style="visibility:{if $FRAME_DET}visible{else}hidden{/if};"><img src="{$GLOBAL.tpl_url}/images/grid/explore_next_level.gif" title="Preview" border="0" ></a></td>
    <td><input id="frame_sel_btn" type="button" class="naBtn" value="Select" onclick="w=window.open('{makeLink mod=store pg=accessory_popup_index}act=artbackground_list&aid=245{/makeLink}', 'w', 'width=850,height=730,scrollbars=yes');w.focus();"  style="display:{if $FRAME_DET}none{else}block{/if}; width:62px"   />
	<input id="frame_cal_btn"  style="display:{if $FRAME_DET}block{else}none{/if}"  type="button" class="naBtn" value="Cancel " onclick="CanceLFrame();"  />
	
	</td>
  </tr>
</table>
	
    </tr>
		</table></div></td>	
	</tr>
	
	<tr>
		<td  colspan="3"  ><div style="display:block" id="art_select">
                <table  width="100%" border="0" cellpadding="0" cellspacing="1">
		<tr valign="middle"  class="{cycle values="naGrid1,naGrid2"}" >
      <td width="308" height="25"  align="right"><span class="fieldname">Select a Wood Frame - 11x14:</span></td>
      <td width="15" height="25"  >&nbsp;</td>
      <td width="442" height="25" >
	  <table width="90%" border="0" cellpadding="0"  cellspacing="0" >
  <tr>
    <td width="400"> <div id="woodframe_selected" style="float:left">&nbsp;{$WOODFRAME_DET.name}</div></td>
    <td><a href="javascript:void(0);" onclick="showWoodFramePopup();" id="woodframe_preview" style="visibility:{if $WOODFRAME_DET}visible{else}hidden{/if};"><img src="{$GLOBAL.tpl_url}/images/grid/explore_next_level.gif" title="Preview" border="0" ></a></td>
    <td><input id="woodframe_sel_btn" type="button" class="naBtn" value="Select" onclick="w=window.open('{makeLink mod=store pg=accessory_popup_index}act=artbackground_list&aid=252{/makeLink}', 'w', 'width=850,height=730,scrollbars=yes');w.focus();"  style="display:{if $WOODFRAME_DET}none{else}block{/if}; width:62px"   />
	
	<input id="woodframe_cal_btn"  style="display:{if $WOODFRAME_DET}block{else}none{/if}"  type="button" class="naBtn" value="Cancel " onclick="canceLWoodFrame();"  />
	
	</td>
  </tr>
</table>
	
    </tr>
		</table></div></td>	
	</tr>
	
	<tr class="naGrid2">
		<td  colspan="3"  ><div style="display:block" id="show_fields">
		{if $PRODUCT_NAME eq 'Single Name Gift'}		
		
	<table border="0" cellpadding="0" cellspacing="1" width="100%">
	
	 <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td class="naGridTitle" height="25" nowrap="nowrap" colspan=3 >&nbsp;<strong>Gift-&nbsp;Name|Gender|Language</strong></td>
    </tr>
	
	 <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="306" height="25"  align="right"><span class="fieldname">Enter Name:</span></td>
      <td width="18" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input  type="text" name="first_name" id="first_name" value="{$smarty.request.first_name}" size="40"  />
      </span></td>
    </tr>
	 <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Gender:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <select name="gender" id="gender" style="width:200px">
	 	<option value="">-Select-</option>
	 	<option value="M" {if $smarty.request.gender eq 'M' or $smarty.request.gender eq ''} selected="selected"{/if}>Male</option>
		<option value="F"  {if $smarty.request.gender eq 'F'} selected="selected"{/if}>Female</option>
	 </select>
      </span></td>
    </tr>
	<tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Language:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <select name="lang" id="lang" style="width:200px">
	 	<option value="1" {if $smarty.request.lang eq 1 or $smarty.request.lang eq ''} selected="selected"{/if}>English</option>
		<option value="2" {if $smarty.request.lang eq 2 } selected="selected"{/if}>Spanish</option>
	 </select>
      </span></td>
    </tr>
	 <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td class="naGridTitle" height="25" nowrap="nowrap" colspan=3 >&nbsp;<strong>Gift-&nbsp;Sentiments</strong></td>
    </tr>
	
	 <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Sentiment Line 1:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input  type="text" name="sentiment1" id="sentiment1" value="{$smarty.request.sentiment1}" size="40" maxlength="200"  />
      </span></td>
    </tr>
	
	 <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Sentiment Line 2:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input   type="text" name="sentiment2" id="sentiment2" value="{$smarty.request.sentiment2}" size="40" maxlength="200"  />
      </span></td>
    </tr>
	
		</table>
		
	
		{elseif $PRODUCT_NAME eq 'Double Name Gift'}
		<table cellpadding="0" cellspacing="1" border="0" width="100%">
		<tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td class="naGridTitle" height="25" nowrap="nowrap" colspan=3 >&nbsp;<strong>Gift-&nbsp;Name|Gender|Language</strong></td>
    </tr>
<tr valign="middle"  class="{cycle values="naGrid1,naGrid2"}">
      <td width="300" height="25"  align="right"><span class="fieldname">Enter Name 1:</span></td>
      <td width="15" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input  type="text" name="first_name" id="first_name" value="{$smarty.request.first_name}" size="40" />
      </span></td>
    </tr>
	<tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Gender 1:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <select name="gender" id="gender" style="width:200px">
	 	<option value="">-Select-</option>
	 	<option value="M" {if $smarty.request.gender eq 'M' or $smarty.request.gender eq ''} selected="selected"{/if}>Male</option>
		<option value="F"  {if $smarty.request.gender eq 'F'} selected="selected"{/if}>Female</option>
	 </select>
      </span></td>
    </tr>
	<tr valign="middle"  class="{cycle values="naGrid1,naGrid2"}">
      <td width="300" height="25"  align="right"><span class="fieldname">Enter Name 2:</span></td>
      <td width="15" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input  type="text" name="first_name2" id="first_name2" value="{$smarty.request.first_name2}" size="40"   />
      </span></td>
    </tr>
	<tr valign="middle"  class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Gender 2:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <select name="gender2" id="gender2" style="width:200px" >
		 <option value="">-Select-</option>
	 	<option value="M" {if $smarty.request.gender2 eq 'M' or $smarty.request.gender2 eq ''} selected="selected"{/if}>Male</option>
		<option value="F"  {if $smarty.request.gender2 eq 'F'} selected="selected"{/if}>Female</option>
	 </select>
      </span></td>
    </tr>
	<tr valign="middle"  class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Language:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <select name="lang" id="lang" style="width:250px">
	 	<option value="1" {if $smarty.request.lang eq 1 or $smarty.request.lang eq ''} selected="selected"{/if}>English</option>
		<option value="2" {if $smarty.request.lang eq 2 } selected="selected"{/if}>Spanish</option>
	 </select>
      </span></td>
    </tr>
		  <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td class="naGridTitle" height="25" nowrap="nowrap" colspan=3 >&nbsp;<strong>Gift-&nbsp;Sentiments</strong></td>
    </tr>
	 
	 <tr valign="middle"  class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Sentiment Line 1:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input  type="text" name="sentiment1" id="sentiment1" value="{$smarty.request.sentiment1}" size="40" maxlength="200"  />
      </span></td>
    </tr>
	
	 <tr valign="middle"  class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Sentiment Line 2:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input  type="text" name="sentiment2" id="sentiment2" value="{$smarty.request.sentiment2}" size="40" maxlength="200"  />
      </span></td>
    </tr>
	  
	
	</table>
	{elseif $PRODUCT_NAME eq 'Poetry Gift'}
		
		<table cellpadding="0" cellspacing="1" border="0" width="100%">

{section name=op loop=$OP_COUNT}

<tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="310" height="25"  align="right"><span class="fieldname">Opening Line {$smarty.section.op.iteration}:</span></td>
      <td width="16" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input  type="text" class="graytext_fordefault"  onblur="javascript : doReload(this);" onfocus="javascript : doClear(this)" name="opt[]" id="opt{$smarty.section.op.index}" value="{foreach from=$OP_ARRAY item=op name=oploop}{if $smarty.section.op.index eq $smarty.foreach.oploop.index}{$op}{/if}{/foreach}" size="40" maxlength="255"  />
      </span></td>
    </tr>
{/section}	
{section name=cl loop=$CL_COUNT}

<tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="310" height="25"  align="right"><span class="fieldname">Closing Line {$smarty.section.cl.iteration}:</span></td>
      <td width="14" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input  type="text" class="graytext_fordefault" class="blacktext_fordefault"  onblur="javascript : doReload(this);" onfocus="javascript : doClear(this)" name="col[]" id="col{$smarty.section.cl.index}" value="{foreach from=$CL_ARRAY item=cl name=cloop}{if $smarty.section.cl.index eq $smarty.foreach.cloop.index}{$cl}{/if}{/foreach}" size="40" maxlength="255"  />
      </span></td>
    </tr>
{/section}	

	</table>
		
		
		{/if}
		
		
		
		</div></td>	
	</tr>
	
	
	<tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td class="naGridTitle" height="25" nowrap="nowrap" colspan=3 >&nbsp;<strong>Gift-&nbsp;Product Details</strong></td>
    </tr>
	

	 <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Product Title:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input  type="text" name="product_title" id="product_title"  size="40"  value="{$smarty.request.product_title}" />
      </span></td>
    </tr>
	
	
	 <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Description:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <textarea rows="10" name="product_description" id="product_description"  cols="37">{$smarty.request.product_description}</textarea>
      </span></td>
    </tr>
	 <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Base price($):</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input  type="text" name="product_basic_price" id="product_basic_price" maxlength="10"  size="40" value="{$smarty.request.product_basic_price}" />
      </span></td>
    </tr>
	 <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Sale price($):</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input  type="text" name="product_sale_price" id="product_sale_price" maxlength="10"  size="40" value="{$smarty.request.product_sale_price}" />
      </span></td>
    </tr>
	
			
	<tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Active:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left" valign="middle"><span class="formfield">
	<input type="radio" name="product_active" value="Y" {if $smarty.request.product_active eq 'Y' or $smarty.request.active eq ''} checked="checked" {/if} />Yes 
	<input type="radio" name="product_active" value="N"  {if $smarty.request.product_active eq 'N'  } checked="checked" {/if} />No
        
      </span></td>
    </tr>
    
   <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Meta Title:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input  type="text" name="meta_title" id="meta_title" maxlength="255"  size="40" value="{$smarty.request.meta_title}" />
      </span></td>
    </tr>
	 <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Meta Keyword:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 	 <textarea rows="5" name="meta_keyword" id="meta_keyword"  cols="37">{$smarty.request.meta_keyword}</textarea>
      </span></td>
    </tr>
	 <tr valign="middle" class="{cycle values="naGrid1,naGrid2"}">
      <td width="308" height="25"  align="right"><span class="fieldname">Meta Description:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 	 <textarea rows="5" name="meta_description" id="meta_description"  cols="37">{$smarty.request.meta_description}</textarea>
      </span></td>
    </tr>
    <tr class="naGridTitle" height="25"> 
      <td colspan=3 valign=center ><div align=center>	  
	       <input type=submit name="submit" value="Submit" class="naBtn" onclick="">&nbsp;
          <input type=reset value="Reset" class="naBtn"> 
        </div>	 </td> 
    </tr> 
	<tr><td colspan=3 valign=center>&nbsp;</td></tr> 

</table>
    </form>
    </td>
  </tr>
</table>
