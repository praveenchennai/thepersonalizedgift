
   
	<input type="hidden" name="return" id="return_url" value="{makeLink mod='member' pg='register'}act=payment_succes{/makeLink}">
	<input type="hidden" name="cancel_return" value="{makeLink mod='member' pg='register'}act=retailar_reg&retailer=1{/makeLink}">
	<input type="hidden" name="notify_url" value="{makeLink mod='member' pg='register'}act=payment_notify{/makeLink}">
	<!-- Set the initial payment. -->
	
	<input type="hidden" name="a1"  id="al" value="">
	<input type="hidden" name="amount"  value="200">
	<input type="hidden" name="item_number" id="item_number" value="" />
	
	<div>
		<div id="subs_pack_ids"></div>
	</div>
	
	
				
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
	
	<div id="button">
	<a href="javascript:void(0);" onclick="validFields();"><img src="https://www.paypal.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" /></a>
	</div>
			   {/if}
</td>

				  </tr>
  </table>			
                 
	
