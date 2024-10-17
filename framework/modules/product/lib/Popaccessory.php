<?php 
/**
 * Newsletter
 *
 * @author sajith
 * @package defaultPackage
 */
session_start();
include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/functions.php");

$objAccessory	=	new Accessory();
$objCategory	=	new Category();
$objProduct		=	new Product();
//echo "Test";
//echo "<pre>";
//print_r($_REQUEST);
//exit;
switch($_REQUEST['act']) {
    case "edit";
	if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			$fname			=	basename($_FILES['image_extension']['name']);
			$ftype			=	$_FILES['image_extension']['type'];
			$tmpname		=	$_FILES['image_extension']['tmp_name'];
			//print_r($req);
			//exit;
			if( ($message 	= 	$objAccessory->modifyAccessory($req,$fname,$tmpname)) === true ) {
				$action = $req['id'] ? "Updated" : "Added";
            	setMessage("Accessory $action Successfully", MSG_SUCCESS);
			}
		}
		# by shinu 4 updated values from session 20-06-07
		$ses_acc_id			=	"0"	;
		$newadjust_weight	=	"0.00";
		$newadjust_price	=	"0.00";
		$newcart_name		=	"";
		$ses_acc_id			=	$_SESSION['acc_id']	;
		$acc_product_id		=	$_SESSION['acc_product_id']	;
		if($ses_acc_id	==$_REQUEST['id'] && $acc_product_id==$_REQUEST['prd_id'])
		{
			$newadjust_weight	=	$_SESSION['adjust_weight'];
			$newadjust_price	=	$_SESSION['adjust_price'];
			$newcart_name		=	$_SESSION['adjust_cartname'];
		}
		
		$framework->tpl->assign("SES_ACCESSORY_ID", $ses_acc_id	);
		$framework->tpl->assign("SES_WEIGHT", $newadjust_weight);
		$framework->tpl->assign("SES_PRICE", $newadjust_price);
		$framework->tpl->assign("SES_CART", $newcart_name);
		# by shinu 4 updated values from session 20-06-07
		
		
		$framework->tpl->assign("ACCESSORY", $objAccessory->GetAccessory($_REQUEST['id']));
		$framework->tpl->assign("PRODUCTS_ACCESSORY", $objAccessory->GetProductAccessoryGroup($_REQUEST['id'],$_REQUEST['prd_id']));
			
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/popAccessory.tpl");
		break;
	
	case "editColor";	
	
	if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			$objAccessory->modifyColor($req);
			
			if( ($message 	= 	$objAccessory->modifyColor($req)) === true ) {
				$action = $req['id'] ? "Updated" : "Added";
            	setMessage("Accessory Color $action Successfully!Only after refreshing display will be changed", MSG_SUCCESS);
			}
		}

	
	$framework->tpl->assign("ACCESSORY", $objAccessory->GetAccessory($_REQUEST['id']));
	$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/popAccessoryColor.tpl");
}
$framework->tpl->display($global['curr_tpl']."/adminPopup.tpl");

?>