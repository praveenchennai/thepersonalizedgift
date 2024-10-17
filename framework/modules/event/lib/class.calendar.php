<?
/*
Authore : AFSAL ISMAIL
CALENDAR FUNCTIONALITY
*/
class Calendar extends FrameWork{
			
			var $year;
			var $month;
			var $day;
			
		function Calendar($year=0,$month=0,$day=0){
		
			$this->FrameWork();
			$this->year  = $year;
			$this->month = $month;
			$this->day   = $day;
			
		}
		function getEventDays($month, $year) {
			
			/*
			  $days = array();
			  $sql = mysql_query("SELECT DAY(event_date) AS day, COUNT(event_id) FROM event_venue WHERE MONTH(event_date) = '$month' AND YEAR(event_date) = '$year' GROUP BY day");
			
			  if (mysql_num_rows($sql) > 0) {
			  while ($row = mysql_fetch_array($sql)) 
			  	$days[] = $row['day'];
			  }
			*/
			   $days = array();
			   $sql  = "SELECT DAY(ev.event_date) AS day, COUNT(ev.event_id) FROM event_venue AS ev INNER JOIN 
			   			event_invite_accept AS em ON ev.event_id = em.event_id 
			            WHERE MONTH(event_date) = '$month' AND YEAR(event_date) = '$year' AND em.mem_id = {$_SESSION['memberid']} GROUP BY day";
			   $rs   =  $this->db->get_results($sql,ARRAY_A);
			   if(count($rs) > 0)
			   {
				   foreach($rs as $row)
				   {
						$days[] = $row['day'];
				   }
				}
			   return $days;
		} 
		
