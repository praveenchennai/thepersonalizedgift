<?php
	session_start();
	include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
	include_once(FRAMEWORK_PATH."/modules/extras/lib/class.extras.php");
	include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");
	include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");
	include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
	include_once(FRAMEWORK_PATH."/modules/member/lib/class.referral.php");
	//include_once(FRAMEWORK_PATH."/modules/member/lib/class.cms.php");
	
	$objUser	=	new User();
	$objExtras	=	new Extras();
	$admin 		=	new Admin();
	$flyer		=	new	Flyer();
	$objEmail 	= 	new Email();
	$objRef 	= 	new Referral();
	$objCms 	= 	new Cms();
	checkLogin();	
	$usr=$objUser->getUserDetails($_SESSION["memberid"]);
	$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
	$topsubmenu=$objCms->linkList("topmembersub");
	$_REQUEST +=$usr;
	if($_REQUEST["pid"])
	{
		$pid=$_REQUEST["pid"];
	}else{
		$pid=$topsubmenu[0]->id;
	}
	//print_r($pid);exit;
	$domain = $_SERVER['HTTP_HOST'];
	$framework->tpl->assign("DOMAINNAME",$domain);
	$framework->tpl->assign("TOPSUB_MENU", $topsubmenu);
	$framework->tpl->assign("PID", $pid);
	$framework->tpl->assign("no_recent_group","1");
	if(isset($_SESSION['chps1']))
	{
	 $framework->tpl->assign("chps1",$_SESSION['chps1']);
	}
	if($_SESSION['chps1']==1){
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
		$rightmenu=$objCms->linkList("social_community_right");
	}elseif($_SESSION['chps1']==2){
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("dating_right"));// to select the right menu of this page from database.
		$rightmenu=$objCms->linkList("dating_right");
	}elseif($_SESSION['chps1']==3){
			$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("rest_right"));// to select the right menu of this page from database.
			$rightmenu=$objCms->linkList("dating_right");
	}
	elseif($_SESSION['chps1']==4){
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("rest_right"));// to select the right menu of this page from database.
		$rightmenu=$objCms->linkList("dating_right");
	}
	elseif($_SESSION['chps1']==5){
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("dating_right"));// to select the right menu of this page from database.
		$rightmenu=$objCms->linkList("dating_right");
	}
	else{
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
	}
	
	if($_REQUEST["pid"]!=""){
$framework->tpl->assign("pid", $_REQUEST["pid"]);	
	}
	switch($_REQUEST["act"])
	{
	
	
		case "referral_more":
		   if( $_REQUEST['link_id']==2)
			$framework->tpl->assign("LEFTBOTTOM","referral" );
			 if( $_REQUEST['link_id']==1)
			$framework->tpl->assign("LEFTBOTTOM","social_community" );
			if ($_REQUEST['link_id'])	{
				$pageArray	=	$objCms->pageGet ($_REQUEST['link_id']);
				
				$framework->tpl->assign("page_title",$pageArray['title'] );
				$framework->tpl->assign("page_content",$pageArray['content'] );
			}
			
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/referral_more.tpl");
	    break;
		case "referral":
		$framework->tpl->assign("LEFTBOTTOM","referral" );

		if($_SERVER['REQUEST_METHOD'] == "POST") 
		{
		    
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_POST;  
			if($objRef->addReferral($req))
			{ 
			setMessage("Mail has been sent successfully."); 
			
				}
			else { setMessage("Mail not send .");
			     }
			
				if($global["inner_change_reg"]=="yes"){
				redirect(makeLink(array("mod"=>"member", "pg"=>"referral"), "act=referral"));
				}
				else
				{
				redirect(makeLink(array("mod"=>"member", "pg"=>"referral"), "act=referral&pid=173"));
				}
			//redirect(makeLink(array("mod"=>"member", "pg"=>"referral"), "act=referral&pid=173"));
		}
		//$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/myaccount.tpl");
			
		if($global["inner_change_reg"]=="yes"){
		
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/referral.tpl");
		}
		else
		{
		$framework->tpl->assign("tp_file",SITE_PATH."/modules/member/tpl/referral.tpl");
		}
		break;
			
	}
	if($global["inner_change_reg"]=="yes")
	{
	$framework->tpl->assign("advertisement",1);
	$framework->tpl->assign("inner_content",1);
	$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
	}
	else{
	$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/myaccount.tpl");
	}
	
	$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>