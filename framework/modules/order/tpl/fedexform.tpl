<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/scripts/validator.js"></SCRIPT>
<table width="820" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table width="100%"  border="0">
  <tr>
    <td valign="top">
<table width=80% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
<form action="" method="post" enctype="multipart/form-data" name="frm">
  	<input type="hidden" name="act" value="{$smarty.request.act}">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
	<input type="hidden" name="mId" value="{$smarty.request.mId}">
	<input type="hidden" name="store_id" value="{$smarty.request.store_id}">
	<input type="hidden" name="shipmethod_id" value="{$smarty.request.shipmethod_id}">
    <tr align="left">
      <td colspan=3 valign=top><table width="400%" align="center">
        <tr>
          <td width="23%" nowrap class="naH1">{$smarty.request.sId} </td>
          <td width="77%" align="right" nowrap class="titleLink">&nbsp;<!--<a href="{makeLink mod=order pg=order}act=shippingselmethodslist{/makeLink}&store_id={$smarty.request.store_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}">{$smarty.request.sId} List</a>--></td>
        </tr>
      </table></td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3>
		<div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>      </td>
    </tr>
    {/if}
	<tr class=naGrid2>
      <td valign=top colspan=3 >USPS Form </td>
    </tr>
    <tr class=naGrid2>
      <td align="right" >Live Fedex Server </td>
      <td valign=top>&nbsp;</td>
      <td align="left"><input name="fedex_server" type="text" class="input" id="fedex_server" value="{$FORM_VALUES.fedex_server}" size="40"/><br />
        &nbsp;<font color="#999999">Ex: https://gatewaybeta.fedex.com:443/GatewayDC</font></td>
    </tr>
    <tr class=naGrid2>
      <td align="right" >Account Number </td>
      <td valign=top>:</td>
      <td width="536" align="left"><input name="account_number" type="text" class="input" id="account_number" value="{$FORM_VALUES.account_number}" size="30"/></td>
      </tr>
    <tr class=naGrid2> 
      <td  align="right" valign=top>Meter</td> 
      <td width=6 valign=top>:</td> 
      <td align="left"><input name="meter" type="text" class="input" id="meter" value="{$FORM_VALUES.meter}" size="30" /></td> 
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=top>Dropoff Type</td>
      <td valign=top>&nbsp;</td>
      <td align="left">
<select name="fedexDropofftype" class="Form2">
	{html_options values=$FEDEX_DROPOFF_TYPE.dbvalue output=$FEDEX_DROPOFF_TYPE.label selected=$FORM_VALUES.fedexDropofftype}
</select>	  </td>
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=top>Package Type</td>
      <td valign=top>:</td>
      <td align="left">


<select name="fedexPackaging" class="Form2">
	{html_options values=$FEDEX_PACKAGE_TYPE.dbvalue output=$FEDEX_PACKAGE_TYPE.label selected=$FORM_VALUES.fedexPackaging}
</select>	  </td>
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=top><span class="fieldname"> Address</span></td>
      <td valign=top><span class="fieldname">: </span></td>
      <td align="left"><span class="formfield">
        <input class="Form2" type="text" name="fedexAddress" size="30" maxlength="30" value="{$FORM_VALUES.fedexAddress}" />
      </span></td>
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=top><span class="fieldname">City</span></td>
      <td valign=top><span class="fieldname">:</span></td>
      <td align="left"><span class="formfield">
        <input class="Form2" type="text" name="fedexCity" size="25" value="{$FORM_VALUES.fedexCity}" />
      </span></td>
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=top><span class="fieldname">State</span></td>
      <td valign=top><span class="fieldname">:</span></td>
      <td align="left"><span class="formfield">
        <input class="Form2" type="text" name="fedexState" size="2" maxlength="2" value="{$FORM_VALUES.fedexState}" />
      </span></td>
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=top><span class="fieldname"> Zip/Postal Code</span></td>
      <td valign=top><span class="fieldname">: </span></td>
      <td align="left"><span class="formfield">
        <input class="Form2" type="text" name="fedexZip" size="10" maxlength="7" value="{$FORM_VALUES.fedexZip}" />
      </span></td>
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=top><span class="fieldname">Country</span></td>
      <td valign=top><span class="fieldname">: </span></td>
      <td align="left"><span class="formfield">

<select class="Form2" name="fedexCountry">
	{html_options values=$COUNTRIES.dbvalue output=$COUNTRIES.label selected=$FORM_VALUES.fedexCountry}
</select>

      </span></td>
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=top><span class="fieldname">Phone</span></td>
      <td valign=top>:</td>
      <td align="left"><span class="formfield">
        <input class="Form2" type="text" name="fedexPhone" size="15" maxlength="15" value="{$FORM_VALUES.fedexPhone}" />
      </span></td>
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=middle><span class="fieldname"> Ship Service</span></td>
      <td valign=middle><span class="fieldname">: </span></td>
      <td align="left">
{foreach from=$FEDEX_SHIPPING_SERVICES item=service}
	<input class="Form2" type="checkbox" name="services[]" value="{$service.dbvalue}" {if $service.active eq 'Y'}checked{/if} />{$service.label}<br />
{/foreach}	  </td>
    </tr>
    
   <tr class=naGrid2>
     <td colspan="3" valign=top>&nbsp;	 </td>
     </tr> 
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center>	  
	       <input type=submit name="Submit" value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div>	 </td> 
    </tr> 
  </form> 
</table>
</td>
  </tr>
</table>