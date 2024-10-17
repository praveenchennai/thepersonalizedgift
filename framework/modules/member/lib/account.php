<?php
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/extras/lib/class.extras.php");
include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.newsletter.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.payment.php");
include_once(FRAMEWORK_PATH."/includes/payment/authorize.net/AuthorizeNet.php");
include_once(FRAMEWORK_PATH."/includes/payment/authorize.net/arb_auth.class.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.search.php");
$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";

$authObj		= new AuthorizeNet();
$arbObj			= new ARBAuthnet();
if($framework->config['show_property'] == 1)
{
	include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
	$objAlbum = new Album();
}
$PaymentObj		= new Payment();
$objUser=new User();
$newsletter=new Newsletter();
$objExtras=new Extras();
$admin = new Admin();
$flyer			=	new	Flyer();
$objEmail = new Email();
$search         =   new Search();
checkLogin();	
$usr=$objUser->getUserDetails($_SESSION["memberid"]);
$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
	$topsubmenu=$objCms->linkList("topmembersub");
	if ($usr)
	{
		$_REQUEST = $_REQUEST+$usr;
	}	
	if($_REQUEST["pid"])
	{
		$pid=$_REQUEST["pid"];
	}else{
		$pid=$topsubmenu[0]->id;
	}
	//print_r($pid);exit;
	$domain = $_SERVER['HTTP_HOST'];
	//$framework->tpl->assign("DOMAINNAME",$domain);
	$framework->tpl->assign("DOMAINNAME",DOMAIN_URL);
	$framework->tpl->assign("TOPSUB_MENU", $topsubmenu);
	$framework->tpl->assign("PID", $pid);
