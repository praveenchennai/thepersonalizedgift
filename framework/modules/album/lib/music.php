<?php
session_start();
include_once(FRAMEWORK_PATH."/modules/album/lib/class.music.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");


$album= new Album();
$music=new Music();
$user=new User();
$email	 = new Email();


$memberID = $_SESSION['memberid'];
$fileError = array(
1=>"The uploaded file exceeds the maximum allowed file size",
2=>"The uploaded file exceeds the maximum allowed file size",
3=>"The uploaded file was only partially uploaded",
4=>"No file was uploaded",
6=>"Missing a temporary folder"
);

	$framework->tpl->assign("CAT_LIST", $objUser->getCategoryCombo($_REQUEST["mod"]));	
	$framework->tpl->assign("CAT_ARR", $objUser->getCategoryArr($_REQUEST["mod"]));

switch($_REQUEST['act'])
{
	default:
	case "list":
//print_r($_REQUEST);exit;
		if ($_REQUEST["cat_id"])
		{
			
			$catname=$user->getCatName($_REQUEST["cat_id"]);
			$framework->tpl->assign("FILTER",$_REQUEST["cat_id"]);
			$framework->tpl->assign("PH_HEADER", $catname["cat_name"]);
			list($rs, $numpad) = $music->musicList($_REQUEST['pageNo'], 5, "mod={$mod}&pg={$pg}&act=list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}&cat_id={$_REQUEST["cat_id"]}", OBJECT, "id desc", $_REQUEST["cat_id"], $_REQUEST['txtSearch'],$_REQUEST['type']);
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
			list($rs, $numpad) = $music->musicList($_REQUEST['pageNo'], 5, "mod={$mod}&pg={$pg}&act=list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}", OBJECT, $field,0,$_REQUEST['txtSearch'],$_REQUEST['type']);
		}
		else
		{
			$framework->tpl->assign("PH_HEADER", "All Musics");
			list($rs, $numpad) = $music->musicList($_REQUEST['pageNo'], 5, "mod={$mod}&pg={$pg}&act=list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}", OBJECT, 'id desc',0,$_REQUEST['txtSearch'],$_REQUEST['type']);
		}
		
		//print_r($rs);exit;
		$_SESSION["xml_arr"] = $rs;
		$framework->tpl->assign("MUSIC_LIST", $rs);
		$framework->tpl->assign("MUSIC_NUMPAD", $numpad);
		$framework->tpl->assign("RIGT_TPL", SITE_PATH."/modules/album/tpl/right_top_btn.tpl");

		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/music_list.tpl");
		if($global["searchstyle"] == "2") {//for p1musicbox.
			$framework->tpl->display($global['curr_tpl']."/inner2.tpl");
			exit;
		}
		break;
	case "fullscreen":
		include_once(SITE_PATH."/includes/flashPlayer/include.php");
		$framework->tpl->display(SITE_PATH."/modules/album/tpl/fullscreen.tpl");
		exit;
		break;
	case "details":
		include_once(SITE_PATH."/includes/flashPlayer/include.php");
		if($_REQUEST["fn"]!="share")
		{
			$music->incrementView($_REQUEST["music_id"]);
		}
		else
		{
			checkLogin();
			$userinfo = $objUser->getUserdetails($_SESSION["memberid"]);
			$contact  = $objUser->listContacts($userinfo["username"]);
			$framework->tpl->assign("CONTACT",$contact);
			$framework->tpl->assign("USERDET",$userinfo);		
		}
		if($_REQUEST["fn"]=="subscribe")
		{
		$phdet   = $music->getMusicDetails($_REQUEST["music_id"]);
		//print_r();exit;
			checkLogin();
			$res=$album->Subscribe($_SESSION["memberid"],$phdet["user_id"],"music");
			//print_r($res);exit;
			if($res!="true"){
				setMessage($album->getErr());	
			}
		}elseif($_REQUEST["fn"]=="unsubscribe"){
		$phdet   = $music->getMusicDetails($_REQUEST["music_id"]);
			 checkLogin();
			$res=$album->Unsubscribe($_SESSION["memberid"],$phdet["user_id"],"music");
			if($res!="true"){
				setMessage($album->getErr());	
			}
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
									$phid    = $_REQUEST["music_id"];
									$from     = $userinfo["username"];
									
									$comment = $_REQUEST["comments"]."<br><br>";
									$comment = $comment."Click on the link below to view Music<br>";
									$comment = $comment . "<a href=\"".SITE_URL."/".makeLink(array("mod"=>"album", "pg"=>"music"), "act=details&music_id=$phid")."\">View Music</a>";
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
									$phid    = $_REQUEST["music_id"];
									$from    = $userinfo["username"];
									$phdet   = $music->getMusicDetails($_REQUEST["music_id"]);
									//print_r($phdet);exit;

									//$comment = $_REQUEST["comments"]."<br><br>";
									
			  						$message="<div style='padding-left: 25px; padding-right: 25px;'>";
									$message=$message."<h2>I want to share the following Music with you</h2>";
									if($phdet["audio_type"]=='V')
									{
										$message=$message."<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"album", "pg"=>"music"), "act=details&music_id=$phid")."\"><img src='".SITE_URL ."/modules/album/music/thumb/$phid.jpg' border='0' ></a>";
									}
									else
									{
										$message=$message."<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"album", "pg"=>"music"), "act=details&music_id=$phid")."\"><img src='".SITE_URL ."/templates/default/images/play.jpg' border='0'></a>";
									}	
									$message=$message."<h4>Personal Message</h4>";
									$message=$message. "<p>". $_REQUEST["comments"] . "</p>";
									$message=$message."<p>Thanks,<br>";
									$message=$message. $userinfo["first_name"]. " " . $userinfo["last_name"] . "</p>";
									$message=$message."</div>";
									
									mimeMail($arrData["to"],$arrData["subject"],$message,'','','Industrypage.com <'.$framework->config['admin_email'].'>');
									//sendMail($arrData["to"],$arrData["subject"],$message,'Industrypage.com<'.$framework->config['admin_email'].'>','HTML');
							}	
						}
						
						redirect(makeLink(array("mod"=>"album", "pg"=>"music"), "act=details&music_id=".$_REQUEST["music_id"]));
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
					$_POST["type"]     = "music";
					$_POST["user_id"]  = $_SESSION["memberid"];
					$_POST["file_id"]  = $_REQUEST["music_id"];
					$_POST["postdate"] = date("Y-m-d G:i:s");
					unset($_POST["x"],$_POST["y"]);
					$music->setArrData($_POST);
					$music->postComment();
				}	
		}
		if($_REQUEST["rate"])
		{
			checkLogin();
			$array=array();
			$array["type"]    = "music";
			$array["file_id"] = $_REQUEST["music_id"];
			$array["userid"]  = $_SESSION["memberid"];
			$array["mark"]    = $_REQUEST["rate"];
			$music->setArrData($array);
			if(!$music->rateMusic())
			{
				$framework->tpl->assign("MESSAGE",$music->getErr());
			}
			else
			{
				redirect(makeLink(array("mod"=>"album", "pg"=>"music"),"act=list&tpm=5"));
			}
		}
		if($_REQUEST["fn"]=="add")
		{
			checkLogin();
			$array=array();
			$array["type"]    = "music";
			$array["file_id"] = $_REQUEST["music_id"];
			$array["userid"]  = $_SESSION["memberid"];
			$music->setArrData($array);
			if(!$music->addFavorite())
			{
				$msg=$music->getErr();
				$framework->tpl->assign("MESSAGE",$msg);
				setMessage($msg);
				if($global['searchstyle']=="2"){
					redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=favr"));
				}
			}
			else
			{
				redirect(makeLink(array("mod"=>"album", "pg"=>"album"),"act=favr"));
			}
		}
		if($rate=$music->getRating('music',$_REQUEST["music_id"]))
		{
			$framework->tpl->assign("RATE",$rate);
		}
		if($memberID){
			$phdet = $music->getMusicDetails($_REQUEST["music_id"]);
			$sub=$album->chkSubscribe($memberID,$phdet["user_id"],"music");
		}
		$framework->tpl->assign("SUBS",$sub);
		$framework->tpl->assign("PHDET",$phdet);
		$cmCount=$music->getCommentCount($_REQUEST["music_id"]);
		$framework->tpl->assign("COMMENT_COUNT",$cmCount["cnt"]);

		list($rs, $numpad) = $music->commentList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy'], $_REQUEST["music_id"]);
		$framework->tpl->assign("COMMENT_LIST",$rs);
		$framework->tpl->assign("COMMENT_NUMPAD", $numpad);
		
		if($_REQUEST["fn"]=="share")
		{
			$framework->tpl->assign("MEDIA","Music");
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/share_media.tpl");	
		}	
		else
		{
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/music_details.tpl");
		}	
		break;
		
	case "upload":
		checkLogin();
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			
			unset($_POST["image_x"],$_POST["image_y"],$_POST["terms"],$_POST["Submit"]);
			$_POST["user_id"] = $_SESSION["memberid"];	
			$req = $_POST;

			$fname	 =	$_FILES['musicFile']['name'];
			$ftype	 =	$_FILES['musicFile']['type'];
			$fsize	 =	$_FILES['musicFile']['size'];
			$ferror	 =	$_FILES['musicFile']['error'];
			$tmpname =	$_FILES['musicFile']['tmp_name'];

			$fileext=$album->file_extension($fname);

			$dir=SITE_PATH."/modules/album/music/";

			if(!$ferror){
				if(in_array(strtolower($fileext), array('mp3', 'wav', 'mov', 'wmv', 'mpg', 'avi', '3gp', 'dat', 'asx')))
				{
					$mov = new ffmpeg_movie($tmpname);
					$req['dimension_width']  =  $mov->getFrameWidth();
                    $req['dimension_width']  =  $req['dimension_width'] ? $req['dimension_width'] : 320;

                    $req['dimension_height'] =  $mov->getFrameHeight();
                    $req['dimension_height'] =  $req['dimension_height'] ? $req['dimension_height'] : 0;

					$req['filesize']	=	$fsize;
					
					if(strtolower($fileext) == "mp3" || strtolower($fileext) == "wav") {
						$req['audio_type']	=  'A';
					} else {
						$req['audio_type']	=  'V';
					}
					$album->setArrData($req);
					$id = $album->insertMusicDetails();

					if(strtolower($fileext) == "3gp") {
                        $_3gp_fix = " -an"; // right now 3gp is not supporting audio format, so it will work only if we strip the audio
                    } else {
                    	$_3gp_fix = "";
                    }

                    if(strtolower($fileext) == "mp3" || strtolower($fileext) == "wav") {
                    	shell_exec("ffmpeg -i {$tmpname} -ar 44100 -ab 128 -f flv {$dir}{$id}.flv");
                    	chmod("${dir}{$id}.flv", 0777);
                    } else {
                    	shell_exec("ffmpeg -i {$tmpname} -ab 56 -ar 22050 -f flv{$_3gp_fix} {$dir}{$id}.flv");
                    	chmod("${dir}{$id}.flv", 0777);

                    	$mov = new ffmpeg_movie("${dir}{$id}.flv");
                    	$frame = $mov->getFrame(10);
                    	if($frame) {
                    		$frame->resize(110, 80);
                    		$image = $frame->toGDImage();
                    		imagejpeg($image, "${dir}thumb/{$id}.jpg", 100);
                    	}
                    }
					###  Portion to send mail to subscribers................................................
					$mail_header = array();
					$mail_header['from'] 	    = 	$framework->config['admin_email'];
					$toemails					=	$music->getSubscriberEmails($_SESSION["memberid"],"music");
					for($i=0;$i<sizeof($toemails);$i++){
						$mail						=	$toemails[$i]["email"];
						$udet						=	$user->getUserdetails($_SESSION["memberid"]);
						$mail_header["to"]          =	$mail;
						$dynamic_vars               =	array();
						$dynamic_vars["USERNAME"]  	=	$udet["username"];
						$dynamic_vars["SITE"]  		=	$framework->config['site_name'];
						$dynamic_vars["LINK"]       =	"<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"album", "pg"=>"music"), "act=details&music_id=$id")."\">Get Your new Music</a>";
						$email->send("send_to_subscribers",$mail_header,$dynamic_vars);
					
					}
					
		
					### End Portion to send mail to subscribers..............................................

					redirect(makeLink(array("mod"=>"album", "pg"=>"music"), "act=details&music_id=$id"));
				}
				else
				{
					$message="You have to select one music file. We support MP3, WAV, MOV, WMV, MPG, 3GP, DAT, ASX and AVI files.";
				}
			} else {
				$message = $fileError[$ferror];
			}
		}
		$framework->tpl->assign("ERROR_MSG", $message);
		$framework->tpl->assign("SECTION_LIST", $album->albumSectionList());
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/upload_music.tpl");
		break;
	case "add_topic":
		if($_SERVER['REQUEST_METHOD']=='POST' && $_REQUEST['btn_save'])
		{
			///print_r($_POST);
			unset($_POST["btn_save"]);
			checkLogin();
			$_POST["user_id"]=$_SESSION["memberid"];
			$_POST["lastpost"]=date("Y-m-d G:i:s");
			$objUser->setArrData($_POST);
			if(!$objUser->addTopic())
			{
				$framework->tpl->assign("MESSAGE", $objUser->getErr());
			}
			
		}
		list($rs, $numpad) = $objUser->getTopicList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&group_id=".$_REQUEST["group_id"], OBJECT, $_REQUEST['orderBy'],$_REQUEST["group_id"]);
		$framework->tpl->assign("TOPIC_LIST", $rs);
		$framework->tpl->assign("TOPIC_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/add_topic.tpl");
		break;
	case "edit_music":
		checkLogin();
		$id = $_REQUEST['id'];
		if ($id)
		{
			$mdet = $music->getMusicDetails($id);
			if ($mdet['user_id'])
			{
				$udet = $objUser->getUserdetails($mdet['user_id']);
				if ($mdet['id'] == $udet['featured_song_id'])
				{
					$mdet['featured']="Y";
				}
			}
			
			if ($mdet)
			{
				$_REQUEST += $mdet;
			}
			
			
			if ($_SERVER['REQUEST_METHOD']=="POST")
			{

				$dir=SITE_PATH."/modules/album/music/";
				$filetype = $mdet['filetype'];
				if ($framework->config['media_encrypt']==1)
				{
					$file_name = $mdet['media_encrypt_name'];
				}
				else 
				{
					$file_name = $id;
				}
				if ($filetype=="") $filetype="mp3";
				$mov = new ffmpeg_movie($dir.$file_name.".$filetype");
				$start_preview = $_POST['start_preview'];
				if ($start_preview=="") $start_preview=0;
				
				if ($framework->config['media_ffmpeg']==1)
				{
					$featured_duration = $framework->config['featured_song_duration'];
					$other_duration    = $framework->config['other_song_duration']; 
					
					shell_exec("ffmpeg -ss $start_preview -t $featured_duration -i {$dir}{$file_name}.$filetype -acodec copy -vcodec copy -y {$dir}{$file_name}_featured.$filetype");
					shell_exec("ffmpeg -ss $start_preview -t $other_duration -i {$dir}{$file_name}.$filetype -acodec copy -vcodec copy -y {$dir}{$file_name}_other.$filetype");
					
					chmod($dir.$file_name."_featured.".$filetype, 0777);
					chmod($dir.$file_name."_other.".$filetype, 0777);
					//print "yes";
					//print $dir.$file_name."_featured.".$filetype;
					//print "ffmpeg -ss $start_preview -t $featured_duration -i {$dir}{$file_name}.$filetype -acodec copy -vcodec copy -y {$dir}{$file_name}_featured.$filetype";
					//exit;
				}
					
				//ffmpeg -ss 10 -t 60 -i test.avi -acodec copy -vcodec copy -y  test_cut.avi
				$featured = $_POST["featured"];
				unset($_POST['submit'],$_POST['featured']);
				$album->setArrData($_POST);
				$album->editMediaDetails($id,'album_music');
				if ($featured=="Y")
				{
					$arr1 = array();
					$arr1["featured_song_id"] = $id;
					$arr1["id"] = $_SESSION["memberid"];
					$objUser->setArrData($arr1);
					$objUser->update();
				}
				
				
				redirect(makeLink(array("mod"=>"album", "pg"=>"album"), "act=myalbum&crt=M3"));
			}	
			
		}
		$framework->tpl->assign("TITLE_HEAD","Edit Track Details");
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/edit_music.tpl");
		break;
			
}

$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>