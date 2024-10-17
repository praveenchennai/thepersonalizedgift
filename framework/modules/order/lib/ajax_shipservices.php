<?php
	
	# This page hadles the ajax request for shipping services related with a particular shipping method
	
	require_once(FRAMEWORK_PATH."/modules/order/lib/class.shipping.php");
	require_once(FRAMEWORK_PATH."/modules/cart/lib/class.cart.php");
	require_once(FRAMEWORK_PATH."/modules/order/lib/shippingconfig.php");
	
	$ShippingObj	=	new Shipping();
	$CartObj		= 	new Cart();
	
	$shipmethod_id	=	$_REQUEST['shipmethod_id'];
	$country		=	$_REQUEST['country'];
	$postalcode		=	$_REQUEST['postalcode'];
	$store_name		=	(trim($_REQUEST['store_name']) != '') ? $_REQUEST['store_name'] : '0';
	$ship_state		=	$_REQUEST['ship_state'];

	$weight 		= 	$CartObj->calculateWeight();
	$weight			=	(($weight == '') || ($weight <= 0)) ? 0.001 : $weight; #0.001
	
	$Methods		=	array();
	$Methods		=	$ShippingObj->getAllShippingServicesOfShippingMethod($shipmethod_id, $country, $postalcode, $store_name, $weight, $ship_state);
	
	$OptionsJs		=	'var shipservices = document.frmReg.shipping_service;';
	$OptionsJs		.=	'for (i = shipservices.options.length; i >= 0; i--){ shipservices.options[i] = null; }';
	if(count($Methods) > 0) {
		foreach($Methods as $Method) {
			$OptionsJs		.=	"shipservices.options[shipservices.options.length] = new Option('{$Method['label']}','{$Method['dbvalue']}');";
		}
	}	
	
	if(count($Methods) == 0)
		$OptionsJs		.=	"shipservices.options[shipservices.options.length] = new Option('-- No Services Found --','');";
	
	print $OptionsJs;
	exit;
?>