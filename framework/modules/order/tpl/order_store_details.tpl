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
        <td nowrap="nowrap" class="naH1">Order Details </td>
        <td align="right" nowrap="nowrap"><strong>{if !$smarty.request.print}<a href="javascript:void(0);" onclick="w=window.open('{makeLink mod=order pg=order}act=details&id={$ORDER_DETAILS->id}&print=1{/makeLink}', 'w', 'width=1020,height=700,scrollbars=yes');w.focus();w.onload=function () {literal}{window.print();}{/literal}">Print</a>{/if}</strong></td>
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
                              <td width="1916" class="naGridTitle"><strong>Product Name </strong></td>                           
							  <td width="288" align="center" class="naGridTitle"><div align="center"><strong>Price</strong></div></td>
                              <td width="390" align="center" class="naGridTitle"><div align="center"><strong>Quantity</strong></div></td>
                              <td width="323" align="center" class="naGridTitle"><div align="right"><strong>Total</strong></div></td>
                            </tr>
                            {foreach from=$ORDER_PRODUCTS.records item=row}
                            <tr class="naGrid2">
                              <td><strong>{if $GLOBAL.assign_supplier=='Y'}<a href="{makeLink mod=order pg=order}act=item_details&id={$row->id}&ord_id={$smarty.request.id}{/makeLink}"><img src="{$smarty.const.SITE_URL}/modules/cart/images/thumb/{$row->id}.jpg" width="68" height="57"  border="0" align="middle" ></a>{/if}{$row->name}&nbsp;{if $row->brand_id>0}({$row->brand_name}){/if}&nbsp;{if $row->cart_name}:&nbsp;({$row->cart_name}){/if}</strong></td>
                              <td height="26" align="center"><div align="right">
                                  {$row->price}
                              </div></td>
                              <td {if $row->accessory}rowspan="2"{/if} align="center">
								  <div align="center">
									  {$row->quantity}
								  </div>
							  </td>
                              <td align="center">
							  <div align="right">
                                  {$row->price*$row->quantity|string_format:"%.2f"}
                              </div>
							  </td>
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
                              <td height="35" align="center" class="naGrid2">
								  <div align="right">
									{$row->accessory_price|string_format:"%.2f"}
								  </div>
							  </td>
                              <td align="center" class="naGrid2">
							  <div align="right">
                                {$row->accessory_price*$row->quantity|string_format:"%.2f"}
                              </div>
							  </td>
                            </tr>
                            {/if}
							 <tr class="naGrid2">
                            <td height="20" class="bodytext" colspan="5"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
								  <tr>
									<td width="80"><strong>Contact Me</strong></td>
									<td width="2"><strong>:</strong></td>
									<td width="80%">{if $row->contact_me=='Y' }YES{else}NO{/if}</td>
								  </tr>
								</table>
								</td>
                          </tr>
						  <tr class="naGrid2">
                            <td height="20" class="bodytext" colspan="5"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
								  <tr>
									<td width="50"><strong>Notes</strong></td>
									<td width="2"><strong>:</strong></td>
									<td width="80%">{$row->notes}</td>
								  </tr>
								</table>
								</td>
                          </tr>
                            <tr class="naGrid1">
                              <td height="20" class="bodytext" colspan="5">&nbsp;</td>
                            </tr>
                            {/foreach}
                            <tr class="naGrid1">
                              <td height="20" class="bodytext" colspan="5"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
								  <tr>
									<td width="80"><strong>Contact Mes</strong></td>
									<td width="2"><strong>:</strong></td>
									<td width="80%">{if $ORDER_DETAILS->contact_me=='Y' }YES{else}NO{/if}</td>
								  </tr>
								</table></td>
                            </tr>
							{if $ORDER_DETAILS->notes}
							<tr class="naGrid1">
                              <td height="20" class="bodytext" colspan="5"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
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
                              <td height="26" colspan="5" class="bodytext"><div align="center"><strong>This order contains no items </strong></div></td>
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
        <td width="33%" height="30" align="center" valign="top" class="msg">&nbsp;</td>
        <td width="33%" align="right" valign="top" class="msg">&nbsp;</td>
        <td height="30" colspan="2" width="34%" align="right" valign="top" class="msg"><table width="100%" border="0" cellpadding="5" cellspacing="2">
          <tr>
		  <td height="26" colspan="3" class="bodytext"><div align="right">Cart Total :</div></td>
		  <td class="bodytext" width="65">
		  <div align="right">
			<strong>{$CART_TOTAL|string_format:"%.2f"}</strong>
		  </div>
		  </td>
		</tr>
		<tr>
			<td height="26" colspan="3" class="bodytext"><div align="right">Shipping Price :</div></td>
			<td class="bodytext"><div align="right"><strong>{$SHIPPING_PRICE}</strong></div></td>
		  </tr>
		  {if $ORDER_DETAILS->tax > 0}
		  <tr>
			<td height="26" colspan="3" class="bodytext"><div align="right">Tax @ 
			  {$ORDER_DETAILS->tax}
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
			<td class="bodytext"><div align="right"><strong>{$TOTAL_AMOUNT|string_format:"%.2f"}</strong></div></td>
		  </tr>
        </table></td>
      </tr>
      {else}
      <tr class="naGrid2">
        <td colspan="5" class="naError" align="center" height="30">No Records</td>
      </tr>
      {/if}
    </table></td>
  </tr>
</table>
<br/>
