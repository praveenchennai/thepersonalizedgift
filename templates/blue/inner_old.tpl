<html>
<head>
<title>Personal Touch</title>
<link REL="SHORTCUT ICON" HREF="{$GLOBAL.tpl_url}/images/favicon.ico">
<link rel="ICON" href="{$GLOBAL.tpl_url}/images/favicon.ico" type="image/x-icon" />
<link href="{$GLOBAL.tpl_url}/css/style.css" rel="stylesheet" type="text/css">
<link href="{$GLOBAL.tpl_url}/css/styles.css" rel="stylesheet" type="text/css">
{literal}
<script language="javascript" type="text/javascript">
</script>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
{/literal}
</head>
<body bgcolor="#7b343c" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="MM_preloadImages('{$GLOBAL.tpl_url}/images/home1.jpg','{$GLOBAL.tpl_url}/images/aboutus1.jpg','{$GLOBAL.tpl_url}/images/createanewgift1.jpg','{$GLOBAL.tpl_url}/images/myaccount1.jpg','{$GLOBAL.tpl_url}/images/shoppingcart1.jpg','{$GLOBAL.tpl_url}/images/checkout1.jpg','{$GLOBAL.tpl_url}/images/createanewgift1a.jpg','{$GLOBAL.tpl_url}/images/information1.jpg','{$GLOBAL.tpl_url}/images/quickfind1.jpg','{$GLOBAL.tpl_url}/images/featureditems1.jpg')">
<!-- ImageReady Slices (theuniondshop8_home.psd) -->
<table width="880"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td height="126" colspan="2" align="center" valign="top" border="0">
	{if file_exists("`$smarty.const.SITE_PATH`/templates/`$GLOBAL.curr_tpl`/top_menu.tpl")}{include file="`$smarty.const.SITE_PATH`/templates/`$GLOBAL.curr_tpl`/top_menu.tpl"}{/if}</td>
  </tr>
 <tr>
        <td width="175" height="100%" valign="top">{if file_exists("`$smarty.const.SITE_PATH`/templates/`$GLOBAL.curr_tpl`/left.tpl")}{include file="`$smarty.const.SITE_PATH`/templates/`$GLOBAL.curr_tpl`/left.tpl"}{/if}</td>
	
	
	 <td height="100%" valign="top">{include file="`$smarty.const.SITE_PATH`/templates/`$GLOBAL.curr_tpl`/right.tpl}
	 
	 {if file_exists($main_tpl)}
    {include file="$main_tpl"}
    {/if}
	
	{include file="`$smarty.const.SITE_PATH`/templates/`$GLOBAL.curr_tpl`/right_down.tpl}
	
	</td>
	
  </tr>
  
  
 
  
</table>

</body>
</html>