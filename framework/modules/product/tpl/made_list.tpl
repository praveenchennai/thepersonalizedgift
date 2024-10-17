<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_made" method="post" action="" enctype="multipart/form-data" style="margin: 0px;">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1"><!--Zone-->{$smarty.request.sId}
		  <input type="hidden" name="limit"  value="{$smarty.request.limit}"/></td> 
          <td nowrap align="right" class="titleLink">{if $STORE_PERMISSION.add == 'Y'}<a href="{makeLink mod=$MOD pg=$PG}act=form{/makeLink}&sId={$smarty.request.sId}&fId={$smarty.request.fId} ">Add New {$smarty.request.sId}  </a>{/if}</td> 
        </tr> 
      </table></td> 
  </tr>
  {if count($MADE_LIST) > 0}
  <tr>
    <td class="naGrid1"><table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="10%">{if $STORE_PERMISSION.delete == 'Y'}<a class="linkOneActive" href="#" onclick="javascript: document.frm_made.action='{makeLink mod=$MOD pg=$PG}act=delete&fId={$smarty.request.fId}&sId={$smarty.request.sId}{/makeLink}'; document.frm_made.submit();">Delete</a>{/if}</td>
    <td width="50%">&nbsp;</td>
    <td width="19%" nowrap> Results per page: </td>
	<td width="21%">{$MADE_LIMIT}</td>
  </tr>
</table>
</td>
  </tr> 
  {/if}
  <tr> 
    <td><table width=100% border=0 cellpadding="0" cellspacing="0"> 
		{if count($MADE_LIST) > 0}
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_made,'zone_id[]')"></td>
          <td width="96%" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=$MOD pg=$PG orderBy=name display="`$smarty.request.sId` Name"}act=list&fId={$smarty.request.fId}&sId={$smarty.request.sId}{/makeLink}</td>
		  </tr>
        {foreach from=$MADE_LIST item=made}
        <tr class="{cycle name=bg values="naGrid1,naGrid2"}">
          <td  valign="middle"  align="center"><input type="checkbox" name="zone_id[]" value="{$made->id}"></td> 
          <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act=form&id={$made->id}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&limit={$smarty.request.limit}{/makeLink}">{$made->name} </a></td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="2" class="msg" align="center" height="30">{$MADE_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="2" class="naError" align="center" height="30">No Records</td> 
        </tr>
		 {/if}
      </table></td> 
  </tr> 
</table>
</form>