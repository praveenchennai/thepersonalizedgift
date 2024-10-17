<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
 <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_coupon" method="post" action="" enctype="multipart/form-data" style="margin: 0px;">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td>
	<table border="0" cellspacing="0" cellpadding="4" width="100%" align="center"> 
        <tr>
		<td width="8%" nowrap class="naH1">Gift Certificate</td> 
          <td nowrap align="right" class="titleLink" width="92%">
		  	Order Number:<input type="text" name="order_search" size="10">
			<input name="btn_order_search" type="submit" class="naBtn" value="Search">&nbsp;&nbsp;
		  	Certificate Number:<input type="text" name="certi_search" size="10">
			<input name="btn_certi_search" type="submit" class="naBtn" value="Search">&nbsp;&nbsp;
		  </td> 
        </tr>
        <tr>
          <td align="left" class="naGrid1">
		  <select name=id onchange="window.location.href='{makeLink mod=extras pg=certificate}act=viewusercertificate{/makeLink}&id='+this.value" class="input">
			<option value="">-- SELECT GIFT CERTIFICATE --</option>
			{html_options values=$LOAD_PRODUCT.id output=$LOAD_PRODUCT.name selected=`$smarty.request.id`}
		 </select>
		  </td>
          <td align="right" class="naGrid1">
		  	<a class="linkOneActive" href="{makeLink mod=extras pg=certificate}act=form&id={$row->certi_id}{/makeLink}">Add Gift Certificate</a>&nbsp;&nbsp;
			<strong>No: of items per page</strong>{$USERCERTIFICATE_LIMIT}
		  </td>
        </tr> 
      </table>
	  </td> 
  </tr> 
  <tr> 
    <td>
	<table border=0 width=100% cellpadding="5" cellspacing="0"> 
        {if count($USERCERTIFICATE_LIST) > 0}
        <tr>
		  <td width="3%" nowrap class="naGridTitle" height="24" align="left">&nbsp;</td>
		  <td width="3%" nowrap class="naGridTitle" height="24" align="left">&nbsp;</td>
		  <td width="10%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=extras pg=certificate orderBy="b.name" display="Certificate Name"}act=viewusercertificate&id={$smarty.request.id}{/makeLink}</td> 
		  <td width="10%" nowrap class="naGridTitle">{makeLink mod=extras pg=certificate orderBy="certi_number" display="Number"}act=viewusercertificate&id={$smarty.request.id}{/makeLink} </td>
		  <td width="10%" nowrap class="naGridTitle">{makeLink mod=extras pg=certificate orderBy="certi_amount" display="Amount"}act=viewusercertificate&id={$smarty.request.id}{/makeLink} </td>
		  <td width="10%" nowrap class="naGridTitle">{makeLink mod=extras pg=certificate orderBy="certi_startdate" display="Start Date"}act=viewusercertificate&id={$smarty.request.id}{/makeLink}	</td>
		  <td width="10%" nowrap class="naGridTitle">{makeLink mod=extras pg=certificate orderBy="certi_enddate" display="Expiry date"}act=viewusercertificate&id={$smarty.request.id}{/makeLink}	</td>
		  <td width="10%" nowrap class="naGridTitle">{makeLink mod=extras pg=certificate orderBy="a.active" display="Status"}act=viewusercertificate&id={$smarty.request.id}{/makeLink}	</td>	
		  <td width="10%" nowrap class="naGridTitle"></td>
		</tr>
        {foreach from=$USERCERTIFICATE_LIST item=row} 
        <tr class="{cycle values="naGrid1,naGrid2"}">
			<td height="24" align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=extras pg=certificate}act=form&id={$row->certi_id}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td>
			<td height="24" align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=extras pg=certificate}act=certiDelete&id={$row->certi_id}{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td>
			<td height="24" align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=extras pg=certificate}act=form&id={$row->certi_id}{/makeLink}">{$row->name}</a></td> 
			<td height="24" align="left" valign="middle">{$row->certi_number}</td> 
			<td height="24" align="left" valign="middle">{$row->certi_amount}</td> 
			<td height="24" align="left" valign="middle">{$row->certi_startdate}</td> 
			<td height="24" align="left" valign="middle">{$row->certi_enddate}</td>
			<td height="24" align="left" valign="middle">{if $row->newstatus=='Y'}Active{else}Inactive{/if}</td>
			<td height="24" align="left" valign="middle">{if $row->newstatus=='Y'}<a class="linkOneActive" href="{makeLink mod=extras pg=certificate}act=viewhistory&id={$row->certi_id}{/makeLink}">View History</a>{/if}</td> 
		</tr> 
        {/foreach}
        <tr> 
          <td colspan="10" class="msg" align="center" height="30">{$USERCERTIFICATE_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="10" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table>	  
	  </td> 
  </tr> 
</table>
</form>