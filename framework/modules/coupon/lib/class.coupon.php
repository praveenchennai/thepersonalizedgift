<?php
class  Coupon extends Framework
{
	
	private $coupon;

	function __construct()
	{
		$this->FrameWork();
		$this->coupon =	array();
	}
	
	function __set($property,$value)
	{
		$this->$coupon[$property] = $value;
	}
	
	function __get($property)
	{
		$this->$coupon[$property];
	}
	
	
	public function addEditCoupon($req,$id,$store_id)
	{
		extract($req);
		
		$arr = array();
		$arr['coupon_title'] = $coupon_title;
		$arr['coupon_code'] = $coupon_code;
		$arr['coupon_startdate'] = $coupon_startdate;
		$arr['coupon_enddate'] = $coupon_enddate;
		$arr['coupon_min_amount'] = $coupon_min_amount;
		$arr['discount_item_id'] = $discount_item_id;
		$arr['coupon_one_time'] = $coupon_one_time;
		$arr['discount_mode_id'] = $discount_mode_id;
		$arr['coupon_active'] = $coupon_active;
		$arr['coupon_discount'] = $coupon_discount;
		$arr['store_id'] = $store_id;
		
		if($id) {
			$this->db->update("store_coupons", $arr, "coupon_id='$id'");
		} else {
				$this->db->insert("store_coupons", $arr);
			$id 	= 	$this->db->insert_id;
		}			
		
		return id;
		
	}
	
	
	public function getCouponDetails($coupon_id)
	{
		$sql = "SELECT *,DATE_FORMAT(coupon_startdate,'%Y-%m-%d') AS coupon_startdate,DATE_FORMAT(coupon_enddate,'%Y-%m-%d') AS coupon_enddate   FROM store_coupons WHERE coupon_id = $coupon_id";
		$rs = $this->db->get_row($sql,ARRAY_A);
		return $rs;
	}
	
	public function getCouponDetailsById($coupon_code,$active='',$store_id,$coupon_id='')
	{
		$sql = "SELECT s.*,DATE_FORMAT(coupon_startdate,'%Y-%m-%d') AS sdate,DATE_FORMAT(coupon_enddate,'%Y-%m-%d') AS edate,sc.item_value,d.mode 
				FROM store_coupons s
				LEFT JOIN store_coupon_discount_items sc on s.discount_item_id= sc.item_id
				LEFT JOIN store_coupon_discount_mode d ON s.discount_mode_id = d.mode_id
				WHERE coupon_code ='$coupon_code' AND store_id ='$store_id' ";
		
		if(!empty($active))
		{
			 $sql .= " AND coupon_active = 'Y' "	;
		}
		if(!empty($coupon_id))
		{
			 $sql .= " AND coupon_id != '$coupon_id' "	;
		}
			
		$rs = $this->db->get_row($sql,ARRAY_A);
		return $rs;
	}
		
	public function getCouponList($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$store_id)
	{
		$sql= "SELECT a.*,COUNT(b.coupon_id) AS coupon_used  FROM store_coupons a
					LEFT JOIN store_coupon_usage_history b ON a.coupon_id = b.coupon_id
					WHERE a.store_id=$store_id 
					GROUP BY a.coupon_id";
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
	public function deleteCoupon($coupon_id)
	{
		$sql = "delete from `store_coupons` where coupon_id=$coupon_id ";
		$this->db->query($sql);
		return true;
	}
	
	
	public function getCouponDiscountItems()
	{
		$sql = "SELECT *  FROM store_coupon_discount_items ";
		$rs = $this->db->get_results($sql,ARRAY_A);
		return $rs;
	}
	
	
	public function getCouponDiscountMode()
	{
		$sql = "SELECT *  FROM store_coupon_discount_mode ";
		$rs = $this->db->get_results($sql,ARRAY_A);
		return $rs;
	}
	
	public function validateCoupons($key,$trans_amt,$store_id)
	{
	
		$cur_date		=	date("Y-m-d");		
		$arr			=	array();	
		$coupon_det = $this->getCouponDetailsById($key,'Y',$store_id,'');
		
		//print_r($coupon_det);
		
		if($coupon_det['coupon_id'] ==''){
			$arr['key_exist']		=	'fail';
		}else{
			$arr['key_exist']		=	'pass';
		}	
		
		if($coupon_det['edate'] <= $cur_date  &&  $coupon_det['sdate'] >= $cur_date ){
				$arr['date_status']		=	'pass';
				$arr['date_message']	=	$coupon_det['edate'];		
		}else if ($coupon_det['edate'] < $cur_date){
			
				$arr['date_status']		=	'edate_fail';
				$arr['date_message']	=	$coupon_det['edate'];
		}
		else if ($coupon_det['sdate'] > $cur_date){
			
				$arr['date_status']		=	'sdate_fail';
				$arr['date_message']	=	$coupon_det['edate'];
		}
		
		// substract from product or totak
		$arr['substract_from']		=	$coupon_det['item_value'];
		// end

	
		
		if($coupon_det['coupon_one_time'] >0){
			

			$count = $this->gethistoryCount($coupon_det['coupon_id']);
			if($count >=$coupon_det['coupon_one_time']){
				$arr['usage_status']		=	'fail';
			}
			else{
				$arr['usage_status']		=	'pass';
			}
		}else{
			$arr['usage_status']		=	'pass';
		}	
		
		
		if($trans_amt < $coupon_det['coupon_min_amount'])
		{
			$balance_amount = $coupon_det['coupon_min_amount']-$trans_amt;
			$arr['amount_status']		=	'fail';
			$arr['balance_message']		=	$coupon_det['coupon_min_amount'];
		}
		else
		{
			$arr['amount_status']		=	'pass';
			$arr['balance_message']		=	$coupon_det['coupon_min_amount']; // to get the percentage value
		}
		
		$arr['min_coupon_amt'] =	$coupon_det['coupon_min_amount'];
		$arr['coupon_use']	=	$coupon_det['item_value'];
		$arr['coupon_type'] = $coupon_det['mode'];
		$arr['amount'] = $coupon_det['coupon_discount'];
		$arr['coupon_id'] = $coupon_det['coupon_id'];
		

		return 	$arr;
	
	}
	
	function gethistoryCount ($id) {
		$query	=	"SELECT * FROM store_coupon_usage_history WHERE coupon_id =$id";
		$rs 	=	 $this->db->get_results($query);
		$count	=	 count($rs);		
		return $count;
	}
	
	
	public function getCouponReport($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$store_id)
	{
		$sql= "SELECT a.*,b.first_name,b.last_name,c.item_name,d.billing_first_name,d.billing_last_name,e.coupon_title FROM store_coupon_usage_history a
				LEFT JOIN member_master b ON a.user_id = b.id
				LEFT JOIN store_coupon_discount_items c ON  a.substract_from = c.item_value
				LEFT JOIN orders d ON a.order_id = d.id
				LEFT JOIN store_coupons e ON a.coupon_id = e.coupon_id
				WHERE a.store_id=$store_id";
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
	

}
?>