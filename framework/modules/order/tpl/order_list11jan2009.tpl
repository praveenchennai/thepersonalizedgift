<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></SCRIPT>
{literal}
<script language="javascript">
function popupWindow(url) {
window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=900,height=300,screenX=150,screenY=150,top=150,left=150')
}

</script>
{/literal}
<table width="90%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table border="0" cellspacing="0" cellpadding="4" width="100%" align="center"> 
        <tr>
		  <td height="30" nowrap class="naH1"><!--Orders{$SUBNAME}-->View Orders</td> 
          <td nowrap align="right" class="titleLink" width="100%">&nbsp;</td> 
        </tr>
        <tr>
          <td colspan="2" align="right" class="naGrid1"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
			              <td align="center"><form id="form1" name="form1" method="post" action="" style="margin:0px;">
                <strong>Store :- 
                <input type="text" name="store" value="" />
                 &nbsp; &nbsp; Date Range</strong> :- From:
                    <input type="text" name="date_from" value="{$DATE_FROM}" size="10" onfocus="popUpCalendar(this, this, 'yyyy-mm-dd', 0, 0)" readonly="readonly" />
                 To:
                 <input type="text" name="date_to" size="10" value="{$DATE_TO}" onfocus="popUpCalendar(this, this, 'yyyy-mm-dd', 0, 0)" readonly="readonly" />
                  <input type="submit" name="Submit" value="Submit" class="naBtn" />

<select name="order_status" onChange="document.form1.submit();">
  <option value="">-- Orders--</option>
	{html_options values=$ORDER_STATUS.id output=$ORDER_STATUS.name selected = $CURRENT_STATUS}
</select>
              </form>
              </td>
              <td align="right"><strong>Results per page</strong>
                {$ORDER_LIMIT}</td>
            </tr>
          </table>
          </td>
        </tr> 
      </table></td> 
  </tr>
  <form name="form2" method="post"> 
  <input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
  <tr> 
  
    <td>
	
	
	
	
	
	
{if $LISTING_TYPE eq 'N'}
	<table border=0 width=100% cellpadding="5" cellspacing="0"> 
	  {if count($ORDER_LIST) > 0}
	  <tr>
	    <td colspan="2" align="left"><a href="#" onClick="javascript:document.form2.action='{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}{/makeLink}';document.form2.submit();">Archive</a></td>
		<td ><a href="javascript:void(0);" onclick="popupWindow('{makeLink mod=order pg=view_ipn_msg}{/makeLink}')">IPN Message</a></td> 
	  </tr> 
        <tr>
		<td width="1%" nowrap class="naGridTitle" align="left"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.form2,'archieve_id[]')"></td> 
          <td width="10%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="id" display="Order #"}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}{/makeLink}</td> 
          <td width="18%" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="billing_first_name" display="Customer"}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}{/makeLink}</td>
		  <td width="18%" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="date_ordered" display="Date Ordered"}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}{/makeLink}</td>
          <td width="11%" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="order_status_name" display="Order Status"}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}{/makeLink}</td>
		  
		  <td width="11%" nowrap class="naGridTitle">Payment Status</td>
		  
          <td width="12%" align="right" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="paid_price" display="Total Price"}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}{/makeLink}</td>
        </tr>
      
			{foreach from=$ORDER_LIST item=row}
				<tr class="{cycle values="naGrid1,naGrid2"}">
				
				  <td valign="middle" align="left"><input type="checkbox" name="archieve_id[]" value="{$row->id}"></td> 
				  <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=details&id={$row->id}{/makeLink}">{$row->order_number}</a></td> 
				  <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=details&id={$row->id}{/makeLink}">{$row->billing_first_name} {$row->billing_last_name}</a></td>
				  <td valign="middle" height="24" align="left">{$row->date_ordered_f}</td>
				  <td valign="middle" height="24" align="left">{$row->order_status_name}</td>
				  <td valign="middle" height="24" align="left">{$row->payment_status}</td>
				  <td valign="middle" height="24" align="right">{$row->paid_price|number_format:"2"}</td>
				</tr> 
			{/foreach}
        <tr> 
          <td colspan="6" class="msg" align="center" height="30">{$ORDER_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="6" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table>
{else $LISTING_TYPE eq 'Y'}


	<table border=0 width=100% cellpadding="5" cellspacing="0"> 
	  {if count($ORDER_LIST) > 0}
	  <tr>
	     <td ><a href="#" onClick="javascript:document.form2.action='{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}';document.form2.submit();">Archive</a></td>
		<td ><a href="#" onClick="javascript:document.form2.action='{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=delete&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}';document.form2.submit();">Delete</a></td> 

	  </tr> 

        <tr>
			<td width="4%" nowrap class="naGridTitle" align="left"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.form2,'archieve_id[]')"></td> 
			
			<td width="7%" nowrap class="naGridTitle"  align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="id" display="Order #"}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink} </td> 
			<td width="16%" nowrap class="naGridTitle" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="o.billing_first_name" display="Customer"}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink} </td>
			<td width="13%" nowrap class="naGridTitle" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="o.date_ordered" display="Date Ordered"}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink} </td>
			<td width="10%" align="left" nowrap class="naGridTitle">Items<br/>Ordered</td>
			<td width="10%" align="left" nowrap class="naGridTitle">Items On<br/>Back Order</td>
			<td width="10%" align="left" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T4.date_expected" display="Back Order<br/>Expected"}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink} </td>
			<td width="10%" align="left" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T4.rush_order" display="Rush Order"}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink} </td>
            <td width="10%" nowrap class="naGridTitle" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T4.ship_date" display="Ship Date"}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink} </td>
			<td width="10%" align="left" nowrap class="naGridTitle">Bill<br/>Shipping</td>
						
        </tr>
      
			{foreach from=$ORDER_LIST item=row}
				<tr class="{cycle values="naGrid1,naGrid2"}">
				
				  <td valign="middle" align="left"><input type="checkbox" name="archieve_id[]" value="{$row->id}"></td> 
				  <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=details&id={$row->id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}">{$row->order_number}</a></td> 
				  <td valign="middle" height="24" align="left">{$row->billing_first_name} {$row->billing_last_name}</td>
				  <td valign="middle" height="24" align="left">{$row->date_ordered_f}</td>
				   <td valign="middle" height="24" align="left">{$row->M_item_ordered}</td>
				   
								  <td valign="middle" height="24" align="left"><input type="checkbox" name="back_order1[]" {if $row->M_back_order eq 'Y'}checked{/if} ><input type="checkbox" name="back_order2[]" {if $row->M_back_order1 eq 'Y'}checked{/if}></td>
				 
				  <td valign="middle" height="24" align="left">{$row->M_date_expected}</td>
				   <td valign="middle" height="24" align="left">{$row->M_rush_order}</td>
				     <td valign="middle" height="24" align="left">{$row->M_ship_date}</td>
					 
				  <td valign="middle" height="24" align="left"><input type="checkbox" name="bill_shipping1[]" {if $row->M_billing_Shipping eq 'Y'}checked{/if}><input type="checkbox" name="bill_shipping2[]" {if $row->M_billed_shipping eq 'Y'}checked{/if}></td>
				   
				</tr> 
			{/foreach}
        <tr> 
          <td colspan="10" class="msg" align="center" height="30">{$ORDER_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="10" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table>
{/if}  
	  
	  </td> 
  </tr> 
  </form>
</table>