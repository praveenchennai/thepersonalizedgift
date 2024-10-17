<table width="80%"  border="0" align="center">	
		<tr>
			 <td>{messageBox}</td>
		</tr>
 <tr>
    <td valign="top"><table width=100% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
 
    <tr align="left">
      <td colspan=4 valign=top><table width="100%" align="center">
        <tr>
          <td width="35%" nowrap class="naH1">Providers Details</td>
          <td width="65%" align="right" nowrap class="titleLink">
		  {if $PAYMENT_DETAILS.id}
		 	 <a href="{makeLink mod=store pg=store_payment}act=form&id={$PAYMENT_DETAILS.id}{/makeLink}">Manage Details</a> 
		  {else}
		  	 <a href="{makeLink mod=store pg=store_payment}act=form{/makeLink}">Add Provider Details</a> 
		  {/if}
		  &nbsp;
		  </td>
        </tr>
      </table>
	 </td>
    </tr>	
	{if $PAYMENT_COUNT!=0}
		<tr class=naGrid2>
		  <td width="268"  align="right" valign=top>&nbsp;</td>
		  <td valign=top>&nbsp;</td>
		  <td colspan="2" align="left">&nbsp;</td>
	    </tr>
		<tr class=naGrid2>
		  <td  height="33"  align="right" valign=top>Name</td>
		  <td width="9" valign=top>:</td>
		  <td width="325" colspan="2" align="left">
			{$PAYMENT_DETAILS.provider_name}
		  </td>
		</tr>		
	    <tr class=naGrid2>
	      <td colspan="4"  align="right" valign=top>
		  {if $PAYMENT_DETAILS.payment_provider=='R'}
		  <table width="100%"  border="0" cellspacing="1" cellpadding="5">
            <tr>
              <td width="44%" align="right">User ID </td>
              <td width="3%">:</td>
              <td width="53%">{$PAYMENT_DETAILS.pay_userid}</td>
            </tr>
            <tr>
              <td align="right">API Signature </td>
              <td>:</td>
              <td>{$PAYMENT_DETAILS.pay_api_signature}</td>
			</tr>
          </table>
		  	{/if}
			{if $PAYMENT_DETAILS.payment_provider=='P'}
			<table width="100%"  border="0" cellspacing="1" cellpadding="5">
			  <tr>
				<td width="44%" align="right">Email</td>
				<td width="3%">:</td>
				<td width="53%">{$PAYMENT_DETAILS.pay_email}</td>
			  </tr>
			</table>
			{/if}
			{if $PAYMENT_DETAILS.payment_provider=='A'}
			<table width="100%"  border="0" cellspacing="1" cellpadding="5">
			  <tr>
				<td width="44%" align="right">Login ID </td>
				<td width="3%">:</td>
				<td width="53%">{$PAYMENT_DETAILS.pay_userid}</td>
			  </tr>
			  <tr>
			    <td align="right">Transaction Key </td>
			    <td>:</td>
			    <td>{$PAYMENT_DETAILS.pay_transkey}</td>
			   </tr>
			</table>
			{/if}
			{if $PAYMENT_DETAILS.payment_provider=='L'}
			<table width="100%"  border="0" cellspacing="1" cellpadding="5">
			  <tr>
				<td width="44%" align="right">Key File </td>
				<td width="3%">:</td>
				<td width="53%">{$PAYMENT_DETAILS.pay_keyfile}</td>
			  </tr>
			  <tr>
			    <td align="right">Cinfig File</td>
			    <td>:</td>
			    <td>{$PAYMENT_DETAILS.pay_configfile}</td>
			   </tr>
			</table>
			{/if}
		</td>
        </tr>	
	{else}	
	  <tr align="center">
		<td colspan="3">No Records found</td>
		</tr>
	{/if}	
	<tr class="naGridTitle"> 
		  <td height="33" colspan=4 valign=center></td>  	
	</tr> 
</table>
</td>
  </tr>
</table>
