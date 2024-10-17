<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></SCRIPT>
<style>
{literal}
.accessory td {
	cursor:pointer;
	padding-left:5px;
}
.accessoryHover td {
	cursor:pointer;
	background-color:#aabbdd;
	padding-left:5px;
}
{/literal}
</style>
<table width="90%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="96%" border="0" cellspacing="0" cellpadding="0" class="naBrdr">
  <tr>
    <td><table width="98%" align="center">
      <tr>
        <td nowrap="nowrap" class="naH1">Manual Order Details </td>
        <td align="right" nowrap="nowrap"><strong>{if !$smarty.request.print}<a href="javascript:void(0);" onclick="w=window.open('{makeLink mod=order pg=order}act=details&id={$ORDER_DETAILS->id}&print=1{/makeLink}', 'w', 'width=1020,height=700,scrollbars=yes');w.focus();w.onload=function () {literal}{window.print();}{/literal}">Print</a>{/if}</strong></td>
      </tr>
    </table>
	</td>
  </tr>
  <tr>
   {if count($ORDER_DETAILS) > 0}
    <td>
	<form id="form1" name="form1" method="post" action="" style="margin:0px;">
	<table border="0" width="100%" cellpadding="5" cellspacing="2">
	
	<tr>
	
	
	</tr>
	
	
	</table>
	</form>