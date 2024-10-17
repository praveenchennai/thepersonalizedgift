<?php 

/**
 * Module :: Product
 *
 * @author Nirmal K R
 * @package Framework
 **/
 
session_start();
include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/functions.php");

$objAccessory	=	new Accessory();
$objCategory	=	new Category();
$objProduct		=	new Product();
 
switch($_REQUEST['act']) {
    case "form";
		if($_REQUEST['Submit']	==	'Submit') {
			$store_id								=	$_REQUEST['store_id'];
			if(isset($_SESSION['StoreAccessories'][$store_id])) # In case already entered the accessory information unset the session variable
				unset($_SESSION['StoreAccessories'][$store_id]);
			if(count($_REQUEST['accessory']) > 0) # If at least one accessory selected then only session is set
				$_SESSION['StoreAccessories'][$store_id]	=	$_REQUEST['accessory'];
			print "<script>self.close();</script>";
		}
		//echo "<pre>";
		//print_r($_SESSION['StoreAccessories']);
		if($_REQUEST['product_id'] != 0) {
			$grp_arr=$objCategory->GetProductCategory($_REQUEST['product_id']);
			if(count($grp_arr)>0) {
				$framework->tpl->assign("GROUP_ACC", $grp_arr);
			}
		}
		$framework->tpl->assign("GROUP", $objProduct->AllProductGroups('N'));
		
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/popAccessory.tpl");
		break;
}
$framework->tpl->display($global['curr_tpl']."/adminPopup.tpl");

?>