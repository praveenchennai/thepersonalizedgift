<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
  <form method="POST" name="chpwd" action=""> 
    <tr> 
      <td colspan=3 class="naH1">Set Email Address </td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid1>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span>
      </td>
    </tr>
    {/if}
    <tr class=naGrid2> 
      <td valign=top width=40%><div align=right class="element_style">Email Address </div></td> 
      <td width=1 valign=top>:</td> 
      <td><input type="text" name="email" value="{$EMAIL}" class="formText" size="75" maxlength="255"></td> 
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
