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
 * "OrderProcessingAPIFunctions.php" is a client library of functions 
 * that enables to systematically generate XML for Order Processing 
 * API requests.
 *
 * You should also look at the Demo files to learn more about how to call
 * each function and what it returns.
 */

/******************* Order Processing API ********************/

/**
 * The CreateArchiveOrder function is a wrapper function that calls the
 * ChangeOrderState function. The ChangeOrderState function, in turn,
 * creates an <archive-order> command for the specified order, which is
 * identified by its Google Checkout order number ($google_order_number). The 
 * <archive-order> command moves an order from the Inbox in the Google Checkout
 * Merchant Center to the Archive folder.
 *
 * @param   $google_order_number    A number, assigned by Google Checkout, 
 *                                      that uniquely identifies an order.
 * @return  <archive-order> XML
 */
function CreateArchiveOrder($google_order_number) {
    return ChangeOrderState($google_order_number, "archive");
}

/**
 * The CreateCancelOrder function is a wrapper function that calls the
 * ChangeOrderState function. The ChangeOrderState function, in turn,
 * creates a <cancel-order> command for the specified order, which is
 * identified by its Google Checkout order number ($google_order_number). The 
 * <cancel-order> command instructs Google Checkout to cancel an order.
 *
 * @param   $google_order_number    A number, assigned by Google Checkout, 
 *                                      that uniquely identifies an order.
 * @param   $reason                 The reason an order is being canceled
 * @param   $comment                A comment pertaining to a canceled order
 * @return  <cancel-order> XML
 */
function CreateCancelOrder($google_order_number, $reason, $comment="") {
    return ChangeOrderState($google_order_number, "cancel", $reason, 0,
        $comment);
}

/**
 * The CreateChargeOrder function is a wrapper function that calls the
 * ChangeOrderState function. The ChangeOrderState function, in turn,
 * creates a <charge-order> command for the specified order, which is
 * identified by its Google Checkout order number ($google_order_number). The
 * <charge-order> command prompts Google Checkout to charge the customer for an 
 * order and to change the order's financial order state to "CHARGING".
 *
 * @param   $google_order_number    A number, assigned by Google Checkout, 
 *                                      that uniquely identifies an order.
 * @param   $amount                 The amount that Google Checkout should 
 *                                      charge the customer
 * @return  <charge-order> XML
 */
function CreateChargeOrder($google_order_number, $amount="") {
    return ChangeOrderState($google_order_number, "charge", 0, $amount);
}

/**
 * The CreateProcessOrder function is a wrapper function that calls the
 * ChangeOrderState function. The ChangeOrderState function, in turn,
 * creates a <process-order> command for the specified order, which is
 * identified by its Google Checkout order number ($google_order_number). The
 * <process-order> command changes the order's fulfillment order state 
 * to "PROCESSING".
 *
 * @param   $google_order_number    A number, assigned by Google Checkout, 
 *                                      that uniquely identifies an order.
 * @return  <process-order> XML
 */
function CreateProcessOrder($google_order_number) {
    return ChangeOrderState($google_order_number, "process");
}

/**
 * The CreateRefundOrder function is a wrapper function that calls the
 * ChangeOrderState function. The ChangeOrderState function, in turn,
 * creates a <refund-order> command for the specified order, which is
 * identified by its Google Checkout order number ($google_order_number). The 
 * <refund-order> command instructs Google Checkout to issue a refund for an 
 * order.
 *
 * @param   $google_order_number    A number, assigned by Google Checkout, 
 *                                      that uniquely identifies an order.
 * @param   $reason                 The reason an order is being refunded
 * @param   $amount                 The amount that Google Checkout should 
 *                                      refund to the customer.
 * @param   $comment                A comment pertaining to a refunded order
 * @return  <refund-order> XML
 */
function CreateRefundOrder($google_order_number, $reason, $amount="", 
    $comment="") {

    return ChangeOrderState($google_order_number, "refund", $reason, 
        $amount, $comment);
}

/**
 * The CreateUnarchiveOrder function is a wrapper function that calls the
 * ChangeOrderState function. The ChangeOrderState function, in turn,
 * creates an <unarchive-order> command for the specified order, which is
 * identified by its Google Checkout order number ($google_order_number). The 
 * <unarchive-order> command moves an order from the Archive folder in the
 * Google Checkout Merchant Center to the Inbox.
 *
 * @param   $google_order_number    A number, assigned by Google Checkout, 
 *                                      that uniquely identifies an order.
 * @return  <unarchive-order> XML
 */
