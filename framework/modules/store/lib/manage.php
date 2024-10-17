<?php 
/**
 * Admin Store
 *
 * @author sajith
 * @package defaultPackage
 */
authorize();

include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.combination.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.price.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.made.php");
$store = new Store();
$objProduct		=	new Product();
$objCategory	=	new Category();
$objAccessory	=	new Accessory();
$objCombination	=	new Combination();
$objPrice		=	new Price();
$objMade		=	new Made();
$param			=	"mod=$mod&pg=$pg&act=$act&orderBy=$orderBy&sortOrder=$sortOrder";

switch($_REQUEST['act']) {
    //case "storelist":
	case "list":
	$param	.=	"&mod=".$_REQUEST['opt'];
		$_REQUEST['limit'] = $_REQUEST['limit'] ? $_REQUEST['limit'] : 20;
        list($rs, $numpad, $cnt, $limitList) = $store->storeList($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&sId=$sId&fId=$fId", OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("STORE_LIST", $rs);
        $framework->tpl->assign("STORE_NUMPAD", $numpad);
        $framework->tpl->assign("STORE_LIMIT", $limitList);
		switch ($_REQUEST['opt'])
			{
			case "category"	: $framework->tpl->assign("ACT", "category_list");break;
			case "accessory": $framework->tpl->assign("ACT", "accessory_list");break;
			case "product"	: $framework->tpl->assign("ACT", "product_list");break;
			default			: $framework->tpl->assign("ACT", "list");break;
			}
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/store/tpl/manage_storelist.tpl");
    break;
	case "category_list":
	list($rs, $numpad, $cnt, $limitList)	= 	$objCategory->listAllCategory($keysearch,$category_search,$parent_id,$pageNo,$limit,$param,OBJECT, $orderBy);
		$displayPath		=	$objCategory->getCompletePath($parent_id, $sId, $fId);
		$framework->tpl->assign("CATEGORY_LIST", $rs);
		$framework->tpl->assign("ACT", "category_form");
		$framework->tpl->assign("CATEGORY_NUMPAD", $numpad);
		$framework->tpl->assign("CATEGORY_LIMIT", $limitList);
		$framework->tpl->assign("DISPLAY_PATH", $displayPath);
		$framework->tpl->assign("MODULECATEGORY", $objCategory->getModules($category_id));
		$framework->tpl->assign("CATEGORY_SEARCH_TAG", $category_search);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/category/tpl/category_list.tpl");
	break;
	case "category_form":
	include_once(SITE_PATH."/includes/areaedit/include.php");
		if(empty($parent_id))
		$parent_id	=	"0";
		$category_id 	= 	$_REQUEST["category_id"] ? $_REQUEST["category_id"] : "0";
		$displayPath	=	$objCategory->getCompletePath($parent_id);
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			$fname			=	basename($_FILES['category_image']['name']);
			$ftype			=	$_FILES['category_image']['type'];
			$tmpname		=	$_FILES['category_image']['tmp_name'];
			$parent_id		=	$req['parent_id'];
			$category_id	=	$req['category_id'];			
			if( ($message 	= 	$objCategory->categoryAddEdit($req,$fname,$tmpname,$sId)) === true ) {
				redirect(makeLink(array("mod"=>"category", "pg"=>"index"), "act=list&parent_id=$parent_id&sId=$sId&fId=$fId"));
			}
			$framework->tpl->assign("MESSAGE", $message);

		}
		else
		{
		editorInit('html_desc');
		}
		if($message) {
			$framework->tpl->assign("FORUMCATEGORY", $_POST);
		} elseif($_REQUEST['category_id']) {
			$framework->tpl->assign("FORUMCATEGORY", $objCategory->CategoryGet($_REQUEST['category_id']));
		}
		$framework->tpl->assign("ACT", "category_form");
		$framework->tpl->assign("MODULECATEGORY", $objCategory->getModules($category_id));
		$framework->tpl->assign("DISPLAY_PATH", $displayPath);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/category/tpl/category_form.tpl");
	break;
	case "list_ca":
		/*$_REQUEST['limit'] 		= $_REQUEST['limit'] ? $_REQUEST['limit'] : 20;
		$store_id		=	$_REQUEST['store_id'] 	= $_REQUEST['store_id'] ? $_REQUEST['store_id'] : '';
		$product_search	= 	$_REQUEST["product_search"] ? $_REQUEST["product_search"] 	: "";
		$search_status	= 	$_REQUEST["search_status"] 	? $_REQUEST["search_status"] 	: "0";
		$category_id	=	$_REQUEST['category_id'];
		$param			=	"mod=$mod&pg=$pg&act=list&orderBy=$orderBy&sortOrder=$sortOrder";
		$param			.=	"&category_id=$category_id";
		$param1			=	"&fId=$fId&sId=$sId&store_id=".$_REQUEST['store_id'];
		$param			.=	$param1;
		$orderBy 			= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			if($_POST['product_search'])
			{
			$search_status="1";//search_status   1->true; 0->false
			$product_search=$_POST['product_search'];
			}
			else
			{
			$search_status="0";//search_status   1->true; 0->false
			$product_search="";
			//$objProduct->Store_massUpdate($req);
			redirect(makeLink(array("mod"=>"store", "pg"=>"manage"), "act=list&limit=$limit&fId=$fId&sId=$sId&store_id=$store_id"));
			}
		}
		if($search_status=="1")//search_status   1->true; 0->false
			{
			$param			.=	"&search_status=1";//search_status   1->true; 0->false
			$param			.=	"&product_search=$product_search";
			list($rs, $numpad, $cnt, $limitList)	= 	$objProduct->Store_listAllProduct($_REQUEST['store_id'],$pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy,$product_search);
			}
		else//search_status   1->true; 0->false
			{
			$param			.=	"&product_search=$product_search";
			$param			.=	"&search_status=0";//search_status   1->true; 0->false
			list($rs, $numpad, $cnt, $limitList)	= 	$objProduct->Store_listAllProduct($_REQUEST['store_id'],$pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy,'',$_REQUEST['category_id']);
			}
		$framework->tpl->assign('ACCESSORYMENU', $objAccessory->Store_GetAllAccessoryGroup($store_id));//Done
		$framework->tpl->assign("PRODUCT_PRICES", $objPrice->GetPriceTypeInCombo());//Done
		$framework->tpl->assign("PRODUCT_SEARCH_TAG", $product_search);//Done
		$framework->tpl->assign("PRODUCT_LIST", $rs);//Done
		$framework->tpl->assign("PRODUCT_NUMPAD", $numpad);//Done
		$framework->tpl->assign("PRODUCT_LIMIT", $limitList);//Done
		$parent_id			=	isset($_REQUEST["category_id"])?trim($_REQUEST["category_id"]):"0";	
		if($parent_id>0)
			{
			$framework->tpl->assign('SELECT_DEFAULT', '--- '.$objCategory->getCategoryname($parent_id).' ---');//Done
			}
		else
			{
			$framework->tpl->assign('SELECT_DEFAULT', "-- SELECT A CATEGORY --");
			}
		$framework->tpl->assign("CATEGORY_PATH",$objCategory->getCategoryPathAdmin(array("store","manage",$param1),$parent_id,$limit));//Done
			
		$framework->tpl->assign('CATEGORY_PARENT',$objCategory->getCategoryTreeParentFirst($parent_id));//Done
		$framework->tpl->assign("GROUP", $objProduct->Store_AllProductGroups($store_id));//Done
		$framework->tpl->assign("BRAND", $objProduct->GetAllBrands());//Done
		if($store_id>0)
			$objCategory->getCategoryTree($catArr,$store_id);
		else
			$objCategory->getCategoryTree($catArr,0);
		$framework->tpl->assign('CATEGORY', $catArr);//Done
		$framework->tpl->assign('CATEGORY_IS_IN_UI_ALL', $objAccessory->getAccessories_of_category($store_id));//Done
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/store/tpl/store_product_list.tpl");*/
	break;
}

$framework->tpl->display($global['curr_tpl']."/admin.tpl");

?>