<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
  <form method="POST" name="admFrm" action="" style="margin: 0px;"> 
    <tr> 
      <td nowrap class="naH1" colspan="2">Support List Management</td> 
	  <td align="right"><a href="{makeLink mod="$MOD" pg="$PG"}act=list{/makeLink}">List Support Lists</a></td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3 align="center">
        <span class="naError">{$MESSAGE}</span>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">Support List Details</span></td> 
    </tr> 
    <tr class=naGrid2> 
      <td width=40% align="right" valign=top>User Name</td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"><input type="text" name="name" value="{$SUPPORT_LIST.username}" class="formText" size="30" maxlength="255"></td> 
    </tr>
    <tr class=naGrid1>
      <td align="right" valign=top>User Email </td>
      <td valign=top>:</td>
      <td><input type="text" name="name2" value="{$SUPPORT_LIST.email}" class="formText" size="30" maxlength="255"></td>
    </tr>
    <tr class=naGrid2> 
      <td width=40% align="right" valign=top>Support Title </td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"><input type="text" name="owner_name" value="{$SUPPORT_LIST.title}" class="formText" size="30" maxlength="100"></td> 
    </tr>
    <tr class=naGrid1> 
      <td width=40% align="right" valign=top> Content </td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"><textarea id="body_html" name="body_html" rows="10" cols="40">{$SUPPORT_LIST.content}</textarea></td> 
    </tr>
    <tr class="naGrid2">
      <td align="right">Date Created </td>
      <td>:</td>
      <td><input type="text" name="owner_name2" value="{$SUPPORT_LIST.date_added_f}" class="formText" size="30" maxlength="100">
      </td>
    </tr> 
	
	<!--
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr> 
	-->
  </form> 
</table>
