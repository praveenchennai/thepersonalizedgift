<?php 
/**
 * Admin Store
 *
 * @author Ajith
 * @package defaultPackage
 */
	authorize_store();
	include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
	include_once(FRAMEWORK_PATH."/modules/order/lib/class.payment.php");
	$store					= 	new Store();	
	$objPayment			=	new Payment();
	$storeDetails			=	$store->storeGetByName($_REQUEST['storename']);		
	$store_id				=	$storeDetails['id'];
	include_once(FRAMEWORK_PATH."/modules/store/lib/class.template.php");
	$template = new Template();
switch($_REQUEST['act']) {
    case "list":		
		$framework->tpl->assign("STORE_DETAILS", $template->storeGet($store_id));	
    	$_REQUEST['limit'] 	= 	$_REQUEST['limit'] ? $_REQUEST['limit'] : 20;
        list($rs, $numpad, $cnt, $limitList) = $template->cssList($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
     	$framework->tpl->assign("CSS_LIST", $rs);
		$framework->tpl->assign("STORE_ID", $store_id);
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/store/tpl/css_List.tpl");
        break; 
	case "showimage":
		$temp_id	=	$_REQUEST['css_id'];		
		$framework->tpl->assign("CSS_DETAILS", $template->getCss($temp_id));		
		$framework->tpl->display( SITE_PATH."/modules/store/tpl/show_css.tpl");
		exit;
		break;	
	case "assign_css":		
	
		$store_id	=	$_REQUEST['store_id'];
		$temp_id	=	$_REQUEST['css_id'];		
		if($_POST['hdn_avtor']=='Y')
		{
			$avtarArray=$_POST['avtar'];
		}
	  if( ($message =	$template->assignCss($store_id,$temp_id,$avtarArray[0])) === true ) {		
	  
	  
				 if($global['payment_receiver']=='store')
					{
						$StoreId				=	$objPayment->getStoreIdFromStoreName($_REQUEST['storename']);
						$PayMethodId	=	$objPayment->getPaymentMethod($StoreId, $objPayment->config['payment_receiver']);  
						$msg="Your store is currently In-active. Please set up your Payment Gateway and Shipping information for your stores.";
						if(!$PayMethodId)
						{
							$_SESSION[storeSess][0]->statusMsg=$msg;
							redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>"order_payment"), "act=paymentlist&id=".$req['menu_id']."&section_id=".$req['section_id']."&sId=Payment Type"));
						}
					}
			
			setMessage("CSS Assigned Successfully", MSG_SUCCESS);					
		}	
		 redirect(makeLink(array("mod"=>$_REQUEST['mod'],"pg"=>"css"), "act=list&sId=Manage Store"));
		 setMessage($message);
	break;  
}
if($_REQUEST['act']=="showimage"){
	exit;	
}else{
	if($_REQUEST['manage']=="manage"){
		$framework->tpl->display($global['curr_tpl']."/store.tpl");
	}else{
		$framework->tpl->display($global['curr_tpl']."/admin.tpl");
	}
}

?>