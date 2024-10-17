<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td><!--{messageBox}--></td>
  </tr>
</table>
<table  width="100%"><tr><td>
<form name="frm_made" method="post" action="" enctype="multipart/form-data" style="margin: 0px;">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Credit Card<!--Zone{$smarty.request.sId}-->
		  <input type="hidden" name="limit"  value="{$smarty.request.limit}"/></td> 
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=order pg= 	paymentType}act=creditform&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}">Add New CreditCard<!-- {$smarty.request.sId} --> </a></td> 
        </tr> 
      </table></td> 
  </tr>
  {if count($PAYMENTTYPE_LIST) > 0}
  <tr>
    <td class="naGrid1"><table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td><a class="linkOneActive" href="#" onclick="javascript: document.frm_made.action='{makeLink mod=order pg=paymentType}act=delete&fId={$smarty.request.fId}&sId={$smarty.request.sId}{/makeLink}'; document.frm_made.submit();">Delete</a>&nbsp;&nbsp;
	<a class="linkOneActive" href="#" onclick="javascript: document.frm_made.action='{makeLink mod=order pg=paymentType}act=ccardactive&fId={$smarty.request.fId}&sId={$smarty.request.sId}&limit={$smarty.request.limit}{/makeLink}'; document.frm_made.submit();">Active</a>&nbsp;&nbsp;
	<a class="linkOneActive" href="#" onclick="javascript: document.frm_made.action='{makeLink mod=order pg=paymentType}act=ccardinactive&fId={$smarty.request.fId}&sId={$smarty.request.sId}&limit={$smarty.request.limit}{/makeLink}'; document.frm_made.submit();">Inactive</a>
	</td>
    <td width="19%" nowrap> Results per page: </td>
	<td width="21%">{$PAYMENTTYPE_LIMIT}</td>
  </tr>
</table>
</td>
  </tr> 
  {/if}
  <tr> 
    <td><table width=100% border=0 cellpadding="0" cellspacing="0"> 
		{if count($PAYMENTTYPE_LIST) > 0}
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_made,'creditcard_id[]')"></td>
          <td width="48%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=order pg=paymentType orderBy=name display="Name"}act=creditlist&fId={$smarty.request.fId}&sId={$smarty.request.sId}{/makeLink}</td>
		  <td width="20%" align="left" nowrap class="naGridTitle">{makeLink mod=order pg=paymentType orderBy=active display="Status"}act=creditlist&fId={$smarty.request.fId}&sId={$smarty.request.sId}{/makeLink}</td>
		  <td width="28%" align="left" nowrap class="naGridTitle">&nbsp;</td>
	    </tr>
        {foreach from=$PAYMENTTYPE_LIST item=payment}
        <tr class="{cycle name=bg values="naGrid1,naGrid2"}">
          <td  valign="middle"  align="center"><input type="checkbox" name="creditcard_id[]" value="{$payment->id}"></td> 
          <td height="35" align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=order pg=paymentType}act=creditform&id={$payment->id}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&limit={$smarty.request.limit}{/makeLink}">{$payment->name} </a></td> 
		  <td align="left" nowrap>{if $payment->active == Y}Active{else} Inactive{/if}</td>
          <td height="35" align="left" valign="middle">{if $payment->logo_extension ne ''}
		  <img src="{$GLOBAL.mod_url}/images/paymenttype/{$payment->id}{$payment->logo_extension}" width="50" height="30">
		  {/if}</td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="4" class="msg" align="center" height="30">{$PAYMENTTYPE_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="4" class="naError" align="center" height="30">No Records</td> 
        </tr>
		 {/if}
      </table></td> 
  </tr> 
</table>
</form>
<!--{if file_exists("`$smarty.const.SITE_PATH`/templates/`$GLOBAL.curr_tpl`/top_menu.tpl")}{include file="`$smarty.const.SITE_PATH`/templates/`$GLOBAL.curr_tpl`/top_menu.tpl"}{/if}
$GATEWAY /var/www/html/framework/modules/order/tpl/creditcard_gateway_form.tpl-->

</td></tr></table>
