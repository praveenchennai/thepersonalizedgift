{if $GLOBAL.subfrom_subscriptions eq 'Y' && $GLOBAL.subfrom_total eq 'N' && $GLOBAL.subfrom_produstsandoptions eq 'N' && $GLOBAL.subfrom_options eq 'N' && $GLOBAL.subfrom_products eq 'N'}
	{assign var="onlysubscription" value="Yes"}
{else}
	{assign var="onlysubscription" value="No"}
{/if}

<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/includes/datepicker/calendar_version1.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/scripts/validator.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
	//var fields=new Array('coupon_no','coupon_start');
	//var msgs=new Array('Coupon Key','Start Date');
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
function displayAmount(theSelect, SubtractFrom){
	var timesChoice		=	theSelect;
	
	if({/literal}'{$onlysubscription}'{literal} == 'No' && SubtractFrom == '') {
		$SubtractFromObj	=	document.frm.substract_from;
		for(i=0; i<$SubtractFromObj.length; i++)
			$SubtractFromObj[i].checked	=	false;
	}
	
	if(SubtractFrom == 'S' && theSelect != 'F') {
		document.getElementById('subscriptionblock').style.display="inline";
	}
	
	if({/literal}'{$onlysubscription}'{literal} == 'Yes' && timesChoice != 'F') {
		document.getElementById('subscriptionblock').style.display="inline";
	}
	
	switch(timesChoice) {
		case "F" :
			document.getElementById('coupon_amount').style.display="none";
			document.getElementById('subscriptionblock').style.display="none";
			break;
			
		case "A" :
			document.getElementById('coupon_amount').style.display="inline";
			break;
			
		case "P" :
			document.getElementById('coupon_amount').style.display="inline";
			break;
	}	
}

function showSubscriptions(DeductedFrom)
{
	if(DeductedFrom == 'S') {
		document.getElementById('amountblock').style.display="none";
		document.getElementById('subscriptionblock').style.display="inline";
	} else {
		document.getElementById('amountblock').style.display="inline";
		document.getElementById('subscriptionblock').style.display="none";
	}	
}

function IsNumeric(sText)
{
	var ValidChars = "0123456789.";
	var IsNumber=true;
	var Char;
	for (i = 0; i < sText.length && IsNumber == true; i++) { 
		Char = sText.charAt(i); 
		if(ValidChars.indexOf(Char) == -1) {
			IsNumber = false;
		}
	}
	return IsNumber;
}


function validateForm()
{
	var Subscriptions   = document.getElementsByName('SelSubscriptions[]');
	var Packages 		= document.getElementsByName('SelPackage[]');

	for(var i=0; i<Subscriptions.length; i++) {
		if(Subscriptions[i].checked == true) {
			amountfield		=	'deductionamount_' + Subscriptions[i].value;
			FieldAmount		=	document.getElementById(amountfield).value;
			
			if(FieldAmount == '' || FieldAmount == 0) {
				alert('Amount Required');
				document.getElementById(amountfield).focus();
				return false;
			}
			
			if(FieldAmount != '') {
				if(!IsNumeric(FieldAmount)) {
					alert('Numeric Value Required');
					document.getElementById(amountfield).value = '';
					document.getElementById(amountfield).focus();
					return false;
				}
			}
		}
	}
	
	for(var i=0; i<Packages.length; i++) {
		if(Packages[i].checked == true) {
			amountfield		=	'packagedeductionamount_' + Packages[i].value;
			FieldAmount		=	document.getElementById(amountfield).value;
			
			if(FieldAmount == '' || FieldAmount == 0) {
				alert('Amount Required');
				document.getElementById(amountfield).focus();
				return false;
			}
			
			if(FieldAmount != '') {
				if(!IsNumeric(FieldAmount)) {
					alert('Numeric Value Required');
					document.getElementById(amountfield).value = '';
					document.getElementById(amountfield).focus();
					return false;
				}
			}
		}
	}
	
	return true;
}

