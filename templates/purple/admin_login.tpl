<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Admin Control Panel</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="{$GLOBAL.tpl_url}/css/naStyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="90" background="{$GLOBAL.tpl_url}/images/admin_top.gif"><blockquote>
      <p class="naH1"><br>
        ADMIN CONTROL PANEL</p>
    </blockquote></td>
  </tr>
  <tr>
    <td align="center"><form name="form1" method="post" action="">
	  <table width="400" border="0" cellspacing="0" cellpadding="3">
        <tr>
          <td>{messageBox}</td>
        </tr>
      </table>
      <table width="400" height="200" border="0" cellpadding="4" cellspacing="0" class="naBrdr">
        <tr>
          <td height="20" colspan="2" class="naGridTitle">Please Enter Username and Password </td>
        </tr>
        <tr align="center">
          <td colspan="2" class="naError">{$MESSAGE}&nbsp;</td>
          </tr>
        <tr>
          <td align="right">Username : </td>
          <td><input type="text" name="username"></td>
        </tr>
        <tr>
          <td align="right">Password : </td>
          <td><input type="password" name="password"></td>
        </tr>
        <tr>
          <td align="right">&nbsp;</td>
          <td><input type="submit" name="Submit" value="Submit" class="naBtn"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
    </form>
    </td>
  </tr>
  <tr>
    <td height="20" align="center" class="naFooter">Copyright &copy; <strong>{$smarty.now|date_format:"%Y"}</strong>&nbsp;Personal Touch Products, Inc. All rights reserved. </td>
  </tr>
</table>
</body>
</html>