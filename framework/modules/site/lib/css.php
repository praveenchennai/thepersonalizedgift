<?php 
/**
 * Admin Site
 *
 * @author Ajith
 * @package defaultPackage
 */
	include_once(FRAMEWORK_PATH."/modules/site/lib/include_site.php");
	include_once(FRAMEWORK_PATH."/modules/site/lib/class.template.php");
	$template = new Template();
switch($_REQUEST['act']) {
    case "list":		
		$framework->tpl->assign("SITE_DETAILS", $template->siteGet($site_id));	
    	$_REQUEST['limit'] 	= 	$_REQUEST['limit'] ? $_REQUEST['limit'] : 20;
        list($rs, $numpad, $cnt, $limitList) = $template->cssList($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
     	$framework->tpl->assign("CSS_LIST", $rs);
		$framework->tpl->assign("SITE_ID", $site_id);
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/site/tpl/css_List.tpl");
        break; 
	case "showimage":
		$temp_id	=	$_REQUEST['css_id'];		
		$framework->tpl->assign("CSS_DETAILS", $template->getCss($temp_id));		
		$framework->tpl->display( SITE_PATH."/modules/site/tpl/show_css.tpl");
		exit;
		break;	
	case "assign_css":		
		$site_id	=	$_REQUEST['site_id'];
		$temp_id	=	$_REQUEST['css_id'];		
	  if( ($message =	$template->assignCss($site_id,$temp_id)) === true ) {		  
			setMessage("CSS Assigned Successfully", MSG_SUCCESS);					
		}	
		 redirect(makeLink(array("mod"=>$_REQUEST['mod'],"pg"=>"css"), "act=list"));
		 setMessage($message);
	break;  
}
if($_REQUEST['act']=="showimage"){
	exit;	
}else{
	if($_REQUEST['manage']=="manage"){
		$framework->tpl->display($global['curr_tpl']."/site.tpl");
	}else{
		$framework->tpl->display($global['curr_tpl']."/admin.tpl");
	}
}

?>