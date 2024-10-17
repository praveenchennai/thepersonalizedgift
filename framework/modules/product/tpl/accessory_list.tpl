
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
<script language="javascript">
{literal}
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
	/// checking wether fields are selected 20-07-07
	var chk_flag = 0;
	if(document.frm_accessory.mass_field1.checked)
	{var chk_flag = 1;  } 
	if(document.frm_accessory.mass_field2.checked)
	{var chk_flag = 1;  } 
	if(document.frm_accessory.mass_field3.checked)
	{var chk_flag = 1;  } 
	if(document.frm_accessory.mass_field4.checked)
	{var chk_flag = 1;  } 
	if(document.frm_accessory.mass_field5.checked)
	{var chk_flag = 1;  } 
	if(document.frm_accessory.mass_field6.checked)
	{var chk_flag = 1;  } 
	if(document.frm_accessory.mass_field7.checked)
	{var chk_flag = 1;  } 
	if(document.frm_accessory.mass_field8.checked)
	{var chk_flag = 1;  } 
	if(document.frm_accessory.mass_field9.checked)
	{var chk_flag = 1;  } 
	if(document.frm_accessory.mass_field10.checked)
	{var chk_flag = 1;  } 
	if(document.frm_accessory.mass_field11.checked)
	{var chk_flag = 1;  } 
	if(document.frm_accessory.mass_field12.checked)
	{var chk_flag = 1;  } 
	if(document.frm_accessory.mass_field13.checked)
	{var chk_flag = 1;  } 
	if(document.frm_accessory.mass_field14.checked)
	{var chk_flag = 1;  } 
	if(document.frm_accessory.mass_field15.checked)
	{var chk_flag = 1;  } 
	if(document.frm_accessory.mass_field16.checked)
	{var chk_flag = 1;  } 
	if(document.frm_accessory.mass_field17.checked)
	{var chk_flag = 1;  } 
	
	if(chk_flag == 0 )
	{
		 alert("Please select atleast one field for massupdate");
		 return false; 
	} 
	else
	{
		return true;
	}
	
	
	
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
	
	function fnClick(){
	document.frm_accessory.action=('{/literal}{makeLink mod=accessory pg=accessory}act=form&sId={$smarty.request.sId}&cat_id={$smarty.request.cat_id}&fId={$smarty.request.fId}&limit={$smarty.request.limit}&flag=1&mId={$MID}{/makeLink}{literal}');
	document.frm_accessory.submit();
	}
	
	 
{/literal}
</script>
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
          <td nowrap class="naH1"><!--Accessory-->{$smarty.request.sId}
		  <input type="hidden" name="limit"  value="{$smarty.request.limit}"/></td> 
          <td nowrap align="right" class="titleLink">&nbsp;</td> 
        </tr> 
		<tr> 
          <td nowrap >{$CATEGORY_PATH}</td> 
          <td nowrap align="right" class="titleLink">
{if $STORE_PERMISSION.add == 'Y'}		  
		  <a  href="{makeLink mod=$MOD pg=$PG}act=form&sId={$smarty.request.sId}&cat_id={$smarty.request.cat_id}&fId={$smarty.request.fId}&mId={$MID}&limit={$smarty.request.limit}&sel=accessory{/makeLink}">Add New {$smarty.request.sId} </a>&nbsp;&nbsp;
		 <a class="linkOneActive" href="#" onclick="fnClick();">Duplicate {$smarty.request.sId}</a>
		  </td> 
{/if}
        </tr> 
      </table></td> 
  </tr>
  <tr>
   <td valign=top class="naGrid1"><table width="80%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="3%">
{if $STORE_PERMISSION.delete == 'Y'}	
	{if count($ACCESSORY_LIST) > 0}<a class="linkOneActive" href="#" onclick="javascript: document.frm_accessory.action='{makeLink mod=accessory pg=accessory}act=delete&sId={$smarty.request.sId}&cat_id={$smarty.request.cat_id}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}';document.frm_accessory.submit();">Delete</a>{/if}</td>
{/if}
    <td width="1%">&nbsp;</td>
   <td width="12%" align="center">&nbsp;Category&nbsp;:</td>
	  <td width="40%"><input type="hidden" name="hid_cat" value="{$smarty.request.cat_id}" />
	  <select name=cat_id  style="width:185px"onchange="window.location.href='{makeLink mod=accessory pg=accessory}act=list&limit={$smarty.request.limit}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}{/makeLink}&cat_id='+this.value">
        <option value="">-- SELECT A CATEGORY --</option>
	 { if $SELECT_DEFAULT ne '' }
	 <option value="" selected="selected">{$SELECT_DEFAULT}</option>
	 {/if}
       {html_options values=$CATEGORY.category_id output=$CATEGORY.category_name selected=`$smarty.request.cat_id`}
	   </select>	   </td>
	  <td width="11%" align="right"><input type="text" name="accessory_search" value="{$ACCESSORY_SEARCH_TAG}" /></td>
	  <td width="10%"><input name="btn_search" type="submit" class="naBtn" value="Search" /></td>
	  <td width="1%">&nbsp;</td>
    <td width="9%" nowrap><strong>Results per page:</strong></td>
	<td width="13%">{$ACCESSORY_LIMIT}</td>
  </tr>