function CreateUnarchiveOrder($google_order_number) {
    return ChangeOrderState($google_order_number, "unarchive");
}

/**
 * The CreateDeliverOrder function is a wrapper function that calls the
 * ChangeShippingInfo function. The ChangeShippingInfo function, in turn,
 * creates an <deliver-order> command for the specified order, which is
 * identified by its Google Checkout order number ($google_order_number). The 
 * <deliver-order> command changes the order's fulfillment order state 
 * to "DELIVERED". It can also be used to add shipment tracking information
 * for an order.
 *
 * @param   $google_order_number    A number, assigned by Google Checkout, 
 *                                      that uniquely identifies an order.
 * @param   $carrier                The carrier handling an order shipment
 * @param   $tracking_number        The tracking number assigned to an
 *                                      order shipment by the shipping carrier
 * @param   $send_email             A Boolean value that indicates whether
 *                                    Google Checkout should email the customer
 *                                    when the <deliver-order> command is
 *                                    processed for the order.     
 * @return  <deliver-order> XML
 */
function CreateDeliverOrder($google_order_number, $carrier="", 
    $tracking_number="", $send_email="true") {
    return ChangeShippingInfo($google_order_number, "deliver-order",
        $carrier, $tracking_number, $send_email);
}

/**
 * The CreateAddTrackingData function is a wrapper function that calls the
 * ChangeShippingInfo function. The ChangeShippingInfo function, in turn,
 * creates an <add-tracking-data> command for the specified order, which is
 * identified by its Google Checkout order number ($google_order_number). The 
 * <add-tracking-data> command adds shipment tracking information to an order.
 *
 * @param   $google_order_number    A number, assigned by Google Checkout, 
 *                                      that uniquely identifies an order.
 * @param   $carrier                The carrier handling an order shipment
 * @param   $tracking_number        The tracking number assigned to an
 *                                      order shipment by the shipping carrier
 * @return  <add-tracking-data> XML
 */
function CreateAddTrackingData($google_order_number, $carrier, 
    $tracking_number) {

    return ChangeShippingInfo($google_order_number, "add-tracking-data",
        $carrier, $tracking_number);

}

/**
 * The ChangeOrderState function creates XML documents used to send 
 * Order Processing API commands to Google Checkout. This function creates 
 * the XML for the following commands:
 *         <archive-order>
 *         <cancel-order>
 *         <charge-order>
 *         <process-order>
 *         <refund-order>
 *         <unarchive-order>
 * 
 * @param   $google_order_number    A number, assigned by Google Checkout, 
 *                                      that uniquely identifies an order.
 * @param   $function_name          The type of command that should be
 *                                      created. Valid values for this
 *                                      parameter are "archive", "cancel",
 *                                      "charge", "process", "refund" and
 *                                      "unarchive".
 * @param   $reason                 The reason an order is being refunded
 * @param   $amount                 The amount that Google Checkout should 
 *                                      charge or refund to the customer.
 * @param   $comment                A comment pertaining to a refunded order
 * @return  XML corresponding to the specified $function_name
 */
