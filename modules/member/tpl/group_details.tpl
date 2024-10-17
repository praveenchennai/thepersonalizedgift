<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="scripts/validator.js"></script>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
	var fields=new Array('topic','content');
	var msgs=new Array('Topic','Content');
</script>
<form name="frGrpDet" action="" method="post" onSubmit="return chk(this)">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr valign="middle">
                  <td height="39" class="blackboldtext">Group Details</td>
                  </tr>
                <tr>
                  <td align="left" valign="top" class="blacktext"><span class="smalltext"></span><span class="smalltext"> </span><span class="blackboldtext"><span class="smalltext"><span class="footerlink"><strong></strong></span></span></span><div align="left">
                      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><table width="100%" height="145"  border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="21%" height="145" valign="top"><table width="138" height="131"  border="0" cellpadding="0" cellspacing="0" class="border">
                                <tr>
                                  <td width="136" height="129" align="center"><a href="#"><img src="{if $GRP_DET.image=='Y'}{$smarty.const.SITE_URL}/modules/member/images/groupimages/{$GRP_DET.id}.jpg{else}{$smarty.const.SITE_URL}/modules/member/images/groupimages/noimage.jpg{/if}" border="0"></a></td>
                                </tr>
                            </table></td>
                            <td width="57%" valign="middle"><div align="center">
                              <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                <tr class="smalltext">
                                  <td height="15" colspan="3"><strong>{$GRP_DET.groupname}</strong></td>
                                </tr>
                                <tr class="smalltext">
                                  <td width="13%" height="15">Tags</td>
                                  <td width="2%">:</td>
                                  <td width="85%" height="15">{$GRP_DET.tags}</td>
                                </tr>
                                <tr class="smalltext">
                                  <td height="15">Type</td>
                                  <td>:</td>
                                  <td height="15">{$GRP_DET.type}</td>
                                </tr>
                                <tr class="smalltext">
                                  <td height="15">Owner</td>
                                  <td>:</td>
                                  <td height="15"><a class="smalltext" href="{makeLink mod=member pg=profile}act=public&uid={$GRP_DET.user_id}{/makeLink}" ><u>{$GRP_OWNER_NAME}</u></a></td>
                                </tr>
                                <tr class="smalltext">
                                  <td height="15">Created</td>
                                  <td>:</td>
                                  <td height="15">{$GRP_DET.createdate|date_format:"%A, %B %e, %Y"}</td>
                                </tr>
                                <tr class="smalltext">
                                  <td height="15" colspan="3"><a href="{makeLink mod=member pg=group}act=member&group_id={$GRP_DET.id}{/makeLink}" class="smalltext"><u>{$GRP_MEM.members} Members</u></a> | <a href="#" class="smalltext"><u>{$GRP_DIS.discussions} Discussions</u> </a></td>
                                </tr>
                              </table>
                            </div></td>
                            <td width="22%" align="right" valign="top"><br>
                              <table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#DEDEDE" class="border">
                                <tr>
                                  <td width="4%" height="20" bgcolor="#DEDEDE">&nbsp;</td>
                                  <td width="96%" bgcolor="#DEDEDE"><span class="blackboldtext">User Menu</span></td>
                                </tr>
								{if $MEM_FLG=='N'}
                                <tr bgcolor="#F6F5F5">
                                  <td height="20" class="blacktext">&nbsp;</td>
                                  <td height="20" bgcolor="#F6F5F5" class="blacktext"><a href="{makeLink mod=member pg=group}act=details&group_id={$GRP_DET.id}&owner={$GRP_DET.user_id}&type={$GRP_DET.type}&fn=join{/makeLink}" class="blacktext">Join this Group</a></td>
                                </tr>
								{else}
									{if $OWN_FLG=='Y'}
									<tr bgcolor="#F6F5F5">
									  <td height="20" class="blacktext">&nbsp;</td>
									  <td height="20" bgcolor="#F6F5F5" class="blacktext"><a href="{makeLink mod=member pg=group}act=details&group_id={$GRP_DET.id}&fn=invite{/makeLink}" class="blacktext">Invite Members</a></td>
									</tr>
									{/if}
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
                        <td><div align="center"><span class="smalltext" style="color:#FF0000"><strong>{if isset($MESSAGE)}{$MESSAGE}{/if}</strong></span></div></td>
                      </tr>
                      <tr>
                        <td height="20"><span class="blackboldtext">Discussions</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>
						
						
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						
												
                          <tr >
                            <td width="1%" height="25">&nbsp;</td>
                            <td width="24%"><span class="smalltext"><strong>Topic</strong> </span></td>
                            <td width="5%">&nbsp;</td>
                            <td width="16%"><span class="smalltext"><strong>Author</strong></span></td>
                            <td width="7%">&nbsp;</td>
                            <td width="15%"><span class="smalltext"><strong>Posts</strong></span></td>
                            <td width="5%">&nbsp;</td>
                            <td width="26%"><span class="smalltext"><strong>Last Post</strong> </span></td>
                          
                          </tr>
						  {foreach from=$TOPIC_LIST item=topic}
                          <tr bgcolor="#DEDEDE">
                            <td height="20" >&nbsp;</td>
                            <td ><a href="{makeLink mod=member pg=group}act=reply&group_id={$topic->group_id}&tpid={$topic->topicid}{/makeLink}" class="smalltext">{$topic->topic}</a></td>
                            <td >&nbsp;</td>
                            <td class="smalltext">{$topic->author}</td>
                            <td >&nbsp;</td>
                            <td ><span class="smalltext">{$topic->posts}</span></td>
                            <td >&nbsp;</td>
                            <td ><span class="smalltext">{$topic->lastpost|date_format:" %b %e, %Y at %l:%M %p"}</span></td>
                          </tr>
						 {/foreach}	
                        </table>
						</td>
                      </tr>
                      <tr>
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="99%" height="31"><div align="right" class="smalltext">{$TOPIC_NUMPAD}</div></td>
                            <td width="1%">&nbsp;</td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td>{if $MEM_FLG=='Y'}<table width="73%" height="214"  border="0" cellpadding="0" cellspacing="0">
                          
						  <tr>
                            <td height="18"><span class="blackboldtext">Add New Topic</span></td>
                          </tr>
                          <tr>
                            <td width="86%" height="19" valign="middle"><div align="left" class="smalltext">Topic</div></td>
                            <td valign="bottom">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="27" valign="middle"><span class="smalltext">
                              <input name="topic" type="text" id="topic" size="46">
                            </span>                              </textarea></td>
                            <td valign="bottom">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="19" valign="middle"><div align="left" class="smalltext">Message</div></td>
                            <td valign="bottom">&nbsp;</td>
                          </tr>
                          <tr>
						  <td height="76" valign="top">
                            <div align="left">
                              <textarea name="content" cols="45" rows="5" class="input" id="content"></textarea>
                            </div></td>
                            <td width="1%" valign="bottom">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="28" valign="top"><input type="image" src="{$GLOBAL.tpl_url}/images/addtopic.jpg" width="86" height="22">
                           </td>
                          </tr>
						  
					</table>{/if}
					<input name="group_id" type="hidden" id="group_id" value="{$GROUP_ID}" size="5">
					<input name="type" type="hidden" id="type" value="{$GRP_DET.type}">
					<input name="owner" type="hidden" id="owner" value="{$GRP_DET.user_id}">
					</td>
		  </tr>
		</table>
	  </div></td>
	</tr>
</table>
</form>
