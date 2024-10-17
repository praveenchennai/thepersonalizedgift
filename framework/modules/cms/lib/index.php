<?php 
/**
 * CMS Module Index page
 *
 * @author sajith
 * @package defaultPackage
 */


authorize();

include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");

$framework->tpl->display($global['curr_tpl']."/admin.tpl");

?>
