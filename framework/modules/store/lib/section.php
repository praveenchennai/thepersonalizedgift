<?php
	include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
	$store					= 	new Store();	
	$storeDetails			=	$store->storeGetByName($_REQUEST['storename']);		
	$store_id				=	$storeDetails['id'];
	include_once(FRAMEWORK_PATH."/modules/cms/lib/section.php");
?>