<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
  <form method="POST" name="chpwd" action=""> 
    <tr> 
      <td colspan=3 class="naH1">Change Password</td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid1>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span>
      </td>
    </tr>
    {/if}
    <tr class=naGrid2> 
      <td valign=top width=40%><div align=right class="element_style">Old Password</div></td> 
      <td width=1 valign=top>:</td> 
      <td><input type="password" name="old_password" value="" class="formText" size="30" maxlength="25"></td> 
    </tr> 
    <tr class=naGrid1> 
      <td valign=top width=40%><div align=right class="element_style">New Password</div></td> 
      <td width=1 valign=top>:</td> 
      <td><input type="password" name="new_password" value="" class="formText" size="30" maxlength="25"></td> 
    </tr> 
    <tr class=naGrid2> 
      <td valign=top width=40%><div align=right class="element_style">Retype New Password</div></td> 
      <td width=1 valign=top>:</td> 
      <td><input type="password" name="conf_password" value="" class="formText" size="30" maxlength="25"></td> 
    </tr> 
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr>
	<tr><td colspan=3 valign=center>&nbsp;</td></tr> 
  </form> 
</table>