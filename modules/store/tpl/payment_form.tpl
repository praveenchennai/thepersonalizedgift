{literal}
<script language="javascript">
function loadDiv(theSelect) {	
	var headerChoice= theSelect;		
		switch(headerChoice) {
		case "R": {
			document.getElementById('paypalpro').style.display="inline"; 
			document.getElementById('paypal').style.display="none";
			document.getElementById('Authorize').style.display="none";
			document.getElementById('linkpoint').style.display="none";									
			break;
	   }
	   case "P": {
			document.getElementById('paypal').style.display="inline"; 
			document.getElementById('paypalpro').style.display="none";
			document.getElementById('Authorize').style.display="none";	
			document.getElementById('linkpoint').style.display="none";		
			break;
	   }
		case "A": {
			document.getElementById('Authorize').style.display="inline"; 
			document.getElementById('paypalpro').style.display="none";
			document.getElementById('paypal').style.display="none";
			document.getElementById('linkpoint').style.display="none";			
			break;
	   }
	   case "L": {
	   		document.getElementById('linkpoint').style.display="inline";
			document.getElementById('Authorize').style.display="none"; 
			document.getElementById('paypalpro').style.display="none";
			document.getElementById('paypal').style.display="none";			
			break;
	   }
	}
}
</script>
{/literal}
<table width="80%"  border="0">	
		<tr>
			 <td>{messageBox}</td>
		</tr>
 <tr>
    <td valign="top"><table width=100% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
  <form action="" method="post" enctype="multipart/form-data" name="frm" onSubmit="return chk(this);">
    <tr align="left">
      <td colspan=4 valign=top><table width="100%" align="center">
        <tr>
          <td width="46%" nowrap class="naH1">Providers Details </td>
          <td width="54%" align="right" nowrap class="titleLink">&nbsp;</td>
        </tr>
      </table>
	 </td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=4>
		<div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>
      </td>
    </tr>
    {/if}
    <tr class=naGrid2>
      <td  align="right" valign=top> Provider Name </td>
      <td width="3" valign=top>:</td>
      <td width="262" colspan="2" align="left">
	  <select name="payment_provider" onChange="loadDiv(this.value)">
		<option value="P" {if $PAYMENT.payment_provider=='P'} selected {/if}>Paypal</option>
		<option value="R" {if $PAYMENT.payment_provider=='R'} selected {/if}>Paypal Pro</option>
		<option value="A" {if $PAYMENT.payment_provider=='A'} selected {/if}>Authorize.net</option>	 
		<option value="L" {if $PAYMENT.payment_provider=='L'} selected {/if}>Link Point</option>
      </select>
	  </td>
    </tr>
    <tr class='naGrid2'>
      <td colspan="4" align="right" valign=top>
	 {if $PAYMENT.payment_provider=='R'}
	 	 <div id="paypalpro" style="display:inline">
	 {else}	 
		 <div id="paypalpro" style="display:none">
	 {/if}	 
	  <table width="100%"  border="0" cellspacing="1" cellpadding="4">
        <tr>
          <td  align="right">API Username </td>
          <td width="4">:</td>
          <td width="254">
		  <input name="pay_userid" type="text" id="pay_userid" value="{$PAYMENT.pay_userid}">
		  </td>
        </tr>
        <tr>
          <td align="right">API Password</td>
          <td>:</td>
          <td><input name="pay_password" type="password" id="pay_password" value="{$PAYMENT.pay_password}"></td>
        </tr>
        <tr>
          <td  align="right">API Signature </td>
          <td>:</td>
          <td><input type="text" name="pay_api_signature" value="{$PAYMENT.pay_api_signature}"></td>
        </tr>
        <tr>
          <td width="219"  align="right">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>	  
	 </div>
	  {if $PAYMENT.payment_provider=='P'}
	 	 <div id="paypal" style="display:inline">
	 {else}	 
		 <div id="paypal" style="display:none">
	 {/if}	
		  <table width="100%"  border="0" cellspacing="1" cellpadding="4">
			
			  <td align="right">E mail </td>
			  <td width="4" align="center">:</td>
			  <td ><input name="pay_email" type="text" id="pay_email" value="{$PAYMENT.pay_email}"></td>
			</tr>
			<tr>
			  <td width="219" height="2" align="right">&nbsp;</td>
			  <td height="2">&nbsp;</td>
			  <td width="254" height="2" >&nbsp;</td>
			  </tr>
			<tr>
		  </table>	  
		</div>		
	 {if $PAYMENT.payment_provider=='A'}
	 	 <div id="Authorize" style="display:inline">
	 {else}	 
		 <div id="Authorize" style="display:none">
	 {/if}	
		  <table width="100%"  border="0" cellspacing="1" cellpadding="4">
			
			  <td align="right">Login ID </td>
			  <td width="4" align="center">:</td>
			  <td><input name="pay_useridauth" type="text" id="pay_useridauth" value="{$PAYMENT.pay_userid}"></td>
			</tr>
			<tr>
			  <td height="2" align="right">Transaction Key</td>
			   <td width="4" align="center">:</td>
			  <td width="254" height="2" ><input name="pay_transkey" type="text" id="pay_transkey" value="{$PAYMENT.pay_transkey}"></td>
			  </tr>
			<tr>
			  <td width="219" height="2" align="right">&nbsp;</td>
			  <td height="2">&nbsp;</td>
			  <td height="2" >&nbsp;</td>
			  </tr>
			<tr>
		  </table>
		 </div>	
	 {if $PAYMENT.payment_provider=='L'}
	 	 <div id="linkpoint" style="display:inline">
	 {else}	 
		 <div id="linkpoint" style="display:none">
	 {/if}	
		  <table width="100%"  border="0" cellspacing="1" cellpadding="4">			
			    <td align="right">Key File </td>
			      <td width="4" align="center">:</td>
			  <td><input name="pay_keyfile" type="text" id="pay_keyfile" value="{$PAYMENT.pay_keyfile}"></td>
			</tr>
			<tr>
			  <td height="2" align="right">Config File </td>
			   <td width="4" align="center">:</td>
			  <td width="254" height="2" ><input name="pay_configfile" type="text" id="pay_configfile" value="{$PAYMENT.pay_configfile}"></td>
			  </tr>
			<tr>
			  <td width="219" height="2" align="right">&nbsp;</td>
			  <td height="2">&nbsp;</td>
			  <td height="2" >&nbsp;</td>
			  </tr>
			<tr>
		  </table>	
		 </div>	
		 </td>
      </tr>
   <tr class='naGrid2'>
     <td width="230" align="right" valign=top>&nbsp;</td>
     <td valign=top>&nbsp;</td>
     <td colspan="2" align="left">&nbsp;</td>
   </tr> 
    <tr class="naGridTitle"> 
      <td colspan=4 valign=center><div align=center>	  
	       <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div>
	 </td> 
    </tr> 
  </form> 
</table>
</td>
  </tr>
</table>