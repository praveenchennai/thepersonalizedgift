<?php
include_once(FRAMEWORK_PATH."/modules/album/lib/class.video.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.music.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.search.php");

$music=new Music();
$email= new Email();
$album= new Album();
$video=new Video();
$objUser=new User();
$objCms = new Cms();
$flyer = new Flyer();
$categ = new Category();
$objSearch	    =   new Search();

$memberID = $_SESSION['memberid'];
$video_id = $_REQUEST['video_id'];
$vdodet = $video->getVideoDetails($video_id);
if($vdodet["user_id"]==$_SESSION["memberid"]){
	$framework->tpl->assign("OWNER","YES");
}


$fileError = array(
1=>"The uploaded file exceeds the maximum allowed file size",
2=>"The uploaded file exceeds the maximum allowed file size",
3=>"The uploaded file was only partially uploaded",
4=>"No file was uploaded",
6=>"Missing a temporary folder"
);
if($global["sort_by_category_name"]=="1")
$catarr=$objUser->getCategoryArr($_REQUEST["mod"],1);
else
$catarr=$objUser->getCategoryArr($_REQUEST["mod"]);
$catlist= $objUser->getCategoryCombo($_REQUEST["mod"]);
$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
$framework->tpl->assign("advertisement",1);
$framework->tpl->assign("inner_content",1);
$framework->tpl->assign("CAT_LIST",$catlist);
$framework->tpl->assign("CAT_ARR",$catarr);
$framework->tpl->assign("LEFTBOTTOM","upload_video" );
if(isset($_SESSION['chps1']))
{
	$framework->tpl->assign("chps1",$_SESSION['chps1']);
	if(!$_REQUEST["parent_cat"]){
		$_REQUEST["parent_cat"]=40;
	}
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
	$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("finace_right"));// to select the right menu of this page from database.
	$rightmenu=$objCms->linkList("finance_right");
}
elseif($_SESSION['chps1']==5){
	$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("dating_right"));// to select the right menu of this page from database.
	$rightmenu=$objCms->linkList("dating_right");
}
else
{
	$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));
	if(!$_REQUEST["parent_cat"]){
		$_REQUEST["parent_cat"]=40;
	}
}


