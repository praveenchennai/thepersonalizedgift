<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
  <form method="POST" name="admFrm" action="" style="margin: 0px;"> 
    <tr> 
      <td nowrap class="naH1" colspan="2">Module Management</td> 
	  <td align="right"><a href="{makeLink mod="admin" pg="module"}act=list{/makeLink}">List Modules</a></td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span>      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">Module Details</span></td> 
    </tr> 
    <tr class=naGrid2> 
      <td width=40% align="right" valign=top>Name</td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"><input type="text" name="name" value="{$MODULE.name}" class="formText" size="30" maxlength="25" ></td> 
    </tr> 
    <tr class=naGrid1> 
      <td valign=top align="right">Folder</td> 
      <td valign=top>:</td> 
      <td><input type="text" name="folder" value="{$MODULE.folder}" class="formText" size="30" maxlength="255" > </td> 
    </tr>
    <tr class=naGrid2>
      <td valign=top align="right">Position</td>
      <td valign=top>:</td>
      <td><input type="text" name="position" value="{$MODULE.position}" class="formText" size="5" maxlength="4"></td>
    </tr>
    <tr class=naGrid1>
      <td valign=top align="right">Show Admin Menu? </td>
      <td valign=top>:</td>
      <td><input type="checkbox" name="show_admin_menu" value="Y" {if $MODULE.show_admin_menu != 'N'}checked{/if} /></td>
    </tr>
    <tr class=naGrid2>
      <td valign=top align="right">Active</td>
      <td valign=top>:</td>
      <td><input type="checkbox" name="active" value="Y" {if $MODULE.active != 'N'}checked{/if} /></td>
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
