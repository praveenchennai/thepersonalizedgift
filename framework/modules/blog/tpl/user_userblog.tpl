<table width="100%" border="0" cellpadding="0" cellspacing="0" class="blogMainBody"><!-- need change -->
        <tr>
          <td width="5%">&nbsp;</td>
          <td width="90%"><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="">
            <tr valign="middle">
              <td align="center" class="bodytext">&nbsp;              </td>
            </tr>
            <tr>
              <td width="33%" height="30" align="right" valign="middle" class="blacktext"><span class="bodytext"><span class="bodytext"><span class="footerlink"><strong><span class="toplink"><a href="{makeLink mod=member pg=home}{/makeLink}" class="middlelink">Home</a></span></strong></span></span></span></td>
            </tr>
            <tr>
              <td height="244" align="center" valign="top" class="blacktext">
                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="60%" height="100%"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="126" align="left" valign="top" class="bodytext"><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="bodytext">
                              <tr>
                                <td height="30" valign="top"  class="bodyuserHeadertext"><strong >{$USERINFO.username}</strong></td>
                              </tr>
                              <tr>
                                <td><img src="{if $USERINFO.image=='Y'}{$smarty.const.SITE_URL}/modules/member/images/userpics/thumb/{$USERINFO.id}.jpg {else} {$GLOBAL.tpl_url}/images/nophoto.jpg {/if}" border="0" class="border"></td>
                              </tr>
                            </table></td>
                            <td valign="bottom"><table width="100%"  border="0" cellspacing="0" cellpadding="2">
                                <tr>
                                  <td width="8%" height="30"  class="bodytext" align="left"><img src="{$GLOBAL.tpl_url}/images/music1.jpg" width="15" height="19" border="0" class="border"></td>
                                  <td width="25%"  class="bodytext" align="left"><a href="{makeLink mod=album pg=album}act=media&user_id={$USERINFO.id}{/makeLink}" class="blogMiddlelink"><strong>Music&nbsp;&nbsp;({$MS_COUNT})</strong></a></td>
                                  <td width="8%"   class="bodytext" align="left"><img src="{$GLOBAL.tpl_url}/images/group1.jpg" width="15" height="19" border="0" class="border"></td>
                                  <td width="25%"  class="bodytext" align="left"><a href="{makeLink mod=member pg=group}act=other&user_id={$USERINFO.id}{/makeLink}" class="blogMiddlelink"><strong>Groups&nbsp;&nbsp;({$GR_COUNT})</strong></a></td>
                                  <td    rowspan="3" align="right" valign="top" class="bodytext">{if ($OWN!='Y')}
                                      <table width="120" align="right"  border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td height="30"><span class="bodytext"><span class="footerlink"><strong><span class="toplink"><a href="{makeLink mod=member pg=search}act=message&uname={$USERINFO.username}&ret_url={$smarty.server.QUERY_STRING|escape:'url'}{/makeLink}" class="blogMiddlelink"><strong>Send Message</strong></a></span></strong></span></span></td>
                                        </tr>
                                        <tr>
                                          <td height="30"><span class="bodytext"><span class="footerlink"><strong><span class="toplink"><a href="{makeLink mod=member pg=search}act=contact&uname={$USERINFO.username}&ret_url={$smarty.server.QUERY_STRING|escape:'url'}{/makeLink}" class="blogMiddlelink"><strong>Add to Contacts</strong></a></span></strong></span></span></td>
                                        </tr>
                                    </table>
                                  {/if}&nbsp;</td>
                                  <td width="1%"  rowspan="3" align="right" class="bodytext">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td height="30" class="bodytext"><a href="{makeLink mod=album pg=album}act=media&user_id={$USERINFO.id}&crt=M1{/makeLink}" class="bodytext"></a><img src="{$GLOBAL.tpl_url}/images/cinema1.jpg" width="15" height="19" border="0" class="border"></td>
                                  <td height="30" class="bodytext"><a href="{makeLink mod=album pg=album}act=media&user_id={$USERINFO.id}&crt=M1{/makeLink}" class="blogMiddlelink"><strong>Movies&nbsp; ({$MV_COUNT})</strong></a></td>
                                  <td class="bodytext"><img src="{$GLOBAL.tpl_url}/images/friends1.jpg" width="15" height="19" border="0" class="border"></td>
                                  <td class="bodytext"><a href="{makeLink mod=member pg=search}act=mem_contact&user_id={$USERINFO.id}{/makeLink}" class="blogMiddlelink"><strong>Friends&nbsp;&nbsp;({$FR_COUNT})</strong></a></td>
                                </tr>
                                <tr>
                                  <td height="30" class="bodytext"><img src="{$GLOBAL.tpl_url}/images/photo1.jpg" width="15" height="19" border="0" class="border"></td>
                                  <td height="30" class="bodytext"><a href="{makeLink mod=album pg=album}act=media&user_id={$USERINFO.id}&crt=M2{/makeLink}" class="blogMiddlelink"><strong>Photos&nbsp; ({$PH_COUNT})</strong></a></td>
                                  <td colspan="2" class="bodytext">&nbsp;</td>
                                </tr>
                            </table></td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                    </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="">
                      <tr>
                        <td width="270" height="30" valign="middle"><span class="bodytext"><strong>E.T.E Rap Sheet</strong></span></td>
                        <td width="30" valign="top">&nbsp;</td>
                        <td valign="middle"><span class="bodytext"><strong >&nbsp;Basic Information</strong></span></td>
                      </tr>
                      <tr>
                        <td height="100%" valign="top" width="35%" bgcolor="#EEEEEE" class="border"><table width="100%" height="100%" align="center" cellpadding="0" cellspacing="0" class="blogSectionBoxColor">
                          <tr height="30">
                            <td width="9" align="left" valign="middle">&nbsp;</td>
                            <td width="200" align="left" valign="middle"><span class="bodytext"><strong>Education :</strong></span></td>
                            <td width="100" align="left"><span class="bodytext">{$USERINFO.education} </span> </td>
                          </tr>
                          <tr height="30">
                            <td align="left" valign="middle">&nbsp;</td>
                            <td  align="left" valign="middle"><p><span><span class="bodytext"><strong>Talents :</strong></span></span></p></td>
                            <td  align="left"><span class="bodytext">{$USERINFO.talents}</span></td>
                          </tr>
                          <tr height="30">
                            <td align="left">&nbsp;</td>
                            <td  align="left"><span class="bodytext"><strong>Experience :</strong></span></td>
                            <td  align="left" class="bodytext">{$USERINFO.experience}</td>
                          </tr>
                          <tr height="30">
                            <td align="left">&nbsp;</td>
                            <td align="left"><span class="bodytext"><strong>Rate Experience:</strong></span></td>
                            <td align="left" class="bodytext">
							{if (($USERINFO.rate_exp>0) && ($USERINFO.rate_exp<6))}
							<img title="{$USERINFO.rate_exp}" src="{$GLOBAL.tpl_url}/images/star{$USERINFO.rate_exp}.jpg" border="0" >
							{/if}
							</td>
                          </tr>
                          <tr height="30">
						  	<td align="left">&nbsp;</td>
                            <td colspan="2" align="left" class="bodytext"><hr><strong>                              
                              Rate Experience: 0-5 <br>
                            (0 none, 5 Professional)</strong> </td>
                          </tr>
                          <tr valign="middle">
                            <td  colspan="3" align="center" class="bodytext" valign="middle">&nbsp;</td>
                          </tr>
  {if ($OWN=='Y')}
  <tr align="center" valign="middle">
    <td colspan="3"><a href="{makeLink mod=member pg=profile}act=sheet{/makeLink}"><img  src="{$GLOBAL.tpl_url}/images/edit.jpg" width="77" height="22" border="0"></a></td>
  </tr>
  {/if}
                        </table></td>
                        <td valign="top" width="4%">&nbsp;</td>
                        <td align="left" valign="top" width="48%" bgcolor="#EEEEEE" class="border"><table width="100%" height="100%"  align="center" class="blogSectionBoxColor" cellpadding="0" cellspacing="0">
                          <tr>
                            <td align="left" valign="middle" width="4">&nbsp;</td>
                            <td width="8" align="left" valign="middle" >&nbsp;</td>
                            <td width="150" height="30" align="left" valign="middle" ><span class="bodytext"><strong>First Name</strong></span></td>
                            <td width="7" align="left" >:&nbsp;</td>
                            <td height="30" align="left" width="191" >
                              <p><span class="bodytext"> {$USERINFO.first_name}</span></p></td>
                          </tr>
                          <tr>
                            <td align="left" valign="middle">&nbsp;</td>
                            <td align="left" valign="middle">&nbsp;</td>
                            <td height="30" align="left" valign="middle"><span class="bodytext"><strong>Last Name</strong></span></td>
                            <td align="left">:</td>
                            <td height="30" align="left"><span class="bodytext">{$USERINFO.last_name}&nbsp;</span></td>
                          </tr>
  {if ($OWN=='Y') || ($USERINFO.dobshow=='Y')}
  <tr>
    <td align="left" valign="middle">&nbsp;</td>
    <td align="left" valign="middle">&nbsp;</td>
    <td height="30" align="left" valign="middle"><p><span><span class="bodytext"><strong>Date of Birth</strong></span></span></p></td>
    <td align="left">:</td>
    <td height="30" align="left"><span class="bodytext"> </span><span class="bodytext">{$USERINFO.dob} (yyyy-mm-dd) </span></td>
  </tr>
  {/if}
  <tr>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td height="30" align="left"><span class="bodytext"><strong>Sex</strong></span></td>
    <td align="left">:</td>
    <td height="30" align="left"><span class="bodytext">{if ($USERINFO.gender)=="m"} Male {else} Female {/if} </span></td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td height="30" align="left"><span class="bodytext"><strong>Relationship Status</strong></span></td>
    <td align="left">:</td>
    <td height="30" align="left"><span class="bodytext">{$USERINFO.rel_status}</span></td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td height="30" align="left"><span class="bodytext"><strong>Country</strong></span></td>
    <td align="left">:</td>
    <td height="30" align="left"><span class="bodytext">{$USERCTR.country_name} </span></td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td height="30" align="left"><span class="bodytext"><strong>City</strong></span></td>
    <td align="left">:</td>
    <td height="30" align="left"><span class="bodytext">{$USERINFO.city} </span></td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td height="30" align="left"><span class="bodytext"><strong>State</strong></span></td>
    <td align="left">:</td>
    <td height="30" align="left"><span class="bodytext">{$USERINFO.state} </span></td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td height="30" align="left"><span class="style9"><span class="bodytext"><strong>Zip code:</strong></span></span></td>
    <td align="left">:</td>
    <td height="30" align="left"><span class="bodytext">{$USERINFO.postalcode} </span></td>
  </tr>
  <tr>
   <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td height="30" colspan="4" align="left"><span class="style9"><span class="bodytext"><strong>Email : </strong></span></span><span class="bodytext">{$USERINFO.email} </span></td>
  </tr>
  <tr>
   <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td height="30" colspan="4" align="left"><span class="style9"><span class="bodytext"><strong>Looking for Collaboration : </strong></span></span><span class="bodytext">{if ($USERINFO.clb_flg==0)} No {else} Yes {/if} </span></td>
  </tr>
  <tr> {if (($USERINFO.im_id1!='') || ($USERINFO.im_id2!='') )}
    <tr valign="middle">
      <td height="30" colspan="5" align="center" class="bodytext" valign="middle">Instant Messengers</td>
    </tr>
    <tr>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td height="30" align="left"><span class="style9"><span class="bodytext">{if ($USERINFO.im_name1)}{$USERINFO.im_name1}:{/if}</span></span></td>
      <td align="left">&nbsp;</td>
      <td height="30" align="left"><span class="bodytext">{$USERINFO.im_id1} </span></td>
    </tr>
    <tr>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td height="30" align="left"><span class="style9"><span class="bodytext">{if ($USERINFO.im_name2)}{$USERINFO.im_name2}:{/if}</span></span></td>
      <td align="left">&nbsp;</td>
      <td height="30" align="left"><span class="bodytext">{$USERINFO.im_id2} </span></td>
    </tr>
    <tr valign="middle">
      <td colspan="5" align="right">&nbsp;</td>
    </tr>
  {/if} {if ($OWN=='Y')}
  <tr align="center" valign="middle">
    <td colspan="5"><a href="{makeLink mod=member pg=profile}act=basic{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/edit.jpg" width="77" height="22" border="0"></a></td>
  </tr>
  <tr align="center" valign="middle">
    <td colspan="5">&nbsp;</td>
  </tr>
  {/if}
                        </table></td>
                      </tr>
                    </table></td>
                    <td width="2%">&nbsp;</td>
                    <td width="25%" height="100%" rowspan="17" valign="top"><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td bgcolor="#EEEEEE" class="border" valign="top" height="440"> <form method="post">
                          <table width="100%" height="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="blogSectionBoxColor">
                            <tr>
                              <td>&nbsp;</td>
                              <td height="33"><div align="center"><span class="bodytext"><strong class="greyboldtext style1">T.I.P. News Release </strong></span></div></td>
                              <td>&nbsp;</td>
                            </tr>

                            <tr>
                              <td width="1%">&nbsp;</td>
                              <td width="96%" height="33"><span class="bodytext">Comments</span></td>
                              <td width="3%">&nbsp;</td>
                            </tr>
                            <tr>
                              <td class="bodytext" align="center">&nbsp;</td>
                              <td class="bodytext" align="center"><textarea name="tip" cols="28" rows="5" class="input" id="textarea7"></textarea></td>
                              <td class="bodytext" align="center">&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="right" class="bodytext" valign="middle">&nbsp;</td>
                              <td align="right" class="bodytext" height="19" valign="middle">&nbsp;</td>
                              <td align="right" class="bodytext" valign="middle">&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="right" class="bodytext" valign="middle">&nbsp;</td>
                              <td align="right" class="bodytext" height="25" valign="middle"><input name="image2" type="image" src="{$GLOBAL.tpl_url}/images/postcomment.jpg" width="107" height="22"></td>
                              <td align="right" class="bodytext" valign="middle">&nbsp;</td>
                            </tr>

							<tr>
                              <td align="right" class="bodytext" valign="middle">&nbsp;</td>
                              <td align="right" class="bodytext" height="25" valign="middle"><div align="left">
                                <table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                  <tr>
                                    <td height="10" colspan="2">&nbsp;</td>
                                  </tr>
  {if count($TIP_LIST) > 0} {foreach from=$TIP_LIST item=tip}
  <tr>
    <td height="27" colspan="2" class="bodytext">&bull; {$tip->tiptext}</td>
  </tr>
  <tr>
    <td width="65%" align="right" class="bodytext"><a href="{makeLink mod=member pg=profile}act=public&uid={$tip->user_id}{/makeLink}" class="bodytext"><img src="{if $tip->image=='Y'}{$smarty.const.SITE_URL}/modules/member/images/userpics/thumb/{$tip->user_id}.jpg {else} {$GLOBAL.tpl_url}/images/nophoto.jpg {/if}" width="25" height="25" border="0" class="border"></a></td>
    <td width="35%" align="right" class="bodytext"><a href="{makeLink mod=member pg=profile}act=public&uid={$tip->user_id}{/makeLink}" class="bodytext"><u>{$tip->username}</u></a></td>
  </tr>
  {/foreach}
  <tr>
    <td colspan="2" class="bodytext">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="bodytext"><table width="120" border="0" align="right" cellpadding="0" cellspacing="0">
        <tr>
          <td width="70" class="bodytext">{if isset($PRE_PG1)}<a href="{makeLink mod=blog pg=blog_userentry}act=list&username={$USERINFO.username}&type=tip&pgn={$PGN}{/makeLink}" class="bodytext"><u>{/if}Prev{if isset($PRE_PG1)}</u></a>{/if}</td>
          <td width="10">&nbsp;</td>
          <td width="40" class="bodytext">{if isset($NEXT_PG1)}<a href="{makeLink mod=blog pg=blog_userentry}act=list&username={$USERINFO.username}&type=tip&pgn={$PGN}{/makeLink}" class="bodytext"><u>{/if}Next{if isset($NEXT_PG1)}</u></a>{/if}</td>
        </tr>
    </table></td>
  </tr>
  {else}
  <tr>
    <td colspan="2" class="bodytext">&nbsp;</td>
  </tr>
  {/if}
  <tr>
    <td colspan="2" align="right" class="bodytext">&nbsp;</td>
  </tr>
                                </table>
                              </div></td>
                              <td align="right" class="bodytext" valign="middle">&nbsp;</td>
                            </tr>
							  <tr>
								
								<td height="40" align="center" class="bodytext"></td>
								                     
							  </tr>
                          </table>
                        </form></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr height="75%" bgcolor="#EEEEEE" class="blogSectionBoxColor">
                        <td class="border" valign="top"  ><table width="100%"    align="center" cellpadding="0" cellspacing="0" >
                          <tr>
                            <td height="20">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="30" class="greyboldtext style1"><div align="center">My Studio Wall</div></td>
                          </tr>
                          <tr>
                            <td align="left" class="bodytext"><table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                <tr>
                                  <td height="10" colspan="2">&nbsp;</td>
                                </tr>
        {if count($COMMENT_LIST) > 0} {foreach from=$COMMENT_LIST item=comment}
        <tr>
          <td height="27" colspan="2" class="bodytext">&bull; {$comment->comment}</td>
        </tr>
        <tr>
          <td width="60%" align="right" class="bodytext"><a href="{makeLink mod=member pg=profile}act=public&uid={$comment->user_id}{/makeLink}" class="bodytext"><img src="{if $comment->image=='Y'}{$smarty.const.SITE_URL}/modules/member/images/userpics/thumb/{$comment->user_id}.jpg {else} {$GLOBAL.tpl_url}/images/nophoto.jpg {/if}" width="25" height="25" border="0" class="border"></a></td>
          <td width="40%" align="right" class="bodytext"><a href="{makeLink mod=member pg=profile}act=public&uid={$comment->user_id}{/makeLink}" class="bodytext"><u>{$comment->username}</u></a></td>
        </tr>
        {/foreach}
        <tr>
          <td colspan="2" class="bodytext">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" class="bodytext"><table width="120" border="0" align="right" cellpadding="0" cellspacing="0">
              <tr>
                <td width="70" class="bodytext">{if isset($PRE_PG)}<a href="{makeLink mod=blog pg=blog_userentry}act=list&username={$USERINFO.username}&pageNo={$PRE_PG}&type=msg{/makeLink}" class="bodytext"><u>{/if}Prev{if isset($PRE_PG)}</u></a>{/if}</td>
                <td width="10">&nbsp;</td>
                <td width="40" class="bodytext">{if isset($NEXT_PG)}<a href="{makeLink mod=blog pg=blog_userentry}act=list&username={$USERINFO.username}&pageNo={$NEXT_PG}&type=msg{/makeLink}" class="bodytext"><u>{/if}Next{if isset($NEXT_PG)}</u></a>{/if}</td>
              </tr>
          </table></td>
        </tr>
        {else}
        <tr>
          <td colspan="2" class="bodytext">&nbsp;</td>
        </tr>
        {/if}
        <tr>
          <td colspan="2" align="right" class="bodytext">&nbsp;</td>
        </tr>
        </table></td>
 </tr>
                          <tr>
                            <td align="left" class="bodytext"><form method="post">
                              <table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
    {if ($OWN!='Y')}
      <tr>
        <td class="bodytext" align="center"><textarea name="comment" cols="28" rows="5" class="input" id="textarea8"></textarea></td>
      </tr>
      <tr>
        <td align="right" class="bodytext" height="19" valign="middle">&nbsp;</td>
      </tr>
      <tr>
        <td align="right" class="bodytext" height="25" valign="middle"><input name="image" type="image" src="{$GLOBAL.tpl_url}/images/postcomment.jpg" width="107" height="22"></td>
      </tr>
    {/if}
                              </table>
							  <input type="hidden" name="userid" value="{$USERINFO.id}">
							   <input type="hidden" name="username" value="{$USERINFO.username}">
                            </form></td>
                          </tr>
                        </table></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="18">&nbsp;</td>
                    <td height="18">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="18"><span class="bodytext"><strong >More about me </strong></span></td>
                    <td height="18">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="18">&nbsp;</td>
                    <td height="18">&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="border" height="50" bgcolor="#EEEEEE"><table width="100%"  height="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="blogSectionBoxColor">
                        <tr>
						<tr>
                              <td colspan="4" >&nbsp;</td>
                      </tr>
						
                          <td width="500"  align="left" valign="top" ><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                            {if isset($PRF_ABOUT)}
							<tr>
                              <td width="222" height="30" align="left" valign="middle"><span class="bodytext"><strong>Nciknames:</strong></span></td>
                              <td width="26" height="30" align="left"><span class="bodytext"> </span></td>
                              <td width="202" height="30">
                                <p><span class="bodytext">{$PRF_ABOUT.nickname}</span></p></td>
                            </tr>
                            <tr>
                              <td height="30" align="left" valign="middle"><span class="bodytext"><strong>Nationality:</strong></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30" ><span class="bodytext">{$PRF_ABOUT.nationality}</span></td>
                            </tr>
                            <tr>
                              <td height="30" align="left" valign="middle"><p><span><span class="bodytext"><strong>Religion :</strong></span></span></p></td>
                              <td height="30" align="left"><span class="bodytext"> </span></td>
                              <td height="30"><span class="bodytext">{$PRF_ABOUT.religion}</span></td>
                            </tr>
                            <tr>
                              <td height="30" align="left"><span class="bodytext"><strong>Heroes:</strong></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="bodytext">{$PRF_ABOUT.heroes} </span></td>
                            </tr>
                            <tr>
                              <td height="30" align="left"><span class="style9"><span class="bodytext"><strong>Interests:</strong></span></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="bodytext">{$PRF_ABOUT.interests} </span></td>
                            </tr>
                            <tr>
                              <td height="30" align="left"><span class="style9"><span class="bodytext"><strong>Expertise:</strong></span></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="bodytext">{$PRF_ABOUT.expertise} </span></td>
                            </tr>
							<tr>
                              <td height="30" align="left"><span class="style9"><span class="bodytext"><strong>Occupation:</strong></span></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="bodytext">{$PRF_ABOUT.occupation}</span></td>
                            </tr>
							<tr>
                              <td height="30" align="left"><span class="style9"><span class="bodytext"><strong>Industry:</strong></span></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="bodytext">{$PRF_ABOUT.industry}</span></td>
                            </tr>
							<tr>
                              <td height="30" align="left"><span class="style9"><span class="bodytext"><strong>Website:</strong></span></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="bodytext">{$PRF_ABOUT.website}</span></td>
                            </tr>
							<tr>
                              <td height="30" align="left"><span class="style9"><span class="bodytext"><strong>Books/Authors:</strong></span></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="bodytext">{$PRF_ABOUT.books}</span></td>
                            </tr>
							<tr>
                              <td height="30" align="left"><span class="style9"><span class="bodytext"><strong>Favorite Food:</strong></span></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="bodytext">{$PRF_ABOUT.food}</span></td>
                            </tr>
							<tr>
                              <td height="30" align="left"><span class="style9"><span class="bodytext"><strong>Places I've been to:</strong></span></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="bodytext">{$PRF_ABOUT.places}</span></td>
                            </tr>
							<tr>
                              <td height="30" align="left"><span class="style9"><span class="bodytext"><strong>Favorite Video Games:</strong></span></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="bodytext">{$PRF_ABOUT.video}</span></td>
                            </tr>
							<tr>
                              <td height="30" align="left"><span class="style9"><span class="bodytext"><strong>Favorite Tv Show:</strong></span></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="bodytext">{$PRF_ABOUT.tv}</span></td>
                            </tr>
							<tr>
                              <td height="30" align="left"><span class="style9"><span class="bodytext"><strong>Projects I've Worked on:</strong></span></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="bodytext">{$PRF_ABOUT.projects}</span></td>
                            </tr>
							<tr>
                              <td height="30" align="left"><span class="style9"><span class="bodytext"><strong>Places you may have seen me:</strong></span></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30"><span class="bodytext">{$PRF_ABOUT.place_you}</span></td>
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
                    <td height="18"><span class="bodytext"><strong>Education</strong></span></td>
                    <td height="18">&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="border" height="50" bgcolor="#EEEEEE"><table width="100%" height="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="blogSectionBoxColor">
                        <tr>
					  <tr valign="middle">
                              <td colspan="3" align="right" >&nbsp;</td>
                      </tr>
                          <td width="500" height="50" align="left" valign="top" ><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                            {if isset($PRF_SCHOOL)}
							
                            <tr>
                              <td width="144" height="30" align="left" valign="middle"><span class="bodytext"><strong>{if ($PRF_SCHOOL.edu1)}{$PRF_SCHOOL.edu1}:{/if}</strong></span></td>
                              <td height="30" align="left"><span class="bodytext"> </span></td>
                              <td width="250" height="30">
                                <p><span class="bodytext">{$PRF_SCHOOL.edu1_det}</span></p></td>
                            </tr>
                            <tr>
                              <td height="30" align="left" valign="middle"><span class="bodytext"><strong>{if ($PRF_SCHOOL.edu2)}{$PRF_SCHOOL.edu2}:{/if}</strong></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30" ><span class="bodytext">{$PRF_SCHOOL.edu2_det}</span></td>
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
                    <td height="18"><span class="bodytext"><strong><strong>Movies</strong></strong></span></td>
                    <td height="18">&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="border" height="50" bgcolor="#EEEEEE"><table width="100%" height="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="blogSectionBoxColor">
                        <tr>
					  <tr valign="middle">
                              <td colspan="3" align="right" >&nbsp;</td>
                      </tr>
                          <td width="500" height="50" align="left" valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                            {if isset($PRF_MOVIES)}
							
                            <tr>
                              <td width="219" height="30" align="left" valign="middle"><span class="bodytext"><strong>Favorite Movies:</strong> </span></td>
                              <td width="27" height="30" align="left"><span class="bodytext"> </span></td>
                              <td width="204" height="30">
                                <p><span class="bodytext">{$PRF_MOVIES.fav_movies}</span></p></td>
                            </tr>
                            <tr>
                              <td height="30" align="left" valign="middle"><span class="bodytext"><strong>Favorite Actors:</strong> </span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30" ><span class="bodytext">{$PRF_MOVIES.fav_actors}</span></td>
                            </tr>
							 <tr>
                              <td width="219" height="30" align="left" valign="middle"><span class="bodytext"><strong>Favorite Directors:</strong> </span></td>
                              <td height="30" align="left"><span class="bodytext"> </span></td>
                              <td width="204" height="30">
                                <p><span class="bodytext">{$PRF_MOVIES.fav_dctrs}</span></p></td>
                            </tr>
							 <tr>
                              <td width="219" height="30" align="left" valign="middle"><span class="bodytext"><strong>Favorite Genre:</strong> </span></td>
                              <td height="30" align="left"><span class="bodytext"> </span></td>
                              <td width="204" height="30">
                                <p><span class="bodytext">{$PRF_MOVIES.fav_genre}</span></p></td>
                            </tr>
							 <tr>
                              <td width="219" height="30" align="left" valign="middle"><span class="bodytext"><strong>Favorite Soundtracks:</strong> </span></td>
                              <td height="30" align="left"><span class="bodytext"> </span></td>
                              <td width="204" height="30">
                                <p><span class="bodytext">{$PRF_MOVIES.fav_sound}</span></p></td>
                            </tr>
							  <tr>
                              <td width="219" height="30" align="left" valign="middle"><span class="bodytext"><strong>Movie Quotes I Say all the time</strong> </span></td>
                              <td height="30" align="left"><span class="bodytext"> </span></td>
                              <td width="204" height="30">
                                <p><span class="bodytext">{$PRF_MOVIES.mov_qts}</span></p></td>
                            </tr>
							 <tr>
                              <td width="219" height="30" align="left" valign="middle"><span class="bodytext"><strong>Movies I can Watch over and over</strong> </span></td>
                              <td height="30" align="left"><span class="bodytext"> </span></td>
                              <td width="204" height="30">
                                <p><span class="bodytext">{$PRF_MOVIES.mov_watch}</span></p></td>
                            </tr>
							 <tr>
                              <td width="219" height="30" align="left" valign="middle"><span class="bodytext"><strong>Actor that would play me in a movie:</strong></span></td>
                              <td height="30" align="left"><span class="bodytext"> </span></td>
                              <td width="204" height="30">
                                <p><span class="bodytext">{$PRF_MOVIES.play_actor}</span></p></td>
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
                    <td height="18"><span class="bodytext"><strong>Music</strong></span></td>
                    <td height="18">&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="border" height="50" bgcolor="#EEEEEE"><table width="100%" height="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="blogSectionBoxColor">
                        <tr>
					  <tr valign="middle">
                              <td colspan="3" align="right">&nbsp;</td>
                      </tr>
                          <td width="500" height="50" align="left" valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                            {if isset($PRF_MUSIC)}
							
                            <tr>
                              <td width="215" height="30" align="left" valign="middle"><span class="bodytext"><strong>Favorite Artists:</strong></span></td>
                              <td width="27" height="30" align="left"><span class="bodytext"> </span></td>
                              <td width="208" height="30">
                                <p><span class="bodytext">{$PRF_MUSIC.fav_artist}</span></p></td>
                            </tr>
                            <tr>
                              <td height="30" align="left" valign="middle"><span class="bodytext"><strong>Favorite Albums:</strong></span></td>
                              <td height="30" align="left">&nbsp;</td>
                              <td height="30" ><span class="bodytext">{$PRF_MUSIC.fav_album}</span></td>
                            </tr>
							 <tr>
                              <td width="215" height="30" align="left" valign="middle"><span class="bodytext"><strong>Favorite Genre:</strong></span></td>
                              <td height="30" align="left"><span class="bodytext"> </span></td>
                              <td width="208" height="30">
                                <p><span class="bodytext">{$PRF_MUSIC.fav_genre}</span></p></td>
                            </tr>
							 <tr>
                              <td width="215" height="30" align="left" valign="middle"><span class="bodytext"><strong>Favorite Songs: </strong></span></td>
                              <td height="30" align="left"><span class="bodytext"> </span></td>
                              <td width="208" height="30">
                                <p><span class="bodytext">{$PRF_MUSIC.fav_song}</span></p></td>
                            </tr>
							 <tr>
                              <td width="215" height="30" align="left" valign="middle"><span class="bodytext"><strong>Favorite Lyrics: </strong></span></td>
                              <td height="30" align="left"><span class="bodytext"> </span></td>
                              <td width="208" height="30">
                                <p><span class="bodytext">{$PRF_MUSIC.fav_lyric}</span></p></td>
                            </tr>
							  <tr>
                              <td width="215" height="30" align="left" valign="middle"><span class="bodytext"><strong>Songs for Roadtrips:</strong></span></td>
                              <td height="30" align="left"><span class="bodytext"> </span></td>
                              <td width="208" height="30">
                                <p><span class="bodytext">{$PRF_MUSIC.song_trip}</span></p></td>
                            </tr>
							 <tr>
                              <td width="215" height="30" align="left" valign="middle"><span class="bodytext"><strong>Songs I hate:</strong> </span></td>
                              <td height="30" align="left"><span class="bodytext"> </span></td>
                              <td width="208" height="30">
                                <p><span class="bodytext">{$PRF_MUSIC.hate_song}</span></p></td>
                            </tr>
							 <tr>
                              <td width="215" height="30" align="left" valign="middle"><span class="bodytext"><strong>Guilty Pleasure:</strong></span></td>
                              <td height="30" align="left"><span class="bodytext"> </span></td>
                              <td width="208" height="30">
                                <p><span class="bodytext">{$PRF_MUSIC.glty_plsr}</span></p></td>
                            </tr>
							 <tr>
                              <td width="215" height="30" align="left" valign="middle"><span class="bodytext"><strong>Songs I Repeat:</strong> </span></td>
                              <td height="30" align="left"><span class="bodytext"> </span></td>
                              <td width="208" height="30">
                                <p><span class="bodytext">{$PRF_MUSIC.song_rpt}</span></p></td>
                            </tr>
							 <tr>
                              <td width="215" height="30" align="left" valign="middle"><span class="bodytext"><strong>Band I wish I was in:</strong> </span></td>
                              <td height="30" align="left"><span class="bodytext"> </span></td>
                              <td width="208" height="30">
                                <p><span class="bodytext">{$PRF_MUSIC.band_in}</span></p></td>
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
		  <td width="90%">&nbsp;</td>
        </tr>
		
