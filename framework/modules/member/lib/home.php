<?php
session_start();
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/extras/lib/class.extras.php");
include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
include_once(FRAMEWORK_PATH."/modules/blog/lib/class.blog.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.broker.php");
$blog = new Blog();
$album			=	new Album();
if($global['show_private']=='Y'){
	$user_id=$_SESSION['memberid'];
	if($user_id){
		$blog_details=$blog->getBlog($user_id);
	}
	$framework->tpl->assign("BLOG_DETAILS", $blog_details);
	$USERINFO=$objUser->getUserdetails($blog_details['user_id']);
	$framework->tpl->assign("SCREEN_NAME",$USERINFO['screen_name']);
}
if(isset($_SESSION['chps1']))
{
	$framework->tpl->assign("chps1",$_SESSION['chps1']);
}

if($_REQUEST["pid"]!=""){
	$framework->tpl->assign("pid", $_REQUEST["pid"]);
}



if($framework->config['show_property'] == 1)
{
	include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
	$objAlbum = new Album();
}
$objUser=new User();
$objExtras=new Extras();
$admin = new Admin();
$objCms = new Cms();
$flyer			=	new	Flyer();
$broker     =  new Broker();

checkLogin();
$usr=$objUser->getUserDetails($_SESSION["memberid"]);
//print_r($_REQUEST);
// Get the count of Unread Messages # Result based on config table
if($global["message_count"] == 1){
	$msgcount=$objUser->getMsgCount($usr["username"]);
	$framework->tpl->assign("MSGCOUNT",$msgcount);
}

