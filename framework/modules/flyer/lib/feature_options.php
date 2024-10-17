<?php
session_start();

include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.features.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.forms.php");

$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "All";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
$feature_id		=	$_REQUEST["id"] ? $_REQUEST["id"] : "0";
$param			=	"mod=$mod&pg=$pg&act=list&orderBy=$orderBy&keysearch=$keysearch&sId=$sId&fId=$fId";

$FeatureObj	=	new Features();
$objForms	=	new Forms();

switch($_REQUEST['act']) {
			
		case "delete":
		extract($_POST);
		if(count($category_id)>0)
		{
		$message=true;
		foreach ($category_id as $item_id)
			{  
			if($FeatureObj->AttributeItemDelete($item_id)==false)
				$message=false;
			}
		}
		if($message==true)
			setMessage("Item(s) Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage("Item(s) Can not Deleted!");
		redirect(makeLink(array("mod"=>"flyer", "pg"=>"feature_options"), "act=options_view&id=$feature_id&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
		break;
		
	case "options_view":
	
	list($rs, $numpad, $cnt, $limitList)	= 	$FeatureObj->listAllFeatureItems($feature_id,$pageNo,$limit,$param,OBJECT, $orderBy);
		$framework->tpl->assign("ITEM_LIST", $rs);
		$framework->tpl->assign("ACT", "form");
		$framework->tpl->assign("ITEM_NUMPAD", $numpad);
		$framework->tpl->assign("ITEM_LIMIT", $limitList);
		$framework->tpl->assign("FEATURE_ID", $feature_id);
	
	$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/flyer/tpl/features_item_list.tpl");

	break;
	
	case "feature_item_edit":
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			$item_id	=	$req['item_id'];		
			$feature_id	=	$req['feature_id'];			
			if( ($message 	= 	$FeatureObj->FeatureOptionedit($req,$sId)) === true ) {
				$action = $req['item_id'] ? "Updated" : "Added";
            	setMessage("Item edited Successfully", MSG_SUCCESS); 
				redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=options_view&id=$feature_id&sId=$sId&fId=$fId&limit=$limit"));
			}
		}
		
		$framework->tpl->assign("ITEM_VALUE", $FeatureObj->FeatureOptionGet($_REQUEST['item_id']));
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/flyer/tpl/feature_option_edit.tpl");
	break;
	
	case "rss_fields":
	$form_id	=	$_REQUEST['form_id'];
	if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			$objForms->form_rssDelete($form_id);
			if(count($featureId)>0)
			{
				foreach ($featureId as $featureId)
				{  
					$objForms->form_rssAdd($featureId,$form_id);
				}
			}
			setMessage("RSS Fields updated Successfully", MSG_SUCCESS); 
			redirect(makeLink(array("mod"=>"flyer", "pg"=>"feature_options"), "act=rss_fields&form_id=$form_id"));

			}
		$framework->tpl->assign("GRP_FEATURES",$objForms->GetFeatures());
		$framework->tpl->assign('SELECTED_FEATURES', $objForms->GetSelectedRssFeatures($form_id));
		$framework->tpl->assign("FORM_ID", $form_id);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/flyer/tpl/rss_feature_list.tpl");
	break;
	
	
	default: break;	
	

} # Close switch statement

$framework->tpl->display($global['curr_tpl']."/adminPopup.tpl");


?>