<?php 

authorize();

include_once(FRAMEWORK_PATH."/modules/forum/lib/class.forum.php");
$forum = new Forum();
switch($_REQUEST['act']) {
    case "list":
		$framework->tpl->assign("SECTION_LIST", $forum->menuTopicList()); 
		$framework->tpl->assign("TITLE_HEAD", "Discussion Forum");
		list($rs, $numpad) = $forum->ThreadList($_REQUEST['section_id'],$_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("THREAD_LIST", $rs);
        $framework->tpl->assign("THREAD_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/forum/tpl/thread_list.tpl");
        break;
    case "delete":
        $forum->threadDelete($_REQUEST['id']);
        redirect(makeLink(array("mod"=>"forum", "pg"=>"threads"), "act=list"));
        break;
	case "details":	
		if($_REQUEST['id'])
		{	
			$framework->tpl->assign("THREAD_DETAILS", $forum->ThreadGet($_REQUEST['id']));
			$framework->tpl->assign("TITLE_HEAD", "Discussion Forum");	
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/forum/tpl/threaddetails_list.tpl");
		}
}
$framework->tpl->display($global['curr_tpl']."/admin.tpl");
?>