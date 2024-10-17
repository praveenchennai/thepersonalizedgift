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
	
?>
