<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
{literal}
<script language="javascript">
function statusChange() 
{	{/literal}
	var	id	=	document.frm_flyer.status_id.value;
	document.frm_flyer.action="{makeLink mod=flyer pg=flyer}act=list{/makeLink}&status_id="+id; 
	document.frm_flyer.submit();
	{literal}
}
function pageSearch()
	{
	cat_search	=	document.getElementById("flyer_search").value;
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
          <td height="24" colspan="7" align="left" class="naGrid1">{$DISPLAY_PATH}</td>
        </tr>
		
    <tr class=naGrid2>
	<td valign=middle align=left >
	{if count($FLYER_LIST) > 0}<div align=center class="titleLink"><a href="#" onclick="javascript:deleteSelected();">Delete</a></div>{/if}

	</td>
    	<td colspan=6 align="right" valign=middle>
		
		<table border="0" cellspacing="0" cellpadding="2" align="right">
          <tr>
		
		   
		  <td align="left" valign=middle>Show:&nbsp;</td> 
		   <td align="left" valign=middle><select name="status_id" onchange="javascript: statusChange();">
			
     	   <option value="all">All</option>
		   {if $STATUS_ID eq 'draft'}
		   <option value="draft" selected>Drafts</option>
		   {else}   <option value="draft">Drafts</option> {/if}
		   {if $STATUS_ID eq 'publish'}
		   <option value="publish" selected>Published</option>
		   {else}  <option value="publish">Published</option> {/if}
		    {if $STATUS_ID eq 'expire'}
		   <option value="expire" selected>Expired</option>
		   {else}  <option value="expire">Expired</option> {/if}
		 </select></td>
		  <td align="left" valign=middle>&nbsp;</td>
		  <td align="left" valign=middle>Search:</td>
			<td><input type="text" id="flyer_search" name="flyer_search" value="{$FLYER_SEARCH_TAG}"></td>
			<td><input name="btn_search" type="button" class="naBtn" value="Search" onClick="javascript: pageSearch();"></td>
            <td>No of Item In a Page</td>
            <td align="center">:</td>
            <td>{$FLYER_LIMIT}</td>
          </tr>
        </table></td>
    </tr>
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_flyer,'category_id[]')"></td>
          <td width="25%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=flyer_name display="`$smarty.request.sId` Title"}act=list&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&status_id={$STATUS_ID}&store_id={$smarty.request.store_id}{/makeLink}</td> 
          <td width="20%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=form_title display="Category"}act=list&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&status_id={$STATUS_ID}&store_id={$smarty.request.store_id}{/makeLink}</td>
		  <td width="13%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=first_name display="User ID"}act=list&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&status_id={$STATUS_ID}&store_id={$smarty.request.store_id}{/makeLink}</td>
		  <td width="12%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=publish display="State"}act=list&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&status_id={$STATUS_ID}&store_id={$smarty.request.store_id}{/makeLink}</td>

		  <td width="12%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=modified_date display="Modified Date"}act=list&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&status_id={$STATUS_ID}&store_id={$smarty.request.store_id}{/makeLink}</td>
          <td width="13%" align="left" nowrap class="naGridTitle">&nbsp;</td>
	    </tr>
        {if count($FLYER_LIST) > 0}
        {foreach from=$FLYER_LIST item=flyer name=foo}
		
        <tr class="{cycle values="naGrid1,naGrid2"}">
          <td valign="middle" align="center">{if isset($STORE_ID) and $flyer->is_private eq N}<input type="checkbox" name="category_id[]" value="{$flyer->flyer_id}" disabled>{else}<input type="checkbox" name="category_id[]" value="{$flyer->flyer_id}" >{/if}</td> 
          <td align="left" valign="middle">{$flyer->flyer_name} </td> 
          <td align="left" valign="middle">{$flyer->form_title}</td>
		  <td align="left" valign="middle">{$flyer->first_name} {$flyer->last_name}</td>
			  <td align="left" valign="middle">{if $flyer->expire_date lt $CUR_DATE} Expired {elseif $flyer->publish eq 'Y'} Published {else}Draft{/if} </td>
          <td align="left" valign="middle">{$flyer->modified_date}</td>
          <td align="left" valign="middle"><a href="{makeLink mod=flyer  pg=flyer}act=property_view&propid={$flyer->album_id}&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&flyer_id={$flyer->flyer_id}&details={$STATUS_ID}&store_id={$smarty.request.store_id}{/makeLink}">View Listing</a></td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="6" class="msg" align="center" height="30">{$FLYER_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="6" class="naError" align="center" height="30">No Records</td> 
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