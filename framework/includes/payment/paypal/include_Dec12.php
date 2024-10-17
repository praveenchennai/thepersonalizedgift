<?php



/**
 * This file can be used to handle paypal secure payments.
 * The certificate file is expected to present in the same directory.
 * 
 * :- Sajith <sajith@newagesmb.com>
 * 
 * Usage
 * 
 * 	if ($_SERVER['REQUEST_METHOD'] == "POST") {
 *		include_once(SITE_PATH."/includes/paypal/include.php");
 *		$gateWay = processPayment($_POST);
 *		if($gateWay['Ack'] == "Success") {
 *			if($gateWay['Amount'] == $album->cartTotal($memberID)) {
 *				if($gateWay['CVV2Code'] == 'M') {
 *					$album->orderItems($_REQUEST, $gateWay, $memberID);
 *					redirect(makeLink(array("mod"=>"album", "pg"=>"shop"), "act=paymentsuccess&trans_id=".$gateWay['TransactionID']));
 *				} else {
 *					$framework->tpl->assign("ERROR_MSG", "The Card Verification Number you entered is not valid.");
 *				}
 *			} else {
 *				$framework->tpl->assign("ERROR_MSG", "The payment amount you transfered doesn't match with your shopping cart total.");
 *			}
 *		} else {
 *			$framework->tpl->assign("ERROR_MSG", $gateWay['errorMessage']);
 *		}
 *	}
 *
 */

#ssajith@gmail.com
#newagesmb

#include("../../../config.php");


/* 		*** 	From Database	 ***		 */
#define("API_USERNAME", 'ssajith_api1.gmail.com');
#define("API_PASSWORD", 'EXZL8SY43MVRMJBQ');
#define("API_CERTPATH", dirname(__FILE__)."/sandbox.cert");
/* 		*** 	From Database	 ***		 */


set_include_path(FRAMEWORK_PATH."/includes/payment/paypal/lib/".PATH_SEPARATOR.get_include_path());

require_once 'PayPal/Profile/Handler.php';
require_once 'lib/api_form_validators.inc.php';
require_once 'lib/functions.inc.php';
require_once 'lib/constants.inc.php';
require_once 'SampleLogger.php';

require_once 'PayPal.php';
require_once 'PayPal/Profile/Handler/Array.php';
require_once 'PayPal/Profile/API.php';
require_once 'PayPal/Type/DoDirectPaymentRequestType.php';
require_once 'PayPal/Type/DoDirectPaymentRequestDetailsType.php';
require_once 'PayPal/Type/DoDirectPaymentResponseType.php';
// Add all of the types
require_once 'PayPal/Type/BasicAmountType.php';
require_once 'PayPal/Type/PaymentDetailsType.php';
require_once 'PayPal/Type/AddressType.php';
require_once 'PayPal/Type/CreditCardDetailsType.php';
require_once 'PayPal/Type/PayerInfoType.php';
require_once 'PayPal/Type/PersonNameType.php';
require_once 'PayPal/Type/AbstractResponseType.php';

