<form method="POST" name="admFrm" action="" style="margin: 0px;"> 
  <table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
    <tr>
      <td>{messageBox}</td>
    </tr>
  </table>
  <table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
    <tr> 
      <td nowrap class="naH1" colspan="2">Template Management</td> 
	  <td align="right"><a href="{makeLink mod="$MOD" pg="$PG"}act=list{/makeLink}">List Templates</a></td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3 align="center">
        <span class="naError">{$MESSAGE}</span>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle">Template Details</td> 
    </tr> 
    <tr class=naGrid2> 
      <td width=40% align="right" valign=top>Template Name</td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"><input type="text" name="name" value="{$TEMPLATE.name}" class="formText" size="30" maxlength="255"></td> 
    </tr>
    <tr>
      <td colspan=3 class="naGridTitle">HTML Body</td>
    </tr>
    <tr class="naGrid2">
      <td colspan="3"><table border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td><textarea id="body_html" name="body_html" rows="20" cols="60">{$TEMPLATE.body_html}</textarea></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan=3 class="naGridTitle">TEXT Body</td>
    </tr>
    <tr class="naGrid2">
      <td colspan="3"><table border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td><textarea name="body_text" rows="20" cols="100">{$TEMPLATE.body_text}</textarea></td>
        </tr>
      </table></td>
    </tr>
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr> 
  </table>
</form> 
