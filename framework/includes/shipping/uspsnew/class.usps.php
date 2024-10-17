<?php

require_once FRAMEWORK_PATH.'/includes/shipping/uspsnew/xmlparser.php';
# require_once 'xmlparser.php';

class USPS {

    var $server = "";
    var $user = "";
    var $pass = "";
    var $service = "";
    var $dest_zip;
    var $orig_zip;
    var $pounds;
    var $ounces;
    var $container = "None";
    var $size = "REGULAR";
    var $machinable;
    var $country = "USA";
    
    function setServer($server) {
        $this->server = $server;
    }

    function setUserName($user) {
        $this->user = $user;
    }

    function setPass($pass) {
        $this->pass = $pass;
    }

    function setService($service) {
        /* Must be: Express, Priority, or Parcel */
        $this->service = $service;
    }
    
    function setDestZip($sending_zip) {
        /* Must be 5 digit zip (No extension) */
        $this->dest_zip = $sending_zip;
    }

    function setOrigZip($orig_zip) {
        $this->orig_zip = $orig_zip;
    }

    function setWeight($pounds, $ounces=0) {
        /* Must weight less than 70 lbs. */
        $this->pounds = $pounds;
        $this->ounces = $ounces;
    }

    function setContainer($cont) {
        $this->container = $cont;
    }

    function setSize($size) {
        $this->size = $size;
    }

    function setMachinable($mach) {
        /* Required for Parcel Post only, set to True or False */
        $this->machinable = $mach;
    }
    
    function setCountry($country) {
        $this->country = $country;
    }
    
    function getPrice() {
        if($this->country=="USA"){
            // may need to urlencode xml portion
            $str = $this->server. "?API=RateV2&XML=<RateV2Request%20USERID=\"";
            $str .= $this->user . "\"%20PASSWORD=\"" . $this->pass . "\"><Package%20ID=\"0\"><Service>";
            $str .= $this->service . "</Service><ZipOrigination>" . $this->orig_zip . "</ZipOrigination>";
            $str .= "<ZipDestination>" . $this->dest_zip . "</ZipDestination>";
            $str .= "<Pounds>" . $this->pounds . "</Pounds><Ounces>" . $this->ounces . "</Ounces>";
            $str .= "<Container>" . urlencode($this->container) . "</Container><Size>" . $this->size . "</Size>";
            $str .= "<Machinable>" . $this->machinable . "</Machinable></Package></RateV2Request>";
        }
        else {
            $str = $this->server. "?API=IntlRate&XML=<IntlRateRequest%20USERID=\"";
            $str .= $this->user . "\"%20PASSWORD=\"" . $this->pass . "\"><Package%20ID=\"0\">";
            $str .= "<Pounds>" . $this->pounds . "</Pounds><Ounces>" . $this->ounces . "</Ounces>";
            $str .= "<MailType>Package</MailType><Country>".urlencode($this->country)."</Country></Package></IntlRateRequest>";
        }
		
	    $ch = curl_init();
        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $str);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt ($ch, CURLOPT_TIMEOUT, 0);
		
        // grab URL and pass it to the browser
        $ats = curl_exec($ch);
				
        // close curl resource, and free up system resources
        curl_close($ch);
        $xmlParser = new xmlparser();
        $array = $xmlParser->GetXMLTree($ats);
		
		if(isset($array['HTML'])) {
			$error = new error();
			$error->description = 'The parameter is incorrect';
			$this->error = $error;
			return $this;
		}
				
