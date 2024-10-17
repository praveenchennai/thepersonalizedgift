<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
{literal}
<script language="javascript">
	var fields=new Array('username','password','confirm_pass','email2','first_name','last_name');
	var msgs=new Array('Username','Password','Confirm password','email','First name','Last name');
	
	var emails=new Array('email2');
	var email_msgs=new Array('Invalid Email')
	
	function checkLength()
	{
	
		if (chk(document.subFrm))
		{	 			

			var str1=document.subFrm.username.value;
			var str2=document.subFrm.password.value;
			var str3=document.subFrm.confirm_pass.value;
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
	</script>
	{/literal}
</head>

<body>
<form action="" method="POST" enctype="multipart/form-data" name="subFrm" style="margin: 0px;" onSubmit="return checkLength()"> 
<table width="100%" cellpadding=5 cellspacing=0 class=naBrDr>
	    <tr class=naGrid2>
	   <td colspan="3" align="left" valign=top class="naGridTitle"><strong>Create new user </strong></td>
    </tr>
	
	
	    <tr align="center" class=naGrid2>
	      <td colspan="3" valign=top>{messageBox}</td>
    </tr>
     <tr class=naGrid2>
	   <td width="38%" align="right" valign=top>Username</td>
	   <td width="4%" valign=top>:</td>
	   <td width="58%"><span class="bodytext">
	     <input name="username" type="text" class="input" id="username" value="{$smarty.request.username}" size="30" />
	   </span></td>
    </tr>
	 <tr class=naGrid2>
	   <td valign=top align="right">Password</td>
	   <td valign=top>:</td>
	   <td><span class="bodytext">
	     <input name="password" type="password" class="input" id="password" size="30"/>
	   </span></td>
    </tr>
	 <tr class=naGrid2>
	   <td valign=top align="right">Confirm Password </td>
	   <td valign=top>:</td>
	   <td><span class="bodytext">
	     <input name="confirm_pass" type="password" class="input" id="confirm_pass" size="30"/>
	   </span></td>
    </tr>
	 <tr class=naGrid2>
	   <td valign=top align="right">First Name</td>
	   <td valign=top>:</td>
	   <td><span class="bodytext">
	     <input name="first_name" type="text" class="input" id="first_name" value="{$smarty.request.first_name}" size="30" />
	   </span></td>
    </tr>
	 <tr class=naGrid2>
	   <td valign=top align="right">Last Name</td>
	   <td valign=top>:</td>
	   <td><span class="bodytext">
	     <input name="last_name" type="text" class="input" id="last_name" value="{$smarty.request.last_name}" size="30" />
	   </span></td>
    </tr>
	 <tr class=naGrid2>
	   <td valign=top align="right">Email</td>
	   <td valign=top>:</td>
	   <td><span class="bodytext">
	     <input name="email2" type="text" class="input" id="email2" value="{$smarty.request.email2}" size="30" {if ($smarty.request.id!="")} readonly {/if}/>
	   </span></td>
    </tr>
	 <tr class=naGrid2>
	   <td valign=top align="right">&nbsp;</td>
	   <td valign=top>&nbsp;</td>
	   <td>&nbsp;</td>
  </tr>
	 <tr align="center" class=naGridTitle>
	   <td colspan="3" valign=top><input type=submit value="Submit" class="naBtn"></td>
  </tr>
	   </table>
</form>
</body>
</html>
