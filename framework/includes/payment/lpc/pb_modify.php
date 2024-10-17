<?php
/**********************************************************************\

	PB_MODIFY.php -  Minimum Required Fields to Modify a Recurring Credit Card Sale (Periodic Bill)

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
	$myorder["chargetotal"]  = "125";
	$myorder["cardnumber"]   = "4111111111111111";
	$myorder["cardexpmonth"] = '06';
	$myorder["cardexpyear"]  = '09';
	
	# PERIODIC FIELDS
	
	# Set action to MODIFY to modify the periodic bill. You may modify ONLY the parameters in the periodic entity
	$myorder["action"] = "MODIFY";
	# Changes will be in effect as of the time specified. No periodic bills will be sent until the startdate specified here. If you don't want it to start today, pass a date in the format YYYYMMDD 
	$myorder["startdate"] = "immediate";
	# Specifies a recurring transaction charging the card 5 times, once a month.
	$myorder["installments"] = "15";
	$myorder["threshold"] = "3";
	$myorder["periodicity"] = "monthly";
	# You MUST pass a valid Order ID for an EXISTING periodic bill to identify which bill you want to modify -->
	$myorder["oid"] = "3B5D2BDB-45C25105-875-2B3C0";


  # Send transaction. Use one of two possible methods  #
//	$result = $mylphp->process($myorder);       # use shared library model
	$result = $mylphp->curl_process($myorder);  # use curl methods


	if ($result["r_approved"] != "APPROVED")	// transaction failed, print the reason
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

