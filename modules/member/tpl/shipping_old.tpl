<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/includes/ajax/ajax.js"></script>
{literal}
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
function pagesub()
	{
	if(chk(document.frmReg))
		{
		//document.frmReg.state.value=document.
		if(document.getElementById('new_state').type=='text')
			{
			//alert(document.getElementById('new_state').value);
			state=document.getElementById('new_state').value;
			}
		else 
			{
			//alert(document.getElementById('new_state').options[document.getElementById('new_state').selectedIndex].value);
			state=document.getElementById('new_state').options[document.getElementById('new_state').selectedIndex].value;
			}
		document.frmReg.state.value=state;
		{/literal}
		document.frmReg.action='{makeLink mod=member pg=register}act=shipping_det{/makeLink}';
		{literal}
		document.frmReg.submit();
		}
	}
function show_state(opt_name,country_id,state_name) {
	document.getElementById('div_state').innerHTML="Loading....";
	//alert(document.getElementById('country').label)
	//country_name=document.getElementById('country')[document.getElementById('country').selectedIndex].innerHTML;
	var req1 = newXMLHttpRequest();
	req1.onreadystatechange = getReadyStateHandler(req1, serverRese);
	str="opt_name="+opt_name+"&country_id="+country_id+"&state_name="+state_name;
	{/literal}
	req1.open("POST", "{makeLink mod=member pg=ajax_state}{/makeLink}");
	{literal}
	req1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	req1.send(str);
	}
	function serverRese(_var) {
	document.getElementById('div_state').innerHTML=_var;
	}

</SCRIPT>
{/literal}
{literal}
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
	var fields=new Array('fname', 'lname', 'address1', 'postalcode', 'country' );
	var msgs=new Array('Shipping First Name','Shipping Last Name','Shipping Address','Shipping Zip/Postal Code','Shipping Country');
	
	
	var nums=new Array('postalcode');
	var nums_msgs=new Array('Postal Code should be a number');
	
</SCRIPT>
{/literal}
<table width="100%"  border="0" cellspacing="0" cellpadding="0">

			<form name="frmReg" id="frmReg" method="post" action="" onSubmit="return chk(this);"> 
<tr>
				<td height="45" valign="middle" class="greyboldext" colspan="3">Shipping Details</td>
			  </tr>
			   <tr>
    <td height="2" valign="top" colspan=3><hr size="1"  class="border1"/></td>
  </tr>
              <tr>              <tr>
                <td width="2%" align="center" valign="top">&nbsp;</td>
                <td colspan="2" align="center" valign="top"><table width="90%" height="432" border="0" align="right" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="18" colspan="4" class="bodytext">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="18" colspan="4" class="bodytext"><strong>( * Mandatory Fields )</strong></td>
                  </tr>
                  {if isset($MESSAGE)}
                  <tr>
                    <td height="18" colspan="4" class="bodytext"><div align="center"><span class="bodytext" style="color:#FF0000"><strong>
                        {$MESSAGE}</strong></span></div></td>
                  </tr>
                  {/if}
                  <tr>
                    <td height="18" colspan="4" class="bodytext">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="18" class="bodytext">First Name: *</td>
                    <td width="376" colspan="2" class="bodytext"><input name="fname" type="text" class="input" id="fname" value="{$BILLING_ADDRESS.fname}" size="30"/>
                    </td>
                  </tr>
                  <tr>
                    <td height="18" class="bodytext">&nbsp;</td>
                    <td colspan="2" class="bodytext">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="18" class="bodytext">Last Name: *</td>
                    <td colspan="2" class="bodytext"><input name="lname" type="text" class="input" id="lname" value="{$BILLING_ADDRESS.lname}" size="30"/></td>
                  </tr>
                  <tr>
                    <td height="18" class="bodytext">&nbsp;</td>
                    <td colspan="2" class="bodytext">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="18" align="left" valign="middle" class="bodytext">Street Address: *</td>
                    <td colspan="2" class="bodytext"><input name="address1" type="text" class="input" id="address1" value="{$BILLING_ADDRESS.address1}" size="30" /></td>
                  </tr>
                  <tr>
                    <td height="18" class="bodytext"><div align="right"></div></td>
                    <td colspan="2" class="bodytext">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="18" align="left" valign="middle" class="bodytext"></td>
                    <td colspan="2" class="bodytext"><input name="address2" type="text" class="input" id="address2" value="{$BILLING_ADDRESS.address2}" size="30" /></td>
                  </tr>
                  <tr>
                    <td height="18" class="bodytext"><div align="right"></div></td>
                    <td colspan="2" class="bodytext">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="18" class="bodytext">City:</td>
                    <td colspan="2" class="bodytext"><input name="city" type="text" class="input" id="city" value="{$BILLING_ADDRESS.city}" size="30" /></td>
                  </tr>
                  <tr>
                    <td height="18" class="bodytext">&nbsp;</td>
                    <td colspan="2" class="bodytext">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="18" class="bodytext">Zipcode: *</td>
                    <td colspan="2" class="bodytext"><input name="postalcode" type="text" class="input" id="postalcode" value="{$BILLING_ADDRESS.postalcode}" size="30" /></td>
                  </tr>
                  <tr>
                    <td height="18" class="bodytext">&nbsp;</td>
                    <td colspan="2" class="bodytext">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="18" class="bodytext">Country: *</td>
                    <td colspan="2" class="bodytext"><select name="country" class="input" id="country" style="width:195px " onChange="javascript: show_state('new_state',this.value,'');">
                        <option value="">---Select a Country---</option>
                        {html_options values=$COUNTRY_LIST.country_id output=$COUNTRY_LIST.country_name selected=$BILLING_ADDRESS.country}
                    </select></td>
                  </tr>
                  <tr>
                    <td height="18" class="bodytext">&nbsp;</td>
                    <td colspan="2" class="bodytext">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="18" class="bodytext">State/Province:</td>
                    <td colspan="2" class="bodytext"><div id="div_state" class="bodytext" style="display:inline"><input name="state" type="text" class="input" id="state" value="{$BILLING_ADDRESS.state}" size="30" /></div></td>
                  </tr>
                  <tr>
                    <td height="18" class="bodytext">&nbsp;</td>
                    <td colspan="2" class="bodytext">&nbsp;</td>
                  </tr>
				   <SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >show_state('new_state',{$BILLING_ADDRESS.country},'{$BILLING_ADDRESS.state}');</SCRIPT>
                   <tr>
                    <td height="18" class="bodytext">Telephone:</td>
                    <td colspan="2" class="bodytext"><input name="telephone" type="text" class="input" id="telephone" value="{$BILLING_ADDRESS.telephone}" size="30" /></td>
                  </tr>
                  <tr>
                    <td height="18" class="bodytext">&nbsp;</td>
                    <td colspan="2" class="bodytext">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="18" class="bodytext">Mobile:</td>
                    <td colspan="2" class="bodytext"><input name="mobile" type="text" class="input" id="mobile" value="{$BILLING_ADDRESS.mobile}" size="30" /></td>
                  </tr>
                  <tr>
                    <td height="18" class="bodytext">&nbsp;</td>
                    <td colspan="2" class="bodytext">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="18" class="bodytext">&nbsp;</td>
                    <td colspan="2" class="bodytext"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="18%"><input type="submit" class="button_class" style="height:22;width:80" onClick="javascript:pagesub()" value="Submit" /></td>
                        <td width="7%">&nbsp;</td>
                        <td width="75%"><input name="button" type="button" class="button_class" style="height:22;width:80" onClick="javascript: history.go(-1)" value="Cancel" /></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
		<input type="hidden" name="state" /></form>
</table>
