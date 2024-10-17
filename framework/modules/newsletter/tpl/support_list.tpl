<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Support Lists </td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
        {if count($SUPPORT_LIST) > 0}
        <tr>
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="5%" nowrap class="naGridTitle" align="center"></td>
          <td width="35%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=$MOD pg=$PG orderBy="username" display="User Name"}act=list{/makeLink}</td> 
          <td width="35%" nowrap class="naGridTitle" align="left">{makeLink mod=$MOD pg=$PG orderBy="title" display="Title"}act=list{/makeLink}</td>
          <td width="30%" nowrap class="naGridTitle" align="left">{makeLink mod=$MOD pg=$PG orderBy="date_added" display="Created"}act=list{/makeLink}</td>
        </tr>
        {foreach from=$SUPPORT_LIST item=support}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act=form&id={$support->id}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td valign="middle" align="center"><a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act=delete&id={$support->id}{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td>
          <td height="24" align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act=form&id={$support->id}{/makeLink}">{$support->username}</a></td> 
          <td height="24" align="left" valign="middle">{$support->title}</td>
          <td align="left" valign="middle">{$support->date_added_f}</td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="6" class="msg" align="center" height="30">{$SUPPORT_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="6" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>