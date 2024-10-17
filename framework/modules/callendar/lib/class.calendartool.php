<?php
/**
* Class Callender Tool
* Author   : Aneesh Aravindan
* Created  : 01/Oct/2007
* Modified : 31/Oct/2007 By Aneesh Aravindan
*/  
include_once "class.calendarevents.php";

class CalendarTool EXTENDS FrameWork{
	var $CALELEM;   # Element ID
	var $YEAR;		# Year Format :: 1999
	var $MONTH;		# Month Format :: 02
	var $DAY;		# Date Format :: 09
	var $MINYEAR;
	var $MAXYEAR;

			
	function CalendarTool($YEAR=0,$MONTH=0,$DAY=0,$CALELEM='myCalendar',$WEEKDAYS=array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'),$NAVIG='',$MINYEAR='2001-01-01',$MAXYEAR='2030-12-31')	{
		$this->FrameWork();
		$this->YEAR  		=	 $YEAR;
		$this->MONTH 		=	 $MONTH;
		$this->DAY  		= 	 $DAY;
		$this->CALELEM 		= 	 $CALELEM;
		$this->NAVIG   		= 	 $NAVIG;
		$this->WEEKDAYS     =    $WEEKDAYS;
		$this->SRC_PATH		= 	 SITE_URL;
		
		$this->MINYEAR 		= 	 $MINYEAR;
		$this->MAXYEAR 		= 	 $MAXYEAR;
	}
		
