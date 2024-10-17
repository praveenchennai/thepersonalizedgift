<?php 
/**
 * Newsletter
 *
 * @author sajith
 * @package defaultPackage
 */
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
$objProduct		=	new Product();
switch($_REQUEST['act']) {
    case "PopUp";
		$image=$_REQUEST['image'];
		$from=$_REQUEST['from'];
		$id=explode("_",$image);
		if(!empty($from))
			$from .= "/";
		$ext=$_REQUEST['ext'];
		$status=$_REQUEST['status'];
		
		if (is_numeric($image)){
			$framework->tpl->assign("PRODUCT_DETAILS", $objProduct->ProductGet($image));
		}
		else {
		$framework->tpl->assign("PRODUCT_DETAILS", $objProduct->ProductGet($id[1]));
		}
		$framework->tpl->assign("IMAGE", $image);
		$framework->tpl->assign("EXT", $ext);
		$framework->tpl->assign("FROM", $from);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/popImage.tpl");
    break;
	case "newPopup":
		$image_name	=	$_REQUEST['image_name'];
		$rear_image_name	=	$_REQUEST['rear_image_name'];
		$framework->tpl->assign("IMAGE", $image_name);
		$framework->tpl->assign("REAR_IMAGE", $rear_image_name);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/savePopImage.tpl");
	break;
	case "cartPopup":
		$image_name	=	$_REQUEST['image_name'];
		$framework->tpl->assign("IMAGE", $image_name);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/cart/tpl/cartPopImage.tpl");
	break;
	case "buyPop":
		$image		=	$_REQUEST['image'];
		$framework->tpl->assign("IMAGE", $image);
		$framework->tpl->assign("EXT", 'jpg');
		$framework->tpl->assign("FROM", 'saved_work/');	
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/buyPop.tpl");	
	break;
		
}

$framework->tpl->display($global['curr_tpl']."/userPopup.tpl");

?>