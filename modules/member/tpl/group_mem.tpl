<form name="frmGrList"  action="" method="post">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr valign="middle">
                  <td width="79%" height="39" class="blackboldtext"> Group List of <span class="smalltext"><strong>{$USERINFO.username}</strong></span></td>
                  <td width="21%" align="right" class="blackboldtext"><a href="{makeLink mod=member pg=group}act=mygroup{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/mygroups.jpg" width="86" height="22" border="0"></a></td>
                </tr>
                <tr>
                  <td colspan="2" align="left" valign="top" class="blacktext"><span class="smalltext"></span><span class="smalltext"> </span><span class="blackboldtext"><span class="smalltext"><span class="footerlink"><strong></strong></span></span></span>                    <div align="left">
                    <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="blacktext">
                      <tr>
                        <td width="24%" valign="top">                          <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td><table  border="0" cellspacing="0" cellpadding="2">
                                <tr>
                                  <td width="126" align="left" valign="middle" class="smalltext"><strong>{$USERINFO.username}</strong></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><a href="{makeLink mod=member pg=profile}act=public&uid={$USERINFO.id}{/makeLink}" ><img src="{if $USERINFO.image=='Y'}{$smarty.const.SITE_URL}/modules/member/images/userpics/thumb/{$USERINFO.id}.jpg {else} {$GLOBAL.tpl_url}/images/nophoto.jpg {/if}" border="0" class="border"></a></td>
                                </tr>
                              </table></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                          </table></td>
                        <td width="3%" align="right">&nbsp;</td>
                        <td align="right" valign="top"><span class="smalltext">
                        </span>                          <table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#DEDEDE" class="border">
                            <tr>
                              <td width="1%" height="20" bgcolor="#DEDEDE">&nbsp;</td>
                              <td width="29%" bgcolor="#DEDEDE"><span class="blackboldtext">{$GRP_HEADER}</span></td>
                              <td width="70%" bgcolor="#DEDEDE">&nbsp;</td>
                            </tr>
							<tr bgcolor="#F6F5F5">
                              <td height="50" colspan="3" bgcolor="#F6F5F5" class="blacktext"><table height="100%" width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                <tr>
								<td width="40%" align="right" class="blackboldtext">Search for a Group:&nbsp; </td>
                                  <td width="40%" align="right"><input name="txtSearch" type="text" id="txtSearch"  size="36"></td>
								   <td width="5%" align="right">&nbsp;</td>
								  <td width="15%" align="right"><span class="blackboldtext"><input type="image" src="{$GLOBAL.tpl_url}/images/search2.jpg" width="86" height="22" border="0"></span></td>
                                </tr>
                               
                              </table></td>
                              </tr>
                            {if count($GROUP_LIST)==0}
							<tr bgcolor="#F6F5F5">
                              <td colspan="3" bgcolor="#F6F5F5" class="blacktext"><table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                <tr>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td height="1" align="center"><span class="smalltext"><span class="smalltext" style="color:#FF0000"><strong>No Groups Found!!</strong></span></span></td>
                                </tr>
                                <tr>
                                  <td height="18">&nbsp;</td>
                                </tr>
                              </table></td>
                              </tr>
							{/if}
                            {foreach from=$GROUP_LIST item=grp}
                            <tr bgcolor="#F6F5F5">
                              <td colspan="3" bgcolor="#F6F5F5" class="blacktext"><table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                <tr>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td height="1" bgcolor="#DEDEDE"><img src="{$GLOBAL.tpl_url}/images/spacer.gif" width="1" height="1"></td>
                                </tr>
                                <tr>
                                  <td height="18">&nbsp;</td>
                                </tr>
                              </table></td>
                              </tr>
                            <tr bgcolor="#F6F5F5">
                              <td height="140" valign="top" bgcolor="#F6F5F5" class="blacktext">&nbsp;</td>
                              <td align="center" valign="middle" bgcolor="#F6F5F5" class="blacktext"><table width="138" height="131"  border="0" cellpadding="0" cellspacing="0" class="border">
                                <tr>
                                  <td width="136" height="129" align="center" valign="middle">{if ($grp->type!="private")}<a href="{makeLink mod=member pg=group}act=details&group_id={$grp->id}{/makeLink}">{/if}<img src="{if $grp->image=='Y'}{$smarty.const.SITE_URL}/modules/member/images/groupimages/{$grp->id}.jpg{else}{$smarty.const.SITE_URL}/modules/member/images/groupimages/noimage.jpg{/if}" border="0">{if ($grp->type!="private")}</a>{/if}</td>
                                </tr>
                              </table></td>
                              <td valign="top" bgcolor="#F6F5F5" class="smalltext">  <table width="100%"  border="0">
                                  <tr>
                                    <td align="right"><img src="{$GLOBAL.tpl_url}/images/{$grp->type}.jpg" border="0"></td>
                                  </tr>
                                  <tr>
                                    <td><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                      <tr class="smalltext">
                                        <td height="15" colspan="3"><strong>{$grp->groupname}</strong></td>
                                      </tr>
                                      <tr class="smalltext">
                                        <td width="14%" height="15">Tags</td>
                                        <td width="2%">:</td>
                                        <td width="84%" height="15">{$grp->tags}</td>
                                      </tr>
                                      <tr class="smalltext">
                                        <td height="15">Created</td>
                                        <td>:</td>
                                        <td height="15">{$grp->createdate|date_format:"%A, %B %e, %Y"}</td>
                                      </tr>
                                      <tr class="smalltext">
                                        <td height="15" colspan="3" class="smalltext">{if ($grp->type!="private")}<a href="{makeLink mod=member pg=group}act=member&group_id={$grp->id}{/makeLink}" class="smalltext">{/if}<u>{$grp->members} Members</u>{if ($grp->type!="private")}</a>{/if}| {if ($grp->type!="private")}<a href="{makeLink mod=member pg=group}act=details&group_id={$grp->id}{/makeLink}" class="smalltext">{/if}<u>{$grp->discussions} Discussions</u>{if ($grp->type!="private")}</a>{/if}</td>
                                      </tr>
                                    </table></td>
                                  </tr>
                                </table></td>
                            </tr>
                            
                            {/foreach}
							
							
							
                            <tr bgcolor="#F6F5F5">
                              <td height="20" colspan="3" bgcolor="#F6F5F5" class="blacktext">&nbsp;</td>
                              </tr>
                            <tr bgcolor="#DEDEDE">
                              <td height="20" colspan="3" >&nbsp;<span class="smalltext">{$GROUP_NUMPAD}</span></td>
                            </tr>
                          </table>                          </td>
                      </tr>
                    </table>
                  </div></td>
                </tr>
                <tr>
                  <td colspan="2" valign="top" class="blacktext">&nbsp;</td>
                </tr>
              </table>
			  </form>