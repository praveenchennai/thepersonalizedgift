<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></SCRIPT>
<script language="javascript">
{literal}
function subfrm()
{
{/literal}
 document.frm.action='{makeLink mod=order pg=order}act=manual_order_form_online{/makeLink}';
 document.frm.submit();
{literal}

}
function selectOne()
{
	if(document.assignTo.assign_to){
		if(document.assignTo.assign_to.value=="")
		{
			alert("Please Select a Doctor");
			return false;
		}
	}
	if(document.assignTo.assign_to_pharma){
		if(document.assignTo.assign_to_pharma.value==0)
		{
			alert("Please Select a Pharmacist");
			return false;
		}
	}
	return true;
}
function UploadOne()
{
	if(document.upload.image_extension){
		if(document.upload.image_extension.value=="")
		{
			alert("Please Specify PDF File");
			return false;
		}
	}
	return true;
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
  <tr><td><table width="98%" align="center"><tr><td nowrap="nowrap" class="naH1">User Details </td></tr></table></td></tr>
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
				 	<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1">
                       <tr class="tablegreen2"><td height="26" colspan="3"  align="left" class="naGridTitle">{$USER_DETAILS.first_name} {$USER_DETAILS.last_name}</td>
					   	<td colspan="4"  align="right" class="naGridTitle"></td></tr>
                     </table>
				  </td>
                </tr>
             </table>
			 </td>
        </tr>
        <tr>
          <td   align="center" valign="middle" class="msg">
		  	<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0">
          		<tr>
            		<td width="100%" colspan="5" class="bodytext"  align="left">Consultation # : <strong>{$USER_DETAILS.medical_id}</strong></td>
					</td>
         		 </tr>
				<tr>
       				<td height="30" align="left" colspan="2" valign="top"class="naH1">Customer Information </td>
					<td width="5" bgcolor="#FFFFFF"></td>
					<td  class="naH1" colspan="2" >Customer Shipping Information </td>
				</tr>
				 <tr height="25"  class="{cycle values="naGrid1,naGrid2"}">
					<td height="18" width="25%" align="left" valign="middle" class="bodytext">Customer Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
					<td class="bodytext" width="25%">{$USER_DETAILS.first_name} {$USER_DETAILS.last_name}</td>
					<td width="5" bgcolor="#FFFFFF">&nbsp;</td>
					<td height="18" width="25%" align="left" valign="middle" class="bodytext">Customer Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
					<td class="bodytext" width="25%">{$USER_DETAILS.first_name} {$USER_DETAILS.last_name}</td>
				</tr>
				<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
					<td height="18" align="left" valign="middle" class="bodytext">Street Address&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
					<td class="bodytext">{$USER_DETAILS.address1}</td>
					<td width="5" bgcolor="#FFFFFF">&nbsp;</td>
					<td height="18" align="left" valign="middle" class="bodytext">Street Address&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
					<td class="bodytext">{$USER_DETAILS.shipping_address1}</td>
				</tr>
				<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
					<td height="18" align="left" valign="middle" class="bodytext"></td>
					<td class="bodytext">{$USER_DETAILS.address2}</td>
					<td width="5" bgcolor="#FFFFFF">&nbsp;</td>
					<td height="18" align="left" valign="middle" class="bodytext"></td>
					<td class="bodytext">{$USER_DETAILS.shipping_address2}</td>
				</tr>
			   <tr height="25" class="{cycle values="naGrid1,naGrid2"}">
					<td height="18" class="bodytext">City&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
					<td class="bodytext">{$USER_DETAILS.city}</td>
					<td width="5" bgcolor="#FFFFFF">&nbsp;</td>
					<td height="18" class="bodytext">City&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
					<td class="bodytext">{$USER_DETAILS.shipping_city}</td>
				</tr>
				<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
					<td height="18" class="bodytext">State&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
					<td class="bodytext">{$USER_DETAILS.state}</td>
					<td width="5" bgcolor="#FFFFFF">&nbsp;</td>
					<td height="18" class="bodytext">State&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
					<td class="bodytext">{$USER_DETAILS.shipping_state}</td>
				  </tr>
				  <tr height="25" class="{cycle values="naGrid1,naGrid2"}">
					<td height="18" class="bodytext">Zipcode&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
					<td class="bodytext">{$USER_DETAILS.postalcode}</td>
					<td width="5" bgcolor="#FFFFFF">&nbsp;</td>
					<td height="18" class="bodytext">Zipcode&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
					<td class="bodytext">{$USER_DETAILS.shipping_postalcode}</td>
				  </tr>
				  <tr height="25" class="{cycle values="naGrid1,naGrid2"}">
					<td height="18" class="bodytext">Primary Number&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
					<td class="bodytext">{$USER_DETAILS.telephone}</td>
					<td width="5" bgcolor="#FFFFFF">&nbsp;</td>
					<td height="18" class="bodytext">Primary Number&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
					<td class="bodytext">{$USER_DETAILS.telephone}</td>
				  </tr>
				  <tr><td colspan="5" height="10"></td></tr>
			  	  <tr>
					<td height="25" width="100%"  colspan="5" align="left" valign="top" class="naGridTitle">  
					   Medical Questionnaire:
					</td>
				</tr>
				<tr class="{cycle values="naGrid1,naGrid2"}"><td colspan="5" height="10"></td></tr>
				<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
					<td height="18" class="bodytext">Hieight&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
					<td align="left">{$USER_DETAILS.height}</td>
					<td width="5" bgcolor="#FFFFFF">&nbsp;</td>
					<td >Date of Last Exam&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
					<td align="left">{$USER_DETAILS.last_exam_date|date_format:"%m-%d-%Y"}</td>
				</tr>
				<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
					<td>Weight&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
					<td>{$USER_DETAILS.weight}</td>
					<td width="5" bgcolor="#FFFFFF">&nbsp;</td>
					<td>Allergies&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
					<td>{$USER_DETAILS.allergies}</td>
				</tr>
				<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
					<td>Do You Drink Alcohol?&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
					<td>{$USER_DETAILS.alcohol}</td>
					<td width="5" bgcolor="#FFFFFF">&nbsp;</td>
					<td>Do You Smoke? &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
					<td>{$USER_DETAILS.smoke}</td>
				</tr>
				<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
					<td >Recent Surgeries&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
					<td >{$USER_DETAILS.recent_surgeries}</td>
					<td>&nbsp;</td>
					<td class="date2">&nbsp;</td>
					<td class="gray2">&nbsp;</td>
				</tr>
				<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
					<td colspan="5">Chief Complaint&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
				</tr>
				<tr>
					<td colspan="5"><textarea cols="118" rows="2" readonly >{$USER_DETAILS.chief_complaint}</textarea></td>
				</tr>
				<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
					<td colspan="5">Medication(s) Currently Taken :</td>
				</tr>
				<tr>
					<td colspan="5"><textarea cols="118" rows="2" readonly >{$USER_DETAILS.current_medication}</textarea></td>
				</tr>
				<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
					<td colspan="5">Pain Indicator&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
				</tr>
				<tr>
					<td colspan="5"><textarea cols="118" rows="2" readonly >{$USER_DETAILS.pain}</textarea></td>
				</tr>
				<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
					<td colspan="5">FEDEX Shipping Method&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
				</tr>
				<tr>
					<td colspan="5"><textarea cols="118" rows="2" readonly >{$USER_DETAILS.fedex}</textarea></td>
				</tr>
				{if $USER_DETAILS.refill neq "0" || $STAT eq "Accepted" || $STAT eq "Declined"}
				<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
					<td colspan="5">Doctor's Notes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
				</tr>
				<tr>
					<td colspan="5"><textarea cols="118" rows="2" readonly >{$USER_DETAILS.prescription[0]}</textarea></td>
				</tr>
				{/if}
				{if $USER_DETAILS.refill neq "0" || $STAT eq "Accepted"}
				<tr height="25" >
					<td colspan="5" class="date2" valign="bottom">
						<table align="center" width="100%" border="0" cellpadding="0" cellspacing="2">
							<tr class="{cycle values="naGrid1,naGrid2"}">
								<td align="left" class="bodytext"><strong>Medication</strong></td>
								<td align="left" class="bodytext"><strong>Quantity</strong></td>
								<td align="left" class="bodytext"><strong>SIG</strong></td>
								<td align="left" class="bodytext"><strong>Brand/Generic</strong></td>
								<td align="left" class="bodytext"><strong>Refill</strong></td>
							</tr>
							{section name=customer loop=3 start=0 step=1}
							{if $smarty.section.customer.index eq "0"}
							<tr class="{cycle values="naGrid1,naGrid2"}">
								<td align="left">
									<select name="medication[]" class="input" >
        							<option value="">--Select--</option>
		 							{html_options values=$MEDICATION_LIST.value output=$MEDICATION_LIST.value selected=$USER_DETAILS.medication[$smarty.section.customer.index]}
  									</select>
								</td>
								<td align="left">
									<select name="quantity[]" class="input">
        							<option value="">--Select--</option>
		 							{html_options values=$QUANTITY_LIST.value output=$QUANTITY_LIST.value selected=$USER_DETAILS.quantity[$smarty.section.customer.index]}
  									</select>
								</td>
								<td align="left">
									<select name="sig[]" class="input">
        							<option value="">--Select--</option>
		 							{html_options values=$SIG_LIST.value output=$SIG_LIST.value selected=$USER_DETAILS.sig[$smarty.section.customer.index]}
  									</select>
								</td>
								<td align="left">
									<select name="generic[]" class="input" >
        							<option value="">--Select--</option>
		 							{html_options values=$GENERIC_LIST.value output=$GENERIC_LIST.value selected=$USER_DETAILS.generic[$smarty.section.customer.index]}
  									</select>
								</td>
								<td align="left">
									<select name="doct_refill[]" class="input" >
        							<option value="">--Select--</option>
		 							{html_options values=$REFILL_LIST.value output=$REFILL_LIST.value selected=$USER_DETAILS.doct_refill[$smarty.section.customer.index]}
  									</select>
								</td>
							</tr>
							{else}
							{if ($USER_DETAILS.medication[$smarty.section.customer.index] neq "") OR ($USER_DETAILS.quantity[$smarty.section.customer.index] neq "") OR ($USER_DETAILS.sig[$smarty.section.customer.index] neq "") OR ($USER_DETAILS.generic[$smarty.section.customer.index] neq "") OR ($USER_DETAILS.doct_refill[$smarty.section.customer.index] neq "")}
							<tr class="{cycle values="naGrid1,naGrid2"}">
								<td align="left">
									<select name="medication[]" class="input" >
        							<option value="">--Select--</option>
		 							{html_options values=$MEDICATION_LIST.value output=$MEDICATION_LIST.value selected=$USER_DETAILS.medication[$smarty.section.customer.index]}
  									</select>
								</td>
								<td align="left">
									<select name="quantity[]" class="input">
        							<option value="">--Select--</option>
		 							{html_options values=$QUANTITY_LIST.value output=$QUANTITY_LIST.value selected=$USER_DETAILS.quantity[$smarty.section.customer.index]}
  									</select>
								</td>
								<td align="left">
									<select name="sig[]" class="input">
        							<option value="">--Select--</option>
		 							{html_options values=$SIG_LIST.value output=$SIG_LIST.value selected=$USER_DETAILS.sig[$smarty.section.customer.index]}
  									</select>
								</td>
								<td align="left">
									<select name="generic[]" class="input" >
        							<option value="">--Select--</option>
		 							{html_options values=$GENERIC_LIST.value output=$GENERIC_LIST.value selected=$USER_DETAILS.generic[$smarty.section.customer.index]}
  									</select>
								</td>
								<td align="left">
									<select name="doct_refill[]" class="input" >
        							<option value="">--Select--</option>
		 							{html_options values=$REFILL_LIST.value output=$REFILL_LIST.value selected=$USER_DETAILS.doct_refill[$smarty.section.customer.index]}
  									</select>
								</td>
							</tr>
							<tr><td height="5" colspan="4"></td></tr>
							{/if}
							{/if}
							{/section}
						</table>
				</td>
				</tr>
				{/if}
				{if $STAT eq "Shipped"}
				<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
					<td colspan="1">Tracking Number&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
					<td colspan="4">{$USER_DETAILS.tracking_no}</td>
				</tr>
				{/if}
				 {if count($PDF_ATTACH) > 0}
				<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
					<td colspan="5" align="left"><strong>Attached PDF Files</strong></td>
				</tr>
				<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
					<td colspan="5" align="left">
					<table align="" border="0" cellpadding="2" cellspacing="4" >
					
				{foreach from=$PDF_ATTACH item=file_att name=foo}
				{if $smarty.foreach.foo.index is div by 3}
					</tr><tr> 
				{/if} 
					<td colspan="1" width="10" align="left"><a class="linkOneActive" target="_blank" href="{$GLOBAL.modbase_url}healthcare/uploaded_files/{$file_att->id}.{$file_att->image_extension}"><img  src="{$GLOBAL.tpl_url}/images/pdf.gif" border="0"></a></td> 
					<td colspan="1" align="left">{$file_att->subject}</td> 
					<td  width="10" align="left"><a href="{makeLink mod=healthcare pg=health}act=view&id={$smarty.request.id}&remove_id={$file_att->id}&assign_id={$smarty.request.assign_id}&refill={$smarty.request.refill}&doct_id={$smarty.request.doct_id}&pharma_id={$smarty.request.pharma_id}&stat={$smarty.request.stat}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&pageNo={$smarty.request.pageNo}{/makeLink}" onClick="return confirm('Do You Want To Remove This');"><img  src="{$GLOBAL.tpl_url}/images/grid/icon.delete.gif" border="0"></a></td>
				
				{/foreach}
				</table>
					</td>
				</tr>
				{/if}
				{if $STAT eq "Unassigned" OR $STAT eq "Assigned"}
			<form action="" method="POST" enctype="multipart/form-data" name="upload" id="upload" style="margin: 0px;" onSubmit="return UploadOne();"> 
				<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
					<td colspan="1" nowrap>Attach PDF File&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
					<td colspan="4"><input name="image_extension" type="file" id="image_extension" tabindex="11" size="50" ></td>
				</tr>
				<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
					<td colspan="1" nowrap>PDF File Subject&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
					<td colspan="3"><input name="subject" type="text" id="subject"  size="43" ></td>
					<td align="left"><input name="btn_upload" type="submit"  id="btn_upload"  value="Upload" class="naBtn" style="width:75"></td>
				</tr>
			</form>
				{/if}
				<tr>
					<td colspan="5" height="5"></td>
				</tr>
				{if $DOCTOR_NAME neq "" || $PHARMACY_NAME neq "" || $USER_DETAILS.refill neq "0"}
				<tr>
					<td height="25" width="100%"  colspan="5"  valign="top" class="naGridTitle"> 
						<table align="center" cellpadding="0" cellspacing="0" width="100%" >
							<tr>
								<td align="left" width="40%"> {if $DOCTOR_NAME neq ""}{if $USER_DETAILS.refill mod 3 ==0 AND $USER_DETAILS.refill>0}{else}Assigned To Doctor : {$DOCTOR_NAME}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{/if}{/if}</td>
								<td align="left" width="40%">{if $PHARMACY_NAME neq ""}Assigned To Pharmacist : {$PHARMACY_NAME}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{/if}</td>
								<td align="right" width="20%">
									{if $USER_DETAILS.refill mod 3 ==0 AND $USER_DETAILS.refill>0}Reconsult{/if}
									{if $USER_DETAILS.refill mod 3 ==1}First Refill{/if}
		  							{if $USER_DETAILS.refill mod 3 ==2}Second Refill{/if}
								</td>
							</tr>
						</table>
					  <!--  {if $STAT eq "Accepted" || $STAT eq "Declined" || $USER_DETAILS.refill mod 3 ==1 || $USER_DETAILS.refill mod 3 ==2}Assigned To Doctor : {$DOCTOR_NAME}{/if} -->
					</td>
				</tr>
				{/if}
				
				
				<tr>
					<td colspan="5" height="5"></td>
				</tr>
		<form action="" method="post"  name="assignTo" id="assignTo" onSubmit="return selectOne()"> 
				<tr>
				
				{if $STAT eq "Assigned" OR $STAT eq "Doctor_Assigned_Request" OR $STAT eq "Changed_Request" OR $STAT eq "Declined"}
					<td  align="right" colspan="2" >Re-Assign a Doctor for this request&nbsp;</td>
					<td width="5" bgcolor="#FFFFFF">&nbsp;</td>
					<td align="left" colspan="2">
					<select name="assign_to" >
					<option value="">-- Select a Doctor --</option>
					{html_options values=$DOCTOR_LIST.id output=$DOCTOR_LIST.username selected=`$smarty.request.doct_id`}
					</select>
					</td>
				{elseif $STAT eq "Accepted"}
					<td  align="right" colspan="2" >Assign a Pharmacist for this Prescription&nbsp;</td>
					<td width="5" bgcolor="#FFFFFF">&nbsp;<input type="hidden" name="assign_to" value="{$smarty.request.doct_id}"></td>
					<td align="left" colspan="2">
					<select name="assign_to_pharma" >
					<option value="0">-- Select a Pharmacist --</option>
					{html_options values=$PHARMACY_LIST.id output=$PHARMACY_LIST.username selected=`$smarty.request.pharma_id`}
					</select>
					</td>
				{elseif $STAT eq "Pharmacy_Assigned"}
					<td  align="right" colspan="2" >Re-Assign a Pharmacist for this Prescription&nbsp;</td>
					<td width="5" bgcolor="#FFFFFF">&nbsp;<input type="hidden" name="assign_to" value="{$smarty.request.doct_id}"></td>
					<td align="left" colspan="2">
					<select name="assign_to_pharma" >
					<option value="0">-- Select a Pharmacist --</option>
					{html_options values=$PHARMACY_LIST.id output=$PHARMACY_LIST.username selected=`$smarty.request.pharma_id`}
					</select>
					</td>
				{elseif $STAT eq "Unassigned"}
					{if $USER_DETAILS.refill mod 3 ==1 || $USER_DETAILS.refill mod 3 ==2}
					<td  align="right" colspan="2" >Assign a Pharmacist for this Prescription&nbsp;</td>
					<td width="5" bgcolor="#FFFFFF">&nbsp;<input type="hidden" name="refil_mod" value="{$USER_DETAILS.refill}"><input type="hidden" name="assign_to" value="{$smarty.request.doct_id}"></td>
					<td align="left" colspan="2">
					<select name="assign_to_pharma" >
					<option value="0">-- Select a Pharmacist --</option>
					{html_options values=$PHARMACY_LIST.id output=$PHARMACY_LIST.username selected=`$smarty.request.pharma_id`}
					</select>
					</td>
				   {else}
		  			
					<td  align="right" colspan="2" >Assign a Doctor for this request&nbsp;</td>
					<td width="5" bgcolor="#FFFFFF">&nbsp;</td>
					<td align="left" colspan="2">
					<select name="assign_to" >
					<option value="">-- Select a Doctor --</option>
					{html_options values=$DOCTOR_LIST.id output=$DOCTOR_LIST.username selected=`$smarty.request.doct_id`}
					</select>
					</td>
					{/if}
				{/if}
				
				</tr>
				{if $STAT eq "Shipped"}
				<tr height="25" class="{cycle values="naGrid1,naGrid2"}">
					<td colspan="1">COD Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
					<td colspan="4"><input type="text" name="cud_tot" value="{$USER_DETAILS.cud_tot}"></td>
				</tr>
				{/if}
				<tr>
					<td colspan="4"><input type="hidden" name="medical_id" value="{$USER_DETAILS.medical_id}"><input type="hidden" name="assnd" value="{$ASSND}"><input type="hidden" name="refill" value="{$USER_DETAILS.refill}">&nbsp;</td>
				</tr>
			
				<tr> 
					<td  valign="bottom" valign="top" align="center" colspan="5">
						<table align="center" cellpadding="0" border="0" cellspacing="0">
							<tr>
								<td>
									{if $STAT neq "Shipped" AND $STAT neq "Void"}
									<input name="btn_save" type="submit"  id="btn_save"  value="Submit" class="naBtn" style="width:75">
									{/if}
									{if $STAT eq "Shipped" AND $STAT neq "Void"}
									<input name="btn_cud" type="submit"  id="btn_cud"  value="Submit" class="naBtn" style="width:75">
									{/if}
								</td>
								<td width="20" bgcolor="#FFFFFF">&nbsp;</td>
								<td align="left">
									<input type="button" name="Button" value="Back"  class="naBtn" onClick="javascript: history.go(-1)" style="width:75">
								</td> 
								
							</form>
							{if $STAT neq "Void"}
							<td width="20" bgcolor="#FFFFFF">
							<form action="" method="post"  name="voidTo" id="voidTo" style="margin:0px"> 
								&nbsp;<input type="hidden" name="assign_id" value="{$smarty.request.assign_id}"></td>
								<td align="left" valign="middle">
									<input name="btn_void" type="submit"  id="btn_void"  value="Void" class="naBtn" style="width:75">
							</form>
								</td> 
							{/if}
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
