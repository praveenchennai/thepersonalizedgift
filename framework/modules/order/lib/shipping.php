<?php

/**
 * Admin Payment type management
 *
 * @author 		Vimson
 * @Filename 	shipping.php
 * @package 	defaultPackage
 * 
 * 
 **/
error_reporting(E_ERROR | E_PARSE );  # | E_WARNING | E_NOTICE

# The following block of code is for store implementation
if($_REQUEST['manage']=="manage"){
	authorize_store();
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("PG","order_shipping") ;
		if($framework->config['single_prod']==1){
			$module		=	'store';	$Page	=	'home_shipping';
		}
		else {
			$module		=	'store';	$Page	=	'order_shipping';
		}
	$permission_array	=	array(	'add'		=>	$objAdmin->GetmenuPermission($store_id,$fId,'add'),
									'edit'		=>	$objAdmin->GetmenuPermission($store_id,$fId,'edit'),
									'delete'	=>	$objAdmin->GetmenuPermission($store_id,$fId,'delete'));
    $framework->tpl->assign("STORE_PERMISSION",$permission_array) ;
}else{
	authorize();
	$framework->tpl->assign("MOD","order") ;
	$framework->tpl->assign("PG","shipping") ;
		if($framework->config['single_prod']==1){
			$module		=	'home';	$Page	=	'shipping';
		}
		else{
			$module		=	'order';	$Page	=	'shipping';
		}
	
	$permission_array	=	array(	'add'		=>	$objAdmin->GetmenuPermission(0,$fId,'add'),
									'edit'		=>	$objAdmin->GetmenuPermission(0,$fId,'edit'),
									'delete'	=>	$objAdmin->GetmenuPermission(0,$fId,'delete'));
    $framework->tpl->assign("STORE_PERMISSION",$permission_array) ;
}
session_start();
# The following block of code  is for store implementation Ends heree....................




	include_once(FRAMEWORK_PATH."/modules/order/lib/class.order.php");
	include_once(FRAMEWORK_PATH."/modules/order/lib/class.shipping.php");
	include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
	include_once(FRAMEWORK_PATH."/modules/extras/lib/class.extras.php");
	include_once(FRAMEWORK_PATH."/modules/order/lib/class.payment.php");
	
	
	$act				=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
	$pageNo 			= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
	$orderBy 			= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
	$limit				=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
	$param				=	"mod=$mod&pg=$pg&act=$act&orderBy=$orderBy&fId=$fId&sId=$sId";
	if(empty($limit))
		$_REQUEST["limit"]		=	"20";
	
	$ShippingObj	=	new Shipping();
	$objPayment		=	new Payment();
	
	
		
	switch($_REQUEST['act']) {
		
		case "shippingselmethodslist":
		
			
			$Submit			=	$_REQUEST['Submit'];
			
			if(!$_REQUEST['intr_frs_status']){
				$_REQUEST['intr_frs_status']='N';
			}
			
			if($Submit == 'Submit') {
				if($_REQUEST['shipping_status']=='Y')	{
						$req=&$_REQUEST;
						$req['store_id']=$store_id;
						$ShippingObj->saveInternationalFlatRateShipping($req);
						setMessage("Updated successfully",MSG_SUCCESS);
						//$framework->tpl->assign("MESSAGE", "Updated successfully");
				}
				else{
					$req=&$_REQUEST;
					$req['store_id']=$store_id;
					$ShippingObj->saveInternationalFlatRateShipping($req);
					setMessage("Updated successfully",MSG_SUCCESS);
					//$framework->tpl->assign("MESSAGE", "Updated successfully");
				}
				
				
					$ShippingObj->saveInternationalMessage($_REQUEST);
					if($framework->config['pay_invoice']=="Y")
					{
						$ShippingObj->saveInvoiceMessage($_REQUEST);
					}
					
			}			
			
			if($_REQUEST['mod'] == 'store') {
				$StoreId	=	$ShippingObj->getStoreIdFromStoreName($_REQUEST['storename']);
				$Stores['id'][0]		=	$StoreId;
				$Stores['name'][0]		=	$_REQUEST['storename'];
				$store_id				=	$StoreId;	
			} else {
				$Stores				=	$ShippingObj->getStoreCombo();
				$store_id			=	(trim($_REQUEST['store_id']) != '') ? $_REQUEST['store_id'] : 0;
			}
			
			
			$IntrnlMessage	=	$ShippingObj->getInternationalMessageDetails();
			
			$ShippingMethods	=	$ShippingObj->getShippingMethodsOfStore($store_id);
			//print_r($ShippingMethods);exit;
			
			//code modified by robin
			
				if($global['payment_receiver']=='store')
					 {
						 $flg=0;
						 foreach($ShippingMethods as $ship)
						 {	if($ship[selected]=='Y')
							$flg++;
						 }
						 /*
							if(!$flg)
								//$_SESSION[storeSess][0]->statusMsg="Your store is currently In-active. Please set up your Shipping information for your stores.";
							else
							unset($_SESSION[storeSess][0]->statusMsg);*/
					}

			if($ShippingObj->config['payment_receiver'] == 'admin')
				$store_id	=	0;
			
			$framework->tpl->assign("INTRNL_MESSAGE", $IntrnlMessage);
			$framework->tpl->assign("STORE_OWNER", $ShippingObj->config['payment_receiver']);
			$framework->tpl->assign("STORE_ID", $store_id);			
			$framework->tpl->assign("SHIPMETHODLIST", $ShippingMethods);
			
			
			
			$framework->tpl->assign("SELECTED_STORE_ID", $store_id);
			$framework->tpl->assign("STORES", $Stores);
			
			### Start showing Pay Invoice message Nov-30-2007 by shinu ###
			if($framework->config['pay_invoice']=="Y")
			{
				$framework->tpl->assign("PAY_INVOICE", "Y");
				$InvoiceMessage	=	$ShippingObj->getPayinvoiceMessage();
				$framework->tpl->assign("INVOICE_MESSAGE", $InvoiceMessage);
				
			}
			
			if($framework->config['enable_flat_rate_shipping']=="Y")
			{
				$flat_rate_shipping=$ShippingObj->getFlatRateShipping($store_id);
				$framework->tpl->assign("FLAT_SHIPPING",$flat_rate_shipping);
			}
			
		
			
			### end showing Pay Invoice message Nov-30-2007 by shinu ###
			
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/selectedshippingmethodlist.tpl");
			break;
		
		case "upsform";	
			$Submit		=	$_REQUEST['Submit'];
			if($Submit == 'Submit') {
				$StatusMsg	=	$ShippingObj->validateUpsForm($_REQUEST);
				if($StatusMsg === TRUE) {
					$ShippingObj->saveUpsForm($_REQUEST);
					//added by adarsh for changeing the message at the top
					$objPayment->getStorePaymentMsg($store_id);
					
					redirect(makeLink(array("mod"=> $module, "pg"=> $Page), "act=shippingselmethodslist&fId={$_REQUEST['fId']}&sId={$_REQUEST['sId']}&store_id={$_REQUEST['store_id']}"));
				} else {
					setMessage($StatusMsg);
					$framework->tpl->assign("FORM_VALUES", $_REQUEST);
				}
			} else {
				$FormData	=	$ShippingObj->getFormData($_REQUEST);
				$framework->tpl->assign("FORM_VALUES", $FormData);
			}
			
			$framework->tpl->assign("UPS_PICKUP_TYPES", $ShippingObj->getUpsPickupTypes());
			$framework->tpl->assign("UPS_PACKAGE_TYPES", $ShippingObj->getPackageTypes());
			$framework->tpl->assign("SHIP_SERVICES", $ShippingObj->getUpsShipServices($_REQUEST['store_id']));
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/upsform.tpl");
			break;
		
		case "uspsform";	
			$Submit		=	$_REQUEST['Submit'];
			if($Submit == 'Submit') {
				$StatusMsg	=	$ShippingObj->validateUspsForm($_REQUEST);
				if($StatusMsg === TRUE) {
					$ShippingObj->saveUspsForm($_REQUEST);
					redirect(makeLink(array("mod"=> $module, "pg"=> $Page), "act=shippingselmethodslist&fId={$_REQUEST['fId']}&sId={$_REQUEST['sId']}&store_id={$_REQUEST['store_id']}"));
				} else {
					setMessage($StatusMsg);
					$framework->tpl->assign("FORM_VALUES", $_REQUEST);
				}
			} else {
				$FormData	=	$ShippingObj->getFormData($_REQUEST);			
				$framework->tpl->assign("FORM_VALUES", $FormData);
			}
		
			$framework->tpl->assign("USPS_INTNL_SERVICES", $ShippingObj->getInternationalUspsServices($_REQUEST['store_id']));
			$framework->tpl->assign("USPS_DOMESTIC_SERVICES", $ShippingObj->getDomesticUspsServices($_REQUEST['store_id']));
			$framework->tpl->assign("USPS_BOX_TYPES", $ShippingObj->getUspsBoxTypes());
			$framework->tpl->assign("USPS_PACKAGE_TYPES", $ShippingObj->getUspsPackageTypes());
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/uspsform.tpl");
			break;
		
		case "fedexform";	
			$Submit		=	$_REQUEST['Submit'];
			if($Submit == 'Submit') {
				$StatusMsg	=	$ShippingObj->validateFedexForm($_REQUEST);
				if($StatusMsg === TRUE) {
					$ShippingObj->saveFedexForm($_REQUEST);
					//added by adarsh for changeing the message at the top
					$objPayment->getStorePaymentMsg($store_id);
					
					redirect(makeLink(array("mod"=> $module, "pg"=> $Page), "act=shippingselmethodslist&fId={$_REQUEST['fId']}&sId={$_REQUEST['sId']}&store_id={$_REQUEST['store_id']}"));
				} else {
					setMessage($StatusMsg);
					$framework->tpl->assign("FORM_VALUES", $_REQUEST);
				}
			} else {
				$FormData	=	$ShippingObj->getFormData($_REQUEST);			
				$framework->tpl->assign("FORM_VALUES", $FormData);
			}
			
			$framework->tpl->assign("FEDEX_DROPOFF_TYPE", $ShippingObj->getFedexDropOffTypes());
			$framework->tpl->assign("COUNTRIES", $ShippingObj->getCountryComboValues());
			$framework->tpl->assign("FEDEX_PACKAGE_TYPE", $ShippingObj->getFedexPackageTypes());
			$framework->tpl->assign("FEDEX_SHIPPING_SERVICES", $ShippingObj->getFedexShippingServices($_REQUEST['store_id']));
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/fedexform.tpl");
			break;	
		
		case "canadaPostform";	
			$Submit		=	$_REQUEST['Submit'];
			if($Submit == 'Submit') {
				$StatusMsg	=	$ShippingObj->validateCanadaPostForm($_REQUEST);
				if($StatusMsg === TRUE) {
					$ShippingObj->saveCanadaPostForm($_REQUEST);
					redirect(makeLink(array("mod"=> $module, "pg"=> $Page), "act=shippingselmethodslist&fId={$_REQUEST['fId']}&sId={$_REQUEST['sId']}&store_id={$_REQUEST['store_id']}"));
				} else {
					setMessage($StatusMsg);
					$framework->tpl->assign("FORM_VALUES", $_REQUEST);
				}
			} else {
				$FormData	=	$ShippingObj->getFormData($_REQUEST);			
				$framework->tpl->assign("FORM_VALUES", $FormData);
			}
			
			$framework->tpl->assign("COUNTRIES", $ShippingObj->getCountryComboValues());
			//$framework->tpl->assign("CANADAPOST_SHIPPING_SERVICES", $ShippingObj->getCanadaPostShippingServices($_REQUEST['store_id']));
			
			$framework->tpl->assign("CANADAPOST_INTNL_SERVICES", $ShippingObj->getCanadaPostIntlShippingServices($_REQUEST['store_id']));
			$framework->tpl->assign("CANADAPOST_DOMESTIC_SERVICES", $ShippingObj->getCanadaPostDomesticShippingServices($_REQUEST['store_id']));
			$framework->tpl->assign("CANADAPOST_USA_SERVICES", $ShippingObj->getCanadaPostUsaShippingServices($_REQUEST['store_id']));
			
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/canadaPost.tpl");
			break;
		case "dhlform";	
			$Submit		=	$_REQUEST['Submit'];
			if($Submit == 'Submit') {
				$StatusMsg	=	$ShippingObj->validateDhlForm($_REQUEST);
				if($StatusMsg === TRUE) {
					$ShippingObj->saveDhlForm($_REQUEST);
					redirect(makeLink(array("mod"=> $module, "pg"=> $Page), "act=shippingselmethodslist&fId={$_REQUEST['fId']}&sId={$_REQUEST['sId']}&store_id={$_REQUEST['store_id']}"));
				} else {
					setMessage($StatusMsg);
					$framework->tpl->assign("FORM_VALUES", $_REQUEST);
				}
			} else {
				$FormData	=	$ShippingObj->getFormData($_REQUEST);
				$framework->tpl->assign("FORM_VALUES", $FormData);
			}
			
			$framework->tpl->assign("DHL_NORMAL_SERVICES", $ShippingObj->getDhlNormalService());
			$framework->tpl->assign("DHL_SPECIAL_SERVICES", $ShippingObj->getDhlSpecialService());
			$framework->tpl->assign("DHL_SHIPPING_TYPE", $ShippingObj->getDhlShippingType());
			$framework->tpl->assign("DHL_BILLING_TYPE", $ShippingObj->getDhlBillingType());
			$framework->tpl->assign("DHL_ADDITIONAL_PROTECTION", $ShippingObj->getDhlAddProtection());
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/dhlform.tpl");
		break;
		
		
		
		case 'InterShipperform':
			/**
			 * Case for handling InterShipper shipping web service
			 */
			$store_id		=	$_REQUEST['store_id'];
			$ShipServices	=	$ShippingObj->getInterShipperServices($store_id);
			
			$Submit		=	$_REQUEST['Submit'];
			if($Submit == 'Submit') {
				$ShippingObj->saveInterShipperForm($_REQUEST);
				redirect(makeLink(array("mod"=> $module, "pg"=> $Page), "act=shippingselmethodslist&fId={$_REQUEST['fId']}&sId={$_REQUEST['sId']}&store_id={$_REQUEST['store_id']}"));
			} else {
				$FormData	=	$ShippingObj->getFormData($_REQUEST);
				$framework->tpl->assign("FORM_VALUES", $FormData);
			}

			$ShippingClasses	=	$ShippingObj->getInterShipperShippingServiceClasses($FormData['InterClasses']);
			$DeliveryTypes		=	$ShippingObj->getInterShipperDeliveryTypes();
			$ShipMethods		=	$ShippingObj->getInterShipperShippingMethods();
			$PackagingTypes		=	$ShippingObj->getInterShippingPackagingTypes();
			$ContentTypes		=	$ShippingObj->getInterShipperShippingContents();
			
			$framework->tpl->assign('CONTENT_TYPES', $ContentTypes);
			$framework->tpl->assign('PACKAGING_TYPES', $PackagingTypes);
			$framework->tpl->assign('SHIP_METHODS', $ShipMethods);
			$framework->tpl->assign('DELIVERY_TYPES', $DeliveryTypes);
			$framework->tpl->assign('SHIP_CLASSES', $ShippingClasses);
			$framework->tpl->assign('SHIP_SERVICES', $ShipServices);
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/intershipform.tpl");
			break;


		
		case "selectshippingmethod":
			$ShippingObj->assignShippingMethodToStore($_REQUEST);
			$FormAct	=	$ShippingObj->getFormNameOfShippingMethod($_REQUEST);
			
			redirect(makeLink(array("mod"=> $module, "pg"=> $Page), "act=$FormAct&fId={$_REQUEST['fId']}&sId={$_REQUEST['sId']}&store_id={$_REQUEST['store_id']}"));
			break;
		
		case "unselectshippingmethod":
			$ShippingObj->unAssignShippingMethodFromStore($_REQUEST);
			redirect(makeLink(array("mod"=> $module, "pg"=> $Page), "act=shippingselmethodslist&fId={$_REQUEST['fId']}&sId={$_REQUEST['sId']}&store_id={$_REQUEST['store_id']}"));
			break;
		
		case "shippinglist":
			$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
			list($rs, $numpad, $cnt, $limitList)	= 	$ShippingObj->listAllShippingMethods($pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy);
			$framework->tpl->assign("SHIPMETHODLIST", $rs);
			$framework->tpl->assign("PAYMETHOD_NUMPAD", $numpad);
			$framework->tpl->assign("PAYMETHOD_LIMIT", $limitList);
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/shippingmethodlist.tpl");
			break;
		#### Added For Australia Post Shipping....
		#### Author Jipson Thomas.................
		#### Dated  : 25February 2008.............
		case "auspostform";	
			$Submit		=	$_REQUEST['Submit'];
			if($Submit == 'Submit') {
				$StatusMsg	=	$ShippingObj->validateAusPostForm($_REQUEST);
				if($StatusMsg ===TRUE) {
					$ShippingObj->saveAusPostForm($_REQUEST);
					redirect(makeLink(array("mod"=> $module, "pg"=> $Page), "act=shippingselmethodslist&fId={$_REQUEST['fId']}&sId={$_REQUEST['sId']}&store_id={$_REQUEST['store_id']}"));
				} else {
					setMessage($StatusMsg);
					$framework->tpl->assign("FORM_VALUES", $_REQUEST);
				}
			} else {
				$FormData	=	$ShippingObj->getFormData($_REQUEST);			
				$framework->tpl->assign("FORM_VALUES", $FormData);
			}
			
			$framework->tpl->assign("COUNTRIES", $ShippingObj->getCountryComboValues());
			$framework->tpl->assign("FEDEX_SHIPPING_SERVICES", $ShippingObj->getAusPostShippingServices($_REQUEST['store_id']));
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/AusPostform.tpl");
		break;	
		#### End of Australia shipping Form.......
			
		case "shipformform":
			$id 		= 	$_REQUEST["id"] ? $_REQUEST["id"] : "0";

			if($_SERVER['REQUEST_METHOD'] == "POST") {
				header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
				header('Cache-Control: Private');
				$message 	= 	$ShippingObj->addEditShippingMethod($_REQUEST,$_FILES);
				if($message === true) {
					$action = $req['id'] ? "Updated" : "Added";
					setMessage("Shipping Method $action Successfully", MSG_SUCCESS);
					redirect(makeLink(array("mod"=> $module, "pg"=> $Page), "act=shippinglist&fId=$fId&sId=$sId"));
				}
				setMessage($message);
			}
			if($message) {
				$framework->tpl->assign("SHIPPING", $_POST);
			} elseif($_REQUEST['id']) {
				$framework->tpl->assign("SHIPPING", $ShippingObj->getShippingMethodDetails($_REQUEST['id']));
			}
			
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/shippingmethodform.tpl");
			break;	


		case "shipmethoddelete":
			$ShippingObj->removeShipMethods($_REQUEST);
			setMessage("Shipping Methods Removed Successfully", MSG_SUCCESS);
			redirect(makeLink(array("mod"=> $module, "pg"=> $Page), "act=shippinglist&fId=$fId&sId=$sId"));
			break;
			
			
		case "shipmethodactivate":
			$ShippingObj->activateShipMethods($_REQUEST);
			setMessage("Shipping Methods Activated Successfully", MSG_SUCCESS);
			redirect(makeLink(array("mod"=> $module, "pg"=> $Page), "act=shippinglist&fId=$fId&sId=$sId"));
			break;	
		
		
		case "shipmethodinactive":
			$ShippingObj->deActivateShipMethods($_REQUEST);
			setMessage("Shipping Methods Deactivated Successfully", MSG_SUCCESS);
			redirect(makeLink(array("mod"=> $module, "pg"=> $Page), "act=shippinglist&fId=$fId&sId=$sId"));
			break;		
		

	} # Close Switch statement
	
	
	
if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}

?>