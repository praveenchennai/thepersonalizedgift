<?php

include_once("../config.php");

include_once(FRAMEWORK_PATH."/includes/class.framework.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
include_once(FRAMEWORK_PATH."/includes/functions.php");


$objUser	=	new User();
$framework 	= 	new FrameWork();
$email	 	= 	new Email();
$objStore 	= 	new Store();

$members=$objUser->getMembers();

//print_r($members);

if(count($members)){
	$mail_header = array();
	$mail_header['from'] = 	$framework->config['admin_email'];
	$dynamic_vars = array();
	foreach($members as $key=>$val)
	{
		$mail_header["to"]          = $val["email"];
		$dynamic_vars["FIRST_NAME"] = $val["first_name"];
		$dynamic_vars["LAST_NAME"]  = $val["last_name"];
		$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
		$dynamic_vars["STORE_URL"]  =  "<a target='_blank' href=\"".SITE_URL.'/'.$val['name']."\" >".SITE_URL.'/'.$val['name']."</a>";
		$email->send("webstore_deactivation",$mail_header,$dynamic_vars);			
	}
}

$stores=$objUser->getStoreForDeletion();
//print_r($stores);

if(count($stores)>0){
	
	$mail_header = array();
	$dynamic_vars = array();
	$mail_header["to"]          = 	$framework->config['admin_email'];
	$mail_header['from'] 		= 	$framework->config['site_name'];
	$dynamic_vars["SITE_NAME"]  =   $framework->config['site_name'];
	
	foreach($stores as $key=>$value){
	
		$dynamic_vars["STORE_NAME"] 		= $value["name"];
		$dynamic_vars["STORE_HEADING"] 	    = $value["heading"];
		$dynamic_vars["STORE_DESCRIPTION"]  = $value["description"];
		$dynamic_vars["CUSTOMER_NAME"]  	= $value["first_name"].' '.$value["last_name"];
		$dynamic_vars["CUSTOMER_EMAIL"]		= $value["email"];
		$dynamic_vars["CUSTOMER_PHONE"] 	= $value["telephone"];
		$dynamic_vars["CUSTOMER_ADDRESS"]	= $value["first_name"];
		$dynamic_vars["REG_DATE"] 			= $value["regdate"];
		$dynamic_vars["CANCEL_DATE"] 		= $value["cancelDate"];
		$email->send("webstore_cancellation_notice",$mail_header,$dynamic_vars);
		//$objStore->storeDelete($value['id']);
	}
}
?>