if($_REQUEST["pid"]!=""){
	$framework->tpl->assign("pid", $_REQUEST["pid"]);
}
switch($_REQUEST['act'])
{
	default:
	case "list":
		include_once(SITE_PATH."/includes/flashPlayer/include.php");
		$framework->tpl->assign("TITLE_HEAD", "Video List");
		if($_REQUEST["subscribe"])
		{
			// for subscribing videos..... jipson
			checkLogin();
			$res=$album->Subscribe($memberID,$_REQUEST["subscribe"],"video");
			//print_r($res);exit;
			if($res!="true"){
				setMessage($album->getErr());
			}
		}elseif($_REQUEST["unsubscribe"]){
			// for unsubscribing videos..... jipson
			checkLogin();
			$res=$album->Unsubscribe($memberID,$_REQUEST["unsubscribe"],"video");
			//print_r($res);exit;
			if($res!="true"){
				setMessage($album->getErr());
			}
		}
		if ($_REQUEST["cat_id"])
		{
			$catname=$objUser->getCatName($_REQUEST["cat_id"]);
			$framework->tpl->assign("FILTER",$_REQUEST["cat_id"]);
			$framework->tpl->assign("PH_HEADER", $catname["category_name"]);
			list($rs, $numpad) = $video->videoList($_REQUEST['pageNo'], 5, "mod={$mod}&pg={$pg}&act=list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}&cat_id={$_REQUEST["cat_id"]}", OBJECT, "id desc", $_REQUEST["cat_id"], $_REQUEST['txtSearch']);
		}
		elseif ($_REQUEST["filter"])
		{
			include_once(SITE_PATH."/includes/flashPlayer/include.php");

			if ($_REQUEST["filter"]=="recent")
			{
				$pheader="Most Recent";
				$field="postdate desc";
				/*
				Jewish
				*/
				$phdet = $video->getVideoDetails($_REQUEST["video_id"]);


				if($phdet["user_id"]==$_SESSION["memberid"]){
					$framework->tpl->assign("OWNER","YES");
				}
				$height = $phdet['dimension_height']+20;
				$link=SITE_URL."/".makeLink(array("mod"=>"album", "pg"=>"album"), "act=embed&video_id={$phdet['id']}");
				$embed_url = "<iframe frameborder='0'  marginheight='0' marginwidth='0'  width='400' height='350' src='$link' scrolling='no'></iframe>";
				$framework->tpl->assign("EMBED_URL",$embed_url);
				$v_url =SITE_URL."/index.php?". $_SERVER['QUERY_STRING'];
				$framework->tpl->assign("VISIBLE_URL",$v_url);
				/*
				Jewish
				*/

			}
			elseif ($_REQUEST["filter"]=="viewed")
			{
				$pheader="Most Viewed";
				$field="views desc";
			}
			elseif ($_REQUEST["filter"]=="discussed")
			{
				$pheader="Most Discussed";
				$field="cmcnt desc";
			}
			elseif ($_REQUEST["filter"]=="rated")
			{
				$pheader="Top Rated";
				$field="rating desc";
			}
			elseif ($_REQUEST["filter"]=="favorites")
			{
				$pheader="Top Favorites";
				$field="favcnt desc";
			}

			$framework->tpl->assign("FILTER",$_REQUEST["filter"]);
			$framework->tpl->assign("PH_HEADER", $pheader);
			list($rs, $numpad) = $video->videoList($_REQUEST['pageNo'], 10, "mod={$mod}&pg={$pg}&act=list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}", OBJECT, $field,0,$_REQUEST['txtSearch']);

			/* new */
			if($rs[0]->cat_id > 0)
			list($rscat, $numpad) = $video->videoList($_REQUEST['pageNo'], 10, "mod={$mod}&pg={$pg}&act=list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}", OBJECT, $field,$rs[0]->cat_id,$_REQUEST['txtSearch']);
			if($_REQUEST['video_id'] >0 )
			{
				$videodet=$video->getVideoDetails($_REQUEST['video_id']);
			}
			else if($rs[0]->cat_id > 0)
			{
				$videodet=$video->getVideoDetails($rs[0]->id);
			}
			if($memberID && $videodet["user_id"]!=""){
				$sub=$album->chkSubscribe($memberID,$videodet["user_id"],"video");
				$framework->tpl->assign("SUBS",$sub);
			}
			//print_r($videodet);exit;
			$framework->tpl->assign("SING_VIDEO_DET",$videodet);

		}
		else
		{
			//if (!$_REQUEST["filter"]){
			//	$_REQUEST["filter"]="nofilter";
			//}
			if($_REQUEST['view'] == "all")
			$param = "mod={$mod}&pg={$pg}&act=list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}&view=all";
			else
			$param = "mod={$mod}&pg={$pg}&act=list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}";
			if($global["change_movie_video"]==1){
				$framework->tpl->assign("PH_HEADER", "All Videos");
			}else{
				$framework->tpl->assign("PH_HEADER", "All Movies");
			}

			list($rs, $numpad) = $video->videoList($_REQUEST['pageNo'],10,$param, OBJECT, 'id desc',0,$_REQUEST['txtSearch']);

		}
		if(count($rs) > 0)
		{
			foreach($rs as $row)
			{

				$dur[] = implode(":",$album->secs2hms($row->length));// calculate duration video clips
			}
		}
		for ($i=0;$i<sizeof($rs);$i++)
		{
			$medet=$objUser->getUsernameDetails($rs[$i]->username);
			$rs[$i]->nick_name=$medet["nick_name"];
			if($medet["user_id"]==$_SESSION["memberid"]){
				$rs[$i]->owner="Y";
			}

		}
		//print_r($rs);exit;


		if($_REQUEST["rate"])
		{
			checkLogin();
			$array=array();
			$array["type"]    = "video";
			$array["file_id"] = $_REQUEST["video_id"];
			$array["userid"]  = $_SESSION["memberid"];
			$array["mark"]    = $_REQUEST["rate"];
			$video->setArrData($array);
			if($global["new_album_functions"]==1){
				$msg=$album->AddRating('album_video',$_REQUEST["video_id"],'video',$_REQUEST["rate"],$_SESSION["memberid"]);
				$framework->tpl->assign("MESSAGE",$msg);
				setMessage($msg);
			}else{
				if(!$video->rateVideo())
				{
					setMessage($video->getErr());

				}
				else
				{
					redirect(makeLink(array("mod"=>"album", "pg"=>"video"),"act=list&filter={$_REQUEST['recent']}"));
				}
			}
		}
		$framework->tpl->assign("DURATION",$dur);
		$framework->tpl->assign("VIDEO_LIST", $rs);
		$framework->tpl->assign("VIDEO_LIST_CAT", $rscat);
		if($rs[0]->cat_id > 0)
		$rscat = $objUser->getCatName($rs[0]->cat_id) ;
		$framework->tpl->assign("VIDEO_NUMPAD", $numpad);
		$framework->tpl->assign("CAT_NAME",$rscat["category_name"]);
		$framework->tpl->assign("RIGT_TPL", SITE_PATH."/modules/album/tpl/right_top_btn.tpl");
		if($_REQUEST['view'] == "all"){
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/all_videos.tpl");
			$framework->tpl->assign("left_tpl", SITE_PATH."/modules/album/tpl/video_list_left.tpl");
		}else{
			//if($global["hide_video_list"]==1){
			//$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/all_videos.tpl");
			//$framework->tpl->assign("left_tpl", SITE_PATH."/modules/album/tpl/video_list_left.tpl");
			//}else{
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/video_list.tpl");
			$framework->tpl->assign("left_tpl", SITE_PATH."/modules/album/tpl/video_list_left.tpl");
			//}
		}
		break;
		###The code from Industry page for simple video listing.............
	case "simple_list":
		if($global["inner_change_reg"]=="yes")
		{
			checklogin();
		}
		if($_REQUEST["subscribe"])
		{
			// for subscribing videos..... jipson
			checkLogin();
			$res=$album->Subscribe($memberID,$_REQUEST["subscribe"],"video");
			//print_r($res);exit;
			if($res!="true"){
				setMessage($album->getErr());
			}
		}elseif($_REQUEST["unsubscribe"]){
			// for unsubscribing videos..... jipson
			checkLogin();
			$res=$album->Unsubscribe($memberID,$_REQUEST["unsubscribe"],"video");
			//print_r($res);exit;
			if($res!="true"){
				setMessage($album->getErr());
			}
		}
		if(!$_REQUEST["parent_cat"]){
			$_REQUEST["parent_cat"]=0;

		}
		list($catlist,$numcat)=$categ->getSubcategories($_REQUEST["parent_cat"]);
		if(!$_REQUEST["cat_id"]){
			$_REQUEST["cat_id"]=$_REQUEST["parent_cat"];
		}
		$catname=$objUser->getCatName($_REQUEST["cat_id"]);
		$framework->tpl->assign("CATEG_LIST",$catlist);
		$framework->tpl->assign("PARENT",$_REQUEST["parent_cat"]);
		//print_r($catlist);exit;
		if($_POST['txtSearch']){
			$_REQUEST['txtSearch']=$_POST['txtSearch'];
		}

		if ($_REQUEST["filter"])
		{
			if ($_REQUEST["filter"]=="recent")
			{
				$pheader="Most Recent ".$catname["category_name"];
				$field="postdate desc";
			}
			elseif ($_REQUEST["filter"]=="viewed")
			{
				$pheader="Most Viewed ".$catname["category_name"];
				$field="views desc";
			}
			elseif ($_REQUEST["filter"]=="discussed")
			{
				$pheader="Most Discussed ".$catname["category_name"];
				$field="cmcnt desc";
			}
			elseif ($_REQUEST["filter"]=="rated")
			{
				$pheader="Top Rated ".$catname["category_name"];
				$field="rating desc";
			}
			elseif ($_REQUEST["filter"]=="favorites")
			{
				$pheader="Top Favorites ".$catname["category_name"];
				$field="favcnt desc";
			}

			$framework->tpl->assign("FILTER",$_REQUEST["filter"]);
			$framework->tpl->assign("PH_HEADER", $pheader);
			$par="mod={$mod}&pg={$pg}&act=simple_list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}&cat_id={$_REQUEST['cat_id']}&parent_cat={$_REQUEST['parent_cat']}";
			list($rs, $numpad) = $video->videoList($_REQUEST['pageNo'], 5, $par, OBJECT, $field, $_REQUEST["cat_id"],$_REQUEST['txtSearch']);

		}else{


			$framework->tpl->assign("FILTER",$_REQUEST["cat_id"]);
			$framework->tpl->assign("PH_HEADER", $catname["category_name"]);
			$par="mod={$mod}&pg={$pg}&act=simple_list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}&cat_id={$_REQUEST['cat_id']}&parent_cat={$_REQUEST['parent_cat']}";
			list($rs, $numpad) = $video->videoList($_REQUEST['pageNo'], 5,$par, OBJECT, "id desc", $_REQUEST["cat_id"], $_REQUEST['txtSearch']);
		}
		//print_r($rs);
		//	exit;
		for ($i=0;$i<sizeof($rs);$i++)
		{
			$medet=$objUser->getUsernameDetails($rs[$i]->username);
			//print_r($medet);exit;
			$rs[$i]->nick_name=$medet["nick_name"];
			$rs[$i]->mem_type=$medet["mem_type"];
			if($medet["user_id"]==$_SESSION["memberid"]){
				$rs[$i]->owner="Y";
			}
			if($global['show_private']=='Y'){
				if($rs[$i]->privacy=='private'){
					if($rs[$i]->friends_can_see=='Y'){
						if($objUser->isFriends($medet["user_id"],$_SESSION["memberid"])==true){
							$rs[$i]->show_video="Y";
						}else{
							$rs[$i]->show_video="N";
						}
					}else{
						$rs[$i]->show_video="N";
					}
					if($medet["user_id"]==$_SESSION["memberid"]){
						$rs[$i]->show_video="Y";
					}
				}else{
					$rs[$i]->show_video="Y";
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
			list($res, $numpades) = $video->videoList('', 500000,$par, OBJECT, $field,$ctid, $_REQUEST['txtSearch']);
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
		$framework->tpl->assign("VIDEO_LIST", $rs);
		$framework->tpl->assign("VIDEO_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/video_list.tpl");
		$framework->tpl->assign("left_tpl", SITE_PATH."/modules/album/tpl/video_list_left.tpl");
		break;
		### End of the code from industrypage for simple video listing......
	case "fullscreen":
		include_once(SITE_PATH."/includes/flashPlayer/include.php");
		$framework->tpl->display(SITE_PATH."/modules/album/tpl/fullscreen.tpl");
		exit;
		break;
	case "details_prf":
		include_once(SITE_PATH."/includes/flashPlayer/include.php");
		$video->incrementView($_REQUEST["video_id"]);
		$phdet = $video->getVideoDetails($_REQUEST["video_id"]);
		$medet=$objUser->getUsernameDetails($phdet["username"]);
		$phdet["nick_name"]=$medet["nick_name"];
		$phdet["mem_type"]=$medet["mem_type"];
		$framework->tpl->assign("PHDET",$phdet);
		$framework->tpl->assign("PROFILE_HEAD",$MOD_VARIABLES['MOD_HEADS']['HD_VIDEO_UPLOAD']);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/video_details.tpl");
		//$framework->tpl->assign("profile_tpl",SITE_PATH."/modules/album/tpl/video_details.tpl");
		break;
	case "details":
		include_once(SITE_PATH."/includes/flashPlayer/include.php");
		$framework->tpl->assign("TITLE_HEAD", "Video Details");

		if($_REQUEST["fn"]!="share")
		{
			$video->incrementView($_REQUEST["video_id"]);
		}
		else
		{
			checkLogin();
			$objUserinfo = $objUser->getUserdetails($_SESSION["memberid"]);
			if($global["new_album_functions"]==1){
				$rs = $objUser->ViewFriends($_SESSION['memberid']);
				$framework->tpl->assign("CONTACT",$rs);
				//print_r($rs);exit;
			}else{
				$contact  = $objUser->listContacts($userinfo["username"]);
				$framework->tpl->assign("CONTACT",$contact);
			}
			$framework->tpl->assign("USERDET",$objUserinfo);
		}
		if($_SERVER['REQUEST_METHOD']=="POST")
		{

			checkLogin();
			if ($_REQUEST["fn"]=="share")
			{
				$arr=explode(",",$_POST["friends"]);
				for($i=0;$i<sizeof($arr);$i++){
					if($global["show_screen_name_only"]=="1"){
						$fdet=$objUser->getUnamebyscreenname($arr[$i]);
						$arruname[$i]=$fdet["username"];

					}
				}
				if($global["show_screen_name_only"]=="1"){
					$arr=$arruname;
				}
				for($i=0;$i<sizeof($arr);$i++)
				{

					if($arr[$i])
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

							$arrData["from"]     = $objUserinfo["username"];
							$arrData["to"]       = $arr[$i];
							$arrData["subject"]  = $_REQUEST["subject"];
							$arrData["datetime"] = date("Y-m-d G:i:s");
							$arrData["status"]   = "U";

							$touser   = $objUser->getUsernameDetails($arr[$i]);
							$touserid = $touser["id"];
							$phid    = $_REQUEST["video_id"];
							$from     = $objUserinfo["username"];

							$comment = $_REQUEST["comments"]."<br><br>";
							$comment = $comment."Click on the link below to view Movie<br>";
							$comment = $comment . "<a href=\"".SITE_URL."/".makeLink(array("mod"=>"album", "pg"=>"video"), "act=details&video_id=$phid")."\">View Movie</a>";
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

						$arrData["from"]     = $objUserinfo["username"];
						$arrData["to"]       = $arr1[$i];
						$arrData["subject"]  = $_REQUEST["subject"];

						$touser   = $objUser->getUsernameDetails($arr1[$i]);
						$touserid = $touser["id"];
						$phid    = $_REQUEST["video_id"];
						$from     = $objUserinfo["username"];

						//$comment = $_REQUEST["comments"]."<br><br>";

						$message="<div style='padding-left: 25px; padding-right: 25px;'>";
						$message=$message."<h2>I want to share the following Movie with you</h2>";
						$message=$message."<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"album", "pg"=>"video"), "act=details&video_id=$phid")."\"><img src='".SITE_URL ."/modules/album/video/thumb/$phid.jpg' border='0'></a>";
						$message=$message."<h4>Personal Message</h4>";
						$message=$message. "<p>". $_REQUEST["comments"] . "</p>";
						$message=$message."<p>Thanks,<br>";
						$message=$message. $objUserinfo["first_name"]. " " . $objUserinfo["last_name"] . "</p>";
						$message=$message."</div>";
						if($global["inner_change_reg"]=="yes")
						{
							mimeMail($arrData["to"],$arrData["subject"],$message,'','','Link54.com <'.$framework->config['admin_email'].'>');
						}
						else
						{
							mimeMail($arrData["to"],$arrData["subject"],$message,'','','Industrypage.com <'.$framework->config['admin_email'].'>');
						}

						//sendMail($arrData["to"],$arrData["subject"],$message,'Industrypage.com<'.$framework->config['admin_email'].'>','HTML');
					}
					}



					redirect(makeLink(array("mod"=>"album", "pg"=>"video"), "act=details&video_id=".$_REQUEST["video_id"]));
				}
				else
				{
					$framework->tpl->assign("MESSAGE",$invalid);
				}
				if($invalid!='')
				{

				}
			}
			else
			{
				$_POST["type"]     = "video";
				$_POST["user_id"]  = $_SESSION["memberid"];
				$_POST["file_id"] = $_REQUEST["video_id"];
				$_POST["postdate"] = date("Y-m-d G:i:s");
				unset($_POST["x"],$_POST["y"]);
				$video->setArrData($_POST);
				$video->postComment();
			}
		}
		if($_REQUEST["rate"])
		{
			checkLogin();
			if($global["new_album_functions"]==1){
				$msg=$album->AddRating('album_video',$_REQUEST["video_id"],'video',$_REQUEST["rate"],$_SESSION["memberid"]);
				$framework->tpl->assign("MESSAGE",$msg);
			}else{
				$array=array();
				$array["type"]    = "video";
				$array["file_id"] = $_REQUEST["video_id"];
				$array["userid"]  = $_SESSION["memberid"];
				$array["mark"]    = $_REQUEST["rate"];
				$video->setArrData($array);
				if(!$video->rateVideo())
				{
					setMessage($video->getErr());
				}
				else
				{
					redirect(makeLink(array("mod"=>"album", "pg"=>"video"),"act=list"));
				}
			}
		}
		if($_REQUEST["fn"]=="add")
		{
			checkLogin();
			if($global["new_album_functions"]==1){
				$msg=$album->AddFavourites('album_video',$_REQUEST["video_id"],'video',$_SESSION["memberid"]);
				$framework->tpl->assign("MESSAGE",$msg);
			}else{
				$array=array();
				$array["type"]    = "video";
				$array["file_id"] = $_REQUEST["video_id"];
				$array["userid"]  = $_SESSION["memberid"];
				$video->setArrData($array);
				if(!$video->addFavorite())
				{
					$framework->tpl->assign("MESSAGE",$video->getErr());
				}
				else
				{
					redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=favr&crt=M1&tpm=5"));
				}
			}
		}
		if($global["new_album_functions"]==1){
			$rate=$album->GetRatingNew('album_video',$_REQUEST["video_id"],'video');
			$framework->tpl->assign("RATE",$rate);
		}else{
			if($rate=$video->getRating('video',$_REQUEST["video_id"]))
			{
				$framework->tpl->assign("RATE",$rate);
			}
		}


		$phdet = $video->getVideoDetails($_REQUEST["video_id"]);
		//print_r($phdet);exit;
		$medet=$objUser->getUsernameDetails($phdet["username"]);
		////////////////to check to display the profile/////////////////////////////
		###to list private users Profile on the page
		### Modified on & Jan 2008.
		### Modified By Jinson.
		if($global['show_private']=='Y')
		{


			if($medet["mem_type"]==3){
				if($medet["friends_can_see"]=='Y')
				{
					if($objUser->isFriends($medet["user_id"],$_SESSION["memberid"])==true)
					{$phdet["view_profile"]="Y";}
					else{$phdet["view_profile"]="N";}
				}
				else{
					$phdet["view_profile"]="N";
				}


				if($medet["user_id"]==$_SESSION["memberid"]){
					$phdet["view_profile"]="Y";
				}

			}
			else{$rs[$i]->show_profile="Y";}


		}
		//////////////////////////////////////////
		$phdet["nick_name"]=$medet["nick_name"];
		$phdet["mem_type"]=$medet["mem_type"];
		//print_r($phdet);exit;

		if($memberID && $phdet["user_id"]!=""){
			$sub=$album->chkSubscribe($memberID,$phdet["user_id"],"video");
			//print_r($memberID);exit;
			$framework->tpl->assign("SUBS",$sub);
		}
		$framework->tpl->assign("PHDET",$phdet);

		$cmCount=$video->getCommentCount($_REQUEST["video_id"]);
		$framework->tpl->assign("COMMENT_COUNT",$cmCount["cnt"]);

		list($rs, $numpad) = $video->commentList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&video_id=".$_REQUEST["video_id"], OBJECT, $_REQUEST['orderBy'], $_REQUEST["video_id"]);
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
		//print_r($rs);
		$framework->tpl->assign("COMMENT_LIST",$rs);
		$framework->tpl->assign("COMMENT_NUMPAD", $numpad);
		if($_REQUEST["fn"]=="share")
		{
			$framework->tpl->assign("MEDIA","Movie");
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/share_media.tpl");
			$framework->tpl->assign("left_tpl", SITE_PATH."/modules/album/tpl/share_media_left.tpl");
		}
		else
		{
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/video_details.tpl");
			$framework->tpl->assign("left_tpl", SITE_PATH."/modules/album/tpl/video_details_left.tpl");
		}
		$framework->tpl->assign("TITLE", "Video Details");
		break;
	case "embed" :
		checkLogin();
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			$name = $_FILES['video_image']['name'];
			$proceed=1;
			/*if ($name)
			{
			if(!in_array(strtolower($_FILES['video_image']['type']), array('image/jpeg', 'image/jpg', 'image/pjpeg', 'image/pjpg')))
			{

			}
			}	*/

			unset($_POST["submit"]);
			$_POST["user_id"] = $_SESSION["memberid"];
			$album->setArrData($_POST);
			$album->insertVideoDetails();
			redirect(makeLink(array("mod"=>"album", "pg"=>"album"), "act=myalbum&crt=M1"));
		}

		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/upload_video.tpl");
		break;
	case "edit_video":
		checkLogin();
		$go_back_url = fetchPreURL();
		$framework->tpl->assign("EDIT", "Y");
		$id = $_REQUEST['id'];
		$vdet = $video->getVideoDetails($id);

		if($vdet["user_id"]==$_SESSION["memberid"]){
			$framework->tpl->assign("OWNER","YES");
		}
		$framework->tpl->assign("CAT_ID",$vdet['cat_id']);
		$timevar=time();
		if ($vdet)
		{
			$_REQUEST = $_REQUEST+$vdet;
		}
		//print_r($_REQUEST);exit;
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			unset($_POST["submit"]);
			if($_FILES['videoFile']['name']!="")
			{
				if($framework->config["redirect"] == 1){

					$videocnt = $_POST['videocnt'];
					unset($_POST['videocnt']);
				}
				if($framework->config["video_flv"] == "1")
				{
					$album->mymediaDelete($_REQUEST["id"].$vdet['time'],"M1",$_REQUEST["id"]);
				}
				else
				{
					$album->mediaDelete($_REQUEST["id"],"M1");
				}
				$req = $_POST;
				$fname	 =	$_FILES['videoFile']['name'];
				$ftype	 =	$_FILES['videoFile']['type'];
				$fsize	 =	$_FILES['videoFile']['size'];
				$ferror	 =	$_FILES['videoFile']['error'];
				$tmpname =	$_FILES['videoFile']['tmp_name'];
				$fileext=$album->file_extension($fname);
				$dir=SITE_PATH."/modules/album/video/";
				#######added by Jinson####################
				########this is to convert bytes(Maximum upload file size) to MB that set in the congig table.
				#########on 18 th April,2008
				$fs = round($framework->config['fileupload_maxsize'] / 1048576 * 100) / 100 ;
				###########
				if($framework->config['fileupload_maxsize']>$_FILES['videoFile']['size'])
				{

					if(!$ferror) {
						if(in_array(strtolower($fileext), array('mov', 'wmv', 'mpg','mpeg', 'avi', '3gp', 'dat', 'asx')))
						{
							$mov = new ffmpeg_movie($tmpname);
							$req['dimension_width']  =  $mov->getFrameWidth();
							$req['dimension_width']  =  $req['dimension_width'] ? $req['dimension_width'] : 320;

							$req['dimension_height'] =  $mov->getFrameHeight();
							$req['dimension_height'] =  $req['dimension_height'] ? $req['dimension_height'] : 240;

							$req['filesize']         =  $fsize;
							$req['length']			 =  $mov->getDuration();


							if(strtolower($fileext) == "3gp") {
								$_3gp_fix = " -an"; // right now 3gp is not supporting audio format, so it will work only if we strip the audio
							} else {
								$_3gp_fix = "";
							}
							shell_exec("ffmpeg -i {$tmpname} -ab 56 -ar 22050 -f flv{$_3gp_fix} {$dir}{$id}{$timevar}.flv");
							chmod("${dir}{$id}{$timevar}.flv",0777);

							$mov = new ffmpeg_movie("${dir}{$id}{$timevar}.flv");

							$frame = $mov->getFrame(10);

							if($frame) {

								if ($framework->config["video_thumb"]){

									$thumb_size  = explode(",",$framework->config["video_thumb"]);
									$thumb_width = $thumb_size[0];
									$thum_height = $thumb_size[1];


								}else{

									$thumb_width = 110;
									$thum_height = 80;
								}

								$frame->resize($thumb_width, $thum_height);
								$image = $frame->toGDImage();
								imagejpeg($image, "${dir}thumb/{$id}.jpg", 100);
							}


							$album->setArrData($_POST);

							$album->editMediaDetails($id,'album_video',$item,$timevar);

							###  Portion to send mail to subscribers................................................
							$mail_header = array();
							$mail_header['from'] 	    = 	$framework->config['admin_email'];
							$toemails					=	$music->getSubscriberEmails($_SESSION["memberid"],"video");
							/*for($i=0;$i<sizeof($toemails);$i++){

							$mail						=	$toemails[$i]["email"];
							$udet						=	$objUser->getUserdetails($_SESSION["memberid"]);
							$mail_header["to"]          = 	$mail;
							$dynamic_vars               = 	array();
							$dynamic_vars["USERNAME"]  	= 	$udet["username"];
							$dynamic_vars["SITE"] 		=	$framework->config['site_name'];
							$dynamic_vars["LINK"]       = 	"<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"album", "pg"=>"video"), "act=details&video_id=$id")."\">Get Your new Video</a>";
							$email->send("send_to_subscribers",$mail_header,$dynamic_vars);
							}
							*/

							if($global["show_property"]== 1)//realestate tube..... Set the default video
							{
								$rs = $album->propertyList("album_video","video",0,$_REQUEST["propid"],$_SESSION["memberid"]);

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
								if($framework->config["redirect"] == 1){/* Created:Afsal Ismail :For:Bayard project */

									//if($videocnt > 0 && $req["act"] !="editiinfo")
									//redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_view&propid={$_REQUEST['propid']}&flyer_id={$_REQUEST['flyer_id']}"));
									//else
									redirect(makeLink(array("mod"=>"album", "pg"=>"video"), "act=upload&propid={$_REQUEST['propid']}&flyer_id={$_REQUEST['flyer_id']}"));
								}
								else
								{
									redirect(makeLink(array("mod"=>"album", "pg"=>"album"), "act=propdView&propid={$_REQUEST['propid']}&view=edit"));
								}
							}
							else
							{

								redirect(makeLink(array("mod"=>"album", "pg"=>"video"), "act=details&video_id=$id"));
							}
						}
						else
						{
							$message="You have to select one movie file. We support MOV, WMV, MPG, 3GP, DAT, ASX and AVI files.";
						}
					} else {
						$message = $fileError[$ferror];
					}

				}else
				{
					$message="Maximun File Upload Size is ".$fs." MB";
				}
				setMessage($message);

			}
			else
			{
				$album->setArrData($_POST);
				$album->editMediaDetails($id,'album_video');
				if ($go_back_url)
				{
					redirect($go_back_url);
				}
				else
				{
					redirect(makeLink(array("mod"=>"album", "pg"=>"album"), "act=myalbum&crt=M1"));
				}
			}


		}
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/upload_video.tpl");
		break;
	case "upload":

		checkLogin();
		$flyer_id		=	$_REQUEST['flyer_id'];
		$prop_id		=	$_REQUEST['propid'];

		$flyerInfo      =   $flyer->getFlyerBasicFormData($_REQUEST['flyer_id']);

		if($global['Location_price_info']=='N'){
			if($flyerInfo['location_city']=="" || $flyerInfo['location_state']=="" || $flyerInfo['location_country']=="" || $flyerInfo['location_zip']=="" )
			{
				redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"),"act=property_form&flyer_id=$flyer_id&propid=$prop_id&red=1"));
			}
		}
		$FloatingPrice = $flyer->getFlatingPriceResults($prop_id);
		if($global['Location_price_info']=='N'){
			if($FloatingPrice['price']<1 || $FloatingPrice['duration']<1 || $FloatingPrice['unit']=="" )
			{
				redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"),"act=property_quantity&flyer_id=$flyer_id&propid=$prop_id&red=2"));
			}
		}
		if($_REQUEST["parent_cat"]){
			$framework->tpl->assign("CAT_ID",$_REQUEST["parent_cat"]);
		}
		if($global["show_property"] == 1){//realestate tube.....
			$framework->tpl->assign("TOP_MENU_SUB",SITE_PATH."/modules/album/tpl/top_menu.tpl");
		}

		/*
		* Created:Afsal
		* Proj:Bayard
		Created :29-11-2007
		*/
		$fs = round($framework->config['fileupload_maxsize'] / 1048576 * 100) / 100 ;
		$framework->tpl->assign("MAX_FILE_UPLOAD",$fs);
		if($framework->config["tab_show"] == "1")
		$StepsHTML		=	$flyer->getPropertyCreatonStepsHTML(6, $_REQUEST['flyer_id'],$_REQUEST['propid']);

		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			unset($_POST["image_x"],$_POST["image_y"]);
			$_POST["user_id"] = $_SESSION["memberid"];

			/*Redirect checking
			* Created:Afsal
			* Bayard:
			*/
			//print_r($_POST);exit;
			if($framework->config["redirect"] == 1){

				$videocnt = $_POST['videocnt'];
				unset($_POST['videocnt']);
			}



			$req = $_POST;

			$fname	 =	$_FILES['videoFile']['name'];
			$ftype	 =	$_FILES['videoFile']['type'];
			$fsize	 =	$_FILES['videoFile']['size'];
			$ferror	 =	$_FILES['videoFile']['error'];
			$tmpname =	$_FILES['videoFile']['tmp_name'];
			#######added by Jinson####################
			########this is to convert bytes(Maximum upload file size) to MB that set in the congig table.
			#########on 18 th April,2008
			if ($framework->config['fileupload_maxsize']>0)
			{
				$fs = round($framework->config['fileupload_maxsize'] / 1048576 * 100) / 100 ;
				if($framework->config['fileupload_maxsize']<$_FILES['videoFile']['size'])
				{
					$size_chk =1;
				}	
			}
			else 
			{
				$size_chk =0;
			}
			###########
			if($size_chk == 0)
			{

				$fileext=$album->file_extension($fname);

				$dir=SITE_PATH."/modules/album/video/";

				if(!$ferror){
					if(in_array(strtolower($fileext), array('mov', 'wmv', 'mpg','mpeg', 'avi', '3gp', 'dat', 'asx')))
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
						////////////for link54
						if($framework->config["video_flv"] == "1")
						{
							$timevar=time();
							shell_exec("ffmpeg -i {$tmpname} -ab 56 -ar 22050 -f flv{$_3gp_fix} {$dir}{$id}{$timevar}.flv");
							chmod("${dir}{$id}{$timevar}.flv",0777);
							
							$mov = new ffmpeg_movie("${dir}{$id}{$timevar}.flv");
						}
						else
						{
							shell_exec("ffmpeg -i {$tmpname} -ab 56 -ar 22050 -f flv{$_3gp_fix} {$dir}{$id}.flv");
							chmod("${dir}{$id}.flv",0777);
							
							shell_exec("ffmpeg -ss 1 -t 2 -i {$tmpname} -an -f flv{$_3gp_fix} -s 100x100 {$dir}{$id}_small.flv");
							chmod("${dir}{$id}_small.flv",0777);
							$mov = new ffmpeg_movie("${dir}{$id}.flv");
						}

						$frame = $mov->getFrame(10);


						
						if($frame) {

							if ($framework->config["video_thumb"]){

								$thumb_size  = explode(",",$framework->config["video_thumb"]);
								$thumb_width = $thumb_size[0];
								$thum_height = $thumb_size[1];


							}else{

								$thumb_width = 110;
								$thum_height = 80;
							}

							$frame->resize($thumb_width, $thum_height);
							$image = $frame->toGDImage();
							imagejpeg($image, "${dir}thumb/{$id}.jpg", 100);
						}


						###  Portion to send mail to subscribers................................................
						$mail_header = array();
						$mail_header['from'] 	    = 	$framework->config['admin_email'];
						$toemails					=	$music->getSubscriberEmails($_SESSION["memberid"],"video");
						for($i=0;$i<sizeof($toemails);$i++){

							$mail						=	$toemails[$i]["email"];
							$udet						=	$objUser->getUserdetails($_SESSION["memberid"]);
							$mail_header["to"]          = 	$mail;
							$dynamic_vars               = 	array();
							$dynamic_vars["USERNAME"]  	= 	$udet["username"];
							$dynamic_vars["SITE"] 		=	$framework->config['site_name'];
							$dynamic_vars["LINK"]       = 	"<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"album", "pg"=>"video"), "act=details&video_id=$id")."\">Get Your new Video</a>";
							$email->send("send_to_subscribers",$mail_header,$dynamic_vars);
						}


						if($global["show_property"] == 1)//realestate tube..... Set the default video
						{
							$rs = $album->propertyList("album_video","video",0,$_REQUEST["propid"],$_SESSION["memberid"]);

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

							if($framework->config["redirect"] == 1){/* Created:Afsal Ismail :For:Bayard project */

								//if($videocnt > 0 && $req["act"] !="editiinfo")
								//redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_view&propid={$_REQUEST['propid']}&flyer_id={$_REQUEST['flyer_id']}"));
								//else
								redirect(makeLink(array("mod"=>"album", "pg"=>"video"), "act=upload&propid={$_REQUEST['propid']}&flyer_id={$_REQUEST['flyer_id']}"));
							}
							else
							{
								redirect(makeLink(array("mod"=>"album", "pg"=>"album"), "act=propdView&propid={$_REQUEST['propid']}&view=edit"));
							}
						}
						else
						{
							if ($framework->config['mymedia_redirect']==1)
							{
								redirect(makeLink(array("mod"=>"album", "pg"=>"video"), "act=details_prf&video_id=$id"));
							}
							else 
							{
								redirect(makeLink(array("mod"=>"album", "pg"=>"video"), "act=details&video_id=$id"));
							}	
						}
					}
					else
					{
						$message="You have to select one movie file. We support MOV, WMV, MPG,MPEG, 3GP, DAT, ASX and AVI files.";
					}
				} else {
					$message = $fileError[$ferror];
				}

			}
			else{
				$message="Maximun File Upload Size is ".$fs." MB";
			}
			setMessage($message);
		}

		if($global["show_property"] == 1)//realestate tube
		{
			$param = "mod=".$mod."&pg=".$pg."&act=".$_REQUEST['act']."&propid=".$_REQUEST['propid'];
			list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 4,$param, 'ARRAY_A','id:DESC',"album_video","video",'',$_REQUEST['propid'],$_SESSION['memberid']);
			$framework->tpl->assign("VIDEO_DETAILS",$rs);
			$framework->tpl->assign("VIDEO_CNT",count($rs));
			$framework->tpl->assign("NUM_PAD",$numpad);
			$rsPropDeta = $album->getAlbumByFields('user_id,id',$_SESSION["memberid"].",".$_REQUEST["propid"]);


			$framework->tpl->assign("PROP_DETAILS",$rsPropDeta);

		}
		$publish=$objSearch->basicAlbumInfo($_REQUEST["propid"]);
		//print_r($publish);
		$framework->tpl->assign("PUBLISH",$publish);

		/*
		* Created:Afsal
		* Proj:Bayard
		* Created :29-11-2007
		*/
		if($framework->config["tab_show"] == "1")
		$framework->tpl->assign("STEPS_HTML", $StepsHTML);

		$framework->tpl->assign("SECTION_LIST", $album->albumSectionList());
		if ($framework->config['profile_inner']=="Y")
		{
			$framework->tpl->assign("PROFILE_HEAD",$MOD_VARIABLES['MOD_HEADS']['HD_VIDEO_UPLOAD']);
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_main.tpl");
			$framework->tpl->assign("profile_tpl",SITE_PATH."/modules/album/tpl/upload_video.tpl");
		}
		else
		{
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/upload_video.tpl");
		}

		break;
	case "editvideo":
		checkLogin();
		/*
		* Created:Afsal
		* Proj:Bayard
		Created :29-11-2007
		*/
		if($framework->config["tab_show"] == "1")
		$StepsHTML		=	$flyer->getPropertyCreatonStepsHTML(6, $_REQUEST['flyer_id'],$_REQUEST['propid']);

		/*
		Real Estate tube
		*/
		if(count($_POST))
		{
			$video->updateVideoInfo($_REQUEST);

			if($_REQUEST['default_vdo'] > 0){

				$album->updateAlbum(array("default_vdo" => $_REQUEST['vid']),$_REQUEST['propid']);
			}

			setMessage("Video details have been updated",MSG_SUCCESS);
			redirect(makeLink(array("mod"=>"album", "pg"=>"video"), "act=upload&propid={$_REQUEST['propid']}&flyer_id={$_REQUEST['flyer_id']}"));
		}

		$rsPropDeta = $album->getAlbumByFields('user_id,id',$_SESSION["memberid"].",".$_REQUEST["propid"]);
		list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 10,'', 'ARRAY_A','',"album_video","video",'',$_REQUEST['propid'],$_SESSION['memberid']);
		$_REQUEST=$_REQUEST +  $video->getVideoDetails($_REQUEST['vid']);
		$framework->tpl->assign("PROP_DETAILS",$rsPropDeta);
		$framework->tpl->assign("VIDEO_DETAILS",$rs);

		/*
		* Created:Afsal
		* Proj:Bayard
		* Created :13-12-2007
		*/
		if($framework->config["tab_show"] == "1")
		$framework->tpl->assign("STEPS_HTML", $StepsHTML);

		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/upload_video.tpl");
		break;
	case "delvdo":
		checkLogin();

		$album->mediaDelete($_REQUEST["vid"],"M1");

		if($_REQUEST['propid'] > 0 && $_REQUEST["vid"] >0){
			$vid = $album->resetDefaultThumb($_REQUEST['propid'],$_REQUEST["vid"],"video");

			if($vid > 0){

				if($vid && $_REQUEST['propid'])
				$album->updateAlbum(array("default_vdo" => $vid),$_REQUEST['propid']);
			}

			/*if no record in album_videos set default_mg in custom table set as null */

			$unset_video = $album->unsetDefaultThumb("video",$_REQUEST['propid']);

			if($unset_video == "0"){
				$album->updateAlbum(array("default_vdo" => ""),$_REQUEST['propid']);
			}
		}

		setMessage("Your video has been removed",MSG_SUCCESS);

		//if($_REQUEST['from'] == "myalbum")
		//redirect(makeLink(array("mod"=>"album", "pg"=>"album"), "act=myalbum&crt=M1"));
		//else

		if($framework->config["redirect"] == "1")/* Created:Afsal-Date:13-12-2007-Bayard */
		$extQrStr = "&flyer_id=".$_REQUEST['flyer_id'];

		redirect(makeLink(array("mod"=>"album", "pg"=>"video"), "act=upload&propid={$_REQUEST['propid']}$extQrStr"));

		break;
	case "defaultvdo":
		checkLogin();
		if($_REQUEST['vid'] && $_REQUEST['propid'])
		$album->updateAlbum(array("default_vdo" => $_REQUEST['vid']),$_REQUEST['propid'],$_SESSION["memberid"]);

		if($framework->config["redirect"] == "1")/* Created:Afsal-Date:13-12-2007-Bayard */
		$extQrStr = "&flyer_id=".$_REQUEST['flyer_id'];

		redirect(makeLink(array("mod"=>"album", "pg"=>"video"), "act=upload&propid={$_REQUEST['propid']}$extQrStr"));
		break;
	case "videolist":

		$par = "mod=".$_REQUEST['mod']."&pg=".$_REQUEST['pg']."&act=".$_REQUEST['act'];
		list($rs,$numpad) = $album->propertySearch($_REQUEST['pageNo'],15,$par,ARRAY_A,'','default_vdo','0',$_REQUEST['view'],'>');
		$framework->tpl->assign("VIDEO_LIST",$rs);
		$framework->tpl->assign("NUMPAD",$numpad);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/video_list.tpl");

		break;
	case "video_grid":
		include_once(SITE_PATH."/includes/flashPlayer/include.php");
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/ad_video.tpl");
		$framework->tpl->display($global['curr_tpl']."/inner_login.tpl");
		exit;
		break;	
	case "video_grid_xml":
		$cat_name=$_REQUEST['cat_name'];
		$state=$_REQUEST['state'];
		$category=$categ->getCategoryId($cat_name);
		$cat_id=$category[0]->category_id;
		echo $album->generateGridXML($cat_id,$state);
		exit;	
		break;
	case "video_load":
		$vid=$_REQUEST['video_id'];
		$phdet = $video->getVideoDetails($vid);
		$medet=$objUser->getUsernameDetails($phdet["username"]);
		$phdet["nick_name"]=$medet["nick_name"];
		$phdet["mem_type"]=$medet["mem_type"];
		$framework->tpl->assign("PHDET",$phdet);
		include_once(SITE_PATH."/includes/flashPlayer/include.php");		
		$framework->tpl->display(SITE_PATH."/modules/album/tpl/video_load.tpl");
		exit;
		
		break;		
	case "rnd_video";
	
		if ($_REQUEST["rnd_uid"])
		{
			$rand_mems = $_SESSION["rand_mems"];
		}
		else
		{
			($_REQUEST["limit_mem"]) ? $limit_mem = $_REQUEST["limit_mem"] : $limit_mem = "0,9";
			
			if ($_REQUEST["limit_mem"])
			{
				$rand_mems = $_SESSION["rand_mems"];
			}
			else
			{
				$rand_mems = $video->getVideosListRandom();
				$_SESSION["rand_mems"] = $rand_mems;

			}
			
			$framework->tpl->assign("TOT_USERS",count($rand_mems));

			$arr = explode(",",$limit_mem);
			
			($arr[0]==0)? $start=$arr[0]: $start= $arr[0]+1;
			
			if ($_REQUEST["limit_mem"])
			{
				$limit_val =start;
			}
			else
			{
				$limit_val = 9;

			}

			if ($start>0)
			{
				$framework->tpl->assign("PRE_CNT",$start);
			}
			else
			{
				$framework->tpl->assign("PRE_CNT",0);
			}

			$new_mem = array();
			for ($i=$start;$i<$start+9;$i++)
			{
				if ($rand_mems[$i])
				{
					$new_mem[] = $rand_mems[$i];
					
				}
			}
		}
		
		$framework->tpl->assign("USER_IMG",$new_mem);
		$framework->tpl->assign("USER_COUNT",count($new_mem));
		$bound = count($rand_mems)-$limit_val;
		$framework->tpl->assign("BOUND",$bound);
		$framework->tpl->display(SITE_PATH."/modules/album/tpl/rnd_video.tpl");
		exit;
		break;	


}

$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>