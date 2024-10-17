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
    <table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr>
    <tr>
        <td></td>
      </tr> 
      <tr> 
        <td><table width="98%" align="center"> 
            <tr> 
              <td nowrap class="naH1">Add Institution</td> 
              <td nowrap align="right" class="titleLink"><a href="{makeLink mod=album pg=album_admin}act=list_institution&link=Y{/makeLink}">List Institution</a></td> 
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
                          <td width="31%" align="right"><span class="blacktext">Name*</span></td>
                          <td width="5%" align="center" valign="middle"><span class="blacktext">:</span></td>
                          <td width="64%" height="30" colspan="3"><input name="institution_name" type="text" class="inputblue" size="47" value="{$OPTION.institution_name}"></td>
                        </tr>
						<tr>
							<td width="31%" align="right">Tags</td>
							<td width="5%" align="center" valign="middle"><span class="blacktext">:</span></td>
							<td width="64%" height="30" colspan="3"><textarea name="institution_name_tag" class="inputblue" style="width:295px; height:100px" >{$OPTION.institution_name_tag}</textarea></td>
					    </tr>
                        <tr>
                          <td align="right"><b>Address</b></td>
                          <td align="center" valign="middle">&nbsp;</td>
                          <td height="30" colspan="3">&nbsp;</td>
                        </tr>
                        <tr>
                          <td align="right"><span class="blacktext">Street and number</span></td>
                          <td align="center" valign="middle"><span class="blacktext">:</span></td>
                          <td height="30" colspan="3"><input name="institution_street" id="acronym" type="text" class="inputblue" size="47" value="{$OPTION.institution_street}"></td>
                        </tr>
                        <tr>
                          <td align="right" class="blacktext">City *</td>
                          <td align="center" valign="middle"><span class="blacktext">:</span></td>
                          <td height="30" colspan="3"><input name="institution_city" id="acronym" type="text" class="inputblue" size="47" value="{$OPTION.institution_city}"></td>
                        </tr>
						<tr>
							<td width="31%" align="right">Tags</td>
							<td width="5%" align="center" valign="middle"><span class="blacktext">:</span></td>
							<td width="64%" height="30" colspan="3"><textarea name="institution_city_tag" class="inputblue" style="width:295px; height:100px" >{$OPTION.institution_city_tag}</textarea></td>
					    </tr>
				         <tr>
                           <td align="right" class="blacktext">Zip code </td>
                           <td align="center" valign="middle"><span class="blacktext">:</span></td>
                           <td height="30" colspan="3"><input name="institution_zipcode" type="text" class="inputblue" size="47" value="{$OPTION.institution_zipcode}"></td>
                        </tr>
				        <tr>
                          <td align="right" class="blacktext">State*</td>
                          <td align="center" valign="middle"><span class="blacktext">:</span></td>
                          <td height="30" colspan="3"><input name="institution_state" type="text" class="inputblue" size="47" value="{$OPTION.institution_state}"></td>
                        </tr>
						<tr>
							<td width="31%" align="right">Tags</td>
							<td width="5%" align="center" valign="middle"><span class="blacktext">:</span></td>
							<td width="64%" height="30" colspan="3"><textarea name="institution_state_tag" class="inputblue" style="width:295px; height:100px" >{$OPTION.institution_state_tag}</textarea></td>
					    </tr>
				        <tr>
				          <td align="right" class="blacktext">Country*</td>
				          <td align="center" valign="middle">:</td>
				          <td height="30" colspan="3"><select name="institution_country" class="inputblue">
                              <option value="">---Select Country--</option>
							  	{if isset($OPTION.institution_country)}
							   	{html_options values=$COUNTRY_LIST.country_id output=$COUNTRY_LIST.country_name selected=$OPTION.institution_country}
								{else}
							   	{html_options values=$COUNTRY_LIST.country_id output=$COUNTRY_LIST.country_name selected='840'}
								{/if}
                            </select></td>
			            </tr>
				        <tr>
                          <td align="right" class="blacktext">Email for contact </td>
                          <td align="center" valign="middle"><span class="blacktext">:</span></td>
                          <td height="30" colspan="3"><input name="institution_email" id="journal_isbn" type="text" class="inputblue" size="47" value="{$OPTION.institution_email}"></td>
                        </tr>
				        <tr>
				          <td align="right" class="blacktext">Type</td>
				          <td align="center" valign="middle">:</td>
				          <td height="30" colspan="3"><p>
				            <input name="institution_type" type="radio" value="academia" {if ($OPTION.institution_type eq 'academia')}checked{/if}>&nbsp;Academia&nbsp;&nbsp;<input name="institution_type" type="radio" value="industry" {if ($OPTION.institution_type eq 'industry')}checked{/if}>&nbsp;Industry			              
	                      </p></td>
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

