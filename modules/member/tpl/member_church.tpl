<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="scripts/validator.js"></script>
{if ($EDIT_MODE==1)}
{literal}
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
	var fields=new Array('chr_name','chr_url','address1','city','state','country','postalcode','telephone','denomination','p_name','p_title','p_email','p_biography');
	var msgs=new Array('Church Name','Church URL','Address','City','State','Country','Zip Code','telephone','Denomination','Pastor Name','Pastor Title','Pastor Email','Pastor Biography');

	var emails=new Array('email','p_email');
	var email_msgs=new Array('Invalid Email','Invalid Pastor Email')

	var nums=new Array('postalcode');
	var nums_msgs=new Array('Postal Code should be a number');
	function checkLength()
	{
		
		if (chk(document.frmReg))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
</SCRIPT>
{/literal}

{else}
{literal}
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
	var fields=new Array('chr_name','username','password','confirm_pass','chr_url','email','address1','city','state','country','postalcode','telephone','denomination','p_name','p_title','p_email','p_biography');
	var msgs=new Array('Church Name','Username','Password','Confirm Password','Church URL','Church Email','Address','City','State','Country','Zip Code','telephone','Denomination','Pastor Name','Pastor Title','Pastor Email','Pastor Biography');

	var emails=new Array('email','p_email');
	var email_msgs=new Array('Invalid Email','Invalid Pastor Email')

	var nums=new Array('postalcode');
	var nums_msgs=new Array('Postal Code should be a number');
	function checkLength()
	{
		
		if (chk(document.frmReg))
		{
			
			var str1=document.frmReg.username.value;
			var str2=document.frmReg.password.value;
			var str3=document.frmReg.confirm_pass.value;
			if (str1.length<4)
			{
				alert("Username length is too short");
				return false;		
			}
			else if (str2.length<6)
			{
				alert("Password length is too short");
				return false;		
			}
			else if (str2!=str3)
			{
				alert("Passwords are not matching");
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
</SCRIPT>
{/literal}
{/if}
<form name="frmReg" id="frmReg" enctype="multipart/form-data" method="post" action="" onSubmit="return checkLength()">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="36" align="center" valign="top">&nbsp;</td>
                <td width="62%" height="36" align="left" valign="middle"><span class="whiteboldtop">Member Church Details </span></td>
                <td width="34%">&nbsp;</td>
              </tr>
              <tr>
                <td width="2%" align="center" valign="top">&nbsp;</td>
                <td colspan="2" align="center" valign="top"><table width="628" height="308" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="10" height="10"> <img src="{$GLOBAL.tpl_url}/images/whtbox_01.jpg" width="10" height="10" alt="" /></td>
                    <td height="10" background="{$GLOBAL.tpl_url}/images/whtbox_topfill.jpg"></td>
                    <td width="9" height="10"> <img src="{$GLOBAL.tpl_url}/images/whtbox_03.jpg" width="9" height="10" alt="" /></td>
                  </tr>
                  <tr>
                    <td background="{$GLOBAL.tpl_url}/images/whtbox_leftfill.jpg"></td>
                    <td align="left" valign="top" background="{$GLOBAL.tpl_url}/images/whtbox_fill.jpg"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="7%">&nbsp;</td>
                          <td width="90%"><table width="100%" border="0" align="right" cellpadding="0" cellspacing="0">
                            <tr>
                              <td height="18" colspan="4" class="smalltext"><strong>( * Mandatory Fields )</strong></td>
                            </tr>
                            <tr>
                              <td height="18" colspan="4" class="smalltext"><div align="center"><span class="smalltext" style="color:#FF0000"><strong>{if isset($MESSAGE)}{$MESSAGE}{/if}&nbsp; </strong></span></div></td>
                            </tr>
                            <tr>
                              <td height="18" colspan="2" class="smalltext">&nbsp;</td>
                              <td colspan="2" class="smalltext">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="18" colspan="2" class="smalltext">Church Name:<strong> * </strong></td>
                              <td colspan="2" class="smalltext"><input name="chr_name" type="text" class="input" id="chr_name" value="{$smarty.request.chr_name}" size="30" /></td>
                            </tr>
                            <tr>
                              <td height="18" align="left" valign="middle" class="smalltext">&nbsp;</td>
                              <td height="18" class="smalltext">&nbsp;</td>
                              <td height="18" colspan="2" class="smalltext">&nbsp;</td>
                            </tr>
							<tr>
                              <td width="157" height="18" align="left" valign="middle" class="smalltext">Username: <strong>*</strong> </td>
                              <td width="17" height="18" class="smalltext">&nbsp;</td>
                              <td height="18" colspan="2" class="smalltext"><input name="username" type="text" class="input" id="username" value="{$smarty.request.username}" size="30" {if $EDIT_MODE==1}readonly {/if} />
                              <strong>(Minimum 4 characters)</strong></td>
                            </tr>
                            <tr>
                              <td height="18" colspan="2" class="smalltext">&nbsp;</td>
                              <td colspan="2" class="smalltext">&nbsp;</td>
                            </tr>
							{if $EDIT_MODE!=1}
                            <tr>
                              <td height="18" align="left" valign="middle" class="smalltext">Password: <strong>*</strong> </td>
                              <td height="18" class="smalltext">&nbsp;</td>
                              <td colspan="2"  class="smalltext"><input name="password" type="password" class="input" id="password" size="30" />
                              <strong>(Minimum 6 characters)</strong></td>
                            </tr>
							<tr>
                              <td height="18" colspan="2" class="smalltext">&nbsp;</td>
                              <td colspan="2" class="smalltext">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="18" align="left" valign="middle" class="smalltext">Confirm Password: <strong>*</strong> </td>
                              <td height="18" class="smalltext">&nbsp;</td>
                              <td colspan="2"  class="smalltext"><input name="confirm_pass" type="password" class="input" id="confirm_pass" size="30" /></td>
                            </tr>
                            <tr>
                              <td height="18" colspan="2" class="smalltext">&nbsp;</td>
                              <td colspan="2" class="smalltext">&nbsp;</td>
                            </tr>
							{/if}

                            <tr>
                              <td width="157" height="18" align="left" valign="middle" class="smalltext">Church URL: </td>
                              <td width="17" height="18" class="smalltext">&nbsp;</td>
                              <td height="18" colspan="2" class="smalltext"><input name="chr_url" type="text" class="input" id="chr_url" value="{$smarty.request.chr_url}" size="30" /></td>
                            </tr>
                            <tr>
                              <td height="18" colspan="2" class="smalltext">&nbsp;</td>
                              <td colspan="2" class="smalltext">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="18" align="left" valign="middle" class="smalltext">Church Email: <strong>* </strong></td>
                              <td height="18" class="smalltext">&nbsp;</td>
                              <td colspan="2"  class="smalltext"><input name="email" type="text" class="input" id="email" value="{$smarty.request.email}" size="30" {if $EDIT_MODE==1}readonly {/if} /></td>
                            </tr>
                            <tr>
                              <td height="18" colspan="2" class="smalltext">&nbsp;</td>
                              <td colspan="2" class="smalltext">&nbsp;</td>
                            </tr>
							<td height="18" align="left" valign="middle" class="smalltext"><div align="left">Address: *</div></td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext"><textarea name="address1" cols="29" rows="5" class="input" id="address1">{$smarty.request.address1}</textarea></td>
                              </tr>
                              <tr>
                                <td height="18" class="smalltext"><div align="right"></div></td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext">&nbsp;</td>
                              </tr>
                              <tr>
                                <td height="18" class="smalltext">City: <strong>* </strong></td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext"><input name="city" type="text" class="input" id="city" value="{$smarty.request.city}" size="30" /></td>
                              </tr>
                              <tr>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext">&nbsp;</td>
                              </tr>
                              <tr>
                                <td height="18" class="smalltext">State: <strong>*</strong> </td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext"><input name="state" type="text" class="input" id="state" value="{$smarty.request.state}" size="30" /></td>
                              </tr>
                              <tr>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext">&nbsp;</td>
                              </tr>
                              <tr>
                                <td height="18" class="smalltext">Country: <strong>*</strong></td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext"><select name="country" class="input" id="country" style="width:195px ">
                                    <option>---Select a Country---</option>
                                    
                                    
							{html_options values=$COUNTRY_LIST.country_id output=$COUNTRY_LIST.country_name selected=$smarty.request.country}
                            
                                
                                </select></td>
                              </tr>
                              <tr>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext">&nbsp;</td>
                              </tr>
                              <tr>
                                <td height="18" class="smalltext">Zipcode: <strong>*</strong></td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext"><input name="postalcode" type="text" class="input" id="postalcode" value="{$smarty.request.postalcode}" size="30" /></td>
                              </tr>
                              <tr>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext">&nbsp;</td>
                              </tr>
                              <tr>
                                <td height="18" class="smalltext">Telephone: <strong>*</strong> </td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext"><input name="telephone" type="text" class="input" id="telephone" value="{$smarty.request.telephone}" size="30" /></td>
                              </tr>
                              <tr>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext">&nbsp;</td>
                                <td width="19" class="smalltext">&nbsp;</td>
                              </tr>
							  {if $EDIT_MODE!=1}
                            <tr>
                              <td height="18" align="left" valign="middle" class="smalltext">Picture:</td>
                              <td height="18" class="smalltext">&nbsp;</td>
                              <td colspan="2" class="smalltext"><input name="image" type="file" class="input" id="image" /></td>
                            </tr>
                            <tr>
                              <td height="18" colspan="2" class="smalltext">&nbsp;</td>
                              <td colspan="2" class="smalltext">&nbsp;</td>
                            </tr>
							{/if}
                            <tr>
                              <td height="18" colspan="2" align="left" valign="middle" class="smalltext">Denomination: <strong>*</strong></td>
                              <td colspan="2" class="smalltext"><select name="denomination" class="input">
                                <option>--Select a Denomination--</option>
                                
							{html_options values=$DNM_LIST.id output=$DNM_LIST.dnm_det selected=$smarty.request.denomination}
                                
                              </select></td>
                            </tr>
                            <tr>
                              <td height="18" colspan="2" class="smalltext">&nbsp;</td>
                              <td colspan="2" class="smalltext">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="18" align="left" valign="middle" class="smalltext"><div align="left"> Size of 


 congregation: </div></td>
                              <td height="18" class="smalltext">&nbsp;</td>
                              <td colspan="2" class="smalltext"><input name="congr_size" type="text" class="input" id="congr_size" value="{$smarty.request.congr_size}" size="30" /></td>
                            </tr>
                            <tr>
                              <td height="18" class="smalltext"><div align="right"></div></td>
                              <td height="18" class="smalltext">&nbsp;</td>
                              <td colspan="2" class="smalltext">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="18" class="smalltext"><strong>Pastor Details </strong></td>
                              <td height="18" class="smalltext">&nbsp;</td>
                              <td colspan="2" class="smalltext">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="18" class="smalltext">&nbsp;</td>
                              <td height="18" class="smalltext">&nbsp;</td>
                              <td colspan="2" class="smalltext">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="18" align="left" valign="middle" class="smalltext"> Name of Pastor: <strong>* </strong></td>
                              <td height="18" class="smalltext">&nbsp;</td>
                              <td colspan="2" class="smalltext"><input name="p_name" type="text" class="input" id="p_name" value="{$smarty.request.p_name}" size="30" /></td>
                            </tr>
                            <tr>
                              <td height="18" class="smalltext">&nbsp;</td>
                              <td height="18" class="smalltext">&nbsp;</td>
                              <td colspan="2" class="smalltext">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="18" align="left" valign="middle" class="smalltext"> Title: <strong>*</strong> </td>
                              <td height="18" class="smalltext">&nbsp;</td>
                              <td colspan="2" class="smalltext"><select name="p_title" class="input" id="p_title">
                                  <option value="Reverend" {if $smarty.request.p_title=="Reverend"} selected {/if}>Reverend </option>
                                  <option value="Reverend Dr." {if $smarty.request.p_title=="Reverend Dr."} selected {/if}>Reverend Dr.</option>
                                  <option value="Bishop" {if $smarty.request.p_title=="Bishop"} selected {/if}>Bishop </option>
                                  <option value="Bishop Dr" {if $smarty.request.p_title=="Bishop Dr"} selected {/if}>Bishop Dr</option>
                              </select></td>
                            </tr>
                            <tr>
                              <td height="18" class="smalltext">&nbsp;</td>
                              <td height="18" class="smalltext">&nbsp;</td>
                              <td colspan="2" class="smalltext">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="18" align="left" valign="middle" class="smalltext">Email: <strong>*</strong> </td>
                              <td height="18" class="smalltext">&nbsp;</td>
                              <td colspan="2" class="smalltext"><input name="p_email" type="text" class="input" id="p_email" value="{$smarty.request.p_email}" size="30" /></td>
                            </tr>

                            <tr>
                              <td height="18" class="smalltext">&nbsp;</td>
                              <td height="18" class="smalltext">&nbsp;</td>
                              <td colspan="2" class="smalltext">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="18" align="left" valign="top" class="smalltext">BioGraphy: <strong>*</strong> </td>
                              <td height="18" class="smalltext">&nbsp;</td>
                              <td colspan="2" class="smalltext">
                                  <textarea name="p_biography" rows="5" cols="30" class="input" id="p_biography">{$smarty.request.p_biography}</textarea>
</td>
                            </tr>
                            <tr>
                              <td height="18" class="smalltext">&nbsp;</td>
                              <td height="18" class="smalltext">&nbsp;</td>
                              <td colspan="2" class="smalltext">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="18" align="left" valign="middle" class="smalltext">&nbsp;</td>
                              <td height="18" class="smalltext">&nbsp;</td>
                              <td width="104" align="left" valign="middle" class="smalltext"><input type="image" src="{$GLOBAL.tpl_url}/images/submit.jpg" /></td>
                              <td width="270" class="smalltext"><a href="javascript: history.go(-1)"><img src="{$GLOBAL.tpl_url}/images/cancel.jpg" border="0" /></a></td>
                            </tr>
                            <tr>
                              <td height="18" align="left" valign="middle" class="smalltext">&nbsp;</td>
                              <td height="18" class="smalltext">&nbsp;</td>
                              <td class="smalltext">&nbsp;</td>
                              <td class="smalltext">&nbsp;</td>
                            </tr>
                          </table></td>
                          <td width="3%">&nbsp;</td>
                        </tr>
                    </table></td>
                    <td width="9" background="{$GLOBAL.tpl_url}/images/whtbox_rightfill.jpg"></td>
                  </tr>
                  <tr>
                    <td width="10" height="10"> <img src="{$GLOBAL.tpl_url}/images/whtbox_07.jpg" width="10" height="10" alt="" /></td>
                    <td height="10" background="{$GLOBAL.tpl_url}/images/whtbox_bottomfill.jpg"></td>
                    <td width="9" height="10"> <img src="{$GLOBAL.tpl_url}/images/whtbox_09.jpg" width="9" height="10" alt="" /></td>
                  </tr>
                </table></td>
  </tr>
</table>
</form>