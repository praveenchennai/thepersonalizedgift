<?php 
session_start();
include_once(FRAMEWORK_PATH."/modules/blog/lib/class.blog.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
$blog = new Blog();
$album  = new Album();
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
$objCms = new Cms();
$blog = new Blog();
$objUser     =	new User();

if($_REQUEST['recentid']!="")
	{
	 $_SESSION['chps1']=1;
	}
$framework->tpl->assign("LEFTBOTTOM",'blog');
if(isset($_SESSION['chps1']))
{
	$framework->tpl->assign("chps1",$_SESSION['chps1']);
}
         $Date=date("Y-m-d H:i:s");
		$user_id=$_SESSION['memberid'];
		$framework->tpl->assign("CUR_DATE", $Date);
		
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
	$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("fiance_right"));// to select the right menu of this page from database.
	$rightmenu=$objCms->linkList("dating_right");
}
elseif($_SESSION['chps1']==5){
	$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("dating_right"));// to select the right menu of this page from database.
	$rightmenu=$objCms->linkList("dating_right");
}
else
{
$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));
}
if($_REQUEST["pid"]!=""){
	$framework->tpl->assign("pid", $_REQUEST["pid"]);
}
if(isset($_REQUEST['subcat_id'])){
    $subcat_id=$_REQUEST['subcat_id'];
}
if(isset($_REQUEST['parent_id'])){
    $parent_id=$_REQUEST['parent_id'];
}
if($global['show_screen_name_only']=='1'){
			if($_REQUEST['screen_name']){
				$user1 = new User();
				$uname1= $_REQUEST['screen_name'];
				$arrUname1 = explode('/',$uname1);
				$usrde=$user1->allUsers('screen_name',$arrUname1[0]);
				$_REQUEST['username']=$usrde[0]->username;
				$userDetails = $user1->getScreennameDetails($arrUname1[0]);
				$user_id = $userDetails['id'];
			$countBlog=$blog->getCountBlog($user_id); 			         
            $blogDetails = $blog->getBlog($user_id);
			$framework->tpl->assign("MEMBER", $objUser->getUserdetails($user_id));
			
			if($_SESSION["memberid"]==$user_id)
			{
				$owner="Y";
			}
			else
			{
				$owner="N";
			}
			$framework->tpl->assign("OWNER",$owner);
			///////////////////////fetching the group details//////////////////////
			$tbl="album_video";
			list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 15,$par, OBJECT, 'title:ASC',$tbl,'video',$stxt,$alb,$user_id );$framework->tpl->assign("IMG_EXT","NO");
			if($global["show_private"]=="Y"){
				$cnv=0;
				for ($i=0;$i<sizeof($rs);$i++)
					{
						if($rs[$i]->privacy=='private'){
							if($rs[$i]->friends_can_see=='Y'){
								if($objUser->isFriends($getId,$_SESSION["memberid"])==true){
									$cnv++;
									$rs[$i]->show_video="Y";
								}else{
									$rs[$i]->show_video="N";
								}
							}else{
								$rs[$i]->show_video="N";
							}
							if($getId==$_SESSION["memberid"]){
								$cnv++;
								$rs[$i]->show_video="Y";
							}
						}else{
							$cnv++;
							$rs[$i]->show_video="Y";
						}
					}
			}
			$framework->tpl->assign("PGFILE","video");
			$framework->tpl->assign("FILE_ID","music_id");
		    $framework->tpl->assign("PHOTO_LIST",$rs);
			//print_r($rs);exit;
			///////////////////////////////////
			
			$media="Photo";
			$tbl="album_photos";
			$type="photo";

			list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 15,$par, OBJECT, 'title:ASC',$tbl,'video',$stxt,$alb,$user_id );
			if($global["show_private"]=="Y"){
				$cnv=0;
				for ($i=0;$i<sizeof($rs);$i++)
					{
						if($rs[$i]->privacy=='private'){
							if($rs[$i]->friends_can_see=='Y'){
								if($objUser->isFriends($getId,$_SESSION["memberid"])==true){
									$cnv++;
									$rs[$i]->show_video="Y";
								}else{
									$rs[$i]->show_video="N";
								}
							}else{
								$rs[$i]->show_video="N";
							}
							if($getId==$_SESSION["memberid"]){
								$cnv++;
								$rs[$i]->show_video="Y";
							}
						}else{
							$cnv++;
							$rs[$i]->show_video="Y";
						}
					}
			}
			$framework->tpl->assign("PGFILE","photo");
			$framework->tpl->assign("FILE_ID","photo_id");
		   $framework->tpl->assign("PHOTOS_LIST",$rs);
		 //  print_r($rs);exit;
			//$framework->tpl->assign("PHOTOS_LIST",$rs);
			
				list($rs_group, $numpad) = $objUser->groupList($_REQUEST['pageNo'], 5, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy'],$_REQUEST["cat_id"],1,0,$user_id);				
					
			$framework->tpl->assign("GROUP_LIST", $rs_group);
		
		$framework->tpl->assign("PRF_ABOUT", $objUser->getPrfDetails($user_id,'profile_about'));//getting details from profile_about

$framework->tpl->assign("PRF_CONTACT", $objUser->getPrfDetails($user_id,'profile_contact'));//getting details from profile_contact

$framework->tpl->assign("PRF_COLLEGE", $objUser->getPrfDetails($user_id,'profile_college'));//getting details from profile_college

$framework->tpl->assign("PRF_SCHOOL", $objUser->getPrfDetails($user_id,'profile_school'));//getting details from profile_school

$framework->tpl->assign("PRF_BOOKS", $objUser->getPrfDetails($user_id,'profile_books'));//getting details from profile_books

$framework->tpl->assign("PRF_FOOD", $objUser->getPrfDetails($user_id,'profile_food'));//getting details from profile_food

$framework->tpl->assign("PRF_MOVIES", $objUser->getPrfDetails($user_id,'profile_movies'));//getting details from profile_movies

$framework->tpl->assign("PRF_MUSIC", $objUser->getPrfDetails($user_id,'profile_music'));//getting details from profile_music

$framework->tpl->assign("PRF_STYLE",$objUser->getPrfDetails($user_id,'profile_style'));//getting details from profile_style

$framework->tpl->assign("PRF_TV", $objUser->getPrfDetails($user_id,'profile_tv'));//getting details from profile_tv

$framework->tpl->assign("PRF_TRAVEL", $objUser->getPrfDetails($user_id,'profile_travel'));//getting details from profile_tv

$framework->tpl->assign("PRF_GAMES", $objUser->getPrfDetails($user_id,'profile_games'));//getting details from profile_games

			}
		}
 switch($_REQUEST['act']) {
 
    case "list":
		
        if($_REQUEST['username']) {
            $user = new User();
			$uname= $_REQUEST['username'];
			$arrUname = explode('/',$uname);
			 
			$userDetails = $user->getUsernameDetails($arrUname[0]);
            $user_id = $userDetails['id'];
			$countBlog=$blog->getCountBlog($user_id); 			         
            $blogDetails = $blog->getBlog($user_id);
            $_REQUEST['subcat_id'] = $blogDetails['subcat_id'];
            $_REQUEST['parent_id'] = $blogDetails['cat_id'];
        }elseif(isset($_REQUEST['user_id'])){
            $user_id=$_REQUEST['user_id'];
        }else{
			#if($global['show_myprofile']=='1'){
			#	$user_id =$_SESSION["memberid"];
			#	$countBlog=$blog->getCountBlog($user_id); 			         
			#	$blogDetails = $blog->getBlog($user_id);
			#	$_REQUEST['subcat_id'] = $blogDetails['subcat_id'];
			#	$_REQUEST['parent_id'] = $blogDetails['cat_id'];
			#}else{
				redirect(SITE_URL);
			#}
        }
        include(SITE_PATH."/modules/blog/lib/blog_customize.php");
        $framework->tpl->assign("USER_ID", $user_id);
        $framework->tpl->assign("CAT_NAME", $blog->getCatname($_REQUEST['parent_id']));
        $framework->tpl->assign("SUBCAT_NAME", $blog->getCatname($_REQUEST['subcat_id']));
		$framework->tpl->assign("type", $blog->settingsGet());
        $additionalVar="user_id=".$_REQUEST['user_id']."&parent_id=".$_REQUEST['parent_id']."&subcat_id=".$_REQUEST['subcat_id'];
        list($blog_rs, $numpad) = $blog->blogentryList($blog_Id,$_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&$additionalVar", OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("BLOG_LIST", $blog_rs);
        $framework->tpl->assign("BLOG_NUMPAD", $numpad);
		$framework->tpl->assign("BLOG_ID",$blog_Id);
		
		
		// profile
		
				include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
				include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
			
				$objUser=new User();
				$objAlbum=new Album();
				
				
				//after post
							if($_SERVER['REQUEST_METHOD'] == "POST") 
							{
								if(isset($_POST["comment"]))
								{
									checkLogin();
									$req = &$_REQUEST;
									$userid=$_REQUEST["userid"];
									$username=$_REQUEST["username"];
										$screen_name=$_REQUEST["screen_name"];							 
									if( ($message = $objUser->insertComment($req,$_REQUEST["userid"])) === true )
									{
										redirect(makeLink(array('mod'=>blog,'pg'=>blog_userentry),"act=list&screen_name=$screen_name"));
									}
								}
								elseif(isset($_POST["tip"]))
								{	
									checkLogin();
									$arr = array();
									$arr["user_id"]=$_SESSION["memberid"];
									$arr["tiptext"]=$_POST["tip"];
									$objUser->setArrData($arr);
									if(!$objUser->insertTip($getId))
									{
										$framework->tpl->assign("MESSAGE", $objUser->getErr());	
										$framework->tpl->assign("USERINFO", $_POST);
										
									}
									else
									{
										$userDet=$objUser->getUserdetails($getId);
										$framework->tpl->assign("USERINFO", $userDet);
									}
									
								}	
							}
				
				$getId=$user_id;
				if ($getId)
				{
				include_once(SITE_PATH."/includes/flashPlayer/include.php");
					$framework->tpl->assign("MS_COUNT",$objAlbum->getMediaCount($getId,'album_music','public'));
					if($global["show_private"]=="Y"){
					
					}else{
						$framework->tpl->assign("MV_COUNT",$objAlbum->getMediaCount($getId,'album_video','public'));
					}
					if($global["show_private"]=="Y"){
					
					}else{
						$framework->tpl->assign("PH_COUNT",$objAlbum->getMediaCount($getId,'album_photos','public'));
					}
					$framework->tpl->assign("GR_COUNT",$objUser->getGroupCount($getId));
					
					if($global["show_property"] == 1){
					
						list($rs) = $album->propertySearch($_REQUEST['pageNo'],1, $par, ARRAY_A, "id:DESC","user_id,default_vdo",$getId.","."0",'',"=".",".">");
						$framework->tpl->assign("PROP_DETAILS",$rs);
						list($rsall) = $album->propertySearch($_REQUEST['pageNo'],15, $par, ARRAY_A, "id:DESC","user_id",$getId,'',"=");
						$framework->tpl->assign("VIDEO_DETAILS",$rsall);
						$framework->tpl->assign("MEMBER", $objUser->getUserdetails($getId));
						$mem=$album->getSubscriberDetails($getId,"album");
						$framework->tpl->assign("SUBSCRIPTION",$mem);
						
					}
					
					$framework->tpl->assign("GR_COUNT",$objUser->getGroupCount($getId));
					$userDet=$objUser->getUserdetails($getId);
					$framework->tpl->assign("USERINFO", $userDet);//getting details from member_master
					$framework->tpl->assign("FR_COUNT",$objUser->getFriendsCount($userDet["id"]));
				
					
					$framework->tpl->assign("PRF_ABOUT", $objUser->getPrfDetails($getId,'profile_about'));//getting details from profile_about
					
					$framework->tpl->assign("PRF_CONTACT", $objUser->getPrfDetails($getId,'profile_contact'));//getting details from profile_contact
						
					$framework->tpl->assign("PRF_COLLEGE", $objUser->getPrfDetails($getId,'profile_college'));//getting details from profile_college
				
					$framework->tpl->assign("PRF_SCHOOL", $objUser->getPrfDetails($getId,'profile_school'));//getting details from profile_school
				
					$framework->tpl->assign("PRF_BOOKS", $objUser->getPrfDetails($getId,'profile_books'));//getting details from profile_books
				
					$framework->tpl->assign("PRF_FOOD", $objUser->getPrfDetails($getId,'profile_food'));//getting details from profile_food
					
					$framework->tpl->assign("PRF_MOVIES", $objUser->getPrfDetails($getId,'profile_movies'));//getting details from profile_movies
				
					$framework->tpl->assign("PRF_MUSIC", $objUser->getPrfDetails($getId,'profile_music'));//getting details from profile_music
					
					$framework->tpl->assign("PRF_STYLE",$objUser->getPrfDetails($getId,'profile_style'));//getting details from profile_style
				
					$framework->tpl->assign("PRF_TV", $objUser->getPrfDetails($getId,'profile_tv'));//getting details from profile_tv
				
					$framework->tpl->assign("PRF_TRAVEL", $objUser->getPrfDetails($getId,'profile_travel'));//getting details from profile_tv
				
					$framework->tpl->assign("PRF_GAMES", $objUser->getPrfDetails($getId,'profile_games'));//getting details from profile_games
				
				
					$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
					$cname=$objUser->getCountryName($userDet["country"]);
					$framework->tpl->assign("USERCTR", $cname);
				}

			$uid=$getId;
			if ($_REQUEST["type"]!="tip")
			{
				$pgn1="1";
			}	
			else
			{
				$pgn1=$_REQUEST['pageNo'];
			}
			
			list($blog_rs, $numpad,$count) = $objUser->getMessage($_REQUEST['pageNo'], 5, "uid=$uid&mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $orderBy,$uid);
		
		  ###to list private users on the search result (Link 54)
						### Modified on 4 Jan 2008.
		                ### Modified By Jinson.
						if($global['show_private']=='Y')
						{ for ($i=0;$i<sizeof($blog_rs);$i++)
										{
										$medet=$objUser->getUsernameDetails($blog_rs[$i]->username);
										if($medet["user_id"]==$_SESSION["memberid"])
										{$blog_rs[$i]->owner="Y";}
										if($medet["mem_type"]==3){
													if($medet["friends_can_see"]=='Y')
													            {
										                     if($objUser->isFriends($medet["user_id"],$_SESSION["memberid"])==true)
															 {$blog_rs[$i]->show_profile="Y";}
															 else{$blog_rs[$i]->show_profile="N";}
										                         }
																			else{
															$blog_rs[$i]->show_profile="N";
																				}
																				if($medet["user_id"]==$_SESSION["memberid"]){
						                                                        $blog_rs[$i]->show_profile="Y";
					                                                             }
																				
																 }
																 else{$blog_rs[$i]->show_profile="Y";}										
	
										}
						    }//if
					    //////////////////
			$num_pages=ceil($count/5);
			if ($num_pages>1)
			{
				
					if ($_REQUEST["pageNo"])
					{
						if ($_REQUEST["type"]!="tip")
						{
					
							if($_REQUEST["pageNo"]!=$num_pages)
							{
								$pgNo=$_REQUEST["pageNo"]+1;
								$framework->tpl->assign("NEXT_PG", $pgNo);
								
							}
							if(($_REQUEST["pageNo"]!=1) && ($_REQUEST["pageNo"]!=''))
							{
								$pgNo=$_REQUEST["pageNo"]-1;
								$framework->tpl->assign("PRE_PG", $pgNo);
							}
						}			
						
					}
					else
					{
							$pgNo=2;
							$framework->tpl->assign("NEXT_PG", $pgNo);
					}
			}
			$framework->tpl->assign("COMMENT_LIST", $blog_rs);
			$framework->tpl->assign("COMMENT_NUMPAD", $numpad);
			
			if ($_REQUEST["pgn"]<1)
			{
				
				$pgn="0";
				$start=0;
				$end=5;
			}	
			else
			{
				$pgn=$_REQUEST['pgn'];
				$start=$pgn+5;
				$end=$pgn+5;
			}
		
			list($rs,$count) = $objUser->getTip($start,$end,$uid);
			
			$num_pages=ceil($count/5);
			

			if ($num_pages>1)
			{
			
					if ($_REQUEST["pgn"])
					{
					
							if($_REQUEST["pgn"]<$num_pages-1)
							{
								$pgn=$_REQUEST["pgn"]+1;
								$framework->tpl->assign("PGN", $pgn);
								$framework->tpl->assign("NEXT_PG1", $pgn);
								
							}
							else
							{
								$pgn=$_REQUEST["pgn"]-1;
								$framework->tpl->assign("PGN", $pgn);
								$framework->tpl->assign("PRE_PG1", $pgn);
							}
						
					}
					else
					{
							$pgn=1;
							$framework->tpl->assign("PGN", $pgn);
							$framework->tpl->assign("NEXT_PG1", $pgn);
					}

			}
			$framework->tpl->assign("TIP_LIST", $rs);
		
		//end profile
		
		if($getId){
		
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/user_userblog.tpl");
		
		}else{
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/blogclient_null.tpl");
		}
        break;
    case "form":
        checkLogin();
        if(isset($_SESSION['memberid'])){
            $user_id=$_SESSION['memberid'];
        }
        include_once(SITE_PATH."/includes/areaedit/include.php");
        $framework->tpl->assign("BLOG_DETAILS", $blog->getBlog($user_id));
        $Date=date("Y-m-d H:i:s");
        $framework->tpl->assign("CUR_DATE", $Date);
        $framework->tpl->assign("USER_ID", $user_id);
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $req = &$_REQUEST;
            if( ($message = $blog->blogentryAddEdit($req)) === true ) {
                redirect(makeLink(array("mod"=>"blog", "pg"=>"blog_userentry"),"act=list"));
            }
            $framework->tpl->assign("MESSAGE", $message);
        }
        editorInit('post_description');
        if($message) {
            $framework->tpl->assign("POSTS", $_POST);
        } elseif($id) {
            $framework->tpl->assign("BLOG_ENTRY_DETAILS", $blog->getBlogentry($id));
        }
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/blogs_entry_form.tpl");
        break;
    case "rating":		
		checkLogin();
		$req = &$_REQUEST;
		$rateval=$req['rateval'];
        if(isset($_SESSION['memberid'])){
            $userID=$_SESSION['memberid'];
        }
        Rating($userID,$rateval,$_REQUEST['id'],'blog','blog_post','blog_rating');
        $additionalUrl= "user_id=".$_REQUEST['user_id']."&subcat_id=$subcat_id&parent_id=$parent_id";
		//print_r($additionalUrl);exit;
        redirect(makeLink(array("mod"=>"blog", "pg"=>"blog_userentry"),"act=list&$additionalUrl"));
        break;
}
if($_REQUEST['act']=="list"){
#	if($global['show_myprofile']=='1'){
#		if($user_id ==$_SESSION["memberid"]){
#			 $framework->tpl->display($global['curr_tpl']."/inner.tpl");
#		}else{
#			$framework->tpl->display(SITE_PATH."/modules/blog/tpl/blogframe.tpl");
#		}
#	}else{
		$framework->tpl->display(SITE_PATH."/modules/blog/tpl/blogframe.tpl");
#	}
}else{
    $framework->tpl->display($global['curr_tpl']."/inner.tpl");
}
?>