<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
{literal}
<script language="javascript">
function pageSearch()
	{
	cat_search	=	document.getElementById("category_search").value;
	document.frm_category.keysearch.value='Y';
	document.frm_category.action='{/literal}{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=list{/makeLink}{literal}';
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
		alert("Please select one {/literal}{$smarty.request.sId}{literal}");
		return false;
		}
	
	
	if(confirm('Are you sure to delete selected {/literal}{$smarty.request.sId}{literal}?'))
	{
		document.frm_category.action='{/literal}{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=delete&limit={$smarty.request.limit}{/makeLink}{literal}'; 
		document.frm_category.submit();
	}
}

function moduleChange() 
{
	var	id	=	document.frm_category.id.value;
	document.frm_category.action='{/literal}{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=list&limit={$smarty.request.limit}&id='+ id +'{/makeLink}{literal}'; 
	document.frm_category.submit();
}


</script>
{/literal}
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0"> 
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_category" method="post" action="" enctype="multipart/form-data" style="margin: 0px;">
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
	
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">{$smarty.request.sId}<input type="hidden" name="limit"  value="{$smarty.request.limit}"/></td> 
          <td nowrap align="right" class="titleLink">
	{if $STORE_PERMISSION.add == 'Y'}
		  <a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act={$ACT}&store_id={$smarty.request.store_id}&parent_id={$smarty.request.parent_id}{/makeLink}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}&limit={$smarty.request.limit} ">Add New {$smarty.request.sId}</a>
	 {/if}
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
	{if $STORE_PERMISSION.delete == 'Y'}
	{if count($CATEGORY_LIST) > 0}<div align=center class="titleLink"><a href="#" onclick="javascript:deleteSelected();">Delete</a></div>{/if}
    {/if}
	</td>
    	<td colspan=4 align="right" valign=middle>
		
		<table border="0" cellspacing="0" cellpadding="2" align="right">
          <tr>
		    <!-- <td>
	 	 <select name=id onchange="window.location.href='{makeLink mod=$MOD pg=$PG}act=list{/makeLink}&fId={$smarty.request.fId}&sId={$smarty.request.sId} &limit='+document.frm_category.limit.value+'&id='+this.value">
     	   <option value="{$smarty.request.id}">{$SELECT_DEFAULT}</option>
      	  {html_options values=$MODULE_PARENT.id output=$MODULE_PARENT.name selected=`$smarty.request.id`}
		 </select>
		 </td>-->
		<td> 
	 	 <select name="id" onchange="javascript: moduleChange();">
     	   <option >-- SELECT A MODULE --</option>
		  {foreach from=$MODULECATEGORY item=modcategory name=foo}
      	  <option value="{$modcategory.id}" >{$modcategory.name}</option>
		  {/foreach}
		 </select>
		 
		 </td>
		  <td align="left" valign=middle>Search:</td>
			<td><input type="text" id="category_search" name="category_search" value="{$CATEGORY_SEARCH_TAG}"></td>
			<td><input name="btn_search" type="button" class="naBtn" value="Search" onClick="javascript: pageSearch();"></td>
            <td>No of Item In a Page</td>
            <td align="center">:</td>
            <td>{$CATEGORY_LIMIT}</td>
          </tr>
        </table></td>
    </tr>
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_category,'category_id[]')"></td>
          <td width="30%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=category_name display="`$smarty.request.sId` Name"}act=list&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&store_id={$smarty.request.store_id}{/makeLink}</td> 
          <td width="30%" align="left" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=is_in_ui display="Show In Admin Side only"}act=list&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&store_id={$smarty.request.store_id}{/makeLink}</td>
          <td width="22%" align="left" nowrap class="naGridTitle">&nbsp;</td>
          <td width="10%" align="left" nowrap class="naGridTitle">&nbsp;</td>
	    </tr>
        {if count($CATEGORY_LIST) > 0}
        {foreach from=$CATEGORY_LIST item=category name=foo}
		
        <tr class="{cycle values="naGrid1,naGrid2"}">
          <td valign="middle" align="center">{if isset($STORE_ID) and $category->is_private eq N}<input type="checkbox" name="category_id[]" value="{$category->category_id}" disabled>{else}<input type="checkbox" name="category_id[]" value="{$category->category_id}" >{/if}</td> 
          <td align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act={$ACT}&category_id={$category->category_id}&parent_id={$category->parent_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&limit={$smarty.request.limit}&store_id={$smarty.request.store_id}{/makeLink}">{$category->category_name} </a></td> 
          <td align="left" valign="middle">{if ($category->is_in_ui eq Y)} YES  {else} NO {/if}</td>
          <td align="left" valign="middle">{if ($category->is_in_ui eq N)}<a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=$BULKPG}act=list&id={$category->category_id}&parent_id={$category->parent_id}&store_id={$smarty.request.store_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}">Bulk Price</a>{/if}</td>
          <td align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=list&parent_id={$category->category_id}&limit={$smarty.request.limit}&store_id={$smarty.request.store_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/grid/explore_next_level.gif" alt="Edit" border="0" {popup text="[Click here to explore the child level of the Category]" fgcolor="#eeffaa" width="340"}></a></td>
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
      <td width="53%" class="naGridTitle"><span class="group_style">Mass Updates
        
      </span></td>
	   <td width="47%" align="right" class="naGridTitle"><table border="0" class="naGridTitle" align="right">
  <tr>
<td align="right"><strong>Append  to the existing </strong><input name="append" type="checkbox" id="append" value="Y" checked></td>
  </tr>
</table></td>
    </tr> 
     <tr class=naGrid2> 
       <td valign=top colspan="2">&nbsp;</td>
     </tr>
     <tr class=naGrid2>
       <td valign=top><table width="100%">
	   {if $FIELDS.19==Y}
         <tr>
           <td width="10%">&nbsp;</td>
           <td width="49%">Base Price:</td>
           <td width="41%"><input type="text" name="base_price" value="" class="formText" size="30" maxlength="25" ></td>
         </tr>
		 {/if}
		  {if $FIELDS.5==Y}
		  {if $STORE_ID eq ''}
         <tr>
           <td width="10%">&nbsp;</td>
           <td width="49%">Show In Admin Side only:</td>
           <td width="41%"><input type=checkbox name="is_in_ui" value="Y" ></td>
         </tr>
		 {/if}
		 {/if}
		  {if $FIELDS.6==Y}
		   {if $STORE_ID eq ''}
         <tr>
           <td width="10%">&nbsp;</td>
           <td width="49%">Store Owner can Manage:</td>
           <td><input type=checkbox name="store_owner_manage" value="Y"></td>
         </tr>
		 {/if}
		 {/if}
		   {if $FIELDS.7==Y}
         <tr>
           <td width="10%">&nbsp;</td>
           <td width="49%">Mandatory Fields:</td>
           <td><input type=checkbox name="mandatory" value="Y" ></td>
         </tr>
		 {/if}
		   {if $FIELDS.8==Y}
         <tr>
           <td width="10%">&nbsp;</td>
           <td width="49%">Custom Wrap Around Text:</td>
           <td><input type=checkbox name="customization_text_required" value="Y" ></td>
         </tr>
		 {/if}
		    {if $FIELDS.9==Y}
         <tr>
           <td width="10%">&nbsp;</td>
           <td width="49%">Requested Monogram text:</td>
           <td><input type=checkbox name="additional_customization_request" value="Y" ></td>
         </tr>
		 {/if}
		
         <tr>
           <td>&nbsp;</td>
           <td>&nbsp;</td>
           <td>&nbsp;</td>
         </tr>
       </table></td>
       <td valign=top><table width="100%">
	      {if $FIELDS.10==Y}
         <tr>
           <td width="10%">&nbsp;</td>
           <td width="49%">Accessory Category:</td>
           <td><input type=checkbox name="accessory_category" value="Y" ></td>
         </tr>
		 {/if}
		   {if $FIELDS.11==Y}
		   {if $STORE_ID eq ''}
         <tr>
           <td width="10%">&nbsp;</td>
           <td width="49%">Is Monogram:</td>
           <td><input type=checkbox name="is_monogram" value="Y" ></td>
         </tr>
		 {/if}
		 {/if}
  {if $FIELDS.12==Y}
      <tr>
        <td width="10%">&nbsp;</td>
        <td width="40%">Gender:</td>
        <td> <input type="text" name="gender" class="formText" size="30" maxlength="25" ></td>
      </tr>
  {/if} {if $FIELDS.13==Y}
  <tr>
    <td width="10%">&nbsp;</td>
    <td width="40%">First Name:</td>
    <td> <input type="text" name="firstname" value="" class="formText" size="30" maxlength="25" ></td>
  </tr>
  {/if} {if $FIELDS.14==Y}
  <tr>
    <td width="10%">&nbsp;</td>
    <td width="40%">Nickname:</td>
    <td><input type="text" name="nickname" value="" class="formText" size="30" maxlength="25" ></td>
  </tr>
  {/if} {if $FIELDS.15==Y}
  <tr>
    <td width="10%">&nbsp;</td>
    <td width="40%">Sentiment Line-1:</td>
    <td> <input type="text" name="sentiment_line_1" value="" class="formText" size="30" maxlength="25" ></td>
  </tr>
  {/if} {if $FIELDS.16==Y}
  <tr>
    <td width="10%">&nbsp;</td>
    <td width="40%">Sentiment Line-2:</td>
    <td> <input type="text" name="sentiment_line_2" value="" class="formText" size="30" maxlength="25" ></td>
  </tr>
  {/if} {if $FIELDS.17==Y}
  <tr>
    <td width="10%" >&nbsp;</td>
    <td width="40%">Active:</td>
    <td><input type=checkbox name="active" value="y" checked></td>
  </tr>
  {/if}
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
       </table></td>
	  
       </tr> 
	    {if $FIELDS.18==Y}	
    {if count($MODULECATEGORY) > 0}
	  <tr class="naGridTitle">
      <td colspan=2 valign=middle align="left"><table align="left" cellpadding="0" cellspacing="0">
        <tr>
            <td class="naGridTitle">Needed to update modules also</td>
            <td align="center">&nbsp;</td>
            <td><input type=checkbox name="is_update_module" value="Y" checked ></td>
          </tr></table></td>
    </tr>
	<tr>
	<td colspan="2" valign="middle" class="{cycle values="naGrid1" advance=false}">
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
          <td colspan="2" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
		{/if}
    <tr class="naGridTitle"> 
      <td colspan=2 valign="center"><div align=center> 
	 <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr><tr><td colspan=2 valign=center>&nbsp;</td></tr></table></td>
  </tr>
</table><input type="hidden" name="keysearch" value="N">
</form>