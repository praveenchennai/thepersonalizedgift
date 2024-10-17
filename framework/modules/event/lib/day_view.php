<?
include_once(FRAMEWORK_PATH."/modules/event/lib/calendar.config.php");
include_once(FRAMEWORK_PATH."/modules/event/lib/class.calendar.php");

	$year  = isset($_REQUEST['year']) ? $_REQUEST['year'] : date('Y');
	$month = isset($_REQUEST['month']) ? $_REQUEST['month'] : date('m'); 
	$day   = isset($_REQUEST['day']) ? $_REQUEST['day'] : date('d'); 
	
	$objCalendar = new Calendar ($year,$month,$day);

	$date  = $year."-".$month."-".$day;
	$framework->tpl->assign("nextdate",$objCalendar->next_date($date));
	$framework->tpl->assign("prevdate",$objCalendar->prev_date($date));
	$framework->tpl->assign("nextweek",$objCalendar->next_week($date));
	$framework->tpl->assign("calendar",$objCalendar->drawCalendar($month, $year,$day));
	$framework->tpl->assign("sdate",$date );
	$framework->tpl->assign("TIME_ARRAY",$objCalendar->timeArray());
	
	$framework->tpl->assign("MENU",SITE_PATH."/modules/event/tpl/event_top_menu.tpl");
	$framework->tpl->assign("main_tpl",SITE_PATH."/modules/event/tpl/day_view.tpl");
	$framework->tpl->display($global['curr_tpl']."/inner.tpl");

?>
