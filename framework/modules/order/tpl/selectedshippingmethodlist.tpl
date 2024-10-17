<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
{literal}
<script language="javascript">
 function divVisible(val,param)
 {
 	if(val==true && (param=='1' || param=='2'))
	{
		document.getElementById('div_msg').style.display='block';
		document.getElementById('div_msg1').style.display='none';
		document.getElementById('intr_div').style.display='none';
	}
	else 
	{
		document.getElementById('div_msg').style.display='none';
		document.getElementById('div_msg1').style.display='block';
		document.getElementById('intr_div').style.display='block';
	}
 }
 
 
 function valButton(btn) {
    var cnt = -1;
    for (var i=btn.length-1; i > -1; i--) {
        if (btn[i].checked) {cnt = i; i = -1;}
    }
    if (cnt > -1) return btn[cnt].value;
    else return null;
}
   
 function sub_check()
 {
 var btn = valButton(document.frm_message.intr_frs_status);
if (btn == null){
 alert('No International Orders selected')
 return false;
}
 }
</script>
{/literal}
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table  width="100%" border="0">
<tr><td>
{if count($SHIPMETHODLIST) > 0}

<form name="frm_made" method="post" action="" enctype="multipart/form-data" style="margin: 0px;">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
	 <input type="hidden" name="limit"  value="{$smarty.request.limit}"/>

<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center">
        <tr> 
          <td nowrap class="naH1" > Shipping Methods 
		   </td> 
          <td nowrap align="right" class="titleLink">&nbsp;<!--<a href="{makeLink mod=order pg=shipping}act=shipformform&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}">Add New Shipping Method</a>--></td> 
        </tr>
        <tr>
          <td nowrap >&nbsp;</td>
          <td nowrap align="right" class="titleLink">&nbsp;</td>
        </tr> 
      </table></td> 
  </tr>
  
  <tr>
    <td width="30" class="naGrid1"><table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="8%" align="right">{if $STORE_OWNER eq "store"}Store:{/if}</td>
    <td width="57%" nowrap>{if $STORE_OWNER eq "store"}
<select name=store_id onchange="window.location.href='{makeLink mod=order pg=shipping}act=shippingselmethodslist&limit={$smarty.request.limit}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}&store_id='+this.value">
	{html_options values=$STORES.id output=$STORES.name selected=`$SELECTED_STORE_ID`}
