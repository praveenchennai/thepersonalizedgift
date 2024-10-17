<table border=0 width=80% cellpadding=5 cellspacing=0 class=naBrDr> 
  <form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;"> 
    <tr> 
      </tr> 
   
    <tr align="left">
      <td colspan=3 valign=top><table width="100%" align="center">
        <tr>
          <td nowrap class="naH1">Categories</td>
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=category pg=index}act=list{/makeLink}">Category List</a></td>
        </tr>
      </table></td>
    </tr>
	 <tr class=naGrid2>
      <td valign=top colspan=3 align="left" class="naGridTitle">{$DISPLAY_PATH}</td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">Category Details </span></td> 
    </tr> 
    <tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style"> Name </div></td> 
      <td width=1 valign=top>:</td> 
      <td><input type="text" name="category_name" value="{$FORUMCATEGORY.category_name}" class="formText" size="33" maxlength="50" > </td> 
    </tr> 
    <tr class=naGrid1>
      <td valign=top><div align=right class="element_style"> Description </div></td>
      <td valign=top>:</td>
      <td><textarea name="category_description" cols="30" rows="4" class="formText">{$FORUMCATEGORY.category_description}</textarea></td>
    </tr>
    <tr class=naGrid1>
      <td valign=top><div align=right class="element_style">Image</div></td>
      <td valign=top>:</td>
      <td><input name="category_image" type="file" id="category_image"></td>
    </tr>
	{if $FORUMCATEGORY.category_image ne ''}
	<tr class=naGrid1>
      <td valign=top align="center">&nbsp;</td>
      <td valign=top>&nbsp;</td>
      <td valign=top align="left"><img src="{$GLOBAL.mod_url}/images/thumb/{$FORUMCATEGORY.category_id}.{$FORUMCATEGORY.category_image}"></td>
    </tr>
	 {/if}
	
     <tr class=naGrid1>
       <td align="right" valign=top>Display Order </td>
       <td valign=top>:</td>
       <td><input type="text" name="display_order" value="{$FORUMCATEGORY.display_order}" class="formText" size="30" maxlength="25" ><img src="{$GLOBAL.tpl_url}/images/icon_small_info.gif" width="17" height="16" align="top" {popup text="The order which the category displayed in the user side (product accessory listing)" fgcolor="#eeffaa"} /></td>
     </tr>
	  {if $FORUMCATEGORY.level ne ''}
     <tr class=naGrid1>
       <td align="right" valign=top>Level</td>
       <td valign=top>:</td>
       <td>{$FORUMCATEGORY.level}</td>
     </tr>
	 {/if}
	<tr class=naGrid1>
       <td align="right" valign=top>Show In Admin Side only: </td>
       <td valign=top>:</td>
       <td><input type=checkbox name="is_in_ui" value="Y" {if $FORUMCATEGORY.is_in_ui=='Y'} checked{/if}></td>
     </tr>
	 <tr class=naGrid1>
	   <td align="right" valign=top>Store Owner can Manage</td>
	   <td valign=top>:</td>
	   <td><input type=checkbox name="store_owner_manage" value="Y" {if $FORUMCATEGORY.store_owner_manage=='Y'} checked{/if}></td>
    </tr>
	 <tr class=naGrid1>
	   <td align="right" valign=top>Mandatory Fields</td>
	   <td valign=top>:</td>
	   <td><input name="mandatory" type=checkbox id="mandatory" value="Y" {if $FORUMCATEGORY.mandatory=='Y'} checked{/if}></td>
    </tr>
	 <tr class=naGrid1>
      <td align="right" valign=top>Custom Wrap Around Text </td>
      <td valign=top>:</td>
      <td><input type=checkbox name="customization_text_required" value="Y" {if $FORUMCATEGORY.customization_text_required=='Y'} checked{/if}></td>
    </tr>
    <tr class=naGrid1>
      <td align="right" valign=top>Additional Customization Request is needed </td>
      <td valign=top>:</td>
      <td><input type=checkbox name="additional_customization_request" value="Y" {if $FORUMCATEGORY.additional_customization_request=='Y'} checked{/if}></td>
    </tr>
    <tr class=naGrid1>
      <td align="right" valign=top>Accessory Category </td>
      <td valign=top>&nbsp;</td>
      <td><input type=checkbox name="accessory_category" value="Y" {if $FORUMCATEGORY.accessory_category=='Y'} checked{/if}></td>
    </tr>
    <tr class=naGrid1>
      <td align="right" valign=top>Is Monogram</td>
      <td valign=top>&nbsp;</td>
      <td><input type=checkbox name="is_monogram" value="Y" {if $FORUMCATEGORY.is_monogram=='Y'} checked{/if}></td>
    </tr>
	 <tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style"> Active </div></td> 
      <td width=1 valign=top>:</td> 
      <td><input type=checkbox name="active" value="y" {if $FORUMCATEGORY.active=='y'} checked{/if}>  </td> 
    </tr> 	
      {if count($MODULECATEGORY) > 0}
	  <tr class="naGridTitle">
      <td colspan=3 valign=middle align="left">Modules</td>
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
        {/if}    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
	  <input type="hidden" name="category_id" value="{$FORUMCATEGORY.category_id}">
	  <input type="hidden" name="parent_id" value="{$smarty.request.parent_id}">
          <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr>
	<tr><td colspan=3 valign=center>&nbsp;</td></tr> 
  </form> 
</table>
