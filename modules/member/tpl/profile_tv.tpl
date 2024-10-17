<form name="frmtv" method="post" action="">
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
                  <td colspan="3"><strong>Television</strong></td>
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
                                    <td width="150" height="30" align="left" valign="middle"><span class="blackboldtext">Favorite Shows: </span></td>
                                    <td width="15" height="30" align="left"><span class="smalltext"> </span></td>
                                    <td height="30" colspan="2">
                                      <p><span class="smalltext">
                                        <input name="fav_show" type="text" id="fav_show" value="{$PRF_TV.fav_show}" size="26">
                                    </span><span class="smalltext"> </span></p></td>
                                  </tr>
                                  <tr>
                                    <td height="30" align="left" valign="middle"><span class="blackboldtext">Favorite Channels: </span></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td width="162" height="30"><span class="smalltext">
                                      <input name="fav_channel" type="text" id="fav_channel" value="{$PRF_TV.fav_channel}" size="26">
                                    </span></td>
                                    <td width="220">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td height="30" align="left" valign="middle"><span class="blackboldtext">Favorite Reruns: </span></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td width="162" height="30"><span class="smalltext">
                                      <input name="fav_rerun" type="text" id="fav_rerun" value="{$PRF_TV.fav_rerun}" size="26">
                                    </span></td>
                                    <td width="220">&nbsp;</td>
                                  </tr>
								  <tr>
                                    <td height="30" align="left" valign="middle"><span class="blackboldtext">Most Addictive Shows:</span> </td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td width="162" height="30"><span class="smalltext">
                                      <input name="adct_show" type="text" id="adct_show" value="{$PRF_TV.adct_show}" size="26">
                                    </span></td>
                                    <td width="220">&nbsp;</td>
                                  </tr>
				
								   <tr>
                                    <td height="30" width="180" align="left" valign="middle"><span class="blackboldtext">Favorite Late Night Talk Show: </span></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td width="162" height="30"><span class="smalltext">
                                      <input name="talk_show" type="text" id="talk_show" value="{$PRF_TV.talk_show}" size="26">
                                    </span></td>
                                    <td width="220">&nbsp;</td>
                                  </tr>
								  <tr>
                                    <td height="30" align="left" valign="middle"><span class="blackboldtext">Favorite Episode: </span></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td width="162" height="30"><span class="smalltext">
                                      <input name="fav_epsd" type="text" id="fav_epsd" value="{$PRF_TV.fav_epsd}" size="26">
                                    </span></td>
                                    <td width="220">&nbsp;</td>
                                  </tr>
								  <tr>
                                    <td height="30" align="left" valign="middle"><span class="blackboldtext">Characters you relate to: </span></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td width="162" height="30"><span class="smalltext">
                                      <input name="chr_relate" type="text" id="chr_relate" value="{$PRF_TV.chr_relate}" size="26">
                                    </span></td>
                                    <td width="220">&nbsp;</td>
                                  </tr>
								  <tr>
                                    <td height="30" align="left" valign="middle"><span class="blackboldtext">Favorite Theme Song: </span></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td width="162" height="30"><span class="smalltext">
                                      <input name="fav_thsong" type="text" id="fav_thsong" value="{$PRF_TV.fav_thsong}" size="26">
                                    </span></td>
                                    <td width="220">&nbsp;</td>
                                  </tr>
								  <tr>
                                    <td height="30" align="left" valign="middle"><span class="blackboldtext">Show I secretly watch: </span></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td width="162" height="30"><span class="smalltext">
                                      <input name="scrt_watch" type="text" id="scrt_watch" value="{$PRF_TV.scrt_watch}" size="26">
                                    </span></td>
                                    <td width="220">&nbsp;</td>
                                  </tr>
								  <tr>
                                    <td height="30" align="left" valign="middle" width="190"><span class="blackboldtext">Show I wished wasn't cancelled: </span></td>
                                    <td height="30" align="left">&nbsp;</td>
                                    <td width="162" height="30"><span class="smalltext">
                                      <input name="wish_cancel" type="text" id="wish_cancel" value="{$PRF_TV.wish_cancel}" size="26">
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