<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%" height="39" class="naH1">Subscribed Blogs </td>
    <td align="right" valign="middle" class="naH1"><a href="{makeLink mod=blog pg=searchBlog}act=list{/makeLink}" class="linkOneActive"><img src="{$GLOBAL.mod_url}/images/search.jpg" border="0"></a></td>
  </tr>
  <tr>
    <td colspan="2">
 <table width=100% border=0 cellpadding="0" cellspacing="0" class="border">
  {if count($BLOG_SUBSCRIBE) > 0}
   {foreach from=$BLOG_SUBSCRIBE item=blogsubscribe}   
	  <tr align="left"  nowrap class="subTitle">
		<td width="3%" height="24"  valign="middle">&nbsp;</td>
		<td colspan="2"  valign="middle"><!--a class="blackboldtext" href="{makeLink mod=blog pg=blog_userentry}act=list&user_id={$blogsubscribe->user_id}&subcat_id={$blogsubscribe->subcat_id}&parent_id={$blogsubscribe->cat_id}{/makeLink}"--><a class="blackboldtext" href="{$blogsubscribe->uname}">{$blogsubscribe->blog_name}</a></td>
		<td width="41%"  valign="middle">&nbsp;</td>
		<td width="3%"  valign="middle">&nbsp;</td>
	  </tr>
	  <tr align="left"  nowrap class="naGrid2">
		<td height="24"  valign="middle">&nbsp;</td>
		<td colspan="2"  valign="middle">{$blogsubscribe->blog_description}</td>
		<td  valign="middle">&nbsp;</td>
		<td  valign="middle">&nbsp;</td>
	  </tr>
  {/foreach}
	  <tr class="naGridTitle">
		<td height="30"  align="center">&nbsp;</td>
		<td height="30" colspan="3"  align="center">{$SUBSCRIBE_NUMPAD}</td>
		<td  align="center" height="30">&nbsp;</td>
	  </tr>
  {else}
	  <tr class="naGrid1">
		<td align="center" height="30">&nbsp;</td>
		<td height="30" colspan="3" align="center" >No Records</td>
		<td align="center" height="30">&nbsp;</td>
	  </tr>
  {/if}
    </table>	
 </td>
</tr>
</table>


