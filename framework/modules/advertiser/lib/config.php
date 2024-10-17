<?php 
/**
 * Admin Advertiser Config page
 *
 * @author Aneesh Aravindan
 * @package defaultPackage
 */
if($_REQUEST['manage']=="manage"){
	authorize_store();
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("PG","advertiser_config") ;
	
	$framework->tpl->assign("STORE_PERMISSION",$store->getStorePermissionById($store_id,$module_menu_id)) ;
}else{
	authorize();
	$framework->tpl->assign("MOD","advertiser") ;
	$framework->tpl->assign("PG","config") ;
	$STORE = array('hide'=>'Y','add'=>'Y','edit'=>'Y','delete'=>'Y');
	$framework->tpl->assign("STORE_PERMISSION",$STORE) ;
}

include_once(FRAMEWORK_PATH."/modules/advertiser/lib/class.advertiser.php");

$objAdvertiser	 		= 	 new Advertiser();


switch($_REQUEST['act']) {
    case "config":
	
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			$req = &$_REQUEST;
			if( ($message = $objAdvertiser->editConfiguration ( $req )) === true ) {
				setMessage("Advertiser Configuaration Updated Successfully", MSG_SUCCESS);
				redirect(makeLink(array("mod"=>"advertiser", "pg"=>"config"), "act=config"));
			}
				setMessage($message);
		}	

		$CONFIG_DETAILS = $objAdvertiser->getConfiguration();

		$framework->tpl->assign("CONFIG_DETAILS", $CONFIG_DETAILS);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/advertiser/tpl/config_form.tpl");
        break;

}
if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}

?>