<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form action="" method="POST" enctype="multipart/form-data" name="frm_bulk_price" style="margin: 0px;"> 
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">{$smarty.request.sId}</td> 
          <td nowrap align="right" class="titleLink">{if $STORE_PERMISSION.add == 'Y'}<a href="{makeLink mod=$MOD pg=$PG}act=form&id={$smarty.request.id}&sId={$smarty.request.sId}&mId={$MID}&fId={$smarty.request.fId}{/makeLink}">Add {$smarty.request.sId}</a>{/if}</td> 
        </tr> 
      </table></td> 
  </tr>
  {if count($BULK_ITEMS) > 0}
  <tr>
    <td class="naGrid1"><table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td>{if $STORE_PERMISSION.delete == 'Y'}<a class="linkOneActive" href="#" onclick="javascript: if(confirm('Are you sure want to delete the bulk price for the product')){ldelim} document.frm_bulk_price.action='{makeLink mod=product pg=bulk}act=bulk_delete&sId={$smarty.request.sId}&mId={$MID}&fId={$smarty.request.fId}{/makeLink}'; document.frm_bulk_price.submit();{rdelim}">Delete</a>{/if}</td>
    <td width="100%">&nbsp;</td>
    <td nowrap>No of Item In a Page :</td>
	<td>{$BULK_ITEMS_LIMIT}</td>
  </tr>
</table>
</td>
  </tr> 
  {/if}
  <tr> 
    <td><table width=100% border=0 cellpadding="2" cellspacing="0"> 
		{if count($BULK_ITEMS) > 0}
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_bulk_price,'id[]')"></td>
          <td width="20%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=min_qty display="Minimum Quantity"}act=form_list&id={$smarty.request.id}&sId={$smarty.request.sId}&mId={$MID}&fId={$smarty.request.fId}{/makeLink}</td>
		  <td width="20%" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=max_qty display="Maximum Quantity"}act=form_list&id={$smarty.request.id}&sId={$smarty.request.sId}&mId={$MID}&fId={$smarty.request.fId}{/makeLink}</td>
		  <td width="20%" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=unit_price display="Unit Price"}act=form_list&id={$smarty.request.id}&sId={$smarty.request.sId}&mId={$MID}&fId={$smarty.request.fId}{/makeLink}</td>
		  <td width="36%" align="left" nowrap class="naGridTitle">&nbsp;</td>
	    </tr>
        {foreach from=$BULK_ITEMS item=bulk}
        <tr class="{cycle name=bg values="naGrid1,naGrid2"}">
          <td  valign="middle"  align="center"><input type="checkbox" name="id[]" value="{$bulk->id}"></td> 
          <td valign="middle" align="left">{$bulk->min_qty}</td> 
          <td valign="middle" align="left">{$bulk->max_qty}</td>
          <td valign="middle" align="left">{$bulk->unit_price}</td>
          <td valign="middle" align="left"><a class="linkOneActive" href="{makeLink mod=product pg=bulk}act=form&id={$smarty.request.id}&bid={$bulk->id}&sId={$smarty.request.sId}&mId={$MID}&fId={$smarty.request.fId}{/makeLink}">View</td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="5" class="msg" align="center" height="30">{$BULK_ITEMS_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="5" class="naError" align="center" height="30">No Records</td> 
        </tr>
		 {/if}
      </table></td> 
  </tr> 
</table>
</form> 
