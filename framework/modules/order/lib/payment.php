<?php
	# This File is used for Payment gateway operations




# The following block of code is for store implementation --> ## Put at the Header of the shipping.php file
if($_REQUEST['manage']=="manage"){
	authorize_store();
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("PG","order_payment") ;
	$module		=	'store';	$Page	=	'order_payment';
	$permission_array	=	array(	'add'		=>	$objAdmin->GetmenuPermission($store_id,$fId,'add'),
									'edit'		=>	$objAdmin->GetmenuPermission($store_id,$fId,'edit'),
									'delete'	=>	$objAdmin->GetmenuPermission($store_id,$fId,'delete'));
    $framework->tpl->assign("STORE_PERMISSION",$permission_array) ;
}else{
	authorize();
	$framework->tpl->assign("MOD","order") ;
	$framework->tpl->assign("PG","payment") ;
	$module		=	'order';	$Page	=	'payment';
	$permission_array	=	array(	'add'		=>	$objAdmin->GetmenuPermission(0,$fId,'add'),
									'edit'		=>	$objAdmin->GetmenuPermission(0,$fId,'edit'),
									'delete'	=>	$objAdmin->GetmenuPermission(0,$fId,'delete'));
    $framework->tpl->assign("STORE_PERMISSION",$permission_array) ;
}
# The following block of code  is for store implementation Ends heree....................


	session_start();
	include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
	include_once(FRAMEWORK_PATH."/modules/order/lib/class.payment.php");
	require_once FRAMEWORK_PATH."/modules/order/lib/paymentconfig.php";
	
	$act				=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
	#$param				=	"mod=$mod&pg=$pg&act=$act&orderBy=$orderBy&sortOrder=$sortOrder&cat_id=$cat_id&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&mId={$_REQUEST['mId']}";
	$param				=	"mod=$mod&pg=$pg&act=$act&orderBy=$orderBy&fId=$fId&sId=$sId";
	$objStore			=	new Store();
	$objPayment			=	new Payment();
	
	switch($_REQUEST['act']) {
		case "paymentlist":
			$orderBy 			= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "paymethod_name";
			
			
			if($_REQUEST['mod'] == 'store') {
				$StoreId				=	$objPayment->getStoreIdFromStoreName($_REQUEST['storename']);
				$Stores['id'][0]		=	$StoreId;
				$Stores['name'][0]		=	$_REQUEST['storename'];
				$store_id				=	$StoreId;	
			} else {
				$Stores				=	$objPayment->getStoreCombo();
				$DefaultStoreId		=	(trim($_REQUEST["store_id"]) != '') ? $_REQUEST["store_id"] : $Stores['id'][0];
			}
			//echo $StoreId;
			$PayMethods			=	$objPayment->getPaymentMethods($orderBy);
			
			if($objPayment->config['payment_receiver'] == 'admin')
				$DefaultStoreId 	= 	'0';
				else
				$DefaultStoreId 	= 	$store_id;
				
			$PayMethodId	=	$objPayment->getPaymentMethod($DefaultStoreId, $objPayment->config['payment_receiver']);
			$CreditCards	=	$objPayment->getSelectedCreditCardDetailsOfStores($DefaultStoreId);
			$PaymethodName	=	$objPayment->getPaymentMethodNameFromPaymentMethodId($PayMethodId);
			
			$framework->tpl->assign("CREDITCARD_LIST", $CreditCards);
			$framework->tpl->assign("PAYMENT_METHOD_NAME", $PaymethodName);
				$framework->tpl->assign("STORE_OWNER", $objPayment->config['payment_receiver']);
			$framework->tpl->assign("PAY_METHODID", $PayMethodId);
			$framework->tpl->assign("PAY_METHODS", $PayMethods);
			$framework->tpl->assign("SELECTED_STORE_ID", $DefaultStoreId);
			$framework->tpl->assign("STORES", $Stores);
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/payment_list.tpl");
			break;
		
		case "form":
			# $objPayment->processPaymentMethodFormDetails($_REQUEST);
			
			$objPayment->deactivatePaymentMethodOfStores($_REQUEST);
			$paymethod_id	=	$_REQUEST['paymethod_id'];
			
			if($_REQUEST['Action'] == 'Activate')
				redirect(makeLink(array("mod"=> $module, "pg"=> $Page), "act=paymentdetailsform&storeowner={$_REQUEST['storeowner']}&store_id={$_REQUEST['store_id']}&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&mId={$_REQUEST['mId']}&paymethod_id=$paymethod_id"));
			else if($_REQUEST['Action'] == 'DeActivate')
				redirect(makeLink(array("mod"=> $module, "pg"=> $Page), "act=paymentlist&storeowner={$_REQUEST['storeowner']}&store_id={$_REQUEST['store_id']}&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&mId={$_REQUEST['mId']}"));
 			break;
			
		case "paymentdetailsform":
		
			if(trim($_REQUEST['paymethod_id']) != '' || trim($_REQUEST['paymethod_id']) != 0)
				$PayMethodId		=	$_REQUEST['paymethod_id'];
			else
				$PayMethodId		=	$objPayment->getPaymentMethod($_REQUEST['store_id'], $objPayment->config['payment_receiver']);
			
			$PayMethodDetails	=	$objPayment->getPaymentMethodDetailsById($PayMethodId);
			$class_name			=	$PayMethodDetails['class_name'];
			$payapi_file		=	$PayMethodDetails['payapi_file'];
			
			if(file_exists(FRAMEWORK_PATH.''.$payapi_file))
				require_once FRAMEWORK_PATH.''.$payapi_file;
			$ActionObj	=	new $class_name($objPayment);
			
			$admintpl_form_file	=	$PayMethodDetails['admintpl_form_file'];
			$CreditCards		=	$objPayment->getCreditCardDetailsOfStores($_REQUEST['store_id']);
			
			$Submit				=	$_REQUEST['Submit'];
			if($Submit == 'Submit') {
				$ValidateMethod		=	'validate'.$class_name;
				$SaveMethod			=	'set'.$class_name;
				$StatusMsg			=	$ActionObj->$ValidateMethod($_REQUEST, $_FILES);
				if($StatusMsg === TRUE) {
					$objPayment -> activatePaymentMethodOfStores($_REQUEST);
					 if($objPayment->config['payment_receiver']=='store')
					 {
					 include_once(FRAMEWORK_PATH."/modules/order/lib/class.shipping.php");
					 $ShippingObj	=	new Shipping();
					  $ShippingMethods	=	$ShippingObj->getShippingMethodsOfStore($_REQUEST['store_id']);
					  
					 //code modified by robin
					  $flg=0;
					 foreach($ShippingMethods as $ship)
					 {	if($ship[selected]=='Y')
					 	$flg++;
					 }
						if(!$flg)
							$_SESSION[storeSess][0]->statusMsg="Your store cannot process orders. Please set up your Shipping information.";
						else
					 	unset($_SESSION[storeSess][0]->statusMsg);
					 }
						$ActionObj	-> $SaveMethod($_REQUEST, $_FILES);
					redirect(makeLink(array("mod" => $module, "pg" => $Page), "act=paymentlist&storeowner={$_REQUEST['storeowner']}&store_id={$_REQUEST['store_id']}&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&mId={$_REQUEST['mId']}"));
				} else {
					setMessage($StatusMsg);
				}	
				$framework->tpl->assign("FORM_VALUES", $_REQUEST);
			} else {
				$Method				=	'get'.$class_name;
				$PaymentConfigs		=	$ActionObj->$Method($_REQUEST['store_id'],$objPayment->config['payment_receiver']);
				$framework->tpl->assign("FORM_VALUES", $PaymentConfigs);
				
			}	
			
			$framework->tpl->assign("CREDITCARD_LIST", $CreditCards);
			$framework->tpl->assign("PAYMENT_METHOD", $PayMethodDetails['paymethod_name']);
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/".$admintpl_form_file);
			break;
		
		case "downloadcertificate":
			header("Content-type: application/octet-stream");
			header("Content-disposition: attachment; filename={$_REQUEST['filename']}");
			header("Cache-Control: public, must-revalidate");
			@readfile(SITE_PATH.'/modules/payment/certificatefiles/'.$_REQUEST['filename']);
			ob_end_flush();
			exit;
			break;

		case "shippinglist":
					
			break;
		
	} # Close Switch statement


#$framework->tpl->display($global['curr_tpl']."/admin.tpl");
	

## Put at the Footer of the shipping.php file
if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}

?>