//print_r($_SESSION['pre_url']);
switch($_REQUEST["act"])
{
	case "jquery_test":
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/jqry.tpl");
		break;
	case "change_pass":
		$framework->tpl->assign("TITLE_HEAD","Change Password");
		if($_SERVER['REQUEST_METHOD']=="POST")
		{
			if($objUser->changePassword($_POST["old_pass"],$_POST["new_pass"],$_SESSION["memberid"]))
			{
				redirect(makeLink(array('mod'=>"member",'pg'=>"home")));
			}
			else
			{
				setmessage($objUser->getErr());
			}
		}
		$framework->tpl->assign("SUBMITBUTTON1", createImagebutton_Div("Submit","#","check()"));
		$framework->tpl->assign("CANCEL", createImagebutton_Div("Cancel","#","history.go(-1)"));

		$framework->tpl->assign("SUBMITBUTTON", createButton("SUBMIT","#","check()"));
		$framework->tpl->assign("RESETBUTTON", createButton("CANCEL","#","history.go(-1)"));
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/change_password.tpl");
		break;
	case "my_account":

		if ($framework->config['checkforpaydetails'] == 'Yes') { #This condition used for Checking the Broker Commision etc...
			$Information	=	$objUser->checkForBrokerPaymentConfiguration($_SESSION['memberid']);
			print $Information;
		}
		// /*
		$udet = $objUser->getUserDetails($_SESSION["memberid"]);

		$pop=$udet["popup_active"];
		if($pop!='')
		{
			$framework->tpl->assign("POPUP",$_SESSION["userPopup"]);

			list($rs, $numpad) = $album->getBookingUserDetails($_REQUEST['pageNo'],10,'',ARRAY_A,$orderBy,$_SESSION["memberid"]);
			//$bookingUserDetails = $album->getBookingUserDetails($_SESSION["memberid"]);
			$bidDetails=$album->getAlbumBidDttails($_SESSION["memberid"]);
			$bidHighestAmt = $album->bidHighestAmt($_SESSION["memberid"]);
			//	print_r($bidHighestAmt);
			$framework->tpl->assign("BOOKING_DETAILS",$rs);
			$framework->tpl->assign("BIDDING_DETAILS",$bidDetails);
			$framework->tpl->assign("BID_HIGHEST",$bidHighestAmt);
			//$bid=1;
			//$framework->tpl->assign("BID",$bid);
		}

		// */

		$framework->tpl->assign("USR",$usr);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/my_account.tpl");
		break;


	case "myaccount":
		$framework->tpl->assign("USR",$usr);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/myhome.tpl");
		break;

		/**
	* default valuses for homepage
	* Author   : 		
	* Created  : 29/Aug/2006
	* Modified : 29/mar/2008 By Vipin Vijayan
	*/

	default:


		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
		//$framework->tpl->assign("LEFTBOTTOM","social_community" );
		$framework->tpl->assign("TITLE_HEAD","My Account");
		$sess = $objUser->getLastSession($_SESSION["memberid"]);
		if(!array_key_exists('article_redirect_link',$global))
		{
			if ($admin->moduleExists("extras"))
			{
				$gift =	$objExtras->giftByuserid($_SESSION["memberid"]);
			}
			$udet = $objUser->getUserDetails($_SESSION["memberid"]);

			if ($admin->moduleExists("extras"))
			{
				$coupons =	$objExtras->couponByuserid($_SESSION["memberid"]);
			}
		}
		if ($udet["sub_pack"]>0)
		{
			if ($objUser->subAlert($_SESSION['memberid']))
			{
				$framework->tpl->assign("SHOW_MSG",1);
			}
		}

		list($res,$numpad)=$objUser->getFriendRequest($_REQUEST['pageNo'],5,'',$_SESSION["memberid"]);
		$framework->tpl->assign("MYLIST_REQ_NO",sizeof($res));

		if ($framework->config["nomination_limit"])
		{
			$week_id   = date("Wy");
			$cnt = $objUser->getNominationCount($week_id,$_SESSION['memberid']);
			$nom_limit = $framework->config["nomination_limit"];
			$nom_left = $nom_limit - $cnt;
			$framework->tpl->assign("NOM_LEFT",$nom_left);
		}
		//############vinoy   for seller earnings ,yearly earnings ,ratings, //
         $srate=$objUser->getSellerRatings($_SESSION["memberid"]);
		 $syearearn=$broker->getSellerYearlyEarnings($_SESSION["memberid"]);
		 $stotearn =$broker->getSellerEarnings($_SESSION["memberid"]); 
		 
		 $srate_show=  $srate['rate']*20;
		 $framework->tpl->assign("PSRATE",$srate_show);
		 $framework->tpl->assign("SRATE",$srate['rate']);
		 $framework->tpl->assign("SYEAR_EARN",$syearearn['yearamt']);
		 $framework->tpl->assign("STOT_EARN",$stotearn['totamt']);
		 
		 //############vinoy   for broker earnings ,yearly earnings ,ratings, //
		 
		$brate=$objUser->getBrokerRatings($_SESSION["memberid"]);
		$btotalearn=$broker ->getBrokerOrManagerInvoiceSum($_SESSION["memberid"],'BROKER_COMMISION');
		$byearearn=$broker ->getBrokerOrManagerYearlySum($_SESSION["memberid"],'BROKER_COMMISION');
		$btotdeposite=$broker ->getBrokerDeposite($_SESSION["memberid"],'Broker');
		$byeardeposite=$broker ->getBrokerYearlyDeposite($_SESSION["memberid"],'Broker');
		$bfulltotal=$btotalearn['Total_amount'] + $btotdeposite['totamt'];
		$byeartotal=$byearearn['year_amount'] + $byeardeposite['yearamt'];
		
		 $brate_show=  $brate['rate']*20;
		 $framework->tpl->assign("PBRATE",$brate_show);
		 $framework->tpl->assign("BRATE",$brate['rate']);
		 $framework->tpl->assign("BTOT_EARN", $bfulltotal);
	     $framework->tpl->assign("BYEAR_EARN",$byeartotal);
		 
		 
		  //############vinoy   for manager earnings ,yearly earnings ,ratings, //
		 
		 
		$mrate=$objUser->getManagerRatings($_SESSION["memberid"]);
		$mtotalearn=$broker ->getBrokerOrManagerInvoiceSum($_SESSION["memberid"],'MANAGER_COMMISION');
		$myearearn=$broker ->getBrokerOrManagerYearlySum($_SESSION["memberid"],'MANAGER_COMMISION');
		$mtotdeposite=$broker ->getBrokerDeposite($_SESSION["memberid"],'Manager');
		$myeardeposite=$broker ->getBrokerYearlyDeposite($_SESSION["memberid"],'Manager');
		$mfulltotal=$mtotalearn['Total_amount'] + $mtotdeposite['totamt'];
		$myeartotal=$myearearn['year_amount'] + $myeardeposite['yearamt'];
		
		
 		 $mrate_show=  $mrate['rate']*20;
		 $framework->tpl->assign("PMRATE",$mrate_show);
		 $framework->tpl->assign("MRATE",$mrate['rate']);
		 $framework->tpl->assign("MTOT_EARN", $mfulltotal);
	     $framework->tpl->assign("MYEAR_EARN",$myeartotal);
		 
		 
		 
		/*
		START
		Real Estate tube
		*/
		/*
		if($framework->config['show_property'] == 1)
		{
		$rs = $objAlbum->getAlbumByFields('user_id',$_SESSION['memberid']);
		$framework->tpl->assign("PROP_DETAILS",$rs);
		$framework->tpl->assign("PROP_LIST",SITE_PATH."/modules/album/tpl/adList.tpl");

		}
		*/
		/*
		Real Estate tube
		END
		*/
		
		# for sawitonline.com
		if(SHOW_FORMS=="Y")
		$framework->tpl->assign("FORMS",$flyer->getallForms());
		# for sawitonline.com End
		$framework->tpl->assign("LAST_LG",$sess);
		$framework->tpl->assign("GIFT",$gift);
		$framework->tpl->assign("COUPONS",$coupons);
		$framework->tpl->assign("UNAME",$udet["username"]);
		$framework->tpl->assign("UDET",$udet);
		$framework->tpl->assign("UID",$_SESSION['memberid']);
		$framework->tpl->assign("MEMB_TYPE_NO",$_SESSION['member_type_no']);
		// for dynamic content section on home page
		$data="home_page";
		if($data) {
			$menuRS = $objCms->menuGetByURL($data);
			$menu   = $menuRS['id'];
		}


		if($menu) {
			$menuRS = $objCms->menuGet($menu);
			$section = $menuRS['section_id'];
			$framework->tpl->assign("MENU_NAME", $menuRS['name']);
			$menuList = $objCms->menuList($section);
		}

		$registrationpack = $objUser->getPackageDetails($udet["reg_pack"]);

		//$subscribepack = $objUser->getSubscrDetails($udet["sub_pack"]);

		$framework->tpl->assign("REGISTRATION_PACK", $registrationpack);

		if($menuList) {
			$menu = $menu ? $menu : $menuList[0]->id;
			$pageRS = $objCms->GetCMSpage($menu,OBJECT);
			$framework->tpl->assign("PAGE_HOME", $pageRS[0]);
			$framework->tpl->assign("PAGE_HOME_RIGHT", $pageRS[1]);
		}

		// assign country name
		$framework->tpl->assign("COUNTRY",$objUser->getCountryName($udet['country']));
		$framework->tpl->assign("advertisement",1);
		// ends

		//if($_REQUEST["swap_shop"]=="yes"){
		//$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/myswapshop.tpl");// for link54.
		//}else{

		if($global["inner_change_reg"]=="yes")
		{
			$framework->tpl->assign("righnavexist",1);
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/myaccount.tpl");

		}else
		{
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/myhome.tpl");
		}
		//print(SITE_PATH."/modules/member/tpl/myhome.tpl");exit;
		//}
		//print_r($_SESSION["memberid"]);exit;



}

unset($_SESSION["userPopup"]);


$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>