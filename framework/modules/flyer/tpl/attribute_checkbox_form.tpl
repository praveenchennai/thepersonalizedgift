<script language="javascript">
	{literal}
		var CurrentSize = '5';
		
		function AddOption() {
		
			var table = document.getElementById('customFieldsTable');
			var lastRow = table.rows.length;
			var row = table.insertRow(lastRow - 2);
			var cell = row.insertCell(0);
			var cell2 = row.insertCell(1);

			var row2 = table.insertRow(lastRow - 1);
			var cell3 = row2.insertCell(0);
			var cell4 = row2.insertCell(1);
		{/literal}
			CurrentSize++;

			cell.width= "200";
			cell.className = "FieldLabel";

			cell3.width= "200";
			cell3.className = "FieldLabel";
	
			//cell.innerHTML = '&nbsp;&nbsp;\nCheckbox&nbsp;' + CurrentSize + ":&nbsp;";
			//cell2.innerHTML = '<input type=text name=Key[' + CurrentSize + '] class=field250>&nbsp;';

			cell3.innerHTML = '&nbsp;&nbsp;\nCheckbox&nbsp;' + CurrentSize + ':&nbsp;';

			cell4.innerHTML = '<input type="text" name="Value[' + CurrentSize + ']" class="field250">';

		}

	</script>
<form method="post" onsubmit="return CheckForm()">
<table border=0 width=80% cellpadding=5 cellspacing=0 class=naBrDr> 
  	<input type="hidden" name="sId" value="{$smarty.request.sId}">
	<input type="hidden" name="fId" value="{$smarty.request.fId}"> 
   
    <tr align="left">
      <td width="100%" valign=top><table width="100%" align="center">
        <tr>
          <td nowrap class="naH1"><!--Categories-->{$smarty.request.sId}</td>
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=list{/makeLink}&sId={$smarty.request.sId}&fId={$smarty.request.fId}&mId={$MID}">{$smarty.request.sId} List</a></td>
        </tr>
      </table></td>
    </tr>
	 <tr class=naGrid2>
      <td valign=top align="left" class="naGridTitle">&nbsp;</td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>
      </td>
    </tr>
    {/if}
    <tr> 
      <td class="naGridTitle"><span class="group_style">{$smarty.request.sId} Items </span></td> 
    </tr>
	  <tr> 
      <td >
	  <!--  feature option value enter starts here -->
	  
	  <table border="0" cellpadding="0" style="border-collapse: collapse" width="100%" height="100%">
	
	<tr>
		<td height="20">&nbsp;</td>
	</tr>
	<tr>
		<td valign="top">

	<table cellspacing="0" cellpadding="3" width="95%" align="center">

		<tr>
			<td>
				
			</td>
		</tr>
		<tr>
			<td>
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel" id="customFieldsTable">
					<tr>
						<td colspan="2" >
							&nbsp;&nbsp;Checkbox  Details
						</td>
					</tr>
						<!-- <tr>
		<td width="200" class="FieldLabel">

			&nbsp;&nbsp;
			Instructions:&nbsp;
		</td>
		<td>
			<input type="text" name="DefaultValue" class="field250" value="[ Select an option ]">&nbsp;<div style="display:none" id="ssgPZaOZzg"></div>
		</td>
	</tr> -->
<tr>
	<td width="200" class="FieldLabel">
		&nbsp;&nbsp;
		Checkbox 1:&nbsp;
	</td>
	<td>
		<input type="text" name="Value[1]" class="field250" value="">&nbsp;<div style="display:none" id="ssSru0aIl3"></div>
	</td>

</tr>
<tr>
	<td width="200" class="FieldLabel">
		&nbsp;&nbsp;
		Checkbox 2:&nbsp;
	</td>
	<td>
		<input type="text" name="Value[2]" class="field250" value="">&nbsp;<div style="display:none" id="ssFEzpN5WT"></div>
	</td>

</tr>
<tr>
	<td width="200" class="FieldLabel">
		&nbsp;&nbsp;
		Checkbox 3:&nbsp;
	</td>
	<td>
		<input type="text" name="Value[3]" class="field250" value="">&nbsp;<div style="display:none" id="ssfItggF9E"></div>
	</td>

</tr>
<tr>
	<td width="200" class="FieldLabel">
		&nbsp;&nbsp;
		Checkbox 4:&nbsp;
	</td>
	<td>
		<input type="text" name="Value[4]" class="field250" value="">&nbsp;<div style="display:none" id="ssCAOgwA0Q"></div>
	</td>

</tr>
<tr>
	<td width="200" class="FieldLabel">
		&nbsp;&nbsp;
		Checkbox 5:&nbsp;
	</td>
	<td>
		<input type="text" name="Value[5]" class="field250" value="">&nbsp;<div style="display:none" id="ssicIoHHTr"></div>
	</td>

</tr>


	

	<tr id="additionalOption">
		<td>&nbsp;</td>
		<td><a href="javascript:AddOption()">Add More</a></td>
	</tr>

					<tr>
						<td>&nbsp;
							
					  </td>
						<td height="35">
											</td>
					</tr>

				</table>
			</td>
		</tr>
	</table>

  </td>
	</tr>
	<tr>

		<td height="20">
		<div align="center">
			<table border="0" cellpadding="0" style="border-collapse: collapse" width="95%" height="35">
				<tr>
					<td class="PageFooter" align="right">&nbsp;
					</td>

				</tr>
			</table>
		</div>
		</td>
	</tr>
</table>

	  
	  
	  <!--  feature option value enter ends here --> 
	  </td> 
    </tr>
		
	<tr class="naGridTitle"> 
      <td valign=center height="20"><div align=center> 
		
		   <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn">
        </div></td> 
		
    </tr>
	<tr><td valign=center>&nbsp;</td></tr>  
</table>
</form>