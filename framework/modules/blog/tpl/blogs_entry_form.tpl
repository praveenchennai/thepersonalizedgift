<table width="100%"  border="0" cellspacing="0" cellpadding="0">
 <tr>
	<td width="5%" height="39" class="naH1">&nbsp;</td>
    <td width="95%" class="naH1">Blog Details</td>
 </tr>  
  <tr>
   <td  align="center">
    <td  align="left">
      <table width="95%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="95%" height="169" align="left" valign="top" bgcolor="#EEEEEE">
          	<form action="" method="POST" enctype="multipart/form-data" name="admFrm" style="margin: 0px;">
			<table width=100% border=0 align="center" cellpadding="0" cellspacing="0" class="border">
				  <tr>
					<td valign=top colspan=3 ></td>
				  </tr>
              	  <tr>
					<td height="15" colspan=3 valign=top>&nbsp;</td>
				  </tr>
				  <tr>
					<td valign=top colspan=3>&nbsp;</td>
				  </tr>
				  {if isset($MESSAGE)}
				  <tr>
					<td valign=top colspan=3 align="center"><span class="naError">{$MESSAGE}</span></td>
				  </tr>
   				 {/if}
				<tr>
				  <td colspan=3></td>
				</tr>
				<tr>
				  <td width="42"></td>
				  <td colspan="2" ></td>
				</tr>
				<tr>
				  <td colspan=3></td>
				</tr>
				<tr>
				  <td colspan="2" align="right" valign=middle  width="25">&nbsp;</td>
				  <td  valign="middle"><span class="blackboldtext">Title</span>&nbsp;</td>
				  </tr>
				<tr>
				  <td colspan="2" align="right" valign=middle >&nbsp;</td>
				  <td valign="middle">
					<input name="post_title" type="text" class="input" id="post_title"  size="30" maxlength="255" value="{$BLOG_ENTRY_DETAILS.post_title}">
					<input name="create_date" type="hidden" id="create_date" value="{$CUR_DATE}">
				  </td>
				</tr>
				<tr>
				  <td colspan="2" valign=top>&nbsp;</td>
				  <td><span class="blackboldtext">Body </span></td>
				</tr>
				<tr>
				  <td colspan="2" align="right" valign=middle>&nbsp; </td>
				  <td valign="middle">
                    <table width="570" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>
							<textarea name="post_description" cols="40" rows="30" class="input" id="post_description">{$BLOG_ENTRY_DETAILS.post_description}</textarea>
							<input type="hidden" name="id" value="{$BLOG_ENTRY_DETAILS.id}">
							<input name="blog_id" type="hidden" id="blog_id" value="{$BLOG_DETAILS.id}">
						</td>
                      </tr>
                    </table>
				  </td>
				</tr>
				<tr>
				  <td colspan="2" valign=top>&nbsp;</td>
				  <td>&nbsp;</td>
				</tr>
				<tr>
				  <td colspan="2" valign=top><div align=right class="element_style"> </div></td>
				  <td>&nbsp;       
			      </td>
				</tr>
				<tr align="center">
				  <td colspan=3 valign=center>
					<input name="submit" type=submit class="btnBg" value="Submit">
					&nbsp;
					<input name="reset" type=reset class="btnBg" value=" Reset">
				  </td>
				</tr>
				<tr align="center">
				  <td colspan=3 valign=center>&nbsp;</td>
				</tr>
				<tr align="center">
				  <td colspan=3 valign=center>&nbsp;</td>
				</tr>
  			</table>
			</form>
		  </td>
		</tr>
	  </table>	
	 </td>
  </tr>
</table>
