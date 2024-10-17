<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
<table width="97%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="96%" border="0" cellspacing="0" cellpadding="0" class="naBrdr">
  <tr>
    <td>
		<table width="98%" align="center">
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
        <td height="24" colspan="2" align="center" nowrap="nowrap"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                      <tr>
                        <td valign="top"  class="naFooter"><table width="100%" border="0" align="center" cellpadding="4" cellspacing="1">
                            {if $ORDER_PRODUCTS.records}
                            <tr class="tablegreen2">
                              <td width="1916" class="naGridTitle"><strong>Product Name </strong></td>
                             {if $GLOBAL.assign_supplier=='Y'} <td width="351" height="25" class="naGridTitle">&nbsp;</td>{/if}
                              <td width="288" align="center" class="naGridTitle"><div align="center"><strong>Price</strong></div></td>
                              <td width="390" align="center" class="naGridTitle"><div align="center"><strong>Quantity</strong></div></td>
                              <td width="323" align="center" class="naGridTitle"><div align="right"><strong>Total</strong></div></td>
                            </tr>
                            {foreach from=$ORDER_PRODUCTS.records item=row}
                            <tr class="naGrid2">
                              <td><strong>{$row->name}</strong></td>
                              {if $GLOBAL.assign_supplier=='Y'}<td height="26"><a href="{makeLink mod=order pg=order}act=assign_supplier&order_id={$ORDER_ID}&id={$row->product_id}&base_quantity={$row->quantity}{/makeLink}">Assign Supplier</a></td>{/if}
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
                              <td height="35" colspan="2" class="bodytext"><table width="84%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                  <tr class="smallwhite">
                                    <td colspan="2" height="26"><strong>Options</strong></td>
                                    <td width="100" height="26"><div align="right"><strong>Price</strong></div></td>
                                  </tr>
                                  {foreach from=$row->accessory item=sub name=acc}
                                  <tr class="accessory" onmouseover="this.className='accessoryHover';" onmouseout="this.className='accessory';">
                                    <td height="20" width="20" nowrap="nowrap"><strong>{$smarty.foreach.acc.index+1})</td>
                                    <td height="20"><strong>{$sub->category_name}</strong> : {$sub->name}</td>
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
                                    <td height="20"><strong>Additional Customization text</strong> : {$sub->addl_customization_text}</td>
                                    <td height="20">&nbsp;</td>
                                  </tr>
								  {/if}
                                  {/foreach}
                              </table></td>
                              <td height="35" align="center" class="naGrid2"><div align="right">
                                {$row->accessory_price|string_format:"%.2f"}
                              </div></td>
                              <td align="center" class="naGrid2"><div align="right">
                                {$row->accessory_price*$row->quantity|string_format:"%.2f"}
                              </div></td>
                            </tr>
                            {/if}
                            <tr class="naGrid1">
                              <td height="20" class="bodytext" colspan="5">&nbsp;</td>
                            </tr>
                            {/foreach}
                            
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
        <td align="center" valign="top" class="msg">
		<form id="form1" name="form1" method="post" action="" style="margin:0px;">
		  <table width="100%" class="naBrdr">
            <tr>
              <td colspan="2">
			  <table width="98%" align="center">
		  <tr>
			<td nowrap="nowrap" class="naH1">{$PRODUCT_DETAILS.name}</td>
			<td align="right" nowrap="nowrap">&nbsp;</td>
		  </tr>
		</table>			  </td>
            </tr>
            <tr>
              <td width="45%">
			  <table width="74%" border="0" align="center" cellpadding="5" cellspacing="0">
           
            <tr>
              <td colspan="2" class="naGridTitle">Assign Supplier </td>
            </tr>            
            <tr class="naGrid1">
              <td width="42%">Supplier</td>
              <td width="58%">: 
                <select name="user_id">
				  <option value="">-- Select a Supplier --</option>
					{html_options values=$SUPPLIER.id output=$SUPPLIER.name selected=`$smarty.request.user_id`}
                </select>				</td>
            </tr>
            <tr class="naGrid2">
              <td>Quantity</td>
              <td>: 
                <input name="quantity" type="text"  size="4"/>
                <input name="order_id" type="hidden" id="order_id"  value="{$ORDER_ID}"/>
                <input name="product_id" type="hidden" id="product_id"  value="{$PRODUCT_ID}"/>
				<input name="base_quantity" type="hidden" id="base_quantity"  value="{$smarty.request.base_quantity}"/>			  </td>
            </tr>
            
            <tr class="naGrid1">
              <td nowrap="nowrap">&nbsp;</td>
              <td>&nbsp;&nbsp;
                <input type="submit" value="Submit"/>			 </td>
            </tr>
          </table></td>
              <td width="55%">
			   <table width="75%" border="0" align="left" cellpadding="5" cellspacing="0">
          <tr>
            <td colspan="2" class="naGridTitle">Quantity Details  </td>
          </tr>
          <tr class="naGrid1">
            <td width="41%">Assigned Quantity</td>
            <td width="59%">:{if $TOTAL_ASSIGNED ne ''}{$TOTAL_ASSIGNED}{else}0{/if}</td>
          </tr>
          <tr class="naGrid2">
            <td>Balance Quantity</td>
            <td>:{$BALANCE_AMOUNT}</td>
          </tr>
          <tr class="naGrid1">
            <td nowrap="nowrap">&nbsp;</td>
            <td>&nbsp;&nbsp;</td>
          </tr>
        </table></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
		</form>		
		</td>
        </tr>
      <tr>
        <td height="30"  align="left" valign="top" class="msg">
		<table width="100%" class="naBrdr">
          <tr>
            <td>
			<table width="98%" align="center">
                <tr>
                  <td nowrap="nowrap" class="naH1">{$PRODUCT_DETAILS.name} Assigned Details </td>
                  <td align="right" nowrap="nowrap">&nbsp;</td>
                </tr>
            </table>
			</td>
          </tr>
          <tr>
            <td >
		<form name="frm_supplier" method="post" action="" style="margin: 0px;">
			<table width="60%" border="0" align="center" cellpadding="4" cellspacing="0">
                            {if count($ASSIGNED_DETAILS) > 0}
                            <tr class="naGrid1">
                              <td align="center" class="naGrid1"><a class="linkOneActive" href="#" onclick="javascript:if(confirm('Are you sure you want to delete?')){ldelim}document.frm_supplier.action='{makeLink mod=$smarty.request.mod pg=order}act=delete_supllier{/makeLink}'; document.frm_supplier.submit();{rdelim}">
                                <input type="hidden" name="order_id" value="{$ORDER_ID}"/>
                                <input name="id" type="hidden" id="id" value="{$smarty.request.id}" />
								<input name="base_quantity" type="hidden" id="base_quantity" value="{$smarty.request.base_quantity}" />
                              Delete </a></td>
                              <td align="center" class="naGrid1">&nbsp;</td>
                              <td align="center" class="naGrid1">&nbsp;</td>
                              <td height="25" align="center" class="naGrid1">&nbsp;</td>
                            </tr>
                            <tr class="tablegreen2">
                              <td width="548" align="center" class="naGridTitle">
                                <input type="checkbox" name="select_all" onclick="javascript:CheckCheckAll(document.frm_supplier,'sup_id[]')" />                              </td>
                              <td width="548" align="center" class="naGridTitle"><strong>Supplier Name </strong></td>
                              <td width="989" align="center" class="naGridTitle"><strong>Quantity</strong></td>
                              <td width="989" height="25" align="center" class="naGridTitle"><strong>Assigned Date </strong></td>                            
                            </tr>
                            {foreach from=$ASSIGNED_DETAILS item=row}
                            <tr class="{cycle values="naGrid1,naGrid2"}">
                              <td align="center"><input type="checkbox" name="sup_id[]" value="{$row->sup_id}"></td>
                              <td align="center">{$row->first_name}&nbsp;{$row->last_name}</td>
                          	  <td align="center">{$row->total}</td>
                          	  <td align="center">{$row->assigned_date}</td>                       
						    </tr>                           
                            {/foreach}                            
                            {else}
                            <tr class="tablegreen2">
                              <td height="26" colspan="4" class="bodytext"><div align="center"><strong>This order contains no items </strong></div></td>
                            </tr>
                            {/if}
                  </table>
			</form>				  
			</td>
          </tr>
        </table></td>
        </tr>
     {/if}
    </table></td>
  </tr>
</table>
<br/>
