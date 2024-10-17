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
 * This sample code demonstrates how to utilize the 
 * Google Checkout PHP Client Library (GlobalAPIFunctions.php) to generate 
 * <checkout-shopping-cart> XML (referred below as "Cart XML") 
 * and to place a new order by posting the cart XML via server-to-server POST.
 * 
 * This alternative method of posting Checkout API requests contains
 * the following steps:
 *
 *   1. The Merchant web page displays a Google Checkout button. The button
 *          is contained in a form that submits to the merchant's server.
 *   2. When the customer clicks the Google Checkout button, the merchant's
 *          server generates the <checkout-shopping-cart> XML and posts the
 *          shopping cart directly to the Google Checkout server.
 *   3. The Google Checkout server responds to the merchant's request with 
 *          a <checkout-redirect> XML response, which contains a <redirect-url>.
 *   4. The Merchant returns an HTTP 302 redirect to the customer's browser.
 *   5. The customer's browser follows the redirect, bringing the customer
 *          to the Google Checkout order page.
 *
 */

/*
 * Include Google Checkout PHP Client Library and other libraries 
 * used to create shopping cart and send server-to-server request.
 */
include ("GlobalAPIFunctions.php");
include ("CheckoutAPIFunctions.php");
include ("ResponseHandlerAPIFunctions.php");

/*
 * This page posts requests to itself. When you navigate to this page,
 * it will display an HTML page containing information about server-to-server
 * Checkout API requests. It will also display a Google Checkout button.
 * The HTML for this page is contained within the following "if" block.
 * When you click the Google Checkout button, the page will submit an
 * HTTP POST request to itself. This request will set the
 * $_SERVER["REQUEST_METHOD"] variable equal to "POST". As such, the HTML
 * form will not display and the code within the "elseif" portion of this
 * "if-elseif" block will execute. That code executes a server-to-server
 * HTTP POST request and then redirects the user to the <redirect-url>
 * returned in the Google Checkout <checkout-redirect> response.
 *
 * +++ CHANGE ME +++
 * If your site submits server-to-server Checkout API requests, you may want
 * to change the form's action so that the form submits to a different page.
 */

