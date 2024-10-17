<?php 
session_start();
checkLogin();
$framework->tpl->assign("LEFTBOTTOM",'blog');
if(isset($_SESSION['memberid'])){
	$user_id=$_SESSION['memberid'];
}
if($_REQUEST['recentid']!="")
	{
	 $_SESSION['chps1']=1;
	}
if(isset($_SESSION['chps1']))
{
	$framework->tpl->assign("chps1",$_SESSION['chps1']);
}
         $Date=date("Y-m-d H:i:s");
		$user_id=$_SESSION['memberid'];
		$framework->tpl->assign("CUR_DATE", $Date);
		
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
include_once(FRAMEWORK_PATH."/modules/blog/lib/class.blog.php");
$blog = new Blog();
if($user_id){
			$blog_details=$blog->getBlog($user_id);
		}
$framework->tpl->assign("BLOG_DETAILS", $blog_details);		
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
$user = new user();
$array=$user->getUserdetails($user_id);

switch($_REQUEST['act']) {
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
		$framework->tpl->assign("MBODY", $blog->getMainBody($blog_Id));
        $framework->tpl->assign("BLOG_ID",$blog_Id);
		$framework->tpl->assign("USERNAME",$array['username']);
		$framework->tpl->assign("SCREEN_NAME",$array['screen_name']);
		$framework->tpl->assign("SECTION_LIST", $blog->menufontList());
		
		if($_REQUEST["remove"]==1)
		{
			$blog->removePicture($_REQUEST[blog_id]);
			
			redirect(makeLink(array("mod"=>"blog", "pg"=>"blogSettings"), "act=form&pid=".$_REQUEST[pid]));
		}
		
        if($_SERVER['REQUEST_METHOD'] == "POST") {
		
		  $req = &$_REQUEST;	    
			$fname=basename($_FILES['image']['name']);
			
			$ftype=$_FILES['image']['type'];
			$tmpname=$_FILES['image']['tmp_name'];
           if( ($message = $blog->sttingBlogpage($req,$fname,$tmpname)) === true ) {
		   
		   
		   if($global["inner_change_reg"]=="yes")
							{
							}else
							{
			 setMessage("Your Channel Design has been updated.",MSG_SUCCESS);
			                }
             redirect(makeLink(array("mod"=>"blog", "pg"=>"blogSettings"),"act=form&sv=suc"));
            }
			
            $framework->tpl->assign("MESSAGE", $message);
        }
		// Genereating preview Afsal
		if(isset($_REQUEST['sv']))
		{
			if($_REQUEST['sv'] == "suc"){
				$url =  SITE_URL;
			
				if($global['show_screen_name_only']=='1'){
				$uname = $array["screen_name"];
				}else{
			 		$uname = $array["username"];
				}
				
				print "<script>window.open('$url/$uname','myvindow','menubar=0,toolbar=0,height='+(screen.height-100)+',width='+(screen.width-100)+',resizable=1,scrollbars=1,left=0,top=0')</script>";
			}
		}
		//
        if($message) {
            $framework->tpl->assign("POSTS", $_POST);
        } elseif($_REQUEST['blog_id']) {
            $framework->tpl->assign("POSTS", $blog->getSettings($_REQUEST['blog_id']));
        }
       $framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/clientblogsetting_form.tpl");
        break;
		case "preview":	
					
			include("blog_customizepreview.php");
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/blog_preview.tpl");
		break;
}
if($_REQUEST['act']=="preview"){
	$framework->tpl->display(SITE_PATH."/modules/blog/tpl/blogframe.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/inner.tpl");
}
//$framework->tpl->display("default/inner.tpl");
?>