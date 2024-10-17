<?php
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");

class paymentType extends FrameWork {

	function paymentType() {
		$this->FrameWork();
	}
	function GetAllCreditCards($storename)
	{ 
		$card_ids="'0'";
		$cards	=	"";
		$storeqry		=	"select * from payment_methods_stores where store_id ='$storename'";// echo $storeqry;
		$rs		 		= 	$this->db->get_row($storeqry,ARRAY_A);
		$cards		 	= 	$rs["credit_cards"];
		
		$cardArray = explode('^*^',$cards);
		foreach ($cardArray as $value) {
  			 $card_ids=$card_ids. " , '".$value."'";
		}
		
		$qry		=	"select * from order_payment_type where active='Y' AND id IN($card_ids)"; 
		
		$rs['id'] 		= 	$this->db->get_col($qry, 0);
		$rs['name'] 	= 	$this->db->get_col($qry, 1);
	
		return $rs;
	}
	function GetMailCheckFields()
	{
		$qry		=	"SELECT * FROM order_mailcheck_form order by position";
		$rs		 	= 	$this->db->get_col($qry, 4);
		return $rs;
	}	
	function GetMailCheckmatidatory()
	{
		$qry		=	"SELECT * FROM order_mailcheck_form order by position";
		$rs		 	= 	$this->db->get_col($qry, 3);
		return $rs;
	}
	function GetCreditcardLogo()
	{
		$qry		=	"select * from order_payment_type where active='Y'";
		$rs['id'] 				= 	$this->db->get_col($qry, 0);
		$rs['logo_extension'] 	= 	$this->db->get_col($qry, 2);
		return $rs;
	}
	function GetPaymentType($id=0) {
		if($id>0)
		{
			$rs = $this->db->get_row("SELECT * FROM order_payment_type WHERE id='{$id}'", ARRAY_A);
			return $rs;
		}
	}
	function GetCheckFields($id=0) {
		if($id>0)
		{
			$rs = $this->db->get_row("SELECT * FROM order_mailcheck_form WHERE id='{$id}'", ARRAY_A);
			return $rs;
		}
	}
	
