<script language="javascript">
{literal}
function submit_form()
{
	document.frmSub.submit();
}
{/literal}
</script>
<form name="frmSub" id="frmSub" method="post" action="" > 
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="36" align="center" valign="top">&nbsp;</td>
                <td height="36" colspan="2" align="left" valign="middle" class="greyboldext" >Please Verify Your Email Address</td>
              </tr>
			   <tr>
   					 <td height="2" colspan="3" valign="top"><div class="hrline"></div></td>
		  </tr>
              <tr>
                <td width="2%" align="center" valign="top">&nbsp;</td>
                <td colspan="2" align="center" valign="top"><table width="100%" border="0" align="right" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="18" colspan="6" class="bodytext">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="18" colspan="6" class="bodytext"  style="font-size:14px"><div align="center">{messageBox}</div></td>
                  </tr>
				  <tr>
                    <td height="18" colspan="6" class="bodytext"  >&nbsp;</td>
                  </tr>
                <!--  <tr>
                    <td height="18" colspan="5" class="bodytext"  style="font-size:14px">You have not verified your email address.If you did not receive the activation link, please click on the link below to resend Activation Link</td>
                  </tr>
-->
                  <tr>
                    <td width="4" align="left" valign="middle" class="bodytext">&nbsp;</td>
                    <td width="151" height="18" align="left" valign="middle" class="bodytext">&nbsp;</td>
                    <td width="16" height="18" class="bodytext">&nbsp;</td>
                    <td colspan="2" class="bodytext">&nbsp;</td>
                    <td width="14" class="bodytext">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="18" colspan="4" align="left" valign="middle"><!--<input name="btn_save" type="submit"  id="btn_save"  value="Send Activation Link" class="button_class" style="width:175">-->
					{$SUBMITBUTTON}
                    </td>
                    <td width="256" class="bodytext">&nbsp;</td>
                    <td class="bodytext">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="left" valign="middle" class="bodytext">&nbsp;</td>
                    <td height="18" colspan="3" align="left" valign="middle"class="smalltext" style="font-size:14px" >&nbsp;</td>
                    <td class="bodytext">&nbsp;</td>
                    <td class="bodytext">&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
  </table>
</form>
