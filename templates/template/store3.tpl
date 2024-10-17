<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Admin Control Panel</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META http-equiv="cache-control" content="no-cache">
<META HTTP-EQUIV="EXPIRES" CONTENT="Mon, 22 Jul 2002 11:12:01 GMT">
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
		var moduleid;
		exdate.setDate(exdate.getDate()+expiredays)
		document.cookie=moduleid+ "=" +escape(value)+((expiredays==null) ? "" : ";expires="+exdate.toGMTString());
		
        if(element) element.blur();
    }
	
		function show_sub(id)
		{
		//alert(id);
		if(document.getElementById(id).style.display == 'none')
		document.getElementById(id).style.display = 'inline';
		else
		document.getElementById(id).style.display = 'none';
		//document.getElementById(id).style.visibility == 'visible';
		
		}
	function hide_menu(id)
	{
	//alert(id);
		if(document.getElementById(id).style.display == 'none')
		{
			document.getElementById(id).style.display = 'inline';
		}
		else
		{
			document.getElementById(id).style.display = 'none';
		}
		
	}
{/literal}
</script>
</head>

{if $MENU_ALIGN == "top"}
{assign var="onLoad" value="new NavToggle(document.getElementById('menu0')); return false;"}
{assign var = "height" value="53"}
{else}
{assign var="onLoad" value=""}
{assign var = "height" value="30"}
{/if}

<body {if $smarty.request.mod == "" || $smarty.request.mod==$smarty.session.storeSess.modules.0->folder} onLoad="new NavToggle(document.getElementById('menu0')); return false;"{/if}>
{if $MENU_ALIGN == "top"}
  <div id="Navcontainer">
    <div id="divNav">
      <ul id="nav">
		{if is_array($smarty.session.storeSess.modules)}
			{foreach from=$smarty.session.storeSess.modules item=module name=modloop}
				<li id="limenu{$smarty.foreach.modloop.index}"{if $smarty.request.mod == "" && $smarty.foreach.modloop.index == 0 || $module->folder == $MOD_REP} class="active"{/if}><a id="menu{$smarty.foreach.modloop.index}" href="#menu{$smarty.foreach.modloop.index}; return false;" onClick="new NavToggle(this); return false;">{$module->name}</a>
				  <ul class="subnav" id="menu{$smarty.foreach.modloop.index}">
					 {foreach from=$module->menu item=menus}
						<li><a onClick="this.blur();" href="{makeLink mod=store pg=`$menus.mod`_`$menus.pg`}{$menus.other}&sId={$menus.menu}&fId={$menus.id}{/makeLink}">{$menus.menu}</a></li> 
					{/foreach}
				  </ul>
				</li>
			{/foreach}
		{/if}
      </ul>
    </div>
  </div>
  {/if}
  {popup_init src="`$smarty.const.SITE_URL`/includes/overlib/overlib.js"}
  
 <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="{$height}" colspan="3" background="{$GLOBAL.tpl_url}/images/admin_top.gif">
      
    </td>
  </tr>
  {if $MENU_ALIGN == "top"}
  <tr>
  	<td height="27" colspan="3" class="subnav">&nbsp;</td>
  </tr>
  {/if}
  <tr>

		<td valign="top" align="center">{if isset($smarty.session.storeSess[0]->statusMsg)}<table cellpadding="0" cellspacing="0" class="msg_success" width="80%"><tr><td width="84%" align="center"><strong>Welcome {$smarty.session.storeSess[0]->username}</strong><br><font>{$smarty.session.storeSess[0]->statusMsg}</font></td></tr></table>{/if}<br>
		{if file_exists($main_tpl)}
		{include file="$main_tpl"}
	{/if}	</td>

  </tr>
  <tr>
    <td height="20" colspan="3" align="center" class="naFooter">Copyright &copy; <strong>{$smarty.now|date_format:"%Y"}</strong> <a class="naFooter" href="http://www.newagesmb.com" target="_blank">www.newagesmb.com</a>. All rights reserved. </td>
  </tr>
</table>
</body>
</html>