<?php 
/**
 *  Admin Coupon
 *
 * @author Ajith
 * @package defaultPackage
 */ 

  if($_REQUEST['manage']=="manage"){
	authorize_store();
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("PG","extras_states") ;
	$permission_array	=	array(	'add'		=>	$objAdmin->GetmenuPermission($store_id,$fId,'add'),
									'edit'		=>	$objAdmin->GetmenuPermission($store_id,$fId,'edit'),
									'delete'	=>	$objAdmin->GetmenuPermission($store_id,$fId,'delete'));
									
    $framework->tpl->assign("STORE_PERMISSION",$permission_array) ;
}else{
	authorize();
	$framework->tpl->assign("MOD","extras") ;
	$framework->tpl->assign("PG","states") ;
	$permission_array	=	array(	'add'		=>	$objAdmin->GetmenuPermission(0,$fId,'add'),
									'edit'		=>	$objAdmin->GetmenuPermission(0,$fId,'edit'),
									'delete'	=>	$objAdmin->GetmenuPermission(0,$fId,'delete'));
    $framework->tpl->assign("STORE_PERMISSION",$permission_array) ;
}

//authorize();
include_once(FRAMEWORK_PATH."/modules/extras/lib/class.states.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
$objUser = new User();
$states = new States();

$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
switch($_REQUEST['act']) {
    case "list":	
			
    	$_REQUEST['limit'] 	= 	$_REQUEST['limit'] 		? $_REQUEST['limit'] 		: 20;
		$country_id			=	$_REQUEST['country_id'] ? $_REQUEST['country_id'] 	: "";
        $param="mod=$mod&pg=$pg&act=".$_REQUEST['act']."&country_id=$country_id";
		list($rs, $numpad, $cnt, $limitList) = $states->listAllSates($country_id,$_REQUEST['pageNo'], $_REQUEST['limit'], $param, OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("STATE_LIST", $rs);
        $framework->tpl->assign("STATE_NUMPAD", $numpad);
        $framework->tpl->assign("STATE_LIMIT", $limitList);
		 $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/extras/tpl/states_list.tpl");
        break;
    case "form":
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $req = &$_REQUEST;
            if( ($message = $states->stateAddEdit($req)) === true ) {
            	$action = $req['id'] ? "Updated" : "Added";
            	setMessage("State $action Successfully", MSG_SUCCESS);
                redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=list"));
            }
            setMessage($message);
        }        
        if($message) {
            $framework->tpl->assign("COUPON", $_POST);
      	}elseif($_REQUEST['id']) {
        	$stateDetails 		= 	$states->stateGet($_REQUEST['id']);			
            $framework->tpl->assign("STATES", $stateDetails);
		}
		

		
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/extras/tpl/state_form.tpl");
        break;
	case "delete":
		
		extract($_REQUEST);
		
		if(count($id)>0){			
			$message=true;
			foreach ($id as $state_id){				
					if($states->stateDelete($state_id)==false)
					$message=false;
			}
		}		
		if($message==true)
			setMessage("State Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage("State Can not Deleted!");
		 redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']),"act=list"));
		break;   
	
}
if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}
?>