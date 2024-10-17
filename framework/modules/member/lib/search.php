<?php
	session_start();
	include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
	include_once(FRAMEWORK_PATH."/modules/extras/lib/class.states.php");
	$getId=$_SESSION["memberid"];
	
	$objUser=new User();
	$states 	= 	new States();
	
	$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
	$framework->tpl->assign("AGE_LIST",$objUser->getAgeList());
	include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
	$objCms = new Cms();
	$framework->tpl->assign("LEFTBOTTOM","search" );
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
	switch($_REQUEST['act']) 
	{
		case "list":
		
		if($global["inner_change_reg"]=="yes")
							{
		                   checkLogin();
		
		                    }
							
						
				if($_SERVER['REQUEST_METHOD']=="POST" && $global['searchstyle'] == "1" && $_POST["frm"]=="friend")
					{
					
						$param="mod=$mod&pg=$pg&act=".$_REQUEST['act']."&criteria=".$_REQUEST["criteria"]."&resultpage=search_member_result&frm=friend";
						$_REQUEST["pageNo"]=1;
						list($rs, $numpad) = $objUser->profileList($_REQUEST['pageNo'], 10, $param, OBJECT, $_REQUEST['orderBy'],$_REQUEST["txtuname"],$_SESSION["memberid"]);
							//print_r($numpad);exit;
						$framework->tpl->assign("PROFILE_LIST", $rs);
						$framework->tpl->assign("PROFILE_NUMPAD", $numpad);
						$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/search_member_result.tpl");
					}elseif($global['searchstyle'] == "1" && $_REQUEST['resultpage']=="search_member_result" && $_REQUEST['frm']=="friend"){
						$param="mod=$mod&pg=$pg&act=".$_REQUEST['act']."&criteria=".$_REQUEST["criteria"]."&resultpage=search_member_result&frm=friend";
						list($rs, $numpad) = $objUser->profileList($_REQUEST['pageNo'], 10, $param, OBJECT, $_REQUEST['orderBy'],$_REQUEST["txtuname"],$_SESSION["memberid"]);
							//print_r($numpad);exit;
						$framework->tpl->assign("PROFILE_LIST", $rs);
						$framework->tpl->assign("PROFILE_NUMPAD", $numpad);
						$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/search_member_result.tpl");
					}elseif($_SERVER['REQUEST_METHOD']=="POST" && $global['searchstyle'] == "1" &&$_POST["frm"]=="school"){
						$param="mod=$mod&pg=$pg&act=".$_REQUEST['act']."&resultpage=search_member_result&frm=school";
						if($_REQUEST["country"]!=" "){
							$param=$param."&country=".$_REQUEST["country"];
						}else{
							unset($_REQUEST["country"]);
						}
						if($_REQUEST["state"]){
							$param=$param."&state=".$_REQUEST["state"];
						}else{
							unset($_REQUEST["state"]);
						}
						$_REQUEST["pageNo"]=1;
						if($_POST["school_name"]){
							$search_field="school_name";
							$search_value=$_POST["school_name"];
							$param=$param."&school_name=".$search_value;
							list($rs, $numpad) = $objUser->profileList($_REQUEST['pageNo'], 10, $param, OBJECT, $_REQUEST['orderBy'],$_REQUEST["txtuname"],$_SESSION["memberid"],'','','',$search_field,$search_value);
						}else{
							list($rs, $numpad) = $objUser->profileList($_REQUEST['pageNo'], 10, $param, OBJECT, $_REQUEST['orderBy'],$_REQUEST["txtuname"],$_SESSION["memberid"]);
						}
						
							//print_r($numpad);exit;
						$framework->tpl->assign("PROFILE_LIST", $rs);
						$framework->tpl->assign("PROFILE_NUMPAD", $numpad);
						$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/search_member_result.tpl");

					}elseif($global['searchstyle'] == "1" && $_REQUEST['resultpage']=="search_member_result" && $_REQUEST['frm']=="school"){
						$param="mod=$mod&pg=$pg&act=".$_REQUEST['act']."&resultpage=search_member_result&frm=school";
						if($_REQUEST["country"]){
							$param=$param."&country=".$_REQUEST["country"];
						}
						if($_REQUEST["state"]){
							$param=$param."&state=".$_REQUEST["state"];
						}
						if($_REQUEST["school_name"]){
							$search_field="school_name";
							$search_value=$_REQUEST["school_name"];
							$param=$param."&school_name=".$search_value;
							list($rs, $numpad) = $objUser->profileList($_REQUEST['pageNo'], 10, $param, OBJECT, $_REQUEST['orderBy'],$_REQUEST["txtuname"],$_SESSION["memberid"],'','','',$search_field,$search_value);
						}else{
							list($rs, $numpad) = $objUser->profileList($_REQUEST['pageNo'], 10, $param, OBJECT, $_REQUEST['orderBy'],$_REQUEST["txtuname"],$_SESSION["memberid"]);
						}
						//list($rs, $numpad) = $objUser->profileList($_REQUEST['pageNo'], 10, $param, OBJECT, $_REQUEST['orderBy'],$_REQUEST["txtuname"],$_SESSION["memberid"]);
							//print_r($numpad);exit;
						$framework->tpl->assign("PROFILE_LIST", $rs);
						$framework->tpl->assign("PROFILE_NUMPAD", $numpad);
						$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/search_member_result.tpl");
					}elseif($global['searchstyle'] == "1" && $_REQUEST['sub']=="browse"){
					
				
						$param="mod=$mod&pg=$pg&act=".$_REQUEST['act']."&criteria=".$_REQUEST["criteria"]."&sub=".$_REQUEST['sub'];
						
						if($_REQUEST["c2"])
						{
							$frage=$_REQUEST["frage"];
							$toage=$_REQUEST["toage"];
							$param=$param."&frage=".$_REQUEST["frage"]."&toage=".$_REQUEST["toage"];
						}				
						if($_REQUEST["c3"])
						{
							$param=$param."&country=".$_REQUEST["country"];
						}
						else
						{
							unset($_REQUEST["country"]);
						}
						if($_REQUEST["c4"])
						{
							if ($_REQUEST["gender"]=="Male")
							{
								$gender="m";
								$param=$param."&gender=".$gender;
							}
							elseif($_REQUEST["gender"]=="Female")
							{
								$gender="f";
								$param=$param."&gender=".$gender;
							}
							
						}
						else
						{
							unset($_REQUEST["gender"]);
						}	
						
						if($_REQUEST["c5"])
						{
							
							$param=$param."&city=".$_REQUEST["city"];
						}
						else
						{
							unset($_REQUEST["city"]);
						}	
						
						if($_REQUEST["c6"])
						{
							
							$param=$param."&state=".$_REQUEST["state"];
						}
						else
						{
							unset($_REQUEST["state"]);
						}	
						
						if($_REQUEST["c7"])
						{
							
							$param=$param."&zip=".$_REQUEST["zip"];
						}
						else
						{
							unset($_REQUEST["zip"]);
						}	
						if($_REQUEST["c8"])
						{
							
							$param=$param."&exp_rate=".$_REQUEST["exp_rate"];
						}
						else
						{
							unset($_REQUEST["exp_rate"]);
						}
						if($_REQUEST["c9"])
						{
							
							$param=$param."&rel_status=".$_REQUEST["rel_status"];
						}
						else
						{
							unset($_REQUEST["rel_status"]);
						}	
						if($_REQUEST["clb_flg"])
						{
							
							$param=$param."&clb_flg=1";
						}
						else
						{
							unset($_REQUEST["clb_flg"]);
						}	
		
						
						if($_REQUEST["photo"]=="yes")
						{
							$photo="1";
							$param=$param."&photo=1";
						}
						else
						{
							unset($_REQUEST["photo"]);
						}
						if($_SERVER['REQUEST_METHOD']=="POST")
						{
							$_REQUEST["pageNo"]=1;
							
						}
						
						list($rs, $numpad) = $objUser->profileList($_REQUEST['pageNo'], 10, $param, OBJECT, $_REQUEST['orderBy'],$_REQUEST["txtuname"],$_SESSION["memberid"]);
						$framework->tpl->assign("PROFILE_LIST", $rs);
						$framework->tpl->assign("PROFILE_NUMPAD", $numpad);
						
						$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_search.tpl");
					}else{
					/*************************link54 search ***************************/
						$framework->tpl->assign("TITLE_HEAD","Search Results");
						echo $_REQUEST["criteria"];
						$param="mod=$mod&pg=$pg&act=".$_REQUEST['act']."&criteria=".$_REQUEST["criteria"];
						$cat_combo = $objUser->getCategoryCombo($_REQUEST["mod"]);
						$framework->tpl->assign("CAT_COMBO",$cat_combo);
						if($_REQUEST["c50"])
						{
							$param=$param."&genre=".$_REQUEST["genre"];
							$searchfield="genre";
							$searchvalue=$_REQUEST["genre"];
							$crt="Like";
						}
						else
						{
							unset($_REQUEST["genre"]);
						}
						if($_REQUEST["c2"])
						{
							$frage=$_REQUEST["frage"];
							$toage=$_REQUEST["toage"];
							$param=$param."&frage=".$_REQUEST["frage"]."&toage=".$_REQUEST["toage"];
						}				
						if($_REQUEST["c3"])
						{
							$param=$param."&country=".$_REQUEST["country"];
						}
						else
						{
							unset($_REQUEST["country"]);
						}
						if($_REQUEST["c4"])
						{
							if($global["searchstyle"]!="2"){
								if ($_REQUEST["gender"]=="Male")
								{
									$gender="m";
									$param=$param."&gender=".$gender;
								}
								elseif($_REQUEST["gender"]=="Female")
								{
									$gender="f";
									$param=$param."&gender=".$gender;
								}
							}else{
								if ($_REQUEST["gender"]=="Male")
								{
									$gender="male";
									$param=$param."&gender=".$gender;
								}
								elseif($_REQUEST["gender"]=="Female")
								{
									$gender="female";
									$param=$param."&gender=".$gender;
								}
							}
							
						}
						else
						{
							unset($_REQUEST["gender"]);
						}	
						
						if($_REQUEST["c5"])
						{
							
							$param=$param."&city=".$_REQUEST["city"];
						}
						else
						{
							unset($_REQUEST["city"]);
						}	
						
						if($_REQUEST["c6"])
						{
							
							$param=$param."&state=".$_REQUEST["state"];
						}
						else
						{
							unset($_REQUEST["state"]);
						}	
						
						if($_REQUEST["c7"])
						{
							
							$param=$param."&zip=".$_REQUEST["zip"];
						}
						else
						{
							unset($_REQUEST["zip"]);
						}	
						if($_REQUEST["c8"])
						{
							
							$param=$param."&exp_rate=".$_REQUEST["exp_rate"];
						}
						else
						{
							unset($_REQUEST["exp_rate"]);
						}
						if($_REQUEST["c9"])
						{
							
							$param=$param."&rel_status=".$_REQUEST["rel_status"];
						}
						else
						{
							unset($_REQUEST["rel_status"]);
						}	
						if($_REQUEST["clb_flg"])
						{
							
							$param=$param."&clb_flg=1";
						}
						else
						{
							unset($_REQUEST["clb_flg"]);
						}	
		
						if($_REQUEST["photo"]=="yes")
						{
							$photo="yes";
							$param=$param."&photo=yes";
						}
						else
						{
							unset($_REQUEST["photo"]);
						}
						
						
							if($_REQUEST['c11'])
						{
							$param=$param."&screen_name=".$_REQUEST["screen_name"];
						}
						else
						{
							unset($_REQUEST["screen_name"]);
						}
						if($_REQUEST["txtuname"])
						{
							$param=$param."&txtuname=".$_REQUEST["txtuname"];
						}
						if($_SERVER['REQUEST_METHOD']=="POST")
						{
							$_REQUEST["pageNo"]=1;
							if($_POST["criteria"]=="nick_name"){
								$searchfield="nick_name";
								$searchvalue=$_REQUEST["txtuname"];
								unset($_POST["criteria"],$_POST["txtuname"]);
								unset($_REQUEST["criteria"],$_REQUEST["txtuname"]);
							}
										
						}
					
						list($rs, $numpad) = $objUser->profileList($_REQUEST['pageNo'], 10, $param, OBJECT, $_REQUEST['orderBy'],$_REQUEST["txtuname"],$_SESSION["memberid"],'','','',$searchfield,$searchvalue,'',$crt);
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
						//print_r($rs);
						$framework->tpl->assign("PROFILE_COUNT", count( $rs));
						$framework->tpl->assign("PROFILE_NUMPAD", $numpad);
						if($global['searchstyle'] == "1")
						{
							$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/search_member.tpl");
						}
						else
						{
							$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_search.tpl");
						}
					}
			break;
		case "contact":
			checkLogin();
			$framework->tpl->assign("CONTACT_NAME", $_REQUEST["uname"]);
			$nickname=$objUser->getUsernameDetails($_REQUEST["uname"]);
			//print_r($nickname);exit;
			$framework->tpl->assign("NICK_NAME", $nickname["nick_name"]);
			if ($_POST["to"])
			{
				
				$from=$objUser->getUserdetails($_SESSION["memberid"]);
				
				if($from['username']!=$_REQUEST["to"])
				{								
					$array= array("userid"=>$from['username'],"friedid"=>$_REQUEST["to"]);
					
					$objUser->setArrData($array);
					if(!$objUser->addContact($_SESSION["memberid"],'message_contacts'))
					{	
						$framework->tpl->assign("MESSAGE", $objUser->getErr());
					}
					else
					{
						if ($_REQUEST["ret_url"])
						{
							redirect("index.php?".$_REQUEST["ret_url"]);
						}
						else
						{
							redirect(makeLink(array("mod"=>"member", "pg"=>"search"), "act=list"));
						}	
					}
				}
				else
				{
					$framework->tpl->assign("MESSAGE", "Same user!!!");
				}
		
				
			}
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/add_contact.tpl");
			break;
		case "message":
			checkLogin();
			$go_back_url = fetchPreURL();
			$framework->tpl->assign("TITLE_HEAD", "Send Message");
			if(!$_REQUEST["uname"]){
				if($_REQUEST["uid"]){
					$un=$objUser->getUserdetails($_REQUEST["uid"]);
					$_REQUEST["uname"]=$un["username"];
				}
			}
			if ($_SERVER['REQUEST_METHOD']=="POST")
			{
				$from  = $objUser->getUserdetails($_SESSION["memberid"]);
				
				$_POST["from"]=$from["username"];
				$_POST["datetime"]=date("Y-m-d G:i:s");
				$_POST["status"]="U";
				
				unset($_POST["x"],$_POST["y"]);
				if($global["hide_email_id"]=="1"){
					$us=$objUser->getUserdetails($_POST["meid"]);
					$_POST["to"]=$us["email"];
					unset($_POST["meid"]);
				}
				$objUser->setArrData($_POST);
				
				if(!$objUser->sendMessage())
				{	
					$framework->tpl->assign("MESSAGE", $objUser->getErr());
				}
				else
				{
					if ($go_back_url)
					{
						redirect($go_back_url);
					}
					else 
					{	
						if ($_REQUEST["ret_url"])
						{
							redirect("index.php?".$_REQUEST["ret_url"]);
						}
						else
						{
							redirect(makeLink(array("mod"=>"member", "pg"=>"search"), "act=list"));
						}
					}
					
						/*if ($_REQUEST["ret_url"])
						{
							redirect("index.php?".$_REQUEST["ret_url"]);
						}
						else
						{
							redirect(makeLink(array("mod"=>"member", "pg"=>"search"), "act=list"));
						}*/
				}
				 
			}
			if($global["hide_email_id"]=="1"){
				$medet=$objUser->getUsernameDetails($_REQUEST["uname"]);
				$framework->tpl->assign("MEID",$medet["id"]);
				$framework->tpl->assign("NICK_NAME", $medet["nick_name"]);
			}
			$medet=$objUser->getUsernameDetails($_REQUEST["uname"]);
			$framework->tpl->assign("CONTACT_SCREEN_NAME",$medet['screen_name']);
			$framework->tpl->assign("CONTACT_NAME", $_REQUEST["uname"]);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/send_message.tpl");
			break;	
		case "mem_contact":
			$mem_id   = $_REQUEST["user_id"];
			$user_det = $objUser->getUserDetails($mem_id);
			$uname    = $user_det["username"];
			$param="mod=$mod&pg=$pg&act=".$_REQUEST['act']."&user_id=".$_REQUEST["user_id"];
			
			if($_SERVER['REQUEST_METHOD']=="POST")
			{
				$_REQUEST["pageNo"]=1;
			}
			list($rs, $numpad) = $objUser->profileList($_REQUEST['pageNo'], 5, $param, OBJECT, $_REQUEST['orderBy'],$_REQUEST["txtuname"],$_SESSION["memberid"],'other',$uname);
			//print_r($rs);exit;
			$userDet=$objUser->getUserdetails($mem_id);
			$framework->tpl->assign("USERINFO", $userDet);//getting details from member_master
			$framework->tpl->assign("PROFILE_LIST", $rs);
			$framework->tpl->assign("PROFILE_NUMPAD", $numpad);
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/member_contact.tpl");
			break;
			
			case "coupon":
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/mycoupon.tpl");
			break;
			
			case "couponus":
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/mycoupon1.tpl");
			break;
		case "profile_list":
			
			$framework->tpl->assign("STATE_LIST",$states->GetAllStates(840));
			list($rs, $numpad) = $objUser->profileListByAjax($_REQUEST['pageNo'], 6, $param, ARRAY_A, $_REQUEST['orderBy'],$_REQUEST["txtuname"],$_SESSION["memberid"],'','','',$searchfield,$searchvalue,'',$crt);
			$framework->tpl->assign("PROFILE_LIST", $rs);
			$framework->tpl->assign("PROFILE_COUNT", count( $rs));
		    $framework->tpl->assign("PROFILE_NUMPAD", $numpad);
			//print_r($rs);
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_search.tpl");
			break;	
			
		
	}	
	if($_REQUEST['act']=='coupon' or $_REQUEST['act']=='couponus')
	{
	$framework->tpl->display($global['curr_tpl']."/inner_coupon.tpl");
	}else
	{		
	$framework->tpl->display($global['curr_tpl']."/inner.tpl");
	}
?>