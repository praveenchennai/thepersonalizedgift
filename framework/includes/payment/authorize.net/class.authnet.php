<?php
	
/** The following class is used for Authorize.NET Payment gateway communication
 *  Parameter takes login and transkey arguments for authnet payment gateway
 *  account details
 */

class Authnet
{
    var		$login    = "";
    var		$transkey = "";
    var		$params   = array();
    var		$results  = array();

    var		$approved = false;
    var		$declined = false;
    var		$error    = true;

    var		$test;
    var		$fields;
    var		$response;

    function Authnet($test = false, $login = '2n4XG4e2XPfn', $transkey = "2n94Pc3EHbGr4n9g")
    {
		$this->test    		=	$test;
		$this->login		=	$login;
		$this->transkey		=	$transkey;

		if ($this->test) {
			#$this->url = "https://secure.authorize.net/gateway/transact.dll";
			$this->url = "https://test.authorize.net/gateway/transact.dll";
		} else {
			#$this->url = "https://secure.authorize.net/gateway/transact.dll";
			
			$this->url = "https://test.authorize.net/gateway/transact.dll";
		}
		
		$this->params['x_delim_data']     = "TRUE";
		$this->params['x_delim_char']     = "|";
		$this->params['x_relay_response'] = "FALSE";
		$this->params['x_url']            = "FALSE";
		$this->params['x_version']        = "3.1";
		$this->params['x_method']         = "CC";
		$this->params['x_type']           = "AUTH_CAPTURE";
		$this->params['x_login']          = $this->login;
		$this->params['x_tran_key']       = $this->transkey;
		
		$this->params['x_test_request']   = "TRUE";
		
		
      }

    function transaction($cardnum, $expiration, $amount, $cvv = "", $Params)
    {
        $this->params['x_card_num']  	= 	trim($cardnum);
        $this->params['x_exp_date']  	= 	trim($expiration);
        $this->params['x_amount']    	= 	trim($amount);
    	$this->params['x_card_code']	= 	trim($cvv);
		$this->params['x_po_num']    	= 	trim($invoice);
    	$this->params['x_tax']       	= 	trim($Params['tax']);
		$this->params['x_invoice_num']  = 	$Params['invoice_number'];

		$this->params['x_first_name']  	= 	$Params['firstName'];
		$this->params['x_last_name']    = 	$Params['lastName'];
		$this->params['x_company']      =	$Params['company'];
		$this->params['x_address']      =	$Params['address1'].' '.$Params['address2'] ;
		$this->params['x_city']  		= 	$Params['city'];
		$this->params['x_state']  		= 	$Params['state'];
		$this->params['x_zip']  		= 	$Params['zip'];
		$this->params['x_country']  	= 	$Params['country'];
		$this->params['x_phone']  		= 	$Params['phone'];
		$this->params['x_email']  		= 	$Params['mail'];

		$this->params['x_ship_to_first_name']  		= 	$Params['ship_to_first_name'];
		$this->params['x_ship_to_last_name']  		= 	$Params['ship_to_last_name'];
		$this->params['x_ship_to_company']  		= 	$Params['ship_to_company'];
		$this->params['x_ship_to_address']  		= 	$Params['ship_to_address1'].' '.$Params['ship_to_address2'];
		$this->params['x_ship_to_city']  			= 	$Params['ship_to_city'];
		$this->params['x_ship_to_state']  			= 	$Params['ship_to_state'];
		$this->params['x_ship_to_zip']  			= 	$Params['ship_to_zip'];
		$this->params['x_ship_to_country']  		= 	$Params['ship_to_country'];
		
		$this->params['x_po_num']  					= 	$Params['order_number'];
		
    }

    function process($retries = 1)
    {
        $this->_prepareParameters();
        $ch = curl_init($this->url);

        $count = 0;
        while ($count < $retries)
        {
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim($this->fields, "& "));
            $this->response = curl_exec($ch);
            $this->_parseResults();
            if ($this->getResultResponseFull() == "Approved")
            {
                $this->approved = true;
                $this->declined = false;
                $this->error    = false;
                break;
            }
            else if ($this->getResultResponseFull() == "Declined")
            {
                $this->approved = false;
                $this->declined = true;
                $this->error    = false;
                break;
            }
            $count++;
        }
        curl_close($ch);
    }

    function _parseResults()
    {
        $this->results = explode("|", $this->response);
    }

    function setParameter($param, $value)
    {
        $param                = trim($param);
        $value                = trim($value);
        $this->params[$param] = $value;
    }

    function setTransactionType($type)
    {
        $this->params['x_type'] = strtoupper(trim($type));
    }

    function _prepareParameters()
    {
        foreach($this->params as $key => $value)
        {
            $this->fields .= "$key=" . urlencode($value) . "&";
        }
    }

    function getResultResponse()
    {
        return $this->results[0];
    }

    function getResultResponseFull()
    {
        $response = array("", "Approved", "Declined", "Error");
        return $response[$this->results[0]];
    }

    function isApproved()
    {
        return $this->approved;
    }

    function isDeclined()
    {
        return $this->declined;
    }

    function isError()
    {
        return $this->error;
    }

    function getResponseText()
    {
        return $this->results[3];
    }

    function getAuthCode()
    {
        return $this->results[4];
    }

    function getAVSResponse()
    {
        return $this->results[5];
    }

    function getTransactionID()
    {
        return $this->results[6];
    }
} # Close class definition	

?>