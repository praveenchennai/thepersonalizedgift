<?php 

/**
 * Module :: Product
 *
 * @author Nirmal K R
 * @package Framework
 **/
 
session_start();
include_once(FRAMEWORK_PATH."/modules/order/lib/class.order.php");
$objOrder 		= 	new Order();

$ipn_msg_list = $objOrder->getIpnMessage();



$framework->tpl->assign("IPN_MSG_LIST", $ipn_msg_list);

$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/order/tpl/ipn_msg_list.tpl");
		

$framework->tpl->display($global['curr_tpl']."/adminPopup.tpl");

?>