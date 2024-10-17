<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="scripts/validator.js"></script>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
	var fields=new Array('address1','city','state','country','postalcode','telephone');
	var msgs=new Array('Address','City','State','Country','Postal Code','Telephone');


	var nums=new Array('postalcode');
	var nums_msgs=new Array('Postal Code should be a number');
</SCRIPT>

<form name="frmReg" id="frmReg" method="post" action="" onSubmit="return chk(this)"> 
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="36" align="center" valign="top">&nbsp;</td>
                <td width="62%" height="36" align="left" valign="middle"><span class="whiteboldtop">Member Church Location</span></td>
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
                                <td height="18" colspan="5" class="smalltext"><strong>( * Mandatory Fields )</strong></td>
                              </tr>
                              <tr>
                                <td height="18" colspan="5" class="smalltext"><div align="center"><span class="smalltext" style="color:#FF0000"><strong>{if isset($MESSAGE)}{$MESSAGE}{/if}&nbsp; </strong></span></div></td>
                              </tr>
                              <tr>
                                <td height="18" colspan="5" class="smalltext">&nbsp;</td>
                              </tr>
                              <tr>
                                <td width="185" height="18" align="left" valign="middle" class="smalltext"><div align="left">Address: *</div></td>
                                <td width="16" height="18" class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext"><textarea name="address1" cols="29" rows="5" class="input" id="address1">{$smarty.request.address1}</textarea></td>
                              </tr>
                              <tr>
                                <td height="18" class="smalltext"><div align="right"></div></td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext">&nbsp;</td>
                              </tr>
                              <tr>
                                <td height="18" class="smalltext">City: *</td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext"><input name="city" type="text" class="input" id="city" value="{$smarty.request.city}" size="30" /></td>
                              </tr>
                              <tr>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext">&nbsp;</td>
                              </tr>
                              <tr>
                                <td height="18" class="smalltext">State: *</td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext"><input name="state" type="text" class="input" id="state" value="{$smarty.request.state}" size="30" /></td>
                              </tr>
                              <tr>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext">&nbsp;</td>
                              </tr>
                              <tr>
                                <td height="18" class="smalltext">Country: *</td>
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
                                <td height="18" class="smalltext">Zipcode: *</td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext"><input name="postalcode" type="text" class="input" id="postalcode" value="{$smarty.request.postalcode}" size="30" /></td>
                              </tr>
                              <tr>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext">&nbsp;</td>
                              </tr>
                              <tr>
                                <td height="18" class="smalltext">Telephone: *</td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext"><input name="telephone" type="text" class="input" id="telephone" value="{$smarty.request.telephone}" size="30" /></td>
                              </tr>
                              <tr>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext">&nbsp;</td>
                                <td width="54" class="smalltext">&nbsp;</td>
                              </tr>
                              <tr>
                                <td height="18" class="smalltext">Mobile:</td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext"><input name="mobile" type="text" class="input" id="mobile" value="{$smarty.request.mobile}" size="30" /></td>
                              </tr>
                              <tr>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext">&nbsp;</td>
                                <td width="54" class="smalltext">&nbsp;</td>
                              </tr>
                              
                              <tr>
                                <td height="18" align="left" valign="middle" class="smalltext">&nbsp;</td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td width="169" class="smalltext">&nbsp;</td>
                                <td width="124" class="smalltext">&nbsp;</td>
                                <td class="smalltext">&nbsp;</td>
                              </tr>
                              <tr>
                                <td height="18" align="left" valign="middle" class="smalltext">&nbsp;</td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td colspan="2" class="smalltext"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td width="18%"><input type="image" src="{$GLOBAL.tpl_url}/images/submit.jpg" /></td>
                                    <td width="7%">&nbsp;</td>
                                    <td width="75%"><a href="javascript: history.go(-1)"><img src="{$GLOBAL.tpl_url}/images/cancel.jpg" border="0" /></a></td>
                                  </tr>
                                </table></td>
                                <td class="smalltext">&nbsp;</td>
                              </tr>
                              <tr>
                                <td height="18" align="left" valign="middle" class="smalltext">&nbsp;</td>
                                <td height="18" class="smalltext">&nbsp;</td>
                                <td class="smalltext">&nbsp;</td>
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