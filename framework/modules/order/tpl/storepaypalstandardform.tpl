{if $PAYMENT_RECEIVER eq 'store'}
<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/scripts/validator.js"></SCRIPT>
{literal}
<script language="javascript">
function chkform()
{
if(document.getElementById('account_mailaddress').value!=document.getElementById('account_mailaddressconfirm').value)
{
alert("Email address does not match");return false;
}else
{

document.getElementById('test_mode_msg_new').value=document.getElementById('test_mode_msg').value;

 document.frm.submit();return true;
}
}
function changeState(flag)
{
	if(flag == true)
	document.getElementById('test_mode_msg').disabled='';
	else
	document.getElementById('test_mode_msg').disabled='disabled';
	
}
</script>
{/literal}
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table width="100%"  border="0">
  <tr>
    <td valign="top">
<table width=80% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
<form action="" method="post" enctype="multipart/form-data" name="frm"  onSubmit="javascript:return chkform();">
  	<input type="hidden" name="act" value="{$smarty.request.act}">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
	<input type="hidden" name="mId" value="{$smarty.request.mId}">
	<input type="hidden" name="mod" value="{$smarty.request.mod}" />
	<input type="hidden" name="pg" value="{$smarty.request.pg}" />
    <tr align="left">
      <td colspan=3 valign=top><table width="400%" align="center">
        <tr>
          <td width="23%" nowrap class="naH1">PayPal Account</td>
          <td width="77%" align="right" nowrap class="titleLink">&nbsp;<!--<a href="{makeLink mod=order pg=order}act=shippingselmethodslist{/makeLink}&store_id={$smarty.request.store_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}">{$smarty.request.sId} List</a>--></td>
        </tr>
      </table></td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3>
		<div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>      </td>
    </tr>
    {/if}
	<tr valign="middle" class=naGrid2>
        <td colspan=3 class="naGridTitle" height="25" nowrap="nowrap"><strong>PayPal Account Details<!--Paypal Standard Account Details  Form --></strong></td>
       </tr>	
	<tr class="naGrid2" height="50px"><td  colspan="3">A PayPal Account is required to process orders and receive payments from your webstore customers. 
If you do not already have a PayPal account, please visit <a href="http://www.paypal.com" target="_blank"> www.paypal.com</a>
 to set up your account.</td></tr>
 <tr class="naGrid1" height="40px"><td  colspan="3"><b>Important Note:</b> Your PayPal account must be set up as a Business Account in order to receive payments.</td></tr>
<tr class="naGrid2"><td  colspan="3"></td></tr>
	 {if $smarty.request.mod neq 'store'}
    <tr valign="middle" class="naGrid1">
      <td height="40"  align="right" valign="bottom">
Store Name:</td>
      <td height="40"  align="center" valign="bottom">&nbsp;</td>
      <td height="40"  align="left" valign="bottom">
		<select name=store_id onchange="window.location.href='{makeLink mod=$MOD pg=$PG}act=paypalformforstore&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}&store_id='+this.value">
			{html_options values=$STORES.id output=$STORES.heading selected=`$SELECTED_STORE_ID`}
		</select>
	  </td>
    </tr>
	{/if}
    <tr valign="middle" class="naGrid2" height="40px">
      <td width="308"   align="right"><span class="fieldname">PayPal Account
E-mail Address:</span></td>
      <td width="3"  align="center">&nbsp;</td>
      <td width="442"  align="left">
	  <span class="formfield">
      <input name="account_mailaddress" type="text" class="Form2" id="account_mailaddress" value="{$FORM_VALUES.account_mailaddress}" size="30" />
      </span></td>
    </tr>
	
	 <tr valign="middle" class="naGrid1" height="40px">
      <td width="308"   align="right"><span class="fieldname">Confirm
PayPal Account E-mail Address:</span></td>
      <td width="3"  align="center">&nbsp;</td>
      <td width="442" align="left"><span class="formfield">
        <input name="account_mailaddressconfirm" type="text" class="Form2" id="account_mailaddressconfirm" value="{$FORM_VALUES.account_mailaddress}" size="30" />
      </span></td>
    </tr>
	
	<!-- <tr valign="middle" class=naGrid2>
      <td width="308" height="40"  align="right"><span class="fieldname">Automated Recurring Billing</span></td>
      <td width="3" height="40" align="center">:</td>
      <td width="442" height="40" align="left"><span class="formfield">
      <input type="checkbox" name="rec_billing" id="rec_billing" {if $FORM_VALUES.rec_billing eq 'Y'} checked {/if} value="Y">
      </span></td>
    </tr>-->	
	
	<!-- <tr valign="middle" class="naGrid2">
      <td width="308"   align="right"><span class="fieldname">Currency:</span></td>
      <td width="3"  align="center">&nbsp;</td>
      <td width="442"  align="left"><span class="formfield">
     <select name="currency_id">
     <option value="currency_id">-Select-</option>   
    {foreach from=$CURRENCYLIST item=crow}
	 <option value="{$crow.cid}" {if $crow.cid eq $FORM_VALUES.currency_id} selected="selected" {/if}>{$crow.symbol}&nbsp;-&nbsp;{$crow.currency_name}</option>
	{/foreach}
   	</select>
      </span></td>
    </tr>
	
    <tr valign="middle" class="naGrid1">
      <td width="308"   align="right"><span class="fieldname">Check this box for test mode:</span></td>
      <td width="3"  align="center">&nbsp;</td>
      <td width="442"  align="left"><span class="formfield">
      <input type="checkbox" name="test_mode" id="test_mode" {if $FORM_VALUES.test_mode eq 'Y'} checked {/if} value="Y" onclick="changeState(this.checked)">
      </span></td>
    </tr>
	
	
		
	 <tr valign="top" class="naGrid2">
      <td width="308"   align="right" valign="top"><span class="fieldname">Test Mode Message:</span></td>
      <td width="3"  align="center" valign="top">&nbsp;</td>
      <td width="442" align="left" valign="top"><span class="formfield">
     <textarea name="test_mode_msg" id="test_mode_msg" rows="8" cols="40" {if $FORM_VALUES.test_mode neq 'Y'} disabled="disabled" {/if}  >{if $FORM_VALUES.test_mode_msg eq '' } This website is temporarily closed. Please contact the store owner for further assistance {else}{$FORM_VALUES.test_mode_msg}{/if}</textarea> <input type="hidden" name="test_mode_msg_new" id="test_mode_msg_new" value="{$FORM_VALUES.test_mode_msg}" /> </span></td>
    </tr>	 -->
	
   <tr class="naGrid1">
     <td colspan="3" valign=top>&nbsp;	 </td>
     </tr> 
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center>	  
	   {if $smarty.request.mod eq 'store'}
	   <input type="hidden" name="store_id" value="{$SELECTED_STORE_ID}">
	   {/if}
	       <input type=submit name="Submit" value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div>	 </td> 
    </tr> 
	<tr><td colspan=3 valign=center>&nbsp;</td></tr> 
  </form> 
</table>
</td>
  </tr>
</table>
{else}
	<br /><br /><strong>Payment By Adminstrator</strong>
{/if}