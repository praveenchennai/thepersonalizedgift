<?php

/**

	The following class abstracts the eSELECT payment gateway process operations.

	The following fields are used as a payment gateway account details
		a. store_id
		b. api_token

	The following methods are used
		a. geteSELECT($StoreId, $PaymentReceiver) -- Retrives the payment details associated with a Store
		b. seteSELECT($REQUEST, $FILES) -- This methos stores the payment details associated with a store
		c. validateeSELECT($REQUEST,$FILES) -- Validating the payment details form for paypal pro
		d. processeSELECT($StoreName, $Params) -- Send request to the payment gateway for payment processing and
												 -- processes the response from the payment gateway
												 -- This method is used by the common interface created in the class.payment.php file
	
 **/	


class eSELECT extends FrameWork
{	
	
	var		$PaymentObj;
	
	# Constructor
	function eSELECT($PaymentObj = '')
	{
		$this->PaymentObj	=	$PaymentObj;	
		$this->FrameWork();
	}
	
	function geteSELECT($StoreId, $PaymentReceiver)
	{
		$ConfDetails	=	array();
		
		if($PaymentReceiver == 'admin')
			$StoreName	=	'0';
		
		$Qry1		=	"SELECT COUNT(*) AS TotCount FROM payment_configuration WHERE store_id = '$StoreId' AND 
						(payconfig_varname = 'eSEL_store_id' OR payconfig_varname = 'eSEL_api_token')";
		$Result1 	= 	$this->db->get_row($Qry1, ARRAY_A);
		$TotCount	=	$Result1['TotCount'];
		if($TotCount == 0)
			return $ConfDetails;	

		$Qry2		=	"SELECT payconfig_varname,payconfig_varvalue FROM payment_configuration WHERE store_id = '$StoreId' AND 
						(payconfig_varname = 'eSEL_store_id' OR payconfig_varname = 'eSEL_api_token' OR payconfig_varname = 'eSEL_mod')";
		$Result2	=	$this->db->get_results($Qry2);	
		foreach($Result2 as $Row)
			$ConfDetails[$Row->payconfig_varname]	=	$Row->payconfig_varvalue;
		return $ConfDetails;	
	}
	
