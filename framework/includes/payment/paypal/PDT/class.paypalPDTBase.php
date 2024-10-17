<?php

//
// Edit History:
//
//  Last $Author: munroe $
//  Last Modified: $Date: 2006/03/24 14:30:35 $
//
//  Dick Munroe (munroe@csworks.com) 22-Mar-2006
//      Initial version created
//
//  Dick Munroe (munroe@csworks.com) 23-Mar-2006
//      getData needs to be overridden to prevent injection of IPN specific stuff.
//

/**
 * @author Dick Munroe <munroe@csworks.com>
 * @copyright copyright @ 2006 by Dick Munroe, Cottage Software Works, Inc.
 * @license http://www.csworks.com/publications/ModifiedNetBSD.html
 * @version 1.0.0
 * @package paypalPDT
 * @example ./example.php
 *
 * Essentially once the acquisition of the PDT data is over, the rest of the processing
 * is fundamentially identical to the IPN processing.  The first pass at this will
 * have seperate classes for both.  Eventually I'll restructure the dm.paypal and
 * paypalPDT classes to be a single class with a factory.
 *
 * This requires the PHP cURL module to be installed and also requires the following
 * additional classes from phpclasses.org:
 *
 *     cURL:                        http://munroe.users.phpclasses.org/browse/package/1988.html
 *     dm.paypal                    http://munroe.users.phpclasses.org/browse/package/2970.html
 *
 * These have to be installed on your PHP search path or within the directory containing
 * the Paypal PDT classes in a directory named curl.
 */

include_once('curl/class.curl.php') ;
include_once('dm.paypal/class.paypalIPNBase.php') ;

/**
 * This is an abstract class that has to be overridden to provide useful application
 * level processing.
 */

class paypalPDTBase extends paypalIPNBase
{
    /**
     * @var string the Paypal PDT Identity Token.
     * @access private
     */
     
    var $m_paypalPDTIdentityToken ;
    
    /**
     * @var string The data received by the paypal verification request.
     * @access private
     */
     
    var $m_paypalResponse ;

    /**
     * Constructor
     *
     * @param string $thePaypalURL The URL of the paypal verification URL.
     * @param string $theSandboxURL The URL of the paypal sandbox verification URL.
     * @return void
     * @access public
     */

    function paypalPDTBase($theReceiverEmail, 
                           $thePDTIdentityToken,
                           $thePaypalURL = NULL, 
                           $theSandboxURL = NULL)
    {
        $this->m_paypalPDTIdentityToken = $thePDTIdentityToken ;
        
        $this->paypalIPNBase($theReceiverEmail, $thePaypalURL, $theSandboxURL) ;
    }

    /*
     * This function must be overridden in order to provide actual
     * PDT preprocessing.  This is called after communication with
     * Paypal to accumulate the PDT data into the source.
     *
     * @desc Preprocess the PDT source data.
     * @param reference to array $theSource for the PDT data.
     * @access public
     */

    function preprocessPDT(&$theSource)
    {
        trigger_error("preprocessPDT must be overridden", E_USER_ERROR) ;
    }

    /**
     * The PDT equivalent of a "verified" response is "SUCCESS" so this function
     * has to be overridden here.
     *
     * @desc Check for a verified response from Paypal.
     * @param string theResponse
     * @return boolean.
     * @access protected
     */

    function checkVerified($theResponse)
    {
        return $theResponse == "SUCCESS" ;
    }

    /**
     * @desc Get the PDT data.
     * @access private
     * @return boolean true if the source is empty after processing.
     */

    function getData(&$theSource)
    {
        /*
         * Parse the PDT data out of the source.
         */

        $this->m_data =& new paypalIPNData($theSource) ;
        return empty($theSource) ;
    }

    /**
     * This function calls helper functions that model each piece of the PDT process as documented in
     * Paypal Hacks, chapter 7.  The overall process is:
     *
     *  1. Connect to Paypal to fetch the PDT.
     *  2. Parse the PDT and put the results into theSource
     *  3. Connect to the application specific preprocessing hook.
     *  4. Verify the PDT with Paypal.
     *  5. If the PDT is verified, Check for a completed transaction.
     *  6. If the transaction was completed, check that the transaction ID isn't a duplicate.
     *  7. If the transaction ID is unique, check that the receiver email address is for the local site.
     *  8. If the receiver email address matches, check that the item details presented make sense.
     *  9. If the item details make sense, process the payment.
     *
     * @desc Process an PDT received in a $_POST array.
     * @param array $theSource [by reference] the arguments passed by Paypal as part of the PDT process.
     * @return boolean False if the PDT wasn't processed.
     * @access public
     */

