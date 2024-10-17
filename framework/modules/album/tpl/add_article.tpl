{literal} 
<script language="javascript">
	function chkFrom()
	{
		var status=0;
		var i=0;
		
		if(document.pForm.delId.length  > 0)
		{
			for( i=0; i <document.pForm.delId.length; i++){
				if(document.pForm.delId[i].checked==true){
					 status++;
				}
			}
		}
		
		if(status==1)
		{
			document.pForm.frmAction.value='update';
			document.pForm.submit();
		}
		else 
		{
			alert("Please select one.");
			return false; 
		}
		
	}
function chkUpdate()
{
	document.pForm.frmAction.value='update';
	document.pForm.submit();
}	
function createNew()
{
	document.pForm.frmAction.value='insert';
	document.pForm.submit();
}	

</script>
{/literal}
<form action="" method=post enctype="multipart/form-data" name="pForm" id=pForm > 
 <table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
    <tr>
      <td>{messageBox}</td>
    </tr>
  </table>
 <br>
<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr>
<tr>
      <td></td>
    </tr> 
  <tr> 
    <td><table width="98%" align="center"> 
	<tr>
      <td>&nbsp;</td>
    </tr>
        <tr> 
          <td width="50%" nowrap class="naH1">Add Conference </td> 
          <td width="50%" align="right" nowrap class="titleLink"><a href="{makeLink mod=album pg=album_admin}act=list_conference&link=Y{/makeLink}">List Conference </a></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#F6F5F5" >
              <tr>
                <td ><table width="100%" height="24" border="0" cellpadding="0" cellspacing="0" >
                        <tr>
                          <td width="1%" class="naGridTitle">&nbsp;</td>
                          <td width="99%" class="naGridTitle">&nbsp;</td>
                        </tr>
                    </table></td>
              </tr>
              <tr>
                <td align="center" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="10">
				{if isset($MESSAGE)}
				<tr class=naGrid2>
					<td valign=top colspan=3 align="center">
					<span class="naError">{$MESSAGE}</span>
				  </td>
				</tr>
   				 {/if} 
                  <tr>
                    <td align="center" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0" >
                      <tr>
                        <td width="31%" align="right"><span class="blacktext">Full Conference name*</span></td>
                        <td width="5%" align="center" valign="middle"><span class="blacktext">:</span></td>
                        <td width="64%" height="30" colspan="3"><input name="conference_name" type="text" class="inputblue" size="47" value="{$CONFERENCE.conference_name}"></td>
                      </tr>
					    <tr>
                        <td width="31%" align="right">Tags</td>
                        <td width="5%" align="center" valign="middle"><span class="blacktext">:</span></td>
                        <td width="64%" height="30" colspan="3"><textarea name="tag" class="inputblue" style="width:295px; height:100px" >{$CONFERENCE.tag}</textarea></td>
					    </tr>
						<tr>
                        <td align="right">&nbsp;</td>
                        <td align="center" valign="middle">&nbsp;</td>
                        <td height="30" colspan="3">(Enter tags seperated by pipes.)</td>
						</tr>
                      <tr>
                        <td align="right"><span class="blacktext">Acronym*</span></td>
                        <td align="center" valign="middle"><span class="blacktext">:</span></td>
                        <td height="30" colspan="3"><input name="conference_acronym" id="acronym" type="text" class="inputblue" size="47" value="{$CONFERENCE.conference_acronym}"></td>
                      </tr>
                      <tr>
                        <td align="right"><span class="blacktext">Place*</span></td>
                        <td align="center" valign="middle"><span class="blacktext">:</span></td>
                        <td height="30" colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="30" valign="bottom"><span class="blacktext">Town</span></td>
                            <td valign="bottom"><span class="blacktext">State</span></td>
                            <td valign="bottom"><span class="blacktext">Country*</span></td>
                          </tr>
                          <tr>
                            <td width="38%" height="30"><input name="conference_town" type="text" class="inputblue" size="25" value="{$CONFERENCE.conference_town}"></td>
                            <td width="34%"><input name="conference_state" type="text" class="inputblue" size="25" value="{$CONFERENCE.conference_state}"></td>
                            <td width="28%"><select name="conference_country" class="inputblue">
                              <option value="">---Select Country--</option>
							  	{if isset($CONFERENCE.conference_country)}
							   	{html_options values=$COUNTRY_LIST.country_id output=$COUNTRY_LIST.country_name selected=$CONFERENCE.conference_country}
								{else}
							   	{html_options values=$COUNTRY_LIST.country_id output=$COUNTRY_LIST.country_name selected='840'}
								{/if}
                            </select></td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td align="right" class="blacktext">Date *</td>
                        <td align="center" valign="middle"><span class="blacktext">:</span></td>
                        <td height="30" colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
						  <td width="6%" height="30"><span class="blacktext">Day</span></td>
                            <td width="15%"><input name="conference_day" type="text" class="inputblue" size="5" value="{$CONFERENCE.conference_day}"></td>
                            <td width="11%" height="30"><span class="blacktext">Month*</span></td>
                            <td width="21%"><select name="conference_month" class="inputblue">
                            		<option value="1" {if ( isset($CONFERENCE)&& $CONFERENCE.conference_month=='1')}selected{/if}>January</option>
									<option value="2" {if ( isset($CONFERENCE)&& $CONFERENCE.conference_month=='2')}selected{/if}>February</option>
									<option value="3" {if ( isset($CONFERENCE)&& $CONFERENCE.conference_month=='3')}selected{/if}>March</option>
									<option value="4" {if ( isset($CONFERENCE)&& $CONFERENCE.conference_month=='4')}selected{/if}>April</option>
									<option value="5" {if ( isset($CONFERENCE)&& $CONFERENCE.conference_month=='5')}selected{/if}>May</option>
									<option value="6" {if ( isset($CONFERENCE)&& $CONFERENCE.conference_month=='6')}selected{/if}>June</option>
									<option value="7" {if ( isset($CONFERENCE)&& $CONFERENCE.conference_month=='7')}selected{/if}>July</option>
									<option value="8" {if ( isset($CONFERENCE)&& $CONFERENCE.conference_month=='8')}selected{/if}>August</option>
									<option value="9" {if ( isset($CONFERENCE)&& $CONFERENCE.conference_month=='9')}selected{/if}>September</option>
									<option value="10" {if ( isset($CONFERENCE)&& $CONFERENCE.conference_month=='10')}selected{/if}>October</option>
									<option value="11"{if ( isset($CONFERENCE)&& $CONFERENCE.conference_month=='11')}selected{/if}>November</option>
									<option value="12"{if ( isset($CONFERENCE)&& $CONFERENCE.conference_month=='12')}selected{/if}>december</option>
                            </select></td>
                            <td width="9%"><span class="blacktext">Year*</span></td>
                            <td width="38%"><select name="conference_year" class="inputblue">
                              {foreach from=$YEAR_LIST item=year name=loop2}
							 {if (isset($CONFERENCE.conference_year))}
							  {html_options values=$year output=$year selected=$CONFERENCE.conference_year}
							  {else}
                              {html_options values=$year output=$year selected=$NOW}
							  {/if}
							  {/foreach} 
                            </select></td>
                          </tr>
                        </table></td>
                      </tr>
					  <tr>
                        <td align="right"><span class="blacktext">Sponsors*</span></td>
                        <td align="center" valign="middle"><span class="blacktext">:</span></td>
                        <td height="30" colspan="3"><input name="conference_sponsors" type="text" class="inputblue" size="47" value="{$CONFERENCE.conference_sponsors}"></td>
                      </tr>
					   <tr>
                        <td align="right" class="blacktext">Publisher</td>
                        <td align="center" valign="middle"><span class="blacktext">:</span></td>
                        <td height="30" colspan="3"><input name="conference_publisher" type="text" class="inputblue" size="47" value="{$CONFERENCE.conference_publisher}"></td>
                      </tr>
					  <tr>
                        <td align="right" class="blacktext">URL of the conference</td>
                        <td align="center" valign="middle"><span class="blacktext">:</span></td>
                        <td height="30" colspan="3"><input name="conference_url" type="text" class="inputblue" size="47" value="{$CONFERENCE.$smarty.request.url_conference}"></td>
                      </tr>
					  <tr>
                        <td align="right" class="blacktext">ISBN/ISSN</td>
                        <td align="center" valign="middle"><span class="blacktext">:</span></td>
                        <td height="30" colspan="3"><input name="conference_isbn" id="conference_isbn" type="text" class="inputblue" size="47" value="{$CONFERENCE.conference_isbn}"></td>
                      </tr>
					   <tr>
                        <td align="right" class="blacktext">Acceptance Rate</td>
                        <td align="center" valign="middle"><span class="blacktext">:</span></td>
                        <td height="30" colspan="3"><input name="conference_acceptance_rate" type="text" class="inputblue" size="47" value="{$CONFERENCE.conference_acceptance_rate}"></td>
                      </tr>
					  <tr>
                        <td align="right" class="blacktext">Quality rating*</td>
                        <td align="center" valign="middle"><span class="blacktext">:</span></td>
                        <td height="30" colspan="3"><select name="conference_quality_rating" class="inputblue">
						<option value="">-Select-</option>
						<option  {if ( isset($CONFERENCE)&& $CONFERENCE.conference_quality_rating=='1')}selected{/if}>1</option>
						<option {if ( isset($CONFERENCE)&& $CONFERENCE.conference_quality_rating=='2')}selected{/if}>2</option>
						<option {if ( isset($CONFERENCE)&& $CONFERENCE.conference_quality_rating=='3')}selected{/if}>3</option>
						<option {if ( isset($CONFERENCE) && $CONFERENCE.conference_quality_rating=='4')}selected{/if}>4</option>
						<option {if ( isset($CONFERENCE)&& $CONFERENCE.conference_quality_rating=='5')}selected{/if}>5</option>
						</select></td>
                      </tr>
					  <tr>
					 	<td>&nbsp;</td>
					 </tr>
					  <tr > 
					  		<td>&nbsp;</td>
							<td>&nbsp;</td>
						 <td  valign=center><input type=submit value="Submit" class="naBtn">&nbsp;<input type=reset value="Reset" class="naBtn"></td> 
   					 </tr> 
					 <tr>
					 	<td>&nbsp;</td>
					 </tr>
					  </table></td>
                  </tr>
                </table>
				</td>
              </tr>
   </table></td> 
  </tr> 
</table>
</form>			