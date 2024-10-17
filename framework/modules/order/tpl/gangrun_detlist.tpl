<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></SCRIPT>
<script language="javascript">
{literal}
function chkstatus(){
if(document.form2.order_status.value==''){
	alert("Select any status");
	return false;
}
return true;
}
{/literal}
</script>
<table width="90%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <form name="form2" method="post"> 
  <tr> 
    <td><table border="0" cellspacing="0" cellpadding="4" width="100%" align="center"> 
        <tr>
		  <td height="30" nowrap class="naH1"><!--Orders-->GANG RUN ORDERS</td> 
          <td nowrap align="right" class="titleLink" width="100%">&nbsp;</td> 
        </tr>
        <tr>
          <td colspan="2" align="right" class="naGrid1"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
			              <td align="center">
               
                 &nbsp; &nbsp; Date Range</strong> :- From: {$DATE_FROM}
                    
                 To: {$DATE_TO}</td><td>
               <input type="hidden" name="gang_run_name" value="GANGRUN_{$DATE_TO}" />
                  <input type="button"  value="Download Art Files" class="naBtn" onClick="javascript:document.form2.action='{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=downloadAllFiles&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}';document.form2.submit();" style="width:130px;" /></td><td><select name="order_status" style="width:125px;">
					<option value="">-- Select a Status --</option>
					{html_options values=$ORDER_UPDATE_STATUS.drop_down_id output=$ORDER_UPDATE_STATUS.value selected=`$ORDER_DETAILS->order_status`}
					<!-- {html_options values=$ORDER_UPDATE_STATUS.id output=$ORDER_UPDATE_STATUS.name } -->
			   </select>		


              </td>
			  <td><input type="submit" value="Update Status" class="naBtn" onclick="return chkstatus();"  /></td>
              <td align="right"><strong>Results per page</strong>
                {$ORDER_LIMIT}</td>
            </tr>
          </table>
          </td>
        </tr> 
      </table></td> 
  </tr>
  <input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
  <tr> 
  
    <td>
	
	
	
	
	
	
{if $LISTING_TYPE eq 'N'}
	<table border=0 width=100% cellpadding="5" cellspacing="0"> 
	  {if count($ORDER_LIST) > 0}
	
        <tr>
		<td width="4%" nowrap class="naGridTitle" align="left"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.form2,'archieve_id[]')"></td> 
          <td width="9%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="id" display="Order #"}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}{/makeLink}</td> 
          <td width="10%" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="billing_first_name" display="Customer"}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}{/makeLink}</td>
		  <td width="12%" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="date_ordered" display="Date Ordered"}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}{/makeLink}</td>
          <td width="15%" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="order_status_name" display="Order Status"}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}{/makeLink}</td>
          <td width="12%" align="right" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="paid_price" display="Total Price"}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}{/makeLink}</td>
        </tr>
      
			{foreach from=$ORDER_LIST item=row}
				<tr class="{cycle values="naGrid1,naGrid2"}">
				
				  <td valign="middle" align="left"><input type="checkbox" name="archieve_id[]" value="{$row->id}"></td> 
				  <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=details&id={$row->id}{/makeLink}">{$row->order_number}</a></td> 
				  <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=details&id={$row->id}{/makeLink}">{$row->billing_first_name} {$row->billing_last_name}</a></td>
				  <td valign="middle" height="24" align="left">{$row->date_ordered_f}</td>
				  <td valign="middle" height="24" align="left">{$row->order_status_name}</td>
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
	     <td >&nbsp;</td>
		<td ><a href="#" onClick="javascript:document.form2.action='{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=delete&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}';document.form2.submit();">Delete</a></td> 

	  </tr> 

        <tr>
			<td width="4%" nowrap class="naGridTitle" align="left"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.form2,'archieve_id[]')"></td> 
			
			<td width="7%" nowrap class="naGridTitle"  align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="id" display="Order #"}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink} </td> 
			<td width="16%" nowrap class="naGridTitle" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="o.billing_first_name" display="Customer"}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink} </td>
			<td width="13%" nowrap class="naGridTitle" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="o.date_ordered" display="Date Ordered"}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink} </td>
		
						
        </tr>
      
			{foreach from=$ORDER_LIST item=row}
				<tr class="{cycle values="naGrid1,naGrid2"}">
				
				  <td valign="middle" align="left"><input type="checkbox" name="archieve_id[]" value="{$row->id}"></td> 
				  <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=details&id={$row->id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}">{$row->order_number}</a></td> 
				  <td valign="middle" height="24" align="left">{$row->billing_first_name} {$row->billing_last_name}</td>
				  <td valign="middle" height="24" align="left">{$row->date_ordered_f}</td>
				 
				   
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