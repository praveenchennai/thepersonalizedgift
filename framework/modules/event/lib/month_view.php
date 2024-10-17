<?
include_once(FRAMEWORK_PATH."/modules/event/lib/calendar.config.php");
include_once(FRAMEWORK_PATH."/modules/event/lib/class.calendar.php");


	$objCalendar = new Calendar ();
	
	$year  = isset($_REQUEST['year']) ? $_REQUEST['year'] : date('Y');
	$month = isset($_REQUEST['month']) ? $_REQUEST['month'] : date('m'); 
	$day   = isset($_REQUEST['day']) ? $_REQUEST['day'] : date('d'); 
	
	$date  = $year."-".$month."-".$day;
		
	$framework->tpl->assign("calendar",$objCalendar->drawCalendar_big($month, $year,$day));
	$framework->tpl->assign("MENU",SITE_PATH."/modules/event/tpl/event_top_menu.tpl");
	$framework->tpl->assign("main_tpl",SITE_PATH."/modules/event/tpl/month_view.tpl");
	$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>