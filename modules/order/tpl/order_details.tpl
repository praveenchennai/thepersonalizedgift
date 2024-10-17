<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></SCRIPT>
<script language="javascript">
{literal}
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
<table align="center" width="96%" border="0" cellspacing="0" cellpadding="0" class="naBrdr">
  <tr>
    <td><table width="98%" align="center">
      <tr>
        <td nowrap="nowrap" class="naH1">Order Details - {$LOG_DET[0]->payment_status} </td>
  
    		   <td align="right" nowrap="nowrap"><strong>{if !$smarty.request.print}<a href="javascript:void(0);" onclick="w=window.open('{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=details&id={$ORDER_DETAILS->id}&print=1{/makeLink}', 'w', 'width=1020,height=700,scrollbars=yes');w.focus();w.click2();">Print </a>{/if}</strong></td>

      </tr>
    </table>
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
                            <tr class="tablegreen2">
                              <td width="109" class="naGridTitle">&nbsp;</td>
                              <td width="2181" class="naGridTitle"><strong>Product Name </strong></td>
                             
                              <td width="194" align="center" class="naGridTitle"><div align="center"><strong>Price</strong></div></td>
                              <td width="300" align="center" class="naGridTitle"><div align="center"><strong>Quantity</strong></div></td>
                              <td width="324" align="center" class="naGridTitle"><div align="right"><strong>Total</strong></div></td>
                            </tr>
                            {foreach from=$ORDER_PRODUCTS.records item=row name=foo}
                            <tr class="naGrid2">
                              <td rowspan="2"><div align="center"><a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=item_details&id={$row->id}&ord_id={$smarty.request.id}&order_ord={$smarty.foreach.foo.index}{/makeLink}"><img src="{$smarty.const.SITE_URL}/modules/cart/images/thumb/{$row->id}.{$row->image_extension}" width="68" height="57"  border="0" align="middle" ></a></div></td>
                              <td><a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=item_details&id={$row->id}&ord_id={$smarty.request.id}&order_ord={$smarty.foreach.foo.index}{/makeLink}" style="text-decoration:none">
                              <strong>{if $row->product_title neq ''}{$row->product_title}{else}{$row->name}{/if}&nbsp;{if $row->brand_id>0}({$row->brand_name}){/if}&nbsp;{if $row->cart_name}:&nbsp;({$row->cart_name}){/if}</strong></a></td>
                             
                              <td height="26" align="center"><div align="right">
                                  {$row->price}
                              </div></td>
                              <td {if $row->accessory}rowspan="2"{/if} align="center"><div align="center">
                                  {$row->quantity}
                              </div></td>
                              <td align="center"><div align="right">
                                  {$row->price*$row->quantity|string_format:"%.2f"}
                              </div></td>
                            </tr>
                            {if $row->accessory}
                            <tr class="naGrid2">
							<td height="35" colspan="2" class="bodytext">
							  <table width="84%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                  <tr class="smallwhite">
                                    <td colspan="2" height="26"><strong>Additional Details</strong></td>
                                    <td width="100" height="26"><div align="right"><strong>Price</strong></div></td>
                                  </tr>
                                  {foreach from=$row->accessory item=sub name=acc}
                                  <tr class="accessory" onmouseover="this.className='accessoryHover';" onmouseout="this.className='accessory';">
                                    <td height="20" width="20" nowrap="nowrap"><strong>{$smarty.foreach.acc.index+1})</td>
                                    <td height="20"><strong>{$sub->type|ucfirst}</strong> : {$sub->name}&nbsp;{if $sub->available_cart_name}({$sub->available_cart_name}){else}{if $sub->cart_name}({$sub->cart_name}){/if}{/if}</td>
                                    <td height="20"><div align="right">
                                      {if $sub->price>0}
                                      {$sub->price}
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
							  
                             
                              <td align="center" class="naGrid2" colspan=2><div align="right">
                                {$row->accessory_price*$row->quantity|string_format:"%.2f"}
                              </div></td>
							 
                            </tr>
                            {/if}     
							<tr class="naGrid1">
                              <td height="20" class="bodytext" colspan="6">&nbsp;</td>
                            </tr>
							<tr class="naGrid2">
                              <td height="20" class="bodytext" colspan="6"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
								  <tr>
									<td width="100" nowrap><strong>Ordered from </strong></td>
									<td width="2"><strong>:</strong></td>
									<td width="80%" align=""><i>{if $row->store_id == '0' }Main Store{else}{$row->store_name}{/if}</i></td>
								  </tr>
								</table></td>
                            </tr>
                            {/foreach}
                            <tr class="naGrid1">
                              <td height="20" class="bodytext" colspan="6"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
								  <tr>
									<td width="80"><strong>Contact Me</strong></td>
									<td width="2"><strong>:</strong></td>
									<td width="80%">{if $ORDER_DETAILS->contact_me=='Y' }YES{else}NO{/if}</td>
								  </tr>
								</table></td>
                            </tr>
							{if $ORDER_DETAILS->notes}
							<tr class="naGrid1">
                              <td height="20" class="bodytext" colspan="6"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
								  <tr>
									<td width="50"><strong>Notes</strong></td>
									<td width="2"><strong>:</strong></td>
									<td width="80%">{$ORDER_DETAILS->notes}</td>
								  </tr>
								</table></td>
                            </tr>
							{/if}
                            {else}
                            <tr class="tablegreen2">
                              <td height="26" colspan="6" class="bodytext"><div align="center"><strong>This order contains no items </strong></div></td>
                            </tr>
                            {/if}
                        </table></td>
                      </tr>
                  </table></td>
        </tr>
      {foreach from=$ORDER_LIST item=row}
      {assign var=comm value=`$comm+$row->item_price_original*$row->item_commission/100`}
      {assign var=tot value=`$tot+$row->item_price`}
      {/foreach}
      <tr>
        <td width="33%" height="30" align="center" valign="top" class="msg"><table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
          <tr>
            <td width="100%" class="naH1">Billing Details</td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellpadding="2" cellspacing="0">

              <tr class="{cycle values="naGrid1,naGrid2"}">
                <td width="50%" height="10" class="bodytext">Customer Name</td>
                <td class="bodytext">: {$ORDER_DETAILS->billing_first_name} {$ORDER_DETAILS->billing_last_name}</td>
              </tr>

              <tr class="{cycle values="naGrid1,naGrid2"}">
                <td height="18" align="left" valign="middle" class="bodytext">Street Address</td>
                <td class="bodytext">: {$ORDER_DETAILS->billing_address1}</td>
              </tr>
			
			{if $ORDER_DETAILS->billing_address2 neq ''}

			<tr class="{cycle values="naGrid1,naGrid2"}">
                <td height="18" align="left" valign="middle" class="bodytext">&nbsp;</td>
                <td class="bodytext">: {$ORDER_DETAILS->billing_address2}</td>
              </tr>
		
			{/if}	

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
                <td class="bodytext" valign="top">:{$ORDER_DETAILS->billing_email}</td>
              </tr>

            </table></td>
          </tr>
        </table></td>
        <td width="33%" align="right" valign="top" class="msg"><table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
          <tr>
            <td width="100%" class="naH1">Shipping Details</td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellpadding="2" cellspacing="0">
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
			
				{if $ORDER_DETAILS->shipping_address2 neq ''}
                <tr class="{cycle values="naGrid1,naGrid2"}">
                  <td height="18" align="left" valign="middle" class="bodytext"></td>
                  <td class="bodytext">&nbsp;&nbsp;&nbsp;{$ORDER_DETAILS->shipping_address2}</td>
                </tr>
				{/if}

               
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
				 
            </table></td>
          </tr>
        </table></td>
        <td height="30" colspan="3" width="34%" align="right" valign="top" class="msg" ><div align="right"><table width="100%" border="0" cellpadding="5" cellspacing="2">
          <tr>
		  <td height="26" colspan="3" class="bodytext"><div align="right">Order Total :</div></td>
		  <td class="bodytext" width="65">
			  <div align="right"><strong>{$CART_TOTAL|string_format:"%.2f"}</strong>
                  </div></td></tr>
		<tr>
			<td height="26" colspan="3" class="bodytext"><div align="right">Shipping  :</div></td>
			<td class="bodytext"><div align="right"><strong>{$SHIPPING_PRICE}</strong></div></td>
		  </tr>
		  {if $ORDER_DETAILS->tax > 0}
		  <tr>
			<!-- <td height="26" colspan="3" class="bodytext"><div align="right">Tax @ {$ORDER_DETAILS->tax}% :</div></td> -->
			<td height="26" colspan="3" class="bodytext"><div align="right">Tax :</div></td>
			<td class="bodytext"><div align="right"><strong>{$TAX_AMOUNT|string_format:"%.2f"}</strong></div></td>
		  </tr>
		  {/if}
		  {if $ORDER_GIFT_CERT || $ORDER_COUPON}
		  <tr>
			<td height="26" colspan="3" class="bodytext"><div align="right">Sub Total :</div></td>
			<td class="bodytext"><div align="right"><strong>{$SUB_TOTAL|string_format:"%.2f"}</strong></div></td>
		  </tr>
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
		  <tr>
			<td height="26" colspan="3" class="bodytext"><div align="right">Grand Total :</div></td>
			<td class="bodytext"><div align="right"><strong>{$ORDER_DETAILS->currency}{$TOTAL_AMOUNT|string_format:"%.2f"}</strong></div></td>
		  </tr>
        </table></div></td>
      </tr>
      <tr>
        <td height="30" width="33%"  align="left" valign="top" class="msg"><table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
          <tr>
            <td colspan="2" class="naGridTitle">Transaction Details</td>
          </tr>
          <tr class="naGrid1">
            <td width="40%" height="27">TransactionID</td>
            <td width="60%">:
              {$ORDER_DETAILS->transaction_id|default:"<em>&lt;Not Applicable&gt;</em>"}</td>
          </tr>
          <tr class="naGrid2">
            <td height="27">Order Number </td>
            <td>:
              {$ORDER_DETAILS->order_number}</td>
          </tr>
          <tr class="naGrid1">
            <td height="27">Order Date</td>
            <td>:
              {$ORDER_DETAILS->date_ordered_f}</td>
          </tr>
          <tr class="naGrid2">
            <td height="27">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
		
		</td>
		<td height="30" width="33%" colspan="2" align="center" valign="top" class="msg">
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
		
		
        <td height="30" width="33%" colspan="2" align="right" valign="top" class="msg"><form id="form1" name="form1" method="post" action="" style="margin:0px;">
          <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
            <tr>
              <td colspan="2" class="naGridTitle">Update Status  <div align="right" > {if $ORDER_DETAILS->order_history!=''}<strong><u><A href="#" onclick="javascript:popUpComments(); return false;">Previous Comments</A></u></strong>{/if}</div></td>
            </tr>
            <tr class="naGrid1">
              <td width="22%">Action </td>
              <td width="78%">: 
                <select name="order_status">
					<option value="">-- Select a Status --</option>
					{html_options values=$ORDER_STATUS.id output=$ORDER_STATUS.name selected=`$ORDER_DETAILS->order_status`}
                </select>
				</td>
            </tr>
            <tr class="naGrid2">
              <td>Comments</td>
              <td>: 
                <input name="comments" type="text" /></td>
            </tr>
            <tr class="naGrid1">
              <td nowrap="nowrap">Transaction # </td>
              <td>: 
                <input name="shipping_transaction_no" value="{$ORDER_DETAILS->shipping_transaction_no}" type="text" />&nbsp;<img align="absbottom" src="{$GLOBAL.tpl_url}/images/icon_small_info.gif" width="17" height="16" {popup text="Shipping Transaction Number (if any) for tracking shipping status" width="200" fgcolor="#ddeeff"} /></td>
            </tr>
            <tr class="naGrid2">
              <td nowrap="nowrap">&nbsp;</td>
              <td> {if !$smarty.request.print} &nbsp;&nbsp;
                <input type="submit" value="Submit" />
				 {/if}
				</td>
            </tr>
          </table>
         </form>
        </td>
      </tr>
	  
	  {if $smarty.request.storename eq ''}
	  <tr><td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td  class="naH1">IPN Message</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
    <td><div style="width:620px; height:550px; overflow:auto; border:1px solid; padding:10px">{$LOG_DET[0]->ipn_variables} </div></td>
  </tr>
   <tr>
  	<td>&nbsp;</td>
  </tr>
   <tr>
  	<td colspan="4"><b>Note:</b> To know more about IPN message <a href="https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_html_IPNandPDTVariables#id091EB04C0HS" target="_blank">Click Here</a></td>
  </tr>
</table>
</td></tr>

	{/if}

      {else}
      <tr class="naGrid2">
        <td colspan="5" class="naError" align="center" height="30">No Records</td>
      </tr>
      {/if}
    </table></td>
  </tr>
</table>
<br />
