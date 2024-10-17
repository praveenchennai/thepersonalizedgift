<?php 
/**
 * Product
 *
 * @author adarsh
 * @package defaultPackage
 */



include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");
$objAccessory	=	new Accessory();

switch($_REQUEST['act']) {
   
       case "preview":
	
		$ac_value = $objAccessory->GetAccessory($_REQUEST['id']);
		
		$framework->tpl->assign("ACCESSORY",$ac_value);
	
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/art_preview.tpl");
        break;
		
		case "preview_new":
		$ac_value = $objAccessory->GetAccessory($_REQUEST['id']);
		
		$framework->tpl->assign("ACCESSORY",$ac_value);
	
        echo $framework->tpl->display(FRAMEWORK_PATH."/modules/product/tpl/art_preview_new.tpl");
		exit;
        break;
		
		case "preview_view":
			$ac_value = $objAccessory->GetAccessory($_REQUEST['id']);
			//echo "<img src='".SITE_URL."/modules/product/images/accessory/".$ac_value["id"].".".$ac_value["image_extension"]."'>";
			
			if (file_exists(SITE_PATH."/modules/product/images/accessory/".$ac_value["id"].".".$ac_value["image_extension"]))			
				echo SITE_URL."/modules/product/images/accessory/".$ac_value["id"].".".$ac_value["image_extension"]."|".$_REQUEST['key'];
			else
				echo SITE_URL."/modules/product/images/accessory/no-preview.gif";
		  exit;
		
		break;
		
		case "img_view":
	
			$framework->tpl->assign("IMG_ID",$_REQUEST['id']);
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/pgift_img_view.tpl");
        break;
		case "img_view_new":
			$framework->tpl->assign("IMG_ID",$_REQUEST['id']);
			echo $framework->tpl->display(FRAMEWORK_PATH."/modules/product/tpl/pgift_img_view_new.tpl");
			exit;
        break;

		
}
$framework->tpl->display($global['curr_tpl']."/adminPopup.tpl");

?>