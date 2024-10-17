
    <input type="hidden" name="cmd" value="_xclick-subscriptions">
	<input type="hidden" name="business" value="{$PAYPAL_ACCOUNT_MAIL}">
	<input type="hidden" name="lc" value="US">
	<input type="hidden" name="item_name" value="Web-store Subscription Fee">
	<input type="hidden" name="no_note" value="1">
	
	<input type="hidden" name="currency_code" value="USD">
	<input type="hidden" name="src" value="1">
	
	<div>
		<div id="subs_pack_ids"></div>
	</div>
	
	
	<input type="hidden" name="a1"  id="al" value="">
	
	<input type="hidden" name="sra" value="1">
				
	<input type="hidden" name="return" id="return_url" value="{makeLink mod='member' pg='register'}act=payment_succes{/makeLink}">
	
	<input type="hidden" name="cancel_return" value="{makeLink mod='member' pg='register'}act=retailar_reg&retailer=1{/makeLink}">
	
	<input type="hidden" name="notify_url" value="{makeLink mod='member' pg='register'}act=payment_notify{/makeLink}">
	
	<!-- Set the initial payment. -->
	
	<input type="hidden" name="item_number" id="item_number" value="" />
	
    <input type="hidden" name="bn" value="PP-BuyNowBF">			
	</div>		
	<div id="reg_amt_div"></div>	
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" >			 
				    <tr>
                
               <td colspan="5" align="center" style="">
			   {if $PAYMENT_MODE_DET.test_mode eq 'Y' and  $smarty.request.web_store_url neq $GLOBAL.webstore_url }
		<div align="center">
			<div style="height:40px; margin-top:20px; color:#FF0000" align="center"><b>{if $PAYMENT_MODE_DET.test_mode_msg neq ''} {$PAYMENT_MODE_DET.test_mode_msg}{else}This website is temporarily closed. Please contact the store owner for further assistance.{/if}</b></div>
		</div>
		{else}
	
	<div id="button" style="width:116px">
	<a href="javascript:void(0);" onclick="validFields();"><img  src="https://www.paypal.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" /></a>
	</div>
			   {/if}
</td>

				  </tr>
  </table>			
                 
	
