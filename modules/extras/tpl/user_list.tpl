<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table border="0" cellspacing="0" cellpadding="4" width="100%" align="center"> 
        <tr>
		<td nowrap class="naH1">Coupons</td> 
          <td nowrap align="right" class="titleLink" width="100%"><a href="{makeLink mod=extras pg=extras}act=form{/makeLink}">Add New</a></td> 
        </tr>
        <tr>
          <td colspan="2" align="right" class="naGrid1"><strong>No: of items per page</strong> {$COUPON_LIMIT}</td>
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
        {if count($COUPON_LIST) > 0}
        <tr>
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="5%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="20%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=extras pg=extras orderBy="coupon_no" display="Coupon No"}act=list{/makeLink}</td> 
          <td width="20%" nowrap class="naGridTitle">{makeLink mod=extras pg=extras orderBy="coupon_start" display="Start Date"}act=list{/makeLink}</td>
          <td width="20%" nowrap class="naGridTitle">{makeLink mod=extras pg=extras orderBy="coupon_end" display="End Date"}act=list{/makeLink}</td>
       	  <td width="20%" nowrap class="naGridTitle">{makeLink mod=extras pg=extras orderBy="active" display="Active"}act=list{/makeLink}</td>
	    </tr>
        {foreach from=$COUPON_LIST item=row}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=extras pg=extras}act=form&id={$row->id}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
          <td valign="middle" height="24" align="center"><a class="linkOneActive" href="{makeLink mod=extras pg=extras}act=delete&id={$row->id}{/makeLink}"onclick="javascript: return confirm('Are you sure to delete?')"><img title="Delete" alt="Delete" src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td> 
          <td height="24" align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=extras pg=extras}act=form&id={$row->id}{/makeLink}">{$row->coupon_no}</a></td> 
          <td height="24" align="left" valign="middle">{$row->coupon_start}</td> 
		  <td height="24" align="left" valign="middle">{$row->coupon_end}</td> 
          <td height="24" align="left" valign="middle">{if $row->active=='Y'}Active{else}Inactive{/if}</td> 
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="5" class="msg" align="center" height="30">{$COUPON_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="5" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>