</select>
	
	{/if}</td>
	<td width="21%">{if $STORE_OWNER eq "admin"} Shipping By main store{/if}
	  </td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td width="30"><br><br></td>
  </tr> 
  {/if}
  <tr> 
    <td><table width=100% border=0 cellpadding="0" cellspacing="0"> 
		{if count($SHIPMETHODLIST) > 0}
	    <tr>
	      <td width="15%" align="center" nowrap class="naGridTitle">Selected</td>
          <td width="20%" height="24" align="left" nowrap class="naGridTitle">Name</td>
		  <td width="40%" align="left" nowrap class="naGridTitle">Description</td>
		  <td width="25%" align="left" nowrap class="naGridTitle">&nbsp;</td>
	    </tr>
        {foreach from=$SHIPMETHODLIST item=shipmethod}
        <tr class="{cycle name=bg values="naGrid1,naGrid2"}">
          <td  valign="middle"  align="center">
{if $shipmethod.selected eq 'Y' } 
	<img border="0" title="Activate"  src="{$smarty.const.SITE_URL}/framework/includes/images/active{$shipmethod.selected}.gif"/>
	<a href="{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=unselectshippingmethod&shipmethod_id={$shipmethod.shipmethod_id}&store_id={$SELECTED_STORE_ID}&limit={$smarty.request.limit}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}" onClick="return confirm('Are you sure you want to deactivate this shipping method?');"><img border="0" title="Deactivate"  src="{$smarty.const.SITE_URL}/framework/includes/images/deactive{$shipmethod.selected}.gif"/></a>
{else}
<a href="{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=selectshippingmethod&shipmethod_id={$shipmethod.shipmethod_id}&store_id={$SELECTED_STORE_ID}&limit={$smarty.request.limit}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}" onClick="return confirm('Are you sure you want to activate this shipping method?');"><img border="0" title="Activate"  src="{$smarty.const.SITE_URL}/framework/includes/images/active{$shipmethod.selected}.gif"/></a>
	<img border="0" title="Deactivate"  src="{$smarty.const.SITE_URL}/framework/includes/images/deactive{$shipmethod.selected}.gif"/>
{/if}
		  </td> 
          <td height="35" align="left" valign="middle">
{if $shipmethod.selected eq 'Y' }		  
	<!-- <a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act={$shipmethod.form_file}&shipmethod_id={$shipmethod.shipmethod_id}&store_id={$SELECTED_STORE_ID}&limit={$smarty.request.limit}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}">{$shipmethod.name|upper}</a> -->
	<a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act={$shipmethod.form_file}&shipmethod_id={$shipmethod.shipmethod_id}&store_id={$SELECTED_STORE_ID}&limit={$smarty.request.limit}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}">{$shipmethod.name|upper}</a>
{else}		  
	<a class="linkOneActive" href="#">{$shipmethod.name|upper} </a>
{/if}
		  </td> 
		  <td align="left" nowrap>&nbsp;&nbsp;{$shipmethod.ship_method_description|wordwrap:65}</td>
          <td height="35" align="center" valign="middle">{if $shipmethod.logo_extension ne ''}
		  <img src="{$GLOBAL.modbase_url}/order/images/shipping/thumb/{$shipmethod.shipmethod_id}{$shipmethod.logo_extension}" >
		  {/if}</td>
        </tr> 
        {/foreach}
        <tr align="center"> 
          <td height="30" colspan="2" class="msg">&nbsp;</td> 
          <td height="30" class="msg">&nbsp;</td>
          <td height="30" class="msg">&nbsp;</td>
        </tr>
        {else}
        <!-- <tr class="naGrid2"> 
          <td colspan="4" class="naError" align="center" height="30">No Records</td> 
        </tr>-->
		
      </table></td> 
  </tr> 
</table>
</form>

{/if}


<div align="center">
	<span style="color:#FF0000"><b>{$MESSAGE}{$MESSAGE1}</b></span>
