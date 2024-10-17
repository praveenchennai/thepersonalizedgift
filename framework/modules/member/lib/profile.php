<?php
session_start();
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.music.php");
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.photo.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.video.php");
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.broker.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.property.php");

$video		 =	new Video();
$objPhoto	 =  new Photo();
$objUser     =	new User();
$objAlbum    =	new Album();
$objMusic    =	new Music();
$objCategory =	new Category();
$objCms = new Cms();
$flyer		=	new	Flyer();
$broker     =  new Broker();
$property   = new Property();

if($_REQUEST['recentid']!="")
{
	$_SESSION['chps1']=1;
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

if($_REQUEST["pid"]!=""){
	$framework->tpl->assign("pid", $_REQUEST["pid"]);
}

if ($_REQUEST['prf_target_id']>0)
{
	$_REQUEST['uid'] = $_REQUEST['prf_target_id'];
}

if($_REQUEST["uid"])
{
	$getId=$_REQUEST["uid"];
	if($getId==$_SESSION["memberid"])
	{
		$framework->tpl->assign("OWN","Y");
		$framework->tpl->assign("LEFTBOTTOM",'myprofile');
	}
	else
	{
		$framework->tpl->assign("OWN","N");
	}
}
else
{
	if ($_REQUEST['prf_name'])
	{
		$name = $_REQUEST['prf_name'];
		$rs = $objUser->profileCheck($name);
		if (count($rs)>0)
		{
			$getId = $rs[0]['id'];
			$_REQUEST['uid'] = $getId;
		}
	}
	else
	{
		checkLogin();
		$getId=$_SESSION["memberid"];
		$framework->tpl->assign("OWN","Y");
	}
}


$framework->tpl->assign("MS_COUNT",$objAlbum->getMediaCount($getId,'album_music','public'));
if($global['show_private']=='Y'){
	list($rs, $numpad) = $objAlbum->mediaList($_REQUEST['pageNo'],10000,$par, OBJECT, 'title:ASC','album_video','video','','',$getId);
	$cnt=0;
	for ($i=0;$i<sizeof($rs);$i++)
	{
		if($rs[$i]->privacy=='private'){
			if($rs[$i]->friends_can_see=='Y'){
				if($objUser->isFriends($getId,$_SESSION["memberid"])==true){
					$cnt++;
				}
			}
			if($getId==$_SESSION["memberid"]){
				$cnt++;
			}
		}else{
			$cnt++;
		}
	}
	$framework->tpl->assign("MV_COUNT",$cnt);
}else{
	$framework->tpl->assign("MV_COUNT",$objAlbum->getMediaCount($getId,'album_video','public'));
}
if($global['show_private']=='Y'){
	list($rs, $numpad) = $objAlbum->mediaList($_REQUEST['pageNo'],10000,$par, OBJECT, 'title:ASC','album_photos','photo','','',$getId);
	$cnp=0;
	for ($i=0;$i<sizeof($rs);$i++)
	{
		if($rs[$i]->privacy=='private'){
			if($rs[$i]->friends_can_see=='Y'){
				if($objUser->isFriends($getId,$_SESSION["memberid"])==true){
					$cnp++;
				}
			}
			if($getId==$_SESSION["memberid"]){
				$cnp++;
			}
		}else{
			$cnp++;
		}
	}
	$framework->tpl->assign("PH_COUNT",$cnp);
}else{
	$framework->tpl->assign("PH_COUNT",$objAlbum->getMediaCount($getId,'album_photos','public'));
}
$framework->tpl->assign("GR_COUNT",$objUser->getGroupCount($getId));


$userDet=$objUser->getUserdetails($getId);
$datevar=$userDet['joindate'];
/////////////////////////////

$det=$userDet;


//Ratheesh for display D.O.B
if(array_key_exists("date_format",$global))
{
	if($global["date_format"] !=""){
		list($y,$m,$d)=explode("-",$det['dob']);
		$dt_brth=date("M-d-Y", mktime(0, 0, 0, $m, $d, $y));
		$framework->tpl->assign("PRF_DT_BRTH",$dt_brth);
	}
}//
$dt=date("Y");
$tpm=strtotime($det['dob']);
$dym=getdate($tpm);
$agm=$dt-$dym["year"];
$framework->tpl->assign("AGE",$agm);
//print_r($agm);exit;
$cntry = $objUser->getCountryName($det["country"]);
$framework->tpl->assign("CNTRY",$cntry["country_name"]);
//print_r($det);
$framework->tpl->assign("PRF",$det);


///////////////////////
list($y, $m, $d) = split('[/.-]', $userDet[dob]);
$userDet[y] = $y;
$userDet[m] = $m;
$userDet[d] = $d;
$maxy=date("Y");
$maxy=$maxy+1;
for($i=1902;$i<$maxy;$i++){
	$yearlist[]=$i;
}
//$framework->tpl->assign("basic",1);
$framework->tpl->assign("year_list",$yearlist);
$framework->tpl->assign("USERINFO", $userDet);//getting details from member_master
$framework->tpl->assign("FR_COUNT",$objUser->getContactCount($userDet["username"]));
if($global["show_friend_list_only"]==1){
	if($_SESSION["memberid"]==$getId){
		$framework->tpl->assign("FRIEND_COUNT",$objUser->getFriendsCount($getId,"Yes"));
	}else{
		$framework->tpl->assign("FRIEND_COUNT",$objUser->getFriendsCount($getId));
	}
}

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

switch($_REQUEST["act"])
{


	case "sessionlist":

		list($rs, $numpad,$cnt_rs, $limitList) = $objUser->chatSessList($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&mem_type=".$_REQUEST["mem_type"]."&fId=$fId&sId=$sId", OBJECT, $orderBy,$_REQUEST["txtsearch"],'chatposition','','!=');

		$framework->tpl->assign("SESS_LIST",$rs);
		$framework->tpl->display(SITE_PATH."/modules/member/tpl/sessionlist.tpl");
		exit;

	case "profile_page":
		$framework->tpl->assign("nowdate",date("Y-m-d"));
		$framework->tpl->assign("TITLE_HEAD","My Profile Details");
		$framework->tpl->assign("MEM_TYPE",$objUser->loadMemTypeCmb("0,3"));

		checkLogin();
		if ($userDet)
		{
			if ($userDet['genre'])
			{
				$userDet['genre'] = explode(",",$userDet['genre']);
			}
			$_REQUEST+= $userDet;
		}
		$cat_combo = $objUser->getCategoryCombo($_REQUEST["mod"]);
		$framework->tpl->assign("CAT_COMBO",$cat_combo);

		if ($userDet["profile_flg"]!="Y")
		{
			if ($framework->config['profile_alert']!="N")
			{
				setMessage("Please Complete your Profile Details",MSG_INFO);
			}
		}
		//setMessage("Complete your Profile details");

		//Fetching details of profiles to a single array

		if ($framework->config['profile_tables'])
		{
			$tables = explode(",",$framework->config['profile_tables']);

			$len_tables = sizeof($tables);
			$prf_details = array();
			for ($i=0;$i<$len_tables;$i++)
			{
				$fetch_prf = $objUser->getPrfDetails($getId,$tables[$i]);
				if ($i==0)
				{
					if ($fetch_prf)
					{
						$prf_details = $fetch_prf;
					}
				}
				else
				{
					if ($fetch_prf)
					{
						$prf_details = array_merge($prf_details,$fetch_prf);
					}
				}
				unset($prf_details["id"]);
			}

			$framework->tpl->assign("PRF_INFO",$prf_details);
		}

		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			if ($_POST["genre"])
			{
				$_POST["genre"] = implode(",",$_POST["genre"]);
			}
			$fname=basename($_FILES['image']['name']);
			$proceed = 1;
			if ($fname)
			{
				list($width,$height) = getimagesize($_FILES['image']['tmp_name']);


				if ($framework->config["member_image_min"])
				{
					$img_dimensions = explode(",",$framework->config["member_image_min"]);


					if ( ($width<$img_dimensions[0]) || ($height<$img_dimensions[1]))
					{
						$proceed = 0;
						setMessage("Image size is too small (Mimimum size is {$img_dimensions[0]} X {$img_dimensions[1]})");
					}
				}

				if ($proceed==1)
				{
					$dir   = SITE_PATH."/modules/member/images/userpics/";
					$thumbdir = $dir."thumb/";
					$id    = $_SESSION["memberid"];

					uploadImage($_FILES["image"],$dir,$id.".jpg",1);
					chmod($dir."$id.jpg",0777);
					$_POST["image"] = "Y";

					if ($framework->config["member_image_thumb1"])
					{
						$thumb_size   = explode(",",$framework->config["member_image_thumb1"]);
						$thumb_width  = $thumb_size[0];
						$thumb_height = $thumb_size[1];
					}
					else
					{

						if($global["inner_change_reg"]=="yes")
						{
							$thumb_width  = 70;
							$thumb_height = 70;

						}
						else
						{
							$thumb_width  = 100;
							$thumb_height = 100;
						}
					}

					thumbnail($dir,$thumbdir,"$id.jpg",$thumb_width,$thumb_height,"","$id.jpg",0);
					chmod($thumbdir."$id.jpg",0777);

					if ($framework->config["member_image_thumb2"])
					{
						$thumb_size   = explode(",",$framework->config["member_image_thumb2"]);
						$thumb_width  = $thumb_size[0];
						$thumb_height = $thumb_size[1];

						thumbnail($dir,$thumbdir,"$id.jpg",$thumb_width,$thumb_height,"",$id."_thumb2.jpg",0);
						chmod($thumbdir.$id."_thumb2.jpg",0777);

					}
				}
			}
			if ($proceed==1)
			{
				unset($_POST["btn_save"]);
				$_POST["id"] = $getId;
				$_POST["profile_flg"] = "Y";
				$objUser->setArrData($_POST);
				$objUser->update();
				redirect(makeLink(array('mod'=>member,'pg'=>home)));
			}


		}

		if ($framework->config['profile_inner']=="Y")
		{
			$framework->tpl->assign("PROFILE_HEAD","My Profile");
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_main.tpl");
			$framework->tpl->assign("profile_tpl",SITE_PATH."/modules/member/tpl/profile_about.tpl");
		}
		else if($global["ladaloh_set"]=="Y")
		{
			$show				=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "5";
			list($res, $numpad, $cnt, $limitList)=$objUser->getProfileQuestionsAjax($_REQUEST['pageNo'], 5, $par, ARRAY_A, $_REQUEST['orderBy'],0,0,0,0,0);
			$startpoint = $pageNo+1;
			$endpoint = $pageNo+$show;
			if ($cnt < $endpoint)
			$endpoint=$cnt; 
			$framework->tpl->assign("PROFILE_QUESTION",$res);
			$framework->tpl->assign("PROFILE_QUESTION_NUMPAD",$numpad);
			$framework->tpl->assign("STARTPOINT",$startpoint);
			$framework->tpl->assign("ENDPOINT",$endpoint);
			$framework->tpl->assign("TOTAL",$cnt);
			$framework->tpl->assign("PROFILE_QUESTION_COUNT",$global['profile_question_count']);
			$framework->tpl->assign("MEMBER_QUESTION",$objUser->getProfileQuestionsByUserId($_SESSION["memberid"]));
			
			$show				=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "3";
			$comments_PageNo		=$_REQUEST['pageNo'];
			
			list($rs1, $numpad1, $cnt1, $limitList1)=$objUser->getProfileComments($_REQUEST['pageNo'], 3, $par, ARRAY_A, $_REQUEST['orderBy'],$_SESSION['memberid'],'profile',2);
			$comments_startpoint = $comments_PageNo+1;
			$comments_endpoint = $comments_PageNo+$show;
			if ($cnt1 < $comment_endpoint)
			$comment_endpoint=$cnt1; 
			$framework->tpl->assign("PROFILE_COMMENTS",$rs1);
			$framework->tpl->assign("PROFILE_COMMENTS_NUMPAD",$numpad1);
			$framework->tpl->assign("COMMENT_STARTPOINT",$comments_startpoint);
			$framework->tpl->assign("COMMENT_ENDPOINT",$comments_endpoint);
			$framework->tpl->assign("COMMENT_TOTAL",$cnt1);
			
			$show				=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "3";
			$updates_PageNo		= $_REQUEST['pageNo'];
			
			list($rs2, $numpad2, $cnt2, $limitList2)=$objUser->getProfileComments($_REQUEST['pageNo'], 6, $par, ARRAY_A, $_REQUEST['orderBy'],$_SESSION['memberid'],'updates',3);
			$framework->tpl->assign("PROFILE_UPDATES",$rs2);
			$framework->tpl->assign("PROFILE_UPDATES_NUMPAD",$numpad2);
			
			
			
			
			if($userDet['mem_type']==2)
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/my_home.tpl");
			else
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile.tpl");
		}
		else
		{
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_about.tpl");
		}

		/*if($global['profile_redirection']=='Y')
		{
		$framework->tpl->display($global['curr_tpl']."/inner_profile.tpl");
		exit;
		}*/
		break;
	case "basic":
		$maxy=date("Y");
		$maxy=$maxy+1;
		for($i=1900;$i<$maxy;$i++){
			$yearlist[]=$i;
		}
		//$framework->tpl->assign("basic",1);
		$framework->tpl->assign("year_list",$yearlist);
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{


			unset($_POST["image_x"],$_POST["image_y"]);

			if(!$_POST["dirflg"])
			{
				$_POST["dirflg"]=0;
			}
			if(!$_POST["clb_flg"])
			{
				$_POST["clb_flg"]=0;
			}
			$_POST["id"]=$getId;

			$_POST["dob"]=$_POST["year"]."-".$_POST["month"]."-".$_POST["day"];
			$_REQUEST['dob']=$_POST["dob"];
			unset($_POST["year"],$_POST["month"],$_POST["day"]);
			if($global["check_fields_hide"]=="1"){
				if(! $_POST["checkfields_show"]){
					$_POST["checkfields_show"]="N";
				}
			}
			//print_r($_POST);exit;
			$objUser->setArrData($_POST);

			$num=1;
			if(!$objUser->update($getId,$num))
			{
				$framework->tpl->assign("MESSAGE", $objUser->getErr());
				$framework->tpl->assign("USERINFO", $_POST);

			}
			else
			{

				redirect(makeLink(array('mod'=>member,'pg'=>profile)));
			}
		}



		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_basic.tpl");
		break;
	case "sheet":
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			unset($_POST["image_x"],$_POST["image_y"]);
			$_POST["id"]=$getId;
			//	print_r($_POST);exit;
			$objUser->setArrData($_POST);
			if(!$objUser->update($getId))
			{
				$framework->tpl->assign("MESSAGE", $objUser->getErr());
				$framework->tpl->assign("USERINFO", $_POST);

			}
			else
			{

				redirect(makeLink(array('mod'=>member,'pg'=>profile)));
			}


		}

		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_sheet.tpl");
		break;
	case "wall":
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			unset($_POST["image_x"],$_POST["image_y"]);
			$_POST["id"]=$getId;
			$objUser->setArrData($_POST);
			if(!$objUser->update($getId))
			{
				$framework->tpl->assign("MESSAGE", $objUser->getErr());
				$framework->tpl->assign("USERINFO", $_POST);

			}
			else
			{

				redirect(makeLink(array('mod'=>member,'pg'=>profile)));
			}


		}

		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_wall.tpl");
		break;
	case "about":

		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			unset($_POST["image_x"],$_POST["image_y"],$_POST["image"]);
			//print_r($_POST);exit;
			$objUser->setArrData($_POST);
			$framework->tpl->assign("PRF_ABOUT",$_POST);
			if(!$objUser->addEditProfile($getId,'profile_about'))
			{
				$framework->tpl->assign("MESSAGE", $objUser->getErr());
			}
			else
			{
				redirect(makeLink(array('mod'=>member,'pg'=>profile)));
			}


		}

		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_about.tpl");
		break;
	case "contact":
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			unset($_POST["image_x"],$_POST["image_y"]);
			$objUser->setArrData($_POST);
			$framework->tpl->assign("PRF_CONTACT",$_POST);
			if(!$objUser->addEditProfile($getId,'profile_contact'))
			{
				$framework->tpl->assign("MESSAGE", $objUser->getErr());
			}
			else
			{
				redirect(makeLink(array('mod'=>member,'pg'=>profile)));
			}

		}
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_contact.tpl");
		break;
	case "college":
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			unset($_POST["image_x"],$_POST["image_y"]);
			$objUser->setArrData($_POST);
			$framework->tpl->assign("PRF_COLLEGE",$_POST);
			if(!$objUser->addEditProfile($getId,'profile_college'))
			{
				$framework->tpl->assign("MESSAGE", $objUser->getErr());
			}
			else
			{
				redirect(makeLink(array('mod'=>member,'pg'=>profile)));
			}

		}
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_college.tpl");
		break;
	case "school":
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			unset($_POST["image_x"],$_POST["image_y"]);
			$objUser->setArrData($_POST);
			$framework->tpl->assign("PRF_SCHOOL",$_POST);
			if(!$objUser->addEditProfile($getId,'profile_school'))
			{
				$framework->tpl->assign("MESSAGE", $objUser->getErr());
			}
			else
			{
				redirect(makeLink(array('mod'=>member,'pg'=>profile)));
			}

		}
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_school.tpl");
		break;
	case "books":
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			unset($_POST["image_x"],$_POST["image_y"]);
			$objUser->setArrData($_POST);
			$framework->tpl->assign("PRF_BOOKS",$_POST);
			if(!$objUser->addEditProfile($getId,'profile_books'))
			{
				$framework->tpl->assign("MESSAGE", $objUser->getErr());
			}
			else
			{
				redirect(makeLink(array('mod'=>member,'pg'=>profile)));
			}

		}
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_books.tpl");
		break;
	case "food":
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			unset($_POST["image_x"],$_POST["image_y"]);
			$objUser->setArrData($_POST);
			$framework->tpl->assign("PRF_FOOD",$_POST);
			if(!$objUser->addEditProfile($getId,'profile_food'))
			{
				$framework->tpl->assign("MESSAGE", $objUser->getErr());
			}
			else
			{
				redirect(makeLink(array('mod'=>member,'pg'=>profile)));
			}

		}
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_food.tpl");
		break;
	case "movies":
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			unset($_POST["image_x"],$_POST["image_y"]);
			$objUser->setArrData($_POST);
			$framework->tpl->assign("PRF_MOVIES",$_POST);
			if(!$objUser->addEditProfile($getId,'profile_movies'))
			{
				$framework->tpl->assign("MESSAGE", $objUser->getErr());
			}
			else
			{
				redirect(makeLink(array('mod'=>member,'pg'=>profile)));
			}

		}
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_movies.tpl");
		break;
	case "music":
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			unset($_POST["image_x"],$_POST["image_y"]);
			$objUser->setArrData($_POST);
			$framework->tpl->assign("PRF_MUSIC",$_POST);
			if(!$objUser->addEditProfile($getId,'profile_music'))
			{
				$framework->tpl->assign("MESSAGE", $objUser->getErr());
			}
			else
			{
				redirect(makeLink(array('mod'=>member,'pg'=>profile)));
			}

		}
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_music.tpl");
		break;
	case "style":
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			unset($_POST["image_x"],$_POST["image_y"]);
			$objUser->setArrData($_POST);
			$framework->tpl->assign("PRF_STYLE",$_POST);
			if(!$objUser->addEditProfile($getId,'profile_style'))
			{
				$framework->tpl->assign("MESSAGE", $objUser->getErr());
			}
			else
			{
				redirect(makeLink(array('mod'=>member,'pg'=>profile)));
			}

		}
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_style.tpl");
		break;
	case "tv":
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			unset($_POST["image_x"],$_POST["image_y"]);
			$objUser->setArrData($_POST);
			$framework->tpl->assign("PRF_TV",$_POST);
			if(!$objUser->addEditProfile($getId,'profile_tv'))
			{
				$framework->tpl->assign("MESSAGE", $objUser->getErr());
			}
			else
			{
				redirect(makeLink(array('mod'=>member,'pg'=>profile)));
			}

		}
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_tv.tpl");
		break;
	case "travel":
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			unset($_POST["image_x"],$_POST["image_y"]);
			$objUser->setArrData($_POST);
			$framework->tpl->assign("PRF_TRAVEL",$_POST);
			if(!$objUser->addEditProfile($getId,'profile_travel'))
			{
				$framework->tpl->assign("MESSAGE", $objUser->getErr());
			}
			else
			{
				redirect(makeLink(array('mod'=>member,'pg'=>profile)));
			}

		}
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_travel.tpl");
		break;
	case "games":
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			unset($_POST["image_x"],$_POST["image_y"]);
			$objUser->setArrData($_POST);
			$framework->tpl->assign("PRF_GAMES",$_POST);
			if(!$objUser->addEditProfile($getId,'profile_games'))
			{
				$framework->tpl->assign("MESSAGE", $objUser->getErr());
			}
			else
			{
				redirect(makeLink(array('mod'=>member,'pg'=>profile)));
			}

		}
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_games.tpl");
		break;
	case "public":
		checkLogin();
		$memberID = $_SESSION['memberid'];
		//vinoy for display and give the rating ,feedback ,earning ,score and rank

		$bmuid=$_REQUEST['uid'];
		$type=$_REQUEST['type'];
		if($type=="seller")
		{
			$yearearn=$broker->getSellerYearlyEarnings($bmuid);

			$totearn =$broker->getSellerEarnings($bmuid);
			$framework->tpl->assign("YEAR_EARN",$yearearn['yearamt']);
			$framework->tpl->assign("TOT_EARN",$totearn['totamt']);
		}else{
			$totalearn=$broker ->getBrokerOrManagerInvoiceSum($bmuid,$_REQUEST['type']);
			$yearearn=$broker ->getBrokerOrManagerYearlySum($bmuid,$_REQUEST['type']);
			//############
			if($_REQUEST['type']=='BROKER_COMMISION')
			{
				$type="Broker";
			}
			elseif($_REQUEST['type']=='MANAGER_COMMISION')
			{
				$type="Manager";
			}
			$totdeposite=$broker ->getBrokerDeposite($bmuid,$type);
			$yeardeposite=$broker ->getBrokerYearlyDeposite($bmuid,$type);
			$fulltotal=$totalearn['Total_amount'] + $totdeposite['totamt'];
			$yeartotal=$yearearn['year_amount'] + $yeardeposite['yearamt'];

			$framework->tpl->assign("TOT_EARN", $fulltotal);
			$framework->tpl->assign("YEAR_EARN",$yeartotal);
			//################
			//$framework->tpl->assign("TOT_EARN",$totalearn['Total_amount']);
			//$framework->tpl->assign("YEAR_EARN",$yearearn['year_amount']);
		}
		if($_REQUEST['type']=='BROKER_COMMISION')
		{
			$type="Broker";
		}
		elseif($_REQUEST['type']=='MANAGER_COMMISION')
		{
			$type="Manager";
		}



		$fileid=$_REQUEST['fileid'];
		$chkComts =   $flyer->checkComments($fileid,$bmuid,$memberID,$type);
		$chkRate =   $flyer->checkRating($fileid,$bmuid,$memberID,$type);
		$framework->tpl->assign("COMMENT",$chkComts);
		$framework->tpl->assign("RATE",$chkRate);
		$framework->tpl->assign("TYPE",$type);
		$framework->tpl->assign("FILEID",$_REQUEST['fileid']);
		$framework->tpl->assign("BLOCK",$_REQUEST['block']);
		$bmrate=$objAlbum->getBrokerRate($bmuid,$type);

		$framework->tpl->assign("BMRRATE",$bmrate['rate']);
		$rate_show= $bmrate['rate']*20;
		$framework->tpl->assign("BMPRATE",$rate_show);
		/*$score=$bmrate['rate']*$yearearn['year_amount'];
		$score=round($score);
		$framework->tpl->assign("SCORE", $score);*/

		$udetails=$objUser->getUserdetails($bmuid);

		if($type=="Broker"){
			$framework->tpl->assign("SCORE", $udetails['broker_score']);
			$framework->tpl->assign("RANK", $udetails['broker_rank']);
		}
		if($type=="Manager"){
			$framework->tpl->assign("SCORE", $udetails['manager_score']);
			$framework->tpl->assign("RANK", $udetails['manager_rank']);
		}
		if($type=="seller"){
			$framework->tpl->assign("SCORE", $udetails['seller_score']);
			$framework->tpl->assign("RANK", $udetails['seller_rank']);
		}

		list($my_feedback, $numpad) = $objAlbum->getCommentDetails($bmuid,$type,$pageNo, $limit, $param, ARRAY_A,$orderBy);

		$framework->tpl->assign("MYCOMMENTS",$my_feedback);
		$framework->tpl->assign("NUMPAD",$numpad);

		if($_SERVER['REQUEST_METHOD'] == "POST")
		{

			if($_POST['type']=='Broker')
			{
				$type="BROKER_COMMISION";
			}
			elseif($_POST['type']=='Manager')
			{
				$type="MANAGER_COMMISION";

			}

			$mid=$_POST['bmid'];

			$fileid==$_POST['fileid'];
			$addrate  =   $flyer->addRatingAndFeedback($_POST);
			redirect(makeLink(array("mod"=>"member", "pg"=>"profile"), "act=public&uid=$mid&type=$type&fileid=$fileid"));

		}
		//

		if($global["inner_change_reg"]=="yes")
		{
			checkLogin();
		}
		if($_REQUEST["uid"])
		{
			if($_SESSION["memberid"]==$_REQUEST["uid"])
			{
				$owner="Y";
			}
			else
			{
				$owner="N";

			}
			$framework->tpl->assign("OWNER",$owner);

			$det=$objUser->getUserDetails($_REQUEST["uid"]);
			//print_r($det);

			//Ratheesh for display D.O.B
			if(array_key_exists("date_format",$global))
			{
				if($global["date_format"] !=""){
					list($y,$m,$d)=explode("-",$det['dob']);
					$dt_brth=date("M-d-Y", mktime(0, 0, 0, $m, $d, $y));
					$framework->tpl->assign("PRF_DT_BRTH",$dt_brth);
				}
			}//
			$dt=date("Y");
			$tpm=strtotime($det['dob']);
			$dym=getdate($tpm);
			$agm=$dt-$dym["year"];
			$framework->tpl->assign("AGE",$agm);
			//print_r($agm);exit;
			$cntry = $objUser->getCountryName($det["country"]);
			$framework->tpl->assign("CNTRY",$cntry["country_name"]);

			$framework->tpl->assign("PRF",$det);
		}
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/public_profile.tpl");
		break;

	case "more":

		checkLogin();

		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			if(isset($_POST["comment"]))
			{
				checkLogin();
				$req = &$_REQUEST;



				if( ($message = $objUser->insertComment($req,$_REQUEST[uid])) === true )
				{
					if($_REQUEST[detprf]==1){
						redirect(makeLink(array('mod'=>member,'pg'=>profile), "act=more&uid=$_REQUEST[uid]"));
						exit;
					}else{
						redirect(makeLink(array('mod'=>member,'pg'=>profile),"uid=$getId"));
					}
				}
			}
			elseif(isset($_POST["tip"]))
			{
				checkLogin();
				//

				$arr = array();
				$arr["user_id"]=$getId;
				$arr["tiptext"]=$_POST["tip"];
				$objUser->setArrData($arr);
				//print_r($arr);exit;
				if(!$objUser->insertTip())
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
		$uid=$getId;


		list($rs1, $numpad1,$count) = $objUser->getMessage($_REQUEST['pageNo'], 5, "uid=$uid&mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $orderBy,$uid);
		$framework->tpl->assign("COMMENT_COUNT",$count);
		###to list private users on the search result (Link 54)
		### Modified on 4 Jan 2008.
		### Modified By Jinson.
		if($global['show_private']=='Y')
		{ for ($i=0;$i<sizeof($rs1);$i++)
		{
			$medet=$objUser->getUsernameDetails($rs1[$i]->username);
			if($medet["user_id"]==$_SESSION["memberid"])
			{$rs1[$i]->owner="Y";}
			if($medet["mem_type"]==3){
				if($medet["friends_can_see"]=='Y')
				{
					if($objUser->isFriends($medet["user_id"],$_SESSION["memberid"])==true)
					{$rs1[$i]->show_profile="Y";}
					else{$rs1[$i]->show_profile="N";}
				}
				else{
					$rs1[$i]->show_profile="N";
				}
				if($medet["user_id"]==$_SESSION["memberid"]){
					$rs1[$i]->show_profile="Y";
				}

			}
			else{$rs1[$i]->show_profile="Y";}

		}
		}//if
		//////////////////
		if($global['show_screen_name_only']=='1'){
			for($k=0;$k<count($rs1);$k++){

				$sn=$objUser->getUsernamedetails($rs1[$k]->username);
				$rs1[$k]->screen_name=$sn['screen_name'];
			}
		}
		$num_pages=ceil($count/5);
		if ($num_pages>1)
		{
			if ($_REQUEST["pageNo"])
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
			else
			{
				$pgNo=2;
				$framework->tpl->assign("NEXT_PG", $pgNo);
			}
		}

		$framework->tpl->assign("COMMENT_LIST", $rs1);
		$framework->tpl->assign("COMMENT_NUMPAD", $numpad1);

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

		//list($rs1,$count) = $objUser->getTip($start,$end,$uid);
		if($global['show_screen_name_only']=='1'){
			for($k=0;$k<count($rs1);$k++){
				$sn=$objUser->getUsernamedetails($rs1[$k]->username);
				$rs1[$k]->screen_name=$sn['screen_name'];
			}
		}
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
		if($objAlbum->getSelectedOptions($_SESSION["memberid"]))
		{
			$rs2=$objAlbum->getSelectedOptions($_SESSION["memberid"]);
			$framework->tpl->assign("OPT_LIST", $rs2);
		}
		else
		{
			$objAlbum->setStatusDefault($_SESSION["memberid"]);
			$rs2=$objAlbum->getSelectedOptions($_SESSION["memberid"]);
			$framework->tpl->assign("OPT_LIST", $rs2);
		}

		$framework->tpl->assign("TIP_LIST", $rs1);
		//print_r($rs1);
		//$framework->tpl->assign("TIP_NUMPAD", $numpad1);
		$framework->tpl->assign("RAND",rand());
		$det_login_usr=$objUser->getUserDetails($_SESSION["memberid"]);
		if($_REQUEST["uid"])
		{
			if($_SESSION["memberid"]==$_REQUEST["uid"])
			{
				$owner="Y";
			}
			else
			{
				$owner="N";
			}
			$framework->tpl->assign("OWNER",$owner);

			$det=$objUser->getUserDetails($_REQUEST["uid"]);


			//Ratheesh for display D.O.B
			if(array_key_exists("date_format",$global))
			{
				if($global["date_format"] !=""){
					list($y,$m,$d)=explode("-",$det['dob']);
					$dt_brth=date("M-d-Y", mktime(0, 0, 0, $m, $d, $y));
					$framework->tpl->assign("PRF_DT_BRTH",$dt_brth);
				}
			}//
			$dt=date("Y");
			$tpm=strtotime($det['dob']);
			$dym=getdate($tpm);
			$agm=$dt-$dym["year"];
			$framework->tpl->assign("AGE",$agm);
			//print_r($agm);exit;
			$cntry = $objUser->getCountryName($det["country"]);
			$framework->tpl->assign("CNTRY",$cntry["country_name"]);
			$framework->tpl->assign("PRF",$det);
		}
		list($rs1, $numpad1,$count) = $objUser->getMessage($_REQUEST['pageNo'], "", "uid=$_REQUEST[uid]&mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $orderBy,$uid);

		$cnt = count($rs1);
		$framework->tpl->assign("TIP_count", $rs1);
		$framework->tpl->assign("TIP_CNT", $cnt);
		$framework->tpl->assign("PRF_LOGINUSER",$det_login_usr);

		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/detailedprofile.tpl");
		break;
	case "change":
		checkLogin();
		$framework->tpl->assign("TITLE_HEAD","Change Photo");
		$go_back_url = fetchPreURL();
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			$images = $_FILES['image'];
			//print_r($images);exit;
			
			if ($framework->config['profile_photos_cnt'])
			{
				$photo_cnt = $framework->config['profile_photos_cnt'];

				$proceed = 0;
				for ($i=0;$i<$photo_cnt;$i++)
				{
					$fname=basename($_FILES['image']['name'][$i]);
					$proceed = 1;
						$img_ext = strtolower($_FILES['image']['type'][$i]);
						$img_ext = str_replace("image/","",$img_ext);
	
						if ($img_ext=="jpeg" || $img_ext=="pjpeg")
						{
							$img_ext = "jpg";
						}
						elseif ($img_ext=="x-png")
						{
							$img_ext = "png";
						}
	
						if(!in_array(strtolower($img_ext), array('jpg', 'gif', 'png')))
						{
							$proceed = 0;
							$image_cnt = $i+1;
							$error_msg = "Incorrect Image Type in Image{$image_cnt} (Upload Jpg/GIF/PNG formats only)<br>";
						}
						list($width,$height) = getimagesize($_FILES['image']['tmp_name'][$i]);
						
						if ($framework->config["member_image_min"])
						{
							$img_dimensions = explode(",",$framework->config["member_image_min"]);
							
							if ( ($width<$img_dimensions[0]) || ($height<$img_dimensions[1]))
							{
								$proceed = 0;
								setMessage("Image size is too small (Mimimum size is {$img_dimensions[0]} X {$img_dimensions[1]})");
							}
						}
				}
				if ($proceed==0)
				{
					setMessage($error_msg);
				}
				else
				{
					$arr = array();
					for ($i=0;$i<$photo_cnt;$i++)
					{
						$img_ext = strtolower($_FILES['image']['type'][$i]);
						$img_ext = str_replace("image/","",$img_ext);
						$d_no = $i+1;
						if ($img_ext=="jpeg" || $img_ext=="pjpeg")
						{
							$img_ext = "jpg";
						}
						elseif ($img_ext=="x-png")
						{
							$img_ext = "png";
						}
						
						$dir   = SITE_PATH."/modules/member/images/userpics/";
						$thumbdir = $dir."thumb/";
						$id    = $_SESSION["memberid"];
						
						move_uploaded_file($_FILES["image"]['tmp_name'][$i], $dir."{$id}_{$i}.jpg");
						@chmod($dir."{$id}_{$i}.jpg",0777);
						
						if ($img_ext!="jpg")
						{
							copy($dir."{$id}_{$i}.jpg",$dir."{$id}_{$i}.$img_ext");
							@chmod($dir."{$id}_{$i}.$img_ext",0777);
						}

						if ($framework->config["member_image_thumb1"])
						{
							$thumb_size   = explode(",",$framework->config["member_image_thumb1"]);
							$thumb_width  = $thumb_size[0];
							$thumb_height = $thumb_size[1];
						}
						else
						{
							$thumb_width  = 100;
							$thumb_height = 100;
						}

						thumbnail($dir,$thumbdir,"{$id}_{$i}.$img_ext",$thumb_width,$thumb_height,"","{$id}_{$i}.jpg",0);
						@chmod($thumbdir."{$id}_{$i}.jpg",0777);
						
						if ($_REQUEST["default{$d_no}"]=="Y")
						{
							copy($thumbdir."{$id}_{$i}.jpg",$thumbdir."$id.jpg");
							@chmod($thumbdir."$id.jpg",0777);
						}
						
						$additional_thumb = $framework->config["member_thumb_additional"];
						
						for ($j=1;$j<=$additional_thumb;$j++)
						{
							$t_no = $j+1;
							if ($framework->config["member_image_thumb{$t_no}"])
							{
								$thumb_size   = explode(",",$framework->config["member_image_thumb{$t_no}"]);
								$thumb_width  = $thumb_size[0];
								$thumb_height = $thumb_size[1];
	
								thumbnail($dir,$thumbdir,"{$id}_{$i}.$img_ext",$thumb_width,$thumb_height,"",$id."_thumb{$t_no}_$i.jpg",0);
								@chmod($thumbdir.$id."_thumb{$t_no}_{$i}.jpg",0777);
								if ($_REQUEST["default{$d_no}"]=="Y")
								{
									copy($thumbdir.$id."_thumb{$t_no}_$i.jpg",$thumbdir.$id."_thumb{$t_no}.jpg");
									@chmod($id."_thumb{$t_no}.jpg",0777);
								}
							}
						}

						@unlink($dir."{$id}_{$i}.$img_ext");
						if ($_REQUEST["default{$d_no}"]=="Y")
						{
							
							$arr["id"] = $_SESSION["memberid"];
							$pid = $_SESSION["memberid"];
							$arr["image"] = "Y";
							$arr['current_thumb'] = $d_no;
							
						}	
					}
					$arr['tot_images'] = $d_no;
					$objUser->setArrData($arr);
					$objUser->update();
				}

			}
			else
			{
				$photo_cnt = 1;

				$fname=basename($_FILES['image']['name']);
				$proceed = 1;
				$img_ext = strtolower($_FILES['image']['type']);
				$img_ext = str_replace("image/","",$img_ext);

				if ($img_ext=="jpeg" || $img_ext=="pjpeg")
				{
					$img_ext = "jpg";
				}
				elseif ($img_ext=="x-png")
				{
					$img_ext = "png";
				}

				if(!in_array(strtolower($img_ext), array('jpg', 'gif', 'png')))
				{
					$proceed = 0;
					setMessage("Incorrect Image Type (Upload Jpg/GIF/PNG formats only)");
				}

				if ($fname)
				{
					list($width,$height) = getimagesize($_FILES['image']['tmp_name']);


					if ($framework->config["member_image_min"])
					{
						$img_dimensions = explode(",",$framework->config["member_image_min"]);


						if ( ($width<$img_dimensions[0]) || ($height<$img_dimensions[1]))
						{
							$proceed = 0;
							setMessage("Image size is too small (Mimimum size is {$img_dimensions[0]} X {$img_dimensions[1]})");
						}
					}

					if ($proceed==1)
					{
						$dir   = SITE_PATH."/modules/member/images/userpics/";
						$thumbdir = $dir."thumb/";
						$id    = $_SESSION["memberid"];
						uploadImage($_FILES["image"],$dir,$id.".jpg",1);
						@chmod($dir."$id.jpg",0777);

						if ($img_ext!="jpg")
						{
							copy($dir."$id.jpg",$dir."$id.$img_ext");
							@chmod($dir."$id.$img_ext",0777);
						}

						if ($framework->config["member_image_thumb1"])
						{
							$thumb_size   = explode(",",$framework->config["member_image_thumb1"]);
							$thumb_width  = $thumb_size[0];
							$thumb_height = $thumb_size[1];
						}
						else
						{
							$thumb_width  = 100;
							$thumb_height = 100;
						}

						thumbnail($dir,$thumbdir,"$id.$img_ext",$thumb_width,$thumb_height,"","$id.jpg",0);
						@chmod($thumbdir."$id.jpg",0777);

						if ($framework->config["member_image_thumb2"])
						{
							$thumb_size   = explode(",",$framework->config["member_image_thumb2"]);
							$thumb_width  = $thumb_size[0];
							$thumb_height = $thumb_size[1];

							thumbnail($dir,$thumbdir,"$id.$img_ext",$thumb_width,$thumb_height,"",$id."_thumb2.jpg",0);
							@chmod($thumbdir.$id."_thumb2.jpg",0777);
						}
						@unlink($dir."$id.$img_ext");
						$arr = array();
						$arr["id"] = $_SESSION["memberid"];
						$pid = $_SESSION["memberid"];
						$arr["image"] = "Y";
						$objUser->setArrData($arr);
						$objUser->update();
						setMessage("Display picture has been changed",MSG_INFO);
						if ($go_back_url)
						{
							redirect($go_back_url);
						}
						else
						{
							redirect(makeLink(array('mod'=>member,'pg'=>home)));
						}
					}
				}
			}



			// Ratheesh kk for original image
			/*if(array_key_exists("display_imagetype",$global) && $global["display_imagetype"]=="original" ){

			if($objUser->changeOriginalPhoto())
			{
			redirect(makeLink(array('mod'=>member,'pg'=>home)));
			}
			else
			{
			$framework->tpl->assign("MESSAGE",$objUser->getErr());
			}
			}
			else
			{
			if($objUser->changePhoto())
			{
			redirect(makeLink(array('mod'=>member,'pg'=>home)));
			}
			else
			{
			$framework->tpl->assign("MESSAGE",$objUser->getErr());
			}
			}*/
		}
		if($_REQUEST["remove"]==1)
		{
			$objUser->removePicture();
			if ($go_back_url)
			{
				redirect($go_back_url);
			}
			else
			{
				redirect(makeLink(array('mod'=>member,'pg'=>home)));
			}
		}
		$framework->tpl->assign("RAND",rand());
		$framework->tpl->assign("ACC",$_REQUEST[acc]);
		if ($framework->config['profile_inner']=="Y")
		{
			$framework->tpl->assign("PROFILE_HEAD","Change Display Picture");
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_main.tpl");
			$framework->tpl->assign("profile_tpl",SITE_PATH."/modules/member/tpl/change_photo.tpl");
		}
		else
		{
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/change_photo.tpl");
		}
		break;
	case "profile_frame":
		$uid=$_REQUEST["uid"];
		$userDet=$objUser->getUserdetails($uid);
		$framework->tpl->assign("PRF",$userDet);
		$framework->tpl->display(SITE_PATH."/modules/member/tpl/profile_frame.tpl");
		exit;
		break;
	case "musicbox":

		if ($_REQUEST['prf_name'])
		{
			$uid = $getId;
		}
		else
		{
			$uid=$_REQUEST["uid"];
		}
		#	if($_REQUEST['pageNo']){
		#		$pgno=$_REQUEST['pageNo'];
		#	}else{
		#		$pgno=1;
		#	}
		#	$framework->tpl->assign("PGNO",$pgno);
		$framework->tpl->assign("UID",$uid);
		$framework->tpl->assign("total_price",$global['track_price']);
		$framework->tpl->assign("album_price",$global['album_price']);
		$user_Det=$objUser->getUserdetails($uid);
		$gen=explode(",",$user_Det["genre"]);
		foreach($gen as $gener){
			$catdet[]=$objCategory->CategoryGet($gener);
		}
		//print_r($user_Det);exit;
		$framework->tpl->assign("GENRE",$catdet);
		$peoplecount=$objUser->getFriendsCount($uid);
		$framework->tpl->assign("PEOPLE",$peoplecount);
		$framework->tpl->assign("PRF",$user_Det);
		$str_text = $user_Det['nick_name'];
		if (strtolower(substr($str_text,strlen($str_text)-1))=="s")
		{
			$ext_t = "'";
		}
		else
		{
			$ext_t = "'s";
		}
		$framework->tpl->assign("EXT",$ext_t);
		if($userDet["mem_type"]==2){
			if($_REQUEST["menu"]=="mybox"){
				$framework->tpl->assign("RED",0);
				$params="mod=member&pg=profile&act=musicbox&uid={$uid}&menu=mybox";
				$stxt=0;
				$media="Music";
				$tbl="album_music";
				$mpath="music/thumb/";
				$framework->tpl->assign("MPATH",$mpath);
				$framework->tpl->assign("PGFILE","music");
				$framework->tpl->assign("FILE_ID","music_id");
				if($_REQUEST['next2']){
					$pagno =1;
				}else{
					if($_REQUEST['pageNo']){
						$pagno = $_REQUEST['pageNo'];
					}else{
						$pagno =1;
					}
				}
				$framework->tpl->assign("PGNO",$pagno);
				list($musictrack,$numpadmain,$v,$j,$cnt,$prev,$next)= $objAlbum->mediaFavList($pagno, 10,$params, OBJECT, 'title',$tbl,'music',$stxt,$uid);
			}else{
				$framework->tpl->assign("RED",1);
				$params="mod=member&pg=profile&act=musicbox&uid={$uid}&menu=mytrack";

				if($_REQUEST['next2']){
					$pagno =1;
				}else{
					if($_REQUEST['pageNo']){
						$pagno = $_REQUEST['pageNo'];
					}else{
						$pagno =1;
					}
				}
				list($musictrack, $numpadmain,$v,$j,$cnt,$prev,$next)=$objMusic->getMyTrack($pagno, 10, $params,$uid);
				$framework->tpl->assign("PGNO",$pagno);
			}

			//For Flash Player Integration
			/*for ($i=0;$i<sizeof($musictrack);$i++)
			{
			$musictrack[$i]->play_duration = $framework->config["other_song_duration"];
			}
			$_SESSION["xml_arr"] = $musictrack;*/
			//print_r($musictrack);exit;

			$framework->tpl->assign("TRK",$musictrack);
			$framework->tpl->assign("NUMPADMAIN",$numpadmain);
			if($cnt==0){
				$cnt=1;
			}

			$framework->tpl->assign("CNT",$cnt);
			$framework->tpl->assign("PREV",$prev);
			$framework->tpl->assign("NEXT",$next);
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/artist_profile.tpl");
			//$_REQUEST["MUSICTRACK"]=$musictrack;
		}elseif($userDet["mem_type"]==1){
			//checkLogin();
			$params="mod=member&pg=profile&act=musicbox&uid={$uid}";
			$stxt=0;
			$media="Music";
			$tbl="album_music";
			$mpath="music/thumb/";
			$framework->tpl->assign("MPATH",$mpath);
			$framework->tpl->assign("PGFILE","music");
			$framework->tpl->assign("FILE_ID","music_id");
			if($_REQUEST['next2']){
				$pagno =1;
			}else{
				if($_REQUEST['pageNo']){
					$pagno = $_REQUEST['pageNo'];
				}else{
					$pagno =1;
				}
			}
			list($musictrack, $numpadmain,$v,$j,$cnt,$prev,$next)= $objAlbum->mediaFavList($pagno, 10,$params, OBJECT, 'title',$tbl,'music',$stxt,$uid);
			$framework->tpl->assign("PGNO",$pagno);
			$framework->tpl->assign("TRK",$musictrack);
			$framework->tpl->assign("NUMPADMAIN",$numpadmain);
			if($cnt==0){
				$cnt=1;
			}

			$framework->tpl->assign("CNT",$cnt);
			$framework->tpl->assign("PREV",$prev);
			$framework->tpl->assign("NEXT",$next);
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/member_profile.tpl");
			//$_REQUEST["MUSICTRACK"]=$musictrack;
		}
		//For Flash Player Integration
		/*for ($i=0;$i<sizeof($musictrack);$i++)
		{
		$musictrack[$i]->play_duration = $framework->config["other_song_duration"];
		}*/

		$all_tracks = $objAlbum->getAllTracks($uid,1,1);
		$_SESSION["xml_arr"] = $musictrack;

		//print_r($_SESSION["xml_arr"]);
		$_REQUEST["MUSICTRACK"]=$musictrack;
		$framework->tpl->assign("PLAY_MODE",1);
		if ($_REQUEST['pageNo']>1)
		{
			$framework->tpl->assign("PG_NO",($_REQUEST['pageNo']-1)*10);
		}
		else
		{
			$framework->tpl->assign("PG_NO",0);
		}
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			if($_POST["chkb"]){
				foreach ($_POST["chkb"] as $mid){
					checkLogin();
					$item_rs =$objAlbum->mediaDetailsGet($mid,"music");
					$objAlbum->addToCart($_SESSION["memberid"], $mid,"music", $global['track_price'], $item_rs['cat_id']);

				}redirect(makeLink(array("mod"=>"album", "pg"=>"shop"), "act=viewcart"));
			}

		}
		if($_REQUEST["BUYALBUM"]){
			checkLogin();
			foreach ($all_tracks as $mtk){
				$item_rs =$objAlbum->mediaDetailsGet($mtk->id,"music");
				$jk=$objAlbum->addToCart($_SESSION["memberid"], $mtk->id,"music", $global['track_price'], $item_rs['cat_id'],"album");
			}redirect(makeLink(array("mod"=>"album", "pg"=>"shop"), "act=viewcart"));

		}
		if($_REQUEST["lnk"]=="mylist"){
			$framework->tpl->assign("BOX", "List");
			$mem_id   = $_REQUEST["user_id"];
			if($_REQUEST["type"]){
				$ftype=$_REQUEST["type"];
			}else{
				$ftype=0;
			}
			$friendtype=$objUser->getFriendType();
			//print_r($friendtype);exit;
			$framework->tpl->assign("FR_TYPE", $friendtype);
			$framework->tpl->assign("FTYPE", $ftype);
			$framework->tpl->assign("USID", $mem_id);
			//$user_det = $objUser->getUserDetails($mem_id);
			$uname    = $user_det["username"];
			$param="mod=$mod&pg=$pg&act=".$_REQUEST['act']."&type=".$ftype."&user_id=".$_REQUEST["user_id"]."&uid=".$mem_id."&mid=".$_REQUEST["mid"]."&next2=bottom&lnk=".$_REQUEST["lnk"];

			if($_SERVER['REQUEST_METHOD']=="POST")
			{
				$_REQUEST["pageNo"]=1;
			}
			list($rs, $numpad) = $objUser->getFriendDetails($_REQUEST['pageNo'], 10, $param, OBJECT, $_REQUEST['orderBy'],$_REQUEST["txtuname"],$mem_id,$ftype);
			//print_r($rs);exit;
			$userDet=$objUser->getUserdetails($mem_id);
			$framework->tpl->assign("USERINFO", $userDet);//getting details from member_master
			for ($i=0;$i<sizeof($rs);$i++)
			{
				$medet=$objUser->getUsernameDetails($rs[$i]->username);
				$rs[$i]->nick_name=$medet["nick_name"];
				//print_r($mdet);exit;

			}
			//print_r($rs);exit;
			$framework->tpl->assign("PROFILE_LIST", $rs);
			$framework->tpl->assign("PROFILE_NUMPAD", $numpad);
			if($userDet["mem_type"]==2){
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/friendlist_profile_artist.tpl");
			}else{
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/friendlist_profile_member.tpl");
			}
		}elseif($_REQUEST["lnk"]=="myphotos"){
			checkLogin();



			$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&lnk=".$_REQUEST["lnk"]."&user_id=".$_REQUEST["user_id"]."&uid=".$_REQUEST["user_id"]."&mid=".$_REQUEST["mid"]."&next2=bottom";
			list($rs, $numpad) = $objPhoto->photoList($_REQUEST['pageNo'], 5,$par, OBJECT, 'id desc',0,$stxt);

			$_REQUEST['next2'] = $_REQUEST['pageNo'];

			$framework->tpl->assign("PHOTO_LIST", $rs);
			$framework->tpl->assign("PHOTO_NUMPAD", $numpad);
			if($userDet["mem_type"]==2){
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/photo_profile_artist.tpl");
			}else{
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/photo_profile_member.tpl");
			}
			$framework->tpl->assign("BOX", "Photos");
		}elseif($_REQUEST["lnk"]=="myvideos"){
			checkLogin();
			$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&lnk=".$_REQUEST["lnk"]."&user_id=".$_REQUEST["user_id"]."&uid=".$_REQUEST["user_id"]."&mid=".$_REQUEST["mid"]."&next2=bottom";
			$media="Video";
			$type="video";
			$tbl="album_video";
			$mpath="video/thumb/";
			$framework->tpl->assign("MPATH",$mpath);
			$framework->tpl->assign("PGFILE","video");
			$framework->tpl->assign("FILE_ID","video_id");
			list($rs, $numpad) = $objAlbum->mediaList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title:ASC',$tbl,'video',$stxt,$alb,$_REQUEST["user_id"]);
			$mvCount=$objAlbum->getMediaCount($_SESSION["memberid"],'album_video');
			$framework->tpl->assign("mvCount",$mvCount);
			$framework->tpl->assign("PHOTO_LIST", $rs);
			$framework->tpl->assign("PHOTO_NUMPAD", $numpad);
			if($userDet["mem_type"]==2){
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/video_profile_artist.tpl");
			}else{
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/video_profile_member.tpl");
			}
			$framework->tpl->assign("BOX", "Videos");
		}elseif($_REQUEST["lnk"]=="lyrics"){
			checkLogin();
			$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&lnk=".$_REQUEST["lnk"]."&uid=".$_REQUEST["user_id"]."&mid=".$_REQUEST["mid"]."&next2=bottom";
			$tbl="album_music";
			$mpath="music/thumb/";
			$framework->tpl->assign("MPATH",$mpath);
			$framework->tpl->assign("PGFILE","music");
			$framework->tpl->assign("FILE_ID","music_id");
			list($rs, $numpad) = $objAlbum->mediaList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title:ASC',$tbl,'music',$stxt,$alb,$_REQUEST["user_id"],'public');
			$framework->tpl->assign("msCount",$msCount);
			$framework->tpl->assign("BOX", "Lyrics");
			$framework->tpl->assign("PHOTO_LIST", $rs);
			$framework->tpl->assign("PHOTO_NUMPAD", $numpad);
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/lyric_profile_artist.tpl");

		}else{
			$framework->tpl->assign("PAGE_HIT",$framework->pageHit("profile",$uid));
			$framework->tpl->assign("BOX", "MeggaBox");

		}
		$framework->tpl->display($global['curr_tpl']."/inner1.tpl");
		exit;
		break;
		
    case "opinion_form":
			$show				=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "5";
			//###################
			$memberId=$_SESSION["memberid"];
			$page=$_REQUEST['page'];
		
			if (isset($page))
			{
			  $CurPage = $page;
			}
			else
			{
			  $CurPage = 0;
			}
			$rowspage = 10;
			$start = $CurPage * $rowspage;
			$res=$objUser->getOpenionQuestionsAjax($start,$rowspage);
			$rs=$objUser->getOpenionQuestions();
			
		    $totalrecords =count($rs);
			$lastpage = ceil($totalrecords/$rowspage);
			
			if ($CurPage == $lastpage)
			{
				$framework->tpl->assign("NEXT",'false');
				//$_SESSION['profile_question_next']='false';
			}
			else
			{
				$nextpage = $CurPage + 1;
				$framework->tpl->assign("NEXTPAGE",$nextpage);
				$framework->tpl->assign("NEXT",'true');
		
			}
			if ($CurPage == 0)
			{
				$framework->tpl->assign("PREV",'false');
			}
			else
			{
				 $prevpage = $CurPage - 1;
				 $framework->tpl->assign("PREVPAGE",$prevpage);
				 $framework->tpl->assign("PREV",'true');
			}
			//#######################
			//list($res, $numpad, $cnt, $limitList)=$objUser->getOpenionQuestionsAjax($_REQUEST['pageNo'], 10, $par, ARRAY_A, $_REQUEST['orderBy'],0,0,0,0,0);
			
			$fquest = $objUser->firstQuestion();
			list($fanswer, $funmp, $cnt, $limitList)=$objUser->firstAnswerDetailsAjax($_REQUEST['pageNo'], 4, $par, ARRAY_A, $_REQUEST['orderBy'],$fquest['id'],0,0,0,0);
			//$fanswer = $objUser->firstAnswerDetails($fquest['id']);
			//print_r($res);
			$framework->tpl->assign("TOTAL",$totalrecords);
			$framework->tpl->assign("ROWPAGE",$rowspage);
			$framework->tpl->assign("FANSWER",$fanswer);
			$framework->tpl->assign("FNUMPAD",$funmp);
			$framework->tpl->assign("FQUEST",$fquest);
			$framework->tpl->assign("QLIST", $res);
			$framework->tpl->assign("QNUMPAD", $numpad);
			
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/openion_form.tpl");
	break;
	case "opinion_page":
		  $page=$_REQUEST['page'];
		 $counter=$_REQUEST['counter'];
		 $opt=$_REQUEST['opt'];
		  
			if (isset($page))
			{
			   $CurPage = $page;
			}
			else
			{
			  $CurPage = 0;
			}
			$rowspage =10;
			$start = $CurPage * $rowspage;
			$res=$objUser->getOpenionQuestionsAjax($start,$rowspage);
			$rs=$objUser->getOpenionQuestions();
			$rescnt=count($res);
		    $totalrecords =count($rs);
			$lastpage = ceil($totalrecords/$rowspage);
			
			if ($CurPage == $lastpage-1)
			{
				$framework->tpl->assign("NEXT",'false');
				//$_SESSION['profile_question_next']='false';
				$NEXT='false';
			}
			else
			{
				$nextpage = $CurPage + 1;
				$framework->tpl->assign("NEXTPAGE",$nextpage);
				//$framework->tpl->assign("NEXT",'true');
			$NEXT='true';
			}
			if ($CurPage == 0)
			{
				//$framework->tpl->assign("PREV",'false');
				$PREV='false';
			}
			else
			{
				 $prevpage = $CurPage - 1;
				 $framework->tpl->assign("PREVPAGE",$prevpage);
				// $framework->tpl->assign("PREV",'true');
				$PREV='true';
			}
			$rows=$rowspage+$rowspage;
			 $div=$totalrecords/$rowspage;
			 $div=round($div);
			 
			
			 if($page>0){
			  $counter=($page)*$rowspage+1;
			 }
			 else if($page==0)
			{
			$counter=1;
			}
			
			
			
			
			$rsecho.="<table width=\"96%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"table-white\">";
							
                                 $rsecho.="<tr>";
                                 $rsecho.="<td height=\"14\" colspan=\"2\" align=\"left\" valign=\"top\">&nbsp;</td>";
                                $rsecho.="</tr>";
                                 $rsecho.="<tr>";
                                  $rsecho.="<td width=\"3%\" height=\"308\" align=\"left\" valign=\"top\">&nbsp;</td>";
                                  $rsecho.="<td width=\"97%\" align=\"left\" valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
								
								   foreach($res as $citem)
								   {
								   
                                      $rsecho.="<tr>";
									  $rsecho.="<td width=\"7%\" height=\"20\" class=\"blacklink\">".$counter.")</td>";
                                        $rsecho.="<td width=\"93%\" height=\"20\" class=\"blacklink\"><a href=\"javascript:void(0)\" onclick=\"javascript:chgQuest(".$citem['id'].");return false;\" class=\"blacklink\">".$citem['question']."</a></td>";
                                      $rsecho.="</tr>";
									  $counter++;
									 }
                                     
                                      $rsecho.="<tr>";
                                       $rsecho.="<td class=\"blackheading\">&nbsp;</td>";
                                      $rsecho.="</tr>";
                                  $rsecho.="</table></td>";
                                 $rsecho.="</tr>";
								
								
								  $rsecho.="<tr>";
                                 $rsecho.="<td width=\"3%\" height=\"4\" align=\"left\" valign=\"top\">&nbsp;</td>";
                                  $rsecho.="<td width=\"97%\" align=\"left\" valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
								
								   if( $totalrecords > $rowspage)
								   {
                                      $rsecho.="<tr>";
									   $rsecho.="<td width=\"45%\" height=\"20\" class=\"blacklink\">";
									 if($PREV=='true')
									 {
									   $rsecho.="<a href=\"javascript:void(0);\" onClick=\"javascript:getOpinionQuestion($prevpage);\" title=\"previous\"><img src=\"{$global[tpl_url]}/images/left-arrow.jpg\"  alt=\"\" width=\"10\" height=\"20\" border=\"0\"/></a>";
									  }else{
									//   $rsecho.="<img src=\"{$global[tpl_url]}/images/left-arrow.jpg\"  alt=\"\" width=\"10\" height=\"20\" border=\"0\"/>";
									   }
									      $rsecho.="</td>";
                                          $rsecho.="<td width=\"10%\" height=\"20\" class=\"blacklink\"><input type=\"hidden\" name=\"next_val\" id=\"next_val\" value=\"$NEXT\"><input type=\"hidden\" name=\"next_page_val\" id=\"next_page_val\" value=\"$NEXTPAGE\"></td>";
									    $rsecho.="<td width=\"45%\" height=\"20\" class=\"blacklink\" align=\"right\">";
										if($NEXT =='true'){
										  $rsecho.="<a href=\"javascript:void(0);\" onClick=\"javascript:getOpinionQuestion($nextpage);\" title=\"next\"><img src=\"{$global[tpl_url]}/images/right-arrow.jpg\" alt=\"\" width=\"10\" height=\"20\" border=\"0\" /></a>";
										}else
										{
										 // $rsecho.="<img src=\"{$global[tpl_url]}/images/right-arrow.jpg\" alt=\"\" width=\"10\" height=\"20\" border=\"0\" />";
										  }
										  $rsecho.="&nbsp;</td>";
										$rsecho.="</tr>";
									 }
                                     
                                  $rsecho.="</table></td>";
                                $rsecho.="</tr>";
								$rsecho.="</table>";
								
								
								
								
								
								list($fanswer, $funmp, $cnt, $limitList)=$objUser->firstAnswerDetailsAjax($_REQUEST['pageNo'], 4, $par, ARRAY_A, $_REQUEST['orderBy'],$res[0]['id'],0,0,0,0);
			
			$togIndex = 1;
						 
				  $resecho.=" <table width=\"100%\" border=\"0\" style=\"height:100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"table-gray\">";
				  $resecho.="<tr>";
				  $resecho.="<td width=\"7%\" height=\"36\" align=\"right\"><img src=\"{$global[tpl_url]}/images/arrow_graybox.jpg\" width=\"13\" height=\"11\" alt=\"\"/></td>";
				  $resecho.="<td width=\"93%\" align=\"left\" class=\"headingtext6\">".$res[0]['question']."</td>";
				  $resecho.="<input name=\"qid\" id=\"qid\" type=\"hidden\" value=\"".$res[0]['id']."\">";
                  $resecho.="</tr>";
                  $resecho.="<tr>";
				  $resecho.="<td height=\"20\" align=\"right\">&nbsp;</td>";
                  $resecho.="<td align=\"left\" class=\"headingtext6\"><a href=\"javascript:void(0)\" onclick=\"javascript:showPopup('answer_pop',event,'slideDivAnswer','".$Group['user_id']."');return false;\" class=\"blacktext2\"><img src=\"{$global[tpl_url]}/images/submit-answer.jpg\"  width=\"111\" height=\"22\" border=\"0\" /></a></td>";
     			  $resecho.="</tr>";
                  $resecho.="<tr>";
                  $resecho.="<td height=\"35\" align=\"right\">&nbsp;</td>";
                  $resecho.="<td class=\"headingtext6\">&nbsp;</td>";
                  $resecho.="</tr>";
                  $resecho.="<tr>";
                  $resecho.="<td height=\"20\" align=\"right\">&nbsp;</td>";
                  $resecho.="<td height=\"328\" align=\"left\" valign=\"top\" >";
				  $resecho.="<table width=\"94%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >";
				 if($fanswer!=''){
					 foreach($fanswer as $Group) 
					 {
					 	 $resecho.="<tr><td width=\"67\" height=\"61\" align=\"left\">";
						 $resecho.="<table width=\"59\"  border=\"0\" cellpadding=\"0\" cellspacing=\"3\" class=\"border1\" style=\"background-color:#FFFFFF;\"  >";
						 if($Group['image']=='Y')
						 {
						 $resecho.="<tr><td width=\"51\" height=\"55\" valign=\"top\"><img src=". SITE_URL ."/modules/member/images/userpics/thumb/".$Group['user_id'].jpg ." width=\"51\" height=\"55\" alt=\"\" /></td> </tr>";
						 }else{
						  $resecho.="<tr><td width=\"51\" height=\"55\" valign=\"top\"><img src=\"{$global[tpl_url]}/images/nophoto_thumb1.jpg\" width=\"51\" height=\"55\" alt=\"\" /></td> </tr>";
						 }
						 $resecho.="</table></td><td width=\"246\" align=\"left\" valign=\"top\" class=\"leftlink\">";
						 $resecho.="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
						 $resecho.="<tr><td height=\"20\" align=\"left\" valign=\"top\" class=\"leftlink\"><strong>".$Group['first_name']."</strong></td> </tr>";
						 $resecho.="<tr> <td align=\"left\" class=\"blacklink\">".$Group['answer']." </td> </tr>";
						 $resecho.="</table></td> </tr>";
						 $resecho.="<tr>  <td height=\"15\" align=\"left\">&nbsp;</td> <td align=\"center\">&nbsp;</td> </tr>";
						 $togIndex++;
					 }
			 }else{
			  $resecho.="<tr><td height=\"15\" colspan=\"2\" align=\"left\" class=\"blacklink\"> No Records</td></tr>";
                              
			 }
            //  $rsecho.="<tr> <td height=\"19\" align=\"left\">&nbsp;</td><td align=\"center\">&nbsp;</td></tr>";
              $resecho.="</table></td> </tr>";
              $resecho.=" <tr> <td height=\"30\" align=\"right\">&nbsp;</td><td align=\"left\" valign=\"middle\" class=\"blacklink\">".$funmp."</td> </tr>";          
              $resecho.="<tr><td height=\"100%\" align=\"right\">&nbsp;</td>  <td height=\"100%\"  >&nbsp;</td></tr>";                  
              $resecho.=" </table>";
			  echo $rsecho."|".$resecho;
	   // echo $rsecho;
        //echo $resecho;
		exit;
		
			
			
	break;
	
	
	case "openion_list":
	 		 $qid= $_REQUEST['questid'];
			 $limit			=	$_POST["limit"] ? $_POST["limit"] : "10";
			 $pageNo 		= 	$_POST["pageNo"] ? $_POST["pageNo"] : "0";
			 $orderBy			=	$_REQUEST["orderBy"] 	? $_REQUEST["orderBy"] 	: "";
			 $quest = $objUser->getQuestion($qid);
			 list($fanswer, $funmp, $cnt, $limitList)=$objUser->firstAnswerDetailsAjax($_REQUEST['pageNo'],  $limit, $par, ARRAY_A, $_REQUEST['orderBy'],$qid,0,0,0,0);
			 $togIndex = 1;
			 
			 
		 
			 
			 $rsecho.=" <table width=\"100%\" border=\"0\" style=\"height:100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"table-gray\">";
                $rsecho.="<tr>";
              $rsecho.="<td width=\"7%\" height=\"36\" align=\"right\"><img src=\"{$global[tpl_url]}/images/arrow_graybox.jpg\" width=\"13\" height=\"11\" alt=\"\"/></td>";
              $rsecho.="<td width=\"93%\" align=\"left\" class=\"headingtext6\">".$quest['question']."</td>";
							 $rsecho.="<input name=\"qid\" id=\"qid\" type=\"hidden\" value=\"".$quest['id']."\">";
                 $rsecho.="</tr>";
                 $rsecho.="<tr>";
                    $rsecho.="<td height=\"20\" align=\"right\">&nbsp;</td>";
                    $rsecho.="<td align=\"left\" class=\"headingtext6\"><a href=\"javascript:void(0)\" onclick=\"javascript:showPopup('answer_pop',event,'slideDivAnswer','".$Group['user_id']."');return false;\" class=\"blacktext2\"><img src=\"{$global[tpl_url]}/images/submit-answer.jpg\"  width=\"111\" height=\"22\" border=\"0\" /></a></td>";
                   $rsecho.="</tr>";
                   $rsecho.="<tr>";
                   $rsecho.="<td height=\"35\" align=\"right\">&nbsp;</td>";
                  $rsecho.="<td class=\"headingtext6\">&nbsp;</td>";
                    $rsecho.="</tr>";
                     $rsecho.="<tr>";
                    $rsecho.="<td height=\"20\" align=\"right\">&nbsp;</td>";
                     $rsecho.="<td height=\"328\" align=\"left\" valign=\"top\" >";
					  $rsecho.="<table width=\"94%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >";
		     foreach($fanswer as $Group) 
		     {
			 
			 
				 $rsecho.="<tr><td width=\"67\" height=\"61\" align=\"left\">";
				 $rsecho.="<table width=\"59\"  border=\"0\" cellpadding=\"0\" cellspacing=\"3\" class=\"border1\" style=\"background-color:#FFFFFF;\"  >";
				 if($Group['image']=='Y')
				 {
				 $rsecho.="<tr><td width=\"51\" height=\"55\" valign=\"top\"><img src=". SITE_URL ."/modules/member/images/userpics/thumb/".$Group['user_id'].jpg ." width=\"51\" height=\"55\" alt=\"\" /></td> </tr>";
				 }else{
				  $rsecho.="<tr><td width=\"51\" height=\"55\" valign=\"top\"><img src=\"{$global[tpl_url]}/images/nophoto_thumb1.jpg\" width=\"51\" height=\"55\" alt=\"\" /></td> </tr>";
				 }
				 $rsecho.="</table></td><td width=\"246\" align=\"left\" valign=\"top\" class=\"leftlink\">";
				 $rsecho.="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
				 $rsecho.="<tr><td height=\"20\" align=\"left\" valign=\"top\" class=\"leftlink\"><strong>".$Group['first_name']."</strong></td> </tr>";
				 $rsecho.="<tr> <td align=\"left\" class=\"blacklink\">".$Group['answer']." </td> </tr>";
				 $rsecho.="</table></td> </tr>";
				 $rsecho.="<tr>  <td height=\"15\" align=\"left\">&nbsp;</td> <td align=\"center\">&nbsp;</td> </tr>";
				 $togIndex++;
  			 }
              //$rsecho.="<tr> <td height=\"19\" align=\"left\">&nbsp;</td><td align=\"center\">&nbsp;</td></tr>";
              $rsecho.="</table></td> </tr>";
             $rsecho.=" <tr> <td height=\"30\" align=\"right\">&nbsp;</td><td align=\"left\" valign=\"middle\" class=\"blacklink\">".$funmp."</td> </tr>";          
              $rsecho.="<tr><td height=\"100%\" align=\"right\">&nbsp;</td>  <td height=\"100%\"  >&nbsp;</td></tr>";                  
              $rsecho.=" </table>";
			  
			  
      echo $rsecho;
		exit;
			 
	break; 
	
	case "answer_save":
			$answer= $_REQUEST['answer'];
			$uid=$_REQUEST['user_id'];
			$qid=$_REQUEST['questid'];
			$ans          = $objUser->addAnswer($answer,$qid,$uid);
			 $quest = $objUser->getQuestion($qid);
			list($fanswer, $funmp, $cnt, $limitList)=$objUser->AnswerDetailsAjax($_REQUEST['pageNo'], 4, $par, ARRAY_A, $_REQUEST['orderBy'],$qid,0,0,0,0);
					
			$togIndex = 1;
						 
				  $rsecho.=" <table width=\"100%\" border=\"0\" style=\"height:100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"table-gray\">";
				  $rsecho.="<tr>";
				  $rsecho.="<td width=\"7%\" height=\"36\" align=\"right\"><img src=\"{$global[tpl_url]}/images/arrow_graybox.jpg\" width=\"13\" height=\"11\" alt=\"\"/></td>";
				  $rsecho.="<td width=\"93%\" align=\"left\" class=\"headingtext6\">".$quest['question']."</td>";
				  $rsecho.="<input name=\"qid\" id=\"qid\" type=\"hidden\" value=\"".$quest['id']."\">";
                  $rsecho.="</tr>";
                  $rsecho.="<tr>";
				  $rsecho.="<td height=\"20\" align=\"right\">&nbsp;</td>";
                  $rsecho.="<td align=\"left\" class=\"headingtext6\"><a href=\"javascript:void(0)\" onclick=\"javascript:showPopup('answer_pop',event,'slideDivAnswer','".$Group['user_id']."');return false;\" class=\"blacktext2\"><img src=\"{$global[tpl_url]}/images/submit-answer.jpg\"  width=\"111\" height=\"22\" border=\"0\" /></a></td>";
     			  $rsecho.="</tr>";
                  $rsecho.="<tr>";
                  $rsecho.="<td height=\"35\" align=\"right\">&nbsp;</td>";
                  $rsecho.="<td class=\"headingtext6\">&nbsp;</td>";
                  $rsecho.="</tr>";
                  $rsecho.="<tr>";
                  $rsecho.="<td height=\"20\" align=\"right\">&nbsp;</td>";
                  $rsecho.="<td height=\"328\" align=\"left\" valign=\"top\" >";
				  $rsecho.="<table width=\"94%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >";
		     foreach($fanswer as $Group) 
		     {
			 
			 
				 $rsecho.="<tr><td width=\"67\" height=\"61\" align=\"left\">";
				 $rsecho.="<table width=\"59\"  border=\"0\" cellpadding=\"0\" cellspacing=\"3\" class=\"border1\" style=\"background-color:#FFFFFF;\"  >";
				 if($Group['image']=='Y')
				 {
				 $rsecho.="<tr><td width=\"51\" height=\"55\" valign=\"top\"><img src=". SITE_URL ."/modules/member/images/userpics/thumb/".$Group['user_id'].jpg ." width=\"51\" height=\"55\" alt=\"\" /></td> </tr>";
				 }else{
				  $rsecho.="<tr><td width=\"51\" height=\"55\" valign=\"top\"><img src=\"{$global[tpl_url]}/images/nophoto_thumb1.jpg\" width=\"51\" height=\"55\" alt=\"\" /></td> </tr>";
				 }
				 				 $rsecho.="</table></td><td width=\"246\" align=\"left\" valign=\"top\" class=\"leftlink\">";
				 $rsecho.="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
				 $rsecho.="<tr><td height=\"20\" align=\"left\" valign=\"top\" class=\"leftlink\"><strong>".$Group['first_name']."</strong></td> </tr>";
				 $rsecho.="<tr> <td align=\"left\" class=\"blacklink\">".$Group['answer']." </td> </tr>";
				 $rsecho.="</table></td> </tr>";
				 $rsecho.="<tr>  <td height=\"15\" align=\"left\">&nbsp;</td> <td align=\"center\">&nbsp;</td> </tr>";
				 $togIndex++;
  			 }
              //$rsecho.="<tr> <td height=\"19\" align=\"left\">&nbsp;</td><td align=\"center\">&nbsp;</td></tr>";
              $rsecho.="</table></td> </tr>";
             $rsecho.=" <tr> <td height=\"30\" align=\"right\">&nbsp;</td><td align=\"left\" valign=\"middle\" class=\"blacklink\">".$funmp."</td> </tr>";          
              $rsecho.="<tr><td height=\"100%\" align=\"right\">&nbsp;</td>  <td height=\"100%\"  >&nbsp;</td></tr>";                  
              $rsecho.=" </table>";
			  
			  
        echo $rsecho;
		exit;
			
	
	break;
	case "change_question":
			$qid=$_REQUEST['questid'];
			$quest = $objUser->getQuestion($qid);
			list($fanswer, $funmp, $cnt, $limitList)=$objUser->firstAnswerDetailsAjax($_REQUEST['pageNo'], 4, $par, ARRAY_A, $_REQUEST['orderBy'],$qid,0,0,0,0);
			
			$togIndex = 1;
						 
				  $rsecho.=" <table width=\"100%\" border=\"0\" style=\"height:100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"table-gray\">";
				  $rsecho.="<tr>";
				  $rsecho.="<td width=\"7%\" height=\"36\" align=\"right\"><img src=\"{$global[tpl_url]}/images/arrow_graybox.jpg\" width=\"13\" height=\"11\" alt=\"\"/></td>";
				  $rsecho.="<td width=\"93%\" align=\"left\" class=\"headingtext6\">".$quest['question']."</td>";
				  $rsecho.="<input name=\"qid\" id=\"qid\" type=\"hidden\" value=\"".$quest['id']."\">";
                  $rsecho.="</tr>";
                  $rsecho.="<tr>";
				  $rsecho.="<td height=\"20\" align=\"right\">&nbsp;</td>";
                  $rsecho.="<td align=\"left\" class=\"headingtext6\"><a href=\"javascript:void(0)\" onclick=\"javascript:showPopup('answer_pop',event,'slideDivAnswer','".$Group['user_id']."');return false;\" class=\"blacktext2\"><img src=\"{$global[tpl_url]}/images/submit-answer.jpg\"  width=\"111\" height=\"22\" border=\"0\" /></a></td>";
     			  $rsecho.="</tr>";
                  $rsecho.="<tr>";
                  $rsecho.="<td height=\"35\" align=\"right\">&nbsp;</td>";
                  $rsecho.="<td class=\"headingtext6\">&nbsp;</td>";
                  $rsecho.="</tr>";
                  $rsecho.="<tr>";
                  $rsecho.="<td height=\"20\" align=\"right\">&nbsp;</td>";
                  $rsecho.="<td height=\"328\" align=\"left\" valign=\"top\" >";
				  $rsecho.="<table width=\"94%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >";
				 if($fanswer!=''){
					 foreach($fanswer as $Group) 
					 {
					 	 $rsecho.="<tr><td width=\"67\" height=\"61\" align=\"left\">";
						 $rsecho.="<table width=\"59\"  border=\"0\" cellpadding=\"0\" cellspacing=\"3\" class=\"border1\" style=\"background-color:#FFFFFF;\"  >";
						 if($Group['image']=='Y')
						 {
						 $rsecho.="<tr><td width=\"51\" height=\"55\" valign=\"top\"><img src=". SITE_URL ."/modules/member/images/userpics/thumb/".$Group['user_id'].jpg ." width=\"51\" height=\"55\" alt=\"\" /></td> </tr>";
						 }else{
						  $rsecho.="<tr><td width=\"51\" height=\"55\" valign=\"top\"><img src=\"{$global[tpl_url]}/images/nophoto_thumb1.jpg\" width=\"51\" height=\"55\" alt=\"\" /></td> </tr>";
						 }
						 $rsecho.="</table></td><td width=\"246\" align=\"left\" valign=\"top\" class=\"leftlink\">";
						 $rsecho.="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
						 $rsecho.="<tr><td height=\"20\" align=\"left\" valign=\"top\" class=\"leftlink\"><strong>".$Group['first_name']."</strong></td> </tr>";
						 $rsecho.="<tr> <td align=\"left\" class=\"blacklink\">".$Group['answer']." </td> </tr>";
						 $rsecho.="</table></td> </tr>";
						 $rsecho.="<tr>  <td height=\"15\" align=\"left\">&nbsp;</td> <td align=\"center\">&nbsp;</td> </tr>";
						 $togIndex++;
					 }
			 }else{
			  $rsecho.="<tr><td height=\"15\" colspan=\"2\" align=\"left\" class=\"blacklink\"> No Records</td></tr>";
                              
			 }
            //  $rsecho.="<tr> <td height=\"19\" align=\"left\">&nbsp;</td><td align=\"center\">&nbsp;</td></tr>";
              $rsecho.="</table></td> </tr>";
              $rsecho.=" <tr> <td height=\"30\" align=\"right\">&nbsp;</td><td align=\"left\" valign=\"middle\" class=\"blacklink\">".$funmp."</td> </tr>";          
              $rsecho.="<tr><td height=\"100%\" align=\"right\">&nbsp;</td>  <td height=\"100%\"  >&nbsp;</td></tr>";                  
              $rsecho.=" </table>";
			  
			  
        echo $rsecho;
		exit;
	break;
	
	
		
	case "":
		//$_REQUEST["uid"]=$getId;
		//	print_r($_REQUEST);
		$framework->tpl->assign("LEFTBOTTOM","myprofile" );
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			if(isset($_POST["comment"]))
			{
				checkLogin();
				$req = &$_REQUEST;

				if( ($message = $objUser->insertComment($req,$_REQUEST[uid])) === true )
				{
					if($_REQUEST[detprf]==1){
						redirect(makeLink(array('mod'=>member,'pg'=>profile), "act=more&uid=$_REQUEST[uid]"));
						exit;
					}else{
						redirect(makeLink(array('mod'=>member,'pg'=>profile),"uid=$getId"));
					}
				}
			}
			elseif(isset($_POST["tip"]))
			{
				checkLogin();
				//

				$arr = array();
				$arr["user_id"]=$getId;
				$arr["tiptext"]=$_POST["tip"];
				$objUser->setArrData($arr);
				//print_r($arr);exit;
				if(!$objUser->insertTip())
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
		$uid=$getId;



		list($rs1, $numpad1,$count) = $objUser->getMessage($_REQUEST['pageNo'], 5, "uid=$uid&mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $orderBy,$uid);
		$framework->tpl->assign("COMMENT_COUNT",$count);
		###to list private users comments (Link 54)
		### Modified on 4 Jan 2008.
		### Modified By Jinson.


		if($global['show_private']=='Y')
		{ for ($i=0;$i<sizeof($rs1);$i++)
		{
			$medet=$objUser->getUsernameDetails($rs1[$i]->username);
			if($medet["user_id"]==$_SESSION["memberid"])
			{$rs1[$i]->owner="Y";}
			if($medet["mem_type"]==3){
				if($medet["friends_can_see"]=='Y')
				{
					if($objUser->isFriends($medet["user_id"],$_SESSION["memberid"])==true)
					{$rs1[$i]->show_profile="Y";}
					else{$rs1[$i]->show_profile="N";}
				}
				else{
					$rs1[$i]->show_profile="N";
				}
				if($medet["user_id"]==$_SESSION["memberid"]){
					$rs1[$i]->show_profile="Y";
				}

			}
			else{$rs1[$i]->show_profile="Y";}

		}
		}//if

		/////////////////////////
		//print_r($rs1);

		if($global['show_screen_name_only']=='1'){
			for($k=0;$k<count($rs1);$k++){

				$sn=$objUser->getUsernamedetails($rs1[$k]->username);
				$rs1[$k]->screen_name=$sn['screen_name'];
			}
		}
		$num_pages=ceil($count/5);
		if ($num_pages>1)
		{
			if ($_REQUEST["pageNo"])
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
			else
			{
				$pgNo=2;
				$framework->tpl->assign("NEXT_PG", $pgNo);
			}
		}

		$framework->tpl->assign("COMMENT_LIST", $rs1);
		$framework->tpl->assign("COMMENT_NUMPAD", $numpad1);

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

		//list($rs1,$count) = $objUser->getTip($start,$end,$uid);
		if($global['show_screen_name_only']=='1'){
			for($k=0;$k<count($rs1);$k++){

				$sn=$objUser->getUsernamedetails($rs1[$k]->username);
				$rs1[$k]->screen_name=$sn['screen_name'];
			}
		}
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
		if($objAlbum->getSelectedOptions($_SESSION["memberid"]))
		{
			$rs2=$objAlbum->getSelectedOptions($_SESSION["memberid"]);
			$framework->tpl->assign("OPT_LIST", $rs2);
		}
		else
		{
			$objAlbum->setStatusDefault($_SESSION["memberid"]);
			$rs2=$objAlbum->getSelectedOptions($_SESSION["memberid"]);
			$framework->tpl->assign("OPT_LIST", $rs2);
		}
		$framework->tpl->assign("TIP_LIST", $rs1);
		//print_r($rs1);
		//$framework->tpl->assign("TIP_NUMPAD", $numpad1);
		$framework->tpl->assign("RAND",rand());
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_main.tpl");

		break;

	case "update_members_rank":





		//   $memberid =    $_SESSION["memberid"];
		$isbroker='isbroker';
		$isseller='isseller';
		$ispopertymanager='ispopertymanager';

		//this section for setting score to the broker

		$brokerrate = $objUser->allUsers($isbroker,'Y');
		if(count($brokerrate ))
		{
			foreach ($brokerrate as $brate)
			{

				$type="Broker";
				$byearearn=$broker->getBrokerOrManagerYearlySum($brate->id,'BROKER_COMMISION');
				$bmrate=$objAlbum->getBrokerRate($brate->id,$type);

				// echo $bmrate['rate'];
				$score=$bmrate['rate']*$byearearn['year_amount'];
				$score=round($score);

				$scores=array();
				$scores["id"]= $brate->id;
				$scores["broker_score"]= $score;

				$objUser->setArrData($scores);
				$objUser->update();
			}
		}

		//this section for setting rank to the broker
		$broker = $objUser->allUsers($isbroker,'Y','','broker_score Desc');

		if(count($broker))
		{
			$i=1;
			foreach ($broker as $brank)
			{
				$bscore=$brank->broker_score;


				$rank=array();
				$rank["id"]= $brank->id;
				if($bscore==0)
				{
					$rank["broker_rank"]= 0;
				}else{
					$rank["broker_rank"]= $i;
				}
				$objUser->setArrData($rank);
				$objUser->update();
				$i++;
			}

		}

		//this section for setting score to the manager
		$managerrate = $objUser->allUsers($ispopertymanager,'Y');
		if(count($managerrate))
		{
			foreach ($managerrate as $mrate)
			{
				$type="Manager";
				$myearearn=$broker->getBrokerOrManagerYearlySum($mrate->id,'MANAGER_COMMISION');
				$bmrate=$objAlbum->getBrokerRate($mrate->id,$type);
				$score=$bmrate['rate']*$myearearn['year_amount'];
				$score=round($score);

				$scores=array();
				$scores["id"]= $mrate->id;
				$scores["manager_score"]= $score;

				$objUser->setArrData($scores);
				$objUser->update();

			}
		}

		//this section for setting rank to the manager
		$manager = $objUser->allUsers($ispopertymanager,'Y','','manager_score Desc');
		if(count($manager))
		{
			$i=1;
			foreach ($manager as $mrank)
			{
				$mscore=$mrank->manager_score;
				$rank=array();
				$rank["id"]= $mrank->id;
				if($mscore==0)
				{
					$rank["manager_rank"]= 0;
				}else{
					$rank["manager_rank"]= $i;
				}
				$objUser->setArrData($rank);
				$objUser->update();
				$i++;
			}
		}

		//this section for setting score to the seller

		$sellerrate = $objUser->allUsers($isseller,'Y');
		if(count($sellerrate))
		{
			foreach ($sellerrate as $srate)
			{
				$type="seller";
				$syearearn=$broker ->getBrokerOrManagerYearlySum($srate->id,$type);
				$bmrate=$objAlbum->getBrokerRate($srate->id,$type);
				$score=$bmrate['rate']*$syearearn['year_amount'];
				$score=round($score);

				$scores=array();
				$scores["id"]= $srate->id;
				$scores["seller_score"]= $score;

				$objUser->setArrData($scores);
				$objUser->update();

			}

		}

		//this section for setting rank to the manager
		$seller = $objUser->allUsers($isseller,'Y','','seller_score Desc');
		if(count($seller))
		{
			$i=1;
			foreach ($seller as $srank)
			{
				$sscore=$srank->seller_score;
				$rank=array();
				$rank["id"]= $srank->id;
				if($sscore==0)
				{
					$rank["seller_rank"]= 0;
				}else{
					$rank["seller_rank"]= $i;
				}
				$objUser->setArrData($rank);
				$objUser->update();
				$i++;
			}
		}



		break;

	case "update_property_rank":

		$propid = $objAlbum->getalbumid();
		if (count($propid)){
			foreach ($propid as $albmid)
			{

				$prrate=$objAlbum->getPropertyRate($albmid['album_id']);
				$yearearn=$objAlbum->getPropertyYearlyEarnings($albmid['album_id']);
				$score=$prrate['rate']*$yearearn['yearamt'];
				$score=round($score);
				$property->updatePropertyScore($albmid['album_id'], $score);

			}

			$property->updatePropertyRank();


		}

		break;

}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>