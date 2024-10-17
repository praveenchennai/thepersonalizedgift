<div style="height:400px;">
	<div align="left" class="greyboldext">{$MOD_VARIABLES.MOD_HEADS.HD_MY_ACCOUNT}</div>
	<br>
	<br>
	<div class="row" align="center"><strong>{$MOD_VARIABLES.MOD_COMM.COMM_TOP_WELCOME} {$UNAME}</strong></div><br>
	<div class="row" align="center">
		{if $LAST_LG.last_login!=""}
	<div align="center">{$MOD_VARIABLES.MOD_LABELS.LBL_LAST_LOGIN} {$LAST_LG.last_login|date_format}  {$MOD_VARIABLES.MOD_COMM.COMM_AT} {$LAST_LG.last_login|date_format:"%I:%M:%S %p"}</div>
	{/if}
	</div><br><br>
	<div class="row">
		<div style="float:left; padding-left:10px; width:320px; ">
			<div align="center" class="border" style="width:90%; padding-top:10px; padding-bottom:10px;height:170px; "><br>
			<span><b>{$MOD_VARIABLES.MOD_LABELS.LBL_ACCOUNT_SETTINGS} </b></span>
			<br><br>
			<div class="blacktext" style="right:320px; top:0px; text-align:left; padding-left:10px;">
			<span>{$MOD_VARIABLES.MOD_LABELS.LBL_ACCOUNT_SETTINGS_DES}</span><br><br>
			<span>
			<a href="{makeLink mod=member pg=register}act=add_edit{/makeLink}" class="bodytext"><u>{$MOD_VARIABLES.MOD_LABELS.LBL_MY_PROFILE}</u></a><br />
			<a href="{makeLink mod=member pg=home}act=change_pass{/makeLink}" class="bodytext"><u>{$MOD_VARIABLES.MOD_LABELS.LBL_CHANGE_PASSWORD}</u></a> <br />
			<a href="{makeLink mod=member pg=register}act=billing_det{/makeLink}" class="bodytext"><u>{$MOD_VARIABLES.MOD_LABELS.LBL_BILLING_ADDRS}</u></a> <br />
			<a href="{makeLink mod=member pg=register}act=shipping_det{/makeLink}" class="bodytext"><u>{$MOD_VARIABLES.MOD_LABELS.LBL_SHIPPING_ADDRS}</u></a>
			</span>
			</div>
			
			<br>
			</div>
		</div>
		<div style="float:right; padding-right:10px; width:320px; ">
			<div align="center" class="border" style="width:90%; padding-top:10px; padding-bottom:10px; height:170px; "><br>
			<span><b>{$MOD_VARIABLES.MOD_LABELS.LBL_PURCHASES}</b></span>
			<br><br>
			<div class="blacktext" style="right:320px; top:0px; text-align:left; padding-left:10px;">
			<span>{$MOD_VARIABLES.MOD_LABELS.LBL_PURCHASES_DES}</span><br><br>
			<span>
			<a href="{makeLink mod=product pg=fav}act=last_purchase{/makeLink}" class="bodytext"><u>{$MOD_VARIABLES.MOD_LABELS.LBL_RECENT_PURCHASE}</u></a><a href="#" class="bodytext"><u></u></a><br />
			<a href="{makeLink mod=member pg=order}act=past{/makeLink}" class="bodytext"><u>{$MOD_VARIABLES.MOD_LABELS.LBL_PAST_ORDERS}</u></a><br />
                                                   <!-- <a href="{makeLink mod=member pg=order}act=track{/makeLink}" class="bodytext"><u>Track Order</u></a>
			 <br />-->
			</span>
			</div>
			<br>
			</div>
		</div>
	</div>
			</div>
	