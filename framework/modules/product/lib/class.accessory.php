<?php
//error_reporting(0);
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
class Accessory extends FrameWork
{
	function Accessory()
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
	
	// return sub catid list
	function subCategories($category_id,$catString)
	{	
		$catQry	=	"SELECT category_id  from master_category where parent_id='$category_id' AND active='y' ";
		$catRes	=	mysql_query($catQry);
		while($catRow	=	mysql_fetch_array($catRes))
		{
			$category_id=	$catRow["category_id"];
			$catString	=	$catString.",'".$catRow["category_id"]."'";
			$count		=	$this->subCount($category_id);
			if($count>0)
			{
				$catString	=	$this->subCategories($category_id,$catString,0);
			}
		}
		return $catString;
	}
	//return subcount
	function subCount($category_id)
	{
		$catQry	=	"SELECT category_id  from master_category where parent_id='$category_id' AND active='y' ";
		$catRes	=	mysql_query($catQry);
		$count	=	mysql_num_rows($catRes);
		return $count;
	
	}
	
	function RemoveImage($accessory_id,$field,$extn)
	{
		if($accessory_id>0)
			{
			$arr	=	array($field=>"");
			$this->db->update("product_accessories", $arr, "id='$accessory_id'");
			$image1	=	SITE_PATH."/modules/product/images/accessory/thumb/".$accessory_id.".".$extn;
			$image2	=	SITE_PATH."/modules/product/images/accessory/thumb/".$accessory_id."_des_.".$extn;
			$image3	=	SITE_PATH."/modules/product/images/accessory/thumb/".$accessory_id."_List_.".$extn;
			if($image1)
			unlink($image1);
			if($image2)
			unlink($image2);
			if($image3)
			unlink($image3);
			return true;
			}
		return false;
	}	
			
	/*
	*	This function will return all the brand names.
	*	This function is used for listing brand names in admin side.
	*/
	
