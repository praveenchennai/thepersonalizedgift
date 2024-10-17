{literal}

<script language="javascript" type="text/javascript">
function deleteSelected()
{
	document.frm_node.action	=	'{/literal}{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=delete_nodes&feed_id={$smarty.request.feed_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&limit={$smarty.request.limit}{/makeLink}{literal}';
	document.frm_node.submit();
}
</script>

{/literal}



<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0"> 
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_node" method="post" action="" enctype="multipart/form-data" style="margin: 0px;">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Node List</td> 
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=node_form&feed_id={$smarty.request.feed_id}&sId={$smarty.request.sId}&forms_id={$FORM_ID}&fId={$smarty.request.fId}&mId={$MID}&limit={$smarty.request.limit}{/makeLink}">Add New Node</a>
	</td> 
        </tr> 
      </table></td> 
  </tr> 
   <tr> 
    <td><table border=0 width=100% cellspacing="0" cellpadding="2"> 
        <tr>
          <td height="24" colspan="5" align="left" class="naGrid1"><!-- {$DISPLAY_PATH} --></td>
        </tr>
		
    <tr class=naGrid2>
	<td valign=middle align=left >
	{if count($NODE_LIST) > 0}<div align=center class="titleLink"><a href="#" onClick="javascript: deleteSelected();">Delete</a></div>{/if}
    </td>
    	<td colspan=4 align="right" valign=middle>
		
		<table border="0" cellspacing="0" cellpadding="2" align="right">
          <tr>
		 <td>No of Item In a Page</td>
            <td align="center">:</td>
            <td>{$NODE_LIMIT}</td>
          </tr>
        </table></td>
    </tr>
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_node,'node_ids[]')"></td>
          <td width="30%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=node_name display="Node Name"}act=node_list&feed_id={$smarty.request.feed_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&limit={$smarty.request.limit}{/makeLink}</td> 
          <td width="30%" align="left" nowrap class="naGridTitle">Has Child Nodes </td>
              <td width="10%" align="left" nowrap class="naGridTitle">ListingFeature</td>
	    </tr>
        {if count($NODE_LIST) > 0}
        {foreach from=$NODE_LIST item=node name=foo}
        <tr class="{cycle values="naGrid1,naGrid2"}">
          <td valign="middle" align="center"><input type="checkbox" name="node_ids[]" value="{$node->node_id }" ></td> 
          <td align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=node_form&node_id={$node->node_id}&feed_id={$smarty.request.feed_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&limit={$smarty.request.limit}{/makeLink}">{$node->node_name} </a></td> 
          <td align="left" valign="middle">{if $node->haschild_nodes eq 'Y'} Yes {else} No{/if}</td>
          <td align="left" valign="middle">{if $node->fieldname neq ''} {$node->fieldname} {else} Not selected {/if}</td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="5" class="msg" align="center" height="30">{$NODE_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="5" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr>
    
</table><input type="hidden" name="keysearch" value="N">
</form>