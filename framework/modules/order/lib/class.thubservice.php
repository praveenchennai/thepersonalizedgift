<?php



class THubService extends FrameWork
{
	
	var		$orderDownloadStatusList;
	var 	$orderShippedStatus;
	var 	$orderCompleteStatus;
	var 	$orderBackOrderedStatus;
	var 	$thub_company_field;
	var 	$thub_shipcompany_field;
	
	var 	$Currency;
	
	
	
	/**
	 * Constructor
	 *
	 * @return THubService
	 */
	function THubService() 
	{
		$this->orderDownloadStatusList		=	'0,1,2,3,4,5,6,7';
		$this->orderShippedStatus			=	'';
		$this->orderCompleteStatus			=	'';
		$this->orderBackOrderedStatus		=	'';
		$this->thub_company_field			=	'';
		$this->thub_shipcompany_field		=	'';
		
		$this->Currency						=	'USD';
		$this->FrameWork();
	}
	
	
	function getOrders($REQUEST)
	{
		$Response	=	'';
		$request	=	$REQUEST['request'];
		$request 	=	trim(stripcslashes($request));
		$xmlRequest	=	new xml_doc($request);
		$xmlRequest->parse();
		$xmlRequest->getTag(0, $_tagName, $_tagAttributes, $_tagContents, $_tagTags);
				
		if(strtoupper(trim($_tagName)) != 'REQUEST') {
			$Response	=	$this->xmlErrorResponse('unknown', '9999', 'Unknown request', $this->config['site_name'], '');
			return $Response;
		}
				
		if(count($_tagTags) == 0) {
			$Response	=	$this->xmlErrorResponse('unknown', '9999', 'REQUEST tag doesnt have necessry parameters', $this->config['site_name'], '');
			return $Response;
		}
		
		$RequestParams	=	array();
		foreach($_tagTags as $k=>$v) {
			$xmlRequest->getTag($v, $tN, $tA, $tC, $tT);
			$RequestParams[strtoupper($tN)] = trim($tC);
		}
		
		if (!isset($RequestParams['COMMAND'])) {
			$Response	=	$this->xmlErrorResponse('unknown', '9999', 'Command is not set', $this->config['site_name'], '');
			return $Response;
		}
					
		$RequestParams['COMMAND'] = strtoupper($RequestParams['COMMAND']);
		if(($RequestParams['COMMAND'] != ('GET'.'ORDERS')) && ($RequestParams['COMMAND'] != ('UPDATE'.'ORDERS')) && ($RequestParams['COMMAND'] != ('UPDATE'.'ORDERS'.'SHIPPING'.'STATUS'))) {
      		$Response	=	$this->xmlErrorResponse('unknown', '9999', 'Unknown Command'.$RequestParams['COMMAND'], $this->config['site_name'], '');
			return $Response;
      	}
		      	
      	if (isset($RequestParams['REQUESTID']))
      		$request_id = $RequestParams['REQUESTID'];
      	
      	$Authenticated	=	false;
      	$AdminUserId	=	$RequestParams['USERID'];
      	$AdminPassword	=	$RequestParams['PASSWORD'];
      	
      	$AuthQuery	=	"SELECT COUNT(*) AS RowCount FROM member_master WHERE username = '$AdminUserId' AND password = '$AdminPassword'";
      	$AuthRow	=	$this->db->get_row($AuthQuery);
		$RowCount	=	$AuthRow->RowCount;
		if($RowCount > 0)
			$Authenticated	=	true;
		
		if($Authenticated === false) {
			$Response	=	$this->xmlErrorResponse($RequestParams['COMMAND'], '9000', 'Order download service authentication failure - Login/Password supplied did not match', $this->config['site_name'], '');
			return $Response;
		}
		
		if(isset($RequestParams['LIMITORDERCOUNT']))
			define(QB_ORDERS_PER_RESPONSE, $RequestParams['LIMITORDERCOUNT']);
		else 
			define(QB_ORDERS_PER_RESPONSE, 25);
		
		if(isset($RequestParams['NUMBEROFDAYS']))
			define(QB_NUMBER_OF_DAYS, $RequestParams['NUMBEROFDAYS']);
		else 
			define(QB_NUMBER_OF_DAYS, 5);
		
		if(isset($RequestParams['ORDERSTARTNUMBER']))
			define(QB_ORDER_START_NUMBER, $RequestParams['ORDERSTARTNUMBER']);
		else 
			define(QB_ORDER_START_NUMBER, 0);
		
		if(isset($RequestParams['EXCLUDE-ORDERS'])) {
			$exclude_orders = trim($RequestParams['EXCLUDE-ORDERS']);
			if (empty($exclude_orders)) 
				unset($exclude_orders);
		}
		
		
		$xmlResponse	=	new xml_doc();
		$xmlResponse->version='1.0';
		$xmlResponse->encoding='ISO-8859-1';
		
		$root 		=	$xmlResponse->createTag("RESPONSE", array('Version'=>'4.0'));
		$envelope 	=	$xmlResponse->createTag("Envelope", array(), '', $root);
		$xmlResponse->createTag("Command", array(), $RequestParams['COMMAND'], $envelope);
		
		
		switch($RequestParams['COMMAND'])
		{
			case 'GETORDERS':
				$str_date_filter = "";
				if(QB_ORDER_START_NUMBER == 0)
					$str_date_filter = " AND to_days(Now()) - TO_DAYS(T1.date_purchased) <= ".QB_NUMBER_OF_DAYS;
				
				$OrderCountQry	=	"SELECT COUNT(*) AS OrderCount 
									FROM orders AS T1 
									WHERE T1.id >".QB_ORDER_START_NUMBER.
									" AND T1.order_status IN(".$this->orderDownloadStatusList.")".
									(QB_ORDERS_PER_RESPONSE>0?" LIMIT 0, ".QB_ORDERS_PER_RESPONSE:'');
				$OrderCountRow	=	$this->db->get_row($OrderCountQry);
				$OrderCount		=	$OrderCountRow->OrderCount;
								
				$no_orders	=	false;
				if($OrderCount == 0)
					$no_orders = true;
				
				$xmlResponse->createTag("StatusCode", array(), $no_orders?"1000":"0", $envelope);
				$xmlResponse->createTag("StatusMessage", array(), $no_orders?"No Orders returned":"All Ok", $envelope);		
				
				if($no_orders === true){
					$Response	=	$xmlResponse->generate();
					return $Response;
				}
				
				$ordersNode = $xmlResponse->createTag("Orders", array(), '', $root);

				
				$OrderQry		=	"SELECT T1.*, T2.name AS StoreName,T3.country_name as Bill_Country,T4.country_name  as Ship_Country FROM orders AS T1 LEFT JOIN store AS T2 ON T2.id = T1.store_id inner join country_master T3 on T3.country_id=T1.billing_country inner join country_master T4 on T4.country_id=T1.shipping_country
									WHERE T1.id >".QB_ORDER_START_NUMBER.
									" AND T1.order_status IN(".$this->orderDownloadStatusList.")".
									(QB_ORDERS_PER_RESPONSE>0?" LIMIT 0, ".QB_ORDERS_PER_RESPONSE:'');
				$OrderDetails	=	$this->db->get_results($OrderQry);
				
				foreach($OrderDetails as $OrderDetail) {
					$Order		=	$this->parseSpecCharsA($OrderDetail);
					$OrderId	=	$Order->id;
					
					$orderNode  = $xmlResponse->createTag("Order", 		array(), '', $ordersNode);
			        $itemsNode  = $xmlResponse->createTag("Items", 		array(), '', $orderNode);
			        $billNode  	= $xmlResponse->createTag("Bill", 		array(), '', $orderNode);
			        $shipNode  	= $xmlResponse->createTag("Ship", 		array(), '', $orderNode);
			        $chargesNode  = $xmlResponse->createTag("Charges", 	array(), '', $orderNode);
			        
			        $xmlResponse->createTag("OrderID", 				array(), $Order->id, $orderNode);
			        $xmlResponse->createTag("ProviderOrderRef", 	array(), $Order->id, $orderNode);
			        $xmlResponse->createTag("_Status", 				array(), $Order->order_status, $orderNode); 
			        $xmlResponse->createTag("_StatusID",			array(), $Order->order_status, $orderNode); 
			        $xmlResponse->createTag("Date", 				array(), date("Y-m-d",strtotime($Order->date_ordered)), $orderNode); //date
			        $xmlResponse->createTag("Time", 				array(), date("H-i-s",strtotime($Order->date_ordered)), $orderNode); //time
			        $xmlResponse->createTag("TimeZone", 			array(), 'PST', $orderNode);
			        $xmlResponse->createTag("StoreId", 				array(), $Order->store_id, $orderNode);
			        $xmlResponse->createTag("StoreName", 			array(), $Order->StoreName, $orderNode);
			        $xmlResponse->createTag("Currency", 			array(), $this->Currency, $orderNode);
					if(!empty($Order->notes)) $xmlResponse->createTag("Comment", array(), $Order->notes, $orderNode);
					
					$xmlResponse->createTag("PayMethod",	array(), $Order->paytype, $billNode);
        			
					$xmlResponse->createTag("FirstName",	array(), strtok($Order->billing_first_name, " "), $billNode);
					$xmlResponse->createTag("LastName",		array(), strtok($Order->billing_last_name, " "), $billNode);
					
					if($Order->billing_company_name != "")
						$xmlResponse->createTag("CompanyName",array(), $Order->billing_company_name, $billNode);
					else
						$xmlResponse->createTag("CompanyName",array(), '',   $billNode);
					
					$xmlResponse->createTag("Address1", 	array(), $Order->billing_address1, 	$billNode);
					$xmlResponse->createTag("Address2", 	array(), $Order->billing_address2, 	$billNode);
					$xmlResponse->createTag("City", 		array(), $Order->billing_city,	 	$billNode);
					$xmlResponse->createTag("State", 		array(), $Order->billing_city, 		$billNode);
					$xmlResponse->createTag("Zip", 			array(), $Order->billing_postalcode, 	$billNode);
					$xmlResponse->createTag("Country", 		array(), $Order->Bill_Country, 	$billNode);
					$xmlResponse->createTag("Email", 		array(), $Order->billing_email, 		$billNode);
					$xmlResponse->createTag("Phone", 		array(), $Order->billing_telephone, 	$billNode);
					
					$creditCard = $xmlResponse->createTag("CreditCard",  array(), '',   $billNode);
					$xmlResponse->createTag("CreditCardType",     array(), $Order->credit_card_type,      $creditCard);
					$xmlResponse->createTag("CreditCardCharge",   array(), $Order->paid_price, 			$creditCard);
					$xmlResponse->createTag("ExpirationDate",     array(), $Order->expiration_date, 		$creditCard);
					$xmlResponse->createTag("CreditCardName",     array(), $Order->credit_card_name,      $creditCard);
					$xmlResponse->createTag("CreditCardNumber",   array(), $Order->credit_card_number,    $creditCard);
					
					$xmlResponse->createTag("ShipMethod",	array(), $Order->shipping_method,   $shipNode);
					$xmlResponse->createTag("ShipService",	array(), $Order->shipping_service,   $shipNode);
					$xmlResponse->createTag("FirstName",	array(), strtok($Order->shipping_first_name, " "),   	$shipNode);
					$xmlResponse->createTag("LastName", 	array(), strtok($Order->shipping_last_name, " "),   	$shipNode); 
					
					if($Order->shipping_company_name != "")
						$xmlResponse->createTag("CompanyName",array(), $Order->shipping_company_name,   $shipNode);
					else
						$xmlResponse->createTag("CompanyName",array(), '',   $shipNode);
										
					$xmlResponse->createTag("Address1", array(), $Order->shipping_address1,   $shipNode);
					$xmlResponse->createTag("Address2", array(), $Order->shipping_address2,   $shipNode);
					$xmlResponse->createTag("City",     array(), $Order->shipping_city,     	$shipNode);
					$xmlResponse->createTag("State",    array(), $Order->shipping_state,    	$shipNode);
					$xmlResponse->createTag("Zip",      array(), $Order->shipping_postalcode, $shipNode);
					$xmlResponse->createTag("Country",  array(), $Order->Ship_Country, 	$shipNode);
					$xmlResponse->createTag("Email",    array(), $Order->shipping_email,    	$shipNode);
					$xmlResponse->createTag("Phone",    array(), $Order->shipping_telephone,  $shipNode);
					

					$OrdPrdctsQuery	=	"SELECT 
											T1.*, 
											T2.name AS StoreName,
											T3.id AS ProductId,  
											T3.name AS ProductName, 
											T3.display_name AS ProductDisplayName, 
											T3.weight AS ProductWeight,
											T4.jobname as JobName,
											T4.height as height,
											T4.width as width
										FROM order_products AS T1 
										LEFT JOIN order_products_job AS T4 ON T4.id = T1.id 
										LEFT JOIN store AS T2 ON T2.id = T1.store_id 
										LEFT JOIN products AS T3 ON T3.id = T1.product_id 
										WHERE T1.order_id = '$OrderId'";
					$OrdProducts	=	$this->db->get_results($OrdPrdctsQuery);
					
					foreach($OrdProducts as $OrdProduct) {
						$Product	=	$this->parseSpecCharsA($OrdProduct);
						$Product 	= 	$this->parseSpecChars($Product);
						
						$itemNode	=	$xmlResponse->createTag("Item",    array(), '',    $itemsNode);
				
						$xmlResponse->createTag("ItemCode",       array(), $Product->ProductId, 			$itemNode);
						$xmlResponse->createTag("ItemDescription",array(), $Product->ProductDisplayName,  $itemNode);
						$xmlResponse->createTag("Quantity",       array(), $Product->quantity, 			$itemNode);
						$xmlResponse->createTag("UnitPrice",      array(), ($Product->price*$Product->height*$Product->width),  		  		$itemNode);
						$xmlResponse->createTag("ItemTotal",      array(), ($Product->total_price* $Product->quantity),       	$itemNode);
						$xmlResponse->createTag("ItemUnitWeight", array(), ($Product->ProductWeight * $Product->quantity),   $itemNode);
					}
					
					$xmlResponse->createTag("Tax",      array(), ($Order->total_price*$Order->tax/100), 				$chargesNode);
					$xmlResponse->createTag("Shipping", array(), $Order->shipping_price,    $chargesNode);
					$xmlResponse->createTag("Handling", array(), $Order->handling,      	$chargesNode);
					$xmlResponse->createTag("Discount", array(), $Order->discount,      	$chargesNode);
					$xmlResponse->createTag("Total",    array(), $Order->total_price,      $chargesNode);
					
				}
				
				$Response	=	$xmlResponse->generate();
				return $Response;
				break;
			
			default: return $Response;
					
		}
		
		
		
	}
	
	
	function xmlErrorResponse($command, $code, $message, $provider, $request_id='') 
	{
		header("Content-type: application/xml");
		$xmlResponse = new xml_doc('<?xml version="1.0" encoding="UTF-8"?>');
		//$xmlResponse = new xml_doc();
		//$xmlResponse->version='';
		// $xmlResponse->encoding='UTF-8';
		$root = $xmlResponse->createTag("RESPONSE", array('Version'=>'1.0'));
		//print("i'm here");
		$envelope = $xmlResponse->createTag("Envelope", array(), '', $root);
		$xmlResponse->createTag("Command", array(), $command, $envelope);
		$xmlResponse->createTag("StatusCode", array(), $code, $envelope);
		$xmlResponse->createTag("StatusMessage", array(), $message, $envelope);
		//if ($request_id) $xmlResponse->createTag("RequestID", array(), $request_id, $envelope);
		//$xmlResponse->createTag("Provider", array(), $provider, $envelope);
		return $xmlResponse->generate();
	}

	
	function parseTagName($str) 
	{
		return preg_replace("/[-=+\s!@#\$\^\&%*\(\)\{\}\[\]':`~\.]/is", "", $str);
	}
	
	
	
