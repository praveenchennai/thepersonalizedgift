<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="70%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Manage Restrictions -> {$smarty.request.type_name}</td> 
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=member pg=user}act=res_form&link_id={$smarty.request.mem_type}&type_name={$smarty.request.type_name}{/makeLink}">Add New</a></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="2"> 
        {if count($SUB_LIST) > 0}
		<tr>
		  <td  align="right" colspan="3" class="naGrid1">Items per Page: {$LIMIT_LIST}</td> 
        </tr>
        <tr>
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td nowrap class="naGridTitle" height="24" align="left">{makeLink mod=member pg=user orderBy="display_name" display="Section"}act=res_list&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
		  <td nowrap class="naGridTitle" height="24" align="left">{makeLink mod=member pg=user orderBy="max_records_user" display="Maximum Records"}act=res_list&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
	    </tr>
        {foreach from=$SUB_LIST item=sub}
        <tr class="{cycle name=bg values="naGrid1,naGrid2"}"> 
          <td valign="middle"  height="24" align="center"><a class="linkOneActive" href="{makeLink mod=member pg=user}act=res_form&id={$sub->id}&link_id={$sub->link_id}&type_name={$smarty.request.type_name}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td valign="middle"  height="24" align="left"><a class="linkOneActive" href="{makeLink mod=member pg=user}act=res_form&id={$sub->id}&link_id={$sub->link_id}&type_name={$smarty.request.type_name}{/makeLink}">{$sub->display_name}</a></td> 
		  <td valign="middle"  height="24" align="left"><a class="linkOneActive" href="{makeLink mod=member pg=user}act=res_form&id={$sub->id}&link_id={$sub->link_id}&type_name={$smarty.request.type_name}{/makeLink}">{$sub->max_records_user}</a></td> 
	    </tr> 
        {/foreach}
        <tr> 
          <td colspan="2" class="msg" align="center" height="30">{$SUB_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="2" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>