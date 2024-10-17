<?php 
session_start();
$uid=$_SESSION['memberid'];
include_once(FRAMEWORK_PATH."/modules/forum/lib/class.forumclient.php");
$forum = new Forum();
switch($_REQUEST['act']) {
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