<script language="javascript">
{literal}
function checkBlank()
{
	if(document.comments.comment.value == ""){
		alert("Please Enter a Note");
		return false;
	}else{
		return true;
	}
	
}

{/literal}
</script>
<style>
{literal}
.accessory td {
	cursor:pointer;
	padding-left:5px;
}
.accessoryHover td {
	cursor:pointer;
	background-color:#aabbdd;
	padding-left:5px;
}
{/literal}
</style>
<table align="center" width="76%" border="0" cellspacing="0" cellpadding="0" class="naBrdr">
 <tr><td align="center">{messageBox}</td></tr>
  <tr><td><table width="98%" align="center"><tr><td nowrap="nowrap" class="naH1">User Questionnaire Reports </td></tr></table></td></tr>
  <tr class=naGrid2><td align="center"></td></tr>
  <tr>
    <td>
	<table border="0" width="100%" cellpadding="5" cellspacing="2">
      {if count($USER_DETAILS) > 0}
      <tr>
        <td height="24" colspan="5" align="center" nowrap="nowrap">
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
               <tr>
                 <td valign="top"  class="naFooter">
				 	<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0">
                       <tr class="tablegreen2"><td height="26" colspan="3"  align="left" class="naGridTitle">{$CUSTOMER_DET.first_name} {$CUSTOMER_DET.last_name}</td>
					   	<td colspan="4"  align="right" class="naGridTitle"></td></tr>
                     </table>
				  </td>
                </tr>
             </table>
			 </td>
        </tr>
        <tr>
          <td  align="center" valign="middle" class="msg">
		  	<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0">
          		<tr  height="25"  class="{cycle values="naGrid1,naGrid2"}">
            		<td height="25" colspan="2"  align="left" valign="middle" class="bodytext">Consultation #&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;<strong>{$USER_DETAILS.0.medical_questionnaire_id}</strong>
					<td class="bodytext"  colspan="3" > </td>
					</td>
         		 </tr>
				
				 <tr height="25"  class="{cycle values="naGrid1,naGrid2"}">
					<td height="25" colspan="2"  align="left" valign="middle" class="bodytext">Customer Name&nbsp;&nbsp;&nbsp;:&nbsp;{$CUSTOMER_DET.first_name} {$CUSTOMER_DET.last_name}</td>
					<td class="bodytext" colspan="3"></td>
				</tr>
				<tr height="25"  class="{cycle values="naGrid1,naGrid2"}">
					<td height="25" colspan="2"  align="left" valign="middle" class="bodytext">Customer Email&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;{$CUSTOMER_DET.email}</td>
					<td class="bodytext" colspan="3"></td>
				</tr>
				
				  <tr><td colspan="5" height="10"></td></tr>
			  	  <tr>
					<td height="25" width="100%"  colspan="5" align="left" valign="top" class="naGridTitle">  
					   Medical Questionnaire
					</td>
				</tr>
				<tr ><td colspan="5" height="10"></td></tr>
				<tr >
					<td colspan="5" >
					<table align="center" width="100%" border="0" cellpadding="3" cellspacing="0">
					{foreach from=$USER_DETAILS item=user_report name=foo}
						
						{if $user_report.status eq 'Unassigned'}
						<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
							<td height="25"  width="174" class="bodytext"><strong>Created Time</strong></td>
							<td height="25"  class="bodytext">{$user_report.assigned_date|date_format:"%m-%d-%Y %H:%M:%S"}</td>
							<td height="25"  width="40" class="bodytext"><strong>Status</strong></td>
							<td height="25"  class="bodytext">{$user_report.status}</td>
							<td height="25"  width="40" class="bodytext"><strong>Refill</strong></td>
							<td height="25"  width="40" class="bodytext">
							{if $user_report.refill mod 3 ==0 AND $user_report.refill>0}Reconsult{/if}
		    				{if $user_report.refill mod 3 ==1}First{/if}
		  					{if $user_report.refill mod 3 ==2}Second{/if}
							{if $user_report.refill==0}None{/if}</td>
						</tr>
						{/if}
						{if $user_report.status eq 'Assigned'}
						<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
							<td height="25" class="bodytext"><strong>Assigned Time</strong></td>
							<td height="25" class="bodytext">{$user_report.assigned_date|date_format:"%m-%d-%Y %H:%M:%S"}</td>
							<td height="25" class="bodytext"><strong>Status</strong></td>
							<td height="25" class="bodytext">{$user_report.status}</td>
							<td height="25" class="bodytext"><strong>Refill</strong></td>
							<td height="25" class="bodytext">{if $user_report.refill mod 3 ==0 AND $user_report.refill>0}Reconsult{/if}
		    				{if $user_report.refill mod 3 ==1}First{/if}
		  					{if $user_report.refill mod 3 ==2}Second{/if}
							{if $user_report.refill==0}None{/if}</td>
						</tr>
						<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
							<td height="25" colspan="6" class="bodytext">
								<table align="left"  cellpadding="0" cellspacing="0">
									<tr>
									<td height="25" align="left" class="bodytext">Assigned To Doctor</td>
									<td align="left" class="bodytext">&nbsp;</td>
									<td align="left"  class="bodytext">{$user_report.doctor_name}</td>
									</tr>
								</table>
							</td>
						</tr>
						{/if}
						{if $user_report.status eq 'Accepted'}
						<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
							<td height="25" class="bodytext"><strong>Accepted Time</strong></td>
							<td height="25" class="bodytext">{$user_report.assigned_date|date_format:"%m-%d-%Y %H:%M:%S"}</td>
							<td height="25" class="bodytext"><strong>Status</strong></td>
							<td height="25" class="bodytext">{$user_report.status}</td>
							<td height="25" class="bodytext"><strong>Refill</strong></td>
							<td height="25" class="bodytext">{if $user_report.refill mod 3 ==0 AND $user_report.refill>0}Reconsult{/if}
		    				{if $user_report.refill mod 3 ==1}First{/if}
		  					{if $user_report.refill mod 3 ==2}Second{/if}
							{if $user_report.refill==0}None{/if}</td>
						</tr>
						<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
							<td height="25" colspan="6" class="bodytext">
								<table align="left"  cellpadding="0" cellspacing="0">
									<tr>
									<td height="25" align="left" class="bodytext">Accepted By Doctor</td>
									<td align="left" class="bodytext">&nbsp;</td>
									<td align="left"  class="bodytext">{$user_report.doctor_name}</td>
									</tr>
								 </table>
								</td>
						</tr>
						<tr class="{cycle values="naGrid1,naGrid2"}">
							<td  width="100%" colspan="6" class="bodytext">
								<table align="center" width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr height="25">
										<td height="25" align="left" class="bodytext"><strong>Medication</strong></td>
										<td align="left" class="bodytext"><strong>Quantity</strong></td>
										<td align="left" class="bodytext"><strong>SIG</strong></td>
										<td align="left" class="bodytext"><strong>Brand/Generic</strong></td>
										<td align="left" class="bodytext"><strong>Refill</strong></td>
									</tr>
							{if $user_report.0.medication eq "" AND $user_report.0.quantity eq "" AND $user_report.0.sig eq "" AND $user_report.0.generic eq "" AND $user_report.0.doct_refill eq ""} 
							{else}	
								<tr height="25" >
									<td height="25" align="left" class="bodytext">{$user_report.0.medication}</td>
									<td align="left" class="bodytext">{$user_report.0.quantity}</td>
									<td align="left" class="bodytext">{$user_report.0.sig}</td>
									<td align="left" class="bodytext">{$user_report.0.generic}</td>
									<td align="left" class="bodytext">{$user_report.0.doct_refill}</td>
								</tr>
							{/if}
							{if $user_report.1.medication eq "" AND $user_report.1.quantity eq "" AND $user_report.1.sig eq "" AND $user_report.1.generic eq "" AND $user_report.1.doct_refill eq ""} 
							{else}	
								<tr height="25">
									<td height="25" align="left" class="bodytext">{$user_report.1.medication}</td>
									<td height="25" align="left" class="bodytext">{$user_report.1.quantity}</td>
									<td height="25" align="left" class="bodytext">{$user_report.1.sig}</td>
									<td height="25" align="left" class="bodytext">{$user_report.1.generic}</td>
									<td height="25" align="left" class="bodytext">{$user_report.1.doct_refill}</td>
								</tr>
							{/if}
							{if $user_report.2.medication eq "" AND $user_report.2.quantity eq "" AND $user_report.2.sig eq "" AND $user_report.2.generic eq "" AND $user_report.2.doct_refill eq ""} 
							{else}	
								<tr height="25">
									<td height="25" align="left" class="bodytext">{$user_report.2.medication}</td>
									<td height="25" align="left" class="bodytext">{$user_report.2.quantity}</td>
									<td height="25" align="left" class="bodytext">{$user_report.2.sig}</td>
									<td height="25" align="left" class="bodytext">{$user_report.2.generic}</td>
									<td height="25" align="left" class="bodytext">{$user_report.2.doct_refill}</td>
								</tr>
							{/if}
							{if $user_report.0.prescription neq ""}
							<tr height="25" >
								<td height="25" colspan="5" class="bodytext" valign="bottom">Doctor's Notes</td>
							</tr>
							<tr height="25" >
							  <td colspan="5" class="gray2" valign="top"> 
								<textarea  rows="2"  readonly cols="120">{$user_report.0.prescription}</textarea></td>
							</tr>
						{/if}
							<tr><td height="5" colspan="4"></td></tr>
								
							</table>
						  </td>
						</tr>
						{/if}
						{if $user_report.status eq 'Declined'}
						<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
							<td height="25" class="bodytext"><strong>Declined Time</strong></td>
							<td height="25" class="bodytext">{$user_report.assigned_date|date_format:"%m-%d-%Y %H:%M:%S"}</td>
							<td height="25" class="bodytext"><strong>Status</strong></td>
							<td height="25" class="bodytext">{$user_report.status}</td>
							<td height="25" class="bodytext"><strong>Refill</strong></td>
							<td height="25" class="bodytext">{if $user_report.refill mod 3 ==0 AND $user_report.refill>0}Reconsult{/if}
		    				{if $user_report.refill mod 3 ==1}First{/if}
		  					{if $user_report.refill mod 3 ==2}Second{/if}
							{if $user_report.refill==0}None{/if}</td>
						</tr>
						<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
							<td height="25" colspan="6" class="bodytext">
								<table align="left"  cellpadding="0" cellspacing="0">
									<tr>
									<td height="25" align="left" class="bodytext">Declined By Doctor</td>
									<td align="left" class="bodytext">&nbsp;</td>
									<td align="left"  class="bodytext">{$user_report.doctor_name}</td>
									</tr>
								</table>
							</td>
						</tr>
						{/if}
						{if $user_report.status eq 'Pharmacy_Assigned'}
						<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
							<td height="25" class="bodytext"><strong>Pharmacy Assigned Time</strong></td>
							<td height="25" class="bodytext">{$user_report.assigned_date|date_format:"%m-%d-%Y %H:%M:%S"}</td>
							<td height="25" class="bodytext"><strong>Status</strong></td>
							<td height="25" class="bodytext">{$user_report.status}</td>
							<td height="25" class="bodytext"><strong>Refill</strong></td>
							<td height="25" class="bodytext">{if $user_report.refill mod 3 ==0 AND $user_report.refill>0}Reconsult{/if}
		    				{if $user_report.refill mod 3 ==1}First{/if}
		  					{if $user_report.refill mod 3 ==2}Second{/if}
							{if $user_report.refill==0}None{/if}</td>
						</tr>
						<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
							<td height="25" colspan="6" class="bodytext">
								<table align="left"  cellpadding="0" cellspacing="0">
									<tr>
									<td height="25" align="left" class="bodytext">Assigned To Pharmacy</td>
									<td align="left" class="bodytext">&nbsp;</td>
									<td align="left"  class="bodytext">{$user_report.pharmacy_name}</td>
									</tr>
								</table>
							</td>
						</tr>
						{/if}
						{if $user_report.status eq 'Shipped'}
						<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
							<td height="25" class="bodytext"><strong>Shipped Time</strong></td>
							<td height="25" class="bodytext">{$user_report.assigned_date|date_format:"%m-%d-%Y %H:%M:%S"}</td>
							<td height="25" class="bodytext"><strong>Status</strong></td>
							<td height="25" class="bodytext">{$user_report.status}</td>
							<td height="25" class="bodytext"><strong>Refill</strong></td>
							<td height="25" class="bodytext">{if $user_report.refill mod 3 ==0 AND $user_report.refill>0}Reconsult{/if}
		    				{if $user_report.refill mod 3 ==1}First{/if}
		  					{if $user_report.refill mod 3 ==2}Second{/if}
							{if $user_report.refill==0}None{/if}</td>
						</tr>
						<tr class="{cycle values="naGrid1,naGrid2"}">
							<td  colspan="6" class="bodytext">
								<table align="left"  cellpadding="0" cellspacing="0">
									<tr height="25">
									<td height="25" align="left" class="bodytext">Shipped By Pharmacy</td>
									<td align="left" class="bodytext">&nbsp;</td>
									<td align="left"  class="bodytext">{$user_report.pharmacy_name}</td>
									</tr>
									<tr height="25">
										<td height="25" align="left" class="bodytext">Tracking Number</td><td align="left" class="bodytext">&nbsp;</td>
										<td align="left"  class="bodytext">{$user_report.tracking_no}</td>
									</tr>
									<tr height="25">
										<td height="25" align="left" class="bodytext">COD Total</td><td align="left" class="bodytext">&nbsp;</td>
										<td align="left"  class="bodytext">{if $user_report.cud_tot}{$user_report.cud_tot}{else}Pending{/if}</td>
									</tr>
								</table>
							</td>
						</tr>
						 <tr  height="25" class="{cycle values="naGrid1,naGrid2"}"><td colspan="6">&nbsp;</td></tr>
						{/if}
						{if $user_report.status eq 'Changed_Request'}
						<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
							<td height="25" class="bodytext"><strong>Request Changed Time</strong></td>
							<td height="25" class="bodytext">{$user_report.assigned_date|date_format:"%m-%d-%Y %H:%M:%S"}</td>
							<td height="25" class="bodytext"><strong>Status</strong></td>
							<td height="25" class="bodytext">{$user_report.status}</td>
							<td height="25" class="bodytext"><strong>Refill</strong></td>
							<td height="25" class="bodytext">{if $user_report.refill mod 3 ==0 AND $user_report.refill>0}Reconsult{/if}
		    				{if $user_report.refill mod 3 ==1}First{/if}
		  					{if $user_report.refill mod 3 ==2}Second{/if}
							{if $user_report.refill==0}None{/if}</td>
						</tr>
						{if $user_report.doctor_name neq ''}
						<tr height="25"class="{cycle values="naGrid1,naGrid2"}" >
							<td height="25" colspan="6" class="bodytext">
								<table align="left"  cellpadding="0" cellspacing="0">
									<tr>
									<td height="25"  align="left" class="bodytext">Assigned To Doctor</td>
									<td align="left" class="bodytext">&nbsp;</td>
									<td align="left"  class="bodytext">{$user_report.doctor_name}</td>
									</tr>
								</table>
							</td>
						</tr>
						{/if}
						{/if}
						{if $user_report.status eq 'Doctor_Assigned_Request'}
						<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
							<td height="25" class="bodytext"><strong>Assigned Time</strong></td>
							<td height="25" class="bodytext">{$user_report.assigned_date|date_format:"%m-%d-%Y %H:%M:%S"}</td>
							<td height="25" class="bodytext"><strong>Status</strong></td>
							<td height="25" class="bodytext">{$user_report.status}</td>
							<td height="25" class="bodytext"><strong>Refill</strong></td>
							<td height="25" class="bodytext">{if $user_report.refill mod 3 ==0 AND $user_report.refill>0}Reconsult{/if}
		    				{if $user_report.refill mod 3 ==1}First{/if}
		  					{if $user_report.refill mod 3 ==2}Second{/if}
							{if $user_report.refill==0}None{/if}</td>
						</tr>
						<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
							<td height="25" colspan="6" class="bodytext">
								<table align="left"  cellpadding="0" cellspacing="0">
									<tr>
									<td  height="25" align="left" class="bodytext">Changed Request Assigned To Doctor</td>
									<td align="left" class="bodytext">&nbsp;</td>
									<td align="left"  class="bodytext">{$user_report.doctor_name}</td>
									</tr>
								</table>
							</td>
						</tr>
						{/if}
						{if $user_report.status eq 'Void'}
						<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
							<td height="25" class="bodytext"><strong>Void Time</strong></td>
							<td height="25" class="bodytext">{$user_report.assigned_date|date_format:"%m-%d-%Y %H:%M:%S"}</td>
							<td height="25" class="bodytext"><strong>Status</strong></td>
							<td height="25" class="bodytext">{$user_report.status}</td>
							<td height="25" class="bodytext"><strong>Refill</strong></td>
							<td height="25" class="bodytext">{if $user_report.refill mod 3 ==0 AND $user_report.refill>0}Reconsult{/if}
		    				{if $user_report.refill mod 3 ==1}First{/if}
		  					{if $user_report.refill mod 3 ==2}Second{/if}
							{if $user_report.refill==0}None{/if}</td>
						</tr>
						{/if}
					{/foreach}
					</table>
					</td>
				</tr>
				<!-- ============================Comments Start======================================= -->
				<tr class="{cycle values="naGrid1,naGrid2"}"><td>&nbsp;</td></tr>
				<tr class="{cycle values="naGrid1,naGrid2"}">
	                <td width="100%" colspan="6" class="bodytext" ><strong>Notes ({$COMMENT_COUNT})</strong></td>
                </tr>
				{if count($COMMENT_LIST)>0}
				<tr>
	            	<td  valign="top" width="100%" colspan="6" class="bodytext" ><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
                    {foreach from=$COMMENT_LIST item=comment}
                    	<tr class="{cycle values="naGrid1,naGrid2"}">
							<td width="3%" class="smalltext">&nbsp;</td>
                            <td width="15%"  class="smalltext" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                        <tr>
                                    <td align="left" class="smalltext" valign="top"><strong>{$comment->username}</strong></td>
                                  </tr>
                                </table></td>
                                 <td width="82%" class="smalltext" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                   <tr>
                                     <td align="right" class="smalltext"><strong>Posted on :</strong>{$comment->postdate|date_format:" %b %e, %Y at %l:%M %p"}</td>
                                     <td>&nbsp;</td>
                                   </tr>
                                   <tr>
                                     <td align="left" class="smalltext">&nbsp;</td>
                                     <td>&nbsp;</td>
                                   </tr>
                                   <tr>
                                     <td width="93%" align="left" class="smalltext"><div style="width:300px;overflow:auto; border:hidden">{$comment->comment}</div></td>
                                     <td width="7%">&nbsp;</td>
                                   </tr>
                                 </table></td>
                              </tr>
							  <tr >
							  <td colspan="3">&nbsp;</td>
							  </tr>
							  {/foreach}
                            </table></td>
                          </tr>
                          {/if}						
                        </table></td>
                      </tr>
					  <form name="comments" action="" method="post" onSubmit="return checkBlank()">
                      <tr>
                        <td><table width="73%" height="162"  border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="58%" height="18" class="blackboldtext">Add New Note:</td>
                            <td width="42%">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="85" colspan="2"><textarea name="comment" cols="45" rows="5" class="input" id="comment"></textarea></td>
                            </tr>
                          <tr>
                            <td height="10">&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="22" valign="top">
								<input type="hidden" name="type" value="Medical Questionnaire">
								<input type="submit" class="naBtn" value="Post Notes" style="width:120px" />
							</td>
                            <td>&nbsp;</td>
                          </tr>
                        </table></td>
                      </tr>
					  </form>
				<!-- ============================Comments End======================================== -->
				
				<tr>
					<td colspan="5" height="5"></td>
				</tr>
				<tr> 
					<td  valign="bottom" valign="top" align="center" colspan="5">
						<table align="center" cellpadding="0" border="0" cellspacing="0">
							<tr>
								<td align="left">
									<input type="button" name="Button" value="Back"  class="naBtn" onClick="javascript: history.go(-1)" style="width:75">
								</td> 
							</tr>
						</table>
					
					</td>
				</tr>
			</table>
		</td>
	</tr>
	  {else}
	  <tr class="naGrid2">
		<td colspan="5" class="naError" align="center" height="30">No Records</td>
	  </tr>
	  {/if}
	  	</td>
  	</tr>
	</table>
   </td>
  </tr>
</table>
