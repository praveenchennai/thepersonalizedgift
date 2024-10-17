<script language="javascript" type="text/javascript">
{literal}
	function valueAssign(catname,catid,level)
	{
		window.opener.document.admFrm.testval.value = catname;
		window.opener.document.admFrm.change_parent_id.value = catid;
		window.opener.document.admFrm.level.value = level;
		window.close();
		
	}
	function pageSearch()
	{

	cat_search = document.getElementById("category_search").value;
	document.admFrm.keysearch.value = "Y";
	document.admFrm.action='{/literal}{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=list&cat_id={$smarty.request.cat_id}{/makeLink}{literal}';
	document.admFrm.submit();
	
	}
	function moduleChange() 
	{
	var	id	=	document.frm_category.id.value;
	document.frm_category.action='{/literal}{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=list&limit={$smarty.request.limit}&id='+ id +'{/makeLink}{literal}'; 
	document.frm_category.submit();
	}
{/literal}

</script>
<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;"> 
<table border=0 width=100% cellpadding=5 cellspacing=0 class=naBrDr>
<tr>
<td>
<table border=0 width=100% cellspacing="0" cellpadding="2"> 
        <tr>
          <td height="24" colspan="4" align="left" class="naGrid1">{$DISPLAY_PATH}
	        <input type="hidden" name="sId" value="{$smarty.request.sId}">
            <input type="hidden" name="fId" value="{$smarty.request.fId}">
            <span class="naH1">
            <input type="hidden" name="limit"  value="{$smarty.request.limit}"/>
            <span class="naError">
            <input type="hidden" name="keysearch" value="N">
            </span> </span></td>
        </tr>
		
    <tr class=naGrid2>
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
	      <td width="30%" align="left" height="22" nowrap class="naGridTitle">{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg` orderBy=category_name display="`$smarty.request.sId` Name"}act=list&parent_id={$smarty.request.parent_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&store_id={$smarty.request.store_id}{/makeLink}</td> 
          <td width="10%" align="left" nowrap class="naGridTitle">&nbsp;</td>
	    </tr>
        {if count($CATEGORY_LIST) > 0}
        {foreach from=$CATEGORY_LIST item=category name=foo}
		
        <tr class="{cycle values="naGrid1,naGrid2"}">
          <td align="left" valign="middle"><a class="linkOneActive" href="#" onClick="valueAssign('{$category->category_name}','{$category->category_id}','{$category->level}')">{$category->category_name} </a></td> 
          <td align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=list&parent_id={$category->category_id}&limit={$smarty.request.limit}&store_id={$smarty.request.store_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/grid/explore_next_level.gif" alt="Edit" border="0" {popup text="[Click here to explore the child level of the Category]" fgcolor="#eeffaa" width="340"}></a></td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="4" class="msg" align="center" height="30">{$CATEGORY_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="4" class="naError" align="center" height="30">            No Records</td> 
        </tr>
        {/if}
      </table>
</td>
</tr>
</table>
</form>