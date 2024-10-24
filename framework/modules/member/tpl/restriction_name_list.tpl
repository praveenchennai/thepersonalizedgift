<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Restriction Name</td> 
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=member pg=user}act=restriction_name_form{/makeLink}">Add New</a></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="2"> 
        {if count($RESTRICTION_LIST) > 0}
		<tr>
		  <td  align="right" colspan="4" class="naGrid1">Items per Page: {$LIMIT_LIST}</td> 

        </tr>
        <tr>
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td nowrap class="naGridTitle" height="24" align="left">{makeLink mod=member pg=user orderBy="restriction_name" display="Restriction Name"}act=restriction_name_list{/makeLink}</td> 
		  <td nowrap class="naGridTitle" height="24" align="right">{makeLink mod=member pg=user orderBy="restriction_value" display="Restriction Limit"}act=restriction_name_list{/makeLink}</td> 
        </tr>
        {foreach from=$RESTRICTION_LIST item=pack}
        <tr class="{cycle name=bg values="naGrid1,naGrid2"}"> 
          <td valign="middle"  height="24" align="center"><a class="linkOneActive" href="{makeLink mod=member pg=user}act=restriction_name_form&id={$pack->restriction_list_id}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td valign="middle"  height="24" align="center"><a class="linkOneActive" href="{makeLink mod=member pg=user}act=pack_delete&id={$pack->restriction_list_id}{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td> 
          <td valign="middle"  height="24" align="left"><a class="linkOneActive" href="{makeLink mod=member pg=user}act=restriction_name_form&id={$pack->restriction_list_id}{/makeLink}">{$pack->restriction_name}</a></td> 
		  <td valign="middle"  height="24" align="right"><a class="linkOneActive" href="{makeLink mod=member pg=user}act=restriction_name_form&id={$pack->restriction_list_id}{/makeLink}">{$pack->restriction_value}</a></td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="3" class="msg" align="center" height="30">{$PACK_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="3" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>