<table width=80% border=0 align="center" cellpadding=5 cellspacing=1> 
   <tr align="left">
      <td colspan=3 valign=top>&nbsp;</td>
   </tr>	
    <tr>
      <td height="378" colspan=3 valign=middle>
	  <table width="100%"  border="0" bgcolor="#EEEEEE" class="border">	
    <tr> 
      <td height="31" colspan=3><span class="group_style"><strong>Plan Details </strong></span></td> 
    </tr> 
    <tr>
      <td height="35" align="right" valign=middle class="smalltext">Name</td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td class="smalltext">{$PLANDETAILS.plan_name}</td>
    </tr>
    <tr>
      <td height="32" align="right" valign=middle class="smalltext"> Campaign </td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td width="390" class="smalltext">{$PLANDETAILS.camp_name}</td>
	</tr>
    <tr> 
      <td width=335 height="37" align="right" valign=middle class="smalltext">Plan Description</td> 
      <td width=27 align="center" valign=middle class="smalltext">:</td> 
      <td class="smalltext">{$PLANDETAILS.plan_desc}
	  </td> 
    </tr> 
    <tr>
      <td height="30" align="right" valign=middle class="smalltext">Plan Price </td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td valign="top" class="smalltext">{$PLANDETAILS.plan_price}</td>
    </tr>
    <tr>
      <td height="33" align="right" valign=middle class="smalltext">Supported File Types </td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td class="smalltext">{$FILE_TYPE}</td>
    </tr>
    <tr>
      <td height="33" align="right" valign=middle class="smalltext">Duration</td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td class="smalltext">{$PLANDETAILS.duration}{if $PLANDETAILS.duration_type|upper=='D'}&nbsp;Day{else}&nbsp;Month{/if}</td>
    </tr>
    <tr align="center">
      <td height="33" colspan="3" valign=middle class="smalltext">
	   <div  STYLE="width:630px; height:500px; overflow: auto;">{$FILEINFO}</div>
	  </td>
      </tr>	
    <tr align="center"> 
      <td height="56" colspan=3 valign=center class="smalltext">&nbsp;</td> 
    </tr> 
      </table>	  
	  </td>
    </tr> 
</table>
