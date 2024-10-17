{if $MONOGRAM=='NO'}
<table align="center" width="100%" cellpadding="0" border="1">
<tr>
  <td colspan=3 valign=center class="naGrid1" height="25"><table><tr><td><input type="checkbox" name="sel_all_{$smarty.request.category_id}" id="sel_all_{$smarty.request.category_id}" onClick="javascript: selectCheck('{$smarty.request.category_id}','{foreach from=$GRP_ACCESSORY item=accessories_item name=fooS}{$accessories_item.id}{if $smarty.foreach.fooS.index<(count($GRP_ACCESSORY)-1)},{/if}{/foreach}')"></td><td><strong>{$CAT_NAME}</strong>&nbsp;<a href="#"  onClick="javascript:popUp3('0','{$smarty.request.category_id}','{$smarty.request.product_id}'); return false;">(New {$FOLDER_NAME})</a></td></tr></table></td>
</tr>
<tr>
  <td colspan=3 valign=center><table width="100%"  border="0" cellpadding="5" cellspacing="3" class=naGrid2>
    <tr>{foreach from=$GRP_ACCESSORY item=accessories_item name=foo} {if $smarty.foreach.foo.index is div by 3} </tr>
    <tr>{/if}
        <td width="4%" nowrap valign="middle">{html_checkboxes id="accessory_`$smarty.request.category_id`_`$accessories_item.id`" name="accessory[`$STORE`]" values=`$accessories_item.new_item_id`_`$GROUPID` selected=$AVAILABLE_ACCESSORIES.accessory_id onClick='newSelect(this.value);'}</td>
        <td  width="20%" align="left"><a href="#"  onClick="javascript:popUp1('{$accessories_item.id}','{$smarty.request.product_id}'); return false;">{$accessories_item.name}</a></td>
        {if $accessories_item.type == Color}
        <td width="8%" valign="middle">
          <table width="80%"  border="0" cellspacing="0" cellpadding="0" style="cursor:hand;" onClick="javascript:popUpColor('{$accessories_item.id}','{$smarty.request.product_id}'); return false;">
           {if $accessories_item.color1!=''|| $accessories_item.color2!='' || $accessories_item.color3!=''}
            <tr>
              <td height="10" bgcolor="{if $accessories_item.color1!=''}{$accessories_item.color1}{elseif $accessories_item.color2!=''}{$accessories_item.color2}{else}{$accessories_item.color3}{/if}"></td>
            </tr>
            <tr>
              <td height="10" bgcolor="{if $accessories_item.color2!=''}{$accessories_item.color2}{elseif $accessories_item.color1!=''}{$accessories_item.color1}{else}{$accessories_item.color3}{/if}"></td>
            </tr>
            <tr>
              <td height="10" bgcolor="{if $accessories_item.color3!=''}{$accessories_item.color3}{elseif $accessories_item.color2!=''}{$accessories_item.color2}{else}{$accessories_item.color1}{/if}"></td>
            </tr>
			{else}
			<tr>
			<td>no color</td>
			</tr>
			{/if}
        </table></td>
        {else}
        <td width="8%" nowrap valign="middle">&nbsp;</td>
        {/if}
        <td width="1%" nowrap valign="middle">&nbsp;</td>
        {/foreach}
    </table></td>
</tr>
</table>
{else}
<table align="center" width="100%" cellpadding="0" border="0">
{foreach from=$GRP_ACCESSORY item=item_accessories name=fofo}
<tr><td>
<table align="center" width="100%" cellpadding="0" border="1">
<tr>
  <td colspan=3 valign=center class="naGrid1" height="25"><table><tr><td><input type="checkbox" name="sel_all_{$item_accessories.cat_id}" id="sel_all_{$item_accessories.cat_id}" onClick="javascript: selectCheck('{$item_accessories.cat_id}','{foreach from=$item_accessories.data item=accessories_item name=fooS}{$accessories_item.id}{if $smarty.foreach.fooS.index<(count($item_accessories.data)-1)},{/if}{/foreach}')"></td><td><strong>{$item_accessories.cat_name}</strong>&nbsp;<a href="#"  onClick="javascript:popUp3('0','{$smarty.request.category_id}','{$smarty.request.product_id}'); return false;">(New {$FOLDER_NAME})</a></td></tr></table></td>
</tr>
<tr>
  <td colspan=3 valign=center><table width="100%"  border="0" cellspacing="3" cellpadding="5" class=naGrid2>
	  <tr>{foreach from=$item_accessories.data item=accessories_item name=foo} {if $smarty.foreach.foo.index is div by 3} </tr>
	  <tr>{/if}
		  <td width="5%" nowrap valign="middle">{html_checkboxes id="accessory_`$item_accessories.cat_id`_`$accessories_item.id`" name="accessory[`$STORE`]" values=`$accessories_item.new_item_id`_`$GROUPID` selected=$AVAILABLE_ACCESSORIES.accessory_id onClick='newSelect(this.value);'}</td>
		  <td  width="27%" align="left"><a href="#"  onClick="javascript:popUp1('{$accessories_item.id}','{$smarty.request.product_id}'); return false;">{$accessories_item.name}</a></td>
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
</td></tr>
{/foreach}
</table>
{/if}