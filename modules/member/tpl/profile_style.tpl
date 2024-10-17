<form name="frmstyle" method="post" action="">
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
                  <td colspan="3"><strong>Style</strong></td>
                </tr>
                <tr>
                  <td height="18" colspan="3">&nbsp;</td>
                </tr>
                <tr>
                  <td width="81%"><table width="97%"   border="0" align="center" cellpadding="0" cellspacing="0" class="border">
                      <tr>
                        <td width="578"  align="left" valign="top" bgcolor="#EEEEEE"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td colspan="5">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td colspan="5"><div align="center"><span class="smalltext" style="color:#FF0000"><strong>{if isset($MESSAGE)}{$MESSAGE}{/if}</strong></span></div></td>
                                  </tr>
                                  <tr>
                                    <td width="123" height="30" align="left" valign="middle"><span class="blackboldtext">Where I Shop: </span></td>
                                    <td width="15" height="30" align="left"><span class="smalltext"> </span></td>
                                    <td height="30" colspan="2">
                                      <p><span class="smalltext">
                                        <input name="shop" type="text" id="shop" value="{$PRF_STYLE.shop}" size="26">
                                    </span><span class="smalltext"> </span></p></td>
                                  </tr>
                                  <tr>
                                    <td height="30" align="left" valign="middle"><span class="blackboldtext">Brands I love: </span></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td width="162" height="30"><span class="smalltext">
                                      <input name="brand" type="text" id="brand" value="{$PRF_STYLE.brand}" size="26">
                                    </span></td>
                                    <td width="220">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td height="30" align="left" valign="middle"><span class="blackboldtext">I like to wear: </span></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td width="162" height="30"><span class="smalltext">
                                      <input name="wear" type="text" id="wear" value="{$PRF_STYLE.wear}" size="26">
                                    </span></td>
                                    <td width="220">&nbsp;</td>
                                  </tr>
								  <tr>
                                    <td height="30" align="left" valign="middle"><span class="blackboldtext">Can't live without:</span> </td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td width="162" height="30"><span class="smalltext">
                                      <input name="cant_live" type="text" id="cant_live" value="{$PRF_STYLE.cant_live}" size="26">
                                    </span></td>
                                    <td width="220">&nbsp;</td>
                                  </tr>
								  <tr>
                                    <td height="30" align="left" valign="middle"><span class="blackboldtext">Prized Possessions: </span></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td width="162" height="30"><span class="smalltext">
                                      <input name="prized_psn" type="text" id="prized_psn" value="{$PRF_STYLE.prized_psn}" size="26">
                                    </span></td>
                                    <td width="220">&nbsp;</td>
                                  </tr>
								  <tr>
                                    <td height="30" align="left" valign="middle"><span class="blackboldtext">Guilty Pleasure: </span></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td width="162" height="30"><span class="smalltext">
                                      <input name="glt_plsr" type="text" id="glt_plsr" value="{$PRF_STYLE.glt_plsr}" size="26">
                                    </span></td>
                                    <td width="220">&nbsp;</td>
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
                                          <td width="95"><a href="{makeLink mod=member pg=profile}{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/cancel.jpg"  border="0"></a></td>
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