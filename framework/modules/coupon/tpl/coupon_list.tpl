{literal}
<script language="javascript">
function deleteCoupon()
{ 
 document.frm_coupon.action='{/literal}{makeLink mod=store pg=coupon_coupon}act=delete{/makeLink}{literal}'; 
 document.frm_coupon.submit();
}
</script>
{/literal}
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
		<td nowrap class="naH1">Coupons</td> 
          <td nowrap align="right" class="titleLink" width="100%"><a href="{makeLink mod=store pg=coupon_coupon}act=form{/makeLink}&sId={$smarty.request.sId}&mId={$MID} ">Add New Coupon</a></td> 
        </tr>
        <tr>
          <td colspan="2" align="right" class="naGrid1"><strong>No: of items per page</strong> {$COUPON_LIMIT}</td>
        </tr> 
      </table>
	  </td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
        {if count($COUPON_LIST) > 0}
        <tr>
          <td height="24" colspan="2" align="left" nowrap class="naGridTitle">
		  	<input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_coupon,'coupon_id[]')">
		 	<a class="linkOneActive" href="#" onclick="javascript:deleteCoupon()">Delete</a>
		  </td> 
          <td width="25%" nowrap class="naGridTitle" height="24" align="left">{makeLink mod=extras pg=extras orderBy="coupon_name" display="Coupon Name"}act=list&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td> 
          <td width="15%" nowrap class="naGridTitle">{makeLink mod=extras pg=extras orderBy="coupon_start" display="Start Date"}act=list&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td>
          <td width="15%" nowrap class="naGridTitle">{makeLink mod=extras pg=extras orderBy="coupon_end" display="End Date"}act=list&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td>
       	  <td width="10%" nowrap class="naGridTitle">{makeLink mod=extras pg=extras orderBy="active" display="Active"}act=list&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}</td>
	  	  <td width="15%" nowrap class="naGridTitle">Usage</td>
		  <td width="20%" nowrap class="naGridTitle">Used</td>
	    </tr>
        {foreach from=$COUPON_LIST item=row}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
         <td width="5%" height="24" align="left" >
		 	 <input type="checkbox" name="coupon_id[]" value="{$row->coupon_id}"></td> 
		  <td width="5%" height="24" align="center" valign="middle">
		 	 {if $row->user_id==0}<a class="linkOneActive" href="{makeLink mod=store pg=coupon_coupon}act=form&id={$row->coupon_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a>{/if}</td> 
          <td height="24" align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=store pg=coupon_coupon}act=form&id={$row->coupon_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}">{$row->coupon_title}</a></td> 
          <td height="24" align="left" valign="middle">{if $row->coupon_startdate}{$row->coupon_startdate|date_format}{/if}</td> 
		  <td height="24" align="left" valign="middle">{if $row->coupon_enddate gt 0}{$row->coupon_enddate|date_format}{/if}</td> 
          <td height="24" align="left" valign="middle">{if $row->coupon_active=='Y'}Active{else}Inactive{/if}</td> 
     	  <td height="24" align="left" valign="middle">{if $row->coupon_one_time eq 0} Unlimited{else}{$row->coupon_one_time}{/if}</td> 
	      <td height="24" align="left" valign="middle">{$row->coupon_used}</td>
		  
		</tr> 
        {/foreach}
        <tr> 
          <td colspan="8" class="msg" align="center" height="30">{$COUPON_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="8" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table>	  
	  </td> 
  </tr> 
</table>
</form>