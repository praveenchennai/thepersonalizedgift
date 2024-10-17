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
				<li id="limenu{$smarty.foreach.modloop.index}"><a id="menu{$smarty.foreach.modloop.index}" href="#menu{$smarty.foreach.modloop.index}; return false;" onClick="new NavToggle(this); return false;">{$module->name}</a>
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
      <table width="98%" height="{$height}" border="0" align="center" cellpadding="1" cellspacing="0">
        <tr>
  {if $MENU_ALIGN == "left"}		
          <td align="left"><strong>Welcome {$smarty.session.storeSess[0]->username}</strong></td>
		{/if}
          <td align="right">
			<div id="topbar">
			  <div id="topbar-container">
			  {if $smarty.session}
				<div id="supplementalNav"><img src="{$GLOBAL.tpl_url}/images/user_go.png" height="16" width="16">&nbsp;<!-- <a onClick="this.blur();" href="{makeLink mod=store pg=css}act=add_edit{/makeLink}">Profile</a>&nbsp;| <a onClick="this.blur();" href="{makeLink mod=store pg=css}act=list{/makeLink}">Preferences</a>&nbsp;| -->&nbsp;<a href="{makeLink mod=store pg=logout}{/makeLink}">Logout</a>&nbsp;<span title="">1.0.0</span>&nbsp; </div>
				{/if}
			  </div>
			</div>
		  </td>
	    </tr>
  {if $MENU_ALIGN == "top"}		
		<tr>
          <td align="right" valign="bottom">{if $smarty.session}<strong>Welcome {$smarty.session.storeSess[0]->username}</strong>{/if}</td>
        </tr>
		{/if}
      </table>
    </td>
  </tr>
  {if $MENU_ALIGN == "top"}
  <tr>
  	<td height="27" colspan="3" class="subnav">&nbsp;</td>
  </tr>
  {/if}
  <tr>
{if $MENU_ALIGN == "left"}
             <td width="1%" valign="top"><a href="javascript:hide_menu('menu');"><img src="{$GLOBAL.tpl_url}/images/grid/show.gif"></a></td>
			 <td valign="top" align="right" width="15%" bgcolor="#F4F4F4"  id="menu" style="display:inline">
		<table width="100%"  height="100%"  border="0" cellspacing="1" cellpadding="0">
  <tr>
    <td valign="top" bgcolor="#FFFFFF">
<table width="99%"  border="0" cellspacing="0" cellpadding="5">
		{if is_array($smarty.session.storeSess.modules)}
			{foreach from=$smarty.session.storeSess.modules item=module name=modloop}
			
		
		  <tr>
		  <td valign="top" width="5%">&nbsp;</td>
			<td  width="95%">
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td  bgcolor="#D3D3D3"></td>
			  </tr>
			</table>
			<br>
			<div>
			<table width="100%"  border="0" cellspacing="0" cellpadding="5">
			  <tr>
				<td><a id="menu{$smarty.foreach.modloop.index}" href="#" onClick="show_sub('{$smarty.foreach.modloop.index}')">{$module->name}</a></td>
			  </tr>
			</table>
			</div>
		  <div  id ={$smarty.foreach.modloop.index} >
				<table width="100%"  border="0" cellspacing="0" cellpadding="5">
					  {foreach from=$module->menu item=menus}
				  <tr>
					<td><a  {if $menus.id eq $smarty.request.fId} class="submenu2" {else} class="submenu" {/if} href="{makeLink mod=store pg=`$menus.mod`_`$menus.pg`}{$menus.other}&sId={$menus.menu}&fId={$menus.id}{/makeLink}">{$menus.menu}</a>
				</td>
				  </tr>
				  {/foreach}
				</table>
		 </div>
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td></td>
			  </tr>
			</table>
		</td>
		  </tr>
			{/foreach}
		{/if}
		 </table>	
	</td>
  </tr>
</table>

			 </td>
		    <td valign="top" align="center" width="84%">{if isset($smarty.session.storeSess[0]->statusMsg)}<table cellpadding="0" cellspacing="0" class="msg_success" width="80%"><tr><td width="84%" align="center"><strong>Webstore:&nbsp;{$smarty.session.storeSess[0]->heading}</strong><br><font>{$smarty.session.storeSess[0]->statusMsg}</font></td></tr></table>{if  $smarty.session.store_renew_msg eq 1}<br><table cellpadding="0" cellspacing="0" class="msg_success" width="80%"><tr><td width="84%" align="center">Your last webstore subscription payment failed but PayPal has notified us that they will try to process your payment once again. You can however take care of your payment now to ensure that your webstore remains active.

<br><strong>NOTE:</strong> Failure to complete payment may result in the cancelation of your webstore subscription and the inactivation of your webstore. <a href="{makeLink mod=store pg=member_user}act=confirm_payment&user_id={$smarty.session.storeSess[0]->user_id}&modify=1{/makeLink}">Make Payemnt</a>
</td></tr></table>{/if}<br><br>{/if}
			{if file_exists($main_tpl)}
			{include file="$main_tpl"}
	{/if}	</td>
{else}	 
		<td valign="top" align="center">{if isset($smarty.session.storeSess[0]->statusMsg)}<table cellpadding="0" cellspacing="0" class="msg_success" width="80%"><tr><td width="84%" align="center"><strong>Welcome {$smarty.session.storeSess[0]->username}</strong><br><font>{$smarty.session.storeSess[0]->statusMsg}</font></td></tr></table>{/if}<br>
		{if file_exists($main_tpl)}
		{include file="$main_tpl"}
	{/if}	</td>
{/if}
  </tr>
  <tr>
    <td height="20" colspan="3" align="center" class="naFooter">Copyright &copy; <strong>{$smarty.now|date_format:"%Y"}</strong>&nbsp;Personal Touch Products, Inc. All rights reserved. </td>
  </tr>
</table>
</body>
{$EDITOR_INIT}
</html>