	function parseSpecChars($obj) 
	{
		foreach($obj as $k=>$v){
			$obj->$k = htmlspecialchars($v, ENT_NOQUOTES); 
		}
		return $obj;
	}
	
	
	
	function parseSpecCharsA($arr)
	{
		//  $obj='';
		foreach($arr as $k=>$v){
			$obj->$k = htmlspecialchars($v, ENT_NOQUOTES); 
		}
		return $obj;
	}
	
	
	function _log($text, $line) 
	{
	  //disabling log for production
	  //$filename = "_log.txt";
	  //$df =  fopen($filename, "a");
	  //$str = date("m-d-Y | H:i:s ($line) ");
	  //fwrite($df, $str.$text."\n");
	  //fclose($df);
	}
	
	
}



class xml_doc 
{
	var $parser;			// Object Reference to parser
	var $xml;				// Raw XML code
	var $version;			// XML Version
	var $encoding;			// Encoding type
	var $dtd;				// DTD Information
	var $entities;			// Array (key/value set) of entities
	var $xml_index;			// Array of object references to XML tags in a DOC
	var $xml_reference;		// Next available Reference ID for index
	var $document;			// Document tag (type: xml_tag)
	var $stack;			// Current object depth (array of index numbers)

	function xml_doc($xml='') 
	{
		// XML Document constructor
		$this->xml = $xml;

		// Set default values
		$this->version = '1.0';
	  	$this->encoding = "ISO-8859-1";
		//$this->encoding = "UTF-8";
		$this->dtd = '';
		$this->entities = array();
		$this->xml_index = array();
		$this->xml_reference = 0;
		$this->stack = array();
	}

