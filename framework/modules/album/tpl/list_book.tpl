<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">List Books</td> 
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=album pg=album_admin}act=add_book&link=Y{/makeLink}">Add New</a></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
        {if count($BOOK_LIST) > 0}
        <tr>
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
		  <td width="40%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=album pg=album_admin orderBy="book_title" display="Book Title"}act=list_book&link=Y{/makeLink}</td> 
          <td width="30%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=album pg=album_admin orderBy="book_author" display="Author"}act=list_book&link=Y{/makeLink}</td> 
		  <td width="30%" nowrap class="naGridTitle" height="24" align="center">{makeLink mod=album pg=album_admin orderBy="book_publisher" display="Year"}act=list_book&link=Y{/makeLink}</td> 
        </tr>
        {foreach from=$BOOK_LIST item=option}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=album pg=album_admin}act=add_book&id={$option->id}&link=Y{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=album pg=album_admin}act=book_delete&id={$option->id}&link=Y{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td> 
		  <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=album pg=album_admin}act=add_book&id={$option->id}&link=Y{/makeLink}">{$option->book_title}</a></td> 
          <td valign="middle" height="24" align="left">{$option->book_author}</td> 
		  <td valign="middle" height="24" align="center">{$option->book_year}</td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="6" class="msg" align="center" height="30">{$BOOK_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="6" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>