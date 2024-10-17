<?php 
/**
 * Admin Payment type management
 *
 * @author sajith
 * @package defaultPackage
 */


# The following block of code is for store implementation --> ## Put at the Header of the shipping.php file
if($_REQUEST['manage']=="manage"){
	authorize_store();
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("PG","order_paymentType") ;
	$permission_array	=	array(	'add'		=>	$objAdmin->GetmenuPermission($store_id,$fId,'add'),
									'edit'		=>	$objAdmin->GetmenuPermission($store_id,$fId,'edit'),
									'delete'	=>	$objAdmin->GetmenuPermission($store_id,$fId,'delete'));
    $framework->tpl->assign("STORE_PERMISSION",$permission_array) ;
}else{
	authorize();
	$framework->tpl->assign("MOD","order") ;
	$framework->tpl->assign("PG","paymentType") ;
	$permission_array	=	array(	'add'		=>	$objAdmin->GetmenuPermission(0,$fId,'add'),
									'edit'		=>	$objAdmin->GetmenuPermission(0,$fId,'edit'),
									'delete'	=>	$objAdmin->GetmenuPermission(0,$fId,'delete'));
    $framework->tpl->assign("STORE_PERMISSION",$permission_array) ;
}
session_start();
# The following block of code  is for store implementation Ends heree....................




include_once(FRAMEWORK_PATH."/modules/order/lib/class.order.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.paymenttype.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/extras/lib/class.extras.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.payment.php");

$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
$param			=	"mod=$mod&pg=$pg&act=$act&orderBy=$orderBy&fId=$fId&sId=$sId";
if(empty($limit))
	$_REQUEST["limit"]		=	"20";

