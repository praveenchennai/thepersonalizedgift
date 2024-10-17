<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="scripts/validator.js"></script>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
	var fields=new Array('content');
	var msgs=new Array('Content');
</script>
<form name="frGrpPost" action="" method="post" onSubmit="return chk(this)">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr valign="middle">
                  <td height="39" class="blackboldtext">Group Details</td>
                  </tr>
                <tr>
                  <td align="left" valign="top" class="blacktext"><span class="smalltext"></span><span class="smalltext"> </span><span class="blackboldtext"><span class="smalltext"><span class="footerlink"><strong></strong></span></span></span>                    <div align="left">
                      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><table width="100%" height="137"  border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="21%" height="137" valign="top"><table width="138" height="131"  border="0" cellpadding="0" cellspacing="0" class="border">
                                <tr>
                                  <td width="136" height="129" align="center"><a href="#"><img src="{if $GRP_DET.image=='Y'}{$smarty.const.SITE_URL}/modules/member/images/groupimages/{$GRP_DET.id}.jpg{else}{$smarty.const.SITE_URL}/modules/member/images/groupimages/noimage.jpg{/if}" width="100" height="100" border="0"></a></td>
                                </tr>
                            </table></td>
                            <td width="57%" valign="middle"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                              <tr class="smalltext">
                                <td height="15" colspan="3"><strong>{$GRP_DET.groupname}</strong></td>
                              </tr>
                              <tr class="smalltext">
                                <td width="13%" height="15">Tags</td>
                                <td width="2%">:</td>
                                <td width="85%" height="15">{$GRP_DET.tags}</td>
                              </tr>
                              <tr class="smalltext">
                                <td height="15">Created</td>
                                <td>:</td>
                                <td height="15">{$GRP_DET.createdate|date_format:"%A, %B %e, %Y"}</td>
                              </tr>
                              <tr class="smalltext">
                                <td height="15" colspan="3"><a href="{makeLink mod=member pg=group}act=member&group_id={$GRP_DET.id}{/makeLink}" class="smalltext"><u>{$GRP_MEM.members} Members</u></a> | <a href="{makeLink mod=member pg=group}act=details&group_id={$GRP_DET.id}{/makeLink}" class="smalltext"><u>{$GRP_DIS.discussions} Discussions</u> </a></td>
                              </tr>
                            </table></td>
                            <td width="22%" align="right" valign="top"><br>
                                <table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#DEDEDE" class="border">
                                <tr>
                                  <td width="4%" height="20" bgcolor="#DEDEDE">&nbsp;</td>
                                  <td width="96%" bgcolor="#DEDEDE"><span class="blackboldtext">User Menu</span></td>
                                </tr>
								{if $MEM_FLG=='N'}
                                <tr bgcolor="#F6F5F5">
                                  <td height="20" class="blacktext">&nbsp;</td>
                                  <td height="20" bgcolor="#F6F5F5" class="blacktext"><a href="{makeLink mod=member pg=group}act=details&group_id={$GRP_DET.id}&fn=join{/makeLink}" class="blacktext">Join this Group</a></td>
                                </tr>
								{else}
								<tr bgcolor="#F6F5F5">
                                  <td height="20" class="blacktext">&nbsp;</td>
                                  <td height="20" bgcolor="#F6F5F5" class="blacktext"><a href="{makeLink mod=member pg=group}act=details&group_id={$GRP_DET.id}&fn=invite{/makeLink}" class="blacktext">Invite Members</a></td>
                                </tr>
								<tr bgcolor="#F6F5F5">
                                  <td height="20" class="blacktext">&nbsp;</td>
                                  <td height="20" bgcolor="#F6F5F5" class="blacktext"><a href="{makeLink mod=member pg=group}act=details&group_id={$GRP_DET.id}&fn=leave{/makeLink}" class="blacktext">Leave this Group</a></td>
                                </tr>
                                {/if}
                              </table></td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><table width="712" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="712" height="41" bgcolor="#CDCDCD"><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" class="border">
                              <tr>
                                <td width="1%" height="21" bgcolor="#E1E1E1">&nbsp;</td>
                                <td width="99%" bgcolor="#E1E1E1"><span class="smalltext"><a href="#" class="smalltext"><strong>{$TP_DET.topic} </strong></a></span><span class="smalltext"></span></td>
                              </tr>
                              <tr>
                                <td width="1%" height="23" class="smalltext style13">&nbsp;</td>
                                <td class="smalltext">Posted by {$TP_DET.first_name} on {$TP_DET.lastpost|date_format:" %b %e, %Y at %l:%M %p"}</td>
                              </tr>
                            </table></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                          </tr>
						  {foreach from=$REPLY_LIST item=reply}
                          <tr>
                            <td height="42" valign="top" bgcolor="#FFFFFF"><table width="100%" height="40" border="0" align="left" cellpadding="0" cellspacing="0">
                              <tr>
                                <td width="1%" bgcolor="#E1E1E1"><a href="#" class="smalltext"></a></td>
                                <td width="49%" bgcolor="#E1E1E1"><a href="#" class="smalltext"><strong>From : </strong>{$reply->first_name} </a></td>
                                <td width="50%" bgcolor="#E1E1E1"><div align="right"><span class="smalltext"><strong>Post :</strong>{$reply->lastpost|date_format:" %b %e, %Y at %l:%M %p"}&nbsp;</span></div></td>
                              </tr>
                              <tr>
                                <td width="1%" height="20" bgcolor="#FFFFFF" class="smalltext">&nbsp;</td>
                                <td height="20" colspan="2" bgcolor="#FFFFFF" class="smalltext">{$reply->content}</td>
                              </tr>
                            </table></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                          </tr>
						{/foreach}
                        </table></td>
                      </tr>
                      <tr>
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="99%" height="31"><div align="right" class="smalltext">{$REPLY_NUMPAD}</div></td>
                            <td width="1%">&nbsp;</td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td>{if $MEM_FLG=='Y'}<table width="73%" height="162"  border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="58%" height="18" class="blackboldtext">Add New Comment:</td>
                            <td width="42%">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="85" colspan="2"><textarea name="content" cols="45" rows="5" class="input" id="content"></textarea></td>
                            </tr>
                          <tr>
                            <td height="10">&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="22" valign="top"><input type="image" src="{$GLOBAL.tpl_url}/images/postcomment.jpg" width="107" height="22"></td>
                            <td>&nbsp;</td>
                          </tr>
                        </table>{/if}</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                    </table>
                  </div></td>
                </tr>
                <tr>
                  <td valign="top" class="blacktext style12">&nbsp;</td>
                </tr>
              </table>
			  </form>