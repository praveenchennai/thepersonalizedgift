<form name="menu_permission" action="" method="post">
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table border="0" cellspacing="0" cellpadding="4" width="100%" align="center"> 
        <tr>
		<td nowrap class="naH1">{$smarty.request.sId}</td> 
          <td nowrap align="right" class="titleLink" width="100%">&nbsp;</td> 
        </tr>
        <tr>
          <td colspan="2" align="left" class="naGrid1"><strong>{$STORE_DETAILS.name}</strong></td>
        </tr> 
      </table>
	 </td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
        <tr>
          <td width="2%" nowrap class="naGridTitle">&nbsp;</td>
          <td width="30%" height="24" nowrap class="naGridTitle">{makeLink mod=store pg=store_permission orderBy="heading" display="Name"}act=addPermission&id={$smarty.request.id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td>
          <td width="10%" nowrap class="naGridTitle">{makeLink mod=store pg=store_permission orderBy="active" display="Hide"}act=menupermission&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td>
          <td width="10%" nowrap class="naGridTitle">{makeLink mod=store pg=store_permission orderBy="active" display="Add"}act=menupermission&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td>
          <td width="10%" nowrap class="naGridTitle">{makeLink mod=store pg=store_permission orderBy="active" display="Edit"}act=menupermission&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td>
          <td width="38%" nowrap class="naGridTitle">{makeLink mod=store pg=store_permission orderBy="active" display="Delete"}act=menupermission&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td>
        </tr>
        {foreach from=$MENU item=row key = id name = foo}
        <tr class="{cycle values="naGrid1,naGrid2"}">
          <td align="left" valign="middle">&nbsp;</td>
          <td height="24" align="left" valign="middle"><strong>{$row}</strong></td>
          <td align="left" valign="middle"><input type="checkbox" name="hide[{$id}]" value="mod-{$id}" {foreach from=$STORE_PERMISSION item = perm}{if $perm->hide eq 'Y' && $perm->module_id eq $id && $perm->module_menu_id eq '0'}checked{/if}{/foreach}></td>
          <td height="24" align="left" valign="middle">&nbsp;</td>
          <td align="left" valign="middle">&nbsp;</td>
          <td align="left" valign="middle">&nbsp;</td>
        </tr>
		{foreach from=$SUB_MENU[$id]  item=row1 }
        <tr class="{cycle values="naGrid1,naGrid2"}">
          <td align="left" valign="middle">&nbsp;</td> 
          <td height="24" align="left" valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;{$row1->menu}</td> 
          <td align="left" valign="middle"><input type="checkbox" name="menu_hide[{$row1->id}]" value="mod-{$id}/menu-{$row1->id}" {foreach from=$STORE_PERMISSION item = perm}{if $perm->hide eq 'Y' && $perm->module_id eq $id && $perm->module_menu_id eq $row1->id}checked{/if}{/foreach}></td>
          <td height="24" align="left" valign="middle"><input type="checkbox" name="add[{$row1->id}]" value="mod-{$id}/menu-{$row1->id}" {foreach from=$STORE_PERMISSION item = perm}{if $perm->add eq 'Y' && $perm->module_id eq $id && $perm->module_menu_id eq $row1->id}checked{/if}{/foreach}></td> 
          <td align="left" valign="middle"><input type="checkbox" name="edit[{$row1->id}]" value="mod-{$id}/menu-{$row1->id}" {foreach from=$STORE_PERMISSION item = perm}{if $perm->edit eq 'Y' && $perm->module_id eq $id && $perm->module_menu_id eq $row1->id}checked{/if}{/foreach}></td>
          <td align="left" valign="middle"><input type="checkbox" name="delete[{$row1->id}]" value="mod-{$id}/menu-{$row1->id}" {foreach from=$STORE_PERMISSION item = perm}{if $perm->delete eq 'Y' && $perm->module_id eq $id && $perm->module_menu_id eq $row1->id}checked{/if}{/foreach}></td>
        </tr>
		{/foreach}
        {/foreach}
      </table></td> 
  </tr>
    <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><input type=submit value="Submit" class="naBtn">&nbsp;&nbsp;<input type=reset value="Reset" class="naBtn"></td>
  </tr>
     <tr>
    <td align="center">&nbsp;</td>
  </tr>
</table>
</form>