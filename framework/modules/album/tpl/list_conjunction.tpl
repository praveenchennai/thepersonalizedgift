<table width="60%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="60%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="100%" align="center"> 
        <tr> 
          <td nowrap class="naH1">List Conjunctions</td> 
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=album pg=album_admin}act=conjunctions&link=Y{/makeLink}">Add New</a></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
        {if count($RS) > 0}
        <tr>
          <td width="8%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="8%" nowrap class="naGridTitle" height="24" align="center"></td> 
		  <td width="84%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=album pg=album_admin orderBy="conjunction" display="Conjunctions"}act=list_conjunction&link=Y{/makeLink}</td> 
        </tr>
        {foreach from=$RS item=option}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=album pg=album_admin}act=conjunctions&id={$option->id}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=album pg=album_admin}act=conjunction_delete&id={$option->id}{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td> 
		  <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=album pg=album_admin}act=conjunctions&id={$option->id}{/makeLink}">{$option->conjunction}</a></td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="6" class="msg" align="center" height="30">{$NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="6" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>