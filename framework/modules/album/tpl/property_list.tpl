<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">{$smarty.request.sId}</td> 
          <td nowrap align="right" class="titleLink"><!-- <a href="{makeLink mod=member pg=user}act=sub_form{/makeLink}">Add New</a> --></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="2"> 
        {if count($PROP_DETAILS) > 0}
		<tr>
		  <td  align="right" colspan="6" class="naGrid1">Items per Page: {$LIMIT_LIST}</td> 
        </tr>
        <tr>
          <td width="5%" nowrap class="naGridTitle" align="center">&nbsp;</td>
          <td width="5%" nowrap class="naGridTitle" align="center">{makeLink mod=album pg=album_admin orderBy="active" display="Active"}act=propdView}{/makeLink}</td>
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td>
          <td nowrap class="naGridTitle" height="24" align="left">{makeLink mod=album pg=album_admin orderBy="prop_title" display="Property Name"}act=propdView{/makeLink}</td> 
		  <td nowrap class="naGridTitle" height="24" align="left">Description</td> 
		  <td nowrap class="naGridTitle" height="24" align="right">{makeLink mod=album pg=album_admin orderBy="post_date" display="Added Date"}act=propdView{/makeLink}</td> 
        </tr>
        {foreach from=$PROP_DETAILS item=prop}
        <tr class="{cycle name=bg values="naGrid1,naGrid2"}">
          <td valign="middle" align="center"><a class="linkOneActive" href="{makeLink mod=album pg=album_admin}act=edit_property&propid={$prop->id}&user_id={$prop->user_id}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td valign="middle" align="center"><a href="{makeLink mod=album pg=album_admin}act=active&propid={$prop->id}&stat={$prop->active}{/makeLink}"><img title="Activate/Deactivate" alt="Activate/Deactivate" src="{$GLOBAL.tpl_url}/images/active{$prop->active}.gif" border="0" width="16" height="19"></a></td>
          <td valign="middle"  height="24" align="center"><a class="linkOneActive" href="{makeLink mod=album pg=album_admin}act=albdelete&id={$prop->id}{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td>
          <td valign="middle"  height="24" align="left"><a class="linkOneActive" href="{makeLink mod=album pg=album_admin}act=propdView&propid={$prop->id}{/makeLink}">{$prop->prop_title|truncate:70:"..."}</a></td> 
		  <td valign="middle"  height="24" align="left"><a class="linkOneActive" href="{makeLink mod=album pg=album_admin}act=propdView&propid={$prop->id}{/makeLink}">{$prop->prop_description|truncate:100:"..."}</a></td> 
		  <td valign="middle"  height="24" align="right"><a class="linkOneActive" href="{makeLink mod=album pg=album_admin}act=propdView&propid={$prop->id}{/makeLink}">{$prop->post_date|date_format:"%b %e, %Y %H:%M:%S"}</a></td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="6" class="msg" align="center" height="30">{$SUB_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="6" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>