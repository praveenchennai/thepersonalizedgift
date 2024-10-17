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
          <td width="23%" nowrap class="naH1">Webstore Settings</td>
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
	<tr class="naGrid2"><td  colspan="3"><!--Select your webstore's Setting here.Select the currency you want to use your website.If you want the payment gateway to be in testing mode for testing,please do it here.-->Select your webstore's setting here. Select the currency you want to use for your website. If you want the payment gateway to be disabled please check the box which says "Disable Store Checkout."</td></tr>
	<tr valign="middle" class=naGrid2>
      <td colspan=3 class="naGridTitle" height="25" nowrap="nowrap"><strong>Webstore Setting<!--Paypal Standard Account Details  Form --></strong></td>
    </tr>
	{if $smarty.request.mod neq 'store'}
    <tr valign="middle" class="naGrid1">
      <td height="30"  align="right" valign="bottom">
Store Name:</td>
      <td height="30"  align="center" valign="bottom">&nbsp;</td>
      <td height="30"  align="left" valign="bottom">
		<select name=store_id onchange="window.location.href='{makeLink mod=$MOD pg=$PG}act=websitesetting&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}&store_id='+this.value">
			{html_options values=$STORES.id output=$STORES.heading selected=`$SELECTED_STORE_ID`}
		</select>
	  </td>
    </tr>
	{/if}
   <!--  <tr valign="middle" class="naGrid2">
      <td width="308"   align="right"><span class="fieldname">PayPal Account
E-mail Address:</span></td>
      <td width="3"  align="center">&nbsp;</td>
      <td width="442"  align="left">
	  <span class="formfield">
      <input name="account_mailaddress" type="text" class="Form2" id="account_mailaddress" value="{$FORM_VALUES.account_mailaddress}" size="30" />
      </span></td>
    </tr>
	
	 <tr valign="middle" class="naGrid1">
      <td width="308"   align="right"><span class="fieldname">Confirm
PayPal Account E-mail Address:</span></td>
      <td width="3"  align="center">&nbsp;</td>
      <td width="442" align="left"><span class="formfield">
        <input name="account_mailaddressconfirm" type="text" class="Form2" id="account_mailaddressconfirm" value="{$FORM_VALUES.account_mailaddress}" size="30" />
      </span></td>
    </tr> -->
	
	<!-- <tr valign="middle" class=naGrid2>
      <td width="308" height="40"  align="right"><span class="fieldname">Automated Recurring Billing</span></td>
      <td width="3" height="40" align="center">:</td>
      <td width="442" height="40" align="left"><span class="formfield">
      <input type="checkbox" name="rec_billing" id="rec_billing" {if $FORM_VALUES.rec_billing eq 'Y'} checked {/if} value="Y">
      </span></td>
    </tr>-->	
	
	<tr valign="middle" class="naGrid2">
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
      <td width="308"   align="right"><span class="fieldname">Disable Store Checkout:</span></td>
      <td width="3"  align="center">&nbsp;</td>
      <td width="442"  align="left"><span class="formfield">
      <input type="checkbox" name="test_mode" id="test_mode" {if $FORM_VALUES.test_mode eq 'Y'} checked {/if} value="Y" onclick="changeState(this.checked)">
      </span>&nbsp;&nbsp;&nbsp;<img src="{$GLOBAL.tpl_url}/images/icon_small_info.gif" width="17" height="16" align="top" {popup text="Select the Disable Store Checkout checkbox to temporarily prevent checkout for your webstore. You can use this setting to prevent purchases while making changes to your webstore." fgcolor="#eeffaa"} /></td>
    </tr>
	
 <tr valign="top" class="naGrid2">
      <td width="308"   align="right" valign="top"><span class="fieldname">Disable Store Checkout Message:</span></td>
      <td width="3"  align="center" valign="top">&nbsp;</td>
      <td width="442" align="left" valign="top"><span class="formfield">
     <textarea name="test_mode_msg" id="test_mode_msg" rows="8" cols="40" {if $FORM_VALUES.test_mode neq 'Y'} disabled="disabled" {/if}  >{if $FORM_VALUES.test_mode_msg eq '' }This website is temporarily closed. Please contact the store owner for further assistance.{else}{$FORM_VALUES.test_mode_msg}{/if}</textarea> <input type="hidden" name="test_mode_msg_new" id="test_mode_msg_new" value="{$FORM_VALUES.test_mode_msg}" /> </span>&nbsp;&nbsp;&nbsp;<img src="{$GLOBAL.tpl_url}/images/icon_small_info.gif" width="17" height="16" align="top" {popup text="If &ldquo;Disable Store Checkout&rdquo; is selected above, this message will be displayed during checkout instead of the shopping cart payment button." fgcolor="#eeffaa"} /></td>
    </tr>
	{if $SMTP neq 'Y'}		
	<tr valign="middle" class=naGrid2>
      <td colspan=3 class="naGridTitle" height="25" nowrap="nowrap"><strong>SMTP Email Settings<!--Paypal Standard Account Details  Form --></strong></td>
    </tr>
	<tr valign="top" class="naGrid1">
      <td width="308"   align="right" valign="top"><span class="fieldname">Authenticate:</span></td>
      <td width="3"  align="center" valign="top">&nbsp;</td>
      <td width="442" align="left" valign="top"><span class="formfield"><input name="mail_authenticate" value="Y" {if $FORM_VALUES.mail_authenticate eq 'Y' or $FORM_VALUES.mail_authenticate eq ''} checked="checked" {/if} id="mail_authenticate" class="formText" onclick="show(this);" type="radio">Yes&nbsp;<input name="mail_authenticate"  {if $FORM_VALUES.mail_authenticate eq 'N' } checked="checked" {/if} id="mail_authenticate" class="formText" value="N" onclick="donotshow(this);" type="radio">No
       </span></td>
    </tr>
	<tr valign="top" class="naGrid2">
      <td width="308"   align="right" valign="top"><span class="fieldname">Domain:</span></td>
      <td width="3"  align="center" valign="top">&nbsp;</td>
      <td width="442" align="left" valign="top"><span class="formfield"><input name="mail_domain" value="{$FORM_VALUES.mail_domain}" class="formText" size="30" maxlength="255" type="text">

       </span></td>
    </tr>
	<tr valign="top" class="naGrid1">
      <td width="308"   align="right" valign="top"><span class="fieldname">Username:</span></td>
      <td width="3"  align="center" valign="top">&nbsp;</td>
      <td width="442" align="left" valign="top"><span class="formfield"><input  name="mail_username" value="{$FORM_VALUES.mail_username}" class="formText" size="30" maxlength="255" type="text" />
       </span></td>
    </tr>
	<tr valign="top" class="naGrid2">
      <td width="308"   align="right" valign="top"><span class="fieldname">Password:</span></td>
      <td width="3"  align="center" valign="top">&nbsp;</td>
      <td width="442" align="left" valign="top"><span class="formfield"><input name="mail_password" value="{$FORM_VALUES.mail_password}" class="formText" size="30" maxlength="255" type="password" />
       </span></td>
    </tr>

	<tr valign="top" class="naGrid1">
      <td width="308"   align="right" valign="top"><span class="fieldname">Mail Batch:</span></td>
      <td width="3"  align="center" valign="top">&nbsp;</td>
      <td width="442" align="left" valign="top"><span class="formfield"><select name="mail1_batch">
	  <option label="Yes" value="Y" {if $FORM_VALUES.mail1_batch=='Y'} selected="selected" {/if}>Yes</option>
      <option label="No" value="N" {if $FORM_VALUES.mail1_batch=='N' || $FORM_VALUES.mail1_batch=='' } selected="selected" {/if}>No</option>
