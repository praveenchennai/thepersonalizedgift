<?php 
/**
 * Admin section Index page
 *
 * @author sajith
 * @package defaultPackage
 */
//error_reporting(0);
if($_REQUEST['manage']=="manage"){
	authorize_store();
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("PG","cms_section") ;
	
	$framework->tpl->assign("STORE_PERMISSION",$store->getStorePermissionById($store_id,$module_menu_id)) ;
}else{
	authorize();
	$framework->tpl->assign("MOD","cms") ;
	$framework->tpl->assign("PG","section") ;
	$STORE = array('hide'=>'Y','add'=>'Y','edit'=>'Y','delete'=>'Y');
	$framework->tpl->assign("STORE_PERMISSION",$STORE) ;
}
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");

$cms = new Cms();

switch($_REQUEST['act']) {
    case "list":
        list($rs, $numpad) = $cms->sectionList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&orderBy=".$_REQUEST['orderBy'], OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("SECTION_LIST", $rs);
        $framework->tpl->assign("SECTION_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/cms/tpl/section_list.tpl");
        break;
    case "form":
	
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $req = &$_REQUEST;
			
            if( ($message = $cms->sectionAddEdit($req)) === true ) {
            	$action = $req['id'] ? "Updated" : "Added";
            	setMessage("CMS Section $action Successfully", MSG_SUCCESS);
                redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>"section"), "act=list&sId=".$req['sId']));
            }
            setMessage($message);
        }
        if($message) {
            $framework->tpl->assign("SECTION", $_POST);
        } elseif($_REQUEST['id']) {
            $framework->tpl->assign("SECTION", $cms->sectionGet($_REQUEST['id']));
        }
		
	
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/cms/tpl/section_form.tpl");
        break;
    case "delete":
        $cms->sectionDelete($_REQUEST['id']);
        setMessage("CMS Section Deleted Successfully!", MSG_SUCCESS);
        redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>"section"), "act=list&sId=".$_REQUEST['sId']));
        break;
}
if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}

?>