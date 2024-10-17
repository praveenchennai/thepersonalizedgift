<?php 
/**
 * Store Indexpage
 *
 * @author ajith
 * @package defaultPackage
 */

include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.payment.php");
require_once FRAMEWORK_PATH."/modules/order/lib/paymentconfig.php";

$store					= 	new Store();	
$objUser				=	new User();
$admin                  =   new Admin();
$objPayment			=	new Payment();
$storeDetails			=	$store->storeGetByName($_REQUEST['storename']);	
$store_id				=	$storeDetails['id'];

authorize_store();
include_once(FRAMEWORK_PATH."/modules/store/lib/class.storelogin.php");
$act	=	$_REQUEST['act'];
if($global['payment_receiver']=='store')
{
	$StoreId				=	$objPayment->getStoreIdFromStoreName($_REQUEST['storename']);
	$PayMethodId	=	$objPayment->getPaymentMethod($StoreId, $objPayment->config['payment_receiver']);
	//added by adarsh for change the message at the top
	$objPayment->getStorePaymentMsg($store_id);
		
	if(!$PayMethodId)
	{
		if( $storeDetails['active'] =='N')
		{
			//$msg="Your store is currently In-active. Please select one template, set up your Payment Gateway and Shipping information for your stores.";
			//$_SESSION[storeSess][0]->statusMsg=$msg;
			redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>"css"), "act=list&id=".$req['menu_id']."&section_id=".$req['section_id']."&sId=Payment Type"));
					}else
		{
			//$msg="Your store is currently Active. Please set up your  Shipping information for your stores.";
			//$_SESSION[storeSess][0]->statusMsg=$msg;
			redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>"order_paymentType"), "act=type&sId=Payment Type&fId=".$_REQUEST['fId']));
		}
	
	
	}
	
}
if($act==''){	

 		include_once(SITE_PATH."/includes/areaedit/include.php");
        if($_SERVER['REQUEST_METHOD'] == "POST") {
           $req = &$_REQUEST;
		    if( ($message = $store->createStoreContent($req)) === true ) {
				$action = $store_id ? "Updated" : "Added";
            	setMessage("Store Content $action Successfully", MSG_SUCCESS);
               // redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>"menu"), "act=list&id=".$req['menu_id']."&section_id=".$req['section_id']));
            }else{
           	 setMessage($message);	
			}		
        }
        editorInit('content');
        if($message) {           
            $_POST['description'] = stripslashes($_POST['content']);
            $framework->tpl->assign("STORE_CONTENT", $_POST);
        } elseif($store_id) {
            $framework->tpl->assign("STORE_CONTENT", $store->storeGet($store_id));
        }        
           $uid= $_SESSION['storeSess'][0]->id;
		 
	
	 	if($store->getUserDetails($uid))
		{
		
			redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>"storeIndex"), "act=user_form&id=".$req['menu_id']."&section_id=".$req['section_id']));
			// $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/store/tpl/store_admin_form.tpl");
		}else
		{
			
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/store/tpl/store_content.tpl");
		}			
      
		
		
}elseif($act=='user_form')
{	
	 if($_SERVER['REQUEST_METHOD'] == "POST") {
            $req = &$_REQUEST;
				$arr["username"]=$_REQUEST["username"];
				$arr["password"]=$_REQUEST["password"];
				$arr["email"]=$_REQUEST["email"];
				$arr["first_name"]=$_REQUEST["name"];
				$arr["active"]="Y";
				$arr["id"]=$_SESSION['storeSess'][0]->id;
				
				$objUser->setArrData($arr);
            	$myId=$objUser->update();
				setMessage($objUser->getErr());
              redirect(makeLink(array("mod"=>"store", "pg"=>"storeIndex"), "act=list"));
           
            setMessage($message);
        }
	 $framework->tpl->assign("ADMIN", $admin->adminGet($_SESSION['storeSess'][0]->id));
	 $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/store/tpl/store_admin_form.tpl");
}elseif($act=='list')
{	
	 $framework->tpl->assign("ADMIN", $admin->adminGet($_SESSION['storeSess'][0]->id));
	 $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/store/tpl/store_admin_list.tpl");
}


//echo "<pre>";
//print_r($_SESSION['storeSess']);

//echo "<pre>";


$framework->tpl->display($global['curr_tpl']."/store.tpl");
?>
