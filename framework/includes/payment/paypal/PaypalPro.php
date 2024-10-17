<?php

/**

	The following class abstracts the paypal pro payment gateway process operations.

	The following fields are used as a payment gateway account details
		a. api_username
		b. api_password
		c. cert_file_path
		d. signature

	The following methods are used
		a. getPaypalPro($StoreId, $PaymentReceiver) -- Retrives the payment details associated with a Store
		b. setPaypalPro($REQUEST, $FILES) -- This methos stores the payment details associated with a store
		c. validatePaypalPro($REQUEST,$FILES) -- Validating the payment details form for paypal pro
		d. processPaypalPro($StoreName, $Params) -- Send request to the payment gateway for payment processing and
												 -- processes the response from the payment gateway
												 -- This method is used by the common interface created in the class.payment.php file
	
 **/	
  
  
class PaypalPro extends FrameWork
{
	
	var		$PaymentObj;
	
	# Constructor
	function PaypalPro($PaymentObj = '')
	{
		$this->PaymentObj	=	$PaymentObj;	
		$this->FrameWork();	
	}
	
	####### Paypal Pro section #############################################################################
		# a. api_username
		# b. api_password
		# c. cert_file_path
		# d. signature

	# The following method returns the payment method details associated with paypal pro
	function getPaypalPro($StoreId, $PaymentReceiver)
	{
		$ConfDetails	=	array();
		
		if($PaymentReceiver == 'admin')
			$StoreId	=	'0';
			
		$Qry1		=	"SELECT COUNT(*) AS TotCount FROM payment_configuration WHERE store_id='$StoreId' AND (payconfig_varname = 'api_username' OR 
						payconfig_varname = 'api_password' OR payconfig_varname = 'cert_file_path' OR payconfig_varname = 'signature')";
		$Result1 	= 	$this->db->get_row($Qry1, ARRAY_A);
		$TotCount	=	$Result1['TotCount'];
		if($TotCount == 0)
			return $ConfDetails;
		
