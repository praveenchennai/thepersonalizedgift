<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table border="0" cellspacing="0" cellpadding="4" width="100%" align="center"> 
        <tr>
		<td nowrap class="naH1">{$smarty.request.sId}</td> 
          <td nowrap align="right" class="titleLink" width="100%"><a href="{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=add_currency{/makeLink}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}">Add New</a></td> 
        </tr>
        <tr>
          <td colspan="2" align="right" class="naGrid1">&nbsp;</td>
        </tr> 
      </table>
	 </td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
        {if count($CURRENCY_LIST) > 0}
        <tr>
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="30%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy="currency_name" display="Currency Name"}act={$smarty.request.act}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
          <td width="30%" nowrap class="naGridTitle">Symbol</td>
          <td width="10%" nowrap class="naGridTitle" align="center">Active</td>
        </tr>
		
        {foreach from=$CURRENCY_LIST item=row}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=add_currency&id={$row.cid}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=delete_currency&id={$row.cid}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td> 
          <td height="24" align="left" valign="middle"><a class="linkOneActive"  href="{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=add_currency&id={$row.cid}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}">{$row.currency_shorttxt}</a></td> 
          <td height="24" align="left" valign="middle">{$row.symbol}</td> 
          <td height="24" align="center" valign="middle"> {if $row->active.gif=='Y'} 
			<img border="0" title="Activate"  src="{$smarty.const.SITE_URL}/img/active{$row.active.gif}.gif"/>
		{else}
			<img border="0" title="Deactivate"  src="{$smarty.const.SITE_URL}/img/active{$row.active.gif}.gif"/>
		{/if}</td> 
		  
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="5" class="msg" align="center" height="30">{$STORE_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="5" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>