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
	$framework->tpl->assign("PG","newsletter_subscription") ;
	$framework->tpl->assign("STORE_PERMISSION",$store->getStorePermissionById($store_id,$module_menu_id)) ;
}else{
	authorize();
	$framework->tpl->assign("MOD","newsletter") ;
	$framework->tpl->assign("PG","subscription") ;
	$STORE = array('hide'=>'Y','add'=>'Y','edit'=>'Y','delete'=>'Y');
	$framework->tpl->assign("STORE_PERMISSION",$STORE) ;
}

include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.newsletter.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");

$newsletter = new Newsletter();

switch($_REQUEST['act']) {
    case "list":
        list($rs, $numpad) = $newsletter->subscriptionList($_REQUEST, $_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act={$_REQUEST['act']}&orderBy={$_REQUEST['orderBy']}&list_id={$_REQUEST['list_id']}&email={$_REQUEST['email']}&format={$_REQUEST['format']}&confirmed={$_REQUEST['confirmed']}&active={$_REQUEST['active']}", OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("MAILINGLIST", $newsletter->listCombo());
        $framework->tpl->assign("SUBSCRIPTION_LIST", $rs);
        $framework->tpl->assign("SUBSCRIPTION_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/newsletter/tpl/subscription_list.tpl");
        break;
    case "form":
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $req = &$_REQUEST;
            if( ($message = $newsletter->subscriptionAddEdit($req)) === true ) {
            	$action = $req['id'] ? "Updated" : "Added";
            	setMessage("Subscription $action Successfully", MSG_SUCCESS);
                redirect(makeLink(array("mod"=>"newsletter", "pg"=>"subscription"), "act=list"));
            }
            setMessage($message);
        }
        $user = new User();
        if($message) {
            $framework->tpl->assign("SUBSCRIPTION", $_POST);
            $userDetails = $user->getUserdetails($_POST['member_id']);
        } elseif($_REQUEST['id']) {
            $subscription = $newsletter->subscriptionGet($_REQUEST['id']);
            $framework->tpl->assign("SUBSCRIPTION", $subscription);
            $userDetails = $user->getUserdetails($subscription['member_id']);
        }
        if($userDetails) {
            $framework->tpl->assign("MEMBER_NAME", $userDetails['first_name'].' '.$userDetails['last_name'].' ('.$userDetails['username'].')');
        }
        $framework->tpl->assign("MAILINGLIST", $newsletter->listCombo());
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/newsletter/tpl/subscription_form.tpl");
        break;
    case "delete":
        $newsletter->subscriptionDelete($_REQUEST['id']);
        setMessage("Subscription Deleted Successfully!", MSG_SUCCESS);
        redirect(makeLink(array("mod"=>"newsletter", "pg"=>"subscription"), "act=list"));
        break;
}
if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}
?>