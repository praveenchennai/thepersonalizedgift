<?php
/**
 * Art
 *
 * @author ajith
 * @package defaultPackage
 */
authorize();
include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
$store = new Store();
include_once(FRAMEWORK_PATH."/modules/product/lib/class.art.php");
switch($_REQUEST['act']) {
	$act		=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
	$pageNo 	= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
	$orderBy 	= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "art_name";
	$limit		=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
	$param		=	"mod=$mod&pg=$pg&act=$act&orderBy=$orderBy";
    case "list":
    	list($rs, $numpad, $cnt, $limitList)	= 	$objBrand->listAllArt($pageNo,$limit,$param,OBJECT, $orderBy);
		$framework->tpl->assign("ART_LIST", $rs);
		$framework->tpl->assign("ART_NUMPAD", $numpad);
		$framework->tpl->assign("ART_LIMIT", $limitList);
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/art_list.tpl");
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
$framework->tpl->display($global['curr_tpl']."/admin.tpl");
?>