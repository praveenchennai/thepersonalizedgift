<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_price" method="post" action="" enctype="multipart/form-data" style="margin: 0px;">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">{$smarty.request.sId} <input type="hidden" name="limit"  value="{$smarty.request.limit}"/></td> 
          <td nowrap align="right" class="titleLink">{if $STORE_PERMISSION.add == 'Y'}<a href="{makeLink mod=$MOD pg=$PG}act=form{/makeLink}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&limit={$smarty.request.limit}">Add New {$smarty.request.sId}</a>{/if}</td> 
        </tr> 
      </table></td> 
  </tr>
  {if count($PRICE_LIST) > 0}
  <tr>
    <td class="naGrid1"><table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td>{if $STORE_PERMISSION.delete == 'Y'}<a class="linkOneActive" href="#" onclick="javascript: document.frm_price.action='{makeLink mod=$MOD pg=price}act=delete&fId={$smarty.request.fId}&sId={$smarty.request.sId}&limit={$smarty.request.limit}{/makeLink}'; document.frm_price.submit();">Delete</a>{/if}</td>
    <td width="100%">&nbsp;</td>
    <td nowrap>No of Item In a Page :</td>
	<td>{$PRICE_LIMIT}</td>
  </tr>
</table>
</td>
  </tr> 
  {/if}
  <tr> 
    <td><table width=100% border=0 cellpadding="2" cellspacing="0"> 
		{if count($PRICE_LIST) > 0}
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_price,'price_id[]')"></td>
          <td width="35%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=name display="`$smarty.request.sId` Name"}act=list&fId={$smarty.request.fId}&sId={$smarty.request.sId}{/makeLink}</td>
		  <td width="61%" align="left" nowrap class="naGridTitle">&nbsp;</td>
	    </tr>
        {foreach from=$PRICE_LIST item=price}
        <tr class="{cycle name=bg values="naGrid1,naGrid2"}">
          <td  valign="middle"  align="center"><input type="checkbox" name="price_id[]" value="{$price->id}"></td> 
          <td valign="middle" align="left"><a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act=form&id={$price->id}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&limit={$smarty.request.limit}{/makeLink}">{$price->name} </a></td> 
          <td valign="middle" align="left">{if $price->default == Y}Default{/if}</td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="3" class="msg" align="center" height="30">{$PRICE_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="3" class="naError" align="center" height="30">No Records</td> 
        </tr>
		 {/if}
      </table></td> 
  </tr> 
</table>
</form>