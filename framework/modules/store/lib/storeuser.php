<?php 
/**
 * Admin Store
 *
 * @author Ajith
 * @package defaultPackage
 */
 include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
	$store					= 	new Store();	
	$storeDetails			=	$store->storeGetByName($_REQUEST['storename']);		
	$store_id				=	$storeDetails['id'];
	authorize_store();
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
$user = new User();
	switch($_REQUEST['act']) {   
		case "list":
			list($rs, $numpad) = $user->userList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy'],'','store_id',$store_id);
			$framework->tpl->assign("ACTIVE", "");
			$framework->tpl->assign("USER_LIST", $rs);
			$framework->tpl->assign("USER_NUMPAD", $numpad);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/store/tpl/storeuser_list.tpl");
        break; 
		case "view":
			$framework->tpl->assign("USER", $user->getUserDetails($_REQUEST["id"]));		
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/store/tpl/storeuser_form.tpl");
        break;
	}

	if($_REQUEST['manage']=="manage"){
		$framework->tpl->display($global['curr_tpl']."/store.tpl");
	}else{
		$framework->tpl->display($global['curr_tpl']."/admin.tpl");
	}


?>