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
include_once(FRAMEWORK_PATH."/modules/coupon/lib/class.coupon.php");


$objUser = new User();
$states = new States();
$objCoupon = new Coupon();

$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
switch($_REQUEST['act']) {
    case "list":	
			
    	$_REQUEST['limit'] 	= 	$_REQUEST['limit'] 		? $_REQUEST['limit'] 		: 20;
		$country_id			=	$_REQUEST['country_id'] ? $_REQUEST['country_id'] 	: "";
        $param="mod=$mod&pg=$pg&act=".$_REQUEST['act']."&country_id=$country_id";
		
		list($rs, $numpad, $cnt, $limitList) = $objCoupon->getCouponList($_REQUEST['pageNo'], $_REQUEST['limit'], $param, OBJECT, $_REQUEST['orderBy'],$store_id);
		
        $framework->tpl->assign("COUPON_LIST", $rs);
        $framework->tpl->assign("COUPON_NUMPAD", $numpad);
        $framework->tpl->assign("COUPON_LIMIT", $limitList);
		 $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/coupon/tpl/coupon_list.tpl");
        break;
    case "form":
		
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $req = &$_REQUEST;
			
			extract($_POST);
			
			if(!trim($coupon_title))
			{
				$message="Title is required";
			}
			else if(!trim($coupon_code))
			{
				$message="Coupon code is required";
			}
			else if(!trim($coupon_startdate))
			{
				$message="Start date  is required";
			}
			else if(!trim($coupon_min_amount))
			{
				$message="Min Amount to use Coupon  is required";
			}
			else if(!trim($discount_item_id))
			{
				$message="Select the item which discount coupon is used";
			}
			else if(!trim($discount_mode_id))
			{
				$message="Discount Type  is required";
			}
			else if(!is_numeric($coupon_min_amount))
			{
				$message="Invalid Min Amount to use Coupon";
			}
			else if(!is_numeric($coupon_discount))
			{
				$type = ($discount_mode_id==1)? "Percentage": "Amount";
				$message=" Invalid {$type}";
			}
			else if($objCoupon->getCouponDetailsById($coupon_code,'',$store_id,$_REQUEST['id']))
			{
				$copun_det = $objCoupon->getCouponDetailsById($coupon_code,'',$store_id);
				if($copun_det['coupon_code'])
				{
					$message="Invalid coupon code. Please try again another code";
				}
			}
			
			else 
			{
				
				$id = $objCoupon->addEditCoupon($_POST,$_REQUEST['id'],$store_id);
				
				if($id ) {
            		$action = $req['id'] ? "Updated" : "Added";
            		setMessage("State $action Successfully", MSG_SUCCESS);
                	redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=list"));
            	}
				
			}
			
        }  
		      
        if($message) {
            $framework->tpl->assign("COUPON", $_POST);
      	}elseif($_REQUEST['id']) {
        	$couponDetails 		= 	$objCoupon->getCouponDetails($_REQUEST['id']);			
            $framework->tpl->assign("COUPON", $couponDetails);
		}
		
		setMessage($message);
		
		$framework->tpl->assign("COUPON_ITEMS",$objCoupon->getCouponDiscountItems());
		$framework->tpl->assign("COUPON_DISCOUNT_MODE", $objCoupon->getCouponDiscountMode());
		
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/coupon/tpl/coupon_form.tpl");
        break;
	case "delete":
		
		
		extract($_REQUEST);
		
		if(count($coupon_id)>0){			
			$message=true;
			foreach ($coupon_id as $id){				
					if($objCoupon->deleteCoupon($id)==false)
					$message=false;
			}
		}		
		if($message==true)
			setMessage("State Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage("State Can not Deleted!");
		 redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']),"act=list"));
		break;   
	
	case "code_exist":
			
	
		$coupon_code = trim($_REQUEST['code']);
		$coupon_id = trim($_REQUEST['coupon_id']);
		
		$copun_det = $objCoupon->getCouponDetailsById($coupon_code,'',$store_id,$coupon_id);
		if($copun_det['coupon_code'])
		{
			echo "false";
		}
		else
		{
			echo "true";
		}
		
		exit;
		
		break;
	
  case "report":	
			
    	$_REQUEST['limit'] 	= 	$_REQUEST['limit'] 		? $_REQUEST['limit'] 		: 20;
		$country_id			=	$_REQUEST['country_id'] ? $_REQUEST['country_id'] 	: "";
        $param="mod=$mod&pg=$pg&act=".$_REQUEST['act']."&country_id=$country_id";
		
		list($rs, $numpad, $cnt, $limitList) = $objCoupon->getCouponReport($_REQUEST['pageNo'], $_REQUEST['limit'], $param, OBJECT, $_REQUEST['orderBy'],$store_id);
		
         $framework->tpl->assign("REPORT_LIST", $rs);
        $framework->tpl->assign("REPORT_NUMPAD", $numpad);
        $framework->tpl->assign("REPORT_LIMIT", $limitList);
		 $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/coupon/tpl/report.tpl");
        break;	
		
	/*case "store_report":
	
		$arr = array();
		$arr['order_id'] = '134';
		$arr['order_number'] = '134';
		$arr['coupon_id'] = '134';
		$arr['curr_date'] = date("Y-m-d H:i:s");
		$arr['user_id'] = '134';
		$arr['store_id'] = '134';
		$arr['coupon_type'] = 'P';
		$arr['substract_from'] = 'P';
		$arr['amount_discount'] = 3.00;
		$arr['coupon_key'] = 'P111';
			
		$objCoupon->saveReport($arr);
		
		break;*/
				
	
}
if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}
?>