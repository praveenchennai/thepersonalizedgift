<div id="loadDyn1"  style="position:absolute; display:yes;height:200px; width:8	00px">
	  				<table width="550" border="0" cellspacing="0" cellpadding="0"   >
								
						 		 <tr>
									<td width="20" height="20"><img src="{$GLOBAL.tpl_url}/images/popup_topleft.png" width="20" height="20" /></td>
									<td width="248" background="{$GLOBAL.tpl_url}/images/popup_top.png">&nbsp;</td>
									<td width="20" height="20"><img src="{$GLOBAL.tpl_url}/images/popup_topright.png" width="20" height="20" /></td>
							 	 </tr>
								  <tr>
									<td height="30" background="{$GLOBAL.tpl_url}/images/popup_left.png">&nbsp;</td>
									<td width="500" bgcolor="#FFFFFF" ><table cellpadding="0" width="900" cellspacing="0" border="0" class="blacktext" height="300">
									<tr>
									
									<td  height="5"  valign="top" align="right" >
									<img src="{$GLOBAL.tpl_url}/images/close12.jpg" border="0" onclick="javascript:viewBidList()"; style="cursor:pointer; ">
									</td>
									</tr>
									<tr><td height="20%"  valign="top">
									<div id="con_list1" style="overflow:auto; height:500px ">
<div align="center" style="border:1px solid #afafaf;width:880px;">
	<!-- BEGIN PRICE INFORMATION 1 -->
	<div style="width:100%;">
	<div class="blocktitle" style="padding-left:20px;">{$MOD_VARIABLES.MOD_HEADS.HD_BIDS_FOR}</div><div style="height:10px;"><!-- --></div>
        <div style="height:3px"><!-- --></div>

        <div style="height:3px"><!-- --></div>
		{if count($FIXED_BLOCK1)>0}
		<div class="meroon" align="left" style="padding-left:20px;height:25px"><div id="col1" style="float:left;width:64%">&nbsp;</div><div style="float:left;width:3%;background-color:#FF7575;border:1px solid #000000;" align="center">&nbsp;</div><div id="col2" class="bodytext" style="padding-left:3px;float:left"><strong> - {$MOD_VARIABLES.MOD_LABELS.LBL_AUCTION_DISABLED}</strong></div><div style="width:10px;float:left">&nbsp;</div><div style="float:left;width:3%;background-color:#FFFFCC;border:1px solid #000000;" align="center">&nbsp;</div><div id="col2" class="bodytext" style="padding-left:3px;float:left"><strong> - {$MOD_VARIABLES.MOD_LABELS.LBL_AUCTION_ENABLED}</strong></div></div>
		{else}
			<div align="center" class="bodytext">{$MOD_VARIABLES.MOD_LABELS.LBL_NO_REC_BID_DET}</div>
		{/if}
		<div style="padding-left:20px;padding-right:20px">
		{if count($FIXED_BLOCK1)>0}
				<div class="bodytext" align="left" style="border:1px solid #6D0506;background-color:#F1F1F1">
					<div style="float:left;width:5px">&nbsp;</div>
					
					<div style="float:left;width:15%" align="center"><strong>{$MOD_VARIABLES.MOD_LABELS.LBL_STARTDATE}</strong></div>
					<div style="float:left;width:15%" align="center"><strong>{$MOD_VARIABLES.MOD_LABELS.LBL_ENDDATE}</strong></div>
					<div style="float:left;width:15%" align="center"><strong>{$MOD_VARIABLES.MOD_LABELS.LBL_PRICE}</strong></div>
					<div style="float:left;width:17%" align="center"><strong>{$MOD_VARIABLES.MOD_LABELS.LBL_DURATION}</strong></div>
					<div style="float:left;width:10%" align="center"><strong>{$MOD_VARIABLES.MOD_LABELS.LBL_UNIT}</strong></div>
					
					
					<div style="float:left;width:5%" align="center">&nbsp;</div>
					<div style="clear:both"></div>
				</div>
{/if}
			{foreach from=$FIXED_BLOCK1 item=fixRow name=fixname}
			<div class="bodytext" align="left" style="border-left:1px solid #6D0506;border-right:1px solid #6D0506;border-bottom:1px solid #6D0506;{if $fixRow.auction eq 'Y'} {if $fixRow.auction_closed=='Y'}background-color:#FF7575;{else}background-color:#FFFFCC; {/if}{else}background-color:#FDFDFD;{/if}">
			<div style="float:left;width:3%;cursor:pointer;"   align="center"></div>
			  
			  <div style="float:left;width:15%" align="center">{$fixRow.start_date_format}</div>
			  <div style="float:left;width:15%" align="center">{$fixRow.rental_end_date_format}</div>
			  <div style="float:left;width:15%" align="center">${$fixRow.price}</div>
			  <div style="float:left;width:17%" align="center">{$fixRow.duration}</div>
			  <div style="float:left;width:10%" align="center">{$fixRow.unit|upper}</div>
			  <div style="float:left;width:5%" align="center">&nbsp;</div>
			  <div style="clear:both"></div>
			  {if $fixRow.auction eq "Y"}
			  
			  {if $fixRow.auction_closed=="Y"}
			  <div style="float:left;width:5%;">
			    <!-- -->
		      </div>
			  <div style="float:left;width:708px;" align="center" class="bodytext"> {$fixRow.auction_close_log}</div>
			  <div style="float:right;width:10%" align="center">&nbsp;</div>
			  <div style="clear:both">
			    <!-- -->
		      </div>
			  {else}
			  <div style="float:left;width:5%;">&nbsp;</div>
			  <div style="float:left;width:25%" align="center"><strong>{$MOD_VARIABLES.MOD_LABELS.LBL_STARTBID} :</strong> ${$fixRow.start_bid}</div>
			  <div style="float:left;width:30%" align="center"><strong>{$MOD_VARIABLES.MOD_LABELS.LBL_AUCTIONEND} :</strong> {$fixRow.auctionFormat_date}</div>
			  <div style="float:left;width:30%">&nbsp;</div>
			  <div style="float:right;width:10%;" align="center">&nbsp;</div>
			  <div style="clear:both">
			    <!-- -->
		      </div>
			  {/if}
			  
			  {/if} </div>
