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
      <td valign=top colspan=3 ><strong>USPS Form </strong></td>
    </tr>
    <tr class=naGrid2>
      <td width="251" align="right" >User Name</td>
      <td valign=top>:</td>
      <td align="left"><input name="user_name" type="text" class="input" id="user_name" value="{$FORM_VALUES.user_name}" size="30"/>        <font color="#999999" size="-3">&nbsp; </font></td>
      </tr>
    <tr class=naGrid2> 
      <td  align="right" valign=top>Password</td> 
      <td width=7 valign=top>:</td> 
      <td align="left"><input name="password" type="text" class="input" id="password" value="{$FORM_VALUES.password}" size="30" /></td> 
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=top><span class="fieldname"> Box Type(Container Type)</span></td>
      <td valign=top><span class="fieldname">: </span></td>
      <td align="left">

<!-- <select name="uspsBoxType" class="Form2">
	<option value="Your Packaging" selected="selected">Your Packaging</option>
	<option value="Flat Rate Envelope">Flat Rate Envelope</option>
	<option value="Flat Rate Box">Flat Rate Box</option>
</select>	-->
<select name="uspsBoxType" class="Form2">
	{html_options values=$USPS_BOX_TYPES.dbvalue output=$USPS_BOX_TYPES.label selected=$FORM_VALUES.uspsBoxType }
</select>	  </td>
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=top><span class="fieldname">Package Type</span></td>
      <td valign=top><span class="fieldname">: </span></td>
      <td align="left">
<!--<select name="uspsPackageType" class="Form2">
	<option value="Regular" selected="selected">Regular</option>
	<option value="Large">Large</option>
	<option value="Oversize">Oversize</option>
</select>-->

<select name="uspsPackageType" class="Form2">
	{html_options values=$USPS_PACKAGE_TYPES.dbvalue output=$USPS_PACKAGE_TYPES.label selected=$FORM_VALUES.uspsPackageType }
</select>	  </td>
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=top> USPS Live Server Location </td>
      <td valign=top>&nbsp;</td>
      <td align="left"><input name="live_server" type="text" id="live_server" size="40" value="{$FORM_VALUES.live_server}">        <font color="#999999" size="-3"><br />Ex: http://stg-production.shippingapis.com/ShippingAPI.dll</font></td>
      </tr>
    <tr class=naGrid2>
      <td  align="right" valign=middle><span class="fieldname">Domestic Orders</span></td>
      <td valign=middle><span class="fieldname">: </span></td>
      <td align="left">

{foreach from=$USPS_DOMESTIC_SERVICES item=service}
	  <input class="Form2" type="checkbox" name="uspsDomSvcs[]" value="{$service.dbvalue}" {if $service.active eq 'Y'}checked{/if} />{$service.label}<br/>
{/foreach}	  
	  
<!--<input class="Form2" type="checkbox" name="uspsDomSvcs[]" value="1" />Express Mail<br/>
<input class="Form2" type="checkbox" name="uspsDomSvcs[]" value="2" />First-Class Mail<br/>
<input class="Form2" type="checkbox" name="uspsDomSvcs[]" value="3"  />Priority Mail<br/>
<input class="Form2" type="checkbox" name="uspsDomSvcs[]" value="4" />Parcel Post<br/>
<input class="Form2" type="checkbox" name="uspsDomSvcs[]" value="5" />Bound Printed Matter<br/>
<input class="Form2" type="checkbox" name="uspsDomSvcs[]" value="6" />Media Mail<br/>
<input class="Form2" type="checkbox" name="uspsDomSvcs[]" value="7" />Library Mail	  -->	  </td>
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=top><span class="fieldname">International Orders</span></td>
      <td valign=top><span class="fieldname">: </span></td>
      <td align="left">

{foreach from=$USPS_INTNL_SERVICES item=service}
	<input class="Form2" type="checkbox" name="uspsIntlSvcs[]" value="{$service.dbvalue}" {if $service.active eq 'Y'}checked{/if} />{$service.label}<br/>
{/foreach}

	  
	  
<!--<input class="Form2" type="checkbox" name="uspsIntlSvcs[]" value="1" />Express Mail International<br/>
<input class="Form2" type="checkbox" name="uspsIntlSvcs[]" value="2" />Priority Mail International<br/>
<input class="Form2" type="checkbox" name="uspsIntlSvcs[]" value="3" />First-Class Mail International<br/>
<input class="Form2" type="checkbox" name="uspsIntlSvcs[]" value="4" />Global Express Guaranteed<br/>-->	  </td>
    </tr>
    
   <tr class=naGrid2>
     <td colspan="3" valign=top>	 </td>
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