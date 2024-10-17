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
  <div class="headerbox" style=" border:0px solid"> <!--header box starts-->
       <div class="logo"> 
        <div class="menubg">
		
			 <!-- top menu starts -->
          	<div class="topmenu">
			{foreach from=$TOP_MENU item=row name=topmenu}
			<div class="toplink" align="justify" ><a href="{$row->url}" onmouseout="document.getElementById('img_{$row->id}').src='{$GLOBAL.modbase_url}/cms/images/{$GLOBAL.curr_tpl}_{$row->id}.{$row->link_image}';" onmouseover="document.getElementById('img_{$row->id}').src='{$GLOBAL.modbase_url}/cms/images/{$GLOBAL.curr_tpl}_m_{$row->id}.{$row->link_image}';"> <img id="img_{$row->id}" src="{$GLOBAL.modbase_url}/cms/images/{$GLOBAL.curr_tpl}_{$row->id}.{$row->link_image}" name="{$row->id}"  border="0" /  ></a></div>
			{/foreach}
			
			
            </div> 
          <!-- top menu ends --> 
         </div> 
     </div>
<div class="lady"><img src="{$GLOBAL.tpl_url}/images/lady{$WELCOME_MESSAGE_STORE.avator}.gif"> </div>
<div class="createpersonalizedgift"> {if $WELCOME_MESSAGE_STORE.heading1 eq ''}<img src="{$GLOBAL.tpl_url}/images/creat-personalized-gifts.jpg" />{else}<div class="storehead1" align="right">{$WELCOME_MESSAGE_STORE.heading1}<br/><div class="storehead2" align="right">{$WELCOME_MESSAGE_STORE.heading2}</div></div>{/if}</div>
<!--<div class="createpersonalizedgift"> <img src="{$GLOBAL.tpl_url}/images/creat-personalized-gifts.jpg" /></div>
--><div class="welcomebox" ><!--welocome box starts-->
<img src="{$GLOBAL.tpl_url}/images/welcome.jpg" width="365" height="22" border="0" />{if $WELCOME_MESSAGE_STORE.description != ''}<div class="welcometext">{$WELCOME_MESSAGE_STORE.description|truncate:200}
 <span class="redtext">
  <a href="{makeLink mod=product pg=site}act=welcome_store&sid={$WELCOME_MESSAGE_STORE.name}{/makeLink}" class="redtext">more1 &raquo;</a></span></div>
  {else}
  <div class="welcometext">{$WELCOME_MESSAGE[0]->content|truncate:200}
 <span class="redtext">
  <a href="welcome.php" class="redtext">more &raquo;</a></span></div>
  {/if}
<div style="width:135px; ">&nbsp;</div>
<div style="width:500px; ">&nbsp;</div>
<!--login box starts-->

<div style="width:500px;" >
{if $MEMBER == ""}
<form name="frlgfail" method="post" action="{makeLink mod=member pg=login}{/makeLink}" style="margin:0px; ">
  <div class="loginbox">
 
     <img src="{$GLOBAL.tpl_url}/images/login.jpg" width="101" height="21" />    <!--search box starts-->
    <br/>
   <div style="float:left; width:130px;">Username</div><div style="float:left; width:100 px; text-align:left"> Password</div><br />
   <div style="float:left; width:130px;"><input name="txtuname" type="text" class="input" size="20" /></div>
   <div style="float:left; width:100px;"><input name="txtpswd" type="password" class="input" size="20" /> 
   </div>
   <div class="space_div">&nbsp;</div> <div class="space_div">&nbsp;</div> <div class="space_div">&nbsp;</div> <div class="space_div">&nbsp;</div>
   <div class="go"><input type="image" src="{$GLOBAL.tpl_url}/images/goo.jpg"></div> 
{if $STOREFLAG eq 'Y'}<div style="float:left; width:130px; height:18px; margin-top:5px;"><a href="{makeLink mod=member pg=register}act=add_edit{/makeLink}" class="footerlink"><b>&raquo;&nbsp;Register</b></a></div>{else}<div style="float:left; width:130px; height:18px; margin-top:5px;"></div>{/if}
   <div style="float:left; height:18px; margin-top:5px;"><a href="{makeLink mod=member pg=pswd}{/makeLink}" class="footerlink"><b>&raquo; Forgot Password ?</b></a></div>   
