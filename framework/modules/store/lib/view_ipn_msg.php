<?php 

/**
 * Module :: Product
 *
 * @author Nirmal K R
 * @package Framework
 **/
 
session_start();
include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
$objStore 			= 	new Store();

$txn_id = $_REQUEST['txn_id'];
$storeid = $_REQUEST['sid'];

$ipn_msg_list = $objStore->getIpnMessage($txn_id);

$framework->tpl->assign("IPN_MSG_LIST", $ipn_msg_list);

$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/store/tpl/ipn_msg_list.tpl");
		

$framework->tpl->display($global['curr_tpl']."/adminPopup.tpl");

?>