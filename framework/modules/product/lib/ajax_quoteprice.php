<?php 
/**
 * Calulate the total amount using ajaxt techonology in instantquote and orderform2 pages of calsilkscreen.
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
include_once(FRAMEWORK_PATH."/modules/cart/lib/class.cart.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.order.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");

$user           =   new User();
$oder			=	new Order();
$objProduct	    =	new Product();
$cart 			= 	new Cart();

//print_r($_REQUEST); 
//exit;$objProduct->paddlePrice($_REQUEST)



//echo "document.getElementById('total_price').innerHTML = '<strong>$".number_format($re)."</strong>';";
// works  on 19/07/07//////////////////////////////////////////////////
//$cmyk2=;
$cm=$_REQUEST["pag"];
switch($cm){
	case "orderform":
		$qt=$_REQUEST["qty"];
		$wd=$_REQUEST["width"];
		$ht=$_REQUEST["height"];
		$shape=$_REQUEST["shape"];
		$punch=$_REQUEST["punch"];
		$kisscut=$_REQUEST["kisscut"];
		$re=($wd*$ht*$qt*2)/100;
		if($shape=="cust"){
			if($re!=0){
				$re=$re+((30/1000)*$qt);
			//	$re=$re+5;
			}
		}
		if($punch){
			if($re!=0){
				$re=$re+(((15/1000)*$qt)*$punch);
			}
		}
		if($kisscut){
			if($re!=0){
				$re=$re+(((15/1000)*$qt)*$kisscut);
			}
		}
		if($_REQUEST["cmyk2"]==1){
			if($re!=0){
				$re=$re+5;
			}
		}
		if($_REQUEST["art_file2"]==1){
			if($re!=0){
				$re=$re+5;
			}
		}
		if($_REQUEST["resolution2"]==1){
			if($re!=0){
				$re=$re+5;
			}
		}
		if($_REQUEST["cutline2"]==1){
			if($re!=0){
				$re=$re+5;
			}
		}
		if($_REQUEST["bleed2"]==1){
			if($re!=0){
				$re=$re+5;
			}
		}
		if($_REQUEST["border2"]==1){
			if($re!=0){
				$re=$re+5;
			}
		}
		if($_REQUEST["safety_margin2"]==1){
			if($re!=0){
				$re=$re+5;
			}
		}
		$udet = $user->getUserDetails($_SESSION["memberid"]);
		
		$country=$udet["country"];
		$state=$udet["state"];
		$tax	=	$cart->CalculateTax($country,$state,false,$storename);
		if(empty($tax))
			$tax=0;
			if($tax!=0){
				$taxamt=($re)*($tax/100);
				$gtotal=$re+($re)*($tax/100);

			}else{
				$gtotal=$re;
				$taxamt=0;
				
			}
		$gtotal=$re+$taxamt;
		//if($_REQUEST["pg"]=='orderform'){
			
			echo"document.orderForm.tax.value= '".number_format($tax)."%';";
			echo"document.orderForm.subtotal.value= '$".number_format($re)."';";
			echo"document.orderForm.total.value= '$".number_format($gtotal)."';";
	break;
	case "orderform3":
			$country=$_REQUEST["country"];
			$state=$_REQUEST["state"];
			$shipping_service	=	$_REQUEST['shipping_service'];
			$ShipSrvcArr		=	explode('*^*',$shipping_service);
			$_SESSION['shipping_price']		    =	 $ShipSrvcArr[2];
			$_SESSION['shipping_service'] 		=	 $ShipSrvcArr[0];
			
			//$id=$_REQUEST["oid"];
			//$oddetail=$oder->getCartPrice($id);
			$shipweight=0;
			$rs = $cart->getCartBox();
			$rt=$cart->getCart();
			$tot=$rt["productTotal"];
			$atot=$rt["AccessoryTotal"];
			foreach ($rt["records"] as $ry){
				$shipweight=$shipweight+$ry->shipping_weight;
			}
			/*if($_SESSION['shipping_price']){
				$ship_price=round(($shipweight*$_SESSION['shipping_price'])+5,2);
				$_SESSION['shipping_price']=$ship_price;
			}*/
			$cart_price 		= 	$rs->total_price;
			if($_SESSION["memberid"]){
				$udet = $user->getUserDetails($_SESSION["memberid"]);
				if($udet["sp_discount"]){
					$disc=$udet["sp_discount"];
				}else{
					$disc=1;
				}
				//$cart_price =($cart_price * $disc);
				$cart_price =($tot * $disc)+$atot;
				//$cart_price =($atot);
				//$cart_price =$atot;
				//
			}
			$tax	=	$cart->CalculateTax($country,$state,false,$storename);
			if(empty($tax))
			$tax=0;
			if($_REQUEST["tax_exemption"])
				$tax=0;
			$total_price		= 	round(($cart_price+$_SESSION['shipping_price']) * (100 + $tax)/100, 2);
			$tax_price			=	round(($cart_price+$_SESSION['shipping_price']) * $tax/100, 2);	
			//$total_price		= 	round(($cart_price+$shipping_price) * (100 + $tax)/100, 2);
			$subtotal=$cart_price;
			
			
			echo"document.frmOrderform.tax.value= '$".number_format($tax_price)."';";
			echo"document.frmOrderform.subtotal.value= '$".number_format($subtotal)."';";
			echo"document.frmOrderform.total.value= '$".number_format($total_price)."';";
			echo"document.frmOrderform.shipping.value= '$".number_format($_SESSION['shipping_price'])."';";

	break;
	case "quote":
		$qt=$_REQUEST["qty"];
		$wd=$_REQUEST["width"];
		$ht=$_REQUEST["height"];
		$shape=$_REQUEST["shape"];
		$punch=$_REQUEST["punch"];
		$kisscut=$_REQUEST["kisscut"];
		$re=($wd*$ht*$qt*2)/100;
		if($shape=="cust"){
			if($re!=0){
				$re=$re+((30/1000)*$qt);
				//$re=$re+5;
			}
		}
		if($punch){
			if($re!=0){
				$re=$re+(((15/1000)*$qt)*$punch);
			}
		}
		if($kisscut){
			if($re!=0){
				$re=$re+(((15/1000)*$qt)*$kisscut);
			}
		}
		if($re<$global["minimum_price"]){
			$re=$global["minimum_price"];
		}
		echo"document.getElementById('result').innerHTML = '$".number_format($re)."';";
	break;
	

}

