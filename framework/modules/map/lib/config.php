<?php 
/**
 * Admin Map Config page
 *
 * @author Aneesh Aravindan
 * @package defaultPackage
 */
error_reporting(0);
if($_REQUEST['manage']=="manage"){
	authorize_store();
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("PG","map_config") ;
	
	$framework->tpl->assign("STORE_PERMISSION",$store->getStorePermissionById($store_id,$module_menu_id)) ;
}else{
	authorize();
	$framework->tpl->assign("MOD","map") ;
	$framework->tpl->assign("PG","config") ;
	$STORE = array('hide'=>'Y','add'=>'Y','edit'=>'Y','delete'=>'Y');
	$framework->tpl->assign("STORE_PERMISSION",$STORE) ;
}

include_once(FRAMEWORK_PATH."/modules/map/lib/class.map.php");
include_once(FRAMEWORK_PATH."/modules/map/lib/G_Maps.class.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");

$map	 		= 	 new Map();
$gmap	 		= 	 new G_Maps();
$objUser = new User();



$CONFIG_DETAILS = $map->getConfiguration();
foreach ($CONFIG_DETAILS as $Config) {
  if ( $Config['map_field'] == 'key_value' ) {
  	 $gmap->GOOGLE_MAP_KEY    =   $Config['map_value'];
  }
  if ( $Config['map_field'] == 'map_api_url' ) {
  	 $gmap->GOOGLE_API_URL    =   $Config['map_value'];
  }
  if ( $Config['map_field'] == 'geo_code_url' ) {
  	 $gmap->GEOCODES_URL    =   $Config['map_value'];
  }
}



switch($_REQUEST['act']) {
    case "config":
	
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			$req = &$_REQUEST;
			if( ($message = $map->editConfiguration ( $req )) === true ) {
				setMessage("Map Configuaration Updated Successfully", MSG_SUCCESS);
				redirect(makeLink(array("mod"=>"map", "pg"=>"config"), "act=config"));
			}
				setMessage($message);
		}	
		
		$framework->tpl->assign("CONFIG_DETAILS", $CONFIG_DETAILS);
		/*
		$LOCATION_DETAILS = $map->getConfiguration('location');
		$framework->tpl->assign("LOCATION_DETAILS", $LOCATION_DETAILS);
		*/
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/map/tpl/config_form.tpl");
        break;
	case "admin_view":
	    $framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
		
		if ( ! $_SESSION["My_Map"] )
		{
			$gmap->ELEMENT_ID    = 'map_view';
			$framework->tpl->assign("My_Map", $gmap->showMap());
			$_SESSION["My_Map"] = $gmap->showMap();
		} else
		{
		    $framework->tpl->assign("My_Map", $_SESSION["My_Map"]);
		}
		
	    $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/map/tpl/map_view.tpl");
		break;
}
if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}

?>