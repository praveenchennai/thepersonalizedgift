<?
/**
 * Admin product
 *
 * @author nirmal
 * @package product
 */
 //error_reporting(0);
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.accessory.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.price.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.combination.php");
class Product extends FrameWork
{

	function Product()
	{
		$this->FrameWork();
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
	This function fetch all the active category of the selected owner
	@pageNo: page number starting from 0
	@limit: Number of results to be placed in page
	@params: The additional parameters to be send along with the pagination as well as with number of results drop down
	@output: Specifying the outpu format as class oblect as array
	@orderBy: for ordering the query results
	@product_search: for product search in admin side
	@catId: category id for short listing the product list in admin side
	*/
	function listAllProduct($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy,$store_id='',$product_search="",$catId='',$bId='',$mId='')
	{	
	
		global $member_type;
		global $id;
		if($catId>0)
		{
		$categories	=	"'0'";
			$categories	=	$this->getChildCategories($catId,$categories); 
			$categories	= $categories.",'".$catId."'";
			$catRs		=	mysql_query("select product_id from category_product where category_id IN ($categories)");	
			$products	=	"'0'";
			while($catRow=mysql_fetch_array($catRs))
			{	
			$products.=",'".$catRow["product_id"]."'"; }
		}
	
		if($store_id!="") {	
		
		 
				 $qry		="SELECT z.name as Made,  p.*,b.brand_name
							FROM products p
							LEFT JOIN product_made_in m ON p.id = m.product_id
							LEFT JOIN product_zone z ON m.zone_id = z.id
							LEFT JOIN brands b ON p.brand_id = b.brand_id 
							INNER JOIN (product_store as s) ON (s.product_id = p.id) WHERE s.store_id ='$store_id' AND p.custom_product='N' ";
				 if($product_search!="")
						 $qry.=	"and (p.name  LIKE '%".$product_search."%' or p.display_name  LIKE '%".$product_search."%' or p.description LIKE '%".$product_search."%' or p.cart_name LIKE '%".$product_search."%') ";
						// echo  $qry;
						// exit;
				 if($catId>0)
					 	$qry.=	"and p.id IN($products) ";
		}else{
				
				 $qry		="SELECT z.name as Made,b.brand_name, p. *
								FROM products p
								LEFT JOIN product_made_in m ON p.id = m.product_id
								LEFT JOIN product_zone z ON m.zone_id = z.id
								LEFT JOIN brands b ON p.brand_id = b.brand_id"; 
					if($member_type==3){
						 $qry= $qry."  INNER JOIN product_supplier ps ON p.id=ps.product_id";
					}
					$qry= $qry."  WHERE";
					
					if(($catId=='' || $catId==0) && $product_search=='')
					 $qry.=	" 1";
					 
				 if($product_search!=""){				 
					 $qry.=	" (p.name LIKE '%".$product_search."%' or p.display_name  LIKE '%".$product_search."%' or p.description LIKE '%".$product_search."%' or p.cart_name LIKE '%".$product_search."%')";
				 }
				 if($member_type==3){
						 $qry= $qry." AND ps.supply_id=$id";
					}
					 if($bId!=""){
						    if($catId>0){
							  $qry= $qry."  p.brand_id=$bId AND";
							 }
							 else{
							   $qry= $qry." AND p.brand_id=$bId";
							  }
							}  
					  if($mId!=""){
						    if($catId>0){
							  $qry= $qry."  m.zone_id=$mId AND";
							 }
							 else{
							   $qry= $qry." AND m.zone_id=$mId";
							  }		  
							  
					}
				// echo $catId;
				if($catId>0){		
				 if($product_search!="") {				
				  	 $qry.=	" and ";
				 }
				 
					 $qry.=	" p.id IN($products)";
				 
				 }
				 $qry.=	" AND p.hide_in_mainstore='N'";
				
			}   
			//echo $brandid;
			//echo $qry;
			// exit;
			
		//$qry		=	"select * from products as p left join (brands as b) on (p.brand_id=b.brand_id) where ";

			
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		
	    //exit;
		/*if(count($rs[0])>0)
			{
			$arr=array();
			$i=0;
			foreach ($rs[0] as $row)
				{
				$sql	=		"select z.* from product_made_in m, product_zone z where m.product_id=".$row->id." and m.zone_id=z.id" ;
				$arr	=	$this->db->get_row($sql,OBJECT);
				
				
				if($arr>0)
				$rs[0][$i]->Made=$arr->name;
				else
				$rs[0][$i]->Made='';
				$i++;
				}
			}*/
		
		return $rs;
	}
		function listAllProductofTheStore($store_id='',$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy,$product_search="",$catId='')
	{
		if(trim($catId)=="")
		{
				$qry		=	"select * from products as p left join (brands as b) on (p.brand_id=b.brand_id)";
			if($product_search!="")
				$qry	.=	" where p.name LIKE '%".$product_search."%'";
		}
		else
		{	$categories	=	"'0'";
			$categories	=	$this->getChildCategories($catId,$categories); 
			$categories	= $categories.",'".$catId."'";
			$catRs		=	mysql_query("select product_id from category_product where category_id IN ($categories)");	
			$products	=	"'0'";
			while($catRow=mysql_fetch_array($catRs))
			 {	
			 $products.=",'".$catRow["product_id"]."'"; }
			 $qry	=	"select * from products as p left join (brands as b) on (p.brand_id=b.brand_id) where id IN($products) ";
		}
		
		
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	function listRandomProduct($limit,$product_search=''){
		$qry		=	"select * from products as p left join (brands as b) on (p.brand_id=b.brand_id)";
		if($product_search!=""){
				$qry	.=	" where p.name LIKE '%".$product_search."%'";
		}
		$qry	.=	" order by rand() limit 0,{$limit}";
		$rs		=	$this->db->get_results($qry);	
		return $rs;	
	}
	/*
	This function returns the subcategory ids of the given category. 
	@output: category_id separated by ','
	@catId: input category id
	@categories: category ids from the previous function call
	*/
	function getChildCategories($catId,$categories)
	{
		$Qry	=	"select * from master_category where parent_id=$catId ";
		$Rs		=	mysql_query($Qry);
		while($Row=mysql_fetch_array($Rs))
		{	
			$categories.=",'".$Row["category_id"]."'"; 
			$cat_ID	=	$Row["category_id"];
			$Rs2	=	mysql_query("select * from master_category where parent_id=$cat_ID");
			$rowcount	=	mysql_num_rows($Rs2);
			if($rowcount!=0)
			{ 
			$categories.=",".$this->getChildCategories($cat_ID,$categories);
			}
		}
		return $categories;
	}
	function listAllSettings($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy)
	{
		$qry		=	"select * from product_settings";
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}

	function GetAllBrands()
	{
		$qry				=	"select * from brands";
		$rs['brand_id'] 	= 	$this->db->get_col($qry, 0);
		$rs['brand_name'] 	= 	$this->db->get_col($qry, 1);
		return $rs;
	}
	function GetAllCategoty()
	{
		$qry		=	"SELECT mc.* FROM `master_category`
						AS mc LEFT JOIN (category_modules AS cm, module AS m) 
						ON ( 	m.name = 'Product' AND 
								m.id = cm.module_id AND 
								cm.category_id = mc.category_id
							) order by mc.category_name";
		$rs['category_id'] 		= 	$this->db->get_col($qry, 0);
		$rs['category_name'] 	= 	$this->db->get_col($qry, 2);
		return $rs;
	}
	function GetAllProductCategoty($product_id=0)
	{
		if($product_id>0)
		{
			$qry				=	"select category_id from category_product where product_id=$product_id";
			$rs['category_id'] 	= 	$this->db->get_col($qry, 0);
			return $rs;
		}
	}
	function GetAllProductCategotyName($product_id=0)
	{
		if($product_id>0)
		{
			$qry		=	"select ms.category_name from master_category ms, category_product cp where ms.category_id=cp.category_id and cp.product_id=$product_id";
			$rs['category_name'] = $this->db->get_col($qry, 0);
			return $rs;
		}
	}
	function ProductGet($id=0)
	{
		if($id>0)
		{
			
			$rs 	=	$this->db->get_row("Select * from products where id='$id'", ARRAY_A);			
			return $rs;
		}
	}
	//function to get Related product names from table product_related. created by Jeffy on 31st aug 2007
	function RelatedProductGet($id=0)
	{
		if($id>0)
		{
			//$rs 	=	$this->db->get_results("Select product_related.related_id from product_related where product_related.product_id='$id'");
			$rs 	=	$this->db->get_results("Select product_related.related_id,products.* from product_related,products where product_related.product_id={$id} and product_related.related_id = products.id");

			//$rs 	=	$this->db->get_results("Select * from products where id=".$rs[0]->related_id."");
			return $rs;
		}
	}
	function GetProductName($id=0)
		{
		$rs 	=	$this->db->get_row("Select * from products where id='$id'", ARRAY_A);
			return $rs['name'];
		}
	function SettingsGet($id=0)
	{
		if($id>0)
		{
			$rs 	=	$this->db->get_row("Select * from product_settings where id='$id'", ARRAY_A);
			return $rs;
		}
	}
	function ProductAddEdit(&$req,$file,$tmpname,$swf='',$tmp_swf='',$two_d='',$tmp_two_d='',$overl='',$tmp_over='',$pdf_file='',$tmp_pdf_file='',$psd_file='',$tmp_psd_file='',$ai_image='',$tmp_ai_image='',$over2='',$tmp_over2='',$over3='',$tmp_over3='',$over4='',$tmp_over4='',$over5='',$tmp_over5='',$type='') {
		extract($req);		
		
		
		global	$member_type;
		global	$user_id;
		//echo $store_id;
		//exit;
		//list($width,$height)	=	split(',',$this->config['product_thumb_image']);
		//echo "product_desc_image: ".$width."<br>";
		//exit;
		//echo "<pre>";
		//print_r($req);
		//foreach ($accessory['All'] as $access_id)
		//{
		//echo "<br>";
		//print_r($access_id);
		//}
		//print_r($_SESSION['StoreAccessories']);
		//exit;
		
		
		
		$date_created		=	date("Y-m-d H:i:s");
		$param	=	array();
		if ($file){
			$dir			=	SITE_PATH."/modules/product/images/";
			$file1			=	$dir."thumb/";
			$resource_file	=	$dir.$file;
			$path_parts 	= 	pathinfo($file);
			$param["image_extension"]='1';
/*--------------------------------------------------------------------------------------------*/
			/*$rs 	=	$this->db->get_row("select value from config where field='product_imagetype'", ARRAY_A);
			$val=$rs['value'];
			if($val=='gif'){
			echo   $image_extension	=	"gif";
			exit();
			}
/*--------------------------------------------------------------------------------------------*/
		}
		
		if(isset($clone))
			{
			//echo "clone";
			$id='';
			}	
		//exit;	
		/*last*/
		if($parent_id>0 && $path_parts['extension']==''){
		//echo "1<br>";
		
			 $image_extension	=	"jpg";
			
		}else{
		//echo "2<br>";
			$image_extension	=	$path_parts['extension'];
			
		}	
		//------------------------------------------------------
		if($this->config['product_imagetype'] == 'gif' && $file!="") {
		$image_extension	 ='gif';	
		}
		
		if($this->config['product_imagetype'] == 'gif') {
			//$image_extension	 ='gif';	
			$two_dimage_extension='gif';
			$overlimage_extension='gif';
			$over2image_extension='gif';
			$over3image_extension='gif';
			$over4image_extension='gif';
			$over5image_extension='gif';
		} else {
			$path_parts11 			= 	pathinfo($two_d);
			$two_dimage_extension	=	$path_parts11['extension'];
			
			$path_parts22 			= 	pathinfo($overl);
			$overlimage_extension	=	$path_parts22['extension'];
			
			$path_over22 			= 	pathinfo($over2);
			$over2image_extension	=	$path_over22['extension'];
			
			$path_over33 			= 	pathinfo($over3);
			$over3image_extension	=	$path_over33['extension'];
			
			$path_over44 			= 	pathinfo($over4);
			$over4image_extension	=	$path_over44['extension'];
			
			$path_over55 			= 	pathinfo($over5);
			$over5image_extension	=	$path_over55['extension'];;
		}
			
		//----------------------------------------------------
			
		
		$save_gift_property	=	false;
		//Adding price
		//Adding price
		$brand_id			=	$req['brand_id'];
		
		if(count($category)>0) {
		
			if(!trim($name)) {
			$message 			=	$sId." name is required";
				return array("status"=>false,"message"=>$message);
				exit;
			} else {
				if($personalise_with_monogram)
					{
					$stat_personalise_with_monogram="Y";
					}
				else
					{
					$stat_personalise_with_monogram="N";
					}
				if(empty($display_gross))
					$display_gross="N";
				if(empty($display_related))
					$display_related="N";
				if(empty($out_stock))
					$out_stock="N";
				if(empty($hide_in_mainstore))
					$hide_in_mainstore="N";
				if(empty($display_name))
					$display_name=$name;
			
				if(empty($id)){
					if($is_giftcertificate)
					{
					//save giftcertificate properties
					$type_option			=	$req['coupon_options'];
					if($type_option			==	'fixed')
					$no_times			=	$req['one_times'];
					$duration				=	$req['duration'];
					$save_gift_property		=	true;
					$stat_is_giftcertificate = "Y";
					}
					else
					{
					$stat_is_giftcertificate =	"N";
					}
				}
				else
				{
					$sam_Product				=	$this->ProductGet($id);
					$stat_is_giftcertificate	=	$sam_Product['is_giftcertificate'];
				}
					
					
					
					
				//exit;
				//calculate the base price from category selectet
				
				
				
				
				if($price=='')
					{
					if($category)
						{
						$cat_price=0;
						foreach ($category as $category_id)
							{
								$qry	=	"SELECT base_price FROM master_category WHERE category_id=".$category_id;
								$rs		=	$this->db->get_row($qry,ARRAY_A);
								if($rs['base_price']>$cat_price)
									$cat_price=$rs['base_price'];
							}
						if($cat_price>0)
							$price 	=	$cat_price;
						}
					}
					if($active=='')
					{
					$active='N';
					}
				$done_status="Y";
				if ($file){
					$array 			= 	array("product_id"=>$product_id,
					"name"=>mysql_real_escape_string(htmlentities($name)),
					"display_name"=>mysql_real_escape_string($display_name),
					"cart_name"=>mysql_real_escape_string($cart_name),
					"brand_id"=>$brand_id,
					"parent_id"=>$parent_id,
					"group_id"=>$group_id,
					"price"=>$price,
					"weight"=>$weight,
					"description"=>mysql_real_escape_string($description),
					"image_extension"=>$image_extension,
					
					"date_created"=>$date_created,
					"personalise_with_monogram"=>$stat_personalise_with_monogram,
					"is_giftcertificate"=>$stat_is_giftcertificate,
					"thickness"=>$thickness,
					"display_gross"=>$display_gross,
					"display_related"=>$display_related,
					"display_order"=>$display_order,
					"out_stock"=>$out_stock,
					"out_message"=>$out_message,
					"hide_in_mainstore"=>$hide_in_mainstore,
					"size"=>$size,
					"width"=>$width,
					"x_co"=>$x_co,
					"y_co"=>$y_co,
					"active"=>$active,
					"dual_side"=>$dual_side,
					"image_area_height"=>$image_area_height,
					"image_area_width"=>$image_area_width
					);
				}else{
					$array 			= 	array("product_id"=>$product_id,
					"name"=>mysql_real_escape_string(htmlentities($name)),
					"display_name"=>mysql_real_escape_string($display_name),
					"cart_name"=>mysql_real_escape_string($cart_name),
					"brand_id"=>$brand_id,
					"parent_id"=>$parent_id,
					"group_id"=>$group_id,
					"price"=>$price,
					"weight"=>$weight,
					"description"=>mysql_real_escape_string($description),
					"date_created"=>$date_created,
					"personalise_with_monogram"=>$stat_personalise_with_monogram,
					"is_giftcertificate"=>$stat_is_giftcertificate,
					"thickness"=>$thickness,
					"display_gross"=>$display_gross,
					"display_related"=>$display_related,
					"display_order"=>$display_order,
					"out_stock"=>$out_stock,
					"out_message"=>$out_message,
					"hide_in_mainstore"=>$hide_in_mainstore,
					"size"=>$size,
					"width"=>$width,
					"x_co"=>$x_co,
					"y_co"=>$y_co,
					"dual_side"=>$dual_side,
					"active"=>$active,
					"image_area_height"=>$image_area_height,
					"image_area_width"=>$image_area_width
					//"status"=>$done_status
					);
					if($seo_name){
						$array["seo_name"]=$seo_name;
					}

					$array["page_title"]=$page_title;
					$array["meta_description"]=$meta_description;
					$array["meta_keywords"]=$meta_keywords;

				if(trim($image_extension))
					{
							
						$newarray		=	array("image_extension"	=>	$image_extension);
						$array			=	array_merge($array,$newarray);
						$param["image_extension"]='1';
					}
				/*echo "<pre>";	
				print_r($array);
				exit;*/
				}
				
	
				
				# Add Quantity If Exist 
				if (isset($quantity)) {
					if ( trim($quantity) || $quantity == 0  ) {
						
						if ($quantity>0)
						$newarray		=	array("quantity"	=>	$quantity);
						else
						$newarray		=	array("quantity"	=>	0);
						
						$array			=	array_merge($array,$newarray);
					}
				}
				
				
				
				if(($swf))
					{
					
						$path_parts3 	= 	pathinfo($swf);
						$newarray		=	array("swf"	=>	$path_parts3['extension']);
						$array			=	array_merge($array,$newarray);
					}

				if(($two_d))
					{
						$path_parts1 		= 	pathinfo($two_d);
						$SourceExtension	=	$path_parts1['extension'];
						$newarray			=	array("two_d_image"	=>	$two_dimage_extension);
						$array				=	array_merge($array,$newarray);
						$param["two_d_image"]='1';
					}
				if(($overl))
					{
						$path_parts2 		= 	pathinfo($overl);
						$SourceExtension	=	$path_parts2['extension'];
						$newarray			=	array("overlay"	=>	$overlimage_extension);
						$array				=	array_merge($array,$newarray);
						$param["overlay"]='1';
					}
				if($over2)
					{
						$path_over2 		= 	pathinfo($over2);
						$SourceExtension	=	$path_over2['extension'];
						$newarray			=	array("overlay2"	=>	$over2image_extension);
						$array				=	array_merge($array,$newarray);
						$param["overlay2"]='1';
					}
				if($over3)
					{
						$path_over3 		= 	pathinfo($over3);
						$SourceExtension	=	$path_over3['extension'];
						$newarray			=	array("overlay3"	=>	$over3image_extension);
						$array				=	array_merge($array,$newarray);
						$param["overlay3"]='1';
					}
				if($over4)
					{
						$path_over4 		= 	pathinfo($over4);
						$SourceExtension	=	$path_over4['extension'];
						$newarray			=	array("overlay4"	=>	$over4image_extension);
						$array				=	array_merge($array,$newarray);
						$param["overlay4"]='1';
					}
				if($over5)
					{
						$path_over5 		= 	pathinfo($over5);
						$SourceExtension	=	$path_over5['extension'];
						$newarray			=	array("overlay5"	=>	$over5image_extension);
						$array				=	array_merge($array,$newarray);
						$param["overlay5"]='1';
					}
					if(($pdf_file))
					{
						$path_parts3 	= 	pathinfo($pdf_file);
						$file_ext3		=	$path_parts3['extension'];
						if($file_ext3=='PDF' || $file_ext3=='Pdf' || $file_ext3='pdf')
						{
							$save_filename3	=	$id."_".$pdf_file;
							$newarray		=	array("pdf_file"	=>	$save_filename3);
							$array			=	array_merge($array,$newarray);
							
							$dir3			=	SITE_PATH."/modules/product/files/";
							_upload($dir3,$save_filename3,$tmp_pdf_file,0,0,0);
						}
					}
					if(($psd_file))
					{
						$path_parts4 	= 	pathinfo($psd_file);
						$file_ext4		=	$path_parts4['extension'];
						if($file_ext4=='PSD' || $file_ext4=='Psd' || $file_ext4='psd')
						{
							$save_filename4	=	$id."_".$psd_file;
							$newarray		=	array("psd_file"	=>	$save_filename4);
							$array			=	array_merge($array,$newarray);
							
							$dir3			=	SITE_PATH."/modules/product/files/";
							_upload($dir3,$save_filename4,$tmp_psd_file,0,0,0);
						}
					}
					if(($ai_image))
					{
						$path_parts5 	= 	pathinfo($ai_image);
						$file_ext5		=	$path_parts5['extension'];
						if($file_ext5=='AI' || $file_ext5=='Ai' || $file_ext5='ai')
						
						{
							$save_filename5	=	$id."_".$ai_image;
							$newarray		=	array("ai_image"	=>	$save_filename5);
							$array			=	array_merge($array,$newarray);
							
							$dir3			=	SITE_PATH."/modules/product/files/";

							_upload($dir3,$save_filename5,$tmp_ai_image,0,0,0);
						}
					}
					
					

				//manage product from store
				
					if($manage=='manage' && $id>0)
						{
						
						$old_product_id='';
					    $qry	=	"SELECT * from product_store ps where ps.product_id=$id and store_id!=$store_id";
						$res	=	$this->db->get_results($qry,ARRAY_A);
						
					
						if($res)//The product is assigned to other stores
							{
							//echo "The product is assigned to other stores"."<br>";
							$this->db->query("DELETE FROM product_store WHERE product_id='$id' and store_id=$store_id");
							$old_product_id=$id;//for getting the old product images to new product
							$id="";
							$array['hide_in_mainstore']='Y';
							}
						else// the product is assigned to this store only
							{
							//echo "the product is assigned to this store only"."<br>";
							//$this->db->query("DELETE FROM product_store WHERE product_id='$id' and store_id=$store_id");
							$this->RemoveAllAccessorySelected($id);//remove the old accessories
							$array['hide_in_mainstore']='Y';
							}
						
						}
						
					if($manage=='manage' && $remove=='yes')
					{
					$this->RemoveImage($id,"image_extension");
					}
				// manage exclude accessory
				if ( $ex_accessory )
				$array['exclude_group'] 	=	implode ( "," , $ex_accessory ); 		
						
				//manage product from store
				if($id) {
				
					$array['id'] 	= 	$id;



					
					//echo "the product is updated at $id"."<br>";
					$this->db->update("products", $array, "id='$id'");
					//if($Append!='Y')
						//{
							if($manage!='manage')
							{
							//$this->deleteRelatedStore($id);
							//$this->RemoveAllAccessorySelected($id);
							}
						//}
					
						
						
				} else {
				
					$this->db->insert("products", $array);					
					 $id 			= 	$this->db->insert_id;
					if($member_type==3){
						$assignSupplier		=	array("product_id"	=>	$id,"supply_id"=>$user_id);
						$this->db->insert("product_supplier", $assignSupplier);
					}
					//for saving imapge product of the old products
					//print_r($param);exit;
					if($manage=='manage' && $old_product_id>0)
						{
						$this->CopyOldImagesToNewProduct($old_product_id,$id,$param);
						
							$product_storeArray=array("product_id"	=>	$id,"store_id"=>$store_id);
							$this->db->insert("product_store", $product_storeArray);
							
							//echo $product_storeArray["store_id"];
						}
						
					
					
					
					//echo "the product is added at $id"."<br>";
					//save gift certificate properties
					if($save_gift_property	===	true)
						{
						$gift_cert			=	array("product_id"=>$id,"type_option"=>$type_option,"no_times"=>$no_times,"duration"=>$duration);
						$this->db->insert("certificate_property", $gift_cert);
						//echo "Inserted Gift certificate<br>";
						}
						else if(!isset($clone))
						{
						}
					
				}
				//echo "Updated Product table<br>";
				//Assigning Product Zones
				if($zone_id>0)
					{
					//deleting exisiting zones of the product
					$this->db->query("DELETE FROM product_made_in WHERE product_id='$id'");
					//echo "Inserted Product zone <br>";
					
					//entering new zones for the product
					$ins_zone	=	array("zone_id"=>$zone_id,"product_id"=>$id);
					$this->db->insert("product_made_in", $ins_zone);
					}//if($zone_id>0)
				
				//Assigning product Price
				$priceObj	=	new Price();
				$this->db->query("DELETE FROM product_price WHERE product_id='$id'");
				if($req['is_percentage'])
								{
								$save_perc	=	"Y";
								}
							else
								{
								$save_perc	=	"N";
								}
				$pricearray 			= 	array("type_id"=>$req['price_type'],
														"product_id"=>$id,
														"price"=>$req['prices'],
														"is_percentage"=>$save_perc);
				if($req['price_type']>0)
				{
					if($req['prices']>0)
						{
						$this->db->insert("product_price", $pricearray);
						//echo "Inserted Product price <br>";
						}
				}
				
				
				//Assigning product to category
				$this->db->query("DELETE FROM category_product WHERE product_id='$id'");
				foreach ($category as $category_id)
				{
					$ins			=	array("category_id"=>$category_id,"product_id"=>$id);
					$this->db->insert("category_product", $ins);
					//echo "Inserted Product category <br>";
				}
				
				
			}
		}
		else
		{
			$message 		=	"Category is required";
			//echo "No category <br>";
			return array("status"=>false,"message"=>$message);
			//exit;
			
		}
		if($swf)
				{
				
					$dir3			=	SITE_PATH."/modules/product/images/";
					$path_parts3 	= 	pathinfo($swf);
					$save_filename3	=	"swf_".$id.".".$path_parts3['extension'];
					_upload($dir3,$save_filename3,$tmp_swf,0,0,0);
				}
		if($two_d)
				{
				   $SourceExtension=$path_parts1['extension'];
					$dir1			=	SITE_PATH."/modules/product/images/";
					$path_parts1 	= 	pathinfo($two_d);
					$save_filename1	=	"2D_".$id.".".$two_dimage_extension;
					
					_upload($dir1,$save_filename1,$tmp_two_d,0,0,0,$SourceExtension);
				}
		if($overl)
				{
				    $SourceExtension=$path_parts2['extension'];
					$dir1			=	SITE_PATH."/modules/product/images/";
					$path_parts2 	= 	pathinfo($overl);
					$save_filename2	=	"OV_".$id.".".$overlimage_extension;
					_upload($dir1,$save_filename2,$tmp_over,1,130,109,$SourceExtension);
					
				}
		if($over2)
				{
				    $SourceExtension=$path_over2['extension'];
					$dir1			=	SITE_PATH."/modules/product/images/";
					$path_over2 	= 	pathinfo($over2);
					$save_over2	=	"OV2_".$id.".".$over2image_extension;
					_upload($dir1,$save_over2,$tmp_over2,1,130,109,$SourceExtension);
				}		
		if($over3)
				{
				 $SourceExtension=$path_over3['extension'];
					$dir1			=	SITE_PATH."/modules/product/images/";
					$path_over3 	= 	pathinfo($over3);
					$save_over3	=	"OV3_".$id.".".$over3image_extension;
					_upload($dir1,$save_over3,$tmp_over3,1,130,109,$SourceExtension);
				}
		if($over4)
				{
				    $SourceExtension=$path_over4['extension'];
					$dir1			=	SITE_PATH."/modules/product/images/";
					$path_over4 	= 	pathinfo($over4);
					$save_over4	=	"OV4_".$id.".".$over4image_extension;
					_upload($dir1,$save_over4,$tmp_over4,1,130,109,$SourceExtension);
				}
		if($over5)
				{
				    $SourceExtension=$path_over5['extension'];
					$dir1			=	SITE_PATH."/modules/product/images/";
					$path_over5 	= 	pathinfo($over5);
					$save_over5	=	"OV5_".$id.".".$over5image_extension;
					_upload($dir1,$save_over5,$tmp_over5,1,130,109,$SourceExtension);
				}			
				
				
		if($file){
			if($path_parts['extension']=='swf'	||	$path_parts['extension']=='Swf' 	|| 	$path_parts['extension']=='SWF')
					{
					$save_filename	=	$id.".".$path_parts['extension'];
					//echo $dir;
					//echo $save_filename;
					_upload($dir,$save_filename,$tmpname,0,0,0);
					//$this->db->update("products", array('swf'=>$swf_filename), "id='$id'");
					}
					else
					{
					$SourceExtension	=	$path_parts['extension'];
						$save_filename	=	$id.".".$image_extension;
						$new_name		=	$id."_des_.".$image_extension;
						list($thumb_width,$thumb_height,)	=	split(',',$this->config['product_thumb_image']);
						
						if($thumb_width>0 && $thumb_height>0)
						_upload($dir,$save_filename,$tmpname,1,$thumb_width,$thumb_height,$SourceExtension);
						else
						_upload($dir,$save_filename,$tmpname,0);
						//upload description iamges
						//echo "$new_name";
						$path=$dir;
						$thumb=$dir."thumb/";
						list($desc_width,$desc_height,)	=	split(',',$this->config['product_desc_image']);
						if($desc_width>0 && $desc_height>0)
						
						thumbnail($path,$thumb,$save_filename,$desc_width,$desc_height,$mode,$new_name,$SourceExtension);
						//upload listing images
						$new_name		=	$id."_List_.".$image_extension;
						//echo "$new_name";
						list($list_width,$list_height,)	=	split(',',$this->config['product_list_image']);
						if($list_width>0 && $list_height>0)
						
						thumbnail($path,$thumb,$save_filename,$list_width,$list_height,$mode,$new_name);
					
						$new_name		=	$id."_thumb2_.".$image_extension;
						//echo "$new_name";
						list($thumb2_width,$thumb2_height,)	=	split(',',$this->config['product_thumb2_image']);
						if($thumb2_width>0 && $thumb2_height>0)
						thumbnail($path,$thumb,$save_filename,$thumb2_width,$thumb2_height,$mode,$new_name,$SourceExtension);
					
						$new_name		=	$id."_thumb3_.".$image_extension;
						//echo "$new_name";
						list($thumb3_width,$thumb3_height,)	=	split(',',$this->config['product_thumb3_image']);
						if($thumb3_width>0 && $thumb3_height>0)
						thumbnail($path,$thumb,$save_filename,$thumb3_width,$thumb3_height,$mode,$new_name,$SourceExtension);
					
					//echo "Uploaded image <br>";
					}
		}
		/**************************************************************/
		//STORE TO PRODUCT 
		
		//$this->RemoveAllAccessorySelected($id);
		//store_id  == 0; main store
		/*if(count($accessory[0])>0)
				{
				foreach ($accessory[0] as $access_id)
					{
					$this->SetProductAccessory($id,$access_id,0);
					}
				}
		//All for registering the parameter category in 
		if(count($accessory['All'])>0)
			{
			foreach ($accessory['All'] as $access_id)
				{
				$this->SetProductAccessory($id,$access_id,0);
				}
			}
		for($i=0;$i<count($stores_id);$i++)
			{
			$this->relatedInsertStore($id,$stores_id[$i]);
			//All
			if(count($accessory['All'])>0)
				{
				foreach ($accessory['All'] as $access_id)
					{
					$this->SetProductAccessory($id,$access_id,$stores_id[$i]);
					}
				}
			
			//with store id
			//echo "stores_id: $stores_id[$i]<br>";
			if(count($accessory[$stores_id[$i]])>0)
				{
				foreach ($accessory[$stores_id[$i]] as $access_id)
					{
					$this->SetProductAccessory($id,$access_id,$stores_id[$i]);
					}
				}
			}//for loop of store id*/
		//exit;
		/**************************** saving product available accessories with the new table for store ****************/
		
		if(count($accessory['All'])>0)
			{
			foreach ($accessory['All'] as $access_id)
				{
				$this->SetProductAccessory($id,$access_id,0,'YES');
				}
			}
		if(count($accessory[0])>0)
				{
				foreach ($accessory[0] as $access_id)
					{
					$this->SetProductAccessory($id,$access_id,0,'NO');
					}
				}
				/*for($i=0;$i<count($stores_id);$i++)
			{
			$this->relatedInsertStore($id,$stores_id[$i]);
			if(count($accessory[$stores_id[$i]])>0)
				{
				foreach ($accessory[$stores_id[$i]] as $access_id)
					{
					$this->SetProductAccessory($id,$access_id,$stores_id[$i],'NO');
					}
				}
			}*/
// modified by Ratheesh kk, modified by vipin on 07-01-2008
if($this->config["artist_selection"] == "Yes"){
       if (count($accessory['All'])=='0' and count($accessory[0])=='0')
	     {
		 // ?? to retheeshkk
			$this->relatedInsertStore($id,$stores_id);
			if(count($accessory[$stores_id[$i]])>0)
				{
				foreach ($accessory[$stores_id[$i]] as $access_id)
					{
					$this->SetProductAccessory($id,$access_id,$stores_id[$i],'NO');
					}
				}
			 }		
		 }
		/**************************** saving product available accessories with the new table for store ****************/
		// by shinu 20-06-07 for saing accessory from session 
		//array structure(accessoryid,productid,adjustprice,adjustweight,cartname)
		$MultArr	=	$_SESSION['MultiAccessory'];
		if($MultArr)
		{
			$newMultArr	=	array_reverse($MultArr);
			foreach($newMultArr as $value) 
			{
				$ses_accessory_id	=	$value[0]; 
				$ses_product_id	=	$value[1];
				$ses_adjust_price	=	$value[2];
				$ses_adjust_weight	=	$value[3];
				$ses_cart_name	=	$value[4];
				if($ses_product_id=="" || $ses_product_id=="0" ) { $ses_product_id=$id; } 
				$ses_newarray 			= 	array("adjust_price"=>$ses_adjust_price,"adjust_weight"=>$ses_adjust_weight,"cart_name"=>$ses_cart_name);
				
				$this->db->update("product_availabe_accessory", $ses_newarray, "accessory_id='$ses_accessory_id' and product_id='$ses_product_id'");
			}	
		}				
		unset($_SESSION['MultiAccessory']);
		// print_r($MultArr);
		//exit;
		/*
					$ses_adjust_weight	=	$_SESSION['ses_adjust_weight'];
					$ses_adjust_price	=	$_SESSION['ses_adjust_price'];
					$ses_cart_name		=	$_SESSION['ses_cart_name'];
					$ses_accessory_id	=	$_SESSION['ses_accessory_id'];
					//echo "<br><br>session<br>----------<br><br>";
					//print(" sid ".$_SESSION['ses_cart_name']);
					//exit;
					if($ses_accessory_id!="")
					{
						if($ses_adjust_price!="")
						{
							$newarray1 			= 	array("adjust_price"=>$ses_adjust_price);
							$this->db->update("product_availabe_accessory", $newarray1, "accessory_id='$ses_accessory_id' and product_id='$id'");
						}
						if($ses_adjust_weight!="")
						{
							$newarray2 			= 	array("adjust_weight"=>$ses_adjust_weight);
							$this->db->update("product_availabe_accessory", $newarray2, "accessory_id='$ses_accessory_id' and product_id='$id'");
						}
						if($ses_cart_name!="")
						{
							$newarray3			= 	array("cart_name"=>$ses_cart_name);
							$this->db->update("product_availabe_accessory", $newarray3, "accessory_id='$ses_accessory_id' and product_id='$id'");
						}
						unset($_SESSION['ses_adjust_weight']);
						unset($_SESSION['ses_adjust_price']);
						unset($_SESSION['ses_accessory_id']);
						unset($_SESSION['ses_cart_name']);
					}*/
					
					
		//print_r($id);
		//exit;
		

		return array("status"=>true,"id"=>$id);
		//return $id;
	}
	function GetFromProductAccessoryStore($product_id=0)
		{
		//echo $product_id;
		$res=array();
		if($product_id>0)
			{
			$res=$this->GetStoreIdFromProductAccessoryStore($product_id);
			//print_r($res);
			for($i=0;$i<count($res);$i++)
				{
				$available_ids=$this->GetAvailableFromProductAccessoryStore($product_id,$res[$i]['store_id']);
				for($j=0;$j<count($available_ids);$j++)
					{
					$res[$i][$j]=$this->GetProductAvailableAccessory($available_ids[$j]['available_id']);
					}
				}
			}
		return $res;
		}
	function GetFromDefaultAccessoryStore()
		{
		//echo $product_id;
		$res=array();
		$res=$this->GetStoreIdFromDefaultAccessoryStore();
			//print_r($res);
			//exit;
			for($i=0;$i<count($res);$i++)
				{
				//echo $res[$i]['store_id'];
				$available_ids=$this->GetAllFromDefaultAccessoryStore($res[$i]['store_id']);
				//print_r($available_ids);
				for($j=0;$j<count($available_ids);$j++)
					{
					//print($available_ids[$j]['id']);
					$res[$i][$j]=$this->GetDefaultAvailableAccessory($available_ids[$j]['id']);
					}
				}
		//print_r($res);
		//exit;
		return $res;
		}	
	function GetStoreIdFromProductAccessoryStore($product_id)
		{
		$qry	=	"SELECT ps.store_id from product_accessory_store ps, product_availabe_accessory pa where pa.id=ps.available_id and pa.product_id=".$product_id." group by ps.store_id";
		$res	=	$this->db->get_results($qry,ARRAY_A);
		return $res;
		}
	function GetAvailableFromProductAccessoryStore($product_id,$store_id)
		{
		$qry	=	"SELECT ps.available_id from product_accessory_store ps, product_availabe_accessory pa where pa.id=ps.available_id and pa.product_id=".$product_id." and ps.store_id=".$store_id;
		$res	=	$this->db->get_results($qry,ARRAY_A);
		return $res;
		}
	function GetProductAvailableAccessory($id)
		{
		$qry	=	"select * from product_availabe_accessory where id=".$id;
		$res	=	$this->db->get_row($qry,ARRAY_A);
		return $res;
		}
	function GetStoreIdFromDefaultAccessoryStore()
		{
		$qry	=	"SELECT s.store_id from store_accessory_default s group by s.store_id";
		$res	=	$this->db->get_results($qry,ARRAY_A);
		return $res;
		}
	function GetAllFromDefaultAccessoryStore($store_id)
		{
		$qry	=	"SELECT s.id from store_accessory_default s where s.store_id=".$store_id;
		$res	=	$this->db->get_results($qry,ARRAY_A);
		return $res;
		}
	function GetDefaultAvailableAccessory($id)
		{
		$qry	=	"select * from store_accessory_default where id=".$id;
		$res	=	$this->db->get_row($qry,ARRAY_A);
		return $res;
		}
	/* This function is used for adding default accessory and product to the stores */
	function defaultProductAccessoryAddEdit($req)
	{
		extract($req);
		//print_r($req); break;
		if($Append_product_store!='Y')
		{
			if(isset($mainstore))
			{
				$main_id=0;
				$this->deleteDefaultStoreAccessory($main_id);
			}
			if(count($stores_id)>0)
				{
				foreach ($stores_id as $store_id_values)
				{	
					$this->deleteDefaultStoreAccessory($store_id_values);
				}
			}
		}
					
		//All for registering the parameter category in 
		if(count($accessory)>0)
		{
				$store_ids=array_keys($accessory);
				$i	=	0;
				foreach ($accessory as $access_id)
				{	
					foreach ($access_id as $accessory_id)
					{	
						$this->SetDefaultProductAccessory($accessory_id,$store_ids[$i]);
					}
					$i++;
				}
		}
		// updating the accessory to all product under the store
		if(isset($mainstore))
		{
			$main_id=0;
			$mainstoreres	=	mysql_query("select * from product_store where store_id='0'");
			while($mainstorerow=mysql_fetch_array($mainstoreres))
			{
				$storeProductId	=	$mainstorerow["product_id"];
				$accessoryres	=	mysql_query("select * from store_accessory_default where store_id='0'");
				while($accessoryrow=mysql_fetch_array($accessoryres))
				{
					$default_category_id	=	$accessoryrow["category_id"];
					$default_accessory_id	=	$accessoryrow["accessory_id"];
					$default_group_id	=	$accessoryrow["group_id"];
					//This should not update the already existing product. Commented by Nirmal
					//$this->AddProductAccessoryFromDefault($main_id,$storeProductId,$default_category_id,$default_accessory_id,$default_group_id);
				}
			 }
		  }
		if(count($stores_id)>0)
		{
				foreach ($stores_id as $store_id_new)
				{	
					$mainstoreres	=	mysql_query("select * from product_store where store_id='store_id_new'");
					while($mainstorerow=mysql_fetch_array($mainstoreres))
					{
						$storeProductId	=	$mainstorerow["product_id"];
						$accessoryres	=	mysql_query("select * from store_accessory_default where store_id='0'");
						while($accessoryrow=mysql_fetch_array($accessoryres))
						{
							$default_category_id	=	$accessoryrow["category_id"];
							$default_accessory_id	=	$accessoryrow["accessory_id"];
							$default_group_id	=	$accessoryrow["group_id"];
							//This should not update the already existing product. Commented by Nirmal
							//$this->AddProductAccessoryFromDefault($main_id,$storeProductId,$default_category_id,$default_accessory_id,$default_group_id);
						}
					 }
				}
		}
		
		// updating the accessory to all product under the store
		//break;
	
		
	}
	/* this function is used for adding default accessory and product to the stores */
	
	// this function is used for adding the default accessory to all store products
	function AddProductAccessoryFromDefault($store,$product_id,$category_id,$accessory_id,$group_id)
	{
		$checkres	=	mysql_query("select * from product_availabe_accessory where product_id='$product_id' and category_id='$category_id' and accessory_id='$accessory_id' and store_id='$store' and group_id='$group_id'");
		$count	=	mysql_num_rows($checkres);
		if($count==0)
		{
			$array 		=	array("product_id"=>$product_id,"category_id"=>$category_id,"accessory_id"=>$accessory_id,"store_id"=>$store,'group_id'=>$group_id);
			$this->db->insert("product_availabe_accessory", $array);
		}
	}
	
	/* this function is used for deleting the accessory from store_accessory_default table */
	function deleteDefaultStoreAccessory($id)
	{
		$this->db->query("DELETE FROM store_accessory_default WHERE store_id='$id'");

	}
	/* this function is used for deleting the accessory from store_accessory_default table */
	
	/* this function is used for adding default accessory and product to the stores */
	function SetDefaultProductAccessory($accessory_category,$store_id='0')
	{
		list($accessory_id,$category_id,$group_id)		=	split('_',$accessory_category);
		$qry	=	"select * from store_accessory_default where accessory_id='$accessory_id' and store_id='$store_id' and category_id='$category_id'";
		$res	=	mysql_query($qry);
		$count	=	mysql_num_rows($res);
		if($count==0)
		{
			$array 		=	array("accessory_id"=>$accessory_id,"store_id"=>$store_id,"category_id"=>$category_id,"group_id"=>$group_id);
			$this->db->insert("store_accessory_default", $array);
		}
			
	}
	/* this function is used for adding default accessory and product to the stores */
	
	
	
	function ProductGetDetails($product_id=0)
	{
		if($product_id>0)
		{

			$arr 	= 	$this->db->get_row("Select
											name,brand_id
										from 
											products
										where 
											id='$product_id'", ARRAY_A);

			if(count($arr)>0)
			{
				$rs['product_name']		=	$arr['name'];
				if($arr['brand_id']>0)
				{
					$arr_brand 			= 	$this->db->get_row("Select
											brand_name
										from 
											brands
										where 
											brand_id=".$arr['brand_id'], ARRAY_A);
					$rs['brand_name']	=	$arr_brand['brand_name'];
				}
				else
				{
					$rs['brand_name']	=	"No Brand";
				}
			}
			$cat=$this->GetAllProductCategotyName($product_id);
			foreach ($cat as $catname)
			{
				$rs['category_name'] 	=	(implode(', ',$catname));
			}
			
			
			
			return $rs;
		}
	}
	/*****************   Product description managment function  ******************/
	/////********* Delete a product **********///////////
	function deleteProduct($product_id,$store_id=0)
	{
		global $member_type;
		global $user_id;		
		if($member_type==3){
			$this->db->query("DELETE 	FROM 	product_supplier  WHERE product_id ='$product_id' AND supply_id ='$user_id'");
			return true;
		}else{		
				if($store_id==0 || $store_id=='')
				{
					$this->db->query("DELETE 		FROM 	products 					WHERE id='$product_id'");
					$this->db->query("DELETE 		FROM 	category_product 			WHERE product_id='$product_id'");
					$this->db->query("DELETE 		FROM 	product_accessory_exclude	WHERE product_id='$product_id'");
					$this->db->query("DELETE pa,ps 	FROM 	product_availabe_accessory pa,product_accessory_store ps WHERE pa.product_id='$product_id' and pa.id=ps.available_id");
					$this->db->query("DELETE 		FROM 	product_availabe_accessory	WHERE product_id='$product_id'");
					$this->db->query("DELETE 		FROM 	product_store  				WHERE product_id='$product_id'");
					$this->db->query("DELETE 		FROM 	product_settings_items 		WHERE product_id='$product_id'");
					$this->db->query("DELETE 		FROM 	product_made_in 			WHERE product_id='$product_id'");
					$this->db->query("DELETE 		FROM 	product_bulk_price 			WHERE product_id='$product_id'");
					$this->db->query("DELETE 		FROM 	product_cross_sell 			WHERE product_id='$product_id' OR sell_id='$product_id'");
					$this->db->query("DELETE 		FROM 	product_price 				WHERE product_id='$product_id'");
					$this->db->query("DELETE 		FROM 	product_related 			WHERE product_id='$product_id' OR related_id='$product_id'");
					$this->db->query("DELETE 		FROM 	media_favorites 			WHERE file_id='$product_id' AND type='product'");
					$this->db->query("DELETE 		FROM 	certificate_property		WHERE product_id='$product_id'");
					$this->db->query("DELETE 		FROM 	certificates	 			WHERE product_id='$product_id'");
					//to delete supplier assigned product
					$query = "select * from product_supplier where product_id = '$product_id'";
					$get_query = mysql_query($query);
					if (mysql_num_rows($get_query)>0){
					$this->db->query("DELETE  FROM 	product_supplier WHERE product_id='$product_id'");
					}
					//
					return true;
				}
				else if($store_id>0)
				{
				$this->db->query("DELETE 		FROM 	product_store  				WHERE product_id='$product_id' and store_id=$store_id");
				$this->db->query("DELETE pa,ps 	FROM 	product_availabe_accessory pa,product_accessory_store ps WHERE pa.product_id='$product_id' and pa.id=ps.available_id and ps.store_id=$store_id");
				//exit;
				return true;	
				}
				else
				{
				return false;
				}
			}	



	}
	/**
	 *Listing products in  in combo 
	 *
	 * return array
	 */
	function productsList ($store_id=0,$product_id=0,$cid) {
	//===========
	/*$sql		= 	"SELECT  product_id FROM category_product WHERE category_id='$cid'";
	$rs2	=	$this->db->get_results($sql,ARRAY_A);
	for($j=0;$j<count($rs2);$j++) {
	$product_id=$rs2[$j]['product_id'];*/
	//=========
	
	if($product_id>0)
	{
			$sql		= 	"SELECT id, name FROM products WHERE id!=$product_id and active='Y'";
			if($store_id>0)
				$sql		= 	"SELECT p.id, p.name FROM products p,product_store ps WHERE p.id!=$product_id and p.active='Y' and p.id=ps.product_id and ps.store_id=$store_id";
			}
	else
	{
			$sql		= 	"SELECT id, name FROM products WHERE active='Y'";
			if($store_id>0)
				$sql		= 	"SELECT p.id, p.name FROM products p,product_store ps WHERE p.active='Y' and p.id=ps.product_id and ps.store_id=$store_id";
			}
		//echo $sql;
		$rs['id'] 	= 	$this->db->get_col($sql, 0);
		$rs['name'] = 	$this->db->get_col("", 1);
		return $rs;
	}
	
	function productsList1 ($store_id=0,$product_id=0,$cid) {
	
	$qry	=	"Select cp.product_id,p.id,p.name from  category_product cp,products p where cp.category_id=".$cid." and cp.product_id=p.id GROUP BY (p.name)";
	
	
		$rs['id'] 	= 	$this->db->get_col($qry, 1);
		$rs['name'] = 	$this->db->get_col("", 2);
		return $rs;
		//exit;
	}
	

	/**
	 * Insert Related products
	 *
	 * @param <id> $id
	 * @return Array
	 */

	function relatedInsert($id,$relatedproduct)
	{
		$array 		= 	array("product_id"=>$id,"related_id"=>$relatedproduct);
		$this->db->insert("product_related", $array);
	}

	function getRelatedProducts($id)
	{

		 $sql		= 	"SELECT `products`.`id`, `products`.`name` FROM `product_related` Inner Join `products` ON `products`.`id` = `product_related`.`related_id` WHERE `product_related`.`product_id` = '$id'";
		
		$rs['id'] 	= 	$this->db->get_col($sql, 0);
		$rs['name'] = 	$this->db->get_col("", 1);
		return $rs;

	}
	function CheckTheAvailablityStoreAccessory($product_id=0,$store_id=0)
		{
		$qry	=	"select * from product_availabe_accessory pa,product_accessory_group_categories pc,product_accessory_group pg where pa.product_id='$product_id' and pa.store_id='".$store_id."' and pa.category_id=pc.category_id and pc.group_id=pg.id and pg.parameter='N'";
		$res	=	$this->db->get_results($qry,ARRAY_A);
		if(count($res)>0)
			return	'YES';
		else
			return	'NO';
		}
	function storeGetDetails ($product_id,$param='',$store_id=0) {
		if($param=='')
		{
		$sql		= 	"SELECT id, name FROM store WHERE 1 ";
		$rs['id'] 	= 	$this->db->get_col($sql, 0);
		$rs['name'] = 	$this->db->get_col("", 1);
		}
		else
		{
		$sql		= 	"SELECT id, name FROM store ";
		if($store_id>0)
			$sql	.=	"WHERE id=$store_id";
		//echo $sql;
		//exit;
		
		$arr		=	$this->db->get_results($sql,ARRAY_A);
		if(count($arr)>0)
			{
			$i=0;
			foreach ($arr as $row)
				{
				$rs[$i]=$row;
				if($product_id>0)
					{
					$qry	=	"select * from product_availabe_accessory pa,product_accessory_group_categories pc,product_accessory_group pg where pa.product_id='$product_id' and pa.store_id='".$row['id']."' and pa.category_id=pc.category_id and pc.group_id=pg.id and pg.parameter='N'";
					$res		=	$this->db->get_results($qry,ARRAY_A);
					if(count($res)>0)
						$rs[$i]['status']	=	'YES';
					else
						$rs[$i]['status']	=	'NO';
					}
				
				$i++;
				}
			}
		}
		//exit;
		return $rs;
	}

	function getRelatedStore($id)
	{
		$sql		=	 "SELECT `store`.`id`,
							`store`.`name`
						FROM
							`store`
						Inner Join `product_store` ON `store`.`id` = `product_store`.`store_id`
						WHERE
							`product_store`.`product_id` =  '$id'";  

		$rs['id'] 	= 	$this->db->get_col($sql, 0);
		$rs['name'] = 	$this->db->get_col("", 1);
		return $rs;

	}

	/**
	 * Get delete related products 
	 *
	 * @param <id> $id
	 * @return Array
	 */
	function deleteRelated($id)
	{
		$this->db->query("DELETE FROM product_related WHERE product_id='$id'");


	}

	function insertRelated(&$req,$p_id)
	{
		extract($req);
		
		//print_r($hf1);
		//if($hf1)
		//{
			$rlitem		=	explode(",",$hf1);
			$cntr		=	count($rlitem);
			$this->deleteRelated($p_id);
			
			for($i=0;$i<$cntr;$i++)
			{
				$this->relatedInsert($p_id,$rlitem[$i]);
			}

		//}
		return true;
	}
	
	
function deleteStore_availableID($product_id)
	{
	$qry	=	"select id from product_availabe_accessory where product_id=".$product_id;
	$rs		=	$this->db->get_results($qry,ARRAY_A);
	for($i=0;$i<count($rs);$i++)
		{
		$this->db->query("DELETE FROM store_available_accessory WHERE available_id=".$rs[$i]['id']);
		}
	}

	function deleteRelatedStore($id)
	{
		$this->db->query("DELETE FROM product_store WHERE product_id='$id'");

	}
	
	
	//Assign the product to the store
	function relatedInsertStore($id,$relatedstore)
	{
		$qry	=	"select * from product_store where store_id=".$relatedstore." and product_id=".$id;
		$rs		=	$this->db->get_results($qry,ARRAY_A);
		if(count($rs)==0)
			{
			$array 		=	array("product_id"=>$id,"store_id"=>$relatedstore);
			$this->db->insert("product_store", $array);
			}
		//$this->SetAccessoriesToStore($id,$relatedstore);
	}
	function SetAccessoriesToStore($id,$relatedstore)
		{
		//Get the accessory categories of a product and assign to store
		$category	=	$this->GetProductAccessoryCategoryID($id);
		$this->AssignCategoryToStore($category,$relatedstore);
		
		//Getting the parameter 'Y' categories's available ID and saving in store available accessory
		//$availableID	=	$this->GetProductAccessoryCategoryID($id,'Y');
		//$this->AssignAvailableIDToStore($availableID,$relatedstore);
		
		//If the accessory category comes unders a group having the parameter is YES, the accessory group is also assigned to store
		//echo "<pre>";
		$this->AssignAccessoryCategoryGroupToStore($category,$relatedstore);
		//exit;
		//Get the accessories of the product and assign to store
		$accessory	=	$this->GetProductAccessoryID($id);
		$this->AssignAccessoryToStore($accessory,$relatedstore);
		}
	//function that add the product availbale accessory 'ID' to store
	function AssignAvailableIDToStore(&$availableID,$store_id)
		{
		for($i=0;$i<count($availableID);$i++)
			{
			$qry	=	"select store_id from store_available_accessory where store_id=".$store_id." and available_id=".$availableID[$i]['id'];
			$rs		=	$this->db->get_results($qry,ARRAY_A);
			if(!$rs)
				{
				$array 		=	array("available_id"=>$availableID[$i]['id'],"store_id"=>$store_id);
				$this->db->insert("store_available_accessory", $array);
				}
			}
		}
	
	//function get accessory category groups and assign to a store
	function AssignAccessoryCategoryGroupToStore(&$category,$store_id)
		{
		//print_r($category);
		for($i=0;$i<count($category);$i++)
			{
			$qry	=	"SELECT DISTINCT ag.id as id FROM product_accessory_group_categories ac, product_accessory_group ag where ag.parameter='Y' AND ag.id=ac.group_id AND ac.category_id=".$category[$i]['category_id'];
			$rs		=	$this->db->get_row($qry,ARRAY_A);
			if($rs)
				{
				$this->PutCategoryIDToStore($rs['id'],$store_id);//assigning the accessory category to a store
				}
			}
		
		}
	//function to Assign the category_id to store
	function PutCategoryIDToStore($group_id,$store_id)
		{
			$qry	=	"select store_id from store_accessory_group where store_id=".$store_id." and  group_id=".$group_id;
			$rs		=	$this->db->get_results($qry,ARRAY_A);
			if(!$rs)
				{
				$array 		=	array("group_id"=>$group_id,"store_id"=>$store_id);
				$this->db->insert("store_accessory_group", $array);
				}
		}
	//function insert category to a store
	function AssignCategoryToStore(&$category,$store_id)
		{
		for($i=0;$i<count($category);$i++)
			{
			$qry	=	"select store_id from store_category where store_id=".$store_id." and category_id=".$category[$i]['category_id'];
			$rs		=	$this->db->get_results($qry,ARRAY_A);
			if(!$rs)
				{
				$array 		=	array("category_id"=>$category[$i]['category_id'],"store_id"=>$store_id);
				$this->db->insert("store_category", $array);
				}
			}
			return true;
		}
	//function to Assign the accessories to a store
	function AssignAccessoryToStore(&$accessory,$store_id)
		{
		for($i=0;$i<count($accessory);$i++)
			{
			$qry	=	"select store_id from store_accessory where store_id=".$store_id." and accessory_id=".$accessory[$i]['accessory_id'];
			$rs		=	$this->db->get_results($qry,ARRAY_A);
			if(!$rs)
				{
				$array 		=	array("accessory_id"=>$accessory[$i]['accessory_id'],"store_id"=>$store_id);
				$this->db->insert("store_accessory", $array);
				}
			}
			return true;
		}
	//function to get the accessory category of a product
	function GetProductAccessoryCategoryID($product_id=0,$param='')
		{
		if($product_id>0)
			{
			if($param=='')
				$qry	=	"SELECT DISTINCT category_id FROM product_availabe_accessory WHERE product_id=".$product_id;
			else
				$qry	=	"	SELECT DISTINCT pa.id FROM product_availabe_accessory pa,product_accessory_group_categories pgc, product_accessory_group pg 
								WHERE pa.product_id=".$product_id." AND pa.category_id=pgc.category_id AND pgc.group_id=pg.id AND pg.parameter='Y'";
			$rs		=	$this->db->get_results($qry,ARRAY_A);
			if($rs)
				return $rs;
			}
		}
	//function to get the accessory of a product
	function GetProductAccessoryID($product_id=0)
		{
		if($product_id>0)
			{
			$qry	=	"SELECT DISTINCT accessory_id FROM product_availabe_accessory WHERE product_id=".$product_id;
			$rs		=	$this->db->get_results($qry,ARRAY_A);
			if($rs)
				return $rs;
			}
		}
	function deleteSettings($id)
	{
		$this->db->query("DELETE FROM product_settings WHERE id='$id'");
		$this->db->query("DELETE FROM product_settings_items WHERE settings_id='$id'");
		return true;
	}


	function getProductAll () {
		$sql		= 	"SELECT id, name FROM products WHERE 1 ";
		$rs['id'] 	= 	$this->db->get_col($sql, 0);
		$rs['name'] = 	$this->db->get_col("", 1);
		return $rs;
	}
	//Select all category  under parent_id
	function getParentCat($parent_id){
		$query	=	"SELECT * FROM category_product WHERE product_id=$parent_id";		
		$rs		=	$this->db->get_results($query,ARRAY_A);
		return $rs;
	}	
	// function used to return the product price
	function getProductPrice($productid)
	{
		$rs 	=	$this->db->get_row("SELECT price from products where id ='$productid'", ARRAY_A);
		$paddle_price	= $rs['price'];
		return $paddle_price;
	}

	//
	function insertSettingsProduct(&$req,$id)
	{
		extract($req);
		$this->deleteSettingsProducts($id);
		if($hf1)
		{
			$rlitem		=	explode(",",$hf1);
			$cntr		=	count($rlitem);
			
			for($i=$cntr-1;$i>=0;$i--)
			{
				
				$this->insertSettings_Products($id,$rlitem[$i]);
				//insert additional price for the selected product.
				$this->db->query("DELETE FROM product_price WHERE product_id='$rlitem[$i]'");///delete old records
				if($price_type>0)
				{
				if($prices>0)
					{
					if($is_percentage)
						$status_is_percentage	=	"Y";
					else
						$status_is_percentage	=	"N";
					
					$pricearray 			= 	array("type_id"=>$price_type,
														"product_id"=>$rlitem[$i],
														"price"=>$prices,
														"is_percentage"=>$status_is_percentage);
					$this->db->insert("product_price", $pricearray);
					}
				}
				//echo "Product_id:".$rlitem[$i]."<br>";
			}

		}


		//return true;
	}

	function insertSettings_Products($id,$prod_id)
	{
		$array 		= 	array("settings_id"=>$id,"product_id"=>$prod_id);
		
		$this->db->insert("product_settings_items", $array);

	}
	function deleteSettingsProducts($id)
	{
		$this->db->query("DELETE FROM product_settings_items WHERE settings_id='$id'");


	}


	//
	function getSettingsProduct($id)
	{
		$sql	=	"SELECT
						`products`.`id`,
						`products`.`name`
					FROM
						`product_settings_items`
					Inner Join `product_settings` ON `product_settings`.`id` = `product_settings_items`.`settings_id`
					Inner Join `products` ON `products`.`id` = `product_settings_items`.`product_id`
					WHERE

						`product_settings_items`.`settings_id` =  '$id'";  

		$rs['id'] 	= 	$this->db->get_col($sql, 0);
		$rs['name'] = 	$this->db->get_col("", 1);
		
		return $rs;

	}
	

	function addEditSettings (&$req) {
	
		extract($req);
		
		if(!trim($name)) {
			$message 			= 	"{$req['sId']} name required";
		} else {
			if ($req){
				if($right_display)
					$right_display_comment	=	"Y";
				else
					$right_display_comment	=	"N";
				
					
				$array 			= 	array("name"=>$name,"right_display"=>$right_display_comment,"display_order"=>$display_order);
			}
			if($id) {
				$array['id'] 	=	$id;
				$this->db->update("product_settings", $array, "id='$id'");
			} else {
				$this->db->insert("product_settings", $array);
				$id 			= 	$this->db->insert_id;
			}
			$this->db->query("DELETE FROM product_settings_store WHERE settings_id='$id'");
			        if($mainstore!="")
					{
					$array 		=	array("settings_id"=>$id,"store_id"=>$mainstore);
					$this->db->insert("product_settings_store", $array);
					}
					else{
					$this->db->query("DELETE FROM product_settings_store WHERE settings_id='$id' and store_id='0'");
				    }
					
					

					
					  for($i=0;$i<count($stores_id);$i++)
					{
					
					$this->settingsInsertStore($id,$stores_id[$i]);
					}

			return true;
		}
		return $message;
	}
function GetAccessorycategoryID($accessory_id)
	{
	$qry	=	"select category_id from category_accessory where accessory_id=".$accessory_id." LIMIT 0,1";
	$row 	= 	$this->db->get_row($qry,ARRAY_A);
	if($row['category_id']>0)
	return $row['category_id'];
	else
	return 0;
	}
function SetProductAccessory($product_id,$accessory_category,$store_id=0,$param='YES')
	{
	list($accessory_id,$category_id,$group_id)		=	split('_',$accessory_category);
	if($param=='YES')//for groups whose parameter is YES
		{
		if($product_id>0 && $category_id>0 && $accessory_id>0 && $group_id>0)
			{
				
				if(!$this->IsMonogramCategory($category_id))
					{
					$qry	=	"delete from product_availabe_accessory where category_id!='$category_id' and product_id='$product_id' and group_id='$group_id'";
					$this->db->query($qry);//delete other groups assigned to a product category
					}
				$qry		=	"select count(*) as number from product_availabe_accessory where category_id='$category_id' and product_id='$product_id' and accessory_id='$accessory_id' and group_id='$group_id'";
				$row 	= 	$this->db->get_row($qry,ARRAY_A);
				if($row['number']==0)
					{
					$array 		= 	array("product_id"=>$product_id,"category_id"=>$category_id,"accessory_id"=>$accessory_id,"group_id"=>$group_id);
					$this->db->insert("product_availabe_accessory", $array);
					//echo "ins_id: $ins_id<br>";
					}
			}
		}
	else//for groups whose parameter is NO
		{
		if($product_id>0 && $category_id>0 && $accessory_id>0 && $group_id>0)
			{
				if(!$this->IsMonogramCategory($category_id))
					{
					$qry	=	"DELETE pa,ps FROM product_availabe_accessory pa,product_accessory_store ps WHERE pa.category_id!='$category_id' and pa.product_id='$product_id' and pa.group_id='$group_id' and pa.id=ps.available_id";
					$this->db->query($qry);//delete other groups assigned to a product category
					}
				$qry		=	"select id from product_availabe_accessory where category_id='$category_id' and product_id='$product_id' and accessory_id='$accessory_id' and group_id='$group_id'";
				$row 	= 	$this->db->get_row($qry,ARRAY_A);
				if($row['id']>0)
					{
					$available_id=$row['id'];
					}
				else
					{
					$array 		= 	array("product_id"=>$product_id,"category_id"=>$category_id,"accessory_id"=>$accessory_id,"group_id"=>$group_id);
					$available_id=$this->db->insert("product_availabe_accessory", $array);
					}
				//echo "ins_id: $ins_id<br>";
				$array 		= 	array("available_id"=>$available_id,"store_id"=>$store_id);
				$this->db->insert("product_accessory_store", $array);
			}
		}
	
	//Assign Store to Accessory
	/*if($store_id>0)
		{
		$arr=array('0'=>$accessory_id);
		$this->AssignAccessoryToStore($arr,$store_id);
		$qry	=	"select store_id from store_accessory where store_id=".$store_id." and accessory_id=".$accessory_id;
		$rs		=	$this->db->get_results($qry,ARRAY_A);
		if(!$rs)
			{
			$array 		=	array("accessory_id"=>$accessory_id,"store_id"=>$store_id);
			$this->db->insert("store_accessory", $array);
			}
		}*/
	//echo $qry."<br>";
	
	}
	//save product accessory
	function save_product_accessory($id=0,&$accessory,$store_id=0,$from='')
	{
		$objAccessory						=	new Accessory();
		if($id>0)
		{
			//echo "remove";
			//get the primary key of the ID
			
			//print_r($accessory);
			//exit;
			if(count($accessory)>0)
			{
				foreach ($accessory as $accessory_id_new)
				{
					
					list($accessory_id,$category_id)		=	split('_',$accessory_id_new);
					
					//$category_id							=	$this->GetAccessorycategoryID($accessory_id);
					//echo $category_id."<br>";
					//echo $accessory_id."<br>";
					if($this->checkForRecord($id,$category_id,$accessory_id)===false)
						{//insert the selected product accessories
						$available_id=$this->InsertProductAccessorySelected($id,$category_id,$accessory_id);
						}
					else
						{
						$available_id=$this->GetAvailableID($id,$category_id,$accessory_id);
						}
					/*echo "id: $id <br>";
					echo "category_id: $category_id <br>";
					echo "accessory_id: $accessory_id <br>";
					echo "available_id: $available_id <br>";
					echo "store_id: $store_id <br>";
					echo "<br><br><br>";/**/
					//exit;
				//if($store_id>0)
					$this->insertStoreAvailablity($available_id,$store_id);
					
				}
			}
//exit;
		}
	}

function append_product_accessory($id=0,&$accessory,$store_id=0,$from='')
	{
		$objAccessory						=	new Accessory();
		if($id>0)
		{
			if(count($accessory)>0)
			{
				foreach ($accessory as $accessory_id_new)
				{
					//$accessory_details							=	$objAccessory->GetAccessory($accessory_id);
					list($accessory_id,$category_id)		=	split('_',$accessory_id_new);
					//$category_id							=	$this->GetAccessorycategoryID($accessory_id);
					if($this->checkForRecord($id,$category_id,$accessory_id)===false)
					{
					//insert the selected product accessories
					$available_id=$this->InsertProductAccessorySelected($id,$category_id,$accessory_id);
					}
					else
					{
					$available_id=$this->GetAvailableID($id,$category_id,$accessory_id);
					}
				//if($store_id>0)
					$this->insertStoreAvailablity($available_id,$store_id);
				}
			}
		}
		//exit;
	}
function insertStoreAvailablity($available_id,$store_id)
	{
	$array 		= 	array("available_id"=>$available_id,"store_id"=>$store_id);
	$this->db->insert("store_available_accessory", $array);
	}
	function RemoveStore_accessoryID($product_id,$store_id)
		{
		
		
		$qry	=	"Select pa.id from product_availabe_accessory pa,store_available_accessory sa where pa.product_id=".$product_id." and pa.id=sa.available_id and sa.store_id=".$store_id;
		$rs		=	$this->db->get_results($qry,ARRAY_A);
		
		for($i=0;$i<count($rs);$i++)
			{
			$this->db->query("DELETE FROM store_available_accessory WHERE available_id=".$rs[$i]['id']." and store_id=".$store_id);
			$this->db->query("DELETE FROM product_availabe_accessory WHERE id=".$rs[$i]['id']);
			}
		}
	
	function checkForRecord($product_id,$category_id,$accessory_id)
	{
		$qry		=	"select count(*) as number from product_availabe_accessory where category_id='$category_id' and product_id='$product_id' and accessory_id='$accessory_id'";
		if($product_id>0 && $category_id>0 && $accessory_id>0)
		{
			$row 	= 	$this->db->get_row($qry,ARRAY_A);
			if($row['number']>0)
			return true;
			else
			return false;
		}
		else
		{
			return false;
		}
	}
	function GetAvailableID($product_id,$category_id,$accessory_id)
	{
		$qry		=	"select id from product_availabe_accessory where category_id='$category_id' and product_id='$product_id' and accessory_id='$accessory_id'";
		if($product_id>0 && $category_id>0 && $accessory_id>0)
		{
			$row 	= 	$this->db->get_row($qry,ARRAY_A);
			return $row['id'];
		}
		else
		{
			return false;
		}


	}


	function InsertProductAccessorySelected($product_id=0,$category_id=0,$accessory_id=0)
	{
		if($product_id>0 && $category_id>0 && $accessory_id>0)
		{
			$array 		= 	array("product_id"=>$product_id,"category_id"=>$category_id,"accessory_id"=>$accessory_id);
			$inserted_id=$this->db->insert("product_availabe_accessory", $array);
		return $inserted_id;
		}
	}
	function RemoveAllAccessorySelected($product_id=0)
	{
		if($product_id>0)
		{
			#-- - for saving adjust price,weight,cart in session ----
		$MultArr	=	$_SESSION['MultiAccessory'];
		$qry	=	"select * from product_availabe_accessory WHERE product_id='$product_id'";
		$rs		=	mysql_query($qry);
		while($row=mysql_fetch_array($rs))
		{
			$acc_id	=	$row["accessory_id"];
			$price	=	$row["adjust_price"];
			$weight	=	$row["adjust_weight"];
			$cart	=	$row["cart_name"];
			$gid =$row["group_id"];
			$MultArr[]	=	array($acc_id,$product_id,$price,$weight,$cart,$gid);
		}
		if($MultArr)
		{
			$_SESSION['MultiAccessory']		=	$MultArr;
		}
		#--  for saving adjust price,weight,cart in session ----
		
		
		//$this->deleteStore_availableID($product_id);
		//$this->db->query("DELETE pa,ps FROM product_availabe_accessory pa,product_accessory_store ps WHERE pa.product_id='$product_id' and pa.id=ps.available_id and pa.group_id='$gid'");
		//$this->db->query("DELETE FROM product_availabe_accessory WHERE product_id='$product_id' and group_id='$gid'");
		  $this->db->query("DELETE pa,ps FROM product_availabe_accessory pa,product_accessory_store ps WHERE pa.product_id='$product_id' and pa.id=ps.available_id");
		  $this->db->query("DELETE FROM product_availabe_accessory WHERE product_id='$product_id'");
		}
		
	}
	
	
	
	
	
	
	function GetAllSelectedAccessory($product_id=0)
	{
		if($product_id>0)
		{
			$qry		=	"SELECT concat(paa.accessory_id,'_',paa.category_id,'_',paa.group_id) as accessory_id FROM `product_availabe_accessory` paa
												WHERE 
												paa.product_id =  $product_id ";
			$rs['accessory_id'] 	= 	$this->db->get_col($qry, 0);
			//$rs['accessory_id']=array(2,3,4,5);
			return $rs;
		}
		else
			{
			$qry		=	"SELECT concat(paa.accessory_id,'_',paa.category_id,'_',paa.group_id) as accessory_id FROM `store_accessory_default` paa
												";
			$rs['accessory_id'] 	= 	$this->db->get_col($qry, 0);
			//$rs['accessory_id']=array(2,3,4,5);
			return $rs;
			}
	}
	
	//added by shinu for store accessory 26-05-07
	function GetAllSelectedAccessoryforStore($product_id,$store)
	{
		if($product_id>0)
		{
			$qry		=	"SELECT concat(paa.accessory_id,'_',paa.category_id,'_',paa.group_id) as accessory_id FROM `product_availabe_accessory` paa,product_accessory_store pas
												WHERE 
												paa.product_id =  $product_id and paa.id=pas.available_id and pas.store_id=$store";
			$rs['accessory_id'] 	= 	$this->db->get_col($qry, 0);
			//$rs['accessory_id']=array(2,3,4,5);
			return $rs;
		}
		else
			{
			$qry		=	"SELECT concat(paa.accessory_id,'_',paa.category_id,'_',paa.group_id) as accessory_id FROM `store_accessory_default` paa
												";
			$rs['accessory_id'] 	= 	$this->db->get_col($qry, 0);
			//$rs['accessory_id']=array(2,3,4,5);
			return $rs;
			}
	}
	
	
//Mass update the product Details	
function massUpdate($req,$store_id=0)
	{
	extract($req); 
	//echo "from store";
	//echo "$store_id";
	//echo "$manage";
	//exit;
	//print_r($req);exit;
	#exit;
	$field_arr = $req;


		if(count($product_id)>0)
		{

			$array 				= 	array();
			//=============
			if(trim($field_arr["mass_field1"]))
		{
			$newarray=array("brand_id"=>$brand_id);
			$array=array_merge($array,$newarray);
			unset($field_arr['mass_field1']);
		}
		else
		{ 
			unset($field_arr['brand_id']); 
		}
		
		
		
			//=============
			
			
			
			/*if(trim($brand_id))
			{
				if($brand_id>0)
				{
					$newarray		=	array("brand_id"	=>	$brand_id);
					$array			=	array_merge($array,$newarray);
				}
			}*/
			
			if(trim($thickness))
			{
				$newarray		=	array("thickness"	=>	$thickness);
				$array			=	array_merge($array,$newarray);
			}
			
			//if(trim($weight))
			if(trim($field_arr["mass_field3"]))
			{
				$newarray		=	array("weight"		=>	$weight);
				$array			=	array_merge($array,$newarray);
				unset($field_arr['mass_field3']); 
			}
			else
			{ 
				unset($field_arr['weight']); 
			}
			
			if(trim($field_arr["mass_field13"]))
			{
				$newarray		=	array("display_name"		=>	$display_name);
				$array			=	array_merge($array,$newarray);
				unset($field_arr['mass_field13']); 
			}
			else
			{ 
				unset($field_arr['display_name']); 
			}
			
			
			
			/*if(trim($field_arr["mass_field4"]))
			{
				$newarray		=	array("price_type"		=>	$price_type);
				$array			=	array_merge($array,$newarray);
				unset($field_arr['price_type']); 
			}
			else
			{ 
				unset($field_arr['price_type']); 
			}*/
			
			
			//if(trim($description))
			if(trim($field_arr["mass_field11"]))
			{
				$newarray		=	array("description"	=>	$description);
				$array			=	array_merge($array,$newarray);
			}else{
			
			   unset($field_arr['description']); 
			   }
			
			if(trim($active))
			//if(trim($field_arr["mass_field7"]))
			{
				$newarray		=	array("active"		=>	"Y");
				$array			=	array_merge($array,$newarray);
				//unset($field_arr['mass_field7']);
			}
			else
			{
				$newarray		=	array("active"		=>	"N");
				$array			=	array_merge($array,$newarray);
				//unset($field_arr['active']);
			}
			
			
			//if(trim($display_gross))
			if(trim($field_arr["mass_field8"]))
			{
				$newarray		=	array("display_gross"		=>	"Y");
				$array			=	array_merge($array,$newarray);
			}
			else
			{
				$newarray		=	array("display_gross"		=>	"N");
				$array			=	array_merge($array,$newarray);
			}
			
			
			//if(trim($display_related))
			if(trim($field_arr["mass_field5"]))
			{
				$newarray		=	array("display_related"		=>	"Y");
				$array			=	array_merge($array,$newarray);
			}
			else
			{
				$newarray		=	array("display_related"		=>	"N");
				$array			=	array_merge($array,$newarray);
			}
			
			//if(trim($page_title))
			if(trim($field_arr["mass_field20"]))
			{
				$newarray		=	array("page_title"	=>	$page_title);
				$array			=	array_merge($array,$newarray);
			}else{
				unset($field_arr['page_title']); 
			}
			
			//if(trim($meta_description))
			if(trim($field_arr["mass_field21"]))
			{
				$newarray		=	array("meta_description"	=>	$meta_description);
				$array			=	array_merge($array,$newarray);
			}else{
				unset($field_arr['meta_description']); 
			}
			
			//if(trim($meta_keywords))
			if(trim($field_arr["mass_field22"]))
			{
				$newarray		=	array("meta_keywords"	=>	$meta_keywords);
				$array			=	array_merge($array,$newarray);
			}else{
				unset($field_arr['meta_keywords']); 
			}
			
			// manage exclude accessory mass_field14
			if( trim($field_arr["mass_field15"]) )
			{
			    $newarray		=	array("exclude_group"		=>	implode ( "," , $ex_accessory ) );
				$array			=	array_merge($array,$newarray);
			}


			
			/*if(trim($hide_in_mainstore))
			{
				$newarray		=	array("hide_in_mainstore"		=>	"Y");
				$array			=	array_merge($array,$newarray);
			}
			else
			{
				$newarray		=	array("hide_in_mainstore"		=>	"N");
				$array			=	array_merge($array,$newarray);
			}*/
			foreach ($product_id as $id)
				{
				
					//if(trim($price))
					if(trim($field_arr["mass_field2"]))
					{
					
						$newarray		=	array("price"		=>	$price);
						$array			=	array_merge($array,$newarray);
						unset($field_arr['mass_field2']);
					}
					else// calculate price from base price of category
					{
					$qry	=	"SELECT mc.base_price FROM master_category mc, category_product cp WHERE mc.category_id=cp.category_id and cp.product_id=".$id;
					$rs		=	$this->db->get_results($qry,ARRAY_A);
					if($rs)
						{
						$cat_price=0;
						for($i=0;$i<count($rs);$i++)
							{
								if($rs[$i]['base_price']>$cat_price)
									$cat_price=$rs[$i]['base_price'];
							}
						if($cat_price>0)
							{
							$newarray		=	array("price"		=>	$cat_price);
							$array			=	array_merge($array,$newarray);
							unset($field_arr['price']); 
							}
						}
					}
					
					//if managing from stores
					if($manage=='manage' && $id>0)
						{
						$old_product_id='';
						$qry	=	"SELECT * from product_store ps where ps.product_id=$id and store_id!=$store_id";
						$res	=	$this->db->get_results($qry,ARRAY_A);
						if($res)//The product is assigned to other stores
							{
							//echo "The product is assigned to other stores"."<br>";
							$this->db->query("DELETE FROM product_store WHERE product_id='$id' and store_id=$store_id");
							$old_product_id=$id;//for getting the old product images to new product
							$id=$this->creteNewProductFrom($id,$array,$Append);
							}
						/*else// the product is assigned to this store only
							{
							//echo "the product is assigned to this store only"."<br>";
							$this->db->query("DELETE FROM product_store WHERE product_id='$id' and store_id=$store_id");
							$this->RemoveAllAccessorySelected($id);//remove the old accessories
							}*/
						
						}
					//if managing from stores
					//echo "zone_id".$zone_id;
					if($zone_id>0){
					  if(trim($field_arr["mass_field9"]))
					
						{
						$this->db->query("DELETE FROM product_made_in WHERE product_id='$id'");
						$ins	=	array("zone_id"=>$zone_id,"product_id"=>$id);
						$this->db->insert("product_made_in", $ins);
						}else{
						unset($field_arr['mass_field9']);
						}
						}
					//updating categories
					if(count($category)>0)//the category will be updated only if any category is selected
						{
						 if(trim($field_arr["mass_field10"]))
					
						{
						
						if($Append!='Y')
						{
						
						
						$this->db->query("DELETE FROM category_product WHERE product_id='$id'");
						}
						foreach ($category as $cat_id)
							{
								$ins	=	array("category_id"=>$cat_id,"product_id"=>$id);
								$this->db->insert("category_product", $ins);
							}
							}else{
						    unset($field_arr['mass_field10']);
						}
						}
						
						
						
						
						
					//set price if it is entered	
					//echo "Second Updation: $id<br>";
					//print_r($array);
					$this->db->update("products", $array, "id='$id'");
					//print_r($this->ProductGet($new_product_id));
					//echo "<br>";
					///*******geting the additional pricing structure for the selected products.***////////
					//if($price_type>0)
					if(trim($field_arr["mass_field4"]))
						{
						//if($prices)
						if(trim($field_arr["mass_field6"]))
						
							{
							//if($is_percentage)
							if(trim($field_arr["mass_field13"]))
								$status_is_percentage="Y";
							else
								$status_is_percentage="N";
							$pricearray		=	array("type_id"=>$price_type,"price"=>$prices,"is_percentage"=>$status_is_percentage,"product_id"=>$id);
							$this->db->query("DELETE FROM product_price WHERE product_id='$id'");
							$this->db->insert("product_price", $pricearray);
							
							}else{
							 unset($field_arr['mass_field6']);
							 }
							
						}else{
							 unset($field_arr['mass_field4']);
							 }
					//************************************************************////////
					//mass update the accessory available for the selected products
					/*******************************************************************************************/
					
					//======================= This change is needed for mass updation for Accessory on 14-08-07==================
					//----------------------------------------------------------------------
					
					for($i=1;$i<$field_arr["cnt"];$i++){
						    if($Append!='Y' && trim($field_arr["mass_field12$i"]))
							{
							/*echo "came";
							echo $mass_field12;
								echo $new_group_id;*/
								
								//if($req['new_group_id']==""){

								
								$this->RemoveAllAccessorySelected($id);
								//}
								
							}
					}
						//======================= This change is needed for mass updation for Accessory on 14-08-07==================
					    //----------------------------------------------------------------------
			
						/****************** Assigning to product available accessory and the new table product accessory store *****************************/
					 if(trim($field_arr["mass_field14"])){
					
					
						if($Append_product_store!='Y'&& $Remove=='Y')
						{
						  for($i=0;$i<count($stores_id);$i++)
						  {
						  $this->deleteProductRelatedStore($id,$stores_id[$i]);
						//  $this->db->query("DELETE FROM product_store WHERE product_id='$id' and store_id='$stores_id[$i]'");
						  }
							//$this->deleteRelatedStore($id);
							//$this->RemoveAllAccessorySelected($id);
							
							/*$qry="select * from store where active='Y'";
							$row 	= 	$this->db->get_results($qry,ARRAY_A);
							for($i=0;$i<count($row);$i++) {
					        
			                 $sid= $row[$i]['id'];
							 
							 
						     $this->relatedInsertStore($id,$sid);
							}*/
							
						}else{
						
						     for($i=0;$i<count($stores_id);$i++)
							 {
							  $this->relatedInsertStore($id,$stores_id[$i]);
						     }
						}
					}
						
					for($i=1;$i<$field_arr["cnt"];$i++){
					
					 if(trim($field_arr["mass_field12$i"]))
					 {
										 
						/*if($Append!='Y')
						{
						$this->RemoveAllAccessorySelected($id);
						}
						/****************** Assigning to product available accessory and the new table product accessory store *****************************/
					
					/*if($Append_product_store!='Y')
					
						{
						$this->deleteRelatedStore($id);
						$this->RemoveAllAccessorySelected($id);
						}*/
						
						
						
					//store_id  == 0; main store
					if(count($accessory['All'])>0)
							{
							foreach ($accessory['All'] as $access_id)
								{
								$this->SetProductAccessory($id,$access_id,0,'YES');//$this->SetProductAccessory($id,$access_id,0);
								}
							}
					if(count($accessory[0])>0)
							
							{
							foreach ($accessory[0] as $access_id)
								{
								$this->SetProductAccessory($id,$access_id,0,'NO');
								}
							}
							 if(trim($field_arr["mass_field14"])){
							 
					for($i=0;$i<count($stores_id);$i++)
							{
							$this->relatedInsertStore($id,$stores_id[$i]);
							
							   if(count($accessory[$stores_id[$i]])>0)
								{
								
								foreach ($accessory[$stores_id[$i]] as $access_id)
									{
									$this->SetProductAccessory($id,$access_id,$stores_id[$i],'NO');
									}
								}
								
							}
							}else{
								unset($field_arr['mass_field14']);
								}
					}	
					else{
					 unset($field_arr['mass_field12$i']);
					 }
					 
					} 
					
					
					
					
					
					/****************** Assigning to product available accessory and the new table product accessory store *****************************/
					/*******************************************************************************************/
					
# ------------- assign product to store mass update(08-05-07 -------------------------#
	//STORE TO PRODUCT
		/*if($Append_product_store!='Y')
		{
		$this->deleteRelatedStore($id);
		$this->RemoveAllAccessorySelected($id);
		}*/
		//store_id  == 0; main store
		/*if(count($accessory[0])>0)
				{
				foreach ($accessory[0] as $access_id)
					{
					$this->SetProductAccessory($id,$access_id,0);
					}
				}*/
		//All for registering the parameter category in 
		/*if(count($accessory['All'])>0)
			{
			foreach ($accessory['All'] as $access_id)
				{
				$this->SetProductAccessory($id,$access_id,0);
				}
			}*/
		/*for($i=0;$i<count($stores_id);$i++)
			{
			$this->relatedInsertStore($id,$stores_id[$i]);
			//All
			if(count($accessory['All'])>0)
				{
				foreach ($accessory['All'] as $access_id)
					{
					$this->SetProductAccessory($id,$access_id,$stores_id[$i]);
					}

				}
			
			//with store id
			//echo "stores_id: $stores_id[$i]<br>";
			if(count($accessory[$stores_id[$i]])>0)
				{
				foreach ($accessory[$stores_id[$i]] as $access_id)
					{
					$this->SetProductAccessory($id,$access_id,$stores_id[$i]);
					}
				}
			}*/
# ------------- assign product to store mass update(08-05-07) -------------------------#					
					
					
					
					
				}
			
			}//if(count($product_id)>1)
	//exit;
	}
	

//=========

//this is for deleting selected product from  selected Stores on 23-0807

function deleteProductRelatedStore($id,$stores_id)
{
$this->db->query("DELETE FROM product_store WHERE product_id='$id' and store_id='$stores_id'");
}

//user side functions

//function to get featurded item title

function GetFeaturedTitle()
	{
		$qry		=	"SELECT * FROM `product_settings` where right_display='Y'";
		$row 		= 	$this->db->get_row($qry,ARRAY_A);
		return $row['name'];
	}	
	
	//query modified by robin 31-12-2007
	# query modified by Jeffy on 14 may 08. Added 'ORDER BY pi.id DESC'
function GetFeaturedItems($store_name='')
	{
	global $store_id;
	$rs2		= 	array();
	$qry		=	"SELECT * FROM `product_settings` where right_display='Y'";
	$row 		= 	$this->db->get_row($qry,ARRAY_A);
	$qry1		=	"SELECT p.*,pc.category_id FROM ";
	if($store_name)
	$qry1		.=	" store as st, 
						product_store as ps , ";
						
	$qry1		.=	" product_settings_items as pi, 
						products as p,category_product pc					
					where ";
	if($store_name)				
	$qry1		.=		" p.id=ps.product_id and
						ps.store_id=st.id and
						st.name='".$store_name."' and ";
	
		$qry1 .= " p.id=pi.product_id and p.id=pc.product_id and
				   p.active='Y' and 
				   pi.settings_id=".$row['id']."  
				   ORDER BY pi.id DESC limit 0,6";
					echo $qry1;
					
					
	$rs1		=	$this->db->get_results($qry1,ARRAY_A);
	/*if(count($rs1)<6)
		{
		$i		=	count($rs1);
		$limit	=	6-$i;
		$qry3	=	"SELECT product_id as id from product_settings_items pi where pi.settings_id=".$row['id']."";
		$rs3	=	$this->db->get_col($qry3,0);
		$data	=	"";
		if(count($rs3)>0)
		{
		$data	=	implode(",",$rs3);
		}
		$qry2	=	"SELECT p.* FROM products p where p.active='Y' AND id NOT IN (".$data.") ORDER BY p.name";
		$rs2	=	$this->db->get_results($qry2,ARRAY_A);
		if(count($rs2)>0)
		   $rs1	=	array_merge($rs1,$rs2);
		}*/
		if($store_id>0)
		{
			for($i=0;$i<count($rs1);$i++)
			{
				$rsStoe=$this->findStoreProductArray($store_id,$rs1[$i][id]);
				if(count($rsStoe)>0)
				{
					$rs1[$i]=$rsStoe[0];
				}
			
			}
		}
			
			//print_r($rs1);exit;
	return $rs1;
	}
	
	function findStoreProductArray($store_id,$id)
	{
		$qry= "Select p.* From products p Inner Join product_store s ON p.id = s.product_id
				Where
					p.parent_id = '$id' AND
					s.store_id = '$store_id'
				";
				
		$rs=$this->db->get_results($qry,ARRAY_A);
		return $rs;
	}
	
function CheckToDisplayGrossSell($product_id=0)
	{
		$qry		=	"select count(*) as number from products  where id='$product_id' and display_gross='Y'";
		$row 		= 	$this->db->get_row($qry,ARRAY_A);
		if($row['number']>0)
		return true;
		else
		return false;

	}
function GetRightColumnItems($store_name='',$limit,$product_id='')
	{
	
	//--------------if store is available------------------------
					
	if($store_name==""){
	
	
			 $qry1		=	"	SELECT settings_id FROM product_settings_store  where store_id='0'";
					$res	=	mysql_query($qry1);
					//$project_ids="'0'";
					while($row=mysql_fetch_array($res))
					{	
						$id	=	$row["settings_id"];
						if($id!="")
						{
							$flag++;
							 $project_ids=$project_ids. "  '".$id."',";
							 
						}
					}
					
					
					$project_ids=substr($project_ids,0,-1);
					
					if($project_ids!="")
					{
					
					
					
					
    $arr1		=	array();
	$rs2		= 	array();
	$qry		=	"	SELECT * FROM 	product_settings  
						WHERE 			id IN ($project_ids) and right_display='Y'  
						ORDER BY 		display_order";
	//echo "$qry"."<Br>";
	$row 		= 	$this->db->get_results($qry,ARRAY_A);
	//print_r($row );
	for($i=0;$i<count($row);$i++)
		{
		$qry1		=	"SELECT p.* FROM ";
		if($store_name)
		$qry1		.=	" store as st, 
							product_store as ps , ";
							
		$qry1		.=	" product_settings_items as pi, 
							products as p					
						where ";
		if($store_name)				
		$qry1		.=		" p.id=ps.product_id and
							ps.store_id=st.id and
							st.name='".$store_name."' and ";
							
		$qry1		.=		" p.id=pi.product_id and 
							p.active='Y' and 
							pi.settings_id=".$row[$i]['id']."  AND
							p.name!=''";
							
		if(empty($store_name))	
		$qry1		.=	"  		AND p.hide_in_mainstore!='Y' ";	
		$qry1		.=		"ORDER BY rand() limit 0,$limit";
						//echo $qry1;
						//exit;
		$rs1				=	$this->db->get_results($qry1,ARRAY_A);
		$rs2[$i]['head']	=	$row[$i];
		$rs2[$i]['data']	=	$rs1;
		}
		$insert=count($rs2);
	/*********************** Dislay Cross Sell Items **************************************/
	if(($product_id>0))
		{
		if($this->CheckToDisplayGrossSell($product_id)==true)
			{
			$rs3	=	$this->GetCrossSellProduct($product_id,$store_name);
			if(count($rs3)>0)
				{
				$j	=	0;
				foreach ($rs3 as $row3)
					{
					if($row3['sell_id']!=$product_id)
						{
						$arr1[$j]=$this->ProductGet($row3['sell_id']);
						$j++;
						}
					}
				$arr2=array("id" => "G","name" => "Cross Sell","display_order" =>"0","right_display" => "Y");
				$arr[0]['head']	=	$arr2;
				$arr[0]['data']	=	$arr1;
				$k				=	0;
				while ($rs2[$k]['head']!="")
					{
					$arr[$k+1]['head']	=	$rs2[$k]['head'];
					$arr[$k+1]['data']	=	$rs2[$k]['data'];
					$k++;
					}
				$rs2 = $arr;
				}
			}
			
			}
			
			/*		integrate best selling products in the right column*/
			$status		=	$this->GetBestSellingProducts($limit,$store_name);
			if($status['status']==true)
				{
				$j	=	0;
				$arr4	=	array();
				foreach ($status['data'] as $row4)
					{
					$arr4[$j]=$this->ProductGet($row4['product_id']);
					$j++;
					}
				
				$arr5=array("id" => "B","name" => "Best Selling Product","display_order" =>"0","right_display" => "Y");
				$rs2[$insert]['head']	=	$arr5;
				$rs2[$insert]['data']	=	$arr4;
				$insert++;
				}
	
	                  return $rs2;				
					
					
					}else{
					
					
					$arr1		=	array();
	$rs2		= 	array();
	$qry		=	"	SELECT * FROM 	product_settings  
						WHERE 			right_display='N'  
						ORDER BY 		display_order";
	//echo "$qry"."<Br>";
	$row 		= 	$this->db->get_results($qry,ARRAY_A);
	//print_r($row );
	for($i=0;$i<count($row);$i++)
		{
		$qry1		=	"SELECT p.* FROM ";
		if($store_name)
		$qry1		.=	" store as st, 
							product_store as ps , ";
							
		$qry1		.=	" product_settings_items as pi, 
							products as p					
						where ";
		if($store_name)				
		$qry1		.=		" p.id=ps.product_id and
							ps.store_id=st.id and
							st.name='".$store_name."' and ";
							
		$qry1		.=		" p.id=pi.product_id and 
							p.active='Y' and 
							pi.settings_id=".$row[$i]['id']."  AND
							p.name!=''";
							
		if(empty($store_name))	
		$qry1		.=	"  		AND p.hide_in_mainstore!='Y' ";	
		$qry1		.=		"ORDER BY rand() limit 0,$limit";
						//echo $qry1;
						//exit;
		$rs1				=	$this->db->get_results($qry1,ARRAY_A);
		$rs2[$i]['head']	=	$row[$i];
		$rs2[$i]['data']	=	$rs1;
		}
		$insert=count($rs2);
	/*********************** Dislay Cross Sell Items **************************************/
	if(($product_id>0))
		{
		if($this->CheckToDisplayGrossSell($product_id)==true)
			{
			$rs3	=	$this->GetCrossSellProduct($product_id,$store_name);
			if(count($rs3)>0)
				{
				$j	=	0;
				foreach ($rs3 as $row3)
					{
					if($row3['sell_id']!=$product_id)
						{
						$arr1[$j]=$this->ProductGet($row3['sell_id']);
						$j++;
						}
					}
				$arr2=array("id" => "G","name" => "Cross Sell","display_order" =>"0","right_display" => "Y");
				$arr[0]['head']	=	$arr2;
				$arr[0]['data']	=	$arr1;
				$k				=	0;
				while ($rs2[$k]['head']!="")
					{
					$arr[$k+1]['head']	=	$rs2[$k]['head'];
					$arr[$k+1]['data']	=	$rs2[$k]['data'];
					$k++;
					}
				$rs2 = $arr;
				}
			}
			
			}
			
			/*		integrate best selling products in the right column*/
			$status		=	$this->GetBestSellingProducts($limit,$store_name);
			if($status['status']==true)
				{
				$j	=	0;
				$arr4	=	array();
				foreach ($status['data'] as $row4)
					{
					$arr4[$j]=$this->ProductGet($row4['product_id']);
					$j++;
					}
				
				$arr5=array("id" => "B","name" => "Best Selling Product","display_order" =>"0","right_display" => "Y");
				$rs2[$insert]['head']	=	$arr5;
				$rs2[$insert]['data']	=	$arr4;
				$insert++;
				}
	
	                  return $rs2;	
					
					
					
					
					}
			
	
	}
	//--------------if store is available------------------------
		if($store_name!=""){
		$qry		=	"	SELECT * FROM 	store where name='$store_name'";
		$row 		= 	$this->db->get_row($qry,ARRAY_A);
		}
		
		 $sid=$row['id'];
			 $qry1		=	"	SELECT settings_id FROM product_settings_store  where store_id='$sid'";
			$res	=	mysql_query($qry1);				
	
	//$project_ids="'0'";
	while($row=mysql_fetch_array($res))
	{	
		$id	=	$row["settings_id"];
		if($id!="")
		{
			$flag++;
			 $project_ids=$project_ids. "  '".$id."',";
			 
		}
	}
			
	$project_ids=substr($project_ids,0,-1);
	
	$arr1		=	array();
	$rs2		= 	array();
	$qry		=	"	SELECT * FROM 	product_settings  
						WHERE 			id IN ($project_ids) and right_display='Y'  
						ORDER BY 		display_order";
	//echo "$qry"."<Br>";
	$row 		= 	$this->db->get_results($qry,ARRAY_A);
	//print_r($row );
	for($i=0;$i<count($row);$i++)
		{
		$qry1		=	"SELECT p.* FROM ";
		if($store_name)
		$qry1		.=	" store as st, 
							product_store as ps , ";
							
		$qry1		.=	" product_settings_items as pi, 
							products as p					
						where ";
		if($store_name)				
		$qry1		.=		" p.id=ps.product_id and
							ps.store_id=st.id and
							st.name='".$store_name."' and ";
							
		$qry1		.=		" p.id=pi.product_id and 
							p.active='Y' and 
							pi.settings_id=".$row[$i]['id']."  AND
							p.name!=''";
							
		if(empty($store_name))	
		$qry1		.=	"  		AND p.hide_in_mainstore!='Y' ";	
		$qry1		.=		"ORDER BY rand() limit 0,$limit";
						//echo $qry1;
						//exit;
		$rs1				=	$this->db->get_results($qry1,ARRAY_A);
		$rs2[$i]['head']	=	$row[$i];
		$rs2[$i]['data']	=	$rs1;
		}
		$insert=count($rs2);
	/*********************** Dislay Cross Sell Items **************************************/
	if(($product_id>0))
		{
		if($this->CheckToDisplayGrossSell($product_id)==true)
			{
			$rs3	=	$this->GetCrossSellProduct($product_id,$store_name);
			if(count($rs3)>0)
				{
				$j	=	0;
				foreach ($rs3 as $row3)
					{
					if($row3['sell_id']!=$product_id)
						{
						$arr1[$j]=$this->ProductGet($row3['sell_id']);
						$j++;
						}
					}
				$arr2=array("id" => "G","name" => "Cross Sell","display_order" =>"0","right_display" => "Y");
				$arr[0]['head']	=	$arr2;
				$arr[0]['data']	=	$arr1;
				$k				=	0;
				while ($rs2[$k]['head']!="")
					{
					$arr[$k+1]['head']	=	$rs2[$k]['head'];
					$arr[$k+1]['data']	=	$rs2[$k]['data'];
					$k++;
					}
				$rs2 = $arr;
				}
			}
			//install related products
			/*
			$status=$this->CheckToDisplayRelatedProduct($product_id);
			//print_r($status);
			//exit;
			if($status['status']==true)
				{
				$j	=	0;
				$arr4	=	array();
				foreach ($status['data'] as $row4)
					{
					//echo $row4['related_id'];
					$arr4[$j]=$this->ProductGet($row4['related_id']);
					$j++;
					}
				$arr5=array("id" => "R","name" => "Related Products","display_order" =>"0","right_display" => "Y");
				
				$rs2[$insert]['head']	=	$arr5;
				$rs2[$insert]['data']	=	$arr4;
				$insert++;
				}
				*/
			}
			
			/*		integrate best selling products in the right column*/
			$status		=	$this->GetBestSellingProducts($limit,$store_name);
			if($status['status']==true)
				{
				$j	=	0;
				$arr4	=	array();
				foreach ($status['data'] as $row4)
					{
					$arr4[$j]=$this->ProductGet($row4['product_id']);
					$j++;
					}
				
				$arr5=array("id" => "B","name" => "Best Selling Product","display_order" =>"0","right_display" => "Y");
				$rs2[$insert]['head']	=	$arr5;
				$rs2[$insert]['data']	=	$arr4;
				$insert++;
				}
	
	return $rs2;
	
	//}
	}
	function GetRelatedProduct($product_id,$store_name='')
		{
		$status=$this->CheckToDisplayRelatedProduct($product_id,$store_name,2);
		if($status['status']==true)
			{
			$j	=	0;
			$arr4	=	array();
			foreach ($status['data'] as $row4)
				{
				$arr4[$j]=$this->ProductGet($row4['related_id']);
				$j++;
				}
			if($status['data'])
					{
					$arr5=array("id" => "R","name" => "Related Products","display_order" =>"0","right_display" => "Y");
					$rs2['head']	=	$arr5;
					$rs2['data']	=	$arr4;
					}
			}
		return $rs2;
		}
	function paddlePrice($values)
	{
		if( $values['product_id'] ) {
			$product_id	=	$values['product_id'];
		}
		if( $values['qty'] ) {
			$qty		=	$values['qty'];
		}
		$rs 	=	$this->db->get_row("SELECT price from products where id ='$product_id'", ARRAY_A);
		$price		= $rs['price'];
		return $price*$qty;
	
	}
	
	function calulate_price($values) {
		
		$objPrice		=	new Price();
		$objAccessory	=	new Accessory();
		$objCombination	=	new Combination();
		
		//echo $values['qty'];
		//print_r($values);
		$total_price	=	"";
		$mono_status	=	false;
		//from ajax and to find price of the product while adding the product to cart.
		if( $values['from'] ) {
			$from		=	$values['from'];
		}
		if( $values['product_id'] ) {
			$product_id	=	$values['product_id'];
		}
		if( $values['storename'] ) {
			$store		=	$values['storename'];
		}
		if( $values['qty'] ) {
			$qty		=	$values['qty'];
		}
		if( $values['height'] ) {
			$height		=	$values['height'];
		}else{
			$height=1;
		}
		if( $values['width'] ) {
			$width		=	$values['width'];
		}else{
			$width=1;
		}
		
		
		//exit;
		//calculate bulk price
		/*$ar		=	$objPrice->GetbulkPriceForProduct($product_id,$qty);
		if($ar['status']===true)
			{
			$product_price	=	$ar['amount'];
			}
		else
			{*/
			$product_price	=	$objPrice->GetPriceOfProduct($product_id,0,$qty);
			 $product_price=$product_price*$width*$height;
			//exit;
			/*}*/
			//calculate monogram pricing
		if($this->IsMonogram($product_id))
		{
			$mono_status	=	true;
			//echo "mono_status: true <br>";
			}
		//find out all the categories of the product in the store
		$i=0;
		$price=0;
		$newVal	=	array();
		if($values['access']) {
		foreach ($values['access'] as $access) {
			if(is_array($access)){
					$newVal		=	$access;
				}else{
					$newVal[0]	=	$access;						
				}
				foreach ($newVal as $val2) {
					if($val2 > 0) {
					
						/*$accessory		=	$objAccessory->GetProductsAccessory($access,$product_id);
						if($accessory=="" || $accessory['adjust_price']==NULL)
						{
							$accessory		=	$objAccessory->GetAccessory($access);
						}*/
						$accessory		=	$objAccessory->GetAccessory($val2);
						//print_r($accessory);
						//exit;
						if($accessory['is_price_percentage']=="Y"){
							$price		+=	round($product_price*$accessory['adjust_price'])/100;
						}elseif($accessory['independent_qty']=="Y"){
							$acprice=$accessory['adjust_price']/$qty;
							$price		+=	$acprice;
						}else{
							$price		+=	$accessory['adjust_price'];
						}
						//echo "access: ".$access."<br>";
						//echo "$access | ";
						if($objAccessory->IsAccessoryMonogram($val2) && $mono_status	== true)
							{
							
							$monogram_price	=	$this->GetMonogramPrice();
							$mono_status	=	false;
							$price		+=	$monogram_price;
							//echo "price: ".$price."<br>";
							}
						$i++;
					}
				}
			}
		}
		
		$total_price			=	(	$product_price  + 	$price	)	;
		//exit;
		return $total_price;
	}
	function Getoptions($values) {

		
		$objPrice		=	new Price();
		$objAccessory	=	new Accessory();
		$objCategory	=	new Category();
		//echo $values['qty'];
		//print_r($values);
		$total_price	=	"";
		$mono_status	=	false;
		$product_price	=	$objPrice->GetPriceOfProduct($product_id,0,$qty);
		if($this->IsMonogram($product_id))
		{
			$mono_status	=	true;
			//echo "mono_status: true <br>";
			}
		//find out all the categories of the product in the store
		$i=0;
		$price=0;
		$arr=array();
		$newVal	=	array();
		if($values['access']) {
		foreach ($values['access'] as $key=>$access) {
			if(is_array($access)){
				$newVal		=	$access;
			}else{
				$newVal[0]	=	$access;						
			}
				foreach ($newVal as $val2) {
					if($val2 > 0) {
					$accessory		=	$objAccessory->GetAccessory($val2);
					$category_id	=	$key;//$this->GetAccessorycategoryID($accessory['id']);
					
					#-----------------------------
					$catName		=	$objCategory->GetCategoryGroupName($category_id);
					$groupName		=	$this->getCatGroupName($accessory['id'],$category_id);
				
				/*	if($groupName != "")	{ 
					//if($groupName =="Colors" ||  $groupName =="Sizes" )
					if($groupName != "Monogram" )
					{ $groupName		= ""; }
					else{
					 $groupName		=$groupName." : ";}
					} else{ 
					$groupName		= "";}*/
					#-----------------------------
				
					$arr[$i]['name']			=	$accessory['name'];
					$arr[$i]['display_name']	=	$accessory['display_name'];
					$arr[$i]['category_name']	=	$groupName.$objCategory->GetCategoryGroupName($category_id);
					
					if($accessory['is_price_percentage']=="Y")
					$price		=	round($product_price*$accessory['adjust_price'])/100;
					else
					$price		=	$accessory['adjust_price'];
					if($price>0)
					$arr[$i]['price']	=	"$ ".$price;
					else
					$arr[$i]['price']	=	"No additonal amount";
					//echo "access: ".$access."<br>";
					if($objAccessory->IsAccessoryMonogram($val2) && $mono_status	== true)
						{
						$monogram_price	=	$this->GetMonogramPrice();
						$mono_status	=	false;
						$arr['monogram']	=	"Monogram Customization";
						$arr['monogram_price']	=	$monogram_price;
						//echo "price: ".$price."<br>";
						}
					
					$i++;
				}
			}
		}
	}
		//exit;
		//print_r($arr);
		return $arr;
	}
	
// For the category name of an accessory

	function getCatGroupName($accid,$catId) {
		$objCategory	=	new Category();
		/*$catId=0;
		$qry	=	"select category_id from category_accessory where 			accessory_id='$accid' ORDER BY category_id asc";
		$row 	= 	$this->db->get_row($qry,ARRAY_A);
		$catId	=	$row['category_id'];*/
		
		$qry2	=	"select parent_id  from master_category where  
		category_id ='$catId'";
		$row2 	= 	$this->db->get_row($qry2,ARRAY_A);
		$parent	=	$row2['parent_id'];
		
		//$category_name	=	$objCategory->getCategoryname($parent);
		$category_name	=	"";
		
		$qry3	=	"select category_name from master_category where category_id='$parent' AND is_monogram='Y'";
		$row3 	= 	$this->db->get_row($qry3,ARRAY_A);
		$category_name	= $row3['category_name'];
		if($category_name!="")
		{	$category_name=$category_name.":"; }
		
		return $category_name;
	}
	
// Ckeck for Best Selling Products.
function GetBestSellingProducts($limit=6,$store_name='')
	{
	$qry		=	"Select * from config where field='display_best_seller'";
	$row 		= 	$this->db->get_row($qry,ARRAY_A);
	if($row['value']=="Y")
		{
		$qry	=	"select sum(op.quantity) AS quantity,op.product_id from ";
		if($store_name)
		$qry		.=	" 	store as st, 
							product_store as ps , ";
		$qry		.=	"	order_products AS op,
							products as p where ";
		if($store_name)				
		$qry		.=	" 	op.product_id=ps.product_id and
							ps.store_id=st.id and
							st.name='".$store_name."'  and ";
							
		$qry		.=	" 	p.id=op.product_id AND p.name!='' ";
		if(empty($store_name))
		$qry		.=	" 	AND p.hide_in_mainstore!='Y' ";
		$qry		.=	" 	group by op.product_id order by quantity DESC LIMIT 0,$limit";
		
							
		
		//exit;
		$rs_n 	= 	$this->db->get_results($qry,ARRAY_A);

		if(count($rs_n)>0)
			{
			$status=true;
			$data=$rs_n;

			}
		else
			{
			$status=false;
			$data="";
			}
		}
	else
		{
		$status=false;
		$data="";
		}
	$arr=array("status"=>$status,"data"=>$data);
	//print_r($arr);
	return $arr;
	}
// Check for products 
function CheckTheAvailablityOfProduct($category_id,$store_name='')
	{
	$qry		=	"SELECT p.*	FROM ";
	if($store_name)
	$qry		.=	" 		store as st, 
							product_store as ps, ";   
	$qry		.=	"  		products AS p,
					   		category_product AS cp,
							master_category AS mc
					  WHERE  ";
	if($store_name)				
	$qry		.=		" 	p.id=ps.product_id AND
							ps.store_id=st.id AND
							st.name='".$store_name."' AND ";				  
					 
	$qry		.=	"   	mc.category_id=".$category_id." AND
					  		mc.active='y' AND
							mc.is_in_ui='N' AND
							mc.category_id=cp.category_id AND
							cp.product_id=p.id	AND
							p.active='Y' ";
	if(empty($store_name))	
	$qry		.=	"  		AND p.hide_in_mainstore!='Y' ";				
	$qry		.=	"  		order by p.display_order ";
	// $qry;
	///exit;
	if($category_id>0)
		{
			$row 	= 	$this->db->get_results($qry,ARRAY_A);
			if(count($row)>0)
			return true;
			else
			return false;
		}
		else
		{
			return false;
		}

	}
function GetProductsOfTheStoreAndAllSubcategory($category_id=0,$parent_id=0,$show_All='N',$store_name='',$pageNo=0, $limit = '', $params='', $output=OBJECT)
	{
	$obj	=	new Category();
	$obj->getCategoryTree($arr, $category_id);
	/* following code added by ajith*/
	if($category_id){
		if(count($arr['category_id'])==0){
			$arr['category_id'][]=$category_id;	
		}
	}
	/*************************************/	
	if($show_All=="Y")
		$arr['category_id'][]=$category_id;	
	if(count($arr['category_id'])>0)
		{
		$comma_separated = implode(",", $arr['category_id']);
		 $qry		=	"SELECT DISTINCT p.*	FROM ";
	
		if($store_name)
		$qry		.=	" 		store as st, 
								product_store as ps, "; 
		
								    						  
		$qry		.=	"  		products AS p,
								category_product AS cp,
								master_category AS mc
						  WHERE  ";
		if($store_name)				
		$qry		.=		" 	p.id=ps.product_id AND
								ps.store_id=st.id AND
								st.name='".$store_name."' AND ";				  
		
												 
		$qry		.=	"   	mc.category_id in (".$comma_separated.") AND
								mc.active='y' AND
								mc.is_in_ui='N' AND
								mc.category_id=cp.category_id AND
								cp.product_id=p.id	AND
								p.parent_id=$parent_id	AND
								p.active='Y'
												  		
						";
		if(empty($store_name))	
		$qry		.=	"  		AND p.hide_in_mainstore!='Y' ";
		
		//exit;
		$qry		.=	"  		order by CASE WHEN p.display_order IS NULL THEN 1000 END,p.display_order,name ";		
	$rs			=	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, ARRAY_A,$orderBy,'1');
	
	return $rs;
	
		}
		
	
	}
function GetProductsOfTheStoreAndAllSubcategory_1($category_id=0,$parent_id=0,$show_All='N',$store_name='',$list_all_page)
	{
	$obj	=	new Category();
	$obj->getCategoryTree($arr, $category_id);
	/* following code added by ajith*/
	if($category_id){
		if(count($arr['category_id'])==0){
			$arr['category_id'][]=$category_id;	
		}
	}
	/*************************************/	
	if($show_All=="Y")
		$arr['category_id'][]=$category_id;	
	if(count($arr['category_id'])>0)
		{
		$comma_separated = implode(",", $arr['category_id']);
		 $qry		=	"SELECT DISTINCT p.*	FROM ";
	
		if($store_name)
		$qry		.=	" 		store as st, 
								product_store as ps, "; 
		
								    						  
		$qry		.=	"  		products AS p,
								category_product AS cp,
								master_category AS mc
						  WHERE  ";
		if($store_name)				
		$qry		.=		" 	p.id=ps.product_id AND
								ps.store_id=st.id AND
								st.name='".$store_name."' AND ";				  
		
												 
		$qry		.=	"   	mc.category_id in (".$comma_separated.") AND
								mc.active='y' AND
								mc.is_in_ui='N' AND
								mc.category_id=cp.category_id AND
								cp.product_id=p.id	AND
								p.parent_id=$parent_id	AND
								p.active='Y'
												  		
						";
		if(empty($store_name))	
		$qry		.=	"  		AND p.hide_in_mainstore!='Y' ";
		
		//exit;
		$qry		.=	"  		order byCASE WHEN p.display_order IS NULL THEN 1000 END,p.display_order,name ";		
	//$rs			=	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, ARRAY_A,$orderBy,'1');
	$rs = $this->db->get_results($qry, ARRAY_A);
	return $rs;
	
		}
		
	
	}
	
	function getAllSubCategories($cat_id,$string)
	{
		$catQry	=	"select category_id from master_category where parent_id='$cat_id' AND  active='y'";
		$catRes	=	mysql_query($catQry);	
		while($catRow=mysql_fetch_array($catRes))
		{
			$id	=	$catRow['category_id'];
			$string=$string." , '" .$id	. "'";
			$CountQry	=	"select category_id from master_category where parent_id='$id' AND  active='y'";
			$CountRes	=	mysql_query($CountQry);
			$count	=	mysql_num_rows($CountRes);
			if($count>0)
			{
				$string	=	$this->getAllSubCategories($id,$string);
			}
		}
		return $string;
	}
//function that returns the products of the selected category and store.GetProductsOfTheStoreAndcategory
//updated by vipin on 21-11-2007
function GetProductsOfTheStoreAndcategory($category_id=0,$parent_id=0,$store_name='',$pageNo=0, $limit = 9, $params='', $output=OBJECT)
	{
	 
	// $this->config["artist_selection"];
		$string	=	"'".$category_id."'";
		$categories	=	$this->getAllSubCategories($category_id,$string);
		
	$qry		=	"SELECT DISTINCT p.*	FROM ";
	if($store_name)
	$qry		.=	" 		store as st, 
							product_store as ps, ";   
							
	if ($this->config["artist_selection"] == "Yes" and $_REQUEST['act'] == "listproduct" ){
	$userid = $_SESSION[memberid];
	/*$qry		.=	"  		products AS p,
					   		category_product AS cp,
							master_category AS mc,
							product_saved_work AS pw
					  WHERE pw.pro_save_id = p.id and pw.user_id != $userid and  ";*/
	$qry		.=	"  		products AS p,
					   		category_product AS cp,
							master_category AS mc,
							product_saved_work AS pw
					  WHERE pw.pro_save_id = p.id and  ";
	
	}else{				  						
	$qry		.=	"  		products AS p,
					   		category_product AS cp,
							master_category AS mc
					  WHERE  ";
	}				  
	if($store_name)				
	$qry		.=		" 	p.id=ps.product_id AND
							ps.store_id=st.id AND
							st.name='".$store_name."' AND ";				  

		$qry		.=	"   	mc.category_id IN ($categories) AND
					  		mc.active='y' AND
							mc.is_in_ui='N' AND
							mc.category_id=cp.category_id AND
							cp.product_id=p.id	AND
							p.parent_id='$parent_id'	AND
							p.active='Y'
										  		
					";	


if(empty($store_name))	
		$qry		.=	"  		AND p.hide_in_mainstore!='Y' ";	
if ($this->config["artist_selection"] == "Yes" and $_REQUEST['act'] == "listproduct" )	
	$qry		.=	"  order by rand() ";	
	else	
	$qry		.=	"  		order by CASE WHEN p.display_order IS NULL OR p.display_order =' ' THEN 1000 END,p.display_order,p.name ";
	//$rs			=	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, ARRAY_A,$orderBy,'1');

	$rs			=	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, ARRAY_A,'','1');

	return $rs;
	}
	
//function that returns the products of the selected category and store.GetProductsOfTheStoreAndcategory
function GetProductsOfTheStoreAndcategoryOrder($category_id=0,$parent_id=0,$store_name='',$pageNo=0, $limit = 9, $params='', $output=OBJECT)
	{
	extract($_REQUEST);
		$string	=	"'".$category_id."'";
		$categories	=	$this->getAllSubCategories($category_id,$string);
	$qry		=	"SELECT DISTINCT p.*	FROM ";
	if($store_name)
	$qry		.=	" 		store as st, 
							product_store as ps, ";   
	$qry		.=	"  		products AS p,
					   		category_product AS cp,
							master_category AS mc
					  WHERE  ";
	if($store_name)				
	$qry		.=		" 	p.id=ps.product_id AND
							ps.store_id=st.id AND
							st.name='".$store_name."' AND ";				  
					 
	$qry		.=	"   	mc.category_id IN ($categories) AND
					  		mc.active='y' AND
							mc.is_in_ui='N' AND
							mc.category_id=cp.category_id AND
							cp.product_id=p.id	AND
							p.parent_id='$parent_id'	AND
							p.active='Y'

										  		
					";

if(empty($store_name))	
		$qry		.=	"  		AND p.hide_in_mainstore!='Y' ";	
	
		//$qry		.=	"  		order by CASE WHEN p.display_order IS NULL OR p.display_order =' ' THEN 1000 END,p.display_order,p.name ";	
	//$rs			=	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, ARRAY_A,$orderBy,'1');
	$rs			=	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, ARRAY_A,$orderBy,'1');
	return $rs;
	}


function GetProductsOfTheStoreAndcategory_1($category_id=0,$parent_id=0,$store_name='',$list_all_page)
	{
		$string	=	"'".$category_id."'";
		$categories	=	$this->getAllSubCategories($category_id,$string);
	$qry		=	"SELECT DISTINCT p.*	FROM ";
	if($store_name)
	$qry		.=	" 		store as st, 
							product_store as ps, ";   
	$qry		.=	"  		products AS p,
					   		category_product AS cp,
							master_category AS mc
					  WHERE  ";
	if($store_name)				
	$qry		.=		" 	p.id=ps.product_id AND
							ps.store_id=st.id AND
							st.name='".$store_name."' AND ";				  
					 
	$qry		.=	"   	mc.category_id IN ($categories) AND
					  		mc.active='y' AND
							mc.is_in_ui='N' AND
							mc.category_id=cp.category_id AND
							cp.product_id=p.id	AND
							p.parent_id='$parent_id'	AND
							p.active='Y'
										  		
					";
	if(empty($store_name))	
		$qry		.=	"  		AND p.hide_in_mainstore!='Y' ";	
		
		$qry		.=	"  		order by CASE WHEN p.display_order IS NULL OR p.display_order =' ' THEN 1000 END,p.display_order,p.name ";	
	
	//$rs			=	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, ARRAY_A,$orderBy,'1');
	//echo $qry;
		if ($list_all_page == 'Y') {
		$rs = $this->db->get_results($qry, ARRAY_A);
		
		return $rs;
		}
	//$rs			=	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, ARRAY_A,$orderBy,'1');
	//return $rs;
	}
	
	function getCustomizationText ($id) {
		$rs = $this->db->get_row("SELECT * FROM product_accessory_wrap_text WHERE id = '$id'", ARRAY_A);
		return $rs['text'];
	}

function GetMonogramPrice()
	{
	$qry		=	"SELECT amount FROM product_monogram_price WHERE name='Monogram'";
	$row 		= 	$this->db->get_row($qry,ARRAY_A);
	if($row['amount'])
		return $row['amount'];
	else
		return 0;
	}
function IsMonogram($product_id=0)
	{
	$qry		=	"select count(*) as number from products where id='$product_id' and personalise_with_monogram='Y'";
	
	if($product_id>0)
		{
			$row 	= 	$this->db->get_row($qry,ARRAY_A);
			if($row['number']>0)
			return true;
			else
			return false;
		}
		else
		{
			return false;
		}

	}
function AllProductGroups($parameter = 'Y')
	{
	$arr	=	array();
	$qry	=	"select * from product_accessory_group where parameter='$parameter' order by display_order asc";
	$row 	= 	$this->db->get_results($qry,ARRAY_A);
	for($i=0;$i<count($row);$i++)
		{
		$arr[$i]['group']['id']=$row[$i]['id'];
		$arr[$i]['group']['group_name']=$row[$i]['group_name'];
		$arr[$i]['group']['parameter']=$row[$i]['parameter'];
		 $qry	=	"select mc.* from master_category as mc,product_accessory_group_categories as pgc where mc.category_id=pgc.category_id AND pgc.group_id=".$row[$i]['id'];
		$rs['category_id'] 					= 	$this->db->get_col($qry, 0);
		 $rs['category_name'] 				= 	$this->db->get_col($qry, 2);
		//exit;
		
		$arr[$i]['group']['category_id']	=	$rs['category_id'];
		$arr[$i]['group']['category_name']	=	$rs['category_name'];
		}
	return $arr;
	}
function getcategory_id()
	{
	$arr	=	array();
	$qry	=	"select DISTINCT pagc.* from product_accessory_group_categories as pagc";
	$row 	= 	$this->db->get_results($qry,ARRAY_A);
	//print_r($row);
	//exit;
	for($i=0;$i<count($row);$i++)
		{
		$arr[$i]=$row[$i];
		$j=0;
		foreach ($row as $category)
			{
			$category_id	=	$category['category_id'];
			$ress 			= 	$this->db->get_results("SELECT * FROM product_accessories WHERE category_id='{$category_id}'", ARRAY_A);
			$arr[$i]['access'][$j]=$ress;
			$j++;
			//exit;
			}
		}
		//print_r($arr);
		return $arr;
	}
// Find the cross selling products of the selected product ($product_id).
function GetCrossSellProduct($product_id=0,$store_name='')
	{
	if($product_id>0)
		{
		$qry		 =	" 	SELECT count(*) as number, pc.sell_id  FROM ";
		if($store_name)
		$qry		.=	" 	store as st, 
							product_store as ps , ";
		$qry		.=	" 	product_cross_sell as pc";
		if(empty($store_name))	
		$qry		.=",products as p";
		
		$qry		.=	" 	WHERE ";
		if($store_name)				
		$qry		.=	" 	pc.sell_id=ps.product_id AND
							ps.store_id=st.id AND
							st.name='".$store_name."' AND ";
		$qry		.=	" 	pc.sell_id!=$product_id AND pc.product_id=$product_id";
		
		if(empty($store_name))	
		$qry		.=	"  	 AND p.id=pc.sell_id AND p.hide_in_mainstore!='Y' ";
		
		$qry		.=	" 	group by sell_id order by number DESC limit 0,6";
		$row 		= 	$this->db->get_results($qry,ARRAY_A);
		return $row;
		}
	}
	/**
    * Showing the related products
  	* Author   : ---
  	* Created  : --
  	* Modified : 26-Dec-2007 By Shinu
  	*/
function CheckToDisplayRelatedProduct($product_id=0,$store_name='',$limit=6)
	{
	if($product_id>0)
		{
		/*$qry	=	" 		SELECT pr.related_id as related_id  FROM ";
		if($store_name)
		$qry		.=	" 	store as st, 
							product_store as ps , ";
		$qry		.=	" 	product_related AS pr,products AS p" ;
		
		$qry		.=	" 	WHERE ";
		if($store_name)				
		$qry		.=	" 	pr.product_id=ps.product_id AND
							ps.store_id=st.id AND
							st.name='".$store_name."' AND ";
							
		$qry		.=	" 	p.id=pr.related_id AND pr.product_id=$product_id ";
		if(empty($store_name))	
		$qry		.=	"  	AND p.hide_in_mainstore!='Y' ";
			
		$qry		.=	"	order by rand() limit 0,$limit";*/
		$qry1		=	"select relate_id from products_related where product_id='$product_id'";
		$rs1		=	$this->db->get_results($qry1,ARRAY_A);
		$relate_ids="'0'";
		for($j=0;$j<count($rs1);$j++)
		{
			$rel_prdId	=	$rs1[$j]['relate_id'];
			$relate_ids=$relate_ids. " , '".$rel_prdId."'";
		}
		
		$qry2		=	"select product_id from products_related where relate_id IN($relate_ids) ";
		$rs2		=	$this->db->get_results($qry2,ARRAY_A);
		$product_ids="'0'";
		for($j=0;$j<count($rs2);$j++)
		{
			$prdId	=	$rs2[$j]['product_id'];
			if($prdId != $product_id)
				$product_ids=$product_ids. " , '".$prdId."'";
		}
		
		
		//$qry3	=	"select id as related_id from  products where ";
		$qry	=	" SELECT p.id as related_id  FROM ";
		if($store_name)
		$qry		.=	" 	store as st, 
							product_store as ps , ";
		$qry		.=	" 	products AS p" ;
		
		$qry		.=	" 	WHERE ";
		if($store_name)				
		$qry		.=	" 	ps.store_id=st.id AND
							st.name='".$store_name."' ";
		
		if(empty($store_name))	
		$qry		.=	"  	p.hide_in_mainstore!='Y' ";
		
		$qry		.=	"  	AND p.active='Y' AND p.id IN ($product_ids) ";
			
		$qry		.=	"	order by rand() limit 0,$limit";
		
							
		$row 	= 	$this->db->get_results($qry,ARRAY_A);
		$rs=$this->ProductGet($product_id);
		//if(count($row)>0 && $rs['display_related']=='Y')
		if(count($row)>0)
			$arr=array("status"=>true,"data"=>$row);
		else
			$arr=array("status"=>false,"data"=>false);
		
		return $arr;
		
		}
	}
	//insert the products shopped in shopping cart
function insertTOCrossSellProduct($products)
	{
	for($i=0;$i<count($products);$i++)
		{
		for($j=0;$j<count($products);$j++)
			{
			if($i!=$j)
				{
				$array	=	array("product_id"=>$products[$i],"sell_id"=>$products[$j]);
				$this->db->insert("product_cross_sell", $array);
				}
			}
		}
	}
//function Next and Previous products in description page.
function GetNextPreviousProducts($product_id,$category_id=0,$store_name='')
	{
	//echo $category_id;
	
	if($category_id==0)
		{
		$category	=	$this->GetAllProductCategoty($product_id);
		//print_r($category);
		$category_id=$category['category_id'][0];
		}
	//echo $category_id;
	
	$qry		=	"SELECT p.id FROM ";
	if($store_name)
	$qry		.=	" 		store as st, 
							product_store as ps, ";   
	$qry		.=	"  		products AS p,
					   		category_product AS cp,
							master_category AS mc
					  WHERE  ";
	if($store_name)				
	$qry		.=		" 	p.id=ps.product_id AND
							ps.store_id=st.id AND
							st.name='".$store_name."' AND ";				  
					 
	$qry		.=	"   	mc.category_id=".$category_id." AND
					  		mc.active='y' AND
							mc.is_in_ui='N' AND
							mc.category_id=cp.category_id AND
							cp.product_id=p.id	AND 
							p.active='Y' AND
							p.parent_id	= '0' ";
	if($store_name=='')
	$qry		.=		" 	AND p.hide_in_mainstore	= 'N'";	
	
	$qry		.=	"		order by p.display_order ";
	//echo $qry;
	//exit;

	$rs			=	$this->db->get_results($qry, ARRAY_A);
	
	for($i=0;$i<count($rs);$i++)
		{
		if($rs[$i]['id']==$product_id)
			{
			if($i>0)
			$previous	=	$rs[$i-1]['id'];
			if($ii<count($rs))
				$next		=	$rs[$i+1]['id'];
			break;
			}
		}
	if(empty($previous))
		$arr['previous']	=	0;
	else
		$arr['previous']	=	$previous;
	if(empty($next))
		$arr['next']		=	0;
	else	
		$arr['next']		=	$next;
	//
	//print_r($arr);
	/*if($arr['previous']>0)
		$arr['previous']['diplay_string']*/
	return $arr;
	}
function GetProductsearchResults($array,$type,$pageNo=0, $limit = 10, $params='', $output=OBJECT,$orderBy='p.display_order ',$store_name='')
	{
		//echo $params;
		$qry		=	$this->GetProductSearchQry($array,$type,$store_name);
		$qry		.=	"  		order by CASE WHEN p.display_order IS NULL THEN 1000 END,p.display_order,p.name ";	
	
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, ARRAY_A,'', '1');
		/*echo "qry: ".$qry."<br>";
		echo "pageNo: ".$pageNo."<br>";
		echo "limit: ".$limit."<br>";
		echo "params: ".$params."<br>";
		echo "orderBy: ".$orderBy."<br>";*/
		//print_r($rs);
		return $rs;
	}
	/**
      * Author   : Salim
      * Searches 3 tables Products,Product_accessories and cms_page for the key word	
      * Created  : 1/Feb/2008
      * Modified : 
      * Description of Modifications here
      */
function GetAllsearchResults($array,$type,$pageNo=0, $limit = 10, $params='', $output=OBJECT,$orderBy='p.display_order ',$store_name='')
	{	global $global;
		global $store_id;	
		if($store_name !=''){
			
			$sql	=	"SELECT DISTINCT id,name,description,thickness AS type, 'products' AS TableName FROM products,product_store WHERE product_store.product_id = products.id AND product_store.store_id = $store_id AND(name LIKE '%".$array['keyword']."%' or display_name LIKE '%".$array['keyword']."%' or description LIKE '%".$array['keyword']."%' and active = 'Y') 
						 UNION ALL
						SELECT DISTINCT id,title AS name,content AS description,post_admin AS type, 'cms_page' AS TableName FROM cms_page,store_cms WHERE store_cms.cms_id = cms_page.id and store_cms.store_id = $store_id AND(title LIKE '%".$array['keyword']."%' or content LIKE '%".$array['keyword']."%' and active = 'Y') ";
						
					/*	 UNION ALL
						SELECT DISTINCT id,title AS name,description,url AS type, 'cms_link' AS TableName FROM cms_link WHERE (title LIKE '%".$array['keyword']."%' or url LIKE '%".$array['keyword']."%' or description LIKE '%".$array['keyword']."%') and type_link like 'texts' and area LIKE '%store%'
					$sql	.=	" UNION ALL
						SELECT DISTINCT product_accessories.id,name,description,type, 'product_accessories' AS TableName FROM product_accessories, store_accessory WHERE store_accessory.accessory_id = product_accessories.id AND store_accessory.store_id = $store_id AND(name LIKE '%".$array['keyword']."%' or display_name LIKE '%".$array['keyword']."%' or description LIKE '%".$array['keyword']."%' and active = 'Y') ORDER BY type ";*/
			
				//echo $sql;exit;
		}
		else{
		
/*		 UNION ALL
						SELECT DISTINCT id,name,description,type, 'product_accessories' AS TableName FROM product_accessories WHERE (name LIKE '%".$array['keyword']."%' or display_name LIKE '%".$array['keyword']."%' or description LIKE '%".$array['keyword']."%') and active = 'Y' AND parent_id != '1' and description !=''  */
		
		$sql		=	"SELECT DISTINCT id,name,description,thickness AS type, 'products' AS TableName FROM products WHERE (name LIKE '%".$array['keyword']."%' or display_name LIKE '%".$array['keyword']."%' or description LIKE '%".$array['keyword']."%') and active = 'Y' AND hide_in_mainstore = 'N'
						
							UNION ALL
						SELECT DISTINCT id,title AS name,content AS description,post_admin AS type, 'cms_page' AS TableName FROM cms_page WHERE (title LIKE '%".$array['keyword']."%' or content LIKE '%".$array['keyword']."%' and active = 'Y') and post_admin='admin'  ORDER BY type ";
							
		}
		//$sql	   .=	"  		order by CASE WHEN a.display_order IS NULL THEN 1000 END,a.display_order,a.name ";	
		$rs 		= 	$this->db->get_results_pagewise($sql, $pageNo, $limit, $params, ARRAY_A,'', '1');
		
		return $rs;
	}

