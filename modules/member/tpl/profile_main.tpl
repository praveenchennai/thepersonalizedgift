<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="5%">&nbsp;</td>
          <td width="90%"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr valign="middle">
              <td height="39" align="center" class="blackboldtext">
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
              <td width="33%" height="30" align="right" valign="middle" class="blacktext"><span class="blackboldtext"><span class="smalltext"><span class="footerlink"><strong><span class="toplink"><a href="{makeLink mod=member pg=home}{/makeLink}" class="middlelink">Home</a></span></strong></span></span></span></td>
            </tr>
            <tr>
              <td height="244" align="center" valign="top" class="blacktext">
                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="60%" height="100%"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="126" align="left" valign="top" class="smalltext"><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="smalltext">
                              <tr>
                                <td height="30" valign="top"  class="middlelink"><strong >{$USERINFO.username}</strong></td>
                              </tr>
                              <tr>
                                <td><img src="{if $USERINFO.image=='Y'}{$smarty.const.SITE_URL}/modules/member/images/userpics/thumb/{$USERINFO.id}.jpg {else} {$GLOBAL.tpl_url}/images/nophoto.jpg {/if}" border="0" class="border"></td>
                              </tr>
                            </table></td>
                            <td valign="top"><table width="100%"  border="0" cellspacing="3" cellpadding="0">
                                <tr>
                                  <td width="38%" height="30"  class="smalltext"><a href="{makeLink mod=album pg=album}act=media&user_id={$USERINFO.id}{/makeLink}" class="smalltext"><strong><u>Music</u>&nbsp;&nbsp;&nbsp;&nbsp;({$MS_COUNT})</strong></a></td>
                                  <td width="31%"   class="smalltext" align="left"><a href="{makeLink mod=member pg=group}act=other&user_id={$USERINFO.id}{/makeLink}" class="smalltext"><strong><u>Groups</u>&nbsp;&nbsp;&nbsp;&nbsp;({$GR_COUNT})</strong></a></td>
                                  <td width="100%"  rowspan="3" align="right" valign="top" class="smalltext">{if ($OWN!='Y')}
                                      <table width="99%" align="right"  border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td height="24"><span class="blackboldtext"><span class="footerlink"><strong><span class="toplink"><a href="{makeLink mod=member pg=search}act=message&uname={$USERINFO.username}&ret_url={$smarty.server.QUERY_STRING|escape:'url'}{/makeLink}" class="middlelink">Send Message</a></span></strong></span></span></td>
                                        </tr>
                                        <tr>
                                          <td height="24"><span class="blackboldtext"><span class="footerlink"><strong><span class="toplink"><a href="{makeLink mod=member pg=search}act=contact&uname={$USERINFO.username}&ret_url={$smarty.server.QUERY_STRING|escape:'url'}{/makeLink}" class="middlelink">Add to Contacts</a></span></strong></span></span></td>
                                        </tr>
                                    </table>
                                  {/if}&nbsp;</td>
                                  <td width="1%"  rowspan="3" align="right" class="smalltext">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td height="30" class="smalltext"><a href="{makeLink mod=album pg=album}act=media&user_id={$USERINFO.id}&crt=M1{/makeLink}" class="smalltext"><strong><u>Movies</u>&nbsp; ({$MV_COUNT})</strong></a></td>
                                  <td class="smalltext"><a href="{makeLink mod=member pg=search}act=mem_contact&user_id={$USERINFO.id}{/makeLink}" class="smalltext"><strong><u>Friends</u>&nbsp;&nbsp;&nbsp;({$FR_COUNT})</strong></a></td>
                                </tr>
                                <tr>
                                  <td height="30" class="smalltext"><a href="{makeLink mod=album pg=album}act=media&user_id={$USERINFO.id}&crt=M2{/makeLink}" class="smalltext"><strong><u>Photos</u>&nbsp; ({$PH_COUNT})</strong></a></td>
                                  <td class="smalltext">&nbsp;</td>
                                </tr>
                            </table></td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                    </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="270" height="30" valign="middle"><span class="smalltext"><strong class="greyboldtext style1">E.T.E Rap Sheet</strong></span></td>
                        <td width="30" valign="top">&nbsp;</td>
                        <td valign="middle"><span class="smalltext"><strong class="greyboldtext style1">&nbsp;Basic Information</strong></span></td>
                      </tr>
                      <tr>
                        <td height="100%" valign="top"><table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0" class="border">
                          <tr>
                            <td align="center" valign="top" bgcolor="#EEEEEE"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="4">
                              <tr>
                                <td width="110" height="30" align="left" valign="middle"><span class="blackboldtext">Education :</span></td>
                                <td height="30" align="left"><span class="smalltext"> </span>                                 </td>
                                </tr>
  {if ($OWN=='Y') || ($USERINFO.dobshow=='Y')}
  <tr>
    <td height="30" align="left" valign="middle"><p><span><span class="blackboldtext">Talent :</span></span></p></td>
    <td height="30" align="left"><span class="smalltext"> </span></td>
    </tr>
  {/if}  <tr>
    <td height="30" align="left"><span class="blackboldtext">Expirience :</span></td>
    <td height="30" align="left">&nbsp;</td>
    </tr>
  <tr>
    <td height="30" align="left"><span class="style9"><span class="blackboldtext">Rate Experience: 0-5 (0 none, 5 Professional) :</span></span></td>
    <td height="30" align="left">&nbsp;</td>
    </tr>
  <tr valign="middle">
    <td height="30" colspan="2" align="center" class="blackboldtext" valign="middle">&nbsp;</td>
  </tr>
  {if ($OWN=='Y')}
  <tr align="center" valign="middle">
    <td colspan="2"><a href="{makeLink mod=member pg=profile}act=basic{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/edit.jpg" width="77" height="22" border="0"></a></td>
  </tr>
  {/if}
                            </table></td>
                          </tr>
                        </table></td>
                        <td valign="top">&nbsp;</td>
                        <td align="left" valign="top"><table width="100%" height="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
                          <tr>
                            <td height="169" align="center" valign="top" bgcolor="#EEEEEE"><table width="90%" height="100%" border="0" align="center" cellpadding="0" cellspacing="4">
                              <tr>
                                <td height="30" align="left" valign="middle"><span class="blackboldtext">First Name:</span></td>
                                <td height="30" align="left"><span class="smalltext"> </span>
                                    <p><span class="smalltext">{$USERINFO.first_name}</span></p></td>
                              </tr>
                              <tr>
                                <td height="30" align="left" valign="middle"><span class="blackboldtext">Last Name:</span></td>
                                <td height="30" align="left"><span class="smalltext">{$USERINFO.last_name}&nbsp;</span></td>
                              </tr>
  {if ($OWN=='Y') || ($USERINFO.dobshow=='Y')}
  <tr>
    <td height="30" align="left" valign="middle"><p><span><span class="blackboldtext">Date of Birth :</span></span></p></td>
    <td height="30" align="left"><span class="smalltext"> </span><span class="smalltext">{$USERINFO.dob} (yyyy-mm-dd) </span></td>
  </tr>
  {/if}
  <tr>
    <td height="30" align="left"><span class="blackboldtext">Country:</span></td>
    <td height="30" align="left"><span class="smalltext">{$USERCTR.country_name} </span></td>
  </tr>
  <tr>
    <td height="30" align="left"><span class="blackboldtext">City:</span></td>
    <td height="30" align="left"><span class="smalltext">{$USERINFO.city} </span></td>
  </tr>
  <tr>
    <td height="30" align="left"><span class="style9"><span class="blackboldtext">Email:</span></span></td>
    <td height="30" align="left"><span class="smalltext">{$USERINFO.email} </span></td>
  </tr>
  <tr>
    <td height="30" align="left"><span class="style9"><span class="blackboldtext">Zip code:</span></span></td>
    <td height="30" align="left"><span class="smalltext">{$USERINFO.postalcode} </span></td>
  </tr>
  <tr valign="middle">
    <td height="30" colspan="2" align="center" class="blackboldtext" valign="middle">Instant Messengers</td>
  </tr>
  <tr>
    <td height="30" align="left"><span class="style9"><span class="blackboldtext">{if ($USERINFO.im_name1)}{$USERINFO.im_name1}:{/if}</span></span></td>
    <td height="30" align="left"><span class="smalltext">{$USERINFO.im_id1} </span></td>
  </tr>
  <tr>
    <td height="30" align="left"><span class="style9"><span class="blackboldtext">{if ($USERINFO.im_name2)}{$USERINFO.im_name2}:{/if}</span></span></td>
    <td height="30" align="left"><span class="smalltext">{$USERINFO.im_id2} </span></td>
  </tr>
  <tr valign="middle">
    <td colspan="2" align="right">&nbsp;</td>
  </tr>
  {if ($OWN=='Y')}
  <tr align="center" valign="middle">
    <td colspan="2"><a href="{makeLink mod=member pg=profile}act=basic{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/edit.jpg" width="77" height="22" border="0"></a></td>
  </tr>
  {/if}
                            </table></td>
                          </tr>
                        </table></td>
                      </tr>
                    </table></td>
                    <td width="2%">&nbsp;</td>
                    <td width="25%" height="100%" rowspan="17" valign="top"><table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#EEEEEE" class="border">
                      <tr>
                        <td height="30" align="center"><span class="smalltext"><strong class="greyboldtext style1">T.I.P. News Release </strong></span></td>
                      </tr>
                      <tr>
                        <td><table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
                          <tr>
                            <td height="33"><span class="blackboldtext">Comment :</span></td>
                          </tr>
                          <tr>
                            <td class="smalltext">This will allow people to post... <br>
                            This will allow people to post... <br>
                            This will allow people to post... </td>
                          </tr>
                          <tr>
                            <td align="right" class="smalltext">Submit Button</td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td height="40" align="center" class="smalltext">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center" valign="top" class="smalltext"><table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
                          <tr>
                            <td height="20">&nbsp;</td>
                          </tr>
                          <tr>
                            <td class="smalltext">&bull; This will allow users to post a message for all users to see.</td>
                          </tr>
                          <tr>
                            <td align="right" class="smalltext">&nbsp;</td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td height="40" align="center" class="smalltext">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center" class="smalltext"><table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
                          <tr>
                            <td height="20">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="30" class="greyboldtext style1">My Studio Wall</td>
                          </tr>
                          <tr>
                            <td align="left" class="smalltext">Write a message on this users wall that can only be seen inside there profile.</td>
                          </tr>
                          <tr>
                            <td align="left" class="smalltext">&nbsp;</td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td height="100%" align="center" class="smalltext">&nbsp;</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="18">&nbsp;</td>
                    <td height="18">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="18"><span class="smalltext"><strong class="greyboldtext style1">More about me </strong></span></td>
                    <td height="18">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="18">&nbsp;</td>
                    <td height="18">&nbsp;</td>
                  </tr>
                  <tr>
                    <td><table width="100%" height="52"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
                        <tr>
						<tr>
                              <td colspan="4" bgcolor="#EEEEEE">&nbsp;</td>
                          </tr>
						
                          <td width="500"  align="left" valign="top" bgcolor="#EEEEEE"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                            {if isset($PRF_ABOUT)}
							<tr>
                              <td width="144" height="30" align="left" valign="middle"><span class="blackboldtext">Nciknames:</span></td>
                              <td height="30" align="left"><span class="smalltext"> </span></td>
                              <td width="250" height="30">
                                <p><span class="smalltext">{$PRF_ABOUT.nickname}</span></p></td>
                            </tr>
                            <tr>
                              <td height="30" align="left" valign="middle"><span class="blackboldtext">Nationality:</span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30" ><span class="smalltext">{$PRF_ABOUT.nationality}</span></td>
                            </tr>
                            <tr>
                              <td height="30" align="left" valign="middle"><p><span><span class="blackboldtext">Religion :</span></span></p></td>
                              <td height="30" align="left"><span class="smalltext"> </span></td>
                              <td height="30"><span class="smalltext">{$PRF_ABOUT.religion}</span></td>
                            </tr>
                            <tr>
                              <td height="30" align="left"><span class="blackboldtext">Heroes:</span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="smalltext">{$PRF_ABOUT.heroes} </span></td>
                            </tr>
                            <tr>
                              <td height="30" align="left"><span class="style9"><span class="blackboldtext">Interests:</span></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="smalltext">{$PRF_ABOUT.interests} </span></td>
                            </tr>
                            <tr>
                              <td height="30" align="left"><span class="style9"><span class="blackboldtext">Expertise:</span></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="smalltext">{$PRF_ABOUT.expertise} </span></td>
                            </tr>
							<tr>
                              <td height="30" align="left"><span class="style9"><span class="blackboldtext">Occupation:</span></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="smalltext">{$PRF_ABOUT.occupation}</span></td>
                            </tr>
							<tr>
                              <td height="30" align="left"><span class="style9"><span class="blackboldtext">Industry:</span></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="smalltext">{$PRF_ABOUT.industry}</span></td>
                            </tr>
							<tr>
                              <td height="30" align="left"><span class="style9"><span class="blackboldtext">Website:</span></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="smalltext">{$PRF_ABOUT.website}</span></td>
                            </tr>
							<tr>
                              <td height="30" align="left"><span class="style9"><span class="blackboldtext">Books/Authors:</span></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="smalltext">{$PRF_ABOUT.books}</span></td>
                            </tr>
							<tr>
                              <td height="30" align="left"><span class="style9"><span class="blackboldtext">Favorite Food:</span></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="smalltext">{$PRF_ABOUT.food}</span></td>
                            </tr>
							<tr>
                              <td height="30" align="left"><span class="style9"><span class="blackboldtext">Places I've been to:</span></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="smalltext">{$PRF_ABOUT.places}</span></td>
                            </tr>
							<tr>
                              <td height="30" align="left"><span class="style9"><span class="blackboldtext">Favorite Video Games:</span></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="smalltext">{$PRF_ABOUT.video}</span></td>
                            </tr>
							<tr>
                              <td height="30" align="left"><span class="style9"><span class="blackboldtext">Favorite Tv Show:</span></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="smalltext">{$PRF_ABOUT.tv}</span></td>
                            </tr>
							<tr>
                              <td height="30" align="left"><span class="style9"><span class="blackboldtext">Projects I've Worked on:</span></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="smalltext">{$PRF_ABOUT.projects}</span></td>
                            </tr>
							<tr>
                              <td height="30" align="left"><span class="style9"><span class="blackboldtext">Places you may have seen me:</span></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="smalltext">{$PRF_ABOUT.place_you}</span></td>
                            </tr>

							

                            <tr valign="middle">
                              <td colspan="3" align="right">&nbsp;</td>
                            </tr>
								{if ($OWN=='Y')}
								<tr align="center" valign="middle">
								  <td colspan="3"><a href="{makeLink mod=member pg=profile}act=about{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/edit.jpg" width="77" height="22" border="0"></a></td>
								</tr>
								{/if}
								
							{else}
								{if ($OWN=='Y')}
								<tr align="center" valign="middle">
								  <td colspan="3"><a href="{makeLink mod=member pg=profile}act=about{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/add1.jpg" width="77" height="22" border="0"></a></td>
								</tr>
								{/if}
								
							{/if}
							<tr valign="middle">
                              <td colspan="3" align="right">&nbsp;</td>
                            </tr>
                          </table></td>
                        </tr>
                    </table></td>
                    <td>&nbsp;</td>
                  </tr>
                  
                  
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="18"><span class="smalltext"><strong class="greyboldtext style1">Education</strong></span></td>
                    <td height="18">&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td><table width="100%" height="52"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
                        <tr>
							<tr valign="middle">
                              <td colspan="3" align="right" bgcolor="#EEEEEE">&nbsp;</td>
                            </tr>
                          <td width="500" height="50" align="left" valign="top" bgcolor="#EEEEEE"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                            {if isset($PRF_SCHOOL)}
							
                            <tr>
                              <td width="144" height="30" align="left" valign="middle"><span class="blackboldtext">{if ($PRF_SCHOOL.edu1)}{$PRF_SCHOOL.edu1}:{/if}</span></td>
                              <td height="30" align="left"><span class="smalltext"> </span></td>
                              <td width="250" height="30">
                                <p><span class="smalltext">{$PRF_SCHOOL.edu1_det}</span></p></td>
                            </tr>
                            <tr>
                              <td height="30" align="left" valign="middle"><span class="blackboldtext">{if ($PRF_SCHOOL.edu2)}{$PRF_SCHOOL.edu2}:{/if}</span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30" ><span class="smalltext">{$PRF_SCHOOL.edu2_det}</span></td>
                            </tr>
                            <tr valign="middle">
                              <td colspan="3" align="right">&nbsp;</td>
                            </tr>
								{if ($OWN=='Y')}
								<tr align="center" valign="middle">
								  <td colspan="3"><a href="{makeLink mod=member pg=profile}act=school{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/edit.jpg" width="77" height="22" border="0"></a></td>
								</tr>
								{/if}
							{else}
                            	{if ($OWN=='Y')}
								<tr align="center" valign="middle">
								  <td colspan="3"><a href="{makeLink mod=member pg=profile}act=school{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/add1.jpg" width="77" height="22" border="0"></a></td>
								</tr>
								{/if}
							{/if}
							<tr valign="middle">
                              <td colspan="3" align="right">&nbsp;</td>
                            </tr>
                          </table></td>
                        </tr>
                    </table></td>
                    <td>&nbsp;</td>
                  </tr>
                 
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="18"><span class="smalltext"><strong class="greyboldtext style1"><strong class="greyboldtext style1">Movies</strong></strong></span></td>
                    <td height="18">&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td><table width="100%" height="52"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
                        <tr>
							<tr valign="middle">
                              <td colspan="3" align="right" bgcolor="#EEEEEE">&nbsp;</td>
                            </tr>
                          <td width="500" height="50" align="left" valign="top" bgcolor="#EEEEEE"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                            {if isset($PRF_MOVIES)}
							
                            <tr>
                              <td width="144" height="30" align="left" valign="middle"><span class="blackboldtext">Favorite Movies: </span></td>
                              <td height="30" align="left"><span class="smalltext"> </span></td>
                              <td width="250" height="30">
                                <p><span class="smalltext">{$PRF_MOVIES.fav_movies}</span></p></td>
                            </tr>
                            <tr>
                              <td height="30" align="left" valign="middle"><span class="blackboldtext">Favorite Actors: </span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30" ><span class="smalltext">{$PRF_MOVIES.fav_actors}</span></td>
                            </tr>
							 <tr>
                              <td width="144" height="30" align="left" valign="middle"><span class="blackboldtext">Favorite Directors: </span></td>
                              <td height="30" align="left"><span class="smalltext"> </span></td>
                              <td width="250" height="30">
                                <p><span class="smalltext">{$PRF_MOVIES.fav_dctrs}</span></p></td>
                            </tr>
							 <tr>
                              <td width="144" height="30" align="left" valign="middle"><span class="blackboldtext">Favorite Genre: </span></td>
                              <td height="30" align="left"><span class="smalltext"> </span></td>
                              <td width="250" height="30">
                                <p><span class="smalltext">{$PRF_MOVIES.fav_genre}</span></p></td>
                            </tr>
							 <tr>
                              <td width="144" height="30" align="left" valign="middle"><span class="blackboldtext">Favorite Soundtracks: </span></td>
                              <td height="30" align="left"><span class="smalltext"> </span></td>
                              <td width="250" height="30">
                                <p><span class="smalltext">{$PRF_MOVIES.fav_sound}</span></p></td>
                            </tr>
							  <tr>
                              <td width="144" height="30" align="left" valign="middle"><span class="blackboldtext">Movie Quotes I Say all the time </span></td>
                              <td height="30" align="left"><span class="smalltext"> </span></td>
                              <td width="250" height="30">
                                <p><span class="smalltext">{$PRF_MOVIES.mov_qts}</span></p></td>
                            </tr>
							 <tr>
                              <td width="144" height="30" align="left" valign="middle"><span class="blackboldtext">Movies I can Watch over and over </span></td>
                              <td height="30" align="left"><span class="smalltext"> </span></td>
                              <td width="250" height="30">
                                <p><span class="smalltext">{$PRF_MOVIES.mov_watch}</span></p></td>
                            </tr>
							 <tr>
                              <td width="144" height="30" align="left" valign="middle"><span class="blackboldtext">Actor that would play me in a movie:</span></td>
                              <td height="30" align="left"><span class="smalltext"> </span></td>
                              <td width="250" height="30">
                                <p><span class="smalltext">{$PRF_MOVIES.play_actor}</span></p></td>
                            </tr>
							 
                            <tr valign="middle">
                              <td colspan="3" align="right">&nbsp;</td>
                            </tr>
								{if ($OWN=='Y')}
								<tr align="center" valign="middle">
								  <td colspan="3"><a href="{makeLink mod=member pg=profile}act=movies{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/edit.jpg" width="77" height="22" border="0"></a></td>
								</tr>
								{/if}
							{else}
                            	{if ($OWN=='Y')}
								<tr align="center" valign="middle">
								  <td colspan="3"><a href="{makeLink mod=member pg=profile}act=movies{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/add1.jpg" width="77" height="22" border="0"></a></td>
								</tr>
								{/if}
							{/if}
							<tr valign="middle">
                              <td colspan="3" align="right">&nbsp;</td>
                            </tr>
                          </table></td>
                        </tr>
                    </table></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="18"><span class="smalltext"><strong class="greyboldtext style1">Music</strong></span></td>
                    <td height="18">&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td><table width="100%" height="52"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
                        <tr>
							<tr valign="middle">
                              <td colspan="3" align="right" bgcolor="#EEEEEE">&nbsp;</td>
                            </tr>
                          <td width="500" height="50" align="left" valign="top" bgcolor="#EEEEEE"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                            {if isset($PRF_MUSIC)}
							
                            <tr>
                              <td width="144" height="30" align="left" valign="middle"><span class="blackboldtext">Favorite Artists:</span></td>
                              <td height="30" align="left"><span class="smalltext"> </span></td>
                              <td width="250" height="30">
                                <p><span class="smalltext">{$PRF_MUSIC.fav_artist}</span></p></td>
                            </tr>
                            <tr>
                              <td height="30" align="left" valign="middle"><span class="blackboldtext">Favorite Albums:</span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30" ><span class="smalltext">{$PRF_MUSIC.fav_album}</span></td>
                            </tr>
							 <tr>
                              <td width="144" height="30" align="left" valign="middle"><span class="blackboldtext">Favorite Genre:</span></td>
                              <td height="30" align="left"><span class="smalltext"> </span></td>
                              <td width="250" height="30">
                                <p><span class="smalltext">{$PRF_MUSIC.fav_genre}</span></p></td>
                            </tr>
							 <tr>
                              <td width="144" height="30" align="left" valign="middle"><span class="blackboldtext">Favorite Songs: </span></td>
                              <td height="30" align="left"><span class="smalltext"> </span></td>
                              <td width="250" height="30">
                                <p><span class="smalltext">{$PRF_MUSIC.fav_song}</span></p></td>
                            </tr>
							 <tr>
                              <td width="144" height="30" align="left" valign="middle"><span class="blackboldtext">Favorite Lyrics: </span></td>
                              <td height="30" align="left"><span class="smalltext"> </span></td>
                              <td width="250" height="30">
                                <p><span class="smalltext">{$PRF_MUSIC.fav_lyric}</span></p></td>
                            </tr>
							  <tr>
                              <td width="144" height="30" align="left" valign="middle"><span class="blackboldtext">Songs for Roadtrips:</span></td>
                              <td height="30" align="left"><span class="smalltext"> </span></td>
                              <td width="250" height="30">
                                <p><span class="smalltext">{$PRF_MUSIC.song_trip}</span></p></td>
                            </tr>
							 <tr>
                              <td width="144" height="30" align="left" valign="middle"><span class="blackboldtext">Songs I hate: </span></td>
                              <td height="30" align="left"><span class="smalltext"> </span></td>
                              <td width="250" height="30">
                                <p><span class="smalltext">{$PRF_MUSIC.hate_song}</span></p></td>
                            </tr>
							 <tr>
                              <td width="144" height="30" align="left" valign="middle"><span class="blackboldtext">Guilty Pleasure: </span></td>
                              <td height="30" align="left"><span class="smalltext"> </span></td>
                              <td width="250" height="30">
                                <p><span class="smalltext">{$PRF_MUSIC.glty_plsr}</span></p></td>
                            </tr>
							 <tr>
                              <td width="144" height="30" align="left" valign="middle"><span class="blackboldtext">Songs I Repeat: </span></td>
                              <td height="30" align="left"><span class="smalltext"> </span></td>
                              <td width="250" height="30">
                                <p><span class="smalltext">{$PRF_MUSIC.song_rpt}</span></p></td>
                            </tr>
							 <tr>
                              <td width="144" height="30" align="left" valign="middle"><span class="blackboldtext">Band I wish I was in: </span></td>
                              <td height="30" align="left"><span class="smalltext"> </span></td>
                              <td width="250" height="30">
                                <p><span class="smalltext">{$PRF_MUSIC.band_in}</span></p></td>
                            </tr>
							
                            <tr valign="middle">
                              <td colspan="3" align="right">&nbsp;</td>
                            </tr>
								{if ($OWN=='Y')}
								<tr align="center" valign="middle">
								  <td colspan="3"><a href="{makeLink mod=member pg=profile}act=music{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/edit.jpg" width="77" height="22" border="0"></a></td>
								</tr>
								{/if}
							{else}
                            	{if ($OWN=='Y')}
								<tr align="center" valign="middle">
								  <td colspan="3"><a href="{makeLink mod=member pg=profile}act=music{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/add1.jpg" width="77" height="22" border="0"></a></td>
								</tr>
								{/if}
							{/if}
							<tr valign="middle">
                              <td colspan="3" align="right">&nbsp;</td>
                            </tr>
                          </table></td>
                        </tr>
                    </table></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="3">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="3">&nbsp;</td>
                  </tr>
              </table></td>
        </tr>
          </table>
		</td>
		</tr>
</table>