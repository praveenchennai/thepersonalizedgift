<?php

/**

	The following class abstracts the LinkpointCentral payment gateway process operations.

	The following fields are used as a payment gateway account details
		a. configfile
		b. keyfile

	The following methods are used
		a. getLinkpointCentral($StoreId, $PaymentReceiver) -- Retrives the payment details associated with a Store
		b. setLinkpointCentral($REQUEST, $FILES) -- This methos stores the payment details associated with a store
		c. validateLinkpointCentral($REQUEST,$FILES) -- Validating the payment details form for paypal pro
		d. processLinkpointCentral($StoreName, $Params) -- Send request to the payment gateway for payment processing and
												 -- processes the response from the payment gateway
												 -- This method is used by the common interface created in the class.payment.php file
	
 **/	

	

class LinkpointCentral extends FrameWork
{
	var		$PaymentObj;
	
	# Constructor
	function LinkpointCentral($PaymentObj = '')
	{
		$this->PaymentObj	=	$PaymentObj;
		$this->FrameWork();
	}
	
	function getLinkpointCentral($StoreId, $PaymentReceiver)
	{
		$ConfDetails	=	array();
		
		if($PaymentReceiver == 'admin')
			$StoreName	=	'0';
		
		$Qry1		=	"SELECT COUNT(*) AS TotCount FROM payment_configuration WHERE 
						store_id = '$StoreId' AND (payconfig_varname = 'configfile' OR payconfig_varname = 'keyfile')";
		$Result1 	= 	$this->db->get_row($Qry1, ARRAY_A);
		$TotCount	=	$Result1['TotCount'];
		if($TotCount == 0)
			return $ConfDetails;
		
		$Qry2		=	"SELECT payconfig_varname,payconfig_varvalue FROM payment_configuration WHERE 
						store_id = '$StoreId' AND (payconfig_varname = 'configfile' OR payconfig_varname = 'keyfile')";
		$Result2	=	$this->db->get_results($Qry2);	
		foreach($Result2 as $Row)
			$ConfDetails[$Row->payconfig_varname]	=	$Row->payconfig_varvalue;
		return $ConfDetails;
	}
	
