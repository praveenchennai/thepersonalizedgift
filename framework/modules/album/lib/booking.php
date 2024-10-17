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
include_once(FRAMEWORK_PATH."/modules/album/lib/class.property.php");
include_once(FRAMEWORK_PATH."/modules/event/lib/class.calendar.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.payment.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.photo.php");
include_once(FRAMEWORK_PATH."/modules/callendar/lib/class.calendarevents.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");



$objProperty    =	new Property();
$objUser    	=	new User();
$objCalendar 	=	new CalendarTool ();
$property 		=	new Property();
$PaymentObj		=	new Payment();
$PhotoObj		=	new Photo();
$objDate 		=   new Date();
$objCalendarEvents = new CalendarEvents();
$objFlyer 		= new Flyer();
$objEmail 		= new Email();
// Get the title of property 
// Afsal Imsail
	if(isset($_REQUEST['type'])){
		
		if(trim($_REQUEST['type']) == "propCal" && trim($_REQUEST['propid'])!= ""){
			$flyer			=	new	Flyer();
			
			$flyer_id = $flyer->getFlyerIDByAlbum($_REQUEST['propid']);
			$FlyerBasicData	=	$flyer->getFlyerBasicFormData($flyer_id);
			
		}
	}


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
	    $framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/album_booking.tpl");
	    break;
		
	case "book_album":
		
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
	case 'mycalendar_year':
		/* if from property search loging not required */
		//if(!isset($_REQUEST['action_from']))
		//checkLogin();
		
		//$req=&$_REQUEST;
		$req_type = $_REQUEST["action_from"];
		list($CALENDARLIST,$CALENDAR_NAVIG)= $objCalendarEvents->printCalendarValues($_REQUEST,$objCalendar,$_SESSION["memberid"],"YEAR",$FlyerBasicData["title"]);
		$YEAR_VIEW = $objCalendarEvents->printCalendar($CALENDAR_NAVIG,$CALENDARLIST,"YEAR",$req_type);
		
		
		if(isset($_REQUEST['type']) && $_REQUEST['type'] == "ajax"){// if type = ajax

			echo $YEAR_VIEW;
			exit;
			
		}else{
			
	    	$framework->tpl->assign("calendarList",$YEAR_VIEW);
	    	
		}
		break;
	case 'mycalendar_month':
	  	//checkLogin();
		list($CALENDARLIST,$CALENDAR_NAVIG)= $objCalendarEvents->printCalendarValues($_REQUEST,$objCalendar,$_SESSION["memberid"],"MONTH",$FlyerBasicData["title"]);
		$MONTH_VIEW = $objCalendarEvents->printCalendar($CALENDAR_NAVIG,$CALENDARLIST,"MONTH");
		
		if(isset($_REQUEST['type']) && $_REQUEST['type'] == "ajax"){// if type = ajax

			echo $MONTH_VIEW;
			exit;
			
		}else{
			
	    	$framework->tpl->assign("calendarList",$MONTH_VIEW);
	    	
		}
		break;
	case 'mycalendar_day':
		//checkLogin();
		
		list($CALENDARLIST,$CALENDAR_NAVIG)= $objCalendarEvents->printCalendarValues($_REQUEST,$objCalendar,$_SESSION["memberid"],"YEAR",$FlyerBasicData["title"]);
		$YEAR_VIEW = $objCalendarEvents->printCalendar($CALENDAR_NAVIG,$CALENDARLIST,"YEAR");
		
		if(isset($_REQUEST['type']) && $_REQUEST['type'] == "ajax"){// if type = ajax

			echo $YEAR_VIEW;
			exit;
			
		}else{
			
	    	$framework->tpl->assign("calendarList",$YEAR_VIEW);
		}
		break;
		
	case 'mycalendar_week':
		/* if from property search loging not required */
		if(!isset($_REQUEST['action_from']))
		checkLogin();
		
		$objEvents = new CalendarEvents();
		
		$datesY  	= isset($_REQUEST['year']) ? $_REQUEST['year'] : date('Y');
		$datesM 	= isset($_REQUEST['month']) ? $_REQUEST['month'] : date('m'); 
		$datesD   	= isset($_REQUEST['day']) ? $_REQUEST['day'] : date('d'); 
		
		if(!isset($_REQUEST['action_from']))
		$EVENTDAYS = $objEvents->GetEventYear($datesM, $datesY,$array,$_SESSION['memberid'],$_REQUEST['propid']);
		
		$objCalTime	=	new Calendar($datesY,$datesM,$datesD);
		$date  = $datesY."-".$datesM."-".$datesD;
		
		$week_start_date  = $objCalTime->getcurrent_week($date);
		$array = array('FCase'=>'property_booking','DISPLAY' => 'block','CUSTOM_FUNCTION' => 'dispWeekView','CLOSE_BUTTON' => 'no',"memberid" =>$_SESSION['memberid'],"propid" => $_REQUEST['propid'],"flyer_id" => $_REQUEST['flyer_id']);
		list($CALENDARLIST,$EVENTDAYS) = $objCalendar->DrawCalendarLayer($datesY,$datesM,$datesD,'calendarList',$array);
		list($weekList)	  = $objCalTime->weekList();
	
		$previousWeek = $objCalTime->prev_week($date);
		$nextWeek	  =	$objCalTime->next_week($date);
		
		
		$framework->tpl->assign("weeklist",$weekList);
		$framework->tpl->assign("EVENTDAYS",$EVENTDAYS);
		$framework->tpl->assign("currentweek",$week_start_date);
		$framework->tpl->assign("prevweek",$previousWeek);
		$framework->tpl->assign("nextweek",$nextWeek);
		
		if(trim($_REQUEST['type'])!= "" && trim($_REQUEST['propid'])!= "")
		$framework->tpl->assign("CALENDAR_NAVIG",$objCalendar->calenDarNavig($_REQUEST['act'],$_REQUEST['type'],$_REQUEST['propid'],$FlyerBasicData["title"],$_REQUEST['action_from']));
		else 
		$framework->tpl->assign("CALENDAR_NAVIG",$objCalendar->calenDarNavig($_REQUEST['act']));
		
		
		if(isset($_REQUEST['type']) && $_REQUEST['type'] == "ajax"){// if type = ajax
			
			echo $objCalendar->printCalendarWeekView($CALENDARLIST,$weekList,$date,$nextWeek,$previousWeek,$EVENTDAYS);
			exit;
			
		}else {
			
			$framework->tpl->assign("calendarList",$CALENDARLIST);
			
			if(!isset($_REQUEST['action_from'])){
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/mycalendar_week.tpl");
			}
			else {
			$framework->tpl->display(SITE_PATH."/modules/album/tpl/mycalendar_week_search.tpl");
			exit;
			}
			
		}
		break;
		
	case 'booking_payment':
		/**
		 * This case for Displaying Payment Details at the time of booking
		 * @author vimson@newagesmb.com
		 * 
		 */
		
		checkLogin();
		$PropertyId				=	$_REQUEST['propid'];
		$CheckIn				=	$_REQUEST['CheckIn1'];
		$CheckOut				=	$_REQUEST['CheckOut2'];
		//$QuantityTitleId		=	$_REQUEST['QuantityTitleId'];
		
		//$PropertyId				=	30;
		//$CheckIn				=	"2008-04-20";
		//$CheckOut				=	"2008-04-30";
		//$QuantityTitleId		=	1;
						
		$_SESSION['PropertyId']			=	$PropertyId;
		$_SESSION['CheckIn']			=	$CheckIn;
		$_SESSION['CheckOut']			=	$CheckOut;
		$_SESSION['QuantityTitleId']	=	$QuantityTitleId;
		
		$EncodedSessionId		=	$PaymentObj->encodeSession();
		
		//$BookingDetails			=	$objProperty->getBookingPriceDetails($CheckIn, $CheckOut, $PropertyId);
		$MemberDetails			=	$objUser->getUserdetails($_SESSION['memberid']);
		$Country2LetterCode		=	$objUser->getCountry2LetterCode($MemberDetails['country']);
		$PaypalInfo				=	$PaymentObj->getPaypalInformationOfProperty($PropertyId);
		$PropertyInformation	=	$objProperty->getBasicPropertyInfomation($PropertyId, $objAlbum, $PhotoObj);
		$WeekEnd_Price			=	$objProperty->getSpecialPriceOfParticularDate($PropertyId);
		
		$WEEK_PRICE				=   "";
		
		
		if($PropertyInformation["duration"] == "1" && strtolower($PropertyInformation["unit"]) == "day"){
				
			
				if($WeekEnd_Price["Price"] != "NA"){
					if($WeekEnd_Price["type"] == "pr"){
						
						$WEEK_PRICE = $WeekEnd_Price["Price"];
						
						if($WEEK_PRICE > 0){
							$WEEK_PRICE = " $".$WeekEnd_Price["Price"]." Extra";
						}else{
							$WEEK_PRICE = " $".abs($WeekEnd_Price["Price"])." Offer";
						}
						
					}else{
						
						$WEEK_PRICE = $WeekEnd_Price["Price"];
						
						if($WEEK_PRICE > 0){
							$WEEK_PRICE = $WeekEnd_Price["Price"]."% Extra";
						}else{
							$WEEK_PRICE = abs($WeekEnd_Price["Price"])."% Offer";
						}
					}
					$WEEK_END_PRICE = $framework->MOD_VARIABLES["MOD_LABELS"]["LBL_WEEK_END_PRICE"].$WEEK_PRICE;
				}
					
		}

		$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
		$framework->tpl->assign('ENCODED_SESSION_ID', $EncodedSessionId);
		$framework->tpl->assign('CHECK_IN', $CheckIn);
		$framework->tpl->assign('CHECK_OUT', $CheckOut);
		$framework->tpl->assign('PROPERTY_ID', $PropertyId);
		$framework->tpl->assign('PROPERTY_INFORMATION', $PropertyInformation);
		$framework->tpl->assign('PAYPAL_INFO', $PaypalInfo);
		$framework->tpl->assign('TOTAL_BOOKING_PRICE', $BookingDetails['TotalBookingPrice']);
		$framework->tpl->assign('PROPERTY_NUMBER', $PropertyId);
		$framework->tpl->assign('COUNTRY2_CODE', $Country2LetterCode);
		$framework->tpl->assign('MEMBER_DETAILS', $MemberDetails);
		$framework->tpl->assign('SESSION_EXP_MSG',$framework->MOD_VARIABLES["MOD_JS"]["JS_BOK_SESSION_EXP"]);
		$framework->tpl->assign('MINIMUM_RENT_DURATION',$PropertyInformation["min_duration"]);
		///$framework->tpl->assign('BOOKING_DETAILS',$BookingDetails); afsal
		
		
		$datesY  	= isset($_REQUEST['year']) ? $_REQUEST['year'] : date('Y');
		$datesM 	= isset($_REQUEST['month']) ? $_REQUEST['month'] : date('m'); 
		$datesD   	= isset($_REQUEST['day']) ? $_REQUEST['day'] : date('d'); 
		$Elm = $_REQUEST["elm"];
		
		$days = $objCalendarEvents->setBackGroundColor($PropertyId,$datesM,$datesY,true,true,"Yes");
		
		$array_start = array('CUSTOM_FUNCTION' => 'dispEventCalendar',"propid" => $PropertyId,"event_arry" => $days,"cal" => "start");
		$array_end  = array('CUSTOM_FUNCTION' => 'dispEventCalendar',"propid" => $PropertyId,"event_arry" => $days,"cal" => "end");
		
	
		if($_REQUEST['type'] == "ajax"){	
			
			if($Elm == "check_in")
			$array = $array_start;
			else
			$array = $array_end;

			$CALENDAR_MONTH = $objCalendar->DrawCalendarEvents($datesY,$datesM,$datesD,$Elm,$array,"YES");
			
			echo $CALENDAR_MONTH."|".$Elm."|".$_SESSION["memberid"];
			exit;
		}
		
		
		$framework->tpl->assign("WEEK_END_PRICE",$WEEK_END_PRICE);
		
		
		$framework->tpl->assign("checkIn",$objCalendar->DrawCalendarEvents(date('Y'),date('m'),date('d'),"check_in",$array_start));
		$framework->tpl->assign("checkOut",$objCalendar->DrawCalendarEvents(date('Y'),date('m'),date('d'),"check_out",$array_end));
		
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/booking_paymentdetails.tpl");
		break;
	
	case 'booking_payment_notify':
		/**
		 * A request from the paypal with the post information such as transaction id in case of successful payment
		 * @author vimson@newagesmb.com
		 * 
		 */
	$to      = 'pljinson@yahoo.com';
    $subject = 'bayard';
    $message = $_REQUEST['custom'];
    $headers = 'From: webmaster@example.com' . "\r\n" .
    'Reply-To: webmaster@example.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);

		$EncodedSessionId	=	$_REQUEST['EncodedSessionId'];
		$PaymentObj->decodeSession($EncodedSessionId);
		$PropertyId			=	$_SESSION['PropertyId'];
		$CheckIn			=	$_SESSION['CheckIn'];
		$CheckOut			=	$_SESSION['CheckOut'];
		$MemberId			=	$_SESSION['memberid'];
		$totalamount		=   $_SESSION["totalamount"];
		$objProperty->saveBookingInformations($_POST, $PropertyId, $CheckIn, $CheckOut,$MemberId, $PaymentObj, $objUser,$totalamount);
		$objProperty->propertyStay($_POST);
		$MemberId=$_SESSION['memberid'];
		//####   for dending mails to seller broker ,buyer and manager
		$type="PropertyBookingOwner";
		$email->mailSend($type, $MemberId,$_SESSION);
		$type="PropertyBookingBuyer";
		$email->mailSend($type, $MemberId,$_SESSION);
		$type="PropertyBookingBroker";
		$email->mailSend($type, $MemberId,$_SESSION);
		$type="PropertyBookingManager";
		$email->mailSend($type, $MemberId,$_SESSION);
		//##################################
		
		
		break;

		
	case 'bid_payment':
		/**
		 * The following case is for payment for bid auction
		 * 
		 */
		$bid_id					=	$_REQUEST['bid_id'];
		$BidDetails				=	$objProperty->getBidPaymentDetails($bid_id);
		$MemberDetails			=	$objUser->getUserdetails($BidDetails['BidderId']);
		$Country2LetterCode		=	$objUser->getCountry2LetterCode($MemberDetails['country']);
		$PropInfo				=	$objProperty->getBasicPropertyInfomation($BidDetails['album_id'], $objAlbum, $PhotoObj);
		
		$framework->tpl->assign('COUNTRY2_CODE', $Country2LetterCode);
		$framework->tpl->assign('PAYPAL_POST_URL', $framework->config['paypal_post_url']);
		$framework->tpl->assign('MEMBER_DETAILS', $MemberDetails);
		$framework->tpl->assign('BID_INFORMATION', $BidDetails);
		$framework->tpl->assign('PROPERTY_INFORMATION', $PropInfo);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/bid_payment.tpl");
		break;
	
	
		
	case 'bid_payment_notify':
		/**
		 * The following case called by the paypal payment gateway after successful payment
		 */
		$objProperty->processBidPayment($_POST);
		break;
		

	case 'bid_payment_succeess':
		/**
		 * Here you can specify the after successful bid
		 */
		 $bid_id=$_REQUEST['bid_id'];
		 
		 $bid_det  			= $objFlyer->getBidDetails($bid_id);
		 $pricing_det 		= $objFlyer->getPricingDetails($bid_det['pricing_id']);
		 $bid_user_det   	= $objUser->getUserDetails($bid_det['user_id']);
		 $flyer_id    		= $objFlyer->getFlyerIDByAlbum($pricing_det->album_id);
		 $flyer_det   		= $objFlyer->GetFlyerData($flyer_id);
		 
		 $mail_header = array();
		 $mail_header['from'] 	= 	$framework->config['site_name']."<".$framework->config['admin_email'].">";
		 $mail_header["to"]   	= $bid_user_det['email'];
		 $dynamic_vars = array();
		 $dynamic_vars['BID_USER'] = $bid_user_det['username'];
		 ($flyer_det['flyer_name']!="")? $prop_name = $flyer_det['flyer_name'] : $prop_name = $flyer_det['title'];
			
		 $dynamic_vars['PROPERTY_NAME'] = $prop_name;
		 $dynamic_vars['PROPERTY_DESC'] = $flyer_det['description'];
		 $dynamic_vars['START_DATE'] = date($framework->config['date_format_new'],strtotime($pricing_det->start_date));
		 $dynamic_vars['END_DATE'] = date($framework->config['date_format_new'],strtotime($pricing_det->rental_end_date));
		 $dynamic_vars['DURATION'] = $pricing_det->duration;
		 $dynamic_vars['UNIT'] = $pricing_det->unit;
		 $dynamic_vars['BID_AMT'] = "$".$bid_det['bid_amount'];
		 $dynamic_vars['BID_DATE'] = date($framework->config['date_format_new'],strtotime($bid_det['bid_date']));
		 $lost_bids = $objFlyer->getAllBidsByPricing($bid_det['pricing_id']);
		
		for($i=0;$i<sizeof($lost_bids);$i++)
		{
			if ($bid_id!=$lost_bids[$i]['id'])
			{
				$lost_bid_det = $objUser->getUserdetails($lost_bids[$i]['user_id']);
				$dynamic_vars['BID_USER'] = $lost_bid_det['username'];
				$dynamic_vars['BID_AMT'] = "$".$lost_bids[$i]['bid_amount'];
				$dynamic_vars['BID_DATE'] = date($framework->config['date_format_new'],strtotime($lost_bids[$i]['bid_date']));
	
				$mail_header["to"]   = $lost_bid_det['email'];
				$objEmail->send("Property_Auction_Lost",$mail_header,$dynamic_vars);
			}	
		}
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/bid_payment_success.tpl");		
		break;
		
	case 'invoice_payment':
		$InvoiceId	  =	$_REQUEST['InvoiceId'];
		$SentToId	  =	$_REQUEST['SentToId'];
		$MemberId	  =	$_REQUEST['MemberId'];
		$start_date   = $_REQUEST['start_date'];
		$end_date	  =	$_REQUEST['end_date'];
		$prop_bill_id = $_REQUEST['prop_bill_id']; 
		$PaymentObj->makeInvoicePayments($_POST, $InvoiceId, $SentToId, $MemberId,$start_date,$end_date,$prop_bill_id);
		break;
		
		
	case 'totalprice':
			
			$propid 	= $_REQUEST["propid"];
			$check_in 	= $_REQUEST["check_in"];
			$check_out 	= $_REQUEST["check_out"];
			
			$check_in  = $objFlyer->convertToMySqlFormat($check_in);
			$check_out = $objFlyer->convertToMySqlFormat($check_out);
			
			echo $objFlyer->albumPriceCalculation($propid,$check_in,$check_out);
			
			exit;
		break;
	case 'displayBookingPrice':
		
			$propid 	= $_REQUEST["propid"];
			$check_in 	= $_REQUEST["startDate"];
			$check_out 	= $_REQUEST["endDate"];
			$totAmt		= $_REQUEST["totAmt"];	
			
			$check_in  = $objFlyer->convertToMySqlFormat($check_in);
			$check_out = $objFlyer->convertToMySqlFormat($check_out);
			
			
			if(!$objProperty->checkInDateGreater($check_in,$check_out)){
				echo  $framework->MOD_VARIABLES["MOD_LABELS"]["LBL_PROP_MSG6"]."|"."error";
				exit;
			}
			
			/* Check checkout and checkin date equla */
			if(!$objProperty->checkInDatecheckOutDateNotEqual($check_in,$check_out)){
				echo  $framework->MOD_VARIABLES["MOD_ERRORS"]["MSG_NOT_EQUAL_DATE"]."|"."error";
				exit;
			}
			
			/* Check the avialability of property */
			if($objProperty->checktheAvialabiltyOfProperty($check_in,$check_out,$propid)){
			echo  $framework->MOD_VARIABLES["MOD_LABELS"]["LBL_PROP_AVIALABLE_MSG"]."|"."error";
			exit;
			}
			
			/*Property  minimum and maximum booking check*/
			$rs = $objProperty->getFlyerDet($propid);
			$minimum_day = $rs["minimum_booking_days"];
			$maximun_day = $rs["maximum_booking_days"];
			$val = $objProperty->bookingMinimumAndMaximumDate($propid,$check_in,$minimum_day,$maximun_day);
		
		
			if($val == "0")	{
					$message1 = $framework->MOD_VARIABLES["MOD_LABELS"]["LBL_PROP_AVIALABLE_MSG2"];
					$message1 = str_replace("%","$minimum_day day's",$message1);
				echo $message1."|"."error";
				exit;
			}elseif($val == "1"){
					$message2 = $framework->MOD_VARIABLES["MOD_LABELS"]["LBL_PROP_AVIALABLE_MSG3"];
					$message2 = str_replace("%","$maximun_day day's",$message2);
				echo $message2."|"."error";
				exit;
			}
		
				$bking_percent = $objProperty->getBookingCharges($propid);
				 
				
				
				$check_out = date("Y-m-d",strtotime("$check_out -1 day"));
				
				list($freeFloatDate,$singleFreeFloatDate) = $objProperty->getFreeFloatingDays($check_in,$check_out,$propid);
				$row = $objProperty->getFreefloatingPrice($propid);
				$check_out = $objProperty->setTheFreefloatingDays($freeFloatDate,$singleFreeFloatDate,$row["duration"],$row["unit"],$check_in,$propid,$check_out);
				
				
				/* Check the avialability of property */
					if($objProperty->checktheAvialabiltyOfProperty($check_in,$check_out,$propid)){
					echo  $framework->MOD_VARIABLES["MOD_LABELS"]["LBL_PROP_AVIALABLE_MSG"]."|"."error";
					exit;
					}
					
				/* Check the avialability of property */
			
					if(!$objProperty->checkPropertyBookingBetweenDate($check_in,$check_out,$propid)){
					echo  $framework->MOD_VARIABLES["MOD_LABELS"]["LBL_PROP_AVIALABLE_MSG"]."|"."error";
					exit;
					}
					
				list($displayOrderHtm,$bookingCharge,$totAmount)=$objProperty->displayBookingCharges($check_in,$check_out,$propid,$bking_percent,$objCalendarEvents);
				
				$_SESSION['PropertyId']			=	$propid;
				$_SESSION['CheckIn']			=	$check_in;
				$_SESSION['CheckOut']			=	$check_out;
				$_SESSION['totalamount']		=	$totAmount;
				$EncodedSessionId				=	$PaymentObj->encodeSession();
				
				echo $displayOrderHtm."|".$bookingCharge."|".$totAmount."|".$EncodedSessionId."|".$_SESSION["memberid"];
				exit;
		break;
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>