	/**
      * Author   : Salim
      * Created  : 4/Feb/2008
      * Modified : 
      * Description of Modifications here
      */
function getCmsSearchDetails($id='')
	{	
		$sql 	=	"SELECT cp. * , cm. * FROM cms_page cp, cms_menu cm WHERE cp.menu_id = cm.id AND cp.id = ".$id;
		$rs	=	$this->db->get_row($sql,ARRAY_A);
		return $rs['seo_url'];
				
	}
function getCategoryIdOfSearchItem($id='',$table)
{
	if($table == 'products'){	
		$sql	=	"SELECT * FROM category_product where product_id =".$id;
	}
	else{
		$sql	=	"SELECT * FROM category_accessory where accessory_id =".$id;
	}
		$rs	=	$this->db->get_row($sql,ARRAY_A);
	
		return $rs['category_id'];	
}

function GetProductsearchResults_1($array,$type,$pageNo=0, $limit = 10, $params='', $output=OBJECT,$orderBy='p.display_order ',$store_name='',$list_all_page)
	{
		
		$qry		=	$this->GetProductSearchQry($array,$type,$store_name);
		$qry		.=	"  		order by CASE WHEN p.display_order IS NULL THEN 1000 END,p.display_order,p.name ";	
		
			if($list_all_page == "Y"){
			$rs = $this->db->get_results($qry, ARRAY_A);
			return $rs;
			}
	
		#$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, ARRAY_A, $orderBy,'1');
		/*echo "qry: ".$qry."<br>";
		echo "pageNo: ".$pageNo."<br>";
		echo "limit: ".$limit."<br>";
		echo "params: ".$params."<br>";
		echo "orderBy: ".$orderBy."<br>";*/
		//print_r($rs);
		#return $rs;
	}

function GetProductSearchQry($array,$type,$store_name='')	
	{
	//print_r($array);
	$qry1	=	array();
	$i		=	0;
	$qry2	=	array();
	$j		=	0;
	$qry3	=	array();
	$k		=	0;
	if($array['category_id']>0 || $store_name)
		$qry		=	" SELECT DISTINCT p.* FROM 	";
	else
		$qry		=	" SELECT p.* FROM 			";

	switch ($type)
		{
		case 'normal':
					if($array['category_id']>0)
									{
									$qry1[$i]		.=	" category_product	AS 	cp	";$i++;//if category id exists
									$qry1[$i]		.=	" master_category	AS 	mc ";$i++;//if category id exists
									$qry2[$j]		.=	" cp.product_id		=	p.id  ";$j++;//if category id exists
									$qry2[$j]		.=	" cp.category_id	=	mc.category_id  ";$j++;//if category id exists
									$qry3[$k]		.=	" mc.category_id	= '".$array['category_id']."'  ";$k++;//if category id exists
									}
						if($store_name)
								{
								$qry1[$i]		.=	" product_store		AS 	ps	";$i++;//if store name exists 
								$qry1[$i]		.=	" store				AS 	s	";$i++;//if store name exists
								$qry2[$j]		.=	" ps.product_id	=	p.id ";$j++;//if store name exists
								$qry2[$j]		.=	" ps.store_id		=	s.id ";$j++;//if store name exists
								$qry3[$k]		.=	" s.name			=	'".$store_name."'  ";$k++;
								}
						if($array['keyword'])
								{
								$qry3[$k]		.=	" (p.name			LIKE '%".$array['keyword']."%' OR ";
								$qry3[$k]		.=	" p.display_name	LIKE '%".$array['keyword']."%' OR ";
								$qry3[$k]		.=	" p.cart_name   	LIKE '%".$array['keyword']."%' OR ";
								$qry3[$k]		.=	" p.description		LIKE '%".$array['keyword']."%' )  ";$k++;
								}
						if($array['price1']>=0 && $array['price2']>=0)
								{
								$p1=$array['price1'];
								$p2=$array['price2'];
									 if ($p2>0)	
									 $qry3[$k]		.=	" p.price	BETWEEN '$p1' AND  '$p2' ";$k++;}		
						$qry3[$k]		.=	" p.parent_id		= '0'   ";$k++;
						if($store_name=='')
							{$qry3[$k]		.=	" p.hide_in_mainstore	= 'N'   ";$k++;}
							
						
			//echo $qry3;
			break;
		case 'advanced':
								if($array['category_id']>0)
									{
									$qry1[$i]		.=	" category_product	AS 	cp	";$i++;//if category id exists
									$qry1[$i]		.=	" master_category	AS 	mc ";$i++;//if category id exists
									$qry2[$j]		.=	" cp.product_id		=	p.id  ";$j++;//if category id exists

									$qry2[$j]		.=	" cp.category_id	=	mc.category_id  ";$j++;//if category id exists
									$qry3[$k]		.=	" mc.category_id	= '".$array['category_id']."'  ";$k++;//if category id exists
									}
								if($store_name)
									{
									$qry1[$i]		.=	" product_store		AS 	ps	";$i++;//if store name exists 
									$qry1[$i]		.=	" store				AS 	s	";$i++;//if store name exists
									$qry2[$j]		.=	" ps.product_id	=	p.id  ";$j++;//if store name exists
									$qry2[$j]		.=	" ps.store_id		=	s.id ";$j++;//if store name exists
									$qry3[$k]		.=	" s.name			=	'".$store_name."'  ";$k++;
									}
								if($array['brand_id']>0)
									{
									$qry1[$i]		.=	" brands 				AS 	b	";$i++;
									$qry2[$j]		.=	" b.brand_id		=	p.brand_id  ";$j++;
									$qry3[$k]		.=	" b.brand_id		= '".$array['brand_id']."'  ";$k++;
									}
								if($array['keyword'])
								{
									$qry3[$k]		.=	" p.display_name			LIKE '%".$array['keyword']."%'  ";$k++;}
								if($array['title'])
								{
									$qry3[$k]		.=	" p.display_name			LIKE '%".$array['title']."%'  ";$k++;}
								if($array['subject'])
								{
								$qry3[$k]		.=	" p.display_name			LIKE '%".$array['subject']."%'  ";$k++;}
									
								if($array['price1'] && $array['price2'])
								{
								$p1=$array['price1'];
								$p2=$array['price2'];
									 $qry3[$k]		.=	" p.price	BETWEEN '$p1' AND  '$p2' ";$k++;}
									
								if($array['discription'])
								{
								$qry3[$k]		.=	" p.description			LIKE '%".$array['discription']."%'  ";$k++;}

									
								if($array['location'])
									{
									$qry1[$i]		.=	" product_made_in	AS 		pmi	";$i++;
									$qry1[$i]		.=	" product_zone		AS 		pz	";$i++;
									$qry2[$j]		.=	" pmi.product_id	=		p.id  ";$j++;
									$qry2[$j]		.=	" pmi.zone_id		=		pz.id  ";$j++;
									$qry3[$k]		.=	" pz.name			LIKE 	'%".$array['location']."%' ";$k++;
									}
								$qry3[$k]		.=	" p.parent_id			= '0'   ";$k++;
								if($store_name=='')
								{$qry3[$k]		.=	" p.hide_in_mainstore	= 'N'   ";$k++;}
			break;
		}
$qry		.=	"  		products AS p 															";
if(count($qry1)>0 && count($qry2)>0)
{
$qry		.=	"						LEFT JOIN 	(											";
$qry		.=	implode(",", $qry1);
$qry		.=	"									) 											";
$qry		.=	"						ON 			(	 										";
$qry		.=	implode(" AND ", $qry2);
$qry		.=	"									) 											";
}
$qry		.=	" WHERE  																		";
if(count($qry3)>0)
{$qry		.=	implode(" AND ", $qry3)." AND ";
}
$qry		.=	"   	p.active		=		'Y'												";	
//$qry		.=	" ORDER BY 																		";
//$qry		.=	" 		p.display_order 														";

	return $qry;
	}
function ValidateGiftCertificate($product_id,$rate='')
	{
	if($this->IsGiftCertificate($product_id))
		{
		if(empty($rate) && $rate<=0)
				return false;
		else
				return true;
		}
	else
		{
		return true;
		}
	}	

function IsGiftCertificate($product_id)
	{
	$qry		=	"select count(*) as number from products where id='$product_id' and is_giftcertificate<>'N'";
	if($product_id>0)
		{
			$row 	= 	$this->db->get_row($qry,ARRAY_A);
			if($row['number']>0)
			return true;
			else
			return false;
		}
		else
		{
			return false;
		}
	}
function GetGiftCertificateDetails($user_id=0)
	{
	$rs		=	array();
	if($user_id>0)
		{
		$qry	=	"	SELECT c.*,cp.type_option,cp.no_times,cp.duration FROM certificates AS c,certificate_property AS cp WHERE 
						user_id='$user_id' AND
						c.certi_startdate<=curdate() AND
						c.certi_enddate>curdate() AND
						c.active='Y' AND
						c.product_id=cp.product_id ";
		$rs			=	$this->db->get_results($qry, ARRAY_A);
		}
	return $rs;
	}	
	
