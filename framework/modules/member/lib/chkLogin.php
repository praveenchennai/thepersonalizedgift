<?php	
session_start();
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user_db2.php");
include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");

$email	 = new Email();
$db2User=new Userdb2();
$admin   = new Admin();


if($_REQUEST["act"]=="y" && SHOW_FORMS=="Y" && $_SERVER['REQUEST_METHOD']=="POST")
{
		
	$req = &$_REQUEST;
	$user_det = $db2User->getUserdetails($req["uid"]);
	$mail_header = array();
	$mail_header["from"] = $framework->config['site_name'].'<'.$framework->config['admin_email'].'>';
	$mail_header["to"]   = $user_det["email"];
	$myId = $user_det["id"];
	$dynamic_vars = array();

	$dynamic_vars["USER_NAME"]  = $user_det["username"];
	$dynamic_vars["FIRST_NAME"] = $user_det["first_name"];
	$dynamic_vars["LAST_NAME"]  = $user_det["last_name"];
	$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
	$dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";
	$email->send("registration_confirmation",$mail_header,$dynamic_vars);
	setMessage("An activation link has been sent to {$user_det['email']}",MSG_SUCCESS);								
}
elseif ($_SERVER['REQUEST_METHOD']=="POST" && $_REQUEST["act"]!="NEW")
{
	
	
	$db2User->ip_det = array("country"=>$_POST['country'],"city"=>$_POST["city"]);
	
	$_SESSION['email_confirm'] = $global['email_confirm'];
	
	# Modified : Ratheesh KK for showing inactive messge  while an inactive user trying to login 
	$checkRS = $db2User->chkUserAuthentication($_POST["txtuname"],$_POST["txtpswd"]);
	
	if ( (count($checkRS)>0))
	{
		if($checkRS[0]->active =='1')
		{
			$_SESSION["memberid"]=$checkRS[0]->id;
			if ($_REQUEST["url"]){
			 	redirect($_REQUEST["url"]);	
			}else{
			 	redirect(makeLink(array('mod'=>"member",'pg'=>"userHome")));
			}
			
		}
		else
		{
			$chekvar="inactive";
			$myId = $checkRS[0]->id;
		}
	}
	else
	{  
	    $chekvar=1;
		//setMessage($db2User->getErr(),MSG_INFO);
		
	}
}
else if($_REQUEST["act"]=="NEW"){

			if($_SERVER['REQUEST_METHOD'] == "POST") {
            $req = &$_REQUEST;
			$arr["username"]=$_REQUEST["username"];
				$arr["password"]=$_REQUEST["password"];
				$arr["email"]=$_REQUEST["email"];				
				$db2User->setArrData($arr);
            	$myId=$db2User->insert();				
				setMessage($db2User->getErr());
				if($myId)
				{
									$mail_header = array();
									$mail_header["from"] = $framework->config['site_name'].'<'.$framework->config['admin_email'].'>';
									$mail_header["to"]   = $arr["email"];
									//$myId = $user_det["id"];
									$dynamic_vars = array();
									$dynamic_vars["USER_NAME"]  = $arr["username"];
									$dynamic_vars["FIRST_NAME"] = $arr["first_name"];
									$dynamic_vars["LAST_NAME"]  = $arr["last_name"];
									$dynamic_vars["PASS_WORD"]  = $arr["password"];
									$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
									$dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";
									$email->send("registration_confirmation",$mail_header,$dynamic_vars);
	
					//redirect(makeLink(array("mod"=>"member", "pg"=>"login"),"act=y"));
				}
	}
}else
{

//
	if($_REQUEST["act"]=="y")
	{
		$framework->tpl->assign("ACT", "y");
		//print_r($global["projectname"]);
	if($global["inner_change_reg"]=="yes")
	                {	
	
	}else
	{
	//$store    = $db2User->getStore($_REQUEST["user_id"]);
	}
	$framework->tpl->assign("URL", $store);
	//print_r($_SESSION);

		if(SHOW_FORMS=="Y")
		{
			setMessage(" Congratulations!<br>Your Account has been set up successfully. We have sent you an email with your account information. Please verify your  E-Mail address to complete the registration.",MSG_INFO);
			//$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/loginfirst.tpl");
		}else{
		if($_REQUEST['thnx']!='Y'){
				setMessage("Account Created Successfully. An activation link has been sent to your email.
		Please click on that link to activate this account. If you have not received an activation email please check your junk or spam mail folder",MSG_INFO);}
		}
	}
	if($_REQUEST["fn"]=="active"){	
		$db2User->makeActive($_REQUEST["user_id"]);
		$framework->tpl->assign("ACT", "active");
		setMessage("Your account has been activated successfully.Please Login");
	}
	
	if ( !empty($_SESSION['memberid']) ) {	  	
		//$_SESSION['RefererPath']='';
		if(SHOW_FORMS=="Y")
		{
			redirect(makeLink(array('mod'=>"member",'pg'=>"home")));
		}
		elseif( $global['payment_tpl'] == 'popup' ) 
		{
			redirect($domainpath);
		}
		redirect(makeLink(array('mod'=>"member",'pg'=>"home")));
	}
}

$framework->tpl->assign("LOGINBUTTON", createButton("LOGIN","#","check()"));
$framework->tpl->assign("SUBMITBUTTON", createButton("SUBMIT","#","check()"));
$framework->tpl->assign("RESETBUTTON", createButton("CANCEL","#","history.go(-1)"));
$framework->tpl->assign("SUBMITBUTTON1", createImagebutton_Div("Submit","#","submit_form()"));
$framework->tpl->assign("CANCEL", createImagebutton_Div("Cancel","#","history.go(-1)"));


if($_REQUEST["act"]=="firstreg"){
	$myId = $_REQUEST["user_id"];
	$framework->tpl->assign("REG_TYPE", "first");
	$framework->tpl->assign("MEMB_ID", $myId);
	$framework->tpl->display($global['curr_tpl']."/index.tpl");
	exit;
}
if($_REQUEST["act"]=="scus"){
	$myId = $_REQUEST["user_id"];
	$framework->tpl->assign("REG_TYPE", "sucs");
	$framework->tpl->assign("MEMB_ID", $myId);
	$framework->tpl->display($global['curr_tpl']."/index.tpl");
	exit;
}

if($_REQUEST["fn"]=="activeparent"){
		$db2User->makeParentActive($_REQUEST["user_id"]);
		$framework->tpl->assign("ACT", "active");
		setMessage("Your account has been activated successfully.");
	}

if($_REQUEST["act"]=="y" && SHOW_FORMS=="Y")
		{
		//print_r($_SESSION["memberid"]);exit;
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/loginfirst.tpl");
			//$framework->tpl->assign("SENDACTIVATIONLINK", createButton("SENDACTIVATIONLINK","#",//"history.go(-1)"));
	}
#######################This is for personalizedgift to redirect after registered for store thru /setup
	 elseif($_REQUEST['thnx']=='Y'){
	 
	$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/thnx_setup.tpl");
	} 
######################
	else{
	
			//$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/loginfail.tpl");
			//redirect(makeLink(array('mod'=>"member",'pg'=>"index")));
						redirect(makeLink(array("mod"=>"member", "pg"=>"index"),"act=login&ch=$chekvar&myId=$myId"));	
	}
	if($global["inner_change_reg"]=="yes"){
	            $framework->tpl->assign("USER_LIST", $rs);
				$framework->tpl->display($global['curr_tpl']."/inner_ch.tpl");
				exit;
			}
			
			
$framework->tpl->display($global['curr_tpl']."/inner.tpl");

?>