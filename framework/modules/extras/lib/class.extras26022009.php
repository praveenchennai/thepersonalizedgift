<?php
/**
 * Admin Coupons and Gift Cirtificate
 *
 * @author Ajith
 * @package defaultPackage
 */
class Extras extends FrameWork {
  var $coupon_no;
  var $coupon_start;
  var $coupon_end;  
  var $coupon_amount;
  var $coupon_amounttype;
  var $coupon_options;  
  var $one_times;
  var $coupon_createddate;
  function Extras($coupon_no="", $coupon_start="",$coupon_end="",$coupon_amount="",$coupon_amounttype="",$coupon_options="",$one_times="",$coupon_createddate="") {
	$this->coupon_no 			= 	$coupon_no;
	$this->coupon_start 		= 	$coupon_start;
	$this->coupon_amount		=	$coupon_amount;	
	$this->coupon_amounttype	=	$coupon_amounttype;
	$this->coupon_options 		= 	$coupon_options;
	$this->one_times 			= 	$one_times;
	$this->coupon_createddate	=	$coupon_createddate;	
	$this->FrameWork();
  }
	/**
	 * Listing coupon with pagination
	 * @param <Page Number> $pageNo
	 * return array
	 */
	function listAllCoupons($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy){	
		$qry		=	"SELECT * FROM coupons  WHERE  active='Y'";
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	/**
	 * getting coupon based on given id
	 * @param <id> $id	
	 * return row as array	
	 */
	function couponGet ($id) {
		$rs 	=	 $this->db->get_row("SELECT * FROM coupons WHERE id='{$id}'", ARRAY_A);
		return $rs;
	}
	/**
	 * Deleting coupon based on given id
	 * @param <id> $id	
	 * return boolean Message
	 */
	function couponDelete ($id) {
		$true	=	$this->db->query("UPDATE  coupons SET active='N' WHERE id='$id'");
		if($true){
			return true;
		}
	}
	/**
	 * Add Edit Coupons
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message	 
	 */
	 function checkDuplicateCoupon ($coupon_no,$newID) {
	    $q1     =    "SELECT * FROM coupons WHERE coupon_no='{$coupon_no}' AND id<>'{$newID}'";
		$rs 	=	 $this->db->get_row($q1, ARRAY_A);
		if ( $rs ) {
		   return true;
		} else{
		   return false;
		}
	 }
	 
	 
	function couponAddEdit (&$req) {
		extract($req);						
		$coupon_createddate=date("Y-m_d");
		if($coupon_amounttype=='F'){
			$coupon_amount	=	0;
		}				
		
		
		/*  Coupon Key Auto Generation
		$alphanum  	= 	"abcd789efghijk01lmnopqr2stu345vwxyz6";
		do{				
			$coupon_no	= 	substr(str_shuffle($alphanum), 0, 7);		
			$checkNo	=	$this->checkCouponexist($coupon_no);
		}while($checkNo!=0);
		if($checkNo==0){
			$inscoupon_no = $coupon_no;
		}		
		*/
		
		$inscoupon_no = trim($coupon_no);
		
		if( $this->checkDuplicateCoupon ($inscoupon_no,$id) ) {
		    $message = "Coupon Key Already Exist";
		}else if(!trim($inscoupon_no)) {
			$message = "Coupon Key Required";
		} else if(!trim($coupon_start)) {
			$message = "Start date is Required";
		} else if($coupon_end<$coupon_start){
			$message = "End date is not less than start date";
		}else {				
			$array = array("coupon_name"=>$coupon_name,"coupon_no"=>$inscoupon_no, "coupon_start"=>$coupon_start, "coupon_end"=>$coupon_end, 
							"coupon_amount"=>$coupon_amount,"coupon_amounttype"=>$coupon_amounttype, 
							"coupon_options"=>$coupon_options,"one_times"=>$one_times,"coupon_createddate"=>$coupon_createddate);
			if($id) {
				unset($arry['coupon_no']);
				$array['id'] 	= 	$id;
				$this->db->update("coupons", $array, "id='$id'");
			} else {
				$this->db->insert("coupons", $array);
				$id 	= 	$this->db->insert_id;
			}			
			return true;
		}
		return $message;
	}	
	/**
	 * assign coupons to particular user
	 * @param <POST/GET Array> $req
	 * return Message
	 */
	function asignUser (&$req) {
		extract($req);		
		 $updateQuery	=	"UPDATE  coupons SET user_id='$user_id' WHERE id=$coupon_id";
			$true			=	$this->db->query($updateQuery);
		if($true){
			return 1;
		}
	}
	/**
	 *Listing coupons in combo 
	 *
	 * return array
	 */
function couponList () {
        $sql				= "SELECT id, coupon_name  FROM coupons  WHERE user_id=0 AND active='Y'";
		$rs['id'] 			= $this->db->get_col($sql, 0);
        $rs['coupon_name'] 	= $this->db->get_col("", 1);		
        return $rs;
    }
	/**
	 *Listing usage history under given coupon
	 *  
	 * @param <coupon_id> $coupon_id
	 * @param <Page Number> $pageNo
	 * return array
	 */	
	function listHistory($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy){	
		$coupon_id	=	$_REQUEST['id'];				
		$qry		=	"SELECT a.id AS his_id,a.*,b.* FROM trans_history a,coupons b  WHERE  a.trans_id='$coupon_id' AND a.trans_id=b.id AND a.trans_type='C' AND a.active='Y'";
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}	
	/**
	 * getting amount sum based on coupon id
	 * @param <coupon id> $coupon id
	 * @param <Page Number> $pageNo
	 */
	function getHistorysum ($id,$type,$active='') {	
			$query	=	"SELECT SUM(trans_useamount) as amt FROM trans_history WHERE trans_id='$id' AND trans_type='$type'";
		if($active=='Y'){
			$query	=	$query." AND active ='Y'";
		}
		$rs 	=	 $this->db->get_results($query);		
		$amt	=	$rs[0]->amt;		
		return $amt;
	}
	/**
	 * Listing Certificate(Product) with pagination
	 *
	 * @param <Page Number> $pageNo
	 * return array
	 */
	function listallCertificate($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy){	
		$qry		=	"SELECT * FROM  products  WHERE  active='Y' AND is_giftcertificate='Y'";
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	/**
	 * Listing Certificate with pagination
	 *
	 * @param <Page Number> $pageNo
	 * return array
	 */
	function listuserCertificate($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy,$certi_id=0){
		//$certi_id	=	$_REQUEST['id'];		
		//print($certi_id);
		if($certi_id>0){		
			$qry		=	"SELECT a.id as certi_id,a.active as newstatus,a.*,b.* FROM  certificates a,products b WHERE   a.product_id=b.id AND a.id='$certi_id'";
		}elseif($_REQUEST['id']>0){
			$qry		=	"SELECT a.id as certi_id,a.active as newstatus,a.*,b.* FROM  certificates a,products b WHERE   a.product_id=b.id AND a.product_id='$_REQUEST[id]'";
		}else{
			$qry		=	"SELECT a.id as certi_id,a.active as newstatus,a.*,b.* FROM  certificates a,products b WHERE   a.product_id=b.id ";
		}
		//die($qry);
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		//echo "<pre>";
		//print_r($rs);
		//exit;
		return $rs;
	}	
	/**
	 * Add Edit Certificates
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message	 
	 */
	function certificateAddEdit (&$req) {		
		extract($req);		
		//print_r($req);
		//exit;
		
		$propartyDetails			=	$this->getProparty($product_id);
		if($propartyDetails['duration'] == 0){
			$propartyDetails['duration'] = 30;
		}
		$duration					=	"+".$propartyDetails['duration']." days";					
		$certificate_createddate	=	date("Y-m-d");		
		$expiryDate 				= 	date("Y-m-d", strtotime($duration));						
		$alphanum  					= 	"7890123456";
		do{				
			$certificate_no			= 	substr(str_shuffle($alphanum), 0, 5);
			$checkNo				=	$this->checkCertificateexist($certificate_no);
		}while($checkNo!=0);
		if($checkNo==0){
			$inscertificate_no 		= 	$certificate_no;			
		}
		if(empty($user_id))
			$user_id=0;
		if(empty($active))
			$active='N';
		else
			$active='Y';
		if(!trim($certi_amount)) {
			$message = "amount required";		
		} else {
			$array = array("order_id"=>$order_id,"order_number"=>$order_number,"user_id"=>$user_id,"product_id"=>$product_id,"certi_number"=>$inscertificate_no, "certi_amount"=>$certi_amount, 
							"certi_startdate"=>$certificate_createddate,"certi_enddate"=>$expiryDate,"active"=>$active); 
			if($id) {
				unset($arry['certi_number']);
				$array['id'] 	= 	$id;
				$this->db->update("certificates", $array, "id='$id'");
			} else {;
				$this->db->insert("certificates", $array);
				$id 			= 	$this->db->insert_id;
				$certi = $this->getcertificate($id);
				$cert_id = $certi[0]->product_id;
				extract($certi);
				$array_cert = array("product_id"=>$product_id, "certificate_id"=>$certi[0]->id, "no_times"=>0,"type_option"=>one);
				$this->db->insert("certificate_property", $array_cert);
			}			
			return true;
		}
		return $message;
	}	
	/**
	 *Listing usage history under given gift certificate
	 *  
	 * @param <Page Number> $pageNo
	 * return array
	 */	
	function listgiftHistory($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy){	
		$gift_id	=	$_REQUEST['id'];				
		$qry		=	"SELECT a.id AS his_id,a.*,b.*,a.order_id FROM trans_history a,certificates b  WHERE  a.trans_id='$gift_id' AND a.trans_id=b.id AND a.trans_type='G'";
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}	
	 /**
	 * getting giftcertificate based on given id
	 * @param <id> $id
	 * @param <Page Number> $pageNo
	 */
	function cetificateGet ($id) {	
		$rs 	=	 $this->db->get_row("SELECT * FROM certificates WHERE id='{$id}'", ARRAY_A);
		return $rs;
	}	
	 /**
	 * getting product proparty based on given product id
	 * @param <product_id> $product_id	
	 * return array
	 */
	function getProparty ($product_id) {	
		$rs 	=	 $this->db->get_row("SELECT * FROM certificate_property WHERE product_id ='{$product_id}'", ARRAY_A);
		return $rs;
	}	
	 /**
	 * getting giftcertificate based on given id with availabe proparties
	 * @param <id> $id
	 * @param <Page Number> $pageNo
	 */
	function GetgiftCertificate ($id) {
		$query	="SELECT a.id as cert_id,a.*,b.* FROM certificates a,certificate_property b 
				  WHERE a.id='{$id}' AND a.id=b.certificate_id";	
		$rs 	=	 $this->db->get_row($query, ARRAY_A);
		return $rs;
	}	
	/**
	 * getting giftcertificate based on given certificate number
	 * @param <id> $id
	 * @param <Page Number> $pageNo
	 */
	function getCertificateDetails($certificate_id){
		$query			=	"SELECT a.id as certi_id,a.*,b.* FROM certificates a,certificate_property b
							WHERE a.certi_number='$certificate_id' AND a.id=b.certificate_id and a.active='Y'";
		$rs 			=	$this->db->get_row($query, ARRAY_A);
		return $rs;
	}
	 /**
	 * Loading Products in combo
	 * return array
	 */
	 function productCombo () {
        $sql				= "SELECT * FROM  products  WHERE  active='Y' AND is_giftcertificate='Y'";
		$rs['id'] 			= $this->db->get_col($sql, 0);
        $rs['name'] 	= $this->db->get_col("", 2);		
       return $rs;
    }
	/**
	 * getting history count
	 * @param <coupon id> $coupon id
	 * @param <tran_type> $type
	 * return integer value
	 */
	function gethistoryCount ($id,$type) {
		$query	=	"SELECT * FROM trans_history WHERE trans_id=$id AND trans_type='$type'";
		$rs 	=	 $this->db->get_results($query);
		$count	=	 count($rs);		
		return $count;
	}
	 /**
	 * checking Random Number for coupons exist in the db
	 * @param <coupon_no> $coupon_no	
	 * return count
	 */
	function checkCouponexist($coupon_no){
		$query	=	"SELECT * FROM coupons WHERE coupon_no='$coupon_no' AND active='Y'";
		$rs		=	$this->db->get_results($query);
		$count	=	count($rs);
		return $count;
	}		
	 /**
	 * checking Random Number  for certificates  exist in the db
	 * @param <certi_number> $certi_no	
	 * return count	 
	 */
	function checkCertificateexist($certi_no){
		$query	=	"SELECT * FROM certificates WHERE certi_number='$certi_no' AND active='Y'";
		$rs		=	$this->db->get_results($query);
		$count	=	count($rs);
		return $count;
	}
	/**
	 * Getting  Coupon details by given coupon number 
	 * @param <coupon_no> $coupon_no	 
	 */
	function getCoupondetails($coupon_no){
		$query			=	"SELECT * FROM coupons WHERE coupon_no='$coupon_no' AND active='Y'";
		$rs 			=	$this->db->get_row($query, ARRAY_A);
		return $rs;
	}
	/**
	 * Assign Coupon/Certificate validation
	 * @param <trans_no> $trans_no
	 * @param <trans_type> $trans_type	
	 * @param <trans_amount> $trans_amount 
	 */
	 function useExtrafeatures($trans_no,$trans_amount,$trans_type){	
		if($trans_type=='C'){
			$trasStatus		=	$this->validateCoupons($trans_no,$trans_amount);
		}else if($trans_type=='G'){
			$trasStatus		=	$this->validateCertificates($trans_no,$trans_amount);
		}
		return $trasStatus;
	}
	/**
	 * Validating Coupons
	 * @param <trans_no> $trans_no
	 * @param <trans_amount> $amount	 
	 */
	function validateCoupons($trans_no,$trans_amount){		
		$cur_date		=	date("Y-m-d");		
		$array			=	array();		
		$rs				=	$this->getCoupondetails($trans_no);	
		if(count($rs)==0){
			$array['key_exist']		=	'fail';
		}else{
			$array['key_exist']		=	'pass';
		}	
		if($rs['coupon_amounttype']=='A'){
			$sum			=	$this->getHistorysum($rs['id'],'C');
			$balance_amount =	$rs['coupon_amount']-$sum;	
			if($trans_amount<=$balance_amount){
				$array['amount_status']		=	'pass';
				$array['balance_message']	=	$balance_amount;
			}else{				
				$array['amount_status']		=	'fail';
				$array['balance_message']	=	$balance_amount;
			}
		}else{
				$array['amount_status']		=	'pass';
				$array['balance_message']	=	$rs['coupon_amount']; // to get the percentage value
		}		
		if($rs['coupon_end']<=$cur_date){
				$array['date_status']		=	'fail';
				$array['date_message']		=	$rs['coupon_end'];		
		}else{
				$array['date_status']		=	'pass';
				$array['date_message']		=	$rs['coupon_end'];
		}
		if($rs['coupon_options']=='one'){
			$count	=	$this->gethistoryCount ($rs['id'],'C');
			if($count>=1){
				$array['usage_status']		=	'fail';
			}else{
				$array['usage_status']		=	'pass';
			}
		}else if($rs['coupon_options']=='fixed'){
			$count	=	$this->gethistoryCount ($rs['id'],'C');
			if($count>=$rs['one_times']){
				$array['usage_status']		=	'fail';
			}else{
				$array['usage_status']		=	'pass';
			}			
		}else if($rs['coupon_options']=='unlimit'){
				$array['usage_status']		=	'pass';
		}	
		$array['coupon_type'] = $rs['coupon_amounttype'];
		return 	$array;	
	}
	/**
	 * Validating Certificates
	 * @param <trans_no> $trans_no
	 * @param <trans_amount> $amount	 
	 */
	function validateCertificates($trans_no,$trans_amount){	
		$cur_date		=	date("Y-m-d");		
		$array			=	array();		
		$rs 			=	$this->getCertificateDetails($trans_no);
		if(count($rs)==0){
			$array['key_exist']		=	'fail';
		}else{
			$array['key_exist']		=	'pass';
		}			
		if($rs['certi_enddate']<=$cur_date){
				$array['date_status']		=	'fail';
				$array['date_message']		=	$rs['certi_enddate'];		
		}else{
				$array['date_status']		=	'pass';
				$array['date_message']		=	$rs['certi_enddate'];
		}		
		$sum								=	$this->getHistorysum($rs['certi_id'],'G');
		$balance_amount 					=	$rs['certi_amount']-$sum;	
		if($trans_amount<=$balance_amount){
				$array['amount_status']		=	'pass';
				$array['balance_message']	=	$balance_amount;
		}else{				
				$array['amount_status']		=	'fail';
				$array['balance_message']	=	$balance_amount;
		}
		if($rs['type_option']=='one'){
			$count							=	$this->gethistoryCount ($rs['certi_id'],'G');
			if($count>=1){
				$array['usage_status']		=	'fail';
			}else{
				$array['usage_status']		=	'pass';
			}
		}else if($rs['type_option']=='fixed'){
			$count	=	$this->gethistoryCount ($rs['certi_id'],'G');
			if($count>=$rs['no_times']){
				$array['usage_status']		=	'fail';
			}else{
				$array['usage_status']		=	'pass';
			}			
		}else if($rs['type_option']=='unlimit'){
				$array['usage_status']		=	'pass';
		}	
		return $array;
	}
	/**
	 * Updating transaction history while using coupon or certificate
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message
	 **/
	function updateHistory(&$req){
		extract($req);		
		$current_date			=	date("Y-m-d");		
		$valid	=	$this->useExtrafeatures($req['trans_no'],$req['trans_amount'],$req['trans_type']);
		if($trans_type=='C'){
			$coupon_array		=	$this->getCoupondetails($req['trans_no']);
			$trans_id			=	$coupon_array['id'];
		}else{
			$certificate_array	=	$this->getCertificateDetails($req['trans_no']);
			$trans_id			=	$certificate_array['certi_id'];
		}
		if($valid['amount_status']=='pass' && $valid['date_status']=='pass' && $valid['usage_status']=='pass'){
			$array				=	array("trans_id"=>$trans_id,"order_id"=>$req['order_id'],"trans_useamount"=>$req['trans_amount'],
									"trans_usagedate"=>$current_date,"trans_type"=>$req['trans_type'],"active"=>'Y');
			//print_r($array);
			
			$this->db->insert("trans_history", $array);
			$id 				= 	$this->db->insert_id;			
			return $valid;
		}else{			
			return $valid;
		}
	}
	/**
	 * Assigne user_id to the particular certificate and make it active
	 *
	 * @param <user_id,certi_no> $user_id,$certi_no
	 * @return Boolean/Error Message
	 **/
	function activateCertificate($user_id,$certi_no) {
		$array			=	array("user_id"=>$user_id,"active"=>'Y');		
		$this->db->update("certificates", $array, "certi_number='$certi_no'");
		return true;
	}
	/**
	 * Getting Coupon history details based on particular order_id
	 *
	 * @param <order_id> $order_id
	 * return array
	 **/
	function historyByOrderid($order_id,$trans_type){
		if($trans_type=='C'){
			$query	=	"SELECT a.*,b.* FROM trans_history a,coupons b WHERE a.order_id = '$order_id'
						 AND a.trans_id=b.id AND a.trans_type='C'";
		}else if($trans_type=='G'){
			$query	=	"SELECT a.*,b.*,c.* FROM trans_history a,certificates b,products c WHERE a.order_id = '$order_id'
						AND a.trans_id=b.id AND a.trans_type='G' AND b.product_id=c.id";
		}
		$rs		=	$this->db->get_row($query);
		return $rs;
	}
	/**
	 * Getting Coupon details based on user_id
	 *
	 * @param <user_id> $user_id
	 * return array
	 **/	
	  function couponByuserid($user_id){	 		 	
			$query	=	"SELECT * FROM coupons WHERE user_id = $user_id";				
			$rs		=	$this->db->get_results($query);			
			$count	=	count($rs);			
			$arr = array();			
			$cnt=0;	
			if ($rs) {
        		for ($i=0;$i<$count;$i++) {								
					$sum				= 	$this->getHistorysum ($rs[$i]->id,'C');	
					$balance_amt		=	$rs[$i]->coupon_amount - $sum;			
					$arr[$cnt++] 		=	array("coupon_name"=>$rs[$i]->coupon_name,"coupon_no"=>$rs[$i]->coupon_no,"coupon_start"=>$rs[$i]->coupon_start,"coupon_end"=>$rs[$i]->coupon_end,
											"coupon_amount"=>$rs[$i]->coupon_amount,"coupon_amounttype"=>$rs[$i]->coupon_amounttype,"sum"=>$sum,"balance_amount"=>$balance_amt,"coupon_options"=>$rs[$i]->coupon_options,"one_times"=>$rs[$i]->one_times);
				}
			}
			return $arr;
		}	
	/**
	 * Getting Certificate details based on user_id
	 *
	 * @param <user_id> $user_id
	 * return array
	 **/	
	  function giftByuserid($user_id){
	  		$query	=	"SELECT a.id as certi_id,a.*,b.* FROM certificates a,products b WHERE a.user_id = $user_id AND a.product_id=b.id";				
			$rs		=	$this->db->get_results($query);				
			$count	=	count($rs);			
			$arr = array();			
			$cnt=0;	
			if ($rs) {
        		for ($i=0;$i<$count;$i++) {								
					$sum				= 	$this->getHistorysum ($rs[$i]->certi_id,'G');	
					$balance_amt		=	$rs[$i]->certi_amount - $sum;				
					$arr[$cnt++] 		=	array("name"=>$rs[$i]->name,"certi_number"=>$rs[$i]->certi_number,"certi_amount"=>$rs[$i]->certi_amount,
											"certi_startdate"=>$rs[$i]->certi_startdate,"certi_enddate"=>$rs[$i]->certi_enddate,"sum"=>$sum,"balance_amt"=>$balance_amt);
				}
			}			
			return $arr;			
		}
		
		//created by Jeffy on 5th Oct 2007
		function GetcertProdDetails ($id) {
			$query	="SELECT a.name, a.price, a.date_created ,b.* FROM products a,certificate_property b 
					  WHERE a.id='{$id}' AND a.id=b.product_id and certificate_id=''";	
			$rs 	=	 $this->db->get_row($query, ARRAY_A);
			return $rs;
		}	
		function GetCertiProp($id){
			$query	=	"SELECT * FROM certificate_property WHERE certificate_id='$id'";
			$rs		=	$this->db->get_row($query,ARRAY_A);
			return $rs;
		}
		function GetCertiProductId($id){
			$query	=	"SELECT product_id FROM certificates WHERE id='$id'";
			$rs		=	$this->db->get_row($query,ARRAY_A);
			return $rs;
		}
		function GetOrderNumber($order_id){
			$query	=	"SELECT order_number FROM orders WHERE id='$order_id'";
			$rs		=	$this->db->get_row($query,ARRAY_A);
			return $rs;
		}
		function getCertificatesByOrderNum($order_number){
			$query			=	"SELECT a.id as certi_id,a.*,b.* FROM certificates a,certificate_property b
								WHERE a.order_number='$order_number' AND a.product_id=b.product_id and a.active='Y'";
			$rs 			=	$this->db->get_row($query, ARRAY_A);
			return $rs;
		}
		function GetOrderId($order_number){
			$query	=	"SELECT id FROM orders WHERE order_number='$order_number'";
			$rs		=	$this->db->get_results($query);
			return $rs;
		}
		function checkDuplicateCertificate ($certi_number,$id) {
			$q1 = "SELECT * FROM certificates WHERE certi_number='{$certi_number}' AND id<>'{$id}'";
			$rs 	=	 $this->db->get_row($q1, ARRAY_A);
			if ( $rs ) {
			   return true;
			} else{
			   return false;
			}
		 }
		function certiDelete ($id) {
			$certi = $this->getcertificate($id);
			$cert_id = $certi[0]->product_id;
			if($this->db->query("DELETE FROM certificates WHERE id='$id'")){
				if($this->db->query("DELETE FROM certificate_property WHERE product_id='$cert_id'"))
					return true;
			}
		}
		function getcertificate($id){
			$query	=	"SELECT * FROM certificates WHERE id='$id'";
			$rs		=	$this->db->get_results($query);
			return $rs;
		}
		function certificateUpdate(&$req) {		
			extract($req);
			$rsProd = $this->GetCertiProductId($id);
			if(!$product_id)
				$product_id = $rsProd[product_id];
			$inscoupon_no = trim($certi_number);
			if( $this->checkDuplicateCertificate ($inscoupon_no,$id) ) {
				$message = "Certificate Number Already Exist";
			}else{
				if($certi_amounttype == "F"){
					$array = array("product_id"=>$product_id, "certi_number"=>$certi_number, "certi_amount"=>0,"certi_amounttype"=>$certi_amounttype,
						"certi_startdate"=>$certi_startdate,"certi_enddate"=>$certi_enddate);
				}else{
					$array = array("product_id"=>$product_id, "certi_number"=>$certi_number, "certi_amount"=>$certi_amount,"certi_amounttype"=>$certi_amounttype,
						"certi_startdate"=>$certi_startdate,"certi_enddate"=>$certi_enddate);
				}
				if($id) {
					$array['id'] 	= 	$id;
					$this->db->update("certificates", $array, "id='$id'");
					$certi = $this->getcertificate($id);
					$cert_id = $certi[0]->product_id;
					extract($certi);
					if($coupon_options == "fixed"){
						$array_cert = array("no_times"=>$one_times,"type_option"=>$coupon_options);
					}else{
						$array_cert = array("no_times"=>0,"type_option"=>$coupon_options);
					}
					$certificate_id = $certi[0]->id;
					$this->db->update("certificate_property", $array_cert, "certificate_id='$certificate_id'");
					return true;
				}else{
					$product_id = $array[product_id];
					$this->db->insert("certificates", $array);
					$id = mysql_insert_id();
					$certi = $this->getcertificate($id);
					$cert_id = $certi[0]->product_id;
					extract($certi);
					if($coupon_options == "fixed"){
						$array_cert = array("product_id"=>$product_id, "certificate_id"=>$certi[0]->id, "no_times"=>$one_times,"type_option"=>$coupon_options);
					}else{
						$array_cert = array("product_id"=>$product_id, "certificate_id"=>$certi[0]->id, "no_times"=>0,"type_option"=>$coupon_options);
					}
					$this->db->insert("certificate_property", $array_cert);
					return true;
				}
			}
			return $message;
		}
		//---- End -----
		
		/**
	 * The following method returns all subscriptions defined in the system
	 * 
	 * @author vimson@newagesmb.com
	 *
	 */
		function getAllSubscriptions($CouponId = '')
		{
			$Qry1			=	"SELECT T1.id AS id, T1.name AS name, T2.id AS CouponPackageId, T2.deduction_amount AS deduction_amount 
							FROM subscription_master AS T1 
							LEFT JOIN coupon_subpackages AS T2 ON T2.subscription_id = T1.id AND T2.coupon_id = '$CouponId' 
							WHERE T1.active = 'Y' ";
			$SubScriptions	=	$this->db->get_results($Qry1, ARRAY_A);
			return $SubScriptions;
		}
		
					
}//end class

?>