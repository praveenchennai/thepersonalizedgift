<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/scripts/validator.js"></SCRIPT>
<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>
<script type="text/javascript" src="{$smarty.const.SITE_URL}/scripts/swfobject.js"></script>
<link type="text/css" href="{$smarty.const.SITE_URL}/templates/default/css/pop-up.css" rel="stylesheet" />	

{literal}

<script language="javascript">
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
function deleteSelected()
{
	var count1=0;
	
		for (var i=0;i<frmGift.elements.length;i++)
			{
			var e = frmGift.elements[i];
					
					if(e.name=='id[]')
					{
						if(e.checked==true)
						{
						count1++;
						}
					}
				
			
			}
		if(count1==0){
		alert("Please select one Gift");
		return false;
		}
	
	
	if(confirm('Are you sure to delete selected Gift?'))
	{
		document.frmGift.action='{/literal}{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=predef_gift_delete{/makeLink}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}&product_search={$smarty.request.product_search}&category_id={$smarty.request.category_id}&limit={$smarty.request.limit}&pageNo={$smarty.request.pageNo}{literal}'; 
		document.frmGift.submit();
	}
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
	req1.open("POST", "{makeLink mod=store pg=art_preview_index}act=img_view_new{/makeLink}&"+Math.random());
	{literal}
	req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	req1.send(str);
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
document.getElementById('imageLoader').sendTextFromHtml('{/literal}{$smarty.const.SITE_URL}{literal}/modules/ajax_editor/images/'+prm);
}
function closeAd_new(id){
if (ie||w3){
	//adDiv.display="none";
	document.getElementById(id).style.display="none";
}else{
	document.getElementById(id).style.visibility ="hide";}
}
function imgPopup(id)
{
popupCommon(id,'flash_popup');
//	w=window.open("{/literal}{makeLink mod=store pg=art_preview_index}act=img_view&id={literal}"+id+"{/literal}{/makeLink}{literal}", 'w', 'width=750,height=680,scrollbars=yes');
	//w.focus();	
}


</script>
{/literal}

<form action="" method="post" enctype="multipart/form-data" name="frmGift" >
 <input type="hidden" name="limit"  value="{$smarty.request.limit}"/>
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table border="0" cellspacing="0" cellpadding="4" width="100%" align="center"> 
        <tr>
		<td nowrap class="naH1" width="5%">Predefined Gifts
		<div class="shadow01" style="display:none;" id="html_popup" >
		<div class="container">
		<div class="close_bar">
			<a href="javascript:void(0);" onClick="closeAd_new('html_popup');"><img src="{$smarty.const.SITE_URL}/templates/default/images/close_new.png" width="80" height="29" border="0" class="fr" /></a>		</div>
		<div class="image_holder" id="htmlLoader"  style="overflow:auto">
		
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
		</div>
		</td> 
		
          <td nowrap ><a href="javascript:void(0);" onclick="htmlpopup('html_popup');" ><img src="{$smarty.const.SITE_URL}/templates/blue/images/icon_small_info.gif" title="" border="0" ></a></td>
          <td nowrap align="right" class="titleLink" width="100%" height="40"><a href="{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=predef_gift{/makeLink}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}&product_search={$smarty.request.product_search}&category_id={$smarty.request.category_id}&limit={$smarty.request.limit}&pageNo={$smarty.request.pageNo}">Add New</a></td> 
        </tr>
		
        <tr>
          <td colspan="3" align="left" class="naGrid1">
		  
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
  <tr>
    <td> {if count($PREDFD_GIFT_LIST) > 0}<a onclick="javascript:deleteSelected();" href="#" class="linkOneActive">Delete</a>{/if}</td>
   	<td align="center">Category&nbsp;:</td>
	  <td>
	 	 <select name=category_id onchange="window.location.href='{makeLink mod=$MOD pg=$PG}act=predef_gift_list{/makeLink}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&brandid={$smarty.request.brandid}&zoneid={$smarty.request.zoneid}&product_search={$smarty.request.product_search}&pageNo={$smarty.request.pageNo}&limit='+document.frmGift.limit.value+'&category_id='+this.value">
     	   <option value="">{$SELECT_DEFAULT}</option>
      	  {html_options values=$CATEGORY_PARENT.category_id output=$CATEGORY_PARENT.category_name selected=`$smarty.request.category_id`}
		 </select>		 </td>
		  <td><input type="text" name="product_search" value="{$PRODUCT_SEARCH_TAG}" size="15"></td>
	<td><input name="btn_search" type="submit" class="naBtn" value="Search"></td>
	 <td>&nbsp;</td>
    <td nowrap>Results per page:</td>
	<td>{$PREDFD_GIFT_LIMIT}</td>
    <td>&nbsp;</td>
  </tr>
