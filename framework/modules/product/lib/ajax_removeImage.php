<?
session_start();
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
$objProduct		=	new Product();
$field			=	$_REQUEST["field"] 				? $_REQUEST["field"] 				: "";
$product_id		=	$_REQUEST["product_id"] 		? $_REQUEST["product_id"] 				: "0";
$content		=	$objProduct->RemoveImage($product_id,$field);
if($content==true)
echo "0|".$field;
else
echo "1|".$field;
exit;
?>