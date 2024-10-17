<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/scripts/validator.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
	var fields=new Array('coupon_start');
	var msgs=new Array('Start Date');
</SCRIPT>
<script language="javascript">
{literal}
function display(theSelect){
		var timesChoice= theSelect;		
		switch(timesChoice) {
			case "fixed" :
				document.getElementById('hidetimes').style.display="inline";
			break;
			case "one" :
				document.getElementById('hidetimes').style.display="none";
			break;
			case "unlimit" :
				document.getElementById('hidetimes').style.display="none";
			break;
		}	
}
function displayAmount(theSelect){
		var timesChoice= theSelect;		
		switch(timesChoice) {
			case "F" :
				document.getElementById('coupon_amount').style.display="none";
			break;
			case "A" :
				document.getElementById('coupon_amount').style.display="inline";
			break;
			case "P" :
				document.getElementById('coupon_amount').style.display="inline";
			break;
		}	
}
{/literal}
</script>
<table width="100%"  border="0">
  <tr>
    <td><table width=80% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
  <form action="" method="post" enctype="multipart/form-data" name="frm" onSubmit="return chk(this);">
    <tr align="left">
      <td colspan=4 valign=top><table width="100%" align="center">
        <tr>
          <td width="23%" nowrap class="naH1">Coupon Details </td>
          <td width="77%" align="right" nowrap class="titleLink"><a href="{makeLink mod=extras pg=extras}act=list{/makeLink}">CouponList</a></td>
        </tr>
      </table></td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=4><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>
      </td>
    </tr>
    {/if}
    <tr class=naGrid2>
      <td  align="right" valign=top>Name </td>
      <td valign=top>:</td>
      <td colspan="2" align="left"><input name="coupon_name" type="text" class="input" id="coupon_name" value="{$COUPON.coupon_name}" size="30"/></td>
    </tr>
    <tr class=naGrid2> 
      <td  align="right" valign=top> Start Date </td> 
      <td width=3 valign=top>:</td> 
      <td colspan="2" align="left"><input name="coupon_start" type="text" class="input" id="coupon_start" value="{$COUPON.coupon_start}" size="30" onFocus="popUpCalendar(this, this, 'yyyy-mm-dd', 0, 0)" readonly/></td> 
    </tr>
   <tr class=naGrid2>
     <td align="right" valign=top> Expiry Date </td>
     <td valign=top>:</td>
     <td colspan="2" align="left"><input name="coupon_end" type="text" class="input" id="coupon_end" value="{$COUPON.coupon_end}" size="30" onFocus="popUpCalendar(this, this, 'yyyy-mm-dd', 0, 0)" readonly/></td>
   </tr>
   <tr class=naGrid2>
     <td align="right" valign=top>Amount Type  </td>
     <td valign=top>:</td>
     <td width="180" align="left">
	 <select name="coupon_amounttype" onChange="return displayAmount(this.value)">
		<option value="F" {if $COUPON.coupon_amounttype=='F'} selected{/if}>Free Shipping</option>
		<option value="A" {if $COUPON.coupon_amounttype=='A'} selected{/if}>Amount</option>
		<option value="P" {if $COUPON.coupon_amounttype=='P'} selected{/if}>Percentage</option>
     </select>
	 </td>
     <td  align="left">
	{if $COUPON.coupon_amounttype!='F'}
		<div id="coupon_amount" style="display:inline">
	{else}
		<div id="coupon_amount" style="display:none">
	{/if}	
	 <table width="100%"  border="0" cellpadding="0" cellspacing="0">
       <tr>
         <td width="25%">Amount</td>
         <td width="75%"><input type="text" name="coupon_amount" class="input" size="30" value="{$COUPON.coupon_amount}"></td>
       </tr>
     </table>
	 </div> 
	 </td>
     </tr>
   <tr class=naGrid2>
     <td align="right" valign=top>Options</td>
     <td valign=top>:</td>
     <td align="left">
	 <select name="coupon_options" onChange="return display(this.value)">
       <option value="one" {if $COUPON.coupon_options=='one'} selected{/if}>One Time</option>
       <option value="fixed"{if $COUPON.coupon_options=='fixed'} selected{/if}>Fixed</option>
	   <option value="unlimit"{if $COUPON.coupon_options=='unlimit'} selected{/if}>Unlimit</option>
     </select>
	 </td>
     <td align="left">
	{if $COUPON.coupon_options=='fixed'}
		<div id="hidetimes" style="display:inline">
	{else}
		<div id="hidetimes" style="display:none">
	{/if}	
	 <table width="100%"  border="0" cellpadding="0" cellspacing="0">
       <tr>
         <td width="47%">No of Times</td>
         <td width="53%"><input name="one_times" type="text" class="input" id="one_times"  value="{$COUPON.one_times}" size="2"/></td>
       </tr>
     </table>
	 </div>
	 </td>
     </tr>
   <tr class=naGrid2>
     <td width="230" valign=top>&nbsp;</td>
     <td valign=top>&nbsp;</td>
     <td colspan="2" align="left">&nbsp;</td>
   </tr> 
    <tr class="naGridTitle"> 
      <td colspan=4 valign=center><div align=center>	  
	       <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div>
	 </td> 
    </tr> 
  </form> 
</table>
</td>
  </tr>
</table>