<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Admin Control Panel</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="{$GLOBAL.tpl_url}/css/naStyle.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="{$smarty.const.SITE_URL}/scripts/validator.js"></script>
<script type="text/javascript">
{literal}
    function NavToggle(element) {
	
        // This gives the active tab its look
        var navid = document.getElementById('nav');
        var navs = navid.getElementsByTagName('li');
        var navsCount = navs.length;
        for(j = 0; j < navsCount; j++) {
            active = (navs[j].id == element.parentNode.id) ? "active" : "";
            navs[j].className = active;
        }
		
		var exdate=new Date();
		var value="123";
		var	expiredays="";
		exdate.setDate(exdate.getDate()+expiredays)
		document.cookie=moduleid+ "=" +escape(value)+((expiredays==null) ? "" : ";expires="+exdate.toGMTString());
		
        if(element) element.blur();
    }
{/literal}
</script>
</head>
<body {if $smarty.request.mod == "" || $smarty.request.mod==$smarty.session.adminSess->modules.0->folder} onLoad="new NavToggle(document.getElementById('menu0')); return false;"{/if}>
  <div id="Navcontainer">
    <div id="divNav">
      <ul id="nav">
		{if is_array($smarty.session.adminSess->modules)}
			{foreach from=$smarty.session.adminSess->modules item=module name=modloop}
				<li id="limenu{$smarty.foreach.modloop.index}"{if $smarty.request.mod == "" && $smarty.foreach.modloop.index == 0 || $module->folder == $smarty.request.mod} class="active"{/if}><a id="menu{$smarty.foreach.modloop.index}" href="#menu{$smarty.foreach.modloop.index}; return false;" onClick="new NavToggle(this); return false;">{$module->name}</a>
				  <ul class="subnav" id="menu{$smarty.foreach.modloop.index}">
					 {foreach from=$module->menu item=menus}
						<li><a onclick="this.blur();" href="{makeLink mod=`$menus.mod` pg=`$menus.pg`}{$menus.other}&sId={$menus.menu}&fId={$menus.id}{/makeLink}">{$menus.menu}</a></li> 
					{/foreach}
				  </ul>
				</li>
			{/foreach}
		{/if}
      </ul>
    </div>
  </div>{popup_init src="`$smarty.const.SITE_URL`/includes/overlib/overlib.js"}
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="53" background="{$GLOBAL.tpl_url}/images/admin_top.gif">
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
  	<td height="27" class="subnav">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top" align="center"><br>
    {if file_exists($main_tpl)}
    {include file="$main_tpl"}
    {/if}
    </td>
  </tr>
  <tr>
    <td height="20" align="center" class="naFooter">Copyright &copy; <strong>{$smarty.now|date_format:"%Y"}</strong> <a class="naFooter" href="http://www.newagesmb.com" target="_blank">www.newagesmb.com</a>. All rights reserved. </td>
  </tr>
</table>
</body>

</html>