</div>

  <table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" class="naBrdr">
  <form name="frm_message" method="post" action="">
	<tr> 
      <td align="left" colspan=3 class="naH1"><!--Flat Rate Shipping-->Shipping Details</td> 
    </tr> 
    <tr class="naGrid2" >
      <td colspan="4" class="naGridTitle" height="25" nowrap="nowrap" align="left" >&nbsp;&nbsp;<b>Domestic Orders:</b></td>
      </tr>
	  <tr><td width="90%" colspan="4"><table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
    <tr  class="naGrid1" >
      <td align="right" width="45%">Shipping price for 1st gift:</td>
      <td height="30"  align="center" valign="middle">&nbsp;</td>
      <td width="57%" colspan="2"><label>
        {$CURRENCY_SYMBOL}
        <input type="text" name="sp_firstgift" style="text-align:right " value="{$FLAT_SHIPPING.sp_firstgift}" />
      </label></td>
    </tr>
    <tr  class="naGrid2" >
      <td align="right" valign="middle">Shipping price for each additional gift:</td>
      <td height="30"  align="center" valign="middle">&nbsp;</td>
      <td colspan="2" valign="middle">{$CURRENCY_SYMBOL}
        <input type="text" name="sp_additionalgift" style="text-align:right " value="{$FLAT_SHIPPING.sp_additionalgift}" /></td>
    </tr>
    <tr  class="naGrid1" >
      <td align="right">Shipping price for 1st mat:</td>
     <td height="30"  align="center" valign="middle">&nbsp;</td>
      <td colspan="2">{$CURRENCY_SYMBOL}
        <input type="text" name="sp_firstmat" style="text-align:right "  value="{$FLAT_SHIPPING.sp_firstmat}" /></td>
    </tr>
    <tr  class="naGrid2">
      <td align="right" valign="middle">Shipping price for each additional Mat:</td>
      <td height="30"  align="center" valign="middle">&nbsp;</td>
      <td colspan="2" valign="middle">{$CURRENCY_SYMBOL}
        <input type="text" name="sp_additionalmat" style="text-align:right " value="{$FLAT_SHIPPING.sp_additionalmat}" /></td>
    </tr>
	<!-- ***** Added on 13-nov-09 to update the shipping price ****** -->
	 <tr  class="naGrid1">
      <td align="right" valign="middle">Shipping price for 1st 8.5x11 Wood Frame:</td>
      <td height="30"  align="center" valign="middle">&nbsp;</td>
      <td colspan="2" valign="middle">{$CURRENCY_SYMBOL}
        <input type="text" name="sp_firstwood" style="text-align:right " value="{$FLAT_SHIPPING.sp_firstwood}" /></td>
    </tr>
	 <tr  class="naGrid2">
      <td align="right" valign="middle">Shipping price for each additional 8.5x11 Wood Frame:</td>
      <td height="30"  align="center" valign="middle">&nbsp;</td>
      <td colspan="2" valign="middle">{$CURRENCY_SYMBOL}
        <input type="text" name="sp_additionalwood" style="text-align:right " value="{$FLAT_SHIPPING.sp_additionalwood}" /></td>
    </tr>
	 <tr  class="naGrid1">
      <td align="right" valign="middle">Shipping price for 1st 8.5x11 Plaque:</td>
      <td height="30"  align="center" valign="middle">&nbsp;</td>
      <td colspan="2" valign="middle">{$CURRENCY_SYMBOL}
        <input type="text" name="sp_firstplaque" style="text-align:right " value="{$FLAT_SHIPPING.sp_firstplaque}" /></td>
    </tr>
	 <tr  class="naGrid2">
      <td align="right" valign="middle">Shipping price for each additional 8.5x11 Plaque: </td>
      <td height="30"  align="center" valign="middle">&nbsp;</td>
      <td colspan="2" valign="middle">{$CURRENCY_SYMBOL}
        <input type="text" name="sp_additionalplaque" style="text-align:right " value="{$FLAT_SHIPPING.sp_additionalplaque}" /></td>
    </tr>
	
	 <tr  class="naGrid1">
      <td align="right" valign="middle">Shipping price for 1st 11x14 Wood Frames:</td>
      <td height="30"  align="center" valign="middle">&nbsp;</td>
      <td colspan="2" valign="middle">{$CURRENCY_SYMBOL}
        <input type="text" name="sp_firstlargewoodframe" style="text-align:right " value="{$FLAT_SHIPPING.sp_firstlargewoodframe}" /></td>
    </tr>
	 <tr  class="naGrid2">
      <td align="right" valign="middle">Shipping price for each additional 11x14 Wood Frames: </td>
      <td height="30"  align="center" valign="middle">&nbsp;</td>
      <td colspan="2" valign="middle">{$CURRENCY_SYMBOL}
        <input type="text" name="sp_additionallargewoodframe" style="text-align:right " value="{$FLAT_SHIPPING.sp_additionallargewoodframe}" /></td>
    </tr>
