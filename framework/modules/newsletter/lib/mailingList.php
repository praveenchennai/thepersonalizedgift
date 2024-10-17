<?php 
/**
 * Newsletter
 *
 * @author sajith
 * @package defaultPackage
 */
 error_reporting(0);
if($_REQUEST['manage']=="manage"){
	authorize_store();
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("PG","newsletter_mailingList") ;
	$framework->tpl->assign("STORE_PERMISSION",$store->getStorePermissionById($store_id,$module_menu_id)) ;
}else{
	authorize();
	$framework->tpl->assign("MOD","newsletter") ;
	$framework->tpl->assign("PG","mailingList") ;
	$STORE = array('hide'=>'Y','add'=>'Y','edit'=>'Y','delete'=>'Y');
	$framework->tpl->assign("STORE_PERMISSION",$STORE) ;
}




include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.newsletter.php");

$newsletter = new Newsletter();

switch($_REQUEST['act']) {
    case "list":
        list($rs, $numpad) = $newsletter->mailingListList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&orderBy=".$_REQUEST['orderBy'], OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("MAILINGLIST_LIST", $rs);
        $framework->tpl->assign("MAILINGLIST_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/newsletter/tpl/mailingList_list.tpl");
        break;
    case "form":
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $req = &$_REQUEST;
            if( ($message = $newsletter->mailingListAddEdit($req)) === true ) {
            	$action = $req['id'] ? "Updated" : "Added";
            	setMessage("Mailing List $action Successfully", MSG_SUCCESS);
                redirect(makeLink(array("mod"=>"newsletter", "pg"=>"mailingList"), "act=list"));
            }
            setMessage($message);
        }
        if($message) {
            $framework->tpl->assign("MAILINGLIST", $_POST);
        } elseif($_REQUEST['id']) {
            $framework->tpl->assign("MAILINGLIST", $newsletter->mailingListGet($_REQUEST['id']));
        }
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/newsletter/tpl/mailingList_form.tpl");
        break;
    case "delete":
        $newsletter->mailingListDelete($_REQUEST['id']);
        setMessage("Mailing List Deleted Successfully!", MSG_SUCCESS);
        redirect(makeLink(array("mod"=>"newsletter", "pg"=>"mailingList"), "act=list"));
        break;
}
if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}
?>