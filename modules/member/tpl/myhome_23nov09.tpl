<div style="height:400px;">
	<div align="left" class="greyboldext">My Account</div>
	<br>
	<br>
	<div class="row" align="center"><strong>Welcome {$UNAME}</strong></div><br>
	<div class="row" align="center">
		{if $LAST_LG.last_login!=""}
	<div align="center">Your last successful login was on {$LAST_LG.last_login|date_format}  at {$LAST_LG.last_login|date_format:"%I:%M:%S %p"}</div>
	{/if}
	</div><br><br>
	<div class="row">
		<div style="float:left; padding-left:10px; width:320px; ">
			<div align="center" class="border" style="width:90%; padding-top:10px; padding-bottom:10px;height:170px; "><br>
			<span><b>Account Settings </b></span>
			<br><br>
			<div class="blacktext" style="right:320px; top:0px; text-align:left; padding-left:10px;">
			<span>These settings control your account settings on The PersonalizedGift</span><br><br>
			<span>
			<a href="{makeLink mod=member pg=register}act=add_edit{/makeLink}" class="bodytext"><u>My Profile</u></a><br />
			<a href="{makeLink mod=member pg=home}act=change_pass{/makeLink}" class="bodytext"><u>Change Password</u></a> <br />
			<a href="{makeLink mod=member pg=register}act=billing_det{/makeLink}" class="bodytext"><u>Billing Address</u></a> <br />
			<a href="{makeLink mod=member pg=register}act=shipping_det{/makeLink}" class="bodytext"><u>Shipping Address</u></a>
			</span>
			</div>
			
			<br>
			</div>
		</div>
		<div style="float:right; padding-right:10px; width:320px; ">
			<div align="center" class="border" style="width:90%; padding-top:10px; padding-bottom:10px; height:170px; "><br>
			<span><b>Purchases</b></span>
			<br><br>
			<div class="blacktext" style="right:320px; top:0px; text-align:left; padding-left:10px;">
			<span>You can see the recent purchase details in The PersonalizedGift</span><br><br>
			<span>
			<a href="{makeLink mod=product pg=fav}act=last_purchase{/makeLink}" class="bodytext"><u>Recent Purchases</u></a><a href="#" class="bodytext"><u></u></a><br />
			<a href="{makeLink mod=member pg=order}act=past{/makeLink}" class="bodytext"><u>Past Orders</u></a><br />
                                                   <!-- <a href="{makeLink mod=member pg=order}act=track{/makeLink}" class="bodytext"><u>Track Order</u></a>
			 <br />-->
			</span>
			</div>
			<br>
			</div>
		</div>
	</div>
			</div>
	