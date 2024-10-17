<?php

/**
 * This file can be used to handle paypal secure payments.
 * The certificate file is expected to present in the same directory.
 * 
 * :- Aneesh <aneesh.aravindan@newagesmb.com>
 * 
*/

//include_once("../../../../theunionshop/config.php"); 
//include_once(FRAMEWORK_PATH."/class.framework.php");
//$framework = new FrameWork();
//include_once(FRAMEWORK_PATH."/functions.php");


/* 		*** 	From Database	 ***		 */
//$getConfig     =   getConfigPayment (0,2); 
//print_r ($getConfig);
//define("LOGINID", '2n4XG4e2XPfn');
//define("TRANKEY", '2n94Pc3EHbGr4n9g');
define("LOGINID", '-not set-');
define("TRANKEY", '-not set-');
/* 		*** 	From Database	 ***		 */


function processPayment ($param) {
		$x_amount			=	$param["amount"];
		$x_card_num			=	$param["creditCard"];
		$x_exp_month		=	$param["expMonth"];
		$x_exp_year			=	$param["expYear"];
		$x_exp_date			=	$x_exp_month."".$x_exp_year;
		$cvvCode			=	$param["cvc"];
		$duration  			=	$param['duration'];
		$id					=   $param['id'];
			
		$DEBUGGING			= 	1;				# Display additional information to track down problems
		$TESTING			= 	1;				# Set the testing flag so that transactions are not live
		$ERROR_RETRIES		= 	2;				# Number of transactions to post if soft errors occur	
			

		$auth_net_login_id			=  LOGINID;     //"benamram202";
		$auth_net_tran_key			=  TRANKEY;	
		$auth_net_url				= "https://secure.authorize.net/gateway/transact.dll";
		
		$authnet_values				= array
		(
			"x_login"				=> 	$auth_net_login_id,
			"x_version"				=> 	"3.1",
			"x_delim_char"			=> 	"|",
			"x_delim_data"			=> 	"TRUE",
			"x_url"					=> 	"FALSE",
			"x_type"				=> 	"AUTH_CAPTURE",
			"x_method"				=> 	"CC",
			"x_tran_key"			=> 	$auth_net_tran_key,
			"x_relay_response"		=> 	"FALSE",
			"x_card_num"			=> 	$x_card_num,
			"x_exp_date"			=> 	$x_exp_date,
			"x_amount"				=> 	$x_amount,
			"x_first_name"			=> 	$fname,
			"x_last_name"			=> 	$lname,
			"x_address"				=> 	$x_address,
			"x_city"				=> 	$x_city,
			"x_state"				=> 	$x_state,
			"x_zip"					=> 	$x_zip,
			"x_invoice_num"			=>	$sesId,
			"x_cust_id"				=>	$x_cust_id,
			"x_test_request"		=>	"FALSE",				
		 );	   	
		 $fields = "";


		 foreach( $authnet_values as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";
		 ///////////////////////////////////////////////////////////		
		 if($x_amount!="" && $x_amount!="0"){						
				 $ch = curl_init("https://secure.authorize.net/gateway/transact.dll"); // URL of gateway for cURL to post to
				 curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
				 curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // Returns response data instead of TRUE(1)
				 curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim($fields, "& ")); // use HTTP POST to send form data
				 $resp = curl_exec($ch); //execute post and get results
				 curl_close ($ch);				
				 $strArry	=	explode("|",$resp);					
                 return  $strArry;         # $strArry[3] = reason;  $strArry[0]==1 || $strArry[0]==111 = success
		 } else {
		 	return array('errorMessage'=>"Amount is zero or null");
		 }

}



//$val = array
//		(
//			"amount"=>rand(10, 100),
//			"creditCard"=>"4111111111111111",
//			"expMonth"=>"03",
//			"expYear"=>"2008",
//			"cvc"=>"123"			
//		);
//$val = $_POST;
//print_r (processPayment($val));


?>