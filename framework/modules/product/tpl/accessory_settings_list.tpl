<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_group" method="post" action="" style="margin: 0px;">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1"><!--Accessory Exclude-->{$smarty.request.sId}<input type="hidden" name="limit"  value="{$smarty.request.limit}"/></td> 
          <td nowrap align="right" class="titleLink">
		{if $STORE_PERMISSION.add == 'Y'}
		  <a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=settingsAdd&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}">Add New {$smarty.request.sId} </a>
       {/if}
	   </td> 
	    </tr> 
      </table></td> 
  </tr>
  <tr>
	<td colspan=2 align=center>[This page is used to specify the avioding accessory items combinations]</td>
   </tr> 
   {if count($GROUP_LIMIT) > 0}
  <tr>
   <td valign=top class="naGrid1"><table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td>
	{if $STORE_PERMISSION.delete == 'Y'}
	<a class="linkOneActive" href="#" onclick="javascript: document.frm_group.action='{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=settingsDelete&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}'; document.frm_group.submit();">Delete</a>
	{/if}
	</td>
    <td width="100%">&nbsp;</td>
    <td nowrap>Results per page:</td>
	<td>{$GROUP_LIMIT}</td>
  </tr>
</table></td>
            </tr> 
			{/if}
  <tr> 
  <tr> 
    <td><table width=100% border=0 cellpadding="2" cellspacing="0"> 
		{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=2><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div></td>
    </tr>
    {/if}
       {if count($GROUP_LIMIT) > 0}
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_group,'group_id[]')"></td>
          <td width="96%" height="24" align="left" nowrap class="naGridTitle">Accessory Items</td> 
          </tr>
        {foreach from=$GROUP_LIST item=product}
        <tr class="{cycle values="naGrid1,naGrid2"}">
          <td  valign="middle" align="center"><input type="checkbox" name="group_id[]" value="{$product.group_id}"></td> 
          <td height="24" align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=settingsAdd&group_id={$product.group_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}">{$product.name}</a></td> 
       </tr> 
        {/foreach}
        <tr> 
          <td colspan="2" class="msg" align="center" height="30">{$GROUP_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="2" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>
</form>