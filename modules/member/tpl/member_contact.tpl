<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<script language="javascript">
{literal}
	function changeStatus(ctrl,chkbox,lbname) 
	{
		var chk=document.getElementsByName(chkbox);
		if(ctrl!='') 
		{
			var txt=document.getElementsByName(ctrl);
			txt[0].disabled=!chk[0].checked;

		}	
		if(lbname!='')
		{
			var lbl=document.getElementsByTagName("label");
			for(var i=0;i<lbl.length;i++)
			{
				if (lbl[i].id==lbname)
				{
					lbl[i].disabled=!chk[0].checked;
				}	
			}
		}		
	} 		
{/literal}
</script>
            <tr>
              <td width="5%">&nbsp;</td>
              <td width="90%"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr valign="middle">
                  <td height="39" class="blackboldtext">Contact List of {$USERINFO.username}</td>
                </tr>
                <tr>
                  <td height="244" align="left" valign="top" class="blacktext"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="96%" valign="top"><table  border="0" cellspacing="0" cellpadding="2">
                            <tr>
                              <td width="126" align="left" valign="middle" class="smalltext"><strong>{$USERINFO.username}</strong></td>
                            </tr>
                            <tr>
                              <td align="left" valign="middle"><a href="{makeLink mod=member pg=profile}act=public&uid={$USERINFO.id}{/makeLink}" ><img src="{if $USERINFO.image=='Y'}{$smarty.const.SITE_URL}/modules/member/images/userpics/thumb/{$USERINFO.id}.jpg {else} {$GLOBAL.tpl_url}/images/nophoto.jpg {/if}" border="0" class="border"></a></td>
                            </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td valign="top">&nbsp;</td>
                        </tr>
                        <tr>
                          <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                              {if count($PROFILE_LIST) > 0}
							  <tr>
                                <td width="20%" height="17">&nbsp;</td>
                                <td width="80%" class="smalltext">&nbsp;</td>
                              </tr>
							
                              <tr>
                                <td height="17" colspan="2"><table width="100%"  border="0" cellpadding="2" cellspacing="0" bgcolor="#DEDEDE" class="border">
                                  <tr>
                                    <td height="20" bgcolor="#DEDEDE"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td width="1%">&nbsp;</td>
                                        <td width="49%" class="smalltext">{$PROFILE_NUMPAD}</td>
                                        <td width="1%">&nbsp;</td>
                                      </tr>
                                    </table>                                      </td>
                                  </tr>
                                </table></td>
                                </tr>
                              <tr>
                                <td height="17">&nbsp;</td>
                                <td class="smalltext">&nbsp;</td>
                              </tr>
							  {foreach from=$PROFILE_LIST item=prf}
                              <tr>
                                <td height="17" colspan="2"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td width="29%" align="left" valign="top"><a href="{makeLink mod=member pg=profile}act=public&uid={$prf->id}{/makeLink}" ><img src="{if $prf->image=='Y'}{$smarty.const.SITE_URL}/modules/member/images/userpics/thumb/{$prf->id}.jpg {else} {$GLOBAL.tpl_url}/images/nophoto.jpg {/if}"  class="border"></a></td>
                                    <td width="50%" align="left" valign="top" class="smalltext"><table width="89%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                      <tr class="smalltext">
                                        <td width="36%" height="15"><strong>Username</strong></td>
                                        <td width="4%">:</td>
                                        <td width="60%" height="15">{$prf->username}</td>
                                      </tr>
                                      
                                      <tr class="smalltext">
                                        <td width="36%" height="15"><strong>Email</strong></td>
                                        <td width="4%">:</td>
                                        <td width="60%" height="15">{$prf->email}</td>
                                      </tr>
                                      <tr class="smalltext">
                                        <td width="36%" height="15"><strong>Gender</strong></td>
                                        <td width="4%">:</td>
                                        <td width="60%" height="15">{if ($prf->gender=="m")}Male {else} Female{/if}</td>
                                      </tr>
  
  
  <tr class="smalltext">
    <td width="36%" height="15"><strong>Country</strong></td>
    <td width="4%">:</td>
    <td width="60%" height="15">{$prf->country_name}</td>
  </tr>
                                    </table>                                      <br></td>
                                    <td width="2%" align="left" valign="top">&nbsp;</td>
                                    <td width="19%" align="left" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td height="24"><span class="blackboldtext"><span class="smalltext"><span class="footerlink"><strong><span class="toplink"><a href="{makeLink mod=member pg=profile}act=public&uid={$prf->id}{/makeLink}" class="middlelink">View Profile</a></span></strong></span></span></span></td>
                                        </tr>
                                        <tr>
                                          <td height="24"><span class="blackboldtext"><span class="smalltext"><span class="footerlink"><strong><span class="toplink"><a href="{makeLink mod=member pg=search}act=message&uname={$prf->username}{/makeLink}" class="middlelink">Send Message</a></span></strong></span></span></span></td>
                                        </tr>
                                        <tr>
                                          <td height="24"><span class="blackboldtext"><span class="smalltext"><span class="footerlink"><strong><span class="toplink"><a href="{makeLink mod=member pg=search}act=contact&uname={$prf->username}{/makeLink}" class="middlelink">Add to Contacts</a></span></strong></span></span></span></td>
                                        </tr>
                                    </table></td>
                                  </tr>
                                </table></td>
                              </tr>
							  
                              <tr>
                                <td height="17" colspan="2">&nbsp;</td>
                              </tr>
							    
                              <tr bgcolor="#b5b5b6">
                                <td height="1" colspan="2"><img src="{$GLOBAL.tpl_url}/images/spacer1.gif" width="1" height="1"></td>
                              </tr>
							  
                              <tr>
                                <td height="17" colspan="2">&nbsp;</td>
                              </tr>
							  {/foreach}
                              
                              
                              <tr>
                                <td height="17" colspan="2"><table width="100%"  border="0" cellpadding="2" cellspacing="0" bgcolor="#DEDEDE" class="border">
                                  <tr>
                                    <td height="20" bgcolor="#DEDEDE"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td width="1%">&nbsp;</td>
                                          <td width="49%" class="smalltext">{$PROFILE_NUMPAD}</td>
                                          <td width="1%">&nbsp;</td>
                                        </tr>
                                    </table></td>
                                  </tr>
                                </table></td>
                              </tr>
							   {/if}
                              <tr>
                                <td height="17" colspan="2">&nbsp;</td>
                              </tr>
                          </table></td>
                        </tr>
                      </table></td>
                    </tr>
                  </table></td>
                </tr>
              </table></td>
              <td width="5%">&nbsp;</td>
            </tr>
          </table>