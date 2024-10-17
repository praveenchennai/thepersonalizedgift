<?
if($_REQUEST['manage']=="manage"){
	authorize_store();
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("PG","product_price") ;
    $permission_array	=	array(	'add'		=>	$objAdmin->GetmenuPermission($store_id,$fId,'add'),
									'edit'		=>	$objAdmin->GetmenuPermission($store_id,$fId,'edit'),
									'delete'	=>	$objAdmin->GetmenuPermission($store_id,$fId,'delete'));
    $framework->tpl->assign("STORE_PERMISSION",$permission_array) ;
}else{
	authorize();
	$framework->tpl->assign("MOD","product") ;
	$framework->tpl->assign("PG","price") ;
	$permission_array	=	array(	'add'		=>	$objAdmin->GetmenuPermission(0,$fId,'add'),
									'edit'		=>	$objAdmin->GetmenuPermission(0,$fId,'edit'),
									'delete'	=>	$objAdmin->GetmenuPermission(0,$fId,'delete'));
    $framework->tpl->assign("STORE_PERMISSION",$permission_array) ;
}

session_start();
include_once(FRAMEWORK_PATH."/modules/product/lib/class.price.php");

$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
$param			=	"mod=$mod&pg=$pg&act=$act&orderBy=$orderBy&fId=$fId&sId=$sId";
if(empty($limit))
	$_REQUEST["limit"]		=	"20";
$objPrice		=	new Price();
switch($_REQUEST['act']) {
	case "list":
		list($rs, $numpad, $cnt, $limitList)	= 	$objPrice->listAllPrices($pageNo,$limit,$param,OBJECT, $orderBy);
		$framework->tpl->assign("PRICE_LIST", $rs);
		$framework->tpl->assign("PRICE_NUMPAD", $numpad);
		$framework->tpl->assign("PRICE_LIMIT", $limitList);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/price_list.tpl");
		break;
	case "form":
		$id = $_REQUEST["id"] ? $_REQUEST["id"] : "0";
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			if( ($message 	= 	$objPrice->priceAddEdit($req)) === true ) {
				$action = $req['id'] ? "Updated" : "Added";
            	setMessage("$sId $action Successfully", MSG_SUCCESS);
				redirect(makeLink(array("mod"=>"product", "pg"=>"price"), "act=list&limit=$limit&fId=$fId&sId=$sId"));
			}
			setMessage($message);
		}
		if($message) {
			$framework->tpl->assign("PRICE", $_POST);
		} elseif($_REQUEST['id']) {
			$framework->tpl->assign("PRICE", $objPrice->GetPrice($_REQUEST['id']));
		}
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/price_form.tpl");
		break;
	case "delete":
			$i=0;//cannot delete
			$j=0;//deleted
		if(count($price_id)>0)
			{
			
			foreach ($price_id as $id)
				{
				$status=$objPrice->priceDelete($id);
					if($status['status']==false)
					{
					$i++;
					}
				else
					{
					$j++;
					}
				}
			}
		if($i>0 && $j==0)
			setMessage("Selected $sId(s) Can not Deleted! Since it is Assigned to Product(s)");
		elseif($i==0 && $j==0)
			setMessage("Please Select atleast one $sId to Delete &iexcl;");
		elseif($i>0 && $j>0)
			setMessage("Some of the Selected $sId(s) Can not Deleted! since it is Assigned to Product(s)");
		elseif($i==0 && $j>0)	
			setMessage("Selected $sId(s) Deleted Successfully!", MSG_SUCCESS);			
		redirect(makeLink(array("mod"=>"product", "pg"=>"price"), "act=list&pageNo=$pageNo&limit=$limit&limit=$limit&fId=$fId&sId=$sId"));
		break;
}

if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}
?>