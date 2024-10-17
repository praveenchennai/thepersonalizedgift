<?php 
session_start();
if(isset($_REQUEST['user_id'])){
	$user_id=$_REQUEST['user_id'];
	}else if(isset($_SESSION['memberid'])){
		$user_id=$_SESSION['memberid'];
	}else{
		redirect("index.php");
	}
include_once(FRAMEWORK_PATH."/modules/blog/lib/class.blog.php");
$blog = new Blog();
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
$objCms = new Cms();
$blog = new Blog();
$framework->tpl->assign("LEFTBOTTOM",'blog');
if($_REQUEST["leftid"]==0){
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
	}elseif($_REQUEST["leftid"]==4){
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("rest_right"));// to select the right menu of this page from database.
	}
$strMessage="";
switch($_REQUEST['act']) {				
    case "form":
		$additionalUrl=SITE_URL."/".makeLink(array("mod"=>"blog", "pg"=>"blog_userentry"), "act=list&user_id=".$user_id."&subcat_id=".$_REQUEST['subcat_id']."&parent_id=".$_REQUEST['parent_id']);	
		$framework->tpl->assign("CUR_DATE", $Date);
		$framework->tpl->assign("USER_ID", $user_id);			
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			$req = &$_REQUEST;
			$rec_email=$req['rec_email'];
			$from_email=$req['from_email'];
			$subject=$req['subject'];
			$message=$req['message'];
			$strMessage='  '.$message.'<br><br>'.'<a href='.$additionalUrl.'>$additionalUrl</a>';
			$send=sendMail($rec_email, $subject, $strMessage, $from_email, $mailFormat='TEXT');
			if($send){
            print_r($send); 			
				$framework->tpl->assign("STAT_MESSAGE", "Mail sent successfully");
			}
		}
		   $framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/blogemail_form.tpl");		
        break;
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");

?>