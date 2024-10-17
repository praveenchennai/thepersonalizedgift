<?php 

authorize();

include_once(FRAMEWORK_PATH."/modules/forum/lib/class.forum.php");
$forum = new Forum();
switch($_REQUEST['act']) {
    case "list":
		if ($_SESSION["memberid"])
			{
				if($objUser->checkGroupMember($_REQUEST["topc_id"],$_SESSION["memberid"]))
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
		list($rs, $numpad) = $forum->postList($_REQUEST['thread_id'],$_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&thread_id=".$_REQUEST['thread_id'], OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("POST_LIST", $rs);
		$framework->tpl->assign("TITLE_HEAD", "Discussion Forum");
        $framework->tpl->assign("POST_NUMPAD", $numpad);
		$framework->tpl->assign("POST_NAME", $_REQUEST['name']);
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/forum/tpl/post_list.tpl");
        break;
    case "delete":
        $forum->postDelete($_REQUEST['id']);
        redirect(makeLink(array("mod"=>"forum", "pg"=>"forumposts"), "act=list"));
        break;
	case "details":	
		if($_REQUEST['id'])
		{	
			$framework->tpl->assign("POST_DETAILS", $forum->ThreadGet($_REQUEST['id']));	
			$framework->tpl->assign("TITLE_HEAD", "Discussion Forum");
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/forum/tpl/postdetails_list.tpl");
		}
}
$framework->tpl->display($global['curr_tpl']."/admin.tpl");
?>