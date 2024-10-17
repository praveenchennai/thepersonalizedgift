<?php 
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
	$objCms = new Cms();
	$email	 		= new Email();
if(isset($_SESSION['chps1']))
	{
	 $framework->tpl->assign("chps1",$_SESSION['chps1']);
	}
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


	//$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
$user = new User();
$objEmail			= 	new Email();
switch($_REQUEST['act']) {
	case "invite":
		if($global['invite_chk']==1)
		{
			if ($_SERVER['REQUEST_METHOD']=="POST")
			{
					$val=$user->sentInvite($_POST['friends'],$_POST['email'],$_POST['comments'],$objEmail);
					if($val!='true')
					{
						
						$framework->tpl->assign("MESSAGE",$val);	
					}
				
			}
			
			if ($_SESSION["memberid"])
			{
				if($objUser->checkGroupMember($_REQUEST["id"],$_SESSION["memberid"]))
				{
					$framework->tpl->assign("MEM_FLG","Y");	
					
				}
				else
				{
					$framework->tpl->assign("MEM_FLG","N");	
				}
				
			}
			else
			{
				$framework->tpl->assign("MEM_FLG","N");
			}
			$framework->tpl->assign("ID", $_REQUEST['id']);
			$framework->tpl->assign("USERDET",$objUser->getUserDetails($_SESSION["memberid"]));
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/group_invite.tpl");		
				
		}
		else
		{
			$mem_det = $objUser->getUserDetails($_SESSION["memberid"]);
			if($_SERVER['REQUEST_METHOD']=="POST")
				{
				checkLogin();
				$mailTo=$_POST["friend_email"];
				$arr1=explode(",",$mailTo);
				for($i=0;$i<sizeof($arr1);$i++)
				{	if($arr1[$i])
				{
					if(!checkEmail($arr1[$i]))
					{
						if($invalid!='')
						{
							$invalid=$invalid."<br>".$arr1[$i]." (Invalid Email Id)";
						}
						else
						{
							$invalid=$arr1[$i]." (Invalid Email Id)";
						}
					}
				}
				}
			if($invalid =='')
				{
					$_POST["user_id"]=$_SESSION["memberid"];
					$user->InsertInviteHistory($_POST);
					
					$site=$framework->config['site_name'];
					$subject="You are Invited to $site by ".$mem_det['first_name'];
					
					$mailFrom=$mem_det["email"];
					$link="<br /><a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"register"), "&act=add_edit&reff_id=".$_SESSION["memberid"])."\">Click To Join</a>";
					$body=$_POST["message"]."<br />Your Friend ".$mem_det['first_name']." is a member of $site and inviting you to join  $site.<br />Click on the link below to Join $site"."\n".$link;
					//print_r($body);exit;
					mimeMail($mailTo, $subject, $body,'','', $mailFrom);
					setMessage("Your message  sent successfully.", MSG_SUCCESS);
				}
				else
				setMessage("Invald EmailID.", MSG_ERROR);
					
				}
			if($_REQUEST['id']){
					$us_det		=	$objUser->getInviteDetails($_REQUEST['id']);
					$_REQUEST +=	$us_det;
				}
	
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/invite_friend.tpl");
		}
		break;
	case "showmyinvitelink":
		checkLogin();
			$mem_det = $objUser->getUserDetails($_SESSION["memberid"]);
			$mylink="<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"register"), "act=add_edit&reff_id=".$_SESSION["memberid"])."\">Invite Link of ".$mem_det['first_name']."</a>";
			$framework->tpl->assign("INVITELINK", $mylink);
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/invite_link.tpl");
		break;
	case "invitehistory":
			checkLogin();
			$memid = $_SESSION["memberid"];
			$param="mod={$mod}&pg={$pg}&act=invitehistory&uid={$memid}";
			list($res,$numpad)=$objUser->getInviteHistoryDetails($_REQUEST['pageNo'],5,$param,$memid);
			$framework->tpl->assign("RS", $res);
			$framework->tpl->assign("NUMPAD", $numpad);
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/invite_history.tpl");
		break;
	case "delete":
			checkLogin();
			$re		=	$objUser->deleteInviteDetails($_REQUEST['id']);
			redirect(makeLink(array("mod"=>"member", "pg"=>"invite"),"act=invitehistory"));
		break;
	case "friendrequest":
			checkLogin();
			$memid = $_SESSION["memberid"];
			$ftype=$objUser->getFriendType();
			$framework->tpl->assign("FTYPE", $ftype);
			if($_REQUEST["act1"]=="accept"){
			$fdet = $objUser->getUserdetails($memid);
			$user_det = $objUser->getUserdetails($_REQUEST["fid"]);
			if($global['show_private']=='Y')
						{
						        $mail_header = array();
								$mail_header["from"] = $framework->config['admin_email'];
								$mail_header["to"]   = $user_det["email"];
						        $dynamic_vars = array();
								$dynamic_vars["FRIENDNAME"]  = $fdet["screen_name"];
								$dynamic_vars["USER_NAME"] =  $user_det["screen_name"];
								$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
								$linkvar="/index.php?sess=";
								$linkvar1="mod=member&pg=invite&act=friend_list";
								$linkvar1=base64_encode($linkvar1);
								$linkvar.=$linkvar1;
								$qr_str = "http://".$_SERVER['HTTP_HOST'].$linkvar;
								$dynamic_vars["LINK"]       = "<b><a target='_blank' href=\"".$qr_str."\">http://www.link54.com/friendslist</a></b>";
								$email->send("friendaccept_mail",$mail_header,$dynamic_vars);
						}
			
			
			
				$msg=$objUser->addAsFriend($memid,$_REQUEST["fid"],"approved",$_REQUEST["friend_type"]);
				$objUser->changeApproveFriendlist($memid,$_REQUEST["fid"],"approved");
			}
			if($_SERVER['REQUEST_METHOD']=="POST"){
			//print_r($_POST);exit;
				if($_POST["friend_type"]==4){
				$objUser->changeApproveFriendlist($memid,$_POST["fid"],"denied");
				}else{
					$msg=$objUser->addAsFriend($memid,$_POST["fid"],"approved",$_POST["friend_type"]);
					$objUser->changeApproveFriendlist($memid,$_POST["fid"],"approved");
				}
			}
			if($_REQUEST["act1"]=="deny"){
				$objUser->changeApproveFriendlist($memid,$_REQUEST["fid"],"denied");
			}
			$framework->tpl->assign("TITLE_HEAD","My List Requests");
			$param="mod={$mod}&pg={$pg}&act=friendrequest&uid={$memid}";
			list($res,$numpad)=$objUser->getFriendRequest($_REQUEST['pageNo'],5,$param,$memid);
			###to list private users on the search result (Link 54)
						### Modified on 4 Jan 2008.
		                ### Modified By Jinson.
						if($global['show_private']=='Y')
						{ for ($i=0;$i<sizeof($res);$i++)
										{
										$medet=$objUser->getUsernameDetails($res[$i]->username);
										if($medet["user_id"]==$_SESSION["memberid"])
										{$res[$i]->owner="Y";}
										if($medet["mem_type"]==3){
													if($medet["friends_can_see"]=='Y')
													            {
										                     if($objUser->isFriends($medet["user_id"],$_SESSION["memberid"])==true)
															 {$res[$i]->show_profile="Y";}
															 else{$res[$i]->show_profile="N";}
										                         }
																			else{
															$res[$i]->show_profile="N";
																				}
																				if($medet["user_id"]==$_SESSION["memberid"]){
						                                                        $res[$i]->show_profile="Y";
					                                                             }
																				
																 }
																 else{$res[$i]->show_profile="Y";}										
	
										}
						    }//if
					    //////////////////
			$framework->tpl->assign("RS", $res);
			$framework->tpl->assign("USER_ID", $memid);
			$framework->tpl->assign("NUMPAD", $numpad);
			if ($framework->config['profile_inner']=="Y"){
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_main.tpl");
				$framework->tpl->assign("profile_tpl",SITE_PATH."/modules/member/tpl/friend_request.tpl");
			}
			else{
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/friend_request.tpl");
				$framework->tpl->assign("left_tpl",SITE_PATH."/modules/member/tpl/friend_left.tpl");
			}
		break;
	case "sentrequest":
			checkLogin();
			$memid = $_SESSION["memberid"];
			$param="mod={$mod}&pg={$pg}&act=sentrequest&uid={$memid}";
			list($res,$numpad)=$objUser->getSentRequest($_REQUEST['pageNo'],5,$param,$memid);
						###to list private users on the search result (Link 54)
						### Modified on 4 Jan 2008.
		                ### Modified By Jinson.
						if($global['show_private']=='Y')
						{ for ($i=0;$i<sizeof($res);$i++)
										{
										$medet=$objUser->getUsernameDetails($res[$i]->username);
										if($medet["user_id"]==$_SESSION["memberid"])
										{$res[$i]->owner="Y";}
										if($medet["mem_type"]==3){
																	if($medet["friends_can_see"]=='Y')
													           			 {
																		 if($objUser->isFriends($medet["user_id"],$_SESSION["memberid"])==true)
																			 {$res[$i]->show_profile="Y";}
																		 else{
																			$res[$i]->show_profile="N";}
																			 }
																			else{
																				$res[$i]->show_profile="N";
																				}
																				if($medet["user_id"]==$_SESSION["memberid"]){
						                                                        	$res[$i]->show_profile="Y";
					                                                             }
																				
																 }
																 else{
																 	$res[$i]->show_profile="Y";
																 }										
	
										}
						    }//if
					    //////////////////
			$framework->tpl->assign("RS", $res);
			$framework->tpl->assign("TITLE_HEAD","Sent List Requests");
			$framework->tpl->assign("NUMPAD", $numpad);
			$framework->tpl->assign("USER_ID", $memid);
			if ($framework->config['profile_inner']=="Y"){
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_main.tpl");
				$framework->tpl->assign("profile_tpl",SITE_PATH."/modules/member/tpl/sent_request.tpl");
			}
			else{
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/sent_request.tpl");
			$framework->tpl->assign("left_tpl",SITE_PATH."/modules/member/tpl/friend_left.tpl");
			}
		break;
		case "addfriend":
		
			checkLogin();
			$memid = $_SESSION["memberid"];
			$fdet = $objUser->getUsernameDetails($_REQUEST["uname"]);
			$user_det = $objUser->getUserdetails($memid);
			////////////////////////////
				if($global['show_private']=='Y')
						{
						
						 $num=$objUser->checkFriendcount($memid,$fdet["id"]);
						 if($num>0)
		                    {
							
							}
							else
							{
						        $mail_header = array();
								$mail_header["from"] = $framework->config['admin_email'];
								$mail_header["to"]   = $fdet["email"];
						        $dynamic_vars = array();
								$dynamic_vars["FRIENDNAME"]  = $fdet["screen_name"];
								$dynamic_vars["USER_NAME"] =  $user_det["screen_name"];
								$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
								$linkvar="/index.php?sess=";
								$linkvar1="mod=member&pg=invite&act=friendrequest";
								$linkvar1=base64_encode($linkvar1);
								$linkvar.=$linkvar1;
								$qr_str = "http://".$_SERVER['HTTP_HOST'].$linkvar;
								$dynamic_vars["LINK"]       = "<b><a target='_blank' href=\"".$qr_str."\">http://www.link54.com/friendslist</a></b>";
								$email->send("friendrequest_mail",$mail_header,$dynamic_vars);
							}	
												
						}
						///////////////////
			$msg=$objUser->addAsFriend($memid,$fdet["id"]);
			$param="mod={$mod}&pg={$pg}&act=sentrequest&uid={$memid}";
			list($res,$numpad)=$objUser->getSentRequest($_REQUEST['pageNo'],5,$param,$memid);
			###to list private users on the search result (Link 54)
						### Modified on 4 Jan 2008.
		                ### Modified By Jinson.
						if($global['show_private']=='Y')
						{ for ($i=0;$i<sizeof($res);$i++)
										{
										$medet=$objUser->getUsernameDetails($res[$i]->username);
										if($medet["user_id"]==$_SESSION["memberid"])
										{$res[$i]->owner="Y";}
										if($medet["mem_type"]==3){
													if($medet["friends_can_see"]=='Y')
													            {
										                     if($objUser->isFriends($medet["user_id"],$_SESSION["memberid"])==true)
															 {$res[$i]->show_profile="Y";}
															 else{$res[$i]->show_profile="N";}
										                         }
																			else{
															$res[$i]->show_profile="N";
																				}
																				if($medet["user_id"]==$_SESSION["memberid"]){
						                                                        $res[$i]->show_profile="Y";
					                                                             }
																				
																 }
																 else{$res[$i]->show_profile="Y";}										
	
										}
						    }//if
					    //////////////////
			
			//print_r($res);
			
			$framework->tpl->assign("RS", $res);
			$framework->tpl->assign("NUMPAD", $numpad);
			$framework->tpl->assign("MS", $msg);
			$framework->tpl->assign("TITLE_HEAD","Sent List Requests");
			if ($framework->config['profile_inner']=="Y"){
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_main.tpl");
				$framework->tpl->assign("profile_tpl",SITE_PATH."/modules/member/tpl/sent_request.tpl");
			}
			else{
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/sent_request.tpl");
				$framework->tpl->assign("left_tpl",SITE_PATH."/modules/member/tpl/friend_left.tpl");
			}	
		break;
		case "removefriend":
			checkLogin();
			$memid = $_SESSION["memberid"];
			$fdet = $objUser->getUsernameDetails($_REQUEST["uname"]);
			$msg=$objUser->removeFriend($memid,$fdet["id"]);
			redirect(makeLink(array('mod'=>"member",'pg'=>"invite"),"act=friend_list&user_id=".$_SESSION["memberid"]));
		break;
		case "changeFriendType":
			checkLogin();
			$memid = $_SESSION["memberid"];
			$ftype=$objUser->getFriendType();
			$fdet = $objUser->getUsernameDetails($_REQUEST["uname"]);
			$framework->tpl->assign("FTYPE", $ftype);
			//print_r($fdet);exit;
			$framework->tpl->assign("FDET", $fdet);
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/friend_request_type.tpl");
			if($_SERVER['REQUEST_METHOD']=="POST"){
				if($_POST["friend_type"]!=4){
					//print_r($_POST);exit;
					$msg=$objUser->changeFriendType($_SESSION["memberid"],$_POST["fid"],$_POST["friend_type"]);
					redirect(makeLink(array('mod'=>"member",'pg'=>"invite"),"act=friend_list&user_id=".$_SESSION["memberid"]));
				}
				}
		break;
		case "friend_list":
		     checkLogin();
			if($_REQUEST["chps"]!=""){
$framework->tpl->assign("chps", $_REQUEST["chps"]);	
	}
			$mem_id   = $_REQUEST["user_id"];
			if(!$_REQUEST["user_id"]){
				$mem_id   = $_SESSION["memberid"];
			}
			if($mem_id==$_SESSION["memberid"]){
				$own="Y";
			}else{
				$own="N";
			}
			if($_REQUEST["type"]){
				$ftype=$_REQUEST["type"];
			}else{
				$ftype=0;
			}
			$friendtype=$objUser->getFriendType();
			// print_r($friendtype);exit;
			$framework->tpl->assign("FR_TYPE", $friendtype);
			$framework->tpl->assign("OWN",$own);
			$framework->tpl->assign("TITLE_HEAD","My List");
			$framework->tpl->assign("FTYPE", $ftype);
			$framework->tpl->assign("USID", $mem_id);
			$user_det = $objUser->getUserDetails($mem_id);
			$uname    = $user_det["username"];
			$param="mod=$mod&pg=$pg&act=".$_REQUEST['act']."&user_id=".$_REQUEST["user_id"]."&type=".$ftype;
			//print_r($param);
			if($_SERVER['REQUEST_METHOD']=="POST")
			{
				$_REQUEST["pageNo"]=1;
			}
			if($own="Y"){
				list($rs, $numpad) = $objUser->getFriendDetails($_REQUEST['pageNo'], 5, $param, OBJECT, $_REQUEST['orderBy'],$_REQUEST["txtuname"],$mem_id,$ftype);
			}else{
				list($rs, $numpad) = $objUser->getFriendDetails($_REQUEST['pageNo'], 5, $param, OBJECT, $_REQUEST['orderBy'],$_REQUEST["txtuname"],$mem_id,$ftype,3);// 3 is the default mem_type in member master of private members......
			}
			
			for ($i=0;$i<sizeof($rs);$i++)	
						{
							$medet=$objUser->getUsernameDetails($rs[$i]->username);
							$rs[$i]->nick_name=$medet["nick_name"];
										//print_r($mdet);exit;
			
						}
			//print_r($rs);exit;
			$userDet=$objUser->getUserdetails($mem_id);
			$framework->tpl->assign("USERINFO", $userDet);//getting details from member_master
            ###to list private users on the search result (Link 54)
						### Modified on 4 Jan 2008.
		                ### Modified By Jinson.
						if($global['show_private']=='Y')
						{ for ($i=0;$i<sizeof($rs);$i++)
										{
										$medet=$objUser->getUsernameDetails($rs[$i]->username);
										if($medet["user_id"]==$_SESSION["memberid"])
										{$rs[$i]->owner="Y";}
										if($medet["mem_type"]==3){
													if($medet["friends_can_see"]=='Y')
													            {
										                     if($objUser->isFriends($medet["user_id"],$_SESSION["memberid"])==true)
															 {$rs[$i]->show_profile="Y";}
															 else{$rs[$i]->show_profile="N";}
										                         }
																			else{
															$rs[$i]->show_profile="N";
																				}
																				if($medet["user_id"]==$_SESSION["memberid"]){
						                                                        $rs[$i]->show_profile="Y";
					                                                             }
																				
																 }
																 else{$rs[$i]->show_profile="Y";}										
	
										}
						    }//if
					    //////////////////
			$framework->tpl->assign("PROFILE_LIST", $rs);
			$framework->tpl->assign("PROFILE_NUMPAD", $numpad);
			if ($framework->config['profile_inner']=="Y"){
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_main.tpl");
				$framework->tpl->assign("profile_tpl",SITE_PATH."/modules/member/tpl/friends_list.tpl");
			}
			else{
				
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/friend_list.tpl");
			$framework->tpl->assign("left_tpl",SITE_PATH."/modules/member/tpl/friend_left.tpl");
			}
			break;
	
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>