<form name="fruser" method="post" action="">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1"><!--Registered Users-->{$SUBNAME}</td> 
		  <td nowrap  align="right">&nbsp;</td> 
		  <td align="right" >&nbsp;</td>
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="2"> 
		<tr>
		  <td  align="right" colspan="3" class="naGrid1">Items per Page: {$LIMIT_LIST}</td> 

        </tr>
        <tr>
          <td width="32" nowrap class="naGridTitle" align="center"></td>
          <td width="200" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=member pg=user orderBy="type" display="Member Type"}act=mem_price_list&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
		  <td width="200" height="24" align="left" nowrap class="naGridTitle">{makeLink mod=member pg=user orderBy="percentage" display="Price Percentage"}act=mem_price_list&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
		  </tr>
        {if count($TYPE_LIST) > 0}
        {foreach from=$TYPE_LIST item=type}
        <tr class="{cycle values="naGrid1,naGrid2"}">
          <td valign="middle" align="center"><a class="linkOneActive" href="{makeLink mod=member pg=user}act=mem_price&id={$type->id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td valign="middle" height="24" align="left"><a class="linkOneActive" href="{makeLink mod=member pg=user}act=mem_price&id={$type->id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}">{$type->type}</a></td> 
		  <td valign="middle" height="24" align="left">{if ($type->percent)==0}None {elseif ($type->percent)<0} More {$type->percent|abs}% {else} Less {$type->percent}%  {/if} </td> 
		  </tr> 
        {/foreach}
        <tr> 
          <td colspan="3" class="msg" align="center" height="30">{$TYPE_NUM}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="3" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table></form>