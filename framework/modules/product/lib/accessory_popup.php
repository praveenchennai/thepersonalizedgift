<?php 
/**
 * Product
 *
 * @author adarsh
 * @package defaultPackage
 */



include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");

$objProduct		=	new Product();
$objAccessory	=	new Accessory();

$act			=	$_REQUEST["act"] 			? $_REQUEST["act"] 				: "";
$limit			=	$_REQUEST["limit"] 			? $_REQUEST["limit"] 			: "20";
$pageNo 		= 	$_REQUEST["pageNo"] 		? $_REQUEST["pageNo"] 			: "1";


switch($_REQUEST['act']) {
   
    case "artbackground_list":
	
		$catacc			=	$_REQUEST['aid'] ? $_REQUEST['aid'] : "";
		$accessory_search=$_REQUEST['accessory_search'];
		
		$param			=	"mod=$mod&pg=$pg&act=$act&aid=$catacc&orderBy=$orderBy&sortOrder=$sortOrder&cat_id=$cat_id&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"];
		
		$orderBy 			= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "display_order";
		
		$subacc	=	$objAccessory->GetSubCatOfAcc($catacc);
		$string = $catacc;
		for($i=0;$i<count($subacc);$i++)
		{
			$string	=	$string.",".$subacc[$i][category_id];
		}
		
		if($accessory_search)
		{
			$search_status="1";
		}
		
		if($catacc == 235)
		$orderBy="display_order";
		else
		$orderBy="name";
		
		
		if($search_status=="1")//search_status   1->true; 0->false
		{
		$param			.=	"&search_status=1";//search_status   1->true; 0->false
		$param			.=	"&accessory_search=$accessory_search";
		
			list($rs, $numpad, $cnt, $limitList)	= 	$objAccessory->listAllAccessoriesOfCatagorybySearch2($pageNo,$limit,$param,OBJECT, $orderBy,$cat_id,$accessory_search,$string,$store_id);
		
		}
		else
	   list($rs, $numpad, $cnt, $limitList)	= 	$objAccessory->listAllAccessoriesOfCatagory($pageNo,$limit,$param,OBJECT, $orderBy,$cat_id,$store_id,$string,1);
	   
	   
	    $framework->tpl->assign("AID", $_REQUEST['aid']);	
    	$framework->tpl->assign("ACCESSORY_LIST", $rs);
		$framework->tpl->assign("ACCESSORY_NUMPAD", $numpad);
		$framework->tpl->assign("ACCESSORY_LIMIT", $limitList);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/artbackground_list.tpl");
        break;
}
$framework->tpl->display($global['curr_tpl']."/adminPopup.tpl");


?>