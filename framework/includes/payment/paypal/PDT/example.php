<?php

/**
 * @author Dick Munroe <munroe@csworks.com>
 * @copyright copyright @ 2006 by Dick Munroe, Cottage Software Works, Inc.
 * @license http://www.csworks.com/publications/ModifiedNetBSD.html
 * @version 1.0.0
 * @package paypalPDT
 */

/**
 * If you notice, there is a great deal of similarity between the processing
 * of PDT data and IPN data.  Internally the only differences are:
 *
 *    1. The source of the data.
 *       IPN data is received at the IPN callback via a POST.
 *       PDT data is received at the PDT return via a GET and an interaction with Paypal.
 *
 *    2. httpPost needs to be different.
 *       It needs to interact with Paypal AND produce a source of data from which
 *       the PDT data can be extracted and processed.
 *
 *    3. processNotification needs to be different.
 *       Due to the change in interface for httpPost, the "front" end of processNotification
 *       has to be slightly different.
 *
 * All of which means that as written these classes are principally useful for sites which
 * use either PDT or IPN, but not both.
 *
 * Since the actual processing of the PDT or IPN data is functionally identical (at least for
 * most of the cases) it would be ideal if you could transparently plug in either the PDT or
 * IPN base class and do the right thing with a minimum of code duplication.
 *
 * Unfortunately this sort of thing is best done with "mixins" or with delegated interfaces.
 * Since PHP doesn't support multiple inheritance and since I REALLY dislike delagated
 * interfaces the way to reduce to code duplication burden and, more or less transparently,
 * do the same thing with either IPN or PDT invocations is to create a duplicate of the
 * paypalPDTBase class that inherits from the application IPN class, eliminate all the 
 * member functions which would normally be overridden by a super class, and things should
 * (more or less) work identically.
 *
 * I'm going to leave actually DOING this as an excersize to the reader, but rest assured that
 * I have to do this as well, so the architecture of these classes will be such that it
 * will be as easy as possible.
 */
 
include_once("SDD/class.SDD.php") ;
include_once("class.paypalPDTBase.php") ;

class examplePaypalPDT extends paypalPDTBase
{
    function examplePaypalPDT($theReceiverEmail, 
                              $thePDTIdentityToken,
                              $thePaypalURL = NULL, 
                              $theSandboxURL = NULL)
    {
        $this->paypalPDTBase($theReceiverEmail, $thePDTIdentityToken, $thePaypalURL, $theSandboxURL) ;
    }
    
    function preprocess(&$theSource)
    {
        $theSource = array_merge(array('test_pdt' => '1'), $theSource) ;
        echo 'preprocess:theSource = ',SDD::dump($theSource),SDD::newline() ;
        return true ;
    }
    
    function preprocessPDT(&$thePDT)
    {
        echo 'preprocessPDT:thePDT = ',SDD::dump($thePDT),SDD::newline() ;
    }
    
    function checkTransactionId($theTransactionId)
    {
        echo 'checkTransactionId = ',SDD::dump($theTransactionId),SDD::newline(2) ;
        return TRUE ;
    }

    function validateItem(&$thePDT)
    {
        echo 'validateItem:thePDT = ',SDD::dump($thePDT),SDD::newline(2) ;
        return TRUE ;
    }

    function alternatePaymentStatus(&$thePDT)
    {
        echo 'alternatePaymentStatus:thePDT = ',SDD::dump($thePDT),SDD::newline(2) ;
        return TRUE ;
    }

    function processPayment(&$thePDT)
    {
        echo 'processPayment:thePDT = ',SDD::dump($thePDT),SDD::newline(2) ;
        return TRUE ;
    }

    function postprocess(&$thePDT, $theStatus)
    {
        echo 'postprocess:thePDT = ',SDD::dump($thePDT),SDD::newline(2) ;
        echo 'postprocess:theStatus = '.SDD::dump($theStatus),SDD::newline(2) ;
    }    
} ;

/**
 * The identity token gets handed to you when you enable PDT for your site.  
 * See your profile under Selling Preferences->Website Payment Preferences 
 * for how to enable PDT.  The identity token is at the top of the page 
 * displayed AFTER you save your Website Payment Preferences.  Don't loose it.
 *
 * @var string The Paypal Payment Data Transfer Identity Token
 */
 
$thePDTIdentityToken = 'XV20ACunItkcAQACjJOhIsWYUCt1D1Ce7HnqthqzwlmsHdAEddnp4si08OW' ;

$theSandbox = "sandbox." ;
$theReceiverEmail = "thewizard1@csworks.com" ;

if (count($_GET) > 0)
{
    echo 'serialize($_GET) = ',SDD::dump(serialize($_GET)),SDD::newline(2) ;
    echo '$_GET = ',SDD::dump($_GET),SDD::newline(2) ;

    /*
     * For reasons I don't understand, I can't communicate reliably with the https
     * versions of paypal but the http versions work just fine and for testing
     * it doesn't matter.
     */
     
    $thePDT = new ExamplePaypalPDT($theReceiverEmail, 
                                   $thePDTIdentityToken,
                                   'http://www.paypal.com/cgi-bin/webscr',
                                   'http://www.sandbox.paypal.com/cgi-bin/webscr') ;
    
    $thePDTData = array() ;
    
    $theReturnStatus = $thePDT->processNotification($_GET, $thePDTData) ;
    
    echo 'example.php:thePDT = ',SDD::dump($thePDT),SDD::newline(2) ;
    echo 'example.php:theReturnStatus = ',SDD::dump($theReturnStatus),SDD::newline(2) ;
}
else
{
?>
<form action="https://www.<? echo $theSandbox ; ?>paypal.com/cgi-bin/webscr" method="post">
    <p>
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="business" value="<? echo $theReceiverEmail ; ?>">
        <input type="hidden" name="item_name" value="ESPlanner Individual (Download+CD)">
        <input type="hidden" name="item_number" value="000002">
        <input type="hidden" name="amount" value="164.00">
        <input type="hidden" name="no_shipping" value="2">
        <input type="hidden" name="no_note" value="1">
        <input type="hidden" name="currency_code" value="USD">
        <input type="hidden" name="bn" value="PP-BuyNowBF">
        <input type="hidden" name="notify_url" value="">
        <input type="hidden" name="return" value="<? echo $_SERVER['SCRIPT_URI'] ; ?>">
        <input type="image" src="https://www.<? echo $theSandbox ; ?>paypal.com/en_US/i/btn/x-click-but23.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
        (Download+CD)..............164.00
        <img alt="" border="0" src="https://www.<? echo $theSandbox ; ?>paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
    </p>
</form>
<?php
}
?>