<!--	 <tr  class="naGrid1">
      <td align="right" valign="middle">Shipping price for 1st 8.5x11 Glass Frame: </td>
      <td height="30"  align="center" valign="middle">&nbsp;</td>
      <td colspan="2" valign="middle">{$CURRENCY_SYMBOL}
        <input type="text" name="sp_firstglass" style="text-align:right " value="{$FLAT_SHIPPING.sp_firstglass}" /></td>
    </tr>
	 <tr  class="naGrid2">
      <td align="right" valign="middle">Shipping price for each additional 8.5x11 Glass Frame:</td>
      <td height="30"  align="center" valign="middle">&nbsp;</td>
      <td colspan="2" valign="middle">{$CURRENCY_SYMBOL}
        <input type="text" name="sp_additionalglass" style="text-align:right " value="{$FLAT_SHIPPING.sp_additionalglass}" /></td>
    </tr>-->
	<!-- ***** Added on 13-nov-09 to update the shipping price ****** -->
	</table></td></tr>
	<tr  class="naGrid2" >
      <td colspan="3" class="naGridTitle" height="25" nowrap="nowrap" align="left" >&nbsp;&nbsp;<b>International Orders:</b></td>
      </tr>
        <!-- <tr >
          <td colspan="4" align="left">Disable flat rate shipping for international orders:&nbsp;&nbsp;<input name="intr_frs_status" type="checkbox" value="Y"  onclick="divVisible(this.checked)"  { if $FLAT_SHIPPING.intr_frs_status=='Y'}  checked="checked" {/if} /></td>
          </tr> -->
		  <tr  >
		    <td colspan="3" align="left" height="25">
			<table border="0" width="100%"  align="center" cellpadding="5" cellspacing="1"><tr  class="naGrid1"><td align="left" colspan="2"><input name="intr_frs_status" id="intr_frs_status_one" type="radio" value="1"  onclick="divVisible(this.checked,'1')" {if $FLAT_SHIPPING.intr_frs_status eq '1'}checked{/if} />&nbsp;Do not accept International Orders and display the following message.&nbsp;&nbsp;</td></tr>
			
			 <tr  class="naGrid2" >
          <td align="left"  colspan="2" ><input name="intr_frs_status" id="intr_frs_status_two" type="radio" value="3"  onclick="divVisible(this.checked,'3')" {if $FLAT_SHIPPING.intr_frs_status eq '3'}checked{/if}  />&nbsp;Accept International Orders and display the following message.&nbsp;&nbsp;</td>
          </tr>
			</table>
			
			</td>
          </tr>
		  
		 
		  
    <tr><td colspan="4" width="60%"><div id="intr_div"  style="display:{ if $FLAT_SHIPPING.intr_frs_status=='1'}none{else}block{/if}"><table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" >
	<tr  class="naGrid1" >
      <td align="right" width="45%">Shipping price for 1st gift:</td>
      <td height="30"  align="center" valign="middle">&nbsp;</td>
      <td width="57%" colspan="2"><label>
        {$CURRENCY_SYMBOL} <input type="text" style="text-align:right " name="intr_sp_firstgift" value="{$FLAT_SHIPPING.intr_sp_firstgift}" />
      </label></td>
    </tr>
    <tr  class="naGrid2" >
      <td align="right" valign="middle">Shipping price for each additional gift:</td>
     <td height="30"  align="center" valign="middle">&nbsp;</td>
      <td colspan="2" valign="middle">{$CURRENCY_SYMBOL}
        <input type="text" name="intr_sp_additionalgift" style="text-align:right " value="{$FLAT_SHIPPING.intr_sp_additionalgift}" /></td>
    </tr>
    <tr  class="naGrid1" >
      <td align="right">Shipping price for 1st mat:</td>
      <td height="30"  align="center" valign="middle">&nbsp;</td>
      <td colspan="2">{$CURRENCY_SYMBOL}
        <input type="text" name="intr_sp_firstmat" style="text-align:right " value="{$FLAT_SHIPPING.intr_sp_firstmat}" /></td>
    </tr>
    <tr  class="naGrid2" >
      <td align="right" valign="middle">Shipping price for each additional Mat:</td>
      <td height="30"  align="center" valign="middle">&nbsp;</td>
      <td colspan="2" valign="middle">{$CURRENCY_SYMBOL}
        <input type="text" name="intr_sp_additionalmat" style="text-align:right " value="{$FLAT_SHIPPING.intr_sp_additionalmat}" /></td>
    </tr>
	<!-- ***** Added on 13-nov-09 to update the shipping price ****** -->
	  <tr  class="naGrid1" >
      <td align="right" valign="middle">Shipping price for 1st 8.5x11 Wood Frame: </td>
      <td height="30"  align="center" valign="middle">&nbsp;</td>
      <td colspan="2" valign="middle">{$CURRENCY_SYMBOL}
        <input type="text" name="intr_sp_firstwood" style="text-align:right " value="{$FLAT_SHIPPING.intr_sp_firstwood}" /></td>
    </tr>
	  <tr  class="naGrid2" >
      <td align="right" valign="middle">Shipping price for each additional 8.5x11 Wood Frame:</td>
      <td height="30"  align="center" valign="middle">&nbsp;</td>
      <td colspan="2" valign="middle">{$CURRENCY_SYMBOL}
        <input type="text" name="intr_sp_additionalwood" style="text-align:right " value="{$FLAT_SHIPPING.intr_sp_additionalwood}" /></td>
    </tr>
	 <tr  class="naGrid1" >
      <td align="right" valign="middle">Shipping price for 1st 8.5x11 Plaque: </td>
      <td height="30"  align="center" valign="middle">&nbsp;</td>
      <td colspan="2" valign="middle">{$CURRENCY_SYMBOL}
        <input type="text" name="intr_sp_firstplaque" style="text-align:right " value="{$FLAT_SHIPPING.intr_sp_firstplaque}" /></td>
    </tr>
	  <tr  class="naGrid2" >
      <td align="right" valign="middle">Shipping price for each additional 8.5x11 Plaque:</td>
      <td height="30"  align="center" valign="middle">&nbsp;</td>
      <td colspan="2" valign="middle">{$CURRENCY_SYMBOL}
        <input type="text" name="intr_sp_additionalplaque" style="text-align:right " value="{$FLAT_SHIPPING.intr_sp_additionalplaque}" /></td>
    </tr>
	 <tr  class="naGrid1" >
      <td align="right" valign="middle">Shipping price for 1st 11x14 Wood Frames: </td>
      <td height="30"  align="center" valign="middle">&nbsp;</td>
      <td colspan="2" valign="middle">{$CURRENCY_SYMBOL}
        <input type="text" name="intr_sp_firstlargewoodframe" style="text-align:right " value="{$FLAT_SHIPPING.intr_sp_firstlargewoodframe}" /></td>
    </tr>
	  <tr  class="naGrid2" >
      <td align="right" valign="middle">Shipping price for each additional 11x14 Wood Frames:</td>
      <td height="30"  align="center" valign="middle">&nbsp;</td>
      <td colspan="2" valign="middle">{$CURRENCY_SYMBOL}
        <input type="text" name="intr_sp_additionallargewoodframe" style="text-align:right " value="{$FLAT_SHIPPING.intr_sp_additionallargewoodframe}" /></td>
    </tr>
	<!-- <tr  class="naGrid1" >
      <td align="right" valign="middle">Shipping price for 1st 8.5x11 Glass Frame:</td>
      <td height="30"  align="center" valign="middle">&nbsp;</td>
      <td colspan="2" valign="middle">{$CURRENCY_SYMBOL}
        <input type="text" name="intr_sp_firstglass" style="text-align:right " value="{$FLAT_SHIPPING.intr_sp_firstglass}" /></td>
    </tr>
	  <tr  class="naGrid2" >
      <td align="right" valign="middle">Shipping price for each additional 8.5x11 Glass Frame:</td>
      <td height="30"  align="center" valign="middle">&nbsp;</td>
      <td colspan="2" valign="middle">{$CURRENCY_SYMBOL}
        <input type="text" name="intr_sp_additionalglass" style="text-align:right " value="{$FLAT_SHIPPING.intr_sp_additionalglass}" /></td>
    </tr>-->
	<!-- ***** Added on 13-nov-09 to update the shipping price ****** -->
	
	
	</table></div></td></tr>
    
	
	<tr  class="naGrid1"><td colspan="4" width="70%"><div id="div_msg" style="display:{ if $FLAT_SHIPPING.intr_frs_status=='1'}block{else}none{/if}"><table  width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="naBrdr" bgcolor="#FFFFFF">
    <tr class="naGrid1">
      <td width="45%"  align="right" valign="top">International Message Text:</td>
      <td  height="30"  align="center" valign="top">&nbsp;</td>
     
      <td width="54%" ><textarea name="intrnl_message_flat_one" cols="60" rows="5" id="intrnl_message_flat">{$FLAT_SHIPPING.intr_message_one}</textarea></td>
      <input name="intrnl_message_status" type="hidden" id="intrnl_message_status" value="Yes"  {if $INTRNL_MESSAGE.intrnl_message_status eq 'Yes'}checked{/if} />
    </tr>
	</table>
	</div>
	<div id="div_msg1" style="display:{ if $FLAT_SHIPPING.intr_frs_status=='3'}block{else}none{/if}">
	<table  width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="naBrdr" bgcolor="#FFFFFF">
    <tr class="naGrid1">
      <td width="45%"   align="right" valign="top">International Message Text:</td>
      <td  height="30"  align="center" valign="top">&nbsp;</td>
     
      <td width="54%" ><textarea name="intrnl_message_flat" cols="60" rows="5" id="intrnl_message_flat">{$FLAT_SHIPPING.intr_message_two}</textarea></td>
      <input name="intrnl_message_status" type="hidden" id="intrnl_message_status" value="Yes"  {if $INTRNL_MESSAGE.intrnl_message_status eq 'Yes'}checked{/if} />
    </tr>
	</table>
	</div>
	</td></tr>
	
	{if $STORE_OWNER eq "admin" || $STORE_ID eq 0}
	<!-- pay invoice message -->
	{if $PAY_INVOICE eq 'Y'}
	    <tr >
      <td width="40%" align="right">Message  for Pay Invoice:</td>
      <td height="30"  align="center" valign="bottom">&nbsp;</td>
      <td colspan="2">
        <input name="payinvoice_message_status" type="checkbox" id="payinvoice_message_status" value="Yes"  {if $INVOICE_MESSAGE.invoice_message_status eq 'Yes'}checked{/if} />
        <img src="{$GLOBAL.tpl_url}/images/icon_small_info.gif" width="17" height="16" {popup text=" If no no product in the shopping cart,then this message will show in the shipping page.' " width="400" fgcolor="#eeffaa"} />
      </label></td>
    </tr>
    <tr>
      <td align="right" valign="middle">International Message Text:</td>
      <td height="30"  align="center" valign="bottom">&nbsp;</td>
      <td width="23%"><label>
        <textarea name="payinvoice_message" cols="80" rows="3" id="intrnl_message">{$INVOICE_MESSAGE.invoice_message}</textarea>
      </label></td>
      <td width="35%"></td>
    </tr>
	{/if}
	<!-- end pay invoice message -->
	{/if}
	<!-- flat rate shipping-->
	
	 <input name="shipping_status" type="hidden" id="shipping_status" value="Y"  {if $FLAT_SHIPPING.shipping_status eq 'Y'}checked{/if} />
	    <!--<tr >
      <td width="40%" align="right">Enable flat rate shippping</td>
      <td width="2%">:</td>
      <td colspan="2">
        <input name="shipping_status" type="hidden" id="shipping_status" value="Y"  {if $FLAT_SHIPPING.shipping_status eq 'Y'}checked{/if} />
      </label></td>
    </tr>
	
    <tr>
      <td align="right" valign="middle">Shipping Price</td>
      <td>:</td>
      <td width="23%"><label>
        <input type="text" name="shipping_fee" value="{$FLAT_SHIPPING.shipping_fee}" />&nbsp;$
      </label></td>
      <td width="35%"><input type="hidden" name="sid" value="{$FLAT_SHIPPING.id}" /></td>
    </tr>
	-->
	<!-- end flat rate shipping-->
    <!--<tr  class="naGrid2">
      <td align="right" valign="middle">&nbsp;</td>
      <td>&nbsp;</td>
	   <td width="60">&nbsp;</td>
      <td ><label>
      <input type="submit" name="Submit" value="Submit" class="naBtn" onClick="return sub_check()" />
      </label></td>
    </tr>-->
	 <tr class="naGridTitle"> 
      <td colspan=4 valign="center" height="25"><div align=center>	  
	       <input type=submit  src="{$GLOBAL.tpl_url}/images/btn_submit2.gif" name="Submit" value="Submit" class="naBtn">&nbsp; 
       
        </div>	 </td> 
    </tr> 
	<tr><td colspan=4 valign=center height="25">&nbsp;</td></tr> </form>
  </table>







