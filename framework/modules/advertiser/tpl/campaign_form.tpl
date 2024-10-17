<script language="JavaScript"  src="scripts/validator.js"></script>
	<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
		var fields=new Array('camp_name','camp_width','camp_height','camp_limit');
		var msgs=new Array('Campaign Name','Width','Height','Limit');
	</script>
	
<table width=80% border=0 align="center" cellpadding=5 cellspacing=1> 
  <form action="" method="POST" enctype="multipart/form-data" name="admFrm"  onSubmit="return chk()">    
   <tr align="left">
      <td colspan=3 valign=top><table width="100%" align="center" >
        <tr>
          <td nowrap class="naH1">Campaigns</td>
          <td nowrap align="right" class="titleLink"><a href="{makeLink mod=banner pg=banner_campaign}act=list{/makeLink}">Campaign List</a></td>
        </tr>
      </table>
	</td>
    </tr>
	
    <tr>
      <td height="378" colspan=3 valign=top>
	  <table width="100%"  border="0" bgcolor="#EEEEEE" class="border">
	  {if isset($MESSAGE)}
        <tr>
    	<td colspan=3 valign=top>
		<div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3><span class="group_style"><strong>Campaign Details </strong></span></td> 
    </tr> 
    <tr>
      <td align="right" valign=middle class="smalltext"> Campaign Name </td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td width="456" class="smalltext"><input name="camp_name" type="text" class="formText" id="camp_name" value="{$CAMPAIGN.camp_name}" size="30" maxlength="25"> 
	</td>
    </tr>
    <tr> 
      <td width=300 align="right" valign=middle class="smalltext">Campaign Width </td> 
      <td width=9 align="center" valign=middle class="smalltext">:</td> 
      <td class="smalltext"><input name="camp_width" type="text" class="formText" id="camp_width" value="{$CAMPAIGN.camp_width}" size="2" maxlength="4"> </td> 
    </tr> 
    <tr>
      <td align="right" valign=middle class="smalltext">Campaign Height </td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td class="smalltext"><input name="camp_height" type="text" class="formText" id="camp_height" value="{$CAMPAIGN.camp_height}" size="2" maxlength="4"></td>
    </tr>
    <tr>
      <td align="right" valign=middle class="smalltext">Campaign Limit </td>
      <td align="center" valign=middle class="smalltext">:</td>
      <td class="smalltext"><input name="camp_limit" type="text" class="formText" id="camp_limit" value="{$CAMPAIGN.camp_limit}" size="2" maxlength="4"></td>
    </tr>
    <tr align="center"> 
      <td height="56" colspan=3 valign=center class="smalltext"> 
          <input type=submit value="Submit"  class="input">&nbsp; 
          <input type=reset value="Reset"  class="input">	  
	 </td> 
    </tr> 
      </table>	  
	  </td>
    </tr>
  </form> 
</table>
