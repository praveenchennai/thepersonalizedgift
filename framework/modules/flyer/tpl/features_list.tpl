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
		document.frm_features.action='{/literal}{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=delete&limit={$smarty.request.limit}{/makeLink}{literal}'; 
		document.frm_features.submit();
	}
}
function popUp1(group_id) {
	//alert(url);
	{/literal}
window.open('{makeLink mod=flyer pg=feature_options}act=options_view{/makeLink}&id='+group_id, "name_of_window", "width=540,height=380,left=100,top=200,scrollbars=yes,menubar=no, resizable=no,location=no,toolbar=no");
}
</script>

<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0"> 
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_features" method="post" action="" enctype="multipart/form-data" style="margin: 0px;">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">{$smarty.request.sId}</td> 
          <td nowrap align="right" class="titleLink"> <a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act={$ACT}{/makeLink}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}&limit={$smarty.request.limit} ">Add New {$smarty.request.sId}</a>
	
	</td> 
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
	
	{if count($FEATURES_LIST) > 0}<div align=center class="titleLink"><a href="#" onclick="javascript:deleteSelected();">Delete</a></div>{/if}

	</td>
    	<td colspan=4 align="right" valign=middle>
		
		<table border="0" cellspacing="0" cellpadding="2" align="right">
          <tr>
		 <td align="left" valign=middle>Search:</td>
			<td><input type="text" id="category_search" name="category_search" value="{$FEATURES_SEARCH_TAG}"></td>
			<td><input name="btn_search" type="button" class="naBtn" value="Search" onClick="javascript: pageSearch();"></td>
            <td>No of Item In a Page</td>
            <td align="center">:</td>
            <td>{$FEATURES_LIMIT}</td>
          </tr>
        </table></td>
    </tr>
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_features,'category_id[]')"></td>
          <td width="30%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=label display="`$smarty.request.sId` Label"}act=list&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&store_id={$smarty.request.store_id}{/makeLink}</td> 
          <td width="20%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=type display="Type"}act=list&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&store_id={$smarty.request.store_id}{/makeLink}</td>
          <td width="10%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=position display="Position"}act=list&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&store_id={$smarty.request.store_id}{/makeLink}</td>
		  <td width="10%" align="left" nowrap class="naGridTitle">&nbsp;</td>
	    </tr>
        {if count($FEATURES_LIST) > 0}
        {foreach from=$FEATURES_LIST item=features name=foo}
		
        <tr class="{cycle values="naGrid1,naGrid2"}">
          <td valign="middle" align="center">{if isset($STORE_ID) and $features->is_private eq N}<input type="checkbox" name="category_id[]" value="{$features->id}" disabled>{else}<input type="checkbox" name="category_id[]" value="{$features->feature_id}" >{/if}</td> 
          <td align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act={$ACT}&feature_id={$features->feature_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&limit={$smarty.request.limit}&store_id={$smarty.request.store_id}{/makeLink}">{$features->label} </a> </td> 
          <td align="left" valign="middle">{$features->type}</td>
		   <td align="left" valign="middle">{$features->position}</td>
          <td align="left" valign="middle">{if $features->type eq 'Dropdown'}<a href="#"  onClick="javascript:popUp1('{$features->feature_id}'); return false;">View Items</a> {/if}</td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="5" class="msg" align="center" height="30">{$FEATURES_NUMPAD}</td> 
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
</form>