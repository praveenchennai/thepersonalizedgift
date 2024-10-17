<?php

/**
 * @desc The following page is used for handling broker functionality
 * @author vimson@newagesmb.com
 * 
 * 
 * @desc $objUser is alreay declared at index.php file in bayard folder
 * 
 * 
 * 
 */

include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.broker.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.photo.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.payment.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.search.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.payment.php");
$FlyerObj		=	new	Flyer();
$BrokerObj		=	new Broker($objUser, $FlyerObj);
$AlbumObj		=	new Album();
$PhotoObj		=	new Photo();
$search         =   new Search();
$PaymentObj		=	new Payment();
$email			= 	new Email();
$PaymentObj		= 	new Payment();

$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
$pageNo 		=	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$MemberId		=	$_SESSION['memberid'];


switch($_REQUEST['act']) {

	case 'broker_search_form':
		/**
		 * @desc The following case is used for broker search functionality
		 * 
		 * Author 	:	vimson@newagesmb.com
		 * 
		 */
		checkLogin();
	
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/brokersearchform.tpl");
		break;
	
	case 'brokersearch_results':
		/**
		 * @desc The following case displays the search results 
		 * 
		 * Author 	:	vimson@newagesmb.com
		 * 
		 */
		 
		checkLogin();
		$limit	=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "10";
		$Params	=	"mod=$mod&pg=$pg&act=brokersearch_results&criteria={$_REQUEST['criteria']}&commisionminimum={$_REQUEST['commisionminimum']}&commisionmaximum={$_REQUEST['commisionmaximum']}";
		list($BrokerDetails,$numpad,$cnt_rs,$limitList)		=	$BrokerObj->getBrokerSearchDetails($_REQUEST['criteria'],$pageNo,$limit,$Params,'',$MemberId, $_REQUEST['commisionminimum'], $_REQUEST['commisionmaximum']);
		$BrokerCount	=	count($BrokerDetails);
		//print_r($BrokerDetails);
		$Start	=	($pageNo - 1) * $limit + 1;
		$end	=	($pageNo - 1) * $limit + $limit;
		if($end > $cnt_rs)
			$end	=	$cnt_rs;
		
		$framework->tpl->assign("Start", $Start);
		$framework->tpl->assign("End", $end);
		$framework->tpl->assign("TotalFound", $cnt_rs);
		$framework->tpl->assign("SEARCH_RESULTS", $BrokerDetails);
		$framework->tpl->assign("BROKER_COUNT", $BrokerCount);
		$framework->tpl->assign("PAGE_NUMBERS", $numpad);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/brokersearchresults.tpl");
		break;	
	
	
	case 'asyncbrokersearch_results':
		$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "10";
		$Params			=	"mod=$mod&pg=$pg&act=brokersearch_results&criteria={$_REQUEST['criteria']}&commisionminimum={$_REQUEST['commisionminimum']}&commisionmaximum={$_REQUEST['commisionmaximum']}";
		$AsyncOutput	=	$BrokerObj->getAsynchronousOutputForBrokerSearchResults($_REQUEST['criteria'],$pageNo,$limit,$Params,'',$MemberId, $_REQUEST['commisionminimum'], $_REQUEST['commisionmaximum']);
		print $AsyncOutput;
		exit;	
		
		
	case 'assign_property':
	
		/**
		 * @desc The following case used for assigning properties to a broker
		 * 
		 * Author 	:	vimson@newagesmb.com
		 * 
		 */
		checkLogin();
		$Action			=	trim($_REQUEST['Action']);
		$BrokerId		=	trim($_REQUEST['BrokerId']);
						
		if($_SERVER['REQUEST_METHOD'] == 'POST' && $Action == 'Assign') {
		
			$Status	=	$BrokerObj->assignPropertyToBroker($_POST, $MemberId);
			  //===========vinoy start=============
				if($Status == TRUE) {
			      $type="assignPropertyToBroker";
				  $email->mailSend($type,$MemberId,$_POST);
				}
			 //===========vinoy End=============
			redirect(makeLink(array("mod"=>"flyer", "pg"=>"broker"), "act=assign_property&BrokerId=$BrokerId"));
		}
		
		
		if($BrokerId == '') {
			redirect(makeLink(array("mod"=>"flyer", "pg"=>"broker"), "act=broker_search_form"));
		}
		
		$limit	 			=	10;
		$Params				=	"mod=$mod&pg=$pg&act=assign_property&BrokerId=$BrokerId";
		$BrokerDetails		=	$objUser->getUserDetails($BrokerId);
		$UserDetails		=	$objUser->getUserDetails($MemberId);
		$DepositFee			=	$BrokerDetails['broker_depositfee'];
		$DepositStatus		=	$BrokerObj->whetherDepositFeePaidToBroker($BrokerId, $MemberId);
		$DepositStatus		=	($DepositStatus === FALSE && $DepositFee > 0) ? 'PENDING' : 'PAID';
		
		if($DepositStatus == 'PENDING') {
			$PaymentInfo	=	$PaymentObj->getPaypalAccountInfoOfUser($BrokerId);
			$PaypalAccount	=	$PaymentInfo['paypal_account'];
			$framework->tpl->assign("AMOUNT", $DepositFee);
			$framework->tpl->assign("MEMBER_DETAILS", $UserDetails);
			$framework->tpl->assign("PAYPAL_ACCOUNT", $PaypalAccount);
		}
		
		
		list($Properties,$numpad,$cnt,$limitList )	=	$FlyerObj->getPropertiesOfUser($_SESSION['memberid'], $pageNo, $limit, $Params, $AlbumObj, $PhotoObj);
		
		
		//print_r($Properties);
		$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
		$framework->tpl->assign("PAYPAL_POST_URL", $framework->config['paypal_post_url']);
		$framework->tpl->assign("DEPOSIT_STATUS", $DepositStatus);
		$framework->tpl->assign("PROPERTIES", $Properties);
		$framework->tpl->assign("MEM_DET", $UserDetails);
		$framework->tpl->assign("PAGE_NUMBERS", $numpad);
		$framework->tpl->assign("Broker", $BrokerDetails);
		$framework->tpl->assign("CALENDAR",SITE_PATH."/modules/album/tpl/mycalendar_search.tpl");
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/assignproperty.tpl");
		break;
	
	case 'broker_deposit_notfy':
		$BrokerId	=	$_REQUEST['BrokerId'];
		$MemberId	=	$_REQUEST['MemberId'];
		
		$BrokerObj->makeDepositPayment($_POST,$BrokerId, $MemberId);
		break;
		
	
	case 'assigned_properties':
		/**
		 * This case listed all the propertied that are assigned to a broker
		 * 
		 * Author 	:	vimson@newagesmb.com
		 * Created	:	20/12/2007 
		 * 
		 */
		checkLogin();
		$limit		=	10;
		$params		=	"mod=$mod&pg=$pg&act=assigned_properties";
		//print_r($Params	);
		$UserRoles	=	$BrokerObj->getUserRoles($MemberId);
			extract($_POST);
		if($_SERVER['REQUEST_METHOD'] == 'POST' && $Action == 'Assign') {
			$BrokerObj->acceptPropertyAssignRequest($_POST['relation_id'], $_POST['Process'], $_POST['decline_description']);
		}
		$WaitAssignedProps	=	$BrokerObj->getPropertiesWaitingForApproval($MemberId, $AlbumObj, $PhotoObj);
		$DeclinedProps		=	$BrokerObj->getDeclinedPropertiesOfUser($MemberId);
		list($BrokerProps,$numpad,$cnt_rs, $limitList)	=	$BrokerObj->getAssignedPropertiesOfBroker($MemberId, $pageNo, $limit, $params, '', $AlbumObj, $PhotoObj);
		
		$framework->tpl->assign('BROKER_NUMPAD', $numpad);
		$framework->tpl->assign('ROLES', $UserRoles);
		$framework->tpl->assign('BROKER_PROPERTIES', $BrokerProps);
		$framework->tpl->assign('DECLINED_PROPERTIES', $DeclinedProps);
		$framework->tpl->assign('APPRWAIT_PROPERTIES', $WaitAssignedProps);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/myassignedproperties.tpl");
		break;
		
	
	case 'remove_propassign_requests':
		/**
		 * The following case removes a property assign to broker life cycle. If the broker declines and user confirm that decline that record removed from the list
		 * 
		 * Author	:	vimson@newagesmb.com
		 * Created	:	26/12/2007
		 * 
		 */
		$BrokerObj->removePropertyAssignRequestToBroker($_REQUEST['relation_id']);
		redirect(makeLink(array("mod"=>"flyer", "pg"=>"broker"), "act=assigned_properties"));
		break;

	case 'my_assigned_properties':
		$limit	=	10;
		list($AssignedProps,$numpad,$cnt_rs, $limitList)	=	$BrokerObj->getMyAssignedPropertiesOfBroker($MemberId, $pageNo, $limit, $params, '', $AlbumObj, $PhotoObj, $objUser);
					
		$framework->tpl->assign('BROKER_PROPERTIES', $AssignedProps);
		$framework->tpl->assign('BROKER_NUMPAD', $numpad);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/mypropertiesassigned.tpl");
		break;
	
	case 'my_invoices':
		/**
		 * This case used for listing all the invoices reeived by a member
		 * @author vipin
		 * 
		 */
		
		$MemberDetails			=	$objUser->getUserdetails($_SESSION['memberid']);
		$invoice_broker_manager		=	$BrokerObj->getBrokerManagerInfo();
		//$Invoice_managers		=	$BrokerObj->getBrokerManagerInfo('PROP_MANAGER');
		$Now			=	date('Y-m-d H:i:s');
		$today			=	date('Y-m-d');
		if (count($invoice_broker_manager)>0){
		//print_r($invoice_broker_manager);
			foreach($invoice_broker_manager as $Broker_Manager_Array) {
			
				$Broker_Manager_Details	=	$objUser->getUserdetails($Broker_Manager_Array['assigned_user_id']);
				
				$property_id			=	$Broker_Manager_Array['property_id'];
				$owner_id				=	$Broker_Manager_Array['property_owner_id'];
				$invoice_type			=	$Broker_Manager_Array['assigned_role'];
				if ($invoice_type == 'PROP_BROKER')
					$invoice_type = 'BROKER_COMMISION';
				else
					$invoice_type = 'MANAGER_COMMISION';
				if ($invoice_type == "BROKER_COMMISION")
					$interval				=	$Broker_Manager_Details['brkrbilling_duration'];
				else
					$interval				=	$Broker_Manager_Details['mngrbilling_duration'];
					
				$Broker_Manager_Invoiceinfo		=	$BrokerObj->getInvoiceInfo($owner_id,$invoice_type,$property_id,$interval);
				$end_date				=	$Broker_Manager_Invoiceinfo['end_date'];
				$startdate 				=	$Broker_Manager_Invoiceinfo['to_date'];
				if ($startdate== "")
					$startdate			=	$Broker_Manager_Array['accept_time'];
				if ($interval == '')
					$interval = 0;
					//test variable
					$interval = 0;
				if ($end_date == "" and $startdate!=""){
					list($year,$month,$day ) = split('[/.-]', $startdate);
					$end_time = mktime(0,0,0,$month,$day+$interval,$year);
					$end_date = date("Y-m-d", $end_time);
				   
				}	
				//test variable;
				$end_date = $today;
				
				if (strtotime($end_date) >= strtotime($today)){
				
					$BrokerInvoicesArray  =	$PaymentObj->getInvoiceListall($owner_id,$invoice_type,$interval,$startdate,$Broker_Manager_Array['assigned_user_id']);	
					//print_r($BrokerInvoicesArray);
					
					if (count($BrokerInvoicesArray)>0){
					$InsertArray1	=	array(
										'from_date' 			=> 	$startdate,
										'to_date' 				=> 	$Now,
										'Invoice_amount'		=> 	$BrokerInvoicesArray[0]['invoice_amount'],
										'Invoice_type'			=>	$invoice_type,
										'bill_status'			=>	$BrokerInvoicesArray[0]['invoice_status'],
										'sentto_id'				=>	$owner_id,
										'sentby_id'				=>	$Broker_Manager_Array['assigned_user_id']
										
									);
									
					$prop_bill_id = $BrokerObj->InvoiceAdd("property_bill_detail",$InsertArray1);				
					
								
				
				$BrokerObj->Updateinvoice($startdate,$today,$owner_id,$Broker_Manager_Array['assigned_user_id'],$prop_bill_id);
					
					}
					}
			
			}
		 }	
		
		$Params					=	"mod=$mod&pg=$pg&act={$_REQUEST['act']}";
    	$_REQUEST['limit'] 		= 	$_REQUEST['limit'] ? $_REQUEST['limit'] : 20;
    	$_REQUEST['orderBy'] 	= 	$_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : "T2.first_name:DESC";
    	
		list($Invoices_disp, $numpad, $cnt, $limitList) = $BrokerObj->getAllInvoiceInfo($_REQUEST['pageNo'], $_REQUEST['limit'], $Params, ARRAY_A, $_REQUEST['orderBy'], $_SESSION['memberid']);
					//print_r($Invoices_disp);
			for ($i=0; $i<count($Invoices_disp); $i++ ) {
    				$Invoices[$i]['startdate'] = $Invoices_disp[$i]['from_date'];
    				$Invoices[$i]['enddate'] = $Invoices_disp[$i]['to_date'];
					$st=date($global["date_format_new"],strtotime($Invoices_disp[$i]['from_date']));
					$gt=date($global["date_format_new"],strtotime($Invoices_disp[$i]['to_date']));
				    $Invoices_disp[$i]['from_date'] = $st;
					$Invoices_disp[$i]['to_date'] = $gt;
    			}
    		 
		$framework->tpl->assign('POST_URL', $framework->config['paypal_post_url']);
    	$framework->tpl->assign('MEMBER_ID', $_SESSION['memberid']);
    	$framework->tpl->assign('MEMBER_DETAILS', $MemberDetails);
    	$framework->tpl->assign('RECORD_LIMIT', $limitList);
    	$framework->tpl->assign('NUM_PAD', $numpad);
    	$framework->tpl->assign('INVOICE_LIST', $Invoices_disp);
		$framework->tpl->assign('POST_URL', $framework->config['paypal_post_url']);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/myinvoices.tpl");
		break;	
		
		case "view_property_assigner":
		/**
		 * This case used for listing all the invoices 
		 * @author vipin
		 * 
		 */
		$assignid = $_REQUEST['assign_id']; 
		$propbill_id = $_REQUEST['prop_bill_id'];
		$sentto_id = $_REQUEST['sentto_id'];
		$ManagerDetails	 =	$objUser->getUserdetails($assignid);
		$propallsum = $BrokerObj->getAllpropInvoicedetailbyid($propbill_id);
		$member_details = $objUser->getUserdetails($_SESSION['memberid']); 
		 $start_date = $propallsum[0]['from_date'];
		 $end_date = $propallsum[0]['to_date'];
		 $total_amount = $propallsum[0]['Invoice_amount'];
		 $invoice_type = $propallsum[0]['invoice_type'];
		 $sentbyid = $propallsum[0]['sentby_id'];
		 $status = $propallsum[0]['bill_status'];
		  list($propalldetail, $numpad, $cnt, $limitList) =  $BrokerObj->getAllpropInvoiceInfo($_REQUEST['pageNo'], $_REQUEST['limit'], $Params, ARRAY_A, $_REQUEST['orderBy'], $sentto_id,$propbill_id ,$sentbyid);
		//print_r($propalldetail);
		 for ($i=0; $i<count($propalldetail); $i++ ) {
		 			if ($propalldetail[$i]['date_booked']!=""){
    					$st=date($global["date_format_new"],strtotime($propalldetail[$i]['date_booked']));
						$propalldetail[$i]['date_booked'] = $st;
					}
		}
		
		$framework->tpl->assign('MANAGER_DETAIL', $ManagerDetails);
		$framework->tpl->assign('MEMBER_DETAILS', $member_details);
		$framework->tpl->assign('PROP_DETAIL', $propalldetail);
		$framework->tpl->assign('FLYER_NUMPAD', $numpad);
		$framework->tpl->assign('TOTAL_AMOUNT', $total_amount);
		$framework->tpl->assign('STATUS', $status);
		$framework->tpl->assign('START_DATE', $start_date);
		$framework->tpl->assign('END_DATE', $end_date);
		$framework->tpl->assign('POST_URL', $framework->config['paypal_post_url']);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/flyer/tpl/assigner_information.tpl");
		break;
		case "my_received_invoices":
		/**
		 * This case used for listing all the invoices reeived by a broker/manager
		 * @author vipin
		 * 
		 */
			$Params					=	"mod=$mod&pg=$pg&act={$_REQUEST['act']}";
    	$_REQUEST['limit'] 		= 	$_REQUEST['limit'] ? $_REQUEST['limit'] : 20;
    	$_REQUEST['orderBy'] 	= 	$_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : "T2.first_name:DESC";
    	
		list($Invoices_disp, $numpad, $cnt, $limitList) = $BrokerObj->getAllInvoiceInfo_received_broker($_REQUEST['pageNo'], $_REQUEST['limit'], $Params, ARRAY_A, $_REQUEST['orderBy'], $_SESSION['memberid']);
		for ($i=0; $i<count($Invoices_disp); $i++ ) {
    				$Invoices[$i]['startdate'] = $Invoices_disp[$i]['from_date'];
    				$Invoices[$i]['enddate'] = $Invoices_disp[$i]['to_date'];
					$st=date($global["date_format_new"],strtotime($Invoices_disp[$i]['from_date']));
					$gt=date($global["date_format_new"],strtotime($Invoices_disp[$i]['to_date']));
				    $Invoices_disp[$i]['from_date'] = $st;
					$Invoices_disp[$i]['to_date'] = $gt;
    			}
    		 
		$framework->tpl->assign('POST_URL', $framework->config['paypal_post_url']);
    	$framework->tpl->assign('MEMBER_ID', $_SESSION['memberid']);
    	$framework->tpl->assign('MEMBER_DETAILS', $MemberDetails);
    	$framework->tpl->assign('RECORD_LIMIT', $limitList);
    	$framework->tpl->assign('NUM_PAD', $numpad);
    	$framework->tpl->assign('INVOICE_LIST', $Invoices_disp);
		$framework->tpl->assign('POST_URL', $framework->config['paypal_post_url']);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/my_received_invoices.tpl");			
		  
		break;
	default:
		;	
		

}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");


?>