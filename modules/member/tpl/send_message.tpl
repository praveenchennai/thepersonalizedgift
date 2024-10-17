<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;"> 
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
				<tr>
				  <td height="30">&nbsp;</td>
				</tr>
							
                <tr>
                  <td height="244" colspan="2" align="center" valign="top" class="blacktext"><table width="100%" height="56"  border="0" align="center" cellpadding="0" cellspacing="0" class="border">
                      <tr>
                        <td width="100%" height="54" align="left" valign="top" bgcolor="#EEEEEE"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
							
                            <tr>
                              <td align="center" valign="middle"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td width="100%" align="center" valign="top"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                      <tr>
                                        <td width="20%">&nbsp;</td>
                                        <td width="9%"><span class="blackboldtext">To:</span></td>
                                        <td width="64%"><span class="smalltext">
                                        <input name="to" type="text" class="formText" id="to" value="{$CONTACT_NAME}" size="40" maxlength="25" readonly >
                                        </span></td>
                                      </tr>
                                      <tr>
                                        <td height="18" colspan="3">&nbsp;</td>
                                      </tr>
                                      <tr>
                                        <td>&nbsp;</td>
                                        <td><span class="blackboldtext">Subject:</span></td>
                                        <td><span class="smalltext">
                                        <input name="subject" type="text" class="formText" id="subject" size="40" maxlength="25" >
                                        </span></td>
                                      </tr>
                                      <tr>
                                        <td height="18" colspan="3">&nbsp;</td>
                                      </tr>
                                      <tr>
                                        <td>&nbsp;</td>
                                        <td><span class="blackboldtext">Message:</span></td>
                                        <td align="left" valign="top"><span class="smalltext">
                                          <textarea name="comments" cols="31" rows="5" id="comments"></textarea>
                                        </span></td>
                                      </tr>
                                      <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td height="25" align="left" valign="middle"><span class="smalltext"> </span>
                                            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                              <tr>
                                                <td width="8%">&nbsp;</td>
                                                <td width="13%">&nbsp;</td>
                                                <td width="32%">&nbsp;</td>
                                                <td width="46%">&nbsp;</td>
                                              </tr>
                                             
                                              
                                              <tr>
                                                <td colspan="4"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                                  <tr>
                                                    <td width="32%"><div align="left"><input type="image" src="{$GLOBAL.tpl_url}/images/send1.jpg"  border="0"></div></td>
                                                    <td width="5%">&nbsp;</td>
                                                    <td width="63%"><a href="index.php?{$smarty.request.ret_url}"><img src="{$GLOBAL.tpl_url}/images/cancel.jpg"  border="0"></a></td>
                                                    
                                                  </tr>
                                                </table></td>
                                              </tr>
											   <tr>
                                                <td width="8%">&nbsp;</td>
                                                <td width="13%">&nbsp;</td>
                                                <td width="32%">&nbsp;</td>
                                                <td width="46%">&nbsp;</td>
                                              </tr>
                                          </table></td>
                                      </tr>
                                    </table></td>
                                    
                                  </tr>
                              </table></td>
                            </tr>
                           
                        </table></td>
                      </tr>
                  </table></td>
                </tr>
              </table>
</form>
