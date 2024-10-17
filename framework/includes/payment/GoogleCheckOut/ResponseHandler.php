<?php
/**
 * Copyright (C) 2006 Google Inc.
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *      http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Please refer to the Google Checkout PHP Sample Code Documentation
 * for requirements and guidelines on how to use the sample code.
 * 
 * This sample code is a simple listener for the responses that the 
 * Google Checkout server sends to the merchants. When a response is 
 * received, it logs the message to a local file and processes the message.
 *
 */

// Include Google Checkout PHP Client Library
include ("GlobalAPIFunctions.php");

// Include Response Message Processor
include ("ResponseHandlerAPIFunctions.php");

// Retrieve the XML sent in the HTTP POST request to the ResponseHandler
$xml_response = $HTTP_RAW_POST_DATA;

// Get rid of PHP's magical escaping of quotes 
if (get_magic_quotes_gpc()) {
    $xml_response = stripslashes($xml_response);
}

// Log the XML received in the HTTP POST request
LogMessage($GLOBALS["logfile"], $xml_response);

/*
 * Call the ProcessXmlData function, which is defined in
 * ResponseHandlerAPIFunctions.php. The ProcessXmlData will route 
 * the XML data to the function that handles the particular type
 * of XML message contained in the POST request.
 */
ProcessXmlData($xml_response);

?>