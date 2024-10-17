<?php

/**
 * This class abstracts the payment operation. This class provides a common interface for the payment gateway processing
 * 
 * @author newage
 *
 **/


require_once FRAMEWORK_PATH."/modules/order/lib/paymentconfig.php";

class Payment extends FrameWork 
{
	var		$Methods;
	
	# Class Constructor
	function Payment()
	{
		$this->Methods	=	array();
		$this->FrameWork();
	}
	
	# The following method returns the available Payment methods 
	function getPaymentMethods($orderBy)
	{
		$this->Methods	=	array();
		if(trim($orderBy)) {
			$OrderByArr		=	explode(':',$orderBy);
			$Order			=	$OrderByArr[1];
			$field			=	$OrderByArr[0];
			$Order_By		=	"ORDER BY $field $Order ";
		} else {
			$Order_By		=	" ORDER BY paymethod_name ASC";
		}
		$Qry			=	"SELECT * FROM payment_methods WHERE active = 'Y' $Order_By";
		$Result 		= $this->db->get_results($Qry);
		if($Result) {
			$Arrindx	=	0;
			foreach($Result as $Row) {
				$this->Methods[$Arrindx]['paymethod_id']			=	$Row->paymethod_id;	  
				$this->Methods[$Arrindx]['paymethod_name']			=	$Row->paymethod_name;	  
				$this->Methods[$Arrindx]['paymethod_description']	=	$Row->paymethod_description;
				$Arrindx++;
			} 
		} 
		return $this->Methods;
	} 
	
	# The following method stores the payment method selection details
	function processPaymentMethodFormDetails($REQUEST)
	{
		extract($REQUEST); 																		# CreditCards
		if($storeowner == 'admin') {
			if($Action == 'DeActivate') {
				$Qry1	=	"UPDATE payment_methods_stores SET paymethod_id = '0', credit_cards = '' WHERE store_id = '0'";
				$this->db->query($Qry1);
				
				$Qry11	=	"DELETE FROM payment_configuration WHERE store_id = '0'";
				$this->db->query($Qry11);
				
			} else if($Action == 'Activate') {
				$Qry2	=	"UPDATE payment_methods_stores SET paymethod_id = '$paymethod_id',credit_cards = '$CreditCards' WHERE store_id = '0'";
				$this->db->query($Qry2);
				
				$Qry21	=	"DELETE FROM payment_configuration WHERE store_id = '0'";
				$this->db->query($Qry21);
			}
			
		} # Close storeowner == admin
		
		if($storeowner == 'store') {
			$Qry3		=	"SELECT COUNT(*) AS TotCount FROM payment_methods_stores WHERE store_id = '$store_id'";
			$Result3 	=	$this->db->get_row($Qry3, ARRAY_A);
			$TotCount	=	$Result3['TotCount'];
			if($Action == 'Activate') {
				if($TotCount > 0)
					$Qry4	=	"UPDATE payment_methods_stores SET paymethod_id = '$paymethod_id',credit_cards = '$CreditCards' WHERE store_id = '$store_id'"; 
				else
					$Qry4	=	"INSERT INTO payment_methods_stores (paymethod_id,store_id,credit_cards) VALUES ('$paymethod_id','$store_id','$CreditCards')";
				$this->db->query($Qry4);
				
				$Qry41	=	"DELETE FROM payment_configuration WHERE store_id = '$store_id'";
				$this->db->query($Qry41);
				
			} else if($Action == 'DeActivate') {
				$Qry5	=	"UPDATE payment_methods_stores SET paymethod_id = '',credit_cards = '' WHERE store_id = '$store_id'";
				$this->db->query($Qry5);
				
				$Qry51	=	"DELETE FROM payment_configuration WHERE store_id = '$store_id'";
				$this->db->query($Qry51);
			}
		} # Close storeowner == store
		
	}
	
		
	# The following method deactivates the payment method associated with the main and sub stores
	/**
      * The following method deactivates the payment method associated with the main and sub stores
      * Author   : 
      * Created  : 
      * Modified : 13/Nov/2007 By Adarsh
      */


