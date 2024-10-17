<form method="POST" name="admFrm" action="" style="margin: 0px;"> 
  <table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
    <tr>
      <td>{messageBox}</td>
    </tr>
  </table>
  <table width=80% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
    <tr> 
      <td nowrap class="naH1" colspan="2">Add VQR </td> 
	  <td align="right">&nbsp;</td> 
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
	 <tr class=naGrid2> 
      <td width=40% align="right" valign=top>Conference</td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"><input type="text" name="conference_vqr" value="{$OPTION.conference_vqr}" class="formText"  maxlength="255"></td> 
    </tr>
    <tr class=naGrid2> 
      <td width=40% align="right" valign=top>Journal</td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"><input type="text" name="journal_vqr" value="{$OPTION.journal_vqr}" class="formText"  maxlength="255"></td> 
    </tr>
	<tr class=naGrid1> 
      <td width=40% align="right" valign=top>Books</td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"><input type="text" name="book_vqr" value="{$OPTION.book_vqr}" class="formText" size="20" maxlength="255" ></td> 
    </tr>
    <tr class=naGrid1> 
      <td width=40% align="right" valign=top>Report</td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"><input type="text" name="report_vqr" value="{$OPTION.report_vqr}" class="formText" size="20" maxlength="255" ></td> 
    </tr>
		
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr> 
  </table>
</form> 
