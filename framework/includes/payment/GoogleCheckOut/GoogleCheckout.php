<?php

/**

	The following class abstracts the Google Checkout payment gateway process operations.

	The following fields are used as a payment gateway account details
		a. merchant_id
		b. merchant_key

	The following methods are used
		a. getGoogleCheckout($StoreId, $PaymentReceiver) -- Retrives the payment details associated with a Store
		b. setGoogleCheckout($REQUEST, $FILES) -- This methos stores the payment details associated with a store
		c. validateGoogleCheckout($REQUEST,$FILES) -- Validating the payment details form for paypal pro
		d. processGoogleCheckout($StoreName, $Params) -- Send request to the payment gateway for payment processing and
												 -- processes the response from the payment gateway
												 -- This method is used by the common interface created in the class.payment.php file
	
 **/	


class GoogleCheckout extends FrameWork
{	
	
	var		$PaymentObj;
	
	# Constructor
	function GoogleCheckout($PaymentObj = '')
	{
		$this->PaymentObj	=	$PaymentObj;	
		$this->FrameWork();
	}
	
	function getGoogleCheckout($StoreId, $PaymentReceiver)
	{
		$ConfDetails	=	array();
		
		if($PaymentReceiver == 'admin')
			$StoreName	=	'0';
		
		$Qry1		=	"SELECT COUNT(*) AS TotCount FROM payment_configuration WHERE store_id = '$StoreId' AND 
						(payconfig_varname = 'merchant_id' OR payconfig_varname = 'merchant_key')";
		$Result1 	= 	$this->db->get_row($Qry1, ARRAY_A);
		$TotCount	=	$Result1['TotCount'];
		if($TotCount == 0)
			return $ConfDetails;	

		$Qry2		=	"SELECT payconfig_varname,payconfig_varvalue FROM payment_configuration WHERE store_id = '$StoreId' AND 
						(payconfig_varname = 'merchant_id' OR payconfig_varname = 'merchant_key')";
		$Result2	=	$this->db->get_results($Qry2);	
		foreach($Result2 as $Row)
			$ConfDetails[$Row->payconfig_varname]	=	$Row->payconfig_varvalue;
		return $ConfDetails;	
	}
	
	function setGoogleCheckout($REQUEST, $FILES)
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
		
		$Qry1		=	"SELECT COUNT(*) AS TotCount FROM payment_configuration WHERE store_id = '$store_id' AND 
						(payconfig_varname = 'merchant_id' OR payconfig_varname = 'merchant_key')";
		$Result1 	= 	$this->db->get_row($Qry1, ARRAY_A);
		$TotCount	=	$Result1['TotCount'];
		if($TotCount == 0) {
			$InsArray1	=	array("store_id" => $store_id, "payconfig_varname" => "merchant_id", "payconfig_varvalue" => $merchant_id);
			$InsArray2	=	array("store_id" => $store_id, "payconfig_varname" => "merchant_key", "payconfig_varvalue" => $merchant_key);
			$this->db->insert("payment_configuration", $InsArray1);
			$this->db->insert("payment_configuration", $InsArray2);
		} else {
			$Qry2	=	"UPDATE payment_configuration SET payconfig_varvalue = '$merchant_id' WHERE store_id = '$store_id' AND payconfig_varname = 'merchant_id'";
			$this->db->query($Qry2);
			
			$Qry3	=	"UPDATE payment_configuration SET payconfig_varvalue = '$merchant_key' WHERE store_id = '$store_id' AND payconfig_varname = 'merchant_key'";
			$this->db->query($Qry3);
		}
	}
	
	function validateGoogleCheckout($REQUEST, $FILES)
	{
		extract($REQUEST);
		$StatusMsg	=	'';
		
		if(trim($merchant_id) == '') 
			$StatusMsg	.=	'Merchant  ID required<br>';
		if(trim($merchant_key) == '') 
			$StatusMsg	.=	'Merchant Transaction Key required';
		if(count($creditcards) == 0)
			$StatusMsg	.=	'At least one Card Type must be active<br>';
		
		if($StatusMsg == '')
			return TRUE;
		else
			return $StatusMsg;		
	}
	
	# The following method processes the payment details collected from the end user
	function processGoogleCheckout($StoreName, $Params)
	{
	
		$Results	=	array();
		
		require_once FRAMEWORK_PATH."/includes/payment/GoogleCheckOut/class.googlecheck.php";
		
		$ConfInfo		=	$this->PaymentObj->getConfigurationDetailsOfStore($StoreName); # Retrieves configuratrion details

		$login			=	$ConfInfo['merchant_id'];
		$transkey		=	$ConfInfo['merchant_key'];
		
		$Amount			=	$Params['paid_price'];
		$CreditCardNo	=	$Params['creditCard'];
		
		$ExpireMonth	=	$Params['Expiry_Month'];
		$ExpireYear		=	$Params['Expiry_Year'];
		$ExpireDate		=	$ExpireMonth.''.$ExpireYear;
		
		$Cvc			=	$Params['cvc'];
		
		
		
			# Authnet($test = false, $login = '2n4XG4e2XPfn', $transkey = "2n94Pc3EHbGr4n9g")
		$GoogleObj		=	new Google(false, $login, $transkey);	
		$GoogleObj		->	transaction($CreditCardNo, $ExpireDate, $Amount, $Cvc, $Params);
		$GoogleObj		->	process();
		
		if($GoogleObj->isApproved()) {
			$Results['Approved']		=	'Yes';
			$Results['TransactionId']	=	$GoogleObj->getTransactionID();
			$Results['Message']			=	$GoogleObj->getResponseText();
		} else if($GoogleObj->isDeclined()) {

			$Results['Approved']		=	'No';
			$Results['TransactionId']	=	'0';
			$Results['Message']			=	$GoogleObj->getResponseText();
		} else {
			$Results['Approved']		=	'No';
			$Results['TransactionId']	=	'0';
			$Results['Message']			=	$GoogleObj->getResponseText();
		}	
		
		return $Results;

	} # Close method definition
	

} # Close class definition 

?>