	# The following method deactivates the payment method associated with the main and sub stores
	function deactivatePaymentMethodOfStores($REQUEST)
	{
		extract($REQUEST);
		if($storeowner == 'admin') {
			if($Action == 'DeActivate') {
				$Qry1	=	"UPDATE payment_methods_stores SET paymethod_id = '0', credit_cards = '' WHERE store_id = '0'";
				$this->db->query($Qry1);
				
				//$Qry11	=	"DELETE FROM payment_configuration WHERE store_id = '0'";
				//$this->db->query($Qry11);
			} else if($Action == 'Activate') {
				$Qry2	=	"UPDATE payment_methods_stores SET paymethod_id = '',credit_cards = '' WHERE store_id = '0'";
				$this->db->query($Qry2);
				//$Qry21	=	"DELETE FROM payment_configuration WHERE store_id = '0'";
				//$this->db->query($Qry21);
			}
		} # Close storeowner == admin
		
		if($storeowner == 'store') {
			$Qry3		=	"SELECT COUNT(*) AS TotCount FROM payment_methods_stores WHERE store_id = '$store_id'";
			$Result3 	=	$this->db->get_row($Qry3, ARRAY_A);
			$TotCount	=	$Result3['TotCount'];
			if($Action == 'Activate') {
				if($TotCount > 0)
					$Qry4	=	"UPDATE payment_methods_stores SET paymethod_id = '',credit_cards = '' WHERE store_id = '$store_id'"; 
				$this->db->query($Qry4);
				
				//$Qry41	=	"DELETE FROM payment_configuration WHERE store_id = '$store_id'";
				//$this->db->query($Qry41);
				
			} else if($Action == 'DeActivate') {
				$Qry5	=	"UPDATE payment_methods_stores SET paymethod_id = '',credit_cards = '' WHERE store_id = '$store_id'";
				$this->db->query($Qry5);
				
				//$Qry51	=	"DELETE FROM payment_configuration WHERE store_id = '$store_id'";
				//$this->db->query($Qry51);
			}
		}	
	}
	
	
	# The following method activates the payment method related with the main store or stores if they are not active
	/**
      * The following method activates the payment method related with the main store or stores if they are not active
      * Author   : 
      * Created  : 
      * Modified : 13/Nov/2007 By Adarsh
      */
	# The following method activates the payment method related with the main store or stores if they are not active
	function activatePaymentMethodOfStores($REQUEST)
	{
		extract($REQUEST);		

		$Qry1		=	"SELECT COUNT(*) AS TotCount FROM payment_methods_stores WHERE store_id = '$store_id'";
		$Result1 	=	$this->db->get_row($Qry1, ARRAY_A);
		$TotCount	=	$Result1['TotCount'];
		
		if($TotCount == 0) {
			$Qry2	=	"INSERT INTO payment_methods_stores (paymethod_id,store_id,credit_cards) VALUES ('$paymethod_id','$store_id','$CreditCards')";
			$this->db->query($Qry2);
		} else {
			$Qry3			=	"SELECT paymethod_id FROM payment_methods_stores WHERE store_id = '$store_id'";
			$Row3 			=	$this->db->get_row($Qry3, ARRAY_A);
			$paymethod		=	trim($Row3['paymethod_id']);
			$Qry4	=	"UPDATE payment_methods_stores SET paymethod_id = '$paymethod_id' WHERE store_id = '$store_id' AND credit_cards = ''";
			$this->db->query($Qry4);
			//$Qry5	=	"DELETE FROM payment_configuration WHERE store_id = '$store_id'";
			//$this->db->query($Qry5);
		}		
	}

	
	
	#The following method returns the payment method id corresponding to a particular store or admin
	function getPaymentMethod($StoreId, $PaymentReceiver)
	{
		if($PaymentReceiver == 'admin')
			$StoreId	=	'0';
		$Qry1			=	"SELECT paymethod_id FROM payment_methods_stores WHERE store_id = '$StoreId'";
		
		$Result1 		=	$this->db->get_row($Qry1, ARRAY_A);
		$paymethod_id	=	$Result1['paymethod_id'];
		return $paymethod_id;
	}

	# The following method returns the payment method detals corresponding to the payment method id
	function getPaymentMethodDetailsById($paymethod_id)
	{
		 $Qry		=	"SELECT * FROM payment_methods WHERE paymethod_id = '$paymethod_id'";
		$Result 	= 	$this->db->get_row($Qry, ARRAY_A);
		return $Result;
	}
	
	# The following method returns the configuration details associated with a store
	function getConfigurationDetailsOfStore($Storename)
	{
		$ConfArray	=	array();
		
		$StoreId	=	$this->getStoreIdFromStoreName($Storename);
		
		$Qry1		=	"SELECT payconfig_varname,payconfig_varvalue FROM payment_configuration WHERE store_id = '$StoreId'";
		$Result1 	= 	$this->db->get_results($Qry1);
		if($Result1) {
			foreach($Result1 as $Row)
				$ConfArray[$Row->payconfig_varname]		=	$Row->payconfig_varvalue;
		} 
		return $ConfArray;
	} # Close method definition
	
	
	# The following method checks whether the payment gateway details exists for a this store or not if exists return TRUE, else return FALSE
	function configurationExistsForStore($StoreName = '0')
	{
		if($this->config['payment_receiver'] == 'admin')
			$StoreName 	=	 '0';
		
		$StoreId		=	$this->getStoreIdFromStoreName($StoreName);
		
		$Qry1			=	"SELECT paymethod_id FROM payment_methods_stores WHERE store_id = '$StoreId'";
		$Row1 			=	$this->db->get_row($Qry1, ARRAY_A);
		$paymethod_id	=	trim($Row1['paymethod_id']);
		if($paymethod_id == 0 || $paymethod_id == '')
			return FALSE;
		
		$Qry2		=	"SELECT COUNT(*) AS ConfCount FROM payment_configuration WHERE store_id = '$StoreId'";
		$Row2 		=	$this->db->get_row($Qry2, ARRAY_A);
		$ConfCount	=	$Row2['ConfCount'];

		if($ConfCount == 0)
			return FALSE;
		else if($ConfCount > 0)
			return TRUE;

	} # Close method definition
	
	
	# The following method serves as a common interface for all the payment gateway implementation
	function processPaymentRequest($StoreName,$Params)
	{
		$ConfStatus		=	$this->configurationExistsForStore($StoreName);
		
		if($ConfStatus === FALSE)
			return FALSE;
		
		$StoreId	=	$this->getStoreIdFromStoreName($StoreName);
		
		if($this->config['payment_receiver'] == 'admin') {
			$StoreId 	=	 '0';
		} else {
			$Qry1	=	"SELECT CASE payment_receiver 
							WHEN 'S' THEN 'store' 
							WHEN 'A' THEN 'admin' 
							END AS PayReceiver 
						FROM store WHERE id = '$StoreId'";
			$Row1	= 	$this->db->get_row($Qry1, ARRAY_A);
			if($Row1['PayReceiver'] == 'admin')
				$StoreId 	=	 '0';
		} # Close else condition
		
		$Qry2	=	"SELECT 
						payment_methods_stores.paymethod_id, 
						payment_methods.class_name, 
						payment_methods.payapi_file 
					FROM payment_methods_stores 
					LEFT JOIN payment_methods ON payment_methods_stores.paymethod_id = payment_methods.paymethod_id 
					WHERE payment_methods_stores.store_id = '$StoreId'";
		$Row2			= 	$this->db->get_row($Qry2, ARRAY_A);
		
		$class_name		=	$Row2['class_name'];
		$payapi_file	=	$Row2['payapi_file'];
		
		if(file_exists(FRAMEWORK_PATH.''.$payapi_file))
			require_once FRAMEWORK_PATH.''.$payapi_file;
			
		$ActionObj		=	new $class_name($this);
		$MethodName		=	'process'.$class_name;
		
		$Result	=	$ActionObj->$MethodName($StoreName,$Params);
		
		return $Result;
	} #Close method definition
	
	
	# The following method returns the storeid associated with a particular store_name
	function getStoreIdFromStoreName($StoreName)
	{
		if(trim($StoreName) == '0')
			return 0;
		
		$Qry1		=	"SELECT id FROM store WHERE name = '$StoreName'";	
		$Row 		=	$this->db->get_row($Qry1, ARRAY_A);
		$StoreId	=	$Row['id'];
		return $StoreId;
	}
	
