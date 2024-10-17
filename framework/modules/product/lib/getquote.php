<?
if($_REQUEST['manage']=="manage"){
	authorize_store();
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("PG","product_brand") ;
	$framework->tpl->assign("STORE_PERMISSION",$store->getStorePermissionById($store_id,$module_menu_id)) ;
}else{
	authorize();
	$framework->tpl->assign("MOD","product") ;
	$framework->tpl->assign("PG","getquote") ;
	$STORE = array('hide'=>'Y','add'=>'Y','edit'=>'Y','delete'=>'Y');
	$framework->tpl->assign("STORE_PERMISSION",$STORE) ;
}
session_start();

include_once(FRAMEWORK_PATH."/modules/product/lib/class.brand.php");
$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";



switch($_REQUEST['act']) {

	case "getquote_list":
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/getquote_list.tpl");
		break;



}

if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}
?>