<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$PAGE_TITLE}</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="keywords" content="{$META_KEYWORD}" />
<meta name="description" content="{$META_DESCRIPTION}" />
<link type="text/css" rel="stylesheet" href="{$GLOBAL.tpl_url}/css/style-div.css"/>
<link href="{$GLOBAL.tpl_url}/css/popgeneral.css" rel="stylesheet" type="text/css">
{literal}
<style type="text/css">
<!--

#noclick {

	opacity: 0.5;

	filter:alpha(opacity=40);

	-moz-opacity: 0.3;

	-khtml-opacity: 0.5;

	width:100%;

	height:100%;

	position:absolute;

	display:none;

	background:#000000;

	z-index:2500;

	left:0;

	top:0;

}
-->
</style>
{/literal}
<script language="javascript">
{literal}
function disableRightClick(e)
{
{/literal}
  var message = "{$MOD_VARIABLES.MOD_COMM.COMM_SITE_PROTECT_MESSAGE}";{literal}
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
function disableCtrlKeyCombination(e)
{
        var key;
        var isCtrl;

        if(window.event)
        {
                key = window.event.keyCode;     //IE
                if(window.event.ctrlKey)
                        isCtrl = true;
                else
                        isCtrl = false;
        }
        else
        {
                key = e.which;     //firefox
                if(e.ctrlKey)
                        isCtrl = true;
                else
                        isCtrl = false;
        }

        //if ctrl is pressed check if other key is in forbidenKeys array
        if(isCtrl)
        {	
			alert('CTRL key has been disabled.');
            return false;
        }
        return true;
}

</script>
{/literal}
{$ANALYTICS_CODE}
</head>

<body onkeypress="return disableCtrlKeyCombination(event);" onkeydown="return disableCtrlKeyCombination(event);" >
<div id="noclick"  ></div>
  <div id="wrapper">
  <!-- top border ends--><div class="topborder"><img src="{$GLOBAL.tpl_url}/images/topborder.jpg" /></div><!-- top border starts-->
  <div class="innerheader"> <!--header box starts-->
    <div class="innerlogo"></div>
	<!--<div class="innerlady"></div>-->
	<div class="innerlady">{if $WELCOME_MESSAGE_STORE ne ''}<img src="{$GLOBAL.tpl_url}/images/lady{$WELCOME_MESSAGE_STORE.avator}.gif" width="108" height="139">{else}<img src="{$GLOBAL.tpl_url}/images/lady_inner.jpg" />{/if}</div>
       <!--<div class="createpersonalizedgift">{if $WELCOME_MESSAGE_STORE ne ''}<img src="{$smarty.const.SITE_URL}/modules/store/images/{$WELCOME_MESSAGE_STORE.id}.jpg" />{else}<img src="{$GLOBAL.tpl_url}/images/creat-personalized-gifts.jpg" />{/if}</div>-->
	   <!-- <div class="createpersonalizedgift"><img src="{$GLOBAL.tpl_url}/images/creat-personalized-gifts.jpg" /></div> -->
	    <div class="createpersonalizedgift"> {if $WELCOME_MESSAGE_STORE.heading1 eq ''}<img src="{$GLOBAL.tpl_url}/images/creat-personalized-gifts.jpg" />{else}<div class="storehead1" align="right">{$WELCOME_MESSAGE_STORE.heading1}
<br/><div class="storehead2" align="right">{$WELCOME_MESSAGE_STORE.heading2}</div></div>{/if}</div>
	   {if $MEMBER == ""}
	   <div class="innerlogin">
	   
			   <form name="frlgfail" method="post" action="{makeLink mod=member pg=login}{/makeLink}" style="margin:0px; ">
			   
			   <div style="vertical-align:bottom; padding-top:10px; padding-bottom:2px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$MOD_VARIABLES.MOD_COMM.COMM_CMN_USERNAME_TOP}&nbsp;&nbsp;<input name="txtuname" type="text" class="input" size="20" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$MOD_VARIABLES.MOD_COMM.COMM_CMN_PASSWORD_TOP} &nbsp;
			     <input name="txtpswd" type="password" class="input" size="20" />&nbsp;&nbsp;&nbsp;&nbsp;<input type="image" style="vertical-align:middle; " src="{$GLOBAL.tpl_url}/images/goo.jpg" ></div>
			<!--<div style="text-align:center; width:450px; "><a href="{makeLink mod=member pg=pswd}{/makeLink}" class="footerlink" ><b>&raquo; Forgot Password ?</b></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="{makeLink mod=member pg=register}act=add_edit{/makeLink}" class="footerlink"><b>&raquo; Register</b></a></div>-->
			<div style="text-align:left; width:450px; ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{if $STOREFLAG eq 'Y'}<a href="{makeLink mod=member pg=register}act=add_edit{/makeLink}" class="footerlink"><b>&raquo; {$MOD_VARIABLES.MOD_COMM.COMM_CMN_REGISTER_TOP}</b></a>{else}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{/if}
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="{makeLink mod=member pg=pswd}{/makeLink}" class="footerlink" ><b>&raquo; {$MOD_VARIABLES.MOD_COMM.COMM_CMN_FORGOT_PASSWORD_TOP}</b></a></div>
			   </form></div>
		{else}<div class="innerlogout">
		<br><br>
			<div style="text-align:right; width:400px; "><a href="{makeLink mod=member pg=logout}{/makeLink}" class="footerlink"><b>&raquo; {$MOD_VARIABLES.MOD_COMM.COMM_TOP_LOGOFF}</b></a></div>
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
	     <input name="keyword" type="text" class="input" size="20" value="{$smarty.request.keyword}" />
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
	<div class="footerbox"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="175" align="right"><!-- START SCANALERT CODE --> <div class="mcDateLogo">
	<div class="mcDateStamp" align="center">{$MOD_VARIABLES.MOD_COMM.COMM_VERIFIED} <span>{$LOGO_DAY}-{$LOGO_MONTH}</span></div>
</div> <!-- END SCANALERT CODE --></td>
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
<div class="spaceDiv"></div>

</div><!-- Body box ends-->


 <!-- Footer border start-->
<div class="bottomborder"><img src="{$GLOBAL.tpl_url}/images/bottomborder.jpg" /></div>
<!-- Footer border ends-->
  </div>
  
  
  
  </div>
  	<div id="popupRegister" style="display:none;">
			
			<img src="{$GLOBAL.tpl_url}/images/loading07.gif" width="400" height="192"/>
			
		</div>
<div id="backgroundPopup"></div>

</body>
{$EDITOR_INIT}
</html>
