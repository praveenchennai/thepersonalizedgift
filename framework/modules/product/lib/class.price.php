<?
class Price extends FrameWork
{
	
	function Price()
	{
		$this->FrameWork();
		//$objProduct=new Product();
	}
	function setArrData($szArrData)
	{
		$this->arrData 	= 	$szArrData;
	}
	function getArrData()
	{
		return $this->arrData;
	}
	function setErr($szError)
	{
		$this->err 		.= 	"$szError";
	}
	function getErr()
	{
		return $this->err;
	}
	/*
	*	This function will return all the brand names.
	*	This function is used for listing brand names in admin side.
	*/
	function listAllPrices($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy)
	{
		$qry		=	"select * from product_price_type";
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	function GetPrice($id=0) {
		if($id>0)
		{
			$rs = $this->db->get_row("SELECT * FROM product_price_type WHERE id='{$id}'", ARRAY_A);
			return $rs;
		}
	}
	function GetBulkofProducts($id=0,$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy) {
		if($id>0)
		{
			$rs = $this->db->get_results_pagewise("SELECT * FROM product_bulk_price WHERE product_id='{$id}' ", $pageNo, $limit, $params, $output, $orderBy);
			return $rs;
		}
	}
	function GetBulk($id=0) {
		if($id>0)
		{
			$rs = $this->db->get_row("SELECT * FROM product_bulk_price WHERE id='{$id}'", ARRAY_A);
			return $rs;
		}
	}
	function GetPriceName($id=0)
		{
		if($brand_id>0)
			{
				$rs = $this->db->get_row("SELECT name FROM product_price_type WHERE id='{$id}'", ARRAY_A);
				return $rs['name'];
			}
		}
	function priceAddEdit(&$req)
	{
		extract($req);
		if(!trim($name))
		{
			$message 				=	"{$req['sId']} is required";
		}
		else
		{
			$array 				= 	array("name"=>$name);
			if($id)
			{
				$array['id'] 	= 	$id;
				$this->db->update("product_price_type", $array, "id='$id'");
			}
			else
			{
				$this->db->insert("product_price_type", $array);
				$id = $this->db->insert_id;
			}
			return true;
		}
		return $message;
	}
	function bulkAddEdit(&$req)
	{
		extract($req);
		if(!trim($unit_price))
		{
			$message 				=	"Unit Price is required";
		}
		else if(!trim($min_qty))
		{
			$message 				=	"Minimum Quatity is required";
		}
		else if(!trim($max_qty))
		{
			$message 				=	"Maximum Quatity is required";
		}
		else
		{
			$array 					= 	array("min_qty"=>$min_qty,"max_qty"=>$max_qty,"unit_price"=>$unit_price,"product_id"=>$id,"active"=>"Y");
			if($bid)
				{
					$this->db->update("product_bulk_price", $array, "id='$bid'");
				}
			else
				{
					$bid	=	$this->db->insert("product_bulk_price", $array);
				}
			return true;
		}
		return $message;
	}
	function bulkMassUpdate(&$req)
	{
		extract($req);
		if(!trim($unit_price))
		{
			$message 				=	"Unit Price is required";
		}
		else if(!trim($min_qty))
		{
			$message 				=	"Minimum Quatity is required";
		}
		else if(!trim($max_qty))
		{
			$message 				=	"Maximum Quatity is required";
		}
		else
		{
			if(count($id)>0)
				{
				foreach ($id as $product_id)
					{
					$array 					= 	array("min_qty"=>$min_qty,"max_qty"=>$max_qty,"unit_price"=>$unit_price,"product_id"=>$product_id,"active"=>"Y");
					if($this->checkForUpdateBulk($product_id,$max_qty,$min_qty))
						{
						$this->db->update("product_bulk_price", $array, "product_id='$product_id' AND max_qty='$max_qty' AND min_qty='$min_qty'");
						}
					else
						{
						$this->db->insert("product_bulk_price", $array);
						}
					}
				//echo "true";
				return true;
				
				exit;
				}
			
			
		}
		return $message;
	}
	function PriceDelete($id, $sId = 'Price Type') {
		if($this->checkdefaultprice($id))
			{
			$arr=array("status"=>false,"message"=>"The $sId cannot delete since it is assigned to some product");
			}
		else
			{
			$this->db->query("DELETE FROM product_price_type WHERE id='$id'");
			$this->db->query("DELETE FROM product_price WHERE type_id='$id'");
			$arr=array("status"=>true,"message"=>"The $sId is deleted successfully");
			}
		
		return true;
	}
	function BulkDelete($id=0,$product_id=0) {
		if($id>0)
			{
			$this->db->query("DELETE FROM product_bulk_price WHERE id='$id'");
			}
		else if($product_id>0)
			{
			$this->db->query("DELETE FROM product_bulk_price WHERE product_id='$product_id'");
			}
		else
			{
			return false;
			}
		return true;
	}
	function checkdefaultprice($id)
	{
		$qry		=	"select count(*) as number from product_price pt where pt.type_id='$id'";
		$row 		= 	$this->db->get_row($qry,ARRAY_A);
		if($row['number']>0)
		return true;
		else
		return false;

	}
function checkForUpdateBulk($product_id,$max_qty,$min_qty)
	{
		$sel="SELECt count(*) as number FROM product_bulk_price WHERE product_id='$product_id' AND max_qty='$max_qty' AND min_qty='$min_qty'";
		$row 		= 	$this->db->get_row($sel,ARRAY_A);
		if($row['number']>0)
		return true;
		else
		return false;

	}
	function returnAllPriceType()
		{
		$qry		=	"select * from product_price_type";
		$rs 		= 	$this->db->get_results($qry, ARRAY_A);
		return $rs;
		}
	function GetAllPriceType($product_id=0)
		{
		if($product_id>0)
			{
			$qry		=	"select ppt.*,pp.price,pp.is_percentage as is_percentage from product_price_type as ppt left join (product_price as pp) on (pp.type_id=ppt.id";
			$qry		.=	" and pp.product_id=".$product_id;
			$qry		.=	") order by ppt.name";
			}
		else
			{
			$qry		=	"select * from product_price_type";
			}
		$arr1 		= 	$this->db->get_results($qry, ARRAY_A);
			
		return $arr1;
		}
	function GetPriceTypeInCombo()
		{
		$sql		= 	"SELECT id, name FROM product_price_type ";
		$rs['id'] 	= 	$this->db->get_col($sql, 0);
		$rs['name'] = 	$this->db->get_col($sql, 1);
		return $rs;
		}	
	function priceTypeofProduct($product_id)
		{
		$sql		= 	"SELECT * FROM product_price where product_id='$product_id'";
		$row 		= 	$this->db->get_row($sql,ARRAY_A);
		return $row;
		}
	//functions used in USER SIDE
	function GetPriceOfProduct($product_id=0,$price_type=0,$qty=0)
		{
		$objProduct=new Product();
		$base_price		=	0;
		$ar		=	$this->GetbulkPriceForProduct($product_id,$qty);
		if($ar['status']===true)
			{
			$output	=	$ar['amount'];
			}
		else
			{
			//echo "base_price: ".$base_price."<br>";
			//return base_price;
			//find the default price id if the price_type is_id is 0.
			if($price_type==0)
			{
			$qry1			=	"SELECT type_id FROM product_price WHERE product_id='$product_id'";
			$row1 			= 	$this->db->get_row($qry1,ARRAY_A);
			$price_type		=	$row1['type_id'];
			}
			if(empty($price_type))
				$price_type	=	0;
			//echo "price_type: ".$price_type."<br>";
			//exit;
			//calulate the product price
			if($product_id>0)
				{
				
				
				//get the base price of the product
				$qry2		=	"SELECT price FROM products WHERE id=".$product_id;
				$row2 		= 	$this->db->get_row($qry2,ARRAY_A);
				$base_price	=	$row2['price'];
				//echo "base_price: ".$base_price."<br>";
				//exit;
				if($base_price>0)
					{
					//echo "base_price: ".$base_price."<br>";
					if($price_type>0)
						{
						$output	=	$this->calculateProductPriceWithBasePrice($product_id,$price_type,$base_price);
						//echo "product_id: ".$product_id."<br>";
						//echo "output: ".$output."<br>";
						}//if($price_type>0)
					else
						{
						$output	=	$base_price;
						}
					}//if($base_price>0)
				else
					{
					$output	=	0;
					}
				}//if($product_id=0)
			else
				{
				$output		=	"Please select a product";
				}
			}
			
			$output=$objProduct->getMemberPercent($output);	
			//echo $output;
			//exit;		
		return $output;
		}	
	function calculateProductPriceWithBasePrice($product_id,$price_type,$base_price)
		{
		$qry		=	"SELECT * FROM product_price WHERE product_id=".$product_id." AND type_id=".$price_type;
		$row 		= 	$this->db->get_row($qry,ARRAY_A);
		$price		=	$row['price'];
		$is_perc	=	$row['is_percentage'];
		//echo "is_percentage: ".$is_percentage."<br>";
		if($price>0 && ($is_perc=='N' || $is_perc=='Y'))
			{
			if($is_perc=='Y')
				{
				return (round($base_price*$price)/100);
				}
			else
				{
				return $price;
				}
			}//if($price>0 && ($is_perc=='N' || $is_perc=='Y'))
		else
			{
			return $base_price;
			}
		
		}
function GetbulkPriceForProduct($product_id,$qty=0)
	{
	$qry	=	"SELECT * FROM product_bulk_price WHERE product_id='$product_id' AND min_qty <= '$qty' AND max_qty>='$qty' AND active='Y' ORDER BY unit_price";
	//echo $qry;
	$row 	= 	$this->db->get_row($qry,ARRAY_A);
	
	if(count($row) && $row['unit_price']>0)
		{
		$arr	=	array("status"=>true, "amount"=>$row['unit_price']);
		}
	else
		{
		//find the bulk price for category.
		//if no bulk price for category found return false;
		$arr	=	$this->GetBulkPriceForAProductCategory($product_id,$qty);
		}
		return $arr;
	}
	/* function calculate the price in the category*/
function GetBulkPriceForAProductCategory($product_id,$qty)
	{
	if($product_id>0)
		{
		$qry	=	"select mc.category_id from 	
									master_category mc,
									category_product cp
							where 	cp.product_id='".$product_id."' AND
									cp.category_id=mc.category_id AND
									mc.active='Y'";
		$res	=	$this->db->get_results($qry, ARRAY_A);// All the categories in which the product belongs to
		for($i=0;$i<count($res);$i++)
			{
			$status=$this->GetBulkPriceOfCategory($res[$i]['category_id'],$qty);
			if(empty($price))
				{
				if($status['status']==true)
					$price	=	$status['amount'];
				}
			else
				{
				if($status['status']==true && $price>$status['amount'])
					$price	=	$status['amount'];
				}
			}//for($i=0;$i<count($res);$i++)
		if($price)
			$arr	=	array("status"=>true, "amount"=>$price);
		else
			$arr	=	array("status"=>false, "amount"=>0);
		return $arr;
		}
	}
function GetBulkPriceOfCategory($category_id,$qty)
	{
	$qry	=	"SELECT * FROM category_bulk_price WHERE category_id='$category_id' AND min_qty <= '$qty' AND max_qty>='$qty' AND active='Y' ORDER BY unit_price";
	$row 	= 	$this->db->get_row($qry,ARRAY_A);
	if(count($row) && $row['unit_price']>0)
		{
		$arr	=	array("status"=>true, "amount"=>$row['unit_price']);
		}
	else
		{
		//find the bulk price for category.
		//if no bulk price for category found return false;
		$arr	=	array("status"=>false, "amount"=>0);
		}
		return $arr;
	}


	function GetPriceOfSavedArt($req)
	{
			//get the base price of the product
				  $product_price	=	$req['price'];
					if($req['accessory_id'] > 0) {
						$sql = "SELECT * FROM product_accessories WHERE id=".$req[accessory_id];	
						$accessory= $this->db->get_row($sql, ARRAY_A);
					/*	if($accessory['is_price_percentage']=="Y"){
							$price		+=	round($product_price*$accessory['adjust_price'])/100;
						}elseif($accessory['independent_qty']=="Y"){*/
							$acprice=($product_price*$req[qty]*$accessory[adjust_price])/100;
							//$price		+=	$acprice;

						/*}else{
							$price		+=	$accessory['adjust_price'];

						}*/
					}
				$total_price			=	($product_price*$req[qty] + $acprice)	;
			return $total_price;
	}
	
	function GetPriceOfPredefinedProduct($product_id=0,$price_type=0,$qty=0)
		{
		$objProduct=new Product();
		$base_price		=	0;
		$ar		=	$this->GetbulkPriceForProduct($product_id,$qty);
		if($ar['status']===true)
			{
			$output	=	$ar['amount'];
			}
		else
			{
			
			//echo "base_price: ".$base_price."<br>";
			//return base_price;
			//find the default price id if the price_type is_id is 0.
			if($price_type==0)
			{
			$qry1			=	"SELECT type_id FROM product_price WHERE product_id='$product_id'";
			$row1 			= 	$this->db->get_row($qry1,ARRAY_A);
			$price_type		=	$row1['type_id'];
			}
			if(empty($price_type))
				$price_type	=	0;
			//echo "price_type: ".$price_type."<br>";
			//exit;
			//calulate the product price
			if($product_id>0)
				{
				
				
				//get the base price of the product
				//$qry2		=	"SELECT price FROM products WHERE id=".$product_id;
				
				 $qry2		=	"SELECT product_sale_price as price,product_basic_price as basic_price FROM product_predefined WHERE id=".$product_id;
				
				
				$row2 		= 	$this->db->get_row($qry2,ARRAY_A);
				
				if($row2['price']>0)
				$base_price	=	$row2['price'];
				else
				$base_price	=	$row2['basic_price'];
				//echo "base_price: ".$base_price."<br>";
				//exit;
				if($base_price>0)
					{
					//echo "base_price: ".$base_price."<br>";
					if($price_type>0)
						{
						$output	=	$this->calculateProductPriceWithBasePrice($product_id,$price_type,$base_price);
						//echo "product_id: ".$product_id."<br>";
						//echo "output: ".$output."<br>";
						}//if($price_type>0)
					else
						{
						$output	=	$base_price;
						}
					}//if($base_price>0)
				else
					{
					$output	=	0;
					}
				}//if($product_id=0)
			else
				{
				$output		=	"Please select a product";
				}
			}
			
			$output=$objProduct->getMemberPercent($output);	
			//echo $output;
			//exit;		
		return $output;
		}	
		
		
}

?>
