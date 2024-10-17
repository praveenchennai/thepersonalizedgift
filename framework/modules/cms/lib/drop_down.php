<?php 
/**
 * CMS Module Add Menu
 *
 * @author sajith
 * @package defaultPackage
 */
error_reporting(0);
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
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");

include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");

$cms = new Cms();

switch($_REQUEST['act']) {
    case "list":
		if($_REQUEST['value_id']){
			//die("sd: ".$_REQUEST['value_id']);
			list($rs, $numpad) = $cms->dropdownGet($_REQUEST['value_id'], $_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&id=".$_REQUEST['id'], OBJECT, $_REQUEST['orderBy']);
        	$framework->tpl->assign("VALUE_LIST", $rs);
		}
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $req = &$_REQUEST;
            if( ($message = $cms->groupvalueAddEdit($req)) === true ) {
            	$action = $req['id'] ? "Updated" : "Added";
            	setMessage("CMS Menu $action Successfully", MSG_SUCCESS);
                redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>"drop_down"), "act=list&section_id=".$req['section_id']."&id=".$_REQUEST['id']));
            }
            setMessage($message);
        }

        $framework->tpl->assign("SECTION_LIST", $cms->sectionCombo());

        $rs = $cms->menuList($_REQUEST['section_id']);
        $framework->tpl->assign("MENU_LIST", $rs);

        if($message) {
            $framework->tpl->assign("MENU", $_POST);
        } elseif($_REQUEST['id']) {
            $framework->tpl->assign('MENU', $cms->menuGet($_REQUEST['id']));
        }

        list($rs, $numpad) = $cms->groupvalueGet($_REQUEST['id'], $_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&id=".$_REQUEST['id'], OBJECT, $_REQUEST['orderBy']);
        if($global['health_care']=='1'){
			$framework->tpl->assign("HEALTH_CARE",$global['health_care']);
		}
		$framework->tpl->assign("PAGE_LIST", $rs);
        $framework->tpl->assign("PAGE_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/cms/tpl/drop_down.tpl");
        break;
    case "delete":
        $cms->groupvalueDelete($_REQUEST['value_id']);
        setMessage("CMS Menu Deleted Successfully!", MSG_SUCCESS);
        redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>"drop_down"), "act=list&section_id=".$_REQUEST['section_id']."&id=".$_REQUEST['id']));
        break;
}

if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}

?>