<?

/**
 * Category
 *
 * @author afsal
 * @package defaultPackage
 */

authorize();

include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");

$objCategory	=	new Category();
$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$keysearch		=	$_REQUEST["keysearch"] ? $_REQUEST["keysearch"] : "N";
$category_search=	$_REQUEST["category_search"] ? $_REQUEST["category_search"] : "";
$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "category_name";
$parent_id 		= 	$_REQUEST["parent_id"] ? $_REQUEST["parent_id"] : "0";
$category_id	= 	$_REQUEST['cat_id'];
$param			=	"mod=$mod&pg=$pg&act=list&orderBy=$orderBy&parent_id=$parent_id&category_search=$category_search&keysearch=$keysearch&sId=$sId&fId=$fId&cat_id=$category_id";
$objCategory	=	new Category();
$framework->tpl->assign("STORE_ID", $store_id);


switch($_REQUEST['act']) {
    case "list":
			
		  if($_SERVER['REQUEST_METHOD'] == "POST") {
		 
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			$fname			=	basename($_FILES['category_image']['name']);
			$ftype			=	$_FILES['category_image']['type'];
			$tmpname		=	$_FILES['category_image']['tmp_name'];
			$objCategory->categoryMassUpdate($req,$fname,$tmpname,$store_id);
			}

			list($rs, $numpad, $cnt, $limitList)	= 	$objCategory->listAllCategory($keysearch,$category_search,$parent_id,$pageNo,$limit,$param,OBJECT, $orderBy,$store_id,$_REQUEST['cat_id']);

			if( $_REQUEST['category_search']!="" ){
			
			list($rs, $numpad, $cnt, $limitList)	= 	$objCategory->listAllCategory($keysearch,$category_search,$parent_id,$pageNo,$limit,$param,OBJECT, $orderBy,$store_id,$_REQUEST['cat_id']);
          }else if($_REQUEST['category_search']=="" && $_REQUEST['id']!="" )
		// 
			{
			
			 
			//$param			.=	"&search_status=1";//search_status   1->true; 0->false
			//$param			.=	"&category_search=$category_search";
			
			list($rs, $numpad, $cnt, $limitList)	= 	$objCategory->listAllCategory2($keysearch,$category_search,$parent_id,$pageNo,$limit,$param,OBJECT, $orderBy,$store_id,$id,$_REQUEST['cat_id']);
			}
		//list($rs, $numpad, $cnt, $limitList)	= 	$objCategory->listAllCategory($keysearch,$category_search,$parent_id,$pageNo,$limit,$param,OBJECT, $orderBy,$store_id);
		
		$displayPath		=	$objCategory->getCompletePath($parent_id, $sId, $fId);
		$framework->tpl->assign("CATEGORY_LIST", $rs);
		$framework->tpl->assign("ACT", "form");
		$framework->tpl->assign("CATEGORY_NUMPAD", $numpad);
		$framework->tpl->assign("CATEGORY_LIMIT", $limitList);
		$framework->tpl->assign("DISPLAY_PATH", $displayPath);
		$framework->tpl->assign("MODULECATEGORY", $objCategory->getModules($category_id,$store_id));
		$framework->tpl->assign("CATEGORY_SEARCH_TAG", $category_search);
		if($store_id)
		{
			$framework->tpl->assign("BULKPG", "category_bulk");
		}else
		{
			$framework->tpl->assign("BULKPG", "bulk");
		}
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/category/tpl/category_search.tpl");
        break;
}
$framework->tpl->display($global['curr_tpl']."/adminPopup.tpl");
?>