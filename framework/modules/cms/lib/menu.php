<?php 
/**
 * CMS Module Add Menu
 *
 * @author sajith
 * @package defaultPackage
 */
//error_reporting(0);

if($_REQUEST['manage']=="manage"){
	authorize_store();
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("PG","cms_menu") ;
	$framework->tpl->assign("STORE_PERMISSION",$store->getStorePermissionById($store_id,$module_menu_id)) ;
}else{
	authorize();
	$framework->tpl->assign("MOD","cms") ;
	$framework->tpl->assign("PG","menu") ;
	$STORE = array('hide'=>'Y','add'=>'Y','edit'=>'Y','delete'=>'Y');
	$framework->tpl->assign("STORE_PERMISSION",$STORE) ;
}
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");

$cms = new Cms();

switch($_REQUEST['act']) {
    case "list":
        if($_SERVER['REQUEST_METHOD'] == "POST") {
		
            if($_REQUEST['storeowner_edit_pages']==''){
				$_REQUEST['storeowner_edit_pages']='N';
			}
			$req = &$_REQUEST;
            if( ($message = $cms->menuAddEdit($req)) === true ) {
            	$action = $req['id'] ? "Updated" : "Added";
            	setMessage("CMS Menu $action Successfully", MSG_SUCCESS);
                redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>"menu"), "act=list&section_id=".$req['section_id']));
            }
            setMessage($message);
        }
		
		//code by robin
				global $store_id;
				global $global;
				
				if($global['add_storemenu']==1 &&  $store_id>0)
				{
				 $hide_menu_operations=1;
				}
				else
				{
				 $hide_menu_operations=0;
				}
		//code end
		
		
        $framework->tpl->assign("SECTION_LIST", $cms->sectionCombo());
		
		$section_details=$cms->sectionGet($_REQUEST['section_id']);
		if($section_details['show_sub_store']=='Y')
		{
		 $update_allstores='Y';
		}else
		{
			$update_allstores='N';
		}
		 $framework->tpl->assign("UPDATEALL", $update_allstores);
		 
        $rs = $cms->menuList($_REQUEST['section_id']);
		
        $framework->tpl->assign("MENU_LIST", $rs);
		
		

        if($message) {
            $framework->tpl->assign("MENU", $_POST);
        } elseif($_REQUEST['id']) {
            $framework->tpl->assign('MENU', $cms->menuGet($_REQUEST['id']));
        }


        list($rs, $numpad) = $cms->pageList($_REQUEST['id'], $_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&id=".$_REQUEST['id'], OBJECT, $_REQUEST['orderBy']);
       
	    $framework->tpl->assign("HIDE_OPR", $hide_menu_operations);
        $framework->tpl->assign("PAGE_LIST", $rs);
        $framework->tpl->assign("PAGE_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/cms/tpl/menu.tpl");
        break;
    case "delete":
        $cms->menuDelete($_REQUEST['id']);
        setMessage("CMS Menu Deleted Successfully!", MSG_SUCCESS);
        redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>"menu"), "act=list&section_id=".$_REQUEST['section_id']));
        break;
	case "updateStore":
		/// add store cms page  with most recent super admin page
		$menu_id=$_REQUEST['id'];
		$scetion_id=$_REQUEST['section_id'];
		$cms->updateAllStore($menu_id);
		redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>"menu"), "act=list&section_id=".$_REQUEST['section_id']."&id=".$_REQUEST['id']));
		break;	
}

if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}

?>