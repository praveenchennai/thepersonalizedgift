<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
{literal}
<script language="javascript">
function pageSearch()
	{
	cat_search	=	document.getElementById("category_search").value;
	document.frm_features.keysearch.value='Y';
	document.frm_features.action='{/literal}{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=list{/makeLink}{literal}';
	document.frm_features.submit();
	}
function deleteSelected()
{
	var count1=0;
	
		for (var i=0;i<frm_features.elements.length;i++)
			{
			var e = frm_features.elements[i];
					
					if(e.name=='category_id[]')
					{
						if(e.checked==true)
						{
						count1++;
						}
					}
				
			
			}
		if(count1==0){
		alert("Please select one {/literal}{$smarty.request.sId}{literal}");
		return false;
		}
	
	
	if(confirm('Are you sure to delete selected {/literal}{$smarty.request.sId}{literal}?'))
	{
		document.frm_features.action='{/literal}{makeLink mod=flyer pg=feature_options}act=delete&limit={$smarty.request.limit}{/makeLink}{literal}'; 
		document.frm_features.submit();
	}
}
</script>
{/literal}
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0"> 
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_features" method="post" action="" enctype="multipart/form-data" style="margin: 0px;">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Field Items</td> 
          <td nowrap align="right" class="titleLink">&nbsp;</td> 
        </tr> 
      </table></td> 
  </tr> 
   <tr> 
    <td><table border=0 width=100% cellspacing="0" cellpadding="2"> 
        <tr>
          <td height="24" colspan="5" align="left" class="naGrid1"><!-- {$DISPLAY_PATH} --></td>
        </tr>
		
    <tr class=naGrid2>
	<td valign=middle align=left >
	{if count($ITEM_LIST) > 0}<div align=center class="titleLink"><a href="#" onClick="javascript:deleteSelected();">Delete</a></div>{/if}
    </td>
    	<td colspan=4 align="right" valign=middle>
		
		<!-- <table border="0" cellspacing="0" cellpadding="2" align="right">
          <tr>
		 <td align="left" valign=middle>:</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>No of Item In a Page</td>
            <td align="center">:</td>
            <td>{$ITEM_LIMIT}</td>
          </tr>
        </table> --></td>
    </tr>
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_features,'category_id[]')"></td>
          <td width="30%" align="left" nowrap class="naGridTitle">Item Name</td> 
          <td width="30%" align="left" nowrap class="naGridTitle">&nbsp;</td>
              <td width="10%" align="left" nowrap class="naGridTitle">&nbsp;</td>
	    </tr>
        {if count($ITEM_LIST) > 0}
        {foreach from=$ITEM_LIST item=attribute name=foo}
        <tr class="{cycle values="naGrid1,naGrid2"}">
          <td valign="middle" align="center"><input type="checkbox" name="category_id[]" value="{$attribute->value_id}" ></td> 
          <td align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=feature_options}act=feature_item_edit&item_id={$attribute->value_id}&flag=feature&sId={$smarty.request.sId}&fId={$smarty.request.fId}&limit={$smarty.request.limit}&store_id={$smarty.request.store_id}{/makeLink}">{$attribute->name} </a> </td> 
          <td align="left" valign="middle"></td>
          <td align="left" valign="middle">&nbsp;</td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="5" class="msg" align="center" height="30"><!-- {$ITEM_NUMPAD} --></td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="5" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr>
   <tr>
    <td>&nbsp;</td>
  </tr> 
  <tr>
    <td>&nbsp;</td>
  </tr>
</table><input type="hidden" name="keysearch" value="N">
<input type="hidden" name="feature_id" value="{$FEATURE_ID}">
</form>