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
        if(element) element.blur();
    }
{/literal}
</script>
</head>
<body>
 <div id="Navcontainer">
    <div id="divNav">
      <ul id="nav">	
	  <li id="limenu0" >
	  	<a id="menu0" href="#menu0" onClick="new NavToggle(this); return false;">Home</a>
			<ul class="subnav" id="menu0"></ul>
		</li>				  				  
		<li id="limenu1" {if $smarty.request.pg=='css'}class="active"{/if}>
			<a id="menu1" href="#menu1" onClick="new NavToggle(this); return false;">CSS</a>
				<ul class="subnav" id="menu1">
					<li><a  onclick="this.blur();" href="{makeLink mod=store pg=css}act=list{/makeLink}">CSS List</a></li>
				</ul>
		</li>
		<li id="limenu2" {if  $smarty.request.pg == 'menu' ||  $smarty.request.pg == 'link' || $smarty.request.pg == 'page'} class="active"{/if}>
			<a id="menu2" href="#menu2" onClick="new NavToggle(this); return false;">CMS</a>
				<ul class="subnav" id="menu2">
					<li><a onClick="this.blur();" href="{makeLink mod=store pg=menu}act=list{/makeLink}">Manage Content</a></li>
					<li><a onClick="this.blur();" href="{makeLink mod=store pg=link}area=top{/makeLink}">Manage Links</a></li>
				</ul>
		</li>
		<li id="limenu3" {if $smarty.request.pg=='storeuser'}class="active"{/if}>
			<a id="menu3" href="#menu3" onClick="new NavToggle(this); return false;">Users</a>
				<ul class="subnav" id="menu3">
					<li><a onClick="this.blur();" href="{makeLink mod=store pg=storeuser}act=list{/makeLink}">User List</a></li>
				</ul>
		</li>
		{if $GLOBAL.payment_receiver!='admin'}
			{if $PAYMENT_OWNER=='S'}
				<li id="limenu4" {if  $smarty.request.pg=='store_payment'}class="active"{/if}>
					<a id="menu4" href="#menu4" onClick="new NavToggle(this); return false;">Payment </a>
						<ul class="subnav" id="menu4">
							<li><a onClick="this.blur();" href="{makeLink mod=store pg=store_payment}act=list{/makeLink}">Payment Configuration</a></li>
						</ul>
				</li>	
		{/if}	
	{/if}	
	</ul>
   </div>
 </div>
{popup_init src="`$smarty.const.SITE_URL`/includes/overlib/overlib.js"}
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="26" background="{$GLOBAL.tpl_url}/images/admin_top.gif">
      <table width="98%" height="53" border="0" align="center" cellpadding="1" cellspacing="0">
        <tr>
          <td align="right">
			<div id="topbar">
			  <div id="topbar-container">
				<div id="supplementalNav"><img src="{$GLOBAL.tpl_url}/images/user_go.png" height="16" width="16"><a href="{makeLink mod=store pg=logout}{/makeLink}">Logout</a>&nbsp;<span title="">1.0.0</span>&nbsp; </div>
			  </div>
			</div>
		  </td>
	    </tr><tr>
          <td align="right" valign="bottom"><strong>Welcome {$smarty.session.storeSess[0]->username}</strong></td>
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