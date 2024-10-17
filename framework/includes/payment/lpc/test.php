<?
include("lib/lphp.php");

$mylphp     = new lphp;


					 //payment gateway details

					 	$order_id				= rand(1,10000);
					 # constants
						$myorder["host"] 	   	= "staging.linkpt.net";
						$myorder["port"] 	   	= "1129";
						$myorder["keyfile"]    	= "lib/1909990185.pem"; # Change this to the name and location of your certificate file
						$myorder["configfile"] 	= "1909990185"; # Change this to your store number # transaction details
						$myorder["ordertype"]  	= 'SALE';
						//$myorder["result"]     	= 'GOOD';   Test
						$myorder["oid"]        	= $order_id;
						# totals
						
						# card info
						$myorder["subtotal"]     = '10000';
						$myorder["cardnumber"]   = '4111111111111111';
						$myorder["cardexpmonth"] = '06';
						$myorder["cardexpyear"]  = '10';
						$myorder["reg_id"] 		 = '121212';
						$myorder["sub_id"] 		 = '212121';
						$myorder["insertID"] 	 = '333444';
						$myorder["memname"] 	 = 'aaaaa';
						$myorder["memusername"]  = 'ads';
					 	
						$result = $mylphp->curl_process($myorder); 

					 	echo $result["r_approved"];

						if ($result["r_approved"] != "APPROVED") // transaction failed, print the reason
						{
						    echo 'Failed'."<br>";
						    echo "<pre>";
							  print_r($result);
							echo "</pre>";
						} else {
							echo 'Success'."<br>";
						    echo "<pre>";
							  print_r($result);
							echo "</pre>";
						}
						


   
						# https://www.linkpointcentral.com/lpc/servlet/lppay
						
						
						
						
						
						
						
						
						
						
	
						
?>