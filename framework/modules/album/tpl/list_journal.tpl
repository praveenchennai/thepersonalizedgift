<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">List Journal </td> 
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=album pg=album_admin}act=add_journal&link=Y{/makeLink}">Add New</a></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
        {if count($JOURNAL_LIST) > 0}
        <tr>
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
		  <td width="40%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=album pg=album_admin orderBy="journal_name" display="Full Journal Name"}act=list_journal&link=Y{/makeLink}</td> 
          <td width="30%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=album pg=album_admin orderBy="journal_acronym" display="Acronym"}act=list_journal&link=Y{/makeLink}</td> 
		  <td width="30%" nowrap class="naGridTitle" height="24" align="center">{makeLink mod=album pg=album_admin orderBy="journal_volume" display="Volume"}act=list_journal&link=Y{/makeLink}</td> 
          <td width="25%" nowrap class="naGridTitle" height="24" align="center">{makeLink mod=album pg=album_admin orderBy="journal_year" display="Year"}act=list_journal&link=Y{/makeLink}</td> 

        </tr>
        {foreach from=$JOURNAL_LIST item=option}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=album pg=album_admin}act=add_journal&id={$option->id}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=album pg=album_admin}act=journal_delete&id={$option->id}{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td> 
		  <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=album pg=album_admin}act=add_journal&id={$option->id}&link=Y{/makeLink}">{$option->journal_name}</a></td> 
          <td valign="middle" height="24" align="left">{$option->journal_acronym}</td> 
		  <td valign="middle" height="24" align="center">{$option->journal_volume}</td> 
          <td valign="middle" height="24" align="center">{$option->journal_year}</td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="6" class="msg" align="center" height="30">{$JOURNAL_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="6" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>