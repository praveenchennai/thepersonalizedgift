<html>
<head>
<title>Join The Industry Page.com </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="{$smarty.const.SITE_URL}/templates/{$smarty.session.currTemplate|default:"default"}/css/style.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.blogbody {ldelim}
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;	
	{$PAGE_BG}
{rdelim}
.siteheading  {ldelim}
	FONT-WEIGHT:bold;	
	TEXT-DECORATION: none;
	line-height: 16px;
	{$HEADER_TEXT}
{rdelim}
.sitetag  {ldelim}
	FONT-WEIGHT:bold;	
	TEXT-DECORATION: none;
	line-height: 16px;
	{$TAG_TEXT}
{rdelim}
.leftblogBorder{ldelim}
	{$LEFT_BORDER}	
{rdelim}
.leftmenuheading  {ldelim}
	FONT-WEIGHT:bold;
	FONT-SIZE: 12px;
	FONT-FAMILY:  Arial, Helvetica, sans-serif;
	TEXT-DECORATION: none;
	line-height: 16px;
	{$LEFT_BG}
{rdelim}
.lefinterior{ldelim}
	FONT-WEIGHT: normal; 
	FONT-SIZE: 12px;	
	FONT-FAMILY: Arial; 
	TEXT-DECORATION: none
	{$LEFT_INTERIOR}
{rdelim}
.leftmenulink{ldelim}
	FONT-WEIGHT: normal; 
	FONT-SIZE: 12px; 
	COLOR: #000000; 
	FONT-FAMILY: Arial; 
	TEXT-DECORATION: none
{rdelim}
.leftmenulink:visited{ldelim}
	FONT-WEIGHT: normal; 
	FONT-SIZE: 12px; 
	COLOR: #000000; 
	FONT-FAMILY: Arial; 
	TEXT-DECORATION: none
{rdelim}
.leftmenulink:hover{ldelim}
	FONT-WEIGHT: normal; 
	FONT-SIZE: 12px; 
	COLOR: #666666; 
	FONT-FAMILY: Arial; 
	TEXT-DECORATION: none
{rdelim}
.leftmenulink:active{ldelim}
	FONT-WEIGHT: normal; 
	FONT-SIZE: 12px; 
	COLOR: #333333; 
	FONT-FAMILY: Arial; 
	TEXT-DECORATION: none
{rdelim}

.navigationlink{ldelim}
	FONT-WEIGHT: normal; 
	FONT-SIZE: 10px; 
	COLOR: #000000; 
	FONT-FAMILY: Arial; 
	TEXT-DECORATION: none
{rdelim}
.navigationlink:visited{ldelim}
	FONT-WEIGHT: normal; 
	FONT-SIZE: 10px; 
	COLOR: #000000; 
	FONT-FAMILY: Arial; 
	TEXT-DECORATION: none
{rdelim}
.navigationlink:hover{ldelim}
	FONT-WEIGHT: normal; 
	FONT-SIZE: 10px; 
	COLOR: #666666; 
	FONT-FAMILY: Arial; 
	TEXT-DECORATION: none
{rdelim}
.navigationlink:active{ldelim}
	FONT-WEIGHT: normal; 
	FONT-SIZE: 10px; 
	COLOR: #333333; 
	FONT-FAMILY: Arial; 
	TEXT-DECORATION: none
{rdelim}
.bodytext {ldelim}
	FONT-WEIGHT: normal;	 
	TEXT-DECORATION: none;
	{$BODY_TEXT}
{rdelim}
.bodyuserHeadertext {ldelim}
	FONT-WEIGHT: normal;	 
	TEXT-DECORATION: none;
	{$USER_HEADER}
{rdelim}
.bodyheadingtext {ldelim}
	FONT-WEIGHT: bold; 
	FONT-SIZE: 12px; 
	COLOR: #000000; 
	FONT-FAMILY: Arial; 
	TEXT-DECORATION: none
{rdelim}
.blogMiddlelink {ldelim}
	FONT-WEIGHT: normal; 
	{$TEXT_LINK}
{rdelim}
.blogMiddlelink:visited {ldelim}
	FONT-WEIGHT: normal; 
	{$TEXT_VIST}
{rdelim}
.blogMiddlelink:hover {ldelim}
	FONT-WEIGHT: normal; 
	{$TEXT_HOVER}
{rdelim}
.blogMiddlelink:active {ldelim}
	FONT-WEIGHT:normal; 
	{$TEXT_ACTIVE}
{rdelim}
.blogBorder{ldelim}
	border:1px solid #b5b5b6;
{rdelim}
.headerBorder{ldelim}
{$HEADER_BORDER}
{rdelim}
.blogMaintable{ldelim}
	{$TABLE_BG}
{rdelim}
.blogMainBody{ldelim}	
	{$PAGE_MAINBODY}
{rdelim}
.blogSectionBoxColor{ldelim}
	{$PAGE_SECTIONBOX}
{rdelim}
--> 
</style>
</head>
<body class="blogbody" bgcolor="#d0cece">
<BGSOUND SRC="{$MUSIC.filepath}" LOOP=5>
<table  width="{$PAGEWIDTH}" height="100%" border="0"  cellpadding="0" cellspacing="0" align="{$PAGEALIGN}" class="blogMaintable" >
  <tr>
    <td width="100%" height="134" valign="top">
	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#DEDEDE">
        <tr>
          <td><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="100%" height="78" bgcolor="White">
                  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="800" height="80">
                    <param name="movie" value="{$smarty.const.SITE_URL}/templates/{$smarty.session.currTemplate|default:"default"}/images/logo.swf">
                    <param name="quality" value="high">
                    <embed src="{$smarty.const.SITE_URL}/templates/{$smarty.session.currTemplate|default:"default"}/images/logo.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="800" height="80"></embed>
                  </object>
                </td>
              </tr>
          </table>
		   {include file="`$smarty.const.SITE_PATH`/templates/default/topmenu.tpl"}
		  </td>
        </tr>
		    
 
  <tr>
    <td height="100%" valign="top">{if file_exists($main_tpl)} {include file="$main_tpl"} {/if}</td>
  </tr>
  <tr>
    <td height="20" valign="top">	
	<table width="100%" height="30"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#dedede">
      <tr>
        <td width="100%" height="30" align="center" valign="middle">
          <span class="smalltext">Copyright TheIndustryPage &copy; 2006 </span><br>
	  </td>
      </tr>
    </table>
	</td>
  </tr>
</table>
</body>
</html>