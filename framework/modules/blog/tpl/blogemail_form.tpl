<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="scripts/validator.js"></script>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
		var fields		=	new Array('rec_email','from_email','subject','message');
		var msgs		=	new Array('Recipient email','From email','Subject','Message');
		var emails		=	new Array('rec_email','from_email');
		var email_msgs	=	new Array('Invalid Recipient Email','Invalid From Email');
</script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
 <tr>
	<td width="10%" height="39" class="naH1">&nbsp;</td>
    <td width="45%" class="naH1">Send Mail </td>
    <td width="35%" align="right" class="naH1"><a href="{makeLink mod=blog pg=searchBlog}act=list{/makeLink}" class="linkOneActive"><img src="{$GLOBAL.mod_url}/images/search.jpg" border="0"></a></td>
    <td width="10%" class="naH1">&nbsp;</td>
 </tr>  
  <tr>
    <td colspan="4" align="center">
      <table width="80%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="80%" height="169" align="left" valign="top" bgcolor="#EEEEEE">
          	<table width=100% border=0 align="center" cellpadding="0" cellspacing="0" class="border">
              <tr>
                <td valign=top colspan=3 ></td>
              </tr>
              	<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;" onSubmit="return chk(this)">
				 {if isset($STAT_MESSAGE)}
				  <tr>
					<td height="15" colspan=3 valign=top align="center"><strong>{$STAT_MESSAGE}</strong></td>
				  </tr>
				 {/if}
				  <tr>
					<td valign=top colspan=3>&nbsp;</td>
				  </tr>
				  {if isset($MESSAGE)}
				  <tr>
					<td valign=top colspan=3><div align=center class="element_style"><span class="naError">{$MESSAGE}</span></div></td>
				  </tr>
   				 {/if}
				<tr>
				  <td colspan=3></td>
				</tr>
				<tr>
				  <td width="104"></td>
				  <td colspan="2" ></td>
				</tr>
				<tr>
				  <td colspan=3></td>
				</tr>
				<tr>
				  <td height="19" colspan="2" align="right" valign=middle >&nbsp;</td>
				  <td valign="middle"><span class="blackboldtext">To: (recipient email )</span></td>
				  </tr>
				<tr>
				  <td colspan="2" align="right" valign=middle >&nbsp; </td>
				  <td valign="middle"><input name="rec_email" type="text" class="input" id="rec_email"  size="30" maxlength="25">				 
				  </td>
				  </tr>
				<tr>
				  <td colspan="2" align="right" valign=middle >&nbsp;</td>
				  <td valign="middle">&nbsp;</td>
				  </tr>
				<tr>
				  <td colspan="2" align="right" valign=middle >&nbsp;</td>
				  <td valign="middle"><span class="blackboldtext"> From:(your email - required </a>)</span></td>
				 </tr>
				<tr>
				  <td colspan="2" align="right" valign=middle >&nbsp;</td>
				  <td valign="middle">
					<input name="from_email" type="text" class="input" id="from_email"  size="30" maxlength="25">
					<input name="cur_date" type="hidden" id="cur_date" value="{$CUR_DATE}">
				  </td>
				</tr>
				<tr>
				  <td colspan="2" valign=top>&nbsp;</td>
				  <td>&nbsp;</td>
				  </tr>
				<tr>
				  <td colspan="2" valign=top>&nbsp;</td>
				  <td><span class="blackboldtext">Subject</span></td>
				  </tr>
				<tr>
				  <td colspan="2" valign=top>&nbsp;</td>
				  <td>
				 	 <input name="subject" type="text" class="input" id="subject"  size="30" maxlength="25">
				   </td>
				  </tr>
				<tr>
				  <td colspan="2" valign=top>&nbsp;</td>
				  <td>&nbsp;</td>
				  </tr>
				<tr>
				  <td colspan="2" valign=top>&nbsp;</td>
				  <td><span class="blackboldtext">Body </span></td>
				</tr>
				<tr>
				  <td colspan="2" align="right" valign=middle>&nbsp; </td>
				  <td valign="middle">
					<textarea name="message" cols="40" rows="5" class="input" id="message"></textarea>
					<input type="hidden" name="active" value="y">
					<input type="hidden" name="user_id" value="{$USER_ID}">
					<input type="hidden" name="id" value="{$BLOG_DETAILS.id}">
				  </td>
				</tr>
				<tr>
				  <td colspan="2" valign=top>&nbsp;</td>
				  <td>&nbsp;</td>
				</tr>
				<tr>
				  <td colspan="2" valign=top><div align=right class="element_style"> </div></td>
				  <td>       
				   
				  </td>
				</tr>
				<tr align="center">
				  <td colspan=3 valign=center>
					<input name="submit" type=submit class="btnBg" value="Send">
					&nbsp;
					<input name="reset" type=reset class="btnBg" value="Reset">
				  </td>
				</tr>
				<tr align="center">
				  <td colspan=3 valign=center>&nbsp;</td>
				</tr>
				<tr align="center">
				  <td colspan=3 valign=center>&nbsp;</td>
				</tr>
  			</form>
		 		</table>
		  </td>
		</tr>
	  </table>
	</td>
  </tr>
</table>
