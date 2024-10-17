<?php 
session_start();
if(isset($_SESSION['memberid'])){
    $userID=$_SESSION['memberid'];
}
include_once(FRAMEWORK_PATH."/modules/blog/lib/class.blog.php");
include_once(FRAMEWORK_PATH."/modules/blog/lib/class.rss.php");
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
$objCms = new Cms();
$blog = new Blog();
$framework->tpl->assign("LEFTBOTTOM",'blog');
if($_REQUEST["leftid"]==0){
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
	}elseif($_REQUEST["leftid"]==4){
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("rest_right"));// to select the right menu of this page from database.
	}
$blog = new Blog();
$rssFeed  = new RSS($blog,$objUser);

if(isset($_REQUEST['subcat_id'])){
    $subcat_id=$_REQUEST['subcat_id'];
}
if(isset($_REQUEST['parent_id'])){
    $parent_id=$_REQUEST['parent_id'];
}
$additionalVar="id=".$_REQUEST['id']."&user_id=".$_REQUEST['user_id']."&parent_id=".$_REQUEST['parent_id']."&subcat_id=".$_REQUEST['subcat_id'];

switch($_REQUEST['act']) {
    case "list":
        if(isset($_REQUEST['user_id'])){
            $user_id=$_REQUEST['user_id'];
        }
        include(FRAMEWORK_PATH."/modules/blog/lib/blog_customize.php");
        $Date=date("Y-m-d H:i:s");
        $framework->tpl->assign("CUR_DATE", $Date);
        $framework->tpl->assign("USER_ID", $userID);
        $framework->tpl->assign("BLOGUSER_ID", $user_id);
        if($userID!=""){
            $framework->tpl->assign("SUBSCRIBE_BLOG", $blog->getSubscribedblog($_REQUEST['id']));
        }
        $framework->tpl->assign("CAT_NAME", $blog->getCatname($_REQUEST['parent_id']));
        $framework->tpl->assign("SUBCAT_NAME", $blog->getCatname($_REQUEST['subcat_id']));
        $framework->tpl->assign("SUBCAT_ID", $subcat_id);
        $framework->tpl->assign("PARENT_ID", $parent_id);	
		$blogdet=$blog->getBlogentrydetails($_REQUEST['id']);
        $framework->tpl->assign("BLOG_ENTRY_DETAILS",$blogdet);
        list($blog_rs, $numpad) = $blog->blogCommentsList($_REQUEST['id'],$_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&$additionalVar", OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("BLOG_COMMENTS", $blog_rs);
        $framework->tpl->assign("COMMENTS_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/blogs_usercomments.tpl");
        break;
    case "form":
	if((isset($_REQUEST['addcomments']))&&($_REQUEST['addcomments']!="")){
			if(isset($_REQUEST['blog_user_id'])){
				$blog_user_id=$_REQUEST['blog_user_id'];
			}
			if(!isset($_SESSION['memberid'])){
				checkLogin();
			}
			include("blog_customize.php");
			$framework->tpl->assign("BLOG_ENTRY_DETAILS", $blog->getBlogentrydetails($_REQUEST['id']));
			list($blog_rs, $numpad) = $blog->blogCommentsList($_REQUEST['id'],$_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
			$framework->tpl->assign("BLOG_COMMENTS", $blog_rs);
			$framework->tpl->assign("COMMENTS_NUMPAD", $numpad);
			$Date=date("Y-m-d H:i:s");
			$framework->tpl->assign("CUR_DATE", $Date);
			$framework->tpl->assign("USER_ID", $userID);
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				$req = &$_REQUEST;
				$id=$req['id'];
				if( ($message = $blog->blogcommentsAdd($req)) === true ) {
					redirect(makeLink(array("mod"=>"blog", "pg"=>"blog_usercomments"),"act=list&id=$id&user_id=$blog_user_id&subcat_id=$subcat_id&parent_id=$parent_id"));
				}
				$framework->tpl->assign("MESSAGE", $message);
			}
	
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/blogs_usercomments.tpl");
		}
        break;
    case "delete":
        $blog_entry_id=$_REQUEST['blog_entry_id'];
        $blog->blogcommentsDelete($_REQUEST['id']);
        redirect(makeLink(array("mod"=>"blog", "pg"=>"blog_comments"),"act=list&id=$blog_entry_id"));
        break;
    case "rating":
        checkLogin();
        if(isset($_SESSION['memberid'])){
            $userID=$_SESSION['memberid'];
        }
        Rating($userID,$rateval,$_REQUEST['id'],'blog','blog_post','blog_rating');
        $additionalUrl= "user_id=".$_REQUEST['user_id']."&subcat_id=$subcat_id&parent_id=$parent_id&id=".$_REQUEST['id'];
        redirect(makeLink(array("mod"=>"blog", "pg"=>"blog_usercomments"),"act=list&$additionalUrl"));
        break;
	case "rssfeed":
	
		$RSSFeedData = $rssFeed->generateRSSFeed($_REQUEST['member_id'],$_REQUEST['id']);
		header("Content-Type: text/xml");
		print $RSSFeedData;
		exit;
		
	break;
	case "rss_bookmark":
	
		$member_id			=	$_REQUEST['member_id'];
		require_once SITE_PATH.'/includes/Rss/class.rsspublish.php';
		$RsspublisherObj	=	new Rsspublisher();
		
		//$FeedURL			=	SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"preview"), "act=rss_flyerlist&member_id=$member_id");
		$FeedURL			=	SITE_URL.'/'.makeLink(array("mod"=>"blog", "pg"=>"blog_usercomments"), "act=rssfeed&id=".$_REQUEST['id']."&member_id=".$_REQUEST['user_id']."&parent_id=".$_REQUEST['parent_id']);
		
		$WidgetHTML			=	$RsspublisherObj->getWidget('ALL', $FeedURL);
		$framework->tpl->assign("BOOKMARK_WIDGET", $WidgetHTML);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/rssbookmark.tpl");
	break;

}
if($_REQUEST['pg']== "blog_usercomments" && $_REQUEST['act'] == "rss_bookmark"){
	$framework->tpl->display($global['curr_tpl']."/inner.tpl");
}
else
{
$framework->tpl->display(SITE_PATH."/modules/blog/tpl/blogframe.tpl");
}
?>