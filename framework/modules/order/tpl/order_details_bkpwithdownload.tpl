<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></SCRIPT>
<script language="javascript">
{literal}
function pagesubmit()
{ 
{/literal}
 document.form1.action='{makeLink mod=order pg=order}act=details{/makeLink}';
 document.form1.status_key.value='Y';
 document.form1.submit();
{literal}
}
function manageDiv (opt)	{
// alert(opt);
  document.getElementById('myid').value=opt;
      if (document.getElementById("div_"+opt).style.display == "none")	{
	  	document.getElementById("div_"+opt).style.display='inline';
	  } else	{
	  	document.getElementById("div_"+opt).style.display='none';
	  }
	  
	  return false;
 
  }
function  manageNewtopic(){
	if (document.getElementById("div_new_topic").style.display == "none")	{
	  	document.getElementById("div_new_topic").style.display='inline';
	  } else	{
	  	document.getElementById("div_new_topic").style.display='none';
	  }
	  
	  return false;
}
function  manageDivthreadPost(opt){
	if (document.getElementById("div_new_topic_"+opt).style.display == "none")	{
	  	document.getElementById("div_new_topic_"+opt).style.display='inline';
	  } else	{
	  	document.getElementById("div_new_topic_"+opt).style.display='none';
	  }
	  
	  return false;
}

function subfrm()
{
{/literal}
 document.frm.action='{makeLink mod=order pg=order}act=manual_order_form_online{/makeLink}';
 document.frm.submit();
{literal}

}

function popUpComments() {
{/literal}
windowReference=window.open('{makeLink mod=order pg=order}act=popupComments{/makeLink}&oId={$ORDER_DETAILS->id}', "name_of_window", "width=550,height=200,left=100,top=200,scrollbars=yes,menubar=no, resizable=yes,location=no,toolbar=no");
if (!windowReference.opener)
windowReference.opener = self;
{literal}
}
{/literal}
</script>
<style>
{literal}
.accessory td {
	cursor:pointer;
	padding-left:5px;
}
.accessoryHover td {
	cursor:pointer;
	background-color:#aabbdd;
	padding-left:5px;
}
{/literal}
</style>
<table width="90%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table  border="0" width="96%" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center" width="100%">
<table align="center" width="99%" border="0" cellspacing="0" cellpadding="0" class="naBrdr">
  <tr>
    <td><table width="98%" align="center">
      <tr>
        <td nowrap="nowrap" class="naH1">Order Details </td>
        <td align="right" nowrap="nowrap"><strong>{if !$smarty.request.print}<a href="javascript:void(0);" onclick="w=window.open('{makeLink mod=order pg=order}act=details&id={$ORDER_DETAILS->id}&print=1{/makeLink}', 'w', 'width=1020,height=700,scrollbars=yes');w.focus();w.onload=function () {literal}{window.print();}{/literal}">Print</a>{/if}</strong></td>
      </tr>
    </table>	</td>
  </tr>
  <tr class=naGrid2>
    <td align="center">
	
