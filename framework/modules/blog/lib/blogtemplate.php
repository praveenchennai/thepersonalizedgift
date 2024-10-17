<?php 

authorize();

include_once(FRAMEWORK_PATH."/modules/blog/lib/class.blog.php");
$blog = new blog();
$framework->tpl->assign("LEFTBOTTOM",'blog');
switch($_REQUEST['act']) {
    case "list":		
		list($rs, $numpad) = $blog->adminBlogtempalteList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("TEMPLATE_LIST", $rs);
        $framework->tpl->assign("TEMPLATE_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/blogtemplate_list.tpl");
        break;
    case "form":	
	 include_once(SITE_PATH."/includes/colorPicker/include.php");
		
		$blogDetails= $blog->getBlog($user_id);
		$blog_Id=$blogDetails['id'];	
		$page_width=$blog->getPagewidth($blog_Id);		
		$width_val=str_replace("%",'',$page_width['page_width']);	
		$framework->tpl->assign("WIDTH_VAL", $width_val);	
		$framework->tpl->assign("PAGEWIDTH", $page_width);
		$framework->tpl->assign("PAGEHEADER", $blog->getHeader($blog_Id));
		$framework->tpl->assign("PAGEBACKGROUND", $blog->getBackground($blog_Id));
		$framework->tpl->assign("TEXTLINK", $blog->getText($blog_Id));
		$framework->tpl->assign("LEFTMODULE", $blog->getLeftmodule($blog_Id));
		$framework->tpl->assign("MUSIC", $blog->getMusic($blog_Id));
		$framework->tpl->assign("SEARCHBAR", $blog->getSearchbar($blog_Id));
        $framework->tpl->assign("BLOG_ID",$blog_Id);
        $framework->tpl->assign("SECTION_LIST", $blog->menufontList());
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $req = &$_REQUEST;
			$fname=basename($_FILES['image']['name']);
			$ftype=$_FILES['image']['type'];
			$tmpname=$_FILES['image']['tmp_name'];
            if( ($message = $blog->blogtemplateAddEdit($req,$fname,$tmpname)) === true ) {
                redirect(makeLink(array("mod"=>"blog", "pg"=>"blogtemplate"), "act=list"));
            }
            $framework->tpl->assign("MESSAGE", $message);
        }
        if($message) {
            $framework->tpl->assign("TEMPLATE", $_POST);
        } elseif($_REQUEST['id']) {
            $framework->tpl->assign("TEMPLATE", $blog->templateGet($_REQUEST['id']));
        }
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/blogtemplate_form.tpl");
        break;
    case "delete":
        $blog->blogtemplateDelete($_REQUEST['id']);
        redirect(makeLink(array("mod"=>"blog", "pg"=>"blogtemplate"), "act=list"));
        break;	
	case "preview":					
			include("blog_customizepreview.php");
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/blog_preview.tpl");
	break;
	
}
if($_REQUEST['act']=="preview"){
	$framework->tpl->display(SITE_PATH."/modules/blog/tpl/blogframe.tpl");	
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}
?>