    function processNotification (&$theSource)
    {
        $this->preprocess($theSource) ;

        $theTestPDT = $theSource['test_pdt'] ;
        
        if ($theTestPDT !== NULL)
        {
            $theURL = $this->m_sandboxURL ;
        }
        else
        {
            $theURL = $this->m_paypalURL ;
        }

        if ($theURL != '')
        {
            /*
             * There is no Paypal supported mechanism for noting that this
             * is a debugging interaction so remove the "local" token before
             * interacting with Paypal.  Then put it back.
             */
             
            $theDestination = array() ;
            $theReturnStatus = $this->httpPost($theURL, $theSource, $theDestination) ;
            if ($theTestPDT !== NULL)
            {
                $theDestination['test_pdt'] = $theTestPDT ;
            }
        }
        else
        {
            /*
             * For debugging purposes, if the URL for interacting with is not provided,
             * then force a SUCCESS response.
             */
             
            $theReturnStatus = "SUCCESS" ;
        }
        
        if ($theReturnStatus)
        {
            /*
             * At this point, theDestination has been modified to contain the PDT data and
             * any originating PDT data has been removed.
             */
    
            if (!$this->getData($theDestination))
            {
                $theReturnStatus = !$this->sourceNotEmpty($this->m_data, $theDestination) ;
            }
    
            if ($theReturnStatus)
            {
                $this->preprocessPDT($this->m_data->getData()) ;
    
                $theReturnStatus = $this->_processNotification($theReturnStatus, $this->m_data) ;
            }
        }
            
        $this->postprocess($this->m_data, $theReturnStatus) ;

        return $theReturnStatus ;
    }

    /*
     * @desc Post the PDT notification back to Paypal for verification.
     * @param string $url the Paypal verification URL.
     * @param object $theSource [by reference] the PDT notification data in source form.
     * @return mixed "SUCCESS" if the communication with Paypal succeeds, false otherwise.
     *                theSouce is modified to contain the data returned by the interaction
     *                with Paypal.
     * @access private
     */

    function httpPost ($url, &$theSource, &$theDestination)
    {
        /*
         * Put the 'cmd' and 'at' fields at the beginning of the source.
         */
         
        $theSource = array_merge(array('cmd' => '_notify-synch', 'at' => $this->m_paypalPDTIdentityToken),
                                 $theSource) ;
    
        /*
         * Walk through the source producing a query string for Paypal.
         */
         
        $theQueryString = "" ;
        
        foreach (array('cmd', 'tx', 'at') as $theValue)
        {
            $theQueryString .= '&' . $theValue . '=' . urlencode($theSource[$theValue]) ;
        }
        
    	/*
    	 * This is an undocumented change in the Paypal URL encoding.
    	 * The encoding results of the HEX value have to be in upper case.
    	 */

    	preg_replace_callback('/%[\da-f]{2}/', create_function('$match', 'return strtoupper($match[0]) ;'), 
    	                      $theQueryString) ;
                              
        $theQueryString = substr($theQueryString, 1) ;

        /*
         * Technically the m_curl variable should be local to here.  It's being kept
         * in object context so that debugging will be a bit easier.
         */

        $this->m_curl = new cURL() ;

        // Notification if transfered into a urlencoded string

        $this->m_curl->setopt(CURLOPT_URL, $url);
        $this->m_curl->setopt(CURLOPT_POST, 1) ;
        $this->m_curl->setopt(CURLOPT_RETURNTRANSFER,1);
        $this->m_curl->setopt(CURLOPT_SSL_VERIFYHOST, 2) ;
        $this->m_curl->setopt(CURLOPT_SSL_VERIFYPEER, FALSE) ;
        $this->m_curl->setopt(CURLOPT_POSTFIELDS, $theQueryString);

        // If you need to go through a proxy server, see set_proxy_options

        if (!is_null($this->m_proxyURL) && !is_null($this->m_proxyUserPassword))
        {
            if (preg_match("/[^:]+:[0-9]+/", $this->m_proxyURL) &&
                preg_match("/([^:]+):.*/", $this->m_proxyUserPassword, $matches))
            {
                    $this->m_curl->setopt(CURLOPT_PROXY, $this->m_proxyURL);
                    $this->m_curl->setopt(CURLOPT_PROXYUSERPWD, $this->m_proxyUserPassword);
            }
            else
            {
                trigger_error("Can't set proxy information", E_USER_ERROR) ;
                return false ;
            }
        }
        
        $this->m_paypalResponse = $this->m_curl->exec();

        $this->m_curl->close();

        if ($this->m_curl->hasError())
        {
            return FALSE ;
        }
        else
        {
            $this->m_paypalResponse = explode("\n", $this->m_paypalResponse) ;
            
            if($this->m_paypalResponse[0] == "SUCCESS")
            {
                /*
                 * Get rid of the SUCCESS/FAIL response
                 */
                 
                array_shift($this->m_paypalResponse) ;
                
                /*
                 * Get rid of the null line.
                 */
                 
                array_pop($this->m_paypalResponse) ;
                
                $theDestination = array() ;
                
                foreach ($this->m_paypalResponse as $theValue)
                {
                    $xxx = explode("=", $theValue) ;
                    
                    $theDestination[urldecode($xxx[0])] = urldecode($xxx[1]) ;
                }

                return "SUCCESS" ;
            }
            else
            {
                return FALSE ;
            }
        }
    }
}
?>
