<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table border="0" cellspacing="0" cellpadding="4" width="100%" align="center"> 
        <tr>
		<td nowrap class="naH1">Stores</td> 
          <td nowrap align="right" class="titleLink" width="100%"><a href="{makeLink mod=store pg=index}act=form{/makeLink}">Add New</a></td> 
        </tr>
        <tr>
          <td colspan="2" align="right" class="naGrid1"><strong>Results per page</strong> {$STORE_LIMIT}</td>
        </tr> 
      </table>
	 </td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
        {if count($STORE_LIST) > 0}
        <tr>
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="30%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=store pg=index orderBy="name" display="Store Name"}act=list{/makeLink}</td> 
          <td width="30%" nowrap class="naGridTitle">{makeLink mod=store pg=index orderBy="heading" display="Title"}act=list{/makeLink}</td>
          <td width="10%" nowrap class="naGridTitle">{makeLink mod=store pg=index orderBy="active" display="Status"}act=list{/makeLink}</td>
        </tr>
        {foreach from=$STORE_LIST item=row}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=store pg=index}act=form&id={$row->id}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=store pg=index}act=delete&id={$row->id}{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td> 
          <td height="24" align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=store pg=index}act=form&id={$row->id}{/makeLink}">{$row->name}</a></td> 
          <td height="24" align="left" valign="middle">{$row->heading}</td> 
          <td height="24" align="left" valign="middle">{if $row->active=='Y'}Active{else}Inactive{/if}</td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="5" class="msg" align="center" height="30">{$STORE_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="5" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>