<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="scripts/validator.js"></script>
{literal}
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
	var fields=new Array('friends');
	var msgs=new Array('Friends');
function removeAll()
{
	document.forms[0].friends.value='';
}	

function addAll()
{
	var items=document.forms[0].contact.options;
	for(i=0;i<items.length;i++)
	{
		if(items[i].value!='')
		{
			var str1=items[i].value+",";
			var str2=document.forms[0].friends.value;
			var sVal=str2.search(str1);
			if(sVal<0)
			{
			document.forms[0].friends.value+=items[i].value+","
			}
		}	
	}
}
function add()
{
if(document.forms[0].contact.value!='')
	{
		if(document.forms[0].friends.value=='')
		{
			document.forms[0].friends.value=document.forms[0].contact.value+",";
		}
		else
		{
			
			var str1=document.forms[0].contact.value+",";
			var str2=document.forms[0].friends.value;
			var sVal=str2.search(str1);
			if(sVal<0)
			{
				document.forms[0].friends.value+=document.forms[0].contact.value+",";
			}
			else
			{
				alert("Username already added");
			}
		}	
	}	
}
</script>
{/literal}
<form name="frmInvite"  action="" method="post" onSubmit="return chk(this)">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="5%">&nbsp;</td>
              <td width="90%" height="39"><span class="blackboldtext"><span class="blackboldtext">Invite Member </span></span></td>
              <td width="5%">&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr valign="middle">
                  <td valign="top" class="blackboldtext"><table width="100%" height="145"  border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="21%" height="145" valign="top"><table width="138" height="131"  border="0" cellpadding="0" cellspacing="0" class="border">
                          <tr>
                            <td width="136" height="129" align="center"><a href="#"><img src="{if $GRP_DET.image=='Y'}{$smarty.const.SITE_URL}/modules/member/images/groupimages/{$GRP_DET.id}.jpg{else}{$smarty.const.SITE_URL}/modules/member/images/groupimages/noimage.jpg{/if}" border="0"></a></td>
                          </tr>
                      </table></td>
                      <td width="57%" align="center" valign="middle"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
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
                          <td height="15" colspan="3"><a href="{makeLink mod=member pg=group}act=member&group_id={$GRP_DET.id}{/makeLink}" class="smalltext"><u>{$GRP_MEM.members} Members</u></a> | <a href="#" class="smalltext"><u>{$GRP_DIS.discussions} Discussions</u> </a></td>
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
                  </table> </td>
                </tr>
                <tr>
                  <td height="23" align="left" valign="middle" class="blacktext"><table width="720"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td colspan="3" class="blackboldtext"><div align="center"><span class="smalltext" style="color:#FF0000"><strong>{if isset($MESSAGE)}{$MESSAGE}{/if}</strong></span> </div></td>
                    </tr>
                    <tr>
                      <td width="302">&nbsp;</td>
                      <td width="4" height="18">&nbsp;</td>
                      <td width="414" height="18">&nbsp;</td>
                    </tr>
                    <tr>
                      <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td height="18" valign="top" class="smalltext"><span class="blackboldtext">Invite New Friends</span></td>
                        </tr>
                        <tr>
                          <td valign="top"><textarea name="friends" cols="44" rows="4" class="input" id="friends"></textarea></td>
                        </tr>
                        <tr>
                          <td height="40"><span class="smalltext">Enter The Industry page Username separated by Commas </span></td>
                        </tr>
                        <tr>
                          <td height="21">&nbsp;</td>
                        </tr>
                      </table></td>
                      <td height="18">&nbsp;</td>
                      <td height="18" valign="top">                        <table width="414"  border="0" cellspacing="0" cellpadding="0">
                          <tr valign="top">
                            <td width="143">&nbsp;</td>
                            <td width="126"><span class="blackboldtext">Contacts</span></td>
                            <td width="145">&nbsp;</td>
                          </tr>
                          <tr valign="top">
                            <td><table width="100"  border="0" align="center" cellpadding="0" cellspacing="0">
                              <tr>
                                <td height="22" align="center"><a href="javascript: add()"><img src="{$GLOBAL.tpl_url}/images/add.jpg" width="94" height="22" border="0" ></a></td>
                              </tr>
                              <tr>
                                <td height="5" align="center" valign="top"><img src="{$GLOBAL.tpl_url}/images/spacer.gif" width="1" height="5"></td>
                              </tr>
                              <tr>
                                <td height="22" align="center"><a href="javascript: addAll()"><img src="{$GLOBAL.tpl_url}/images/add-all.jpg" width="94" height="22" border="0" ></a></td>
                              </tr>
                              <tr>
                                <td height="5" align="center" valign="top"><img src="{$GLOBAL.tpl_url}/images/spacer.gif" width="1" height="5"></td>
                              </tr>
                              <tr>
                                <td height="22" align="center"><a href="javascript: removeAll()"><img src="{$GLOBAL.tpl_url}/images/remove-all.jpg" width="94" height="22" border="0" ></a></td>
                              </tr>
                            </table>
                            </td>
                            <td valign="middle"><select name="contact" size="5" id="contact" style="width:100px;">
							  {html_options values=$CONTACT.contact output=$CONTACT.contact}
                            </select></td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr valign="top">
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr valign="top">
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td height="30" colspan="3"><span class="blackboldtext">Your Message : </span></td>
                      </tr>
                    <tr>
                      <td height="18" colspan="3">
                        <table width="720"  border="0" cellpadding="3" cellspacing="0" bgcolor="#E4E4E4" class="border">
                          <tr>
                            <td width="1" height="29" class="smalltext">&nbsp;</td>
                            <td width="705" valign="bottom" class="smalltext"><strong>Subject:</strong> {$USERDET.first_name} has invited you to join Industry page group {$GRP_DET.groupname}
                            <input type="hidden" name="subject" value="{$USERDET.first_name} has invited you to join Industry page group {$GRP_DET.groupname}"></td>
                          </tr>
                          <tr>
                            <td height="24" valign="middle" class="smalltext">&nbsp; </td>
                            <td height="24" valign="top" class="smalltext"><strong>Message:</strong> (enter no more than 500 characters) 
                            <input name="group_id" type="hidden" id="group_id" value="{$GROUP_ID}"></td>
                          </tr>
                          <tr>
                            <td align="center" valign="top">&nbsp;</td>
                            <td align="left" valign="top"><textarea name="comments" cols="110" rows="6" class="input" id="comments"></textarea></td>
                          </tr>
                          <tr>
                            <td colspan="2">&nbsp;</td>
                          </tr>
                        </table></td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td valign="top" class="blacktext"><table width="22%" height="39"  border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="46%" height="11"><img src="{$GLOBAL.tpl_url}/images/spacer.gif" width="1" height="5"></td>
                      <td width="54%"></td>
                    </tr>
                    <tr>
                      <td height="22" valign="top"><input type="image" src="{$GLOBAL.tpl_url}/images/send.jpg" width="60" height="22" border="0" ></td>
                      <td valign="top"><a href="#"><img src="{$GLOBAL.tpl_url}/images/cancel.jpg" width="60" height="22" border="0"></a></td>
                    </tr>
                  </table></td>
                </tr>
              </table></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
</form>		  