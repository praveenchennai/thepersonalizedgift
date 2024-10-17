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
if($_REQUEST["leftid"]==0){
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
	}elseif($_REQUEST["leftid"]==4){
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("rest_right"));// to select the right menu of this page from database.
	}
$framework->tpl->assign("LEFTBOTTOM",'blog');
	switch($_REQUEST['act']) {
    case "list":
		$framework->tpl->assign("SITE_PATH", SITE_PATH);
		$framework->tpl->assign("CAT_NAME", $blog->getCatname($_REQUEST['parent_id']));		
		$framework->tpl->assign("SUBCAT_NAME", $blog->getCatname($_REQUEST['id']));	
		$additionalVar="id=".$_REQUEST['id']."&parent_id=".$_REQUEST['parent_id'];	
		list($rs, $numpad) = $blog->blogList($_REQUEST['id'],$_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&$additionalVar", OBJECT,$orderBy);
		//print_r($rs);exit;
		if($global['show_screen_name_only']=='1'){
				for($k=0;$k<count($rs);$k++){
					
					$sn=$objUser->getUsernamedetails($rs[$k]->uname);
					$rs[$k]->screen_name=$sn['screen_name'];
				}
			}
		$framework->tpl->assign("BLOG_LIST", $rs);
        $framework->tpl->assign("BLOG_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/blog_list.tpl");
        break;	
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>