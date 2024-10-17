<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/scripts/validator.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
	var fields=new Array('coupon_no','coupon_start');
	var msgs=new Array('Coupon Key','Start Date');
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
				document.getElementById('certi_amount').style.display="none";
			break;
			case "A" :
				document.getElementById('certi_amount').style.display="inline";
			break;
			case "P" :
				document.getElementById('certi_amount').style.display="inline";
			break;
		}	
}
{/literal}
</script>
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table width="100%"  border="0">
  <tr>
    <td valign="top"><table width=80% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
  <form action="" method="post" enctype="multipart/form-data" name="frm" onSubmit="return chk(this);">
    <tr align="left">
      <td colspan=4 valign=top><table width="100%" align="center">
        <tr>
          <td width="23%" nowrap class="naH1">{$SUBNAME} </td>
          <td width="77%" align="right" nowrap class="titleLink"><a href="{makeLink mod=extras pg=certificate}act=viewusercertificate{/makeLink}">Certificate List</a></td>
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
	{if !$CERTIFICATE_DETAILS.certi_number}
	<tr class=naGrid2>
      <td  align="right" valign=top>Gift Certificate</td>
      <td valign=top>&nbsp;</td>
      <td colspan="2" align="left">
	  	<select name=product_id onchange="window.location.href='{makeLink mod=extras pg=certificate}act=form{/makeLink}&cert_prod_id='+this.value" class="input">
			<option value="">-- SELECT GIFT CERTIFICATE --</option>
			{if $CERTIFICATE_NAME}
				{html_options values=$LOAD_PRODUCT.id output=$LOAD_PRODUCT.name selected=`$CERTIFICATE_NAME`}
			{else}
				{html_options values=$LOAD_PRODUCT.id output=$LOAD_PRODUCT.name selected=`$smarty.request.product_id`}
			{/if}
		 </select>
	  </td>
    </tr>
	{/if}
    <tr class=naGrid2>
      <td  align="right" valign=top>Number</td>
      <td valign=top>&nbsp;</td>
      <td colspan="2" align="left"><input name="certi_number" type="text" class="input" id="certi_number" value="{$CERTIFICATE_DETAILS.certi_number}" size="30"/> 
        (This will uniquely identify the Number)</td>
    </tr>
    <tr class=naGrid2> 
      <td  align="right" valign=top> Start Date </td> 
      <td width=3 valign=top>:</td> 
      <td colspan="2" align="left">
	  	<input name="certi_startdate" type="text" class="input" id="certi_startdate" value="{if $CERTIFICATE_START_DATE}{$CERTIFICATE_START_DATE}{else}{$CERTIFICATE_DETAILS.certi_startdate}{/if}" size="30" onFocus="popUpCalendar(this, this, 'yyyy-mm-dd', 0, 0)" readonly/>
	  </td>
    </tr>
   <tr class=naGrid2>
     <td align="right" valign=top> Expiry Date </td>
     <td valign=top>:</td>
     <td colspan="2" align="left"><input name="certi_enddate" type="text" class="input" id="certi_enddate" value="{$CERTIFICATE_DETAILS.certi_enddate}" size="30" onFocus="popUpCalendar(this, this, 'yyyy-mm-dd', 0, 0)" readonly/></td>
   </tr>
   <tr class=naGrid2>
     <td align="right" valign=top>Amount</td>
     <td valign=top>:</td>
     <td width="180" align="left">
		 <input type="text" name="certi_amount" class="input" size="10" value="{if $CERTIFICATE_PRICE}{$CERTIFICATE_PRICE}{else}{$CERTIFICATE_DETAILS.certi_amount}{/if}">
	 </td>
     <td  align="left">
	 <table width="100%"  border="0" cellpadding="0" cellspacing="0">
       <tr>
         <td width="25%">&nbsp;</td>
         <td width="75%">&nbsp;</td>
       </tr>
     </table>
	 </td>
     </tr>
   <tr class=naGrid2>
     <td align="right" valign=top>Options</td>
     <td valign=top>:</td>
     <td align="left">
	 <select name="coupon_options" onChange="return display(this.value)">
       <option value="one" {if $CERTIFICATE_OPTION}{if $CERTIFICATE_OPTION=='one'} selected{/if}{else}{if $CERTIFICATE_DETAILS.type_option=='one'} selected{/if}{/if}>One Time</option>
       <option value="fixed" {if $CERTIFICATE_OPTION}{if $CERTIFICATE_OPTION=='fixed'} selected{/if}{else}{if $CERTIFICATE_DETAILS.type_option=='fixed'} selected{/if}{/if}>Fixed</option>
	   <option value="unlimit" {if $CERTIFICATE_OPTION}{if $CERTIFICATE_OPTION=='unlimit'} selected{/if}{else}{if $CERTIFICATE_DETAILS.type_option=='unlimit'} selected{/if}{/if}>Unlimit</option>
     </select>
	 </td>
     <td align="left">
	{if $CERTIFICATE_DETAILS.type_option=='fixed' or $CERTIFICATE_OPTION=='fixed'}
		<div id="hidetimes" style="display:inline">
	{else}
		<div id="hidetimes" style="display:none">
	{/if}	
	 <table border="0" cellpadding="0" cellspacing="0">
       <tr>
         <td width="60%">No of Times</td>
         <td width="22%"><input name="one_times" type="text" class="input" id="one_times"  value="{if $CERTIFICATE_TIMES}{$CERTIFICATE_TIMES}{else}{$CERTIFICATE_DETAILS.no_times}{/if}" size="2"/></td>
       </tr>
     </table>
	 </div>
	 </td>
     </tr>
	 <tr class=naGrid2>
	 	<td align="right" valign=top>Order Number</td>
		 <td valign=top>:</td>
		 {if $CERTIFICATE_DETAILS.order_number}
		 <td align="left"><a href="{makeLink mod=order pg=order}act=details&id={$CERTIFICATE_DETAILS.order_id}{/makeLink}">{$CERTIFICATE_DETAILS.order_number}</a></td>
		 <td align="left">(Click to view Order)</td>
		 {else}
		 <td align="left">Nil</td>
		 <td align="left">&nbsp;</td>
		 {/if}
	</tr>
   <tr class=naGrid2>
     <td width="230" valign=top>&nbsp;</td>
     <td valign=top>&nbsp;</td>
     <td colspan="2" align="left">&nbsp;</td>
   </tr> 
    <tr class="naGridTitle"> 
      <td colspan=4 valign=center><div align=center>	  
	       <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input name="Button" type=button class="naBtn" value="Cancel" onClick="javascript:history.go(-1)"> 
		  <!-- <input type=reset value="Reset" class="naBtn"> --> 
        </div>
	 </td> 
    </tr> 
  </form> 
</table>
</td>
  </tr>
</table>