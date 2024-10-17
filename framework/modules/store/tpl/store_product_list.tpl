<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>
{literal}
	<script language="javascript">
	function newSelect(accessory_id)
		{
		var elm= eval("document.getElementById('accessory_"+accessory_id+"')");
		var e= document.getElementsByName('accessory[]');
		for(i=0;i<e.length;i++)
				{
				if(e[i].id=='accessory_'+accessory_id)
					{
					if(elm.checked==true)
						e[i].checked=true;
					else
						e[i].checked=false;
					}
				//alert(e[i].id+'->'+e[i].checked);
				}
		//alert(e[0].id);
		}
function popUp1(id) {
	//alert(url);
	{/literal}
	window.open('{makeLink mod=product pg=Popaccessory}act=edit&id='+id+'{/makeLink}', "name_of_window", "width=380,height=180,left=100,top=200,scrollbars=yes,menubar=no, resizable=no,location=no,toolbar=no");
	{literal}
}
	function selectCheck(category_id,accessory)
		{
		if(accessory.length>0)
			{
			var e= eval("document.getElementById('sel_all_"+category_id+"')");
			var accessoryIDS=accessory.split(",");
			for( var sIndex in accessoryIDS )
				{
					var accessory_id = accessoryIDS[sIndex];
					var elm= eval("document.getElementById('accessory_"+accessory_id+"')");
					if(e.checked==true)
						elm.checked=true;
					else
						elm.checked=false;
				newSelect(elm.vlaue);
				}
			}
			else
			{
			return false;
			}
		}
		
		function serverCall(grpid, from) {
		var req = newXMLHttpRequest();
		document.getElementById("div_append").style.display='inline';
		if(from=='remove' || from=='remove_all')
			document.getElementById("div_append").style.display='none';
		req.onreadystatechange = getReadyStateHandler(req, serverResponse);
		if(from=='accessory_group' || from=='clear_All' || from=='remove_all')
			{
			//document.getElementById("group_id").selectedIndex=0;
			//document.getElementById("div_append").style.display='none';
			for (var i=0;i<document.frm_product.elements.length;i++)
				{
				var e = document.frm_product.elements[i];
				//alert(e.name);
				if(e.name!='active' && e.name!='is_percentage' && e.name!='select_all' && e.name!='product_id[]' )
					{e.checked=false;}
				
				}
			}
		{/literal}
		//alert(product_id);
		//alert("{makeLink mod=product pg=ajaxList}{/makeLink}&grpid="+grpid+"&from="+from);
		req.open("GET", "{makeLink mod=product pg=ajaxList}{/makeLink}&grpid="+grpid+"&from="+from);
		{literal}
		req.send(null);
	return false;
	}
	function serverResponse(_var) {
	_var = _var.split('|');
	eval(_var[0]);
		
		}
	function ViewGroup(grpid, category_id) 
	{
	var e= eval("document.getElementById('grp_"+grpid+"')");
	if(category_id>0)
		{
		document.getElementById("div_append").style.display='inline';
		var w= eval("document.getElementById('cat_"+category_id+"')");
		//alert(w.innerHTML);
		e.innerHTML=w.innerHTML;
		}
	else
		{
		e.innerHTML='';
		}
	}
