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

include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");

$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$keysearch		=	$_REQUEST["keysearch"] ? $_REQUEST["keysearch"] : "N";
$category_search=	$_REQUEST["category_search"] ? $_REQUEST["category_search"] : "";
$limit			=	"40";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "category_name";
$parent_id 		= 	$_REQUEST["parent_id"] ? $_REQUEST["parent_id"] : "0";
$id				=	$_REQUEST['id'];
$param			=	"mod=$mod&pg=$pg&act=list&orderBy=$orderBy&parent_id=$parent_id&category_search=$category_search&keysearch=$keysearch&sId=$sId&fId=$fId&id=".$id;
$objCategory	=	new Category();
$framework->tpl->assign("STORE_ID", $store_id);


switch($_REQUEST['act']) {
	case "list":
	
	$req 			=	&$_REQUEST;
	extract($req);
	if($_SERVER['REQUEST_METHOD'] == "POST") {
	
			ob_start();		
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			ob_end_flush();
			
			$fname			=	basename($_FILES['category_image']['name']);
			$ftype			=	$_FILES['category_image']['type'];
			$tmpname		=	$_FILES['category_image']['tmp_name'];
			$objCategory->categoryMassUpdate($req,$fname,$tmpname,$store_id);
			}
			list($rs, $numpad, $cnt, $limitList)	= 	$objCategory->listAllCategory($keysearch,$category_search,$parent_id,$pageNo,$limit,$param,OBJECT, $orderBy,$store_id,$id);

			if( $_REQUEST['category_search']!="" ){
			 $keysearch = 'Y';
			list($rs, $numpad, $cnt, $limitList)	= 	$objCategory->listAllCategory($keysearch,$category_search,$parent_id,$pageNo,$limit,$param,OBJECT, $orderBy,$store_id,$id);
			
          }else if($_REQUEST['category_search']=="" && $_REQUEST['id']!="" )
		// 
			{
			
			
			//$param			.=	"&search_status=1";//search_status   1->true; 0->false
			//$param			.=	"&category_search=$category_search";
			
			list($rs, $numpad, $cnt, $limitList)	= 	$objCategory->listAllCategory2($keysearch,$category_search,$parent_id,$pageNo,$limit,$param,OBJECT, $orderBy,$store_id,$id);
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
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/category/tpl/category_list.tpl");
		break;
	case "form":
	    include_once(SITE_PATH."/includes/areaedit/include.php");
		
		editorInit('category_description');
		
		if(empty($parent_id))
		$parent_id	=	"0";
					
		$category_id 	= 	$_REQUEST["category_id"] ? $_REQUEST["category_id"] : "0";
		$displayPath	=	$objCategory->getCompletePath($parent_id);
		if($store_id)
		{
			$framework->tpl->assign("BULKPG", "category_bulk");
		}else
		{
			$framework->tpl->assign("BULKPG", "bulk");
		}
		if($_SERVER['REQUEST_METHOD'] == "POST") {
		
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			 	=	&$_REQUEST;
			$fname				=	basename($_FILES['category_image']['name']);
			$ftype				=	$_FILES['category_image']['type'];
			$tmpname			=	$_FILES['category_image']['tmp_name'];
			
			$mfname				=	basename($_FILES['category_image_over']['name']);
			$mftype				=	$_FILES['category_image_over']['type'];
			$mtmpname			=	$_FILES['category_image_over']['tmp_name'];
			$category_search 	= 	$_REQUEST['category_search'];
			$parent_id			=	$req['parent_id'];
			$category_id		=	$req['category_id'];	
			//print_r($req);exit;
			if($req['parent_id'] == 282){	
				$seo_url	    	=   preg_replace('/[^\w\d_ -]/si','',$req['category_name']);
				$seo_url	   	 	=   str_replace(" ","-",$seo_url);
				$seo_url	   	    =	strtolower($seo_url);
				$req['seo_url']	    =	$seo_url; 
			}
			
			if ( $global ['category_thumb_image'] )	{
				list( $req['cat_thumb_width'] , $req['cat_thumb_height'] ) = explode ( "," , $global ['category_thumb_image'] );
			}

			
			if( ($message 	= 	$objCategory->categoryAddEdit($req,$fname,$tmpname,$mfname,$mtmpname,$sId,$store_id)) === true ) {
				$action = $req['category_id'] ? "Updated" : "Added";
            	setMessage($_REQUEST["sId"]." $action Successfully", MSG_SUCCESS);
			
				redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=list&parent_id=$parent_id&sId=$sId&fId=$fId&limit=$limit&category_search=".$category_search."&id=".$_REQUEST['id']));
			}

		}
		else
		{
		//editorInit('html_desc');
		}
		if($message) {
			$framework->tpl->assign("FORUMCATEGORY", $_POST);
		} elseif($_REQUEST['category_id']) {
			$framework->tpl->assign("FORUMCATEGORY", $objCategory->CategoryGet($_REQUEST['category_id']));
			$framework->tpl->assign("PARENTCATEGORY", $objCategory->parentGet($_REQUEST['category_id']));
		}
		$framework->tpl->assign("MODULECATEGORY", $objCategory->getModules($category_id,$store_id));
		$framework->tpl->assign("DISPLAY_PATH", $displayPath);
		$framework->tpl->assign("ASSIGN_PRODUCTS",$objCategory->getProducts($_REQUEST['category_id']));
		$framework->tpl->assign("ASSIGN_ACCESSORIES",$objCategory->getAccessories($_REQUEST['category_id']));

		$objAdmin = new Admin();
        $framework->tpl->assign("PRODUCT_FIELDS",$objAdmin->GetFields(20)); 
		
		
        $framework->tpl->assign("ACCESSORY_FIELDS",$objAdmin->GetFields(26)); 
		
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/category/tpl/category_form.tpl");
		
		
		break;
		
	case "delete":
		# For Detailed Category Delete as on project scihat_new
		# added by jeffy on 20th may 2008
		if($_REQUEST['CATID']){
			$objCategory->categoryDeleteTwo($_REQUEST['CATID']);
		}
		# ----------
		$req = &$_REQUEST;
		$category_id    =   $_REQUEST['category_id'];
		$message="";
		$catCount=count($category_id);
		for($i=0;$i<$catCount;$i++){
			# For Detailed Category Delete as on project scihat_new
			# added by jeffy on 20th may 2008
			if ($global['CATEGORY_DELETE'] == 1){
				if(!$message){
					$message .=	$objCategory->categoryDeleteOne($category_id[$i], $sId);
				}else{
					$message =$message.", ".$objCategory->categoryDeleteOne($category_id[$i], $sId);
				}
			# ----------
			}else{
				if(!$message){
					$message .=	$objCategory->categoryDelete($category_id[$i], $sId);
				}else{
					$message =$message.", ".$objCategory->categoryDelete($category_id[$i], $sId);
				}
			}
		}
		if(!$message){
			setMessage("Category Deleted Successfully!", MSG_SUCCESS);
			redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=list&parent_id=$parent_id&limit=$limit&sId=$sId&fId=$fId"));
		}else{
			# For Detailed Category Delete as on project scihat_new
			# added by jeffy on 20th may 2008
			if ($global['CATEGORY_DELETE'] == 1){
				$message2 = "The categori(es) you have selected has <i>".$message."</i>under it. Click 'Yes' if you want to delete the categori(es). This will not delete the records under it. But remove the categori(es) only";
				setMessage($message2);
				$_SESSION['da'] = 1;
				#foreach( $category_id as $key => $value){
					$catID = $category_id[0];
				#}
			# ----------
			}else{
				setMessage($message);
			}
			redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=list&parent_id=$parent_id&limit=$limit&sId=$sId&fId=$fId&category_search=".$category_search."&id=".$_REQUEST['id']));
		}
		break;
		
}

if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}
?>


