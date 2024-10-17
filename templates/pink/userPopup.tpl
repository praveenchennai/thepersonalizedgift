<html>
<head>

<link type="text/css" rel="stylesheet" href="{$GLOBAL.tpl_url}/css/style-div.css"/>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="50" background="{$GLOBAL.tpl_url}/images/admin_top.gif" colspan="2">&nbsp; </td>
  </tr>
  <tr>
	<td style="width:5px;">&nbsp;</td>
    <td valign="top" align="center"><br>
    {if file_exists($main_tpl)}
    {include file="$main_tpl"}
    {/if}
    </td>
  </tr>
</table>
</body>
</html>