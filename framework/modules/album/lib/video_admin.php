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

$album= new Album();
$objPhoto=new Photo();
$objUser=new User();
$objVideo	 = new Video();
$objCategory = new Category();
$email= new Email();
switch($_REQUEST['act'])
{
	
	
		case "myvideo":
		///////////////////////////////
		
		if ($_REQUEST["CRT"])
		{
			$crt = $_REQUEST["CRT"];
			$framework->tpl->assign("CRT",$crt);
			$framework->tpl->assign("CRTVAR","Wealth");
			$framework->tpl->assign("TIMEVAR",$framework->config['wealthvideo']);
			

		}
		else
		{$framework->tpl->assign("TIMEVAR",$framework->config['healthvideo']);
		$framework->tpl->assign("CRTVAR","Health");
		}
	    ////////////////////////////////
    	$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/album/tpl/myvideo_admin.tpl");
		break;
		
			case "upload":
		///////////////////////////////
		
	         if($_REQUEST["CRT"]!="")
			 {
			$crt = $_REQUEST["CRT"];
			$framework->tpl->assign("CRT",$crt);}
			$time=time();
			if($_SERVER['REQUEST_METHOD'] == "POST")
            {
			    if($_REQUEST["CRT"]=="")
				{
				@unlink(SITE_PATH."/modules/album/adminvideo/Health".$framework->config["healthvideo"].".flv");
				$objVideo->updateAdminVideoInfo($time,'healthvideo');
				
				}
				else
				{
				@unlink(SITE_PATH."/modules/album/adminvideo/Wealth".$framework->config["wealthvideo"].".flv");
				$objVideo->updateAdminVideoInfo($time,'wealthvideo');  
				}
			$album->mediaDelete($var,"AdminAlbum");
			$req = $_REQUEST;
			$fname	 =	$_FILES['videoFile']['name'];
            $ftype	 =	$_FILES['videoFile']['type'];
            $fsize	 =	$_FILES['videoFile']['size'];
            $ferror	 =	$_FILES['videoFile']['error'];
            $tmpname =	$_FILES['videoFile']['tmp_name'];
			$fileext=$album->file_extension($fname);
            $dir=SITE_PATH."/modules/album/adminvideo/";
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
				     if(strtolower($fileext) == "3gp") {
                        $_3gp_fix = " -an"; // right now 3gp is not supporting audio format, so it will work only if we strip the audio
                    } else {
                    	$_3gp_fix = "";
                    }
					if($crt!=""){
						$id="Wealth";
						$framework->tpl->assign("CRTVAR",$id);
						}else
						{
						$id="Health";
						$framework->tpl->assign("CRTVAR",$id);
						}
					////////////for link54
					if($framework->config["video_flv"] == "1")
					{
					$timevar=time();
					$framework->tpl->assign("TIME",$$timevar);
					shell_exec("ffmpeg -i {$tmpname} -ab 56 -ar 22050 -f flv{$_3gp_fix} {$dir}{$id}{$time}.flv");
                    chmod("${dir}{$id}.flv",0777);
					
						if($crt!=""){
						$id="Wealth";
						$framework->tpl->assign("CRTVAR",$id);
						$mov = new ffmpeg_movie("${dir}{$id}.flv");
						}else
						{$id="Health";
						$framework->tpl->assign("CRTVAR",$id);
						  $mov = new ffmpeg_movie("${dir}{$id}.flv");
						}
					}
					else
						{if($crt!=""){
						$id="Wealth";
						$framework->tpl->assign("CRTVAR",$id);
						$mov = new ffmpeg_movie("${dir}{$id}.flv");
						}else
						{$id="Health";
						$framework->tpl->assign("CRTVAR",$id);
						  $mov = new ffmpeg_movie("${dir}{$id}.flv");
						}
						
                    shell_exec("ffmpeg -i {$tmpname} -ab 56 -ar 22050 -f flv{$_3gp_fix} {$dir}{$id}{$time}.flv");
                    chmod("${dir}{$id}.flv",0777);
                    $mov = new ffmpeg_movie("${dir}{$id}.flv");
					}
					
               }
                else
                {
                    $message="You have to select one movie file. We support MOV, WMV, MPG, 3GP, DAT, ASX and AVI files.";
                }
				setMessage($message);
				}
				//
				 redirect(makeLink(array("mod"=>"album", "pg"=>"video_admin"), "act=myvideo&CRT=$crt"));
      }
	////////////////////////////////
      	$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/album/tpl/myvideo_upload.tpl");
		break;
}
$framework->tpl->display($global['curr_tpl']."/admin.tpl");
?>