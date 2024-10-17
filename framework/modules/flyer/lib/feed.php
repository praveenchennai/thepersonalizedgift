<?php

/**
 * @description Feed creation for automatic submission
 *
 * @author vimson@newagesmb.com 
 * 
 */

session_start();
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.feed.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.features.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");



$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "feedlist";
$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "10";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "feed_id";
$param			=	"mod=$mod&pg=$pg&act=$act&orderBy=$orderBy&sId=$sId&fId=$fId";

$FeaturesObj	=	new Features();
$FlyerObj		=	new	Flyer();
$FeedObj		=	new Feed($FlyerObj);



switch($_REQUEST['act']) {

	case "feedlist":
		list($rs, $numpad, $cnt, $limitList)	= 	$FeedObj->getFeeds($pageNo,$limit,$param,OBJECT, $orderBy);
		$framework->tpl->assign("FEEDS_LIST", $rs);
		$framework->tpl->assign("FEED_NUMPAD", $numpad);
		$framework->tpl->assign("FEED_LIMIT", $limitList);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/flyer/tpl/feedlist.tpl");
		break;
	
	case "feedform":

		$Submit		=		$_REQUEST['Submit'];
		if($Submit == 'Submit'  && $_SERVER['REQUEST_METHOD'] == 'POST') {
			$StatusMsg	=	$FeedObj->validateFeedForm($_REQUEST);
			
			if($StatusMsg === TRUE) {
				$msg	=	$FeedObj->feedAddEdit($_REQUEST);
				setMessage($msg, MSG_SUCCESS);
				redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=feedlist&sId=$sId&fId=$fId&limit=$limit"));
			} else {
				setMessage($StatusMsg);
				$framework->tpl->assign("FEED_VALUE", $_REQUEST);
			}
		}
		
		if($_REQUEST['feed_id'] != '') {
			$framework->tpl->assign("FEED_VALUE", $FeedObj->getFeedDetailsFromFeedId($_REQUEST['feed_id']));
		}
		
		$framework->tpl->assign("CATEGORY_ARRAY", $FeedObj->GetAllSecondLevelCategory());
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/flyer/tpl/feedform.tpl");	
		break;
	

	case "delete_feeds":
		
		$FeedObj->removeFeeds($_REQUEST);
		redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=feedlist&sId=$sId&fId=$fId&limit=$limit"));
		break;
	

	case "node_list":
		
		$feed_id	=	$_REQUEST['feed_id'];
		$param		.=	"&feed_id=$feed_id";
		list($rs, $numpad, $cnt, $limitList)	= 	$FeedObj->getNodesOfFeed($feed_id, $pageNo,$limit,$param,OBJECT, $orderBy);

		$framework->tpl->assign("NODE_LIST", $FeedObj->processNodeList($rs));
		$framework->tpl->assign("NODE_LIMIT", $limitList);
		$framework->tpl->assign("NODE_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/flyer/tpl/nodelist.tpl");	
		break;
	

	case "node_form":
		$feed_id			=	$_REQUEST['feed_id'];
		$node_id			=	$_REQUEST['node_id'];
		$ParentNodes		=	$FeedObj->getParentNodeCombo($feed_id);
		$StaticFeatures		=	$FeedObj->getStaticFieldsForComboFilling();
		$DynamicFeatures	=	$FeaturesObj->getAllFeaturesForComboFilling();
		$Submit				=	$_REQUEST['Submit'];
		
		if($Submit == 'Submit' && $_SERVER['REQUEST_METHOD'] == 'POST') {
			$StatusMessage	=	$FeedObj->validateNodeAddEditForm($_REQUEST);
			if($StatusMessage === TRUE) {
				$FeedObj->addEditNodeItem($_REQUEST);
				redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=node_list&feed_id=$feed_id&node_id=$node_id&sId=$sId&fId=$fId&limit=$limit"));
			} else {
				setMessage($StatusMessage);
				$framework->tpl->assign("NODE_VALUE", $_REQUEST);
			}
		}
		
		if($node_id != '') {
			$Nodedetails	=	$FeedObj->getNodeDetailsFromId($node_id);
			$framework->tpl->assign("NODE_VALUE", $Nodedetails);
		}
		
		$framework->tpl->assign("PARENT_NODES", $ParentNodes);
		$framework->tpl->assign("STATIC_FEATURES", $StaticFeatures);
		$framework->tpl->assign("DYNAMIC_FEATURES", $DynamicFeatures);
		
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/flyer/tpl/nodeform.tpl");
		break;

	case "delete_nodes":
		$feed_id			=	$_REQUEST['feed_id'];
		$node_id			=	$_REQUEST['node_id'];
		$FeedObj->removeNodes($_REQUEST);
		redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=node_list&feed_id=$feed_id&sId=$sId&fId=$fId&limit=$limit"));
		break;
	
	case "feed_download":
		$feed_id		=	$_REQUEST['feed_id'];
		$ListingIds		=	$FeedObj->getListingsOfFeedCategory($feed_id);
		
		$FeedFile		=	$FeedObj->generateFeedForAutoSubmission($feed_id, $ListingIds);

		$FeedFileName	=	$feed_id.'_feed.xml';
		
		header("Content-Type: application/xhtml+xml");
		header("Content-disposition: attachment; filename=$FeedFileName");
		if(file_exists($FeedFile))
			readfile($FeedFile);
		exit;
		break;

	default: break;
	
} 

$framework->tpl->display($global['curr_tpl']."/admin.tpl");


?>