<?php 
/**
 * Admin Module Index page
 *
 * @author sajith
 * @package defaultPackage
 */


authorize();
include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");
$framework->tpl->assign("GLOBAL_VAR",$global);
$framework->tpl->display($global['curr_tpl']."/admin.tpl");

?>


