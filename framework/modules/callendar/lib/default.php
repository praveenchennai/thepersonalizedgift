<?php 
/**
* Ajax Call in Callendar
* Author   : Aneesh Aravindan
* Created  : 01/Oct/2007
* Modified : 31/Oct/2007 By Aneesh Aravindan
*/
include_once(FRAMEWORK_PATH."/modules/callendar/lib/class.calendartool.php");
$objCalendar = new CalendarTool ();

switch ($_REQUEST['act']) {
    case "Callendar_Tool_Assign":
		/* Begin ADD THE FOLLOWING , TO YOUR CASE */
	    $framework->tpl->assign("ELEMENT NAME",$objCalendar->DrawCalendarLayer(date('Y'),date('m'),date('d'),'ELEMENT NAME','CASE'));
		/* End ADD THE FOLLOWING , TO YOUR CASE */
	    break;
	case "callendar_navig":
	    $Elem  = isset($_REQUEST['elem']) ? $_REQUEST['elem'] : 'myCalendar';
		$year  = isset($_REQUEST['year']) ? $_REQUEST['year'] : date('Y');
		$month = isset($_REQUEST['month']) ? $_REQUEST['month'] : date('m'); 
		$day   = isset($_REQUEST['day']) ? $_REQUEST['day'] : date('d'); 
		
		$FCase   =  isset($_REQUEST['FCase']) ?  $_REQUEST['FCase'] : 'default'; 
		$TimeShow=  isset($_REQUEST['TimeShow']) ?  $_REQUEST['TimeShow'] : 'NO'; 
		$CUSTOM_FUNCTION=  isset($_REQUEST['CUSTOM_FUNCTION']) ?  $_REQUEST['CUSTOM_FUNCTION'] : ''; 
		$minYear   =  isset($_REQUEST['minYear']) ?  $_REQUEST['minYear'] : ""; 
		$maxYear   =  isset($_REQUEST['maxYear']) ?  $_REQUEST['maxYear'] : ""; 
		
		
		$res	=	$objCalendar->DrawCalendarLayer( $year ,$month, $day,$Elem,array('FCase'=>$FCase,'minYear'=>$minYear,'maxYear'=>$maxYear,'TimeShow'=>$TimeShow,'CUSTOM_FUNCTION'=>$CUSTOM_FUNCTION),'YES');
		
		echo $res. "|". $Elem;
		exit;
		break;	
		
	case "callendar_navig_year":
		
		extract($_REQUEST);
		
	    $Elem  = isset($_REQUEST['elem']) ? $_REQUEST['elem'] : 'myCalendar';
		
		
		if ($_REQUEST['dates'] ) {
			$next_month_array = explode("-",$_REQUEST['dates']);
		} else {
			$next_month_array[0]	=  date('Y'); 
			$next_month_array[1]	=  date('m');
			$next_month_array[2]	=  date('d'); 
		}
				
		
		$res	=	$objCalendar->DrawCalendarYear($next_month_array[0], $next_month_array[1],$next_month_array[2],$Elem,array('FCase'=>$FCase,'ColSet'=>$ColSet,'minDate'=>$minDate,'maxDate'=>$maxDate,'FromAjax'=>true));
		
		echo $res. "|". $Elem;
		exit;
		break;	
		
	case "callendar_month":
	    $Elem  = isset($_REQUEST['elem']) ? $_REQUEST['elem'] : 'myCalendar';
		$year  = isset($_REQUEST['year']) ? $_REQUEST['year'] : date('Y');
		$month = isset($_REQUEST['month']) ? $_REQUEST['month'] : date('m'); 
		$day   = isset($_REQUEST['day']) ? $_REQUEST['day'] : date('d'); 
		
		$FCase   =  isset($_REQUEST['FCase']) ?  $_REQUEST['FCase'] : 'default'; 
		$TimeShow=  isset($_REQUEST['TimeShow']) ?  $_REQUEST['TimeShow'] : 'NO'; 
		$CUSTOM_FUNCTION=  isset($_REQUEST['CUSTOM_FUNCTION']) ?  $_REQUEST['CUSTOM_FUNCTION'] : ''; 
		$minYear   =  isset($_REQUEST['minYear']) ?  $_REQUEST['minYear'] : ""; 
		$maxYear   =  isset($_REQUEST['maxYear']) ?  $_REQUEST['maxYear'] : ""; 
		
		
		$res	=	$objCalendar->DrawCalendarMonth( $year ,$month, $day,$Elem,array('FCase'=>$FCase,'minYear'=>$minYear,'maxYear'=>$maxYear,'TimeShow'=>$TimeShow,'CUSTOM_FUNCTION'=>$CUSTOM_FUNCTION),'YES');
		
		echo $res. "|". $Elem;
		exit;
		break;	
		
	case "callendar_drag_month":
	    $Elem  = isset($_REQUEST['elem']) ? $_REQUEST['elem'] : 'myCalendar';
		$year  = isset($_REQUEST['year']) ? $_REQUEST['year'] : date('Y');
		$month = isset($_REQUEST['month']) ? $_REQUEST['month'] : date('m'); 
		$day   = isset($_REQUEST['day']) ? $_REQUEST['day'] : date('d'); 
		
		$FCase   =  isset($_REQUEST['FCase']) ?  $_REQUEST['FCase'] : 'default'; 
		$TimeShow=  isset($_REQUEST['TimeShow']) ?  $_REQUEST['TimeShow'] : 'NO'; 
		$CUSTOM_FUNCTION=  isset($_REQUEST['CUSTOM_FUNCTION']) ?  $_REQUEST['CUSTOM_FUNCTION'] : ''; 
		$minYear   =  isset($_REQUEST['minYear']) ?  $_REQUEST['minYear'] : ""; 
		$maxYear   =  isset($_REQUEST['maxYear']) ?  $_REQUEST['maxYear'] : ""; 
		
		
		//$res	=	$objCalendar->dragMonth( $year ,$month, $day,$Elem,array('FCase'=>$FCase,'minYear'=>$minYear,'maxYear'=>$maxYear,'TimeShow'=>$TimeShow,'CUSTOM_FUNCTION'=>$CUSTOM_FUNCTION),'YES');
		
		//echo $res. "|". $Elem;
		exit;
		break;	
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>