<script language="javascript">
{literal}
function Message(theSelect,blog_id,link_id) {
var headerChoice= theSelect;
		if(theSelect=='Y'){		
			document.getElementById(blog_id).style.display="inline";
			document.getElementById(link_id).style.display="none";
		}else if(theSelect=='N'){
			document.getElementById(blog_id).style.display="inline";
			document.getElementById(link_id).style.display="none";
		} 
}
{/literal}
</script>
<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" class=naBrdr> 
  <tr> 
    <td width="9%" colspan="2">
	<table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Blogs</td> 
          <td nowrap align="right" class="titleLink"></td> 
        </tr> 
      </table>
	  </td> 
  </tr>
  <tr> 
    <td colspan="2">
	<table border=0 width=100%>		
			
		 <form action="" method="POST"  name="blogFrm" style="margin: 0px;" >
			<tr>
			  <td height="24" colspan="2" align="left" nowrap class="naGridTitle"></td>
			  <td width="5%" height="24" align="left" nowrap class="naGridTitle">Category</td>
			  <td colspan="2" align="left" nowrap class="naGridTitle">
			  <select name=cat_id onchange="window.location.href='{makeLink mod=blog pg=admin_blog}act=list{/makeLink}&cat_id='+this.value" class="input">
				<option value="">-- SELECT A CATEGORY --</option>
				{html_options values=$SECTION_LIST.id output=$SECTION_LIST.cat_name selected=$CAT_ID}
			 </select>
			  </td>
			  <td height="24" colspan="2" align="left" nowrap class="naGridTitle">&nbsp;</td>
		   </tr>
			<tr>
			  <td height="24" colspan="2" align="left" nowrap class="naGridTitle"></td>
			  <td height="24" align="left" nowrap class="naGridTitle">Subcategory</td>
			  <td colspan="2" align="left" nowrap class="naGridTitle">
			  <select name=subcat_id class="input">
				<option value=""> SELECT A SUBCATEGORY</option>
					{html_options values=$SUBCATLIST.id output=$SUBCATLIST.cat_name selected=$SUBCAT_ID}
				</select>
			  </td>
			  <td height="24" colspan="2" align="left" nowrap class="naGridTitle">&nbsp;</td>
		   </tr>
			<tr>
			  <td height="24" colspan="2" align="left" nowrap class="naGridTitle"></td>
			  <td height="24" align="left" nowrap class="naGridTitle">Key Words </td>
			  <td  align="left" nowrap class="naGridTitle">
			  	<input type="text" name="txtkeywords" value="{$POST.txtkeywords}">
				Exact
				<input name="chkExact" type="checkbox" id="chkExact" value="y" {if $POST.chkExact=="y"} checked{/if}>
			  </td>
			  <td  align="left" nowrap class="naGridTitle">
				 <input type="submit" name="btn_search" value="Search" class="btnBg">
				 &nbsp;				 
			 </td>
			  <td height="24" colspan="2" align="left" nowrap class="naGridTitle">&nbsp;</td>
			</tr>
		 </form>
	  {if count($BLOG_LIST) > 0}	
        <tr>
          <td height="24" colspan="2" align="left" nowrap class="naGridTitle"></td>
          <td height="24" colspan="3" align="left" nowrap class="naGridTitle">{makeLink mod=blog pg=admin_blog orderBy="blog_name" display="Blog Name"}act=list{/makeLink}</td> 
       	 <td height="24" colspan="2" align="left" nowrap class="naGridTitle">{makeLink mod=blog  pg=admin_blog orderBy="blogdate" display="Created Date"}act=list{/makeLink}</td>
	    </tr>
        {foreach from=$BLOG_LIST item=blog_list}
        <form action="" method="POST"  name="blogFrm" style="margin: 0px;">
		<tr class="{cycle name=bg values="naGrid1,naGrid2"}"> 			
			<td  align="center"  valign="middle" colspan="2"><a class="linkOneActive" href="{makeLink mod=blog pg=admin_blog}act=delete&id={$blog_list->id}{/makeLink}"onClick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td> 
			<td height="24" colspan="3" align="left" valign="middle">{$blog_list->blog_name}</td> 
			<td width="23%" height="24" align="left" valign="middle">{$blog_list->blogdate}</td> 
            <td width="32%" align="left" valign="middle">
			{if ($blog_list->active=='Y')}
				<div id="strLink{$blog_list->id}" style="display:display">
				  <a class="linkOneActive" href="#" onClick="return Message('Y','strMessage{$blog_list->id}','strLink{$blog_list->id}')">Suspend</a>	
				</div>				
				<div id="strMessage{$blog_list->id}" style="display:none">			
				<textarea name="strMessage"></textarea>
				<input name="id" type="hidden" value="{$blog_list->id}">				
				<input name="adminflag" type="submit" value="Suspend" class="btnBg" onclick="javascript: return confirm('Do you really want to suspend the blog?')">
				</div>
		      {else}
			  	<div id="strsusLink{$blog_list->id}" style="display:display">
				  <a class="linkOneActive" href="#" onClick="return Message('N','strsusMessage{$blog_list->id}','strsusLink{$blog_list->id}')">Unsuspend</a>	
				</div>
				<div id="strsusMessage{$blog_list->id}" style="display:none">			
					<textarea name="strMessage"></textarea>
					<input name="id" type="hidden" value="{$blog_list->id}">				
					<input name="adminflag" type="submit" value="Unsuspend" class="btnBg">
				</div>	
			{/if}
			</td>
        </tr> 
		</form>
        {/foreach}
		<tr> 
		  <td colspan="7" class="msg" align="center" height="30">{$BLOG_NUMPAD}</td> 
		</tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="7" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table>
    </td> 
  </tr> 
</table>
