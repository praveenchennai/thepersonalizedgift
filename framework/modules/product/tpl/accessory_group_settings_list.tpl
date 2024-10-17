<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_product_group" method="post" action="" style="margin: 0px;">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">{$smarty.request.sId}</td> 
          <td nowrap align="right" class="titleLink">{if $STORE_PERMISSION.add == 'Y'}<a href="{makeLink mod=$MOD pg=$PG}act=accessorysettingsForm&limit={$smarty.request.limit}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}">Add New {$smarty.request.sId}</a>{/if}</td> 
        </tr> 
      </table></td> 
  </tr> 
  {if count($SETTINGS_LIST) > 0}
  <tr>
   <td valign=top class="naGrid1"><table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td>{if $STORE_PERMISSION.delete == 'Y'}<a class="linkOneActive" href="#" onclick="javascript: document.frm_product_group.action='{makeLink mod=$MOD pg=$PG}act=settings_Delete&fId={$smarty.request.fId}&sId={$smarty.request.sId}&limit={$smarty.request.limit}{/makeLink}'; document.frm_product_group.submit();">Delete</a>{/if}</td>
    <td width="100%">&nbsp;</td>
    <td nowrap>Results per page:</td>
	<td>{$SETTINGS_LIMIT}</td>
  </tr>
</table></td>
            </tr> {/if}
  <tr> 
    <td><table width=100% border=0 cellpadding="2" cellspacing="0"> 
		{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div></td>
    </tr>
    {/if}
       {if count($SETTINGS_LIST) > 0}
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_product_group,'id[]')"></td>
          <td width="63%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=index orderBy=name display="Name" } act=settings&subact=list&fId={$smarty.request.fId}&sId={$smarty.request.sId}{/makeLink}</td> 
          <td width="33%" align="left" nowrap class="naGridTitle">&nbsp;</td>
	    </tr>
        {foreach from=$SETTINGS_LIST item=product}
        <tr class="{cycle values="naGrid1,naGrid2"}">
          <td  valign="middle" align="center"><input type="checkbox" name="id[]" value="{$product->id}"></td>
          <td align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act=accessorysettingsForm&id={$product->id}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&limit={$smarty.request.limit}{/makeLink}">{$product->name} </a></td> 
          <td align="left" valign="middle"><a href="{makeLink mod=$MOD pg=$PG}act=Accessory_settings_Form&id={$product->id}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&limit={$smarty.request.limit}{/makeLink}">View Arts</a></td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="3" class="msg" align="center" height="30">{$SETTINGS_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="3" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>
</form>