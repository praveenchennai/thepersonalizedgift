<?php	
session_start();
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/cart/lib/class.cart.php");
include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");
include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");

$email	 = new Email();
$objUser = new User();
$admin   = new Admin();
$store   = new Store();
//print_r($_REQUEST);exit;

//print_r($framework->MOD_VARIABLES['MOD_LABELS']['LBL_CONTACTADD']);

//====================
if($_SESSION['RefererPath'] ==  '' && $_SERVER['HTTP_REFERER'] != '') {
	$_SESSION['RefererPath']	=	$_SERVER['HTTP_REFERER'];
}
if ($_SESSION['RefererPath'] != '') {
	$domainpath = $_SESSION['RefererPath'];
}
//====================
$userPopup = "popup";
$_SESSION["userPopup"] = $userPopup;
if($_REQUEST["act"]=="y" && SHOW_FORMS=="Y" && $_SERVER['REQUEST_METHOD']=="POST")
{
	$req = &$_REQUEST;
	$user_det = $objUser->getUserdetails($req["uid"]);
	$mail_header = array();
	$mail_header["from"] = $framework->config['site_name'].'<'.$framework->config['admin_email'].'>';
	$mail_header["to"]   = $user_det["email"];
	$myId = $user_det["id"];
	$dynamic_vars = array();
	$dynamic_vars["USER_NAME"]  = $user_det["username"];
	$dynamic_vars["FIRST_NAME"] = $user_det["first_name"];
	$dynamic_vars["LAST_NAME"]  = $user_det["last_name"];
	$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
	if ($_REQUEST["storename"]!="")
	{
		$dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".$_REQUEST["storename"]."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";
	}
	else
	{
		$dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";
	}

	$email->send("registration_confirmation",$mail_header,$dynamic_vars);
	setMessage("An activation link has been sent to {$user_det['email']}",MSG_SUCCESS);
}elseif($_SERVER['REQUEST_METHOD']=="POST" && $_REQUEST["act"]!="NEW"){
	if ($framework->config["payment_receiver"]=="store")
	{
		if ($_REQUEST["storename"]!="")
		{

			$storeDet = $store->storeGetByName($_REQUEST["storename"]);
			$fr_store = $storeDet["id"];
		}
		else
		{
			$fr_store = 0;
		}
	}
	else
	{
		$fr_store = -1;
	}

	$objUser->ip_det = array("country"=>$_POST['country'],"city"=>$_POST["city"]);

	$_SESSION['email_confirm'] = $global['email_confirm'];
	if ($objUser->authenticate($_POST["txtuname"],$_POST["txtpswd"],$fr_store))
	{
		$userDetails = $objUser->getUsernameDetails($_POST["txtuname"]);
		if ($admin->moduleExists("cart"))
		{
			$objCart = new Cart();
			$objCart->userCartMergeOnLogin($userDetails['id']);
		}

		if ($userDetails["profile_flg"]!="Y")//Checking whether this user has updated his profile
		{
			if ($framework->config["profile_alert"]=="Y")
			{
				redirect(makeLink(array("mod"=>"member", "pg"=>"profile"), "act=profile_page"));
			}
		}

		if (SEO_URL==1)
		{
			if ($_SESSION['lg_rd_url']!="")
			{
				$red_url =  $_SESSION['lg_rd_url'];
				unset($_SESSION['lg_rd_url']);
				redirect($red_url);
			}
		}

		if ($_REQUEST["url"])
		{
			redirect($_REQUEST["url"]);
		}
		else
		{


			/*if($userDetails['newsletter'] == 'N') {
			redirect(makeLink(array('mod'=>"newsletter", "pg"=>"subscribe")));
			} else {
			redirect(makeLink(array('mod'=>"member",'pg'=>"home")));
			}*/
			if($global["show_property"] == 1){
				if($global["redirect"] == 1)
				redirect(makeLink(array("mod"=>"member","pg" => "home"),"act=my_account"));
				else
				redirect(SITE_URL);

			}elseif($global['redirect_page']=='index.php'){
				redirect($global['redirect_page']);
			}elseif($global['target_page']!=''){
				redirect(makeLink(array("mod"=>"product", "pg"=>"list"), "act=".$global['target_page']));
			}else{
				//print $domainpath;

				//$_SESSION['RefererPath'] = '';



				if($global["inner_change_reg"]=="yes")
				{

					$framework->tpl->assign("chatsection",1);

					redirect(makeLink(array("mod"=>"chat", "pg"=>"index"), "act=list"));
				}
				elseif($global["redirect_to_search"]=="Y")
				{
					redirect(makeLink(array("mod"=>"product", "pg"=>"search"), "act=search_inventry"));
				}
				elseif($global["myprofile_redirection"]==1)
				{
					redirect(makeLink(array("mod"=>"member", "pg"=>"profile"), "act=profile_page"));
				}
				else {
					redirect(makeLink(array('mod'=>"member",'pg'=>"home")));
				}
				if ( $global['payment_tpl'] == 'popup' )
				{
					redirect($domainpath); //Commented by Retheesh. If this is required for any other projects please add a config value and check.Otherwise it is affecting other projects
					//}elseif($global['direct_to_swapshop'] == '1'){
					// redirect(makeLink(array('mod'=>"member",'pg'=>"home"),"swap_shop=yes"));
				}
				
				if($global['article_redirect_link']==1)
				{
					redirect(makeLink(array('mod'=>"",'pg'=>"index")));
				}

				else{
					redirect(makeLink(array('mod'=>"member",'pg'=>"home")));
				}
			}
		}
	}
	else
	{
		setMessage($objUser->getErr(),MSG_INFO);
	}
}
else if($_REQUEST["act"]=="NEW"){
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$req = &$_REQUEST;
		$arr["username"]=$_REQUEST["username"];
		$arr["password"]=$_REQUEST["password"];
		$arr["email"]=$_REQUEST["email"];
		//print_r($arr);exit;
		$objUser->setArrData($arr);
		$myId=$objUser->insert();
		setMessage($objUser->getErr());
		if($global['no_activation_link_need']=="Y"){
			$objUser->makeActive($myId);
			$objUser->ip_det = array("country"=>$_POST['country'],"city"=>$_POST["city"]);
			if ($objUser->authenticate($_REQUEST["username"],$_REQUEST["password"],0)){
				if ($_REQUEST["url"])
				{
					redirect($_REQUEST["url"]);
				}else{
					redirect(makeLink(array('mod'=>"member",'pg'=>"home")));
				}
			}
			//redirect(makeLink(array('mod'=>"member",'pg'=>"login")));
		}else{
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

	}
}else{
	if($_REQUEST["act"]=="y"){

		$framework->tpl->assign("ACT", "y");
		//print_r($global["projectname"]);
		if($global["inner_change_reg"]=="yes")
		{

		}else
		{
			$store    = $objUser->getStore($_REQUEST["user_id"]);
		}
		$framework->tpl->assign("URL", $store);
		//print_r($_SESSION);

		if(SHOW_FORMS=="Y")
		{
			setMessage(" Congratulations!<br>Your Account has been set up successfully. We have sent you an email with your account information. Please verify your  E-Mail address to complete the registration.",MSG_INFO);
			//$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/loginfirst.tpl");
		}else{
			if($_REQUEST['thnx']!='Y'){
				if ($framework->MOD_VARIABLES['MOD_LABELS']['LBL_SUCCESS_MESSAGE'])
				$message_send = $framework->MOD_VARIABLES['MOD_LABELS']['LBL_SUCCESS_MESSAGE'];
				else
				$message_send = "Account Created Successfully. An activation link has been sent to your email.
		Please click on that link to activate this account. If you have not received an activation email please check your junk or spam mail folder.";	
				setMessage($message_send,MSG_INFO);
			}
		}
	}
	if($_REQUEST["fn"]=="active"){
		$objUser->makeActive($_REQUEST["user_id"]);
		$framework->tpl->assign("ACT", "active");
		setMessage("Your account has been activated successfully.");
	}
	
	if($_REQUEST["fn"]=="active" && $_REQUEST["user_id"]){
		$user_det = $objUser->getUserdetails($_REQUEST["user_id"]);
		if($user_det['mem_type']== 2 && $user_det['from_store'] ==0 )
		{
			$storedet    = $objUser->getStore($_REQUEST["user_id"]);
			$reurl=	 SITE_URL.'/'.$storedet->name.'/manage';
			redirect($reurl);
		}
	}	
	

	if ( !empty($_SESSION['memberid']) ) {
		//$_SESSION['RefererPath']='';
		if(SHOW_FORMS=="Y")
		{
			//redirect(makeLink(array('mod'=>"member",'pg'=>"home")));
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
if($_REQUEST["act"]=="y" && SHOW_FORMS=="Y")
{
	//print_r($_SESSION["memberid"]);exit;
	$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/loginfirst.tpl");
	//$framework->tpl->assign("SENDACTIVATIONLINK", createButton("SENDACTIVATIONLINK","#",//"history.go(-1)"));
}
#######################This is for personalizedgift to redirect after registered for store thru /setup
elseif($_REQUEST['thnx']=='Y'){

	$store    = $objUser->getStore($_REQUEST["user_id"]);
//
    $msg_list["NAME"]=$store->name;
	if ($store->id>0)
	{
		$msg_list["STORE_LINK"]=SITE_URL."/".$store->name;
		$msg_list["MANAGE_STORE"]=SITE_URL."/".$store->name."/manage";
	}
	else
	{
	 	$msg_list["STORE_LINK"]="";
		$msg_list["MANAGE_STORE"]="";
	}
	$name="login_confirm";
	$ms=$objUser->listMessage($msg_list,$name);
	$framework->tpl->assign("MSGBODY",$ms["body"]);
//
	$framework->tpl->assign("URL", $store);
	$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/thnx_setup.tpl");
}
######################
else{
	if ($_REQUEST["url"])
	{
		$framework->tpl->assign("MSG_FROM_PAGE","Y");
	}
	$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/loginfail.tpl");
	if ($framework->config['inner_login']=="Y")
	{
		$framework->tpl->display($global['curr_tpl']."/inner_login.tpl");
		exit;
	}
}

if($global["inner_change_reg"]=="yes"){
	$framework->tpl->assign("USER_LIST", $rs);
	$framework->tpl->display($global['curr_tpl']."/inner_ch.tpl");
	exit;
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");

?>