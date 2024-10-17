<table width="100%"  border="0">
  <tr>
    <td>
	<table width=80% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
 	<tr align="left">
      <td colspan=4 valign=top><table width="100%" align="center">
        <tr>
          <td width="23%" nowrap class="naH1">Coupon Details </td>
          <td width="77%" align="right" nowrap class="titleLink"><a href="{makeLink mod=extras pg=extras}act=list{/makeLink}">CouponList</a> </td>
        </tr>
      </table></td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=4></td>
    </tr>
    {/if}
    <tr class=naGrid2>
      <td  align="right" valign=top> Name </td>
      <td valign=top>:</td>
      <td colspan="2" align="left">{$COUPON_DETAILS.coupon_name}</td>
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=top>Number</td>
      <td valign=top>:</td>
      <td colspan="2" align="left">{$COUPON_DETAILS.coupon_no}</td>
    </tr>
    <tr class=naGrid2> 
      <td  align="right" valign=top> Start Date </td> 
      <td width=3 valign=top>:</td> 
      <td colspan="2" align="left">{$COUPON_DETAILS.coupon_start}    </td>
    </tr>
   <tr class=naGrid2>
     <td align="right" valign=top> Expiry Date </td>
     <td valign=top>:</td>
     <td colspan="2" align="left">{$COUPON_DETAILS.coupon_end}  </td>
   </tr>
   <tr class=naGrid2>
     <td align="right" valign=top>Amount Type  </td>
     <td valign=top>:</td>
     <td width="128" align="left">
	  {if $COUPON_DETAILS.coupon_amounttype=='A'}Amount
	  {elseif $COUPON_DETAILS.coupon_amounttype=='P'}Persantage
	  {else}Free Type {/if}	</td>
     <td width="166" align="left"><table width="100%"  border="0" cellpadding="0" cellspacing="0">
       <tr>
         <td width="46%">Amount</td>
         <td width="54%">{$COUPON_DETAILS.coupon_amount}</td>
       </tr>
     </table></td>
   </tr>
   <tr class=naGrid2>
     <td align="right" valign=top>Options</td>
     <td valign=top>:</td>
     <td align="left">
	 	{if $COUPON_DETAILS.coupon_options=='one'} One Time use
		{elseif $COUPON_DETAILS.coupon_options=='unlimit'} Unlimited use
		{else}Fixed{/if}	 </td>	   
     <td align="left">
	 {if $COUPON_DETAILS.coupon_options=='fixed'}
	 <table width="100%"  border="0" cellpadding="0" cellspacing="0">
       <tr>
         <td width="46%">No of Times</td>
         <td width="54%">{$COUPON_DETAILS.one_times}</td>
       </tr>
     </table>
	 {/if}	
	 </td>
   </tr>
   {if $COUPON_DETAILS.user_id!=0}
	   <tr class=naGrid2>
		 <td valign=top align="right"> Assigned User</td>
		 <td valign=top>:</td>
		 <td colspan="2" align="left"> <a class="linkOneActive" href="{makeLink mod=member pg=user}act=view&id={$COUPON_DETAILS.user_id}{/makeLink}"> {$USER_DETAILS.first_name}{$USER_DETAILS.last_name}</a></td>
	   </tr>
   {/if}
   <tr class=naGrid2>
     <td width="230" valign=top>&nbsp;</td>
     <td valign=top>&nbsp;</td>
     <td colspan="2" align="left">&nbsp;</td>
   </tr> 
    <tr class="naGridTitle"> 
      <td colspan=4 valign=center><div align=center>&nbsp;</td> 
    </tr>  
</table>
</td>
  </tr>
</table>