//print_r($_REQUEST);exit;
switch($_REQUEST["act"])
{
	case "profile":
	if($_SERVER['REQUEST_METHOD']=="POST")
	{		
			unset($_POST["x"],$_POST["y"]);
			$_POST["id"] = $_SESSION["memberid"];
			$objUser->setArrData($_POST);
				$upId=$objUser->update();
				//print_r($upId);exit;
	}
	$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/myaccount.tpl");
	$framework->tpl->assign("tp_file",SITE_PATH."/modules/member/tpl/accountprofile.tpl");
		break;
		
	case "links":
	checkLogin();
	if($_SERVER['REQUEST_METHOD']=="POST")
	{
		$objUser->addeditLinks($_POST);
		redirect(makeLink(array("mod"=>"member", "pg"=>"account"), "act=links&pid=168"));
	}	
	$framework->tpl->assign("LINK_DETAILS",$objUser->getLinkDetails($_SESSION["memberid"]));
	$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/myaccount.tpl");
	$framework->tpl->assign("tp_file",SITE_PATH."/modules/member/tpl/link_ account.tpl");
	
	break;
		
	case "billing_new":
	checkLogin();
	if($_SERVER['REQUEST_METHOD']=="POST")
	{		
		if($_SERVER['REQUEST_METHOD']=="POST")
		{
			$framework->tpl->assign("BILLING_ADDRESS",$_POST);
			$cArray 			= 	array("user_id"=>$_SESSION["memberid"],"card_type"=>$_POST["cc_credit_card"],"exp_year"=>$_POST["cc_exp_year"],"exp_month"=>$_POST["cc_exp_month"],"f_name"=>$_POST["cc_fname"],"l_name"=>$_POST["cc_lname"],"security_code"=>$_POST["cc_security_code"]);
			unset($_POST["cc_credit_card"],$_POST["cc_credit_no"],$_POST["cc_exp_month"],$_POST["cc_exp_year"],$_POST["cc_fname"],$_POST["cc_lname"],$_POST["cc_security_code"]);
			unset($_POST["x"],$_POST["y"],$_POST["new_state"],$_POST["account_url"],$_POST["cpg"]);
			$_POST["user_id"]   = $_SESSION["memberid"];
			$_POST["addr_type"] = "billing";
			$objUser->addeditCreditcard($cArray);
			$objUser->setArrData($_POST);
			$objUser->insertAddress();
			// upgrading the  arb user subscription ***********************
			$PaymentMethod	=	$PaymentObj->getActivePaymentGateway('0');  #Paypal Pro, Authorize.Net, LinkPoint Central 0 -->
			$authDetails	=  $authObj->getAuthorizeNet(0, 'admin');
			if($PaymentMethod === 'Authorize.Net' && $authDetails['auth_net_arb']=='Y')
			{
				$UserDetails				=	$objUser->getUserdetails($_SESSION["memberid"]);
				$Params						=	array(); 
				
				$Params['name']        	 	 =  $UserDetails['username'];
				$SubAmount					 =	$arbObj->getSubpackFee($UserDetails['sub_pack']);
				$Params['amount']        	 =  $SubAmount;
				
				// setting the length and unit
					$UsersubDetail	=	$arbObj->getArbUnits($UserDetails['sub_pack']);
					if($UsersubDetail['type']=="Y")
					{
						//$Params['length']        =  365*$UsersubDetail['duration'];
						$Params['length']        =  365;
						$Params['unit']        	 =  "days";
					}
					elseif($UsersubDetail['type']=="D")
					{
						$Params['length']        =  $UsersubDetail['duration'];
						$Params['unit']        	 =  "days";
					}
					elseif($UsersubDetail['type']=="M")
					{
						$Params['length']        =  1*$UsersubDetail['duration'];
						$Params['unit']        	 =  "months";
					}
					else
					{
						$Params['length']        =  "1";
						$Params['unit']        	 =  "months";
					}
					
				// end setting the length and unit
				
				$Params['startDate']         =  date("Y-m-d");
				$Params['totalOccurrences']  =  "9999";
				$Params['trialOccurrences']  =  "0";
				$Params['trialAmount']       =  "0.00";
				$Params['firstName']         =  $UserDetails['first_name'];
				$Params['lastName']          =  $UserDetails['last_name'];
				$Params['address']        	 =  $UserDetails['address1']." ".$UserDetails['address2'];
				$Params['city']              =  $UserDetails['city'];
				$Params['state']             =  $UserDetails['state'];
				$Params['zip']               =  $UserDetails['postalcode'];
				$Params['country']           =  $UserDetails['country_name'];
				if($Params['lastName'] =="")
				{
					$Params['lastName']          =  $UserDetails['first_name'];
				}			
				$Params['loginname']         =  $authDetails['auth_net_login_id'];
				$Params['transactionkey']    =  $authDetails['auth_net_tran_key'];
				$Params['refId']        	 =  $UserDetails['id'];
				$arb_subDet	=	$arbObj->getSubscriptionId($UserDetails['id']);
				$arb_sub_id	=	$arb_subDet['subscription_id'];
				
				$Params['subscriptionId']    = $arb_sub_id;
				$Params['cardNumber']        =  $_REQUEST['cc_credit_no'];
				$Params['cardCode']        	 =  $_REQUEST['cc_security_code'];
				$Params['expirationDate']    =  $_REQUEST['cc_exp_year']."-".$_REQUEST['cc_exp_month'];
				if($arb_sub_id!="")
				{
					$Result			=	$arbObj->updateSubscription($Params);
			
				}
				else
				{
					if($SubAmount>0)
					{ 	
					
					$arbObj->createARBAuthSubscription('0',$Params); 	}
				}				
			// end upgrading the  arb user subscription ***********************
			}
			
			
			redirect(makeLink(array("mod"=>"member", "pg"=>"account"), "act=billing&pid=170"));
			
		}	
	}
	
	$address = $objUser->getAddress('',$_SESSION['memberid'],'billing');
	$framework->tpl->assign("BILLING_ADDRESS",$address);
	$usr=$objUser->getUserDetails($_SESSION["memberid"]);
	$framework->tpl->assign("USER_REG_PACK",$usr['reg_pack']);
	$packageDetails	=	$objUser->getPackageDetails($usr['reg_pack']);
	$remaing_flyers	=	($objUser->getMaxLimit($usr['reg_pack'],'2')) - ($flyer->allFlyerCount($_SESSION["memberid"]));
	if($remaing_flyers < 1 )
	$remaing_flyers	=	0;
	$framework->tpl->assign("PACKAGE",$packageDetails);
	$framework->tpl->assign("RENEWAL_DATE",$objUser->getSubscriptionEndDate($_SESSION["memberid"]));
	$CreditCards 	=   $PaymentObj->getCreditCardsOfStore('0');
	$framework->tpl->assign("CREDITCARD",$CreditCards);
	$framework->tpl->assign("PUBLISHED_FLYS",$flyer->activeFlyerCount($_SESSION["memberid"]));
	$framework->tpl->assign("ALL_FLYS",$flyer->allFlyerCount($_SESSION["memberid"]));
	$framework->tpl->assign("MAX_FLYS",$objUser->getMaxLimit($usr['reg_pack'],'2'));
	$framework->tpl->assign("REMAINING",$remaing_flyers);
	$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/myaccount.tpl");
	$framework->tpl->assign("tp_file",SITE_PATH."/modules/member/tpl/accountbilling.tpl");
	break;
				
	case "billing":
		checkLogin();
		redirect(makeLink(array("mod"=>"member", "pg"=>"account","sslval"=>'true'), "act=billing_new&pid=170"));
		break;
	
		
	case 'billinginfo_form':
		/**
		 * This case is for processing credit card infromations in Bayard project
		 * @author vimson@newagesmb.com
		 *  
		 */
		checkLogin();
		if($_SERVER['REQUEST_METHOD']=="POST") {
		
			$Status	=	$PaymentObj->saveCreditCardInformation($_REQUEST);
									
			//===========vinoy start=============
				if($Status == TRUE)
				{
					 $MemberId =$_SESSION['memberid'];
					 $type  ="payment";
					 $objEmail->mailSend($type, $MemberId,$_REQUEST);
					 
					 #The following code redirects the control to the page from where this page requested
					 if(isset($_SESSION['ReturnURL']) && is_array($_SESSION['ReturnURL'])) { 
					 	$ReturnModule	=	$_SESSION['ReturnURL']['mod'];
					 	$ReturnPage		=	$_SESSION['ReturnURL']['pg'];
					 	$ReturnAction	=	$_SESSION['ReturnURL']['act'];
					 	unset($_SESSION['ReturnURL']);
					 	redirect(makeLink(array("mod"=>$ReturnModule, "pg"=>$ReturnPage), "act=$ReturnAction"));
					 }
					 
				}
			 //===========vinoy End=============
			
		}
		
		if(trim($_REQUEST['paymsg']) != '' && $_SERVER['REQUEST_METHOD'] != "POST")
			setMessage($_REQUEST['paymsg']);
			
		$CCInfo			=	$PaymentObj->getCreditCardInformation($_SESSION["memberid"]);
		$CreditCards 	=   $PaymentObj->getCreditCardsOfStore('0');
		$framework->tpl->assign("CREDITCARDS",$CreditCards);
		$framework->tpl->assign("CCINFORM",$CCInfo);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/billinginfo_form.tpl");
		break;
	
		
	case 'paypalinfo_form':
		/**
		 * This case is for processing paypal account form for users
		 * @author vimson@newagesmb.com
		 *  
		 */
		checkLogin();
		if($_SERVER['REQUEST_METHOD']=="POST") {
			$PaymentObj->savePaypalAccountInformation($_REQUEST);
		}
		
		$PaypalInfo	=	$PaymentObj->getPaypalAccountInfoOfUser($_SESSION["memberid"]);
		$framework->tpl->assign("PAYPALNFO",$PaypalInfo);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/paypalinfo_form.tpl");
		break;
	
		
	case 'payment_history':
		/**
		 * This case is for displaying payment transaction history
		 * @author vimson@newagesmb.com
		 *  
		 */
		checkLogin();
		$MemberId		=	$_SESSION["memberid"];
		$orderBy		=	(trim($_REQUEST['orderBy']) == '') ? 'id:DESC' : $_REQUEST['orderBy'];
			
		$param			=	"mod={$_REQUEST['mod']}&pg={$_REQUEST['pg']}&act=payment_history";
		list($rs, $numpad, $cnt, $limitList)	= 	$PaymentObj->getTransactionHistory($MemberId, $pageNo, $limit, $param, ARRAY_A, $orderBy);
						
		$framework->tpl->assign("PAYMENT_HISTORY",$rs);
		$framework->tpl->assign("NUM_PAD",$numpad);
		
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/transaction_history.tpl");
		break;	
		
	case 'propertyuser_settings':
		/**
		 * This case is used for saving the property user settings related with a particular account
		 * 
		 * @author vimson@newagesmb.com
		 * Modified: 08/May/2008 by Retheesh Kumar
		 * Validation added for deselecting Property Managers and Brokers
		 */
		checkLogin();
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			$user_det = $objUser->getUserdetails($_SESSION['memberid']);
			$proceed = 1;
			if ( ($_POST['ispopertymanager'] != "Y") || ($_POST['isbroker'] != "Y"))
			{
				$memTypeDet = $objUser->getMemTypeDetails($user_det['mem_type']);
				if ($_POST['ispopertymanager'] != "Y")
				{
					if($memTypeDet['type']=="Manager")
					{
						setMessage($framework->MOD_VARIABLES['MOD_ERRORS']['ERR_DSEL_MANAGER']);
						$proceed = 0;
					}
					elseif ($flyer->checkAssigned($_SESSION['memberid'],'PROP_MANAGER'))
					{
						setMessage($framework->MOD_VARIABLES['MOD_ERRORS']['ERR_MANAGER_ASSIGNED']);
						$proceed = 0;
					}
				}
				if ($_POST['isbroker'] != "Y")
				{	
					if ($memTypeDet['type']=="Broker")
					{
						setMessage($framework->MOD_VARIABLES['MOD_ERRORS']['ERR_DSEL_BROKER']);
						$proceed = 0;
					}
					elseif ($flyer->checkAssigned($_SESSION['memberid'],'PROP_BROKER'))
					{
						setMessage($framework->MOD_VARIABLES['MOD_ERRORS']['ERR_BROKER_ASSIGNED']);
						$proceed = 0;
					}
				}	
			}
			
			
			if ($proceed == 1) 
			{
				$objUser->setUserPropertyRoles($_POST, $_SESSION["memberid"]);	
			}
			
			redirect(makeLink(array('mod' => 'member', 'pg' => 'home')));
		}
		break;
	
		
	case 'my_invoices':
		/**
		 * This case used for listing all the invoices reeived by a member
		 * @author vimson@newagesmb.com
		 * 
		 */
		checkLogin();
		$MemberDetails			=	$objUser->getUserdetails($_SESSION['memberid']);
		$Params					=	"mod=$mod&pg=$pg&act={$_REQUEST['act']}";
    	$_REQUEST['limit'] 		= 	$_REQUEST['limit'] ? $_REQUEST['limit'] : 20;
    	$_REQUEST['orderBy'] 	= 	$_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : "T1.invoice_id:DESC";
    	list($Invoices, $numpad, $cnt, $limitList)	=	$PaymentObj->getInvoiceList($_REQUEST['pageNo'], $_REQUEST['limit'], $Params, ARRAY_A, $_REQUEST['orderBy'], $_SESSION['memberid']);
    	
		$framework->tpl->assign('POST_URL', $framework->config['paypal_post_url']);
    	$framework->tpl->assign('MEMBER_ID', $_SESSION['memberid']);
    	$framework->tpl->assign('MEMBER_DETAILS', $MemberDetails);
    	$framework->tpl->assign('RECORD_LIMIT', $limitList);
    	$framework->tpl->assign('NUM_PAD', $numpad);
    	$framework->tpl->assign('INVOICE_LIST', $Invoices);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/myinvoices.tpl");
		break;	
	

	case 'my_earnings':
		/**
		 * This case used for listing all the invoices reeived by a sellers from the broker
		 * 
		 * @author vimson@newagesmb.com
		 * 
		 */
		$Params					=	"mod=$mod&pg=$pg&act={$_REQUEST['act']}";
    	$_REQUEST['limit'] 		= 	$_REQUEST['limit'] ? $_REQUEST['limit'] : 20;
    	$_REQUEST['orderBy'] 	= 	$_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : "T2.username:DESC";
    	list($InvoiceSenders, $numpad, $cnt, $limitList)	=	$PaymentObj->getMyInvoiceReceivers($_REQUEST['pageNo'], $_REQUEST['limit'], $Params, ARRAY_A, $_REQUEST['orderBy'], $_SESSION['memberid']);
		
    	$framework->tpl->assign("NUM_PAD",$numpad);
    	$framework->tpl->assign("RECORD_LIMIT",$limitList);
    	$framework->tpl->assign("INVOICE_SENDERS",$InvoiceSenders);
    	$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/myinvoicereceivers.tpl");
		break;	
	

		
	case 'myearning_invoices':
		/**
		 * The following method lists all the invoices sent by the broker to the selected seller
		 * 
		 * @author vimson@newagesmb.com
		 * 
		 */
		$Params					=	"mod=$mod&pg=$pg&act={$_REQUEST['act']}&SentToId={$_REQUEST['SentToId']}";
    	$_REQUEST['limit'] 		= 	$_REQUEST['limit'] ? $_REQUEST['limit'] : 20;
    	$_REQUEST['orderBy'] 	= 	$_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : "T1.created_time:DESC";
    	list($Invoices, $numpad, $cnt, $limitList)	=	$PaymentObj->getMyEarningInvoices($_REQUEST['pageNo'], $_REQUEST['limit'], $Params, ARRAY_A, $_REQUEST['orderBy'], $_SESSION['memberid'], $_REQUEST['SentToId']);
		for ($i=0; $i<count($Invoices); $i++ ) {
    		$st=date($global["date_format_new"],strtotime($Invoices[$i]['created_time']));
			$Invoices[$i]['created_time'] = $st;
		}    	
		$framework->tpl->assign("NUM_PAD",$numpad);
    	$framework->tpl->assign("RECORD_LIMIT",$limitList);
    	$framework->tpl->assign("INVOICES",$Invoices);
    	$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/myearninginvoices.tpl");
		break;	
		

		
	default:
	if($_SERVER['REQUEST_METHOD']=="POST")
	{		
		$udet 		= 	$objUser->getUserDetails($_SESSION["memberid"]);
		$oldpass	=	$udet['password'];
		$userEmail	=	$udet['email'];
		if($_POST["old_password"] != $oldpass)
		{
			unset($_POST["password"]);
		}
		if($_POST["confirm_password"] != $_POST["password"])
		{
			unset($_POST["password"]);
		}
		if($_POST["password"] == "")
		{
			unset($_POST["password"]);
		}
		
			unset($_POST["confirm_password"],$_POST["old_password"],$_POST["x"],$_POST["y"]);
			
			// for unchecking the newsletters
			if(SHOW_FORMS=="Y")
			{
				if(isset($_POST["newsletter"]))
				{ 
					$_POST["newsletter"]= "Y"; 
					$newsletter->addtonewsLetterGroups($_SESSION["memberid"],$userEmail,8);
				}
				else
				{
					 $_POST["newsletter"]= "N";
					  $newsletter->deletefromnewsLetterGroups($_SESSION["memberid"],$userEmail,8);
				}
				if(isset($_POST["promotionoffer"]))
				{ 
					$_POST["promotionoffer"]= "Y"; 
					$newsletter->addtonewsLetterGroups($_SESSION["memberid"],$userEmail,9);
				}
				else
				{ 
					$_POST["promotionoffer"]= "N"; 
					$newsletter->deletefromnewsLetterGroups($_SESSION["memberid"],$userEmail,9);

				}
			
			}
			// for unchecking the newsletters
			
			$_POST["id"] = $_SESSION["memberid"];
			$objUser->setArrData($_POST);
				$upId=$objUser->update();
				//print_r($upId);exit;
				redirect(makeLink(array("mod"=>"member", "pg"=>"account")));
	}
		$_REQUEST["act"]="general";
	$framework->tpl->assign("tp_file",SITE_PATH."/modules/member/tpl/accountgeneral.tpl");
	//
			$sess = $objUser->getLastSession($_SESSION["memberid"]);
				if ($admin->moduleExists("extras"))	
				{
					$gift =	$objExtras->giftByuserid($_SESSION["memberid"]);
				}	
			$udet = $objUser->getUserDetails($_SESSION["memberid"]);
				if ($admin->moduleExists("extras"))	
				{
					$coupons =	$objExtras->couponByuserid($_SESSION["memberid"]);
				}	
			if ($udet["sub_pack"]>0)
			{
				if ($objUser->subAlert($_SESSION['memberid']))
				{
					$framework->tpl->assign("SHOW_MSG",1);
				}
			}	
			
			/*
			START
			Real Estate tube
			*/
			if($framework->config['show_property'] == 1)
			{
				$rs = $objAlbum->getAlbumByFields('user_id',$_SESSION['memberid']);
				$framework->tpl->assign("PROP_DETAILS",$rs);
				$framework->tpl->assign("PROP_LIST",SITE_PATH."/modules/album/tpl/adList.tpl");
				
			}
			/*
			Real Estate tube
			END
			*/
			# for sawitonline.com
			if(SHOW_FORMS=="Y")
			$framework->tpl->assign("FORMS",$flyer->getallForms());
			# for sawitonline.com End
			$framework->tpl->assign("LAST_LG",$sess);
			$framework->tpl->assign("GIFT",$gift);
			$framework->tpl->assign("COUPONS",$coupons);
			$framework->tpl->assign("UNAME",$udet["username"]);
			
}

if($framework->config['show_biling'] != 'Yes') {
	$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/myaccount.tpl");
}	

$framework->tpl->display($global['curr_tpl']."/inner.tpl");

?>