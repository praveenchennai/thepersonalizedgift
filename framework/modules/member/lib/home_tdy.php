<?php
	include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
	include_once(FRAMEWORK_PATH."/modules/extras/lib/class.extras.php");
	include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");
	include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");
	if($framework->config['show_property'] == 1)
	{
	include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
	$objAlbum = new Album();
	}
	$objUser=new User();
	$objExtras=new Extras();
	$admin = new Admin();
	$flyer			=	new	Flyer();
	checkLogin();	
	
	$usr=$objUser->getUserDetails($_SESSION["memberid"]);
	switch($_REQUEST["act"])
	{
		case "change_pass":
			if($_SERVER['REQUEST_METHOD']=="POST")
			{
				if($objUser->changePassword($_POST["old_pass"],$_POST["new_pass"],$_SESSION["memberid"]))
				{
					redirect(makeLink(array('mod'=>"member",'pg'=>"home")));
				}
				else
				{
					$framework->tpl->assign("MESSAGE",$objUser->getErr());
				}
			}
			$framework->tpl->assign("SUBMITBUTTON", createButton("SUBMIT","#","check()"));
			$framework->tpl->assign("RESETBUTTON", createButton("CANCEL","#","history.go(-1)"));
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/change_password.tpl");
			break;
		default:
				$sess = $objUser->getLastSession($_SESSION["memberid"]);
					if ($admin->moduleExists("extras"))	
					{
						$gift =	$objExtras->giftByuserid($_SESSION["memberid"]);
					}	
				$udet = $objUser->getUserDetails($_SESSION["memberid"]);
					if ($admin->moduleExists("extras"))	
					{
						$coupons =	$objExtras->couponByuserid($_SESSION["memberid"]);
					}	
				if ($udet["sub_pack"]>0)
				{
					if ($objUser->subAlert($_SESSION['memberid']))
					{
						$framework->tpl->assign("SHOW_MSG",1);
					}
				}	
				
				/*
				START
				Real Estate tube
				*/
				if($framework->config['show_property'] == 1)
				{
					$rs = $objAlbum->getAlbumByFields('user_id',$_SESSION['memberid']);
					$framework->tpl->assign("PROP_DETAILS",$rs);
					$framework->tpl->assign("PROP_LIST",SITE_PATH."/modules/album/tpl/adList.tpl");
					
				}
				/*
				Real Estate tube
				END
				*/
				# for sawitonline.com
				if(PROJECT=="sawitonline.com")
				$framework->tpl->assign("FORMS",$flyer->getallForms()); 
				# for sawitonline.com End
				$framework->tpl->assign("LAST_LG",$sess);
				$framework->tpl->assign("GIFT",$gift);
				$framework->tpl->assign("COUPONS",$coupons);
				$framework->tpl->assign("UNAME",$udet["username"]);
				
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/myhome.tpl");
	}
		
	$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>