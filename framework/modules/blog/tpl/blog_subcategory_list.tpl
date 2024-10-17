<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="80%" height="39" class="naH1">Subcategories</td>
    <td align="right" valign="middle" class="naH1"><a href="{makeLink mod=blog pg=searchBlog}act=list{/makeLink}" class="linkOneActive"><img src="{$GLOBAL.mod_url}/images/search.jpg" border="0"></a></td>
  </tr>
  <tr>
 <td colspan="2">
 <table width=100% border=0 cellpadding="0" cellspacing="0" class="border">
 <tr align="left"  nowrap class="forumTitle1">
    <td width="0%" height="24"  valign="middle">&nbsp;</td>
    <td colspan="2"  valign="middle"><a class="linkOneActive" href="{makeLink mod=blog pg=blog_category}act=list{/makeLink}">Browse</a>&nbsp;<img src="{$GLOBAL.mod_url}/images/rightarrow.gif" width="9" height="8">&nbsp;<a class="blackboldtext" href="{makeLink mod=blog pg=blog_subcategory}act=list&id={$CAT_NAME.id}&name={$blogcat->cat_name}{/makeLink}">{$CAT_NAME.cat_name}</a></td>
    <td  valign="middle">&nbsp;</td>
    <td  valign="middle">&nbsp;</td>
  </tr>
  {if count($BLOG_SUBCAT) > 0}
   {foreach from=$BLOG_SUBCAT item=blogsubcat}
  <tr align="left"  nowrap class="subTitle">
    <td width="0%" height="24"  valign="middle">&nbsp;</td>
    <td colspan="2"  valign="middle"><a class="blackboldtext" href="{makeLink mod=blog pg=bloglist}act=list&id={$blogsubcat->id}&parent_id={$blogsubcat->parent_id}{/makeLink}">{$blogsubcat->cat_name}</a>&nbsp;•{$blogsubcat->countBlog}</td>
    <td  valign="middle">&nbsp;</td>
    <td  valign="middle">&nbsp;</td>
  </tr>
  <tr align="left"  nowrap class="naGrid2">
    <td height="24"  valign="middle">&nbsp;</td>
    <td colspan="2"  valign="middle">{$blogsubcat->cat_desc}</td>
    <td  valign="middle">&nbsp;</td>
    <td  valign="middle">&nbsp;</td>
  </tr>
  {/foreach}
  <tr class="forumTitle1">
    <td height="24"  align="center">&nbsp;</td>
    <td height="30" colspan="3"  align="center">{$SUBCAT_NUMPAD}</td>
    <td  align="center" height="30">&nbsp;</td>
  </tr>
  {else}
  <tr class="naGrid2">
    <td class="naError" align="center" height="24">&nbsp;</td>
    <td height="30" colspan="3" align="center" class="naError">No Records</td>
    <td class="naError" align="center" height="30">&nbsp;</td>
  </tr>
  {/if}
    </table>	
 </td>
</tr>
</table>


