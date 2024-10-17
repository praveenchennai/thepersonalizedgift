<?
include_once(FRAMEWORK_PATH."/modules/event/lib/calendar.config.php");
include_once(FRAMEWORK_PATH."/modules/event/lib/class.event.php");

	$objEvent = new Event ();
	
	$params = "mod=event&pg=friend_search&search=".$_REQUEST['search'];
	$page = $_REQUEST['pageNo'];
	$result = $objEvent->eventPosted($page,$limit,$params);
	
	if(count($_REQUEST))
	{
		$arraySearch = $objEvent->searchResults($_REQUEST['search'],$params,$page);
		$framework->tpl->assign("SERARCH",$arraySearch);
	}
	$framework->tpl->assign("main_tpl",SITE_PATH."/modules/event/tpl/friend_search.tpl");
	$framework->tpl->display($global['curr_tpl']."/popup.tpl");

?>
