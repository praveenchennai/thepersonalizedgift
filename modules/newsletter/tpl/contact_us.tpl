<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="scripts/validator.js"></script>
{literal}
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
	var fields=new Array('your_name','your_email','message');
	var msgs=new Array("Your Name","Your Email","Message");
	
	var emails=new Array("your_email");
	var email_msgs=new Array("Invalid Your Email Address")

	function checkLength()
		{
		if (chk(document.frmReg))
			{
			return true;	 			
			}
		else
			{
			return false;
			}		
		}
</SCRIPT>
{/literal}
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td align="center"><div align="center">{messageBox}</div></td>
  </tr>
</table>
<form name="frmReg" id="frmReg" method="post" action="" onSubmit="return checkLength()"> 
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="36" align="center" valign="top">&nbsp;</td>
                <td height="45" colspan="2" align="left" valign="middle"><img src="{$GLOBAL.tpl_url}/images/contact-us.jpg"></td>
              </tr>
			   <tr>
   					 <td height="2" colspan="3" valign="top" bgcolor="#b20d13"><img src="{$GLOBAL.tpl_url}/images/spacer.gif" width="1" height="1"></td>
  				</tr>
              <tr>
                <td width="2%" align="center" valign="top">&nbsp;</td>
                <td colspan="2" align="center" valign="top"><table width="100%" height="400" border="0" align="right" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="18" colspan="5" class="bodytext"><strong>( * Mandatory Fields )</strong></td>
                  </tr>
                  <tr>
                    <td height="18" colspan="5" align="center" class="bodytext">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="18" colspan="5" class="bodytext">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="147" height="18" class="bodytext">Your  Name: *</td>
                    <td width="6" class="bodytext">&nbsp;</td>
                    <td colspan="2" class="bodytext"><input name="your_name" type="text" class="input" id="your_name" value="{$smarty.request.your_name}" size="30"/>
                    </td>
                  </tr>
                  <tr>
                    <td height="18" colspan="2" class="bodytext">&nbsp;</td>
                    <td colspan="2" class="bodytext">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="18" colspan="2" class="bodytext">Your Email Address: *</td>
                    <td colspan="2" class="bodytext"><input name="your_email" type="text" class="input" id="your_email" value="{$smarty.request.your_email}" size="30" /></td>
                  </tr>                 
				    <tr>
				     <td height="18" colspan="2" class="bodytext">&nbsp;</td>
				     <td colspan="2" class="bodytext">&nbsp;</td>
			      </tr>
				 <tr>
                    <td height="18" align="left" valign="top" class="bodytext"><div align="left">Message*</div></td>
                    <td height="18" class="bodytext">&nbsp;</td>
                    <td colspan="2" class="bodytext"><textarea name="message" cols="55" rows="12" class="input" id="message">{$smarty.request.message}</textarea></td>
                  </tr>
                  <tr>
                    <td height="18" class="bodytext"><div align="right"></div></td>
                    <td height="18" class="bodytext">&nbsp;</td>
                    <td colspan="2" class="bodytext">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="18" align="left" valign="middle" class="bodytext">&nbsp;</td>
                    <td height="18" class="bodytext">&nbsp;</td>
                    <td colspan="2" class="bodytext"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="18%"><input type="image" src="{$GLOBAL.tpl_url}/images/submit.jpg" /></td>
                          <td width="7%">&nbsp;</td>
                          <td width="75%"><a href="javascript: history.go(-1)"><img src="{$GLOBAL.tpl_url}/images/cancel.jpg" border="0" /></a></td>
                        </tr>
                    </table></td>
                    <td width="19" class="bodytext">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="18" align="left" valign="middle" class="bodytext">&nbsp;</td>
                    <td height="18" class="bodytext">&nbsp;</td>
                    <td width="180" class="bodytext">&nbsp;</td>
                    <td width="196" class="bodytext">&nbsp;</td>
                    <td class="bodytext">&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
  </table>
 </form>