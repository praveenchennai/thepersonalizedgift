<?php 
/**
 * template
 *
 * @author sajith
 * @package defaultPackage
 */
 error_reporting(0);
if($_REQUEST['manage']=="manage"){
	authorize_store();
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("PG","newsletter_template") ;
    $framework->tpl->assign("STORE_PERMISSION",$store->getStorePermissionById($store_id,$module_menu_id)) ;
}else{
	authorize();
	$framework->tpl->assign("MOD","newsletter") ;
	$framework->tpl->assign("PG","template") ;
	$STORE = array('hide'=>'Y','add'=>'Y','edit'=>'Y','delete'=>'Y');
	$framework->tpl->assign("STORE_PERMISSION",$STORE) ;
}

include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.newsletter.php");

$newsletter = new Newsletter();

switch($_REQUEST['act']) {
    case "list":
        list($rs, $numpad) = $newsletter->templateList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&orderBy=".$_REQUEST['orderBy'], OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("TEMPLATE_LIST", $rs);
        $framework->tpl->assign("TEMPLATE_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/newsletter/tpl/template_list.tpl");
        break;
    case "form":
        include_once(SITE_PATH."/includes/areaedit/include.php");
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $req = &$_REQUEST;
            if( ($message = $newsletter->templateAddEdit($req)) === true ) {
            	$action = $req['id'] ? "Updated" : "Added";
            	setMessage("Template $action Successfully", MSG_SUCCESS);
                redirect(makeLink(array("mod"=>"newsletter", "pg"=>"template"), "act=list"));
            }
            setMessage($message);
        }
        if($message) {
            $row = $_POST;
        } elseif($_REQUEST['id']) {
            $row = $newsletter->templateGet($_REQUEST['id']);
        }
        $framework->tpl->assign("TEMPLATE", $row);
        editorInit('body_html');
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/newsletter/tpl/template_form.tpl");
        break;
    case "delete":
        $newsletter->templateDelete($_REQUEST['id']);
        setMessage("Template Deleted Successfully!", MSG_SUCCESS);
        redirect(makeLink(array("mod"=>"newsletter", "pg"=>"template"), "act=list"));
        break;
}
if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}
?>