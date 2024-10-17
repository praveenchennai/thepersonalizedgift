<form name="prfcontact" method="post" action="">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="5%">&nbsp;</td>
        <td width="90%"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr valign="middle">
            <td height="39" colspan="2" align="center" class="blackboldtext">
              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td width="100">&nbsp;</td>
                  <td width="10"><img src="{$GLOBAL.tpl_url}/images/link_spacer.gif" width="10" height="5"></td>
                  <td width="112">&nbsp;</td>
                  <td width="10"><img src="{$GLOBAL.tpl_url}/images/link_spacer.gif" width="10" height="5"></td>
                  <td width="498">&nbsp;</td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td width="62%" height="23" align="left" valign="middle" class="blacktext"><span class="smalltext"></span><span class="smalltext"><span class="blackboldtext">Edit Profile </span> </span></td>
            <td width="33%" height="23" align="right" valign="middle" class="blacktext"><span class="blackboldtext"><span class="smalltext"><span class="footerlink"><strong><span class="toplink"><a href="#" class="middlelink">View Public Profile</a></span></strong></span></span></span></td>
          </tr>
          <tr>
            <td height="19" colspan="2" align="center" valign="top" class="blacktext"><div align="left"></div></td>
          </tr>
          <tr>
            <td height="244" colspan="2" align="center" valign="top" class="blacktext">
              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td colspan="3"><span class="smalltext"><strong class="greyboldtext style1">Contact Information </strong></span></td>
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
                                    <td width="123" height="30" align="left" valign="middle"><span class="blackboldtext">AIM:</span></td>
                                    <td width="15" height="30" align="left"><span class="smalltext"> </span></td>
                                    <td height="30" colspan="2">
                                      <p><span class="smalltext">
                                        <input name="aim" type="text" id="aim" value="{$PRF_CONTACT.aim}" size="26">
                                    </span><span class="smalltext"> </span></p></td>
                                  </tr>
                                  <tr>
                                    <td height="30" align="left" valign="middle"><span class="blackboldtext">MSN:</span></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td width="162" height="30"><span class="smalltext">
                                      <input name="msn" type="text" id="msn" value="{$PRF_CONTACT.msn}" size="26">
                                    </span></td>
                                    <td width="220">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td height="30" align="left" valign="middle"><p><span><span class="blackboldtext">Yahoo :</span></span></p></td>
                                    <td height="30" align="left"><span class="smalltext"> </span></td>
                                    <td height="30" colspan="2"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td width="20%" colspan="3"><span class="smalltext">
                                            <input name="yahoo" type="text" id="yahoo"   value="{$PRF_ABOUT.yahoo}" size="26">
                                          </span></td>
                                          <td width="2%"><span class="smalltext">
                                          </span></td>
                                          </tr>
                                    </table></td>
                                  </tr>
                                  <tr>
                                    <td height="30" align="left"><span class="style9"><span class="blackboldtext">ICQ:</span></span></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td height="30"><span class="smalltext">
                                      <input name="icq" type="text" id="icq" value="{$PRF_CONTACT.icq}" size="26">
                                    </span></td>
                                    <td height="30">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td height="30" align="left"><span class="style9"><span class="blackboldtext">Jabber:</span></span></td>
                                    <td height="30" align="left"><span class="smalltext"> </span></td>
                                    <td height="30" colspan="2"><span class="smalltext">
                                      <input name="jabber" type="text" id="jabber" value="{$PRF_CONTACT.jabber}" size="26">
                                    </span></td>
                                  </tr>
								   
                                  <tr align="center" valign="middle">
                                    <td colspan="4">&nbsp;</td>
                                  </tr>
                                  <tr align="center" valign="middle">
                                    <td colspan="4">
                                      <table width="250" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td width="111"><input name="image" type="image" src="{$GLOBAL.tpl_url}/images/save_changes.jpg"></td>
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
