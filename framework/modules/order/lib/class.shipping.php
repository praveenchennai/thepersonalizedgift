<?php

/**
 *	The following method abstracts the shipping operations
 *
 *  @Filename class.shipping.php	
 *  @author newage
 *
 **/
 
require_once FRAMEWORK_PATH."/modules/order/lib/shippingconfig.php";


class Shipping extends FrameWork  
{	
	
	# UPS CONSTANT ARRAY SECTION #
	var $UPS_PICKUP_TYPES_ARRAY		=	array(  'Regular+Daily+Pickup' => 'Regular Daily Pickup',
												'On+Call+Air' => 'On Call Air',
												'One+Time+Pickup' => 'One Time Pickup',
												'Letter+Center' => 'Letter Center',
												'Customer+Counter' => 'Customer Counter'
											);
	
	
	var $UPS_PACKAGE_TYPES_ARRAY		=	array( '00'	=> 'Unknown',
											'01'	=> 'UPS Letter',
											'02'	=> 'My Packaging',
											'03'	=> 'UPS Tube',
											'04'	=> 'UPS Pak',
											'21'	=> 'UPS Express Box',
											'24'	=> 'UPS 25Kg Box &reg;',
											'25'	=> 'UPS 10Kg Box &reg;'
										);	
	
	var $UPS_SHIP_SERVICE_ARRAY		=	array( 
											'1DM' => 'Next Day Air Early AM',
											'1DML' => 'Next Day Air Early AM Letter',
											'1DA' => 'Next Day Air',
											'1DAL' => 'Next Day Air Letter',
											'1DP' => 'Next Day Air Saver',
											'1DPL' => 'Next Day Air Saver Letter',
											'2DM' => '2nd Day Air A.M.',
											'2DA' => '2nd Day Air',
											'2DML' => '2nd Day Air A.M. Letter',
											'2DAL' => '2nd Day Air Letter',
											'3DS' => '3 Day Select',
											'GNDCOM' => 'Ground Commercial',
											'GNDRES' => 'Ground Residential',
											'XPR' => 'Worldwide Express',
											'XDM' => 'Worldwide Express Plus',
											'XPRL' => 'Worldwide Express Letter',
											'XDML' => 'Worldwide Express Plus Letter',
											'XPD' => 'Worldwide Expedited'
									);		
	

	
	
	
	# USPS CONSTANT ARRAY SECTION #
	var $USPS_BOX_TYPE_ARRAY	=	array( 'Your Packaging' => 'Your Packaging',
										'Flat Rate Envelope' => 'Flat Rate Envelope',
										'Flat Rate Box' => 'Flat Rate Box'
									);
	
	var $USPS_PACKAGE_TYPE_ARRAY	=	array( 'Regular' => 'Regular',
											'Large' => 'Large',
											'Oversize' => 'Oversize'
										);
	
	var $USPS_DOMESTIC_ORDER_ARRAY		=	array( 'Express'=> 'Express Mail',
												'First Class'=> 'First-Class Mail',
												'Priority'=> 'Priority Mail',
												'Parcel'=> 'Parcel Post',
												'Media'	=> 'Bound Printed Matter',
												'BPM'	=> 'Media Mail',
												'Library'=> 'Library Mail'
										);		
										
	var $USPS_INTNL_ORDER_ARRAY		=	array( 'Global Express Guaranteed' => 'Global Express Guaranteed',
											'Global Express Guaranteed Non-Document Rectangular' => 'Global Express Guaranteed Non-Document Rectangular',
											'Global Express Guaranteed Non-Document Non-Rectangular' => 'Global Express Guaranteed Non-Document Non-Rectangular',
											'Express Mail International (EMS)' => 'Express Mail International (EMS)',
											'Express Mail International (EMS) Flat Rate Envelope' => 'Express Mail International (EMS) Flat Rate Envelope',
											'Priority Mail International' => 'Priority Mail International',
											'Priority Mail International Flat Rate Box' => 'Priority Mail International Flat Rate Box'
									);	
	#Australia Post Array Section#
	var $AusPost_DOMESTIC_SHIP_SERVICES		=	array( 'STANDARD' => 'Standard Delivery',
												'EXPRESS' => 'Express Delivery'
												
										);	
	var $AusPost_INTERNATIONAL_SHIP_SERVICES		=	array('Air' => 'Air Mail',
												'Sea' => 'Sea Mail',
												'ECI_D' => 'Express Courier International_Document',
												'ECI_M' => 'Express Courier International_Mechandise',
												'EPI' => 'Express Post International'
										);	
		
	# FEDEX CONSTANT ARRAY SECTION #
	
	var $FEDEX_PACKAGE_TYPE_ARRAY	=	array( 'YOURPACKAGING' => 'Your Packaging',
											'FEDEXENVELOPE' => 'FedEx Envelope',
											'FEDEXPAK' => 'FedEx Pak',
											'FEDEXBOX' => 'FedEx Box',
											'FEDEXTUBE' => 'FedEx Tube',
											'FEDEX10KGBOX' => 'FedEx 10KB Box',
											'FEDEX25KGBOX' => 'FedEx 25KB Box'
										);	
	
	var $FEDEX_SHIP_SERVICES		=	array( 'FEDEXGROUND' => 'FedEx Ground',
												'GROUNDHOMEDELIVERY' => 'FedEx Ground Home Delivery',
												'PRIORITYOVERNIGHT' => 'FedEx Priority Overnight',
												'STANDARDOVERNIGHT' => 'FedEx Standard Overnight',
												'FIRSTOVERNIGHT' => 'FedEx First Overnight',
												'FEDEX2DAY' => 'FedEx 2 Day',
												'FEDEXEXPRESSSAVER' => 'FedEx Express Saver',
												'INTERNATIONALPRIORITY' => 'FedEx International Priority',
												'INTERNATIONALECONOMY' => 'FedEx International Economy',
												'INTERNATIONALFIRST' => 'FedEx International First',
												'FEDEX1DAYFREIGHT' => 'FedEx 1 Day Freight',
												'FEDEX2DAYFREIGHT' => 'FedEx 2 Day Freight',
												'FEDEX3DAYFREIGHT' => 'FedEx 3 Day Freight',
												'INTERNATIONALPRIORITYFREIGHT' => 'FedEx International Priority Freight',
												'INTERNATIONALECONOMYFREIGHT' => 'FedEx International Economy Freight'
										);
	
	var	$FEDEX_DROPOFFTYPES			=	array(	'REGULARPICKUP' => 'Regular Pickup',
												'REQUESTCOURIER' => 'Request Courier',
												'DROPBOX' => 'Drop Box',
												'BUSINESSSERVICECENTER' => 'Business Service center',
												'STATION' => 'Station'
										);		
	
	# CANADA POST CONSTANT ARRAY SECTION #

	var $CANADAPOST_DOMESTIC_SHIP_SERVICES	=	array(  'PRIORITY COURIER' => 'Priority Courier',
														'EXPEDITED' => 'Expedited',
														'REGULAR' => 'Regular'
										);
										
	var $CANADAPOST_INTNL_SHIP_SERVICES		=	array(  'PUROLATOR INTERNATIONAL'=> 'Purolator International',
														'PARCEL AIR'=> 'Parcel Air',
														'PARCEL SURFACE'=> 'Parcel Surface',
														'Small Packets Air' => 'Small Packets Air',
														'Small Packets Surface' => 'Small Packets Surface'
										);												
										
	var $CANADAPOST_USA_SHIP_SERVICES		=	array(  'PUROLATOR INTERNATIONAL' => 'Purolator International',
														'XPRESSPOST USA' => 'Xpresspost USA',
														'EXPEDITED US COMMERCIAL' => 'Expedited US Commercial',
														'Small Packets Air' => 'Small Packets Air',
														'Small Packets Surface' => 'Small Packets Surface'
										);	

	# DHL CONSTANT ARRAY SECTION #
	var $DHL_NORMAL_SHIP_SERVICES			=	array(  'E' => 'DHL_SERVICE_EXPRESS',
														'N' => 'DHL_SERVICE_NEXT_AFTERNOON',
														'S' => 'DHL_SERVICE_SECOND_DAY',
														'G' => 'DHL_SERVICE_GROUND'
										);
										
	var $DHL_SPECIAL_SHIP_SERVICES			=	array(  'HAA' => 'DHL_SERVICE_HOLD_AT_DHL',
														'HAZ' => 'DHL_SERVICE_HAZARDOUS_MATERIALS',
														'SAT' => 'DHL_SERVICE_SAT',
														'1030' => 'DHL_SERVICE_EXPRESS_1030'
										);
								
	var $DHL_SHIPPING_TYPE					=	array(  'P' => 'DHL_SHIP_PACKAGE',
														'L' => 'DHL_SHIP_LETTER'
														
										);
										
	var $DHL_BILLING_TYPE					=	array(  'S' => 'DHL_BILL_SENDER',
														'R' => 'DHL_BILL_RECEIVER',
														'T' => 'DHL_BILL_THIRD_PARTY'
														
										);
										
	var $DHL_ADDITIONAL_PROTECTION			=	array(  'AP' => 'DHL_ASSET_PROTECTION',
														'DV' => 'DHL_DECLARED_VALUE',
														'NR' => 'DHL_NOT_REQUIRED'
														
										);
	
										
	var $INTERSHIP_SHIP_SERVICE_ARRAY		=	array( 
													'DOL' => 'DHL Next Day Air 10:30 AM (Letter)',
													'DON' => 'DHL Next Day Air 10:30 AM',
													'DNL' => 'DHL Next Day Air 12:00 PM (Letter)',
													'DNP' => 'DHL Next Day Air 12:00 PM',
													'DAL' => 'DHL Next Day Air 3:00 PM (Letter)',
													'DAP' => 'DHL Next Day Air 3:00 PM',
													'D2L' => 'DHL 2nd Day (Letter)',
													'D2P' => 'DHL 2nd Day',
													'DGN' => 'DHL Ground',
													'DGH' => 'DHL @ Home',
													'DGM' => 'DHL Media Mail',
													'DGB' => 'DHL Bound Printed Matter',
													'DIL' => 'DHL International Document (Letter)',
													'DID' => 'DHL International Document',
													'DPE' => 'DHL Worldwide Priority Express',
													'DGP' => 'DHL Global Mail Priority',
													'DGS' => 'DHL Global Mail Standard',
													
													'F2D' => 'FedEx 2nd Day',
													'FCG' => 'FedEx Canadian Ground',
													'FCE' => 'FedEx Canadian International Economy',
													'FCF' => 'FedEx Canadian International First',
													'FCP' => 'FedEx Canadian International Priority',
													'FES' => 'FedEx Express Saver',
													'FON' => 'FedEx First Overnight',
													'FGN' => 'FedEx Ground',
													'FHD' => 'FedEx Home Delivery',
													'FIE' => 'FedEx International Economy',
													'FIF' => 'FedEx International First',
													'FIP' => 'FedEx International Priority',
													'FPN' => 'FedEx Priority Overnight',
													'FSO' => 'FedEx Standard Overnight',
													
													'U2D' => 'UPS 2nd Day Air',
													'U2A' => 'UPS 2nd Day Air AM',
													'U3S' => 'UPS 3 Day Select',
													'UCX' => 'UPS Canadian Expedited',
													'UCE' => 'UPS Canadian Express',
													'UCP' => 'UPS Canadian Express Plus',
													'UGN' => 'UPS Ground',
													'UND' => 'UPS Next Day Air',
													'UNA' => 'UPS Next Day Air Early AM',
													'UNS' => 'UPS Next Day Air Saver',
													'UCS' => 'UPS Standard Canadian Service',
													'UWX' => 'UPS WorldWide Expedited',
													'UWE' => 'UPS WorldWide Express',
													'UWP' => 'UPS WorldWide Express Plus',
													
													'PEG' => 'USPS Global Express Guaranteed',
													'PEM' => 'USPS Express Mail International',
													'PMI' => 'USPS Priority Mail International',
													'PFI' => 'USPS First Class Mail International',
													'PFM' => 'USPS First Class Mail',
													'PFB' => 'USPS Priority Mail Flat Rate Box',
													'PFL' => 'USPS Priority Mail Flat Rate Letter',
													'PEA' => 'USPS Express Mail',
													'PEO' => 'USPS Express Mail P.O.',
													'PPM' => 'USPS Priority Mail',
													'PGM' => 'USPS Parcel Post Machine',
													'PGN' => 'USPS Parcel Post Non-Machine',
													'PMM' => 'USPS Media Mail',
													
													'CXP' => 'CAN XPressPost (CAN$)',
													'CPC' => 'CAN Priority Courier (CAN$)',
													'CEX' => 'CAN Expedited (1-4 Days, CAN$)',
													'CPP' => 'CAN XpressPost (3-5 Days, CAN$)',
													'CEE' => 'CAN Expedited (6-12 Days, CAN$)',
													'CRE' => 'CAN Regular (3-5 Days, CAN$)',
													'CPU' => 'CAN Purolator International (CAN$)',
													
													'AON' => 'AirBorne Overnight',
													'ANA' => 'AirBorne Next Afternoon',
													'A2D' => 'AirBorne 2nd Day',
													'AGN' => 'AirBorne Ground'
												);

	var $INTERSHIP_DELIVERYTYPES	=	array( 
												'COM' => 'Commercial',
												'RES' => 'Residential'
											);
	
	
	var $INTERSHIP_SHIP_METHODS		=	array( 
											'DRP' => 'Drop-Off At Carrier Location',
											'PCK' => 'Schedule A Special Pickup',
											'SCD' => 'Regularly Scheduled Pickup'
										);
										
	var $INTERSHIP_PACKAGING		=	array( 
											'BOX' => 'Customer-supplied Box',
											'CBX' => 'Carrier Box',
											'CPK' => 'Carrier Pak',
											'ENV' => 'Carrier Envelope',
											'MEM' => 'Media Mail',
											'TUB' => 'Carrier Tube'
										);
	
	var $INTERSHIP_CONTENTS		=	array( 
										'AHM' => 'Accessible Hazmat',
										'IHM' => 'Inaccessible Hazmat',
										'LQD' => 'Liquid',
										'OTR' => 'Other'
									);

	var $INTERSHIP_SERVICECLASSES	=	array( 
											'1DY' => '1st Day',
											'2DY' => '2nd Day',
											'3DY' => '3rd Day',
											'GND' => 'Ground'
										);
	

	/**
	 * Constructor Function
	 *
	 * @return Shipping
	 */
	function Shipping()
	{
		$this->FrameWork(); 
	}
	
	
	
	# The following method returns the shipping methods in a pagewise manner
	function listAllShippingMethods($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy)
	{
		$Qry		=	"SELECT * FROM shipping_methods";
		$Result 	= 	$this->db->get_results_pagewise($Qry, $pageNo, $limit, $params, $output, $orderBy);
		return $Result;
	}
	
	
	# The following method returns the Shipping method details
	function getShippingMethodDetails($Id)
	{
		$Qry		=	"SELECT * FROM shipping_methods WHERE shipmethod_id = '$Id'";
		$Result 	= $this->db->get_row($Qry, ARRAY_A);
		return $Result;
	}
	
