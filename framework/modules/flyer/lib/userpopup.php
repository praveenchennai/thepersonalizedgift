<?php
session_start();
switch($_REQUEST['act']) {
	case "html_code":
	$widget_id	=	$_REQUEST['widget_id'];
	$widget_jsCode	=	"<script src=\"".SITE_URL."/index.php?gadId=".$widget_id."\"></script>";
	$framework->tpl->assign("WIDGET_JSCODE", $widget_jsCode);
	$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/pop_jscode.tpl");
	break;
	
	default: break;	
	

} # Close switch statement

$framework->tpl->display($global['curr_tpl']."/userPopup.tpl");


?>