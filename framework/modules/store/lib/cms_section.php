<?php
	include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
	include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");
		$objAdmin = new Admin();
		 $fId=$_REQUEST['fId'];
		 $sId=$_REQUEST['sId'];
		$framework->tpl->assign("FIELDS",$objAdmin->GetFields($fId)); 
	 
		$store					= 	new Store();	
		$storeDetails			=	$store->storeGetByName($_REQUEST['storename']);		
		$store_id				=	$storeDetails['id'];
		
		 $menu                   =   $objAdmin->getMenuById($fId);
		 $module_menu_id         =   $menu['id']   ;
	     
	$module_permission = $store->ModulePermission($store_id);
	$framework->tpl->assign("MODULE_PERMISSION", $module_permission);
	$menu_permission = $store->MenuPermission($store_id);
	$framework->tpl->assign("MENU_PERMISSION", $menu_permission);

	
	include(FRAMEWORK_PATH."/modules/cms/lib/section.php");
?>