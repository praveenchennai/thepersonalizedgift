<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
  <form method="POST" name="admFrm" action="" style="margin: 0px;"> 
    <tr> 
      <td nowrap class="naH1" colspan="2">Mailing List Management</td> 
	  <td align="right"><a href="{makeLink mod="$MOD" pg="$PG"}act=list{/makeLink}">List Mailing Lists</a></td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3 align="center">
        <span class="naError">{$MESSAGE}</span>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">Mailing List Details</span></td> 
    </tr> 
    <tr class=naGrid2> 
      <td width=40% align="right" valign=top>List Name</td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"><input type="text" name="name" value="{$MAILINGLIST.name}" class="formText" size="30" maxlength="255"></td> 
    </tr>
    <tr class=naGrid1> 
      <td width=40% align="right" valign=top>Owner Name</td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"><input type="text" name="owner_name" value="{$MAILINGLIST.owner_name}" class="formText" size="30" maxlength="100"></td> 
    </tr>
    <tr class=naGrid2> 
      <td width=40% align="right" valign=top>Owner Email</td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"><input type="text" name="owner_email" value="{$MAILINGLIST.owner_email}" class="formText" size="30" maxlength="255" ></td> 
    </tr>
    <tr class="naGrid1">
      <td align="right">Allow Subscription</td>
      <td>:</td>
      <td><table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><input name="allow_subscription" type="radio" value="Y" id="Y"{if $MAILINGLIST.allow_subscription ne 'N'} checked{/if}></td>
            <td><label for="Y">Yes</label></td>
            <td>&nbsp;</td>
            <td><input name="allow_subscription" type="radio" value="N" id="N"{if $MAILINGLIST.allow_subscription eq 'N'} checked{/if}></td>
            <td><label for="N">No</label></td>
          </tr>
      </table></td>
    </tr> 
    <tr class="naGridTitle"> 
      <td colspan=3 valign=center><div align=center> 
          <input type=submit value="Submit" class="naBtn">&nbsp; 
          <input type=reset value="Reset" class="naBtn"> 
        </div></td> 
    </tr> 
  </form> 
</table>
