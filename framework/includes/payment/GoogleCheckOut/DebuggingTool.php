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
 */

// Include Google Checkout PHP Client Library
include ("GlobalAPIFunctions.php");
include ("ResponseHandlerAPIFunctions.php");

if (($_SERVER['REQUEST_METHOD'] == 'POST') && 
    ($_POST["openFile"] == "false")) {

    // Get the Cart XML
    if(get_magic_quotes_gpc()) {
        $xml = stripslashes(trim($_POST['xml']));
    } else {
        $xml = trim($_POST['xml']);
    }

    if ($_POST["toolType"] == "Submit Cart to Google Checkout") {

        $transmit_response = SendRequest($xml, $GLOBALS["request_url"]);
        ProcessXmlData($transmit_response);
        die();

    } elseif ($_POST["toolType"] == "Display HTML Form for Checkout") {

        // 2. Calculate the HMAC-SHA1 signature
        $signature = CalcHmacSha1($xml);

        // Base64-encode the shopping cart XML and signature before posting
        $b64_cart = base64_encode($xml);
        $b64_signature = base64_encode($signature);

        $checkout_post_data = "cart=" . urlencode($b64_cart) . 
        "&signature=" . urlencode($b64_signature);

        // Set up the form to POST the cart
 
        // Log <checkout-shopping-cart XML
        LogMessage($GLOBALS["logfile"], $checkout_post_data);

/*
 * The following HTML page displays some information about the POST
 * request that will be submitted to Google Checkout if you click the 
 * Google Checkout button that appears on the page. The Google Checkout 
 * button is embedded in a form similar to the form you want to include 
 * on your site. The form sends the request to Google Checkout and shows 
 * you an interface similar to what your customer would see after clicking 
 * the Google Checkout button.
 *
 * Note: This page also calls the DisplayDiagnoseResponse function,
 * which is defined in GlobalAPIFunctions.php, to verify that the 
 * API request contains valid XML. If the request does not contain
 * valid XML, you will see a link to a tool that lets you edit and
 * recheck the XML. The code for that tool is in the 
 * <b>CheckoutShoppingCartTool.php</b> file, which is also included
 * in the <b>PHP.zip</b> file.
 */
?>
<html>
<head>
    <style type="text/css">@import url(googleCheckout.css);</style>
</head>
<body>
    <p style="text-align:center">
    <table class="table-1" cellspacing="5" cellpadding="5">
        <tr><td style="padding-bottom:20px"><h2>
        Place a New Order
        </h2></td></tr>

        <tr><td>

            <!-- Print the steps for posting a shopping cart XML -->
            <p><b>Follow these steps to post an XML shopping cart:</b></p>
            <p><ol>
                <li>Create a &lt;checkout-shopping-cart&gt; XML structure
                    containing information about the buyer's order.</li>
                <li>Create an HMAC_SHA1 signature for the shopping cart.
                    The CalcHmacSha1 function in GlobalAPIFunctions.php
                    can be used to generate this signature.</li>
                <li>Base64-encode the shopping cart XML.</li>
                <li>Base64-encode the HMAC_SHA1 signature.</li>
                <li>Put the cart and signature into a form that displays
                    a Google Checkout button.</li>
            </ol></p>
            <p>&nbsp;</p>

            <!-- Print the shopping cart XML -->
            <p><b>1. &lt;checkout-shopping-cart&gt; XML:</b></p>
            <p><?php echo htmlentities($xml); ?></p>
            <p>&nbsp;</p>

            <!-- Print the HMAC-SHA1 signature in binary -->
            <p><b>2. HMAC-SHA1 Signature:</b></p>
            <p><?php echo htmlentities($signature); ?></p>
            <p>&nbsp;</p>

            <!-- Print the base64-encoded shopping cart XML -->
            <p><b>3. Base64-encoded &lt;checkout-shopping-cart&gt; XML:</b></p>
            <p><?php echo htmlentities($b64_cart); ?></p>
            <p>&nbsp;</p>

            <!-- Print the base64-encoded HMAC-SHA1 signature -->
            <p><b>4. Base64-encoded HMAC-SHA1 Signature:</b></p>
            <p><?php echo htmlentities($b64_signature); ?></p>
            <p>&nbsp;</p>

        </td></tr>

        <!-- Print Error message if the shopping cart XML is invalid -->
        <?php
            DisplayDiagnoseResponse($checkout_post_data, 
                $GLOBALS["checkout_diagnose_url"], $xml, "debug");
        ?>

        <!-- Print the Google Checkout button in a form 
             containing the shopping cart data -->
        <tr><td>
            <p><b>5. Google Checkout button form.
            Click on the button to post this cart.</b></p>

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
                action="<?php echo $GLOBALS["checkout_url"]; ?>">
                <input type="hidden" name="cart" 
                value="<?php echo $b64_cart;  
                // base64-encoded <checkout-shopping-cart> XML ?>">
                <input type="hidden" name="signature" 
                value="<?php echo $b64_signature;
                // base64-encoded signature ?>">
                <input type="image" name="Checkout" alt="Checkout" 
                src="<?php echo $button_src; ?>" 
                height="<?php echo $button_h; ?>" 
                width="<?php echo $button_w; ?>">
                </form></p>
        </td></tr>
    </table>
    </p>
</body>
</html>

<?php 
        exit();
    // End: elseif ($_POST["toolType"] == "Post Cart - Browser to Server"
    }
// End: if (($_SERVER['REQUEST_METHOD'] == 'POST') && 
//      ($_POST["openFile"] == "false")) {
} elseif (($_SERVER["REQUEST_METHOD"] == "POST") && 
    ($_POST["openFile"] == "true") &&
    ($_FILES["xml"]["error"] == 0))
{

    $filename = $_FILES["xml"]["tmp_name"];
    $handle = fopen($filename, "r");
    $xml = fread($handle, $_FILES["xml"]["size"]);
    fclose($handle);

} else {
    $xml = "";
}
?>
<html>
<head>
    <style type="text/css">@import url(googleCheckout.css);</style>
