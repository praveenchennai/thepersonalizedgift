<?php
/**
 *	Fedex Rate request Calculator
 *
 *	@author			vimson
 *
 *  
 */


require_once FRAMEWORK_PATH.'/includes/shipping/fedexnew/xmlparser.php';


class FDXRateRequest
{
	var		$Server;
	
	var		$CustomerTransactionIdentifier;
	var		$AccountNumber;
	var		$MeterNumber;
	var		$CarrierCode;
	var		$ShipDate;
	var		$DropoffType;
	var		$Service;
	var		$Packaging;
	var		$WeightUnits;
	var		$Weight;
	
	var		$OAStateOrProvinceCode;
	var		$OAPostalCode;
	var		$OACountryCode;
	
	var		$DAStateOrProvinceCode;
	var		$DAPostalCode;
	var		$DACountryCode;
	
	var		$Price;
	var		$Error;
	
	function FDXRateRequest($RequestData = array()) 
	{
		$this->Server							=	$RequestData['Server'];
		
		$this->CustomerTransactionIdentifier	=	'CTIString';
		$this->AccountNumber					=	$RequestData['AccountNumber'];
		$this->MeterNumber						=	$RequestData['MeterNumber'];
		$this->CarrierCode						=	(trim($RequestData['CarrierCode']) != '') ? $RequestData['CarrierCode'] : 'FDXE';
		$this->ShipDate							=	$RequestData['ShipDate'];
		$this->DropoffType						=	$RequestData['DropoffType'];
		$this->Service							=	$RequestData['Service'];
		$this->Packaging						=	$RequestData['Packaging'];
		$this->WeightUnits						=	(trim($RequestData['WeightUnits']) != '') ? $RequestData['WeightUnits'] : 'LBS';
		$this->Weight							=	sprintf("%0.1f",$RequestData['Weight']);
		
		$this->OAStateOrProvinceCode			=	$RequestData['OAStateOrProvinceCode'];
		$this->OAPostalCode						=	$RequestData['OAPostalCode'];
		$this->OACountryCode					=	$RequestData['OACountryCode'];
		
		$this->DAStateOrProvinceCode			=	$RequestData['DAStateOrProvinceCode'];
		$this->DAPostalCode						=	$RequestData['DAPostalCode'];
		$this->DACountryCode					=	$RequestData['DACountryCode'];
		
		if($this->Service == 'FEDEXGROUND' || $this->Service == 'GROUNDHOMEDELIVERY')
			$this->CarrierCode		=	'FDXG';
		
	}
	
