<?
session_start();
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");

$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
$param			=	"mod=$mod&pg=$pg&act=$act&orderBy=$orderBy";
$objCategory	=	new Category();
switch($_REQUEST['act']) {
	case "list":
		$id = $_REQUEST["id"] ? $_REQUEST["id"] : "";
		$param	.=	"&id=$id";
		list($rs, $numpad, $cnt, $limitList)	= 	$objCategory->GetBulkofCategory($_REQUEST['id'],$pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy);
			$framework->tpl->assign("BULK_ITEMS", $rs);
			$framework->tpl->assign("BULK_ITEMS_NUMPAD", $numpad);
			$framework->tpl->assign("BULK_ITEMS_LIMIT", $limitList);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/category/tpl/bulk_form_list.tpl");
		break;
	case "form":
		$id = $_REQUEST["id"] ? $_REQUEST["id"] : "0";
		$bid = $_REQUEST["bid"] ? $_REQUEST["bid"] : "";
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			if( ($message 	= 	$objCategory->bulkAddEditCategory($req)) === true ) {
				$action = $req['id'] ? "Updated" : "Added";
            	setMessage("$sId for the categiry $action Successfully", MSG_SUCCESS);
				redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=list&id=$id"));
			}
			setMessage($message);
		}
		if($message) {
			$framework->tpl->assign("BULK", $_POST);
		} elseif($_REQUEST['id']) {
			$framework->tpl->assign("BULK", $objCategory->GetCategoryBulk($bid));
		}
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/category/tpl/bulk_form.tpl");
		break;
	case "delete":
		if(count($_REQUEST['id'])>0)
			{
			$message=true;
			foreach ($_REQUEST['id'] as $b_id)
				{
				if($objCategory->BulkDelete($b_id,0)==false)
					$message=false;
				}
			}
		if($message==true)
			setMessage("$sId for the categiry Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage("$sId for the categiry Can not Deleted!");
		redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=list&id=".$_REQUEST['cat_id']));
		
		break;
}

//$framework->tpl->display($global['curr_tpl']."/admin.tpl");
if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}
?>