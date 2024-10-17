<?php
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");

$product = new Product();
$objAccessory	=	new Accessory();
$objCategory	=	new Category();

switch($_REQUEST['act']) {

	case "add_frame":
	$base_price = $product->getProductPrice($_REQUEST["product_id"]);
	$framework->tpl->assign("BASE_PRICE",$base_price);
	$prd_det = $product->ProductGet($_REQUEST["product_id"]);
	$names = $prd_det["x_co"];
	$image_name = $_REQUEST["PHPSESSID"].date("Y-m-d G:i:s");
	
	$image_name = md5($image_name);
	$framework->tpl->assign("IMG_NAME",$image_name);
	
	$framework->tpl->assign("PRD_DET",$prd_det);
	//print_r($prd_det);
	$framework->tpl->assign("NAMES",$names);


	list($rs, $numpad, $cnt, $limitList)	= 	$objAccessory->listAllAccessories($pageNo,$limit,$param,OBJECT, $orderBy,174,$store_id);
	$framework->tpl->assign("ACCESSORY",$rs);//print_r($rs);

	$framework->tpl->assign("main_tpl", SITE_PATH."/modules/ajax_editor/tpl/editor_add_frame.tpl");	
	$framework->tpl->display($global['curr_tpl']."/inneredit.tpl");
	break;
	case "add_text":
	$base_price = $product->getProductPrice($_REQUEST["product_id"]);
	$framework->tpl->assign("BASE_PRICE",$base_price);
	$prd_det = $product->ProductGet($_REQUEST["product_id"]);
	$names = $prd_det["x_co"];
	$image_name = $_REQUEST["PHPSESSID"].date("Y-m-d G:i:s");
	
	$image_name = md5($image_name);
	$framework->tpl->assign("IMG_NAME",$image_name);
	
	$framework->tpl->assign("PRD_DET",$prd_det);
	//print_r($prd_det);
	$framework->tpl->assign("NAMES",$names);


	list($rs, $numpad, $cnt, $limitList)	= 	$objAccessory->listAllAccessories($pageNo,$limit,$param,OBJECT, $orderBy,174,$store_id);
	$framework->tpl->assign("ACCESSORY",$rs);//print_r($rs);

	$framework->tpl->assign("main_tpl", SITE_PATH."/modules/ajax_editor/tpl/editor_per_text.tpl");	
	$framework->tpl->display($global['curr_tpl']."/inneredit.tpl");
break;

	/*case "add_frame":
	list($rs, $numpad, $cnt, $limitList)	= 	$objAccessory->listAllAccessories($pageNo,$limit,$param,OBJECT, $orderBy,174,$store_id);
	$framework->tpl->assign("ACCESSORY",$rs);//print_r($rs);

	$framework->tpl->assign("main_tpl", SITE_PATH."/modules/ajax_editor/tpl/editor_per_frame.tpl");	
	$framework->tpl->display($global['curr_tpl']."/inneredit.tpl");
break;*/
			case "list_accessories":	
			
			$show = 9;	
			$category_id 		= 	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] 	: "";
			$act				=	$_REQUEST["act"] 	? $_REQUEST["act"] 		: "";
			$pageNo 			= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] 	: "0";
			$show_All 			= 	$_REQUEST["show_All"] ? $_REQUEST["show_All"] 	: "N";
			$orderBy			=	$_REQUEST["orderBy"] 	? $_REQUEST["orderBy"] 	: " a.id ";
			//$parent_id 			= 	$_REQUEST["parent_id"] ? $_REQUEST["parent_id"] : "0";
			$user_id			=	$_REQUEST["u_id"] ? $_REQUEST["u_id"] : "";
			
		 	$param				=	"mod=ajax_editor&pg=editor&act=$act&cat_id=$category_id&u_id=$user_id&disorder=3";
			list($res, $numpad, $cnt, $limitList)	=	$objAccessory->accessoryList($category_id,$store_id,$pageNo,$show,$param,OBJECT, $orderBy);
			
			$childcategories = $objCategory->getChildCategoriesListById(203);
			$prd_det = $product->ProductGet($_REQUEST["product_id"]);
			$framework->tpl->assign("PRD_DET",$prd_det);
						
			$framework->tpl->assign("CATEGORY",$childcategories);
			$framework->tpl->assign("ACCESSORY",$res);
			$framework->tpl->assign("ACCESSORYDISP",$res[0]);
			$framework->tpl->assign("CATEGORY_PATH",$objCategory->getArtCategoryPath($category_id,0));
			$framework->tpl->assign("ACCESSORY_NUMPAD",$numpad);
			
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/ajax_editor/tpl/user_accessoy_listing.tpl");
			$framework->tpl->display($global['curr_tpl']."/inneredit.tpl");
		break;

	default:
	$base_price = $product->getProductPrice($_REQUEST["product_id"]);
	$framework->tpl->assign("BASE_PRICE",$base_price);
	$prd_det = $product->ProductGet($_REQUEST["product_id"]);
	$names = $prd_det["x_co"];
	$image_name = $_REQUEST["PHPSESSID"].date("Y-m-d G:i:s");
	
	$childcategories = $objCategory->getChildCategoriesListById(203);
	$framework->tpl->assign("CATEGORY",$childcategories);
	
	$image_name = md5($image_name);
	$framework->tpl->assign("IMG_NAME",$image_name);
	
	$framework->tpl->assign("PRD_DET",$prd_det);
	
	$framework->tpl->assign("NAMES",$names);
	/*$framework->tpl->assign("main_tpl", SITE_PATH."/modules/ajax_editor/tpl/editor.tpl");
	$framework->tpl->display($global['curr_tpl']."/inner.tpl");*/
	$framework->tpl->assign("main_tpl", SITE_PATH."/modules/ajax_editor/tpl/editor_per.tpl");
	$framework->tpl->display($global['curr_tpl']."/inneredit.tpl");
}
?>