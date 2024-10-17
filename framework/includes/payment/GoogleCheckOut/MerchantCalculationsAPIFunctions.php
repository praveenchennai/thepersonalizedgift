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
 * "MerchantCalculationsAPIFunctions.php" contains a set of functions that
 * process <merchant-calculation-callback> message 
 * and build <merchant-calculation-result> message.
 */


/**
 * The ProcessMerchantCalculationCallback function handles a 
 * <merchant-calculation-callback> request and returns a 
 * <merchant-calculation-results> XML response. This function calls 
 * the CreateMerchantCalculationResults function, which constructs 
 * the <merchant-calculation-results> response. This function then 
 * prints the <merchant-calculation-results> response to return the 
 * <merchant-calculation-results> information to Google Checkout and logs the 
 * response as well.
 * 
 * @param  $dom_mc_callback_obj      <merchant-calculation-callback> XML
 */
function ProcessMerchantCalculationCallback($dom_mc_callback_obj) {

    /*
     * Process <merchant-calculation-callback> and create 
     * <merchant-calculation-results>
     */
    $xml_mc_results = CreateMerchantCalculationResults($dom_mc_callback_obj);

    // Respond with <merchant-calculation-results> XML
    echo $xml_mc_results;

    // Log <merchant-calculation-results>
    LogMessage($GLOBALS["logfile"], $xml_mc_results);
}

/**
 * The CreateMerchantCalculationResults function creates the XML DOM for 
 * a <merchant-calculation-results> XML response. This function receives 
 * the <merchant-calculation-callback> from the 
 * ProcessMerchantCalculationCallback function.
 *
 * This function calls the CreateMerchantCodeResults, GetShippingRate 
 * and GetTaxRate functions to calculate shipping costs, taxes and 
 * discounts that should be applied to the order total
 *
 * @param   $dom_mc_callback_obj   <merchant-calculation-callback> XML DOM
 * @return  <merchant-calculation-results> XML
 */
