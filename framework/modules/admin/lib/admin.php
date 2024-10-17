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

        list($rs, $numpad) = $admin->adminList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("ADMIN_LIST", $rs);
        $framework->tpl->assign("ADMIN_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/admin/tpl/admin_list.tpl");
        break;
    case "form":
	
        if($_SERVER['REQUEST_METHOD'] == "POST") {
		
            $req = &$_REQUEST;
            if( ($message = $admin->adminAddEdit($req)) === true) {
		
            	$action = $req['id'] ? "Updated" : "Added";
            	setMessage("Admin User $action Successfully", MSG_SUCCESS);
                redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>"admin"), "act=list"));
            }
            setMessage($message);
        }
        if($message) {
            $framework->tpl->assign("ADMIN", $_POST);
        } elseif($_REQUEST['id']) {
            $framework->tpl->assign("ADMIN", $admin->adminGet($_REQUEST['id']));
        }
        $framework->tpl->assign("MODULE_LIST", $admin->adminModuleList($_REQUEST['id']));
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/admin/tpl/admin_form.tpl");
        break;
		
    case "delete":
        $admin->adminDelete($_REQUEST['id']);
        setMessage("Admin User Deleted Successfully!", MSG_SUCCESS);
        redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>"admin"), "act=list"));
        break;
}
$framework->tpl->display($global['curr_tpl']."/admin.tpl");

?>