<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
{literal}
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
function show_state(opt_name,country_id,state_name) 
{
	document.getElementById('div_state').innerHTML="Loading....";
	//alert(document.getElementById('country').label)
	//country_name=document.getElementById('country')[document.getElementById('country').selectedIndex].innerHTML;
	var req1 = newXMLHttpRequest();
	req1.onreadystatechange = getReadyStateHandler(req1, serverRese);
	str="opt_name="+opt_name+"&country_id="+country_id+"&state_name="+state_name;
	{/literal}
	req1.open("POST", "{makeLink mod=member pg=ajax_state}{/makeLink}&"+Math.random());
	{literal}
	req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	req1.send(str);
	}
	function serverRese(_var) {
	_var = _var.split('|');
	document.getElementById('div_state').innerHTML=_var[0];
}
</script>
{/literal}
{literal}
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >

	var fields=new Array('prop_city','prop_country','prop_region','prop_zip','prop_list_type','prop_sale_type','prop_title','prop_description','cont_phone','cont_email','cont_addr1','cont_city','cont_state','cont_zip','price');
	var msgs=new Array('City','Country','Region','Zip','Listing Type','Sale Type','Ad Title','Description','Contact phone','Contact email','Contact address','Contact city','Contact state','Contact zip','Price');
	
		var emails=new Array('cont_email');
		var email_msgs=new Array('Invalid Email')

	function checkLength()
	{
		if (chk(document.frmProp))
		{	 			
			return true;
		}
		else
		{
			return false;
		}		
	}
  function validation()
  {
		var price=document.name1.price.value();
		if(price.isNaN()) {
		return false;
		}
  }
  function validEmail(email)
  {
	if (!emailCheck(email))
	{
		return false;
	}
  }
</SCRIPT>
{/literal}
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class="naBrdr"> 
  <tr> 
    <td><table width="98%" align="center"> 
        <tr> 
          <td width="42%" nowrap class="naH1">Edit Property</td> 
          <td width="58%" align="right" nowrap class="titleLink"><table width="94%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="5%">&nbsp;</td>
              <td width="34%"><a href="{makeLink mod=album pg=album_admin}act=upload&user_id={$prop.user_id}&propid={$smarty.request.propid}{/makeLink}">Edit/Upload Videos</a></td>
              <td width="2%">&nbsp;</td>
              <td width="35%"><a href="{makeLink mod=album pg=album_admin}act=upload_photo&user_id={$prop.user_id}&propid={$smarty.request.propid}&crt=M2{/makeLink}">Edit/Upload Photos</a></td>
              <td width="1%">&nbsp;</td>
              <td width="23%"><a href="{makeLink mod=album pg=album_admin}act=propdView{/makeLink}">Property List</a></td>
            </tr>
          </table>            <!-- <a href="{makeLink mod=member pg=user}act=sub_form{/makeLink}">Add New</a> --></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td>
