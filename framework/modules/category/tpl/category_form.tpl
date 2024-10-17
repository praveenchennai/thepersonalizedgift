<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;"> 

<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
  	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
    <tr> 
      </tr> 
  
    <tr align="left">
      <td colspan=3 valign=top><table width="100%" align="center">
        <tr>
          <td nowrap class="naH1"> <!--Categories-->{$smarty.request.sId}</td>
          <td nowrap align="right" class="titleLink">{if $FIELDS.24==Y}{if ($smarty.request.bulk eq N)}<a class="linkOneActive" href="{makeLink mod=`$smarty.request.mod` pg=$BULKPG}act=list&id={$category->category_id}&parent_id={$category->parent_id}&store_id={$smarty.request.store_id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}">Bulk Price</a>{/if}{/if}&nbsp;&nbsp;&nbsp;&nbsp;<a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=list{/makeLink}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}&limit={$smarty.request.limit}&category_search={$smarty.request.category_search}&id={$smarty.request.id}">{$smarty.request.sId} List</a></td>
        </tr>
      </table></td>
    </tr>
	 <!--<tr class=naGrid2>
      <td valign=top colspan=3 align="left" class="naGridTitle">{$DISPLAY_PATH}</td>
    </tr>-->
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle">Category Details </td> 
    </tr> 
    {if $FIELDS.0==Y}
	<tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style"> Name:</div></td> 
      <td width="3%" valign=top>&nbsp;</td> 
      <td  width="57%"><input type="text" name="category_name" value="{$FORUMCATEGORY.category_name}" class="formText" size="33" maxlength="50" > </td> 
    </tr> 
	{if $smarty.request.category_id <> ""}
	<tr class=naGrid2> 
	 <td valign=top width=40%><div align=right class="element_style"></div></td> 
      <td width="3%" valign=top>&nbsp;</td> 
	  <td>
	  <input type="button" name="change" value="Change" class="naBtn"  onClick="w=window.open('{makeLink mod=category pg=categorySearch}act=list&cat_id={$smarty.request.category_id}{/makeLink}', 'w', 'width=770,height=600,scrollbars=yes');w.focus(); ">
      <input type="text" name="testval">
      <input type="checkbox" name="chk" value="checkbox" onClick=" {literal}if (document.admFrm.chk.checked == true) {document.admFrm.testval.disabled=true;document.admFrm.change.disabled=true} else {document.admFrm.testval.disabled=false;document.admFrm.change.disabled=false}{/literal}">
      <input type="hidden" name="change_parent_id">
      <input type="hidden" name="level">	  </td>
	</tr>
	{/if}
	{/if}
	{if $FIELDS.1==Y}
    <tr class=naGrid1>
      <td valign=top><div align=right class="element_style"> Description:</div></td>
      <td valign=top>&nbsp;</td>
      <td><div style="width:430px"><textarea name="category_description" id="category_description" style="width:400px" cols="15" rows="10" class="formText">{$FORUMCATEGORY.category_description}</textarea></div></td>
    </tr>
	{/if}
	{if $FIELDS.2==Y}
    <tr class=naGrid2>
      <td valign=top><div align=right class="element_style">Additional Description:</div></td>
      <td valign=top>&nbsp;</td>
      <td>
	  <!-- <textarea id="html_desc" name="html_desc" rows="20" cols="40">{$FORUMCATEGORY.html_desc}</textarea> -->
	  {loadEditor field_name=html_desc width=530 height=400 value=`$FORUMCATEGORY.html_desc`}{/loadEditor}
	 </td>
    </tr>
	{/if}
	{if $FIELDS.3==Y}
    <tr class=naGrid2>
      <td valign=top><div align=right class="element_style">Image:</div></td>
      <td valign=top>&nbsp;</td>
      <td><input name="category_image" type="file" id="category_image"></td>
    </tr>
	
	{if $FORUMCATEGORY.category_image ne ''}
	<tr class=naGrid1>
      <td valign=top align="center">&nbsp;</td>
      <td valign=top>&nbsp;</td>
      <td valign=top align="left"><img src="{$GLOBAL.mod_url}/images/thumb/{$FORUMCATEGORY.category_id}.{$FORUMCATEGORY.category_image}"></td>
    </tr>
	 {/if}
	 {/if}
	 {if $FIELDS.20==Y}
    <tr class=naGrid1>
      <td valign=top><div align=right class="element_style">Mouse Over Image:</div></td>
      <td valign=top>&nbsp;</td>
      <td><input name="category_image_over" type="file" id="category_image_over"></td>
    </tr>
	{/if}
	{if $FORUMCATEGORY.category_m_over_image ne ''}
	<tr class=naGrid2>
      <td valign=top align="center">&nbsp;</td>
      <td valign=top>&nbsp;</td>
      <td valign=top align="left"><img src="{$GLOBAL.mod_url}/images/thumb/m_{$FORUMCATEGORY.category_id}.{$FORUMCATEGORY.category_m_over_image}"></td>
    </tr>
	 {/if}
	 {if $FIELDS.19==Y}	
	 <tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style"> Base Price:</div></td> 
      <td width=1 valign=top>&nbsp;</td> 
      <td><input type="text" name="base_price" value="{$FORUMCATEGORY.base_price}" class="formText" size="10" maxlength="10" ></td> 
    </tr>
	{/if}
	{if $FIELDS.4==Y}
     <tr class=naGrid2>
       <td align="right" valign=top>Display Order:</td>
       <td valign=top>&nbsp;</td>
       <td><input type="text" name="display_order" value="{$FORUMCATEGORY.display_order}" class="formText" size="30" maxlength="25" ><img src="{$GLOBAL.tpl_url}/images/icon_small_info.gif" width="17" height="16" align="top" {popup text="The order which the category displayed in the user side (product accessory listing)" fgcolor="#eeffaa"} /></td>
     </tr>
	 {/if}
	 {if $FIELDS.20==Y}
	<tr class=naGrid1>
       <td align="right" valign=top>Page Title:</td>
       <td valign=top>&nbsp;</td>
       <td><textarea name="page_title" style="width:378" class="formText">{$FORUMCATEGORY.page_title}</textarea></td>
    </tr>
	{/if}
	{if $FIELDS.21==Y}
	<tr class=naGrid1>
       <td align="right" valign=top>Meta Description:</td>
       <td valign=top>&nbsp;</td>
       <td><textarea name="meta_description" style="width:378" class="formText">{$FORUMCATEGORY.meta_description}</textarea></td>
    </tr>
	{/if}
	{if $FIELDS.22==Y}
	<tr class=naGrid1>
       <td align="right" valign=top>Meta Keywords:</td>
       <td valign=top>&nbsp;</td>
       <td><textarea name="meta_keywords" style="width:378" class="formText">{$FORUMCATEGORY.meta_keywords}</textarea></td>
    </tr>
	{/if}
	  {if $FORUMCATEGORY.level ne ''}
			<tr class=naGrid2>
			   <td align="right" valign=top>Level:</td>
			   <td valign=top>&nbsp;</td>
			   <td>{$FORUMCATEGORY.level}</td>
			 </tr>
	 {/if}
	
	{if $FIELDS.5==Y}
	{if $STORE_ID eq ''}
	<tr class=naGrid1>
       <td align="right" valign=top>Show In Admin Side only:</td>
       <td valign=top>&nbsp;</td>
       <td><input type=checkbox name="is_in_ui" value="Y" {if $FORUMCATEGORY.is_in_ui=='Y'} checked{/if}></td>
     </tr>
	 {/if}
	 {/if}
	 
	 {if $FIELDS.6==Y}
	 {if $STORE_ID eq ''}
	 <tr class=naGrid1>
	   <td align="right" valign=top>Store Owner can view:</td>
	   <td valign=top>&nbsp;</td>
	   <td><input type=checkbox name="store_owner_manage" value="Y" {if $FORUMCATEGORY.store_owner_manage=='Y'} checked{/if}></td>
    </tr>
		{/if}
	{/if}
	
	
	{if $FIELDS.7==Y}
	 <tr class=naGrid1>
	   <td align="right" valign=top>Mandatory Fields:</td>
	   <td valign=top>&nbsp;</td>
	   <td><input name="mandatory" type=checkbox id="mandatory" value="Y" {if $FORUMCATEGORY.mandatory=='Y'} checked{/if}></td>
    </tr>
	{/if}
	
	{if $FIELDS.8==Y}
	 <tr class=naGrid1>
      <td align="right" valign=top>Custom Wrap Around Text:</td>
      <td valign=top>&nbsp;</td>
      <td><input type=checkbox name="customization_text_required" value="Y" {if $FORUMCATEGORY.customization_text_required=='Y'} checked{/if}></td>
    </tr>
	{/if}
	
	{if $FIELDS.9==Y}
    <tr class=naGrid1>
      <td align="right" valign=top>Requested Monogram text:</td>
      <td valign=top>&nbsp;</td>
      <td><input type=checkbox name="additional_customization_request" value="Y" {if $FORUMCATEGORY.additional_customization_request=='Y'} checked{/if}></td>
    </tr>
	{/if}
	
	{if $FIELDS.11==Y}
	{if $STORE_ID eq ''}
    <tr class=naGrid1>
      <td align="right" valign=top>Is Monogram:</td>
      <td valign=top>&nbsp;</td>
      <td><input type=checkbox name="is_monogram" value="Y" {if $FORUMCATEGORY.is_monogram=='Y'} checked{/if}></td>
    </tr>
	{/if}
	{/if}
	
	{if $FIELDS.12==Y}	
	 <tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style">Gender:</div></td> 
      <td width=1 valign=top>&nbsp;</td> 
      <td>
	  <input type="text" name="gender" value="{$FORUMCATEGORY.gender}" class="formText" size="30" maxlength="25" ></td> 
    </tr>
	{/if}
	
	{if $FIELDS.13==Y}	
	 <tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style">First Name:</div></td> 
      <td width=1 valign=top>&nbsp;</td> 
      <td>
	  <input type="text" name="firstname" value="{$FORUMCATEGORY.firstname}" class="formText" size="30" maxlength="25" ></td> 
    </tr>
	{/if}
	
    {if $FIELDS.14==Y}	
	 <tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style">Nickname:</div></td> 
      <td width=1 valign=top>&nbsp;</td> 
      <td>
	  <input type="text" name="nickname" value="{$FORUMCATEGORY.nickname}" class="formText" size="30" maxlength="25" ></td> 
    </tr>
	{/if}
	
	{if $FIELDS.15==Y}	
	 <tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style">Sentiment Line-1:</div></td> 
      <td width=1 valign=top>&nbsp;</td> 
      <td>
	  <input type="text" name="sentiment_line_1" value="{$FORUMCATEGORY.sentiment_line_1}" class="formText" size="30" maxlength="25" ></td> 
    </tr>
	{/if}

   {if $FIELDS.16==Y}	
	 <tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style">Sentiment Line-2:</div></td> 
      <td width=1 valign=top>&nbsp;</td> 
      <td>
	  <input type="text" name="sentiment_line_2" value="{$FORUMCATEGORY.sentiment_line_2}" class="formText" size="30" maxlength="25" ></td> 
    </tr>
	{/if}

	
	

