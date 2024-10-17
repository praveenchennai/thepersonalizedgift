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
    if( ($message = $admin->changeEmail($req)) === true ) {
        redirect(makeLink(array("mod"=>"admin")));
    }
    $framework->tpl->assign("MESSAGE", $message);
}
$framework->tpl->assign("EMAIL", $admin->getEmail($_SESSION['adminSess']->id));
$framework->tpl->assign("main_tpl", SITE_PATH."/modules/admin/tpl/set_email.tpl");
$framework->tpl->display($global['curr_tpl']."/admin.tpl");

?>