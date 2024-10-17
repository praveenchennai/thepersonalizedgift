<?
session_start();
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
$objProduct		=	new Product();

$store_id		=	$_REQUEST["store_id"] 			? $_REQUEST["store_id"] 				: "0";
$product_id		=	$_REQUEST["product_id"] 		? $_REQUEST["product_id"] 				: "0";
echo "$store_id|";
$content		=	$objProduct->DisplayStoreAccessory($store_id,$product_id);
echo "gkdhgdlfgdohgdfgudfg";

exit;
?>