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
    <tr align="left">
      <td colspan=3 valign=top><table width="400%" align="center">
        <tr>
          <td width="23%" nowrap class="naH1">{$smarty.request.sId} </td>
          <td width="77%" align="right" nowrap class="titleLink"><a href="{makeLink mod=payment pg=index}act=paymentlist{/makeLink}&store_name={$smarty.request.store_name}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}">{$smarty.request.sId} List</a></td>
        </tr>
      </table></td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3>
		<div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>
      </td>
    </tr>
    {/if}
	<tr class=naGrid2>
      <td valign=top colspan=3 ><strong>{$PAYMENT_METHOD}</strong></td>
    </tr>
    <tr class=naGrid2>
      <td  align="right" valign=top>Merchant Login ID</td>
      <td valign=top>:</td>
      <td align="left"><input name="auth_net_login_id" type="text" class="input" id="coupon_name" value="{$FORM_VALUES.auth_net_login_id}" size="30"/>        <font color="#999999" size="-3">&nbsp; </font></td>
      </tr>
    <tr class=naGrid2> 
      <td  align="right" valign=top>Merchant Transaction Key</td> 
      <td width=3 valign=top>:</td> 
      <td align="left"><input name="auth_net_tran_key" type="text" class="input" id="coupon_start" value="{$FORM_VALUES.auth_net_tran_key}" size="30" /></td> 
    </tr>
   <tr class=naGrid2>
     <td width="230" valign=top>&nbsp;</td>
     <td valign=top>&nbsp;</td>
     <td align="left">&nbsp;</td>
   </tr> 
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center>	  
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