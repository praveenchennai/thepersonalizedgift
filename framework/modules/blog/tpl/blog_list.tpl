<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="80%" height="39" class="naH1">Subcategories</td>
    <td align="right" valign="middle" class="naH1"><a href="{makeLink mod=blog pg=searchBlog}act=list{/makeLink}" class="linkOneActive"><img src="{$GLOBAL.mod_url}/images/search.jpg" border="0"></a></td>
  </tr>
  <tr>
    <td colspan="2">
 <table width=100% border=0 cellpadding="0" cellspacing="0" class="border">
 <tr align="left"  nowrap class="forumTitle1">
    <td width="2%" height="24"  valign="middle">&nbsp;</td>
    <td colspan="2"  valign="middle"><a class="linkOneActive" href="{makeLink mod=blog pg=blog_category}act=list{/makeLink}">Browse</a>&nbsp;<img src="{$GLOBAL.mod_url}/images/rightarrow.gif" width="9" height="8">&nbsp;<a class="blackboldtext" href="{makeLink mod=blog pg=blog_subcategory}act=list&id={$CAT_NAME.id}{/makeLink}">{$CAT_NAME.cat_name}</a>&nbsp;<img src="{$GLOBAL.mod_url}/images/rightarrow.gif" width="9" height="8">&nbsp;<a class="blackboldtext" href="{makeLink mod=blog pg=bloglist}act=list&id={$SUBCAT_NAME.id}&parent_id={$SUBCAT_NAME.parent_id}{/makeLink}">{$SUBCAT_NAME.cat_name}</a></td>
    <td width="21%"  valign="middle">&nbsp;</td>
    <td width="2%"  valign="middle">&nbsp;</td>
  </tr>
  {if count($BLOG_LIST) > 0}
   {foreach from=$BLOG_LIST item=bloglist}
  <tr align="left"  nowrap class="subTitle">
    <td width="2%" height="24" rowspan="5"  valign="middle">&nbsp;</td>
    <td width="14%"  valign="middle">&nbsp;</td>
    <td colspan="2" rowspan="5"  valign="top">
	<br><a class="blackboldtext" href="{$bloglist->uname}">{$bloglist->blog_name}</a>&nbsp;
	<br>
	<span class="bodytext">{$bloglist->blog_description}</span>
	</td>
    <td rowspan="5"  valign="middle">&nbsp;</td>
  </tr>
  <tr align="left"  nowrap class="subTitle">
    <td  valign="middle">
	{if $bloglist->image == Y}						
		<img src="{$GLOBAL.modbase_url}/member/images/userpics/thumb/{$bloglist->user_id}.jpg" border="0" width="75" height="75">
	{else}						
		<img src="{$smarty.const.SITE_URL}/templates/default/images/nophoto.jpg" border="0" width="75" height="75">
	{/if}
	</td>
  </tr>
  <tr align="left"  nowrap class="subTitle">
    <td  valign="middle">&nbsp;</td>
  </tr>
  <tr align="left"  nowrap class="subTitle">
    <td  valign="middle" class="blackboldtext">{$bloglist->username}</td>
  </tr>
  <tr align="left"  nowrap class="subTitle">
    <td  valign="middle">&nbsp;</td>
  </tr>
  <tr align="left"  nowrap class="naGrid2">
    <td height="19"  valign="middle">&nbsp;</td>
    <td  valign="middle">&nbsp;</td>
    <td  valign="middle">&nbsp;</td>
    <td  valign="middle">&nbsp;</td>
    <td  valign="middle">&nbsp;</td>
  </tr>
  {/foreach}
  <tr class="forumTitle1">
    <td height="30"  align="center">&nbsp;</td>
    <td height="30" colspan="3"  align="center">{$BLOG_NUMPAD}</td>
    <td  align="center" height="30">&nbsp;</td>
  </tr>
  {else}
  <tr class="naGrid2">
    <td class="naError" align="center" height="30">&nbsp;</td>
    <td height="30" colspan="3" align="center" class="naError">No Records</td>
    <td class="naError" align="center" height="30">&nbsp;</td>
  </tr>
  {/if}
    </table>	
 </td>
</tr>
</table>


