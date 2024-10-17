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
	
}
$framework->tpl->display($global['curr_tpl']."/admin.tpl");

?>