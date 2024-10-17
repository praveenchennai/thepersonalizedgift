<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></script>
<script language="javascript">
{literal}
function submitForm() {
	document.form1.submit();
}
{/literal}
</script>
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
                              <tr>
    <td height="45" valign="middle" class="greyboldext">{$MOD_VARIABLES.MOD_HEADS.HD_PAST_ORDERS}</td>
  </tr>
                             <tr>
    <td height="2" valign="top" ><div class="hrline"></div></td>
  </tr>
                              <tr>
                                <td height="100%" valign="top"><table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0">
                                  <tr>
                                    <td width="12" height="56">&nbsp;</td>
                                    <td colspan="2" class="bodytext"><form id="form1" name="form1" method="post" action="" style="margin:0px;"><table width="95%"  border="0" cellpadding="0" cellspacing="0" class="bodytext">
                                      <tr>
                                        <TD align=middle><div align="right"><STRONG>{$MOD_VARIABLES.MOD_LABELS.LBL_DATE_RANGE}</STRONG> - {$MOD_VARIABLES.MOD_LABELS.LBL_FROM}
                                            <input type="text" name="date_from" size="10" onfocus="this.value='';popUpCalendar(this, this, 'yyyy-mm-dd', 0, 0)" readonly="readonly" value="{$DATE_FROM}" />
                                            &nbsp;&nbsp;   {$MOD_VARIABLES.MOD_LABELS.LBL_TO}&nbsp;</div></TD>
                                        <TD align=right><input type="text" name="date_to" size="10" onfocus="this.value='';popUpCalendar(this, this, 'yyyy-mm-dd', 0, 0)" readonly="readonly" value="{$DATE_TO}" />
                                        </TD>
                                        <TD align=middle><!--<input name="submit" type="submit" class="button_class" style="height:22;width:80" value="Submit" />--><img src="{$GLOBAL.tpl_url}/images/submit.jpg" align="absmiddle" style="cursor:pointer" onclick="javascript:submitForm();"  ></TD>
                                        <TD align=right>{if $ORDER_LIMIT}<STRONG>{$MOD_VARIABLES.MOD_LABELS.LBL_SHOW} </STRONG>{/if}</TD>
                                        <td width="2%">{$ORDER_LIMIT}</td>
                                      </tr>
                                    </table></form></td>
                                    </tr>
                                  <tr>
                                    <td height="40" class="bodytext">&nbsp;</td>
                                    <td valign="top" class="bodytext"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                            <tr valign="top">
                                              <td><table width="582" border="0" align="center" cellpadding="0" cellspacing="0" class="table_bg">
                                                  <tr>
                                                    <td width="10">&nbsp;</td>
                                                    <td width="562" >&nbsp;</td>
                                                    <td width="10">&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td >&nbsp;</td>
                                                    <td align="center" valign="top"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                          <td width="11">&nbsp;</td>
                                                          <td width="545">&nbsp;</td>
                                                          <td width="15">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td>&nbsp;</td>
                                                          <td><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                                              <tr>
                                                                <td valign="top"><table width="100%" border="0" align="center" cellpadding="4" cellspacing="1">
                                                                    {if $ORDER_LIST}
																	<tr class="table_bg">
                                                                      <td width="152" height="35" class="bodytext"><STRONG>{$MOD_VARIABLES.MOD_LABELS.LBL_ORDER}</STRONG></td>
                                                                      <td width="111" height="35" class= "bodytext"><STRONG>{$MOD_VARIABLES.MOD_LABELS.LBL_STATUS}</STRONG></td>
                                                                      <td width="158" height="35" class="bodytext"><STRONG>{$MOD_VARIABLES.MOD_LABELS.LBL_DATE_ORDERED}</STRONG></td>
                                                                      <td width="88" class="bodytext"><div align="right"><STRONG>{$MOD_VARIABLES.MOD_LABELS.LBL_TOTAL_PRICE}</STRONG></div></td>
                                                                    </tr>
																	{foreach from=$ORDER_LIST item=row}
                                                                    <tr class="table_bg">
                                                                      <td height="20" class="bodytext"><a class="bodytext" href="{makeLink mod=member pg=order}act=details&id={$row->id}{/makeLink}"><u>{$row->order_number}</u></a></td>
                                                                      <td height="26" align="left" class="bodytext">{$row->order_status_name}</td>
                                                                      <td height="26" class="bodytext">{$row->date_ordered_f}</td>
                                                                      <td class="bodytext"><div align="right">{$row->paid_price|number_format:"2"}</div></td>
                                                                    </tr>
																	{/foreach}
                                                                    <tr class="table_bg">
                                                                      <td height="26" colspan="4" class="bodytext"><div align="center">{$ORDER_NUMPAD}</div></td>
                                                                    </tr>
																	{else}
																	<tr class="table_bg">
                                                                      <td height="26" colspan="4" class="bodytext"><div align="center">{$MOD_VARIABLES.MOD_LABELS.LBL_NO_ORDERS}</div></td>
                                                                    </tr>
																	{/if}
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
                                                    <td >&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td height="10">&nbsp;</td>
                                                    <td >&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                  </tr>
                                                </table></td>
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