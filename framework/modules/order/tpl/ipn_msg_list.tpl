

<form name="frmStore" method="post" action="">
<table align="center" width="95%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="1" > 
        {if count($IPN_MSG_LIST) > 0}
        <tr>
		 <td width="140" nowrap class="naGridTitle" height="24" align="left">Item name</td> 
		 <td width="80"  nowrap class="naGridTitle" height="24" align="left">Time</td> 
          <td  width="70"   nowrap class="naGridTitle">Payment status</td>
		   <td  nowrap class="naGridTitle" height="24" align="center">Transaction ID</td> 
			  <td width="170" nowrap class="naGridTitle" height="24" align="left">Description</td> 
		    <td  nowrap class="naGridTitle" height="24" align="center">Receiver email</td> 
			 <td  nowrap class="naGridTitle" height="24" align="center">Payer email</td>
            <td  width="50"  nowrap class="naGridTitle" align="center">Payment amount</td>
		     <td width="50"    nowrap class="naGridTitle" align="center">Payment currency</td>
			  <td   width="50"  nowrap class="naGridTitle" align="center">Payment type</td>
			  
				 <td    nowrap class="naGridTitle" align="center">Payer Name</td>
        </tr>
		
        {foreach from=$IPN_MSG_LIST item=row}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
          <td height="24" align="left" valign="middle">{$row->item_name}</td> 
		  <td height="24" align="left" valign="middle">{$row->log_time|date_format:"%b %e, %Y %r"}</td> 
		  <td height="24" align="left" valign="middle">{$row->payment_status}</td> 
          <td height="24" align="center" valign="middle">{$row->txn_id}</td> 
		  <td height="24" align="left" valign="middle">{$row->description}</td> 
		  <td height="24" align="center" valign="middle">{$row->receiver_email}</td> 
		  <td height="24" align="center" valign="middle">{$row->payer_email}</td>
		  <td height="24" align="center" valign="middle">{$row->payment_amount}</td> 
		  <td   align="center" valign="middle">{$row->payment_currency}</td> 
		  <td  align="left" valign="middle">{$row->payment_type}</td> 
		  <td  align="left" valign="middle">{$row->payer_first_name}&nbsp;&nbsp;{$row->payer_last_name}</td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="9" class="msg" align="center" height="30">{$STORE_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="9" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>
</form>