	# The following method handles add/Update operation
	function addEditShippingMethod($REQUEST, $FILES)
	{
		$Message	=	'';

		extract($REQUEST);
		
		if(trim($name) == '')
			$Message	.=	'Shipping company Name required<br>';
		
		if(trim($id ) == '') {
			if($FILES['logo_extension']['size'] == 0)
				$Message	.=	'Shipping company Logo required<br>';
			else if($FILES['logo_extension']['type'] != 'image/gif' && $FILES['logo_extension']['type'] != 'image/jpeg' && $FILES['logo_extension']['type'] != 'image/pjpeg')
				$Message	.=	'Shipping company Logo JPEG/GIF format image required<br>';		
		}
		
		if(trim($id ) != '') {
			if($FILES['logo_extension']['size'] > 0) {
				if($FILES['logo_extension']['type'] != 'image/gif' && $FILES['logo_extension']['type'] != 'image/jpeg' && $FILES['logo_extension']['type'] != 'image/pjpeg')			
					$Message	.=	'Shipping company Logo JPEG/GIF format image required<br>';	
			}
		}
		
		$path_parts 	=	pathinfo($FILES['logo_extension']['name']);
		
		if($Message == '') { # Save the Shipping method details
			if(trim($id ) == '') {
				$array 			= 	array("name"=>$name, "ship_method_description"=>$ship_method_description, "logo_extension"=>'.'.$path_parts['extension']);
				$this->db->insert("shipping_methods", $array);
				$id 		= 	$this->db->insert_id;
				
				$DirName	=	SITE_PATH."/modules/order/images/shipping/";
				$FileName	=	$id.'.'.$path_parts['extension'];
				_upload($DirName, $FileName, $FILES['logo_extension']['tmp_name'], $id=1, 100, 50);
				# Close insert operation
			} else {
				$array 			= 	array("name"=>$name, "ship_method_description"=>$ship_method_description);
				$this->db->update("shipping_methods", $array, "shipmethod_id='$id'");
				if($FILES['logo_extension']['size'] > 0) {
					$array 	= 	array("logo_extension"=>'.'.$path_parts['extension']);
					$this->db->update("shipping_methods", $array, "shipmethod_id='$id'");
					$DirName	=	SITE_PATH."/modules/order/images/shipping/";
					$FileName	=	$id.'.'.$path_parts['extension'];
					_upload($DirName, $FileName, $FILES['logo_extension']['tmp_name'], $id=1, 100, 50);
				}
			}
			return TRUE;
		} # Close if message	
		
		return $Message;
	}
	
	# The following method removes the shipping methods selected from the listing page
	function removeShipMethods($REQUEST)
	{
		$shipmethod_ids		=	$REQUEST['shipmethod_ids'];
		foreach($shipmethod_ids as $shipmethod_id) {
			$Qry	=	"DELETE FROM shipping_methods WHERE shipmethod_id = '$shipmethod_id'";
			$this->db->query($Qry);
		}
		return TRUE;
	}
	
	# The following method activaites the selected Ship methods
	function activateShipMethods($REQUEST)
	{
		$shipmethod_ids		=	$REQUEST['shipmethod_ids'];
		foreach($shipmethod_ids as $shipmethod_id) {
			$Qry	=	"UPDATE shipping_methods SET active = 'Y' WHERE shipmethod_id = '$shipmethod_id'";
			$this->db->query($Qry);
		}
		return TRUE;
	}
	
	# The following method deactivates the selected shipping methods
	function deActivateShipMethods($REQUEST)
	{ 
		$shipmethod_ids		=	$REQUEST['shipmethod_ids'];
		foreach($shipmethod_ids as $shipmethod_id) {
			$Qry	=	"UPDATE shipping_methods SET active = 'N' WHERE shipmethod_id = '$shipmethod_id'";
			$this->db->query($Qry);
		}
		return TRUE;
	}
	
	
	# The following method returns the active shipping methods with the flag that whether they are selected or not
	function getShippingMethodsOfStore($store_id)
	{
		$ShipMethods	=	array();
		$Qry1			=	"SELECT * FROM shipping_methods WHERE active = 'Y'";
		$Result			=	$this->db->get_results($Qry1,ARRAY_A);
		
		$Qry11		=	"SELECT COUNT(*) AS ConfCount FROM shippingmethod_to_store WHERE store_id = '$store_id'";
		$Row11		=	$this->db->get_row($Qry11, ARRAY_A);
		$ConfCount	=	$Row11['ConfCount'];
		
		if($ConfCount == 0) {
			$UpsActive			=	'N';
			$UspsActive			=	'N';
			$FedexActive		=	'N';
			$canadaPostActive	=	'N';
			$DHLActive			=	'N';
			$InterShipperActive	=	'N';
			$AusPostActive	=	'N';
		} else {
			$Qry12	=	"SELECT * FROM shippingmethod_to_store WHERE store_id = '$store_id'";
			$Row12	=	$this->db->get_row($Qry12, ARRAY_A);
			//print_r($Row12);exit;
			$UpsActive			=	$Row12['ups_active'];
			$UspsActive			=	$Row12['usps_active'];
			$FedexActive		=	$Row12['fedex_active'];
			$canadaPostActive	=	$Row12['canadaPost_active'];
			$DHLActive			=	$Row12['dhl_active'];
			$InterShipperActive	=	$Row12['intershipper_active'];
			$AusPostActive		=	$Row12['AusPost_active'];
			$LocalPickupActive	=	$Row12['localpickup_active'];
		}
		
		if($Result) {
			$ArrIndx	=	0;
			foreach($Result as $Row) {
				$ShipMethods[$ArrIndx]	=	$Row;
				$shipmethod_id	=	$Row['shipmethod_id'];
				$name			=	$Row['name'];
				if($name === Ups) {
					if($UpsActive == 'Y')
						$ShipMethods[$ArrIndx]['selected']	=	'Y';
					else
						$ShipMethods[$ArrIndx]['selected']	=	'N';
				}
				if($name === AustraliaPost) {
					if($AusPostActive == 'Y')
						$ShipMethods[$ArrIndx]['selected']	=	'Y';
					else
						$ShipMethods[$ArrIndx]['selected']	=	'N';
				}
				
				if($name === Usps) {
					if($UspsActive == 'Y')
						$ShipMethods[$ArrIndx]['selected']	=	'Y';
					else
						$ShipMethods[$ArrIndx]['selected']	=	'N';
				}
				
				if($name === Fedex) {
					if($FedexActive == 'Y')
						$ShipMethods[$ArrIndx]['selected']	=	'Y';
					else
						$ShipMethods[$ArrIndx]['selected']	=	'N';
				}

				if($name === canadaPost) {
					if($canadaPostActive == 'Y')
						$ShipMethods[$ArrIndx]['selected']	=	'Y';
					else
						$ShipMethods[$ArrIndx]['selected']	=	'N';
				}

				if($name === DHL) {
					if($DHLActive == 'Y')
						$ShipMethods[$ArrIndx]['selected']	=	'Y';
					else
						$ShipMethods[$ArrIndx]['selected']	=	'N';
				}
				
				if($name === InterShipper) {
					if($InterShipperActive == 'Y')
						$ShipMethods[$ArrIndx]['selected']	=	'Y';
					else
						$ShipMethods[$ArrIndx]['selected']	=	'N';
				}
				if($name === LocalPickup ) {
					if($LocalPickupActive == 'Y')
						$ShipMethods[$ArrIndx]['selected']	=	'Y';
					else
						$ShipMethods[$ArrIndx]['selected']	=	'N';
				}
				

				$ArrIndx++;			
			} # Close foreach
		}
		return $ShipMethods;
	} # Close method definition
	
	
	# The following method assigns a particular shippig method to a store
	function assignShippingMethodToStore($REQUEST)
	{


		$store_id		=	(trim($REQUEST['store_id']) != '' ) ? $REQUEST['store_id'] : 0;
		$shipmethod_id	=	$REQUEST['shipmethod_id'];
		
		$ConfExists		=	$this->whetherShippingConfExists($store_id);

		$ShipMethodDetails	=	$this->getShippingMethodDetailsFromId($shipmethod_id);
		
		$MethodName			=	$ShipMethodDetails['name'];
		
		$Qry	=	'';
		if($ConfExists === TRUE) {
			if(Ups	==	$MethodName)
				$Qry	=	"UPDATE shippingmethod_to_store SET ups_active = 'Y' WHERE store_id = '$store_id'";
			if(Usps	==	$MethodName)
				$Qry	=	"UPDATE shippingmethod_to_store SET usps_active = 'Y' WHERE store_id = '$store_id'";
			if(Fedex	==	$MethodName)	
				$Qry	=	"UPDATE shippingmethod_to_store SET fedex_active = 'Y' WHERE store_id = '$store_id'";
			if(canadaPost	==	$MethodName)	
				$Qry	=	"UPDATE shippingmethod_to_store SET canadaPost_active = 'Y' WHERE store_id = '$store_id'";
			if(DHL	==	$MethodName)	
				$Qry	=	"UPDATE shippingmethod_to_store SET dhl_active = 'Y' WHERE store_id = '$store_id'";
			if(InterShipper	==	$MethodName)
				$Qry	=	"UPDATE shippingmethod_to_store SET intershipper_active = 'Y' WHERE store_id = '$store_id'";
			if(AustraliaPost	==	$MethodName)
				$Qry	=	"UPDATE shippingmethod_to_store SET AusPost_active = 'Y' WHERE store_id = '$store_id'";
					
			if($Qry)
				$this->db->query($Qry);
				
		} else {
			if(Ups	==	$MethodName)
				$Qry	=	"INSERT INTO shippingmethod_to_store (store_id,ups_active) VALUES ('$store_id','Y')";
			if(Usps	==	$MethodName)
				$Qry	=	"INSERT INTO shippingmethod_to_store (store_id,usps_active) VALUES ('$store_id','Y')";
			if(Fedex	==	$MethodName)
				$Qry	=	"INSERT INTO shippingmethod_to_store (store_id,fedex_active) VALUES ('$store_id','Y')";	
			if(canadaPost	==	$MethodName)
				$Qry	=	"INSERT INTO shippingmethod_to_store (store_id,canadaPost_active) VALUES ('$store_id','Y')";
			if(DHL	==	$MethodName)
				$Qry	=	"INSERT INTO shippingmethod_to_store (store_id,dhl_active) VALUES ('$store_id','Y')";
			if(InterShipper	==	$MethodName)
				$Qry	=	"INSERT INTO shippingmethod_to_store (store_id,intershipper_active) VALUES ('$store_id','Y')";
			if(AustraliaPost	==	$MethodName)
				$Qry	=	"INSERT INTO shippingmethod_to_store (store_id,AusPost_active) VALUES ('$store_id','Y')";
						
			if($Qry)
				$this->db->query($Qry);
		}
	} # Close method definition
	

	# The following method unassign a particular shipping method from a store 
	function unAssignShippingMethodFromStore($REQUEST)
	{
		$store_id			=	(trim($REQUEST['store_id']) != '' ) ? $REQUEST['store_id'] : 0;
		$shipmethod_id		=	$REQUEST['shipmethod_id'];		
		$ShipMethodDetails	=	$this->getShippingMethodDetailsFromId($shipmethod_id);
		$MethodName			=	$ShipMethodDetails['name'];
		$Qry	=	'';
		if(Ups	==	$MethodName) {
			$UpsId	=	UpsId;
			$Qry1	=	"UPDATE shippingmethod_to_store SET ups_active = 'N',ups_values = '' WHERE store_id = '$store_id'";
			$Qry2	=	"DELETE FROM shipping_services WHERE store_id ='$store_id' AND shipmethod_id = '$UpsId'";
		} 
		
		if(Usps	==	$MethodName) {
			$UspsId	=	UspsId;
			$Qry1	=	"UPDATE shippingmethod_to_store SET usps_active = 'N', usps_values = '' WHERE store_id = '$store_id'";
			$Qry2	=	"DELETE FROM shipping_services WHERE store_id ='$store_id' AND shipmethod_id = '$UspsId'";
		}
		
		if(Fedex	==	$MethodName) {
			$FedexId	=	FedexId;
			$Qry1		=	"UPDATE shippingmethod_to_store SET fedex_active = 'N', fedex_values = '' WHERE store_id = '$store_id'";
			$Qry2		=	"DELETE FROM shipping_services WHERE store_id ='$store_id' AND shipmethod_id = '$FedexId'";
		}	
		
		if(canadaPost  ==	$MethodName) {
			$canadaPostId	=	canadaPostId;
			$Qry1		=	"UPDATE shippingmethod_to_store SET canadaPost_active = 'N', canadaPost_values = '' WHERE store_id = '$store_id'";
			$Qry2		=	"DELETE FROM shipping_services WHERE store_id ='$store_id' AND shipmethod_id = '$canadaPostId'";
		}
		if(DHL  ==	$MethodName) {
			$DHLId		=	DHLId;
			$Qry1		=	"UPDATE shippingmethod_to_store SET dhl_active = 'N', dhl_values = '' WHERE store_id = '$store_id'";
			$Qry2		=	"DELETE FROM shipping_services WHERE store_id ='$store_id' AND shipmethod_id = '$DHLId'";
		}	
		if(AustraliaPost	==	$MethodName) {
			$AusPotId	=	AusPostId;
			$Qry1	=	"UPDATE shippingmethod_to_store SET AusPost_active = 'N',AusPost_values = '' WHERE store_id = '$store_id'";
			$Qry2	=	"DELETE FROM shipping_services WHERE store_id ='$store_id' AND shipmethod_id = '$AusPotId'";
		}
		if($Qry1)
			$this->db->query($Qry1);
		
		if($Qry2)
			$this->db->query($Qry2);	
			
		return TRUE;	
	}
	
	# The following method returns the form related with a particular shipping method of a store
	function getFormNameOfShippingMethod($REQUEST)
	{
		$shipmethod_id		=	$REQUEST['shipmethod_id'];
		$Qry				=	"SELECT form_file FROM shipping_methods WHERE shipmethod_id = '$shipmethod_id'";
		$Row				=	$this->db->get_row($Qry, ARRAY_A);
		$form_file			=	$Row['form_file'];
		return $form_file;
	}
	
	# The folowing method returns the form data corresponding to a Ups, Usps or fedex form
	function getFormData($REQUEST)
	{
		$DataArray			=	array();
		
		$store_id			=	(trim($REQUEST['store_id']) != '' ) ? $REQUEST['store_id'] : 0;
		$shipmethod_id		=	$REQUEST['shipmethod_id'];		
		
		$ShipMethodDetails	=	$this->getShippingMethodDetailsFromId($shipmethod_id);
		$Configuration		=	$this->getShippingMethodConfigurationOfStore($store_id);
		$ShipMethodName		=	$ShipMethodDetails['name'];
		if($ShipMethodName == Ups) {
			$UpsData	=	$Configuration['ups_values'];
			if(trim($UpsData) == '')
				return $DataArray;
			
			$Arr	=	explode(VALUE_SEPERATOR,$UpsData);	
			$DataArray['user_name']				=	$Arr[0];
			$DataArray['password']				=	$Arr[1];
			$DataArray['user_key']				=	$Arr[2];
			$DataArray['upsType']				=	$Arr[3];
			$DataArray['upsPackage']			=	$Arr[4];
			
			return $DataArray;
		} # Close Ups ship
		
		if($ShipMethodName == Usps) {
			$UspsData	=	$Configuration['usps_values'];
			if(trim($UspsData) == '')
				return $DataArray;
			$Arr	=	explode(VALUE_SEPERATOR,$UspsData);	
			$DataArray['user_name']					=	$Arr[0];
			$DataArray['password']					=	$Arr[1];
			$DataArray['uspsBoxType']				=	$Arr[2];
			$DataArray['uspsPackageType']			=	$Arr[3];
			$DataArray['live_server']				=	$Arr[4];

			return $DataArray;
		} # Close Usps Ship
		
		if($ShipMethodName == Fedex) {
			$FedexData	=	$Configuration['fedex_values'];
			if(trim($FedexData) == '')
				return $DataArray;
			$Arr	=	explode(VALUE_SEPERATOR,$FedexData);	
			$DataArray['account_number']		=	$Arr[0];
			$DataArray['meter']					=	$Arr[1];
			$DataArray['fedexPackaging']		=	$Arr[2];
			$DataArray['fedexAddress']			=	$Arr[3];
			$DataArray['fedexCity']				=	$Arr[4];
			$DataArray['fedexState']			=	$Arr[5];
			$DataArray['fedexZip']				=	$Arr[6];
			$DataArray['fedexCountry']			=	$Arr[7];
			$DataArray['fedexPhone']			=	$Arr[8];
			$DataArray['fedexDropofftype']		=	$Arr[9];
			$DataArray['fedex_server']			=	$Arr[10];
			
			return $DataArray;
		} # Close Fedex Ship


		if($ShipMethodName == canadaPost) {
			$canadaPostData	=	$Configuration['canadaPost_values'];
			if(trim($canadaPostData) == '')
				return $DataArray;
			$Arr	=	explode(VALUE_SEPERATOR,$canadaPostData);	

			$DataArray['canadaPost_server']		=	$Arr[0];
			$DataArray['customer_id']			=	$Arr[1];
			$DataArray['canadaPostCity']		=	$Arr[2];
			$DataArray['canadaPostState']		=	$Arr[3];
			$DataArray['canadaPostCountry']		=	$Arr[4];
			$DataArray['canadaPostZip']			=	$Arr[5];
			
			return $DataArray;
		} # Close canadaPost Ship
		
		if($ShipMethodName == DHL) {
			$DHLData	=	$Configuration['dhl_values'];
			if(trim($DHLData) == '')
				return $DataArray;
			
			$Arr	=	explode(VALUE_SEPERATOR,$DHLData);	
			$DataArray['user_name']				=	$Arr[0];
			$DataArray['password']				=	$Arr[1];
			$DataArray['account_no']			=	$Arr[2];
			$DataArray['zipcode']				=	$Arr[3];
			$DataArray['shipkey']				=	$Arr[4];
			$DataArray['dhlshipmenttype']		=	$Arr[5];
			$DataArray['dhlbillingtype']		=	$Arr[6];
			//$DataArray['dhlNormal']				=	$Arr[7];
			//$DataArray['dhlSpecial']			=	$Arr[8];
			return $DataArray;
		} # Close DHL ship
		
		if($ShipMethodName == InterShipper) {
			$InterShipperData	=	$Configuration['intershipper_values'];
			$InterArr			=	explode(VALUE_SEPERATOR, $InterShipperData);
			
			$DataArray['Interuser_name']		=	$InterArr[0];
			$DataArray['Interpassword']			=	$InterArr[1];
			$DataArray['InterClasses']			=	$InterArr[2];
			$DataArray['InterDeliveryType']		=	$InterArr[3];
			$DataArray['InterShippingMethod']	=	$InterArr[4];
			$DataArray['Interdefaultlength']	=	$InterArr[5];
			$DataArray['Interdefaultwidth']		=	$InterArr[6];
			$DataArray['Interdefaultheight']	=	$InterArr[7];
			$DataArray['InterPackaging']		=	$InterArr[8];
			$DataArray['InterContents']			=	$InterArr[9];
			return $DataArray;
		}
		

		return $DataArray;
	} # Close method definition
	
	
	# The following method returns the shipping method configuration details associated with a store
	function getShippingMethodConfigurationOfStore($store_id)
	{
		$Qry	=	"SELECT * FROM shippingmethod_to_store WHERE store_id = '$store_id'";
		$Row	=	$this->db->get_row($Qry, ARRAY_A);
		return $Row;
	}
	
	
		
