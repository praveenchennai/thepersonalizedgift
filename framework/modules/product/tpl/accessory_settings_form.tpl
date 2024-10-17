<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>


{if $smarty.request.group_id>0}
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_accessory_edit" method="post" action="" style="margin: 0px;" >
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1"><!--Accessory-->Existing Item
		  </td> 
          <td nowrap align="right" class="titleLink">&nbsp;</td> 
        </tr> 
      </table></td> 
  </tr>

  <tr> 
    <td><table width=100% border=0 cellpadding="2" cellspacing="0"> 
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_accessory_edit,'accessory[]')" checked disabled></td>
          <td width="50%" align="left" nowrap class="naGridTitle">Name</td> 
          <td width="15%" align="center" nowrap class="naGridTitle">Type</td>
          <td width="15%" align="center" nowrap class="naGridTitle">Adjust Price</td>
          <td width="15%" align="center" nowrap class="naGridTitle">Adjust Weight</td>
          <td width="20%" align="center" nowrap class="naGridTitle">Active</td>
	    </tr>
		{if count($CATEGORY_EDIT_ALL) > 0}
        {foreach from=$CATEGORY_EDIT_ALL item=accessory}
        <tr class="{cycle name=bg values="naGrid1,naGrid2"}">
          <td  valign="middle" align="center"><input type="checkbox" name="accessory[]" id="accessory_{$accessory.id}" value="{$accessory.id}" checked disabled></td> 
          <td align="left">{$accessory.name}</td>
		  <td align="center">{$accessory.type}</td> 
          <td align="center">{$accessory.adjust_price}</td>
          <td align="center">{$accessory.adjust_weight}</td>
          <td align="center">{ if $accessory.active eq 'Y'} Yes {else} No {/if}</td>
        </tr> 
        {/foreach}
        <!--<tr> 
          <td colspan="6" class="msg" align="center" height="30">{$ACCESSORY_NUMPAD}</td> 
        </tr>-->
        {else}
         <tr class="naGrid2"> 
          <td colspan="6" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
		
      </table></td> 
  </tr>
 </table>
 
<br />
</form>
{/if}







<form name="frm_accessory_new" method="post" action="" style="margin: 0px;" >
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1"><!--Accessory-->New Item
		  </td> 
          <td nowrap align="right" class="titleLink">&nbsp;</td> 
        </tr> 
      </table></td> 
  </tr>

  <tr> 
    <td><table width=100% border=0 cellpadding="2" cellspacing="0"> 
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_accessory_new,'accessorynew[]')"></td>
          <td width="50%" align="left" nowrap class="naGridTitle">Name</td> 
          <td width="15%" align="center" nowrap class="naGridTitle">Type</td>
          <td width="15%" align="center" nowrap class="naGridTitle">Adjust Price</td>
          <td width="15%" align="center" nowrap class="naGridTitle">Adjust Weight</td>
          <td width="20%" align="center" nowrap class="naGridTitle">Active</td>
	    </tr>
		{if count($CATEGORY_NEW_ALL) > 0}
        {foreach from=$CATEGORY_NEW_ALL item=accessory}
        <tr class="{cycle name=bg values="naGrid1,naGrid2"}">
          <td  valign="middle" align="center"><input type="checkbox" name="accessorynew[]" id="accessorynew_{$accessory.id}" value="{$accessory.id}" checked></td> 
          <td align="left">{$accessory.name}</td>
		  <td align="center">{$accessory.type}</td> 
          <td align="center">{$accessory.adjust_price}</td>
          <td align="center">{$accessory.adjust_weight}</td>
          <td align="center">{ if $accessory.active eq 'Y'} Yes {else} No {/if}</td>
        </tr> 
        {/foreach}
        <!--<tr> 
          <td colspan="6" class="msg" align="center" height="30">{$ACCESSORY_NUMPAD}</td> 
        </tr>-->
        {else}
         <tr class="naGrid2"> 
          <td colspan="6" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
		
      </table></td> 
  </tr>
 </table>
 <input name="btnSubmit" type="hidden" value="Submit">
</form><br />











