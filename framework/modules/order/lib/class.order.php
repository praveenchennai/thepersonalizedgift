<?php

include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.order.php");
include_once(FRAMEWORK_PATH."/modules/cart/lib/class.cart.php");

$objCart=new Cart();
class Order extends FrameWork {
	function Order() {
		$this->FrameWork();
	}
	function getOrderfromStore($id)
	{
		$rs = $this->db->get_row("select store_id from cart where id='$id'",ARRAY_A);
		 $store_id	= $rs['store_id'];
		
		if($store_id=="0")
		{
			$store_name	=	"Main Store";
		}
		else
		{
			$rs2 = $this->db->get_row("select heading from store where id='$store_id'",ARRAY_A);
			$store_name	= $rs2['heading'];
		}
		return $store_name; 
	}
	
	function getOrderfromStorename($id)
	{
		$rs = $this->db->get_row("select store_id from orders where id='$id'",ARRAY_A);
		 $store_id	= $rs['store_id'];
		
		if($store_id=="0")
		{
			$store_name	=	"Main Store";
		}
		else
		{
			$rs2 = $this->db->get_row("select heading from store where id='$store_id'",ARRAY_A);
			$store_name	= $rs2['heading'];
		}
		return $store_name; 
	}
	

	function orderList ($sid='', $date_from, $date_to, $pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy, $user_id='',$store_id='') 
	{
		$date=02-23-07;
		
		if($store_id){
			/*$sql = " SELECT o.*, 
						   DATE_FORMAT(date_ordered, '".'%c-%d-%y'.' '.$this->config['time_format']."') date_ordered_f,
						   s.name as order_status_name 
					 FROM orders o, order_status s,order_products op
					 WHERE o.order_status = s.id AND o.id=op.order_id AND op.store_id=$store_id ";*/
			
			
			$sql = 	"SELECT	distinct o.*,
							T2.name as order_status_name,
							DATE_FORMAT(o.date_ordered, '".'%c-%d-%y'.' '.$this->config['time_format']."') date_ordered_f,
							T4.order_type AS M_order_type,
							T4.ship_date AS M_ship_date,
							T4.rush_order AS M_rush_order,
							T4.item_ordered AS M_item_ordered,
							T4.back_order AS M_back_order,
							T4.back_order1 AS M_back_order1,
							T4.date_expected AS M_date_expected,
							T4.order_status AS M_order_status,
							T4.billing_Shipping AS M_billing_Shipping,
							T4.billed_shipping AS M_billed_shipping,
							T4.order_summary AS M_order_summary,
							T4.backorder_summary AS M_backorder_summary
					FROM orders AS o 
					LEFT JOIN order_status AS T2 ON T2.id = o.order_status 
					LEFT JOIN order_products AS T3 ON o.id = T3.order_id 
					LEFT JOIN manual_order AS T4 ON T4.order_number = o.order_number 
					WHERE T3.store_id = '$store_id' ";
			
			
		} else {
			
			/*$sql = " SELECT o.*, 
						  DATE_FORMAT(o.date_ordered, '".'%c-%d-%y'.' '.$this->config['time_format']."') date_ordered_f,
						  s.name as order_status_name 
					 FROM orders o, order_status s
					 WHERE o.order_status = s.id ";*/
			
			$sql = "SELECT	o.*,
							T2.name as order_status_name,
							DATE_FORMAT(o.date_ordered, '".'%c-%d-%y'.' '.$this->config['time_format']."') date_ordered_f, 
							T4.order_type AS M_order_type,
							T4.ship_date AS M_ship_date,
							T4.rush_order AS M_rush_order,
							T4.item_ordered AS M_item_ordered,
							T4.back_order AS M_back_order,
							T4.back_order1 AS M_back_order1,
							T4.date_expected AS M_date_expected,
							T4.order_status AS M_order_status,
							T4.billing_Shipping AS M_billing_Shipping,
							T4.billed_shipping AS M_billed_shipping,
							T4.order_summary AS M_order_summary,
							T4.backorder_summary AS M_backorder_summary 
					FROM orders AS o 
					LEFT JOIN order_status AS T2 ON T2.id = o.order_status 
					LEFT JOIN manual_order AS T4 ON T4.order_number = o.order_number 
					WHERE 1 ";
		}

		if($sid)
		    $sql .= " AND o.order_status = '$sid'" ;
		else
			$sql .= " AND o.order_status != '4' AND o.order_status != '0'" ;

		if($date_from)
			$sql .= " AND o.date_ordered > '$date_from'";

		if($date_to)
			$sql .= " AND o.date_ordered < DATE_ADD('$date_to', INTERVAL 1 DAY)";

		if ($user_id)
			$sql .= " AND o.user_id = '$user_id'";
		
//print_r($sql);exit;
		$rs  = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
	
	function orderList1 ($sid='', $date_from, $date_to, $pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy, $user_id='',$store_id='',$order_status) 
	{
	
		$date=02-23-07;
		if($store_id){
		
		$sql = "SELECT o.*, 
					   DATE_FORMAT(date_ordered, '".'%c-%d-%y'.' '.$this->config['time_format']."') date_ordered_f,
					   s.name as order_status_name 
				  FROM orders o, order_status s,order_products op
				 WHERE o.order_status = s.id AND o.id=op.order_id AND op.store_id=$store_id";
		
		} else {
		
			
			if($order_status	==	'Archives')
				$Filter	=	" WHERE archive = 'Y' and order_status != '100'";
			else
				$Filter	=	" WHERE archive = 'N' and order_status != '100'";
			
			$sql="SELECT * FROM manual_order $Filter ";

		}
	
		$rs  = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);

		return $rs;
	}
	
	
	