	# The following method returns the Shipping method details associated with a particular shipping Id
	function getShippingMethodDetailsFromId($shipmethod_id)
	{
		$Qry	=	"SELECT * FROM shipping_methods WHERE shipmethod_id = '$shipmethod_id'";
		$Row	=	$this->db->get_row($Qry, ARRAY_A);
		return $Row;
	}
	
	# The following method returns TRUE when shipping configuration exists for a store else return false
	function whetherShippingConfExists($store_id)
	{
		$Qry		=	"SELECT COUNT(*) AS TotCount FROM shippingmethod_to_store WHERE store_id= '$store_id'";
		$Row		=	$this->db->get_row($Qry, ARRAY_A);
		$TotCount	=	$Row['TotCount'];
		if($TotCount > 0)
			return TRUE;
		else
			return FALSE; 
	}
		
	# The following method returns an array of store names and heading appended with the admin details
	function getStoreCombo()
	{
		$Stores		=	array();
		$Stores['id'][0]		=	'0';
		$Stores['name'][0]		=	'admin';
		
		if($this->config['payment_receiver'] == 'store') {
			#$Qry1		=	"SELECT id,name FROM store WHERE payment_receiver = 'S' ORDER BY id ASC ";
			$Qry1		=	"SELECT id,name FROM store WHERE active = 'Y' ORDER BY id ASC ";
			$Result1 	= 	$this->db->get_results($Qry1);
			if($Result1) {
				$ArrIndx	=	1;
				foreach($Result1 as $Row1) {
					$Stores['id'][$ArrIndx]			=	$Row1->id;
					$Stores['name'][$ArrIndx]		=	$Row1->name;
					$ArrIndx++;
				}
			}
		} # Close if payment receiver
			
		return $Stores;
	} 


	######################################################################################
	############## UPS Section	##########################################################
	function validateUpsForm($REQUEST)
	{
		extract($REQUEST);
		$Msg	=	'';
		
		if(trim($user_name) == '')
			$Msg	.=	'User name required<br>';
		if(trim($password) == '')
			$Msg	.=	'Password required<br>';	
		if(trim($user_key) == '')
			$Msg	.=	'User Key required<br>';		
		
		if(count($upsSvcs) == 0)
			$Msg	.=	'Select Shipping Services<br>';
		
		$Params		=	array();
		$Params['src_zip']			=	'08033';
		$Params['dst_zip']			=	'90210';
		$Params['weight']			=	120;
		$Params['src_country']		=	'US';
		$Params['dst_country']		=	'US';
		
		if(count($upsSvcs) > 0)
			$ShipPriceStatus		=	$this->testGetQuote($Params,$REQUEST,'Ups');
		
		if($ShipPriceStatus != '') 
			$Msg	.=	$ShipPriceStatus;
		
		if($Msg == '')
			return TRUE;
		else
			return $Msg;
	}
	
	function saveUpsForm($REQUEST)
	{
		extract($REQUEST);
		
		$upsSvcs		=	$REQUEST['upsSvcs'];
		$shipmethod_id	=	UpsId;	

		$UpsArr			=	array();
		$UpsArr[0]		=	$user_name;
		$UpsArr[1]		=	$password;
		$UpsArr[2]		=	$user_key;
		$UpsArr[3]		=	$upsType;
		$UpsArr[4]		=	$upsPackage;
		
		$UpsData		=	implode(VALUE_SEPERATOR, $UpsArr);
		
		$Qry	=	"UPDATE shippingmethod_to_store SET ups_values = '$UpsData' WHERE store_id = '$store_id'";
		$this->db->query($Qry);
		
		$Qry1	=	"DELETE FROM shipping_services WHERE store_id ='$store_id' AND shipmethod_id = '$shipmethod_id'";
		$this->db->query($Qry1);
		
		foreach($upsSvcs as $upsSvc) {
			$Qry2	=	"INSERT INTO shipping_services (store_id,shipmethod_id,servicevalue) 
						VALUES ('$store_id','$shipmethod_id','$upsSvc')";
			$this->db->query($Qry2);
		}
		
		return TRUE;
	} 
	
	
	# The following method returns the pickup types for combo filling
	function getUpsPickupTypes()
	{
		$PickupTypes	=	array();
		foreach($this->UPS_PICKUP_TYPES_ARRAY as $Key => $Value) {
			$PickupTypes['dbvalue'][]	=	$Key;
			$PickupTypes['label'][]	=	$Value;
		}
		return $PickupTypes;
	} 
	
		
	# The following method returns the package types for combo filling
	function getPackageTypes()
	{
		$PackageTypes	=	array();
		foreach($this->UPS_PACKAGE_TYPES_ARRAY as $Key => $Value) {
			$PackageTypes['dbvalue'][]	=	$Key;
			$PackageTypes['label'][]	=	$Value;
		}
		return $PackageTypes;
	}  
	
	# The following method returns the ship services
	function getUpsShipServices($store_id)
	{
		$shipmethod_id		=	UpsId;
		$store_id			=	(trim($store_id) != '') ? $store_id : '0';
		$SelectedServices	=	array();
		$ShipServices		=	array();
		
		$Qry		=	"SELECT servicevalue FROM shipping_services WHERE store_id= '$store_id' AND shipmethod_id = '$shipmethod_id'";
		$Result 	= 	$this->db->get_results($Qry,ARRAY_A);
		if($Result) {
			foreach($Result as $Row)
				$SelectedServices[]	=	$Row['servicevalue'];
		}
		
		$ArrIndx	=	0;
		foreach($this->UPS_SHIP_SERVICE_ARRAY as $Key => $Value) {
			if(in_array($Key,$SelectedServices))
				$ShipServices[$ArrIndx]['active']		=	'Y';
			else
				$ShipServices[$ArrIndx]['active']		=	'N';

			$ShipServices[$ArrIndx]['dbvalue']		=	$Key;
			$ShipServices[$ArrIndx]['label']		=	$Value;
			$ArrIndx++;
		}
		return $ShipServices;
	}
	
	
	
	###########################################################################################
	############ Usps section #################################################################
	function validateUspsForm($REQUEST)
	{
		extract($REQUEST);
		$Msg	=	'';

		if(trim($user_name) == '')
			$Msg	.=	'User name required<br>';
		if(trim($password) == '')
			$Msg	.=	'Password required<br>';	
		if(trim($live_server) == '')
			$Msg	.=	'Live Server URL required<br>';	
		if(count($uspsDomSvcs) == 0)
			$Msg	.=	'Select USPS Domestic services<br>';	
		
		#if(count($uspsIntlSvcs) == 0)
			#$Msg	.=	'Select USPS International services<br>';		
		
		$Params		=		array();
		$Params['src_zip']		=	'10022';
		$Params['dst_zip']		=	'20008';
		$Params['weight']		=	10;
		$Params['ounce']		=	5;
		$Params['dst_country']	=	'USA';

		$ShipPriceStatus	=	$this->testGetQuote($Params,$REQUEST,'Usps');

		if($ShipPriceStatus != '') 
			$Msg	.=	$ShipPriceStatus;
		
		if($Msg == '')
			return TRUE;
		else
			return $Msg;
	}
	
	
	function saveUspsForm($REQUEST)
	{
		extract($REQUEST);
		$ShipMethodId	=	UspsId;
		
		$UspsArr		=	array();
		$UspsArr[0]		=	$user_name;
		$UspsArr[1]		=	$password;
		$UspsArr[2]		=	$uspsBoxType;
		$UspsArr[3]		=	$uspsPackageType;
		$UspsArr[4]		=	$live_server;
		$UspsData		=	implode(VALUE_SEPERATOR, $UspsArr);
		
		$Qry	=	"UPDATE shippingmethod_to_store SET usps_values = '$UspsData' WHERE store_id = '$store_id'";
		$this->db->query($Qry);
		
		$Qry01	=	"DELETE FROM shipping_services WHERE store_id = '$store_id' AND shipmethod_id = '$ShipMethodId'";
		$this->db->query($Qry01);
		
		foreach($uspsDomSvcs as $uspsDomSvc) {
			$Qry1	=	"INSERT INTO shipping_services (store_id,shipmethod_id,servicevalue,domestic) 
						VALUES ('$store_id','$ShipMethodId','$uspsDomSvc','Y')";
			$this->db->query($Qry1);
		}
		
		foreach($uspsIntlSvcs as $uspsIntlSvc) {
			$Qry2	=	"INSERT INTO shipping_services (store_id,shipmethod_id,servicevalue,international) 
						VALUES ('$store_id','$ShipMethodId','$uspsIntlSvc','Y')";
			$this->db->query($Qry2);
		}

		return TRUE;
	
	}
	
	# The following method returns the Usps Box types for combo box filling
	function getUspsBoxTypes()
	{
		$UspsBoxTypes	=	array();
		$ArrIndx	=	0;
		foreach($this->USPS_BOX_TYPE_ARRAY as $Key => $Value) {
			$UspsBoxTypes['dbvalue'][]	=	$Key;
			$UspsBoxTypes['label'][]		=	$Key;
			$ArrIndx++;
		}
		return $UspsBoxTypes;	
	}
	
	# Thefollowing method returns the Usps package types for combo filling
	function getUspsPackageTypes()
	{
		$UspsPackageTypes	=	array();
		
		$ArrIndx	=	0;
		foreach($this->USPS_PACKAGE_TYPE_ARRAY as $Key => $Value) {
			$UspsPackageTypes['dbvalue'][]	=	$Key;
			$UspsPackageTypes['label'][]	=	$Value;
			$ArrIndx++;
		}
		return $UspsPackageTypes;
	}
	
	# The following method returns the Domestic Usps services
	function getDomesticUspsServices($store_id)
	{
		$DomesticServices	=	array();
		$SelServices		=	array();
		$store_id			=	(trim($store_id) != '') ? $store_id : 0;
		$UspsId				=	UspsId;
		
		$Qry	=	"SELECT servicevalue FROM shipping_services WHERE store_id = '$store_id' AND shipmethod_id = '$UspsId' AND domestic = 'Y'";
		$Result 	= 	$this->db->get_results($Qry,ARRAY_A);
		if($Result) {
			foreach($Result as $Row)
				$SelServices[]	=	$Row['servicevalue'];
		}
		
		$ArrIndx	=	0;
		foreach($this->USPS_DOMESTIC_ORDER_ARRAY as $Key => $Value) {
			
			if(in_array($Key,$SelServices))
				$DomesticServices[$ArrIndx]['active']	=	'Y';
			else
				$DomesticServices[$ArrIndx]['active']	=	'N';	

			$DomesticServices[$ArrIndx]['dbvalue']	=	$Key;
			$DomesticServices[$ArrIndx]['label']	=	$Value;
			$ArrIndx++;
		}
		
		
		return $DomesticServices;
	}
	
	# The folowing method returns the international Usps services
	function getInternationalUspsServices($store_id)
	{
		$IntnlServices		=	array();
		$SelServices		=	array();
		$store_id			=	(trim($store_id) != '') ? $store_id : 0;
		$UspsId				=	UspsId;
		
		$Qry	=	"SELECT servicevalue FROM shipping_services WHERE store_id = '$store_id' AND shipmethod_id = '$UspsId' AND international = 'Y'";
		$Result 	= 	$this->db->get_results($Qry,ARRAY_A);
		if($Result) {
			foreach($Result as $Row)
				$SelServices[]	=	$Row['servicevalue'];
		}
		
		
		$ArrIndx	=	0;
		foreach($this->USPS_INTNL_ORDER_ARRAY as $Key => $Value) {

			if(in_array($Key,$SelServices))
				$IntnlServices[$ArrIndx]['active']	=	'Y';
			else
				$IntnlServices[$ArrIndx]['active']	=	'N';	

			$IntnlServices[$ArrIndx]['dbvalue']	=	$Key;
			$IntnlServices[$ArrIndx]['label']	=	$Value;
			$ArrIndx++;
		}	 
		return $IntnlServices;
	}
	
	
	
	###########################################################################################
	############## Fedex Section ##############################################################
	function validateFedexForm($REQUEST)
	{
		extract($REQUEST);
		$Msg	=	'';

		if(trim($fedex_server) == '')
			$Msg	.=	'Fedex Server Name required<br>';
		if(trim($account_number) == '')
			$Msg	.=	'Account Number required<br>';
		if(trim($meter) == '')
			$Msg	.=	'Meter required<br>';	
		if(trim($fedexAddress) == '')
			$Msg	.=	'Address required<br>';	
		if(trim($fedexCity) == '')
			$Msg	.=	'City required<br>';		
		if(trim($fedexState) == '')
			$Msg	.=	'State required<br>';			
		if(trim($fedexZip) == '')
			$Msg	.=	'Zip Code required<br>';				
		if(trim($fedexPhone) == '')
			$Msg	.=	'Phone Number required<br>';					
		if(count($services) == 0)
			$Msg	.=	'Fedex Services not selected<br>';	
		
		if(count($services) > 0)
			$ShipPriceStatus	=	$this->testGetQuote($Params,$REQUEST,'Fedex');
		
		if($ShipPriceStatus != '') 
			$Msg	.=	$ShipPriceStatus;
		
		if($Msg == '')
			return TRUE;
		else
			return $Msg;
	}
	