	# The following method returns the StoreName associated with a StoreId
	function getStoreNameFromStoreId($StoreId)
	{
		$Qry1		=	"SELECT name FROM store WHERE id = '$StoreId'";	
		$Row 		=	$this->db->get_row($Qry1, ARRAY_A);
		$StoreName	=	$Row['name'];
		return $StoreName;
	}
		
	
	########################### The following is the general functions used by the payment module ###############
	# The following method returns the extension of file entered. The input parameter of the file is the filename
	function getExtensionOfFile($FileName)
	{
		$ExtArr			=	explode('.',$FileName);
		$ExtArrCount	=	count($ExtArr);
		return $ExtArr[$ExtArrCount - 1];	
	}
	
	# The following method returns an array of store names and heading appended with the admin details
	function getStoreCombo()
	{
		$Stores		=	array();
		$Stores['id'][0]		=	'0';
		$Stores['name'][0]		=	'admin';
		
		if($this->config['payment_receiver'] == 'store') {
			# $Qry1		=	"SELECT id,name FROM store WHERE payment_receiver = 'S' ORDER BY id ASC ";
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
		} # Close if payment_receiver
		
		return $Stores;
	} # Close method definition
	
	
	# The following method returns an array of Credit card details with the information such as whether this card is
	# Active or not in that store.
	function getCreditCardDetailsOfStores($StoreId)
	{
		$CreditCards	=	array();
		
		$Qry1			=	"SELECT id,name,logo_extension FROM order_payment_type WHERE active = 'Y'";
		$Result1 		= 	$this->db->get_results($Qry1);
		$Qry2			=	"SELECT credit_cards FROM payment_methods_stores WHERE store_id = '$StoreId'";
		$Result2 		= 	$this->db->get_row($Qry2, ARRAY_A);
		$credit_cards	=	$Result2['credit_cards'];
		$CCArray		=	explode('^*^', $credit_cards);
		
		if($Result1) {
			$ArrIndx	=	0;
			foreach($Result1 as $Row1) {
				$CreditCards[$ArrIndx]['id']				=	$Row1->id;
				$CreditCards[$ArrIndx]['name']				=	$Row1->name;
				$CreditCards[$ArrIndx]['logo_extension']	=	$Row1->logo_extension;
				if(in_array($Row1->id,$CCArray))
					 $CreditCards[$ArrIndx]['active']	=	'Yes';
				else
					 $CreditCards[$ArrIndx]['active']	=	'No';
				$ArrIndx++;
			} # Close foreach
		} # Close if 
		return $CreditCards;
	} # Close function definition
	
	
	# The following method returns the selected or active credit card details associated with a store
	function getSelectedCreditCardDetailsOfStores($StoreId)
	{
		$CreditCards	=	array();
		
		$Qry1			=	"SELECT credit_cards, paymethod_id FROM payment_methods_stores WHERE store_id = '$StoreId'";
		$Row1			=	$this->db->get_row($Qry1, ARRAY_A);
		$credit_cards	=	trim($Row1['credit_cards']);
		$paymethod_id	=	trim($Row1['paymethod_id']);
		
		if($credit_cards == '' || $paymethod_id == 0)
			return $CreditCards;
		
		$CCArray		=	explode('^*^',$credit_cards);
		$ArrIndx	=	0;
		foreach($CCArray as $CC) {
			$Qry2	=	"SELECT * FROM order_payment_type WHERE id = '$CC'";
			$Row2	=	$this->db->get_row($Qry2, ARRAY_A);
			$CreditCards[$ArrIndx]	=	$Row2;
			$ArrIndx++;
		}
		return $CreditCards;
	}
	
	
	# The following method returns the payment method name. If there is no payment method selected return an 
	# empty string else the name of the payment method selected
	function getPaymentMethodNameFromPaymentMethodId($PayMethodId)
	{
		
		if($PayMethodId == 0 || $PayMethodId == '')
			return '';
		
		$Qry1	=	"SELECT paymethod_name FROM payment_methods WHERE paymethod_id = '$PayMethodId'";
		$Row1	=	$this->db->get_row($Qry1, ARRAY_A);
		$paymethod_name	=	$Row1['paymethod_name'];
		return $paymethod_name;
	}
	
	
	# The following method returns the credit cards from a particular store
	function getCreditCardsOfStore($StoreName)
	{
		$CreditCards	=	array();
		$StoreId		=	$this->getStoreIdFromStoreName($StoreName);
		$Qry1			=	"SELECT credit_cards FROM payment_methods_stores WHERE store_id = '$StoreId'";
		$Row1			=	$this->db->get_row($Qry1, ARRAY_A);
		$credit_cards	=	trim($Row1['credit_cards']);
		
		if($credit_cards == '')
			return $CreditCards;

		$CCArray		=	explode('^*^',$credit_cards);
		$ArrIndx	=	0;
		foreach($CCArray as $CC) {
			$Qry2		=	"SELECT name FROM order_payment_type WHERE id = '$CC'";
			$Row2		=	$this->db->get_row($Qry2, ARRAY_A);
			$CCName		=	$Row2['name'];
			$CreditCards['name'][$ArrIndx]	=	$CCName;
			$ArrIndx++;
		}
		return $CreditCards;
	}
	
