<?php
//error_reporting(0);
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.order.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/extras/lib/class.extras.php");
include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");


class Cart extends FrameWork {	
	function Cms() {
        $this->FrameWork();
    }
    function addToCart(&$req) {
		$objPrice		=	new Price();
    	$objProduct 	= 	new Product();
    	$objAccessory	=	new Accessory();
    	extract($req);  
		//print_r($req);exit; 	
			if(!$price) {
				$total_price = $objProduct->calulate_price($req);
				//echo $total_price;
				//exit;
					$product_price	=	$objPrice->GetPriceOfProduct($product_id, 0,$qty);
			} else {
					$product_price	=	$total_price = $price;
			}
			$arr=array("user_id"=>$user_id, "sess_id"=>session_id(), "product_id"=>$product_id, "quantity"=>$qty, "price"=>$product_price, "total_price"=>$total_price, "date_added"=>date("Y-m-d H:i:s"), "store_id"=>$store_id, "notes"=>$notes, "contact_me"=>$contact_me);
			if ($this->config["cart_restricted_amt"] == "Y"){
					$sql = "SELECT *  FROM cart	 WHERE user_id = '$user_id'";
    				$rs = $this->db->get_results($sql, ARRAY_A);
				    if(count($rs)>=1){
						return "false";
					}
		     }
			if($height)	
				$arr1["height"]=$height;
			if($width)	
				$arr1["width"]=$width;
			if($jobname)
				$arr1["jobname"]=$jobname;
			if($materialcheck)
				$arr1["materialcheck"]=$materialcheck;	
			$cart_id = $this->db->insert("cart",$arr);
			$arr1["cart_id"]=$cart_id;
			$arr1["sess_id"]=session_id();
			
			$chk=$this->checkTableExist("cart_job")	;
			if($chk==1)
				$this->db->insert("cart_job",$arr1);		
			
    		//print_r($req);
    		if ($req["prd_image"])
			{	$source_path = $req["image_path"];
				if ($req["image_ext"])
				{
					$im_ext= $req["image_ext"];
				}
				else 
				{
					$im_ext = "jpg";
				}
				$dest_path   = SITE_PATH."/modules/cart/images/";	
				$thumb       = $dest_path."thumb/"; 
				$save_filename = "$cart_id.$im_ext";
				copy($source_path."$prd_image.$im_ext",$dest_path.$save_filename);
				if ($this->config['product_thumb_image'])
				{
					list($thumb_width,$thumb_height)	=	split(',',$this->config['product_thumb_image']);
				}
				else 
				{
					$thumb_width = 100;
					$thumb_height = 100;
				}
				

				
				thumbnail($dest_path,$thumb,$save_filename,$thumb_width,$thumb_height,$mode,$save_filename);
				if ($req["image_del"]=="Y")
				{
					@unlink($source_path."$prd_image.jpg");
				}
				if ($this->config["artist_selection"] == "Yes"){
					$sql = "DELETE FROM product_saved_work WHERE id = ".$req['save_id'];
    				$this->db->query($sql);
				}
				
			
	
				
			}
    		if ($req["rear_image"])
			{	$source_path = $req["image_path"];
				$rear_image = $req["rear_image"];
				if ($req["image_ext"])
				{
					$im_ext= $req["image_ext"];
				}
				else 
				{
					$im_ext = "jpg";
				}
				$dest_path   = SITE_PATH."/modules/cart/images/";	
				$thumb       = $dest_path."thumb/"; 
				$cart_rear   = $cart_id."_rear";
				$save_filename = "$cart_rear.$im_ext";
				copy($source_path."$rear_image.$im_ext",$dest_path.$save_filename);
				if ($this->config['product_thumb_image'])
				{
					list($thumb_width,$thumb_height)	=	split(',',$this->config['product_thumb_image']);
				}
				else 
				{
					$thumb_width = 100;
					$thumb_height = 100;
				}
				
				thumbnail($dest_path,$thumb,$save_filename,$thumb_width,$thumb_height,$mode,$save_filename);
				if ($req["image_del"]=="Y")
				{
					@unlink($source_path."$rear_image.jpg");
				}
				
			}	
										
							
    		if($req['access']) {	
				$newVal=array();
	    		foreach ($req['access'] as $key=>$val) {
					if(is_array($val)){
						$newVal		=	$val;
					}else{
						$newVal[0]	=	$val;						
					}
					foreach ($newVal as $val2) {				
						if ($val2) {
							$accessory  =   $objAccessory->GetAccessory($val2);
								if($height and $width)
										$area=$height*$width;
									else
										$area=1;
								if($accessory['is_price_percentage']=="Y") { 
									$price	=	($product_price*$accessory['adjust_price'])/100;
								}elseif($accessory['independent_qty']=="Y"){
									$acprice=$accessory['adjust_price']/$qty;
								
									$acprice  =  $acprice/$area;
									$price		=	$acprice;
								//	echo $price;
								} else {
									$price	=	$accessory['adjust_price']/$area;
								}
								if ($custom_cat[$key] == "Other") {
									$custom_text1 = $custom_text[$key];						
							
								} elseif ($custom_cat[$key] == "None") {
									$custom_text1 = "";
								} else {
									$custom_text1 = $objProduct->getCustomizationText($custom_cat[$key]);
								}
								$this->db->insert("cart_accessory", array("cart_id"=>$cart_id, "category_id"=>$key, "accessory_id"=>$val2, "price"=>$price, 
																  "customization_text"=>$custom_text1, 
																  "addl_customization_text"=>$addl_custom[$key],
																  "wrap_text"=>$wrap_text_[$key]));
						}
															//exit;

					}
				}
    		}
			return $cart_id;
    
	}
    
	
	function updateInventory (&$cartArray)	{
		global $global;
    	$session_id = session_id();

		foreach($cartArray as $k=>$cartRS){
			$ExistQty	=	$this->db->get_row("select name,out_stock,out_message,quantity from products where id={$cartRS->product_id}",ARRAY_A);
			/* */
			$CartQty    = 	$this->db->get_row("SELECT sum(quantity) as quantity FROM cart WHERE sess_id='$session_id' and product_id = {$cartRS->product_id}",ARRAY_A);
			/**/
			if ( ($ExistQty['quantity'] < $CartQty['quantity'] ) && ($global['outofstock_message_status'] == 'Y') ) {
				if ( trim( $ExistQty['out_message'] )	)
				$cartArray[$k]->StockMessage = $ExistQty['out_message'];
				else
				$cartArray[$k]->StockMessage = $global['outofstock_message'];
				/*
				$from=$this->config['site_name']."(".$this->config['admin_email'].")";
				$subject="Out of Stock";
				$msg="<html><body>";
				$msg=$msg."<strong>".$this->config['site_name']." Out of Stock</strong><br><br>";
				$msg=$msg."Product ID:- ".$cartRS->product_id."<br>";
				$msg=$msg."Product Name:- ".$ExistQty["name"]."<br>";
				$msg=$msg."Available Quantity:- ". intval($ExistQty['quantity'] )."<br>";
				$msg=$msg."Ordered Quantity:- ". $CartQty['quantity'] ."<br>";
				//$msg=$msg."Password: ".$usr["password"];
				$msg=$msg."</body></html>";

				sendMail($to,$subject,$msg,$from,'HTML');
				*/
			}
		}

	}
	
	
	