    function addFavorite($product_id,$userid)
    {
        $sql="Select id from media_favorites where file_id=".$product_id ." and userid=".$userid." and type='product'";
        $count=count($this->db->get_results($sql));
        if($count==0)
        {
			$arr=array("file_id"=>$product_id,"userid"=>$userid,"type"=>"product");
            $this->db->insert("media_favorites",$arr);
            return true;
        }
        else
        {
           return false;
        }
    }
function getFavCnt($type,$file_id)
	{

		$sql = "SELECT count(id) as cnt  FROM `media_favorites`";
		$sql = $sql. " where file_id=$file_id and type='$type'";
		$RS = $this->db->get_results($sql,ARRAY_A);
		return $RS[0]['cnt'];
	}
	function mediaFavList ($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$tbl_name,$type,$stxt=0,$uid=0)
	{
		$sql="Select a.*,count(c.id) from (`$tbl_name` a inner join `media_favorites` b ";
		$sql=$sql."on (a.id=b.file_id and b.type='$type'))left join `media_comments` c on ";
		$sql=$sql."(a.id=c.file_id and c.type='$type')  where b.userid=$uid";

		if ($stxt!='')
		{
			$sql=$sql. " and (a.tags like '%$stxt%' or a.title like '%$stxt%')";
		}

		$sql=$sql." group by a.id";
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);

