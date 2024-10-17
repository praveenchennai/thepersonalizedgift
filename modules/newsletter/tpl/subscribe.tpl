<table width="101%"  border="0" cellspacing="0" cellpadding="0">
  <tr valign="middle">
    <td width="90%" height="45" class="blackboldtext">&nbsp;&nbsp;&nbsp;<img src="{$GLOBAL.tpl_url}/images/subscribetonewsletters-.jpg"></td>
  </tr>
  <tr>
      <td height="2" valign="top" bgcolor="#b20d13"><img src="{$GLOBAL.tpl_url}/images/spacer.gif" width="1" height="1"></td>
   </tr>
  <tr>
    <td height="40" align="center" valign="top" class="blacktext"><div align="left">
	<span class="bodytext">
        &nbsp;&nbsp;Subscribe to Free Newsletters. Select Mailing Lists which interests you. <strong>
        <br>
        </strong></span>
            <div align="center"> <span class="smalltext" style="color:#FF0000"><strong>
              {if isset($MESSAGE)}
              {$MESSAGE}
              {/if}
    &nbsp; </strong></span></div>
    </div></td>
  </tr>
  <tr>
    <td height="244" align="center" valign="top" class="blacktext">
      <form name="form1" method="post" action="" style="margin:0px; ">
        <table border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#2e384f">
              <tr>
                <td width="10"><img src="{$GLOBAL.tpl_url}/images/top_left_brdr.jpg" width="10" height="10"></td>
                <td width="562" background="{$GLOBAL.tpl_url}/images/top_brdr.jpg"><img src="{$GLOBAL.tpl_url}/images/top_brdr.jpg" width="10" height="10"></td>
                <td width="10"><img src="{$GLOBAL.tpl_url}/images/top_right_brdr.jpg" width="10" height="10"></td>
              </tr>
              <tr>
                <td height="209" background="{$GLOBAL.tpl_url}/images/left_brdr.jpg"><img src="{$GLOBAL.tpl_url}/images/left_brdr.jpg" width="10" height="10"></td>
                <td align="center" valign="top" class="tablegreen2"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="11">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td width="11">&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                      <tr>
                        <td valign="top"  class="tablegreen2"><table width="500"  border="0" align="center" cellpadding="0" cellspacing="0" >
                          <tr>
                            <td width="500" align="left" valign="top" class="tablegreen2">
							<table width="90%" border="0" align="center" cellpadding="5" cellspacing="0">
                                <tr>
                                  <td colspan="2" class="bodytext">Mailing Lists</td>
                                </tr>
                                <tr>
                                  <td colspan="2"><div></div></td>
                                </tr>
                              {foreach from=$MAILING_LIST item=row}
                              {if $row->allow_subscription == 'Y'}
                              <tr>
                          <td width="20">
						  <input type="checkbox" name="list_id[]" id="{$row->id}" value="{$row->id}" {if $row->member_id}checked{/if} >
                         </td>
                                <td class="bodytext"><label for="{$row->id}">{$row->name}</label></td>
                              </tr>
                              {/if}
                              {/foreach}
                              <tr>
                                <td colspan="2"><div></div></td>
                              </tr>
                              <tr>
                                <td colspan="2"><input type="image" src="{$GLOBAL.tpl_url}/images/submit.jpg"/></td>
                              </tr>
                            </table></td>
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
                </table></td>
                <td background="{$GLOBAL.tpl_url}/images/right_brdr.jpg"><img src="{$GLOBAL.tpl_url}/images/right_brdr.jpg" width="10" height="10" border="0"></td>
              </tr>
              <tr>
                <td height="10"><img src="{$GLOBAL.tpl_url}/images/bottom_left_brdr.jpg" width="10" height="10"></td>
                <td background="{$GLOBAL.tpl_url}/images/bottom_brdr.jpg"><img src="{$GLOBAL.tpl_url}/images/bottom_brdr.jpg" width="10" height="10"></td>
                <td><img src="{$GLOBAL.tpl_url}/images/bottom_right_brdr.jpg" width="10" height="10"></td>
              </tr>
            </table>
      </form></td>
  </tr>
</table>
