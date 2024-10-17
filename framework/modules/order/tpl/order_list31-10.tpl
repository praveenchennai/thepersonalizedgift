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
          <td colspan="2" align="right" class="naGrid1"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
			   
              <td align="center"><form id="form1" name="form1" method="post" action="" style="margin:0px;">
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
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
        <tr>
          <td width="7%" nowrap class="naGridTitle" align="left"><a href="#" onClick="javascript:document.form2.action='{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}{/makeLink}';document.form2.submit();">Archieve</a></td>
          <td width="9%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="id" display="Order #"}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}{/makeLink}</td> 
          <td width="10%" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="billing_first_name" display="Customer"}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}{/makeLink}</td>
		      <td width="12%" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="date_ordered" display="Date Ordered"}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}{/makeLink}</td>

          <td width="15%" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="order_status_name" display="Order Status"}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}{/makeLink}</td>
         
          <td width="12%" align="right" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="paid_price" display="Total Price"}act=list&date_from={$DATE_FROM}&date_to={$DATE_TO}&order_status={$CURRENT_STATUS}{/makeLink}</td>
        </tr>
        {if count($ORDER_LIST) > 0}
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