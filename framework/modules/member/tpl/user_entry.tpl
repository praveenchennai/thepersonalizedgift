<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></script>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
{literal}

<script language="javascript">
	/*var fields=new Array('username','password','first_name','last_name','address1','email','postalcode');
	var msgs=new Array('Username','Password','First Name','Last Name','address1','email','postalcode');*/
	var fields=new Array('username','password','first_name','last_name','email');
	var msgs=new Array('Username','Password','First Name','Last Name','email');
	var emails=new Array('email');
	var email_msgs=new Array('Invalid Email')
</script>	
{/literal}
{if $smarty.request.id}
{literal}
<script language="javascript">
function same_as_home_address(){
	var checkbox_status = document.sup_frm.elements['same_address'].checked
	if ( !checkbox_status ){
		document.sup_frm.elements['same_address'].checked = checkbox_status
		document.sup_frm.elements['shipping_address1'].value = ''
		document.sup_frm.elements['shipping_address2'].value = ''
		document.sup_frm.elements['shipping_city'].value = ''
		document.sup_frm.elements['shipping_state'].value = ''		
		document.sup_frm.elements['shipping_postalcode'].value = ''
	}else{
		document.sup_frm.elements['same_address'].checked = checkbox_status
		document.sup_frm.elements['shipping_address1'].value = document.sup_frm.elements['address1'].value
		document.sup_frm.elements['shipping_address2'].value = document.sup_frm.elements['address2'].value
		document.sup_frm.elements['shipping_city'].value = document.sup_frm.elements['city'].value
		document.sup_frm.elements['shipping_state'].value = document.sup_frm.elements['state'].value
		document.sup_frm.elements['shipping_postalcode'].value = document.sup_frm.elements['postalcode'].value
	}
}
function checkThis()
{
	return chk(document.sup_frm);
}
</script>
{/literal}
{else}
{literal}
<script language="javascript">

