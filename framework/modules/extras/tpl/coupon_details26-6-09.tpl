<table width="100%"  border="0">
  <tr>
    <td>
	<table width=80% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
 	<tr align="left">
      <td colspan=4 valign=top><table width="100%" align="center">
        <tr>
          <td width="17%" nowrap class="naH1">Coupon Details </td>
          <td width="83%" align="right" nowrap class="titleLink"><a href="{makeLink mod=extras pg=extras}act=list{/makeLink}">CouponList</a> </td>
        </tr>
      </table></td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=4></td>
    </tr>
    {/if}
    <tr class=naGrid2>
      <td width="30%"  align="right" valign=top> <strong>Name</strong> </td>
      <td width="2%" valign=top>:</td>
      <td colspan="2" align="left">{$COUPON_DETAILS.coupon_name}</td>
    </tr>
    <tr class=naGrid2>
      <td width="30%"  align="right" valign=top><strong>Coupon Key</strong> </td>
      <td width="2%" valign=top>:</td>
      <td colspan="2" align="left">{$COUPON_DETAILS.coupon_no}</td>
    </tr>
    <tr class=naGrid2> 
      <td width="30%"  align="right" valign=top> <strong>Start Date</strong> </td> 
      <td width=2% valign=top>:</td> 
      <td colspan="2" align="left">{$COUPON_DETAILS.coupon_start}    </td>
    </tr>
   <tr class=naGrid2>
     <td width="30%" align="right" valign=top> <strong>Expiry Date</strong> </td>
     <td width="2%" valign=top>:</td>
     <td colspan="2" align="left">{$COUPON_DETAILS.coupon_end}  </td>
   </tr>
   <tr class=naGrid2>
     <td width="30%" align="right" valign=top><strong>Amount Type</strong>  </td>
     <td width="2%" valign=top>:</td>
     <td width="20%" align="left">
	  {if $COUPON_DETAILS.coupon_amounttype=='A'}Amount
	  {elseif $COUPON_DETAILS.coupon_amounttype=='P'}Percentage
	  {else}Free Type {/if}	</td>
     <td width="350" align="left"><table width="100%"  border="0" align="left" cellpadding="0" cellspacing="0">
       <tr align="left">
         <td width="20%" align="right"><strong>Amount</strong>&nbsp;&nbsp;</td>
         <td width="20%">{$COUPON_DETAILS.coupon_amount}</td>
		 <td width="60%">&nbsp;</td>
       </tr>
     </table></td>
   </tr>
   <tr class=naGrid2>
     <td width="30%" align="right" valign=top> <strong>Substract From</strong></td>
     <td width="2%" valign=top>:</td>
     <td colspan="2" align="left">{if $COUPON_DETAILS.substract_from=='T'}  Total{elseif $COUPON_DETAILS.substract_from=='O'}  Options {elseif $COUPON_DETAILS.substract_from=='M'}  Products{elseif $COUPON_DETAILS.substract_from == 'P'}  Product & Options{elseif $COUPON_DETAILS.substract_from == 'S'}Subscriptions{/if}</td>
     </tr>
   <tr class=naGrid2>
     <td width="30%" align="right" valign=top><strong>Options</strong></td>
     <td width="2%" valign=top>:</td>
     <td width="20%" align="left">
	 	{if $COUPON_DETAILS.coupon_options=='one'} One Time use
		{elseif $COUPON_DETAILS.coupon_options=='unlimit'} Unlimited use
		{else}Fixed{/if}	 </td>	   
     <td align="left">
	 {if $COUPON_DETAILS.coupon_options=='fixed'}
	 <table width="100%"  border="0" cellpadding="0" cellspacing="0">
       <tr>
         <td width="28%" align="right"><strong>No of Times&nbsp;&nbsp;</strong></td>
         <td width="72%" align="left">{$COUPON_DETAILS.one_times}</td>
       </tr>
     </table>
	 {/if}	 </td>
   </tr>
   {if $COUPON_DETAILS.user_id!=0}
	   <tr class=naGrid2>
		 <td width="30%" align="right" valign=top> <strong>Assigned User</strong></td>
		 <td width="2%" valign=top>:</td>
		 <td colspan="2" align="left"> <a class="linkOneActive" href="{makeLink mod=member pg=user}act=view&id={$COUPON_DETAILS.user_id}{/makeLink}"> {$USER_DETAILS.first_name}{$USER_DETAILS.last_name}</a></td>
	   </tr>
   {/if}

{if $COUPON_DETAILS.Subscriptions|@count > 0}
   <tr class=naGrid2>
     <td colspan="3" align="center" valign=bottom><strong style="font-size:13px;color:#263A5D;">Subscriptions Available under this Coupon</strong></td>
     <td valign=bottom>&nbsp;</td>
   </tr>
{foreach from=$COUPON_DETAILS.Subscriptions item=Subscription}
   <tr class=naGrid2>
     <td width="30%" align="right" valign=top><strong>{$Subscription.SubscriptionName}</strong></td>
     <td width="2%" valign=top>:</td>
     <td colspan="2" align="left">{$Subscription.deduction_amount}</td>
   </tr> 
{/foreach}
{/if}

{if $COUPON_DETAILS.Packages|@count > 0}
   <tr class=naGrid2>
     <td colspan="3" align="center" valign=bottom><strong style="font-size:13px;color:#263A5D;">Packages Available under this Coupon</strong></td>
     <td valign=bottom>&nbsp;</td>
   </tr>
{foreach from=$COUPON_DETAILS.Packages item=Package}
   <tr class=naGrid2>
     <td width="30%" align="right" valign=top><strong>{$Package.SubscriptionName}</strong></td>
     <td width="2%" valign=top>:</td>
     <td colspan="2" align="left">{$Package.deduction_amount}</td>
   </tr> 
{/foreach}
{/if}

<tr class=naGrid2>
      <td width="30%"  align="right" valign=top> <strong style="color:#660033;">Coupon Usage is Over?</strong> </td>
      <td width="2%" valign=top>:</td>
      <td colspan="2" align="left">{if $COUPON_DETAILS.available eq 'N'}Yes{else}No{/if}</td>
</tr>



     <tr height="50"> 
      <td colspan=4 valign=center><div align=center>&nbsp;</td> 
    </tr>  

	<tr class="naGridTitle"> 
      <td colspan=4 valign=center><div align=center>&nbsp;</td> 
    </tr>  
</table>
</td>
  </tr>
</table>