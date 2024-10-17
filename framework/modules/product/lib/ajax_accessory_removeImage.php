<?
session_start();
include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php"); 
$objAccessory		=	new Accessory();
$field			=	$_REQUEST["field"] 	? $_REQUEST["field"] 	: "";
$extn			=	$_REQUEST["extn"] 	? $_REQUEST["extn"] 	: "";
$accessory_id		=	$_REQUEST["accessory_id"] ? $_REQUEST["accessory_id"] : "0";
$content		=	$objAccessory->RemoveImage($accessory_id,$field,$extn);
if($content==true)
echo "0|".$field;
else
echo "1|".$field;
exit;
?>