<?php
$memberID = $_SESSION['memberid'];
include_once(FRAMEWORK_PATH."/modules/album/lib/class.video.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.photo.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");

$album       = new Album();
$objPhoto    = new Photo();
$objUser     = new User();
$objCategory = new Category();
$objVideo	 = new Video();

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
					$param="mod={$mod}&pg={$pg}&act=subscription&membid={$memid}&type=video";
					list($res,$numpad)=$objVideo->getSubscriptionDetails($_REQUEST['pageNo'],2,$param,$memid,"album_video");
					$framework->tpl->assign("SUBSCRIBER_DET",$mem);
					$framework->tpl->assign("SUBSCRIPTION_DET",$res);
					$framework->tpl->assign("SUBSCRIPTION_NUMPAD",$numpad);
				}
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/video_subs_list.tpl");
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
					//print_r($res);exit;
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
		include_once(SITE_PATH."/includes/areaedit/include.php");
		editorInit('prop_description');
		
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
					$album->updateAlbum($req,$_REQUEST['propid']);
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
				
				
					for($i=0;$i<$length;$i++)
					{
						$album->changesharemode($files[$i], $_REQUEST['crt']);
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

		}
		if ($crt=="M1")
		{
			$media="Video";
			$type="video";
			$tbl="album_video";

			$mpath="video/thumb/";
			$framework->tpl->assign("MPATH",$mpath);

			$framework->tpl->assign("PGFILE","video");
			$framework->tpl->assign("FILE_ID","video_id");
			
			if($global["show_property"] == 1)
			{
			list($rs,$numpad) = $album->propertySearch($_REQUEST['pageNo'],10,$par,ARRAY_A,'id:DESC',"user_id",$_SESSION['memberid']);
			}
			else
			{
			list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title:ASC',$tbl,'video',$stxt,$alb,$_SESSION["memberid"]);
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
			$framework->tpl->assign("FILE_ID","photo_id");

			list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title:ASC',$tbl,'photo',$stxt,$alb,$_SESSION["memberid"]);
		}
		else
		{
			$tbl="album_music";
			$type="music";

			$mpath="music/thumb/";
			$framework->tpl->assign("MPATH",$mpath);

			$framework->tpl->assign("PGFILE","music");
			$framework->tpl->assign("FILE_ID","music_id");

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

		$framework->tpl->assign("phCount",$phCount);
		$framework->tpl->assign("msCount",$msCount);
		$framework->tpl->assign("mvCount",$mvCount);

		$alb_arr=$album->getAlbums($_SESSION["memberid"],$type);
		$framework->tpl->assign("ALB_ARR",$alb_arr);
		$framework->tpl->assign("MEDIA",$media);
		//print_r($rs);
		$framework->tpl->assign("PHOTO_LIST", $rs);
		$framework->tpl->assign("PHOTO_NUMPAD", $numpad);
		
		
		$_SESSION["xml_arr"] = $rs;
		
		
	
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/myalbum.tpl");
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


			list($rs, $numpad) = $album->mediaFavList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title',$tbl,'video',$stxt,$_SESSION["memberid"]);

		}
		elseif($crt=="M2")
		{
			$media="Photo";
			$tbl="album_photos";

			$mpath="photos/thumb/";
			$framework->tpl->assign("MPATH",$mpath);

			$framework->tpl->assign("PGFILE","photo");
			$framework->tpl->assign("FILE_ID","photo_id");


			list($rs, $numpad) = $album->mediaFavList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title',$tbl,'photo',$stxt,$_SESSION["memberid"]);
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


			list($rs, $numpad) = $album->mediaFavList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title',$tbl,'music',$stxt,$_SESSION["memberid"]);
		}
		if($_REQUEST["msg"]){
			$framework->tpl->assign("MESSAGE",$_REQUEST["msg"]);
		}
		$framework->tpl->assign("TITLE_HEAD","My MeggaBox");
		$phCount=$album->getFavMediaCount($_SESSION["memberid"],'photo');
		$msCount=$album->getFavMediaCount($_SESSION["memberid"],'music');
		$mvCount=$album->getFavMediaCount($_SESSION["memberid"],'video');

		$framework->tpl->assign("phCount",$phCount);
		$framework->tpl->assign("msCount",$msCount);
		$framework->tpl->assign("mvCount",$mvCount);

		for($i=0;$i<sizeof($rs);$i++){
			$usr=$objUser->getUserdetails($rs[$i]->user_id);
			$rs[$i]->username =$usr->username;
		}
		//
		$framework->tpl->assign("PHOTO_LIST", $rs);
		$framework->tpl->assign("PHOTO_NUMPAD", $numpad);
		$framework->tpl->assign("MEDIA",$media);
		
		$_SESSION["xml_arr"] = $rs;
		
		if($global["show_property"] == 1) //realestate tube
		{
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/myalbum.tpl");
		}
		else
		{
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/myfavorites.tpl");
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
		$album->mediaRemoveFavorite($_REQUEST['id'], $_REQUEST['crt']);
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
		$framework->tpl->assign("USERINFO", $userDet);//getting details from member_master
		
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
			$media="Movie";
			$tbl="album_video";

			$mpath="video/thumb/";
			$framework->tpl->assign("MPATH",$mpath);

			$framework->tpl->assign("PGFILE","video");
			$framework->tpl->assign("FILE_ID","video_id");

			list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title:ASC',$tbl,'video',$stxt,$alb,$memId,'public');

		}
		elseif ($crt=="M2")
		{
			$media="Photo";
			$tbl="album_photos";

			$mpath="photos/thumb/";
			$framework->tpl->assign("MPATH",$mpath);

			$framework->tpl->assign("PGFILE","photo");
			$framework->tpl->assign("FILE_ID","photo_id");

			list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title:ASC',$tbl,'photo',$stxt,$alb,$memId,'public');
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

		$phCount=$album->getMediaCount($memId,'album_photos','public');
		$msCount=$album->getMediaCount($memId,'album_music','public');
		$mvCount=$album->getMediaCount($memId,'album_video','public');

		$framework->tpl->assign("phCount",$phCount);
		$framework->tpl->assign("msCount",$msCount);
		$framework->tpl->assign("mvCount",$mvCount);

		$framework->tpl->assign("MEDIA",$media);

		$framework->tpl->assign("PHOTO_LIST", $rs);
		$framework->tpl->assign("PHOTO_NUMPAD", $numpad);

		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/medias.tpl");
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
	
			$framework->tpl->assign("DEF_EXT",$objPhoto->imgExtension($rs[0]["id"]));
					
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
		print_r($_REQUEST);exit;
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
  		checkLogin();
  		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			
			$req = &$_REQUEST;
			$framework->tpl->assign("REQ", 'true');
			
			$fname	 =	$_FILES['paperFile']['name'];
			$ftype	 =	$_FILES['paperFile']['type'];
			$fsize	 =	$_FILES['paperFile']['size'];
			$ferror	 =	$_FILES['paperFile']['error'];
			$tmpname =	$_FILES['paperFile']['tmp_name'];
			$fileext=$album->file_extension($fname);
			
			$_POST['album_name']	=	$_POST['paper_title'];
			$_POST['user_id']		=	$memberID;
			$_POST['post_date']		=	date("Y-m-d G:i:s");;
			$_POST['active']		=	'y';
			$_POST['file_type'] 	=	$fileext;
			
			if($album->chkAlbum($_POST['paper_title'],$_REQUEST['id']))
			{
				if($fname || $req['link_to_paper'] || $req['link_pointing_to_paper'])
				{
					unset($_POST['author']);
					unset($_POST['affiliation']);
					unset($_POST['department']);
					unset($_POST['institution']);
					unset($_POST['city']);
					unset($_POST['state']);
					unset($_POST['country']);
					unset($_POST['email']);
					unset($_POST['author_type']);
					unset($_POST['authorid']);
					if(!$_REQUEST['id'])
					$id=$album->createAlbum($_POST);
					else{
					$id=$_REQUEST['id'];
					$album->updateAlbum($_POST,$_REQUEST['id']);
					}
					if($fname)
					{
						
						$dir=SITE_PATH."/modules/album/music/";
						$tempdir=$dir.date("Ymd:His").".mp3";
						copy($tmpname,$tempdir);
						$tmpname=$tempdir;
						move_uploaded_file($_FILES['paperFile']['tmp_name'], $dir.$id.".".strtolower($album->file_extension($_FILES['paperFile']['name'])));
						chmod($dir.$id.".".strtolower($album->file_extension($_FILES['paperFile']['name'])), 0777);
						unlink($tempdir);
						
					}
					
					for($i=0;$i<$req['no_of_author'];$i++)
					{
						$array=array();
						if(!$_REQUEST['id'])
						$array["article_id"]    = $id;
						
						$array["author"]        = $req["author"][$i];
						$array["affiliation"]   = $req["affiliation"][$i];
						$array["department"]    = $req["department"][$i];
						$array["institution"]   = $req["institution"][$i];
						$array["city"]          = $req["city"][$i];
						$array["state"]  		= $req["state"][$i];
						$array["country"]  		= $req["country"][$i];
						$array["email"]  		= $req["email"][$i];
						$array["author_type"]  	= $req["author_type"][$i];
					
						$album->setArrData($array);
						if(!$_REQUEST['id'])
						$album->insertAuthorDetails();
						else{
						$album->updateAuthorDetails($req["authorid"][$i]);
						}
						
					}
					
					redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=article_success&id=$id"));
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
			$framework->tpl->assign("EDITFLG", $editflg);
			$framework->tpl->assign("ID",$_REQUEST['id']);	
			$framework->tpl->assign("ARTICLE",$album->getAlbumDetails($_REQUEST['id']));
			$framework->tpl->assign("AUTHOR",$album->getAuthors($_REQUEST['id']));
		}
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
		$path = SITE_PATH."/modules/album/music/";
		$filename=$path.$aid.".".$article['file_type'];
	
		if(file_exists($filename))
			$url="modules/album/music/$aid.".$article['file_type']."";
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
		$par = "mod=".$_REQUEST['mod']."&pg=".$_REQUEST['pg']."&act=".$_REQUEST['act'];
		
		list($articles,$numpad) = $album->getAlbumAll($_REQUEST['pageNo'],10,$par,'ARRAY_A','','','0','','');
		
		for($i=0;$i<count($articles);$i++)
		{
			$authors = $album->getAuthors($articles[$i]['id']);
			for($j=0;$j<count($authors);$j++)
			{
				 if($j != count($authors)-1)
				 	$author=$authors[$j]->author."<br> ";
				 else
					 $author=$authors[$j]->author;
				 
				 $articles[$i]['author'] .= $author;
			}
		}
		$framework->tpl->assign("ARTICLE_LIST",$articles);
		$framework->tpl->assign("NUMPAD",$numpad);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/article_list.tpl");
		break;
	case "article_details":
	
		$par = "mod=".$_REQUEST['mod']."&pg=".$_REQUEST['pg']."&act=".$_REQUEST['act']."&id=".$_REQUEST['id'];
		
		list($articles,$numpad) = $album->getAlbumAll($_REQUEST['pageNo'],5,$par,'ARRAY_A','','','0','','');
		
		for($i=0;$i<count($articles);$i++)
		{
			$authors = $album->getAuthors($articles[$i]['id']);
			for($j=0;$j<count($authors);$j++)
			{
				 if($j != count($authors)-1)
				 	$author=$authors[$j]->author.", ";
				 else
					 $author=$authors[$j]->author;
				 
				 $articles[$i]['author'] .= $author;
			}
		}
		$framework->tpl->assign("ARTICLE_LIST",$articles);
		$framework->tpl->assign("NUMPAD",$numpad);
		$aid=$_REQUEST['id'];
		$article=$album->getAlbumDetails($_REQUEST['id']);
		$path = SITE_PATH."/modules/album/music/";
		$filename=$path.$aid.".".$article['file_type'];
	
		if(file_exists($filename))
		{
			$url="modules/album/music/$aid.".$article['file_type']."";
			$framework->tpl->assign("URL",$url);
		}
		$framework->tpl->assign("ARTICLE",$article);
		$framework->tpl->assign("AUTHORS",$album->getAuthors($_REQUEST['id']));
		
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/article_details.tpl");
	break;
	case "myarticle":
		$par = "mod=".$_REQUEST['mod']."&pg=".$_REQUEST['pg']."&act=".$_REQUEST['act'];
		
		list($articles,$numpad) = $album->getAlbumByUsers($_REQUEST['pageNo'],10,$par,'ARRAY_A','',$memberID,'');
		
		for($i=0;$i<count($articles);$i++)
		{
			$authors = $album->getAuthors($articles[$i]['id']);
			for($j=0;$j<count($authors);$j++)
			{
				 if($j != count($authors)-1)
				 	$author=$authors[$j]->author.", ";
				 else
					 $author=$authors[$j]->author;
				 
				 $articles[$i]['author'] .= $author;
			}
		}
		$framework->tpl->assign("ARTICLE_LIST",$articles);
		$framework->tpl->assign("NUMPAD",$numpad);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/myalbum.tpl");
		break;
	case "article_delete":
		if($_REQUEST['id']);
		{
		////test
		$album->articleDelete($_REQUEST['id']);
		$article=$album->getAlbumDetails($_REQUEST['id']);
		$path = SITE_PATH."/modules/album/music/";
		echo $filename=$path.$aid.".".$article['file_type'];
		 
		if(file_exists($filename))
		{
			echo "test";
			$url="modules/album/music/$aid.".$article['file_type']."";
			unlink($url);
		}
		//redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=myarticle"));
		}
		break;
	case "article_search_list":
 	if($_SERVER['REQUEST_METHOD']=='POST'){
	$req = $_POST;
	$ser_res = $album->articleSearch($req[search_catagory],$req[title_key],'','');
	#print_r($ser_res);
	$framework->tpl->assign("SEARCH_LIST",$ser_res);
	$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/search_article_list.tpl");
	}
 		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/search_article_list.tpl");
		break;

	
	
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>