function ChangeOrderState($google_order_number, $function_name, $reason="", 
    $amount="", $comment="") {

    /*
     * Verify that the necessary parameter values have been provided.
     * The $google_order_number and $function_name parameters are
     * required for all commands. The $reason parameter is required 
     * for <cancel-order> and <refund-order> commands. In addition,
     * if an $amount is provided for either the <charge-order> or
     * <refund-order> commands, then the $GLOBALS["currency"] variable
     * must also have a value.
     */
    $error_function_name = "ChangeOrderState(" . $function_name . ")";
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "google_order_number", $google_order_number);
    if ($function_name == "cancel" || $function_name == "refund") {
        CheckForError($GLOBALS["mp_type"], $error_function_name,
            "reason", $reason);
    }

    if ($function_name == "charge" || $function_name == "refund") {
        $error_type = "MISSING_CURRENCY";
        if ($amount != "" && $GLOBALS["currency"] == "") {
            trigger_error(error_msg($error_type, $error_function_name), 
                E_USER_ERROR);
        }
    }

    $dom_order_obj = domxml_new_doc("1.0");

    /*
     * Create the root tag for the Order Processing API command. 
     * Also set the "xmlns" and "google-order-number" attributes 
     * on that element.
     */
    $dom_order = $dom_order_obj->append_child(
        $dom_order_obj->create_element($function_name."-order"));
    $dom_order->set_attribute("xmlns", $GLOBALS["schema_url"]);
    $dom_order->set_attribute("google-order-number", $google_order_number);

    // Add <reason> element to <cancel-order> and <refund-order> commands
    if ($function_name == "cancel" || $function_name == "refund") {
        $dom_reason = $dom_order->append_child(
            $dom_order_obj->create_element("reason"));
        $dom_reason->append_child($dom_order_obj->create_text_node($reason));
    }

    // Add <amount> element to <charge-order> and <refund-order> commands
    if (($function_name == "charge" || $function_name == "refund") && 
        $amount != "")
    {

        $dom_amount = $dom_order->append_child(
            $dom_order_obj->create_element("amount"));
        $dom_amount->set_attribute("currency", $GLOBALS["currency"]);
        $dom_amount->append_child($dom_order_obj->create_text_node($amount));
    }

    // Add <comment> element
    if ($comment != "") {
        $dom_comment = $dom_order->append_child(
            $dom_order_obj->create_element("comment"));
        $dom_comment->append_child(
            $dom_order_obj->create_text_node($comment));
    }

    return $dom_order_obj->dump_mem();
}

/**
 * The ChangeShippingInfo function creates XML documents used to send 
 * Order Processing API commands to Google Checkout. This function creates 
 * the XML for the following commands:
 *         <deliver-order>
 *         <add-tracking-data>
 * 
 * @param   $google_order_number    A number, assigned by Google Checkout, 
 *                                      that uniquely identifies an order.
 * @param   $function_name          The type of command that should be
 *                                      created. Valid values for this
 *                                      parameter are "deliver" and
 *                                      "add-tracking-data".
 * @param   $carrier                The carrier handling an order shipment
 * @param   $tracking_number        The tracking number assigned to an
 *                                      order shipment by the shipping carrier
 * @param   $send_email             A Boolean value that indicates whether
 *                                    Google Checkout should email the customer
 *                                    when the <deliver-order> command is
 *                                    processed for the order.
 * @return  XML corresponding to the specified $function_name
 */
function ChangeShippingInfo($google_order_number, $function_name, 
    $carrier="", $tracking_number="", $send_email="") {

    /*
     * Verify that the necessary parameter values have been provided.
     * The $google_order_number and $function_name parameters are
     * required for all commands. For the <deliver-order> command, the
     * $carrier and $tracking_number parameters are optional; however,
     * if the $carrier is provided, then a $tracking_number must also
     * be provided. For the <add-tracking-data> command, the $carrier
     * and $tracking_number parameters are both required.
     */
    $error_function_name = "ChangeShippingInfo(" . $function_name . ")";
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "google_order_number", $google_order_number);

    // Tracking information is optional for deliver-order, 
    // but required for add-tracking-data
    if ($function_name == "deliver-order") {
        // Check for missing tracking number when carrier is set
        $error_type = "MISSING_TRACKING";
        if ($carrier != "" && $tracking_number == "") {
            trigger_error(error_msg($error_type, $error_function_name), 
                E_USER_ERROR);
        }
    } elseif ($function_name == "add-tracking-data") {
        CheckForError($GLOBALS["mp_type"], $error_function_name, 
            "carrier", $carrier);
        CheckForError($GLOBALS["mp_type"], $error_function_name, 
            "tracking_number", $tracking_number);
    }

    $dom_shipping_obj = domxml_new_doc("1.0");

    /*
     * Create the root tag for the Order Processing API command. 
     * Also set the "xmlns" and "google-order-number" attributes 
     * on that element.
     */
    $dom_shipping = $dom_shipping_obj->append_child(
        $dom_shipping_obj->create_element($function_name));
    $dom_shipping->set_attribute("xmlns", $GLOBALS["schema_url"]);
    $dom_shipping->set_attribute("google-order-number", $google_order_number);

    // Add the <carrier> and <tracking-number> elements
    if ($carrier != "") {
        $dom_tracking_data = 
            $dom_shipping->append_child(
                $dom_shipping_obj->create_element("tracking-data"));

        $dom_carrier = 
            $dom_tracking_data->append_child(
                $dom_shipping_obj->create_element("carrier"));

        $dom_carrier->append_child(
            $dom_shipping_obj->create_text_node($carrier));

        $dom_comment = $dom_tracking_data->append_child(
            $dom_shipping_obj->create_element("tracking-number"));
        $dom_comment->append_child(
            $dom_shipping_obj->create_text_node($tracking_number));
    }

    // Add the <send-email> element to the command
    if ($send_email != "") {
        $dom_send_email = $dom_shipping->append_child(
            $dom_shipping_obj->create_element("send-email"));
        $dom_send_email->append_child(
            $dom_shipping_obj->create_text_node($send_email));
    }

    return $dom_shipping_obj->dump_mem();
}

