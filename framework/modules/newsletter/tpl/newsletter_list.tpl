<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Newsletters</td> 
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=$MOD pg=$PG}act=form{/makeLink}">Add New</a></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
        {if count($NEWSLETTER_LIST) > 0}
        <tr>
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="50%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=$MOD pg=$PG orderBy="name" display="Name"}act=list{/makeLink}</td> 
          <td width="10%" nowrap class="naGridTitle" height="24" align="center">Send</td> 
          <td width="10%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=$MOD pg=$PG orderBy="format" display="Format"}act=list{/makeLink}</td> 
          <td width="20%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=$MOD pg=$PG orderBy="date_created" display="Created"}act=list{/makeLink}</td> 
        </tr>
        {foreach from=$NEWSLETTER_LIST item=newsletter}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act=form&id={$newsletter->id}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act=delete&id={$newsletter->id}{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td> 
          <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act=form&id={$newsletter->id}{/makeLink}">{$newsletter->name}</a></td> 
          <td valign="middle" height="24" align="center"><a href="{makeLink mod=$MOD pg=$PG}act=send&id={$newsletter->id}{/makeLink}">Send</a></td> 
          <td valign="middle" height="24" align="left">{if $newsletter->format eq 'H'}HTML{elseif $newsletter->format eq 'T'}TEXT{else}BOTH{/if}</td> 
          <td valign="middle" height="24" align="left">{$newsletter->date_created_f}</td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="6" class="msg" align="center" height="30">{$NEWSLETTER_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="6" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>