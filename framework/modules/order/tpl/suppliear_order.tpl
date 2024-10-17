<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_sup_order" method="post" action="" enctype="multipart/form-data" style="margin: 0px;">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">{$smarty.request.sId}Assigned Orders 
            <input type="hidden" name="limit"  value="{$smarty.request.limit}"/>
		  </td> 
          <td nowrap align="right" class="titleLink">		  
		  <a href="{makeLink mod=$MOD pg=$PG} act=form&sId={$smarty.request.sId}&mId={$MID}&fId={$smarty.request.fId}&limit={$smarty.request.limit}{/makeLink} "></a>		  </td> 
		
        </tr> 
      </table></td> 
  </tr>
  {if count($ASSIGN) > 0}		
  <tr>
    <td class="naGrid1"><table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td></td>
    <td width="100%">&nbsp;</td>
    <td nowrap><strong>Results per page:</strong></td>
	<td>{$SUP_LIMIT}</td>
  </tr>
</table>
</td>
  </tr> 
  {/if}
  <tr> 
    <td><table width=100% border=0 cellpadding="2" cellspacing="0"> 
		{if count($ASSIGN) > 0}
	    <tr>
	      <td  height="24" align="left" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy=name display="Product Name"}act=assigned_details&fId={$smarty.request.fId}&sId={$smarty.request.sId}{/makeLink}</td>
          <td height="24" align="left" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy=quantity  display="Quantity"}act=assigned_details&fId={$smarty.request.fId}&amp&sId={$smarty.request.sId}{/makeLink}</td>
		  <td  height="24" align="left" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy=weight display="Weight"}act=assigned_details&fId={$smarty.request.fId}&sId={$smarty.request.sId}{/makeLink}</td>
          <td height="24" align="left" nowrap class="naGridTitle">{makeLink mod=$smarty.request.mod pg=$smarty.request.pg orderBy=assigned_date display="Assigned Date"}act=assigned_details&fId={$smarty.request.fId}&amp&sId={$smarty.request.sId}{/makeLink}</td>
		</tr>
        {foreach from=$ASSIGN item=sup_order}
        <tr class="{cycle values="naGrid1,naGrid2"}">
			<td valign="middle" height="24" align="left">{$sup_order->name}</td> 
			<td valign="middle" height="24" align="left">{$sup_order->quantity}</td> 
			<td valign="middle" height="24" align="left">{$sup_order->weight}</td> 
			<td valign="middle" height="24" align="left">{$sup_order->assigned_date}</td> 
		</tr> 
        {/foreach}
        <tr> 
          <td colspan="2" class="msg" align="center" height="30">{$SUP_NUMPAD}</td> 
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