<?
session_start();
include_once(FRAMEWORK_PATH."/modules/product/lib/class.stock.php");

$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$param			=	"mod=$mod&pg=$pg&act=$act&orderBy=$orderBy&sortOrder=$sortOrder";
$objStock		=	new Stock();
switch($_REQUEST['act']) {
	case "list":
		$orderBy 			= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "";
		list($rs, $numpad)	= 	$objStock->listAllProductStock($pageNo,$global['admin_product_list_count'],$param,OBJECT, $orderBy);
		$framework->tpl->assign("PRODUCT_DESC_LIST", $rs);
		$framework->tpl->assign("PRODUCT_DESC_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/stock_list.tpl");
		break;
	case "update":
		$description_id 	= 	$_REQUEST["description_id"] ? $_REQUEST["description_id"] : "";
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			if( ($message 	= 	$objStock->sockAddEdit($req)) === true ) {
				redirect(makeLink(array("mod"=>"product", "pg"=>"stock"), "act=list"));
			}
			$framework->tpl->assign("MESSAGE", $message);
		}

		if($message) {
			$framework->tpl->assign("TYPE", $_POST);
		} elseif($_REQUEST['description_id']) {
			$framework->tpl->assign("STOCK",$objStock->GetStock($description_id));
		}

		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/stock_form.tpl");
		break;

}

$framework->tpl->display($global['curr_tpl']."/admin.tpl");
?>