	function parse() 
	{
		// Creates the object tree from XML code
		$this->parser = xml_parser_create($this->encoding);
		xml_set_object($this->parser, $this);
		xml_set_element_handler($this->parser, "startElement", "endElement");
		xml_set_character_data_handler($this->parser, "characterData");
		xml_set_default_handler($this->parser, "defaultHandler");

		if (!xml_parse($this->parser, $this->xml)) {
			// Error while parsing document
			$err_code = xml_get_error_code($this->parser);
			$err_string = xml_error_string($this->parser);
			$err_line = xml_get_current_line_number($this->parser);
			$err_col = xml_get_current_column_number($this->parser);
			$err_byte = xml_get_current_byte_index($this->parser);
			print "<p><b>Error Code:</b> $err_code<br>$err_string<br><b>Line:</b> $err_line <b>Column:</b> $err_col</p>";
		}

		xml_parser_free($this->parser);
	}

	function generate() 
	{
		// Generates XML string from the xml_doc::document object

		// Create document header
		if ($this->version == '' and $this->encoding == '') {
			// $out_header = '<' . '?xml ?' . ">\n";
			$out_header = '';
		} elseif ($this->version != '' and $this->encoding == '') {
			$out_header = '<' . "?xml version=\"{$this->version}\"?" . ">\n";
		} else {
			$out_header = '<' . "?xml version=\"{$this->version}\" encoding=\"{$this->encoding}\"?" . ">\n";
		}

		if ($this->dtd != '') {
			$out_header .= "<!DOCTYPE " . $this->dtd . ">\n";
		}

		// Get reference for root tag
		$_root =& $this->xml_index[0];

		// Create XML for root tag
		$this->xml = $this->createXML(0);

		return $out_header . $this->xml;
	}

	
	function stack_location() 
	{
		// Returns index for current working tag
		return $this->stack[(count($this->stack) - 1)];
	}