function processPayment ($param) {
	$dummy= @new APIProfile();
	$environments = $dummy->getValidEnvironments();

	$was_submitted = false;

	$logger = new SampleLogger('WebPaymentPro.php', PEAR_LOG_DEBUG);
	$logger->_log(print_r($_POST, true));

	$handler = & ProfileHandler_Array::getInstance(array(
				'username' => API_USERNAME,
				'certificateFile' => null,
				'subject' => null,
				'environment' => ENVIRONMENT ));

	$pid = ProfileHandler::generateID();

	$profile = & new APIProfile($pid, $handler); 
	$logger->_log('Profile: '. print_r($profile, true));

	$profile->setAPIUsername(API_USERNAME);
	$profile->setAPIPassword(API_PASSWORD);
	$profile->setSignature(null);
	$profile->setCertificateFile(API_CERTPATH);
	$profile->setEnvironment(ENVIRONMENT);
	$logger->_log('Profile: '. print_r($profile, true));

	$caller =& PayPal::getCallerServices($profile);
	// $logger->_log('caller: '. print_r($caller, true));

	$dp_request =& PayPal::getType('DoDirectPaymentRequestType');
	if (PayPal::isError($dp_request)) {
		$logger->_log('Error in request: '. $dp_request);
	} else {
		$logger->_log('Create request: '. $dp_request);
	}

	$logger->_log('Initial request: '. print_r($dp_request,true));

	/**
 	* Get posted request values
 	*/
	$paymentType 		= 'Sale';
	$firstName 			= $param['firstName'];
	$lastName 			= $param['lastName'];
	$creditCardType 	= $param['creditCardType'];
	$creditCardNumber 	= $param['creditCardNumber'];
	$expDateMonth 		= $param['expDateMonth'];
	// Month must be padded with leading zero
	$padDateMonth 		= str_pad($expDateMonth, 2, '0', STR_PAD_LEFT);

	$expDateYear 		= $param['expDateYear'];
	$cvv2Number 		= $param['cvv2Number'];
	$address1 			= $param['address1'];
	$address2 			= $param['address2'];
	$city 				= $param['city'];
	$state 				= $param['state'];
	$zip 				= $param['zip'];
	$country			= $param['country'] ? $param['country'] : 'US';
	$amount 			= $param['amount'];

	
	
	
	
	
	// Populate SOAP request information
	// Payment details
	$OrderTotal =& PayPal::getType('BasicAmountType');
	if (PayPal::isError($OrderTotal)) {
		var_dump($OrderTotal);
		exit;
	}
	$OrderTotal->setattr('currencyID', 'USD');
	$OrderTotal->setval($amount, 'iso-8859-1');
	$PaymentDetails =& PayPal::getType('PaymentDetailsType');
	$PaymentDetails->setOrderTotal($OrderTotal);

	$shipTo =& PayPal::getType('AddressType');
	$shipTo->setName($firstName.' '.$lastName);
	$shipTo->setStreet1($address1);
	$shipTo->setStreet2($address2);
	$shipTo->setCityName($city);
	$shipTo->setStateOrProvince($state);
	$shipTo->setCountry($country);
	$shipTo->setPostalCode($zip);
	$PaymentDetails->setShipToAddress($shipTo);

	$dp_details =& PayPal::getType('DoDirectPaymentRequestDetailsType');
	$dp_details->setPaymentDetails($PaymentDetails);

	// Credit Card info
	$card_details =& PayPal::getType('CreditCardDetailsType');
	$card_details->setCreditCardType($creditCardType);
	$card_details->setCreditCardNumber($creditCardNumber);
	$card_details->setExpMonth($padDateMonth);
	// $card_details->setExpMonth('01');
	$card_details->setExpYear($expDateYear);
	// $card_details->setExpYear('2010');
	$card_details->setCVV2($cvv2Number);
	$logger->_log('card_details: '. print_r($card_details, true));

	$payer =& PayPal::getType('PayerInfoType');
	$person_name =& PayPal::getType('PersonNameType');
	$person_name->setFirstName($firstName);
	$person_name->setLastName($lastName);
	$payer->setPayerName($person_name);

	$payer->setPayerCountry($country);
	$payer->setAddress($shipTo);

	$card_details->setCardOwner($payer);

	$dp_details->setCreditCard($card_details);
	$dp_details->setIPAddress($_SERVER['SERVER_ADDR']);
	$dp_details->setPaymentAction($paymentType);

	$dp_request->setDoDirectPaymentRequestDetails($dp_details);

	$caller =& PayPal::getCallerServices($profile);

	// Execute SOAP request
	$response = $caller->DoDirectPayment($dp_request);

	if(method_exists($response, 'getAck')) {
		$ack = @$response->getAck();
	}

	$logger->_log('Ack='.$ack);


	switch($ack) {
		case ACK_SUCCESS:
		case ACK_SUCCESS_WITH_WARNING:
			// Good to break out;
			break;

		default:
			$logger->_log('DoDirectPayment failed: ' . print_r($response, true));
			require_once 'PayPal.php';
			require_once 'PayPal/Profile/Handler/Array.php';
			require_once 'PayPal/Profile/API.php';
			require_once 'PayPal/Type/AbstractResponseType.php';
			require_once 'PayPal/Type/ErrorType.php';
			require_once 'PayPal/Type/RefundTransactionResponseType.php';
			require_once 'PayPal/Type/TransactionSearchResponseType.php';
			require_once 'PayPal/Type/GetTransactionDetailsResponseType.php';
			require_once 'PayPal/Type/DoDirectPaymentResponseType.php';
			require_once 'PayPal/Type/SetExpressCheckoutResponseType.php';
			require_once 'PayPal/Type/GetExpressCheckoutDetailsResponseDetailsType.php';
			require_once 'PayPal/Type/GetExpressCheckoutDetailsResponseType.php';
			require_once 'PayPal/Type/DoExpressCheckoutPaymentResponseType.php';
			require_once 'PayPal/Type/DoCaptureResponseDetailsType.php';
			require_once 'PayPal/Type/DoCaptureResponseType.php';
			require_once 'PayPal/Type/DoVoidResponseType.php';

			require_once 'SampleLogger.php';

			if(!method_exists($response,'getAck')){
				if(method_exists($response,'getMessage')){
					$error = strval($response->getMessage());
				}
				if($response->userinfo) {
					$error .= ". ".$response->userinfo;
				}

				$ret['Ack']			  = "Failure";
				$ret['errorMessage']  = $error;
			} else {

				// Require AbstractResponseType.php
				$ret['Ack']           = $response->getAck();
				$ret['CorrelationID'] = $response->getCorrelationID();
				$ret['Version']       = $response->getVersion();
				// Require ErrorType.php
				$errorList     		  = $response->getErrors();

				if(! is_array($errorList)) {
					$errorCode    = $errorList->getErrorCode();
					$shortMessage = $errorList->getShortMessage();
					$longMessage  = $errorList->getLongMessage();
					$ret['errorMessage'] = $longMessage;
				} else {
					for($n = 0; $n < sizeof($errorList); $n++) {
						$oneError 	  = $errorList[$n];
						$errorCode    = $oneError->getErrorCode();
						$shortMessage = $oneError->getShortMessage();
						$longMessage  = $oneError->getLongMessage();
						$ret['errorMessage'] .= $longMessage . "<br />";
					}
				}
			}
			$ret['response'] 		= $response;

			return $ret;
	}

	$amt_obj 	 = $response->getAmount();
	$currency_cd = $amt_obj->_attributeValues['currencyID'];

	$ret['Ack'] 			= $response->getAck();
	$ret['TransactionID'] 	= $response->getTransactionID();
	$ret['AVSCode'] 		= $response->getAVSCode();
	$ret['CVV2Code']		= $response->getCVV2Code();
	$ret['Amount']			= $amt_obj->_value;
	$ret['AmountDisplay'] 	= $currency_cd.' '.$ret['Amount'];

	$ret['response'] 		= $response;

	return $ret;
}

/*
$val = array();
$val = $_POST;
print_r (processPayment($val));
*/
?>