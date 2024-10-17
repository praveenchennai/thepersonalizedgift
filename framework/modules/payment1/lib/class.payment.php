<?php
/*
	This class abstracts the payment operation.
*/

class Payment extends FrameWork {
	
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
		extract($REQUEST);
		if($storeowner == 'admin') {
			if($Action == 'DeActivate') {
				$Qry1	=	"UPDATE payment_methods_stores SET paymethod_id = '0' WHERE store_name = '0'";
				$this->db->query($Qry1);
				
				$Qry11	=	"DELETE FROM payment_configuration WHERE store_name = '0'";
				$this->db->query($Qry11);
				
			} else if($Action == 'Activate') {
				$Qry2	=	"UPDATE payment_methods_stores SET paymethod_id = '$paymethod_id' WHERE store_name = '0'";
				$this->db->query($Qry2);
				
				$Qry21	=	"DELETE FROM payment_configuration WHERE store_name = '0'";
				$this->db->query($Qry21);
			}
		} # Close storeowner == admin
		
		if($storeowner == 'store') {		
			$Qry3		=	"SELECT COUNT(*) AS TotCount FROM payment_methods_stores WHERE store_name = '$storename'";
			$Result3 	=	$this->db->get_row($Qry3, ARRAY_A);
			$TotCount	=	$Result3['TotCount'];
			if($Action == 'Activate') {
				if($TotCount > 0)
					$Qry4	=	"UPDATE payment_methods_stores SET paymethod_id = '$paymethod_id' WHERE store_name = '$storename'"; 
				else
					$Qry4	=	"INSERT INTO payment_methods_stores (paymethod_id,store_name) VALUES ('$paymethod_id','$storename')";				
				$this->db->query($Qry4);
				
				$Qry41	=	"DELETE FROM payment_configuration WHERE store_name = '$storename'";
				$this->db->query($Qry41);
				
			} else if($Action == 'DeActivate') {
				$Qry5	=	"UPDATE payment_methods_stores SET paymethod_id = '' WHERE store_name = '$storename'";
				$this->db->query($Qry5);
				
				$Qry51	=	"DELETE FROM payment_configuration WHERE store_name = '$storename'";
				$this->db->query($Qry51);
			}
		} # Close storeowner == store
		
	}
	
	
	#The following method returns the payment method id corresponding to a particular store or admin
	function getPaymentMethod($StoreName, $PaymentReceiver)
	{
		if($PaymentReceiver == 'admin')
			$StoreName	=	'0';
		$Qry1			=	"SELECT paymethod_id FROM payment_methods_stores WHERE store_name = '$StoreName'";
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
	
	
	
	
	
	
	####### Paypal Pro section #############################################################################
		# a. api_username
		# b. api_password
		# c. cert_file_path
		# d. signature

	# The following method returns the payment method details associated with paypal pro
	function getPaypalPro($StoreName, $PaymentReceiver)
	{
		$ConfDetails	=	array();
		
		if($PaymentReceiver == 'admin')
			$StoreName	=	'0';
			
		$Qry1		=	"SELECT COUNT(*) AS TotCount FROM payment_configuration WHERE store_name='$StoreName' AND (payconfig_varname = 'api_username' OR 
						payconfig_varname = 'api_password' OR payconfig_varname = 'cert_file_path' OR payconfig_varname = 'signature')";
		$Result1 	= 	$this->db->get_row($Qry1, ARRAY_A);
		$TotCount	=	$Result1['TotCount'];
		if($TotCount == 0)
			return $ConfDetails;
		
		$Qry2		=	"SELECT payconfig_varname,payconfig_varvalue FROM payment_configuration WHERE store_name='$StoreName' AND (payconfig_varname = 'api_username' OR 
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
		
		if($storeowner == 'admin')
			$store_name		=	'0';
		
		$Qry1		=	"SELECT COUNT(*) AS TotCount FROM payment_configuration WHERE store_name='$store_name' AND (payconfig_varname = 'api_username' OR 
						payconfig_varname = 'api_password' OR payconfig_varname = 'cert_file_path' OR payconfig_varname = 'signature')";
		$Result1 	= 	$this->db->get_row($Qry1, ARRAY_A);
		$TotCount	=	$Result1['TotCount'];
		
		if($TotCount == 0) {
			$InsArray1	=	array("store_name" => $store_name, "payconfig_varname" => "api_username", "payconfig_varvalue" => $api_username);
			$InsArray2	=	array("store_name" => $store_name, "payconfig_varname" => "api_password", "payconfig_varvalue" => $api_password);
			$InsArray3	=	array("store_name" => $store_name, "payconfig_varname" => "signature", "payconfig_varvalue" => $signature);
			$InsArray4	=	array("store_name" => $store_name, "payconfig_varname" => "cert_file_path", "payconfig_varvalue" => '');
			
			$this->db->insert("payment_configuration", $InsArray1);
			$this->db->insert("payment_configuration", $InsArray2);
			$this->db->insert("payment_configuration", $InsArray3);
			$this->db->insert("payment_configuration", $InsArray4);
			$ConfigId		=	$this->db->insert_id;	
			
			if($FILES['cert_file_path']['size'] > 0) {
				$CertFileName	=	'cert_'.$ConfigId.'.'.$this->getExtensionOfFile($FILES['cert_file_path']['name']);
				copy($FILES['cert_file_path']['tmp_name'], FRAMEWORK_PATH.'/modules/payment/certificatefiles/'.$CertFileName);
				
				$Qry2	=	"UPDATE payment_configuration SET payconfig_varvalue = '$CertFileName' WHERE payconfig_id = '$ConfigId'";
				$this->db->query($Qry2);
				
				$Qry21	=	"UPDATE payment_configuration SET payconfig_varvalue = '' WHERE store_name = '$store_name' AND payconfig_varname = 'signature'";
				$this->db->query($Qry21);
				
			}	
		} else {
			$Qry3		=	"UPDATE payment_configuration SET payconfig_varvalue = '$api_username' WHERE payconfig_varname = 'api_username' AND store_name = '$store_name'";
			$this->db->query($Qry3);
			$Qry4		=	"UPDATE payment_configuration SET payconfig_varvalue = '$api_password' WHERE payconfig_varname = 'api_password' AND store_name = '$store_name'";
			$this->db->query($Qry4);
			
			if(trim($signature)) {
				$Qry5		=	"UPDATE payment_configuration SET payconfig_varvalue = '$signature' WHERE payconfig_varname = 'signature' AND store_name = '$store_name'";
				$this->db->query($Qry5);
				
				$Qry51		=	"UPDATE payment_configuration SET payconfig_varvalue = '' WHERE store_name = '$store_name' AND payconfig_varname = 'cert_file_path'";
				$this->db->query($Qry51);
			}
			
			if($FILES['cert_file_path']['size'] > 0) {
				$Qry6			=	"SELECT payconfig_id FROM payment_configuration WHERE payconfig_varname = 'cert_file_path' AND store_name = '$store_name'";
				$Result6 		= 	$this->db->get_row($Qry6, ARRAY_A);
				$payconfig_id	=	$Result6['payconfig_id'];
				$CertFileName	=	'cert_'.$payconfig_id.'.'.$this->getExtensionOfFile($FILES['cert_file_path']['name']);
				if(file_exists(FRAMEWORK_PATH.'/modules/payment/certificatefiles/'.$CertFileName))
					unlink(FRAMEWORK_PATH.'/modules/payment/certificatefiles/'.$CertFileName);
				copy($FILES['cert_file_path']['tmp_name'], FRAMEWORK_PATH.'/modules/payment/certificatefiles/'.$CertFileName);	
				$Qry6		=	"UPDATE payment_configuration SET payconfig_varvalue = '$CertFileName' WHERE payconfig_id = $payconfig_id";
				$this->db->query($Qry6);
				
				$Qry61	=	"UPDATE payment_configuration SET payconfig_varvalue = '' WHERE store_name = '$store_name' AND payconfig_varname = 'signature'";
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
		if($StatusMsg == '')
			return TRUE;
		else
			return $StatusMsg;	
	}
	
	
	
	
		
	####### Paypal Standard section #############################################################################
		# a. business 	-- 	PayPal account of the seller
		# b. paypal_url -- URL redirect to the paypal site
	function getPaypalStandard($StoreName, $PaymentReceiver)
	{
		$ConfDetails	=	array();
		
		if($PaymentReceiver == 'admin')
			$StoreName	=	'0';
		
		$Qry1		=	"SELECT COUNT(*) AS TotCount FROM payment_configuration WHERE store_name = '$StoreName' AND 
						(payconfig_varname = 'paypal_url' OR payconfig_varname = 'business')";
		$Result1 	= 	$this->db->get_row($Qry1, ARRAY_A);
		$TotCount	=	$Result1['TotCount'];
		if($TotCount == 0)
			return $ConfDetails;
		
		$Qry2		=	"SELECT payconfig_varname,payconfig_varvalue FROM payment_configuration WHERE store_name = '$StoreName' AND 
						(payconfig_varname = 'paypal_url' OR payconfig_varname = 'business')";
		$Result2	=	$this->db->get_results($Qry2);	
		foreach($Result2 as $Row)
			$ConfDetails[$Row->payconfig_varname]	=	$Row->payconfig_varvalue;
		return $ConfDetails;	
	}
	
	function setPaypalStandard($REQUEST, $FILES)
	{
		extract($REQUEST);
		$Qry1		=	"SELECT COUNT(*) AS TotCount FROM payment_configuration WHERE store_name = '$store_name' AND 
						(payconfig_varname = 'paypal_url' OR payconfig_varname = 'business')";
		$Result1 	= 	$this->db->get_row($Qry1, ARRAY_A);
		$TotCount	=	$Result1['TotCount'];
		if($TotCount == 0) {
			$InsArray1	=	array("store_name" => $store_name, "payconfig_varname" => "paypal_url", "payconfig_varvalue" => $paypal_url);
			$InsArray2	=	array("store_name" => $store_name, "payconfig_varname" => "business", "payconfig_varvalue" => $business);
			$this->db->insert("payment_configuration", $InsArray1);
			$this->db->insert("payment_configuration", $InsArray2);
		} else {
			$Qry2	=	"UPDATE payment_configuration SET payconfig_varvalue = '$paypal_url' WHERE store_name = '$store_name' AND payconfig_varname = 'paypal_url'";
			$this->db->query($Qry2);
			
			$Qry3	=	"UPDATE payment_configuration SET payconfig_varvalue = '$business' WHERE store_name = '$store_name' AND payconfig_varname = 'business'";
			$this->db->query($Qry3);
		}
	}
	
	# The following method validates the paypal standard form
	function validatePaypalStandard($REQUEST, $FILES)
	{
		extract($REQUEST);
		$StatusMsg	=	'';
		
		if(trim($paypal_url) == '')
			$StatusMsg	.=	'Paypal URL required.<br>';
		if(trim($business) == '')
			$StatusMsg	.=	'PayPal Account of the seller required.';	
		if($StatusMsg == '')
			return TRUE;
		else
			return $StatusMsg;
	}	
		
		
	
	
	
	
	
	
	####### Authorize.NET section #############################################################################
		# a. auth_net_login_id
		# b. auth_net_tran_key
	
	function getAuthorizeNet($StoreName, $PaymentReceiver)
	{
		$ConfDetails	=	array();
		
		if($PaymentReceiver == 'admin')
			$StoreName	=	'0';
		
		$Qry1		=	"SELECT COUNT(*) AS TotCount FROM payment_configuration WHERE store_name = '$StoreName' AND 
						(payconfig_varname = 'auth_net_login_id' OR payconfig_varname = 'auth_net_tran_key')";
		$Result1 	= 	$this->db->get_row($Qry1, ARRAY_A);
		$TotCount	=	$Result1['TotCount'];
		if($TotCount == 0)
			return $ConfDetails;	

		$Qry2		=	"SELECT payconfig_varname,payconfig_varvalue FROM payment_configuration WHERE store_name = '$StoreName' AND 
						(payconfig_varname = 'auth_net_login_id' OR payconfig_varname = 'auth_net_tran_key')";
		$Result2	=	$this->db->get_results($Qry2);	
		foreach($Result2 as $Row)
			$ConfDetails[$Row->payconfig_varname]	=	$Row->payconfig_varvalue;
		return $ConfDetails;	
	}
	
	function setAuthorizeNet($REQUEST, $FILES)
	{
		extract($REQUEST);
		$Qry1		=	"SELECT COUNT(*) AS TotCount FROM payment_configuration WHERE store_name = '$store_name' AND 
						(payconfig_varname = 'auth_net_login_id' OR payconfig_varname = 'auth_net_tran_key')";
		$Result1 	= 	$this->db->get_row($Qry1, ARRAY_A);
		$TotCount	=	$Result1['TotCount'];
		if($TotCount == 0) {
			$InsArray1	=	array("store_name" => $store_name, "payconfig_varname" => "auth_net_login_id", "payconfig_varvalue" => $auth_net_login_id);
			$InsArray2	=	array("store_name" => $store_name, "payconfig_varname" => "auth_net_tran_key", "payconfig_varvalue" => $auth_net_tran_key);
			$this->db->insert("payment_configuration", $InsArray1);
			$this->db->insert("payment_configuration", $InsArray2);
		} else {
			$Qry2	=	"UPDATE payment_configuration SET payconfig_varvalue = '$auth_net_login_id' WHERE store_name = '$store_name' AND payconfig_varname = 'auth_net_login_id'";
			$this->db->query($Qry2);
			
			$Qry3	=	"UPDATE payment_configuration SET payconfig_varvalue = '$auth_net_tran_key' WHERE store_name = '$store_name' AND payconfig_varname = 'auth_net_tran_key'";
			$this->db->query($Qry3);
		}
	}
	
	function validateAuthorizeNet($REQUEST, $FILES)
	{
		extract($REQUEST);
		$StatusMsg	=	'';
		
		if(trim($auth_net_login_id) == '') 
			$StatusMsg	.=	'Merchant Login ID required<br>';
		if(trim($auth_net_tran_key) == '') 
			$StatusMsg	.=	'Merchant Transaction Key required';
		
		if($StatusMsg == '')
			return TRUE;
		else
			return $StatusMsg;		
	}
	
	
	
	
	
	
	####### Linkpoint Central section #############################################################################
		# a. configfile 	-- merchant store number (or storename) as specified in the merchant's Welcome e-mail
		# b. keyfile		-- the location and file name of the digital certificate, which should be saved on your Web server with a .pem extension
	
	function getLinkpointCentral($StoreName, $PaymentReceiver)
	{
		$ConfDetails	=	array();
		
		if($PaymentReceiver == 'admin')
			$StoreName	=	'0';
		
		$Qry1		=	"SELECT COUNT(*) AS TotCount FROM payment_configuration WHERE 
						store_name = '$StoreName' AND (payconfig_varname = 'configfile' OR payconfig_varname = 'keyfile')";
		$Result1 	= 	$this->db->get_row($Qry1, ARRAY_A);
		$TotCount	=	$Result1['TotCount'];
		if($TotCount == 0)
			return $ConfDetails;
		
		$Qry2		=	"SELECT payconfig_varname,payconfig_varvalue FROM payment_configuration WHERE 
						store_name = '$StoreName' AND (payconfig_varname = 'configfile' OR payconfig_varname = 'keyfile')";
		$Result2	=	$this->db->get_results($Qry2);	
		foreach($Result2 as $Row)
			$ConfDetails[$Row->payconfig_varname]	=	$Row->payconfig_varvalue;
		return $ConfDetails;		
	}
	
	function setLinkpointCentral($REQUEST, $FILES)
	{
		extract($REQUEST);
		$Qry1		=	"SELECT COUNT(*) AS TotCount FROM payment_configuration WHERE store_name = '$store_name' AND 
						(payconfig_varname = 'configfile' OR payconfig_varname = 'keyfile')";
		$Result1 	= 	$this->db->get_row($Qry1, ARRAY_A);
		$TotCount	=	$Result1['TotCount'];
		if($TotCount == 0) {
			$InsArray1	=	array("store_name" => $store_name, "payconfig_varname" => "configfile", "payconfig_varvalue" => $configfile);
			$this->db->insert("payment_configuration", $InsArray1);
			
			$InsArray2	=	array("store_name" => $store_name, "payconfig_varname" => "keyfile", "payconfig_varvalue" => "");
			$this->db->insert("payment_configuration", $InsArray2);
			$ConfId		=	$this->db->insert_id;
			$File		=	"KeyFile_".$ConfId.'.'.$this->getExtensionOfFile($FILES['keyfile']['name']);
			copy($FILES['keyfile']['tmp_name'], FRAMEWORK_PATH.'/modules/payment/certificatefiles/'.$File);
			
			$Qry2	=	"UPDATE payment_configuration SET payconfig_varvalue = '$File' WHERE 
						store_name = '$store_name' AND payconfig_varname = 'keyfile'";
			$this->db->query($Qry2);			
		} else {
			$Qry3		=	"UPDATE payment_configuration SET payconfig_varvalue = '$configfile' WHERE 
							store_name = '$store_name' AND payconfig_varname = 'configfile'";
			$this->db->query($Qry3);

			if($FILES['keyfile']['size'] > 0) {
				$Qry4			=	"SELECT payconfig_id FROM payment_configuration WHERE payconfig_varname = 'keyfile' AND store_name = '$store_name'";
				$Result4 		= 	$this->db->get_row($Qry4, ARRAY_A);
				$payconfig_id	=	$Result4['payconfig_id'];
				$File		=	"KeyFile_".$payconfig_id.'.'.$this->getExtensionOfFile($FILES['keyfile']['name']);
				copy($FILES['keyfile']['tmp_name'], FRAMEWORK_PATH.'/modules/payment/certificatefiles/'.$File);
				$Qry5	=	"UPDATE payment_configuration SET payconfig_varvalue = '$File' WHERE 
							store_name = '$store_name' AND payconfig_varname = 'keyfile'";
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

		if($StatusMsg == '')
			return TRUE;
		else
			return $StatusMsg;
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
		$Stores['name'][0]		=	'0';
		$Stores['heading'][0]	=	'admin';
		
		$Qry1		=	"SELECT name,heading FROM store WHERE payment_receiver = 'S' ORDER BY id ASC ";
		$Result1 	= 	$this->db->get_results($Qry1);
		if($Result1) {
			$ArrIndx	=	1;
			foreach($Result1 as $Row1) {
				$Stores['name'][$ArrIndx]		=	$Row1->name;
				$Stores['heading'][$ArrIndx]	=	$Row1->heading;
				$ArrIndx++;
			}
		}
		return $Stores;
	} # Close method definition


} # Close class definition
?>