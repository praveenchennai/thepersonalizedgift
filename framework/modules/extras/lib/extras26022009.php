<?php 
/**
 *  Admin Coupon
 *
 * @author Ajith
 * @package defaultPackage
 */ 
authorize();
include_once(FRAMEWORK_PATH."/modules/extras/lib/class.extras.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
$extras = new Extras();
$user = new User();
switch($_REQUEST['act']) {
    case "list":		
    	$_REQUEST['limit'] = $_REQUEST['limit'] ? $_REQUEST['limit'] : 20;
        list($rs, $numpad, $cnt, $limitList) = $extras->listAllCoupons($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("COUPON_LIST", $rs);
        $framework->tpl->assign("COUPON_NUMPAD", $numpad);
        $framework->tpl->assign("COUPON_LIMIT", $limitList);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/extras/tpl/coupon_list.tpl");
        break;
    case "form":
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $req = &$_REQUEST;
            
            if( ($message = $extras->couponAddEdit($req)) === true ) {
            	$action = $req['id'] ? "Updated" : "Added";
            	setMessage("Coupon $action Successfully", MSG_SUCCESS);
                redirect(makeLink(array("mod"=>"extras", "pg"=>"extras"), "act=list&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}"));
            }			
            setMessage($message);
        }        
        if($message) {
            $framework->tpl->assign("COUPON", $_POST);
      	}elseif($_REQUEST['id']) {
        	$extrasDetails 		= 	$extras->couponGet($_REQUEST['id']);			
            $framework->tpl->assign("COUPON", $extrasDetails);
		}

		$Subscriptions	=	$extras->getAllSubscriptions($_REQUEST['id']);
		$framework->tpl->assign('SUBSCRIPTIONS', $Subscriptions);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/extras/tpl/coupon_form.tpl");
        break;
	case "delete":
	extract($_REQUEST);
		if(count($coupon_id)>0){			
			$message=true;
			foreach ($coupon_id as $id){				
					if($extras->couponDelete($id)==false)
					$message=false;
			}
		}		
		if($message==true)
			setMessage("Coupon Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage("Coupon Can not Deleted!");
		 redirect(makeLink(array("mod"=>"extras", "pg"=>"extras"),"act=list"));
		break;   
	case "userlist":				
		list($rs, $numpad) = $user->userList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy'],$_REQUEST["txtsearch"]);
		$framework->tpl->assign("USER_LIST", $rs);
		$framework->tpl->assign("USER_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/member/tpl/member_list.tpl");
      break;
	  case "assignuser":
		$req	=	&$_REQUEST;
		$true	=	$extras->asignUser($req);		
		if($true==1){
	    	setMessage("Coupon Assigned!", MSG_SUCCESS);
		}
		 $framework->tpl->assign("OK", "T");
	 	 $email_id	=	$_REQUEST['user_email'];		
		 $framework->tpl->assign("SECTION_LIST", $extras->couponList());
		 $framework->tpl->assign("EMAIL_ID", $email_id);		 
	     $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/extras/tpl/assign_coupon.tpl");				
	  break;	
	  case "setuser":	 	
	  	$req	=	&$_REQUEST;
		$true	=	$extras->asignUser($req);		
		if($true==1){
	    	setMessage("Coupon Assigned!", MSG_SUCCESS);
		}
		 $framework->tpl->assign("OK", "T");
		 $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/extras/tpl/assign_coupon.tpl");
	  break;
	  case "viewhistory":		  	
	    $extrasDetails 		= 	$extras->couponGet($_REQUEST['id']);
		$AmtType			= 	$extrasDetails['coupon_amounttype']; 
		$framework->tpl->assign("AMOUNT_TYPE", $AmtType);			
		$_REQUEST['limit']  =   $_REQUEST['limit'] ? $_REQUEST['limit'] : 20; 
		$id					=	$_REQUEST['id'];
	    list($rs, $numpad, $cnt, $limitList) = $extras->listHistory($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&id=$id", OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("HISTORY_LIST", $rs);
        $framework->tpl->assign("HISTORY_NUMPAD", $numpad);
        $framework->tpl->assign("HISTORY_LIMIT", $limitList);
		if($AmtType=='A'){
			$sum	=	$extras->getHistorysum($_REQUEST['id'],'C','Y');			
			$framework->tpl->assign("SUM", $sum);
			$framework->tpl->assign("BALANCE", $extrasDetails['coupon_amount']-$sum);
		}
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/extras/tpl/history_list.tpl");
	  break;
	  case "viewcoupon":
	  	$id					=	$_REQUEST['id'];
		$extrasDetails 		= 	$extras->couponGet($_REQUEST['id']);
					
		$user_id			=	$extrasDetails['user_id'];
		$userDetails		=	$user->getUserdetails($user_id);				
		$framework->tpl->assign("COUPON_DETAILS",$extrasDetails);
		$framework->tpl->assign("USER_DETAILS",$userDetails);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/extras/tpl/coupon_details.tpl");
	  break;
	  case "updatehistory":	  
	  	$_REQUEST['trans_no']		=	"wthdn9b";
		$_REQUEST['trans_type']		=	'G';
		$_REQUEST['trans_amount']	=	80;
		$_REQUEST['order_id']		=	'101';
		 $req = &$_REQUEST;
		if( ($message = $extras->updateHistory($req)) === true ) {
			$action = $req['id'] ? "Updated" : "Added";
			setMessage("Coupon $action Successfully", MSG_SUCCESS);
		 }				
         setMessage($message);
	  break;
	  case "usetrans":	
	  		$user_id		= 	$adminSess->id;			
	  		//$transstat 	= 	$extras->useExtrafeatures('ecrjv',200,'C');
			//$transstat 	= 	$extras->couponhistoryByOrderid(101,'G');			
			$transstat 		= 	$extras->giftHistoryByuserid($user_id);
			print_r($transstat);
			//print_r ($transstat);
	  break;
}
$framework->tpl->display($global['curr_tpl']."/admin.tpl");

?>