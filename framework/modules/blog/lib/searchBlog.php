<?php 
session_start();
if(isset($_SESSION['memberid'])){
	$user_id=$_SESSION['memberid'];
}
include_once(FRAMEWORK_PATH."/modules/blog/lib/class.blog.php");
$blog = new Blog();
$strMessage="";
$dispFlag=0;
if(isset($_REQUEST['btn_search'])){
	$dispFlag=1;
}
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
		$framework->tpl->assign("DISP_FLAG",$dispFlag);
		$framework->tpl->assign("SECTION_LIST", $blog->menuSectionList());
		if(isset($_REQUEST['cat_id'])){
			$cat_id=$_REQUEST['cat_id'];
			$framework->tpl->assign("CAT_ID", $cat_id);
		}
		if($cat_id!=""){
			$framework->tpl->assign("SUBCATLIST", $blog->menuSubcatList($cat_id));
		}
		if(isset($_REQUEST['subcat_id'])){
			$subcat_id=$_REQUEST['subcat_id'];
			$framework->tpl->assign("SUBCAT_ID", $subcat_id);
		}

		if($_SERVER['REQUEST_METHOD']=="POST") {
			$req = &$_REQUEST;
			$framework->tpl->assign("POST", $req);
			list($rs, $numpad) = $blog->blogsearchList($req,'Y',$_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $orderBy);
			$framework->tpl->assign("SEARCH_LIST", $rs);
			$framework->tpl->assign("SEARCH_NUMPAD", $numpad);
		}

		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/searchBlog_form.tpl");
		break;
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");

?>