	function getOrderProducts($order_id)
	{
		 $sql = "select * from order_products where id='$order_id'";
		
		$rs = $this->db->get_row($sql,ARRAY_A);
		return $rs;
	}
	function getOrderProductsjob($order_id)
	{
		$sql = "select * from order_products_job where order_id='$order_id'";
		$rs = $this->db->get_results($sql);
		//print_r($rs);
		//exit;
		return $rs;
	}
	function getOrderProductsjobComb($order_id)
	{
		$sql = "SELECT * FROM order_products_job, order_products WHERE order_products_job.order_id = '$order_id' AND order_products.order_id = '$order_id'";
		$rs = $this->db->get_results($sql);
		//print_r($rs);
		//exit;
		return $rs;
	}
	function orderProducts ($order_id,$val='') {
		//print_r("JJJJJJJJJJJJJJ");exit;
		/*$sql = "SELECT p.id as product_id,c.*, p.name,
		        p.cart_name as cart_name,
		        SUM(a.price) as accessory_price,p.brand_id as brand_id,
		        b.brand_name as brand_name,
				d.order_number as order_number ,d.id as id
    			  FROM products p 
				  LEFT JOIN brands b on (p.brand_id=b.brand_id), order_products c 
				  LEFT JOIN order_accessory a ON(a.order_product_id = c.id)
				   LEFT JOIN certificates d ON (d.order_id = c.order_id)
    			 WHERE c.product_id = p.id 
    			   AND c.order_id = '$order_id' ";*/
				   
				   $sql = "SELECT p.product_id as pid,p.id as product_id,c.*, p.name,
				   p.cart_name as cart_name,p.image_extension as image_extension,
				   SUM(a.price) as accessory_price,p.brand_id as brand_id,
				   b.brand_name as brand_name,
				   d.order_number as order_number,d.id as cid
    			   FROM products p 
				   LEFT JOIN brands b on (p.brand_id=b.brand_id),
				   order_products c 
				   LEFT JOIN order_accessory a ON(a.order_product_id = c.id)
				    LEFT JOIN certificates d ON (d.order_id = c.order_id)
					
    			   WHERE c.product_id = p.id
    			   AND c.order_id = '$order_id' ";
				   
				   
				   
		/* $sql = "SELECT c.*, p.name,p.cart_name as cart_name,paa.cart_name as acc_cart_name, 
		 		SUM(a.price) as accessory_price,p.brand_id as brand_id,b.brand_name as 
				brand_name FROM products p LEFT JOIN brands b on (p.brand_id=b.brand_id), 
				order_products c LEFT JOIN order_accessory a ON(a.order_product_id = c.id),order_products  aa JOIN product_availabe_accessory paa ON(paa.accessory_id =aa.product_id) 
				WHERE c.product_id = p.id  AND c.order_id = '$order_id' ";*/
				
				 if($store_id){  
					 $sql=$sql."AND c.store_id=	'$store_id'";
				  }
    		  	$sql=$sql." GROUP BY c.id   order by c.id ";
    	
		
		//echo $sql;


    	$rs 	= 	$this->db->get_results($sql);
   		$objProduct 	= 	new Product();
   		$objCategory 	= 	new Category();
		$monogram_price	=	$objProduct->GetMonogramPrice();

    	$total 	= 	0;
		//
    	if($rs) {
		
		
    		$obj->category_name = "Monogram";
    		$obj->name = "Customization";
    		$obj->price = $monogram_price;
	    	for ($i=0; $i<count($rs); $i++) {
				if($val=='N'){
				 $sql = "SELECT a.*, p.name as name,p.cart_name as cart_name,p.type as type, m.category_name
	    				  FROM order_accessory a, product_accessories p, master_category m
	    				 WHERE a.accessory_id = p.id 
	    				   AND m.category_id = a.category_id
	    				   AND order_product_id = '{$rs[$i]->id}'
						   ORDER BY a.accessory_dispaly_order ASC ";
						   
				}else{
				
		
				/*
	    		$sql = "SELECT a.*, p.name, m.category_name,p.cart_name as cart_name,paa.cart_name as available_cart_name
	    				  FROM order_accessory a, product_accessories p, master_category m, product_availabe_accessory paa
	    				 WHERE a.accessory_id = p.id 
	    				   AND m.category_id = a.category_id
	    				   AND order_product_id = '{$rs[$i]->id}'
						   AND paa.product_id='{$rs[$i]->product_id}'
						   AND paa.category_id=a.category_id
						   AND paa.accessory_id=p.id
						   ";
				*/		   
	    		 $sql = "SELECT a.*, p.name, m.category_name,p.cart_name as cart_name,paa.cart_name as available_cart_name
	    				    FROM order_accessory a, product_accessories p, master_category m, product_availabe_accessory paa, product_accessory_group pag
	    				    WHERE a.accessory_id = p.id 
	    				   AND m.category_id = a.category_id
	    				   AND a.order_product_id = '{$rs[$i]->id}'
						   AND paa.product_id='{$rs[$i]->product_id}'
						   AND paa.category_id=a.category_id
						   AND paa.accessory_id=p.id
						   AND pag.id=paa.group_id
						   ORDER BY pag.display_order
						   ";		
						 				   
						   
				}
				
				

				$rs[$i]->accessory = $this->db->get_results($sql);
				//print_r($rs[$i]->accessory_price);
//print_r();exit;
	    		
	    		for ($j=0; $j < count($rs[$i]->accessory); $j++) {
    				$rs[$i]->accessory[$j]->category_name = $objCategory->GetCategoryGroupName($rs[$i]->accessory[$j]->category_id);
    			}
				
				/*
		    	$sql = "SELECT COUNT(*) AS count 
		    			  FROM order_accessory a, product_accessories p, master_category m
		    			 WHERE a.order_product_id = '{$rs[$i]->id}'
		    			   AND a.accessory_id = p.id
		    			   AND a.category_id = m.category_id
		    			   AND m.is_monogram = 'Y'";
		    	$chk_monogram = $this->db->get_row($sql);
		    	if ($chk_monogram->count > 0) {
		    		$rs[$i]->accessory[] = $obj;
		    		$rs[$i]->accessory_price += $monogram_price;
		    	}*/
				$chid=$rs[$i]->id;
				$chktable=$this->checkTableExist('order_products_job');
				if($chktable==1){
					$sq="select height,width,jobname from order_products_job where id='$chid'";
					$st= $this->db->get_results($sq);
					foreach ($st as $ss){
						$height=$ss->height;
						$width=$ss->width;
						$jobname=$ss->jobname;
					}
				}
				###########################################################################
				include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
				$user=new User();
				if($this->config['separate_shipadd_job']=='1'){
					$meadd=$user->getProductShipMap($chid);
					if($meadd){
						$shipadd=$user->getAddress($meadd);
						 $rs[$i]->ship_fname=$shipadd["fname"]; 
						 $rs[$i]->ship_lname=$shipadd["lname"]; 
						 $rs[$i]->ship_add1=$shipadd["address1"]; 
						 $rs[$i]->ship_add2=$shipadd["address2"]; 
						 $rs[$i]->ship_countryname=$shipadd["country_name"]; 
						 $rs[$i]->ship_state=$shipadd["state"]; 
						 $rs[$i]->ship_city=$shipadd["city"]; 
						 $rs[$i]->ship_postalcode=$shipadd["postalcode"]; 
					}

				}
				
				##############################################################################
				//
	    		if($height && $width)
					$area=$height * $width;
					
				else
					$area=1;
				 $rs[$i]->height=$height; 
					  $rs[$i]->width=$width; 
					 $rs[$i]->jobname=$jobname; 
	    		$total += $rs[$i]->quantity * $area * ( $rs[$i]->price + $rs[$i]->accessory_price );
				$AccessoryTotal += $rs[$i]->quantity * $area * ( 0 + $rs[$i]->accessory_price );
				$ProductTotal += $rs[$i]->quantity * $area * (  $rs[$i]->price  + 0 );
	    	}
		//	exit;
    	}
		   
		    //print_r($rs);exit; 	
				
		return array('records'=>$rs, 'total'=>$total, 'AccessoryTotal'=>$AccessoryTotal,'ProductTotal'=>$ProductTotal);
	}