        //$xmlParser->printa($array);
        if(count($array['ERROR'])) { // If it is error
            $error = new error();
            $error->number = $array['ERROR'][0]['NUMBER'][0]['VALUE'];
            $error->source = $array['ERROR'][0]['SOURCE'][0]['VALUE'];
            $error->description = $array['ERROR'][0]['DESCRIPTION'][0]['VALUE'];
            $error->helpcontext = $array['ERROR'][0]['HELPCONTEXT'][0]['VALUE'];
            $error->helpfile = $array['ERROR'][0]['HELPFILE'][0]['VALUE'];
            $this->error = $error;
        } else if(count($array['RATEV2RESPONSE'][0]['PACKAGE'][0]['ERROR'])) {
            $error = new error();
            $error->number = $array['RATEV2RESPONSE'][0]['PACKAGE'][0]['ERROR'][0]['NUMBER'][0]['VALUE'];
            $error->source = $array['RATEV2RESPONSE'][0]['PACKAGE'][0]['ERROR'][0]['SOURCE'][0]['VALUE'];
            $error->description = $array['RATEV2RESPONSE'][0]['PACKAGE'][0]['ERROR'][0]['DESCRIPTION'][0]['VALUE'];
            $error->helpcontext = $array['RATEV2RESPONSE'][0]['PACKAGE'][0]['ERROR'][0]['HELPCONTEXT'][0]['VALUE'];
            $error->helpfile = $array['RATEV2RESPONSE'][0]['PACKAGE'][0]['ERROR'][0]['HELPFILE'][0]['VALUE'];
            $this->error = $error;
        } else if(count($array['INTLRATERESPONSE'][0]['PACKAGE'][0]['ERROR'])){ //if it is international shipping error
            $error = new error($array['INTLRATERESPONSE'][0]['PACKAGE'][0]['ERROR']);
            $error->number = $array['INTLRATERESPONSE'][0]['PACKAGE'][0]['ERROR'][0]['NUMBER'][0]['VALUE'];
            $error->source = $array['INTLRATERESPONSE'][0]['PACKAGE'][0]['ERROR'][0]['SOURCE'][0]['VALUE'];
            $error->description = $array['INTLRATERESPONSE'][0]['PACKAGE'][0]['ERROR'][0]['DESCRIPTION'][0]['VALUE'];
            $error->helpcontext = $array['INTLRATERESPONSE'][0]['PACKAGE'][0]['ERROR'][0]['HELPCONTEXT'][0]['VALUE'];
            $error->helpfile = $array['INTLRATERESPONSE'][0]['PACKAGE'][0]['ERROR'][0]['HELPFILE'][0]['VALUE'];
            $this->error = $error;
        } else if(count($array['RATEV2RESPONSE'])){ // if everything OK
            //print_r($array['RATEV2RESPONSE']);
            $this->zone = $array['RATEV2RESPONSE'][0]['PACKAGE'][0]['ZONE'][0]['VALUE'];
            foreach ($array['RATEV2RESPONSE'][0]['PACKAGE'][0]['POSTAGE'] as $value){
                $price = new UspsPrice();
                $price->mailservice = $value['MAILSERVICE'][0]['VALUE'];
                $price->rate = $value['RATE'][0]['VALUE'];
                $this->list[] = $price;
            }
        } else if (count($array['INTLRATERESPONSE'][0]['PACKAGE'][0]['SERVICE'])) { // if it is international shipping and it is OK
            foreach($array['INTLRATERESPONSE'][0]['PACKAGE'][0]['SERVICE'] as $value) {
                $price = new intPrice();
                $price->id = $value['ATTRIBUTES']['ID'];
                $price->pounds = $value['POUNDS'][0]['VALUE'];
                $price->ounces = $value['OUNCES'][0]['VALUE'];
                $price->mailtype = $value['MAILTYPE'][0]['VALUE'];
                $price->country = $value['COUNTRY'][0]['VALUE'];
                $price->rate = $value['POSTAGE'][0]['VALUE'];
                $price->svccommitments = $value['SVCCOMMITMENTS'][0]['VALUE'];
                $price->svcdescription = $value['SVCDESCRIPTION'][0]['VALUE'];
                $price->maxdimensions = $value['MAXDIMENSIONS'][0]['VALUE'];
                $price->maxweight = $value['MAXWEIGHT'][0]['VALUE'];
                $this->list[] = $price;
            }
        
        }
        return $this;
    } # Close method definition
	
	
	# The following method caculate the Shipping cost of Usps service
	function getUSPSQuote($Params,$UspsDetails)
	{
		$Results			=	array();
		
		$this->server		=	$UspsDetails['live_server'];
		$this->user			=	$UspsDetails['user_name'];
		$this->pass			=	$UspsDetails['password'];
		$this->service		=	$UspsDetails['servicevalue'];
		
		$this->orig_zip		=	$Params['src_zip'];
		$this->dest_zip		=	$Params['dst_zip'];
		$this->pounds		=	(int) $Params['weight'];
				
		$this->ounces		=	(trim($Params['ounce']) != '') ? $Params['ounce'] : 0;
		$this->country		=	$Params['dst_country'];

		$this->container	=	$UspsDetails['uspsBoxType'];
		$this->size			=	$UspsDetails['uspsPackageType'];
		
		if($this->service == 'Parcel')
			$this->machinable	=	USPS_MACHINABLE;
		else
			$this->machinable	=	'';
		
		$PriceObj	=	$this->getPrice();
		
		if(isset($PriceObj->error)) {
			$Results['Got'] 	 	=	'No';
			$Results['Price'] 	 	=	0;
			$Results['Message'] 	=	$PriceObj->error->description;
		} else {
			if(!isset($PriceObj->list)) {
				$Results['Got'] 	 	=	'No';
				$Results['Price'] 	 	=	0;
				$Results['Message'] 	=	'Rate cannot fetch. Please try once again';
			} else {
				
				# The following block of code is used for fetching the rate of international orders
				if($this->country != 'USA') {
					$Results	=	array();	
					foreach($PriceObj->list as $UspsPrice) {
						if($this->service == $UspsPrice->svcdescription) {
							$Results['Got'] 	 	=	'Yes';
							$Results['Price'] 	 	=	$UspsPrice->rate;
							$Results['Message'] 	=	'Success';
							return $Results;
						} # Close if	
					} # Close foreach
				} #Close if USA
				
				
				foreach($PriceObj->list as $UspsPrice) {
					$Results['Got'] 	 	=	'Yes';
					$Results['Price'] 	 	=	$UspsPrice->rate;
					$Results['Message'] 	=	'Success';
					break;
				}
			} # Close inner else 
		} # Close outer else

		return $Results;
		
	} # Close method definition
	
} 
# Close class definition





# Error class declaration
class error {
    var $number;
    var $source;
    var $description;
    var $helpcontext;
    var $helpfile;
}

# Price class declaration
/*class price {
    var $mailservice;
    var $rate;
} */

class UspsPrice {
    var $mailservice;
    var $rate;
}

# IntPrice class declaration
class intPrice {
    var $id;
    var $rate;
}

?>