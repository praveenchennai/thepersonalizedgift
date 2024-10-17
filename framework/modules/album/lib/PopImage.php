<?php 
/**
 * Newsletter
 *
 * @author sajith
 * @package defaultPackage
 */
include_once(FRAMEWORK_PATH."/modules/album/lib/class.photo.php");
$objPhoto    = new Photo();
switch($_REQUEST['act']) {
    case "PopUp";
	if($_REQUEST["type"]){
		$type=$_REQUEST["type"];
		$tbname=$_REQUEST["tbname"];
	}else{
		$type="photo";
		$tbname="album_photos";
	}
	$photo_id=$_REQUEST['photo_id'];
	$phdet = $objPhoto->getPhotoDetails($photo_id,$tbname,$type);
	$framework->tpl->assign("PHDET", $phdet);
	$framework->tpl->assign("photo_id", $photo_id);
	//print_r($phdet);exit;
	if($type=="photo"){
		 $framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/PopImage.tpl");
	}else{
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/PopImageProduct.tpl");
	}
        break;
}
$framework->tpl->display($global['curr_tpl']."/userPopup.tpl");

?>