<?php 
/**
 * Calulate the total amount using ajaxt techonology in Orderform2  page
 *
 * @author Jipson.
 * @package defaultPackage
 */

include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.combination.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.price.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.made.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/cart/lib/class.cart.php");

$user                        =   new User();
$objProduct	                 =	 new Product();
$cart 			             = 	 new Cart();

//$UserDetails=$user->getUserdetails($_SESSION['memberid']);
//$country=$user["country"];
//$state=$user["state"];		
//$tax=$cart->CalculateTax($country,$state,false,$storename);
//if(empty($tax))
			//$tax=0;
$udet = $user->getUserDetails($_SESSION["memberid"]);
		
		$country=$udet["country"];
		$state=$udet["state"];
		$tax	=	$cart->CalculateTax($country,$state,false,$storename);
		if(empty($tax))
			$tax=0;
			$httal=number_format($objProduct->calulate_price($_REQUEST)*$_REQUEST['qty'], 2);
			$gt=$httal+($httal*$tax/100);

//print_r($_REQUEST);
//exit;
if($_REQUEST["height"] && $_REQUEST["width"]){
	if($httal<$global["minimum_price"])
		$httal=$global["minimum_price"];
	
	echo "document.getElementById('total_price').innerHTML ='<strong>Sticker Price  : $".number_format($httal, 2)."</strong>';";
}else{
	echo "document.getElementById('total_price').innerHTML ='<strong>Sticker Price  : $ 0</strong>';";
}
echo "document.orderForm.hidtotal.value= '".number_format($objProduct->calulate_price($_REQUEST)*$_REQUEST['qty'], 2)."';";
echo "document.orderForm.taxhid.value=$tax;";
echo "document.orderForm.gtothid.value=$gt;";
exit;
?>