		foreach ($rs as $value)
		{
			for($i=0;$i<sizeof($value);$i++)
			{
				if($value[$i]->id>0)
				{
					$rating=$this->getRating($type,$value[$i]->id);
					$rs[0][$i]->rate=$rating["rate"];
					$rs[0][$i]->cnt=$rating["cnt"];

					$cmcnt =$this->getCommentsCnt($type,$value[$i]->id);
					$rs[0][$i]->cmtcnt=$cmcnt;

					$favcnt =$this->getFavCnt($type,$value[$i]->id);
					$rs[0][$i]->favrcnt=$favcnt;

				}
			}
		}
		//		print_r($rs);
		return $rs;
	}
function GetFavList($user_id,$store_name='',$pageNo=0, $limit = 9, $params='', $output=OBJECT,$orderBy='')
	{
	$qry		=	"		SELECT p.*	FROM ";
	if($store_name)
	$qry		.=	" 		store as st, 
							product_store as ps, ";   
	$qry		.=	"  		products AS p,
							media_favorites AS mf
					   		
					  WHERE  ";
	if($store_name)				
	$qry		.=		" 	p.id=ps.product_id 			AND
							ps.store_id=st.id 			AND
							st.name='".$store_name."'	AND ";				  
					 
	$qry		.=	"   	p.id=mf.file_id				AND
							mf.userid='".$user_id."' 	AND
							mf.type='product' 			AND
							p.active='Y'
											  		
					";
	//echo $qry;
	$rs			=	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, ARRAY_A,'','1');
	return $rs;
	}
