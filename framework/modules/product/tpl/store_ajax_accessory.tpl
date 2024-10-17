<table align="center" width="100%" cellpadding="0" border="1">
<tr>
  <td colspan=3 valign=center class="naGrid1" height="25"><table><tr><td><input type="checkbox" name="sel_all_{$smarty.request.category_id}" id="sel_all_{$smarty.request.category_id}" onClick="javascript: selectCheck('{$smarty.request.category_id}','{foreach from=$GRP_ACCESSORY item=accessories_item name=fooS}{$accessories_item.id}{if $smarty.foreach.fooS.index<(count($GRP_ACCESSORY)-1)},{/if}{/foreach}')"></td><td><strong>{$CAT_NAME}</strong></td></tr></table></td>
</tr>
<tr>
  <td colspan=3 valign=center><table width="100%"  border="0" cellspacing="3" cellpadding="5" class=naGrid2>
	  <tr>{foreach from=$GRP_ACCESSORY item=accessories_item name=foo} {if $smarty.foreach.foo.index is div by 3} </tr>
	  <tr>{/if}
		  <td width="5%" nowrap valign="middle">{html_checkboxes id="accessory_`$smarty.request.category_id`_`$accessories_item.id`" name="accessory[`$STORE`]" values=`$accessories_item.new_item_id`_`$GROUPID` selected=$AVAILABLE_ACCESSORIES.accessory_id onClick='newSelect(this.value);'}</td>
		  <td  width="27%" align="left"><a href="#"  onClick="javascript:popUp1('{$accessories_item.id}','{$smarty.request.id}'); return false;">{$accessories_item.name}</a></td>
		  {/foreach}{if $smarty.foreach.foo.index mod 3 eq 0}
		  <td width="5%" nowrap valign="middle">&nbsp;</td>
		  <td  width="27%" align="left">&nbsp;</td>
		  <td width="5%" nowrap valign="middle">&nbsp;</td>
		  <td  width="27%" align="left">&nbsp;</td>
		  {/if}
		  {if $smarty.foreach.foo.index mod 3 eq 1}
		  <td width="5%" nowrap valign="middle">&nbsp;</td>
		  <td  width="27%" align="left">&nbsp;</td>
		  {/if}</tr>
  </table></td>
</tr>
</table>