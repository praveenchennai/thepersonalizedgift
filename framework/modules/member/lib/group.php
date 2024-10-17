<?php
	session_start();
	include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
	include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
	$objCms = new Cms();
	
checkLogin();
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
	}else{
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
	}
	
	
	if($_REQUEST["pid"]!=""){
	
$framework->tpl->assign("pid", $_REQUEST["pid"]);	
	}
	
	
	//$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
	if($global["show_property"] == 1){ // Afsal created on 17-09-2007 fore realestatetube
		include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
		$objCategory = new Category();
	}
	
	$objUser=new User();
	
	//if($global["show_property"] == 1) // Afsal created on 17-09-2007 fore realestatetube
	//$objAlbum = new Album();
	if($global["show_property"] == 1) //edited by afsal for realestatetube
	{
		$framework->tpl->assign("CAT_ARR",$objCategory->getChildCategories2 ("Property Listing Type"));
		$framework->tpl->assign("CAT_LIST", $objCategory->getChildCategoriesList("Property Listing Type"));
		
		
	}
	else
	{
		$framework->tpl->assign("CAT_LIST", $objUser->getCategories());
		$framework->tpl->assign("CAT_ARR", $objUser->listCategories());
	}
	
	$getId=$_SESSION["memberid"];
	$framework->tpl->assign("LEFTBOTTOM","group" );
	switch($_REQUEST['act']) 
	{
		case "create":
			ini_set("display_errors", "on");
			checkLogin();
			if ($_SERVER['REQUEST_METHOD']=="POST")
			{
				checkLogin();
				$_POST['user_id']=$getId;
				unset($_POST["x"],$_POST["y"]);
				$image=$_FILES["image"]["name"];
				if($image)
				{
					$_POST["image"]="Y";
				}
				$_POST["createdate"]=date("Y-m-d G:i:s");
				$objUser->setArrData($_POST);
				
				if ($global["photo_thumb1"])
				{
					$thumb_size  = explode(",",$global["photo_thumb1"]);
					$thumb_width = $thumb_size[0];
					$thum_height = $thumb_size[1];
				}
				else 
				{
					$thumb_width = '';
					$thum_height = '';
				}
				
				if($global["small_thumb_group"]==1){
					if ($objUser->createGroup(65,65))
					{
						redirect(makeLink(array("mod"=>"member", "pg"=>"group"), "act=mygroup"));
					}
					else
					{
						$framework->tpl->assign("MESSAGE", $objUser->getErr());
					}
				}else{
					if ($objUser->createGroup($thumb_width,$thum_height))
					{
						if($global["mygroup_redirection"]==1){
							redirect(makeLink(array("mod"=>"member", "pg"=>"group"), "act=mygroup"));
						}
						else
						redirect(makeLink(array("mod"=>"member", "pg"=>"group"), "act=list&crt=M1"));
					}
					else
					{
						$framework->tpl->assign("MESSAGE", $objUser->getErr());
					}
				}
				
			}
			if($global['profile_inner']=='Y')
		 	{
		 		 $framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/profile_main.tpl");
			     $framework->tpl->assign("profile_tpl", SITE_PATH."/modules/member/tpl/group_create.tpl");
		    }	
		    else
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/group_create.tpl");		
			break;
		case "list":
			

			$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act'];
			if ($_REQUEST["crt"])
			{
				$par = $par."&crt=".$_REQUEST['crt'];
			}
			if ($_REQUEST["cat_id"])
			{
				$par = $par."&cat_id=".$_REQUEST['cat_id'];
			}
			if ($_REQUEST["group_list"])
			{
				$par = $par."&group_list=".$_REQUEST['group_list'];
			}
			if($_POST["txtSearch"])
			{
				$stxt=$_POST["txtSearch"];
				$_REQUEST["pageNo"]=1;
				$framework->tpl->assign("STXT",$stxt);
				
			}
			else
			{	if(!$_REQUEST["stxt"])
				{
					$stxt=0;
				}
				else
				{
					$stxt=$_REQUEST["stxt"];
					$framework->tpl->assign("STXT",$stxt);
				}	
			}
			$par = $par."&stxt=".$stxt;
			if ($_REQUEST["cat_id"])
			{
				$catname=$objUser->getCatName($_REQUEST["cat_id"]);
				$framework->tpl->assign("CRT",$_REQUEST["cat_id"]);
				$framework->tpl->assign("GRP_HEADER", $catname["cat_name"]);
				list($rs, $numpad) = $objUser->groupList($_REQUEST['pageNo'], 5,$par, OBJECT, $_REQUEST['orderBy'],$_REQUEST["cat_id"],0,0,0,$stxt);				
			}
			elseif ($_REQUEST["crt"])
			{
				if ($_REQUEST["crt"]=="M1")
				{
					$sort="createdate desc";
					$gheader="Recently Added";
				}
				elseif ($_REQUEST["crt"]=="M2")
				{
					$sort="members desc";
					$gheader="Most Members";
				}
				elseif ($_REQUEST["crt"]=="M3")
				{
					$sort="discussion desc";
					$gheader="Most Discussed";
				}
				$framework->tpl->assign("CRT",$_REQUEST["crt"]);
				$framework->tpl->assign("GRP_HEADER", $gheader);
				list($rs, $numpad) = $objUser->groupList($_REQUEST['pageNo'], 5, $par, OBJECT, $sort,0,0,0,0,$stxt);
			}	
			else
			{
				
				$framework->tpl->assign("GRP_HEADER", "All Groups");
				list($rs, $numpad) = $objUser->groupList($_REQUEST['pageNo'], 5, $par, OBJECT, $_REQUEST['orderBy'],0,0,0,0,$stxt);
				//$objUser->countGroupProperties($rs);
			}
			
			
			if($global["show_property"] == 1) {// Afsal created on 17-09-2007 fore realestatetube
				$framework->tpl->assign("group_left", SITE_PATH."/modules/member/tpl/group_left.tpl");
				$framework->tpl->assign("PROP_COUNT",$objUser->countGroupProperties($rs));
			}
			
			$framework->tpl->assign("GROUP_LIST", $rs);
			$framework->tpl->assign("GROUP_NUMPAD", $numpad);

			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/group_list.tpl");	
			$framework->tpl->assign("left_tpl", SITE_PATH."/modules/member/tpl/group_list_left.tpl");	
			break;
		case "other":
			
			$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&user_id=".$_REQUEST["user_id"];
			$mem_id=$_REQUEST["user_id"];						
			if($_POST["txtSearch"])
			{
				$stxt=$_POST["txtSearch"];
				$_REQUEST["pageNo"]=1;
				$framework->tpl->assign("STXT",$stxt);
				
			}
			else
			{	if(!$_REQUEST["stxt"])
				{
					$stxt=0;
				}
				else
				{
					$stxt=$_REQUEST["stxt"];
					$framework->tpl->assign("STXT",$stxt);
				}	
			}
			$par = $par."&stxt=".$stxt;

			$framework->tpl->assign("GRP_HEADER", "All Groups");
			list($rs, $numpad) = $objUser->groupList($_REQUEST['pageNo'], 5, $par, OBJECT, $_REQUEST['orderBy'],0,0,0,0,$stxt,'other',$mem_id);
			
				$userDet=$objUser->getUserdetails($mem_id);
				$framework->tpl->assign("USERINFO", $userDet);//getting details from member_master

			
			$framework->tpl->assign("GROUP_LIST", $rs);
			$framework->tpl->assign("GROUP_NUMPAD", $numpad);

			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/group_mem.tpl");
			$framework->tpl->assign("left_tpl", SITE_PATH."/modules/member/tpl/group_mem_left.tpl");		
			break;	
		case "details":
	
		if($global["inner_change_reg"]=="yes")
							{
		checklogin();
		}
			$framework->tpl->assign("pid",$_REQUEST["pid"]);
			$framework->tpl->assign("GROUP_ID", $_REQUEST["group_id"]);
			$framework->tpl->assign("PROJECT", $framework->config['site_name']);
			
			if($global["show_property"] == 1){ // Afsal created on 17-09-2007 fore realestatetube
				$params =$param="mod={$mod}&pg={$pg}&act=".$_REQUEST['act']."&group_id=".$_REQUEST['group_id'];
				list($rs,$numpad) = $objAlbum->propertySearch($_REQUEST['pageNo'], 2, $params, ARRAY_A, $orderBy,$search_fields='',$search_values='',$type='',$criteria='=',$category='',$listAllSub='no',$_REQUEST['group_id']);
				$framework->tpl->assign("NOPROPERTY",$objAlbum->countGroupProperties($_REQUEST["group_id"]));
				$framework->tpl->assign("GROUP_ALBM",$rs);
				$framework->tpl->assign("NUMPAD",$numpad);
			}
						
			if ($_SERVER['REQUEST_METHOD']=="POST")
			{
				if($_REQUEST["fn"]=="invite")
				{
					checkLogin();
					
					$userinfo = $objUser->getUserdetails($_SESSION["memberid"]);
					//$contact  = $objUser->listContacts($userinfo["username"]);
					//modified by ratheesh on 18 dec 2007
					if($global["new_album_functions"]==1){
						$contact = $objUser->viewFriends($_SESSION['memberid']);
					}else{
						$contact  = $objUser->listContacts($userinfo["username"]);
					}
					$framework->tpl->assign("CONTACT", $contact);
					$arr=explode(",",$_POST["friends"]);
					
				
					
					
					for($i=0;$i<sizeof($arr);$i++)
					{	if($arr[$i])
						{
						   if($global["inner_change_reg"]=="yes")
							{
							if(!$objUser->validScreenname($arr[$i]))
								{
									if($invalid!='')
									{
										$invalid=$invalid."<br>".$arr[$i]." (Unknown user)";
									}
									else
									{
										$invalid=$arr[$i]." (Unknown user)";
									}
								}
									
								}else
								{
								
									if(!$objUser->validUsername($arr[$i]))
									{
										if($invalid!='')
										{
											$invalid=$invalid."<br>".$arr[$i]." (Unknown user)";
										}
										else
										{
											$invalid=$arr[$i]." (Unknown user)";
										}
									}
								
								
								}
									if($global["show_screen_name_only"]=="1"){
										$fdet=$objUser->getUnamebyscreenname($arr[$i]);
										$arruname[$i]=$fdet["username"];
										
									}
						}
						
					}
					if($global["show_screen_name_only"]=="1"){
						
						$arr=$arruname;
						
					}
					//print_r($arr);exit;
					$arr1=explode(",",$_POST["email"]);
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
					if($invalid=='')
					{
						for($i=0;$i<sizeof($arr);$i++)
						{	if($arr[$i])
							{
								if($objUser->validUsername($arr[$i]))
								{
	
									$arrData  = array();
									
									$arrData["from"]     = $userinfo["username"];
									$arrData["to"]       = $arr[$i];
									$arrData["subject"]  = $_REQUEST["subject"];
									$arrData["datetime"] = date("Y-m-d G:i:s");
									$arrData["status"]   = "U";
									$touser   = $objUser->getUsernameDetails($arr[$i]);
									$touserid = $touser["id"];
									$grpid    = $_REQUEST["group_id"];
									$from     = $userinfo["username"];
									$comment = $_REQUEST["comments"]."<br><br>";
									$comment = $comment."To accept the Invitation click on the Link below <br>";
									$comment = $comment . "<a href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"group"), "act=mygroup&group_id=$grpid&touserid=$touserid&from=$from&invite=accept")."\">Join Group</a>";
									$arrData["comments"] = $comment;
									$objUser->setArrData($arrData);
									$objUser->sendMessage(1);
	
								}	
							}	
							
							
						}
						for($i=0;$i<sizeof($arr1);$i++)
						{	if($arr1[$i])
							{
								$arrData  = array();
								$arrData["from"]     = $userinfo["username"];
								$arrData["to"]       = $arr1[$i];
								$arrData["subject"]  = $_REQUEST["subject"];
								$touser   = $objUser->getUsernameDetails($arr1[$i]);
								$grpid    = $_REQUEST["group_id"];
								$from     = $userinfo["username"];
												
								//$comment = $_REQUEST["comments"]."<br><br>";
												
								$message="<div style='padding-left: 25px; padding-right: 25px;'>";
								$message=$message."<h5>To accept the Invitation click on the Link below <br></h5>";
								$message=$message."<a href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"register"), "act=add_edit&group_id=$grpid&invite=accept")."\">Join Group</a>";
								$message=$message."<h4>Personal Message</h4>";
								$message=$message. "<p>". $_REQUEST["comments"] . "</p>";
								$message=$message."<p>Thanks,<br>";
								$message=$message. $userinfo["first_name"]. " " . $userinfo["last_name"] . "</p>";
							    $message=$message."</div>";
								mimeMail($arrData["to"],$arrData["subject"],$message,'','',$framework->config['site_name'].' <'.$framework->config['admin_email'].'>');
								//echo "mimeMail(".$arrData['to'].",".$arrData['subject'].",".$message.",'','','Industrypage.com <'.$framework->config['admin_email'].'>')";
								//sendMail($arrData["to"],$arrData["subject"],$message,'Industrypage.com<'.$framework->config['admin_email'].'>','HTML');
								}	
						}
						setMessage("The group invitation has been sent successfully.",MSG_SUCCESS);
						
						//redirect(makeLink(array("mod"=>"member", "pg"=>"group"), "act=mygroup"));
						
					}
					else
					{
						$framework->tpl->assign("MESSAGE",$invalid);
					}	
					
						
				}
				else
				{
					unset($_POST["x"],$_POST["y"],$_POST["type"],$_POST["owner"]);
					checkLogin();
					$_POST["user_id"]=$_SESSION["memberid"];
					$_POST["lastpost"]=date("Y-m-d G:i:s");
					$_POST["group_id"] = $_REQUEST["group_id"];
					$objUser->setArrData($_POST);
					if(!$objUser->createTopic())
					{
						$framework->tpl->assign("MESSAGE", $objUser->getErr());
					}
				}	
			}
			else
			{
				if($_REQUEST["fn"]=="join")
				{
					checkLogin();
					
					if($_REQUEST["type"]=="public")
					{
						$array=array();
						$array["group_id"] = $_REQUEST["group_id"];
						$array["user_id"]  = $_SESSION["memberid"];
						$array["joindate"] = date("Y-m-d G:i:s");
						$objUser->setArrData($array);
	
						if($objUser->joinGroup())
						{
							redirect(makeLink(array("mod"=>"member", "pg"=>"group"), "act=details&group_id=".$array["group_id"]));
						}
						else
						{
							$framework->tpl->assign("MESSAGE", $objUser->getErr());
						}
					}
					else
					{
						$array=array();
						$array["group_id"] = $_REQUEST["group_id"];
						$array["user_id"]  = $_SESSION["memberid"];
						$array["joindate"] = date("Y-m-d G:i:s");
						$array["active"]   = "N";
						$objUser->setArrData($array);
						$memid=$objUser->joinGroup();
										

						if($memid)
						{
						$frominfo = $objUser->getUserdetails($_SESSION["memberid"]);
						//	print "k";
							$toinfo= $objUser->getUserdetails( $_REQUEST["owner"]);
							//print_r($toinfo);exit;
							$grp      = $objUser->getGroupDetails($_REQUEST["group_id"]);
							$arrData  = array();
							
							$arrData["from"]     = $frominfo["username"];
							$arrData["to"]       = $toinfo["username"];
							$arrData["subject"]  = "[Group: ".$grp["groupname"]."] Join Request";
							$arrData["datetime"] = date("Y-m-d G:i:s");
							$arrData["status"]   = "U";
	
							$grpid    = $_REQUEST["group_id"];
							$from     = $frominfo["username"];
							$touserid = $_SESSION["memberid"];
							
							$comment = $_REQUEST["comments"]."<br><br>";
							$comment = $comment."To accept the Request click on the Link below <br>";
							$comment = $comment . "<a href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"group"), "act=mygroup&group_id=$grpid&touserid=$touserid&from=$from&memid=$memid&req=accept")."\">Accept Request</a>";
							$arrData["comments"] = $comment;
							$objUser->setArrData($arrData);
							$objUser->sendMessage(1);
							redirect(makeLink(array("mod"=>"member", "pg"=>"group"), "act=sendReq&grp_nm=".$grp["groupname"]."&owner=".$toinfo["username"]));
						}
						else
						{
							$framework->tpl->assign("MESSAGE", "You have already sent a request to join this Group");
						}
						
					}	
				}
				elseif($_REQUEST["fn"]=="leave")
				{
					checkLogin();
					$array=array();
					$array["group_id"] = $_REQUEST["group_id"];
					$array["user_id"]  = $_SESSION["memberid"];
					$objUser->setArrData($array);
					if($objUser->leaveGroup())
					{
						redirect(makeLink(array("mod"=>"member", "pg"=>"group"), "act=details&group_id=".$array["group_id"]));
					}
					else
					{
						$framework->tpl->assign("MESSAGE", $objUser->getErr());
					}
				}
				elseif($_REQUEST["fn"]=="invite")
				{
					checkLogin();
					$userinfo = $objUser->getUserdetails($_SESSION["memberid"]);
					if($global["new_album_functions"]==1){
						$contact = $objUser->viewFriends($_SESSION['memberid']);
						//print_r($contact);exit;
					}else{
						$contact  = $objUser->listContacts($userinfo["username"]);
					}
					
					$framework->tpl->assign("CONTACT", $contact);

				}
				else if($_REQUEST["fn"]=="joingroup")
				{
					checkLogin();
					$grpid=$_REQUEST["group_id"];
					$req_user= $getId;
					$arrData  = array();
					if(!$objUser->checkGroupMember($grpid,$req_user,1))
					{
						$grpDet=$objUser->getGroupDetails($_REQUEST["group_id"]);
						$grOwner=$objUser->getUserDetails($grpDet["user_id"]);
						$arrData["subject"]  = $_REQUEST["subject"];
						
						
						$grOwnerEmail=$grOwner['email'];
						
						$req_user= $getId;
						$req_user_det=$objUser->getUserDetails($getId);
						
						$array=array();
						$array["group_id"] = $_REQUEST["group_id"];
						$array["user_id"]  = $_SESSION["memberid"];
						$array["joindate"] = date("Y-m-d G:i:s");
						$array['active']   ='N';
						$objUser->setArrData($array);
						$id=$objUser->joinGroup();
						if($id)
						{
							
							$message="<div style='padding-left: 25px; padding-right: 25px;'>";
							$message=$message."<h5> User ".$req_user_det['username']." request for joining ".$grpDet['groupname']."  group<br></h5>";
							$message=$message."<a href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"group"), "act=details&id=$id&user_id=$req_user&group_id=$grpid&fn=creator_res&invite=accept")."\">Accept</a>&nbsp;&nbsp;&nbsp;";
							$message=$message."<a href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"group"), "act=details&user_id=$req_user&group_id=$grpid&fn=creator_res&invite=decline")."\">Decline</a>";
							$message=$message."<p>Thanks,<br>";
							$message=$message. $req_user_det["first_name"]. " " . $req_user_det["last_name"] . "</p>";
							$message=$message."</div>";
							mimeMail($grOwnerEmail,$arrData["subject"],$message,'','','Industrypage.com <'.$framework->config['admin_email'].'>');
							$framework->tpl->assign("MESSAGE","Request is sent to creator of group ".$grpDet['groupname']."");
						}
						//echo "mimeMail(".$grOwnerEmail.",".$arrData['subject'].",".$message.",'','','Industrypage.com <'.$framework->config['admin_email'].'>')";
	
					}
					else
					{
						$framework->tpl->assign("MESSAGE","You are already sent a request.");
					}	
					
				}
			else if($_REQUEST["fn"]=="creator_res")	
			{
				
				if(!$objUser->checkGroupMember($_REQUEST['group_id'],$_REQUEST["user_id"],0))
				{
					if($_REQUEST['invite']=='accept' && $_REQUEST['id'])
					{
						$objUser->UpdateGroupMember($_REQUEST['id']);
						$framework->tpl->assign("MESSAGE","Request approved successfully.");
					}	
					else if($_REQUEST["group_id"] && $_REQUEST["user_id"])
					{
							$array=array();
							$array["group_id"] = $_REQUEST["group_id"];
							$array["user_id"]  = $_REQUEST["user_id"];
							$objUser->setArrData($array);
							$objUser->leaveGroup();
							$framework->tpl->assign("MESSAGE","Request Rejected.");
					}	
				}
				else
				$framework->tpl->assign("MESSAGE","You are already member of this group.");	
						
			}
			}
			$grpDet=$objUser->getGroupDetails($_REQUEST["group_id"]);
			$grOwner=$objUser->getUserDetails($grpDet["user_id"]);
			if($grOwner["mem_type"]==3){
				$framework->tpl->assign("NOT_SHOW_DETAILS","Y");
			}
			$framework->tpl->assign("GRP_OWNER_NAME",$grOwner["username"]);	
			$framework->tpl->assign("GRP_OWNER_SCREENNAME",$grOwner["screen_name"]);	
			
			if ($_SESSION["memberid"])
			{
				if($objUser->checkGroupMember($_REQUEST["group_id"],$_SESSION["memberid"]))
				{
					$framework->tpl->assign("MEM_FLG","Y");	
				}
				else
				{
					$framework->tpl->assign("MEM_FLG","N");	
				}
				if($grpDet["type"]=='private')
				{
					if($objUser->checkGroupOwner($_REQUEST["group_id"],$_SESSION["memberid"]))
					{
						$framework->tpl->assign("OWN_FLG","Y");	
					}
					else
					{
						$framework->tpl->assign("OWN_FLG","N");	
					}
				}
				else
				{
					$framework->tpl->assign("OWN_FLG","Y");	
				}
			}
			else
			{
				$framework->tpl->assign("MEM_FLG","N");
			}
			
			if($global["show_property"] == 1) // Afsal created on 17-09-2007 fore realestatetube
			$framework->tpl->assign("group_left", SITE_PATH."/modules/member/tpl/group_left.tpl");

			$framework->tpl->assign("GRP_DET",$grpDet);
			$framework->tpl->assign("GRP_MEM",$objUser->getGroupMembers($_REQUEST["group_id"]));
			$framework->tpl->assign("GRP_DIS",$objUser->getGroupDiscussions($_REQUEST["group_id"]));
		
			if($_REQUEST["fn"]=="invite")
			{
				$framework->tpl->assign("USERDET",$objUser->getUserDetails($_SESSION["memberid"]));
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/group_invite.tpl");	
				$framework->tpl->assign("left_tpl", SITE_PATH."/modules/member/tpl/group_invite_left.tpl");	
			}
			else
			{
				list($rs, $numpad) = $objUser->topicList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&group_id=".$_REQUEST["group_id"], OBJECT, $_REQUEST['orderBy'],$_REQUEST["group_id"]);
				
				
				$framework->tpl->assign("TOPIC_LIST", $rs);
				
				$framework->tpl->assign("TOPIC_NUMPAD", $numpad);
				if($global['profile_inner']=='Y')
		 		{
		 		 $framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/profile_main.tpl");
			     $framework->tpl->assign("profile_tpl", SITE_PATH."/modules/member/tpl/group_details.tpl");
				 }	
		        else
				{
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/group_details.tpl");	
				$framework->tpl->assign("left_tpl", SITE_PATH."/modules/member/tpl/group_details_left.tpl");	
				}	
			}	
			break;
		case "member":
			if ($_SESSION["memberid"])
			{
				if($objUser->checkGroupMember($_REQUEST["group_id"],$_SESSION["memberid"]))
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
			
			list($rs, $numpad) = $objUser->memberList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy'],$_REQUEST["group_id"]);
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
			$framework->tpl->assign("GRP_MEM_LIST", $rs);
			$framework->tpl->assign("GRP_MEM_NUMPAD", $numpad);
			$framework->tpl->assign("GRP_DET",$objUser->getGroupDetails($_REQUEST["group_id"]));
			$framework->tpl->assign("GRP_MEM",$objUser->getGroupMembers($_REQUEST["group_id"]));
			$framework->tpl->assign("GRP_DIS",$objUser->getGroupDiscussions($_REQUEST["group_id"]));
			
			if($global['profile_inner']=='Y')
		 	{
			     $framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/group_members.tpl");
		    }	
		    else
			{
				$framework->tpl->assign("left_tpl", SITE_PATH."/modules/member/tpl/group_member_left.tpl");	
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/group_member.tpl");	
			}
			break;	
		case "reply":
			$framework->tpl->assign("TP_DET",$objUser->getTopicDetails($_REQUEST["tpid"]));
			if ($_SERVER['REQUEST_METHOD']=="POST")
			{
				unset($_POST["x"],$_POST["y"]);
				if(!$_SESSION["memberid"])
				{
					checkLogin();
				}
				$_POST["user_id"]  = $_SESSION["memberid"];
				$_POST["lastpost"] = date("Y-m-d G:i:s");
				$_POST["topicid"]  = $_REQUEST["tpid"];
				$objUser->setArrData($_POST);
				if(!$objUser->postReply())
				{
					$framework->tpl->assign("MESSAGE", $objUser->getErr());
				}
			}
			if ($_SESSION["memberid"])
			{
				if($objUser->checkGroupMember($_REQUEST["group_id"],$_SESSION["memberid"]))
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
			
			$framework->tpl->assign("GRP_DET",$objUser->getGroupDetails($_REQUEST["group_id"]));
			$framework->tpl->assign("GRP_MEM",$objUser->getGroupMembers($_REQUEST["group_id"]));
			$framework->tpl->assign("GRP_DIS",$objUser->getGroupDiscussions($_REQUEST["group_id"]));
	
			list($rs, $numpad) = $objUser->replyList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&group_id=".$_REQUEST["group_id"]."&tpid=".$_REQUEST["tpid"], OBJECT, $_REQUEST['orderBy'],$_REQUEST["tpid"]);
			//print_r($rs);
			$framework->tpl->assign("REPLY_LIST", $rs);
			$framework->tpl->assign("REPLY_NUMPAD", $numpad);
			
			if($global['profile_inner']=='Y')
		 	{
			     $framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/group_post.tpl");
		    }	
		    else
			{

				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/group_post.tpl");		
				$framework->tpl->assign("left_tpl", SITE_PATH."/modules/member/tpl/group_post_left.tpl");
			}
			break;
		case "mygroup":
			checkLogin();
				if($_REQUEST["chps"]!=""){
$framework->tpl->assign("chps", $_REQUEST["chps"]);	
	}
			if($_REQUEST["invite"]=="accept")
			{
				if($objUser->validGroup($_REQUEST["group_id"]))
				{
					if($objUser->validUser($_REQUEST["touserid"]))
					{
						if($_SESSION["memberid"]==$_REQUEST["touserid"])
						{
							if($objUser->acceptInvite($_REQUEST["touserid"],$_REQUEST["group_id"]))
							{
								$userinfo = $objUser->getUserdetails($_REQUEST["touserid"]);
								$grpDet   = $objUser->getGroupDetails($_REQUEST["group_id"]);
								$arrData  = array();
								$arrData["from"]     = $framework->config['site_name'];
								$arrData["to"]       = $_REQUEST["from"];
								$arrData["subject"]  = "Invitation Accepted for Group ".$grpDet["groupname"];
								$arrData["datetime"] = date("Y-m-d G:i:s");
								$arrData["status"]   = "U";
								$arrData["comments"] = $userinfo["username"] . " has accepted your invitation to join ". $framework->config['site_name'] . $grpDet["groupname"];
								$objUser->setArrData($arrData);
								$objUser->sendMessage(1);

								redirect(makeLink(array("mod"=>"member", "pg"=>"group"), "act=mygroup"));
							}
							else
							{
								$framework->tpl->assign("MESSAGE", $objUser->getErr());
							}
						}
						else
						{
							$framework->tpl->assign("MESSAGE", "You have not been invited to join this group");
						}
					}
					else
					{
						$framework->tpl->assign("MESSAGE", $objUser->getErr());
					}	
				}
				else
				{
					$framework->tpl->assign("MESSAGE", $objUser->getErr());
				}
			}
			
			
			if($_REQUEST["req"]=="accept")
			{
				
				if($objUser->validGroup($_REQUEST["group_id"]))
				{
					if($objUser->validUser($_REQUEST["touserid"]))
					{
						
							if($objUser->acceptRequest($_REQUEST["touserid"],$_REQUEST["group_id"],$_REQUEST["memid"]))
							{
								
								$userinfo = $objUser->getUserdetails($_REQUEST["touserid"]);
								$grpDet   = $objUser->getGroupDetails($_REQUEST["group_id"]);
								$arrData  = array();
								$arrData["from"]     =  $framework->config['site_name'];
								$arrData["to"]       = $_REQUEST["from"];
								$arrData["subject"]  = "Request Accepted for Group ".$grpDet["groupname"];
								$arrData["datetime"] = date("Y-m-d G:i:s");
								$arrData["status"]   = "U";
								$arrData["comments"] = "Your request has been accepted to join ".$framework->config['site_name']." Group ". $grpDet["groupname"];
								$objUser->setArrData($arrData);
								$objUser->sendMessage(1);

								redirect(makeLink(array("mod"=>"member", "pg"=>"group"), "act=mygroup"));
							}
							else
							{
								$framework->tpl->assign("MESSAGE", $objUser->getErr());
							}
						
					}
					else
					{
						$framework->tpl->assign("MESSAGE", $objUser->getErr());
					}	
				}
				else
				{
					$framework->tpl->assign("MESSAGE", $objUser->getErr());
				}
			}			
			if(!$_REQUEST['orderBy']){
				$_REQUEST['orderBy']="createdate desc";
			}
			$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act'];
			
			if ($_REQUEST["prf_target_id"])
			{
				$par = $par."&prf_target_id=".$_REQUEST['crt'];
			}
			
			if($_REQUEST['prf_target_id'])
				$memberid=$_REQUEST['prf_target_id'];
			else
				$memberid=$_SESSION["memberid"];
				
			if ($_REQUEST["crt"])
			{
				$framework->tpl->assign("CRT",$_REQUEST["crt"]);
				$framework->tpl->assign("GRP_HEADER", "Groups you own");
				list($rs, $numpad) = $objUser->groupList($_REQUEST['pageNo'], 5, $par, OBJECT, $_REQUEST['orderBy'],$_REQUEST["cat_id"],1,1,$memberid);				
			}
			else
			{
				$framework->tpl->assign("GRP_HEADER","All your Groups");
				list($rs, $numpad) = $objUser->groupList($_REQUEST['pageNo'], 5, $par, OBJECT, $_REQUEST['orderBy'],$_REQUEST["cat_id"],1,0,$memberid);				
			}		
			
			$framework->tpl->assign("GROUP_LIST", $rs);
			$framework->tpl->assign("GROUP_NUMPAD", $numpad);
			
			if($global["show_property"] == 1){ // Afsal created on 17-09-2007 fore realestatetube
			$framework->tpl->assign("group_left", SITE_PATH."/modules/member/tpl/group_left.tpl");
			$framework->tpl->assign("PROP_COUNT",$objUser->countGroupProperties($rs));
			}
			
			if($global['profile_inner']=='Y')
		 	{
		 		 $framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/profile_main.tpl");
			     $framework->tpl->assign("profile_tpl", SITE_PATH."/modules/member/tpl/mygroups.tpl");
		    }	
		    else
		   {
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/mygroups.tpl");	
				$framework->tpl->assign("left_tpl", SITE_PATH."/modules/member/tpl/mygroups_left.tpl");	
			}	
			break;
		case "sendReq":
			$owdet   = $objUser->getUsernameDetails($_REQUEST["owner"]);
			if ($global['member_screen_name']=='Y')
		     {
			 	$framework->tpl->assign("GR_OWNER",$owdet["screen_name"]);
			 }else{
				$framework->tpl->assign("GR_OWNER",$_REQUEST["owner"]);
			 }
			$framework->tpl->assign("GRP_NM",$_REQUEST["grp_nm"]);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/group_request.tpl");		
			//$framework->tpl->assign("left_tpl", SITE_PATH."/modules/member/tpl/group_request_left.tpl");	
			//print_r($_REQUEST);exit;	
			break;
		case "replyto":
			$framework->tpl->assign("TP_DET",$objUser->getTopicDetails($_REQUEST["tpid"]));
			if ($_SERVER['REQUEST_METHOD']=="POST")
			{
				unset($_POST["x"],$_POST["y"]);
				if(!$_SESSION["memberid"])
				{
					checkLogin();
				}
				$_POST["user_id"]  = $_SESSION["memberid"];
				$_POST["lastpost"] = date("Y-m-d G:i:s");
				$_POST["topicid"]  = $_REQUEST["tpid"];
				$objUser->setArrData($_POST);
				if(!$objUser->postReply())
				{
					$framework->tpl->assign("MESSAGE", $objUser->getErr());
				}
			}
			/*if ($_SESSION["memberid"])
			{
				if($objUser->checkGroupMember($_REQUEST["group_id"],$_SESSION["memberid"]))
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
			*/
			list($rs, $numpad) = $objUser->replyList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&group_id=".$_REQUEST["group_id"]."&tpid=".$_REQUEST["tpid"], OBJECT, $_REQUEST['orderBy'],$_REQUEST["tpid"]);
//			print_r($rs);
			$framework->tpl->assign("REPLY_LIST", $rs);
			$framework->tpl->assign("REPLY_NUMPAD", $numpad);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/group_post.tpl");	
			$framework->tpl->assign("left_tpl", SITE_PATH."/modules/member/tpl/group_post_left.tpl");	
			break;

		
	}
	
	$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>