<?php 

checkLogin();
include_once(FRAMEWORK_PATH."/modules/order/lib/class.order.php");
include_once(FRAMEWORK_PATH."/modules/extras/lib/class.extras.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.paymenttype.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.shipping.php");
include_once(FRAMEWORK_PATH."/modules/forum/lib/class.forum.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");

$order = new Order();
$typeObj = new paymentType();
$forum = new Forum();	
$user	 	=	new User();	

switch ($_REQUEST['act']) {
	case "past":
		if($_SERVER['REQUEST_METHOD'] == "POST") {
	    	$_REQUEST['pageNo'] = 1;
		}
		$_REQUEST['pageNo'] = $_REQUEST['pageNo'] ? $_REQUEST['pageNo'] : 1;
		$_REQUEST['limit'] = $_REQUEST['limit'] ? $_REQUEST['limit'] : 20;
    	$_REQUEST['orderBy'] = $_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : "date_ordered:DESC";
    	$date_from = isset($_POST['date_from']) ? $_POST['date_from'] : $_REQUEST['date_from'];
    	$date_to   = isset($_POST['date_to'])   ? $_POST['date_to']   : $_REQUEST['date_to'];
		list($rs, $numpad, $count, $limitList) = $order->orderList('',$date_from, $date_to, $_REQUEST['pageNo'], $_REQUEST['limit'], "mod=member&pg=order&act=past&date_from=$date_from&date_to=$date_to", OBJECT, $_REQUEST['orderBy'], $_SESSION['memberid']);
		//echo($limitList);exit;
		###### Modified By Jipson Thomas  On 4 April 2008;
		###### This section is to fetch the order status name from drop_down table......
		if($global["order_status_name_dropdown"]=="Y"){
			$i=0;
			foreach($rs as $re){
				$sts=$order->getDropdownStatus($re->order_status);
				$rs[$i]->order_status_name=$sts;
			$i++;
			}
		}
		###### End of Modification by Jipson......
		$framework->tpl->assign("DATE_FROM", $date_from);
        $framework->tpl->assign("DATE_TO", $date_to);
        $framework->tpl->assign("ORDER_LIST", $rs);
		$framework->tpl->assign("ORDER_NUMPAD", $numpad);
		$framework->tpl->assign("ORDER_LIMIT", $limitList);
		$framework->tpl->assign("SUBMITBUTTON", createButton("SUBMIT","#","check()"));
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/order_past.tpl");
		break;
	case "details":
		
		$framework->tpl->assign("CURRENT_ORDER_DETAILS",$order->getPastOrderDetails($_REQUEST['id']));
		
		$extras = new Extras();
    	
		$table_id = $order->getTableID('orders'); 
		$table_id = $table_id[table_id];
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			$order_id	=	$_REQUEST['order_id'];
			if ($_REQUEST['btn_post']) {
				 $ord_id = $_REQUEST['id'];
				 $req = &$_REQUEST;
				  $req['file_id']= $_REQUEST['id']; 
				   $_REQUEST['id'] = "";
				  if($_SESSION[memberid]!=""){
					 $getID = $_SESSION[memberid];
					  $req['user_id']=$getID;
				 }
				if( ($message = $forum->topicAddEdit($req,$fname,$tmpname,$ord_id,$getID,$table_id)) === true ) {
					setMessage("Comment Added Successfully!", MSG_SUCCESS);
					redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=details&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&id=$ord_id"));
				}else{
					redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=details&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&id=$ord_id&err_msg=$message"));
				}
				
			}elseif ($_REQUEST['submit_post']) {
				 $req = &$_REQUEST;
				if( ($message = $forum->postReplyAdd($req,$getId)) === true ) {
					setMessage("Comment Added Successfully!", MSG_SUCCESS);
					redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=details&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&id=$_REQUEST[file_id]"));
				}else{
					redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=details&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&id=$_REQUEST[file_id]&err_msg2=$message"));
				}
				
			}
		}
    	$ordArray = $order->orderProducts($_REQUEST['id']);
    	$ordDetails = $order->orderDetails($_REQUEST['id'], $_SESSION['memberid']);
		$chkDetails = $order->checkDetails($_REQUEST['id']); 
    	$gift_cert_rs = $extras->historyByOrderid($_REQUEST['id'], 'G');
    	$coupon_rs = $extras->historyByOrderid($_REQUEST['id'], 'C');
    	//print_r($ordArray );exit;
    	$shipping_price = ($coupon_rs->coupon_amounttype=='F') ? 0 : $ordDetails->shipping_price;
		
		$tax_amount = round(($ordArray['total']+$shipping_price) * ($ordDetails->tax) / 100, 2);
		$sub_total = round(($ordArray['total']+$shipping_price) * (100 + $ordDetails->tax) / 100, 2);
		
		if($coupon_rs->coupon_amounttype=='F') {
			$coupon_amount = 0;
		} elseif ($coupon_rs->coupon_amounttype=='A') {
			$coupon_amount = - $coupon_rs->trans_useamount;
		} elseif ($coupon_rs->coupon_amounttype=='P') {
			$coupon_amount = - $sub_total * $coupon_rs->trans_useamount / 100;
		}
		
		$ShippingObj	=	new Shipping();
		$TrackUrl		=	$ShippingObj->getTrackURL($ordDetails->shipping_method, $ordDetails->shipping_transaction_no);

		// for Order Notes Display
		if($_REQUEST['id'])
		{	
				if($table_id>0){
					$all_topic_thread = $forum->getTopicThread($_REQUEST['id'],$table_id);
		   			$all_topic = $forum->AllTopicGetbyOrd($_REQUEST['id']);
					$framework->tpl->assign("USERTOPIC_LIST", $all_topic_thread);
		    		$framework->tpl->assign("CRT",$_REQUEST['cat_id']);			
				}
		 }// if
		
		
		$certificate_amount = - $gift_cert_rs->trans_useamount;
		$total_amount= $sub_total + $coupon_amount + $certificate_amount;
		if ($framework->config["special_discount_field"]=="Y"){ 
			$udet = $user->getUserDetails($_SESSION["memberid"]);
			if($udet["sp_discount"]){
				$disc=$udet["sp_discount"];
			}else{
				$disc=1;
			}
			$dsicount_perc=(1-$disc)*100;
			$discount_amount=(1-$disc)*$ordArray['ProductTotal'];
			$tax_amount 	= round((($ordArray['total']-$discount_amount)+$shipping_price) * ($ordDetails->tax) / 100, 2);
			$sub_total 		= round((($ordArray['total']-$discount_amount)+$shipping_price) * (100 + $ordDetails->tax) / 100, 2);
			$total_amount=$sub_total + $coupon_amount + $certificate_amount;
			$framework->tpl->assign("DISCOUNT",'Y');	
			$framework->tpl->assign("DIS_PERC",$dsicount_perc);	
			$framework->tpl->assign("DIS_AMT",$discount_amount);	
		}
		###### Modified By Jipson Thomas  On 4 April 2008;
		###### This section is to fetch the order status name from drop_down table......
		if($global["order_status_name_dropdown"]=="Y"){
				$sts=$order->getDropdownStatus($ordDetails->order_status);
				$ordDetails->status=$sts;
		}
		###### End of Modification by Jipson......
		$framework->tpl->assign("CART_TOTAL", $ordArray['total']);
		$framework->tpl->assign("SHIPPING_PRICE", $shipping_price > 0 ? number_format($shipping_price, 2, '.', '') : 'Free');
		$framework->tpl->assign("TAX_AMOUNT", $tax_amount);
		$framework->tpl->assign("FIELDS",$typeObj->GetMailCheckFields()); 
		$framework->tpl->assign("CHECK_DETAILS", $chkDetails);
		$framework->tpl->assign("SUB_TOTAL", $sub_total);
		$framework->tpl->assign("COUPON_AMOUNT", $coupon_amount);
		$framework->tpl->assign("CERTIFICATE_AMOUNT", $certificate_amount);
		$framework->tpl->assign("TOTAL_AMOUNT", $total_amount);
    	
		$framework->tpl->assign("TRACK_LINK", $TrackUrl);
		
    	$framework->tpl->assign("ORDER_STATUS", $order->orderStatus());
    	$framework->tpl->assign("ORDER_PRODUCTS", $ordArray);
    	$framework->tpl->assign("ORDER_DETAILS", $ordDetails);
    	$framework->tpl->assign("ORDER_GIFT_CERT", $gift_cert_rs);
    	$framework->tpl->assign("ORDER_COUPON", $coupon_rs);
		$framework->tpl->assign("SUBMITBUTTON", createButton("SUBMIT","#","trackorder()"));
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/order_details.tpl");
		break;
	case "track":
		$framework->tpl->assign("SUBMITBUTTON", createButton("SUBMIT","#","trackorder()"));
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/order_track.tpl");
		break;
}

$framework->tpl->display($global['curr_tpl']."/inner.tpl");

?>