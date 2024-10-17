<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;"> 
<table border=0 width=80% cellpadding=5 cellspacing=0 class=naBrDr> 
  	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}"> 
   
    <tr align="left">
      <td colspan=3 valign=top><table width="100%" align="center">
        <tr>
          <td nowrap class="naH1">Form Blocks</td>
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=block_list{/makeLink}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}">Block List</a></td>
        </tr>
      </table></td>
    </tr>
	 <tr class=naGrid2>
      <td valign=top colspan=3 align="left" class="naGridTitle">&nbsp;</td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">Block Details </span></td> 
    </tr> 
  
	<tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style">Block Title </div></td> 
      <td width="3%" valign=top>:</td> 
      <td  width="57%"><input type="text" name="block_title" value="{$BLOCK_VALUE.block_title}" class="formText" size="33" maxlength="50" > </td> 
    </tr>
	
		<tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style">Display Order </div></td> 
      <td width="3%" valign=top>:</td> 
      <td  width="57%"><input type="text" name="display_order" value="{$BLOCK_VALUE.display_order}" class="formText" size="15" maxlength="5" > </td> 
    </tr>
		<tr class=naGrid1>
		  <td align="right" valign=top>Show in the flyer </td>
		  <td valign=top>&nbsp;</td>
		  <td><label>
		    <select name="block_position">
			{if $BLOCK_VALUE.block_position eq 'Left' }  
		      <option value="Main" >Main</option>
			  <option value="Left" selected="selected">Left</option>
			
			{else}
			  <option value="Main" selected="selected">Main</option>
			  <option value="Left" >Left</option>
			{/if}
	        </select>
		  </label></td>
    </tr>
	
	 <tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style"> Active </div></td> 
      <td width=3% valign=top>:</td> 
      <td><input type=checkbox name="active" value="Y" {if isset($BLOCK_VALUE.active) && $BLOCK_VALUE.active=='Y'} checked{/if} {if !isset($BLOCK_VALUE.active)} checked{/if}>  </td> 
    </tr>
	
	 <tr align="center" > 
      <td colspan="3" valign=top> 
	  
	  <!-- *********** Attributes ******************* -->
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0" >
	  <tr height="25"> <td colspan="8" class="naGridTitle">&nbsp;Select SubTitles </td>
	  </tr>
  <tr>
	{assign var="col" value="1"}
	{foreach from=$GRP_ATTRIBUTES item=item_attributes name=loop2}
    <td width="25%">&nbsp;
	 {html_checkboxes name="attributeId" values=`$item_attributes.attr_group_id` selected=$SELECTED_ATTRIBUTES.attribute_id onClick='newSelect(this.value);'} 
		<!-- <input type="checkbox" name="attributeId[]" value="{$item_attributes.attr_group_id}" > -->
		&nbsp;{$item_attributes.group_name}	</td>
    <td width="1%" >&nbsp;</td>
		{if $col%4 eq 0}			</tr><tr><td >&nbsp;</td></tr><tr>
		{/if}
		{assign var="col" value="`$col+1`"}
	{/foreach}
    </tr>
   </table>
	 <!-- ******************** Attributes  ******************** -->
	
	 	  <!-- *********** Features ******************* -->
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0" >
	  <tr height="25"> <td colspan="8" class="naGridTitle">&nbsp;Select Fields</td>
	  </tr>
  <tr>
	{assign var="col2" value="1"}
	{foreach from=$GRP_FEATURES item=item_features name=loop3}
    <td  width="25%">&nbsp;
	 {html_checkboxes name="featureId" values=`$item_features.feature_id` selected=$SELECTED_FEATURES.feature_id onClick='newSelect(this.value);'} 
		<!--<input type="checkbox" name="featureId[]" value="{$item_features.feature_id}" >-->
		&nbsp;{$item_features.label}	</td>
    <td width="1%" >&nbsp;</td>
		{if $col2%4 eq 0}			</tr><tr><td >&nbsp;</td></tr><tr>
		{/if}
		{assign var="col2" value="`$col2+1`"}
	{/foreach}
    </tr>
   </table>
	 <!-- ******************** Features  ******************** -->	   </td> 
    </tr>
			
	<tr class="naGridTitle"> 
      <td colspan=3 valign=center height="20"><div align=center> 
	  <input type="hidden" name="block_id" value="{$BLOCK_VALUE.block_id}">
	  <input type="hidden" name="forms_id" value="{$FORM_ID}">
	
		   <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn">
        </div></td> 
    </tr>
	<tr><td colspan=3 valign=center>&nbsp;</td></tr>  
</table>
  </form>