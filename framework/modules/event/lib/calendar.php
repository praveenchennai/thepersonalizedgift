<?
include_once(SITE_PATH."/modules/event/lib/calendar.config.php");
include_once(SITE_PATH."/modules/event/lib/class.calendar.php");

	$objCalendar = new Calendar ();
	
	$year  = isset($_GET['year']) ? $_GET['year'] : date('Y');
	$month = isset($_GET['month']) ? $_GET['month'] : date('m'); 
	$day = isset($_GET['day']) ? $_GET['day'] : date('d'); 
	
	$date		=   $year."-".$month."-".$day;

	$week_start_date  = $objCalendar->getcurrent_week($date);
	
	$framework->tpl->assign("currentweek",$week_start_date);
	$framework->tpl->assign("calendar",$objCalendar->drawCalendar($month, $year,$day));
	$framework->tpl->display($global['curr_tpl']."/inner.tpl");
	
?>