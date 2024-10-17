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
 * This sample code demonstrates how to utilize the Google Checkout 
 * PHP Client Library to generate an order processing request and to 
 * transmit it to Google Checkout.
 * 
 */

// Include Google Checkout PHP Client Library
include ("GlobalAPIFunctions.php");
include ("OrderProcessingAPIFunctions.php");
include ("ResponseHandlerAPIFunctions.php");

/*
 * Build Order Processing Commands 
 *
 * The next three lines of code specify the information for
 * a <charge-order> command and then call the CreateChargeOrder
 * function to construct that command.
 *
 * Following the <charge-order> command, there are several snippets 
 * of commented code that could be used to create other types of 
 * Order Processing API commands for the same order.
 */

$google_order_number = "841171949013218";
$amount = "100.00";
$xml_request = CreateChargeOrder($google_order_number, $amount);

/*
 * This section creates a <refund-order> request
 * $google_order_number = "841171949013218";
 * $reason = "Buyer requested refund.";
 * $amount = "100.00";
 * $comment = "Buyer is not happy with the product.";    
 * $xml_request = 
 *     CreateRefundOrder($google_order_number, $reason, $amount, $comment);
 */

/*
 * This section creates a <cancel-order> request
 * $google_order_number = "841171949013218";
 * $reason = "Buyer cancelled the order.";
 * $comment = "Buyer found a better deal.";
 * $xml_request = CreateCancelOrder($google_order_number, $reason, $comment);
 */

/*
 * This section creates a <process-order> request
 * $google_order_number = "841171949013218";    
 * $xml_request = CreateProcessOrder($google_order_number);
 */

/*
 * This section creates a <deliver-order> request
 * $google_order_number = "841171949013218";
 * $carrier = "UPS";
 * $tracking_number = "Z5498W45987123684";
 * $send_email = "true";
 * $xml_request = CreateDeliverOrder($google_order_number, $carrier, 
 *     $tracking_number, $send_email);
 */

/*
 * This section creates an <add-tracking-data> request
 * $google_order_number = "841171949013218";
 * $carrier = "UPS";                    
 * $tracking_number = "Z9842W69871281267";
 * $xml_request = 
 *     CreateAddTrackingData($google_order_number, $carrier, $tracking_number);
 */

/*
 * This section creates an <archive-order> request
 * $google_order_number = "841171949013218";
 * $xml_request = CreateArchiveOrder($google_order_number);
 */

/*
 * This section creates an <unarchive-order> request
 * $google_order_number = "841171949013218 ";
 * $xml_request = CreateUnarchiveOrder($google_order_number);
 */

/*
 * This section creates a <send-buyer-message> request
 * $google_order_number = "841171949013218";
 * $message = "Dear Customer, due to a high volume of orders, your " .
 * "order will not be charged and shipped until next week. Thank you " .
 * "for your patience.";    
 * $send_email = "true";
 * $xml_request = 
 *     CreateSendBuyerMessage($google_order_number, $message, $send_email);
 */

/*
 * This section creates an <add-merchant-order-number> request
 * $google_order_number = "841171949013218";
 * $merchant_order_number = "MyOrderNumber12345";
 * $xml_request = CreateAddMerchantOrderNumber($google_order_number, 
 *     $merchant_order_number);
 */

/*
 * The following HTML page calls the DisplayDiagnoseResponse function,
 * which is defined in GlobalAPIFunctions.php, to verify that the 
 * API request contains valid XML. If the request does contain valid 
 * XML, the page sends the request to Google Checkout by calling the 
 * SendRequest function, which is also defined in GlobalAPIFunctions.php.
 * The page then calls the ProcessXmlData function, which is defined
 * in ResponseHandlerAPIFunctions.php, to handle Google Checkout's API 
 * response.
 *
 * If the request does not contain valid XML, you will see a link to 
 * a tool that lets you edit and recheck the XML. The code for that 
 * tool is in the <b>DebuggingTool.php</b> file, which is also 
 * included in the <b>checkout-php-samplecode.zip</b> file.
 */

?>

<html>
<head>
    <style type="text/css">@import url(googleCheckout.css);</style>
</head>
<body>
<p style="text-align:center">
<table class="table-1" cellspacing="5" cellpadding="5">
    <tr><td style="padding-bottom:20px;text-align:center"><h2>
        Order Processing Command
    </h2></td></tr>
    <tr><td style="padding-bottom:20px">
        <p><b>Order Processing Command XML:</b></p>
        <p><?php echo htmlentities($xml_request); ?></p>
    </td></tr>
<?php
    // Validate Request XML
    DisplayDiagnoseResponse($xml_request, 
        $GLOBALS["request_diagnose_url"], $xml_request, "debug");

    echo "<tr><td style=\"padding-bottom:20px\">" .
         "<p><b>Synchronous Response Received:</b></p>";

    // Send the request and receive a response
    $transmit_response = SendRequest($xml_request, $GLOBALS["request_url"]);

    // Process the response
    echo "<p>" . ProcessXmlData($transmit_response) . "</p></td></tr>";
?>
</table>
</p>
</body>
</html>