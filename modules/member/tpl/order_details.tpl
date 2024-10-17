<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></script>
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="45" valign="middle">&nbsp;&nbsp;&nbsp;<div class="greyboldext">{$MOD_VARIABLES.MOD_HEADS.HD_ORDER_DEATILS}</div> <br>
    <!--<div><hr size="1"  class="border1"/></div>-->
	<div class="hrline"></div> <br></td>
  </tr>
 
  <tr>
    <td height="100%" valign="top"><table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="12" height="56">&nbsp;</td>
        <td colspan="2" class="bodytext"><form id="form1" name="form1" method="post" action="{makeLink mod="member" pg="order"}act=past{/makeLink}" style="margin:0px;">
          <table width="95%"  border="0" cellpadding="0" cellspacing="0" class="bodytext">
            <tr>
              <TD align=middle><div align="right"><STRONG>{$MOD_VARIABLES.MOD_LABELS.LBL_DATE_RANGE}</STRONG> - {$MOD_VARIABLES.MOD_LABELS.LBL_FROM}
                <input type="text" name="date_from" size="10" onfocus="popUpCalendar(this, this, 'yyyy-mm-dd', 0, 0)" readonly="readonly" value="{$smarty.request.date_from}" />
                &nbsp;&nbsp;   {$MOD_VARIABLES.MOD_LABELS.LBL_TO}&nbsp;</div></TD>
              <TD align=right><input type="text" name="date_to" size="10" onfocus="popUpCalendar(this, this, 'yyyy-mm-dd', 0, 0)" readonly="readonly" value="{$smarty.request.date_to}" />
              </TD>
              <TD align=middle><input name="image" type="image" src="{$GLOBAL.tpl_url}/images/submit.jpg" align="absmiddle" border="0"></TD>
            </tr>
          </table>
        </form>
        </td>
      </tr>
      <tr>
        <td height="40" class="bodytext">&nbsp;</td>
        <td valign="top" class="bodytext"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr valign="top">
                <td><table width="582" border="0" align="center" cellpadding="0" cellspacing="0">
                 
                  <tr>
                    <td >&nbsp;</td>
                    <td align="center" valign="top" class="table_bg"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                      {if count($ORDER_DETAILS) > 0}
					  <tr>
                        <td width="11">&nbsp;</td>
                        <td width="545">&nbsp;</td>
                        <td width="15">&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                          <tr>
                            <td valign="top" class="table_bg2"><table width="100%" border="0" align="center" cellpadding="4" cellspacing="1">
                              {if $ORDER_PRODUCTS.records}
							  <tr class="carttr">
								<td height="35" class="table_bg"><strong>{$MOD_VARIABLES.MOD_LABELS.LBL_PRODUCT_NAME} </strong></td>
								<td width="58" height="35" align="center" class="table_bg"><div align="center"><strong>{$MOD_VARIABLES.MOD_LABELS.LBL_PRODUCT_PRICE}</strong></div></td>
								<td width="58" height="35" align="center" class="table_bg"><div align="center"><strong>{$MOD_VARIABLES.MOD_LABELS.LBL_PRODUCT_QTY}</strong></div></td>
								<td width="58" align="center" class="table_bg"><div align="center"><strong>{$MOD_VARIABLES.MOD_LABELS.LBL_TOTAL_AMOUNT}</strong></div></td>
							  </tr>
							  {foreach from=$ORDER_PRODUCTS.records item=row}
							  <tr class="table_bg">
								<td height="26" class="bodytext">{$row->name}</td>
								<td height="26" align="center" class="bodytext">
								  <div align="right">
									{$row->price}
								  </div></td>
								<td {if $row->accessory}rowspan="2"{/if} align="center" class="bodytext">
								  <div align="center">{$row->quantity}</div></td>
								<td align="center" class="bodytext">
								  <div align="right">
									{$row->price*$row->quantity|string_format:"%.2f"}
								  </div>								 </td>
							  </tr>
							  {if $row->accessory}
							  <tr class="table_bg">
								<td height="35" class="bodytext"><table width="94%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                  <tr class="smallwhite">
                                    <td height="26"><strong>{$MOD_VARIABLES.MOD_LABELS.LBL_ADDITIONALS}</strong></td>
                                    <td width="50" height="26"><div align="right"><strong>{$MOD_VARIABLES.MOD_LABELS.LBL_PRODUCT_PRICE}</strong></div></td>
                                  </tr>
                                  {foreach from=$row->accessory item=sub}
                                  <tr class="smallwhite">
                                    <td height="20"><em>
                                      {$sub->category_name}
                                      </em>:
                                      {$sub->name}</td>
                                    <td height="20"><div align="right">
                                      {if $sub->price>0}
                                      {$sub->price}
                                      {else}
                                      -&nbsp;&nbsp;&nbsp;
                                      {/if}
                                    </div></td>
                                  </tr>
                                  {/foreach}
                                </table></td>
								<td height="35" align="center" class="bodytext"><div align="right">{$row->accessory_price|string_format:"%.2f"}</div></td>
								<td align="center" class="bodytext"><div align="right">{$row->accessory_price*$row->quantity|string_format:"%.2f"}</div></td>
							  </tr>
							  {/if}
							  <tr class="table_bg">
								<td height="20" class="bodytext" colspan="4">&nbsp;</td>
							  </tr>
							  {/foreach} 
                                  <tr>
                                    <td height="26" colspan="3" class="table_bg"><div align="right">{$MOD_VARIABLES.MOD_LABELS.LBL_CART_TOTAL}</div></td>
                                    <td class="table_bg" width="65"><div align="right"> <strong>
                                      {$CART_TOTAL|string_format:"%.2f"}
                                    </strong> </div></td>
                                  </tr>
                                  <tr>
                                    <td height="26" colspan="3" class="table_bg"><div align="right">{$MOD_VARIABLES.MOD_LABELS.LBL_SHIPPING_PRICE}</div></td>
                                    <td class="table_bg"><div align="right"><strong>
                                      {$SHIPPING_PRICE}
                                    </strong></div></td>
                                  </tr>
                                  {if $ORDER_DETAILS->tax > 0}
                                  <tr>
                                    <td height="26" colspan="3" class="table_bg"><div align="right">{$MOD_VARIABLES.MOD_LABELS.LBL_TAX} @
                                      {$ORDER_DETAILS->tax}
                                      % :</div></td>
                                    <td class="table_bg"><div align="right"><strong>
                                      {$ORDER_DETAILS->tax*$CART_TOTAL/100|string_format:"%.2f"}
                                    </strong></div></td>
                                  </tr>
                                  {/if}
                                  {if $ORDER_GIFT_CERT || $ORDER_COUPON}
                                  <tr>
                                    <td height="26" colspan="3" class="table_bg"><div align="right">{$MOD_VARIABLES.MOD_LABELS.LBL_SUB_TOTAL}</div></td>
                                    <td class="table_bg"><div align="right"><strong>
                                      {$SUB_TOTAL|string_format:"%.2f"}
                                    </strong></div></td>
                                  </tr>
                                  {/if}
                                  {if $ORDER_COUPON}
                                  <tr>
                                    <td height="26" colspan="3" class="table_bg"><div align="right">Coupon
                                      {if $ORDER_COUPON->coupon_amounttype=='P'}
                                      -
                                      {$ORDER_COUPON->coupon_amount}
                                      % Discount
                                      {/if}
                                      :</div></td>
                                    <td class="table_bg"><div align="right"><strong>
                                      {$COUPON_AMOUNT|string_format:"%.2f"}
                                    </strong></div></td>
                                  </tr>
                                  {/if}
                                  {if $ORDER_GIFT_CERT}
                                  <tr>
                                    <td height="26" colspan="3" class="table_bg"><div align="right">{$MOD_VARIABLES.MOD_LABELS.LBL_GIFT_CERTIFICATE}</div></td>
                                    <td class="table_bg"><div align="right"><strong>
                                      {$CERTIFICATE_AMOUNT|string_format:"%.2f"}
                                    </strong></div></td>
                                  </tr>
                                  {/if}
                                  <tr>
                                    <td height="26" colspan="3" class="table_bg"><div align="right">{$MOD_VARIABLES.MOD_LABELS.LBL_GRANT_TOTAL}</div></td>
                                    <td class="table_bg"><div align="right"><strong>
                                      {$TOTAL_AMOUNT|string_format:"%.2f"}
                                    </strong></div></td>
                                  </tr>
						  {else}
							  <tr class="table_bg">
								<td height="26" colspan="4" class="bodytext"><div align="center"><strong>There is no order with this Order # : {$smarty.request.id}</strong></div></td>
							  </tr>
						  {/if}
                            </table></td>
                          </tr>
                        </table></td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                          <tr>
                            <td valign="top" class="table_bg2"><table width="100%" border="0" align="center" cellpadding="4" cellspacing="1">
                              <tr class="carttr">
                                <td width="281" height="35" class="whiteboltext">{$MOD_VARIABLES.MOD_HEADS.HD_BILL_DETAILS} </td>
                                <td width="246" height="35" class= "whiteboltext">{$MOD_VARIABLES.MOD_HEADS.HD_SHIP_DETAILS} </td>
                              </tr>
                              <tr class="table_bg">
                                <td height="26" valign="top" class="bodytext"><table width="100%" border="0" cellpadding="5" cellspacing="0">
                                  <tr class="{cycle values="naGrid1,naGrid2"}">
                                    <td width="40%" height="18" class="bodytext">{$MOD_VARIABLES.MOD_LABELS.LBL_CUSTOMER_NAME}</td>
                                    <td class="bodytext">:
                                      {$ORDER_DETAILS->billing_first_name}
                                        {$ORDER_DETAILS->billing_last_name}</td>
                                  </tr>
                                  <tr class="{cycle values="naGrid1,naGrid2"}">
                                    <td height="18" align="left" valign="middle" class="bodytext">{$MOD_VARIABLES.MOD_LABELS.LBL_STREET_ADDRESS1}</td>
                                    <td class="bodytext">:
                                      {$ORDER_DETAILS->billing_address1}</td>
                                  </tr>
                                  <tr class="{cycle values="naGrid1,naGrid2"}">
                                    <td height="18" align="left" valign="middle" class="bodytext"></td>
                                    <td class="bodytext">&nbsp;&nbsp;{$ORDER_DETAILS->billing_address2}</td>
                                  </tr>
                                  <tr class="{cycle values="naGrid1,naGrid2"}">
                                    <td height="18" class="bodytext">{$MOD_VARIABLES.MOD_LABELS.LBL_BILL_CITY}</td>
                                    <td class="bodytext">:
                                      {$ORDER_DETAILS->billing_city}</td>
                                  </tr>
                                  <tr class="{cycle values="naGrid1,naGrid2"}">
                                    <td height="18" class="bodytext">{$MOD_VARIABLES.MOD_LABELS.LBL_BILL_STATE_PROVINCE1}</td>
                                    <td class="bodytext">:
                                      {$ORDER_DETAILS->billing_state}</td>
                                  </tr>
                                  <tr class="{cycle values="naGrid1,naGrid2"}">
                                    <td height="18" class="bodytext">{$MOD_VARIABLES.MOD_LABELS.LBL_ZIP1}</td>
                                    <td class="bodytext">:
                                      {$ORDER_DETAILS->billing_postalcode}</td>
                                  </tr>
                                  <tr class="{cycle values="naGrid1,naGrid2"}">
                                    <td height="18" class="bodytext">{$MOD_VARIABLES.MOD_LABELS.LBL_BILL_COUNTRY}</td>
                                    <td class="bodytext">:
                                      {$ORDER_DETAILS->billing_country}</td>
                                  </tr>
                                  <tr class="{cycle values="naGrid1,naGrid2"}">
                                    <td height="18" class="bodytext">{$MOD_VARIABLES.MOD_LABELS.LBL_BILL_TELE}</td>
                                    <td class="bodytext">:
                                      {$ORDER_DETAILS->billing_telephone}</td>
                                  </tr>
                                  <tr class="{cycle values="naGrid1,naGrid2"}">
                                    <td height="18" class="bodytext">{$MOD_VARIABLES.MOD_LABELS.LBL_MOBLIE}</td>
                                    <td class="bodytext">:
                                      {$ORDER_DETAILS->billing_mobile}</td>
                                  </tr>
                                </table></td>
                                <td height="26" align="left" valign="top" class="bodytext"><table width="100%" border="0" cellpadding="5" cellspacing="0">
                                  <tr class="{cycle name="ship" values="naGrid1,naGrid2"}">
                                    <td width="40%" height="18" class="bodytext">{$MOD_VARIABLES.MOD_LABELS.LBL_CUSTOMER_NAME}</td>
                                    <td class="bodytext">:
                                      {$ORDER_DETAILS->shipping_first_name}
                                        {$ORDER_DETAILS->shipping_last_name}</td>
                                  </tr>
                                  <tr class="{cycle values="naGrid1,naGrid2"}">
                                    <td height="18" align="left" valign="middle" class="bodytext">{$MOD_VARIABLES.MOD_LABELS.LBL_STREET_ADDRESS1}</td>
                                    <td class="bodytext">:
                                      {$ORDER_DETAILS->shipping_address1}</td>
                                  </tr>
                                  <tr class="{cycle values="naGrid1,naGrid2"}">
                                    <td height="18" align="left" valign="middle" class="bodytext"></td>
                                    <td class="bodytext">&nbsp;&nbsp;{$ORDER_DETAILS->shipping_address2}</td>
                                  </tr>
                                  <tr class="{cycle values="naGrid1,naGrid2"}">
                                    <td height="18" class="bodytext">{$MOD_VARIABLES.MOD_LABELS.LBL_BILL_CITY}</td>
                                    <td class="bodytext">:
                                      {$ORDER_DETAILS->shipping_city}</td>
                                  </tr>
                                  <tr class="{cycle values="naGrid1,naGrid2"}">
                                    <td height="18" class="bodytext">{$MOD_VARIABLES.MOD_LABELS.LBL_BILL_STATE_PROVINCE1}</td>
                                    <td class="bodytext">:
                                      {$ORDER_DETAILS->shipping_state}</td>
                                  </tr>
                                  <tr class="{cycle values="naGrid1,naGrid2"}">
                                    <td height="18" class="bodytext">{$MOD_VARIABLES.MOD_LABELS.LBL_ZIP1}</td>
                                    <td class="bodytext">:
                                      {$ORDER_DETAILS->shipping_postalcode}</td>
                                  </tr>
                                  <tr class="{cycle values="naGrid1,naGrid2"}">
                                    <td height="18" class="bodytext">{$MOD_VARIABLES.MOD_LABELS.LBL_BILL_COUNTRY}</td>
                                    <td class="bodytext">:
                                      {$ORDER_DETAILS->shipping_country}</td>
                                  </tr>
                                  <tr class="{cycle values="naGrid1,naGrid2"}">
                                    <td height="18" class="bodytext">{$MOD_VARIABLES.MOD_LABELS.LBL_BILL_TELE}</td>
                                    <td class="bodytext">:
                                      {$ORDER_DETAILS->shipping_telephone}</td>
                                  </tr>
                                  <tr class="{cycle values="naGrid1,naGrid2"}">
                                    <td height="18" class="bodytext">{$MOD_VARIABLES.MOD_LABELS.LBL_MOBLIE}</td>
                                    <td class="bodytext">:
                                      {$ORDER_DETAILS->shipping_mobile}</td>
                                  </tr>
                                </table></td>
                              </tr>
                            </table></td>
                          </tr>
                        </table></td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td class="table_bg2"><table width="100%" border="0" align="center" cellpadding="4" cellspacing="1">
                          <tr class="carttr">
                            <td width="281" height="35" class="whiteboltext">{$MOD_VARIABLES.MOD_HEADS.HD_TRANSACTION_DEATILS}</td>
                            <td width="246" height="35" class= "whiteboltext">{$MOD_VARIABLES.MOD_HEADS.HD_STATUS} </td>
                          </tr>
                          <tr class="table_bg">
                            <td height="26" valign="top" class="bodytext"><table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">

                              <tr class="naGrid1">
                                <td width="22%" height="27">{$MOD_VARIABLES.MOD_LABELS.LBL_TRANSACTION_ID}</td>
                                <td width="78%">:
                                  {$ORDER_DETAILS->transaction_id|default:"<em>&lt;Not Applicable&gt;</em>"}</td>
                              </tr>
                              <tr class="naGrid2">
                                <td height="27">{$MOD_VARIABLES.MOD_LABELS.LBL_ORDER_ID}</td>
                                <td>:
                                  {$ORDER_DETAILS->id}</td>
                              </tr>
                              <tr class="naGrid1">
                                <td height="27">{$MOD_VARIABLES.MOD_LABELS.LBL_ORDER_DATE}</td>
                                <td>:
                                  {$ORDER_DETAILS->date_ordered_f}</td>
                              </tr>
                              <tr class="naGrid2">
                                <td height="27">&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                            </table></td>
                            <td height="26" align="left" valign="top" class="bodytext"><table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">

                              <tr class="naGrid1">
                                <td width="22%">{$MOD_VARIABLES.MOD_LABELS.LBL_STATUS}</td>
                                <td width="78%">: {$ORDER_DETAILS->status}</td>
                              </tr>
                              <tr class="naGrid2">
                                <td>{$MOD_VARIABLES.MOD_LABELS.LBL_COMMENTS}</td>
                                <td>: </td>
                              </tr>
                              <tr class="naGrid1">
                                <td nowrap="nowrap">{$MOD_VARIABLES.MOD_LABELS.LBL_TRANSACTIONS} </td>
                                <td>: {$ORDER_DETAILS->shipping_transaction_no}</td>
                              </tr>
                            </table></td>
                          </tr>
                        </table></td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
					  {else}
					  <tr>
                        <td colspan="3"><div align="center"><strong>{$MOD_VARIABLES.MOD_LABELS.LBL_NO_ORDER_ID}
                              {$smarty.request.id}
                        </strong></div></td>
                      </tr>
  					  {/if}
                    </table></td>
                    <td >&nbsp;</td>
                  </tr>
                  
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
        <td valign="top">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="38" valign="top">&nbsp;</td>
  </tr>
</table>
