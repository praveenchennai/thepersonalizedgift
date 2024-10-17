<table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr valign="middle">
                  <td width="79%" height="39" class="blackboldtext"><p><br>
                    My Groups</p>
                    <p class="blacktext">The groups you own and belong to are shown below.<br>
                      <br>
                  </p>                  </td>
                  <td width="21%" align="right" class="blackboldtext">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="2" align="left" valign="top" class="blacktext"><span class="smalltext"></span><span class="smalltext"> </span><span class="blackboldtext"><span class="smalltext"><span class="footerlink"><strong></strong></span></span></span>                    <div align="left">
                    <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="blacktext">
                      <tr>
                        <td width="24%" valign="top">                          <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#DEDEDE" class="border">
                                <tr>
                                  <td width="4%" height="20" bgcolor="#DEDEDE">&nbsp;</td>
                                  <td width="96%" bgcolor="#DEDEDE"><span class="blackboldtext">Browse</span></td>
                                </tr>
                                <tr bgcolor="#F6F5F5">
                                  <td height="20" class="blacktext">&nbsp;</td>
                                  <td height="20" bgcolor="#F6F5F5" class="blacktext">{if (!isset($CRT))}<img src="{$GLOBAL.tpl_url}/images/arrow3.jpg" width="14" height="16" border="0" align="absmiddle">{/if}<a href="{makeLink mod=member pg=group}act=mygroup{/makeLink}" class="blacktext">All your Groups</a></td>
                                </tr>
								<tr bgcolor="#F6F5F5">
                                  <td height="20" class="blacktext">&nbsp;</td>
                                  <td height="20" class="blacktext">{if ($CRT=='M1')}<img src="{$GLOBAL.tpl_url}/images/arrow3.jpg" width="14" height="16" border="0" align="absmiddle">{/if}<a href="{makeLink mod=member pg=group}act=mygroup&crt=M1{/makeLink}" class="blacktext">Groups you own</a></td>
                                </tr>
                                
                               
                              </table></td>
                            </tr>
                            <tr>
                              <td>                                </td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td><a href="{makeLink mod=member pg=group}act=create{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/createagroup.jpg" border="0"></a></td>
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
                                  <td width="136" height="129" align="center" valign="middle"><a href="{makeLink mod=member pg=group}act=details&group_id={$grp->id}{/makeLink}"><img src="{if $grp->image=='Y'}{$smarty.const.SITE_URL}/modules/member/images/groupimages/{$grp->id}.jpg{else}{$smarty.const.SITE_URL}/modules/member/images/groupimages/noimage.jpg{/if}" border="0"></a></td>
                                </tr>
                              </table></td>
                              <td valign="top" bgcolor="#F6F5F5" class="smalltext">  <table width="100%"  border="0">
                                  <tr>
                                    <td align="right"><img src="{$GLOBAL.tpl_url}/images/{$grp->type}.jpg" border="0"></td>
                                  </tr>
                                  <tr>
                                    <td><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                      <tr class="smalltext">
                                        <td height="15" colspan="2"><strong>{$grp->groupname}</strong></td>
                                      </tr>
                                      <tr class="smalltext">
                                        <td width="10%" height="15">Tags:</td>
                                        <td width="90%" height="15">{$grp->tags}</td>
                                      </tr>
                                      <tr class="smalltext">
                                        <td height="15">Created:</td>
                                        <td height="15">{$grp->createdate|date_format:"%A, %B %e, %Y"}</td>
                                      </tr>
                                      <tr class="smalltext">
										<td height="15" colspan="2">Member since {$grp->joindate|date_format:"%B %e, %Y"}</td>
                                       
                                      </tr>
                                      <tr class="smalltext">
                                        <td height="15" colspan="2" class="smalltext"><a href="{makeLink mod=member pg=group}act=member&group_id={$grp->id}{/makeLink}" class="smalltext"><u>{$grp->members} Members</u></a>| <a href="{makeLink mod=member pg=group}act=details&group_id={$grp->id}{/makeLink}" class="smalltext"><u>{$grp->discussions} Discussions</u> </a></td>
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