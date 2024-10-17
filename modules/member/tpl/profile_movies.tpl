<form name="frmovies" method="post" action="">
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
                  <td colspan="3"><span class="smalltext"><strong class="greyboldtext style1">Movies</strong></span></td>
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
                                    <td width="120" height="40" align="left" valign="middle"><span class="blackboldtext">Favorite Movies:</span></td>
                                    <td width="10" height="40" align="left"><span class="smalltext"> </span></td>
                                    <td height="40" colspan="2">
                                      <p><span class="smalltext">
                                        <input name="fav_movies" type="text" id="fav_movies" value="{$PRF_MOVIES.fav_movies}" size="26">
                                    </span><span class="smalltext"> </span></p></td>
                                  </tr>
                                  <tr>
                                    <td height="40" align="left" valign="middle"><span class="blackboldtext">Favorite Actors: </span></td>
                                    <td height="40" align="left">&nbsp;</td>
                                    <td  height="40"><span class="smalltext">
                                      <input name="fav_actors" type="text" id="fav_actors" value="{$PRF_MOVIES.fav_actors}" size="26">
                                    </span></td>
                                    <td width="143">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td height="40" align="left" valign="middle"><span class="blackboldtext">Favorite Directors: </span></td>
                                    <td height="40" align="left">&nbsp;</td>
                                    <td  height="40"><span class="smalltext">
                                      <input name="fav_dctrs" type="text" id="fav_dctrs" value="{$PRF_MOVIES.fav_dctrs}" size="26">
                                    </span></td>
                                    <td width="143">&nbsp;</td>
                                  </tr>
								  <tr>
                                    <td height="40" align="left" valign="middle"><span class="blackboldtext">Favorite Genre: </span></td>
                                    <td height="40" align="left">&nbsp;</td>
                                    <td  height="40"><span class="smalltext">
                                      <input name="fav_genre" type="text" id="fav_genre" value="{$PRF_MOVIES.fav_genre}" size="26">
                                    </span></td>
                                    <td width="143">&nbsp;</td>
                                  </tr>
								  <tr>
                                    <td height="40" align="left" valign="middle"><span class="blackboldtext">Favorite Soundtracks:</span></td>
                                    <td height="40" align="left">&nbsp;</td>
                                    <td  height="40"><span class="smalltext">
                                      <input name="fav_sound" type="text" id="fav_sound" value="{$PRF_MOVIES.fav_sound}" size="26">
                                    </span></td>
                                    <td width="143">&nbsp;</td>
                                  </tr>
								  <tr>
                                    <td height="40" align="left" valign="middle"><span class="blackboldtext">Movie Quotes I say all the time: </span></td>
                                    <td height="40" align="left">&nbsp;</td>
                                    <td height="40" ><span class="smalltext">
                                      <input name="mov_qts" type="text" id="mov_qts" value="{$PRF_MOVIES.mov_qts}" size="26">
                                    </span></td>
                                    <td width="143">&nbsp;</td>
                                  </tr>
								   <tr>
                                    <td height="40" align="left" valign="middle"><span class="blackboldtext">Movies I can watch over and over: </span></td>
                                    <td height="40" align="left">&nbsp;</td>
                                    <td height="40"><span class="smalltext">
                                      <input name="mov_watch" type="text" id="mov_watch" value="{$PRF_MOVIES.mov_watch}" size="26">
                                    </span></td>
                                    <td width="143">&nbsp;</td>
                                  </tr>
								   <tr>
                                    <td width="130" height="40" align="left" valign="middle"><span class="blackboldtext">Actor that would play me in a movie: </span></td>
                                    <td height="40" align="left">&nbsp;</td>
                                    <td height="40"><span class="smalltext">
                                      <input name="play_actor" type="text" id="play_actor" value="{$PRF_MOVIES.play_actor}" size="26">
                                    </span></td>
                                    <td width="143">&nbsp;</td>
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