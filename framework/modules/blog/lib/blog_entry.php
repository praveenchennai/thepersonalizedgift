<?php 
session_start();
//ini_set("display_errors", "on");
//error_reporting(E_ALL ^ E_NOTICE);
checkLogin();
if(isset($_SESSION['memberid'])){
	$user_id=$_SESSION['memberid'];
}
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
$objCms = new Cms();
$framework->tpl->assign("LEFTBOTTOM",'blog');
if($_REQUEST["leftid"]==0){
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
	}elseif($_REQUEST["leftid"]==4){
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("rest_right"));// to select the right menu of this page from database.
	}
include_once(FRAMEWORK_PATH."/modules/blog/lib/class.blog.php");
$blog = new Blog();
switch($_REQUEST['act']) {
	case "list":		
		include(SITE_PATH."/modules/blog/lib/blog_customize.php");
		$countBlog=$blog->getCountBlog($user_id);		
		$framework->tpl->assign("BLOG_DETAILS", $blog->getBlog($user_id));
		list($blog_rs, $numpad) = $blog->blogentryList($blog_Id,$_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
		$framework->tpl->assign("BLOG_LIST", $blog_rs);
		$framework->tpl->assign("BLOG_NUMPAD", $numpad);
		if($countBlog>0){
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/user_blog.tpl");
		}else{
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/blog_null.tpl");
		}		
		break;			
    case "form":
	$id=$_REQUEST['id'];	
	checkLogin();
	if(isset($_SESSION['memberid'])){
		$user_id=$_SESSION['memberid'];
	}
		include_once(SITE_PATH."/includes/areaedit/include.php");		
		$framework->tpl->assign("BLOG_DETAILS", $blog->getBlog($user_id));	
		$Date=date("Y-m-d H:i:s");		
		$framework->tpl->assign("CUR_DATE", $Date);
		$framework->tpl->assign("USER_ID", $user_id);			
		if($_SERVER['REQUEST_METHOD'] == "POST") {
            $req = &$_REQUEST;	
			
			if( ($message = $blog->blogentryAddEdit($req)) === true ) {
                redirect(makeLink(array("mod"=>"blog", "pg"=>"blog_entry"),"act=list"));
            }
            $framework->tpl->assign("MESSAGE", $message);
        }
		editorInit('post_description');
        if($message) {
            $framework->tpl->assign("POSTS", $_POST);
        } else if($id) {
			$framework->tpl->assign("BLOG_ENTRY_DETAILS", $blog->getBlogentry($id));
        }
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/blogs_entry_form.tpl");		
        break;
		
}
if($_REQUEST['act']=="list"){
	if($countBlog>0){
		$framework->tpl->display(SITE_PATH."/modules/blog/tpl/blogframe.tpl");
	}else{
		$framework->tpl->display($global['curr_tpl']."/inner.tpl");
	}
}else{
	$framework->tpl->display($global['curr_tpl']."/inner.tpl");
}
?>