<?php 
/**
* Cart Module
*
* @author sajith
* @package defaultPackage
*/

include_once(FRAMEWORK_PATH."/modules/cart/lib/class.cart.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/extras/lib/class.extras.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.paymenttype.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.payment.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.shipping.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
include_once(FRAMEWORK_PATH."/modules/forum/lib/class.forum.php");
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.parcer.php");

$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
$param			=	"mod=$mod&pg=$pg&act=$act&orderBy=$orderBy&fId=$fId&sId=$sId";
$storename		=	$_REQUEST["storename"] ? $_REQUEST["storename"] : "0";


$objUser		=	new User();
$objAccessory	=	new Accessory();
$objProduct		=	new Product();
$cart 			= 	new Cart();
$typeObj 		= 	new paymentType();
$ShippingObj	=	new Shipping();
$email	 		= 	new Email();
$PaymentObj		=	new Payment();
$objStore 		= 	new Store();
$forum 			= 	new Forum();
$objCms 		= 	new Cms();	
$parser			=&  new xmlParser();

//print_r($_REQUEST);
//print_r($_POST);
//print_r($_GET);
//echo $_REQUEST['act'];	
		//exit;
		

		
$framework->tpl->assign("CURR_STORE", $_REQUEST["storename"]);	
		
switch ($_REQUEST['act']) {
	case "add":
		$_SESSION['order_contact_me']	=	"";
		$_SESSION['order_notes']		=	"";
		$storeArr = $objStore->storeGetByName($_REQUEST['storename']);
		$_REQUEST['store_id'] = $storeArr['id'];
		$status	=	$objAccessory->ValidateTheSelectionOfAccessory($_REQUEST);
		if($status['status']==true) {
			if($objProduct->ValidateGiftCertificate($_REQUEST['product_id'],$_REQUEST['price']))
			{
				$_REQUEST['user_id'] = $_SESSION['memberid'];
				$cart->addToCart($_REQUEST);
				redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=view"));
			}
			else
			{
				setMessage('Please Specify The Amoount for '.$objProduct->GetProductName($_REQUEST['product_id']));
				redirect(makeLink(array("mod"=>"product", "pg"=>"list"), "act=desc&id=".$_REQUEST['product_id']));
			}
		} else {
			setMessage($status['message']);
			redirect(makeLink(array("mod"=>"product", "pg"=>"list"), "act=desc&id=".$_REQUEST['product_id']));
		}
		
		break;
	case "delete":
		$cart->deleteCart($_REQUEST['id']);
		#### this condition is added by Jipson Thomas to redirect the page to Orderform page in calsilkscreen project....on 14 february 2008..............
		if($global['redirect_to_contact_info']=="Y"){
			if($_REQUEST["from"]=="orderform2"){
				redirect(makeLink(array("mod"=>"product", "pg"=>"list"), "act=oderform&act1=2"));
			}elseif($_REQUEST["from"]=="orderform3"){
				redirect(makeLink(array("mod"=>"product", "pg"=>"list"), "act=oderform&act1=3"));
			}else{
				redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=view"));
			}##### Modification by Jipson Ends here................................................
		}else{
			redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=view"));
		}
		break;
	case "view":
	if($_SERVER['REQUEST_METHOD']=="POST") {
		
			$cart->updateCart($_REQUEST);
			if (isset($_REQUEST['update_x']) && $_REQUEST['update_x']!="" ) {
			$_SESSION['order_contact_me']	=	"";
			$_SESSION['order_notes']		=	"";
				redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=view"));
			} else {
			$_SESSION['order_contact_me']	=	$_REQUEST['contact_me'];
			$_SESSION['order_notes']		=	$_REQUEST['notes'];
			    //$cart->updateCartTracking1($_REQUEST);
				//============vinoy add===========
				if ( $global['payment_tpl'] == 'popup' && $_SESSION['memberid']=="") 
		        {
					redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=login"));
				}elseif ( $global['payment_tpl'] == 'pjctcartlogin' && $_SESSION['memberid']=="") 
		        {
					redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=login"));
				}elseif( $global['payment_tpl'] == 'cartlogin'){
					redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=view"));
					//==============vinoy end==========
					#### this condition is added by Jipson Thomas to redirect the page to Contact Info page in calsilkscreen project....on 14 february 2008..............
			    }elseif($global['redirect_to_contact_info']=="Y"){
					redirect(makeLink(array("mod"=>"product", "pg"=>"list"), "act=oderform&act1=1"));
				##### Modification byJipson Ends here..............................
				}else{
					redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=shipping"));
				}
			}
		}
		//print_r($cart->getCart());
#for 3 image buttons			
	$framework->tpl->assign("SUBMITBUTTON1", createImagebutton_Div("Submit","#","submit_form()"));
	$framework->tpl->assign("CANCEL", createImagebutton_Div("Cancel","#","history.go(-1)"));
#END

		$framework->tpl->assign("CONTINUEBUTTON", createButton("CONTINUE SHOPPING","#","continueshopping()"));
		$framework->tpl->assign("UPDATEBUTTON", createButton("UPDATE","#","update()"));
		$framework->tpl->assign("CHECKOUTBUTTON", createButton("CHECKOUT","#","checkout()"));
		$lnk_proceed_to_shipping = makeLink(array("mod"=>"cart", "pg"=>"default"), "act=shipping");
		$framework->tpl->assign("PROCEED_TO_CHECKOUT", createImagebutton_Div("Proceed to Checkout",$lnk_proceed_to_shipping,""));
		$framework->tpl->assign("LAST_CAT_ID", $cart->getLastCatID());
		$lastCart = $cart->getLastCatID();
		$lnk_continue = makeLink(array("mod"=>"product", "pg"=>"list"), "act=list&add_accessory=N&cat_id=$lastCart");
		$framework->tpl->assign("CONTINUEBUTTON_SMALL", createImagebutton_Div("Continue Shopping",$lnk_continue,""));
		$framework->tpl->assign("UPDATEBUTTON_SMALL", createImagebutton_Div("Update","#","update()"));
		$framework->tpl->assign("CHECKOUTBUTTON_SMALL", createImagebutton_Div("Check Out","#","checkout()"));
		//print_r($cart->getCart());
		
		$avilable_table	=$framework->config['avilable_access'];
		
		if($avilable_table=='N'){
			$cartArray	=	$cart->getCart('N');
			$CARTARRAY	= $cart->getCart('N');
		}else{
			$CARTARRAY	= $cart->getCart();
			$cartArray	=	$cart->getCart();
		}
		//print_r($CARTARRAY);exit;
		### Start showing Pay Invoice Nov-26-2007 by shinu ###
		$framework->tpl->assign("CART_TOTAL_AMOUNT", ($cartArray["total"]+$invoice_amt)); 
		if($framework->config['pay_invoice']=="Y")
		{
			$invoice_amt	=	$cart->getInvoiceAmount(); 
			if($invoice_amt !== "" && $invoice_amt >0)
			{
				$framework->tpl->assign("INVOICE_AMOUNT", $invoice_amt); 
				$framework->tpl->assign("GRAND_TOTAL_AMOUNT", ($cartArray["total"]+$invoice_amt)); 
				$framework->tpl->assign("INVOICE", $cart->getInvoice());
				$framework->tpl->assign("PAY_INVOICE", "Y");
			}
		}
		### End showing Pay Invoice Nov-26-2007 by shinu ###
		
		if ( $global['payment_tpl'] == 'cartlogin'){
			if(count($CARTARRAY[records])>0){
				foreach($CARTARRAY[records] as $key=>$val){
					 $storeName  =	$objStore->storeGet($val->store_id);
					 $store_name = $storeName[name];
					 $CARTARRAY[records][$key]->store_name =  $store_name;
				}
			}
		}
		//print_r($CARTARRAY);
		$framework->tpl->assign("CART", $CARTARRAY);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/cart/tpl/cart_view.tpl");
		break;
	case "login":
		if ($_SERVER['REQUEST_METHOD']=="POST") {
			if ($objUser->authenticate($_POST["txtuname"],$_POST["txtpswd"])) {
				redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=shipping"));
			} else {
				$err = $objUser->getErr();
				$framework->tpl->assign("LOGIN_MESSAGE", $objUser->getErr());
			}
		}
		//=======vinoy add=============
           if ( $global['payment_tpl'] == 'popup' OR $global['payment_tpl'] == 'pjctcartlogin') 
		   {
		   if($storename=="" || $storename=="0") { $storename="Main Store";}
		   $framework->tpl->assign("STORE_NAME", $storename);
		    $framework->tpl->assign("STORE_HEAD", $objStore ->storeGetByName1("$storename"));
			
			$framework->tpl->assign("CHECKOUT", createButton("PROCEED TO CHECKOUT","index.php?mod=cart&pg=default&act=shipping"));
			$framework->tpl->assign("SIGNCHECKOUT", createButton("CHECKOUT","#","check();"));
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/cart/tpl/checout_login.tpl");
		   }else{
		//=======vinoy end=============
#for 3 image buttons			
	$framework->tpl->assign("LOGINBUTTON1", createImagebutton_Div("Login","#","submit_form()"));
#END

		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/cart/tpl/shipping.tpl");
			if ( $global['payment_tpl'] == 'popup' ) 
			{
			$framework->tpl->display($global['curr_tpl']."/checkout.tpl");
			 exit;
			}elseif( $global['payment_tpl'] == 'cartlogin'){
				if($err !="") $err ="Invalid Username or password";
			 redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=view&err=$err"));
			}
		}
		
		break;
		
		case "login1":
				
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/cart/tpl/shipping_login1.tpl");
		//$framework->tpl->assign("main_tpl", SITE_PATH."/modules/cart/tpl/shipping.tpl");
		if ( $global['payment_tpl'] == 'popup' ) 
		{
		$framework->tpl->display($global['curr_tpl']."/checkout.tpl");
		 exit;
		}
		break;
		
		
	case "shipping":
		if($_SERVER['REQUEST_METHOD']=="POST") {
		
		// added for registering a user from shipping page on 12-11-07
			if($_REQUEST['username'] != '')
			{
				//$objUser->unsetFields();
				$UserAddr 				= 	array('username'		=> 	$_POST['username'],
									            'password' 			=> 	$_POST['password'],
												'first_name' 		=> 	$_POST['billing_first_name'],
												'last_name' 		=> 	$_POST['billing_last_name'],
									            'email' 			=> 	$_POST['billing_email']);
				$BillingAddr 				= 	array('fname'		=> 	$_POST['billing_first_name'],
									            'lname' 		=> 	$_POST['billing_last_name'],
									            'address1' 		=> 	$_POST['billing_address1'],
									            'address2' 		=> 	$_POST['billing_address2'],
												'address3' 		=> 	$_POST['billing_address3'],
									            'city' 			=> 	$_POST['billing_city'],
									            'state' 		=> 	$_POST['billing_state'], 
									            'postalcode' 	=> 	$_POST['billing_postalcode'],
									            'country' 		=> 	$_POST['billing_country'],
									            'telephone' 	=> 	$_POST['billing_telephone'],
									            'mobile' 		=> 	$_POST['billing_mobile']);
				$ShippingAddr 				= 	array('fname'		=> 	$_POST['shipping_first_name'],
									           'addr_type' 		=> 	"shipping",
											    'lname' 		=> 	$_POST['shipping_last_name'],
									            'address1' 		=> 	$_POST['shipping_address1'],
									            'address2' 		=> 	$_POST['shipping_address2'],
												'address3' 		=> 	$_POST['shipping_address3'],
									            'city' 			=> 	$_POST['shipping_city'],
									            'state' 		=> 	$_POST['shipping_state'], 
									            'postalcode' 	=> 	$_POST['shipping_postalcode'],
									            'country' 		=> 	$_POST['shipping_country'],
									            'telephone' 	=> 	$_POST['shipping_telephone'],
									            'mobile' 		=> 	$_POST['shipping_mobile']
									           );
			$Result				=	$objUser->allUsers('username',$_POST['username']);  
			$resultCount		=	count($Result); 
			if($resultCount==0)
			{
				$myId=$objUser->insertDetails($UserAddr,$BillingAddr,$ShippingAddr);
				if($myId!=="" && $myId!="0")
				{
				 //   $_SESSION['memberid'] =	$myId;
				//sending mail to the user
					$mail_header = array();
					$user_det = $objUser->getUserdetails($myId);
					$mail_header["from"] = $framework->config['admin_email'];
					$mail_header["to"]   = $user_det["email"];
					$myId = $user_det["id"];
					$dynamic_vars = array();
					$dynamic_vars["USER_NAME"]  = $user_det["username"];
					$dynamic_vars["FIRST_NAME"] = $user_det["first_name"];
					$dynamic_vars["LAST_NAME"]  = $user_det["last_name"];
					$dynamic_vars["PASSWORD"]   = $user_det['password'];
					$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
					$dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";
					$email->send("registration_confirmation",$mail_header,$dynamic_vars);
				//end sending mail to the user
				}
			}
			
			
			}
	// end of added for registering a user from shipping page on 12-11-07
	
			
			# The following code modified by vimson for inserting shipping charges to DB
			$shipping_service	=	$_REQUEST['shipping_service'];
			$ShipSrvcArr		=	explode('*^*',$shipping_service);
			$_SESSION['shipping_price']		    =	 $ShipSrvcArr[2];
			$_SESSION['shipping_service'] 		=	 $ShipSrvcArr[0];
			
			if($_REQUEST['shippingpresent'] == 'Yes') {
				$_SESSION['international_order'] 	=	 'N';
			} else if($_REQUEST['shippingpresent'] == 'No') {
				$_SESSION['international_order'] 	=	 'Y';
			}	
			
			$BillState		=	$_REQUEST["billing_state"] ? $_REQUEST["billing_state"] : "";
			if($BillState=="")
			{ $BillState = $_POST['shipping_state']; }
			$_SESSION['BILLING_ADDRESS'] = array('fname'		=> 	$_POST['billing_first_name'],
									            'lname' 		=> 	$_POST['billing_last_name'],
									            'address1' 		=> 	$_POST['billing_address1'],
									            'address2' 		=> 	$_POST['billing_address2'],
												'address3' 		=> 	$_POST['billing_address3'],
									            'city' 			=> 	$_POST['billing_city'],
									            'state' 		=> 	$BillState, 
									            'postalcode' 	=> 	$_POST['billing_postalcode'],
									            'country' 		=> 	$_POST['billing_country'],
									            'telephone' 	=> 	$_POST['billing_telephone'],
									            'mobile' 		=> 	$_POST['billing_mobile'],
									            'email'			=>  $_POST['billing_email']);
			$_SESSION['SHIPPING_ADDRESS'] = array('fname' 		=> 	$_POST['shipping_first_name'],
									            'lname' 		=> 	$_POST['shipping_last_name'],
									            'address1' 		=> 	$_POST['shipping_address1'],
									            'address2' 		=> 	$_POST['shipping_address2'],
												'address3' 		=> 	$_POST['shipping_address3'],
									            'city' 			=> 	$_POST['shipping_city'],
									            'state' 		=> 	$_POST['shipping_state'],
									            'postalcode' 	=> 	$_POST['shipping_postalcode'],
									            'country' 		=> 	$_POST['shipping_country'],
									            'telephone' 	=> 	$_POST['shipping_telephone'],
									            'mobile' 		=> 	$_POST['shipping_mobile'],
									            'method' 		=> 	$ShipSrvcArr[1]);
			
			if ($_SESSION['memberid']) {
				// update billing address
				$array = $_SESSION['BILLING_ADDRESS'];
				array_pop($array);
				$array['user_id'] = $_SESSION['memberid'];
				$array['addr_type'] = "billing";
				$objUser->setArrData($array);
				$objUser->insertAddress();
				
				// update shipping address
				$array = $_SESSION['SHIPPING_ADDRESS'];
				array_pop($array);
				$array['user_id'] = $_SESSION['memberid'];
				$array['addr_type'] = "shipping";
				$objUser->setArrData($array);
				$objUser->insertAddress();
			}
			


			# The following section of code used for shipping rate calculation. Now we use the rate from the form
			/*$weight = $cart->calculateWeight();
			//$box	= $cart->selectBox();
			include_once(FRAMEWORK_PATH."/includes/shipping/".$_POST['shipping_method']."/include.php");
			$param = array(
						'src_zip'		=>	$framework->config['product_shipping_zip'],
						'dst_zip'		=>	$_POST['billing_postalcode'],
						'src_country'	=>	$framework->config['product_shipping_country'],
						'dst_country'	=>	$objUser->getCountry2LetterCode($_POST['billing_country']),
						'weight'		=>	$weight
						);
			$result = getQuote($param);
			if($result['error']) {			
				setMessage("Error in shipping method<br />".$result['error'][0]);
				//$framework->tpl->assign("MESSAGE", "Error in shipping method<br />".$result['error'][0]);
			} else {
				$_SESSION['shipping_price'] = $result['cost'];
				//$cart->updateCartTracking2($_REQUEST);
				redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=checkout"));
			}*/
			
			redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=checkout"));

		}
		
		
		// for loading the biiling country
			$bill 	= 	$objUser->getAddresses($_SESSION['memberid'], "billing");
			$master	= 	$objUser->getAddresses($_SESSION['memberid'], "master");
			$bill	=	get_object_vars($bill[0]);
				
				$master	=	get_object_vars($master[0]);
			/*if($bill) {
			$billCountry	=	$bill[0]['country'];
			$billState		=	$bill[0]['state'];
			}
			else{
			$billCountry	=	$master[0]['country'];
			$billState		=	$master[0]['state'];
			}*/
			$framework->tpl->assign("M_COUNTRY_BILL", $billCountry);
			$framework->tpl->assign("M_BILL_STATE", $billState);
			
		if ($_SESSION['SHIPPING_ADDRESS'] == "") {
			if ($_SESSION['memberid']) {
				$userDetails = $objUser->getUserdetails($_SESSION['memberid']);
				$mail = $userDetails['email'];
				
				$bill 	= 	$objUser->getAddresses($_SESSION['memberid'], "billing");
				$ship 	= 	$objUser->getAddresses($_SESSION['memberid'], "shipping");
				$master	= 	$objUser->getAddresses($_SESSION['memberid'], "master");			
				
				/**
					* Modified : 18/Dec/2007 By Ratheesh kk
					for listing the shipping details of user on shipping page
				*/	
				/*$bill	=	get_object_vars($bill[0]);
				$ship	=	get_object_vars($ship[0]);
				$master	=	get_object_vars($master[0]);*/
				$master['fname'] =$userDetails['first_name'];
				$master['lname'] =$userDetails['last_name'];
				if($bill){
					$bill[0]['email'] = $master['email'] = $mail;
				}
				
				$framework->tpl->assign("BILLING_ADDRESS", $bill ? $bill[0] : $master[0]);
				$framework->tpl->assign("SHIPPING_ADDRESS", $ship ? $ship[0] : $master[0]);
				
			}
		} else {
			$framework->tpl->assign("BILLING_ADDRESS", $_SESSION['BILLING_ADDRESS']);
			$framework->tpl->assign("SHIPPING_ADDRESS", $_SESSION['SHIPPING_ADDRESS']);
			
		}
#for 3 image buttons	
	

	
		
	$framework->tpl->assign("LOGINBUTTON1", createImagebutton_Div("Login","#","submit_form()"));
	$framework->tpl->assign("SUBMITBUTTON1", createImagebutton_Div("Submit","#","pagesub()"));
	$framework->tpl->assign("CANCEL", createImagebutton_Div("Cancel","#","history.go(-1)"));
#END

		$framework->tpl->assign("INTRNL_MESSAGE", wordwrap($ShippingObj->getInternationalMessage(),100,'<br />'));
		$framework->tpl->assign("DEFAULT_INTRNL_MESSAGE", wordwrap($ShippingObj->getDefaultInternationalMessage(),100,'<br />'));
		$framework->tpl->assign("INTRNL_MESSAGE_STATUS", $ShippingObj->getInternationalMessageStatus());
		$framework->tpl->assign("SHIPPING_COUNTRY_ID", $ShippingObj->getShippingCountryId());
		
		$lnk_continue = makeLink(array("mod"=>"product", "pg"=>"list"), "act=list&add_accessory=N&cat_id=$lastCart");
		$framework->tpl->assign("CONTINUE_SMALL", createImagebutton_Div("Continue","#","javascript:return pagesub()"));
		
		### Start showing Pay Invoice message Nov-30-2007 by shinu ###
		//if($framework->config['pay_invoice']=="Y")
		//{
			$framework->tpl->assign("PAY_INVOICE", "Y");
			$InvoiceMessage	=	$ShippingObj->getPayinvoiceMessage();
			$framework->tpl->assign("INVOICE_MESSAGE", $InvoiceMessage);
			
			$avilable_table	=$framework->config['avilable_access'];
			if($avilable_table=='N'){
				$cartArray = $cart->getCart('N');
			}else{
				$cartArray = $cart->getCart();
			}	
			$cartAmount	=$cartArray['total'];
			$framework->tpl->assign("CART_AMOUNT", $cartAmount);
		//}
		### end showing Pay Invoice message Nov-30-2007 by shinu ###
		
		$framework->tpl->assign("SHIPPING_METHODS", $ShippingObj->getShippingMethodsForComboFilling($storename));
		$framework->tpl->assign("LOGINBUTTON", createButton("LOGIN","#","submitLogin()"));
		$framework->tpl->assign("SUBMITBUTTON", createButton("SUBMIT","#","pagesub()"));
		$framework->tpl->assign("CONTINUEBUTTON", createButton("CONTINUE","#","javascript:return pagesub()"));
		$framework->tpl->assign("RESETBUTTON", createButton("CANCEL","#","history.go(-1)"));
		$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
		$framework->tpl->assign("STATE_LIST", $objUser->listStateUS());//for showing the US states on loading the page
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/cart/tpl/shipping.tpl");
		//=========================================
		if ( $global['payment_tpl'] == 'popup' ) 
		{
		$framework->tpl->display($global['curr_tpl']."/checkout.tpl");
		 exit;
		}elseif($global['payment_tpl'] == 'cartlogin'){
		 $framework->tpl->display($global['curr_tpl']."/shipping.tpl");
		}
		//=========================================
		break;
	case "checkout":
	## adding  shipping price to new session variable 29-02-08 ###
	if($_SESSION['shipping_price'] != 0 || $_SESSION['shipping_price'] != '')
	{
		$_SESSION['shipping_price_Actual']	=	$_SESSION['shipping_price'];
	}
	## end of adding  shipping price to new session variable 29-02-08 ###
	
		//code by robin
		if ($_SERVER['HTTPS'])
		{
			$_SESSION['secure'] ='yes';
		}
		else 
		{
			$_SESSION['secure'] ='no';
			
		}
		$_SESSION['domain_name'] =SITE_URL;
		//$_SESSION['domain_name']
		
		// end robin
		
		
		
		
		
//print_r($_SESSION);
		//$sess_Array=serialize($_SESSION);
			$sess_Array=$_SESSION;
			//print_r($sess_Array);
		//$test= unserialize($sess_Array);
		//print_r($test);
		
		// code modified by robin
			/*
		$ses_parent=$sess_Array['memberid']."-".$sess_Array['mem_type']."-".$sess_Array['mem_sess_id']."-".$sess_Array['mem_active']."-".$sess_Array['shipping_price']."-".$sess_Array['shipping_service']."-".$sess_Array['international_order']."-".$sess_Array['secure'];
		$ses_billig= $sess_Array['BILLING_ADDRESS']['fname']."-".$sess_Array['BILLING_ADDRESS']['lname']."-".$sess_Array['BILLING_ADDRESS']['address1']."-".$sess_Array['BILLING_ADDRESS']['address2']."-".$sess_Array['BILLING_ADDRESS']['address3']."-".$sess_Array['BILLING_ADDRESS']['city']."-".$sess_Array['BILLING_ADDRESS']['state']."-".$sess_Array['BILLING_ADDRESS']['postalcode']."-".$sess_Array['BILLING_ADDRESS']['country']."-".$sess_Array['BILLING_ADDRESS']['telephone']."-".$sess_Array['BILLING_ADDRESS']['mobile']."-".$sess_Array['BILLING_ADDRESS']['email'];
		$ses_shipping= $sess_Array['SHIPPING_ADDRESS']['fname']."-".$sess_Array['SHIPPING_ADDRESS']['lname']."-".$sess_Array['SHIPPING_ADDRESS']['address1']."-".$sess_Array['SHIPPING_ADDRESS']['address2']."-".$sess_Array['SHIPPING_ADDRESS']['address3']."-".$sess_Array['SHIPPING_ADDRESS']['city']."-".$sess_Array['SHIPPING_ADDRESS']['state']."-".$sess_Array['SHIPPING_ADDRESS']['postalcode']."-".$sess_Array['SHIPPING_ADDRESS']['country']."-".$sess_Array['SHIPPING_ADDRESS']['telephone']."-".$sess_Array['SHIPPING_ADDRESS']['mobile'];
		*/
		$sess_id=session_id();
		//$Sess_str	=	session_encode();
		//$Sess_str   = base64_encode($Sess_str);
		$sess_insert_id= $cart->encodeSession();
		
		
		//$Sess_str=str_replace('"',"-",$Sess_str);
		
		
		
		//echo $ses_Shipping;
		
			if($_SERVER['REQUEST_METHOD'] == "POST") {
			
			$extra = new Extras();
			$chk = $extra->useExtrafeatures($_POST['key'], $_POST['amount'], $_POST['type']);
			$certType = $_POST['type'] == 'G' ? "Gift Certificate" : "Coupon";

			if ($_POST['key'] == "") {
				setMessage("$certType Key is required");
				$message = "$certType Key is required";
			} elseif($_POST['amount'] == "" && $_POST['type'] == 'G') {
				setMessage("Amount is required");
				$message = "Amount is required";
			} elseif ($chk['key_exist'] == "fail") {
				setMessage("The $certType Key you entered does not exist or is inactive.");
				$message = "The $certType Key you entered does not exist or is inactive.";
			} elseif ($chk['date_status'] == "fail") {
				if ($chk['date_message']) {
					setMessage("Your $certType expired on ".date("D, dS M Y", strtotime($chk['date_message'])));
					$message = "Your $certType expired on ".date("D, dS M Y", strtotime($chk['date_message']));
				}
			} elseif ($chk['usage_status'] == "fail") {
				setMessage("Your $certType usage exceeded than allowed number of usage");
				$message = "Your $certType usage exceeded than allowed number of usage";
			} elseif ($chk['amount_status'] == "fail") {
				setMessage("Your $certType has an available balance of ".$chk['balance_message']."$ only");
				$message = "Your $certType usage exceeded than allowed number of usage";
			} else {
				if ($_POST['type'] == "G") {
					$_SESSION['gift_certificate'] = array("key"=>$_POST['key'], "amount"=>$_POST['amount']);
				} else {
					$amount = ($chk['coupon_type'] == 'A') ? $_POST['amount'] : $chk['balance_message'];
					// substarct from product or from total
					$substract_from	=	$chk['substract_from'];//print_r($chk);
					if($substract_from=="")
						{  $substract_from	=	"P"; }
					// end 
					$_SESSION['coupon'] = array("key"=>$_POST['key'], "amount"=>$amount, "substract_from"=>$substract_from, "coupon_type"=>$chk['coupon_type']);
				}
				redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=checkout"));
			}
		}
		$avilable_table	=$framework->config['avilable_access'];
		if($avilable_table=='N'){
			$cartArray = $cart->getCart('N');
		}else{
			$cartArray = $cart->getCart();
		}	
		
		$shipping_price = ($_SESSION['coupon']['coupon_type']=='F') ? 0 : $_SESSION['shipping_price'];
		
		
		
		
		$tax	=	$cart->CalculateTax($_SESSION['BILLING_ADDRESS']['country'],$_SESSION['BILLING_ADDRESS']['state'],false,$storename);
		
		if ($global["tax_title"] == 'Y' )
		$taxtitle	=	$cart->GetTaxTitle($_SESSION['BILLING_ADDRESS']['country'],$_SESSION['BILLING_ADDRESS']['state'],$storename);
		else
		$taxtitle	=   "";
		
		
		
		//$Shipp_Country_name = $cart->getCountryNameById($_SESSION['BILLING_ADDRESS']['country']);
		
		

		
		/* Added By Aneesh for Shipping Discount */
		$shipping_discountamt	= 0;
		if ( $shipping_price>0 && $global["shippping_free"] != 'none' && $global["shippping_free_minvalue"] > 0 &&   $global["shippping_free_percentage"] > 0 ) {
			if(	$cartArray['total']	>= $global["shippping_free_minvalue"] ) {
				$shipping_discountamt	=  round (  $shipping_price * ( $global["shippping_free_percentage"]/100 ) ,2 );
				$shipping_price_new   	=  number_format($shipping_price - $shipping_discountamt,2);
			}
		}
		
		
		
		if(empty($tax))
			$tax=0;
		$tax_amount = round(($cartArray['total']+$shipping_price - $shipping_discountamt ) * ($tax) / 100, 2);
		$sub_total = round(($cartArray['total']+$shipping_price - $shipping_discountamt ) * (100 + $tax) / 100, 2);
		
		
		
		
		
		
		if($_SESSION['coupon']['coupon_type']=='F') {
			$coupon_amount = "-";
		} elseif ($_SESSION['coupon']['coupon_type']=='A') {
		// T - substract  from  total
			if($_SESSION['coupon']['substract_from']=="T")
			{ 
				if($sub_total > $_SESSION['coupon']['amount'])
				{ 	$coupon_amount = - $_SESSION['coupon']['amount'];	}
				else			
				{	$coupon_amount = - $sub_total; 	}
			} // O - substract only from option prize
			elseif($_SESSION['coupon']['substract_from']=="O")
			{  
				if($cartArray['AccessoryTotal'] > $_SESSION['coupon']['amount'])
				{ $coupon_amount = - $_SESSION['coupon']['amount'];}
				else
				{ $coupon_amount = - $cartArray['AccessoryTotal']; }
			} // M - substract only from product prize
			elseif($_SESSION['coupon']['substract_from']=="M")
			{  
				if($cartArray['productTotal'] > $_SESSION['coupon']['amount'])
				{ $coupon_amount = - $_SESSION['coupon']['amount'];}
				else
				{ $coupon_amount = - $cartArray['productTotal']; }
			} // P - substract  from product+option prize
			else
			{
				if($cartArray['total'] > $_SESSION['coupon']['amount'])
				{ $coupon_amount = - $_SESSION['coupon']['amount'];}
				else
				{ $coupon_amount = - $cartArray['total']; }
			}
			$coupon_amount= round($coupon_amount, 2);  
		} elseif ($_SESSION['coupon']['coupon_type']=='P') {
		
		// substract from product or price 05-12-07
			if($_SESSION['coupon']['substract_from']=="T")
			{
				$coupon_amount = - $sub_total * $_SESSION['coupon']['amount'] / 100;
			}
			elseif($_SESSION['coupon']['substract_from']=="O")
			{
				$coupon_amount =	- $cartArray['AccessoryTotal'] * $_SESSION['coupon']['amount'] / 100;
			}
			elseif($_SESSION['coupon']['substract_from']=="M")
			{
				$coupon_amount =	- $cartArray['productTotal'] * $_SESSION['coupon']['amount'] / 100;
			}
			else
			{
				$coupon_amount = - $cartArray['total'] * $_SESSION['coupon']['amount'] / 100;
			}
		// end substract from product or price 05-12-07	
			$coupon_amount= round($coupon_amount, 2);  
		}
		if($_SESSION['gift_certificate']['amount'] > $sub_total){
			$certificate_amount = - $sub_total;
		}else{
			$certificate_amount = - $_SESSION['gift_certificate']['amount'];
		}

		
		
		
		
		
		$framework->tpl->assign("COUPON_POSITION", $_SESSION['coupon']['substract_from']);
		
		$framework->tpl->assign("TAXTITLE", $taxtitle);
		$framework->tpl->assign("TAX", $tax);
		$framework->tpl->assign("TAX_AMOUNT", $tax_amount);
		$framework->tpl->assign("CART_TOTAL", $cartArray['total']);
		$framework->tpl->assign("SHIPPING_PRICE", $shipping_price > 0 ? number_format($shipping_price, 2, '.', '') : 'Free');
		$framework->tpl->assign("SUB_TOTAL", $sub_total);
		$framework->tpl->assign("COUPON_AMOUNT", $coupon_amount);
		$framework->tpl->assign("CERTIFICATE_AMOUNT", $certificate_amount);
		$framework->tpl->assign("SHIPPING_DISCOUNT_AMOUNT", $shipping_price_new);
		$framework->tpl->assign("TOTAL_AMOUNT", $sub_total + $coupon_amount + $certificate_amount);
		
		//code by robin
		//$framework->tpl->assign("SESS_PARENT", $ses_parent);
		//$framework->tpl->assign("SESS_BILLING", $ses_billig);
		//$framework->tpl->assign("SESS_SHIPPING", $ses_shipping);
		$framework->tpl->assign("SESS_INSERT_ID", $sess_insert_id);
		$framework->tpl->assign("STR_SESS", $Sess_str);
		$framework->tpl->assign("SESS_ID", $sess_id);
		$framework->tpl->assign("MESSAGE", $message);
		// end
		$framework->tpl->assign("LAST_CAT_ID", $cart->getLastCatID());
		if ( $global['payment_tpl'] == 'cartlogin'){
			if(count($cartArray[records])>0){
				foreach($cartArray[records] as $key=>$val){
					 $storeName  =	$objStore->storeGet($val->store_id);
					 $store_name = $storeName[name];
					 $cartArray[records][$key]->store_name =  $store_name;
				}
			}
		}
		$framework->tpl->assign("CART", $cartArray);
		//print_r($cartArray);
		
		### Start showing Pay Invoice Nov-27-2007 by shinu ###
		if($framework->config['pay_invoice']=="Y")
		{
			$invoice_amt	=	$cart->getInvoiceAmount(); 
			$framework->tpl->assign("INVOICE_AMOUNT", $invoice_amt); 
			if($invoice_amt !== "" && $invoice_amt >0)
			{
				$framework->tpl->assign("INVOICE", $cart->getInvoice());
				$framework->tpl->assign("PAY_INVOICE", "Y");
				$framework->tpl->assign("CART_TOTAL_AMOUNT", $sub_total + $coupon_amount + $certificate_amount);
				$framework->tpl->assign("TOTAL_AMOUNT", ($sub_total + $coupon_amount + $certificate_amount+$invoice_amt)); 

			}
		}
	    $framework->tpl->assign("CONTINUEBUTTON", createButton("CONTINUE SHOPPING","#","continueshopping()"));
		//$framework->tpl->assign("UPDATEBUTTON", createButton("UPDATE","#","update()"));
		$framework->tpl->assign("CHECKOUTBUTTON", createButton("CHECKOUT","#","checkout()"));
		$framework->tpl->assign("SUBMITBUTTON1", createButton("SUBMIT","#","check()"));
		$framework->tpl->assign("SUBMITBUTTON2", createButton("SUBMIT","#","check1()"));
		### End showing Pay Invoice Nov-27-2007 by shinu ###
		
		$framework->tpl->assign("CONTINUEBUTTON_DIV", createImagebutton_Div("Continue Shopping","#","continueshopping()"));
		$framework->tpl->assign("CHECKOUTBUTTON_DIV", createImagebutton_Div("Checkout","#","checkout()"));
		$framework->tpl->assign("SUBMITGIFT1", createImagebutton_Div("Submit","#","check()"));
		$framework->tpl->assign("SUBMITCOUPON1", createImagebutton_Div("Submit","#","check1()"));
		
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/cart/tpl/checkout.tpl");
		//=========================================
		if ( $global['payment_tpl'] == 'popup' ) 
		{
		$framework->tpl->display($global['curr_tpl']."/cart_checkout.tpl");
		exit;
		}
		//=========================================
		break;
	case "remove_gift":
		unset($_SESSION['gift_certificate']);
		redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=checkout"));
		break;
	case "remove_coupon":
		unset($_SESSION['coupon']);
		$_SESSION['shipping_price']	=	$_SESSION['shipping_price_Actual'];
		redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=checkout"));
		break;
	case "payment":
	
		
	setMessage('');	
	#print makeLink(array("mod"=>"cart", "pg"=>"default", "sslval"=>"false", "newurl"=>"true", "storename"=>"ss"), "act=checkout"); 
	#------------------------------------------
	global $store_id;	
	
	//$price=$_REQUEST['price'];
	
// session_id();
	//$sess_str= $_REQUEST['sess_str'];
	$sess_insert_id=$_REQUEST['sess_insert_id'];
	if($sess_insert_id)
	{
		$cart->decodeSession($sess_insert_id);
	}
	//$sess_str=base64_decode($sess_str);
	//echo $sess_str;
	//$sess_str=str_replace('-','"',$sess_str);
	//session_decode($sess_str);
	$sess_id=$_REQUEST['sess_id'];
	if($sess_id)
	{
	$_SESSION['sess_id_old']=$sess_id;
	}else
	{
	$sess_id=$_SESSION['sess_id_old'];
	}
	
	/*
	if(!$_SESSION['BILLING_ADDRESS'])
	{
	
		
		$_SESSION['memberid']=$sess_parent_Ar[0];
		$_SESSION['mem_type']=$sess_parent_Ar[1];
		$_SESSION['mem_sess_id']=$sess_parent_Ar[2];
		$_SESSION['mem_active']=$sess_parent_Ar[3];
		$_SESSION['shipping_price']=$sess_parent_Ar[4];
		$_SESSION['shipping_service']=$sess_parent_Ar[5];
		$_SESSION['international_order']=$sess_parent_Ar[6];
		$_SESSION['secure']=$sess_parent_Ar[7];
		$_SESSION['sess_id_old']=$sess_id;
		
		$_SESSION['BILLING_ADDRESS']['lname']=$sess_billing_Ar[1];
		$_SESSION['BILLING_ADDRESS']['address1']=$sess_billing_Ar[2];
		$_SESSION['BILLING_ADDRESS']['address2']=$sess_billing_Ar[3];
		$_SESSION['BILLING_ADDRESS']['address3']=$sess_billing_Ar[4];
		$_SESSION['BILLING_ADDRESS']['city']=$sess_billing_Ar[5];
		$_SESSION['BILLING_ADDRESS']['state']=$sess_billing_Ar[6];
		$_SESSION['BILLING_ADDRESS']['postalcode']=$sess_billing_Ar[7];
		$_SESSION['BILLING_ADDRESS']['country']=$sess_billing_Ar[8];
		$_SESSION['BILLING_ADDRESS']['telephone']=$sess_billing_Ar[9];
		$_SESSION['BILLING_ADDRESS']['mobile']=$sess_billing_Ar[10];
		$_SESSION['BILLING_ADDRESS']['email']=$sess_billing_Ar[11];
		
		$_SESSION['SHIPPING_ADDRESS']['fname']=$sess_Shipping_Ar[0];
		$_SESSION['SHIPPING_ADDRESS']['lname']=$sess_Shipping_Ar[1];
		$_SESSION['SHIPPING_ADDRESS']['address1']=$sess_Shipping_Ar[2];
		$_SESSION['SHIPPING_ADDRESS']['address2']=$sess_Shipping_Ar[3];
		$_SESSION['SHIPPING_ADDRESS']['address3']=$sess_Shipping_Ar[4];
		$_SESSION['SHIPPING_ADDRESS']['city']=$sess_Shipping_Ar[5];
		$_SESSION['SHIPPING_ADDRESS']['state']=$sess_Shipping_Ar[6];
		$_SESSION['SHIPPING_ADDRESS']['postalcode']=$sess_Shipping_Ar[7];
		$_SESSION['SHIPPING_ADDRESS']['country']=$sess_Shipping_Ar[8];
		$_SESSION['SHIPPING_ADDRESS']['telephone']=$sess_Shipping_Ar[9];
		$_SESSION['SHIPPING_ADDRESS']['mobile']=$sess_Shipping_Ar[10];
		$_SESSION['BILLING_ADDRESS']['method']=$sess_Shipping_Ar[11];
		
	}*/
	//print_r($_SESSION);
	//print_r($sess_parent_Ar);
	//print_r($test);
	//echo unserialize($test);
		$rs = $cart->getCartBox($sess_id);
		$accessoryPrice	=	$cart->getAccessoryPrice($sess_id);
		$ProductPrice	=	$cart->getProductPrice($sess_id);
		$tax	=	$cart->CalculateTax($_SESSION['BILLING_ADDRESS']['country'],$_SESSION['BILLING_ADDRESS']['state'],false,$storename);
		
		
		
		
		
		if(empty($tax))
			$tax=0;
			/*if($price!=""){
			$cart_price 		= 	$price;
			}else{*/
		$cart_price 		= 	$rs->total_price;
		//}
		$shipping_price 	= 	($_SESSION['coupon']['coupon_type']=='F') ? 0 : $_SESSION['shipping_price'];
		
		
		/* Added By Aneesh for Shipping Discount */
		$shipping_discountamt	= 0;
		if ( $shipping_price>0 && $global["shippping_free"] != 'none' && $global["shippping_free_minvalue"] > 0 &&   $global["shippping_free_percentage"] > 0 ) {
			if(	$cart_price	>= $global["shippping_free_minvalue"] ) {
				$shipping_discountamt	=  round (  $shipping_price * ( $global["shippping_free_percentage"]/100 ) ,2 );
				$shipping_price_new   	=  number_format($shipping_price - $shipping_discountamt,2);
			}
		}
		
		
		
		$total_price		= 	round(($cart_price+($shipping_price-$shipping_discountamt)) * (100 + $tax)/100, 2);
		
		
		//================
		
		if($_SESSION['coupon']['coupon_type']=='F') {
			$coupon_amount = "-";
		} elseif ($_SESSION['coupon']['coupon_type']=='A') {
		// ------
		if($_SESSION['coupon']['substract_from']=="T")
			{ 
				if($total_price > $_SESSION['coupon']['amount'])
				{ 	$coupon_amount = - $_SESSION['coupon']['amount'];	}
				else			
				{	$coupon_amount = - $total_price; 	}
			}
			elseif($_SESSION['coupon']['substract_from']=="O")
			{  
				if($accessoryPrice > $_SESSION['coupon']['amount'])
				{ $coupon_amount = - $_SESSION['coupon']['amount'];}
				else
				{ $coupon_amount = - $accessoryPrice; }
			}
			elseif($_SESSION['coupon']['substract_from']=="M")
			{  
				if($ProductPrice > $_SESSION['coupon']['amount'])
				{ $coupon_amount = - $_SESSION['coupon']['amount'];}
				else
				{ $coupon_amount = - $ProductPrice; }
			}
			else
			{
				if($cart_price > $_SESSION['coupon']['amount'])
				{ $coupon_amount = - $_SESSION['coupon']['amount'];}
				else
				{ $coupon_amount = - $cart_price; }
			}
			$coupon_amount= round($coupon_amount, 2);  
		//-------------
			
		} elseif ($_SESSION['coupon']['coupon_type']=='P') {
		// substract from product or total
			if($_SESSION['coupon']['substract_from']=="T")
			{
				$coupon_amount = - $total_price * $_SESSION['coupon']['amount'] / 100;
			}
			elseif($_SESSION['coupon']['substract_from']=="O")
			{
				$coupon_amount =	- $accessoryPrice * $_SESSION['coupon']['amount'] / 100;			
			}
			elseif($_SESSION['coupon']['substract_from']=="M")
			{
				$coupon_amount =	- $ProductPrice * $_SESSION['coupon']['amount'] / 100;			
			}
			else
			{
				$coupon_amount = - $cart_price * $_SESSION['coupon']['amount'] / 100;
			}
			// end substract from product or total
		}
       $certificate_amount = - $_SESSION['gift_certificate']['amount'];

      $total_price			=	round($total_price + $coupon_amount + $certificate_amount,2);
	  ### Start showing Pay Invoice Dec-03-2007 by shinu ###
	  	$payinvoice_amount	= 0;
		if($framework->config['pay_invoice']=="Y")
		{
			$payinvoice_amount	=	$cart->getInvoiceAmount(); 
		}
	  ### Start showing Pay Invoice Nov-27-2007 by shinu ###
	 
		$framework->tpl->assign("TOTAL_AMT", $total_price);
		//=================
		$_SESSION['pp_store_id']	=	$store_id;
		$_SESSION['pp_cart_price']	=	$cart_price ;
		$_SESSION['pp_tax']			=	$tax;
		$_SESSION['pp_total_price']	=	$total_price + $payinvoice_amount;

		
		####################################################added to hide inactive payment modes in payment page:Salim Modified by Vimson on Dec31, commented the old code below
		$activepaymenttypes = 	$typeObj->listAllActivePaymentModes();
		
		$payment_mod	=	array();
		$ArrIndx		=	0;
		foreach($activepaymenttypes as $activepaymenttype) {
			if($activepaymenttype['id'] == 1 && $activepaymenttype['active'] == 'Y')
			{
				$payment_mod[$ArrIndx++]	=	array( 'head'=>'Using Credit Card','url'=>SITE_PATH."/modules/cart/tpl/payment_credit_card.tpl" ,"id"=>'1');
			}
			if($activepaymenttype['id'] == 2 && $activepaymenttype['active'] == 'Y'){
				$payment_mod[$ArrIndx++]	=	array( 'head3'=>'Pay through Paypal Account','url'=>SITE_PATH."/modules/cart/tpl/paypalstandardform.tpl","id"=>'4');
				}
			if($activepaymenttype['id'] == 5 && $activepaymenttype['active'] == 'Y'){
				$payment_mod[$ArrIndx++]	=	array( 'head2'=>'Electronic Check','url'=>SITE_PATH."/modules/cart/tpl/mail_acheck_form.tpl","id"=>'2');
				}
			if($activepaymenttype['id'] == 4 && $activepaymenttype['active'] == 'Y'){
				$payment_mod[$ArrIndx++]	=	array( 'head3'=>'Call with Credit Card','url'=>SITE_PATH."/modules/cart/tpl/callwithcraditcard.tpl","id"=>'3');
				}
			if($activepaymenttype['id'] == 3 && $activepaymenttype['active'] == 'Y'){
				$payment_mod[$ArrIndx++]	=	array( 'head3'=>'Mail a Check','url'=>SITE_PATH."/modules/cart/tpl/mail_a_check.tpl","id"=>'5');
				}
			if($activepaymenttype['id'] == 6 && $activepaymenttype['active'] == 'Y'){
				$payment_mod[$ArrIndx++]	=	array( 'head3'=>'2CheckOut','url'=>SITE_PATH."/modules/cart/tpl/2CheckOut.tpl","id"=>'6');
				}
			if($activepaymenttype['id'] == 7 && $activepaymenttype['active'] == 'Y'){
				$payment_mod[$ArrIndx++]	=	array( 'head3'=>'Google Checkout','url'=>SITE_PATH."/modules/cart/tpl/googlecheckoutform.tpl","id"=>'7');
				}
			if($activepaymenttype['id'] == 8 && $activepaymenttype['active'] == 'Y'){
				$payment_mod[$ArrIndx++]	=	array( 'head3'=>'Worldpay','url'=>SITE_PATH."/modules/cart/tpl/worldpayform.tpl","id"=>'8');
				}

			
		}
		
		
		
		/*$payment_mod = array();
		if( $activepaymenttype['0']['active'] == 'Y' && $activepaymenttype['0']['id'] == 1)
			$payment_mod[0] = array( 'head'=>'Using Credit Card','url'=>SITE_PATH."/modules/cart/tpl/payment_credit_card.tpl" ,"id"=>'1');
		if( $activepaymenttype['1']['active'] == 'Y' && $activepaymenttype['1']['id'] == 2)
			$payment_mod[3] = array( 'head3'=>'Pay through Paypal Account','url'=>SITE_PATH."/modules/cart/tpl/paypalstandardform.tpl","id"=>'4');
		if( $activepaymenttype['2']['active'] == 'Y' && $activepaymenttype['2']['id'] == 5)
			$payment_mod[1] = array( 'head2'=>'Electronic Check','url'=>SITE_PATH."/modules/cart/tpl/mail_acheck_form.tpl","id"=>'2');
		if( $activepaymenttype['3']['active'] == 'Y' && $activepaymenttype['3']['id'] == 4)
			$payment_mod[2] = array( 'head3'=>'Call with Credit Card','url'=>SITE_PATH."/modules/cart/tpl/callwithcraditcard.tpl","id"=>'3');
		if( $activepaymenttype['4']['active'] == 'Y' && $activepaymenttype['4']['id'] == 3)
			$payment_mod[4] = array( 'head3'=>'Mail a Check','url'=>SITE_PATH."/modules/cart/tpl/mail_a_check.tpl","id"=>'5');*/
		####################################################


		$framework->tpl->assign("TOTAL_PRICE",$total_price);
		$ip_addr	=	$_SERVER['REMOTE_ADDR'];
		$framework->tpl->assign("CLIENT_IP",$ip_addr);
		$framework->tpl->assign("CREDITCARD", $typeObj->GetAllCreditCards($storename));
		list($rs, $numpad, $cnt, $limitList)	= 	$typeObj->listAllPaymentTypes($pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy,$storename);
		$framework->tpl->assign("CREDITCARDLOGO", $rs);
		$framework->tpl->assign("FIELDS",$typeObj->GetMailCheckFields()); 
		$framework->tpl->assign("MATIDATORY",$typeObj->GetMailCheckmatidatory());
		/*$pay_mod = array(
		  array( 'head'=>'Using Credit Card','url'=>SITE_PATH."/modules/cart/tpl/payment_credit_card.tpl" ,"id"=>'1'),
          array( 'head2'=>'Electronic Check','url'=>SITE_PATH."/modules/cart/tpl/mail_acheck_form.tpl","id"=>'2'),
		  array( 'head3'=>'Call with Credit Card','url'=>SITE_PATH."/modules/cart/tpl/callwithcraditcard.tpl","id"=>'3'),
		  array( 'head3'=>'Pay through Paypal Account','url'=>SITE_PATH."/modules/cart/tpl/paypalstandardform.tpl","id"=>'4'),
		  array( 'head3'=>'Mail a Check','url'=>SITE_PATH."/modules/cart/tpl/mail_a_check.tpl","id"=>'5'));*/

		$framework->tpl->assign("PAYMENT_ITEMS",$payment_mod);		
		
		# The following block added for paypal standard integration
		$res_max_order_no=mysql_query("SELECT order_number  FROM orders order by id DESC");
		if(mysql_num_rows($res_max_order_no)>0){
			$max_order_no=mysql_result($res_max_order_no,0,order_number);
		}
		$OrderNumb=substr($max_order_no, 4, 4)+1; 
		
	    $Today			=	str_pad(date('y'), 2, '0', STR_PAD_LEFT);
		$ThisMonth		=	str_pad(date('m'), 2, '0', STR_PAD_LEFT);
		$OrderNumb		=	str_pad($OrderNumb, 4, '0', STR_PAD_LEFT);
		
		$OrderNumber	=	$Today.''.$ThisMonth.''.$OrderNumb;
		$framework->tpl->assign("ORDER_NUMBER", $OrderNumber);
		
		$_SESSION['order_id']	=	$OrderNumber;
		
		/**
		 * Encoding and passing session id to paypal
		 */
		$SessInsertId	=	$cart->encodeSession();
		$framework->tpl->assign("CURR_SESS_ID", $SessInsertId);
		
		
		/**
		 * The following code snipplet used for fetching the paypal account
		 */
		if($framework->config['payment_receiver'] == 'store') {
			$PaypalBusinessAccount	=	$PaymentObj->getPayflowLinkDetailsFromStoreName($_REQUEST['storename']);
			$test_mode_paypal		=	$PaymentObj->getPayflowtest_modeFromStoreName($_REQUEST['storename']);
		} else {
			$PaypalBusinessAccount	=	$typeObj->getPaypalAccountEmail();
		}
			
		
		$framework->tpl->assign("B_COUNTRY", $_SESSION['BILLING_ADDRESS']['country']);
		$framework->tpl->assign("B_MOBILE", $_SESSION['BILLING_ADDRESS']['mobile']);
		$framework->tpl->assign("B_STATE", $_SESSION['BILLING_ADDRESS']['state']);
		$framework->tpl->assign("S_FNAME", $_SESSION['SHIPPING_ADDRESS']['country']);
		$framework->tpl->assign("S_LNAME", $_SESSION['SHIPPING_ADDRESS']['address1']);
		$framework->tpl->assign("S_ADDRESS", $_SESSION['SHIPPING_ADDRESS']['city']);
		$framework->tpl->assign("S_CITY", $_SESSION['SHIPPING_ADDRESS']['state']);
		$framework->tpl->assign("S_POSTALCODE", $_SESSION['SHIPPING_ADDRESS']['postalcode']);
		$framework->tpl->assign("S_COUNTRY", $_SESSION['SHIPPING_ADDRESS']['country']);
		$framework->tpl->assign("S_TELPHONE", $_SESSION['SHIPPING_ADDRESS']['telephone']);
		$framework->tpl->assign("S_STATE", $_SESSION['SHIPPING_ADDRESS']['state']);
			
		
		$Country2Code	=	$objUser->getCountry2LetterCode($_SESSION['BILLING_ADDRESS']['country']);
		$framework->tpl->assign("PAYPAL_ACCOUNT_MAIL", $PaypalBusinessAccount);
		$framework->tpl->assign("PAYPAL_TEST_MODE", $test_mode_paypal);
		$framework->tpl->assign("PAYPAL_AMOUNT", $total_price+ $payinvoice_amount);
		$framework->tpl->assign("FIRST_NAME", $_SESSION['BILLING_ADDRESS']['fname']);
		$framework->tpl->assign("LAST_NAME", $_SESSION['BILLING_ADDRESS']['lname']);
		$framework->tpl->assign("ADDRESS1", $_SESSION['BILLING_ADDRESS']['address1']);
		$framework->tpl->assign("ADDRESS2", $_SESSION['BILLING_ADDRESS']['address2']);
		$framework->tpl->assign("CITY", $_SESSION['BILLING_ADDRESS']['city']);
		$framework->tpl->assign("ZIP_CODE", $_SESSION['BILLING_ADDRESS']['postalcode']);
		$framework->tpl->assign("COUNTRY2_CODE", $Country2Code);
		$framework->tpl->assign("PHONE_NUMBER", $_SESSION['BILLING_ADDRESS']['telephone']);
		$framework->tpl->assign("CUSTOMER_EMAIL", $_SESSION['BILLING_ADDRESS']['email']);
		if($Country2Code == 'US')
			$State2Code	=	$objUser->getUSStateCodeFromStateName($_SESSION['BILLING_ADDRESS']['state']);
		else
			$State2Code	=	$_SESSION['BILLING_ADDRESS']['state'];
		$framework->tpl->assign("STATE_CODE", $State2Code);
		
		//code for 2CheckOut strt****
		//code by robin
		
		if($framework->config['payment_receiver'] == 'store') {
			$twocheckoutAccount	=	$PaymentObj->get2checkoutDetailsFromStoreName($_REQUEST['storename']);
		} else {
			$twocheckoutAccount	=	$PaymentObj->get2checkoutDetailsFromStoreName();
		}
		
		$framework->tpl->assign("TwoCOAccountNumber", $twocheckoutAccount['account_number']);
				
		
		//2CheckOut End******
		
		if($framework->config['payment_receiver'] == 'store') {
			$worldpayAccount	=	$PaymentObj->getWorldpayDetailsFromStoreName($_REQUEST['storename']);
		} else {
			$worldpayAccount=	$PaymentObj->getWorldpayDetailsFromStoreName();
		}
		$framework->tpl->assign("WORLDPAY_MERCHANT_ID",$worldpayAccount);
		
		
		$cctext 	=	$objCms->GetCMSpageByTitle('creditcardtext');
		$framework->tpl->assign("CREDIT_CARD_TEXT", $cctext[0]['content']);
		
		$framework->tpl->assign("SUBMITBUTTON1", createButton("SUBMIT","#","check123()"));
		$framework->tpl->assign("SUBMITBUTTON2", createButton("SUBMIT","#","check124()"));
		$framework->tpl->assign("SUBMITBUTTON3", createButton("SUBMIT","#","check125()"));
		$framework->tpl->assign("SUBMITBUTTON4", createButton("SUBMIT","#","check126()"));
		$framework->tpl->assign("SUBMITBUTTON1_DIV", createImagebutton_Div("Submit","#","check123()"));
		$framework->tpl->assign("SUBMITBUTTON2_DIV", createImagebutton_Div("Submit","#","check124()"));
		$framework->tpl->assign("SUBMITBUTTON3_DIV", createImagebutton_Div("Submit","#","check125()"));
		$framework->tpl->assign("SUBMITBUTTON4_DIV", createImagebutton_Div("Submit","#","check126()"));
		$framework->tpl->assign("CREDITCARD_METHOD", SITE_PATH."/modules/cart/tpl/payment_credit_card.tpl");
		$framework->tpl->assign("PAYMENT_CONDITION", SITE_PATH."/modules/cart/tpl/payment_condition.tpl"); 
		$framework->tpl->assign("MAIL_ACHECK", SITE_PATH."/modules/cart/tpl/mail_acheck_form.tpl"); 
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/cart/tpl/payment.tpl");
		
		
		$storedetail=$objStore->storeGetByName($_REQUEST['storename']);
		$framework->tpl->assign("STORENOW", $storedetail); 		
		###################################
		$avilable_table	=$framework->config['avilable_access'];
		if($avilable_table=='N'){
			$cartArray = $cart->getCart('N',$sess_id);
		}else{
			$cartArray = $cart->getCart('',$sess_id);
		}	
		$shipping_price = ($_SESSION['coupon']['coupon_type']=='F') ? 0 : $_SESSION['shipping_price'];
		
		$tax	=	$cart->CalculateTax($_SESSION['BILLING_ADDRESS']['country'],$_SESSION['BILLING_ADDRESS']['state'],false,$storename);
		
		
		
		
		
		
		if(empty($tax))
			$tax=0;
		$tax_amount = round(($cartArray['total']+($shipping_price-$shipping_discountamt)) * ($tax) / 100, 2);
		$sub_total = round(($cartArray['total']+($shipping_price-$shipping_discountamt)) * (100 + $tax) / 100, 2);
		
		
		
		if($_SESSION['coupon']['coupon_type']=='F') {
			$coupon_amount = "-";
		} elseif ($_SESSION['coupon']['coupon_type']=='A') {
		// ------
		if($_SESSION['coupon']['substract_from']=="T")
			{ 
				if($sub_total > $_SESSION['coupon']['amount'])
				{ 	$coupon_amount = - $_SESSION['coupon']['amount'];	}
				else			
				{	$coupon_amount = - $sub_total; 	}
			}
			elseif($_SESSION['coupon']['substract_from']=="O")
			{  
				if($cartArray['AccessoryTotal'] > $_SESSION['coupon']['amount'])
				{ $coupon_amount = - $_SESSION['coupon']['amount'];}
				else
				{ $coupon_amount = - $cartArray['AccessoryTotal']; }
			}
			elseif($_SESSION['coupon']['substract_from']=="M")
			{  
				if($cartArray['productTotal'] > $_SESSION['coupon']['amount'])
				{ $coupon_amount = - $_SESSION['coupon']['amount'];}
				else
				{ $coupon_amount = - $cartArray['productTotal']; }
			}
			else
			{
				if($cartArray['total'] > $_SESSION['coupon']['amount'])
				{ $coupon_amount = - $_SESSION['coupon']['amount'];}
				else
				{ $coupon_amount = - $cartArray['total']; }
			}
			$coupon_amount= round($coupon_amount, 2);  
		//-------------
			
		} elseif ($_SESSION['coupon']['coupon_type']=='P') {
		// substract from product or total
			if($_SESSION['coupon']['substract_from']=="T")
			{
				$coupon_amount = - $sub_total * $_SESSION['coupon']['amount'] / 100;
			}
			elseif($_SESSION['coupon']['substract_from']=="O")
			{
				$coupon_amount =	- $cartArray['AccessoryTotal'] * $_SESSION['coupon']['amount'] / 100;
			}
			elseif($_SESSION['coupon']['substract_from']=="M")
			{
				$coupon_amount =	- $cartArray['productTotal'] * $_SESSION['coupon']['amount'] / 100;
			}
			else
			{
				$coupon_amount = - $cartArray['total'] * $_SESSION['coupon']['amount'] / 100;
			}
			$coupon_amount= round($coupon_amount, 2);  
		// end substract from product or total

		}
				if($_SESSION['gift_certificate']['amount'] > $sub_total){
			$certificate_amount = - $sub_total;
		}else{
			$certificate_amount = - $_SESSION['gift_certificate']['amount'];
		}

		$framework->tpl->assign("TAX", $tax);
		$framework->tpl->assign("TAX_AMOUNT", $tax_amount);
		$framework->tpl->assign("CART_TOTAL", $cartArray['total']);
		$framework->tpl->assign("SHIPPING_PRICE", $shipping_price > 0 ? number_format($shipping_price, 2, '.', '') : 'Free');
		$framework->tpl->assign("SUB_TOTAL", $sub_total);
		$framework->tpl->assign("COUPON_AMOUNT", $coupon_amount);
		$framework->tpl->assign("CERTIFICATE_AMOUNT", $certificate_amount);
		$framework->tpl->assign("TOTAL_AMOUNT", $sub_total + $coupon_amount + $certificate_amount);
		
		
		### Start showing Pay Invoice Nov-27-2007 by shinu ###
		if($framework->config['pay_invoice']=="Y")
		{
			$invoice_amt	=	$cart->getInvoiceAmount(); 
			$framework->tpl->assign("INVOICE_AMOUNT", $invoice_amt); 
			if($invoice_amt !== "" && $invoice_amt >0)
			{
				$framework->tpl->assign("INVOICE", $cart->getInvoice());
				$framework->tpl->assign("PAY_INVOICE", "Y");
				$framework->tpl->assign("CART_TOTAL_AMOUNT", $sub_total + $coupon_amount + $certificate_amount);
				$framework->tpl->assign("TOTAL_AMOUNT", ($sub_total + $coupon_amount + $certificate_amount+$invoice_amt)); 
				$framework->tpl->assign("TOTAL_AMT", ($sub_total + $coupon_amount + $certificate_amount+$invoice_amt));
			}
		}
		
		### End showing Pay Invoice Nov-27-2007 by shinu ###
		
		/*==============vinoy Add=================*/
		//print_r($_SESSION);
		$shipmethod=$_SESSION['SHIPPING_ADDRESS'] ['method'];
		
		if($framework->config['payment_receiver'] == 'store') {
			$merchant_id=	$PaymentObj->getGoogleCheckoutDetailsFromStoreName($_REQUEST['storename']);
			$mercant_key	=	$PaymentObj->getGoogleCheckoutDetailsFromStoreName1($_REQUEST['storename']);
		} else {
			$merchant_id=	$PaymentObj->getGoogleCheckoutDetailsFromStoreName();
			$mercant_key=	$PaymentObj->getGoogleCheckoutDetailsFromStoreName1();
		}
				
		require_once FRAMEWORK_PATH."/includes/payment/GoogleCheckOut/config.php";
		//$merchant_id = $typeObj->getGoogleCheckoutID();
		//$mercant_key = 'jP2mcev-VVFZhmmUDyFaGQ';
		//$mercant_key = $typeObj->getGoogleCheckoutKEY();
		$framework->tpl->assign("GOOGLECHECKOUT_MERCHANT_ID", $merchant_id);
		$private['SES_INSERT_ID']=$SessInsertId;
		$GCheckout = new gCart($merchant_id, $mercant_key,'',$private);
		//$GCheckout->setMerchantCheckoutFlowSupport(SITE_PATH, SITE_PATH);
		//$AARechargeableBatteryPack= new gItem();
		$cartitem = array();
		foreach($cartArray['records'] as $items)
		{
		    $cartitem[] = new gItem($items->name,$items->name,$items->quantity,$items->accessory_price+$items->price);
		}
		
		$GCheckout->addItems($cartitem);
		$GCheckout->setPickup($shipmethod,$shipping_price);
		
		$GCheckout->setMerchantCheckoutFlowSupport("http://192.168.1.254/theunionshop/index.php?mod=test", "http://192.168.1.254/thepersonalizedgift/index.php","","");
		
		$Gcart= base64_encode($GCheckout->getCart());
		$Gsign=base64_encode($GCheckout->getSignature($GCheckout->getCart()));
		$framework->tpl->assign("Gcart",$Gcart);
		$framework->tpl->assign("Gsign",$Gsign);
		
		//==============vinoy end================
				
		//code by robin
		//$framework->tpl->assign("SESS_PARENT", $ses_parent);
		//$framework->tpl->assign("SESS_BILLING", $ses_billig);
		//$framework->tpl->assign("SESS_SHIPPING", $ses_shipping);
		$framework->tpl->assign("SESS_INSERT_ID", $sess_insert_id);
		$framework->tpl->assign("STR_SESS", $Sess_str);
		$framework->tpl->assign("SESS_ID", $sess_id);
		
		// end
		$framework->tpl->assign("LAST_CAT_ID", $cart->getLastCatID());
		if ( $global['payment_tpl'] == 'cartlogin'){
			if(count($cartArray[records])>0){
				foreach($cartArray[records] as $key=>$val){
					 $storeName  =	$objStore->storeGet($val->store_id);
					 $store_name = $storeName[name];
					 $cartArray[records][$key]->store_name =  $store_name;
				}
			}
		}
		$framework->tpl->assign("CART", $cartArray);
		//print_r($cartArray);

		###################################
		if ($global['payment_tpl'] == 'popup') 
		{
		$framework->tpl->display($global['curr_tpl']."/final_checkout.tpl");
		exit;
		}
		break;
	case "final_checkout":
	//---------------------------------
	
		$sname=$_POST['storename'];
		 $paytype=$_REQUEST['paytype'];
		

		//$storedetail=$objStore->storeGetByName($sname);
		$sql="select id from store where name='$sname'";
		$res=mysql_query($sql);
		while($row=mysql_fetch_array($res)){
		//$rs = $this->db->get_row("$sql");
		 $sid=$row['id'];
		}
		//---------------------------------
		if($_SESSION['sess_id_old'])
		{
			$rs = $cart->getCartBox($_SESSION['sess_id_old']);
			$accessoryPrice		=	$cart->getAccessoryPrice($_SESSION['sess_id_old']);
			$ProductPrice		=	$cart->getProductPrice($_SESSION['sess_id_old']);
		}else
		{	$rs = $cart->getCartBox();
			$accessoryPrice		=	$cart->getAccessoryPrice();
			$ProductPrice		=	$cart->getProductPrice();
		}
		//$rs = $cart->getCartBox();
		$tax	=	$cart->CalculateTax($_SESSION['BILLING_ADDRESS']['country'],$_SESSION['BILLING_ADDRESS']['state'],false,$storename);
		if(empty($tax))
			$tax=0;
		
		$_SESSION['tax']	=	$tax;
			
		$cart_price 		= 	$rs->total_price;
		$shipping_price 	= 	($_SESSION['coupon']['coupon_type']=='F') ? 0 : $_SESSION['shipping_price'];
		
		
		/* Added By Aneesh for Shipping Discount */
		$shipping_discountamt	= 0;
		if ( $shipping_price>0 && $global["shippping_free"] != 'none' && $global["shippping_free_minvalue"] > 0 &&   $global["shippping_free_percentage"] > 0 ) {
			if(	$cart_price	>= $global["shippping_free_minvalue"] ) {
				$shipping_discountamt	=  round (  $shipping_price * ( $global["shippping_free_percentage"]/100 ) ,2 );
				$shipping_price_new   	=  number_format($shipping_price - $shipping_discountamt,2);
			}
		}
		
		
		$total_price		= 	round(($cart_price+ ($shipping_price-$shipping_discountamt) ) * (100 + $tax)/100, 2);
		
	
		
		if($_SESSION['coupon']['coupon_type']=='F') {
			$coupon_amount = "-";
		} elseif ($_SESSION['coupon']['coupon_type']=='A') {
			//$coupon_amount = - $_SESSION['coupon']['amount'];
		// ------
		if($_SESSION['coupon']['substract_from']=="T")
			{ 
				if($total_price > $_SESSION['coupon']['amount'])
				{ 	$coupon_amount = - $_SESSION['coupon']['amount'];	}
				else			
				{	$coupon_amount = - $total_price; 	}
			}
			elseif($_SESSION['coupon']['substract_from']=="O")
			{  
				if($accessoryPrice > $_SESSION['coupon']['amount'])
				{ $coupon_amount = - $_SESSION['coupon']['amount'];}
				else
				{ $coupon_amount = - $accessoryPrice; }
			}
			elseif($_SESSION['coupon']['substract_from']=="M")
			{  
				if($ProductPrice > $_SESSION['coupon']['amount'])
				{ $coupon_amount = - $_SESSION['coupon']['amount'];}
				else
				{ $coupon_amount = - $ProductPrice; }
			}
			else
			{
				if($cart_price > $_SESSION['coupon']['amount'])
				{ $coupon_amount = - $_SESSION['coupon']['amount'];}
				else
				{ $coupon_amount = - $cart_price; }
			}
			$coupon_amount= round($coupon_amount, 2);  
		//-------------
			
		} elseif ($_SESSION['coupon']['coupon_type']=='P') {
		//	$coupon_amount = - $total_price * $_SESSION['coupon']['amount'] / 100;
			// substract from product or total
			if($_SESSION['coupon']['substract_from']=="T")
			{
				$coupon_amount = - $total_price * $_SESSION['coupon']['amount'] / 100;
			}
			elseif($_SESSION['coupon']['substract_from']=="O")
			{
				$coupon_amount = - $accessoryPrice * $_SESSION['coupon']['amount'] / 100;
			}
			elseif($_SESSION['coupon']['substract_from']=="M")
			{
				$coupon_amount = - $ProductPrice * $_SESSION['coupon']['amount'] / 100;
			}
			else
			{
				$coupon_amount = - $cart_price * $_SESSION['coupon']['amount'] / 100;
			}
			$coupon_amount= round($coupon_amount, 2);  
		// end substract from product or total
		}
		
		### Start showing Pay Invoice Nov-27-2007 by shinu ###
		if($framework->config['pay_invoice']=="Y")
		{
			$invoice_amt	=	$cart->getInvoiceAmount(); 
			if($invoice_amt !== "" && $invoice_amt >0)
			{
				$total_price	=	$total_price+$invoice_amt;
			}
		}
		### End showing Pay Invoice Nov-27-2007 by shinu ###
		global $store_id;
		
		
		
		$certificate_amount = - $_SESSION['gift_certificate']['amount'];
		
		$paid_price			=	$total_price + $coupon_amount + $certificate_amount;
		$paid_price			=	$paid_price < 0 ? 0 : $paid_price;
		// for credit card amount added on 27-11
		$billing_price		=	$total_price + $coupon_amount + $certificate_amount;
		
		
		$checkout			=	array('user_id'				=>		$_SESSION['memberid'], 
		                              'store_id'            =>      $sid,
									  'cart_price'			=>		$cart_price,
									  'shipping_price'		=>		$_SESSION['shipping_price'],
									  'tax'					=>		$tax,
									  'paytype' 			=>		$paytype,
									  'total_price'			=>		$total_price,
									  'paid_price'			=>		$paid_price,
									  'date_ordered'		=>		date("Y-m-d H:i:s"),
									  //'order_type'		    =>	    U,
									  'billing_first_name'	=> 		$_SESSION['BILLING_ADDRESS']['fname'],
									  'billing_last_name'	=> 		$_SESSION['BILLING_ADDRESS']['lname'],
									  'billing_address1'	=> 		$_SESSION['BILLING_ADDRESS']['address1'],
									  'billing_address2'	=> 		$_SESSION['BILLING_ADDRESS']['address2'],
									  //'billing_address3'	=> 		$_SESSION['BILLING_ADDRESS']['address3'],
									  'billing_city'		=> 		$_SESSION['BILLING_ADDRESS']['city'],
									  'billing_state'		=> 		$_SESSION['BILLING_ADDRESS']['state'],
									  'billing_postalcode' 	=> 		$_SESSION['BILLING_ADDRESS']['postalcode'],
									  'billing_country'		=> 		$_SESSION['BILLING_ADDRESS']['country'],
									  'billing_telephone'	=> 		$_SESSION['BILLING_ADDRESS']['telephone'],
									  'billing_mobile'		=> 		$_SESSION['BILLING_ADDRESS']['mobile'],
									  'billing_email'		=> 		$_SESSION['BILLING_ADDRESS']['email'],
									  'shipping_first_name'	=> 		$_SESSION['SHIPPING_ADDRESS']['fname'],
									  'shipping_last_name'	=> 		$_SESSION['SHIPPING_ADDRESS']['lname'],
									  'shipping_address1'	=> 		$_SESSION['SHIPPING_ADDRESS']['address1'],
									  'shipping_address2'	=> 		$_SESSION['SHIPPING_ADDRESS']['address2'],
									  //'shipping_address3'	=> 		$_SESSION['SHIPPING_ADDRESS']['address3'],
									  'shipping_city'		=> 		$_SESSION['SHIPPING_ADDRESS']['city'],
									  'shipping_state'		=> 		$_SESSION['SHIPPING_ADDRESS']['state'],
									  'shipping_postalcode'	=> 		$_SESSION['SHIPPING_ADDRESS']['postalcode'],
									  'shipping_country'	=> 		$_SESSION['SHIPPING_ADDRESS']['country'],
									  'shipping_telephone'	=> 		$_SESSION['SHIPPING_ADDRESS']['telephone'],
									  'shipping_mobile'		=> 		$_SESSION['SHIPPING_ADDRESS']['mobile'],
									  'shipping_method'		=> 		$_SESSION['SHIPPING_ADDRESS']['method'],
									  'contact_me'			=> 		$_SESSION['order_contact_me'],
									  'notes'				=> 		$_SESSION['order_notes'],
									  'shipping_service'	=>		$_SESSION['shipping_service'],
									  'international_order'	=>		$_SESSION['international_order']
									  );
			$_SESSION['order_contact_me']	=	"";
			$_SESSION['order_notes']		=	"";
			if(trim($billing_address3))
			{
				$newarray		=	array("billing_address3"	=>	$_SESSION['BILLING_ADDRESS']['address3']);
				$checkout			=	array_merge($checkout,$newarray);
			}
			if(trim($shipping_address3))
			{
				$newarray		=	array("shipping_address3"	=>	$_SESSION['SHIPPING_ADDRESS']['address3']);
				$checkout			=	array_merge($checkout,$newarray);
			}
		
		# If the paypal returns the approved message after successful payment.
		if($_REQUEST['paypalstatus'] === 'Approved') {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$cart->placeOrder($checkout,$store_id);
			$sess_insert_id= $cart->encodeSession();
			### Start saving Pay Invoice to orders_invoice Nov-27-2007 by shinu ###
			if($framework->config['pay_invoice']=="Y")
			{
				$last_order_id	=	$cart->getOrderId();
				$cart->orderInvoice($last_order_id);
				$cart->deleteCartInvoice ();  
			}
			### End saving Pay Invoice to orders_invoice Nov-27-2007 by shinu ###
			
			
			redirect(makeLink(array("mod"=>"cart", "pg"=>"default","sslval"=>"false", "newurl"=>"true", "storename"=>$_REQUEST['storename'] ), "act=thanks&fId=$fId&sId=$sId&sess_insert_id=$sess_insert_id"));
		}
		
		
		
		## call with credit card
		if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['hid_check']=="2") { 
			
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$cart->placeOrder($checkout,$store_id);
			$sess_insert_id= $cart->encodeSession();
			### Start saving Pay Invoice to orders_invoice Nov-27-2007 by shinu ###
			if($framework->config['pay_invoice']=="Y")
			{
				$last_order_id	=	$cart->getOrderId();
				$cart->orderInvoice($last_order_id);
				$cart->deleteCartInvoice ();  
			}
			### End saving Pay Invoice to orders_invoice Nov-27-2007 by shinu ###
			redirect(makeLink(array("mod"=>"cart", "pg"=>"default","sslval"=>"false", "newurl"=>"true", "storename"=>$_REQUEST['storename'] ), "act=thanks&fId=$fId&sId=$sId&sess_insert_id=$sess_insert_id"));
		}							  
		## ------------- saving check details --------------------		
			
						
			
			if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['hid_check']=="1") { 
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			
			$storename	=	 $_REQUEST ['storename'];    # https - http
			
			if($amount=="" && $hid_amount=="1")	{	
				$message 		=	"Amount is required";	setMessage($message);
				redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=payment&fId=$fId&sId=$sId&storename=$storename"));	}
			if($transit_routing=="" && $hid_transit_routing=="1")	{
				$message 		=	"Transit Routing is required"; setMessage($message);
				redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=payment&fId=$fId&sId=$sId&storename=$storename"));	}
			if($account_number=="" && $hid_account_number=="1")	{
				$message 		=	"Account Number is required"; setMessage($message);
				redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=payment&fId=$fId&sId=$sId&storename=$storename"));	}
			if($check_number=="" && $hid_check_number=="1")	{
				$message 		=	"Check Number is required"; setMessage($message);
				redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=payment&fId=$fId&sId=$sId&storename=$storename"));	}
			if($account_type=="" && $hid_account_type=="1")	{
				$message 		=	"Account Type is required"; setMessage($message);
				redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=payment&fId=$fId&sId=$sId&storename=$storename"));	}
			if($bank_name=="" && $hid_bank_name=="1")	{
				$message 		=	"Bank name is required"; setMessage($message);
				redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=payment&fId=$fId&sId=$sId&storename=$storename"));	}
			if($bank_state=="" && $hid_bank_state=="1")	{
				$message 		=	"Bank state is required"; setMessage($message);
				redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=payment&fId=$fId&sId=$sId&storename=$storename"));	}
			if($social_security_number=="" && $hid_social_security_number=="1")	{
				$message 		=	"social security number is required"; setMessage($message);
				redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=payment&fId=$fId&sId=$sId&storename=$storename"));	}
			if($drivers_license=="" && $hid_drivers_license=="1")	{
				$message 		=	"Drivers license is required"; setMessage($message);
				redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=payment&fId=$fId&sId=$sId&storename=$storename"));	}
			if($drivers_license_state=="" && $hid_drivers_license_state=="1")	{
				$message 		=	"Drivers license state is required"; setMessage($message);
				redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=payment&fId=$fId&sId=$sId&storename=$storename"));	}
			
			
			$cart->placeOrder($checkout,$typeObj->GetMailCheckmatidatory());
			$sess_insert_id= $cart->encodeSession();
			### Start saving Pay Invoice to orders_invoice Nov-27-2007 by shinu ###
			if($framework->config['pay_invoice']=="Y")
			{
				$last_order_id	=	$cart->getOrderId();
				$cart->orderInvoice($last_order_id);
				$cart->deleteCartInvoice ();  
			}
			### End saving Pay Invoice to orders_invoice Nov-27-2007 by shinu ###
			if( ($message 	= 	$cart->AddcheckDetails($req,$typeObj->GetMailCheckmatidatory())) === true ) {
				$action = $req['id'] ? "Updated" : "Added";
            	setMessage("Check details added Successfully", MSG_SUCCESS);
				redirect(makeLink(array("mod"=>"cart", "pg"=>"default", "sslval"=>"false", "newurl"=>"true", "storename"=>$_REQUEST['storename']), "act=thanks&fId=$fId&sId=$sId&sess_insert_id=$sess_insert_id"));
			}
			
			
			setMessage($message);
		}
		
		## ------------- saving check details --------------------							  
		if($paid_price) {
			
			/*
			# This code fragment commented by vimson@newagesmb.com on June 21 11pm for implementing payment gateway.
			//Call payment gateway
			include_once(FRAMEWORK_PATH."/includes/payment/authorize.net/include.php");
			$paymentArray = array
				(
					"amount"		=>	$paid_price,
					"creditCard"	=>	$_POST['creditCard'],
					"expMonth"		=>	$_POST['Expiry_Month'],
					"expYear"		=>	$_POST['Expiry_Year'],
					"cvc"			=>	$_POST['cvc']
				); */

			

			if($global['payment_receiver'] == 'admin') 	
				$store_name		=	'0';
			else
				$store_name		=	(trim($_REQUEST['storename']) == '') ? '0' : $_REQUEST['storename'];
			$ConfExists		=	$PaymentObj->configurationExistsForStore($store_name);
			
			if($ConfExists === FALSE) {
				# The control here indicate that the configuration settings are not available
				$ConfMessage	=	'Payment Configuration not Entered at Store Manage Section';
				#$framework->tpl->assign("INACTIVE",'disabled');
				redirect(makeLink(array("mod"=>"cart", "pg"=>"default", "sslval"=>"true", "newurl"=>"true", "storename"=>$_REQUEST['storename']), "act=payment&price={$_REQUEST['price']}&sess_id={$_REQUEST['sess_id']}&sess_insert_id={$_REQUEST['sess_insert_id']}&storename={$_REQUEST['storename']}&ConfMessage=$ConfMessage"));
			}
						
			$sub_pack		=	urlencode($_REQUEST['sub_pack']);
			$btn_save		=	$_REQUEST['btn_save'];
			//if($btn_save == 'Submit') {
			
			if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['hid_creditcard']=="1") {
			
			// code modified by robin date 13-09
			if($global['payment_receiver'] == 'admin') 
				$store_name=0;								
			// end robin
				$PaymentMethod	=	$PaymentObj->getActivePaymentGateway($store_name);  #Paypal Pro, Authorize.Net, LinkPoint Central	0 --> Store Owned by admin, function prototype getActivePaymentGateway($StoreName)

                $storename		=	$_REQUEST ['storename'];    # https - http
				$UserDetails	=	$objUser->getUserdetails($_SESSION['memberid']);
				
				if($PaymentMethod === 'Paypal Pro') {

					$Params						 =		  array(); 
					$Params['creditCardType']    =        $_REQUEST['card_type'];  
					$Params['creditCard']        =        $_REQUEST['creditCard'];
					$Params['Expiry_Month']      =        $_REQUEST['Expiry_Month'];      
					$Params['Expiry_Year']       =        $_REQUEST['Expiry_Year']; 
					$Params['cvc']               =        $_REQUEST['cvc'];

					$Params['firstName']         =        $_SESSION['BILLING_ADDRESS']['fname'];
					$Params['lastName']          =        $_SESSION['BILLING_ADDRESS']['lname']; 
					$Params['address1']          =        $_SESSION['BILLING_ADDRESS']['address1']; 
					$Params['address2']          =        $_SESSION['BILLING_ADDRESS']['address2']; 
					$Params['city']              =        $_SESSION['BILLING_ADDRESS']['city'];
					$Params['state']             =        $_SESSION['BILLING_ADDRESS']['state']; 
					$Params['zip']               =        $_SESSION['BILLING_ADDRESS']['postalcode']; 
					$Params['country']           =        $objUser->getCountryName($_SESSION['BILLING_ADDRESS']['country']);
					//$Params['paid_price']      =        $total_price;
					$Params['paid_price']        =        $billing_price; 
					
				} else if($PaymentMethod === 'Authorize.Net' || $PaymentMethod === 'LinkPoint Central' || $PaymentMethod === 'Moneris-eSELECT' || $PaymentMethod === '2CheckOut') {
					
					$Params['firstName']         =        $_SESSION['BILLING_ADDRESS']['fname'];
					$Params['lastName']          =        $_SESSION['BILLING_ADDRESS']['lname']; 
					$Params['company']       	 =        'NIL'; 
					$Params['address1']          =        $_SESSION['BILLING_ADDRESS']['address1']; 
					$Params['address2']          =        $_SESSION['BILLING_ADDRESS']['address2']; 
					$Params['city']              =        $_SESSION['BILLING_ADDRESS']['city'];
					$Params['state']             =        $_SESSION['BILLING_ADDRESS']['state']; 
					$Params['zip']               =        $_SESSION['BILLING_ADDRESS']['postalcode']; 
					$countryRow1				 =		  $objUser->getCountryName($_SESSION['BILLING_ADDRESS']['country']);						
					$Params['country']           =        $countryRow1['country_name'];
					$Params['phone']			 =		  $_SESSION['BILLING_ADDRESS']['telephone'];
					$Params['mail']				 =		  $UserDetails['email'];
					
					$Params['ship_to_first_name']         =        $_SESSION['SHIPPING_ADDRESS']['fname'];
					$Params['ship_to_last_name']       	  =        $_SESSION['SHIPPING_ADDRESS']['lname'];
					$Params['ship_to_company']        	  =        'NIL';
					$Params['ship_to_address1']        	  =        $_SESSION['SHIPPING_ADDRESS']['address1'];
					$Params['ship_to_address2']        	  =        $_SESSION['SHIPPING_ADDRESS']['address2'];
					$Params['ship_to_city']        		  =        $_SESSION['SHIPPING_ADDRESS']['city'];
					$Params['ship_to_state']        	  =        $_SESSION['SHIPPING_ADDRESS']['state'];
					$Params['ship_to_zip'] 		       	  =        $_SESSION['SHIPPING_ADDRESS']['postalcode'];
					$countryRow2						  =		   $objUser->getCountryName($_SESSION['SHIPPING_ADDRESS']['country']);
					$Params['ship_to_country'] 	   		  =        $countryRow2['country_name'];
					
					
					/** Vinoy add the following value
					*/
					//$Params['order_number'] 	   		  =        'sdfdsfds';
					
					
										
					//$Params['paid_price']        =        $total_price;
					$Params['paid_price']        =        $billing_price;
					$Params['creditCard']        =        $_REQUEST['creditCard'];
					$Params['Expiry_Month']      =        $_REQUEST['Expiry_Month'];      
					$Params['Expiry_Year']       =        substr($_REQUEST['Expiry_Year'], -2); 
					$Params['cvc']               =        $_REQUEST['cvc'];
					$Params['tax']               =        $tax;
					$Params['invoice_number']    =        $PaymentObj->getInvoiceNumber();
				}
				
				$Result			=	$PaymentObj->processPaymentRequest($store_name,$Params);

				if($Result['Approved'] == 'No') {
					setMessage($Result['Message']);
					redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=payment&storename=$storename"));
				} else {
					$TransactionId		=	$Result['TransactionId']; 
					if($TransactionId != "") {
						$newcheckout	=	array("payment_transactionid"	=>	$TransactionId);
						$checkout		=	array_merge($checkout,$newcheckout);
					}
					
					$cart->placeOrder($checkout,$store_id);
					 // for create default topic for order note
					if($global['set_order_note'] == 'Y'){
						$last_order_id	=	$cart->getOrderId();
						if($last_order_id>0){
							$orderNumber	=	$cart->orderNumber($last_order_id);
							$table_id = $cart->getTableID('orders'); 
							$_REQUEST[table_id] = $table_id[table_id];
							$_REQUEST[topic_name]=$orderNumber;
							$_REQUEST[file_id]=$last_order_id;
							$_REQUEST[cat_id]=0;
							$_REQUEST[body_html]=$global['order_topic_text'];
							$req = &$_REQUEST;
							$add_dflt_topic= $forum->topicAddEdit($req,$fname,$tmpname,$ord_id,$getID,$table_id);
						}
					}
					
					
					
					$sess_insert_id= $cart->encodeSession();
					### Start saving Pay Invoice to orders_invoice Nov-27-2007 by shinu ###
					if($framework->config['pay_invoice']=="Y")
					{
					$last_order_id	=	$cart->getOrderId();
					$cart->orderInvoice($last_order_id);
					$cart->deleteCartInvoice ();  
					}
					### End saving Pay Invoice to orders_invoice Nov-27-2007 by shinu ###
				    redirect(makeLink(array("mod"=>"cart", "pg"=>"default", "sslval"=>"false", "newurl"=>"true", "storename"=>$_REQUEST['storename'] ), "act=thanks&sess_insert_id=$sess_insert_id"));	
				}
			} # Close if Submit

			//$cart->placeOrder($checkout);
			//redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=thanks"));
			
		} else {
			//No need of payment gateway
			$cart->placeOrder($checkout,$store_id);
			$sess_insert_id= $cart->encodeSession();
			### Start saving Pay Invoice to orders_invoice Nov-27-2007 by shinu ###
			if($framework->config['pay_invoice']=="Y")
			{
				$last_order_id	=	$cart->getOrderId();
				$cart->orderInvoice($last_order_id);
				$cart->deleteCartInvoice ();  
			}
			### End saving Pay Invoice to orders_invoice Nov-27-2007 by shinu ###
			redirect(makeLink(array("mod"=>"cart", "pg"=>"default", "sslval"=>"false", "newurl"=>"true", "storename"=>$_REQUEST['storename'] ), "act=thanks&sess_insert_id=$sess_insert_id"));
		}
		break;
	case "coupon_check":
		$extras = new Extras();
		$rs = $extras->getCoupondetails($_REQUEST['key']);
		echo $rs ? $rs['coupon_amounttype'] : "Coupon Key is invalid. Please try again.";
		exit;
		break;
	case "thanks":
	
		$sess_insert_id=$_REQUEST['sess_insert_id'];
		if($sess_insert_id)
		{
			$cart->decodeSession($sess_insert_id);
		}
		
		$_SESSION['order_contact_me']	=	"";
		$_SESSION['order_notes']		=	"";
		
		if( $_REQUEST["txn_id"] != "" ) {
			$framework->tpl->assign("THANKS", $cart->listMessage( $_REQUEST["txn_id"] ) );
		}else {
			$framework->tpl->assign("THANKS", $cart->listMessage());
		}
		
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/cart/tpl/thanks.tpl");
		break;
		
	case "paypal_process":
	        global $store_id;
	        $fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
									
	        $item_name = $_POST['item_name'];
			$item_number = $_POST['item_number'];
			$payment_status = $_POST['payment_status'];
			$payment_amount = $_POST['mc_gross'];
			$payment_currency = $_POST['mc_currency'];
			$txn_id = $_POST['txn_id'];
			
			$receiver_email = $_POST['receiver_email'];
			$payer_email = $_POST['payer_email'];
			list($memberID,$cmp_id) = explode("-", $item_number);
			
			$CurrentSessId	=	$_REQUEST['CurrentSessId'];
			$cart->decodeSession($CurrentSessId);
			
			if ($payment_status == 'Completed') {
				$checkout			=	array('user_id'				=>		$_SESSION['memberid'], 
		                              'store_id'            =>      $_SESSION['pp_store_id'],
									  'cart_price'			=>		$_SESSION['pp_cart_price'],
									  'shipping_price'		=>		$_SESSION['shipping_price'],
									  'tax'					=>		$_SESSION['pp_tax'],
									  'paytype' 			=>		'Through Paypal',
									  'total_price'			=>		$_SESSION['pp_total_price'],
									  'paid_price'			=>		$payment_amount,
									  'date_ordered'		=>		date("Y-m-d H:i:s"),
									  //'order_type'		    =>	    U,
									  'billing_first_name'	=> 		$_SESSION['BILLING_ADDRESS']['fname'],
									  'billing_last_name'	=> 		$_SESSION['BILLING_ADDRESS']['lname'],
									  'billing_address1'	=> 		$_SESSION['BILLING_ADDRESS']['address1'],
									  'billing_address2'	=> 		$_SESSION['BILLING_ADDRESS']['address2'],
									  //'billing_address3'	=> 		$_SESSION['BILLING_ADDRESS']['address3'],
									  'billing_city'		=> 		$_SESSION['BILLING_ADDRESS']['city'],
									  'billing_state'		=> 		$_SESSION['BILLING_ADDRESS']['state'],
									  'billing_postalcode' 	=> 		$_SESSION['BILLING_ADDRESS']['postalcode'],
									  'billing_country'		=> 		$_SESSION['BILLING_ADDRESS']['country'],
									  'billing_telephone'	=> 		$_SESSION['BILLING_ADDRESS']['telephone'],
									  'billing_mobile'		=> 		$_SESSION['BILLING_ADDRESS']['mobile'],
									  'billing_email'		=> 		$_SESSION['BILLING_ADDRESS']['email'],
									  'shipping_first_name'	=> 		$_SESSION['SHIPPING_ADDRESS']['fname'],
									  'shipping_last_name'	=> 		$_SESSION['SHIPPING_ADDRESS']['lname'],
									  'shipping_address1'	=> 		$_SESSION['SHIPPING_ADDRESS']['address1'],
									  'shipping_address2'	=> 		$_SESSION['SHIPPING_ADDRESS']['address2'],
									  //'shipping_address3'	=> 		$_SESSION['SHIPPING_ADDRESS']['address3'],
									  'shipping_city'		=> 		$_SESSION['SHIPPING_ADDRESS']['city'],
									  'payment_transactionid'=>		$txn_id,
									  'shipping_state'		=> 		$_SESSION['SHIPPING_ADDRESS']['state'],
									  'shipping_postalcode'	=> 		$_SESSION['SHIPPING_ADDRESS']['postalcode'],
									  'shipping_country'	=> 		$_SESSION['SHIPPING_ADDRESS']['country'],
									  'shipping_telephone'	=> 		$_SESSION['SHIPPING_ADDRESS']['telephone'],
									  'shipping_mobile'		=> 		$_SESSION['SHIPPING_ADDRESS']['mobile'],
									  'shipping_method'		=> 		$_SESSION['SHIPPING_ADDRESS']['method'],
									  'contact_me'			=> 		$_SESSION['order_contact_me'],
									  'notes'				=> 		$_SESSION['order_notes'],
									  'shipping_service'	=>		$_SESSION['shipping_service'],
									  'international_order'	=>		$_SESSION['international_order']
									  );
									  $cart->placeOrder($checkout,$_SESSION['pp_store_id']);
			
			}
			
			break;
			
		
			
		case "googleProcess";
		
		global $store_id;	
		
				
		 	
			$data=$HTTP_RAW_POST_DATA;
			$parser->parse($data);
			if($parser->neworder==1){
			$payment_status='Completed';
			}
			$txn_id         = $parser->google_id;
			$payment_amount = $parser->total;
			$CurrentSessId  = $parser->ses_insert_id;
			//mail($email, $subject, $CurrentSessId, "From: $email");
			$cart->decodeSession($CurrentSessId);
			
			if ($payment_status == 'Completed') {
				$checkout			=	array('user_id'				=>$_SESSION['memberid'], 
		                              'store_id'            =>      $_SESSION['pp_store_id'],
									  'cart_price'			=>		$_SESSION['pp_cart_price'],
									  'shipping_price'		=>		$_SESSION['shipping_price'],
									  'tax'					=>		$_SESSION['pp_tax'],
									  'paytype' 			=>		'Through GoogleCheckout',
									  'total_price'			=>		$_SESSION['pp_total_price'],
									  'paid_price'			=>		$payment_amount,
									  'date_ordered'		=>		date("Y-m-d H:i:s"),
									  //'order_type'		    =>	    U,
									  'billing_first_name'	=> 		$_SESSION['BILLING_ADDRESS']['fname'],
									  'billing_last_name'	=> 		$_SESSION['BILLING_ADDRESS']['lname'],
									  'billing_address1'	=> 		$_SESSION['BILLING_ADDRESS']['address1'],
									  'billing_address2'	=> 		$_SESSION['BILLING_ADDRESS']['address2'],
									  //'billing_address3'	=> 		$_SESSION['BILLING_ADDRESS']['address3'],
									  'billing_city'		=> 		$_SESSION['BILLING_ADDRESS']['city'],
									  'billing_state'		=> 		$_SESSION['BILLING_ADDRESS']['state'],
									  'billing_postalcode' 	=> 		$_SESSION['BILLING_ADDRESS']['postalcode'],
									  'billing_country'		=> 		$_SESSION['BILLING_ADDRESS']['country'],
									  'billing_telephone'	=> 		$_SESSION['BILLING_ADDRESS']['telephone'],
									  'billing_mobile'		=> 		$_SESSION['BILLING_ADDRESS']['mobile'],
									  'billing_email'		=> 		$_SESSION['BILLING_ADDRESS']['email'],
									  'shipping_first_name'	=> 		$_SESSION['SHIPPING_ADDRESS']['fname'],
									  'shipping_last_name'	=> 		$_SESSION['SHIPPING_ADDRESS']['lname'],
									  'shipping_address1'	=> 		$_SESSION['SHIPPING_ADDRESS']['address1'],
									  'shipping_address2'	=> 		$_SESSION['SHIPPING_ADDRESS']['address2'],
									  //'shipping_address3'	=> 		$_SESSION['SHIPPING_ADDRESS']['address3'],
									  'shipping_city'		=> 		$_SESSION['SHIPPING_ADDRESS']['city'],
									  'payment_transactionid'=>		$txn_id,
									  'shipping_state'		=> 		$_SESSION['SHIPPING_ADDRESS']['state'],
									  'shipping_postalcode'	=> 		$_SESSION['SHIPPING_ADDRESS']['postalcode'],
									  'shipping_country'	=> 		$_SESSION['SHIPPING_ADDRESS']['country'],
									  'shipping_telephone'	=> 		$_SESSION['SHIPPING_ADDRESS']['telephone'],
									  'shipping_mobile'		=> 		$_SESSION['SHIPPING_ADDRESS']['mobile'],
									  'shipping_method'		=> 		$_SESSION['SHIPPING_ADDRESS']['method'],
									  'contact_me'			=> 		$_SESSION['order_contact_me'],
									  'notes'				=> 		$_SESSION['order_notes'],
									  'shipping_service'	=>		$_SESSION['shipping_service'],
									  'international_order'	=>		$_SESSION['international_order']
									  );
									  $cart->placeOrder($checkout,$_SESSION['pp_store_id']);
			
			}
			
		
			break;
	
}

$framework->tpl->display($global['curr_tpl']."/inner.tpl");

?>

