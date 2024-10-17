<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
{literal}
<script language="javascript">
function pageSearch()
	{
	cat_search	=	document.getElementById("category_search").value;
	document.frm_flyer.keysearch.value='Y';
	document.frm_flyer.action='{/literal}{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=list{/makeLink}{literal}';
	document.frm_flyer.submit();
	}
function deleteSelected()
{
	var count1=0;
	
		for (var i=0;i<frm_flyer.elements.length;i++)
			{
			var e = frm_flyer.elements[i];
					
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
		document.frm_flyer.action='{/literal}{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=delete&limit={$smarty.request.limit}{/makeLink}{literal}'; 
		document.frm_flyer.submit();
	}
}
</script>
{/literal}
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0"> 
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_flyer" method="post" action="" enctype="multipart/form-data" style="margin: 0px;">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">{$smarty.request.sId}</td> 
          <td nowrap align="right" class="titleLink">&nbsp;
	</td> 
        </tr> 
      </table></td> 
  </tr> 
   <tr> 
    <td><table border=0 width=100% cellspacing="0" cellpadding="2"> 
        <tr>
          <td height="24" colspan="5" align="left" class="naGrid1">{$DISPLAY_PATH}</td>
        </tr>
		
    <tr class=naGrid2>
	<td valign=middle align=left >
	{if count($FLYER_LIST) > 0}<div align=center class="titleLink"><a href="#" onclick="javascript:deleteSelected();">Delete</a></div>{/if}

	</td>
    	<td colspan=4 align="right" valign=middle>
		
		<table border="0" cellspacing="0" cellpadding="2" align="right">
          <tr>
		 <td align="left" valign=middle>Category</td>
		  <td align="left" valign=middle>&nbsp;</td>
		   <td align="left" valign=middle>
		   <input type="hidden" name="hid_cat" value="{$smarty.request.cat_id}" />
	  <select name=cat_id  style="width:185px"onchange="window.location.href='{makeLink mod=flyer pg=flyer}act=list&limit={$smarty.request.limit}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}&cat_id='+this.value">
        <option value="">-- SELECT A CATEGORY --</option>
	 { if $SELECT_DEFAULT ne '' }
	 <option value="0" selected="selected">{$SELECT_DEFAULT}</option>
	 {/if}
       {html_options values=$CATEGORY.category_id output=$CATEGORY.category_name selected=`$smarty.request.cat_id`}
	   </select>	  
		   </td>
		  <td align="left" valign=middle>&nbsp;</td>
		  <td align="left" valign=middle>Search:</td>
			<td><input type="text" id="category_search" name="category_search" value="{$FLYER_SEARCH_TAG}"></td>
			<td><input name="btn_search" type="button" class="naBtn" value="Search" onClick="javascript: pageSearch();"></td>
            <td>No of Item In a Page</td>
            <td align="center">:</td>
            <td>{$FLYER_LIMIT}</td>
          </tr>
        </table></td>
    </tr>
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_flyer,'category_id[]')"></td>
          <td width="30%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=name display="`$smarty.request.sId` Name"}act=list&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&store_id={$smarty.request.store_id}{/makeLink}</td> 
          <td width="30%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=cat_id display="Category"}act=list&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&store_id={$smarty.request.store_id}{/makeLink}</td>
          <td width="22%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=cat_id display="Created Date"}act=list&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&store_id={$smarty.request.store_id}{/makeLink}</td>
          <td width="10%" align="left" nowrap class="naGridTitle">&nbsp;</td>
	    </tr>
        {if count($FLYER_LIST) > 0}
        {foreach from=$FLYER_LIST item=flyer name=foo}
		
        <tr class="{cycle values="naGrid1,naGrid2"}">
          <td valign="middle" align="center">{if isset($STORE_ID) and $flyer->is_private eq N}<input type="checkbox" name="category_id[]" value="{$flyer->id}" disabled>{else}<input type="checkbox" name="category_id[]" value="{$flyer->id}" >{/if}</td> 
          <td align="left" valign="middle">{$flyer->name} </td> 
          <td align="left" valign="middle">{$flyer->category_name}</td>
          <td align="left" valign="middle">{$flyer->created_date}</td>
          <td align="left" valign="middle"><a href="#">View Flyer</a></td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="5" class="msg" align="center" height="30">{$FLYER_NUMPAD}</td> 
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