<?php 

checkLogin(1);

include_once(FRAMEWORK_PATH."/modules/blog/lib/class.blog.php");
$blog = new blog();
$framework->tpl->assign("LEFTBOTTOM",'blog');
switch($_REQUEST['act']) {
    case "list":
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
		$req = &$_REQUEST;
		$framework->tpl->assign("POST",$req );	
		list($rs, $numpad) = $blog->blogsearchList($req,'',$_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("BLOG_LIST", $rs);
        $framework->tpl->assign("BLOG_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/adminblog_list.tpl");
		if($req['adminflag']=="Unsuspend"){
		$blog->adminblogUnsuspend($req);
        redirect(makeLink(array("mod"=>"blog", "pg"=>"admin_blog"), "act=list"));
	}
	if($req['adminflag']=="Suspend"){
		 $blog->adminblogSuspend($req);
        redirect(makeLink(array("mod"=>"blog", "pg"=>"admin_blog"), "act=list"));
	}
        break;
    case "delete":
        $blog->adminblogDelete($_REQUEST['id']);
        redirect(makeLink(array("mod"=>"blog", "pg"=>"admin_blog"), "act=list"));
        break;	
	case "settings":
        if($_SERVER['REQUEST_METHOD']=="POST")
			{
				$type=$_POST['type'];
				$blog->settingsUpdate($type);
			}
			$framework->tpl->assign("type", $blog->settingsGet());
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/blog_settings.tpl");		
        break;	
	case "Suspend":
		$req=&$_REQUEST;		
        $blog->adminblogSuspend($req);
        redirect(makeLink(array("mod"=>"blog", "pg"=>"admin_blog"), "act=list"));
        break;
	case "Unsuspend":
		$req=&$_REQUEST;	
        $blog->adminblogUnsuspend($req);
        redirect(makeLink(array("mod"=>"blog", "pg"=>"admin_blog"), "act=list"));
        break;
}
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
?>