	function startElement($parser, $name, $attrs=array()) 
	{
		// Process a new tag
		// Check to see if tag is root-level
		if (count($this->stack) == 0) {
			// Tag is root-level (document)

			$this->document = new xml_tag($this,$name,$attrs);
			$this->document->refID = 0;

			$this->xml_index[0] =& $this->document;
			$this->xml_reference = 1;

			$this->stack[0] = 0;

		} else {
			// Get current location in stack array
			$parent_index = $this->stack_location();

			// Get object reference to parent tag
			$parent =& $this->xml_index[$parent_index];

			// Add child to parent
			$parent->addChild($this,$name,$attrs);

			// Update stack
			array_push($this->stack,($this->xml_reference - 1));
		}
	}

	function endElement($parser, $name) 
	{
		// Update stack
		array_pop($this->stack);
	}

	function characterData($parser, $data) 
	{
		// Add textual data to the current tag
		// Get current location in stack array
		$cur_index = $this->stack_location();

		// Get object reference for tag
		$tag =& $this->xml_index[$cur_index];

		// Assign data to tag
		$tag->contents .= $data;
	}

	function defaultHandler($parser, $data) 
	{

	}

	function createTag($name, $attrs=array(), $contents='', $parentID = '') 
	{
		// Creates an XML tag, returns Tag Index #
		if ($parentID === '') {
			// Tag is root-level

			$this->document = new xml_tag($this,$name,$attrs,$contents);
			$this->document->refID = 0;

			$this->xml_index[0] =& $this->document;
			$this->xml_reference = 1;

			return 0;
		} else {
			// Tag is a child

			// Get object reference to parent tag
			$parent =& $this->xml_index[$parentID];
			// Add child to parent
			return $parent->addChild($this,$name,$attrs,$contents);
		}
	}


