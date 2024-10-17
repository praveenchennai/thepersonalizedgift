<form action='' method='POST'>
    <table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
      <tr>
        <td></td>
      </tr>
    </table>
    <table align="center" width="80%" border="0" cellspacing="0" cellpadding="0" class=naBrdr>
    <tr>
        <td></td>
      </tr> 
      <tr> 
        <td><table width="98%" align="center"> 
            <tr> 
              <td nowrap class="naH1">Please Verify Your Email Address</td> 
              <td nowrap align="right" class="titleLink">&nbsp;</td> 
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
				    
                    <tr>
                      <td align="center" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0" >
						<tr>
							<td width="44%" align="right" colspan="3">  <span class="naError">{messageBox}</span></td>
							
						</tr>
				        <tr >
				          <td>&nbsp;</td>
				          <td>&nbsp;</td>
				          <td  valign=center>&nbsp;</td>
			            </tr>
				        <!--<tr > 
							<td colspan="3"><table width="100%" cellpadding="0" cellspacing="0" border="0" ><tr>
				  		      <td width="39%"><div align="right">
			  		          </div></td>
						      <td width="25%"><input name="btn_upgrade" class="formbutton" value="Send Activation link" type="submit">
							  
</td>
					          <td width="36%"  valign=center><input type="button" class="formbutton" value="Change Email Address" onclick="window.location='{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=change_email&user_id	={$smarty.request.user_id}{/makeLink}'"></td></tr></table></td>
				        </tr>--> 
				        
				        </table></td>
                    </tr>
                   
                    </table>
			      </td>
                </tr>
       </table></td> 
      </tr> 
	  <tr class="naGridTitle" height="30"> 
      <td colspan=3 valign=center><div align=center> 
          <input type=submit value="Send Activation link" name="btn_upgrade" class="naBtn">&nbsp; 
          <input  type="button" value="Change Email Address"  class="naBtn" onclick="window.location='{makeLink mod=`$smarty.request.mod` pg=`$smarty.request.pg`}act=change_email&user_id	={$smarty.request.user_id}{/makeLink}'"> 
        </div></td> 
    </tr>
	  <tr>
				 	      <td>&nbsp;</td>
				        </tr>
    </table>
	
				
  </form>

