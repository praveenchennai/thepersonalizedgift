<pre>
<?php
	require("class.usps.php");
	
	$usps = new USPS;
	#$usps->setServer("http://testing.shippingapis.com/ShippingAPITest.dll");
	#$usps->setUserName("020NEWAG8287");

	$usps->setServer("http://production.shippingapis.com/shippingapi.dll");
	$usps->setUserName("045FULLM0939");
	$usps->setPass("966XJ61RA365");


	# $usps->setService("All");
	# $usps->setService("Surface Post");
	
	$usps->setDestZip("20008");
	$usps->setOrigZip("10022");
	$usps->setWeight(10, 5);
	# $usps->setContainer("Flat Rate Bo");
	$usps->setCountry("SWITZERLAND");
	$usps->setMachinable("true");
	# $usps->setSize("REGULAR");
	$price = $usps->getPrice(); 
		
	if($price->error)
		print $price->error->description;
	
	
	foreach($price->list as $PriceObj) {
		print_r($PriceObj);
		
		if(trim($PriceObj->svcdescription) == 'Express Mail International (EMS)') {
			print "{$PriceObj->rate}:::Express Mail International (EMS)<br>";
		}
		
	}
	
?>
</pre>
