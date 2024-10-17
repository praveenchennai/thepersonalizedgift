<?php
/**
* Class Advertiser 
* Author   : 
* Created  : 20/Nov/2007
* Modified : 20/Nov/2007 By Aneesh Aravindan
*/
include_once(FRAMEWORK_PATH."/modules/advertiser/lib/class.advertiser.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.payment.php");

$objAdvertiser	=	new Advertiser();
$PaymentObj		=	new Payment();


switch($_REQUEST['act']) {
	case 'advertise_payment_notify':
		/*	 * transaction id in case of successful payment	*/
		$EncodedSessionId	=	$_REQUEST['EncodedSessionId'];
		$PaymentObj->decodeSession($EncodedSessionId);
						
		$AdvSessId			=	$_SESSION['AdvSessId'];
		$MemberId			=	$_SESSION['memberid'];

	if ($objAdvertiser->saveAdvertisementInfo($_POST, $AdvSessId,$MemberId, $PaymentObj) === 'TRUE'){
		
		$objAdvertiser->advertisement_publish_mail($AdvSessId,$MemberId);
	}	
		
		break;	
	
}