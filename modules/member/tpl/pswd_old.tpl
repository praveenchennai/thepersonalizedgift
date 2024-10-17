<form name="frmPass" enctype="multipart/form-data" action="" method="post">
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr valign="middle">
      <td height="45" class="blackboldtext">&nbsp;</td>
      <td height="45" class="greyboldext">Password Retrieval</td>
    </tr>
    <tr>
	<td height="2" valign="top" colspan="4" ><hr size="1"  class="border1"/></td>
  </tr>
  {if ($SEND!=1)}
  <tr valign="middle">
    <td width="2%" height="39" class="bodytext">&nbsp;</td>
    <td width="98%" class="bodytext">Please provide your username and email address which you gave while registering the account</td>
  </tr>
  {/if}
  <tr>
    <td colspan="2" align="left" valign="middle" class="blacktext"><span class="bodytext"></span><span class="bodytext"> </span><span class="blackboldtext"><span class="bodytext"><span class="footerlink"><strong></strong></span></span></span>
        <div align="left">
          <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="blacktext">
          {if ($SEND==1)}
            <tr>
              <td colspan="4" align="right"><div align="center" class="bodytext">Your password has been sent to your email address</div></td>
            </tr>
            <tr>
              <td align="right">&nbsp;</td>
              <td align="right">&nbsp;</td>
              <td align="right">&nbsp;</td>
              <td width="56%">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4" align="right"><div align="center"><a href="{makeLink mod=member pg=login}{/makeLink}" class="blackboldtext">Go back to Login page</a> </div></td>
            </tr>
            <tr>
              <td align="right">&nbsp;</td>
              <td align="right">&nbsp;</td>
              <td align="right">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          {else}
          <tr>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td><span class="blackboldtext"><span class="bodytext" style="color:#FF0000"><strong>{if isset($MESSAGE)}{$MESSAGE}{/if}</strong></span></span></td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="2%" align="right">&nbsp;</td>
            <td width="36%" align="right" class="bodytext">Username:</td>
            <td width="2%" align="right"><div align="left"></div></td>
            <td><span class="bodytext">
              <input name="username" type="text" class="input" id="username">
            </span></td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
            <td align="right" class="bodytext">Email:</td>
            <td align="right"><div align="left"></div></td>
            <td><span class="bodytext">
              <input name="email" type="text" class="input" id="email">
            </span></td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td ><table width="100%"  border="0">
                <tr>
                  <td width="16%"><input name="submit" type="submit" class="button_class" style="height:22;width:80"  value="Submit" /></td>
                  <td width="84%"><input name="button" type="button" class="button_class" style="height:22;width:80" onClick="javascript: history.go(-1)" value="Cancel" /></td>
                </tr>
            </table></td>
            <td width="4%">&nbsp;</td>
          </tr>
          {/if}
          </table>
      </div></td>
  </tr>
  </table>
</form>