{/literal}
</script>
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form action="" method="post" enctype="multipart/form-data" name="frm" >
{if $onlysubscription eq 'Yes'}
	<input type="hidden" name="substract_from" value="S" />
{/if}
<table width="100%"  border="0">
  <tr>
    <td valign="top"><table width=80% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
   <tr align="left">
      <td colspan=4 valign=top><table width="100%" align="center">
        <tr>
          <td width="18%" nowrap class="naH1">{$SUBNAME} </td>
          <td width="82%" align="right" nowrap class="titleLink"><a href="{makeLink mod=extras pg=extras}act=list{/makeLink}&sId={$SUBNAME}&mId={$MID}">{$SUBNAME} List</a></td>
        </tr>
      </table></td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=4><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>      </td>
    </tr>
    {/if}
    <tr class=naGrid2>
      <td width="30%"  align="right" valign=top>Name </td>
      <td width="2%" valign=top>:</td>
      <td colspan="2" align="left"><input name="coupon_name" type="text" class="input" id="coupon_name" value="{$COUPON.coupon_name}" size="30"/></td>
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=top>Coupon Key </td>
      <td valign=top>:</td>
      <td colspan="2" align="left"><input name="coupon_no" type="text" class="input" id="coupon_no" value="{$COUPON.coupon_no}" size="30"/> 
        (This will uniquely identify the Coupon)</td>
    </tr>
    <tr class=naGrid2> 
      <td  align="right" valign=top> Start Date </td> 
      <td width=2% valign=top>:</td> 
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
     <td width="117" align="left">
<select name="coupon_amounttype" id="coupon_amounttype" onChange="return displayAmount(this.value,'')">
	{if $GLOBAL.amttype_freeshipping eq 'Y'}<option value="F" {if $COUPON.coupon_amounttype=='F'} selected{/if}>Free Shipping</option>{/if}
	{if $GLOBAL.amttype_showamount eq 'Y'}<option value="A" {if $COUPON.coupon_amounttype=='A'} selected{/if}>Amount</option>{/if}
	{if $GLOBAL.amttype_showpercentage eq 'Y'}<option value="P" {if $COUPON.coupon_amounttype=='P'} selected{/if}>Percentage</option>{/if}
</select>	 
	</td>
     <td width="489"  align="left">&nbsp;</td>
     </tr>
   <tr class=naGrid2 height="1">
     <td colspan="4" align="right" valign=top>
	 
	 

<div id="coupon_amount" style="display:{if $COUPON.coupon_amounttype != 'F'}inline{else}none{/if}">
 <table width="100%" border="0" cellpadding="0" cellspacing="0">
   <tr>
     <td colspan="3" align="left">
	 <div id="amountblock" style="height:30px;display:{if $COUPON.substract_from eq 'S' || $COUPON.substract_from eq '' }none{else}inline{/if};">
	   <table width="100%" border="0" cellpadding="0" cellspacing="0">
		 <tr>
		   <td width="30%" height="30" align="right">Amount&nbsp;&nbsp;</td>
		   <td width="2%" height="30" align="center">:</td>
		   <td width="73%" height="30">&nbsp;
			 <input type="text" name="coupon_amount" class="input" size="10" value="{$COUPON.coupon_amount}"></td>
		 </tr>
	   </table>
	 </div>
	 </td>
     </tr>

{if $onlysubscription neq 'Yes'}
   <tr>
	 <td width="30%" height="30" align="right"> Deducted From&nbsp; </td>
	 <td width="2%" align="center">:</td>
	 <td height="30">&nbsp;
		{if $GLOBAL.subfrom_total eq 'Y'}<label><input type="radio" name="substract_from" value="T" {if $COUPON.substract_from eq 'T'}checked="checked"{/if} onclick="showSubscriptions(this.value);" />Total &nbsp;&nbsp;</label>{/if}
		{if $GLOBAL.subfrom_produstsandoptions eq 'Y'}<label><input type="radio" name="substract_from" value="P"  {if $COUPON.substract_from eq 'P'}checked="checked"{/if} onclick="showSubscriptions(this.value);" />Product & Options&nbsp;&nbsp;</label>{/if}
		{if $GLOBAL.subfrom_options eq 'Y'}<label><input type="radio" name="substract_from" value="O" {if $COUPON.substract_from eq 'O'}checked="checked"{/if} onclick="showSubscriptions(this.value);" />Options &nbsp;&nbsp;</label>{/if}
		{if $GLOBAL.subfrom_products eq 'Y'}<label><input type="radio" name="substract_from" value="M"  {if $COUPON.substract_from eq 'M'}checked="checked"{/if} onclick="showSubscriptions(this.value);" />Products&nbsp;&nbsp;</label>{/if}
		{if $GLOBAL.subfrom_subscriptions eq 'Y'}<label><input type="radio" name="substract_from" value="S"  {if $COUPON.substract_from eq 'S'}checked="checked"{/if} onclick="showSubscriptions(this.value);" />Subscriptions</label>{/if}	 </td>
   </tr>
{/if}
 </table>
