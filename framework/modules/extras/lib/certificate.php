<?php 
/**
 * Admin Coupon
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
        list($rs, $numpad, $cnt, $limitList) = $extras->listallCertificate($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
      	$framework->tpl->assign("CERTIFICATE_LIST", $rs);
        $framework->tpl->assign("CERTIFICATE_NUMPAD", $numpad);
        $framework->tpl->assign("CERTIFICATE_LIMIT", $limitList);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/extras/tpl/certificate_list.tpl");
        break;
	case "form":	
			/*$_REQUEST['product_id']		= 17;
			$_REQUEST['certi_amount']	= 250;      
            $req = &$_REQUEST;
            if( ($message = $extras->certificateAddEdit($req)) === true ) {
            	$action = $req['id'] ? "Updated" : "Added";
            	setMessage("Certificate $action Successfully", MSG_SUCCESS);
               // redirect(makeLink(array("mod"=>"extras", "pg"=>"extras"), "act=list"));
            }
			 setMessage($message);          
			 */
			 if($_REQUEST['cert_prod_id']){
			 	$certProdDetails = $extras->GetcertProdDetails($_REQUEST['cert_prod_id']);
				$framework->tpl->assign("CERTIFICATE_NAME", $certProdDetails[product_id]);
				$framework->tpl->assign("CERTIFICATE_START_DATE", substr($certProdDetails[date_created],0,10));
				$framework->tpl->assign("CERTIFICATE_PRICE", $certProdDetails[price]);
				$framework->tpl->assign("CERTIFICATE_OPTION", $certProdDetails[type_option]);
				$framework->tpl->assign("CERTIFICATE_TIMES", $certProdDetails[no_times]);
				$framework->tpl->assign("CERTIFICATE_DETAILS", $certProddetails);
			 }
			 $framework->tpl->assign("LOAD_PRODUCT", $extras->productCombo());
			 if($_SERVER['REQUEST_METHOD'] == "POST") {
				$req = &$_REQUEST;
				if( ($message = $extras->certificateUpdate($req)) === true ) {
					$action = $req['id'] ? "Updated" : "Added";
					setMessage("Certificate $action Successfully", MSG_SUCCESS);
					redirect(makeLink(array("mod"=>"extras", "pg"=>"certificate"), "act=viewusercertificate"));
				}
				setMessage($message);
			}
			if($message) {
				$framework->tpl->assign("CERTIFICATE_DETAILS", $_POST);
			}elseif($_REQUEST['id']) {
				$certificateDetails = $extras->GetgiftCertificate($_REQUEST['id']);
				$framework->tpl->assign("CERTIFICATE_DETAILS", $certificateDetails);
			}
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/extras/tpl/usercertificate_form.tpl");
      break;
	case "viewusercertificate":		
		$framework->tpl->assign("LOAD_PRODUCT", $extras->productCombo());
		//print($_REQUEST['id']);
		if($_POST['btn_order_search'] and $_REQUEST['order_search'] != ""){
			$rsSearch = $extras->getCertificatesByOrderNum($_REQUEST['order_search']);
		}elseif($_POST['btn_certi_search'] and $_REQUEST['certi_search'] != ""){
			$rsSearch = $extras->getCertificateDetails($_REQUEST['certi_search']);
		}
		list($rs, $numpad, $cnt, $limitList) = $extras->listuserCertificate($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&id=$certificate_id", OBJECT, $_REQUEST['orderBy'],$rsSearch['certi_id']);
		$framework->tpl->assign("USERCERTIFICATE_LIST", $rs);
		//echo "<pre>";
		//print_r($rs);
		$framework->tpl->assign("USERCERTIFICATE_NUMPAD", $numpad);
		$framework->tpl->assign("USERCERTIFICATE_LIMIT", $limitList);
		
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/extras/tpl/usercertificate_list.tpl");
	break;	    
	case "delete":
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
	case "certiDelete":
		if($id){
			if($extras->certiDelete($id))
				$message=true;
			else
				$message=false;
		}
		if($message==true)
			setMessage("Certificate is  Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage("Certificate Cannot be Deleted!");
		 redirect(makeLink(array("mod"=>"extras", "pg"=>"certificate"),"act=viewusercertificate"));
		break;
	case "viewhistory":
	    $giftDetails 		= 	$extras->cetificateGet($_REQUEST['id']);
		$_REQUEST['limit']  =   $_REQUEST['limit'] ? $_REQUEST['limit'] : 20; 
		$id		=	$_REQUEST['id'];
	    list($rs, $numpad, $cnt, $limitList) = $extras->listgiftHistory($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&id=$id", OBJECT, $_REQUEST['orderBy']);
		$framework->tpl->assign("GIFTHISTORY_LIST", $rs);
		for($i=0;$i<count($rs);$i++){
			$orderNumber[$i]= $extras->GetOrderNumber($rs[$i]->order_id);
			$count++;
		}
		$framework->tpl->assign("ORDER_NUM", $orderNumber);
		$certProp = $extras->GetCertiProp($rs[0]->id);
		$framework->tpl->assign("GIFTCERT_TYPE", $certProp[type_option]);
		$framework->tpl->assign("NO_TIMES", $certProp[no_times]);
        $framework->tpl->assign("GIFTHISTORY_NUMPAD", $numpad);
        $framework->tpl->assign("GIFTHISTORY_LIMIT", $limitList);		
		$sum	=	$extras->getHistorysum($_REQUEST['id'],'G');
		$framework->tpl->assign("SUM", $sum);
		if($certProp[type_option] == "one"){
			$balance = 0;
		}elseif($certProp[type_option] == "unlimit"){
			$balance = $giftDetails['certi_amount']-$sum;
		}elseif($certProp[type_option] == "fixed"){
			if($certProp[no_times] == $cnt){
				$balance = 0;
			}else{
				$balance = $giftDetails['certi_amount']-$sum;
			}
//			$count = $certProp[no_times]-$cnt;
		//	$balance = ($giftDetails['certi_amount'] * $count);
		}
		$framework->tpl->assign("BALANCE", $balance);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/extras/tpl/gifthistory_list.tpl");
	  break;
	  case "viewcertificate":	   
	  	$id	=	$_REQUEST['id'];
		$certificateDetails 		= 	$extras->GetgiftCertificate($_REQUEST['id']);		
		$framework->tpl->assign("CERTIFICATE_DETAILS", $certificateDetails);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/extras/tpl/gift_details.tpl");
	  break;
	  case "usetrans":
	  		$transstat 	= 	$extras->useExtrafeatures('rt123',150,'G');
			print_r ($transstat);
	  break;
	  case "certi_update":
	  	$user_id	= 	$adminSess->id;
		$certi_no	=	'wthdn9b';
	  	$val		=	$extras->activateCertificate($user_id,$certi_no);
	  break;
}
$framework->tpl->display($global['curr_tpl']."/admin.tpl");

?>