function Removefav($product_id,$user_id)
	{
	$this->db->query("DELETE FROM media_favorites where file_id=".$product_id ." and userid=".$user_id." and type='product'");
	return true;
	}
//last purchases
function GetLastPurchase($user_id,$from='',$to='',$store_name='',$pageNo=0, $limit = 9, $params='', $output=OBJECT)
	{
	$qry		=	"		SELECT p.*,o.date_ordered,sum(op.quantity) as quantity	FROM ";
	if($store_name)
	$qry		.=	" 		store AS st, 
							product_store AS ps, ";   
	$qry		.=	"  		products AS p,
							orders AS o,
							order_products AS op
					   		
					  WHERE  ";
	if($store_name)				
	$qry		.=		" 	p.id=ps.product_id 			AND
							ps.store_id=st.id 			AND
							st.name='".$store_name."'	AND ";				  
	if($from)
		$qry		.=	"   o.date_ordered>='".$from."'	AND ";
	if($to)
		$qry		.=	"   o.date_ordered<='".$to." 00:00:00"."'	AND ";
	$qry		.=	"   	p.id=op.product_id			AND
							op.order_id=o.id			AND
							o.user_id='$user_id'		AND
							
							p.active='Y'
				 	GROUP BY DAY(o.date_ordered),op.product_id 						  		
					ORDER BY o.date_ordered DESC";
	$rs			=	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, ARRAY_A,'','1');
	return $rs;
	}	
	
/*********************************************************************/
/* function used with store in admin side     */
function Store_listAllProduct($store_id,$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy,$product_search="",$catId='')
	{
		if(trim($catId)=="")
		{
			$qry		=	"select * from products as p left join (brands as b) on (p.brand_id=b.brand_id),product_store as ps where ps.product_id=p.id and ps.store_id=$store_id ";
			if($product_search!="")
				$qry	.=	" and p.name LIKE '%".$product_search."%'";
		}
		else
		{	$categories	=	"'0'";
			$categories	=	$this->getChildCategories($catId,$categories); 
			$categories	= $categories.",'".$catId."'";
			$catRs		=	mysql_query("select product_id from category_product where category_id IN ($categories)");	
			$products	=	"'0'";
			while($catRow=mysql_fetch_array($catRs))
			{	
			$products.=",'".$catRow["product_id"]."'"; }
			$qry	=	"select * from products as p left join (brands as b) on (p.brand_id=b.brand_id),product_store as ps where ps.product_id=p.id and ps.store_id=$store_id and p.id IN($products) ";
		}
		
		
		
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
function Store_AllProductGroups($store_id)
	{
	$arr	=	array();
	$qry	=	"SELECT PA.* FROM product_accessory_group AS PA,store_accessory_group AS SA WHERE SA.group_id=PA.id AND SA.store_id=$store_id AND PA.parameter='Y'";
	$row 	= 	$this->db->get_results($qry,ARRAY_A);
	for($i=0;$i<count($row);$i++)
		{
		$arr[$i]['group']['id']=$row[$i]['id'];
		$arr[$i]['group']['group_name']=$row[$i]['group_name'];
		$arr[$i]['group']['parameter']=$row[$i]['parameter'];
		$qry	=	"select mc.* from master_category as mc,product_accessory_group_categories as pgc where mc.category_id=pgc.category_id AND pgc.group_id=".$row[$i]['id'];
		$rs['category_id'] 					= 	$this->db->get_col($qry, 0);
		$rs['category_name'] 				= 	$this->db->get_col($qry, 2);
		
		$arr[$i]['group']['category_id']	=	$rs['category_id'];
		$arr[$i]['group']['category_name']	=	$rs['category_name'];
		}
	return $arr;
	}
function Store_massUpdate($req)
	{
	#print_r($req);
	#exit;
	extract($req);

		if(count($product_id)>0)
		{

			$array 				= 	array();
			if(trim($brand_id) || ($brand_id=="0"))
			{
				$newarray		=	array("brand_id"	=>	$brand_id);
				$array			=	array_merge($array,$newarray);
			}
			if(trim($price))
			{
				$newarray		=	array("price"		=>	$price);
				$array			=	array_merge($array,$newarray);
			}
			if(trim($thickness))
			{
				$newarray		=	array("thickness"	=>	$thickness);
				$array			=	array_merge($array,$newarray);
			}
			if(trim($weight))
			{
				$newarray		=	array("weight"		=>	$weight);
				$array			=	array_merge($array,$newarray);
			}
			if(trim($description))
			{
				$newarray		=	array("description"	=>	$description);
				$array			=	array_merge($array,$newarray);
			}
			if(trim($active))
			{
				$newarray		=	array("active"		=>	"Y");
				$array			=	array_merge($array,$newarray);
			}
			else
			{
				$newarray		=	array("active"		=>	"N");
				$array			=	array_merge($array,$newarray);
			}
			if(trim($display_gross))
			{
				$newarray		=	array("display_gross"		=>	"Y");
				$array			=	array_merge($array,$newarray);
			}
			else
			{
				$newarray		=	array("display_gross"		=>	"N");
				$array			=	array_merge($array,$newarray);
			}
			if(trim($display_related))
			{
				$newarray		=	array("display_related"		=>	"Y");
				$array			=	array_merge($array,$newarray);
			}
			else
			{
				$newarray		=	array("display_related"		=>	"N");
				$array			=	array_merge($array,$newarray);
			}
			foreach ($product_id as $id)
				{
					$this->db->update("products", $array, "id='$id'");
					if(count($category)>0)//the category will be updated only if any category is selected
						{
						$this->db->query("DELETE FROM category_product WHERE product_id='$id'");
						foreach ($category as $cat_id)
							{
								$ins	=	array("category_id"=>$cat_id,"product_id"=>$id);
								$this->db->insert("category_product", $ins);
							}
						}
					///*******geting the additional pricing structure for the selected products.***////////
					if($price_type>0)
						{
						if($prices)
							{
							if($is_percentage)
								$status_is_percentage="Y";
							else
								$status_is_percentage="N";
							$pricearray		=	array("type_id"=>$price_type,"price"=>$prices,"is_percentage"=>$status_is_percentage,"product_id"=>$id);
							$this->db->query("DELETE FROM product_price WHERE product_id='$id'");
							$this->db->insert("product_price", $pricearray);
							}
						}
					//************************************************************////////
					//mass update the accessory available for the selected products
					/*******************************************************************************************/
					if(count($accessory)>0)
						{
						if($Append=='Y')
						$this->append_product_accessory($id,$accessory);
						else
						$this->save_product_accessory($id,$accessory);
						}
					/*******************************************************************************************/
				}//foreach ($product_id as $id)
			
			}//if(count($product_id)>1)
	
	}	
	
/************************************************************************/	
/****** function to retursn the content to div   *********/
function DisplayAccessory($group_id,$category_id,$product_id=0,$store='All')
	{
	$accessory_tpl = $this->tpl;
	//print_r($this->GetAccessories($category_id));
	//exit;
	$objCategory	=	new Category();
	$cat	=	$objCategory->CategoryGet($category_id);
	$child	=	$objCategory->GetChildMonogramCategories($category_id);
	//echo '<pre>';
	//print_r($child);
	//exit; 
	
	if($cat['is_monogram']=="Y" && $child['status']==true)
		{
		$monogram=true;
		$accessory_tpl->assign("MONOGRAM",'YES');
		$i=0;
		foreach ($child['data'] as $row)
			{
			$result[$i]['data']	=	$this->GetAccessories($row->category_id,$store);
			$result[$i]['cat_name']=$objCategory->getCategoryname($row->category_id);
			$result[$i]['cat_id']=$objCategory->getCategoryname($row->category_id);
			$i++;
			}
		$accessory_tpl->assign("GRP_ACCESSORY",$result);
		}
	else
		{
		$accessory_tpl->assign("GRP_ACCESSORY",$this->GetAccessories($category_id,$store));
		//echo '<pre>';
		//print_r($this->GetAccessories($category_id));
		//exit;
		$accessory_tpl->assign("CAT_NAME",$objCategory->getCategoryname($category_id));
		$monogram=false;
		$accessory_tpl->assign("MONOGRAM",'NO');
		}
	$accessory_tpl->assign("GROUPID",$group_id);
	$accessory_tpl->assign("STORE",$store);
	$accessory_tpl->assign("FOLDER_NAME", $this->GetMenuName('accessory'));
	$accessory_tpl->assign('AVAILABLE_ACCESSORIES', $this->GetAllSelectedAccessory($product_id));
	$content = $accessory_tpl->fetch(FRAMEWORK_PATH."/modules/product/tpl/ajax_accessory.tpl");
	return $content;
	}
	
function DisplayStoreAccessory($store='0',$product_id='0')
	{

	$accessory_tpl = $this->tpl;
	$accessory_tpl->assign("STORE",$store);
	$accessory_tpl->assign("GROUP", $this->AllProductGroups('N'));
	$accessory_tpl->assign("FOLDER_NAME", $this->GetMenuName('accessory'));
	$accessory_tpl->assign("PRODUCT_ID",$product_id);
	$content = $accessory_tpl->fetch(FRAMEWORK_PATH."/modules/product/tpl/ajax_store.tpl");
	return $content;
	}
/**
	The following function added by vimson@newagesmb.com on 04-27-2007 for the 
	purpose of displaying the accessories at the store
**/	
function DisplayAccessoriesForStores($group_id,$category_id,$product_id=0,$store='0')
{
	$accessory_tpl = $this->tpl;
	//echo $store;
	//exit;
	//print_r($this->GetAccessories($category_id));
	//exit;
	$objCategory	=	new Category();
	
	
	$accessory_tpl->assign("GROUPID",$group_id);
	$accessory_tpl->assign("GRP_ACCESSORY",$this->GetAccessories($category_id,$store));
	$accessory_tpl->assign("CAT_NAME",$objCategory->getCategoryname($category_id));
	$accessory_tpl->assign("STORE",$store);
	//  start modified by shinu 26-06-07 for selected accessory of store
	//$accessory_tpl->assign('AVAILABLE_ACCESSORIES', $this->GetAllSelectedAccessory($product_id));
	$accessory_tpl->assign('AVAILABLE_ACCESSORIES', $this->GetAllSelectedAccessoryforStore($product_id,$store));
	//  end modified by shinu 26-06-07 for selected accessory of store
	$content = $accessory_tpl->fetch(FRAMEWORK_PATH."/modules/product/tpl/store_ajax_accessory.tpl");
	return $content;
}
/******** functio to display the selected store with parameter No ***********************/
function GetSelectedStore($store_id,$product_id=0)
	{

	if($product_id>0)

		{
		$qry	=	"
					SELECT 
							PA.category_id,PC.group_id
							 	FROM 	product_availabe_accessory PA,
										product_accessory_group_categories PC,
										product_accessory_group PG
								WHERE 
										PA.product_id		=	".$product_id." AND 
										PA.store_id			=	".$store_id." AND 
										PA.category_id		=	PC.category_id AND
										PC.group_id			=	PG.ID AND
										PG.parameter		=	'N' 
					";
		$rs = $this->db->get_results($qry, ARRAY_A);
		return $rs;
		}
	}	
	
	function getthestoreids($storename) {
		$rs = $this->db->get_row("SELECT * FROM store WHERE name='{$storename}'", ARRAY_A);
		return $rs['id'];
	}
	
/********  function get the accessories of the selected product accessory group  ********/
function GetAccessories($category_id,$store)
	{
	$store_name	=	$_REQUEST['storename'];
	$storeID	=	$this->getthestoreids($store_name);
	$res	=	mysql_query("select * from accessory_notin_store where store_id='$storeID'");
	$exclude_accsry	=	"'0'";
	while($row=	mysql_fetch_array($res))
	{
		$acc_id	=	$row["assessory_id"];
		$exclude_accsry=$exclude_accsry. " , '".$acc_id."'";
	}
	
	//$qry	=	"SELECT *,concat(pa.id,'_',ca.category_id) as new_item_id FROM product_accessories as pa,category_accessory as ca WHERE ca.category_id='$category_id' and ca.accessory_id=pa.id ORDER BY pa.display_name";
	$qry	=	"SELECT *,concat(pa.id,'_',ca.category_id) as new_item_id FROM product_accessories as pa,category_accessory as ca WHERE ca.category_id='$category_id' and ca.accessory_id=pa.id and pa.id NOT IN($exclude_accsry)  ORDER BY pa.display_order";
	$rs = $this->db->get_results($qry, ARRAY_A);
	return $rs;
	
	}
	// retun the display name of product
	function getCMSProductDisplayName()
	{
		$rs	=	$this->db->get_row("SELECT menu FROM module_menu WHERE `mod`='product' AND pg='index' AND  other='act=list' ", ARRAY_A);
		return $rs['menu'];
	}
	// retun the display name of product
	function getCMSAccessoryDisplayName()
	{
		$rs	=	$this->db->get_row("SELECT menu FROM module_menu WHERE `mod`='accessory' AND pg='accessory' AND  other='act=list' ", ARRAY_A);
		return $rs['menu'];
	}
	//adding Product into saved work
	
	function savedWork(&$req,$pro_id=''){	
		extract($req);				
		if ($file){
			$dir			=	SITE_PATH."/modules/product/images/";
			$file1			=	$dir."thumb/";
			$resource_file	=	$dir.$file;
			$path_parts 	= 	pathinfo($file);
		}
		if(!trim(name)){		
			$message 			=	"Product Name";
		}else{		
			if($req['image_name_rear']!="") $rear_image_extension = "jpg";
			else $rear_image_extension = "";
			$array 				= 	array("pro_art_category"=>$pro_art_category,"user_id"=>$user_id,"pro_save_id"=>$pro_id,"parent_id"=>$parent_id,"name"=>$name,"product_type"=>$product_type,"rear_image_extension"=>$rear_image_extension,"description"=>$description,"product_price"=>$price,"sale_price"=>$prices,"price_type"=>$price_type,"xml"=>$xml);
			if($id){			
				$array['id'] 	= 	$id;
				$this->db->update("product_saved_work", $array, "id='$id'");
				$product_id		=	$id;
			}
			else{					
				$this->db->insert("product_saved_work",$array);
				$product_id = $this->db->insert_id;				
			}			
			return $product_id;
		}
		return $message;
	}
	//This is the function for getting saved work with type product
	function listSaveProduct($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy,$user_id){	
		$qry		=	"SELECT * FROM  product_saved_work WHERE user_id=$user_id AND product_type='P'";
		$rs			=	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, ARRAY_A,'','1');
		return $rs;
	}
	//This is the function for getting saved work with type art
	function listSaveArt($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy,$user_id){	
		$qry		=	"SELECT * FROM  product_saved_work WHERE user_id=$user_id AND product_type='A'";
		//$qry		=	"SELECT * FROM  product_saved_work WHERE user_id=$user_id ";
		$rs			=	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, ARRAY_A,'','1');
		return $rs;
	}
	
	//This is the function for getting saved work with type art by vipin
	function listSaveArtlist($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy,$user_id){	
		$qry		=	"SELECT * FROM  product_saved_work WHERE user_id=$user_id AND product_type='A'";
		//$qry		=	"SELECT * FROM  product_saved_work WHERE user_id=$user_id ";
		$rs			=	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output=OBJECT,'1');
		return $rs;
	}
	
	//This is the function for getting saved work with type art
	function listSavedPaddles($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy,$user_id){	
		//$qry		=	"SELECT * FROM  product_saved_work WHERE user_id=$user_id AND product_type='A'";
		$qry		=	"SELECT * FROM  product_saved_work WHERE user_id=$user_id order by id desc";
		$rs			=	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, ARRAY_A,'','1');
		return $rs;
	}
	//for getting saved_work
	function getSavedWork($id){	
		$rs	=	$this->db->get_row("SELECT * FROM product_saved_work WHERE `id`='$id'", ARRAY_A);
		return $rs;
	}
	function getSavedWorkName($id){	
		$rs	=	$this->db->get_row("SELECT * FROM product_saved_work WHERE `pro_save_id`='$id'", ARRAY_A);
		return $rs;
	}
	function GetMenuName($folder)
		{
		
		$qry 	=	"select * from module m where m.folder='".$folder."'";
		$rs	=	$this->db->get_row($qry, ARRAY_A);
		return $rs['name'];
		}
function GetProductGroups($group_id,$storename='',$pageNo=0, $limit = 9, $params='', $output=OBJECT,$orderBy='p.display_order ')
	{
	
	if($storename)
		$qry	=	"SELECT ps.name as groupname,p.* FROM products p, product_settings ps, product_settings_items pi,store AS st,product_store AS ps WHERE p.id=pi.product_id and pi.settings_id=ps.id and ps.right_display='Y' and ps.id='$group_id' and p.id=ps.product_id and ps.store_id=st.id and st.name='".$storename."'";
	else
		$qry	=	"SELECT ps.name as groupname,p.* FROM products p, product_settings ps, product_settings_items pi WHERE p.id=pi.product_id and pi.settings_id=ps.id and ps.right_display='Y' and ps.id='$group_id'";
	//echo $qry;
	
	$rs			=	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, ARRAY_A,$orderBy,'1');
	return $rs;
	}



function GetProductsGroupsOnly( $Dis = "N" )	{
	$qry	=	"SELECT * FROM  product_settings WHERE right_display = '$Dis' ORDER BY display_order";
	$rec			=	$this->db->get_results($qry,ARRAY_A);
	if ($rec)
	return $rec;	
}	

function GetProductsGroupsName( $id = "" )	{
	if ( $id )	{
		$qry	=	"SELECT name FROM  product_settings WHERE id = $id";
		$rec			=	$this->db->get_row($qry,ARRAY_A);
		
		if ($rec)
		return $rec['name'];
		else
		return 'ALL';
	}
}	


function GetProductSettingItemPagewise($groupid=0,$parent_id=0,$show_All='N',$store_name='', $pageNo=0, $limit = 9, $params='', $output=OBJECT,$orderBy='p.display_order')	{
	if ( $groupid > 1 ) {
	
	$qry1	=	"SELECT product_id FROM  product_settings_items WHERE settings_id = $groupid";
	$rec1	=	$this->db->get_results($qry1,ARRAY_A);
	$prod_array = array();
		
	foreach ( $rec1 as $vals )	{
		$prod_array[] = $vals['product_id'];
	}

		if ( count($prod_array) > 0 ) {
			$prod_array_str = implode(",",$prod_array );
	
			$qry	=	"SELECT DISTINCT p.* FROM products AS p WHERE p.id in ($prod_array_str) AND p.parent_id=0 AND p.active='Y' AND p.hide_in_mainstore!='Y'";

			$rs			=	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, ARRAY_A,$orderBy,'1');
			
			return $rs;
		}
	}
}



function GetProductSettingItemRandom($limit=3,$Dis = "Y")	{
			#Getting id with right_display Y	 
			$qry	=	"SELECT * FROM  product_settings WHERE right_display = '$Dis' ORDER BY display_order";
			$rec	=	$this->db->get_results($qry,ARRAY_A);
			$grp_id_array	=	array();

			
			foreach ( $rec as $vals )	{
				$grp_id_array[] = $vals['id'];
			}



			if ( count($grp_id_array) > 0 ) {
	
					$grp_array_str = implode(",",$grp_id_array );
				
					$qry1	=	"SELECT product_id FROM  product_settings_items WHERE settings_id in ($grp_array_str)";
					$rec1	=	$this->db->get_results($qry1,ARRAY_A);
					$prod_array = array();
					
					foreach ( $rec1 as $vals1 )	{
						$prod_array[] = $vals1['product_id'];
					}
				
					
					if ( count($prod_array) > 0 ) {
						$prod_array_str = implode(",",$prod_array );
						$qry	=	"SELECT DISTINCT p.* FROM products AS p WHERE p.id in ($prod_array_str) AND p.parent_id=0 AND p.active='Y' AND p.hide_in_mainstore!='Y' order by rand() limit {$limit}";
						$rs			=	$this->db->get_results($qry);
						return $rs;
					}
			
			}
}

	
	
	
	
	
	
	
	
	
	
function GetProductsAccessories($category_id,$parent_id=0,$store_name='',$pageNo=0, $limit = 9, $params='', $output=OBJECT,$orderBy='')
	{
	$qry		=	" (SELECT DISTINCT p.id as id,p.name as name,p.price as price,p.weight as weight,p.description as description,
				p.image_extension as image_extension,p.active as active,'Y' as product	FROM ";
	if($store_name)
	$qry		.=	" 		store as st, 
							product_store as ps, ";   
	$qry		.=	"  		products AS p,
					   		category_product AS cp,
							master_category AS mc
					  WHERE  ";
	if($store_name)				
	$qry		.=		" 	p.id=ps.product_id AND
							ps.store_id=st.id AND
							st.name='".$store_name."' AND ";				  
					 
	$qry		.=	"   	mc.category_id IN ($category_id) AND
					  		mc.active='y' AND
							mc.is_in_ui='N' AND
							mc.category_id=cp.category_id AND
							cp.product_id=p.id	AND
							p.parent_id='$parent_id'	AND
							p.active='Y' ) UNION ALL
										  		
					";
					
	$qry		.=	"(SELECT DISTINCT pa.id as id,pa.name as name,pa.adjust_price as price,pa.adjust_weight as weight,pa.description as description,
				pa.image_extension as image_extension,pa.active as active,'N' as product	FROM ";
	if($store_name)
	$qry		.=	" 		store as st, 
							store_accessory as sa, ";   
	$qry		.=	"  		product_accessories AS pa,
					   		category_accessory AS ca,
							master_category AS mc
					  WHERE  ";
	if($store_name)				
	$qry		.=		" 	pa.id=sa.accessory_id AND
							sa.store_id=st.id AND
							st.name='".$store_name."' AND ";				  
					 
	$qry		.=	"   	mc.category_id=".$category_id." AND
					  		mc.active='y' AND
							mc.category_id=ca.category_id AND
							ca.accessory_id=pa.id	AND
							pa.active='Y'
							 )				  		
					";	
				//echo $qry;
				$rs			=	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, ARRAY_A,'','1');
				//print_r($rs);
				//exit;		
	}
function IsMonogramCategory($category_id)
	{
	$qry	=	"select category_id from master_category where category_id=$category_id and is_monogram='Y'";
	$rs		=	$this->db->get_row($qry, ARRAY_A);
	if($rs['category_id']>0)
		return true;
	else
		return false;
	}
function RemoveImage($product_id,$field)
	{
	if($product_id>0)
		{
		$extension	=	$this->GetImageExtenstion($product_id,$field);
		switch ($field) 
		{
		case	"image_extension": 	
									$image_path		=	SITE_PATH."/modules/product/images/thumb/".$product_id.".".$extension;
									if(file_exists($image_path))
										{
										unlink($image_path);
										}
									$image_path		=	SITE_PATH."/modules/product/images/".$product_id.".".$extension;
									if(file_exists($image_path))
										{
										unlink($image_path);
										}
									$image_path		=	SITE_PATH."/modules/product/images/thumb/".$product_id."_des_.".$extension;
									if(file_exists($image_path))
										{
										unlink($image_path);
										}
									$image_path		=	SITE_PATH."/modules/product/images/thumb/".$product_id."_List_.".$extension;
									if(file_exists($image_path))
										{
										unlink($image_path);
										}
									$image_path		=	SITE_PATH."/modules/product/images/thumb/".$product_id."_thumb2_.".$extension;
									if(file_exists($image_path))
										{
										unlink($image_path);
										}
									$image_path		=	SITE_PATH."/modules/product/images/thumb/".$product_id."_thumb3_.".$extension;
									if(file_exists($image_path))
										{
										unlink($image_path);
										}

									
									break;
		case	"two_d_image":		$image_path		=	SITE_PATH."/modules/product/images/2D_".$product_id.".".$extension;
									if(file_exists($image_path))
										{
										unlink($image_path);
										}
									break;
		case	"overlay":			$image_path		=	SITE_PATH."/modules/product/images/OV_".$product_id.".".$extension;
									if(file_exists($image_path))
										{
										unlink($image_path);
										}
									$image_path		=	SITE_PATH."/modules/product/images/thumb/OV_".$product_id.".".$extension;
									if(file_exists($image_path))
										{
										unlink($image_path);
										}
									break;
		case	"overlay2":			$image_path		=	SITE_PATH."/modules/product/images/OV2_".$product_id.".".$extension;
									if(file_exists($image_path))
										{
										unlink($image_path);
										}
									$image_path		=	SITE_PATH."/modules/product/images/thumb/OV2_".$product_id.".".$extension;
									if(file_exists($image_path))
										{
										unlink($image_path);
										}
									break;
		case	"overlay3":			$image_path		=	SITE_PATH."/modules/product/images/OV3_".$product_id.".".$extension;
									if(file_exists($image_path))
										{
										unlink($image_path);
										}
									$image_path		=	SITE_PATH."/modules/product/images/thumb/OV3_".$product_id.".".$extension;
									if(file_exists($image_path))
										{
										unlink($image_path);
										}
									break;
		case	"overlay4":			$image_path		=	SITE_PATH."/modules/product/images/OV4_".$product_id.".".$extension;
									if(file_exists($image_path))
										{
										unlink($image_path);
										}
									$image_path		=	SITE_PATH."/modules/product/images/thumb/OV4_".$product_id.".".$extension;
									if(file_exists($image_path))
										{
										unlink($image_path);
										}
									break;
		case	"overlay5":			$image_path		=	SITE_PATH."/modules/product/images/OV5_".$product_id.".".$extension;
									if(file_exists($image_path))
										{
										unlink($image_path);
										}
									$image_path		=	SITE_PATH."/modules/product/images/thumb/OV5_".$product_id.".".$extension;
									if(file_exists($image_path))
										{
										unlink($image_path);
										}
									break;
		
		
		
		}
		$arr	=	array($field=>"");
		$this->db->update("products", $arr, "id='$product_id'");
		return true;
		}
	return false;
	}