	function getOdrderAccessoryReductionPrice ($order_id,$val='',$coupon_amount,$coupon_type) {
		
				   
				   $sql = "SELECT p.id as product_id,c.*, p.name,
				   p.cart_name as cart_name,
				   SUM(a.price) as accessory_price,p.brand_id as brand_id,
				   b.brand_name as brand_name,
				   d.order_number as order_number,d.id as cid
    			   FROM products p 
				   LEFT JOIN brands b on (p.brand_id=b.brand_id),
				   order_products c 
				   LEFT JOIN order_accessory a ON(a.order_product_id = c.id)
				    LEFT JOIN certificates d ON (d.order_id = c.order_id)
    			   WHERE c.product_id = p.id
    			   AND c.order_id = '$order_id' ";
				   
		
				
				 if($store_id){  
					 $sql=$sql."AND c.store_id=	'$store_id'";
				  }
    		  	$sql=$sql." GROUP BY c.id";
    	
    	$rs 	= 	$this->db->get_results($sql);
   		$objProduct 	= 	new Product();
   		$objCategory 	= 	new Category();
		$monogram_price	=	$objProduct->GetMonogramPrice();

    	$total 	= 	0;
		//
    	if($rs) {
		
		
    		$obj->category_name = "Monogram";
    		$obj->name = "Customization";
    		$obj->price = $monogram_price;
	    	for ($i=0; $i<count($rs); $i++) {
				if($val=='N'){
				 $sql = "SELECT a.*, p.name as name,p.cart_name as cart_name, m.category_name
	    				  FROM order_accessory a, product_accessories p, master_category m
	    				 WHERE a.accessory_id = p.id 
	    				   AND m.category_id = a.category_id
	    				   AND order_product_id = '{$rs[$i]->id}'";
						   
				}else{
				
		
	    		 $sql = "SELECT a.*, p.name, m.category_name,p.cart_name as cart_name,paa.cart_name as available_cart_name
	    				    FROM order_accessory a, product_accessories p, master_category m, product_availabe_accessory paa, product_accessory_group pag
	    				    WHERE a.accessory_id = p.id 
	    				   AND m.category_id = a.category_id
	    				   AND a.order_product_id = '{$rs[$i]->id}'
						   AND paa.product_id='{$rs[$i]->product_id}'
						   AND paa.category_id=a.category_id
						   AND paa.accessory_id=p.id
						   AND pag.id=paa.group_id
						   ORDER BY pag.display_order
						   ";		
						 				   
						   
				}
				
				$rs[$i]->accessory = $this->db->get_results($sql);
				
	    		for ($j=0; $j < count($rs[$i]->accessory); $j++) {
    				$rs[$i]->accessory[$j]->category_name = $objCategory->GetCategoryGroupName($rs[$i]->accessory[$j]->category_id);
    			}
				
				
				$chid=$rs[$i]->id;
				$chktable=$this->checkTableExist('order_products_job');
				if($chktable==1){
					$sq="select height,width,jobname from order_products_job where id='$chid'";
					$st= $this->db->get_results($sq);
					foreach ($st as $ss){
						$height=$ss->height;
						$width=$ss->width;
						$jobname=$ss->jobname;
					}
				}
				

	    		$total += $rs[$i]->quantity * ( $rs[$i]->accessory_price );
	    	}

    	}
		
	if($coupon_type=="P")
		{
			$reduction_total = - $total * $coupon_amount / 100;
			$reduction_total= round($reduction_total, 2);  
		}
		else if($coupon_type=="A")
		{	
			if($coupon_amount > 0)
			{
				$reduction_total = - $coupon_amount;
			}
			else
			{
				$reduction_total =	0;
			}
			$reduction_total= round($reduction_total, 2);  
		}
		
    	return $reduction_total;
	}


	function orderDetails ($order_id, $user_id='') {
		$sql = "SELECT *, DATE_FORMAT(date_ordered, '".$this->config['date_format'].' '.$this->config['time_format']."') date_ordered_f FROM orders WHERE id='{$order_id}'";
		if($user_id) {
			$sql .= " AND user_id='$user_id'";
		}
		$rs = $this->db->get_row($sql);
		//print_r($rs);exit;
		if($rs) {
			$sql = "SELECT name FROM order_status WHERE id='{$rs->order_status}'";
			$rs->status = $this->db->get_var($sql);
			$sql = "SELECT country_name FROM country_master WHERE country_id='{$rs->billing_country}'";
			$rs->billing_country = $this->db->get_var($sql);
			$sql = "SELECT country_name FROM country_master WHERE country_id='{$rs->shipping_country}'";
			$rs->shipping_country = $this->db->get_var($sql);
		}
		return $rs;
	}
	
	#---- Check details ---
	function checkDetails ($order_id) {
		$sql = "SELECT * FROM order_check_details WHERE order_id='{$order_id}'"; 
		$rs = $this->db->get_row($sql);
		return $rs;
	}
	#---- Check details ---
	
	function orderStatus() {
		$sql = "SELECT * FROM order_status WHERE 1";
		$rs['id'] = $this->db->get_col($sql, 0);
        $rs['name'] = $this->db->get_col("", 1);
        return $rs;
	}
	
	function orderUpdateStatus() {
		$sql = "SELECT drop_down_id,value FROM drop_down WHERE group_id=1";
		$rs['drop_down_id'] = $this->db->get_col($sql, 0);
        $rs['value'] = $this->db->get_col("", 1);
        return $rs;
	}
	