{if $FIELDS.17==Y}	
	 <tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style"> Active:</div></td> 
      <td width=1 valign=top>&nbsp;</td> 
      <td><input type=checkbox name="active" value="y" {if isset($FORUMCATEGORY.active) && $FORUMCATEGORY.active=='y'} checked{/if} {if !isset($FORUMCATEGORY.active)} checked{/if}>  </td> 
    </tr>
	{/if}
	
	
	
{foreach from=$smarty.session.adminSess->modules item=module name=modloop}
{if $module->folder == "accessory"}
{assign var = "accessory" value = $module->name}
{/if}
{if $module->folder == "product"}
{assign var = "product" value = $module->name}
{/if}
{/foreach} 	  

{if $product!=""}	
	  <tr class="naGridTitle">
      <td colspan=3 valign=middle align="left">
	   Assigned {$product}
	 </td>
    </tr>
	<tr>
	<td colspan=3>
<table width=100% border=0 cellpadding="5" cellspacing="1"> 
		{if count($ASSIGN_PRODUCTS) > 0}
	    <tr>
		  {if $PRODUCT_FIELDS.1 == Y}
	      <td width="38%" align="left" nowrap class="naGridTitle">Name</td> 
		  {/if}
		   {if $PRODUCT_FIELDS.50 == Y}
	      <td width="38%" align="left" nowrap class="naGridTitle">Retail Shop</td> 
		  {/if}
		  {if $PRODUCT_FIELDS.2 == Y}
		  <td width="12%" align="left" nowrap class="naGridTitle">Brand</td>
		  {/if}
		  {if $PRODUCT_FIELDS.3 == Y}
		  <td width="12%" align="left" nowrap class="naGridTitle">Made In</td>
		  {/if}
		  {if $PRODUCT_FIELDS.22 == Y}
		  <td width="12%" align="left" nowrap class="naGridTitle">Weight</td>
		  {/if}
		  {if $PRODUCT_FIELDS.14 == Y}
		  <td width="12%" align="left" nowrap class="naGridTitle">Price</td>
		  {/if}
		  {if $PRODUCT_FIELDS.29 == Y}
		  <td width="10%" align="left" nowrap class="naGridTitle">Active</td>
		  {/if}
		</tr>
        {foreach from=$ASSIGN_PRODUCTS item=product}
        <tr class="{cycle values="naGrid1,naGrid2"}">
		  {if $PRODUCT_FIELDS.1 == Y}
          <td align="left" valign="middle">{$product->name}</td> 
		  {/if}
		   {if $PRODUCT_FIELDS.50 == Y}
          <td align="left" valign="middle">{$product->storename}</td> 
		  {/if}
		  {if $PRODUCT_FIELDS.2 == Y}
		  <td align="left" valign="middle">{if $product->brand_id > 0}{$product->brand_name}{else}No Brand{/if}</td>
		  {/if}
		  {if $PRODUCT_FIELDS.3 == Y}
		  <td align="left" valign="middle">{if $product->Made}{$product->Made}{else}No Made{/if}</td>
		  {/if}
		  {if $PRODUCT_FIELDS.22 == Y}
		  <td align="left" valign="middle">{$product->weight}&nbsp;lbs</td>
		  {/if}
		  {if $PRODUCT_FIELDS.14 == Y}
		  <td align="left" valign="middle">$&nbsp;{$product->price}</td>
		  {/if}
		  {if $PRODUCT_FIELDS.29 == Y}
		  <td align="left" valign="middle">{if $product->active == Y}Yes{else}No{/if}</td>
		  {/if}
		</tr> 
        {/foreach}
        {else}
         <tr class="naGrid2"> 
          <td colspan="6" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
		</table>	
	</td>
	</tr>
	{/if}

