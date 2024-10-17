<?
if($_REQUEST['manage']=="manage"){
	authorize_store();
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("PG","product_made") ;
   $permission_array	=	array(	'add'		=>	$objAdmin->GetmenuPermission($store_id,$fId,'add'),
									'edit'		=>	$objAdmin->GetmenuPermission($store_id,$fId,'edit'),
									'delete'	=>	$objAdmin->GetmenuPermission($store_id,$fId,'delete'));
    $framework->tpl->assign("STORE_PERMISSION",$permission_array) ;
}else{
	authorize();
	$framework->tpl->assign("MOD","product") ;
	$framework->tpl->assign("PG","made") ;
	$permission_array	=	array(	'add'		=>	$objAdmin->GetmenuPermission(0,$fId,'add'),
									'edit'		=>	$objAdmin->GetmenuPermission(0,$fId,'edit'),
									'delete'	=>	$objAdmin->GetmenuPermission(0,$fId,'delete'));
    $framework->tpl->assign("STORE_PERMISSION",$permission_array) ;
}
session_start();
include_once(FRAMEWORK_PATH."/modules/product/lib/class.made.php");

$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
if(empty($limit))
	$_REQUEST["limit"]		=	"20";
$param			=	"mod=$mod&pg=$pg&act=$act&orderBy=$orderBy&fId=$fId&sId=$sId";
$objMade		=	new Made();
switch($_REQUEST['act']) {
	case "list":
		list($rs, $numpad, $cnt, $limitList)	= 	$objMade->listAllMades($pageNo,$limit,$param,OBJECT, $orderBy);
		$framework->tpl->assign("MADE_LIST", $rs);if($_REQUEST['manage']=="manage"){
	authorize_store();
	$framework->tpl->assign("MOD","store") ;
    $framework->tpl->assign("STORE_PERMISSION",$store->getStorePermissionById($store_id,$module_menu_id)) ;
}else{
	authorize();
	$framework->tpl->assign("MOD","product") ;
	$STORE = array('hide'=>'Y','add'=>'Y','edit'=>'Y','delete'=>'Y');
	$framework->tpl->assign("STORE_PERMISSION",$STORE) ;
}

		$framework->tpl->assign("MADE_NUMPAD", $numpad);
		$framework->tpl->assign("MADE_LIMIT", $limitList);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/made_list.tpl");
		break;
	case "form":
		$id 		= 	$_REQUEST["id"] ? $_REQUEST["id"] : "0";
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_REQUEST;
			$fname			=	basename($_FILES['logo_extension']['name']);
			$ftype			=	$_FILES['logo_extension']['type'];
			$tmpname		=	$_FILES['logo_extension']['tmp_name'];
			if( ($message 	= 	$objMade->madeAddEdit($req,$fname,$tmpname,$sId)) === true ) {
				$action = $req['id'] ? "Updated" : "Added";
            	setMessage("$sId $action Successfully", MSG_SUCCESS);
				redirect(makeLink(array("mod"=>"product", "pg"=>"made"), "act=list&fId=$fId&sId=$sId"));
			}
			setMessage($message);
			

		}
		if($message) {
			$framework->tpl->assign("MADE", $_POST);
		} elseif($_REQUEST['id']) {
			$framework->tpl->assign("MADE", $objMade->GetMade($_REQUEST['id']));
		}
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/product/tpl/made_form.tpl");
		break;
	case "delete":
			$i=0;//cannot delete
			$j=0;//deleted
			if(count($zone_id)>0)
			{
			$message=true;
			foreach ($zone_id as $id)
				{
				$status=$objMade->madeDelete($id, $sId);
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
			setMessage("Selected $sId(s) Can not Deleted! since it is assigned to product(s)");
		elseif($i==0 && $j==0)
			setMessage("Please select atleast one $sId to delete &iexcl;");
		elseif($i>0 && $j>0)
			setMessage("Some of the selected $sId(s) Can not Deleted! since it is assigned to product(s)");
		elseif($i==0 && $j>0)	
			setMessage("Selected $sId(s) Deleted Successfully!", MSG_SUCCESS);
		redirect(makeLink(array("mod"=>"product", "pg"=>"made"), "act=list&orderBy=$orderBy&pageNo=$pageNo&limit=$limit&fId=$fId&sId=$sId"));
		break;
}

if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}
?>