<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Admin Users</td> 
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=$smarty.request.mod pg=admin}act=form{/makeLink}">Add New</a></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="2"> 
        {if count($ADMIN_LIST) > 0}
        <tr>
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td nowrap class="naGridTitle" height="24" align="left">{makeLink mod=$smarty.request.mod pg=admin orderBy="username" display="User Name"}act=list{/makeLink}</td> 
        </tr>
        {foreach from=$ADMIN_LIST item=admin}
        <tr> 
          <td valign="middle" class="{cycle name=bg values="naGrid1,naGrid2" advance=false}" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=admin}act=form&id={$admin->id}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td valign="middle" class="{cycle values="naGrid1,naGrid2" advance=false}" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=admin}act=delete&id={$admin->id}{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td> 
          <td valign="middle" class="{cycle values="naGrid1,naGrid2"}" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=admin}act=form&id={$admin->id}{/makeLink}">{$admin->name} ({$admin->username})</a></td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="3" class="msg" align="center" height="30">{$ADMIN_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="3" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>