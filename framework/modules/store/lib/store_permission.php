<?
authorize();

include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");

$store = new Store();
$admin = new Admin();

switch($_REQUEST['act'])
{
case "listPermission" :

    	$_REQUEST['limit'] = $_REQUEST['limit'] ? $_REQUEST['limit'] : 20;
        list($rs, $numpad, $cnt, $limitList) = $store->storeList($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&sId=$sId&fId=$fId", OBJECT, $_REQUEST['orderBy']);


       
        $framework->tpl->assign("STORE_LIST", $rs);
        $framework->tpl->assign("STORE_NUMPAD", $numpad);
        $framework->tpl->assign("STORE_LIMIT", $limitList);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/store/tpl/store_permission.tpl");
        break;

case "addPermission":

        //To display the checked ones
		
        $rslt = $store->getStorePermission($_REQUEST[id]);
        $framework->tpl->assign("STORE_PERMISSION", $rslt);

		//echo "<pre>";
		//print_r($rslt);
		//echo "<pre>";
		
		
		//Listing of modules and menus
		$id = $_REQUEST['id'];
		$framework->tpl->assign("STORE_DETAILS",$store->storeGet ($id));
        $r = $admin->getModule();
		foreach($r as $val)
		{
		$array1[$val->id] = $val->name;
		$array2[$val->id] = $admin->getModuleMenu($val->id);
		}

		$framework->tpl->assign("MENU",$array1);
		$framework->tpl->assign("SUB_MENU",$array2);


		//submission
      
	    if($_SERVER['REQUEST_METHOD']=="POST")
		{
		$req = &$_REQUEST;
        $action = $store->menuPermissionAddEdit($req,$sId,$array1,$array2);		
       	setMessage("$sId $action Successfully", MSG_SUCCESS);
        redirect(makeLink(array("mod"=>"store", "pg"=>"store_permission"), "act=listPermission&id=$id&sId=$sId&fId=$fId"));
		}

		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/store/tpl/menu_permission.tpl");
		break;   
		
	case "add_default":

        //To display the checked ones
		
        $rslt = $store->getStorePermission(0);
		
        $framework->tpl->assign("STORE_PERMISSION", $rslt);

		//echo "<pre>";
		//print_r($rslt);
		//echo "<pre>";
		
		
		//Listing of modules and menus
		$id = 0;
		$framework->tpl->assign("STORE_DETAILS",$store->storeGet ($id));
        $r = $admin->getModule();
		foreach($r as $val)
		{
		$array1[$val->id] = $val->name;
		$array2[$val->id] = $admin->getModuleMenu($val->id);
		}

		$framework->tpl->assign("MENU",$array1);
		$framework->tpl->assign("SUB_MENU",$array2);


		//submission
      
	    if($_SERVER['REQUEST_METHOD']=="POST")
		{
		$req = &$_REQUEST;
        $action = $store->menuPermissionAddEdit($req,$sId,$array1,$array2);		
       	setMessage("$sId $action Successfully", MSG_SUCCESS);
        redirect(makeLink(array("mod"=>"store", "pg"=>"store_permission"), "act=listPermission&sId=$sId&fId=$fId"));
		}

		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/store/tpl/menu_permission.tpl");
		break;   
}
$framework->tpl->display($global['curr_tpl']."/admin.tpl");

?>