<div id="splitBlockPriceDiv_1{$fixRow.id}" style="display:none">
				
				<div class="bodytext" align="left" style="border-left:1px solid #999999;border-right:1px solid #999999;border-bottom:1px solid #999999;">
				
					<div style="float:left;width:25%" align="left"><strong>{$MOD_VARIABLES.MOD_LABELS.LBL_BID_DET_PROP_DET}</strong></div>
					<div style="float:left;width:65%" align="left">&nbsp;</div>
					<div style="float:left;width:10%;" align="right">&nbsp;</div>
					<div style="clear:both"></div>
					
				</div>
<div class="bodytext" align="left" style="border-left:1px solid #999999;border-right:1px solid #999999;border-bottom:1px solid #999999;">
					<div style="float:left;width:25%" align="right"><strong>{$MOD_VARIABLES.MOD_LABELS.LBL_BID_DET_PROP_NAME}</strong></div>
					<div style="float:left;padding-left:5px;width:65%" align="left">{$fixRow.property.flyer_name}</div>
					<div style="float:left;width:5%;" align="right">&nbsp;</div>
					<div style="clear:both"></div>
					<div style="float:left;width:25%" align="right"><strong>{$MOD_VARIABLES.MOD_LABELS.LBL_BID_DET_PROP_DESC}</strong></div>
					<div style="float:left;padding-left:5px;width:65%" align="left">{$fixRow.property.description}</div>
					<div style="float:left;width:5%;" align="right">&nbsp;</div>
					<div style="clear:both"></div>
					
				</div>
				<!-- Bid Div Was here -->
				</div>