	# The following method returns the Active Payment Gateway
	function getActivePaymentGateway($StoreName = '0')
	{
		$Row2		=	array();
		$StoreId	=	$this->getStoreIdFromStoreName($StoreName);
		
		$Qry1		=	"SELECT payment_methods_stores.paymethod_id, payment_methods.paymethod_name 
							FROM payment_methods_stores 
						LEFT JOIN payment_methods ON payment_methods_stores.paymethod_id = payment_methods.paymethod_id 
						WHERE payment_methods_stores.store_id = '$StoreId'";
		$Row2		=	$this->db->get_row($Qry1, ARRAY_A);	
		return trim($Row2['paymethod_name']); # Paypal Pro, Authorize.Net, LinkPoint Central
	}
	
	
	# The follwoing method returns the next order number generated as INVOICE number
	function getInvoiceNumber()
	{
		$Qry	=	"SELECT MAX(id)+1 AS InvoiceNumber FROM orders ";
		$Row	=	$this->db->get_row($Qry, ARRAY_A);				
		$InvoiceNumber	=	$Row['InvoiceNumber'];
		return $InvoiceNumber;
	}
	
	
	/**
	 * @description	The following method returns the user details for yourpay connect posting page
	 *
	 *
	 */
	function getUserDetailsForYourPayConnectForm($user_id)
	{
		$Qry1	=	"SELECT 
						T1.*, T2.*, T3.country_2_code 
					FROM member_master AS T1 
					LEFT JOIN member_address AS T2 ON T2.user_id = T1.id 
					LEFT JOIN country_master AS T3 ON T3.country_id = T2.country 
					WHERE T1.id = '$user_id'";
		$Row1	=	$this->db->get_row($Qry1, ARRAY_A);
		
		if($Row1['country_2_code'] == 'US') {
			$CountryCode	=	$Row1['country'];
			$State			=	$Row1['state'];	
			$Qry2	=	"SELECT code FROM state_code WHERE country_id = '$CountryCode' AND name = '$State'";
			$Row2	=	$this->db->get_row($Qry2, ARRAY_A);
			$Row1['state_name']		=	$Row2['code'];
		} else {
			$Row1['state_name']		=	$Row1['state'];
		}
		
		return $Row1;
	
	}
	
	
	/**
	 * @description	The following method is for decoding the session which are already encoded to adatabase
	 *
	 *
	 */
	function decodeSession($id) 
	{
		$Qry1		=	"select sess_value from order_session_value  where id='$id'";
		$Row1 		= 	$this->db->get_row($Qry1, ARRAY_A);
		$sess_str	=	$Row1['sess_value'];
		$sess_str	=	base64_decode($sess_str);
		session_decode($sess_str);
		
		$Qry2		=	"DELETE FROM order_session_value WHERE id = '$id'";
		$this->db->query($Qry2);
	}
	
	
	
	/**
	 * @description	The following method is for encoding the current session to database. This function returns an id 
	 *
	 *
	 */
	function encodeSession()
	{
		$Sess_str	=	session_encode();
		$Sess_str   = 	base64_encode($Sess_str);
		$arrayValue	=	array("sess_value"	=>	$Sess_str);
		$this->db->insert("order_session_value", $arrayValue);
		return $this->db->insert_id;
	}

	
	/**
	 * The following method cheks whether Credit Card information for a particular user exists or not
	 *
	 * @param int $MemberId
	 * @return boolean
	 */
	function whetherCreditCardInfoExists($MemberId)
	{
		$Qry		=	"SELECT COUNT(*) AS TotCount FROM member_paymentdetails WHERE memberid = '$MemberId'";
		$Row		=	$this->db->get_row($Qry, ARRAY_A);
		$TotCount	=	$Row['TotCount'];
		
		if($TotCount > 0)
			return TRUE;
		else 
			return FALSE;
	}
	
	
	/**
	 * The following method saves or updates the paypal information associated with a particular customer
	 *
	 * @param array $REQUEST
	 * @return boolean
	 */
	function savePaypalAccountInformation($REQUEST)
	{
		extract($REQUEST);
		$InfoExists		=	$this->whetherCreditCardInfoExists($_SESSION["memberid"]);
		
		if($InfoExists === TRUE) {
			$UpdArray	=	array('paypal_account' => $paypal_account);
			$this->db->update("member_paymentdetails", $UpdArray, "memberid='{$_SESSION["memberid"]}'");
		}
		
		if($InfoExists === FALSE) {
			$InsArray	=	array('paypal_account' => $paypal_account, 'memberid' => $_SESSION["memberid"]);
			$this->db->insert("member_paymentdetails", $InsArray);			
		}
						
		return TRUE;
	}
	
	
	/**
	 * The following method returns the paypal account information of a user
	 *
	 * @param int $MemberId
	 * @return array
	 */
	function getPaypalAccountInfoOfUser($MemberId)
	{
		$Qry	=	"SELECT * FROM member_paymentdetails WHERE memberid = '$MemberId'";
		$Row	=	$this->db->get_row($Qry, ARRAY_A);
		return $Row;
	}
	
	
	
