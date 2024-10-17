<?php 

if($_REQUEST['manage']=="manage"){
	authorize_store();
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("PG","cms_menu") ;
	
	$framework->tpl->assign("STORE_PERMISSION",$store->getStorePermissionById($store_id,$module_menu_id)) ;
}else{
	authorize();
	$framework->tpl->assign("MOD","cms") ;
	$framework->tpl->assign("PG","drop_down") ;
	$STORE = array('hide'=>'Y','add'=>'Y','edit'=>'Y','delete'=>'Y');
	$framework->tpl->assign("STORE_PERMISSION",$STORE) ;
}

include_once(FRAMEWORK_PATH."/modules/cms/lib/class.dropdown.php");

$DropdownObj	=	new Dropdown();


$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : 10;
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : 1;
$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "label";


switch($_REQUEST['act']) {
    
	case "dropdownlist":
		list($rs, $numpad) = $DropdownObj->getDropDowns($_REQUEST['pageNo'], $limit, "mod={$_REQUEST['mod']}&pg={$_REQUEST['pg']}&act={$_REQUEST['act']}&sId={$_REQUEST['sId']}", OBJECT, $_REQUEST['orderBy']);
		$framework->tpl->assign("PAGE_LIST", $rs);
        $framework->tpl->assign("PAGE_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/cms/tpl/customdropdown.tpl");
		break;
	
	case "optionlist":
		$dropdownid			=	$_REQUEST['id'];
		list($rs, $numpad, $count, $limitList) 	=	$DropdownObj->getOptionsOfDropdown($dropdownid, $pageNo, $limit, "mod={$_REQUEST['mod']}&pg={$_REQUEST['pg']}&act={$_REQUEST['act']}&sId={$_REQUEST['sId']}&limit=$limit&id={$_REQUEST['id']}", OBJECT, $_REQUEST['orderBy']);
		$framework->tpl->assign("PAGE_LIST", $rs);
        $framework->tpl->assign("PAGE_NUMPAD", $numpad);
		$framework->tpl->assign("LIST_LIMIT", $limitList);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/cms/tpl/optionslist.tpl");
		break;
	
	case "addoption":
		$Submit		=	$_REQUEST['Submit'];
		if($Submit == 'Submit' && $_SERVER['REQUEST_METHOD'] == 'POST') {
			$msg	=	$DropdownObj->validateOption($_REQUEST);
			if($msg === TRUE) {
				$StatusMsg	=	$DropdownObj->addEditOption($_REQUEST);
				setMessage($StatusMsg, MSG_SUCCESS);
				redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=optionlist&id={$_REQUEST['id']}&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&limit={$_REQUEST['limit']}"));
			} else {
				setMessage($msg);
				$framework->tpl->assign("FORM_VALUES", $_REQUEST);
			}
		}
		
		if($_REQUEST['optionid'] != '') {
			$Optiondetails	=	$DropdownObj->getOptiondetailsFromId($_REQUEST['optionid']);
			$framework->tpl->assign("FORM_VALUES", $Optiondetails);
		}
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/cms/tpl/addoptionform.tpl");
		break;
	
	case "delete":
		$DropdownObj->removeOption($_REQUEST['optionid']);
		setMessage('Option Removed successfully.', MSG_SUCCESS);
		redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=optionlist&id={$_REQUEST['id']}&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&limit={$_REQUEST['limit']}"));
		break;	
		
	
}

if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}

?>