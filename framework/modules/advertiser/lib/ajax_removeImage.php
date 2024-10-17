<?
session_start();
include_once(FRAMEWORK_PATH."/modules/advertiser/lib/class.advertiser.php");
$objAdvertiser	=	new Advertiser();
$field			=	$_REQUEST["field"] 				? $_REQUEST["field"] 				: "";
$adv_id			=	$_REQUEST["adv_id"] 		? $_REQUEST["adv_id"] 				: "0";
$content		=	$objAdvertiser->RemoveImage($adv_id,$field);
if($content==true)
echo "0|".$field;
else
echo "1|".$field;
exit;
?>