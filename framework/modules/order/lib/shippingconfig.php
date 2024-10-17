<?php

	# This file contains the constants related with the shipping process
	
	define('Ups', 'Ups');
	define('UpsId', '5');
	
	define('Usps', 'Usps');
	define('UspsId', '6');
	
	define('Fedex', 'Fedex');
	define('FedexId', '7');
	
	define('AusPost', 'AustraliaPost');
	define('AusPostId', '11');

	define('canadaPost', 'canadaPost');
	define('canadaPostId', '8');
	
	define('DHL', 'DHL');
	define('DHLId', '9');

	define('InterShipper', 'InterShipper');
	define('InterShipperId', '10');
		
	define('LocalPickUp', 'LocalPickUp');
	define('LocalPickUpId', '12');
	define('VALUE_SEPERATOR', '^**^');  # Never Change this seperator, it will affetc all the existing configuration

	define('USPS_MACHINABLE','FALSE'); 	# TRUE, FALSE, ALL only for Parcel Post Machinable applicable
	
	define('INTERNATIONAL_ORDER_MESSAGE','We will contact you to determine which shipping method will either be the most economical or that will ensure the items arrive by a specified date. We will not charge you any shipping fees until we have contacted you. We feel this is the best way to handle our custom process and provide you with both great service and an efficient/cost effective International shipping method. If the shipping options available are not acceptable to you for any reason, we will issue a prompt refund of your purchase.');

?>