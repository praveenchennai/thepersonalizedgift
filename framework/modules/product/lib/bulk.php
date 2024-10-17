<?
if($_REQUEST['manage']=="manage"){
	authorize_store();
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("PG","product_bulk") ;
    $permission_array	=	array(	'add'		=>	$objAdmin->GetmenuPermission($store_id,$fId,'add'),
									'edit'		=>	$objAdmin->GetmenuPermission($store_id,$fId,'edit'),
									'delete'	=>	$objAdmin->GetmenuPermission($store_id,$fId,'delete'));
    $framework->tpl->assign("STORE_PERMISSION",$permission_array) ;
}else{
	authorize();
	$framework->tpl->assign("MOD","product") ;
	$framework->tpl->assign("PG","bulk") ;
	$permission_array	=	array(	'add'		=>	$objAdmin->GetmenuPermission(0,$fId,'add'),
									'edit'		=>	$objAdmin->GetmenuPermission(0,$fId,'edit'),
									'delete'	=>	$objAdmin->GetmenuPermission(0,$fId,'delete'));
    $framework->tpl->assign("STORE_PERMISSION",$permission_array) ;
}

session_start();
include_once(FRAMEWORK_PATH."/modules/product/lib/class.price.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");

$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
$param			=	"mod=$mod&pg=$pg&act=$act&orderBy=$orderBy&fId=$fId&sId=$sId";
$objPrice		=	new Price();
$objProduct		=	new Product();
switch($_REQUEST['act']) {
	case "list":
		
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			if( ($message 	= 	$objPrice->bulkMassUpdate($req)) === true ) {
				setMessage("$sId Updated for the selected Product(s) Successfully", MSG_SUCCESS);
				}
			else//echo "Message:  ".$message."<br>";
			setMessage($message);
		}
		list($rs, $numpad, $cnt, $limitList)	= 	$objProduct->listAllProduct($pageNo,$_REQUEST['limit'],$param,OBJECT,$orderBy,$store_id);
		$framework->tpl->assign("BULK_LIST", $rs);
		$framework->tpl->assign("BULK_NUMPAD", $numpad);
		$framework->tpl->assign("BULK_LIMIT", $limitList);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/bulk_list.tpl");
		break;
	case "form_list":
		$id = $_REQUEST["id"] ? $_REQUEST["id"] : "";
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "";
		$param	.=	"&id=$id";
		list($rs, $numpad, $cnt, $limitList)	= 	$objPrice->GetBulkofProducts($_REQUEST['id'],$pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy);
			//print_r($rs);
			$framework->tpl->assign("BULK_ITEMS", $rs);
			$framework->tpl->assign("BULK_ITEMS_NUMPAD", $numpad);
			$framework->tpl->assign("BULK_ITEMS_LIMIT", $limitList);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/bulk_form_list.tpl");
		break;
	case "form":
		$id = $_REQUEST["id"] ? $_REQUEST["id"] : "0";
		$bid = $_REQUEST["bid"] ? $_REQUEST["bid"] : "";
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			if( ($message 	= 	$objPrice->bulkAddEdit($req)) === true ) {
				$action = $req['id'] ? "Updated" : "Added";
            	setMessage("$sId $action Successfully", MSG_SUCCESS);
				redirect(makeLink(array("mod"=>"product", "pg"=>"bulk"), "act=form_list&id=$id&limit=$limit&fId=$fId&sId=$sId"));
			}
			setMessage($message);
		}
		if($message) {
			$framework->tpl->assign("BULK", $_POST);
		} elseif($_REQUEST['id']) {
			$framework->tpl->assign("BULK", $objPrice->GetBulk($bid));
		}
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/bulk_form.tpl");
		break;
	case "bulk_delete":
	extract($_REQUEST);
		if(count($id)>0)
			{
			$message=true;
			foreach ($id as $bid)
				{
				//print($bid);
				//echo " bulk_deleted"."<br>";
				if($objPrice->BulkDelete($bid)==false)
					$message=false;
				}
			}
		//exit;
		if($message==true)
			setMessage("$sId Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage("$sId Can not Deleted!");
		redirect(makeLink(array("mod"=>"product", "pg"=>"bulk"), "act=list&id=$id&limit=$limit&fId=$fId&sId=$sId"));
		break;
		
		case "delete":
		if(count($id)>0)
			{
			$message=true;
			foreach ($id as $product_id)
				{
				//print($bid);
				//echo " bulk_deleted"."<br>";
				if($objPrice->BulkDelete(0,$product_id)==false)
					$message=false;
				}
			}
		//exit;
		if($message==true)
			setMessage("$sId Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage("$sId Can not Deleted!");
		redirect(makeLink(array("mod"=>"product", "pg"=>"bulk"), "act=list&limit=$limit&fId=$fId&sId=$sId"));
		
		break;
}

if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}