<?php 
/**
 * Admin section Index page
 *
 * @author Ajith
 * @package defaultPackage
 */
 include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
 include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
 $cms 		=  new Cms();
 $user		=  new User();
switch($_REQUEST['act']) {   
    case "feedback":
		$user_id	=  $storeDetails['user_id'];
		$userDetails	=	$user->getUserdetails($user_id);
		if ($_SERVER['REQUEST_METHOD']=="POST"){			
			$arr1 = array();
				$msg="<div style='padding-left: 25px; padding-right: 25px;'>";
				$msg=$msg.$userDetails['first_name']." ".$userDetails['last_name'].", <br>";
				$msg=$msg."<br>";
				$msg=$msg."<p>".$_REQUEST['message']."</p>";
				$msg=$msg."<br>";				
				$msg=$msg."</div>";
				$arr1["from"]     = $_REQUEST['first_name']." ".$_REQUEST['last_name'];
				$arr1["to"]       = $userDetails['email'];
				$arr1["subject"]  = "Comments";
				$arr1["comments"] =  addslashes($msg);
				$arr1["datetime"] = date("Y-m-d H:i:s");
				$arr1["email"]	  = $_REQUEST['email'];											
				mimeMail($arr1["to"],$arr1["subject"] ,$msg,'','',$arr1["from"].'<'.$arr1["email"].'>');
			}
			$framework->tpl->assign("USER_DETAILS", $userDetails['first_name']." ".$userDetails['last_name']);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/feedback.tpl");
	break;
	case "questions":
	if ($_SERVER['REQUEST_METHOD']=="POST"){		
			unset($_POST["x"],$_POST["y"]);
			$arr1 = array();
				$msg="<div style='padding-left: 25px; padding-right: 25px;'>";
				$msg=$msg."Dear Admin, <br>";
				$msg=$msg."<br>";
				$msg=$msg."<b>".$_REQUEST['sermon']."</b>";
				$msg=$msg."<p>".$_REQUEST['message']."</p>";
				$msg=$msg."<br>";				
				$msg=$msg."</div>";
				$arr1["from"]     = $_REQUEST['first_name']." ".$_REQUEST['last_name'];
				$arr1["to"]       = $framework->config['admin_email'];
				$arr1["subject"]  = "Questions";
				$arr1["comments"] =  addslashes($msg);
				$arr1["datetime"] = date("Y-m-d H:i:s");
				$arr1["email"]	  = $_REQUEST['email'];								
				mimeMail($arr1["to"],$arr1["subject"] ,$msg,'','',$arr1["from"].'<'.$arr1["email"].'>');
			}
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/questions_form.tpl");
	break;	
	case "cms":
		$menu_id		=	$_REQUEST['menu_id'];
		$pageDetails	=	$cms->cmspageGet($menu_id);
		$framework->tpl->assign("CMS", $pageDetails);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/cmspage.tpl");
	break;
	case "cmschurch":		
		$assignVal		=	"";		
		$cmval			=	$_REQUEST['cmval'];
		$mem_id			=	$_REQUEST['user_id'];
		if($cmval=='c'){			
			$userDetails	=	$user->getUserdetails($mem_id);			
			$assignVal		=	$assignVal.$userDetails[chr_name]."<br>";
			$assignVal		=	$assignVal.$userDetails[address1]."<br>";
			$assignVal		=	$assignVal.$userDetails[city]."<br>";
			$assignVal		=	$assignVal.$userDetails[state]."<br>";
			$assignVal		=	$assignVal.$userDetails[country_name]."<br>";
			$assignVal		=	$assignVal.$userDetails[email]."<br>";
			$assignVal		=	$assignVal.$userDetails[chr_url]."<br>";
			$heading		=	"Contact US";
		}else{
			$pageDetails	=	$cms->cmspageGet(19);
			$assignVal		=	$pageDetails['content'];
			$heading		=	"About US";
		}
		$framework->tpl->assign("CONTACT", $assignVal);
		$framework->tpl->assign("HEADING", $heading);		
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/church_cmspage.tpl");
	break;
	case "feedbackchurch":
		$mem_id			=	$_REQUEST['user_id'];
		$userDetails	=	$user->getUserdetails($mem_id);
		if ($_SERVER['REQUEST_METHOD']=="POST"){		
			unset($_POST["x"],$_POST["y"]);
			$arr1 = array();
				$msg="<div style='padding-left: 25px; padding-right: 25px;'>";
				$msg=$msg."Dear Admin, <br>";
				$msg=$msg."<br>";
				$msg=$msg."<p>".$_REQUEST['message']."</p>";
				$msg=$msg."<br>";				
				$msg=$msg."</div>";
				$arr1["from"]     = $_REQUEST['first_name']." ".$_REQUEST['last_name'];
				$arr1["to"]       = $userDetails['email'];
				$arr1["subject"]  = "Feed Back";
				$arr1["comments"] =  addslashes($msg);
				$arr1["datetime"] = date("Y-m-d H:i:s");
				$arr1["email"]	  = $_REQUEST['email'];								
				mimeMail($arr1["to"],$arr1["subject"] ,$msg,'','',$arr1["from"].'<'.$arr1["email"].'>');
			}
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/church_feedback.tpl");
	break;
	case "questionchurch":
		$mem_id			=	$_REQUEST['user_id'];
		$userDetails	=	$user->getUserdetails($mem_id);
	if ($_SERVER['REQUEST_METHOD']=="POST"){		
			unset($_POST["x"],$_POST["y"]);
			$arr1 = array();
				$msg="<div style='padding-left: 25px; padding-right: 25px;'>";
				$msg=$msg."Dear Admin, <br>";
				$msg=$msg."<br>";
				$msg=$msg."<b>".$_REQUEST['sermon']."</b>";
				$msg=$msg."<p>".$_REQUEST['message']."</p>";
				$msg=$msg."<br>";				
				$msg=$msg."</div>";
				$arr1["from"]     = $_REQUEST['first_name']." ".$_REQUEST['last_name'];
				$arr1["to"]       = $userDetails['email'];
				$arr1["subject"]  = "Feed Back";
				$arr1["comments"] =  addslashes($msg);
				$arr1["datetime"] = date("Y-m-d H:i:s");
				$arr1["email"]	  = $_REQUEST['email'];								
				mimeMail($arr1["to"],$arr1["subject"] ,$msg,'','',$arr1["from"].'<'.$arr1["email"].'>');
			}
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/church_questions.tpl");
	break;	
}
if($_REQUEST['act']=='cmschurch' || $_REQUEST['act']=="questionchurch" || $_REQUEST['act']=="feedbackchurch"){
	$framework->tpl->display($global['curr_tpl']."/innerwhite.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/inner.tpl");
}	

?>