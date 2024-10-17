<?php
	include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
	include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");
		$objAdmin = new Admin();
		$fId=$_REQUEST['fId'];
		$sId=$_REQUEST['sId'];
		$framework->tpl->assign("FIELDS",$objAdmin->GetFields($fId)); 
	    $a = $objAdmin->GetFields($fId);
		$store					= 	new Store();	
		$storeDetails			=	$store->storeGetByName($_REQUEST['storename']);		
		$store_id				=	$storeDetails['id'];
	include(FRAMEWORK_PATH."/modules/product/lib/index.php");
?>