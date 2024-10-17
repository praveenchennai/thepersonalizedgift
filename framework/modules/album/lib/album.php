<?php
$memberID = $_SESSION['memberid'];
include_once(FRAMEWORK_PATH."/modules/album/lib/class.video.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.photo.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.xmlParser.php");
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");
include_once(FRAMEWORK_PATH."/modules/map/lib/G_Maps.class.php");
//include_once(FRAMEWORK_PATH."/modules/callendar/lib/class.calendarevents.php");
	$album       = new Album();
	$objPhoto    = new Photo();
	$objUser     = new User();
	$objCategory = new Category();
	$objVideo	 = new Video();
	$objCms 	 = new Cms();
	$flyer			=	new	Flyer();
	$gmap	 		= 	new G_Maps();
	//$objEVENTS		= 	new CalendarEvents();

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
if($_REQUEST["act"]!="myalbum" && $_REQUEST["crt"]!="5"){
	if($_REQUEST["act"]!="favr" && $_REQUEST["crt"]!="5"){
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
		else
		{
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));
		}
	}
}
	if($_REQUEST["pid"]!=""){
$framework->tpl->assign("pid", $_REQUEST["pid"]);	
	}

$framework->tpl->assign("advertisement",1);
$framework->tpl->assign("inner_content",1);
switch($_REQUEST['act'])
{
	case "mysoldtracks":
			checkLogin();
			if($_REQUEST['pageNo']==1){
				$_SESSION["gtotal"]=0;
			}
			//print_r($_REQUEST["gtotal"]);
			$params="mod={$mod}&pg={$pg}&act=mysoldtracks";
			list($det1,$numpad1)=$album->getSolidDetailsArtistAlbum($_REQUEST['pageNo'],10,$params,OBJECT,$_SESSION["memberid"]);
			list($det,$numpad)=$album->getSolidDetailsArtist($_REQUEST['pageNo'],10,$params,OBJECT,$_SESSION["memberid"]);
		//	print_r($det1);exit;
			foreach($det as $dt){
				$dt->price= $global['track_price'];
			}
			if($det){
				$amt=sizeof($det)*$global['track_price'];
				//$_SESSION["gtotal"]=$_SESSION["gtotal"]+$amt;
				//$det["total_amount"]=$amt;
				$framework->tpl->assign("TOTAL",$amt);
				//$framework->tpl->assign("GTOTAL",$_SESSION["gtotal"]);
				
			}
			for($i=0;$i<sizeof($det1);$i++){
					$sz=sizeof($det1[$i]);
					
					//echo $det1[$i][member_id];exit;
				//for($j=0;$j<$sz;$j++){
				//print_r($j);exit;
				//echo $det1[$i][$j][member_id];exit;
					$usr=$objUser->getUserdetails($det1[$i][member_id]);
					$det1[$i][first_name]=$usr[first_name];
					$det1[$i][nick_name]=$usr[nick_name];
					//print($usr[first_name]);
					//exit;
					
										
					//$det1[$i][$j]->udet=$usr;
				//}
			}
		//	print_r($det1);exit;
			$amt1=$global['album_price'];
			$framework->tpl->assign("DET",$det);
			$framework->tpl->assign("DET1",$det1);
			$framework->tpl->assign("ALB_PRICE",$amt1);
			$framework->tpl->assign("NUMPAD1",$numpad1);
			$framework->tpl->assign("TITLE_HEAD","My Music Sales");
			$framework->tpl->assign("NUMPAD",$numpad);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/mysoldtrack.tpl");
		break;

	case "subscription":

			if($_REQUEST["type"]=="video"){
				$mem=$objVideo->getSubscriberDetails($_SESSION["memberid"],"video");
				if($mem){
					if($_REQUEST["membid"]){
						$memid=$_REQUEST["membid"];
					}else{
						$memid=$mem[0]["id"];
					}
					$memdet=$objUser->getUserdetails($memid);
					$param="mod={$mod}&pg={$pg}&act=subscription&membid={$memid}&type=video";
					list($res,$numpad)=$objVideo->getSubscriptionDetails($_REQUEST['pageNo'],2,$param,$memid,"album_video");
					
					if($global['show_private']=='Y'){
						for ($i=0;$i<sizeof($res);$i++)
						{
							if($res[$i]->privacy=="private"){
								if($res[$i]->friends_can_see=='Y'){
									if($objUser->isFriends($res[$i]->user_id,$_SESSION["memberid"])==true){
										$res[$i]->show_media="Y";
										
									}else{
										$res[$i]->show_media="N";
										
									}
								}else{
									$res[$i]->show_media="N";
									if($res[$i]->user_id==$_SESSION["memberid"]){
										$res[$i]->show_media="Y";
									}
								}
							}else{
								$res[$i]->show_media="Y";
							}
						}
					}
				//print_r($res);

					$framework->tpl->assign("SUBSCRIBER_DET",$mem);
					$framework->tpl->assign("SUBSCRIPTION_DET",$res);
					$framework->tpl->assign("SUBSCRIPTION_NUMPAD",$numpad);
					$framework->tpl->assign("uname",$memdet["screen_name"]);
				}
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/video_subs_list.tpl");
				$framework->tpl->assign("left_tpl", SITE_PATH."/modules/album/tpl/video_subs_list_left.tpl");
			}elseif($_REQUEST["type"]=="music"){
				$mem=$objVideo->getSubscriberDetails($_SESSION["memberid"],"music");
				//print_r($mem);exit;
				if($mem){
					if($_REQUEST["membid"]){
						$memid=$_REQUEST["membid"];
					}else{
						$memid=$mem[0]["id"];
					}
					$param="mod={$mod}&pg={$pg}&act=subscription&membid={$memid}&type=music";
					list($res,$numpad)=$objVideo->getSubscriptionDetails($_REQUEST['pageNo'],5,$param,$memid,"album_music");
					//print_r($res);exit;
					$framework->tpl->assign("SUBSCRIBER_DET",$mem);
					$framework->tpl->assign("SUBSCRIPTION_DET",$res);
					$framework->tpl->assign("SUBSCRIPTION_NUMPAD",$numpad);
				}
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/music_subs_list.tpl");
				
			}elseif($_REQUEST["type"]=="photo"){
				$mem=$objVideo->getSubscriberDetails($_SESSION["memberid"],"photo");
				if($mem){
					if($_REQUEST["membid"]){
						$memid=$_REQUEST["membid"];
					}else{
						$memid=$mem[0]["id"];
					}
					$param="mod={$mod}&pg={$pg}&act=subscription&membid={$memid}&type=photo";
					list($res,$numpad)=$objVideo->getSubscriptionDetails($_REQUEST['pageNo'],5,$param,$memid,"album_photos");
					$memdet=$objUser->getUserdetails($memid);
					$framework->tpl->assign("uname",$memdet["screen_name"]);
					if($global['show_private']=='Y'){
						for ($i=0;$i<sizeof($res);$i++)
						{
							if($res[$i]->privacy=="private"){
								if($res[$i]->friends_can_see=='Y'){
									if($objUser->isFriends($res[$i]->user_id,$_SESSION["memberid"])==true){
										$res[$i]->show_media="Y";
										
									}else{
										$res[$i]->show_media="N";
										
									}
								}else{
									$res[$i]->show_media="N";
									if($res[$i]->user_id==$_SESSION["memberid"]){
										$res[$i]->show_media="Y";
									}
								}
							}else{
								$res[$i]->show_media="Y";
							}
						}
					}
					$framework->tpl->assign("SUBSCRIBER_DET",$mem);
					$framework->tpl->assign("SUBSCRIPTION_DET",$res);
					$framework->tpl->assign("SUBSCRIPTION_NUMPAD",$numpad);
				}
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/photo_subs_list.tpl");
				
			}elseif($_REQUEST["type"]=="album"){
				
				
				 if($_REQUEST['unsubscribe'] == "yes" && $_REQUEST['sid'] > 0)
				 {
					$album->Unsubscribe($_SESSION['memberid'],$_REQUEST['sid'],"album");
					setMessage("Subscription removed.");
					
				 }
				 
				 
				 $mem=$album->getSubscriberDetails($_SESSION["memberid"],"album");
				if($mem)
				{
					if($_REQUEST["sid"])
					{
						$memid = $_REQUEST["sid"];
					}
					
					$param="mod={$mod}&pg={$pg}&act=subscription&membid={$memid}&type=album";
					list($res,$numpad)= $album->getAlbumSubscriptionDetails($_REQUEST['pageNo'],12,$param,ARRAY_A,"a.post_date DESC",$memid);
					
					if(!isset($_REQUEST["sid"]))
					{
						$sel_name = "New Property";
					}
					else
					{
						$sel_name = $res[0]["username"];
					}
					
					$framework->tpl->assign("SUBSCRIBER_DET",$mem);
					$framework->tpl->assign("SELNAME",$sel_name);
					$framework->tpl->assign("SUBSCRIPTION_DET",$res);
					$framework->tpl->assign("NUMPAD",$numpad);
				}
				$framework->tpl->assign("sub_mem_det", SITE_PATH."/modules/album/tpl/subscribe_users.tpl");
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/video_subs_list.tpl");

			}
		break;

	case "add":
		checkLogin();
		/*
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			$req = &$_POST;
			if( ($message = $album->createAlbum($req)) === true)
			{
				if ($_REQUEST["urlalb"])
				{
					redirect("index.php?".$_REQUEST["urlalb"]);
				}
			}
		}
		*/
		//include_once(SITE_PATH."/includes/areaedit/include.php");
		//editorInit('prop_description');
		
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			unset($_POST["Submit"]);
			$_POST["post_date"]=date("Y-m-d H:i:s");
			$_POST["user_id"]=$_SESSION['memberid'];
			if(isset($_REQUEST['features']))
			{
				$_POST["features"] = implode(",",$_REQUEST['features']);
			}
			$req = $_POST;
			if($global["show_property"] == 1){ //realestate tube
				
				//Update
				if($_REQUEST['propid'] <> "")
				{
					$album->updateAlbum($req,$_REQUEST['propid'],$_SESSION['memberid']);
					setMessage("The Property <b>".$req["prop_title"]."</b> is updated. Now you can edit/upload video, Photos to your property here.",MSG_SUCCESS);
					redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=propdView&&propid={$_REQUEST['propid']}&view=edit"));
				}
				else //Insert
				{
					$propid = $album->createAlbum($req);
					setMessage("The Property <b>".$req["prop_title"]."</b> is created. Now you can upload video, Photos to your property here.",MSG_SUCCESS);
					redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=propdView&&propid=$propid&view=edit"));
				}//Ratheesh For create newalbum
			}else{
				
				if( $album->createAlbum($req)){
					if($global["mymedia_redirect"] == 1){
					redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=album_list"));
					}
					else
					redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=myalbum"));
				}
			}	
		}
		
		if($_REQUEST['propid'] <> "")
		{
			$rs		   = $album->getAlbumDetails($_REQUEST['propid']); 
			$_REQUEST  = $_REQUEST + $rs ;
			
			if(!empty($rs['features']))
			{
				$rsFeature = $rs['features'];
				$framework->tpl->assign("FE",$rsFeature);
			}
		}
		
		if($global["show_property"] == 1) //realestate tube
		{
		
			$framework->tpl->assign("LISTING_TYPE_PARENT",1);
			$framework->tpl->assign("PROPERTY_TYPE_PARENT",11);
			$framework->tpl->assign("SALE_TYPE_PARENT",21);
			$framework->tpl->assign("FEATURES_PARENT",39);
			$framework->tpl->assign("LISTING_TYPE",$objCategory->getCategoryTreeParentLevel(1));
			$framework->tpl->assign("PROPERTY_TYPE",$objCategory->getCategoryTreeParentLevel(11));
			$framework->tpl->assign("SALE_TYPE", $objCategory->getCategoryTreeParentLevel(21));
			$framework->tpl->assign("FEATURES",$objCategory->getCategoryTreeParentLevel(39));
			
		}
		if($global["show_property"] == 1 && $_REQUEST['act'] == "add"){//realestate tube.....
			$framework->tpl->assign("TOP_MENU_SUB",SITE_PATH."/modules/album/tpl/top_menu.tpl");
		}

		$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
		$framework->tpl->assign("SECTION_LIST", $album->menuSectionList());
		
		if($global['profile_inner']=='Y')
		{
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/profile_main.tpl");
			$framework->tpl->assign("profile_tpl",SITE_PATH."/modules/album/tpl/create_album.tpl");
		}
		else
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/album_new.tpl");
		
		break;
	case "myalbum":
	
		checkLogin();
		$alb_list=$album->getAlbumList($_SESSION["memberid"]);
		$framework->tpl->assign("ALB_LST",$alb_list);
	//	print_r($_REQUEST);exit;
	
		if($global["show_property"] == 1 && $_REQUEST['act'] == "myalbum"){//realestate tube.....
		$framework->tpl->assign("TOP_MENU_SUB",SITE_PATH."/modules/album/tpl/top_menu.tpl");
		}

		if (($_REQUEST["action1"]) || ($_REQUEST["action2"]))
		{	
			if (($_REQUEST["action1"]=="delete") || ($_REQUEST["action2"]=="delete"))
			{
				$files=$_REQUEST["chk"];
				$length=sizeof($files);
				for($i=0;$i<$length;$i++)
				{
					if($global["show_property"] == 1) //realestate=ube //25-09-2007 today
					{
						$album->propertyDelete($files[$i],"album");
					}
					else
					{
						$album->mediaDelete($files[$i], $_REQUEST['crt']);
					}
				}
			}
			elseif (($_REQUEST["action1"]=="move") || ($_REQUEST["action2"]=="move"))
			{
				$files=$_REQUEST["chk"];
				$length=sizeof($files);
				
				if($_REQUEST["album1"])
				{
					$alb=$_REQUEST["album1"];
				}
				else
				{
					$alb=$_REQUEST["album2"];
				}
				
				if($alb)
				{
					for($i=0;$i<$length;$i++)
					{
						$album->movetoAlbum($files[$i], $_REQUEST['crt'],$alb);
					}
				}	

			}
			elseif (($_REQUEST["action1"]=="share") || ($_REQUEST["action2"]=="share"))
			{
			//print_r($_REQUEST);exit;
				$files=$_REQUEST["chk"];
				$length=sizeof($files);
				$arr = array(privacy=>'public');
				//print_r($_REQUEST['crt']);exit;
					for($i=0;$i<$length;$i++)
					{
						$album->changesharemode($arr, $files[$i],$_REQUEST['crt']);
					}
				

			}
			elseif (($_REQUEST["action1"]=="unshare") || ($_REQUEST["action2"]=="unshare"))
			{
			//print_r($_REQUEST);exit;
				$files=$_REQUEST["chk"];
				$length=sizeof($files);
				$arr = array(privacy=>'private');
				
					for($i=0;$i<$length;$i++)
					{
						$album->changesharemode($arr, $files[$i],$_REQUEST['crt']);
					}
				
			}	
			redirect(makeLink(array("mod"=>"album", "pg"=>"album"), "act=myalbum&crt=".$_REQUEST['crt']));
		}	
		$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act'];
		if ($_REQUEST["crt"])
		{
			$par = $par."&crt=".$_REQUEST['crt'];
		}

		$framework->tpl->assign("ALB_NM","All Albums");
		if ($_REQUEST["alb_id"])
		{
			$par = $par."&alb_id=".$_REQUEST['alb_id'];
			$framework->tpl->assign("ALB_NM","Album '".$album->getAlbumName($_REQUEST['alb_id'])."'");
		}
		if ($_REQUEST["prf_target_id"])
		{
			$par = $par."&prf_target_id=".$_REQUEST['crt'];
		}

		$media="Music";
		$tbl = "album_music";
		$type="music";


		$alb=0;
		if($_POST["txtSearch"])
		{
			$stxt=$_POST["txtSearch"];
			$framework->tpl->assign("STXT",$stxt);

		}
		else
		{
			if(!$_REQUEST["stxt"])
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
		if($_REQUEST["alb_id"])
		{
			$alb = $_REQUEST["alb_id"];
			$framework->tpl->assign("ALB_ID",$alb);
		}

		if ($_REQUEST["crt"])
		{
			$crt = $_REQUEST["crt"];
			$framework->tpl->assign("CRT",$crt);

		}else{
			if($global["hide_video_list"]==1){
				$crt="M1";
			}
		}
		if ($crt=="M1")
		{
			if($global["change_movie_video"]==1){
				$media="Video";
			}else{
				$media="Video";
			}
			$type="video";
			$tbl="album_video";

			$mpath="video/thumb/";
			$framework->tpl->assign("MPATH",$mpath);

			$framework->tpl->assign("PGFILE","video");
			$framework->tpl->assign("PGACT","details_prf");
			$framework->tpl->assign("FILE_ID","video_id");
			$framework->tpl->assign("IMG_EXT","NO");
			//$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
			if($global["show_property"] == 1)
			{
			list($rs,$numpad) = $album->propertySearch($_REQUEST['pageNo'],10,$par,ARRAY_A,'id:DESC',"user_id",$_SESSION['memberid']);
			}
			else
			{
				if($_REQUEST['prf_target_id'])
					$memberid=$_REQUEST['prf_target_id'];
				else
					$memberid=$_SESSION["memberid"];
				
			list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title:ASC',$tbl,'video',$stxt,$alb,$memberid);
			}
			
		}
		elseif ($crt=="M2")
		{
			$media="Photo";
			$tbl="album_photos";
			$type="photo";

			$mpath="photos/thumb/";
			$framework->tpl->assign("MPATH",$mpath);

			$framework->tpl->assign("PGFILE","photo");
			$framework->tpl->assign("PGACT","details");
			$framework->tpl->assign("FILE_ID","photo_id");
			$framework->tpl->assign("IMG_EXT","YES");
			//$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
			
			if($_REQUEST['prf_target_id'])
				$memberid=$_REQUEST['prf_target_id'];
			else
				$memberid=$_SESSION["memberid"];
				
			list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title:ASC',$tbl,'photo',$stxt,$alb,$memberid);
		}elseif ($crt=="M5")
		{
			$media="Product";
			$tbl="album_products";
			$type="product";

			$mpath="products/thumb/";
			$framework->tpl->assign("chat_tpl",SITE_PATH."/templates/green/tpl/chat.tpl");// Code for including chat.......
			$framework->tpl->assign("MPATH",$mpath);
			//$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("rest_right"));// to select the right menu of this page from database.
			$framework->tpl->assign("PGFILE","product");
			$framework->tpl->assign("FILE_ID","photo_id");
			$framework->tpl->assign("IMG_EXT","YES");
			list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title:ASC',$tbl,'product',$stxt,$alb,$_SESSION["memberid"]);
		}
		else
		{
			$tbl="album_music";
			$type="music";

			$mpath="music/thumb/";
			$framework->tpl->assign("MPATH",$mpath);

			$framework->tpl->assign("PGFILE","music");
			$framework->tpl->assign("FILE_ID","music_id");
			$framework->tpl->assign("IMG_EXT","NO");
			list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title:ASC',$tbl,'music',$stxt,$alb,$_SESSION["memberid"]);
		}
		
		if ($framework->config["other_song_duration"])
		{
			for ($i=0;$i<sizeof($rs);$i++)
			{
				$rs[$i]->play_duration = $framework->config["other_song_duration"];
			}
		}
		
		
		$phCount=$album->getMediaCount($_SESSION["memberid"],'album_photos');
		$msCount=$album->getMediaCount($_SESSION["memberid"],'album_music');
		$mvCount=$album->getMediaCount($_SESSION["memberid"],'album_video');
		if($global["new_album_functions"]==1){
			$prCount=$album->getMediaCount($_SESSION["memberid"],'album_products');
		}
		
		$framework->tpl->assign("phCount",$phCount);
		$framework->tpl->assign("prCount",$prCount);
		$framework->tpl->assign("msCount",$msCount);
		$framework->tpl->assign("mvCount",$mvCount);

		$alb_arr=$album->getAlbums($_SESSION["memberid"],$type);
		$framework->tpl->assign("ALB_ARR",$alb_arr);
		$framework->tpl->assign("MEDIA",$media);
		
		//print_r($rs);
		
		$framework->tpl->assign("PHOTO_LIST", $rs);
		$framework->tpl->assign("PHOTO_NUMPAD", $numpad);
		
		
		$_SESSION["xml_arr"] = $rs;
		
		
		if ($crt=="M5")
		{
			 $framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/myproduct.tpl");
			 $framework->tpl->assign("left_tpl", SITE_PATH."/modules/album/tpl/myproduct_left.tpl");
		 }
		 elseif($global['profile_inner']=='Y')
		 {
		 	 $framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/profile_main.tpl");
			 $framework->tpl->assign("profile_tpl", SITE_PATH."/modules/album/tpl/myalbum.tpl");
		 }
		 else{
			 $framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/myalbum.tpl");
			 $framework->tpl->assign("left_tpl", SITE_PATH."/modules/album/tpl/myalbum_left.tpl");
		 }
		if($global["searchstyle"] == "2") {//for p1musicbox.
			$framework->tpl->display($global['curr_tpl']."/inner2.tpl");
			exit;
		}
		break;
	case "delalb":
		$album->albumDelete($_REQUEST["alb_id"]);
		redirect(makeLink(array("mod"=>"album", "pg"=>"album"), "act=myalbum&crt=".$_REQUEST['crt']));	
		break;
	case "favr":
		checkLogin();
		$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act'];
		if ($_REQUEST["crt"])
		{
			$par = $par."&crt=".$_REQUEST['crt'];
		}else{
			if($global["hide_video_list"]==1){
				$_REQUEST['crt']="M1";
			}
		}

		if($_POST["txtSearch"])
		{
			$stxt=$_POST["txtSearch"];
			$framework->tpl->assign("STXT",$stxt);

		}
		else
		{
			if(!$_REQUEST["stxt"])
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


		$media="Music";
		if($_REQUEST["crt"])
		{
			$crt=$_REQUEST["crt"];
			$framework->tpl->assign("CRT",$crt);
		}
		if($crt=="M1")
		{
			$media="Movie";
			$tbl="album_video";

			$mpath="video/thumb/";
			$framework->tpl->assign("MPATH",$mpath);

			$framework->tpl->assign("PGFILE","video");
			$framework->tpl->assign("FILE_ID","video_id");
			$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
			if($global["new_album_functions"]==1){
				list($rs, $numpad) = $album->FavList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title',$tbl,'video',$stxt,$_SESSION["memberid"]);
				if($global['show_private']=='Y'){
					for ($i=0;$i<sizeof($rs);$i++)
					{
						$medet=$objUser->getUserdetails($rs[$i]->user_id);
						$rs[$i]->screen_name=$medet["screen_name"];
						if($rs[$i]->privacy=='private'){
							if($rs[$i]->friends_can_see=='Y'){
								if($objUser->isFriends($rs[$i]->user_id,$_SESSION["memberid"])==true){
									$rs[$i]->show_media="Y";
								}else{
									$rs[$i]->show_media="N";
								}
							}else{
								$rs[$i]->show_media="N";
							}
							if($rs[$i]->user_id==$_SESSION["memberid"]){
								$rs[$i]->show_media="Y";
							}
						}else{
							$rs[$i]->show_media="Y";
						}
					}
				}
			}else{
				list($rs, $numpad) = $album->mediaFavList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title',$tbl,'video',$stxt,$_SESSION["memberid"]);
			}

		}
		elseif($crt=="M2")
		{
			$media="Photo";
			$tbl="album_photos";

			$mpath="photos/thumb/";
			$framework->tpl->assign("MPATH",$mpath);

			$framework->tpl->assign("PGFILE","photo");
			$framework->tpl->assign("FILE_ID","photo_id");
			$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
			if($global["new_album_functions"]==1){
				list($rs, $numpad) = $album->FavList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title',$tbl,'photo',$stxt,$_SESSION["memberid"]);
				if($global['show_private']=='Y'){
					for ($i=0;$i<sizeof($rs);$i++)
					{
						$medet=$objUser->getUserdetails($rs[$i]->user_id);
						$rs[$i]->screen_name=$medet["screen_name"];
						if($rs[$i]->privacy=='private'){
							if($rs[$i]->friends_can_see=='Y'){
								if($objUser->isFriends($rs[$i]->user_id,$_SESSION["memberid"])==true){
									$rs[$i]->show_media="Y";
								}else{
									$rs[$i]->show_media="N";
								}
							}else{
								$rs[$i]->show_media="N";
							}
							if($rs[$i]->user_id==$_SESSION["memberid"]){
								$rs[$i]->show_media="Y";
							}
						}else{
							$rs[$i]->show_media="Y";
						}
					}
				}
			}else{
				list($rs, $numpad) = $album->mediaFavList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title',$tbl,'photo',$stxt,$_SESSION["memberid"]);
			}
		}elseif($crt=="M5")
		{
			$media="Product";
			$tbl="album_products";

			$mpath="products/thumb/";
			$framework->tpl->assign("chat_tpl",SITE_PATH."/templates/green/tpl/chat.tpl");// Code for including chat.......
			$framework->tpl->assign("MPATH",$mpath);
			//$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("rest_right"));// to select the right menu of this page from database.
			$framework->tpl->assign("PGFILE","product");
			$framework->tpl->assign("FILE_ID","photo_id");
			

			if($global["new_album_functions"]==1){
				list($rs, $numpad) = $album->FavList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title',$tbl,'product',$stxt,$_SESSION["memberid"]);
			}else{
				list($rs, $numpad) = $album->mediaFavList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title',$tbl,'product',$stxt,$_SESSION["memberid"]);
			}
		}
		elseif($crt == "M3")
		{
			$tbl="album";
			if (($_REQUEST["action1"]) || ($_REQUEST["action2"]))
			{	
				if (($_REQUEST["action1"]=="delete") || ($_REQUEST["action2"]=="delete"))
				{
					$files=$_REQUEST["chk"];
					$length=sizeof($files);
					for($i=0;$i<$length;$i++)
					{
						$album->favoritePropDelete($files[$i], $_SESSION['memberid']);
					}
					setMessage("");
				}
			}
			list($rs, $numpad) = $album->propertySearch($_REQUEST['pageNo'], 10, $par, ARRAY_A, $orderBy,"user_id",$_SESSION["memberid"],'album',$criteria='=',$category='',$listAllSub='no',$group_id='','yes');
			
		}
		else
		{
			$tbl="album_music";

			$mpath="music/thumb/";
			$framework->tpl->assign("MPATH",$mpath);

			$framework->tpl->assign("PGFILE","music");
			$framework->tpl->assign("FILE_ID","music_id");

			if($global["new_album_functions"]==1){
				list($rs, $numpad) = $album->FavList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title',$tbl,'music',$stxt,$_SESSION["memberid"]);
			}else{
				list($rs, $numpad) = $album->mediaFavList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title',$tbl,'music',$stxt,$_SESSION["memberid"]);
			}
		}
		
		if($_REQUEST["msg"]){
			$framework->tpl->assign("MESSAGE",$_REQUEST["msg"]);
		}
		
		$framework->tpl->assign("TITLE_HEAD","My MeggaBox");
		if($global["new_album_functions"]==1){
			if($global['show_private']=='Y'){
				list($rsp, $numpadp) = $album->FavList($_REQUEST['pageNo'], 50000,$par, OBJECT, 'title','album_photos','photo','',$_SESSION["memberid"]);
				$phCount=0;
					for ($i=0;$i<sizeof($rsp);$i++)
					{
						
						if($rsp[$i]->privacy=='private'){
							if($rsp[$i]->friends_can_see=='Y'){
								if($objUser->isFriends($rsp[$i]->user_id,$_SESSION["memberid"])==true){
									$phCount++;
								}
							}
							if($rsp[$i]->user_id==$_SESSION["memberid"]){
								$phCount++;
							}
						}else{
							$phCount++;
						}
					}
			}else{
				$phCount=$album->getFavCount($_SESSION["memberid"],'photo','album_photos');
			}
			$prCount=$album->getFavCount($_SESSION["memberid"],'product','album_products');
			$msCount=$album->getFavCount($_SESSION["memberid"],'music','album_music');
			if($global['show_private']=='Y'){
				list($rsv, $numpadv) = $album->FavList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title','album_video','video','',$_SESSION["memberid"]);
				$mvCount=0;
					for ($i=0;$i<sizeof($rsv);$i++)
					{
						if($rsv[$i]->privacy=='private'){
							if($rsv[$i]->friends_can_see=='Y'){
								if($objUser->isFriends($rsv[$i]->user_id,$_SESSION["memberid"])==true){
									$mvCount++;
								}
							}
							if($rsv[$i]->user_id==$_SESSION["memberid"]){
								$mvCount++;
							}
						}else{
							$mvCount++;
						}
					}
			}else{
				$mvCount=$album->getFavCount($_SESSION["memberid"],'video','album_video');
			}
			
		}else{
			$phCount=$album->getFavMediaCount($_SESSION["memberid"],'photo');
			$prCount=$album->getFavMediaCount($_SESSION["memberid"],'product');
			$msCount=$album->getFavMediaCount($_SESSION["memberid"],'music');
			$mvCount=$album->getFavMediaCount($_SESSION["memberid"],'video');
		}
		$framework->tpl->assign("phCount",$phCount);
		$framework->tpl->assign("prCount",$prCount);
		$framework->tpl->assign("msCount",$msCount);
		$framework->tpl->assign("mvCount",$mvCount);

		for($i=0;$i<sizeof($rs);$i++){
			$usr=$objUser->getUserdetails($rs[$i]->user_id);
			$rs[$i]->username =$usr->username;
		}
		//
		//print_r($rs);exit;
		$framework->tpl->assign("PHOTO_LIST", $rs);
		$framework->tpl->assign("PHOTO_NUMPAD", $numpad);
		$framework->tpl->assign("MEDIA",$media);
		
		$_SESSION["xml_arr"] = $rs;
		
		if($global["show_property"] == 1) //realestate tube
		{
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/myalbum.tpl");
		}
		elseif ($crt=="M5")
		{
			 $framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/myproductfav.tpl");
		 }else
		{
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/myfavorites.tpl");
		 $framework->tpl->assign("left_tpl", SITE_PATH."/modules/album/tpl/myfavorites_left.tpl");
		}
		if($global["searchstyle"] == "2") {//for p1musicbox.
			$framework->tpl->display($global['curr_tpl']."/inner2.tpl");
			exit;
		}
		break;
	case "purchased":
		checkLogin();
		$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act'];
		if ($_REQUEST["crt"]) {
			$par = $par."&crt=".$_REQUEST['crt'];
		}
		if($_POST["txtSearch"]) {
			$stxt=$_POST["txtSearch"];
			$framework->tpl->assign("STXT",$stxt);
		} else {
			if(!$_REQUEST["stxt"]) {
				$stxt=0;
			} else {
				$stxt=$_REQUEST["stxt"];
				$framework->tpl->assign("STXT",$stxt);
			}
		}
		$par = $par."&stxt=".$stxt;

		$media="Music";
		if($_REQUEST["crt"]) {
			$crt=$_REQUEST["crt"];
			$framework->tpl->assign("CRT",$crt);
		}
		if($crt=="M1") {
			$media="Movie";
			$tbl="album_video";

			$mpath="video/thumb/";
			$framework->tpl->assign("MPATH",$mpath);

			$framework->tpl->assign("PGFILE","video");
			$framework->tpl->assign("FILE_ID","video_id");

			list($rs, $numpad) = $album->mediaPurchasedList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title',$tbl,'video',$stxt,$_SESSION["memberid"]);
		} else {
			$tbl="album_music";

			$mpath="music/thumb/";
			$framework->tpl->assign("MPATH",$mpath);

			$framework->tpl->assign("PGFILE","music");
			$framework->tpl->assign("FILE_ID","music_id");

			list($rs, $numpad) = $album->mediaPurchasedList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title',$tbl,'music',$stxt,$_SESSION["memberid"]);
		}
		
		$_SESSION["xml_arr"] = $rs;
		
		$msCount=$album->getPurchasedMediaCount($_SESSION["memberid"],'music');
		$mvCount=$album->getPurchasedMediaCount($_SESSION["memberid"],'video');

		$framework->tpl->assign("msCount",$msCount);
		$framework->tpl->assign("mvCount",$mvCount);
		$framework->tpl->assign("TITLE_HEAD","My Purchased Items");
		$framework->tpl->assign("PHOTO_LIST", $rs);
		$framework->tpl->assign("PHOTO_NUMPAD", $numpad);
		$framework->tpl->assign("MEDIA",$media);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/mypurchased.tpl");
		if($global["searchstyle"] == "2") {//for p1musicbox.
			$framework->tpl->display($global['curr_tpl']."/inner2.tpl");
			exit;
		}
		break;
	case "removeFavorite":
		if($global["new_album_functions"]==1){
				if($_REQUEST['crt']=="M1"){
					$tblname="album_video";
					$type="video";
				}elseif($_REQUEST['crt']=="M2"){
					$tblname="album_photos";
					$type="photo";
				}elseif($_REQUEST['crt']=="M3"){
					$tblname="album_music";
					$type="music";
				}elseif($_REQUEST['crt']=="M5"){
					$tblname="album_products";
					$type="product";
				}
				//print_r($tblname);exit;
				$album->RemoveFavorite($tblname,$_REQUEST['id'],$type,$_SESSION["memberid"]);
			}else{
				$album->mediaRemoveFavorite($_REQUEST['id'], $_REQUEST['crt']);
			}
		redirect(makeLink(array("mod"=>"album", "pg"=>"album"), "act=favr&crt=".$_REQUEST['crt']));
		break;
	case "delete":
		$album->mediaDelete($_REQUEST['id'], $_REQUEST['crt']);
		redirect(makeLink(array("mod"=>"album", "pg"=>"album"), "act=myalbum&crt=".$_REQUEST['crt']));
		break;
	case "deleteprop":
		if($global["show_property"] == 1) //realestate tube
		{
		$album->propertyDelete($_REQUEST['propid']);
		setMessage("Porperty has been deleted.",MSG_SUCCESS);
		redirect(makeLink(array("mod"=>"member", "pg"=>"home")));
		}
		break;
	case "media":
		$memId=$_REQUEST["user_id"];
		$userDet=$objUser->getUserdetails($memId);
		$framework->tpl->assign("no
		", $userDet);//getting details from member_master
		$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&user_id=$memId";
		if ($_REQUEST["crt"])
		{
			$par = $par."&crt=".$_REQUEST['crt'];
		}

		$framework->tpl->assign("ALB_NM","All Albums");
		if ($_REQUEST["alb_id"])
		{
			$par = $par."&alb_id=".$_REQUEST['alb_id'];
			$framework->tpl->assign("ALB_NM","Album '".$album->getAlbumName($_REQUEST['alb_id'])."'");
		}

		$media="Music";
		$tbl = "album_music";
		$alb=0;
		if($_POST["txtSearch"])
		{
			$stxt=$_POST["txtSearch"];
			$framework->tpl->assign("STXT",$stxt);

		}
		else
		{
			if(!$_REQUEST["stxt"])
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
		if($_REQUEST["alb_id"])
		{
			$alb = $_REQUEST["alb_id"];
			$framework->tpl->assign("ALB_ID",$alb);
		}

		if ($_REQUEST["crt"])
		{
			$crt = $_REQUEST["crt"];
			$framework->tpl->assign("CRT",$crt);

		}
		if ($crt=="M1")
		{
			if($global["change_movie_video"]==1){
				$media="Video";
			}else{
				$media="Video";
			}
			$tbl="album_video";

			$mpath="video/thumb/";
			$framework->tpl->assign("MPATH",$mpath);

			$framework->tpl->assign("PGFILE","video");
			$framework->tpl->assign("FILE_ID","video_id");
			if($global['show_private']=='Y'){
				list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title:ASC',$tbl,'video',$stxt,$alb,$memId);
				for ($i=0;$i<sizeof($rs);$i++)
				{
					if($rs[$i]->privacy=='private'){
						if($rs[$i]->friends_can_see=='Y'){
							if($objUser->isFriends($memId,$_SESSION["memberid"])==true){
								$rs[$i]->show_media="Y";
							}else{
								$rs[$i]->show_media="N";
							}
						}else{
							$rs[$i]->show_media="N";
						}
						if($memId==$_SESSION["memberid"]){
							$rs[$i]->show_media="Y";
						}
					}else{
						$rs[$i]->show_media="Y";
					}
				}
				//print_r($rs);exit;
			}else{
				list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title:ASC',$tbl,'video',$stxt,$alb,$memId,'public');
			}

		}
		elseif ($crt=="M2")
		{
			$media="Photo";
			$tbl="album_photos";

			$mpath="photos/thumb/";
			$framework->tpl->assign("MPATH",$mpath);

			$framework->tpl->assign("PGFILE","photo");
			$framework->tpl->assign("FILE_ID","photo_id");
			
			if($global['show_private']=='Y'){
				list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title:ASC',$tbl,'photo',$stxt,$alb,$memId);
				//print_r($numpad);exit;
				for ($i=0;$i<sizeof($rs);$i++)
				{
					if($rs[$i]->privacy=='private'){
						if($rs[$i]->friends_can_see=='Y'){
							if($objUser->isFriends($memId,$_SESSION["memberid"])==true){
								$rs[$i]->show_media="Y";
							}else{
								$rs[$i]->show_media="N";
							}
						}else{
							$rs[$i]->show_media="N";
						}
						if($memId==$_SESSION["memberid"]){
							$rs[$i]->show_media="Y";
						}
					}else{
						$rs[$i]->show_media="Y";
					}
				}
				}else{
					list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title:ASC',$tbl,'photo',$stxt,$alb,$memId,'public');
				}
		}
		else
		{
			$tbl="album_music";

			$mpath="music/thumb/";
			$framework->tpl->assign("MPATH",$mpath);

			$framework->tpl->assign("PGFILE","music");
			$framework->tpl->assign("FILE_ID","music_id");

			list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title:ASC',$tbl,'music',$stxt,$alb,$memId,'public');
		}
		if($global['show_private']=='Y'){
			list($rsp, $numpadp) = $album->mediaList(1,10000,$par, OBJECT, 'title:ASC','album_photos','photo','','',$memId);
			$cnp=0;
			for ($i=0;$i<sizeof($rsp);$i++)
				{
					if($rsp[$i]->privacy=='private'){
						if($rsp[$i]->friends_can_see=='Y'){
							if($objUser->isFriends($memId,$_SESSION["memberid"])==true){
								$cnp++;
							}
						}
						if($memId==$_SESSION["memberid"]){
							$cnp++;
						}
					}else{
						$cnp++;
					}
				}
				$phCount=$cnp;
			}else{
				$phCount=$album->getMediaCount($memId,'album_photos','public');
			}
		$msCount=$album->getMediaCount($memId,'album_music','public');
		if($global['show_private']=='Y'){
			list($rst, $numpadv) = $album->mediaList(1,10000,$par, OBJECT, 'title:ASC','album_video','video','','',$memId);
			$cnv=0;
			for ($i=0;$i<sizeof($rst);$i++)
				{
					if($rst[$i]->privacy=='private'){
						if($rst[$i]->friends_can_see=='Y'){
							if($objUser->isFriends($memId,$_SESSION["memberid"])==true){
								$cnv++;
							}
						}
						if($memId==$_SESSION["memberid"]){
							$cnv++;
						}
					}else{
						$cnv++;
					}
				}
				$mvCount=$cnv;
			}else{
				$mvCount=$album->getMediaCount($memId,'album_video','public');
			}
		
		if($memId==$_SESSION["memberid"]){
			$framework->tpl->assign("OWN","Y");
		}
		$framework->tpl->assign("phCount",$phCount);
		$framework->tpl->assign("msCount",$msCount);
		$framework->tpl->assign("mvCount",$mvCount);
		$framework->tpl->assign("MEDIA",$media);
		$framework->tpl->assign("PHOTO_LIST", $rs);
		//print_r($numpad);exit;
		$framework->tpl->assign("PHOTO_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/medias.tpl");
		$framework->tpl->assign("left_tpl", SITE_PATH."/modules/album/tpl/medias_left.tpl");
		break;
	case "download":
		checkLogin();
		if($_REQUEST['crt'] == "M1") {
			$type = "video";
			include_once(FRAMEWORK_PATH."/modules/album/lib/class.video.php");
			$obj = new Video();
		} else {
			$type = "music";
			include_once(FRAMEWORK_PATH."/modules/album/lib/class.music.php");
			$obj = new Music();
		}
		$obj->incrementDownload($_REQUEST['id']);
		$media = $album->mediaDetailsGet($_REQUEST['id'], $type);
		$path = SITE_PATH."/modules/album/$type/";
		$file = $_REQUEST['id'].".".$media['filetype'];
		//print_r($_REQUEST);
		//ob_start();
		
		//checkLogin();
		$path = SITE_URL."/modules/album/music/";
		$file = $_REQUEST["id"].".mp3";
		header('Content-Disposition: attachment; filename="'.$file.'"');
		readfile($path.$file);
		exit;
		
		//include(FRAMEWORK_PATH."/modules/album/lib/download.php?id=". $_REQUEST['id']."&f_type=".$media['filetype']."&type=".$type);
		//redirect(FRAMEWORK_URL."/modules/album/lib/download.php?id=". $_REQUEST['id']."&f_type=".$media['filetype']."&type=".$type);
		//ob_end_clean();
		exit;
		break;
	case "propdView":
		include_once(SITE_PATH."/includes/flashPlayer/include.php");
		
		if($_REQUEST['view'] == "edit"){
			checkLogin();
		}
					if($_REQUEST["fn"]!="share")
					{
						//$video->incrementView($_REQUEST["video_id"]);
					}
					else
					{
						checkLogin();
						$userinfo = $objUser->getUserdetails($_SESSION["memberid"]);
						$contact  = $objUser->listContacts($userinfo["username"]);
						$framework->tpl->assign("CONTACT",$contact);
						$framework->tpl->assign("USERDET",$userinfo);		
					}	
				
				if($_SERVER['REQUEST_METHOD']=="POST")
				{
						checkLogin();
						if ($_REQUEST["fn"]=="share")
							{
								$arr=explode(",",$_POST["friends"]);
								for($i=0;$i<sizeof($arr);$i++)
								{	if($arr[$i])
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
								}
								
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
												$phid    = $_REQUEST["propid"];
												$from     = $userinfo["username"];
												
												$comment = $_REQUEST["comments"]."<br><br><br><br>";
												$comment = $comment."Click on the link below to view Details<br>";
												$comment = $comment . "<a href=\"".SITE_URL."/".makeLink(array("mod"=>"album", "pg"=>"album"), "act=propdView&propid=$phid&view=video")."\" target='_blank'>View Details</a>";
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
												$touserid = $touser["id"];
												$propid    = $_REQUEST["propid"];
												$rs		   = $album->getAlbumDetails($propid); 
												$phid	   = $rs["default_vdo"];
												$from     = $userinfo["username"];
																								
												$message="<div style='padding-left: 25px; padding-right: 25px;'>";
												$message=$message."<h2>I want to share the following Movie with you</h2>";
												$message=$message."<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"album", "pg"=>"album"), "act=propdView&propid=$propid&view=video")."\"><img src='".SITE_URL ."/modules/album/video/thumb/$phid.jpg' border='0'></a>";
												$message=$message."<h4>Personal Message</h4>";
												$message=$message. "<p>". $_REQUEST["comments"] . "</p>";
												$message=$message."<p>Thanks,<br>";
												$message=$message. $userinfo["first_name"]. " " . $userinfo["last_name"] . "</p>";
												$message=$message."</div>";
			
												mimeMail($arrData["to"],$arrData["subject"],$message,'','',$framework->config["site_name"].'<'.$framework->config['admin_email'].'>');
												//sendMail($arrData["to"],$arrData["subject"],$message,'Industrypage.com<'.$framework->config['admin_email'].'>','HTML');
										}	
									}
			
									setMessage("The property has been sent successfully.",MSG_SUCCESS);
									redirect(makeLink(array("mod"=>"album", "pg"=>"album"), "act=propdView&propid=".$_REQUEST["propid"]."&view=overview"));
								}	
								else
								{
									setMessage($invalid,MSG_INFO);
									redirect(makeLink(array("mod"=>"album", "pg"=>"album"), "act=propdView&propid=".$_REQUEST["propid"]."&view=video&fn=share#sahre"));
								}
								if($invalid!='')
								{
									
								}
							}
							else
							{
								$_POST["type"]     = "album";
								$_POST["user_id"]  = $_SESSION["memberid"];
								$_POST["file_id"] = $_REQUEST["propid"];
								$_POST["postdate"] = date("Y-m-d G:i:s");
								unset($_POST["x"],$_POST["y"],$_POST['btncmt']);
								$album->setArrData($_POST);
								$album->postComment();
							}	
			  	}
			 		//share deatisl display

			/* Comment Listing */
			list($rs, $numpad) = $album->commentList1($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&propid=".$_REQUEST['propid']."&view=overview", OBJECT, $_REQUEST['orderBy'], $_REQUEST["propid"]);
			$framework->tpl->assign("COMMENT_LIST",$rs);
			$framework->tpl->assign("COMMENT_NUMPAD", $numpad);

			$framework->tpl->assign("FILTER",$_REQUEST["filter"]);
            $framework->tpl->assign("PH_HEADER", $pheader);
            list($rs, $numpad) = $objVideo->videoList($_REQUEST['pageNo'], 5, "mod={$mod}&pg={$pg}&act=list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}", OBJECT, $field,0,$_REQUEST['txtSearch']);
			
			/* new */ 
			if($rs[0]->cat_id > 0)
			list($rscat, $numpad) = $objVideo->videoList($_REQUEST['pageNo'], 5, "mod={$mod}&pg={$pg}&act=list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}", OBJECT, $field,$rs[0]->cat_id,$_REQUEST['txtSearch']);
			
			if($_REQUEST['video_id'] >0 )
			{
				$videodet=$objVideo->getVideoDetails($_REQUEST['video_id']);
			}
			else if($rs[0]->cat_id > 0)
			{
				$videodet=$objVideo->getVideoDetails($rs[0]->id);
			}
			if($global["show_property"] == 1 && $_REQUEST['view'] == "edit"){//realestate tube.....
			$framework->tpl->assign("TOP_MENU_SUB",SITE_PATH."/modules/album/tpl/top_menu.tpl");
			}
			//print_r($videodet);exit;
			$framework->tpl->assign("SING_VIDEO_DET",$videodet);
			$rsPropDeta = $album->getAlbumByFields('id',$_REQUEST["propid"]);
			$rs = $album->propertyList("album_photos","photo",0,$_REQUEST["propid"],$_SESSION["memberid"]);
			$framework->tpl->assign("PHOTO_LIST",$rs);
		
	if($global["show_property"] == 1) //realestate tube
	{
		if($_REQUEST['view'] == "photo" || $_REQUEST['view'] == "overview") //display photo
		{
			$rsPropDeta = $album->getAlbumByFields('id',$_REQUEST['propid']);
			$framework->tpl->assign("DEF_EXT",$objPhoto->imgExtension($rsPropDeta[0]["default_img"]));
		}
					
			list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 10,'', 'ARRAY_A','',"album_video","video",'',$_REQUEST['propid'],$_SESSION['memberid']);
			$framework->tpl->assign("VIDEO_DETAILS",$rs);
			
			// Video length cal
			if(count($rs) > 0)
			{
				foreach($rs as $row)
				{
				  $dur[] = implode(":",$album->secs2hms($row["length"]));// calculate duration video clips
				}
			}
			$framework->tpl->assign("DURATION",$dur);
			
				if($_REQUEST['vid'] > 0)
				{
				   $framework->tpl->assign("SING_V_DETAIL",$objVideo->getVideoDetails($_REQUEST['vid']));
				   $vid = $_REQUEST['vid'];
				}
				else
				{
				  if($rsPropDeta[0]["default_vdo"] > 0)
				  $framework->tpl->assign("SING_V_DETAIL",$objVideo->getVideoDetails($rsPropDeta[0]["default_vdo"]));
				  $vid = $rsPropDeta[0]["default_vdo"];
				}
				
				
				$phdet = $objVideo->getVideoDetails($vid);
				$height = $phdet['dimension_height']+20;
				$link=SITE_URL."/".makeLink(array("mod"=>"album", "pg"=>"album"), "act=embed&video_id={$phdet['id']}");
				$embed_url = "<iframe frameborder='0'  marginheight='0' marginwidth='0'  width='400' height='350' src='$link' scrolling='no'></iframe>";
				$framework->tpl->assign("EMBED_URL",$embed_url);
				$v_url =SITE_URL."/index.php?". $_SERVER['QUERY_STRING'];
				$framework->tpl->assign("VISIBLE_URL",$v_url);
	}
	
			/*
			property rating
			*/
			if($_REQUEST["rate"])
			{
				checkLogin();
				$array=array();
				$array["type"]    = "album";
				$array["file_id"] = $_REQUEST["propid"];
				$array["userid"]  = $_SESSION["memberid"];
				$array["mark"]    = $_REQUEST["rate"];
				
				$album->setArrData($array);
				if(!$album->rateAlbum())
				{
					$framework->tpl->assign("RATE_MESSAGE",$objVideo->getErr());
					setMessage($album->getErr());
				}
				else
				{
					setMessage("Thanks for rating !",MSG_SUCCESS);
					redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=propdView&propid={$_REQUEST['propid']}&view={$_REQUEST['view']}"));
					
				}
 			}
			//Add to favorite
			if($_REQUEST["fn"]=="add")
        	{
			 
				checkLogin();
				$array=array();
				$array["type"]    = "album";
				$array["file_id"] = $_REQUEST["propid"];
				$array["userid"]  = $_SESSION["memberid"];
				$objAlbum->setArrData($array);
				
				if(!$objAlbum->addFavorite())
				{
				
					setMessage($objAlbum->getErr(),MSG_INFO);
					//redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=propdView&propid={$_REQUEST['propid']}&view=video"));
				}
				else
				{
					setMessage("Property added to your favorite list",MSG_SUCCESS);
					redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=favr&crt=M3&tpm=5"));
				}
        	}
			
			if($_REQUEST['fn'] == "subscribe" && $_REQUEST['subid'])
			{
				checkLogin();
				if($_REQUEST['subid'])
				{
					$res=$album->Subscribe($memberID,$_REQUEST['subid'],"album");
					$rsmem = $objUser->getUserdetails($_REQUEST['subid']);
				}
				if($res!="true"){
				 setMessage("You are already subscribed to ".$rsmem["username"]);	
				 
				}
				else
				{
					setMessage("Your subscription to ".$rsmem["username"]." has been added.");
				}
				redirect(makeLink(array("mod"=>"album","pg"=>"album"),"act=subscription&type=album"));
			}
		if($_REQUEST["propid"])
		{
			$album->incrementProView($_REQUEST["propid"]);
			$framework->tpl->assign("PROP_DETAILS",$rsPropDeta);
			$framework->tpl->assign("CATEGORY",$album->propertyCategoryName($_REQUEST["propid"]));
			$framework->tpl->assign("FEATURES",$album->propertyCategoryName($_REQUEST["propid"],'features'));
			$framework->tpl->assign("COUNTRY",$album->propertyCountryName($_REQUEST["propid"]));
		}
		
		if($_REQUEST['view'] == "edit")
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/adView.tpl");
		else
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/searchView.tpl");
				
		break;
	case "Search":
		
			if($_REQUEST["city"] !=""){
				$arrField[] 	= "prop_city";
				$arrVal[]		= $_REQUEST["city"];
				$arrCriteria[]	= "like";
			}
			if($_REQUEST['state'] !=""){
				$arrField[] = "prop_region";
				$arrVal[]   = $_REQUEST["state"];
				$arrCriteria[]	= "like";
			}
			if($_REQUEST['zipcode'] !=""){
				$arrField[] 	= "prop_zip";
				$arrVal[]   	= $_REQUEST["zipcode"];
				$arrCriteria[]	= "like";
			}
			if($_REQUEST['minprice'] !="" && $_REQUEST['maximumprice'] !=""){
				$arrField[] = "price";
				$arrField[] = "price";
				
				$arrVal[]   = $_REQUEST["minprice"];
				$arrVal[]   = $_REQUEST["maximumprice"];
				$arrCriteria[]	= ">=";
				$arrCriteria[]	= "<=";
			}
			
			if($_REQUEST['searchtag'] !=""){
				$searchFields	= "search_tags";
				$searchVals   = $_REQUEST["searchtag"];
				$criteria	= "like";
			}
			elseif(count($arrField) && count($arrVal) && count($arrCriteria)){
				$searchFields	=	implode(",",$arrField);
				$searchVals		=	implode(",",$arrVal);
				$criteria		=	implode(",",$arrCriteria);
			}
		$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act'];
		$searchResutls = $album->propertySearch($_REQUEST['pageNo'],10,$par,ARRAY_A,'id',$searchFields,$searchVals,'',$criteria);
		$framework->tpl->assign("PROPERTY_VIEW",$searchResutls);
		//$framework->tpl->assign("search_tpl", SITE_PATH."/modules/album/tpl/property_search.tpl");
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/property_search.tpl");
		break;
	case "category" :
		$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act'];
		$framework->tpl->assign("CATEGORY",$objCategory->getChildCategories2("Property Listing Type"));
		list($rs,$numpad) = $album->propertySearch($_REQUEST['pageNo'],10, $par, ARRAY_A, "id:DESC",$search_fields='',$search_values='',$type='',$criteria='=',$_REQUEST['cat_id']);
		$framework->tpl->assign("CATEGORYSORT",$rs);
		$framework->tpl->assign("NUMPAD",$numpad);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/category_property_list.tpl");
		break;
	case "player":
		echo trim($album->generatePlayListXML($_REQUEST));
		exit;	
	break;	
	case "addgroup";
		checkLogin();
		
		if($_SERVER['REQUEST_METHOD']=="POST")
		{
		
			$file = $_REQUEST['chk'];
			
			for($i=0;$i<sizeof($file);$i++)
			{
			  $album->addTogroup($_REQUEST['group_id'],$file[$i]);
			}
			setMessage("Your property have been submitted to this group",MSG_SUCCESS);
			
		}
		list($rs,$numpad) = $album->propertySearch($_REQUEST['pageNo'],15,$par,ARRAY_A,'',"user_id",$_SESSION['memberid']);
		$grpDet = $objUser->getGroupDetails($_REQUEST["group_id"]);
		$framework->tpl->assign("PHOTO_NUMPAD",$numpad);
		$framework->tpl->assign("GRP_DET",$grpDet);
		$framework->tpl->assign("PHOTO_LIST", $rs);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/myalbum_addto_group.tpl");
	break;
  	case"article_upload";
  		 /**
		   * This function is used to upload the  article.
		   * Author   : Adarsh
		   * Created  : 14/Nov/2007
		   * Modified : 
   		*/		
		checkLogin();
		$framework->tpl->assign("REQ", 'true');
		
  		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			$req = &$_REQUEST;
			$fname	 =	$_FILES['paper_file']['name'];
			$ftype	 =	$_FILES['paper_file']['type'];
			$fsize	 =	$_FILES['paper_file']['size'];
			$ferror	 =	$_FILES['paper_file']['error'];
			$tmpname =	$_FILES['paper_file']['tmp_name'];
			$fileext    =  strtolower($album->file_extension($_FILES['paper_file']['name']));
			$fileChk='false';
			if($fname && in_array(strtolower($fileext), array('doc', 'pdf','txt','csv')))
			{
				$fileChk='true';
			}
			else
				$fileChk='false';
				
			if($_REQUEST['id'])
			{
				if($_REQUEST['url'] || $fname)
				{
					$fileChk='true';
				}
				else
				$fileChk='false';
				
			}
			if($album->chkAlbum($_POST['paper_title'],$_REQUEST['id']))
			{
				if($fileChk=='true' || $req['link_to_paper'] || $req['link_pointing_to_paper'])
				{
						/*------------------xml rewrting for article----------------------------------*/
						$xml.='<?xml version="1.0" encoding="UTF-8"?>';
						$xml.='<article>';
						$xml.='<main>';
							$xml.="\n";
							$xml.='<paper_title>'.$req['paper_title'].'</paper_title>';
							$xml.='<cat_id>'.$req['cat_id'].'</cat_id>';
							$xml.='<user_id>'.$memberID.'</user_id>';
							$xml.='<no_of_author>'.$req['no_of_author'].'</no_of_author>';
							$xml.='<author_email>'.$req['author_email'].'</author_email>';
							$xml.='<published>'.$req['published'].'</published>';
							if($_REQUEST['id'])
							{
								$xml.='<articleId>'.$_REQUEST['id'].'</articleId>';
							}
							$xml.="\n";
							if($_REQUEST['published']=='conference')
							{
								$xml.='<conference_name>'.$req['conference_name'].'</conference_name>';
								$xml.='<conference_exist>'.$req['conference_exist'].'</conference_exist>';
								$xml.='<conference_acronym>'.$req['conference_acronym'].'</conference_acronym>';
								$xml.='<conference_town>'.$req['conference_town'].'</conference_town>';
								$xml.='<conference_state>'.$req['conference_state'].'</conference_state>';
								$xml.='<conference_country>'.$req['conference_country'].'</conference_country>';
								$xml.='<conference_day>'.$req['conference_day'].'</conference_day>';
								$xml.='<conference_month>'.$req['conference_month'].'</conference_month>';
								$xml.='<conference_year>'.$req['conference_year'].'</conference_year>';
								$xml.="\n";
								$xml.='<conference_sponsors>'.$req['conference_sponsors'].'</conference_sponsors>';
								$xml.='<conference_publisher>'.$req['conference_publisher'].'</conference_publisher>';
								$xml.='<conference_url>'.$req['url_conference'].'</conference_url>';
								$xml.='<conference_isbn>'.$req['conference_isbn'].'</conference_isbn>';
								//$xml.='<conference_acceptance_rating>'.$req['conference_acceptance_rating'].'</conference_acceptance_rating>';
								//$xml.='<conference_quality_rating>'.$req['conference_quality_rating'].'</conference_quality_rating>';
								$xml.="\n";
							
							}
							if($_REQUEST['published']=='journal')
							{
								$xml.='<journal_name>'.$req['journal_name'].'</journal_name>';
								$xml.='<journal_acronym>'.$req['journal_acronym'].'</journal_acronym>';
								$xml.='<journal_month>'.$req['journal_month'].'</journal_month>';
								$xml.='<journal_year>'.$req['journal_year'].'</journal_year>';
								//$xml.='<journal_pages>'.$req['journal_pages'].'</journal_pages>';
								$xml.='<journal_volume>'.$req['journal_volume'].'</journal_volume>';
								$xml.='<journal_number>'.$req['journal_number'].'</journal_number>';
								$xml.="\n";
								$xml.='<journal_publisher>'.$req['journal_publisher'].'</journal_publisher>';
								$xml.='<journal_url>'.$req['journal'].'</journal_url>';
								$xml.='<journal_isbn>'.$req['journal_isbn'].'</journal_isbn>';
								//$xml.='<journal_quality_rating>'.$req['journal_quality_rating'].'</journal_quality_rating>';
								$xml.="\n";
							}
							if($_REQUEST['published']=='book')
							{
								$xml.='<book_title>'.$req['book_title'].'</book_title>';
								$xml.='<book_author>'.$req['book_author'].'</book_author	>';
								$xml.='<book_month>'.$req['book_month'].'</book_month>';
								$xml.='<book_year>'.$req['book_year'].'</book_year>';
								$xml.="\n";
								$xml.='<book_publisher>'.$req['book_publisher'].'</book_publisher>';
								$xml.='<book_isbn>'.$req['book_isbn'].'</book_isbn>';
								$xml.="\n";
							}
							if($_REQUEST['published']=='report')
							{
								$xml.='<report_name>'.$req['report_name'].'</report_name>';
								$xml.='<report_identifier>'.$req['report_identifier'].'</report_identifier>';
								$xml.='<report_month>'.$req['report_month'].'</report_month>';
								$xml.='<report_year>'.$req['report_year'].'</report_year>';
								$xml.="\n";
							}
						
							$xml.='<abstract>'.$req['abstract'].'</abstract>';
							
							if($_FILES['paper_file']['name'])
							{
								if($fname)
								{
										$dir2=SITE_PATH."/modules/album/temp/";
										$tempdir=$dir2.date("Ymd:His").".".$fileext;
										copy($tmpname,$tempdir);
										$tmpname=$tempdir;
										
								}
							$xml.='<paper_file>'.$tmpname.'</paper_file>';
							//$xml.='<file_type>'.$fileext.'</file_type>';
							}	
							
								$xml.='<link_to_paper>'.$req['link_to_paper'].'</link_to_paper>';
								$xml.='<link_pointing_to_paper>'.$req['link_pointing_to_paper'].'</link_pointing_to_paper>';
								$xml.="\n";
								$xml.='<isbn>'.$req['isbn'].'</isbn>';
								$xml.='<doi>'.$req['doi'].'</doi>';
						$xml.='</main>';
						for($i=0; $i < $req['no_of_author'];$i++)
						{
							$xml.="\n";
							$xml .='<authors>';
							$xml .='<author>'.$req["author"][$i].'</author>';
							$xml .='<author_name_exist>'.$req["author_name_exist"][$i].'</author_name_exist>';
							$xml .='<affiliation>'.$req['affiliation'][$i].'</affiliation>';
							$xml .='<department>'.$req['department'][$i].'</department>';
							$xml.="\n";
							
							$xml .='<institution>'.$req['institution'][$i].'</institution>';
							$xml .='<city>'.$req['city'][$i].'</city>';
							$xml .='<state>'.$req['state'][$i].'</state>';
							$xml.="\n";
							$xml .='<country>'.$req['country'][$i].'</country>';
							$xml .='<email>'.$req['email'][$i].'</email>';
							$xml .='<author_type>'.$req['author_type'][$i].'</author_type>';
							$xml .='</authors>';
						}
						
						$xml.='</article>';		
						
						$dir=SITE_PATH."/modules/album/xml/";
						$newfilename="article.xml";
						
						if(file_exists($dir.$newfilename))
						{
							unlink($dir.$newfilename);
						}
						$fp = @fopen($dir.$newfilename,'w');
						if(!$fp)
						{
							die('Error cannot create XML file');
						}
						fwrite($fp,$xml);
						/*------------------xml rewrting for article ends----------------------------------*/
						
						/*------------------xml reading for article ----------------------------------*/
						$dir=SITE_PATH."/modules/album/xml/";
						$newfilename="article.xml";
						$p =& new xmlParser();
						$p->parse($dir.$newfilename);
						
						 $article=$p->output;
						 /*------------------xml reading for article ends-------------------------------*/

						 $array=$album->getArticleArray($article);
						 $id=$album->insetArticle($array,$objCategory);
						 if($_REQUEST['id'])
						 {
							redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=myarticle"));
						 }
						 else
						 {
							redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=article_success&id=$id"));
						 }
				}
				else{
					setMessage("You have to specify any one. Upload paper,Link to PDF of paper,Link to URL pointing to paper entry in the publishers' database.");
				}
			}
			else
			{
				setMessage("Article alreday exit.");
			}	
		}
		if($_REQUEST['id'])
		{
			$editflg="true";
			$aid=$_REQUEST['id'];
			$framework->tpl->assign("EDITFLG", $editflg);
			$framework->tpl->assign("ID",$_REQUEST['id']);	
			$article=$album->getAlbumDetails($_REQUEST['id']);
			if(count($article))
			{
					if($article['conference_id']){
						$rs2=$album->getConferenceDetails($article['conference_id'],false);
						$article['conference_id']=$rs2;
					}
					else if($article['journal_id'])
					{
						$rs2=$album->getJournalDetails1($article['journal_id'],false);
						$article['journal_id']=$rs2;
					}
					else if($article['book_id'])
					{
						$rs2=$album->getBookDetails($article['book_id'],false);
						$article['book_id']=$rs2;
					}
					else if($article['report_id'])
					{
						$rs2=$album->getInstitutionDetails($article['report_id'],false);
						$article['report_id']=$rs2;
					}
					if($article	['author_id'])
					{
						$authorId=explode(',',$article['author_id']);
						for($i=0;$i< count($authorId);$i++)
						{
							$rs3=$album->getAuthorDetails($authorId[$i],false);
							$author[$i]=$rs3;
						}
					}
			}	
			$framework->tpl->assign("ARTICLE",$article);
			$framework->tpl->assign("CAT_NAME",$objCategory->CategoryGetName($article['cat_id']));
			$framework->tpl->assign("AUTHER_NO",count($authorId));
			$framework->tpl->assign("AUTHOR",$author);
			$path = SITE_PATH."/modules/album/article/";
			$filename=$path.$aid.".".$article['file_type'];
			
			if(file_exists($filename))
			{
			$url="modules/album/article/$aid.".$article['file_type'];
			$framework->tpl->assign("URL",$url);
			}
		}
		
		$year=date("Y");
		for($i=1900;$i<=$year;$i++){
			$yearlist[]=$i;
		}
	   $framework->tpl->assign("NOW", $year);
	   $framework->tpl->assign("YEAR_LIST", $yearlist);
	   $framework->tpl->assign("CAT_LIST", $objUser->getCategories());
	   $framework->tpl->assign("ERROR_MSG", $message);	
 	   $framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
  	   $framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/article_upload.tpl");
  	break;

	//----------To Add Email Alerts, done by Jeffy, on 26th sep 2007------------
	case "email_alert";
		checkLogin();
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			unset($_POST["Submit"]);
			$_POST["post_date"]=date("Y-m-d H:i:s");
			$_POST["user_id"]=$_SESSION['memberid'];
			if(isset($_REQUEST['features']))
			{
				$_POST["features"] = implode(",",$_REQUEST['features']);
			}
			$req = $_POST;
			if($global["show_property"] == 1){
				
				if($_REQUEST['propid'] <> "")
				{
					//Update
					$album->updateAlbum($req,$_REQUEST['propid']);
					setMessage("Your Email alert is Updated Successfully.",MSG_SUCCESS);
					redirect(makeLink(array("mod"=>"member", "pg"=>"home")));
				}
				else //Insert
				{
					$propid = $album->createEmailAlert($req);
					setMessage("Your Email alert is submitted Successfully. The Profiles which match your requirements will be sent to your Email id.",MSG_SUCCESS);
					redirect(makeLink(array("mod"=>"member", "pg"=>"home")));
				}
			}else{
				if( $album->createAlbum($req)){
					redirect(makeLink(array("mod"=>"member", "pg"=>"home")));
				}
			}	
		}
		
		if($global["show_property"] == 1) //realestate tube
		{
		
			$framework->tpl->assign("LISTING_TYPE_PARENT",1);
			$framework->tpl->assign("PROPERTY_TYPE_PARENT",11);
			$framework->tpl->assign("SALE_TYPE_PARENT",21);
			$framework->tpl->assign("FEATURES_PARENT",39);
			$framework->tpl->assign("LISTING_TYPE",$objCategory->getCategoryTreeParentLevel(1));
			$framework->tpl->assign("PROPERTY_TYPE",$objCategory->getCategoryTreeParentLevel(11));
			$framework->tpl->assign("SALE_TYPE", $objCategory->getCategoryTreeParentLevel(21));
			$framework->tpl->assign("FEATURES",$objCategory->getCategoryTreeParentLevel(39));
			
		}
		
		$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
		$framework->tpl->assign("SECTION_LIST", $album->menuSectionList());
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/email_alert.tpl");
	break;
	//----------Ends------------
	case "adminPropList";
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/album/tpl/property_list.tpl");
	break;
	default:
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/main.tpl");
		break;
	case "article_success";
		checkLogin();
		$aid=$_REQUEST['id'];
		$article=$album->getAlbumDetails($aid);
		$path = SITE_PATH."/modules/album/article/";
		$filename=$path.$aid.".".$article['file_type'];
	
		if(file_exists($filename))
			$url=$filename;
		else if($article['link_to_paper'])
			$url=$article['link_to_paper'];
		else if($article['link_pointing_to_paper'])
			$url=$article['link_pointing_to_paper'];
		$framework->tpl->assign("URL",$url);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/article_success.tpl");
	break;
	case "channels";
		
	break;
	case "article_list":
		 /**
		   * This function is used to list all the articles.
		   * Author   : Adarsh
		   * Created  : 14/Nov/2007
		   * Modified : 
   		  */		
		$par = "mod=".$_REQUEST['mod']."&pg=".$_REQUEST['pg']."&act=".$_REQUEST['act'];
		if ($_REQUEST["cat_id"])
		{
				$par = $par."&cat_id=".$_REQUEST['cat_id'];
		}
		if ($_REQUEST["cat_id"])
		{	
			$framework->tpl->assign("CRT",$_REQUEST["cat_id"]);
			$catname=$objUser->getCatName($_REQUEST["cat_id"]);
			$framework->tpl->assign("GRP_HEADER", $catname["category_name"]);
			list($articles, $numpad) = $album->getAlbumAll($_REQUEST['pageNo'],10, $par,'ARRAY_A', $_REQUEST['orderBy'],'cat_id',$_REQUEST["cat_id"],0,0,0,$stxt);				
		}	
		else
			list($articles,$numpad) = $album->getAlbumAll($_REQUEST['pageNo'],10,$par,'ARRAY_A',$_REQUEST['orderBy'],'','0','','');
		$framework->tpl->assign("ARTICLE_LIST",$articles);
		$framework->tpl->assign("NUMPAD",$numpad);
		$framework->tpl->assign("CAT_ARR", $objUser->listCategories());
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/article_list1.tpl");
		break;
	case "article_details":
		 /**
		   * This function is used to view the details of a particular articles.
		   * Author   : Adarsh
		   * Created  : 18/Nov/2007
		   * Modified : 
   		  */		
		$par = "mod=".$_REQUEST['mod']."&pg=".$_REQUEST['pg']."&act=".$_REQUEST['act'];
		
		if($_REQUEST['id'])
		{
			$par.="&id=".$_REQUEST['id'];
		}
		
		if($_SERVER['REQUEST_METHOD'])
		{
			if($_REQUEST["rate"])
			{
				
				checkLogin();
				$id=$_REQUEST["id"];
				$array=array();
				$array["type"]    = "article";
				$array["file_id"] = $_REQUEST["id"];
				$array["userid"]  = $_SESSION["memberid"];
				$array["mark"]    = $_REQUEST["rate"];
				$album->setArrData($array);
				/*if(!$album->rateArticle())
				{
					$framework->tpl->assign("MESSAGE",$album->getErr());
				}
				else
				{
					redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=article_details&id=$id&tpm=5"));
				}*/
			}
			else
				if($_SERVER['REQUEST_METHOD']=='POST')
				{
				
					$post_det = array();
					$post_det["comment"]  = $_POST["comment"];
					$post_det["type"]     = "article";
					$post_det["user_id"]  = $_SESSION["memberid"];
					$post_det["file_id"]  = $_REQUEST["id"];
					$post_det["postdate"] = date("Y-m-d G:i:s");
					$album->setArrData($post_det);
					$album->postComment();
				}	
		}	
		$aid=$_REQUEST['id'];
		$article=$album->getArticleDetails($_REQUEST['id']);
		$path = SITE_PATH."/modules/album/article/";
		$filename=$path.$aid.".".$article['file_type'];
	
		if(file_exists($filename))
		{
			$url="modules/album/article/$aid.".$article['file_type']."";
			$framework->tpl->assign("URL",$url);
		}
		//$framework->tpl->assign("FORUM_TOPIC",$album->getArticleByFields('aid',$_REQUEST['id']));
		list($rs, $numpad) = $album->commentList2($_REQUEST['pageNo'], 10, $par, OBJECT, $_REQUEST['orderBy'], $_REQUEST["id"]);
		$framework->tpl->assign("COMMENT_LIST",$rs);
		$framework->tpl->assign("COMMENT_NUMPAD", $numpad);
		$framework->tpl->assign("ID",$_REQUEST['id']);
		$framework->tpl->assign("ARTICLE",$article);
    	$framework->tpl->assign("REVIEW",$album->getMemberReview($_REQUEST['id'],$_SESSION['memberid']));
		$framework->tpl->assign("ARTICLE_REVIEW",$album->getArticleReview($_REQUEST['id']));
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/article_details.tpl");
	break;
	case "myarticle":
		/**
		   * Used to list all the articles of a user .
		   * Author   : Adarsh
		   * Created  : 14/Nov/2007
		   * Modified : 
   		  */		
		$par = "mod=".$_REQUEST['mod']."&pg=".$_REQUEST['pg']."&act=".$_REQUEST['act'];
		list($articles,$numpad) = $album->getAlbumByUsers($_REQUEST['pageNo'],10,$par,'ARRAY_A','',$_SESSION['memberid'],'');
		$framework->tpl->assign("ARTICLE_LIST",$articles);
		$framework->tpl->assign("NUMPAD",$numpad);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/myalbum.tpl");
		break;
	case "article_delete":
		 /**
		   * This function is used to delete  the articles.
		   * Author   : Adarsh
		   * Created  : 28/Des/2007
		   * Modified : 
   		  */		
		if($_REQUEST['id']);
		{
				$req=$_REQUEST['id'];
				$article=$album->getArticleDetails($req);
				
				$path = SITE_PATH."/modules/album/article/";
				$filename=$path.$req.".".$article['file_type'];
				if(file_exists($filename) && $filename)
				{
					unlink($filename);
				}
			if($_REQUEST['flag'])
			{	
				redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=article_upload&id=".$req));
			}
			else{
				$album->albumDelete($_REQUEST['id']);	
				redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=myarticle"));
			}
		}
		break;
	case "article_search_list":
		 /**
		   * This function is list the article using several search criteria.
		   * Author   : Adarsh
		   * Created  : 15/Jan/2008
		   * Modified : 
   		  */		
		$req = $_REQUEST;
		if($req['search_category'] && $req['category_id'] )
		{
			$search_fields="published";
			$search_values=$req['search_category'];
			$search_fields.=','."cat_id";
			$search_values.=','.$req['category_id'];
		}
		else if($req['search_category'])
		{
			$search_fields="published";
			$search_values=$req['search_category'];
		}
		else if($req['category_id'])
		{
			$search_fields.="cat_id";
			$search_values.=$req['category_id'];
		}
		if($req['title_key'])
		{
			$stxt=$req['title_key'];
		}
		if($req['any_keyword'])
		{
			 $stxt2=$album->removeConjunction($req['any_keyword']);
			//$stxt2=$req['any_keyword'];
		}
		if($req['published'])
		{
			$stxt=$req['title_key'];
		}
		$par = "mod=".$_REQUEST['mod']."&pg=".$_REQUEST['pg']."&act=".$_REQUEST['act']."&search_category=".$req['search_category']."&authors=".$req['authors']."&keyword=".$req[title_key];
		
		if($req['authors'])
		{
			$par=$par."&authors=".$req['authors'];
			$stxt1=$req['authors'];
		}
		if($req['any_keyword'])
		{
			$par=$par."&any_keyword=".$req['any_keyword'];
			
		}
		list($ser_res, $numpad) = $album->articleSearchList($_REQUEST['pageNo'],'20',$par,'ARRAY_A', $_REQUEST['orderBy'],'album','article',$stxt,$stxt1,$stxt2,'','','',$search_fields,$search_values);
		$framework->tpl->assign("ARTICLE_LIST",$ser_res);
		$framework->tpl->assign("NUMPAD",$numpad);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/article_search_list.tpl");
	break;
	case "article_review";
		/**
		   * Used to review an article.
		   * Author   : Adarsh
		   * Created  : 15/Jan/2008
		   * Modified : 
   		  */		
		checkLogin();
	
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
			    $req = &$_POST;
				$arr = array();
				$arr["article_id"]  =   $req["id"];
				$arr["user_id"] 	=   $_SESSION["memberid"];
				$arr["postdate"]    =   date("Y-m-d G:i:s");
				$album->setArrData($arr);
				if($_REQUEST['status']=='edit')
				$album->updateMemberReview($req["questid"][$i],$_SESSION["memberid"],$req['id']);
				else
				$album->insertMemberReview($req);
				setMessage("Article reviewed successfully.", MSG_SUCCESS);
				
			redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=article_details&id=".$req['id'].""));
		}
		if($_REQUEST['status']=='edit')
		{
			$editflg="true";
			$framework->tpl->assign("EDITFLG", $editflg);
			$framework->tpl->assign("USR_REVIEW",$album->getMemberReview($_REQUEST['id'],$_SESSION['memberid']));
		}
		$topic=$album->getReviewTopics();
		for($i=0;$i< count($topic);$i++)
		{
			$array[$i]['name'] = $topic[$i]->topic_name;
			$questions=$album->getReiviewQuest($topic[$i]->id);
			
			for($j=0;$j< count($questions);$j++)
			{
				$array[$i]['questions'][$j]['question']=$questions[$j]['question'];
				$array[$i]['questions'][$j]['qid']=$questions[$j]['id'];
				$qids[]= $questions[$j]['id'];
				$option=$album->getOptions($questions[$j]['id']);
				for($k=0;$k< count($option);$k++)
				{
					$array[$i]['questions'][$j]['option'][$k]=$option[$k]['option'];
					$array[$i]['questions'][$j]['optid'][$k]=$option[$k]['id'];
				}
				
			}
			
		}
		//print("<pre>");
		//print_r($array);
		$framework->tpl->assign("QIDS",implode(",",$qids));
		$framework->tpl->assign("ID",$_REQUEST['id']);
		$framework->tpl->assign("ARRAY_QN",$array);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/article_review.tpl");
	break;
	case "site_search":
		/**
		   * Used to search for bith article and dicussion topics with one criteria .
		   * Author   : Adarsh
		   * Created  : 15/Jan/2008
		   * Modified : 
   		  */		
		$req = $_REQUEST;
		$limit=20;
		$page=  $_REQUEST['page'];
		if(!isset($_REQUEST['page'])){ 
        $page = 1; 
        } 
		$limitvalue = (($page * $limit) - $limit); 
		
		$end      = $limitvalue+20;
		$rs=$album->get_site_searchlist($req,$limitvalue,$end);
		$totalrows      = $rs[0]; 
		 
		 if ($page !=1)
			{
				$pageprev = $page-1;
				$framework->tpl->assign("PAGEPREV", $pageprev); 	
			}
		if($totalrows - ($limit * $page) > 0){
			$pagenext = $page+1;
		  $framework->tpl->assign("PAGENEXT", $pagenext); 
   		 }
		$framework->tpl->assign("RS",$rs[1]);
		$framework->tpl->assign("VAL",$req['search_value']);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/site_search.tpl");
	break;
	/**
		   
		   * Author   : vinoy
		   * Created  : 03/April/2008
		   * Modified : vipin on 05/May/2008
   		  */		
	case"booking_list":
	  checkLogin();
		$memberID = $_SESSION['memberid'];
		 if($_REQUEST['transaction']=="success")
		{
    	//$msg=$framework->MOD_VARIABLES[MOD_MSG][MSG_BOOK_SUCCESS];
     	setMessage('Your Booking placed successfully',MSG_SUCCESS);
		}
		$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "0";
		$orderby		=	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"]: "t2.id:DESC";
		//$_REQUEST["orderBy"]
		list($rs, $numpad)=$album->myBookingProperties($pageNo ,10,'',ARRAY_A,$orderby,$memberID);
		
		$framework->tpl->assign("BOOKING_DETAILS",$rs);
		$framework->tpl->assign("FLYER_NUMPAD",$numpad);
		$framework->tpl->assign("PAGE_NO",$pageNo);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/flyer/tpl/proprerty_booklinglist.tpl");
	break;
	case"property_buyers":
	  checkLogin();
	  $pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "0";
		$memberID = $_SESSION['memberid'];
		list($rs, $numpad) = $album->getBookingUserDetails( $pageNo,10,'',ARRAY_A,$_REQUEST["orderBy"],$memberID);
		//print_r($rs);
		$framework->tpl->assign("BOOKING_DETAILS",$rs);
		$framework->tpl->assign("FLYER_NUMPAD",$numpad);
		$framework->tpl->assign("PAGE_NO",$pageNo);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/flyer/tpl/proprerty_buyerlist.tpl");
	break;
	case "multiple_upload":
		checkLogin();
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/upload_multiple.tpl");
		break;
	case "upload_conf": // This case generates Configuration XML for the Flash Uploader
		$sess_id = session_id();
		$redirect_url = makeLink(array("mod"=>"album", "pg"=>"album", "url_encode"=>"1"),"act=after_upload&sess_id={$sess_id}");
		$upd_url = SITE_URL."/".makeLink(array("mod"=>"album", "pg"=>"album", "url_encode"=>"1"),"act=do_multiple&mem_id={$_SESSION['memberid']}&sess_id={$sess_id}");
		echo $album->getUploadConfXML($upd_url,$redirect_url);
		exit;
		break;	
	case "do_multiple": // This case is called internally by the Flash Uploader on Upload
		$fileName=$_REQUEST['fileName'];
		
		if ($_FILES['Filedata']['name']) 
		{
			$uploadDir = SITE_PATH."/modules/album/tempUpload/";
			$thumb_dir = $uploadDir."thumb/";
			$resize_dir = $uploadDir."resized/";
			$file_ext = strstr($fileName,".");
			$file_ext =strtolower( substr($file_ext,1,strlen($file_ext)));
			$sess_id  = $_REQUEST['sess_id'];
			$arr = array();
			if (pictureFormat("image/".$file_ext))
			{
				$arr['sess_id'] = $sess_id;
				$arr['user_id'] = $_REQUEST['mem_id'];
				$arr['type']    = "IMAGE";
				$arr['extn'] = ".".$file_ext;
				$album->setArrData($arr);
				$up_id = $album->insertTempUpload();
				
				uploadFile($_FILES['Filedata'],$uploadDir, $up_id.".".$file_ext);
				chmod($uploadDir."$up_id".".".$file_ext,0777);
				
				if ($framework->config["photo_thumb1"])
				{
					$thumb_size  = explode(",",$framework->config["photo_thumb1"]);
					$thumb_width = $thumb_size[0];
					$thumb_height = $thumb_size[1];
				}
				else 
				{
					$thumb_width = 100;
					$thumb_height = 100;
				}
				thumbnail($uploadDir,$thumb_dir,$up_id.".".$file_ext,$thumb_width,$thumb_height,"",$up_id.".".$file_ext);
				chmod($thumb_dir."$up_id".".".$file_ext,0777);
				
				if ($framework->config["photo_resize"])
				{
				
					$thumb_size  = explode(",",$framework->config["photo_resize"]);
					$thumb_width = $thumb_size[0];
					$thumb_height = $thumb_size[1];
				}
				else 
				{

					$thumb_width = 500;
					$thumb_height = 500;
				}
				thumbnail($uploadDir,$resize_dir,$up_id.".".$file_ext,$thumb_width,$thumb_height,"",$up_id.".".$file_ext);
				chmod($resize_dir."$up_id".".".$file_ext,0777);
			}
			elseif(in_array(strtolower($file_ext), array('mov', 'wmv', 'mpg','mpeg', 'avi', '3gp', 'dat', 'asx')))
            {
            	$tmpname = $_FILES['Filedata']['tmp_name'];
            	$size    = $_FILES['Filedata']['size'];
               	$arr['sess_id'] = $sess_id;
				$arr['user_id'] = $_REQUEST['mem_id'];
				$arr['type']    = "VIDEO";
				$arr['extn'] = ".flv";
				$arr['size'] = $size;
				$album->setArrData($arr);
				$up_id = $album->insertTempUpload();
				shell_exec("ffmpeg -i {$tmpname} -ab 56 -ar 22050 -f flv{$_3gp_fix} {$uploadDir}{$up_id}.flv");
                chmod("${uploadDir}{$up_id}.flv",0777);
                $mov = new ffmpeg_movie("${uploadDir}{$up_id}.flv");
                $frame = $mov->getFrame(10);
                if ($frame)
                {
                	if ($framework->config["video_thumb"])
                	{
            			$thumb_size  = explode(",",$framework->config["video_thumb"]);
						$thumb_width = $thumb_size[0];
						$thumb_height = $thumb_size[1];	
                    }
                    else
                    {		
                    	$thumb_width = 110;
                    	$thumb_height = 80;
                    }
                    	
                    $frame->resize($thumb_width, $thumb_height);
                    $image = $frame->toGDImage();
                    imagejpeg($image, "${uploadDir}thumb/{$up_id}.jpg", 100);
                }
            }					
		}
		break;	
	case "after_upload"	:
		checkLogin();
		$sess_id = $_REQUEST['sess_id'];
		$user_id = $_SESSION['memberid'];
		
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			$srcImageDir = SITE_PATH."/modules/album/tempUpload/";
			$srcThumbDir = $srcImageDir."thumb/";
			$srcResizeDir = $srcImageDir."resized/";
			
			$dstImageDir = SITE_PATH."/modules/album/photos/";
			$dstThumbDir = $dstImageDir."thumb/";
			$dstResizeDir = $dstImageDir."resized/";
			
			$dstVideoDir = SITE_PATH."/modules/album/video/";
			$dstThumbVideo = $dstVideoDir."thumb/";
			
			$title = $_POST['title'];
			$descr = $_POST['description'];
			$extn  = $_POST['extn'];
			$id	   = $_POST['id'];
			$type  = $_POST['type'];
			$f_size  = $_POST['size'];
			$len   = sizeof($id);
			for ($i=0;$i<$len;$i++)
			{
				$arr = array();
				$arr['title'] = $title[$i];
				$arr['description'] = $descr[$i];
				$arr['user_id'] = $user_id;
				$arr['postdate'] = date("Y-m-d H:i:s");
				$arr['privacy']  = 'public';
				if ($type[$i]=="IMAGE")
				{
					$arr['img_extension'] = $extn[$i];
					$album->setArrData($arr);
					$ph_id = $album->insertPhotoDetails();
					
					copy($srcImageDir.$id[$i].$extn[$i],$dstImageDir.$ph_id.$extn[$i]);
					copy($srcThumbDir.$id[$i].$extn[$i],$dstThumbDir.$ph_id.$extn[$i]);
					copy($srcResizeDir.$id[$i].$extn[$i],$dstResizeDir.$ph_id.$extn[$i]);
				}
				elseif ($type[$i]=="VIDEO")
				{
					$mov = new ffmpeg_movie("${srcImageDir}{$id[$i]}.flv");
					$arr['dimension_width']  =  $mov->getFrameWidth();
                    $arr['dimension_width']  =  $arr['dimension_width'] ? $arr['dimension_width'] : 320;

                    $arr['dimension_height'] =  $mov->getFrameHeight();
                    $arr['dimension_height'] =  $arr['dimension_height'] ? $arr['dimension_height'] : 240;

                    $arr['filesize']         =  $f_size[$i];
                    $arr['length']			 =  $mov->getDuration();
					$album->setArrData($arr);                    
					$v_id = $album->insertVideo();
					
                    copy($srcImageDir.$id[$i].".flv",$dstVideoDir.$v_id.".flv");
					copy($srcThumbDir.$id[$i].".jpg",$dstThumbVideo.$v_id.".jpg");
				}
				
				@unlink($srcImageDir.$id[$i].$extn[$i]);
				@unlink($srcThumbDir.$id[$i].$extn[$i]);
				@unlink($srcResizeDir.$id[$i].$extn[$i]);
				
				$album->removeTemp($id[$i]);
			}
		}

		$rs = $album->getAfterUploadList($user_id,$sess_id,"IMAGE");
		$framework->tpl->assign("PHOTO_LIST",$rs);
		
		$rs = $album->getAfterUploadList($user_id,$sess_id,"VIDEO");
		$framework->tpl->assign("VIDEO_LIST",$rs);
		
		if (count($rs)==0)
		{
			//redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=myalbum"));
		}

		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/after_upload.tpl");
		break;

		case "album_list":
				
					
			if($global["inner_change_reg"]=="yes")
			{
	            checkLogin();
		    }
			
			if($_REQUEST['uid'])
			{
				$usr=$objUser->getUserdetails($_REQUEST['uid']);
				$framework->tpl->assign("PROFILENAME", $usr['screen_name']);
			}
			$framework->tpl->assign("TITLE_HEAD", "Photo List");
			
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
		
		if($_REQUEST['pid']!='')
		{
		$par = $par."&stxt=".$stxt."&pid=".$_REQUEST['pid'];
		}
		if ($_REQUEST["cat_id"])
		{
			$catname=$objUser->getCatName($_REQUEST["cat_id"]);
			$framework->tpl->assign("CRT",$_REQUEST["cat_id"]);
			$framework->tpl->assign("PH_HEADER", $catname["cat_name"]);
			list($rs, $numpad,$count) = $objPhoto->photoList($_REQUEST['pageNo'], 5, $par, OBJECT, "id desc",$_REQUEST["cat_id"],$stxt);
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
			list($rs, $numpad) = $objPhoto->photoList($_REQUEST['pageNo'], 5,$par, OBJECT, $field,0,$stxt);
		}
		else
		{	//print_r($par);exit;
		
			$framework->tpl->assign("PH_HEADER", "All Photos");
			if($_REQUEST["user_id"]){
				//checkLogin();
				
				list($rs, $numpad) = $album->getPhotoAlbums($_SESSION["memberid"],$_REQUEST['pageNo'],12,$par, OBJECT, 'id desc',0,$stxt,'photo');
			}else{
			
			   if($_REQUEST['uid']) 
			   {
				list($rs, $numpad) = $album->getPhotoAlbums($_REQUEST['uid'],$_REQUEST['pageNo'],12,$par, OBJECT, 'id desc',0,$stxt,'photo');
				}else
				{
				list($rs, $numpad) = $album->getPhotoAlbums($_SESSION["memberid"],$_REQUEST['pageNo'],12,$par, OBJECT, 'id desc',0,$stxt,'photo');
				}
				
			}
		}
		//$pht=0;
		
		for ($i=0;$i<sizeof($rs);$i++)
		{
		    if($_REQUEST['uid'])
			
			{
			  if($rs[$i]->is_locked==0)
			  {
			  $rs[$i]->locked="N";
			  }
			  else
			  {
				if($objUser->isFriends($_REQUEST['uid'],$_SESSION["memberid"])==false){
								$rs[$i]->locked="Y";
				}  else
				{
				$rs[$i]->locked="N";
				}
			}
			}
			else
			{
			$rs[$i]->locked="N";
			}
			
		    $medet1=$album->getAlbumCount($rs[$i]->id);
		    $rs[$i]->cnt1=$medet1;  
			$medet=$objUser->getUsernameDetails($rs[$i]->username);
			if($medet["user_id"]==$_SESSION["memberid"]){
				$rs[$i]->owner="Y";
			//	$pht++;
			}
			$rs[$i]->nick_name=$medet["nick_name"];
			$rs[$i]->mem_type=$medet["mem_type"];
			if($global['show_private']=='Y'){
				if($rs[$i]->privacy=='private'){
					if($rs[$i]->friends_can_see=='Y'){
						if($objUser->isFriends($medet["user_id"],$_SESSION["memberid"])==true){
							$rs[$i]->show_photo="Y";
							//$pht++;
						}else{
							$rs[$i]->show_photo="N";
							//$count--;
						}
					}else{
						$rs[$i]->show_photo="N";
						//$count--;
					}
					if($medet["user_id"]==$_SESSION["memberid"]){
						$rs[$i]->show_photo="Y";
						//$pht++;
					}
				}else{
					$rs[$i]->show_photo="Y";
					//$pht++;
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
		}
		### code using to show the pagination .....
		$framework->tpl->assign("PHOTO_LIST", $rs);
		
		$framework->tpl->assign("PHOTOCOUNT", sizeof($rs));
		$framework->tpl->assign("PHOTO_NUMPAD", $numpad);
		
		if($global['profile_inner']=='Y')
		 {
		 	 $framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/profile_main.tpl");
			 $framework->tpl->assign("profile_tpl", SITE_PATH."/modules/album/tpl/my_photo_album_list.tpl");
		 }	
		 else
		 $framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/album_list.tpl");
				 
		break;
		
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>