		function drawCalendar($month, $year,$day,$chkNavig='') {
			
			  if($chkNavig != 'no')
			  $navig = $this->navig($month, $year,$day);
			  // set variables we will need to help with layouts
			  $first       = mktime(0,0,0,$month,1,$year); // timestamp for first of the month
			  $offset      = date('w', $first); // what day of the week we start counting on
			  $daysInMonth = date('t', $first);
			  $monthName   = date('F', $first);
			  $weekDays    = array('Su', 'M', 'Tu', 'W', 'Th', 'F', 'Sa');
			  $eventDays   = $this->getEventDays($month, $year);
			  
			  // Start drawing calendar
			  $out  = "<table id=\"myCalendar\" border='0' class='forcolor' width='100%'>\n";
			  $out .= "<tr><td align='center' colspan=\"7\">\n";
			  $out .= "<table align='center' border='0' width='100%' cellspacing='0' cellpadding='0'>\n";
			  $out .= "<tr class='topcolor'><td align='left'>{$navig['prev']}</td>\n";
			  $out .= "<td align='center'>$monthName $year</td>\n";
			  $out .= "<td align='right'>{$navig['next']}</td>\n";
			  $out .= "</tr></table></td></tr>\n";
			  $out .= "<tr><th colspan=\"7\"></th></tr>\n";
			  $out .= "<tr>\n";
			  foreach ($weekDays as $wd) $out .= "<td class=\"weekname\">$wd</td>\n";
			  
			  $i = 0;
			  for ($d = (1 - $offset); $d <= $daysInMonth; $d++) {
				if ($i % 7 == 0) $out .= "<tr>\n"; // Start new row
				if ($d < 1) $out .= "<td class=\"nonMonthDay\">&nbsp;</td>\n";
				else {
				  if (in_array($d, $eventDays)) {
					$out .= "<td class=\"monthDay\">\n";
					$out .= "<a href=\"".makelink(array('mod' => 'event','pg'=>'day_view'),'year='.$year.'&month='.$month.'&day='.$d)."\" class=\"event\">$d</a>\n";
					$out .= "</td>\n";
				  } else $out .= "<td><a href=\"".makelink(array('mod' => 'event','pg'=>'day_view'),'year='.$year.'&month='.$month.'&day='.$d)."\" class=\"nonevent\">$d</a></td>\n";
				}
				++$i; // Increment position counter
				if ($i % 7 == 0) $out .= "</tr>\n"; // End row on the 7th day
			  }
			  
			  // Round out last row if we don't have a full week
			  if ($i % 7 != 0) {
				for ($j = 0; $j < (7 - ($i % 7)); $j++) {
				  $out .= "<td class=\"nonMonthDay\">&nbsp;</td>\n";
				}
				$out .= "</tr>\n";
			  }
			  
			  $out .= "</table>\n";
				
				return $out;
		}
		function navig($month, $year,$day){
			// Previous month link
			    $PATH_CALENDAR = SITE_URL."/templates/".$this->config['curr_tpl'];
				
				$prevTS = strtotime("$year-$month-01 -1 month"); // timestamp of the first of last month
				$pMax = date('t', $prevTS);
				$pDay = ($day > $pMax) ? $pMax : $day;
				list($y, $m) = explode('-', date('Y-m', $prevTS));
				
				if($_REQUEST['pg'] == "week_view")
				{
					$date  = $year."-".$month."-".$day;
					$prev_week = $this->prev_week($date);
					$y		   = $prev_week[0];
					$m		   = $prev_week[1];
					$pDay	   = $prev_week[2];
				$prev= "<a href=\"".makelink(array('mod' => 'event','pg'=>'week_view'),'year='.$y.'&month='.$m.'&day='.$pDay).
				"\"><img src='$PATH_CALENDAR/images/left_white_arrow.gif' border='0'><img src='$PATH_CALENDAR/images/left_white_arrow.gif' border='0'></a> ";

				}
				else
				{
					  if($_REQUEST['pg'] == "day_view")
					  $pg = "day_view";
					  elseif($_REQUEST['pg'] == "month_view")
					  $pg = "month_view";
					  
				$prev= "<a href=\"".makelink(array('mod' => 'event','pg'=>$pg),'year='.$y.'&month='.$m.'&day='.$pDay)."\"><img src='$PATH_CALENDAR/images/left_white_arrow.gif' border='0'><img src='$PATH_CALENDAR/images/left_white_arrow.gif' border='0'></a> ";
				}
				
				// Next month link
				$nextTS = strtotime("$year-$month-01 +1 month");
				$nMax = date('t', $nextTS);
				$nDay = ($day > $nMax) ? $nMax : $day;
				list($y, $m) = explode('-', date('Y-m', $nextTS));
				
				if($_REQUEST['pg'] == "week_view")
				{
				$date  = $year."-".$month."-".$day;
					$next_week = $this->next_week($date);
					$y		   = $next_week[0];
					$m		   = $next_week[1];
					$pDay	   = $next_week[2];
				$next = "<a href=\"".makelink(array('mod' => 'event','pg'=>'week_view'),'year='.$y.'&month='.$m.'&day='.$pDay).               "\"><img src='$PATH_CALENDAR/images/right_white_arrow.gif' border='0'><img src='$PATH_CALENDAR/images/right_white_arrow.gif' border='0'></a>";
				}
				else
				{
				  if($_REQUEST['pg'] == "day_view")
				  $pg = "day_view";
				  elseif($_REQUEST['pg'] == "month_view")
				  $pg = "month_view";
				  
				$next = "<a href=\"".makelink(array('mod' => 'event','pg'=>$pg),'year='.$y.'&month='.$m.'&day='.$pDay).               "\"><img src='$PATH_CALENDAR/images/right_white_arrow.gif' border='0'><img src='$PATH_CALENDAR/images/right_white_arrow.gif' border='0'></a>";
				}
				return array("next"=>$next,"prev"=>$prev);
		
		}
		
