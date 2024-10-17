<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1"><!--Section--> {$smarty.request.sId} </td> 
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=$smarty.request.mod pg=$PG}act=form{/makeLink}&sId={$SUBNAME}&mId={$MID}&sel=cms">Add New {$smarty.request.sId} </a></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
        {if count($SECTION_LIST) > 0}
        <tr>
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="70%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=$smarty.request.mod pg=$PG orderBy="name" display="Section Name"}act=list{/makeLink}</td> 
          <td width="10%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=$smarty.request.mod pg=$PG orderBy="show_menu" display="Show Menu"}act=list{/makeLink}</td> 
          <td width="10%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=$smarty.request.mod pg=$PG orderBy="active" display="Active"}act=list{/makeLink}</td> 
        </tr>
        {foreach from=$SECTION_LIST item=section}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=$PG}act=form&id={$section->id}&sId={$SUBNAME}&mId={$MID}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=$PG}act=delete&id={$section->id}&sId={$SUBNAME}&mId={$MID}{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td> 
          <td valign="middle"   height="24" align="left">  <a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=$PG}act=form&id={$section->id}&sId={$SUBNAME}&mId={$MID}{/makeLink}">{$section->name}</a></td> 
          <td valign="middle" height="24" align="left">{if $section->show_menu=='Y'}Yes{else}No{/if}</td> 
          <td valign="middle" height="24" align="left">{if $section->active=='Y'}Yes{else}No{/if}</td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="5" class="msg" align="center" height="30">{$SECTION_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="5" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>