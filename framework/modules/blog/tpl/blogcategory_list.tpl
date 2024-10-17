<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="48%" height="39" class="naH1">Blog Categories</td>
    <td width="42%" align="right" valign="middle" class="naH1">
	<a href="{makeLink mod=blog pg=searchBlog}act=list{/makeLink}" class="linkOneActive"><a class="linkOneActive" href="{makeLink mod=blog pg=blog}act=form{/makeLink}"><img src="{$GLOBAL.mod_url}/images/Manage.jpg" border="0"></a></td>
    <td width="10%" align="right" valign="middle" class="naH1"><a href="{makeLink mod=blog pg=searchBlog}act=list{/makeLink}" class="linkOneActive"><img src="{$GLOBAL.mod_url}/images/search.jpg" border="0"></a></td>
  </tr>
  <tr>
    <td colspan="3"><table width=100% border=0 cellpadding="0" cellspacing="0" class="border">
  {if count($BLOG_CAT) > 0} {foreach from=$BLOG_CAT item=blogcat}
  <tr align="left"  nowrap class="forumTitle1">
    <td width="5%" height="24"  valign="middle">&nbsp;</td>
    <td colspan="3"  valign="middle"><a class="blackboldtext" href="{makeLink mod=blog pg=blog_subcategory}act=list&id={$blogcat->id}&name={$blogcat->cat_name}{/makeLink}">{$blogcat->cat_name}</a></td>
    <td  valign="middle">&nbsp;</td>
  </tr>
  <tr align="left"  nowrap class="forumTitle2">
    <td height="24"  valign="middle">&nbsp;</td>
    <td colspan="3"  valign="middle">{$blogcat->cat_desc}</td>
    <td  valign="middle">&nbsp;</td>
  </tr>
  {foreach from=$blogcat->subcat item=subcat}
  <tr class="{cycle name=bg values="forumTitle4,forumTitle3"}">
    <td height="24"  align="left"  valign="middle">&nbsp;</td>
    <td width="9%"  align="left"  valign="middle">&nbsp;</td>
    <td colspan="2"  align="left"  valign="middle"> <a class="linkOneActive" href="{makeLink mod=blog pg=bloglist}act=list&id={$subcat->id}&parent_id={$subcat->parent_id}{/makeLink}">{$subcat->cat_name}</a> =>{$subcat->cat_desc} </td>
    <td  align="center"  valign="middle">&nbsp;</td>
  </tr>
  {/foreach} {if $subcat->more == true}
  <tr align="right">
    <td height="24" nowrap class="forumTitle1"></td>
    <td colspan="3" nowrap class="forumTitle1">
      <a class="linkOneActive" href="{makeLink mod=blog pg=bloglist_all}act=list&id={$blogcat->id}{/makeLink}"><img src="{$GLOBAL.mod_url}/images/more_new.jpg" border="0"></a></td>
    <td width="4%" nowrap class="forumTitle1">&nbsp;</td>
  </tr>
  {/if} {/foreach}
  <tr class="forumTitle1">
    <td height="24"  align="center">&nbsp;</td>
    <td height="30" colspan="3"  align="center">{$CAT_NUMPAD}</td>
    <td  align="center" height="30">&nbsp;</td>
  </tr>
  {else}
  <tr class="forumTitle4">
    <td class="naError" align="center" height="24">&nbsp;</td>
    <td height="30" colspan="3" align="center" class="naError">No Records</td>
    <td class="naError" align="center" height="30">&nbsp;</td>
  </tr>
  {/if}
    </table></td>
</tr>
</table>


