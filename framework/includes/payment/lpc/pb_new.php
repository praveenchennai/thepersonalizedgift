<?php
/**********************************************************************\

	PB_NEW.php - Minimum Required Fields for a Recurring Credit Card Sale (Periodic Bill) 

	Copyright 2003 LinkPoint International, Inc. All Rights Reserved.

	This software is the proprietary information of LinkPoint International, Inc.  
	Use is subject to license terms.

\***********************************************************************/
include("lib/lphp.php");

$mylphp     = new lphp;

	$myorder["host"]       = "staging.linkpt.net";
	$myorder["port"]       = "1129";
	$myorder["keyfile"]    = "lib/1909990185.pem"; # Change this to the name and location of your certificate file 
	$myorder["configfile"] = "1909990185";        # Change this to your store number 

	$myorder["ordertype"]    = "SALE";
	$myorder["chargetotal"]  = "750";
	$myorder["cardnumber"]   = "4111111111111111";
	$myorder["cardexpmonth"] = '06';
	$myorder["cardexpyear"]  = '10';
	$myorder["addrnum"]      = "123";   # Required for AVS. If not provided, transactions will downgrade.
	$myorder["zip"]          = "12345"; # Required for AVS. If not provided, transactions will downgrade.
	$myorder["debugging"]    = "true";  # for development only - not intended for production use
	
	# periodic fields
	$myorder["action"]       = "SUBMIT";
	$myorder["installments"] = "3";
	$myorder["threshold"]    = "3";
	$myorder["startdate"]    = "immediate";
	$myorder["periodicity"]  = "monthly";


  # Send transaction. Use one of two possible methods  #
//	$result = $mylphp->process($myorder);       # use shared library model
	$result = $mylphp->curl_process($myorder);  # use curl methods


	if ($result["r_approved"] != "SUBMITTED")	// transaction failed, print the reason
	{
		print "Status: $result[r_approved]\n";
		print "Error: $result[r_error]\n";
	}
	else
	{					// success
		print "Status: $result[r_approved]\n";
		print "Code: $result[r_code]\n";
		print "OID: $result[r_ordernum]\n\n";
	}

   
  



/*
	# Look at returned hash & use the elements you need  #
	while (list($key, $value) = each($result))
	{
		echo "$key = $value\n"; 

	#if you're in web space, look at response like this:
		 echo htmlspecialchars($key) . " = " . htmlspecialchars($value) . "<BR>\n";
	}
*/
?>

