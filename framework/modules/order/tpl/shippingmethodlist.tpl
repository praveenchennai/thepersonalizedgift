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
          <td nowrap class="naH1">Shipping Method
		  <input type="hidden" name="limit"  value="{$smarty.request.limit}"/></td> 
          <td nowrap align="right" class="titleLink">&nbsp;<!--<a href="{makeLink mod=order pg=shipping}act=shipformform&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}">Add New Shipping Method</a>--></td> 
        </tr> 
      </table></td> 
  </tr>
  {if count($SHIPMETHODLIST) > 0}
  <tr>
    <td class="naGrid1"><table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td>
	<!--<a class="linkOneActive" href="#" onclick="javascript: {literal}if(!confirm('Are you sure you want to remove the selected shipping methods?')) { return false;} {/literal} document.frm_made.action='{makeLink mod=order pg=shipping}act=shipmethoddelete&fId={$smarty.request.fId}&sId={$smarty.request.sId}{/makeLink}'; document.frm_made.submit();">Delete</a>-->&nbsp;&nbsp;
	<a class="linkOneActive" href="#" onclick="javascript: document.frm_made.action='{makeLink mod=order pg=shipping}act=shipmethodactivate&fId={$smarty.request.fId}&sId={$smarty.request.sId}&limit={$smarty.request.limit}{/makeLink}'; document.frm_made.submit();">Active</a>&nbsp;&nbsp;
	<a class="linkOneActive" href="#" onclick="javascript: document.frm_made.action='{makeLink mod=order pg=shipping}act=shipmethodinactive&fId={$smarty.request.fId}&sId={$smarty.request.sId}&limit={$smarty.request.limit}{/makeLink}'; document.frm_made.submit();">Inactive</a>
	</td>
    <td width="19%" nowrap> Results per page: </td>
	<td width="21%">{$PAYMETHOD_LIMIT}</td>
  </tr>
</table>
</td>
  </tr> 
  {/if}
  <tr> 
    <td><table width=100% border=0 cellpadding="0" cellspacing="0"> 
		{if count($SHIPMETHODLIST) > 0}
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_made,'shipmethod_ids[]')"></td>
          <td width="48%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=order pg=shipping orderBy=name display="Name"}act=shippinglist&fId={$smarty.request.fId}&sId={$smarty.request.sId}{/makeLink}</td>
		  <td width="20%" align="left" nowrap class="naGridTitle">{makeLink mod=order pg=shipping orderBy=active display="Status"}act=shippinglist&fId={$smarty.request.fId}&sId={$smarty.request.sId}{/makeLink}</td>
		  <td width="28%" align="left" nowrap class="naGridTitle">&nbsp;</td>
	    </tr>
        {foreach from=$SHIPMETHODLIST item=shipmethod}
        <tr class="{cycle name=bg values="naGrid1,naGrid2"}">
          <td  valign="middle"  align="center"><input type="checkbox" name="shipmethod_ids[]" value="{$shipmethod->shipmethod_id}"></td> 
          <td height="35" align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=order pg=shipping}act=shipformform&id={$shipmethod->shipmethod_id}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&limit={$smarty.request.limit}{/makeLink}">{$shipmethod->name} </a></td> 
		  <td align="left" nowrap>{if $shipmethod->active == Y}Active{else} Inactive{/if}</td>
          <td height="35" align="left" valign="middle">{if $shipmethod->logo_extension ne ''}
		  <img src="{$GLOBAL.mod_url}/images/shipping/thumb/{$shipmethod->shipmethod_id}{$shipmethod->logo_extension}" >
		  {/if}</td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="4" class="msg" align="center" height="30">{$PAYMETHOD_NUMPAD}</td> 
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