	# Save Fedex information
	function saveFedexForm($REQUEST)
	{
		extract($REQUEST);
		
		$shipmethod_id	=	FedexId;
		
		$FedexArr		=	array();
		$FedexArr[0]	=	$account_number;
		$FedexArr[1]	=	$meter;
		$FedexArr[2]	=	$fedexPackaging;
		$FedexArr[3]	=	$fedexAddress;
		$FedexArr[4]	=	$fedexCity;
		$FedexArr[5]	=	$fedexState;
		$FedexArr[6]	=	$fedexZip;
		$FedexArr[7]	=	$fedexCountry;
		$FedexArr[8]	=	$fedexPhone;
		$FedexArr[9]	=	$fedexDropofftype;
		$FedexArr[10]	=	$fedex_server;
		
		$FedexData		=	implode(VALUE_SEPERATOR, $FedexArr);

		$Qry	=	"UPDATE shippingmethod_to_store SET fedex_values = '$FedexData' WHERE store_id = '$store_id'";
		$this->db->query($Qry);
		
		$Qry1	=	"DELETE FROM shipping_services WHERE store_id = '$store_id' AND shipmethod_id = '$shipmethod_id'";
		$this->db->query($Qry1);
		
		foreach($services as $service) {
			$Qry2	=	"INSERT INTO shipping_services (store_id,shipmethod_id,servicevalue) VALUES 
						('$store_id','$shipmethod_id','$service')";
			$this->db->query($Qry2);			
		}

		return TRUE;
	}
	
	# The following method returns the Fedex package types for combo filling
	function getFedexPackageTypes()
	{
		$PackageTypes	=	array();
		
		foreach($this->FEDEX_PACKAGE_TYPE_ARRAY as $Key => $Value) {
			$PackageTypes['dbvalue'][]		=	$Key;
			$PackageTypes['label'][]		=	$Value;
		}
		return $PackageTypes;
	} 
	
	# The following method returns the Fedex DropoffTypes
	function getFedexDropOffTypes()
	{
		$DropOffTypes	=	array();
		foreach($this->FEDEX_DROPOFFTYPES as $Key => $Value) {
			$DropOffTypes['dbvalue'][]	=	$Key;
			$DropOffTypes['label'][]	=	$Value;
		}
		return $DropOffTypes;
	}
		

	# The following method returns the shipping services
	function getFedexShippingServices($store_id)
	{
		$ShipServices	=	array();
		$SelServices	=	array();
		$ArrIndx		=	0;
		$store_id		=	(trim($store_id) != '') ? $store_id : 0;
		$ShipMethodId	=	FedexId;
		
		$Qry		=	"SELECT servicevalue FROM shipping_services WHERE store_id = '$store_id' AND shipmethod_id = '$ShipMethodId'";
		$Result		=	$this->db->get_results($Qry,ARRAY_A);
		if($Result) {
			foreach ($Result as $row)
				$SelServices[]	=	$row['servicevalue'];
		}
		
		foreach($this->FEDEX_SHIP_SERVICES as $Key => $Value) {
			
			if(in_array($Key,$SelServices))
				$ShipServices[$ArrIndx]['active']	=	'Y';
			else
				$ShipServices[$ArrIndx]['active']	=	'N';

			$ShipServices[$ArrIndx]['dbvalue']	=	$Key;
			$ShipServices[$ArrIndx]['label']	=	$Value;
			$ArrIndx++;		
		} # Close foreach
		return $ShipServices;
	}
	
	
	###########################################################################################
	###############Australia Post Section######################################################
		function validateAusPostForm($REQUEST)
	{
		extract($REQUEST);
		$Msg	=	'';
		if(count($dservices) == 0 && count($iservices) == 0)
			$Msg	.=	'Australia Post Services not selected<br>';	
		if($Msg == '')
			return TRUE;
		else
			return $Msg;
	}
	# Save Australia Post Shipping information
	function saveAusPostForm($REQUEST)
	{
		extract($REQUEST);
		
		$shipmethod_id	=	AusPostId;
		
		$FedexArr		=	array();
		$FedexArr[0]	=	$fedexZip;
		
		$FedexData		=	implode(VALUE_SEPERATOR, $FedexArr);

		$Qry	=	"UPDATE shippingmethod_to_store SET AusPost_values = '$FedexData' WHERE store_id = '$store_id'";
		$this->db->query($Qry);
		
		$Qry1	=	"DELETE FROM shipping_services WHERE store_id = '$store_id' AND shipmethod_id = '$shipmethod_id'";
		$this->db->query($Qry1);
		
		foreach($dservices as $service) {
			$Qry2	=	"INSERT INTO shipping_services (store_id,shipmethod_id,servicevalue,domestic) VALUES 
						('$store_id','$shipmethod_id','$service','Y')";
			$this->db->query($Qry2);			
		}
		foreach($iservices as $service) {
			$Qry2	=	"INSERT INTO shipping_services (store_id,shipmethod_id,servicevalue,international) VALUES 
						('$store_id','$shipmethod_id','$service','Y')";
			$this->db->query($Qry2);			
		}

		return TRUE;
	}
	
	function getAusPostShippingServices($store_id)
	{
		$ShipServices	=	array();
		$SelServices	=	array();
		$ArrIndx		=	0;
		$store_id		=	(trim($store_id) != '') ? $store_id : 0;
		$ShipMethodId	=	AusPostId;
		
		$Qry		=	"SELECT servicevalue FROM shipping_services WHERE store_id = '$store_id' AND shipmethod_id = '$ShipMethodId'";
		$Result		=	$this->db->get_results($Qry,ARRAY_A);
		if($Result) {
			foreach ($Result as $row)
				$SelServices[]	=	$row['servicevalue'];
		}
		
		foreach($this->AusPost_DOMESTIC_SHIP_SERVICES as $Key => $Value) {
			
			if(in_array($Key,$SelServices))
				$ShipServices[$ArrIndx]['active']	=	'Y';
			else
				$ShipServices[$ArrIndx]['active']	=	'N';

			$ShipServices[$ArrIndx]['dbvalue']	=	$Key;
			$ShipServices[$ArrIndx]['label']	=	$Value;
			$ShipServices[$ArrIndx]['type']	=	'DOMESTIC';
			$ArrIndx++;		
		} # Close foreach
		foreach($this->AusPost_INTERNATIONAL_SHIP_SERVICES as $Key => $Value) {
			
			if(in_array($Key,$SelServices))
				$ShipServices[$ArrIndx]['active']	=	'Y';
			else
				$ShipServices[$ArrIndx]['active']	=	'N';

			$ShipServices[$ArrIndx]['dbvalue']	=	$Key;
			$ShipServices[$ArrIndx]['label']	=	$Value;
			$ShipServices[$ArrIndx]['type']	=	'INTERNATIONAL';
			$ArrIndx++;		
		} # Close foreach
		//print_r($ShipServices);exit;
		return $ShipServices;
	}
	###########################################################################################
	############## CanadaPost Section ##############################################################
	function validateCanadaPostForm($REQUEST)
	{
		extract($REQUEST);
		$Msg	=	'';


		if(trim($canadaPost_server) == '')
			$Msg	.=	'CanadaPost Server Name required<br>';
		if(trim($customer_id) == '')
			$Msg	.=	'Customer ID required<br>';
		if(trim($canadaPostCity) == '')
			$Msg	.=	'City required<br>';		
		if(trim($canadaPostState) == '')
			$Msg	.=	'State required<br>';			
		if(trim($canadaPostCountry) == '')
			$Msg	.=	'Country required<br>';			
		if(trim($canadaPostZip) == '')
			$Msg	.=	'Zip Code required<br>';				
		if(count($canadaDomSvcs) == 0)
			$Msg	.=	'Select CanadaPost Domestic Services<br>';	
		


		if(count($canadaDomSvcs) > 0)
			$ShipPriceStatus	=	$this->testGetQuote($Params,$REQUEST,'canadaPost');
		


		if($ShipPriceStatus != '') 
			$Msg	.=	$ShipPriceStatus;
		
		if($Msg == '')
			return TRUE;
		else
			return $Msg;
	}
	
	# Save CanadaPost information
	function saveCanadaPostForm($REQUEST)
	{
		extract($REQUEST);
		
		$shipmethod_id	=	canadaPostId;
		
		$canadaPostArr		=	array();
		$canadaPostArr[0]	=	$canadaPost_server;
		$canadaPostArr[1]	=	$customer_id;
		$canadaPostArr[2]	=	$canadaPostCity;
		$canadaPostArr[3]	=	$canadaPostState;
		$canadaPostArr[4]	=	$canadaPostCountry;
		$canadaPostArr[5]	=	$canadaPostZip;
		
		$canadaPostData		=	implode(VALUE_SEPERATOR, $canadaPostArr);

		
		
		
		$Qry	=	"UPDATE shippingmethod_to_store SET canadaPost_values = '$canadaPostData' WHERE store_id = '$store_id'";
		$this->db->query($Qry);
		
		$Qry1	=	"DELETE FROM shipping_services WHERE store_id = '$store_id' AND shipmethod_id = '$shipmethod_id'";
		$this->db->query($Qry1);
		

		foreach($canadaDomSvcs as $canadaDomSvc) {
			$Qry1	=	"INSERT INTO shipping_services (store_id,shipmethod_id,servicevalue,domestic) 
						VALUES ('$store_id','$shipmethod_id','$canadaDomSvc','Y')";
			$this->db->query($Qry1);
		}
		
		foreach($canadaIntlSvcs as $canadaIntlSvc) {
			$Qry2	=	"INSERT INTO shipping_services (store_id,shipmethod_id,servicevalue,international) 
						VALUES ('$store_id','$shipmethod_id','$canadaIntlSvc','Y')";
			$this->db->query($Qry2);
		}
		
		foreach($canadaUsaSvcs as $canadaUsaSvc) {
			$Qry2	=	"INSERT INTO shipping_services (store_id,shipmethod_id,servicevalue,usa) 
						VALUES ('$store_id','$shipmethod_id','$canadaUsaSvc','Y')";
			$this->db->query($Qry2);
		}
		
		
		return TRUE;
	}
	






	# The following method returns the Domestic shipping services of CANADA POST
	function getCanadaPostDomesticShippingServices($store_id)
	{
		$ShipServices	=	array();
		$SelServices	=	array();
		$ArrIndx		=	0;
		$store_id		=	(trim($store_id) != '') ? $store_id : 0;
		$ShipMethodId	=	canadaPostId;
		
		$Qry		=	"SELECT servicevalue FROM shipping_services WHERE store_id = '$store_id' AND shipmethod_id = '$ShipMethodId' AND domestic = 'Y'";

		$Result		=	$this->db->get_results($Qry,ARRAY_A);
		if($Result) {
			foreach ($Result as $row)
				$SelServices[]	=	$row['servicevalue'];
		}
		
		foreach($this->CANADAPOST_DOMESTIC_SHIP_SERVICES as $Key => $Value) {
			
			if(in_array($Key,$SelServices))
				$ShipServices[$ArrIndx]['active']	=	'Y';
			else
				$ShipServices[$ArrIndx]['active']	=	'N';

			$ShipServices[$ArrIndx]['dbvalue']	=	$Key;
			$ShipServices[$ArrIndx]['label']	=	$Value;
			$ArrIndx++;		
		} # Close foreach
		return $ShipServices;
	}
	
	# The following method returns the Domestic shipping services of CANADA POST
	function getCanadaPostIntlShippingServices($store_id)
	{
		$ShipServices	=	array();
		$SelServices	=	array();
		$ArrIndx		=	0;
		$store_id		=	(trim($store_id) != '') ? $store_id : 0;
		$ShipMethodId	=	canadaPostId;
		
		$Qry		=	"SELECT servicevalue FROM shipping_services WHERE store_id = '$store_id' AND shipmethod_id = '$ShipMethodId' AND international = 'Y'";

		$Result		=	$this->db->get_results($Qry,ARRAY_A);
		if($Result) {
			foreach ($Result as $row)
				$SelServices[]	=	$row['servicevalue'];
		}
		
		foreach($this->CANADAPOST_INTNL_SHIP_SERVICES as $Key => $Value) {
			
			if(in_array($Key,$SelServices))
				$ShipServices[$ArrIndx]['active']	=	'Y';
			else
				$ShipServices[$ArrIndx]['active']	=	'N';

			$ShipServices[$ArrIndx]['dbvalue']	=	$Key;
			$ShipServices[$ArrIndx]['label']	=	$Value;
			$ArrIndx++;		
		} # Close foreach
		return $ShipServices;
	}
	
	# The following method returns the Domestic shipping services of CANADA POST
	function getCanadaPostUsaShippingServices($store_id)
	{
		$ShipServices	=	array();
		$SelServices	=	array();
		$ArrIndx		=	0;
		$store_id		=	(trim($store_id) != '') ? $store_id : 0;
		$ShipMethodId	=	canadaPostId;
		
		$Qry		=	"SELECT servicevalue FROM shipping_services WHERE store_id = '$store_id' AND shipmethod_id = '$ShipMethodId' AND usa = 'Y'";

		$Result		=	$this->db->get_results($Qry,ARRAY_A);
		if($Result) {
			foreach ($Result as $row)
				$SelServices[]	=	$row['servicevalue'];
		}
		
		foreach($this->CANADAPOST_USA_SHIP_SERVICES as $Key => $Value) {
			
			if(in_array($Key,$SelServices))
				$ShipServices[$ArrIndx]['active']	=	'Y';
			else
				$ShipServices[$ArrIndx]['active']	=	'N';

			$ShipServices[$ArrIndx]['dbvalue']	=	$Key;
			$ShipServices[$ArrIndx]['label']	=	$Value;
			$ArrIndx++;		
		} # Close foreach
		return $ShipServices;
	}
	
	
	######################################################################################
	############## DHL Section	##########################################################
	function validateDhlForm($REQUEST)
	{
		extract($REQUEST);
		$Msg	=	'';
		
		if(trim($user_name) == '')
			$Msg	.=	'User name required<br>';
		if(trim($password) == '')
			$Msg	.=	'Password required<br>';	
		if(trim($account_no) == '')
			$Msg	.=	'Account Number required<br>';		
		if(trim($zipcode) == '')
			$Msg	.=	'Zipcode required<br>';	
			
		/*$Params		=	array();
		$Params['src_zip']			=	'08033';
		$Params['dst_zip']			=	'90210';
		$Params['weight']			=	120;
		$Params['src_country']		=	'US';
		$Params['dst_country']		=	'US';
		
		if(count($upsSvcs) > 0)
			$ShipPriceStatus		=	$this->testGetQuote($Params,$REQUEST,'DHL');
		
		if($ShipPriceStatus != '') 
			$Msg	.=	$ShipPriceStatus;*/
		
		if($Msg == '')
			return TRUE;
		else
			return $Msg;
	} 
	
	function saveDhlForm($REQUEST)
	{
		extract($REQUEST);
		
		$dhlNorSvcs		=	$REQUEST['dhlNorSvcs'];
		$dhlSplSvcs		=	$REQUEST['dhlSplSvcs'];
		
		$shipmethod_id	=	DHLId;	

		$DHLArr			=	array();
		$DHLArr[0]		=	$user_name;
		$DHLArr[1]		=	$password;
		$DHLArr[2]		=	$account_no;
		$DHLArr[3]		=	$zipcode;
		$DHLArr[4]		=	$shipkey;
		$DHLArr[5]		=	$dhlshipmenttype;
		$DHLArr[6]		=	$dhlbillingtype;
		
		
		$DHLData		=	implode(VALUE_SEPERATOR, $DHLArr);
		
		$Qry	=	"UPDATE shippingmethod_to_store SET dhl_values = '$DHLData' WHERE store_id = '$store_id'";
		$this->db->query($Qry);
		
		$Qry1	=	"DELETE FROM shipping_services WHERE store_id ='$store_id' AND shipmethod_id = '$shipmethod_id'";
		$this->db->query($Qry1);
		#For DHL Normal Services...
		foreach($dhlNorSvcs as $dhlNorSvc) {
			$Qry2	=	"INSERT INTO shipping_services (store_id,shipmethod_id,servicevalue) 
						VALUES ('$store_id','$shipmethod_id','$dhlNorSvc')";
			$this->db->query($Qry2);
		}
		#For DHL Special Services...
		foreach($dhlSplSvcs as $dhlSplSvc) {
			$Qry3	=	"INSERT INTO shipping_services (store_id,shipmethod_id,servicevalue) 
						VALUES ('$store_id','$shipmethod_id','$dhlSplSvc')";
			$this->db->query($Qry3);
		}
		
				
		return TRUE;
	} 
	
	#Fill the combo Shipment Type...
	function getDhlShippingType()
	{
		$ShippingType	=	array();
		foreach($this->DHL_SHIPPING_TYPE as $Key => $Value) {
			$ShippingType['dbvalue'][]	=	$Key;
			$ShippingType['label'][]	=	$Value;
		}
		return $ShippingType;
	}
	#Fill the combo Billing Type...
	function getDhlBillingType()
	{
		$BillingType	=	array();
		foreach($this->DHL_BILLING_TYPE as $Key => $Value) {
			$BillingType['dbvalue'][]	=	$Key;
			$BillingType['label'][]	=	$Value;
		}
		return $BillingType;
	}

