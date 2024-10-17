<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Subscription Packages</td> 
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=member pg=user}act=sub_form{/makeLink}">Add New</a></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="2"> 
        {if count($SUB_LIST) > 0}
		<tr>
		  <td  align="right" colspan="5" class="naGrid1">Items per Page: {$LIMIT_LIST}</td> 
        </tr>
        <tr>
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td nowrap class="naGridTitle" height="24" align="left">{makeLink mod=member pg=user orderBy="name" display="Package Name"}act=sub_list{/makeLink}</td> 
		  <td nowrap class="naGridTitle" height="24" align="left">Duration</td> 
		  <td nowrap class="naGridTitle" height="24" align="right">{makeLink mod=member pg=user orderBy="fees" display="Subscription Fee"}act=sub_list{/makeLink}</td> 
        </tr>
        {foreach from=$SUB_LIST item=sub}
        <tr class="{cycle name=bg values="naGrid1,naGrid2"}"> 
          <td valign="middle"  height="24" align="center"><a class="linkOneActive" href="{makeLink mod=member pg=user}act=sub_form&id={$sub->id}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td valign="middle"  height="24" align="center"><a class="linkOneActive" href="{makeLink mod=member pg=user}act=sub_delete&id={$sub->id}{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td> 
          <td valign="middle"  height="24" align="left"><a class="linkOneActive" href="{makeLink mod=member pg=user}act=sub_form&id={$sub->id}{/makeLink}">{$sub->name}</a></td> 
		  <td valign="middle"  height="24" align="left"><a class="linkOneActive" href="{makeLink mod=member pg=user}act=sub_form&id={$sub->id}{/makeLink}">{$sub->duration} {if $sub->type=='Y'} Year {elseif  $sub->type=='M'} Month {else} Days {/if}</a></td> 
		  <td valign="middle"  height="24" align="right"><a class="linkOneActive" href="{makeLink mod=member pg=user}act=sub_form&id={$sub->id}{/makeLink}">${$sub->fees|string_format:"%.2f"}</a></td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="3" class="msg" align="center" height="30">{$SUB_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="3" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>