     function updateCart (&$req) {
	    //$notes=$_REQUEST['notes'];
		//$contact_me=$_REQUEST['contact_me'];
		$objPrice		=	new Price();
		$objProduct 	= 	new Product();
    	$session_id = session_id();
		if($req['cart']) {
			foreach ($req['cart'] as $cart_id=>$quantity) {
			
			
				list($product_id,$price,$total_price)	=	$this->db->get_row("select product_id,price,total_price from cart where id='$cart_id'",ARRAY_N);
    			/*echo "product_id: $product_id<br>";
				echo "price: $price<br>";
				echo "total_price: $total_price<br>";
				echo "quantity: $quantity<br>------<br>";*/
				$quantity = intval(abs($quantity));
				if($objProduct->IsGiftCertificate($product_id)==false)
					{
					$product_price	=	$objPrice->GetPriceOfProduct($product_id, 0,$quantity);
					if($product_price!=$price)
						{
						$diff	=	$price-$product_price;
						$price	=	$product_price;
						$total_price	=	$total_price-$diff;
						}
					/*echo "product_id: $product_id<br>";
					echo "price: $price<br>";
					echo "total_price: $total_price<br>";
					echo "quantity: $quantity<br>------<br>";*/
					}
					
					if($quantity==0)
					{
											
						$sql = "DELETE cart, cart_accessory 
								  FROM cart LEFT JOIN cart_accessory ON (cart.id = cart_accessory.cart_id) 
								 WHERE cart.sess_id = '$session_id' ";
						
							$sql .= "AND cart.id='$cart_id'";
						
						$this->db->query($sql);
						
						$dest_path   = SITE_PATH."/modules/cart/images/";	
						$thumb       = $dest_path."thumb/"; 
						@unlink($dest_path."$cart_id.jpg");
						@unlink($thumb."$cart_id.jpg");
						redirect(makeLink(array("mod"=>"cart", "pg"=>"default"), "act=view"));
					}
				
    			#$this->db->update("cart", array("quantity"=>$quantity,"price"=>$price, "total_price"=>$total_price,"contact_me"=>$contact_me,"notes"=>$notes), "id='$cart_id' AND sess_id='$session_id'");
				$this->db->update("cart", array("quantity"=>$quantity,"price"=>$price, "total_price"=>$total_price), "id='$cart_id' AND sess_id='$session_id'");#to avoid the updation of the cart table with additional notes
    		}
			/*echo "<br>";
		exit;*/
    	}
    }
	
	
	function reduceQty() {
		global $global;

		if(!$_SESSION['sess_id_old'])
		{
    		$session_id = session_id();
		}else
		{
			$session_id=$_SESSION['sess_id_old'];
		}
		
		
		# Begin Deducting Quantity if Exist #
			$sql = "SELECT product_id,sum(quantity) as quantity FROM cart WHERE sess_id='$session_id' GROUP BY product_id";
			$rs = $this->db->get_results($sql, ARRAY_A);
			 /*product_id  	 quantity
					595 	    6
					600 	    1*/
			$OutStockProducts	=	'';		
			foreach($rs as $results) {
				$quantity = $results['quantity'];
				$pID	  = $results['product_id'];
				$sql1	  = "UPDATE products SET quantity=quantity-{$quantity} WHERE id = {$pID} AND quantity>0";
				$this->db->query($sql1);
				
				
				# Check Quantity of the Products
				$CurrQty	=	$this->db->get_row("select name,out_stock,out_message,quantity from products where id={$pID}",ARRAY_A);
				if ( ($CurrQty['quantity'] < 1 ) ) {   #&& ($CurrQty['out_stock'] == 'Y') {
					$OutStockProducts=$OutStockProducts."Product ID:- ".$pID."<br>";
					$OutStockProducts=$OutStockProducts."Product Name:- ".$CurrQty["name"]."<br>";
					#$OutStockProducts=$OutStockProducts."Ordered Quantity:- ". $quantity ."<br>";
					$OutStockProducts=$OutStockProducts."<br>"."<div>---------------------------</div>"."<br>";
					
					if ( $CurrQty['quantity'] < 0 )
					$this->db->query("UPDATE products SET quantity=0 WHERE id = {$pID}");
					
				}
				
			}
			# Sending Mail to Admin
			if (trim($OutStockProducts) ) {
				$from=$this->config['site_name']."(".$this->config['admin_email'].")";
				$subject="Out of Stock";
				$msg="<html><body>";
				$msg=$msg."<strong>".$this->config['site_name']." Out of Stock</strong><br><br>";
				$msg=$msg.$OutStockProducts;
				$msg=$msg."</body></html>";
				sendMail($to,$subject,$msg,$from,'HTML');
			}
		# End Deducting Quantity if Exist #
		
		
	}
	
	
    
    function deleteCart ($id="") {
	
    	if(!$_SESSION['sess_id_old'])
		{
    		$session_id = session_id();
		}else
		{
			$session_id=$_SESSION['sess_id_old'];
		}
    	
		
    	$sql = "DELETE cart, cart_accessory 
    			  FROM cart LEFT JOIN cart_accessory ON (cart.id = cart_accessory.cart_id) 
    			 WHERE cart.sess_id = '$session_id' ";
    	if ($id) {
    		$sql .= "AND cart.id='$id'";
    	}
		//print_r($sql);exit;
    	$this->db->query($sql);
    	
    	$dest_path   = SITE_PATH."/modules/cart/images/";	
		$thumb       = $dest_path."thumb/"; 
		@unlink($dest_path."$id.jpg");
		@unlink($thumb."$id.jpg");
		
    }
    
    function userCartMergeOnLogin($user_id) {
    	$session_id = session_id();
    	
    	$this->db->update("cart", array("user_id"=>$user_id), "sess_id='$session_id'");
    }

