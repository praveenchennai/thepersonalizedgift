<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td>
	<table border="0" cellspacing="0" cellpadding="4" width="100%" align="center"> 
        <tr>
		<td width="25%" nowrap class="naH1">Gift Certificate History</td> 
		<td width="30%">&nbsp;</td>
		<td width="15%">&nbsp;</td>
          <td width="30%" nowrap align="right" class="titleLink" width="100%"><a href="{makeLink mod=extras pg=certificate}act=viewusercertificate{/makeLink}">Certificate List</a></td> 
        </tr>
        <tr>
			<td width="25%" class="naGrid1"><strong>Certificate Number : {$GIFTHISTORY_LIST[0]->certi_number}</strong></td>
			<td width="30%" class="naGrid1"><strong>
				{if $GIFTCERT_TYPE == one}Gift Certificate Type: One Time{/if}
				{if $GIFTCERT_TYPE == fixed}Gift Certificate Type: Fixed{/if}
				{if $GIFTCERT_TYPE == unlimit}Gift Certificate Type: Unlimited{/if}
			</strong></td>
			<td width="15%" class="naGrid1">{if $GIFTCERT_TYPE == fixed}<strong>No of Times: {$NO_TIMES}</strong>{/if}</td>
          <td width="30%" align="right" class="naGrid1"><strong>No: of items per page</strong>{$GIFTHISTORY_LIMIT}</td>
        </tr> 
      </table>
	  </td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
        {if count($GIFTHISTORY_LIST) > 0}
        <tr>
          <td width="25%" nowrap class="naGridTitle" height="24" align="left">Order Number</td> 
         <td width="25%" colspan="2" nowrap class="naGridTitle">{makeLink mod=extras pg=certificate orderBy="a.trans_useamount" display="Amount"}act=viewhistory&id={$smarty.request.id}{/makeLink}</td>
          <td width="25%" nowrap class="naGridTitle">{makeLink mod=extras pg=certificate orderBy="a.trans_usagedate" display="Date"}act=viewhistory&id={$smarty.request.id}{/makeLink}</td>
       	  <td width="25%" nowrap class="naGridTitle"></td>
	    </tr>
        {foreach from=$GIFTHISTORY_LIST item=row name=foo}
		{assign var="num" value="`$smarty.foreach.foo.index`"}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
          <td height="24" align="left" valign="middle">
		  	<!-- <a href="{makeLink mod=order pg=order}act=details&id={$row->order_id}{/makeLink}">{$ORDER_NUM.$num.order_number}</a> -->
			{$ORDER_NUM.$num.order_number}
		  </td>
          <td height="24" colspan="2" align="left" valign="middle">{$row->trans_useamount}</td> 
		  <td height="24" align="left" valign="middle">{$row->trans_usagedate}</td> 
          <td height="24" align="left" valign="middle"></td>
		</tr> 
        {/foreach}
		 <tr class="naGrid2"> 
          <td class="naError" align="right" height="30">Total Amount </td> 
          <td width="17%" height="30" align="left" class="naError">{$SUM}</td>
          {if $GIFTCERT_TYPE == unlimit}
			  <td width="16%" align="right" class="naError">&nbsp;</td>
			  <td class="naError" align="left" height="30" colspan="2">&nbsp;</td>
		  {else}
			  <td width="16%" align="right" class="naError">Balance Amount</td>
			  <td class="naError" align="left" height="30" colspan="2">{$BALANCE}</td>
		  {/if}
	    </tr>
		<tr> 
		  <td colspan="4" class="msg" align="center" height="30">{$GIFTHISTORY_NUMPAD}</td> 
		</tr>			
        {else}
         <tr class="naGrid2"> 
          <td colspan="4" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>