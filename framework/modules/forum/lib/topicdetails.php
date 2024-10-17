<?php 
session_start();
$uid=$_SESSION['memberid'];
//include_once(FRAMEWORK_PATH."/modules/forum/lib/class.forumclient.php");
include_once(FRAMEWORK_PATH."/modules/forum/lib/class.forum.php");
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
$objCms 	 = new Cms();
$forum = new Forum();
$user = new User();
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
	
	else{
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
	}
	if($_REQUEST["pid"]!=""){
$framework->tpl->assign("pid", $_REQUEST["pid"]);	
	}


switch($_REQUEST['act']) {
   case "details":
   $framework->tpl->assign("CRT",$_REQUEST['cat_id']);	
           
   	
	     if($_REQUEST['id']!="")
		  {
		  
		  if($_REQUEST['chk']==1)
		  {
		  $forum->topicUpdate($_REQUEST['id']);
		  }
		  $Topic=$forum->TopicGet($_REQUEST['id']);
		  $userdetails = $user->getUserDetails($Topic['user_id']);
		  $framework->tpl->assign("USER",$userdetails);
		  $framework->tpl->assign("TOPICS",$Topic);
		  }
		  if($_REQUEST['cat_id']!="")
		  {
		   $MainTopic=$forum->TopicGet($_REQUEST['cat_id']);
		   $framework->tpl->assign("MAINTOPICS",$MainTopic);
		  }
		if($_SESSION['chps1']==3){ 
		        $framework->tpl->assign("category", Health);  
			   list($rs_health, $numpad) = $forum->TopicList(23,$_REQUEST['pageNo'], '', "mod=".$_REQUEST['mod']."&pg=".$_REQUEST['pageNo']."&act=".$_REQUEST['act']."&cat_id=".$_REQUEST['cat_id'], OBJECT, $_REQUEST['orderBy']);
               $framework->tpl->assign("TOPIC_LIST", $rs_health);
			   $framework->tpl->assign("left_tpl", SITE_PATH."/modules/forum/tpl/health_left.tpl");	}
		
		if($_SESSION['chps1']==4){
		       $framework->tpl->assign("category", Wealth);  	
			   list($rs_health, $numpad) = $forum->TopicList(42,$_REQUEST['pageNo'], '', "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&cat_id=".$_REQUEST['cat_id'], OBJECT, $_REQUEST['orderBy']);
               $framework->tpl->assign("TOPIC_LIST",$rs_health);
			    $framework->tpl->assign("left_tpl", SITE_PATH."/modules/forum/tpl/health_left.tpl");}   
		
		$framework->tpl->assign("TITLE_HEAD", "Discussion Forum");
		$framework->tpl->assign("SECTION_LIST", $forum->menuTopicList());
			
		list($rs, $numpad) = $forum->postViewList($_REQUEST['id'],$_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&id=".$_REQUEST['id']."&cat_id=".$_REQUEST['cat_id'], OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("POST_LIST", $rs);
		//print_r($rs);
		if($_SERVER['REQUEST_METHOD'] == "POST") {		
			$req = &$_REQUEST;
		   	$getId=$_SESSION["memberid"];			
			if( ($message = $forum->CommentReplyAdd($req,$getId)) === true ) {
				redirect(makeLink(array("mod"=>"forum", "pg"=>"topicdetails"), "act=details&id=".$_REQUEST['id']."&cat_id=".$_REQUEST['cat_id']));
			}
			$framework->tpl->assign("MESSAGE", $message);
		}
		
		if($_REQUEST["rate"])
		{
			    checkLogin();
				$msg=$forum->AddRating('forum_topic',$_REQUEST["id"],'topic',$_REQUEST["rate"],$_SESSION["memberid"]);
				$framework->tpl->assign("MESSAGE",$msg);
				
				
		}		
		
		$framework->tpl->assign("POST_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/forum/tpl/topic_details.tpl");
        break;

case "postreply":
   $framework->tpl->assign("CRT",$_REQUEST['cat_id']);		
	     if($_REQUEST['id']!="")
		  {
		 // $forum->topicUpdate($_REQUEST['id']);
		  $Topic=$forum->TopicGet($_REQUEST['id']);
		  $userdetails = $user->getUserDetails($Topic['user_id']);
		  $framework->tpl->assign("USER",$userdetails);
		  $framework->tpl->assign("TOPICS",$Topic);
		  }
		  if($_REQUEST['cat_id']!="")
		  {
		   $MainTopic=$forum->TopicGet($_REQUEST['cat_id']);
		   $framework->tpl->assign("MAINTOPICS",$MainTopic);
		  }
		if($_SESSION['chps1']==3){ 
		        $framework->tpl->assign("category", Health);  
			   list($rs_health, $numpad) = $forum->TopicList(23,$_REQUEST['pageNo'], '', "mod=".$_REQUEST['mod']."&pg=".$_REQUEST['pageNo']."&act=".$_REQUEST['act']."&cat_id=".$_REQUEST['cat_id'], OBJECT, $_REQUEST['orderBy']);
               $framework->tpl->assign("TOPIC_LIST", $rs_health);
			   $framework->tpl->assign("left_tpl", SITE_PATH."/modules/forum/tpl/health_left.tpl");	}
		
		if($_SESSION['chps1']==4){
		       $framework->tpl->assign("category", Wealth);  	
			   list($rs_health, $numpad) = $forum->TopicList(42,$_REQUEST['pageNo'], '', "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&cat_id=".$_REQUEST['cat_id'], OBJECT, $_REQUEST['orderBy']);
               $framework->tpl->assign("TOPIC_LIST", $rs_health);
			    $framework->tpl->assign("left_tpl", SITE_PATH."/modules/forum/tpl/health_left.tpl");}   
		
		$framework->tpl->assign("TITLE_HEAD", "Discussion Forum");
		$framework->tpl->assign("SECTION_LIST", $forum->menuTopicList());
		//$framework->tpl->assign("THREAD_LIST", $forum->getThread($_REQUEST['id']));
		$framework->tpl->assign("TOPIC_ID", $_REQUEST['topic_id']);		
		$AdditionalVar="id=".$_REQUEST['id']."&topic_id=".$_REQUEST['topic_id'];
		list($rs, $numpad) = $forum->postList($_REQUEST['id'],$_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&$AdditionalVar", OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("POST_LIST", $rs);
        $framework->tpl->assign("POST_NUMPAD", $numpad);
		
		if($_SERVER['REQUEST_METHOD'] == "POST") {		
			$req = &$_REQUEST;
			$getId=$_SESSION["memberid"];			
			if( ($message = $forum->ReplyAdd($req,$getId)) === true ) {
				redirect(makeLink(array("mod"=>"forum", "pg"=>"topicdetails"), "act=details&id=".$_REQUEST['id']."&cat_id=".$_REQUEST['cat_id']));
			}
			$framework->tpl->assign("MESSAGE", $message);
		}
		if($message) {
			$framework->tpl->assign("POSTS", $_POST);
		} /*elseif($_REQUEST['id']) {
			$framework->tpl->assign("POSTS", $forum->getPosts($_REQUEST['id']));
		}*/
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/forum/tpl/posttopic_reply.tpl");
        break;


    case "list":
	    if($global['chk_filed']=='check');
		{
			if ($_SESSION["memberid"])
			{
				if($objUser->checkGroupMember($_REQUEST["topic_id"],$_SESSION["memberid"]))
				{
					$framework->tpl->assign("MEM_FLG","Y");	
				}
				else
				{
					$framework->tpl->assign("MEM_FLG","N");	
				}
					
				}
			else
			{
				$framework->tpl->assign("MEM_FLG","N");
			}
		}		 
		$framework->tpl->assign("TITLE_HEAD", "Discussion Forum");
		$framework->tpl->assign("SECTION_LIST", $forum->menuTopicList());
		$framework->tpl->assign("THREAD_LIST", $forum->getThread($_REQUEST['id']));
		$framework->tpl->assign("TOPIC_ID", $_REQUEST['topic_id']);		
		$AdditionalVar="id=".$_REQUEST['id']."&topic_id=".$_REQUEST['topic_id'];
		list($rs, $numpad) = $forum->postList($_REQUEST['id'],$_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&$AdditionalVar", OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("POST_LIST", $rs);
        $framework->tpl->assign("POST_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/forum/tpl/clientposts_list.tpl");
        break;
	case "form":
		checkLogin();
		$framework->tpl->assign("TITLE_HEAD", "Discussion Forum");
		$Date=date("Y-m-d H:i:s");
		$topic_id=$_REQUEST['topic_id'];
		$thread_id=$_REQUEST['thread_id'];		
		$framework->tpl->assign("CUR_DATE", $Date);	
		$framework->tpl->assign("USER_ID", $uid);
		$framework->tpl->assign("TOPIC_ID", $_REQUEST['topic_id']);	
		$framework->tpl->assign("THREAD_ID", $_REQUEST['thread_id']);
		$framework->tpl->assign("THREAD_LIST", $forum->getThread($_REQUEST['thread_id']));
		$framework->tpl->assign("TOPICSCAT", $forum->getTopic($_REQUEST['topic_id']));
		$userDetails=$forum->getUser($uid);
		$fname=$userDetails['first_name'];
		$lname=$userDetails['last_name'];
		$Name=$fname."  ".$lname;
		$framework->tpl->assign("USER_NAME", $Name);
		if($_SERVER['REQUEST_METHOD'] == "POST") {		
			$req = &$_REQUEST;			
			if( ($message = $forum->postsAddEdit($req)) === true ) {
				redirect(makeLink(array("mod"=>"forum", "pg"=>"clientposts"), "act=list&id=$thread_id&topic_id=$topic_id"));
			}
			$framework->tpl->assign("MESSAGE", $message);
		}
		if($message) {
			$framework->tpl->assign("POSTS", $_POST);
		} elseif($_REQUEST['id']) {
			$framework->tpl->assign("POSTS", $forum->getPosts($_REQUEST['id']));
		}
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/forum/tpl/clientpost_form.tpl");
		break;
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>