	function get_week_range($week, $year) {
	
		if ($week > 0) {
		   $first_monday = (8 - date("w", mktime(1,0,0,12,31,$year -1))) % 7;
		   if ($first_monday == 0) $first_monday = 7;
	
		   $fm_ts = mktime(1,0,0,1,$first_monday,$year);
	   
		   $monday = strtotime('+'.($week - 1).' week', $fm_ts);
		   $sunday = strtotime('+6 days', $monday);
	
		   $start = date("Y-m-d", $monday);
		   $end    = date("Y-m-d", $sunday);
		} else {
			$last_day = ($year-1)."-12-31";
			$week_ = get_week_number($last_day);
			list($start, $end) = get_week_range($week_, ($year-1));
		}

   		return array($start, $end);
	}
	/*
	Get the week list ontthe base of year
	*/
	function weekList(){
			
			if($this->selectDate()){
			
				$i = 1;
				
				$array_week	= array();
				
				$date		=   $this->selectDate();
				
				$week		=	date("W",strtotime($date));
				
				$date_range = array();
				$date_range[0] = $date;
			
				
				$sdate = $date_range[0];//start date
				
				$array_week[$i] = $date_range[0];
				
				$eventArr[] = $this->displayEvent('','',$i,$array_week[$i]);
				for($i=1;$i<=6;$i++){
				
					$array_week[$i+1] = date("Y-m-d",strtotime("+".$i. "day",strtotime($sdate)));
					$eventArr[] = $this->displayEvent('','',$i+1,$array_week[$i+1]);
					
				}
				//$array_week[7] = $date_range[1]; //end date
				
				return array($array_week,$eventArr);
			}
			
	}
	function next_date($date){
		
		if($date)
		{
			$next_date	= date("Y-m-d",strtotime("+1 day",strtotime($date)));
			$next_array = explode("-",$next_date);
			return $next_array;
		}
			
	}
	function prev_date($date){
		
		if($date)
		{
			$prev_date	= date("Y-m-d",strtotime("-1 day",strtotime($date)));
			$prev_array = explode("-",$prev_date);
			return $prev_array;
		}
			
	}
	function prev_week($date){
		
		//$week		=	date("W",strtotime($date));
		//$week_range =	$this->get_week_range($week, date("Y",strtotime($date)));
		
		if($date)
		{
			
			//$prev_week_date	= date("Y-m-d",strtotime("-1 week",strtotime($week_range[0])));
			$prev_week_date	= date("Y-m-d",strtotime("-1 week",strtotime($date)));
			
			
			$prev_week_array = explode("-",$prev_week_date);
			
			return $prev_week_array;
		}
	}
	function next_week($date){
		
		//$week		=	date("W",strtotime($date));
		//$week_range =	$this->get_week_range($week, date("Y",strtotime($date)));
		
		if($date)
		{
			$next_week_date	= date("Y-m-d",strtotime("+1 week",strtotime($date)));
			$next_week_array = explode("-",$next_week_date);
			return $next_week_array;
		}
	}
	function getcurrent_week($date){
	
			$week		=	date("W",strtotime($date));
			$year		=	date("Y",strtotime($date));
			
			$week_range_array =  $this->get_week_range($week, $year);
			$week_start_date  = explode("-",$week_range_array[0]);
			return $week_start_date;
	}
	/*----------------------------BIG CALENDAR---------------------------------------------------*/
	function drawCalendar_big($month, $year,$day) {
				
			  $navig = $this->navig($month, $year,$day);
			  // set variables we will need to help with layouts
			  $first       = mktime(0,0,0,$month,1,$year); // timestamp for first of the month
			  $offset      = date('w', $first); // what day of the week we start counting on
			  $daysInMonth = date('t', $first);
			  $monthName   = date('F', $first);
			  $weekDays    = array('Su', 'M', 'Tu', 'W', 'Th', 'F', 'Sa');
			  $eventDays   = $this->getEventDays($month, $year);
			  
			  // Start drawing calendar
			  $out  = "<table id=\"myCalendar\" border='1' class='forcolor' width='100%'>\n";
			  $out .= "<tr><td align='center' colspan=\"7\">\n";
			  $out .= "<table align='center' border='0' width='100%' cellspacing='0' cellpadding='0'>\n";
			  $out .= "<tr class='event_back_color'><td align='left'>{$navig['prev']}</td>\n";
			  $out .= "<td align='center'>$monthName $year</td>\n";
			  $out .= "<td align='right'>{$navig['next']}</td>\n";
			  $out .= "</tr></table></td></tr>\n";
			  $out .= "<tr><th colspan=\"7\"></th></tr>\n";
			  $out .= "<tr>\n";
			  foreach ($weekDays as $wd) $out .= "<td class=\"weekname\" align='center'>$wd</td>\n";
			  
			  $i = 0;
			  for ($d = (1 - $offset); $d <= $daysInMonth; $d++) {
				if ($i % 7 == 0) $out .= "<tr>\n"; // Start new row
				if ($d < 1) $out .= "<td class=\"tdwh\">&nbsp;</td>\n";
				else {
				  if (in_array($d, $eventDays)) {
				  
				   $row_event = $this->displayEvent("monthView",'','',$year."-".$month."-".$d);
					
					$out .= "<td class=\"tdwhevent\">
				                 <table width =\"100%\" border='0' cellspacing=\"0\" cellpadding=\"0\">
								 <tr><td class=\"tdwhevent\" align='left'>
								 <a href=\"".makelink(array('mod' => 'event','pg'=>'day_view'),'year='.$year.'&month='.$month.                                 '&day='.$d)."\" class=\"event\">$d</a></td>
								 <td class=\"tdwhevent\" align='right'>
								 <input name='btn'type='button' class='input' value='Add'></td></tr><tr><td style='height:100px' colspan='2'>".$row_event[0]."</td></tr></table></td>";
					$out .= "</td>\n";
				  } else $out .= "<td class=\"tdwh\">
				                 <table width =\"100%\" border='0' cellspacing=\"0\" cellpadding=\"0\">
								 <tr><td class=\"tdwh\" align='left'>
								 <a href=\"".makelink(array('mod' => 'event','pg'=>'day_view'),'year='.$year.'&month='.$month.                                 '&day='.$d)."\" class=\"nonevent\">$d</a></td>
								 <td class=\"tdwh\" align='right'>
								 <input name='btn'type='button' class='input' value='Add'></td></tr></table></td>";
				}
				++$i; // Increment position counter
				if ($i % 7 == 0) $out .= "</tr>\n"; // End row on the 7th day
			  }
			  
			  // Round out last row if we don't have a full week
			  if ($i % 7 != 0) {
				for ($j = 0; $j < (7 - ($i % 7)); $j++) {
				  $out .= "<td class=\"tdwh\">&nbsp;</td>\n";
				}
				$out .= "</tr>\n";
			  }
			  
			  $out .= "</table>\n";
				
			  return $out;
		}
		function draw_year_calendar($month, $year,$day){
		
				$out .= "<table width='100%'  border='1' cellspacing='5' cellpadding='1' class='yeartbale'>";
				$out .=  "<tr>";
			for($i=1;$i<=12;$i++)
			{
				$out .=  "<td valign='center' align='center' height='180'><table class='border'><tr><td>".$this->drawCalendar($i, $year,$day,'no')."</td></tr></table></td>";
				if($i==3 || $i==6 || $i==9)
				$out .= "<tr></tr>";
			}
				$out .=	 "</tr>";
				$out .=  "</table>";
				
				return $out;
		}
		function displayEvent($view='',$time = '',$index='',$date=''){
			
			 $sql  = "SELECT hour,minute FROM event_settings WHERE type = '0'";
			 $rs   =  $this->db->get_row($sql,ARRAY_A);
			
			 $minute = $rs["minute"];
			 $hour   = $rs["hour"];
			
			if($this->selectDate())
			{
				if($date)
				$date = $date;
				else
				$date = $this->selectDate();
				
				if($view == "dayView")
				{
					
					if($hour > 0)
						$time2 =date("H:i:s",strtotime("+ $hour hours",strtotime($time)));
					else
						$time2 =date("H:i:s",strtotime("+ $minute minutes",strtotime($time)));
					
					
					$qry = " AND ev.event_time >= '$time' AND ev.event_time < '$time2'";
					
				}
					
					$sql = "SELECT em.event_id,em.event_name,em.type_e,ev.event_date,ev.event_time FROM
					        event_master AS em INNER JOIN event_venue AS ev ON em.event_id = ev.event_id 
							INNER JOIN event_invite_accept ea ON em.event_id = ea.event_id
							WHERE ev.event_date = '$date' $qry AND ea.mem_id ={$_SESSION['memberid']}";
							
					$rs  = $this->db->get_results($sql,ARRAY_A);
					//print $sql."<br>";
					if(count($rs) > 0)
					{
										
						foreach ($rs as $row)
						{
							if($view =="monthView")
							{
							
							 if($rs["type_e"] == "EV")
							 $link  = makeLink(array("mod"=>event,"pg"=>event),"act=details&eventid={$row['event_id']}");
							 elseif($rs["type_e"] == "CL")
							 $link  = makeLink(array("mod"=>event,"pg"=>event),"act=mycalendar_det&eventid={$row['event_id']}");
							 
							 $title = date("h:i A",strtotime($row[event_time]))." - ".$row[event_name];
							 $event .=  "<div class='spamonth' style='vertical-align:left;'><a href='$link' class='spalink'>$title</a></div><hr size='1'>";

							}
							else
							{
						
							 if($row["type_e"] == "EV")
								 $link  = makeLink(array("mod"=>event,"pg"=>event),"act=details&eventid={$row['event_id']}");
							 elseif($row["type_e"] == "CL")
							 	$link  = makeLink(array("mod"=>event,"pg"=>event),"act=mycalendar_det&eventid={$row['event_id']}");

							 $title = date("h:i A",strtotime($row[event_time]))." - ".$row[event_name];
							 $event .=  "<div class='spa' style='vertical-align:middle'><a href='$link' class='spalink'>$title</a></div><br>";
							}
						}
					} 
					if($view =="monthView")
					$arrEvent[] = $event;
					else
					$arrEvent[$index] = $event;
			}
			return $arrEvent;
		}
		function timeArray($time_gap_hour=1,$time_gap_minutes=0)
		{
			 $sql  = "SELECT start_time,end_time,hour,minute,val FROM event_settings WHERE type = '0'";
			 $rs   =  $this->db->get_row($sql,ARRAY_A);
			 $val  =  $rs["val"];
			 $time_gap_hour    = $rs["hour"];
			 $time_gap_minutes = $rs["minute"];
			 
			 $start_time = $rs["start_time"];
			 $end_time	 = $rs["end_time"];
			
			for($i=0;$i<$val;$i++)
			{
				if($i !=0)
				{
					$time[$i]   = date("h:i A",strtotime("+".$time_gap_hour. "hours". "$time_gap_minutes minutes",strtotime($start_time)));
					$start_time = $time[$i];
				}
				else
				{
					$time[$i]   = date("h:i A",strtotime("+ 0". "hours". "$time_gap_minutes minutes",strtotime($start_time)));
					$start_time = $time[$i];
				}
				
				$eventArr[] = $this->displayEvent('dayView',date("H:i:s",strtotime($time[$i])),$i);
				
				if($time[$i] ==  date("h:i A",strtotime($end_time)))
				break;
			}
			
			return array($time,$eventArr);

		}
		function selectDate()
		{
			return $this->year."-".$this->month."-".$this->day;
		}
}
?>