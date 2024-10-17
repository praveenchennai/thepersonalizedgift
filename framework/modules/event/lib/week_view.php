<?
include_once(FRAMEWORK_PATH."/modules/event/lib/calendar.config.php");
include_once(FRAMEWORK_PATH."/modules/event/lib/class.calendar.php");

	
	$year  = isset($_REQUEST['year']) ? $_REQUEST['year'] : date('Y');
	$month = isset($_REQUEST['month']) ? $_REQUEST['month'] : date('m'); 
	$day   = isset($_REQUEST['day']) ? $_REQUEST['day'] : date('d'); 

	$objCalendar = new Calendar ($year,$month,$day);
	
	$date  = $year."-".$month."-".$day;
	
	$framework->tpl->assign("calendar",$objCalendar->drawCalendar($month, $year,$day));
	$framework->tpl->assign("sdate",$date );
	$framework->tpl->assign("weeklist",$objCalendar->weekList());
	
	$week_start_date  = $objCalendar->getcurrent_week($date);
	$framework->tpl->assign("currentweek",$week_start_date);
	$framework->tpl->assign("prevweek",$objCalendar->prev_week($date));
	$framework->tpl->assign("nextweek",$objCalendar->next_week($date));
	$framework->tpl->assign("MENU",SITE_PATH."/modules/event/tpl/event_top_menu.tpl");
	$framework->tpl->assign("main_tpl",SITE_PATH."/modules/event/tpl/week_view.tpl");
	$framework->tpl->display($global['curr_tpl']."/inner.tpl");

?>
