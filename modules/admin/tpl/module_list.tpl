<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Modules</td> 
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=admin pg=module}act=form{/makeLink}">Add New</a></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="2"> 
        {if count($MODULE_LIST) > 0}
        <tr>
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="40%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=admin pg=module orderBy="name" display="Module Name"}act=list{/makeLink}</td> 
          <td width="40%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=admin pg=module orderBy="folder" display="Folder Name"}act=list{/makeLink}</td> 
          <td width="10%" nowrap class="naGridTitle" align="left">{makeLink mod=admin pg=module orderBy="position" display="Position"}act=list{/makeLink}</td>
        </tr>
        {foreach from=$MODULE_LIST item=module}
        <tr> 
          <td valign="middle" class="{cycle values="naGrid1,naGrid2" advance=false}" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=admin pg=module}act=form&id={$module->id}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td valign="middle" class="{cycle values="naGrid1,naGrid2" advance=false}" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=admin pg=module}act=delete&id={$module->id}{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td> 
          <td valign="middle" class="{cycle values="naGrid1,naGrid2" advance=false}" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=admin pg=module}act=form&id={$module->id}{/makeLink}">{$module->name}</a></td> 
          <td valign="middle" class="{cycle values="naGrid1,naGrid2" advance=false}" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=admin pg=module}act=form&id={$module->id}{/makeLink}">{$module->folder}</a></td> 
          <td valign="middle" class="{cycle values="naGrid1,naGrid2"}" align="left">{$module->position}</td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="5" class="msg" align="center" height="30">{$MODULE_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="5" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>