	function updateStatus($req,$ostatus) {
		extract($req);

		$this->db->update("orders", array('order_status'=>$order_status, 'shipping_transaction_no'=>$shipping_transaction_no,'ship_date'=>$ship_date,'rush_order'=>$rush_order,'back_order_expected'=>$back_order_expected,'item_ordered'=>$item_ordered,'back_order'=>$back_order,'back_order1'=>$back_order1,'billing_Shipping'=>$billing_Shipping,'billed_shipping'=>$billed_shipping), "id='$order_id'");

		$sql = "SELECT name FROM order_status WHERE id='$order_status'";
		$status = $this->db->get_var($sql);
		$status = "$ostatus - ".date("d M Y H:i:s")." - ".$comments;
		$this->db->query("UPDATE orders SET order_history = CONCAT('<li>', '$status', '</li>', IFNULL(order_history, '')) WHERE id='$order_id'");
		
		
		
		if( trim ( $this->config['order_status_mail'] ) =='Y' )
		$this->sendMail($order_id);
		
	}
	function sendMail($order_id) {
		$order = new Order();
		$ordDetails = $order->orderDetails($order_id);
		
		if($ordDetails->store_id==0){
			$email_header['from'] 	= 	$this->config['admin_email'];  
			$dynamic_vars['STORE_NAME']  = $this->config['site_name'];
		}
		else{
			$res = $this->db->get_row("select email,heading from store where id='{$ordDetails->store_id}'",ARRAY_A); 
			if($res['email']!='')
				$email_header['from'] 	= 	$res['email']; 
			else{
				$res = $this->db->get_row("select m.email,s.heading from member_master m inner join store s on s.user_id=m.id where s.id='{$ordDetails->store_id}'",ARRAY_A); 
				$email_header['from'] 	= 	$res['email']; 
			}
			$dynamic_vars['STORE_NAME']  = $res['heading'];
		}
    	$dynamic_vars['CUSTOMER_NAME']	= $ordDetails->billing_first_name.' '.$ordDetails->billing_last_name;
    	$dynamic_vars['ORDER_NUMBER']	= $ordDetails->order_number;
    	$dynamic_vars['ORDER_DATE']		= $ordDetails->date_ordered_f;
    	$dynamic_vars['ORDER_COMMENTS'] = '<ul>' . $ordDetails->order_history . '</ul>';
    	$dynamic_vars['ORDER_STATUS']	= $ordDetails->status;
		
    	$email_header['to'] 	= 	$ordDetails->billing_email;
    	$email = new Email();		
    	$email->send('order_status', $email_header, $dynamic_vars);
	}
	/**
	 * Assign order to supplier
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message	 
	 */
	function assignSupplier(&$req) {
		extract($req);
		$date	=	date("Y-m-d:H.i.s"); 
		$stat	=	$this->checkQuantity($order_id,$product_id,$base_quantity,$quantity);
		if(!trim($quantity)){
			$message = "Quantity field should not be null";
		}else if($stat==0){
			$message = "No enough quantity is avalabe for Assigning to supplier";
		}else{
			$array = array("order_id"=>$order_id,"product_id"=>$product_id,
							"user_id"=>$user_id,"quantity"=>$quantity,"assigned_date"=>$date);							
			$this->db->insert("order_supplier", $array);
			$id = $this->db->insert_id;
			return true;				
		}		
		return $message;
	}
/**
 * checking Quantity assigned to supplier
 *
 * @param <POST/GET Array> $req
 * @return Boolean/Error Message	 
 */
 function checkQuantity($order_id,$product_id,$base_quantity,$quantity){
 	$query		=	"SELECT SUM(quantity) as total  FROM order_supplier  WHERE order_id=$order_id AND product_id=$product_id";
	$rs			=	$this->db->get_results($query);
	$totVal		=	$rs[0]->total+$quantity;
	
	if($totVal<=$base_quantity){
		return	1;
	}else{
	
		return 0;
	}
 }
 
 /**
 * getting Balance Quantity
 *
 * product_id,order_id
 * @return sum	 
 */
 function quantityBalance($order_id,$product_id){
 	$query		=	"SELECT SUM(quantity) as total  FROM order_supplier  WHERE order_id=$order_id AND product_id=$product_id";
	$rs			=	$this->db->get_results($query);
	$totVal		=	$rs[0]->total;	
	return $totVal;
 }
	 /**
	 * getting Assigned order details
	 *
	 * product_id,order_id
	 * @return array	 
	 */
	 function getAssignedDetails($order_id,$product_id){
		$query	="SELECT a.*,a.quantity as total,a.id as sup_id,
						 b.id as user_id,b.* FROM order_supplier a,member_master b
					WHERE a.user_id=b.id
						 AND a.order_id=$order_id
						 AND a.product_id=$product_id"; 				
		$rs		=	$this->db->get_results($query);
		return $rs;	 
	 }
  /**
	 * For Deleting Supplier Order
	 *
	 * product_id,order_id
	 * @return array	 
 */
 	function supOrderDelete($id){
		$query	=	"DELETE FROM order_supplier WHERE id=$id";
		$status=$this->db->query($query);
		return $status;
	}
	 /**
	 * Get Assigned Products details for supplierf
	 *
	 * user_id
	 * @return array	 
 */
	 function supAssignedOrder($user_id,$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy){
			
			$sql	=	"SELECT a.*,b.* FROM order_supplier a,products b
							WHERE a.product_id=b.id 
								AND a.user_id='$user_id'";
			$rs  	= 	$this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	 }
	 function ChangetoArchieve($archieve_id){	
          foreach($archieve_id as $key => $val) {		 
		  	$this->db->query("UPDATE orders SET order_status = '4' WHERE id = '$val'");
		  }	 
	 }
	 
	  function ChangetoArchieve1($archieve_id){	
          foreach($archieve_id as $key => $val) {		 
		  	$this->db->query("UPDATE orders SET order_status = '4' WHERE id = '$val'");
		  }	 
	 }
	 /**
	 *
	 * Function for inserting order details to orders table
	 *By Jipson
	 */
	 function InsertOrder($file1,$tmpname1,$file2,$tmpname2,$file3,$tmpname3){
	 	//print_r($this->arrData);
		//exit;
	 	$this->db->insert("orders", $this->arrData);
		$id = $this->db->insert_id;
		if ($file1){
				$i++;
				$dir			=	SITE_PATH."/modules/order/images/userorders/";
				$resource_file	=	$dir.$file1;
				$path_parts 	= 	pathinfo($file1);
				$save_filename	=	$id."file1".".".$path_parts['extension'];
					_upload($dir,$save_filename,$tmpname1,1);
			}
		if ($file2){
				$i++;
				$dir			=	SITE_PATH."/modules/order/images/userorders/";
				$resource_file	=	$dir.$file2;
				$path_parts 	= 	pathinfo($file2);
				$save_filename	=	$id."file2".".".$path_parts['extension'];
					_upload($dir,$save_filename,$tmpname2,1);
			}
		if ($file3){
						$i++;
						$dir			=	SITE_PATH."/modules/order/images/userorders/";
						$resource_file	=	$dir.$file3;
						$path_parts 	= 	pathinfo($file3);
						$save_filename	=	$id."file3".".".$path_parts['extension'];
							_upload($dir,$save_filename,$tmpname3,1);
					}



		return $id;
	 }
	 /** Setting the Post array data
    */
    function setArrData($szArrData)
    {
	        $this->arrData = $szArrData;
    }
    /*
    End function setArrDateDate
    Return Post array data
    */
    function getArrData()
    {
        return $this->arrData;
    }
    /*
    End function getArrDate */
	function update(){
		$id=$this->arrData["id"];
		$this->db->update("orders", $this->arrData,"id=$id");
	}
	//Fetching Order Details from orders given Id
    function getCartPrice($oid)
    {
        $sql = "SELECT cart_price FROM orders WHERE id='$oid'";
        return $this->db->get_var($sql);
    }
    
    
    
