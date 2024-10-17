<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="javascript">
{literal}
function disableRightClick(e)
{
  var message = "Right click disabled";
  if(!document.rightClickDisabled) {
    if(document.layers) {
      document.captureEvents(Event.MOUSEDOWN);
      document.onmousedown = disableRightClick;
    }
    else document.oncontextmenu = disableRightClick;
    return document.rightClickDisabled = true;
  }

  if(document.layers || (document.getElementById && !document.all)) {
    if (e.which==2||e.which==3) {
      //alert(message);
      return false;
    }
  } else {
    alert(message);
    return false;
  }
}
disableRightClick();
</script>
{/literal}
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
	<div class="innerlady">{if $WELCOME_MESSAGE_STORE ne ''}<img src="{$GLOBAL.tpl_url}/images/inner_lady{$WELCOME_MESSAGE_STORE.avator}.gif">{else}<img src="{$GLOBAL.tpl_url}/images/inner_lady.gif" />{/if}</div>
       <!--<div class="createpersonalizedgift">{if $WELCOME_MESSAGE_STORE ne ''}<img src="{$smarty.const.SITE_URL}/modules/store/images/{$WELCOME_MESSAGE_STORE.id}.jpg" />{else}<img src="{$GLOBAL.tpl_url}/images/creat-personalized-gifts.jpg" />{/if}</div>-->
	   <div class="createpersonalizedgift"> {if $WELCOME_MESSAGE_STORE.heading1 eq ''}<img src="{$GLOBAL.tpl_url}/images/creat-personalized-gifts.jpg" />{else}<div class="storehead1" align="right">{$WELCOME_MESSAGE_STORE.heading1}<br/><div class="storehead2" align="right">{$WELCOME_MESSAGE_STORE.heading2}</div></div>{/if}</div>
	   {if $MEMBER == ""}
	   <div class="innerlogin">
	   
			   <form name="frlgfail" method="post" action="{makeLink mod=member pg=login newurl='true' sslval='false' storename=$smarty.request.storename}{/makeLink}" style="margin:0px; ">
			   
			   <div style="vertical-align:bottom; padding-top:10px; padding-bottom:2px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Username&nbsp;&nbsp;<input name="txtuname" type="text" class="input" size="20" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Password &nbsp;<input name="txtpswd" type="password" class="input" size="20" />&nbsp;&nbsp;&nbsp;&nbsp;<input type="image" style="vertical-align:middle; " src="{$GLOBAL.tpl_url}/images/go.jpg" ></div>
			<!--<div style="text-align:center; width:450px; "><a href="{makeLink mod=member pg=pswd}{/makeLink}" class="footerlink" ><b>&raquo; Forgot Password ?</b></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="{makeLink mod=member pg=register}act=add_edit{/makeLink}" class="footerlink"><b>&raquo; Register</b></a></div>-->
			<div style="text-align:left; width:450px; ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{if $STOREFLAG eq 'Y'}<a href="{makeLink mod=member pg=register newurl='true' sslval='false' storename=$smarty.request.storename}act=add_edit{/makeLink}" class="footerlink"><b>&raquo; Register</b></a>{else}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{/if}
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="{makeLink mod=member pg=pswd newurl='true' sslval='false' storename=$smarty.request.storename}{/makeLink}" class="footerlink" ><b>&raquo; Forgot Password ?</b></a></div>
			   </form></div>
		{else}<div class="innerlogout">
		<br><br>
			<div style="text-align:right; width:400px; "><a href="{makeLink mod=member pg=logout newurl='true' sslval='false' storename=$smarty.request.storename}{/makeLink}" class="footerlink"><b>&raquo; Logout</b></a></div>
		</div>
		{/if}
			    
	    <div class="innermenubg">
		<div style="float:left; width:100px;">&nbsp;</div>
		<div style="float: left" class="innermenubox">
	     {foreach from=$INNER_TOP_MENU item=row name=topmenu}
			<a href="{$row->url}" onmouseout="document.getElementById('img_{$row->id}').src='{$GLOBAL.modbase_url}/cms/images/{$GLOBAL.curr_tpl}_{$row->id}.{$row->link_image}';" onmouseover="document.getElementById('img_{$row->id}').src='{$GLOBAL.modbase_url}/cms/images/{$GLOBAL.curr_tpl}_m_{$row->id}.{$row->link_image}';"> <img id="img_{$row->id}" src="{$GLOBAL.modbase_url}/cms/images/{$GLOBAL.curr_tpl}_{$row->id}.{$row->link_image}" name="{$row->id}"  border="0" /  ></a>
			{if $smarty.foreach.topmenu.index ne 4}<img src="{$GLOBAL.tpl_url}/images/topmenu_seperator.jpg" />{/if}{/foreach}</div>
