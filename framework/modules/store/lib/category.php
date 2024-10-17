<?php
	include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
	include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");
		$objAdmin = new Admin();
		$fId=$_REQUEST['fId'];
		$sId=$_REQUEST['sId'];
		$framework->tpl->assign("FIELDS",$objAdmin->GetFields($fId)); 
	    $menu                   =   $objAdmin->getMenuById($fId);
        $module_menu_id         =   $menu['id']   ;
	$store					= 	new Store();	
	$storeDetails			=	$store->storeGetByName($_REQUEST['storename']);		
	$store_id				=	$storeDetails['id'];
	include(FRAMEWORK_PATH."/modules/category/lib/index.php");
?>