	function setLinkpointCentral($REQUEST, $FILES)
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
						(payconfig_varname = 'configfile' OR payconfig_varname = 'keyfile')";
		$Result1 	= 	$this->db->get_row($Qry1, ARRAY_A);
		$TotCount	=	$Result1['TotCount'];
		if($TotCount == 0) {
			$InsArray1	=	array("store_id" => $store_id, "payconfig_varname" => "configfile", "payconfig_varvalue" => $configfile);
			$this->db->insert("payment_configuration", $InsArray1);
			
			$InsArray2	=	array("store_id" => $store_id, "payconfig_varname" => "keyfile", "payconfig_varvalue" => "");
			$this->db->insert("payment_configuration", $InsArray2);
			$ConfId		=	$this->db->insert_id;
			$File		=	"KeyFile_".$ConfId.'.'.$this->PaymentObj->getExtensionOfFile($FILES['keyfile']['name']);
			copy($FILES['keyfile']['tmp_name'], SITE_PATH.'/modules/payment/certificatefiles/'.$File);
			
			$Qry2	=	"UPDATE payment_configuration SET payconfig_varvalue = '$File' WHERE 
						store_id = '$store_id' AND payconfig_varname = 'keyfile'";
			$this->db->query($Qry2);			
		} else {
			$Qry3		=	"UPDATE payment_configuration SET payconfig_varvalue = '$configfile' WHERE 
							store_id = '$store_id' AND payconfig_varname = 'configfile'";
			$this->db->query($Qry3);

			if($FILES['keyfile']['size'] > 0) {
				$Qry4			=	"SELECT payconfig_id FROM payment_configuration WHERE payconfig_varname = 'keyfile' AND store_id = '$store_id'";
				$Result4 		= 	$this->db->get_row($Qry4, ARRAY_A);
				$payconfig_id	=	$Result4['payconfig_id'];
				$File		=	"KeyFile_".$payconfig_id.'.'.$this->PaymentObj->getExtensionOfFile($FILES['keyfile']['name']);
				copy($FILES['keyfile']['tmp_name'], SITE_PATH.'/modules/payment/certificatefiles/'.$File);
				$Qry5	=	"UPDATE payment_configuration SET payconfig_varvalue = '$File' WHERE 
							store_id = '$store_id' AND payconfig_varname = 'keyfile'";
				$this->db->query($Qry5);			
			}
		}
	}
	
	function validateLinkpointCentral($REQUEST, $FILES)
	{
		extract($REQUEST);
		$StatusMsg	=	'';
		
		if(trim($configfile) ==  '')
			$StatusMsg	.=	'Merchant Store Number required<br>';
		if($FILES['keyfile']['size'] == 0 && $old_keyfile == '') 
			$StatusMsg	.=	'Key File required';
		if(count($creditcards) == 0)
			$StatusMsg	.=	'At least one Card Type must be active<br>';	
		if($StatusMsg == '')
			return TRUE;
		else
			return $StatusMsg;
	}	
	
	# The following method process the Linkpoint central payment details
	function processLinkpointCentral($StoreName, $Params)
	{
		$Results	=	array();
		
		require_once FRAMEWORK_PATH."/includes/payment/lpc/lib/lphp.php";
				
		$ConfInfo		=	$this->PaymentObj->getConfigurationDetailsOfStore($StoreName); # Retrieves configuratrion details
		$host			=	LINKPOINT_HOST_NAME;
		$port			=	LINKPOINT_PORT_NUMBER;
		
		$configfile		=	$ConfInfo['configfile'];
		$keyfile		=	SITE_PATH.'/modules/payment/certificatefiles/'.$ConfInfo['keyfile'];
		
		$myorder["host"] 	   		= 	LINKPOINT_HOST_NAME;
		$myorder["port"] 	   		= 	LINKPOINT_PORT_NUMBER;
		$myorder["keyfile"]   		= 	$keyfile; 			# Certificate File
		$myorder["configfile"]		= 	$configfile; 		# Store Number
		$myorder["ordertype"]  		= 	LINKPOINT_ORDER_TYPE;
		$myorder["result"]     		= 	LINKPOINT_TEST_STATUS;
		
		$myorder["subtotal"]    	= 	$Params['paid_price'];
		$myorder["cardnumber"]   	= 	$Params['creditCard'];
		$myorder["cardexpmonth"] 	= 	$Params['Expiry_Month'];
		$myorder["cardexpyear"] 	= 	$Params['Expiry_Year'];
		$myorder["cvmindicator"] 	= 	'provided';
		$myorder["cvmvalue"] 		= 	$Params['cvc'];
		
		$myorder["name"]			= 	$Params['first_name'];
		$myorder["company"] 		= 	$Params['company_name'];
		$myorder["address1"] 		=	$Params['address1'];
		$myorder["address2"] 		=	$Params['address2'];
		$myorder["city"] 			=	$Params['city'];
		$myorder["state"] 			=	$Params['state'];
		$myorder["country"] 		=	$Params['country'];
		$myorder["phone"]			=	$Params['phone'];
		$myorder["email"]			=	$Params['mail'];
		
		$lphpObj	=	new lphp;
		$Result 	= 	$lphpObj->curl_process($myorder);
		
		if ($Result["r_approved"] != "APPROVED")  {  // transaction failed, print the reason
			$Results['Approved']		=	'No';
			$Results['TransactionId']	=	0;
			$Results['Message']			=	substr($Result['r_error'],12);
		} else {
			$Results['Approved']		=	'Yes';
			$Results['TransactionId']	=	$Result['r_code'];
			$Results['Message']			=	$Result['r_approved'];
		}
		return $Results;
			
	} # Close method definition
	

} # Close class definition	

?>