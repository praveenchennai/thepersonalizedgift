<?php
/**
* CMS Module Language
*
* @author aneesh
* @package defaultPackage
*/

if($_REQUEST['manage']=="manage"){
	authorize_store();
}else{
	authorize();
}



include_once(FRAMEWORK_PATH."/modules/cms/lib/class.language.php");
include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");

$objAdmin	 = new Admin();
$objLang 	 = new Language();

$req = &$_REQUEST;


switch($_REQUEST['act']) {
	case "delete":
		
		break;

		
	case "ajaxDefault":
		$req = $_REQUEST;
		extract($req);
		
		$CONTENT_STR = " ";
		if ( isset($req['module_id']) && isset($req['content_id']) )	{
			list($CONTENT_VARIABLES,$NUM_PADS) = $objLang->getLangContVariable( $req ); #, $_REQUEST['orderPos'] 
			if( count($CONTENT_VARIABLES) ) {
				$CONTENT_STR       = $objLang->getAjaxResults($CONTENT_VARIABLES,$NUM_PADS);
			}
			
		}
		
		
		echo $CONTENT_STR;
		exit;
		break;
	default:
		$req = $_REQUEST;
		extract($req);
		
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			$module_id = $_POST['moduleid'];
			$content_id= $_POST['contentid'];
			
			if ( $objLang->createArrayFile ( $_POST ) == true ) {
				setMessage("Successfully Executed", MSG_SUCCESS);	
				redirect(makeLink(array("mod"=>"cms", "pg"=>"language"), "module_id=".$module_id."&content_id=".$content_id));	
			}else{
				setMessage("Failed to Execute", MSG_ERROR);	
			}
		
		}
		
		
		$MODULES_ARRAY = $objLang->sectionGetModules();
		$framework->tpl->assign("MODULES_ARRAY_LIST", $MODULES_ARRAY); 
		$LANG_CONT_ARRAY = $objLang->sectionGetLangContent();
		$framework->tpl->assign("LANG_CONT_LIST", $LANG_CONT_ARRAY); 
		
		if ( $req['module_id']>0 && $req['content_id']>0 )	{
			list($CONTENT_VARIABLES,$NUM_PADS) = $objLang->getLangContVariable( $req );
			$framework->tpl->assign("CONTENT_VARIABLES_LIST", $CONTENT_VARIABLES);
			$framework->tpl->assign("NUM_PADS", $NUM_PADS);
		}
		
		
		
        

		break;

}
$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/cms/tpl/language.tpl");
if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}


?>