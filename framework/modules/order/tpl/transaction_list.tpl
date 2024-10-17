<table width="90%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table border="0" cellspacing="0" cellpadding="4" width="100%" align="center"> 
        <tr>
		  <td height="30" nowrap class="naH1">Transaction History</td>
          <td nowrap align="right" class="titleLink" width="100%">&nbsp;</td> 
        </tr>
        <tr>
          <td height="30" nowrap>&nbsp;</td>
          <td nowrap align="right" class="titleLink">Results per page</strong>{$LIMIT_LIST}</td>
        </tr>
      </table></td> 
  </tr>
  <tr> 
  
<td>
	
<table border=0 width=100% cellpadding="5" cellspacing="0"> 
        <tr height="30">
			<td width="20%" nowrap class="naGridTitle" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T2.username" display="Sender"}act=transaction_list{/makeLink}</td> 
			<td width="20%" nowrap class="naGridTitle" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T3.username" display="Receiver"}act=transaction_list{/makeLink}</td>
			<td width="10%" nowrap class="naGridTitle" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T1.transaction_date" display="Date"}act=transaction_list{/makeLink}</td>
			<td width="30%" nowrap class="naGridTitle" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T1.trans_description" display="Description"}act=transaction_list{/makeLink}</td>
			<td width="8%" align="left" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T1.trans_amount" display="Amount"}act=transaction_list{/makeLink}</td>
			<td width="12%" align="left" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T1.transaction_id" display="Transaction Id"}act=transaction_list{/makeLink}</td>
        </tr>
      
			{foreach from=$TRANSACTIONS item=Transaction}
				<tr class="{cycle values="naGrid1,naGrid2"}" height="25" >
					<td width="20%" nowrap align="left">{$Transaction.sender}</td> 
					<td width="20%" nowrap  align="left">{$Transaction.receiver}</td>
					<td width="10%" nowrap  align="left">{$Transaction.transaction_date}</td>
					<td width="30%" nowrap  align="left">{$Transaction.trans_description}</td>
					<td width="8%" nowrap  align="left">${$Transaction.trans_amount|string_format:"%.2f"}</td>
					<td width="12%" align="left" nowrap >{$Transaction.transaction_id}</td>
				</tr> 
			{/foreach}
        <tr> 
          <td colspan="6" class="msg" align="center" height="30">{$NUM_PAD}</td> 
        </tr>
{if count($TRANSACTIONS) eq 0}
	<tr class="naGrid2"> 
		<td colspan="6" class="naError" align="center" height="30">No Records</td> 
	</tr>
{/if}
 </table>
 
 
	  </td> 
  </tr> 
</table>