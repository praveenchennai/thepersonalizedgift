<?php 
/**
 * CMS Module Add Pages
 *
 * @author sajith
 * @package defaultPackage
 */
session_start();
if($_REQUEST['manage']=="manage"){
	authorize_store();
}else{
	authorize();
}
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");

$cms = new Cms();
if($_REQUEST['manage']=="manage"){

	$AREA_EDIT_HEIGHT	=	400;
	$AREA_EDIT_WIDTH	=	640;
	}
switch($_REQUEST['act']) {
    case "form":
        include_once(SITE_PATH."/includes/areaedit/include.php");
		
        if($_SERVER['REQUEST_METHOD'] == "POST") {	
		
		
					
            $req = &$_REQUEST;	
            if( ($message = $cms->pageAddEdit($req)) === true ) {
				
            	$action = $req['id'] ? "Updated" : "Added";
            	setMessage("CMS Page $action Successfully", MSG_SUCCESS);
                redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>"home_menu"), "act=list&id=".$req['menu_id']."&section_id=".$req['section_id']));
            }
            setMessage($message);
        }
		        
				
        if($message) {
            $_POST['short_content'] = stripslashes($_POST['short_content']);
            $_POST['content'] = stripslashes($_POST['content']);
            $framework->tpl->assign("PAGE", $_POST);
        } elseif($_REQUEST['id']) {             
            $framework->tpl->assign("PAGE", $cms->pageGet($_REQUEST['id']));

        }
        $menuRS = $cms->menuGet($_REQUEST['menu_id']);        
        $framework->tpl->assign("MENU_NAME", $menuRS['name']);
        $framework->tpl->assign("SECTION_ID", $menuRS['section_id']);
		if($_REQUEST['manage']=="manage"){
			$menuid=$_REQUEST['menu_id'];
			$framework->tpl->assign("EDIT_PAGE",$cms->getCmsMenuPermisssion($store_id,$menuid));
		}
	
		if($_REQUEST['mod'] != 'home'){
			editorInit('content');
		}
		
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/cms/tpl/page.tpl");
        break;
    case "delete":
        $cms->pageDelete($_REQUEST['id']);
        setMessage("CMS Page Deleted Successfully!", MSG_SUCCESS);
        redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>"menu"), "act=list&id=".$_REQUEST['menu_id']."&section_id=".$_REQUEST['section_id']));
        break;
		
}


if($_REQUEST['manage']=="manage"){
	//echo $global['curr_tpl']."/store.tpl"; exit;
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	//	echo $global['curr_tpl']."/admin.tpl"; exit;
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}

?>