function checkThis()
{
	if ( chk(document.sup_frm))
	{
		if document.sup_frm.password.value!=document.sup_frm.rep_pass.value)
		{
			alert("Passwords are not matching");
			document.sup_frm.password.focus();
			return false;
		}
		else
		{
			return true;
		}
	}
	else
	{
		return false;
	}
}
</script>
{/literal}
{/if}
<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
  <form method="POST" name="sup_frm" action="" style="margin: 0px;" onSubmit="return checkThis()"> 
    <tr> 
      <td colspan="2" align="left" nowrap class="naH1">User Management</td> 
      <td align="right"><a href="{makeLink mod=member pg=user}act=list&mem_type={$smarty.request.mem_type}&sId={$smarty.request.sId}&fId={$smarty.request.fId}{/makeLink}">View All</a></td> 
    </tr> 
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center>{messageBox}</div>
      </td>
    </tr>
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">User Details</span></td> 
    </tr> 
	{if $smarty.request.company_name != ''}
	<tr class=naGrid2> 
      <td valign=top width="40%"><div align=right class="element_style"> Company Name:</div></td> 
      <td width="1%" valign=top>&nbsp;</td> 
      <td width="59%"><input name="company_name" type="text" class="formText" id="company_name" value="{$smarty.request.company_name}" size="30" maxlength="25" ></td> 
    </tr>
	{/if}
    <tr class=naGrid1> 
      <td valign=top width="40%"><div align=right class="element_style"> Username:</div></td> 
      <td width="1%" valign=top>&nbsp;</td> <input type="hidden" name="user_id" value="{$smarty.request.id}" />
      <td width="59%"><input name="username" type="text" class="formText" id="username" value="{$smarty.request.username}" size="30" maxlength="25" {if $smarty.request.id} readonly{/if} disabled="disabled"></td> 
    </tr> 
	 <tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Password:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td><input name="password" type="password" class="formText" id="password" value="{$smarty.request.password}" size="30" maxlength="25" ></td> 
    </tr> 
    <tr class=naGrid1> 
      <td valign=top><div align=right class="element_style"> First Name:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td><input name="first_name" type="text" class="formText" id="first_name" value="{$smarty.request.first_name}" size="30" maxlength="25" ></td> 
    </tr> 
    <tr class=naGrid2> 
      <td valign=top><div align=right class="element_style"> Last Name:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td><input name="last_name" type="text" class="formText" id="last_name" value="{$smarty.request.last_name}" size="30" maxlength="25" ></td> 
    </tr> 
	 <tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Email:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td><input name="email" type="text" class="formText" id="email" value="{$smarty.request.email}" size="30" maxlength="25" {if $smarty.request.id} readonly{/if} ></td> 
    </tr> 
	{if $dobshow eq 'Y'}
	{if $NO_DOB ne 'Y'}
	<tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Date of Birth:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td><input name="dob" type="text" class="formText" id="dob" value="{$smarty.request.dob}" size="30" maxlength="25" onFocus="popUpCalendar(this, this, 'yyyy-mm-dd', 0, 0)" readonly ></td> 
    </tr> 
	{/if}
	{/if}
	{if $NO_DOB ne 'Y'}
	<tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Gender:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td><select name="gender" class="input" id="gender" style="width:195px ">
	  <option value="male" {if $smarty.request.gender=="male"} selected{/if}>Male</option>
	  	  <option value="female" {if $smarty.request.gender=="female"} selected{/if}>Female</option>
          </select>
	</td> 
    </tr> 
	{/if}
	{if $HEALTH_CARE eq 1 AND $MEM_TYPE eq 1}
	 <tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Home Address Street1:</div></td> 
	   <td valign=top>&nbsp;</td> 
	   <td ><input type="text" name="address1"  class="formText" id="address1" size="30" value="{$smarty.request.address1}"/></td>
	 </tr>
	<tr class=naGrid2> 
	  <td valign=top><div align=right class="element_style">Home Address Street2:</div></td>
	  <td valign=top>&nbsp;</td> 
	   <td ><input type="text" name="address2"  class="formText" id="address2" size="30" value="{$smarty.request.address2}"/></td>
	</tr>
	<tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">City:</div></td>
	  <td valign=top>&nbsp;</td> 
	   <td ><input name="city" type="text" class="formText" id="city" value="{$smarty.request.city}" size="30" /></td>
	</tr>
	<tr class=naGrid2> 
	  <td valign=top><div align=right class="element_style">State:</div></td>
	  <td valign=top>&nbsp;</td> 
	   <td ><select name="state" class="formText" id="state" style="width:195px ">
			<option>---Select a State---</option>
				{foreach from=$STATE_LIST item=state_list}
					{html_options values=$state_list.value output=$state_list.value selected=$smarty.request.state}
				{/foreach}
			</select></td>
	</tr>
  {if $smarty.request.country}
  <SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >show_home_state('home_state',{$smarty.request.home_country},'{$smarty.request.state}');</SCRIPT>
  {/if}
	<tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Zipcode:</div></td>
	  <td valign=top>&nbsp;</td> 
	   <td ><input name="postalcode" type="text" class="formText" id="postalcode" value="{$smarty.request.postalcode}" size="30" /></td>
	</tr>
	<tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Fax:</div></td>
	  <td valign=top>&nbsp;</td> 
	   <td ><input name="fax" type="text" class="formText" id="fax" value="{$smarty.request.fax}" size="30" /></td>
	</tr>
	<tr class=naGrid1> 
	  <td valign=top><div align=right class="element_style">Shipping Address same as above:</div></td>
	  <td valign=top>&nbsp;</td> 
	  <td><input class="formText" name="same_address" type="checkbox" value="1" onClick="same_as_home_address()" /></td>
	</tr>
	<tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Shipping Address Street1:</div></td>
	  <td valign=top>&nbsp;</td> 
	  <td><input type="text" name="shipping_address1"  class="formText" id="shipping_address1" size="30" value="{$smarty.request.shipping_address1}"/></td>
	</tr>
	<tr class=naGrid1> 
	  <td valign=top><div align=right class="element_style">Shipping Address Street2:</div></td>
	  <td valign=top>&nbsp;</td> 
	  <td><input type="text" name="shipping_address2"  class="formText" id="shipping_address2" size="30" value="{$smarty.request.shipping_address2}"/></td>
	</tr>
	<tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">City:</div></td>
	  <td valign=top>&nbsp;</td> 
	  <td><input name="shipping_city" type="text" class="formText" id="shipping_city" value="{$smarty.request.shipping_city}" size="30" /></td>
	</tr>
	<tr class=naGrid1> 
	  <td valign=top><div align=right class="element_style">State:</div></td>
	  <td valign=top>&nbsp;</td> 
	  <td><!-- <div id="div_shipping_state" class="gray2"><input name="shipping_state" type="text" class="input" id="shipping_state" value="{$smarty.request.shipping_state}" size="30" /></div> -->
			<select name="shipping_state" class="formText" id="shipping_state" style="width:195px ">
			<option>---Select a State---</option>
				{foreach from=$STATE_LIST item=state_list}
					{html_options values=$state_list.value output=$state_list.value selected=$smarty.request.shipping_state}
				{/foreach}
			</select></td>
	</tr>
	  {if $smarty.request.country}
	  <SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >show_shipping_state('shipping_state',{$smarty.request.shipping_country},'{$smarty.request.shipping_state}');</SCRIPT>
	  {/if}
	<tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Zipcode:</div></td>
	  <td valign=top>&nbsp;</td> 
	  <td><input name="shipping_postalcode" type="text" class="formText" id="shipping_postalcode" value="{$smarty.request.shipping_postalcode}" size="30" /></td>
	</tr>
	
	{else}				  
    <tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Address:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td><input name="address1" type="text" class="formText" id="address1" value="{$smarty.request.address1}" size="30" maxlength="25" ></td> 
    </tr> 
	<tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">City:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td><input name="city" type="text" class="formText" id="city" size="30" maxlength="25" value="{$smarty.request.city}" ></td> 
    </tr>
	<tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Country:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td><select name="country" class="input" id="country" style="width:195px " onChange="javascript: show_state('state',this.value,'');">
                        <option>---Select a Country---</option>
							{html_options values=$COUNTRY_LIST.country_id output=$COUNTRY_LIST.country_name selected=$smarty.request.country}
                    </select></td> 
    </tr> 
	<tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">State:</div></td> 
      <td valign=top>&nbsp;</td>
		{if $HEALTH_CARE eq 1}
	  		<td>
				<select name="state" class="input" id="state" style="width:195px ">
					<option>---Select a State---</option>
						{foreach from=$STATE_LIST item=state_list}
							{html_options values=$state_list.value output=$state_list.value selected=$smarty.request.state}
						{/foreach}
				</select>
			</td> 
		{else}
	      	<td><input name="state" type="text" class="formText" id="state" size="30" maxlength="25" value="{$smarty.request.state}" ></td> 
		{/if}
    </tr> 
	<tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Zip Code:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td><input name="postalcode" type="text" class="formText" id="postalcode" size="30" maxlength="25" value="{$smarty.request.postalcode}"  ></td> 
    </tr> 
   {/if}
	<tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Telephone:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td><input name="telephone" type="text" class="formText" id="telephone" value="{$smarty.request.telephone}" size="30" maxlength="25" ></td> 
    </tr> 
	<tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Mobile:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td><input name="mobile" type="text" class="formText" id="mobile" value="{$smarty.request.mobile}" size="30" maxlength="25" ></td> 
    </tr> 
	{if $SPECIAL_DISCOUNT eq 'Y'}
	<tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Special Discount:</div></td> 
      <td valign=top>&nbsp;</td> 
      <td><input name="sp_discount" type="text" class="formText" id="sp_discount" value="{$smarty.request.sp_discount}" size="30" maxlength="25" ></td> 
    </tr> 
	{/if}
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input type=submit class="formbutton" value="Submit">
          <input name="Button" type=button class="formbutton" value="Cancel" onClick="javascript:history.go(-1)"> 
        </div></td> 
    </tr> 
	<tr><td colspan=3 valign=center>&nbsp;</td></tr> 
  </form> 
</table>



