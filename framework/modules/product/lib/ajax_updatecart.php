<?php 
/**
 * Calulate the total amount using ajaxt techonology in product details page
 *
 * @author Nirmal
 * @package defaultPackage
 
 */
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.combination.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.price.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.made.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");
include_once(FRAMEWORK_PATH."/modules/cart/lib/class.cart.php");

$email			= 	new Email();
$objCategory	=	new Category();
$objProduct		=	new Product();
$objPrice		=	new Price();
$objCombination	=	new Combination();
$objMade		=	new Made();
$objAccessory	=	new Accessory();
$cart 			= 	new Cart();



$storeArr = $objStore->storeGetByName($_REQUEST['storename']);
		$_REQUEST['store_id'] = $storeArr['id'];				
				$status	=	$objCombination->ValidateMandatory($_REQUEST);
				if($status['status']==true){
					if($objProduct->ValidateGiftCertificate($_REQUEST['product_id'],$_REQUEST['price'])){
						$accessory_status	=	$objAccessory->Validate_Accessory_Excluded($_REQUEST);
						if($accessory_status['status']==true){
								$_REQUEST['user_id'] = $_SESSION['memberid'];
								
								$cartstatus=$cart->updateCartData($_REQUEST);
								if (($global["cart_restricted_amt"] == "Y") AND ($cartstatus=="false")){
									echo "location.href='".makeLink(array("mod"=>"cart", "pg"=>"default"), "act=view&status=false")."';";
								}else{
									echo "location.href='".makeLink(array("mod"=>"cart", "pg"=>"default"), "act=view")."';";
								}
							}else{
								echo "document.getElementById('message').innerHTML = '".$accessory_status['message']."';";
								echo "height = document.getElementById('message').clientHeight;";
								echo "height = height+10;";
								echo "document.getElementById('show_message').style.height = height+'px';";
								echo "document.getElementById('show_message').style.overflow ='visible';";
								echo "document.getElementById('add_to_cart').style.display='inline';";
								echo "document.getElementById('addtocart').style.display='none';";
								echo "window.location='#error_message';";
							}
						}else{
							echo "document.getElementById('message').innerHTML = 'Please Specify The Amoount for ".$objProduct->GetProductName($_REQUEST['product_id'])."';";
							echo "height = document.getElementById('message').clientHeight;";
							echo "height = height+10;";
							echo "document.getElementById('show_message').style.height = height+'px';";
							echo "document.getElementById('show_message').style.overflow ='visible';";
							echo "document.getElementById('add_to_cart').style.display='inline';";
							echo "document.getElementById('addtocart').style.display='none';";
							echo "window.location='#error_message';";
						}
				} else {
					echo "document.getElementById('message').innerHTML = '".$status['message']."';";
					echo "height = document.getElementById('message').clientHeight;";
					echo "height = height+10;";
					echo "document.getElementById('show_message').style.height = height+'px';";
					echo "document.getElementById('show_message').style.overflow ='visible';";
					echo "document.getElementById('add_to_cart').style.display='inline';";
					echo "document.getElementById('addtocart').style.display='none';";
					echo "window.location='#error_message';";
				}
exit;
?>