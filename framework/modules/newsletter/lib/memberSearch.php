<?php 
/**
 * Newsletter
 *
 * @author sajith
 * @package defaultPackage
 */


authorize();

include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.newsletter.php");

$newsletter = new Newsletter();

switch($_REQUEST['act']) {
    case "list":
        list($rs, $numpad, $cnt, $limitList) = $newsletter->memberSearch($_REQUEST['keyword'], $_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act={$_REQUEST['act']}&orderBy={$_REQUEST['orderBy']}&keyword={$_REQUEST['keyword']}&from={$_REQUEST['from']}", OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("MEMBER_LIST", $rs);
        $framework->tpl->assign("MEMBER_NUMPAD", $numpad);
        $framework->tpl->assign("MEMBER_LIMIT", $limitList);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/newsletter/tpl/member_search.tpl");
        break;
}
$framework->tpl->display($global['curr_tpl']."/adminPopup.tpl");

?>