</table>


<!--************* End of profile ***************-->
{if $type=='Y'}
<table width="100%"  border="0"  cellpadding="0" cellspacing="0" class="blogMainBody">
 <tr>
    <td height="100%" valign="top">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="5%">&nbsp;</td>
          <td width="90%">
		  <table width="100%"  border="0" cellspacing="0" cellpadding="0">             
              <tr>
                <td height="19" align="center" valign="top" class="blacktext">
				<div align="left">
                    <table width="100%" height="20"  border="0" cellpadding="0" cellspacing="2">
                      <tr>
                        <td width="89%" height="25" align="left" valign="middle"><a class="linkOneActive" href="{makeLink mod=blog pg=blog_category}act=list{/makeLink}">Browse</a>&nbsp;<img src="{$GLOBAL.mod_url}/images/rightarrow.gif" width="9" height="8">&nbsp;<a class="bodytext" href="{makeLink mod=blog pg=blog_subcategory}act=list&id={$CAT_NAME.id}{/makeLink}">{$CAT_NAME.cat_name}</a>&nbsp;<img src="{$GLOBAL.mod_url}/images/rightarrow.gif" width="9" height="8">&nbsp;<a class="bodytext" href="{makeLink mod=blog pg=bloglist}act=list&id={$SUBCAT_NAME.id}&parent_id={$SUBCAT_NAME.parent_id}{/makeLink}">{$SUBCAT_NAME.cat_name}</a></td>
                        <td width="11%" align="right" valign="middle"><a href="{makeLink mod=blog pg=searchBlog}act=list{/makeLink}" class="linkOneActive"><img src="{$GLOBAL.mod_url}/images/search.jpg" border="0"></a></td>
                      </tr>
                    </table>
                </div>
				</td>
              </tr>
              <tr>
                <td height="244" align="center" valign="top" class="blacktext">
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="173" valign="top"><table width="172"  border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="172" valign="top">
							<table width="170" height="88"  border="0" align="center" cellpadding="3" cellspacing="0" class="leftblogBorder">
                                <tr class="leftmenuheading">
                                  <td width="100%" height="22" valign="top" bgcolor="#EEEEEE" class="leftmenuheading">User Menu </td>
                                </tr>
                                <tr class="lefinterior">
                                  <td height="22" valign="top"><a href="{makeLink mod=member pg=home}act=form{/makeLink}" class="leftmenulink">Your profile</a></td>
                                </tr>
                                <tr class="lefinterior">
                                  <td height="22" valign="top">&nbsp;</td>
                                </tr>
                                <tr class="lefinterior">
                                  <td height="22" valign="top">&nbsp;</td>
                                </tr>
                            </table>
							</td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td valign="top">&nbsp;							</td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td>&nbsp;							</td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                          </tr>
                      </table></td>
                      <td valign="top">
                        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td colspan="4" align="center">
							  <table width="95%"  border="0" align="right" cellpadding="3" cellspacing="0">
 								 {if count($BLOG_LIST) > 0} {foreach from=$BLOG_LIST item=bloglist}
								  <tr>
									<td width="60%" height="25" class="bodyheadingtext">{$bloglist->post_title}</td>
								  </tr>
								  <tr>
									<td valign="top" class="bodytext">{$bloglist->create_date}&nbsp;{$bloglist->blogentrytime}<br><br>
									  {$bloglist->post_description}
									</td>
								  </tr>
								  <tr>
								    <td><table border="0" cellspacing="0" cellpadding="3">
                                      <tr>
                                        <td><a href="{makeLink mod=blog pg=blog_usercomments}act=list&id={$bloglist->id}&user_id={$USER_ID}&subcat_id={$SUBCAT_NAME.id}&parent_id={$SUBCAT_NAME.parent_id}{/makeLink}" class="blogMiddlelink"><span class="bodytext">
                                          {$bloglist->blogs_comments_no}
                                        </span>Comments</a></td>
                                        <td width="30">&nbsp;</td>
                                        <td><a href="{makeLink mod=blog pg=blog_email}act=form&id={$bloglist->id}&user_id={$USER_ID}&subcat_id={$SUBCAT_NAME.id}&parent_id={$SUBCAT_NAME.parent_id}{/makeLink}" class="blogMiddlelink">Email this</a></td>
                                        <td>&nbsp;</td>
                                      </tr>
                                      <tr>
                                        <td><span class="bodytext">
                                          {$bloglist->blog_rating}
  Ratings </span></td>
                                        <td>&nbsp;</td>
                                        <td width="70"><span class="bodytext">Rate this </span></td>
                                        <td> <a href="{makeLink mod=blog pg=blog_userentry}act=rating&id={$bloglist->id}&user_id={$USER_ID}&subcat_id={$SUBCAT_NAME.id}&parent_id={$SUBCAT_NAME.parent_id}&rateval=1{/makeLink}" class="linkOneActive"><img src="{$GLOBAL.mod_url}/images/Y.gif" border="0"></a> <a href="{makeLink mod=blog pg=blog_userentry}act=rating&id={$bloglist->id}&user_id={$USER_ID}&subcat_id={$SUBCAT_NAME.id}&parent_id={$SUBCAT_NAME.parent_id}&rateval=2{/makeLink}" class="linkOneActive"><img src="{$GLOBAL.mod_url}/images/Y.gif" border="0"></a> <a href="{makeLink mod=blog pg=blog_userentry}act=rating&id={$bloglist->id}&user_id={$USER_ID}&subcat_id={$SUBCAT_NAME.id}&parent_id={$SUBCAT_NAME.parent_id}&rateval=3{/makeLink}" class="linkOneActive"><img src="{$GLOBAL.mod_url}/images/Y.gif" border="0"></a> <a href="{makeLink mod=blog pg=blog_userentry}act=rating&id={$bloglist->id}&user_id={$USER_ID}&subcat_id={$SUBCAT_NAME.id}&parent_id={$SUBCAT_NAME.parent_id}&rateval=4{/makeLink}" class="linkOneActive"><img src="{$GLOBAL.mod_url}/images/Y.gif" border="0"></a> <a href="{makeLink mod=blog pg=blog_userentry}act=rating&id={$bloglist->id}&user_id={$USER_ID}&subcat_id={$SUBCAT_NAME.id}&parent_id={$SUBCAT_NAME.parent_id}&rateval=5{/makeLink}" class="linkOneActive"><img src="{$GLOBAL.mod_url}/images/Y.gif" border="0"></a> </td>
                                      </tr>
                                    </table></td>
							    </tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
  								{/foreach}
								  <tr>
									<td height="43" valign="top" class="bodytext">{$BLOG_NUMPAD}</td>
								  </tr>
  							{/if}
                            </table>
							</td>
                          </tr>
                          
                          <tr>
                            <td colspan="4">&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="4">&nbsp;</td>
                          </tr>
                      </table></td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td height="18" align="center" valign="top" class="blacktext">&nbsp;</td>
              </tr>
          </table></td>
          <td width="5%">&nbsp;</td>
        </tr>
    </table></td>
  </tr>
</table>
{/if}