    function getCart ($val='',$ses_id="") {	
		global $global;
		global $store_id;		
    		if(!$ses_id)
		{
    	$session_id = session_id();
		}else
		{
			$session_id=$ses_id;
		}
    	
    	/*$sql = "SELECT p.id as product_id,c.*, p.display_name as name,p.cart_name as cart_name, SUM(a.price) as accessory_price,p.brand_id as brand_id,b.brand_name as brand_name
    			  FROM products p LEFT JOIN brands b on (p.brand_id=b.brand_id), cart c LEFT JOIN cart_accessory a ON(a.cart_id = c.id)
    			 WHERE c.product_id = p.id
    			   AND c.sess_id = '$session_id' ";
				 if($store_id){  
					 $sql=$sql."AND c.store_id=	'$store_id'";
				  }
    		  	$sql=$sql." GROUP BY c.id";  */
				
				
					/*$sql = "SELECT p.id as product_id,c.*, p.display_name as name,p.cart_name as cart_name, SUM(a.price) as accessory_price,p.brand_id as brand_id,b.brand_name as brand_name
					  FROM products p LEFT JOIN brands b on (p.brand_id=b.brand_id), cart c LEFT JOIN cart_accessory a ON(a.cart_id = c.id)
					 WHERE c.product_id = p.id
					   AND c.sess_id = '$session_id' ";
					
					$sql=$sql." GROUP BY c.id";*/
				
				if($global['payment_receiver']!='store')//this condition added on  31-12-2008
				{
				$sql = "SELECT p.id as product_id,c.*, p.display_name as name,p.cart_name as cart_name, SUM(a.price) as accessory_price,p.brand_id as brand_id,b.brand_name as brand_name
					  FROM products p LEFT JOIN brands b on (p.brand_id=b.brand_id), cart c LEFT JOIN cart_accessory a ON(a.cart_id = c.id)
					 WHERE c.product_id = p.id
					   AND c.sess_id = '$session_id' ";
					
					$sql=$sql." GROUP BY c.id";
				}else
				{
				$sql = "SELECT p.id as product_id,c.*, p.display_name as name,p.cart_name as cart_name, SUM(a.price) as accessory_price,p.brand_id as brand_id,b.brand_name as brand_name
					  FROM products p LEFT JOIN brands b on (p.brand_id=b.brand_id), cart c LEFT JOIN cart_accessory a ON(a.cart_id = c.id)
					 WHERE c.product_id = p.id
					   AND c.sess_id = '$session_id' AND c.store_id='$store_id' ";
					
					$sql=$sql." GROUP BY c.id";
				}
				
				//$global['payment_receiver'];
				
    	
    	$rs 	= 	$this->db->get_results($sql);

   		$objProduct 	= 	new Product();
   		$objCategory 	= 	new Category();
   		$monogram_price	=	$objProduct->GetMonogramPrice();
    	$total 	= 	0;
    	if($rs) {
    		$obj->category_name = "Monogram";
    		$obj->name = "Customization";
    		$obj->price = $monogram_price;
			
    		for ($i=0; $i<count($rs); $i++) {
			if($val=='N'){
	    		 $sql = "SELECT a.*, p.display_name as name,p.name as aname, p.cart_name as cart_name, m.category_name
	    				  FROM cart_accessory a, product_accessories p, master_category m
	    				 WHERE a.accessory_id = p.id 
	    				   AND m.category_id = a.category_id
	    				   AND cart_id = '{$rs[$i]->id}'";					  
						  
			 }else{
			 
			/*
						 	$sql = "SELECT a.*, p.display_name as name,p.cart_name as cart_name, m.category_name,paa.cart_name as available_cart_name
	    				  FROM cart_accessory a, product_accessories p, master_category m, product_availabe_accessory paa
	    				 WHERE a.accessory_id = p.id 
	    				   AND m.category_id = a.category_id
	    				   AND cart_id = '{$rs[$i]->id}'
						   AND paa.product_id='{$rs[$i]->product_id}'
						   AND paa.category_id=a.category_id
						   AND paa.accessory_id=p.id
						   ";			

			*/ 
			 
				$sql = "SELECT a.*, p.display_name as name , p.name as name1,p.cart_name as cart_name, m.category_name,paa.cart_name as available_cart_name 
	    				  FROM cart_accessory a, product_accessories p, master_category m, product_availabe_accessory paa, product_accessory_group pag
	    				 WHERE a.accessory_id = p.id 
	    				   AND m.category_id = a.category_id
	    				   AND cart_id = '{$rs[$i]->id}'
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
				$chk=$this->checkTableExist("cart_job")	;
				
				if($chk==1){
				
					 $st="SELECT cart_id, jobname,height,width,materialcheck FROM cart_job WHERE sess_id = '$session_id'  AND cart_id = '{$rs[$i]->id}'";
					 $ss=$this->db->get_results($st);
					 $rs[$i]->jobname=$ss[0]->jobname;
					 $rs[$i]->height=$ss[0]->height;
					 $rs[$i]->width=$ss[0]->width;
					 $rs[$i]->materialcheck=$ss[0]->materialcheck;
				}


			
		    	/*$sql = "SELECT COUNT(*) AS count 
		    			  FROM cart_accessory a, product_accessories p, master_category m
		    			 WHERE a.cart_id = '{$rs[$i]->id}'
		    			   AND a.accessory_id = p.id
		    			   AND a.category_id = m.category_id
		    			   AND m.is_monogram = 'Y'";
		    	$chk_monogram = $this->db->get_row($sql);
		    	if ($chk_monogram->count > 0) {
		    		$rs[$i]->accessory[] = $obj;
		    		$rs[$i]->accessory_price += $monogram_price;
		    	}
	    		*/
				
				if($rs[$i]->height && $rs[$i]->width)
					$area=$rs[$i]->height * $rs[$i]->width;
				else
					$area=1;
				if ( $global['calculate_shippping_weight'] == 'Y' ) {
					$rs[$i]->shipping_weight=$area*$rs[$i]->quantity*0.0007;
				}
				
				
	    		$total += $rs[$i]->quantity * $area * ( $rs[$i]->price + $rs[$i]->accessory_price );
				//added for accessory total
				$accessoryTotal	 += $rs[$i]->quantity * $area * ( 0 + $rs[$i]->accessory_price );
				$productTotal	 += $rs[$i]->quantity * $area * ( $rs[$i]->price  + 0 );
	    	}
    	}
		
		
		
		
    	
		/*
			Fill Store Name
		*/
		$this->callToFillStoreName ( $rs );
		/*
		    Fill Store Name
		*/
		
		
		/* Fill Image Extension */
		$this->fillImagetoProduct ( $rs );
		
		/* Fill Image Extension */
		
		
			
		/* Fill Inventory Message */
		if ( $global['product_inventory'] == 'Y' ) {
			$this->updateInventory ( $rs );
		}
		//print_r($rs);exit;

		/* Fill Inventory Message */
		
		
    	return array('records'=>$rs, 'total'=>$total,'AccessoryTotal'=>$accessoryTotal,'productTotal'=>$productTotal);
    }
    
	/**
  	 * This function is used getting the cupon reduction price for accessory
  	 * Author   : Shinu
  	 * Created  : 10/Dec/2007
  	 * Modified : 10/Dec/2007 By Shinu
  	 */
	
	function getAccessoryReductionPrice($val='',$ses_id="",$coupon_amount,$coupon_type)
	{
		global $global;
		global $store_id;		
    		if(!$ses_id)
		{
    	$session_id = session_id();
		}else
		{
			$session_id=$ses_id;
		}
				
				$sql = "SELECT p.id as product_id,c.*, p.display_name as name,p.cart_name as cart_name, SUM(a.price) as accessory_price,p.brand_id as brand_id,b.brand_name as brand_name
    			  FROM products p LEFT JOIN brands b on (p.brand_id=b.brand_id), cart c LEFT JOIN cart_accessory a ON(a.cart_id = c.id)
    			 WHERE c.product_id = p.id
    			   AND c.sess_id = '$session_id' ";
				
    		  	$sql=$sql." GROUP BY c.id";
				
				
				
    	
    	$rs 	= 	$this->db->get_results($sql);

   		$objProduct 	= 	new Product();
   		$objCategory 	= 	new Category();
   		$monogram_price	=	$objProduct->GetMonogramPrice();
    	$total 	= 	0;
    	if($rs) {
    		$obj->category_name = "Monogram";
    		$obj->name = "Customization";
    		$obj->price = $monogram_price;
			
    		for ($i=0; $i<count($rs); $i++) {
			if($val=='N'){
	    		$sql = "SELECT a.*, p.display_name as name,p.cart_name as cart_name, m.category_name
	    				  FROM cart_accessory a, product_accessories p, master_category m
	    				 WHERE a.accessory_id = p.id 
	    				   AND m.category_id = a.category_id
	    				   AND cart_id = '{$rs[$i]->id}'";					  
						  
			 }else{
			 
		
			 
			 	$sql = "SELECT a.*, p.display_name as name,p.cart_name as cart_name, m.category_name,paa.cart_name as available_cart_name 
	    				  FROM cart_accessory a, product_accessories p, master_category m, product_availabe_accessory paa, product_accessory_group pag
	    				 WHERE a.accessory_id = p.id 
	    				   AND m.category_id = a.category_id
	    				   AND cart_id = '{$rs[$i]->id}'
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
				$chk=$this->checkTableExist("cart_job")	;
				
				if($chk==1){
				
					 $st="SELECT cart_id, jobname,height,width,materialcheck FROM cart_job WHERE sess_id = '$session_id'  AND cart_id = '{$rs[$i]->id}'";
					 $ss=$this->db->get_results($st);
					 $rs[$i]->jobname=$ss[0]->jobname;
					 $rs[$i]->height=$ss[0]->height;
					 $rs[$i]->width=$ss[0]->width;
					 $rs[$i]->materialcheck=$ss[0]->materialcheck;
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
	

	
	
	
		
    function getCartBox ($ses_id="") {
	
    global $global;
    global $store_id;	
	
    if(!$ses_id)
	{
    	$session_id = session_id();
		}else
		{
			$session_id=$ses_id;
		}
		if($global['payment_receiver'] != 'store')//this condition added on  31-12-2008
				{   	
			    	$sql = "SELECT COUNT(*) as count, SUM(quantity * total_price) as total_price
			    			FROM cart
			    			WHERE sess_id = '$session_id'";
				}
				else 
				{
					$sql = "SELECT COUNT(*) as count, SUM(quantity * total_price) as total_price
    			            FROM cart
    			            WHERE sess_id = '$session_id' AND store_id = '$store_id'";
				}
				//echo $sql;
    	$rs = $this->db->get_row($sql);
    	return $rs;
    }
	
	function getAccessoryPrice ($ses_id="") {
	if(!$ses_id)
	{
    	$session_id = session_id();
		}else
		{
			$session_id=$ses_id;
		}
		    	
    	$sql = "SELECT *  FROM cart
    			 WHERE sess_id = '$session_id'";
    	$rs = $this->db->get_results($sql, ARRAY_A);
		$acc_total	=	0;
		if(count($rs)>0)
		{ 
			for($i=0;$i<count($rs);$i++)
			{
				$total		=	($rs[$i]['total_price']*$rs[$i]['quantity']) - ($rs[$i]['price']*$rs[$i]['quantity']);
				$acc_total	=	$acc_total+$total;
			}
		}
		return $acc_total;
    }
	function getProductPrice ($ses_id="") {
	if(!$ses_id)
	{
    	$session_id = session_id();
		}else
		{
			$session_id=$ses_id;
		}
		    	
    	$sql = "SELECT *  FROM cart
    			 WHERE sess_id = '$session_id'";
    	$rs = $this->db->get_results($sql, ARRAY_A);
		$prd_total	=	0;
		if(count($rs)>0)
		{ 
			for($i=0;$i<count($rs);$i++)
			{
					$pPrice		=	$rs[$i]['price'] * $rs[$i]['quantity'];
					$prd_total	=	$prd_total+$pPrice;
			}
		}
		return $prd_total;
    }
    
    function getLastCatID () {
    	$session_id = session_id();
    	
    	$sql = "SELECT product_id
    			  FROM cart
    			 WHERE sess_id = '$session_id' 
    		  ORDER BY date_added 
    			  DESC
    			 LIMIT 1";
    	$rs = $this->db->get_row($sql);
    	$product_id = $rs->product_id;
    	
    	if($product_id) {
	    	$sql = "SELECT category_id
	    	          FROM category_product
	    	         WHERE product_id = '$product_id'";
	    	$rs = $this->db->get_row($sql);
	    	$category_id = $rs->category_id;
    	}
    	return $category_id;
    }

    function placeOrder ($checkout,$store_id) { 
    	global $global;
		
		if(!$_SESSION['sess_id_old'])
		{
    		$session_id = session_id();
		}else
		{
			$session_id=$_SESSION['sess_id_old'];
		}
		$ip_addr	=	$_SERVER['REMOTE_ADDR'];
		$checkout['client_ip']	=	$ip_addr;
		
		
    	$order_id = $this->db->insert("orders", $checkout);
		$_SESSION['order_id']	=	$order_id;   
    	$this->db->query("INSERT INTO order_products(id, order_id, store_id, product_id, 
    												 quantity, price, total_price, notes, contact_me) 
							   SELECT
									  id, '$order_id', store_id, product_id, quantity,
									  price, total_price, notes, contact_me
							     FROM
							    	  cart 
							    WHERE
							   		  sess_id = '$session_id'");
			
		$chk=$this->checkTableExist("cart_job")	;
		$chko=$this->checkTableExist("order_products_job")	;
		
		if($chk==1 && $chko==1){
		$this->db->query("INSERT INTO order_products_job(id, order_id, jobname,height,width,materialcheck) 
							   SELECT
									  cart_id, '$order_id', jobname,height,width,materialcheck
							     FROM
							    	  cart_job
							    WHERE
							   		  sess_id = '$session_id'");
		}
    	$this->db->query("INSERT INTO order_accessory 
    						   SELECT op.id, 
    						   		  ca.category_id, 
    						   		  ca.accessory_id, 
    						   		  ca.price, 
    						   		  ca.customization_text, 
    						   		  ca.addl_customization_text,
									  ca.wrap_text
    						     FROM order_products op, cart_accessory ca
    						    WHERE ca.cart_id = op.id
    						   	");
								
		//=======================
		
		$Row 		= 	$this->db->get_row("SELECT COUNT(*) AS OrderCount FROM orders WHERE MONTH(date_ordered) = MONTH(NOW())", ARRAY_A);
		 $OrderCount	=	$Row['OrderCount'];
				
	   //$Today			=	str_pad(date('d'), 2, '0', STR_PAD_LEFT);
		$Today			=	str_pad(date('y'), 2, '0', STR_PAD_LEFT);
		$ThisMonth		=	str_pad(date('m'), 2, '0', STR_PAD_LEFT);
		 $OrderNumb		=	str_pad($OrderCount, 4, '0', STR_PAD_LEFT);
			
		$OrderNumber	=	$Today.''.$ThisMonth.''.$OrderNumb;
		
		$Qry01	=	"UPDATE orders SET order_number = '$OrderNumber' WHERE id ='$order_id'";
		$this->db->query($Qry01);
								
		//=================================
    	$rs = $this->db->get_results("SELECT * FROM cart WHERE sess_id = '$session_id'");

    	$crossSellArray = array();
    	$extra = new Extras();
    	if ($rs) {
    		$objProduct = new Product();
    		foreach ($rs as $row) {
    			if($objProduct->IsGiftCertificate($row->product_id)) {
    				$arr = array("quantity"=>$row->quantity, "product_id"=>$row->product_id, "order_id"=>$order_id, "order_number"=>$OrderNumber, "certi_amount"=>$row->total_price, "user_id"=>$row->user_id, "active"=>'Y');
    				for ($i=0; $i<$row->quantity; $i++) {
    					$extra->certificateAddEdit($arr);
    				}
    			}
    			$crossSellArray[] = $row->product_id;
    		}
    		$objProduct->insertTOCrossSellProduct($crossSellArray);
    	}
    	
    	if ($_SESSION['gift_certificate']['amount'] > 0) {
    		$_REQUEST['trans_no']		=	$_SESSION['gift_certificate']['key'];
    		$_REQUEST['trans_type']		=	'G';
    		$_REQUEST['trans_amount']	=	$_SESSION['gift_certificate']['amount'];
    		$_REQUEST['order_id']		=	$order_id;
    		$extra->updateHistory($_REQUEST);
    	}
		//echo "<pre>";
		//print_r($_SESSION);
		//$this->deleteCart();
		//exit;
    	if ($_SESSION['coupon']['amount'] > 0 || $_SESSION['coupon']['coupon_type']=='F') {
    		$_REQUEST['trans_no']		=	$_SESSION['coupon']['key'];
    		$_REQUEST['trans_type']		=	'C';
    		$_REQUEST['trans_amount']	=	$_SESSION['coupon']['amount'];
    		$_REQUEST['order_id']		=	$order_id;
    		//echo "inside: ";
			$extra->updateHistory($_REQUEST);
    	}
		//=======================
    	 $sql="select heading  from store where id='$store_id'";
		$rs = $this->db->get_row($sql, ARRAY_A);
		
		  $sname 	= 	$rs['heading'];
		
		 //========================
    	$this->sendMail($order_id,$sname);
    	
    	unset($_SESSION['BILLING_ADDRESS']);
    	unset($_SESSION['SHIPPING_ADDRESS']);
    	unset($_SESSION['gift_certificate']);
    	unset($_SESSION['coupon']);
		
		
		
		
		
		if ( $global['product_inventory']=='Y' ) {
			$this->reduceQty();
		}

		
    	$this->deleteCart();
		//exit;
		
		//$Row 		= 	$this->db->get_row("SELECT COUNT(*) AS OrderCount FROM orders WHERE DATEDIFF(date_ordered,NOW()) = 0", ARRAY_A);
		/*$Row 		= 	$this->db->get_row("SELECT COUNT(*) AS OrderCount FROM orders WHERE MONTH(date_ordered) = MONTH(NOW())", ARRAY_A);
		 $OrderCount	=	$Row['OrderCount'];
		
				
	   //$Today			=	str_pad(date('d'), 2, '0', STR_PAD_LEFT);
		$Today			=	str_pad(date('y'), 2, '0', STR_PAD_LEFT);
		$ThisMonth		=	str_pad(date('m'), 2, '0', STR_PAD_LEFT);
		 $OrderNumb		=	str_pad($OrderCount, 4, '0', STR_PAD_LEFT);
		
		$OrderNumber	=	$Today.''.$ThisMonth.''.$OrderNumb;
		
		$Qry01	=	"UPDATE orders SET order_number = '$OrderNumber' WHERE id ='$order_id'";
		$this->db->query($Qry01);*/
		
    }
    
    function sendMail($order_id,$sname) {
	
		global $global;
		global $store_id;
    	$extras = new Extras();
    	$email_tpl = $this->tpl;
    	$order = new Order();
    	$store = new Store();	
		
		### Start showing Pay Invoice Nov-28-2007 by shinu ###
		$invoice_amt	=	0;
		if($this->config['pay_invoice']=="Y")
		{
			$invoice_amt	=	$this->getInvoiceAmount(); 
			$email_tpl->assign("INVOICE_AMOUNT", $invoice_amt); 
			if($invoice_amt !== "" && $invoice_amt >0)
			{
				$email_tpl->assign("INVOICE", $this->getInvoice());
				$email_tpl->assign("PAY_INVOICE", "Y");
			}
		}
		### End showing Pay Invoice Nov-28-2007 by shinu ###
    	/*********************************************************/
		
		//if no avilable accessory
		//its optional parameter
		$val	=	 $global['avilable_access'];
		
		/*********************************************************/
    	$ordArray = $order->orderProducts($order_id,$val);
		
    	$ordDetails = $order->orderDetails($order_id);
		
    	$gift_cert_rs = $extras->historyByOrderid($order_id, 'G');
    	$coupon_rs = $extras->historyByOrderid($order_id, 'C');
    	
    	$shipping_price = ($coupon_rs->coupon_amounttype=='F') ? 0 : $ordDetails->shipping_price;
		
    	
    	/* Added By Aneesh for Shipping Discount */
		$shipping_discountamt	= 0;
		if ( $shipping_price>0 && $global["shippping_free"] != 'none' && $global["shippping_free_minvalue"] > 0 &&   $global["shippping_free_percentage"] > 0 ) {
			if(	$ordDetails->cart_price	>= $global["shippping_free_minvalue"] ) {
				$shipping_discountamt	=  round (  $shipping_price * ( $global["shippping_free_percentage"]/100 ) ,2 );
				$shipping_price_new   	=  number_format($shipping_price - $shipping_discountamt,2);
				$shipping_price         =  $shipping_price_new;
			}
		}
    	
		
    	
		$tax_amount = round(($ordArray['total']+$shipping_price) * ($ordDetails->tax) / 100, 2);
		$sub_total = round(($ordArray['total']+$shipping_price) * (100 + $ordDetails->tax) / 100, 2);
		
		if($coupon_rs->coupon_amounttype=='F') {
			$coupon_amount = 0;
		} elseif ($coupon_rs->coupon_amounttype=='A') {
			//$coupon_amount = - $coupon_rs->trans_useamount;
			#--
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
			
			#--
		} elseif ($coupon_rs->coupon_amounttype=='P') {
			// substract from product or total
			if($coupon_rs->substract_from=='T')
			{
				$coupon_amount = - $sub_total * $coupon_rs->trans_useamount / 100;
			}
			elseif($coupon_rs->substract_from=="O")
			{
				$coupon_amount = - $ordArray['AccessoryTotal'] * $coupon_rs->trans_useamount / 100;
			}
			elseif($coupon_rs->substract_from=="M")
			{
				$coupon_amount = - $ordArray['ProductTotal'] * $coupon_rs->trans_useamount / 100;
			}
			else
			{
				$coupon_amount = - $ordArray['total'] * $coupon_rs->trans_useamount / 100;
			}
		}
		
		$certificate_amount = - $gift_cert_rs->trans_useamount;
		
		$email_tpl->assign("CART_TOTAL", $ordArray['total']);
		$email_tpl->assign("SHIPPING_PRICE", $shipping_price > 0 ? number_format($shipping_price, 2, '.', '') : 'Free');
		$email_tpl->assign("TAX_AMOUNT", $tax_amount);
		$email_tpl->assign("SUB_TOTAL", $sub_total);
		$email_tpl->assign("COUPON_AMOUNT", $coupon_amount);
		$email_tpl->assign("CERTIFICATE_AMOUNT", $certificate_amount);
		$email_tpl->assign("TOTAL_AMOUNT", $sub_total + $coupon_amount + $certificate_amount+$invoice_amt);
    	
    	$email_tpl->assign("ORDER_PRODUCTS", $ordArray);
    	$email_tpl->assign("ORDER_DETAILS", $ordDetails);
    	$email_tpl->assign("ORDER_GIFT_CERT", $gift_cert_rs);
    	$email_tpl->assign("ORDER_COUPON", $coupon_rs);
    	$email_tpl->assign("STORE_NAME", $sname);
    	$dynamic_vars['ORDER_DETAILS'] = $email_tpl->fetch(SITE_PATH."/modules/cart/tpl/email.tpl");
    	$dynamic_vars['CUSTOMER_NAME'] = $ordDetails->billing_first_name.' '.$ordDetails->billing_last_name;
    	$dynamic_vars['ORDER_NUMBER']  = $ordDetails->order_number;
		if($sname)
			$dynamic_vars['STORE_NAME']  = $sname;
		else
			$dynamic_vars['STORE_NAME']  = $this->config['site_name'];

    	$email_header['to'] 	= 	$ordDetails->billing_email;
    	if ($store_id != "")
	    	{
	    		$res = $store->GetStoreOwnerEmailByStoreId($store_id);
	  			$email_header['from'] 	= 	$res[0]['email'];
	    	}
    	else
    		{
    			$email_header['from'] 	= 	$this->config['admin_email'];	
    		}
    	//$email_header['from'] 	= 	$this->config['admin_email'];
         // $email_header['from'] 	= 	$this->config['email'];
		/**
		 *	The folowing code modified by vimson on Aug 27
		 *
		 */
		//$email_header['Bcc'] 	= 	'Staff@FMRIncorporated.org';
	/*	$sql="select email from member_master where id='9'";
		$row 	= 	$this->db->get_row($sql,ARRAY_A);
		
		$email_header['bcc'] 	= 	$row['email'];
		
    	//$email_header['Bcc'] 	= 	$this->config['admin_email'];*/
    	$email = new Email();
    	$email->send('order_confirmation', $email_header, $dynamic_vars);
    }
/*
Function Name: 	CalculateTax
Created by:		Nirmal
Date: 			28-3-2007

@country_id: 	country_id of the buyer
@state_name: 	state_nameof the buyer
@validate_from_state[true/false]
				true: 	Check the admin country_id, state_name match with buyer country_id, state_name. Then only the TAX will apply
				false: 	Checks only the buyer country_id, state_name. 
@storename: 	To find the store owner country_id, state_name

*/
function CalculateTax($country_id,$state_name,$validate_from_state=false,$storename='')
	{
	$qry	=	"SELECT tax FROM state_code WHERE country_id='$country_id' and name='$state_name'";
	$rs 	= 	$this->db->get_row($qry);
	$tax	=	$rs->tax;
	if($validate_from_state==true)
		{
		if($storename)
			{
			$sql	=	"	SELECT * FROM member_address AS ma 
							LEFT JOIN (member_master as mm) ON (ma.user_id=mm.id) 
							LEFT JOIN (store as s) ON (s.user_id=mm.id) 
							WHERE 
							ma.addr_type='master' AND
							mm.mem_type<>'0' AND
							mm.active='Y' AND 
							s.name='$storename'" ;
			}
		else
			{
			$sql	=	"	SELECT * FROM member_address AS ma 
							LEFT JOIN (member_master as mm) ON (ma.user_id=mm.id) 
							WHERE 
							ma.addr_type='master' AND
							mm.mem_type='0' AND
							mm.active='Y'";
			
			
			}
			//echo $sql;
			$row 	= 	$this->db->get_row($sql);
			$admin_county	=	$row->country;
			$admin_state	=	$row->state;
		if($admin_county==$country_id && $admin_state==$state_name)
			{
			$tax	=	$rs->tax;
			}
		else
			{
			$tax	=	0;
			}
		}
	return $tax;
	}
	
	
function GetTaxTitle($country_id,$state_name,$storename='')
	{
	$qry		=	"SELECT title FROM state_code WHERE country_id='$country_id' and name='$state_name'";
	$rs 		= 	$this->db->get_row($qry);
		if (trim($rs->title)) {
			$taxtitle	=	$rs->title;
		}	else {
			$taxtitle	=	"";
		}	
		return 	$taxtitle;
	}
	/**
	 * Calculate weight of shopping cart items
	 *
	 * @return float Weight
	 */
	function calculateWeight() {
		$session_id = session_id();

		$weight = $this->db->get_var("SELECT SUM(p.weight * c.quantity) 
										FROM cart c, products p 
									   WHERE c.product_id = p.id 
									     AND c.sess_id = '$session_id'");

		$rs = $this->db->get_results("SELECT pa.is_weight_percentage, pa.adjust_weight, p.weight 
										FROM cart c, cart_accessory ca, product_accessories pa, products p
									   WHERE c.id = ca.cart_id
									     AND ca.accessory_id = pa.id
									     AND c.product_id = p.id
									     AND c.sess_id = '$session_id'");
		if ($rs) {
			foreach ($rs as $row) {
				$weight += $row->is_weight_percentage == 'Y' ? ($row->weight * $row->adjust_weight / 100) * $row->quantity : $row->adjust_weight * $row->quantity;
			}
		}
		return $weight;
	}
	// this function is used to select the box for a particular order
	/*function selectBox()
	{
		$session_id = session_id();
		$rs 	=	$this->db->get_row("SELECT product_id,quantity from cart where sess_id='$session_id'", ARRAY_A);
		$paddle		= $rs['product_id'];
		$quantity	= $rs['quantity'];
		
		
	}*/
	
	function AddcheckDetails($req,$mandatory="")
	{
		extract($req);
	
	
		if(!trim($amount) && $mandatory[0]==1)
		{
			$message 				=	"Amount is required";
		}
		elseif(!trim($account_number) && $mandatory[2]==1)
		{
			$message 				=	"Account number is required";
		}
		elseif(!trim($check_number)  && $mandatory[3]==1)
		{
			$message 				=	"Check number is required";
		}
		
		else
		{
			$array 				= 	array("amount"=>$amount,"transit_routing"=>$transit_routing,"account_number"=>$account_number,"check_number"=>$check_number,"account_type"=>$account_type,"bank_name"=>$bank_name,"bank_state"=>$bank_state,"social_security_number"=> $social_security_number,"drivers_license"=>$drivers_license,"drivers_license_state"=>$drivers_license_state);
			
			//print_r($_SESSION);
			//break;
				$array['order_id'] 	= 	$_SESSION['order_id']; //echo " order ".$_SESSION['order_id']; break;
				$this->db->insert("order_check_details", $array);
				$id = $this->db->insert_id;
						
			return true;
		}
		
		return $message;
	
	}
	function addToCartTracking(&$req)
	{
	extract($req);
	$this->db->insert("cart_tracking",array("user_id"=>$user_id,"sess_id"=>session_id(),"store_id"=>$store_id,"ip_address"=>$_SERVER['REMOTE_ADDR'],"date"=>date("Y-m-d H:i:s")));
	}
	function updateCartTracking1(&$req)
	{
	extract($req);
	$session_id = session_id();
	$this->db->update("cart_tracking",array("check_out"=>"Y"),"sess_id = '$session_id'");
	}
	function updateCartTracking2(&$req)
	{
	extract($req);
	$session_id = session_id();
	$this->db->update("cart_tracking",array("shipping"=>"Y"),"sess_id = '$session_id'");
	}
	
	function checkTableExist($tablename){
		$sql ="show table status like '$tablename'";
		$res=$this->db->get_results($sql);
		if($res){
			return 1;
		}
		
	}
	function listMessage($txn_id="") {
	
	 $dynamic_vars = array();
	 unset($dynamic_vars);
	$store					= 	new Store();	
	$storeDetails			=	$store->storeGetByName($_REQUEST['storename']);	
	$dynamic_vars['STORE_NAME']  = $storeDetails['heading'];
	 $order = new Order();
	 $order_id=$_SESSION['order_id'];
	 
		 if($txn_id!=""){
		 
		 $sql ="SELECT  id  FROM orders WHERE payment_transactionid ='$txn_id'";
		 $rs =$this->db->get_row($sql,ARRAY_A);
		 $order_id=$rs['id'];
		 }
	
	
		$ordDetails = $order->orderDetails($order_id);
		
    	//$dynamic_vars['CUSTOMER_NAME']	= $ordDetails->billing_first_name.' '.$ordDetails->billing_last_name;
        $dynamic_vars['ORDER_NUMBER']	= $ordDetails->order_number;
		$dynamic_vars['ORDER_DATE']		= $ordDetails->date_ordered_f;
    	$dynamic_vars['ORDER_COMMENTS'] = '<ul>' . $ordDetails->order_history . '</ul>';
    	$dynamic_vars['ORDER_STATUS']	= $ordDetails->status;
		
	
	 $sql ="SELECT body FROM email_config WHERE bit_type=2";
	 $rs =$this->db->get_row($sql,ARRAY_A);
	 
	 	
	 if (is_array($dynamic_vars)) {
	 		$body	 = $rs['body'];
			
			
			foreach ($dynamic_vars as $key=>$val) {
				
				 $dyn_var="%_".$key."_%";
				
					if($dynamic_vars[$key]!=""){
					
														
					    $pos_end = strpos($body, "%+");
						$pos_start = strpos($body, "+%");
						$posend=$pos_end-$pos_start;
						if($posend>0)
						{
							$posend=$posend+2;
						}
						
						$Tmp	=	substr($body, $pos_start, $posend);
						
						$testString = str_replace($Tmp,"",$body);
						$testString= str_replace($dyn_var, $val, $testString);
						
						
						
				}
				else{
					
					$testString=$body;
					$testString = str_replace('+%',"", $testString);	
					$testString = str_replace('%+',"", $testString);
					if(!$dynamic_vars['STORE_NAME'])
					{
						$Tmp	=	"%_STORE_NAME_%";
						
						$testString = str_replace($Tmp,"",$testString);
						
					}
					
					
				}
				$body	=	$testString;
		    }		
			 
			$rs['body']=$body;
			
			
			
	}	
	
	 return $rs;
	}	
	
	
	
	
	function storeGet ($id) {
		$rs = $this->db->get_row("SELECT * FROM store WHERE id='{$id}'", ARRAY_A);
		return $rs;
	}	
	
	
	function ProductGet ($id) {
		$rs = $this->db->get_row("SELECT * FROM products WHERE id='{$id}'", ARRAY_A);
		return $rs;
	}	

	
	
	
	function callToFillStoreName ( $TLinks )	{
		if ( $TLinks ){
			foreach($TLinks as $keys=>$vals)
			{
					if ($vals->store_id) {
							$STORE_DETAILS		=	$this->storeGet ($vals->store_id);
							
							//if ( trim( $STORE_DETAILS['redirect_url'] ) )
							//$TLinks[$keys]->store_name = $STORE_DETAILS['redirect_url'];
							//else
							$TLinks[$keys]->store_name = $STORE_DETAILS['heading'];
							
							
							
					}
			}
		}	
	}	
	
	
	function fillImagetoProduct ( &$arr ) {
		if ( $arr ) {
			foreach ( $arr as $k=>$vals )	{
				$PRODUCT_DETAILS		= $this->ProductGet ( $vals->product_id );
				$arr[$k]->image_extension = $PRODUCT_DETAILS['image_extension'];
				
			}
		}
	}
	
	
	function decodeSession($id="") {
	
		$qry		=	"select sess_value from order_session_value  where id='$id'";
		$row 		= 	$this->db->get_row($qry,ARRAY_A);
		$sess_str	=	$row['sess_value'];
		$sess_str	=	base64_decode($sess_str);
		session_decode($sess_str);
	}
	
	function encodeSession()
	{
		$Sess_str	=	session_encode();
		$Sess_str   = base64_encode($Sess_str);
		$arrayValue		=	array("sess_value"	=>	$Sess_str);
		$this->db->insert("order_session_value", $arrayValue);
		return $this->db->insert_id;
	}
	 function editFromCart(&$req,$cart_id) {
		$objPrice		=	new Price();
    	$objProduct 	= 	new Product();
    	$objAccessory	=	new Accessory();
    	extract($req);  
		//print_r($req);exit; 	
			if(!$price) {
				$total_price = $objProduct->calulate_price($req);
				//echo $total_price;
				//exit;
					$product_price	=	$objPrice->GetPriceOfProduct($product_id, 0,$qty);
			} else {
					$product_price	=	$total_price = $price;
			}
			$arr=array("user_id"=>$user_id, "sess_id"=>session_id(), "product_id"=>$product_id, "quantity"=>$qty, "price"=>$product_price, "total_price"=>$total_price, "date_added"=>date("Y-m-d H:i:s"), "store_id"=>$store_id, "notes"=>$notes, "contact_me"=>$contact_me);
			if($height)	
				$arr1["height"]=$height;
			if($width)	
				$arr1["width"]=$width;
			if($jobname)
				$arr1["jobname"]=$jobname;	
			$this->db->update("cart",$arr,"cart_id='$cart_id'");	
			$arr1["cart_id"]=$cart_id;
			$arr1["sess_id"]=session_id();
			$chk=$this->checkTableExist("cart_job")	;
			if($chk==1)
				$this->db->update("cart_job",$arr1,"cart_id='$cart_id'");	
    		//print_r($req);
    		if ($req["prd_image"])
			{	$source_path = $req["image_path"];
				if ($req["image_ext"])
				{
					$im_ext= $req["image_ext"];
				}
				else 
				{
					$im_ext = "jpg";
				}
				$dest_path   = SITE_PATH."/modules/cart/images/";	
				$thumb       = $dest_path."thumb/"; 
				$save_filename = "$cart_id.$im_ext";
				copy($source_path."$prd_image.$im_ext",$dest_path.$save_filename);
				if ($this->config['product_thumb_image'])
				{
					list($thumb_width,$thumb_height)	=	split(',',$this->config['product_thumb_image']);
				}
				else 
				{
					$thumb_width = 100;
					$thumb_height = 100;
				}
				
				
				thumbnail($dest_path,$thumb,$save_filename,$thumb_width,$thumb_height,$mode,$save_filename);
				if ($req["image_del"]=="Y")
				{
					@unlink($source_path."$prd_image.jpg");
				}
			}
    									
    		if($req['access']) {	
				$newVal=array();
	    		foreach ($req['access'] as $key=>$val) {
					if(is_array($val)){
						$newVal		=	$val;
					}else{
						$newVal[0]	=	$val;						
					}
					foreach ($newVal as $val2) {				
						if ($val2) {
							$accessory  =   $objAccessory->GetAccessory($val2);
								if($height and $width)
										$area=$height*$width;
									else
										$area=1;
								if($accessory['is_price_percentage']=="Y") { 
									$price	=	($product_price*$accessory['adjust_price'])/100;
								}elseif($accessory['independent_qty']=="Y"){
									$acprice=$accessory['adjust_price']/$qty;
								
									$acprice  =  $acprice/$area;
									$price		=	$acprice;
								//	echo $price;
								} else {
									$price	=	$accessory['adjust_price']/$area;
								}
								if ($custom_cat[$key] == "Other") {
									$custom_text1 = $custom_text[$key];						
							
								} elseif ($custom_cat[$key] == "None") {
									$custom_text1 = "";
								} else {
									$custom_text1 = $objProduct->getCustomizationText($custom_cat[$key]);
								}
								$this->db->update("cart_accessory",array("cart_id"=>$cart_id, "category_id"=>$key, "accessory_id"=>$val2, "price"=>$price, 
															  "customization_text"=>$custom_text1, 
															  "addl_customization_text"=>$addl_custom[$key],
															  "wrap_text"=>$wrap_text_[$key]),"cart_id='$cart_id'");	
						}
															//exit;

					}
				}
    		}
			return $cart_id;
    
	}
    
	/**
    * adding pay invoice details
  	* Author   : Shinu
  	* Created  : 26/Nov/2006
  	* Modified : 26/Nov/2006 By Shinu
  	*/
	function invoiceAdd($req)
	{
	global $global;
	global $store_id;
	$invoice_no			=	$req['invoice_no'];
	$invoice_amount		=	$req['invoice_amount'];
	$comments		=	$req['comments'];
	$user_id		=	$_SESSION["memberid"];
	$sess_id		=	session_id();
	if($invoice_no!= "" && $invoice_amount!="")
	{
		$array 			= 	array("invoice_no"=>$invoice_no,"amount"=>$invoice_amount,
							"create_date"=>date("Y-m-d"), "store_id" => $store_id,	
							"comments"=>$comments,"user_id"=>$user_id,"sess_id"=>$sess_id );
		$this->db->insert("pay_invoice", $array);
		return true;
	}
	else
	{ return false; }
	
	}
	
	/**
    * Total amount in pay invoice details
  	* Author   : Shinu
  	* Created  : 26/Nov/2006
  	* Modified : 26/Nov/2006 By Shinu
  	*/
	  function getInvoiceAmount($ses_id="") {	
		global $global;
		global $store_id;		
    		if(!$ses_id)
		{
    	$session_id = session_id();
		}else
		{
			$session_id=$ses_id;
		}
    	
		$sql = "SELECT  SUM(amount) as amount from pay_invoice where  sess_id = '$session_id' ";
		$rs = $this->db->get_row($sql, ARRAY_A);
		return $rs["amount"];
   }	
   
   /**
    * Total amount in pay invoice details
  	* Author   : Shinu
  	* Created  : 26/Nov/2006
  	* Modified : 26/Nov/2006 By Shinu
  	*/
	  function getInvoiceCount($ses_id="") {	
		global $global;
		global $store_id;		
    		if(!$ses_id)
		{
    	$session_id = session_id();
		}else
		{
			$session_id=$ses_id;
		}
    	
		$sql = "SELECT  count(*) as count from pay_invoice where  sess_id = '$session_id' ";
		$rs = $this->db->get_row($sql, ARRAY_A);
		return $rs["count"];
   }	
   
   /**
    *  pay invoice details
  	* Author   : Shinu
  	* Created  : 26/Nov/2006
  	* Modified : 26/Nov/2006 By Shinu
  	*/
	  function getInvoice($ses_id="") {	
		global $global;
		global $store_id;		
    		if(!$ses_id)
		{
    	$session_id = session_id();
		}else
		{
			$session_id=$ses_id;
		}
    	
		$sql = "SELECT  * from pay_invoice where  sess_id = '$session_id' ";
		$rs = $this->db->get_results($sql, ARRAY_A);
		return $rs;
   }	
   
    /**
    *  order pay invoice details
  	* Author   : Shinu
  	* Created  : 27/Nov/2006
  	* Modified : 27/Nov/2006 By Shinu
  	*/
	 function getOrderInvoice($id) {	
		
		$sql = "SELECT  * from orders_invoice where  order_id = '$id' ";
		$rs = $this->db->get_results($sql, ARRAY_A);
		return $rs;
   }	
   
    /**
    *  Deleting a pay invoice
  	* Author   : Shinu
  	* Created  : 26/Nov/2006
  	* Modified : 26/Nov/2006 By Shinu
  	*/
    function deleteInvoice ($id="") {
    	$sql = "DELETE  FROM pay_invoice WHERE pay_id = '$id' ";
    	$this->db->query($sql);
		return true;
		
    }
	
	/**
    *  Deleting a pay invoice in the cart
  	* Author   : Shinu
  	* Created  : 26/Nov/2006
  	* Modified : 26/Nov/2006 By Shinu
  	*/
	function deleteCartInvoice () {
    	if(!$_SESSION['sess_id_old'])
		{
    		$session_id = session_id();
		}else
		{
			$session_id=$_SESSION['sess_id_old'];
		}
    	
		$sql = "DELETE  FROM pay_invoice WHERE sess_id = '$session_id' ";
    	$this->db->query($sql);
		return true;
    	
    }
	
	/**
    *  Get order id
  	* Author   : Shinu
  	* Created  : 27/Nov/2006
  	* Modified : 27/Nov/2006 By Shinu
  	*/
	function getOrderId() {
    	$sql = "SELECT MAX(id) as id FROM orders ";
    	$rs = $this->db->get_row($sql, ARRAY_A);
		return $rs["id"];
    	
    }
	
	/**
    *  Saving pay invoice details for an order
  	* Author   : Shinu
  	* Created  : 27/Nov/2006
  	* Modified : 27/Nov/2006 By Shinu
  	*/
	function orderInvoice($order_id) 
	{
		if(!$_SESSION['sess_id_old'])
		{
    		$session_id = session_id();
		}else
		{
			$session_id=$_SESSION['sess_id_old'];
		}
    	$sql = "SELECT * from  pay_invoice where sess_id='$session_id' ";
    	$rs = $this->db->get_results($sql, ARRAY_A);
		if(count($rs)>0)
		{
			for($i=0;$i<count($rs);$i++)
			{
				$invoice_no	=	$rs[$i]["invoice_no"];
				$amount		=	$rs[$i]["amount"];
				$store_id	=	$rs[$i]["store_id"];
				$comments	=	$rs[$i]["comments"];
				
				$array 			= 	array("order_id"=>$order_id,"invoice_no"=>$invoice_no,
								"amount"=>$amount,"store_id"=>$store_id,"comments"=>$comments);
				$this->db->insert("orders_invoice", $array);
			}
		}
    }
	/**
    *  Get order Number of particular order
  	* Author   : Ratheesh
  	* Created  : 24/Jan/2008
  	* Modified :  24/Jan/2008
  	*/
	function orderNumber($order_id) {
    	$sql = "SELECT order_number FROM orders where id=$order_id";
    	$rs = $this->db->get_row($sql, ARRAY_A);
		return $rs["order_number"];
    }
	/**
    *  Get table_id from custom fields table 
  	* Author   : Ratheesh
  	* Created  : 24/Jan/2008
  	* Modified :  24/Jan/2008
  	*/
	function getTableID($table_name)
	{
		$sql = "Select table_id from custom_fields_table where table_name='$table_name'";
		$rs = $this->db->get_row($sql,ARRAY_A);
		return $rs;
	}
	/**
    * Total amount in pay invoice details
  	* Author   : Shinu
  	* Created  : 26/Nov/2006
  	* Modified : 26/Nov/2006 By Shinu
  	*/
	 function getOrderInvoiceAmount($id) {	
		$sql = "SELECT  SUM(amount) as amount from orders_invoice where  order_id = '$id' ";
		$rs = $this->db->get_row($sql, ARRAY_A);
		return $rs["amount"];
     }	

	function getCountryNameById($id)
	{
		$sql = "Select country_name from country_master where country_id='$id'";
		$rs= $this->db->get_row($sql,ARRAY_A);
		return $rs['country_name'];
	}
	
}

?>