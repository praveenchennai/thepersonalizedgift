<?
if($_REQUEST['manage']=="manage"){
	authorize_store();
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("STORE_PERMISSION",$store->getStorePermissionById($store_id,$module_menu_id)) ;
}else{
	authorize();
	$framework->tpl->assign("MOD","Category") ;
	$STORE = array('hide'=>'Y','add'=>'Y','edit'=>'Y','delete'=>'Y');
	$framework->tpl->assign("STORE_PERMISSION",$STORE) ;
}

session_start();
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");

$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$keysearch		=	$_REQUEST["keysearch"] ? $_REQUEST["keysearch"] : "N";
$category_search=	$_REQUEST["category_search"] ? $_REQUEST["category_search"] : "";
$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "category_name";
$parent_id 		= 	$_REQUEST["parent_id"] ? $_REQUEST["parent_id"] : "0";
// $id	=	$_REQUEST['id'];

$param			=	"mod=$mod&pg=$pg&act=list&orderBy=$orderBy&parent_id=$parent_id&category_search=$category_search&keysearch=$keysearch&sId=$sId&fId=$fId";
$objCategory	=	new Category();
$framework->tpl->assign("STORE_ID", $store_id);
switch($_REQUEST['act']) {
	case "list":
	
	
	//echo $objCategory->GetParentCategory(255);
	if($_SERVER['REQUEST_METHOD'] == "POST" ) {
	
	
	
	//$category_search=$_POST['category_search'];
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			$fname			=	basename($_FILES['category_image']['name']);
			$ftype			=	$_FILES['category_image']['type'];
			$tmpname		=	$_FILES['category_image']['tmp_name'];
			$objCategory->categoryMassUpdate($req,$fname,$tmpname,$store_id);
			}
			
		list($rs, $numpad, $cnt, $limitList)	= 	$objCategory->listAllCategory($keysearch,$category_search,$parent_id,$pageNo,$limit,$param,OBJECT, $orderBy,$store_id);
		   //====================================
		   if( $_REQUEST['id'])//search_status   1->true; 0->false
			{
			
			 
			//$param			.=	"&search_status=1";//search_status   1->true; 0->false
			//$param			.=	"&category_search=$category_search";
			
			list($rs, $numpad, $cnt, $limitList)	= 	$objCategory->listAllCategory2($keysearch,$category_search,$parent_id,$pageNo,$limit,$param,OBJECT, $orderBy,$store_id,$id);
			}
			//====================================
		$displayPath		=	$objCategory->getCompletePath($parent_id, $sId, $fId);
		$framework->tpl->assign("CATEGORY_LIST", $rs);
		$framework->tpl->assign("ACT", "form");
		$framework->tpl->assign("CATEGORY_NUMPAD", $numpad);
		$framework->tpl->assign("CATEGORY_LIMIT", $limitList);
		$framework->tpl->assign("DISPLAY_PATH", $displayPath);
		/*print "<pre>";
		print_r($objCategory->getModuleTreeParentFirst($category_id,$store_id));
		
		exit;
		$framework->tpl->assign('MODULE_PARENT',$objCategory->getModuleTreeParentFirst($category_id,$store_id));
*/
		$framework->tpl->assign("MODULECATEGORY", $objCategory->getModules($category_id,$store_id));
		$framework->tpl->assign("CATEGORY_SEARCH_TAG", $category_search);
		if($store_id)
		{
			$framework->tpl->assign("BULKPG", "category_bulk");
		}else
		{
			$framework->tpl->assign("BULKPG", "bulk");
		}
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/category/tpl/category_list.tpl");
		break;
	case "form":
	    include_once(SITE_PATH."/includes/areaedit/include.php");
		if(empty($parent_id))
		$parent_id	=	"0";
					
					$category_id 	= 	$_REQUEST["category_id"] ? $_REQUEST["category_id"] : "0";
		$displayPath	=	$objCategory->getCompletePath($parent_id);
		if($_SERVER['REQUEST_METHOD'] == "POST") {
		
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			//print_r($req);exit;
			$fname			=	basename($_FILES['category_image']['name']);
			$ftype			=	$_FILES['category_image']['type'];
			$tmpname		=	$_FILES['category_image']['tmp_name'];
			$parent_id		=	$req['parent_id'];
			$category_id	=	$req['category_id'];			
			if( ($message 	= 	$objCategory->categoryAddEdit($req,$fname,$tmpname,$sId,$store_id)) === true ) {
				$action = $req['category_id'] ? "Updated" : "Added";
            	setMessage($_REQUEST["sId"]." $action Successfully", MSG_SUCCESS);
			//echo $message;
				redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=list&parent_id=$parent_id&sId=$sId&fId=$fId&limit=$limit"));
			}

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
		
		
		$framework->tpl->assign("MODULECATEGORY", $objCategory->getModules($category_id,$store_id));
		$framework->tpl->assign("DISPLAY_PATH", $displayPath);
		$framework->tpl->assign("ASSIGN_PRODUCTS",$objCategory->getProducts($_REQUEST['category_id']));
		$framework->tpl->assign("ASSIGN_ACCESSORIES",$objCategory->getAccessories($_REQUEST['category_id']));

		$objAdmin = new Admin();
        $framework->tpl->assign("PRODUCT_FIELDS",$objAdmin->GetFields(20)); 
        $framework->tpl->assign("ACCESSORY_FIELDS",$objAdmin->GetFields(26)); 
		  
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/category/tpl/category_form.tpl");
		
		//$a = $objAdmin->GetFields(26);
		//echo "<pre>";
		//print_r($a);
		break;
	case "delete":
	$req 			=	&$_REQUEST;
	extract($req);
	$message="";
	$catCount=count($category_id);
	for($i=0;$i<$catCount;$i++)
	{	
		if(!$message)
		{
		$message .=	$objCategory->categoryDelete($category_id[$i], $sId);
		}else{
			$message =$message.", ".$objCategory->categoryDelete($category_id[$i], $sId);
		}
		
	}
		if(!$message){
			setMessage("Category Deleted Successfully!", MSG_SUCCESS);
			redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=list&parent_id=$parent_id&limit=$limit&sId=$sId&fId=$fId&category_search=$category_search"));
		}
		else
		{
			setMessage($message);
			redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=list&parent_id=$parent_id&limit=$limit&sId=$sId&fId=$fId&category_search=$category_search"));
		}
		break;

}

if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}
?>


