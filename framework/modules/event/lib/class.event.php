<?
/*
Authore Afsal Ismail
Event Module
*/
class Event extends FrameWork {
	
	function Event(){
	
		$this->FrameWork();
	}
	function timeArray(){
	
		$timearray = array(array("01" => "01",
		                "02" => "02",
						"03" => "03",
						"04" => "04",
						"05" => "05",
						"06" => "06",
						"07" => "07",
						"08" => "08",
						"09" => "09",
						"10" => "10",
						"11" => "11",
						"12" => "12"),
						array("00" => "00", "01" => "01", "02" => "02",
						"03" => "03", "04" => "04","05" => "05",
						"06" => "06", "07" => "07","08" => "08",
						"09" => "09","10" => "10", "11" => "11",
						"12" => "12","13" => "13","14" => "14",
						"15" => "15","16" => "16","17" => "17",
						"18" => "18","19" => "19","20" => "20",
						"21" => "21","22" => "22","23" => "23",
						"24" => "24","25" => "25","26" => "26",
						"27" => "27","28" => "28","29" => "29",
						"30" => "30",
						"31" => "31","32" => "32","33" => "33",
						"34" => "34","35" => "35","36" => "36",
						"37" => "37","38" => "38","39" => "39",
						"40" => "40","41" => "41","42" => "42",
						"43" => "43","44" => "44","45" => "45",
						"46" => "46","47" => "47","48" => "48",
						"49" => "49","50" => "50","51" => "51",
						"52" => "52","53" => "53","54" => "54",
						"55" => "55","56" => "56","57" => "57",
						"58" => "58","59" => "59"),array("AM" => "AM","PM" => "PM"));
						
						return $timearray;

	}
	function eventCategory($type)
	{
		 $rs = $this->db->get_results("SELECT cat_id,cat_name FROM  event_category  WHERE type ='$type' ORDER BY cat_id", ARRAY_A);
			foreach($rs as $val){
				$asscArray[$val['cat_id']] = $val['cat_name'];
			}
			
    	return $asscArray;
	}
	function insertEvent(){
		
		if(isset($_REQUEST['public_cat']))
			$event_cat_id = $_REQUEST['public_cat'] ;
		elseif(isset($_REQUEST['private_cat']))
			$event_cat_id = $_REQUEST['private_cat'] ;
		/*
		Insert value to event_master table
		*/
		$array = array("event_name" => $_REQUEST['event_name'],"event_organizer" => $_REQUEST['event_organizer'],
					   "event_email" => $_REQUEST['event_email'],"event_cat_id" => $event_cat_id,
					   "event_age_limit" => $_REQUEST['event_age_limit'],"event_crt_date" => cDate(),"mem_id"=> $_SESSION['memberid']);
					  
		$this->db->insert("event_master",$array);
		$event_id = mysql_insert_id();
		
		/*
		Insert value to event_description table
		*/
		$array = array("event_id" => $event_id,"event_sh_descr" => $_REQUEST['event_sh_descr'],
		         "event_long_descr" => $_REQUEST['event_long_descr']);				 
		$this->db->insert("event_description",$array); 
		
		/*
		Insert value to event_venue table
		*/
		if($_REQUEST['ampm'] == "AM" && $_REQUEST['hour'] == 12)
		$hour = "00";
		else if($_REQUEST['ampm'] == "PM" && $_REQUEST['hour'] != 12)
		$hour = $_REQUEST['hour'] + 12;
		else
		$hour = $_REQUEST['hour'];
		
		$time = $hour.":".$_REQUEST['minute'].":"."00";
		
		
		$array = array("event_id" => $event_id,"event_date" => $_REQUEST['event_date'],"event_time" => $time,
		               "event_place" => $_REQUEST['event_place'],"event_address" => $_REQUEST['event_address'],
					   "event_city" => $_REQUEST['event_city'],"event_state" => $_REQUEST['event_state'],
				       "event_country" => $_REQUEST['country'],"event_zip" => $_REQUEST['event_zip']);
				 
		$this->db->insert("event_venue",$array);  
		
		/*
		Insert value to event_invite_accept
		*/
		$array = array("mem_id" => $_SESSION['memberid'],"event_id" => $event_id,"event_rsvp" => "yes");
		$this->db->insert("event_invite_accept",$array);  
		
		return $event_id;
		
	}
	function updateEvent($eventid)
	{
		if($_REQUEST['eventType'] == "publiCate")
			$event_cat_id = $_REQUEST['public_cat'] ;
		elseif($_REQUEST['eventType'] == "privatCate")
			$event_cat_id = $_REQUEST['private_cat'] ;
		/*
		update value to event_master table
		*/
		$array = array("event_name" => $_REQUEST['event_name'],"event_organizer" => $_REQUEST['event_organizer'],
					   "event_email" => $_REQUEST['event_email'],"event_cat_id" => $event_cat_id,
					   "event_age_limit" => $_REQUEST['event_age_limit'],"event_crt_date" => cDate());
					  
		$this->db->update("event_master",$array,"event_id =".$eventid);
		
		/*
		update value to event_description table
		*/
		$array = array("event_sh_descr" => $_REQUEST['event_sh_descr'],
		         "event_long_descr" => $_REQUEST['event_long_descr']);
				 
		$this->db->update("event_description",$array,"event_id =$eventid"); 
		
		/*
		update value to event_venue table
		*/
		if($_REQUEST['ampm'] == "AM" && $_REQUEST['hour'] == 12)
		$hour = "00";
		else if($_REQUEST['ampm'] == "PM" && $_REQUEST['hour'] != 12)
		$hour = $_REQUEST['hour'] + 12;
		else
		$hour = $_REQUEST['hour'];
		
		$time = $hour.":".$_REQUEST['minute'].":"."00";
		
		
		$array = array("event_date" => $_REQUEST['event_date'],"event_time" => $time,
		               "event_place" => $_REQUEST['event_place'],"event_address" => $_REQUEST['event_address'],
					   "event_city" => $_REQUEST['event_city'],"event_state" => $_REQUEST['event_state'],
				       "event_country" => $_REQUEST['country'],"event_zip" => $_REQUEST['event_zip']);
				 
		$this->db->update("event_venue",$array,"event_id=".$eventid);  
		
	}
	 function listCountry()
    {
        $sql = "Select country_id, country_name, country_2_code from country_master";
        $rs['country_id'] = $this->db->get_col($sql, 0);
        $rs['country_name'] = $this->db->get_col($sql, 1);
        $rs['country_2_code'] = $this->db->get_col($sql, 2);
        return $rs;
    }
	function eventPosted($page,$limit = 10,$params,$qry='',$orderBy='em.event_id DESC',$search='')
	{
		if($search)
		{
			$qry .= " AND em.event_name LIKE '$search%'";
		}
		$sql = "SELECT em.event_id,em.event_organizer, em.event_name, ev.event_date, ev.event_time, ev.event_place,em.active,
               CASE ea.event_rsvp WHEN 'nil' THEN 'N/A' WHEN 'yes' THEN 'Attending' WHEN 'no' THEN 'Not Attending'
			   WHEN 'myb' THEN 'Maybe Attending' END AS rsvp,me.id,me.username,ca.cat_name
               FROM event_master AS em INNER JOIN event_venue AS ev INNER JOIN event_description AS ed
               INNER JOIN event_invite_accept AS ea INNER JOIN member_master AS me INNER JOIN event_category AS ca
			   ON em.event_id = ev.event_id AND em.event_id = ed.event_id
               AND  em.event_id = ea.event_id AND me.id = em.mem_id AND em.event_cat_id = ca.cat_id WHERE 1 $qry";
		
		 $result = $this->db->get_results_pagewise($sql, $page, $limit, $params, $output=OBJECT, $orderBy) ;
		 return $result;
	}
	function eventListing($page,$limit = 10,$params,$qry='',$orderBy='em.event_id DESC',$search='',$public_cat)
	{
	
		if($search)
		{
			$qry .= " AND em.event_name LIKE '$search%'";
		}
		$sql = "SELECT em.event_id,em.event_organizer, em.event_name, ev.event_date, ev.event_time, ev.event_place,em.active,
                me.id,me.username,ca.cat_name
                FROM event_master AS em INNER JOIN event_venue AS ev INNER JOIN event_description AS ed
                INNER JOIN member_master AS me INNER JOIN event_category AS ca
			    ON em.event_id = ev.event_id AND em.event_id = ed.event_id
                AND me.id = em.mem_id AND em.event_cat_id = ca.cat_id WHERE 1 AND ca.cat_id='$public_cat' AND em.active='Y' AND em.type_e = 'ev' $qry";

		 $result = $this->db->get_results_pagewise($sql, $page, $limit, $params, $output=OBJECT, $orderBy) ;
		 return $result;
	}
	function eventDetails($eventid)
	{
		if($eventid)
		{
		$sql = "SELECT em.event_id,em.event_organizer, em.event_name,em.event_email, ev.event_date, ev.event_time, ev.event_place,
				em.photo_up,em.event_photo,ev.event_address,ev.event_city,ev.event_state,ev.event_zip,ed.event_sh_descr,ed.event_long_descr ,
                CASE ea.event_rsvp WHEN 'nil' THEN 'N/A' WHEN 'yes' THEN 'Attending' WHEN 'no' THEN 'Not Attending'
                WHEN 'myb' THEN 'Maybe Attending' END AS rsvp,co.country_name,ev.event_country As countrycode,em.event_cat_id,ca.type
                FROM event_master AS em INNER JOIN event_venue AS ev INNER JOIN event_description AS ed
                INNER JOIN event_invite_accept AS ea INNER JOIN event_category AS ca INNER JOIN country_master AS co
                ON em.event_id = ev.event_id AND em.event_id = ed.event_id
                AND  em.event_id = ea.event_id AND em.event_cat_id = ca.cat_id AND ev.event_country = co.country_id WHERE em.event_id = $eventid";
		$result = $this->db->get_row($sql);
		return $result;
		}

	}
	function eventInvite($eventid)
	{
		if($eventid)
		{
			$sql = "SELECT ed.event_guest_listview, ed.event_allow_comment,ed.event_rsvp,ed.event_invite_msg,ed.event_no_guest 
			        FROM event_description AS ed WHERE ed.event_id=$eventid";
			$result = $this->db->get_row($sql,ARRAY_A);
			return $result;
		}
	}
	/*
		search members
	*/
	function searchResults($value,$params,$page)
	{
		if(!empty($value))
		{
			$sql = "SELECT m.id,m.first_name,DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS(m.dob ) ) , '%Y' ) +0 As age,
					c.country_name AS country,m.state,m.city FROM member_master AS m INNER JOIN member_address AS ma
                    ON  m.id = ma.user_id AND ma.addr_type = 'master' INNER JOIN country_master AS c ON ma.country = c.country_id
                    AND (m.first_name Like '$value%' OR m.last_name Like '$value%' 
                    OR m.email Like '$value%')";

			$result = $this->db->get_results_pagewise($sql, $page, $limit = 10, $params, $output=ARRAY_A, $orderBy='') ;
			return $result;
		}
	}
	/*
	Invite people for events
	*/
	function invitePeople()
	{
		$memberid = explode(",",$_REQUEST['invite_friend']);

		for($i=0;$i<sizeof($memberid);$i++)
		{
			if($memberid[$i] > 0)
			{
				$array = array("event_id" => $_REQUEST["eventid"],"from_mem_id" => "60" ,"to_mem_id" => $memberid[$i],
							   "event_invite_date" => cDate(),"event_invite_msg" => $_REQUEST["event_invite_msg"]);
				$this->db->insert("event_invite_frd",$array);
			}
		}
			$array = array("event_invite_msg" => $_REQUEST['event_invite_msg'],"event_no_guest" => $_REQUEST['event_no_guest']);
			
			$this->db->update("event_description",$array,"event_id=".$_REQUEST["eventid"]);
		
	}
	function eventInternalMails($memberid)
	{
		if($memberid)
		{
			$sql = "SELECT mem.first_name,evt.event_id,evt.event_name,inv.event_invite_date,inv.event_invite_msg
			        FROM member_master AS mem,event_master AS evt,event_invite_frd AS inv WHERE
					mem.id = inv.from_mem_id AND inv.event_id = evt.event_id AND inv.to_mem_id=$memberid ORDER BY inv.event_invite_date DESC";
					
			$rs = $this->db->get_results_pagewise($sql, $page, $limit = 10, $params, $output=ARRAY_A, $orderBy='');
			return $rs;
		}
	}
	function eventInternalMailsDetails()
	{
			$sql = "SELECT mem.first_name,evt.event_name,inv.event_invite_date,inv.event_invite_msg
			        FROM member_master AS mem,event_master AS evt,event_invite_frd AS inv WHERE
					mem.id = inv.from_mem_id AND inv.event_id = evt.event_id";
			$rs = $this->db->get_row($sql,ARRAY_A);
			return $rs;
	}
	function eventGuest($eventid)
	{
		if($eventid)
		{
			$sql = "SELECT  event_no_guest FROM event_description WHERE event_id = $eventid";
			$rs = $this->db->get_row($sql,ARRAY_A);
			
				for($i=0;$i<=$rs["event_no_guest"];$i++)
				{
					$array[$i] = "+ ".$i." Guests";
				}
				if(count($array)==0)
				{
					$array[$i] = "+ "."0 Guests";
				}
				//print_r($array);
			return $array;
			
		}
	}
	function rsvpStatus($eventid)
	{
		$sql = "SELECT event_rsvp FROM event_description WHERE event_id = $eventid AND event_rsvp = 'y'";
		return $this->db->get_col($sql);
	}
	function eventAcceptCheck($eventid,$memberid)
	{
		if($eventid && $memberid)
		{
					
			$sql =  "SELECT event_rsvp FROM event_invite_accept WHERE event_id = $eventid AND mem_id=$memberid";
			$rs1  = $this->db->get_row($sql,ARRAY_A);

			$sql = "SELECT event_rsvp FROM event_description AS ed INNER JOIN event_master em ON ed.event_id = em.event_id 
			        AND em.event_id = $eventid AND event_rsvp = 'y'";
			$rs2  = $this->db->get_row($sql,ARRAY_A);
			
			
			return array("invite_accept" => $rs1,"rsvp_yesno" => $rs2);
			
		}
		
	}
	function rsvpStatusDescription($status)
	{
		$arrayRsvp = array("no" => "Not Attending", "yes" => "Attending","myb" => "Maybe Attending",
		                   "na" => "RSVP not required","nil" => "Please RSVP to this Event");
		return $arrayRsvp[$status];
	}
	function checkExistEventAccept($eventid,$memberid)
	{
		if($eventid && $memberid)
		{
			$sql = "SELECT mem_id FROM event_invite_accept WHERE mem_id = $memberid AND event_id = $eventid";
			$rs = $this->db->get_row($sql,ARRAY_A);
			return count($rs);
		}
		
	}
	function eventAccept($memberid,$eventid)
	{
		if(isset($_REQUEST['rsvp']))
		{
		 	$array = array("mem_id" =>$memberid,"event_id" => $eventid,"event_rsvp" => $_REQUEST['rsvp']);
		}
		else
		{
			if (count($this->rsvpStatus($eventid)) > 0)
		 		$array = array("mem_id" =>$memberid,"event_id" => $eventid,"event_rsvp" => "nil");
			else
				$array = array("mem_id" =>$memberid,"event_id" => $eventid,"event_rsvp" => "na");
		}
		
		/*
		checkExistEventAccept  function check already this member accept the event if
		he already accept then update the RSVP status in event_invite_accept table.
		*/
		if ($this->checkExistEventAccept($eventid,$memberid) > 0)
			 $this->db->update("event_invite_accept",$array,"mem_id=$memberid AND event_id=$eventid");
		else
		 	$this->db->insert("event_invite_accept",$array," mem_id=$memberid AND event_id=$eventid");
		 
		 /*
		 if member send any RSVP comment that will be inserted in event_comment table.
		 Member can send number of comments for a event.
		 */
		 if(isset($_REQUEST['rsvp']))
		 {
		 	$array = array("event_id" => $eventid,"com_date" => cDate(),"mem_id" => $memberid,
			               "event_no_guest" => $_REQUEST['guest'],"comments" => $_REQUEST['comments']);
			$this->db->insert("event_comment",$array);
			setMessage('Thanks! Your RSVP has been delivered.',MSG_SUCCESS);
		 }
		
	}
	function removeEvents($eventid)
	{
		if($eventid)
		{
			$sql = "DELETE FROM event_invite_accept WHERE mem_id = {$_SESSION['memberid']} AND event_id = $eventid";
			$this->db->query($sql);
		}	
	}
	function uploadPhoto($image,$eventid)
	{
	if($image["name"])
	{
		if (!pictureFormat($image["type"]))
		{
			setMessage("Unknown Picture Format!!", MSG_INFO);
			return false;
		}
	}
	else
	{
			setMessage("Please select  picture to Upload", MSG_INFO);
			return false;

	}
		if($eventid)
		{
			$dir	 = SITE_PATH."/modules/event/photos/";
			$imgpath = SITE_PATH."/modules/event/thumb/";

			uploadImage($image, $dir, $eventid.".jpg",1);
			chmod($dir."$eventid.jpg",0777);
			
			
			thumbnail($dir,$imgpath,"$eventid.jpg",170,257,"","$eventid.jpg");
			//chmod($dir."$eventid.jpg",0777);
			
			$array = array("photo_up" => "Yes","event_photo" => "$eventid.jpg");
			$this->db->update("event_master",$array,"event_id =$eventid AND mem_id ={$_SESSION['memberid']}");

		}
	}
	function hourminuteArray()
	{
		$hArray = array("0" => "0 hour","1" => "1 hour","2" => "2 hour","3" => "3 hour",
		                "4" => "4 hour","5" => "5 hour","6" => "6 hour","7" => "7 hour",
						"8" => "8 hour","9" => "9 hour","10" => "10 hour","11" => "11 hour","12" => "12 hour");
		$mArray = array("0" => "0 minutes","15" => "15 minutes","30" => "30 minutes","45" => "45 minutes");
		
		return array($hArray,$mArray);
		
	}
	function reminderVa()
	{
		$remArray = array("0" => "-","1" => "1 Day","2" => "2 Day","3" => "3 Day","4" => "4 Day",
		                  "5" => "5 Day","6" => "6 Day","7" => "7 Day");
		return $remArray;
	}
	/**
	* Inserting values to event_master,event_description,event_venue Table
	* Author   : Afsal
	* Created  : 10/Aug/2007
	* Modified : 01/11/2007 By Afsal
	*/
	function insertCalendar($req='',$memberid = '',$type_schedule ='')
	{
		/** inser value to event_master table */
		unset($req["hidCASEFETCH_calendarStart"],$req["minYear_calendarStart"],
		$req["maxYear_calendarStart"]);
		
		if($type_schedule == "meeting")
		{
			$master_req = $req;
			$master_req["mem_id"] =  $memberid;
			$master_req["event_crt_date"] = cDate();
			
			/**
			unset unnecessary field for event_master table
			*/
			unset($master_req["event_location"],$master_req["event_date"],
			$master_req["minute_durHour"],$master_req["minute_durMinute"],$master_req["hour_dur"],
			$master_req["Time_Minute"],$master_req["reminder"],$master_req["event_sh_descr"],
			$master_req["befor"]);
			
			$event_id = $this->db->insert("event_master",$master_req);
			
			/**
			get the remaining field
			*/
			$difarr = array_diff($req,$master_req); //get the necessary items for next insertion
			$req 	= $difarr ;
			$req["event_id"] = $event_id;
		}
		else
		{
			$array   = array("event_name" => $_REQUEST['event_name'],"event_cat_id" => $_REQUEST['event_cat_id'],
					   "event_crt_date" => cDate(),"mem_id"=> $_SESSION['memberid'],"type_e" => "CL");
			$event_id = $this->db->insert("event_master",$array);
		}
		
		
		### inser value to event_description table ************* insert
		if($type_schedule == "meeting")
		{
			//print_r($req);exit;
			$desc_req = $req;
			unset($desc_req["event_location"],$desc_req["minute_durHour"],
			$desc_req["minute_durMinute"],$desc_req["hour_dur"],$desc_req["Time_Minute"],
			$desc_req["reminder"],$desc_req["event_date"],$req_venue["befor"],
			$desc_req["befor"]);
			
			$this->db->insert("event_description",$desc_req);
			$difarr = array_diff($req,$desc_req); //get the necessary items for next insertion
			$req 	= $difarr ;
			
		}
		else
		{
			$array = array("event_id" => $event_id,"event_sh_descr" => $_REQUEST['event_sh_descr']);
			$this->db->insert("event_description",$array); 
		}
		
		/**
		Insert to event_venue,event_invite_accept,event_reminder table
		*/
		if($type_schedule == "meeting")
		{
			$req_venue	= $req;
			$req_venue["event_time"] = $req["minute_durHour"].":".$req["minute_durMinute"] ;
			$req_venue["event_duration"] = $req_venue["hour_dur"].":". $req_venue["Time_Minute"] ;
			$req_venue["event_place"]	 = $req_venue["event_location"];
			$req_venue["event_id"]		 = $event_id;
			
			/**
			unset unnecessary fields
			*/
			unset($req_venue["minute_durHour"],$req_venue["minute_durMinute"],
			$req_venue["hour_dur"],$req_venue["Time_Minute"],$req_venue["event_location"],
			$req_venue["reminder"],$req_venue["befor"]);
			

			$this->db->insert("event_venue",$req_venue);
			
			$difarr = array_diff($req,$req_venue); //get the necessary items for next insertion
			$req 	= $difarr ;
			
		}
		else
		{
		
			if($_REQUEST['ampm'] == "AM" && $_REQUEST['hour'] == 12)
				$hour = "00";
			else if($_REQUEST['ampm'] == "PM" && $_REQUEST['hour'] != 12)
				$hour = $_REQUEST['hour'] + 12;
			else
				$hour = $_REQUEST['hour'];
			
			$time 	  = $hour.":".$_REQUEST['minute'].":"."00";
			$duration = $_REQUEST['hour_dur'].":".$_REQUEST['minute_dur'];
		
			/**
			Insert to event_venue
			*/
			$array = array("event_id" => $event_id,"event_date" => $_REQUEST['event_date'],"event_time" => $time,
						   "event_place" => $_REQUEST['event_place'],"event_address" => $_REQUEST['event_address'],
						   "event_city" => $_REQUEST['event_city'],"event_street" => $_REQUEST['event_street'],"event_state" => $_REQUEST['event_state'],
						   "event_country" => $_REQUEST['country'],"event_zip" => $_REQUEST['event_zip'],"event_duration" => $duration);
			$this->db->insert("event_venue",$array);
		
			/**
			Insert to to event_invite_accept
			*/
		}
		
		if($type_schedule == "meeting")
		{
		
		}
		else
		{
			$array = array("mem_id" => $_SESSION['memberid'],"event_id" => $event_id,"event_rsvp" => "nil");
			$this->db->insert("event_invite_accept",$array);  
		}
		/**
		Insert to to event_reminder
		*/
		if($req['reminder'] == 'yes')
		{
			$req_rem = $req;
			$req_rem["event_id"] = $event_id ;
			unset($req_rem["minute_durHour"],$req_rem["minute_durMinute"],$req_rem["Time_Minute"],
			$req_rem["hour_dur"],$req_rem["reminder"]);
			$this->db->insert("event_reminder",$req_rem);  
		}
		setMessage("Record saved successfully !!",MSG_SUCCESS);
		
	return $event_id;
	}
	