//}
// works  on 19/07/07//////////////////////////////////////////////////

//$paddlePrice	=	$objProduct->getProductPrice($_REQUEST['product_id']);
//if($_REQUEST["pg"]=="quote"){
	//
//}
//echo "document.getElementById('paddle_price').innerHTML = '$$paddlePrice';";

/*$arr=$objProduct->Getoptions($_REQUEST);
echo "|";
if(count($arr)>0)
{
echo "<table width='100%'  border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td class='bodytext'><strong>Option(s)</strong></td>
    <td class='bodytext'><div align='right'><strong>Price</strong></div></td>
	<td width='15'>&nbsp;</td>  
  </tr>";
for($i=0;$i<count($arr);$i++)
	{
	echo "<tr>
    <td class='bodytext'><div align='left'>".$arr[$i]['category_name'].":".$arr[$i]['name']."</div></td>
    <td class='bodytext'><div align='right'>".$arr[$i]['price']."</div></td>
	<td width='10'>&nbsp;</td>
  	</tr>";

	}
if($arr['monogram'] && $arr['monogram_price'])
	{
	echo "<tr>
    <td class='bodytext'><div align='left'>".$arr[$i]['monogram'].":".$arr[$i]['name']."</div></td>
    <td class='bodytext'><div align='right'>$ ".$arr[$i]['monogram_price']."</div></td>
	<td width='10'>&nbsp;</td>
  	</tr>";
	}
echo '</table>';
}
else
{
echo '<table><tr><td class="bodytext">No Options Selected</td><td width="10">&nbsp;</td></tr></table>';
}*/
exit;
?>