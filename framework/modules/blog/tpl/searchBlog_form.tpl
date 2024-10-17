
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
 <tr>
	<td height="39" class="naH1">Search Blog </td>
  </tr>
   <tr>
    <td align="center">	
	<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="border">
		  <tr>
			<td width="80%" height="169" align="left" valign="top" bgcolor="#EEEEEE"  colspan="2">			
			<table width=100% border=0 align="center" cellpadding=5 cellspacing=1>			 
			<form action="" method="POST" enctype="multipart/form-data" name="frmSearch" style="margin: 0px;">
			{if isset($MESSAGE)}
			  <tr>
				<td valign=top colspan=5><div align=center class="element_style"> <span class="naError">{$MESSAGE}</span></div></td>
			  </tr>
			{/if}
			<tr>
			  <td colspan="3" align="right" valign=top class="blackboldtext">Category</td>
			  <td valign=top class="blackboldtext">:</td>
			  <td>
			  <select name=cat_id onchange="window.location.href='{makeLink mod=blog pg=searchBlog}act=list{/makeLink}&cat_id='+this.value" class="input">
				<option value="">-- SELECT A CATEGORY --</option>
				{html_options values=$SECTION_LIST.id output=$SECTION_LIST.cat_name selected=$CAT_ID}
			 </select>
			  </td>
			  </tr>
			<tr>
			  <td colspan="3" align="right" valign=top class="blackboldtext">Subcategory</td>
			  <td valign=top class="blackboldtext">:</td>
			  <td>
				<select name=subcat_id class="input">
				<option value=""> SELECT A SUBCATEGORY</option>
					{html_options values=$SUBCATLIST.id output=$SUBCATLIST.cat_name selected=$SUBCAT_ID}
				</select>
			  </td>
			  </tr>
			<tr>
			  <td colspan="3" align="right" valign=top class="blackboldtext">Enter Keywords</td>
			  <td width="3" valign=top class="blackboldtext">:</td>
			  <td class="blackboldtext">
				<input type="text" name="txtkeywords" value="{$POST.txtkeywords}">
				Exact
				<input name="chkExact" type="checkbox" id="chkExact" value="y" {if $POST.chkExact=="y"} checked{/if}>
			  </td>
			</tr>
			<tr>
			  <td colspan=5 valign=center>
				<div align=center>				
				  <input type="submit" name="btn_search" value="Search" class="btnBg">
					&nbsp;
				  <input name="reset" type=reset  value=" Reset" class="btnBg">
			  </div>
			  </td>
			</tr>
			  </form>
			</table>
					<table width=100% border=0 align="center" cellpadding=0 cellspacing=0>					   
					  {if count($SEARCH_LIST) > 0}
					  <tr>
						<td height="24" colspan="5" align="left" nowrap class="naGridTitle"><strong>Blog</strong></td>
					  </tr>
					  {foreach from=$SEARCH_LIST item=blog}
					  <tr class="naGrid1">
						<td width="3%"  align="left"  valign="middle">&nbsp;</td>
						<td colspan="3"  align="left"  valign="middle" class="blackboldtext">
						  {$blog->name}
						</td>
					    <td rowspan="2"  align="left"  valign="top"><a class="blackboldtext" href="{$blog->uname}"><strong>{$blog->blog_name}</strong></a><br>
						{$blog->blog_description} 
						</td>
					  </tr>
					  <tr class="naGrid1">
					    <td height="41"  align="left"  valign="middle">&nbsp;</td>
				        <td colspan="3"  align="left"  valign="middle">
						{if $blog->image==Y }						
							<img src="{$GLOBAL.modbase_url}/member/images/userpics/thumb/{$blog->user_id}.jpg" border="0" width="75" height="75">
						{else}						
							<img src="{$smarty.const.SITE_URL}/templates/default/images/nophoto.jpg" border="0" width="75" height="75">
						{/if}
						</td>
					  </tr> 
					   <tr class="naGrid2">
						<td height="19"  align="center">&nbsp;</td>
						<td height="19" colspan="3"  align="center">&nbsp;</td>
						<td width="84%" height="19"  align="center">&nbsp;</td>
					  </tr>
					 {/foreach}
					  <tr class="naGridTitle">
						<td height="30"  align="center">&nbsp;</td>
						<td height="30" colspan="3"  align="center">&nbsp;</td>
						<td width="84%" height="30"  align="center">{$SEARCH_NUMPAD}</td>
					  </tr>
					{else}
						 {if $DISP_FLAG ==1}
						  <tr class="naGrid2">
							<td colspan="5" class="naError" align="center" height="30">No Records</td>
						  </tr>	
						  {/if}					
					{/if}
				</table>	
			</td>
		</tr>
    </table>
	</td>
  </tr>
</table>
