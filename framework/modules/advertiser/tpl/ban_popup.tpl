<table width=80% border=0 align="center" cellpadding=5 cellspacing=1> 
   <tr align="left">
      <td colspan=3 valign=top>&nbsp;</td>
   </tr>	
    <tr>
      <td height="378" colspan=3 valign=middle>
	  <table width="100%"  border="0" bgcolor="#EEEEEE" class="border">	
	  {if ($CHKURL !='Y')}
    <tr> 
      <td height="31" colspan=3><span class="group_style"><strong>Ads Details </strong></span></td> 
    </tr> 
    <tr>
      <td height="35" align="right" valign=middle class="smalltext">Title</td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td class="smalltext">{$BANNER_DETAILS.company_name}</td>
    </tr>
    <tr>
      <td height="32" align="right" valign=middle class="smalltext">Campaign</td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td width="390" class="smalltext">{$BANNER_DETAILS.camp_name}</td>
	</tr>
    <tr> 
      <td width=335 height="37" align="right" valign=middle class="smalltext">Plan</td> 
      <td width=27 align="center" valign=middle class="smalltext">:</td> 
      <td class="smalltext">{$BANNER_DETAILS.plan_name}
	  </td> 
    </tr> 
    <tr>
      <td height="30" align="right" valign=middle class="smalltext">File Type </td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td valign="top" class="smalltext">{$BANNER_DETAILS.file_type}</td>
    </tr>
    <tr>
      <td height="33" align="right" valign=middle class="smalltext">Start Date </td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td class="smalltext">{$BANNER_DETAILS.start_date}</td>
    </tr>
    <tr>
      <td height="33" align="right" valign=middle class="smalltext">Expiry Date </td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td class="smalltext">{$BANNER_DETAILS.end_date}</td>
    </tr>
	{/if}
    <tr align="center">
      <td height="33" colspan="3" valign=middle class="smalltext" align="center">
	   <div >{$FILE_INFO}</div>
	  </td>
      </tr>	
    <tr align="center"> 
      <td height="56" colspan=3 valign=center class="smalltext">&nbsp;</td> 
    </tr> 
      </table>	  
	  </td>
    </tr> 
</table>
