<?php
	session_start();
	include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
	include_once(FRAMEWORK_PATH."/modules/payment/lib/class.payment.php");
	
	$act				=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
	$param				=	"mod=$mod&pg=$pg&act=$act&orderBy=$orderBy&sortOrder=$sortOrder&cat_id=$cat_id&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&mId={$_REQUEST['mId']}";
	$objStore			=	new Store();
	$objPayment			=	new Payment();
	
	switch($_REQUEST['act']) {
		case "paymentlist":
			$orderBy 			= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "paymethod_name";
			$Stores				=	$objPayment->getStoreCombo();
						
			$DefaultStoreName	=	(trim($_REQUEST["store_name"]) != '') ? $_REQUEST["store_name"] : $Stores['name'][0];
			$PayMethods			=	$objPayment->getPaymentMethods($orderBy);
			
			if($objPayment->config['payment_receiver'] == 'admin')
				$DefaultStoreName 	= 	'0';
			$PayMethodId	=	$objPayment->getPaymentMethod($DefaultStoreName, $objPayment->config['payment_receiver']);
			
			$framework->tpl->assign("STORE_OWNER", $objPayment->config['payment_receiver']);
			$framework->tpl->assign("SINGLE_CHECKED", $checked);
			$framework->tpl->assign("PAY_METHODID", $PayMethodId);
			$framework->tpl->assign("PAY_METHODS", $PayMethods);
			$framework->tpl->assign("SELECTED_STORE_NAME", $DefaultStoreName);
			$framework->tpl->assign("STORES", $Stores);
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/payment/tpl/payment_list.tpl");
			break;
		
		case "form":
			$objPayment->processPaymentMethodFormDetails($_REQUEST);
			if($_REQUEST['Action'] == 'Activate')
				redirect(makeLink(array("mod"=>"payment", "pg"=>"index"), "act=paymentdetailsform&storeowner={$_REQUEST['storeowner']}&store_name={$_REQUEST['storename']}&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&mId={$_REQUEST['mId']}"));
			else if($_REQUEST['Action'] == 'DeActivate')
				redirect(makeLink(array("mod"=>"payment", "pg"=>"index"), "act=paymentlist&storeowner={$_REQUEST['storeowner']}&store_name={$_REQUEST['storename']}&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&mId={$_REQUEST['mId']}"));
 			break;
			
		case "paymentdetailsform":
			$PayMethodId		=	$objPayment->getPaymentMethod($_REQUEST['store_name'], $objPayment->config['payment_receiver']);
			$PayMethodDetails	=	$objPayment->getPaymentMethodDetailsById($PayMethodId);
			$action_method		=	$PayMethodDetails['action_method'];
			$admintpl_form_file	=	$PayMethodDetails['admintpl_form_file'];

			$Submit				=	$_REQUEST['Submit'];
			if($Submit == 'Submit') {
				$ValidateMethod		=	'validate'.$action_method;
				$SaveMethod			=	'set'.$action_method;
				$StatusMsg			=	$objPayment->$ValidateMethod($_REQUEST, $_FILES);
				
				if($StatusMsg === TRUE) {
					$objPayment->$SaveMethod($_REQUEST, $_FILES);
					redirect(makeLink(array("mod"=>"payment", "pg"=>"index"), "act=paymentlist&storeowner={$_REQUEST['storeowner']}&store_name={$_REQUEST['store_name']}&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&mId={$_REQUEST['mId']}"));
				} else {
					setMessage($StatusMsg);	
				}	
				$framework->tpl->assign("FORM_VALUES", $_REQUEST);
			} else {
				$Method				=	'get'.$action_method;
				$PaymentConfigs		=	$objPayment->$Method($_REQUEST['store_name'],$objPayment->config['payment_receiver']);
				$framework->tpl->assign("FORM_VALUES", $PaymentConfigs);
				
			}	
			$framework->tpl->assign("PAYMENT_METHOD", $PayMethodDetails['paymethod_name']);
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/payment/tpl/".$admintpl_form_file);
			break;
		
		case "downloadcertificate":
			header("Content-type: application/octet-stream");
			header("Content-disposition: attachment; filename={$_REQUEST['filename']}");
			@readfile(FRAMEWORK_PATH.'/modules/payment/certificatefiles/'.$_REQUEST['filename']);
			ob_end_flush();
			exit;
			break;

		case "shippinglist":
					
			break;
		
	} # Close Switch statement

	$framework->tpl->display($global['curr_tpl']."/admin.tpl");

?>
