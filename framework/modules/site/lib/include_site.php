<?php
	include_once(FRAMEWORK_PATH."/modules/site/lib/class.site.php");
	$site					=	new Site();
	$siteDetails			=	$site->getSiteByName($_REQUEST['sitename']);		
	$site_id				=	$siteDetails['id'];
	authorize_site();
?>	