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
	<input type="hidden" name="mod" value="{$smarty.request.mod}" />
	<input type="hidden" name="pg" value="{$smarty.request.pg}" />
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
	<tr valign="middle" class=naGrid2>
      <td height="40" colspan=3 ><strong>Google Checkout  Form </strong></td>
    </tr>
    <tr valign="middle" class=naGrid2>
      <td height="30"  align="right" valign="bottom">
Store Name </td>
      <td height="30"  align="center" valign="bottom">:</td>
      <td height="30"  align="left" valign="bottom">
		<select name=store_id onchange="window.location.href='{makeLink mod=$MOD pg=$PG}act=googlecheckoutform&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}&store_id='+this.value">
			{html_options values=$STORES.id output=$STORES.name selected=`$SELECTED_STORE_ID`}
		</select>
	  </td>
    </tr>
    <tr valign="middle" class=naGrid2>
      <td width="308" height="40"  align="right"><span class="fieldname">Enter Merchant ID</span></td>
      <td width="3" height="40" align="center">:</td>
      <td width="442" height="40" align="left"><span class="formfield">
        <input name="merchant_id" type="text" class="Form2" id="merchant_id" value="{$FORM_VALUES.merchant_id}" size="30" />
      </span></td>
    </tr>
	 <tr valign="middle" class=naGrid2>
      <td width="308" height="40"  align="right"><span class="fieldname">Enter Merchant KEY</span></td>
      <td width="3" height="40" align="center">:</td>
      <td width="442" height="40" align="left"><span class="formfield">
        <input name="merchant_key" type="text" class="Form2" id="merchant_key" value="{$FORM_VALUES1.merchant_key}" size="30" />
      </span></td>
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
