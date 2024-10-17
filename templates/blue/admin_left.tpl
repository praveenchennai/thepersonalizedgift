<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Admin Control Panel</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="{$GLOBAL.tpl_url}/css/naStyle.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
<script type="text/javascript">
{literal}
	
function show_sub(id)
{
if(document.getElementById(id).style.display == 'none')
document.getElementById(id).style.display = 'inline';
else
document.getElementById(id).style.display = 'none';
//document.getElementById(id).style.visibility == 'visible';
}
	
{/literal}
</script>
</head>
<body>
{popup_init src="`$smarty.const.SITE_URL`/includes/overlib/overlib.js"}
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="53" colspan="2" background="{$GLOBAL.tpl_url}/images/admin_top.gif">
      <table width="98%" height="53" border="0" align="center" cellpadding="1" cellspacing="0">
        <tr>
          <td align="right">
			<div id="topbar">
			  <div id="topbar-container">
				<div id="supplementalNav">&nbsp;{if $smarty.session.adminSess->username eq "admin"}<img src="{$GLOBAL.tpl_url}/images/user_go.png" height="16" width="16"> <a onClick="this.blur();" href="{makeLink mod="admin" pg="admin"}act=list{/makeLink}">Admin</a> | <a onClick="this.blur();" href="{makeLink mod="admin" pg="config"}{/makeLink}">Preferences</a> |&nbsp;{/if}<a href="{makeLink mod=admin pg=change_password}{/makeLink}">Change Password</a>&nbsp;|&nbsp;<a href="{makeLink mod=admin pg=logout}{/makeLink}">Logout</a>&nbsp;|&nbsp;<span title="">1.0.0</span>&nbsp; </div>
			  </div>
			</div>
		  </td>
	    </tr><tr>
          <td align="right" valign="bottom"><strong>Welcome {$smarty.session.adminSess->username}</strong></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
  	<td height="27" colspan="2" class="subnav">&nbsp;</td>
  </tr>
  <tr>
    <td width="15%" align="center" valign="top">

<table width="99%"  border="0" cellspacing="0" cellpadding="3">
{if is_array($smarty.session.adminSess->modules)}
	{foreach from=$smarty.session.adminSess->modules item=module name=modloop}
  <tr>
    <td ><a id="menu{$smarty.foreach.modloop.index}" href="#menu{$smarty.foreach.modloop.index}" onClick="show_sub('{$smarty.foreach.modloop.index}')">{$module->name}</a>
  <div  id ={$smarty.foreach.modloop.index} style="display:none">
		<table width="100%"  border="0" cellspacing="0" cellpadding="3">
			  {foreach from=$module->menu item=menus}
		  <tr>
			<td>&nbsp;&nbsp;&#42;<a href="{makeLink mod=`$menus.mod` pg=`$menus.pg`}{$menus.other}&sId={$menus.menu}&fId={$menus.id}{/makeLink}">{$menus.menu}</a>
		</td>
		  </tr>
		  {/foreach}
		</table>
 </div>
</td>
  </tr>
    {/foreach}
{/if}
 </table>
	
	</td>
    <td width="85%" align="center" valign="top"><br>
    {if file_exists($main_tpl)}
    {include file="$main_tpl"}
    {/if}
    </td>
  </tr>
  <tr>
    <td height="20" colspan="2" align="center" class="naFooter">Copyright &copy; <strong>{$smarty.now|date_format:"%Y"}</strong> <a class="naFooter" href="http://www.newagesmb.com" target="_blank">www.newagesmb.com</a>. All rights reserved. </td>
  </tr>
</table>
</body>
</html>