<?php
	require_once 'class.ups.php';
	
	$UpsObj	=	new Ups();
	
	$Params		=	array();
	$Params['src_zip']			=	'08033';
	$Params['dst_zip']			=	'90210';
	$Params['weight']			=	120;
	$Params['src_country']		=	'US';
	$Params['dst_country']		=	'US';
	
	$UpsDetails		=	array();
	$UpsDetails['servicevalue']	=	'1DAL';
	$UpsDetails['upsType']		=	'Regular+Daily+Pickup';
	$UpsDetails['upsPackage']	=	'00';
	
	$Cost	=	$UpsObj->getUPSQuote($Params, $UpsDetails);
	print_r($Cost);

?>