	function getDhlAddProtection()
	{
		$AddProtection	=	array();
		foreach($this->DHL_ADDITIONAL_PROTECTION as $Key => $Value) {
			$AddProtection['dbvalue'][]	=	$Key;
			$AddProtection['label'][]	=	$Value;
		}
		return $AddProtection;
	}
	# The following method returns the Normal ship services
	function getDhlNormalService($store_id)
	{
		$shipmethod_id		=	DHLId;
		$store_id			=	(trim($store_id) != '') ? $store_id : '0';
		$SelectedServices	=	array();
		$ShipServices		=	array();
		
		$Qry		=	"SELECT servicevalue FROM shipping_services WHERE store_id= '$store_id' AND shipmethod_id = '$shipmethod_id'";
		$Result 	= 	$this->db->get_results($Qry,ARRAY_A);
		if($Result) {
			foreach($Result as $Row)
				$SelectedServices[]	=	$Row['servicevalue'];
		}
		
		$ArrIndx	=	0;
		foreach($this->DHL_NORMAL_SHIP_SERVICES as $Key => $Value) {
			if(in_array($Key,$SelectedServices))
				$ShipServices[$ArrIndx]['active']		=	'Y';
			else
				$ShipServices[$ArrIndx]['active']		=	'N';

			$ShipServices[$ArrIndx]['dbvalue']		=	$Key;
			$ShipServices[$ArrIndx]['label']		=	$Value;
			$ArrIndx++;
		}
		return $ShipServices;
	}
	# The following method returns the Normal ship services
	function getDhlSpecialService($store_id)
	{
		$shipmethod_id		=	DHLId;
		$store_id			=	(trim($store_id) != '') ? $store_id : '0';
		$SelectedServices	=	array();
		$ShipServices		=	array();
		
		$Qry		=	"SELECT servicevalue FROM shipping_services WHERE store_id= '$store_id' AND shipmethod_id = '$shipmethod_id'";
		$Result 	= 	$this->db->get_results($Qry,ARRAY_A);
		if($Result) {
			foreach($Result as $Row)
				$SelectedServices[]	=	$Row['servicevalue'];
		}
		
		$ArrIndx	=	0;
		foreach($this->DHL_SPECIAL_SHIP_SERVICES as $Key => $Value) {
			if(in_array($Key,$SelectedServices))
				$ShipServices[$ArrIndx]['active']		=	'Y';
			else
				$ShipServices[$ArrIndx]['active']		=	'N';

			$ShipServices[$ArrIndx]['dbvalue']		=	$Key;
			$ShipServices[$ArrIndx]['label']		=	$Value;
			$ArrIndx++;
		}
		return $ShipServices;
	}
	##############################DHL END#######################################################################

	
	
	
	
	############################## InterShipper Section Starts #################################################
	
	/**
	 * The following method returns the Shipping services related with the InterShipper service
	 *
	 * @param unknown_type $StoreId
	 */
	function getInterShipperServices($StoreId)
	{
		$ShipServices	=	array();
		$SelServives	=	array();
		
		$ShipMethodId	=	InterShipperId;
		
		$Qry1			=	"SELECT servicevalue FROM shipping_services WHERE store_id = '$StoreId' AND shipmethod_id = '$ShipMethodId'";
		$Services1		=	$this->db->get_results($Qry1, ARRAY_A);
		foreach($Services1 as $Service1)
			$SelServives[]	=	$Service1['servicevalue'];
		
		$ArrIndx	=	0;
		foreach($this->INTERSHIP_SHIP_SERVICE_ARRAY as $Key => $Value) {
			$ShipServices[$ArrIndx]['ServiceName']		=	$Value;
			$ShipServices[$ArrIndx]['ServiceCode']		=	$Key;
						
			if(in_array($Key, $SelServives))
				$ShipServices[$ArrIndx]['Selected']		=	'Yes';
			else
				$ShipServices[$ArrIndx]['Selected']		=	'No';
			$ArrIndx++;	
		}
		
		return $ShipServices;
	
	}
	
	
	/**
	 * The following method rturns the all shipping classes whether they are selected or not
	 *
	 * @param Imploded String $SelectedClasses
	 * @return unknown
	 */
	function getInterShipperShippingServiceClasses($SelectedClasses)
	{
		$ShippingClasses	=	array();

		if($SelectedClasses != '')
			$SelShipClasses	=	explode('~!', $SelectedClasses);
			
		$ArrIndx	=	0;
		foreach($this->INTERSHIP_SERVICECLASSES as $Key => $Value) {
			$ShippingClasses[$ArrIndx]['ClassCode']	=	$Key;
			$ShippingClasses[$ArrIndx]['ClassName']	=	$Value;
			
			if(in_array($Key,$SelShipClasses))
				$ShippingClasses[$ArrIndx]['Selected']	=	'Yes';
			else
				$ShippingClasses[$ArrIndx]['Selected']	=	'No';
			
			$ArrIndx++;	
		}
		
		return $ShippingClasses;
	}
	
	
	/**
	 * The following method returns the delivery types associated with InterShipper
	 *
	 * @return Array
	 */
	function getInterShipperDeliveryTypes()
	{
		$DeliveryTypes	=	array();
		
		foreach($this->INTERSHIP_DELIVERYTYPES as $Key => $Value) {
			$DeliveryTypes['DeliveryCode'][]	=	$Key;
			$DeliveryTypes['DeliveryType'][]	=	$Value;
		}
		
		return $DeliveryTypes;
	}

	
	/**
	 * The following method returns the Intershipper available shipping methods
	 *
	 * @return unknown
	 */
	function getInterShipperShippingMethods()
	{
		$ShippingMethods	=	array();
		
		foreach($this->INTERSHIP_SHIP_METHODS as $Key => $Value) {
			$ShippingMethods['ShipMethodCode'][]	=	$Key;
			$ShippingMethods['ShipMethodType'][]	=	$Value;
		}
		
		return $ShippingMethods;
	}
	
	
	/**
	 * The following method returns the Intershipper packaging types
	 *
	 * @return Array
	 */
	function getInterShippingPackagingTypes()
	{
		$PackagingTypes	=	array();
		
		foreach($this->INTERSHIP_PACKAGING as $Key => $Value) {
			$PackagingTypes['PackageCode'][]	=	$Key;
			$PackagingTypes['PackageType'][]	=	$Value;
			
		}
		return $PackagingTypes;
	}

	
	/**
	 * The following method returns the Intershipper package contentt type allowed by the shipping companies
	 *
	 * @return unknown
	 */
	function getInterShipperShippingContents()
	{
		$ShippingContentTypes	=	array();
		
		foreach($this->INTERSHIP_CONTENTS as $Key => $Value) {
			$ShippingContentTypes['ContentCode'][]	=	$Key;
			$ShippingContentTypes['ContentType'][]	=	$Value;
		}
		return $ShippingContentTypes;
	}


	
	/**
	 * The following method saves the InterShipper form details
	 *
	 * @param Array $REQUEST
	 */
	function saveInterShipperForm($REQUEST)
	{
		extract($REQUEST);
		$InterShipArr	=	array();
				
		$InterShipArr[0]		=	$Interuser_name;
		$InterShipArr[1]		=	$Interpassword;
		
		if(count($InterClasses) > 0)
			$InterShipArr[2]		=	implode('~!', $InterClasses);
		else
			$InterShipArr[2]		=	'';	
			
		
		$InterShipArr[3]		=	$InterDeliveryType;
		$InterShipArr[4]		=	$InterShippingMethod;
		$InterShipArr[5]		=	$Interdefaultlength;
		$InterShipArr[6]		=	$Interdefaultwidth;
		$InterShipArr[7]		=	$Interdefaultheight;
		$InterShipArr[8]		=	$InterPackaging;
		$InterShipArr[9]		=	$InterContents;
		
		$InterShipperData		=	implode(VALUE_SEPERATOR, $InterShipArr);
		
		$Qry1	=	"UPDATE shippingmethod_to_store SET intershipper_values = '$InterShipperData' WHERE store_id = '$store_id'";
		$this->db->query($Qry1);
		
		$Qry2	=	"DELETE FROM shipping_services WHERE store_id ='$store_id' AND shipmethod_id = '$shipmethod_id'";
		$this->db->query($Qry2);
		
		foreach($InterShipServices as $InterShipService) {
			$Qry3	=	"INSERT INTO shipping_services (store_id,shipmethod_id,servicevalue) 
						VALUES ('$store_id','$shipmethod_id','$InterShipService')";
			$this->db->query($Qry3);
		}
		
		return true;
	}
	
	
	############################## InterShipper Section Ends #################################################
	
	
	

	
	################ Common Utility function for shipping, that are used by the extenal modules for shipping process ################ 

	# The following method returns the active Shipping methods of a store
	function getShipMethodsComboOfCombo($store_id)
	{
		$ShippingMethods	=	array(); # An array of two keys 	'Id'	 and 	'ShipMethod'
		
		$Qry		=	"SELECT * FROM shippingmethod_to_store WHERE  store_id = '$store_id'";
		$Row		=	$this->db->get_row($Qry, ARRAY_A);
		
		if($Row['ups_active'] == 'Y') {
			$ShippingMethods['Ids'][]			=	UpsId;
			$ShippingMethods['ShipMethod'][]	=	Ups;
		}
		
		if($Row['usps_active'] == 'Y') {
			$ShippingMethods['Ids'][]			=	UspsId;
			$ShippingMethods['ShipMethod'][]	=	Usps;
		}
		
		if($Row['fedex_active'] == 'Y') {
			$ShippingMethods['Ids'][]			=	FedexId;
			$ShippingMethods['ShipMethod'][]	=	Fedex;
		}
					
		if($Row['canadaPost_active'] == 'Y') {
			$ShippingMethods['Ids'][]			=	canadaPostId;
			$ShippingMethods['ShipMethod'][]	=	canadaPost;
		}
		
		if($Row['dhl_active'] == 'Y') {
			$ShippingMethods['Ids'][]			=	DHLId;
			$ShippingMethods['ShipMethod'][]	=	DHL;
		}
		
		if($Row['intershipper_active'] == 'Y') {
			$ShippingMethods['Ids'][]			=	InterShipperId;
			$ShippingMethods['ShipMethod'][]	=	InterShipper;
		}
		
		return $ShippingMethods;

	} # Close method definition