function GetImageExtenstion($product_id,$field)
	{
	$qry	=	"select $field as field from products where id=$product_id";
	$rs		=	$this->db->get_row($qry, ARRAY_A);
	if($rs)
		return $rs['field'];
	else
		return 0;
	}	
	
	function getMemberPercent($price)
	{
		if(count($_SESSION)>0)
		{
		$qry_percent = "SELECT a.*,b.* FROM member_master a,member_types b WHERE a.id ='".$_SESSION['memberid']."' AND a.mem_type = b.id";
		$percent = $this->db->get_results($qry_percent);
		//print_r($percent);
		//exit;
			if(($percent[0]->percentage)>0)
			{
			$discount_price = $price*$percent[0]->percentage/100;
			}
			else
			{
			$discount_price = $price;
			}
		return $discount_price;
		}
		else
		{
		return $price;
		}
	}
function CopyOldImagesToNewProduct($old_product_id,$new_product_id,$param)
	{
	
	$oldProduct	=	$this->ProductGet($old_product_id);
	$array	=	array();
	if($oldProduct['image_extension']!='' && $param['image_extension']==1)
		{
		$newarray		=	array("image_extension"	=>	$oldProduct['image_extension']);
		$array			=	array_merge($array,$newarray);
		$Simage_path		=	SITE_PATH."/modules/product/images/thumb/".$old_product_id.".".$oldProduct['image_extension'];
		
		$Dimage_path		=	SITE_PATH."/modules/product/images/thumb/".$new_product_id.".".$oldProduct['image_extension'];
		if(file_exists($Simage_path))
			{
			copy($Simage_path,$Dimage_path);
			}
		$Simage_path		=	SITE_PATH."/modules/product/images/".$old_product_id.".".$oldProduct['image_extension'];
		$Dimage_path		=	SITE_PATH."/modules/product/images/".$new_product_id.".".$oldProduct['image_extension'];
		if(file_exists($Simage_path))
			{
			copy($Simage_path,$Dimage_path);
			}
		$Simage_path		=	SITE_PATH."/modules/product/images/thumb/".$old_product_id."_des_.".$oldProduct['image_extension'];
		$Dimage_path		=	SITE_PATH."/modules/product/images/thumb/".$new_product_id."_des_.".$oldProduct['image_extension'];
		if(file_exists($Simage_path))
			{
			copy($Simage_path,$Dimage_path);
			}
		$Simage_path		=	SITE_PATH."/modules/product/images/thumb/".$old_product_id."_List_.".$oldProduct['image_extension'];
		$Dimage_path		=	SITE_PATH."/modules/product/images/thumb/".$new_product_id."_List_.".$oldProduct['image_extension'];
		if(file_exists($Simage_path))
			{
			copy($Simage_path,$Dimage_path);
			}
		$Simage_path		=	SITE_PATH."/modules/product/images/thumb/".$old_product_id."_thumb2_.".$oldProduct['image_extension'];
		$Dimage_path		=	SITE_PATH."/modules/product/images/thumb/".$new_product_id."_thumb2_.".$oldProduct['image_extension'];
		if(file_exists($Simage_path))
			{
			copy($Simage_path,$Dimage_path);
			}
		$Simage_path		=	SITE_PATH."/modules/product/images/thumb/".$old_product_id."_thumb3_.".$oldProduct['image_extension'];
		$Dimage_path		=	SITE_PATH."/modules/product/images/thumb/".$new_product_id."_thumb3_.".$oldProduct['image_extension'];
		if(file_exists($Simage_path))
			{
			copy($Simage_path,$Dimage_path);
			}
						
		}//if ends
	if($oldProduct['two_d_image']!='' && $param['two_d_image']!=1)
		{
			$newarray		=	array("two_d_image"	=>	$oldProduct['two_d_image']);
			$array			=	array_merge($array,$newarray);
			$Simage_path		=	SITE_PATH."/modules/product/images/2D_".$old_product_id.".".$oldProduct['two_d_image'];
			$Dimage_path		=	SITE_PATH."/modules/product/images/2D_".$new_product_id.".".$oldProduct['two_d_image'];
			if(file_exists($Simage_path))
				{
				copy($Simage_path,$Dimage_path);
				}
		}
	if($oldProduct['overlay']!='' && $param['overlay']!=1)
		{
			$newarray		=	array("overlay"	=>	$oldProduct['overlay']);
			$array			=	array_merge($array,$newarray);
			$Simage_path		=	SITE_PATH."/modules/product/images/OV_".$old_product_id.".".$oldProduct['overlay'];
			$Dimage_path		=	SITE_PATH."/modules/product/images/OV_".$new_product_id.".".$oldProduct['overlay'];
			if(file_exists($Simage_path))
				{
				copy($Simage_path,$Dimage_path);
				}
			$Simage_path		=	SITE_PATH."/modules/product/images/thumb/OV_".$old_product_id.".".$oldProduct['overlay'];
			$Dimage_path		=	SITE_PATH."/modules/product/images/thumb/OV_".$new_product_id.".".$oldProduct['overlay'];
			if(file_exists($Simage_path))
				{
				copy($Simage_path,$Dimage_path);
				}
		}
	if($oldProduct['overlay2']!='' && $param['overlay2']!=1)
		{
			$newarray		=	array("overlay2"	=>	$oldProduct['overlay2']);
			$array			=	array_merge($array,$newarray);
			$Simage_path		=	SITE_PATH."/modules/product/images/OV2_".$old_product_id.".".$oldProduct['overlay2'];
			$Dimage_path		=	SITE_PATH."/modules/product/images/OV2_".$new_product_id.".".$oldProduct['overlay2'];
			if(file_exists($Simage_path))
				{
				copy($Simage_path,$Dimage_path);
				}
			$Simage_path		=	SITE_PATH."/modules/product/images/thumb/OV2_".$old_product_id.".".$oldProduct['overlay2'];
			$Dimage_path		=	SITE_PATH."/modules/product/images/thumb/OV2_".$new_product_id.".".$oldProduct['overlay2'];
			if(file_exists($Simage_path))
				{
				copy($Simage_path,$Dimage_path);
				}
		}
	if($oldProduct['overlay3']!='' && $param['overlay3']!=1)
		{
			$newarray		=	array("overlay3"	=>	$oldProduct['overlay3']);
			$array			=	array_merge($array,$newarray);
			$Simage_path		=	SITE_PATH."/modules/product/images/OV3_".$old_product_id.".".$oldProduct['overlay3'];
			$Dimage_path		=	SITE_PATH."/modules/product/images/OV3_".$new_product_id.".".$oldProduct['overlay3'];
			if(file_exists($Simage_path))
				{
				copy($Simage_path,$Dimage_path);
				}
			$Simage_path		=	SITE_PATH."/modules/product/images/thumb/OV3_".$old_product_id.".".$oldProduct['overlay3'];
			$Dimage_path		=	SITE_PATH."/modules/product/images/thumb/OV3_".$new_product_id.".".$oldProduct['overlay3'];
			if(file_exists($Simage_path))
				{
				copy($Simage_path,$Dimage_path);
				}
		}
	if($oldProduct['overlay4']!='' && $param['overlay4']!=1)
		{
			$newarray		=	array("overlay4"	=>	$oldProduct['overlay4']);
			$array			=	array_merge($array,$newarray);
			$Simage_path		=	SITE_PATH."/modules/product/images/OV4_".$old_product_id.".".$oldProduct['overlay4'];
			$Dimage_path		=	SITE_PATH."/modules/product/images/OV4_".$new_product_id.".".$oldProduct['overlay4'];
			if(file_exists($Simage_path))
				{
				copy($Simage_path,$Dimage_path);
				}
			$Simage_path		=	SITE_PATH."/modules/product/images/thumb/OV4_".$old_product_id.".".$oldProduct['overlay4'];
			$Dimage_path		=	SITE_PATH."/modules/product/images/thumb/OV4_".$new_product_id.".".$oldProduct['overlay4'];
			if(file_exists($Simage_path))
				{
				copy($Simage_path,$Dimage_path);
				}
		}
	if($oldProduct['overlay5']!='' && $param['overlay5']!=1)
		{
			$newarray		=	array("overlay5"	=>	$oldProduct['overlay5']);
			$array			=	array_merge($array,$newarray);
			$Simage_path		=	SITE_PATH."/modules/product/images/OV5_".$old_product_id.".".$oldProduct['overlay5'];
			$Dimage_path		=	SITE_PATH."/modules/product/images/OV5_".$new_product_id.".".$oldProduct['overlay5'];
			if(file_exists($Simage_path))
				{
				copy($Simage_path,$Dimage_path);
				}
			$Simage_path		=	SITE_PATH."/modules/product/images/thumb/OV5_".$old_product_id.".".$oldProduct['overlay5'];
			$Dimage_path		=	SITE_PATH."/modules/product/images/thumb/OV5_".$new_product_id.".".$oldProduct['overlay5'];
			if(file_exists($Simage_path))
				{
				copy($Simage_path,$Dimage_path);
				}
		}
	if(count($array)>0)
		$this->db->update("products", $array, "id='$new_product_id'");
	}
function creteNewProductFrom($old_product_id,$array,$Append='N')
	{
	$arr	=	$oldProduct	=	$this->ProductGet($old_product_id);
	unset($arr['image_extension'],$arr['swf'],$arr['two_d_image'],$arr['overlay'],$arr['overlay2'],
	$arr['overlay3'],$arr['overlay4'],$arr['overlay5'],$arr['pdf_file'],$arr['psd_file'],
	$arr['ai_image'],$arr['id']);
	if(isset($array['brand_id']))
		$arr['brand_id']=$array['brand_id'];
	if(isset($array['thickness']))
		$arr['thickness']=$array['thickness'];
	if(isset($array['weight']))
		$arr['weight']=$array['weight'];
	if(isset($array['description']))
		$arr['description']=$array['description'];
	if(isset($array['active']))
		$arr['active']=$array['active'];
	if(isset($array['display_gross']))
		$arr['display_gross']=$array['display_gross'];
	if(isset($array['display_related']))
		$arr['display_related']=$array['display_related'];
	if(isset($array['price']))
		$arr['price']=$array['price'];
	$new_product_id	=	$this->db->insert("products", $arr);
	//echo "Read from old ID: $old_product_id";
	//print_r($array);
	//print_r($this->ProductGet($new_product_id));
	//echo "<br>";
	$param["overlay5"]='0';
	$param["overlay4"]='0';
	$param["overlay3"]='0';
	$param["overlay2"]='0';
	$param["overlay"]='0';
	$param["two_d_image"]='0';
	$param["image_extension"]='0';
	$this->CopyOldImagesToNewProduct($old_product_id,$new_product_id,$param);
	//print_r($this->ProductGet($new_product_id));
	//echo "<br>";
	if($Append=='Y')
		{
		//inserting old category
		$this->db->query("INSERT INTO category_product(category_id, product_id) 
							   SELECT
									  category_id,$new_product_id as product_id
							     FROM
							    	  category_product 
							    WHERE
							   		  product_id = '$old_product_id'");
		//update special price from old
		$this->db->query("INSERT INTO product_price(type_id, product_id, price, is_percentage) 
							   SELECT
									  type_id, $new_product_id as product_id, price, is_percentage

							     FROM
							    	  product_price 
							    WHERE
							   		  product_id = '$old_product_id'");
		//updating zone
		$this->db->query("INSERT INTO product_made_in(zone_id, product_id) 
							   SELECT
									  zone_id, $new_product_id as product_id
							     FROM
							    	  product_made_in 
							    WHERE
							   		  product_id = '$old_product_id'");
		$this->db->query("INSERT INTO product_availabe_accessory(product_id,category_id,accessory_id,cart_name,group_id,is_price_percentage,
									  adjust_price,is_weight_percentage,adjust_weight) 
							   SELECT
									  $new_product_id as product_id,pa.category_id, pa.accessory_id,pa.cart_name,pa.group_id,pa.is_price_percentage,
									  pa.adjust_price,pa.is_weight_percentage,pa.adjust_weight
							     FROM
							    	  product_availabe_accessory pa LEFT JOIN product_accessory_store ps ON (pa.id=ps.available_id) 
							    WHERE
							   		  pa.product_id = '$old_product_id' AND ps.available_id IS NULL");
		
		
		}
	return $new_product_id;
	}
	
function listAllProductSupply($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy,$store_id='',$product_search="",$catId=''){
		 //$qry		="SELECT DISTINCT (p.id),p.*,ps.supply_id
						//FROM products p
					// LEFT JOIN product_supplier ps ON p.id = ps.product_id";
		 $qry		=	"SELECT DISTINCT (p.id),p.*
		 				FROM products p WHERE p.active='Y'";
		 $rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
function suppluUpdate($req){	
	extract($req);	
	$count_id		=	count($id);	
	$supplier_count	=	count($mass_suppliers);	
		if($count_id>0){
			for($i=0;$i<$count_id;$i++){				
				for($sup=0;$sup<$supplier_count;$sup++){
					$this->db->query("DELETE FROM product_supplier where product_id=".$id[$i]." AND supply_id=".$mass_suppliers[$sup]);
					$this->db->insert("product_supplier", array("supply_id"=>$mass_suppliers[$sup],"product_id"=>$id[$i]));
				}
			}
		}		
		return true;
	}
	## Function to get Accessory name from product_accessories table by giving accessory id.
	## Autho Jipson.
	function GetAccessoryName($id)
	{
		$qry    =	"select name from product_accessories where id=".$id;
		$rs['name'] = $this->db->get_col($qry, 0);
			return $rs;
	} #End function.
	
	
	
	function GetAccessoryExclude($id=0)
		{
			if ( $id > 0 )	{
			
			
				$rs 	=	$this->db->get_row("Select exclude_group from products where id='$id'", ARRAY_A);
				$selIDs['group_id']	=	array();
				$selIDs['group_id']	=	explode ( ",", $rs['exclude_group'] );
				//print_r($selIDs);
				//exit;
				return $selIDs;
				
			}
		}
		
		
		
		
		//==================================
		function GetAccessoryExclude1($id=0)
		{
			if ( $id > 0 )	{
			
			
				$rs 	=	$this->db->get_row("Select exclude_group from products where id='$id'", ARRAY_A);
				$rs = explode ( ",", $rs['exclude_group'] );
				//print_r($rs);
				//exit;
				
				return $rs;
			}
		}
		//===================================
		
		function settingsInsertStore($id,$relatedstore)
	{
	
	    $qry	=	"select * from product_settings_store  where store_id=".$relatedstore." and settings_id=".$id;
		$rs		=	$this->db->get_results($qry,ARRAY_A);
		if(count($rs)==0)
			{
			
			$array 		=	array("settings_id"=>$id,"store_id"=>$relatedstore);
			$this->db->insert("product_settings_store", $array);
			}
			}
	
	function getRelatedStore1($id)
	{
		$sql		=	 "SELECT `store`.`id`,
							`store`.`name`
						FROM
							`store`
						Inner Join `product_settings_store` ON `store`.`id` = `product_settings_store`.`store_id`
						WHERE
							`product_settings_store`.`settings_id` =  '$id'";  

		$rs['id'] 	= 	$this->db->get_col($sql, 0);
		$rs['name'] = 	$this->db->get_col("", 1);
		return $rs;

	}





		
		function getMainStore($id)
		{
		
		$qry	=	"select * from product_settings_store  where store_id='0' and settings_id='$id'";
		$rs		=	$this->db->get_results($qry,ARRAY_A);
		return $rs;
		}
		
		
		
		function storeGetDetails1 ($id,$param='',$store_id=0) {
		if($param=='')
		{
		$sql		= 	"SELECT id, name FROM store WHERE 1 ";
		$rs['id'] 	= 	$this->db->get_col($sql, 0);
		$rs['name'] = 	$this->db->get_col("", 1);
		}
		else

		{
		$sql		= 	"SELECT id, name FROM store ";
		/*if($store_id>0)
			$sql	.=	"WHERE id=$store_id";
		//echo $sql;
		//exit;
		*/
		$rs		=	$this->db->get_results($sql,ARRAY_A);
		/*if(count($arr)>0)
			{
			$i=0;
				foreach ($arr as $row)
					{
					$rs[$i]=$row;
						if($product_id>0)
							{
							//$qry	=	"select * from product_availabe_accessory pa,product_accessory_group_categories pc,product_accessory_group pg where pa.product_id='$product_id' and pa.store_id='".$row['id']."' and pa.category_id=pc.category_id and pc.group_id=pg.id and pg.parameter='N'";
							//$qry	=	"select * from product_availabe_accessory pa,product_accessory_group_categories pc,product_accessory_group pg where pa.product_id='$product_id' and pa.store_id='".$row['id']."' and pa.category_id=pc.category_id and pc.group_id=pg.id and pg.parameter='N'";
		
							$res		=	$this->db->get_results($qry,ARRAY_A);
							if(count($res)>0)
								$rs[$i]['status']	=	'YES';
							else
								$rs[$i]['status']	=	'NO';
							}
						
						$i++;
						}
				}*/
		}
		//exit;
		return $rs;
	}
		
		
		
		
		
		
	function listNewProduct($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy,$store_id='',$product_search="",$catId='')
	{	
	
		global $member_type;
		global $id;
		if($catId>0)
		{
		$categories	=	"'0'";
			$categories	=	$this->getChildCategories($catId,$categories); 
			$categories	= $categories.",'".$catId."'";
			$catRs		=	mysql_query("select product_id from category_product where category_id IN ($categories)");	
			$products	=	"'0'";
			while($catRow=mysql_fetch_array($catRs))
			{	
			$products.=",'".$catRow["product_id"]."'"; }
		}
	
		if($store_id!="") {	
		
		 
				 $qry		="SELECT z.name as Made,  p.*,b.brand_name
							FROM products p
							LEFT JOIN product_made_in m ON p.id = m.product_id
							LEFT JOIN product_zone z ON m.zone_id = z.id
							LEFT JOIN brands b ON p.brand_id = b.brand_id 
							INNER JOIN (product_store as s) ON (s.product_id = p.id) WHERE s.store_id ='$store_id' ";
				 if($product_search!="")
						 $qry.=	"and (p.name  LIKE '%".$product_search."%' or p.description LIKE '%".$product_search."%') ";
						 //echo  $qry;
						 //exit;
				 if($catId>0)
					 	$qry.=	"and p.id IN($products)";
		}else{
				
				 $qry		="SELECT z.name as Made,b.brand_name, p. *
								FROM products p
								LEFT JOIN product_made_in m ON p.id = m.product_id
								LEFT JOIN product_zone z ON m.zone_id = z.id
								LEFT JOIN brands b ON p.brand_id = b.brand_id"; 
					if($member_type==3){
						 $qry= $qry."  INNER JOIN product_supplier ps ON p.id=ps.product_id";
					}
					$qry= $qry."  WHERE";
					
					if(($catId=='' || $catId==0) && $product_search=='')
					 $qry.=	" 1";
					 
				 if($product_search!=""){				 
					 $qry.=	" (p.name LIKE '%".$product_search."%' or p.description LIKE '%".$product_search."%')";
				 }
				 if($member_type==3){
						 $qry= $qry." AND ps.supply_id=$id";
					}
				// echo $catId;
				if($catId>0){		
				 if($product_search!="") {				
				  	 $qry.=	" and ";
				 }
					 $qry.=	" p.id IN($products)";
				 
				 }
				
			}   
			 
		//$qry		=	"select * from products as p left join (brands as b) on (p.brand_id=b.brand_id) where ";
	
		
		//echo $qry;
		//exit;
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		//exit;
		/*if(count($rs[0])>0)
			{
			$arr=array();
			$i=0;
			foreach ($rs[0] as $row)
				{
				$sql	=		"select z.* from product_made_in m, product_zone z where m.product_id=".$row->id." and m.zone_id=z.id" ;
				$arr	=	$this->db->get_row($sql,OBJECT);
				
				
				if($arr>0)
				$rs[0][$i]->Made=$arr->name;
				else
				$rs[0][$i]->Made='';
				$i++;
				}
			}*/
		
		return $rs;
	}
	//by jeffy on 15th oct 2007
	function relatedProductAddEdit(&$req){
		extract($req);
		$this->db->query("DELETE FROM product_related WHERE product_id=$req[id]");
		$this->insertRelated($req,$req[id]);
	}

	function GetArtUserName($id)
	  {
		 $qry 	=	"select a.* from product_saved_work a, member_master b where a.user_id = b.id and a.pro_save_id = $id";
		 $rs	=	$this->db->get_row($qry, ARRAY_A);
		 return $rs['first_name']." ".$rs['last_name'];
	 }	
	 #################### Dec 18 2007 ###################
	/**
    * Showing all the product relate groups
  	* Author   : Shinu
  	* Created  : 18-Dec-2007
  	* Modified : 18-Dec-2007 By Shinu
  	*/
	function listAllRelateGroups($keysearch='N',$name_search='',$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy="1")
	{	
			/*$qry		=	"select DISTINCT(prn.relate_id),prn.* from product_relate_name as  prn 
							 LEFT JOIN products_related as pn ON pn.relate_id = prn.relate_id 
							 ";
			if($keysearch=='Y' && $name_search)
			$qry	.=	" where prn.relate_name LIKE '%$name_search%' ";*/
			/*$qry		=	"select * from product_relate_name  ";
			if($keysearch=='Y' && $name_search)
			$qry	.=	" where relate_name LIKE '%$name_search%' ";*/
			
			
			 $qry	=	"SELECT product_relate_name.relate_name,product_relate_name.relate_id,products_related.relate_id as prd_count, COUNT(*) as count
						 FROM product_relate_name LEFT JOIN products_related
						 ON product_relate_name.relate_id=products_related.relate_id
						 GROUP BY relate_name";
			
			$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		
		
		
		return $rs;
	}
	/**
    * Deleting Product related groups
  	* Author   : Shinu
  	* Created  : 19-Dec-2007
  	* Modified : 19-Dec-2007 By Shinu
  	*/
	function RelateNameDelete($relate_id)
	{
		$qry	=	"delete from product_relate_name where relate_id='$relate_id'";
		$this->db->query($qry);
		$qry2	=	"delete from products_related where relate_id='$relate_id'";
		$this->db->query($qry2);
		return true;
	}
	
	/**
    * Creating a  Product related groups
  	* Author   : Shinu
  	* Created  : 19-Dec-2007
  	* Modified : 19-Dec-2007 By Shinu
  	*/
	function RelateNameAdd($relate_name)
	{
		$Array = array("relate_name"=>$relate_name);
		$this->db->insert("product_relate_name", $Array);
		$relate_id = $this->db->insert_id;
		return $relate_id;
	}
	/**
    * Creating a  adding Product to related groups
  	* Author   : Shinu
  	* Created  : 19-Dec-2007
  	* Modified : 19-Dec-2007 By Shinu
  	*/
	function RelateProductAdd($relate_id,$prd_id)
	{
		$qry2	=	"delete from products_related where relate_id='$relate_id' and product_id='$prd_id'";
		$this->db->query($qry2);
		$Array = array("relate_id"=>$relate_id,"product_id"=>$prd_id);
		$this->db->insert("products_related", $Array);
		return true;
	}
	/**
    * Showing all the product relate groups
  	* Author   : Shinu
  	* Created  : 18-Dec-2007
  	* Modified : 18-Dec-2007 By Shinu
  	*/	
	function listAllRelateGroupItems($relate_id,$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy,$store_id='',$product_search="",$catId='',$bId='',$mId='')
	{	
		$rel_qry	=	"select product_id from products_related where relate_id='$relate_id'";
		$rel_rs 		= 	$this->db->get_results($rel_qry, ARRAY_A);
		$product_ids="'0'";
		for($j=0;$j<count($rel_rs);$j++)
		{
			$rel_prdId	=	$rel_rs[$j]['product_id'];
			$product_ids=$product_ids. " , '".$rel_prdId."'";
		}
		
		global $member_type;
		global $id;
		if($catId>0)
		{
		$categories	=	"'0'";
			$categories	=	$this->getChildCategories($catId,$categories); 
			$categories	= $categories.",'".$catId."'";
			$catRs		=	mysql_query("select product_id from category_product where category_id IN ($categories)");	
			$products	=	"'0'";
			while($catRow=mysql_fetch_array($catRs))
			{	
			$products.=",'".$catRow["product_id"]."'"; }
		}
	
		if($store_id!="") {	
		
		 
				 $qry		="SELECT z.name as Made,  p.*,b.brand_name
							FROM products p
							LEFT JOIN product_made_in m ON p.id = m.product_id
							LEFT JOIN product_zone z ON m.zone_id = z.id
							LEFT JOIN brands b ON p.brand_id = b.brand_id 
							INNER JOIN (product_store as s) ON (s.product_id = p.id) WHERE s.store_id ='$store_id' ";
				 if($product_search!="")
						 $qry.=	"and (p.name  LIKE '%".$product_search."%' or p.display_name  LIKE '%".$product_search."%' or p.description LIKE '%".$product_search."%' or p.cart_name LIKE '%".$product_search."%') ";
						 if($catId>0)
					 	$qry.=	"and p.id IN($products) ";
		}else{
				
				 $qry		="SELECT z.name as Made,b.brand_name, p. *
								FROM products p
								LEFT JOIN product_made_in m ON p.id = m.product_id
								LEFT JOIN product_zone z ON m.zone_id = z.id
								LEFT JOIN brands b ON p.brand_id = b.brand_id"; 
					if($member_type==3){
						 $qry= $qry."  INNER JOIN product_supplier ps ON p.id=ps.product_id";
					}
					$qry= $qry."  WHERE";
					
					if(($catId=='' || $catId==0) && $product_search=='')
					 $qry.=	" 1";
					 
				 if($product_search!=""){				 
					 $qry.=	" (p.name LIKE '%".$product_search."%' or p.display_name  LIKE '%".$product_search."%' or p.description LIKE '%".$product_search."%' or p.cart_name LIKE '%".$product_search."%')";
				 }
				 if($member_type==3){
						 $qry= $qry." AND ps.supply_id=$id";
					}
					 if($bId!=""){
						    if($catId>0){
							  $qry= $qry."  p.brand_id=$bId AND";
							 }
							 else{
							   $qry= $qry." AND p.brand_id=$bId";
							  }
							}  
					  if($mId!=""){
						    if($catId>0){
							  $qry= $qry."  m.zone_id=$mId AND";
							 }
							 else{
							   $qry= $qry." AND m.zone_id=$mId";
							  }		  
							  
					}
				if($catId>0){		
				 if($product_search!="") {				
				  	 $qry.=	" and ";
				 }
				 
					 $qry.=	" p.id IN($products)";
				 
				 }
				 $qry.=	" AND p.hide_in_mainstore='N' 
				          AND p.id IN($product_ids)";
				
			}   
						
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		
	
		
		return $rs;
	}
	/**
    * Deleting Product related groups
  	* Author   : Shinu
  	* Created  : 19-Dec-2007
  	* Modified : 19-Dec-2007 By Shinu
  	*/
	function RelateProductDelete($relate_id,$prd_id)
	{
		$qry2	=	"delete from products_related where relate_id='$relate_id' AND product_id='$prd_id'";
		$this->db->query($qry2);
		return true;
	}
	
	/**
    * Get product save id from product_saved_work
  	* Author   : Jeffy
  	* Created  : 28-Jan-2008
  	*/
	function productSavedId($id)
	{
		$qry2	=	"SELECT * FROM product_saved_work WHERE pro_save_id = $id";
		$rs1		=	$this->db->get_results($qry2,ARRAY_A);
		return $rs1;
	}
	
	function GetFeaturedItemsToDisplay($store_name='')
	{
	$rs2		= 	array();
	$qry		=	"SELECT * FROM `product_settings` where right_display='Y'";
	$row 		= 	$this->db->get_row($qry,ARRAY_A);
	$qry1		=	"SELECT p.* FROM ";
	if($store_name)
	$qry1		.=	" store as st, 
						product_store as ps , ";
						
	$qry1		.=	" product_settings_items as pi, 
						products as p					
					where ";
	if($store_name)				
	$qry1		.=		" p.id=ps.product_id and
						ps.store_id=st.id and
						st.name='".$store_name."' and ";
						
	$qry1		.=		" p.id=pi.product_id and
						p.active='Y' and 
						pi.settings_id=".$row['id'];
					
	$rs1		=	$this->db->get_results($qry1,ARRAY_A);
	
	return $rs1;
	}
	
	//for Delete saved_work
	function delSavedWork($id,$user_id){	
		$rs	=	$this->db->query("DELETE FROM product_saved_work WHERE `id`='$id' AND user_id=$user_id");
		return $rs;
	}
	####################end  Dec 18 2007 ###################
	
	function listProductViewItems($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy)
	{
		$qry		=	"select a.*,b.name,b.display_name,b.id as ProdID,b.image_extension from page_hit a,products b where a.page_user=b.id";
		//echo $qry;
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		//print_r($rs);
		return $rs;
	}
	function listCartItems($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy)
	{
		$qry		=	"select  count(user_id) as Num,a.id as cartID,a.*,b.username,b.id,c.product_id as pid,c.display_name from cart a,member_master b,products c where a.user_id!='' and a.product_id=c.id and a.user_id=b.id GROUP BY  a.user_id";
	
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		//print_r($rs);
		return $rs;
	}
	

	
	function listUserCartItems($userid,$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy)
	{
	
		$qry		=	"select  a.id as cartID,a.*,b.username,b.id,c.product_id as pid,c.display_name,c.price,c.description,c.image_extension  from cart a,member_master b,products c where a.user_id!='' and a.product_id=c.id and a.user_id=b.id  and a.user_id=".$userid;
		//echo $qry;
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		//print_r($rs);
		return $rs;
	}
	function ShopProductGet($id,$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy)
	{
		if($id>0)
		{
		
		    $sql="select A.*, B.*,C.id as acc_id,C.*  from cart A,cart_accessory B,product_accessories C where A.id=B.cart_id and A.id=".$id."  and B.accessory_id=C.id ";   
			$rs 		= 	$this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		//echo $sql;
		//print_r($rs);
		return $rs;
		}
	}
	
	function updateProd(&$req){
		extract($req);
		$array 				= 	array("name"=>$name,"display_name"=>$name,"description"=>$description);
		$this->db->update("products", $array, "id='$req[pro_saveid]'");
		return $req[pro_saveid];
	}
	function updateArtProd(&$req){
		extract($req);
		$array 				= 	array("name"=>$name,"description"=>$description,"main_store"=>$main_store,"own_store"=>$own_store,"xml"=>$xml);
		$this->db->update("product_saved_work", $array, "id='$req[product_saved_work_id]'");
		return $req[product_saved_work_id];
	}
	/**
    * Getting whether the Fields are viewable at store manage 
  	* Author   : Salim
  	* Created  : 08-Apr-2008
  	* Modified : 
  	*/
	function getStoreEditable($menu_id='')
	{
		if($menu_id)
		{
			$qry		=	"SELECT * FROM module_fields WHERE menu_id=".$menu_id." AND active = 'Y' order by display_order";
			$rs		 	= 	$this->db->get_results($qry,ARRAY_A);
			//print_r($rs);exit;
			return $rs;
		}
		return false;
	}
	/**
    * Updating the manage_store field in module_fields table
  	* Author   : Salim
  	* Created  : 9-Apr-2008
  	* Modified : 
  	*/
	function updateStoreEditable($field_arr,$menu_id)
	{
		$id	=	$this->db->update("module_fields", $field_arr, "id='$menu_id'");
		return true;
	}
	/**
    * Getting the value of the products if the POST array has value NULL
  	* Author   : Salim
  	* Created  : 23-Apr-2008
  	* Modified : 
  	*/
	function getvalueifpostnull($product_id,$field)
	{
		$sql	=	"SELECT $field FROM products WHERE id = $product_id";
		$rs		=	$this->db->get_col($sql,0);
		$retval	=	$rs[0];
		return $retval;
	}
	
	/**
    * Adding, Updating, Deleting,Searching Inventry csv.
    * (10 methods below this)
  	* Author   : Salim
  	* Created  : 30-Apr-2008
  	* Modified : 20-May-2008
  	*/
	function checkUniqueKeyExists($key_field)
	{
		$sql	=	"SELECT * FROM inventry";
		$rs		=	$this->db->get_col($sql,0);
		return $rs;
	}
	
	function addInventry($arr)
	{
		$this->db->insert("inventry", $arr);
	}

	function updateInventry($arr,$part_num)
	{		
		$this->db->update("inventry", $arr, "part_num='$part_num'");
	}
	
	function inventryLog($arr)
	{
		$this->db->insert("inventry_log", $arr);
	}
	
	function getInventryLog()
	{
		$qry	=	"SELECT * FROM inventry_log ORDER BY time_added DESC LIMIT 0,10";
		$rs		=	$this->db->get_results($qry,ARRAY_A);
		return $rs;
	}
	
		
	function searchInventry($array,$type,$pageNo, $limit, $params='', $output, $orderBy)
	{


		$sql	=	"SELECT * FROM inventry WHERE part_num LIKE '%".$array['partno']."%'";
		if($array['modelno']){
			$sql	=	$sql." OR model_num LIKE '%".$array['modelno']."%'";	
		}
		if($array['craftype']){
			$sql	=	$sql." OR aircraft_type LIKE '%".$array['craftype']."%'";
		}
//		echo $sql;
		$rs 		= 	$this->db->get_results_pagewise($sql, $pageNo, $limit, $params, ARRAY_A,'', '1');
		return $rs;
	}
	
	function getInventryList($pageNo,$limit,$params,$orderBy)
	{
	 	$qry	=	"SELECT * FROM inventry";
		$rs	=	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, ARRAY_A,'', $orderBy);
		return $rs;
	}
	
	function getOneInventryByPartNumber($part_num)
	{
		$sql	=	"SELECT * FROM inventry WHERE part_num = '$part_num'";
		$rs		=	$this->db->get_row($sql,ARRAY_A);
		return $rs;
	}
	
	function inventryDelete($inv_id)
	{
		$sql	=	"DELETE FROM inventry WHERE part_num = ".$inv_id;
			if($this->db->query($sql) == '1'){
				return true;	
			}
			else {
				return false;
			}
	}
	
	function getInventryListBySearch($pageNo,$limit,$params,$orderBy,$search_tag)
	{
	 	$qry	=	"SELECT * FROM inventry WHERE part_num LIKE'%".$search_tag."%'";
		$rs	=	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, ARRAY_A,'', $orderBy);
		return $rs;
	}
	
	
	function getSeoProductDetails($seo_item, $store_name='')
	{
		if ($store_name=='')
		{
			$sql = "select a.*,b.category_id from products a inner join category_product b
			    on a.id=b.product_id where a.seo_name='$seo_item'";
			$rs = $this->db->get_row($sql,ARRAY_A);
		}
		else 
		{
			$sql = "select id from products where seo_name='$seo_item'";
			$rs = $this->db->get_row($sql,ARRAY_A);
			$product_id = $rs['id'];
			
			$sql = "select id from store where name='$store_name'";
			$rs = $this->db->get_row($sql,ARRAY_A);
			$store_id = $rs['id'];
			
			
			$sql = "select a.*,b.category_id from (products a inner join category_product b
			    on a.id=b.product_id) inner join product_store c on a.id=c.product_id  
			    where a.seo_name='$seo_item' and c.store_id=$store_id";
			$rs = $this->db->get_row($sql,ARRAY_A);
			if (count($rs)==0)
			{
				$sql = "select a.*,b.category_id from products a inner join category_product b
				    on a.id=b.product_id where a.seo_name='$seo_item'";
				$rs = $this->db->get_row($sql,ARRAY_A);
			}
				
			
		}
		
		return $rs;
		
	}
	
	function changeActive($current_act,$id)
	{
		if ($current_act=='Y')
		{
			$this->db->query("update products set `active`='N' WHERE id=$id");
			return true;
		}
		else
		{
			$this->db->query("update products set `active`='Y' WHERE id=$id");
			return true;
		}
	}
	
	function chkSeoNameExists($str,$id='')
	{
	 
		$sql="select id from products where seo_name ='$str' and hide_in_mainstore='N'";
		if($id!=''){
		$sql.=" and id !='$id'";
		}
		
		$rs = $this->db->get_row($sql,ARRAY_A);
		if(count($rs)>0)
			return true;
		else
			return false;
	}
	
	
	function GetFeaturedItems3($store_name='')
	{
	global $store_id;
	$rs2		= 	array();
	$qry		=	"SELECT * FROM `product_settings` where right_display='Y'";
	$row 		= 	$this->db->get_row($qry,ARRAY_A);
	
	if($store_name)
	{
		
		 $qry1	="SELECT z.name as Made,p.id,p.name,p.image_extension,p.description,p.price,'gift',po.position ,b.brand_name,'' as category
				  FROM products p  LEFT JOIN  products_dispaly_order po ON po.product_id=p.id and po.p_type='gift' and  po.store_id='$store_id' 
				  LEFT JOIN product_made_in m ON p.id = m.product_id
				  LEFT JOIN product_zone z ON m.zone_id = z.id
				  
				  LEFT JOIN brands b ON p.brand_id = b.brand_id 
				  
				  
				  INNER JOIN (product_store as s) ON (s.product_id = p.id) 
				  WHERE s.store_id ='$store_id' and  p.active='Y' AND po.display_status='Y'";
				  
	$qry1		.=" UNION ";			   
				   
	$qry1		.=	"(SELECT '', pp.id,pp.product_title as name,'jpg',pp.product_description as description,
					pp.product_basic_price as price,'pgift',pd.position,'',pp.category
					FROM product_predefined as pp LEFT JOIN category_predefined_product cp ON cp.product_id=pp.id
					INNER JOIN  products_dispaly_order pd ON pd.product_id=pp.id and pd.p_type='pgift' and  pd.store_id='$store_id' 
					LEFT JOIN product_predefined_store ps ON ps.product_id = pp.id 
					WHERE   ps.active='Y' AND pd.display_status='Y')";
					
	$qry1		.=" order by  8 limit 8 ";					
				   
							
	}
	else{
	
	$qry1		=	"(SELECT p.id,name,p.image_extension,p.description,pc.category_id as category ,p.price,'gift',po.position   FROM ";
	$qry1		.=	" product_settings_items as pi, 
					products as p LEFT JOIN  products_dispaly_order po ON po.product_id=p.id and po.p_type='gift' and  po.store_id='$store_id' 	,category_product pc	
						
					where ";
		$qry1 .= " p.id=pi.product_id and p.id=pc.product_id and
				   p.active='Y' and 
				   pi.settings_id=".$row['id']."  
				   and p.parent_id=0 AND po.display_status='Y') ";
				   
	$qry1		.=" UNION ";			   
				   
	$qry1		.=	"(SELECT pp.id,pp.product_title as name,'jpg',pp.product_description as description,
					pp.category as category , pp.product_basic_price as price,'pgift',pd.position
					FROM product_predefined as pp LEFT JOIN category_predefined_product cp ON cp.product_id=pp.id
					INNER JOIN  products_dispaly_order pd ON pd.product_id=pp.id and pd.p_type='pgift' and  pd.store_id=0 
					WHERE pp.store_id=0 and pp.product_active='Y' AND pd.display_status='Y')
					
					order by 8 ";			   
				   
	}	
	
	//echo $qry1;
	
					
	$rs1		=	$this->db->get_results($qry1,ARRAY_A);
	
		if($store_id>0)
		{
			
			
			for($i=0;$i<count($rs1);$i++)
			{
				$rsStoe=$this->findStoreProductArray($store_id,$rs1[$i][id]);
				if(count($rsStoe)>0)
				{
					$rs1[$i]=$rsStoe[0];
				}
				
				if($rs1[$i][parent_id])
				{
					$productdet=$this->ProductGet($rs1[$i][parent_id]);
					$rs1[$i]['description']=$productdet['description'];
				}	
				
				
			}
		}
			
			//print_r($rs1);exit;
	return $rs1;
	}
	
	function GetFeaturedItems2($store_name='')
	{
	global $store_id;
	$rs2		= 	array();
	$qry		=	"SELECT * FROM `product_settings` where right_display='Y'";
	$row 		= 	$this->db->get_row($qry,ARRAY_A);
	
	if($store_name)
	{
		
		 $qry1	="SELECT z.name as Made,  p.*,b.brand_name
				  FROM products p
				  LEFT JOIN product_made_in m ON p.id = m.product_id
				  LEFT JOIN product_zone z ON m.zone_id = z.id
				  
				  LEFT JOIN brands b ON p.brand_id = b.brand_id 
				  
				  
				  INNER JOIN (product_store as s) ON (s.product_id = p.id) 
				  WHERE s.store_id ='$store_id' and  p.active='Y' 
				  AND p.custom_product <> 'Y'
				  group  by p.name   order by p.display_order ";
				   
							
	}
	else{
	
	$qry1		=	"SELECT p.*,pc.category_id FROM ";
	$qry1		.=	" product_settings_items as pi, 
					products as p,category_product pc					
					where ";
		$qry1 .= " p.id=pi.product_id and p.id=pc.product_id and
				   p.active='Y' and 
				   pi.settings_id=".$row['id']."  
				   AND p.custom_product <> 'Y'
				   and p.parent_id=0 order by p.display_order limit 0,8";
	}				
	//echo $qry1;
					
	$rs1		=	$this->db->get_results($qry1,ARRAY_A);
	
		if($store_id>0)
		{
			
			
			for($i=0;$i<count($rs1);$i++)
			{
				$rsStoe=$this->findStoreProductArray($store_id,$rs1[$i][id]);
				if(count($rsStoe)>0)
				{
					$rs1[$i]=$rsStoe[0];
				}
				
				if($rs1[$i][parent_id])
				{
					$productdet=$this->ProductGet($rs1[$i][parent_id]);
					$rs1[$i]['description']=$productdet['description'];
				}	
				
				
			}
		}
			
			//print_r($rs1);exit;
	return $rs1;
	}
	

