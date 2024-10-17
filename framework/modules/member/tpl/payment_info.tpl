<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>
<script language="javascript">
{literal}
function loadSubsPackIds(sub_pack)
{
	document.getElementById('pay_button').innerHTML = '<strong>Loading Please Wait</strong>';
	var item_number = {/literal}{$smarty.request.user_id}{literal}+'_'+sub_pack;
	document.getElementById('item_number').value = item_number;

	var req1 = newXMLHttpRequest();
	req1.onreadystatechange = getReadyStateHandler(req1, serverResloadSubsPackIds);
	
	{/literal}
	str="sub_pack="+sub_pack;
	req1.open("POST", "{$smarty.const.SITE_URL}/{makeLink mod=member pg=ajax_store}act=subs_pack_vals_fixval{/makeLink}&"+Math.random());
	{literal}
	req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	
	req1.send(str);
	
	
	
}
function serverResloadSubsPackIds(_var)
{
	document.getElementById('subs_pack_ids').innerHTML =_var;
	document.getElementById('pay_button').innerHTML = '<input name="image" type="image" src="https://www.paypal.com/en_US/i/btn/btn_subscribeCC_LG.gif">';
}

function chekSubVal()
{
	if(document.getElementById('sub_drop').value == '')
	{
		alert("Please select your subscription");
		return false;
	}
	else
	{
		var sub_id = document.getElementById('sub_drop').value;
		sub_id = {/literal}{$smarty.request.user_id}{literal}+'_'+sub_id;
		var item_number = document.getElementById('item_number').value;
		if(item_number != sub_id)
		{
			document.getElementById('item_number').value = sub_id;
			return true;
		}
	}
}


{/literal}
</script>

	

<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
	{if  $smarty.session.store_renew_msg neq 1}
	
      <tr>
        <td>{messageBox}</td>
      </tr>
	  {/if}
    </table>
    <table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr>
    <tr>
        <td></td>
      </tr> 
      <tr> 
        <td><table width="98%" align="center"> 
            <tr> 
              <td nowrap class="naH1">Make payment </td> 
              <td nowrap align="right" class="titleLink">&nbsp;</td> 
            </tr> 
        </table></td> 
      </tr> 
      <tr> 
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#F6F5F5" >
                <tr>
                  <td ><table width="100%" height="24" border="0" cellpadding="0" cellspacing="0" >
                          <tr>
                            <td width="1%" class="naGridTitle">&nbsp;</td>
                            <td width="99%" class="naGridTitle">&nbsp;</td>
                          </tr>
                      </table></td>
                </tr>
                <tr>
                  <td align="center" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="10">
				 	{if  $smarty.session.store_renew_msg neq 1}
				    {if isset($MESSAGE)}
				    <tr class=naGrid2>
					    <td valign=top colspan=3 align="center">
					    <span class="naError">{$MESSAGE}</span>
				      </td>
				    </tr>
			        {/if} 
					 {/if} 
                    <tr>
                      <td align="center" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0" >
					  {if $smarty.request.action eq''}
					  {if ($PACK[1]>0)}
						<!--/*<tr>
							<td width="44%" align="right"><b>Set-Up Fee</b></td>
							<td width="2%" align="center" valign="middle"><span class="blacktext">:</span></td>
							<td width="54%" height="30" colspan="3">${$PACK[1]|string_format:"%.2f"} {$PACK[0]}(One time Setup fee)</td>
						</tr>*/-->
						<!--
						<tr>
							<td width="44%" align="right">&nbsp;</td>
							<td width="2%" align="center" valign="middle">&nbsp;</td>
							<td width="54%" height="30" colspan="3">Note: This Setup Fee includes up to 1 hour of support if assistance is needed.</td>
						</tr>
						-->
						{/if}
						<tr>
							<td width="44%" align="right"><b>Select Subscription</b> </td>
							<td width="2%" align="center" valign="middle">:</td>
							<td width="54%" height="30" colspan="3"><select name="sub_drop" id="sub_drop" onchange="loadSubsPackIds(this.value)">
							<option value="" >--Select Subscription--</option>
							{foreach from=$SUB_PACKS item=sub_pack}
							<option value="{$sub_pack->id}">{$sub_pack->name} ({$sub_pack->duration}{if $sub_pack->type eq 'M'} Month{elseif $sub_pack->type eq 'Y'} Year{/if})(${$sub_pack->fees})</option>
							{/foreach}
							</select></td>
						</tr>
						<!--<tr>
							<td width="44%" align="right"><b>Next Subscription Fee Due Date</b></td>
							<td width="2%" align="center" valign="middle">:</td>
							<td width="54%" height="30" colspan="3">{$ENDDATE|date_format}</td>
						</tr>-->
						<!--<tr>
							<td width="44%" align="right"><b>Total Start-Up Fee($) </b></td>
							<td width="2%" align="center" valign="middle">:</td>
							<td width="54%" height="30" colspan="3">${$TOT_AMT}</td>
						</tr>-->
						{else}
						<tr>
							<td width="44%" align="right"><b>Subscription Package </b></td>
							<td width="2%" align="center" valign="middle">:</td>
							<td width="54%" height="30" colspan="3">&nbsp;</td>
						</tr>
						{/if}
				        <tr >
				          <td>&nbsp;</td>
				          <td>&nbsp;</td>
				          <td  valign=center>&nbsp;</td>
			            </tr>
				        <tr > 
							<td colspan="3">
							{if $PAYPAL_TEST_MODE eq 'Y'}