function popUp2(grp_id,prd_id) {
	//alert(url);
{/literal}
windowReference=window.open('{makeLink mod=product pg=PopnewCategory}act=category&grp_id='+grp_id+'&prd_id='+prd_id+'{/makeLink}', "name_of_window", "width=500,height=300,left=100,top=200,scrollbars=yes,menubar=no, resizable=no,location=no,toolbar=no");
if (!windowReference.opener)
windowReference.opener = self;
{literal}
}
function popUp3(grp_id,cat_id,prd_id) {
	//alert(url);
{/literal}
windowReference=window.open('{makeLink mod=product pg=PopnewCategory}act=accessory&cat_id='+cat_id+'&grp_id='+grp_id+'&prd_id='+prd_id+'{/makeLink}', "name_of_window", "width=500,height=300,left=100,top=200,scrollbars=yes,menubar=no, resizable=no,location=no,toolbar=no");
if (!windowReference.opener)
windowReference.opener = self;
{literal}
}
		</script>
{/literal}
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0"> 
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<form name="frm_product" method="post" action="" enctype="multipart/form-data" style="margin: 0px;">
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td nowrap class="naH1">{$SUBNAME}<input type="hidden" name="limit"  value="{$smarty.request.limit}"/></td> 
          <td nowrap align="right" class="titleLink">&nbsp;</td> 
        </tr>
        <tr>
          <td nowrap>{$CATEGORY_PATH}</td>
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=store pg=manage}act=form{/makeLink}&store_id={$smarty.request.store_id}&sId={$smarty.request.sId}&mId={$MID}&fId={$smarty.request.fId}">Add New {$smarty.request.sId} </a></td>
        </tr> 
      </table></td> 
  </tr>
  {if count($PRODUCT_LIST) > 0}
  <tr>
   <td valign=top class="naGrid1"><table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td><a class="linkOneActive" href="#" onclick="javascript: document.frm_product.action='{makeLink mod=store pg=manage}act=delete&store_id={$smarty.request.store_id}&fId={$smarty.request.fId}&sId={$smarty.request.sId}&category_id={$smarty.request.category_id}{/makeLink}'; document.frm_product.submit();">Delete</a></td>
  	 <td width="100%">&nbsp;</td>
	<td align="center">Category&nbsp;:</td>
	  <td>
	 	 <select name=category_id onchange="window.location.href='{makeLink mod=store pg=manage}act=list{/makeLink}&store_id={$smarty.request.store_id}&fId={$smarty.request.fId}&sId={$smarty.request.sId} &limit='+document.frm_product.limit.value+'&category_id='+this.value">
     	   <option value="">{$SELECT_DEFAULT}</option>
      	  {html_options values=$CATEGORY_PARENT.category_id output=$CATEGORY_PARENT.category_name selected=`$smarty.request.category_id`}
		 </select>
		 </td>
    <td><input type="text" name="product_search" value="{$PRODUCT_SEARCH_TAG}"></td>
	<td><input name="btn_search" type="submit" class="naBtn" value="Search"></td>
	 <td>&nbsp;</td>
    <td nowrap>Results per page:</td>
	<td>{$PRODUCT_LIMIT}</td>
  </tr>
