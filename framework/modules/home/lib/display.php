<?php
session_start();
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
$cms = new Cms();
extract($_REQUEST);
//print_r($_REQUEST);exit;
if($data) {
	$menuRS = $cms->menuGetByURL($data);
	$menu   = $menuRS['id'];
	$cms_page = $cms->GetCMSpage($menu);
	$framework->tpl->assign("row",$cms_page[0]);
}

	$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/cms/tpl/display.tpl");
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
?>