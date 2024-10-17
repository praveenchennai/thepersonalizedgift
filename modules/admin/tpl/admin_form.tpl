<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
  <form method="POST" name="admFrm" action="" style="margin: 0px;"> 
    <tr> 
      <td colspan="2" align="left" nowrap class="naH1">Admin User Management</td> 
      <td align="right"><a href="{makeLink mod="admin" pg="admin"}act=list{/makeLink}">List Admin Users</a></td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">Admin Details</span></td> 
    </tr> 
    <tr class=naGrid2> 
      <td valign=top width="40%"><div align=right class="element_style"> Username</div></td> 
      <td width="1%" valign=top>:</td> 
      <td width="59%"><input type="text" name="username" value="{$ADMIN.username}" class="formText" size="30" maxlength="25" {if $ADMIN.username == 'admin'}readonly{/if} > </td> 
    </tr> 
    <tr class=naGrid1> 
      <td valign=top><div align=right class="element_style"> Password</div></td> 
      <td valign=top>:</td> 
      <td><input type="password" name="password" value="{$ADMIN.password}" class="formText" size="30" maxlength="25"> </td> 
    </tr> 
    <tr class=naGrid2> 
      <td valign=top><div align=right class="element_style"> Confirm Password</div></td> 
      <td valign=top>:</td> 
      <td><input type="password" name="conf_password" value="{$ADMIN.password}" class="formText" size="30" maxlength="25"> </td> 
    </tr> 
    <tr class=naGrid1> 
      <td valign=top><div align=right class="element_style"> Name</div></td> 
      <td valign=top>:</td> 
      <td><input type="text" name="name" value="{$ADMIN.name}" class="formText" size="30" maxlength="255" > </td> 
    </tr> 
    <tr class=naGrid2> 
      <td valign=top><div align=right class="element_style"> Email</div></td> 
      <td valign=top>:</td> 
      <td><input type="text" name="email" value="{$ADMIN.email}" class="formText" size="30" maxlength="255" > </td> 
    </tr> 
    {if $MODULE_LIST}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">Assign Modules</span></td> 
    </tr> 
    {foreach item=MODULE from=$MODULE_LIST}
    <tr class={cycle values="naGrid2,naGrid1"}> 
      <td valign=top><div align=right class="element_style">{$MODULE->name}</div></td> 
      <td valign=top>:</td> 
      <td><span class="formCheckbox"> 
        <input type=checkbox name="module[]" value="{$MODULE->id}"{if $MODULE->module_id} checked{/if}> 
        </span> </td> 
    </tr> 
    {/foreach}
    {/if}
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr> 
	<tr><td colspan=3 valign=center>&nbsp;</td></tr>
  </form> 
</table>