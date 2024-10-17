<?php 
session_start();
if(isset($_SESSION['memberid'])){
	$user_id=$_SESSION['memberid'];
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
switch($_REQUEST['act']) {
	case "list":		
	
		$framework->tpl->assign("CAT_NAME", $blog->getCatname($_REQUEST['id']));
		$additionalVar="id=".$_REQUEST['id'];
		list($subcat_rs, $numpad) = $blog->blogsubcategoryList($_REQUEST['id'],$_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&$additionalVar", OBJECT, $_REQUEST['orderBy']);
		//print_r($subcat_rs);exit;
		$framework->tpl->assign("BLOG_SUBCAT", $subcat_rs);
		$framework->tpl->assign("SUBCAT_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/blog_subcategory_list.tpl");	
		break;  
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>