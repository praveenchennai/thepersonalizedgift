<?php 
//error_reporting(0);
if($_REQUEST['manage']=="manage"){
	authorize_store();
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("PG","newsletter_general") ;
    $framework->tpl->assign("STORE_PERMISSION",$store->getStorePermissionById($store_id,$module_menu_id)) ;
}else{
	authorize();
	$framework->tpl->assign("MOD","newsletter") ;
	$framework->tpl->assign("PG","general") ;
	$STORE = array('hide'=>'Y','add'=>'Y','edit'=>'Y','delete'=>'Y');
	$framework->tpl->assign("STORE_PERMISSION",$STORE) ;
	
}
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
$param			=	"mod=$mod&pg=$pg&act=$act&orderBy=$orderBy&fId=$fId&sId=$sId";
$email = new Email();
switch($_REQUEST['act']) {
	case "list":
		$param			=	"mod=".$_REQUEST["mod"]."&pg=".$_REQUEST["pg"]."&act=".$_REQUEST["act"]."&orderBy=$orderBy&fId=$fId&sId=$sId";
		list($rs, $numpad) = $email->generalList($_REQUEST['pageNo'], 10,$param, OBJECT, $_REQUEST['orderBy']);
		$framework->tpl->assign("GENERAL_LIST", $rs);
		$framework->tpl->assign("GENERAL_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/newsletter/tpl/general_list.tpl");
		break;
		//===================
		case "messagelist":
		
		
		list($rs, $numpad) = $email->messageList($_REQUEST['pageNo'], 10,$param, OBJECT, $_REQUEST['orderBy']);
		$framework->tpl->assign("GENERAL_LIST", $rs);
		$framework->tpl->assign("GENERAL_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/newsletter/tpl/message_list.tpl");
		break;
		//===================
	case "form":
		include_once(SITE_PATH."/includes/areaedit/include.php");
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			$req = &$_REQUEST;
			if( ($message = $email->generalEdit($req)) === true ) {
				setMessage("General Email Updated Successfully", MSG_SUCCESS);
				redirect(makeLink(array("mod"=>"newsletter", "pg"=>"general"), "act=list"));
			}
			setMessage($message);
		}
		if ($_REQUEST['id']) {
			editorInit('body');
		}
		if($message) {
			$framework->tpl->assign("GENERAL", $_POST);
		} elseif($_REQUEST['id']) {
			$framework->tpl->assign("GENERAL", $email->generalGet($_REQUEST['id']));
		}
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/newsletter/tpl/general_form.tpl");
		break;
		//========================
	case "messageform":
		include_once(SITE_PATH."/includes/areaedit/include.php");
		if($_SERVER['REQUEST_METHOD'] == "POST") {
		
			$req = &$_REQUEST;
			
				if( ($message = $email->generalEditMsg($req)) === true ) {
					setMessage("General Messages Updated Successfully", MSG_SUCCESS);
					redirect(makeLink(array("mod"=>"newsletter", "pg"=>"general"), "act=messagelist&sId=General Message"));
				}
			setMessage($message);
		}
		if ($_REQUEST['id']) {
			editorInit('body');
		}
		if($message) {
			$framework->tpl->assign("GENERAL", $_POST);
		} elseif($_REQUEST['id']) {
			$framework->tpl->assign("GENERAL", $email->generalGet($_REQUEST['id']));
		}
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/newsletter/tpl/message_form.tpl");
		break;
		//=============================
	case "database":
		$general 	= $email->generalGet($_REQUEST['id']);
		
		$dbVars 	= $general['database_variables'];
		$dbVarsArr 	= explode("|", $dbVars);
		
		foreach ($dbVarsArr as $dbVar) {
			list($key, $desc) 	= explode("^", $dbVar);
			if($key) {
				$dbVariables[] 	= array("key"=>$key, "desc"=>$desc);
			}
		}
		$framework->tpl->assign("DB_VARIABLES", $dbVariables);
		$framework->tpl->assign("GENERAL", $general);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/newsletter/tpl/general_database.tpl");
		$framework->tpl->display($global['curr_tpl']."/adminPopup.tpl");
		exit;
		break;
	case "data_msg":
		$general 	= $email->generalGet($_REQUEST['id']);
		
		$dbVars 	= $general['database_variables'];
		$dbVarsArr 	= explode("|", $dbVars);
		$i=0;
		foreach ($dbVarsArr as $dbVar) {
			list($key, $desc) 	= explode("^", $dbVar);
			if($key) {
				$dbVariables[] 	= array("key"=>$key, "desc"=>$desc,"numb"=>$i);
				$i=$i+1;
			}
		}
		$framework->tpl->assign("DB_VARIABLES", $dbVariables);
		$framework->tpl->assign("GENERAL", $general);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/newsletter/tpl/general_msgdatabase.tpl");
		$framework->tpl->display($global['curr_tpl']."/adminPopup.tpl");
		exit;
		break;
}
if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}
?>