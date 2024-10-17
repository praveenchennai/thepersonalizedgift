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

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $req = &$_REQUEST;
    if( ($message = $admin->changePassword($req)) === true ) {
	 setMessage("Password Successfully Changed",MSG_SUCCESS);
       // redirect(makeLink(array("mod"=>$_REQUEST['mod'])));
    }
   
}

$framework->tpl->assign("main_tpl", SITE_PATH."/modules/admin/tpl/change_password.tpl");
$framework->tpl->display($global['curr_tpl']."/admin.tpl");

?>