<div id="splitBlockPriceDiv_1{$fixRow.id}" >
				{if count($fixRow.Blocks)==0}
				<div align="left" style="border-left:1px solid #999999;border-right:1px solid #999999;border-bottom:1px solid #999999;" class="bodytext">{$MOD_VARIABLES.MOD_LABELS.LBL_NO_BID_OWNER}</div>
				{else}
				<div align="left" style="border-left:1px solid #999999;border-right:1px solid #999999;border-bottom:1px solid #999999;" class="bodytext"><strong>{$MOD_VARIABLES.MOD_LABELS.LBL_OWNER_BID_DET}</strong>
				</div>
				
				<div class="bodytext" align="left" style="border-left:1px solid #999999;border-right:1px solid #999999;border-bottom:1px solid #999999;">
					<div style="float:left;width:10%" align="center">&nbsp;</div>
					<div style="float:left;width:20%" align="left"><strong>{$MOD_VARIABLES.MOD_LABELS.LBL_BID_DET_BID_USER}</strong></div>
					<div style="float:left;width:22%" align="left"><strong>{$MOD_VARIABLES.MOD_LABELS.LBL_BID_DET_BID_DATE}</strong></div>
					<div style="float:left;width:30%" align="left"><strong>{$MOD_VARIABLES.MOD_LABELS.LBL_BID_DET_BID_AMT}</strong></div>
					<div style="clear:both"></div>
						</div>	
											{/if}
				{foreach key=key  from=$fixRow.Blocks item=fixBRow name=fixBname}				
				<div class="bodytext" align="left" style="border-left:1px solid #999999;border-right:1px solid #999999;border-bottom:1px solid #999999;">
					<div style="float:left;width:10%" align="center">{$smarty.foreach.fixBname.index+1} ]</div>
					<div style="float:left;width:20%" align="left">{$fixBRow.username}</div>
					<div style="float:left;width:20%" align="left">{$fixBRow.bid_date|date_format:"%e, %b %Y"}</div>
					<div style="float:left;width:10%" align="right">${$fixBRow.bid_amount|string_format:"%.2f"}</div>
					<div style="float:left;width:2%">&nbsp;</div>
					<div  style="float:left;width:20%;padding-left:10px;" align="left"></div>
					<div style="clear:both"></div>
						</div>
				{/foreach}					
				<!-- Bid Div Was here -->
		  </div>
				
				
			{/foreach}
		</div>

	
	<div style="clear:both"></div>
	</div>	
	
	<div class="divSpc"></div>
	<div class="sepertor10px"><!-- --></div>
	<!-- END PRICE INFORMATION 1 -->
	
	
	
	
	<div class="divSpc"></div>
	<div class="sepertor10px"><!-- --></div>
	
	<!-- VIDEO LISTING START HERE -->
	<div style="width:100%;">
	  <div class="sepertor10px"><!-- --></div>
			<div class="divSpc"></div>

	
	</div>
	<div style="width:100%;text-align:center;display:block;">
	  <div class="sepertor10px"><!-- --></div>
		
		<div class="divSpc"></div>
	</div>
	
	<!-- My Avialability Calendar End Here-->
	
</div>
 </div>
									  </td></tr></table>
									</td>
									<td background="{$GLOBAL.tpl_url}/images/popup_right.png">&nbsp;</td>
								  </tr>
								  <tr>
									<td width="20" height="20"><img src="{$GLOBAL.tpl_url}/images/popup_bottomleft.png" width="20" height="20" /></td>
									<td background="{$GLOBAL.tpl_url}/images/popup_bottom.png">&nbsp;</td>
									<td width="20" height="20"><img src="{$GLOBAL.tpl_url}/images/popup_bottomright.png" width="20" height="20" /></td>
								  </tr>
								</table></div>
