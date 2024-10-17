{literal}

<style type="text/css">
<!--
.border1 {
	border: 1px solid #FFFFFF;
	background-color:#EFEFEF;
}
-->
</style>


{/literal}
<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>

<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0" >
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;" onSubmit="return chk(this);"> 

<input type="hidden" name="tmpcount" value="1"  />

 	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
	<input type="hidden" name="check" value="no">
	<input type="hidden" name="limit"  value="{$smarty.request.limit}"/>

  <table border=0 width=95% cellpadding="5" cellspacing="2" class=naBrDr>
    <tr align="left">
      <td colspan=9 valign=top><table width="100%" align="center">
          <tr>
            <td nowrap class="naH1">{$smarty.request.sId}</td>
            <td nowrap align="right" class="titleLink"></td>
			<td nowrap align="right" class="titleLink"></td>
			
          </tr>
		  <tr>
		   <td>
		   	<table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
  <td width="3%">
	<a class="linkOneActive" href="#" onclick="javascript: document.admFrm.action='{makeLink mod=product pg=inventry}act=delete&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}'; document.admFrm.submit();">Delete</a>
</td>
    <td width="3%">

    <td width="1%">&nbsp;</td>
	
   <td width="12%" align="center">&nbsp;</td>
	  <td width="11%" align="right"><input type="text" name="inventry_search" value="{$ACCESSORY_SEARCH_TAG}" /></td>
	  <td width="10%"><input name="btn_search" type="submit" class="naBtn" value="Search" /></td>
	  <td width="1%">&nbsp;</td>
	  <td width="1%">&nbsp;</td>
    <td width="9%" nowrap><!--<strong>Results per page:</strong>--></td>
	<td width="13%"><!--{$INVENTRY_LIMIT}--></td>
  </tr>
</table>
		   </td>
		  </tr>
      </table></td>
	  </tr>
  <!--<tr>
  	 <td class="naGridTitle"  colspan="7" align="left">View Inventry </td>
  </tr>-->
  <tr>
  	 <td class="naGridTitle" align="center" width="2%">&nbsp;</td>
	 <td class="naGridTitle" align="center" width="2%">&nbsp;</td>
	 <td class="naGridTitle" align="center" width="11%">Part Number</td>
	 <td class="naGridTitle" align="center" width="15%">Model Number</td>
	 <td class="naGridTitle" align="center" width="15%">Descripton</td>
	 <td class="naGridTitle" align="center" width="15%">Condition</td>
	 <td class="naGridTitle" align="center" width="15%">Price</td>
	 <td class="naGridTitle" align="center" width="15%">Aircraft Type</td>
	 <td class="naGridTitle" align="center" width="10%">Quantity</td>
     <td class="naGridTitle" align="center" width="10%">Manufacturer</td>
  </tr>
  {foreach from =$INVENTRY item=inven}
  <tr class="{cycle values= naGrid2,naGrid1}">
   	 <td align="center" width="2%"><input type="checkbox" name="del_id[]" id="id[]" value="{$inven.part_num}" /></td>
	<td valign="middle" align="center"><a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=edit&part_num={$inven.part_num}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a></td> 
	 <td align="center" width="11%">{$inven.part_num}</td>
	 <td align="center" width="15%">{$inven.model_num}</td>
	 <td align="center" width="15%">{$inven.description}</td>
	 <td align="center" width="15%">{$inven.condition}</td>
	 <td align="center" width="15%">{$inven.price}</td>
	 <td align="center" width="15%">{$inven.aircraft_type}</td>
	 <td align="center" width="10%">{$inven.quantity}</td>
	 <td align="center" width="10%">{$inven.manufacturer}</td>
  </tr>
  {/foreach}
  <tr><td colspan=9 class="msg" align="center" height="30">{$NUMPAD}</td></tr>
	<tr><td colspan=9 valign=center>&nbsp;</td></tr>
</table>

</form>