function CreateMerchantCalculationResults($dom_mc_callback_obj) {

    // Create an empty XMLDOM
    $dom_mc_results_obj = domxml_new_doc("1.0");

    // Create root tag for <merchant-calculation-results> response
    $dom_mc_results = $dom_mc_results_obj->append_child(
        $dom_mc_results_obj->
            create_element("merchant-calculation-results"));
    $dom_mc_results->set_attribute("xmlns", $GLOBALS["schema_url"]);

    // Create child element <results>
    $dom_results = 
        $dom_mc_results->append_child(
            $dom_mc_results_obj->create_element("results"));

    $dom_data = $dom_mc_callback_obj->document_element();

    /*
     * Retrieve Boolean value indicating whether merchant calculates
     * tax for the order.
     *     e.g. <tax>true</tax>
     * If you do not use custom calculations to calculate tax, you 
     * may ignore the next two lines of code.
     */
    $dom_tax_list = $dom_data->get_elements_by_tagname("tax");
    $calc_tax = $dom_tax_list[0]->get_content();

    /*
     * Retrieve the names of the shipping methods available for the
     * order. These shipping methods will have been communicated to Google 
     * Checkout in a CheckoutAPIRequest. Note: The 
     * <merchant-calculated-callback> will only contain 
     * <merchant-calculated-shipping> options from the Checkout API request.
     */
    $dom_method_list = $dom_data->get_elements_by_tagname("method");

    /*
     * Retrieve shipping addresses from the <merchant-calculated-callback>
     * response. These shipping addresses are anonymous, meaning they 
     * only include the city, region (state), postal code and country
     * code for the address
     */
    $dom_anonymous_address_list = 
        $dom_data->get_elements_by_tagname("anonymous-address");

    /*
     * Retrieve a list of coupon and gift certificate codes that 
     * should be applied to the order total. Note: The
     * <merchant-calculated-callback> can only contain these codes if
     * the <accept-merchant-coupons> or <accept-gift-certificates> tag
     * in the corresponding Checkout API request has a value of "true".
     */
    $dom_merchant_code_list = 
        $dom_data->get_elements_by_tagname("merchant-code-string");

    // Loop through address IDs to build <result> elements
    foreach ($dom_anonymous_address_list as $dom_anonymous_address) {

        // Retrieve the address ID
        $address_id = $dom_anonymous_address->get_attribute("id");

        /*
         * If there are merchant-calculated shipping methods, build 
         * a <result> element for each shipping method-address ID 
         * combination. If there are no shipping methods, build a
         * <result> element that contains calculations for each address ID.
         */
        if (sizeof($dom_method_list) > 0) {

            // Loop for each merchant-calulated shipping method
            foreach ($dom_method_list as $dom_method) {

                // Retrieve the name of the shipping method
                $shipping_name = $dom_method->get_attribute("name");

                /*
                 * Create a <result> element in the response with
                 * shipping-name and address-id attributes
                 */
                $dom_result = $dom_results->append_child(
                    $dom_mc_results_obj->create_element("result"));
                $dom_result->set_attribute("shipping-name", $shipping_name);
                $dom_result->set_attribute("address-id", $address_id);

                /*
                 * If the <tax> tag in the <merchant-calculation-callback>
                 * has a value of "true", call the GetTaxRate function
                 * to calculate taxes for the order.
                 */
                if ($calc_tax == "true") {
                    $dom_total_tax = $dom_result->append_child(
                        $dom_mc_results_obj->create_element("total-tax"));
                    $dom_total_tax->set_attribute(
                        "currency", $GLOBALS["currency"]);
                    $total_tax = GetTaxRate($dom_mc_callback_obj, $address_id);
                    $dom_total_tax->append_child(
                        $dom_mc_results_obj->create_text_node($total_tax));
                }

                /*
                 * If there are coupon or gift certificate codes, call
                 * the CreateMerchantCodeResults function to verify those
                 * codes and to create <coupon-result> or 
                 * <gift-certificate-result> elements to be included in
                 * the <merchant-calculation-response>.
                 */
                if (sizeof($dom_merchant_code_list) > 0) {
                    $dom_merchant_code_results = 
                        CreateMerchantCodeResults($dom_mc_callback_obj, 
                            $dom_merchant_code_list, $address_id);
                    $dom_merchant_code_results_root = 
                        $dom_merchant_code_results->document_element();
                    $dom_result->append_child(
                        $dom_merchant_code_results_root->clone_node(true));
                }

                /*
                 * Call the GetShippingRate function to calculate the
                 * shipping cost for the shipping method-address ID 
                 * combination.
                 */
                $dom_shipping_rate = $dom_result->append_child(
                    $dom_mc_results_obj->create_element("shipping-rate"));
                $dom_shipping_rate->set_attribute(
                    "currency", $GLOBALS["currency"]);
                $shipping_rate = 
                    GetShippingRate($dom_mc_callback_obj, $address_id, $shipping_name);
                $dom_shipping_rate->append_child(
                    $dom_mc_results_obj->create_text_node($shipping_rate));

                // Verify that the order can be shipped to the address
                $shippable = 
                    VerifyShippable($dom_mc_callback_obj, $address_id, $shipping_name);
                $dom_shippable = $dom_result->append_child(
                    $dom_mc_results_obj->create_element("shippable"));
                $dom_shippable->append_child(
                    $dom_mc_results_obj->create_text_node($shippable));
            }

        // This block executes if no shipping methods are specified
        } else {

            /*
             * Create a <result> element in the response with
             * address-id attribute
             */
            $dom_result = $dom_results->append_child(
                $dom_mc_results_obj->create_element("result"));
            $dom_result->set_attribute("address-id", $address_id);

            /*
             * If the <tax> tag in the <merchant-calculation-callback>
             * has a value of "true", call the GetTaxRate function
             * to calculate taxes for the order.
             */
            if ($calc_tax == "true") {
                $dom_total_tax = $dom_result->append_child(
                    $dom_mc_results_obj->create_element("total-tax"));
                $dom_total_tax->set_attribute(
                    "currency", $GLOBALS["currency"]);
                $total_tax = GetTaxRate($dom_mc_callback_obj, $address_id);
                $dom_total_tax->append_child(
                    $dom_mc_results_obj->create_text_node($total_tax));
            }

            /*
             * If there are coupon or gift certificate codes, call
             * the CreateMerchantCodeResults function to verify those
             * codes and to create <coupon-result> or 
             * <gift-certificate-result> elements to be included in
             * the <merchant-calculation-response>.
             */
            if (sizeof($dom_merchant_code_list) > 0) {

                $dom_merchant_code_results = 
                    CreateMerchantCodeResults($dom_mc_callback_obj, 
                        $dom_merchant_code_list, $address_id);

                $dom_merchant_code_results_root = 
                    $dom_merchant_code_results->document_element();
                $dom_result->append_child(
                    $dom_merchant_code_results_root->clone_node(true));
            }
        }
    }

    // Return <merchant-calculation-results> XML
    return $dom_mc_results_obj->dump_mem();
}

