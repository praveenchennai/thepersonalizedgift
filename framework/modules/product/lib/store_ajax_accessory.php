<?
session_start();
$_SESSION['sess_grp_array']="";
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.combination.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.price.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.made.php");
$objProduct		=	new Product();
$objCategory	=	new Category();
$objAccessory	=	new Accessory();
$objCombination	=	new Combination();
$objPrice		=	new Price();
$objMade		=	new Made();
$grpid			=	$_REQUEST["grpid"] 			? $_REQUEST["grpid"] 				: "";
$category_id	=	$_REQUEST["category_id"] 	? $_REQUEST["category_id"] 			: "";
$product_id 	= 	$_REQUEST["product_id"] 	? $_REQUEST["product_id"] 			: "0";
$store 			= 	$_REQUEST["store"] 			? $_REQUEST["store"] 				: "0";
echo "$grpid|";
if($category_id && $category_id>0)
{
$content		=	$objProduct->DisplayAccessoriesForStores($grpid,$category_id,$product_id,$store);
echo "$content";
}
else
{
echo " ";
}
echo "|$store";

exit;
?>