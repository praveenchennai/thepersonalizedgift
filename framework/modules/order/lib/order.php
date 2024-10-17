<?php 
/**
 * Admin order management
 *
 * @author sajith
 * @package defaultPackage
 */

include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.order.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/extras/lib/class.extras.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.paymenttype.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/cart/lib/class.cart.php");
include_once(FRAMEWORK_PATH."/modules/forum/lib/class.forum.php");
include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");
include_once(FRAMEWORK_PATH."/modules/ajax_editor/lib/class.editor.php");
		
		

$store		=	new Store();
$order 		= 	new Order();
$typeObj 	= 	new paymentType();
$user	 	=	new User();	
$product	=	new Product();
$cart 			= 	new Cart();
$forum 		= new Forum();	
$objAdmin 	= new Admin();
$ajax_editor 	= 	new Ajax_Editor();
//exit;
       
	    $fId=$_REQUEST['fId'];
		$menu_id_arr      = $objAdmin->getMenuById($fId);
		$menu_id          = $menu_id_arr['module_id'];
		$order_fields = $objAdmin->GetFields($menu_id);
		$framework->tpl->assign("ORDER_FIELDS",$order_fields); 
		
switch($_REQUEST['act']) {
    case "list":

			if($_REQUEST['manage']){
			authorize_store();
		}
	
    	if($_SERVER['REQUEST_METHOD'] == "POST") {
	    	$_REQUEST['pageNo'] = 1;
		}
		$sid = isset($_REQUEST['order_status']) ? $_REQUEST['order_status'] : "";		
		if(isset($_POST['order_status'])){		
			$sid =$_POST['order_status'];
		}				
		$archieve = $_REQUEST['archieve_id'];
		if(count($archieve)>0){		
			$order->ChangetoArchieve($archieve);
			setMessage("Moved to Archive Successfully!!", MSG_SUCCESS);
		}
    	$_REQUEST['limit'] = $_REQUEST['limit'] ? $_REQUEST['limit'] : 20;
    	$_REQUEST['orderBy'] = $_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : "o.date_ordered:DESC";
    	$date_from = isset($_POST['date_from']) ? $_POST['date_from'] : $_REQUEST['date_from'];
    	$date_to   = isset($_POST['date_to']) ? $_POST['date_to']   : $_REQUEST['date_to'];
        list($rs1, $numpad, $cnt, $limitList) = $order->orderList($sid, $date_from, $date_to, $_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&date_from=$date_from&date_to=$date_to&order_status=$sid", OBJECT, $_REQUEST['orderBy'],'',$store_id);


		foreach($rs1 as $key=>$val)
		{
			$ipn_log= $order->getIpnStatusByOrderSessId($val->order_sess_id);
			$rs1[$key]->ps=$ipn_log[0]->ps;
			$rs1[$key]->payment_status=$ipn_log[0]->payment_status;
			
		}
		
		

		/**
		 * The following code is for changing the display view of online orders as of Manual order
		 */
		$framework->tpl->assign("LISTING_TYPE", $order->config['online_order_listing']);
		$framework->tpl->assign("DATE_FROM", $date_from);
        $framework->tpl->assign("DATE_TO", $date_to);
        $framework->tpl->assign("ORDER_LIST", $rs1);
        $framework->tpl->assign("ORDER_NUMPAD", $numpad);
        $framework->tpl->assign("ORDER_LIMIT", $limitList);
		$stat_array = array("id"=>array("0"=>"4"),"name"=>array("0"=>"Archive Orders"));		
		$framework->tpl->assign("ORDER_STATUS",$stat_array);
		$framework->tpl->assign("CURRENT_STATUS", $sid);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/order_list.tpl");
        break;
		
		//==================================
		
		 case "manual_order_list":	
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				$_REQUEST['pageNo'] = 1;
			}
			$sid = isset($_REQUEST['order_status']) ? $_REQUEST['order_status'] : "";		
			if(isset($_POST['order_status'])){		
				$sid =$_POST['order_status'];
			}				
			$archieve = $_REQUEST['archieve_id'];
			if(count($archieve)>0){		
				$order->ChangetoArchieve1($archieve);
				setMessage("Moved to Archive Successfully!!", MSG_SUCCESS);
			}
			$_REQUEST['limit'] = $_REQUEST['limit'] ? $_REQUEST['limit'] : 20;
			$_REQUEST['orderBy'] = $_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : "date_ordered:DESC";
			$date_from = isset($_POST['date_from']) ? $_POST['date_from'] : $_REQUEST['date_from'];
			$date_to   = isset($_POST['date_to']) ? $_POST['date_to']   : $_REQUEST['date_to'];
			list($rs, $numpad, $cnt, $limitList) = $order->orderList1($sid, $date_from, $date_to, $_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&date_from=$date_from&date_to=$date_to&order_status=$sid", OBJECT, $_REQUEST['orderBy'],'',$store_id, $_REQUEST['order_status']);
			$framework->tpl->assign("DATE_FROM", $date_from);
			$framework->tpl->assign("DATE_TO", $date_to);
			$framework->tpl->assign("ORDER_LIST", $rs);
			$framework->tpl->assign("ORDER_NUMPAD", $numpad);
			$framework->tpl->assign("ORDER_LIMIT", $limitList);
			$stat_array = array("id"=>array("0"=>"Archives"),"name"=>array("0"=>"Archive Orders"));		
			$framework->tpl->assign("ORDER_STATUS",$stat_array);
			$framework->tpl->assign("CURRENT_STATUS", $sid);
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/manual_order_list.tpl");
			break;
			
			//==================================
	
	case "manual_order_form":
		
		$Submit	=	$_REQUEST['Submit'];
		$id		=	$_REQUEST['id'];
		
		if($Submit	==	'Submit') {
			$msg	=	$order->validateManualOrderForm($_REQUEST);
			
			if($msg == '') {
				$order->addEditManualOrder($_REQUEST);
				redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=manual_order_list&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}"));
			} else {
				setMessage($msg);
			}
		}
		
		if(isset($id)) {
			$FORM_VALUES	=	$order->getManualOrderDetailsById($id);
			$framework->tpl->assign("FORM_VALUES",$FORM_VALUES);
			$framework->tpl->assign("ORDER_STATUS", $order->orderStatus());	
			$framework->tpl->assign("ORDER_UPDATE_STATUS", $order->orderUpdateStatus());
		} else {
			# $ManualOrder					=	$order->generateManualOrderNumber();
			# $FORM_VALUES['order_number']	=	$ManualOrder;
			
			$framework->tpl->assign("FORM_VALUES",$_REQUEST);
			$framework->tpl->assign("ORDER_STATUS", $order->orderStatus());	
		}
			
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/manualorder_form.tpl");
		break;
	
	case "manual_order_form_online":
	
		$order_id	=	$_REQUEST['order_id'];
		$order->addEditManualOrderOfOnlineOrders($_REQUEST);
		
		
		redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=details&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&id=$order_id"));
		
		
	
		break;
		#####  Case to list the weeks and to give link to the order list of particular week...
		#####  Date Added on 16/05/2008 By Jipson Thomas...................................... 
		#####  Date Modified on 16/05/2008 By Jipson Thomas...................................
	case "gangrun":
		$rs=$order->getGangrunList();
		$j=0;
		for($i=sizeof($rs)-1;$i>=0;$i--){
			$rec[$j]["enddate"]		=	$rs[$i]["enddate"];
			$rec[$j]["gangname"]	=	$rs[$i]["gangname"];
			$j++;
		}
		if(!$_REQUEST["sindex"]){
			$sindex=0;
		}else{
			$sindex=$_REQUEST["sindex"];
		}
		if($sindex >sizeof($rec)-20){
			$next='';
		}else{
			$nextindex=$sindex+20;
			$next="<a href='index.php?mod=order&pg=order&act=gangrun&sindex=$nextindex' >Next</a>&gt;&gt;";
		}
		if($sindex < 20){
			$previous='';
		}else{
			$previndex=$sindex-20;
			$previous="&lt;&lt;<a href='index.php?mod=order&pg=order&act=gangrun&sindex=$previndex'>Previous</a>";
			
		}
		$j=0;
		for($i=$sindex;$i<=$nextindex;$i++){
			$frec[$j]["enddate"]	=	$rec[$i]["enddate"];
			$frec[$j]["gangname"]	=	$rec[$i]["gangname"];
			$j++;
		}
		if($previous==''){
			$numpad=$next;
		}elseif($next==''){
			$numpad=$previous;
		}else{
			$numpad=$previous." |  ".$next;
		}
		$framework->tpl->assign("NUMPAD",$numpad);
		$framework->tpl->assign("GNGLIST",$frec);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/gangrun_list.tpl");
		break;
	
	#####  Case to list the GangRun order list of particular week...
		#####  Date Added on 16/05/2008 By Jipson Thomas...................................... 
		#####  Date Modified on 16/05/2008 By Jipson Thomas...................................
		#####  Date Modified on 22/05/2008 By Ratheesh kk  for order status...................................
	case "gangrundet":
		$date_to=$_REQUEST['date_to'];
		$date_from=date("Y-m-d",strtotime($date_to)-(7 * 24 * 60 * 60));
		$sts=$order->orderStatus();
		//print_r($sts);exit;
		//$framework->tpl->assign("ORDER_UPDATE_STATUS", $sts);
		$framework->tpl->assign("ORDER_UPDATE_STATUS", $order->orderUpdateStatus());	
		if($_SERVER['REQUEST_METHOD'] == "POST") {
	    	$_REQUEST['pageNo'] = 1;
			extract($_POST);
			$mg=false;
			foreach($archieve_id as $id){
				$mg=$order->updateOrderStatus($id,$order_status);
			}
			if($mg==true){
				setMessage("Order Status Updated Successfully!!", MSG_SUCCESS);
			}
		}
		//$sid = isset($_REQUEST['order_status']) ? $_REQUEST['order_status'] : "";		
		//if(isset($_POST['order_status'])){		
		//	$sid =$_POST['order_status'];
		//}				
		//$archieve = $_REQUEST['archieve_id'];
		//if(count($archieve)>0){		
		//	$order->ChangetoArchieve($archieve);
		//	setMessage("Moved to Archive Successfully!!", MSG_SUCCESS);
		//}
    	$_REQUEST['limit'] = $_REQUEST['limit'] ? $_REQUEST['limit'] : 20;
    	$_REQUEST['orderBy'] = $_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : "o.date_ordered:DESC";
    //	$date_from = isset($_POST['date_from']) ? $_POST['date_from'] : $_REQUEST['date_from'];
    	//$date_to   = isset($_POST['date_to']) ? $_POST['date_to']   : $_REQUEST['date_to'];
        list($rs1, $numpad, $cnt, $limitList) = $order->orderList($sid, $date_from, $date_to, $_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&date_from=$date_from&date_to=$date_to&order_status=$sid", OBJECT, $_REQUEST['orderBy'],'',$store_id);
		

		/**
		 * The following code is for changing the display view of online orders as of Manual order
		 */
		$framework->tpl->assign("LISTING_TYPE", $order->config['online_order_listing']);
		$framework->tpl->assign("DATE_FROM", $date_from);
        $framework->tpl->assign("DATE_TO", $date_to);
        $framework->tpl->assign("ORDER_LIST", $rs1);
        $framework->tpl->assign("ORDER_NUMPAD", $numpad);
        $framework->tpl->assign("ORDER_LIMIT", $limitList);
		$stat_array = array("id"=>array("0"=>"4"),"name"=>array("0"=>"Archive Orders"));		
		$framework->tpl->assign("ORDER_STATUS",$stat_array);
		$framework->tpl->assign("CURRENT_STATUS", $sid);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/gangrun_detlist.tpl");
		break;
	case "archive_orders":
		$order->archiveManualOrders($_REQUEST);
		redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=manual_order_list&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}"));
		break;
	
		
		
		
    case "item_details":    
    	
		include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");
		$objAccessory = new Accessory();

		$order_id = $_REQUEST["id"];
		$ord= $_REQUEST["order_ord"];
		
    	$ord_items = $order->getOrderProducts($order_id);
    	$option = explode("|",$ord_items["notes"]);  

    	for ($k=0;$k<sizeof($option);$k++)
		{
			$arr1 = explode("~",$option[$k]);
			
			
			if ($arr1[0])
			{
				$options[$k]["label"] = $arr1[0];
				$options[$k]["value"] = $arr1[1];
				if($arr1[2] && $options[$k]["label"]=='Sentiment2')
				{
					$options[$k]["value"] = $arr1[2];
				}
			
			}	
			
			
		}
	
		
		$framework->tpl->assign("ORD_ITEMS",$ord_items);		
		$framework->tpl->assign("GIFT_DET",$options);
    	$prd_details = $product->ProductGet($ord_items["product_id"]);
		$ord_accessory = $order->OrderAccessoryGet($_REQUEST['ord_id'],$ord,$order_id);
		
	    if (count($ord_accessory)>0)
		{
			foreach ($ord_accessory as $accessory_list)
			{
				if ($accessory_list['type'] == 'poem')
				$accessory_id = $accessory_list['id'] ;
			
			}
			
			$accessory = $objAccessory->GetAccessory($accessory_id);
			
		}
		
		
		
		
		if ($accessory['poem']!='')
		{
			//$opening_lines = $ajax_editor->GetTextBoxValuesO($accessory['poem'],$cnt);
			//$closing_lines = $ajax_editor->GetTextBoxValuesC($accessory['poem'],$cnt);
			$framework->tpl->assign("POEM_SHOW",1);
			
			$opening_lines_all = $ajax_editor->GetTOpeningLines($accessory['poem'],$cnt);
			$closing_lines_all = $ajax_editor->GetTClosingLines($accessory['poem'],$cnt);
			
			$framework->tpl->assign("OPENING_LINES_ALL",$opening_lines_all);
			$framework->tpl->assign("CLOSING_LINES_ALL",$closing_lines_all);
			
			//$framework->tpl->assign("OPENING_LINES",preg_replace("/,>|>/","",$opening_lines));
			//$framework->tpl->assign("CLOSING_LINES",preg_replace("/>|>/","",$closing_lines));
		}
		
		$framework->tpl->assign("ORDER_ACCESSORY",$ord_accessory);
		$framework->tpl->assign("PRD_DET",$prd_details);
/*	For displaying the Surname Text:: START	SALIM*/
		$surnametxt	 	= explode("~",$option[0]);
		$surnamearry	=	$ajax_editor->getSurName($surnametxt[1]);		
		
     		 $string ='<div id="surdiv" style="text-align:center; "><div style="font-family:Georgia; font-size:9px; width:360px; text-align:justify;margin:0 auto;color:#000000" >'.substr($surnamearry->Text,0,1000).' ...</div>';
			 $string.='<div style="font-family:Georgia; font-size:5px; width:250px; text-align:center;margin:0 auto; " >&nbsp;</div>';
			 $string.='<div style="font-family:Georgia; font-size:9px; width:250px; text-align:center;margin:0 auto;color:#000000" >'.$surnamearry->Arms.'</div>';
			 $string.='<div style="font-family:Georgia; font-size:9px; width:250px; text-align:center;margin:0 auto; color:#000000" >'.$surnamearry->Crest.'</div>';
			 $string.='<div style="font-family:Georgia; font-size:9px; width:250px; text-align:center;margin:0 auto; color:#000000" >'.$surnamearry->Origin.'</div></div>';

		$framework->tpl->assign("SUR_TEXT",$string);
/*    END	*/

		$custom_field_values	=	$product->getProductCustomFieldValues($order_id);
		//print_r($custom_field_values);
		$framework->tpl->assign("CUSTOM_FIELD_VALUES",$custom_field_values);
    	$framework->tpl->assign("main_tpl", SITE_PATH."/modules/order/tpl/order_item_details.tpl");
    	break;    
		
    case "details":		
        
    	$framework->tpl->assign("CURRENT_ORDER_DETAILS",$order->getPastOrderDetails($_REQUEST['id']));
    	 
   	   	
		if($order_fields[0]=="Y"){
			$table_id = $order->getTableID('orders'); 
			$table_id = $table_id[table_id];
		}
    	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		
		 $order_id	=	$_REQUEST['order_id'];
		 
		 // added by vinoy 07-2-08
			   $order_stst=$_POST['order_status'];
		     $order_stats =$order->getDropdownStatus($order_stst);
			// end added by vinoy 07-2-08
			
		 
		 if($_POST['status_key'] == 'Y') 
		{ 	$_POST['order_id'] = $_REQUEST['order_id'];
			$order->updateStatus($_POST,$order_stats);
    		setMessage("Order Status Updated Successfully!!", MSG_SUCCESS);
    		redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=details&id={$_REQUEST['order_id']}&hidtrackno=1"));
		}
		
		 
		 
			if ($_REQUEST['btn_post']) {
				 $ord_id = $_REQUEST['id'];
				 $req = &$_REQUEST;
				  $req['file_id']= $_REQUEST['id']; 
				   $_REQUEST['id'] = "";
				  if($_SESSION[adminSess]->id!=""){
					 $getID = $_SESSION[adminSess]->id;
					  $req['user_id']=$getID;
				 }
				if( ($message = $forum->topicAddEdit($req,$fname,$tmpname,$ord_id,$getID,$table_id)) === true ) {
					setMessage("Comment Added Successfully!", MSG_SUCCESS);
					redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=details&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&id=$ord_id&hidtrackno=1"));
				}else{
					redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=details&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&id=$ord_id&err_msg=$message&hidtrackno=1"));
				}
				
			}elseif ($_REQUEST['submit_post']) {
				 $req = &$_REQUEST;
				if($_SESSION[adminSess]->id!=""){
					 $getID = $_SESSION[adminSess]->id;
					  $req['user_id']=$getID;
				}
			 //	 $_REQUEST['id'] = "";
				 
				  $req['cat_id']=$_REQUEST['file_id'];
				 
				if( ($message = $forum->postReplyAdd($req,$getId)) === true ) {
					setMessage("Comment Added Successfully!", MSG_SUCCESS);
					redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=details&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&id=$_REQUEST[file_id]&hidtrackno=1"));
				}else{
					redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=details&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&id=$_REQUEST[file_id]&err_msg2=$message&hidtrackno=1"));
				}
				
			}else{
				if($order_id){
					$order->addEditManualOrderOfOnlineOrders($_REQUEST);
					redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=list&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&id=$order_id"));
				}
			
				 $_POST['order_id'] = $_REQUEST['id'];
				 $order->updateStatus($_POST, $order_stats);
				 setMessage("Order Status Updated Successfully!!", MSG_SUCCESS);
				 redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=details&id={$_REQUEST['id']}&hidtrackno=1"));
			}
    	}
    	$extras = new Extras(); 
		

		$framework->tpl->assign("ORDER_SHOW", $extras->config['order_details']);
		
		/*********************************************************/
		//if no avilable accessory
		//its optional parameter
		$val	=	$framework->config['avilable_access'];
		/*********************************************************/
		$ordArray 		= $order->orderProducts($_REQUEST['id'],$val);
		// print_r($ordArray);
		if($global["Order_Product_Job_TBL"]=="Y"){
			$ordjob			= $order->getOrderProductsjob($_REQUEST['id'],$val);
		}
    	$ordDetails 	= $order->orderDetails($_REQUEST['id']);
		//print_r($ordDetails);exit;
		
		if($ordjob){
			$sz=sizeof($ordArray[records]);
			$jz=sizeof($ordjob);
			for($i=0;$i<$sz;$i++){
				for($j=0;$j<$jz;$j++){
					if($ordArray[records][$i]->id==$ordjob[$j]->id){
						$ordArray[records][$i]->jobname=$ordjob[$j]->jobname;
						$ordArray[records][$i]->height=$ordjob[$j]->height;
						$ordArray[records][$i]->width=$ordjob[$j]->width;
						$ordArray[records][$i]->file1_ext=$ordjob[$j]->file1_ext;
						$ordArray[records][$i]->file2_ext=$ordjob[$j]->file2_ext;
					}
				}
			}
			//$ordArray	=array_merge($ordArray,$ordjob);
			//$ordArray[records][0]->jobname="test";
			//echo $ordArray[records][0]->jobname;
		}
		
		$chkDetails 	= $order->checkDetails($_REQUEST['id']); 
    	$gift_cert_rs	= $extras->historyByOrderid($_REQUEST['id'], 'G');
    	$coupon_rs = $extras->historyByOrderid1($_REQUEST['id'], 'C');   	
    	$shipping_price = ($coupon_rs->coupon_amounttype=='F') ? 0 : $ordDetails->shipping_price;		
		//$tax_amount 	= round(($ordArray['total']+$shipping_price) * ($ordDetails->tax) / 100, 2);
		
		$tax_amount 	= round($ordDetails->tax, 2);
		//$sub_total 		= round(($ordArray['total']+$shipping_price) * (100 + $ordDetails->tax) / 100, 2);
		$sub_total 		= round(($ordArray['total']+$shipping_price+$tax_amount), 2);
		
		
		if($coupon_rs->coupon_amounttype=='F') {
			$coupon_amount = 0;
		} elseif ($coupon_rs->coupon_amounttype=='A') {
			//$coupon_amount = - $coupon_rs->trans_useamount;
			// -------
			if($coupon_rs->substract_from=='T')
			{ 
				if($sub_total > $coupon_rs->trans_useamount)
				{ $coupon_amount = - $coupon_rs->trans_useamount; }
				else
				{ $coupon_amount = - $sub_total;}
			}
			elseif($coupon_rs->substract_from=="O")
			{ 
				if($ordArray['AccessoryTotal'] > $coupon_rs->trans_useamount)
				{ $coupon_amount = - $coupon_rs->trans_useamount; }
				else
				{ $coupon_amount = - $ordArray['AccessoryTotal'];}
			}
			elseif($coupon_rs->substract_from=="M")
			{ 
				if($ordArray['ProductTotal'] > $coupon_rs->trans_useamount)
				{ $coupon_amount = - $coupon_rs->trans_useamount; }
				else
				{ $coupon_amount = - $ordArray['ProductTotal'];}
			}
			else
			{ 
				if($ordArray['total'] > $coupon_rs->trans_useamount)
				{ $coupon_amount = - $coupon_rs->trans_useamount; }
				else
				{ $coupon_amount = - $ordArray['total'];}
			}
			//---------
		} elseif ($coupon_rs->coupon_amounttype=='P') {
		// substract from product or total
			if($coupon_rs->substract_from=='T')
			{
				$coupon_amount = - $sub_total * $coupon_rs->trans_useamount / 100;
			}
			elseif($coupon_rs->substract_from=='O')
			{
				$coupon_amount = - $ordArray['AccessoryTotal'] * $coupon_rs->trans_useamount / 100;
			}
			elseif($coupon_rs->substract_from=='M')
			{
				$coupon_amount = - $ordArray['ProductTotal'] * $coupon_rs->trans_useamount / 100;
			}
			else
			{
				$coupon_amount = - $ordArray['total'] * $coupon_rs->trans_useamount / 100;
			}
		// substract from product or total
		}
		 $oid=$_REQUEST['id'];
		 $total_amount=$sub_total + $coupon_amount + $certificate_amount;
		if ($framework->config["special_discount_field"]=="Y"){ 
			$udet = $user->getUserDetails($_SESSION["memberid"]);
			if($udet["sp_discount"]){
				$disc=$udet["sp_discount"];
			}else{
				$disc=1;
			}
			$dsicount_perc=(1-$disc)*100;
			$discount_amount=(1-$disc)*$ordArray['ProductTotal'];
			$tax_amount 	= round( $ordDetails->tax, 2);
			//$tax_amount 	= round((($ordArray['total']-$discount_amount)+$shipping_price) * ($ordDetails->tax) / 100, 2);
			$sub_total 		= round((($ordArray['total']-$discount_amount)+$shipping_price+$tax_amount), 2);
			$total_amount=$sub_total + $coupon_amount + $certificate_amount;
			$framework->tpl->assign("DISCOUNT",'Y');	
			$framework->tpl->assign("DIS_PERC",$dsicount_perc);	
			$framework->tpl->assign("DIS_AMT",$discount_amount);	
		}
		$certificate_amount = - $gift_cert_rs->trans_useamount;
		//$framework->tpl->assign("STORE_NAME",  $order->getOrderfromStore($_REQUEST['id']));
		$framework->tpl->assign("STORE_NAME",  $order->getOrderfromStorename($oid));
		$framework->tpl->assign("CART_TOTAL", $ordArray['total']);
		$framework->tpl->assign("SHIPPING_PRICE", $shipping_price > 0 ? number_format($shipping_price, 2, '.', '') : 'Free');
		$framework->tpl->assign("TAX_AMOUNT", $tax_amount);
		$framework->tpl->assign("SUB_TOTAL", $sub_total);
		$framework->tpl->assign("COUPON_AMOUNT", $coupon_amount);
		$framework->tpl->assign("CERTIFICATE_AMOUNT", $certificate_amount);
		$framework->tpl->assign("TOTAL_AMOUNT", $total_amount);    	
    	$framework->tpl->assign("ORDER_STATUS", $order->orderStatus());	
		$framework->tpl->assign("ORDER_UPDATE_STATUS", $order->orderUpdateStatus());	
		//print_r($ordArray);
		//exit;
		
		
		$framework->tpl->assign("ORDER_JOBS", $ordjob);
		$storedetail=$store->storeGet($ordDetails->store_id);
		$ordDetails->store_name=$storedetail["name"];
		//Ratheesh added for store name
		foreach($ordArray as $key=>$ordValue){
			if(is_array($ordValue)){
			foreach($ordValue as $newKey=>$Value){
				$storedetail_new=$store->storeGet($Value->store_id);
		 		$ordArray[$key][$newKey]->store_name=$storedetail_new["name"];
			}
			}//End of is array check.
		}
		
		
		$framework->tpl->assign("ORDER_PRODUCTS", $ordArray);
		
		//print_r($ordArray);
		
		# For manual order processing
		$OrderNumber		=	$ordDetails->order_number;
		$ManualOrderDetals	=	$order->getManualOrderDetailsFromOrderNumber($OrderNumber);
		$framework->tpl->assign("MANUAL_ORDER_DETAILS", $ManualOrderDetals);
		
		//
		//print_r($ordDetails);
		$ord_comment   =  $ordDetails->order_history;
	    $str=substr($ord_comment,41,14);
		$framework->tpl->assign("ORDER_COMMENT", $str);
		//print_r($ordDetails);exit;
    	$framework->tpl->assign("ORDER_DETAILS", $ordDetails);
	

		$framework->tpl->assign("LOG_DET", $order->getIpnStatusByOrderSessId($ordDetails->order_sess_id));
		
		$framework->tpl->assign("FIELDS",$typeObj->GetMailCheckFields()); 
		$framework->tpl->assign("CHECK_DETAILS", $chkDetails);
    	$framework->tpl->assign("ORDER_GIFT_CERT", $gift_cert_rs);
    	$framework->tpl->assign("ORDER_COUPON", $coupon_rs);
		### Code added By Jipson to hide the track number after a submit
		### Dated	:	07 february 2008.
		if($_REQUEST["hidtrackno"]==1){
			$framework->tpl->assign("HIDE_TRACK","Y");
		}
		### End .....
		$framework->tpl->assign("ORDER_TRANSACTION_DETAILS", $order->orderTransactionDetails($_REQUEST['id'],'O'));
		### Start showing Pay Invoice Nov-27-2007 by shinu ###
		if($framework->config['pay_invoice']=="Y")
		{
			$invoice_amt	=	$cart->getOrderInvoiceAmount($_REQUEST['id']); 
			$framework->tpl->assign("INVOICE_AMOUNT", $invoice_amt); 
			if($invoice_amt !== "" && $invoice_amt >0)
			{
				$framework->tpl->assign("INVOICE", $cart->getOrderInvoice($_REQUEST['id']));
				$framework->tpl->assign("PAY_INVOICE", "Y");
				$framework->tpl->assign("CART_TOTAL_AMOUNT", $sub_total + $coupon_amount + $certificate_amount);
				$framework->tpl->assign("TOTAL_AMOUNT", ($sub_total + $coupon_amount + $certificate_amount+$invoice_amt)); 

			}
		}
		// for Order Notes Display on Admin
			if($_REQUEST['id'])
		    {	
				if($table_id>0){
					$all_topic_thread = $forum->getTopicThread($_REQUEST['id'],$table_id);
		   			$all_topic = $forum->AllTopicGetbyOrd($_REQUEST['id']);
					//print_r($all_topic_thread);exit;
            		$framework->tpl->assign("USERTOPIC_LIST", $all_topic_thread);
		    		$framework->tpl->assign("CRT",$_REQUEST['cat_id']);			
				}
			 }// if
		### End showing Pay Invoice Nov-27-2007 by shinu ###
		
		
    	if ($framework->config["order_details_client"]=="Y"){    	
    		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/order/tpl/order_details.tpl");
    	}
    	else {
			if($store_id){
				$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/order_store_details.tpl");
			}else{    	
        		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/order_details.tpl");
			}	
    	}	
        break;
		
		//==========vinoy==================
		
		 case "manual_details":
    	if ($_SERVER['REQUEST_METHOD'] == "POST") {
    		$_POST['order_id'] = $_REQUEST['id'];
    		$order->updateStatus($_POST);
    		setMessage("Order Status Updated Successfully!!", MSG_SUCCESS);
    		redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=manual_order__details&id={$_REQUEST['id']}"));
    	}
    	$extras = new Extras(); 
		

		$framework->tpl->assign("ORDER_SHOW", $extras->config['order_details']);
		
		/*********************************************************/
		//if no avilable accessory
		//its optional parameter
		$val	=	$framework->config['avilable_access'];
		/*********************************************************/
		$ordArray 		= $order->orderProducts($_REQUEST['id'],$val);
		$ordjob			= $order->getOrderProductsjob($_REQUEST['id'],$val);
    	$ordDetails 	= $order->orderDetails($_REQUEST['id']);
		if($ordjob){
			$sz=sizeof($ordArray[records]);
			$jz=sizeof($ordjob);
			for($i=0;$i<$sz;$i++){
				for($j=0;$j<$jz;$j++){
					if($ordArray[records][$i]->id==$ordjob[$j]->id){
						$ordArray[records][$i]->jobname=$ordjob[$j]->jobname;
						$ordArray[records][$i]->height=$ordjob[$j]->height;
						$ordArray[records][$i]->width=$ordjob[$j]->width;
					}
				}
			}
			//$ordArray	=array_merge($ordArray,$ordjob);
			//$ordArray[records][0]->jobname="test";
			//echo $ordArray[records][0]->jobname;
		}
		$chkDetails 	= $order->checkDetails($_REQUEST['id']); 
    	$gift_cert_rs	= $extras->historyByOrderid($_REQUEST['id'], 'G');
    	$coupon_rs = $extras->historyByOrderid($_REQUEST['id'], 'C');    	
    	$shipping_price = ($coupon_rs->coupon_amounttype=='F') ? 0 : $ordDetails->shipping_price;		
		$tax_amount 	= round(($ordArray['total']+$shipping_price) * ($ordDetails->tax) / 100, 2);
		$sub_total 		= round(($ordArray['total']+$shipping_price) * (100 + $ordDetails->tax) / 100, 2);
		
		if($coupon_rs->coupon_amounttype=='F') {
			$coupon_amount = 0;
		} elseif ($coupon_rs->coupon_amounttype=='A') {
			$coupon_amount = - $coupon_rs->trans_useamount;
		} elseif ($coupon_rs->coupon_amounttype=='P') {
			$coupon_amount = - $sub_total * $coupon_rs->trans_useamount / 100;
		}
		
		$certificate_amount = - $gift_cert_rs->trans_useamount;
		$framework->tpl->assign("STORE_NAME",  $order->getOrderfromStore($_REQUEST['id']));
		$framework->tpl->assign("CART_TOTAL", $ordArray['total']);
		$framework->tpl->assign("SHIPPING_PRICE", $shipping_price > 0 ? number_format($shipping_price, 2, '.', '') : 'Free');
		$framework->tpl->assign("TAX_AMOUNT", $tax_amount);
		//$framework->tpl->assign("SUB_TOTAL", $sub_total);
		$framework->tpl->assign("COUPON_AMOUNT", $coupon_amount);
		$framework->tpl->assign("CERTIFICATE_AMOUNT", $certificate_amount);
		//$framework->tpl->assign("TOTAL_AMOUNT", $sub_total + $coupon_amount + $certificate_amount);    	
    	$framework->tpl->assign("ORDER_STATUS", $order->orderStatus());	
		$framework->tpl->assign("ORDER_UPDATE_STATUS", $order->orderUpdateStatus());
		
		$framework->tpl->assign("ORDER_PRODUCTS", $ordArray);
		$framework->tpl->assign("ORDER_JOBS", $ordjob);
		$storedetail=$store->storeGet($ordDetails->store_id);
		$ordDetails->store_name=$storedetail["name"];
		
		
		
		//
    	$framework->tpl->assign("ORDER_DETAILS", $ordDetails);
		$framework->tpl->assign("FIELDS",$typeObj->GetMailCheckFields()); 
		$framework->tpl->assign("CHECK_DETAILS", $chkDetails);
    	$framework->tpl->assign("ORDER_GIFT_CERT", $gift_cert_rs);
    	$framework->tpl->assign("ORDER_COUPON", $coupon_rs);
    	if ($framework->config["order_details_client"]=="Y"){    	
    		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/order/tpl/manual_order_details.tpl");
    	}
    	else {
			if($store_id){
				$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/order_store_details.tpl");
			}else{    	
        		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/manual_order_details.tpl");
			}	
    	}	
        break;
		
		
		
		
		//=============vinoy=================
		
		
		
		
		
		
		
	case "assign_supplier":
		$order_id		=	$_REQUEST['order_id'];
		$product_id		=	$_REQUEST['id'];
		$base_quantity	=	$_REQUEST['base_quantity'];
	if ($_SERVER['REQUEST_METHOD'] == "POST") { 
			$req	=	&$_REQUEST; 
			if(($message = $order->assignSupplier($req)) === true ) {
				setMessage("Order Assigned Successfully!!");	
				redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=assign_supplier&id={$_REQUEST['id']}&order_id=$order_id&base_quantity=$base_quantity"));			
			}
    		setMessage($message);    		
    	}	
		$extras 		= 	new Extras();
		$assignedDetails=	$order->getAssignedDetails($order_id,$product_id);				
		$ordArray 		= 	$order->orderProducts($order_id);
		$proArr			=	$product->ProductGet($product_id);
    	$ordDetails 	= 	$order->orderDetails($order_id);
		$chkDetails 	= 	$order->checkDetails($order_id);    	
		$sum			=	$order->quantityBalance($order_id,$product_id);	
		$balance		=	$base_quantity-$sum;
		$framework->tpl->assign("TOTAL_ASSIGNED", $sum);
		$framework->tpl->assign("BALANCE_AMOUNT", $balance);
		$framework->tpl->assign("SHIPPING_PRICE", $shipping_price > 0 ? number_format($shipping_price, 2, '.', '') : 'Free');
		$framework->tpl->assign("SUPPLIER", $user->loadSupplier());
    	$framework->tpl->assign("ORDER_PRODUCTS", $ordArray);
    	$framework->tpl->assign("ORDER_DETAILS", $ordDetails);		
		$framework->tpl->assign("PRODUCT_DETAILS", $proArr);
		$framework->tpl->assign("ORDER_ID", $order_id);
		$framework->tpl->assign("PRODUCT_ID", $product_id);		
		$framework->tpl->assign("ASSIGNED_DETAILS", $assignedDetails);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/assign_supplier.tpl");		
	break;	
	case "delete_supllier":	
		$order_id		=	$_REQUEST['order_id'];
		$product_id		=	$_REQUEST['id'];
		$base_quantity	=	$_REQUEST['base_quantity'];		
		if(count($sup_id)>0){			
			foreach ($sup_id as $id){				
				$status		=	$order->supOrderDelete($id);				
			}
			if($status==true){
				setMessage("Order deleted Successfully!!");
				redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=assign_supplier&id={$_REQUEST['id']}&order_id=$order_id&base_quantity=$base_quantity"));
			}
		}		
	break;
	case "assigned_details":	
		$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
		$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
		$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
		$param			=	"mod=$mod&pg=$pg&act=$act&orderBy=$orderBy&fId=$fId&sId=$sId";
		if(empty($limit))
		$_REQUEST["limit"]		=	"20";
		list($rs, $numpad, $cnt, $limitList)	= 	$order->supAssignedOrder($adminSess->id,$pageNo,$limit,$param,OBJECT, $orderBy);
		$framework->tpl->assign("ASSIGN", $rs);
		 $framework->tpl->assign("SUP_NUMPAD", $numpad);
        $framework->tpl->assign("SUP_LIMIT", $limitList);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/suppliear_order.tpl");		
	break;	
	case "popupComments":
	
	 $oid=$_REQUEST['oId'];
	$comments	=	$order->selComments($oid);		
	 $framework->tpl->assign("COMMENTS", $comments);
	
	$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/order_comments.tpl");

	break;
	case "delete":	
		$archieve_id = $_REQUEST['archieve_id'];
		if(count($archieve_id)>0)
					{
			$message=true;
			foreach ($archieve_id as $id)
				{
				if($order->deleteOrder($id)===false)
					$message=false;
				}
				if($message==true)
			setMessage("Order(s) Deleted Successfully!");
			}
			redirect(makeLink(array("mod"=>"order", "pg"=>"order"), "act=list&fId=$fId&sId=$sId"));

	break;
	case "gang_orderdelete":	
		$archieve_id = $_REQUEST['archieve_id'];
		if(count($archieve_id)>0)
					{
			$message=true;
			foreach ($archieve_id as $id)
				{
				if($order->deleteOrder($id)===false)
					$message=false;
				}
				if($message==true)
			setMessage("Order(s) Deleted Successfully!");
			}
			redirect(makeLink(array("mod"=>"order", "pg"=>"order"), "act=gangrun&fId=$fId&sId=$sId"));

	break;
	
	case "manual_delete":	

		if(count($order_ids)>0)
					{
			$message=true;
			foreach ($order_ids as $id)
				{
				if($order->delete_manual_Order($id)===false)
					$message=false;
				}
				if($message==true)
			setMessage("Manual Order(s) Deleted Successfully!");
			}
			redirect(makeLink(array("mod"=>"order", "pg"=>"order"), "act=manual_order_list&fId=$fId&sId=$sId"));

	break;
	case "notes_view":
	     if($_REQUEST['cat_id'])
		    {
		     $Topic=$forum->TopicGet($_REQUEST['id']);
		     $userdetails = $user->getUserDetails($Topic['user_id']);
		     $framework->tpl->assign("USER",$userdetails);
		     $framework->tpl->assign("TOPICS",$Topic);
			 $rs_parent = $forum->getCategoryTreeParentLevel($parent,$_REQUEST['id']);
			 if(count($rs_parent)>0){
			 	foreach($rs_parent as $key=>$value){
					$parent = $value[id];
					$rs_child = $forum->getCategoryTreeParentLevel($parent,$_REQUEST['id']);
					if(count($rs_child)>0){
						foreach($rs_child as $value_child){
							$parent = $value_child[id];
							$child_body = $value_child[body];
							$reply_date = $value_child[posted_date];
							$rs_parent[$key]['child']['body'][] = $child_body;
							$rs_parent[$key]['child']['posted_date'][] = $reply_date;
						}
					}
				}
			 }
			
             $framework->tpl->assign("POST_LIST", $rs_parent);
			 }
			if($_SERVER['REQUEST_METHOD'] == "POST") {		
				$req = &$_REQUEST;
				$getId=$_REQUEST['user_id'];	
				if($_REQUEST['btn_reply'] == "Send") {		
					if( ($message = $forum->CommentReplyAdd($req,$getId)) === true ) {
						
							setMessage("Reply Comment Added Successfully!", MSG_SUCCESS);
						
					}else{ setMessage($message);}  
				}else{
					if( ($message = $forum->ReplyAdd($req,$getId)) === true ) {
							setMessage("Comment Added Successfully!", MSG_SUCCESS);
					   
					redirect(makeLink(array("mod"=>"order", "pg"=>"order"), "act=notes_view&id=".$_REQUEST['id']."&cat_id=".$_REQUEST['cat_id']));
					}else{ setMessage($message);}
				}
				
				
			}
		/*if($message) {
			$framework->tpl->assign("POSTS", $_POST);
		}*/
     
		$framework->tpl->display(FRAMEWORK_PATH."/modules/order/tpl/order_notes.tpl");exit; 
		 break;
		 
		 
    case "show_order":
	
	if ($_SERVER['REQUEST_METHOD'] == "POST") { 
		$param=	"mod=order&pg=order&act=show_order&date_from=".$_POST['date_from']."&date_to=".$_POST['date_to']."&orderBy=$orderBy&fId=$fId&sId=$sId";
		list($data, $numpad , $cnt, $limitList)=$order->show_order(0,$_REQUEST['limit'],$param,$_POST['date_from'],$_POST['date_to']);
		$gtotal=$order->total_earningorder($_POST['date_from'],$_POST['date_to']);
		$framework->tpl->assign("DATE_FROM", $_POST['date_from']);
		$framework->tpl->assign("DATE_TO", $_POST['date_to']);
	}else{
		$param			=	"mod=order&pg=order&act=show_order&date_from=".$_REQUEST['date_from']."&date_to=".$_REQUEST['date_to']."&orderBy=$orderBy&fId=$fId&sId=$sId";
		
		list($data, $numpad , $cnt, $limitList)=$order->show_order($_REQUEST["pageNo"],$_REQUEST['limit'],$param,$_REQUEST['date_from'],$_REQUEST['date_to']);
		$gtotal=$order->total_earningorder($_REQUEST['date_from'],$_REQUEST['date_to']);
		$framework->tpl->assign("DATE_FROM", $_REQUEST['date_from']);
		$framework->tpl->assign("DATE_TO", $_REQUEST['date_to']);
	}
		$framework->tpl->assign("GTOTAL",$gtotal);
		$framework->tpl->assign("PTOTAL", $data["page_total"]);
		$framework->tpl->assign("ORDERTOTAL", $data["order_total"]);
		unset($data["page_total"]);
		unset($data["order_total"]);
		$framework->tpl->assign("EARNING_LIMIT",$limitList);
		$framework->tpl->assign("EARNING_LIST",$data);
		$framework->tpl->assign("EARNING_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/orderusereport.tpl");
	#### End...................................................................................................
	break;
	
	case "show_earning":
	//print_r($_REQUEST);
	##### This case is written to show the report of total earning from the website between a date range.......
	##### Author	:	Jipson Thomas..........................................................................
	##### Dated		:	18 January 2008........................................................................
	if ($_SERVER['REQUEST_METHOD'] == "POST") { 
		$param=	"mod=order&pg=order&act=show_earning&date_from=".$_POST['date_from']."&date_to=".$_POST['date_to']."&orderBy=$orderBy&fId=$fId&sId=$sId";
		list($data, $numpad, $cnt, $limitList)=$order->show_earning(0,$_REQUEST['limit'],$param,$_POST['date_from'],$_POST['date_to'],OBJECT, $orderBy);
		$gtotal=$order->total_earning($_POST['date_from'],$_POST['date_to']);
		$framework->tpl->assign("DATE_FROM", $_POST['date_from']);
		$framework->tpl->assign("DATE_TO", $_POST['date_to']);
	}else{
		$param			=	"mod=order&pg=order&act=show_earning&date_from=".$_REQUEST['date_from']."&date_to=".$_REQUEST['date_to']."&orderBy=$orderBy&fId=$fId&sId=$sId";
		list($data, $numpad, $cnt, $limitList)=$order->show_earning($_REQUEST["pageNo"],$_REQUEST['limit'],$param,$_REQUEST['date_from'],$_REQUEST['date_to'],OBJECT, $orderBy);
		$gtotal=$order->total_earning($_REQUEST['date_from'],$_REQUEST['date_to']);
		$framework->tpl->assign("DATE_FROM", $_REQUEST['date_from']);
		$framework->tpl->assign("DATE_TO", $_REQUEST['date_to']);
	}
		$framework->tpl->assign("GTOTAL",$gtotal);
		$framework->tpl->assign("PTOTAL", $data["page_total"]);
		$framework->tpl->assign("EARNING_LIMIT",$limitList);
		
		$framework->tpl->assign("EARNING_LIST",$data);
		$framework->tpl->assign("EARNING_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/earning_report.tpl");
	#### End...................................................................................................
	break;
	
	case "order_detail_report":
	
		$param			=	"mod=order&pg=order&act=order_detail_report&order_id=".$_REQUEST['order_id']."&orderBy=$orderBy&fId=$fId&sId=$sId";
		list($odat,$numpad)=$order->getOrderProductReport($_REQUEST["pageNo"],10,$param,$_REQUEST['order_id']);
		$gtotal=$order->total_earning_order($_REQUEST['order_id']);
		$framework->tpl->assign("GTOTAL",$gtotal);
		$framework->tpl->assign("PTOTAL", $odat["page_total"]);
		$framework->tpl->assign("ORDER_LIST",$odat);
		$framework->tpl->assign("ORDER_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/order_report.tpl");
		break;
		
	
	case 'thub_export':
		header("Content-type: application/xml");
		include_once(FRAMEWORK_PATH."/modules/order/lib/class.thubservice.php");
		
		$THubServiceObj	=	new THubService();
		$OutputXML		=	$THubServiceObj->getOrders($_REQUEST);
		print $OutputXML;
		exit;
		break;
	case 'file_download':
			$path		=	$_REQUEST["path"];
			$filename	=	$_REQUEST["filename"];
			$ext		=	$_REQUEST["type"];
			$order->allFiledownload($path,$filename,$ext);
		break;
	case 'downloadAllFiles':
		//	include_once(FRAMEWORK_PATH."/modules/order/lib/class.zip_dir.php");
		//	$createZip = new createDirZip;
			extract($_POST);
			if(count($archieve_id)>0)
			{
				$nfldr=$gang_run_name;
				if (is_dir(	SITE_PATH.'/modules/order/images/userorders/'.$nfldr.'/')!=true) {
					mkdir(SITE_PATH.'/modules/order/images/userorders/'.$nfldr, 0777);
				}
				foreach ($archieve_id as $ids)
				{
					$fls=$order->getFileIdExt($ids);
					$i=0;
					foreach($fls as $rec){
						if($rec['file1_ext']!=''){
							
							//print_r($global["mod_url"].'/images/userorders/'.$rec['id']."file1.".$rec['file1_ext']);exit;
							if(file_exists(SITE_PATH.'/modules/order/images/userorders/'.$rec['id']."file1.".$rec['file1_ext'])){
								//if (is_dir(	SITE_PATH.'/modules/order/images/userorders/'.$rec['order_id'].'/')) {
								//	rename(SITE_PATH.'/modules/order/images/userorders/'.$rec['id']."file1.".$rec['file1_ext'],SITE_PATH.'/modules/order/images/userorders/'.$rec['order_id'].'/'.$rec['order_id']."_".$rec['id']."file1.".$rec['file1_ext']);
								//}else{
								//	mkdir(SITE_PATH.'/modules/order/images/userorders/'.$rec['order_id'], 0777);
								//	rename(SITE_PATH.'/modules/order/images/userorders/'.$rec['id']."file1.".$rec['file1_ext'],SITE_PATH.'/modules/order/images/userorders/'.$rec['order_id'].'/'.$rec['order_id']."_".$rec['id']."file1.".$rec['file1_ext']);
								//}
								copy(SITE_PATH.'/modules/order/images/userorders/'.$rec['id']."file1.".$rec['file1_ext'],SITE_PATH.'/modules/order/images/userorders/'.$nfldr.'/'.$rec['order_id']."_".$rec['id']."file1.".$rec['file1_ext']);
								$fle[$i]["name"]=	SITE_PATH.'/modules/order/images/userorders/'.$nfldr.'/'.$rec['order_id']."_".$rec['id']."file1.".$rec['file1_ext'];
								$i++;				//rename(SITE_PATH.'/modules/order/images/userorders/'.$rec['order_id'].'/'.$rec['order_id']."_".$rec['id']."file1.".$rec['file1_ext'],SITE_PATH.'/modules/order/images/userorders/'.$nfldr.'/'.$rec['order_id']."_".$rec['id']."file1.".$rec['file1_ext']);
							}
							
						}
						if($rec['file2_ext']!=''){
							if(file_exists(SITE_PATH.'/modules/order/images/userorders/'.$rec['id']."file2.".$rec['file2_ext'])){
								//if (is_dir(	SITE_PATH.'/modules/order/images/userorders/'.$rec['order_id'].'/')) {
								//	rename(SITE_PATH.'/modules/order/images/userorders/'.$rec['id']."file2.".$rec['file2_ext'],SITE_PATH.'/modules/order/images/userorders/'.$rec['order_id'].'/'.$rec['order_id']."_".$rec['id']."file2.".$rec['file2_ext']);
								//}else{
								//	mkdir(SITE_PATH.'/modules/order/images/userorders/'.$rec['order_id'], 0755);
								//	rename(SITE_PATH.'/modules/order/images/userorders/'.$rec['id']."file2.".$rec['file2_ext'],SITE_PATH.'/modules/order/images/userorders/'.$rec['order_id'].'/'.$rec['order_id']."_".$rec['id']."file2.".$rec['file2_ext']);
								//}
									//copy(SITE_PATH.'/modules/order/images/userorders/'.$rec['order_id'].'/'.$rec['order_id']."_".$rec['id']."file2.".$rec['file2_ext'],SITE_PATH.'/modules/order/images/userorders/'.$nfldr.'/'.$rec['order_id']."_".$rec['id']."file2.".$rec['file2_ext']);
									//rename(SITE_PATH.'/modules/order/images/userorders/'.$rec['order_id'].'/'.$rec['order_id']."_".$rec['id']."file2.".$rec['file2_ext'],SITE_PATH.'/modules/order/images/userorders/'.$nfldr.'/'.$rec['order_id']."_".$rec['id']."file2.".$rec['file2_ext']);
									copy(SITE_PATH.'/modules/order/images/userorders/'.$rec['id']."file2.".$rec['file2_ext'],SITE_PATH.'/modules/order/images/userorders/'.$nfldr.'/'.$rec['order_id']."_".$rec['id']."file2.".$rec['file2_ext']);
									$fle[$i]["name"]=	SITE_PATH.'/modules/order/images/userorders/'.$nfldr.'/'.$rec['order_id']."_".$rec['id']."file2.".$rec['file2_ext'];
								$i++;	
							}
						}
						
					}
					
				}
			//	print_r($fle);exit;
			######Section to create Zip file........................
					
					$dr=$nfldr.'/';
				//	$createZip->addDirectory($dr);
					//$createZip->get_files_from_folder(SITE_PATH.'/modules/order/images/userorders/'.$dr, $dr);
					
				//	$fileName = 'GangRunArt.zip';
				//	$fd = fopen ($fileName, 'a+');
				//	print_r($fd);exit;
				//	$out = fwrite ($fd, $createZip->getZippedfile());
					//fclose ($fd);
					
				//	$createZip->forceDownload($fileName);
				//	@unlink($fileName);
					//@unlink(SITE_PATH.'/modules/order/images/userorders/'.$nfldr.'/');
					####################Create zip file end...............
					$FolderToCompress = SITE_PATH."/modules/order/images/userorders/".$dr;
					//shell_exec("zip -rDj arhiveFile  /var/www/html/zip_check/XML/");
					shell_exec("zip -rDj GangRunArt  ".$FolderToCompress);
					header("Pragma: public");
					header("Expires: 0");
					header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
					header("Cache-Control: private",false);
					header("Content-Type: application/zip");
					header("Content-Disposition: attachment; filename=".basename('GangRunArt.zip').";" );
					header("Content-Transfer-Encoding: binary");
					header("Content-Length: ".filesize(SITE_PATH.'/admin/GangRunArt.zip'));
					readfile(SITE_PATH.'/admin/GangRunArt.zip');
					@unlink(SITE_PATH."/admin/GangRunArt.zip");
			}
			redirect(makeLink(array("mod"=>"order", "pg"=>"order"), "act=gangrun&fId=$fId&sId=$sId"));

		break;	 
		 
	
}
if($_REQUEST['print']) {
	$framework->tpl->display($global['curr_tpl']."/printPopup.tpl");
} else if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}
?>
