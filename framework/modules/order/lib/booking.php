<?php

include_once(FRAMEWORK_PATH."/modules/album/lib/class.property.php");


$PropertyObj	=	new Property();



switch($_REQUEST['act'])
{

    case "booking_details":
    	$Params					=	"mod=$mod&pg=$pg&act={$_REQUEST['act']}";
    	$_REQUEST['limit'] 		= 	$_REQUEST['limit'] ? $_REQUEST['limit'] : 20;
    	$_REQUEST['orderBy'] 	= 	$_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : "T1.id:DESC";
    	
		list($BookingDetails, $numpad, $cnt, $limitList)	=	$PropertyObj->getBookingDetails($_REQUEST['pageNo'], $_REQUEST['limit'], $Params, ARRAY_A, $_REQUEST['orderBy']);

		$framework->tpl->assign('LIMIT_LIST', $limitList);			
    	$framework->tpl->assign('BOOKING_LIST', $BookingDetails);
    	$framework->tpl->assign('NUM_PAD', $numpad);
    	$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/booking_details.tpl");
    	break;
    
    
   	case 'invoice_list':
   		/**
   		 * The follwoing case for listing invoices at the admin side for admin reference
   		 * @author vimson@newagesmb.com
   		 */
   		
    	$Params					=	"mod=$mod&pg=$pg&act={$_REQUEST['act']}";
    	$_REQUEST['limit'] 		= 	$_REQUEST['limit'] ? $_REQUEST['limit'] : 20;
    	$_REQUEST['orderBy'] 	= 	$_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : "T1.invoice_id:DESC";
    	
    	list($Invoices, $numpad, $cnt, $limitList)	=	$PropertyObj->getInvoices($_REQUEST['pageNo'], $_REQUEST['limit'], $Params, ARRAY_A, $_REQUEST['orderBy']);
    	    	
    	$framework->tpl->assign('NUM_PAD', $numpad);
    	$framework->tpl->assign('INVOICES', $Invoices);
    	$framework->tpl->assign('LIMIT_LIST', $limitList);
    	$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/invoice_list.tpl");
    	break;	
    

    case 'deposit_list':
   		/**
   		 * The follwoing case for listing deposits at the admin side for admin reference
   		 * @author vimson@newagesmb.com
   		 * 
   		 */
   		
    	$Params					=	"mod=$mod&pg=$pg&act={$_REQUEST['act']}";
    	$_REQUEST['limit'] 		= 	$_REQUEST['limit'] ? $_REQUEST['limit'] : 20;
    	$_REQUEST['orderBy'] 	= 	$_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : "T1.id:DESC";
    	
    	list($Deposits, $numpad, $cnt, $limitList)	=	$PropertyObj->getDeposits($_REQUEST['pageNo'], $_REQUEST['limit'], $Params, ARRAY_A, $_REQUEST['orderBy']);
    	    	
    	$framework->tpl->assign('NUM_PAD', $numpad);
    	$framework->tpl->assign('DEPOSITS', $Deposits);
    	$framework->tpl->assign('LIMIT_LIST', $limitList);
    	$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/deposit_list.tpl");
    	break;			
    	
    
    case 'transaction_list':
   		/**
   		 * The follwoing case for listing Transactions at the admin side for admin reference
   		 * @author vimson@newagesmb.com
   		 * 
   		 */
   		
    	$Params					=	"mod=$mod&pg=$pg&act={$_REQUEST['act']}";
    	$_REQUEST['limit'] 		= 	$_REQUEST['limit'] ? $_REQUEST['limit'] : 20;
    	$_REQUEST['orderBy'] 	= 	$_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : "T1.id:DESC";
    	
    	list($Transactions, $numpad, $cnt, $limitList)	=	$PropertyObj->getTransactions($_REQUEST['pageNo'], $_REQUEST['limit'], $Params, ARRAY_A, $_REQUEST['orderBy']);
    	    	
    	$framework->tpl->assign('NUM_PAD', $numpad);
    	$framework->tpl->assign('TRANSACTIONS', $Transactions);
    	$framework->tpl->assign('LIMIT_LIST', $limitList);
    	$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/transaction_list.tpl");
    	break;		
    
    	
    			
	
    default:
    	;
    	
}

$framework->tpl->display($global['curr_tpl']."/admin.tpl");

?>