	function updateCalendar($eventid,$req='',$memberid = '',$type_schedule ='')
	{
		
		unset($req["hidCASEFETCH_calendarStart"],$req["minYear_calendarStart"],
		$req["maxYear_calendarStart"]);
		
		if($type_schedule == "meeting")
		{
			$master_req = $req;
			$master_req["mem_id"] =  $memberid;
			$master_req["event_crt_date"] = cDate();
			
			/**
			unset unnecessary field for event_master table
			*/
			unset($master_req["event_location"],$master_req["event_date"],
			$master_req["minute_durHour"],$master_req["minute_durMinute"],$master_req["hour_dur"],
			$master_req["Time_Minute"],$master_req["reminder"],$master_req["event_sh_descr"],
			$master_req["befor"]);
			
			
			$event_id = $this->db->update("event_master",$master_req,"event_id = $eventid AND mem_id=$memberid");
			
			/**
			get the remaining field
			*/
			$difarr = array_diff($req,$master_req); //get the necessary items for next insertion
			$req 	= $difarr ;
			
			/** Update value to event_description table ************* */
			$desc_req = $req;
			unset($desc_req["event_location"],$desc_req["minute_durHour"],
			$desc_req["minute_durMinute"],$desc_req["hour_dur"],$desc_req["Time_Minute"],
			$desc_req["reminder"],$desc_req["event_date"],$req_venue["befor"],
			$desc_req["befor"]);
			
			$this->db->update("event_description",$desc_req,"event_id = $eventid");
			$difarr = array_diff($req,$desc_req); //get the necessary items for next insertion
			$req 	= $difarr ;
			
			/**
			Update to event_venue,event_invite_accept,event_reminder table
			*/	
			$req_venue	= $req;
			$req_venue["event_time"] = $req["minute_durHour"].":".$req["minute_durMinute"] ;
			$req_venue["event_duration"] = $req_venue["hour_dur"].":". $req_venue["Time_Minute"] ;
			$req_venue["event_place"]	 = $req_venue["event_location"];
						
			/**
			unset unnecessary fields
			*/
			unset($req_venue["minute_durHour"],$req_venue["minute_durMinute"],
			$req_venue["hour_dur"],$req_venue["Time_Minute"],$req_venue["event_location"],
			$req_venue["reminder"],$req_venue["befor"]);
			$this->db->update("event_venue",$req_venue,"event_id=$eventid");
			
			$difarr = array_diff($req,$req_venue); //get the necessary items for next insertion
			$req 	= $difarr ;
			
			/**
			Update to to event_reminder
			*/
			if($req['reminder'] == 'yes')
			{
				$req_rem = $req;
				unset($req_rem["minute_durHour"],$req_rem["minute_durMinute"],$req_rem["Time_Minute"],
				$req_rem["hour_dur"],$req_rem["reminder"]);
				
				$this->db->update("event_reminder",$req_rem,"event_id=$eventid"); 
				
			}
			else 
			{
				/** If exist*/
				$sql ="DELETE FROM event_reminder WHERE event_id =$eventid";
				$this->db->query($sql);
			}
			setMessage("Record updated successfully !!",MSG_SUCCESS);
		}
		else
		{
			$array   = array("event_name" => $_REQUEST['event_name'],"event_cat_id" => $_REQUEST['event_cat_id'],
				       "event_crt_date" => cDate(),"mem_id"=> $_SESSION['memberid'],"type_e" => "CL");
						  
			$this->db->update("event_master",$array,"event_id =$eventid");
			
			$array = array("event_sh_descr" => $_REQUEST['event_sh_descr']);
			$this->db->update("event_description",$array); 
			
					if($_REQUEST['ampm'] == "AM" && $_REQUEST['hour'] == 12)
			$hour = "00";
			else if($_REQUEST['ampm'] == "PM" && $_REQUEST['hour'] != 12)
			$hour = $_REQUEST['hour'] + 12;
			else
			$hour = $_REQUEST['hour'];
			
			$time = $hour.":".$_REQUEST['minute'].":"."00";
			
			$duration = $_REQUEST['hour_dur'].":".$_REQUEST['minute_dur'];
			
			$array = array("event_date" => $_REQUEST['event_date'],"event_time" => $time,
			               "event_place" => $_REQUEST['event_place'],"event_address" => $_REQUEST['event_address'],
						   "event_city" => $_REQUEST['event_city'],"event_street" => $_REQUEST['event_street'],"event_state" => $_REQUEST['event_state'],
					       "event_country" => $_REQUEST['country'],"event_zip" => $_REQUEST['event_zip'],"event_duration" => $duration);
					 
			$this->db->update("event_venue",$array,"event_id =$eventid");  
			
			if($_REQUEST['reminder'] == 'yes')
			{
				$sql = "SELECT event_id FROM event_reminder WHERE event_id =$eventid AND mem_id = {$_SESSION['memberid']}";
				$rs  = $this->db->get_row($sql,ARRAY_A);
				
				$array = array("event_id" => $eventid,"mem_id" => $_SESSION['memberid'],"befor" => $_REQUEST['remind']);
				
				if(count($rs) > 0)
				$this->db->update("event_reminder",$array,"event_id =$eventid"); 
				else
				$this->db->insert("event_reminder",$array); 
			}
			else
			{
				 $this->db->query("DELETE FROM event_reminder WHERE event_id = $eventid AND mem_id = {$_SESSION['memberid']}");  
	
			}
		}
		return $eventid;
	}
	
