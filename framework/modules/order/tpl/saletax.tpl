<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/scripts/validator.js"></SCRIPT>
{literal}
<script language="javascript">
function showBox(val)
{
  if(val==3){
  	document.getElementById('custom_div').style.display='block';
  }
  else if(val=2 || val==1){
  	document.getElementById('custom_div').style.display='none';
  }
}
</script>
{/literal}
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
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
      <td colspan=3 valign=top><table width="400%" align="center" border="0">
        <tr>
          <td width="23%" nowrap class="naH1">{$smarty.request.sId} </td>
          <td width="77%" align="right" nowrap class="titleLink">&nbsp;</td>
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
      <!--<td height="40" colspan=3 ><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="3%" valign="top" colspan="2" class="naGridTitle" height="25" nowrap="nowrap"><strong>Please read the following carefully.</strong></td>
          </tr>
        </table>        </td>-->
		<td colspan=3 class="naGridTitle" height="25" nowrap="nowrap"><strong>Please read the following carefully.</strong></td>
		
    </tr>
	
	
	<tr valign="middle" class=naGrid1>
      <td height="40" colspan=3 ><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="3%" valign="top">1.</td>
            <td width="97%">You may be required by law to collect and pay sales tax. (Please consult with your CPA, accountant or tax authority as needed).</td>
          </tr>
        </table>        </td>
    </tr>
	<tr valign="middle" class=naGrid2>
      <td height="40" colspan=3 ><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="3%" valign="top">2.</td>
            <td width="97%"> Your PayPal account provides a feature which will allow you to add sales tax to your customers invoice before payment is made.(Contact PayPal for more information and further assistance as needed).</td>
          </tr>
        </table> </td>
    </tr>
	<tr valign="middle" class=naGrid1>
      <td height="40" colspan=3> <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="3%" valign="top">3.</td>
            <td width="97%">Your web-store is not designed to calculate or include sales tax. You can however display any one of the following sales tax notices in your shopping cart.</td>
          </tr>
        </table> </td>
    </tr>
	<tr class=naGrid2>
		<td colspan="2">&nbsp;</td>
		
		<td>&nbsp;</td>
	</tr>
  {foreach from=$TAX_MSG item=row}  
 <tr valign="middle" class="{cycle name=bg values="naGrid1,naGrid2"}">
      <td width="114" height="40"  align="right" colspan="2"><span class="fieldname">
        <input name="saletax_opt" type="radio" value="{$row.taxid}" onclick="showBox(this.value)" {if $smarty.request.taxid eq $row.taxid or  ( (!$TAX_DET.taxid)  and $row.taxid eq 1) } checked="checked" {/if} />
        </span></td>
 
      <td width="619" height="40" align="left"><span class="formfield">
	  {$row.message}
      </span></td>
	  
    </tr>
 {/foreach}	

 <tr valign="middle" class=naGrid2>
 	  <td width="114" height="40"  align="right">&nbsp;</td>	
	  <td width="8" height="40">&nbsp;</td>		
      <td width="619"><div id="custom_div" style="display: {if $smarty.request.taxid eq 3 }block{else}none{/if}; width:400px" >
	  <textarea name="custom_msg" id="custom_msg" style="width:350px; height:100px">{if $TAX_DET.taxid eq 3 }{$TAX_DET.custom_msg}{/if}</textarea></div></td>
    </tr>	
  
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center>	  
	       <input type=submit name="submit" value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
		  <input type="hidden" name="id" value="{$TAX_DET.id}" />
        </div>	 </td> 
    </tr> 
	<tr><td colspan=3 valign=center>&nbsp;</td></tr> 
  </form> 
</table>
</td>
  </tr>
</table>