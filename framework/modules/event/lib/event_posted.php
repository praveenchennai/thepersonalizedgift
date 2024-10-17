<?
include_once(FRAMEWORK_PATH."/modules/event/lib/calendar.config.php");
include_once(FRAMEWORK_PATH."/modules/event/lib/class.event.php");

	$objEvent = new Event ();
	
	$params = "mod=event&pg=event_posted";
	$page = $_REQUEST['pageNo'];
	$result = $objEvent->eventPosted($page,$limit,$params," AND em.mem_id = {$_SESSION['memberid']}");
	$framework->tpl->assign("EVENT_POSTED",$result[0] );
	$framework->tpl->assign("EVENT_STR",$result[1] );
	$framework->tpl->assign("main_tpl",SITE_PATH."/modules/event/tpl/event_posted.tpl");
	$framework->tpl->display($global['curr_tpl']."/inner.tpl");

?>