	function DrawCalendarLayer($YEAR, $MONTH,$DAY,$CALELEM='myCalendar',$CASEFETCH=array(),$ELEMOLD='',$NAVIG='') {
	/*
		$CASEFETCH = Array (FCase=>Function Case,minYear=>Callendar Min Year,maxYear=>Callendar Max Year,TimeShow => Show Time
	*/
			  if($NAVIG != 'NO')
			  $NAVIG = $this->DateNavigation($MONTH, $YEAR,$DAY,$CALELEM,$CASEFETCH);
			  
			  # Set variables we will need to help with layouts
			  $FIRST       = mktime(0,0,0,$MONTH,1,$YEAR);	 	# timestamp for first of the month
			  $OFFSET      = date('w', $FIRST); 				# what day of the week we start counting on
			  $DAYSINMONTH = date('t', $FIRST);
			  $MONTHNAME   = date('F', $FIRST);
		 
			  $objEVENTS	=	new CalendarEvents;
			  
			  if(trim($CASEFETCH['CUSTOM_FUNCTION']) != "")
			  $EVENTDAYS = $objEVENTS->GetEventYear($MONTH, $YEAR,$CASEFETCH['FCase'],$CASEFETCH['memberid'],$CASEFETCH['propid']);
			  
			 /* Display Format */
			 $dispaly_format = $this->config["calendar_date_format"];

			  # Start drawing calendar,$DISPLAY='none'
			  if( trim($CASEFETCH['DISPLAY']) )
			  $DISPLAY	= $CASEFETCH['DISPLAY'];
			  else 
			  $DISPLAY	= 'none';
			  
			  if($ELEMOLD != 'YES') { 
			  $out  = "<div id=\"$CALELEM\" style=\"width:200px;display:$DISPLAY;z-index:5000;\">\n";
			  }
			  
			  /*
			  if($ELEMOLD == 'YES') { 
			  $out  = "<div id=$CALELEM style=\"width:200px;display:block;z-index:100;\">\n";
			  } else {
			  $out  = "<div id=$CALELEM style=\"width:200px;display:none;z-index:100;\">\n";
			  }
			  */
			  $out  .= "<div  class='calforcolor' style=\"display:block;margin:2px\" width='100%'>\n";
			  	$out .="<div align=\"center\" class='caltopcolor' style=\"height:17px\">\n";
					$out .= "<div style=\"width:8%;float:left;text-align:left;margin-top:3px;\">{$NAVIG['prevYear']}</div>\n";
					$out .= "<div style=\"width:8%;float:left;text-align:left;margin-top:3px;\">{$NAVIG['prev']}</div>\n";
															
					$out .= "<div style=\"width:57%;float:left;text-align:center;\" title=$MONTHNAME-$YEAR>$MONTHNAME $YEAR</div>\n";
					
					$out .= "<div style=\"width:8%;float:left;text-align:left;margin-top:3px;\">{$NAVIG['next']}</div>\n";
					$out .= "<div style=\"width:8%;float:left;text-align:left;margin-top:3px;\">{$NAVIG['nextYear']}</div>\n";
					
					$out .= "<div style=\"width:10%;float:left;text-align:right\" title='Close'>";
					
					if($CASEFETCH['CLOSE_BUTTON'] != 'no')
					$out .= "<a href=\"javascript:void(0);\" style=\"cursor:pointer;color:#ffffff\" onClick=\"javascript:showElement( 'SELECT' );showElement( 'APPLET' );document.getElementById('$CALELEM').style.display='none'; \"><img border=0 align=right src=$this->SRC_PATH/includes/callendar/icnCClose.gif></a>";
					
					$out .="</div>\n";
					
			    $out .= "</div>\n";	
			  
			  	$out .="<div style=\"clear:both\"></div><div align=\"center\" style=\"height:15px;background-color:#DDDDDD;vertical-align:middle;\" >\n";
				    foreach ($this->WEEKDAYS as $wd) $out .=  "<div style=\"width:14%;float:left;height:15px\" class=\"calweekname\">$wd</div>\n";
			    $out .= "</div><div style=\"clear:both\"></div>\n";	
				
				

			    $i = 0;
			    for ($d = (1 - $OFFSET); $d <= $DAYSINMONTH; $d++) {
				if ($i % 7 == 0) $out .= "<div align=\"center\">\n"; // Start new row onmouseover=\"return overlib('This',RIGHT,WIDTH,150,BGCOLOR,'#afafaf',FGCOLOR,'#fbfbfb',TEXTCOLOR,'#F07113');\" onmouseout=\"return nd()\"
				if ($d < 1) $out .= "<div class=\"calnonMonthDay\" style=\"width:14%;float:left\">&nbsp;</div>\n";
				else {
					
					if(trim($CASEFETCH['CUSTOM_FUNCTION']) != "") // if there is any customfunctions
					{
					 	list($exist,$popUp,$clickCap,$onClickUrl) = $this->CheckEventWithNos($d,$EVENTDAYS);
				
					   if ($exist) {
							
								$out .= "<div class=\"calmonthDay\" style=\"width:14%;float:left\">\n";
								
								if ( trim($popUp) )
								$popUp		=	"<div>".$MONTHNAME . '-' . $d . ',' . $YEAR . "</div><div>" . $popUp. "</div>";
								
								$Descript	=	"<div>".$MONTHNAME . '-' . $d . ',' . $YEAR . "</div><div>" . $onClickUrl. "</div>";
								$CAPICOMSG	=	$CAPICO . $clickCap;
	
	
									if($onClickUrl && trim($popUp)) {
									$out .= "<a href=\"javascript:void(0)\" class=\"calevent\" style=\"cursor:pointer\" onclick=\"".$CASEFETCH['CUSTOM_FUNCTION']."($YEAR,$MONTH,$d);\" onmouseover=\"return overlib('$Descript',RIGHT,WIDTH,150,HEIGHT,20,BGCOLOR,'#afafaf',FGCOLOR,'#fbfbfb',TEXTCOLOR,'#F07113',STICKY,CAPTION,
								   '$CAPICOMSG',OFFSETX, 0, OFFSETY, 0, DELAY, 100,NOCLOSE);\" onmouseout=\"return nd();\">$d</a>\n";
								   							
									} elseif ($onClickUrl) {
															$out .= "<a href=\"javascript:void(0)\" class=\"calevent\" style=\"cursor:pointer\" onclick=\"".$CASEFETCH['CUSTOM_FUNCTION']."($YEAR,$MONTH,$d);\" onmouseover=\"return overlib('$Descript',RIGHT,WIDTH,150,HEIGHT,20,BGCOLOR,'#afafaf',FGCOLOR,'#fbfbfb',TEXTCOLOR,'#F07113',STICKY,CAPTION,
								   '$CAPICOMSG',OFFSETX, 0, OFFSETY, 0, DELAY, 100,NOCLOSE);\" onmouseout=\"return nd();\">$d</a>\n";
									} elseif (trim($popUp)) {
									$out .= "<a href=\"javascript:void(0)\" onmouseover=\"return overlib('$popUp',RIGHT,WIDTH,150,HEIGHT,20,BGCOLOR,'#afafaf',FGCOLOR,'#fbfbfb',TEXTCOLOR,'#F07113');\" class=\"calevent\" style=\"cursor:pointer\" onmouseout=\"return nd();\" onclick=\"dispDayView($YEAR,$MONTH,$d);\">$d</a>\n";								
		   							} else {
									$out .= "<a href=\"javascript:void(0);\" class=\"calevent\">$d</a>\n";
									}
								
								$out .= "</div>\n";
						 	}else{
						 		$out .= "<div style=\"width:14%;float:left\"><a onClick=\"javascript:".$CASEFETCH['CUSTOM_FUNCTION']."($YEAR,$MONTH,$d);\" href=\"javascript:void(0);\" class=\"calnonevent\">$d</a></div>\n";
						 	}
					}
					else
					{
					
						  if (in_array($d,$EVENTDAYS)) {
							$out .= "<div class=\"calmonthDay\" style=\"width:14%;float:left\" title='This Date is Reserved'>\n";
							$out .= "<a href=\"javascript:void(0);\" class=\"calevent\">$d</a>\n";
							$out .= "</div>\n";
						  }elseif( $d == date('d') && $MONTH ==  date('m') && $YEAR ==  date('Y') ) {
							$out .= "<div class=\"calmonthDay\" style=\"width:14%;float:left;text-align:center;\" title='Today'>\n";
							
							if ( $CASEFETCH['TimeShow'] == 'YES' ) {	
								 $out .= "<a onClick=\"javascript:showElement( 'SELECT' );showElement( 'APPLET' );showTimMin(document.getElementById('txt$CALELEM'),'$YEAR-$MONTH-$d','CalTimHr_$CALELEM','CalTimMin_$CALELEM');document.getElementById('$CALELEM').style.display='none';\" href=\"javascript:void(0);\" class=\"caltodate\">$d</a>\n";
			
							}	else	{
								
							
								 $out .= "<a onClick=\"javascript:showElement( 'SELECT' );showElement( 'APPLET' );document.getElementById('txt$CALELEM').value=formatDate('$MONTH/$d/$YEAR','$dispaly_format','yes');document.getElementById('$CALELEM').style.display='none';\" href=\"javascript:void(0);\" class=\"caltodate\">$d</a>\n";
							}
							$out .= "</div>\n";
						  } 
						  else {
							 if ( $CASEFETCH['TimeShow'] == 'YES' ) {
							 	$out .= "<div style=\"width:14%;float:left\"><a onClick=\"javascript:showElement( 'SELECT' );showElement( 'APPLET' );showTimMin(document.getElementById('txt$CALELEM'),'$YEAR-$MONTH-$d','CalTimHr_$CALELEM','CalTimMin_$CALELEM');document.getElementById('$CALELEM').style.display='none';\" href=\"javascript:void(0);\" class=\"calnonevent\">$d</a></div>\n";
							 }	else	{	
							 	
							 	$type = "";
							 	
								if(!$this->checkInDateGreater(date("Y-m-d"),$YEAR."-".$MONTH."-".$d))
								$type = "propertyBlock";
							 	
								if($type == "propertyBlock")
								$out .= "<div style=\"width:14%;float:left;\" class=\"block-day\">$d</div>\n";
								else
							 	$out .= "<div style=\"width:14%;float:left\"><a onClick=\"javascript:showElement( 'SELECT' );showElement( 'APPLET' );document.getElementById('txt$CALELEM').value=formatDate('$MONTH/$d/$YEAR','$dispaly_format','yes');document.getElementById('$CALELEM').style.display='none';\" href=\"javascript:void(0);\" class=\"calnonevent\">$d</a></div>\n";
							 }
						  }	 
					}// CUSTOM_FUNCTION close tag here
				}
				++$i; // Increment position counter
				if ($i % 7 == 0) $out .= "</div><div style=\"clear:both\"></div>\n"; // End row on the 7th dayformatDate(CtrlSDate,"MM/dd/yyyy");
			  }


			if ( $CASEFETCH['TimeShow'] == 'YES' )	{
				 $out .=  $this->TimeLayer($CALELEM);
			}

			$out .= "</div><div style=\"clear:both\"></div>\n";



			  $out  .= "<input name=\"hidCASEFETCH_$CALELEM\" id=\"hidCASEFETCH_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['FCase']}\">\n";
			  $out  .= "<input name=\"minYear_$CALELEM\" id=\"minYear_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['minYear']}\">\n";
			  $out  .= "<input name=\"maxYear_$CALELEM\" id=\"maxYear_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['maxYear']}\">\n";
			  $out  .= "<input name=\"maxYear_$CALELEM\" id=\"maxYear_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['maxYear']}\">\n";
			  $out  .= "<input name=\"TimeShow_$CALELEM\" id=\"TimeShow_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['TimeShow']}\">\n";
			  $out  .= "<input name=\"CUSTOM_FUNCTION_$CALELEM\" id=\"CUSTOM_FUNCTION_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['CUSTOM_FUNCTION']}\">\n";

			  $out .= "</div>\n";	
			  
			  if($ELEMOLD != 'YES') {
			  $out .= "</div>\n";	
			  }
			  
			  if($CASEFETCH['CUSTOM_FUNCTION'] == "dispWeekView")
			  return array($out,$EVENTDAYS);
			  else
			  return $out;
				
		}
		

	function TimeLayer ($CALELEM)	{

		# Hours & Minutes
		$hourVal	=	0;
		$mintVal	=	0;

		$TimeStr	=	"<br /><div style=\"clear:both\"></div><div align=\"center\" class='caltopcolor' style=\"height:15px\">\n";
		$TimeStr   .=	"Time:<span> <input onClick=\"javascript:setTimMin('Hrs','CalTimHr_$CALELEM','CalTimMin_$CALELEM');\" value=$hourVal name=\"CalTimHr_$CALELEM\" id=\"CalTimHr_$CALELEM\" type=\"text\" size=\"1\" maxlength=\"2\" style=\"border:1px solid #CCCCCC;height:10px;width:25px; font-family:Arial, Helvetica, sans-serif;font-size:9px; font-weight:normal;text-align:center;cursor:default\"></span>";
		$TimeStr   .=	":<span><input onClick=\"javascript:setTimMin('Min','CalTimMin_$CALELEM','CalTimHr_$CALELEM');\" value=$mintVal name=\"CalTimMin_$CALELEM\" id=\"CalTimMin_$CALELEM\" type=\"text\" size=\"1\" maxlength=\"2\" style=\"border:1px solid #CCCCCC;height:10px;width:25px; font-family:Arial, Helvetica, sans-serif;font-size:9px; font-weight:normal;text-align:center;cursor:default\"></span>";
		$TimeStr   .=	"</div><div style=\"clear:both\"></div>\n";	
		return $TimeStr;
	}

	function CheckEventWithNos ( $CurrDate = "", $EVENTDAYSARR = array() )	{
		foreach ( $EVENTDAYSARR as $evdays ) {
			if ( $CurrDate == $evdays['date'] )
			return array($evdays['unavailable'],$evdays['tooltip'],$evdays['clickCap'],$evdays['onClickFun'],$evdays["eventType"]);
		}
	}


	function DrawCalendarYear($YEAR, $MONTH, $DAY,$CALELEM='myCalendar',$CASEFETCH=array()) {
	/*  2008-10-24 date('Y');   date('m');   date('d');
		$CASEFETCH = Array (FCase=>Function Case,minDate=>Callendar Min Date,maxDate=>Callendar Max Date,
	*/
	
		   $this->WEEKDAYS =array('Su', 'Mo', 'Tu', 'Wd', 'Th', 'Fr', 'Sa');
		   $objEVENTS	=	new CalendarEvents();
		   
		   $CAPICO		=	'';
		   $CAPICO		=	"<img align=left src=$this->SRC_PATH/includes/callendar/icnInfo2.gif>";
		   /*   Begin Callendar Coulumn Setting :: $CASEFETCH['ColSet']   */	 
		   $Cols = array(1,2,3,4,6); 	 
		   if (  in_array( $CASEFETCH['ColSet'],$Cols ) ) {
		   	$ContWIDTH	=	170 * $CASEFETCH['ColSet'];
			$SplitWIDTH	=	100 / $CASEFETCH['ColSet'];;
		   } else {
		    $ContWIDTH	=	170 * 4;
			$SplitWIDTH	=	100 / 4;
			$CASEFETCH['ColSet'] = 4;
		   }	   
		   /*   End Callendar Coulumn Setting :: $CASEFETCH['ColSet']   */	
		   
		   
		   /* Set Min Max Date */
		   if (trim($CASEFETCH['minDate']))
		   $MIN_DATE_ARR	=	explode( "-",$CASEFETCH['minDate'] );
		   else
		   $MIN_DATE_ARR	=	explode( "-",$this->MINYEAR ); 
		   
		   if (trim($CASEFETCH['maxDate']))
		   $MAX_DATE_ARR	=	explode( "-",$CASEFETCH['maxDate'] ); 
		   else
		   $MAX_DATE_ARR	=	explode( "-",$this->MAXYEAR ); 
		   /* Set Min Max Date */
			

		    if ( ! $CASEFETCH['FromAjax'] )
			$out  = "<div id=\"$CALELEM\" align=\"center\" style=\"z-index:5000;width:{$ContWIDTH}px;border:1px solid #afafaf;background-color:#FBFBFB;padding:5px 5px 5px 5px\">\n"; # Begin Container
			
			# Start drawing calendar
			for ( $iM= 0; $iM<12; $iM++ ):  
				
				$date = $YEAR . '-' . $MONTH . '-' . $DAY;
				$next_month	= date("Y-m-d",strtotime("+$iM month",strtotime($date)));
				$next_month_array = explode("-",$next_month);
				# Set variables we will need to help with layouts
				$YEARNAME	 = $next_month_array[0];
				$FIRST       = mktime(0,0,0,$next_month_array[1],1,$next_month_array[0]);	 	# timestamp for first of the month
				$OFFSET      = date('w', $FIRST); 				# what day of the week we start counting on
				$DAYSINMONTH = date('t', $FIRST);
				$MONTHNAME   = date('F', $FIRST);
				$MONTHSHORT   = date('M', $FIRST);
				
				$EVENTDAYSARR = $objEVENTS->GetEventYear($next_month_array[1], $next_month_array[0],$CASEFETCH['FCase'],$CASEFETCH['memberid'],$CASEFETCH['propid'],$CASEFETCH['flyer_id']);
				
				
				/***************************************************************************************************************/
				/* OWNER VIEW OF THE CALENDAR IN DETAILS PAGE  START HERE*/
				$m = $iM+1;
				/* RETURN BLOCKED AND FIXED RATE COLOR */
				$days = $objEVENTS->setBackGroundColor($CASEFETCH['propid'],$m,$YEAR);
				/* OWNER VIEW OF THE CALENDAR IN DETAILS PAGE START HERE */
				/***************************************************************************************************************/
				
					
				$out  .= "<div style=\"width:{$SplitWIDTH}%;float:left\">"; 	#Begin Split Div
				$out  .= "<div id=\"$CALELEM$iM\">\n"; # Begin Parent Div
				$out  .= "<div  class='calforcolor' style=\"width:140px;height:130px;margin:14px;\">\n";  # Begin Parent sub Div

				# Begin Month Year Names
				$out .="<div align=\"center\" class='caltopcolor' style=\"height:17px\">\n";
					$out .= "<div style=\"width:100%;float:left;text-align:center;\" title=$MONTHNAME-$YEARNAME>$MONTHNAME $YEARNAME</div>\n";
				$out .= "</div>\n";	
				# End Month Year Names

				# Begin Week Names
				$out .="<div align=\"center\" style=\"height:15px;background-color:#DDDDDD;vertical-align:middle;\" >\n";
				    foreach ($this->WEEKDAYS as $wd) $out .=  "<div style=\"width:14%;float:left;height:15px\" class=\"calweekname\">$wd</div>\n";
			    $out .= "</div>\n";	
				# End Week Names

				
				$i = 0;
			    for ($d = (1 - $OFFSET); $d <= $DAYSINMONTH; $d++) 
				{
								/* RETURN BLOCKED AND FIXED RATE COLOR */
								$val = "";				 			
								list($val,$id) = $objEVENTS->getEventBackColor($days,$d);
								$val = trim($val);
						 		$backlColor = "";
						 		if(trim($val) != ""){	//array_search($d,$days);
						 			$backlColor = "background-color:{$val};";
						 		}
					
					
					
					
					if ($i % 7 == 0) $out .= "<div align=\"center\">\n";
					if ($d < 1) $out .= "<div class=\"calnonMonthDay\" style=\"width:14%;float:left\">&nbsp;</div>\n";
					else {
					    list($exist,$popUp,$clickCap,$onClickUrl,$eventType) = $this->CheckEventWithNos($d,$EVENTDAYSARR);
						if ( $exist ) {
							
							$out .= "<div class=\"calmonthDay\" style=\"width:14%;float:left;$backlColor\">\n";
							
							
							if ( trim($popUp) )
							
							$popUp		=	"<div>".$MONTHSHORT . '-' . $d . ',' . $YEARNAME . "</div><div>" . $popUp. "</div>";
							$Descript	=	"<div>".$MONTHSHORT . '-' . $d . ',' . $YEARNAME . "</div><div>" . $onClickUrl. "</div>";
							$CAPICOMSG	=	$CAPICO . $clickCap;
								
							if($eventType == "propertyBook"){
								

								if($onClickUrl && trim($popUp)) {
								$out .= "<a href=\"javascript:void(0)\" class=\"calevent\" style=\"cursor:pointer\" onclick=\"return overlib('$Descript',RIGHT,WIDTH,150,HEIGHT,20,BGCOLOR,'#afafaf',FGCOLOR,'#fbfbfb',TEXTCOLOR,'#F07113',STICKY,CAPTION,
	                                     '$CAPICOMSG',OFFSETX, 0, OFFSETY, 0, DELAY, 100,NOCLOSE);\" onmouseover=\"return overlib('$popUp',RIGHT,WIDTH,150,HEIGHT,20,BGCOLOR,'#afafaf',FGCOLOR,'#fbfbfb',TEXTCOLOR,'#F07113');\" onmouseout=\"return nd();\">$d</a>\n";
	   							} elseif ($onClickUrl) {
								$out .= "<a href=\"javascript:void(0)\" class=\"calevent\" style=\"cursor:pointer\" onclick=\"return overlib('$Descript',RIGHT,WIDTH,150,HEIGHT,20,BGCOLOR,'#afafaf',FGCOLOR,'#fbfbfb',TEXTCOLOR,'#F07113',STICKY,CAPTION,
	                                     '$CAPICOMSG',OFFSETX, 0, OFFSETY, 0, DELAY, 100,NOCLOSE);\" onmouseout=\"return nd();\">$d</a>\n";
								} elseif (trim($popUp)) {
								$out .= "<a href=\"javascript:void(0)\" onmouseover=\"return overlib('$popUp',RIGHT,WIDTH,150,HEIGHT,20,BGCOLOR,'#afafaf',FGCOLOR,'#fbfbfb',TEXTCOLOR,'#F07113');\" class=\"calevent\" style=\"cursor:pointer\" onmouseout=\"return nd();\">$d</a>\n";								
	   							} else {
								$out .= "<a href=\"javascript:void(0);\" class=\"calevent\">$d</a>\n";
								}
							}else{
								$out .= "<a href=\"javascript:void(0);\" class=\"calevent\">$d</a>\n";
							}
							$out .= "</div>\n";
						} else {
							
							$currDate   =    $next_month_array[0] . "-" . $next_month_array[1] . "-" .$d;
							/*
							if ( trim($popUp) )
							$popUp		=	"<div>".$MONTHSHORT . '-' . $d . ',' . $YEARNAME . "</div><div>" . $popUp. "</div>";
							
							$Descript	=	"<div>".$MONTHSHORT . '-' . $d . ',' . $YEARNAME . "</div><div>" . $onClickUrl. "</div>";
							$CAPICOMSG	=	$CAPICO . $clickCap;
							
							if($onClickUrl && trim($popUp)) {
						 	$out .= "<div  style=\"width:14%;float:left\"><a  onclick=\"return overlib('$Descript',RIGHT,WIDTH,150,HEIGHT,20,BGCOLOR,'#afafaf',FGCOLOR,'#fbfbfb',TEXTCOLOR,'#F07113',STICKY,CAPTION,
   '{$CAPICOMSG}',OFFSETX, 0, OFFSETY, 0, DELAY, 100,NOCLOSE);\" onmouseover=\"return overlib('$popUp',RIGHT,WIDTH,150,HEIGHT,20,BGCOLOR,'#afafaf',FGCOLOR,'#fbfbfb',TEXTCOLOR,'#F07113');\" href=\"javascript:void(0);\" class=\"calnonevent\" onmouseout=\"return nd();\">$d</a></div>\n";
   							} elseif ($onClickUrl) {
						 	$out .= "<div  style=\"width:14%;float:left\"><a  onclick=\"return overlib('$Descript',RIGHT,WIDTH,150,HEIGHT,20,BGCOLOR,'#afafaf',FGCOLOR,'#fbfbfb',TEXTCOLOR,'#F07113',STICKY,CAPTION,
   '{$CAPICOMSG}',OFFSETX, 0, OFFSETY, 0, DELAY, 100,NOCLOSE);\" href=\"javascript:void(0);\" class=\"calnonevent\" onmouseout=\"return nd();\">$d</a></div>\n";
							} elseif (trim($popUp)) {
						 	$out .= "<div  style=\"width:14%;float:left\"><a onmouseover=\"return overlib('$popUp',RIGHT,WIDTH,150,HEIGHT,20,BGCOLOR,'#afafaf',FGCOLOR,'#fbfbfb',TEXTCOLOR,'#F07113');\" href=\"javascript:void(0);\" class=\"calnonevent\" onmouseout=\"return nd();\">$d</a></div>\n";
							} else {
						 	$out .= "<div  style=\"width:14%;float:left\"><a href=\"javascript:void(0);\" class=\"calnonevent\">$d</a></div>\n";
							}*/
							$out .= "<div  style=\"width:14%;float:left;$backlColor\"><a href=\"javascript:void(0);\" class=\"calnonevent\">$d</a></div>\n";
							
					    }	
						
						/*
						if( $d == date('d') && $next_month_array[1] ==  date('m') && $next_month_array[0] ==  date('Y') ) {
							$out .= "<div class=\"calmonthDay\" style=\"width:14%;float:left\" title=\"Today:$MONTHSHORT-$d\">\n";
							$out .= "<a onClick=\"\" href=\"javascript:void(0)\" class=\"caltodate\">$d</a>\n";
							$out .= "</div>\n";
						}*/
						
											
					}
				if ($i % 7 == 0) $out .= "</div>\n"; // End row on the 7th day
				++$i; // Increment position counter
				}


				$out .= "</div>\n";	 #End Parent sub Div
			$out .= "</div>\n";	#End Parent Div
			$out .= "</div>\n";	#End Split Div
			
			if (($iM+1) % $CASEFETCH['ColSet'] == 0) 
			$out  .= "<div style=\"clear:both\"></div>\n";
			
			endfor;
			  
			
			/*
			Next Callendar Starting Dates
			*/  
			$date = $YEAR . '-' . $MONTH . '-' . $DAY;
			
			$next_month	= date("Y-m-d",strtotime("+12 month",strtotime($date)));
			$next_month_array = explode("-",$next_month);
			$prev_month	= date("Y-m-d",strtotime("-12 month",strtotime($date)));
			$prev_month_array = explode("-",$prev_month);
			
			$out  .= "<input name=\"hidnext_$CALELEM\" id=\"hidnext_$CALELEM\" type=\"hidden\" value=\"{$next_month}\" readonly>\n";
			$out  .= "<input name=\"hidprev_$CALELEM\" id=\"hidprev_$CALELEM\" type=\"hidden\" value=\"{$prev_month}\" readonly>\n";
		    $out  .= "<input name=\"hidCASEFETCH_$CALELEM\" id=\"hidCASEFETCH_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['FCase']}\" readonly>\n";
			$out  .= "<input name=\"ColSet_$CALELEM\" id=\"ColSet_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['ColSet']}\" readonly>\n";
		    $out  .= "<input name=\"minDate_$CALELEM\" id=\"minDate_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['minDate']}\" readonly>\n";
		    $out  .= "<input name=\"maxDate_$CALELEM\" id=\"maxDate_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['maxDate']}\" readonly>\n";

			$out  .= "<div style=\"clear:both\"></div>\n";
			
			$out  .=  "<div  class=\"calPagi\" align=\"center\" style=\"padding-right:5px;\">\n";
			
				$date 				= $YEAR . '-' . $MONTH . '-' . $DAY;
				$start_month		= date("Y-m-d",strtotime("+0 month",strtotime($date)));
				$end_month			= date("Y-m-d",strtotime("+11 month",strtotime($date)));
				$start_month_array  = explode("-",$start_month);
				$end_month_array  	= explode("-",$end_month);
				# Set variables we will need to help with layouts
				$STARTYEAR	 		= $start_month_array[0];
				$ENDYEAR	 		= $end_month_array[0];
				$FIRST      		= mktime(0,0,0,$start_month_array[1],1,$start_month_array[0]);	 	# timestamp for first of the month
				$STARTMONTH   		= date('M', $FIRST);
				$FIRST      		= mktime(0,0,0,$end_month_array[1],1,$end_month_array[0]);	 	# timestamp for first of the month
				$ENDMONTH   		= date('M', $FIRST);
				$ENDDAYSINMONTH 	= date('t', $FIRST);
				

			
			    $out  .=  "<div align=\"left\" style=\"width:50%;float:left;cursor:default\">\n";
			    $out  .=  "<span title=\"1-$STARTMONTH-$STARTYEAR/$ENDDAYSINMONTH-$ENDMONTH-$ENDYEAR\">&nbsp;1-$STARTMONTH-$STARTYEAR/$ENDDAYSINMONTH-$ENDMONTH-$ENDYEAR</span>\n";
			    $out  .= "</div>\n";
				
				$out  .=  "<div align=\"right\" style=\"width:49%;float:right;\"  >\n";
				if( $MIN_DATE_ARR[0] <= $prev_month_array[0] ){
					if(trim($CASEFETCH["CUSTOM_FUNCTION"])!=""){
				
				$out  .=  "<span onclick=\"javascript:".$CASEFETCH['CUSTOM_FUNCTION']."('p');\" style=\"cursor:pointer;\">Previous</span>\n";	
						
					}else{
				$out  .=  "<span onclick=\"javascript:MovetoCallendarYear('$CALELEM','p','$this->SRC_PATH')\" style=\"cursor:pointer;\">Previous</span>\n";
					}
				
				
				}
				else{
				$out  .=  "<span>Previous</span>\n";
				}
	
				$out  .=  "<span>l</span>\n";
				
				if( $MAX_DATE_ARR[0] >= $next_month_array[0] ){
					

					if(trim($CASEFETCH["CUSTOM_FUNCTION"])!=""){
						
				$out  .=  "<span onclick=\"javascript:".$CASEFETCH['CUSTOM_FUNCTION']."('n');\" style=\"cursor:pointer;\">Next</span>\n";	
				
					}else{
					
				$out  .=  "<span onclick=\"javascript:MovetoCallendarYear('$CALELEM','n','$this->SRC_PATH')\" style=\"cursor:pointer;\">Next</span></div>\n";
					}
				
				}
				else{
				$out  .=  "<span>Next</span>\n";
				}
				$out  .= "</div><div style=\"clear:both\">\n";
				
			$out  .= "</div>\n";
			
			if ( ! $CASEFETCH['FromAjax'] )
			$out .= "</div>\n";	#End Container
			
			
			
			/*
			Next Callendar Starting Dates
			*/  


			  
			return $out;
				
		}
		
		function DateNavigation($MONTH, $YEAR,$DAY, $Element='myCalendar', $ARGUMENTS=array()) {
			
			
				
				if ( !$ARGUMENTS['maxYear'] || trim($ARGUMENTS['maxYear']) =="" ) {
					$maxY = explode( "-",$this->MAXYEAR );
				} else {	
					$maxY = explode( "-",$ARGUMENTS['maxYear'] );
				}
						
				if ( !$ARGUMENTS['minYear'] || trim($ARGUMENTS['minYear']) =="" )	{
					$minY = explode("-",$this->MINYEAR);
				} else {	
				    $minY = explode("-",$ARGUMENTS['minYear']);
				}
				
				# Previous month link
				$prevTS = strtotime("$YEAR-$MONTH-01 -1 month"); # timestamp of the first of last month
				$pMax = date('t', $prevTS);
				$pDay = ($DAY > $pMax) ? $pMax : $DAY;
				list($y, $m) = explode('-', date('Y-m', $prevTS));		
				
				# Previous year link
				$prevTS_Y = strtotime("$YEAR-$MONTH-01 -1 year"); # timestamp of the first of last year
				$pMax_Y = date('t', $prevTS_Y);
				$pDay_Y = ($DAY > $pMax_Y) ? $pMax_Y : $DAY;
				list($y_Y, $m_Y) = explode('-', date('Y-m', $prevTS_Y));
				
				
				
				if ( $minY[0] <= $y ) {
					
					if(trim($ARGUMENTS['CUSTOM_FUNCTION']) != ""){
					$prev= "<a title='Previous Month' onClick=\"javascript:MovetoCallendar('$Element','$y','$m','$pDay','$this->SRC_PATH');".$ARGUMENTS['CUSTOM_FUNCTION']."('$y','$m','$pDay');\" href=\"javascript:void(0);\"><img align='absbottom' src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'></a> ";
					$prevYear= "<a title='Previous Year' onClick=\"javascript:MovetoCallendar('$Element','$y_Y','$m_Y','$pDay_Y','$this->SRC_PATH');".$ARGUMENTS['CUSTOM_FUNCTION']."('$y_Y','$m_Y','$pDay_Y');\" href=\"javascript:void(0);\"><img align='absbottom' src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'><img align='absbottom' src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'></a> ";
					}else{
					$prev= "<a title='Previous Month' onClick=\"javascript:MovetoCallendar('$Element','$y','$m','$pDay','$this->SRC_PATH')\" href=\"javascript:void(0);\"><img align='absbottom' src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'></a> ";
					$prevYear= "<a title='Previous Year' onClick=\"javascript:MovetoCallendar('$Element','$y_Y','$m_Y','$pDay_Y','$this->SRC_PATH')\" href=\"javascript:void(0);\"><img align='absbottom' src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'><img align='absbottom' src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'></a> ";
					}
				}else {
					$prev= "<a href=\"javascript:void(0);\"><img align='absbottom' src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'><img align='absbottom' src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'></a> ";
					$prevYear= "<a href=\"javascript:void(0);\"><img align='absbottom' src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'><img align='absbottom' src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'></a> ";
				}
				
				
				
				# Next month link
				$nextTS = strtotime("$YEAR-$MONTH-01 +1 month");
				$nMax = date('t', $nextTS);
				$nDay = ($DAY > $nMax) ? $nMax : $DAY;
				list($y, $m) = explode('-', date('Y-m', $nextTS));
				
				
				# Next month link
				$nextTS_Y = strtotime("$YEAR-$MONTH-01 +1 year");
				$nMax_Y = date('t', $nextTS_Y);
				$nDay_Y = ($DAY > $nMax_Y) ? $nMax_Y : $DAY;
				list($y_Y, $m_Y) = explode('-', date('Y-m', $nextTS_Y));
				

				if ( $maxY[0] >= $y ) {
					
					if(trim($ARGUMENTS['CUSTOM_FUNCTION']) != ""){
					$next = "<a title='Next Month' onClick=\"javascript:MovetoCallendar('$Element','$y','$m','$pDay','$this->SRC_PATH');".$ARGUMENTS['CUSTOM_FUNCTION']."('$y','$m','$pDay');\" href=\"javascript:void(0);\"><img align='absbottom' src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'></a>";
					$nextYear = "<a title='Next Year' onClick=\"javascript:MovetoCallendar('$Element','$y_Y','$m_Y','$pDay_Y','$this->SRC_PATH');".$ARGUMENTS['CUSTOM_FUNCTION']."('$y_Y','$m_Y','$pDay_Y');\" href=\"javascript:void(0);\"><img align='absbottom' src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'><img align='absbottom' src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'></a>";
					}else{
					$next = "<a title='Next Month' onClick=\"javascript:MovetoCallendar('$Element','$y','$m','$pDay','$this->SRC_PATH');\" href=\"javascript:void(0);\"><img align='absbottom' src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'></a>";
					$nextYear = "<a title='Next Year' onClick=\"javascript:MovetoCallendar('$Element','$y_Y','$m_Y','$pDay_Y','$this->SRC_PATH');\" href=\"javascript:void(0);\"><img align='absbottom' src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'><img align='absbottom' src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'></a>";
					}
				}else {
					$next = "<a title='Next Month' href=\"javascript:void(0);\"><img align='absbottom' src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'></a>";
					$nextYear = "<a title='Next Year' href=\"javascript:void(0);\"><img align='absbottom' src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'><img align='absbottom' src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'></a>";
				}
				return array("next"=>$next,"prev"=>$prev,"nextYear"=>$nextYear,"prevYear"=>$prevYear);
		}
		
		
		
		function DateNavigationMonth($MONTH, $YEAR,$DAY, $Element='myCalendar', $ARGUMENTS=array()) {
				
				if ( !$ARGUMENTS['maxYear'] || trim($ARGUMENTS['maxYear']) =="" ) {
					$maxY = explode( "-",$this->MAXYEAR );
				} else {	
					$maxY = explode( "-",$ARGUMENTS['maxYear'] );
				}
						
				if ( !$ARGUMENTS['minYear'] || trim($ARGUMENTS['minYear']) =="" )	{
					$minY = explode("-",$this->MINYEAR);
				} else {	
				    $minY = explode("-",$ARGUMENTS['minYear']);
				}
				
				# Previous month link
				$prevTS = strtotime("$YEAR-$MONTH-01 -1 month"); # timestamp of the first of last month
				$pMax = date('t', $prevTS);
				$pDay = ($DAY > $pMax) ? $pMax : $DAY;
				list($y, $m) = explode('-', date('Y-m', $prevTS));		
				
				if ( $minY[0] <= $y ) {
				$prev= "<a title='Previous' onClick=\"javascript:".$ARGUMENTS['CUSTOM_FUNCTION']."('$y','$m','$pDay');\" href=\"javascript:void(0);\"><img src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'><img src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'></a> ";
				}else {
				$prev= "<a href=\"javascript:void(0);\"><img src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'><img src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'></a> ";
				}
				# Next month link
				$nextTS = strtotime("$YEAR-$MONTH-01 +1 month");
				$nMax = date('t', $nextTS);
				$nDay = ($DAY > $nMax) ? $nMax : $DAY;
				list($y, $m) = explode('-', date('Y-m', $nextTS));

				if ( $maxY[0] >= $y ) {
				$next = "<a title='Next' onClick=\"javascript:".$ARGUMENTS['CUSTOM_FUNCTION']."('$y','$m','$pDay');\" href=\"javascript:void(0);\"><img src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'><img src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'></a>";
				}else {
				$next = "<a title='Next' href=\"javascript:void(0);\"><img src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'><img src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'></a>";
				}
				
				return array("next"=>$next,"prev"=>$prev);
		}
			
		function DrawCalendarMonth($YEAR, $MONTH,$DAY,$CALELEM='myCalendar',$CASEFETCH=array(),$ELEMOLD='',$NAVIG='') {
	/*
		$CASEFETCH = Array (FCase=>Function Case,minYear=>Callendar Min Year,maxYear=>Callendar Max Year,TimeShow => Show Time
	*/

			  $propid = $CASEFETCH['propid'];
		
			  if($NAVIG != 'NO')
			  $NAVIG = $this->DateNavigationMonth($MONTH, $YEAR,$DAY,$CALELEM,$CASEFETCH);
			  

			  # Set variables we will need to help with layouts
			  $FIRST       = mktime(0,0,0,$MONTH,1,$YEAR);	 	# timestamp for first of the month
			  $OFFSET      = date('w', $FIRST); 				# what day of the week we start counting on
			  $DAYSINMONTH = date('t', $FIRST);
			  $MONTHNAME   = date('F', $FIRST);
		  	  $j =0;
			  $objEVENTS	=	new CalendarEvents;
			  
			  if(trim($CASEFETCH['CUSTOM_FUNCTION']) != "")
			  $EVENTDAYS = $objEVENTS->GetEventYear($MONTH, $YEAR,$CASEFETCH['FCase'],$CASEFETCH['memberid'],$CASEFETCH['propid'],$CASEFETCH['flyer_id']);
			  
			  
			  
			  
			  	/***************************************************************************************************************/
				/* OWNER VIEW OF THE CALENDAR IN DETAILS PAGE  START HERE*/
				
				/* RETURN BLOCKED AND FIXED RATE COLOR */
				$days = $objEVENTS->setBackGroundColor($CASEFETCH['propid'],$MONTH,$YEAR);
				/* OWNER VIEW OF THE CALENDAR IN DETAILS PAGE START HERE */
				/***************************************************************************************************************/
			  
			  
			  # Start drawing calendar
	
			  if($ELEMOLD != 'YES') { 
			 
			  $out  .= "<div id=\"$CALELEM\" style=\"width:700px;display:block;z-index:5000;\">\n";
			  }
			  
				
			 	$out .= "<div  class='calforcolor' style=\"display:block;margin:2px;width:100%;\">\n";
			  	$out .= "<div align=\"center\" class='caltopcolor-month' style=\"height:32px;\">\n";
				$out .= "<div style=\"width:8%;float:left;text-align:left;margin-top:10px;\">&nbsp;&nbsp;{$NAVIG['prev']}</div>\n";
				$out .= "<div style=\"width:8%;float:left;text-align:left;\">&nbsp;</div>\n";
				$out .= "<div style=\"width:63%;float:left;text-align:center;\" title=$MONTHNAME-$YEAR>$MONTHNAME $YEAR</div>\n";
				$out .= "<div style=\"width:8%;float:left;text-align:right;margin-top:10px;float:right;\">{$NAVIG['next']}&nbsp;&nbsp;</div>\n";
			    $out .= "</div>\n";	
			  
			  	$out .="<div style=\"clear:both\"></div><div align=\"center\"  style=\"height:25px;background-color:#DDDDDD;vertical-align:middle;\">\n";
				    foreach ($this->WEEKDAYS as $wd) $out .=  "<div style=\"width:98px;float:left;height:15px;margin-top:8px;\" class=\"calweekname-month border_calendar_title\">$wd</div>\n";
			    $out .= "</div><div style=\"clear:both\"></div>\n";	
			
			    $i = 0;
			    for ($d = (1 - $OFFSET); $d <= $DAYSINMONTH; $d++) {
			    	
			    	
			    	
			    	/* RETURN BLOCKED AND FIXED RATE COLOR */
					$val = "";				 			
					//list($val,$id) = $objEVENTS->getEventBackColor($days,$d);
					
					list($val,$id,$type,$start,$end) = $objEVENTS->getEventBackColor($days,$d);
					
					$val = trim($val);
			 		$backlColor = "";
			 		if(trim($val) != ""){	//array_search($d,$days);
			 			$backlColor = "background-color:{$val};";
			 		}
			   
			   if (($i+1) %7 == 0)
			   $class = n_border_calendar_cols;
			   else
			   $class = border_calendar_cols;
			   
			    	
				if ($i % 7 == 0) $out .= "<div align=\"center\">\n"; // Start new row onmouseover=\"return overlib('This',RIGHT,WIDTH,150,BGCOLOR,'#afafaf',FGCOLOR,'#fbfbfb',TEXTCOLOR,'#F07113');\" onmouseout=\"return nd()\"
				if ($d < 1) $out .= "<div class=\"calnonMonthDay ".$class."\" style=\"width:98px;float:left;height:80px;\">&nbsp;</div>\n";
				else {
					
					list($exist,$popUp,$clickCap,$onClickUrl) = $this->CheckEventWithNos($d,$EVENTDAYS);
					
					
					   
					if($exist){
							
						//$popUp		=	"<div>".$MONTHSHORT . '-' . $d . ',' . $YEARNAME . "</div><div>" . $popUp. "</div>";
						$Descript	=	"<div>".$popUp."</div>";
						$CAPICOMSG	=	$CAPICO . $clickCap;
						
					    $url .= "style=\"cursor:pointer\" onclick=\"return overlib('$Descript',RIGHT,WIDTH,200,HEIGHT,20,BGCOLOR,'#afafaf',FGCOLOR,'#fbfbfb',TEXTCOLOR,'#F07113',STICKY,CAPTION,
	                                     '$CAPICOMSG',OFFSETX, 0, OFFSETY, 0, DELAY, 100,NOCLOSE);\" onmouseover=\"return overlib('$Descript',RIGHT,WIDTH,200,HEIGHT,20,BGCOLOR,'#afafaf',FGCOLOR,'#fbfbfb',TEXTCOLOR,'#F07113',STICKY,CAPTION,
	                                     '$CAPICOMSG',OFFSETX, 0, OFFSETY, 0, DELAY, 100,NOCLOSE);\" onmouseout=\"return nd();\"";
					}else {
						$url = "";
					}
						
					
						if(!$this->checkInDateGreater(date("Y-m-d"),$YEAR."-".$MONTH."-".$d))
				 		$type = "propertyBlock";
				 		
				 		if($objEVENTS->blockPropertyBookedDate($propid,$YEAR."-".$MONTH."-".$d))
				 		$type = "propertyBlock";
				 		
				 		
				 		if($type == "propertyBlock"){
				 		
				 		$out .= "<div class=\"".$class."\" style=\"width:98px;float:left;height:80px;text-align:right;$backlColor;text-decoration:line-through;font-size:14px;font-weight:bold;\" title=\"".$this->MOD_VARIABLES['MOD_HINTS']['MSG_PROPERTY_NOT_AVIALABLE']."\">$d</div>\n";
				 		}else{
					
						$out .= "<div id=\"dateCont_$d\" class=\"".$class."\" style=\"width:98px;float:left;height:80px;text-align:right;$backlColor;\" $url><a href=\"javascript:void(0);\"".$url." class=\"calnonevent\"><span style=\"font:Verdana, Arial, Helvetica, sans-serif;font-size:14px;\">$d</span></a></div>\n";
						
				 		}
						 
				}
				++$i; // Increment position counter
				
				if ($i % 7 == 0) $out .= "</div><div style=\"clear:both\"></div>\n"; // End row on the 7th day
			  }
			
			  
			  
			  if($i > 28){
			  	

				  if($i > 35){
					$n= 42-$i;
				  }else{
					$n=35-$i;
				  }
			  }
				
			  		
				for($k=0;$k<$n;$k++){
					
					//Afsal Ismail
				   if (($k+1)==$n)
				   $class = n_border_calendar_cols;
				   else
				   $class = border_calendar_cols;
					
				 $out .= "<div class=\"calnonMonthDay ".$class."\" style=\"width:98px;float:left;height:80px;\">&nbsp;</div>\n";
				}
				
			  
			  

			if ( $CASEFETCH['TimeShow'] == 'YES' )	{
				 $out .=  $this->TimeLayer($CALELEM);
			}
			
			$out .= "</div><div style=\"clear:both\"></div>\n";



			  $out  .= "<input name=\"hidCASEFETCH_$CALELEM\" id=\"hidCASEFETCH_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['FCase']}\">\n";
			  $out  .= "<input name=\"minYear_$CALELEM\" id=\"minYear_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['minYear']}\">\n";
			  $out  .= "<input name=\"maxYear_$CALELEM\" id=\"maxYear_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['maxYear']}\">\n";
			  $out  .= "<input name=\"TimeShow_$CALELEM\" id=\"TimeShow_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['TimeShow']}\">\n";
			  $out  .= "<input name=\"CUSTOM_FUNCTION_$CALELEM\" id=\"CUSTOM_FUNCTION_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['CUSTOM_FUNCTION']}\">\n";
			  $out .= "</div>\n";	
			 
			  if($ELEMOLD != 'YES') {
			  $out .= "</div>\n";	
			 
			  }
			  
			  return $out;
				
		}
		/*
		* @Author:Afsal Ismail
		* Dated:29-12-2007
		* Get the day view details
		*/
		function printCalendarDayView($CALENDARLIST,$TIME_ARRAY,$DATE,$NEXTDATE,$PREVDATE){
				
				$PATH_CALENDAR = SITE_URL."/templates/".$this->config['curr_tpl'];
				
				$strHtml  = '<div align="center" class="floatleft" style="width:30%;">';
				$strHtml .=	$CALENDARLIST;
				$strHtml .= '</div>';
				$strHtml .= '<div class="floatleft" style="width:5%;">&nbsp;</div>';
				$strHtml .= '<div class="floatleft" style="width:60%;">';
				$strHtml .= '<div style="width:100%;float:left;" class="calendar_day_top">';
				$strHtml .= '<div align="left" class="floatleft" style="width:24%;"><img src="'.$PATH_CALENDAR.'/images/left.gif" border="0" style="cursor:pointer;" onClick="return dispDayView('.$PREVDATE[0].",".$PREVDATE[1].",".$PREVDATE[2].')"></div>';
				$strHtml .= '<div align="center" style="float:left;">'.date("D M d Y",strtotime($DATE)).'</div>';
				$strHtml .= '<div align="right" class="floatright"><img src="'.$PATH_CALENDAR.'/images/right.gif" border="0" style="cursor:pointer;"  onClick="return dispDayView('.$NEXTDATE[0].",".$NEXTDATE[1].",".$NEXTDATE[2].')"></div>';
				//$strHtml .= '<div align="right" class="floatright"><img src="'.$PATH_CALENDAR.'/images/right.gif" border="0" style="cursor:pointer;"  onClick="return dispDayView('.$NEXTDATE[0].",".$NEXTDATE[1].')"></div>';
				$strHtml .=	'</div>';
				$strHtml .=	'<div class="divSpc"></div>';	
				$strHtml .=	'<div><hr size=1></div>';
				foreach ($TIME_ARRAY as $time_row){
				$strHtml .=	'<div class="bodytext" style="float:left;font-weight:bold;" >'.$time_row.'</div>';
				$strHtml .=	'<div class="divSpc"></div>';
				$strHtml .=	'<div><hr size=1></div>';
				}
				$strHtml .=	'</div>';
				
				$strHtml .= '<div class="divSpc"></div>';
				return $strHtml;
		}
		/*
		* @Author:Afsal Ismail
		* Dated:09-01-2008
		* Get the Top Navigation Tab of MyCalendar
		*/
		function printCalendarWeekView($CALENDARLIST,$WEEKLIST,$DATE,$NEXTWEEK,$PREVWEEK,$EVENTS){
				
				$PATH_CALENDAR = SITE_URL."/templates/".$this->config['curr_tpl'];
				
				$strHtml  = '<div align="center" class="floatleft" style="width:30%;">';
				$strHtml .=	$CALENDARLIST;
				$strHtml .= '</div>';
				$strHtml .= '<div class="floatleft" style="width:5%;">&nbsp;</div>';
				$strHtml .= '<div class="floatleft" style="width:60%;">';
				$strHtml .= '<div style="width:100%;float:left;" class="calendar_day_top">';
				$strHtml .= '<div align="left" class="floatleft" style="width:24%;"><img src="'.$PATH_CALENDAR.'/images/left.gif" border="0" style="cursor:pointer;" onClick="return dispWeekView('.$PREVWEEK[0].",".$PREVWEEK[1].",".$PREVWEEK[2].')"></div>';
				$strHtml .= '<div align="center" style="float:left;">'.date("F j, Y",strtotime($WEEKLIST[1])).'&nbsp;&nbsp;&nbsp;'.date("F j, Y",strtotime($WEEKLIST[7])).'</div>';
				$strHtml .= '<div align="right" class="floatright"><img src="'.$PATH_CALENDAR.'/images/right.gif" border="0" style="cursor:pointer;"  onClick="return dispWeekView('.$NEXTWEEK[0].",".$NEXTWEEK[1].",".$NEXTWEEK[2].')"></div>';
				$strHtml .=	'</div>';
				$strHtml .=	'<div class="divSpc"></div>';	
				$strHtml .=	'<div><hr size=1></div>';
				
				foreach ($WEEKLIST as $row){
				
					list($y,$m,$d)=split("-",$row);
					foreach ($EVENTS as $evt){
				
						if($evt["date"] == $d){
							
							$strDiv = "<div class=\"border\">".$evt["onClickFun"]."</div>";	
						}
					}
						
				$strHtml .=	'<div class="bodytext" style="float:left;font-weight:bold;" >'.date("D, m/d",strtotime($row)).'</div>';
				$strHtml .= "<div class=\"divSpc\"></div>";
				$strHtml .= $strDiv;
				$strHtml .=	'<div class="divSpc"></div>';
				$strHtml .=	'<div><hr size=1></div>';
				
				}
				$strHtml .=	'</div>';
				
				$strHtml .= '<div class="divSpc"></div>';
				return $strHtml;
				
		}
		/*
		* @Author:Afsal Ismail
		* Dated:09-01-2008
		* Get the Top Navigation Tab of MyCalendar
		*/
		function calenDarNavig($action,$type = '',$propid='',$prop_title='',$action_from=''){
				

				if(trim($type)!= "" && trim($propid)!="")
				$param = "&type=".$type."&propid=".$propid;
				
				if(trim($action_from) !=''){
					if(trim($param)!="")
					$param .="&action_from=".$action_from;
				}
				
				if(trim($action_from) !=""){
				
						$strHtm .= '<div id="Tabs">';
						$strHtm .= '<ul>';
						
						if($action == "mycalendar_year")
						$strHtm .= '<li id="selectedItem"><a href="javascript:void(0);" onClick="dispYearView(\'\');">Year View</a></li>';
						else
						$strHtm .= '<li><a href="javascript:void(0);" onClick="dispYearView(\'\');">Year View</a></li>';
						
						if($action == "mycalendar_month")
						$strHtm .= '<li id="selectedItem"><a href="javascript:void(0);" onClick="dispMonthView('.date("Y").','.date('m').','.date('d').');">Month View</a></li>';
						else
						$strHtm .= '<li><a href="javascript:void(0);" onClick="dispMonthView('.date("Y").','.date('m').','.date('d').');">Month View</a></li>';

						//if($action == "mycalendar_week")
						//$strHtm .= '<li id="selectedItem"><a href="javascript:void(0);" onClick="top.calendarView(\'week\');">Week View</a></li>';
						//else
						//$strHtm .= '<li><a href="javascript:void(0);" onClick="top.calendarView(\'week\');">Week View</a></li>';
						
						$strHtm .= '</ul>';
						$strHtm .= '</div>';	
				}
				else 
				{
				
						$strHtm .= '<div id="Tabs">';
						$strHtm .= '<ul>';
						
						if($action == "mycalendar_year")
						$strHtm .= '<li id="selectedItem"><a href="'.makeLink(array("mod"=>"album", "pg"=>"booking"),"act=mycalendar_year").'">Year View</a></li>';
						else
						$strHtm .= '<li><a href="'.makeLink(array("mod"=>"album", "pg"=>"booking"),"act=mycalendar_year".$param).'">Year View</a></li>';
						
						if($action == "mycalendar_month")
						$strHtm .= '<li id="selectedItem"><a href="'.makeLink(array("mod"=>"album", "pg"=>"booking"),"act=mycalendar_month".$param).'">Month View</a></li>';
						else
						$strHtm .= '<li><a href="'.makeLink(array("mod"=>"album", "pg"=>"booking"),"act=mycalendar_month".$param).'">Month View</a></li>';
						
						if($type != "propCal"){
							
							if($action == "mycalendar_day")
							$strHtm .= '<li id="selectedItem"><a href="'.makeLink(array("mod"=>"album", "pg"=>"booking"),"act=mycalendar_day".$param).'">Day View</a></li>';
							else
							$strHtm .= '<li><a href="'.makeLink(array("mod"=>"album", "pg"=>"booking"),"act=mycalendar_day".$param).'">Day View</a></li>';
							
						}
						
						if($action == "mycalendar_week")
						$strHtm .= '<li id="selectedItem"><a href="'.makeLink(array("mod"=>"album", "pg"=>"booking"),"act=mycalendar_week".$param).'">Week View</a></li>';
						else
						$strHtm .= '<li><a href="'.makeLink(array("mod"=>"album", "pg"=>"booking"),"act=mycalendar_week".$param).'">Week View</a></li>';
						
						$strHtm .= '</ul>';
						$strHtm .= '</div>';
						
		
						if(trim($type) == "propCal"){
							if(strlen($prop_title) > 43)
								$title = substr($prop_title,0,43)."...";
							else 
								$title =substr($prop_title,0,43);
						
							
							$strHtm .= '<div class="floatleft">&nbsp;&nbsp;</div>';
							$strHtm .= '<div class="floatleft"><span class="meroon">Property Title : </span><span class="bodytext">'.$title.'</span></div>';
						
						}
				}
				return $strHtm;
		}	
		
		function dragMonth($YEAR, $MONTH,$DAY,$CALELEM='myCalendar',$CASEFETCH=array(),$ELEMOLD='',$NAVIG='') {
	/*
		$CASEFETCH = Array (FCase=>Function Case,minYear=>Callendar Min Year,maxYear=>Callendar Max Year,TimeShow => Show Time
	*/
	
			  if($NAVIG != 'NO')
			  $NAVIG = $this->DateNavigationdragMonth($MONTH, $YEAR,$DAY,$CALELEM,$CASEFETCH);
			  

			  # Set variables we will need to help with layouts
			  $FIRST       = mktime(0,0,0,$MONTH,1,$YEAR);	 	# timestamp for first of the month
			  $OFFSET      = date('w', $FIRST); 				# what day of the week we start counting on
			  $DAYSINMONTH = date('t', $FIRST);
			  $MONTHNAME   = date('F', $FIRST);
			  $saveDate	   = $YEAR."-".$MONTH."-"; 
		  	  $j =0;
			  $objEVENTS	=	new CalendarEvents();
			  
			   $dispaly_format = $this->config["calendar_date_format"];
			  
			 // if(trim($CASEFETCH['CUSTOM_FUNCTION']) != "")
			 // $EVENTDAYS = $objEVENTS->GetEventYear($MONTH, $YEAR,$CASEFETCH['FCase'],$CASEFETCH['memberid'],$CASEFETCH['propid'],$CASEFETCH['flyer_id']);
			  
			  # Start drawing calendar
			  
			  if($ELEMOLD != 'YES') { 
			  	
			  $out  = "<div id=\"$CALELEM\" style=\"width:430px;display:block;z-index:5000;\">\n";
			  }
			  
			 	$out .= "<div  class=\"calforcolor\" style=\"display:block;margin:2px;width:100%;\">\n";
			  	$out .= "<div align=\"center\" class=\"caltopcolor-month\" style=\"height:22px;width:100%;\">\n";
				$out .= "<div style=\"width:8%;float:left;text-align:left;margin-top:5px;\">&nbsp;&nbsp;{$NAVIG['prev']}</div>\n";
				$out .= "<div style=\"width:5%;float:left;text-align:left;\">&nbsp;</div>\n";
				$out .= "<div style=\"width:63%;float:left;text-align:center;\" title=$MONTHNAME-$YEAR>$MONTHNAME $YEAR</div>\n";
				$out .= "<div style=\"width:8%;float:left;text-align:right;margin-top:5px;float:right;\">{$NAVIG['next']}&nbsp;&nbsp;</div>\n";
			    $out .= "</div>\n";	
			  
			  	$out .="<div style=\"clear:both\"></div><div align=\"center\"  style=\"height:22px;background-color:#DDDDDD;vertical-align:middle;\">\n";
				    foreach ($this->WEEKDAYS as $wd) $out .=  "<div style=\"width:13.5%;float:left;height:15px;margin-top:8px;\" class=\"calweekname-month border_calendar_title\">$wd</div>\n";
			    $out .= "</div><div style=\"clear:both\"></div>\n";	
			
			    $i = 0;
			    for ($d = (1 - $OFFSET); $d <= $DAYSINMONTH; $d++) {
			   
			   if (($i+1) %7 == 0)
			   $class = n_border_calendar_cols;
			   else
			   $class = border_calendar_cols;
			   
			   if ($i % 7 == 0)
		 	   $w = "15.5";
		 	   else 
		 	   $w = "13.5";
			    	
				if ($i % 7 == 0) $out .= "<div align=\"center\">\n"; // Start new row onmouseover=\"return overlib('This',RIGHT,WIDTH,150,BGCOLOR,'#afafaf',FGCOLOR,'#fbfbfb',TEXTCOLOR,'#F07113');\" onmouseout=\"return nd()\"
				if ($d < 1) $out .= "<div class=\"calnonMonthDay ".$class."\" style=\"width:".$w."%;float:left;height:40px;\">&nbsp;</div>\n";
				else {
					   //list($exist,$popUp,$clickCap,$onClickUrl) = $this->CheckEventWithNos($d,$EVENTDAYS);
					   $exist = "";
					   if ($exist) {
							
							
						 	}else{
						 		
						 		if ($i % 7 == 0)
						 		$w = "15.5";
						 		else 
						 		$w = "13.5";
						 		
						 		$days = $CASEFETCH["event_arry"];
				
						 		$val = "";				 			
								list($val,$id) = $objEVENTS->getEventBackColor($days,$d);
								$val = trim($val);
						 		$backlColor = "";
						 		if(trim($val) != ""){	//array_search($d,$days);
						 			$backlColor = "background-color:{$val};";
						 		}
						 		//$dynamicDate = $YEAR."-".$MONTH."-".$d;
						 		
						 		//if($objEVENTS->compareDates(date("Y-m-d"),$dynamicDate) == "LESSTHAN"){
						 		//$out .= "<div id=\"dateCont_$d\" class=\"".$class." calevent\" style=\"width:".$w."%;float:left;height:40px;text-align:right;background-color:{$val}\" onmousedown=\"javascript:setMouseDown_dragOpt(this,$d,'#EEEEEE',event,' $MONTHNAME $YEAR','$saveDate','$id');\" onmouseup=\"javascript:setMouseDown_dragOpt(this,$d,'#EEEEEE',event,' $MONTHNAME $YEAR','$saveDate');\" onmouseover=\"javascript:setMouseOver_dragOpt(this,$d,'#EEEEEE',event,' $MONTHNAME $YEAR','$saveDate');\">$d</div>\n";
						 		//}else{
						 		
						 		$out .= "<div id=\"dateCont_$d\" class=\"".$class." \" style=\"width:".$w."%;float:left;height:40px;text-align:right;background-color:{$val}\" onmousedown=\"javascript:setMouseDown_dragOpt(this,$d,'#EEEEEE',event,' $MONTHNAME $YEAR','$saveDate','$id','$dispaly_format');\" onmouseup=\"javascript:setMouseDown_dragOpt(this,$d,'#EEEEEE',event,' $MONTHNAME $YEAR','$saveDate','','$dispaly_format');\" onmouseover=\"javascript:setMouseOver_dragOpt(this,$d,'#EEEEEE',event,' $MONTHNAME $YEAR','$saveDate','','$dispaly_format');\">$d</div>\n";
						 		//}
						 		$backlColor = "";
						 	}
					}

				++$i; // Increment position counter
				
				if ($i % 7 == 0) $out .= "</div><div style=\"clear:both\"></div>\n"; // End row on the 7th day
			  }
			
			  
			  if($i > 28){

				  if($i > 35){
					$n= 42-$i;
				  }else{
					$n=35-$i;
				  }
			  }
				
			  		
				for($k=0;$k<$n;$k++){
					
					//Afsal Ismail
				   if (($k+1)==$n)
				   $class = n_border_calendar_cols;
				   else
				   $class = border_calendar_cols;
					
				$out .= "<div class=\"calnonMonthDay ".$class."\" style=\"width:13.5%;float:left;height:40px;\">&nbsp;</div>\n";
				}
				
			 
			if ( $CASEFETCH['TimeShow'] == 'YES' )	{
				 $out .=  $this->TimeLayer($CALELEM);
			}
			
			  $out .= "</div><div style=\"clear:both\"></div>\n";


			  $out  .= "<input name=\"hidCASEFETCH_$CALELEM\" id=\"hidCASEFETCH_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['FCase']}\">\n";
			  $out  .= "<input name=\"minYear_$CALELEM\" id=\"minYear_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['minYear']}\">\n";
			  $out  .= "<input name=\"maxYear_$CALELEM\" id=\"maxYear_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['maxYear']}\">\n";
			  $out  .= "<input name=\"TimeShow_$CALELEM\" id=\"TimeShow_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['TimeShow']}\">\n";
			  $out  .= "<input name=\"CUSTOM_FUNCTION_$CALELEM\" id=\"CUSTOM_FUNCTION_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['CUSTOM_FUNCTION']}\">\n";
			  $out .= "</div>\n";	
			 
			  if($ELEMOLD != 'YES') {
			  
			   $out .= "</div>\n";	
			  }
			  return $out;
		}
		function DateNavigationdragMonth($MONTH, $YEAR,$DAY, $Element='myCalendar', $ARGUMENTS=array()) {
				
				if ( !$ARGUMENTS['maxYear'] || trim($ARGUMENTS['maxYear']) =="" ) {
					$maxY = explode( "-",$this->MAXYEAR );
				} else {	
					$maxY = explode( "-",$ARGUMENTS['maxYear'] );
				}
						
				if ( !$ARGUMENTS['minYear'] || trim($ARGUMENTS['minYear']) =="" )	{
					$minY = explode("-",$this->MINYEAR);
				} else {	
				    $minY = explode("-",$ARGUMENTS['minYear']);
				}
				
				# Previous month link
				$prevTS = strtotime("$YEAR-$MONTH-01 -1 month"); # timestamp of the first of last month
				$pMax = date('t', $prevTS);
				$pDay = ($DAY > $pMax) ? $pMax : $DAY;
				list($y, $m) = explode('-', date('Y-m', $prevTS));		
				
				# Previous year link
				$prevTS_Y = strtotime("$YEAR-$MONTH-01 -1 year"); # timestamp of the first of last month
				$pMax_Y = date('t', $prevTS_Y);
				$pDay_Y = ($DAY > $pMax_Y) ? $pMax_Y : $DAY;
				list($y_Y, $m_Y) = explode('-', date('Y-m', $prevTS_Y));
				
								
				if ( $minY[0] <= $y ) {
				$prev= "<a title='Previous Year' onClick=\"javascript:".$ARGUMENTS['CUSTOM_FUNCTION']."('$y_Y','$m_Y','$pDay_Y');\" href=\"javascript:void(0);\"><img src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'><img src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'></a> " . "<a title='Previous Month' onClick=\"javascript:".$ARGUMENTS['CUSTOM_FUNCTION']."('$y','$m','$pDay');\" href=\"javascript:void(0);\"><img src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'></a> ";
				}else {
				$prev= "<a href=\"javascript:void(0);\"><img src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'><img src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'></a> " . "<a href=\"javascript:void(0);\"><img src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'><img src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'></a> ";
				}
				
				
				# Next month link
				$nextTS = strtotime("$YEAR-$MONTH-01 +1 month");
				$nMax = date('t', $nextTS);
				$nDay = ($DAY > $nMax) ? $nMax : $DAY;
				list($y, $m) = explode('-', date('Y-m', $nextTS));
				
				# Next Year link
				$nextTS_Y = strtotime("$YEAR-$MONTH-01 +1 year");
				$nMax_Y = date('t', $nextTS_Y);
				$nDay_Y = ($DAY > $nMax_Y) ? $nMax_Y : $DAY;
				list($y_Y, $m_Y) = explode('-', date('Y-m', $nextTS_Y));
				

				if ( $maxY[0] >= $y ) {
				$next = "<a title='Next Month' onClick=\"javascript:".$ARGUMENTS['CUSTOM_FUNCTION']."('$y','$m','$pDay');\" href=\"javascript:void(0);\"><img src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'></a>" . "&nbsp;<a title='Next Year' onClick=\"javascript:".$ARGUMENTS['CUSTOM_FUNCTION']."('$y_Y','$m_Y','$pDay_Y');\" href=\"javascript:void(0);\"><img src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'><img src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'></a>";
				}else {
				$next = "<a title='Next Month' href=\"javascript:void(0);\"><img src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'></a>" . "&nbsp;<a title='Next Year' href=\"javascript:void(0);\"><img src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'><img src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'></a>";
				}
				
				return array("next"=>$next,"prev"=>$prev);
		}
		
		
		function DrawCalendarEvents($YEAR, $MONTH,$DAY,$CALELEM='myCalendar',$CASEFETCH=array(),$ELEMOLD='',$NAVIG='') {
	/*
		$CASEFETCH = Array (FCase=>Function Case,minYear=>Callendar Min Year,maxYear=>Callendar Max Year,TimeShow => Show Time
	*/

			  if($NAVIG != 'NO')
			  $NAVIG = $this->DateNavigationEventsCalendar($MONTH, $YEAR,$DAY,$CALELEM,$CASEFETCH);
			  
			
			
			  # Set variables we will need to help with layouts
			  $FIRST       = mktime(0,0,0,$MONTH,1,$YEAR);	 	# timestamp for first of the month
			  $OFFSET      = date('w', $FIRST); 				# what day of the week we start counting on
			  $DAYSINMONTH = date('t', $FIRST);
			  $MONTHNAME   = date('F', $FIRST);
		 
			  $objEVENTS	=	new CalendarEvents();
			  
			  $propid = $CASEFETCH["propid"];
			  /*
			  if(trim($CASEFETCH['CUSTOM_FUNCTION']) != "")
			  $EVENTDAYS = $objEVENTS->GetEventYear($MONTH, $YEAR,$CASEFETCH['FCase'],$CASEFETCH['memberid'],$CASEFETCH['propid']);
			  */
			 /* Display Format */
			 $dispaly_format = $this->config["calendar_date_format"];

			  # Start drawing calendar,$DISPLAY='none'
			  if( trim($CASEFETCH['DISPLAY']) )
			  $DISPLAY	= $CASEFETCH['DISPLAY'];
			  else 
			  $DISPLAY	= 'none';
			  
			  
			  if($ELEMOLD != 'YES') { 
			  $out  = "<div id=\"$CALELEM\" style=\"width:200px;display:$DISPLAY;z-index:5000;\">\n";
			  }
			  
			  /*
			  if($ELEMOLD == 'YES') { 
			  $out  = "<div id=$CALELEM style=\"width:200px;display:block;z-index:100;\">\n";
			  } else {
			  $out  = "<div id=$CALELEM style=\"width:200px;display:none;z-index:100;\">\n";
			  }
			  */
				$out  .= "<div  class='calforcolor' style=\"display:block;margin:2px\" width='100%'>\n";
			  	$out .="<div align=\"center\" class='caltopcolor' style=\"height:17px;\">\n";
					$out .= "<div style=\"width:8%;float:left;text-align:left;margin-top:3px;\">{$NAVIG['prevYear']}</div>\n";
					$out .= "<div style=\"width:8%;float:left;text-align:left;margin-top:3px;\">{$NAVIG['prev']}</div>\n";
															
					$out .= "<div style=\"width:57%;float:left;text-align:center;\" title=$MONTHNAME-$YEAR>$MONTHNAME $YEAR</div>\n";
					
					$out .= "<div style=\"width:8%;float:left;text-align:left;margin-top:3px;\">{$NAVIG['next']}</div>\n";
					$out .= "<div style=\"width:8%;float:left;text-align:left;margin-top:3px;\">{$NAVIG['nextYear']}</div>\n";
					
					$out .= "<div style=\"width:10%;float:left;text-align:right\" title='Close'>";
					
					if($CASEFETCH['CLOSE_BUTTON'] != 'no')
					$out .= "<a href=\"javascript:void(0);\" style=\"cursor:pointer;color:#ffffff\" onClick=\"javascript:showElement( 'SELECT' );showElement( 'APPLET' );document.getElementById('$CALELEM').style.display='none'; \"><img border=0 align=right src=$this->SRC_PATH/includes/callendar/icnCClose.gif></a>";
					
					$out .="</div>\n";
					
			    $out .= "</div>\n";	
			  
			  	$out .="<div style=\"clear:both\"></div><div align=\"center\" style=\"height:15px;background-color:#DDDDDD;vertical-align:middle;\" >\n";
				    foreach ($this->WEEKDAYS as $wd) $out .=  "<div style=\"width:14%;float:left;height:15px\" class=\"calweekname\">$wd</div>\n";
			    $out .= "</div><div style=\"clear:both\"></div>\n";	
				
				

			    $i = 0;
			    for ($d = (1 - $OFFSET); $d <= $DAYSINMONTH; $d++) {
				if ($i % 7 == 0) $out .= "<div align=\"center\">\n"; // Start new row onmouseover=\"return overlib('This',RIGHT,WIDTH,150,BGCOLOR,'#afafaf',FGCOLOR,'#fbfbfb',TEXTCOLOR,'#F07113');\" onmouseout=\"return nd()\"
				if ($d < 1) $out .= "<div class=\"calnonMonthDay\" style=\"width:14%;float:left\">&nbsp;</div>\n";
				else {
						
				 		$days = $CASEFETCH["event_arry"];
				 		
						//print_r($days);

				 		$val = "";
				 		$type = "";
				 					 			
						list($val,$id,$type,$start,$end) = $objEVENTS->getEventBackColor($days,$d);
				
						$val = trim($val);
				 		$backlColor = "";
				 		if(trim($val) != ""){	//array_search($d,$days);
				 			$backlColor = "background-color:{$val};";
				 		}
						
				 		if(!$this->checkInDateGreater(date("Y-m-d"),$YEAR."-".$MONTH."-".$d))
				 		$type = "propertyBlock";
				 		
				 		if($objEVENTS->blockPropertyBookedDate($propid,$YEAR."-".$MONTH."-".$d))
				 		$type = "propertyBlock";
				 		
				 		
				 		if($type == "propertyBlock" || $type == "auction"){
				 		
				 		$out .= "<div style=\"width:14%;float:left;\" class=\"block-day\">$d</div>\n";
				 		}
				 		else
				 		{
				 			if($CASEFETCH["cal"] == "start"){
				 				//if($start == $d)
						//$out .= "<div style=\"width:14%;float:left;background-color:{$val};\"><a onClick=\"javascript:".$CASEFETCH['CUSTOM_FUNCTION']."('$CALELEM',$YEAR,$MONTH,$d,'Yes');document.getElementById('txt$CALELEM').value=formatDate('$MONTH/$d/$YEAR','$dispaly_format','yes');displayBookingCharges();document.getElementById('$CALELEM').style.display='none'\" href=\"javascript:void(0);\" class=\"calnonevent\"><span style=\"color:#FF0000;font-weight:bold;\">$d</span></a></div>\n";
							//	elseif($type == "propertyReserve")
						//$out .= "<div style=\"width:14%;float:left;background-color:{$val};\"><a href=\"javascript:void(0);\" class=\"calnonevent\">$d</a></div>\n";
							//	else
						$out .= "<div style=\"width:14%;float:left;background-color:{$val};\"><a onClick=\"javascript:document.getElementById('txt$CALELEM').value=formatDate('$MONTH/$d/$YEAR','$dispaly_format','yes');displayBookingCharges();document.getElementById('$CALELEM').style.display='none'\" href=\"javascript:void(0);\" class=\"calnonevent\">$d</a></div>\n";
						
								
				 			}
				 			 
				 			if($CASEFETCH["cal"] == "end"){
				 			//	if($end == $d)
						//$out .= "<div style=\"width:14%;float:left;background-color:{$val};\"><a onClick=\"javascript:".$CASEFETCH['CUSTOM_FUNCTION']."('$CALELEM',$YEAR,$MONTH,$d,'Yes');document.getElementById('txt$CALELEM').value=formatDate('$MONTH/$d/$YEAR','$dispaly_format','yes');displayBookingCharges();document.getElementById('$CALELEM').style.display='none'\" href=\"javascript:void(0);\" class=\"calnonevent\"><span style=\"color:#FF0000;font-weight:bold;\">$d</span></a></div>\n";
								//elseif($type == "propertyReserve")
						//$out .= "<div style=\"width:14%;float:left;background-color:{$val};\"><a href=\"javascript:void(0);\" class=\"calnonevent\">$d</a></div>\n";
								//else
						$out .= "<div style=\"width:14%;float:left;background-color:{$val};\"><a onClick=\"javascript:document.getElementById('txt$CALELEM').value=formatDate('$MONTH/$d/$YEAR','$dispaly_format','yes');displayBookingCharges();document.getElementById('$CALELEM').style.display='none';\" href=\"javascript:void(0);\" class=\"calnonevent\">$d</a></div>\n";
								
				 			}
				 		}
						

					}
				++$i; // Increment position counter
				if ($i % 7 == 0) $out .= "</div><div style=\"clear:both\"></div>\n"; // End row on the 7th dayformatDate(CtrlSDate,"MM/dd/yyyy");
			  }


			if ( $CASEFETCH['TimeShow'] == 'YES' )	{
				 $out .=  $this->TimeLayer($CALELEM);
			}

			$out .= "</div><div style=\"clear:both\"></div>\n";



			  $out  .= "<input name=\"hidCASEFETCH_$CALELEM\" id=\"hidCASEFETCH_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['FCase']}\">\n";
			  $out  .= "<input name=\"minYear_$CALELEM\" id=\"minYear_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['minYear']}\">\n";
			  $out  .= "<input name=\"maxYear_$CALELEM\" id=\"maxYear_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['maxYear']}\">\n";
			  $out  .= "<input name=\"maxYear_$CALELEM\" id=\"maxYear_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['maxYear']}\">\n";
			  $out  .= "<input name=\"TimeShow_$CALELEM\" id=\"TimeShow_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['TimeShow']}\">\n";
			  $out  .= "<input name=\"CUSTOM_FUNCTION_$CALELEM\" id=\"CUSTOM_FUNCTION_$CALELEM\" type=\"hidden\" value=\"{$CASEFETCH['CUSTOM_FUNCTION']}\">\n";

			  $out .= "</div>\n";	
			  
			  if($ELEMOLD != 'YES') {
			  $out .= "</div>\n";	
			  }
			  
			  return $out;
				
		}
		
		function DateNavigationEventsCalendar($MONTH, $YEAR,$DAY, $Element='myCalendar', $ARGUMENTS=array()) {
				
			
			
				if ( !$ARGUMENTS['maxYear'] || trim($ARGUMENTS['maxYear']) =="" ) {
					$maxY = explode( "-",$this->MAXYEAR );
				} else {	
					$maxY = explode( "-",$ARGUMENTS['maxYear'] );
				}
						
				if ( !$ARGUMENTS['minYear'] || trim($ARGUMENTS['minYear']) =="" )	{
					$minY = explode("-",$this->MINYEAR);
				} else {	
				    $minY = explode("-",$ARGUMENTS['minYear']);
				}
				
				# Previous month link
				$prevTS = strtotime("$YEAR-$MONTH-01 -1 month"); # timestamp of the first of last month
				$pMax = date('t', $prevTS);
				$pDay = ($DAY > $pMax) ? $pMax : $DAY;
				list($y, $m) = explode('-', date('Y-m', $prevTS));		
				
				# Previous year link
				$prevTS_Y = strtotime("$YEAR-$MONTH-01 -1 year"); # timestamp of the first of last year
				$pMax_Y = date('t', $prevTS_Y);
				$pDay_Y = ($DAY > $pMax_Y) ? $pMax_Y : $DAY;
				list($y_Y, $m_Y) = explode('-', date('Y-m', $prevTS_Y));
				
				
				
				if ( $minY[0] <= $y ) {
					
					if(trim($ARGUMENTS['CUSTOM_FUNCTION']) != ""){
					$prev= "<a title='Previous Month' onClick=\"javascript:".$ARGUMENTS['CUSTOM_FUNCTION']."('$Element','$y','$m','$pDay','Yes');\" href=\"javascript:void(0);\"><img align='absbottom' src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'></a> ";
					$prevYear= "<a title='Previous Year' onClick=\"javascript:".$ARGUMENTS['CUSTOM_FUNCTION']."('$Element','$y_Y','$m_Y','$pDay_Y','Yes');\" href=\"javascript:void(0);\"><img align='absbottom' src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'><img align='absbottom' src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'></a> ";
					}
				}else {
					$prev= "<a href=\"javascript:void(0);\"><img align='absbottom' src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'><img align='absbottom' src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'></a> ";
					$prevYear= "<a href=\"javascript:void(0);\"><img align='absbottom' src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'><img align='absbottom' src='$this->SRC_PATH/includes/callendar/left_white_arrow.gif' border='0'></a> ";
				}
				
				
				
				# Next month link
				$nextTS = strtotime("$YEAR-$MONTH-01 +1 month");
				$nMax = date('t', $nextTS);
				$nDay = ($DAY > $nMax) ? $nMax : $DAY;
				list($y, $m) = explode('-', date('Y-m', $nextTS));
				
				
				# Next month link
				$nextTS_Y = strtotime("$YEAR-$MONTH-01 +1 year");
				$nMax_Y = date('t', $nextTS_Y);
				$nDay_Y = ($DAY > $nMax_Y) ? $nMax_Y : $DAY;
				list($y_Y, $m_Y) = explode('-', date('Y-m', $nextTS_Y));
				

				if ( $maxY[0] >= $y ) {
					
					if(trim($ARGUMENTS['CUSTOM_FUNCTION']) != ""){
					$next = "<a title='Next Month' onClick=\"javascript:".$ARGUMENTS['CUSTOM_FUNCTION']."('$Element','$y','$m','$pDay','Yes');\" href=\"javascript:void(0);\"><img align='absbottom' src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'></a>";
					$nextYear = "<a title='Next Year' onClick=\"javascript:".$ARGUMENTS['CUSTOM_FUNCTION']."('$Element','$y_Y','$m_Y','$pDay_Y','Yes');\" href=\"javascript:void(0);\"><img align='absbottom' src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'><img align='absbottom' src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'></a>";
					}
				}else {
					$next = "<a title='Next Month' href=\"javascript:void(0);\"><img align='absbottom' src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'><img align='absbottom' src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'></a>";
					$nextYear = "<a title='Next Year' href=\"javascript:void(0);\"><img align='absbottom' src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'><img align='absbottom' src='$this->SRC_PATH/includes/callendar/right_white_arrow.gif' border='0'></a>";
				}
				return array("next"=>$next,"prev"=>$prev,"nextYear"=>$nextYear,"prevYear"=>$prevYear);
				
		}
		/**
		 * Enter description here...
		 *
		 * @param unknown_type $current_date
		 * @param unknown_type $calendar_date
		 * @return unknown
		 */
		function checkInDateGreater($current_date,$calendar_date){
			
			if(strtotime($current_date)>strtotime($calendar_date)){
				return false;
			}else{
				return true;
			}
		}
		
}
?>