     function getPastOrderDetails($oid)
     {
     	if ($oid>0) {
	        $sql = "SELECT * FROM orders WHERE id='$oid'";
	        $Row		=	$this->db->get_row($sql, ARRAY_A);
			return $Row;
     	}
     }
    
    
	
	function checkTableExist($tablename){
		$sql ="show table status like '$tablename'";
		$res=$this->db->get_results($sql);
		if($res){
			return 1;
		}
	}
	
	
	/**
	 *	The following method is for generating the manual order number 
	 *
	 *	@author v@newagesmb.com
	 */
	function generateManualOrderNumber()
	{
		$Qry		=	"SELECT COUNT(*) AS TotCount FROM manual_order WHERE MONTH(NOW()) = MONTH(date_ordered)";
		$Row		=	$this->db->get_row($Qry, ARRAY_A);
		$TotCount	=	$Row['TotCount'];
		$TotCount++;
				
		$TotCount	=	str_pad($TotCount, 5, '0', STR_PAD_LEFT);  # Leading zero format
		$Year		=	date('y');	# Leading zero format
		$Month		=	date('m');	# Leading zero format
		$ManualOrderNumber	=	$Year.$Month.$TotCount;

		return $ManualOrderNumber;
	}
	
	
	/**
	 *	The following method returns the manual order details
	 *
	 *	@author v@newagesmb.com
	 */
	function getManualOrderDetailsById($id)
	{
		$Qry		=	"SELECT * FROM manual_order WHERE id = '$id'";
		$Row		=	$this->db->get_row($Qry, ARRAY_A);
		return $Row;
	}
	
	
	/**
	 *	The following method add and edit the manula orders
	 *
	 *
	 *	@author v@newagesmb.com
	 */
	function addEditManualOrder($REQUEST)
	{
		extract($REQUEST);
		
		
		if($id != '') {	# Updates the Manual order

			$UpdateArray	=	array(
									'order_number'		=>	$order_number,
									'customer_name'		=>	$customer_name,
									'item_ordered'		=> $item_ordered,
									'date_ordered'		=>	$date_ordered,
									'ship_date'			=>	$ship_date,
									'rush_order'		=>	$rush_order,
									'order_status'		=>	$order_status,
									'shipping_transaction_no'		=>	$shipping_transaction_no,
									'date_expected'		=>	$date_expected,
									'order_summary'		=>	$order_summary,
									'backorder_summary'	=>	$backorder_summary,
									'comments'			=>	$comments
								);
			
			

			/*if($item_ordered == 'Yes')
				$UpdateArray	=	array_merge($UpdateArray, array('item_ordered'	=>	'Y'));
			else
				$UpdateArray	=	array_merge($UpdateArray, array('item_ordered'	=>	'N'));	*/

			
			if($back_order == 'Yes')
				$UpdateArray	=	array_merge($UpdateArray, array('back_order'	=>	'Y'));
			else
				$UpdateArray	=	array_merge($UpdateArray, array('back_order'	=>	'N'));		
			
			
			if($back_order1 == 'Yes')
				$UpdateArray	=	array_merge($UpdateArray, array('back_order1'	=>	'Y'));
			else
				$UpdateArray	=	array_merge($UpdateArray, array('back_order1'	=>	'N'));			
			
			
			if($billing_Shipping == 'Yes')
				$UpdateArray	=	array_merge($UpdateArray, array('billing_Shipping'	=>	'Y'));
			else
				$UpdateArray	=	array_merge($UpdateArray, array('billing_Shipping'	=>	'N'));				
			
			
			if($billed_shipping == 'Yes')
				$UpdateArray	=	array_merge($UpdateArray, array('billed_shipping'	=>	'Y'));
			else
				$UpdateArray	=	array_merge($UpdateArray, array('billed_shipping'	=>	'N'));					
			
			$this->db->update("manual_order", $UpdateArray, "id='$id'");
			return;
		} else {
		 	
			$InsertArray	=	array();
			
			$InsertArray	=	array(
									'order_number'		=>	$order_number,
									'customer_name'		=>	$customer_name,
									'item_ordered'		=> $item_ordered,
									'date_ordered'		=>	$date_ordered,
									'ship_date'			=>	$ship_date,
									'rush_order'		=>	$rush_order,
									'order_status'		=>	$order_status,
									'shipping_transaction_no'		=>	$shipping_transaction_no,
									'date_expected'		=>	$date_expected,
									'order_summary'		=>	$order_summary,
									'backorder_summary'	=>	$backorder_summary,
									'comments'			=>	$comments
								);
			
			/*if($item_ordered == 'Yes')
				$InsertArray	=	array_merge($InsertArray, array('item_ordered'	=>	'Y'));
			else
				$InsertArray	=	array_merge($InsertArray, array('item_ordered'	=>	'N'));	*/

			
			if($back_order == 'Yes')
				$InsertArray	=	array_merge($InsertArray, array('back_order'	=>	'Y'));
			else
				$InsertArray	=	array_merge($InsertArray, array('back_order'	=>	'N'));		
			
			
			if($back_order1 == 'Yes')
				$InsertArray	=	array_merge($InsertArray, array('back_order1'	=>	'Y'));
			else
				$InsertArray	=	array_merge($InsertArray, array('back_order1'	=>	'N'));			
			
			
			if($billing_Shipping == 'Yes')
				$InsertArray	=	array_merge($InsertArray, array('billing_Shipping'	=>	'Y'));
			else
				$InsertArray	=	array_merge($InsertArray, array('billing_Shipping'	=>	'N'));				
			
			
			if($billed_shipping == 'Yes')
				$InsertArray	=	array_merge($InsertArray, array('billed_shipping'	=>	'Y'));
			else
				$InsertArray	=	array_merge($InsertArray, array('billed_shipping'	=>	'N'));		
			
			$this->db->insert("manual_order", $InsertArray);
			return; 
		}
	
	}
	
