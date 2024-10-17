<table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr valign="middle">
                  <td height="39" align="left" class="blackboldtext">Public Profile </td>
                </tr>
                <tr valign="middle">
                  <td height="23" align="left" valign="middle" class="blacktext"><table width="740"  border="0" cellpadding="0" cellspacing="0" class="border">
                    <tr>
                      <td width="216" valign="top"><table width="209"  border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="20">&nbsp;</td>
                          <td width="189"><table width="138" height="131"  border="0" cellpadding="0" cellspacing="0" >
                            <tr>
                              <td width="19" class="smalltext">&nbsp;</td>
                              <td width="119" class="smalltext"><strong>{$PRF.username}</strong></td>
                            </tr>
							<tr>
                              <td height="129" colspan="2" align="center"><img src="{if $PRF.image=='Y'}{$smarty.const.SITE_URL}/modules/member/images/userpics/thumb/{$PRF.id}.jpg {else} {$GLOBAL.tpl_url}/images/nophoto.jpg {/if}" border="0" class="border"></td>
                            </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td colspan="2">&nbsp;</td>
                        </tr>
                      </table></td>
                      <td width="10">&nbsp;</td>
                      <td width="327" align="left"><table width="89%"  border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr class="smalltext">
                          <td width="36%" height="15"><strong>First Name</strong></td>
                          <td width="4%">:</td>
                          <td width="60%" height="15">{$PRF.first_name}</td>
                        </tr>
                        <tr class="smalltext">
                          <td width="36%" height="15"><strong>Last Name</strong></td>
                          <td width="4%">:</td>
                          <td width="60%" height="15">{$PRF.last_name}</td>
                        </tr>
                        <tr class="smalltext">
                          <td width="36%" height="15"><strong>Email</strong></td>
                          <td width="4%">:</td>
                          <td width="60%" height="15">{$PRF.email}</td>
                        </tr>
                        <tr class="smalltext">
                          <td width="36%" height="15"><strong>Gender</strong></td>
                          <td width="4%">:</td>
                          <td width="60%" height="15">{if ($PRF.gender=="m")}Male {else} Female{/if}</td>
                        </tr>
  {if ($PRF.DOBSHOW=="Y")}
  <tr class="smalltext">
    <td width="36%" height="15"><strong>Date of Birth</strong></td>
    <td width="4%">:</td>
    <td width="60%" height="15">{$PRF.dob}</td>
  </tr>
  {/if}
  <tr class="smalltext">
    <td width="36%" height="15"><strong>Marital Status</strong></td>
    <td width="4%">:</td>
    <td width="60%" height="15">{$PRF.marital_status}</td>
  </tr>
  <tr class="smalltext">
    <td width="36%" height="15"><strong>Country</strong></td>
    <td width="4%">:</td>
    <td width="60%" height="15">{$CNTRY}</td>
  </tr>
 
                      </table></td>
                      <td width="187" valign="bottom"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="89%"><a href="{makeLink mod=member pg=profile}uid={$PRF.id}{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/viewadv.jpg" border="0"></a></td>
                          <td width="11%">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="2">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="23" align="left" valign="middle" class="blacktext"><span class="smalltext"></span><span class="smalltext"> </span><span class="blackboldtext"><span class="smalltext"><span class="footerlink"><strong><span class="toplink"></span></strong></span></span></span></td>
  </tr>
              </table>