<?php 
/**
 * Admin Store
 *
 * @author Ajith
 * @package defaultPackage
 */
	authorize_store();
	include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
	$store					= 	new Store();	
	$storeDetails			=	$store->storeGetByName($_REQUEST['storename']);		
	$store_id				=	$storeDetails['id'];
switch($_REQUEST['act']) {
   case "list":
		$payment 			= 	$store->getPaymentbystoreid($store_id);
		$count				=	count($payment);
		switch($payment['payment_provider']){
			case "P":
				$payment['provider_name']	=	"Paypal";
			break;
			case "R":
				$payment['provider_name']	=	"Paypal Pro";
			break;
			case "A":
				$payment['provider_name']	=	"Authorize.net";
			break;
			case "L":
				$payment['provider_name']	=	"Link Point";
			break;
		}
		$framework->tpl->assign("PAYMENT_DETAILS", $payment);
		$framework->tpl->assign("PAYMENT_COUNT", $count);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/store/tpl/payment_list.tpl");
   break;		
   case "form":
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $req = &$_REQUEST;
            if( ($message = $store->paymentAddEdit($req)) === true ) {
            	$action = $req['id'] ? "Updated" : "Added";
            	setMessage("Providers $action Successfully", MSG_SUCCESS);
                redirect(makeLink(array("mod"=>"store", "pg"=>"store_payment"), "act=list"));
            }
            setMessage($message);
        }        
        if($message) {
            $framework->tpl->assign("PAYMENT", $_POST);
      	}elseif($_REQUEST['id']) {
        	$paymentDetails 		= 	$store->getPayment($_REQUEST['id']);			
            $framework->tpl->assign("PAYMENT", $paymentDetails);
		}
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/store/tpl/payment_form.tpl");
        break;
}
	if($_REQUEST['manage']=="manage"){
		$framework->tpl->display($global['curr_tpl']."/store.tpl");
	}else{
		$framework->tpl->display($global['curr_tpl']."/admin.tpl");
	}


?>