	/**
	 *	The following method used for archiving orders
	 *
	 *	@author v@newagesmb.com
	 */
	function archiveManualOrders($REQUEST)
	{
		$ManualOrderIds	=	$REQUEST['order_ids'];
		$order_status	=	$REQUEST['order_status'];
		
		
		foreach($ManualOrderIds as $OrderId) {
			
			if($order_status == 'Archives') 
				$Qry	=	"UPDATE manual_order SET archive = 'N' WHERE id = '$OrderId'";
			else	
				$Qry	=	"UPDATE manual_order SET archive = 'Y' WHERE id = '$OrderId'";
				
			$this->db->query($Qry);
		}
	}
	
	/**
	 *	The follwoing method validates the manual order form
	 *
	 *	@author v@newagesmb.com
	 */	
	function validateManualOrderForm($REQUEST)
	{
		extract($REQUEST);

		$msg	=	'';

		if(trim($order_number) == '') {
			$msg	=	'Order Number required';
			return $msg;
		}	
		
		if($id == '') {
			if($order_number != '') {
				$Qry1		=	"SELECT COUNT(*) AS TotCount FROM manual_order WHERE order_number = '$order_number'";
				$Row1		=	$this->db->get_row($Qry1, ARRAY_A);
				$TotCount	=	$Row1['TotCount'];
				if($TotCount > 0) 
					$msg	=	'Order Number Entered is already exists. Please try once again';
				
				if($TotCount == 0) {
					$Qry2		=	"SELECT COUNT(*) AS TotCount1 FROM orders WHERE order_number = '$order_number'";			
					$Row2		=	$this->db->get_row($Qry2, ARRAY_A);
					$TotCount1	=	$Row2['TotCount1'];
					
					if($TotCount1 > 0) 
						$msg	=	'Order Number Entered is already exists.  Please try once again';
				}
			}
		}	
		
		
		if($id != '') {
			
			$Qry01	=	"SELECT order_number FROM manual_order WHERE id = '$id'";
			$Row01	=	$this->db->get_row($Qry01, ARRAY_A);
			$TMPorder_number	=	$Row01['order_number'];

			if($order_number != $TMPorder_number) {
				$Qry1		=	"SELECT COUNT(*) AS TotCount FROM manual_order WHERE order_number = '$order_number'";
				$Row1		=	$this->db->get_row($Qry1, ARRAY_A);
				$TotCount	=	$Row1['TotCount'];
				if($TotCount > 0) 
					$msg	=	'Order Number Entered is already exists. Please try once again';
				
				if($TotCount == 0) {
					$Qry2		=	"SELECT COUNT(*) AS TotCount1 FROM orders WHERE order_number = '$order_number'";			
					$Row2		=	$this->db->get_row($Qry2, ARRAY_A);
					$TotCount1	=	$Row2['TotCount1'];
					
					if($TotCount1 > 0) 
						$msg	=	'Order Number Entered is already exists.  Please try once again';
				}
			}
		}	

		return $msg;
		
	}
	
	
	/**
	 *	The following method processes the manual order for online oreders
	 *
	 *	
	 *	@author v@newagesmb.com
	 */
	function addEditManualOrderOfOnlineOrders($REQUEST)
	{
		extract($REQUEST);
		
		if(trim($id) != '') { # Update the record
			
			$UpdateArray	=	array(
									'order_number'		=>	$order_number,
									'customer_name'		=>	$customer_name,
									'date_ordered'		=>	$date_ordered,
									'ship_date'			=>	$ship_date,
									'rush_order'		=>	$rush_order,
									'order_status'		=>	$order_status,
									'shipping_transaction_no'		=>	$shipping_transaction_no,
									'date_expected'		=>	$date_expected,
									'order_summary'		=>	$order_summary,
									'backorder_summary'	=>	$backorder_summary,
									'comments'			=>	$comments
								);
			
			

			if($item_ordered == 'Yes')
				$UpdateArray	=	array_merge($UpdateArray, array('item_ordered'	=>	'Y'));
			else
				$UpdateArray	=	array_merge($UpdateArray, array('item_ordered'	=>	'N'));	

			
			if($back_order == 'Yes')
				$UpdateArray	=	array_merge($UpdateArray, array('back_order'	=>	'Y'));
			else
				$UpdateArray	=	array_merge($UpdateArray, array('back_order'	=>	'N'));		
			
			
			if($back_order1 == 'Yes')
				$UpdateArray	=	array_merge($UpdateArray, array('back_order1'	=>	'Y'));
			else
				$UpdateArray	=	array_merge($UpdateArray, array('back_order1'	=>	'N'));			
			
			
			if($billing_Shipping == 'Yes')
				$UpdateArray	=	array_merge($UpdateArray, array('billing_Shipping'	=>	'Y'));
			else
				$UpdateArray	=	array_merge($UpdateArray, array('billing_Shipping'	=>	'N'));				
			
			
			if($billed_shipping == 'Yes')
				$UpdateArray	=	array_merge($UpdateArray, array('billed_shipping'	=>	'Y'));
			else
				$UpdateArray	=	array_merge($UpdateArray, array('billed_shipping'	=>	'N'));	
								
			
			$this->db->update("manual_order", $UpdateArray, "id='$id'");
			return;
			
		}
		
		if(trim($id) == '') { # Create the record
			$InsertArray	=	array();
			
			$InsertArray	=	array(
									'order_number'		=>	$order_number,
									'customer_name'		=>	$customer_name,
									'date_ordered'		=>	$date_ordered,
									'ship_date'			=>	$ship_date,
									'rush_order'		=>	$rush_order,
									'order_status'		=>	$order_status,
									'shipping_transaction_no'		=>	$shipping_transaction_no,
									'date_expected'		=>	$date_expected,
									'order_summary'		=>	$order_summary,
									'backorder_summary'	=>	$backorder_summary,
									'comments'			=>	$comments,
									'order_type'		=>	'ONLINE'
								);
			
			if($item_ordered == 'Yes')
				$InsertArray	=	array_merge($InsertArray, array('item_ordered'	=>	'Y'));
			else
				$InsertArray	=	array_merge($InsertArray, array('item_ordered'	=>	'N'));	

			
			if($back_order == 'Yes')
				$InsertArray	=	array_merge($InsertArray, array('back_order'	=>	'Y'));
			else
				$InsertArray	=	array_merge($InsertArray, array('back_order'	=>	'N'));		
			
			
			if($back_order1 == 'Yes')
				$InsertArray	=	array_merge($InsertArray, array('back_order1'	=>	'Y'));
			else
				$InsertArray	=	array_merge($InsertArray, array('back_order1'	=>	'N'));			
			
			
			if($billing_Shipping == 'Yes')
				$InsertArray	=	array_merge($InsertArray, array('billing_Shipping'	=>	'Y'));
			else
				$InsertArray	=	array_merge($InsertArray, array('billing_Shipping'	=>	'N'));				
			
			
			if($billed_shipping == 'Yes')
				$InsertArray	=	array_merge($InsertArray, array('billed_shipping'	=>	'Y'));
			else
				$InsertArray	=	array_merge($InsertArray, array('billed_shipping'	=>	'N'));		
			
			$this->db->insert("manual_order", $InsertArray);
			return; 
		}
	
	}
	
	
	/**	
	 *	The following method returns the manual order details from the order number
	 *
	 *	@author v@newagesmb.com
	 */
	function getManualOrderDetailsFromOrderNumber($order_number)
	{
		$Qry	=	"SELECT * FROM manual_order WHERE order_number = '$order_number'";
		$Row	=	$this->db->get_row($Qry, ARRAY_A);
		return $Row;
	}
	function selComments($oid)
	{
	    $Qry	=	"SELECT * FROM orders WHERE id = '$oid'";
		$Row	=	$this->db->get_row($Qry, ARRAY_A);
		echo $Row['order_history'];
		exit;
		return $Row;
	}
	
