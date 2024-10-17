<?php	
session_start();
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
$objUser=new User();
$email	 = new Email();
$objStore	=	new Store();
global $store_id;


if ($_REQUEST["username"] || $_REQUEST["email"])
{
	$usr=$objUser->getUserPass($_REQUEST["username"],$_REQUEST["email"],$store_id,$_REQUEST['manage'],$_REQUEST['storename'])	;
	if($usr)
	{
		$mail_header = array();
		if($store_id){
			$storeDetails = $objStore->storeGet($store_id);
			if($storeDetails['email'] != ''){
				$mail_header['from'] = 	$storeDetails['email'];
			}	
			else{
				$res = $objStore->GetStoreOwnerEmailByStoreId($store_id);
	  			$mail_header['from'] 	= 	$res[0]['email'];
		   }	
		}
		else
		$mail_header['from'] 	= 	$framework->config['admin_email'];
		
		$mail_header["to"]   = $usr["email"];
		$dynamic_vars = array();
		$dynamic_vars["USER_NAME"]  = $usr["username"];
		$dynamic_vars["PASSWORD"]  = $usr["password"];
		$dynamic_vars["FIRST_NAME"] = $usr["first_name"];
		$dynamic_vars["LAST_NAME"]  = $usr["last_name"];
			if(!empty($store_id)){
				$store	=	$objStore->storeGet($store_id);
				$dynamic_vars["SITE_NAME"]  = $store['name'];
			}
			else{
				$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
			}
		$email->send("forgot_password",$mail_header,$dynamic_vars);
		/*
		$from=$framework->config['site_name']."(".$framework->config['admin_email'].")";
		$to=$_REQUEST["email"];
		$subject="Password Information";
		$msg="<html><body>";
		$msg=$msg."<strong>".$framework->config['site_name']." Password Information</strong><br><br>";
		$msg=$msg."Username: ".$usr["username"]."<br>";
		$msg=$msg."Password: ".$usr["password"];
		$msg=$msg."</body></html>";
		sendMail($to,$subject,$msg,$from,'HTML');
		*/
		$framework->tpl->assign("SEND","1");
	}
	else
	{
		if($framework->config['registration_unique_field']!='')
		{
			$message = "Invalid Input";
			
		}
		else
		{
			$message = "Invalid username or Email";
			setMessage($message);
		}
		$framework->tpl->assign("MESSAGE",$message);
		//setMessage($message);
	}

}
else
{
	if($_SERVER['REQUEST_METHOD']=='POST')
	{
		$message = "Invalid username or Email";
		setMessage($message);
	}	
}
$framework->tpl->assign("TITLE_HEAD","Forgot Password");

$framework->tpl->assign("SUBMITBUTTON1", createImagebutton_Div("Submit","#","submit_form()"));
$framework->tpl->assign("CANCEL", createImagebutton_Div("Cancel","#","history.go(-1)"));
$framework->tpl->assign("RESETBUTTON1", createImagebutton("Cancel","#","history.go(-1)"));

$framework->tpl->assign("SUBMITBUTTON", createButton("SUBMIT","#","check()"));
$framework->tpl->assign("RESETBUTTON", createButton("CANCEL","#","history.go(-1)"));
	if($_REQUEST['manage']=="manage"){
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/pswd_store_manage.tpl");
	}
	else{
	$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/pswd.tpl");
	}
if($global["inner_change_reg"]=="yes"){
	$framework->tpl->display($global['curr_tpl']."/inner_ch.tpl");
	exit;
}
	
if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store3.tpl");
}else{
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
}	
?>