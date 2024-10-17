<?php
include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
$store			= 	new Store();
$storeDetails	=	$store->storeGetByName($_REQUEST['storename']);
$store_id		=	$storeDetails['id'];
$framework->tpl->assign("STORE_ID", $store_id);
if($_SERVER['REQUEST_METHOD'] == "POST") {
	include_once(FRAMEWORK_PATH."/modules/store/lib/class.storelogin.php");	
	$login = new Storelogin($_POST['username'],$_POST['password'],$_POST['store_id']);

	if($login->authenticate()) {		
		 redirect(makeLink(array("mod"=>"store", "pg"=>"storeIndex")));
	} else {
		setMessage($login->errorMessage);
	}

			//echo "<pre>";
			//print_r($_SESSION['storeSess']);
	
}
$framework->tpl->display($global['curr_tpl']."/store_login.tpl");
?>