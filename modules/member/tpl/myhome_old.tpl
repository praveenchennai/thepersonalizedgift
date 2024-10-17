<table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td height="45" valign="middle"> <div align="left" class="greyboldext">My Account</div></td>
                              </tr>
                              <tr>
								<td height="2" valign="top" ><hr size="1"  class="border1"/></td>
							  </tr>
                              <tr>
                                <td height="100%" valign="top"><table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0">
                                  <tr>
                                    <td width="12" height="56">&nbsp;</td>
                                    <td colspan="2" class="bodytext"> <p><strong>Welcome {$UNAME}</strong></p>
                                    <p>This is the overview for all of your account settings on Personalizedgift. You can change your Profile, manage or customize your settings, and find all of your recent purchases right here. </p></td>
                                  </tr>
								 {if $LAST_LG.last_login!=""}
                                  <tr>
                                    <td height="56">&nbsp;</td>
                                    <td colspan="2" class="bodytext"><div align="center"><strong>Your last successful login was on {$LAST_LG.last_login|date_format}  at {$LAST_LG.last_login|date_format:"%I:%M:%S %p"}
                                  </tr>
								  {/if}
                                  <tr>
                                    <td height="40" class="bodytext">&nbsp;</td>
                                    <td valign="top" class="bodytext"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td valign="top">                                          <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                            <tr valign="top">
                                              <td width="51%"><table width="100%"  border="0" cellpadding="3" cellspacing="0" class="border">
                                                <tr>
                                                  <td height="76" colspan="2" class="td-color"> Account Settings </td>
                                                </tr>
                                                <tr valign="top" class="table_bg">
                                                  <td width="40%" height="42"><span class="blacktext"><a href="{makeLink mod=member pg=register}act=add_edit{/makeLink}" class="bodytext"><u>My Profile</u></a><br />
                                                      <a href="{makeLink mod=member pg=home}act=change_pass{/makeLink}" class="bodytext"><u>Change Password</u></a> <br />
													  <a href="{makeLink mod=member pg=register}act=billing_det{/makeLink}" class="bodytext"><u>Billing Address</u></a> <br />
                                                      <a href="{makeLink mod=member pg=register}act=shipping_det{/makeLink}" class="bodytext"><u>Shipping Address</u></a></span></td>
                                                  <td width="60%" class="bodytext"><div align="left">These settings control your account settings on The PersonalizedGift</div></td>
                                                </tr>
                                                <tr class="table_bg">
                                                  <td height="28">&nbsp;</td>
                                                  <td height="28">&nbsp;</td>
                                                </tr>
                                              </table></td>
                                              <td width="3%">&nbsp;</td>
                                              <td width="46%"><table width="100%" height="100%"  border="0" cellpadding="3" cellspacing="0" class="border">
                                                <tr>
                                                  <td height="33" colspan="2" class="td-color"> Purchases</td>
                                                </tr>
                                                <tr valign="top" class="table_bg">
                                                  <td width="40%" height="60" class="blacktext"><a href="{makeLink mod=product pg=fav}act=last_purchase{/makeLink}" class="bodytext"><u>Recent Purchases</u></a><a href="#" class="bodytext"><u></u></a> <br></td>
                                                  <td width="60%" class="bodytext">You can see the recent purchase details in The PersonalizedGift</td>
                                                </tr>
                                                <tr valign="top" class="table_bg">
                                                  <td width="40%" height="28" class="blacktext">&nbsp;</td>
                                                  <td class="blacktext">&nbsp;</td>
                                                </tr>
                                              </table></td>
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
                                            <tr>
                                              <td>&nbsp;</td>
                                              <td>&nbsp;</td>
                                              <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td>&nbsp;</td>
                                              <td>&nbsp;</td>
                                              <td>&nbsp;</td>
                                            </tr>
                                          </table></td>
                                      </tr>
                                    </table></td>
                                    <td valign="top">&nbsp;</td>
                                  </tr>
                                </table></td>
                              </tr>
                              <tr>
                                <td valign="top">&nbsp;</td>
                              </tr>
                          </table>