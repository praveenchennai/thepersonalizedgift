{literal}
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" >
	var fields=new Array('email');
	var msgs=new Array('Email');
	var emails=new Array('email');
	var email_msgs=new Array('Invalid Email')
	function checkLength()
	{
		if (chk(document.subFrm))
		{	 			
			return true;
		}
		else
		{
			return false;
			document.subFrm.email.focus();
			exit();
		}		
	}
	
	
	
</SCRIPT>
{/literal}
<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
  <form method="POST" name="subFrm" action="" style="margin: 0px;" > 
    <tr> 
      <td nowrap class="naH1" colspan="2">Subscription Management</td> 
	  <td align="right"><a href="{makeLink mod="$MOD" pg="$PG"}act=list{/makeLink}">List Subscription</a></td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3 align="center">
        <span class="naError">{$MESSAGE}</span>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">Subscriber Details</span></td> 
    </tr> 
    <tr class=naGrid2> 
      <td width="40%" align="right" valign=top>List Name</td> 
      <td width="1%" valign=top>:</td> 
      <td width="59%">
        <select name="list_id">
        <option value="">Select Mailing List</option>
        {html_options values=$MAILINGLIST.id output=$MAILINGLIST.name selected=$SUBSCRIPTION.list_id}
        </select>
      </td> 
    </tr>
    <tr class=naGrid1> 
      <td width=40% align="right" valign=top>Member</td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"><div style="float:left;" id="memberName">{$MEMBER_NAME}</div><input type="button" class="naBtn" value="Select" onClick="w=window.open('{makeLink mod=newsletter pg=memberSearch}act=list{/makeLink}', 'w', 'width=700,height=500,scrollbars=yes');w.focus();" {popup text='Click to search and select a member' fgcolor='#eeddff'}><input type="hidden" name="member_id" value="{$SUBSCRIPTION.member_id}"></td> 
    </tr>
    <tr class=naGrid2> 
      <td width=40% align="right" valign=top>Member Email</td> 
      <td width=1% valign=top>:</td> 
      <td width="59%"><input type="text" name="email" value="{$SUBSCRIPTION.email}" class="formText" size="30" maxlength="255" onblur="checkLength()" ></td> 
    </tr>
    <tr class="naGrid1">
      <td align="right">Newsletter Format</td>
      <td>:</td>
      <td><table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><input name="format" type="radio" value="H" id="H"{if $SUBSCRIPTION.format ne 'T'} checked{/if}></td>
            <td><label for="H">HTML</label></td>
            <td>&nbsp;</td>
            <td><input name="format" type="radio" value="T" id="T"{if $SUBSCRIPTION.format eq 'T'} checked{/if}></td>
            <td><label for="T">Text</label></td>
          </tr>
      </table></td>
    </tr> 
    <tr class="naGrid2">
      <td align="right">Confirmed</td>
      <td>:</td>
      <td><table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><input name="confirmed" type="radio" value="Y" id="Y"{if $SUBSCRIPTION.confirmed eq 'Y'} checked{/if}></td>
            <td><label for="Y">Yes</label></td>
            <td>&nbsp;</td>
            <td><input name="confirmed" type="radio" value="N" id="N"{if $SUBSCRIPTION.confirmed ne 'Y'} checked{/if}></td>
            <td><label for="N">No</label></td>
          </tr>
      </table></td>
    </tr> 
    <tr class="naGrid1">
      <td align="right">Active</td>
      <td>:</td>
      <td><table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><input name="active" type="radio" value="Y" id="Y"{if $SUBSCRIPTION.active ne 'N'} checked{/if}></td>
            <td><label for="Y">Yes</label></td>
            <td>&nbsp;</td>
            <td><input name="active" type="radio" value="N" id="N"{if $SUBSCRIPTION.active eq 'N'} checked{/if}></td>
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