</div>
</form>
{else}
<div class="loginbox">
 
 
 <br><br><br>
   <div style="float:left; vertical-align:bottom; width:60px;height:18px; margin-top:5px;"><a href="{makeLink mod=member pg=logout}{/makeLink}" class="footerlink"><b>&raquo; Logout</b></a></div>
</div>
{/if}
<form name="frmSer" method="post" action="{makeLink mod=product pg=search}act=result{/makeLink}" style="margin:0px; ">
 <div class="searchbox"><!-- search box starts -->
  <div align="center"><img src="{$GLOBAL.tpl_url}/images/quick-search.jpg" alt="" width="110" height="23" /></div>
  <div align="center">
    <input name="keyword" type="text" class="input" size="20" />
  </div>
 <input type="hidden" name="type" value="normal">
  <div align="center" style="height:30px; margin-top:2px; ">
  <input type="image" src="{$GLOBAL.tpl_url}/images/search.jpg" width="62" height="18">
  <!--<a href="#"><img src="{$GLOBAL.tpl_url}/images/search.jpg" width="62" height="18" border="0" /></a>--></div>
</div>
</form><!-- search box ends -->
</div>
<!--welocome box ends-->
</div>

<!--welocome box ends-->
</div><!--header box ends-->

<div class="bodybox"><!-- body box starts-->
<div class="productsgallery" ><br><img src="{$GLOBAL.tpl_url}/images/letest-featuredproduct.jpg" width="252" height="19" /><br>
<br>
{foreach from=$FEATURED_ITEMS item=item name=foo}

	<div class="home_product"><!-- 1st product-->
	<div class ="productname"><b>{$item.name}</b></div><br>
	{if $item.name eq 'Family Tree Gift'}
	<div class="photoboxbk1">
	<div class="photobox1">
	{else}
	<div class="photoboxbk">
	<div class="photobox">{/if}<a href="{makeLink mod=product pg=list}act=listproduct&id={$item.id}&cat_id={$item.category_id}{/makeLink}">
	<img src="{$GLOBAL.mod_url}product/images/thumb/{$item.id}_List_.{$item.image_extension }" border="0"/></a></div>
	</div>
    <div class="phototext">{$item.description|truncate:60}
      <span><a href="{makeLink mod=product pg=list}act=listproduct&id={$item.id}&cat_id={$item.category_id}{/makeLink}" class="redtext">read more &raquo;</a></span>
     <div class="price" align="left" style="padding-top:8px">
	 {if $item.price ne $item.discount_price}
	 <del style="color:#FF0000"><span class="redtext2" >${$item.price|truncate:5:""}</span></del>&nbsp;&nbsp;
     <span class="redtext3">${$item.discount_price|truncate:5:""}</span><div style="padding-top:5px">&nbsp;&nbsp;&nbsp;&nbsp;<a href="{makeLink mod=product pg=list}act=listproduct&id={$item.id}&cat_id={$item.category_id}{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/buy.jpg" width="61" height="22" border="0" /  align="absmiddle" /></a></div></div>
	 {else}
	 <span class="redtext2" >&nbsp;&nbsp;&nbsp;&nbsp;${$item.price|truncate:5:""}</span>
     <div style="padding-top:5px">&nbsp;&nbsp;&nbsp;&nbsp;<a href="{makeLink mod=product pg=list}act=listproduct&id={$item.id}&cat_id={$item.category_id}{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/buy.jpg" width="61" height="22" border="0" /  align="absmiddle" /></a></div></div>  
	 {/if}
    </div></div><!-- end of 1st product-->
	{if $smarty.foreach.foo.index is div by 2}
	<div style="float:left; width:40px;">&nbsp;</div>
	{/if}
	
  {/foreach}
  </div>
 <div class="rightcolumn" >
   <div class="leftcolumn">
   <!-- added for shopping cart START-->
   <div >   
	<img src="{$GLOBAL.tpl_url}/images/giftcategories-top.jpg" width="185" border="0" align="absbottom">
	<div style=" padding-left:20px; padding-right:20px;" class="cartbox">Shopping Cart&nbsp;&nbsp;<img src="{$GLOBAL.tpl_url}/images/cart.gif" width="13" height="13" /><br />