	function calendarDetails($eventid)
	{
		if($eventid)
		{
			$sql = "SELECT em.event_id,em.event_organizer, em.event_name,em.event_email, ev.event_date, ev.event_time,
					ADDTIME(ev.event_time,ev.event_duration) AS totime,
					ev.event_place,ev.event_street,em.photo_up,em.event_photo,ev.event_address,ev.event_city,ev.event_state,
					ev.event_zip,ed.event_sh_descr,ed.event_long_descr,ev.event_country As countrycode,
					ev. event_duration,em.event_cat_id,ca.type,ca.cat_name
					FROM event_master AS em INNER JOIN event_venue AS ev INNER JOIN event_description AS ed
					INNER JOIN event_category AS ca ON em.event_id = ev.event_id AND em.event_id = ed.event_id
					AND em.event_cat_id = ca.cat_id  WHERE em.event_id = $eventid AND em.mem_id = {$_SESSION['memberid']}";

			$result = $this->db->get_row($sql,ARRAY_A);
			return $result;
		}
	}
	function reminderIsAdded($eventid)
	{
		if($eventid)
		{
			$sql = "SELECT befor FROM event_reminder WHERE event_id = $eventid AND mem_id ={$_SESSION['memberid']}";
			$rs = $this->db->get_row($sql,ARRAY_A);
			return $rs['befor'];
		}
	}
	function eventActiveDeactive($status,$eventid)
	{
		if($status && $eventid)
		{
			if($status == 'Y')
				$status ='N';
			elseif($status == 'N')
				$status = 'Y';
				
				
			$sql = "UPDATE event_master SET active = '$status' WHERE event_id =$eventid";
			$this->db->query($sql);
		}
		
	}
	function insertFeatured()
	{
		if($_REQUEST['eventid'])
		{
			$sql = "SELECT event_id FROM event_featured WHERE event_id ={$_REQUEST['eventid']}";
			$rs  = $this->db->get_row($sql,ARRAY_A);

			if(count($rs) == 0)
			{
				$array = array("event_id" => $_REQUEST['eventid'],"fe_date" => cDate());
				$this->db->insert("event_featured",$array);
				return true;
			}
			else
			{
				setMessage('This event already featured.',MSG_INFO);
				
			}
			
		}
		
	}
	function removeFeatured($eventid)
	{
		if($eventid)
		{
			$sql = "DELETE FROM event_featured WHERE event_id =$eventid";
			$this->db->query($sql);
		}
	}
	function featuredEvent($page,$limit = 10,$params,$qry='',$orderBy='',$search = '')
	{
		if($search)
		{
		$qry .= " AND em.event_name LIKE '$search%'";
		}
		
		$sql = "SELECT em.event_id,em.event_organizer, em.event_name, ev.event_date, ev.event_time, ev.event_place,em.active,
                CASE ea.event_rsvp WHEN 'nil' THEN 'N/A' WHEN 'yes' THEN 'Attending' WHEN 'no' THEN 'Not Attending'
			    WHEN 'myb' THEN 'Maybe Attending' END AS rsvp,me.id,me.username
                FROM event_master AS em INNER JOIN event_venue AS ev INNER JOIN event_description AS ed
                INNER JOIN event_invite_accept AS ea INNER JOIN member_master AS me INNER JOIN event_featured AS fe
				ON em.event_id = ev.event_id AND em.event_id = ed.event_id
                AND  em.event_id = ea.event_id AND me.id = em.mem_id AND em.event_id = fe.event_id  WHERE 1 $qry";
		
		 $result = $this->db->get_results_pagewise($sql, $page, $limit, $params, $output=OBJECT, $orderBy='fe.id DESC') ;
		 return $result;
	}
	function featureEventList()
	{
		$sql = "SELECT em.event_id,em.event_organizer, em.event_name, ev.event_date, ev.event_time,
				ev.event_place,em.photo_up,em.event_photo,em.active,ed.event_sh_descr,co.country_name
                FROM event_master AS em INNER JOIN event_venue AS ev INNER JOIN event_description AS ed
                INNER JOIN member_master AS me INNER JOIN event_featured AS fe
				INNER JOIN country_master As co ON em.event_id = ev.event_id AND em.event_id = ed.event_id
                AND  me.id = em.mem_id AND em.event_id = fe.event_id AND
				co.country_id = ev.event_country  WHERE 1 AND em.active='Y'";
				
		$rs   =  $this->db->get_results($sql,ARRAY_A);
		$i = 0;
		$strHtm = '';
		foreach($rs as $row)
		{
			 if(strlen($row['event_name']) >30)
			 	$event_name = substr($row['event_name'],0,39)."..";
			 else
			 	$event_name = $row['event_name'];
			
			if($row['photo_up'] == 'Yes')
				$img = '<img src='.SITE_URL."/modules/event/photos/".$row['event_photo'].' width="50" height="77">';
			
			
			$strHtm.= '<td valign="top">';
		  	$strHtm.= '<table width="100%"  border="0" cellspacing="0" cellpadding="0">';
      		$strHtm.= '<tr>';
        	$strHtm.= '<td width="20%" rowspan="2" style="text-align:center" valign="top">'.$img.'</td>';
       		$strHtm.= "<td width='75%'><a href=".makeLink(array('mod'=>'event', 'pg'=>'event'), 'act=details&eventid='.$row['event_id'])." class='toplinknormal' valign='top'>".$event_name."</a></td>";
      		$strHtm.= '</tr>';
      		$strHtm.= '<tr>';
        	$strHtm.= '<td class = "bodytext" style="width=240px" valign="top">'.$row['event_sh_descr']."<br>". date("F j, Y",strtotime($row["event_date"])).", ".$row["event_place"].", ".$row["country_name"].'</td>';
      		$strHtm.= '</tr>';
     		$strHtm.= '</table>';
		    $strHtm.= '</td>';

			$i++;
			if($i%2 ==0)
			$strHtm.='<tr><td class="trHeight1"></td></tr><tr></tr>';
			
			
		}
		return $strHtm;
	}
	
