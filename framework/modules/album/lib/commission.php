<?php 
/**
 * Commission
 *
 * @author sajith
 * @package defaultPackage
 */


authorize();

include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");

$album = new Album();

switch($_REQUEST['act']) {
    case "list":
        list($rs, $numpad) = $album->commissionList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&orderBy=".$_REQUEST['orderBy'], OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("COMMISSION_LIST", $rs);
        $framework->tpl->assign("COMMISSION_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/commission_list.tpl");
        break;
    case "form":
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $req = &$_REQUEST;
            if( ($message = $album->commissionAddEdit($req)) === true ) {
                redirect(makeLink(array("mod"=>"album", "pg"=>"commission"), "act=list"));
            }
            $framework->tpl->assign("MESSAGE", $message);
        }
        if($message) {
            $framework->tpl->assign("COMMISSION", $_POST);
        } elseif($_REQUEST['cat_id']) {
            $framework->tpl->assign("COMMISSION", $album->commissionGet($_REQUEST['cat_id'], $_REQUEST['type']));
        }
        $framework->tpl->assign("CATEGORY_LIST", $album->albumSectionList());
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/commission_form.tpl");
        break;
    case "delete":
        $album->commissionDelete($_REQUEST['cat_id'], $_REQUEST['type']);
        redirect(makeLink(array("mod"=>"album", "pg"=>"commission"), "act=list"));
        break;
}
$framework->tpl->display($global['curr_tpl']."/admin.tpl");

?>