function getAccessorycount($category_id)
	{
		$qry = "SELECT count(*)  as acc_cnt  FROM product_accessories pa inner join category_accessory ca on pa.id=ca.accessory_id  WHERE ca.category_id='{$category_id}' and pa.active='Y'";
		$rs	=	$this->db->get_row($qry, ARRAY_A);
		return $rs['acc_cnt'];
	}
	
	function getAllProductsByStore($store_id='')
	{
			
		global $member_type;
		global $id;
		if($catId>0)
		{
		$categories	=	"'0'";
			$categories	=	$this->getChildCategories($catId,$categories); 
			$categories	= $categories.",'".$catId."'";
			$catRs		=	mysql_query("select product_id from category_product where category_id IN ($categories)");	
			$products	=	"'0'";
			while($catRow=mysql_fetch_array($catRs))
			{	
			$products.=",'".$catRow["product_id"]."'"; }
		}
	
		if($store_id!="") {	
		
		 
				 $qry		="SELECT z.name as Made,  p.*,b.brand_name
							FROM products p
							LEFT JOIN product_made_in m ON p.id = m.product_id
							LEFT JOIN product_zone z ON m.zone_id = z.id
							LEFT JOIN brands b ON p.brand_id = b.brand_id 
							INNER JOIN (product_store as s) ON (s.product_id = p.id) WHERE s.store_id ='$store_id' ";
				 if($product_search!="")
						 $qry.=	"and (p.name  LIKE '%".$product_search."%' or p.display_name  LIKE '%".$product_search."%' or p.description LIKE '%".$product_search."%' or p.cart_name LIKE '%".$product_search."%') ";
						// echo  $qry;
						// exit;
				 if($catId>0)
					 	$qry.=	"and p.id IN($products) ";
		}else{
				
				 $qry		="SELECT z.name as Made,b.brand_name, p. *
								FROM products p
								LEFT JOIN product_made_in m ON p.id = m.product_id
								LEFT JOIN product_zone z ON m.zone_id = z.id
								LEFT JOIN brands b ON p.brand_id = b.brand_id"; 
					if($member_type==3){
						 $qry= $qry."  INNER JOIN product_supplier ps ON p.id=ps.product_id";
					}
					$qry= $qry."  WHERE";
					
					if(($catId=='' || $catId==0) && $product_search=='')
					 $qry.=	" 1";
					 
				 if($product_search!=""){				 
					 $qry.=	" (p.name LIKE '%".$product_search."%' or p.display_name  LIKE '%".$product_search."%' or p.description LIKE '%".$product_search."%' or p.cart_name LIKE '%".$product_search."%')";
				 }
				 if($member_type==3){
						 $qry= $qry." AND ps.supply_id=$id";
					}
					 if($bId!=""){
						    if($catId>0){
							  $qry= $qry."  p.brand_id=$bId AND";
							 }
							 else{
							   $qry= $qry." AND p.brand_id=$bId";
							  }
							}  
					  if($mId!=""){
						    if($catId>0){
							  $qry= $qry."  m.zone_id=$mId AND";
							 }
							 else{
							   $qry= $qry." AND m.zone_id=$mId";
							  }		  
							  
					}
				// echo $catId;
				if($catId>0){		
				 if($product_search!="") {				
				  	 $qry.=	" and ";
				 }
				 
					 $qry.=	" p.id IN($products)";
				 
				 }
				 $qry.=	" AND p.hide_in_mainstore='N'";
				
			}   
		$rs 		= 	$this->db->get_results($qry);
		return $rs;
		
	}
	
	function insertPredefinedProduct(&$req)
	{
		$this->db->insert("product_predefined", $req);
		 $id  = 	$this->db->insert_id;
		 return $id;
		 
	}
	
	function updatePredefinedProduct(&$req,$id)
	{
		$this->db->update("product_predefined", $req, "id='$id'");
	}
	
	function getPredefinedGiftCategory()
	{
		$qry = "select * from product_predefined_category ";
		$rs  =  $this->db->get_results($qry);
		return $rs;
		
	}
	
	function getPredefinedGift_detail($store_id='',$cat_id='',$product_id)
	{
		$objAccessory=	new Accessory();
		$qry = "select pp.*,mc.category_name,p.name,ps.active  from product_predefined pp inner join master_category mc on mc.category_id=pp.category inner join products p on p.id=pp.product_id inner join product_predefined_store ps on ps.product_id=pp.id  where 1  and pp.id = $product_id ";
		$qry.= " and  ps.active='Y' ";
		if($store_id != '')
		$qry.= " and  ps.store_id=$store_id";
		
		if($cat_id != '')
			$qry.= " and   pp.category=$cat_id";
		
		$rs  =  $this->db->get_results($qry,ARRAY_A);
		
		
		$res = $objAccessory->GetAccessory($rs[0]['art_id']);
		$rs[0]['acc_art'] = $res;
		
		$res = $objAccessory->GetAccessory($rs[0]['mat_id']);
		$rs[0]['acc_mat'] = $res;
		
		
		$res = $objAccessory->GetAccessory($rs[0]['frame_id']);
		$rs[0]['acc_frame'] = $res;
		$res = $objAccessory->GetAccessory($rs[0]['poem_id']);
		$rs[0]['acc_poem'] = $res;
			
		return $rs;
	}
	
	function getPredefinedGiftList($pageNo=0, $limit = 10, $params='', $output,$orderBy,$store_id='',$cat_id='',$stxt='',$flag=0)
	{
		
		if(!$orderBy)
		$orderBy = 'pp.product_title';
	
		$objAccessory=	new Accessory();
	
		 if($store_id)
		 {
	 	 $qry = "select pp.*,mc.category_name,p.name,ps.active  from product_predefined pp inner join master_category mc on mc.category_id=pp.category inner join products p on p.id=pp.product_id inner join product_predefined_store ps on ps.product_id=pp.id  where 1 and  ps.store_id=$store_id ";
		 $qry.= " and pp.product_active='Y' ";	
			if($flag==1)
		 	{
		 		 $qry.= " and  ps.active='Y' ";
		 	}
		 
		 }
		 else
		 {
		  $qry = "select pp.*,mc.category_name,p.name,pp.product_active as active from product_predefined pp inner join master_category mc on mc.category_id=pp.category inner join products p on p.id=pp.product_id  where 1 and  pp.store_id=0  ";
		  
		  	if($flag==1)
		 	{
		 		 $qry.= " and pp.product_active='Y' ";
		 	}
		  }
		 
		/* if($store_id)
		  $qry.= " and   pp.store_id=$store_id";*/
		 
		 if($cat_id !='')
		 {
		 	 $qry.= " and   pp.category=$cat_id";
		 }
		 
		
		 
		
		 if($stxt !='')	
		 {
		 	 $qry.= " and   pp.product_title like '%$stxt%' or product_description like  '%$stxt%'  ";
		 }
		
		//echo $qry;
		
		
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		
		
		foreach($rs[0] as $key=>$val){
			$res = $objAccessory->GetAccessory($val['mat_id']);
			$rs[0][$key]['acc_mat'] = $res;
			$res = $objAccessory->GetAccessory($val['art_id']);
			$rs[0][$key]['acc_art'] = $res;
			$res = $objAccessory->GetAccessory($val['frame_id']);
			$rs[0][$key]['acc_frame'] = $res;
			$res = $objAccessory->GetAccessory($val['poem_id']);
			$rs[0][$key]['acc_poem'] = $res;
			
			if($val['product_sale_price'] >0 ){
			
				$rs[0][$key]['discount_price'] = $val['product_sale_price'];
			}
			else{
				$rs[0][$key]['discount_price'] = $val['product_basic_price'];
			}
			
		}
		
		
		
		
		return $rs;
	}
	
	
	
	
	function PredefinedGiftDelete($id)
	{
		$this->db->query("DELETE FROM product_predefined WHERE id='$id'");
	}
	
	function PredefinedGiftDeleteStore($id,$store_id)
	{
		$this->db->query("DELETE FROM product_predefined_store WHERE product_id='$id' and store_id='$store_id'");
		$this->db->query("DELETE FROM product_predefined WHERE id='$id' and store_id='$store_id'");
	}
	
	function getPredefinedGiftByStore($sid,$id)
	{
		$qry= "select * from product_predefined where store_id='$sid' and id='$id' ";
		$rs	=	$this->db->get_row($qry,ARRAY_A);	
		return $rs;
	}
	
	function getPredefinedGiftDetails($id)
	{
		
		$objAccessory=	new Accessory();
		
	 	 $qry = "select pp.*,mc.category_name,p.name from product_predefined pp inner join master_category mc on mc.category_id=pp.category inner join products p on p.id=pp.product_id where  pp.id='$id'  ";
		
		$rs	=	$this->db->get_row($qry,ARRAY_A);
		
		$res = $objAccessory->GetAccessory($rs['mat_id']);
		$rs['acc_mat'] = $res;
		$res = $objAccessory->GetAccessory($rs['art_id']);
		$rs['acc_art'] = $res;
		$res = $objAccessory->GetAccessory($rs['frame_id']);
		$rs['acc_frame'] = $res;
		$res = $objAccessory->GetAccessory($rs['poem_id']);
		$rs['acc_poem'] = $res;
		
		return $rs;
	}
	
	function changePredefinedGiftStatus($current_act,$id)
	{
		if ($current_act=='Y')
		{
			$this->db->query("update product_predefined set `product_active`='N' WHERE id=$id");
			$this->db->query("update product_predefined_store set `active`='N' WHERE product_id=$id ");
			return true;
		}
		else
		{
			$this->db->query("update product_predefined set `product_active`='Y' WHERE id=$id");
			$this->db->query("update product_predefined_store set `active`='Y' WHERE product_id=$id ");
			return true;
		}
	}
	
	function changePredefinedGiftStatusStore($current_act,$id,$store_id)
	{
		
		if ($current_act=='Y')
		{
			$this->db->query("update product_predefined_store set `active`='N' WHERE product_id=$id and store_id=$store_id");
			return true;
		}
		else
		{
			$this->db->query("update product_predefined_store set `active`='Y' WHERE product_id=$id and store_id=$store_id");
			return true;
		}
	}
	
	
	
	 function sortPredefinedGifts($order,$store_id,$p_type,$display_status) {
	// print_r($order);print_r($store_id);print_r($p_type);exit;
	 	$this->db->query("DELETE FROM products_dispaly_order WHERE store_id='$store_id'");
		
    	if($order) {
    		foreach ($order as $pos=>$id) {
    			$this->db->insert("products_dispaly_order", array("position"=>$pos,"store_id"=>$store_id,"product_id"=>$id,"p_type"=>$p_type[$id],"display_status"=>$display_status[$id]));
    		}
    	}
    }

	function getPredefinedGiftList_1($store_id,$flag=1)
	{
			
		if($store_id!="") {	
		
			$qry		="(SELECT p.id,p.name,p.price,'gift',o.position,o.display_status,o.product_id,o.p_type,o.store_id,IFNULL(o.position, 9) as pos,p.custom_product
							FROM products p
							LEFT JOIN product_made_in m ON p.id = m.product_id
							LEFT JOIN product_zone z ON m.zone_id = z.id
							LEFT JOIN brands b ON p.brand_id = b.brand_id 
							LEFT JOIN products_dispaly_order o ON o.product_id = p.id 
							and o.p_type='gift' and o.store_id= '$store_id'
							INNER JOIN (product_store as s) ON (s.product_id = p.id) WHERE s.store_id ='$store_id' and p.active='Y')
							UNION 
							(SELECT pp.id ,pp.product_title as name,pp.product_sale_price as price,'pgift',op.position,op.display_status,op.product_id,op.p_type,op.store_id,IFNULL(op.position, 9) as pos,'' 
							FROM product_predefined pp
							LEFT JOIN product_predefined_store ps ON ps.product_id = pp.id 
							LEFT JOIN products_dispaly_order op ON op.product_id = pp.id 
							and op.p_type='pgift' and op.store_id= '$store_id'
							where ps.store_id='$store_id' and ps.active='Y') 
							ORDER BY pos	 ";
		}					
		else {
		
				$qry		= " (SELECT p.id,p.name,p.price,'gift',o.position,o.display_status,o.product_id,o.p_type,o.store_id,IFNULL(o.position, 9) as pos,p.custom_product
								FROM products p
								LEFT JOIN product_made_in m ON p.id = m.product_id
								LEFT JOIN product_zone z ON m.zone_id = z.id
								LEFT JOIN products_dispaly_order o ON o.product_id = p.id and o.p_type='gift'
								and o.store_id= '$store_id'
								LEFT JOIN brands b ON p.brand_id = b.brand_id WHERE 1 AND p.hide_in_mainstore='N')
								
								
								UNION 
								(SELECT pp.id ,pp.product_title as name,pp.product_sale_price as price,'pgift',op.position,op.display_status,op.product_id,op.p_type,op.store_id,IFNULL(op.position, 9) as pos,'' 
								FROM product_predefined pp
								LEFT JOIN products_dispaly_order op ON op.product_id = pp.id and
								op.p_type='pgift' and op.store_id= '$store_id'
								where pp.store_id='$store_id' and  pp.product_active='Y') 
								ORDER BY pos 	";
				
		
		}
			
		//echo $qry;				 
			 
	
		$rs  =  $this->db->get_results($qry,ARRAY_A);
		
		
		return $rs;
	}
	
	
	
	function getAllProductsByStoreId($store_id='')
	{
			
		global $member_type;
		global $id;
		if($catId>0)
		{
		$categories	=	"'0'";
			$categories	=	$this->getChildCategories($catId,$categories); 
			$categories	= $categories.",'".$catId."'";
			$catRs		=	mysql_query("select product_id from category_product where category_id IN ($categories)");	
			$products	=	"'0'";
			while($catRow=mysql_fetch_array($catRs))
			{	
			$products.=",'".$catRow["product_id"]."'"; }
		}
	
		if($store_id!="") {	
		
		 
				 $qry		="SELECT z.name as Made,  p.*,b.brand_name
							FROM products p
							LEFT JOIN product_made_in m ON p.id = m.product_id
							LEFT JOIN product_zone z ON m.zone_id = z.id
							LEFT JOIN brands b ON p.brand_id = b.brand_id 
							INNER JOIN (product_store as s) ON (s.product_id = p.id) WHERE s.store_id ='$store_id' ";
				 if($product_search!="")
						 $qry.=	"and (p.name  LIKE '%".$product_search."%' or p.display_name  LIKE '%".$product_search."%' or p.description LIKE '%".$product_search."%' or p.cart_name LIKE '%".$product_search."%') ";
						// echo  $qry;
						// exit;
				 if($catId>0)
					 	$qry.=	"and p.id IN($products) ";
		}else{
				
				 $qry		="SELECT z.name as Made,b.brand_name, p. *
								FROM products p
								LEFT JOIN product_made_in m ON p.id = m.product_id
								LEFT JOIN product_zone z ON m.zone_id = z.id
								LEFT JOIN brands b ON p.brand_id = b.brand_id"; 
					if($member_type==3){
						 $qry= $qry."  INNER JOIN product_supplier ps ON p.id=ps.product_id";
					}
					$qry= $qry."  WHERE";
					
					if(($catId=='' || $catId==0) && $product_search=='')
					 $qry.=	" 1";
					 
				 if($product_search!=""){				 
					 $qry.=	" (p.name LIKE '%".$product_search."%' or p.display_name  LIKE '%".$product_search."%' or p.description LIKE '%".$product_search."%' or p.cart_name LIKE '%".$product_search."%')";
				 }
				 if($member_type==3){
						 $qry= $qry." AND ps.supply_id=$id";
					}
					 if($bId!=""){
						    if($catId>0){
							  $qry= $qry."  p.brand_id=$bId AND";
							 }
							 else{
							   $qry= $qry." AND p.brand_id=$bId";
							  }
							}  
					  if($mId!=""){
						    if($catId>0){
							  $qry= $qry."  m.zone_id=$mId AND";
							 }
							 else{
							   $qry= $qry." AND m.zone_id=$mId";
							  }		  
							  
					}
				// echo $catId;
				if($catId>0){		
				 if($product_search!="") {				
				  	 $qry.=	" and ";
				 }
				 
					 $qry.=	" p.id IN($products)";
				 
				 }
				 $qry.=	" AND p.hide_in_mainstore='N'";
				 $qry.=	" AND p.predefined_gift_dispaly='Y'";
				
			}   
		$rs 		= 	$this->db->get_results($qry);
		return $rs;
		
	}
	function changeDispalyStatus($stat,$product_id,$p_type,$store_id)
	{
		if($stat == 'Y')
			$stat	=	'N';
		else
			$stat	=	'Y';
			
		$arr 	=	array("display_status"=>$stat);
		$this->db->update("products_dispaly_order", $arr, "product_id='$product_id' AND p_type='$p_type' AND store_id='$store_id'");
		return true;
	}

	function getRelatedProductsList($id)
	{
	    $sql	= 	"SELECT `products`.`id`, `products`.`name` FROM `product_related` Inner Join `products` ON `products`.`id` = `product_related`.`related_id` WHERE `product_related`.`product_id` = '$id'";
		$rs 	= 	$this->db->get_results($sql);
		return $rs;
	}
	function getStoreNameByid($id) {
		$rs = $this->db->get_row("SELECT * FROM store WHERE id='{$id}'", ARRAY_A);
		return $rs['heading'];
	}

	
		
		function ProductAddEdit2(&$req,$file,$tmpname,$swf='',$tmp_swf='',$two_d='',$tmp_two_d='',$overl='',$tmp_over='',$pdf_file='',$tmp_pdf_file='',$psd_file='',$tmp_psd_file='',$ai_image='',$tmp_ai_image='',$over2='',$tmp_over2='',$over3='',$tmp_over3='',$over4='',$tmp_over4='',$over5='',$tmp_over5='',$type='') {
		extract($req);		
		
		
		global	$member_type;
		global	$user_id;
		//echo $store_id;
		//exit;
		//list($width,$height)	=	split(',',$this->config['product_thumb_image']);
		//echo "product_desc_image: ".$width."<br>";
		//exit;
		//echo "<pre>";
		//print_r($req);
		//foreach ($accessory['All'] as $access_id)
		//{
		//echo "<br>";
		//print_r($access_id);
		//}
		//print_r($_SESSION['StoreAccessories']);
		//exit;
		
	

		$date_created		=	date("Y-m-d H:i:s");
		$param	=	array();
		if ($file){
			$dir			=	SITE_PATH."/modules/product/images/";
			$file1			=	$dir."thumb/";
			$resource_file	=	$dir.$file;
			$path_parts 	= 	pathinfo($file);
			$param["image_extension"]='1';
/*--------------------------------------------------------------------------------------------*/
			/*$rs 	=	$this->db->get_row("select value from config where field='product_imagetype'", ARRAY_A);
			$val=$rs['value'];
			if($val=='gif'){
			echo   $image_extension	=	"gif";
			exit();
			}
/*--------------------------------------------------------------------------------------------*/
		}
		
		if(isset($clone))
			{
			//echo "clone";
			$id='';
			}	
		//exit;	
		/*last*/
		if($parent_id>0 && $path_parts['extension']==''){
		//echo "1<br>";
		
			 $image_extension	=	"jpg";
			
		}else{
		//echo "2<br>";
			$image_extension	=	strtolower($path_parts['extension']);
			
		}	
		//------------------------------------------------------
		if($this->config['product_imagetype'] == 'gif' && $file!="") {
		$image_extension	 ='gif';	
		}
		
		if($this->config['product_imagetype'] == 'gif') {
			//$image_extension	 ='gif';	
			$two_dimage_extension='gif';
			$overlimage_extension='gif';
			$over2image_extension='gif';
			$over3image_extension='gif';
			$over4image_extension='gif';
			$over5image_extension='gif';
		} else {
			$path_parts11 			= 	pathinfo($two_d);
			$two_dimage_extension	=	$path_parts11['extension'];
			
			$path_parts22 			= 	pathinfo($overl);
			$overlimage_extension	=	$path_parts22['extension'];
			
			$path_over22 			= 	pathinfo($over2);
			$over2image_extension	=	$path_over22['extension'];
			
			$path_over33 			= 	pathinfo($over3);
			$over3image_extension	=	$path_over33['extension'];
			
			$path_over44 			= 	pathinfo($over4);
			$over4image_extension	=	$path_over44['extension'];
			
			$path_over55 			= 	pathinfo($over5);
			$over5image_extension	=	$path_over55['extension'];;
		}
			
		//----------------------------------------------------
			
		
		$save_gift_property	=	false;
		//Adding price
		//Adding price
		$brand_id			=	$req['brand_id'];
		
		
		
		if(!trim($name)) {
			$message 			=	" Product name is required";
			return array("status"=>false,"message"=>$message);
		}
			
		if(count($category)==0) {
			$message 		=	"Category is required";
			return array("status"=>false,"message"=>$message);
		}
		
		
		if(!trim($description)) {
			$message 			=	" Description is required";
			return array("status"=>false,"message"=>$message);
		}
		
		
		if($_FILES['image_extension']['name'] =='' && $_REQUEST['id']=='') {
			$message 			=	" Product image is required";
			return array("status"=>false,"message"=>$message);
		}
		
		
		if(!trim($price)) {
			$message 			=	" Base price is required";
			return array("status"=>false,"message"=>$message);
		}
		
		/*if(!trim($prices)) {
			$message 			=	" Sale price is required";
			return array("status"=>false,"message"=>$message);
		}
	
		
		
		if(!trim($page_title)) {
			$message 			=	" Page title is required";
			return array("status"=>false,"message"=>$message);
		}
		
		if(!trim($meta_description)) {
			$message 			=	" Meta description is required";
			return array("status"=>false,"message"=>$message);
		}
		
		if(!trim($meta_keywords)) {
			$message 			=	" Meta keyword is required";
			return array("status"=>false,"message"=>$message);
		}
		*/
		if(!trim($domestic_sprice) || !trim($domestic_sprice_addtl) ) {
		
		      $domestic_sprice_addtl=0;
			//$message 			=	" Domestic shipping price details  is required";
			//return array("status"=>false,"message"=>$message);
		}
		
		if(!trim($inter_sprice) || !trim($inter_sprice_addtl) ) {
		 $inter_sprice_addtl=0;
			//$message 			=	" International shipping price details  is required";
			//return array("status"=>false,"message"=>$message);
		}
	
	
	if($_REQUEST['act']=="add_custom_product")
	{
	array_push($category,306);
	}
	
		if(count($category)>0) {
		
		
			if(!trim($name)) {
			$message 			=	$sId." name is required";
				return array("status"=>false,"message"=>$message);
				exit;
			} else {
				if($personalise_with_monogram)
					{
					$stat_personalise_with_monogram="Y";
					}
				else
					{
					$stat_personalise_with_monogram="N";
					}
				if(empty($display_gross))
					$display_gross="N";
				if(empty($display_related))
					$display_related="N";
				if(empty($out_stock))
					$out_stock="N";
				if(empty($hide_in_mainstore))
					$hide_in_mainstore="N";
				if(empty($display_name))
					$display_name=$name;
					
				if(empty($cart_name))
					$cart_name=$name;		
				$hide_in_mainstore="Y";
			
				if(empty($id)){
					if($is_giftcertificate)
					{
					//save giftcertificate properties
					$type_option			=	$req['coupon_options'];
					if($type_option			==	'fixed')
					$no_times			=	$req['one_times'];
					$duration				=	$req['duration'];
					$save_gift_property		=	true;
					$stat_is_giftcertificate = "Y";
					}
					else
					{
					$stat_is_giftcertificate =	"N";
					}
				}
				else
				{
					$sam_Product				=	$this->ProductGet($id);
					$stat_is_giftcertificate	=	$sam_Product['is_giftcertificate'];
				}
					
					
					
					
				//exit;
				//calculate the base price from category selectet
				
				
				
				
				if($price=='')
					{
					if($category)
						{
						$cat_price=0;
						foreach ($category as $category_id)
							{
								$qry	=	"SELECT base_price FROM master_category WHERE category_id=".$category_id;
								$rs		=	$this->db->get_row($qry,ARRAY_A);
								if($rs['base_price']>$cat_price)
									$cat_price=$rs['base_price'];
							}
						if($cat_price>0)
							$price 	=	$cat_price;
						}
					}
					if($active=='')
					{
					$active='N';
					}
				$done_status="Y";
				
				
				if ($file){
					$array 			= 	array("product_id"=>$product_id,
					"name"=>mysql_real_escape_string(htmlentities($name)),
					"display_name"=>mysql_real_escape_string($name),
					"cart_name"=>mysql_real_escape_string($name),
					"brand_id"=>$brand_id,
					"parent_id"=>0,
					"group_id"=>$group_id,
					"price"=>$price,
					"weight"=>$weight,
					"description"=>mysql_real_escape_string($description),
					"image_extension"=>$image_extension,
					
					"date_created"=>$date_created,
					"personalise_with_monogram"=>$stat_personalise_with_monogram,
					"is_giftcertificate"=>$stat_is_giftcertificate,
					"thickness"=>$thickness,
					"display_gross"=>$display_gross,
					"display_related"=>$display_related,
					"display_order"=>$display_order,
					"out_stock"=>$out_stock,
					"out_message"=>$out_message,
					"hide_in_mainstore"=>$hide_in_mainstore,
					"size"=>$size,
					"width"=>$width,
					"x_co"=>$x_co,
					"y_co"=>$y_co,
					"active"=>$active,
					"dual_side"=>$dual_side,
					"image_area_height"=>$image_area_height,
					"image_area_width"=>$image_area_width,
					"custom_product"=>'Y'
					);
				}else{
					$array 			= 	array("product_id"=>$product_id,
					"name"=>mysql_real_escape_string(htmlentities($name)),
					"display_name"=>mysql_real_escape_string($name),
					"cart_name"=>mysql_real_escape_string($name),
					"brand_id"=>$brand_id,
					"parent_id"=>0,
					"group_id"=>$group_id,
					"price"=>$price,
					"weight"=>$weight,
					"description"=>mysql_real_escape_string($description),
					"date_created"=>$date_created,
					"personalise_with_monogram"=>$stat_personalise_with_monogram,
					"is_giftcertificate"=>$stat_is_giftcertificate,
					"thickness"=>$thickness,
					"display_gross"=>$display_gross,
					"display_related"=>$display_related,
					"display_order"=>$display_order,
					"out_stock"=>$out_stock,
					"out_message"=>$out_message,
					"hide_in_mainstore"=>$hide_in_mainstore,
					"size"=>$size,
					"width"=>$width,
					"x_co"=>$x_co,
					"y_co"=>$y_co,
					"dual_side"=>$dual_side,
					"active"=>$active,
					"image_area_height"=>$image_area_height,
					"image_area_width"=>$image_area_width,
					"custom_product"=>'Y'	
					//"status"=>$done_status
					);
					
				

				if(trim($image_extension))
					{
							
						$newarray		=	array("image_extension"	=>	$image_extension);
						$array			=	array_merge($array,$newarray);
						$param["image_extension"]='1';
					}
				/*echo "<pre>";	
				print_r($array);
				exit;*/
				}
				
				
					
					if($domestic_sprice){
						$array["domestic_sprice"]=$domestic_sprice;
					}
					if($domestic_sprice_addtl){
						$array["domestic_sprice_addtl"]=$domestic_sprice_addtl;
					}
					if($inter_sprice){
						$array["inter_sprice"]=$inter_sprice;
					}
					if($inter_sprice_addtl){
						$array["inter_sprice_addtl"]=$inter_sprice_addtl;
					}
					
					
					if($seo_name){
						$array["seo_name"]=$seo_name;
					}
					

					$array["page_title"]=$page_title;
					$array["meta_description"]=$meta_description;
					$array["meta_keywords"]=$meta_keywords;
					
	
				
				# Add Quantity If Exist 
				if (isset($quantity)) {
					if ( trim($quantity) || $quantity == 0  ) {
						
						if ($quantity>0)
						$newarray		=	array("quantity"	=>	$quantity);
						else

						$newarray		=	array("quantity"	=>	0);
						
						$array			=	array_merge($array,$newarray);
					}
				}
				
				
				
				if(($swf))
					{
					
						$path_parts3 	= 	pathinfo($swf);
						$newarray		=	array("swf"	=>	$path_parts3['extension']);
						$array			=	array_merge($array,$newarray);
					}

				if(($two_d))
					{
						$path_parts1 		= 	pathinfo($two_d);
						$SourceExtension	=	$path_parts1['extension'];
						$newarray			=	array("two_d_image"	=>	$two_dimage_extension);
						$array				=	array_merge($array,$newarray);
						$param["two_d_image"]='1';
					}
				if(($overl))
					{
						$path_parts2 		= 	pathinfo($overl);
						$SourceExtension	=	$path_parts2['extension'];
						$newarray			=	array("overlay"	=>	$overlimage_extension);
						$array				=	array_merge($array,$newarray);
						$param["overlay"]='1';
					}
				if($over2)
					{
						$path_over2 		= 	pathinfo($over2);
						$SourceExtension	=	$path_over2['extension'];
						$newarray			=	array("overlay2"	=>	$over2image_extension);
						$array				=	array_merge($array,$newarray);
						$param["overlay2"]='1';
					}
				if($over3)
					{
						$path_over3 		= 	pathinfo($over3);
						$SourceExtension	=	$path_over3['extension'];
						$newarray			=	array("overlay3"	=>	$over3image_extension);
						$array				=	array_merge($array,$newarray);
						$param["overlay3"]='1';
					}
				if($over4)
					{
						$path_over4 		= 	pathinfo($over4);
						$SourceExtension	=	$path_over4['extension'];
						$newarray			=	array("overlay4"	=>	$over4image_extension);
						$array				=	array_merge($array,$newarray);
						$param["overlay4"]='1';
					}
				if($over5)
					{
						$path_over5 		= 	pathinfo($over5);
						$SourceExtension	=	$path_over5['extension'];
						$newarray			=	array("overlay5"	=>	$over5image_extension);
						$array				=	array_merge($array,$newarray);
						$param["overlay5"]='1';
					}
					if(($pdf_file))
					{
						$path_parts3 	= 	pathinfo($pdf_file);
						$file_ext3		=	$path_parts3['extension'];
						if($file_ext3=='PDF' || $file_ext3=='Pdf' || $file_ext3='pdf')
						{
							$save_filename3	=	$id."_".$pdf_file;
							$newarray		=	array("pdf_file"	=>	$save_filename3);
							$array			=	array_merge($array,$newarray);
							
							$dir3			=	SITE_PATH."/modules/product/files/";
							_upload($dir3,$save_filename3,$tmp_pdf_file,0,0,0);
						}
					}
					if(($psd_file))
					{
						$path_parts4 	= 	pathinfo($psd_file);
						$file_ext4		=	$path_parts4['extension'];
						if($file_ext4=='PSD' || $file_ext4=='Psd' || $file_ext4='psd')
						{
							$save_filename4	=	$id."_".$psd_file;
							$newarray		=	array("psd_file"	=>	$save_filename4);
							$array			=	array_merge($array,$newarray);
							
							$dir3			=	SITE_PATH."/modules/product/files/";
							_upload($dir3,$save_filename4,$tmp_psd_file,0,0,0);
						}
					}
					if(($ai_image))
					{
						$path_parts5 	= 	pathinfo($ai_image);
						$file_ext5		=	$path_parts5['extension'];
						if($file_ext5=='AI' || $file_ext5=='Ai' || $file_ext5='ai')
						
						{
							$save_filename5	=	$id."_".$ai_image;
							$newarray		=	array("ai_image"	=>	$save_filename5);
							$array			=	array_merge($array,$newarray);
							
							$dir3			=	SITE_PATH."/modules/product/files/";
							_upload($dir3,$save_filename5,$tmp_ai_image,0,0,0);
						}
					}
					
					

				//manage product from store
				
					if($manage=='manage' && $id>0)
						{
						
						$old_product_id='';
					    $qry	=	"SELECT * from product_store ps where ps.product_id=$id and store_id!=$store_id";
						$res	=	$this->db->get_results($qry,ARRAY_A);
						
					
						if($res)//The product is assigned to other stores
							{
							//echo "The product is assigned to other stores"."<br>";
							$this->db->query("DELETE FROM product_store WHERE product_id='$id' and store_id=$store_id");
							$old_product_id=$id;//for getting the old product images to new product
							$id="";
							$array['hide_in_mainstore']='Y';
							}
						else// the product is assigned to this store only
							{
							//echo "the product is assigned to this store only"."<br>";
							//$this->db->query("DELETE FROM product_store WHERE product_id='$id' and store_id=$store_id");
							$this->RemoveAllAccessorySelected($id);//remove the old accessories
							$array['hide_in_mainstore']='Y';
							}
						
						}
						
					if($manage=='manage' && $remove=='yes')
					{
					$this->RemoveImage($id,"image_extension");
					}
				// manage exclude accessory
				if ( $ex_accessory )
				$array['exclude_group'] 	=	implode ( "," , $ex_accessory ); 		
						
				//manage product from store
				if($id) {
				
					$array['id'] 	= 	$id;
					
										
					//echo "the product is updated at $id"."<br>";
					$this->db->update("products", $array, "id='$id'");
					//if($Append!='Y')
						//{
							if($manage!='manage')
							{
							//$this->deleteRelatedStore($id);
							//$this->RemoveAllAccessorySelected($id);
							}
						//}
					
						
						
				} else {
					
				
					$this->db->insert("products", $array);					
					 $id 			= 	$this->db->insert_id;

					
					 if($store_id)
					 {
					 	$product_storeArray=array("product_id"	=>	$id,"store_id"=>$store_id);
						$this->db->insert("product_store", $product_storeArray);
					 }

					if($member_type==3){
						$assignSupplier		=	array("product_id"	=>	$id,"supply_id"=>$user_id);
						$this->db->insert("product_supplier", $assignSupplier);
					}
					//for saving imapge product of the old products
					//print_r($param);exit;
					if($manage=='manage' && $old_product_id>0)
						{
						$this->CopyOldImagesToNewProduct($old_product_id,$id,$param);
						
							$product_storeArray=array("product_id"	=>	$id,"store_id"=>$store_id);
							$this->db->insert("product_store", $product_storeArray);
							
							//echo $product_storeArray["store_id"];
						}
						
					
					
					
					//echo "the product is added at $id"."<br>";
					//save gift certificate properties
					if($save_gift_property	===	true)
						{
						$gift_cert			=	array("product_id"=>$id,"type_option"=>$type_option,"no_times"=>$no_times,"duration"=>$duration);
						$this->db->insert("certificate_property", $gift_cert);
						//echo "Inserted Gift certificate<br>";
						}
						else if(!isset($clone))
						{
						}
					
				}
				//echo "Updated Product table<br>";
				//Assigning Product Zones
				if($zone_id>0)
					{
					//deleting exisiting zones of the product
					$this->db->query("DELETE FROM product_made_in WHERE product_id='$id'");
					//echo "Inserted Product zone <br>";
					
					//entering new zones for the product
					$ins_zone	=	array("zone_id"=>$zone_id,"product_id"=>$id);
					$this->db->insert("product_made_in", $ins_zone);
					}//if($zone_id>0)
				
				//Assigning product Price
				$priceObj	=	new Price();
				$this->db->query("DELETE FROM product_price WHERE product_id='$id'");
				if($req['is_percentage'])
								{
								$save_perc	=	"Y";
								}
							else
								{
								$save_perc	=	"N";
								}
				$pricearray 			= 	array("type_id"=>$req['price_type'],
														"product_id"=>$id,
														"price"=>$req['prices'],
														"is_percentage"=>$save_perc);
				if($req['price_type']>0)
				{
					if($req['prices']>0)
						{
						$this->db->insert("product_price", $pricearray);
						//echo "Inserted Product price <br>";
						}
				}
				
				
				//Assigning product to category
				$this->db->query("DELETE FROM category_product WHERE product_id='$id'");
				foreach ($category as $category_id)
				{
					$ins			=	array("category_id"=>$category_id,"product_id"=>$id);
					$this->db->insert("category_product", $ins);
					//echo "Inserted Product category <br>";
				}
				
				
			}
		}
		else
		{
			$message 		=	"Category is required";
			//echo "No category <br>";
			return array("status"=>false,"message"=>$message);
			//exit;
			
		}
		if($swf)
				{
				
					$dir3			=	SITE_PATH."/modules/product/images/";
					$path_parts3 	= 	pathinfo($swf);
					$save_filename3	=	"swf_".$id.".".$path_parts3['extension'];
					_upload($dir3,$save_filename3,$tmp_swf,0,0,0);
				}
		if($two_d)
				{
				   $SourceExtension=$path_parts1['extension'];
					$dir1			=	SITE_PATH."/modules/product/images/";
					$path_parts1 	= 	pathinfo($two_d);
					$save_filename1	=	"2D_".$id.".".$two_dimage_extension;
					
					_upload($dir1,$save_filename1,$tmp_two_d,0,0,0,$SourceExtension);
				}
		if($overl)
				{
				    $SourceExtension=$path_parts2['extension'];
					$dir1			=	SITE_PATH."/modules/product/images/";
					$path_parts2 	= 	pathinfo($overl);
					$save_filename2	=	"OV_".$id.".".$overlimage_extension;
					_upload($dir1,$save_filename2,$tmp_over,1,130,109,$SourceExtension);
					
				}
		if($over2)
				{
				    $SourceExtension=$path_over2['extension'];
					$dir1			=	SITE_PATH."/modules/product/images/";
					$path_over2 	= 	pathinfo($over2);
					$save_over2	=	"OV2_".$id.".".$over2image_extension;
					_upload($dir1,$save_over2,$tmp_over2,1,130,109,$SourceExtension);
				}		
		if($over3)
				{
				 $SourceExtension=$path_over3['extension'];
					$dir1			=	SITE_PATH."/modules/product/images/";
					$path_over3 	= 	pathinfo($over3);
					$save_over3	=	"OV3_".$id.".".$over3image_extension;
					_upload($dir1,$save_over3,$tmp_over3,1,130,109,$SourceExtension);
				}
		if($over4)
				{
				    $SourceExtension=$path_over4['extension'];
					$dir1			=	SITE_PATH."/modules/product/images/";
					$path_over4 	= 	pathinfo($over4);
					$save_over4	=	"OV4_".$id.".".$over4image_extension;
					_upload($dir1,$save_over4,$tmp_over4,1,130,109,$SourceExtension);
				}
		if($over5)
				{
				    $SourceExtension=$path_over5['extension'];
					$dir1			=	SITE_PATH."/modules/product/images/";
					$path_over5 	= 	pathinfo($over5);
					$save_over5	=	"OV5_".$id.".".$over5image_extension;
					_upload($dir1,$save_over5,$tmp_over5,1,130,109,$SourceExtension);
				}			
				
				
		if($file){
			if($path_parts['extension']=='swf'	||	$path_parts['extension']=='Swf' 	|| 	$path_parts['extension']=='SWF')
					{
					$save_filename	=	$id.".".$path_parts['extension'];
					//echo $dir;
					//echo $save_filename;
					_upload($dir,$save_filename,$tmpname,0,0,0);
					//$this->db->update("products", array('swf'=>$swf_filename), "id='$id'");
					}
					else
					{	$image_extension	=	strtolower($image_extension);
						$SourceExtension	=	strtolower($SourceExtension);
						
						$SourceExtension	=	$path_parts['extension'];
						$save_filename	=	$id.".".$image_extension;
						$new_name		=	$id."_des_.".$image_extension;
						list($thumb_width,$thumb_height,)	=	split(',',$this->config['product_thumb_image']);
					
						if($thumb_width>0 && $thumb_height>0){
							//echo $dir." + ".$save_filename." + ".$tmpname." + ".$thumb_width." + ".$thumb_height." + ".$SourceExtension;exit;
							_upload($dir,$save_filename,$tmpname,1,$thumb_width,$thumb_height,$SourceExtension);
						}else{
							_upload($dir,$save_filename,$tmpname,0);
						}
						//upload description iamges
						//echo "$new_name";
						$path=$dir;
						$thumb=$dir."thumb/";
						list($desc_width,$desc_height,)	=	split(',',$this->config['product_desc_image']);
						
						if($desc_width>0 && $desc_height>0){
							
							thumbnail($path,$thumb,$save_filename,$desc_width,$desc_height,$mode,$new_name,$SourceExtension);
						}
						//upload listing images
						$new_name		=	$id."_List_.".$image_extension;
						//echo "$new_name";
						list($list_width,$list_height,)	=	split(',',$this->config['product_list_image']);
						if($list_width>0 && $list_height>0)
						
						thumbnail($path,$thumb,$save_filename,$list_width,$list_height,$mode,$new_name);
					
						$new_name		=	$id."_thumb2_.".$image_extension;
						//echo "$new_name";
						list($thumb2_width,$thumb2_height,)	=	split(',',$this->config['product_thumb2_image']);
						if($thumb2_width>0 && $thumb2_height>0)
						thumbnail($path,$thumb,$save_filename,$thumb2_width,$thumb2_height,$mode,$new_name,$SourceExtension);
					
						$new_name		=	$id."_thumb3_.".$image_extension;
						//echo "$new_name";
						list($thumb3_width,$thumb3_height,)	=	split(',',$this->config['product_thumb3_image']);
						if($thumb3_width>0 && $thumb3_height>0)
						thumbnail($path,$thumb,$save_filename,$thumb3_width,$thumb3_height,$mode,$new_name,$SourceExtension);
					
					//echo "Uploaded image <br>";
					}
		}
		/**************************************************************/
		//STORE TO PRODUCT 
		
		//$this->RemoveAllAccessorySelected($id);
		//store_id  == 0; main store
		/*if(count($accessory[0])>0)
				{
				foreach ($accessory[0] as $access_id)
					{
					$this->SetProductAccessory($id,$access_id,0);
					}
				}
		//All for registering the parameter category in 
		if(count($accessory['All'])>0)
			{
			foreach ($accessory['All'] as $access_id)
				{
				$this->SetProductAccessory($id,$access_id,0);
				}
			}
		for($i=0;$i<count($stores_id);$i++)
			{
			$this->relatedInsertStore($id,$stores_id[$i]);
			//All
			if(count($accessory['All'])>0)
				{
				foreach ($accessory['All'] as $access_id)
					{
					$this->SetProductAccessory($id,$access_id,$stores_id[$i]);
					}
				}
			
			//with store id
			//echo "stores_id: $stores_id[$i]<br>";
			if(count($accessory[$stores_id[$i]])>0)
				{
				foreach ($accessory[$stores_id[$i]] as $access_id)
					{
					$this->SetProductAccessory($id,$access_id,$stores_id[$i]);
					}
				}
			}//for loop of store id*/
		//exit;
		/**************************** saving product available accessories with the new table for store ****************/
		
		if(count($accessory['All'])>0)
			{
			foreach ($accessory['All'] as $access_id)
				{
				$this->SetProductAccessory($id,$access_id,0,'YES');
				}
			}
		if(count($accessory[0])>0)
				{
				foreach ($accessory[0] as $access_id)
					{
					$this->SetProductAccessory($id,$access_id,0,'NO');
					}
				}
				/*for($i=0;$i<count($stores_id);$i++)
			{
			$this->relatedInsertStore($id,$stores_id[$i]);
			if(count($accessory[$stores_id[$i]])>0)
				{
				foreach ($accessory[$stores_id[$i]] as $access_id)
					{
					$this->SetProductAccessory($id,$access_id,$stores_id[$i],'NO');
					}
				}
			}*/
// modified by Ratheesh kk, modified by vipin on 07-01-2008
if($this->config["artist_selection"] == "Yes"){
       if (count($accessory['All'])=='0' and count($accessory[0])=='0')
	     {
		 // ?? to retheeshkk
			$this->relatedInsertStore($id,$stores_id);
			if(count($accessory[$stores_id[$i]])>0)
				{
				foreach ($accessory[$stores_id[$i]] as $access_id)
					{
					$this->SetProductAccessory($id,$access_id,$stores_id[$i],'NO');
					}
				}
			 }		
		 }
		/**************************** saving product available accessories with the new table for store ****************/
		// by shinu 20-06-07 for saing accessory from session 
		//array structure(accessoryid,productid,adjustprice,adjustweight,cartname)
		$MultArr	=	$_SESSION['MultiAccessory'];
		if($MultArr)
		{
			$newMultArr	=	array_reverse($MultArr);
			foreach($newMultArr as $value) 
			{
				$ses_accessory_id	=	$value[0]; 
				$ses_product_id	=	$value[1];
				$ses_adjust_price	=	$value[2];
				$ses_adjust_weight	=	$value[3];
				$ses_cart_name	=	$value[4];
				if($ses_product_id=="" || $ses_product_id=="0" ) { $ses_product_id=$id; } 
				$ses_newarray 			= 	array("adjust_price"=>$ses_adjust_price,"adjust_weight"=>$ses_adjust_weight,"cart_name"=>$ses_cart_name);
				
				$this->db->update("product_availabe_accessory", $ses_newarray, "accessory_id='$ses_accessory_id' and product_id='$ses_product_id'");
			}	
		}				
		unset($_SESSION['MultiAccessory']);
		// print_r($MultArr);
		//exit;
		/*
					$ses_adjust_weight	=	$_SESSION['ses_adjust_weight'];
					$ses_adjust_price	=	$_SESSION['ses_adjust_price'];
					$ses_cart_name		=	$_SESSION['ses_cart_name'];
					$ses_accessory_id	=	$_SESSION['ses_accessory_id'];
					//echo "<br><br>session<br>----------<br><br>";
					//print(" sid ".$_SESSION['ses_cart_name']);
					//exit;
					if($ses_accessory_id!="")
					{
						if($ses_adjust_price!="")
						{
							$newarray1 			= 	array("adjust_price"=>$ses_adjust_price);
							$this->db->update("product_availabe_accessory", $newarray1, "accessory_id='$ses_accessory_id' and product_id='$id'");
						}
						if($ses_adjust_weight!="")
						{
							$newarray2 			= 	array("adjust_weight"=>$ses_adjust_weight);
							$this->db->update("product_availabe_accessory", $newarray2, "accessory_id='$ses_accessory_id' and product_id='$id'");
						}
						if($ses_cart_name!="")
						{
							$newarray3			= 	array("cart_name"=>$ses_cart_name);
							$this->db->update("product_availabe_accessory", $newarray3, "accessory_id='$ses_accessory_id' and product_id='$id'");
						}
						unset($_SESSION['ses_adjust_weight']);
						unset($_SESSION['ses_adjust_price']);
						unset($_SESSION['ses_accessory_id']);
						unset($_SESSION['ses_cart_name']);
					}*/
					
					
		//print_r($id);
		//exit;
		

		return array("status"=>true,"id"=>$id);
		//return $id;
	}



	
	/*
	This function fetch all the active category of the selected owner
	@pageNo: page number starting from 0
	@limit: Number of results to be placed in page
	@params: The additional parameters to be send along with the pagination as well as with number of results drop down
	@output: Specifying the outpu format as class oblect as array
	@orderBy: for ordering the query results
	@product_search: for product search in admin side
	@catId: category id for short listing the product list in admin side
	*/
	function listAllProduct2($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy,$store_id='',$product_search="",$catId='',$bId='',$mId='')
	{	
	
		global $member_type;
		global $id;
		if($catId>0)
		{
		$categories	=	"'0'";
			$categories	=	$this->getChildCategories($catId,$categories); 
			$categories	= $categories.",'".$catId."'";
			$catRs		=	mysql_query("select product_id from category_product where category_id IN ($categories)");	
			$products	=	"'0'";
			while($catRow=mysql_fetch_array($catRs))
			{	
			$products.=",'".$catRow["product_id"]."'"; }
		}
	
		if($store_id!="") {	
		
		 
				 $qry		="SELECT z.name as Made,  p.*,b.brand_name
							FROM products p
							LEFT JOIN product_made_in m ON p.id = m.product_id
							LEFT JOIN product_zone z ON m.zone_id = z.id
							LEFT JOIN brands b ON p.brand_id = b.brand_id 
							INNER JOIN (product_store as s) ON (s.product_id = p.id) WHERE s.store_id ='$store_id' AND custom_product='Y' ";
				 if($product_search!="")
						 $qry.=	"and (p.name  LIKE '%".$product_search."%' or p.display_name  LIKE '%".$product_search."%' or p.description LIKE '%".$product_search."%' or p.cart_name LIKE '%".$product_search."%') ";
						 //echo  $qry;
						// exit;
				 if($catId>0)
					 	$qry.=	"and p.id IN($products) ";
		}else{
				
				 $qry		="SELECT z.name as Made,b.brand_name, p. *
								FROM products p
								LEFT JOIN product_made_in m ON p.id = m.product_id
								LEFT JOIN product_zone z ON m.zone_id = z.id
								LEFT JOIN brands b ON p.brand_id = b.brand_id"; 
					if($member_type==3){
						 $qry= $qry."  INNER JOIN product_supplier ps ON p.id=ps.product_id";
					}
					$qry= $qry."  WHERE";
					
					if(($catId=='' || $catId==0) && $product_search=='')
					 $qry.=	" 1";
					 
				 if($product_search!=""){				 
					 $qry.=	" (p.name LIKE '%".$product_search."%' or p.display_name  LIKE '%".$product_search."%' or p.description LIKE '%".$product_search."%' or p.cart_name LIKE '%".$product_search."%')";
				 }
				 if($member_type==3){
						 $qry= $qry." AND ps.supply_id=$id";
					}
					 if($bId!=""){
						    if($catId>0){
							  $qry= $qry."  p.brand_id=$bId AND";
							 }
							 else{
							   $qry= $qry." AND p.brand_id=$bId";
							  }
							}  
					  if($mId!=""){
						    if($catId>0){
							  $qry= $qry."  m.zone_id=$mId AND";
							 }
							 else{
							   $qry= $qry." AND m.zone_id=$mId";
							  }		  
							  
					}
				// echo $catId;
				if($catId>0){		
				 if($product_search!="") {				
				  	 $qry.=	" and ";
				 }
				 
					 $qry.=	" p.id IN($products)";
				 
				 }
				 $qry.=	" AND p.hide_in_mainstore='N'";
				
			}   
			//echo $brandid;
		//	echo $qry;
			// exit;
			
		//$qry		=	"select * from products as p left join (brands as b) on (p.brand_id=b.brand_id) where ";

			
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		
	    //exit;
		/*if(count($rs[0])>0)
			{
			$arr=array();
			$i=0;
			foreach ($rs[0] as $row)
				{
				$sql	=		"select z.* from product_made_in m, product_zone z where m.product_id=".$row->id." and m.zone_id=z.id" ;
				$arr	=	$this->db->get_row($sql,OBJECT);
				
				
				if($arr>0)
				$rs[0][$i]->Made=$arr->name;
				else
				$rs[0][$i]->Made='';
				$i++;
				}
			}*/
		
		return $rs;
	}


		
	function GetCustomProductsOfTheStoreAndcategory($category_id=0,$parent_id=0,$store_name='')
	{
	 
	// $this->config["artist_selection"];
		$string	=	"'".$category_id."'";
		$categories	=	$this->getAllSubCategories($category_id,$string);
		
	$qry		=	"SELECT DISTINCT p.*	FROM ";
	if($store_name)
	$qry		.=	" 		store as st, 
							product_store as ps, ";   
							
	if ($this->config["artist_selection"] == "Yes" and $_REQUEST['act'] == "listproduct" ){
	$userid = $_SESSION[memberid];
	/*$qry		.=	"  		products AS p,
					   		category_product AS cp,
							master_category AS mc,
							product_saved_work AS pw
					  WHERE pw.pro_save_id = p.id and pw.user_id != $userid and  ";*/
	$qry		.=	"  		products AS p,
					   		category_product AS cp,
							master_category AS mc,
							product_saved_work AS pw
					  WHERE pw.pro_save_id = p.id and  ";
	
	}else{				  						
	$qry		.=	"  		products AS p,
					   		category_product AS cp,
							master_category AS mc
					  WHERE  ";
	}				  
	if($store_name)				
	$qry		.=		" 	p.id=ps.product_id AND
							ps.store_id=st.id AND
							st.name='".$store_name."' AND ";				  

		$qry		.=	"   	mc.category_id IN ($categories) AND
					  		mc.active='y' AND
							mc.is_in_ui='N' AND
							mc.category_id=cp.category_id AND
							cp.product_id=p.id	AND
							p.parent_id='$parent_id'	AND
							p.active='Y'
										  		
					";	


if(empty($store_name))	
		$qry		.=	"  		AND p.hide_in_mainstore!='Y' ";	
if ($this->config["artist_selection"] == "Yes" and $_REQUEST['act'] == "listproduct" )	
	$qry		.=	"  order by rand() ";	
	else	
	$qry		.=	"  		order by CASE WHEN p.display_order IS NULL OR p.display_order =' ' THEN 1000 END,p.display_order,p.name ";
	//$rs			=	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, ARRAY_A,$orderBy,'1');
	//echo "<pre>";
	//echo $qry;
	$rs			=	$this->db->get_results($qry,ARRAY_A);

	return $rs;
	}
	
	
	function getPredefinedGiftList_new($pageNo=0, $limit = 10, $params='', $output,$orderBy,$store_id='',$cat_id='',$stxt='',$flag=0)
	{
		
		if(!$orderBy)
		$orderBy = 'product_id:DESC,product_title:ASC';
	
		$objAccessory=	new Accessory();
	
		 if($store_id)
		 {
	 	 $qry = "(select pp.*,mc.category_name,p.name,ps.active,p.image_extension,'N' as other_gift  from product_predefined pp inner join master_category mc on mc.category_id=pp.category inner join products p on p.id=pp.product_id inner join product_predefined_store ps on ps.product_id=pp.id  where 1 and  ps.store_id=$store_id ";
		 $qry.= " and pp.product_active='Y' ";	
			if($flag==1)
		 	{
		 		 $qry.= " and  ps.active='Y' ";
		 	}
		 
		 }
		 else
		 {
		  $qry = "(select pp.*,mc.category_name,p.name,pp.product_active as active,p.image_extension,'N' as other_gift from product_predefined pp inner join master_category mc on mc.category_id=pp.category inner join products p on p.id=pp.product_id  where 1 and  pp.store_id=0  ";
		  
		  	if($flag==1)
		 	{
		 		 $qry.= " and pp.product_active='Y' ";
		 	}
		  }
		 
		/* if($store_id)
		  $qry.= " and   pp.store_id=$store_id";*/
		 
		 if($cat_id !='')
		 {
		 	 $qry.= " and   pp.category=$cat_id";
		 }
		 
		
		 
		
		 if($stxt !='')	
		 {
		 	 $qry.= " and   pp.product_title like '%$stxt%' or product_description like  '%$stxt%'  ";
		 }
		 
		$qry		.=")";	 
		
		$qry		.=" UNION ";	
		
		$qry.= "(SELECT DISTINCT p.id,cp.category_id AS category,p.product_id,'','','',''
				,p.name AS product_title,p.description AS product_description,p.price AS product_price,'',p.active,
				st.id AS store_id,'','','','','','','','','','','','',p.page_title,p.meta_keywords,p.meta_description,'','','','',p.image_extension,'Y' as other_gift
				 FROM store AS st,
				 product_store AS ps, products AS p, 
				 category_product AS cp, master_category AS mc 
				 WHERE p.id=ps.product_id 
				 AND ps.store_id=st.id AND  ps.store_id='$store_id' 
				 AND mc.category_id IN ($cat_id) 
				 AND mc.active='y' AND mc.is_in_ui='N' 
				 AND mc.category_id=cp.category_id 
				 AND cp.product_id=p.id AND p.parent_id='' 
				 AND p.active='Y' 
				ORDER BY CASE WHEN p.display_order IS NULL OR p.display_order =' ' THEN 1000 END,p.display_order,p.name)";
		
		//echo $qry;
				
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, 40, $params, $output, $orderBy);
		
		
		foreach($rs[0] as $key=>$val){
			$res = $objAccessory->GetAccessory($val['mat_id']);
			$rs[0][$key]['acc_mat'] = $res;
			$res = $objAccessory->GetAccessory($val['art_id']);
			$rs[0][$key]['acc_art'] = $res;
			$res = $objAccessory->GetAccessory($val['frame_id']);
			$rs[0][$key]['acc_frame'] = $res;
			$res = $objAccessory->GetAccessory($val['poem_id']);
			$rs[0][$key]['acc_poem'] = $res;
			
			if($val['product_sale_price'] >0 ){
			
				$rs[0][$key]['discount_price'] = $val['product_sale_price'];
			}
			else{
				$rs[0][$key]['discount_price'] = $val['product_basic_price'];
			}
			
		}
		
		
		
		
		return $rs;
	}
	
	function getProductCustomFields($product_id,$cart_id)
	{
	    $sql	= 	" SELECT *, if(required='Y',concat(field_name,': *'),concat(field_name,':')) as field_name FROM product_custom_fields where product_id = '$product_id'";
		
		if($cart_id > 0){
			
			$sql	=  "SELECT product_custom_fields.*,
							   if(product_custom_fields.required='Y',concat(product_custom_fields.field_name,': *'),concat(product_custom_fields.field_name,':')) as field_name,
							   product_custom_field_values.field_value
						FROM product_custom_fields 
						LEFT JOIN product_custom_field_values ON product_custom_field_values.field_id = product_custom_fields.id 
							AND product_custom_field_values.cart_id = '$cart_id'
						WHERE product_custom_fields.product_id = '$product_id' 
						ORDER BY id";
		}
		//echo $sql;
		$rs 	= 	$this->db->get_results($sql,ARRAY_A);
		return $rs;  
	}	
	
	function deleteProductCustomFields($product_id, $store_id)
	{
	    $sql	= 	" DELETE FROM product_custom_fields where product_id = '$product_id'";
		$rs 	= 	$this->db->query($sql);
		return true;;
	}	
		
	function getProductCustomFieldValues($cart_id=0)
	{
	    $sql	= 	" SELECT * FROM product_custom_field_values where cart_id = '$cart_id'";
		$rs 	= 	$this->db->get_results($sql,ARRAY_A);
		return $rs; 
	}	
		
	
	

} # Close class definition



 

?>