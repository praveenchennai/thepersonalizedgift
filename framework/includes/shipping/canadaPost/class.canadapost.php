<?php 
/*
  _canadapost.php version 0.1
  
  Before use this class, you should open a Canada 
  Post Eparcel Account, and change the CPCIP to
  your ID, or use the demo ID in this class. 
  Visit www.canadapost.ca for detail.
  
  XML connection method with Canada Post.

  Copyright (c) 2002,2003 Kelvin Zhang (kelvin@syngear.com)

  Released under the GNU General Public License
  
  Usage:
*/
 //test();

    function test() {
        $rate = new _canadapost('sellonline.canadapost.ca', '30000', 'en', 'CPC_DEMO_XML', 'M1P1C0', '8');
        $rate->setDestination ('Verdun','Wisconsin','CA','H3K1E5');
        //$rate->setDestination ('Cochin','Kerala','IN','682306');
        //$rate->setDestination ('Brooklyn','New York','US','11211');
        
        //$rate->addItem (2, 23.8, 1.5, 10, 10, 10, 'test stuff1');
        //$rate->addItem (1, 123.8, 1.5, 10, 30, 10, 'test stuff2');
        $rate->addItem (1, 123.8,5, 0, 0, 0, 'Rings');
        print_r ($rate->getShppingProducts());  
    }

  class _canadapost {
    var $server = "sellonline.canadapost.ca";	// test server ip address of canada post.
    var $port = "30000";		// test port number.
    var $language = "en";		// canada posr support two languages. en: english fr: franch (optional)
    var $CPCID = "CPC_DEMO_XML"; 	//  CPC_DEMO_XML (Canada Post Customer ID)Merchant Identification assigned by Canada Post. 
    var $orig_zip;			// Origin Postal Code. (optional)
    var $turnaround_time;		// Turn Around Time  (hours) . (optional)
    var $items_price;			// Total amount in $ of the items, for insurance calculation.
    
    // items informations
    var $items_qty = 0;
    var $item_quantity;
    var $item_weight;
    var $item_length;
    var $item_width;
    var $item_height;
    var $item_description;
    var $item_readytoship;		// this item will be shipped in its original box.
    
    //destination informations
    var $dest_city;
    var $dest_province;
    var $dest_country;
    var $dest_zip;

    function _canadapost ($server, $port, $language, $CPCID, $orig_zip, $turnaround_time) {
      $this->server = $server;
      $this->port = $port;
      $this->language = $language;
      $this->CPCID = $CPCID;
      $this->orig_zip = $orig_zip;
      $this->turnaround_time = $turnaround_time;
      $this->items_qty = 0;
      $this->items_price = 0;

    }

    function setDestination ($dest_city,$dest_province,$dest_country,$dest_zip) {
      $this->dest_city = $dest_city;
      $this->dest_province = $dest_province;
      $this->dest_country = $dest_country;
      $this->dest_zip = $dest_zip;
    }
    
    /* 
      using HTTP/POST send message to canada post server
    */
    function sendToHost($host,$port,$method,$path,$data,$useragent=0) {
	// Supply a default method of GET if the one passed was empty
	if (empty($method))
		$method = 'GET';
	$method = strtoupper($method);
	$fp = fsockopen($host,$port);
	if ($method == 'GET')
		$path .= '?' . $data;
	fputs($fp, "$method $path HTTP/1.1\n");
	fputs($fp, "Host: $host\n");
	fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
	fputs($fp, "Content-length: " . strlen($data) . "\n");
	if ($useragent)
		fputs($fp, "User-Agent: MSIE\n");
	fputs($fp, "Connection: close\n\n");
	if ($method == 'POST')
		fputs($fp, $data);

	while (!feof($fp))
		$buf .= fgets($fp,128);
	fclose($fp);
	return $buf;
    }

    /*
      Add items to parcel. If $readytoship=1, this item will be shipped in its oringinal box
    */
    function addItem ($quantity, $rate, $weight, $length, $width, $height, $description, $readytoship=1) {
      $index = $this->items_qty;
      $this->item_quantity[$index] = (string)$quantity;
      $this->item_weight[$index] = (string)$weight;
      $this->item_length[$index] = (string)$length;
      $this->item_width[$index] = (string)$width;
      $this->item_height[$index] = (string)$height;
      $this->item_description[$index] = $description;
      $this->item_readytoship[$index] = $readytoship;
      $this->items_qty ++;
      $this->items_price += $quantity * $rate;
    }
    
    /*
      Get Canada Post shipping products that are available for current parcels
      This function will return an array include all available products. e.g:
        Array ( 
          [0] => Array ( 
            [name] => Priority Courier 
            [rate] => 25.35 
            [shippingDate] => 2002-08-26 
            [deliveryDate] => 2002-08-27 
            [deliveryDayOfWeek] => 3 
            [nextDayAM] => true 
            [packingID] => P_0 
          ) 
          [1] => Array ( 
            [name] => Xpresspost 
            [rate] => 14.36 
            [shippingDate] => 2002-08-26 
            [deliveryDate] => 2002-08-27 
            [deliveryDayOfWeek] => 3 
            [nextDayAM] => false 
            [packingID] => P_0 
          ) 
          [2] => Array ( 
            [name] => Regular 
            [rate] => 12.36 
            [shippingDate] => 2002-08-26 
            [deliveryDate] => 2002-08-28 
            [deliveryDayOfWeek] => 4 
            [nextDayAM] => false 
            [packingID] => P_0 
          ) 
        )
      If the parcels can't be shipped or other error, this function will return 
      error message. e.g: "The parcel is too large to delivery."
    */ 

	function get_canadapostQuote($Params, $canadaPostDetails)	{

			$Results				=	array();

			#$rate = new _canadapost('sellonline.canadapost.ca', '30000', 'en', 'CPC_DEMO_XML', 'M1P1C0', '8');	
			$this->server = $canadaPostDetails['canadaPost_server'];
			$this->port = '30000';
			$this->language = 'en';
			$this->CPCID = $canadaPostDetails['customer_id'];
			$this->orig_zip = $canadaPostDetails['zip'];
			$this->turnaround_time = 8;


			//$this->setDestination ('Verdun','Wisconsin','CA','H3K1E5');  # City, State , Country , zip
			$this->setDestination ( $Params['dst_state'],$Params['dst_state'],$Params['dst_country'],$Params['dst_zip'] );  # City, State , Country , zip

			//$this->addItem (2, 23.8, 1.5, 10, 10, 10, 'test stuff1'); # quantity,rate,weight,length,width,height,description,readytoship
			$this->addItem (1, 0, $Params['weight'], 0, 0, 0, 'All Products');


			$CostArray	=	$this->getShppingProducts();  


			if(!is_array($CostArray)) {
				$Results['Got'] 	 	=	'No';
				$Results['Price'] 	 	=	0;
				$Results['Message'] 	=	implode('<pre>',$CostArray);
			} else {
				$CostArray				=	array_reverse ($CostArray);
				
				$Results['Got'] 	 	=	'Yes';
				$Results['Price'] 	 	=	$CostArray;
				$Results['Message'] 	=	'Success';
			}
			
			
			return $Results;

	}





    function getShppingProducts() {  
	$strXML = "<?xml version=\"1.0\" ?>";

	// set package configuration.
	$strXML .= "<eparcel>\n";
	$strXML .= "        <language>" . $this->language . "</language>\n";
	$strXML .= "        <ratesAndServicesRequest>\n";
	$strXML .= "                <merchantCPCID>" . $this->CPCID . "</merchantCPCID>\n";
	$strXML .= "                <fromPostalCode>" . $this->orig_zip . "</fromPostalCode>\n";
	$strXML .= "                <turnAroundTime>" . $this->turnaround_time . "</turnAroundTime>\n";
	$strXML .= "                <itemsPrice>" . (string)$this->items_price . "</itemsPrice>\n";
	
	// add items information.
	$strXML .= "            <lineItems>\n";
	for ($i=0; $i < $this->items_qty; $i++) {
		$strXML .= "	    <item>\n";
		$strXML .= "                <quantity>" . $this->item_quantity[$i] . "</quantity>\n";
		$strXML .= "                <weight>" . $this->item_weight[$i] . "</weight>\n";
		$strXML .= "                <length>" . $this->item_length[$i] . "</length>\n";
		$strXML .= "                <width>" . $this->item_width[$i] . "</width>\n";
		$strXML .= "                <height>" . $this->item_height[$i] . "</height>\n";
		$strXML .= "                <description>" . $this->item_description[$i] . "</description>\n";
		if ($this->item_readytoship[$i]) $strXML .= "                <readyToShip/>\n";
		$strXML .= "	    </item>\n";
	}
	$strXML .= "           </lineItems>\n";
	
	// add destination information.
	$strXML .= "               <city>" . $this->dest_city . "</city>\n";
	$strXML .= "               <provOrState>" . $this->dest_province . "</provOrState>\n";
	$strXML .= "               <country>" . $this->dest_country . "</country>\n";
	$strXML .= "               <postalCode>" . $this->dest_zip . "</postalCode>\n";
	$strXML .= "        </ratesAndServicesRequest>\n";
	$strXML .= "</eparcel>\n";
	
	//print substr($strXML,22);
	if ($resultXML = $this->sendToHost($this->server,$this->port,'POST','',$strXML)) {
		return $this->parserResult($resultXML);
	} else return false;
    }

    /*
      Parser XML message returned by canada post server.
    */
    function parserResult($resultXML) {
    	$statusMessage = substr($resultXML, strpos($resultXML, "<statusMessage>")+strlen("<statusMessage>"), strpos($resultXML, "</statusMessage>")-strlen("<statusMessage>")-strpos($resultXML, "<statusMessage>"));
    	//print "message = $statusMessage";
    	if ($statusMessage == 'OK') {
    		$strProduct = substr($resultXML, strpos($resultXML, "<product id=")+strlen("<product id=>"), strpos($resultXML, "</product>")-strlen("<product id=>")-strpos($resultXML, "<product id="));
    		$index = 0;
    		$aryProducts = false;
    		while (strpos($resultXML, "</product>")) {
    			$aryProducts[$index][name] = substr($resultXML, strpos($resultXML, "<name>")+strlen("<name>"), strpos($resultXML, "</name>")-strlen("<name>")-strpos($resultXML, "<name>"));
    			$aryProducts[$index][rate] = substr($resultXML, strpos($resultXML, "<rate>")+strlen("<rate>"), strpos($resultXML, "</rate>")-strlen("<rate>")-strpos($resultXML, "<rate>"));
    			$aryProducts[$index][shippingDate] = substr($resultXML, strpos($resultXML, "<shippingDate>")+strlen("<shippingDate>"), strpos($resultXML, "</shippingDate>")-strlen("<shippingDate>")-strpos($resultXML, "<shippingDate>"));
    			$aryProducts[$index][deliveryDate] = substr($resultXML, strpos($resultXML, "<deliveryDate>")+strlen("<deliveryDate>"), strpos($resultXML, "</deliveryDate>")-strlen("<deliveryDate>")-strpos($resultXML, "<deliveryDate>"));
    			$aryProducts[$index][deliveryDayOfWeek] = substr($resultXML, strpos($resultXML, "<deliveryDayOfWeek>")+strlen("<deliveryDayOfWeek>"), strpos($resultXML, "</deliveryDayOfWeek>")-strlen("<deliveryDayOfWeek>")-strpos($resultXML, "<deliveryDayOfWeek>"));
    			$aryProducts[$index][nextDayAM] = substr($resultXML, strpos($resultXML, "<nextDayAM>")+strlen("<nextDayAM>"), strpos($resultXML, "</nextDayAM>")-strlen("<nextDayAM>")-strpos($resultXML, "<nextDayAM>"));
    			$aryProducts[$index][packingID] = substr($resultXML, strpos($resultXML, "<packingID>")+strlen("<packingID>"), strpos($resultXML, "</packingID>")-strlen("<packingID>")-strpos($resultXML, "<packingID>"));
    			$index++;
    			$resultXML = substr($resultXML, strpos($resultXML, "</product>") + strlen("</product>"));
    		}
    		return $aryProducts;
    	}
    	else {
    		if (strpos($resultXML, "<error>")) return $statusMessage;
    		else return false;
    	}
    }

  }
?>