{if $ORDER_SHOW eq 'Y'}	
<form action="{makeLink mod=order pg=order}act=manual_order_form_online{/makeLink}" method="post" name="frm">
<table width=100% border=0 align="center" cellpadding=5 cellspacing=1 > 
  	<input type="hidden" name="act" value="{$smarty.request.act}">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
	<input type="hidden" name="mId" value="{$smarty.request.mId}">
	<input type="hidden" name="id" value="{$MANUAL_ORDER_DETAILS.id}">
	<input type="hidden" name="order_id" value="{$smarty.request.id}">

	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=14>
		<div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>      </td>
    </tr>
	{/if}
	<tr class=naGrid2>
      <td valign=top colspan=14 >&nbsp;</td>
    </tr>
    <tr class=naGrid2>
      <td width="12%"  align="right" valign=top><span class="fieldname"> Order Number</span></td>
      <td width="2%" align="center" valign=middle><span class="fieldname">: </span></td>
      <td width="20%" align="left"><span class="formfield">
        <input readonly="1" name="order_number" type="text" class="Form2" id="order_number" value="{$ORDER_DETAILS->order_number}" size="20" maxlength="30" tabindex="1" />
      </span></td>
      <td width="16%" align="right">Date Ordered </td>
      <td width="2%" align="center" valign="middle">:</td>
      <td width="9%" align="left"><span class="formfield">
        <input name="date_ordered" type="text" class="Form2" id="date_ordered" value="{$MANUAL_ORDER_DETAILS.date_ordered}" size="10" maxlength="30" tabindex="6" />
      </span></td>
      <td colspan="4" align="right" valign="bottom">&nbsp;</td>
      <td align="left" valign="bottom">&nbsp;</td>
      <td width="3%" align="left" valign="bottom">&nbsp;</td>
      <td width="2%" align="left" valign="bottom">&nbsp;</td>
      <td width="31%" align="left" valign="bottom">&nbsp;</td>
    </tr>
    <tr class=naGrid2>
      <td height="57"  align="right" valign=middle><span class="fieldname">Customer</span></td>
      <td align="center" valign=middle><span class="fieldname">:</span></td>
      <td align="left" valign="middle"><span class="formfield">
        <input readonly="1" name="customer_name" type="text" class="Form2" id="customer_name" value="{$ORDER_DETAILS->billing_first_name} {$ORDER_DETAILS->billing_last_name}" size="25" tabindex="2" />
      </span></td>
      <td align="right" valign="middle">Date Shipped </td>
      <td align="center" valign="middle">:</td>
      <td align="left"><span class="formfield">
        <input  name="ship_date" type="text" class="Form2" id="ship_date" value="{$MANUAL_ORDER_DETAILS.ship_date}" size="10" maxlength="30" tabindex="7" />
      </span></td>
      <td colspan="8" align="right" valign="middle">&nbsp;</td>
      </tr>
    <tr class=naGrid2>
      <td  align="right" valign=top>&nbsp;</td>
      <td align="center" valign=middle>&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="right">Rush Order </td>
      <td align="center" valign="middle">:</td>
      <td align="left"><span class="formfield">
        <input  name="rush_order" type="text" class="Form2" id="rush_order" value="{$MANUAL_ORDER_DETAILS.rush_order}" size="10" maxlength="30" tabindex="8" />
      </span></td>
      <td colspan="8" align="right" valign="middle">&nbsp;</td>
    </tr>
    <tr class=naGrid2>
    <!--  <td  align="right" valign=top>Item Orderd </td>
      <td align="center" valign=middle>:</td>
      <td align="left" valign="middle"><input name="item_ordered" type="checkbox" id="item_ordered" value="Yes" {if $MANUAL_ORDER_DETAILS.item_ordered eq 'Y'}checked{/if} tabindex="3"></td>-->
      <td align="right">Items on Back Order </td>
      <td align="center" valign="middle">:</td>
      <td align="left"><input name="back_order" type="checkbox" id="back_order" value="Yes" {if $MANUAL_ORDER_DETAILS.back_order eq 'Y'}checked{/if} tabindex="9" ><input name="back_order1" type="checkbox" id="back_order1" value="Yes" {if $MANUAL_ORDER_DETAILS.back_order1 eq 'Y'}checked{/if} tabindex="10"></td>
      <td colspan="8" align="right" valign="middle">&nbsp;</td>
    </tr>
    <tr class=naGrid2>
      <td colspan="3"  align="left" valign=bottom><strong>&nbsp;&nbsp;&nbsp;&nbsp;Order Summary </strong></td>
      <td align="right">Date Expected </td>
      <td align="center" valign="middle">:</td>
      <td align="left"><span class="formfield">
        <input  name="date_expected" type="text" class="Form2" id="date_expected" value="{$MANUAL_ORDER_DETAILS.date_expected}" size="10" maxlength="30" tabindex="11" />
      </span></td>
      <td colspan="8" align="right" valign="middle">&nbsp;</td>
    </tr>
    <tr class=naGrid2>
      <td colspan="3"  align="left" ><span>&nbsp;&nbsp;&nbsp;&nbsp;</span><textarea name="order_summary" cols="39" rows="6" id="order_summary" tabindex="4">{$MANUAL_ORDER_DETAILS.order_summary}</textarea></td><td colspan="3"  align="right" valign=middle><textarea name="backorder_summary" cols="39" rows="6" id="backorder_summary" tabindex="5">{$MANUAL_ORDER_DETAILS.backorder_summary}</textarea></td>
      <td width="12%"  align="right" valign=middle>Bill Shipping</td>
      <td width="2%"  align="center" valign=middle>:</td>
      <td  align="left" valign=middle><input name="billing_Shipping" type="checkbox" id="billing_Shipping" value="Yes" {if $MANUAL_ORDER_DETAILS.billing_Shipping eq 'Y'}checked{/if} tabindex="12"></td>
      <td width="4%"  align="right" valign=middle>Complete</td>
      <td width="2%"  align="center" valign=middle>:</td>
      <td  align="left" valign=middle><input name="billed_shipping" type="checkbox" id="billed_shipping" value="Yes" {if $MANUAL_ORDER_DETAILS.billed_shipping eq 'Y'}checked{/if} tabindex="13"></td>
      <td  align="left" valign=middle>&nbsp;</td>
      <td  align="left" valign=middle>&nbsp;</td>
    </tr>
	  

	  
   <tr class=naGrid2>
     <td colspan="14" align="center" valign=top><input type="submit" name="Submit2" value="Submit" class="naBtn" tabindex="12" />
       <input name="reset" type="reset" class="naBtn" value="Reset" tabindex="13" /></td>
     </tr> 
