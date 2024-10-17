<form name="frbooks" method="post" action="">
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
                  <td colspan="3"><span class="smalltext"><strong class="greyboldtext style1">Books</strong></span></td>
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
                                    <td width="123" height="30" align="left" valign="middle"><span class="blackboldtext">Favorite Books :</span></td>
                                    <td width="15" height="30" align="left"><span class="smalltext"> </span></td>
                                    <td height="30" colspan="2">
                                      <p><span class="smalltext">
                                        <input name="fav_books" type="text" id="fav_books" value="{$PRF_BOOKS.fav_books}" size="26">
                                    </span><span class="smalltext"> </span></p></td>
                                  </tr>
                                  <tr>
                                    <td height="30" align="left" valign="middle"><span class="blackboldtext">Favorite Authors : </span></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td width="162" height="30"><span class="smalltext">
                                      <input name="fav_authors" type="text" id="fav_authors" value="{$PRF_BOOKS.fav_authors}" size="26">
                                    </span></td>
                                    <td width="220">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td height="30" align="left" valign="middle"><span class="blackboldtext">Favorite Genres : </span></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td width="162" height="30"><span class="smalltext">
                                      <input name="fav_genres" type="text" id="fav_genres" value="{$PRF_BOOKS.fav_genres}" size="26">
                                    </span></td>
                                    <td width="220">&nbsp;</td>
                                  </tr>
								  <tr>
                                    <td height="30" align="left" valign="middle"><span class="blackboldtext">Favorite Characters : </span></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td width="162" height="30"><span class="smalltext">
                                      <input name="fav_chars" type="text" id="fav_chars" value="{$PRF_BOOKS.fav_chars}" size="26">
                                    </span></td>
                                    <td width="220">&nbsp;</td>
                                  </tr>
								  <tr>
                                    <td height="30" align="left" valign="middle"><span class="blackboldtext">Favorite Quotes: </span></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td width="162" height="30"><span class="smalltext">
                                      <input name="fav_qts" type="text" id="fav_qts" value="{$PRF_BOOKS.fav_qts}" size="26">
                                    </span></td>
                                    <td width="220">&nbsp;</td>
                                  </tr>
								  <tr>
                                    <td height="30" align="left" valign="middle"><span class="blackboldtext">Recent Reads: </span></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td width="162" height="30"><span class="smalltext">
                                      <input name="recent_read" type="text" id="recent_read" value="{$PRF_BOOKS.recent_read}" size="26">
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