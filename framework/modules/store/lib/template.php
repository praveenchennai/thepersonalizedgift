<?php 
/**
 * Admin Store
 *
 * @author Ajith
 * @package defaultPackage
 */
require_once(FRAMEWORK_PATH."/modules/store/lib/class.template.php");
$template = new Template();
switch($_REQUEST['act']) {
    case "list":
    	$_REQUEST['limit'] = $_REQUEST['limit'] ? $_REQUEST['limit'] : 20;
        list($rs, $numpad, $cnt, $limitList) = $template->templateList($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
     	$framework->tpl->assign("TEMPLATE_LIST", $rs);
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/store/tpl/template_list.tpl");
        break;
    case "form":
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $req = &$_REQUEST;
            if( ($message = $store->storeAddEdit($req)) === true ) {
            	$action = $req['id'] ? "Updated" : "Added";
            	setMessage("Store $action Successfully", MSG_SUCCESS);
                redirect(makeLink(array("mod"=>"store", "pg"=>"index"), "act=list"));
            }
            setMessage($message);
        }
        include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
        $user = new User();

        if($message) {
            $framework->tpl->assign("STORE", $_POST);
            
            if($_POST['member_id']) {
            	$details = $user->getUserdetails($_POST['member_id']);
            	$framework->tpl->assign("OWNER_NAME", $details['first_name']." ".$details['last_name']." (".$details['username'].")");
            }
        } elseif($_REQUEST['id']) {
        	$storeDetails = $store->storeGet($_REQUEST['id']);
            $framework->tpl->assign("STORE", $storeDetails);

            $cat = $store->storeCategoriesGet($_REQUEST['id']);
            $framework->tpl->assign("ALL_CATEGORIES", $cat['all']);
            $framework->tpl->assign("STORE_CATEGORIES", $cat['store']);
            
            if ($storeDetails['user_id']) {
            	$details = $user->getUserdetails($storeDetails['user_id']);
            	$framework->tpl->assign("OWNER_NAME", $details['first_name']." ".$details['last_name']." (".$details['username'].")");
            }
        }
        $cat = $store->storeCategoriesGet($_REQUEST['id']);
        $framework->tpl->assign("ALL_CATEGORIES", $cat['all']);
        $framework->tpl->assign("STORE_CATEGORIES", $cat['store']);
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/store/tpl/store_form.tpl");
        break;
    case "delete":
        $store->storeDelete($_REQUEST['id']);
        setMessage("Store Deleted Successfully!", MSG_SUCCESS);
        redirect(makeLink(array("mod"=>"store", "pg"=>"index"), "act=list"));
        break;
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");

?>