if ($_SERVER["REQUEST_METHOD"] != "POST") {
?>

<html>
<head>
    <style type="text/css">@import url(googleCheckout.css);</style>
</head>
<body>
    <p style="text-align:center">
    <table class="table-1" cellspacing="5" cellpadding="5">
        <tr><td style="padding-bottom:20px"><h2>
        Place a New Order using the Alternative Method
        </h2></td></tr>

        <tr><td>
            <p><b>The alternative method of posting Checkout API requests 
                  contains the following steps:</b></p>
            <p><ol>
              <li>The Merchant web page displays a Google Checkout button. 
                  The button is contained in a form that submits to the 
                  merchant's server.</li>
              <li>When the customer clicks the Google Checkout button, the 
                  merchant's server generates the 
                  &lt;checkout-shopping-cart&gt; XML and posts the shopping 
                  cart directly to the Google Checkout server.</li>
              <li>The Google Checkout server responds to the merchant's 
                  request with a &lt;checkout-redirect&gt; XML response, which 
                  contains a &lt;redirect-url&gt;.</li>
              <li>The Merchant returns an HTTP 302 redirect to the 
                  customer's browser.</li>
              <li>The customer's browser follows the redirect, bringing 
                  the customer to the Google Checkout order page.</li>
            </ol></p>
            <p>&nbsp;</p>

            <p><b>Click on the Checkout button to post a cart using this 
            Alternative Method.</b></p>

            <?php
                // Google Checkout button implementation
                $button_w = "180";
                $button_h = "46";
                $button_style = "white";
                $button_variant = "text";
                $button_loc = "en_US";
                $button_src = 
                    "https://sandbox.google.com/buttons/checkout.gif" . 
                    "?merchant_id=" . $GLOBALS["merchant_id"] . 
                    "&w=" . $button_w . 
                    "&h=" . $button_h . 
                    "&style=" . $button_style . 
                    "&variant=" . $button_variant . 
                    "&loc=" . $button_loc;
            ?>

            <p><form method="POST" 
                action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                <input type="image" name="Checkout" alt="Checkout" 
                src="<?php echo $button_src; ?>" height="46" width="180">
                </form></p>

        </td></tr>
    </table>
    </p>
</body>
</html>
<?
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {

/**
 * Build XML for items in the shopping cart. The shopping cart
 * has the following structure:
 *
 * <shopping-cart>
 *     <items>
 *         <item>
 *             <item-name>Dry Food Pack AA1453</item-name>
 *             <item-description>A pack of nutritious food.</item-description>
 *             <quantity>1</quantity>
 *             <unit-price currency="USD">35.00</unit-price>
 *             <tax-table-selector>food</tax-table-selector>
 *             <merchant-private-item-data>
 *               <item-note>Product Number N15037124531</item-note>
 *             </merchant-private-item-data>
 *         </item>
 *         <!-- More items may be included using the same XML structure -->
 *     </items>
 * </shopping-cart>
 */

/*
 * The XML for an individual item is created by defining data fields
 * for the item and then calling the CreateItem() function, which
 * is in the CheckoutAPIFunctions.php file.
 *
 * +++ CHANGE ME +++
 * You will need to modify calls to functions like CreateItem,
 * AddAllowedAreas, AddExcludedAreas, CreateFlatRateShipping and
 * numerous others in this file to reflect the items in the 
 * customer's shopping cart, the shipping options available for
 * those items and the tax tables that you use to calculate taxes.
 */

// Specify item data and create an item to include in the order
$item_name = "Dry Food Pack AA1453"; 
$item_description = "A pack of highly nutritious dried food for emergency.";
$quantity = "1";
$unit_price = "35.00";
$tax_table_selector = "food";
$merchant_private_item_data = "";
CreateItem($item_name, $item_description, $quantity, $unit_price, 
    $tax_table_selector, $merchant_private_item_data);

// Specify item data and create a second item to include in the order
$item_name = "MegaSound 2GB MP3 Player";
$item_description = "Portable MP3 player - stores 500 songs, easy-to-use.";
$quantity = "1";
$unit_price = "178.00";
$tax_table_selector = "";
$merchant_private_item_data = 
    "<item-note>Product Number N15037124531</item-note>";
CreateItem($item_name, $item_description, $quantity, $unit_price, 
    $tax_table_selector, $merchant_private_item_data);

// Specify an expiration date for the order and build <shopping-cart>
$cart_expiration = "2006-12-31T23:59:59";
$merchant_private_data = 
    "<merchant-note>My order number 9876543</merchant-note>";
CreateShoppingCart($cart_expiration, $merchant_private_data);

// Create list of areas where a particular shipping option is available
$allowed_country_area = "ALL";    // OR: "CONTINENTAL_48", "FULL_50_STATES"
$allowed_state = array();        // Ex: array("CA", "NY", "DC", "NC")
$allowed_zip = array();        // Ex: array("94043", "94086", "91801", "91362")
$shipping_restrictions = AddAllowedAreas($allowed_country_area, 
    $allowed_state, $allowed_zip);
  
// Create list of areas where a particular shipping option is not available
$excluded_country_area = "";
$excluded_state = array("AL", "MA", "MT", "WA");
$excluded_zip = array();
$shipping_restrictions = AddExcludedAreas($excluded_country_area, 
    $excluded_state, $excluded_zip);

// Create a <flat-rate-shipping> option with shipping restrictions
$name = "UPS Ground";
$price = "8.50";
CreateFlatRateShipping($name, $price, $shipping_restrictions);

/*
 * The call to the CreateMerchantCalculatedShipping function is commented out 
 * because a shopping cart can not contain a <merchant-calculated-shipping> 
 * option as well as other types of shipping options. 
 * To use  <merchant-calculated-shipping> options with this demo, you would 
 * need to  uncomment the next four lines of code and also comment out the 
 * calls to the CreateFlatRateShipping and CreatePickup functions.
 *
 * $name = "SuperShip";
 * $price = "10.00";
 * $shipping_restrictions = "";
 * CreateMerchantCalculatedShipping($name, $price, $shipping_restrictions);
 */

// Create a <pickup> shipping option
$name = "Pickup";
$price = "0.00";
CreatePickup($name, $price);

/*
 * Create tax tables for the order. Tax tables have the
 * following XML structure:
 * <tax-tables>
 *     <default-tax-table>
 *         <tax-rules>
 *             <default-tax-rule>
 *                 <shipping-taxed>true</shipping-taxed>
 *                 <rate>0.0825</rate>
 *                 <tax-area>
 *                     <!-- could also contain country or zip areas>
 *                     <us-state-area>
 *                         <state>NY</state>
 *                     </us-state-area>
 *                 </tax-area>
 *             </default-tax-rule>
 *         </tax-rules>
 *     </default-tax-table>
 *     <alternate-tax-tables>
 *         <alternate-tax-table>
 *             <alternate-tax-rules>
 *                 <alternate-tax-rule>
 *                     <rate>0.0825</rate>
 *                     <tax-area>
 *                         <!-- could also contain country or zip areas>
 *                         <us-state-area>
 *                             <state>NY</state>
 *                         </us-state-area>
 *                     </tax-area>
 *                 </alternate-tax-rule>
 *             </alternate-tax-rules>
 *         </alternate-tax-table>
 *     </alternate-tax-tables>
 * </tax-tables>
 *
 * +++ CHANGE ME +++
 * You will need to update the tax tables to match those
 * used to calculate taxes for your store
 */

$rate = "0.0825";
$tax_area_country = "ALL";
$tax_area = CreateTaxArea("country", $tax_area_country);
$shipping_taxed = "false";
CreateDefaultTaxRule($rate, $tax_area, $shipping_taxed);
    
$rate = "0.0800";
$tax_area_state = "NY";
$tax_area = CreateTaxArea("state", $tax_area_state);
$shipping_taxed = "true";
CreateDefaultTaxRule($rate, $tax_area, $shipping_taxed);
    
$rate = "0.0225";
$tax_area_state = "CA";
$tax_area = CreateTaxArea("state", $tax_area_state);
CreateAlternateTaxRule($rate, $tax_area);

$rate = "0.0200";
$tax_area_state = "NY";
$tax_area = CreateTaxArea("state", $tax_area_state);
CreateAlternateTaxRule($rate, $tax_area);
                    
$standalone = "false";
$name = "food";
CreateAlternateTaxTable($standalone, $name);

$rate = "0.0500";
$tax_area_country = "FULL_50_STATES";
$tax_area = CreateTaxArea("country", $tax_area_country);
CreateAlternateTaxRule($rate, $tax_area);

$rate = "0.0600";
$tax_area_zip = "9404*";
$tax_area = CreateTaxArea("zip", $tax_area_zip);
CreateAlternateTaxRule($rate, $tax_area);

$standalone = "true";
$name = "drug";
CreateAlternateTaxTable($standalone, $name);

$merchant_calculated = "false";
CreateTaxTables($merchant_calculated);

/*
 * Specify A URL to which Google Checkout should send Merchant Calculations
 * API (<merchant-calculation-callback>) requests and create the
 * <merchant-calculations> XML for a Checkout API request.
 *
 * +++ CHANGE ME +++
 * If you are implementing the Merchant Calculations API, you need to
 * uncomment the following lines of code, which create the
 * <merchant-calculations> XML in a Checkout API response. You also
 * need to update the value of the $merchant_calculations_url variable
 * to the URL to which Google Checkout should send 
 * <merchant-calculation-callback> requests.
 */

/*
$merchant_calculations_url = 
    "http://www.example.com/shopping/MerchantCalculationCallback.php";
$accept_merchant_coupons = "true";
$accept_gift_certificates = "true";
CreateMerchantCalculations($merchant_calculations_url, 
    $accept_merchant_coupons, $accept_gift_certificates);
*/

/*
 * +++ CHANGE ME +++
 * The $edit_cart_url variable identifies a URL that the customer can 
 * link to to edit the contents of the shopping cart.
 * The $continue_shopping_url variable identifies a URL that the
 * customer can link to to continue shopping.
 * If you are providing of these options to your customers, you need 
 * to insert the appropriate URLs for these variables.
 * e.g. $edit_cart_url = "http://www.example.com/shopping/edit";
 *      $continue_shopping_url = "http://www.example.com/shop/continue";
 */

$edit_cart_url = "";
$continue_shopping_url = "";

/*
 * Build the <merchant-checkout-flow-support> element in the CHeckout
 * API request.
 */
CreateMerchantCheckoutFlowSupport($edit_cart_url, $continue_shopping_url);

// Get <checkout-shopping-cart> XML
$xml_cart = CreateCheckoutShoppingCart();

/*
 * Post the cart XML via a server-to-server POST and 
 * capture the <checkout-redirect> response.
 */
$transmit_response = SendRequest($xml_cart, $GLOBALS["request_url"]);

// Process the response by redirecting the user to the checkout redirect URL
ProcessXmlData($transmit_response);
}
?>
