<?
session_start();
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.forms.php");

$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$keysearch		=	$_REQUEST["keysearch"] ? $_REQUEST["keysearch"] : "N";
$category_search=	$_REQUEST["category_search"] ? $_REQUEST["category_search"] : "";
$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "form_title";

$param			=	"mod=$mod&pg=$pg&act=list&orderBy=$orderBy&parent_id=$parent_id&category_search=$category_search&keysearch=$keysearch&sId=$sId&fId=$fId";
$objCategory	=	new Category();
$objForms	=	new Forms();
switch($_REQUEST['act']) {
	case "list":
		list($rs, $numpad, $cnt, $limitList)	= 	$objForms->listAllForms($keysearch,$category_search,$pageNo,$limit,$param,OBJECT, $orderBy);
		$framework->tpl->assign("FORMS_LIST", $rs);
		$framework->tpl->assign("ACT", "form");
		$framework->tpl->assign("FORMS_NUMPAD", $numpad);
		$framework->tpl->assign("FORMS_LIMIT", $limitList);
		$framework->tpl->assign("FORMS_SEARCH_TAG", $category_search);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/flyer/tpl/forms_list.tpl");
		break;
		
		case "form":
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			$forms_id	=	$req['forms_id'];			
			if( ($message 	= 	$objForms->FormsAddedit($req,$sId)) === true ) {
				$action = $req['forms_id'] ? "Updated" : "Added";
            	setMessage($_REQUEST["sId"]." $action Successfully", MSG_SUCCESS); 
				redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=list&sId=$sId&fId=$fId&limit=$limit"));
			}
		}
	
		if($message) {
			$framework->tpl->assign("FORUMCATEGORY", $_POST);
		} elseif($_REQUEST['forms_id']) {
			$framework->tpl->assign("FEATURE_VALUE", $objForms->FormsGet($_REQUEST['forms_id']));
		}
				
		$framework->tpl->assign("CATEGORY_ARRAY", $objForms->GetAllSecondLevelCategory());
		  
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/flyer/tpl/forms_form.tpl");
		break;
		
		case "delete":
		extract($_POST);
		if(count($category_id)>0)
		{
		$message=true;
		foreach ($category_id as $forms_id)
			{  
			if($objForms->formsDelete($forms_id)==false)
				$message=false;
			}
		}
		if($message==true)
			setMessage($_REQUEST["sId"]."(s) Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage($_REQUEST["sId"]."(s) Can not Deleted!");
		redirect(makeLink(array("mod"=>"flyer", "pg"=>"forms"), "act=list&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
		break;
		
		
		case "block_delete":
		extract($_POST);
		if(count($category_id)>0)
		{
		$message=true;
		foreach ($category_id as $block_id)
			{  
			if($objForms->blockDelete($block_id)==false)
				$message=false;
			}
		}
		if($message==true)
			setMessage("Block(s) Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage("Block(s) Can not Deleted!");
		redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=block_list&forms_id=$forms_id&sId=$sId&fId=$fId&limit=$limit"));
		break;
		
		
		case "block_list":
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "block_title";
		$forms_id 		= 	$_REQUEST["forms_id"] ? $_REQUEST["forms_id"] : "0";
		$param			=	"mod=$mod&pg=$pg&act=block_list&forms_id=$forms_id&category_search=$category_search&keysearch=$keysearch&orderBy=$orderBy&sId=$sId&fId=$fId";
		list($rs, $numpad, $cnt, $limitList)	= 	$objForms->listAllBlocks($keysearch,$category_search,$pageNo,$limit,$param,OBJECT, $orderBy,$forms_id);
		$framework->tpl->assign("BLOCK_LIST", $rs);
		$framework->tpl->assign("ACT", "form");
		$framework->tpl->assign("BLOCK_NUMPAD", $numpad);
		$framework->tpl->assign("BLOCK_LIMIT", $limitList);
		$framework->tpl->assign("BLOCK_SEARCH_TAG", $category_search);
		$framework->tpl->assign("FORM_ID", $forms_id);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/flyer/tpl/block_list.tpl");
		break;
		
		case "block_form":
		$forms_id 		= 	$_REQUEST["forms_id"] ? $_REQUEST["forms_id"] : "0";
		if($_SERVER['REQUEST_METHOD'] == "POST") { // print_r($_POST);exit;
			
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			extract($req);
			
			$block_id	=	$req['block_id'];			
			if( ($message 	= 	$objForms->BlockAddedit($req,$sId,$forms_id)) === true ) {
				$action = $req['block_id'] ? "Updated" : "Added";
            	setMessage(" Block $action Successfully", MSG_SUCCESS); 
				
				if($block_id	== "")
				{
				$block_id = $_SESSION['block_id']; }
				if(isset($_SESSION['block_id']))
				{	unset ($_SESSION['block_id']);}
				
				# mapping Features and attributues for the block
				$objForms->blockAttrDelete($block_id);
				
				
				if(count($attributeId)>0)
				{ 
					
					foreach ($attributeId as $attributeId)
					{  
						$objForms->mapAttributes($attributeId,$block_id);
					}
				}
				$objForms->blockFesDelete($block_id);
				if(count($featureId)>0)
				{
					
					foreach ($featureId as $featureId)
					{  
						
						$objForms->mapFeatures($featureId,$block_id);
					}
				}
				# mapping Features and attributues for the block
				redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=block_list&forms_id=$forms_id&sId=$sId&fId=$fId&limit=$limit"));
			}
		}
	
		if($message) {
			$framework->tpl->assign("FORUMCATEGORY", $_POST);
		} elseif($_REQUEST['block_id']) {
			$framework->tpl->assign("BLOCK_VALUE", $objForms->BlockGet($_REQUEST['block_id']));
		}
		
		
		$framework->tpl->assign('SELECTED_FEATURES', $objForms->GetAllSelectedFeatures($_REQUEST['block_id']));
		$framework->tpl->assign('SELECTED_ATTRIBUTES', $objForms->GetAllSelectedAttributes($_REQUEST['block_id']));
		$framework->tpl->assign("FORM_ID", $forms_id);
		$framework->tpl->assign("ALL_FEATURES", $forms_id); 
		
		$framework->tpl->assign("GRP_ATTRIBUTES",$objForms->GetAttributes());
		$framework->tpl->assign("GRP_FEATURES",$objForms->GetFeatures());
		
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/flyer/tpl/block_form.tpl");

		break;
		case "mandatoryFeatures":
			
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$objForms->deleteSelectedFeatures($_REQUEST['block_id']);
				if(count($_REQUEST['featureId'])){
					 
					foreach($_REQUEST['featureId'] as $feature_id){
					
						$objForms->updateSelectedFeatures($feature_id,$_REQUEST['block_id']);
						
					}
					setMessage("Fields are set as mandatory",MSG_SUCCESS);
				}
			}
			
			$framework->tpl->assign('SELECTED_FEATURES_MAND', $objForms->GetAllSelectedFeaturesMandatory($_REQUEST['block_id']));
			$framework->tpl->assign('SELECTED_FEATURES', $objForms->getSelectedOnlyFeatures($_REQUEST['block_id']));
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/flyer/tpl/features_mandatory.tpl");
			$framework->tpl->display($global['curr_tpl']."/adminPopup.tpl");
			exit;
			break;
	
}
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");

?>