	/**
	 * The following method saves the credit card information related with a particular account
	 *
	 * @param Array $REQUEST
	 * @return Boolean
	 */
	function saveCreditCardInformation($REQUEST)
	{
		extract($REQUEST);
		$this->saveBillingAddress($REQUEST, $_SESSION["memberid"]);
		$MemberId		=	$_SESSION["memberid"];
		$InfoExists		=	$this->whetherCreditCardInfoExists($_SESSION["memberid"]);
		
		if($InfoExists === TRUE) {
			$UpdArray	=	array('card_type' => $card_type, 'expire_Month' => $expire_Month, 'expire_Year' => $expire_Year, 'security_code' => $security_code, 'billing_email' => $billing_email);
			$this->db->update("member_paymentdetails", $UpdArray, "memberid='{$_SESSION["memberid"]}'");
			$RowAffected	=	$this->db->rows_affected;
		} else {
			$InsArray	=	array('memberid' => $_SESSION["memberid"], 'card_type' => $card_type, 'expire_Month' => $expire_Month, 'expire_Year' => $expire_Year, 'security_code' => $security_code, 'billing_email' => $billing_email);
			$this->db->insert("member_paymentdetails", $InsArray);
			
		}

		$Qry0				=	"SELECT commnamount_balnce FROM member_paymentdetails WHERE memberid = '$MemberId'";
		$Row0				=	$this->db->get_row($Qry0, ARRAY_A);
		$commnamount_balnce	=	$Row0['commnamount_balnce'];
		
		if($commnamount_balnce > 0)
			$TransType	=	'DIRECTPAY';
		else
			$TransType	=	'AUTHONLY';
		
		$PaymentMethod	=	$this->getActivePaymentGateway($store_name);
		if($PaymentMethod == 'Paypal Pro') {
			
			require_once FRAMEWORK_PATH."/includes/payment/paypal/class.webpaypalpro.php";
			$ConfInfo					=	$this->getConfigurationDetailsOfStore('0'); # Retrieves configuratrion details
			$APIInfo					=	array();
			$APIInfo['API_UserName']	=	$ConfInfo['api_username'];
			$APIInfo['API_Password']	=	$ConfInfo['api_password'];
			$APIInfo['API_Signature']	=	$ConfInfo['signature'];
			$WebsitePaypalProObj		=	new WebsitePaypalPro($APIInfo);

			
			$Params						 =		  array(); 
			$Params['creditCardType']  	 =        $card_type;  
			$Params['creditCardNumber']  =        $card_number;
			
			$Params['expDateMonth']      =        $expire_Month;
			$Params['expDateYear']       =        $expire_Year; 
			$Params['cvv2Number']        =        $security_code;

			$Params['firstName']         =        $fname;
			$Params['lastName']          =        $lname; 
			$Params['address1']          =        $address1; 
			$Params['address2']          =        $address2; 
			$Params['city']              =        $city;
			$Params['state']             =        $state; 
			$Params['zip']               =        $postalcode;
			$Params['email']             =        $billing_email;
			$Params['amount']      		 =        ($TransType == 'DIRECTPAY') ? $commnamount_balnce : .01;
			
			if($TransType == 'AUTHONLY') {
				$Results	=	$WebsitePaypalProObj->doAuthorizeAndVoidCreditCard($Params);
				$PayMsg		=	'Voided Authorization Transaction of .01$';
			} else if($TransType == 'DIRECTPAY') {
				$Results	=	$WebsitePaypalProObj->makeSaleTransactionPayment($Params);
				$PayMsg		=	"Transaction of {$commnamount_balnce}$ ";
			}	
			
			if($Results['Approved'] == 'Yes') {
				$TransactionId		=	$Results['TransactionId'];
				$Qry1	=	"UPDATE member_paymentdetails SET authorized = 'YES', transaction_id = '$TransactionId', commnamount_balnce = 0 WHERE memberid = '$MemberId'";
				$this->db->query($Qry1);
				
				$Qry2	=	"INSERT INTO member_transactiondetails SET 
							member_id = '$MemberId',
							transaction_date = NOW(),
							trans_description = '$PayMsg',
							trans_amount = '{$Params['amount']}',
							transaction_id = '$TransactionId',
							trans_show = 'Y'";
				$this->db->query($Qry2);
				
				setMessage($Results['Message'], MSG_SUCCESS);
				return TRUE;
			} else {
				$Qry3	=	"UPDATE member_paymentdetails SET authorized = 'NO'  WHERE memberid = '$MemberId'";
				$this->db->query($Qry3);
				setMessage($Results['Message']);
				return FALSE;
			}
		}
		
		return FALSE;
	}
	
	/**
	 * The following method returns the credit card information related with a particular member
	 *
	 * @param int $MemberId
	 * @return Associative array
	 */
	function getCreditCardInformation($MemberId)
	{
		$CCInfo	=	array();
		
		$Qry1	=	"SELECT * FROM member_address WHERE user_id = '$MemberId' AND addr_type = 'billing'";
		$Row1	=	$this->db->get_row($Qry1, ARRAY_A);
		
		$CCInfo['fname']		=	$Row1['fname'];
		$CCInfo['lname']		=	$Row1['lname'];
		$CCInfo['address1']		=	$Row1['address1'];
		$CCInfo['address2']		=	$Row1['address2'];
		$CCInfo['city']			=	$Row1['city'];
		$CCInfo['state']		=	$Row1['state'];
		$CCInfo['telephone']	=	$Row1['telephone'];
		$CCInfo['postalcode']	=	$Row1['postalcode'];
		
		$Qry2	=	"SELECT * FROM member_paymentdetails WHERE memberid = '$MemberId'";
		$Row1	=	$this->db->get_row($Qry2, ARRAY_A);
		
		$CCInfo['card_type']			=	$Row1['card_type'];
		$CCInfo['expire_Month']			=	$Row1['expire_Month'];
		$CCInfo['expire_Year']			=	$Row1['expire_Year'];
		$CCInfo['security_code']		=	$Row1['security_code'];
		$CCInfo['commnamount_balnce']	=	$Row1['commnamount_balnce'];
		$CCInfo['authorized']			=	$Row1['authorized'];
		$CCInfo['billing_email']		=	$Row1['billing_email'];

		$CCInfo['expire_date']		=	$CCInfo['expire_Year'].'-'.$CCInfo['expire_Month'].'-00';
					
		return $CCInfo;
				
	}
	
