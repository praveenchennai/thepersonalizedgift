<?
include_once(FRAMEWORK_PATH."/modules/event/lib/calendar.config.php");
include_once(FRAMEWORK_PATH."/modules/event/lib/class.calendar.php");

	$objCalendar = new Calendar ();
	
	$year  = isset($_GET['year']) ? $_GET['year'] : date('Y');
	$month = isset($_GET['month']) ? $_GET['month'] : date('m'); 
	$day   = isset($_GET['day']) ? $_GET['day'] : date('d'); 
	
	$date  = $year."-".$month."-".$day;
	
	$framework->tpl->assign("calendar",$objCalendar->draw_year_calendar($month, $year,$day));
	$framework->tpl->assign("sdate",$date );
	$framework->tpl->assign("weeklist",$objCalendar->weekList($month, $year,$day));
	
	$framework->tpl->assign("prevweek",$objCalendar->prev_week($date));
	$framework->tpl->assign("nextweek",$objCalendar->next_week($date));
	$framework->tpl->assign("MENU",SITE_PATH."/modules/event/tpl/event_top_menu.tpl");
	$framework->tpl->assign("main_tpl",SITE_PATH."/modules/event/tpl/year_view.tpl");
	$framework->tpl->display($global['curr_tpl']."/inner.tpl");

?>