	function GetGatewayInfo($payment_type) {
		$rs = $this->db->get_row("SELECT * FROM order_payment_gateway WHERE type='{$payment_type}' LIMIT 0,1", ARRAY_A);
			return $rs;
	}
	
	
	function listAllPaymentTypesCards($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy)
	{
			
		$qry		=	"select * from order_payment_type";
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
	function listAllPaymentTypes($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy,$storename)
	{
		$card_ids="'0'";
		$cards	=	"";
		$storeqry	="select * from payment_methods_stores where store_id ='$storename'";// echo $storeqry;
		$rs		 		= 	$this->db->get_row($storeqry,ARRAY_A);
		$cards		 	= 	$rs["credit_cards"];
		$cardArray = explode('^*^',$cards);
		foreach ($cardArray as $value) {
  			 $card_ids=$card_ids. " , '".$value."'";
		}
		$qry		=	"select * from order_payment_type where id IN($card_ids)";
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	function listAllPaymentModes($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy)
	{
	//print_r($_SESSION['storeSess']);
	
		$qry		=	"select * from order_payment_mode";
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
		
	function listAllMailCheckFields($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy)
	{
		$qry		=	"select * from order_mailcheck_form";
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
	function creditcardDelete($id)
	{
		$this->db->query("DELETE FROM order_payment_type WHERE id='$id'");
			
			$arr=array("status"=>true,"message"=>"The $sId is deleted successfully");
			return $arr;
	}
		
	function paymentTypeMakeActive($id)
	{
		$this->db->query("UPDATE order_payment_mode set active='Y' where id='$id'");
				
			$arr=array("status"=>true,"message"=>"The $sId status updated successfully");
			return $arr;
	}
	function paymentTypeMakeInactive($id)
	{
		$this->db->query("UPDATE order_payment_mode set active='N' where id='$id'");
				
			$arr=array("status"=>true,"message"=>"The $sId status updated successfully");
			return $arr;
	}
	function MailCheckMakeInactive($id)
	{
		$this->db->query("UPDATE order_mailcheck_form set active='N' where id='$id'");
				
			$arr=array("status"=>true,"message"=>"The status updated successfully");
			return $arr;
	}
	function MailCheckMakeActive($id)
	{
		$this->db->query("UPDATE order_mailcheck_form set active='Y' where id='$id'");
				
			$arr=array("status"=>true,"message"=>"The status updated successfully");
			return $arr;
	}
		function MailCheckMakeNotman($id)
	{
		$this->db->query("UPDATE order_mailcheck_form set mandatory='0' where id='$id'");
				
			$arr=array("status"=>true,"message"=>"The status updated successfully");
			return $arr;
	}
	function MailCheckMakeMan($id)
	{
			$this->db->query("UPDATE order_mailcheck_form set mandatory='1' where id='$id'");
				
			$arr=array("status"=>true,"message"=>"The status updated successfully");
			return $arr;
	}
	function MailCheckDelete($id)
	{
		$this->db->query("DELETE FROM order_mailcheck_form  where id='$id'");
				
			$arr=array("status"=>true,"message"=>"The Field deleted successfully");
			return $arr;
	}
	
	function creditCardMakeActive($id)
	{
		$this->db->query("UPDATE order_payment_type set active='Y' where id='$id'");
				
			$arr=array("status"=>true,"message"=>"CreditCard status updated successfully");
			return $arr;
	}
	function creditCardMakeInactive($id)
	{
		$this->db->query("UPDATE order_payment_type set active='N' where id='$id'");
				
			$arr=array("status"=>true,"message"=>"CreditCard status updated successfully");
			return $arr;
	}
	
	function gatewayAddEdit($req,$payment_type)
	{
		extract($req);
	
		if(!trim($login))
		{
			$message 				=	"Login is required";
		}
		
		else
		{
			$array 				= 	array("gateway"=>$gateway,"login"=>$login,"password"=>$password,"transaction_key"=>$transaction_key,"transaction_type"=>$transaction_type);
			$Rs	=	mysql_query("SELECT * FROM order_payment_gateway where type='$payment_type'");
			$rowcount=mysql_num_rows($Rs);
			if($rowcount>0)
			{
				$this->db->update("order_payment_gateway", $array, "type='$payment_type'");
			}
			else
			{
				$array['type'] 	= 	$payment_type;
				$this->db->insert("order_payment_gateway", $array);
				$id = $this->db->insert_id;
			}
			
			return true;
		}
		
		return $message;
	}
	
	function checkAddEdit($req)
	{
		extract($req);
	
		if(!trim($field))
		{
			$message 	=	"Display name is required";
		}
		
		else
		{
			$array 		= 	array("field"=>$field,"position"=>$position);
			if($id)
			{
				$array['id'] 	= 	$id;
				$this->db->update("order_mailcheck_form", $array, "id='$id'");
			}
			else
			{
				$this->db->insert("order_mailcheck_form", $array);
				$id = $this->db->insert_id;
			}
			return true;
		}
		
		return $message;
	}
		
	function madeAddEdit(&$req,$file,$tmpname)
	{
		extract($req);
		if ($file){
			$dir			=	SITE_PATH."/modules/order/images/paymenttype/";
			$file1			=	$dir."thumb/";
			$resource_file	=	$dir.$file;
			$path_parts 	= 	pathinfo($file);

		}
		if(!trim($name))
		{
			$message 				=	"$sId name is required";
		}
		else
		{
			if ($file)
			{
				$array 			= 	array("name"=>$name,"logo_extension"=>".".$path_parts['extension']);
			}
			else{
				$array 				= 	array("name"=>$name);
			}
			if($id)
			{
				$array['id'] 	= 	$id;
				$this->db->update("order_payment_type", $array, "id='$id'");
			}
			else
			{
				$this->db->insert("order_payment_type", $array);
				$id = $this->db->insert_id;
			}
			if ($file)
			{
				$save_filename	=	$id.".".$path_parts['extension'];
				_upload($dir,$save_filename,$tmpname,0);
			}
			return true;
		}
		return $message;
	}
	
	
		
	/**
	 * The following section added by vimson for paypal standard account details maintanance
	 *
	 * @param Array $REQUEST
	 * @return string
	 */
	function validatePaypalStandardForm($REQUEST)
	{
		extract($REQUEST);
		$Msg	=	'';
		
		if(trim($account_mailaddress) == '')
			$Msg	.=	'Mail Address required<br>';
		
		if(trim($account_mailaddress) != '') {
			if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $account_mailaddress)) {
				$Msg	.=	'Valid Mail Address required<br>';
			}
		}
		
		return $Msg;	
	}
	
	
	/**
	 * The following method returns the get the paypal account account details
	 *
	 * @return unknown
	 */
	function getPaypalStandardAccount()
	{
		$Data	=	array();
		$Qry	=	"SELECT value FROM config WHERE field = 'paypal_account_emailaddress' AND category = 'Payment'";
		$Row	=	$this->db->get_row($Qry, ARRAY_A);
		$Data['account_mailaddress']	=	$Row['value'];
		return $Data;
	}
	
	
	/**
	 * The following method saves the paypal standard details
	 *
	 * @param unknown_type $REQUEST
	 * @return unknown
	 */
	function savePaypalStandardAccount($REQUEST)
	{
		extract($REQUEST);
		$Qry	=	"UPDATE config SET value = '$account_mailaddress' WHERE field = 'paypal_account_emailaddress' AND category = 'Payment'";
		$this->db->query($Qry);
		return TRUE;
	}
	
	
	
	
	/**
	 * The following method saves the paypal standard details
	 *
	 * @param unknown_type $REQUEST
	 * @return unknown
	 */
	function saveCallWithCreditcardComments($REQUEST)
	{
		extract($REQUEST);
		$Qry	=	"UPDATE config SET value = '$call_comments' WHERE field = 'callwithcreditcardcomments' AND category = 'Payment'";
		$this->db->query($Qry);
		return TRUE;
	}

	
		
	/**
	 * The following method returns the get the paypal account account details
	 *
	 * @return unknown
	 */
	function getCallWithCreditcardComments()
	{
		$Data	=	array();
		$Qry	=	"SELECT value FROM config WHERE field = 'callwithcreditcardcomments' AND category = 'Payment'";
		$Row	=	$this->db->get_row($Qry, ARRAY_A);
		$Data['call_comments']	=	$Row['value'];
		return $Data;
	}
	
	
	
	/**
	 * The following method returns the paypal account email
	 *
	 * @return unknown
	 */
	function getPaypalAccountEmail()
	{
		$Row	=	$this->getPaypalStandardAccount();
		$account_mailaddress	=	$Row['account_mailaddress'];
		return $account_mailaddress;
	}
	
	
	//============== added by Jeffy on 18th Oct 2007====================
	
	# The following method saves the mail a check details
	function saveMailaCheckComments($REQUEST)
	{
		extract($REQUEST);
		$Qry	=	"UPDATE config SET value = '$call_comments' WHERE field = 'mailacheckcomments' AND category = 'Payment'";
		$this->db->query($Qry);
		return TRUE;
	}
		# The following method returns the get the paypal account account details
	function getMailaCheckComments()
	{
		$Data	=	array();
		$Qry	=	"SELECT value FROM config WHERE field = 'mailacheckcomments' AND category = 'Payment'";
		$Row	=	$this->db->get_row($Qry, ARRAY_A);
		$Data['call_comments']	=	$Row['value'];
		return $Data;
	}
	
	//==================== Ends =======================
	
	function listAllActivePaymentModes()
	{
	//print_r($_SESSION['storeSess']);
	
		$qry		=	"select * from order_payment_mode order by id";
		$rs 		= 	$this->db->get_results($qry, ARRAY_A);
		return $rs;
	}
	
	function saveCurrencyDetails($req, $id='')
	{
		
		if($id==''){
			$this->db->insert("currency", $req);
			$id = $this->db->insert_id;	
		}
		else{
			$this->db->update("currency", $req, "cid='$id'");
		}
		return $id;
	}
	
	function getCurrencyDetails($id)
	{
		$sql	="select * from currency where cid='$id'";
		$rs 	= $this->db->get_row($sql, ARRAY_A);
		return $rs;
		
	}
	
	function getCurrencyList($flag='')
	{
		$sql	="select * from currency ";
		if($flag=='Y'){
			$sql.=" where active= 'Y'";
		}
		$rs 	= $this->db->get_results($sql, ARRAY_A);
		return $rs;
	}
	
	
	
	function changeState($id,$status)
	{
		$array=array('active'=>$status);
		$this->db->update("currency", $array, "cid ='$id'");
		return $id;
	}
	
	function deleteCurrency($id)
	{
		$sql="delete from currency where cid ='$id'";
		$this->db->query($sql);
		return $id;
	}
	function getActiveCurrency()
	{
		$sql	="select * from currency where active='Y'";
		$rs 	= $this->db->get_row($sql, ARRAY_A);
		return $rs;
	}
	function getCurrencyStore($store_id)
	{
		$sql	="select * from currency a inner join store_payment_details b on a.cid=b.currency_id where b.store_id='$store_id' ";
		$rs 	= $this->db->get_row($sql, ARRAY_A);
		return $rs;
	}
	
	function getDefaultCurrency()
	{
		$sql	="select * from currency where default='Y'";
		$rs 	= $this->db->get_row($sql, ARRAY_A);
		return $rs;
	}
	
	function getSaletaxMsg()
	{
		$sql	="select * from sale_tax where active='Y'";
		$rs 	= $this->db->get_results($sql, ARRAY_A);
		return $rs;
	}
	function getSaletaxDetails($store_id)
	{
		$sql	="select * from sale_tax_store where store_id='$store_id'";
		$rs 	= $this->db->get_row($sql, ARRAY_A);
		return $rs;
	}
	function getSaletaxMessage($id)
	{
		$sql	="select * from sale_tax where taxid='$id'";
		$rs 	= $this->db->get_row($sql, ARRAY_A);
		return $rs;
	}

	
	function saveAnalyticsCode($req)
	{
		$id = $req['id'];
		$array= array('analytics_code'=>$req['analytics_code'],'store_id'=>$req['store_id']);
		if($id==''){
			$this->db->insert("google_analytics", $array);
			$id = $this->db->insert_id;	
		}
		else{
			$this->db->update("google_analytics", $array, "id='$id'");
		}
		return $id;
	}
	
	
	
	function getAnalyticsCode($store_id)
	{
		$sql	="select * from google_analytics where store_id='$store_id'";
		$rs 	= $this->db->get_row($sql, ARRAY_A);
		return $rs;
	}
	

}
?>