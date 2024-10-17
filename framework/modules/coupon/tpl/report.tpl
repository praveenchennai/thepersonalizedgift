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
		<td nowrap class="naH1">Report</td> 
<!--          <td nowrap align="right" class="titleLink" width="100%"><a href="{makeLink mod=store pg=coupon_coupon}act=form{/makeLink}&sId={$smarty.request.sId}&mId={$MID} ">Add New Coupon</a></td> 
-->        </tr>
        <tr>
          <td colspan="2" align="right" class="naGrid1"> {if count($REPORT_LIST) > 0}<strong>No: of items per page</strong> {$REPORT_LIMIT}{/if}</td>
        </tr> 
      </table>
	  </td> 
  </tr> 
  <tr> 
    <td><table border=0 width=100% cellpadding="5" cellspacing="0"> 
        {if count($REPORT_LIST) > 0}
        <tr>

          <td width="15%" nowrap class="naGridTitle" height="24" align="left">order number</td> 
		   <td width="15%" nowrap class="naGridTitle" >Coupon Name</td> 
          <td width="5%" nowrap class="naGridTitle">Discount</td>
          <td width="15%" nowrap class="naGridTitle">Date</td>
       	  <td width="25%" nowrap class="naGridTitle">customer</td>
	  	  <td width="15%" nowrap class="naGridTitle">Discount</td>
	    </tr>
        {foreach from=$REPORT_LIST item=row}
        <tr class="{cycle values="naGrid1,naGrid2"}"> 
       
		  <td width="5%" height="24" align="left" valign="middle">
		 {$row->order_number}</td> 
		  <td >
		 {$row->coupon_title}</td> 
          <td height="24" align="left" valign="middle">{if $row->coupon_type eq 'P' }{$row->amount_discount|round}%{else}${$row->amount_discount}{/if}</td> 
          <td height="24" align="left" valign="middle">{$row->curr_date|date_format}</td> 
		  <td height="24" align="left" valign="middle">{$row->billing_first_name}&nbsp;{$row->billing_last_name}</td> 
          <td height="24" align="left" valign="middle">{$row->item_name}</td> 
		</tr> 
        {/foreach}
        <tr> 
          <td colspan="8" class="msg" align="center" height="30">{$REPORT_NUMPAD}</td> 
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