</table>
</form>
{/if}	
	
	
	</td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellpadding="5" cellspacing="2">
      {if count($ORDER_DETAILS) > 0}
      <tr>
        <td height="24" colspan="5" align="center" nowrap="nowrap"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                      <tr>
                        <td valign="top"  class="naFooter"><table width="100%" border="0" align="center" cellpadding="4" cellspacing="1">
                            {if $ORDER_PRODUCTS.records}
							  {foreach from=$ORDER_JOBS item=row}
							  {if $row->height and $row->width}
							  {assign var="hei" value="Height"}
							   {assign var="wid" value="width"}
							  {/if}
							  {/foreach}
                            <tr class="tablegreen2">
                              <td width="1916" class="naGridTitle"><strong>{if $row->jobname}Job Name{else}Product Name{/if} </strong></td>
                              {if $GLOBAL.assign_supplier=='Y'}<td width="351" height="25" class="naGridTitle">&nbsp;</td>{/if}
                              <td width="288" align="center" class="naGridTitle"><div align="center"><strong>Price</strong></div></td>
                              <td width="390" align="center" class="naGridTitle"><div align="center"><strong>Quantity</strong></div></td>
							  {if $hei}
							  <td width="390" align="center" class="naGridTitle"><div align="center"><strong>{$hei}</strong></div></td>
							  {/if}
							   {if $wid}
							  <td width="390" align="center" class="naGridTitle"><div align="center"><strong>{$wid}</strong></div></td>
							  {/if}
                              <td width="323" align="center" class="naGridTitle"><div align="right"><strong>Total</strong></div></td>
                            </tr>
                            {foreach from=$ORDER_PRODUCTS.records item=row name=foo}
							
                            <tr class="naGrid2">
                              <td><strong>{if $GLOBAL.assign_supplier=='Y'}<a href="{makeLink mod=order pg=order}act=item_details&id={$row->id}&ord_id={$smarty.request.id}{/makeLink}"><img src="{$smarty.const.SITE_URL}/modules/cart/images/thumb/{$row->id}.jpg" width="68" height="57"  border="0" align="middle" ></a>{/if}{if $row->jobname}{$row->jobname}{else}{$row->name}{/if}&nbsp;{if $row->brand_id>0}({$row->brand_name}){/if}&nbsp;{if $row->cart_name}:&nbsp;({$row->cart_name}){/if}&nbsp;{if $row->order_number!=''}:&nbsp;<a href="{makeLink mod=extras pg=certificate}&act=form&id={$row->cid}{/makeLink}">(View Gift Certificate)</a>{/if}</strong></td>
                              {if $GLOBAL.assign_supplier=='Y'}<td height="26"><a href="{makeLink mod=order pg=order}act=assign_supplier&order_id={$smarty.request.id}&id={$row->product_id}&base_quantity={$row->quantity}{/makeLink}">Assign Supplier</a></td>{/if}
                              <td height="26" align="center"><div align="right">
							  {if $hei}
							  	{$row->price*$row->quantity*$row->width*$row->height|string_format:"%.2f"}
							  {else}
							  	{$row->price}
							  {/if}
                                  
                              </div></td>
                              <td valign="top" {if $row->accessory}rowspan="2"{/if} align="center"><div align="center">
                                  {$row->quantity}
                              </div></td>
							  {if $hei}
							  <td valign="top" {if $row->accessory}rowspan="2"{/if} align="center"><div align="center">
                                  {$row->height|string_format:"%.1f"}
                              </div></td>
							  {/if}
							   {if $wid}
							  <td valign="top" {if $row->accessory}rowspan="2"{/if} align="center"><div align="center">
                                  {$row->width|string_format:"%.1f"}
                              </div></td>
							 {/if}
                              <td align="center"><div align="right">
							  {if $hei}
                                  	{$row->price*$row->quantity*$row->width*$row->height|string_format:"%.2f"}
							{else}
									{$row->price*$row->quantity|string_format:"%.2f"}
							{/if}
                              </div></td>
                            </tr>
                            {if $row->accessory}
                            <tr class="naGrid2">
                              <td height="35" {if $GLOBAL.assign_supplier=='Y'}colspan="2"{/if} class="bodytext">
							  <table width="84%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                  <tr class="smallwhite">
                                    <td colspan="2" height="26"><strong>Options</strong></td>
                                    <td width="100" height="26"><div align="right"><strong>Price</strong></div></td>
                                  </tr>
                                  {foreach from=$row->accessory item=sub name=acc}
                                  <tr class="accessory" onmouseover="this.className='accessoryHover';" onmouseout="this.className='accessory';">
                                    <td height="20" width="20" nowrap="nowrap"><strong>{$smarty.foreach.acc.index+1})</td>
                                    <td height="20"><strong>{$sub->category_name}</strong> : {$sub->name}&nbsp;{if $sub->available_cart_name}({$sub->available_cart_name}){else}{if $sub->cart_name}({$sub->cart_name}){/if}{/if}</td>
                                    <td height="20"><div align="right">
                                      {if $sub->price>0}
									  	{if $hei}
											{$sub->price*$row->quantity*$row->width*$row->height|string_format:"%.0f"}
										{else}
											{$sub->price}
										{/if}
                                      {else}
                                      -&nbsp;&nbsp;&nbsp;
                                      {/if}
                                    </div></td>
                                  </tr>
								  {if $sub->customization_text}
                                  <tr class="accessory">
                                    <td height="20">&nbsp;</td>
                                    <td height="20"><strong>Customization text</strong> : {$sub->customization_text}</td>
                                    <td height="20">&nbsp;</td>
                                  </tr>
								  {/if}
								  {if $sub->addl_customization_text}
                                  <tr class="accessory">
                                    <td height="20">&nbsp;</td>
                                    <td height="20"><strong>Requested Monogram text</strong> : {$sub->addl_customization_text}</td>
                                    <td height="20">&nbsp;</td>
                                  </tr>
								  {/if}
								   {if $sub->wrap_text}
                                  <tr class="accessory">
                                    <td height="20">&nbsp;</td>
                                    <td height="20"><strong>Requested Wrap text</strong> : {$sub->wrap_text}</td>
                                    <td height="20">&nbsp;</td>
                                  </tr>
								  {/if}
                                  {/foreach}
                              </table></td>
							  
                              <td height="35" align="center" class="naGrid2" valign="top"><div align="right">
							   {if $hei}
                               		{$row->accessory_price*$row->quantity*$row->width*$row->height|string_format:"%.0f"}
								{else}
									{$row->accessory_price|string_format:"%.2f"}
								{/if}
                              </div></td>
                              <td align="center" class="naGrid2" valign="top"><div align="right">
							  {if $hei}
                                {$row->accessory_price*$row->quantity*$row->width*$row->height|string_format:"%.0f"}
								{else}
								{$row->accessory_price*$row->quantity|string_format:"%.2f"}
								{/if}
                              </div></td>
                            </tr>
                            {/if}
							
							
							
							
							
							
							
							
							{if $row->jobname}
								<tr class="naGrid2">
                            <td height="20" class="bodytext" colspan="7"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
								  <tr>
									<td width="80"><strong>Job Name</strong></td>
									<td width="2"><strong>:</strong></td>
									<td width="80%">{$row->jobname}</td>
								  </tr>
								</table>								</td>
                          </tr>
							{/if}
							{if $row->file1_ext}
								<tr class="naGrid2">
                            <td height="20" class="bodytext" colspan="7"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
								  <tr>
									<td width="80"><strong>Download Front File</strong></td>
									<td width="2"><strong>:</strong></td>
									<td width="80%"><input type="button" class="naBtn" value="Download" onclick="Javascript: window.location='{makeLink mod=order pg=order}act=file_download&path={$smarty.const.SITE_URL}/modules/order/images/userorders/&filename={$row->id}file1&type={$row->file1_ext}{/makeLink}'"/></td>
								  </tr>
								   <tr>
									<td width="80"><strong>&nbsp;</strong></td>
									<td width="2"><strong>&nbsp;</strong></td>
									<td width="80%">&nbsp;</td>
								  </tr>
								  {if $row->file2_ext}
								   <tr>
									<td width="80"><strong>Download Back File</strong></td>
									<td width="2"><strong>:</strong></td>
									<td width="80%"><input type="button" class="naBtn" value="Download" onclick="Javascript: window.location='{makeLink mod=order pg=order}act=file_download&path={$smarty.const.SITE_URL}/modules/order/images/userorders/&filename={$row->id}file2&type={$row->file2_ext}{/makeLink}'" /></td>
								  </tr>
								  {/if}
								</table>								</td>
                          </tr>
							{/if}
							 <tr class="naGrid2">
                            <td height="20" class="bodytext" colspan="7">
							<table width="100%"  border="0" cellspacing="0" cellpadding="0">
								  <tr class="naGrid">
									<td width="50"><strong>Ordered from</strong></td>
									<td width="2"><strong>:</strong></td>
									<td width="80%">{$STORE_NAME}</td>
								  </tr>
							  </table>							</td>
                          </tr>
						 {if $row->contact_me=='Y' }
						    <tr class="naGrid1">
                            <td height="20" class="bodytext" colspan="7">
								<table width="100%"  border="0" cellspacing="0" cellpadding="0">
								  <tr>
									<td width="80"><strong>Contact Me</strong></td>
									<td width="2"><strong>:</strong></td>
									<td width="80%">{if $row->contact_me=='Y' }YES{else}NO{/if}</td>
								  </tr>
								</table>								</td>
                          </tr>
						  {/if}
						    {if $row->notes!='' }
						  <tr class="naGrid2">
                            <td height="20" class="bodytext" colspan="7">
							<table width="100%"  border="0" cellspacing="0" cellpadding="0">
								
								  <tr>
									<td width="50"><strong>Notes</strong></td>
									<td width="2"><strong>:</strong></td>
									<td width="80%">{$row->notes}</td>
								  </tr>
							  </table>								</td>
                          </tr>
						  {/if}
                            <tr class="naGrid1">
                              <td height="20" class="bodytext" colspan="7">&nbsp;</td>
                            </tr>
                            {/foreach}
							{if $ORDER_DETAILS->contact_me}
                            <tr class="naGrid1">
                              <td height="20" class="bodytext" colspan="7"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
								  <tr>
									<td width="80"><strong>Contact Me</strong></td>
									<td width="2"><strong>:</strong></td>
									<td width="80%">{if $ORDER_DETAILS->contact_me=='Y' }YES{else}NO{/if}</td>
								  </tr>
								</table></td>
                            </tr>
							{/if}
							{if $ORDER_DETAILS->notes}
							<tr class="naGrid1">
                              <td height="20" class="bodytext" colspan="7"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
								  <tr>
									<td width="50"><strong>Notes</strong></td>
									<td width="2"><strong>:</strong></td>
									<td width="80%">{$ORDER_DETAILS->notes}</td>
								  </tr>
								</table></td>
                            </tr>
							{/if}
                            {else}
								{if $TOTAL_AMOUNT < 0}
                            <tr class="tablegreen2">
                              <td height="26" colspan="7" class="bodytext"><div align="center"><strong>This order contains no items </strong></div></td>
                            </tr>
								{/if}
                            {/if}
							
							
							
							<!-- start pay invoice -->
							  {if $PAY_INVOICE eq 'Y'}
							   {if $INVOICE_AMOUNT > 0 }
							  <tr class="naGrid2">
                              <td height="26" colspan="7" class="bodytext">
								<table width="100%"  border="0" class="naGrid2">
								<tr class="naGrid2">
								<td class="naGrid2"><strong>Invoice Number</strong></td>
								<td align="right" class="naGrid2"><strong>Amount</strong> &nbsp;</td>
								</tr>
								 {foreach from=$INVOICE item=val}
								<tr  class="naGrid">
								<td class="naGrid2"> {$val.invoice_no}</td>
								<td align="right" class="naGrid2">{$val.amount}&nbsp;</td>
								</tr>
								{if $val.comments ne ''}
								<tr  class="naGrid">
								<td colspan="2" class="naGrid2">&nbsp;&nbsp;&nbsp;&nbsp; <strong>Comments :</strong>&nbsp;&nbsp;{$val.comments}</td>
								</tr>
								{/if}
								 {/foreach}
								</table>							  </td>
                            </tr>
							
							{/if}
							{/if}
							<!-- end pay invoice -->
							
							
							
							
							
                        </table></td>
                      </tr>
                  </table></td>
        </tr>
      {foreach from=$ORDER_LIST item=row}
      {assign var=comm value=`$comm+$row->item_price_original*$row->item_commission/100`}
      {assign var=tot value=`$tot+$row->item_price`}
      {/foreach}
      <tr>
        <td width="16%" height="30" align="center" valign="top" class="msg"><table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
          <tr>
            <td width="100%" class="naH1">Billing Details</td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellpadding="5" cellspacing="0">

              <tr class="{cycle values="naGrid1,naGrid2"}">
                <td width="40%" height="18" class="bodytext">Customer Name</td>
                <td class="bodytext">: {$ORDER_DETAILS->billing_first_name} {$ORDER_DETAILS->billing_last_name}</td>
              </tr>

              <tr class="{cycle values="naGrid1,naGrid2"}">
                <td height="18" align="left" valign="middle" class="bodytext">Street Address</td>
                <td class="bodytext">: {$ORDER_DETAILS->billing_address1}</td>
              </tr>

              <tr class="{cycle values="naGrid1,naGrid2"}">
                <td height="18" align="left" valign="middle" class="bodytext"></td>
                <td class="bodytext">&nbsp;&nbsp;&nbsp;{$ORDER_DETAILS->billing_address2}</td>
              </tr>

              <tr class="{cycle values="naGrid1,naGrid2"}">
                <td height="18" class="bodytext">&nbsp;</td>
                <td class="bodytext">&nbsp;&nbsp;&nbsp;{$ORDER_DETAILS->billing_address3}</td>
              </tr>
              <tr class="{cycle values="naGrid1,naGrid2"}">
                <td height="18" class="bodytext">City</td>
                <td class="bodytext">: {$ORDER_DETAILS->billing_city}</td>
              </tr>

              <tr class="{cycle values="naGrid1,naGrid2"}">
                <td height="18" class="bodytext">State/Province</td>
                <td class="bodytext">: {$ORDER_DETAILS->billing_state}</td>
              </tr>

              <tr class="{cycle values="naGrid1,naGrid2"}">
                <td height="18" class="bodytext">Zipcode</td>
                <td class="bodytext">: {$ORDER_DETAILS->billing_postalcode}</td>
              </tr>

              <tr class="{cycle values="naGrid1,naGrid2"}">
                <td height="18" class="bodytext">Country</td>
                <td class="bodytext">: {$ORDER_DETAILS->billing_country}</td>
              </tr>

              <tr class="{cycle values="naGrid1,naGrid2"}">
                <td height="18" class="bodytext">Telephone</td>
                <td class="bodytext">: {$ORDER_DETAILS->billing_telephone}</td>
              </tr>

              <tr class="{cycle values="naGrid1,naGrid2"}">
                <td height="18" class="bodytext">Mobile</td>
                <td class="bodytext">: {$ORDER_DETAILS->billing_mobile}</td>
              </tr>
			  <tr class="{cycle values="naGrid1,naGrid2"}">
                <td height="18" class="bodytext">Email</td>
                <td class="bodytext">: {$ORDER_DETAILS->billing_email}</td>
              </tr>

            </table></td>
          </tr>
        </table></td>
        <td width="53%" align="right" valign="top" class="msg">
		<!-- not show when the the cart has no products -->
		{if $CART_TOTAL > 0}
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
          <tr>
            <td width="100%" class="naH1">Shipping Details</td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellpadding="5" cellspacing="0">
                <tr class="{cycle name="ship" values="naGrid1,naGrid2"}">
                  <td width="40%" height="18" class="bodytext">Customer Name</td>
                  <td class="bodytext">:
                    {$ORDER_DETAILS->shipping_first_name}
                      {$ORDER_DETAILS->shipping_last_name}</td>
                </tr>
                <tr class="{cycle values="naGrid1,naGrid2"}">
                  <td height="18" align="left" valign="middle" class="bodytext">Street Address</td>
                  <td class="bodytext">:
                    {$ORDER_DETAILS->shipping_address1}</td>
                </tr>
                <tr class="{cycle values="naGrid1,naGrid2"}">
                  <td height="18" align="left" valign="middle" class="bodytext"></td>
                  <td class="bodytext">&nbsp;&nbsp;&nbsp;{$ORDER_DETAILS->shipping_address2}</td>
                </tr>
                <tr class="{cycle values="naGrid1,naGrid2"}">
                  <td height="18" class="bodytext">&nbsp;</td>
                  <td class="bodytext">&nbsp;&nbsp;&nbsp;{$ORDER_DETAILS->shipping_address3}</td>
                </tr>
                <tr class="{cycle values="naGrid1,naGrid2"}">
                  <td height="18" class="bodytext">City</td>
                  <td class="bodytext">:
                    {$ORDER_DETAILS->shipping_city}</td>
                </tr>
                <tr class="{cycle values="naGrid1,naGrid2"}">
                  <td height="18" class="bodytext">State/Province</td>
                  <td class="bodytext">:
                    {$ORDER_DETAILS->shipping_state}</td>
                </tr>
                <tr class="{cycle values="naGrid1,naGrid2"}">
                  <td height="18" class="bodytext">Zipcode</td>
                  <td class="bodytext">:
                    {$ORDER_DETAILS->shipping_postalcode}</td>
                </tr>
                <tr class="{cycle values="naGrid1,naGrid2"}">
                  <td height="18" class="bodytext">Country</td>
                  <td class="bodytext">:
                    {$ORDER_DETAILS->shipping_country}</td>
                </tr>
                <tr class="{cycle values="naGrid1,naGrid2"}">
                  <td height="18" class="bodytext">Telephone</td>
                  <td class="bodytext">:
                    {$ORDER_DETAILS->shipping_telephone}</td>
                </tr>
                <tr class="{cycle values="naGrid1,naGrid2"}">
                  <td height="18" class="bodytext">Mobile</td>
                  <td class="bodytext">:
                    {$ORDER_DETAILS->shipping_mobile}</td>
                </tr>
				<tr class="{cycle values="naGrid1,naGrid2"}">
                  <td height="18" class="bodytext">Email</td>
                  <td class="bodytext">:
                    {$ORDER_DETAILS->billing_email}</td>
                </tr>
            </table></td>
          </tr>
        </table>
		{/if}
		
		
		</td>
        <td height="30" colspan="3" align="right" valign="top" class="msg"><table width="100%" border="0" cellpadding="5" cellspacing="2">
         
		 
		 <!-- if cart amount is grater than zero-->
		 {if $CART_TOTAL > 0}
		  <tr>
		  <td height="26" colspan="3" class="bodytext"><div align="right">Cart Total :</div></td>
		  <td class="bodytext" ><div align="right">
			<strong>{$CART_TOTAL|string_format:"%.0f"}</strong>
		  </div></td>
		</tr>
		<tr>
			<td height="26" colspan="3" class="bodytext"><div align="right">Shipping Price :</div></td>
			<td class="bodytext"><div align="right"><strong>
			{if $ORDER_DETAILS->international_order eq 'Y'}
				&lt;Yet to determine&gt;
			{else}
				{$SHIPPING_PRICE}
			{/if}
			
			</strong></div></td>
		  </tr>
		 
		  {if $ORDER_DETAILS->tax > 0}
		  <tr>
			<td height="26" colspan="3" class="bodytext"><div align="right">Tax @ 
			  {$ORDER_DETAILS->tax|string_format:"%.2f"}
			  % :</div></td>
			<td class="bodytext"><div align="right"><strong>{$TAX_AMOUNT|string_format:"%.2f"}</strong></div></td>
		  </tr>
		  {/if}
		  {if $ORDER_GIFT_CERT || $ORDER_COUPON}
		  <tr>
			<td height="26" colspan="3" class="bodytext"><div align="right">Sub Total :</div></td>
			<td class="bodytext"><div align="right"><strong>{$SUB_TOTAL|string_format:"%.2f"}</strong></div></td>
		  </tr>
		  {/if}
		  
		  
		  
		  {/if}
		  {if $ORDER_COUPON}
		  <tr>
			<td height="26" colspan="3" class="bodytext"><div align="right">Coupon {if $ORDER_COUPON->coupon_amounttype=='P'} - {$ORDER_COUPON->coupon_amount}% Discount {/if}:</div></td>
			<td class="bodytext"><div align="right"><strong>{$COUPON_AMOUNT|string_format:"%.2f"}</strong></div></td>
		  </tr>
		  {/if}
		  {if $ORDER_GIFT_CERT}
		  <tr>
			<td height="26" colspan="3" class="bodytext"><div align="right">Gift Certificate :</div></td>
			<td class="bodytext"><div align="right"><strong>{$CERTIFICATE_AMOUNT|string_format:"%.2f"}</strong></div></td>
		  </tr>
		  {/if}
		   {if $PAY_INVOICE eq 'Y'}
		  {if $INVOICE_AMOUNT > 0 }
		  <tr>
			<td height="26" colspan="3" class="bodytext"><div align="right">Invoice Amount :</div></td>
			<td class="bodytext"><div align="right"><strong>{$INVOICE_AMOUNT|string_format:"%.2f"}</strong></div></td>
		  </tr>
		  {/if}{/if}
		  {if $DISCOUNT eq 'Y'}
		  <tr>
			<td height="26" colspan="3" class="bodytext"><div align="right">Discount Percentage On Basic Price :</div></td>
			<td class="bodytext"><div align="right"><strong>{$DIS_PERC} %</strong></div></td>
		  </tr>
		  <tr>
			<td height="26" colspan="3" class="bodytext"><div align="right">Discount Amount :</div></td>
			<td class="bodytext"><div align="right"><strong>{$DIS_AMT|string_format:"%.2f"}</strong></div></td>
		  </tr>
		  {/if}
		  <tr>
			<td height="26" colspan="3" class="bodytext"><div align="right">Grand Total :</div></td>
			<td class="bodytext"><div align="right"><strong>{$TOTAL_AMOUNT|string_format:"%.2f"}</strong></div></td>
		  </tr>
        </table>		  
         
		  <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
            <tr>
              <td width="100%" class="naH1"> {if $CART_TOTAL > 0}Shipping Details{else} Payment Details{/if} </td>
            </tr>
            <tr>
              <td><table width="100%" border="0" cellpadding="5" cellspacing="0">
                {if $CART_TOTAL > 0}
  <tr class="{cycle name="ship" values="naGrid1,naGrid2"}">
    <td width="50%" height="18" class="bodytext">Shipping Method</td>
    <td width="42%" class="bodytext">: 
      {if $ORDER_DETAILS->shipping_method neq ''}
      {$ORDER_DETAILS->shipping_method}
      {elseif $ORDER_DETAILS->international_order eq 'Y'}
      International Order
      {/if} </td>
  </tr>
  <tr class="{cycle values="nagrid1,nagrid2"}">
    <td height="18" align="left" valign="middle" class="bodytext">Shipping Service</td>
    <td class="bodytext">: {$ORDER_DETAILS->shipping_service}</td>
  </tr>
                {/if}
  <tr class="{cycle values="nagrid1,nagrid2"}">
    <td height="18" align="left" valign="middle" class="bodytext">Mode of Payment</td>
    {if $ORDER_DETAILS->paytype!=""}
    <td class="bodytext">: {$ORDER_DETAILS->paytype}</td>
    {else}
    <td width="8%" class="bodytext">: Through Paypal</td>
    {/if} </tr>
              </table></td>
            </tr>
          </table></td>
	          </tr>
      <tr>
        <td height="30" width="16%"  align="left" valign="top" class="msg"><table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
          <tr>
            <td colspan="2" class="naGridTitle">Transaction Details</td>
          </tr>
          <tr class="naGrid1">
            <td width="22%" height="27">TransactionID</td>
            <td width="78%">:
              {$ORDER_DETAILS->transaction_id|default:"<em>&lt;Not Applicable&gt;</em>"}</td>
          </tr>
          <tr class="naGrid2">
            <td height="27">OrderID</td>
            <td>:
              {$ORDER_DETAILS->order_number}</td>
          </tr>
          <tr class="naGrid1">
            <td height="27">OrderDate</td>
            <td>:
              {$ORDER_DETAILS->date_ordered_f}</td>
          </tr>
          <tr class="naGrid2">
            <td height="27">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
		
	   <tr class="naGrid2">
        <td colspan="5"  align="left">
		{if count($USERTOPIC_LIST) > 0}  
		<table  class="borderblack" cellspacing="0" cellpadding="0" >
  			{foreach from=$USERTOPIC_LIST item=topic}
			<tr class="naGrid2"><td  class="smalltextstyle" align="left"><b>
			<a class="toplink_new" href="#" onclick="javascript:popUp('{makeLink mod=order pg=order}act=notes_view&cat_id={$topic->cat_id}&id={$topic->id}&chk=1{/makeLink}')">
			{$topic->topic_name}</a></b></td>
			</tr>
			<tr bgcolor="#FFFFFF"><td height="1"></td></tr>
			{/foreach}
		 </table>	
    {/if}
		</td></tr>
        </table>		
		</td>
		
		
		   
		<td height="30" colspan="2" align="center" valign="top" class="msg">
		
		
		{if $CHECK_DETAILS->amount ne ''}
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
            <tr>
              <td colspan="2" class="naGridTitle">Check Details</td>
            </tr>
			{if $FIELDS.0==Y}
            <tr class="naGrid1">
              <td width="47%" height="27">Amount</td>
              <td width="53%">:&nbsp;{$CHECK_DETAILS->amount}               </td>
            </tr>
			{/if}
			{if $FIELDS.1==Y}
            <tr class="naGrid2">
              <td height="27">Transit Routing</td>
              <td>:&nbsp;{$CHECK_DETAILS->transit_routing} </td>
            </tr>
			{/if}
			{if $FIELDS.2==Y}
            <tr class="naGrid1">
              <td height="27">Account Number</td>
              <td>:&nbsp;{$CHECK_DETAILS->account_number } </td>
            </tr>
			{/if}
			{if $FIELDS.3==Y}
			 <tr class="naGrid2">
              <td height="27">Check Number</td>
              <td>:&nbsp;{$CHECK_DETAILS->check_number} </td>
            </tr>
			{/if}
			{if $FIELDS.4==Y}
            <tr class="naGrid1">
              <td height="27">Account Type</td>
              <td>:&nbsp;{$CHECK_DETAILS->account_type } </td>
            </tr>
			{/if}
			{if $FIELDS.5==Y}
			 <tr class="naGrid2">
              <td height="27">Bank Name</td>
              <td>:&nbsp;{$CHECK_DETAILS->bank_name} </td>
            </tr>
			{/if}
			{if $FIELDS.6==Y}
            <tr class="naGrid1">
              <td height="27">Bank State</td>
              <td>:&nbsp;{$CHECK_DETAILS->bank_state } </td>
            </tr>
			{/if}
			{if $FIELDS.7==Y}
			 <tr class="naGrid2">
              <td height="27">Social Security Number</td>
              <td>:&nbsp;{$CHECK_DETAILS->social_security_number} </td>
            </tr>
			{/if}
			{if $FIELDS.8==Y}
            <tr class="naGrid1">
              <td height="27">Drivers License</td>
              <td>:&nbsp;{$CHECK_DETAILS->drivers_license} </td>
            </tr>
			{/if}
			{if $FIELDS.9==Y}
			 <tr class="naGrid2">
              <td height="27">Drivers License State </td>
              <td>:&nbsp;{$CHECK_DETAILS->drivers_license_state} </td>
            </tr>
			{/if}
			</table>
		   {/if}	      
		   </td>
		
		
        <td height="30" width="31%" colspan="2" align="right" valign="top" class="msg">
		{if $ORDER_DETAILS->store_name}
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
           <tr>
              <td colspan="2" class="naGridTitle">Shipping Method  </td>
            </tr>
            <tr class="naGrid1">
              <td width="35%">Shipping Service  sdsdsds</td>
              <td width="65%">: 
                {$ORDER_DETAILS->shipping_service} </td>
            </tr>
          </table>
		  {/if}
        <form id="form1" name="form1" method="post" action="" style="margin:0px;">
          <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
            <tr>
              <td colspan="3" class="naGridTitle" >Update Status &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <div align="right" >{if $ORDER_DETAILS->order_history!=''}<strong><u><A href="#" onclick="javascript:popUpComments(); return false;">Previous Comments</A></u></strong>{/if}&nbsp;&nbsp;{if count($ORDER_TRANSACTION_DETAILS) > 0}<A href="#" onclick="javascript:popUpTracking(); return false;">Tracking #</A>{/if}</div></td>
			   </tr>
            <tr class="naGrid1">
              <td width="5%">Action</td>
              <td width="3%">:</td>
              <td width="92%"><select name="order_status">
					<option value="">-- Select a Status --</option>
					{html_options values=$ORDER_UPDATE_STATUS.drop_down_id output=$ORDER_UPDATE_STATUS.value selected=`$ORDER_DETAILS->order_status`}
			   </select>			  </td>
            </tr>
            <tr class="naGrid2">
              <td>Comments</td>
              <td>:</td>
              <td valign="middle"><textarea name="comments" cols="20" rows="6"></textarea></td>
            </tr>
            <tr class="naGrid1">
              <td nowrap="nowrap">Tracking # </td>
              <td nowrap="nowrap">:</td>
              <td><input name="shipping_transaction_no" value="{if $HIDE_TRACK neq 'Y'}{$ORDER_DETAILS->shipping_transaction_no}{/if}" type="text" />&nbsp;<img align="absbottom" src="{$GLOBAL.tpl_url}/images/icon_small_info.gif"  height="16" {popup text="Shipping Transaction Number (if any) for tracking shipping status" width="200" fgcolor="#ddeeff"} />
				<input type="hidden" name="status_key" value="N">				</td>
            </tr>
			 <tr class="naGrid2">
              <td nowrap="nowrap">EDD </td>
              <td nowrap="nowrap">:</td>
              <td><input type="text" name="expt_dlry_date" value="{if $HIDE_TRACK neq 'Y'}{$ORDER_DETAILS->expt_dlry_date}{/if}" size="10" onfocus="popUpCalendar(this, this, 'yyyy-mm-dd', 0, 0)" readonly="readonly" />
				<input type="hidden" name="order_id" value="{$smarty.request.id}">				</td>
            </tr>
			
            <tr class="naGrid1">
              <td nowrap="nowrap">&nbsp;</td>
              <td nowrap="nowrap">&nbsp;</td>
              <td>&nbsp;&nbsp;
                <input type="button" value="Submit" class="naBtn"  onClick="pagesubmit()"/></td>
            </tr>
          </table>
         </form>        </td>
	</tr>
	 {else}
      <tr class="naGrid2">
        <td colspan="5" class="naError" align="center" height="30">No Records</td>
      </tr>
      {/if}
    </table></td>
  </tr>
