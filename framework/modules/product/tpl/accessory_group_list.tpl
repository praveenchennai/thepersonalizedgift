<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_group" method="post" action="" style="margin: 0px;">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1"><!--Groups-->{$smarty.request.sId}<input type="hidden" name="limit"  value="{$smarty.request.limit}"/></td> 
          <td nowrap align="right" class="titleLink">
          {if $STORE_PERMISSION.add == 'Y'}		  
		  <a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=groupform&limit={$smarty.request.limit}{/makeLink}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}">Add New {$smarty.request.sId}</a>
		  {/if}
		  </td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr>
   <td valign=top class="naGrid1"><table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td>
	{if $STORE_PERMISSION.delete == 'Y'}	
	<a class="linkOneActive" href="#" onclick="javascript: document.frm_group.action='{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=groupDelete&limit={$smarty.request.limit}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}'; document.frm_group.submit();">Delete</a>
    {/if}
	</td>
    <td width="100%">&nbsp;</td>
    <td nowrap>Results per page:</td>
	<td>{$GROUP_LIMIT}</td>
  </tr>
</table></td>
            </tr> 
  <tr> 
  <tr> 
    <td><table width=100% border=0 cellpadding="2" cellspacing="0"> 
		{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=4><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div></td>
    </tr>
    {/if}
       {if count($GROUP_LIMIT) > 0}
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_group,'id[]')"></td>
          <td width="50%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=group_name display="Group Name"}act=grouplist&subact=list&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}</td> 
          <td width="23%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=parameter display="Parameter"}act=grouplist&subact=list&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}</td>
	      <td width="23%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=display_order display="Display Order"}act=grouplist&subact=list&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}</td>
	    </tr>
        {foreach from=$GROUP_LIST item=product}
        <tr class="{cycle values="naGrid1,naGrid2"}">
          <td  valign="middle" align="center"><input type="checkbox" name="id[]" value="{$product->id}"></td> 
          <td height="24" align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=groupform&group_id={$product->id}&limit={$smarty.request.limit}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}">{$product->group_name}</a></td> 
          <td align="left" valign="middle">{if $product->parameter==Y}Yes{else}No{/if}</td>
          <td align="left" valign="middle">{$product->display_order}</td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="4" class="msg" align="center" height="30">{$GROUP_NUMPAD}</td> 
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