<form name="frmSer" method="post" action="{makeLink mod=product pg=search newurl='true' sslval='false' storename=$smarty.request.storename}act=result{/makeLink}" style="margin:0px; ">	
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
    <div class="inner_leftcolumn">
	   <!-- added for shopping cart START-->
   <div >   
	<img src="{$GLOBAL.tpl_url}/images/giftcategories-top.jpg" width="185" border="0" align="absbottom">
	<div style=" padding-left:20px; padding-right:20px;" class="cartbox">Shopping Cart&nbsp;&nbsp;<img src="{$GLOBAL.tpl_url}/images/cart.gif" width="13" height="13" /><br />
<div style="height:5px;"><!-- --></div>
<span class="whitenormaltext" style=" height:30px;">{$CART_BOX->count|string_format:"%d"} item is in your cart</span><br />
<div style="height:5px;"><!-- --></div>
<span class="whitenormaltext" ><a class="whitetext" href="{makeLink mod=cart pg=default newurl='true' sslval='false' storename=$smarty.request.storename}act=view{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/arrow_white.gif" width="7" height="10" align="absbottom" style="border:0" />View Cart</a></span>
<span class="whitenormaltext"><a  class="whitetext" href="{makeLink mod=cart pg=default newurl='true' sslval='false' storename=$smarty.request.storename}act={if $smarty.session.SHIPPING_ADDRESS.method}checkout{else}shipping{/if}{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/arrow_white.gif" width="7" height="10"  align="absbottom"style="border:0" />Check Out</a></span>

 </div>
	<img src="{$GLOBAL.tpl_url}/images/giftcategories-bottom.jpg" width="185" border="0" align="top"> 
</div>
   
   
   <div style="height:7px;"><!-- --></div>
	<!-- added for shopping cart END-->
	
	
	<div id="navvy">
	<img src="{$GLOBAL.tpl_url}/images/giftcategories-top.jpg" width="185" align="top">
	<img src="{$GLOBAL.tpl_url}/images/giftcategories.jpg" >
<ul id="navvylist">
{foreach from=$LEFT_SUB_MENU1 item=row name=left_sub_menu1}
<li><a href="{$row->url}">{$row->title}</a></li>
 {/foreach}

</ul>
<img src="{$GLOBAL.tpl_url}/images/giftcategories-bottom.jpg" width="185" align="absbottom" />
</div>
<img src="{$GLOBAL.tpl_url}/images/spacer.gif" width="8" height="8">
<div id="LMDIV2">
<img src="{$GLOBAL.tpl_url}/images/special-occasions-top.jpg" width="185" align="top">
	<img src="{$GLOBAL.tpl_url}/images/special-occasions.jpg" >
<ul id="LMDIV2list">
{foreach from=$LEFT_SUB_MENU2 item=row name=left_sub_menu2}
<li><a href="{$row->url}"><img src="{$GLOBAL.tpl_url}/images/dot.jpg" width="11" height="10" border="0">&nbsp;{$row->title}</a></li>
{/foreach}
</ul>
<img src="{$GLOBAL.tpl_url}/images/special-occasions-bottom.jpg" width="185" align="absbottom">
</div><br>

</div>
	<div class="inner-rightcolumn">
	 <!-- <h2><img src="{$GLOBAL.tpl_url}/images/latestfeaturedproducts.jpg" width="246" height="18"></h2>-->
	  
	 
	   
	  <div class="article">
	     {if file_exists($main_tpl)}
    {include file="$main_tpl"}
    {/if}
	  </div>
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
  </div></div>

</body>
</html>