	function listAllAccessories($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy,	$category_id='',$store_id='')
	{
	//$orderBy="display_order";
		$catString="'".$category_id."'";
		$catString	=	$this->subCategories($category_id,$catString,$store_id);
		list($qry1,$table_id,$join_qry)=$this->generateQry('product_accessories','d','pa');
		 $qry	=	"select pa.*,$qry1 from product_accessories pa  $join_qry ";
		
		 # $qry	=	"select pa.*,$qry1 from product_accessories pa  $join_qry";

		
		if($store_id>0)
		{
		 $qry	=	"SELECT pa.*,$qry1,b.store_id,b.accessory_id 
		              FROM  product_accessories pa $join_qry,store_accessory b  
					 WHERE  pa.id = b.accessory_id
					  AND   b.store_id = $store_id ";
		}
		
		if($category_id>0)
		{
		 $qry	=	"select DISTINCT(pa.name),pa.id ,pa.category_id,pa.adjust_price,pa.adjust_weight ,pa.type,pa.active,$qry1
		             from product_accessories pa $join_qry ,master_category as mc,category_accessory as ca 
					 where mc.category_id=ca.category_id
					 and ca.accessory_id=pa.id 
					 AND ca.category_id IN ($catString) "; 
					
		if($store_id>0)
		{
		 $qry	=	"select pa.*,$qry1,sa.store_id,sa.accessory_id
		             from product_accessories pa $join_qry,master_category as mc,category_accessory as ca ,store_accessory as sa
					 where mc.category_id=ca.category_id
					 and ca.accessory_id=pa.id 
					 and sa.accessory_id = pa.id
					 and sa.store_id = $store_id
					 AND ca.category_id IN ($catString) "; 
		
		}			 
		}
		
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	function listAccessoriesbySearch($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy,	$category_id='',$accessory_search)
	{
		//echo "limit: ".$limit;
		list($qry1,$table_id,$join_qry)=$this->generateQry('product_accessories','d','pa'); // DO NOT CHANGE THIS PART -  Retheesh
		$qry	=	"select pa.*,$qry1 from product_accessories pa $join_qry where pa.name LIKE '%".$accessory_search."%'";
		
		
		if($category_id>0)
							 
						 $qry	=	"select pa.*,$qry1  from product_accessories pa $join_qry,master_category as mc,category_accessory as ca  where mc.category_id=ca.category_id
						 and ca.accessory_id=pa.id AND pa.name LIKE '%".$accessory_search."%' AND ca.category_id=".$category_id;
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
	
	function GetAccessory($id=0) {
		if($id>0)
		{
			list($qry,$table_id,$join_qry)=$this->generateQry('product_accessories','d','a');
			$sql = "SELECT a.*,$qry FROM product_accessories a $join_qry WHERE a.id='$id'";	
			$rs = $this->db->get_row($sql, ARRAY_A);
			return $rs;
		}
	}
	
	//This function will search a paricular accessory by main fields and custom fields
	function getAccessoryBySearch($search_fields,$search_values,$criteria="=")
	{
	
		list($qry,$table_id,$join_qry)=$this->generateQry('product_accessories','d','pa');
		if($search_fields)
		{
			list($qry_cs,$table_id_1)=$this->getCustomQry('product_accessories',$search_fields,$search_values,$criteria,'pa','d');	
		}
		$sql = "SELECT pa.*,$qry FROM product_accessories pa $join_qry";			
		if($qry_cs)
		{
			$wh =retWhere($sql);
			$sql=$sql." $wh $qry_cs";
		}
		
		$rs = $this->db->get_results($sql);
		
		return $rs;
	}
	
	function GetProductsAccessory($id=0,$product_id) {
		if($id>0)
		{
			$rs = $this->db->get_row("SELECT * FROM product_availabe_accessory WHERE accessory_id='{$id}' AND product_id=$product_id ", ARRAY_A);
			return $rs;
		}
	}
	function GetProductsALLcatAccessory($id) {
		if($id>0)
		{
			$rs = $this->db->get_results("SELECT T1.*,T2.*,T3.* FROM product_availabe_accessory  T1 left join product_accessories T2 on (T1.accessory_id = T2.id) LEFT JOIN master_category T3 ON (T1.category_id=T3.category_id) WHERE T1.product_id=$id", ARRAY_A);
			return $rs;
		}
	}
	function modifyAccessory(&$req)
	{
		extract($req); 
		$newadjust_price="0.00";
		$newadjust_weight="0.00";
		$newcart_name="";
		if($prd_id=="")
		{
			$_SESSION['ses_accessory_id'] = $id;
		}
		if($chk_adjust_weight=="1")
		{
			$newadjust_weight	=	$adjust_weight;
			if($prd_id=="")	{
				$_SESSION['ses_adjust_weight']	=	$adjust_weight;
			}
			$array 			= 	array("adjust_weight"=>$adjust_weight);
			$status1=$this->db->update("product_availabe_accessory", $array, "accessory_id='$id' and product_id='$prd_id'");
			
			if($status1==0)
			{
				$_SESSION['ses_accessory_id'] = $id;
				$_SESSION['ses_adjust_weight']	=	$adjust_weight;
				$newadjust_weight	=	$adjust_weight;
				
			}
			
		}
		else
		{
			$qry2	="update product_accessories set adjust_weight='$adjust_weight' where id='$id' ";
			$this->db->query($qry2); 

		}
		
		#---
		if($chk_cart_name=="1")
		{
		$newcart_name	=	$cart_name;
		if($prd_id=="") {
		$_SESSION['ses_cart_name'] = $cart_name;
		}//echo "p ".$prd_id. $cart_name .$id; break;
		$array = array("cart_name"=>$cart_name);
		$status2=$this->db->update("product_availabe_accessory", $array, "accessory_id='$id' and product_id='$prd_id'");
		if($status2==0)
			{
				$_SESSION['ses_accessory_id'] = $id;
				$_SESSION['ses_cart_name']	=	$cart_name;
				$newcart_name	=	$cart_name;
			}
		
		}
		else
		{
		//echo "For the accessory only";
		$array = array("cart_name"=>$cart_name);
		$this->db->update("product_accessories", $array, "id='$id'");
		}
		#---
		
		if($chk_adjust_price=="1")
		{
			$newadjust_price	=	$adjust_price;
			if($prd_id=="")	{
				$_SESSION['ses_adjust_price']	=	$adjust_price;
			}
			$array1 			= 	array("adjust_price"=>$adjust_price);
			$status3=$this->db->update("product_availabe_accessory", $array1, "accessory_id='$id' and product_id='$prd_id'");
			if($status3==0)
			{
				$_SESSION['ses_accessory_id'] 	= $id;
				$_SESSION['ses_adjust_price']	=	$adjust_price;
				$newadjust_price	=	$adjust_price;
			}
		}
		else
		{
		   $this->db->query("update product_accessories set adjust_price='$adjust_price' where id='$id'");
			
		}
		
		
		
		//array structure(accessoryid,productid,adjustprice,adjustweight,cartname)
		
		
			$MultArr	=	$_SESSION['MultiAccessory'];
			$MultArr[]	=	array($id,$prd_id,$newadjust_price,$newadjust_weight,$newcart_name);
			$_SESSION['MultiAccessory']		=	$MultArr;
			$_SESSION['acc_id']				=	$id;
			$_SESSION['adjust_weight']		=	$newadjust_weight;
			$_SESSION['adjust_price']		=	$newadjust_price;
			$_SESSION['adjust_cartname']	=	$newcart_name;
			$_SESSION['acc_product_id']		=	$prd_id;
		
		
			//$array 			= 	array("adjust_price"=>$adjust_price,"adjust_weight"=>$adjust_weight);
			//$this->db->update("product_accessories", $array, "id='$id'");
		
		return true;
	}
	function AddnewAccessory(&$req,$group_id,$cat_id)
		{
		extract($req);
		if(!trim($name))
			{
				$message 				=	"Name is required";
			}
		else
			{
			$active	='Y';
			$array 			= 	array('name'=>$name,"adjust_price"=>$adjust_price,"adjust_weight"=>$adjust_weight,"active"=>$active);
			if(trim($is_price_percentage))
				{
					$newarray=array("is_price_percentage"=>"Y");
					$array=array_merge($array,$newarray);
				}
				else
				{
					$newarray=array("is_price_percentage"=>"N");
					$array=array_merge($array,$newarray);
				}
				if(trim($is_weight_percentage))
				{
					$newarray=array("is_weight_percentage"=>"Y");
					$array=array_merge($array,$newarray);
				}
				else
				{
					$newarray=array("is_weight_percentage"=>"N");
					$array=array_merge($array,$newarray);
				}
			$accessory_id	=	$this->db->insert("product_accessories", $array, "id='$id'");
			$this->db->insert("product_accessory_group_items", array('group_id'=>$group_id,'accessory_id'=>$accessory_id));
			$ins			=	array("category_id"=>$cat_id,"accessory_id"=>$accessory_id);
			$this->db->insert("category_accessory", $ins);
			return $accessory_id;
			}
		return 0;
		}
	function accessoryAddEdit(&$req,$file,$tmpname)
	{
		global $global;
		global $store_id;
		
	//print_r($req);exit;
		
		$field_arr = $req; 
		
		$is_add_toproducts=$field_arr['is_add_toproducts'];
		$accessory_stores	=	$field_arr['accessory_stores'];
		
		unset($field_arr['sId'],$field_arr['accessory_stores'],$field_arr['btn_acsry_submit'],$field_arr['is_add_toproducts'],$field_arr['fId'],$field_arr['cat_id'],$field_arr['accessory_category'],$field_arr['new_html_desc'],$field_arr['hf2'],$field_arr['own_store'],$field_arr['image_ext']);
		
		extract($req);		
	
		if ($file){
			$dir			=	SITE_PATH."/modules/product/images/accessory/";
			$file1			=	$dir."thumb/";
			$resource_file	=	$dir.$file;
			$path_parts 	= 	pathinfo($file);
		}

		if($image_type=='A' && $path_parts['extension']==''){
			$image_extension	=	"jpg";
		}else{
			$image_extension	=	$path_parts['extension'];
		}	
		
		
		# Code added for gif conversion
		if($this->config['accessory_imagetype'] == 'gif')
			$image_extension	=	'gif';	
		
		
		
		if(empty($html_desc))//it will get the value from the hidden filed; The hidden field is used for the browsers that does not support the javascript
			{
				$html_desc=$new_html_desc;
			}
		/*
		* For Art Backgounds 
		* START
		*/
		$pattern = "/^[-,+,0-9]{1}[0-9]{2,3}/";
		$resize_sn_senti_no 	= $resize_sn_senti_no ? $resize_sn_senti_no  : '100';
		$resize_sn_senti_yes	= $resize_sn_senti_yes ? $resize_sn_senti_yes  : '100';
		$resize_dn_senti_no 	= $resize_dn_senti_no ? $resize_dn_senti_no  : '100';
		$resize_dn_senti_yes 	= $resize_dn_senti_yes ? $resize_dn_senti_yes  : '100';
		if(!trim($name))
		
		{
		
			$message 				=	"Name is required";
		}
		
		
		/**
		* END
		*/	
		else
		{
		
			if ($file)
			{
				
				$field_arr["image_extension"] = $image_extension;
				/*$array 			= 	array("cart_name"=>$cart_name,"display_name"=>$display_name,"keyword"=>$keyword,"html_desc"=>$html_desc,"name"=>$name,"description"=>$description,"image_extension"=>$image_extension,"type"=>$type,"adjust_price"=>$adjust_price,"adjust_weight"=>$adjust_weight,"active"=>$active, "color1"=>$color1, "color2"=>$color2, "color3"=>$color3);*/
				
			}
			
			
			if(trim($is_price_percentage))
			{
				$field_arr["is_price_percentage"] = "Y";
			}
			
			else
			{
				$field_arr["is_price_percentage"] = "N";
			}
			
			
			if(trim($is_weight_percentage))
			{
				$field_arr["is_weight_percentage"] = "Y";
				
			}
			else
			{
				$field_arr["is_weight_percentage"] = "N";
			}
			if($this->config["show_quantity_independent_price"]=="Y"){
				if(trim($independent_qty))
				{
					$field_arr["independent_qty"] = "Y";
					
				}
				else
				{
					$field_arr["independent_qty"] = "N";
				}
			}
			if(trim($active))
			{
				$field_arr["active"] = "Y";
				
			}
			else
			{
				if( $_REQUEST['manage']=='manage')
					$field_arr["active"] = "Y";
					else
					$field_arr["active"] = "N";
			}
			if($page_title){
				$field_arr["page_title"]=$page_title;
			}
			if($meta_description){
				$field_arr["meta_description"]=$meta_description;
			}
			if($meta_keywords){
				$field_arr["meta_keywords"]=$meta_keywords;
			}
			
			if( $_REQUEST['manage']=='')
			{
				if(trim($active))
				{
					$field_arr["visibility_store"] = 1;
				}
				else
				{
					$field_arr["visibility_store"] = 0;
				}
			}	
						
			// for store robin 7-11-2007
			/*
						if($_REQUEST['manage']=='manage' && $id>0)
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
			*/
			
			//$this->db->update("master_category", $catearray, "category_id='$category_id'");
			if($id)
			{
				$arr = $this->splitFields($field_arr,"product_accessories");

				if ($store_id !=''){//If editing from the store/manage...
					
					$old_accessory_det	=	$this->getAccessoryDetailsForEditFromStore($req['id']);
					//print_r($old_accessory_det);exit;
					if($old_accessory_det)//If the accessory was already edited...update it
						{
							$arr[0]["id"] = $id;
							$arr[0]["parent_id"] = $old_accessory_det;
							$this->db->update("product_accessories", $arr[0], "id='$id'");
						}
					else{//If editing for the first time...insert it
					//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~code added on 10 sept 2008
					$ac_value =$this->GetAccessory($id);
					$arr = $this->splitFields($ac_value,"product_accessories");
					$arr[0]["adjust_price"]=$_REQUEST['adjust_price'];
					//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~end~~~~~
					
					$arr[0]["id"] = '';
					$arr[0]["image_extension"] = $req['image_ext'];
					//Flag 'parent_id' is set to 1 when an accessory is editod from the store/manage
					//changeg by adsrsh for storeing the old product id
					$arr[0]["parent_id"] = $id;
					
					
					
					$id = $this->db->insert("product_accessories", $arr[0]);
															
					$dir			=	SITE_PATH."/modules/product/images/accessory/";
					$file1			=	$dir."thumb/";
					copy($dir.$req['id'].".".$req['image_ext'],$dir.$id.".".$req['image_ext']);
					copy($file1.$req['id'].".".$req['image_ext'],$file1.$id.".".$req['image_ext']);	
					
					
					$old_accessory_id = $this->Getstore_accessoryId($req['id'],$store_id);
					$ref_id = $old_accessory_id[0]['id'];
					if(trim($active))
						$store_acc_active = "Y";
					else
						$store_acc_active = "N";
						
					$newarray = array();
					$newarray['store_id']=$store_id;
					$newarray['accessory_id']=$id;
					$newarray['active']=$store_acc_active;
					$this->db->update("store_accessory",$newarray,"id='$ref_id'");
					}
					
				}
				else{//If editing from the admin panel...
					$arr[0]["id"] = $id;
					$this->db->update("product_accessories", $arr[0], "id='$id'");
					$this->updateStoreAccessory($arr[0],$id);
				}

								
				// 18-07-07 updating the not linking store details in to accessory_notin_store
				$this->db->query("DELETE FROM accessory_notin_store WHERE assessory_id='$id'");
				if(count($accessory_stores)>0)
				{
					foreach ($accessory_stores as $accy_store_id)
					{
						$store_arr			=	array("assessory_id"=>$id,"store_id"=>$accy_store_id);
						$this->db->insert("accessory_notin_store", $store_arr);
					}
				}			
							
				// end -----
				
				$table_id=$arr[1]["table_id"];
				if ($table_id>0)
				{
					$sql_check = "select id from custom_fields_list where table_key=$id and table_id=$table_id";
					$rs_custom = $this->db->get_row($sql_check,0);
					if (count($rs_custom)>0)
					{
						$this->db->update("custom_fields_list",$arr[1] ,"table_key=$id and table_id=$table_id");
					}
					else 
					{
						$arr[1]["table_key"] = $id;
						$this->db->insert("custom_fields_list", $arr[1]);
					}
				}	
				/*
				$arr = $this->splitFields($arr,$table_name);
		$arr[0]["id"] = $id;
        $this->db->update($table_name,$arr[0] ,"id=$id");
		$table_id=$arr[1]["table_id"];
		$this->db->update("custom_fields_list",$arr[1] ,"table_key=$id and table_id=$table_id");				
				
				*/
			}
			else//adding fresh new accessory.
			{
				
				/*$arr=$this->splitFields($arr,'album_music');		
		$id = $this->db->insert("album_music", $arr[0]);
		$arr[1]["table_key"]=$id;
		$this->db->insert("custom_fields_list", $arr[1]);*/
		//print_r($_POST);exit;
		//$_POST['comments']="";
				$arr = $this->splitFields($field_arr,'product_accessories');
				$id  = $this->db->insert("product_accessories", $arr[0]);
				
##############Assign base category id to an accessory.
#That is, this adds a row to the category_accessory table with category_id = base category id.
				/*if($req['aid'])
					{
						$sal = array();
						$sal['category_id']=$req['aid'];
						$sal['accessory_id']=$id;
						$this->db->insert("category_accessory", $sal);
					}*/
##############
				// 18-07-07 updating the not linking store details in to accessory_notin_store
				if(count($accessory_stores)>0)
				{
					foreach ($accessory_stores as $accy_store_id)
					{
						$store_arr			=	array("assessory_id"=>$id,"store_id"=>$accy_store_id);
						$this->db->insert("accessory_notin_store", $store_arr);
					}
				}			
							
				// end -----
				
				
				
				
				if ($arr[1]["table_id"]>0)
				{
					$arr[1]["table_key"] = $id;
					$this->db->insert("custom_fields_list", $arr[1]);
				}		
			}
			
			//Assigning Accessory to store
			
			if($_POST['hf2'])
			{				$this->insertRelated($req,$id);}
			
			//echo "<pre>";
			//print_r($req);
			//exit;
			//Assigning product to category
				
				
				if(count($accessory_category)>0)
					{
						$this->db->query("DELETE FROM category_accessory WHERE accessory_id='$id'");
						$accessory_cat_ids	=	"'0'";
						foreach ($accessory_category as $accessory_category_id)
							{
								$accessory_cat_ids	=	$accessory_cat_ids." , '".$accessory_category_id."'";
								$ins			=	array("category_id"=>$accessory_category_id,"accessory_id"=>$id);
								$this->db->insert("category_accessory", $ins);
							
							# modified on 02-07-07 for adding new accessories to products
							if($is_add_toproducts)
							{ 
							
							$accsry_qry		=	"select distinct(product_id),group_id from product_availabe_accessory where category_id='$accessory_category_id'";
							$accsy_rs = $this->db->get_results($accsry_qry, ARRAY_A);
							if(count($accsy_rs)>0)
							{
								for($i=0;$i<count($accsy_rs);$i++)
								{ 
									$accsry_prd_id	=	$accsy_rs[$i]["product_id"];
									$accsry_grp_id	=	$accsy_rs[$i]["group_id"];
									$accessory_array = array("product_id"=>$accsry_prd_id,"category_id"=>$accessory_category_id,"accessory_id"=>$id,"group_id"=>$accsry_grp_id);
									$prd_acsy_qry	=	"select * from product_availabe_accessory where product_id='$accsry_prd_id' and category_id='accessory_category_id' and accessory_id='$id'";
									$prd_acsy_rs 	= $this->db->get_results($prd_acsy_qry, ARRAY_A);
									if(count($prd_acsy_rs)==0)
									{
										$this->db->insert("product_availabe_accessory", $accessory_array);
									}
									
								}
							
							}
							}
							# end modified on 02-07-07 for adding new accessories to products

							
							
							}
														
							
							#---- deleting the old products from product avilable accessory ---
							$this->db->query("DELETE FROM product_availabe_accessory WHERE accessory_id=$id AND category_id NOT IN($accessory_cat_ids) ");
					}
			if ($file)
			{
				
				/*$save_filename	=	$id.".".$path_parts['extension'];
				$new_name		=	$id."_des_.".$path_parts['extension'];		--Code commented for GIF conversion*/
				
				# Following code added for GIF conversion 
				$SourceExtension	=	$path_parts['extension'];
				$save_filename		=	$id.".".$image_extension;
				$new_name			=	$id."_des_.".$image_extension;
				
				list($thumb_width,$thumb_height)	=	split(',',$this->config['accessory_thumb_image']);
				_upload($dir,$save_filename,$tmpname,0,$thumb_width,$thumb_height, $SourceExtension);
				list($thumb_width2,$thumb_height2)	=	split(',',$this->config['accessory_thumb2_image']);
				list($thumb_width3,$thumb_height3)	=	split(',',$this->config['accessory_thumb3_image']);
				$path=$dir;
				$thumb=$dir."thumb/";
				if($thumb_width>0 && $thumb_height>0)
				{
					thumbnail($path,$thumb,$save_filename,$thumb_width,$thumb_height,$mode,$save_filename);
				}
				if($thumb_width2>0 && $thumb_height2>0)
				{
					thumbnail($path,$thumb,$save_filename,$thumb_width2,$thumb_height2,$mode,$new_name);
				}
				//upload listing images

				# $new_name		=	$id."_List_.".$path_parts['extension']; -- Code modified fir GIF conversion
				
				$new_name		=	$id."_List_.".$image_extension;
				if($thumb_width3>0 && $thumb_height3>0)
				{
					thumbnail($path,$thumb,$save_filename,$thumb_width3,$thumb_height3,$mode,$new_name);
				}
				
			}

			return array('status'=>true,'id'=>$id);
		}
		return array('status'=>false,'message'=>$message);
	}
	function GetAllAccessoryCategoty($accessory_id=0)
	{
		if($accessory_id>0)
		{
			$qry				=	"select category_id from category_accessory where accessory_id=$accessory_id";
			$rs['category_id'] 	= 	$this->db->get_col($qry, 0);
			return $rs;
		}
	}
	function listAllAccessoryGroups($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy)
	{
		
		$qry		=	"select * from product_group_accessories ORDER BY display_order";		
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
	
	function accessoryGroupAddEdit(&$req)
	{
		extract($req);
		if(!trim($name))
		{
			$message="Name should not be blank";
			}
		else
		{	
		//echo $accessory;
			$selected="";
			$cnt=count($accessory);
					for($i=0;$i<$cnt;$i++)
					{	
						if($selected=="")
						{
							$selected=$accessory[$i][0];
						}
						else
						{
							$selected=$selected.",".$accessory[$i][0];
						}
						
					}
				if($parameter)
					{
					$parameter='Y';
					}
				else
					{
					$parameter='N';
					}
					$array = array("group_name"=>$name,"products"=>$selected);
				if($id) {
						$array['group_id'] 	= 	$id;
							$this->db->update("product_group_accessories", $array, "group_id='$id'");
				} else {
					$this->db->insert("product_group_accessories", $array);
					$id = $this->db->insert_id;
				}
				
				return true;
		}
		return $message;
			
	}
	
	function GetAccessoryGroup($id=0) {
		if($id>0)
		{
			$rs = $this->db->get_row("SELECT * FROM product_group_accessories WHERE group_id='{$id}'", ARRAY_A);
			return $rs;
		}
	}
	function GetProductAccessoryGroup($id=0,$prd_id=0) {
		if($id>0 && $prd_id>0)
		$rs = $this->db->get_row("SELECT * FROM product_availabe_accessory WHERE accessory_id=$id AND product_id=$prd_id", ARRAY_A);
			return $rs;
		}
	
	function accessoryGroupDelete($id=0)
	{
	if($id>0)
		{
			$this->db->query("DELETE FROM product_accessory_group  WHERE id='$id'");
		return true;
		}
	return false;
	}
function accessorySettingsDelete($group_id=0)
	{
	if($group_id>0)
		{
			$this->db->query("DELETE FROM product_accessory_exclude  WHERE group_id='$group_id'");
		return true;
		}
	return false;
	}
	function accessoryDelete($id=0)
		{
		if($id>0)
			{
				
				$this->db->query("DELETE FROM product_accessories  WHERE id='$id'");	
				$this->db->query("DELETE FROM product_accessories  WHERE parent_id='$id'");				
				$sql = "Select table_id from custom_fields_table where table_name='product_accessories'";
				$rs = $this->db->get_row($sql);
				
				if (count($rs)>0)
				{
					$table_id = $rs->table_id;
					$sql = "Delete from custom_fields_list where table_id=$table_id and table_key=$id";
					$this->db->query($sql);
				}
				
				$this->db->query("DELETE FROM product_accessory_group_items  WHERE accessory_id='$id'");
				$this->db->query("DELETE pa,ps FROM product_availabe_accessory pa,product_accessory_store ps WHERE pa.accessory_id='$id' and pa.id=ps.available_id");
				$this->db->query("DELETE FROM product_availabe_accessory  WHERE accessory_id='$id'");
				$this->db->query("DELETE FROM store_accessory_default  WHERE accessory_id='$id'");
				$this->db->query("DELETE FROM category_accessory WHERE accessory_id='$id'");
				$this->db->query("DELETE FROM product_accessory_exclude WHERE accessory_id='$id'");
			return true;
			}
		return false;
		}
	function GetAccessoryGroupSelected($group_id=0)
	{
		if($group_id>0)
		{
			$qry="SELECT * FROM product_group_accessories WHERE group_id='{$group_id}'";
			$rs = $this->db->get_row($qry, ARRAY_A);
			$selected=explode(",",$rs['products']);
			$arr['accessory_id']=$selected;
			return $arr;
		}
	}
	function accessoryList($category_id,$store_id,$pageNo,$limit,$param,$output=OBJECT,$orderBy,$search_fields='',$search_values='',$criteria="="){
		list($qry,$table_id,$join_qry)=$this->generateQry('product_accessories','d','a'); // DO NOT CHANGE THIS PART -  Retheesh
		if($search_fields)
		{
			list($qry_cs)=$this->getCustomQry('product_accessories',$search_fields,$search_values,$criteria,'a','d');	
		}
		if($category_id){ 
			 $join_qry .= " INNER JOIN category_accessory ca on ca.accessory_id=a.id"	;
			
		}
			if($store_id){
				$query ="SELECT distinct(a.id),a.*,$qry FROM product_accessories a $join_qry 
				 		LEFT JOIN  category_accessory b ON (a.id=b.accessory_id),store_accessory  c   
						 WHERE a.active='Y' AND a.id=c.accessory_id AND c.store_id=$store_id";
			}else{
				$query ="SELECT distinct(a.id),a.*,$qry FROM product_accessories a $join_qry  
				 		LEFT JOIN category_accessory b ON (a.id=b.accessory_id)  WHERE a.active='Y'";
				
			}
			
		if($category_id){
			 //$query= $query." AND ca.category_id=$category_id";if any problem happens while accessories
			 // are listing uncomment this line and comment the line jst below.:)salim
			 $query= $query." AND ca.category_id  IN (".$category_id.")";
		}	
		//to get the artwork by user-Vipin
		$user_id			=	$_REQUEST["u_id"] ? $_REQUEST["u_id"] : "";
		if ($user_id>0){
		$query.= " AND d.field_1 = $user_id";
		}
		#For Custom Fields - Retheesh 
		if($qry_cs)
		{
			 $wh =retWhere($query);
			 $query= $query." $wh $qry_cs";
		}
		#For Custom Fields - End




		
		//echo $query;
		$rs			=	$this->db->get_results_pagewise($query, $pageNo, $limit, $param, $output=OBJECT,$orderBy,'1');
		return $rs;	 
	}
	
	
function accessoryLists($category_id,$store_id,$pageNo,$limit,$param,$output=OBJECT,$orderBy,$search_fields='',$search_values='',$criteria="=",$name=''){
	
	list($qry,$table_id,$join_qry)=$this->generateQry('product_accessories','d','a'); // DO NOT CHANGE THIS PART -  Retheesh
	if($search_fields){
		list($qry_cs)=$this->getCustomQry('product_accessories',$search_fields,$search_values,$criteria,'a','d');	
	}
	
	if($category_id){ 
		$join_qry .= " INNER JOIN category_accessory ca on ca.accessory_id=a.id";	
	}

	if($store_id){
		$query ="SELECT distinct(a.id),a.*,$qry FROM product_accessories a $join_qry 
				LEFT JOIN  category_accessory b ON (a.id=b.accessory_id),store_accessory  c   
					WHERE c.active='Y' and  a.active='Y'  AND a.id=c.accessory_id AND c.store_id=$store_id ";
	}else{
		$query ="SELECT distinct(a.id),a.*,$qry FROM product_accessories a $join_qry  
				LEFT JOIN category_accessory b ON (a.id=b.accessory_id)  WHERE a.active='Y'
				AND a.parent_id ='' ";
	}
		
	$orderBy="a.name";
				
	if($category_id){
			//$query= $query." AND ca.category_id=$category_id";if any problem happens while accessories
			// are listing uncomment this line and comment the line jst below.:)salim
			$query= $query." AND ca.category_id  IN (".$category_id.")";
	}	
	if($name){
			$query= $query." AND a.name LIKE '%$name%'";
		}
	//to get the artwork by user-Vipin
	$user_id			=	$_REQUEST["u_id"] ? $_REQUEST["u_id"] : "";
	if ($user_id>0){
	$query.= " AND d.field_1 = $user_id";
	}
	#For Custom Fields - Retheesh 
	if($qry_cs)
	{
			$wh =retWhere($query);
		$query= $query." $wh $qry_cs";
		$orderBy="a.display_order";	
	}
		#For Custom Fields - End
		
		$rs	= $this->db->get_results_pagewise_ajax($query, $pageNo, $limit, $param, $output=OBJECT,$orderBy,1);
		
		return $rs;	
}

	function storeGetDetails () {
		$sql		= 	"SELECT id, name FROM store WHERE 1";
		$rs['id'] 	= 	$this->db->get_col($sql, 0);
		$rs['name'] = 	$this->db->get_col($sql, 1);
	
		return $rs;
	}
	#----------------- 18-07-07 ---
	function GetAllStoresforAccessory()
	{
		$qry				=	"select * from store where active='Y'";
		$rs['store_id'] 	= 	$this->db->get_col($qry, 0);
		$rs['store_name'] 	= 	$this->db->get_col($qry, 5);
		return $rs;
	}
	function GetselectedStoresAccessory($acc_id)
	{
		$qry	=	"select * from accessory_notin_store where assessory_id='$acc_id'";
		$rs['store_id'] 	= 	$this->db->get_col($qry, 2);
			return $rs;
	}
	#--------------------------------
	function getRelatedStore($id)
	{
		$sql		=	 "SELECT `store`.`id`,
							`store`.`name`
						FROM
							`store`
						Inner Join `store_accessory` ON `store`.`id` = `store_accessory`.`store_id`
						WHERE
							`store_accessory`.`accessory_id` =  '$id'";  

		$rs['id'] 	= 	$this->db->get_col($sql, 0);
		$rs['name'] = 	$this->db->get_col("", 1);
		return $rs;

	}
		function getRelatedGroupsStore($id)
	{
		$sql		=	 "SELECT `store`.`id`,
							`store`.`name`
						FROM
							`store`
						Inner Join `store_accessory_group` ON `store`.`id` = `store_accessory_group`.`store_id`
						WHERE
							`store_accessory_group`.`group_id` =  '$id'";  

		$rs['id'] 	= 	$this->db->get_col($sql, 0);
		$rs['name'] = 	$this->db->get_col("", 1);
		return $rs;

	}
	function insertRelated(&$req,$accessory_id)
	{
		extract($req);
		$this->deleteRelatedStore($accessory_id);
		if($hf2)
		{
			$rlitem		=	explode(",",$hf2);
			$cntr		=	count($rlitem);
			
			for($i=0;$i<$cntr;$i++)
			{
				$this->relatedInsertStore($accessory_id,$rlitem[$i]);
			}
		}
		return true;
	}
	function deleteRelatedStore($id)
	{
		$this->db->query("DELETE FROM store_accessory WHERE accessory_id='$id'");

	}
	function relatedInsertStore($id,$relatedaccessory)
	{
		$array 		=	array("accessory_id"=>$id,"store_id"=>$relatedaccessory);
		$this->db->insert("store_accessory", $array);

	}
	
	
	
	//groups---->
	function insertgroupsRelated(&$req,$group_id)
	{
		extract($req);
		$this->deletegroupsRelatedStore($group_id);
		if($hf2)
		{
			$rlitem		=	explode(",",$hf2);
			$cntr		=	count($rlitem);
			
			for($i=0;$i<$cntr;$i++)
			{
				$this->relatedgroupsInsertStore($group_id,$rlitem[$i]);
			}
		}
		return true;
	}
	function deletegroupsRelatedStore($id)
	{
		$this->db->query("DELETE FROM store_accessory_group WHERE group_id='$id'");

	}
	function relatedgroupsInsertStore($id,$relatedaccessory)
	{
		$array 		=	array("group_id"=>$id,"store_id"=>$relatedaccessory);
		$this->db->insert("store_accessory_group", $array);

	}
	//groups<---
	
	
	
	function AccessorymassUpdate(&$req,$aid)
	{
	
	
		extract($req);

		//--------------this is done by Vinoy on 10 -09-07
		unset($req['submit1']);
		//------------------
		$field_arr = $req;
		
		$del_cat_flag	=	0;
		$del_store_flag	=	0;
		$del_prd_flag	=	0;
		//print_r($append);exit;
		// updating only the selected fields 19-07-07
		$mass_array = array();
		if(trim($field_arr["mass_field1"]))
		{
			$work_array=array("name"=>"");
			$mass_array=array_merge($mass_array,$work_array);
			unset($field_arr['mass_field1']);
		}
		else
		{ 
			unset($field_arr['name']); 
		}
		if(trim($field_arr["mass_field2"]))
		{
					$work_array=array("adjust_price"=>"");
			$mass_array=array_merge($mass_array,$work_array);
			unset($field_arr['mass_field2']);
		}
		else
		{ 
			unset($field_arr['adjust_price']); 
		}
		if(trim($field_arr["mass_field3"]))
		{
			$work_array=array("adjust_weight"=>"");
			$mass_array=array_merge($mass_array,$work_array);
			unset($field_arr['mass_field3']);
		}
		else
		{ 
			unset($field_arr['adjust_weight']); 
		}
		
		if(trim($field_arr["mass_field16"]))
		{
				
			$work_array=array("display_name"=>"");
			$mass_array=array_merge($mass_array,$work_array);
			unset($field_arr['mass_field16']);
		}
		else
		{ 
			unset($field_arr['display_name']); 
		}
		
		if(trim($field_arr["mass_field17"]))
		{
			$work_array=array("display_order"=>"");
			$mass_array=array_merge($mass_array,$work_array);
			unset($field_arr['mass_field17']);
		}
		else
		{ 
			unset($field_arr['display_order']); 
		}
		
		
		if(trim($field_arr["mass_field4"]))
		{
			// category
			if($append!='Y')
					$del_cat_flag	=	1;
					//$this->db->query("DELETE FROM category_accessory WHERE accessory_id='$acc_id'");
			
			unset($field_arr['mass_field4']);
		}
		else
		{ 
			unset($field_arr['accessory_category']); 
		}
		if(trim($field_arr["mass_field5"]))
		{
			//$work_array=array("type"=>"");
			//$mass_array=array_merge($mass_array,$work_array);
			unset($field_arr['mass_field5']);
		}
		else
		{ 
			unset($field_arr['type']); 
		}
		//if(trim($active))
		
		if(trim($field_arr["mass_field6"]))
		{
		
		//echo $qry="update product_accessories set active='Y' where id='$aid'";
		//exit;
		
		//mysql_query($qry);
		
			$work_array=array("active"=>"Y");
			$mass_array=array_merge($mass_array,$work_array);
			unset($field_arr['mass_field6']);
			
		}
		else
		{ 
		    $work_array=array("active"=>"N");
			$mass_array=array_merge($mass_array,$work_array);
			//unset($field_arr['active']); 
		}
		
		if(trim($field_arr["mass_field7"]))
		{
			$work_array=array("description"=>"");
			$mass_array=array_merge($mass_array,$work_array);
			unset($field_arr['mass_field7']);
		}
		else
		{ 
			unset($field_arr['description']); 
		}
		if(trim($field_arr["mass_field8"]))
		{
			$work_array=array("keyword"=>"");
			$mass_array=array_merge($mass_array,$work_array);
			unset($field_arr['mass_field8']);
		}
		else
		{ 
			unset($field_arr['keyword']); 
		}
		if(trim($field_arr["mass_field9"]))
		{
			$work_array=array("color1"=>"");
			$mass_array=array_merge($mass_array,$work_array);
			unset($field_arr['mass_field9']);
		}
		else
		{ 
			unset($field_arr['color1']); 
		}
		if(trim($field_arr["mass_field10"]))
		{
			$work_array=array("color2"=>"");
			$mass_array=array_merge($mass_array,$work_array);
			unset($field_arr['mass_field10']);
		}
		else
		{ 
			unset($field_arr['color2']); 
		}
		if(trim($field_arr["mass_field11"]))
		{
			$work_array=array("color3"=>"");
			$mass_array=array_merge($mass_array,$work_array);
			unset($field_arr['mass_field11']);
		}
		else
		{ 
			unset($field_arr['color3']); 
		}
		if(trim($field_arr["mass_field12"]))
		{
			$work_array=array("html_desc"=>"");
			$mass_array=array_merge($mass_array,$work_array);
			unset($field_arr['mass_field12']);
		}
		else
		{ 
			unset($field_arr['html_desc']); 
		}
		if(trim($field_arr["mass_field13"]))
		{
			// assign accessory to stores
			if($append!='Y')
					$del_store_flag	=	1;
					//$this->db->query("DELETE FROM store_accessory WHERE  accessory_id='$acc_id'");
			
			unset($field_arr['mass_field13']);
		}
		else
		{ 
			unset($field_arr['hf2']); 
		}
		if(trim($field_arr["mass_field14"]))
		{
			// Don't link Option to Products in these stores
			if($append!='Y')
					
			$del_prd_flag	=	1;
			//$this->db->query("DELETE FROM accessory_notin_store WHERE assessory_id='$acc_id'");
			
			unset($field_arr['mass_field14']);
		}
		else
		{ 
			unset($field_arr['accessory_stores']); 
		}
		if(trim($field_arr["mass_field18"]))
		{
			$work_array=array("page_title"=>"");
			$mass_array=array_merge($mass_array,$work_array);
			unset($field_arr['mass_field18']);
		}
		else
		{ 
			unset($field_arr['page_title']); 
		}
		if(trim($field_arr["mass_field19"]))
		{
			$work_array=array("meta_description"=>"");
			$mass_array=array_merge($mass_array,$work_array);
			unset($field_arr['mass_field19']);
		}
		else
		{ 
			unset($field_arr['meta_description']); 
		}
		if(trim($field_arr["mass_field20"]))
		{
			$work_array=array("meta_keywords"=>"");
			$mass_array=array_merge($mass_array,$work_array);
			unset($field_arr['mass_field20']);
		}
		else
		{ 
			unset($field_arr['meta_keywords']); 
		}
		
		
		//if($is_add_toproducts)
		
		
		/*if(count($accessory_category)>0)
					{
						$accessory_cat_ids	=	"'0'";
						foreach ($accessory_category as $accessory_category_id)
							{
								$accessory_cat_ids	=	$accessory_cat_ids." , '".$accessory_category_id."'";
								$ins			=	array("category_id"=>$accessory_category_id,"accessory_id"=>$id);
								$this->db->insert("category_accessory", $ins);*/
								
			if(trim($field_arr["mass_field15"]))
			{
			
								
			 $id=$field_arr["del_id"];
			
			for($k=0;$k<count($id);$k++){
			 $sql            = "select  * from category_accessory  where accessory_id='$id[$k]'";
			
			$rs = $this->db->get_results($sql,ARRAY_A);
				
				for($j=0;$j<count($id);$j++){
				 $accessory_category=$rs[$j]['category_id'];
								
			          if(count($accessory_category)>0)
					{
					
						$accessory_cat_ids	=	"'0'";
						/*foreach ($accessory_category as $accessory_category_id)
							{
							echo "came";
					exit;*/
								$accessory_cat_ids	=	$accessory_cat_ids." , '".$accessory_category."'";
								$ins			=	array("category_id"=>$accessory_category,"accessory_id"=>$id[$k]);
								//$this->db->insert("category_accessory", $ins);
			
		
		//if(trim($field_arr["mass_field15"]))
			//{
			
			
			//$ins			=	array("accessory_id"=>$id);
			
							
				 $accsry_qry		=	"select distinct(product_id),group_id from product_availabe_accessory where category_id='$accessory_category'";
				
                 
							$accsy_rs = $this->db->get_results($accsry_qry, ARRAY_A);
							
							
							if(count($accsy_rs)>0)
							{
							
									
								for($i=0;$i<count($accsy_rs);$i++)
								{ 
									$accsry_prd_id	=	$accsy_rs[$i]["product_id"];
									$accsry_grp_id	=	$accsy_rs[$i]["group_id"];
									
									$accessory_array = array("product_id"=>$accsry_prd_id,"category_id"=>$accessory_category,"accessory_id"=>$id[$k],"group_id"=>$accsry_grp_id);
									
									$prd_acsy_qry	="select * from product_availabe_accessory where product_id='$accsry_prd_id' and category_id='$accessory_category' and accessory_id='$id[$k]'";
									
									$prd_acsy_rs 	= $this->db->get_results($prd_acsy_qry, ARRAY_A);
									if(count($prd_acsy_rs)==0)
									{
									$this->db->insert("product_availabe_accessory", $accessory_array);
										
									}else{
									
									}
								}
							
							}
				} 
           }
		
		}}
				
		/*	if(trim($field_arr["mass_field15"]))
				
				
		{
		
		
			$work_array=array("is_add_toproducts"=>"Y");
			$mass_array=array_merge($mass_array,$work_array);
			unset($field_arr['is_add_toproducts']);
		}
		else
		{ 
		   $work_array=array("is_add_toproducts"=>"N");
			$mass_array=array_merge($mass_array,$work_array);
			unset($field_arr['mass_field15']); 
		}*/


		// end updating only the selected fields 19-07-07
		
		$accessory_stores	=	$field_arr['accessory_stores'];
		$id	=	$del_id;
		unset($field_arr['sId'],$field_arr['fId'],$field_arr['cat_id'],$field_arr['accessory_search'],$field_arr['accessory_category'],$field_arr['hid_cat'],$field_arr['limit'],$field_arr['append'],$field_arr["id"],$field_arr["store_all"],$field_arr["store_related"]);
		unset($field_arr['accessory_stores'],$field_arr['del_id'],$field_arr['hf2'],$field_arr['is_add_toproducts'],$field_arr['mass_field15']);
		//print_r($field_arr);exit;
		/*for ($i=0;$i<sizeof($field_arr);$i++)
		{
			if (trim($field_arr[$i])=="")
			{
				//print "yes"."\n";
				//unset($field_arr[$i]);
			}
		}
		print_r($field_arr);
		exit;*/
		foreach ($field_arr as $key=>$value)
		{
			if (trim($field_arr[$key])=="")
			{
				unset($field_arr[$key]);
			}
		}
	
		//extract($req);
		$array = array();
		$id	=	$del_id; 
		unset($del_id);
	
		if(trim($field_arr["adjust_price"]))
		{
			//$newarray=array("adjust_price"=>$adjust_price);
			//$array=array_merge($array,$newarray);
			
			if(trim($field_arr["is_price_percentage"]))
			{
				//$newarray=array("is_price_percentage"=>"Y");
				//$array=array_merge($array,$newarray);
				$field_arr["is_price_percentage"] = "Y";
			}
			else
			{
				$field_arr["is_price_percentage"] = "N";
				//$newarray=array("is_price_percentage"=>"N");
				//$array=array_merge($array,$newarray);
			}
		}
		
		if(trim($field_arr["adjust_weight"]))
		{
			//$newarray=array("adjust_weight"=>$adjust_weight);
			//$array=array_merge($array,$newarray);
			
			if(trim($field_arr["is_weight_percentage"]))
			{
				$field_arr["is_weight_percentage"] = "Y";
				//$newarray=array("is_weight_percentage"=>"Y");
				//$array=array_merge($array,$newarray);
			}
			else
			{
				$field_arr["is_weight_percentage"] = "N";
				//$newarray=array("is_weight_percentage"=>"N");
				//$array=array_merge($array,$newarray);
			}
		}
		
		/*if(trim($description))
		{
			$newarray=array("description"=>$description);
			$array=array_merge($array,$newarray);
		}
		if(trim($keyword))
		{
			$newarray=array("keyword"=>$keyword);
			$array=array_merge($array,$newarray);
		}
		if(trim($color1))
		{
			$newarray=array("color1"=>$color1);
			$array=array_merge($array,$newarray);
		}
		if(trim($color2))
		{
			$newarray=array("color2"=>$color2);
			$array=array_merge($array,$newarray);
		}
		if(trim($color3))
		{
			$newarray=array("color3"=>$color3);
			$array=array_merge($array,$newarray);
		}
		if(trim($html_desc))
		{
			$newarray=array("html_desc"=>$html_desc);
			$array=array_merge($array,$newarray);
		}*/
		
		
		if(trim($field_arr["active"]))
		{
			$field_arr["active"] = "y";
			//$newarray=array("active"=>"y");
			//$array=array_merge($array,$newarray);
		}
		else
		{
			$field_arr["active"] = "n";
			//$newarray=array("active"=>"n");
			//$array=array_merge($array,$newarray);
		}
		
		if(count($id)>0)
			{
			foreach ($id as $acc_id)
				{		
				
				// updating selected fields 19-07-07
				if($append!='Y')
				{

					$this->db->update("product_accessories", $mass_array, "id='$acc_id'");
				}
				
				if($del_cat_flag==1)
				{
					$this->db->query("DELETE FROM category_accessory WHERE accessory_id='$acc_id'");
				}
				if($del_store_flag==1)
				{
					$this->db->query("DELETE FROM store_accessory WHERE  accessory_id='$acc_id'");
				}
				if($del_prd_flag==1)
				{
					$this->db->query("DELETE FROM accessory_notin_store WHERE assessory_id='$acc_id'");
				}
				//print_r($mass_array);exit;
				// end updating selected fields 19-07-07
				
				
					$arr = $this->splitFields($field_arr,"product_accessories");
				
				$arr[0]["id"] = $acc_id;
				$this->db->update("product_accessories", $arr[0], "id='$acc_id'");
				$table_id=$arr[1]["table_id"];
				if ($table_id>0)
				{
					$sql_check = "select id from custom_fields_list where table_key=$acc_id and table_id=$table_id";
					$rs_custom = $this->db->get_row($sql_check,0);
					if (count($rs_custom)>0)
					{
						$this->db->update("custom_fields_list",$arr[1] ,"table_key=$acc_id and table_id=$table_id");
					}
					else 
					{
						$arr[1]["table_key"] = $acc_id;
						$this->db->insert("custom_fields_list", $arr[1]);
					}
				}
				// updating accessory_notin_store tables for accessory in stores 19-07-07	
					
					
					if(count($accessory_stores)>0)
					{
						foreach ($accessory_stores as $accy_store_id)
						{
							$store_arr			=	array("assessory_id"=>$acc_id,"store_id"=>$accy_store_id);
							$this->db->insert("accessory_notin_store", $store_arr);
							
						}
					}		
					// end updating accessory_notin_store tables for accessory in stores	
					
					
					//$this->db->update("product_accessories", $field_arr, "id='$acc_id'");
					
					
					if(count($accessory_category)>0)
						{
							foreach ($accessory_category as $accessory_category_id)
								{
									$qry	=	"select * from category_accessory where category_id=$accessory_category_id and accessory_id=$acc_id";
									$rs		=	$this->db->get_results($qry,ARRAY_A);
									if(count($rs)==0)
										{
										$ins			=	array("category_id"=>$accessory_category_id,"accessory_id"=>$acc_id);
										$this->db->insert("category_accessory", $ins);
										}
								}
						}
						if ($store_related)
						{
							for($k=0;$k<sizeof($store_related);$k++)
							{
								
							}
						}
				}
			}//exit;
			return true;
	}
	function GetAccessoryByCategory($store_id=0,$category_id=0) {
		if($category_id>0)
		{
			if($store_id==0)
				$rs = $this->db->get_results("SELECT *,concat(pa.id,'_',ca.category_id) as new_item_id FROM product_accessories as pa,category_accessory as ca WHERE ca.category_id='{$category_id}' and ca.accessory_id=pa.id ORDER BY pa.display_order ASC", ARRAY_A);
			else
				$rs = $this->db->get_results("SELECT *,concat(pa.id,'_',ca.category_id) as new_item_id FROM product_accessories as pa,category_accessory as ca,store_accessory as sa WHERE sa.store_id=$store_id and sa.accessory_id=pa.id and ca.category_id='{$category_id}' and ca.accessory_id=pa.id", ARRAY_A);
			return $rs;
		}
	}
	function getAccessories_of_category($store_id=0)
	{
		$objCategory	=	new Category();
		$arr			=	array();
		$cat 			= 	$objCategory->getAllCategory_is_in_ui_normally();
		$i				=	0;
		if(count($cat)>0)
		{
			foreach ($cat as $category)
				{
					//print_r($category);
					$arr[$i]['category_id']		=	$category['category_id'];
					$arr[$i]['accessories']		=	$this->GetAccessoryByCategory($store_id,$category['category_id']);
					$arr[$i]['category_name']	=	$category['category_name'];
					$i++;
				}
		}
		//print_r($arr);
		//exit;
		
		return $arr;
	}


	function getAccessories_of_category_combo($store_id=0)
	{
		$objCategory	=	new Category();
		$arr			=	array();
		#$cat 			= 	$objCategory->getAllCategory_is_in_ui_normally();
		
		$qry				=	"SELECT ms. *
										FROM master_category ms
									   WHERE ms.is_in_ui = 'Y'
									     AND ms.active = 'y'
									ORDER BY ms.category_name";
		$rs['cat_id'] 	= 	$this->db->get_col($qry, 0);
		$rs['cat_name'] 	= 	$this->db->get_col($qry, 2);
		return $rs;
	}
	
	
	function GetAccessorySettingsByGroup($group_id)
	{
		$rs	=	$this->GetAllAccessorySettingsGroup($group_id);
		if(count($rs)>0)
		{
		   $i=0;
		   foreach ($rs as $row)
		   {
			       $accessory_id	=	$row['accessory_id'];
			       $accessory	    =	$this->GetAccessory($accessory_id);
			       $rs[$i]      	=	$accessory;
			       $i++;
			}
		 return $rs;
		}
	}

	
	function GetAccessorySettingsByRequest($rs)
	{
		if(count($rs)>0)
		{
		   $i=0;
		   foreach ($rs as $row)
		   {       
			       $accessory_id	=	$row;
			       $accessory	    =	$this->GetAccessory($accessory_id);
			       $rs[$i]      	=	$accessory;
			       $i++;
			}
		    return $rs;
		}	
	}
	
	
	
	
	
	function listAllAccessoriesByCategory($pageNo=0, $limit = 10, $params='', $output=ARRAY_A, $orderBy,$store_id='0',$category_id='0')
	{
		
		if($store_id==0)
		$qry ="SELECT *,concat(pa.id,'_',ca.category_id) as new_item_id FROM product_accessories as pa,category_accessory as ca WHERE ca.category_id='{$category_id}' and ca.accessory_id=pa.id";
		else
		$qry ="SELECT *,concat(pa.id,'_',ca.category_id) as new_item_id FROM product_accessories as pa,category_accessory as ca,store_accessory as sa WHERE sa.store_id=$store_id and sa.accessory_id=pa.id and ca.category_id='{$category_id}' and ca.accessory_id=pa.id";
		
		
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function GetConformationRequest($id=0)
	{
	$qry="SELECT mc.category_id,mc.additional_customization_request as additional_customization_request,mc.customization_text_required as customization_text_required,mc.is_monogram as is_monogram FROM `product_accessories` pa,master_category mc where pa.category_id=mc.category_id and pa.id='$id'";
	$rs = $this->db->get_row($qry, ARRAY_A);
//	echo $qry;
	//exit;
	return $rs;
	}
///////////////////new tables/////////////////
/*



*/
function listAllAccessoryGroup($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy)
	{
		$qry		=	"select * from product_accessory_group";		
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
function GetAccessoryGroupName($group_id=0)
	{
		if($group_id>0)
		{
			$qry="SELECT * FROM product_accessory_group WHERE id='{$group_id}'";
			$rs = $this->db->get_row($qry, ARRAY_A);
			return $rs;
		}
	}
function GetAccessoryGroups($group_id=0)
	{
		if($group_id>0)
			{
			$qry="SELECT * FROM product_accessory_group_items WHERE group_id='{$group_id}'";
			$rs['accessory_id'] = $this->db->get_col($qry, 2);
			return $rs;
			}
	}

function GetCategoryGroups($group_id=0)
	{
		if($group_id>0)
			{
			$qry="SELECT * FROM product_accessory_group_categories WHERE group_id='{$group_id}'";
			$rs['category_id'] = $this->db->get_col($qry, 2);
			return $rs;
			}
	}	
function AddEditaccessoryGroup(&$req)
	{
	extract($req);
	if(!trim($group_name))
		{
		$message="Name required";
		}
	else
		{
		if($parameter)
			{
			$parameter='Y';
			}
		else
			{
			$parameter='N';
			}	
		$array = array("group_name"=>$group_name,"description"=>$description,"parameter"=>$parameter,"display_order"=>$display_order);
		if($group_id) 
			{
			$array['id'] 	= 	$group_id;
			$this->db->update("product_accessory_group", $array, "id='$group_id'");
			} 
		else 
			{
			$this->db->insert("product_accessory_group", $array);
			$group_id = $this->db->insert_id;
			}
			//update store_accessory_group
				$this->insertgroupsRelated($req,$group_id);
		$this->db->query("DELETE FROM product_accessory_group_items WHERE group_id='$group_id'");
		$this->db->query("DELETE FROM product_accessory_group_categories WHERE group_id='$group_id'");
		//insert category id
		if(count($category)>0)
			{
				foreach ($category as $category_id) 
				{
				$newarray = array("group_id"=>$group_id,"category_id"=>$category_id);
				$this->db->insert("product_accessory_group_categories", $newarray);
				//Get All accessory id of the categories;
				$accessory=$this->GetAccessoryIdOfcategory($category_id);
				if(count($accessory)>0)
					{
					foreach ($accessory as $accessory_id) 
						{
						$newarray = array("group_id"=>$group_id,"accessory_id"=>$accessory_id['id']);
						$this->db->insert("product_accessory_group_items", $newarray);
						}
					}
				}
			}
		return true;
		}
	return $message;
	}
function GetAccessoryIdOfcategory($category_id)
	{
	list($qry1,$table_id,$join_qry)=$this->generateQry('product_accessories','d','a');	
	$qry		=	"SELECT a.id,$qry1 from product_accessories a $join_qry where a.active='Y' AND a.category_id='$category_id'";
	$rs		 	= 	$this->db->get_results($qry, ARRAY_A);
	return $rs;
	}
function GetAllAccessoryGroup($id=0)
	{
		
	$qry				=	"select * from product_accessory_group where parameter!='Y'";
	$rs['group_id'] 	= 	$this->db->get_col($qry, 0);
	$rs['group_name'] 	= 	$this->db->get_col($qry, 1);
	return $rs;
	}
function GetAccessoryGroupItems($group_id=0)
	{
	if($group_id>0)
		{
		$rs = $this->db->get_results("SELECT DISTINCT pa.category_id FROM product_accessory_group_items as pgt, product_accessories as pa WHERE pgt.group_id='{$group_id}' and pgt.accessory_id=pa.id", ARRAY_A);
		return $rs;
		}
	
	}
function GetAllAccessoryGroupItems($group_id=0)
	{
	if($group_id>0)
		{
		$rs = $this->db->get_results("SELECT accessory_id FROM product_accessory_group_items as pgt, product_accessories as pa WHERE pgt.group_id='{$group_id}' and pgt.accessory_id=pa.id", ARRAY_A);
		return $rs;
		}
	}
function GetAllAccessoryIds($group_id=0)
	{
	if($group_id>0)
		$qry				=	"select DISTINCT accessory_id from product_accessory_group_items WHERE group_id=$group_id";
	else
		$qry				=	"select DISTINCT accessory_id from product_accessory_group_items ";
	$rs['accessory_id'] 	= 	$this->db->get_col($qry, 0);
	return $rs;
	}
function selectAllGroup()
	{
	$rs = $this->db->get_results("SELECT id as group_id FROM `product_accessory_group` ", ARRAY_A);
		return $rs;
	}
function AddAccessorySettings(&$req)
	{
	extract($req);
	if(count($accessory)>1)
		{
		if($group_id>0)
			$this->db->query("DELETE FROM product_accessory_exclude  WHERE group_id='$group_id'");
		else
			$group_id=$this->GetGroupID();
		if(empty($group_id) || $group_id==0)
			$group_id=1;
		if(empty($product_id) || $product_id==0)
			$product_id=0;
		foreach ($accessory as $accessory_id)
			{
			$array = array("product_id"=>$product_id,"accessory_id"=>$accessory_id,"group_id"=>$group_id);
			$this->db->insert("product_accessory_exclude", $array);
			$status		=	true;
			}
		}
	else
		{
		$status		=	false;
		$message	=	"Please Select more than one items";
		}
	$arr	=	array("status"=>$status,"message"=>$message);
	return $arr;
	}	
function GetAllGroup_id()
	{
	$qry				=	"SELECT group_id FROM `product_accessory_exclude` group by group_id";
	$rs 				= 	$this->db->get_results($qry, ARRAY_A);
	return $rs;
	}
function GetAllAccessorySettingsGroup($group_id)
	{
	$qry				=	"SELECT * FROM `product_accessory_exclude` where group_id=".$group_id;
	$rs 				= 	$this->db->get_results($qry, ARRAY_A);
	return $rs;
	}
function FormatAccessorySettings($group_id)
	{
	$rs	=	$this->GetAllAccessorySettingsGroup($group_id);
	if(count($rs)>0)
		{
		$i=0;
		foreach ($rs as $row)
			{
			$accessory_id	=	$row['accessory_id'];
			$accessory	=	$this->GetAccessory($accessory_id);
			$accessory_name[$i]		=	$accessory['name'];
			$i++;
			}
			$name=implode(",", $accessory_name);
		return $name;
		}
	}
function GetAccessorySettings($group_id=0)
	{
	if($group_id>0)
		{
		$qry		=	"select accessory_id from product_accessory_exclude where group_id=".$group_id;
		$rs['accessory_id'] 	= 	$this->db->get_col($qry, 0);
		return $rs;
		}
	}
function GetAccessorySettingsList($pageNo=0, $limit = 20, $params='', $output=ARRAY_A, $orderBy)
	{
	$qry		=	"select * from product_accessory_exclude group by group_id";		
	$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, ARRAY_A, $orderBy);
	$arr		=	array();
	$arr[0]		=	$rs[0];
	$arr[1]		=	$rs[1];
	$arr[2]		=	$rs[2];
	$arr[3]		=	$rs[3];	
	if(count($rs[0])>0)
		{
		$i		=	0;
		foreach ($rs[0] as $row)
			{
			$arr[0][$i]['id']			=	$row['id'];
			$arr[0][$i]['product_id']	=	$row['product_id'];
			$arr[0][$i]['accessory_id']	=	$row['accessory_id'];
			$arr[0][$i]['group_id']		=	$row['group_id'];
			$arr[0][$i]['name']			=	$this->FormatAccessorySettings($row['group_id']);
			$i++;
			}
		}
	return $arr;
	}
	
function GetGroupID()
	{
	$qry				=	"select MAX(group_id)+1 as grp_id from product_accessory_exclude";
	$rs 				= 	$this->db->get_row($qry, ARRAY_A);
		return $rs['grp_id'];
	}
//////////////////////////////////////////////


///user side functions
//get the price of the accessory
function GetPriceOfAccessory($accessory_id=0,$price=0)
	{
	$add_amount=0;
	list($qry1,$table_id,$join_qry)=$this->generateQry('product_accessories','d','a');
	if($accessory_id>0)
		{
		$qry	=	"SELECT a.*,$qry1 FROM product_accessories a $join_qry WHERE a.id=".$accessory_id;
		$rs 	= 	$this->db->get_row($qry, ARRAY_A);
		if(count($rs>0))
			{
			if($rs['is_price_percentage']=='Y')
				$add_amount=(($price*$rs['adjust_price'])/100);
			else
				$add_amount=$rs['adjust_price'];
			}		
		}//if($accessory_id>0)
	return $add_amount;
	}

function IsAccessoryMonogram($accessory_id=0)
	{
		$qry	=	"	SELECT COUNT(*) AS number 
						FROM 
								product_availabe_accessory AS pa,
								master_category AS mc
						WHERE 	
								pa.accessory_id='$accessory_id' AND
								pa.category_id=mc.category_id AND
								mc.is_monogram='Y'
							";
		if($accessory_id>0)
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
//The Array contains the following 
/*
1. Product id - for calculating the available number of accessory categories

*/	
function ValidateTheSelectionOfAccessory($array='')
	{
	
//print_r($array);
//exit;
extract($array);
	//select all categories available for the product accessories
$qry		=	" SELECT DISTINCT mc.category_id FROM 													";
$qry		.=	"  		master_category AS mc 															";
$qry		.=	"						LEFT JOIN 	(													";
$qry		.=	"										product_availabe_accessory 	AS 	paa 			";
$qry		.=	"									) 													";
$qry		.=	"						ON 			(	 												";
$qry		.=	"										paa.category_id		=	mc.category_id		 	";
$qry		.=	"									) 													";
$qry		.=	" WHERE  																				";
$qry		.=	"   	paa.product_id		=		'".$product_id."' AND								";	
$qry		.=	"   	mc.mandatory		=		'Y'													";		
$status 			=	true;
$arr				=	array();
$arr['message']	=	"";
$objCategory		=	new Category();
$rs_category = $this->db->get_results($qry, ARRAY_A);
for($i=0,$j=0,$k=0;$i<count($rs_category);$i++)
		{
		$category_id=$rs_category[$i]['category_id'];
		$accessory_id=0;
		if(count($array['access'])>0)
			{
			foreach ($array['access'] as $key=>$value)
				{
				if($category_id==$key)
					{
					$accessory_id=$value;
					}
				}
			}
		if($accessory_id>0)
			{
			$arr['success'][$k]	=	$category_id;
			$k++;
			}
		else
			{
			$status	=	false;
			$arr['falied'][$j]	=	$category_id;
			$arr['message']		.= "Please Select the ".$objCategory->GetCategoryGroupName($category_id)." for the product.<BR>";
			$j++;
			}
		}
	$arr['status']	=	$status;
	return $arr;
	}
function getProductAccessoryCategory($product_id)
	{
$qry		 =	" SELECT DISTINCT mc.category_id FROM 													";
$qry		.=	"  		master_category AS mc, 															";
$qry		.=	"		product_availabe_accessory 	AS 	paa 			";
$qry		.=	" WHERE  																				";
$qry		.=	"										paa.category_id		=	mc.category_id	AND 	";
$qry		.=	"   	paa.product_id		=		'".$product_id."' 									";	
$rs_category = $this->db->get_results($qry, ARRAY_A);	
	//echo $qry;
	//print_r($rs_category);
	//exit;
	
	if(count($rs_category)>0)
		{
		return $rs_category;
		}
	}	
function getProductAccessoryIDCategory($product_id)
	{
	$qry		=	" SELECT accessory_id FROM 													";
$qry		.=	"  		product_availabe_accessory 												";
$qry		.=	" WHERE  																		";
$qry		.=	"   	product_id		=		'".$product_id."' 								";	
$accessory = $this->db->get_results($qry, ARRAY_A);	
	//echo $qry;
	//print_r($rs_category);
	//exit;
	
	if(count($accessory)>0)
		{
		return $accessory;
		}
	}	
/****************************************/
/*		validate accessory excluded		*/
/*		$array['access']				*/
/*		$array['product_id']			*/
function Validate_Accessory_Excluded($array='')
	{
	$product_id=$array['product_id'];
	if(empty($product_id))
		$product_id=0;
	$arr=$this->GetAllAccessorySettingsList($product_id);
	//echo "$product_id";
	//print_r($arr);
	//exit;
	for($i=0;$i<count($arr);$i++)
		{
		$status=true;
		for($j=0,$count=0;$j<count($arr[$i]['accessory']['accessory_id']);$j++)
			{
			//print($arr[$i]['accessory']['accessory_id'][$j]);
			//echo "<br>";
			if(count($array['access'])>0)
				{
				foreach ($array['access'] as $key=>$value)
					{
					if($value[0]==$arr[$i]['accessory']['accessory_id'][$j])					#
						{
						$count++;
						}
					}// foreach loop
				}// if loop
			}// j loop
			if($count==count($arr[$i]['accessory']['accessory_id']) || $count>1 )
				{
				$status=false;
				
				#$message="Sorry, It is not available the combinations of ".$arr[$i]['name']." for the selected product right now. Please try different combination for the product.";
				$message="The product is not available with the combinations of ".$arr[$i]['name'];
				
				$err=array("status"=>$status,"message"=>$message);   //$message
				return $err;
				}
		}// i loop
	$err=array("status"=>true,"message"=>"No Error" );  // No Error
	return $err;
	exit;
	}	

function GetAllAccessorySettingsList($product_id=0)
	{
	#$product_id=0;
		$arr		=	array();
	
	    
	    $str		=	"SELECT exclude_group FROM products WHERE id = '$product_id' ";
	    $res  		=   $this->db->get_row($str, ARRAY_A); 
		
	    $excGroup   =   $res['exclude_group'];
	
	
		if ( $product_id==0 )	{
		$qry		=	"select * from product_accessory_exclude where product_id='$product_id' group by group_id";		
		$rs 		= 	$this->db->get_results($qry,ARRAY_A);
		} else	{
			if ( $excGroup  )	{
			$qry		=	"select * from product_accessory_exclude where group_id in ($excGroup) group by group_id";				
			$rs 		= 	$this->db->get_results($qry,ARRAY_A);
			}

		}
		
		//cho $qry;
		//print_r($rs);
		
		if(count($rs)>0)
			{
			$i		=	0;
			foreach ($rs as $row)
				{
				$arr[$i]['id']			=	$row['id'];
				$arr[$i]['product_id']	=	$row['product_id'];
				$arr[$i]['accessory']	=	$this->GetAccessorySettings($row['group_id']);
				$arr[$i]['group_id']		=	$row['group_id'];
				$arr[$i]['name']			=	$this->FormatAccessorySettings($row['group_id']);
				$i++;
				}
			}
	return $arr;
	}

function GetAccessoriesOfTheStoreAndcategory($category_id=0,$store_name='',$pageNo=0, $limit = 9, $params='', $output=OBJECT,$list_all_pages='',$listall="")
	{
		
	list($qry1,$table_id,$join_qry)=$this->generateQry('product_accessories','d','pa');
	 $qry		=	"SELECT distinct(pa.id), pa.*,$qry1	FROM ";
	
	if($store_name)
	$qry		.=	" 		store as st, 
							store_accessory as sa, ";   
	$qry		.=	"  		product_accessories AS pa $join_qry ,
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
							order by pa.display_order,pa.display_name ASC  				  		
					";
	//echo"$qry";
	//
	// echo $qry		=	"SELECT pa.*,mc.parent_id FROM product_accessories pa,master_category  mc where pa.category_id='$category_id' and mc.parent_id='$category_id'  and pa.active='Y'";
	//echo $qry="SELECT * FROM product_accessories where category_id='$category_id'";
	if ($listall == "Y"){
	
	$rs			=	$this->db->get_results($qry,ARRAY_A);
	}
	else{
	$rs			=	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, ARRAY_A,'','1');
	//print_r($rs);
	}
	return $rs;
	
	
	}
	
function GetNextPreviousAccessory($accessory_id,$category_id=0,$store_name='')
	{
	//echo $category_id;
	
	if($category_id==0)
		{
		$category	=	$this->GetAllAccessoryCategoty($accessory_id);
		//print_r($category);
		$category_id=$category['category_id'][0];
		}
	//echo $category_id;
	
	$qry		=	"SELECT pa.*	FROM ";
	if($store_name)
	$qry		.=	" 		store as st, 
							store_category as sc, ";   
	$qry		.=	"  		product_accessories AS pa,
					   		category_accessory AS ca,
							master_category AS mc
					  WHERE  ";
	if($store_name)				
	$qry		.=		" 	mc.category_id=sc.category_id AND
							sc.store_id=st.id AND
							st.name='".$store_name."' AND ";				  
					 
	$qry		.=	"   	mc.category_id=".$category_id." AND
					  		mc.active='y' AND
							mc.category_id=ca.category_id AND
							ca.accessory_id=pa.id	AND
							pa.active='Y'
							order by pa.name 				  		
					";	
	//exit;
	$rs			=	$this->db->get_results($qry, ARRAY_A);
	
	for($i=0;$i<count($rs);$i++)
		{
		if($rs[$i]['id']==$accessory_id)
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
/**************************************************************************************/
/*functions created for store manage in admin side*/
function Store_GetAllAccessoryGroup($store_id)
	{
	$qry				=	"SELECT * FROM product_accessory_group AS PA,store_accessory_group AS SA WHERE SA.group_id=PA.id AND SA.store_id=$store_id AND PA.parameter!='Y'";
	$rs['group_id'] 	= 	$this->db->get_col($qry, 0);
	$rs['group_name'] 	= 	$this->db->get_col($qry, 1);
	return $rs;
	}
/**************************************************************************************/
  
function modifyColor(&$req)
  {
  extract($req);
  //echo $id;
  $array = array("color1"=>$color1,"color2"=>$color2,"color3"=>$color3);
  $r = $this->db->update("product_accessories",$array,"id='$id'");
	  if($r === false)
	  {
	  	return false;
	  }
	  else
	  {
	  	return true;
	  }
  }
  //Last updated on 04-12-2007 by Vipin Vijayan
  function uploadMyArtwork(&$req,$file,$tmpname,$pro_id=''){	
		extract($req);
				
		if ($file){
			$dir			=	SITE_PATH."/modules/product/images/saved_work/";
			$file1			=	$dir."thumb/";
			$resource_file	=	$dir.$file;
			$path_parts 	= 	pathinfo($file);
		}		
		$image_extension	=	$path_parts['extension'];
		if(!trim(name)){		
			$message 			=	"Art Name";
		}else{		
			$array 				= 	array("pro_art_category"=>$pro_art_category,"user_id"=>$user_id,"pro_save_id"=>$pro_id,"parent_id"=>$parent_id,"name"=>$name,"product_type"=>$product_type,"image_extension"=>$image_extension,"description"=>$description,"product_price"=>$price,"sale_price"=>$prices,"price_type"=>$price_type,
									"save_type"=>$save_type,"main_store"=>$main_store,"own_store"=>$own_store,"adjust_weight"=>$adjust_weight,"xml"=>$xml);
			if($id){			
				$array['id'] 	= 	$id;
				$this->db->update("product_saved_work", $array, "id='$id'");
				$product_id		=	$id;
			}
			else{			
				$this->db->insert("product_saved_work", $array);
				$id = $this->db->insert_id;
			}	
			if ($file){			
				$save_filename	=	$id.".".$path_parts['extension'];				
				_upload($dir,$save_filename,$tmpname,1);				
				$path=$dir;
				$thumb=$dir."thumb/";
				thumbnail($path,$thumb,$save_filename,115,99,$mode,$save_filename);
				
				if($this->config['artist_selection']=='Yes')
					{					
									$new_listname_disp	=	$id."_List_.".$path_parts['extension'];
									$new_listname	=	$id.".".$path_parts['extension'];
									$new_descname	=	$id."_des_.".$path_parts['extension'];
									list($acc_descwidth1,$acc_descheight1,)	=	split(',',$this->config['accessory_thumb_image']);
									list($acc_descwidth,$acc_descheight,)	=	split(',',$this->config['accessory_thumb2_image']);
									list($acc_listwidth,$acc_listheight,)	=	split(',',$this->config['accessory_thumb3_image']);
								thumbnail($path,$thumb,$save_filename,$acc_descwidth1,$acc_descheight1,$mode,$new_listname_disp);
									thumbnail($path,$thumb,$save_filename,$acc_listwidth,$acc_listheight,$mode,$new_listname);
									thumbnail($path,$thumb,$save_filename,$acc_descwidth,$acc_descheight,$mode,$new_descname);	
				
				}
				
										
			}		
			return $id;
		}
		return $message;
	}
  
	//Custom Field Functions - Retheesh
	
	function fetchFields($orderBy)
	{	
		$table_id = $this->getCustomId("product_accessories");
		$sql = "select * from custom_fields_main where table_id=$table_id ";
		$rs = $this->db->get_results($sql,ARRAY_A);
		$fields = array_keys($rs[0]);
		$fields=  implode(",",$fields);
		//echo $fields;exit;
		$sql = "SELECT $fields FROM `custom_fields_title` where table_id=$table_id and visible='Y' union 
		SELECT $fields FROM `custom_fields_main` where table_id=$table_id and visible='Y' order by $orderBy";
		$rs = $this->db->get_results($sql);
		//print_r($rs);
		return $rs;
	}
	
	///End Custom
	
	// **************** for accessory groups in taking art *********************************
	function listAllSettings($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy)
	{
		$qry		=	"select * from accessory_settings";
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
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
				$this->db->update("accessory_settings", $array, "id='$id'");
			} else {
				$this->db->insert("accessory_settings", $array);
				$id 			= 	$this->db->insert_id;
			}


			return true;
		}
		return $message;
	}
	function saveArtDetails($art_arr,$product_id,$parent){		
		$artCatArr=explode(',',$art_arr);		
		$count	=	count($artCatArr);
		for($i=0;$i<$count;$i++){		
			$arr[]=explode('|',$artCatArr[$i]);			
			$art_id	= $arr[$i][0];
			$cat_id	= $arr[$i][1];			
			$array 			= 	array("accessory_id"=>$art_id,"product_id"=>$product_id,"category_id"=>$cat_id,"parent"=>$parent);
			$this->db->insert("product_built_in_accessory", $array);
		}		
		return true;
	}
	/*
		for displaying products that used by the given accessory_id
		@param <accessory_id,store_name>
		return array
	*/
	function getProductsByAccessory($accessory_id,$store_name,$pageNo,$limit,$param,$output=OBJECT,$orderBy){
		 $sqlQuery				=	"SELECT DISTINCT p.*,sp.price as pro_price FROM";
		if($store_name){
			$sqlQuery			.=	" 		store AS st, 
									product_store AS ps, ";
		}
			$sqlQuery			.=	"  		products AS p,
									product_built_in_accessory AS pb,
									product_price sp
										WHERE " ;
		if($store_name){	
			$sqlQuery			.=		" 	p.id=ps.product_id AND
									ps.store_id=st.id AND
									st.name='".$store_name."' AND ";
		}						
			$sqlQuery			.=	"	p.active='Y' AND pb.product_id = p.id AND
									pb.accessory_id=$accessory_id AND 
									p.product_id=sp.product_id";														

		if(empty($store_name)){
			$sqlQuery			.=	"  	AND p.hide_in_mainstore!='Y' ";
		}		
		$rs 					 = 	$this->db->get_results_pagewise($sqlQuery, $pageNo, $limit, $params, $output, $orderBy,'1');
		return $rs;			   
			   
	}
	function getAccessDetails($product_id){
		$query	=	"SELECT * FROM product_built_in_accessory WHERE product_id='$product_id'";
		$rs		=	$this->db->get_results($query);
		return $rs;
	}
	//For Listing Random accessory
	function listRandomAccessory($limit,$store_id){
		//list($qry,$table_id,$join_qry)=$this->generateQry('product_accessories','d','a');
		$qry		=	"SELECT pa.* FROM product_accessories pa  WHERE pa.active='Y'";
		if($store_id!=""){
		$qry		=	"SELECT pa.* FROM product_accessories pa,store_accessory  c   
						 WHERE pa.active='Y' AND  pa.id=c.accessory_id AND c.store_id=$store_id";				
		}
		$qry	.=	" order by rand() limit 0,2";
		$rs		=	$this->db->get_results($qry);	
		return $rs;	
	}
	function takeBuildAccessory($product_id){
		$qry	=	"SELECT * FROM product_built_in_accessory
					 WHERE product_id=$product_id"; 
		$rs		=	$this->db->get_results($qry);	
		return $rs;	

	}
	function storeAccDetails($storeId,$accId){
	if($storeId!=""){
	
	            $qry1="select * from store_accessory where store_id=$storeId and  accessory_id=$accId";
						
						$rs1	=	$this->db->get_results($qry1,ARRAY_A);
						
						  if(count($rs1)==0)
						  {
	
							 $qry	=	"INSERT INTO  store_accessory (store_id,accessory_id) VALUES ($storeId,$accId)";
							
							  $rs		=	$this->db->get_results($qry);	
						   }
						 					
						return $rs;
		}	
		}
		
	// *********************** for accessory groups in taking art	**************************
	
	
	function getExcludedAccessoryList	($store_id=0,$product_id=0)	{
		
		 $qry		=	"select * from product_accessory_exclude group by group_id";		
		
		$rs 		= 	$this->db->get_results($qry, ARRAY_A);
		
		if(count($rs[0])>0)	{
			$i		=	0;
			foreach ($rs as $k=>$row)	{
				$rs[$k]['group_name']	=	$this->FormatAccessorySettings($row['group_id']);
			}
			
			$ex_Array['group_id']	=	array();
		    $ex_Array['group_name']	=	array();
			$ex_Array['group_id'][0]	=	'';
			$ex_Array['group_name'][0]	=	'--No Item--';
			$i		=	1;
			foreach ( $rs as $k=>$val)	{
				$ex_Array['group_id'][$i]	=	$val['group_id'];
				$ex_Array['group_name'][$i]	=	$val['group_name'];
				$i++;
			}	
		}
		
			
		return $ex_Array;
	}
	
	
	//=====================
	
	function getExcludedAccessoryList1	($store_id=0,$product_id=0)	{
		
		 $qry		=	"select * from product_accessory_exclude group by group_id";		
		
		$rs 		= 	$this->db->get_results($qry, ARRAY_A);
		
		if(count($rs[0])>0)	{
			$i		=	0;
			foreach ($rs as $k=>$row)	{
				$rs[$k]['group_name']	=	$this->FormatAccessorySettings($row['group_id']);
			}
			}
			/*$ex_Array['group_id']	=	array();
		    $ex_Array['group_name']	=	array();
			$ex_Array['group_id'][0]	=	'';
			$ex_Array['group_name'][0]	=	'--No Item--';
			$i		=	1;
			foreach ( $rs as $k=>$val)	{
				$ex_Array['group_id'][$i]	=	$val['group_id'];
				$ex_Array['group_name'][$i]	=	$val['group_name'];
				$i++;
			}	
		}*/
		
			
		return $rs;
	}
	//=====================
	
		/**
           * This is used for fetching accessories catogarised 
           * Author   : Salim
           * Created  : 04/Dec/2007
           * Modified : 23/Jan/2008
		   * Desc	  :	Added the condition for stores.  
           */

	function listAllAccessoriesOfCatagory($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy,	$category_id='',$store_id='',$string='',$flag='')
	{
	   
	   
	   //print($_REQUEST['aid']);
		$catString="'".$category_id."'";
		//echo $category_id;
		$catString	=	$this->subCategories($category_id,$catString,$store_id);
		
		list($qry1, $table_id, $join_qry)=$this->generateQry('product_accessories','d','pa');

		if($_REQUEST['aid']==203){
			 $qry	=	"select DISTINCT pa.id ,pa.name, pa.display_name, pa.category_id, pa.adjust_weight, pa.type, pa.active, pa.cart_name, $qry1 from product_accessories pa  $join_qry ";
		} elseif($_REQUEST['aid']==174) {
		 $qry	=	"select DISTINCT pa.id, pa.name, pa.display_name, pa.category_id, pa.adjust_price, pa.adjust_weight ,pa.type,pa.display_order,pa.active,$qry1 from product_accessories pa  $join_qry ";
		} else {
		 $qry	=	"select DISTINCT pa.id, pa.name, pa.display_name, pa.category_id, pa.adjust_price, pa.adjust_weight ,pa.type,pa.display_order,pa.active,$qry1 from product_accessories pa  $join_qry ";
		
		}

		 $qry	.=	" INNER JOIN category_accessory ca ON pa.id = ca.accessory_id WHERE ca.category_id  IN (".$string.") AND pa.parent_id =''";
         
		 # $qry	=	"select pa.*,$qry1 from product_accessories pa  $join_qry";

		
		if($store_id>0)
		{ 
			if($_REQUEST['aid']==203)
			{
				 $qry	=	"SELECT DISTINCT pa.id,pa.name,pa.display_name,pa.category_id,pa.adjust_weight ,pa.type,pa.cart_name,b.active,$qry1,b.store_id,b.accessory_id 
		            FROM  product_accessories pa $join_qry
		 			INNER JOIN category_accessory ca ON pa.id = ca.accessory_id AND ca.category_id in ($string)
		            ,store_accessory b  
					WHERE  pa.id = b.accessory_id
					AND   b.store_id = $store_id ";
			}
			elseif($_REQUEST['aid']==174)
			{
				 $qry	=	"SELECT DISTINCT pa.id,pa.name,pa.display_name,pa.category_id,pa.adjust_price,pa.adjust_weight ,pa.type,pa.display_order,b.active,$qry1,b.store_id,b.accessory_id 
		            FROM  product_accessories pa $join_qry
		 			INNER JOIN category_accessory ca ON pa.id = ca.accessory_id AND ca.category_id in ($string)
		            ,store_accessory b  
					WHERE  pa.id = b.accessory_id
					AND   b.store_id = $store_id ";
			} else {
				  
				 $qry	=	"SELECT DISTINCT pa.id,pa.name,pa.display_name,pa.category_id,pa.adjust_price,pa.adjust_weight ,pa.type,pa.display_order,b.active,$qry1,b.store_id,b.accessory_id 
		            FROM  product_accessories pa $join_qry
		 			INNER JOIN category_accessory ca ON pa.id = ca.accessory_id AND ca.category_id in ($string)
		            ,store_accessory b  
					WHERE  pa.id = b.accessory_id
					AND   b.store_id = $store_id ";
			}
					
		}
		
		if($category_id>0)
		{ 
	
		 $qry	=	"select DISTINCT(pa.name),pa.id,pa.name,pa.display_name,pa.category_id,pa.adjust_price,pa.adjust_weight ,pa.type,pa.active,$qry1
		             from product_accessories pa $join_qry ,master_category as mc,category_accessory as ca 
					 where mc.category_id=ca.category_id
					 and ca.accessory_id=pa.id 
					 AND ca.category_id IN ($catString) "; 
				 
					
		if($store_id>0)
		{ 
		
		 $qry	=	"select pa.id,pa.name,pa.display_name,pa.category_id,pa.adjust_price,pa.adjust_weight		            ,pa.type,sa.active, $qry1,sa.store_id,sa.accessory_id
		             from product_accessories pa $join_qry,master_category as mc,category_accessory as ca ,store_accessory as sa
					 where mc.category_id=ca.category_id
					 and ca.accessory_id=pa.id 
					 and sa.accessory_id = pa.id
					 and sa.store_id = $store_id
					 AND ca.category_id IN ($catString) "; 
					
		
		}			 
		}
//	echo $store_id;	
		if($store_id>0)
		{ 
			$qry .= " and pa.active ='Y'";
		}
	//	echo $qry;

	// echo '<pre> -----------query start--------------- ';
	// print_r($qry);
	// echo ' ---------------query end----------- </pre>';
		
		if($flag==1){
			$rs = $this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		} else {
			$rs = $this->db->get_results_pagewise_ajax($qry, $pageNo, $limit, $params, $output, $orderBy,1);
		}


		return $rs;
	}
	
	/**
           * This is used for searching accessories catogarised 
           * Author   : Salim
           * Created  : 04/Dec/2007
           * Modified : 23/Jan/2008
		   * Desc	  :	Added the condition for searching in stores.  
           */
	
	function listAllAccessoriesOfCatagorybySearch($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy,	$category_id='',$accessory_search,$string='',$store_id='')
		{
			list($qry1,$table_id,$join_qry)=$this->generateQry('product_accessories','d','pa'); // DO NOT CHANGE THIS PART -  Retheesh
			
			if ($store_id !='')
			{
				$qry	=	"SELECT DISTINCT pa.*,$qry1,b.store_id,b.accessory_id 
		            FROM  product_accessories pa $join_qry
		 			INNER JOIN category_accessory ca ON pa.id = ca.accessory_id AND ca.category_id in ($string)
		            ,store_accessory b  
					WHERE  pa.id = b.accessory_id
					AND   b.store_id = $store_id AND pa.name LIKE '%".$accessory_search."%'";
			}
			else
			{
				
				$qry	=	"select DISTINCT pa.*,$qry1 from product_accessories pa $join_qry ";
				$qry	.=	"INNER JOIN category_accessory ca ON pa.id = ca.accessory_id WHERE ca.category_id  IN (".$string.")";
				$qry	.=	" and pa.name LIKE '%".$accessory_search."%'";
				
						
			}
			
			if($category_id>0)
								 
							 $qry	=	"select pa.*,$qry1  from product_accessories pa $join_qry,master_category as mc,category_accessory as ca  where mc.category_id=ca.category_id
							 and ca.accessory_id=pa.id AND pa.name LIKE '%".$accessory_search."%' AND ca.category_id=".$category_id;
							 
			 	
			
			 $rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
			return $rs;
		}
		
		/**
           * This is used for searching accessories catogarised 
           * Author   : Salim
           * Created  : 04/Dec/2007
           * Modified : 23/Jan/2008
		   * Desc	  :	Added the condition for searching in stores.  
           */
	
	function listAllAccessoriesOfCatagorybySearch2($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy,	$category_id='',$accessory_search,$string='',$store_id='')
		{
			list($qry1,$table_id,$join_qry)=$this->generateQry('product_accessories','d','pa'); // DO NOT CHANGE THIS PART -  Retheesh
			
			if ($store_id !='')
			{
				$qry	=	"SELECT DISTINCT pa.id,pa.name,pa.display_name,pa.category_id,pa.adjust_weight ,pa.type,pa.cart_name,b.active,$qry1,b.store_id,b.accessory_id 
		            FROM  product_accessories pa $join_qry
		 			INNER JOIN category_accessory ca ON pa.id = ca.accessory_id AND ca.category_id in ($string)
		            ,store_accessory b  
					WHERE  pa.id = b.accessory_id
					AND   b.store_id = $store_id AND pa.name LIKE '%".$accessory_search."%'";
			}
			else
			{
				
				$qry	=	"select DISTINCT pa.*,$qry1 from product_accessories pa $join_qry ";
				$qry	.=	"INNER JOIN category_accessory ca ON pa.id = ca.accessory_id WHERE ca.category_id  IN (".$string.")";
				$qry	.=	" and pa.parent_id=0 and pa.name LIKE '%".$accessory_search."%'";
				
						
			}
			
			
			
			if($store_id>0)
			{ 
				//$qry .= " and pa.visibility_store =1";
			}				 
			 
			
			//echo $qry;
			
			 $rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
			return $rs;
		}
		
	/**
           * This is used for fetching category id of the sub categories of a particular accessory by passing the accessory id
           * Author   : Salim
           * Created  : 20/Dec/2007
           * Modified : 20/Dec/2007
		   * Desc	  :	  
           */
	function GetSubCatOfAcc($cat)
		{
			$sql	=	"SELECT category_id from master_category where parent_id = $cat";
			$rs		=	$this->db->get_results($sql,ARRAY_A);
			return $rs;
		}
	/**
           * This is used for fetching name of the accessory by providing its accessory id.
           * Author   : Salim
           * Created  : 03/Jan/2008
           * Modified : 03/Jan/2008
		   * Desc	  :	 
           */	
	function GetAccName($accid)
	{
		$sql	=	"SELECT name from product_accessories where id = $accid";
		$rs		=	$this->db->get_results($sql,ARRAY_A);
		return $rs;	
	}
	function Getstore_accessoryId($accessory_id,$store_id){
		$sql	=	"SELECT id FROM store_accessory WHERE accessory_id = $accessory_id AND store_id= $store_id";
		$rs		=	$this->db->get_results($sql,ARRAY_A);
		return $rs;	
		
	}
	function GetDetailsOfAccessory($accessory_id)
	{
	
	return $rs;
	}
	
	function getAccessoryDetailsForEditFromStore($accessory_id){
		
		$sql	=	"SELECT * FROM product_accessories WHERE id = $accessory_id";
		$rs		=	$this->db->get_results($sql,ARRAY_A);
		$ans	=	$rs[0]['parent_id'];
		return $ans;
	}
	/**
    * Getting whether the Fields are viewable at store manage 
  	* Author   : Salim
  	* Created  : 10-Apr-2008
  	* Modified : 
  	*/
	function getStoreEditable($table_id,$did)
	{
		if($table_id)
		{
			$qry		=	"SELECT id,display_name AS name,".$did.",'custom_fields_main' AS tablename FROM custom_fields_main cfm WHERE cfm.table_id=".$table_id.
							" UNION ALL SELECT id, display_name AS name, ".$did.",'custom_fields_title' AS tablename FROM custom_fields_title cft WHERE cft.table_id = ".$table_id;
			$rs		 	= 	$this->db->get_results($qry,ARRAY_A);
			//print_r($rs);exit;
			return $rs;
		}
		return false;
	}
	/**
    * Updating the manage_store field in module_fields table
  	* Author   : Salim
  	* Created  : 10-Apr-2008
  	* Modified : 
  	*/
	function updateStoreEditable($field_arr,$table_id,$tablename)
	{
		$id	=	$this->db->update($tablename, $field_arr, "id='$table_id'");
		return true;
	}
	/**
    * Getting the name of the accessory if the POST array has value NULL
  	* Author   : Salim
  	* Created  : 21-Apr-2008
  	* Modified : 
  	*/
	function getnameifpostnull($accessory_id)
	{
		$sql	=	"SELECT name FROM product_accessories WHERE id = $accessory_id";
		$rs		=	$this->db->get_col($sql,0);
		$retval	=	$rs[0];
		return $retval;
	}

	function changeActive($current_act,$id)
	{
	
		if ($current_act=='Y')
		{
			$this->db->query("update product_accessories set `active`='N' WHERE id=$id");
			$this->db->query("update product_accessories set `active`='N' WHERE parent_id=$id");
			$this->db->query("update product_accessories set `visibility_store`='0' WHERE id=$id");
			$this->db->query("update product_accessories set `visibility_store`='0' WHERE parent_id=$id");
			return true;
		}
		else
		{
			$this->db->query("update product_accessories set `active`='Y' WHERE id=$id");
			$this->db->query("update product_accessories set `active`='Y' WHERE parent_id=$id");
			$this->db->query("update product_accessories set `visibility_store`='1' WHERE id=$id");
			$this->db->query("update product_accessories set `visibility_store`='1' WHERE parent_id=$id");
			return true;
		}
		
		
	}

	function changeStoreActive($current_act,$accessory_id,$store_id)
	{
		if ($current_act=='Y')
		{
			$this->db->query("update store_accessory  set `active`='N' WHERE accessory_id='$accessory_id' and store_id='$store_id'");
			return true;
		}
		else
		{
			$this->db->query("update store_accessory  set `active`='Y' WHERE accessory_id='$accessory_id' and store_id='$store_id'");
			return true;
		}
		
		
	}
	
	function updateStoreAccessory($arr,$id)
	{
		$table_id = $this->getCustomId("product_accessories");
		$sql = "select * from custom_fields_main where table_id=$table_id ";
		$rs = $this->db->get_results($sql,ARRAY_A);
		foreach($rs as $key=>$val){
			$field_name=$val['field_name'];
			if($val['mat_store']=='N' && $val['showupdate'] =='Y'){
				$array[$field_name]=$arr[$field_name];
			}
		}
		$this->db->update("product_accessories", $array, "parent_id='$id'");
		return true;
	}
	
	function getStoreList()
	{
		$sql = "select id from store ";
		$rs = $this->db->get_results($sql,ARRAY_A);
		return $rs;
	}
	
	function insertAccessoryRelation($accessoryid)
	{
		$store_det	   = $this->getStoreList();
		foreach($store_det as $key=>$storedetails)
		{
			$store_acc_array =	array("accessory_id"=>$accessoryid,"store_id"=>$storedetails['id'],'active'=>'Y');
			$this->db->insert("store_accessory", $store_acc_array);
		}
	}
	
	function accessoryExistsinStore($accessoryid)
	{
		$sql = "select count(*) as acc_count from store_accessory where accessory_id='$accessoryid'";
		$rs = $this->db->get_row($sql,ARRAY_A);
		return $rs['acc_count'];
		
	}
	
	
	
	function listAllAccessoriesOfCatagoryAll($category_id='',$store_id='',$string='',$aid)
	{
	   
	   
	   //print($_REQUEST['aid']);
		$catString="'".$category_id."'";
		//echo $category_id;
		$catString	=	$this->subCategories($category_id,$catString,$store_id);
		
		list($qry1,$table_id,$join_qry)=$this->generateQry('product_accessories','d','pa');
		if($aid==203)
		{
			 $qry	=	"select DISTINCT pa.id,pa.name,pa.display_name,pa.category_id,pa.adjust_weight ,pa.type,pa.active,pa.cart_name,$qry1 from product_accessories pa  $join_qry ";
		}
		elseif($aid==174)
		 {
		 $qry	=	"select DISTINCT pa.id,pa.name,pa.display_name,pa.category_id,pa.adjust_price,pa.adjust_weight ,pa.type,pa.display_order,pa.active,$qry1 from product_accessories pa  $join_qry ";
		}
		else
		{
		 $qry	=	"select DISTINCT pa.id,pa.name,pa.display_name,pa.category_id,pa.adjust_price,pa.adjust_weight ,pa.type,pa.display_order,pa.active,$qry1 from product_accessories pa  $join_qry ";
		
		}
		 $qry	.=	" INNER JOIN category_accessory ca ON pa.id = ca.accessory_id WHERE ca.category_id  IN (".$string.") AND pa.parent_id =''";
         
		 # $qry	=	"select pa.*,$qry1 from product_accessories pa  $join_qry";

		
		if($store_id>0)
		{ 
			if($aid==203)
			{
				 $qry	=	"SELECT DISTINCT pa.id,pa.name,pa.display_name,pa.category_id,pa.adjust_weight ,pa.type,pa.cart_name,b.active,$qry1,b.store_id,b.accessory_id 
		            FROM  product_accessories pa $join_qry
		 			INNER JOIN category_accessory ca ON pa.id = ca.accessory_id AND ca.category_id in ($string)
		            ,store_accessory b  
					WHERE  pa.id = b.accessory_id
					AND   b.store_id = $store_id ";
			}
			elseif($aid==174)
			{
				 $qry	=	"SELECT DISTINCT pa.id,pa.name,pa.display_name,pa.category_id,pa.adjust_price,pa.adjust_weight ,pa.type,pa.display_order,b.active,$qry1,b.store_id,b.accessory_id 
		            FROM  product_accessories pa $join_qry
		 			INNER JOIN category_accessory ca ON pa.id = ca.accessory_id AND ca.category_id in ($string)
		            ,store_accessory b  
					WHERE  pa.id = b.accessory_id
					AND   b.store_id = $store_id ";
			}
			else
			{
				  
				 $qry	=	"SELECT DISTINCT pa.id,pa.name,pa.display_name,pa.category_id,pa.adjust_price,pa.adjust_weight ,pa.type,pa.display_order,b.active,$qry1,b.store_id,b.accessory_id 
		            FROM  product_accessories pa $join_qry
		 			INNER JOIN category_accessory ca ON pa.id = ca.accessory_id AND ca.category_id in ($string)
		            ,store_accessory b  
					WHERE  pa.id = b.accessory_id
					AND   b.store_id = $store_id ";
			}
					
		}
		
		if($category_id>0)
		{ 
	
		 $qry	=	"select DISTINCT(pa.name),pa.id,pa.name,pa.display_name,pa.category_id,pa.adjust_price,pa.adjust_weight ,pa.type,pa.active,$qry1
		             from product_accessories pa $join_qry ,master_category as mc,category_accessory as ca 
					 where mc.category_id=ca.category_id
					 and ca.accessory_id=pa.id 
					 AND ca.category_id IN ($catString) "; 
				 
					
		if($store_id>0)
		{ 
		
		 $qry	=	"select pa.id,pa.name,pa.display_name,pa.category_id,pa.adjust_price,pa.adjust_weight		            ,pa.type,sa.active, $qry1,sa.store_id,sa.accessory_id
		             from product_accessories pa $join_qry,master_category as mc,category_accessory as ca ,store_accessory as sa
					 where mc.category_id=ca.category_id
					 and ca.accessory_id=pa.id 
					 and sa.accessory_id = pa.id
					 and sa.store_id = $store_id
					 AND ca.category_id IN ($catString) "; 
					
		
		}			 
		}
		
		if($store_id>0)
		{ 
			$qry .= " and pa.active ='Y'";
		}
		//echo $qry;
		
		
		
		$rs 		= 	$this->db->get_results($qry);
		
		//echo sizeof($rs[0]);
		return $rs;
	}
	
	
	
}//End Class

?>
