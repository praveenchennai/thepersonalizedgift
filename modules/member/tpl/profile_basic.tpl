<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="includes/datepicker/calendar.js"></script>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="scripts/validator.js"></script>
	<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
		var fields=new Array('first_name','last_name','country','email');
		var msgs=new Array('First Name','Last Name','Country','Email');
	</script>

<form name="edbasic" method="post" action="" onSubmit="return chk(this)">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="5%">&nbsp;</td>
        <td width="90%"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr valign="middle">
            <td height="39" colspan="2" align="center" class="blackboldtext">
              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="10">&nbsp;</td>
                  <td width="112">&nbsp;</td>
                  <td width="508">&nbsp;</td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td width="62%" height="23" align="left" valign="middle" class="blacktext"><span class="smalltext"></span><span class="smalltext"><span class="blackboldtext">Edit Profile </span> </span></td>
            <td width="33%" height="23" align="right" valign="middle" class="blacktext"><span class="blackboldtext"><span class="smalltext"><span class="footerlink"><strong><span class="toplink"></span></strong></span></span></span></td>
          </tr>
          <tr>
            <td height="19" colspan="2" align="center" valign="top" class="blacktext"><div align="left"></div></td>
          </tr>
          <tr>
            <td height="244" colspan="2" align="center" valign="top" class="blacktext">
              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td colspan="3"><span class="smalltext"><strong class="greyboldtext style1">Basic Information</strong></span></td>
                </tr>
                <tr>
                  <td height="18" colspan="3">&nbsp;</td>
                </tr>
                <tr>
                  <td width="81%"><table width="97%" height="171"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
                      <tr>
                        <td width="578" height="169" align="left" valign="top" bgcolor="#EEEEEE"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td colspan="5">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td colspan="5"><div align="center"><span class="smalltext" style="color:#FF0000"><strong>{if isset($MESSAGE)}{$MESSAGE}{/if}</strong></span></div></td>
                                  </tr>
                                  <tr>
                                    <td width="123" height="30" align="left" valign="middle"><span class="blackboldtext">First Name:</span></td>
                                    <td width="15" height="30" align="left"><span class="smalltext"> </span></td>
                                    <td height="30" colspan="2">
                                      <p><span class="smalltext">
                                        <input name="first_name" type="text" id="first_name" value="{$USERINFO.first_name}" size="26">
                                    </span><span class="smalltext"> </span></p></td>
                                  </tr>
                                  <tr>
                                    <td height="30" align="left" valign="middle"><span class="blackboldtext">Last Name:</span></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td width="220" height="30"><span class="smalltext">
                                      <input name="last_name" type="text" id="last_name" value="{$USERINFO.last_name}" size="26">
                                    </span></td>
                                    <td width="220">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td height="30" align="left" valign="middle"><p><span><span class="blackboldtext">Date of Birth :</span></span></p></td>
                                    <td height="30" align="left"><span class="smalltext"> </span></td>
                                    <td height="30" colspan="2"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td width="20%" colspan="3"><span class="smalltext">
                                            <input name="dob" type="text"  onFocus="popUpCalendar(this, this, 'yyyy-mm-dd', 0, 0)" value="{$USERINFO.dob}" size="26" readonly>
                                          </span></td>
                                          <td width="2%"><span class="smalltext">
                                            <input name="dobshow" type="radio" value="Y" {if ($USERINFO.dobshow=='Y')}checked {/if}>
                                          </span></td>
                                          <td width="5%"><span class="smalltext"><span class="style9">Show</span></span></td>
                                          <td width="2%"><span class="smalltext">
                                            <input name="dobshow" type="radio" value="N" {if ($USERINFO.dobshow=='N')}checked {/if}>
                                          </span></td>
                                          <td width="19%"><span class="smalltext"><span class="style9">Hide</span></span></td>
                                        </tr>
                                    </table></td>
                                  </tr>
                                  <tr>
                                    <td height="30" align="left"><span class="blackboldtext">Country:</span></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td height="30" colspan="2" width="220"><span class="smalltext">
                                      <select name="country" id="country" style="width:190px">
                                        <option value="">----Select Country---</option>
                                        
							   {html_options values=$COUNTRY_LIST.country_id output=$COUNTRY_LIST.country_name selected=$USERINFO.country}
                              
                                      </select>
                                    </span></td>
                                  </tr>
								  <tr>
                                    <td height="30" align="left"><span class="style9"><span class="blackboldtext">City:</span></span></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td height="30"><span class="smalltext">
                                      <input name="city" type="text" id="city" value="{$USERINFO.city}" size="26">
                                    </span></td>
                                    <td height="30">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td height="30" align="left"><span class="style9"><span class="blackboldtext">Email:</span></span></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td height="30"><span class="smalltext">
                                      <input name="email" type="text" id="email" value="{$USERINFO.email}" size="26">
                                    </span></td>
                                    <td height="30">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td height="30" align="left"><span class="style9"><span class="blackboldtext">Zipcode:</span></span></td>
                                    <td height="30" align="left"><span class="smalltext"> </span></td>
                                    <td height="30" colspan="2"><span class="smalltext">
                                      <input name="postalcode" type="text" id="postalcode" value="{$USERINFO.postalcode}" size="26">
                                    </span></td>
                                  </tr>
								  <tr align="center" valign="middle">
                                      <td height="30" colspan="2">&nbsp;</td>
									  <td height="30" align="left" class="blackboldtext" valign="middle"><div align="right">Instant Messengers</div></td>
									  <td height="30" colspan="2">&nbsp;</td>
                                  </tr>
								  <tr>
                                    <td height="30" colspan="4" align="left"><table width="96%"  border="0" cellspacing="0" cellpadding="3">
                                      <tr>
                                        <td width="56%" class="blackboldtext">Messenger name(eg: Yahoo, MSN etc) </td>
                                        <td width="2%">&nbsp;</td>
                                        <td width="42%" class="blackboldtext">Messenger ID</td>
                                      </tr>
                                      <tr>
                                        <td><span class="smalltext">
                                          <input name="im_name1" type="text" id="im_name1" value="{$USERINFO.im_name1}" size="26">
                                        </span></td>
                                        <td>:</td>
                                        <td><span class="smalltext">
                                          <input name="im_id1" type="text" id="im_id1" value="{$USERINFO.im_id1}" size="26">
                                        </span></td>
                                      </tr>
                                      <tr>
                                        <td><span class="smalltext">
                                          <input name="im_name2" type="text" id="im_name2" value="{$USERINFO.im_name2}" size="26">
                                        </span></td>
                                        <td>:</td>
                                        <td><span class="smalltext">
                                          <input name="im_id2" type="text" id="im_id2" value="{$USERINFO.im_id2}" size="26">
                                        </span></td>
                                      </tr>
                                    </table>                                      <span class="smalltext">
                                    </span></td>
                                  </tr>

								   <tr align="center" valign="middle">
                                      <td height="30" colspan="2">&nbsp;</td>
									  <td height="30" align="left"  colspan="2" class="blackboldtext" valign="middle"><input type="checkbox" name="dirflg" value="1" {if ($USERINFO.dirflg==1)}checked {/if}>Display this Contact info in Directory</td>
					
                                  </tr>
                                 
                                  <tr align="center" valign="middle">
                                    <td colspan="4">&nbsp;</td>
                                  </tr>
                                  <tr align="center" valign="middle">
                                    <td colspan="4">
                                      <table width="250" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td width="111"><input name="image" type="image" src="{$GLOBAL.tpl_url}/images/save_changes.jpg" ></td>
                                          <td width="44">&nbsp;</td>
                                          <td width="95"><a href="{makeLink mod=member pg=profile}{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/cancel.jpg" border="0"></a></td>
                                        </tr>
                                    </table></td>
                                  </tr>
                                  <tr valign="middle">
                                    <td colspan="4" align="right">&nbsp;</td>
                                  </tr>
                              </table></td>
                            </tr>
                        </table></td>
                      </tr>
                  </table></td>
                  <td width="3%">&nbsp;</td>
                  <td width="16%">&nbsp;</td>
                </tr>
                <tr>
                  <td height="18" colspan="3">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="3">&nbsp;</td>
                </tr>
            </table></td>
          </tr>
        </table></td>
        <td width="5%">&nbsp;</td>
      </tr>
  </table>
    
</form>