<div style="height:5px;"><!-- --></div>
<span class="whitenormaltext" style=" height:30px;">{$CART_BOX->count|string_format:"%d"} item is in your cart</span><br />
<div style="height:5px;"><!-- --></div>
<span class="whitenormaltext" ><a class="whitetext" href="{makeLink mod=cart pg=default}act=view{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/arrow_white.gif" width="7" height="10" align="absbottom" style="border:0" />View Cart</a></span>
<span class="whitenormaltext"><a  class="whitetext" href="{makeLink mod=cart pg=default}act={if $smarty.session.SHIPPING_ADDRESS.method}checkout{else}shipping{/if}{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/arrow_white.gif" width="7" height="10"  align="absbottom"style="border:0" />Check Out</a></span>

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
<img src="{$GLOBAL.tpl_url}/images/giftcategories-bottom.jpg" width="185"  align="absbottom">
</div>


<img src="{$GLOBAL.tpl_url}/images/spacer.gif" width="8" height="8">
<div id="LMDIV2">
<img src="{$GLOBAL.tpl_url}/images/special-occasions-top.jpg" width="185" align="top">
	<img src="{$GLOBAL.tpl_url}/images/special-occasions.jpg" >
<ul id="LMDIV2list">
{foreach from=$LEFT_SUB_MENU2 item=row name=left_sub_menu1}
<li><a href="{$row->url}"><img src="{$GLOBAL.tpl_url}/images/dot.jpg" width="11" height="10" border="0">&nbsp;{$row->title}</a></li>
 {/foreach}</ul>
<img src="{$GLOBAL.tpl_url}/images/special-occasions-bottom.jpg" width="185" align="absbottom"></div><br>
</div>
  
  

</div>

<div class="spaceDiv"></div>
	<div class="footerbox">
	{foreach from=$BOTTOM_MENU item=row name=bottommenu}
		{if $row->type_link eq 'images'}<a href="{$row->url}" onmouseout="document.getElementById('bimg_{$row->id}').src='{$GLOBAL.modbase_url}/cms/images/{$GLOBAL.curr_tpl}_{$row->id}.{$row->link_image}';" onmouseover="document.getElementById('bimg_{$row->id}').src='{$GLOBAL.modbase_url}/cms/images/{$GLOBAL.curr_tpl}_m_{$row->id}.{$row->link_image}';"> <img id="bimg_{$row->id}" src="{$GLOBAL.modbase_url}/cms/images/{$GLOBAL.curr_tpl}_{$row->id}.{$row->link_image}" name="bimg_{$row->id}"  border="0" /  ></a>{if $smarty.foreach.bottommenu.index != count($BOTTOM_MENU)-1}<img src="{$GLOBAL.tpl_url}/images/seperator.jpg" width="9" height="18" />{/if}{else}<a href="{$row->url}" class="footerlink" style="vertical-align:top;">{$row->title}</a>{/if}{if $smarty.foreach.bottommenu.index != count($BOTTOM_MENU)-1}&nbsp;|{/if}
	{/foreach}
	<br><span class="footertext">Copyright &copy; {$smarty.now|date_format:"%Y"} All Rights Reserved</span>
	</div>
<div class="spaceDiv"></div>
 <!-- Footer border start-->
<div class="bottomborder"><img src="{$GLOBAL.tpl_url}/images/bottomborder.jpg" /></div>
<!-- Footer border ends-->
  </div><!-- Body box ends-->
   </div>

</body>
</html>