</head>
<body>
    <p style="text-align:center">
    <table class="table-1" cellspacing="5" cellpadding="5">
        <tr><td width="100%" style="text-align:center">
            <h2>Google Checkout API XML Debugging Tool</h2>
        </td></tr>
        <tr><td>
            <p style="text-align:left"><b>Open XML File:</b></p>
            <p><form method="POST" 
            action="<?php echo $_SERVER['REQUEST_URI']; ?>" 
            enctype="multipart/form-data">
            <table><tr>
                <td><input type="file" name="xml" size="70"></td>
                <td><input type="hidden" name="openFile" value="true"></td>
                <td><input type="submit" value="Load XML"></td>
            </tr></table>
            </form></p>
        </td></tr>
        <tr><td style="text-align:left">
            <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            <p><b>XML:</b></p>
            <p><textarea name="xml" cols="80" 
                rows="20"><?php echo $xml; ?></textarea></p>
            <p><table style="text-align:left" cellspacing="5" cellpadding="5">
               <tr><td><input name="toolType" type="submit" 
               value="Validate XML"></td>
               <td><input name="toolType" type="submit" 
               value="Display HTML Form for Checkout"></td>
               </tr><tr>
               <td><input name="toolType" type="submit" 
               value="Send Order Processing Command"></td>
               <td><input name="toolType" type="submit" 
               value="Submit Cart to Google Checkout">
               <input type="hidden" name="openFile" value="false"></td></tr>
            </table></p>
            </form>
        </td></tr>
    </table>
    </p>
    <p style="text-align:center">
    <table class="table-1" cellspacing="5" cellpadding="5">
<?php
if (($_SERVER['REQUEST_METHOD'] == 'POST') && 
    ($_POST["openFile"] == "false") &&
    ($_POST['toolType'] == "Validate XML")) {

    // Print Error message if the cart XML is invalid
    $validated = DisplayDiagnoseResponse($xml, 
        $GLOBALS["request_diagnose_url"], $xml, "diagnose");

    if ($validated == true) { 
?>
        <tr><td colspan="2">
            <span style="text-align:center;color:green">
            <h2>This XML is Validated!</h2></span>
        </td></tr>
<?php 
    }
    echo "</table>";
} elseif (($_SERVER['REQUEST_METHOD'] == 'POST') && 
          ($_POST["openFile"] == "false") &&
          ($_POST['toolType'] == "Send Order Processing Command")) {
?>
    <table class="table-1" cellspacing="5" cellpadding="5">
        <tr><td style="padding-bottom:20px;text-align:center"><h2>
            Order Processing Command
        </h2></td></tr>
        <tr><td style="padding-bottom:20px">
            <p><b>Order Processing Command XML:</b></p>
            <p><?php echo htmlentities($xml); ?></p>
        </td></tr>
<?php
    
    // Validate Request XML
    DisplayDiagnoseResponse($xml, 
        $GLOBALS["request_diagnose_url"], $xml, "diagnose");

    echo "<tr><td style=\"padding-bottom:20px\">" .
         "<p><b>Synchronous Response Received:</b></p>";

    // Send the request and receive a response
    $transmit_response = SendRequest($xml, $GLOBALS["request_url"]);

    // Process the response
    echo "<p>" . ProcessXmlData($transmit_response) . "</p></td></tr>";
    echo "</table>";
}
?>
</p>
</body>
</html>