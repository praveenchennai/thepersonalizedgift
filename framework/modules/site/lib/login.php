<?php
include_once(FRAMEWORK_PATH."/modules/site/lib/class.site.php");
	$site					=	new Site();
	$siteDetails			=	$site->getSiteByName($_REQUEST['sitename']);		
	$site_id				=	$siteDetails['id'];
if($_SERVER['REQUEST_METHOD'] == "POST") {		
	$login = new Site($_POST['username'],$_POST['password']);
	if($login->authenticate()) {					
		 redirect(makeLink(array("mod"=>"site", "pg"=>"home")));
	} else {	
		setMessage($login->errorMessage);
	}
}
$framework->tpl->display($global['curr_tpl']."/site_login.tpl");
?>