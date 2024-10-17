<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Mailing List</td> 
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=$MOD pg=$PG}act=form{/makeLink}">Add New</a></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
        {if count($MAILINGLIST_LIST) > 0}
        <tr>
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="90%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=$MOD pg=$PG orderBy="name" display="List Name"}act=list{/makeLink}</td> 
        </tr>
        {foreach from=$MAILINGLIST_LIST item=mailingList}
		<!-- 19 - Feb - 2008 Salim
		Here a condition (IF) is added to avoid the listing of record with id value = 0 from newsletter_mailing_list table 
		Purpose:: 0th record is added to keep track of the newsletters sent using checkbox(member_master table).
		 -->
		{if $mailingList->id ne 0}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act=form&id={$mailingList->id}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act=delete&id={$mailingList->id}{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td> 
          <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act=form&id={$mailingList->id}{/makeLink}">{$mailingList->name}</a></td> 
        </tr> 
		{/if}
        {/foreach}
        <tr> 
          <td colspan="4" class="msg" align="center" height="30">{$MAILINGLIST_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="4" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>