<form  name="frmReg" action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='POST' onsubmit="return chekSubVal();">
{else}
<form name="frmReg" action='https://www.paypal.com/cgi-bin/webscr' method='POST'  onsubmit="return chekSubVal();">
{/if}
<input type="hidden" name="cmd" value="_xclick-subscriptions">
<input type="hidden" name="business" value="{$PAYPAL_ACCOUNT_MAIL}">
	<input type="hidden" name="lc" value="US">
	<input type="hidden" name="item_name" value="Web-store Subscription Fee">
	<input type="hidden" name="no_note" value="1">
	
	<input type="hidden" name="currency_code" value="USD">
	<input type="hidden" name="src" value="1">
	<input type="hidden" name="a1"  id="al" value="">
	<input type="hidden" name="item_number" id="item_number" value="" />
	<input type="hidden" name="sra" value="1">
	<span id="subs_pack_ids"></span>

	<input type="hidden" name="return" value="{$smarty.const.SITE_URL}/{$smarty.session.storeSess[0]->name}/{$smarty.request.manage}/index.php">
	<input type="hidden" name="cancel_return" value="{$smarty.const.SITE_URL}/{$smarty.session.storeSess[0]->name}/{$smarty.request.manage}/{makeLink mod='store' pg='member_user'}act=confirm_payment&user_id={$smarty.request.user_id}&exp=1{/makeLink}">
	
	<input type="hidden" name="notify_url" value="{$smarty.const.SITE_URL}/{makeLink mod='member' pg='register'}act=paypal_fix_update&itemno={$STORE_DET->txn_id}{/makeLink}">
	
	{if $smarty.request.modify eq 1}
	 <input type="hidden" name="modify" value="1"> 
	{/if} 
	
							<table width="100%" cellpadding="0" cellspacing="0" border="0" ><tr>
				  		      <td width="44%"><div align="right">
			  		          </div></td>
						      <td width="8%"></td>
					          <td width="54%"  valign=center><span id="pay_button"><input name="image" type="image" src="https://www.paypal.com/en_US/i/btn/btn_subscribeCC_LG.gif"></span></td></tr></table></form></td>
				        </tr> 
				        <tr>
				 	      <td>&nbsp;</td>
				        </tr>
				        </table></td>
                    </tr>
                    <tr>
                      <td align="center" valign="top">&nbsp;</td>
                    </tr>
                    </table>
			      </td>
                </tr>
       </table></td> 
      </tr> 
    </table>
  

