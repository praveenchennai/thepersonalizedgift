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
          <td nowrap align="right" class="titleLink" width="100%"></td> 
        </tr>
        <tr>
          <td colspan="2" align="right" class="naGrid1"><strong>Results per page</strong> {$STORE_LIMIT}</td>
        </tr> 
      </table>
	 </td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
        {if count($STORE_LIST) > 0}
        <tr>
          <td width="30%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=store pg=manage orderBy="name" display="Stores"}act=list&opt={$smarty.request.opt}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
          <td width="10%" nowrap class="naGridTitle">{makeLink mod=store pg=manage orderBy="active" display="Status"}act=list&opt={$smarty.request.opt}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td>
        </tr>
        {foreach from=$STORE_LIST item=row}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
          <td height="24" align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=store pg=manage}act={$ACT}&store_id={$row->id}&sId={$smarty.request.sId}&limit={$smarty.request.limit}&fId={$smarty.request.fId}{/makeLink}">{$row->name}</a></td> 
          <td height="24" align="left" valign="middle">{if $row->active=='Y'}Active{else}Inactive{/if}</td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="2" class="msg" align="center" height="30">{$STORE_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="2" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>