<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/scripts/validator.js"></SCRIPT>
{literal}
<script language="javascript">
function showPopup(static) {
window.open( "https://www.paypal.com/cgi-bin/webscr?cmd=p/sell/mc/mc_wa-outside", "", 
"status = 1, height = 400,scrollbars=1, width = 600,left=300,top=190, resizable = 0" )

}
</script>
{/literal}
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
    <tr align="left">
      <td colspan=3 valign=top><table width="400%" align="center">
        <tr>
          <td width="23%" nowrap class="naH1">Add Currency</td>
          <td width="77%" align="right" nowrap class="titleLink">&nbsp;<a href="{makeLink mod=home pg=paymentType}act=list_currency{/makeLink}&store_id={$smarty.request.store_id}&sId=Currency List&fId={$smarty.request.fId}&mId={$MID}">Currency List</a></td>
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
      <td class="naGridTitle" height="25" nowrap="nowrap" colspan=3 ><strong>Add Currency</strong></td>
    </tr>
    <tr valign="middle" class=naGrid2>
      <td width="308" height="25"  align="right"><span class="fieldname">Currency Name:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	  <input type="text" name="currency_name"  size="30" value="{$smarty.request.currency_name}"/>
        
      </span></td>
    </tr>
	
	 <tr valign="middle" class=naGrid1>
      <td width="308" height="25"  align="right"><span class="fieldname">Dispaly Text:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	<input type="text" name="currency_shorttxt"   size="30" value="{$smarty.request.currency_shorttxt}" />
        
      </span></td>
    </tr>
	
	<tr valign="middle" class=naGrid2>
      <td width="308" height="25"  align="right"><span class="fieldname">Symbol:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input type="text" name="symbol"  size="30" value="{$smarty.request.symbol}" />
        
      </span></td>
    </tr>
	
	<tr valign="middle" class=naGrid1>
      <td width="308" height="25"  align="right"><span class="fieldname">Paypal Currency Code:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left"><span class="formfield">
	 <input type="text" name="currency_code" id="currency_code"  value="{$smarty.request.currency_code}"  size="30" />
        
      </span> <a href="#"  onclick="showPopup();">Paypal Currency Code</a></td>
    </tr>
	<tr valign="middle" class=naGrid2>
      <td width="308" height="25"  align="right"><span class="fieldname">Active:</span></td>
      <td width="3" height="25">&nbsp;</td>
      <td width="442" height="25" align="left" valign="middle"><span class="formfield">
	<input type="radio" name="active" value="Y" {if $smarty.request.active eq 'Y'} checked="checked" {/if} />Yes 
	<input type="radio" name="active" value="N"  {if $smarty.request.active eq 'N' or $smarty.request.active eq '' } checked="checked" {/if} />No
        
      </span></td>
    </tr>
    
   
    <tr class="naGridTitle" height="25"> 
      <td colspan=3 valign=center ><div align=center>	  
	       <input type=submit name="submit" value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div>	 </td> 
    </tr> 
	<tr><td colspan=3 valign=center>&nbsp;</td></tr> 
  </form> 
</table>
</td>
  </tr>
</table>