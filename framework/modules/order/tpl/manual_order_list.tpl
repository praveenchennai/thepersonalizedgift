<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></SCRIPT>
<table width="90%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table border="0" cellspacing="0" cellpadding="4" width="100%" align="center"> 
        <tr>
		  <td height="30" nowrap class="naH1"><!--Orders-->{$SUBNAME}</td> 
          <td nowrap align="right" class="titleLink" width="100%">&nbsp;</td> 
        </tr>
        <tr>
          <td height="30" nowrap >&nbsp;</td>
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=order pg=order}act=manual_order_form&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}">Add New Manual Order</a>&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" align="right" class="naGrid1"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
			   
            <!--  <td align="center"><form id="form1" name="form1" method="post" action="" style="margin:0px;">
                <strong>Store :- 
                <input type="text" name="store" value="" />
                 &nbsp; &nbsp; Date Range</strong> :- From:
                    <input type="text" name="date_from" value="{$DATE_FROM}" size="10" onfocus="popUpCalendar(this, this, 'yyyy-mm-dd', 0, 0)" readonly="readonly" />
                 To:
                 <input type="text" name="date_to" size="10" value="{$DATE_TO}" onfocus="popUpCalendar(this, this, 'yyyy-mm-dd', 0, 0)" readonly="readonly" />
                  <input type="submit" name="Submit" value="Submit" />

<select name="order_status" onChange="document.form1.submit();">
  <option value="">-- Orders--</option>
  
					{html_options values=$ORDER_STATUS.id output=$ORDER_STATUS.name selected = $CURRENT_STATUS}
                   
</select>
              </form>              </td>-->
              <td align="right"><strong>Results per page</strong>
                {$ORDER_LIMIT}</td>
            </tr>
          </table>          </td>
        </tr> 
      </table></td> 
  </tr>
  <form name="form2" method="post"> 
	<input type="hidden" name="order_status" value="{$smarty.request.order_status}" />
	<input type="hidden" name="fId" value="{$smarty.request.fId}" />
	<input type="hidden" name="sId" value="{$smarty.request.sId}" />
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
        <tr>
          <td width="7%" nowrap class="naGridTitle" align="center"><a href="#" onClick="javascript:document.form2.action='{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=archive_orders&date_from={$DATE_FROM}&date_to={$DATE_TO}{/makeLink}';document.form2.submit();">Archive</a><br />
                <input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.form2,'order_ids[]')">           </td>
          <td width="9%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="id" display="Order #"}act=manual_order_list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}{/makeLink}</td> 
          <td width="10%" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="billing_first_name" display="Customer"}act=manual_order_list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}{/makeLink}</td>
		      <td width="12%" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="date_ordered" display="Date <br>Ordered"}act=manual_order_list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}{/makeLink}</td>
  <td width="10%" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="ship_date" display="Ship <br>Date"}act=manual_order_list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}{/makeLink}</td>
 <td width="10%" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="rush_order" display="Rush <br>Order"}act=manual_order_list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}{/makeLink}</td>
<td width="10%" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="item_ordered" display="Items<br> Ordered"}act=manual_order_list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}{/makeLink}</td>

<td width="10%" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy=" back_order" display="Items On <br>Back Order"}act=manual_order_list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}{/makeLink}</td>
<td width="10%" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy=" date_expected" display="Back Order<br> Expected"}act=manual_order_list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}{/makeLink}</td>
<td width="10%" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy=" billing_Shipping" display="Bill <br>Shipping"}act=manual_order_list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}{/makeLink}</td>


          </tr>
        {if count($ORDER_LIST) > 0}
			{foreach from=$ORDER_LIST item=row}
				<tr class="{cycle values="naGrid1,naGrid2"}">
				  <td valign="middle" align="center"><input type="checkbox" name="order_ids[]" value="{$row->id}"></td> 
				  <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=manual_order_form&id={$row->id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}">{$row->order_number}</a></td> 
				  <td valign="middle" height="24" align="left">{$row->customer_name}</td>
				    <td valign="middle" height="24" align="left">{$row->date_ordered}</td>
					   <td valign="middle" height="24" align="left">{$row->ship_date}</td>
					     <td valign="middle" height="24" align="left">{$row->rush_order}</td>
						
							 <td valign="middle" height="24" align="left"><input type="checkbox" name="archieve_id[]" {if $row->item_ordered eq 'Y'}checked{/if} ></td>
							 <td valign="middle" height="24" align="left"><input type="checkbox" name="archieve_id[]" {if $row->back_order eq 'Y'}checked{/if} ><input type="checkbox" name="archieve_id[]" {if $row->back_order1 eq 'Y'}checked{/if}></td>
							 <td valign="middle" height="24" align="left">{$row->date_expected}</td>
							 <td valign="middle" height="24" align="left"><input type="checkbox" name="archieve_id[]" {if $row->billing_Shipping eq 'Y'}checked{/if}><input type="checkbox" name="archieve_id[]" {if $row->billed_shipping eq 'Y'}checked{/if}></td>
						   
		  </tr> 
			{/foreach}
        <tr> 
          <td colspan="5" class="msg" align="center" height="30">{$ORDER_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="5" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
  </form>
</table>