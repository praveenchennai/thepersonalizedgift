<?php
	
	

	set_time_limit(0);
	ini_set("display_errors", "off");
	
	require_once '../config.php';
	
	require_once FRAMEWORK_PATH."/includes/class.framework.php";
	include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
	include_once(FRAMEWORK_PATH."/includes/functions.php");
	
	$framework 	= 	new FrameWork();
	$objUser	= 	new User;
	
	$objUser->upgradeUserSubscription();
	/* new code for send email to the user who expire date is one less than the current date */
	/* Added By Arun */
	$objUser->sendEmailtoExpiredCustomer();

	//added by adarsh for updating the store status to expired when the ipn message from payal is failed
	$objUser->sendEmailtoExpiredStores();
	
?>
