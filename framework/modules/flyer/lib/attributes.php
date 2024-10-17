<?php
session_start();

include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.attributes.php");

$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$keysearch		=	$_REQUEST["keysearch"] ? $_REQUEST["keysearch"] : "N";
$category_search=	$_REQUEST["category_search"] ? $_REQUEST["category_search"] : "";
$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "group_name";
$param			=	"mod=$mod&pg=$pg&act=list&orderBy=$orderBy&keysearch=$keysearch&sId=$sId&fId=$fId";

$AttributeObj	=	new Attributes();

switch($_REQUEST['act']) {
	case "list":
		list($rs, $numpad, $cnt, $limitList)	= 	$AttributeObj->listAllAttributes($keysearch,$category_search,$pageNo,$limit,$param,OBJECT, $orderBy);
		$framework->tpl->assign("ATTRIBUTE_LIST", $rs);
		$framework->tpl->assign("ACT", "form");
		$framework->tpl->assign("ATTRIBUTE_NUMPAD", $numpad);
		$framework->tpl->assign("ATTRIBUTE_LIMIT", $limitList);
		$framework->tpl->assign("ATTRIBUTE_SEARCH_TAG", $category_search);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/flyer/tpl/attribute_list.tpl");
		break;
		
	case "form":
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			$attribute_id	=	$req['attribute_id'];			
			if( ($message 	= 	$AttributeObj->AttributeAddedit($req,$sId)) === true ) {
				$action = $req['attribute_id'] ? "Updated" : "Added";
            	setMessage($_REQUEST["sId"]." $action Successfully", MSG_SUCCESS); 
								
				if($attribute_id	== "")
				{
				$attribute_id = $_SESSION['attribute_id']; }
				if(isset($_SESSION['attribute_id']))
				{	unset ($_SESSION['attribute_id']);}
				redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=checkbox_add&attribute_id=$attribute_id&action=$action&sId=$sId&fId=$fId&limit=$limit"));
				
				//redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=list&sId=$sId&fId=$fId&limit=$limit"));
			}
		}
		if($message) {
			$framework->tpl->assign("FORUMCATEGORY", $_POST);
		} elseif($_REQUEST['attribute_id']) {
			$framework->tpl->assign("ATTRIBUTE_VALUE", $AttributeObj->AttributeGet($_REQUEST['attribute_id']));
		}
 		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/flyer/tpl/attribute_form.tpl");
		break;
		
	case "checkbox_add":
		$attribute_id	=	$_REQUEST['attribute_id'];
		if($_SERVER['REQUEST_METHOD'] == "POST") {
		
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req	=	$_POST;
			if( ($message 	= 	$AttributeObj->CheckboxAdd($req,$attribute_id)) === true ) {
				$action = $_REQUEST['action'];
            	setMessage($_REQUEST["sId"]." $action Successfully", MSG_SUCCESS);
			
				redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=list&sId=$sId&fId=$fId&limit=$limit"));
			}
		}
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/flyer/tpl/attribute_checkbox_form.tpl");
		break;	
		
		case "delete":
		extract($_POST);
		if(count($category_id)>0)
		{
		$message=true;
		foreach ($category_id as $attr_id)
			{  
			if($AttributeObj->AttributeDelete($attr_id)==false)
				$message=false;
			}
		}
		if($message==true)
			setMessage($_REQUEST["sId"]."(s) Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage($_REQUEST["sId"]."(s) Can not Deleted!");
		redirect(makeLink(array("mod"=>"flyer", "pg"=>"attributes"), "act=list&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
		break;
		
		
	default: break;	
	

} # Close switch statement

$framework->tpl->display($global['curr_tpl']."/admin.tpl");


?>