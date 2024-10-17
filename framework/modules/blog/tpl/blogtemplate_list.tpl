<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" class=naBrdr> 
  <tr> 
    <td width="9%" colspan="2">
	<table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Topics</td> 
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=blog pg=blogtemplate}act=form{/makeLink}">Add New</a></td> 
        </tr> 
      </table></td> 
  </tr>
  <tr> 
    <td colspan="2"><table border=0 width=100%>
		{if count($TEMPLATE_LIST) > 0}
        <tr>
          <td height="24" colspan="2" align="left" nowrap class="naGridTitle"></td>
          <td width="24%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=blog pg=blogtemplate orderBy="site_text" display="Site Text"}act=list{/makeLink}</td> 
       	 <td width="56%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=blog pg=blogtemplate orderBy="site_text" display="Page_width"}act=list{/makeLink}</td>
	    </tr>
        {foreach from=$TEMPLATE_LIST item=tempalte}
        <tr class="{cycle name=bg values="naGrid1,naGrid2"}"> 
			<td width="10%"  align="center"  valign="middle"><a class="linkOneActive" href="{makeLink mod=blog pg=blogtemplate}act=form&id={$tempalte->id}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
			<td width="10%"  align="center"  valign="middle"><a class="linkOneActive" href="{makeLink mod=blog pg=blogtemplate}act=delete&id={$tempalte->id}{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td> 
			<td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=blog pg=blogtemplate}act=form&id={$tempalte->id}{/makeLink}">{$tempalte->site_text} </a></td> 
			<td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=blog pg=blogtemplate}act=form&id={$tempalte->id}{/makeLink}">{$tempalte->temp_width} </a></td> 
        </tr> 
        {/foreach}
		<tr> 
		  <td colspan="4" class="msg" align="center" height="30">{$TEMPLATE_NUMPAD}</td> 
		</tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="4" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>