<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><form name="frmProp" method="post" action="" onSubmit="return checkLength()">
      <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="2" class="formbg">
        <tr align="left" valign="middle">
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr align="left" valign="middle" class="naGridTitle" >
          <td colspan="4" class="headingtext2" style="height:24;padding-left:5px"> Property information for this listing:  </td>
          </tr>
        <tr>
          <td colspan="4" bgcolor="" class="tdHeight"></td>
          </tr>
        <tr>
          <td align="right" class="normaltext">&nbsp;</td>
          <td align="right" class="normaltext">&nbsp;</td>
          <td colspan="2" align="right" class="footerlink"><span style="color:#BA450F">*</span> fields are required </td>
        </tr>
        <tr class="naGrid1">
          <td align="right">City <span class="fullColen">:</span></td>
          <td align="right">&nbsp;</td>
          <td colspan="2" ><input name="prop_city" type="text" class="input" id="prop_city" value="{$smarty.request.prop_city}"> 
            <span style="color:#BA450F">*</span>  </td>
        </tr>
        <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight"></td>
          </tr>
        <tr class="naGrid1">
          <td align="right" class="normaltext">Country <span class="fullColen">:</span></td>
          <td align="right" class="tdHeight"></td>
          <td colspan="2"><span class="bodytext">
            <select name="prop_country" class="combo" id="prop_country" style="width:180px " onChange="javascript: show_state('prop_region',this.value,'');">
              <option value="">---Select a Country---</option>
				{html_options values=$COUNTRY_LIST.country_id output=$COUNTRY_LIST.country_name selected=$smarty.request.prop_country}
            </select>
            <span style="color:#BA450F">*</span></span></td>
        </tr>
        <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight"></td>
          </tr>
        <tr class="naGrid1">
          <td width="33%" align="right" class="normaltext">Region <span class="fullColen">:</span></td>
          <td width="3%" align="right" class="normaltext">&nbsp;</td>
          <td colspan="2"><div id="div_state" class="bodytext"><input name="prop_region" type="text" class="input" id="prop_region" value="{$smarty.request.prop_region}" size="30" /> 
            </div></td>
        </tr>
		 {if $smarty.request.prop_region}
				  <SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >show_state('prop_region',{$smarty.request.prop_country},'{$smarty.request.prop_region}');</SCRIPT>
		 {/if}
        <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight"></td>
          </tr>
        <tr class="naGrid1">
          <td align="right" class="normaltext">Zip Code <span class="fullColen">:</span></td>
          <td align="right" class="normaltext">&nbsp;</td>
          <td colspan="2"><input name="prop_zip" type="text" class="input" id="prop_zip" value="{$smarty.request.prop_zip}"> <span class="bodytext"><span style="color:#BA450F">*</span></span></td>
        </tr>
		  <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight"></td>
          </tr>
        <tr class="naGrid1">
          <td align="right" class="normaltext">Listing Type <span class="fullColen">:</span> </td>
          <td align="right" class="normaltext">&nbsp;</td>
          <td colspan="2">
		  <select name="prop_list_type" class="combo" id="prop_list_type">
		  <option value="">-----Select One-----</option>
		  {html_options values=$LISTING_TYPE.category_id output=$LISTING_TYPE.category_name selected=$smarty.request.prop_list_type}
          </select>
		  <span style="color:#BA450F">*</span>	<input type="hidden" name="prop_list_type_parent" value="{$LISTING_TYPE_PARENT}"></td>
        </tr>
        <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight"></td>
          </tr>
        <tr class="naGrid1">
          <td align="right" class="normaltext">Property Type <span class="fullColen">:</span> </td>
          <td align="right" class="normaltext">&nbsp;</td>
          <td colspan="2">
		  <select name="prop_type" class="combo" id="prop_type">
		  <option value="">-----Select One-----</option>
		  {html_options values=$PROPERTY_TYPE.category_id output=$PROPERTY_TYPE.category_name selected=$smarty.request.prop_type}
          </select>
		  <span style="color:#BA450F">*</span>	<input type="hidden" name="prop_type_parent" value="{$PROPERTY_TYPE_PARENT}"></td>
        </tr>
        <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight"></td>
          </tr>
        <tr class="naGrid1">
          <td align="right" class="normaltext">Sale Type <span class="fullColen">:</span> </td>
          <td align="right" class="normaltext">&nbsp;</td>
          <td colspan="2">
		  <select name="prop_sale_type" class="combo" id="prop_sale_type">
		  <option value="">-----Select One-----</option>
		  {html_options values=$SALE_TYPE.category_id output=$SALE_TYPE.category_name selected=$smarty.request.prop_sale_type}
          </select>
		  <span style="color:#BA450F">*</span>	<input type="hidden" name="prop_sale_type_parent" value="{$SALE_TYPE_PARENT}"></td>
        </tr>
        <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight"></td>
          </tr>
        <tr class="naGrid1">
          <td align="right" class="normaltext">New Porpperty <span class="fullColen">:</span> </td>
          <td align="right" class="normaltext">&nbsp;</td>
          <td width="7%" align="left"><input name="prop_new" type="radio" value="Y" {if $smarty.request.prop_new == "Y"} checked {/if}><span class="bodytext">Yes</span></td>
          <td width="57%" align="left" class="bodytext"><input name="prop_new" type="radio" value="N" {if $smarty.request.prop_new == "N"} checked {/if}><span class="bodytext">No</span></td>
        </tr>
        <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight"></td>
          </tr>
        <tr class="naGrid1">
          <td align="right" class="normaltext">Ad Title <span class="fullColen">:</span> </td>
          <td align="right" class="normaltext">&nbsp;</td>
          <td colspan="2"><input name="prop_title" type="text" class="input" id="prop_title" value="{$smarty.request.prop_title}" size="32">
            <span style="color:#BA450F">*</span></td>
        </tr>
        <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight"></td>
          </tr>
        <tr class="naGrid1">
          <td align="right" class="normaltext">Tags <span class="fullColen">:</span></td>
          <td align="right" class="normaltext">&nbsp;</td>
          <td colspan="2"><input name="search_tags" type="text" class="input" id="search_tags" value="{$smarty.request.search_tags}" size="32"> <span class="bodytext"><b>(</b> Enter one or more tags, separated by spaces.
Tags are keywords used to describe your property.)</span></td>
        </tr>
        <tr class="naGrid1">
          <td align="right" class="normaltext">Description <span class="fullColen">:</span> </td>
          <td align="right" class="normaltext">&nbsp;</td>
          <td colspan="2"><textarea name="prop_description" id="prop_description" class="input" >{$smarty.request.prop_description}</textarea>
            <span style="color:#BA450F">*</span></td>
        </tr>
        <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight"></td>
          </tr>
        <tr align="left" class="naGridTitle"  >
          <td colspan="4" class="headingtext2" style="height:24;padding-left:5px">Contact information for this listing </td>
          </tr>
        <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight">&nbsp;</td>
          </tr>
        <tr class="naGrid1">
          <td align="right" class="normaltext">  Phone <span class="fullColen">:</span></td>
          <td align="right" class="normaltext">&nbsp;</td>
          <td colspan="2"><input name="cont_phone" type="text" class="input" id="cont_phone" value="{$smarty.request.cont_phone}"> <span style="color:#BA450F">*</span></td>
        </tr>
        <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight"></td>
          </tr>
        <tr class="naGrid1">
          <td align="right" class="normaltext">Email <span class="fullColen">:</span> </td>
          <td align="right" class="normaltext">&nbsp;</td>
          <td colspan="2"><input name="cont_email" type="text" class="input" id="cont_email" value="{$smarty.request.cont_email}">
            <span style="color:#BA450F">*</span></td>
        </tr>
        <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight"></td>
          </tr>
        <tr class="naGrid1">
          <td align="right" class="normaltext">Address1 <span class="fullColen">:</span> </td>
          <td align="right" class="normaltext">&nbsp;</td>
          <td colspan="2"><input name="cont_addr1" type="text" class="input" id="cont_addr1" value="{$smarty.request.cont_addr1}">
            <span style="color:#BA450F">*</span></td>
        </tr>
        <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight"></td>
          </tr>
        <tr class="naGrid1">
          <td align="right" class="normaltext">Address2 <span class="fullColen">:</span> </td>
          <td align="right" class="normaltext">&nbsp;</td>
          <td colspan="2"><input name="cont_addr2" type="text" class="input" id="cont_addr2" value="{$smarty.request.cont_addr2}"></td>
        </tr>
        <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight"></td>
          </tr>
        <tr class="naGrid1">
          <td align="right" class="normaltext">City <span class="fullColen">:</span> </td>
          <td align="right" class="normaltext">&nbsp;</td>
          <td colspan="2"><input name="cont_city" type="text" class="input" id="cont_city" value="{$smarty.request.cont_city}">
            <span style="color:#BA450F">*</span></td>
        </tr>
        <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight"></td>
          </tr>
        <tr class="naGrid1">
          <td align="right" class="normaltext">State <span class="fullColen">:</span> </td>
          <td align="right" class="normaltext">&nbsp;</td>
          <td colspan="2"><input name="cont_state" class="input" type="text" id="cont_state" value="{$smarty.request.cont_state}" />
            <span style="color:#BA450F">*</span></td>
        </tr>
        <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight"></td>
          </tr>
        <tr class="naGrid1">
          <td align="right" class="normaltext">Zip code <span class="fullColen">:</span> </td>
          <td align="right" class="normaltext">&nbsp;</td>
          <td colspan="2"><input name="cont_zip" type="text" class="input" id="cont_zip" value="{$smarty.request.cont_zip}">
            <span style="color:#BA450F">*</span></td>
        </tr>
        <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight"></td>
          </tr>
        <tr align="left" class="naGridTitle" >
          <td colspan="4" class="headingtext2" style="height:24;padding-left:5px">Home Descriptors</td>
          </tr>
        <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight"></td>
          </tr>
        <tr class="naGrid1">
          <td align="right" class="normaltext">Bedrooms <span class="fullColen">:</span> </td>
          <td align="right" class="normaltext">&nbsp;</td>
          <td colspan="2"><input name="prop_bedrooms" type="text" class="input" id=" 	prop_bedrooms" value="{$smarty.request.prop_bedrooms}"></td>
        </tr>
        <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight"></td>
          </tr>
        <tr class="naGrid1">
          <td align="right" class="normaltext">Bathrooms <span class="fullColen">:</span> </td>
          <td align="right" class="normaltext">&nbsp;</td>
          <td colspan="2"><input name="prop_bathrooms" type="text" class="input" id="prop_bathrooms" value="{$smarty.request.prop_bathrooms}"></td>
        </tr>
        <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight"></td>
          </tr>
        <tr class="naGrid1">
          <td align="right" class="normaltext"> Bldg Sq Ft <span class="fullColen">:</span> </td>
          <td align="right" class="normaltext">&nbsp;</td>
          <td colspan="2"><input type="text" name="bld_sqft" class="input" value="{$smarty.request.bld_sqft}"></td>
        </tr>
        <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight"></td>
          </tr>
        <tr class="naGrid1">
          <td align="right" class="normaltext"> Lot Sq Ft <span class="fullColen">:</span> </td>
          <td align="right" class="normaltext">&nbsp;</td>
          <td colspan="2"><input name="lot_sqft" type="text" class="input" id="lot_sqft" value="{$smarty.request.lot_sqft}"></td>
        </tr>
        <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight"></td>
          </tr>
        <tr class="naGrid1">
          <td align="right" class="normaltext">Price <span class="fullColen">:</span> </td>
          <td align="right" class="normaltext">&nbsp;</td>
          <td colspan="2"><input type="text" name="price" class="input" value="{$smarty.request.price}">
            <span style="color:#BA450F">*</span></td>
        </tr>
        <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight"></td>
          </tr>
        <tr class="naGrid1">
          <td align="right" class="normaltext">Year Built <span class="fullColen">:</span> </td>
          <td align="right" class="normaltext">&nbsp;</td>
          <td colspan="2"><input type="text" name="year_built" class="input" value="{$smarty.request.year_built}"></td>
        </tr>
        <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight"></td>
          </tr>
        <tr class="naGrid1">
          <td align="right" class="normaltext">Website <span class="fullColen">:</span> </td>
          <td align="right" class="normaltext">&nbsp;</td>
          <td colspan="2"><input type="text" name="website" class="input" value="{$smarty.request.website}"></td>
        </tr>
        <tr class="naGrid1">
          <td colspan="4" align="right" class="tdHeight"></td>
          </tr>
        <tr class="naGrid1">
          <td align="right" valign="top" class="normaltext">Features <span class="fullColen">:</span> </td>
          <td align="right" valign="top" class="normaltext"><input type="hidden" name="feature_parent" value="{$FEATURES_PARENT}"></td>
          <td colspan="2" class="bodytext" valign="top">
		  {foreach  item=feature from=$FEATURES.category_name key=ind}
			{html_checkboxes name="features" values=$FEATURES.category_id[$ind] output=$feature selected=$FE separator="<br/>"}
		  {/foreach}
		</td>
        </tr>
        <tr class="naGrid1">
          <td colspan="4" valign="top">&nbsp;</td>
          </tr>
        <tr class="naGrid1">
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td colspan="2" class="bodytext"><input type="submit" value="Save Property" class="naBtn"></td>
        </tr>
        <tr class="naGrid1">
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
          <td colspan="2" class="bodytext">&nbsp;</td>
        </tr>
      </table>
    </form></td>
  </tr>
</table>
</td>
</tr>
</table>
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td></td>
  </tr>
    <tr>
    <td></td>
  </tr>
</table>