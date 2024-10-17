<?
session_start();
include_once(FRAMEWORK_PATH."/modules/sitemap/lib/class.sitemap.php");
$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$objsitemap	=	new Sitemap(); 
switch($_REQUEST['act']) {
	case "list":		
		$arr		=	array();
		$linkarr	=	array();
		$storeArr	=	array();
		$framework->tpl->assign('SITEURL',SITE_URL);
		$objsitemap->getCategoryTree($catArr);				
		$framework->tpl->assign('SITEMAP',$catArr);		
		$objsitemap->linkSelectionList($linkArr);	
		$framework->tpl->assign("LINKS", $linkArr);
		$framework->tpl->assign("CMS_LIST", $objsitemap->sectionMenuCombo());
		$objsitemap->listStore($storeArr);
		$framework->tpl->assign("STORE",$storeArr);		
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/sitemap/tpl/sitemap.tpl");
	break;	
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>