	function deleteOrder($id)
	{
	//$this->db->update("orders", array('order_status'=>$order_status, 'shipping_transaction_no'=>$shipping_transaction_no,'ship_date'=>$ship_date,'rush_order'=>$rush_order,'back_order_expected'=>$back_order_expected,'item_ordered'=>$item_ordered,'back_order'=>$back_order,'back_order1'=>$back_order1,'billing_Shipping'=>$billing_Shipping,'billed_shipping'=>$billed_shipping), "id='$order_id'");

	//$this->db->query("DELETE FROM orders WHERE id='$id'");
	$this->db->query("update orders SET order_status='0' WHERE id='$id'");
    return true;	
	}
	
	function delete_manual_Order($id)
	{
	
	$this->db->query("update manual_order SET order_status='100' WHERE id='$id'");
    return true;	
	}
	function getTableID($table_name)
	{
		$sql = "Select table_id from custom_fields_table where table_name='$table_name'";
		$rs = $this->db->get_row($sql,ARRAY_A);
		return $rs;
	}
	function OrderAccessoryGet($order_id, $ord="",$id)
	{
		
			
			$rs 	=	$this->db->get_results("Select * from order_products where order_id='$order_id' and id='$id' ", ARRAY_A);			



				
	 $product_order_id=$rs[0]['id'];
		
	
	   $sql = "SELECT p.*  FROM order_accessory a, product_accessories p,order_products op
	    				 WHERE  a.accessory_id =  p.id  AND   a.order_product_id=op.id  AND op.order_id = '$order_id' AND a.order_product_id ='$product_order_id' order by a.accessory_dispaly_order asc";
						 
		 $rs 	= 	$this->db->get_results($sql,ARRAY_A);
		 return $rs;
	}
	//End
	
	########created by jinson on 23rd janauary,2008
	########To Display the order report for all users and site Non members
	
	function show_order($pageNo=0, $limit = 10, $params='',$date_from,$date_to){
		$cond="1";
		if($date_from)
			$cond .= " AND A.date_ordered > '$date_from'";
		
		if($date_to)
			$cond .= " AND A.date_ordered < DATE_ADD('$date_to', INTERVAL 1 DAY)";
		$sql="SELECT A.client_ip,A.user_id,count(*) as Total_order , B.username ,sum(A.total_price ) as Total_Price FROM `orders` A LEFT JOIN  member_master B ON A.user_id=B.id AND A.total_price >0  WHERE $cond group by A.client_ip,A.user_id";
	
		$rs = 	$this->db->get_results_pagewise($sql, $pageNo, $limit, $params,ARRAY_A);
		
		/*foreach($rs as $value){
		
		
		   	$pgtotal = $pgtotal + $value['Total_Price'];
			$pgorder = $pgorder + $value['Total_order'];
		}
		$rs["page_total"]=$pgtotal;
		$rs["order_total"]=$pgorder;*/
		return $rs;
		//return array($rs,$numpad);
		
	}
	#########End
	
	#### Function to show the total earning from website in a date range........
	#### Author	:	Jipson Thomas...............................................
	#### Dated	:	18 january 2008.............................................
	function show_earning($pageNo=0, $limit = 10, $params='',$date_from,$date_to){
		$cond="";
		if($date_from)
			$cond .= " AND a.date_ordered > '$date_from'";
		
		if($date_to)
			$cond .= " AND a.date_ordered < DATE_ADD('$date_to', INTERVAL 1 DAY)";
		$sql="SELECT b.order_id,a.order_number, count( b.product_id ) as Total_product , sum( b.total_price ) as Total_Price FROM `order_products` b INNER JOIN `orders` a ON a.id = b.order_id WHERE  a.order_number!='' $cond GROUP BY b.order_id ";
	     $rs 	= 	$this->db->get_results_pagewise($sql, $pageNo, $limit, $params,ARRAY_A);
		/*list($rs1,$numpad1)  	= 	$this->db->get_results_pagewise($sql, $pageNo, $limit, $params,ARRAY_A);
		foreach($rs1 as $row){
			$pgtotal = $pgtotal + $row['Total_Price'];
			
		}
		$rs["page_total"]=$pgtotal;*/
		//print_r($rs);
		return $rs;
		//return array($rs,$numpad);
		
	}
	#### Function to show the Product Report of an order........................
	#### Author	:	Jipson Thomas...............................................
	#### Dated	:	18 january 2008.............................................
	function getOrderProductReport($pageNo=0, $limit = 10, $params='',$order_id){
	
		$sql="SELECT b.name, a.quantity, a.total_price FROM `order_products` a INNER JOIN products b ON a.product_id = b.id
         WHERE a.order_id =$order_id ";
		list($rs,$numpad)  	= 	$this->db->get_results_pagewise($sql, $pageNo, $limit, $params,ARRAY_A);
		//print_r($rs);exit;
		foreach($rs as $row){
			$pgtotal = $pgtotal + $row['total_price'];
		}
		$rs["page_total"]=$pgtotal;
		//print_r($rs);exit;
		return array($rs,$numpad);
	}
	
