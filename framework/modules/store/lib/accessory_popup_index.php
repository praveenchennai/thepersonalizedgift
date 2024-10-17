<?php

//exit;
	include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
	include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");
	
		$objAdmin = new Admin();
		$fId=$_REQUEST['fId'];
		$sId=$_REQUEST['sId'];
		
		$framework->tpl->assign("FIELDS",$objAdmin->GetFields($fId)); 
		/*
		** This to disable the fields for manage store
		** If the config field 'fields_store_manage' is set to yes
		** SALIM
		*/
		if($framework->config['fields_store_manage'] == 'yes'){
			$framework->tpl->assign("STOREFIELD",$objAdmin->getStoreEditable($fId)); 
		}
		
	    $menu                   =   $objAdmin->getMenuById($fId);
        $module_menu_id         =   $menu['id']   ;
		$store					= 	new Store();	
		$storeDetails			=	$store->storeGetByName($_REQUEST['storename']);		
		$store_id				=	$storeDetails['id'];
		
	
	$module_permission = $store->ModulePermission($store_id);
	$framework->tpl->assign("MODULE_PERMISSION", $module_permission);
	
	$menu_permission = $store->MenuPermission($store_id);
	$framework->tpl->assign("MENU_PERMISSION", $menu_permission);
    //print_r($_SESSION['storeSess']);
	
	include(FRAMEWORK_PATH."/modules/product/lib/accessory_popup.php");
?>