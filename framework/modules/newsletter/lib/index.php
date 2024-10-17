<?php 
/**
 * CMS Module Index page
 *
 * @author sajith
 * @package defaultPackage
 */



authorize();

include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.newsletter.php");

$framework->tpl->display($global['curr_tpl']."/admin.tpl");

?>