		$Qry2		=	"SELECT payconfig_varname,payconfig_varvalue FROM payment_configuration WHERE store_id='$StoreId' AND (payconfig_varname = 'api_username' OR 
						payconfig_varname = 'api_password' OR payconfig_varname = 'cert_file_path' OR payconfig_varname = 'signature')";
		$Result2	=	$this->db->get_results($Qry2);	
		foreach($Result2 as $Row)
			$ConfDetails[$Row->payconfig_varname]	=	$Row->payconfig_varvalue;
		return $ConfDetails;
	}
	
	# The following method abstracts the Save and Update functionality of paypal pro form
	function setPaypalPro($REQUEST, $FILES)
	{
		extract($REQUEST);
		
		if(count($creditcards) > 0) {
			$CreditCards	=	implode('^*^',$creditcards);
			$Qry01			=	"UPDATE payment_methods_stores SET credit_cards = '$CreditCards' WHERE store_id = '$store_id'";
			$this->db->query($Qry01);
		} else {
			$Qry02			=	"UPDATE payment_methods_stores SET credit_cards = '' WHERE store_id = '$store_id'";
			$this->db->query($Qry02);
		}
		
		
		if($storeowner == 'admin')
			$store_id		=	'0';
		
		$Qry1		=	"SELECT COUNT(*) AS TotCount FROM payment_configuration WHERE store_id='$store_id' AND (payconfig_varname = 'api_username' OR 
						payconfig_varname = 'api_password' OR payconfig_varname = 'cert_file_path' OR payconfig_varname = 'signature')";
		$Result1 	= 	$this->db->get_row($Qry1, ARRAY_A);
		$TotCount	=	$Result1['TotCount'];
		
		if($TotCount == 0) {
			$InsArray1	=	array("store_id" => $store_id, "payconfig_varname" => "api_username", "payconfig_varvalue" => $api_username);
			$InsArray2	=	array("store_id" => $store_id, "payconfig_varname" => "api_password", "payconfig_varvalue" => $api_password);
			$InsArray3	=	array("store_id" => $store_id, "payconfig_varname" => "signature", "payconfig_varvalue" => $signature);
			$InsArray4	=	array("store_id" => $store_id, "payconfig_varname" => "cert_file_path", "payconfig_varvalue" => '');
			
			$this->db->insert("payment_configuration", $InsArray1);
			$this->db->insert("payment_configuration", $InsArray2);
			$this->db->insert("payment_configuration", $InsArray3);
			$this->db->insert("payment_configuration", $InsArray4);
			$ConfigId		=	$this->db->insert_id;	
			
			if($FILES['cert_file_path']['size'] > 0) {
				$CertFileName	=	'cert_'.$ConfigId.'.'.$this->PaymentObj->getExtensionOfFile($FILES['cert_file_path']['name']);
				copy($FILES['cert_file_path']['tmp_name'], SITE_PATH.'/modules/payment/certificatefiles/'.$CertFileName);
				
				$Qry2	=	"UPDATE payment_configuration SET payconfig_varvalue = '$CertFileName' WHERE payconfig_id = '$ConfigId'";
				$this->db->query($Qry2);
				
				$Qry21	=	"UPDATE payment_configuration SET payconfig_varvalue = '' WHERE store_id = '$store_id' AND payconfig_varname = 'signature'";
				$this->db->query($Qry21);
				
			}	
		} else {
			$Qry3		=	"UPDATE payment_configuration SET payconfig_varvalue = '$api_username' WHERE payconfig_varname = 'api_username' AND store_id = '$store_id'";
			$this->db->query($Qry3);
			$Qry4		=	"UPDATE payment_configuration SET payconfig_varvalue = '$api_password' WHERE payconfig_varname = 'api_password' AND store_id = '$store_id'";
			$this->db->query($Qry4);
			
			if(trim($signature)) {
				$Qry5		=	"UPDATE payment_configuration SET payconfig_varvalue = '$signature' WHERE payconfig_varname = 'signature' AND store_id = '$store_id'";
				$this->db->query($Qry5);
				
				$Qry51		=	"UPDATE payment_configuration SET payconfig_varvalue = '' WHERE store_id = '$store_id' AND payconfig_varname = 'cert_file_path'";
				$this->db->query($Qry51);
			}
			
			if($FILES['cert_file_path']['size'] > 0) {
				$Qry6			=	"SELECT payconfig_id FROM payment_configuration WHERE payconfig_varname = 'cert_file_path' AND store_id = '$store_id'";
				$Result6 		= 	$this->db->get_row($Qry6, ARRAY_A);
				$payconfig_id	=	$Result6['payconfig_id'];
				$CertFileName	=	'cert_'.$payconfig_id.'.'.$this->PaymentObj->getExtensionOfFile($FILES['cert_file_path']['name']);
				if(file_exists(SITE_PATH.'/modules/payment/certificatefiles/'.$CertFileName))
					unlink(SITE_PATH.'/modules/payment/certificatefiles/'.$CertFileName);
				copy($FILES['cert_file_path']['tmp_name'], SITE_PATH.'/modules/payment/certificatefiles/'.$CertFileName);	
				$Qry6		=	"UPDATE payment_configuration SET payconfig_varvalue = '$CertFileName' WHERE payconfig_id = $payconfig_id";
				$this->db->query($Qry6);
				
				$Qry61	=	"UPDATE payment_configuration SET payconfig_varvalue = '' WHERE store_id = '$store_id' AND payconfig_varname = 'signature'";
				$this->db->query($Qry61);
			}	
		}
		return TRUE;
	}
	
	# The following method validates the paypal pro details form
	function validatePaypalPro($REQUEST,$FILES)
	{
		extract($REQUEST);
		
		$StatusMsg	=	'';
		
		if(trim($api_username) == '')
			$StatusMsg	=	'API Username Required<br>';
		if(trim($api_password) == '')
			$StatusMsg	.=	'API Password Required<br>';	
		if($FILES['cert_file_path']['size'] == 0 && trim($signature) == '' && trim($old_cert_file_path) == '')
			$StatusMsg	.=	'Either Certificate File or Signature required<br>';
		if($FILES['cert_file_path']['size'] > 0 && trim($signature) != '' )
			$StatusMsg	.=	'Either Certificate File or Signature required. Both are not required<br>';	
		if(count($creditcards) == 0)
			$StatusMsg	.=	'At least one Card Type must be active<br>';
			
		if($StatusMsg == '')
			return TRUE;
		else
			return $StatusMsg;	
	}
	
	
	/**
	 * The following method processPaypalProOld ==> processPaypalPro (Renamed asfor implementing NVP API )
	 *
	 * @param string $StoreName
	 * @param Associative Array $Params
	 * @return Associative Array
	 */
	function processPaypalProOld($StoreName, $Params)
	{
		$Results	=	array();
				
		$ConfInfo			=	$this->PaymentObj->getConfigurationDetailsOfStore($StoreName); # Retrieves configuratrion details
		
		$API_USERNAME		=	$ConfInfo['api_username'];
		$API_PASSWORD		=	$ConfInfo['api_password'];
		$API_SIGNATURE		=	$ConfInfo['signature'];
		$API_CERTPATH		=	SITE_PATH.'/modules/payment/certificatefiles/'.$ConfInfo['cert_file_path'];
				
		define('API_USERNAME', $API_USERNAME);
		define('API_PASSWORD', $API_PASSWORD);
		define('API_CERTPATH', $API_CERTPATH);
		define('API_SIGNATURE', $API_SIGNATURE);
		
		
		require_once FRAMEWORK_PATH."/includes/payment/paypal/include.php";
		
		$param		=	array();
		
		$param['firstName']				=	$Params['firstName'];
		$param['lastName']				=	$Params['lastName'];
		$param['creditCardType']		=	$Params['creditCardType']; 		# Visa,MasterCard,Discover,Amex
		$param['creditCardNumber']		=	$Params['creditCard'];
		$param['expDateMonth']			=	$Params['Expiry_Month'];
		$param['expDateYear']			=	$Params['Expiry_Year'];			# Four Digit representation for paypal pro 
		$param['cvv2Number']			=	$Params['cvc'];
		$param['address1']				=	$Params['address1'];
		$param['address2']				=	$Params['address2'];
		$param['city']					=	$Params['city'];
		$param['state']					=	$Params['state'];
		$param['zip']					=	$Params['zip'];
		$param['country']				=	$Params['country'] ? $Params['country'] : 'US';
		$param['amount']				=	$Params['paid_price'];

		$Response	=	processPayment($param);
		

		$Results	=	array();
		if(trim($Response['Ack']) === 'Success' || trim($Response['Ack']) === 'SuccessWithWarning') {
			$Results['Approved']		=	'Yes';
			$Results['TransactionId']	=	$Response['TransactionID'];
			$Results['Message']			=	$Response['Ack'];
		} else {
			$Results['Approved']		=	'No';
			$Results['TransactionId']	=	0;
			$Results['Message']			=	$Response['errorMessage'];
		}
		return $Results;
	}

	
	
	/**
	 * The following method called by the common payment interface
	 *
	 * @param string $StoreName
	 * @param associative array $Params
	 * @return associative array
	 */
	function processPaypalPro($StoreName, $Params)
	{
		$Results	=	array();
				
		$ConfInfo			=	$this->PaymentObj->getConfigurationDetailsOfStore($StoreName); # Retrieves configuratrion details
		
		$API_USERNAME		=	$ConfInfo['api_username'];
		$API_PASSWORD		=	$ConfInfo['api_password'];
		$API_SIGNATURE		=	$ConfInfo['signature'];
		$API_CERTPATH		=	SITE_PATH.'/modules/payment/certificatefiles/'.$ConfInfo['cert_file_path'];
				
		define('API_USERNAME', $API_USERNAME);
		define('API_PASSWORD', $API_PASSWORD);
		define('API_CERTPATH', $API_CERTPATH);
		define('API_SIGNATURE', $API_SIGNATURE);
		
		
		require_once FRAMEWORK_PATH."/includes/payment/paypal/class.webpaypalpro.php";
		
		$APIInfo	=	array();
		$param		=	array();
		
		
		$APIInfo['API_UserName']	=	$API_USERNAME;
		$APIInfo['API_Password']	=	$API_PASSWORD;
		$APIInfo['API_Signature']	=	$API_SIGNATURE;
		$WebsitePaypalProObj		=	new WebsitePaypalPro($APIInfo);
				
		
		$param['paymentType']			=	'SALE';
		$param['firstName']				=	$Params['firstName'];
		$param['lastName']				=	$Params['lastName'];
		$param['creditCardType']		=	$Params['creditCardType']; 		# Visa,MasterCard,Discover,Amex
		$param['creditCardNumber']		=	$Params['creditCard'];
		$param['expDateMonth']			=	$Params['Expiry_Month'];
		$param['expDateYear']			=	$Params['Expiry_Year'];			# Four Digit representation for paypal pro 
		$param['cvv2Number']			=	$Params['cvc'];
		$param['address1']				=	$Params['address1'];
		$param['address2']				=	$Params['address2'];
		$param['city']					=	$Params['city'];
		$param['state']					=	$Params['state'];
		$param['zip']					=	$Params['zip'];
		$param['country']				=	$Params['country'] ? $Params['country'] : 'US';
		$param['amount']				=	$Params['paid_price'];

		$Response	=	$WebsitePaypalProObj->doDirectPayment($param);
		$ack 		= 	strtoupper($Response["ACK"]);
		
		$Results	=	array();
		if($ack === 'SUCCESS' || $ack === 'SUCCESSWITHWARNING') {
			$Results['Approved']		=	'Yes';
			$Results['TransactionId']	=	$Response['TRANSACTIONID'];
			$Results['Message']			=	$Response['ACK'];
		} else {
			$Results['Approved']		=	'No';
			$Results['TransactionId']	=	0;
			$Results['Message']			=	$Response['L_LONGMESSAGE0'];
			#$Results['Message']			=	'Invalid Data in one or more required fields';
		}
				
		return $Results;
	}
	
	
	
	
} # Close class definition 
?>