/**
 * The CreateMerchantCodeResults function creates the <merchant-code-results>
 * XML DOM for a Merchant Calculations API response. This function calls 
 * the GetMerchantCodeInfo function, which you will need to modify, to 
 * retrieve information about each coupon or gift certificate code.
 *
 * @param   $dom_mc_callback_obj    <merchant-calculation-callback> XML DOM
 * @param   $dom_merchant_code_list    
 *                                  collection of merchant-code-string codes
 * @param   $address_id             An ID the corresponds to the address
 *                                      to which an order should be shipped.
 * @return  $dom_mc_results_obj     <merchant-code-results> XMLDOM
 */
function CreateMerchantCodeResults($dom_mc_callback_obj, 
    $dom_merchant_code_list, $address_id) {

    // Create an empty XMLDOM
    $dom_code_results_obj = domxml_new_doc("1.0");
    $dom_merchant_code_results = $dom_code_results_obj->append_child(
        $dom_code_results_obj->create_element("merchant-code-results"));
    
    foreach ($dom_merchant_code_list as $dom_merchant_code) {

        $code = $dom_merchant_code->get_attribute("code");

        $dom_merchant_code_result_obj = 
            GetMerchantCodeInfo($dom_mc_callback_obj, $code, $address_id);

        $dom_merchant_code_result_root = 
            $dom_merchant_code_result_obj->document_element();
        $dom_merchant_code_results->append_child(
            $dom_merchant_code_result_root->clone_node(true));
    }

    return $dom_code_results_obj;
}

/**
 * The GetMerchantCodeInfo function retrieves information about a coupon 
 * or gift certificate code provided by the customer. You will need to 
 * modify this function to retrieve information about the code. The 
 * changes you will need to make are discussed in the comments in the 
 * function. After retrieving this information, this function calls and 
 * returns the value of the CreateMerchantCodeResult function.
 *
 * @param   $dom_mc_callback_obj    <merchant-calculation-callback> XML DOM
 * @param   $code                   A coupon or gift certificate code.
 * @param   $address_id             An ID the corresponds to the address 
 *                                      to which an order should be shipped.
 * @return  merchant-calculated shipping rate
 */
function GetMerchantCodeInfo($dom_mc_callback_obj, $code, $address_id) {
    /*
     * +++ CHANGE ME +++
     * You need to modify this function to retrieve information about
     * a coupon or gift certificate code provided by the customer. This
     * function needs to retrieve the following information about the code:
     *     1. The code's type. The code type may be either "coupon" or
     *         "gift-certificate". 
     *     2. A flag that indicates whether the code is valid. The value
     *         of this flag must be either "true" or "false".
     *     3. The calculated amount of the code. If the code is valid,
     *         you need to quantify the amount of the code discount.
     *         This data is optional.
     *     4. A message that should be displayed with the code. This
     *         data is optional.
     * This function returns the result from the CreateMerchantCodeResult 
     * function, which is a <coupon-result> or a <gift-certificate-result>, 
     * to the CreateMerchantCodeResults function, which adds the XML 
     * block to the response.
     */

    $code_type = "coupon";
    $valid = "true";
    $calculated_amount = "10.00";
    $message = "You saved \$" . $calculated_amount;

    return CreateMerchantCodeResult($code_type, $valid, $code, 
        $calculated_amount, $message);
}

/**
 * The CreateMerchantCodeResult function creates the XML DOM for a
 * <coupon-result> or <gift-certificate-result> in a Merchant
 * Calculations API response.
 *
 * @param    $code_type            The type of code provided by the
 *                                     customer. Valid values are "coupon"
 *                                     and "gift-certificate".
 * @param    $valid                Indicates whether the code is valid.
 *                                     Valid values are "true" and "false".
 * @param    $code                 The code entered by the user
 * @param    $calculated_amount    The amount to deduct from the total
 * @param    $message              A message to display in regard to the code.
 * @return   $dom_code_result_obj  <coupon-result> or 
 *                                     <gift-certificate-result> XML DOM
 */
