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
                  <td height="39" class="blackboldtext">Directory Search</td>
                </tr>
                <tr>
                  <td height="244" align="left" valign="top" class="blacktext"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="96%" valign="top">&nbsp;</td>
                        </tr>
                        <tr>
                          <td valign="top"><table width="80%"  border="0" cellpadding="2" cellspacing="0" bgcolor="#DEDEDE" class="border">
                              <tr>
                                <td height="20" valign="top" bgcolor="#F6F5F5" class="blacktext"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                    <tr>
                                      <td><form name="frmSrc" method="post" action=""><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                          <tr align="center" valign="middle">
                                            <td>
                                              <table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                                <tr>
                                                  <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td><table width="740"  border="0" align="left" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                      <td width="60" align="left" valign="middle">&nbsp;</td>
                                                      <td width="20" height="17" align="left" valign="middle"><div align="right"><span class="smalltext">
                                                          <input name="criteria" type="radio" class="checkbox" value="username" checked >
                                                      </span></div></td>
                                                      <td width="80" align="left" class="smalltext">Username</td>
                                                      <td width="20" align="center" valign="middle"><input name="criteria" type="radio" class="checkbox" value="name" {if ($smarty.request.criteria=="name")}checked {/if}></td>
                                                      <td width="58" class="smalltext">Name</td>
                                                      <td width="20" valign="middle"><input name="criteria" type="radio" class="checkbox" value="email" {if ($smarty.request.criteria=="email")}checked {/if}></td>
                                                      <td width="482" height="30" class="smalltext">Email</td>
                                                    </tr>
                                                  </table></td>
                                                </tr>
                                                <tr>
                                                  <td><span class="smalltext">
                                                  </span>                                                  <table width="726"  border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                      <td width="26" align="left">&nbsp;</td>
                                                      <td width="26" align="left"><span class="smalltext">
                                                        <input name="c1" type="checkbox" class="checkbox" id="c1"  onClick="changeStatus('txtuname','c1','');" value="yes" checked>
                                                      </span></td>
                                                      <td width="283" align="left"><input name="txtuname" type="text" class="input" size="36">                                                        
                                                      <span class="smalltext">
                                                        </span></td>
                                                      <td width="26" align="right" valign="middle"><div align="left"><span class="smalltext">
                                                          <input name="c2" type="checkbox" class="checkbox" id="c2" onClick="changeStatus('frage','c2','lbage');changeStatus('toage','c2','lbto');" value="yes">
                                                      </span></div></td>
                                                      <td width="86" align="left"><span class="smalltext"><label id="lbage" disabled>Age Between</label></span></td>
                                                      <td width="61"><select name="frage" class="input" id="select" disabled>
                                                        
                                                       {html_options values=$AGE_LIST output=$AGE_LIST}
                                                      
                                                      
                                                      </select></td>
                                                      <td width="27"><span class="smalltext"><label id="lbto" disabled>To</label></span></td>
                                                      <td width="191"><span class="smalltext">
                                                        <select name="toage" class="input" id="select2" disabled>
                                                          
                                                      {html_options values=$AGE_LIST output=$AGE_LIST selected="25"}
                                                      
                                                      
                                                        </select>
                                                      </span></td>
                                                    </tr>
                                                  </table></td>
                                                </tr>
                                                <tr>
                                                  <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td><table width="726"  border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                      <td width="26" align="left">&nbsp;</td>
                                                      <td width="26" height="17" align="left"><span class="smalltext">
                                                        <input name="c3" type="checkbox" class="checkbox" id="c3" onClick="changeStatus('country','c3','lb1');" value="yes" checked {if ($smarty.request.c3=="yes")}checked{/if}>
                                                      </span></td>
                                                      <td width="55" align="left" class="smalltext"><label id="lb1" >Country</label></td>
                                                      <td width="227"><select name="country" class="input" id="country">
                                                        <option value="">-----Any Country-----</option>
                                                        
							                          {html_options values=$COUNTRY_LIST.country_id output=$COUNTRY_LIST.country_name selected=$smarty.request.country}
                                                      
                                                      </select></td>
                                                      <td width="26" align="right" valign="middle"><div align="center" class="smalltext">
                                                        <div align="left">
                                                          <input name="c4" type="checkbox" class="checkbox" id="photo422" value="yes" checked onClick="changeStatus('gender','c4','lbgend');">
                                                        </div>
                                                      </div></td>
                                                      <td width="70" align="left" class="smalltext"><label id="lbgend">Gender</label> </td>
                                                      <td width="78"><span class="smalltext">
                                                        <select name="gender" class="input" id="select4">
                                                          <option value="">Any</option>
                                                          <option value="m">Male</option>
                                                          <option value="f">Female</option>
                                                        </select>
                                                      </span></td>
                                                      <td width="27"><span class="smalltext">
                                                        <input name="photo" type="checkbox" class="checkbox" id="photo5" value="yes" onClick="changeStatus('','photo','lbphoto');" >
                                                      </span></td>
                                                      <td width="191"><span class="smalltext"><label id="lbphoto" disabled>With Photo</label> </span></td>
                                                    </tr>
                                                  </table></td>
                                                </tr>
                                                <tr>
                                                  <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                      <td width="40%" height="17"><span class="smalltext">
                                                      </span></td>
                                                      <td width="27%" class="smalltext">&nbsp;</td>
                                                      <td width="33%" class="smalltext"> <input name="btn_search" type="submit" class="naBtn" value="   Search  ">                                                        <span class="smalltext">
                                                        </span></td>
														
                                                    </tr>
													<tr>
													<td width="40%" class="smalltext">&nbsp;</td>
													</tr>
                                                  </table></td>
                                                </tr>
                                            </table></td>
                                          </tr>
                                      </table></form></td>
                                    </tr>
                                </table></td>
                              </tr>
                          </table></td>
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
                                          <td height="24"><span class="blackboldtext"><span class="smalltext"><span class="footerlink"><strong><span class="toplink"><a href="{makeLink mod=member pg=search}act=message&uname={$prf->username}&ret_url={$smarty.server.QUERY_STRING|escape:'url'}{/makeLink}" class="middlelink">Send Message</a></span></strong></span></span></span></td>
                                        </tr>
                                        <tr>
                                          <td height="24"><span class="blackboldtext"><span class="smalltext"><span class="footerlink"><strong><span class="toplink"><a href="{makeLink mod=member pg=search}act=contact&uname={$prf->username}&ret_url={$smarty.server.QUERY_STRING|escape:'url'}{/makeLink}" class="middlelink">Add to Contacts</a></span></strong></span></span></span></td>
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