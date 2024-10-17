<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$PAGE_TITLE}</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="keywords" content="{$META_KEYWORD}" />
<meta name="description" content="{$META_DESCRIPTION}" />
<link type="text/css" rel="stylesheet" href="{$GLOBAL.tpl_url}/css/style-div.css"/>
<script language="javascript">
{literal}
function disableRightClick(e)
{
  {/literal}
  var message = "{$MOD_VARIABLES.MOD_COMM.COMM_SITE_PROTECT_MESSAGE}";//Right click disabled
  {literal}
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
      alert(message);
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
{$ANALYTICS_CODE}
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
<div class="createpersonalizedgift"> {if $WELCOME_MESSAGE_STORE.heading1 eq ''}<img src="{$GLOBAL.tpl_url}/images/creat-personalized-gifts.jpg" />{else}<div class="storehead1" align="right" >{$WELCOME_MESSAGE_STORE.heading1}<br/><div class="storehead2" align="right">{$WELCOME_MESSAGE_STORE.heading2}</div></div>{/if}</div>
<!--<div class="createpersonalizedgift"> <img src="{$GLOBAL.tpl_url}/images/creat-personalized-gifts.jpg" /></div>
--><div class="welcomebox" ><!--welocome box starts-->
<img src="{$GLOBAL.tpl_url}/images/welcome.jpg" width="365" height="22" border="0" />{if $WELCOME_MESSAGE_STORE.description != ''}<div class="welcometext">{$WELCOME_MESSAGE_STORE.description|truncate:200}
 <span class="redtext">
  {if strlen($WELCOME_MESSAGE_STORE.description)>200}
  <a href="{makeLink mod=product pg=site}act=welcome_store&sid={$WELCOME_MESSAGE_STORE.name}{/makeLink}" class="redtext">{$MOD_VARIABLES.MOD_COMM.COMM_MORE} &raquo;</a>{/if}</span></div>
  {else}
  <div class="welcometext">{$WELCOME_MESSAGE[0]->content|truncate:200}
 <span class="redtext">
  {if strlen($WELCOME_MESSAGE[0]->content)>200}<a href="welcome.php" class="redtext">{$MOD_VARIABLES.MOD_COMM.COMM_MORE} &raquo;</a>{/if}</span></div>
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
   <div style="float:left; width:130px;">{$MOD_VARIABLES.MOD_LABELS.LBL_USER_NAME}</div><div style="float:left; width:100 px; text-align:left"> {$MOD_VARIABLES.MOD_LABELS.LBL_PASSWORD}</div><br />
   <div style="float:left; width:130px;"><input name="txtuname" type="text" class="input" size="20" /></div>
   <div style="float:left; width:100px;"><input name="txtpswd" type="password" class="input" size="20" /> 
   </div>
   <div class="space_div">&nbsp;</div> <div class="space_div">&nbsp;</div> <div class="space_div">&nbsp;</div> <div class="space_div">&nbsp;</div>
   <div class="go" ><input type="image" src="{$GLOBAL.tpl_url}/images/goo.jpg"></div> 
<div style="float:left; width:50px; height:18px; margin-top:5px"><a href="{makeLink mod=member pg=register}act=add_edit{/makeLink}" class="footerlink"><b>&raquo;&nbsp;{$MOD_VARIABLES.MOD_COMM.COMM_CMN_REGISTER_TOP}</b></a></div><div style="float:left; width:80px; height:18px; margin-top:5px;"></div><div style="float:left; height:18px; margin-top:5px"><a href="{makeLink mod=member pg=pswd}{/makeLink}" class="footerlink"><b>&raquo; {$MOD_VARIABLES.MOD_COMM.COMM_CMN_FORGOT_PASSWORD_TOP}</b></a></div>   
</div>
</form>
{else}
<div class="loginbox">
 
 
 <br><br><br>
   <div style="float:left; vertical-align:bottom; width:60px;height:18px; margin-top:5px;"><a href="{makeLink mod=member pg=logout}{/makeLink}" class="footerlink"><b>&raquo; {$MOD_VARIABLES.MOD_COMM.COMM_TOP_LOGOFF}</b></a></div>
</div>
{/if}
<form name="frmSer" method="post" action="{makeLink mod=product pg=search}act=result{/makeLink}" style="margin:0px; ">
<!-- search box starts-->
 <div class="searchbox">
  <div align="center"><img src="{$GLOBAL.tpl_url}/images/quick-search.jpg" alt="" width="110" height="23" /></div>
  <div align="center">
    <input name="keyword" type="text" class="input" size="20" />
  </div>
 <input type="hidden" name="type" value="normal">
  <div align="center" style="height:30px; margin-top:2px; ">
  <input type="image" src="{$GLOBAL.tpl_url}/images/search.jpg" width="62" height="18">
 </div>
</div> 

 <!--<a href="#"><img src="{$GLOBAL.tpl_url}/images/search.jpg" width="62" height="18" border="0" /></a>-->
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
	<div class="photobox">{/if}{if $item.gift eq 'pgift'}<a href="{makeLink mod=product pg=list}act=pg_details_product&id={$item.id}&cat_id={$item.category_id}&product_id={$item.pid}&parent_cat_id={$item.category}{/makeLink}"><img src="{$GLOBAL.mod_url}ajax_editor/images/thumb/{$item.id}_1.{$item.image_extension }" class="bordermat"/></a>{else}<a href="{makeLink mod=product pg=list}act=listproduct&id={$item.id}&cat_id={$item.category_id}{/makeLink}">
	<img src="{$GLOBAL.mod_url}product/images/thumb/{$item.id}_List_.{$item.image_extension }" class="bordermat"/></a>{/if}</div>
	</div>
    <div class="phototext"  style="height:120px;overflow:hidden">
	<div style="height:60px;overflow:hidden">
	{$item.description|truncate:50}
      <span><a href="{if $item.gift eq 'pgift'}{makeLink mod=product pg=list}act=pg_details_product&id={$item.id}&cat_id={$item.category_id}&product_id={$item.pid}&parent_cat_id={$item.category}{/makeLink}{else}{makeLink mod=product pg=list}act=listproduct&id={$item.id}&cat_id={$item.category_id}{/makeLink}{/if}" class="redtext">{$MOD_VARIABLES.MOD_COMM.COMM_READ_MORE} &raquo;</a></span>
	  </div>
     <div class="price" align="left" style="padding-top:8px">
	 {if $item.price ne $item.discount_price}
	 <del style="color:#FF0000"><span class="redtext2" >{$CURRENCY_SYMBOL}{$item.price|truncate:9:""}</span></del>&nbsp;&nbsp;
     <span class="redtext3">{$CURRENCY_SYMBOL}{$item.discount_price|truncate:9:""}</span><div style="padding-top:5px">&nbsp;&nbsp;&nbsp;&nbsp;<a href="{if $item.gift eq 'pgift'}{makeLink mod=product pg=list}act=pg_details_product&id={$item.id}&cat_id={$item.category_id}&product_id={$item.pid}&parent_cat_id={$item.category}{/makeLink}{else}{makeLink mod=product pg=list}act=listproduct&id={$item.id}&cat_id={$item.category_id}{/makeLink}{/if}"><img src="{$GLOBAL.tpl_url}/images/buy.jpg" width="61" height="22" border="0" /  align="absmiddle" /></a></div></div>
	 {else}
	 <span class="redtext2" >&nbsp;&nbsp;&nbsp;&nbsp;{$CURRENCY_SYMBOL}{$item.price|truncate:9:""}</span>
     <div style="padding-top:5px">&nbsp;&nbsp;&nbsp;&nbsp;<a href="{if $item.gift eq 'pgift'}{makeLink mod=product pg=list}act=pg_details_product&id={$item.id}&cat_id={$item.category_id}&product_id={$item.pid}&parent_cat_id={$item.category}{/makeLink}{else}{makeLink mod=product pg=list}act=listproduct&id={$item.id}&cat_id={$item.category_id}{/makeLink}{/if}"><img src="{$GLOBAL.tpl_url}/images/buy.jpg" width="61" height="22" border="0" /  align="absmiddle" /></a></div></div>  
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
	<div style=" padding-left:20px; padding-right:20px;" class="cartbox">{$MOD_VARIABLES.MOD_LABELS.LBL_SHOPPING_CART}&nbsp;&nbsp;<img src="{$GLOBAL.tpl_url}/images/cart.gif" width="13" height="13" /><br />
<div style="height:5px;"><!-- --></div>
<span class="whitenormaltext" style=" height:30px;">{$CART_BOX->count|string_format:"%d"} {$MOD_VARIABLES.MOD_LABELS.LBL_ITEMS_CART}</span><br />
<div style="height:5px;"><!-- --></div>
<span class="whitenormaltext" ><a class="whitetext" href="{makeLink mod=cart pg=default}act=view{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/arrow_white.gif" width="7" height="10" align="absbottom" style="border:0" />{$MOD_VARIABLES.MOD_COMM.COMM_VIEW_CART}</a></span>
<span class="whitenormaltext"><a  class="whitetext" href="{makeLink mod=cart pg=default}act={if $smarty.session.SHIPPING_ADDRESS.method}checkout{else}shipping{/if}{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/arrow_white.gif" width="7" height="10"  align="absbottom"style="border:0" />{$MOD_VARIABLES.MOD_COMM.COMM_CHECK_OUT}</a></span>

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
<li style=" border-spacing:0px"><a href="{$row->url}">{$row->title}</a></li>
 {/foreach}
<li style=" border-spacing:0px"><a href="{makeLink mod=product pg=list url_encode=1}act=other_gifts&cat_id=306{/makeLink}">Other Gifts</a></li>
</ul>
<img src="{$GLOBAL.tpl_url}/images/giftcategories-bottom.jpg" width="185"  align="absbottom">
</div>


<img src="{$GLOBAL.tpl_url}/images/spacer.gif" width="8" height="8">
<div id="LMDIV2">
<img src="{$GLOBAL.tpl_url}/images/special-occasions-top.jpg" width="185" align="top">
	<img src="{$GLOBAL.tpl_url}/images/special-occasions.jpg" >
<ul id="LMDIV2list">
{foreach from=$CATEGORY_PGIFT item=row name=left_sub_menu1}
<li><a href="{makeLink mod=product pg=list url_encode=1}act=pg_list&cat_id={$row->category_id}{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/dot.jpg" width="11" height="10" border="0">&nbsp;{$row->category_name}</a></li>
 {/foreach}
<li style=" border-spacing:0px"><a href="{makeLink mod=product pg=list url_encode=1}act=other_gifts&cat_id=306{/makeLink}"><img src="{$GLOBAL.tpl_url}/images/dot.jpg" width="11" height="10" border="0">&nbsp;Other Gifts</a></li>
</ul>
<img src="{$GLOBAL.tpl_url}/images/special-occasions-bottom.jpg" width="185" align="absbottom"></div><br>
</div>
  
  

</div>

<div class="spaceDiv"></div>
	<div class="footerbox"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="175" align="right">
<!-- START SCANALERT CODE -->
 <div class="mcDateLogo">
	<div class="mcDateStamp" align="center">{$MOD_VARIABLES.MOD_LABELS.LBL_VERIFIED} <span>{$LOGO_DAY}-{$LOGO_MONTH}</span></div>
</div>
<!-- END SCANALERT CODE -->

</td>
    <td width="601" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%" height="42" align="center" valign="top" class="footer2">{foreach from=$BOTTOM_MENU item=row name=bottommenu}
		{if $row->type_link eq 'images'}<a href="{$row->url}" onmouseout="document.getElementById('bimg_{$row->id}').src='{$GLOBAL.modbase_url}/cms/images/{$GLOBAL.curr_tpl}_{$row->id}.{$row->link_image}';" onmouseover="document.getElementById('bimg_{$row->id}').src='{$GLOBAL.modbase_url}/cms/images/{$GLOBAL.curr_tpl}_m_{$row->id}.{$row->link_image}';"> <img id="bimg_{$row->id}" src="{$GLOBAL.modbase_url}/cms/images/{$GLOBAL.curr_tpl}_{$row->id}.{$row->link_image}" name="bimg_{$row->id}"  border="0" /  ></a>{if $smarty.foreach.bottommenu.index != count($BOTTOM_MENU)-1}<img src="{$GLOBAL.tpl_url}/images/seperator.jpg" width="9" height="18" />{/if}{else}<a href="{$row->url}" class="footerlink" style="vertical-align:top;">{$row->title}</a>{/if}{if $smarty.foreach.bottommenu.index != count($BOTTOM_MENU)-1}&nbsp;|{/if}
	{/foreach}
	<br><span class="footertext">{$MOD_VARIABLES.MOD_COMM.COMM_COPY_RIGHTS} {$smarty.now|date_format:"%Y"} {$MOD_VARIABLES.MOD_COMM.COMM_COPY_ALL_RIGHTS}</span></td>
      </tr>
      <tr>
        <td height="33" align="center"><img src="{$GLOBAL.tpl_url}/images/cards.jpg" border="0" alt="" width="249" height="31" /></td>
      </tr>
    </table></td>
    <td width="174" align="left"><a href="http://www.copyright.gov" target="_blank"><img src="{$GLOBAL.tpl_url}/images/csite.jpg" alt="" width="98" height="44" border="0" /></a></td>
  </tr>
</table></div>
<div class="spaceDiv" style="height:10px"></div>
 <!-- Footer border start-->
<div class="bottomborder"><img src="{$GLOBAL.tpl_url}/images/bottomborder.jpg" /></div>
<!-- Footer border ends-->
  </div><!-- Body box ends-->
   </div>

</body>
</html>
