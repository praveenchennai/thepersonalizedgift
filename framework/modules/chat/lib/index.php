<?php

	session_start();
	include_once(FRAMEWORK_PATH."/modules/album/lib/class.photo.php");
	include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
	include_once(FRAMEWORK_PATH."/modules/extras/lib/class.extras.php");
	include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");
	include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
	include_once(FRAMEWORK_PATH."/modules/chat/lib/src/phpfreechat.class.php");
	include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
	include_once(FRAMEWORK_PATH."/modules/album/lib/class.music.php");
	include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");
	include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
	include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
	include_once(FRAMEWORK_PATH."/modules/forum/lib/class.forum.php");
	$admin = new Admin();
	$DRAWVALUES=$admin->getDrawsettingvalues();
	$album= new Album();
	$objPhoto=new Photo();
	$objUser=new User();
	$objCms = new Cms();
	$forum = new Forum();	
	$categ = new Category();
	$music=new Music();
	$objExtras	=	new Extras();
	$admin 		=	new Admin();
	$objEmail 	= 	new Email();
	$framework->tpl->assign("AGE_LIST",$objUser->getAgeList());
	checkLogin();	
	$usr=$objUser->getUserDetails($_SESSION["memberid"]);
	$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
	$framework->tpl->assign("CAT_LIST", $objUser->getCategoryCombo($_REQUEST["mod"]));	
	$framework->tpl->assign("CAT_ARR", $objUser->getCategoryArr($_REQUEST["mod"]));
	$framework->tpl->assign("chat_tpl",SITE_PATH."/templates/green/tpl/chat.tpl");// Code for including chat.......
	$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("dating_right"));// to select the right menu of this page from database.
	$framework->tpl->assign("advertisement",1);
	$framework->tpl->assign("inner_content",1);
	$topsubmenu=$objCms->linkList("topmembersub");
	$_REQUEST +=$usr;
	//$_REQUEST+=$usr;
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
    if(!session_register('chps1'))
	{
	session_register('chps1');
    }
	if($_REQUEST['act']=='list')
	{
	 $_SESSION['chps1']=1;
	}
	elseif($_REQUEST['act']=='dating')
	{
	 $_SESSION['chps1']=2;
	}
	elseif($_REQUEST['act']=='health')
	{
	 $_SESSION['chps1']=3;
	}
	elseif($_REQUEST['act']=='finance')
	{
	 $_SESSION['chps1']=4;
	}
	elseif($_REQUEST['act']=='swapshop')
	{
	 $_SESSION['chps1']=5;
	}
	elseif($_REQUEST['act']=='coupon')
	{
	 $_SESSION['chps1']=1;
	}
	$framework->tpl->assign("search_memtype",$_SESSION['chps1']);
	switch($_REQUEST["act"])
	{
	    		 
	   case "list":
	 
	   	  unset($_COOKIE["phpfreechat"]);   
	   ////////updating the  custom table to update the relative position of a user
		    $objUser->update(1);
		    ///////////////
			require_once dirname(__FILE__)."/src/phpfreechat.class.php";
			$params = array();
			$params["title"] = "Social Community Live Chat";
			$params["nick"] = $usr['screen_name'];
			//$params["nick"] = "guest".rand(1,1000);  // setup the intitial nickname
			$params["serverid"] = "1".md5(__FILE__); // calculate a unique id for this chat
			//$params["debug"] = true;
			$params["isadmin"]   = false; // uncomment this line to give admin rights to everyones
			$params["admins"]    = array("jinson" => "jinson"); // login as admin and type /identify jinson to get the admin rights
			$params["max_msg"] = 0;
			$chat = new phpFreeChat( $params );
			if($_SESSION['login_gender'] == 0 ){
				$gender = "f";
			}else{
				$gender = "m";
			}
			$params["nickmeta"] = array('gender'=>"f");
				 
		
			///////////////fetching the social community chat users
				if (!$_REQUEST["mem_type"])
		{
              
			list($rs, $numpad,$cnt_rs, $limitList) = $objUser->chatSessList($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&mem_type=".$_REQUEST["mem_type"]."&fId=$fId&sId=$sId", OBJECT, $orderBy,$_REQUEST["txtsearch"],'chatposition','','!=');
		}
		else
		{
		
			list($rs, $numpad,$cnt_rs, $limitList) = $objUser->chatSessList($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&mem_type=".$_REQUEST["mem_type"]."&fId=$fId&sId=$sId", OBJECT, $orderBy,$_REQUEST["txtsearch"],'chatposition','','!=');
		}
		            
					$username=$usr['username'];
					list($member_result, $numpad,$cnt_rs, $limitList) = $objUser->userGrMemberList($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&mem_type=".$_REQUEST["mem_type"]."&fId=$fId&sId=$sId", OBJECT, $orderBy,$_REQUEST["txtsearch"],'referer',$username);
		 $refcount=$member_result[0]->ref_count;
		 $DRAWVALUES->registration_point;
		 /////////draw points calculation for user////////////////
		 $TotalPoints=0;
		 $regpoints=$DRAWVALUES->registration_point;
		 $limitpoints=$DRAWVALUES->noof_points;
		 $recommendupperlimit=$DRAWVALUES->setting_highvalue;
		 $TotalPoints=$TotalPoints+$refcount*$regpoints+$regpoints;
		 if($refcount>=$recommendupperlimit)
		 {
		   $mulvar=$refcount/$recommendupperlimit;
		   $remavar=$refcount%$recommendupperlimit;
		   $roundmulvar=round($mulvar);
		    if($refcount%$recommendupperlimit==0)
			{
			$TotalPoints=$TotalPoints+$limitpoints*$mulvar; 
			}
			else
			{
			$TotalPoints=$TotalPoints+($limitpoints*$roundmulvar);
			}
		  
		 
		 }
		
		 ////////////////////////////////
		if($global["finance_news"]=="1"){
			$dt=$objCms->getContent("friends_list");
			$subst=substr($dt["content"],0,200);
			$framework->tpl->assign("FRIENDS_LIST",$subst);
		}
		$framework->tpl->assign("chps1",$_SESSION['chps1']);
		$framework->tpl->assign("DRAWPOINTS",$TotalPoints);
		$framework->tpl->assign("SESS_LIST",$rs);
		$framework->tpl->assign("SESS_NUMPAD",$numpad);
		$framework->tpl->assign("SESS_LIMIT",$sess_limit);
		$framework->tpl->assign("LEFTBOTTOM",'social');
		$framework->tpl->assign("LIMIT_LIST",$sess_limit);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/myhome.tpl");
	    $framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));
		$framework->tpl->assign("CHAT",$chat->printChat());
		$framework->tpl->assign("chat_tpl",SITE_PATH."/templates/green/tpl/chat.tpl");
		
		
		 
            break;
         
		 ///////////////////////
	   case "dating":
	   //echo ($_SESSION);
	   $framework->tpl->assign("LEFTBOTTOM","dating" );
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
							$photo="1";
							$param=$param."&photo=1";
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
						$framework->tpl->assign("PROFILE_LIST", $rs);
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
	       ////updating the  custom table to update the relative position of a user
		  $objUser->update(2);
		    ///////////////
			require_once dirname(__FILE__)."/src/phpfreechat.class.php";
			$params = array();
			$params["title"] = "Dating Community Live Chat";
			$params["nick"] = $usr['screen_name'];
			//$params["nick"] = "guest".rand(1,1000);  // setup the intitial nickname
			$params["serverid"] = "2".md5(__FILE__); // calculate a unique id for this chat
			//$params["debug"] = true;
			$params["isadmin"]   = false; // uncomment this line to give admin rights to everyones
			$params["admins"]    = array("jinson" => "jinson"); // login as admin and type /identify jinson to get the admin rights
			$params["max_msg"] = 0;
			$chat = new phpFreeChat( $params );
			if($_SESSION['login_gender'] == 0 ){
				$gender = "f";
			}else{
				$gender = "m";
			}
			$params["nickmeta"] = array('gender'=>"f");
		
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("dating_right"));	
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/profile_search_dat.tpl");
		////////////////// social community chat users
		
		    $framework->tpl->assign("chps1",$_SESSION['chps1']);
			$framework->tpl->assign("LEFTBOTTOM",'dating');
			$framework->tpl->assign("CHAT",$chat->printChat());
			$framework->tpl->assign("chat_tpl",SITE_PATH."/templates/green/tpl/chat_tpl");
            break;
		    case "health":
		  list($rs_health, $numpad) = $forum->TopicList(23,$_REQUEST['pageNo'], '', "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
          $framework->tpl->assign("TOPIC_LIST", $rs_health);
		  $framework->tpl->assign("TIMEVAR",$framework->config['healthvideo']);
		  $framework->tpl->assign("CRTVAR","Health");
	    	$framework->tpl->assign("no_recent_group","1");
	   	   ////updating the  custom table to update the relative position of a user
		   if($global["health_news"]=="1"){
				$dt=$objCms->getContent("healthnews");
				$subst=substr($dt["content"],0,500);
				$framework->tpl->assign("HEALTH_NEWS",$subst);
			}
		   $objUser->update(3);
		    ///////////////
			
			require_once dirname(__FILE__)."/src/phpfreechat.class.php";
			$params = array();
			$params["title"] = "Health Community Live Chat";
			$params["nick"] = $usr['screen_name'];
			//$params["nick"] = "guest".rand(1,1000);  // setup the intitial nickname
			$params["serverid"] = "3".md5(__FILE__); // calculate a unique id for this chat
			//$params["debug"] = true;
			$params["isadmin"]   = false; // uncomment this line to give admin rights to everyones
			$params["admins"]    = array("jinson" => "jinson"); // login as admin and type /identify jinson to get the admin rights
			$params["max_msg"] = 0;
			$chat = new phpFreeChat( $params );
			if($_SESSION['login_gender'] == 0 ){
				$gender = "f";
			}else{
				$gender = "m";
			}
			$params["nickmeta"] = array('gender'=>"f");
		$framework->tpl->assign("CRT",'1000');
		include_once(SITE_PATH."/includes/flashPlayer/include.php");
		 $framework->tpl->assign("left_tpl", SITE_PATH."/modules/forum/tpl/health_left.tpl");		
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/healthhome.tpl");
		////////////////// social community chat users
			
		 $framework->tpl->assign("chps1",$_SESSION['chps1']);
			$framework->tpl->assign("LEFTBOTTOM",'health');
			$framework->tpl->assign("CHAT",$chat->printChat());
			$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("rest_right"));
			$framework->tpl->assign("chat_tpl",SITE_PATH."/templates/green/tpl/chat_tpl");
            break;	
		if($global["inner_change_reg"]=="yes"){
		
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/referral.tpl");
		}
		else
		{
		$framework->tpl->assign("tp_file",SITE_PATH."/modules/member/tpl/referral.tpl");
		}
		break;
		case "finance":
		$ctp=$objCms->linkList("finace_right");
		$framework->tpl->assign("CRTVAR","Wealth");
		list($rs_health, $numpad) = $forum->TopicList(42,$_REQUEST['pageNo'], '', "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("TOPIC_LIST", $rs_health);
		$framework->tpl->assign("left_tpl", SITE_PATH."/modules/forum/tpl/wealth_left.tpl");
		$framework->tpl->assign("TIMEVAR",$framework->config['wealthvideo']);		

		//echo ($_SESSION);
	    	$framework->tpl->assign("no_recent_group","1");
	   	   ////updating the  custom table to update the relative position of a user
		   if($global["finance_news"]=="1"){
				$dt=$objCms->getContent("financenews");
				$subst=substr($dt["content"],0,500);
				$framework->tpl->assign("FINANCE_NEWS",$subst);
			}
		  $objUser->update(4);
		    ///////////////
			require_once dirname(__FILE__)."/src/phpfreechat.class.php";
			$params = array();
			$params["title"] = "Finance Community Live Chat";
			$params["nick"] = $usr['screen_name'];
			//$params["nick"] = "guest".rand(1,1000);  // setup the intitial nickname
			$params["serverid"] = "4".md5(__FILE__); // calculate a unique id for this chat
			//$params["debug"] = true;
			$params["isadmin"]   = false; // uncomment this line to give admin rights to everyones
			$params["admins"]    = array("jinson" => "jinson"); // login as admin and type /identify jinson to get the admin rights
			$params["max_msg"] = 0;
			$chat = new phpFreeChat( $params );
			if($_SESSION['login_gender'] == 0 ){
				$gender = "f";
			}else{
				$gender = "m";
			}
			$params["nickmeta"] = array('gender'=>"f");
		include_once(SITE_PATH."/includes/flashPlayer/include.php");			
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/financehome.tpl");
		////////////////// social community chat users
		 //$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("finace_right"));
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("rest_right"));
		 $framework->tpl->assign("chps1",$_SESSION['chps1']);
			$framework->tpl->assign("LEFTBOTTOM",'finance');
			$framework->tpl->assign("CHAT",$chat->printChat());
			$framework->tpl->assign("chat_tpl",SITE_PATH."/templates/green/tpl/chat_tpl");
            break;	
		if($global["inner_change_reg"]=="yes"){
		
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/referral.tpl");
		}
		else
		{
		$framework->tpl->assign("tp_file",SITE_PATH."/modules/member/tpl/referral.tpl");
		}
		
		
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("finance_right"));
		break;
		
		case "coupon":
	$framework->tpl->assign("chps1",$_SESSION['chps1']);
    $framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));
		
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/coupon.tpl");		

		
		  $objUser->update(7);
		
		break;
		///////////for link54
		
		 case "swapshop":
		 //echo ($_SESSION);
		///////////for link54
		     /////it is for category listing for album nodule////////
			 
			// list($catlist,$numcat)=$categ->getSubcategories('46','','50');link54
			list($catlist,$numcat)=$categ->getSubcategories('44','','45');
			// print_r($catlist);exit;
			$framework->tpl->assign("CAT_LIST", $catlist);	
	        $framework->tpl->assign("CAT_ARR", $catlist);
		   // $framework->tpl->assign("CAT_LIST", $objUser->getCategoryCombo(album));	
	       // $framework->tpl->assign("CAT_ARR", $objUser->getCategoryArr(album));
		    ////////////////it is for category listing for album nodule/////////////
			   	  unset($_COOKIE["phpfreechat"]);   
	    //////////updating the  custom table to update the relative position of a user
			$usr=$objUser->getUserDetails($_SESSION["memberid"]);
		    $objUser->update(5);
		    ///////////////
			require_once FRAMEWORK_PATH."/modules/chat/lib/src/phpfreechat.class.php";
			$params = array();
			$params["title"] = "Swapshop Live Chat";
			$params["nick"] = $usr['screen_name'];
			//$params["nick"] = "guest".rand(1,1000);  // setup the intitial nickname
			$params["serverid"] = "5".md5(__FILE__); // calculate a unique id for this chat
			//$params["debug"] = true;
			$params["isadmin"]   = false; // uncomment this line to give admin rights to everyones
			$params["admins"]    = array("jinson" => "jinson"); // login as admin and type /identify jinson to get the admin rights
			$params["max_msg"] = 0;
			$data_public_url = '';
			$chat = new phpFreeChat( $params );
			if($_SESSION['login_gender'] == 0 ){
				$gender = "f";
			}else{
				$gender = "m";
			}
			$params["nickmeta"] = array('gender'=>"f");
			///////////////fetching the social community chat users
				if (!$_REQUEST["mem_type"])
		{
              
			list($rs, $numpad,$cnt_rs, $limitList) = $objUser->chatSessList($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&mem_type=".$_REQUEST["mem_type"]."&fId=$fId&sId=$sId", OBJECT, $orderBy,$_REQUEST["txtsearch"],'chatposition','','!=');
		}
		else
		{
		
			list($rs, $numpad,$cnt_rs, $limitList) = $objUser->chatSessList($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&mem_type=".$_REQUEST["mem_type"]."&fId=$fId&sId=$sId", OBJECT, $orderBy,$_REQUEST["txtsearch"],'chatposition','','!=');
		}
        $framework->tpl->assign("SESS_LIST",$rs);
		$framework->tpl->assign("SESS_NUMPAD",$numpad);
		$framework->tpl->assign("SESS_LIMIT",$sess_limit);
		$framework->tpl->assign("LEFTBOTTOM",'swapshop');
		$framework->tpl->assign("LIMIT_LIST",$sess_limit);
		//$framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/myhome.tpl");
		////////////////// social community chat users
		  if($global["inner_change_reg"]=="yes")
							{
							$framework->tpl->assign("CHAT",$chat->printChat());
		                    $framework->tpl->assign("chat_tpl",SITE_PATH."/templates/green/tpl/chat.tpl");
							
							}
 
			$framework->tpl->assign("TITLE_HEAD", "Product List");
			$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act'];
			if ($_REQUEST["crt"])
			{
				$par = $par."&crt=".$_REQUEST['crt'];
			}
			if ($_REQUEST["cat_id"])
			{
				$par = $par."&cat_id=".$_REQUEST['cat_id'];
			}
			
			
			
			if($_REQUEST["txtSearch"])
			{
				$stxt=$_REQUEST["txtSearch"];
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
				$framework->tpl->assign("PH_HEADER", $catname["cat_name"]);
				list($rs, $numpad) = $objPhoto->photoList($_REQUEST['pageNo'], 5, $par, OBJECT, "id desc",$_REQUEST["cat_id"],$stxt,'','','','album_products','product');				
			}
			elseif ($_REQUEST["crt"])
			{
				if ($_REQUEST["crt"]=="M1")
				{
					$pheader="Most Recent";
					$field="postdate desc";
				}
				elseif ($_REQUEST["crt"]=="M2")
				{
					$pheader="Most Viewed";
					$field="views desc";
				}
				elseif ($_REQUEST["crt"]=="M3")
				{
					$pheader="Most Discussed";
					$field="cmcnt desc";
				}
				elseif ($_REQUEST["crt"]=="M4")
				{
					$pheader="Top Rated";
					$field="rating desc";
				}
				elseif ($_REQUEST["crt"]=="M5")
				{
					$pheader="Top Favorites";
					$field="favcnt desc";
				}
				
				$framework->tpl->assign("CRT",$_REQUEST["crt"]);
				$framework->tpl->assign("PH_HEADER", $pheader);
				list($rs, $numpad) = $objPhoto->photoList($_REQUEST['pageNo'], 5,$par, OBJECT, $field,0,$stxt,'','','','album_products','product');
			}	
			else
			{
				//print_r($par);exit;
				$framework->tpl->assign("PH_HEADER", "All Products");
				if($_REQUEST["user_id"]){
					//checkLogin();
					list($rs, $numpad) =$objPhoto->photoList($_REQUEST['pageNo'], 5,$par, OBJECT, 'id desc',0,$stxt,'','','','album_products','product');
				}else{
					list($rs, $numpad) =$objPhoto->photoList($_REQUEST['pageNo'], 5,$par, OBJECT, 'id desc',0,$stxt,'','','','album_products','product');
				}
			}	
			for ($i=0;$i<sizeof($rs);$i++)	
			{
				$medet=$objUser->getUsernameDetails($rs[$i]->username);
				$rs[$i]->nick_name=$medet["nick_name"];
				$rs[$i]->mem_type=$medet["mem_type"];
				if($medet["user_id"]==$_SESSION["memberid"]){
					$rs[$i]->owner="Y";
				}
				if($medet["mem_type"]==3){
					$medet1=$objUser->getUserdetails($medet["user_id"]);
					if($medet1["friends_can_see"]=='Y'){
						if($objUser->isFriends($medet["user_id"],$_SESSION["memberid"])==true){
							$rs[$i]->show_profile="Y";
						}else{
							$rs[$i]->show_profile="N";
						}
					}else{
						$rs[$i]->show_profile="N";
					}
					if($medet["user_id"]==$_SESSION["memberid"]){
						$rs[$i]->show_profile="Y";
					}
				}else{
					$rs[$i]->show_profile="Y";
				}
			}
			  ////////for link 54
				### code using to show the pagination .....
				### Author : Jipson Thomas.................
				### Date   :08/01/2008.....................
					if($global["show_private"]=="Y"){
						if($_REQUEST["cat_id"]){
							$ctid=$_REQUEST["cat_id"];
						 }else{
							$ctid=0;
						}
						if(!$field)
							$field="id desc";
						list($res, $numpades) = $objPhoto->photoList('','500000', $par, OBJECT,$field,$ctid,$stxt,'','','','album_products','product');
						$cnt=0;
						for ($i=0;$i<sizeof($res);$i++)
							{
								if($res[$i]->privacy=='private'){
									if($res[$i]->friends_can_see=='Y'){
										if($objUser->isFriends($res[$i]->user_id,$_SESSION["memberid"])==true){
											$cnt++;
										}
									}
									if($res[$i]->user_id==$_SESSION["memberid"]){
										$cnt++;
									}
								}else{
									$cnt++;
								}
							}
						if(count($res)>0){
							$pos=strpos($numpad,"|");
							$stf=substr($numpad,0,$pos);
							$stl=stristr($numpad,":");
							$stl1=stristr($stl,"&nbsp; &nbsp;<font color=black>|</font>&nbsp; &nbsp;");
							$npd=$stf."<font color=black>|</font>&nbsp; &nbsp;Matching Records : $cnt".$stl1;
							$framework->tpl->assign("NUMPAD", $npd);
						}
					}
				###  End....................................
			    $framework->tpl->assign("chps1",$_SESSION['chps1']);
				$framework->tpl->assign("PHOTO_LIST", $rs);
				$framework->tpl->assign("SWAPSHOP",1);
				$framework->tpl->assign("PHOTO_NUMPAD", $numpad);
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/product_list.tpl");
				$framework->tpl->assign("left_tpl", SITE_PATH."/modules/album/tpl/product_list_left.tpl");		
				break;
			
	}
	if($global["inner_change_reg"]=="yes")
	{
	$framework->tpl->assign("advertisement",1);
	$framework->tpl->assign("inner_content",1);
	//$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
	}
	else{
	$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/myaccount.tpl");
	}
	
	$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>