<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/scripts/validator.js"></SCRIPT>

<table width="820" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table width="100%"  border="0">
  <tr>
    <td valign="top"><table width=80% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
<form action="" method="post" enctype="multipart/form-data" name="frm">
  	<input type="hidden" name="act" value="{$smarty.request.act}">
	<input type="hidden" name="storeowner" value="{$smarty.request.storeowner}">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
	<input type="hidden" name="mId" value="{$smarty.request.mId}">
	<input type="hidden" name="store_name" value="{$smarty.request.store_name}">

	<input type="hidden" name="old_cert_file_path" value="{$FORM_VALUES.cert_file_path}">
    <tr align="left">
      <td colspan=4 valign=top><table width="400%" align="center">
        <tr>
          <td width="23%" nowrap class="naH1">{$smarty.request.sId} </td>
          <td width="77%" align="right" nowrap class="titleLink"><a href="{makeLink mod=payment pg=index}act=paymentlist{/makeLink}&store_name={$smarty.request.store_name}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}">{$smarty.request.sId} List</a></td>
        </tr>
      </table></td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=4>
		<div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>
      </td>
    </tr>
    {/if}
	<tr class=naGrid2>
      <td valign=top colspan=4 ><strong>{$PAYMENT_METHOD}</strong></td>
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=top>API Username</td>
      <td valign=top>:</td>
      <td colspan="2" align="left"><input name="api_username" type="text" class="input" id="coupon_name" value="{$FORM_VALUES.api_username}" size="30"/></td>
    </tr>
    <tr class=naGrid2> 
      <td  align="right" valign=top>API Password</td> 
      <td width=3 valign=top>:</td> 
      <td colspan="2" align="left"><input name="api_password" type="text" class="input" id="coupon_start" value="{$FORM_VALUES.api_password}" size="30" /></td> 
    </tr>
   <tr class=naGrid2>
     <td align="right" valign=top>Certificate File</td>
     <td valign=top>:</td>
     <td width="220" align="left"><input type="file" name="cert_file_path"> </td>
     <td width="288" align="left">{if $FORM_VALUES.cert_file_path neq "" }<a href="{makeLink mod=payment pg=index}act=downloadcertificate&filename={$FORM_VALUES.cert_file_path}{/makeLink}"><img title="Download Certificate" alt="Download Certificate" src="{$GLOBAL.tpl_url}/images/grid/icon_download.gif" border="0"></a>{/if}</td>
   </tr>
   <tr class=naGrid2>
     <td height="20" align="right" valign=top>&nbsp;</td>
     <td height="20" valign=top>&nbsp;</td>
     <td height="20" colspan="2" align="left"><strong>OR</strong> (Either certificate file or Signature required) </td>
   </tr>
   <tr class=naGrid2>
     <td align="right" valign=top>Signature</td>
     <td valign=top>:</td>
     <td colspan="2" align="left"><input type="text" name="signature" value="{$FORM_VALUES.signature}">	
	   </td>
     </tr>
   
   <tr class=naGrid2>
     <td width="230" valign=top>&nbsp;</td>
     <td valign=top>&nbsp;</td>
     <td colspan="2" align="left">&nbsp;</td>
   </tr> 
    <tr class="naGridTitle"> 
      <td colspan=4 valign=center><div align=center>	  
	       <input type=submit name="Submit" value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div>
	 </td> 
    </tr> 
  </form> 
</table>
</td>
  </tr>
</table>