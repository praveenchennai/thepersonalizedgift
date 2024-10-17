<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>
{literal}
<script language="javascript">
function removeImge(accessory_id,field,extn) {
	var req = newXMLHttpRequest();
	req.onreadystatechange = getReadyStateHandler(req, display_remove);
	{/literal}
	//alert(extn);
	if(confirm ("Are you sure want to delete the image"))
		req.open("GET", "{makeLink mod=product pg=ajax_accessory_removeImage}{/makeLink}&field="+field+"&extn="+extn+"&accessory_id="+accessory_id);
	else
		return false;	
	{literal}
	req.send(null);
	history.go(0);
	
}
function display_remove(_var) {
	_var = _var.split('|');
	//alert(_var[0]);
	//alert(_var[1]);
	
	if(_var[0]=="0")
	{
	var e= eval("document.getElementById('Image_"+_var[1]+"')");
	//alert(e.innerHTML);
	e.innerHTML="";
	}
	else
		{
		
		}
	
}
  
////functions for cross sell and product to store..............
function chkval()
{
 //document.admFrm.new_html_desc.value=areaedit_editors.html_desc.getHTML();
	//var rit=document.forms[0].products_related.options;
	var rit1=document.forms[0].store_related.options;

	for (var i=0;i<rit1.length;i++)
	{
		if (i>0)
		{
			document.forms[0].hf2.value=document.forms[0].hf2.value + "," + rit1[i].value;
		}
		else
		{
			document.forms[0].hf2.value=rit1[i].value;
		}		
	}
	
}
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
<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
     <tr align="left">
      <td colspan=3 valign=top><table width="100%" align="center">
        <tr>
          <td nowrap class="naH1"><!--Accessory-->{$smarty.request.sId}</td>
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=$MOD pg=$PG}act=list&limit={$smarty.request.limit}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&aid={$smarty.request.aid}&mId={$MID}&accessory_search={$smarty.request.accessory_search}&limit={$smarty.request.limit}{/makeLink}">{$smarty.request.sId} List</a></td>
        </tr>
      </table></td>
    </tr>
	
		
	{if $FIELDS.0==Y}
      <tr class=naGrid1>
      <td width="40%" align="right" valign=top><div align=right class="element_style">Name1:</div></td>
      <td width="1" valign=top>&nbsp;</td>
      <td><input type="text" name="name" value="{$ACCESSORY.name|escape:'html'}" class="formText"  style="width:320" maxlength="150"></td>
    </tr>
     {/if}
	 {if $FIELDS.16==Y}
	<tr class=naGrid1>
        <td align="right" valign=top>Display Name:</td>
        <td valign=top>&nbsp;</td>
        <td><input type="text" name="display_name" value="{$ACCESSORY.display_name|escape:'html'}" class="formText"  style="width:320" maxlength="150"></td>
      </tr>
	  {/if}
	  {if $FIELDS.17==Y}
	<tr class=naGrid1>
	  <td align="right" valign=top>Cart Name:</td>
	  <td valign=top>&nbsp;</td>
	  <td><input type="text" name="cart_name" value="{$ACCESSORY.cart_name|escape:'html'}" class="formText"  style="width:320" maxlength="150"></td>
    </tr>
	   {/if}
	   {if $FIELDS.20==Y}
    <tr class=naGrid1>
      <td align="right" valign=top><div align=right class="element_style">Display Order:</div></td>
      <td valign=top>&nbsp;</td>
      <td>
	    <input type="text" name="display_order" value="{$ACCESSORY.display_order}" style="width:320">
	     </tr>
	{/if}
	{if $FIELDS.1==Y}      <tr class=naGrid1>
      <td align="right" valign=top>Category:</td>
      <td valign=top>&nbsp;</td>
      <td><select name="accessory_category[]" size="15" multiple style="width:320">
	 {html_options values=$ACCESSORY_CATEGORY.category_id output=$ACCESSORY_CATEGORY.category_name selected=$ACCESSORY_CATEGORY_SELECTEDIDS.category_id}
	          </select>
        <img src="{$GLOBAL.tpl_url}/images/icon_small_info.gif" width="17" height="16" align="top" {popup text="[Press Ctrl to select multiple categories]" fgcolor="#eeffaa"} /></td>
      </tr>
	  {/if}
	  {if $FIELDS.2==Y}
        <tr class=naGrid1>
        <td align="right" valign=top>Type:</td>
        <td valign=top>&nbsp;</td>
        <td><select name="type" style="width:320">
		{html_options values=$TYPE output=$TYPE selected=$ACCESSORY.type}
		</select></td>
      </tr>
	  {/if}
	  {if $FIELDS.3==Y}
      <tr class=naGrid1>
      <td valign=top><div align=right class="element_style">Description:</div></td>
      <td valign=top>&nbsp;</td>
      <td><textarea name="description" cols="50" rows="6" class="formText">{$ACCESSORY.description|escape:'html'}</textarea></td>
    </tr>
	{/if}
	
	
	
	{if $FIELDS.4==Y}
    <tr class=naGrid1>
      <td align="right" valign=top><div align=right class="element_style">Image:</div></td>
      <td valign=top>&nbsp;</td>
      <td><input name="image_extension" type="file" id="image" size="51"></td>
    </tr>
	{if $ACCESSORY.image_extension ne ''}
    <tr class=naGrid1>
      <td valign=top align="center">&nbsp;</td>
      <td valign=top>&nbsp;</td>
      <td valign=top align="left"><div id="Image_image_extension">
	
	<!--{assign var='thumb_image' value=","|explode:$GLOBAL.accessory_thumb_image}
	{assign var='thumb2_image' value=","|explode:$GLOBAL.accessory_thumb2_image}
	{assign var='thumb3_image' value=","|explode:$GLOBAL.accessory_thumb3_image}
	{if $thumb_image.0 > 0 && $thumb_image.1 > 0}
	<img src="{$smarty.const.SITE_URL}/modules/product/images/accessory/thumb/{$ACCESSORY.id}.{$ACCESSORY.image_extension}">
	{elseif $thumb2_image.0 > 0 && $thumb2_image.1 > 0}
	<img src="{$smarty.const.SITE_URL}/modules/product/images/accessory/thumb/{$ACCESSORY.id}_thumb2_.{$ACCESSORY.image_extension}">
	{elseif $thumb3_image.0 > 0 && $thumb3_image.1 > 0}
	<img src="{$smarty.const.SITE_URL}/modules/product/images/accessory/thumb/{$ACCESSORY.id}_thumb3_.{$ACCESSORY.image_extension}">
	
	{else}
	<img src="{$smarty.const.SITE_URL}/modules/product/images/accessory/{$ACCESSORY.id}.{$ACCESSORY.image_extension}" width="60" height="60">
	{/if}
	  -->
	  
	  
	  
	  
	  
	  
	  <img src="{$smarty.const.SITE_URL}/modules/product/images/accessory/thumb/{$ACCESSORY.id}.{$ACCESSORY.image_extension}?{$DATE}">&nbsp;<a href="#" onClick="javascript: removeImge('{$ACCESSORY.id}','image_extension','{$ACCESSORY.image_extension}'); return false;">Remove</a></div></td>
    </tr>
	{/if}
	{/if}
	{if $FIELDS.5==Y}
    <tr class=naGrid1>
      <td align="right" valign=top><div align=right class="element_style">Adjust Price:</div></td>
      <td valign=top>&nbsp;</td>
      <td><input type="text" name="adjust_price" value="{$ACCESSORY.adjust_price}" class="formText"  style="width:320" maxlength="150">&nbsp;<input type=checkbox name="independent_qty" value="Y" {if $ACCESSORY.independent_qty=='Y'} checked{/if}> Independent Of Quantity</td>
    </tr>
	{/if}
	{if $FIELDS.6==Y}
    <tr class=naGrid1>
      <td align="right" valign=top><div align=right class="element_style">Is Price Percentage:</div></td>
      <td valign=top>&nbsp;</td>
      <td><input type=checkbox name="is_price_percentage" value="Y" {if $ACCESSORY.is_price_percentage=='Y'} checked{/if}></td>
    </tr>
	{/if}
	{if $FIELDS.7==Y}
    <tr class=naGrid1>
      <td align="right" valign=top>Adjust Weight:</td>
      <td valign=top>&nbsp;</td>
      <td><input type="text" name="adjust_weight" value="{$ACCESSORY.adjust_weight}" class="formText"  style="width:320" maxlength="150"></td>
    </tr>
	{/if}
	{if $FIELDS.8==Y}
    <tr class=naGrid1>
      <td align="right" valign=top>Is Weight Percentage:</td>
      <td valign=top>&nbsp;</td>
      <td><input type=checkbox name="is_weight_percentage" value="Y" {if $ACCESSORY.is_weight_percentage=='Y'} checked{/if}></td>
    </tr>
	{/if}
	
	{if $FIELDS.9==Y}
	  <tr class=naGrid1>
      <td align="right" valign=top>I need Keywords:</td>
      <td valign=top>&nbsp;</td>
      <td><input type="text" name="keyword" value="{$ACCESSORY.keyword}" class="formText"  style="width:320"></td>
    </tr>
	{/if}
	
	{if $FIELDS.21==Y}
	   <tr class=naGrid1>
		<td align="right" valign=top>Page Title:</td>
		<td align="center" valign=top>&nbsp;</td>
		<td><textarea name="page_title" class="formText"  style="width:378" maxlength="50" tabindex="22">{$ACCESSORY.page_title}</textarea></td>
	  </tr>
	  {/if}
	  {if $FIELDS.22==Y}
	  <tr class=naGrid1>
		<td align="right" valign=top>Meta Description:</td>
		<td align="center" valign=top>&nbsp;</td>
		<td><textarea name="meta_description" class="formText"  style="width:378" maxlength="50" tabindex="22">{$ACCESSORY.meta_description}</textarea></td>
	  </tr>
	  {/if}
	  {if $FIELDS.23==Y}
	  <tr class=naGrid1>
		<td align="right" valign=top>Meta Keywords:</td>
		<td align="center" valign=top>&nbsp;</td>
		<td><textarea name="meta_keywords" class="formText"  style="width:378" maxlength="50" tabindex="22">{$ACCESSORY.meta_keywords}</textarea></td>
	  </tr>
  {/if}
	
	{if $FIELDS.10==Y}
    <tr class=naGrid1>
      <td align="right" valign=top>Addition Description:</td>
      <td valign=top>&nbsp;</td>
      <td><textarea id="html_desc" name="html_desc" rows="20" cols="50">{$ACCESSORY.html_desc}</textarea></td>
    </tr>
	{/if}
	
	{if $FIELDS.11==Y}
    <tr class=naGrid1>
      <td align="right" valign=top>Color Code1:</td>
      <td valign=top>&nbsp;</td>
      <td>#
        <input type="text" name="color1" value="{$ACCESSORY.color1}" style="width:320"> </td>
    </tr>
	{/if}

	{if $FIELDS.12==Y}
    <tr class=naGrid1>
      <td align="right" valign=top>Color Code2:</td>
      <td valign=top>&nbsp;</td>
      <td>#
        <input type="text" name="color2" value="{$ACCESSORY.color2}" style="width:320"> </td>
    </tr>
	{/if}
	
	{if $FIELDS.13==Y}
    <tr class=naGrid1>
      <td align="right" valign=top>Color Code3:</td>
      <td valign=top>&nbsp;</td>
      <td>#
        <input type="text" name="color3" value="{$ACCESSORY.color3}" style="width:320"> </td>
    </tr>
	{/if}
	{if $FIELDS.18==Y}
	  <tr class=naGrid1>
      <td align="right" valign=top>Add to all product under selected category:</td>
      <td valign=top>&nbsp;</td>
      <td><input type=checkbox name="is_add_toproducts" value="Y" ></td>
    </tr>
	{/if}
	
	{if $FIELDS.14==Y}
    <tr class=naGrid1>
      <td align="right" valign=top><div align=right class="element_style">Active:</div></td>
      <td valign=top>&nbsp;</td>
      <td><input type=checkbox name="active" value="Y" {if $ACCESSORY.active=='Y'} checked {elseif $ACCESSORY.id eq''} checked {/if}></td>
    </tr>
	{/if}
	
	
	{if $FIELDS.19==Y}
    <tr class=naGrid1>
      <td align="right" valign=top><div align=right class="element_style">Don't link Option to Products in these stores</div></td>
      <td valign=top>:</td>
      <td>
	  <select name="accessory_stores[]" size="15" multiple style="width:335">
	   {html_options values=$ALL_STORE_LIST.store_id output=$ALL_STORE_LIST.store_name selected=$SELECTED_STORES.store_id}
	 </select>
			  </td>
    </tr>
     {/if}
	
	

	
	{if $FIELDS.15==Y && $smarty.request.storename eq ''}
    <tr class=naGridTitle>
      <td align="left" valign=top colspan="3">Assign {$smarty.request.sId} to store {$STOREMANAGE}</td>
      </tr>
    <tr class=naGrid1>
      <td colspan="3" align="right" valign=top><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr class=naGrid1>
          <td align="right">All Stores </td>
          <td>&nbsp;</td>
          <td>Assigned stores </td>
        </tr>
        <tr class=naGrid1>
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
        <tr class=naGrid1>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr class=naGrid1>
          <td width="40%">&nbsp;</td>
          <td width="10%">&nbsp;</td>
          <td width="50%">&nbsp;</td>
        </tr>
      </table></td>
      </tr>
	  {/if}

	
	  
	  
	  
	  
	  
    <tr class=naGrid1>
      <td align="right" valign=top>&nbsp;</td>
      <td valign=top>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
	  <input type="hidden" name="new_html_desc" value="">
	       <input type="hidden" name="id" value="{$smarty.request.id}">
{if $STORE_PERMISSION.edit=='Y'}		   
		   <input type=submit value="Submit" class="naBtn"   name="btn_acsry_submit" onClick="chkval()">&nbsp; 
          <input type=reset value="Reset" class="naBtn">
{/if}
        </div></td> 
    </tr><tr><td colspan=3 valign=center>&nbsp;</td></tr>
</table>
<input type="hidden" name="cat_id" value="{$smarty.request.cat_id}">
<input type="hidden" name="sId" value="{$smarty.request.sId}">
<input type="hidden" name="fId" value="{$smarty.request.fId}">

dsadsadsadsadsa

</form> 
<script language="javascript">
document.forms[0].elements[0].focus();
</script>