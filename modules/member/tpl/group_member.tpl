<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="scripts/validator.js"></script>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
	var fields=new Array('topic','content');
	var msgs=new Array('Topic','Content');
</script>
<form name="frGrpDet" action="" method="post" onSubmit="return chk(this)">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr valign="middle">
                  <td height="39" class="blackboldtext">Member Details</td>
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
                                  <td height="15">Created</td>
                                  <td>:</td>
                                  <td height="15">{$GRP_DET.createdate|date_format:"%A, %B %e, %Y"}</td>
                                </tr>
                                <tr class="smalltext">
                                  <td height="15" colspan="3"><a href="{makeLink mod=member pg=group}act=member&group_id={$GRP_DET.id}{/makeLink}" class="smalltext"><u>{$GRP_MEM.members} Members</u></a> | <a href="{makeLink mod=member pg=group}act=details&group_id={$GRP_DET.id}{/makeLink}" class="smalltext"><u>{$GRP_DIS.discussions} Discussions</u> </a></td>
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
                        <td><div align="center">
                          <p><span class="smalltext" style="color:#FF0000"><strong>{if isset($MESSAGE)}{$MESSAGE}{/if}</strong></span></p>
                          <p>&nbsp;</p>
                        </div></td>
                      </tr>
                      <tr>
                        <td><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
   {foreach from=$GRP_MEM_LIST item=GRP_MEMBER}
       <tr >
         <td bgcolor="#FFFFFF" class="smalltext">&nbsp;</td>
         <td bgcolor="#FFFFFF" class="smalltext">&nbsp;</td>
         <td bgcolor="#FFFFFF" >&nbsp;</td>
         <td valign="middle" bgcolor="#FFFFFF" class="smalltext"><div align="right"><strong>Join Date: </strong>{$GRP_MEMBER->joindate|date_format:"%A, %B %e, %Y"}</div></td>
         <td valign="middle" bgcolor="#FFFFFF" class="smalltext">&nbsp;</td>
       </tr>
       <tr >
        <td width="1%" bgcolor="#FFFFFF" class="smalltext">&nbsp;</td>
        <td width="10%" bgcolor="#FFFFFF" class="smalltext"><table width="79%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left" class="smalltext"><a href="{makeLink mod=member pg=profile}act=public&uid={$GRP_MEMBER->id}{/makeLink}"><img src="{if $GRP_MEMBER->image=='Y'}{$smarty.const.SITE_URL}/modules/member/images/userpics/thumb/{$GRP_MEMBER->id}.jpg {else} {$GLOBAL.tpl_url}/images/nophoto.jpg {/if}" border="0" class="border"></a></td>
            </tr>
          <tr>
            <td width="72%" align="left" class="smalltext">&nbsp;</td>
            </tr>
        </table></td>
		<td width="2%" bgcolor="#FFFFFF" >&nbsp;</td>
        <td width="85%" valign="middle" bgcolor="#FFFFFF" class="smalltext"><table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
          <tr class="smalltext">
            <td height="15" colspan="3"><strong>{$GRP_MEMBER->username}</strong></td>
          </tr>
          <tr class="smalltext">
            <td width="14%" height="15">First Name</td>
            <td width="2%">:</td>
            <td width="84%" height="15">{$GRP_MEMBER->first_name}</td>
          </tr>
          <tr class="smalltext">
            <td height="15">Last Name</td>
            <td>:</td>
            <td height="15">{$GRP_MEMBER->last_name}</td>
          </tr>
          <tr class="smalltext">
            <td height="15">Email</td>
            <td>:</td>
            <td height="15">{$GRP_MEMBER->email}</td>
          </tr>
        </table></td>
        <td width="2%" valign="middle" bgcolor="#FFFFFF" class="smalltext"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td height="24"><span class="blackboldtext"><span class="smalltext"><span class="footerlink"><strong></strong></span></span></span></td>
                                        </tr>
                                        <tr>
                                          <td height="24"><span class="blackboldtext"><span class="smalltext"><span class="footerlink"><strong></strong></span></span></span></td>
                                        </tr>
                                        <tr>
                                          <td height="24"><span class="blackboldtext"><span class="smalltext"><span class="footerlink"><strong></strong></span></span></span></td>
                                        </tr>
                                    </table></td>
      </tr>
      <tr  bgcolor="#F6F5F5">
        <td colspan="3">&nbsp;</td>
      </tr>
  {/foreach}
                        </table></td>
                      </tr>
                      <tr>
                        <td></td>
                      </tr>
                      <tr>
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="99%" height="31"><div align="right" class="smalltext">{$GRP_MEM_NUMPAD}</div></td>
                            <td width="1%">&nbsp;</td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td>
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