<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_accessory" method="post" action="" style="margin: 0px;" >
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1"><!--Accessory-->{$SUBNAME}
		  <input type="hidden" name="limit"  value="{$smarty.request.limit}"/></td> 
          <td nowrap align="right" class="titleLink">&nbsp;</td> 
        </tr> 
		
		
		<tr>
          <td nowrap>{$CATEGORY_ACCESSORY_PATH}           <!--Accessory Exclude--></td>
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=settingsList&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}">{$smarty.request.sId} List</a></td>
        </tr>
 
      </table></td> 
  </tr>
  



	<tr>
	<td colspan=2 align=center>[This page is used to specify the avioding accessory items combinations]</td>
   </tr>


  <tr>
   <td valign=top class="naGrid1"><table width="80%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="3%">
{if $STORE_PERMISSION.delete == 'Y'}	
	{if count($ACCESSORY_LIST) > 0}<a class="linkOneActive" href="#" onclick="javascript: document.frm_accessory.action='{makeLink mod=accessory pg=accessory}act=delete&sId={$smarty.request.sId}&cat_id={$smarty.request.cat_id}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}'; document.frm_accessory.submit();">Delete</a>{/if}</td>
{/if}
    <td width="1%">&nbsp;</td>
   <td width="12%" align="center">&nbsp;Category&nbsp;:</td>
	  <td width="40%"><input type="hidden" name="hid_cat" value="{$smarty.request.cat_id}" />
	  <!--<select name=cat_id  style="width:250px"onchange="javascript: document.frm_accessory.action='{makeLink mod=accessory pg=accessory}act=settingsAdd&limit={$smarty.request.limit}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}&group_id={$smarty.request.group_id}{/makeLink}&cat_id='+this.value;document.frm_accessory.submit();">
        <option value="">-- SELECT A CATEGORY --</option>
	 { if $SELECT_DEFAULT ne '' }
	 <option value="" selected="selected">{$SELECT_DEFAULT}</option>
	 {/if}
       {html_options values=$CATEGORY_ONLY_ALL.cat_id output=$CATEGORY_ONLY_ALL.cat_name selected=`$smarty.request.cat_id`}
	   </select>-->
	   
	<select name=cat_id  style="width:185px"onchange="window.location.href='{makeLink mod=accessory pg=accessory}act=settingsAdd&limit={$smarty.request.limit}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}&group_id={$smarty.request.group_id}{/makeLink}&cat_id='+this.value;document.frm_accessory.submit();">
        <option value="">-- SELECT A CATEGORY --</option>
	 { if $SELECT_DEFAULT ne '' }
	 <option value="" selected="selected">{$SELECT_DEFAULT}</option>
	 {/if}
       {html_options values=$CATEGORY.category_id output=$CATEGORY.category_name selected=`$smarty.request.cat_id`}
	   </select>	   
	   
	   	   </td>
	   
	  <td width="16%" align="right"><input type="text" name="accessory_search" value="{$CATEGORY_ACCESSORY_SEARCH_TAG}" /></td>
	  <td width="20%"><input name="btn_search" type="submit" class="naBtn" value="Search" /></td>
	  <td width="1%">&nbsp;</td>
    <td width="4%" nowrap><strong><!--Results per page:</strong>--></td>
	<td width="3%"><!--{$CATEGORY_ACCESSORY_LIMIT}--></td>
  </tr>
</table></td>
    </tr> 
	
	
	
  <tr> 
    <td><table width=100% border=0 cellpadding="2" cellspacing="0"> 
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_accessory,'accessory[]')"></td>
          <td width="50%" align="left" nowrap class="naGridTitle">{makeLink mod=accessory pg=accessory orderBy=name display="Name" }act=settingsAdd&sId={$smarty.request.sId}&cat_id={$smarty.request.cat_id}&group_id={$smarty.request.group_id}&fId={$smarty.request.fId}&mId={$MID} {/makeLink}</td> 
          <td width="15%" align="center" nowrap class="naGridTitle">{makeLink mod=accessory pg=accessory orderBy=type display="Type" }act=settingsAdd&sId={$smarty.request.sId}&cat_id={$smarty.request.cat_id}&group_id={$smarty.request.group_id}&fId={$smarty.request.fId}&mId={$MID} {/makeLink}</td>
          <td width="15%" align="center" nowrap class="naGridTitle">{makeLink mod=accessory pg=accessory orderBy=adjust_price display="Adjust Price" }act=settingsAdd&sId={$smarty.request.sId}&cat_id={$smarty.request.cat_id}&group_id={$smarty.request.group_id}&fId={$smarty.request.fId}&mId={$MID} {/makeLink}</td>
          <td width="15%" align="center" nowrap class="naGridTitle">{makeLink mod=accessory pg=accessory orderBy=adjust_weight display="Adjust Weight" }act=settingsAdd&sId={$smarty.request.sId}&cat_id={$smarty.request.cat_id}&group_id={$smarty.request.group_id}&fId={$smarty.request.fId}&mId={$MID} {/makeLink}</td>
          <td width="20%" align="center" nowrap class="naGridTitle">{makeLink mod=accessory pg=accessory orderBy=active display="Active" }act=settingsAdd&sId={$smarty.request.sId}&cat_id={$smarty.request.cat_id}&group_id={$smarty.request.group_id}&fId={$smarty.request.fId}&mId={$MID} {/makeLink}</td>
	    </tr>
		{if count($CATEGORY_ACCESSORY) > 0}
        {foreach from=$CATEGORY_ACCESSORY item=accessory}
        <tr class="{cycle name=bg values="naGrid1,naGrid2"}">
          <td  valign="middle" align="center"><input type="checkbox" name="accessory[]" id="accessory_{$accessory.id}" value="{$accessory.id}"></td> 
          <td align="left">{$accessory.name}</td>
		  <td align="center">{$accessory.type}</td> 
          <td align="center">{$accessory.adjust_price}</td>
          <td align="center">{$accessory.adjust_weight}</td>
          <td align="center">{ if $accessory.active eq 'Y'} Yes {else} No {/if}</td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="6" class="msg" align="center" height="30">{$CATEGORY_ACCESSORY_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="6" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
		
      </table></td> 
  </tr>

	<tr class="naGridTitle"> 
      <td colspan=2 valign=center><div align=center> 
	       <input type=submit value="Move to New Item" class="naBtn" name="btnMove" onClick="javascript: document.frm_accessory.action='{makeLink mod=accessory pg=accessory}act=settingsAdd&limit={$smarty.request.limit}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}&group_id={$smarty.request.group_id}{/makeLink}&cat_id='+this.value;document.frm_accessory.submit();">&nbsp; 
	       <input type=submit value="Save" class="naBtn" name="btnSubmit" onClick="javascript: document.frm_accessory_new.btnSubmit.value='Submit';document.frm_accessory_new.submit(); return false;">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr>  
	
	 <tr>
 	   <td colspan="3" height="20"></td>
	 </tr>
	 
 </table>
 
</form><br />