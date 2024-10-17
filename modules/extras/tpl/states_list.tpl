<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_state" method="post" action="" enctype="multipart/form-data" style="margin: 0px;">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td>
	<table border="0" cellspacing="0" cellpadding="4" width="100%" align="center"> 
        <tr>
		<td nowrap class="naH1">States &amp; Taxes</td> 
          <td nowrap align="right" class="titleLink" width="100%">{if $STORE_PERMISSION.add == 'Y'}<a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=form{/makeLink}">Add New State</a>{/if}</td> 
        </tr>
       </table>
	  </td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
         {if count($STATE_LIST) > 0}
			  <tr>
			   <td colspan="4" valign=top class="naGrid1"><table width="100%"  border="0" cellspacing="2" cellpadding="2">
			  <tr>
				<td>
				{if $STORE_PERMISSION.delete == 'Y'}
				<a class="linkOneActive" href="#" onclick="javascript: document.frm_state.action='{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=delete{/makeLink}'; document.frm_state.submit();">Delete</a>{/if}</td>
				<td width="100%">&nbsp;</td>
			   <td align="center">Country&nbsp;:</td>
				  <td><select name=country_id onchange="window.location.href='{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=list{/makeLink}&country_id='+this.value">
					<option value="">-- SELECT A COUNTRY --</option>
				   {html_options values=$COUNTRY_LIST.country_id output=$COUNTRY_LIST.country_name selected=`$smarty.request.country_id`}
				   </select></td>
				<td>&nbsp;</td>
				<td nowrap><strong>Results per page:</strong></td>
				<td>{$STATE_LIMIT}</td>
			  </tr>
			</table></td>
				</tr> 
				{/if}
		{if count($STATE_LIST) > 0}
        <tr>
          <td width="5%" align="left" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_state,'id[]')"></td> 
          <td width="25%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy="name" display="State Name"}act=list&country_id={$smarty.request.country_id}{/makeLink}</td> 
          <td width="20%" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy="code" display="State Code"}act=list&country_id={$smarty.request.country_id}{/makeLink}</td>
          <td width="50%" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy="tax" display="Tax (%)"}act=list&country_id={$smarty.request.country_id}{/makeLink}</td>
		  </tr>
        {foreach from=$STATE_LIST item=row}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
         <td align="left" ><input type="checkbox" name="id[]" value="{$row->id}}"></td> 
		  <td align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=form&id={$row->id}{/makeLink}">{$row->name}</a></td> 
          <td align="left" valign="middle">{$row->code}</td> 
          <td align="left" valign="middle">{$row->tax}</td> 
		  </tr> 
        {/foreach}
        <tr> 
          <td colspan="4" class="msg" align="center" height="30">{$STATE_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="4" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table>	  
	  </td> 
  </tr> 
</table>
</form>