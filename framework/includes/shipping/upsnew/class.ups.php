<?php


/**
 *  FileName: class.ups.php
 *	Description: Abstracts the Ups operations with the provision for entering the container type, package type and delivery mode
 *				 As parameters.
 *	 
 * 	
 *	The following are the delivery codes or shipping services selected at the admin side
 *			1DM         Next Day Air Early AM
 *			1DML      	Next Day Air Early AM Letter
 *			1DA        	Next Day Air
 *			1DAL        Next Day Air Letter
 *			1DP         Next Day Air Saver
 *			1DPL       	Next Day Air Saver Letter
 *			2DM        	2nd Day Air A.M.
 *			2DA         2nd Day Air
 *			2DML      	2nd Day Air A.M. Letter
 *			2DAL       	2nd Day Air Letter
 *			3DS         3 Day Select
 *			GNDCOM      Ground Commercial
 *			GNDRES      Ground Residential
 *			XPR         Worldwide Express
 *			XDM       	Worldwide Express Plus
 *			XPRL      	Worldwide Express Letter
 *			XDML      	Worldwide Express Plus Letter
 *			XPD         Worldwide Expedited
 * 
 * 
 * 
 *	The following are the Rate chart descriptions
 *			Regular+Daily+Pickup  		Regular Daily Pickup
 *  	    On+Call+Air 				On Call Air
 *          One+Time+Pickup 			One Time Pickup
 *          Letter+Center 				Letter Center
 *          Customer+Counter 			Customer Counter
 *
 *  
 *  The following are the container chart descriptions	
 *			00			Unknown
 *			01			UPS Letter
 *			02			My Packaging
 *			03			UPS Tube
 *			04			UPS Pak
 *			21			UPS Express Box
 *			24			UPS 25Kg Box &reg;
 *			25			UPS 10Kg Box &reg;		
 *
 *  
 * 
 **/
 
 
class Ups {

	var		$_action;
	var		$_delivery_code;
	var		$_src_zip;
	var		$_dst_zip;
	var		$_weight;
	var		$_src_country; 
	var		$_dst_country;
	var		$_rate_chart;
	var		$_container;
	var		$_rescom;
	var		$_errors;
	
	# Constructor
	function Ups()
	{
		$this->_errors		=	array();
		$this->_action		=	3;
		$this->_rescom		=	1;
	}
	
	function setRescom($_rescom)
	{	
		$this->_rescom	=	$_rescom;
	}
	
	function setDeliveryCode($_delivery_code)
	{	
		$this->_delivery_code	=	$_delivery_code;
	}
	
	function setSrcZip($_src_zip)
	{
		$this->_src_zip		=	$_src_zip;
	}
	
	function setDstZip($_dst_zip)
	{
		$this->_dst_zip		=	$_dst_zip;
	}
	
	function setWeight($_weight)
	{
		$this->_weight	=	$_weight;
	}
	
	function setSrcCountry($_src_country)
	{
		$this->_src_country		=	$_src_country;
	}
	
	function setDstCountry($_dst_country)
	{
		$this->_dst_country		=	$_dst_country;
	}
	
	function setRateChart($_rate_chart)
	{
		$this->_rate_chart	=	$_rate_chart;
	}
	
	function setContainer($_container)
	{
		$this->_container	=	$_container;
	}
	
	# Fetch shipping cost using the socket communication
	function getCost()
	{
		if ($this->_action == '') 
			array_push ($this->_errors, 'UPS Action is not defined'); 
		if ($this->_delivery_code == '') 
			array_push ($this->_errors, 'UPS Product Code is not defined'); 
		if ($this->_src_zip == '') 
			array_push ($this->_errors, 'Source Zip Code is unavailable'); 
		if ($this->_src_country == '') 
			array_push ($this->_errors, 'Source Country Code is unavailable'); 
		if ($this->_dst_zip == '') 
			array_push ($this->_errors, 'Destination Zip Code is unavailable'); 
		if ($this->_dst_country == '') 
			array_push ($this->_errors, 'Destination Country Code is unavailable'); 
		if ($this->_weight == '') 
			array_push ($this->_errors, 'Packet weight is not defined'); 
		if ($this->_rate_chart == '') 
			array_push ($this->_errors, 'Rate Chart is not defined'); 
		if ($this->_container == '') 
			array_push ($this->_errors, 'Client Shipping Package Type is not defined'); 
		if ($this->_rescom == '') 
			array_push ($this->_errors, 'Residential or Commercial? define it first!'); 
		
		if (count ($this->_errors) <= 0) 
		{ 
		
			$url  = 'www.ups.com'; 
			$port = '80'; 
			$file = '/using/services/rave/qcostcgi.cgi'; 
			$qs   = '?'; 
			$qs  .= 'accept_UPS_license_agreement=yes'; 
			$qs  .= '&'; 
			$qs  .= '10_action='.$this->_action; 
			$qs  .= '&'; 
			$qs  .= '13_product='.$this->_delivery_code; 
			$qs  .= '&'; 
			$qs  .= '14_origCountry='. $this->_src_country; 
			$qs  .= '&'; 
			$qs  .= '15_origPostal='. $this->_src_zip; 
			$qs  .= '&'; 
			$qs  .= '19_destPostal='. $this->_dst_zip; 
			$qs  .= '&'; 
			$qs  .= '22_destCountry='. $this->_dst_country; 
			$qs  .= '&'; 
			$qs  .= '23_weight='. $this->_weight; 
			$qs  .= '&'; 
			$qs  .= '47_rateChart='.$this->_rate_chart; 
			$qs  .= '&'; 
			$qs  .= '48_container='.$this->_container; 
			$qs  .= '&'; 
			$qs  .= '49_residential='.$this->_rescom; 
			$rqs  = $file.$qs; 
			
			if($buffer = file_get_contents("http://".$url.$rqs)) 
			{
				if (strpos ($buffer, '%')) 
				{ 
					$buffers = explode ('%', $buffer, -1); 
					$errcode = substr ($buffers[0], -1);
					switch($errcode){
						case 3:
							$returnval = $buffers[8];
							break;
						case 4:
							$returnval = $buffers[8];
							break;
						case 5:
							$this->_errors[] = $buffers[1];
							break;
						case 6:
							$this->_errors[] = $buffers[1];
							break;
					}
					if ($returnval) { 
						return $returnval; 
					} 
				}
			} else {
				array_push ($this->_errors, 'Could not open socket');
			}
		} # Close error count condition
	} # Close method definition
	

	# The following methid returns the shipping cost
	function getUPSQuote($Params, $UpsDetails)
	{
		$Results				=	array();
		
		$this->_delivery_code	=	$UpsDetails['servicevalue'];
		$this->_rate_chart		=	$UpsDetails['upsType'];
		$this->_container		=	$UpsDetails['upsPackage'];
		
		$this->_src_zip			=	$Params['src_zip'];
		$this->_dst_zip			=	$Params['dst_zip'];
		$this->_weight			=	$Params['weight'];
		$this->_src_country		=	$Params['src_country'];
		$this->_dst_country		=	$Params['dst_country'];
				
		$Cost	=	$this->getCost();
		//print_r($this->_errors); echo"<br>";
		if(count($this->_errors) > 0) {
			$Results['Got'] 	 	=	'No';
			$Results['Price'] 	 	=	0;
			$Results['Message'] 	=	implode('<br>',$this->_errors);
		} else {
			$Results['Got'] 	 	=	'Yes';
			$Results['Price'] 	 	=	$Cost;
			$Results['Message'] 	=	'Success';
		}
	$this->_errors=array();
		return $Results;
	}
	
} # Close class definition

?>