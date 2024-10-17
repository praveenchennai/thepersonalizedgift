<table width="90%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table border="0" cellspacing="0" cellpadding="4" width="100%" align="center"> 
        <tr>
		  <td height="30" nowrap class="naH1">Deposit Details</td>
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
			<td width="30%" nowrap class="naGridTitle" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T2.username" display="Depositor"}act=deposit_list{/makeLink}</td> 
			<td width="30%" nowrap class="naGridTitle" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T3.username" display="Receiver"}act=deposit_list{/makeLink}</td>
			<td width="10%" nowrap class="naGridTitle" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T1.deposit_amount" display="Amount"}act=deposit_list{/makeLink}</td>
			<td width="15%" nowrap class="naGridTitle" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T1.deposite_date" display="Date"}act=deposit_list{/makeLink}</td>
			<td width="15%" align="left" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T1.deposite_for" display="Deposit For"}act=deposit_list{/makeLink}</td>
        </tr>
      
			{foreach from=$DEPOSITS item=Deposit}
				<tr class="{cycle values="naGrid1,naGrid2"}" height="25" >
					<td width="30%" nowrap align="left">{$Deposit.depositor}</td> 
					<td width="30%" nowrap  align="left">{$Deposit.receiver}</td>
					<td width="10%" nowrap  align="left">${$Deposit.deposit_amount|string_format:"%.2f"}</td>
					<td width="15%" nowrap  align="left">{$Deposit.deposite_date}</td>
					<td width="15%" align="left" nowrap >{$Deposit.deposite_for}</td>
				</tr> 
			{/foreach}
        <tr> 
          <td colspan="5" class="msg" align="center" height="30">{$NUM_PAD}</td> 
        </tr>
{if count($DEPOSITS) eq 0}
	<tr class="naGrid2"> 
		<td colspan="5" class="naError" align="center" height="30">No Records</td> 
	</tr>
{/if}
 </table>
 
 
	  </td> 
  </tr> 
</table>