<script src="{$smarty.const.SITE_URL}/includes/DragDrop/prototype.js" type="text/javascript"></script>
<script src="{$smarty.const.SITE_URL}/includes/DragDrop/scriptaculous.js" type="text/javascript"></script>
<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/scripts/validator.js"></SCRIPT>
<style type="text/css">
{literal}
.handle {
	cursor:move;
}
{/literal}
</style>
	<form action="" method="post" style="margin:0px;" name="orderFrm" >
	<input type="hidden" name="sortOrder" value="" />
	
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>


<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table border="0" cellspacing="0" cellpadding="4" width="100%" align="center"> 
        <tr>
		<td nowrap class="naH1">{$smarty.request.sId} </td> 
          <td nowrap align="right" class="titleLink" width="100%"></td> 
        </tr>
         
		
      </table>
	 </td> 
  </tr> 
  <tr> 
    <td>
		<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0" class="naBrdr" style="border-right-width: 0px;border-left-width: 0px;">
  <tr>
    <td ><div style="font-weight:bold;font-size:13px;padding-bottom:5px;padding-top:3px;"> Important Notes:</div><span ><font size="-1" > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1. Drag and Drop the Featured Gifts to change the display order.</font></span></td>
  </tr>
  <tr>
    <td style="padding-bottom:5px"><font size="-1" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  2. A maximum number of 8 featured gifts will be displayed on your webstores home page.</font></td>
  </tr>
</table>
	 </td> 
  </tr>
  <tr> 
    <td><table border=0 width=100% cellpadding="0" cellspacing="0"> 
        {if count($PREDFD_GIFT_LIST) > 0}
        <tr>
           
          <td width="2%" nowrap class="naGridTitle" height="24" align="center"></td> 
          <td width="45%" nowrap class="naGridTitle" height="24" align="left">Featured Gifts</td> 
          <td width="32%" nowrap class="naGridTitle">Featured Gift Type</td>
		      
		  <td width="10%" nowrap class="naGridTitle">Display Order&nbsp;&nbsp;</td>
		   <td  width="1%" nowrap class="naGridTitle"  height="24" align="left" valign="middle">&nbsp;</td>
		  <td width="10%" nowrap class="naGridTitle">Display Status&nbsp;&nbsp;</td>
          
        </tr>
		
		<tr><td colspan="6"><div id="linkOrder">
		{foreach from=$PREDFD_GIFT_LIST item=row name=flist}
		<input type="hidden" name="p_type[{$row.id}]" value="{$row.gift}" />
		<input type="hidden" name="display_status[{$row.id}]" value="{if $row.display_status}{$row.display_status}{else}Y{/if}" />
		  <div id="test_{$row.id}" class="{cycle values="naGrid1,naGrid2"}" >
		<table border="0" width=100% cellpadding="0" cellspacing="0"> 
        <tr>
          <td  width="2%"valign="middle" height="20" align="center"><!--<a class="linkOneActive" href="{makeLink mod=$smarty.request.mod pg=$smarty.request.pg}act=predef_gift&id={$row.id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}"><img title="Edit" alt="Edit" src="{$GLOBAL.tpl_url}/images/grid/icon.edit.gif" border="0"></a>--></td> 
          <td width="45%" height="20" align="left" valign="middle" class="handle">{$row.name}</td> 
          <td  width="32%" height="24" align="left" valign="middle">{if $row.gift eq 'pgift'}Pre-Defined Gift{elseif $row.gift eq 'gift' and $row.custom_product eq 'Y' }Other Gift{else}Generic Gift{/if}</td>
		 
		   
		 <td  width="10%"  height="20" align="left" valign="middle">{if $row.display_order <= 8}{$row.display_order}{/if}</td>  
		   <td  width="2%"  height="20" align="left" valign="middle">&nbsp;</td>   
		  <td  width="10%"  height="20" align="left" valign="middle">
		  {if $row.display_status eq 'N'}
		  		<a href="{makeLink mod=$MOD pg=$PG}act=change_status&stat={$row.display_status}&product_id={$row.product_id}&p_type={$row.p_type}&store_id={$row.store_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}">  <img border="0" title="Activate"  src="{$smarty.const.SITE_URL}/framework/includes/images/activeN.gif"/></a>
			<img border="0" title="Deactivate"  src="{$smarty.const.SITE_URL}/framework/includes/images/deactiveN.gif"/>
		 
		  {else}
 <img border="0" title="Activate"  src="{$smarty.const.SITE_URL}/framework/includes/images/activeY.gif"/>
			<a href="{makeLink mod=$MOD pg=$PG}act=change_status&stat=Y&product_id={$row.product_id}&p_type={$row.p_type}&store_id={$row.store_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}"><img border="0" title="Deactivate"  src="{$smarty.const.SITE_URL}/framework/includes/images/deactiveY.gif"/></a>
		  {/if}
		  </td>  
        </tr> 
		
		</table></div>{/foreach}</div></td></tr>
		 
        <tr> 
          <td colspan="6"  class="naGridTitle" align="center" height="30"><input type="submit" name="Submit"  value="Save Changes" class="naBtn" disabled="disabled" id="sbtn" /></td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="5" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr> 
</table>
</form>
<script type="text/javascript">
 {literal}
 // <![CDATA[
   Sortable.create("linkOrder",
	 {tag:'div',onUpdate:saveOrder,dropOnEmpty:true,containment:["linkOrder"],handle:'handle',constraint:false});
 // ]]>
 function saveOrder() {
 	
 	document.getElementById('sbtn').disabled=false;
 	document.orderFrm.Submit.style.color = "#FF0000";
 	document.orderFrm.sortOrder.value = Sortable.serialize('linkOrder');
 }
 saveOrder();

 {/literal}
 </script>