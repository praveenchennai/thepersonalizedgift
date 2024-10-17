<table border=0 width=100% cellpadding=0 cellspacing=0 class=naBrDr> 
	<tr class=naGrid1>
    <td colspan="3" align="center" valign=top><strong>{$FOLDER_NAME}</strong></td>
    </tr>
	{foreach from=$GROUP item=gp name=gp_loop}
	<tr class=naGrid1>
    <td width="28%" height="40" align="right" valign=middle>{$gp.group.group_name}</td>
    <td width="2%" height="40" align="center" valign=middle>:</td>
    <td width="70%" height="40"><select name="new_group_id" id="new_group_id_{$gp.group.id}" style="width:200" onChange="ViewGroup1('{$gp.group.id}',this.value,'{$PRODUCT_ID}','{$STORE}');">
	<option value="0">---Select Group---</option>
     {html_options values=$gp.group.category_id output=$gp.group.category_name}
	          </select></td>
			  
	</tr>
	<tr class=naGrid1>
		<td colspan="3" align="center" valign=top>
		<div align="center" id="grp_{$STORE}_{$gp.group.id}" style="display:inline"></div>
		</td>
    </tr>
	{/foreach}
</table>