	function createXML($tagID,$parentXML='') 
	{
		// Creates XML string for a tag object
		// Specify parent XML to insert new string into parent XML

		$final = '';

		// Get Reference to tag object
		$tag =& $this->xml_index[$tagID];

		$name = $tag->name;
		$contents = $tag->contents;
		$attr_count = count($tag->attributes);
		$child_count = count($tag->tags);
		$empty_tag = ($tag->contents == '') ? true : false;

		// Create intial tag
		if ($attr_count == 0) {
			// No attributes

			if ($empty_tag === true) {
					$final = "<$name />";
			} else {
					$final = "<$name>$contents</$name>";
			}
		} else {
			// Attributes present

			$attribs = '';
			foreach ($tag->attributes as $key => $value) {
				$attribs .= ' ' . $key . "=\"$value\"";
			}

			if ($empty_tag === true) {
				$final = "<$name$attribs />";
			} else {
				$final = "<$name$attribs>$contents</$name>";
			}
		}

		// Search for children
		if ($child_count > 0) {
			foreach ($tag->tags as $childID) {
				$final = $this->createXML($childID,$final);
			}
		}

		if ($parentXML != '') {
			// Add tag XML to parent XML

			$stop1 = strrpos($parentXML,'</');
			$stop2 = strrpos($parentXML,' />');

			if ($stop1 > $stop2) {
				// Parent already has children

				$begin_chunk = substr($parentXML,0,$stop1);
				$end_chunk = substr($parentXML,$stop1,(strlen($parentXML) - $stop1 + 1));

				$final = $begin_chunk . $final . $end_chunk;
			} elseif ($stop2 > $stop1) {
				// No previous children

				$spc = strpos($parentXML,' ',0);

				$parent_name = substr($parentXML,1,$spc - 1);

				if ($spc != $stop2) {
					// Attributes present
					$parent_attribs = substr($parentXML,$spc,($stop2 - $spc));
				} else {
					// No attributes
					$parent_attribs = '';
				}

				$final = "<$parent_name$parent_attribs>$final</$parent_name>";
			}
		}

		return $final;
	}