</table>
	</td>
	</tr>
	{if $ORDER_FIELDS.0 eq 'Y'}
	 <tr>
		<td align="center" width="100%">
			<table align="center" width="99%" border="0" cellspacing="0" cellpadding="0" class="naBrdr">
  				<tr>
    			<td><table width="98%" border="0" align="center">
      			<tr>
        			<td nowrap="nowrap" class="naH1" width="115">Order Notes</td>
        			<td  nowrap="nowrap" align="left"><strong>[<a href="javascript:void(0);" onclick="javascript:manageNewtopic()">Create new Discussion Topic</a>]</strong></td>
      			</tr>
				<tr>
					<td colspan="2"> <div id="div_new_topic" style="display:none;">
						<form id="form1" name="form1" method="post" action="" style="margin:0px;">
					<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
						 {if $smarty.request.err_msg neq ""}
						 <tr >
							<td valign=top colspan=2 align="center">
								<div align=center class="element_style">
									<span class="naError">{$smarty.request.err_msg}</span></div>      </td>
							</tr>
							{/if}
						
						<tr class="naGrid1">
							<td nowrap="nowrap" width="110" height="26">&nbsp;Topic Name&nbsp;&nbsp;&nbsp;&nbsp;:</td>
							<td valign="middle" align="left"> 
								<input name="topic_name" value="{$TOPICS.topic_name}" size="60" type="text" /></td>
						</tr>
						<tr class="naGrid2">
							<td nowrap="nowrap">&nbsp;Description&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
							<td valign="middle" align="left"> 
								<textarea name="body_html"  id="body_html" cols="120">{$TOPICS.topic_desc}</textarea>
								<input type = "hidden" name="user_id" value="{$ORDER_DETAILS->user_id}"></td>
						</tr>
						<tr class="naGrid1">
							<td></td><td nowrap="nowrap" align="left"  height="26"><input type="submit" value="Submit" name="btn_post" class="naBtn" /></td>
						</tr>
					</table>
					</form></div>
					</td>
				</tr>
			
				</table>
				</td>
  				</tr>
				
				{if count($USERTOPIC_LIST) > 0}   
				{foreach from=$USERTOPIC_LIST item=topic_thread} 
				<tr>
					<td  width="100%">
						<table cellpadding="0" border="0" width="100%" cellspacing="1">
							<tr >
								<td  align="center" width="97%">
									<table width="99%"  bgcolor="#485e85" background="#485e85" cellpadding="2"  border="0" cellspacing="0" style="border: 1px solid #485e85;">
										<tr  height="10">
											<td  height="10"   align="left" class="naGridTitle"><strong>Discussion Topic {$topic_thread.topic_name}</strong></td>
											<td    align="right" class="naGridTitle"><strong>[<a onclick="javascript:manageDivthreadPost({$topic_thread.topicID})" >Create new Post</a>]</strong></td>
										</tr>
						<!-- Starts create New Posts -->		
										<tr height="1">
											<td colspan="2"> <div id="div_new_topic_{$topic_thread.topicID}" style="display:none;">
												<form id="form1" name="form1" method="post" action="" style="margin:0px;">
											<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
												 {if $smarty.request.err_msg2 neq ""}
												 <tr >
													<td valign=top colspan=2 align="center">
														<div align=center class="element_style">
															<span class="naError">{$smarty.request.err_msg2}</span></div>      </td>
													</tr>
													{/if}
												
												<tr  class="naGridTitle">
													<td nowrap="nowrap" width="110" height="26"  style="font-weight:normal;">&nbsp;Subject&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
													<td valign="middle" align="left" style="font-weight:normal;"> 
														<input name="subject" value="" size="60" type="text" /></td>
												</tr>
												<tr  class="naGridTitle">
													<td nowrap="nowrap"  style="font-weight:normal;">&nbsp;Comments&nbsp;&nbsp;:</td>
													<td valign="middle" align="left"> 
														<textarea name="body_html"  id="body_html" cols="120"></textarea>
														<input type = "hidden" name="file_id" value="{$ORDER_DETAILS->id}">
														<input type = "hidden" name="topic_id" value="{$topic_thread.topicID}"></td>
												</tr>
												<tr class="naGridTitle">
													<td></td><td nowrap="nowrap" align="left"  height="26"><input type="submit" value="Post" name="submit_post" class="naBtn" /></td>
												</tr>
											</table>
											</form></div>
											</td>
										</tr>
			<!-- Ends create New Posts -->
										<tr class="naGrid2">
									  		<td colspan="2">
												<table align="left" width="100%" cellpadding="4" cellspacing="4">
													<tr>
														<td>{$topic_thread.topic_desc}
														</td>
													</tr>
													{if count($topic_thread.reply) > 0}
													{foreach from=$topic_thread.reply item=topic_thread_post} 
													<tr class="naGrid2">
													  <td colspan="2" width="100%"> 
															<table border="0"  width="96%" align="center" cellpadding="4" cellspacing="0" class="naBrdr">
																<tr>
																	<td align="left" ><strong>{$topic_thread_post.subject}</strong>
																	</td>
																</tr>
																<tr>
																	<td align="left" >{$topic_thread_post.body} 
																	</td>
																</tr>
																<tr>
																	<td align="left" style="color:#828282 "><strong>Posted By: {$topic_thread_post.username} at {$topic_thread_post.posted_date|date_format:"%d %b %Y %I:%M %p"}</strong>
																	</td>
																</tr>
																{if count($topic_thread_post.reply_reply) > 0}
																{foreach from=$topic_thread_post.reply_reply item=topic_thread_reply} 
																<tr>
																	<td align="left"  width="100%">
																		<table border="0"  width="96%" align="center" cellpadding="4" cellspacing="0" >
																			<tr>
																				<td align="left" ><strong>{$topic_thread_reply.subject}</strong></td>
																			</tr>
																			<tr>
																				<td align="left" >{$topic_thread_reply.body}</td>
																			</tr>
																			<tr>
																				<td align="left"  style="color:#828282 "><strong>Posted By: {$topic_thread_reply.username} at {$topic_thread_reply.posted_date|date_format:"%d %b %Y %I:%M %p"}</strong></td>
																			</tr>
																		</table>
																	</td>
																</tr>
																{/foreach}
																{/if}
																<tr bgcolor="#E5E5E5"  style="border: 1px solid #AAAAAA;">
																	<td align="left"  class="smalltextstyle" style=" border-top: 1px solid #AAAAAA;">
																		<table align="center" width="100%" cellpadding="0" cellspacing="0" >
																			<tr>
																				<td align="left">
																					<input type="hidden" name="myid" id="myid" value="" /><a onclick="javascript:manageDiv({$topic_thread_post.threadID})" >Reply</a>
																				</td>
																			</tr>
														<!-- Reply for Posts Starts  -->
																			<tr  bgcolor=""><td><div id="div_{$topic_thread_post.threadID}" style="display:none;">
																			<form id="form1" name="form1" method="post" action="" style="margin:0px;">
													 						<table>
																				<tr >
																					<td nowrap="nowrap" width="110" height="26"  style="font-weight:normal;">&nbsp;Subject&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
																					<td valign="middle" align="left" style="font-weight:normal;"> 
																					<input name="subject" value="" size="60" type="text" /></td>
																				</tr>
																				<tr >
																				<td nowrap="nowrap"  style="font-weight:normal;">&nbsp;Comments&nbsp;&nbsp;:</td>
																					<td valign="middle" align="left"> 
																					<textarea name="body_html"  id="body_html" cols="120"></textarea>
																					<input type = "hidden" name="file_id" value="{$ORDER_DETAILS->id}">
																					<input type = "hidden" name="topic_id" value="{$topic_thread.topicID}"></td>
																				</tr>
																			   <tr><td></td><td align="left" ><input type="submit"  name="submit_post" value="Reply" class="naBtn" />
																				<input type="hidden" name="parent_id" id="parent_id" value="{$topic_thread_post.threadID}" /></td></tr></table></form></div></td></tr>
														<!-- Reply for Posts Ends  -->
																		</table>
																	</td>
																</tr>
													 		</table>
															</td>
														</tr>
													{/foreach}
													{/if}
												</table>
										
											</td>
							  			</tr>
										
										<tr></tr>
									</table>
								</td>
							</tr>
						</table>
				</td>
			 </tr>
			 <tr><td height="10"></td></tr>
			 {/foreach}
			 {/if}
			
		 </table>
		</td>
    </tr> 
	 {/if}
</table>
<br />

