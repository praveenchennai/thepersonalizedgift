<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/includes/datepicker/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript"  src="{$smarty.const.SITE_URL}/scripts/validator.js"></SCRIPT>
{literal}
<script language="javascript">
function showBox(val)
{
  if(val==3){
  	document.getElementById('custom_div').style.display='block';
  }
  else if(val=2 || val==1){
  	document.getElementById('custom_div').style.display='none';
  }
}
</script>
{/literal}
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table width="100%"  border="0">
  <tr>
    <td valign="top">
<table width=80% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
<form action="" method="post" enctype="multipart/form-data" name="frm">
    <tr align="left">
      <td colspan=3 valign=top><table width="400%" align="center" border="0">
        <tr>
          <td width="23%" nowrap class="naH1">{$smarty.request.sId} </td>
          <td width="77%" align="right" nowrap class="titleLink">&nbsp;</td>
        </tr>
      </table></td>
    </tr>
	{if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3>
		<div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>      </td>
    </tr>
    {/if}
	<tr valign="middle" class=naGrid2>
      <!--<td height="40" colspan=3 ><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="3%" valign="top" colspan="2" class="naGridTitle" height="25" nowrap="nowrap"><strong>Please read the following carefully.</strong></td>
          </tr>
        </table></td>-->
		<td colspan=3 class="naGridTitle" height="25" nowrap="nowrap"><strong>Google Analytics Details</strong></td>
    </tr>
	<tr valign="middle" class=naGrid2>
		<td colspan=3  height="35" nowrap="nowrap">You can use Google Analytics to determine which online marketing efforts are effective and how your visitors interact with your webstore.
		<br /><div style="padding-top:5px" >Sign up is easy - and it's free! <a href="http://www.google.com/analytics/sign_up.html" target="_blank">Sign Up Now</a></div> </td>
    </tr>
	<tr valign="middle" class=naGrid1>
		<td colspan=3  height="35" nowrap="nowrap" align="justify"><div><strong>Important Notice:</strong> 
		You must create a Google Analytics account before you can utilize this feature. Google will provide you with <br />
		<div style="padding-top:5px" > the analytics code to paste into the input field below.</div> 
		 </div></td>
    </tr>
	
	<tr valign="middle" class=naGrid2>
		<td colspan=3  height="35" nowrap="nowrap" align="justify"><div>If you encounter problems with your Google analytics code you can verify your setup: <a href="#"  onclick="window.open('http://www.google.com/support/analytics/bin/answer.py?hl=en_US&answer=55480&utm_id=ad',null,
    'height=700,width=950,status=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,left=300,top=100')">Verify your setup</a></div></td>
    </tr>
	
	
	<tr valign="middle" class=naGrid1>
		<td colspan=3  height="35" nowrap="nowrap" align="justify"><strong>Enter your  google analytics code here:</strong></td></tr>
	
	<tr valign="middle" class=naGrid1>
      <td height="40" colspan=3 ><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="3%" valign="top"></td>
            <td width="97%"><textarea name="analytics_code" id="analytics_code" style="width:620px; height:100px">{$FORM_VALUES}</textarea></div></td>
</td>
          </tr>
        </table>        </td>
    </tr>
	
	<tr class=naGrid2>
		<td colspan="2">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>

    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center>
	  <input type="hidden" name="id"   value="{$ANALYTICS_DET.id}" />
	       <input type=submit  value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div>	 </td> 
    </tr> 
	<tr><td colspan=3 valign=center>&nbsp;</td></tr> 
  </form> 
</table>
</td>
  </tr>
</table>