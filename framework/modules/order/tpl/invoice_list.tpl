<table width="90%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table border="0" cellspacing="0" cellpadding="4" width="100%" align="center"> 
        <tr>
		  <td height="30" nowrap class="naH1">Invoice Details</td>
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
			<td width="12%" nowrap class="naGridTitle" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T1.invoice_number" display="Invoice Number"}act=invoice_list{/makeLink}</td> 
			<td width="10%" nowrap class="naGridTitle" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T1.created_time" display="Date"}act=invoice_list{/makeLink}</td>
			<td width="20%" nowrap class="naGridTitle" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T2.username" display="Sender"}act=invoice_list{/makeLink}</td>
			<td width="20%" nowrap class="naGridTitle" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T3.username" display="Receiver"}act=invoice_list{/makeLink}</td>
			<td width="8%" align="left" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T1.invoice_amount" display="Amount"}act=invoice_list{/makeLink}</td>
			<td width="20%" align="left" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T1.invoice_type" display="InvoiceType"}act=invoice_list{/makeLink}</td>
			<td width="10%" align="left" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="T1.invoice_status" display="Status"}act=invoice_list{/makeLink}</td>
        </tr>
      
			{foreach from=$INVOICES item=Invoice}
				<tr class="{cycle values="naGrid1,naGrid2"}" height="25" >
					<td width="12%" nowrap align="left">{$Invoice.invoice_number}</td> 
					<td width="10%" nowrap  align="left">{$Invoice.created_time}</td>
					<td width="20%" nowrap  align="left">{$Invoice.senterusername}</td>
					<td width="20%" nowrap  align="left">{$Invoice.receiverusername}</td>
					<td width="8%" align="left" nowrap >${$Invoice.invoice_amount|string_format:"%.2f"}</td>
					<td width="20%" align="left" nowrap >{$Invoice.invoice_type}</td>
					<td width="10%" align="left" nowrap>{$Invoice.invoice_status}</td>
				</tr> 
			{/foreach}
        <tr> 
          <td colspan="7" class="msg" align="center" height="30">{$NUM_PAD}</td> 
        </tr>
{if count($INVOICES) eq 0}
	<tr class="naGrid2"> 
		<td colspan="7" class="naError" align="center" height="30">No Records</td> 
	</tr>
{/if}
 </table>
 
 
	  </td> 
  </tr> 
</table>