</table>		 </td>
        </tr> 
      </table>
	 </td> 
  </tr> 

  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
        {if count($PREDFD_GIFT_LIST) > 0}
        <tr>
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"><input type="checkbox" onclick="javascript:CheckCheckAll(document.frmGift,'id[]')" name="select_all"/></td> 
		    <td width="8%" nowrap class="naGridTitle" height="24" align="center">View</td> 
          <td width="8%" nowrap class="naGridTitle" height="24" align="center">Edit</td> 
          <td width="20%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="mc.category_name" display="Gift Category"}act={$smarty.request.act}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&product_search={$smarty.request.product_search}&category_id={$smarty.request.category_id}&limit={$smarty.request.limit}&pageNo={$smarty.request.pageNo}{/makeLink}</td> 
		  <td width="30%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="product_title" display="Product Title"}act={$smarty.request.act}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&product_search={$smarty.request.product_search}&category_id={$smarty.request.category_id}&limit={$smarty.request.limit}&pageNo={$smarty.request.pageNo}{/makeLink}</td> 
          <td width="20%" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="product_sale_price" display="Sale Price"}act={$smarty.request.act}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&product_search={$smarty.request.product_search}&category_id={$smarty.request.category_id}&limit={$smarty.request.limit}&pageNo={$smarty.request.pageNo}{/makeLink}</td>
		  <td width="30%" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="product_basic_price" display="Base Price"}act={$smarty.request.act}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&product_search={$smarty.request.product_search}&category_id={$smarty.request.category_id}&limit={$smarty.request.limit}&pageNo={$smarty.request.pageNo}{/makeLink}</td>
          <td width="10%" nowrap class="naGridTitle" align="center">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="active" display="Active"}act={$smarty.request.act}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&product_search={$smarty.request.product_search}&category_id={$smarty.request.category_id}&limit={$smarty.request.limit}&pageNo={$smarty.request.pageNo}{/makeLink}</td>
        </tr>
		
        {foreach from=$PREDFD_GIFT_LIST item=row}
        <tr class="{cycle values="naGrid1,naGrid2"}">
		   <td valign="middle" height="24" align="center"><input type="checkbox" value="{$row.id}" name="id[]"/></td> 
		   <td valign="middle" height="24" ><a class="linkOneActive" href="javascript:void(0);" onclick="imgPopup('{$row.id}');"><img src="{$GLOBAL.tpl_url}/images/grid/explore_next_level.gif" title="View" border="0" ></a>
		   
		   
		  <div class="shadow" style="display:none;" id="flash_popup">
		<div class="container">
		<div class="close_bar">
			<a href="javascript:void(0);" onClick="closeAd_new('flash_popup');"><img src="{$smarty.const.SITE_URL}/templates/default/images/close_new.png" width="80" height="29" border="0" class="fr" /></a>
		</div>
		<div class="image_holder" id="imageLoader"></div>
		</div>
		</div>
		   
		   
		   </td> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=predef_gift&id={$row.id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&product_search={$smarty.request.product_search}&category_id={$smarty.request.category_id}&limit={$smarty.request.limit}&pageNo={$smarty.request.pageNo}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
         <td height="24" align="left" valign="middle">{$row.category_name}</td>
		  <td height="24" align="left" valign="middle">{$row.product_title}</td> 
          <td height="24" align="left" valign="middle">{if $row.product_sale_price gt 0}${$row.product_sale_price}{/if}</td> 
		 <td height="24" align="left" valign="middle">${$row.product_basic_price}</td>  
          <td height="24" align="center" valign="middle"> {if $row.active eq 'Y'} 
			<img border="0" title="Activate"  src="{$smarty.const.SITE_URL}/framework/includes/images/active{$row.active.gif}.gif"/>
					<a class="linkOneActive"  href="{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=predef_status&id={$row.id}&current_act=Y&sId={$smarty.request.sId}&fId={$smarty.request.fId}&product_search={$smarty.request.product_search}&category_id={$smarty.request.category_id}&limit={$smarty.request.limit}&pageNo={$smarty.request.pageNo}{/makeLink}"><img border="0" title="Deactivate"  src="{$smarty.const.SITE_URL}/framework/includes/images/deactive{$row.active.gif}.gif"/></a>
		{else}
				<a class="linkOneActive"  href="{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=predef_status&id={$row.id}&current_act=N&sId={$smarty.request.sId}&fId={$smarty.request.fId}&product_search={$smarty.request.product_search}&category_id={$smarty.request.category_id}&limit={$smarty.request.limit}&pageNo={$smarty.request.pageNo}{/makeLink}"><img border="0" title="Activate"  src="{$smarty.const.SITE_URL}/framework/includes/images/active{$row.active.gif}.gif"/></a>
			<img border="0" title="Deactive"  src="{$smarty.const.SITE_URL}/framework/includes/images/deactive{$row.active.gif}.gif"/>
			
		{/if}</td> 
		  
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="5" class="msg" align="center" height="30">{$PREDFD_GIFT_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="5" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>
</form>