</select>
</span>&nbsp;&nbsp;&nbsp;<img src="{$GLOBAL.tpl_url}/images/icon_small_info.gif" width="17" height="16" align="top" {popup text="Batch" fgcolor="#eeffaa"} /></td>
    </tr>
	<tr valign="top" class="naGrid2">
      <td width="308"   align="right" valign="top"><span class="fieldname">Max Mails gose out:</span></td>
      <td width="3"  align="center" valign="top">&nbsp;</td>
      <td width="442" align="left" valign="top"><span class="formfield"><input name="mail1_batchcount" value="{$FORM_VALUES.mail1_batchcount}" class="formText" size="30" maxlength="255" type="text">
</span>&nbsp;&nbsp;&nbsp;<img src="{$GLOBAL.tpl_url}/images/icon_small_info.gif" width="17" height="16" align="top" {popup text="Email Batch Count." fgcolor="#eeffaa"} /></td>
    </tr>
	<tr valign="top" class="naGrid1">
      <td width="308"   align="right" valign="top"><span class="fieldname">Delay time:</span></td>
      <td width="3"  align="center" valign="top">&nbsp;</td>
      <td width="442" align="left" valign="top"><span class="formfield"><input name="mail1_timelimit" value="{$FORM_VALUES.mail1_timelimit}"  class="formText" size="30" maxlength="255" type="text">
</span>&nbsp;&nbsp;&nbsp;<img src="{$GLOBAL.tpl_url}/images/icon_small_info.gif" width="17" height="16" align="top" {popup text="Time Delay in Seconds." fgcolor="#eeffaa"} /></td>
    </tr>
	
   <tr valign="top" class="naGrid2">
      <td width="308"   align="right" valign="top"><span class="fieldname">Port:</span></td>
      <td width="3"  align="center" valign="top">&nbsp;</td>
      <td width="442" align="left" valign="top"><span class="formfield"><input type="text" name="mail_port" id="mail_port" value="{$FORM_VALUES.mail_port}"  class="formText" size="30" maxlength="255" >

       </span></td>
    </tr>
	{/if}

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