</table></td>
    </tr> 
	{/if}
  <tr> 
    <td><table width=100% border=0 cellpadding="2" cellspacing="0"> 
		{if count($PRODUCT_LIST) > 0}
	    <tr>
	      <td width="4%" align="center" nowrap class="naGridTitle"><input type="checkbox" name="select_all" onClick="javascript:CheckCheckAll(document.frm_product,'product_id[]')"></td>
          <td width="35%" align="left" nowrap class="naGridTitle">{makeLink mod=product pg=index orderBy=name display="Product Name"}act=list&subact=list&category_id={$smarty.request.category_id}&fId={$smarty.request.fId}&sId={$smarty.request.sId} {/makeLink}</td> 
          <td width="14%" align="left" nowrap class="naGridTitle">{makeLink mod=product pg=index orderBy=brand_name display="Brand"}act=list&subact=list&category_id={$smarty.request.category_id}&fId={$smarty.request.fId}&sId={$smarty.request.sId} {/makeLink}</td>
          <td width="24%" align="left" nowrap class="naGridTitle">{makeLink mod=product pg=index orderBy=weight display="Weight"}act=list&subact=list&category_id={$smarty.request.category_id}&fId={$smarty.request.fId}&sId={$smarty.request.sId} {/makeLink}</td>
          <td width="10%" align="left" nowrap class="naGridTitle">{makeLink mod=product pg=index orderBy=active display="Active"}act=list&subact=list&category_id={$smarty.request.category_id}&fId={$smarty.request.fId}&sId={$smarty.request.sId}{/makeLink}</td>
	      <td width="13%" align="left" nowrap class="naGridTitle">&nbsp;</td>
	    </tr>
        {foreach from=$PRODUCT_LIST item=product}
        <tr class="{cycle values="naGrid1,naGrid2"}">
          <td valign="middle" align="center"><input type="checkbox" name="product_id[]" value="{$product->id}"></td> 
          <td align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=product pg=index}act=form&id={$product->id}&category_id={$smarty.request.category_id}&limit={$smarty.request.limit}&fId={$smarty.request.fId}&sId={$smarty.request.sId}{/makeLink}">{$product->name}</a></td> 
          <td align="left" valign="middle">{if $product->brand_id > 0}{$product->brand_name}{else}No Brand{/if}</td>
          <td align="left" valign="middle">{$product->weight}&nbsp;lbs</td>
          <td align="left" valign="middle">{if $product->active == Y}Yes{else}No{/if}</td>
          <td align="left" valign="middle"><a href="{makeLink mod=product pg=bulk}act=form_list&id={$product->id}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}">Bulk Price </a></td>
        </tr> 
        {/foreach}
        <tr> 
          <td colspan="6" class="msg" align="center" height="30">{$PRODUCT_NUMPAD}</td> 
        </tr>
        {else}
         <tr class="naGrid2"> 
          <td colspan="6" class="naError" align="center" height="30">No Records</td> 
        </tr>
        {/if}
      </table></td> 
  </tr>
 </table><br><table width="80%" border="0" cellpadding="5" cellspacing="0"  class=naBrDr align="center">
	 <tr class='naGrid2'>
               <td colspan="3" class="naGridTitle"><span class="group_style">&nbsp;Mass Updates</span></td>
          </tr>
	  <tr class="naGrid1">
	    <td colspan=3 valign="center"><table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="10" colspan="5" align="left" valign="middle"><div></div></td>
                </tr>
                <tr>
				{if $FIELDS.1==Y}
                  <td width="17%" height="19" align="left" valign="middle"> Brand</td>
                  <td height="19" colspan="2" align="left" valign="top"><select name="brand_id">
	  <option value="0">--- No Brand ---</option>
	{html_options values=$BRAND.brand_id output=$BRAND.brand_name}
	  </select></td>
	  {/if}
	   {if $FIELDS.4==Y}
                  <td width="19%" height="19" align="left" valign="top">Base Price</td>
                  <td width="31%" height="19"><input type="text" name="price" class="formText"  maxlength="15"></td>
				  {/if}
                </tr>
                <tr>
                  <td height="10" colspan="5">&nbsp;</td>
                </tr>
                <tr>
				 {if $FIELDS.7==Y}
                  <td height="19"> Weight</td>
                  <td height="19" colspan="2"><input type="text" name="weight" class="formText"  maxlength="150"></td>
				  {/if}
				   {if $FIELDS.5==Y}
                  <td height="19">Price Type </td>
                  <td height="19"><select name="price_type" style="width:200">
				  	<option value="0"> ---No Change--- </option>
		 			{html_options values=$PRODUCT_PRICES.id output=$PRODUCT_PRICES.name}
	             </select></td>
				 {/if}
                </tr>
                <tr>
                  <td height="19">&nbsp;</td>
                  <td height="19" colspan="2">&nbsp;</td>
                  <td height="19">&nbsp;</td>
                  <td height="19">&nbsp;</td>
                </tr>
                <tr>
				{if $FIELDS.12==Y}
                  <td height="19">Display related </td>
                  <td height="19" colspan="2"><input type=checkbox name="display_related" value="Y"></td>
				  {/if}
				   {if $FIELDS.11==Y}
                  <td height="19">Display Gross sell </td>
                  <td height="19"><input type=checkbox name="display_gross" value="Y" ></td>
				  {/if}
                </tr>
                <tr>
                  <td height="19">&nbsp;</td>
                  <td height="19" colspan="2">&nbsp;</td>
                  <td height="19">&nbsp;</td>
                  <td height="19">&nbsp;</td>
                </tr>
                <tr>
				 {if $FIELDS.19==Y}
                  <td height="19">Active</td>
                  <td height="19" colspan="2"><input type=checkbox name="active" value="Y" checked></td>
				  {/if}
				  {if $FIELDS.6==Y}
                  <td height="19">Price</td>
                  <td height="19"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr class=naGrid1>
                      <td width="15%"><input type="text" name="prices" id="prices" value="" class="formText"  style="width:50" maxlength="10"></td>
                      <td width="6%" valign="middle"><input type="checkbox" id="is_percentage" name="is_percentage" value="Y"></td>
                      <td width="50%" valign="middle" align="left">Is Percentage</td>
                      <td width="29%"><div id="calculated_price" style="float:left">&nbsp;</div></td>
					  {/if}
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="10" colspan="5">&nbsp;</td>
                </tr>


                <tr>
				{if $FIELDS.3==Y}
                  <td height="30" align="left" valign="top"> Category</td>
                  <td height="30" colspan="2"><select name="category[]" size="10" multiple >
	 {html_options values=$CATEGORY.category_id output=$CATEGORY.category_name}
	   </select><img src="{$GLOBAL.tpl_url}/images/icon_small_info.gif" width="17" height="16" align="top" {popup text="[Press Ctrl to select/unselect multiple categories]" fgcolor="#eeffaa"} /></td>
	   {/if}
	    {if $FIELDS.8==Y}
                  <td height="30" align="left" valign="top">Discription</td>
                  <td height="30"><textarea name="description" cols="40" rows="9" class="formText"></textarea></td>
				  {/if}
                </tr>
              </table></td>
          </tr>
        </table></td></tr>
	
	<tr class=naGrid1>
	 {if $FIELDS.20==Y}
    <td colspan="3" align="center" valign=top><strong>Accessories</strong></td>
    </tr>
	{foreach from=$GROUP item=gp name=gp_loop}
	<tr class=naGrid1>
    <td align="right" valign=top>{$gp.group.group_name}</td>
    <td align="center" valign=top>:</td>
    <td><select name="new_group_id" id="new_group_id" style="width:200" onChange="ViewGroup('{$gp.group.id}',this.value);">
	<option value="0">---Select Group---</option>
     {html_options values=$gp.group.category_id output=$gp.group.category_name}
	          </select>&nbsp;<a href="#"  onClick="javascript:popUp2('{$gp.group.id}','0'); return false;">(New Category)</a></td>
		  
	</tr>
	 <tr class=naGrid1>
	    <td colspan="3" align="center" valign=top>
	<div align="center" id="grp_{$gp.group.id}" style="display:inline"></div>
	</td>
    </tr>
  {/foreach}
  {/if}	
  <tr class=naGrid1>
    <td align="center" valign=top colspan="3"><div id="group_message" style="display:none"></div></td>
  </tr>
		 <tr class=naGridTitle>
		
    <td align="left" valign=top colspan="3"><table border="0" cellspacing="0" cellpadding="0" class=naGridTitle>
        <tr class=naGridTitle>
		{if $FIELDS.23==Y}
		<td align="left" valign=top>Accessory Group </td>
          <td align="left" valign=top>:</td>
          <td align="left"><select id="group_id" name="group_id"  style="width:200" onChange="JavaScript:serverCall(this.value,'accessory_group');">
              <option value="0">--- Select Accessory Group ---</option>
              
   {html_options values=$ACCESSORYMENU.group_id output=$ACCESSORYMENU.group_name}
	
          </select></td>
          <td align="left" valign=top>&nbsp;</td>
          <td align="left" valign=top><a href="#display_all" onClick="JavaScript:serverCall(document.frm_product.group_id.options[document.getElementById('group_id').selectedIndex].value,'Append');">Append</a>&nbsp;<!--|&nbsp;<a href="#display_all" onClick="JavaScript:serverCall('0','display_all');">View All</a>&nbsp;|&nbsp;<a href="#display_all" onClick="JavaScript:serverCall(document.frm_product.group_id.options[document.getElementById('group_id').selectedIndex].value,'Remove');">Remove</a>&nbsp;|&nbsp;<a href="#display_all" onClick="JavaScript:serverCall(this.value,'remove_all');">Remove All</a>&nbsp;|&nbsp;<a href="#display_all" onClick="JavaScript:serverCall('0','clear_All');">Clear All </a>&nbsp;--></td>
		  {/if}
        </tr>
    </table></td>
	
  </tr>
	  <tr>
	    <td colspan=3 valign="center">{foreach from=$CATEGORY_IS_IN_UI_ALL item=cate_is_all name=loop1}
        <div id="cat_{$cate_is_all.category_id}" style="display:none">
          <table align="center" width="100%" cellpadding="0" border="1">
            <tr>
              <td colspan=3 valign=center class="naGrid1" height="25"><table><tr><td><input type="checkbox" name="sel_all_{$cate_is_all.category_id}" id="sel_all_{$cate_is_all.category_id}" onClick="javascript: selectCheck('{$cate_is_all.category_id}','{foreach from=$cate_is_all.accessories item=accessories_item name=fooS}{$accessories_item.id}{if $smarty.foreach.fooS.index<(count($cate_is_all.accessories)-1)},{/if}{/foreach}')"></td><td><strong>{$cate_is_all.category_name}</strong>&nbsp;<a href="#"  onClick="javascript:popUp3('0','{$cate_is_all.category_id}','0'); return false;">(New Accessory)</a></td></tr></table></td>
            </tr>
            <tr>
              <td colspan=3 valign=center><table width="100%"  border="0" cellspacing="3" cellpadding="5" class=naGrid2>
                  <tr > {foreach from=$cate_is_all.accessories item=accessories_item name=foo} {if $smarty.foreach.foo.index is div by 3} </tr>
                  <tr> {/if}
                      <td width="5%" nowrap valign="middle">{html_checkboxes id="accessory_`$accessories_item.id`" name='accessory' values=$accessories_item.new_item_id selected=$AVAILABLE_ACCESSORIES.accessory_id onClick='newSelect(this.value);'}</td>
                      <td  width="27%" align="left"><a href="#"  onClick="javascript:popUp1('{$accessories_item.id}'); return false;">{$accessories_item.name}</a></td>
                      {/foreach} {if $smarty.foreach.foo.index mod 3 eq 0}
                      <td width="5%" nowrap valign="middle">&nbsp;</td>
                      <td  width="27%" align="left">&nbsp;</td>
                      <td width="5%" nowrap valign="middle">&nbsp;</td>
                      <td  width="27%" align="left">&nbsp;</td>
                      {/if} {if $smarty.foreach.foo.index mod 3 eq 1}
                      <td width="5%" nowrap valign="middle">&nbsp;</td>
                      <td  width="27%" align="left">&nbsp;</td>
                      {/if} </tr>
              </table></td>
            </tr>
          </table>
        </div>
 {/foreach}<div align="center" style="display:none" id="div_append"><table class="naGrid1" width="100%"><tr><Td><input type="checkbox" id="Append" name="Append" value="Y"></Td><td><strong>Append accessories to the existing</strong></td></tr></table></div></td>
    </tr>
	  <tr class="naGridTitle">
    <td colspan=3 valign="center"><div align=center><input name="btn_submit" type=submit class="naBtn" value="Mass Update">&nbsp;<input name="reset" type=reset class="naBtn" value="Reset"></div></td>
  </tr></table>
</form>