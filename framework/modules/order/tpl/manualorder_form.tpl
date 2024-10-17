<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/scripts/validator.js"></SCRIPT>
<table width="820" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table width="100%"  border="0">
  <tr>
    <td valign="top">
<table width=85% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
<form action="" method="post" enctype="multipart/form-data" name="frm">
  	<input type="hidden" name="act" value="{$smarty.request.act}">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
	<input type="hidden" name="mId" value="{$smarty.request.mId}">
	<input type="hidden" name="id" value="{$smarty.request.id}">
    <tr align="left">
      <td colspan=9 valign=top><table width="400%" align="center">
        <tr>
          <td width="23%" nowrap class="naH1">{$smarty.request.sId} </td>
          <td width="77%" align="right" nowrap class="titleLink">&nbsp;<!--<a href="{makeLink mod=order pg=order}act=shippingselmethodslist{/makeLink}&store_id={$smarty.request.store_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}">{$smarty.request.sId} List</a>--></td>
        </tr>
      </table></td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=9>
		<div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>      </td>
    </tr>
    {/if}
	<tr class=naGrid2>
      <td valign=top colspan=9 >&nbsp;</td>
    </tr>
    <tr class=naGrid2>
      <td width="74"  align="right" valign=top><span class="fieldname"> Order Number </span></td>
      <td width="9" valign=top><span class="fieldname">: </span></td>
      <td width="155" align="left"><span class="formfield">
        <input name="order_number" type="text" class="Form2" id="order_number" value="{$FORM_VALUES.order_number}" size="20" maxlength="30" {if $FORM_VALUES.order_type eq 'ONLINE'}readonly="1"{/if} />
      </span></td>
      <td width="106" align="right">Date Ordered </td>
      <td width="3" align="right">:</td>
      <td width="120" align="left"><span class="formfield">
        <input name="date_ordered" type="text" class="Form2" id="date_ordered" value="{$FORM_VALUES.date_ordered}" size="10" maxlength="30" />
      </span></td>
      <td width="87" align="right">Action</td>
      <td width="3" align="center">:</td>
      <td width="165" align="left"><select name="order_status" id="order_status">
        <option value="">-- Select a Status --</option>
        
				{html_options values=$ORDER_UPDATE_STATUS.drop_down_id output=$ORDER_UPDATE_STATUS.value selected=`$FORM_VALUES.order_status`}
        
      </select></td>
    </tr>
    <tr class=naGrid2>
      <td height="57"  align="right" valign=middle><span class="fieldname">Customer</span></td>
      <td valign=middle><span class="fieldname">:</span></td>
      <td align="left" valign="middle"><span class="formfield">
        <input name="customer_name" type="text" class="Form2" id="customer_name" value="{$FORM_VALUES.customer_name}" size="25" {if $FORM_VALUES.order_type eq 'ONLINE'}readonly="1"{/if} />
      </span></td>
      <td align="right" valign="middle">Date Shipped </td>
      <td align="right">:</td>
      <td align="left"><span class="formfield">
        <input  name="ship_date" type="text" class="Form2" id="ship_date" value="{$FORM_VALUES.date_ordered}" size="10" maxlength="30" />
      </span></td>
      <td align="right" valign="middle">Comments</td>
      <td align="center">:</td>
      <td align="left"><textarea name="comments" rows="3" id="comments">{$FORM_VALUES.comments}</textarea></td>
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=top>Item Orderd </td>
      <td valign=top>:</td>
      <td align="left"><label>
        <input name="item_ordered" type="checkbox" id="item_ordered" value="Yes" {if $FORM_VALUES.item_ordered eq 'Y'}checked{/if}>
      </label></td>
      <td align="right">Rush Order </td>
      <td align="right">:</td>
      <td align="left"><span class="formfield">
        <input  name="rush_order" type="text" class="Form2" id="rush_order" value="{$FORM_VALUES.rush_order}" size="10" maxlength="30" />
      </span></td>
      <td align="right">Transaction # </td>
      <td align="center">:</td>
      <td align="left"><span class="formfield">
        <input  name="shipping_transaction_no" type="text" class="Form2" id="shipping_transaction_no" value="{$FORM_VALUES.shipping_transaction_no}" size="20" maxlength="30" />
      </span></td>
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=top>&nbsp;</td>
      <td valign=top>&nbsp;</td>
      <td align="left">&nbsp;               </td>
      <td align="right">Items on Back Order </td>
      <td align="right">&nbsp;</td>
      <td align="left"><input name="back_order" type="checkbox" id="back_order" value="Yes" {if $FORM_VALUES.back_order eq 'Y'}checked{/if} ><input name="back_order1" type="checkbox" id="back_order1" value="Yes" {if $FORM_VALUES.back_order1 eq 'Y'}checked{/if}></td>
      <td align="right">Bill Shipping</td>
      <td align="center">:</td>
      <td align="left"><label><input name="billing_Shipping" type="checkbox" id="billing_Shipping" value="Yes" {if $FORM_VALUES.billing_Shipping eq 'Y'}checked{/if}></label><label>&nbsp;&nbsp;Complete <input name="billed_shipping" type="checkbox" id="billed_shipping" value="Yes" {if $FORM_VALUES.billed_shipping eq 'Y'}checked{/if}></label></td>
    </tr>
    <tr class=naGrid2>
      <td colspan="3"  align="left" valign=top>Order Summary </td>
      <td align="right">Date Expected </td>
      <td align="right">:</td>
      <td align="left"><span class="formfield">
        <input  name="date_expected" type="text" class="Form2" id="date_expected" value="{$FORM_VALUES.date_expected}" size="10" maxlength="30" />
      </span></td>
      <td align="right">&nbsp;</td>
      <td align="center">&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr class=naGrid2>
      <td colspan="7"  align="left" valign=middle>        <textarea name="order_summary" cols="39" rows="6" id="order_summary">{$FORM_VALUES.order_summary}</textarea>      &nbsp;
        <textarea name="backorder_summary" cols="39" rows="6" id="backorder_summary">{$FORM_VALUES.backorder_summary}</textarea></td>
      <td align="center">&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    
    
   <tr class=naGrid2>
     <td colspan="9" valign=top>&nbsp;	 </td>
     </tr> 
    <tr class="naGridTitle"> 
      <td colspan=9 valign=center><div align=center>	  
	       <input type=submit name="Submit" value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div>	 </td> 
    </tr> 
  </form> 
</table>
</td>
  </tr>
</table>