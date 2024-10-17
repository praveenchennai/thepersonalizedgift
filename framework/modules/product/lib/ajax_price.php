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
$objAccessory	=	new Accessory();

//print_r($_REQUEST);
//exit;
$accessory_status	=	$objAccessory->Validate_Accessory_Excluded($_REQUEST);

if($accessory_status['status']==true)	{

	if ($_REQUEST['Ctid']) {
	$div_msg	=	'div_exc_' . $_REQUEST['Ctid'];
	echo "document.getElementById('$div_msg').innerHTML = '<strong>". '' ."</strong>';";
	}

echo "document.getElementById('total_price').innerHTML = 'Including Options : <strong>$".number_format($objProduct->calulate_price($_REQUEST)*$_REQUEST['qty'], 2)."</strong>';";
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
	$display_name=$arr[$i]['display_name'] ? $arr[$i]['display_name']: $arr[$i]['name'];
	echo "<tr>
    <td class='bodytext'><div align='left'>".$arr[$i]['category_name'].":".$display_name."</div></td>
    <td class='bodytext'><div align='right'>".$arr[$i]['price']."</div></td>
	<td width='10'>&nbsp;</td>
  	</tr>";

	}
if($arr['monogram'] && $arr['monogram_price'])
	{
	$display_name=$arr[$i]['display_name'] ? $arr[$i]['display_name']: $arr[$i]['name'];
	echo "<tr>
    <td class='bodytext'><div align='left'>".$arr[$i]['monogram'].":".$display_name."</div></td>
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

}	else	{

	if ($_REQUEST['Ctid']) {
		$Ctid	=	$_REQUEST['Ctid']; //$_REQUEST['Ctid'];alert(document.getElementById('$Ctid').length) div_err_exclude
		$Ctname	=	$_REQUEST['Ctname']; //$_REQUEST['Ctid'];alert(document.getElementById('$Ctid').length) div_err_exclude
		$Err	=	$accessory_status['message'];
		$div_msg	=	'div_exc_' . $Ctid;
		
		$acc_Id	=	explode ( "_" , $Ctid );
		$acc_Id	= 	$acc_Id[1];
		unset ($_REQUEST["access"][$acc_Id]);
	
		
		echo "if(document.getElementById('$Ctid').type=='select-one')";
		echo "document.getElementById('$Ctid').options[0].selected=true;"; 
		
		
		echo "if(document.getElementById('$Ctid').type=='text')";
		echo "document.getElementById('$Ctid').value='';"; 
		
		echo "document.getElementById('$div_msg').innerHTML = '<strong><font color=red>". $Err ."</font></strong>';";
		echo "document.getElementById('total_price').innerHTML = 'Including Options : <strong>$".number_format($objProduct->calulate_price($_REQUEST)*$_REQUEST['qty'], 2)."</strong>';";
		
		
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
			$display_name=$arr[$i]['display_name'] ? $arr[$i]['display_name']: $arr[$i]['name'];
			echo "<tr>
			<td class='bodytext'><div align='left'>".$arr[$i]['category_name'].":".$display_name."</div></td>
			<td class='bodytext'><div align='right'>".$arr[$i]['price']."</div></td>
			<td width='10'>&nbsp;</td>
			</tr>";
		
			}
		if($arr['monogram'] && $arr['monogram_price'])
			{
			$display_name=$arr[$i]['display_name'] ? $arr[$i]['display_name']: $arr[$i]['name'];
			echo "<tr>
			<td class='bodytext'><div align='left'>".$arr[$i]['monogram'].":".$display_name."</div></td>
			<td class='bodytext'><div align='right'>$ ".$arr[$i]['monogram_price']."</div></td>
			<td width='10'>&nbsp;</td>
			</tr>";
			}
		echo '</table>';
		
		}
			
			
	} 
}

exit;
?>