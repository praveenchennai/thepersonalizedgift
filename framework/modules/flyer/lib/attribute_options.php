<?php
session_start();

include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.attributes.php");

$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "All";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "item_name";
$group_id		=	$_REQUEST["id"] ? $_REQUEST["id"] : "0";
$param			=	"mod=$mod&pg=$pg&act=list&orderBy=$orderBy&keysearch=$keysearch&sId=$sId&fId=$fId";

$AttributeObj	=	new Attributes();

switch($_REQUEST['act']) {
			
		case "delete":
		extract($_POST);
		if(count($category_id)>0)
		{
		$message=true;
		foreach ($category_id as $item_id)
			{  
			if($AttributeObj->AttributeItemDelete($item_id)==false)
				$message=false;
			}
		}
		if($message==true)
			setMessage("Attribute Item(s) Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage("Attribute Item(s) Can not Deleted!");
		redirect(makeLink(array("mod"=>"flyer", "pg"=>"attribute_options"), "act=options_view&id=$group_id&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
		break;
		
	case "options_view":
	
	list($rs, $numpad, $cnt, $limitList)	= 	$AttributeObj->listAllAttributeItems($group_id,$pageNo,$limit,$param,OBJECT, $orderBy);
		$framework->tpl->assign("ITEM_LIST", $rs);
		$framework->tpl->assign("ACT", "form");
		$framework->tpl->assign("ITEM_NUMPAD", $numpad);
		$framework->tpl->assign("ITEM_LIMIT", $limitList);
		$framework->tpl->assign("GROUP_ID", $group_id);
	
	$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/flyer/tpl/attribute_item_list.tpl");
	break;
	
	case "attribute_item_edit":
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			$item_id	=	$req['item_id'];		
			$attribute_id	=	$req['attribute_id'];			
			if( ($message 	= 	$AttributeObj->AttributeOptionedit($req,$sId)) === true ) {
				$action = $req['item_id'] ? "Updated" : "Added";
            	setMessage("Item edited Successfully", MSG_SUCCESS); 
				redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=options_view&id=$attribute_id&sId=$sId&fId=$fId&limit=$limit"));
			}
		}
		
		$framework->tpl->assign("ITEM_VALUE", $AttributeObj->AttributeOptionGet($_REQUEST['item_id']));
		//print_r($AttributeObj->AttributeOptionGet($_REQUEST['item_id']));
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/flyer/tpl/attribute_option_edit.tpl");
	
	break;
	
	default: break;	
	

} # Close switch statement

$framework->tpl->display($global['curr_tpl']."/adminPopup.tpl");


?>