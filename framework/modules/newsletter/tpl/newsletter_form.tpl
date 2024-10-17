<form method="POST" name="admFrm" action="" style="margin: 0px;"> 
  <table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
    <tr>
      <td>{messageBox}</td>
    </tr>
  </table>
  <table width=80% border=0 align="center" cellpadding=5 cellspacing=1 class=naBrDr> 
    <tr> 
      <td nowrap class="naH1" colspan="2">Newsletter Management</td> 
	  <td align="right"><a href="{makeLink mod="$MOD" pg="$PG"}act=list{/makeLink}">List Newsletters</a></td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3 align="center">
        <span class="naError">{$MESSAGE}</span>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle">Newsletter Details</td> 
    </tr> 
    <tr class=naGrid2> 
      <td width=40% align="right" valign=top>Newsletter Name</td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"><input type="text" name="name" value="{$NEWSLETTER.name}" class="formText" size="30" maxlength="255"></td> 
    </tr>
	{if $smarty.request.id == ''}
    <tr class=naGrid1> 
      <td width=40% align="right" valign=top>Format</td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"><select name="format">
        <option value="">Select Format</option>
        <option value="H">HTML</option>
        <option value="T">TEXT</option>
        <option value="B">BOTH</option>
            </select></td>
    </tr>
    <tr class=naGrid2>
      <td align="right" valign=top>Template</td>
      <td valign=top>:</td>
      <td><select name="template_id">
          <option value="">Select Template</option>
          {html_options values=$TEMPLATE.id output=$TEMPLATE.name selected=$smarty.request.template_id}
      </select></td>
    </tr>
    <tr class="naGridTitle">
      <td colspan="3" align="center"><input type="submit" class="naBtn" value="Next &raquo;"></td>
    </tr>
	{else}
    <tr class=naGrid1> 
      <td width=40% align="right" valign=top>Subject</td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"><input type="text" name="subject" value="{$NEWSLETTER.subject}" class="formText" size="50" maxlength="255" ></td> 
    </tr>
	{if $NEWSLETTER.format neq 'T'}
    <tr>
      <td colspan=3 class="naGridTitle">HTML Body</td>
    </tr>
    <tr class="naGrid2">
      <td colspan="3"><table border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td><textarea id="body_html" name="body_html" rows="20" cols="60">{$NEWSLETTER.body_html}</textarea></td>
        </tr>
      </table></td>
    </tr>
	{/if}
	{if $NEWSLETTER.format neq 'H'}
    <tr>
      <td colspan=3 class="naGridTitle">TEXT Body</td>
    </tr>
    <tr class="naGrid2">
      <td colspan="3"><table border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td><textarea name="body_text" rows="20" cols="100">{$NEWSLETTER.body_text}</textarea></td>
        </tr>
      </table></td>
    </tr>
	{/if}
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr> 
	{/if}
  </table>
</form> 