</table></td>
    </tr> 
  <tr> 
    <td><table width=100% border=0 cellpadding="2" cellspacing="0"> 
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_accessory,'del_id[]')"></td>
          <td width="50%" align="left" nowrap class="naGridTitle">{makeLink mod=accessory pg=accessory orderBy=name display="Name" }act=list&subact=list&sId={$smarty.request.sId}&cat_id={$smarty.request.cat_id}&fId={$smarty.request.fId}&mId={$MID} {/makeLink}</td> 
          <td width="15%" align="center" nowrap class="naGridTitle">{makeLink mod=accessory pg=accessory orderBy=type display="Type" }act=list&subact=list&sId={$smarty.request.sId}&cat_id={$smarty.request.cat_id}&fId={$smarty.request.fId}&mId={$MID} {/makeLink}</td>
          <td width="15%" align="center" nowrap class="naGridTitle">{makeLink mod=accessory pg=accessory orderBy=adjust_price display="Adjust Price" }act=list&subact=list&sId={$smarty.request.sId}&cat_id={$smarty.request.cat_id}&fId={$smarty.request.fId}&mId={$MID} {/makeLink}</td>
          <td width="15%" align="center" nowrap class="naGridTitle">{makeLink mod=accessory pg=accessory orderBy=adjust_weight display="Adjust Weight" }act=list&subact=list&sId={$smarty.request.sId}&cat_id={$smarty.request.cat_id}&fId={$smarty.request.fId}&mId={$MID} {/makeLink}</td>
          <td width="20%" align="center" nowrap class="naGridTitle">{makeLink mod=accessory pg=accessory orderBy=active display="Active" }act=list&subact=list&sId={$smarty.request.sId}&cat_id={$smarty.request.cat_id}&fId={$smarty.request.fId}&mId={$MID} {/makeLink}</td>
	    </tr>
		{if count($ACCESSORY_LIST) > 0}
        {foreach from=$ACCESSORY_LIST item=accessory}
        <tr class="{cycle name=bg values="naGrid1,naGrid2"}">
          <td  valign="middle" align="center"><input type="checkbox" name="del_id[]" value="{$accessory->id}"></td> 
          <td align="left"><a class="linkOneActive" href="{makeLink mod=$MOD pg=$PG}act=form&id={$accessory->id}&cat_id={$smarty.request.cat_id}&limit={$smarty.request.limit}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}&orderBy={$ORDERBY}{/makeLink}">{$accessory->name}</a></td>
		  <td align="center">{$accessory->type}</td> 
          <td align="center">{$accessory->adjust_price}</td>
          <td align="center">{$accessory->adjust_weight}</td>
          <td align="center">{ if $accessory->active eq 'Y'} Yes {else} No {/if}</td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="6" class="msg" align="center" height="30">{$ACCESSORY_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="6" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr>
 <tr><td colspan="3">
 </td></tr></table>
{if $STORE_PERMISSION.edit == 'Y'}	 
<table border=0 width="80%" cellpadding=5 cellspacing=0 >
             <tr>
               <td colspan=12 class="naGridTitle" align="left">
			   <table width="100%" border="0" class="naGridTitle">
  <tr>
<td align="left" width="100%"> <span class="group_style">Mass Updates</span></td>
  <td align="right" nowrap><strong>Append  to the existing </strong><input name="append" type="checkbox" id="append" value="Y" checked></td>
  </tr>
</table>			  </td>
			 </tr>
              <tr class=naGrid1>
                <td colspan="12" height="10" valign=top nowrap>&nbsp;</td>
              </tr>
              <tr class=naGrid1>
			  {if $FIELDS.0==Y}
      <td width="10%" align="right" valign=top>Name</td>
      <td width="2%" valign=top>:</td>
      <td width="29%"><input type="text" name="name" value="{$ACCESSORY.name}" class="formText"  style="width:200" maxlength="150"></td>
      <td width="1%">&nbsp;</td>
	  {/if}
	  {if $FIELDS.5==Y}
      <td width="9%" align="right" valign="top">Adjust Price</td>
      <td width="2%" valign="top">:</td>
      <td width="26%"><input type="text" name="adjust_price" value="{$ACCESSORY.adjust_price}" class="formText"  style="width:200" maxlength="150" /></td>
	  {/if}
	   {if $FIELDS.6==Y}
	  <td width="1%" align="right"><input type="checkbox" name="is_price_percentage" value="Y" /></td>
	  <td colspan="2">Is  Percentage</td>
	  {/if}              </tr>
      
	  
	  
	  
<tr class=naGrid1>
	  {if $FIELDS.16==Y}
        <td align="right" valign=top>Display name</td>
        <td valign=top>:</td>
        <td><input type="text" name="display_name" value="{$ACCESSORY.display_name}" class="formText"  style="width:200" maxlength="150"></td>
        <td>&nbsp;</td>
		{/if}
		{if $FIELDS.7==Y}
        <td align="right" valign="top">Adjust Weight </td>
        <td valign="top">:</td>
        <td valign="top"><input type="text" name="adjust_weight" value="{$ACCESSORY.adjust_weight}" class="formText"  style="width:200" maxlength="150" /></td>
		{/if}
		
		
	
		{if $FIELDS.8==Y}
		<td align="right" valign="top"><input type="checkbox" name="is_weight_percentage" value="Y" /></td>
		<td colspan="2" valign="top">Is  Percentage</td>
		{/if}      </tr>	  
	  
	  
	    
	  <tr class=naGrid1>
	  {if $FIELDS.20==Y}
        <td align="right" valign=top>Display Order</td>
        <td valign=top>:</td>
        <td><input type="text" name="display_order" value="{$ACCESSORY.display_order}" class="formText"  style="width:200" maxlength="150"></td>
        <td>&nbsp;</td>
		{/if}
		
        <td align="right" valign="top">&nbsp;</td>
        <td valign="top">&nbsp;</td>
        <td valign="top">&nbsp;</td>
		
		
	
		
		<td align="right" valign="top">&nbsp;</td>
		<td colspan="2" valign="top">&nbsp;</td>
		      </tr>	  
	  
	  
	  
	  
	  
      <tr class=naGrid1>
 {if $FIELDS.1==Y}
        <td align="right" valign=top>Category</td>
        <td valign=top>:</td>
        <td colspan="5"><select name="accessory_category[]" multiple size="20" style="width:375">
		<option value=''>-- Select Category --</option>
	 <!--{html_options values=$CATEGORY.category_id output=$CATEGORY.category_name}-->
	 {html_options values=$ACCESSORY_CATEGORY.category_id output=$ACCESSORY_CATEGORY.category_name}
	   </select></td>
        {/if}
		
		{if $FIELDS.7==Y}        {/if}
		
		
	
		{if $FIELDS.8==Y}
		<td align="right" valign="top"></td>
		<td colspan="2" valign="top"></td>
		{/if}      
		
		      </tr>
			  
			  
			  
		
		
	 <!--  <tr class=naGrid1>
	 {if $FIELDS.6==Y}
        <td align="right" valign=top><input type="checkbox" name="is_price_percentage" value="Y" /></td>
        <td valign=top>:</td>
        <td>Is Price Percentage</td>
        <td>&nbsp;</td>
		{/if}
		{if $FIELDS.8==Y}
        <td align="right" valign="top"><input type="checkbox" name="is_weight_percentage" value="Y" /></td>
        <td valign="top">:</td>
        <td colspan="4">Is Weight Percentage</td>
        {/if}      </tr>-->
      <tr class=naGrid1>
	  {if $FIELDS.2==Y}
        <td align="right" valign=top>Type</td>
        <td valign=top>:</td>
        <td><select name="type" style="width:150">
		<option value=''>-- Select Type --</option>
		{html_options values=$TYPE output=$TYPE}
		</select></td>
        <td>&nbsp;</td>
		{/if}
		{if $FIELDS.14==Y}
        <td align="right" valign="top"><span class="element_style">Active</span></td>
        <td valign="top">:</td>
        <td colspan="4"><input type="checkbox" name="active" value="Y" checked="checked" /></td>
        {/if}      </tr>
		{if $FIELDS.3==Y}
	    <tr class=naGrid1>
		<td align="right" valign=top><span class="element_style">Description</span></td>
        <td valign=top>:</td>
        <td valign="top"><textarea name="description" cols="35" rows="6" class="formText">{$ACCESSORY.description}</textarea></td>
        <td valign="top">&nbsp;</td>
		<td align="right" valign="top"></td>
        <td valign="top">&nbsp;</td>
        <td colspan="4" valign="top"></td>
    </tr>
			  {/if}
		{if $FIELDS.9==Y}
		 <tr class=naGrid1>
		<td align="right" valign=top>I need Keywords</td>
        <td valign=top>:</td>
        <td valign="top"><input type="text" name="keyword" value="{$ACCESSORY.keyword}" class="formText"  style="width:200" /></td>
		<td valign="top">&nbsp;</td>
        <td align="right" valign="top">&nbsp;</td>
        <td valign="top">&nbsp;</td>
        <td colspan="4" valign="top">&nbsp;</td>
        </tr>
	    {/if} 
	    <tr class=naGrid1>
		{if $FIELDS.11==Y}
        <td align="right" valign=top>Color Code1</td>
        <td valign=top>:</td>
        <td valign="top">#
        <input type="text" name="color1" value="{$ACCESSORY.color1}"></td>
		{/if}
		{if $FIELDS.12==Y}
        <td valign="top">&nbsp;</td>
        <td align="right" valign="top">Color Code2</td>
        <td valign="top">:</td>
        <td colspan="4" valign="top">#
          <input type="text" name="color2" value="{$ACCESSORY.color2}" /></td>
        {/if}      </tr>
	  {if $FIELDS.12==Y}
	    <tr class=naGrid1>
		<td align="right" valign=top>Color Code3</td>
        <td valign=top>:</td>
        <td valign="top">#
          <input type="text" name="color3" value="{$ACCESSORY.color3}" /></td>
        <td valign="top">&nbsp;</td>
		<td align="right" valign="top">&nbsp;</td>
        <td valign="top">&nbsp;</td>
        <td colspan="4" valign="top">&nbsp;</td>
      </tr>
	  {/if}
	   {if $FIELDS.10==Y}
	    <tr class=naGrid1>
		<td align="right" valign=top>Addition Description</td>
        <td valign=top>:</td>
        <td colspan="5" valign="top"><textarea id="html_desc" name="html_desc" rows="20" cols="30">{$ACCESSORY.html_desc}</textarea></td>
        <td valign="top">&nbsp;</td>
        <td width="1%" valign="top">&nbsp;</td>
        <td width="12%" valign="top">&nbsp;</td>
      </tr>
	  {/if}
	  
	  
	
	  
	  
	  
	  
	  
	  
	  
      <tr class=naGrid1>
        <td colspan="12" height="10" valign=top nowrap>	  
				
		{if $FIELDS.11==Y && $smarty.request.storename eq ''}
		
		
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr class=naGrid1>
          <td align="right">All Stores </td>
          <td>&nbsp;</td>
          <td>Assigned stores </td>
        </tr>
        <tr class=naGrid1>
          <td align="right">
		
		
		  
		  <span class="element_style">
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
      </table>
		
		{/if}
		<!---  stores 19-0707 ------------------- -->
				
		{if $FIELDS.19==Y}
		 <tr class=naGrid1>
		<td colspan="3" align="right" valign=top><span class="element_style">Don't link Option to Products in these stores</span>          </td>
        <td valign="top">&nbsp;</td>
		<td colspan="6" align="left" valign="top"> <select name="accessory_stores[]" size="15" multiple style="width:200">
	   {html_options values=$ALL_STORE_LIST.store_id output=$ALL_STORE_LIST.store_name selected=$SELECTED_STORES.store_id}
	 </select></td>
      </tr>
		{/if}
		{if $FIELDS.18==Y}
	  <tr class=naGrid1>
      <td colspan="10" align="right" valign=top><div align="left">Add to all product under selected category :
          <input type=checkbox name="is_add_toproducts" value="Y" >
      </div></td>
    </tr>
	{/if}
		
		 <tr align="left" class=naGrid1>
		<td colspan="10" valign=top>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="25" colspan="4" class="naGridTitle">&nbsp;Update selected fields </td>
  </tr>
   <tr>
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field1" value="Y">&nbsp;Name </td>
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field2" value="Y">&nbsp;Adjust Price</td>
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field3" value="y">&nbsp;Adjust Weight</td>
	 <td width="25%">&nbsp;<input type="checkbox" name="mass_field16" value="y">&nbsp;Display name</td>
     </tr>
   <tr>
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field17" value="y">&nbsp;Display Order</td>
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field4" value="y">&nbsp;Category</td>
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field5" value="Y">&nbsp;Type </td>
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field6" value="Y">&nbsp;Active</td>
     </tr>
   <tr>
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field7" value="Y">&nbsp;Description</td>
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field8" value="Y">&nbsp;I need Keywords </td>

    <td width="25%">&nbsp;<input type="checkbox" name="mass_field9" value="Y">&nbsp;Color Code1 </td>
    <td width="25%">&nbsp;<input type="checkbox" name="mass_field10" value="Y">&nbsp;Color Code2</td>
      </tr>
    <tr>
	<td width="25%">&nbsp;<input type="checkbox" name="mass_field11" value="y">&nbsp;Color Code3</td>

	  <td width="25%">&nbsp;<input type="checkbox" name="mass_field12" value="y">&nbsp;Addition Description</td>

    <td width="25%">&nbsp;<input type="checkbox" name="mass_field13" value="Y">&nbsp;Assign options to store </td>
    
    </tr>
	<tr>
	  <td colspan="2">&nbsp;<input type="checkbox" name="mass_field14" value="Y">&nbsp;Don't link Option to Products in these stores &nbsp;</td>
	
	<td colspan="2">&nbsp;<input type="checkbox" name="mass_field15" value="Y">&nbsp;Add to all product under selected category  &nbsp;&nbsp;&nbsp;</td>
	</tr>
</table>
		<span class="element_style">   </td>
      </tr>
		
		
		
		
		
		
		<!---  stores 19-0707 ------------------- -->
				
		    
    <tr class="naGridTitle"> 
      <td colspan=12 valign=center><div align=center> 
	      <input name="submit1" type=submit value="Submit" class="naBtn" onClick="return chkval();" >&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
  </tr></table>
{/if}  
</form><br />