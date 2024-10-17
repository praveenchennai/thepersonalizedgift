<?php

	session_start();
	include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
	include_once(FRAMEWORK_PATH."/modules/album/lib/class.photo.php");
	include_once(FRAMEWORK_PATH."/modules/album/lib/class.video.php");
	include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
	include_once(FRAMEWORK_PATH."/modules/album/lib/class.music.php");
	$getId=$_SESSION["memberid"];
	
	$objUser=new User();
	$album= new Album();
	$music=new Music();
	$objPhoto=new Photo();
	$video=new Video();
	
	$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
	$framework->tpl->assign("AGE_LIST",$objUser->getAgeList());
	switch($_REQUEST['act']) 
	{
		case "user":
			$framework->tpl->assign("TITLE_HEAD", "User Search Result");
			$cat_combo = $objUser->getCategoryCombo($_REQUEST["mod"]);
			$framework->tpl->assign("CAT_COMBO",$cat_combo);
			$framework->tpl->assign("NOT_GENR","NO");
			$_REQUEST['type']=1;
			$param="mod=$mod&pg=$pg&act=".$_REQUEST['act']."&criteria=".$_REQUEST["criteria"]."&type=".$_REQUEST['type'];
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
					}else{
						unset($_REQUEST["gender"]);
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
					}else{
						unset($_REQUEST["gender"]);
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
		//	print_r($_REQUEST);exit;
			list($rs, $numpad) = $objUser->profileList($_REQUEST['pageNo'], 10, $param, OBJECT, $_REQUEST['orderBy'],$_REQUEST["txtuname"],$_SESSION["memberid"],'','','',$searchfield,$searchvalue,'',$crt);
//	
			$framework->tpl->assign("PROFILE_LIST", $rs);
			$framework->tpl->assign("PROFILE_NUMPAD", $numpad);
			$framework->tpl->assign("SEL", "user");
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/browse.tpl");
			$framework->tpl->assign("sub_tpl",SITE_PATH."/modules/member/tpl/profile_search.tpl");
		break;
	case "artist":
			$cat_combo = $objUser->getCategoryCombo($_REQUEST["mod"]);
			$framework->tpl->assign("CAT_COMBO",$cat_combo);
			$framework->tpl->assign("TITLE_HEAD", "Artist Search Result");
			$_REQUEST['type']=2;
			$param="mod=$mod&pg=$pg&act=".$_REQUEST['act']."&criteria=".$_REQUEST["criteria"]."&type=".$_REQUEST['type'];
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
					}else{
						unset($_REQUEST["gender"]);
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
					}else{
						unset($_REQUEST["gender"]);
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
			//print_r($_REQUEST);exit;
			list($rs, $numpad) = $objUser->profileList($_REQUEST['pageNo'], 10, $param, OBJECT, $_REQUEST['orderBy'],$_REQUEST["txtuname"],$_SESSION["memberid"],'','','',$searchfield,$searchvalue,'',$crt);
//	print_r($rs);exit;
			$framework->tpl->assign("PROFILE_LIST", $rs);
			$framework->tpl->assign("PROFILE_NUMPAD", $numpad);
			$framework->tpl->assign("SEL", "artist");
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/browse.tpl");
			$framework->tpl->assign("sub_tpl",SITE_PATH."/modules/member/tpl/profile_search.tpl");
	
		break;
	case "tracks":
			$cat_combo = $objUser->getCategoryCombo("album");
			$framework->tpl->assign("CAT_COMBO",$cat_combo);
			$framework->tpl->assign("TITLE_HEAD", "Track Search Result");
			if ($_REQUEST["cat_id"])
			{
				$param="mod={$mod}&pg={$pg}&act=tracks&filter=".$_REQUEST['filter']."&txtSearch=".$_REQUEST['txtSearch']."&cat_id=".$_REQUEST['cat_id'];
				//$catname=$objUser->getCatName($_REQUEST["cat_id"]);
				//$framework->tpl->assign("FILTER",$_REQUEST["cat_id"]);
				//$framework->tpl->assign("PH_HEADER", $catname["cat_name"]);
				list($rs, $numpad) = $music->musicList($_REQUEST['pageNo'], 5,$param, OBJECT, "id desc", $_REQUEST["cat_id"], $_REQUEST['txtSearch'],$_REQUEST['type']);
			}
			elseif ($_REQUEST["filter"])
			{
				if ($_REQUEST["filter"]=="recent")
				{
					$pheader="Most Recent";
					$field="postdate desc";
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
				list($rs, $numpad) = $music->musicList($_REQUEST['pageNo'], 5, "mod={$mod}&pg={$pg}&act=tracks&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}", OBJECT, $field,0,$_REQUEST['txtSearch'],$_REQUEST['type']);
			}
			else
			{
				$framework->tpl->assign("PH_HEADER", "All Musics");
				list($rs, $numpad) = $music->musicList($_REQUEST['pageNo'], 5, "mod={$mod}&pg={$pg}&act=tracks&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}", OBJECT, 'id desc',0,$_REQUEST['txtSearch'],$_REQUEST['type']);
			}
			for ($i=0;$i<sizeof($rs);$i++)	
			{
				$medet=$objUser->getUsernameDetails($rs[$i]->username);
				$rs[$i]->play_duration = $framework->config["other_song_duration"];
				$rs[$i]->nick_name=$medet["nick_name"];
							//print_r($mdet);exit;

			}
			//print_r($rs);exit;
			$_SESSION["xml_arr"] = $rs;
			$framework->tpl->assign("MUSIC_LIST", $rs);
			$framework->tpl->assign("MUSIC_NUMPAD", $numpad);
			$framework->tpl->assign("RIGT_TPL", SITE_PATH."/modules/album/tpl/right_top_btn.tpl");
			$framework->tpl->assign("sub_tpl", SITE_PATH."/modules/album/tpl/music_list_browse.tpl");
			$framework->tpl->assign("SEL", "tracks");
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/browse.tpl");
			if($global["searchstyle"] == "2") {//for p1musicbox.
				$framework->tpl->display($global['curr_tpl']."/inner2.tpl");
				exit;
			}

		break;
	case "photo":
			$framework->tpl->assign("TITLE_HEAD", "Photo Search Result");
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
				list($rs, $numpad) = $objPhoto->photoList($_REQUEST['pageNo'], 5, $par, OBJECT, "id desc",$_REQUEST["cat_id"],$stxt);				
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
			{
				//print_r($par);exit;
				$framework->tpl->assign("PH_HEADER", "All Photos");
				if($_REQUEST["user_id"]){
					//checkLogin();
					list($rs, $numpad) = $objPhoto->photoList($_REQUEST['pageNo'], 5,$par, OBJECT, 'id desc',0,$stxt);
				}else{
					list($rs, $numpad) = $objPhoto->photoList($_REQUEST['pageNo'], 5,$par, OBJECT, 'id desc',0,$stxt);
				}
			}	
			for ($i=0;$i<sizeof($rs);$i++)	
			{
				$medet=$objUser->getUsernameDetails($rs[$i]->username);
				$rs[$i]->nick_name=$medet["nick_name"];
							//print_r($mdet);exit;

			}
			$framework->tpl->assign("PHOTO_LIST", $rs);
			$framework->tpl->assign("PHOTO_NUMPAD", $numpad);
			$framework->tpl->assign("sub_tpl", SITE_PATH."/modules/album/tpl/photo_list_browse.tpl");		
			$framework->tpl->assign("SEL", "photo");
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/browse.tpl");
	
		break;	
	case "video":
			$framework->tpl->assign("TITLE_HEAD", "Video Search Result");
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
				$catname=$user->getCatName($_REQUEST["cat_id"]);
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
				list($rs, $numpad) = $video->videoList($_REQUEST['pageNo'], 5, "mod={$mod}&pg={$pg}&act=list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}", OBJECT, $field,0,$_REQUEST['txtSearch']);
				
				/* new */ 
				if($rs[0]->cat_id > 0)
				list($rscat, $numpad) = $video->videoList($_REQUEST['pageNo'], 5, "mod={$mod}&pg={$pg}&act=list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}", OBJECT, $field,$rs[0]->cat_id,$_REQUEST['txtSearch']);
				
				if($_REQUEST['video_id'] >0 )
				{
					$videodet=$video->getVideoDetails($_REQUEST['video_id']);
				}
				else if($rs[0]->cat_id > 0)
				{
					$videodet=$video->getVideoDetails($rs[0]->id);
				}
				if($memberID){
					$sub=$album->chkSubscribe($memberID,$videodet["user_id"],"video");
					$framework->tpl->assign("SUBS",$sub);
				}
				$framework->tpl->assign("SING_VIDEO_DET",$videodet);
	
			}
			else
			{
				if($_REQUEST['view'] == "all")
				$param = "mod={$mod}&pg={$pg}&act=list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}&view=all";
				else
				$param = "mod={$mod}&pg={$pg}&act=list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}";
				
				$framework->tpl->assign("PH_HEADER", "All Movies");
				list($rs, $numpad) = $video->videoList($_REQUEST['pageNo'],15,$param, OBJECT, 'id desc',0,$_REQUEST['txtSearch']);
				
			}
			if(count($rs) > 0)
			{
				foreach($rs as $row)
				{
				
				$dur[] = implode(":",$album->secs2hms($row->length));// calculate duration video clips
				}
			}
			
			if($_REQUEST["rate"])
			{
				checkLogin();
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
					redirect(makeLink(array("mod"=>"album", "pg"=>"video"),"act=list&filter={$_REQUEST['recent']}"));
				}
			}
			$framework->tpl->assign("DURATION",$dur);
				for ($i=0;$i<sizeof($rs);$i++)	
			{
				$medet=$objUser->getUsernameDetails($rs[$i]->username);
				$rs[$i]->nick_name=$medet["nick_name"];
							//print_r($mdet);exit;

			}
			$framework->tpl->assign("VIDEO_LIST", $rs);
			$framework->tpl->assign("VIDEO_LIST_CAT", $rscat);
			if($rs[0]->cat_id > 0)
			$rscat = $user->getCatName($rs[0]->cat_id) ;
			$framework->tpl->assign("VIDEO_NUMPAD", $numpad);
			$framework->tpl->assign("CAT_NAME",$rscat["category_name"]);
			$framework->tpl->assign("RIGT_TPL", SITE_PATH."/modules/album/tpl/right_top_btn.tpl");
			$framework->tpl->assign("sub_tpl", SITE_PATH."/modules/album/tpl/video_list_browse.tpl");
			$framework->tpl->assign("SEL", "video");
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/browse.tpl");
	
		break;
	}
	$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>
