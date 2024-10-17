<?php

define('USE_PROXY',FALSE);
define('PROXY_HOST', '127.0.0.1');
define('PROXY_PORT', '808');
define('PAYPAL_URL', 'https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=');
define('VERSION', '3.0');
define('API_ENDPOINT', 'https://api-3t.sandbox.paypal.com/nvp');


/**
 * The following class abstracts the operations of a payflow pro activities
 *
 * @author vimson@newagesmb.com
 *  
 * 
 */
class WebsitePaypalPro 
{
	
	var 	$API_UserName;
	var 	$API_Password;
	var 	$API_Signature;
	var 	$version;

		
	/**
	 * Class Constructor
	 *
	 * @param array $Params
	 * @return PayflowPro
	 */
	function WebsitePaypalPro($Params)
	{
		$this->API_UserName		=	$Params['API_UserName'];
		$this->API_Password		=	$Params['API_Password'];
		$this->API_Signature	=	$Params['API_Signature'];
	}
	
		
	/**
	 * This function will take NVPString and convert it to an Associative Array and it will decode the response.
	 * It is usefull to search for a particular key and displaying arrays.
	 * 
	 * @param string $nvpstr
	 * @return Associative Array
	 */
	function deformatNVP($nvpstr)
	{

		$intial=0;
	 	$nvpArray = array();
	
	
		while(strlen($nvpstr)){
			//postion of Key
			$keypos= strpos($nvpstr,'=');
			//position of value
			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);
	
			/*getting the Key and Value values and storing in a Associative Array*/
			$keyval=substr($nvpstr,$intial,$keypos);
			$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
			//decoding the respose
			$nvpArray[urldecode($keyval)] =urldecode( $valval);
			$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
	     }
		return $nvpArray;
	}
	
	
		
	/**
	 * hash_call: Function to perform the API call to PayPal using API signature
	 *
	 * @param name of API method $methodName
	 * @param nvpstring $nvpStr
	 * @return associtive array containing the response from the server
	 */
	function hash_call($methodName,$nvpStr)
	{
			
		//setting the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,API_ENDPOINT);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
	
		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
	    //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
	   //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php 
		if(USE_PROXY)
			curl_setopt ($ch, CURLOPT_PROXY, PROXY_HOST.":".PROXY_PORT); 
	
		//NVPRequest for submitting to server
		#$nvpreq="METHOD=".urlencode($methodName)."&VERSION=".urlencode($version)."&PWD=".urlencode($API_Password)."&USER=".urlencode($API_UserName)."&SIGNATURE=".urlencode($API_Signature).$nvpStr;
		
		$nvpreq="METHOD=".urlencode($methodName)."&TENDER=C&VERSION=".urlencode(VERSION)."&PWD=".urlencode($this->API_Password)."&USER=".urlencode($this->API_UserName)."&SIGNATURE=".urlencode($this->API_Signature).$nvpStr;
		
				
		//setting the nvpreq as POST FIELD to curl
		curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);
	
		//getting response from server
		$response = curl_exec($ch);
				
		//convrting NVPResponse to an Associative Array
		$nvpResArray	=	$this->deformatNVP($response);
		$nvpReqArray	=	$this->deformatNVP($nvpreq);
		$_SESSION['nvpReqArray']=$nvpReqArray;
	
		if (curl_errno($ch)) {
			// moving to display page to display curl errors
			  $_SESSION['curl_error_no']=curl_errno($ch) ;
			  $_SESSION['curl_error_msg']=curl_error($ch);
			  $location = "APIError.php";
			  header("Location: $location");
		 } else {
			 //closing the curl
				curl_close($ch);
		  }
	
		return $nvpResArray;
	}
	
	
	
	/**
	 * The follwoing method calls a do reference transaction to the payflow server
	 *
	 * @param Associate Array $Params
	 * @return Associative array conataining information from the payflow server
	 * 
	 */
	function doReferenceTransaction($Params)
	{
		$Results			=	array();
		$TransactionId		=	$Params['TransactionId'];
		$TransactionAmount	=	$Params['TransactionAmount'];
				
		$nvpstrparams		=	'';
		
		if($Params['FIRSTNAME'] != '')
			$nvpstrparams		.=	'&FIRSTNAME='.$Params['FIRSTNAME'];
		if($Params['LASTNAME'] != '')
			$nvpstrparams		.=	'&LASTNAME='.$Params['LASTNAME'];
		if($Params['STREET'] != '')
			$nvpstrparams		.=	'&STREET='.$Params['STREET'];
		if($Params['CITY'] != '')
			$nvpstrparams		.=	'&CITY='.$Params['CITY'];
		if($Params['STATE'] != '')
			$nvpstrparams		.=	'&STATE='.$Params['STATE'];
		if($Params['ZIP'] != '')
			$nvpstrparams		.=	'&ZIP='.$Params['ZIP'];		
		if($Params['EMAIL'] != '')
			$nvpstrparams		.=	'&EMAIL='.$Params['EMAIL'];
				
		$nvpstrparams		.=	'&COUNTRYCODE=US&CURRENCYCODE=USD';
						
		#$nvpstr			=	"&PAYMENTACTION=Sale&AMT=$TransactionAmount&ORIGID=$TransactionId&ReferenceID=$TransactionId".$nvpstrparams;
		$nvpstr			=	"&PAYMENTACTION=Sale&AMT=$TransactionAmount&ReferenceID=$TransactionId".$nvpstrparams;
		$ResultArray	=	$this->hash_call("DoReferenceTransaction",$nvpstr);
		
		
		$str	=	var_export($ResultArray, true);
		$str	=	$str."\n".$nvpstr;
		$fp	=	fopen('log.txt', 'a+');
		fwrite($fp, $str);
		fclose($fp);
		
		
		$ack 	= 	strtoupper($ResultArray["ACK"]);
		if($ack == "SUCCESS" || $ack === 'SUCCESSWITHWARNING')  {
			$Results['Approved']		=	'Yes';
			$Results['TransactionId']	=	$ResultArray['TRANSACTIONID'];
			$Results['Message']			=	'Transaction Successful';
		} else {
			$Results['Approved']		=	'No';
			$Results['Message']			=	$ResultArray['L_LONGMESSAGE0'];
		}
		
		return $Results;
		
	}
	
	
	/**
	 * The following method sends a doDirectPayment to the paypal server
	 * We can use the following method for both Sale and Authorization
	 *
	 * @param Associative Array $REQUEST
	 * @return Array
	 */
	function doDirectPayment($REQUEST)
	{
				
		/**
		 * Get required parameters from the web form for the request
		 */
		$paymentType 		=	urlencode($REQUEST['paymentType']);
		$firstName 	 		=	urlencode($REQUEST['firstName']);
		$lastName 	 		=	urlencode($REQUEST['lastName']);
		$creditCardType 	=	urlencode($REQUEST['creditCardType']);
		$creditCardNumber 	= 	urlencode($REQUEST['creditCardNumber']);
		$expDateMonth 		=	urlencode($REQUEST['expDateMonth']);
		
		// Month must be padded with leading zero
		$padDateMonth 	=	 str_pad($expDateMonth, 2, '0', STR_PAD_LEFT);
		
		$expDateYear 	=	urlencode($REQUEST['expDateYear']);
		$cvv2Number 	=	urlencode($REQUEST['cvv2Number']);
		$address1 		=	urlencode($REQUEST['address1']);
		$address2 		=	urlencode($REQUEST['address2']);
		$city 			=	urlencode($REQUEST['city']);
		$state 			=	urlencode($REQUEST['state']);
		$zip 			=	urlencode($REQUEST['zip']);
		$amount 		=	urlencode($REQUEST['amount']);
		$email			=	urlencode($REQUEST['email']);
		//$currencyCode=urlencode($_POST['currency']);
		$currencyCode	=	"USD";
		$paymentType	=	urlencode($REQUEST['paymentType']);
		
		/* Construct the request string that will be sent to PayPal.
		   The variable $nvpstr contains all the variables and is a
		   name value pair string with & as a delimiter */
		/*$nvpstr="&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=".$padDateMonth.$expDateYear."&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName&STREET=$address1&CITY=$city&STATE=$state".
		"&ZIP=$zip&COUNTRYCODE=US&CURRENCYCODE=$currencyCode&ORIGID=7WU51918XU4542049";*/
		
		$nvpstr		=	"&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=".$padDateMonth.$expDateYear."&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName&STREET=$address1&CITY=$city&STATE=$state".
		"&ZIP=$zip&COUNTRYCODE=US&CURRENCYCODE=$currencyCode&EMAIL=$email";
		
		
		/* Make the API call to PayPal, using API signature.
		   The API response is stored in an associative array called $resArray */
		$resArray	=	$this->hash_call("doDirectPayment",$nvpstr);
		
				
		return $resArray;
		
	}
	
	/**
	 * The following methid returns the Trnsaction details associated with a transaction
	 *
	 * @param int $TransactionId
	 * @return Array
	 */
	function getTransactionDetails($TransactionId)
	{
		$Results		=	array();
		
		$transactionID	=	urlencode($_REQUEST['transactionID']);
		$nvpStr			=	"&TRANSACTIONID=$transactionID";
						
		$resArray		=	$this->hash_call("gettransactionDetails",$nvpStr);
		$ack 			=	strtoupper($resArray["ACK"]);

		if($ack != "SUCCESS") {
			$Results['Got']		=	'No';
			$Results['Data']	=	$resArray;
		} else {
			$Results['Got']		=	'Yes';
			$Results['Data']	=	$resArray;
		}
		
		return $Results;
		
	}
	
	
	
	/**
	 * The following method voids a previously completed transaction
	 *
	 * @param Associative Array $Params
	 * @return boolean
	 */
	function doVoidTransaction($Params)
	{
				
		$authorizationID	=	urlencode($Params['TransactionId']);
		$nvpStr				=	"&AUTHORIZATIONID=$authorizationID&NOTE=";
		$resArray			=	$this->hash_call("DOVoid",$nvpStr);
		$ack			 	=	strtoupper($resArray["ACK"]);
		
		if($ack != 'SUCCESS') {
			return false;
		} else {
			return true;
		}
		
		return false;
	}
	
	
	
	/**
	 * The following method both authorizes the credit card and void the .01$ transaction which have already done.
	 *
	 * @param Associative Array $Params
	 * 
	 * @return Associative array containing transaction information
	 */
	function doAuthorizeAndVoidCreditCard($Params)
	{
		$Results			=	array();
		
		$Params['paymentType']	=   'Authorization';
		$DoDirResultArr			=	$this->doDirectPayment($Params);
		$ack 					= 	strtoupper($DoDirResultArr["ACK"]);
		
		if($ack == "SUCCESS" || $ack === 'SUCCESSWITHWARNING')  {
			$Results['Approved']		=	'Yes';
			$Results['Message']			=	'Transaction Success';
			$Results['TransactionId']	=	$DoDirResultArr['TRANSACTIONID'];
			
			# Void the transaction
			$Params['TransactionId']	=	$DoDirResultArr['TRANSACTIONID'];
			$VoidStatus					=	$this->doVoidTransaction($Params);
			
			if($VoidStatus === TRUE)
				$Results['Voided']		=	'Yes';
			else if($VoidStatus === FALSE)
				$Results['Voided']		=	'No';
				
		} else {
			$Results['Approved']		=	'No';
			$Results['Message']			=	'Error:'.$DoDirResultArr['L_LONGMESSAGE0'];
		}
		
		return $Results;
		
	}
	

	
	/**
	 * The follwing method makes an Sale transaction
	 *
	 * @param Array $Params
	 * @return Associative Array 
	 */
	function makeSaleTransactionPayment($Params)
	{
		$Results	=	array();
		
		$Params['paymentType']	=   'Sale';
		$DoDirResultArr			=	$this->doDirectPayment($Params);
		$ack 					= 	strtoupper($DoDirResultArr["ACK"]);
		
		if($ack == "SUCCESS" || $ack === 'SUCCESSWITHWARNING')  {
			$Results['Approved']		=	'Yes';
			$Results['Message']			=	'Transaction Success';
			$Results['TransactionId']	=	$DoDirResultArr['TRANSACTIONID'];
		} else {
			$Results['Approved']		=	'No';
			$Results['Message']			=	'Error:'.$DoDirResultArr['L_LONGMESSAGE0'];
		}

		return $Results;
	}
	
	
	
	
	
}

?>