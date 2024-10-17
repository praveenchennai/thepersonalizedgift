<?php

/**
 * This file can be used to handle paypal secure payments.
 * The certificate file is expected to present in the same directory.
 * 
 * :- Aneesh <aneesh.aravindan@newagesmb.com>
 * 
*/

include("../../../config.php");
require_once 'lib/lphp.php';


/* 		*** 	From Database	 ***		 */
define("KEYFILE", 'lib/5678903.pem');                # Change this to the name and location of your certificate file
define("CONFIGFILE", '987617');
/* 		*** 	From Database	 ***		 */



function processPayment ($param) {
   
    $mylphp                 	= new lphp;

   
    # payment gateway details
	$myorder["host"] 	   		=  "secure.linkpt.net";
    $myorder["port"] 	   		=  "1129";
	$myorder["keyfile"]    		=   KEYFILE;               # Change this to the name and location of your certificate file
	$myorder["configfile"] 		=   CONFIGFILE;                    # Change this to your store number # transaction details
	$myorder["ordertype"]  		=   'SALE';
	$myorder["result"]     		=   'GOOD';

	
	$myorder["oid"]        		=   $param['oid'];


    # card info from User Side
	$myorder["subtotal"]        =   $param['subtotal'];
	$myorder["cardnumber"]      =   $param['cardnumber'];
	$myorder["cardexpmonth"]    =   $param['cardexpmonth'];
	$myorder["cardexpyear"]     =   $param['cardexpyear'];
	$myorder["reg_id"] 		    =   $param['reg_id'];
	$myorder["sub_id"] 		    =   $param['sub_id'];
	$myorder["insertID"] 	    =   $param['insertID'];
	$myorder["memname"] 	    =   $param['memname'];
	$myorder["memusername"]     =   $param['memusername'];
    # card info


    

	$result = $mylphp->curl_process($myorder); 
	
    return $result;

}
					 



/*
$val = array();
$val = $_POST;
print_r ($gateWay = processPayment($val));

if($gateWay['r_approved'] == "APPROVED")	     # SUBMITTED
echo 'S';

*/

				
?>