	/**
	* Get the meeting list of user
	* Author   : Afsal
	* Created  : 01/Nov/2007
	*/
	function getMeetingList($memberid,$page, $limit, $params, $output=OBJECT, $orderBy='',$eventid=''){
	
		if($memberid){
			

			$sql = "SELECT em.event_id,em.event_organizer, em.event_name,em.event_email,em.status, ev.event_date, ev.event_time,
			ADDTIME(ev.event_time,ev.event_duration) AS totime,
			ev.event_place,ev.event_street,em.photo_up,em.event_photo,ev.event_address,ev.event_city,ev.event_state,
			ev.event_zip,ed.event_sh_descr,ed.event_long_descr,ev.event_country As countrycode,
			ev. event_duration FROM event_master AS em INNER JOIN event_venue AS ev INNER JOIN event_description AS ed
			ON em.event_id = ev.event_id AND em.event_id = ed.event_id
			WHERE em.mem_id = $memberid";
			
			$result = $this->db->get_results_pagewise($sql, $page, $limit, $params, $output=OBJECT,$orderBy) ;
			return $result;
		}

	}
	/**
	* Get each meeting list of user
	* Author   : Afsal
	* Created  : 08/Nov/2007
	*/
	function getMeetingListRow($memberid,$eventid){
	
		if($memberid && $eventid){
			
			$sql = "SELECT em.event_id,em.event_organizer, em.event_name,em.event_email,em.status,ev.event_date, ev.event_time,
			ADDTIME(ev.event_time,ev.event_duration) AS totime,
			ev.event_place,ev.event_street,em.photo_up,em.event_photo,ev.event_address,ev.event_city,ev.event_state,
			ev.event_zip,ed.event_sh_descr,ed.event_long_descr,ev.event_country As countrycode,
			ev. event_duration,rm.befor FROM event_master AS em INNER JOIN event_venue AS ev INNER JOIN event_description AS ed
			ON em.event_id = ev.event_id AND em.event_id = ed.event_id LEFT JOIN event_reminder AS rm 
			ON rm.event_id = em.event_id
			WHERE em.mem_id = $memberid AND em.event_id = $eventid";
			
			$result = $this->db->get_row($sql,OBJECT) ;
			//print_r($result);
			return $result;
		}

	}
	
	/**
	* Get the reminder values from event_reminder_value
	* Author   : Afsal
	* Created  : 08/Nov/2007
	*/
	function getReminderValues(){
		
		$sql = "SELECT title,value,type FROM event_reminder_value ORDER BY display_order";
		$reminderAr[0] = $this->db->get_col($sql,0);
		$reminderAr[1] = $this->db->get_col($sql,1);
		return $reminderAr;
		
		
	}
	/**
	* Get the reminder values from event_reminder_value
	* Author   : Afsal
	* Created  : 12/Nov/2007
	*/
	function deleteMeet($id){
		
		$sql = "DELETE FROM event_master WHERE event_id = $id";
		$this->db->query($sql);
		$sql = "DELETE FROM event_venue WHERE event_id = $id";
		$this->db->query($sql);
		$sql = "DELETE FROM event_description WHERE event_id = $id";
		$this->db->query($sql);
		$sql = "DELETE FROM event_reminder WHERE event_id = $id";
		$this->db->query($sql);
		setMessage("Record deleted successfully !!");
		
	}
}
?>