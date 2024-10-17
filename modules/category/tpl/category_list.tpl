<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
{literal}
<script language="javascript">
function pageSearch()
	{
	cat_search	=	document.getElementById("category_search").value;
	document.frm_category.keysearch.value='Y';
	document.frm_category.action='{/literal}{makeLink mod=category pg=index}act=list{/makeLink}{literal}';
	document.frm_category.submit();
	}
function deleteSelected()
{
	var count1=0;
	
		for (var i=0;i<frm_category.elements.length;i++)
			{
			var e = frm_category.elements[i];
					
					if(e.name=='category_id[]')
					{
						if(e.checked==true)
						{
						count1++;
						}
					}
				
			
			}
		if(count1==0){
		alert("Please select one category");
		return false;
		}
	
	
	if(confirm('Are you sure to delete selected categories?'))
	{
		document.frm_category.action='{/literal}{makeLink mod=category pg=index}act=delete&limit={$smarty.request.limit}{/makeLink}{literal}'; 
		document.frm_category.submit();
	}
}
</script>
{/literal}
<form name="frm_category" method="post" action="" enctype="multipart/form-data" style="margin: 0px;"><table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">Categories</td> 
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=category pg=index}act=form&parent_id={$smarty.request.parent_id}{/makeLink}">Add New Category</a></td> 
        </tr> 
      </table></td> 
  </tr> 
   <tr> 
    <td><table border=0 width=100% cellspacing="0" cellpadding="2"> 
        <tr>
          <td height="24" colspan="5" align="left" class="naGrid1">{$DISPLAY_PATH}</td>
        </tr>
		
    <tr class=naGrid2>
	<td valign=middle align=left >{if count($CATEGORY_LIST) > 0}<div align=center class="titleLink"><a href="#" onclick="javascript:deleteSelected();">Delete</a></div>{/if}</td>
    	<td colspan=4 align="right" valign=middle>
		{if count($CATEGORY_LIST) > 0}
		<table border="0" cellspacing="0" cellpadding="2" align="right">
          <tr>
		  <td align="left" valign=middle>Search:</td>
			<td><input type="text" id="category_search" name="category_search" value="{$CATEGORY_SEARCH_TAG}"></td>
			<td><input name="btn_search" type="button" class="naBtn" value="Search" onClick="javascript: pageSearch();"></td>
            <td>No of Item In a Page</td>
            <td align="center">:</td>
            <td>{$CATEGORY_LIMIT}</td>
          </tr>
        </table>{/if}</td>
    </tr>
    <tr class=naGrid2>
      <td colspan="5" align=left valign=botton ><div align=center class="element_style">
          <span class="naError">{$MESSAGE}</span></div></td>
      </tr>
       {if count($CATEGORY_LIST) > 0}
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_category,'category_id[]')"></td>
          <td width="30%" align="left" nowrap class="naGridTitle">{makeLink mod=category pg=index orderBy=category_name display="Category Name"}act=list&parent_id={$smarty.request.parent_id}{/makeLink}</td> 
          <td width="30%" align="left" nowrap class="naGridTitle">{makeLink mod=category pg=index orderBy=is_in_ui display="Show In Admin Side only"}act=list&parent_id={$smarty.request.parent_id}{/makeLink}</td>
          <td width="22%" align="left" nowrap class="naGridTitle">&nbsp;</td>
          <td width="10%" align="left" nowrap class="naGridTitle">&nbsp;</td>
	    </tr>
        {foreach from=$CATEGORY_LIST item=category name=foo}
		
        <tr class="{cycle values="naGrid1,naGrid2"}">
          <td valign="middle" align="center"><input type="checkbox" name="category_id[]" value="{$category->category_id}"></td> 
          <td align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=category pg=index}act=form&category_id={$category->category_id}&parent_id={$category->parent_id}{/makeLink}">{$category->category_name} </a></td> 
          <td align="left" valign="middle">{if ($category->is_in_ui eq Y)} YES  {else} NO {/if}</td>
          <td align="left" valign="middle">{if ($category->is_in_ui eq N)}<a class="linkOneActive" href="{makeLink mod=category pg=bulk}act=list&id={$category->category_id}&parent_id={$category->parent_id}{/makeLink}">Bulk Price</a>{/if}</td>
          <td align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=category pg=index}act=list&parent_id={$category->category_id}&limit={$smarty.request.limit}{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/grid/explore_next_level.gif" alt="Edit" border="0" {popup text="[Click here to explore the child level of the Category]" fgcolor="#eeffaa" width="340"}></a></td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="5" class="msg" align="center" height="30">{$CATEGORY_NUMPAD}</td> 
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
    <td><table border=0 width=100% cellpadding=5 cellspacing=0 class=naBrDr> 
     <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">Mass Updates
        
      </span></td> 
    </tr> 
     <tr class=naGrid2> 
      <td valign=top width=100% colspan="3"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr class=naGrid2>
          <td width="5%">&nbsp;</td>
          <td width="20%">Show In Admin Side only:</td>
          <td width="2%">&nbsp;</td>
          <td width="10%"><input type=checkbox name="is_in_ui" value="Y" ></td>
          <td width="10%">&nbsp;</td>
          <td width="20%">Mandatory Fields:</td>
          <td width="2%">&nbsp;</td>
          <td width="10%"><input type=checkbox name="mandatory" value="Y" ></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr class=naGrid2>
          <td>&nbsp;</td>
          <td>Custom Text Required:</td>
          <td>&nbsp;</td>
          <td><input type=checkbox name="customization_text_required" value="Y" ></td>
          <td>&nbsp;</td>
          <td>Addition Custom Test Required:</td>
          <td>&nbsp;</td>
          <td><input type=checkbox name="additional_customization_request" value="Y" ></td>
          <td>&nbsp;</td>
        </tr>
        <tr class=naGrid2>
          <td>&nbsp;</td>
          <td>Is Monogram:</td>
          <td>&nbsp;</td>
          <td><input type=checkbox name="is_monogram" value="Y" ></td>
          <td>&nbsp;</td>
          <td>Active:</td>
          <td>&nbsp;</td>
          <td><input type=checkbox name="active" value="y" checked></td>
          <td>&nbsp;</td>
        </tr>
        <tr class=naGrid2>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr class=naGrid2>
          <td colspan="5" align="center">Append  to the existing 
            <input name="append" type="checkbox" id="append" value="Y">
          </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr class=naGrid2>
          <td colspan="5" align="center">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
       </table></td> 
      </tr> 
    {if count($MODULECATEGORY) > 0}
	  <tr class="naGridTitle">
      <td colspan=3 valign=middle align="left"><table align="left" cellpadding="0" cellspacing="0">
        <tr>
            <td class="naGridTitle">Needed to update modules also</td>
            <td align="center">&nbsp;</td>
            <td><input type=checkbox name="is_update_module" value="Y"></td>
          </tr></table></td>
    </tr>
	<tr>
	<td colspan="3" valign="middle" class="{cycle values="naGrid1" advance=false}">
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
		 {foreach from=$MODULECATEGORY item=modcategory name=foo}
		 {if $smarty.foreach.foo.index is div by 3}
			</tr><tr>
			{ /if }
		 	 <td  valign="middle" class="{cycle name=bg values="naGrid1" advance=false}" height="24" align="right"><input type=checkbox name="module[]" value="{$modcategory.id}" {$modcategory.checked}></td> 
          <td  valign="middle" class="{cycle name=bg values="naGrid1" advance=false}" height="24" align="center"></td> 
          <td valign="middle" class="{cycle name=bg values="naGrid1,naGrid2"}" height="24" align="left">{$modcategory.name}</td> 
		  {/foreach}
			</tr>
		</table>
	</td>
	</tr>
	    
       	{else}
         <tr class="naGrid2"> 
          <td colspan="3" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
    <tr class="naGridTitle"> 
      <td colspan=3 valign="center"><div align=center> 
	 <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr><tr><td colspan=3 valign=center>&nbsp;</td></tr></table></td>
  </tr>
</table><input type="hidden" name="keysearch" value="N">
</form>