	/**
	 * The following method used for saving or updating the billing information
	 *
	 * @param Array $REQUEST
	 * @param int $MemberId
	 * @return boolean
	 */
	function saveBillingAddress($REQUEST, $MemberId)
	{
		extract($REQUEST);
		
		$Qry1		=	"SELECT COUNT(*) AS TotCount FROM member_address WHERE user_id = '$MemberId' AND addr_type = 'billing'";
		$Row1		=	$this->db->get_row($Qry1, ARRAY_A);
		$TotCount	=	$Row1['TotCount'];
		
		if($TotCount > 0) {
			$UpdArray	=	array('fname' => $fname, 'lname' => $lname, 'address1' => $address1, 'address2' => $address2, 'state' => $state, 'city' => $city, 'postalcode' => $postalcode,'telephone' => $telephone);
			$this->db->update("member_address", $UpdArray, "user_id = '$MemberId' AND addr_type = 'billing'");
		} else {
			$InsArray	=	array('user_id' => $MemberId,'addr_type' => 'billing','fname' => $fname, 'lname' => $lname, 'address1' => $address1, 'address2' => $address2, 'state' => $state, 'city' => $city, 'postalcode' => $postalcode,'telephone' => $telephone);
			$this->db->insert("member_address", $InsArray);	
		}
					
		return TRUE;
	}
	
	
	
