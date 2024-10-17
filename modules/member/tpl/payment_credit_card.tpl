{if $PAYPAL_TEST_MODE eq 'Y'}
<form  name="paymentFrm" action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='POST'>
{else}
<form name="paymentFrm" action='https://www.paypal.com/cgi-bin/webscr' method='POST'>
{/if}



<input type="hidden" name="user_id" value="{$smarty.request.user_id}"> 
	<!--<input type="hidden" name="tot_amt" value="{$smarty.request.tot_amt}">
	<input type="hidden" name="sub_pack" value="{$smarty.request.sub_pack}">
	<input type="hidden" name="action" value="{$smarty.request.action}">
	<input type="hidden" name="thnx_setup" id="thnx_setup" value="Y">-->
	<input type="hidden" name="item_name" value="Web-store Subscription Fee">	



	<input type="hidden" name="cmd" value="_xclick-subscriptions">
	<input type="hidden" name="return" value="{$smarty.const.SITE_URL}/{makeLink mod='member' pg='login'}act=y&user_id={$smarty.request.user_id}&thnx=Y{/makeLink}">
	<input type="hidden" name="cancel_return" value="{$smarty.const.SITE_URL}/{makeLink mod='member' pg='register'}act=sub_credpayment&user_id={$smarty.request.user_id}&tot_amt={$smarty.request.tot_amt}&sub_pack={$smarty.request.sub_pack}&action={$smarty.request.action}{/makeLink}">
	
	<input type="hidden" name="notify_url" value="{$smarty.const.SITE_URL}/{makeLink mod='member' pg='register'}act=paypal_update&user_id={$smarty.request.user_id}{/makeLink}">
	<input type="hidden" name="business" value="{$PAYPAL_ACCOUNT_MAIL}">
	<!-- Set the initial payment. -->
	
	<input type="hidden" name="currency_code" value="USD">
	<input type="hidden" name="a1" value="{$TOTAL_AMT}">
	
	<input type="hidden" name="ipn_test" value="1" />
	
	

	{if $SUBPACK_ID eq 1}	
	<input type="hidden" name="p1" value="1">
	<input type="hidden" name="t1" value="M">
	<input type="hidden" name="a3" value="20.00">
	<input type="hidden" name="p3" value="1">
	<input type="hidden" name="t3" value="M">
	<input type="hidden" name="src" value="0">
	<input type="hidden" name="sra" value="">
	{elseif $SUBPACK_ID eq 2}
	<input type="hidden" name="p1" value="3">
	<input type="hidden" name="t1" value="M">
	<input type="hidden" name="a3" value="54.00">
	<input type="hidden" name="p3" value="3">
	<input type="hidden" name="t3" value="M">
	<input type="hidden" name="src" value="0">
	<input type="hidden" name="srt" value="">
	{elseif $SUBPACK_ID eq 4}
	<input type="hidden" name="p1" value="6">
	<input type="hidden" name="t1" value="M">
	<input type="hidden" name="a3" value="102.00">
	<input type="hidden" name="p3" value="6">
	<input type="hidden" name="t3" value="M">
	<input type="hidden" name="src" value="0">
	<input type="hidden" name="srt" value="">
	{elseif $SUBPACK_ID eq 6}
	<input type="hidden" name="p1" value="1">
	<input type="hidden" name="t1" value="Y">
	<input type="hidden" name="a3" value="192.00">
	<input type="hidden" name="p3" value="1">
	<input type="hidden" name="t3" value="Y">
	<input type="hidden" name="src" value="0">
	<input type="hidden" name="srt" value="">
	{/if}
	
	<!--  <input type="hidden" name="amount" value="{$smarty.request.tot_amt}">-->
	<input type="hidden" name="no_shipping" value="1">
	<input type="hidden" name="no_note" value="1">
	<input type="hidden" name="lc" value="US">
	<input type="hidden" name="bn" value="PP-SubscriptionsBF">
				
		<table width="100%" border="0" align="center" cellpadding="4" cellspacing="0"  height="20">
	
                  <tr>
                    <td width="37%" class="bodytext"><div align="right"></td>
                    <td width="63%" align="left">
                    </td>
                  </tr>
                  <tr>
                    <td class="bodytext"><div align="right"></div></td>
                    <td align="left"></td>
                  </tr>
                 
                  <tr>
                   
                    <td colspan="2" align="center">	</td>
                  
				  </tr>
			
  </table>		
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" >
			
				<tr>
				  <td width="17%" align="center" class="bodytext">&nbsp;</td>
				
					<td width="24%" height="25" align="right" class="bodytext"><strong>Registration Fee:</strong></td>
				    <td width="2%" align="center" class="bodytext">&nbsp;</td>
				    <td width="57%" align="left" class="bodytext"><b>${$REG_FEE}</b></td>
				</tr>
				<tr>
				  <td class="bodytext" height="25" align="center">&nbsp;</td>
					<td class="bodytext" align="right"><strong>Subscription Fee:</strong></td>
				    <td class="bodytext" align="center">&nbsp;</td>
				    <td class="bodytext" align="left"><b>${$SUBS_FEE}</b></td>
				</tr>
	
                  <tr>
				  <td class="bodytext" height="25" align="center">&nbsp;</td>
                    <td  align="right" class="bodytext"><strong>Total Fee: </strong></td>      
					 <td class="bodytext" align="center">&nbsp;</td>
					 <td class="bodytext" align="left"><b>${$TOTAL_AMT}</b>  </td>
                  </tr>
				  <tr>
				  		<td>&nbsp;</td>
				  </tr>
				    <tr>
                    <td class="bodytext" colspan="5" align="center">{$PAYMENT_MSG.content}</td>
                  </tr>
				  <tr>
				  		<td>&nbsp;</td>
				  </tr>
                  <tr>
                    <td class="bodytext" colspan="5"><div align="right"></div></td>
                  </tr>
               <td colspan="5" align="center" style=""> {if $PAYMENT_MODE_DET.test_mode eq 'Y' and  $smarty.request.web_store_url neq $GLOBAL.webstore_url }
		<div align="center">
			<div style="height:40px; margin-top:20px; color:#FF0000" align="center"><b>{if $PAYMENT_MODE_DET.test_mode_msg neq ''} {$PAYMENT_MODE_DET.test_mode_msg}{else}This website is temporarily closed. Please contact the store owner for further assistance.{/if}</b></div>
		</div>
		{else}
	<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			   {/if}</td>

				  </tr>
  </table>			
                 
	
</form>