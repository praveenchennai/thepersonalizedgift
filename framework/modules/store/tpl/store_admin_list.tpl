<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
<script language="javascript">
var fields=new Array('username','password','conf_password','email','name');
	var msgs=new Array('Username','Password','Confirm password','email','name');
	
	var emails=new Array('email');
	var email_msgs=new Array('Invalid Email')
	{literal}
	function checkLength()
	{
	
		if (chk(document.admFrm))
		{	 			

			var str1=document.admFrm.username.value;
			var str2=document.admFrm.password.value;
			var str3=document.admFrm.conf_password.value;
			if (str1.length<4)
			{
				alert("Username length is too short");
				return false;		
			}
			else if (str2.length<6)
			{
				alert("Password length is too short");
				return false;		
			}
			else if (str2!=str3)
			{
				alert("Passwords are not matching");
				return false;		
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}
	}
	{/literal}
</script>

<table width="80%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td>{messageBox}</td>
  </tr>
</table>
<table border=0 width=80% cellpadding=5 cellspacing=1 class=naBrDr> 
  <form method="POST" name="admFrm" action="" style="margin: 0px;" onSubmit="return checkLength()"> 
    <tr> 
      <td colspan="2" align="left" nowrap class="naH1">Store Admin User Management</td> 
      <td align="right"></td> 
    </tr> 
    {if isset($MESSAGE)}
    <tr class=naGrid2>
    	<td valign=top colspan=3><div align=center class="element_style">
        <span class="naError">{$MESSAGE}</span>
      </td>
    </tr>
    {/if}
    <tr> 
      <td colspan=3 class="naGridTitle"><span class="group_style">User Details</span></td> 
    </tr> 
    <tr class=naGrid2> 
      <td valign=top width="40%"><div align=right class="element_style"> Username</div></td> 
      <td width="1%" valign=top>:</td> 
      <td width="59%">{$ADMIN.username}</td> 
    </tr> 
    
    <tr class=naGrid1> 
      <td valign=top><div align=right class="element_style"> Name</div></td> 
      <td valign=top>:</td> 
      <td>{$ADMIN.name}</td> 
    </tr> 
    <tr class=naGrid2> 
      <td valign=top><div align=right class="element_style"> Email</div></td> 
      <td valign=top>:</td> 
      <td>{$ADMIN.email} </td> 
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
          <a href="{makeLink mod='store' pg='storeIndex'}act=user_form{/makeLink}">Edit</a>&nbsp; 
        
        <input type="hidden" id="id" name="id" value="{$ADMIN.id}"></div></td> 
    </tr> 
	<tr><td colspan=3 valign=center>&nbsp;</td></tr>
  </form> 
</table>