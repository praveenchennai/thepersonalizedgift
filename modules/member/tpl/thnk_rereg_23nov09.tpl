{literal}
<script language="javascript">
function browser_check(){
if (navigator.appName=="Netscape"){
history.back();
}else{
history.go(-1);
}
}
</script>
{/literal}
<form name="frmReg" id="frmReg" method="post" action="" > 
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="36" align="center" valign="top">&nbsp;</td>
                <td height="45" colspan="2" align="left" valign="middle" class="greyboldext" >Registration Details</td>
              </tr>
			   <tr>
   					 <td height="2" colspan="3" valign="top"><div class="hrline"></div></td>
		  </tr>
              <tr>
                <td width="2%" align="center" valign="top">&nbsp;</td>
                <td colspan="2" align="center" valign="top"><table width="100%" border="0" align="right" cellpadding="0" cellspacing="0">
                  
				  <tr align="left">
				    <td height="18" colspan="6" class="styletext"  >&nbsp;</td>
			      </tr>
				  <tr align="left">
				    <td height="18" colspan="6" class="bodytext_new"  >Hi, {$FIRST_NAME}&nbsp;{$LAST_NAME}</td>
			      </tr>
				  
				   <tr align="left">
				    <td height="18" colspan="6" class="bodytext"  >&nbsp;</td>
			      </tr>
				   <tr align="left">
				    <td height="18" colspan="6" class="bodytext_new"  >Your store have been created successfully. An activation link has been sent to your email.
		Please click on that link to activate this account.  If you have not received an activation email please check your junk or spam mail folder.</td>
			      </tr>
				     <tr align="left">
				    <td height="18" colspan="6" class="bodytext"  >&nbsp;</td>
			      </tr>
				  
				    <tr align="left">
				    <td height="18" colspan="6" class="bodytext_new"  >Please login to the manage URL select template ,choose avatar for your store, setup payment and setup shipping information.</td>
			      </tr>
				  
				  <tr align="left">
                    <td height="18" colspan="6" class="styletext" >&nbsp;</td>
                  </tr>
				  <tr align="left">
                    <td height="18" colspan="6" class="bodytext_new" >Your Web-Store Link: &nbsp;&nbsp; <a href="{$smarty.const.SITE_URL}/{$SURL}" class="bodytext_new" target="_blank">{$smarty.const.SITE_URL}/{$SURL}</a></td>
                  </tr>
				   <tr align="left">
                    <td height="18" colspan="6" class="bodytext_new" >Manage Your Web-store: &nbsp;&nbsp; <a href="{$smarty.const.SITE_URL}/{$SURL}/manage" class="bodytext_new" target="_blank">{$smarty.const.SITE_URL}/{$SURL}/manage</a></td>
                  </tr>
				   <tr align="left">
                    <td height="18" colspan="6" class="styletext" >&nbsp;</td>
                 <!--  </tr>
				    <tr align="left">
                    <td height="18" colspan="6" class="bodytext_new" ><strong>Payment Details</strong></td>
                  </tr> -->
				
				   <!-- <tr>
				    <td height="18" colspan="6" class="bodytext_new" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="21%">Name:</td>
                        <td width="2%">&nbsp;</td>
                        <td width="77%">{$FIRST_NAME}&nbsp;{$LAST_NAME}</td>
                      </tr>
                      <tr>
                        <td>Item:</td>
                        <td>&nbsp;</td>
                        <td>{$ITEM_NAME}</td>
                      </tr>
					  <tr>
                        <td>Amount:</td>
                        <td>&nbsp;</td>
                        <td>{$AMOUNT}</td>
                      </tr>
                    </table></td>
			      </tr> -->
                  <tr>
                    <td width="4" class="bodytext">&nbsp;</td>
                    <td width="151" height="18" class="bodytext">&nbsp;</td>
                    <td width="16" class="bodytext">&nbsp;</td>
                    <td colspan="2" class="bodytext">&nbsp;</td>
                  </tr>
				  
                  <tr>
                    <td align="left" valign="middle" class="bodytext">&nbsp;</td>
                    <td height="18" align="left" valign="middle" class="bodytext">&nbsp;</td>
                    <td height="18" class="bodytext">&nbsp;</td>
                    <td colspan="2" class="bodytext"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="11%">&nbsp;</td>
                          <td width="24%" align="left" valign="top">&nbsp;
                                                      </td>
                          <td width="65%" align="left" valign="top">&nbsp;                         </td>
                        </tr>
                    </table></td>
                    <td width="14" class="bodytext">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="left" valign="middle" class="bodytext">&nbsp;</td>
                    <td height="18" align="left" valign="middle" class="bodytext">&nbsp;</td>
                    <td height="18" class="bodytext">&nbsp;</td>
                    <td width="273" class="bodytext">&nbsp;</td>
                    <td width="256" class="bodytext">&nbsp;</td>
                    <td class="bodytext">&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
  </table>
</form>
