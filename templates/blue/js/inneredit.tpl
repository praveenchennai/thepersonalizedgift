<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Personal Touch</title>
<link type="text/css" rel="stylesheet" href="{$GLOBAL.tpl_url}/css/style-div.css"/>
</head>

<body>

  <div id="wrapper">
  <!-- top border ends--><div class="topborder"><img src="{$GLOBAL.tpl_url}/images/topborder.jpg" /></div><!-- top border starts-->
  <div class="innerheader"> <!--header box starts-->
    <div class="innerlogo"></div>
	<!--<div class="innerlady"></div>-->
	<div class="innerlady">{if $WELCOME_MESSAGE_STORE ne ''}<img src="{$GLOBAL.tpl_url}/images/lady{$WELCOME_MESSAGE_STORE.avator}.gif" width="108" height="139">{else}<img src="{$GLOBAL.tpl_url}/images/lady_inner.jpg" />{/if}</div>
       <!--<div class="createpersonalizedgift">{if $WELCOME_MESSAGE_STORE ne ''}<img src="{$smarty.const.SITE_URL}/modules/store/images/{$WELCOME_MESSAGE_STORE.id}.jpg" />{else}<img src="{$GLOBAL.tpl_url}/images/creat-personalized-gifts.jpg" />{/if}</div>-->
	   <div class="createpersonalizedgift"><img src="{$GLOBAL.tpl_url}/images/creat-personalized-gifts.jpg" /></div>
	   {if $MEMBER == ""}
	   <div class="innerlogin">
	   
			   <form name="frlgfail" method="post" action="{makeLink mod=member pg=login}{/makeLink}" style="margin:0px; ">
			   
			   <div style="vertical-align:bottom; padding-top:10px; padding-bottom:2px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Username&nbsp;&nbsp;<input name="txtuname" type="text" class="input" size="20" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Password &nbsp;<input name="txtpswd" type="password" class="input" size="20" />&nbsp;&nbsp;&nbsp;&nbsp;<input type="image" style="vertical-align:middle; " src="{$GLOBAL.tpl_url}/images/go.jpg" ></div>
			<!--<div style="text-align:center; width:450px; "><a href="{makeLink mod=member pg=pswd}{/makeLink}" class="footerlink" ><b>&raquo; Forgot Password ?</b></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="{makeLink mod=member pg=register}act=add_edit{/makeLink}" class="footerlink"><b>&raquo; Register</b></a></div>-->
			<div style="text-align:left; width:450px; ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{if $STOREFLAG eq 'Y'}<a href="{makeLink mod=member pg=register}act=add_edit{/makeLink}" class="footerlink"><b>&raquo; Register</b></a>{else}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{/if}
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="{makeLink mod=member pg=pswd}{/makeLink}" class="footerlink" ><b>&raquo; Forgot Password ?</b></a></div>
			   </form></div>
		{else}<div class="innerlogout">
		<br><br>
			<div style="text-align:right; width:400px; "><a href="{makeLink mod=member pg=logout}{/makeLink}" class="footerlink"><b>&raquo; Logout</b></a></div>
		</div>
		{/if}
			    
	    <div class="innermenubg">
		<div style="float:left; width:100px;">&nbsp;</div>
		<div style="float: left" class="innermenubox">
	     {foreach from=$INNER_TOP_MENU item=row name=topmenu}
			<a href="{$row->url}" onmouseout="document.getElementById('img_{$row->id}').src='{$GLOBAL.modbase_url}/cms/images/{$GLOBAL.curr_tpl}_{$row->id}.{$row->link_image}';" onmouseover="document.getElementById('img_{$row->id}').src='{$GLOBAL.modbase_url}/cms/images/{$GLOBAL.curr_tpl}_m_{$row->id}.{$row->link_image}';"> <img id="img_{$row->id}" src="{$GLOBAL.modbase_url}/cms/images/{$GLOBAL.curr_tpl}_{$row->id}.{$row->link_image}" name="{$row->id}"  border="0" /  ></a>
			{if $smarty.foreach.topmenu.index ne 4}<img src="{$GLOBAL.tpl_url}/images/topmenu_seperator.jpg" />{/if}{/foreach}</div>
<form name="frmSer" method="post" action="{makeLink mod=product pg=search}act=result{/makeLink}" style="margin:0px; ">	
		 <div class="innersearchbox">
	   <div style="float:left; width:130px;">
	     <input name="keyword" type="text" class="input" size="20" />
  </div>
 <input type="hidden" name="type" value="normal">
      <div style="float:left; width:65px;">
        <input type="image" src="{$GLOBAL.tpl_url}/images/search_inner.gif" width="62" height="18"> </div>
		</div>
</form>
		</div>
		
	   
</div><!--header box ends-->

<div class="bodybox"><!-- body box starts-->

<div class="content">
    
	
	 <!-- <h2><img src="{$GLOBAL.tpl_url}/images/latestfeaturedproducts.jpg" width="246" height="18"></h2>-->
	  
	 
	   
	  <div class="article">
	     {if file_exists($main_tpl)}
    {include file="$main_tpl"}
    {/if}
	  
	</div>
	<div class="clear"></div>
  </div>
  <div align="center" style="padding-left:10px; padding-right:10px; ">
<div class="spaceDiv"></div>
	<div class="footerbox">
	{foreach from=$BOTTOM_MENU item=row name=bottommenu}
		<a href="{$row->url}" onmouseout="document.getElementById('bimg_{$row->id}').src='{$GLOBAL.modbase_url}/cms/images/{$GLOBAL.curr_tpl}_{$row->id}.{$row->link_image}';" onmouseover="document.getElementById('bimg_{$row->id}').src='{$GLOBAL.modbase_url}/cms/images/{$GLOBAL.curr_tpl}_m_{$row->id}.{$row->link_image}';"> <img id="bimg_{$row->id}" src="{$GLOBAL.modbase_url}/cms/images/{$GLOBAL.curr_tpl}_{$row->id}.{$row->link_image}" name="bimg_{$row->id}"  border="0" /  ></a>{if $smarty.foreach.bottommenu.index != count($BOTTOM_MENU)-1}<img src="{$GLOBAL.tpl_url}/images/seperator.jpg" width="9" height="18" />&nbsp;{/if}
	{/foreach}
	<br>
		<span class="footertext">Copyright @ 2007  All rights are reserved</span>
	</div>
<div class="spaceDiv"></div>

</div><!-- Body box ends-->


 <!-- Footer border start-->
<div class="bottomborder"><img src="{$GLOBAL.tpl_url}/images/bottomborder.jpg" /></div>
<!-- Footer border ends-->
  </div>
  
  
  
  </div>

</body>
</html>
