
<form name="frmPh" action="" enctype="multipart/form-data" method="post" >
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                <tr valign="middle">
                  <td height="39" align="left" class="blackboldtext">Change Photo</td>
                </tr>
                <tr valign="middle">
                  <td height="23" align="center" valign="middle" class="blacktext"><table width="80%"  border="0" cellpadding="0" cellspacing="0" class="border">
                    <tr>
                      <td width="160" valign="top"><table width="149"  border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="11">&nbsp;</td>
                          <td width="147"><table width="123" height="167"  border="0" cellpadding="0" cellspacing="0" >
                            <tr>
                              <td width="7" class="smalltext">&nbsp;</td>
                              <td width="116" class="smalltext"><strong>Display Picture</strong></td>
                            </tr>
							<tr>
                              <td height="129" colspan="2" align="center"><img src="{if $USERINFO.image=='Y'}{$smarty.const.SITE_URL}/modules/member/images/userpics/thumb/{$USERINFO.id}.jpg?{$smarty.request.rnd} {else} {$GLOBAL.tpl_url}/images/nophoto.jpg?{$smarty.request.rnd} {/if}" border="0" class="border"></td>
                            </tr>
							<tr>
							  <td height="19" colspan="2" align="center"><a href="{makeLink mod=member pg=profile}act=change&remove=1{/makeLink}"  class="blacktext"><u>Remove Picture</u></a></td>
						    </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td colspan="2">&nbsp;</td>
                        </tr>
                      </table></td>
                      <td width="10">&nbsp;</td>
                      <td width="362" align="left"><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="smalltext">
                        <tr>
                          <td colspan="3"><div align="center"><span class="smalltext" style="color:#FF0000"><strong>{if isset($MESSAGE)}{$MESSAGE}{/if}</strong></span></div></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td><strong>User Image:</strong> </td>
                          <td width="76%"><span class="field">
                            <input name="image" type="file">
                          </span></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="33%"><input name="image2" type="image" src="{$GLOBAL.tpl_url}/images/save_changes.jpg" ></td>
                              <td width="7%">&nbsp;</td>
                              <td width="60%"><a href="{makeLink mod=member pg=home}{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/cancel.jpg" border="0"></a></td>
                            </tr>
                          </table></td>
                        </tr>
                      </table></td>
                      <td width="53" valign="bottom">&nbsp;</td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="23" align="left" valign="middle" class="blacktext"><span class="smalltext"></span><span class="smalltext"> </span><span class="blackboldtext"><span class="smalltext"><span class="footerlink"><strong><span class="toplink"></span></strong></span></span></span></td>
  </tr>
              </table>
			  </form>