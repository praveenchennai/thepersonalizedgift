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

	if(!$_POST['stop_subs'])
	$_POST['stop_subs']='N';

    $req = &$_POST;    
    if( ($message = $admin->configEdit($req)) === true ) {
    	setMessage("Site Preferences updated successfully", MSG_SUCCESS);
        redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>"config"), "&sId=".$req['']));
    }
    setMessage($message);
}

$framework->tpl->assign("CONFIG", $admin->configList());


$framework->tpl->assign("main_tpl", SITE_PATH."/modules/admin/tpl/config.tpl");

$framework->tpl->display($global['curr_tpl']."/admin.tpl");

?>