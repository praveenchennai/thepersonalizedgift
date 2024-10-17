<?php
/**
* Class Callender Events
* Author   : Aneesh Aravindan
* Created  : 01/Oct/2007
* Modified : 11/01/2008 By Afsal
*/
include_once(FRAMEWORK_PATH."/modules/callendar/lib/class.calendartool.php");
include_once(FRAMEWORK_PATH."/modules/event/lib/class.date.php");
class CalendarEvents EXTENDS FrameWork{
	var $MONTH;
	var $YEAR;
	var $CASEFETCH;
	var $rMonth;
	

	
	function CalendarEvents ($MONTH=0,$YEAR=0,$CASEFETCH='default')	{
		$this->FrameWork();
		$this->MONTH		=	$this->MONTH;
		$this->YEAR			=	$this->YEAR;
		$this->CASEFETCH	=	$this->CASEFETCH;
	}
	
	function GetEventYear($ST_MONTH, $ST_YEAR,$CASEFETCH,$memberid,$propid,$flyer_id) { 
		
		if($memberid > 0)
		$subqry = " AND b.current_user_id = $memberid";

	    $days = array();
		switch ($CASEFETCH) {
		
			/* album_booking :: Create CASE and build custom Functions*/
			
			case "property_booking":
							
							$lstDayF	    = $this->getLastDayOfMonth($ST_MONTH,$ST_YEAR);									
							
							/*
							Property Booking Part
							*/

							if (strlen($ST_MONTH) <2)
							$ST_MONTH = "0".$ST_MONTH;
							
							for($j=1;$j<=$lstDayF;$j++)
							{
								if($j<10)
								$day = "0".$j;
								else
								$day = $j;
							
							$cur_date_First = $ST_YEAR."-".$ST_MONTH."-".$day;
							
							//print $cur_date_First."<br>";
							
							$sql1  = "SELECT 'propertyBook' AS eventType,m.username,DAY(b.start_date) AS strDay,DAY(b.end_date) AS endDay,
									  DATEDIFF(b.end_date,b.start_date) AS nday,b.start_date,b.end_date, MONTH(b.start_date) AS strMonth,
									  MONTH(b.end_date)AS endMonth,YEAR(b.start_date) AS strYear,YEAR(b.end_date) AS endYear FROM album_booking AS b
									  INNER JOIN member_master AS m ON b.user_id = m.id WHERE '$cur_date_First' between DATE_FORMAT(start_date,'%Y-%m-%d') 
									  AND DATE_FORMAT(end_date,'%Y-%m-%d') AND  b.album_id =$propid $subqry ORDER BY start_date";
									
									$rs1   =  $this->db->get_results($sql1,ARRAY_A);
								//	print $sql1;
									/*
									Property Blocking Part Code
									*/
							
							$sql2 = "SELECT 'propertyBlock' AS eventType,m.username,DAY(b.from_date) AS strDay,DAY(b.to_date)AS endDay,
									 DATEDIFF(b.to_date,b.from_date) AS nday,b.from_date,b.to_date,MONTH(b.from_date) AS strMonth,
									 MONTH(b.to_date)AS endMonth,YEAR(b.from_date) As strYear, YEAR(b.to_date) AS endYear FROM 
									 property_blocked AS b INNER JOIN member_master AS m ON b.current_user_id = m.id
									 WHERE '$cur_date_First' between DATE_FORMAT(from_date,'%Y-%m-%d') AND 
									 DATE_FORMAT(to_date,'%Y-%m-%d') AND b.album_id = $propid $subqry  ORDER BY from_date";
							
									$rs2   =  $this->db->get_results($sql2,ARRAY_A);	
							//print $sql2;		
							$rs = "";
							if (count($rs1) >0 && count($rs2) >0)
							$rs = array_merge($rs1,$rs2);
							elseif(count($rs1) >0)
							$rs	= $rs1;
							elseif(count($rs2) >0)
							$rs	= $rs2;
							
							

					if(count($rs))   {
			
						foreach($rs as $row)	{
							
								$firstDay = 0;
								$noDay	  = 0;
								$i++;

									$days[$i] = array();
									
									list($existValArray,$existKeyArray) = $this->matchDateEventMerge($days,$ST_MONTH,$day);
										
									if(count($existValArray)){
								
										/*
										Property Booking Part
										*/
										$days[$i]['month'] = $ST_MONTH;
										$days[$i]['date'] = $day;
										$days[$i]['unavailable'] = true;
										
										if($row["eventType"] == "propertyBook"){
											$days[$i]['tooltip'] = $existValArray['tooltip']."<br>&nbsp;&nbsp;"."Booked By : <strong>" . $row['username'] . "</strong><br>&nbsp;&nbsp;"."From:".date($this->config["date_format_new"],strtotime($row['start_date']))." "."To:".date($this->config["date_format_new"],strtotime($row['end_date']))."<br>&nbsp;<a href=index.php?mod=album&pg=album&act=property_buyers&propid=$propid>Details</a><br>";
											$days[$i]['eventType'] = "propertyBook";
										}
										else
										{
											$days[$i]['tooltip'] = $existValArray['tooltip']."<br>"."Blocked By : <strong>" . $row['username'] . "</strong>";
											$days[$i]['eventType'] = "propertyBlock";
										}
										
										$days[$i]['clickCap'] = 'Booking Details';
										$currDates	=	$ST_YEAR . "-" . $ST_MONTH . "-" . $days[$i]['date'];
										
										if($row["eventType"] == "propertyBook")
										$days[$i]['onClickFun'] .=  $existValArray['onClickFun']."<br>&nbsp;&nbsp;"."Booked By : <strong>" . $row['username'] . "</strong><br><a href=index.php?mod=album&pg=property&act=property_descrip&user=$row[username]&dates=$currDates>Details</a><br>";  
										else
										$days[$i]['onClickFun'] .=  $existValArray['onClickFun']."<br>"."Blocked By :<strong>".$row['username']."</strong><br>"."Quantity : <strong>" . $row['title'] . "</strong><br>><a href=index.php?mod=flyer&pg=list&act=property_quantity&flyer_id=$flyer_id&propid=$propid>Details</a><br>";  
										
										unset($days[$existKeyArray]);
										
										//mod=flyer&pg=list&act=property_quantity&flyer_id=1&propid=1
									
									}else{
										/*
										Property Booking Part
										*/
										$days[$i]['month'] = $ST_MONTH;
										$days[$i]['date'] = $day;
										$days[$i]['unavailable'] = true;
										
										
										if($row["eventType"] == "propertyBook"){
											$days[$i]['tooltip'] = $existValArray['tooltip']."<br>&nbsp;&nbsp;"."Booked By : <strong>" . $row['username'] . "</strong><br>&nbsp;&nbsp;"."From:".date($this->config["date_format_new"],strtotime($row['start_date']))." "."To:".date($this->config["date_format_new"],strtotime($row['end_date']))."<br>&nbsp;<a href=index.php?mod=album&pg=album&act=property_buyers&propid=$propid>Details</a><br>";
											$days[$i]['eventType'] = "propertyBook";
										}
										else{
											$days[$i]['tooltip'] = $existValArray['tooltip']."<br>"."Blocked By : <strong>" . $row['username'] . "</strong>";
											$days[$i]['eventType'] = "propertyBlock";
										}
										
										$days[$i]['clickCap'] = 'Manage Info';
										$currDates	=	$ST_YEAR . "-" . $ST_MONTH . "-" . $days[$i]['date'];
										
										if($row["eventType"] == "propertyBook")
										$days[$i]['onClickFun'] .=  $existValArray['onClickFun']."<br>"."Booked By : <strong>" . $row['username'] . "</strong><br><a href=index.php?mod=album&pg=property&act=property_descrip&user=$row[username]&dates=$currDates>Details</a><br>";  
										else
										$days[$i]['onClickFun'] .=  $existValArray['onClickFun']."<br>"."Blocked By :<strong>".$row['username']."</strong><br>"."Quantity : <strong>" . $row['title'] . "</strong><br><a href=index.php?mod=flyer&pg=list&act=property_quantity&flyer_id=$flyer_id&propid=$propid>Details</a><br>";  

									}
						}	
			
							$i++;
							
						}	
					}
				
					break;
			/* Create CASE and build custom Functions*/
			
			default:
					break;
			    
		} 
	
	    return $days;
	} 
	/*
	* @Author:Afsal Ismail
	* Dated:11-01-2008
	* Get Last Day Of Month
	*/
	function getLastDayOfMonth($month,$year){

		return date('d', mktime(0, 0, 0, ($month + 1), 0, $year));
	}
	/*
	* @Author:Afsal Ismail
	* Dated:11-01-2008
	* Get the Top Navigation Tab of MyCalendar
	*/
	function addMonth($START_MONTH,$ST_MONTH){
		
		
		$START_MONTH = $START_MONTH + 1 ;
		
		
		if($START_MONTH > 12)
		$START_MONTH = 1;
		
		$num = $START_MONTH;
		
		if($START_MONTH == $ST_MONTH)
		return  $this->returnMonth($START_MONTH);
		else 
		$this->addMonth($START_MONTH,$ST_MONTH);
	}
	/*
	* @Author:Afsal Ismail
	* Dated:11-01-2008
	* Return the month name
	*/
	function returnMonth($num){
		$this->rMonth =  $num;
	}
	/*
	* @Author:Afsal Ismail
	* Dated:11-01-2008
	* Compare two dates check greater than or equal to
	*/
	function compareDates($date1,$date2) {
		
		$date1_array = explode("-",$date1);
		$date2_array = explode("-",$date2);
		
		$timestamp1  = mktime(0,0,0,$date1_array[1],$date1_array[2],$date1_array[0]);
		$timestamp2  = mktime(0,0,0,$date2_array[1],$date2_array[2],$date2_array[0]);
		
		if ($timestamp1==$timestamp2){
			
			return "EQUAL";
		}
		elseif($timestamp1 > $timestamp2)
		{
			return "LESSTHAN";
		}
		else
		{
			return "GREATER";
		}
		
	}
	/*
	* @Author:Afsal Ismail
	* Dated:14-01-2008
	* Merege events record if the date match for each events
	*/
	function matchDateEventMerge($arrayEvents,$matchMonth,$matchDay){
		
		if(count($arrayEvents)){
			//print "<BR>";
			
			foreach($arrayEvents as $key=>$evt){
				
				
				if(trim($evt["date"])== trim($matchDay) && trim($evt["month"]) == $matchMonth){
						return array($evt,$key);
				}
			}
		}
	}
	/*
	* @Author:Afsal Ismail
	* Dated:17-01-2008
	* This function check a particular property avialabe of a date
	*/
	function availabilityOfProperty($arrayPropid,$strDate,$endDate,$searchType=""){
		
		if(count($arrayPropid)){
			$retPropId = array();
			
			foreach($arrayPropid as $propid)
			{
				$sql   = "SELECT id FROM album_quantity_title WHERE album_id=$propid ORDER BY id";
				$rsQty = $this->db->get_results($sql,ARRAY_A);
				
				//print_r($rsQty);
				if(count($rsQty)){
					
						foreach ($rsQty as $qty){
							
							$Flg = $this->isExistDateInQuery($strDate,$endDate,$qty["id"],$searchType);
							
							if($Flg != "TRUE")
							break; 
							
						} //end foreach
				}
				
			if(count($retPropId) ==0)
			$arid =0;
			else 
			$arid =count($retPropId)+1;
			
				if($Flg > 0){
				$retPropId[$arid]["propid"] = $propid;	
				$retPropId[$arid]["qtyid"] = $Flg;
				$retPropId[$arid]["strDate"] = $strDate;
				$retPropId[$arid]["endDate"] = $strDate;
				$Flg ="";
				}
			}
		}
		return $retPropId;
	}
	function isExistDateInQuery($frmDate,$toDate,$qtyId,$searchType=''){
		
		if($frmDate && $toDate && $qtyId)
		{
			// PropertyBooking Checking Here
			
			$sql1 = "SELECT 'propertyBook' AS eventType,b.quantity_title_id FROM album_booking AS b
					 INNER JOIN album_quantity_title As t ON b.quantity_title_id = t.id  AND t.album_id = b.album_id 
					 
					 WHERE ((b.quantity_title_id = '$qtyId') AND
					( 
						(DATE_FORMAT(start_date,'%Y-%m-%d') between  '$frmDate' AND '$toDate')  
						OR 
						(DATE_FORMAT(end_date,'%Y-%m-%d') between '$frmDate' AND '$toDate') 
					))
					OR
					((b.quantity_title_id = '$qtyId') AND
					(
						('$frmDate' between  DATE_FORMAT(start_date,'%Y-%m-%d') AND DATE_FORMAT(end_date,'%Y-%m-%d'))  
						OR 
						('$toDate' between DATE_FORMAT(start_date,'%Y-%m-%d') AND DATE_FORMAT(end_date,'%Y-%m-%d')) 
					))
					ORDER BY start_date";
			
//print $sql1;
			$rs1  =  $this->db->get_results($sql1,ARRAY_A);
			
			// PropertyBlock Checking Here
			$sql2 = "SELECT 'propertyBlock' AS eventType,t.id FROM property_blocked AS b
					 INNER JOIN album_quantity_title As t ON b.album_quantity_title_id = t.id  AND t.album_id = b.album_id
					
					WHERE ((b.album_quantity_title_id = '$qtyId') AND
					( 
						(DATE_FORMAT(from_date,'%Y-%m-%d') between  '$frmDate' AND '$toDate')  
						OR 
						(DATE_FORMAT(to_date,'%Y-%m-%d') between '$frmDate' AND '$toDate') 
					))
					OR
					((b.album_quantity_title_id = '$qtyId') AND
					(
						('$frmDate' between  DATE_FORMAT(from_date,'%Y-%m-%d') AND DATE_FORMAT(to_date,'%Y-%m-%d'))  
						OR 
						('$toDate' between DATE_FORMAT(from_date,'%Y-%m-%d') AND DATE_FORMAT(to_date,'%Y-%m-%d')) 
					))
					ORDER BY from_date";
	
			$rs2  =  $this->db->get_results($sql2,ARRAY_A);
			
			
			if (count($rs1) >0 && count($rs2) >0)
			$rs = array_merge($rs1,$rs2);
			elseif(count($rs1) >0)
			$rs	= $rs1;
			elseif(count($rs2) >0)
			$rs	= $rs2;
			
				if($searchType == "ADVANCE_SEARCH"){
					
					if(count($rs) >0)
					return $qtyId;
					else
					return TRUE;
					
				}else{
				
					if(count($rs) >0)
					return TRUE;
					else
					return $qtyId;
				}
		}
	}
	/*
	* @Author:Afsal Ismail
	* Dated:17-01-2008
	* This function return the differenc between two date
	*/
	function get_time_difference($start, $end)
	{
		$uts['start']      =    strtotime( $start );
		$uts['end']        =    strtotime( $end );
		if( $uts['start']!==-1 && $uts['end']!==-1 )
		{
			if( $uts['end'] >= $uts['start'] )
			{
				$diff    =    $uts['end'] - $uts['start'];
				if( $days=intval((floor($diff/86400))) )
					$diff = $diff % 86400;
				if( $hours=intval((floor($diff/3600))) )
					$diff = $diff % 3600;
				if( $minutes=intval((floor($diff/60))) )
					$diff = $diff % 60;
				$diff    =    intval( $diff );            
				return(array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
			}
			else
			{
				$this->setErr("Ending date/time is earlier than the start date/time");
			}
		}
		else
		{
			$this->setErr("Invalid date/time data detected");
		}
		return( false );
	}
	function searchPropertyAvialable($frmDate,$toDate,$flexDay=1,$objSearch=""){
			
			if($flexDay > 1)
			list($qryBooking,$qryBlocking) = $objSearch->createSearchFlexibleQueries ($flexDay,$frmDate,$toDate);
		
		// PropertyBooking Checking Here
			/*
			$sql1 = "SELECT DISTINCT 'propertyBook' AS eventType,b.album_id,count(b.album_id) as albid FROM album_booking AS b
					 
					 WHERE 
					( 
						(DATE_FORMAT(start_date,'%Y-%m-%d') between  '$frmDate' AND '$toDate')  
						OR 
						(DATE_FORMAT(end_date,'%Y-%m-%d') between '$frmDate' AND '$toDate') 
					)
					OR
					
					(
						('$frmDate' between  DATE_FORMAT(start_date,'%Y-%m-%d') AND DATE_FORMAT(end_date,'%Y-%m-%d'))  
						OR 
						('$toDate' between DATE_FORMAT(start_date,'%Y-%m-%d') AND DATE_FORMAT(end_date,'%Y-%m-%d')) 
					)
					group by eventType ORDER BY start_date";
					*/
					
			$sql1 = "SELECT DISTINCT 'propertyBook' AS eventType,b.album_id,f.quantity FROM album_booking AS b
					 INNER JOIN flyer_data_basic AS f ON f.album_id = b.album_id
			
			
					 WHERE 
					(
						(DATE_FORMAT(start_date,'%Y-%m-%d') between  '$frmDate' AND '$toDate')  
						OR 
						(DATE_FORMAT(end_date,'%Y-%m-%d') between '$frmDate' AND '$toDate') 
					)
					OR
					
					(
						('$frmDate' between  DATE_FORMAT(start_date,'%Y-%m-%d') AND DATE_FORMAT(end_date,'%Y-%m-%d'))  
						OR 
						('$toDate' between DATE_FORMAT(start_date,'%Y-%m-%d') AND DATE_FORMAT(end_date,'%Y-%m-%d')) 
					)".$qryBooking.
					"HAVING quantity <= count(b.album_id)";
			
			
					/*
					$sql1 = "SELECT DISTINCT 'propertyBook' AS eventType, b.album_id
					FROM album_booking AS b
					WHERE 
					(
						(DATE_FORMAT( start_date, '%Y-%m-%d' )BETWEEN '$frmDate' AND '$toDate')
						OR 
						(DATE_FORMAT( end_date, '%Y-%m-%d' )BETWEEN '$frmDate' AND '$toDate')
					)
					ORDER BY start_date";
					*/
			

			$rs1  =  $this->db->get_results($sql1,ARRAY_A);
			
			// PropertyBlock Checking Here
			$sql2 = "SELECT DISTINCT 'propertyBlock' AS eventType,b.album_id FROM property_blocked AS b
					 
					
					WHERE 
					( 
						(DATE_FORMAT(from_date,'%Y-%m-%d') between  '$frmDate' AND '$toDate')  
						OR 
						(DATE_FORMAT(to_date,'%Y-%m-%d') between '$frmDate' AND '$toDate') 
					)
					OR
					
					(
						('$frmDate' between  DATE_FORMAT(from_date,'%Y-%m-%d') AND DATE_FORMAT(to_date,'%Y-%m-%d'))  
						OR 
						('$toDate' between DATE_FORMAT(from_date,'%Y-%m-%d') AND DATE_FORMAT(to_date,'%Y-%m-%d')) 
					)".$qryBlocking."
					group by eventType ORDER BY from_date";
	
			
			/*
			$sql2 = "SELECT DISTINCT 'propertyBlock' AS eventType, b.album_id
			FROM property_blocked AS b
			WHERE (
				(DATE_FORMAT(from_date,'%Y-%m-%d') between  '$frmDate' AND '$toDate')
				OR
				(DATE_FORMAT(to_date,'%Y-%m-%d') between '$frmDate' AND '$toDate') 
			)
			ORDER BY from_date";
			*/
			
			
			$rs2  =  $this->db->get_results($sql2,ARRAY_A);
			
			if (count($rs1) >0 && count($rs2) >0)
			$rs = array_merge($rs1,$rs2);
			elseif(count($rs1) >0)
			$rs	= $rs1;
			elseif(count($rs2) >0)
			$rs	= $rs2;
			
			$propidArray = array();
			
			
			if(count($rs)){
				
				foreach ($rs as $propid){
					$propidArray[] = $propid["album_id"];
				}
			}
			
			
			
			/*
			if(count($propidArray)){
				$this->availabilityOfProperty($propidArray,$frmDate,$toDate,"ADVANCE_SEARCH");
			}*/
			
			if(count($propidArray))
			return 	$propidArray;
			else 
			return false;
	}
	/* Created :Afsal Ismail */
	/* Date : 11-02-2008 */
	/* Features:set back ground color of calendar if booked or blocked*/
	
	function setBackGroundColor($propid,$ST_MONTH, $ST_YEAR,$SET_EVENT=true,$blockValid=false,$auction="No"){						
			
		if($propid > 0){
				
					if (strlen($ST_MONTH) <2)
					$ST_MONTH = "0".$ST_MONTH;
					
					$day = "01";
					
				
				$nextRow = false;
				$cur_date_First = $ST_YEAR."-".$ST_MONTH;
				//$cur_date_last  = $ST_YEAR."-".$ST_MONTH."-".$lstDayF;
				
				if($auction == "Yes"){
					
					$sql = "SELECT id,day(start_date)As startDay,day(rental_end_date)AS endDay,
							month(start_date) AS strMonth,month(rental_end_date) AS endMonth,
							year(start_date)AS strYear,year(rental_end_date)AS endYear,
							start_date,rental_end_date,color_code,'propertyReserve' AS eventType FROM property_pricing WHERE album_id = $propid 
							AND '$cur_date_First' between DATE_FORMAT(start_date,'%Y-%m') AND
							DATE_FORMAT(rental_end_date,'%Y-%m') UNION 
							
							SELECT id,day(from_date)As startDay,day(to_date)AS endDay,
							month(from_date)AS strMonth,month(to_date) AS endMonth,
							year(from_date)AS strYear,year(to_date)AS endYear,
							from_date,to_date,color_code,'propertyBlock' AS eventType FROM property_blocked WHERE album_id = $propid 
							AND '$cur_date_First' between DATE_FORMAT(from_date,'%Y-%m') AND
							DATE_FORMAT(to_date,'%Y-%m')UNION
					
							SELECT id,day(start_date)As startDay,day(rental_end_date)AS endDay,
							month(start_date) AS strMonth,month(rental_end_date) AS endMonth,
							year(start_date)AS strYear,year(rental_end_date)AS endYear,
							start_date,rental_end_date,color_code,'auction' AS eventType FROM property_pricing WHERE album_id = $propid AND auction='Y'
							AND '$cur_date_First' between DATE_FORMAT(start_date,'%Y-%m') AND
							DATE_FORMAT(rental_end_date,'%Y-%m') order by eventType";
					
			  }else{
			  	
			  		$sql = "SELECT id,day(start_date)As startDay,day(rental_end_date)AS endDay,
							month(start_date) AS strMonth,month(rental_end_date) AS endMonth,
							year(start_date)AS strYear,year(rental_end_date)AS endYear,
							start_date,rental_end_date,color_code,'propertyReserve' AS eventType FROM property_pricing WHERE album_id = $propid 
							AND '$cur_date_First' between DATE_FORMAT(start_date,'%Y-%m') AND
							DATE_FORMAT(rental_end_date,'%Y-%m') UNION 
							
							SELECT id,day(from_date)As startDay,day(to_date)AS endDay,
							month(from_date)AS strMonth,month(to_date) AS endMonth,
							year(from_date)AS strYear,year(to_date)AS endYear,
							from_date,to_date,color_code,'propertyBlock' AS eventType FROM property_blocked WHERE album_id = $propid 
							AND '$cur_date_First' between DATE_FORMAT(from_date,'%Y-%m') AND
							DATE_FORMAT(to_date,'%Y-%m')";
			  		
			  }
					
			
					$rs   =  $this->db->get_results($sql,ARRAY_A);	
				
						if(count($rs)){
							$j=0;
							$k=0;
							foreach ($rs as $row){
								
								$nextRow = false;
								
								if($auction="Yes"){
									
									if($row["eventType"] == "propertyReserve"){
										
										if($this->chekcSameAuctionAndPropertyReserve($propid,$row["id"]))
										$nextRow = true;
										
									}
								}
								
								if($nextRow == false){
									
								
									$j++;
									$days[$j] = array();
									$record[$k] = array();
									
									$strDay = $row["startDay"];
									//$endDay = $row["endDay"];
									
									
									if($row["endMonth"] == $ST_MONTH && $row["endYear"] == $ST_YEAR){
										$endDay = $row["endDay"];
								
									}else {
										
										$endDay	    = $this->getLastDayOfMonth($ST_MONTH,$ST_YEAR);
									}
									
									if($ST_MONTH != $row["strMonth"] || $row["strYear"] != $ST_YEAR)
									$strDay = 1;
					
									if($strDay > $endDay)
									$strDay = 1;
									
									$record[$k]["start_day"] = $strDay;
									$record[$k]["end_day"]   = $endDay;
									$record[$k]["color"] 	 = $row["color_code"];
									
									
									$k++;
									 
									if($blockValid == true){
											/* This part is chekc the each block in calendar and user cannot select their own dates */
											if($row["eventType"] == "propertyReserve"){
													
												 $row_price = $this->getPropertyPriceInformation($row["id"]);
												 $startDate = $ST_YEAR."-".$ST_MONTH."-".$strDay;
												 $endDate = $ST_YEAR."-".$ST_MONTH."-".$endDay;
												 $block_day = $this->fixedBlocksArray($startDate,$endDate,$row_price["unit"],$row_price["duration"]);
											}
									}
											
									for($i=$strDay;$i<=$endDay;$i++){
										
										$days[$j]["day"] = $i;
										$days[$j]["color"]=$row["color_code"];
										$days[$j]["id"]=$row["id"];
										$days[$j]["type"]=$row["eventType"];
										
										if($row["eventType"] == "propertyReserve"){
											
											if (in_array($i,$block_day[0]))
											$days[$j]["start_day"]=$i;
											
											if (in_array($i,$block_day[1]))
											$days[$j]["end_day"]=$i;
										}
										
										
										$j++;
									}// end for
								}// end if nextRow
									
							}// end foreach
							
							if($SET_EVENT == true)// setBackground Color of Calendar
							return $days;
							else 
							return $record;
						}
						
				
						
			}//end if 
	}
	/* Created :Afsal Ismail */
	/* Date : 11-02-2008 */
	/* Features:set Get the event BackColor day*/
	function getEventBackColor($days,$d){
	
		
		if(count($days) && $d >0){
		
			foreach ($days as $drow){
				if($drow["day"] == $d){
					if($drow["day"]== 21){
						//print_r($days);
						//print $drow["type"];
					}
					return array($drow["color"],$drow["id"],$drow["type"],$drow["start_day"],$drow["end_day"]);
					break;
				}
			}//end foreach
		}//end if
		
	}//end function
	/* Created :Afsal Ismail */
	/* Created Date : 25-02-2008 */
	/* Modified Date : 25-02-2008 */
	/* Features:set Fill start date end date and color*/
	function fillDragCalendarJavascriptArray($propid,$datesM,$datesY){
		
		
		$k =0;
		$rs = $this->setBackGroundColor($propid,$datesM,$datesY,false);	
		
			if(count($rs)){
					
					foreach ($rs as $row) {
					/*
						$strDate[$k] 	   = $row["startDay"];
						$rentalEndDate[$k] = $row["endDay"];
						$selColor[$k]	   = $row["color_code"];
						$k++;
						*/
						$strDate[$k] 	   = $row["start_day"];
						$rentalEndDate[$k] = $row["end_day"];
						$selColor[$k]	   = $row["color"];
						$k++;
					}
				
				$stDate  =  implode(",",$strDate);
				$endDate =	implode(",",$rentalEndDate);
				$selColor = implode(",",$selColor);
				///$selColor =	$this->getAllColor($propid);
				
				return array($stDate,$endDate,$selColor);
				
			}
	}
	
	function monthDifference($strDate,$endDate){
		
		$date1 = $strDate;
		$date2 = $endDate;
		
		$date1array = explode("-",$date1);
		$date2array = explode("-",$date2);
		
		// Assume Date 2 is later than Date 1
		
		$months_apart = ($date2array[0] - $date1array[0])*12;
		
		// Month is later in date 1 than in date 2
		// Find the difference of months
		if( $date1array[1] > $date2array[1] )
		{
			$months_apart -= $date1array[1] - $date2array[1];
		}
		else
		{
			$months_apart += $date2array[1] - $date1array[1];
		}
			return $months_apart;
	}
	
	function checkBlockDatesDetails  ($startDt="",$endDt="",$unit="",$duration="",$outMsg=0,$album_id="",$color_code="",$id="",$type=""){

		$UNIT 		= strtolower($unit);
		$DURATION 	= intval($duration);
		
		
		/* Chekc the past date */
		if($outMsg == 0){
		
				$val = $this->pastDateCheckValidation($startDt);
				
				if($val == "LESSTHAN" || $val == "EQUAL")
				return "Past or Current dates are not allowed for  fixed rates";
		
		}
		
		/* Check if Start Date Less than Or Equal to Start Date */
		if ( strtotime($startDt) > strtotime($endDt) )
		return "Rental end Date should be greater than start date";
		
		
		if($outMsg == 0){
			if($this->validateBlockedAndFixedRateBooking(date("Y-m-d",strtotime($startDt)),date("Y-m-d",strtotime($endDt)),$id,$type,$album_id) == "false")
			return "Please check the date range . Property might be set as Fixed rate or blocked";
		}
		
		
		/* Check Duration */
		if ( $DURATION < 1 )
		return false;

		
		/* Check if same color used in this Album_id , if yes, replace UNIT,DURATION with old one */
		if ($outMsg == 0 && trim($album_id) && trim($color_code) ) {
			$SQL = "SELECT * FROM property_pricing WHERE album_id ='{$album_id}' AND default_val = 'N' AND color_code = '{$color_code}' ORDER BY id LIMIT 1";
			$rs = $this->db->get_row($sql,ARRAY_A);
			
			if($rs) {
				$UNIT = strtolower($rs['unit']);
				$DURATION = intval($rs['duration']);
			}
		}
		
		 
		
	    if($UNIT == "week") {
		   $DURATION = $DURATION* 7;           /* 1 Week == 7 days */
	    }
		
	    if($UNIT == "month") {
		   $DURATION = $DURATION* 28;		   /* 1 Month == 4 Week == 28 days */
	    }
		    
	    if($UNIT == "year") {
		   $DURATION = $DURATION* 336;		   /* 1 Year  == 12 Month ==  48 Week == 336 days */
	    }
		
		
	    if ($outMsg == 1) {
	    	$arrOut = array();
   	
	    	$startDt_Sec = strtotime($startDt);
	    	$i = strtotime($startDt);
	    	
	    	while($i<=strtotime($endDt)) {
	    		$startDt_Sec =  date("j, F  Y",$i);
	    		
	    		$endDt_Tm    =  $i+ ( ($DURATION-1)*86400);
	    		
	    		$endDt_Sec   =  date("j, F  Y",$endDt_Tm);
	    		
				$arrOut[] = $startDt_Sec . " to " . $endDt_Sec;
	    		
	    		$i = $i+ ($DURATION*86400); 		
	    	
	       	}
	       	
	    	return $arrOut;
	    	
	    }else{
			/* DIFFERENCE OF SELECTED DATS*/
			$DIFF   	= strtotime($endDt) - strtotime($startDt);
			$DIFF_SEL	= intval((floor($DIFF/86400)))+1;
			/* */
			$REMINDER   = $DIFF_SEL%$DURATION;
			$MULTIPLYER = intval($DIFF_SEL/$DURATION);
	
			if ($MULTIPLYER<1)	{
				$MINDATE	= $DURATION-1;
				$SAMPLE_MIN_DATE	= date("Y-m-d",strtotime("$startDt +$MINDATE day"));
				return "Please Select : ". date("j, F  Y",strtotime($startDt)) ." to ".date("j, F  Y",strtotime($SAMPLE_MIN_DATE) );
			}
			
			if($REMINDER>0){
				$MINDATE	=	($DURATION * $MULTIPLYER) -1;
				$SAMPLE_MIN_DATE	= date("Y-m-d",strtotime("$startDt +$MINDATE day"));
				$MAXDATE	=	$DURATION;
				$SAMPLE_MAX_DATE	= date("Y-m-d",strtotime("$SAMPLE_MIN_DATE +$MAXDATE day"));
				return "Please Select : ". date("j, F  Y",strtotime($startDt) ) ." to ". date("j, F  Y",strtotime($SAMPLE_MIN_DATE) ) ." OR  ". date("j, F  Y",strtotime($startDt) ) ." to ". date("j, F  Y",strtotime($SAMPLE_MAX_DATE) );
			}else{
				return true;
			}
	    }
	}
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $frmDate
	 * @param unknown_type $toDate
	 * @param unknown_type $id
	 * @param unknown_type $typ
	 * @param unknown_type $propid
	 * @return unknown
	 */
	function validateBlockedAndFixedRateBooking($frmDate,$toDate,$id=0,$typ="",$propid){
			
		if($id > 0 && trim($typ) == "booked"){
			/**
			 * STEP3 Calendar validation
			 */
			
			$SQL = "SELECT id FROM property_pricing 
					 
					 WHERE ((id <> '$id' AND album_id = $propid) AND
					( 
						(start_date between  '$frmDate' AND '$toDate')  
						OR 
						(rental_end_date between '$frmDate' AND '$toDate') 
					))
					OR
					((id <> '$id' AND album_id = $propid) AND
					(
						('$frmDate' between  start_date AND rental_end_date)  
						OR 
						('$toDate' between start_date AND rental_end_date) 
					))
					UNION
					
					 SELECT id  FROM property_blocked
					
					 WHERE ((album_id = $propid) AND
					( 
						(DATE_FORMAT(from_date,'%Y-%m-%d') between  '$frmDate' AND '$toDate')  
						OR 
						(DATE_FORMAT(to_date,'%Y-%m-%d') between '$frmDate' AND '$toDate') 
					 ))
					 OR
					 ((album_id = $propid) AND
					 (
						('$frmDate' between  DATE_FORMAT(from_date,'%Y-%m-%d') AND DATE_FORMAT(to_date,'%Y-%m-%d')) 
						OR 
						('$toDate' between DATE_FORMAT(from_date,'%Y-%m-%d') AND DATE_FORMAT(to_date,'%Y-%m-%d')) 
					 ))";

		}else{
					$SQL = "SELECT id FROM property_pricing AS b
					 
					 WHERE ((album_id = $propid) AND
					( 
						(start_date between  '$frmDate' AND '$toDate')  
						OR 
						(rental_end_date between '$frmDate' AND '$toDate') 
					))
					OR
					((album_id = $propid) AND
					(
						('$frmDate' between  start_date AND rental_end_date)  
						OR 
						('$toDate' between start_date AND rental_end_date) 
					))
					UNION
					
					 SELECT id  FROM property_blocked AS b
					
					 WHERE ((album_id = $propid) AND
					 ( 
						(DATE_FORMAT(from_date,'%Y-%m-%d') between  '$frmDate' AND '$toDate')  
						OR 
						(DATE_FORMAT(to_date,'%Y-%m-%d') between '$frmDate' AND '$toDate') 
					 ))
					 OR
					 ((album_id = $propid) AND
			
					 (
						('$frmDate' between  DATE_FORMAT(from_date,'%Y-%m-%d') AND DATE_FORMAT(to_date,'%Y-%m-%d'))  
						OR 
						('$toDate' between DATE_FORMAT(from_date,'%Y-%m-%d') AND DATE_FORMAT(to_date,'%Y-%m-%d')) 
					 ))";
			
		}
		
			$rs  =  $this->db->get_results($SQL,ARRAY_A);
			
			if(count($rs) > 0)
			return "false";
			else 
			return "true";
	}
	function printCalendar($CALENDAR_NAVIG,$calendarList,$print=""){
		
		if($print == "MONTH"){
			
			$strHtm = '<div style="width:93%;float:left;" class="mainbody">';
			$strHtm .= '<div style="height:10px;clear:both;"><!-- --></div>';
			$strHtm .= $CALENDAR_NAVIG;
			$strHtm .= '<div id="tabcontent">';
			$strHtm .= '<div align="center" id="monthView">'.$calendarList.'</div>';
			$strHtm .= '</div>';
			$strHtm .= '</div>';
				
		}elseif($print == "YEAR"){
			
			$strHtm = '<div style="width:93%;float:left;" class="mainbody">';
			$strHtm .= '<div style="height:10px;clear:both;"><!-- --></div>';
			$strHtm .= $CALENDAR_NAVIG;
			$strHtm .= '<div id="tabcontent">';
			$strHtm .= '<div align="center">';
			$strHtm .= '<div id="yearView" style="width:100%;">';
			$strHtm .= $calendarList;
			$strHtm .= '</div>';
			$strHtm .= '<div class="divSpc"></div>';
			$strHtm .= '</div>';
			$strHtm .= '</div>';
			$strHtm .= '</div>';
			
		}
		return $strHtm;		
	}

	function printCalendarValues($request,$objCalendar,$memebrid,$printVal="",$FlyerBasicData_title=""){
		
			
		//$datesY  	= isset($request['year']) ? $request['year'] : date('Y');
		//$datesM 	= isset($request['month']) ? $request['month'] : date('m'); 
		//$datesD   	= isset($request['day']) ? $request['day'] : date('d'); 
		
		
		if($printVal == "MONTH"){
			
			$datesY  	= isset($request['year']) ? $request['year'] : date('Y');
			$datesM 	= isset($request['month']) ? $request['month'] : date('m'); 
			$datesD   	= isset($request['day']) ? $request['day'] : date('d'); 
			
			$request['type']= "search";
			$request['action_from']= "search";
			$request['act']= "mycalendar_month";
			
			$array = array('FCase'=>'property_booking','DISPLAY' => 'block','CUSTOM_FUNCTION' => 'dispMonthView',"memberid" =>$memebrid,"propid" => $request['propid'],"flyer_id" => $request['flyer_id']);
			$CALENDARLIST = $objCalendar->DrawCalendarMonth($datesY,$datesM,$datesD,'calendarList',$array);
			
			if(trim($request['type'])!= "" && trim($request['propid'])!= "")
			$CALENDAR_NAVIG = $objCalendar->calenDarNavig($request['act'],$request['type'],$request['propid'],$FlyerBasicData_title,$request['action_from']);
			else 
			$CALENDAR_NAVIG = $objCalendar->calenDarNavig($request['act']);
			
		}elseif($printVal == "YEAR"){
			
			if ($_REQUEST['dates'] ) {
				
				$next_month_array = explode("-",$_REQUEST['dates']);
				
				$datesY = $next_month_array[0]; 
				$datesM = $next_month_array[1];
				$datesD = $next_month_array[2]; 
				
			} else {
				
				$datesY  	= isset($request['year']) ? $request['year'] : date('Y');
				$datesM 	= isset($request['month']) ? $request['month'] : "01"; 
				$datesD   	= isset($request['day']) ? $request['day'] : "01";
				
			}
			
			
			$request['type']= "search";
			$request['action_from']= "search";
			$request['act']= "mycalendar_year";
			
			$array = array('FCase'=>'property_booking','DISPLAY' => 'block','CUSTOM_FUNCTION' => 'dispYearView','CLOSE_BUTTON' => 'no',"memberid" =>$memebrid,"propid" => $request['propid'],"flyer_id" => $request['flyer_id']);
			$CALENDARLIST = $objCalendar->DrawCalendarYear($datesY,$datesM,$datesD,'calendarList',$array);
		
			if(trim($request['type'])!= "" && trim($request['propid'])!= "")
				$CALENDAR_NAVIG = $objCalendar->calenDarNavig($request['act'],$request['type'],$request['propid'],$FlyerBasicData_title,$request['action_from']);
			else 
				$CALENDAR_NAVIG = $objCalendar->calenDarNavig($request['act']);
			}
		
			return array($CALENDARLIST,$CALENDAR_NAVIG);
		}
		function pastDateCheckValidation($date){
		
			return $this->compareDates(date("Y-m-d"),$date);
			
		}
		/**
		 * Enter description here...
		 *
		 * @param unknown_type $startDt
		 * @param unknown_type $endDt
		 * @param unknown_type $UNIT
		 * @param unknown_type $DURATION
		 * @return arry
		 * Created:Afsal
		 * Date:03/Apr/2008
		 * Modified:03/Apr/2008
		 */
		function fixedBlocksArray($startDt,$endDt,$UNIT,$DURATION,$type=1){
		
			if($UNIT == "week") {
			   $DURATION = $DURATION* 7;           /* 1 Week == 7 days */
		    }
			
		    if($UNIT == "month") {
			   $DURATION = $DURATION* 28;		   /* 1 Month == 4 Week == 28 days */
		    }
			    
		    if($UNIT == "year") {
			   $DURATION = $DURATION* 336;		   /* 1 Year  == 12 Month ==  48 Week == 336 days */
		    }
			
		    	$arrStart = array();
		    	$arrEnd = array();
	   	
		    	$startDt_Sec = strtotime($startDt);
		    	$i = strtotime($startDt);
		    	$j=0;
		    	while($i<=strtotime($endDt)) {
			    	
		    		if($type == 1){
		    			

			    		$startDt_Sec =  date("d",$i);
			 
			    		$endDt_Tm    =  $i+ ( ($DURATION-1)*86400);
			    		
			    		$endDt_Sec   =  date("d",$endDt_Tm);
			    		
						$arrStart[] = $startDt_Sec;
						$arrEnd[] 	= 	$endDt_Sec;
			    		
		    		}else{
		    			
		    			$startDt_Sec =  $i;
			 
			    		$endDt_Tm    =  $i+ ( ($DURATION-1)*86400);
			    		
			    		$endDt_Sec   =  $endDt_Tm;
			    		
						$arrStart[] = $startDt_Sec;
						$arrEnd[] 	= 	$endDt_Sec;
		    			
		    		}
		    		
		    		$i = $i+ ($DURATION*86400); 
		    		$j++;	
		       	}
		       	
		    	return array($arrStart,$arrEnd);
	}
	/**
	 * Get the price information of property
	 * 
	 *
	 */
	function getPropertyPriceInformation($id){
		
		if($id >0){
			
			$SQL = "SELECT * FROM  property_pricing WHERE id=$id";
			$rs  = $this->db->get_row($SQL,ARRAY_A);
			return $rs;
		}
		
	}
	/**
	 * Check auction and property reserve date shown same dates 
	 *
	 */
	function chekcSameAuctionAndPropertyReserve($propid,$fin_id){
		
		if($propid && $fin_id >0){
			
			$SQL ="SELECT id FROM property_pricing WHERE album_id=$propid AND auction <> 'Y'";
			$rsAuct = $this->db->get_results($SQL,ARRAY_A);
		
			if(count($rsAuct) > 0){
				if(array_search($fin_id,$rsAuct)){
					
					return true;
				}else{
					return false;
				}
			}else{
					return false;
			}
			
		}
	}
	/**
	 * Return the start date of block
	 *Created:Afsal
	 * Date:30/Apr/2008
	 * Modified Date:30/Apr/2008
	 * @param  $startDate
	 * @param  $propid
	 * @return 
	 */
	function startBlockDateCalculation($startDate,$propid){
		
		$bStartDate = strtotime($startDate);
		
		if($propid > 0){
			
		
			$SQL = "SELECT id,start_date,rental_end_date,unit,duration FROM property_pricing 
			        WHERE '$startDate' between  DATE_FORMAT(start_date,'%Y-%m-%d') AND
					DATE_FORMAT(rental_end_date,'%Y-%m-%d') AND album_id =$propid";
				
			$row = $this->db->get_row($SQL,ARRAY_A);
			
			
			if(count($row)){
				
				list($startDate,$endDate) = $this->fixedBlocksArray($row["start_date"],$row["rental_end_date"],$row["unit"],$row["duration"],0);
				
				if(count($startDate) && count($endDate)){
				
					for($i=0;i<count($startDate);$i++){
						
						if($bStartDate >= $startDate[$i] && $bStartDate <= $endDate[$i]){
							return date("Y-m-d",$startDate[$i]);
						}
					}
				}
				
			}else{
				return $startDate;
			}
			
			
		}
		
	}
	/**
	 * Check the property all quantity booked on the same date
	 * @param  $propid
	 * @param  $date
	 * @return true,false
	 * Created:Afsal
	 * Date:06/May/2008
	 * Modified:06/May/2008
	 */
	
	function blockPropertyBookedDate($propid,$date){
		
		if($propid > 0 && trim($date) != ""){
		
			$SQL = "SELECT DISTINCT b.album_id,'propertyBooked' AS eventType,f.quantity,count(b.album_id)
					FROM album_booking AS b	INNER JOIN flyer_data_basic AS f ON f.album_id = b.album_id
					WHERE b.album_id = $propid AND '$date' between DATE_FORMAT(b.start_date,'%Y-%m-%d')
					AND DATE_FORMAT(b.end_date,'%Y-%m-%d')GROUP BY b.album_id HAVING f.quantity <= count(b.album_id)";

			$row = $this->db->get_row($SQL,ARRAY_A);

			if(count($row)){
				return true;
			}else{
				return false;
			}
			
		}
	}
}
?>