$order 				= 	new Order();
$typeObj 			= 	new paymentType();
$objPayment			=	new Payment();
switch($_REQUEST['act']) {
    case "type":
		if($_REQUEST['action'] == 1)
		setMessage("Paypal Information Updated Successfully",MSG_SUCCESS);
	list($rs, $numpad, $cnt, $limitList)	= 	$typeObj->listAllPaymentModes($pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy);
		$framework->tpl->assign("PAYMENTMODE", $rs);
		$framework->tpl->assign("PAYMENTMODE_NUMPAD", $numpad);
		$framework->tpl->assign("PAYMENTMODE_LIMIT", $limitList);
    	//$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/paymentTypeList.tpl");
        break;
		
	case "creditlist":
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
		list($rs, $numpad, $cnt, $limitList)	= 	$typeObj->listAllPaymentTypesCards($pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy);
		
		##########################################################################
		$payment_type 		= 	"credit_card";
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			if( ($message 	= 	$typeObj->gatewayAddEdit($req,$payment_type)) === true ) {
				$action = $payment_type ? "Updated" : "Added";
            	setMessage("$sId $action Successfully", MSG_SUCCESS);
			}
			setMessage($message);
		}
		if($message) {
			$framework->tpl->assign("GATEWAY_DATA", $_POST);
		} elseif($payment_type) {
			$framework->tpl->assign("GATEWAY_DATA", $typeObj->GetGatewayInfo($payment_type));
		}
		##########################################################################
		
		$framework->tpl->assign("PAYMENTTYPE_LIST", $rs);
		$framework->tpl->assign("PAYMENTTYPE_NUMPAD", $numpad);
		$framework->tpl->assign("PAYMENTTYPE_LIMIT", $limitList);
		
		# The following code written by shinu modified by vimson for hiding the payment gateway details
		# $framework->tpl->assign("GATEWAY", FRAMEWORK_PATH."/modules/order/tpl/creditcard_gateway_form.tpl");  {include file="`$GATEWAY`"}
		
		
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/creditcard_list.tpl");
		break;	
		
		case "maillist":
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "position";
		list($rs, $numpad, $cnt, $limitList)	= 	$typeObj->listAllMailCheckFields($pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy);
		$framework->tpl->assign("MAIL_LIST", $rs);
		$framework->tpl->assign("MAIL_NUMPAD", $numpad);
		$framework->tpl->assign("MAIL_LIMIT", $limitList);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/mail_check_list.tpl");
		break;	
			
	case "creditform":
		$id 		= 	$_REQUEST["id"] ? $_REQUEST["id"] : "0";
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			$fname			=	basename($_FILES['logo_extension']['name']);
			$ftype			=	$_FILES['logo_extension']['type'];
			$tmpname		=	$_FILES['logo_extension']['tmp_name'];
			if( ($message 	= 	$typeObj->madeAddEdit($req,$fname,$tmpname)) === true ) {
				$action = $req['id'] ? "Updated" : "Added";
            	setMessage("$sId $action Successfully", MSG_SUCCESS);
				redirect(makeLink(array("mod"=>"order", "pg"=>"paymentType"), "act=creditlist&fId=$fId&sId=$sId"));
			}
			setMessage($message);
			

		}
		if($message) {
			$framework->tpl->assign("PAYMENT", $_POST);
		} elseif($_REQUEST['id']) {
			$framework->tpl->assign("PAYMENT", $typeObj->GetPaymentType($_REQUEST['id']));
		}
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/creditcard_form.tpl");
		break;
		
	case "mailform":
		$id 		= 	$_REQUEST["id"] ? $_REQUEST["id"] : "0";
		if($_SERVER['REQUEST_METHOD'] == "POST") { 
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			if( ($message 	= 	$typeObj->checkAddEdit($req)) === true ) {
				$action = $req['id'] ? "Updated" : "Added";
            	setMessage("$sId $action Successfully", MSG_SUCCESS);
				redirect(makeLink(array("mod"=>"order", "pg"=>"paymentType"), "act=maillist&fId=$fId&sId=$sId"));
			}
			setMessage($message);
		}
		if($message) {
			$framework->tpl->assign("CHECK", $_POST);
		} elseif($_REQUEST['id']) {
			$framework->tpl->assign("mailform", $typeObj->GetCheckFields($_REQUEST['id']));
		}
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/mail_check_form.tpl");
		break;
		
		case "delete":
			$i=0;//cannot delete
			$j=0;//deleted
			if(count($creditcard_id)>0)
			{
			foreach ($creditcard_id as $id)
				{
					$status=$typeObj->creditcardDelete($id);
				}
			}
			redirect(makeLink(array("mod"=>"order", "pg"=>"paymentType"), "act=creditlist&fId=$fId&sId=$sId"));
			break;
			
			case "active":
			$mode_id=$_REQUEST['mode_id'];
			$i=0;//cannot delete
			$j=0;//deleted
			if(count($mode_id)>0)
			{
			foreach ($mode_id as $id)
				{
					$status=$typeObj->paymentTypeMakeActive($id);
				}
			}
			redirect(makeLink(array("mod"=>"order", "pg"=>"paymentType"), "act=type&fId=$fId&sId=$sId"));
			break;
			
		case "inactive":
			$mode_id=$_REQUEST['mode_id'];
			$i=0;//cannot delete
			$j=0;//deleted
			if(count($mode_id)>0)
			{
			foreach ($mode_id as $id)
				{
					$status=$typeObj->paymentTypeMakeInactive($id);
				}
			}
			redirect(makeLink(array("mod"=>"order", "pg"=>"paymentType"), "act=type&fId=$fId&sId=$sId"));
			break;
			
		case "ccardactive":
			$i=0;//cannot delete
			$j=0;//deleted
			if(count($creditcard_id)>0)
			{
			foreach ($creditcard_id as $id)
				{
					$status=$typeObj->creditCardMakeActive($id);
				}
			}
			redirect(makeLink(array("mod"=>"order", "pg"=>"paymentType"), "act=creditlist&fId=$fId&sId=$sId"));
			break;
			
		case "ccardinactive":
			$i=0;//cannot delete
			$j=0;//deleted
			if(count($creditcard_id)>0)
			{
			foreach ($creditcard_id as $id)
				{
					$status=$typeObj->creditCardMakeInactive($id);
				}
			}
			redirect(makeLink(array("mod"=>"order", "pg"=>"paymentType"), "act=creditlist&fId=$fId&sId=$sId"));
			break;
		
		case "paypalform":
			
			$Submit		=	$_REQUEST['Submit'];
					
			if($framework->config['payment_receiver'] == 'store' || $_REQUEST['mod'] == 'store') {
				$storename	=	$_REQUEST['storename'];
				if(trim($store_name) == '')
					redirect(makeLink(array("mod"=>"home", "pg"=>"paymentType"), "act=paypalformforstore&fId=$fId&sId=$sId&storename=$storename"));
				else
					redirect(makeLink(array("mod"=>"store", "pg"=>"home_paymentType"), "act=paypalformforstore&fId=$fId&sId=$sId&storename=$storename"));
			}
								
			if($Submit == 'Submit') {
				$StatusMsg	=	$typeObj->validatePaypalStandardForm($_REQUEST);
				if($StatusMsg == '') {
					$typeObj->savePaypalStandardAccount($_REQUEST);
					redirect(makeLink(array("mod"=>"order", "pg"=>"paymentType"), "act=type&fId=$fId&sId=$sId"));
				} else {
					setMessage($StatusMsg);
					$framework->tpl->assign("FORM_VALUES", $_REQUEST);
				} 
			} else {
				$FormData	=	$typeObj->getPaypalStandardAccount();
				$framework->tpl->assign("FORM_VALUES", $FormData);
			}
			
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/paypalstandardform.tpl");
			break;
		
		case "paypalformforstore":
			
			$Submit		=	$_REQUEST['Submit'];
						
			if($Submit == 'Submit') {
			unset($_REQUEST['account_mailaddressconfirm']);
			
				$StatusMsg	=	$objPayment->validateStorePayflowLinkForm($_REQUEST);
				
				if($StatusMsg == '') {
					$objPayment->saveStorePayflowLinkDetails($_REQUEST);
					//added by adarsh for changeing the message at the top
					
					$objPayment->getStorePaymentMsg($store_id);
					
					redirect(makeLink(array("mod"=>$mod, "pg"=>$pg), "act=type&fId=$fId&sId=$sId&action=1"));
				} else {
					setMessage($StatusMsg);
					$framework->tpl->assign("FORM_VALUES", $_REQUEST);
				}
			}
								
			if($_REQUEST['mod'] == 'store') {
				$StoreId				=	$objPayment->getStoreIdFromStoreName($_REQUEST['storename']);
				$Stores['id'][0]		=	$StoreId;
				//$Stores['heading'][0]	=	$_REQUEST['storename'];
				$Stores['heading'][0]	=	$_SESSION['storeSess'][0]->heading;
				$store_id				=	$StoreId;	
			} else {
				$Stores			=	$objPayment->getStoreCombo();
				$store_id		=	(trim($_REQUEST["store_id"]) != '') ? $_REQUEST["store_id"] : $Stores['id'][0];
			}

			$PayflowDetails		=	$objPayment->getPayflowLinkStoreAccountDetails($store_id);
			//print_r($PayflowDetails);
			
									
			$framework->tpl->assign("PAYMENT_RECEIVER", $framework->config['payment_receiver']);
			$framework->tpl->assign("FORM_VALUES", $PayflowDetails);
			$framework->tpl->assign("CURRENCYLIST", $typeObj->getCurrencyList('Y'));
			$framework->tpl->assign("STORES", $Stores);
			$framework->tpl->assign("SELECTED_STORE_ID", $store_id);		
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/storepaypalstandardform.tpl");
			break;
			
			case "websitesetting":
			
			$Submit		=	$_REQUEST['Submit'];
						
			if($Submit == 'Submit') {
			unset($_REQUEST['account_mailaddressconfirm']);
			
				$objPayment->saveStoresettingDetails($_REQUEST);
				setMessage("Webstore Settings updated sucessfully",MSG_SUCCESS);
				redirect(makeLink(array("mod"=>$mod, "pg"=>$pg), "act=websitesetting&fId=$fId&sId=$sId&action=1"));
			}
								
			if($_REQUEST['mod'] == 'store') {
				$StoreId				=	$objPayment->getStoreIdFromStoreName($_REQUEST['storename']);
				$Stores['id'][0]		=	$StoreId;
				//$Stores['heading'][0]	=	$_REQUEST['storename'];
				$Stores['heading'][0]	=	$_SESSION['storeSess'][0]->heading;
				$store_id				=	$StoreId;	
			} else {
				$Stores			=	$objPayment->getStoreCombo();
				$store_id		=	(trim($_REQUEST["store_id"]) != '') ? $_REQUEST["store_id"] : $Stores['id'][0];
			}

			$PayflowDetails		=	$objPayment->getPayflowLinkStoreAccountDetails($store_id);
			//print_r($PayflowDetails);
			
									
			$framework->tpl->assign("PAYMENT_RECEIVER", $framework->config['payment_receiver']);
			$framework->tpl->assign("FORM_VALUES", $PayflowDetails);
			$framework->tpl->assign("CURRENCYLIST", $typeObj->getCurrencyList('Y'));
			$framework->tpl->assign("STORES", $Stores);
			$framework->tpl->assign("SELECTED_STORE_ID", $store_id);
			$framework->tpl->assign("SMTP", $framework->config['smtp_store']);		
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/storewebsettingform.tpl");
			break;
			
			case "twocheckout":
			
			$Submit		=	$_REQUEST['Submit'];
						
			if($Submit == 'Submit') {
				$StatusMsg	=	$objPayment->validateStore2checkoutForm($_REQUEST);
				if($StatusMsg == '') {
					$objPayment->saveStore2checkoutDetails($_REQUEST);
					redirect(makeLink(array("mod"=>$mod, "pg"=>$pg), "act=type&fId=$fId&sId=$sId"));
				} else {
					setMessage($StatusMsg);
					$framework->tpl->assign("FORM_VALUES", $_REQUEST);
				}
			}
								
			if($_REQUEST['mod'] == 'store') {
				$StoreId				=	$objPayment->getStoreIdFromStoreName($_REQUEST['storename']);
				$Stores['id'][0]		=	$StoreId;
				$Stores['name'][0]		=	$_REQUEST['storename'];
				$store_id				=	$StoreId;	
			} else {
				$Stores			=	$objPayment->getStoreCombo();
				$store_id		=	(trim($_REQUEST["store_id"]) != '') ? $_REQUEST["store_id"] : $Stores['id'][0];
			}

			$PayflowDetails		=	$objPayment->get2checkoutStoreAccountDetails($store_id);
									
			$framework->tpl->assign("PAYMENT_RECEIVER", $framework->config['payment_receiver']);
			$framework->tpl->assign("FORM_VALUES", $PayflowDetails);
			$framework->tpl->assign("STORES", $Stores);
			$framework->tpl->assign("SELECTED_STORE_ID", $store_id);		
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/2checkout.tpl");
			break;
			
			case "worldpayform":
			
			$Submit		=	$_REQUEST['Submit'];
						
			if($Submit == 'Submit') {
				$StatusMsg	=	$objPayment->validateStoreWorldpayForm($_REQUEST);
				if($StatusMsg == '') {
					$objPayment->saveStoreWorldpaytDetails($_REQUEST);
					redirect(makeLink(array("mod"=>$mod, "pg"=>$pg), "act=type&fId=$fId&sId=$sId"));
				} else {
					setMessage($StatusMsg);
					$framework->tpl->assign("FORM_VALUES", $_REQUEST);
				}
			}
								
			if($_REQUEST['mod'] == 'store') {
				$StoreId				=	$objPayment->getStoreIdFromStoreName($_REQUEST['storename']);
				$Stores['id'][0]		=	$StoreId;
				$Stores['name'][0]		=	$_REQUEST['storename'];
				$store_id				=	$StoreId;	
			} else {
				$Stores			=	$objPayment->getStoreCombo();
				$store_id		=	(trim($_REQUEST["store_id"]) != '') ? $_REQUEST["store_id"] : $Stores['id'][0];
			}

			$PayflowDetails		=	$objPayment->getWorldpayStoreAccountDetails($store_id);
									
			$framework->tpl->assign("PAYMENT_RECEIVER", $framework->config['payment_receiver']);
			$framework->tpl->assign("FORM_VALUES", $PayflowDetails);
			$framework->tpl->assign("STORES", $Stores);
			$framework->tpl->assign("SELECTED_STORE_ID", $store_id);		
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/worldpayform.tpl");
			break;
			
			
			case "googlecheckoutform":
			
			$Submit		=	$_REQUEST['Submit'];
						
			if($Submit == 'Submit') {
				$StatusMsg	=	$objPayment->validateStoreGoogleCheckoutForm($_REQUEST);
				if($StatusMsg == '') {
					$objPayment->saveStoreGoogleCheckoutDetails($_REQUEST);
					redirect(makeLink(array("mod"=>$mod, "pg"=>$pg), "act=type&fId=$fId&sId=$sId"));
				} else {
					setMessage($StatusMsg);
					$framework->tpl->assign("FORM_VALUES", $_REQUEST);
				}
			}
								
			if($_REQUEST['mod'] == 'store') {
				$StoreId				=	$objPayment->getStoreIdFromStoreName($_REQUEST['storename']);
				$Stores['id'][0]		=	$StoreId;
				$Stores['name'][0]		=	$_REQUEST['storename'];
				$store_id				=	$StoreId;	
			} else {
				$Stores			=	$objPayment->getStoreCombo();
				$store_id		=	(trim($_REQUEST["store_id"]) != '') ? $_REQUEST["store_id"] : $Stores['id'][0];
			}

			$PayflowDetails		=	$objPayment->getGoogleCheckoutStoreAccountDetails($store_id);
			$PayflowDetails1	=	$objPayment->getGoogleCheckoutStoreAccountDetails1($store_id);
			$framework->tpl->assign("PAYMENT_RECEIVER", $framework->config['payment_receiver']);
			$framework->tpl->assign("FORM_VALUES", $PayflowDetails);
			$framework->tpl->assign("FORM_VALUES1", $PayflowDetails1);
			$framework->tpl->assign("STORES", $Stores);
			$framework->tpl->assign("SELECTED_STORE_ID", $store_id);		
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/googlecheckoutform.tpl");
			break;
			
							
		case "callform":
			
			$Submit		=	$_REQUEST['Submit'];

			if($Submit == 'Submit') {
				
					$typeObj->saveCallWithCreditcardComments($_REQUEST);
					redirect(makeLink(array("mod"=>"order", "pg"=>"paymentType"), "act=type&fId=$fId&sId=$sId"));
				
			} else {
				$FormData	=	$typeObj->getCallWithCreditcardComments();
				$framework->tpl->assign("FORM_VALUES", $FormData);
			}
			
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/callform.tpl");
			break;
			
			
		case "mailcheckform":
			
			$Submit		=	$_REQUEST['Submit'];
			if($Submit == 'Submit') {
					$typeObj->saveMailaCheckComments($_REQUEST);
					redirect(makeLink(array("mod"=>"order", "pg"=>"paymentType"), "act=type&fId=$fId&sId=$sId"));
			} else {
				$FormData	=	$typeObj->getMailaCheckComments();
				$framework->tpl->assign("FORM_VALUES", $FormData);
			}
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/mailcheckform.tpl");
			
		break;
		
		
		case "minactive":
			$i=0;//cannot delete
			$j=0;//deleted
			if(count($mail_field_id)>0)
			{
			foreach ($mail_field_id as $id)
				{
					$status=$typeObj->MailCheckMakeInactive($id);
				}
			}
			redirect(makeLink(array("mod"=>"order", "pg"=>"paymentType"), "act=maillist&fId=$fId&sId=$sId"));
			break;
			
			case "mactive":
			$i=0;//cannot delete
			$j=0;//deleted
			if(count($mail_field_id)>0)
			{
			foreach ($mail_field_id as $id)
				{
					$status=$typeObj->MailCheckMakeActive($id);
				}
			}
			redirect(makeLink(array("mod"=>"order", "pg"=>"paymentType"), "act=maillist&fId=$fId&sId=$sId"));
			break;
			
			case "minactive1":
				$id		=	$_REQUEST['cmid'];
				$status=$typeObj->MailCheckMakeInactive($id);
				redirect(makeLink(array("mod"=>"order", "pg"=>"paymentType"), "act=maillist&fId=$fId&sId=$sId"));
			break;
			case "mactive1":
				$id		=	$_REQUEST['cmid'];
				$status=$typeObj->MailCheckMakeActive($id);
				redirect(makeLink(array("mod"=>"order", "pg"=>"paymentType"), "act=maillist&fId=$fId&sId=$sId"));
			break;
			case "maninactive":
				$id		=	$_REQUEST['cmid'];
				$status=$typeObj->MailCheckMakeMan($id);
				redirect(makeLink(array("mod"=>"order", "pg"=>"paymentType"), "act=maillist&fId=$fId&sId=$sId"));
			break;
			case "manactive":
				$id		=	$_REQUEST['cmid'];
				$status=$typeObj->MailCheckMakeNotman($id);
				redirect(makeLink(array("mod"=>"order", "pg"=>"paymentType"), "act=maillist&fId=$fId&sId=$sId"));
			break;
			
			case "mdelete":
			$i=0;//cannot delete
			$j=0;//deleted
			if(count($mail_field_id)>0)
			{
			foreach ($mail_field_id as $id)
				{
					$status=$typeObj->MailCheckDelete($id);
				}
			}
			redirect(makeLink(array("mod"=>"order", "pg"=>"paymentType"), "act=maillist&fId=$fId&sId=$sId"));
			break;
			
			case 'add_currency':
			
				if($_SERVER['REQUEST_METHOD']=='POST'){
				
					unset($_POST['submit']);
					
					if($_POST['currency_name']==''){
						setMessage("Please enter the currency name", MSG_ERROR);
					}
					else if($_POST['currency_shorttxt']==''){
						setMessage("Please enter the dispaly text", MSG_ERROR);
					}
					else if($_POST['symbol']==''){
						setMessage("Please enter the symbol", MSG_ERROR);
					}
					else if($_POST['currency_code']==''){
						setMessage("Please enter the currency code", MSG_ERROR);
					}
					else{
						$cid = $typeObj->saveCurrencyDetails($_POST,$_REQUEST['id']);
						redirect(makeLink(array("mod"=>"order", "pg"=>"paymentType"), "act=list_currency&fId=$fId&sId=$sId"));
					}
				}
				
				if($_REQUEST['id'] && $_REQUEST['submit'] ==''){
					$currencydet=$typeObj->getCurrencyDetails($_REQUEST['id']);	
					$_REQUEST=$currencydet;
				}		
				
				$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/add_currency.tpl");
				break;
				
			case 'list_currency':
				
				$rs=$typeObj->getCurrencyList();
				$framework->tpl->assign("CURRENCY_LIST", $rs);
				$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/currency_list.tpl");
				break;	
			case 'delete_currency':
			
				$currencydet=$typeObj->getCurrencyDetails($_REQUEST['id']);	
				if($currencydet['active']=='N'){			
					$typeObj->deleteCurrency($_REQUEST['id']);
					setMessage("Successfully deleted", MSG_ERROR);
					}
				else
					setMessage("Please deactivate the currency then delete.", MSG_ERROR);
				
				redirect(makeLink(array("mod"=>"order", "pg"=>"paymentType"), "act=list_currency&fId=$fId&sId=$sId"));
				break;	
				
			case 'saletax';
			
				if($_SERVER['REQUEST_METHOD']=='POST'){
					setMessage("sales tax saved successfully.", MSG_SUCCESS);
					$array=array('store_id'=>$store_id,'taxid'=>$_POST['saletax_opt']);
					$proceed=1;
					
						
						if($_POST['saletax_opt']==3)
						$array['custom_msg']=$_POST['custom_msg'];
						else
						{
							$rs=$typeObj->getSaletaxMessage($_POST['saletax_opt']);	
							$array['custom_msg']=$rs['message'];
						}
						
						if($_POST['id'])
							$typeObj->db->update("sale_tax_store", $array, "id={$_POST['id']}");
						else
							$typeObj->db->insert("sale_tax_store", $array);
				
				}
				if($_POST){
					$_REQUEST['taxid']=$_POST['saletax_opt'];
				}	
				 else{
				 	$taxdet=$typeObj->getSaletaxDetails($store_id);
				 	$_REQUEST['taxid']=$taxdet['taxid'];
				 }
				 
				$tax_mag=$typeObj->getSaletaxMsg();
				$taxdet=$typeObj->getSaletaxDetails($store_id);
				$framework->tpl->assign("TAX_DET", $taxdet);
				$framework->tpl->assign("TAX_MSG", $tax_mag);
				$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/saletax.tpl");
				
				break;	
				
			case 'google_analytics':
				
				if($_SERVER['REQUEST_METHOD']=='POST'){
					$str= $_POST['analytics_code'];
					if (preg_match("/.google-analytics.com/", $str)) {
					$_POST['store_id'] = $store_id;
					$typeObj->saveAnalyticsCode($_POST);
					setMessage("Analytics Code saved successfully.", MSG_SUCCESS);
					} 
					else {
						setMessage("Please enter valid google analytics code.", MSG_ERROR);
					}
				}
				
				if($_POST){
					$framework->tpl->assign("FORM_VALUES", stripcslashes($_POST['analytics_code']));
				}
				else{
					$analytics_code = $typeObj->getAnalyticsCode($store_id);
					$framework->tpl->assign("FORM_VALUES",stripcslashes($analytics_code['analytics_code']));
				}	
				$analytics_code = $typeObj->getAnalyticsCode($store_id);
				$framework->tpl->assign("ANALYTICS_DET", $analytics_code);	
				$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/google_analytics.tpl");
				
				break;	
		
 
}




## Put at the Footer of the shipping.php file
if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}

?>