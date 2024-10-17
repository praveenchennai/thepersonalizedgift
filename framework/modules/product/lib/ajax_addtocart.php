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
						
				//$status	=	$objAccessory->ValidateTheSelectionOfAccessory($_REQUEST);
				$status	=	$objCombination->ValidateMandatory($_REQUEST);
				//$objCombination->ValidateMandatory($_REQUEST); 
				
				if($status['status']==true) {
				if($objProduct->ValidateGiftCertificate($_REQUEST['product_id'],$_REQUEST['price']))
						{
						//print_r($_REQUEST);
						
						//die("es");
						$accessory_status	=	$objAccessory->Validate_Accessory_Excluded($_REQUEST);
						//echo "access<br>";
						//print_r($accessory_status);
						//exit;
						if($accessory_status['status']==true)
								{
								$_REQUEST['user_id'] = $_SESSION['memberid'];
								
								
								$cartstatus=$cart->addToCart($_REQUEST);
								//$cart->addToCartTracking($_REQUEST);
								//redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=view&status=false"));
								if (($global["cart_restricted_amt"] == "Y") AND ($cartstatus=="false")){
								
									echo "location.href='".makeLink(array("mod"=>"cart", "pg"=>"default"), "act=view&status=false")."';";
								}else{
									echo "location.href='".makeLink(array("mod"=>"cart", "pg"=>"default"), "act=view")."';";
								}
								}
							else
								{
								//setMessage($accessory_status['message']);
								echo "document.getElementById('message').innerHTML = '".$accessory_status['message']."';";
								echo "height = document.getElementById('message').clientHeight;";
								echo "height = height+10;";
								echo "document.getElementById('show_message').style.height = height+'px';";
								echo "document.getElementById('show_message').style.overflow ='visible';";
								echo "document.getElementById('add_to_cart').style.display='inline';";
								echo "document.getElementById('addtocart').style.display='none';";
								echo "window.location='#error_message';";
								}
						}
					else
						{
						//setMessage('Please Specify The Amoount for '.$objProduct->GetProductName($_REQUEST['product_id']));
						//redirect(makeLink(array("mod"=>"product", "pg"=>"list"), "act=desc&id=".$_REQUEST['product_id']));
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
					//setMessage($status['message']);
					//echo "alert(".$status['message'].")";
					echo "document.getElementById('message').innerHTML = '".$status['message']."';";
					echo "height = document.getElementById('message').clientHeight;";
					echo "height = height+10;";
					echo "document.getElementById('show_message').style.height = height+'px';";
					echo "document.getElementById('show_message').style.overflow ='visible';";
					echo "document.getElementById('add_to_cart').style.display='inline';";
					echo "document.getElementById('addtocart').style.display='none';";
					echo "window.location='#error_message';";
					//redirect(makeLink(array("mod"=>"product", "pg"=>"list"), "act=desc&id=".$_REQUEST['product_id']));
				}


exit;
?>