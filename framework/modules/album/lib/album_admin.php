<?php
//session_start();
/*checkLogin();
$memberID = $_SESSION['memberid'];*/
include_once(FRAMEWORK_PATH."/modules/album/lib/class.video.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.photo.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(SITE_PATH."/includes/flashPlayer/include.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.xmlParser.php");
include_once(FRAMEWORK_PATH."/modules/extras/lib/class.states.php");

$album= new Album();
$objPhoto=new Photo();
$objUser=new User();
$objVideo	 = new Video();
$objCategory = new Category();
$email= new Email();
$states 	= 	new States();

switch($_REQUEST['act'])
{
	case "add":
		checkLogin();
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			$req = &$_REQUEST;
			if( ($message = $album->createAlbum($req)) === true )
			{
				if ($_REQUEST["urlalb"])
				{
					redirect("index.php?".$_REQUEST["urlalb"]);
				}
			}
		}
		$framework->tpl->assign("SECTION_LIST", $album->menuSectionList());
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/album_new.tpl");
		break;
	case "myalbum":
		//checkLogin();
		//$alb_list=$album->getAlbumList($_SESSION["memberid"]);
		$framework->tpl->assign("ALB_LST",$alb_list);
		if (($_REQUEST["action1"]) || ($_REQUEST["action2"]))
		{	
			if (($_REQUEST["action1"]=="delete") || ($_REQUEST["action2"]=="delete"))
			{
				$files=$_REQUEST["chk"];
				$length=sizeof($files);
				for($i=0;$i<$length;$i++)
				{
					$album->mediaDelete($files[$i], $_REQUEST['crt']);
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
			redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=myalbum&crt=".$_REQUEST['crt']));
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
			$media="Movie";
			$type="video";
			$tbl="album_video";

			$mpath="video/thumb/";
			$framework->tpl->assign("MPATH",$mpath);

			$framework->tpl->assign("PGFILE","video");
			$framework->tpl->assign("FILE_ID","video_id");

			list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title:ASC',$tbl,'video',$stxt,$alb);

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

			list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title:ASC',$tbl,'photo',$stxt,$alb);
		}
		else
		{
			$tbl="album_music";
			$type="music";

			$mpath="music/thumb/";
			$framework->tpl->assign("MPATH",$mpath);

			$framework->tpl->assign("PGFILE","music");
			$framework->tpl->assign("FILE_ID","music_id");

			list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title:ASC',$tbl,'music',$stxt,$alb);
		}

		$phCount=$album->getMediaCount(0,'album_photos');
		$msCount=$album->getMediaCount(0,'album_music');
		$mvCount=$album->getMediaCount(0,'album_video');

		$framework->tpl->assign("phCount",$phCount);
		$framework->tpl->assign("msCount",$msCount);
		$framework->tpl->assign("mvCount",$mvCount);

		//$alb_arr=$album->getAlbums($_SESSION["memberid"],$type);
		$framework->tpl->assign("ALB_ARR",$alb_arr);
		$framework->tpl->assign("MEDIA",$media);

		$framework->tpl->assign("PHOTO_LIST", $rs);
		$framework->tpl->assign("PHOTO_NUMPAD", $numpad);


		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/album/tpl/myalbum_admin.tpl");
		break;
		case "myvideo":
		///////////////////////////////
		
		if ($_REQUEST["crt"])
		{
			$crt = $_REQUEST["crt"];
			$framework->tpl->assign("CRT",$crt);

		}
		
		
		
		
		////////////////////////////////


		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/album/tpl/myvideo_admin.tpl");
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
		else
		{
			$tbl="album_music";

			$mpath="music/thumb/";
			$framework->tpl->assign("MPATH",$mpath);

			$framework->tpl->assign("PGFILE","music");
			$framework->tpl->assign("FILE_ID","music_id");


			list($rs, $numpad) = $album->mediaFavList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title',$tbl,'music',$stxt,$_SESSION["memberid"]);
		}

		$phCount=$album->getFavMediaCount($_SESSION["memberid"],'photo');
		$msCount=$album->getFavMediaCount($_SESSION["memberid"],'music');
		$mvCount=$album->getFavMediaCount($_SESSION["memberid"],'video');

		$framework->tpl->assign("phCount",$phCount);
		$framework->tpl->assign("msCount",$msCount);
		$framework->tpl->assign("mvCount",$mvCount);

		$framework->tpl->assign("PHOTO_LIST", $rs);
		$framework->tpl->assign("PHOTO_NUMPAD", $numpad);
		$framework->tpl->assign("MEDIA",$media);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/myfavorites.tpl");
		break;
		
		case "myarticle":
		$framework->tpl->assign("CAT_ARR", $objUser->getCategoryArr(""));
		if (($_REQUEST["action1"]) || ($_REQUEST["action2"]))
		{	
			if (($_REQUEST["action1"]=="delete") || ($_REQUEST["action2"]=="delete"))
			{
				$files=$_REQUEST["chk"];
				$length=sizeof($files);
				for($i=0;$i<$length;$i++)
				{
					$album->mediaDelete($files[$i], $_REQUEST['crt']);
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
			redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=myarticle&crt=".$_REQUEST['crt']."&cat_id=".$_REQUEST["cat_id"]));
		}	
		if ($_REQUEST["cat_id"])
		{
			$catname=$objUser->getCatName($_REQUEST["cat_id"]);
			$framework->tpl->assign("FILTER",$_REQUEST["cat_id"]);
			$framework->tpl->assign("PH_HEADER", $catname["category_name"]);
			list($rs, $numpad) = $album->musicList($_REQUEST['pageNo'], 5, "mod={$mod}&pg={$pg}&act=list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}&cat_id={$_REQUEST["cat_id"]}", OBJECT, "id desc", $_REQUEST["cat_id"], $_REQUEST['txtSearch'],$_REQUEST['type']);
		}
		else
		{
			$framework->tpl->assign("PH_HEADER", "Article");
			list($rs, $numpad) = $album->musicList($_REQUEST['pageNo'], 5, "mod={$mod}&pg={$pg}&act=list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}", OBJECT, 'id desc',0,$_REQUEST['txtSearch'],$_REQUEST['type']);
		}
		$framework->tpl->assign("MUSIC_LIST", $rs);
		$framework->tpl->assign("MUSIC_NUMPAD", $numpad);

		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/album/tpl/myarticle_admin.tpl");
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

		$msCount=$album->getPurchasedMediaCount($_SESSION["memberid"],'music');
		$mvCount=$album->getPurchasedMediaCount($_SESSION["memberid"],'video');

		$framework->tpl->assign("msCount",$msCount);
		$framework->tpl->assign("mvCount",$mvCount);

		$framework->tpl->assign("PHOTO_LIST", $rs);
		$framework->tpl->assign("PHOTO_NUMPAD", $numpad);
		$framework->tpl->assign("MEDIA",$media);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/mypurchased.tpl");
		break;
	case "removeFavorite":
		$album->mediaRemoveFavorite($_REQUEST['id'], $_REQUEST['crt']);
		redirect(makeLink(array("mod"=>"album", "pg"=>"album"), "act=favr&crt=".$_REQUEST['crt']));
		break;
	case "delete":
	
		if(isset($_REQUEST['photoid']))//afsal
		$_REQUEST['id'] = $_REQUEST['photoid'];
		
		$album->mediaDelete($_REQUEST['id'], $_REQUEST['crt']);
		redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=upload_photo&crt=".$_REQUEST['crt'])."&user_id=".$_REQUEST['user_id']."&propid=".$_REQUEST['propid']);
		break;
	case "albdelete":
		$album->propertyDelete($_REQUEST['id'],"album");
		redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=propdView"));
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
			include_once(SITE_PATH."/modules/album/lib/class.video.php");
			$obj = new Video();
		} else {
			$type = "music";
			include_once(SITE_PATH."/modules/album/lib/class.music.php");
			$obj = new Music();
		}
		$obj->incrementDownload($_REQUEST['id']);
		$media = $album->mediaDetailsGet($_REQUEST['id'], $type);
		$path = SITE_PATH."/modules/album/$type/";
		$file = $_REQUEST['id'].".".$media['filetype'];
		header('Content-Disposition: attachment; filename="'.$file.'"');
		readfile($path.$file);
		exit;
		break;
		
	case "propdView":		
		include_once(SITE_PATH."/includes/flashPlayer/include.php");
		$framework->tpl->assign("SUB_NUMPAD",$numpad);
		$framework->tpl->assign("LIMIT_LIST",$limitList);
		if($global["show_property"] == 1)
		{
			$framework->tpl->assign("DEF_EXT",$objPhoto->imgExtension($rs[0][default_vdo]));
		}
				
		if($_REQUEST["propid"]){
		
			$rs = $album->getAlbumByFields('id',$_REQUEST["propid"]);
			list($rs_vdo, $numpad) = $album->mediaList($_REQUEST['pageNo'], 10,'', 'ARRAY_A','',"album_video","video",'',$_REQUEST['propid']);
			$framework->tpl->assign("VIDEO_DETAILS",$rs_vdo);
			list($rs_photo, $numpad) = $album->mediaList($_REQUEST['pageNo'], 10,'', 'ARRAY_A','',"album_photos","photo",'',$_REQUEST['propid']);
			$framework->tpl->assign("PHOTO_DETAILS",$rs_photo);
			for($i=0;$i<count($rs_photo);$i++){
				$photo_ext[$i] = $objPhoto->imgExtension($rs_photo[$i][id]);
			}
			$framework->tpl->assign("PHOTO_EXT",$photo_ext);
			if(count($rs_vdo) > 0)
			{
				foreach($rs_vdo as $row)
				{
				  $dur[] = implode(":",$album->secs2hms($row["length"]));// calculate duration video clips
				}
			}
			$framework->tpl->assign("DURATION",$dur);
			$framework->tpl->assign("SING_V_DETAIL",$objVideo->getVideoDetails($rs[0][default_vdo]));
			$framework->tpl->assign("PROP_DETAILS",$rs);
			$framework->tpl->assign("PROP_CNTRY",$album->getCountryName($rs[0][prop_country]));
			$framework->tpl->assign("FEATURES",$album->propertyCategoryName($_REQUEST["propid"],'features'));
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/album/tpl/property_detail.tpl");
			
		}else{
			list($rsPropDeta,$numpad,$cnt_rs, $limitList) = $album->propertyView($_REQUEST['pageNo'],$_REQUEST["limit"], "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy'],$_REQUEST["user_id"]);
			$framework->tpl->assign("PROP_DETAILS",$rsPropDeta);
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/album/tpl/property_list.tpl");
			
		}
	
	break;
	case "active":
	
		if($_REQUEST['propid']){
		
			if($_REQUEST['stat'] == "N")
			$stat = "Y";
			else
			$stat = "N";
			
		$framework->db->update("album",array("active" => $stat),"id=".$_REQUEST['propid']);
		redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=propdView"));

		}

		
	break;
	case "edit_property":
	// Author : Afsal Ismail
	// Created on : 10-24-2007
	
		include_once(SITE_PATH."/includes/areaedit/include.php");
		editorInit('prop_description');

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
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
		
			unset($_POST["Submit"]);
			
			if(isset($_REQUEST['features']))
			{
				$_POST["features"] = implode(",",$_REQUEST['features']);
			}
			$req = $_POST;

			if($global["show_property"] == 1){ //realestate tube
					
				//Update
				if($_REQUEST['propid'] <> "" && $_REQUEST['user_id'])
				{
					$album->updateAlbum($req,$_REQUEST['propid'],$_REQUEST['user_id']);
					setMessage("The Property <b>".$req["prop_title"]."</b> is updated. Now you can edit/upload video, Photos to your property here.",MSG_SUCCESS);
					redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"),"act=propdView&&propid={$_REQUEST['propid']}&fid=".$_REQUEST['fid']));
				}
			}
		}
		
		if($global["show_property"] == 1) //realestate tube
		{
			$framework->tpl->assign("LISTING_TYPE_PARENT",1);
			$framework->tpl->assign("PROPERTY_TYPE_PARENT",11); 	
			$framework->tpl->assign("SALE_TYPE_PARENT",21);
			$framework->tpl->assign("FEATURES_PARENT",39);
			
			$framework->tpl->assign("LISTING_TYPE",$objCategory->getChildCategoriesList("Property Listing Type"));
			$framework->tpl->assign("PROPERTY_TYPE",$objCategory->getChildCategoriesList("Real Estate Property Type"));
			$framework->tpl->assign("SALE_TYPE", $objCategory->getChildCategoriesList("Sale Type"));
			$framework->tpl->assign("FEATURES",$objCategory->getChildCategoriesList("Property Features"));
			
		}
			
		$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
		$framework->tpl->assign("SECTION_LIST", $album->menuSectionList());
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/album_new.tpl");
		
	break;
	case "video_list":
		$param = "mod={$mod}&pg={$pg}&act=video_list";
		if($_REQUEST['state_id'])
		{
			$param.="&state_id=".$_REQUEST['state_id'];
		}
		if($_REQUEST['state_search'])
		{
			$param.="&state_search=".$_REQUEST['state_search'];
		}
		
		list($rs, $numpad) = $objVideo->videoList($_REQUEST['pageNo'],10,$param, OBJECT, 'id desc',0,0,0,$_REQUEST['state_id'],$_REQUEST['state_search']);
		if(count($rs)>0)
		{
			foreach($rs as $key=>$value)
			{
				$rs[$key]->state_name=$album->getSateName($value->state);
			}
		}	
		$framework->tpl->assign("STATE_LIST",$states->GetAllStates(840));
		$framework->tpl->assign("VIDEO_NUMPAD", $numpad);
		$framework->tpl->assign("VIDEO_LIST", $rs);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/video_list.tpl");
		break;
	case "video_details":
		include_once(SITE_PATH."/includes/flashPlayer/include.php");
		$phdet = $objVideo->getVideoDetails($_REQUEST["video_id"]);
		$framework->tpl->assign("PHDET",$phdet);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/video_details.tpl");
		break;
	case "upload":
		checkLogin(1);
        if($_SERVER['REQUEST_METHOD'] == "POST")
        {
           	unset($_POST["image_x"],$_POST["image_y"]);
			$_POST["user_id"] = $_SESSION['adminSess']->id;	
			$req = $_POST;
			
            $fname	 =	$_FILES['videoFile']['name'];
            $ftype	 =	$_FILES['videoFile']['type'];
            $fsize	 =	$_FILES['videoFile']['size'];
            $ferror	 =	$_FILES['videoFile']['error'];
            $tmpname =	$_FILES['videoFile']['tmp_name'];

            $fileext=$album->file_extension($fname);

            $dir=SITE_PATH."/modules/album/video/";

            if(!$ferror){
                if(in_array(strtolower($fileext), array('mov', 'wmv', 'mpg', 'avi', '3gp', 'dat', 'asx')))
                {
                    $mov = new ffmpeg_movie($tmpname);
                    $req['dimension_width']  =  $mov->getFrameWidth();
                    $req['dimension_width']  =  $req['dimension_width'] ? $req['dimension_width'] : 320;

                    $req['dimension_height'] =  $mov->getFrameHeight();
                    $req['dimension_height'] =  $req['dimension_height'] ? $req['dimension_height'] : 240;

                    $req['filesize']         =  $fsize;
                    $req['length']			 =  $mov->getDuration();
					$album->setArrData($req);
					
				    $id = $album->insertVideoDetails($req);
                  
                    if(strtolower($fileext) == "3gp") {
                        $_3gp_fix = " -an"; // right now 3gp is not supporting audio format, so it will work only if we strip the audio
                    } else {
                    	$_3gp_fix = "";
                    }

                    shell_exec("ffmpeg -i {$tmpname} -ab 56 -ar 22050 -f flv{$_3gp_fix} {$dir}{$id}.flv");
                    chmod("${dir}{$id}.flv",0777);
					
					shell_exec("ffmpeg -ss 1 -t 2 -i {$tmpname} -an -f flv{$_3gp_fix} -s 130x100 {$dir}{$id}_small.flv");
					chmod("${dir}{$id}_small.flv",0777);
					
                    $mov = new ffmpeg_movie("${dir}{$id}.flv");
                    $frame = $mov->getFrame(10);
                    if($frame) {
	                    $frame->resize(110, 80);
	                    $image = $frame->toGDImage();
	                    imagejpeg($image, "${dir}thumb/{$id}.jpg", 100);
                    }
								
					if ($framework->config['admin_video']=="Y")
					{
						redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=video_details&video_id=$id"));
					}
					else 
					{
						###  Portion to send mail to subscribers................................................
					$mail_header = array();
					$mail_header['from'] 	    = 	$framework->config['admin_email'];
					//$toemails					=	$music->getSubscriberEmails($_SESSION["memberid"],"video");
		
						for($i=0;$i<sizeof($toemails);$i++){
						
							$mail						=	$toemails[$i]["email"];
							$udet						=	$user->getUserdetails($_REQUEST['user_id']);
							$mail_header["to"]          = 	$mail;
							$dynamic_vars               = 	array();
							$dynamic_vars["USERNAME"]  	= 	$udet["username"];
							$dynamic_vars["SITE"] 		=	$framework->config['site_name'];
							$dynamic_vars["LINK"]       = 	"<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"album", "pg"=>"video"), "act=details&video_id=$id")."\">Get Your new Video</a>";
							$email->send("send_to_subscribers",$mail_header,$dynamic_vars);
						}
					}		
					
		
					if($global["show_property"] == 1)//realestate tube..... Set the default video
					{
						$rs = $album->propertyList("album_video","video",0,$_REQUEST["propid"],$_REQUEST['user_id']);
				
						if(count($rs) ==1)
						{
						 $default_vdo = $rs[0]["id"];
						}
						else
						{
						  if(isset($_REQUEST['default_vdo']))
						   $default_vdo = $id;
						}
						if($default_vdo >0)
						$album->updateAlbum(array("default_vdo" => $default_vdo),$_REQUEST['propid']);
						setMessage("Video has been uploaded Successfully",MSG_SUCCESS);
						redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=propdView&propid={$_REQUEST['propid']}&view=edit"));
					}
				}
                else
                {
                    $message="You have to select one movie file. We support MOV, WMV, MPG, 3GP, DAT, ASX and AVI files.";
                }
            } else {
                $message = $fileError[$ferror];
            }
			setMessage($message);
        }
      	
		if($global["show_property"] == 1)//realestate tube
		{
			$param = "mod=".$mod."&pg=".$pg."&act=".$_REQUEST['act']."&propid=".$_REQUEST['propid'];
			list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 4,$param, 'ARRAY_A','id:DESC',"album_video","video",'',$_REQUEST['propid'],$_REQUEST['user_id']);
			$framework->tpl->assign("VIDEO_DETAILS",$rs);
			$framework->tpl->assign("NUM_PAD",$numpad);
			$rsPropDeta = $album->getAlbumByFields('user_id,id',$_REQUEST['user_id'].",".$_REQUEST["propid"]);
			$framework->tpl->assign("PROP_DETAILS",$rsPropDeta);
			
		}
        $framework->tpl->assign("SECTION_LIST", $album->albumSectionList());
        
		$cat_list = $objCategory->getCategoryByModule($_REQUEST['mod'],1);
		//$rs			=	$states->GetAllStates(840);
		$framework->tpl->assign("STATE_LIST",$states->GetAllStates(840));
		        
        $framework->tpl->assign("CATEGORY",$cat_list);
        if ($framework->config['admin_video']=="Y")
        {
        	$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/upload_admin_video.tpl");		
        }
        else 
        {
        	$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/upload_video.tpl");		
        }
		
	break;
	case "editvideo":
		/*
		Real Estate tube
		*/
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			$objVideo->updateVideoInfo($_REQUEST);
			setMessage("Video details have been updated",MSG_SUCCESS);
			redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=upload&propid={$_REQUEST['propid']}"));
		}
		$rsPropDeta = $album->getAlbumByFields('user_id,id',$_SESSION["memberid"].",".$_REQUEST["propid"]);
		list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 10,'', 'ARRAY_A','',"album_video","video",'',$_REQUEST['propid'],$_SESSION['memberid']);
		$_REQUEST=$_REQUEST +  $objVideo->getVideoDetails($_REQUEST['vid']);
		$framework->tpl->assign("PROP_DETAILS",$rsPropDeta);
		$framework->tpl->assign("VIDEO_DETAILS",$rs);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/upload_video.tpl");
		
	break;
	case "delvdo":

			$album->mediaDelete($_REQUEST["vid"],"M1");
			setMessage("Your video has been removed",MSG_SUCCESS);			
			redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=upload&propid={$_REQUEST['propid']}"));
		
	break;
	case "upload_photo":
	
			if ($_SERVER['REQUEST_METHOD']=="POST")
			{
				unset($_POST["image_x"],$_POST["image_y"],$_POST["Submit"]);
				$_POST["postdate"]=date("Y-m-d G:i:s");
				$_POST["user_id"]=$_REQUEST["user_id"];

				/*
				check the image format
				*/
				// If proect=Jewish chekUploadImageValidation1  else chekUploadImageValidation
				$photoArr = $objPhoto->chekUploadImageValidation();

				
				if ($photoArr != "error")
				{
				/**/
					$title   = $_POST["title"];
					$photoid = $_POST['photoid'];
					
					$default_img = isset($_POST['default_img'])? $_POST['default_img'] : "" ;
					unset($_POST["title"],$_POST['submit'],$_POST['photoid'],$_POST['default_img']);

					for($i=0;$i<count($photoArr);$i++)
					{
					
						if(count($title) == 1)
						$_POST["title"] =  $title[0];
						else
						$_POST["title"] =  $title[$photoArr[$i]];
						
						if(isset($photoid[$photoArr[$i]]))
							$updateid = $photoid[$i];
						else
							$updateid = 0;
						$objPhoto->setArrData($_POST);
						
						$objPhoto->uploadPhoto($_REQUEST['propid'],$_REQUEST['crt'],$photoArr[$i],$updateid);
						
					}
					
					if(isset($photoid))
					{
						for($i=0;$i<count($photoid);$i++)
						{
							$_POST["title"] = $title[$i];
							$objPhoto->setArrData($_POST);
							$objPhoto->uploadPhoto($_REQUEST['propid'],$_REQUEST['crt'],'',$photoid[$i]);

						}
						
					}
					
					if($global["show_property"] == 1) //realestate tube
					{
						if($default_img =='')
						{
							$rs = $album->propertyList("album_photos","photo",0,$_REQUEST["propid"],$_REQUEST['user_id']);
							$default_img = $rs[0]["id"];
						}
						else
						{
							$default_img = $default_img;
						}
						
						$album->updateAlbum(array("default_img" => $default_img),$_REQUEST['propid'],$_REQUEST['user_id']);
					}
				###  Portion to send mail to subscribers................................................
					$mail_header = array();
					$mail_header['from'] 	    = 	$framework->config['admin_email'];
					//$toemails					=	$music->getSubscriberEmails($_REQUEST["user_id"],"music");
					for($i=0;$i<sizeof($toemails);$i++){
						$mail						=	$toemails[$i]["email"];
						$udet						=	$objUser->getUserdetails($_REQUEST["user_id"]);
						$mail_header["to"]          =	$mail;
						$dynamic_vars               =	array();
						$dynamic_vars["USERNAME"]  	=	$udet["username"];
						$dynamic_vars["SITE"]  		=	$framework->config['site_name'];
						$dynamic_vars["LINK"]       =	"<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"album", "pg"=>"photo"), "act=list&crt=M1")."\">Get Your new Photo</a>";
						$email->send("send_to_subscribers",$mail_header,$dynamic_vars);
					
					}
					
					### End Portion to send mail to subscribers..............................................
					setMessage("Photos have been updated successfully",MSG_SUCCESS);
					
					if($global["show_property"] == 1)
						redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"),"act=propdView&propid={$_REQUEST['propid']}"));
					else
						redirect(makeLink(array("mod"=>"album", "pg"=>"photo"),"act=list&crt=M1"));					
				}
			}
			
			$rs = $album->propertyList("album_photos","photo",0,$_REQUEST["propid"],$_REQUEST['user_id']);
			$framework->tpl->assign("PROP_DETAILS",$album->getAlbumByFields('user_id,id',$_REQUEST['user_id'].",".$_REQUEST["propid"]));
			
			$framework->tpl->assign("PHOTO_LIST",$rs);
			$framework->tpl->assign("SECTION_LIST", $album->albumSectionList()); 
						
			$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/upload_photos.tpl");
			break;

	break;
	case "defaultvdo":
	
	if($_REQUEST['vid'] && $_REQUEST['propid'])
	$album->updateAlbum(array("default_vdo" => $_REQUEST['vid']),$_REQUEST['propid']);
	redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=upload&propid={$_REQUEST['propid']}&user_id=".$_REQUEST['user_id']));
	break;
	case "addtopic":
	if($_SERVER['REQUEST_METHOD']=='POST')
	{
		$req = &$_REQUEST;
		if( ($message = $album->addEditReviewTopic($req)) === true ) {
                if ($_REQUEST['id']) {
                    redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=listtopic&link=review"));
                } else {
                    redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=listtopic&link=review"));
                }
            }
	}
	setMessage($message);
	if($message) {
           $row = $_POST;
     } elseif($_REQUEST['id']) {
        $row = $album->getReviewTopicDetails($_REQUEST['id']);
     }	
		 $framework->tpl->assign("REVIEW_TOPIC", $row);
	$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/addtopic.tpl");
	break;
	case "listtopic":
	list($rs, $numpad) = $album->reviewTopicList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&orderBy=".$_REQUEST['orderBy']."&link=review", OBJECT, $_REQUEST['orderBy']);
    $framework->tpl->assign("REVIEWTOPIC_LIST", $rs);
    $framework->tpl->assign("REVIEWTOPIC_NUMPAD", $numpad);
	$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/listtopic.tpl");
	break;
	case "topicdelete":
		$album->reviewTopicDelete($_REQUEST['id']);
        setMessage("Topic Deleted Successfully!", MSG_SUCCESS);
		redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=listtopic&link=review"));
	break;
	case "addquestion":
	if($_SERVER['REQUEST_METHOD']=='POST')
	{
		$req = &$_REQUEST;
		if( ($message = $album->addEditReviewQuestion($req)) === true ) {
                if ($_REQUEST['id']) {
                    redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=listquestion&link=review"));
                } else {
                    redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=listquestion&link=review"));
                }
            }
	}
	setMessage($message);
	if($message) {
           $row = $_POST;
     } elseif($_REQUEST['id']) {
        $row = $album->getReviewQuestionDet($_REQUEST['id']);
     }	
		 $framework->tpl->assign("REVIEWQ", $row);
		 
	$framework->tpl->assign("TOPICARR", $album->getReviewTopics());
	$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/addquestion.tpl");
	break;
	case "listquestion":
	list($rs, $numpad) = $album->reviewQuestionList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&orderBy=".$_REQUEST['orderBy']."&link=review", OBJECT, $_REQUEST['orderBy']);
    $framework->tpl->assign("QUESTIONLIST", $rs);
    $framework->tpl->assign("QUESTION_NUMPAD", $numpad);
	$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/listquestion.tpl");
	break;
	case "questiondelete":
		$album->reviewQuestionDelete($_REQUEST['id']);
        setMessage("Question Deleted Successfully!", MSG_SUCCESS);
		redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=listquestion&link=review"));
	break;
	case "addoption":
	if($_SERVER['REQUEST_METHOD']=='POST')
	{
		$req = &$_REQUEST;
		if( ($message = $album->addEditReviewOption($req)) === true ) {
                if ($_REQUEST['id']) {
                    redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=listoption&link=review"));
                } else {
                    redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=listoption&link=review"));
                }
            }
	}
	setMessage($message);
	if($message) {
           $row = $_POST;
     } elseif($_REQUEST['id']) {
        $row = $album->getReviewOptionDet($_REQUEST['id']);
     }	
		 $framework->tpl->assign("OPTION", $row);
		 
	$framework->tpl->assign("QARR", $album->getReviewQuestions());
	$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/add_option.tpl");
	break;
	case "listoption":
	list($rs, $numpad) = $album->reviewOptionList($_REQUEST['pageNo'], 20, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&orderBy=".$_REQUEST['orderBy']."&link=review", OBJECT, $_REQUEST['orderBy']);
    $framework->tpl->assign("OPTIONLIST", $rs);
    $framework->tpl->assign("OPTION_NUMPAD", $numpad);
	$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/list_option.tpl");
	break;
	case "articles":
	if (($_REQUEST["action1"]) || ($_REQUEST["action2"]))
		{	
			if (($_REQUEST["action1"]=="delete") || ($_REQUEST["action2"]=="delete"))
			{
				$files=$_REQUEST["chk"];
				$length=sizeof($files);
				for($i=0;$i<$length;$i++)
				{
					$album->albumDelete($files[$i]);
				}
			}
			
			redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=articles&link=Y&crt=".$_REQUEST['crt']."&cat_id=".$_REQUEST["cat_id"]));
		}	
	    $par = "mod=".$_REQUEST['mod']."&pg=".$_REQUEST['pg']."&act=".$_REQUEST['act']."&link=Y";
		if ($_REQUEST["cat_id"])
		{
				$par = $par."&cat_id=".$_REQUEST['cat_id'];
		}
		if ($_REQUEST["cat_id"])
		{	
			$framework->tpl->assign("CRT",$_REQUEST["cat_id"]);
			$catname=$objUser->getCatName($_REQUEST["cat_id"]);
			$framework->tpl->assign("GRP_HEADER", $catname["category_name"]);
			list($articles, $numpad) = $album->getAlbumAll($_REQUEST['pageNo'],15, $par,'ARRAY_A', $_REQUEST['orderBy'],'cat_id',$_REQUEST["cat_id"],0,0,0,$stxt);				
		}	
		else
		$par = "mod=".$_REQUEST['mod']."&pg=".$_REQUEST['pg']."&act=".$_REQUEST['act']."&link=Y";
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
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/article_admin.tpl");
	break;
	case"article_det":
		$article=$album->getArticleDetails($_REQUEST['id']);
		$framework->tpl->assign("ARTICLE",$article);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/article_det.tpl");
	break;
	case "optiondelete":
		$album->reviewOptionDelete($_REQUEST['id']);
        setMessage("Option Deleted Successfully!", MSG_SUCCESS);
		redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=listoption&link=Y"));
	break;
	case "add_conference":
  	   
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
		$req = &$_REQUEST;
		unset($_POST['delId']);
		unset($_POST['frmAction']);
		$album->setArrData($_POST);
		$tags_array=explode('|',$_POST['tag']);
		if($req['frmAction'])
		{
			unset($_POST['tag']);
			$album->setArrData($_POST);
			$array=$album->getArrData();
			if(isset($req['delId']) && $req['frmAction']=='update')
			{
				$id= $req['delId'];
			    $album->db->update("conference", $array, "id='$id'");
				$album->db->query("DELETE FROM conference_tags WHERE conid='$id'");
			}
			else if($req['frmAction']=='insert')
			{
				$id=$album->db->insert("conference", $array);
			}
			for($i=0;$i< count($tags_array);$i++)
			{
				if($tags_array[$i])
				{
					$tag =array('conid'=>$id,'tag'=>trim(strtolower($tags_array[$i])),'type'=>'name');
					$album->db->insert("conference_tags", $tag);
				}
			}
			redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_conference&link=Y"));	
		}	
		
		if( ($message = $album->addEditConference($req)) === true ) {
             if ($_REQUEST['id']) {
                   redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_conference&link=Y"));
              } else {
                   redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_conference&link=Y"));
              }
            } 
			else if(is_array($message))
			{
				$conference_array = array();
				for($j=0;$j< count($message);$j++)
				{
					$conference_array[]=$album->getConferenceDetails($message[$j],false);
				}
				$conferenceList='<table border=0 width=80% cellpadding="0" cellspacing="0" > 
									<tr>
										<td colspan=3 align="center"><span style="color:#FF0000"><b>Conference already listed.</b></span></td>
									</tr>
									<tr>
										<td colspan=3 align="center">&nbsp;</td>
									</tr>';
									if(count($conference_array)!=1)
									{		
									$conferenceList.='<tr>
												<td width="5%" height="24" align="center"></td> 
												<td width="40%"  height="24" align="left"><strong>Full conference Name</strong></td>
												 <td width="30%"   height="24" align="left"><strong>Acronym</strong></td>
												 <td width="25%"   height="24" align="left"><strong>Year</strong></td>
											</tr>';
											
									for($i=0;$i< count($conference_array);$i++)
									{		
									$conferenceList.='<tr>
												<td width="5%" height="24" align="center"><input name="delId" id="delId" type="checkbox" value="'.$conference_array[$i]['id'].'"></td> 
												<td width="40%"  height="24" align="left">'.$conference_array[$i]['conference_name'].'</td>
												<td width="35%"   height="24" align="left">'.$conference_array[$i]['conference_acronym'].'</td>
												<td width="35%"   height="24" align="left">'.$conference_array[$i]['conference_year'].'</td>
											</tr>';
									}
									$conferenceList.='<tr>
														<td colspan=3 align="center">&nbsp;</td>
													</tr>
													 <tr> 
														<td><input name="frmAction" id="frmAction" type="hidden" value=""></td>
														<td>&nbsp;</td>
														 <td  valign=center width="60%"><input type="button" name="btn" value="Over write" class="naBtn" onClick="chkFrom()">&nbsp;&nbsp;<input type="button" name="btn2" value="Create New" class="naBtn" onClick="createNew();">&nbsp;&nbsp;<input type="button" value="Cancel" class="naBtn" onClick=window.location="'.makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_conference&link=Y").'"></td> 
													 </tr>'; 
									}
									else
									{
										$conferenceList.='<tr>
												<td width="20%" height="24" align="center"><input name="delId" id="delId" type="hidden" value="'.$conference_array[0]['id'].'"></td> 
												<td width="35%"  height="24" align="left"><input name="frmAction" id="frmAction" type="hidden" value=""><span style="color:#FF0000"><b>Do you want to over write?.</b></span></td>
												<td  valign=center><input type="button" name="btn" value="Yes" class="naBtn" onClick="chkUpdate();">&nbsp;&nbsp;<input type="button" value="No" class="naBtn" onClick=window.location="'.makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_conference&link=Y").'"&nbsp;&nbsp;>&nbsp;&nbsp;<input type="button" name="btn2" value="Create New" class="naBtn" onClick="createNew();"></td> 
											</tr>';
									}	
									 $conferenceList.='<tr>
														<td colspan=3 align="center">&nbsp;</td>
													</tr>
							   				 		</table>';		
			setMessage($conferenceList);										
						
			}
			else
			setMessage($message);
		}
		if($message) {
           $row = $_POST;
     	} elseif($_REQUEST['id']) {
        $row = $album->getConferenceDetails($_REQUEST['id']);
     	}	
		$year=date("Y");
		for($i=1900;$i<=$year;$i++){
			$yearlist[]=$i;
		}
		$framework->tpl->assign("NOW", $year);
		$framework->tpl->assign("CONFERENCE", $row);
		$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
		$framework->tpl->assign("YEAR_LIST", $yearlist);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/add_article.tpl");
	break;
	case "list_conference":
		list($rs, $numpad) = $album->getConferenceList($_REQUEST['pageNo'], 20, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&orderBy=".$_REQUEST['orderBy']."&link=Y", OBJECT, $_REQUEST['orderBy']);
		$framework->tpl->assign("CONFERENCE_LIST", $rs);
		$framework->tpl->assign("CONFERENCE_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/list_conference.tpl");
	break;
	case "conference_delete":
		$album->conferenceDelete($_REQUEST['id']);
		$album->db->query("DELETE FROM conference_tags WHERE conid=".$_REQUEST['id']."");
        setMessage("Conference Deleted Successfully!", MSG_SUCCESS);
		redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_conference&link=Y"));
	break;
	case "add_journal":
		 /**
		   * This  is used to add the journal article.
		   * Author   : Adarsh
		   * Created  : 14/Nov/2007
		   * Modified : 
   		   */			
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
		$req = &$_REQUEST;
		$tags_array=explode('|',$_POST['tag']);
		unset($_POST['delId']);
		unset($_POST['frmAction']);
		$album->setArrData($_POST);
		if($req['frmAction'])
		{
			unset($_POST['tag']);
			$album->setArrData($_POST);
			$array=$album->getArrData();
			if($req['frmAction']=='update' && isset($req['delId']))
			{
				 $id= $req['delId'];
			     $album->db->update("journals", $array, "id='$id'");
				 $album->db->query("DELETE FROM journal_name_tags WHERE journal_id='$id'");
			}
			else if($req['frmAction']=='insert')
		     {
				$id=$album->db->insert("journals", $array);
			 }
			for($i=0;$i< count($tags_array);$i++)
			{
				if($tags_array[$i])
				{	
					$tag =array('journal_id'=>$id,'tag'=>trim(strtolower($tags_array[$i])),'type'=>'name');
					$album->db->insert("journal_tags", $tag);
				}	
			}
			 redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_journal&link=Y"));
		}
		if( ($message = $album->addEditJournal($req)) === true ) {
             if ($_REQUEST['id']) {
                   redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_journal&link=Y"));
              } else {
                   redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_journal&link=Y"));
              }
            }
			else if(is_array($message))
			{
				$journal_array = array();
				for($j=0;$j< count($message);$j++)
				{
					$journal_array[]=$album->getJournalDetails1($message[$j],false);
				}
				$journalList='		<table border=0 width=80% cellpadding="0" cellspacing="0" > 
											<tr>
												<td colspan=3 align="center"><span style="color:#FF0000"><b>Journal already listed.</b></span></td>
											</tr>
											<tr>
												<td colspan=3 align="center">&nbsp;</td>
											</tr>';
											
									if(count($journal_array)!=1)
									{		
										$journalList.='<tr>
													<td width="5%" height="24" align="center"></td> 
													<td width="40%"  height="24" align="left"><strong>Full Journal Name</strong></td>
													 <td width="30%"   height="24" align="left"><strong>Acronym</strong></td>
													 <td width="25%"   height="24" align="left"><strong>Year</strong></td>
												</tr>';
										for($i=0;$i< count($journal_array);$i++)
										{		
										$journalList.='<tr>
													<td width="5%" height="24" align="center"><input name="delId" id="delId" type="checkbox" value="'.$journal_array[$i]['id'].'"></td> 
													<td width="40%"  height="24" align="left">'.$journal_array[$i]['journal_name'].'</td>
													<td width="35%"   height="24" align="left">'.$journal_array[$i]['journal_acronym'].'</td>
													<td width="35%"   height="24" align="left">'.$journal_array[$i]['journal_year'].'</td>
												</tr>';
										}
										$journalList.='<tr>
															<td colspan=3 align="center">&nbsp;</td>
														</tr>
														 <tr> 
																<td><input name="frmAction" id="frmAction" type="hidden" value=""></td>
																<td>&nbsp;</td>
																<td  valign=center width="80%" ><input type="button" name="btn" value="Over write" class="naBtn" onClick="chkFrom()">&nbsp;&nbsp;<input type="button" name="btn2" value="Create New" class="naBtn" onClick="createNew();">&nbsp;&nbsp;<input type="button" value="Cancel" class="naBtn" onClick=window.location="'.makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_journal&link=Y").'"></td> 
														 </tr> ';
														
									}				
									else
									{				
									$journalList.='<tr>
												<td width="20%" height="24" align="center"><input name="delId" id="delId" type="hidden" value="'.$journal_array[0]['id'].'"></td> 
												<td width="35%"  height="24" align="left"><input name="frmAction" id="frmAction" type="hidden" value=""><span style="color:#FF0000"><b>Do you want to over write?.</b></span></td>
												<td  valign=center><input type="button" name="btn" value="Yes" class="naBtn" onClick="chkUpdate();">&nbsp;&nbsp;<input type="button" value="No" class="naBtn" onClick=window.location="'.makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_journal&link=Y").'">&nbsp;&nbsp;<input type="button" name="btn2" value="Create New" class="naBtn" onClick="createNew();"></td> 
											</tr>';		
									}
									$journalList.='<tr>
														<td colspan=3 align="center">&nbsp;</td>
												  </tr>
												</table>';				
			setMessage($journalList);										
						
			}
			else
			setMessage($message);
		}
		if($message) {
           $row = $_POST;
     	} elseif($_REQUEST['id']) {
        $row = $album->getJournalDetails1($_REQUEST['id']);
     	}	
		 $framework->tpl->assign("OPTION", $row);
		$year=date("Y");
		for($i=1900;$i<=$year;$i++){
			$yearlist[]=$i;
		}
		$framework->tpl->assign("NOW", $year);
		$framework->tpl->assign("JOURNAL", $row);
		$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
		$framework->tpl->assign("YEAR_LIST", $yearlist);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/add_journal.tpl");
	break;
	case "list_journal":
		/**
		   * This  is used to list the journal article.
		   * Author   : Adarsh
		   * Created  : 14/Nov/2007
		   * Modified : 
 		   */			
		list($rs, $numpad) = $album->getJournalList($_REQUEST['pageNo'], 20, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&orderBy=".$_REQUEST['orderBy']."&link=Y", OBJECT, $_REQUEST['orderBy']);
		
		$framework->tpl->assign("JOURNAL_LIST", $rs);
		$framework->tpl->assign("JOURNAL_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/list_journal.tpl");
	break;
	case "journal_delete":
		/**
		   * This  is used to delete the journal article.
		   * Author   : Adarsh
		   * Created  : 14/Nov/2007
		   * Modified : 
		   */			
		$album->journalDelete($_REQUEST['id']);
		$album->db->query("DELETE FROM journal_name_tags WHERE conid=".$_REQUEST['id']."");
        setMessage("Journal Deleted Successfully!", MSG_SUCCESS);
		redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_journal&link=Y"));
	break;
	case "add_book":
		 /**
		   * This  is used to add the journal article.
		   * Author   : Adarsh
		   * Created  : 14/Nov/2007
		   * Modified : 
   		   */			
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
		$req = &$_REQUEST;
		unset($_POST['delId']);
		unset($_POST['frmAction']);
		$album->setArrData($_POST);
		if($req['frmAction'])
		{
			unset($_POST['book_title_tag']);
			unset($_POST['book_author_tag']);
			unset($_POST['book_publisher_tag']);
			$album->setArrData($_POST);
			$title_tag_array=explode('|',$req['book_title_tag']);
			$author_tag_array=explode('|',$req['book_author_tag']);
			$publisher_tag_array=explode('|',$req['book_publisher_tag']);
			$array=$album->getArrData();	
			if(isset($req['delId'])&& $req['frmAction']=='update')
			{
				$id= $req['delId'];
				$album->db->update("book", $array, "id='$id'");
				$album->db->query("DELETE FROM book_tags WHERE book_id='$id'");
			}
			else if( $req['frmAction']=='insert'){
					$id=$album->db->insert("book", $array);
			}
			for($i=0;$i< count($title_tag_array);$i++)
			{
				if($title_tag_array[$i])
				{
					$tag =array('book_id'=>$id,'tag'=>trim(strtolower($title_tag_array[$i])),'type'=>'title');
					$album->db->insert("book_tags", $tag);
				}	
			}
			for($i=0;$i< count($author_tag_array);$i++)
			{
				if($author_tag_array[$i])
				{
					$tag =array('book_id'=>$id,'tag'=>trim(strtolower($author_tag_array[$i])),'type'=>'author');
					$album->db->insert("book_tags", $tag);
				}	
			}
			for($i=0;$i< count($publisher_tag_array);$i++)
			{
				if($publisher_tag_array[$i])
				{
					$tag =array('book_id'=>$id,'tag'=>trim(strtolower($publisher_tag_array[$i])),'type'=>'publisher');
					$album->db->insert("book_tags", $tag);
				}	
			}
			
			redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_book&link=Y"));
		}
		if( ($message = $album->addEditBook($req)) === true ) {
             if ($_REQUEST['id']) {
                   redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_book&link=Y"));
              } else {
                   redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_book&link=Y"));
              }
            }
			else if(is_array($message))
			{
				$book_array = array();
				for($j=0;$j< count($message);$j++)
				{
					$book_array[]=$album->getBookDetails($message[$j],false);
				}
				
				$bookList='		<table border=0 width=80% cellpadding="0" cellspacing="0" > 
											<tr>
												<td colspan=3 align="center"><span style="color:#FF0000"><b>Book already listed.</b></span></td>
											</tr>
											<tr>
												<td colspan=3 align="center">&nbsp;</td>
											</tr>';
									if(count($book_array)!=1)
									{		
										$bookList.='<tr>
												<td width="5%" height="24" align="center"></td> 
												<td width="40%"  height="24" align="left"><strong>Book title</strong></td>
												 <td width="30%"   height="24" align="left"><strong>Author/Edited By</strong></td>
												 <td width="25%"   height="24" align="left"><strong>Year</strong></td>
											</tr>';
									for($i=0;$i< count($book_array);$i++)
									{		
									$bookList.='<tr>
												<td width="5%" height="24" align="center"><input name="delId" id="delId" type="checkbox" value="'.$book_array[$i]['id'].'"></td> 
												<td width="40%"  height="24" align="left">'.$book_array[$i]['book_title'].'</td>
												<td width="35%"   height="24" align="left">'.$book_array[$i]['book_author'].'</td>
												<td width="35%"   height="24" align="left">'.$book_array[$i]['book_year'].'</td>
											</tr>';
									}		
									$bookList.='<tr>
														<td colspan=3 align="center">&nbsp;</td>
													</tr>
													 <tr> 
															<td><input name="frmAction" id="frmAction" type="hidden" value=""></td>
															<td>&nbsp;</td>
														 	<td  width="80%"   valign=center><input type="button" name="btn" value="Over write" class="naBtn" onClick="chkFrom()">&nbsp;&nbsp;<input type="button" name="btn2" value="Create New" class="naBtn" onClick="createNew();">&nbsp;&nbsp;<input type="button" value="Cancel" class="naBtn" onClick=window.location="'.makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_book&link=Y").'"></td> 
													 </tr>';
									}
									else
									{
										$bookList.='<tr>
												<td width="20%" height="24" align="center"><input name="delId" id="delId" type="hidden" value="'.$book_array[0]['id'].'"></td> 
												<td width="35%"  height="24" align="left"><input name="frmAction" id="frmAction" type="hidden" value=""><span style="color:#FF0000"><b>Do you want to over write?.</b></span></td>
												<td  valign=center><input type="button" name="btn" value="Yes" class="naBtn" onClick="chkUpdate();">&nbsp;&nbsp;<input type="button" value="No" class="naBtn" onClick=window.location="'.makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_book&link=Y").'">&nbsp;&nbsp;<input type="button" name="btn2" value="Create New" class="naBtn" onClick="createNew();"></td> 
											</tr>';	
									}				  
										$bookList.=	 '<tr>
														<td colspan=3 align="center">&nbsp;</td>
													</tr>
							   				 		</table>';
			setMessage($bookList);										
						
			}
			else
			setMessage($message);
		}
		if($message) {
           $row = $_POST;
     	} elseif($_REQUEST['id']) {
        $row = $album->getBookDetails($_REQUEST['id']);
     	}	
		 $framework->tpl->assign("OPTION", $row);
		$year=date("Y");
		for($i=1900;$i<=$year;$i++){
			$yearlist[]=$i;
		}
		$framework->tpl->assign("NOW", $year);
		$framework->tpl->assign("BOOK", $row);
		$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
		$framework->tpl->assign("YEAR_LIST", $yearlist);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/add_book.tpl");
	break;
	case "list_book":
		 /**
		   * This  is used to list the book type article.
		   * Author   : Adarsh
		   * Created  : 14/Nov/2007
		   * Modified : 
 		   */			
		list($rs, $numpad) = $album->getBookList($_REQUEST['pageNo'], 20, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&orderBy=".$_REQUEST['orderBy']."&link=Y", OBJECT, $_REQUEST['orderBy']);
		$framework->tpl->assign("BOOK_LIST", $rs);
		$framework->tpl->assign("BOOK_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/list_book.tpl");
	break;
	case "book_delete":
		/**
		   * This  is used to delete the journal article.
		   * Author   : Adarsh
		   * Created  : 14/Nov/2007
		   * Modified : 
		   */			
		$album->bookDelete($_REQUEST['id']);
		$album->db->query("DELETE FROM book_tags WHERE book_id=".$_REQUEST['id']."");
        setMessage("Book Deleted Successfully!", MSG_SUCCESS);
		redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_book&link=Y"));
	break;
	case "add_institution":
		 /**
		   * This  is used to add the institution type article.
		   * Author   : Adarsh
		   * Created  : 14/Nov/2007
		   * Modified : 
   		   */			
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
		$req = &$_REQUEST;
		unset($_POST['delId']);
		unset($_POST['frmAction']);
		
		if($req['frmAction'])
		{
			unset($_POST['institution_name_tag']);
			unset($_POST['institution_city_tag']);
			unset($_POST['institution_state_tag']);
			$album->setArrData($_POST);
			
			$name_tag_array=explode('|',$req['institution_name_tag']);
			$city_tag_array=explode('|',$req['institution_city_tag']);
			$state_tag_array=explode('|',$req['institution_state_tag']);
			if(isset($req['delId']) && $req['frmAction']=='update')
			{
				$array=$album->getArrData();
				$id= $req['delId'];
				$album->db->update("institution", $array, "id='$id'");
				$album->db->query("DELETE FROM institution_tags WHERE institution_id='$id'");
			}
			else if( $req['frmAction']=='insert')
			{
				$array=$album->getArrData();
				$id=$album->db->insert("institution", $array);
			}
			for($i=0;$i< count($name_tag_array);$i++)
			{
				if($name_tag_array[$i])
				{
					$tag =array('institution_id'=>$id,'tag'=>trim(strtolower($name_tag_array[$i])),'type'=>'name');
					$album->db->insert("institution_tags", $tag);
				}	
			}
					
			for($i=0;$i< count($city_tag_array);$i++)
			{
				if($institution_city_tag[$i])
				{
					$tag =array('institution_id'=>$id,'tag'=>trim(strtolower($city_tag_array[$i])),'type'=>'city');
					$album->db->insert("institution_tags", $tag);
				}	
			}
			for($i=0;$i< count($state_tag_array);$i++)
			{
				if($state_tag_array[$i])
				{
					$tag =array('institution_id'=>$id,'tag'=>trim(strtolower($state_tag_array[$i])),'type'=>'state');
					$album->db->insert("institution_tags", $tag);
				}	
			}
			redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_institution&link=Y"));
			
		}
		$album->setArrData($_POST);
		if( ($message = $album->addEditInstitution($req)) === true ) {
             if ($_REQUEST['id']) {
                   redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_institution&link=Y"));
              } else {
                   redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_institution&link=Y"));
              }
            }
			else if(is_array($message))
			{
				$institution_array = array();
				for($j=0;$j< count($message);$j++)
				{
					$institution_array[]=$album->getInstitutionDetails($message[$j],false);
				}
				
				$institutionList='		<table border=0 width=80% cellpadding="0" cellspacing="0" > 
											<tr>
												<td colspan=3 align="center"><span style="color:#FF0000"><b>Institution already listed.</b></span></td>
											</tr>
											<tr>
												<td colspan=3 align="center">&nbsp;</td>
											</tr>';
									if(count($institution_array)!=1)
									{			
									$institutionList.='<tr>
												<td width="5%" height="24" align="center"></td> 
												<td width="40%"  height="24" align="left"><strong>Name</strong></td>
												 <td width="30%"   height="24" align="left"><strong>City</strong></td>
												 <td width="25%"   height="24" align="left"><strong>State</strong></td>
											</tr>';
									for($i=0;$i< count($institution_array);$i++)
									{		
									$institutionList.='<tr>
												<td width="5%" height="24" align="center"><input name="delId" id="delId" type="checkbox" value="'.$institution_array[$i]['id'].'"></td> 
												<td width="40%"  height="24" align="left">'.$institution_array[$i]['institution_name'].'</td>
												<td width="35%"   height="24" align="left">'.$institution_array[$i]['institution_city'].'</td>
												<td width="35%"   height="24" align="left">'.$institution_array[$i]['institution_state'].'</td>
											</tr>';
									}		
									$institutionList.='<tr>
														<td colspan=3 align="center">&nbsp;</td>
													</tr>
													 <tr> 
															<td><input name="frmAction" id="frmAction" type="hidden" value=""></td>
															<td>&nbsp;</td>
														 	<td width="80%"  valign=center><input type="button" name="btn" value="Over write" class="naBtn" onClick="chkFrom()">&nbsp;&nbsp;<input type="button" name="btn2" value="Create New" class="naBtn" onClick="createNew();">&nbsp;&nbsp;<input type="button" value="Cancel" class="naBtn" onClick=window.location="'.makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_institution&link=Y").'"></td> 
													 </tr>'; 
									}
									else
									{
										$institutionList.='<tr>
												<td width="20%" height="24" align="center"><input name="delId" id="delId" type="hidden" value="'.$institution_array[0]['id'].'"></td> 
												<td width="35%"  height="24" align="left"><input name="frmAction" id="frmAction" type="hidden" value=""><span style="color:#FF0000"><b>Do you want to over write?.</b></span></td>
												<td  valign=center><input type="button" name="btn" value="Yes" class="naBtn" onClick="chkUpdate();">&nbsp;&nbsp;<input type="button" value="No" class="naBtn" onClick=window.location="'.makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_institution&link=Y").'">&nbsp;&nbsp;<input type="button" name="btn2" value="Create New" class="naBtn" onClick="createNew();"></td> 
											</tr>';	
									}				 
									 	$institutionList.=' <tr>
														<td colspan=3 align="center">&nbsp;</td>
													</tr>
							   				 		</table>';
			setMessage($institutionList);										
						
			}
			else
			setMessage($message);
		}
		if($message) {
           $row = $_POST;
     	} elseif($_REQUEST['id']) {
        $row = $album->getInstitutionDetails($_REQUEST['id']);
     	}	
		 $framework->tpl->assign("OPTION", $row);
		$year=date("Y");
		for($i=1900;$i<=$year;$i++){
			$yearlist[]=$i;
		}
		$framework->tpl->assign("OPTION", $row);
		$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
		$framework->tpl->assign("YEAR_LIST", $yearlist);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/add_institution.tpl");
	break;
	case "list_institution":
		 /**
		   * This  is used to list the institution type article.
		   * Author   : Adarsh
		   * Created  : 14/Nov/2007
		   * Modified : 
 		   */			
		list($rs, $numpad) = $album->getInstitutionList($_REQUEST['pageNo'], 20, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&orderBy=".$_REQUEST['orderBy']."&link=Y", OBJECT, $_REQUEST['orderBy']);
		$framework->tpl->assign("INSTITUTION_LIST", $rs);
		$framework->tpl->assign("INSTITUTION_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/list_institution.tpl");
	break;
	case "institution_delete":
		/**
		   * This  is used to delete the institution type article.
		   * Author   : Adarsh
		   * Created  : 14/Nov/2007
		   * Modified : 
		   */			
		$album->institutionDelete($_REQUEST['id']);
		$album->db->query("DELETE FROM institution_tags WHERE institution_id=".$_REQUEST['id']."");
        setMessage("Institution  Deleted Successfully!", MSG_SUCCESS);
		redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_institution&link=Y"));
	break;
	case "add_author":
		 /**
		   * This  is used to add the author article.
		   * Author   : Adarsh
		   * Created  : 14/Nov/2007
		   * Modified : 
   		   */			
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
		$req = &$_REQUEST;
		unset($_POST['delId']);
		unset($_POST['frmAction']);
		$album->setArrData($_POST);
		if($req['frmAction'])
		{
			$name_tag_array=explode('|',$req['author_name_tag']);
			$institution_tag_array=explode('|',$req['author_institution_tag']);
			unset($_POST['author_name_tag']);
			unset($_POST['author_institution_tag']);
			$album->setArrData($_POST);
			if(isset($req['delId'])&& $req['frmAction']=='update')
			{
				$array=$album->getArrData();
				$id= $req['delId'];
				$album->db->update("author_details", $array, "id='$id'");
			    $album->db->query("DELETE FROM author_tags WHERE author_id='$id'");
			}
			else if($req['frmAction']=='insert')
			{
				$array=$album->getArrData();
				$id=$album->db->insert("author_details", $array);
			}
			for($i=0;$i< count($name_tag_array);$i++)
			{
				if($name_tag_array[$i])
				{
					$tag =array('author_id'=>$id,'tag'=>trim(strtolower($name_tag_array[$i])),'type'=>'name');
					$album->db->insert("author_tags", $tag);
				}	
			}
						
			for($i=0;$i< count($institution_tag_array);$i++)
			{
				if($institution_tag_array[$i])
				{
					$tag =array('author_id'=>$id,'tag'=>trim(strtolower($institution_tag_array[$i])),'type'=>'institution');
					$album->db->insert("author_tags", $tag);
				}	
			}
			redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_author&link=Y"));
		}
		if( ($message = $album->addEditAuthor($req)) === true ) {
             if ($_REQUEST['id']) {
                   redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_author&link=Y"));
              } else {
                   redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_author&link=Y"));
              }
            }
			else if(is_array($message))
			{
				$author_array = array();
				for($j=0;$j< count($message);$j++)
				{
					$author_array[]=$album->getAuthorDetails($message[$j],false);
				}
				
				$authorList='<table border=0 width=80% cellpadding="0" cellspacing="0" > 
											<tr>
												<td colspan=3 align="center"><span style="color:#FF0000"><b>Author already listed.</b></span></td>
											</tr>
											<tr>
												<td colspan=3 align="center">&nbsp;</td>
											</tr>';
											
									if(count($author_array)!=1)
									{		
									 $authorList.='<tr>
												<td width="5%" height="24" align="center"></td> 
												<td width="40%"  height="24" align="left"><strong>Author Name</strong></td>
												 <td width="40%"   height="24" align="left"><strong>Institution </strong></td>
												 <td width="30%"   height="24" align="left"><strong>Country</strong></td>
											</tr>';
									for($i=0;$i< count($author_array);$i++)
									{		
									$authorList.='<tr>
												<td width="5%" height="24" align="center"><input name="delId" id="delId" type="checkbox" value="'.$author_array[$i]['id'].'"></td> 
												<td width="40%"  height="24" align="left">'.$author_array[$i]['author'].'</td>
												<td width="40%"   height="24" align="left">'.$author_array[$i]['institution'].'</td>
												<td width="35%"   height="24" align="left">'.$author_array[$i]['country_name'].'</td>
											</tr>';
									}		
									$authorList.='<tr>
														<td colspan=3 align="center">&nbsp;</td>
													</tr>
													 <tr> 
															<td><input name="frmAction" id="frmAction" type="hidden" value=""></td>
															<td>&nbsp;</td>
														 	<td  width="80%" valign=center><input type="button" name="btn" value="Over write" class="naBtn" onClick="chkFrom()">&nbsp;&nbsp;<input type="button" name="btn2" value="Create New" class="naBtn" onClick="createNew();">&nbsp;&nbsp;<input type="button" value="Cancel" class="naBtn" onClick=window.location="'.makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_author&link=Y").'"></td> 
													 </tr>'; 
										}			 
										else
										{
											$authorList.='<tr>
												<td width="20%" height="24" align="center"><input name="delId" id="delId" type="hidden" value="'.$author_array[0]['id'].'"></td> 
												<td width="35%"  height="24" align="left"><input name="frmAction" id="frmAction" type="hidden" value=""><span style="color:#FF0000"><b>Do you want to over write?.</b></span></td>
												<td  valign=center><input type="button" name="btn" value="Yes" class="naBtn" onClick="chkUpdate();">&nbsp;&nbsp;<input type="button" name="btn2" value="Create New" class="naBtn" onClick="createNew();">&nbsp;&nbsp;<input type="button" value="No" class="naBtn" onClick=window.location="'.makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_author&link=Y").'"></td> 
											</tr>';	
										}
										$authorList.=' <tr>
														<td colspan=3 align="center">&nbsp;</td>
													</tr>
							   				 		</table>';
			setMessage($authorList);										
						
			}
			else
			setMessage($message);
		}
		if($message) {
           $row = $_POST;
     	} elseif($_REQUEST['id']) {
        $row = $album->getAuthorDetails($_REQUEST['id']);
     	}	
		 $framework->tpl->assign("OPTION", $row);
		$year=date("Y");
		for($i=1900;$i<=$year;$i++){
			$yearlist[]=$i;
		}
		
		$framework->tpl->assign("BOOK", $row);
		$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
		$framework->tpl->assign("YEAR_LIST", $yearlist);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/add_author.tpl");
	break;
	case "list_author":
		 /**
		   * This  is used to list the authors.
		   * Author   : Adarsh
		   * Created  : 14/Nov/2007
		   * Modified : 
 		   */			
		list($rs, $numpad) = $album->getAuthorList($_REQUEST['pageNo'], 20, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&orderBy=".$_REQUEST['orderBy']."&link=Y", OBJECT, $_REQUEST['orderBy']);
		$framework->tpl->assign("AUTHOR_LIST", $rs);
		$framework->tpl->assign("AUTHOR_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/list_author.tpl");
	break;
	case "author_delete":
		/**
		   * This  is used to delete the author.
		   * Author   : Adarsh
		   * Created  : 16/Nov/2007
		   * Modified : 
		   */			
		$album->authorDelete($_REQUEST['id']);
		$album->db->query("DELETE FROM author_tags WHERE author_id=".$_REQUEST['id']."");
        setMessage("Author Deleted Successfully!", MSG_SUCCESS);
		redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_author&link=Y"));
	break;
	case "add_constant":
		/**
		   * This  is used to add value to constant .
		   * Author   : Adarsh
		   * Created  : 02/Jan/2008.
		   * Modified : 
		   */
		 if($_SERVER['REQUEST_METHOD']=='POST')
		 {
		 	$album->setArrData($_POST);
			$array=$album->getArrData();
			$id=$album->db->update("vqr", $array);
		 } 
		 	
		 $framework->tpl->assign("OPTION", $album->getVQR());
		  			
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/add_vqr.tpl");
		break;
	case "add_article":
		/**
		   * This  is used to add article from admin side through xml file.
		   * Author   : Adarsh
		   * Created  : 08/Jan/2008.
		   * Modified : 
		   */
		 if($_SERVER['REQUEST_METHOD']=='POST')
		 {
		 	$fname	 =	$_FILES['article_xml']['name'];
			$ftype	 =	$_FILES['article_xml']['type'];
			$fsize	 =	$_FILES['article_xml']['size'];
			$ferror	 =	$_FILES['article_xml']['error'];
			$tmpname =	$_FILES['article_xml']['tmp_name'];
			$fileext=$album->file_extension($fname);
			if($fileext=='xml')
			{
				$p =& new xmlParser();
				$p->parse($tmpname);
				$article=$p->output;
				$array=$album->getArticleArray($article);
				$id=$album->insetArticle($array,$objCategory);
				setMessage("Article Uploaded successfully.");
				unset($_FILES);
			}
			else
			setMessage("Invalid file.");	
			
		 }  
		   
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/article_xml.tpl");
		break;	
	case "conjunctions":
		/**
		   * This  is used to add article and conjunctions .
		   * Author   : Adarsh
		   * Created  : 15/jan/2008.
		   * Modified : 
		   */
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
			$req = &$_REQUEST;
			if(($message = $album->addEditConjunctions($req)) === true ) {
                if ($_REQUEST['id']) {
                    redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_conjunction"));
                } else {
                    redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=conjunctions"));
                }
            }
		}
		setMessage($message);
		if($message) {
           $row = $_POST;
    	 } elseif($_REQUEST['id']) {
        	$row = $album->getConjunctionById($_REQUEST['id']);
     	}	
		$framework->tpl->assign("OPTION", $row);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/conjunction.tpl");
		break;	
	case "list_conjunction":
		 /**
		   * This  is used to list article and conjunctions .
		   * Author   : Adarsh
		   * Created  : 15/jan/2008.
		   * Modified : 
		   */
		list($rs, $numpad) = $album->getConjunctionList($_REQUEST['pageNo'], 20, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&orderBy=".$_REQUEST['orderBy'], OBJECT, $_REQUEST['orderBy']);
		$framework->tpl->assign("RS", $rs);
		$framework->tpl->assign("NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/list_conjunction.tpl");
	break;
	case "conjunction_delete":
	 	/**
		   * This  is used to delete article and conjunctions .
		   * Author   : Adarsh
		   * Created  : 15/jan/2008.
		   * Modified : 
		   */
		$album->conjunctionDelete($_REQUEST['id']);
        setMessage("Conjunction Deleted Successfully!", MSG_SUCCESS);
		redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=list_conjunction"));
	break;	
	case "edit_video":
		
		$video_id = $_REQUEST['video_id'];
		$video_det = $objVideo->getVideoDetails($video_id);
		if ($video_det)
		{
			$_REQUEST=$_REQUEST+$video_det;
		}
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			$arr = array();
			$arr['title'] = $_POST['title'];
			$arr['cat_id'] = $_POST['cat_id'];
			$arr['description'] =$_POST['description'];
			$objVideo->setArrData($arr);
			$objVideo->editVideo($video_id);
			redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=video_list"));
			
		}
		$cat_list = $objCategory->getCategoryByModule($_REQUEST['mod'],1);
        $framework->tpl->assign("CATEGORY",$cat_list);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/album/tpl/edit_admin_video.tpl");
		break;
	case "del_video":
		$video_id = $_REQUEST['video_id'];
		$album->mediaDelete($video_id,"M1");
		redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=video_list"));
		break;	
	case"make_home":
		$state_id = $_REQUEST['state_id'];
		$id=$_REQUEST['video_id'];
		
		$album->updateMediaDetByState($state_id);
		$array=array("home_appearance"=>'Y');
		$album->setArrData($array);
		$album->editMediaDetails ($id,'album_video','','');
		redirect(makeLink(array("mod"=>"album", "pg"=>"album_admin"), "act=video_list&state_id=".$_REQUEST['state_ret_id']."&state_search=".$_REQUEST['state_search']));
		break;
			
	default:
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/main.tpl");
		break;
}
$framework->tpl->display($global['curr_tpl']."/admin.tpl");
?>