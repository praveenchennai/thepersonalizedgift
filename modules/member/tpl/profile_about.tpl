<form name="frabout" method="post" action="">
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
            <td width="33%" height="23" align="right" valign="middle" class="blacktext"><span class="blackboldtext"><span class="smalltext"><span class="footerlink"><strong><span class="toplink"></span></strong></span></span></span></td>
          </tr>
          <tr>
            <td height="19" colspan="2" align="center" valign="top" class="blacktext"><div align="left"></div></td>
          </tr>
          <tr>
            <td height="244" colspan="2" align="center" valign="top" class="blacktext">
              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td colspan="3"><span class="smalltext"><strong class="greyboldtext style1">More About Me </strong></span></td>
                </tr>
                <tr>
                  <td height="18" colspan="3">&nbsp;</td>
                </tr>
                <tr>
                  <td width="81%"><table width="97%" height="171"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
                      <tr>
                        <td width="578" height="169" align="left" valign="top" bgcolor="#EEEEEE"><table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                              <td><table width="95%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td colspan="6">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td colspan="6"><div align="center"><span class="smalltext" style="color:#FF0000"><strong>{if isset($MESSAGE)}{$MESSAGE}{/if}</strong></span></div></td>
                                  </tr>
                                  <tr>
                                    <td width="16" align="left" valign="middle">&nbsp;</td>
                                    <td width="192" height="30" align="left" valign="middle"><span class="blackboldtext">Nicknames:</span></td>
                                    <td width="8" height="30" align="left">&nbsp;</td>
                                    <td height="30" colspan="2">
                                      <p><span class="smalltext">
                                        <input name="nickname" type="text" id="nickname" value="{$PRF_ABOUT.nickname}" size="26">
                                    </span><span class="smalltext"> </span></p></td>
                                  </tr>
                                  <tr>
                                    <td align="left" valign="middle">&nbsp;</td>
                                    <td height="30" align="left" valign="middle"><span class="blackboldtext">Nationality:</span></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td width="159" height="30"><span class="smalltext">
                                      <input name="nationality" type="text" id="nationality" value="{$PRF_ABOUT.nationality}" size="26">
                                    </span></td>
                                    <td width="76">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td align="left" valign="middle">&nbsp;</td>
                                    <td height="30" align="left" valign="middle"><p><span><span class="blackboldtext">Religion :</span></span></p></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td height="30" colspan="2"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td colspan="3"><span class="smalltext">
                                            <input name="religion" type="text" id="religion"   value="{$PRF_ABOUT.religion}" size="26">
                                          </span></td>
                                          </tr>
                                    </table></td>
                                  </tr>
                                  <tr>
                                    <td align="left">&nbsp;</td>
                                    <td height="30" align="left"><span class="style9"><span class="blackboldtext">Heroes:</span></span></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td height="30"><span class="smalltext">
                                      <input name="heroes" type="text" id="heroes" value="{$PRF_ABOUT.heroes}" size="26">
                                    </span></td>
                                    <td height="30">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td align="left">&nbsp;</td>
                                    <td height="30" align="left"><span class="style9"><span class="blackboldtext">Interests:</span></span></td>
                                    <td height="30" align="left"><span class="smalltext"> </span></td>
                                    <td height="30" colspan="2"><span class="smalltext">
                                      <input name="interests" type="text" id="interests" value="{$PRF_ABOUT.interests}" size="26">
                                    </span></td>
                                  </tr>
								   <tr>
								     <td align="left">&nbsp;</td>
                                    <td height="30" align="left"><span class="style9"><span class="blackboldtext">Expertise:</span></span></td>
                                    <td height="30" align="left"><span class="smalltext"> </span></td>
                                    <td height="30" colspan="2"><span class="smalltext">
                                      <input name="expertise" type="text" id="expertise" value="{$PRF_ABOUT.expertise}" size="26">
                                    </span></td>
                                  </tr>
								   <tr>
								     <td align="left">&nbsp;</td>
                                    <td height="30" align="left"><span class="style9"><span class="blackboldtext">Occupation:</span></span></td>
                                    <td height="30" align="left"><span class="smalltext"> </span></td>
                                    <td height="30" colspan="2"><span class="smalltext">
                                      <input name="occupation" type="text" id="occupation" value="{$PRF_ABOUT.occupation}" size="26">
                                    </span></td>
                                  </tr>
								     <tr>
								       <td align="left">&nbsp;</td>
                                    <td height="30" align="left"><span class="style9"><span class="blackboldtext">Industry:</span></span></td>
                                    <td height="30" align="left"><span class="smalltext"> </span></td>
                                    <td height="30" colspan="2"><span class="smalltext">
                                      <input name="industry" type="text" id="industry" value="{$PRF_ABOUT.industry}" size="26">
                                    </span></td>
                                  </tr>
								   <tr>
								     <td align="left">&nbsp;</td>
                                    <td height="30" align="left"><span class="style9"><span class="blackboldtext">Website:</span></span></td>
                                    <td height="30" align="left"><span class="smalltext"> </span></td>
                                    <td height="30" colspan="2"><span class="smalltext">
                                      <input name="website" type="text" id="website" value="{$PRF_ABOUT.website}" size="26">
                                    </span></td>
                                  </tr>
								  <tr>
								    <td align="left">&nbsp;</td>
                                    <td height="30" align="left"><span class="style9"><span class="blackboldtext">Books/Authors:</span></span></td>
                                    <td height="30" align="left"><span class="smalltext"> </span></td>
                                    <td height="30" colspan="2"><span class="smalltext">
                                      <input name="books" type="text" id="books" value="{$PRF_ABOUT.books}" size="26">
                                    </span></td>
                                  </tr>
								  <tr>
								    <td align="left">&nbsp;</td>
                                    <td height="30" align="left"><span class="style9"><span class="blackboldtext">Favorite Food:</span></span></td>
                                    <td height="30" align="left"><span class="smalltext"> </span></td>
                                    <td height="30" colspan="2"><span class="smalltext">
                                      <input name="food" type="text" id="food" value="{$PRF_ABOUT.food}" size="26">
                                    </span></td>
                                  </tr>
								  <tr>
								    <td align="left">&nbsp;</td>
                                    <td height="30" align="left"><span class="style9"><span class="blackboldtext">Places I've been to:</span></span></td>
                                    <td height="30" align="left"><span class="smalltext"> </span></td>
                                    <td height="30" colspan="2"><span class="smalltext">
                                      <input name="places" type="text" id="places" value="{$PRF_ABOUT.places}" size="26">
                                    </span></td>
                                  </tr>

								  <tr>
								    <td align="left">&nbsp;</td>
                                    <td height="30" align="left"><span class="style9"><span class="blackboldtext">Favorite Video Games:</span></span></td>
                                    <td height="30" align="left"><span class="smalltext"> </span></td>
                                    <td height="30" colspan="2"><span class="smalltext">
                                      <input name="video" type="text" id="video" value="{$PRF_ABOUT.video}" size="26">
                                    </span></td>
                                  </tr>
								  <tr>
								    <td align="left">&nbsp;</td>
                                    <td height="30" align="left"><span class="style9"><span class="blackboldtext">Favorite Tv Show:</span></span></td>
                                    <td height="30" align="left"><span class="smalltext"> </span></td>
                                    <td height="30" colspan="2"><span class="smalltext">
                                      <input name="tv" type="text" id="tv" value="{$PRF_ABOUT.tv}" size="26">
                                    </span></td>
                                  </tr>
								  <tr>
								    <td align="left">&nbsp;</td>
                                    <td height="30" align="left"><span class="style9"><span class="blackboldtext">Projects I've Worked on:</span></span></td>
                                    <td height="30" align="left"><span class="smalltext"> </span></td>
                                    <td height="30" colspan="2"><span class="smalltext">
                                      <input name="projects" type="text" id="projects" value="{$PRF_ABOUT.projects}" size="26">
                                    </span></td>
                                  </tr>
								  <tr>
								    <td align="left">&nbsp;</td>
                                    <td height="30" align="left"><span class="style9"><span class="blackboldtext">Places you may have seen me:</span></span></td>
                                    <td height="30" align="left"><span class="smalltext"> </span></td>
                                    <td height="30" colspan="2"><span class="smalltext">
                                      <input name="place_you" type="text" id="place_you" value="{$PRF_ABOUT.place_you}" size="26">
                                    </span></td>
                                  </tr>



                                  <tr align="center" valign="middle">
                                    <td colspan="5">&nbsp;</td>
                                  </tr>
                                  <tr align="center" valign="middle">
                                    <td colspan="5">
                                      <table width="250" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td width="111"><input name="image" type="image" src="{$GLOBAL.tpl_url}/images/save_changes.jpg" ></td>
                                          <td width="44">&nbsp;</td>
                                          <td width="95"><a href="{makeLink mod=member pg=profile}{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/cancel.jpg"  border="0"></a></td>
                                        </tr>
                                    </table></td>
                                  </tr>
                                  <tr valign="middle">
                                    <td colspan="5" align="right">&nbsp;</td>
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