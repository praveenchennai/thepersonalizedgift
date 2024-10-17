<?
session_start();
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.features.php");

$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$keysearch		=	$_REQUEST["keysearch"] ? $_REQUEST["keysearch"] : "N";
$category_search=	$_REQUEST["category_search"] ? $_REQUEST["category_search"] : "";
$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "label";
$param			=	"mod=$mod&pg=$pg&act=list&orderBy=$orderBy&parent_id=$parent_id&category_search=$category_search&keysearch=$keysearch&sId=$sId&fId=$fId";
$objCategory	=	new Category();
$objFeatures	=	new Features();

switch($_REQUEST['act']) {
	case "list":
		list($rs, $numpad, $cnt, $limitList)	= 	$objFeatures->listAllFeatures($keysearch,$category_search,$pageNo,$limit,$param,OBJECT, $orderBy);
		$framework->tpl->assign("FEATURES_LIST", $rs);
		$framework->tpl->assign("ACT", "form");
		$framework->tpl->assign("FEATURES_NUMPAD", $numpad);
		$framework->tpl->assign("FEATURES_LIMIT", $limitList);
		$framework->tpl->assign("FEATURES_SEARCH_TAG", $category_search);
		$framework->tpl->assign("FEATURES_SEARCH_TAG", $category_search);
					
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/flyer/tpl/features_list.tpl");
		break;
		
		case "form":
		
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			$feature_id	=	$req['feature_id'];			
			if( ($message 	= 	$objFeatures->FeatureAddedit($req,$sId)) === true ) {
				$action = $req['feature_id'] ? "Updated" : "Added";
            	setMessage($_REQUEST["sId"]." $action Successfully", MSG_SUCCESS); 
			$type	=	$req["type"];
			if($feature_id	== "")
			{
			$feature_id		=	$_SESSION['feature_id']; }
			if(isset($_SESSION['feature_id']))
			{
			unset ($_SESSION['feature_id']);}
				if($type=="Dropdown")
				{
					redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=option_add&feature_id=$feature_id&action=$action&sId=$sId&fId=$fId&limit=$limit"));
				}
				//print($type);exit;
				redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=list&sId=$sId&fId=$fId&limit=$limit"));
			}
		}
		if($message) {
			$framework->tpl->assign("FORUMCATEGORY", $_POST);
		} elseif($_REQUEST['feature_id']) {
			$framework->tpl->assign("FEATURE_VALUE", $objFeatures->FeatureGet($_REQUEST['feature_id']));
		}
		
		$typeArr 	= 	array('Text' => 'Text', 
						'Multiline Text' => 'Multiline Text',
						'Number' => 'Number', 
						'Date' => 'Date', 
						'Dropdown' => 'Dropdown');
						
		$framework->tpl->assign("TYPE_ARRAY", $typeArr);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/flyer/tpl/features_form.tpl");
		break;
		
		case "option_add":
		$feature_id	=	$_REQUEST['feature_id'];
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req	=	$_POST;
			if( ($message 	= 	$objFeatures->FeatureOptionAdd($req,$feature_id)) === true ) {
				$action = $_REQUEST['action'];
            	setMessage($_REQUEST["sId"]." $action Successfully", MSG_SUCCESS);
				redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=list&sId=$sId&fId=$fId&limit=$limit"));
			}
		}
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/flyer/tpl/features_form_options.tpl");
	
		break;
		
		case "delete":
		extract($_POST);
		if(count($category_id)>0)
		{
		$message=true;
		foreach ($category_id as $features_id)
			{  
			if($objFeatures->featuresDelete($features_id)==false)
				$message=false;
			}
		}
		if($message==true)
			setMessage($_REQUEST["sId"]."(s) Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage($_REQUEST["sId"]."(s) Can not Deleted!");
		redirect(makeLink(array("mod"=>"flyer", "pg"=>"features"), "act=list&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
		break;
	
}
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");

?>


