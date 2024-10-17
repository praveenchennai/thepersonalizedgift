<?php 
session_start();
if(isset($_SESSION['memberid'])){
	$user_id=$_SESSION['memberid'];
}
include_once(FRAMEWORK_PATH."/modules/blog/lib/class.blog.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
$objCms = new Cms();
$objUser     =	new User();
$blog = new Blog();
$user_id=$_SESSION['memberid'];
if($user_id){
			$blog_details=$blog->getBlog($user_id);
		}
		
$USERINFO=$objUser->getUserdetails($blog_details['user_id']);		
$framework->tpl->assign("SCREEN_NAME",$USERINFO['screen_name']);
$framework->tpl->assign("BLOG_DETAILS", $blog_details);		
$framework->tpl->assign("LEFTBOTTOM",'blog');
if($_REQUEST['recentid']!="")
	{
	 $_SESSION['chps1']=1;
	}
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
	$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("fiance_right"));// to select the right menu of this page from database.
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
checkLogin();
switch($_REQUEST['act']) {
	case "list":
/////////////////for link54
if($global["inner_change_reg"]=="yes")
							{
		$framework->tpl->assign("blogcategory","1");					
		$cat_rs= $blog->BlogcategoryleftList();
		$framework->tpl->assign("BLOG_CAT", $cat_rs);
	    $req = &$_REQUEST;
		$framework->tpl->assign("POST", $req);
		if($_REQUEST["leftid"]!=0and $_REQUEST['name']==""){	
			$_REQUEST['id']=$_REQUEST["leftid"];
		
		 }
		if($_REQUEST['id']!="")
			{  
			 $framework->tpl->assign("CAT_NAME", $blog->getCatname($_REQUEST['id']));
			 $additionalVar="id=".$_REQUEST['id'];
		list($rs, $numpad) = $blog->blogdisplayList($_REQUEST['id'],$_REQUEST['pageNo'], 5, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&$additionalVar", OBJECT, $_REQUEST['orderBy']);
		     $framework->tpl->assign("SEARCH_LIST", $rs);
			 $framework->tpl->assign("SEARCH_NUMPAD", $numpad); 
			}
			else
			{
			list($rs, $numpad) = $blog->blogsearchList($req,'Y',$_REQUEST['pageNo'], 5, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $orderBy);
			$framework->tpl->assign("SEARCH_LIST", $rs);
			$framework->tpl->assign("SEARCH_NUMPAD", $numpad); 
			}
			//print_r($rs);
		//$framework->tpl->assign("blogdescription",substr());			
		$framework->tpl->assign("main_tpl", SITE_PATH."/templates/green/blogcategory.tpl");			
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/blogcategory_list.tpl");	
		break;  
						
     						}
///////////////////
          else	{
		list($cat_rs, $numpad) = $blog->blogcategoryList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
		//print_r($cat_rs);exit;
		$framework->tpl->assign("BLOG_CAT", $cat_rs);
		$framework->tpl->assign("CAT_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/blogcategory_list.tpl");	
		break;  
               }


             }
			 $framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>