	/**
	 * The following method calculates the Shipping price when we pass the store_id and a list of parameters as input parameter
	 * @service_id is the delivery code of particular shipping method
	 * 
	 * @param unknown_type $store_id
	 * @param unknown_type $shipmethod_id
	 * @param unknown_type $service_id
	 * @param unknown_type $Params
	 * @return unknown
	 */
	function getQuote($store_id, $shipmethod_id,$service_id, $Params)
	{
		$Results			=	array();

		$store_id			=	(trim($store_id) != '') ? $store_id : 0;
		$ShipMethodDetails	=	$this->getShippingMethodDetailsFromId($shipmethod_id);
		$ShipFile			=	$ShipMethodDetails['quote_file'];
		$ClassName			=	$ShipMethodDetails['class_name'];
		$MethodName			=	'get'.$ClassName.'Quote';
		
		if(file_exists(FRAMEWORK_PATH.''.$ShipFile))
			require_once FRAMEWORK_PATH.''.$ShipFile;
		
		$Qry1	=	"SELECT ups_values,usps_values,fedex_values FROM shippingmethod_to_store WHERE 
					store_id = $store_id";
		$Row1	=	$this->db->get_row($Qry1, ARRAY_A);
				
		$Qry2			=	"SELECT servicevalue FROM shipping_services WHERE id = '$service_id'";
		$Row2			=	$this->db->get_row($Qry2, ARRAY_A);
		$servicevalue	=	$Row2['servicevalue'];
		
		$ShipMethodObj	=	new $ClassName($store_id, $Params, $this);
		
		# Shipping method selected by the 
		if(UpsId == $shipmethod_id) {
			$UpsArr			=	explode(VALUE_SEPERATOR, $Row1['ups_values']);
			
			$UpsDetails					=	array();
			$UpsDetails['user_name']	=	$UpsArr[0];
			$UpsDetails['password']		=	$UpsArr[1];
			$UpsDetails['user_key']		=	$UpsArr[2];
			$UpsDetails['upsType']		=	$UpsArr[3];
			$UpsDetails['upsPackage']	=	$UpsArr[4];
			$UpsDetails['servicevalue']	=	$servicevalue;
			$Results		=	$ShipMethodObj->$MethodName($Params,$UpsDetails); # Calling the method
			return $Results;
		}

		if(UspsId == $shipmethod_id) {
			$UspsArr			=	explode(VALUE_SEPERATOR, $Row1['usps_values']);
			$UspsDetails		=	array();
			
			if($Params['dst_country'] == 'UNITED STATES')
				$Params['dst_country']	=	'USA';
			
			$UspsDetails['user_name']		=	$UspsArr[0];
			$UspsDetails['password']		=	$UspsArr[1];
			$UspsDetails['uspsBoxType']		=	$UspsArr[2];
			$UspsDetails['uspsPackageType']	=	$UspsArr[3];
			$UspsDetails['live_server']		=	$UspsArr[4];
			$UspsDetails['servicevalue']	=	trim($servicevalue);
			
			$Results		=	$ShipMethodObj->$MethodName($Params,$UspsDetails); # Calling the method
			return $Results;
		}
		
		if(FedexId == $shipmethod_id) {
			$FedexArr		=	array();
			$FedexDetails	=	array();
			$FedexArr		=	explode(VALUE_SEPERATOR, $Row1['fedex_values']);
			
			$FedexDetails['Server']				=	$FedexArr[10];
			$FedexDetails['AccountNumber']		=	$FedexArr[0];
			$FedexDetails['MeterNumber']		=	$FedexArr[1];
			$FedexDetails['DropoffType']		=	$FedexArr[9];
			$FedexDetails['Packaging']			=	$FedexArr[2];
			$FedexDetails['Service']			=	$servicevalue;
			
			$Params['OAStateOrProvinceCode']	=	$FedexArr[5];
			$Params['OAPostalCode']				=	$FedexArr[6];
			$Params['OACountryCode']			=	$FedexArr[7];
			
			$Results		=	$ShipMethodObj->$MethodName($Params,$FedexDetails); # Calling the method
			
			return $Results;
		}
		
		if(canadaPostId == $shipmethod_id) {
			
			$Qry1	=	"SELECT canadaPost_values FROM shippingmethod_to_store WHERE 
					store_id = $store_id";
			$Row1	=	$this->db->get_row($Qry1, ARRAY_A);
			
			$canadaPostArr			=	explode(VALUE_SEPERATOR, $Row1['canadaPost_values']);
			$canadaPostDetails		=	array();
			
			
			if($Params['dst_country'] == 'UNITED STATES')
				$Params['dst_country']	=	'USA';
			
			$canadaPostDetails['canadaPost_server']			=	$canadaPostArr[0];
			$canadaPostDetails['customer_id']				=	$canadaPostArr[1];
			$canadaPostDetails['city']						=	$canadaPostArr[2];
			$canadaPostDetails['province']					=	$canadaPostArr[3];
			$canadaPostDetails['country']					=	$canadaPostArr[4];
			$canadaPostDetails['zip']						=	$canadaPostArr[5];
			
			//$canadaPostDetails['servicevalue']		=	trim($servicevalue);
			
			$Results		=	$ShipMethodObj->$MethodName($Params,$canadaPostDetails); # Calling the method
			return $Results;
		}
		
		if(DHLId == $shipmethod_id) {
			$Qry1	=	"SELECT dhl_values FROM shippingmethod_to_store WHERE 
					store_id = $store_id";
			$Row1	=	$this->db->get_row($Qry1, ARRAY_A);
			
			$DHLArr			=	explode(VALUE_SEPERATOR, $Row1['dhl_values']);
			$DHLDetails		=	array();
			
			$DHLDetails['userid']		=	$DHLArr[0];
			$DHLDetails['pwd']			=	$DHLArr[1];
			$DHLDetails['actnumber']	=	$DHLArr[2];
			$DHLDetails['zipcode']		=	$DHLArr[3];
			$DHLDetails['shipkey']		=	$DHLArr[4];
			$DHLDetails['dhlShipType']	=	$DHLArr[5];
			$DHLDetails['dhlBillType']	=	$DHLArr[6];
			//$UpsDetails['servicevalue']	=	$servicevalue;
			$Results		=	$ShipMethodObj->$MethodName($Params,$DHLDetails); # Calling the method
			return $Results;
		}
		
		if(InterShipperId == $shipmethod_id) {
			$Qry1	=	"SELECT intershipper_values FROM shippingmethod_to_store WHERE 
						store_id = $store_id";
			$Row1	=	$this->db->get_row($Qry1, ARRAY_A);
			
			$InterShippArr		=	explode(VALUE_SEPERATOR, $Row1['intershipper_values']);
			$InterShipDetails	=	array();
			
			$InterShipDetails['Interuser_name']			=	$InterShippArr[0];
			$InterShipDetails['Interpassword']			=	$InterShippArr[1];
			$InterShipDetails['InterClasses']			=	$InterShippArr[2];
			$InterShipDetails['InterDeliveryType']		=	$InterShippArr[3];
			$InterShipDetails['InterShippingMethod']	=	$InterShippArr[4];
			$InterShipDetails['Interdefaultlength']		=	$InterShippArr[5];
			$InterShipDetails['Interdefaultwidth']		=	$InterShippArr[6];
			$InterShipDetails['Interdefaultheight']		=	$InterShippArr[7];
			$InterShipDetails['InterPackaging']			=	$InterShippArr[8];
			$InterShipDetails['InterContents']			=	$InterShippArr[9];

			if($InterShipDetails['InterClasses'] != '')
				$Classes	=	explode('~!', $InterShipDetails['InterClasses']);
			
			if((float)$Params['length'] <= 0)
				$Params['length']	=	$InterShipDetails['Interdefaultlength'];
			if((float)$Params['width'] <= 0)
				$Params['width']	=	$InterShipDetails['Interdefaultwidth'];
			if((float)$Params['height'] <= 0)
				$Params['height']	=	$InterShipDetails['Interdefaultheight'];
						
			$Results		=	$ShipMethodObj->$MethodName($Params, $InterShipDetails, $Classes);
			return $Results;			
		}
		
		
		if(AusPostId == $shipmethod_id) {
			$Msg			=	'';
			$MsgArr			=	array();
			$AusPostDetails	=	array();
			$services		=	array();
			
			$Qry1	=	"SELECT AusPost_values FROM shippingmethod_to_store WHERE 
					store_id = $store_id";
			$Row1	=	$this->db->get_row($Qry1, ARRAY_A);
			
			$AusPostArr			=	explode(VALUE_SEPERATOR, $Row1['AusPost_values']);
			
		//	$Params['Pickup_Postcode']					=	$AusPostArr[0];
			//$Params['OACountryCode']					=	$REQUEST['fedexCountry'];
			#$Params['Height']							= 10;
			#$Params['Length']							= 120;
			#$Params['Width']							= 200;
			#$Params['Quantity']							= 1;
			$Params['Weight']							=$Params['Weight']*10000;
			$Params['Service_Type']						=$servicevalue;
			#$Params['Country']							=	'US';
			//print_r($Params);exit;
		//	foreach($services as $service) {
			//	$Results					=	array();
			//	$AusPostDetails['Service']	=	$service;
				$Results		=	$ShipMethodObj->$MethodName($Params); # Calling the method
			//print_r($Results);exit;
			//	foreach($Results as $result) {
			//	print_r($result["method"]);

			//	if($Results['Got'] == 'Yes')
				//	continue;

			//	$MsgArr[]		=	$result["method"];
				
			//} # Close foreach
			//if(count($MsgArr) > 0){
			//	return TRUE;
			//}
			//	$Msg		=	implode('<br>',$MsgArr);
				
			
//print_r($Params);exit;
		return $Results;
						
		}

	} # Close method definition
	
	
	# The following method is used for testing the Shipping price calculation at the admin side
	function testGetQuote($Params, $REQUEST, $ShipMethod)
	{
		$Results			=	array();
		
		if('Ups' == $ShipMethod)
			$shipmethod_id	=	UpsId;
		else if('Usps' == $ShipMethod) 
			$shipmethod_id	=	UspsId;	
		else if('Fedex' == $ShipMethod) 
			$shipmethod_id	=	FedexId;		
		else if('canadaPost' == $ShipMethod) 
			$shipmethod_id	=	canadaPostId;
		else if('AusPost' == $ShipMethod) 
			$shipmethod_id	=	AusPostId;		
		
		$ShipMethodDetails	=	$this->getShippingMethodDetailsFromId($shipmethod_id);
		$ShipFile			=	$ShipMethodDetails['quote_file'];
		$ClassName			=	$ShipMethodDetails['class_name'];
		$MethodName			=	'get'.$ClassName.'Quote';
		

		if(file_exists(FRAMEWORK_PATH.''.$ShipFile)) {
			require_once FRAMEWORK_PATH.''.$ShipFile;
		}

		$ShipMethodObj	=	new $ClassName();

		# The following code checks the Ups shipping method
		if('Ups' == $ShipMethod) {
			
			$Msg		=	'';
			$MsgArr		=	array();
			
			$UpsDetails					=	array();
			$UpsDetails['user_name']	=	$REQUEST['user_name'];
			$UpsDetails['password']		=	$REQUEST['password'];
			$UpsDetails['user_key']		=	$REQUEST['user_key'];
			$UpsDetails['upsType']		=	$REQUEST['upsType'];
			$UpsDetails['upsPackage']	=	$REQUEST['upsPackage'];
			$upsSvcs					=	$REQUEST['upsSvcs'];
			
			foreach($upsSvcs as $upsSvc) {
				$UpsDetails['servicevalue']	=	$upsSvc;
				
				if($upsSvc == 'XPR' || $upsSvc == 'XDM' || $upsSvc == 'XPRL' || $upsSvc == 'XDML' || $upsSvc == 'XPD')
					$Params['dst_country']		=	'CA';

				$Results		=	$ShipMethodObj->$MethodName($Params,$UpsDetails); # Calling the method
			
				if($Results['Got'] == 'No') {
				
					$ServiceName	=	$this->UPS_SHIP_SERVICE_ARRAY[$UpsDetails['servicevalue']];
					$MsgArr[]	=	"$ServiceName-->".$Results['Message'];
					
				}	
				
			} # Close foreach
//exit;
			if(count($MsgArr) > 0) 
				$Msg		=	trim(implode("<br>",$MsgArr));
					
			return $Msg;
		}
		
		
		# The following method checks the Usps shipping price calculation methods
		if('Usps' == $ShipMethod) {
			$Msg		=	'';
			$MsgArr		=	array();
			$UpsDetails	=	array();
			
			$UspsDetails['live_server']		=	$REQUEST['live_server'];
			$UspsDetails['user_name']		=	$REQUEST['user_name'];
			$UspsDetails['password']		=	$REQUEST['password'];
			$UspsDetails['uspsBoxType']		=	$REQUEST['uspsBoxType'];
			$UspsDetails['uspsPackageType']	=	$REQUEST['uspsPackageType'];
			
			$uspsDomSvcs		=	$REQUEST['uspsDomSvcs']; # USPS Domestic services
			
			if(count($uspsDomSvcs) == 0)
				return $Msg;
			
			foreach($uspsDomSvcs as $uspsDomSvc) {
				$UspsDetails['servicevalue']	=	$uspsDomSvc;
				$Results		=	$ShipMethodObj->$MethodName($Params,$UspsDetails); # Calling the method

				if($Results['Got'] == 'Yes')
					continue;
					
				$MsgArr[]		=	$this->USPS_DOMESTIC_ORDER_ARRAY[$uspsDomSvc].'-->'.$Results['Message'];
			}

			if(count($MsgArr) > 0)
				$Msg		=	implode('<br>',$MsgArr);
			
			return $Msg;

		} # Close Usps if ship method
		
				
		# The following method checks the AusPost shipping rate calculation according to the data entered in the AusPost 
		if('AusPost' == $ShipMethod) {
			$Msg			=	'';
			$MsgArr			=	array();
			$AusPostDetails	=	array();
			$services		=	array();
			
			$services							=	$REQUEST['services']; # AusPost selected services
			
			/*$Params['OAStateOrProvinceCode']	=	'TN';
			$Params['OAPostalCode']				=	'37115';
			$Params['OACountryCode']			=	'US'; */
			
			$Params['Pickup_Postcode']					=	$this->config['product_shipping_zip'];
			//$Params['OACountryCode']					=	$REQUEST['fedexCountry'];
			$Params['Height']							= 10;
			$Params['Length']							= 120;
			$Params['Width']							= 200;
			$Params['Quantity']							= 1;
			$Params['Weight']							=	15000;
			$Params['Destination_Postcode']				=	'62670';
			$Params['Country']							=	'US';
			
		//	foreach($services as $service) {
				$Results					=	array();
			//	$AusPostDetails['Service']	=	$service;
				$Results		=	$ShipMethodObj->$MethodName($Params); # Calling the method
				foreach($Results as $result) {
			//	print_r($result["method"]);

			//	if($Results['Got'] == 'Yes')
				//	continue;

				$MsgArr[]		=	$result["method"];
				
			} # Close foreach
			if(count($MsgArr) > 0){
				return TRUE;
			}
				$Msg		=	implode('<br>',$MsgArr);
				
			
//print_r($Msg);exit;
			return $Msg;
						
		}
		# The following method checks the Fedex shipping rate calculation according to the data entered in the fedex 
		if('Fedex' == $ShipMethod) {
			$Msg			=	'';
			$MsgArr			=	array();
			$FedexDetails	=	array();
			$services		=	array();
			
			$services							=	$REQUEST['services']; # Fedex selected services
			$FedexDetails['Server']				=	$REQUEST['fedex_server'];
			$FedexDetails['AccountNumber']		=	$REQUEST['account_number'];
			$FedexDetails['MeterNumber']		=	$REQUEST['meter'];
			$FedexDetails['DropoffType']		=	$REQUEST['fedexDropofftype'];
			$FedexDetails['Packaging']			=	$REQUEST['fedexPackaging'];
			
			/*$Params['OAStateOrProvinceCode']	=	'TN';
			$Params['OAPostalCode']				=	'37115';
			$Params['OACountryCode']			=	'US'; */
			
			$Params['OAStateOrProvinceCode']	=	$REQUEST['fedexState'];
			$Params['OAPostalCode']				=	$REQUEST['fedexZip'];
			$Params['OACountryCode']			=	$REQUEST['fedexCountry'];
			
			$Params['Weight']					=	20;
			
			$Params['DAStateOrProvinceCode']	=	'TX';
			$Params['DAPostalCode']				=	'73301';
			$Params['DACountryCode']			=	'US';
			
			foreach($services as $service) {
				$Results					=	array();
				$FedexDetails['Service']	=	$service;
				//print_r($MethodName);exit;
				$Results		=	$ShipMethodObj->$MethodName($Params,$FedexDetails); # Calling the method

				if($Results['Got'] == 'Yes')
					continue;

				$MsgArr[]		=	$this->FEDEX_SHIP_SERVICES[$service].'-->'.$Results['Message'];
				
			} # Close foreach
			
			if(count($MsgArr) > 0)
				$Msg		=	implode('<br>',$MsgArr);

			return $Msg;
						
		} # Close Fedex ship method
		
		
		
		
		# The following method checks the canadaPost shipping rate calculation according to the data entered in the canadaPost 
		if('canadaPost' == $ShipMethod) {
			$Msg			=	'';
			$MsgArr			=	array();
			$canadaPostDetails	=	array();
			$services		=	array();
			

			$services							=	$REQUEST['services']; # Fedex selected services
			$canadaPostDetails['Server']		=	$REQUEST['canadaPost_server'];
			$canadaPostDetails['customer_id']	=	$REQUEST['customer_id'];
			
			
			$Params['canadaPostCity']			=	$REQUEST['canadaPostCity'];
			$Params['canadaPostState']			=	$REQUEST['canadaPostState'];
			$Params['canadaPostCountry']		=	$REQUEST['canadaPostCountry'];
			$Params['canadaPostZip']			=	$REQUEST['canadaPostZip'];
			

			$Results						=	array();


			$rate = new _canadapost($canadaPostDetails['Server'], '30000', 'en', $canadaPostDetails['customer_id'], $REQUEST['canadaPostZip'], '8');
			$rate->setDestination ($REQUEST['canadaPostCity'],$REQUEST['canadaPostState'],$REQUEST['canadaPostCountry'],$REQUEST['canadaPostZip']);
			$rate->addItem (2, 23.8, 1.5, 10, 10, 10, 'test stuff1');

			$Results = $rate->getShppingProducts();  


			if ( ! is_array ($Results) )
			$MsgArr[]		=	$Results;
			

			if(count($MsgArr) > 0)
				$Msg		=	implode('<br>',$MsgArr);

			return $Msg;
						
		} # Close CanadaPost ship method

		
		
	} # Close testeGetQuote method
	
	
	
	# The following method returns the shipping tracking URL 
	function getTrackURL($shipmethod, $ShippingOrderNumber)
	{
		$File	=	FRAMEWORK_PATH.''.'/includes/shipping/trackshipping/class.shiptrack.php';
		
		if(trim($shipmethod) == '' || trim($ShippingOrderNumber) == '' )
			return 'Not Available';
		
		if(file_exists($File))
			require_once $File;
		else 
			return 'Not Available';
		
		if($shipmethod == 'Ups')
			$shipmethod_id	=	UpsId;
		else if($shipmethod == 'Usps')
			$shipmethod_id	=	UspsId;
		else if($shipmethod == 'Fedex')
			$shipmethod_id	=	FedexId;	
				
		$TrackCode		=	$this->getTrackCodeOfShippingMethod($shipmethod_id);
		$ShipTrackObj	=	new ShipTrack();
		$TrackURL		=	$ShipTrackObj->PrintLink($TrackCode,$ShippingOrderNumber,"1","","_blank");
		return 	$TrackURL;	
		
	} # Close class definition
	
	
	# The following method returns the Track code ralted with a particular shipping method
	function getTrackCodeOfShippingMethod($shipmethod_id)
	{
		$Qry		=	"SELECT track_code FROM shipping_methods WHERE shipmethod_id = '$shipmethod_id'";
		$Row		=	$this->db->get_row($Qry, ARRAY_A);
		$track_code	=	$Row['track_code'];
		return $track_code;
	}
	
	# The following method returns the country name withn the country2 code for combo filling
	function getCountryComboValues()
	{
		$CountryArr	=	array();
		
		$Qry		=	"SELECT * FROM country_master";
		$Result		=	$this->db->get_results($Qry,ARRAY_A);
		if($Result) {
			foreach($Result as $Row) {
				$CountryArr['dbvalue'][]	=	$Row['country_2_code'];
				$CountryArr['label'][]		=	$Row['country_name'];
			} # Close foreach
		} # Close if 
		return $CountryArr;
	}
	
	
	# The folowing method returns the two digit country code of country id entered
	function getCountry2code($country_id)
	{	
		$Qry			=	"SELECT country_2_code FROM country_master WHERE country_id = '$country_id'";
		$Row			=	$this->db->get_row($Qry, ARRAY_A);
		$country_2_code	=	$Row['country_2_code'];	
		return $country_2_code;
	}
	