	function getTag($tagID,&$name,&$attributes,&$contents,&$tags) 
	{
		// Returns tag information via variable references from a tag index#
		// Get object reference for tag
		$tag =& $this->xml_index[$tagID];

		$name = $tag->name;
		$attributes = $tag->attributes;
		$contents = $tag->contents;
		$tags = $tag->tags;
	}

	function getChildByName($parentID,$childName,$startIndex=0) 
	{
		// Returns child index# searching by name
		// Get reference for parent
		$parent =& $this->xml_index[$parentID];

		if ($startIndex > count($parent->tags)) return false;


		for ($i = $startIndex; $i < count($parent->tags); $i++) {
			$childID = $parent->tags[$i];		

			// Get reference for child
			$child =& $this->xml_index[$childID];

			if ($child->name == $childName) {
				// Found child, return index#

				return $childID;
			}
		}
	}

}


class xml_tag 
{
	
	var $refID;			// Unique ID number of the tag
	var $name;			// Name of the tag
	var $attributes = array();	// Array (assoc) of attributes for this tag
	var $tags = array();		// An array of refID's for children tags
	var $contents;			// textual (CDATA) contents of a tag
	var $children = array();	// Collection (type: xml_tag) of child tag's


	function xml_tag(&$document,$tag_name,$tag_attrs=array(),$tag_contents='') 
	{
		// Constructor function for xml_tag class


		// Set object variables
		$this->name = $tag_name;
		$this->attributes = $tag_attrs;
		$this->contents = $tag_contents;

		$this->tags = array();			// Initialize children array/collection
		$this->children = array();
	}

	function addChild (&$document,$tag_name,$tag_attrs=array(),$tag_contents='') 
	{
		// Adds a child tag object to the current tag object
		// Create child instance
		$this->children[(count($this->children))] = new xml_tag($document,$tag_name,$tag_attrs,$tag_contents);

		// Add object reference to document index
		$document->xml_index[$document->xml_reference] =& $this->children[(count($this->children) - 1)];

		// Assign document index# to child
		$document->xml_index[$document->xml_reference]->refID = $document->xml_reference;

		// Add child index# to parent collection of child indices
		array_push($this->tags,$document->xml_reference);

		// Update document index counter
		$document->xml_reference++;

		// Return child index#
		return ($document->xml_reference - 1);
	}
}


?>