	function fetchFedexRate()
	{
		$RateRequestXML		=	'<?xml version="1.0" encoding="UTF-8" ?>
								<FDXRateRequest xmlns:api="http://www.fedex.com/fsmapi" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="FDXRateRequest.xsd">
									<RequestHeader>
										<CustomerTransactionIdentifier>'.$this->CustomerTransactionIdentifier.'</CustomerTransactionIdentifier>
										<AccountNumber>'.$this->AccountNumber.'</AccountNumber>
										<MeterNumber>'.$this->MeterNumber.'</MeterNumber>
										<CarrierCode>'.$this->CarrierCode.'</CarrierCode>
									</RequestHeader>
									<ShipDate>'.$this->ShipDate.'</ShipDate>
									<DropoffType>'.$this->DropoffType.'</DropoffType>
									<Service>'.$this->Service.'</Service>
									<Packaging>'.$this->Packaging.'</Packaging>
									<WeightUnits>'.$this->WeightUnits.'</WeightUnits>
									<Weight>'.$this->Weight.'</Weight>
									<OriginAddress>
										<StateOrProvinceCode>'.$this->OAStateOrProvinceCode.'</StateOrProvinceCode>
										<PostalCode>'.$this->OAPostalCode.'</PostalCode>
										<CountryCode>'.$this->OACountryCode.'</CountryCode>
									</OriginAddress>
									<DestinationAddress>';
		$RateRequestXML		.=		(trim($this->DAStateOrProvinceCode) != '') ? '<StateOrProvinceCode>'.$this->DAStateOrProvinceCode.'</StateOrProvinceCode>' : '';
		$RateRequestXML		.=		(trim($this->DAPostalCode) != '') ? '<PostalCode>'.$this->DAPostalCode.'</PostalCode>' : '';
		$RateRequestXML		.=		'<CountryCode>'.$this->DACountryCode.'</CountryCode>
									</DestinationAddress>
									<Payment>
										<PayorType>SENDER</PayorType>
									</Payment>
									<PackageCount>1</PackageCount>
								</FDXRateRequest>';
			
		$header[] = "Host: www.smart-shop.com";
		$header[] = "MIME-Version: 1.0";
		$header[] = "Content-type: multipart/mixed; boundary=----doc";
		$header[] = "Accept: text/xml";
		$header[] = "Content-length: ".strlen($RateRequestXML);
		$header[] = "Cache-Control: no-cache";
		$header[] = "Connection: close \r\n";
		$header[] = $RateRequestXML;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_URL,$this->Server);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 4);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$data = curl_exec($ch); 
		curl_close($ch);
		
		$xmlParser 	= 	new xmlparser();
		$array 		= 	$xmlParser->GetXMLTree($data);
		
		
		if(count($array['FDXRATEREPLY'][0]['ERROR'])) { // If it is error
			$error = new error();
			$error->number 		= 	$array['FDXRATEREPLY'][0]['ERROR'][0]['CODE'][0]['VALUE'];
			$error->description = 	$array['FDXRATEREPLY'][0]['ERROR'][0]['MESSAGE'][0]['VALUE'];
			$error->response 	= 	$array;
			$this->Error		=	$error;
		} else if(count($array['ERROR']) > 0) { 
			$error = new error();
			$error->number 		= 	$array['ERROR'][0]['CODE'][0]['VALUE'];
			$error->description = 	$array['ERROR'][0]['MESSAGE'][0]['VALUE'];
			$error->response 	= 	$array;
			$this->Error		=	$error;
		} else if (count($array['FDXRATEREPLY'][0]['ESTIMATEDCHARGES'][0]['DISCOUNTEDCHARGES'][0]['NETCHARGE'])) {
			$price = new FedexPrice();
			$price->rate	 	=	 $array['FDXRATEREPLY'][0]['ESTIMATEDCHARGES'][0]['DISCOUNTEDCHARGES'][0]['NETCHARGE'][0]['VALUE'];
			$price->service	 	=	 $this->serviceName;
			$price->response 	= 	 $array;
			$this->Price 	 	=    $price;
		}

	} # Close method definition
	
	
	# The following method returns the fedex quote
	function getFDXRateRequestQuote($Params, $FedexDetails)
	{
		unset($this->Error);
		unset($this->Price);
	
		$Results	=	array();
		
		$this->Server							=	$FedexDetails['Server'];
		
		$this->CustomerTransactionIdentifier	=	'CTIString';
		$this->AccountNumber					=	$FedexDetails['AccountNumber'];
		$this->MeterNumber						=	$FedexDetails['MeterNumber'];
		$this->CarrierCode						=	(trim($FedexDetails['CarrierCode']) != '') ? $FedexDetails['CarrierCode'] : 'FDXE';
		$this->ShipDate							=	date('Y-m-d');
		$this->DropoffType						=	$FedexDetails['DropoffType'];
		$this->Packaging						=	$FedexDetails['Packaging'];
		$this->Service							=	$FedexDetails['Service'];
		$this->WeightUnits						=	(trim($FedexDetails['WeightUnits']) != '') ? $FedexDetails['WeightUnits'] : 'LBS';

		$this->Weight							=	sprintf("%0.1f",$Params['Weight']);

		$this->OAStateOrProvinceCode			=	$Params['OAStateOrProvinceCode'];
		$this->OAPostalCode						=	$Params['OAPostalCode'];
		$this->OACountryCode					=	$Params['OACountryCode'];
		
		$this->DAStateOrProvinceCode			=	$Params['DAStateOrProvinceCode'];
		$this->DAPostalCode						=	$Params['DAPostalCode'];
		$this->DACountryCode					=	$Params['DACountryCode'];
		
		if($this->Service == 'FEDEXGROUND' || $this->Service == 'GROUNDHOMEDELIVERY')
			$this->CarrierCode		=	'FDXG';
		
		$this->fetchFedexRate();

		if(isset($this->Error)) {
			$Results['Got'] 	 	=	'No';
			$Results['Price'] 	 	=	0;
			$Results['Message'] 	=	$this->Error->description;
		} else if(isset($this->Price)) {
			$Results['Got'] 	 	=	'Yes';
			$Results['Price'] 	 	=	$this->Price->rate;
			$Results['Message'] 	=	'Success';
		} else {
			$Results['Got'] 	 	=	'No';
			$Results['Price'] 	 	=	0;
			$Results['Message'] 	=	'Unknown Error';
		}
		
		return $Results;
		
	} # Close method definition
	
	
	
} # Close class definition



# Error class definition
class error 
{
    var $number;
    var $description;
    var $response;
} # Class definition closes



# Fedex Price class definition
class FedexPrice
{
    var $service;
    var $rate;
    var $response;
} # Class definition closes



?>