	# The following method returns the shipping country id
	function getShippingCountryId()
	{
		$Country2Code	=	$this->config['product_shipping_country'];	
		$Qry			=	"SELECT country_id FROM country_master WHERE country_2_code = '$Country2Code'";
		$Row			=	$this->db->get_row($Qry, ARRAY_A);
		$country_id		=	$Row['country_id'];
		return $country_id;
	}
	
	
	
	
	# The following method returns the storeid of the corresponding storename
	function getStoreIdFromStoreName($storename)
	{
		$storename	=	(trim($storename) != '') ? $storename : '0';
		if($storename == '0')
			return 0;
		
		$Qry1		=	"SELECT id FROM store WHERE name = '$storename'";
		$Result1	=	$this->db->get_row($Qry1, ARRAY_A);
		$id			=	$Result1['id'];
		return $id;
	}
	
	
	# The following method returns the shipping methods for combo filling at the Shipping page
	function getShippingMethodsForComboFilling($StoreName)
	{
		$rec            =   $this-> getPaymentReceiver();
		$StoreName		=	(trim($StoreName) != '') ? $StoreName : '0';
		$ShipMethods	=	array();
		//print_r($rec);
		//exit;
		if($StoreName != '0') {
			if($rec=='admin'){
				$StoreId	=	'0';
			}else{
				$Qry1		=	"SELECT id FROM store WHERE name = '$StoreName'";
				$Result1	=	$this->db->get_row($Qry1,ARRAY_A);
				$StoreId	=	$Result1['id'];
			}
		} else {
			$StoreId	=	'0';
		}	
		
		$Qry2		=	"SELECT 
							COUNT(*) AS ConfCount 
						FROM shippingmethod_to_store 
						WHERE store_id = '$StoreId'";
		$Result2	=	$this->db->get_row($Qry2,ARRAY_A);
		$ConfCount	=	$Result2['ConfCount'];
		
		
		
		if($ConfCount == 0)
			return $ShipMethods;
		
		$Qry3		=	"SELECT * FROM shippingmethod_to_store WHERE store_id = '$StoreId'";
		$Result3	=	$this->db->get_row($Qry3,ARRAY_A);
		if($Result3['ups_active'] == 'Y') {
			$ShipMethods['dbvalue'][]		=	UpsId;
			$ShipMethods['label'][]			=	'Ups';
		}

		if($Result3['usps_active'] == 'Y') {
			$ShipMethods['dbvalue'][]		=	UspsId;
			$ShipMethods['label'][]			=	'Usps';
		}

		if($Result3['fedex_active'] == 'Y') {
			$ShipMethods['dbvalue'][]		=	FedexId;
			$ShipMethods['label'][]			=	'Fedex';
		}
		
		if($Result3['canadaPost_active'] == 'Y') {
			$ShipMethods['dbvalue'][]		=	canadaPostId;
			$ShipMethods['label'][]			=	'CanadaPost';
		}
		
		if($Result3['dhl_active'] == 'Y') {
			$ShipMethods['dbvalue'][]		=	DHLId;
			$ShipMethods['label'][]			=	'DHL';
		}
		
		if($Result3['intershipper_active'] == 'Y') {
			$ShipMethods['dbvalue'][]		=	InterShipperId;
			$ShipMethods['label'][]			=	InterShipper;
		}
		
		if($Result3['AusPost_active'] == 'Y') {
			$ShipMethods['dbvalue'][]		=	AusPostId;
			$ShipMethods['label'][]			=	AusPost;
		}
		if($Result3['localpickup_active'] == 'Y') {
			$ShipMethods['dbvalue'][]		=	LocalPickUpId;
			$ShipMethods['label'][]			=	LocalPickUp;
		}

		return $ShipMethods;
		
	} # Close fuction definition 
	
	
	
	/**
	 * The following method returns the shipping services associated with a shipping method
	 * 	
	 * @param unknown_type $shipmethod_id
	 * @param unknown_type $country
	 * @param unknown_type $postalcode
	 * @param unknown_type $store_name
	 * @param unknown_type $weight
	 * @param unknown_type $ship_state
	 * @param unknown_type $height
	 * @param unknown_type $length
	 * @param unknown_type $width
	 * @param unknown_type $quantity
	 * @return unknown
	 */
	function getAllShippingServicesOfShippingMethod($shipmethod_id, $country, $postalcode, $store_name, $weight, $ship_state, $height = 0, $length = 0, $width = 0, $quantity = 1)
	{
		$storeid		=	$this->getStoreIdFromStoreName($store_name);
		$SrcCountry		=	trim($this->config['product_shipping_country']);
		$SrcZipCode		=	$this->config['product_shipping_zip'];
		
		$Qry2		=	"SELECT country_2_code,country_3_code,country_name FROM country_master WHERE country_id = '$country'";
		$Result2	=	$this->db->get_row($Qry2, ARRAY_A);

		
		#Ups Block starts Here
		if(UpsId	==	$shipmethod_id) {
			
			$Methods	=	array();

			$Qry1		=	"SELECT id,servicevalue FROM shipping_services WHERE store_id = '$storeid' AND shipmethod_id = '$shipmethod_id'";
			$Results1	=	$this->db->get_results($Qry1,ARRAY_A);
		
			if($SrcCountry == 'US' || $SrcCountry == 'USA' || $SrcCountry == 'UNITED STATES') 
				$SrcCountry		=	"US";
			
			$Params		=	array();
			$Params['src_zip']			=	$SrcZipCode;
			$Params['src_country']		=	$SrcCountry;
			$Params['weight']			=	$weight;
			$Params['dst_country']		=	$Result2['country_2_code'];
			$Params['dst_zip']			=	$postalcode;
			
			
			$Arrindx	=	0;
			foreach($Results1 as $Result1) {
				$Result	=	$this->getQuote($storeid, $shipmethod_id,$Result1['id'], $Params);
				
				if($Result['Got'] == 'Yes') {
					
					#### The following If condition is Added  to hide the price from shipping option label......
					#### Added on 05 May 2008...................................................................
					#### Added By Jipson thomas.................................................................
					//print_r($this->config["not_show_shipping_price_label"]);exit;
					if($this->config["not_show_shipping_price_label"]=="Y"){
						$Result['Price']=$Result['Price']+5;
						$value			=	$this->UPS_SHIP_SERVICE_ARRAY[$Result1['servicevalue']].'*^*Ups*^*'.$Result['Price'];
						$label			=	$this->UPS_SHIP_SERVICE_ARRAY[$Result1['servicevalue']];
					}else{
						$value			=	$this->UPS_SHIP_SERVICE_ARRAY[$Result1['servicevalue']].'*^*Ups*^*'.$Result['Price'];
						$label			=	$this->UPS_SHIP_SERVICE_ARRAY[$Result1['servicevalue']].'--'.'$'.$Result['Price'];
					}
					#### End of  If condition is Added  to hide the price from shipping option label............
					//$label			=	$this->UPS_SHIP_SERVICE_ARRAY[$Result1['servicevalue']].'--'.$Result['Price'].'$';
					
					$Methods[$Arrindx]['dbvalue']	=	$value;
					$Methods[$Arrindx]['label']		=	$label;
					$Arrindx++;
				}
			}
			return $Methods;
			
		}
		
		
		# If the selected method is Usps
		if(UspsId	==	$shipmethod_id) {
			
			$Params		=	array();
			$Params['src_zip']			=	$SrcZipCode;
			$Params['weight']			=	$weight;
			$Params['dst_country']		=	$Result2['country_name'];
			$Params['dst_zip']			=	$postalcode;
			$Params['ounce']			=	'';
			
			if(trim($Params['dst_country']) === 'UNITED STATES')
				$Qry3		=	"SELECT id,servicevalue FROM shipping_services WHERE store_id = '$storeid' AND shipmethod_id = '$shipmethod_id' AND domestic = 'Y'";
			else
				$Qry3		=	"SELECT id,servicevalue FROM shipping_services WHERE store_id = '$storeid' AND shipmethod_id = '$shipmethod_id' AND international = 'Y'";
			$Results3	=	$this->db->get_results($Qry3,ARRAY_A);
			
			if(count($Results3) == 0)
				return $Methods;
			
			$Arrindx	=	0;
			foreach($Results3 as $Result3) {
				$Result	=	$this->getQuote($storeid, $shipmethod_id,$Result3['id'], $Params);
				
				if($Result['Got'] == 'Yes') {
					$servicevalue	=	trim($Result3['servicevalue']);
					
					if($Params['dst_country'] == 'UNITED STATES')
						$service			=	$this->USPS_DOMESTIC_ORDER_ARRAY[$servicevalue];
					else
						$service			=	$this->USPS_INTNL_ORDER_ARRAY[$servicevalue];
					
					
					#### The following If condition is Added  to hide the price from shipping option label......
					#### Added on 05 May 2008...................................................................
					#### Added By Jipson thomas.................................................................
					//print_r($this->config["not_show_shipping_price_label"]);exit;
					if($this->config["not_show_shipping_price_label"]=="Y"){
						$Result['Price']=$Result['Price']+5;
						$value			=	$service.'*^*Usps*^*'.$Result['Price'];

						$Methods[$Arrindx]['dbvalue']	=	$value;
						$Methods[$Arrindx]['label']		=	$service;
					}else{
						$value			=	$service.'*^*Usps*^*'.$Result['Price'];

						$Methods[$Arrindx]['dbvalue']	=	$value;
						$Methods[$Arrindx]['label']		=	$service.'--'.$Result['Price'].'$';
					}
					#### End of  If condition is Added  to hide the price from shipping option label............
					//$Methods[$Arrindx]['label']		=	$service.'--'.$Result['Price'].'$';
					$Arrindx++;
				}
			}
			
			return $Methods;
		}
		
		
		# If the selected method is Fedex 
		if(FedexId	==	$shipmethod_id) {
			$Methods	=	array();

			$Qry1		=	"SELECT id,servicevalue FROM shipping_services WHERE store_id = '$storeid' AND shipmethod_id = '$shipmethod_id'";
			$Results1	=	$this->db->get_results($Qry1,ARRAY_A);
			
			$Qry12		=	"SELECT code FROM state_code WHERE country_id = '$country' AND name = '$ship_state'";
			$Results12	=	$this->db->get_row($Qry12,ARRAY_A);
			
			
			$Params		=	array();
			$Params['Weight']					=	$weight;
			$Params['DACountryCode']			=	$Result2['country_2_code'];
			$Params['DAPostalCode']				=	$postalcode;
			#$Params['DAStateOrProvinceCode']	=	'TX';
			$Params['DAStateOrProvinceCode']	=	$Results12['code'];
			
			$Arrindx	=	0;
			foreach($Results1 as $Result1) {
				$Result	=	$this->getQuote($storeid, $shipmethod_id,$Result1['id'], $Params);
				
				if($Result['Got'] == 'Yes') {
					
					#### The following If condition is Added  to hide the price from shipping option label......
					#### Added on 31 March 2008.................................................................
					#### Added By Jipson thomas.................................................................
					//print_r($this->config["not_show_shipping_price_label"]);exit;
					if($this->config["not_show_shipping_price_label"]=="Y"){
						$Result['Price']=$Result['Price']+5;
						$value			=	$this->FEDEX_SHIP_SERVICES[$Result1['servicevalue']].'*^*Fedex*^*'.$Result['Price'];
						$label			=	$this->FEDEX_SHIP_SERVICES[$Result1['servicevalue']];
					}else{
						$value			=	$this->FEDEX_SHIP_SERVICES[$Result1['servicevalue']].'*^*Fedex*^*'.$Result['Price'];
						$label			=	$this->FEDEX_SHIP_SERVICES[$Result1['servicevalue']].'--'.$Result['Price'].'$';
					}
					#### End of  If condition is Added  to hide the price from shipping option label............
					
					$Methods[$Arrindx]['dbvalue']	=	$value;
					$Methods[$Arrindx]['label']		=	$label;
					$Arrindx++;
				}
			}
			return $Methods;

		} 
		
		
		# If the selected method is Australia Post 
		if(AusPostId	==	$shipmethod_id) {
			$Methods	=	array();
			if($Result2["country_2_code"]=="AU" && $SrcCountry=="AU"){
				$Qry1		=	"SELECT id,servicevalue FROM shipping_services WHERE store_id = '$storeid' AND shipmethod_id = '$shipmethod_id' AND domestic='Y'";
			}else{
				$Qry1		=	"SELECT id,servicevalue FROM shipping_services WHERE store_id = '$storeid' AND shipmethod_id = '$shipmethod_id' AND international='Y'";
			}
			//print_r($Qry1);exit;
			$Results1	=	$this->db->get_results($Qry1,ARRAY_A);
			
			//$Qry12		=	"SELECT code FROM state_code WHERE country_id = '$country' AND name = '$ship_state'";
		//	$Results12	=	$this->db->get_row($Qry12,ARRAY_A);
			
			
			$Params		=	array();
			$Params['Weight']					=	$weight;
			$Params['Height']					=	$height;
			$Params['Width']					=	$width;
			$Params['Length']					=	$length;
			$Params['Country']					=	$Result2['country_2_code'];
			$Params['DAPostalCode']				=	$postalcode;
			$Params['Quantity']					=	$quantity;
			$Params['Pickup_Postcode']			=	$SrcZipCode;
			#$Params['DAStateOrProvinceCode']	=	$Results12['code'];
			
			$Arrindx	=	0;
		//	print_r($Results1);exit;
			foreach($Results1 as $Result1) {
				$Result=	$this->getQuote($storeid, $shipmethod_id,$Result1['id'], $Params);
				
				if($Result['err_msg']=="OK") {
					if($Result2["country_2_code"]=="AU" && $SrcCountry=="AU"){
						$value			=	$this->AusPost_DOMESTIC_SHIP_SERVICES[$Result1['servicevalue']].'*^*AusPost*^*'.trim($Result['charge']);
						$label			=	$this->AusPost_DOMESTIC_SHIP_SERVICES[$Result1['servicevalue']].'--'.trim($Result['charge']).'$';
					}else{
						$value			=	$this->AusPost_INTERNATIONAL_SHIP_SERVICES[$Result1['servicevalue']].'*^*AusPost*^*'.trim($Result['charge']);
						$label			=	$this->AusPost_INTERNATIONAL_SHIP_SERVICES[$Result1['servicevalue']].'--'.trim($Result['charge']).'$';
					}
					
					$Methods[$Arrindx]['dbvalue']	=	$value;
					$Methods[$Arrindx]['label']		=	$label;
					$Arrindx++;
				}
			}
			
			return $Methods;

		} 
		
		
		# If the selected method is CanadaPost
		if(canadaPostId	==	$shipmethod_id) {
			$Params		=	array();
			$Params['src_zip']			=	$SrcZipCode;
			$Params['weight']			=	$weight;
			$Params['dst_country']		=	$Result2['country_name'];
			$Params['dst_zip']			=	$postalcode;
			$Params['dst_state']		=   $ship_state;
			$Params['ounce']			=	'';
			
			if(trim($Params['dst_country']) === 'CANADA')
				$Qry3		=	"SELECT id,servicevalue FROM shipping_services WHERE store_id = '$storeid' AND shipmethod_id = '$shipmethod_id' AND domestic = 'Y'";
			elseif(trim($Params['dst_country']) === 'UNITED STATES')
				$Qry3		=	"SELECT id,servicevalue FROM shipping_services WHERE store_id = '$storeid' AND shipmethod_id = '$shipmethod_id' AND usa = 'Y'";
			else
				$Qry3		=	"SELECT id,servicevalue FROM shipping_services WHERE store_id = '$storeid' AND shipmethod_id = '$shipmethod_id' AND international = 'Y'";

			
			$Results3	=	$this->db->get_results($Qry3,ARRAY_A);
			if(count($Results3) == 0)
				return $Methods;

			$Result	=	$this->getQuote($storeid, $shipmethod_id,0, $Params);	
			
			$Arrindx	=	0;
			$selResArray = array();
			if($Result['Got'] == 'Yes') {
				
				foreach($Results3 as $selresultSet) {
					$selResArray[] = strtoupper($selresultSet['servicevalue']);	
				}

				foreach($Result['Price'] as $resultSet) {
					if (in_array(strtoupper($resultSet['name']),$selResArray)) {
						$value			=	$resultSet['name'].'*^*CanadaPost*^*'.$resultSet['rate'];
						
						$Methods[$Arrindx]['dbvalue']	=	$value;
						$Methods[$Arrindx]['label']		=	$resultSet['name'].'--'.$resultSet['rate'].'$';
					}
					$Arrindx++;
				}
			}
			
			return $Methods;
		}
		
		
		# If the selected method is DHL
		if(DHLId	==	$shipmethod_id) {
			
			$Methods	=	array();

			$Qry1		=	"SELECT id,servicevalue FROM shipping_services WHERE store_id = '$storeid' AND shipmethod_id = '$shipmethod_id'";
			$Results1	=	$this->db->get_results($Qry1,ARRAY_A);
		
			if($SrcCountry == 'US' || $SrcCountry == 'USA' || $SrcCountry == 'UNITED STATES') 
				$SrcCountry		=	"US";
			
			$Params		=	array();
			$Params['shipdate']			=	date('Y-m-d');
			$Params['weight']			=	$weight;
			$Params['length']			=	'';
			$Params['width']			=	'';
			$Params['height']			=	'';
			$Params['shipmenttype']		=	'';
			$Params['addtype']			=	'';
			$Params['addvalue']			=	'';
			
			$Arrindx	=	0;
			foreach($Results1 as $Result1) {
				$Result	=	$this->getQuote($storeid, $shipmethod_id,$Result1['id'], $Params);
				
				if($Result['Got'] == 'Yes') {
					$value			=	$this->DHL_NORMAL_SHIP_SERVICES[$Result1['servicevalue']].'*^*DHL*^*'.$Result['Price'];
					$label			=	$this->DHL_NORMAL_SHIP_SERVICES[$Result1['servicevalue']].'--'.$Result['Price'].'$';
					$Methods[$Arrindx]['dbvalue']	=	$value;
					$Methods[$Arrindx]['label']		=	$label;
					$Arrindx++;
				}
			}
			return $Methods;
			
		}
		
		
		
		# InterShipper Starts Here
		if(InterShipperId	==	$shipmethod_id) {
			$Methods	=	array();
			
			$Qry5		=	"SELECT id,servicevalue FROM shipping_services WHERE store_id = '$storeid' AND shipmethod_id = '$shipmethod_id'";
			$Results5	=	$this->db->get_results($Qry5,ARRAY_A);
			
			$Services	=	array();
			foreach($Results5 as $Result5)
				$Services[]	=	$Result5['servicevalue'];
			
			if($SrcCountry == 'US' || $SrcCountry == 'USA' || $SrcCountry == 'UNITED STATES') 
				$SrcCountry		=	"US";
			
			$Params		=	array();
			$Params['src_zip']			=	$SrcZipCode;
			$Params['src_country']		=	$SrcCountry;
			$Params['weight']			=	$weight;
			$Params['dst_country']		=	$Result2['country_2_code'];
			$Params['dst_zip']			=	$postalcode;
			$Params['Services']			=	$Services;
			$Params['length']			=	$length;
			$Params['width']			=	$width;
			$Params['height']			=	$height;
			$Params['Services']			=	$Services;
			
			$InterShipperQuotes	=	$this->getQuote($storeid, $shipmethod_id,$Result1['id'], $Params);
			
			$Arrindx	=	0;
			foreach($InterShipperQuotes as $InterShipperQuote) {
				$value			=	$this->INTERSHIP_SHIP_SERVICE_ARRAY[$InterShipperQuote['ServiceCode']].'*^*InterShipper*^*'.$InterShipperQuote['Amount'];
				$label			=	$this->INTERSHIP_SHIP_SERVICE_ARRAY[$InterShipperQuote['ServiceCode']].'--'.'$'.$InterShipperQuote['Amount'];
				$Methods[$Arrindx]['dbvalue']	=	$value;
				$Methods[$Arrindx]['label']		=	$label;
				$Arrindx++;
			}
			
			return $Methods;
			
		}
		
		
		
		
		
	} #Close method definition
	
	
	# The following method returns the international shipping order message details. If no message set at the admin side return an empty message else an empty message
	function getInternationalOrderMessage($shipmethod_id, $store_name, $country_id)
	{
		
		$store_id		=	$this->getStoreIdFromStoreName($store_name);
		$Message		=	'';
		
		$country_2code	=	trim($this->getCountry2code($country));
		if($country_2code == $this->config['product_shipping_country'])
			return $Message;
		
		$Qry1		=	"SELECT * FROM shippingmethod_to_store WHERE store_id = $store_id";
		$Row1		=	$this->db->get_row($Qry1,ARRAY_A);
		
		if(UpsId === $shipmethod_id) {
			$ups_active	=	$Row1['ups_active'];
			$ups_values	=	$Row1['ups_values'];
			
			$Arr1	=	explode(VALUE_SEPERATOR,$ups_values);	
			$intrn_message_status	=	$Arr1[5];
			$intrn_message			=	$Arr1[6];
			
			if($intrn_message_status === 'Yes')
				$Message	=	$intrn_message;
		}
		
		if(UspsId === $shipmethod_id) {
			$usps_active	=	$Row1['usps_active'];
			$usps_values	=	$Row1['usps_values'];
			
			$Arr2	=	explode(VALUE_SEPERATOR,$usps_values);	
			$intrn_message_status		=	$Arr2[5];
			$intrn_message				=	$Arr2[6];
			
			if($intrn_message_status === 'Yes')
				$Message	=	$intrn_message;
		}
		
		if(FedexId === $shipmethod_id) {
			$fedex_active	=	$Row1['fedex_active'];
			$fedex_values	=	$Row1['fedex_values'];
			
			$Arr3	=	explode(VALUE_SEPERATOR,$fedex_values);	
			$intrn_message_status	=	$Arr3[11];
			$intrn_message			=	$Arr3[12];
			
			if($intrn_message_status === 'Yes')
				$Message	=	$intrn_message;
		}
		
		return $Message;
		
	}
	
	
	# The following method returns the international order message details
	function getInternationalMessageDetails()
	{
		$IntrnlDetails	=	array();
		
		/*$Qry1		=	"SELECT value FROM config WHERE field = 'intrnl_message'";
		$Row1		=	$this->db->get_row($Qry1,ARRAY_A);
		$IntrnlDetails['intrnl_message']	=	$Row1['value'];*/
		
		$Qry2		=	"SELECT value FROM config WHERE field = 'intrnl_message_status'";
		$Row2		=	$this->db->get_row($Qry2,ARRAY_A);
		$IntrnlDetails['intrnl_message_status']	=	$Row2['value'];
		
		/*$Qry3		=	"SELECT value FROM config WHERE field = 'intrnl_message_flat'";
		$Row3		=	$this->db->get_row($Qry3,ARRAY_A);
		$IntrnlDetails['intrnl_message_flat']	=	$Row3['value'];
		*/
		return $IntrnlDetails;
	}
	
	
	
	
	# The following method saves the international message details into the config table
	function saveInternationalMessage($REQUEST)
	{
		
		extract($REQUEST);

		
		if($intrnl_message_status == '') {
			$intrnl_message_status	=	'No';
			$intrnl_message			=	'';
		}	
		
		if($intrnl_message_status == 'Yes' && trim($intrnl_message) == '') {
			$intrnl_message			=	INTERNATIONAL_ORDER_MESSAGE;
		}	
		
		$Qry1	=	"UPDATE config SET value = '$intrnl_message_status' WHERE field = 'intrnl_message_status'";
		$this->db->query($Qry1);
		
		$Qry2	=	"UPDATE config SET value = '$intrnl_message' WHERE field = 'intrnl_message'";
		$this->db->query($Qry2);
		
		$Qry3	=	"UPDATE config SET value = '$intrnl_message_flat' WHERE field = 'intrnl_message_flat'";
		$this->db->query($Qry3);
		
	
		return TRUE;
	}
	

