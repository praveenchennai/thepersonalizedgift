<?php 
/**
 * Newsletter
 *
 * @author sajith
 * @package defaultPackage
 */


include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.newsletter.php");

$newsletter = new Newsletter();

$send = $newsletter->send($_REQUEST['id'], $_REQUEST['page']);

echo $_REQUEST['id']."|".$send;
exit;

?>