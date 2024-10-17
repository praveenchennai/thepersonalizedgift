<?php
	
	# This page hadles the ajax request for shipping services related with a particular shipping method
	
	include_once(FRAMEWORK_PATH."/modules/order/lib/class.shipping.php");
	include_once(FRAMEWORK_PATH."/modules/cart/lib/class.cart.php");
	require_once(FRAMEWORK_PATH."/modules/order/lib/shippingconfig.php");
	
	$ShippingObj	=	new Shipping();
	$CartObj		= 	new Cart();
	
	$shipmethod_id	=	$_REQUEST['shipmethod_id'];
	$country		=	$_REQUEST['country'];
	$postalcode		=	$_REQUEST['postalcode'];
	$store_name		=	(trim($_REQUEST['store_name']) != '') ? $_REQUEST['store_name'] : '0';
	$ship_state		=	$_REQUEST['ship_state'];

	//$weight 		= 	1;
	//echo $weight;
	$weight=0;
	//$weight			=	(($weight == '') || ($weight <= 0)) ? 0.001 : $weight;
	$crt 	= 	$CartObj->getCart();
	foreach($crt as $rec){
	  	foreach ($rec as $ro){
			$weight=($weight+($ro->quantity*$ro->height*$ro->width*0.0007));
		}
	}
	$weight			=	round($weight);
	$country_2code	=	trim($ShippingObj->getCountry2code($country));
	
	$InternlMessage	=	'';
	if($country_2code != $ShippingObj->config['product_shipping_country'])
		$InternlMessage	=	$ShippingObj->getInternationalOrderMessage($shipmethod_id, $store_name, $country);
	
	$Methods		=	array();
	$Methods		=	$ShippingObj->getAllShippingServicesOfShippingMethod($shipmethod_id, $country, $postalcode, $store_name, $weight, $ship_state);

	$OptionsJs		=	'var shipservices = document.frmOrderform.shipping_service;';
	$OptionsJs		.=	'for (i = shipservices.options.length; i >= 0; i--){ shipservices.options[i] = null; }';
	if(count($Methods) > 0) {
		foreach($Methods as $Method) {
			$OptionsJs		.=	"shipservices.options[shipservices.options.length] = new Option('{$Method['label']}','{$Method['dbvalue']}');";
		}
	}	
	
	if(count($Methods) == 0)
		$OptionsJs		.=	"shipservices.options[shipservices.options.length] = new Option('-- No Services Found --','');";
	
	$ReturnHTML	=	$OptionsJs.'*^*^*'.$InternlMessage;
	
	print $ReturnHTML;
	exit;

?>