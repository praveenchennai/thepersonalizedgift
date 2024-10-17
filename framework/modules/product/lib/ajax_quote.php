<?php 
/**
 * Calulate the total amount using ajaxt techonology in product details page
 *
 * @author Nirmal
 * @package defaultPackage
 */

include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.combination.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.price.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.made.php");
$objProduct	=	new Product();

//print_r($_REQUEST); 
//exit;

echo "document.getElementById('total_price').innerHTML = '<strong>$".number_format($objProduct->paddlePrice($_REQUEST))."</strong>';";

$paddlePrice	=	$objProduct->getProductPrice($_REQUEST['product_id']);
echo "document.getElementById('paddle_price').innerHTML = '$$paddlePrice';";

$arr=$objProduct->Getoptions($_REQUEST);
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
}
exit;
?>