</div>	 </td>
     </tr>
   <tr class=naGrid2 height="1">
     <td colspan="4" align="left" valign=top>


<div id="subscriptionblock" style="display:{if $onlysubscription eq 'Yes'}inline{else}none{/if};">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr >
		<td width="30%" height="30" align="right" valign="top" >Deducted Subscription Packages<br />
			<font color="#666666" style="font-size:10px;font-family:Arial, Helvetica, sans-serif;">[Select subscription packages]</font>
		</td>
	  <td width="2%" height="30" align="center" valign="top" >:</td>
	  <td height="30">
{foreach name=foo key=key item=Subscription from=$SUBSCRIPTIONS}	
	<div style="float:left;width:40%;height:30px;"><label><input type="checkbox" name="SelSubscriptions[]" value="{$Subscription.id}" {if $Subscription.CouponPackageId neq ''}checked{/if} />&nbsp;{$Subscription.name}</label></div><div style="float:left;width:40%;">Deduction Amount&nbsp;:&nbsp;<input name="deductionamount_{$Subscription.id}" id="deductionamount_{$Subscription.id}" type="text" value="{$Subscription.deduction_amount}" size="7" />
	</div><div style="clear:both;"></div>
{/foreach}
	  </td>
	</tr>
  </table>
</div>

<div id="subscriptionblock" style="display:{if $onlysubscription eq 'Yes'}inline{else}none{/if};">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr >
		<td width="30%" height="30" align="right" valign="top" >Deducted Registration Packages<br />
			<font color="#666666" style="font-size:10px;font-family:Arial, Helvetica, sans-serif;">[Select Registration packages]</font>
		</td>
	  <td width="2%" height="30" align="center" valign="top" >:</td>
	  <td height="30">
{foreach name=foo key=key item=Package from=$PACKAGES}	
	<div style="float:left;width:40%;height:30px;"><label><input type="checkbox" name="SelPackage[]" value="{$Package.id}" {if $Package.CouponPackageId neq ''}checked{/if} />&nbsp;{$Package.name}</label></div><div style="float:left;width:40%;">Deduction Amount&nbsp;:&nbsp;<input name="packagedeductionamount_{$Package.id}" id="packagedeductionamount_{$Package.id}" type="text" value="{$Package.deduction_amount}" size="7" />
	</div><div style="clear:both;"></div>
{/foreach}
	  </td>
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
	   <option value="unlimit"{if $COUPON.coupon_options=='unlimit'} selected{/if}>Unlimited</option>
     </select>	 </td>
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
	 </div>	 </td>
     </tr>
   <tr class=naGrid2>
     <td width="20%" valign=top>&nbsp;</td>
     <td valign=top>&nbsp;</td>
     <td colspan="2" align="left">&nbsp;</td>
   </tr> 
    <tr class="naGridTitle"> 
      <td colspan=4 valign=center><div align=center>	  
	       <input type=submit value="Submit" class="naBtn" onclick="return validateForm();">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div>	 </td> 
    </tr> 
</table>
</td>
  </tr>
</table>
</form> 
<script language="javascript" type="text/javascript">
	var	coupon_amounttype	=	document.getElementById('coupon_amounttype').value;
	
	if(coupon_amounttype != 'F')
		document.getElementById('coupon_amount').style.display	=	'inline';
	else
		document.getElementById('coupon_amount').style.display	=	'none';	
	
	displayAmount(document.frm.coupon_amounttype.value, '{$COUPON.substract_from}');
</script>