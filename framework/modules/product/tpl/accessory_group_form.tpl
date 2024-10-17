{literal}
<script language="javascript">
////functions for cross sell and product to store..............

function addSrcToDestList(id) 
	{	
		if(id==1)
		{
		//destList	= window.document.forms[0].products_related;
		//srcList 	= window.document.forms[0].products_all; 
		}else
		{
		destList	= window.document.forms[0].store_related;
		srcList 	= window.document.forms[0].store_all; 
		}
	
	var len = destList.length;
	for(var i = 0; i < srcList.length; i++) 
	{
	if ((srcList.options[i] != null) && (srcList.options[i].selected)) {
	
	var found = false;
	for(var count = 0; count < len; count++) 
	{
		if (destList.options[count] != null) 
		{
			if (srcList.options[i].text == destList.options[count].text) 
			{
			found = true;
			break;				
			}
		}
	}
	
	if (found != true) 
	{
	destList.options[len] = new Option(srcList.options[i].text,srcList.options[i].value); 
	len++;
	
	
				 }
		  }
	   }
	  return false;
	}
		
	function deleteFromDestList(id) 
	{	
		if(id==1)
		{
		var destList  = window.document.forms[0].products_related;
		}else
		{
		var destList  = window.document.forms[0].store_related;
		}
	var len = destList.options.length;
	for(var i = (len-1); i >= 0; i--) {
	if ((destList.options[i] != null) && (destList.options[i].selected == true)) {
	destList.options[i] = null;
		  }
	   }
	     return false;
	}

/* from store */
function _interchange(frm, _all, flag) {
	if(flag == 'add') {
		var _from = document.getElementById("all_categories");
		var _to   = document.getElementById("store_categories");
	} else {
		var _to   = document.getElementById("all_categories");
		var _from = document.getElementById("store_categories");
	}
	if(_all) {
		for(var i = 0;i < _from.length;i++){
			_from.options[i].selected = true;
		}
	}
	for(var i = 0;i < _from.length;i++){
		if(_from.options[i].selected == true) {
			_to.options[_to.length] = new Option(_from.options[i].text, _from.options[i].value);
			_from.options[i] = null;
			i--;
		}
	}
}


</script>
{/literal}
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;"> 
	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}">
