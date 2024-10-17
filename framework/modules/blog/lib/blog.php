<?php 
session_start();
include_once(FRAMEWORK_PATH."/modules/blog/lib/class.blog.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
$objCms = new Cms();
$framework->tpl->assign("LEFTBOTTOM",'blog');
if(isset($_SESSION['chps1']))
{
	$framework->tpl->assign("chps1",$_SESSION['chps1']);
}
if($_SESSION['chps1']==1){
	$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
	$rightmenu=$objCms->linkList("social_community_right");
}elseif($_SESSION['chps1']==2){
	$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("dating_right"));// to select the right menu of this page from database.
	$rightmenu=$objCms->linkList("dating_right");
}elseif($_SESSION['chps1']==3){
	$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("rest_right"));// to select the right menu of this page from database.
	$rightmenu=$objCms->linkList("dating_right");
}
elseif($_SESSION['chps1']==4){
	$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("rest_right"));// to select the right menu of this page from database.
	$rightmenu=$objCms->linkList("dating_right");
}
elseif($_SESSION['chps1']==5){
	$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("dating_right"));// to select the right menu of this page from database.
	$rightmenu=$objCms->linkList("dating_right");
}
else
{
$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));
}
if($_REQUEST["pid"]!=""){
	$framework->tpl->assign("pid", $_REQUEST["pid"]);
}

$blog = new Blog();
$objUser = new User();
switch($_REQUEST['act']) {
    case "form":	
		checkLogin();	
		$Date=date("Y-m-d H:i:s");
		$user_id=$_SESSION['memberid'];
		$framework->tpl->assign("CUR_DATE", $Date);
		$framework->tpl->assign("USER_ID", $user_id);
		$framework->tpl->assign("SITE_PATH", SITE_PATH);
		$framework->tpl->assign("SECTION_LIST", $blog->menuSectionList());
		
		if($global["inner_change_reg"]=="yes")
							{
		if($user_id){
			$blog_details=$blog->getAdvanceBlog($user_id,$_SESSION['chps1']);
		    }
			
			}else
			{
								{
		if($user_id){
			$blog_details=$blog->getBlog($user_id);
		    }
			
			}
			
		/*	foreach ($blog_details as $key=>$value)
			{
		
				if( isset($_SESSION['chps1']))
				{
					if($value==$_SESSION['chps1'])
					{
					$framework->tpl->assign("BLOGEXIST",1); 
					}
				
				}*/
				
			}
		if(isset($_REQUEST['cat_id'])){
			$cat_id=$_REQUEST['cat_id'];	
		}else if($blog_details['cat_id']){
				
			$cat_id=$blog_details['cat_id'];
		}
		if($cat_id!=""){
			$framework->tpl->assign("SUBCATLIST", $blog->menuSubcatList($cat_id)); 
		}		
		list($rs, $numpad) = $blog->templateList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT,$_REQUEST['orderBy']);		              	
        $framework->tpl->assign("TEMPLATE_LIST", $rs);
		
	
		
		if($_SERVER['REQUEST_METHOD'] == "POST") {
            $req = &$_REQUEST;
			if( ($message = $blog->blogAddEdit($req)) === true ) {
                redirect(makeLink(array("mod"=>"blog", "pg"=>"blog_entry"),"act=list"));
            }
            $framework->tpl->assign("MESSAGE", $message);
        }
        if($message) {
            $framework->tpl->assign("POSTS", $_POST);
        } elseif($user_id) {			
			if(isset($_REQUEST['cat_id'])){
				$framework->tpl->assign("CAT_ID", $_REQUEST['cat_id']);
				
			}else{
		 		$framework->tpl->assign("CAT_ID", $blog_details['cat_id']);
			}		
            $framework->tpl->assign("BLOG_DETAILS", $blog_details);
        }
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/blogs_form.tpl");		
        break;
		
	case "channel";
		$params = "mod=".$mod."&pg=".$pg."&act=".$_REQUEST['act'];
		//list($rsb,$numpad) = $blog->getBlogMemberList($_REQUEST['pageNo'], $limit=9, $params, OBJECT, $orderBy);
		list($rsb,$numpad)  = $objUser->userList($_REQUEST['pageNo'], $limit = 9, $params, $output=OBJECT, $orderBy,'',"mem_type",0,"!=") ;
		$framework->tpl->assign("BLOG_MEMBER", $rsb);
		$framework->tpl->assign("NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/channels_list.tpl");
	break;
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>