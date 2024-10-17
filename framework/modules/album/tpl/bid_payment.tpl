<div style="width:900px;" class="border">
<div style="height:10px;"><!-- --></div>
<div class="blocktitle" style="background-color:#CCCCCC;height:22px;padding-top:4px;border:1px solid #b5b5b6;">&nbsp;&nbsp;{$MOD_VARIABLES.MOD_LABELS.LBL_PROPERTY_INFORMATION}</div>
<div align="center">
	<div class="border" style="background:url({$GLOBAL.tpl_url}/images/box_bg.jpg);background-repeat:repeat-x;padding:5px;text-align:left;">
		<div style="float:left;width:210px;">
			<img src="{if $PROPERTY_INFORMATION.DefaultImage neq ''}{$smarty.const.SITE_URL}/modules/album/photos/thumb/{$PROPERTY_INFORMATION.DefaultImage}?{$smarty.now}{else}{$GLOBAL.tpl_url}/images/no_image.jpg?{$smarty.request.rnd} {/if}">
		</div>
		<div style="float:right;width:670px;">
			<div class="bodytextbold">{$PROPERTY_INFORMATION.flyer_name}&nbsp;<font color="#666666">[{$PROPERTY_INFORMATION.form_title}]</font></div>
			<div class="bodytext" align="justify">{$PROPERTY_INFORMATION.description|nl2br}</div>
		</div>
		<div style="clear:both;"><!-- --></div>	
	</div>
</div>
<br/>
<div id="errorMsgCont" style="width:98%;text-align:left;display:none;"><img align="absmiddle" src="{$GLOBAL.tpl_url}/images/hi.jpg" id="checkin" style="text-align:left;" border="0"/ ><span class="ajxMessage-normal" id="errorMsg" style="text-align:left;"></span></div>
<div class="blocktitle" style="background-color:#CCCCCC;height:22px;padding-top:4px;border:1px solid #b5b5b6;">&nbsp;&nbsp;{$MOD_VARIABLES.MOD_LABELS.LBL_RATE_INFORMATION}</div>
<div class="border">
<div style="margin-left:5px;margin-right:5px;">

	<div class="border" style="height:20px;padding-top:7px;">
	  <div class="sepertor10px"><span class="blocktitle">Bid Amount:&nbsp;{$BID_INFORMATION.bid_amount|string_format:"%.2f"}</span> </div>
	</div>	
	
	<div style="height:2px;"><!-- --></div>


	
	<div class="sepertor10px"></div>
	
	<div style="width:240px;text-align:center;" align="left">
		<form name="frmPost" style="margin:0px; " action='{$PAYPAL_POST_URL}' method='POST'>
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="return" value="{$GLOBAL.site_url}/{makeLink mod='album' pg='booking'}act=bid_payment_succeess&bid_id={$BID_INFORMATION.bid_id}{/makeLink}">
			<input type="hidden" name="cancel_return" value="{$GLOBAL.site_url}/{makeLink mod='album' pg='booking'}act=bid_payment&bid_id={$BID_INFORMATION.bid_id}{/makeLink}">
			<input type="hidden" name="notify_url" value="{$GLOBAL.site_url}/{makeLink mod='album' pg='booking'}act=bid_payment_notify&bid_id={$BID_INFORMATION.bid_id}&bidder_id={$BID_INFORMATION.BidderId}&prop_ownerid={$BID_INFORMATION.PropertyOwnerId}{/makeLink}">
			<input type="hidden" name="business" value="{$BID_INFORMATION.paypal_account}">
			<input type="hidden" name="amount" value="{$BID_INFORMATION.bid_amount}">
			<!--<input type="hidden" name="no_shipping" value="1">-->
			<input type="hidden" name="no_note" value="1">
			<input type="hidden" name="currency_code" value="USD">
			<input type="hidden" name="lc" value="US">
			<input type="hidden" name="bn" value="PP-BuyNowBF">
			<!--<input type="hidden" name="address_override" value="1">-->
			<input type="hidden" name="first_name" value="{$MEMBER_DETAILS.first_name}">
			<input type="hidden" name="last_name" value="{$MEMBER_DETAILS.last_name}">
			<input type="hidden" name="address1" value="{$MEMBER_DETAILS.address1}">
			<input type="hidden" name="address2" value="{$MEMBER_DETAILS.address2}">
			<input type="hidden" name="city" value="{$MEMBER_DETAILS.city}">
			<input type="hidden" name="zip" value="{$MEMBER_DETAILS.postalcode}">
			<input type="hidden" name="country" value="{$COUNTRY2_CODE}">
			<input type='hidden' name="night_phone_a" value="{$MEMBER_DETAILS.telephone}">
			<input type='hidden' name='email' value="{$MEMBER_DETAILS.email}">
			<input type="hidden" name="item_name" value="BID PROPERTY NUMBER: {$BID_INFORMATION.album_id}">
			<input type="image" src="{$GLOBAL.tpl_url}/images/paynow.jpg" id="paynow">
		</form>
	</div>
</div>
</div>

