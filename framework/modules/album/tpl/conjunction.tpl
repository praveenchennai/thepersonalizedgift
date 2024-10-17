<form method="POST" name="admFrm" action="" style="margin: 0px;"> 
  <table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
    <tr>
      <td>{messageBox}</td>
    </tr>
  </table>
  <table width=80% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
    <tr> 
      <td nowrap class="naH1" colspan="2">Add Articles and Conjunctions </td> 
	  <td align="right"><a href="{makeLink mod=album pg=album_admin}act=list_conjunction&link=Y{/makeLink}">List Conjunctions</a></td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3 align="center">
        <span class="naError">{$MESSAGE}</span>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle">&nbsp;</td> 
    </tr> 
	 <tr class=naGrid2 height="65" > 
      <td width=40%  align="right">Conjunctions</td> 
      <td width=1% >:</td> 
      <td width="59%"><input type="text" name="conjunction" value="{$OPTION.conjunction}" class="formText" size="25"  maxlength="255"></td> 
    </tr>
		
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr> 
  </table>
</form> 