/**
 * The CreateAddMerchantOrderNumber function creates the XML for the
 * <add-merchant-order-number> Order Processing API command. This command
 * is used to associate the Google order number with the ID that the
 * merchant assigns to the same order.
 *  
 * @param   $google_order_number      A number, assigned by Google Checkout, 
 *                                      that uniquely identifies an order.
 * @param   $merchant_order_number    A string, assigned by the merchant,
 *                                      that uniquely identifies the order.
 * @return  <add-merchant-order-number> XML
 */
function CreateAddMerchantOrderNumber($google_order_number, 
    $merchant_order_number) {
    
    $dom_add_merchant_order_number_obj = domxml_new_doc("1.0");

    /*
     * Create the root tag for the Order Processing API command. 
     * Also set the "xmlns" and "google-order-number" attributes 
     * on that element.
     */
    $dom_add_merchant_order_number = 
        $dom_add_merchant_order_number_obj->append_child(
            $dom_add_merchant_order_number_obj->create_element(
                "add-merchant-order-number"));
    $dom_add_merchant_order_number->set_attribute(
        "xmlns", $GLOBALS["schema_url"]);
    $dom_add_merchant_order_number->set_attribute(
        "google-order-number", $google_order_number);

    // Add the <merchant-order-number> element
    $dom_merchant_order_number = 
        $dom_add_merchant_order_number->append_child(
            $dom_add_merchant_order_number_obj->create_element(
                "merchant-order-number"));

    $dom_merchant_order_number->append_child(
        $dom_add_merchant_order_number_obj->create_text_node(
            $merchant_order_number));

    return $dom_add_merchant_order_number_obj->dump_mem();
}

/**
 * The CreateSendBuyerMessage function creates the XML for the 
 * <send-buyer-message> Order Processing API command. This command 
 * is used to send a message to a customer.
 *
 * @param   $google_order_number    A number, assigned by Google Checkout, 
 *                                    that uniquely identifies an order.
 * @param   $message                The text of the message that you
 *                                    want to send to the customer
 * @param   $send_email             A Boolean value that indicates whether
 *                                    Google Checkout should email the customer
 *                                    with this message
 * @return  <send-buyer-message> XML
 */
function CreateSendBuyerMessage($google_order_number, $message, 
    $send_email="") {

    // The $google_order_number and $message parameters must have values
    $error_function_name = "CreateSendBuyerMessage()";
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "google_order_number", $google_order_number);
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "message", $message);

    $dom_send_buyer_message_obj = domxml_new_doc("1.0");

    // Create the root element for the <send-buyer-message> command
    $dom_send_buyer_message = 
        $dom_send_buyer_message_obj->append_child(
            $dom_send_buyer_message_obj->create_element("send-buyer-message"));
    $dom_send_buyer_message->set_attribute("xmlns", $GLOBALS["schema_url"]);
    $dom_send_buyer_message->set_attribute(
        "google-order-number", $google_order_number);

    // Add the <message> element to the command
    $dom_message = $dom_send_buyer_message->append_child(
        $dom_send_buyer_message_obj->create_element("message"));
    $dom_message->append_child(
        $dom_send_buyer_message_obj->create_text_node($message));
    
    // Add the <send-email> element to the command
    if ($send_email != "") {
        $dom_send_email = $dom_send_buyer_message->append_child(
            $dom_send_buyer_message_obj->create_element("send-email"));
        $dom_send_email->append_child(
            $dom_send_buyer_message_obj->create_text_node($send_email));
    }

    return $dom_send_buyer_message_obj->dump_mem();
}

/** End of file **/

?>