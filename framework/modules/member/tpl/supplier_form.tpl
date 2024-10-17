<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
{literal}
<script language="javascript">
	var fields=new Array('username','password','first_name','last_name','address1','email','postalcode');
	var msgs=new Array('Username','Password','First Name','Last Name','address1','email','postalcode');
	var emails=new Array('email');
	var email_msgs=new Array('Invalid Email')
	var nums=new Array('postalcode');
	var nums_msgs=new Array('Postal Code should be a number');
</script>	
{/literal}
{if $smarty.request.id}
{literal}
<script language="javascript">

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
      <td colspan="2" align="left" nowrap class="naH1">Supplier Management</td> 
      <td align="right"><a href="{makeLink mod="member" pg="user"}act=supplier_list{/makeLink}">List Suppliers</a></td> 
    </tr> 
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center>{messageBox}</div>
      </td>
    </tr>
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">Supplier Details</span></td> 
    </tr> 
    <tr class=naGrid1> 
      <td valign=top width="40%"><div align=right class="element_style"> Username</div></td> 
      <td width="1%" valign=top>:</td> 
      <td width="59%"><input name="username" type="text" class="formText" id="username" value="{$smarty.request.username}" size="30" maxlength="25" {if $smarty.request.id} readonly{/if}></td> 
    </tr> 
	<tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Password</div></td> 
      <td valign=top>:</td> 
      <td><input name="password" type="password" class="formText" id="password" value="{$smarty.request.password}" size="30" maxlength="25" ></td> 
    </tr> 
	{if !$smarty.request.id}
		<tr class=naGrid2> 
		  <td valign=top><div align=right class="element_style">Retype Password</div></td> 
		  <td valign=top>:</td> 
		  <td><input name="rep_pass" type="password" class="formText" id="rep_pass" value="{$smarty.request.rep_pass}" size="30" maxlength="25" ></td> 
		</tr> 
	{/if}	
    <tr class=naGrid1> 
      <td valign=top><div align=right class="element_style"> First Name</div></td> 
      <td valign=top>:</td> 
      <td><input name="first_name" type="text" class="formText" id="first_name" value="{$smarty.request.first_name}" size="30" maxlength="25" ></td> 
    </tr> 
    <tr class=naGrid2> 
      <td valign=top><div align=right class="element_style"> Last Name</div></td> 
      <td valign=top>:</td> 
      <td><input name="last_name" type="text" class="formText" id="last_name" value="{$smarty.request.last_name}" size="30" maxlength="25" ></td> 
    </tr> 
	
    <tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Address 1</div></td> 
      <td valign=top>:</td> 
      <td><input name="address1" type="text" class="formText" id="address1" value="{$smarty.request.address1}" size="30" maxlength="25" ></td> 
    </tr> 
	
    <tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Address 2</div></td> 
      <td valign=top>:</td> 
      <td><input name="address2" type="text" class="formText" id="address2" size="30" maxlength="25" ></td> 
    </tr> 
	<tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Zip Code</div></td> 
      <td valign=top>:</td> 
      <td><input name="postalcode" type="text" class="formText" id="postalcode" size="30" maxlength="25" ></td> 
    </tr> 
    <tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Email</div></td> 
      <td valign=top>:</td> 
      <td><input name="email" type="text" class="formText" id="email" value="{$smarty.request.email}" size="30" maxlength="25" ></td> 
    </tr> 
	<tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Telephone</div></td> 
      <td valign=top>:</td> 
      <td><input name="telephone" type="text" class="formText" id="telephone" value="{$smarty.request.telephone}" size="30" maxlength="25" ></td> 
    </tr> 
	<tr class=naGrid2> 
      <td valign=top><div align=right class="element_style">Mobile Phone</div></td> 
      <td valign=top>:</td> 
      <td><input name="mobile" type="text" class="formText" id="mobile" value="{$smarty.request.mobile}" size="30" maxlength="25" ></td> 
    </tr> 
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input type=submit class="formbutton" value="Submit">
          <input name="Button" type=button class="formbutton" value="Cancel" onClick="javascript:history.go(-1)"> 
        </div></td> 
    </tr> 
  </form> 
</table>