	#### End of the function.....................................................
	
	#### Function to show the total earning amount from an order in a date range.
	#### Author	:	Jipson Thomas................................................
	#### Dated	:	18 january 2008..............................................
	function total_earning_order($order_id){
		
		$sql="SELECT sum(a.total_price) as gtotal FROM `order_products` a WHERE a.order_id =$order_id GROUP BY a.order_id";
		$res		=	$this->db->get_results($sql,ARRAY_A);
		return $res[0]['gtotal'];
		
	}
	/** function for getting the status of the order
	#### Author	:	vinoy jacob..
	 */
	function getDropdownStatus($status)
	{
	    $Qry	=	"SELECT value FROM drop_down WHERE drop_down_id = '$status'";
		$Row	=	$this->db->get_row($Qry, ARRAY_A);
		$os=$Row['value'];
		return $os;
	
	}
	function orderTransactionDetails($order_id,$type)
	{
		$sql = "SELECT * FROM order_transaction_details WHERE order_id ='{$order_id}' AND type='{$type}' "; 
		$rs = $this->db->get_row($sql);
		return $rs;
	}
	#### Function to Download a file from server.
	#### Author	:	Jipson Thomas................................................
	#### Dated	:	17 April 2008..............................................
	function allFiledownload($path,$filename,$type){
		$file = $filename.".".$type;
		header('Content-Disposition: attachment; filename="'.$file.'"');
		readfile($path.$file);
		//exit;
	}
	#### Function get Gangrun List.............................................
	#### Author	:	Jipson Thomas..............................................
	#### Dated	:	16 May 2008................................................
	function getGangrunList(){
		
		$stdate=$this->db->get_row("SELECT min( date_ordered ) as stdate FROM `orders` ",ARRAY_A);
		$strtdate=$stdate["stdate"];
		$fdayname=date("D",$strtdate);
		$tdayname=date("D");
		$glist=array();
		$glist[0]["startdate"]	=	date("Y-m-d",strtotime($strtdate));
	 	if($fdayname=="Sat"){
			$glist[0]["enddate"]	=	date("Y-m-d",$strtdate);
			$glist[0]["gangname"]	=	"Gang Run :".date("Y-m-d",$strtdate);
		}elseif($fdayname=="Sun"){
			$glist[0]["enddate"]	=	date("Y-m-d",strtotime($strtdate)+(7 * 24 * 60 * 60));
			$glist[0]["gangname"]	=	"Gang Run :".date("Y-m-d",strtotime($strtdate)+(7 * 24 * 60 * 60));
		}elseif($fdayname=="Mon"){
			$glist[0]["enddate"]	=	date("Y-m-d",strtotime($strtdate)+(6 * 24 * 60 * 60));
			$glist[0]["gangname"]	=	"Gang Run :".date("Y-m-d",strtotime($strtdate)+(6 * 24 * 60 * 60));
		}elseif($fdayname=="Tue"){
			$glist[0]["enddate"]	=	date("Y-m-d",strtotime($strtdate)+(5 * 24 * 60 * 60));
			$glist[0]["gangname"]	=	"Gang Run :".date("Y-m-d",strtotime($strtdate)+(5 * 24 * 60 * 60));
		}elseif($fdayname=="Wed"){
			$glist[0]["enddate"]	=	date("Y-m-d",strtotime($strtdate)+(4 * 24 * 60 * 60));
			$glist[0]["gangname"]	=	"Gang Run :".date("Y-m-d",strtotime($strtdate)+(4 * 24 * 60 * 60));
		}elseif($fdayname=="Thu"){
			$glist[0]["enddate"]	=	date("Y-m-d",strtotime($strtdate)+(3 * 24 * 60 * 60));
			$glist[0]["gangname"]	=	"Gang Run :".date("Y-m-d",strtotime($strtdate)+(3 * 24 * 60 * 60));
		}elseif($fdayname=="Fri"){
			$glist[0]["enddate"]	=	date("Y-m-d",strtotime($strtdate)+(2 * 24 * 60 * 60));
			$glist[0]["gangname"]	=	"Gang Run :".date("Y-m-d",strtotime($strtdate)+(2 * 24 * 60 * 60));
		}
		
		
		if($tdayname=="Sat"){
			$endtime=time();
		}elseif($tdayname=="Sun"){
			$endtime=time()+(7 * 24 * 60 * 60);
		}elseif($tdayname=="Mon"){
			$endtime=time()+(6 * 24 * 60 * 60);
		}elseif($tdayname=="Tue"){
			$endtime=time()+(5 * 24 * 60 * 60);
		}elseif($tdayname=="Wed"){
			$endtime=time()+(4 * 24 * 60 * 60);
		}elseif($tdayname=="Thu"){
			$endtime=time()+(3 * 24 * 60 * 60);
		}elseif($tdayname=="Fri"){
			$endtime=time()+(2 * 24 * 60 * 60);
		}
		$i=1;
		$stweek=strtotime($glist[0]["enddate"]);
		$week=$stweek;
		while($week<$endtime){
			$glist[$i]["enddate"]	=	date("Y-m-d",$week);
			$glist[$i]["gangname"]	=	"Gang Run :".date("Y-m-d",$week);
			$week	=	$week + (7 * 24 * 60 * 60);
			$i++;
		}
		return $glist;
	}
	
	#### Function to get File Ids and Extensions of an order.....................
	#### Author	:	Jipson Thomas................................................
	#### Dated	:	19 May 2008..................................................
	function getFileIdExt($id){
		$det=$this->db->get_results("select id,file1_ext,file2_ext,order_id from order_products_job where order_id=$id",ARRAY_A);
		return $det;
		//exit;
	}
	#### Function to Order Status Of Order.......................................
	#### Author	:	Jipson Thomas................................................
	#### Dated	:	21 May 2008..................................................
	function updateOrderStatus($id,$status){
		$this->db->query("update orders SET order_status=$status WHERE id='$id'");
    	return true;	
	}
	
	function getIpnMessage()
	{
		$rs=$this->db->get_results("SELECT * FROM ipn_log WHERE  form_ipn='order' ORDER BY log_time DESC");
		return $rs;
		
	}


	function getIpnStatusByOrderSessId($order_sess_id)
	{
		$rs=$this->db->get_results("SELECT *, concat(payment_status, ' ', pending_reason) as ps FROM ipn_log WHERE  order_sess_id=$order_sess_id ORDER BY log_time DESC LIMIT 1 ");
		return $rs;
		
	}
}
?>