<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_bulk" method="post" action="" enctype="multipart/form-data" style="margin: 0px;">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">{$smarty.request.sId}</td> 
          <td nowrap align="right" class="titleLink">&nbsp;</td> 
        </tr> 
      </table></td> 
  </tr>
  {if count($BULK_LIST) > 0}
  <tr>
    <td class="naGrid1"><table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td>{if $STORE_PERMISSION.delete == 'Y'}<a class="linkOneActive" href="#" onclick="javascript: if(confirm('Are you sure want to delete the suppliers for the product')){ldelim} document.frm_bulk.action='{makeLink mod=product pg=index}act=delete&fId={$smarty.request.fId}&sId={$smarty.request.sId}{/makeLink}'; document.frm_bulk.submit();{rdelim}">Delete</a>{/if}</td>
    <td width="100%">&nbsp;</td>
    <td nowrap>No of Item In a Page :</td>
	<td>{$BULK_LIMIT}</td>
  </tr>
</table>
</td>
  </tr> 
  {/if}
  <tr> 
    <td><table width=100% border=0 cellpadding="2" cellspacing="0"> 
		{if count($BULK_LIST) > 0}
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_bulk,'id[]')"></td>
          <td width="96%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=name display="Product Name"}act=supply&fId={$smarty.request.fId}&sId={$smarty.request.sId}{/makeLink}</td>
		  <td width="96%" align="left" nowrap class="naGridTitle">&nbsp;</td>
	    </tr>
        {foreach from=$BULK_LIST item=product}
        <tr class="{cycle name=bg values="naGrid1,naGrid2"}">
          <td  valign="middle"  align="center"><input type="checkbox" name="id[]" value="{$product->id}"></td> 
          <td valign="middle" align="left"><!--a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act=supDetails&id={$product->id}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&limit={$smarty.request.limit}{/makeLink}"-->{$product->name}<!--/a--></td> 
          <td valign="middle" align="left">
			<!--<select name="suppliers[{$product->id}]" style="width:200">
				<option value="0"> ---No Supplier--- </option>
				{foreach from=$SUPPLIERS item=supli}
				<option value="{$supli->id}" {if $supli->id==$product->supply_id }SELECTED{/if}>{$supli->first_name} {$supli->last_name}</option>
				{/foreach}
			 </select>-->
		 </td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="3" class="msg" align="center" height="30">{$BULK_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="3" class="naError" align="center" height="30">No Records</td> 
        </tr>
		 {/if}
      </table></td> 
  </tr> 
</table>
<br>
{if $STORE_PERMISSION.edit == 'Y'}
<table width=80% border=0 align="center" cellpadding=5 cellspacing=0 class=naBrDr>
             <tr>
               <td colspan=3 class="naGridTitle"><span class="group_style">Mass Update</span></td>
             </tr>
             <tr class=naGrid1>
               <td height="10" colspan="3" align="right" valign=top></td>
             </tr>
             <tr class=naGrid1>
               <td width="40%" align="right" valign=top>Suppliers</td>
               <td width="1" valign=top>:</td>
               <td><select name="mass_suppliers[]" style="width:200" multiple size="15">
				  	<option value="0"> ---No Supplier--- </option>
					{foreach from=$SUPPLIERS item=supli}
					<option value="{$supli->id}">{$supli->first_name} {$supli->last_name}</option>
		 			{/foreach}
	             </select>
				 </td>
             </tr>
			 <tr class="naGridTitle">
    <td colspan=3 valign="center"><div align=center><input name="btnsubmit" type='submit' class="naBtn" value="Update">&nbsp;<input name="btnsubmit" type='Reset' class="naBtn" value="Reset"></div></td>
  </tr></table>
<br>
  {/if}
</form>