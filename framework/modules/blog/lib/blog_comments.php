<?php 
session_start();
checkLogin();
if(isset($_SESSION['memberid'])){
    $user_id=$_SESSION['memberid'];
}
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
$objCms = new Cms();
if($_REQUEST["leftid"]==0){
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
	}elseif($_REQUEST["leftid"]==4){
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("rest_right"));// to select the right menu of this page from database.
	}
$framework->tpl->assign("LEFTBOTTOM",'blog');
include_once(FRAMEWORK_PATH."/modules/blog/lib/class.blog.php");
$blog = new Blog();
$additionlVar="id=".$_REQUEST['id'];
switch($_REQUEST['act']) {
    case "list":	

        include(SITE_PATH."/modules/blog/lib/blog_customize.php");
        $Date=date("Y-m-d H:i:s");
        $framework->tpl->assign("CUR_DATE", $Date);
        $framework->tpl->assign("USER_ID", $user_id);
        $framework->tpl->assign("BLOG_ENTRY_DETAILS", $blog->getBlogentrydetails($_REQUEST['id']));
        list($blog_rs, $numpad) = $blog->blogCommentsList($_REQUEST['id'],$_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&$additionlVar", OBJECT, $_REQUEST['orderBy']);
       	$framework->tpl->assign("BLOG_COMMENTS", $blog_rs);
        $framework->tpl->assign("COMMENTS_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/blogs_comments.tpl");
        break;
   	   case "form":
   if((isset($_REQUEST['addcomments']))&&($_REQUEST['addcomments']!="")){
			include(SITE_PATH."/modules/blog/lib/blog_customize.php");
			$framework->tpl->assign("BLOG_ENTRY_DETAILS", $blog->getBlogentrydetails($_REQUEST['id']));
			list($blog_rs, $numpad) = $blog->blogCommentsList($_REQUEST['id'],$_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&$additionlVar", OBJECT, $_REQUEST['orderBy']);
			$framework->tpl->assign("BLOG_COMMENTS", $blog_rs);
			$framework->tpl->assign("COMMENTS_NUMPAD", $numpad);
			$Date=date("Y-m-d H:i:s");
			$framework->tpl->assign("CUR_DATE", $Date);
			$framework->tpl->assign("USER_ID", $user_id);
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				$req = &$_REQUEST;
				$id=$req['id'];
				if( ($message = $blog->blogcommentsAdd($req)) === true ) {
					redirect(makeLink(array("mod"=>"blog", "pg"=>"blog_comments"),"act=list&id=$id"));
				}
				$framework->tpl->assign("MESSAGE", $message);
			}
	
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/blogs_comments.tpl");
	}
        break;
    case "delete":
        $blog_entry_id=$_REQUEST['blog_entry_id'];
        $blog->blogcommentsDelete($_REQUEST['id']);
        redirect(makeLink(array("mod"=>"blog", "pg"=>"blog_comments"),"act=list&id=$blog_entry_id"));
        break;
}
$framework->tpl->display(SITE_PATH."/modules/blog/tpl/blogframe.tpl");
?>