	function seteSELECT($REQUEST, $FILES)
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
						(payconfig_varname = 'eSEL_store_id' OR payconfig_varname = 'eSEL_api_token')";
		$Result1 	= 	$this->db->get_row($Qry1, ARRAY_A);
		$TotCount	=	$Result1['TotCount'];
		if(isset($eSEL_mod)) 
			{  $eSEL_mod="Y"; }
			else {  $eSEL_mod="N"; }
		if($TotCount == 0) {
			
			$InsArray1	=	array("store_id" => $store_id, "payconfig_varname" => "eSEL_store_id", "payconfig_varvalue" => $eSEL_store_id);
			$InsArray2	=	array("store_id" => $store_id, "payconfig_varname" => "eSEL_api_token", "payconfig_varvalue" => $eSEL_api_token);
			$InsArray3	=	array("store_id" => $store_id, "payconfig_varname" => "eSEL_mod", "payconfig_varvalue" => $eSEL_mod);

			$this->db->insert("payment_configuration", $InsArray1);
			$this->db->insert("payment_configuration", $InsArray2);
			$this->db->insert("payment_configuration", $InsArray3);
		} else {
			$Qry2	=	"UPDATE payment_configuration SET payconfig_varvalue = '$eSEL_store_id' WHERE store_id = '$store_id' AND payconfig_varname = 'eSEL_store_id'";
			$this->db->query($Qry2);
			
			$Qry3	=	"UPDATE payment_configuration SET payconfig_varvalue = '$eSEL_api_token' WHERE store_id = '$store_id' AND payconfig_varname = 'eSEL_api_token'";
			$this->db->query($Qry3);
			
			$Qry3	=	"UPDATE payment_configuration SET payconfig_varvalue = '$eSEL_mod' WHERE store_id = '$store_id' AND payconfig_varname = 'eSEL_mod'";
			$this->db->query($Qry3);

		}
	}
	
	function validateeSELECT($REQUEST, $FILES)
	{
		extract($REQUEST);
		$StatusMsg	=	'';
		
		if(trim($eSEL_store_id) == '') 
			$StatusMsg	.=	'Merchant Store ID required<br>';
		if(trim($eSEL_api_token) == '') 
			$StatusMsg	.=	'Merchant API Token required';
		if(count($creditcards) == 0)
			$StatusMsg	.=	'At least one Card Type must be active<br>';
		
		if($StatusMsg == '')
			return TRUE;
		else
			return $StatusMsg;		
	}
	
	# The following method processes the payment details collected from the end user
	function processeSELECT($StoreName, $Params)
	{
		$Results	=	array();
		
		require_once FRAMEWORK_PATH."/includes/payment/eSELECT/lib/mpgClasses.php";
		
		$ConfInfo		=	$this->PaymentObj->getConfigurationDetailsOfStore($StoreName); # Retrieves configuratrion details

		#************************ Request Variables ***************************#
		
		$e_store_id		=	$ConfInfo['eSEL_store_id'];
		$e_api_token	=	$ConfInfo['eSEL_api_token'];
		$e_mod			=	$ConfInfo['eSEL_mod'];

		#************************ Setting Producyion/Development Server	*******#
		
		if ($e_mod == 'Y')	{
			$ESEL_SERVERHOST	=	'esqa.moneris.com';
		} else {
			$ESEL_SERVERHOST	=	'www3.moneris.com';
		}
		#********************* Transactional Variables ************************#
		$type			=	'purchase';
		$crypt			=	'7';
		$order_id		=	 'ORD-' . $Params['invoice_number'] . '-' . date("dmy-G:i:s"); //'ord-'.date("dmy-G:i:s"); //$Params['invoice_number'];
		$cust_id		=	 $Params['firstName'];
		$amount			=	 number_format($Params['paid_price'],2);
		$pan			=	 $Params['creditCard']; #'4242424242424242';
		$expiry_date	=	 $Params['Expiry_Year'].$Params['Expiry_Month']; #'0812';				#	December 2008
		$Cvc			=	 $Params['cvc'];
		
		#******************* Customer Information Variables ********************#
		$mpgCustInfo = new mpgCustInfo();  # Customer Information Object
		
		$billing = array(
				 'first_name' 	=> 	$Params['firstName'],
                 'last_name' 	=>  $Params['lastName'],
                 'company_name' =>  $Params['company'],
                 'address' 		=>  $Params['address1']. '' .$Params['address2'],
                 'city' 		=>  $Params['city'],
                 'province' 	=>  $Params['state'],
                 'postal_code'	=>  $Params['zip'],
                 'country' 		=>  $Params['country'],
                 'phone_number' =>  $Params['phone'],
                 'fax' 			=>  '',
                 'tax1' 		=>  $Params['tax'],
                 'tax2'   		=>  '',
                 'tax3' 		=>  '',
                 'shipping_cost'=>  ''
                 );
		
		$shipping = array(
				 'first_name' 	=> $Params['ship_to_first_name'],
                 'last_name' 	=> $Params['ship_to_last_name'],
                 'company_name' => $Params['ship_to_company'],
                 'address' 		=> $Params['ship_to_address1']. '' .$Params['ship_to_address2'],
                 'city' 		=> $Params['ship_to_city'],
                 'province' 	=> $Params['ship_to_state'],
                 'postal_code' 	=> $Params['ship_to_zip'],
                 'country' 		=> $Params['ship_to_country'],
                 'phone_number' => $Params['ship_to_state'],
                 'fax' 			=> '',
                 'tax1' 		=> $Params['tax'],
                 'tax2' 		=> '',
                 'tax3' 		=> '',
                 'shipping_cost'=> ''
                 );
		
		$mpgCustInfo->setBilling($billing);
		$mpgCustInfo->setShipping($shipping);
		$mpgCustInfo->setEmail($Params['mail']);
		
		
		
		#***************** Transactional Associative Array ********************#
	
		$txnArray=array(
				'type'			=>	$type,
		        'order_id'		=>	$order_id,
		        'cust_id'		=>	$cust_id,
		        'amount'		=>	$amount,
		        'pan'			=>	$pan,
		        'expdate'		=>	$expiry_date,
		        'crypt_type'	=>	$crypt
	           );


		$mpgTxn 		= 	new mpgTransaction($txnArray);     # Transaction Object


		#************************* Request Object *****************************#
		$mpgRequest		= 	new mpgRequest($mpgTxn);


		#************************ HTTPS Post Object ***************************#

		$mpgHttpPost  	=	new mpgHttpsPost($e_store_id,$e_api_token,$mpgRequest,$ESEL_SERVERHOST);

		#****************8********** Response *********************************#
		
		$mpgResponse	=	$mpgHttpPost->getMpgResponse();
		
		if ( intval($mpgResponse->getResponseCode()) < 50 && intval($mpgResponse->getResponseCode()) > 0 ) {    			# Transaction Approved
			$Results['Approved']		=	'Yes';
			$Results['TransactionId']	=	$mpgResponse->getReceiptId();   # getTxnNumber
			$Results['Message']			=	$mpgResponse->getMessage();
		} else if ( intval($mpgResponse->getResponseCode()) >= 50 ) {		# Transaction Declined
			$Results['Approved']		=	'No';
			$Results['TransactionId']	=	'0';
			$Results['Message']			=	$mpgResponse->getMessage();
		} else {															# NULL , Transaction Not Sent to Authorization	
			$Results['Approved']		=	'No';
			$Results['TransactionId']	=	'0';
			$Results['Message']			=	$mpgResponse->getMessage();
		}
		
		
		return $Results;

	} # Close method definition
	

} # Close class definition 

?>