<?php

/* Demo code for the USPS class, which retrieves real-time shipping quotes for domestic and international shipments, directly from the United States
Postal Service. You'll need cURL and DOMXML to use the class. */

require "usps.php";

//Instantiate the class; the last parameter determines what kind of quote you would like (international packages or domestic packages). Use RateV2 for 
//domestic shipments and IntlRate for international. Bear in mind that the parameters given for add_package() differ depending on the API you choose.
//You cannot mix and match APIs! If you instantiate the class with RateV2, you must ONLY put domestic packages in your request. (Use the reset() method
//of the class if you want to clear everything, then set $usps->api to the new API you want to use.) Attempts to add packages that do not fit into
//the requirements of the current API will fail.

#$usps = new USPS("your-username", "your-password", "Verify");
#$usps = new USPS("your-username", "your-password", "ZipCodeLookup");
#$usps = new USPS("your-username", "your-password", "CityStateLookup");

//Add a package - create an array with the required parameters and send that to add_package.
//Domestic shipments require the following parameters:
//service - Parcel, Priority, Express, First Call, BPM, Library, Media, All
//zip_origin - The originating ZIP code of the package
//zip_dest - The destinaton ZIP code of the package
//pounds, ounces - The weight of the package
//size - Regular, Large, or Oversize
//machinable - true or false, only applies to service types Parcel and All
//Be sure to declare every parameter of the package as a string, or add_package will fail!
//International packages require the following parameters:
//pounds, ounces - Package weight
//mail_type - package, postcards or aerogrammes, matter for the blind, envelope
//country - Must be a full name of a country, in the USPS list of valid countries. Look at the top of the class code for the list.

/*
3 New API added

- Verify
- ZipCodeLookup
- CityStateLookup

*/

/*
$package = array(
	'address1' => '',
	'address2' => '8 Wildwood Drive',
	'city' => 'Old Lyme',
	'state' => 'CT',
	'zip5' => '06371',
	'zip4' => '',
);
*/

/*
$package = array(
	'address1' => '',
	'address2' => '6406 Ivy Lane',
	'city' => 'Greenbelt',
	'state' => 'MD',
	'zip5' => '',
	'zip4' => '',
);
*/

/*
$package = array(
	'zip5' => '904210'
);
*/
$usps = new USPS;
if(!$usps->add_package($package));# die("Failed to add the package");

$usps->submit_request();
$err = $usps->get_package_error();
if ($err) {
echo "<pre>";print_r($err);echo "</pre>";
} else {
	$arr['a1'] = $usps->get_address1();
	$arr['a2'] = $usps->get_address2();
	$arr['zip4'] = $usps->get_zip4();
	$arr['zip5'] = $usps->get_zip5();
	$arr['city'] = $usps->get_city();
	$arr['state'] = $usps->get_state();
	echo "<pre>";print_r($arr);echo "</pre>";
}

echo "end";
//Pass the package ID of the shipment you want to get rates for. The USPS API begins counting from 0, so 0 will be the first package
//added, 1 will be second, etc. If the package you requested returned an error, the error itself will be returned as a string. For here,
//though, we'll assume all went well. If you want to get the FULL error details for a certain package (such as error number and other
//such junk), you can call get_package_error($package_id), which will return an array with the error details.
/*
$rates = $usps->get_rates(0);

echo "Mailing rates for a parcel heading across the states:\n";

while(list($k, $v) = each($rates))
{
	echo "$k: " . sprintf("%0.2f", $v) . "\n";
}
*/
//International shipments have other information returned as well. To access extra, relevant information after making a query to the IntlRate
//API, use one of the following accessor functions:
//get_prohibitions($package_id) - Gets a string containing information about stuff that cannot be shipped to that country
//get_restrictions($package_id) - Gets other restrictions about shipments into that country
//get_observations($package_id) - Gets other, miscellaneous observations about shipments into that country
//get_areas_served($package_id) - Gets a list of the areas served within that country

?>