<?
session_start();
include_once(FRAMEWORK_PATH."/modules/product/lib/class.type.php");

$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
$param			=	"mod=$mod&pg=$pg&act=$act&orderBy=$orderBy";
$objType		=	new Type();
switch($_REQUEST['act']) {
	case "list":
		list($rs, $numpad)	= 	$objType->listAllOptions($pageNo,$global['admin_type_list_count'],$param,OBJECT, $orderBy);
		$framework->tpl->assign("TYPE_LIST", $rs);
		$framework->tpl->assign("TYPE_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/type_list.tpl");
		break;
	case "form":
		$id 		= 	$_REQUEST["id"] ? $_REQUEST["id"] : "";
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			if( ($message 	= 	$objType->typeAddEdit($req,$fname,$tmpname)) === true ) {
				redirect(makeLink(array("mod"=>"product", "pg"=>"type"), "act=list"));
			}
			$framework->tpl->assign("MESSAGE", $message);
		}
		if($message) {
			$framework->tpl->assign("TYPE", $_POST);
		} elseif($_REQUEST['id']) {
			$framework->tpl->assign("TYPE", $objType->GetType($_REQUEST['id']));
		}
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/type_form.tpl");
		break;
	case "delete":
		if( ($message 	= 	$objType->typeDelete($_REQUEST['id'])) === true ) {
			redirect(makeLink(array("mod"=>"product", "pg"=>"type"), "act=list"));
		}
		else
		{
			$framework->tpl->assign("MESSAGE", $message);
			list($rs, $numpad)	= 	$objType->listAllOptions($pageNo,$global['admin_type_list_count'],$param,OBJECT, $orderBy);
			$framework->tpl->assign("TYPE_LIST", $rs);
			$framework->tpl->assign("TYPE_NUMPAD", $numpad);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/type_list.tpl");
		}
		break;
}

$framework->tpl->display($global['curr_tpl']."/admin.tpl");
?>