{if $accessory!=""}	
	  <tr class="naGridTitle">
      <td colspan=3 valign=middle align="left">
	   Assigned {$accessory}
	  </td>
    </tr>
	<tr>
	<td colspan=3>
<table width=100% border=0 cellpadding="4" cellspacing="1"> 
		{if count($ASSIGN_ACCESSORIES) > 0}
	    <tr>
		  {if $ACCESSORY_FIELDS.0 == Y}
	      <td width="41%" align="left" nowrap class="naGridTitle">Name</td> 
		  {/if}
		  {if $ACCESSORY_FIELDS.2 == Y}
          <td width="18%" align="center" nowrap class="naGridTitle">Type</td>
		   {/if}
		  {if $ACCESSORY_FIELDS.5 == Y}
          <td width="18%" align="center" nowrap class="naGridTitle">Adjust Price</td>
		   {/if}
		  {if $ACCESSORY_FIELDS.7 == Y}
          <td width="18%" align="center" nowrap class="naGridTitle">Adjust Weight</td>
		   {/if}
		  {if $ACCESSORY_FIELDS.14 == Y}
          <td width="20%" align="center" nowrap class="naGridTitle">Active</td>
		   {/if}
	    </tr>
        {foreach from=$ASSIGN_ACCESSORIES item=accessory}
        <tr class="{cycle name=bg values="naGrid1,naGrid2"}">
		  {if $ACCESSORY_FIELDS.0 == Y}
          <td align="left">{$accessory->name}</td>
		  {/if}
		  {if $ACCESSORY_FIELDS.2 == Y}
		  <td align="center">{$accessory->type}</td> 
		  {/if}
		  {if $ACCESSORY_FIELDS.5 == Y}
          <td align="center">{$accessory->adjust_price}</td>
		  {/if}
		  {if $ACCESSORY_FIELDS.7 == Y}
          <td align="center">{$accessory->adjust_weight}</td>
		  {/if}
		  {if $ACCESSORY_FIELDS.14 == Y}
          <td align="center">{ if $accessory->active eq 'Y'} Yes {else} No {/if}</td>
		  {/if}
        </tr> 
        {/foreach}
        {else}
         <tr class="naGrid2"> 
          <td colspan="6" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table>
	  </td>
	  </tr>
	{/if}
	

	 	

	{if $FIELDS.18==Y}		
      {if count($MODULECATEGORY) > 0}
	  <tr class="naGridTitle">
      <td colspan=3 valign=middle align="left">Modules</td>
    </tr>
	<tr>
	<td colspan="3" valign="middle" class="{cycle values="naGrid1" advance=false}">
		<table width="100%" cellpadding="0" cellspacing="0" >
			<tr >
		 {foreach from=$MODULECATEGORY item=modcategory name=foo}
		 {if $smarty.foreach.foo.index is div by 3}
			</tr><tr >
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
	{/if}



		
	<tr class="naGridTitle"> 
      <td colspan=3 valign=center height="20"><div align=center> 
	  <input type="hidden" name="category_id" value="{$FORUMCATEGORY.category_id}">
	  <input type="hidden" name="parent_id" value="{$smarty.request.parent_id}">
	  {if $STORE_PERMISSION.edit == 'Y'}
	  {if isset($STORE_ID) and $FORUMCATEGORY.is_private eq N}
        <input type=submit value="Submit" class="naBtn">&nbsp; 
		<input type=reset value="Reset" class="naBtn">
		  {else}  
		   <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> {/if}
	 {/if}	  
        </div></td> 
		
    </tr>
	<tr><td colspan=3 valign=center>&nbsp;</td></tr>  
</table>
  </form>