function CreateMerchantCodeResult($code_type, $valid, $code,
    $calculated_amount="", $message="") {

    // Create an empty XMLDOM
    $dom_code_result_obj = domxml_new_doc("1.0");

    // Create root tag for <coupon-result> or <gift-certificate-result>
    $dom_merchant_code_result = $dom_code_result_obj->append_child(
        $dom_code_result_obj->create_element($code_type . "-result"));

    // Create <valid> tag, which will indicate whether the code is valid
    $dom_valid = $dom_merchant_code_result->append_child(
        $dom_code_result_obj->create_element("valid"));

    // Add value for <valid> tag
    $dom_valid->append_child($dom_code_result_obj->create_text_node($valid));

    // Add the coupon or gift certificate code in a <code> tag
    $dom_code = $dom_merchant_code_result->append_child(
        $dom_code_result_obj->create_element("code"));
    $dom_code->append_child($dom_code_result_obj->create_text_node($code));

    /*
     * Add the <calculated-amount> tag if there is a value for the
     * $calculated_amount parameter. You could omit this tag if the
     * code is invalid.
     */
    if ($calculated_amount) {
        $dom_calculated_amount = $dom_merchant_code_result->append_child(
            $dom_code_result_obj->create_element("calculated-amount"));
        $dom_calculated_amount->set_attribute(
            "currency", $GLOBALS["currency"]);
        $dom_calculated_amount->append_child(
            $dom_code_result_obj->create_text_node($calculated_amount));
    }

    // Add a <message> tag if the $message parameter has a value
    if ($message) {
        $dom_message = $dom_merchant_code_result->append_child(
            $dom_code_result_obj->create_element("message"));
        $dom_message->append_child(
            $dom_code_result_obj->create_text_node($message));
    }

    return $dom_code_result_obj;
}


/**
 * The VerifyShippable function determines whether an order can be 
 * shipped to the specified address using the specified shipping method. 
 * You will need to modify this function to return a Boolean value 
 * indicating whether the order is shippable using the given shipping method.
 *
 * @param   $dom_mc_callback_obj    <merchant-calculation-callback> XML DOM
 * @param   $address_id             An ID the corresponds to the address
 *                                      to which an order should be shipped.
 * @param   $shipping_method        A shipping option for an order
 * @return  Boolean value indicating whether items can be shipped to
 *          specified address
 */
function VerifyShippable($dom_mc_callback_obj, $address_id, $shipping_method) {
    /*
     * +++ CHANGE ME +++
     * You need to modify this function to return a Boolean (true/false)
     * value that indicates whether the order can be shipped to the 
     * specified address ($address_id) using the specified shipping
     * method ($shipping_method).
     */
    return "true";
}


/**
 * The <b>GetShippingRate</b> function determines the cost of shipping 
 * the order to the specified address using the specified shipping method. 
 * You will need to modify this function to calculate and return this cost.
 *
 * @param   $dom_mc_callback_obj    <merchant-calculation-callback> XMLDOM
 * @param   $address_id             An ID the corresponds to the address 
 *                                      to which an order should be shipped.
 * @param   $shipping_method        A shipping option for an order
 * @return  merchant-calculated shipping rate
 */
function GetShippingRate($dom_mc_callback_obj, $address_id, $shipping_method) {
    /*
     * +++ CHANGE ME +++
     * You need to modify this function to return the cost of 
     * shipping an order to the specified address using the specified
     * shipping method. 
     */
    return "8.76";
}


/**
 * The GetTaxRate function returns the total tax that should be applied to
 * the order if it is shipped to the specified address. You will need to
 * modify this function to return the calculated tax amount.
 * 
 * @param   $dom_mc_callback_obj    <merchant-calculation-callback> XMLDOM
 * @param   $address_id             An ID the corresponds to the address
 *                                      to which an order should be shipped.
 * @return  merchant-calculated total tax
 */
function GetTaxRate($dom_mc_callback_obj, $address_id) {
    /*
     * +++ CHANGE ME +++
     * You need to modify this function to return the total tax for
     * an order based on the specified address.
     */
    return "17.55";
}

?>