<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
   <tr align="left">
      <td colspan=3 valign=top><table width="100%" align="center">
        <tr>
          <td nowrap class="naH1"><!--Groups-->            {$smarty.request.sId}</td>
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=grouplist&limit={$smarty.request.limit}&sId={$smarty.request.sId}&mId={$MID}&fId={$smarty.request.fId}{/makeLink}">{$smarty.request.sId} List</a></td>
        </tr>
      </table></td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>
      </td>
    </tr>
    {/if}
	{if $FIELDS.0==Y}
	 <tr class=naGrid2> 
      <td valign=top width=408><div align=right class="element_style">Name</div></td> 
      <td width=3 valign=top>:</td> 
      <td width="605"><input type="text" name="group_name" value="{$ACCESSORY_NAME.group_name}" class="formText"  style="width:200" maxlength="150"></td> 
    </tr>
	{/if}
	{if $FIELDS.1==Y}
	  <tr class=naGrid2>
    <td align="right" valign=top>Description</td>
    <td align="center" valign=top>:</td>
    <td><textarea id="description" name="description" cols="60" rows="9" class="formText">{$ACCESSORY_NAME.description}</textarea></td>
  </tr>
  {/if}
   <tr class=naGrid2> 
      <td valign=top width=408><div align=right class="element_style">Display Order</div></td> 
      <td width=3 valign=top>:</td> 
      <td width="605"><input type="text" name="display_order" value="{$ACCESSORY_NAME.display_order}" class="formText"  style="width:200" maxlength="150"></td>
   </tr>
  {if $FIELDS.2==Y}
	 <tr class=naGrid2> 
      <td valign=top width=408><div align=right class="element_style">Show In all Stores</div></td> 
      <td width=3 valign=top>:</td> 
      <td width="605"><input type=checkbox name="parameter" value="Y" {if $ACCESSORY_NAME.parameter=='Y'} checked{/if}></td> 
    </tr>
	{/if}

	<!--{if $FIELDS.3==Y}
	 <tr align="left" class=naGridTitle>
       <td colspan="3" valign=top>Assign {$smarty.request.sId} to store</td>
    </tr>
     <tr class=naGrid2>
       <td colspan="3" align="right" valign=top><table width="100%"  border="0" cellspacing="0" cellpadding="0">
           <tr class=naGrid2>
             <td align="right">All Stores </td>
             <td>&nbsp;</td>
             <td>Assigned stores </td>
           </tr>
           <tr class=naGrid2>
             <td align="right"><span class="element_style">
               <select name="store_all" size="10" multiple id="store_all" style="width:200">
  					 {html_options values=$STORE_LIST.id output=$STORE_LIST.name selected=$SELECTEDIDS.id}
	   			</select>
             </span></td>
             <td align="center" valign="middle" ><table border="0" cellspacing="0" cellpadding="0">
                 <tr>
                   <td align="center"><input type="button" class="naBtn" name="add2" value="Add &gt;&gt;" style="width:100px; " onClick="return addSrcToDestList(2)"></td>
                 </tr>
                 <tr>
                   <td height="5" align="center">&nbsp;</td>
                 </tr>
                 <tr>
                   <td align="center"><input type="button" class="naBtn" name="remove2" value="&lt;&lt; Remove" style="width:100px; " onClick=" return deleteFromDestList(2)"></td>
                 </tr>
             </table></td>
             <td><span class="element_style">
               <select name="store_related" size="10" multiple id="store_related" style="width:200">
					{html_options values=$RELSTORE.id output=$RELSTORE.name selected=$SELECTEDIDS.id}
               </select>
               <input name="hf2" type="hidden" id="hf2" value="">
             </span></td>
           </tr>
           <tr class=naGrid2>
             <td>&nbsp;</td>
             <td>&nbsp;</td>
             <td>&nbsp;</td>
           </tr>
           <tr class=naGrid2>
             <td width="40%">&nbsp;</td>
             <td width="10%">&nbsp;</td>
             <td width="50%">&nbsp;</td>
           </tr>
       </table></td>
     </tr>
	 {/if}-->
	 {if $FIELDS.4==Y}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">Group Categories</span></td> 
    </tr>
     <td colspan=3>
	 <table width="100%"  border="0" cellspacing="3" cellpadding="5" class=naGrid2>
   		<tr>
   		{foreach from=$CATEGORY_IS_IN_UI_ALL item=cate_is_all  name=foo}
		{if $smarty.foreach.foo.index is div by 3}
		</tr><tr>
		{ /if }
		<td colspan=3 valign=center valign="middle"><table><tr><td>{html_checkboxes id="checkbox" name='category' values=$cate_is_all.category_id  selected=$CATEGORY_GROUP.category_id separator='&nbsp;'}</td><td>{$cate_is_all.category_name}</td></tr></table></td>
		
		{/foreach}
		{if $smarty.foreach.foo.index mod 3 eq 0}
		<td width="5%" nowrap valign="middle">&nbsp;</td>
		<td  width="27%" align="left">&nbsp;</td>
		<td width="5%" nowrap valign="middle">&nbsp;</td>
		<td  width="27%" align="left">&nbsp;</td>
		{ /if }
		{if $smarty.foreach.foo.index mod 3 eq 1}
		<td width="5%" nowrap valign="middle">&nbsp;</td>
		<td  width="27%" align="left">&nbsp;</td>
		{ /if }
		</tr>
	</table>
   
    </tr> 
	{/if}
  <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
	       <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr><tr><td colspan=3 valign=center>&nbsp;</td></tr>
</table>
</form>