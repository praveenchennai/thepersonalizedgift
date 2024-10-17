<?php
include_once "class.calendarevents.php";
class Property extends FrameWork {
	var 	$id;
	var 	$category;
	var 	$name;
	var 	$Description;
	var  	$specific_id;


	function Property($id="",$category="", $name="",$Description="") {
		$this->id = $id;
		$this->category = $category;
		$this->name = $name;
		$this->Description=$Description;
		$this->specific_id	=	1; #Weekend Specific Id
		$this->FrameWork();
	}


	function setArrData($szArrData)
	{
		$this->arrData = $szArrData;
	}


	/*
	End function setArrDate
	Return Post array data
	*/
	function getArrData()
	{
		return $this->arrData;
	}
	/**
	 *Listing Category in combo 
	 */



	/* BEGIN FUNCTION Property BOOKING */
	function addEditPropBooking (&$req) {

		extract($req);
		$message = true;

		if ( date($req['txtcalendarTo'])-date($req['txtcalendarFrom']) < 0 ) {
			$message = "Booking Start Date should be less than End Date";
		}

		return $message;
	}

	/*	 END FUNCTION ALBUM BOOKING	 */


	function propertyListAll ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
		$sql	=	"SELECT * FROM album WHERE 1";
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}


	function propertyListByUser ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
		if ($_SESSION['memberid']) {
			$member	=	$_SESSION['memberid'];
			$sql	=	"SELECT * FROM album WHERE user_id=$member";
			$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
			return $rs;
		} else {
			return false;
		}
	}


	function bookedPropertyList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
		if ($_SESSION['memberid']) {
			$sql	=	"SELECT * FROM album_booking  WHERE user_id=$member";
			$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
			return $rs;
		}  else {
			return false;
		}
	}

	
	
	/**
	 * The following method returns the basic property information
	 *
	 * @param Int $PropertyId
	 * @return Array
	 * 
	 */
	function getBasicPropertyInfomation($PropertyId, $AlbumObj, $PhotoObj)
	{
		$BasicPropInfo	=	array();
		
		$Qry1			=	"SELECT 
								T1.*,
								T2.form_title,T3.price ,T3.duration,T3.unit,T3.min_duration
							FROM flyer_data_basic AS T1 
							LEFT JOIN flyer_form_master AS T2 ON T2.form_id = T1.form_id
							INNER JOIN property_pricing AS T3 ON T3.album_id = T1.album_id
							AND T3.default_val = 'Y'
							AND T1.album_id = '$PropertyId'";
		
		$BasicPropInfo	=	$this->db->get_row($Qry1, ARRAY_A);

		$AlbumDetails		=	$AlbumObj->getAlbumDetails($PropertyId);
		$DefaultImage		=	$AlbumDetails["default_img"];
		$DefImageExtn		=	$PhotoObj->imgExtension($AlbumDetails["default_img"]);
		
		if($DefaultImage <=0 || $DefImageExtn == "")
		$DefaultImgName	= "";
		else
		$DefaultImgName		=	$DefaultImage.$DefImageExtn;
		
		$BasicPropInfo		=	array_merge($BasicPropInfo, array('DefaultImage' => $DefaultImgName));
		return $BasicPropInfo;
	}
		

	/**
     * The following method returns the number of days in a year
     *
     * @param Int $Year
     * @return Int [Number of Days in a Year]
     * 
     */
	function getNumberOfDaysInYear($Year)
	{
		$NoOfDays	=	0;

		for($i=1; $i<=12; $i++)
		$NoOfDays	+=	cal_days_in_month(CAL_GREGORIAN, $i, $Year);

		return $NoOfDays;
	}


	/**
	 * The following method returns the number of days between two dates. If any of the date is invalid or EndDate is greater than begin date function returns the days as 0
	 * If the start and End dates are same then this function returns 1
	 *
	 * Author	:	vimson@newagesmb.com
	 * Created	:	04/01/2008
	 * 
	 * @param Date $BeginDate 		[YYYY-MM-DD or YYYY/MM/DD]
	 * @param Date $EndDate			[YYYY-MM-DD or YYYY/MM/DD]
	 * @param String $DateFormat	[Delimiter string default '-']
	 * @return Array 				[ TotalDays => Number of Total days  'WeekendDays' => weekend Days in the number of days]
	 */
	function getNumberOfDaysInDateRange($BeginDate, $EndDate, $DateFormat = '-')
	{
		$WeekDays			=	array();

		$NumberOfDays		=	0;
		$WeekendDaysNo		=	0;

		$WeekEndDaysArray	=	array('Saturday', 'Sunday');

		$BeginDate			=	trim($BeginDate);
		$EndDate			=	trim($EndDate);

		if(empty($BeginDate) || empty($EndDate))
		return array('TotalDays' => $NumberOfDays, 'WeekendDaysNo' => $WeekEndDays);

		$BeginDateParts		=	explode($DateFormat, $BeginDate);
		$EndDateParts		=	explode($DateFormat, $EndDate);

		if(!checkdate($BeginDateParts[1], $BeginDateParts[2], $BeginDateParts[0]))
		return array('TotalDays' => $NumberOfDays, 'WeekendDaysNo' => $WeekEndDays);

		if(!checkdate($EndDateParts[1], $EndDateParts[2], $EndDateParts[0]))
		return array('TotalDays' => $NumberOfDays, 'WeekendDaysNo' => $WeekEndDays);

		$StartDay			=	gregoriantojd($BeginDateParts[1], $BeginDateParts[2], $BeginDateParts[0]);
		$EndDay				=	gregoriantojd($EndDateParts[1], $EndDateParts[2], $EndDateParts[0]);

		$Arrindx	=	0;
		for($i=$StartDay; $i<=$EndDay; $i++) {
			$TempDateParts	=	explode('/',jdtogregorian($i)); 		# month/day/year
			$TempMonth		=	$TempDateParts[0];
			$TempDay		=	$TempDateParts[1];
			$TempYear		=	$TempDateParts[2];
			$WeekDay		=	date('l', mktime(0, 0, 0, $TempMonth, $TempDay, $TempYear));

			$WeekDays[$Arrindx]['Date']			=	"$TempYear-$TempMonth-$TempDay";
			$WeekDays[$Arrindx]['Day']			=	$WeekDay;
			$WeekDays[$Arrindx]['Month']		=	$TempMonth;
			$WeekDays[$Arrindx]['Year']			=	$TempYear;

			if(in_array($WeekDay,$WeekEndDaysArray)) {
				$WeekendDaysNo++;
				$WeekDays[$Arrindx++]['Type']		=	'WEEKEND';
			} else {
				$WeekDays[$Arrindx++]['Type']		=	'WEEKDAY';
			}

		}

		$NumberOfDays		=	$EndDay - $StartDay;
		$NumberOfDays		=	($NumberOfDays < 0) ? 0 : $NumberOfDays + 1;

		return array('TotalDays' => $NumberOfDays, 'WeekendDaysNo' => $WeekendDaysNo, 'WeekDays' => $WeekDays );
	}


	/**
	 * The following method returns the Basic Price details associated with a property
	 *
	 * Author	:	vimson@newagesmb.com
	 * @param Int $PropertyId
	 * @return Array
	 */
	function getBasicPriceDetails($PropertyId)
	{
		$Result	=	array();
		$Qry	=	"SELECT basic_price, basic_price_duration, basic_price_duration_type
					FROM flyer_data_basic WHERE album_id = '$PropertyId'";
		$Row	=	$this->db->get_row($Qry, ARRAY_A);

		$Result['Price']		=	$Row['basic_price'];
		$Result['Duration']		=	$Row['basic_price_duration'];
		$Result['DurationType']	=	$Row['basic_price_duration_type'];

		return $Result;
	}



	/**
	 * The following method returns the weekend price details. if type is 'pe' then the price calculated as the percentage of total amount else if 'pr' then weekend price calculated as Price
	 *
	 * @author vimson@newagesmb.com
	 * @created 07/01/2008
	 * 
	 * @param Int $PropertyId
	 * @return Array
	 */
	function getWeekendPrice($PropertyId)
	{
		$WeekendPrice	=	array();

		$Qry		=	"SELECT priceperc, type FROM album_pricing WHERE specific_id = {$this->specific_id} AND album_id = '$PropertyId' ";
		$Row		=	$this->db->get_row($Qry, ARRAY_A);
		$priceperc	=	(float)trim($Row['priceperc']);
		$type		=	trim($Row['type']);

		if($priceperc == '' || $priceperc == 0)
		$priceperc	=	'NA';

		$WeekendPrice['price']	=	$priceperc;
		$WeekendPrice['type']	=	$type;

		return $WeekendPrice;
	}



	/**
	 * The following method returns the special price corresponding to a particular date
	 *
	 * Author	:	vimson@newagesmb.com
	 * @param unknown_type $Date
	 * @return unknown
	 */
	function getSpecialPriceOfParticularDate($PropertyId)
	{
		$SpecialPrice	=	array();

		$Qry1			=	"SELECT priceperc,type FROM property_special_price
							 WHERE album_id = '$PropertyId' LIMIT 1";
		
		$Row1			=	$this->db->get_row($Qry1, ARRAY_A);

		$priceperc		=	(float)trim($Row1['priceperc']);
		$type			=	trim($Row1['type']);

		if($priceperc == '' || $priceperc == 0)
		$priceperc	=	'NA';

		$SpecialPrice['Price']		=	$priceperc;
		$SpecialPrice['type']		=	$type;

		return $SpecialPrice;
	}
	/**
	 * The following method returns the current Days Price
	 *
	 * @author vimson@newagesmb.com
	 * @param Array $BasicPrice
	 * @param Array $WeekendPrice
	 * @param Array $SpecialPrice
	 * @param Array $Day
	 * 
	 * @return float
	 */
	function getPriceOfCurrentDay($BasicPriceDtls, $WeekendPriceDtls, $SpecialPriceDtls, $BookingDayDtls)
	{
		$BookingPrice		=	0;

		$CurrBookngDate		=	$BookingDayDtls['Date'];
		$CurrBookngDay		=	$BookingDayDtls['Day'];
		$CurrBookngType		=	$BookingDayDtls['Type']; #WEEKEND,WEEKDAY
		$CurrBookngMonth	=	$BookingDayDtls['Month'];
		$CurrBookngYear		=	$BookingDayDtls['Year'];


		$BasicPrice					=	$BasicPriceDtls['Price'];
		$BasicPriceDuration			=	(int)$BasicPriceDtls['Duration'];
		$BasicPriceDuration			=	($BasicPriceDuration <= 0) ? 1 : $BasicPriceDuration;
		$BasicPriceDurationType		=	$BasicPriceDtls['DurationType'];	#Month,Day,Week,Year


		if($BasicPriceDurationType == 'Month') {
			if($SpecialPriceDtls['Price'] != 'NA') {
				if($SpecialPriceDtls['type'] == 'pr') {
					$BookingPriceForPeriod	=	$BasicPrice + $SpecialPriceDtls['Price'];
					$BookingPriceForPeriod	=	round($BookingPriceForPeriod, 2);
				}

				if($SpecialPriceDtls['type'] == 'pe') {
					$BookingPriceForPeriod	=	$BasicPrice + (($BasicPrice * $SpecialPriceDtls['Price']) / 100);
					$BookingPriceForPeriod	=	round($BookingPriceForPeriod, 2);
				}

				$NoOfDaysInMonth	=	cal_days_in_month(CAL_GREGORIAN, $CurrBookngMonth, $CurrBookngYear);
				$BookingPrice		=	($BookingPriceForPeriod / ($NoOfDaysInMonth * $BasicPriceDuration));
				$BookingPrice		=	round($BookingPrice, 2);
				return $BookingPrice;
			}

			if($CurrBookngType == 'WEEKEND' && $WeekendPriceDtls['price'] != 'NA') {
				if($WeekendPriceDtls['type'] == 'pr') {
					$BookingPriceForPeriod	=	$BasicPrice + $WeekendPriceDtls['price'];
					$BookingPriceForPeriod	=	round($BookingPriceForPeriod, 2);
				}

				if($WeekendPriceDtls['type'] == 'pe') {
					$BookingPriceForPeriod	=	$BasicPrice + (($WeekendPriceDtls['price'] * $BasicPrice) / 100);
					$BookingPriceForPeriod	=	round($BookingPriceForPeriod, 2);
				}

				$NoOfDaysInMonth	=	cal_days_in_month(CAL_GREGORIAN, $CurrBookngMonth, $CurrBookngYear);
				$BookingPrice		=	($BookingPriceForPeriod / ($NoOfDaysInMonth * $BasicPriceDuration));
				$BookingPrice		=	round($BookingPrice, 2);
				return $BookingPrice;
			}


			if($CurrBookngType == 'WEEKDAY' || $WeekendPriceDtls['price'] == 'NA') {
				$BookingPriceForPeriod	=	$BasicPrice;
				$NoOfDaysInMonth		=	cal_days_in_month(CAL_GREGORIAN, $CurrBookngMonth, $CurrBookngYear);
				$BookingPrice			=	($BookingPriceForPeriod / ($NoOfDaysInMonth * $BasicPriceDuration));
				$BookingPrice			=	round($BookingPrice, 2);
				return $BookingPrice;
			}
		}


		if($BasicPriceDurationType == 'Day') {
			if($SpecialPriceDtls['Price'] != 'NA') {
				if($SpecialPriceDtls['type'] == 'pr') {
					$BookingPriceForPeriod	=	$BasicPrice + $SpecialPriceDtls['Price'];
					$BookingPriceForPeriod	=	round($BookingPriceForPeriod, 2);
				}

				if($SpecialPriceDtls['type'] == 'pe') {
					$BookingPriceForPeriod	=	$BasicPrice + (($BasicPrice * $SpecialPriceDtls['Price']) / 100);
					$BookingPriceForPeriod	=	round($BookingPriceForPeriod, 2);
				}

				$BookingPrice		=	$BookingPriceForPeriod / $BasicPriceDuration;
				$BookingPrice		=	round($BookingPrice, 2);
				return $BookingPrice;
			}

			if($CurrBookngType == 'WEEKEND' && $WeekendPriceDtls['price'] != 'NA') {
				if($WeekendPriceDtls['type'] == 'pr') {
					$BookingPriceForPeriod	=	$BasicPrice + $WeekendPriceDtls['price'];
					$BookingPriceForPeriod	=	round($BookingPriceForPeriod, 2);
				}

				if($WeekendPriceDtls['type'] == 'pe') {
					$BookingPriceForPeriod	=	$BasicPrice + (($WeekendPriceDtls['price'] * $BasicPrice) / 100);
					$BookingPriceForPeriod	=	round($BookingPriceForPeriod, 2);
				}

				$BookingPrice		=	$BookingPriceForPeriod / $BasicPriceDuration;
				$BookingPrice		=	round($BookingPrice, 2);
				return $BookingPrice;
			}

			if($CurrBookngType == 'WEEKDAY' || $WeekendPriceDtls['price'] == 'NA') {
				$BookingPriceForPeriod	=	$BasicPrice;
				$BookingPrice			=	$BookingPriceForPeriod / $BasicPriceDuration;
				$BookingPrice			=	round($BookingPrice, 2);
				return $BookingPrice;
			}
		}


		if($BasicPriceDurationType == 'Week') {
			if($SpecialPriceDtls['Price'] != 'NA') {
				if($SpecialPriceDtls['type'] == 'pr') {
					$BookingPriceForPeriod	=	$BasicPrice + $SpecialPriceDtls['Price'];
					$BookingPriceForPeriod	=	round($BookingPriceForPeriod, 2);
				}

				if($SpecialPriceDtls['type'] == 'pe') {
					$BookingPriceForPeriod	=	$BasicPrice + (($BasicPrice * $SpecialPriceDtls['Price']) / 100);
					$BookingPriceForPeriod	=	round($BookingPriceForPeriod, 2);
				}

				$BookingPrice		=	($BookingPriceForPeriod / (7 * $BasicPriceDuration));
				$BookingPrice		=	round($BookingPrice, 2);
				return $BookingPrice;
			}

			if($CurrBookngType == 'WEEKEND' && $WeekendPriceDtls['price'] != 'NA') {
				if($WeekendPriceDtls['type'] == 'pr') {
					$BookingPriceForPeriod	=	$BasicPrice + $WeekendPriceDtls['price'];
					$BookingPriceForPeriod	=	round($BookingPriceForPeriod, 2);
				}

				if($WeekendPriceDtls['type'] == 'pe') {
					$BookingPriceForPeriod	=	$BasicPrice + (($WeekendPriceDtls['price'] * $BasicPrice) / 100);
					$BookingPriceForPeriod	=	round($BookingPriceForPeriod, 2);
				}

				$BookingPrice		=	($BookingPriceForPeriod / (7 * $BasicPriceDuration));
				$BookingPrice		=	round($BookingPrice, 2);
				return $BookingPrice;
			}

			if($CurrBookngType == 'WEEKDAY' || $WeekendPriceDtls['price'] == 'NA') {
				$BookingPriceForPeriod	=	$BasicPrice;
				$BookingPrice			=	($BookingPriceForPeriod / (7 * $BasicPriceDuration));
				$BookingPrice			=	round($BookingPrice, 2);
				return $BookingPrice;
			}
		}


		if($BasicPriceDurationType == 'Year') {
			if($SpecialPriceDtls['Price'] != 'NA') {
				if($SpecialPriceDtls['type'] == 'pr') {
					$BookingPriceForPeriod	=	$BasicPrice + $SpecialPriceDtls['Price'];
					$BookingPriceForPeriod	=	round($BookingPriceForPeriod, 2);
				}

				if($SpecialPriceDtls['type'] == 'pe') {
					$BookingPriceForPeriod	=	$BasicPrice + (($BasicPrice * $SpecialPriceDtls['Price']) / 100);
					$BookingPriceForPeriod	=	round($BookingPriceForPeriod, 2);
				}

				$NoOfDaysInYear		=	$this->getNumberOfDaysInYear($CurrBookngYear);
				$BookingPrice		=	($BookingPriceForPeriod / ($NoOfDaysInYear * $BasicPriceDuration));
				$BookingPrice		=	round($BookingPrice, 2);
				return $BookingPrice;
			}

			if($CurrBookngType == 'WEEKEND' && $WeekendPriceDtls['price'] != 'NA') {
				if($WeekendPriceDtls['type'] == 'pr') {
					$BookingPriceForPeriod	=	$BasicPrice + $WeekendPriceDtls['price'];
					$BookingPriceForPeriod	=	round($BookingPriceForPeriod, 2);
				}

				if($WeekendPriceDtls['type'] == 'pe') {
					$BookingPriceForPeriod	=	$BasicPrice + (($WeekendPriceDtls['price'] * $BasicPrice) / 100);
					$BookingPriceForPeriod	=	round($BookingPriceForPeriod, 2);
				}

				$NoOfDaysInYear		=	$this->getNumberOfDaysInYear($CurrBookngYear);
				$BookingPrice		=	($BookingPriceForPeriod / ($NoOfDaysInYear * $BasicPriceDuration));
				$BookingPrice		=	round($BookingPrice, 2);
				return $BookingPrice;
			}

			if($CurrBookngType == 'WEEKDAY' || $WeekendPriceDtls['price'] == 'NA') {
				$BookingPriceForPeriod	=	$BasicPrice;
				$NoOfDaysInYear			=	$this->getNumberOfDaysInYear($CurrBookngYear);
				$BookingPrice			=	($BookingPriceForPeriod / ($NoOfDaysInYear * $BasicPriceDuration));
				$BookingPrice			=	round($BookingPrice, 2);
				return $BookingPrice;
			}
		}
	}




	/**
	 * The following method 
	 *
	 * @author 	vimson@newagesmb.com
	 * @created	07/01/2008
	 * 
	 * @param Date[YYYY-MM-DD] $BeginDate
	 * @param Date[YYYY-MM-DD] $EndDate
	 * @param Int $PropertyId
	 * @return An associative array which contains information about the Booking Price Details
	 */
	function getBookingPriceDetails($BeginDate, $EndDate, $PropertyId, $DateFormat = '-')
	{
		$Bookingetails			=	array();
		
		$DateDetails	=	$this->getNumberOfDaysInDateRange($BeginDate, $EndDate, $DateFormat);
		$TotalDays		=	$DateDetails['TotalDays'];
		$WeekendDaysNo	=	$DateDetails['WeekendDaysNo'];
		$WeekDays		=	$DateDetails['WeekDays'];

		$BasicPrice		=	$this->getBasicPriceDetails($PropertyId);
		$WeekendPrice	=	$this->getWeekendPrice($PropertyId);
		
		$TotalBookingPrice	=	0;
		
		$ArrIndx	=	0;
		foreach($WeekDays as $Day) {
			$SpecialPrice		=	$this->getSpecialPriceOfParticularDate($Day['Date'], $PropertyId);
			$BookingPrice		=	$this->getPriceOfCurrentDay($BasicPrice, $WeekendPrice, $SpecialPrice, $Day);
			$TotalBookingPrice	+=	$BookingPrice;
			
			$Bookingetails['BookingInfo'][$ArrIndx]['Date']		=	$Day['Date'];
			$Bookingetails['BookingInfo'][$ArrIndx]['Day']		=	$Day['Day'];
			$Bookingetails['BookingInfo'][$ArrIndx]['Month']	=	$Day['Month'];
			$Bookingetails['BookingInfo'][$ArrIndx]['Year']		=	$Day['Year'];
			$Bookingetails['BookingInfo'][$ArrIndx]['Type']		=	$Day['Type'];
			$Bookingetails['BookingInfo'][$ArrIndx]['Price']	=	$BookingPrice;
			
			if($SpecialPrice['Price'] != 'NA')
				$Bookingetails['BookingInfo'][$ArrIndx]['PriceMode']	=	'SPECIAL';
			else if($WeekendPrice['price'] != 'NA' && $Day['Type'] == 'WEEKEND')
				$Bookingetails['BookingInfo'][$ArrIndx]['PriceMode']	=	'WEEKEND';
			else
				$Bookingetails['BookingInfo'][$ArrIndx]['PriceMode']	=	'WEEKDAY';
						
			$ArrIndx++;
		}
		
		$Bookingetails['TotalBookingPrice']	=	$TotalBookingPrice;
		
		return $Bookingetails;
		
	}
	
	
	/**
	 * The following method return the Assigned user id of property
	 *
	 * @param Int $PropertyId
	 * @return Int
	 */
	function getPropertyOwnerIdFromPropertyId($PropertyId)
	{
		$Qry		=	"SELECT user_id FROM flyer_data_basic WHERE album_id = '$PropertyId'";
		$Row		=	$this->db->get_row($Qry, ARRAY_A);
		$user_id	=	$Row['user_id'];
		return $user_id;
	}
	
	/**
	 * The following method returns the assigned property user id if that property assigned to a broker, else returns the property owner id
	 *
	 * @created 11/01/2008
	 * @author vimson@newagesmb.com
	 * @param Int $PropertyId
	 * @return Int
	 * 
	 */
	function getAssignedPropertyUserId($PropertyId)
	{				
		$Qry1	=	"SELECT assigned_user_id 
					FROM propertyassign_relation 
					WHERE property_id = '$PropertyId' AND accepted = 'Y' AND declined = 'N' AND assigned_role = 'PROP_BROKER'";
		$Row1	=	$this->db->get_row($Qry1, ARRAY_A);

		$assigned_user_id	=	trim($Row1['assigned_user_id']);
		
		if($assigned_user_id != '') #If Broker Exists
			return array($assigned_user_id, 'BROKER');
		
		$Qry2		=	"SELECT user_id FROM flyer_data_basic WHERE album_id = '$PropertyId'";
		$Row2		=	$this->db->get_row($Qry2, ARRAY_A);
		$user_id	=	$Row2['user_id'];
		
		return array($user_id, 'OWNER');
	}
	
	
	
	/**
	 * The following method returns the assigned property user id if that property assigned to a broker, else returns the property owner id
	 *
	 * @created 02/05/2008
	 * @author Vipin Vijayan
	 * @param Int $PropertyId
	 * @return Int
	 * 
	 */
	function getAssignedPropertyUserIdManager($PropertyId)
	{				
		$Qry1	=	"SELECT assigned_user_id 
					FROM propertyassign_relation 
					WHERE property_id = '$PropertyId' AND accepted = 'Y' AND declined = 'N' AND assigned_role = 'PROP_MANAGER'";
		$Row1	=	$this->db->get_row($Qry1, ARRAY_A);

		$assigned_user_id	=	trim($Row1['assigned_user_id']);
		
		if($assigned_user_id != '') #If Manager Exists
			return array($assigned_user_id, 'MANAGER');
		
		$Qry2		=	"SELECT user_id FROM flyer_data_basic WHERE album_id = '$PropertyId'";
		$Row2		=	$this->db->get_row($Qry2, ARRAY_A);
		$user_id	=	$Row2['user_id'];
		
		return array($user_id, 'OWNER');
	}
	
	
	
	/**
	 * The following method returns the next invoice Number baed on the invoices table 
	 *
	 * @author vimson@newagesmb.com
	 * 
	 * @return String [with a format of DDMMYYYY{Incremental Number of a Day} ]
	 * 
	 */
	function getNextInvoiceNumber()
	{
		$Qry	=	"SELECT COUNT(*) + 1 AS NextInvoiceNumber FROM invoices WHERE DATE(created_time) = CURDATE()";
		$Row	=	$this->db->get_row($Qry, ARRAY_A);
		
		$NextInvoiceNumber	=	$Row['NextInvoiceNumber'];
		$NextInvoiceNumber	=	str_pad($NextInvoiceNumber, 4, '0', STR_PAD_LEFT);
		
		$Day			=	str_pad(date('d'), 2, '0', STR_PAD_LEFT);
		$Month			=	str_pad(date('m'), 2, '0', STR_PAD_LEFT);
		$Year			=	str_pad(date('Y'), 4, '0', STR_PAD_LEFT);
		$InvoiceNumber	=	$Day.$Month.$Year.$NextInvoiceNumber;
		
		return $InvoiceNumber;
	}
	
	/**
	 * The followinng method saves the booking informations after successful payment
	 *
	 * @return unknown
	 * updated on 02-apr-2008 by vipin
	 * adding manager commission
	 */
	function saveBookingInformations($POST, $PropertyId, $CheckIn, $CheckOut,$MemberId, $PaymentObj, $objUser,$totalamount)
	{
		
		
		$LogFileName	=	SITE_PATH.'/tmp/logs/'.'paypal_'.date('Y').date('m').'.log';
		$LogFileName1	=	SITE_PATH.'/tmp/logs/'.'test.log';
		$req = 'cmd=_notify-validate';
		foreach ($POST as $key => $value) {
			$value = urlencode(stripslashes($value));
			$req .= "&$key=$value";
		}
		$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
		#$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
		$fp = fsockopen ($this->config['paypal_socket_address'], 80, $errno, $errstr, 30);
						
		$ItemName			=	$POST['item_name'];
		$ItemNumber			=	$POST['item_number'];
		$PaymentStatus		=	$POST['payment_status'];
		$PaymentAmount		=	$POST['mc_gross'];
		$TransactionId		=	$POST['txn_id'];
		$ReceiverMail		=	$POST['receiver_email'];
		$PayerMail			=	$POST['payer_email'];
		
		
		if (!$fp) {
			;
		} else {
			fputs ($fp, $header . $req);
			while (!feof($fp)) {
				$res	= 	fgets ($fp, 1024);
				if (strcmp ($res, "VERIFIED") == 0) {
					$fpp = fopen($LogFileName, "a+");
					fwrite($fpp, $_SESSION['memberid']."VERIFIED:payment_status:".$PaymentStatus."|"."txn_id:".$TransactionId."|"."payment_amount:".$PaymentAmount."|".$ItemName." - ".date("d-m-Y H:i:s")."\n".$req."\n");
					fclose($fpp);
					
					if($PaymentStatus == 'Completed') {
						
						list($BrokerUserId, $Role1)	=	$this->getAssignedPropertyUserId($PropertyId);
						list($ManagerUserId, $Role2)	=	$this->getAssignedPropertyUserIdManager($PropertyId);
							
						$PropertyOwnerId				=	$this->getPropertyOwnerIdFromPropertyId($PropertyId);
						$CurrentTime					=	date('Y-m-d H:i:s');
						
						$fp1 = fopen($LogFileName1, "a+");
					fwrite($fp1, "property_id".$PropertyId."|"."Manager_id".$ManagerUserId."|"."Broker_id".$BrokerUserId);
					fclose($fp1);
						
						
						
						
						
						$InsertArray		=	array(
													'album_id'			=>	$PropertyId,
													'broker_id'			=>	$BrokerUserId,
													'manager_id'		=>	$ManagerUserId,
													'user_id'			=>	$MemberId,
													'date_booked'		=>	$CurrentTime,
													'start_date'		=>	$CheckIn,
													'end_date'			=>	$CheckOut,
													'amountpaid'		=>	$PaymentAmount,
													'owner_id'			=>	$PropertyOwnerId,
													'totalamount'       =>  $totalamount
												);
						$this->db->insert('album_booking',$InsertArray);
						
						$TransInsArray	=	array(
												'member_id'			=>	$MemberId,
												'sentto_id'			=>	$PropertyOwnerId,
												'transaction_date'	=>	$CurrentTime,
												'trans_description'	=>	$ItemName,
												'trans_amount'		=>	$PaymentAmount,
												'transaction_id'	=>	$TransactionId
											);
						$this->db->insert('member_transactiondetails', $TransInsArray);
						
						
									
						if($Role1 == 'BROKER') {
							$InvoiceNumber		=	$this->getNextInvoiceNumber();
							$BrokerDetails		=	$objUser->getUserdetails($BrokerUserId);
							$broker_commision	=	(float)$BrokerDetails['broker_commision'];
							
							$CommisionAmount	=	0;
							if($broker_commision > 0)
								$CommisionAmount	=	(float)(($PaymentAmount * $broker_commision) / 100);
							$CommisionAmount		=	round($CommisionAmount, 2);
							
							if($CommisionAmount > 0) {
								$InvInsArray	=	array(
													'invoice_number'		=>	$InvoiceNumber,
													'album_id'				=>	$PropertyId,
													'created_time'			=>	$CurrentTime,
													'sentby_id'				=>	$BrokerUserId,
													'sentto_id'				=>	$PropertyOwnerId,
													'invoice_amount'		=>	$CommisionAmount,
													'invoice_description'	=>	'Broker Commision',
													'invoice_type'			=>	'BROKER_COMMISION',
													'invoice_status'		=>	'PENDING'
												);
								$this->db->insert('invoices', $InvInsArray);
							}
						}
						//updated by vipin for manager commission
						if($Role2 == 'MANAGER') {
							$InvoiceNumber		=	$this->getNextInvoiceNumber();
							$ManagerDetails		=	$objUser->getUserdetails($ManagerUserId);
							$manager_commision	=	(float)$ManagerDetails['manager_commision'];
							
							$CommisionAmount	=	0;
							if($manager_commision > 0)
								$CommisionAmount	=	(float)(($PaymentAmount * $manager_commision) / 100);
							$CommisionAmount		=	round($CommisionAmount, 2);
							
							if($CommisionAmount > 0) {
								$InvInsArray	=	array(
													'invoice_number'		=>	$InvoiceNumber,
													'album_id'				=>	$PropertyId,
													'created_time'			=>	$CurrentTime,
													'sentby_id'				=>	$ManagerUserId,
													'sentto_id'				=>	$PropertyOwnerId,
													'invoice_amount'		=>	$CommisionAmount,
													'invoice_description'	=>	'Manager Commision',
													'invoice_type'			=>	'MANAGER_COMMISION',
													'invoice_status'		=>	'PENDING'
												);
								$this->db->insert('invoices', $InvInsArray);
							}
						}
						//
						
						$CommisionAmt	=	($PaymentAmount * $this->config['seller_commision_perc']) / 100;
						$CommisionAmt	=	round($CommisionAmt, 2);
						
						$Qry1	=	"UPDATE member_paymentdetails SET commnamount_balnce = commnamount_balnce + $CommisionAmt WHERE memberid = '$PropertyOwnerId'";
						$this->db->query($Qry1);
						
						$Msg	=	'Commision Amount - Property Number:'.$PropertyId;
						$PaymentObj->withdrawCommisionAmount($CommisionAmt, $PropertyOwnerId, $Msg);
						
						
						/**
						 * 
						 * mail send start 
						 */
						
						return TRUE;
						
					}#Close if Completed
					
				} else if (strcmp ($res, "INVALID") == 0) {
					$fpp = fopen($LogFileName, "a+");
					$PostStr	=	var_export($POST, true)."\n".date('Y-m-d H:i:s')."\n".$res."\n";
					fwrite($fpp, $PostStr);
					fclose($fpp);
				}
			}
			fclose ($fp);
		}
		
	}
	
	
	/**
	 * The following method returns the property booking details for admin side property booking listings
	 *
	 * @author vimson@newagesmb.com
	 * @param Int $pageNo
	 * @param Int $limit
	 * @param String $params
	 * @param Const $output
	 * @param String $orderBy
	 * @return Array
	 */
	function getBookingDetails($pageNo, $limit = 20, $params='', $output = ARRAY_A, $orderBy)
	{
		$BookingList	=	array();
		
		$Qry	=	"SELECT 
						T1.date_booked AS date_booked,
						DATE(T1.start_date) AS start_date,
						DATE(T1.end_date) AS end_date,
						T1.amountpaid AS amountpaid,
						T2.title AS title, 
						T3.username AS seller_username,
						T4.username AS renter_username
					FROM album_booking AS T1 
					LEFT JOIN album_quantity_title AS T2 ON T2.id = T1.quantity_title_id 
					LEFT JOIN member_master AS T3 ON T3.id = T1.current_user_id 
					LEFT JOIN member_master AS T4 ON T4.id = T1.user_id ";			
		
		$BookingList  = $this->db->get_results_pagewise($Qry, $pageNo, $limit, $params, $output, $orderBy);

		return $BookingList;
		
	}
	
	
	
	/**
	 * The following method returns the invoices for listig at the admin side
	 *
	 * @author vimson@newagesmb.com
	 * @param Int $pageNo
	 * @param Int $limit
	 * @param String $params
	 * @param Constatnt $output
	 * @param String $orderBy
	 * @return Array
	 */
	function getInvoices($pageNo, $limit = 20, $params='', $output = ARRAY_A, $orderBy)
	{
		
		$Qry	=	"SELECT 
						T1.invoice_number,
						DATE(T1.created_time) AS created_time,
						T2.username AS senterusername,
						T3.username AS receiverusername,
						T1.invoice_amount AS invoice_amount,
						T1.invoice_type AS invoice_type,
						T1.invoice_status AS invoice_status
					FROM invoices AS T1 
					LEFT JOIN member_master AS T2 ON T2.id = T1.sentby_id 
					LEFT JOIN member_master AS T3 ON T3.id = T1.sentto_id 
					WHERE T1.deleted = 0 ";
		$InvoiceList  = $this->db->get_results_pagewise($Qry, $pageNo, $limit, $params, $output, $orderBy);
		
		return $InvoiceList;
	}
	
	
	/**
	 * The following method returns the Deposits for listig at the admin side
	 *
	 * @author vimson@newagesmb.com
	 * @param Int $pageNo
	 * @param Int $limit
	 * @param String $params
	 * @param Constatnt $output
	 * @param String $orderBy
	 * @return Array
	 */
	function getDeposits($pageNo, $limit = 20, $params='', $output = ARRAY_A, $orderBy)
	{
		
		$Qry	=	"SELECT 
						T2.username AS depositor,
						T3.username AS receiver,
						T1.deposit_amount AS deposit_amount,
						DATE(T1.deposite_date) AS deposite_date,
						T1.deposite_for AS deposite_for
					FROM member_deposits AS T1 
					LEFT JOIN member_master AS T2 ON T2.id = T1.depositor_id 
					LEFT JOIN member_master AS T3 ON T3.id = T1.receiver_id ";
		$Deposits  = $this->db->get_results_pagewise($Qry, $pageNo, $limit, $params, $output, $orderBy);
		
		return $Deposits;
	}
	
	
	/**
	 * The following method returns the Transactions for listig at the admin side
	 *
	 * @author vimson@newagesmb.com
	 * @param Int $pageNo
	 * @param Int $limit
	 * @param String $params
	 * @param Constatnt $output
	 * @param String $orderBy
	 * @return Array
	 */
	function getTransactions($pageNo, $limit = 20, $params='', $output = ARRAY_A, $orderBy)
	{
		
		$Qry	=	"SELECT 
						T2.username AS sender,
						CASE T1.sentto_id WHEN 0 THEN 'admin' ELSE T3.username END AS receiver,
						DATE(T1.transaction_date) AS transaction_date,
						T1.trans_description AS trans_description,
						T1.trans_amount AS trans_amount,
						T1.transaction_id AS transaction_id
					FROM member_transactiondetails AS T1 
					LEFT JOIN member_master AS T2 ON T2.id = T1.member_id 
					LEFT JOIN member_master AS T3 ON T3.id = T1.sentto_id ";
		$Transactions  = $this->db->get_results_pagewise($Qry, $pageNo, $limit, $params, $output, $orderBy);
		
		return $Transactions;
	}
	/**
	 *  Block validation
	 * Created:Afsal
	 */
	function getFixedBlockDates($propid,$startDt,$endDt,$objEvents = ""){
		
		if($propid > 0){
				
				$startDt = $objEvents->startBlockDateCalculation($startDt,$propid);
				$rs= $this->getPropertyPricing($startDt,$endDt,$propid);
				
				if(count($rs)){
					$j=0;
					$arrOut = array();
					$arrFree = array();
					
						foreach($rs as $row){
							
							$arrFree[$j]["start_date"] = $row["start_date"];
							$arrFree[$j]["end_date"] = $row["rental_end_date"];
							
							$DURATION = $row["duration"];
							if($row["unit"] == "week") {
							   $DURATION = $DURATION* 7;           /* 1 Week == 7 days */
						    }
							
						    if($row["unit"] == "month") {
							   $DURATION = $DURATION* 28;		   /* 1 Month == 4 Week == 28 days */
						    }
							    
						    if($row["unit"] == "year") {
							   $DURATION = $DURATION* 336;		   /* 1 Year  == 12 Month ==  48 Week == 336 days */
						    }
							
						    	
						    	$startDt_Sec = strtotime($row["start_date"]);
						    	$i = strtotime($row["start_date"]);
						    	
						    	
						    
						    	while($i<=strtotime($endDt)) {
						    		
						    		$startDt_Sec =  date("Y-m-d",$i);
						    		
						    		$endDt_Tm    =  $i+ ( ($DURATION-1)*86400);
						    		
						    		$endDt_Sec   =  date("Y-m-d",$endDt_Tm);
						    		 
						    		
						    		
						    		if ($this->dateChecking($startDt_Sec,$startDt,1)){
						    			
										$arrOut[$j]["start_date"] = $startDt_Sec;
							    		$arrOut[$j]["end_date"] = $endDt_Sec;
							    		$arrOut[$j]["price"] = $row["price"];
							    		$arrOut[$j]["color"] = $row["color_code"];
							    		
						    		}
							    	
						    		if($row["rental_end_date"] != $endDt){
						    			
						    			if($this->dateChecking($endDt_Sec,$row["rental_end_date"],1)){
						   					
						    				break;
						    			}
						    		 }
						    		
						    		$i = $i+ ($DURATION*86400); 	
						    		$j++;	
						    	
						    	}
						    	$j++;
						}
						
						return $arrOut;
						
			}
		    	
		 }
	}
	/**
	 * Tow date chkeck greater or lessthan
	 * Created: Afsa;
	 * Dated:03/Apr/2008
	 * Modified:03/Apr/2008
	 */
	function dateChecking($date1,$date2,$num){
		
		$date1_array = explode("-",$date1);
		$date2_array = explode("-",$date2);
		
		$timestamp1  = mktime(0,0,0,$date1_array[1],$date1_array[2],$date1_array[0]);
		$timestamp2  = mktime(0,0,0,$date2_array[1],$date2_array[2],$date2_array[0]);
		
		if($num ==1){
			if($timestamp1 >= $timestamp2){
				return true;
			}
		}else{
		
			if($timestamp1<= $timestamp2){
				return true;
			}
		}
	}
	/**
	 * Calculate the free floating date from the date range
	 *
	 * @param unknown_type $startDt
	 * @param unknown_type $endDt
	 * @param unknown_type $propid
	 * @return date array
	 * created:afsal
	 * Date:05/Apr/2008
	 * Modified::05/Apr/2008
	 */
	function getFreeFloatingDays($startDt,$endDt,$propid){
		
		$freeFloatDays = 0;
		$freeFloatDate = array();
		$singleFreeFloatDate = array();
		
		$rsFixed = $this->getPropertyPricing($startDt,$endDt,$propid);
		
		$rsFree = $this->getFreefloatingPrice($propid);
		
		$k=0;
		$m = 0;
		$p=0;
		$i=strtotime($startDt);
		$j=strtotime($endDt);
				
		while($i<=strtotime($endDt)){
			
			foreach ($rsFixed as $row){
				
				$date1 = strtotime($row["start_date"]);
	
				$date2 = strtotime($row["rental_end_date"]);
				
					while($i <$date1){
						
						
						$m=$this->endFreeFloatingDate($rsFree["unit"],$rsFree["duration"],$i);
						
						if($m >= $date1){
							//Store single date array
							$singleFreeFloatDate[]= date("Y-m-d",$i);
							$p++;
						}else{
							
							$freeFloatDate[$k]["start_date"]= date("Y-m-d",$i);
							$i=$this->endFreeFloatingDate($rsFree["unit"],$rsFree["duration"],$i);
							$freeFloatDate[$k]["end_date"]= date("Y-m-d",$i);
							$freeFloatDate[$k]["price"]= "NA";
							$freeFloatDate[$k]["color"]= "NA";
							
						}
						
						$i=strtotime("+1 day",$i);
						$k++;
						
					}
						//*** Fixed block end date less than 
						if($date2 <$j){
						$i=strtotime("+1 day",$date2);
						
						}
					$k++;
			}
			
		
			if($i >=$date1 && $i<= $date2)
			break;
		
			if($i<=$j){
				
				while($i <=$j){
					
					$m=$this->endFreeFloatingDate($rsFree["unit"],$rsFree["duration"],$i);
					
					if($m <=$j){
						
						
						$freeFloatDate[$k]["start_date"]= date("Y-m-d",$i);
						$i=$this->endFreeFloatingDate($rsFree["unit"],$rsFree["duration"],$i);
						$freeFloatDate[$k]["end_date"]= date("Y-m-d",$i);
						$freeFloatDate[$k]["price"]= "NA";
						$freeFloatDate[$k]["color"]= "NA";
					}else{

						$singleFreeFloatDate[]= date("Y-m-d",$i);
						$p++;
					}
					
					$i=strtotime("+1 day",$i);
					$k++;
				}
				break;
			}
		}
	
	//$this->setTheFreefloatingDays($freeFloatDate,$singleFreeFloatDate,$rsFree["duration"],$rsFree["unit"],$startDt,$propid);
	
	return array($freeFloatDate,$singleFreeFloatDate);	
	}
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $startDt
	 * @param unknown_type $endDt
	 * @param unknown_type $propid
	 * @return array
	 * created:afsal
	 * Date:05/Apr/2008
	 * Modified::05/Apr/2008
	 */
	function getPropertyPricing($startDt,$endDt,$propid){
		
		
		$SQL = "SELECT price,duration,start_date,rental_end_date,unit,color_code FROM property_pricing WHERE ((album_id = $propid) AND
					( 
						(start_date between  '$startDt' AND '$endDt')  
						OR 
						(rental_end_date between '$startDt' AND '$endDt') 
					))
					OR
					((album_id = $propid) AND
					(
						('$startDt' between  start_date AND rental_end_date)  
						OR 
						('$endDt' between start_date AND rental_end_date) 
					)) ORDER BY start_date";
		
		$rs = $this->db->get_results($SQL,ARRAY_A);
		
		return $rs;
	}
	/**
	 * Display the Booking Charges...
	 *Created:Afsal
	 * Date:09/Apr/2008
	 * Modify:10/Apr/2008
	 * @param unknown_type $startDt
	 * @param unknown_type $endDt
	 * @param unknown_type $propid
	 */
	function displayBookingCharges($startDt,$endDt,$propid,$booking_percent,$objCalendarEvents){
		
		
		$strHtm = "";
		$rsFixed = $this->getFixedBlockDates($propid,$startDt,$endDt,$objCalendarEvents);
		list($rsFree,$rsFreeSing)	 =	$this->getFreeFloatingDays($startDt,$endDt,$propid);
		$freeFloat 	 =  $this->getFreefloatingPrice($propid);
		$checkFreeFloatDays = true;
		
		
		if(count($rsFixed) <=0)
		$priceArray = $rsFree;
		
		if(count($rsFree) <=0)
		$priceArray = $rsFixed;
		
		if(count($rsFixed) && count($rsFree)){
			
			$mergeArray = array_merge($rsFixed,$rsFree);
			$priceArray = $this->orderByArrayAsDate($mergeArray);
		}
		
		
		/// Check the free float duration and number of free floating days are matching
	
		if($freeFloat["duration"]==1 && strtolower($freeFloat["unit"] == "day")){
		}else{
			if(!$this->calculateNumberOfFloattingDays($rsFree,$rsFreeSing,$freeFloat["duration"],$freeFloat["unit"]))
			$checkFreeFloatDays = false;
		}
		
		//Check the minimum rental duration
			
			if(count($rsFixed) <=0){
				
				$minimum_rental_duration = $freeFloat["min_duration"];
				$minimum_rental_unit     = $freeFloat["unit"];
				
				 if($minimum_rental_duration > 0){
				 	
				 	$total_free_float_days = $this->calculateNumberOfFloattingDays($rsFree,$rsFreeSing,$freeFloat["duration"],$freeFloat["unit"],false);
				 	
				 	if(!$this->minimumRentalDuration($freeFloatDate,$minimum_rental_duration,$minimum_rental_unit,$total_free_float_days)){
				 	
				 		$message = str_replace("%","$minimum_rental_duration  $minimum_rental_unit's",$this->MOD_VARIABLES["MOD_LABELS"]["LBL_PROP_MSG5"]);
				 		echo  $message."|"."error";
				 		exit;
				 	}

				 }
				
			}
		// Checking the display order
		
		$displayOrderHtm = "";
		
		if(count($rsFreeSing) >0){
			//$key = array_keys($rsFreeSing);
			$startDate1 = strtotime($rsFreeSing[0]["start_date"]);
		}
		
		
		/*
		if(count($rsFixed) >0){
			$key = array_keys($rsFixed);
			$startDate2	  = strtotime($rsFixed[$key[0]]["start_date"]);
		}
		*/
		
			$class1 = "border-full-top-less";
			$class2 = "borde-left-less-top-less";
			$class3 = "borde-right-less-top-less";
			
		
		/* Free floating single date  listing */
		if(count($rsFreeSing)){
			foreach ($rsFreeSing as $rsRow){
				
				$singleFreeDate []=date($this->config["date_format_new"],strtotime($rsRow));
			}
			$singleFreeDate = implode("<b>,</b>",$singleFreeDate);
		}
		
		/* Calculate single freefloat price*/
		
		if(count($rsFreeSing)){
			
			$sing_date_array_price = $this->calculateSingleFreeFloatingPrice($rsFreeSing,$freeFloat["duration"],$freeFloat["unit"],$freeFloat["price"]);
		}
		
		//if($checkFreeFloatDays == true){
	
					if(count($rsFixed)>0 || count($rsFree)>0 || count($rsFreeSing)){
					$strHtm  = '<div style="height:100%;">';	
					$strHtm  = '<div class="floatleft borde-right-less" style="width:30%;"><span class="bodytextbold">From</span></div>';
					$strHtm .= '<div class="floatleft border-full" style="width:30%;"><span class="bodytextbold">To</span></div>';
					$strHtm .= '<div class="floatleft borde-left-less" style="width:35%;text-align:right;"><span class="bodytextbold">Amount</span></div>';
					
					if(count($priceArray)){
						
						//Fixed floating price printing row START
						$amountArray = array();
						$j=0;
						foreach ($priceArray as $row){
							
							
							
									/* From the check-in and chekc-out date calculating the week end day. */	
									if($freeFloat["duration"]== "1" &&  strtolower($freeFloat["unit"])=="day"){
									
										$arrayWeekeEndDay = $this->calculateWeekEndDays($rsFree);
										
									}
									
									$back_color = "";
									$special_price = 0;
									$freeFloatPrice = $freeFloat["price"];
									
									
									
								/*
								This part calculating the special price day existing
								if special day then claculating the specila price. 
								*/		
								if($row["price"] == "NA"){
										
											$row["price"]   = $freeFloatPrice;
										if(count($arrayWeekeEndDay)){
											
											if(in_array(strtotime($row["start_date"]),$arrayWeekeEndDay)){
												
												$special_price = $this->calculateWeekEndPrice($propid,$freeFloatPrice);
												
												// if special price set for week end day
												if(abs($special_price) > 0){
													
													/* Weekend day price */
													$row["price"] = number_format($special_price,2);
												
													$back_color = "background-color:#CFD5F5;";
												}
												
											}else {
												
												/* Normal price */
												$row["price"] = $freeFloatPrice;
												
											}
										}
								}

							
	
					$strHtm1 .= '<div class="floatleft '.$class3.'" style="width:30%;'.$back_color.'"><span class="bodytext">'.date($this->config["date_format_new"],strtotime($row["start_date"])).'</span></div>';
					$strHtm1 .= '<div class="floatleft '.$class1.'" style="width:30%;'.$back_color.'"><span class="bodytext">'.date($this->config["date_format_new"],strtotime($row["end_date"])).'</span></div>';
					$strHtm1 .= '<div class="floatleft '.$class2.'" style="width:35%;'.$back_color.'text-align:right;"><span class="bodytext">$'.$row["price"].'</span></div>';
						
						/* Fixed floating  amount adding to array */
						$amountArray [] = $row["price"];	
								
						
						$j++;
						}
					}   //Fixed floating price printing row END
					

					/* Non continues days listed */
					if(count($rsFreeSing)){
						
						
					$strHtm3 .= '<div class="floatleft border-full-top-less" style="width:97.6%;'.$back_color.'"><div class="floatleft bodytext" style="width:60%;">';
						
					$strHtm3 .= $singleFreeDate;
						
					$strHtm3 .='</div><div class="floatright bodytext">$'.$sing_date_array_price.'</div></div>';
					
						$amountArray [] = $sing_date_array_price;
						
					}
					
					$bookingCharge = $this->calculateBookingCharges(array_sum($amountArray),$booking_percent);
					
					$strHtm4 .= '<div style="clear:both;height:15px;"><!-- --></div>';
					$strHtm4 .= '<div class="floatleft" style="width:98%;text-align:left;"><span class="blocktitle">'.$this->MOD_VARIABLES["MOD_LABELS"]["LBL_CHECK_OUT_DATE"].'</span><span class="bodytextbold"> : '.date($this->config["date_format_new"],strtotime("$endDt +1 day")).'</span></div>';	
					$strHtm4 .= '<div style="clear:both;height:15px;"><!-- --></div>';
					$strHtm4 .= '<div class="floatleft" style="width:98%;text-align:left;"><span class="blocktitle">'.$this->MOD_VARIABLES["MOD_LABELS"]["LBL_CHECK_OUT_MESSAGE"].'</span><span class="bodytextbold"> <img src='.SITE_URL.'/modules/'.$_REQUEST['mod'].'/images/icon_small_info.gif onmouseover="showPopup(250,50)"onmouseout="hidePopup()";></span></div>';
					$strHtm4 .= '<div style="clear:both;height:2px;"><!-- --></div>';	
					$strHtm4 .= '<div class="floatleft" id="popupcontent">'.$this->MOD_VARIABLES["MOD_LABELS"]["LBL_POPUP_MESSAGE"].'</div>';
					$strHtm4 .= '<div  style="clear:both;height:5px;"><!-- --></div>';
					$strHtm4 .= '<div class="floatleft" style="width:98%;text-align:right;"><span class="blocktitle">'. $this->MOD_VARIABLES["MOD_LABELS"]["LBL_BOOK_AMT"] .'</span><span class="bodytextbold">&nbsp;$&nbsp; '.number_format(array_sum($amountArray),2).'</span></div>';
					$strHtm4 .= '<div style="clear:both;height:10px;"><!-- --></div>';
					$strHtm4 .= '<div class="floatleft" style="width:98%;text-align:right;"><span class="blocktitle">'.$this->MOD_VARIABLES["MOD_LABELS"]["LBL_BOOK_CHARG"].'</span>&nbsp;&nbsp;(<span class="bodytext">&nbsp;'.array_sum($amountArray).'&nbsp;%&nbsp;'.number_format($booking_percent,0).'&nbsp; </span>)&nbsp;&nbsp;<span class="blocktitle">:</span> <span class="bodytextbold">&nbsp;$ '.number_format($bookingCharge,2).'</span></div>';
					$strHtm4 .= '</div>';
					
					$totAmount = array_sum($amountArray);
					
						if($startDate1 <= $startDate2){
							
							$displayOrderHtm = $strHtm.$strHtm3.$strHtm1.$strHtm4;
						}
						else {
							
						$displayOrderHtm = $strHtm.$strHtm1.$strHtm3.$strHtm4;
						}
						
						///return $displayOrderHtm."|".$bookingCharge."|".$totAmount;
						return array($displayOrderHtm,$bookingCharge,$totAmount);
						
					} //Free floating price printing row END
		
		//}else{
					// if the free floating price not achieve the no of duration 
				//	return $this->MOD_VARIABLES["MOD_LABELS"]["LBL_PROP_MSG4"]."|"."error";
					
		//}
	}
	/**
	 * Get free flating pricne
	 */
	function getFreefloatingPrice($propid){
		
		if($propid >0){
			
			$SQL = "SELECT price,duration,unit,booking_price,min_duration,min_units
					FROM property_pricing WHERE album_id=$propid AND default_val='Y'";
			
			$rsFree = $this->db->get_row($SQL,ARRAY_A);
			return $rsFree;
			
		}
		
	}
	function endFreeFloatingDate($UNIT,$DURATION,$i){
		
			$UNIT 		= strtolower($UNIT);
		
			if($UNIT == "day" && $DURATION==1){
				
				return $i;
			}else{
				
			
				if($UNIT == "week") {
				   $DURATION = $DURATION* 7;           /* 1 Week == 7 days */
				  
			    }
				
			    if($UNIT == "month") {
				   $DURATION = $DURATION* 28;		   /* 1 Month == 4 Week == 28 days */
			    }
				    
			    if($UNIT == "year") {
				   $DURATION = $DURATION* 336;		   /* 1 Year  == 12 Month ==  48 Week == 336 days */
			    }
			    $i = $i+ (($DURATION-1)*86400);
			    return $i;
			}
	}
	/**
	 * Get the booking %
	 *
	 * @param unknown_type $propid
	 * @return booking_price
	 */
	function getBookingCharges($propid){
		
		if($propid > 0){
		
			$SQL = "SELECT booking_price,min_duration,min_units FROM property_pricing
					WHERE album_id=$propid AND default_val='Y'";
			
			$rs = $this->db->get_row($SQL,ARRAY_A);
			return $rs["booking_price"] ;
			
		}
	}
	/**
	 * Calculate the booking charges
	 *
	 * @param unknown_type $amount
	 * @param unknown_type $booking_percent
	 * @return booking charges
	 * Created:Afsal
	 * Date:08/Apr/2008
	 * Modified:08/Apr/2008
	 */
	function calculateBookingCharges($amount,$booking_percent){
		
		if($booking_percent >0 && $amount >0){
			
			$amount = ($amount*$booking_percent)/100;
			
			return $amount;
		}else {
			return 0;
		}
	}
	/**
	 * Check the avialability of property
	 *
	 * Created:Afsal
	 * Date:08/Apr/2008
	 * Modified:08/Apr/2008
	 */
	function checktheAvialabiltyOfProperty($frmDate,$toDate,$propid){
		
		$SQL = "SELECT id FROM property_pricing AS b
				 
				 WHERE ((album_id = $propid AND auction ='Y') AND
				( 
					(start_date between  '$frmDate' AND '$toDate')  
					OR 
					(rental_end_date between '$frmDate' AND '$toDate') 
				))
				OR
				((album_id = $propid AND auction ='Y') AND
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

		$rs = $this->db->get_results($SQL,ARRAY_A);
		
		if(count($rs) > 0){
			return true;
		}else{
			return false;
		}
	}
	/**
	 * Check the propertybooking minimum and maximum date
	 * Created:Afsal
	 * Date:08/Apr/2008
	 * Modified:08/Apr/2008
	 */
	function bookingMinimumAndMaximumDate($propid,$check_in,$minimum_day,$maximun_day){
		
		$current_date = date("Y-m-d");
		
		
		$rs = get_time_difference($current_date,$check_in);
		
		if($minimum_day > 0){
			if($rs["days"] < $minimum_day){
				return "0";
			}
		}
		
		if($maximun_day >0){
			
			if($rs["days"] > $maximun_day){
				return "1";
			}
			
		}
	}
	/**
	 * Get the flyer_data_basic table limited fields
	 * Created:Afsal
	 * Date:08/Apr/2008
	 * Modified:08/Apr/2008
	*/
	function getFlyerDet($propid){
		
		if($propid >0)	{
			
			$SQL = "SELECT minimum_booking_days,maximum_booking_days FROM flyer_data_basic WHERE album_id=$propid";
			$rs  = $this->db->get_row($SQL,ARRAY_A);
			
			return $rs;
		}
	}
	/**
	 * Calculate the week end day
	 * Created:Afsal
	 * Date:08/Apr/2008
	 * Modified:08/Apr/2008
	 */
	function calculateWeekEndDays($arrayDays){
		
		$WeekEndDaysArray	=	array('Sat', 'Sun');
		$WeeEndDate			=	array();
	
		if (count($arrayDays)){
			
			foreach ($arrayDays as $row){
					
				if(in_array(date("D",strtotime($row["start_date"])),$WeekEndDaysArray)){
				
					$WeeEndDate[] = strtotime($row["start_date"]);
				}
			}	
			
			return $WeeEndDate;
		}
	}
	/**
	 * Calculate week end price
	 * Created:Afsal
	 * Date:09/Apr/2008
	 * Modified:09/Apr/2008
	 * @return special price
	 */
	function calculateWeekEndPrice($PropertyId,$BasicPrice){
		
			$SpecialPriceDetails = $this->getSpecialPriceOfParticularDate($PropertyId);
		
			if($SpecialPriceDetails["Price"] !='NA' && abs($SpecialPriceDetails['Price'])>0){
			
				if($SpecialPriceDetails['type'] == 'pr') {
					$BookingPriceForPeriod	=	$BasicPrice + $SpecialPriceDetails['Price'];
					$BookingPriceForPeriod	=	round($BookingPriceForPeriod, 2);
				}
				
				if($SpecialPriceDetails['type'] == 'pe') {
					
					$BookingPriceForPeriod	=	$BasicPrice + (($SpecialPriceDetails['Price'] * $BasicPrice) / 100);
					$BookingPriceForPeriod	=	round($BookingPriceForPeriod, 2);
				}
			}

			return $BookingPriceForPeriod;
	}
	/**
	 * Calculate no of freefloating days
	 * 
	 */
	function calculateNumberOfFloattingDays($freeFloatDayArray,$singleDateArray,$duration,$unit,$type=true){
		
	

			if (count($freeFloatDayArray)) {
				
				foreach ($freeFloatDayArray as $row1){
					$arryDate = get_time_difference($row1["start_date"],$row1["end_date"]);
					$date_diff = $date_diff+ ($arryDate["days"]+1);
				}
			}
			
			if($type == true){
				
				if(count($singleDateArray)){
						$date_diff = $date_diff + count($singleDateArray);
				}
				$unit = strtolower($unit);
				
				if($unit == "week"){
					$duration = $duration*7;
				}elseif($unit == "month"){
					$duration = $duration*28;
				}elseif($unit == "year"){
					$duration = $duration*(28*12);
				}
				
				//echo ($date_diff%2);
				if($date_diff > 0){
					
					if(($date_diff%$duration)==0)
					return true;
					else 
					return false;
				}else {
					return true;
				}
				
			
		}else{
			
			return $date_diff;
		}
		
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $freeFloatDate
	 * @param unknown_type $propid
	 * @return unknown
	 */
	function minimumRentalDuration($freeFloatDate,$minimum_rental_duration,$minimum_rental_unit,$total_free_float_days){
		
	
		if($minimum_rental_duration > 0 && $total_free_float_days >0){
			
			$unit =  strtolower($minimum_rental_unit);
			
			if($unit == "week"){
				$minimum_rental_duration = $minimum_rental_duration*7;
			}elseif($unit == "month"){
				$minimum_rental_duration = $minimum_rental_duration*28;
			}elseif($unit == "year"){
				$minimum_rental_duration = $minimum_rental_duration*(28*12);
			}
			
			if($total_free_float_days<$minimum_rental_duration){
				
				return false;
			}else{
				
				return true;
			}
			
		}else{
				return true;
		}
			
		
		//if(count($freeFloatDate) && )
	}
	/**
	 * Calculate singledate price calculation
	 *
	 * @param unknown_type $rsFreefloatArray
	 * @param unknown_type $duration
	 * @param unknown_type $unit
	 */
	function calculateSingleFreeFloatingPrice($rsFreefloatArray,$duration,$unit,$freeFloatPrice){
		
		$unit = strtolower($unit);
		
		
		if($unit == "week"){
			
			$duration = $duration * 7;
			
			
			$div_days = round(count($rsFreefloatArray)/$duration);
			$sing_date_array_price = $div_days*$freeFloatPrice;
			
			return $sing_date_array_price;
			
		}elseif($unit == "month"){
			
			$duration = $duration * 28;
			$div_days = round(count($rsFreefloatArray)/$duration);
			$sing_date_array_price = $div_days*$freeFloatPrice;
			
		}elseif($unit == "year"){
			
			$duration = $duration * (28*12);
			$div_days = round(count($rsFreefloatArray)/$duration);
			$sing_date_array_price = $div_days*$freeFloatPrice;
			
		}else {
			
			$div_days = round(count($rsFreefloatArray)/$duration);
			$sing_date_array_price = $div_days*$freeFloatPrice;
		}
		
		return $sing_date_array_price;
	}
	/**
	 * check check-in date greater than checkout
	 * 
	 */
	function checkInDateGreater($checkin,$checkout){
		
		if(strtotime($checkin)>strtotime($checkout)){
			return false;
		}else{
			return true;
		}
		
	}
	/**
	 * Check the property booked between the dates
	 *
	 */
	function checkPropertyBookingBetweenDate($frmDate,$toDate,$propid){
		
		if($propid > 0){
			
			$SQL = "SELECT DISTINCT 'propertyBook' AS eventType,b.album_id,f.quantity,count(b.album_id) FROM album_booking AS b
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
					) GROUP BY b.album_id
					HAVING quantity <= count(b.album_id) AND album_id=$propid";
			

			$rs = $this->db->get_results($SQL,ARRAY_A);
			
			if(count($rs) >0)
			return false;
			else 
			return true;
			
		}
	}
	
	
	/**
	 * The following  method returns the Bid Payment details
	 *
	 * @author vimson@newagesmb.com
	 * @param Int $bid_id
	 * @return Array
	 */
	function getBidPaymentDetails($bid_id)
	{
		$BidDetails	=	array();
		
		$Qry0		=	"SELECT 
							COUNT(*) AS BidCount 
						FROM property_bid_select AS T1 
						LEFT JOIN property_bid AS T2 ON T2.id = T1.bid_id 
						LEFT JOIN property_pricing AS T3 ON T3.id = T2.pricing_id 
						LEFT JOIN album AS T4 ON T4.id = T3.album_id 
						LEFT JOIN member_paymentdetails AS T5 ON T5.memberid = T4.user_id 
						LEFT JOIN flyer_data_basic AS T6 ON T6.album_id = T3.album_id 
						WHERE T1.bid_id = '$bid_id' AND T1.status = 'Y' ";
		$BidRow		=	$this->db->get_row($Qry0, ARRAY_A);
		$BidCount	=	$BidRow['BidCount'];
			
		if($BidCount <= 0)
			return $BidDetails;	
		
		
		$Qry1		=	"SELECT 
							T1.*, 
							T2.user_id AS BidderId,
							T2.bid_amount AS bid_amount, 
							T3.album_id AS album_id,
							T5.memberid AS PropertyOwnerId,
							T5.paypal_account AS paypal_account, 
							T6.flyer_id AS flyer_id
						FROM property_bid_select AS T1 
						LEFT JOIN property_bid AS T2 ON T2.id = T1.bid_id 
						LEFT JOIN property_pricing AS T3 ON T3.id = T2.pricing_id 
						LEFT JOIN album AS T4 ON T4.id = T3.album_id 
						LEFT JOIN member_paymentdetails AS T5 ON T5.memberid = T4.user_id 
						LEFT JOIN flyer_data_basic AS T6 ON T6.album_id = T3.album_id 
						WHERE T1.bid_id = '$bid_id' AND T1.status = 'Y' LIMIT 1";
		$BidDetails	=	$this->db->get_row($Qry1, ARRAY_A);
		
		
		return $BidDetails;
			
	}
	
	
	/**
	 * The following method processes the Bid after successful bid payment
	 *
	 * @author vimson@newagesmb.com
	 * @param Post Array $POST
	 * Modified by adarsh on 18Apr 2008  (insetion to property_bid_payments table)
	 */
	function processBidPayment($POST)
	{
		$LogFileName	=	SITE_PATH.'/tmp/logs/'.'paypal_'.date('Y').date('m').'.log';

		$req = 'cmd=_notify-validate';
		foreach ($POST as $key => $value) {
			$value = urlencode(stripslashes($value));
			$req .= "&$key=$value";
		}
		$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
		$fp = fsockopen ($this->config['paypal_socket_address'], 80, $errno, $errstr, 30);
						
		$ItemName			=	$POST['item_name'];
		$ItemNumber			=	$POST['item_number'];
		$PaymentStatus		=	$POST['payment_status'];
		$PaymentAmount		=	$POST['mc_gross'];
		$TransactionId		=	$POST['txn_id'];
		$ReceiverMail		=	$POST['receiver_email'];
		$PayerMail			=	$POST['payer_email'];
		
		
		if (!$fp) {
			;
		} else {
			fputs($fp, $header . $req);
			while(!feof($fp)) {
				$res	= 	fgets ($fp, 1024);
				if(strcmp ($res, "VERIFIED") == 0) {
					$fpp = fopen($LogFileName, "a+");
					fwrite($fpp, $_SESSION['memberid']."VERIFIED:payment_status:".$PaymentStatus."|"."txn_id:".$TransactionId."|"."payment_amount:".$PaymentAmount."|".$ItemName." - ".date("d-m-Y H:i:s")."\n".$req."\n");
					fclose($fpp);
					
					if($PaymentStatus == 'Completed') {
						$bid_id			=	$_REQUEST['bid_id'];
						$bidder_id		=	$_REQUEST['bidder_id'];
						$prop_ownerid	=	$_REQUEST['prop_ownerid'];
						$InvoiceNumber	=	$this->getNextInvoiceNumber();
						
						$InvInsArray	=	array(
											'invoice_number'		=>	$InvoiceNumber,
											'created_time'			=>	date('Y-m-d H:i:s'),
											'sentby_id'				=>	$bidder_id,
											'sentto_id'				=>	$prop_ownerid,
											'invoice_amount'		=>	$PaymentAmount,
											'invoice_description'	=>	'Bid Amount For ' . $ItemName,
											'invoice_type'			=>	'BID_AMOUNT',
											'invoice_status'		=>	'PAID'
										);
						$this->db->insert('invoices', $InvInsArray);
						
						$bidInsArray   = array(
										 'bid_id'    	=>	$bid_id,
										 'payment_date' =>	date('Y-m-d H:i:s'),
										 'amount'		=>  $PaymentAmount
										);
						$this->db->insert('property_bid_payments', $bidInsArray);		
						
						$rs=$this->getPricingDetailsByBidId($bid_id);
						
						$albumBookingArray=array(
											'album_id'				=> $rs['album_id'],
											'quantity_title_id'     => '',
											'owner_id'				=> $prop_ownerid,
											'current_user_id'		=> '',
											'user_id'			 	=> $bidder_id,
											'date_booked'			=> $rs['bid_date'],
											'start_date'			=> $rs['start_date'],
											'end_date'				=> $rs['rental_end_date'],
											'amountpaid'			=> $PaymentAmount,
											'totalamount'			=> $PaymentAmount,
											'type'					=> 'bid'
										);
										
						$this->db->insert('property_bid_payments', $albumBookingArray);		
									
					}#Close if Completed
					
				} else if(strcmp ($res, "INVALID") == 0) {
					$fpp = fopen($LogFileName, "a+");
					$PostStr	=	var_export($POST, true)."\n".date('Y-m-d H:i:s')."\n".$res."\n";
					fwrite($fpp, $PostStr);
					fclose($fpp);
				}
			}
			fclose ($fp);
		}
		
		
		
	}
	
	function orderByArrayAsDate($array){
	
		$priceArray = array();
		
		if(count($array)){
			
			foreach ($array as $val){
				
				$key = strtotime($val["start_date"]);
				$priceArray[$key]["start_date"] = $val["start_date"];
				$priceArray[$key]["end_date"] = $val["end_date"];
				$priceArray[$key]["price"] = $val["price"];
				$priceArray[$key]["color"] = $val["color"];

			}
		}
		
		ksort($priceArray);

		return $priceArray;
	}
	function updatePropertyScore($albumid,$score)
	{
	 //$this->db->update("member_master",$arr,"id=$uid");
	$sql="update flyer_data_basic SET score ='$score' where album_id='$albumid'";
	
	  mysql_query($sql);
	
	}
	function updatePropertyRank()
	{
	
		 $sql ="select album_id,score from flyer_data_basic where publish ='Y'  ORDER BY score Desc";
		 $rs  =	$this->db->get_results($sql, ARRAY_A);
		 $i=0;
		if (count($rs))
		{ 
		  foreach ($rs   as $res)
		  {
			  $aid=$res['album_id'];
			  $pscore=$res['score'];
				$rank=$i+1;
				  if($i>1)
				  {
					  if($pscore[$i-1]==$pscore)
					  {
					   $rank=$i;
					  }else
						{
						$rank=$i+1;
						}
		
				  }
		      $sql="update flyer_data_basic SET rank ='$rank' where album_id='$aid'";
		      mysql_query($sql);
			  $i++;
		  }
		  
		  $qry="update flyer_data_basic SET rank =0 where score=0";
	      mysql_query($qry);
		} 
				
	}
	
	function setTheFreefloatingDays($freeFloatDate,$singleFreeFloatDate,$duration1,$unit,$startDt,$propid,$endDate){
		
		
	 $objEVENTS	=	new CalendarEvents();
	
	 $duration = $duration1;
	 $noFreeFloatDays = 0;
	 $noOfDuration = 0;
	 $totFixedDays = 0;
	 
	
			$unit = strtolower($unit);
			
		if($unit == "week"){
			
			$duration = $duration * 7;
			
			
		}elseif($unit == "month"){
			
			$duration = $duration * 28;

			
		}elseif($unit == "year"){
			
			$duration = $duration * (28*12);
			
		}else {
			
			$duration = $duration * 1;
		}
		
		if(count($freeFloatDate)){
			
			$noFreeFloatDays = count($freeFloatDate)*$duration;
		}
		
		
		
		if(count($singleFreeFloatDate)){
			$noFreeFloatDays = $noFreeFloatDays + count($singleFreeFloatDate);
		}
		
		//print_r($noFreeFloatDays)."<br>";
		
		if(($noFreeFloatDays%$duration) == 0){
		
			
		return $endDate;
		}
		else
		{
			$noOfDuration = $noFreeFloatDays/$duration;
			
			
			
			if($noOfDuration <1)
			$noOfDuration = 1;
			else
			$noOfDuration = ceil($noOfDuration);
			
		}
		
		
		$noOfDuration = ($noOfDuration * $duration);
		
		$noOfDuration = $noOfDuration;
		
		$j=1;
		
		for($i=1;$i<$noOfDuration;$i++){
				
			$blck_end_date = $this->isFixedBlock($startDt,$propid);
			
			if($blck_end_date == "true"){
				
				   $startDt = date("Y-m-d",strtotime("$startDt +1 day"));
				 
			}else{
					$startDt = date("Y-m-d",strtotime("$blck_end_date +1 day"));
					$noOfDuration = $noOfDuration + 1;	
			}
		}
		$exactCheckdate =  $startDt;
		
		return  $exactCheckdate;
	
	}
	function isFixedBlock($startDate,$propid){
		
		if($startDate != "" && $propid > 0){
			
			
			$SQL = "SELECT id,start_date, DATE_FORMAT(rental_end_date,'%Y-%m-%d') AS endDate,unit,duration FROM property_pricing 
		        	WHERE '$startDate' between  DATE_FORMAT(start_date,'%Y-%m-%d') AND
					DATE_FORMAT(rental_end_date,'%Y-%m-%d') AND album_id =$propid";
			
			
			$row = $this->db->get_row($SQL,ARRAY_A);
			
		
			if(count($row) > 0)
			return  $row["endDate"];
			else
			return true;
		}
				
	}
	
	 /* This function retrieves Bid Details and pricing by b
  	 * Author   : Adarsh
  	 * Created  : 30/Apr/2008
  	 */
	function getPricingDetailsByBidId($bid_id)
	{
		$sql="select a.*,b.bid_date from property_pricing a inner join property_bid b on a.id=b.pricing_id where b.id='$bid_id'";
		$rs 		= 	$this->db->get_row($sql, ARRAY_A);
		return $rs;
	}
	
	function propertyStay($POST)
	{

	$InsertArray		=	array( 'suffix'		=>	$POST['h_suffix'],
								   'first_name'	=>	$POST['h_first_name'],
								   'member'		=>	$POST['h_member'],
								   'last_name'	=>	$POST['h_last_name'],
								   'phone'		=>	$POST['h_phone']);
									 
	$this->db->insert('book_stay_member',$InsertArray); 
	//	$sql="NSERT INTO book_stay_member (suffix,first_name,member,last_name,phone) VALUES ('','','','','')";
	//   mysql_query($sql);
	}
	
	/**
	 * Check the checkindate and checkoutdate not equal
	 *
	 * @param  $checkin
	 * @param  $checkout
	 * @return unknown
	 */
	
	function checkInDatecheckOutDateNotEqual($checkin,$checkout){
		
		if(strtotime($checkin)==strtotime($checkout)){
			return false;
		}else{
			return true;
		}
		
	}
}
?>