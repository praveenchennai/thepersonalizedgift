<?php 
session_start();
checkLogin();
if(isset($_SESSION['memberid'])){
	$user_id=$_SESSION['memberid'];
}
include_once(FRAMEWORK_PATH."/modules/blog/lib/class.blog.php");
$blog = new Blog();
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
$objCms = new Cms();
$blog = new Blog();
if($_REQUEST["leftid"]==0){
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
	}elseif($_REQUEST["leftid"]==4){
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("rest_right"));// to select the right menu of this page from database.
	}
switch($_REQUEST['act']) {
	case "list":		
		list($sub_rs, $numpad) = $blog->blogsubscribeList($user_id,$_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
		$framework->tpl->assign("BLOG_SUBSCRIBE", $sub_rs);
		$framework->tpl->assign("SUBSCRIBE_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/blogsubscribe_list.tpl");	
		break; 
	case "subscribe":
		checkLogin();
			$res=$blog->Subscribe($_SESSION["memberid"],$_REQUEST['blog_userid'],"blog");
			//print_r($res);exit;
			if($res!="true"){
				setMessage($blog->getErr());	
			}
		$urlAppent= "id=".$_REQUEST['blog_id']."&user_id=".$_REQUEST['blog_userid']."&subcat_id=".$blog_details['subcat_id']."&parent_id=".$blog_details['cat_id'];
		$blog->blogSubscribe($user_id,$_REQUEST['blog_id']);		
			redirect(makeLink(array("mod"=>"blog", "pg"=>"blog_usercomments"),"act=list&$urlAppent"));
		break;	
		case "unsubscribe":
		$res=$blog->Unsubscribe($_SESSION["memberid"],$_REQUEST['blog_userid'],"blog");
		$blog_details=$blog->getBlogdetails($_REQUEST['blog_id']);		
		$urlAppent= "id=".$_REQUEST['blog_id']."&user_id=".$_REQUEST['blog_userid']."&subcat_id=".$blog_details['subcat_id']."&parent_id=".$blog_details['cat_id'];
		$blog->blogUnsubscribe($_REQUEST['blog_id']);		
			redirect(makeLink(array("mod"=>"blog", "pg"=>"blog_usercomments"),"act=list&$urlAppent"));
		break; 
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>