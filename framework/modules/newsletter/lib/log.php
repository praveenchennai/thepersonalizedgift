<?php 
/**
 * log
 *
 * @author sajith
 * @package defaultPackage
 */

//error_reporting(0);
if($_REQUEST['manage']=="manage"){
	authorize_store();
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("PG","newsletter_log") ;
	$framework->tpl->assign("PG_RESUME","newsletter_newsletter") ;
    $framework->tpl->assign("STORE_PERMISSION",$store->getStorePermissionById($store_id,$module_menu_id)) ;
}else{
	authorize();
	$framework->tpl->assign("MOD","newsletter") ;
	$framework->tpl->assign("PG","log") ;
	$framework->tpl->assign("PG_RESUME","newsletter") ;
	$STORE = array('hide'=>'Y','add'=>'Y','edit'=>'Y','delete'=>'Y');
	$framework->tpl->assign("STORE_PERMISSION",$STORE) ;
}

include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.newsletter.php");

$newsletter = new Newsletter();

switch($_REQUEST['act']) {
    case "list":
        list($rs, $numpad) = $newsletter->logList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&orderBy=".$_REQUEST['orderBy'], OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("LOG_LIST", $rs);
        $framework->tpl->assign("LOG_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/newsletter/tpl/log_list.tpl");
        break;
    case "detail":
        list($rs, $numpad, $cnt, $limitList) = $newsletter->logDetailList($_REQUEST['id'], $_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&orderBy=".$_REQUEST['orderBy']."&id=".$_REQUEST['id'], OBJECT, $_REQUEST['orderBy']);
        if($rs == 0){
		list($rs, $numpad, $cnt, $limitList) = $newsletter->logDetailListRegUsers($_REQUEST['id'], $_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&orderBy=".$_REQUEST['orderBy']."&id=".$_REQUEST['id'], OBJECT, $_REQUEST['orderBy']);
		}
		$framework->tpl->assign("LOG_LIST", $rs);
        $framework->tpl->assign("LOG_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/newsletter/tpl/log_detail_list.tpl");
        break;
    case "delete":
        $newsletter->logDelete($_REQUEST['id']);
        setMessage("Log Entry Deleted Successfully!", MSG_SUCCESS);
        redirect(makeLink(array("mod"=>"newsletter", "pg"=>"log"), "act=list"));
        break;
}
if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}
?>