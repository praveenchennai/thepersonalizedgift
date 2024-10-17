<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
  <form method="POST" name="admFrm" action="" style="margin: 0px;"> 
    <tr> 
      <td colspan="2" align="left" nowrap class="naH1">User Management</td> 
      <td align="right">&nbsp;</td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span></div>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">User Details</span></td> 
    </tr> 
    <tr class=naGrid2> 
      <td valign=top width="40%"><div align=right class="element_style"> Username</div></td> 
      <td width="1%" valign=top>:</td> 
      <td width="59%">{$USER.username} </td> 
    </tr> 
    <tr class=naGrid1> 
      <td valign=top><div align=right class="element_style"> First Name</div></td> 
      <td valign=top>:</td> 
      <td>{$USER.first_name}</td> 
    </tr> 
    <tr class=naGrid2> 
      <td valign=top><div align=right class="element_style"> Last Name</div></td> 
      <td valign=top>:</td> 
      <td>{$USER.last_name} </td> 
    </tr> 
    <tr class=naGrid1> 
      <td valign=top><div align=right class="element_style">Email</div></td> 
      <td valign=top>:</td> 
      <td>{$USER.email} </td> 
    </tr> 
    
    
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input name="Button" type=button class="formbutton" value="Go Back" onClick="javascript:history.go(-1)"> 
        </div></td> 
    </tr> 
  </form> 
</table>