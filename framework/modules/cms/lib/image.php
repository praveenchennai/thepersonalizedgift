<?php 
/**
 * CMS Module Add Menu
 *
 * @author sajith
 * @package defaultPackage
 */
error_reporting(0);
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

include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");

$cms = new Cms();
switch($_REQUEST['act']) {
    case "list":
          if($_SERVER['REQUEST_METHOD'] == "POST") {
			$fname			=	basename($_FILES['cms_image']['name']);
			$ftype			=	$_FILES['cms_image']['type'];
			$tmpname		=	$_FILES['cms_image']['tmp_name'];
			
            $req = &$_REQUEST;
			$req['fname']	=	$fname;
			$req['tmpname']	=	$tmpname;
			
			  if( ($message = $cms->imageAddEdit($req)) === true ) {
			  
            	$action = $req['id'] ? "Updated" : "Added";
            	setMessage("CMS Image $action Successfully", MSG_SUCCESS);
                redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>"image"), "act=list&section_id=".$req['section_id']));
            }
        setMessage($message);
     }
	//
	 $framework->tpl->assign("SECTION_LIST", $cms->imagesectionCombo());

 		$rs = $cms->imageList($_REQUEST['section_id']);
        $framework->tpl->assign("IMAGE_LIST", $rs);
		
		$rs = $cms->imageDetail($_REQUEST['id']);
		$framework->tpl->assign("IMAGE_DETAIL", $rs);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/cms/tpl/dynamic_image.tpl");
        break;
    case "delete":
        $cms->imageDelete($_REQUEST['id']);
        setMessage("CMS Image Deleted Successfully!", MSG_SUCCESS);
        redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>"image"), "act=list&section_id=".$_REQUEST['section_id']));
        break;
}

if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}

?>