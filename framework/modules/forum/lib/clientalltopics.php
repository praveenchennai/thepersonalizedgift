<?php 
	include_once(FRAMEWORK_PATH."/modules/forum/lib/class.forumclient.php");
	$forum = new Forum();
switch($_REQUEST['act']) {
    case "list":
	$framework->tpl->assign("TITLE_HEAD", "Discussion Forum");
	$AdditionalVar="id=".$_REQUEST['id'];
	$framework->tpl->assign("FORUMCATEGORY", $forum->CategoryGet($_REQUEST['id']));		
	
	list($rs,$numpad)= $forum->topicListAll($_REQUEST['id'],$_REQUEST['pageNo'],5,"mod=$mod&pg=$pg&act=".$_REQUEST['act']."&id=".$_REQUEST['id'], OBJECT, $_REQUEST['orderBy']);
	$framework->tpl->assign("TOPIC_LIST", $rs);
	$framework->tpl->assign("TOPIC_NUMPAD", $numpad);
	$framework->tpl->assign("main_tpl", SITE_PATH."/modules/forum/tpl/clienttopicall_list.tpl");
	break;
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");

?>