	# The following method returns the international order message
	function getInternationalMessage()
	{
		$Qry1		=	"SELECT value FROM config WHERE field = 'intrnl_message'";
		$Row1		=	$this->db->get_row($Qry1,ARRAY_A);
		$Message	=	wordwrap($Row1['value'],50);	
		
		return $Message;
	}
	
	# The following method returns the ddefault international order message in the shipping configuration file
	function getDefaultInternationalMessage()
	{
		return wordwrap(INTERNATIONAL_ORDER_MESSAGE,50);
	}
	
	
	
	# The following method returns the international order message status whether Yes or No means present or not present
	function getInternationalMessageStatus()
	{
		$Qry1	=	"SELECT value FROM config WHERE field = 'intrnl_message_status'";
		$Row1	=	$this->db->get_row($Qry1,ARRAY_A);
		$intrnl_message_status	=	$Row1['value'];
		return $intrnl_message_status;
	}
		
	
	
	# Function to get Payment Receiver of Config Table
	# Author  Jipson.
	
	function getPaymentReceiver() {
		
		return $this->config['payment_receiver'];
	}
	
	
	/**
  	 * The following method returns the message for pay invoice
	 * Author   : Shinu
  	 * Created  : 30/Nov/2007
  	 * Modified : 30/Nov/2007 By Shinu
  	 */
	function getPayinvoiceMessage()
	{
		$IntrnlDetails	=	array();
		
		$Qry1		=	"SELECT value FROM config WHERE field = 'payinvoice_message'";
		$Row1		=	$this->db->get_row($Qry1,ARRAY_A);
		$IntrnlDetails['invoice_message']	=	$Row1['value'];
		
		$Qry2		=	"SELECT value FROM config WHERE field = 'payinvoice_message_status'";
		$Row2		=	$this->db->get_row($Qry2,ARRAY_A);
		$IntrnlDetails['invoice_message_status']	=	$Row2['value'];
		
		return $IntrnlDetails;
	}
	
	/**
  	 * The following method will save  pay invoice message
	 * Author   : Shinu
  	 * Created  : 30/Nov/2007
  	 * Modified : 30/Nov/2007 By Shinu
  	 */
	function saveInvoiceMessage($REQUEST)
	{
		extract($REQUEST);
		
		if($payinvoice_message_status == '') {
			$payinvoice_message_status	=	'No';
			$payinvoice_message			=	'';
		}	
		
		if($payinvoice_message_status == 'Yes' && trim($intrnl_message) == '') {
			$payinvoice_message			=	'';
		}	
		
		$Qry1	=	"UPDATE config SET value = '$payinvoice_message_status' WHERE field = 'payinvoice_message_status'";
		$this->db->query($Qry1);
		
		$Qry2	=	"UPDATE config SET value = '$payinvoice_message' WHERE field = 'payinvoice_message'";
		$this->db->query($Qry2);
			
		return TRUE;
	}
	
	/**
  	 * The following method will save  the flat rate shiping fee
	 * Author   : adarsh
  	 * Created  : 28/Aug/2008
  	 * Modified : 
  	 */
	function saveInternationalFlatRateShipping($REQUEST)
	{
		extract($REQUEST);
		
		
		$flat_rate_shipping=$this->getFlatRateShipping($store_id);
		$array	=	array('shipping_fee'=>$shipping_fee,
						   "shipping_status"=>$shipping_status,
						   "store_id"=>$store_id,
						   'sp_firstgift'=>$sp_firstgift,
						   'sp_additionalgift'=>$sp_additionalgift,
						   'sp_firstmat'=>$sp_firstmat,
						   'sp_additionalmat'=>$sp_additionalmat,
						   'intr_sp_firstgift'=>$intr_sp_firstgift,
						   'intr_sp_additionalgift'=>$intr_sp_additionalgift,
						   'intr_sp_firstmat'=>$intr_sp_firstmat,
						   'intr_sp_additionalmat'=>$intr_sp_additionalmat,
						   'intr_frs_status'=>$intr_frs_status,
						   'intr_message_one'=>$intrnl_message_flat_one,
						   'intr_message_two'=>$intrnl_message_flat,
						   'sp_firstwood'=>$sp_firstwood,
						   'sp_additionalwood'=>$sp_additionalwood,
						   'sp_firstplaque'=>$sp_firstplaque,
						   'sp_additionalplaque'=>$sp_additionalplaque,
						   'sp_firstglass'=>$sp_firstglass,
						   'sp_additionalglass'=>$sp_additionalglass,
						   'intr_sp_firstwood'=>$intr_sp_firstwood,
						   'intr_sp_additionalwood'=>$intr_sp_additionalwood,
						   'intr_sp_firstplaque'=>$intr_sp_firstplaque,
						   'intr_sp_additionalplaque'=>$intr_sp_additionalplaque,
						   'intr_sp_firstglass'=>$intr_sp_firstglass,
						   'intr_sp_additionalglass'=>$intr_sp_additionalglass,
						   'sp_firstlargewoodframe'=>$sp_firstlargewoodframe,
						   'sp_additionallargewoodframe'=>$sp_additionallargewoodframe,
						   'intr_sp_firstlargewoodframe'=>$intr_sp_firstlargewoodframe,
						   'intr_sp_additionallargewoodframe'=>$intr_sp_additionallargewoodframe);
		
		if(count($flat_rate_shipping)>0){
			//$array=array("shipping_status"=>$shipping_status);
			if($shipping_status=='Y'){
				
			}
			if ($store_id=='')$store_id=0;
			$this->db->update("flat_rate_shipping", $array,"store_id='$store_id'");
		}
		else{
			
			
			$this->db->insert("flat_rate_shipping", $array);
		}
		
		return true;
	}
	/**
  	 * The following method returns the flat rate shipping fee
	 * Author   : Adarsh
  	 * Created  : 28/Aug/2008
  	 * Modified : 
  	 */
	function getFlatRateShipping($store_id)
	{
		$sql="select * from flat_rate_shipping where store_id='$store_id' ";
		$rs	=	$this->db->get_row($sql,ARRAY_A);
		return $rs;
		
	}
	
	
	# The following method returns the shipping methods for combo filling at the Shipping page
	function getShippingMethodsAll($StoreName)
	{
		$rec            =   $this-> getPaymentReceiver();
		$StoreName		=	(trim($StoreName) != '') ? $StoreName : '0';
		$ShipMethods	=	array();
		//print_r($rec);
		//exit;
		if($StoreName != '0') {
			if($rec=='admin'){
				$StoreId	=	'0';
			}else{
				$Qry1		=	"SELECT id FROM store WHERE name = '$StoreName'";
				$Result1	=	$this->db->get_row($Qry1,ARRAY_A);
				$StoreId	=	$Result1['id'];
			}
		} else {
			$StoreId	=	'0';
		}	
		
		$Qry2		=	"SELECT 
							COUNT(*) AS ConfCount 
						FROM shippingmethod_to_store 
						WHERE store_id = '$StoreId'";
		$Result2	=	$this->db->get_row($Qry2,ARRAY_A);
		$ConfCount	=	$Result2['ConfCount'];
		
		
		
		if($ConfCount == 0)
			return $ShipMethods;
		
		$Qry3		=	"SELECT * FROM shippingmethod_to_store WHERE store_id = '$StoreId'";
		$Result3	=	$this->db->get_row($Qry3,ARRAY_A);
		
		$i=0;
				
		if($Result3['ups_active'] == 'Y') {
			$ShipMethods[$i]['dbvalue']	=	UpsId;
			$ShipMethods[$i]['label']	=	'Ups';
			$i++;
		}

		if($Result3['usps_active'] == 'Y') {
			$ShipMethods[$i]['dbvalue']	=	UspsId;
			$ShipMethods[$i]['label']			=	'Usps';
			$i++;
		}

		if($Result3['fedex_active'] == 'Y') {
			$ShipMethods[$i]['dbvalue']	=	FedexId;
			$ShipMethods[$i]['label']	=	'Fedex';
			$i++;

		}
		
		if($Result3['canadaPost_active'] == 'Y') {
			$ShipMethods[$i]['dbvalue']		=	canadaPostId;
			$ShipMethods[$i]['label']			=	'CanadaPost';
			$i++;
		}
		
		if($Result3['dhl_active'] == 'Y') {
			$ShipMethods[$i]['dbvalue']		=	DHLId;
			$ShipMethods[$i]['label']			=	'DHL';
			$i++;
		}
		
		if($Result3['intershipper_active'] == 'Y') {
			$ShipMethods[$i]['dbvalue']	=	InterShipperId;
			$ShipMethods[$i]['label']	=	InterShipper;
			$i++;
		}
		
		if($Result3['AusPost_active'] == 'Y') {
			$ShipMethods[$i]['dbvalue']	=	AusPostId;
			$ShipMethods[$i]['label']	=	AusPost;
			$i++;
		}
		
		if($Result3['localpickup_active'] == 'Y') {
			$ShipMethods[$i]['dbvalue']		=	LocalPickUpId;
			$ShipMethods[$i]['label']			=	LocalPickUp;
			$i++;

		}

		return $ShipMethods;
		
	} # Close fuction definition 
	

	
} # Close class definition

?>