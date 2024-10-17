<?php
	
	/**
	 *	The following are the Test card details provided by Authorize.NET In test mode the Transaction Id always 0
	 *	370000000000002 - American Express Test Card
	 *	6011000000000012 - Discover Test Card
	 *	5424000000000015 - MasterCard Test Card
	 *	4007000000027 - Visa Test Card
	 *	4012888818888 - Visa Test Card II
	 *	3088000000000017 - JCB Test Card (Use expiration date 0905)
	 *	38000000000006 - Diners Club/Carte Blanche Test (Use expiration date 0905) 
	 */
	
	require_once 'class.authnet.php';
	
	$creditcard	=	'370000000000002';
	$expiration	=	'0707';			#Concatenated string of 2digit representation of month and Year
	$total		=	'12000';
	
	$AuthnetObj		=	new Authnet();
	$AuthnetObj->transaction($creditcard, $expiration, $total); 
	$AuthnetObj->process(); 
	
	print_r($AuthnetObj->results); print "<br><br>";
	
	if($AuthnetObj->isApproved())
		print "Approved -------> Transaction Id : {$AuthnetObj->getTransactionID()}";
	else if($AuthnetObj->isDeclined())
		print "Declined--------> Response : {$AuthnetObj->getResponseText()}";	
	else 	
		print "Declined--------> Response : {$AuthnetObj->getResponseText()}";	
	


?>