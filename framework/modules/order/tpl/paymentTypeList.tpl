<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
{literal}
<script language="javascript" type="text/javascript">
function check(id,fmobj,chekname)
{
var TotalBoxes = 0;
var TotalOn = 0;
for (var i=0;i<fmobj.elements.length;i++)
{
	var e = fmobj.elements[i];			
	if(e.name==chekname)
	{
		if(e.value==id)
		{
			e.checked=true;	
		}		
	}
}
} 
</script>
{/literal}
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table width="80%" class=naBrdr><tr><td>


<form name="frm_payment" method="post" action="" style="margin: 0px;">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
<table width=100% border=0 cellpadding="2" cellspacing="0">
<tr> <td nowrap class="naH1" colspan="3">{$smarty.request.sId}<input type="hidden" name="limit"  value="{$smarty.request.limit}"/></td> </tr> 
<!--<tr>
    <td class="naGrid1" colspan="3" height="20">
	
	{if $smarty.request.manage ne 'manage'}
	<a class="linkOneActive" href="#" onclick="javascript: document.frm_payment.action='{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=inactive&fId={$smarty.request.fId}&sId={$smarty.request.sId}&limit={$smarty.request.limit}{/makeLink}'; document.frm_payment.submit();">Active</a>&nbsp;&nbsp;
	<a class="linkOneActive" href="#" onclick="javascript: document.frm_payment.action='{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=active&fId={$smarty.request.fId}&sId={$smarty.request.sId}&limit={$smarty.request.limit}{/makeLink}'; document.frm_payment.submit();">Inactive</a></td>
    {/if}
	</tr>-->
		{if count($PAYMENTMODE) > 0}
	    <tr>
          <td width="35%" height="25" align="left" nowrap class="naGridTitle"><!--{makeLink mod=$smarty.request.mod pg=paymentType orderBy=name display="`$smarty.request.sId` Name"}act=type&fId={$smarty.request.fId}&sId={$smarty.request.sId}{/makeLink} -->&nbsp;&nbsp;{$smarty.request.sId} Name</td>
		  <td width="61%" align="center" nowrap class="naGridTitle"><!--{makeLink mod=$smarty.request.mod pg=paymentType orderBy=active display="Status"}act=type&fId={$smarty.request.fId}&sId={$smarty.request.sId}{/makeLink}--></td>
	    </tr>
        {foreach from=$PAYMENTMODE item=payment}
			
			{if $MOD eq 'order'}
				{if $payment->name eq 'Credit Card'}
					{assign var='pmode' value='paymentlist'}
					{assign var='page' value='payment'}
				{elseif $payment->name eq 'Call with creditcard'}
					{assign var='pmode' value='maillist'}
					{assign var='page' value='paymentType'}
				{elseif $payment->name eq 'Electronic Check'}
					{assign var='pmode' value='maillist'}
					{assign var='page' value='paymentType'}
				{elseif $payment->name eq 'Paypal Account'}
					{assign var='pmode' value='paypallist'}
					{assign var='page' value='paymentType'}
				{elseif $payment->name eq '2CheckOut'}
					{assign var='pmode' value='twocheckout'}
					{assign var='page' value='paymentType'}
				{elseif $payment->name eq 'Mail a Check'}
					{assign var='pmode' value='mailcheckform'}
					{assign var='page' value='paymentType'}
				{elseif $payment->name eq 'GoogleCheckout'}
					{assign var='pmode' value='googlecheckoutform'}
					{assign var='page' value='paymentType'}
				{elseif $payment->name eq 'Worldpay'}
					{assign var='pmode' value='worldpayform'}
					{assign var='page' value='paymentType'}
				{/if}
			{elseif $MOD eq 'store'}
				{if $payment->name eq 'Credit Card'}
					{assign var='pmode' value='paymentlist'}
					{assign var='page' value='order_payment'}
				{elseif $payment->name eq 'Call with creditcard'}
					{assign var='pmode' value='maillist'}
					{assign var='page' value='paymentType'}
				{elseif $payment->name eq 'Mail a Check'}
					{assign var='pmode' value='maillist'}
					{assign var='page' value='order_paymentType'}
				{elseif $payment->name eq 'Paypal Account'}
					{assign var='pmode' value='paypallist'}
					{assign var='page' value='order_paymentType'}
				{elseif $payment->name eq '2CheckOut'}
					{assign var='pmode' value='twocheckout'}
					{assign var='page' value='order_paymentType'}
				{elseif $payment->name eq 'GoogleCheckout'}
					{assign var='pmode' value='googlecheckoutform'}
					{assign var='page' value='order_paymentType'}
				{elseif $payment->name eq 'Worldpay'}
					{assign var='pmode' value='worldpayform'}
					{assign var='page' value='order_paymentType'}
					
				{/if}
				
			{/if}
        <tr class="{cycle name=bg values="naGrid1,naGrid2"}">
		 {if $payment->id eq '2'}
          <td valign="middle" align="left"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=$page}act=paypalform&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}">&nbsp;&nbsp;{$payment->name}</a></td> 
		 {elseif  $payment->id eq '4'}
		  <td valign="middle" align="left"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=$page}act=callform&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}">&nbsp;&nbsp;{$payment->name} </a></td> 
		 {elseif  $payment->id eq '5'}
		 	<td valign="middle" align="left"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=$page}act=mailcheckform&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}">&nbsp;&nbsp;{$payment->name} </a></td> 
		 {else}
		  <td valign="middle" align="left"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=$page}act={$pmode}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}">&nbsp;&nbsp;{$payment->name} </a></td> 
		  {/if}
          <td valign="middle" align="center">
		  <!--
		  {if $smarty.request.manage ne 'manage'}
		  {if $payment->active eq 'Y' } 	 
			<img border="0"    title="Activate"   src="{$smarty.const.SITE_URL}/framework/includes/images/active{$payment->active}.gif"/>
					<a href="#" onclick="javascript: document.frm_payment.action='{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=inactive&fId={$smarty.request.fId}&sId={$smarty.request.sId}&limit={$smarty.request.limit}{/makeLink}'; document.frm_payment.submit();"><img border="0" name="{$payment->id}"   title="Deactivate" onClick="check(this.name,document.frm_payment,'mode_id[]')" src="{$smarty.const.SITE_URL}/framework/includes/images/deactive{$payment->active}.gif"/></a>
		{else}
			<a href="#" onclick="javascript: document.frm_payment.action='{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=active&fId={$smarty.request.fId}&sId={$smarty.request.sId}&limit={$smarty.request.limit}{/makeLink}'; document.frm_payment.submit();"><img border="0" title="Activate"  name="{$payment->id}" onClick="check(this.name,document.frm_payment,'mode_id[]')"  src="{$smarty.const.SITE_URL}/framework/includes/images/active{$payment->active}.gif"/></a>
			<img border="0"   title="Deactivate"  src="{$smarty.const.SITE_URL}/framework/includes/images/deactive{$payment->active}.gif"/>
		{/if}
		{/if}
		-->
		</td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="3" class="msg" align="center" height="30"><!--{$PRICE_NUMPAD}--></td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="3" class="naError" align="center" height="30">No Records</td> 
        </tr>
		 {/if}
		 
      </table>	
</form>
</td></tr></table>