	/**
	 * The following method stores the payflow link details associated with stores 
	 * "payflowlink_business_email" is the variable used for storing the payflow link business account
	 *
	 * @author vimson@newagesmb.com
	 * @param unknown_type $REQUEST
	 * @return unknown
	 */
	function saveStorePayflowLinkDetails($REQUEST)
	{
		extract($REQUEST);
		
		$Qry1		=	"SELECT COUNT(*) AS ConfCount FROM store_payment_details 
						WHERE store_id = '$store_id' AND config_var = 'payflowlink_business_email' ";
		$Row1		=	$this->db->get_row($Qry1, ARRAY_A);
		$ConfCount	=	$Row1['ConfCount']; # If ConfCount == 0 configuration exists
		
		if($ConfCount == 0) {
			$InsArray	=	array('store_id' => $store_id, 'config_var' => 'payflowlink_business_email', 'config_value' => $account_mailaddress);
			$this->db->insert("store_payment_details", $InsArray);
		}
		
		if($ConfCount > 0) {
			$UpdArray	=	array('config_value' => $account_mailaddress);
			$this->db->update("store_payment_details", $UpdArray, " store_id = '$store_id' AND config_var = 'payflowlink_business_email' ");
		}
		
		return TRUE;
	}
	
	
	/**
	 * The following method validates the form for payflow link for stores form
	 *
	 * @author vimson@newagesmb.com
	 * @param Associative array $REQUEST
	 * @return message
	 */
	function validateStorePayflowLinkForm($REQUEST)
	{
		extract($REQUEST);
		$Msg	=	'';
		
		if(trim($account_mailaddress) == '')
			$Msg	.=	'Mail Address required<br>';
		
		if(trim($account_mailaddress) != '') {
			if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $account_mailaddress)) {
				$Msg	.=	'Valid Mail Address required<br>';
			}
		}
		
		return $Msg;
	}
	
	
	
	/**
	 * The following method returns the payflow link account details asso
	 *
	 * @param int $store_id
	 * @return array
	 */
	function getPayflowLinkStoreAccountDetails($store_id)
	{
		$AccountDetails	=	array();
		$Qry1			=	"SELECT config_value FROM store_payment_details WHERE store_id = '$store_id' AND config_var = 'payflowlink_business_email'";
		$Row1			=	$this->db->get_row($Qry1, ARRAY_A);
		
		$AccountDetails['account_mailaddress']	=	$Row1['config_value'];
		
		return $AccountDetails;
	}
	
	/**
	 * The following method returns the payflow link business account details associated with a storename
	 *
	 * @author vimson@newagesmb.com
	 * @param string $storename
	 * @return string containing an email address of the merchant account
	 */
	function getPayflowLinkDetailsFromStoreName($storename = '0')
	{
		
		if($storename == '0') {
			$store_id	=	0;
		} else {
			$Qry1		=	"SELECT id FROM store WHERE name = '$storename' ";
			$Row1		=	$this->db->get_row($Qry1, ARRAY_A);
			$store_id	=	$Row1['id'];
		}
		
		
		
		$Qry2				=	"SELECT config_value FROM store_payment_details WHERE store_id = '$store_id' AND config_var = 'payflowlink_business_email'";
		$Row2				=	$this->db->get_row($Qry2, ARRAY_A);
		$businessaccount	=	$Row2['config_value'];
		
		return $businessaccount;
	}
	
	
	/**
	 * Enter description here...
	 *
	 * @param int $pageNo
	 * @param int $limit
	 * @param Array $params
	 * @param Constant $output
	 * @param query $orderBy
	 * 
	 * @return Associative Array
	 */
	function getTransactionHistory($MemberId, $pageNo, $limit, $params, $output = ARRAY_A, $orderBy = ' id:DESC ')
	{
		#DATE_FORMAT(transaction_date,'".$this->config['date_format']."')	
				
		/*$Qry	=	"SELECT id, member_id, transaction_date, trans_description, trans_amount, transaction_id, trans_show 
					 FROM member_transactiondetails WHERE member_id = '$MemberId'";*/
		$Qry	=	"SELECT 
						T1.id AS id,
						T1.member_id AS member_id,
						DATE(T1.transaction_date) AS transaction_date,
						T1.trans_description AS trans_description,
						T1.trans_amount AS trans_amount,
						T1.transaction_id AS transaction_id,
						T1.trans_show AS trans_show,
						CASE T1.sentto_id
							WHEN 0 THEN 'admin'
							ELSE T2.username 
						END AS senttousername 
					FROM member_transactiondetails AS T1 
					LEFT JOIN member_master AS T2 ON T2.id = T1.sentto_id
					WHERE member_id = '$MemberId' ";
		$rs 	= 	$this->db->get_results_pagewise($Qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
	
	/**
	 * The following method returns whether the credit card is verified or not
	 * 
	 *
	 * @param Int $MemberId
	 * @return boolean
	 * 
	 */
	function whetherCreditCardVarifiedOrNot($MemberId)
	{
		$Qry			=	"SELECT authorized FROM member_paymentdetails WHERE memberid = '$MemberId'";
		$Row			=	$this->db->get_row($Qry, ARRAY_A);
		$authorized		=	$Row['authorized'];
				
		if($authorized == 'YES')
			return TRUE;
		else
			return FALSE;
	}
	
	
	
	
	/**
	 * The following method returns the commision pay status after payment gateway operation
	 *
	 * @param int $Amount
	 * @param int $MemberId
	 * @param String $WithdrawMessage
	 * @return Array
	 */
	function withdrawCommisionAmount($Amount, $MemberId, $WithdrawMessage = 'Commision Amount')
	{
		$Result		=	array();
		$Params		=	array();
		
		
		$PaymentMethod	=	$this->getActivePaymentGateway('0');
		if($PaymentMethod == 'Paypal Pro') {
			
			require_once FRAMEWORK_PATH."/includes/payment/paypal/class.webpaypalpro.php";
			$ConfInfo					=	$this->getConfigurationDetailsOfStore('0'); # Retrieves configuratrion details
			$APIInfo					=	array();
			$APIInfo['API_UserName']	=	$ConfInfo['api_username'];
			$APIInfo['API_Password']	=	$ConfInfo['api_password'];
			$APIInfo['API_Signature']	=	$ConfInfo['signature'];
			$WebsitePaypalProObj		=	new WebsitePaypalPro($APIInfo);
			
			$Qry1				=	"SELECT authorized,transaction_id FROM member_paymentdetails WHERE memberid = '$MemberId'";
			$Row1				=	$this->db->get_row($Qry1, ARRAY_A);
			$authorized			=	$Row1['authorized'];
			$transaction_id		=	$Row1['transaction_id'];
			
			if($authorized == 'YES') { 	#DoReference Transaction
				$Result['Authorized']			=	'YES';
				$Params['TransactionId']		=	$transaction_id;
				$Params['TransactionAmount']	=	$Amount;
				$PayResult	=	$WebsitePaypalProObj->doReferenceTransaction($Params);
				$Result		=	array_merge($Result, $PayResult);
								
				if($PayResult['Approved'] == 'Yes') {
					$Qry2		=	"UPDATE member_paymentdetails SET transaction_id = '{$PayResult['TransactionId']}', commnamount_balnce = commnamount_balnce - $Amount WHERE memberid = '$MemberId'";
					
					$Qry3	=	"INSERT INTO member_transactiondetails SET 
								member_id = '$MemberId',
								transaction_date = NOW(),
								trans_description = '$WithdrawMessage',
								trans_amount = '$Amount',
								transaction_id = '{$PayResult['TransactionId']}',
								trans_show = 'Y'";
					$this->db->query($Qry3);
					
				} else {
					$Qry2		=	"UPDATE member_paymentdetails SET authorized = 'NO' WHERE memberid = '$MemberId'";
				}	
				
				$this->db->query($Qry2);
			}
			
			if($authorized == 'NO') {
				$Result['Authorized']	=	'NO';
				$Result['Approved']		=	'No';
			}
			
		} else {
			$Result['Approved']		=	'No';
			$Result['Message']		=	'Payment Gateway Not Configured Successfully';
		}
		
		return $Result;
		
	}
	
	
	/**
	 * The following method returns the paypal information associated with a property
	 *
	 * @author vimson@newagesmb.com
	 * @param Int $PropertyId
	 * @return Array 	['Exists'] = 'Yes/No'	['BusinessAccount']	=	'Paypal Business Email Account'
	 * 
	 */
	function getPaypalInformationOfProperty($PropertyId)
	{
		$PaypalInfo		=	array();
		
		$Qry	=	"SELECT 
						T2.paypal_account AS paypal_account 
					FROM flyer_data_basic AS T1 
					LEFT JOIN member_paymentdetails AS T2 ON T2.memberid = T1.user_id 
					WHERE T1.album_id = '$PropertyId' ";
		$Row	=	$this->db->get_row($Qry, ARRAY_A);

		if(trim($Row['paypal_account']) != '')
			$PaypalInfo['Exists']	=	'Yes';
		else
			$PaypalInfo['Exists']	=	'No';
			
		$PaypalInfo['BusinessAccount']	=	$Row['paypal_account'];

		return $PaypalInfo;
	}
	
	
	/**
	 * The following method returns the Invoice list
	 *
	 * @param Int $pageNo
	 * @param Int $limit
	 * @param String $Params
	 * @param Constant $output
	 * @param String $orderBy
	 * @param Int $MemberId
	 * @return Array
	 */
	function getInvoiceList($pageNo, $limit, $Params, $output, $orderBy, $MemberId)
	{
		$Qry			=	"SELECT 
								T1.invoice_id AS invoice_id,
								T1.sentby_id AS sentby_id,
								T1.invoice_number AS invoice_number,
								DATE(T1.created_time) AS created_time,
								T1.invoice_amount AS invoice_amount,
								T1.invoice_description AS invoice_description,
								T1.invoice_status AS invoice_status,
								T1.invoice_type AS invoice_type,
								T2.username AS SenderUsername,
								T3.paypal_account AS paypal_account 
							FROM invoices AS T1 
							LEFT JOIN member_master AS T2 ON T2.id = T1.sentby_id 
							LEFT JOIN member_paymentdetails AS T3 ON T3.memberid = T1.sentby_id 
							WHERE T1.sentto_id = '$MemberId' AND T1.deleted = 0 ";
		$InvoiceList	=	$this->db->get_results_pagewise($Qry, $pageNo, $limit, $Params, $output, $orderBy);
	
		return $InvoiceList;
	}
	
	
	
	/**
	 * The following method returns the Senders List of invoices to a particular member
	 *
	 * @param Int $pageNo
	 * @param Int $limit
	 * @param String $Params
	 * @param Constant $output
	 * @param String $orderBy
	 * @param Int $MemberId
	 * @return Array containing Member List
	 */
	function getMyInvoiceReceivers($pageNo, $limit, $Params, $output, $orderBy, $MemberId)
	{
		$Qry	=	"SELECT 
						COUNT(*) AS InvoiceCount,
						T1.sentto_id AS sentto_id, 
						T2.username AS SentToUsername,
						T2.first_name AS SentToFirstName,
						T2.last_name AS SentToLastName 
					FROM invoices AS T1 
					LEFT JOIN member_master AS T2 ON T2.id = T1.sentto_id 
					WHERE T1.sentby_id = '$MemberId' 
					GROUP BY T1.sentto_id ";
		$InvoiceReceiversList	=	$this->db->get_results_pagewise($Qry, $pageNo, $limit, $Params, $output, $orderBy);
				
		return $InvoiceReceiversList;
		
	}
	
	
	
	/**
	 * The following method returns the Invoices sent by a particular member/Broker to a seller
	 *
	 * @author vimson@newagesmb.com
	 * 
	 * @param Int $pageNo
	 * @param Int $limit
	 * @param Int $Params
	 * @param Int $output
	 * @param Int $orderBy
	 * @param Int $MemberId
	 * @param Int $SentToId
	 * @return Array
	 */
	function getMyEarningInvoices($pageNo, $limit, $Params, $output, $orderBy, $MemberId, $SentToId)
	{
		
		$Qry	=	"SELECT 
						T1.invoice_number AS invoice_number,
						DATE(T1.created_time) AS created_time,
						T1.invoice_amount AS invoice_amount,
						T1.invoice_description AS invoice_description,
						T1.invoice_type AS invoice_type,
						T1.invoice_status AS invoice_status
					FROM invoices AS T1 
					WHERE T1.sentby_id = '$MemberId' AND T1.sentto_id = '$SentToId' 
					AND T1.deleted = 0 ";
			
		$InvoiceList	=	$this->db->get_results_pagewise($Qry, $pageNo, $limit, $Params, $output, $orderBy);
				
		return $InvoiceList;
		
	}
	
	
	
	
	/**
	 * This method used for invoice payments
	 *
	 * @param Array $POST
	 * @param Int $InvoiceId
	 * @param Int $SentToId
	 * @param Int $MemberId
	 * @return Boolean
	 */
	function makeInvoicePayments($POST, $InvoiceId, $SentToId, $MemberId)
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
		#$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
		$fp = fsockopen ($this->config['paypal_socket_address'], 80, $errno, $errstr, 30);

		$item_name 			=	$POST['item_name'];
		$item_number 		= 	$POST['item_number'];
		$payment_status 	= 	$POST['payment_status'];
		$payment_amount 	= 	$POST['mc_gross'];
		$payment_currency 	= 	$POST['mc_currency'];
		$txn_id 			= 	$POST['txn_id'];
		$receiver_email 	= 	$POST['receiver_email'];
		$payer_email 		= 	$POST['payer_email'];
		
		if (!$fp) {
			;
		} else {
			fputs ($fp, $header . $req);
			while (!feof($fp)) {
				$res	= 	fgets ($fp, 1024);
				if (strcmp ($res, "VERIFIED") == 0) {
					$fpp = fopen($LogFileName, "a+");
					fwrite($fpp, $_SESSION['memberid']."VERIFIED:payment_status:".$payment_status."|"."txn_id:".$txn_id."|"."payment_amount:".$payment_amount."|".$item_name." - ".date("d-m-Y H:i:s")."\n".$req."\n");
					fclose($fpp);
					
					if ($payment_status == 'Completed') {
						$Qry1	=	"UPDATE invoices SET invoice_status = 'PAID' WHERE invoice_id = '$InvoiceId'";
						$this->db->query($Qry1);
						
						$DateTime	=	date('Y-m-d H:i:s');
						$TransDescr	=	'Payment Of '.$item_name;
						
						$TransInsArray	=	array(
												'member_id'			=>	$MemberId,
												'sentto_id'			=>	$SentToId,
												'transaction_date'	=>	$DateTime,
												'trans_description'	=>	$TransDescr,
												'trans_amount'		=>	$payment_amount,
												'transaction_id'	=>	$txn_id
											);
						$this->db->insert('member_transactiondetails', $TransInsArray);
					}
					
				} else if (strcmp ($res, "INVALID") == 0) {
					$fpp = fopen($LogFileName, "a+");
					$PostStr	=	var_export($POST, true)."\n".date('Y-m-d H:i:s')."\n".$res."\n";
					fwrite($fpp, $PostStr);
					fclose($fpp);
				}
			}
			fclose ($fp);
		}
		
		return TRUE;
	}
	

	
	
	
	
	
	

} 

?>