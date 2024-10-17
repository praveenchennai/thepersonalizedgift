<?php 
/**
 * Admin Module Index page
 *
 * @author sajith
 * @package defaultPackage
 */


authorize();

include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");

$admin = new Admin();

switch($_REQUEST['act']) {
    case "list":
    	$_REQUEST['orderBy'] = $_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : "position:ASC";
        list($rs, $numpad) = $admin->moduleList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("MODULE_LIST", $rs);
        $framework->tpl->assign("MODULE_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/admin/tpl/module_list.tpl");
        break;
	case "referlist":
    	$_REQUEST['orderBy'] = $_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : "position:ASC";
        list($rs, $numpad) = $admin->moduleList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("MODULE_LIST", $rs);
        $framework->tpl->assign("MODULE_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/admin/tpl/module_list.tpl");
        break;
	 case "form":
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $req = &$_REQUEST;
            if( ($message = $admin->moduleAddEdit($req)) === true ) {
            	$action = $req['id'] ? "Updated" : "Added";
            	setMessage("Module $action Successfully", MSG_SUCCESS);
                redirect(makeLink(array("mod"=>"admin", "pg"=>"module"), "act=list"));
            }
            setMessage($message);
        }
        if($message) {
            $framework->tpl->assign("MODULE", $_POST);
        } elseif($_REQUEST['id']) {
            $framework->tpl->assign("MODULE", $admin->moduleGet($_REQUEST['id']));
        }
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/admin/tpl/module_form.tpl");
        break;
    case "delete":
        $admin->moduleDelete($_REQUEST['id']);
        setMessage("Module Deleted Successfully!", MSG_SUCCESS);
        redirect(makeLink(array("mod"=>"admin", "pg"=>"module"), "act=list"));
        break;
		case "drawsettings":
		
		if($_SERVER['REQUEST_METHOD'] == "POST") {
         $req = &$_POST; 
		
	   $message = $admin->DrawvaluesEdit($req);
	   setMessage("Site Drawsetting Range details  updated successfully", MSG_SUCCESS);
		
}   
        $framework->tpl->assign("DRAWVALUES", $admin->getDrawsettingvalues());
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/admin/tpl/drawsettings.tpl");
        break;
	
}
$framework->tpl->display($global['curr_tpl']."/admin.tpl");

?>