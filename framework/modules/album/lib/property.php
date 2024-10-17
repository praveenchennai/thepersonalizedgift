<?php
if($_REQUEST['manage']=="manage"){
	authorize_store();
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("PG","cms_album") ;
	
	$framework->tpl->assign("STORE_PERMISSION",$store->getStorePermissionById($store_id,$module_menu_id)) ;
}else{
	//authorize();
	$framework->tpl->assign("MOD","album") ;
	$framework->tpl->assign("PG","property") ;
	$STORE = array('hide'=>'Y','add'=>'Y','edit'=>'Y','delete'=>'Y');
	$framework->tpl->assign("STORE_PERMISSION",$STORE) ;
}


include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.property.php");
include_once(FRAMEWORK_PATH."/modules/callendar/lib/class.calendartool.php");
		

$objProperty       = new Property();
$objUser    	   = new User();
$objCalendar = new CalendarTool ();


switch($_REQUEST['act'])
{

    case "property_booking":
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$req = &$_POST;
			if( ($message = $objProperty->addEditPropBooking($req)) === true)	{
				$message = "Album Booked Successfully...";
				setMessage($message,2);
			} else {
		    	setMessage($message,1);
			}
		}
		
		if($_REQUEST['dates']) {
			$datesArr	=	explode("-",$_REQUEST['dates']);
			$datesY		=	$datesArr[0];
			$datesM		=	$datesArr[1];
			$datesD		=	$datesArr[2];
		} else {
			$datesY		=	date('Y');
			$datesM		=	date('m');
			$datesD		=	date('d');
		}
		
		
	    $framework->tpl->assign("calendarFrom",$objCalendar->DrawCalendarLayer($datesY, $datesM,$datesD,'calendarFrom',array('FCase'=>'property_booking') ));
	    $framework->tpl->assign("calendarTo",$objCalendar->DrawCalendarLayer($datesY, $datesM,$datesD,'calendarTo',array('FCase'=>'property_booking','TimeShow'=>'YES') ));
	    $framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/property_booking.tpl");
	    break;
		
	case "my_callendar":
	
        list($rs, $numpad) = $objProperty->propertyListAll($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&orderBy=".$_REQUEST['orderBy'], OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("PROPERTY_LIST", $rs);
        $framework->tpl->assign("PROPERTY_NUMPAD", $numpad);
		
		if($_REQUEST['dates']) {
			$datesArr	=	explode("-",$_REQUEST['dates']);
			$datesY		=	$datesArr[0];
			$datesM		=	$datesArr[1];
			$datesD		=	$datesArr[2];
		} else {
			$datesY		=	date('Y');
			$datesM		=	date('m');
			$datesD		=	date('d');
		}
		
	    $framework->tpl->assign("calendarList",$objCalendar->DrawCalendarYear($datesY,$datesM,$datesD,'calendarList',array('FCase'=>'property_booking') ));
		
		
		
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/album_manage_booking.tpl");
		break;
	
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>