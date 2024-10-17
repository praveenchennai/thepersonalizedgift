<?
include_once(FRAMEWORK_PATH."/modules/event/lib/calendar.config.php");
include_once(FRAMEWORK_PATH."/modules/event/lib/class.event.php");
include_once(FRAMEWORK_PATH."/modules/callendar/lib/class.calendartool.php");


	$objEvent = new Event ();
	$objCalendar = new CalendarTool ();
	
	checkLogin();
	if($_REQUEST['act'] != 'details')
	{
		$framework->tpl->assign("pb",$objEvent->eventCategory('pb'));// public category
		$framework->tpl->assign("pr",$objEvent->eventCategory('pr'));// private category
		$framework->tpl->assign("COUNTRY_LIST", $objEvent->listCountry());
		
		$timeArray = $objEvent->timeArray() ;
		$framework->tpl->assign("myHour",$timeArray[0]);
		$framework->tpl->assign("myMinute",$timeArray[1]);
		$framework->tpl->assign("ampm",$timeArray[2]);

	}
	switch( $_REQUEST['act'])
	{
		case "details":
			/*
			Event Ddetails
			eventAcceptCheck contain tow arrays
			
			*/
			$array = $objEvent->eventAcceptCheck($_REQUEST['eventid'],$_SESSION['memberid']);
			
			if (count($array["invite_accept"]) > 0)
			{
				$invite_accept = "Yes";
				$status =  $array["invite_accept"];
				$invite_accept_status = $status['event_rsvp'];
			}
			else
			{
				$invite_accept = "No";
			}
				
			if (count($array["rsvp_yesno"]) > 0)
				$rsvp_yesno = "Yes";
			else
				$rsvp_yesno = "No";
			
			/*
			Assign invite accept status
			*/
			if(($rsvp_yesno == "Yes" || $rsvp_yesno == "No") && $invite_accept == "Yes")
			{
				$framework->tpl->assign("INVITE_ACCEPT_STATUS_INDEX",$invite_accept_status);
				$framework->tpl->assign("INVITE_ACCEPT_STATUS",$objEvent->rsvpStatusDescription($invite_accept_status));
			}
			if($rsvp_yesno == "Yes" && $invite_accept == "No")
				$show_comment = 1;
			elseif($rsvp_yesno == "Yes" && $invite_accept_status == 'nil')
				$show_comment = 1;
			elseif($rsvp_yesno == "Yes" && $_REQUEST['common'] == "showRsvp")
				$show_comment = 1;
			else
				$show_comment = 0;
			$framework->tpl->assign("SHOW_COMMENT",$show_comment);
			$framework->tpl->assign("INVITE_ACCEPT",$invite_accept);
			$framework->tpl->assign("RSVP_YESNO",$rsvp_yesno);
			$framework->tpl->assign("RSVP",$objEvent->eventAcceptCheck($_REQUEST['eventid'],$_SESSION['memberid']));
			$framework->tpl->assign("GUEST_NO",$objEvent->eventGuest($_REQUEST['eventid']));
			$framework->tpl->assign("EVENT_DET",$objEvent->eventDetails($_REQUEST['eventid']));
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/event/tpl/event_detail.tpl");
			
			if(count($_POST))
			{
				if($_REQUEST['replay'] == "Replay Now"){
					$objEvent->eventAccept($_SESSION["memberid"],$_REQUEST['eventid']);
					$framework->tpl->assign("main_tpl",SITE_PATH."/modules/event/tpl/message.tpl");
				}
				if($_REQUEST['calendar'] == "Add to My Calendar")
				{
					$objEvent->eventAccept($_SESSION["memberid"],$_REQUEST['eventid']);
					redirect(makeLink(array("mod"=>"event", "pg"=>"event"), "eventid=".$_REQUEST['eventid']."&act=details&common=remove"));
				}
				elseif($_REQUEST['calendar'] == "Remove from Calendar")
				{
					$objEvent->removeEvents($_REQUEST['eventid']);
					redirect(makeLink(array("mod"=>"event", "pg"=>"event"), "eventid=".$_REQUEST['eventid']."&act=details"));
				}
			}
			
		break;
		case "edit":
			/*
			Event edit
			*/
			if(count($_POST))
			{
				$objEvent->updateEvent($_REQUEST['eventid']);
				redirect(makeLink(array("mod"=>"event", "pg"=>"event"), "eventid=".$_REQUEST['eventid']."&act=details"));
			}
			else
			{
				$editflg="true";
				$framework->tpl->assign("EDITFLAG",$editflg);
				$framework->tpl->assign("EVENT_DET",$objEvent->eventDetails($_REQUEST['eventid']));
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/event/tpl/event.tpl");
			
			}
			
		break;
	    case "invite":
			/*
			Event invite the people
			*/
			
			if(count($_POST))
			{
				$objEvent->invitePeople();
				
				redirect(makeLink(array("mod"=>"event", "pg"=>"event"), "eventid=".$_REQUEST['eventid']."&act=details"));
				
			}
			else
			{
				$framework->tpl->assign("EVENT_INVITE",$objEvent->eventInvite($_REQUEST['eventid']));
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/event/tpl/event_invite.tpl");
	
			}

	   break;
	   case "eventmail":
	   
				$framework->tpl->assign("EVENT_INVITE_MAIL",$objEvent->eventInternalMails($_SESSION["memberid"]));
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/event/tpl/event_invite_request.tpl");
		break;
		case "eventmail_det":

			$val = $objEvent->eventInternalMailsDetails();
			$framework->tpl->assign("EVENT_INVITE_MAIL",$objEvent->eventInternalMailsDetails());
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/event/tpl/event_invite_req_details.tpl");
			
		break;
		case  "evhome":
			$params = "mod=event&pg=event&act=evhome";
			$page   = $_REQUEST['pageNo'];
			list($rs, $numpad,$cnt_rs, $limitList) = $objEvent->eventListing($page,$_REQUEST['limit'],$params,"",$_REQUEST['orderBy'],$_REQUEST['txtSearch'],$_REQUEST['public_cat']);
			$framework->tpl->assign("EVENTDET",$rs);
			$framework->tpl->assign("NUMPAD",$numpad);
			$framework->tpl->assign("CNTRS",$cnt_rs);
			$framework->tpl->assign("LIMITLIST",$limitList);
			$framework->tpl->assign("CATEGORY",$objEvent->eventCategory('pb'));
			$framework->tpl->assign("FEATURED",$objEvent->featureEventList());
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/event/tpl/event_home.tpl");
			
		break;
		case "evposted":

			$params = "mod=event&pg=event&act=evposted";
			$page = $_REQUEST['pageNo'];
			$result = $objEvent->eventPosted($page,$limit,$params," AND em.mem_id = {$_SESSION['memberid']}");
			$framework->tpl->assign("EVENT_POSTED",$result[0] );
			$framework->tpl->assign("EVENT_STR",$result[1] );
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/event/tpl/event_posted.tpl");
			
		break;
		case "evattend":
			
			$params = "mod=event&pg=event_posted";
			$page = $_REQUEST['pageNo'];
			$result = $objEvent->eventPosted($page,$limit,$params," AND em.mem_id = {$_SESSION['memberid']} AND ea.event_rsvp = 'yes'");
			$framework->tpl->assign("EVENT_POSTED",$result[0] );
			$framework->tpl->assign("EVENT_STR",$result[1] );
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/event/tpl/event_posted.tpl");

		break;
		case "upload":
			
			if(count($_POST))
			{
				$objEvent->uploadPhoto($_FILES['eventphoto'],$_REQUEST['eventid']);
				redirect(makeLink(array("mod"=>"event", "pg"=>"event"), "eventid=".$_REQUEST['eventid']."&act=details"));
			}
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/event/tpl/photo.tpl");
			
		break;
		case "mycalendar":
			
			$sel_date = $_REQUEST['year']."-".$_REQUEST['month']."-".$_REQUEST['day'];
			$time  = split(":",date("h:i:s A",strtotime($_REQUEST['time'])));
			$am_pm = split(" ",date("h:i:s A",strtotime($_REQUEST['time'])));
			$mArray = $objEvent->hourminuteArray();
			$framework->tpl->assign("HOUR",$mArray[0]);
			$framework->tpl->assign("MINUTES",$mArray[1]);
			$framework->tpl->assign("REMVAL",$objEvent->reminderVa());
			$framework->tpl->assign("H",$time[0]);
			$framework->tpl->assign("M",$time[1]);
			$framework->tpl->assign("PM",$am_pm[1]);
			$framework->tpl->assign("CDATE",$sel_date);
			
			if (count($_POST))
			{
				if($_REQUEST['event_date'] < date("Y-m-d"))
				{
					setMessage('Select a future date.',MSG_ERROR);
					$framework->tpl->assign("H",$_REQUEST['hour']);
					$framework->tpl->assign("M",$_REQUEST['minute']);
					$framework->tpl->assign("PM",$_REQUEST['ampm']);
					$framework->tpl->assign("DURH",$_REQUEST['hour_dur']);
					$framework->tpl->assign("DURM",$_REQUEST['minute_dur']);
					$framework->tpl->assign("EVENT_DET",$_REQUEST);
				}
				else
				{
					$eventid = $objEvent->insertCalendar();
					redirect(makeLink(array("mod"=>"event", "pg"=>"event"), "eventid=".$eventid."&act=mycalendar_det"));
					exit;
				}

			}
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/event/tpl/event_my_calendar.tpl");
			
		break;
		case "mycalendar_edit":
			
			if(count($_POST) && $_REQUEST['eventid'] != '')
			{
				$eventid = $objEvent->updateCalendar($_REQUEST['eventid']);
				redirect(makeLink(array("mod"=>"event", "pg"=>"event"), "eventid=".$eventid."&act=mycalendar_det"));
				exit;
			}
			
			$array  = $objEvent->calendarDetails($_REQUEST['eventid']);
			$time[0]  =  date("h",strtotime($array["event_time"]));
			$time[1]  =  date("i",strtotime($array["event_time"]));
			$time[2]  =  date("A",strtotime($array["event_time"]));
			
			$duration[0] = date("h",strtotime($array["event_duration"]));
			$duration[1] = date("i",strtotime($array["event_duration"]));
			
			$mArray = $objEvent->hourminuteArray();
			$framework->tpl->assign("HOUR",$mArray[0]);
			$framework->tpl->assign("MINUTES",$mArray[1]);
			$framework->tpl->assign("REMVAL",$objEvent->reminderVa());
			$framework->tpl->assign("H",$time[0]);
			$framework->tpl->assign("M",$time[1]);
			$framework->tpl->assign("PM",$time[2]);
			$framework->tpl->assign("CDATE",$sel_date);
			$framework->tpl->assign("DURH",$duration[0]);
			$framework->tpl->assign("DURM",$duration[1]);

			$framework->tpl->assign("REMINDER",$objEvent->reminderIsAdded($_REQUEST['eventid']));
			$framework->tpl->assign("EVENT_DET",$objEvent->calendarDetails($_REQUEST['eventid']));
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/event/tpl/event_my_calendar.tpl");
		
		break;
		case "mycalendar_det":
			
			$framework->tpl->assign("EVENT_DET",$objEvent->calendarDetails($_REQUEST['eventid']));
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/event/tpl/event_my_calendar_details.tpl");
			
		break;
		case "meeting":

				if($_SERVER['REQUEST_METHOD']=="POST"){
				
					$req = $_POST;
					$req["event_date"] = $_POST['txtcalendarStart'];
					unset($req["txtcalendarStart"]);				
					
					/** Update meeting details*/
					if($_REQUEST['id']){
						
						$objEvent->updateCalendar($_REQUEST['id'],$req,$_SESSION["memberid"],"meeting");
						redirect(makeLink(array("mod"=>"event", "pg"=>"event"), "act=meetlist"));
					}
					else
					{
						$req["event_date"] = $_POST['txtcalendarStart'];
						unset($req["txtcalendarStart"]);				
						$objEvent->insertCalendar($req,$_SESSION['memberid'],"meeting");
					}

				}
				
				/** Edit meeting details*/
				if($_REQUEST['id']){
					
					/** Get the meeting details for edit purpose*/
					$rs = $objEvent->getMeetingListRow($_SESSION['memberid'],$_REQUEST['id']);
					$framework->tpl->assign("MEETLISTROW",$rs);
					
					
					/** Get hour from the event_duration(datebase field) for displaying hour to textbox*/
					$durationHour = split(":",$rs->event_duration);
					$framework->tpl->assign("EVTDUR",$durationHour[0]);
					
				}
				
				/**Get reminder values */
				$reminderVal = $objEvent->getReminderValues();
				$framework->tpl->assign("REMINDERVALUE",$reminderVal);
				
				
				

	   		$framework->tpl->assign("calendarStart",$objCalendar->DrawCalendarLayer(date('Y'), date('m'),date('d'),'calendarStart',array('FCase'=>'') ));
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/event/tpl/event_meeting.tpl");
			
		break;
		case "meetlist":
			
				
				$params = "mod=".$mod."&pg=".$pg."&act=".$_REQUEST['act'];
				/** Get the meeting details of user*/
				list($meetList,$numpad) = $objEvent->getMeetingList($_SESSION["memberid"],$_REQUEST['pageNo'], 10, $params, $output=OBJECT,'em.event_id DESC');
				$framework->tpl->assign("MEETLIST",$meetList);
				$framework->tpl->assign("NUMPAD",$numpad);
			
		
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/event/tpl/meeting_list.tpl");
		break;
		case "":
		
			/*
			Event creation
			*/
			
			if(count($_POST)){
				
				$number   = $_POST['txtNumber'];
				if($_REQUEST['event_date'] < date("Y-m-d"))
				{
					setMessage('Select a future date.',MSG_ERROR);
					$array = array("event_time" => $_REQUEST['hour'].":".$_REQUEST['minute']." ".$_REQUEST['ampm']);
					$framework->tpl->assign("EVENT_DET",$_REQUEST = $_REQUEST + $array);
				}
				elseif (md5($number) != $_SESSION['image_random_value'])
				{
					setMessage('Please Enter Correct Code.',MSG_ERROR);
					$array = array("event_time" => $_REQUEST['hour'].":".$_REQUEST['minute']." ".$_REQUEST['ampm']);
					$framework->tpl->assign("EVENT_DET",$_REQUEST = $_REQUEST + $array);

				}
				else
				{
			
				$eventid = $objEvent->insertEvent($_REQUEST['eventid']);
				redirect(makeLink(array("mod"=>"event", "pg"=>"event"), "eventid=".$eventid."&act=invite"));
				
				}
			}
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/event/tpl/event.tpl");
	}
	
	$framework->tpl->display($global['curr_tpl']."/inner.tpl");		

?>
