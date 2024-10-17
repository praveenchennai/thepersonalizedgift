<?php 
/**
 * Admin Map Config page
 *
 * @author Aneesh Aravindan
 * @package defaultPackage
 */
error_reporting(0);

include_once(FRAMEWORK_PATH."/modules/map/lib/class.map.php");
include_once(FRAMEWORK_PATH."/modules/map/lib/G_Maps.class.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");

#$map	 		= 	 new Map();
$gmap	 		= 	 new G_Maps();
$objUser = new User();


/*
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
*/


switch($_REQUEST['act']) {
    case "property_view":
		$gmap->ELEMENT_ID    = 'map_prp_view';
		$framework->tpl->assign("My_Map", $gmap->showMap());
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/property_view_map.tpl");
        break;
    case "property_add":
		$gmap->ELEMENT_ID    = 'map_add';
		$framework->tpl->assign("query", 'India'); #$_REQUEST['query']
		$framework->tpl->assign("prop_id", $_REQUEST['prop_id']);
		$framework->tpl->assign("My_Map", $gmap->showMap());
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/property_add_map.tpl");
        break;
}
	$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>