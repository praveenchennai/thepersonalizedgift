<?php
class Store extends FrameWork {
	var $errorMessage;
	function Store() {
		$this->FrameWork();
	}
	/**
	 * Listing stores with pagination
	 * @param <Page Number> $pageNo
	 * return array
	 */
	function storeList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
		$sql		= "SELECT s.*,m.first_name,m.last_name,m.active as mem_active FROM store s INNER JOIN  member_master m ON s.user_id=m.id left join member_subscription ms ON ms.user_id=m.id WHERE 1 and deleted='N'";
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		
		$stop_subs=$this->config['stop_subs'];
		
		foreach($rs[0] as $k=>$val)
		{
			$member_id=$val->user_id;
			// retriving subscription details
			
			$sql2  = "SELECT * FROM `member_subscription` where user_id='$member_id' ORDER BY `enddate` DESC LIMIT 0 , 1";
			$rs2   = $this->db->get_row($sql2, ARRAY_A);
			$cur_date			=	date("Y-m-d");
			
			$cur_enddate	=	$rs2['enddate'];
			list($y,$m,$df)	=	split("-",$cur_enddate);
			$d	=	substr($df, 0, 2);
			$endDate	=	$y."-".$m."-".$d;
			
			if($endDate > $cur_date && $stop_subs =='Y')
			  $subs_status='To be canceled on '.$endDate;
			else if ($endDate > $cur_date && $stop_subs!='Y')
		  	  $subs_status='Active';
			else if ($endDate < $cur_date )
			  $subs_status='Canceled';
			  
			$rs[0][$k]->subs_status=$subs_status;
			
		}
		
		
		return $rs;
	}
	
	
	function storeList_User ($pageNo, $limit = 10, $params='', $user, $output=OBJECT, $orderBy) {
		$sql		= "SELECT * FROM store WHERE name='{$user}'";
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	/**
	 * Getting store based on given id
	 * @param <id> $id
	 * return array
	 */
	function storeGet ($id) {
		$rs = $this->db->get_row("SELECT * FROM store WHERE id='{$id}'", ARRAY_A);
		return $rs;
	}
	/**
	 * Getting store based on given store name
	 * @param <name> $storename
	 * return array
	 */
	function storeGetByName($storename) {
		 $rs = $this->db->get_row("SELECT * FROM store WHERE name='{$storename}'", ARRAY_A);
		
		return $rs;
	}
	function storeGetByName1($storename) {
	
	 $sql = "SELECT * FROM store WHERE name ='$storename'";
	
	    $rs = $this->db->get_row($sql, ARRAY_A);
		 return $rs;
	}
	function getAdminEmail() {
		
		return $this->config['admin_email'];
	}
	
	/**
	 * Add Edit Stores
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message	 
	 */
	function storeAddEdit (&$req, $sId = 'Store') {
//print_r($req);exit;
		extract($req);
		$qry	=	"select image_extension from store where id='$id'"; 
				$rs     = $this->db->get_row($qry, ARRAY_A);
		          $imgext=$rs['image_extension'];
					if($_FILES['image']==""){
					$image_extension = $imgext;
		            }
		if (!$_FILES['image']['error'] ) {
			$image_extension = substr($_FILES['image']['name'], strrpos($_FILES['image']['name'], ".")+1);
			
		}
		$active = $active ? 'Y' : 'N';
		$root_store = $root_store ? 'Y' : 'N';
		if(!trim($name)) {
			$message = "$sId Name is required";
		} elseif(!preg_match("/^([a-z0-9])*$/", $name)) {
			$message = "$sId Name Accepts a-z and 0-9 only";
		} elseif($this->db->get_row("SELECT * FROM store WHERE name = '$name' AND id != '$id'")) {
			$message = "$sId Name already taken, Please choose another name.";
		} elseif(($image_extension != "" && $image_extension != "jpg" && $image_extension != "gif" && $image_extension != "png")) {
			$message = "Image type not supported. Please upload only gif, jpg or png format.";
		} elseif(!trim($reg_pack) &&trim(!$id)){
			$message='Registration package is required';
		}elseif(!trim($sub_pack)&&trim(!$id)){
			$message='Subscription Package package is required';
		} else {
			$array = array("payment_receiver"=>$payment_receiver,"name"=>$name, "heading"=>$heading, "heading1"=>$heading1, "heading2"=>$heading2,"redirect_url"=>$redirect_url,"user_id"=>$member_id,"description"=>$description, "image_extension"=>$image_extension, "root_store"=>$root_store, "title"=>$title,"content"=>$content,"active"=>$active);
		if($google_analytics){
			$array['google_analytics'] = $google_analytics;
		}
		else{$array['google_analytics'] = '';
		}
		//added by salim
		if($page_title){
			$array['page_title'] = $page_title;
		}
		else{
			$array['page_title'] = '';
		}
		if($meta_description){
			$array['meta_description'] = $meta_description;
		}
		else{
			$array['meta_description'] = '';
		}
		if($meta_keywords){
			$array['meta_keywords'] = $meta_keywords;
		}
		else{
			$array['meta_keywords'] = '';
		}
		//end by salim
		
		if($id) {
		        
							$array['id'] = $id;
							$data['active']='N';
							$this->db->update("store", $array, "id=$id");
							
							
						
			} else {

				
				
				
				$data['active']='N';
				$this->db->insert("store", $array);
				$id = $this->db->insert_id;
				
				$updateArr=array("reg_pack"=>$reg_pack,"sub_apck"=>$reg_pack);

				$this->db->update("member_master", $updateArr, "id='$member_id'");
				
				
				/* $rslt = $this->getStorePermission_1(0);
				foreach ($rslt as $rs_key){
					unset($rs_key['id']);
					$rs_key['store_id']=$id;
				}
				$this->db->insert("store_permission", $rslt);
	print_r($rslt);exit; */
				
				if ($this->config["all_products_to_store"]=="Y")
				{
					$sql_pr = "select * from products where hide_in_mainstore='N'";
					$prd_store = $this->db->get_results($sql_pr);
					for ($i=0;$i<sizeof($prd_store);$i++)
					{
						$arr_pr = array();
						$arr_pr["product_id"] = $prd_store[$i]->id;
						$arr_pr["store_id"]   = $id;
						$this->db->insert("product_store",$arr_pr);
					}
				}
				
				if ($this->config["all_accessories_to_store"]=="Y")
				{
					$sql_ac = "select * from product_accessories where parent_id=0";
					$ac_store = $this->db->get_results($sql_ac);
					for ($i=0;$i<sizeof($ac_store);$i++)
					{
						$arr_pr = array();
						$arr_pr["accessory_id"] = $ac_store[$i]->id;
						$arr_pr["store_id"]   = $id;
						$this->db->insert("store_accessory",$arr_pr);
					}
				}
				
			
				// mapping the store and cms page 
				$qry	=	"select * from cms_link where area LIKE '%store_%' "; 
				$res	=	mysql_query($qry); 
				while($row=mysql_fetch_array($res))
				{
					$title="";$url="";$position="";$cms_are="";$area="";$active="Y";
					$title		=	$row["title"];
					$url		=	$row["url"];
					$position	=	$row["position"];
					$cms_area	=	$row["area"];
					$area		=	str_replace("store_", "", $cms_area);
					//echo " C ".$area;
					$active		=	$row["active"];
					
					$cmsarray 	= 	array("title"=>$title,"url"=>$url,"position"=>$position,"area"=>$area,"active"=>$active);
					$this->db->insert("cms_link", $cmsarray);
					$cms_link_id = $this->db->insert_id;
					$cms_type	=	"L";
					//$store_cms_array	=	array("store_id"=>$id,"cms_id "=>$cms_link_id,"cms_type"=>$cms_type);
					$this->db->insert("store_cms", array("store_id"=>$id, "cms_id"=>$cms_link_id, "cms_type"=>$cms_type));
					
				}//print_r($array);exit;
				// mapping the store and cms page 
				
				//mapping cms menus to store
				$qry	=	"select * from cms_menu where active='Y'"; 
				$res	=	mysql_query($qry); 
				
					/*while($row=mysql_fetch_array($res))
					{
						$menuArray=array("section_id"=>$row['section_id'],"name"=>$row['name'],"seo_url"=>$row['seo_url'],"position"=>$row['position'],"type_link"=>$row['type_link'],"active"=>'Y',"type_tip"=>$row['type_tip']);
						$this->db->insert("cms_menu", $menuArray);
						$cms_menu_id = $this->db->insert_id;
						$this->db->insert("store_cms", array("store_id"=>$id, "cms_id"=>$cms_menu_id, "cms_type"=>'M'));
					}*/
					while($row=mysql_fetch_array($res))
					{
						
						$this->db->insert("store_cms", array("store_id"=>$id, "cms_id"=>$row['id'], "cms_type"=>'M'));
						
						
						//insert default pages to that store
						
						$this->insertDefaltPages($row['id'],$id);
					}
			
			}
			$this->db->query("DELETE FROM category_store WHERE store_id='$id'");
			if($category){
				foreach ($category as $category_id)
				{
					$ins			=	array("category_id"=>$category_id,"store_id"=>$id);
					$this->db->insert("category_store", $ins);
					//echo "Inserted Product category <br>";
				}
			}
			$this->db->query("DELETE FROM store_category WHERE store_id = '$id'");
			if($req['store_categories']) {
				foreach ($req['store_categories'] as $category_id) {
					$this->db->insert("store_category", array("store_id"=>$id, "category_id"=>$category_id));
				}
			}
			
				
				if ($this->config["all_categories_to_store"]=="Y")
				{
			
					$sql_acstoreQry= "select * from master_category";
					$sql_acstore = $this->db->get_results($sql_acstoreQry);
					for ($i=0;$i<sizeof($sql_acstore);$i++)
					{
						$arr_pr = array();
						$arr_pr["category_id"] = $sql_acstore[$i]->category_id;
						$arr_pr["store_id"]   = $id;
						$this->db->insert("store_category",$arr_pr);
					}
				}
				
			if($_FILES['image']){//Checking whether option for LOGO upload is present
			if(!$_FILES['image']['error']) {				
				_upload(SITE_PATH."/modules/store/images/", $id.".".$image_extension, $_FILES['image']['tmp_name'], 1);
			}
			}
			return $id;
		}
	
		return $message;
	}
	
	/**
	 * Add Edit Stores
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message	 
	 */
	function storeAddEdit2 (&$req, $sId = 'Store') {
//print_r($req);exit;
		extract($req);
		$qry	=	"select image_extension from store where id='$id'"; 
				$rs     = $this->db->get_row($qry, ARRAY_A);
		          $imgext=$rs['image_extension'];
					if($_FILES['image']==""){
					$image_extension = $imgext;
		            }
		if (!$_FILES['image']['error'] ) {
			$image_extension = substr($_FILES['image']['name'], strrpos($_FILES['image']['name'], ".")+1);
			
		}
		$active = $active ? 'Y' : 'N';
		$root_store = $root_store ? 'Y' : 'N';
		if(!trim($name)) {
			$message = "$sId Name is required";
		} elseif(!preg_match("/^([a-z0-9])*$/", $name)) {
			$message = "$sId Name Accepts a-z and 0-9 only";
		} elseif($this->db->get_row("SELECT * FROM store WHERE name = '$name' AND id != '$id'")) {
			$message = "$sId Name already taken, Please choose another name.";
		} elseif(($image_extension != "" && $image_extension != "jpg" && $image_extension != "gif" && $image_extension != "png")) {
			$message = "Image type not supported. Please upload only gif, jpg or png format.";
		} elseif(!trim($reg_pack) &&trim(!$id)){
			$message='Registration package is required';
		}elseif(!trim($sub_pack)&&trim(!$id)){
			$message='Subscription Package package is required';
		} else {
		
			if($id){
				$array = array("name"=>$name, "heading"=>$heading, "heading1"=>$heading1, "heading2"=>$heading2,"description"=>$description, "active"=>$active);
			}
			else{
			
			
			$array = array("payment_receiver"=>$payment_receiver,"name"=>$name, "heading"=>$heading, "heading1"=>$heading1, "heading2"=>$heading2,"redirect_url"=>$redirect_url,"user_id"=>$member_id,"description"=>$description, "image_extension"=>$image_extension, "root_store"=>$root_store, "title"=>$title,"content"=>$content,"active"=>$active);
			
			}
	
		
		
					
		if($google_analytics){
			$array['google_analytics'] = $google_analytics;
		}
		else{$array['google_analytics'] = '';
		}
		//added by salim
		if($page_title){
			$array['page_title'] = $page_title;
		}
		else{
			$array['page_title'] = '';
		}
		if($meta_description){
			$array['meta_description'] = $meta_description;
		}
		else{
			$array['meta_description'] = '';
		}
		if($meta_keywords){
			$array['meta_keywords'] = $meta_keywords;
		}
		else{
			$array['meta_keywords'] = '';
		}
		//end by salim
		
		
		
		
		
		if($id) {
		        
							$array['id'] = $id;
							$data['active']='N';
							$this->db->update("store", $array, "id=$id");
							
							
						
			} else {

				
				
				
				$data['active']='N';
				$this->db->insert("store", $array);
				$id = $this->db->insert_id;
				
				$updateArr=array("reg_pack"=>$reg_pack,"sub_apck"=>$reg_pack);
				$this->db->update("member_master", $updateArr, "id='$member_id'");
				
				
				/* $rslt = $this->getStorePermission_1(0);
				foreach ($rslt as $rs_key){
					unset($rs_key['id']);
					$rs_key['store_id']=$id;
				}
				$this->db->insert("store_permission", $rslt);
	print_r($rslt);exit; */
				
				if ($this->config["all_products_to_store"]=="Y")
				{
					$sql_pr = "select * from products where hide_in_mainstore='N'";
					$prd_store = $this->db->get_results($sql_pr);
					for ($i=0;$i<sizeof($prd_store);$i++)
					{
						$arr_pr = array();
						$arr_pr["product_id"] = $prd_store[$i]->id;
						$arr_pr["store_id"]   = $id;
						$this->db->insert("product_store",$arr_pr);
					}
				}
				
				if ($this->config["all_accessories_to_store"]=="Y")
				{
					$sql_ac = "select * from product_accessories where parent_id=0";

					$ac_store = $this->db->get_results($sql_ac);
					for ($i=0;$i<sizeof($ac_store);$i++)
					{
						$arr_pr = array();
						$arr_pr["accessory_id"] = $ac_store[$i]->id;
						$arr_pr["store_id"]   = $id;
						$this->db->insert("store_accessory",$arr_pr);
					}
				}
				
			
				// mapping the store and cms page 
				$qry	=	"select * from cms_link where area LIKE '%store_%' "; 
				$res	=	mysql_query($qry); 
				while($row=mysql_fetch_array($res))
				{
					$title="";$url="";$position="";$cms_are="";$area="";$active="Y";

					$title		=	$row["title"];
					$url		=	$row["url"];
					$position	=	$row["position"];
					$cms_area	=	$row["area"];
					$area		=	str_replace("store_", "", $cms_area);
					//echo " C ".$area;
					$active		=	$row["active"];
					
					$cmsarray 	= 	array("title"=>$title,"url"=>$url,"position"=>$position,"area"=>$area,"active"=>$active);
					$this->db->insert("cms_link", $cmsarray);
					$cms_link_id = $this->db->insert_id;
					$cms_type	=	"L";
					//$store_cms_array	=	array("store_id"=>$id,"cms_id "=>$cms_link_id,"cms_type"=>$cms_type);
					$this->db->insert("store_cms", array("store_id"=>$id, "cms_id"=>$cms_link_id, "cms_type"=>$cms_type));
					
				}//print_r($array);exit;
				// mapping the store and cms page 
				
				//mapping cms menus to store
				$qry	=	"select * from cms_menu where active='Y'"; 
				$res	=	mysql_query($qry); 
				
					/*while($row=mysql_fetch_array($res))
					{
						$menuArray=array("section_id"=>$row['section_id'],"name"=>$row['name'],"seo_url"=>$row['seo_url'],"position"=>$row['position'],"type_link"=>$row['type_link'],"active"=>'Y',"type_tip"=>$row['type_tip']);
						$this->db->insert("cms_menu", $menuArray);
						$cms_menu_id = $this->db->insert_id;
						$this->db->insert("store_cms", array("store_id"=>$id, "cms_id"=>$cms_menu_id, "cms_type"=>'M'));
					}*/
					while($row=mysql_fetch_array($res))
					{
						
						$this->db->insert("store_cms", array("store_id"=>$id, "cms_id"=>$row['id'], "cms_type"=>'M'));
						
						
						//insert default pages to that store
						
						$this->insertDefaltPages($row['id'],$id);
					}
			
			}
			$this->db->query("DELETE FROM category_store WHERE store_id='$id'");
			if($category){
				foreach ($category as $category_id)
				{
					$ins			=	array("category_id"=>$category_id,"store_id"=>$id);
					$this->db->insert("category_store", $ins);
					//echo "Inserted Product category <br>";
				}
			}
			$this->db->query("DELETE FROM store_category WHERE store_id = '$id'");
			if($req['store_categories']) {
				foreach ($req['store_categories'] as $category_id) {
					$this->db->insert("store_category", array("store_id"=>$id, "category_id"=>$category_id));
				}
			}
			
				
				if ($this->config["all_categories_to_store"]=="Y")
				{
			
					$sql_acstoreQry= "select * from master_category";
					$sql_acstore = $this->db->get_results($sql_acstoreQry);
					for ($i=0;$i<sizeof($sql_acstore);$i++)
					{
						$arr_pr = array();
						$arr_pr["category_id"] = $sql_acstore[$i]->category_id;
						$arr_pr["store_id"]   = $id;
						$this->db->insert("store_category",$arr_pr);
					}
				}
				
			if($_FILES['image']){//Checking whether option for LOGO upload is present
			if(!$_FILES['image']['error']) {				
				_upload(SITE_PATH."/modules/store/images/", $id.".".$image_extension, $_FILES['image']['tmp_name'], 1);
			}
			}
			return $id;
		}
	
		return $message;
	}
	
	
	
	
	/**
	 * This function used to insert default pages to store cms
	
	* created by : Robin James
	* date of creation :27-05-2008
	
	 */
	function insertDefaltPages($menuid, $store_id)
	{
				$sql_page= "select * from cms_page where active='Y' and post_admin='admin' and menu_id=$menuid";
				$rs = $this->db->get_results($sql_page);
				if($rs)
				{
					foreach ($rs as $results)
					{
					
					$insert_array=array("menu_id"=>$results->menu_id,"title"=>$results->title,"short_content"=>$results->short_content,"content"=>mysql_real_escape_string($results->content),"post_admin"=>'',"post_date"=>$results->post_date,"position"=>$results->position,"active"=>$results->active,"meta_description"=>$results->meta_description,"meta_keywords"=>$results->meta_keywords,"page_name"=>$results->page_name);
					
					$this->db->insert("cms_page",$insert_array);
					$cms_page_id = $this->db->insert_id;
					$cms_type	=	"P";
					
					$this->db->insert("store_cms", array("store_id"=>$store_id, "cms_id"=>$cms_page_id, "cms_type"=>$cms_type));
					
					}
				}
		
	}
	/**
	 * Deleting  store by given id
	 * @param <id> $id
	 * return array
	 */
	 function logoupload($id){
	 
	 if (!$_FILES['image']['error']) {
			$image_extension = substr($_FILES['image']['name'], strrpos($_FILES['image']['name'], ".")+1);
		}
	 if(!$_FILES['image']['error']) {				
				_upload(SITE_PATH."/modules/store/images/", $id.".".$image_extension, $_FILES['image']['tmp_name'], 1);
			}
	 
	 }
	 
	function storeDelete ($id) {
	
		$storedet=$this->storeGet($id);
		$this->db->query("DELETE FROM member_master WHERE id='{$storedet['user_id']}'");
		
	    $qry="DELETE  p.*,ps.* FROM products p INNER JOIN product_store as ps ON p.id = ps.product_id
	           WHERE ps.store_id ='$id' and p.parent_id !=0  ";
		$this->db->query($qry);
			
		$qry= "DELETE  pa.*,sa.*  FROM product_accessories pa INNER JOIN  store_accessory sa
		             WHERE  pa.id=sa.accessory_id AND sa.store_id='$id' and pa.parent_id !=0";
		$this->db->query($qry);			 
		
		
		$qry= "DELETE  cl.*,sc.*  FROM cms_link cl INNER JOIN  store_cms sc
		             WHERE  cl.id=sc.cms_id AND sc.store_id='$id' AND cms_type='L'";	 
		$this->db->query($qry);
		
		$qry= "DELETE  cp.*,sc.*  FROM cms_page cp INNER JOIN  store_cms sc
		             WHERE  cp.id=sc.cms_id AND sc.store_id='$id' AND cms_type='P'";
		$this->db->query($qry);			 

		$this->db->query("DELETE FROM store WHERE id='$id'");
	}
	
	function removeStore ($id) {
		$storedet=$this->storeGet($id);
		$this->db->query("DELETE FROM member_master WHERE id='{$storedet['user_id']}'");
		$this->db->query("DELETE FROM store WHERE id='$id'");
		$this->db->query("DELETE FROM product_store WHERE store_id='$id'");
		$this->db->query("DELETE FROM store_accessory WHERE store_id='$id'");
		
		
	}
	
	
	function deleteStoreCms($store_id)
	{
		$sql="select * from store_cms where store_id='$store_id' ";
	}
	/**
	 * Getting store category based on store id
	 * @param <store_id> $storeID
	 * return array
	 */
	function storeCategoriesGet ($storeID) {
		$sql = "SELECT c.category_id, c.category_name FROM master_category c LEFT JOIN store_category s ON (c.category_id = s.category_id AND s.store_id = '$storeID' AND c.is_private='N' AND  c.is_in_ui='N' ) WHERE s.category_id IS NULL ORDER BY 2";
		$rs['all']['category_id'] 	= $this->db->get_col($sql, 0);
        $rs['all']['category_name'] = $this->db->get_col("", 1);
        $sql = "SELECT c.category_id, c.category_name FROM master_category c, store_category s WHERE c.category_id = s.category_id AND s.store_id = '$storeID' AND c.is_private='N' AND  c.is_in_ui='N' ORDER BY 2";
		$rs['store']['category_id'] 	= $this->db->get_col($sql, 0);
        $rs['store']['category_name'] 	= $this->db->get_col("", 1);
		
        return $rs;
	}
	/**
	 * Loading Stores in a combo	 
	 * return array
	 */
	 function storeCombo1 ($sname) 
	 {
	 
	 //===========store redirecting===================
	/* $host=$_SERVER['HTTP_HOST'];
	 $hosturl="http://www."."$host";
	 echo $domainpath = $_SERVER['REQUEST_URI']; 
	 $fullurl="$hosturl"."$domainpath"."/";*/
	 
      //  echo  $flag=$_REQUEST['flag'];
	   
  /* $qry4="select redirect_url from store where name='$sname'";
	     $rs4		= 	$this->db->get_row($qry4,ARRAY_A);
		 $reURL  	=   $rs4['redirect_url'];
		//$pieces = explode(".", $reURL);
		 //list($a,$b,$c)=split($reURL,".");
		// echo $pieces;
		// exit;
		 if($reURL!=""){
		             //  header("Location: $reURL"); 
		     redirect($reURL); 
			  exit;
			 }else{*/
				
				 $StoreArray['name']	=	array();
				 $StoreArray['heading']	=	array();
				  $qry   		= "select id,name,heading from store where name='$sname' and root_store='Y'";
								
				 $rs			= 	$this->db->get_row($qry,ARRAY_A);
				 
				 if(count($rs)>0){ 
				 
				 $sid  			=   $rs['id'];
												
				 $qry1 			=   "select category_id from category_store where store_id='$sid'";
				
				 $rs1			= 	$this->db->get_results($qry1,ARRAY_A);
				
				 for($i=0;$i<count($rs1);$i++) {
					 $cid   =   $rs1[$i]['category_id'];
					
					$qry2  =   "select store_id from category_store where category_id='$cid'";
					$rs2   = 	$this->db->get_results($qry2,ARRAY_A);
					for($j=0;$j<count($rs2);$j++) {
						$stid     		= 	$rs2[$j]['store_id'];
					    $sql      		= 	"select name,heading FROM store WHERE id='$stid'";
						
						$rs3	  		= 	$this->db->get_row($sql,ARRAY_A);
						if(trim($rs3['name']) != '' && trim($rs3['heading']) != '') {
							if(in_array($rs3['name'],$StoreArray['name']))
								continue;
							
							 $StoreArray['name'][]		=	$rs3['name'];
							
							$StoreArray['heading'][]	=	$rs3['heading'];
						}
					} # Close inner for loop
				} # Close outer for loop
		}else{
		                 
		                    $StoreArray		=	$rs['name'];
							$StoreArray     =	$rs['heading'];
							}
							
				return $StoreArray;		
	//  }
	
	}
	 
	
	
	function storeCombo () {
	$sql	= "SELECT name, heading FROM store WHERE root_store='Y'";
	
		//$sql	= "SELECT name, heading FROM store WHERE 1";
		//==============
		/*$res=mysql_query($sql1)
		while($row=mysql_fetch_array($res)){
		$sid=$row['id'];
		
		//$qry="select a.*,b.* from master_category a, category_modules b where a.category_id =b.category_id and a.level='0' and b.module_id='$id'";

		
		}*/
		//================
		
        $rs['name'] = $this->db->get_col($sql, 0);
        $rs['heading'] = $this->db->get_col("", 1);
        return $rs;
	}
	/**
	 * Add Edit Payment
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message	 
	 */
	function paymentAddEdit (&$req) {
		extract($req);
		global $store_id;
		switch($payment_provider){
			case 'R':
				$pay_userid		=	$pay_userid;
			break;			
			case 'A':
				$pay_userid		=	$pay_useridauth;
			break;				
		}	
		if(!trim($payment_provider)) {
			$message = "Select the provider";
		}else {
			$array = array("store_id"=>$store_id,"payment_provider"=>$payment_provider,
							"pay_userid"=>$pay_userid,"pay_password"=>$pay_password,
							"pay_api_signature"=>$pay_api_signature,"pay_transkey"=>$pay_transkey,
							"pay_email"=>$pay_email,"pay_keyfile"=>$pay_keyfile,"pay_configfile"=>$pay_configfile);
			if($id) {
					$array['id'] = $id;
					$this->db->update("store_payment", $array, "id='$id'");
			} else {
					$this->db->insert("store_payment", $array);
					$id = $this->db->insert_id;
			}
			return true;
		}
		return $message;
	}
	/**
	 * Getting payment details by id
	 * @param <id> $id
	 * return array
	 */
	function getPayment($id){		
		$rs = $this->db->get_row("SELECT * FROM store_payment WHERE id = '$id'", ARRAY_A);
		return $rs;
	}
	/**
	 * Getting payment details by given store_id
	 * @param <store_id> $store_id
	 * return array
	 */
	function getPaymentbystoreid($store_id){		
		$rs 	= 	$this->db->get_row("SELECT * FROM store_payment WHERE store_id = '$store_id'", ARRAY_A);
		return $rs;
	}
	
	function GetAllStoreCategoty($store_id=0)
	{
		if($store_id>0)
		{
			$qry				=	"select category_id from category_store where store_id=$store_id";
			$rs['category_id'] 	= 	$this->db->get_col($qry, 0);
			return $rs;
		}
	}
	// Update by Retheesh Kumar for Taking Art
	function addStore($data)
	{
		
		$sql = "select * from store where name='".$data['name']."'";
		$rs = $this->db->get_results($sql);
		if (count($rs)>0)		
		{
			return false;
		}
		else 
		{
		$data['active']='Y';
			
			$this->db->insert("store",$data);
			
			$id = $this->db->insert_id;
							
				if ($this->config["all_products_to_store"]=="Y")
				{
					$sql_pr = "select * from products where hide_in_mainstore='N'";
					$prd_store = $this->db->get_results($sql_pr);
					for ($i=0;$i<sizeof($prd_store);$i++)
					{
						$arr_pr = array();
						$arr_pr["product_id"] = $prd_store[$i]->id;
						$arr_pr["store_id"]   = $id;
						$this->db->insert("product_store",$arr_pr);
					}
				}
				
				if ($this->config["all_accessories_to_store"]=="Y")
				{
					$sql_ac = "select * from product_accessories where parent_id=0";
					$ac_store = $this->db->get_results($sql_ac);
					for ($i=0;$i<sizeof($ac_store);$i++)
					{
						$arr_pr = array();
						$arr_pr["accessory_id"] = $ac_store[$i]->id;
						$arr_pr["store_id"]   = $id;
						$this->db->insert("store_accessory",$arr_pr);
					}
				}
					if ($this->config["all_categories_to_store"]=="Y")
				{
					$sql_ac = "select * from master_category";
					$ac_store = $this->db->get_results($sql_ac);
					for ($i=0;$i<sizeof($ac_store);$i++)
					{
						$arr_pr = array();
						$arr_pr["category_id"] = $ac_store[$i]->category_id;
						$arr_pr["store_id"]   = $id;
						$this->db->insert("store_category",$arr_pr);
					}
				}
				
				
				// mapping the store and cms page 
				$qry	=	"select * from cms_link where area LIKE '%store_%' "; 
				$res	=	mysql_query($qry); 
				
				while($row=mysql_fetch_array($res))
				{
					$title="";$url="";$position="";$cms_are="";$area="";$active="Y";
					$title		=	$row["title"];
					$url		=	$row["url"];
					$position	=	$row["position"];
					$cms_area	=	$row["area"];
					$area		=	str_replace("store_", "", $cms_area);
					//echo " C ".$area;
					$active		=	$row["active"];
					
					$cmsarray 	= 	array("title"=>$title,"url"=>$url,"position"=>$position,"area"=>$area,"active"=>$active);
					$this->db->insert("cms_link", $cmsarray);
					$cms_link_id = $this->db->insert_id;
					$cms_type	=	"L";
					//$store_cms_array	=	array("store_id"=>$id,"cms_id "=>$cms_link_id,"cms_type"=>$cms_type);
					$this->db->insert("store_cms", array("store_id"=>$id, "cms_id"=>$cms_link_id, "cms_type"=>$cms_type));
					
				}
				//mapping cms menus to store
				$qry	=	"select * from cms_menu where active='Y'"; 
				$res	=	mysql_query($qry); 
					while($row=mysql_fetch_array($res))
					{
						
						$this->db->insert("store_cms", array("store_id"=>$id, "cms_id"=>$row['id'], "cms_type"=>'M'));
						
						
						//insert default pages to that store						
						$this->insertDefaltPages($row['id'],$id);
					}
					
				//added by adarsh for enabling the flat rate shipping
				
				// for thking the flat rate shipping price of admin
				$sql="select * from flat_rate_shipping where store_id=0";
				$shipping_val = $this->db->get_row($sql,ARRAY_A);	
				$ship_array=array("shipping_fee"=>$shipping_val['shipping_fee'],"shipping_status"=>'Y',"store_id"=>$id);
				$this->db->insert("flat_rate_shipping", $ship_array);
					
				//added by adarsh for enabling the flat rate shipping ends
				
			return $id;
		}
	}
	/**
	 * For Adding Stores indexpage Content
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message	 
	 */
	function createStoreContent(&$req){		
		extract($req);
		global $store_id;		
		if(!trim($heading)) {
			$message = "Heading is required";
		 } else {
			$array = array("heading"=>$heading,"description"=>$content);			
			$this->db->update("store", $array, "id='$store_id'");
			return true;
		}	
		return $message;
	}
	function menuPermissionAddEdit(&$req, $sId = 'Store',$mod,$menu)
	{
	    
	
		$hide = $req['hide'];
		$menu_hide = $req['menu_hide'];
		$menu_add = $req['add'];
		$menu_edit = $req['edit'];
		$menu_delete = $req['delete'];
		$id = $req['id'];
		//echo "<pre>";
		//print_r($menu_hide);
		
		if($id)
		$this->db->query("DELETE FROM store_permission WHERE store_id = $id");
		else
		$this->db->query("DELETE FROM store_permission WHERE store_id = 0");
		
		//Inserting modules
		if(sizeof($hide)>0)
		{
			foreach($mod as $mode_key => $mode_value)
			{
			$hide_value = $hide[$mode_key];
			//list($m,$mid) = split("-",$hide_value);
			
				if($hide_value)
				{
				$array = array("store_id"=>$id,"module_id"=>$mode_key,"hide"=>"Y","add"=>"NA","edit"=>"NA","delete"=>"NA");
				$this->db->insert("store_permission",$array);
				}
				else
				{
				$array = array("store_id"=>$id,"module_id"=>$mode_key,"hide"=>"N","add"=>"NA","edit"=>"NA","delete"=>"NA");
				$this->db->insert("store_permission",$array);
				}
			}
		}
		else
		{
			foreach($mod as $mode_key => $mode_value)
			{
			$array = array("store_id"=>$id,"module_id"=>$mode_key,"hide"=>"N","add"=>"NA","edit"=>"NA","delete"=>"NA");
			$this->db->insert("store_permission",$array);
			}
		}
		
		//Inserting menus
		
		foreach($menu as  $menu_value)
		{
			if(sizeof($menu_value)>0)
			{
				foreach($menu_value as $menu_obj)
				{
				//echo $menu_obj->id;
					if($menu_hide[$menu_obj->id])
					{
					$h = "Y";
					}
					else
					{
					$h = "N";
					}
					if($menu_add[$menu_obj->id])
					{
					$a = "Y";
					}
					else
					{
					$a = "N";
					}
					if($menu_edit[$menu_obj->id])
					{
					$e = "Y";
					}
					else
					{
					$e = "N";
					}
					if($menu_delete[$menu_obj->id])
					{
					$d = "Y";
					}
					else
					{
					$d = "N";
					}
                $module_id = $menu_obj->module_id;
				$module_menu_id = $menu_obj->id;
			    $menu_array = array("store_id"=>$id,"module_id"=>$module_id,"module_menu_id"=>$module_menu_id,"hide"=>$h,"add"=>$a,"edit"=>$e,"delete"=>$d);
			    $this->db->insert("store_permission",$menu_array);
				
				}
			}
		}

		return "set";
	}
	function getStorePermission($id)
	{
		$sql = "SELECT * FROM store_permission 	WHERE store_id = $id";
	    $rs = $this->db->get_results($sql);
		 return $rs;
	}
		function getStorePermissionById($store_id,$module_menu_id)
	{
		$sql = "SELECT * FROM store_permission WHERE store_id = $store_id AND module_menu_id = $module_menu_id";
	    $rs = $this->db->get_row($sql,ARRAY_A);
        return $rs;
	}
	function ModulePermission($store_id)
	{
	$sql = "SELECT a.store_id,a.module_id,a.module_menu_id,a.hide,a.add,a.edit,a.delete,
	               b.id,b.name FROM store_permission a,module b WHERE a.module_menu_id = '0' AND a.store_id = $store_id AND a.module_id = b.id";
	$rs = $this->db->get_results($sql);
	for($i = 0;$i<count($rs);$i++)
	{
	$rs1[$rs[$i]->name] = $rs[$i];
	}
	return $rs1;
	}
	function MenuPermission($store_id)
	{
	$sql = "SELECT a.store_id,a.module_id,a.module_menu_id,a.hide,a.add,a.edit,a.delete,
	               b.id,b.name,
				   c.menu,c.id 
			 FROM store_permission a,module b,module_menu c 
			 WHERE  a.store_id = $store_id AND a.module_id = b.id AND a.module_menu_id = c.id";
	//echo $sql;
	$rs = $this->db->get_results($sql);
	
	for($i = 0;$i<count($rs);$i++)
	{
	$rs1[$rs[$i]->menu] = $rs[$i];
	}
	
	return $rs1;
	}
/**
	 * Getting payment details by id
	 * @param <id> $id
	 * return array
	 */
	function getStoreByUser($user_id){		
		$rs = $this->db->get_row("SELECT * FROM store WHERE user_id = '$user_id'", ARRAY_A);
		return $rs;
	}
	
	function getUserDetails($uid)
	{
		 $sql="SELECT * FROM `member_master` where `id`=$uid and active='N'";
		
       if(count($this->db->get_results($sql))>0)
	   {
	  	 return true;
	   }else
	   {
	   	return false;
	   }
		
	}
	function GetMainstore(){
		$sql = "SELECT * FROM main_store WHERE id = 0";
		$rs = $this->db->get_results($sql, ARRAY_A);
		return $rs;
	}
	function SetMainstore($req){
		$this->db->update("main_store", $req);
	}
	
	function storeAllDetailsByName($storename) {
		 $rs = $this->db->get_row("SELECT T1.*,T3.content as home_page_content FROM store T1 LEFT JOIN store_cms T2 ON(T1.id=T2.store_id)
		 	LEFT JOIN cms_page T3 ON(T3.id=T2.cms_id) LEFT JOIN cms_menu T4 ON(T4.id=T3.menu_id) AND  T4.seo_url='store' WHERE T1.name='{$storename}' AND T2.cms_id is NOT NULL  AND T3.post_admin='' ORDER BY T3.post_date  DESC limit 0,1", ARRAY_A);
		return $rs;
	}
	
	function GetStoreOwnerEmailByStoreId($str_id)
	//salim
	{
		$sql	=	"select T1.email from member_master T1,store T2 where T1.id = T2.user_id and T2.id = ".$str_id ;
		$rs = $this->db->get_results($sql, ARRAY_A);
		return $rs;
	}
	function StoreAdminProfileEdit($_req,$store_id){
	//print_r($_req);exit;
	$id 	=	$_req['id'];
	$qry	=	"select image_extension from store where user_id='$id'";
	 			$rs     = $this->db->get_row($qry, ARRAY_A);
	            $imgext=$rs['image_extension'];
					if($_FILES['image']['name']==""){
						$image_extension = $imgext;
		            }
		            else{
						$image_extension = substr($_FILES['image']['name'], strrpos($_FILES['image']['name'], ".")+1);
					}
			if($_FILES['image']['name']!=""){
			if(($image_extension != "" && $image_extension != "jpg" && $image_extension != "gif" && $image_extension != "png")) {
			$message = "Image type not supported. Please upload only gif, jpg or png format.";
			}
			}
			else {
			$content	=	addslashes($_req['content']);
			$arraymem	=	array("first_name"=>$_req['first_name'],"last_name"=>$_req['last_name'],'email'=>$_req['memmail']);
			$arrayadr	=	array("address1"=>$_req['address1'],"address2"=>$_req['address2'],"address3"=>$_req['address3'],"city"=>$_req['city'],"country"=>$_req['country'],"state"=>$_req['state'],"postalcode"=>$_req['postalcode'],"telephone"=>$_req['telephone']);
			$arraystr	=	array("description"=>$_req['description'],"content"=>$content,"image_extension"=>$image_extension,"heading1"=>$_req['heading1'],"heading2"=>$_req['heading2'],'email'=>$_req['email'],'redirect_url'=>$_req['redirect_url'],'page_title'=>$_req['page_title'],'meta_keywords'=>$_req['meta_keywords'],'meta_description'=>$_req['meta_description']);
			
			$uid = $this->db->update("member_master", $arraymem, "id='$id'");
			$this->db->update("member_address", $arrayadr, "user_id='$id'");
			$this->db->update("store", $arraystr, "user_id='$id'");
				
			$message = $uid;
			
			if($_FILES['image']){//Checking whether option for LOGO upload is present			
			if(!$_FILES['image']['error']) {				
				_upload(SITE_PATH."/modules/store/images/", $store_id.".".$image_extension, $_FILES['image']['tmp_name'], 1);
			}
			}
		}
			
			return $message;	
	}
	function getStorePermission_1($id)
	{
		$sql = "SELECT * FROM store_permission 	WHERE store_id = $id";
	    $rs = $this->db->get_results($sql,ARRAY_A);
		 return $rs;
	}
	
	function changeActive($current_act,$id)
	{
		if ($current_act=='Y')
		{
			$this->db->query("update store set `active`='N' WHERE id=$id");
			return true;
		}
		else
		{
			$this->db->query("update store set `active`='Y' WHERE id=$id");
			return true;
		}
	}
	
	
	function StoreAdminMemProfileEdit($_req,$store_id){
	//print_r($_req);exit;
	$id 	=	$_req['id'];
	$qry	=	"select image_extension from store where user_id='$id'";
	 			$rs     = $this->db->get_row($qry, ARRAY_A);
	            $imgext=$rs['image_extension'];
					if($_FILES['image']['name']==""){
						$image_extension = $imgext;
		            }
		            else{
						$image_extension = substr($_FILES['image']['name'], strrpos($_FILES['image']['name'], ".")+1);
					}
			if($_FILES['image']['name']!=""){
			if(($image_extension != "" && $image_extension != "jpg" && $image_extension != "gif" && $image_extension != "png")) {
			$message = "Image type not supported. Please upload only gif, jpg or png format.";
			}
			}
			else {
			$content	=	addslashes($_req['content']);
			$arraymem	=	array("first_name"=>$_req['first_name'],"last_name"=>$_req['last_name'],'email'=>$_req['memmail'],'password'=>$_req['password']);
			$arrayadr	=	array("address1"=>$_req['address1'],"address2"=>$_req['address2'],"address3"=>$_req['address3'],"city"=>$_req['city'],"country"=>$_req['country'],"state"=>$_req['state'],"postalcode"=>$_req['postalcode'],"telephone"=>$_req['telephone']);
			//$arraystr	=	array("description"=>$_req['description'],"content"=>$content,"image_extension"=>$image_extension,"heading1"=>$_req['heading1'],"heading2"=>$_req['heading2'],'email'=>$_req['email'],'redirect_url'=>$_req['redirect_url'],'page_title'=>$_req['page_title'],'meta_keywords'=>$_req['meta_keywords'],'meta_description'=>$_req['meta_description']);
			$arraystr	=	array('email'=>$_req['email']);
			$uid = $this->db->update("member_master", $arraymem, "id='$id'");
			$this->db->update("member_address", $arrayadr, "user_id='$id'");
			$this->db->update("store", $arraystr, "user_id='$id'");
				
			$message = $uid;
			
			if($_FILES['image']){//Checking whether option for LOGO upload is present			
			if(!$_FILES['image']['error']) {				
				_upload(SITE_PATH."/modules/store/images/", $store_id.".".$image_extension, $_FILES['image']['tmp_name'], 1);
			}
			}
		}
			
			return $message;	
	}
	
	function StoreAdminHomeEdit($_req,$store_id){
	//print_r($_req);exit;
	$id 	=	$_req['id'];
	$qry	=	"select image_extension from store where user_id='$id'";
	 			$rs     = $this->db->get_row($qry, ARRAY_A);
	            $imgext=$rs['image_extension'];
					if($_FILES['image']['name']==""){
						$image_extension = $imgext;
		            }
		            else{
						$image_extension = substr($_FILES['image']['name'], strrpos($_FILES['image']['name'], ".")+1);
					}
			if($_FILES['image']['name']!=""){
			if(($image_extension != "" && $image_extension != "jpg" && $image_extension != "gif" && $image_extension != "png")) {
			$message = "Image type not supported. Please upload only gif, jpg or png format.";
			}
			}
			else {
			$content	=	addslashes($_req['content']);
			$arraystr	=	array("description"=>$_req['description'],"content"=>$content,"image_extension"=>$image_extension,"heading1"=>$_req['heading1'],"heading2"=>$_req['heading2'],'page_title'=>$_req['page_title'],'meta_keywords'=>$_req['meta_keywords'],'meta_description'=>$_req['meta_description']);
			$this->db->update("store", $arraystr, "user_id='$id'");
				$message = $id;
				
			
			if($_FILES['image']){//Checking whether option for LOGO upload is present			
			if(!$_FILES['image']['error']) {				
				_upload(SITE_PATH."/modules/store/images/", $store_id.".".$image_extension, $_FILES['image']['tmp_name'], 1);
			}
			}
		}
			
			
			return $message;	
	}
	
	function StoreAdminUrlEdit($_req,$store_id){
	//print_r($_req);exit;
	$id 	=	$_req['id'];
	$qry	=	"select image_extension from store where user_id='$id'";
	 			$rs     = $this->db->get_row($qry, ARRAY_A);
	            $imgext=$rs['image_extension'];
					if($_FILES['image']['name']==""){
						$image_extension = $imgext;
		            }
		            else{
						$image_extension = substr($_FILES['image']['name'], strrpos($_FILES['image']['name'], ".")+1);
					}
			if($_FILES['image']['name']!=""){
			if(($image_extension != "" && $image_extension != "jpg" && $image_extension != "gif" && $image_extension != "png")) {
			$message = "Image type not supported. Please upload only gif, jpg or png format.";
			}
			}
			else {
			$content	=	addslashes($_req['content']);
		    if(strstr($_req['redirect_url'],$_SERVER['SERVER_NAME']))
			{
				$message = "Invalid Redirect URL";
			}
			else{			
				$arraystr	=	array('redirect_url'=>$_req['redirect_url']);
				$this->db->update("store", $arraystr, "user_id='$id'");
				$message = $uid;
			}
			
			if($_FILES['image']){//Checking whether option for LOGO upload is present			
			if(!$_FILES['image']['error']) {				
				_upload(SITE_PATH."/modules/store/images/", $store_id.".".$image_extension, $_FILES['image']['tmp_name'], 1);
			}
			}
		}
			
			return $message;	
	}

	function getIpnMessage($id)
	{
		if($id)
		{
			$sql="select * from ipn_log where item_number='$id' or subscr_id='$id' order by log_time desc";
			$rs = $this->db->get_results($sql);
			return $rs;
		}	
		
	}

	function storeDisabledList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
		$sql		= "SELECT s.*,m.first_name,m.last_name,m.active as mem_active FROM store s INNER JOIN  member_master m ON s.user_id=m.id left join member_subscription ms ON ms.user_id=m.id WHERE 1 and deleted='Y' ";
		
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		
		$stop_subs=$this->config['stop_subs'];
		
		foreach($rs[0] as $k=>$val)
		{
			$member_id=$val->user_id;
			// retriving subscription details
			
			$sql2  = "SELECT * FROM `member_subscription` where user_id='$member_id' ORDER BY `enddate` DESC LIMIT 0 , 1";
			$rs2   = $this->db->get_row($sql2, ARRAY_A);
			$cur_date			=	date("Y-m-d");
			
			$cur_enddate	=	$rs2['enddate'];
			list($y,$m,$df)	=	split("-",$cur_enddate);
			$d	=	substr($df, 0, 2);
			$endDate	=	$y."-".$m."-".$d;
			
			if($endDate > $cur_date && $stop_subs =='Y')
			  $subs_status='To be canceled on '.$endDate;
			else if ($endDate > $cur_date && $stop_subs!='Y')
		  	  $subs_status='Active';
			else if ($endDate < $cur_date )
			  $subs_status='Canceled';
			  
			$rs[0][$k]->subs_status=$subs_status;
			
		}
		
		
		return $rs;
	}
	
	function disableStore($id,$status)
	{		
		$this->db->update("store", array('deleted'=>$status), "id='$id'");
		return $id;
	}
	
	
	function storeDelete2 ($id) {
	
		$storedet=$this->storeGet($id);
		
		//delete from member module
		$this->db->query("DELETE FROM member_master WHERE id='{$storedet['user_id']}'");
		$this->db->query("DELETE FROM member_address WHERE user_id='{$storedet['user_id']}'");
		$this->db->query("DELETE FROM member_subscription WHERE user_id='{$storedet['user_id']}'");
		$this->db->query("DELETE FROM session_det WHERE user_id='{$storedet['user_id']}'");
		$this->db->query("DELETE FROM member_master WHERE from_store='$id''");
		
		//delete from product module
	    $qry="DELETE  p.*,ps.* FROM products p INNER JOIN product_store as ps ON p.id = ps.product_id
	           WHERE ps.store_id ='$id' and p.parent_id !=0  ";
		$this->db->query($qry);
		$this->db->query("DELETE FROM product_store WHERE store_id ='$id'");
		
		
		$qry= "DELETE  pa.*,sa.*  FROM product_accessories pa INNER JOIN  store_accessory sa
		             WHERE  pa.id=sa.accessory_id AND sa.store_id='$id' and pa.parent_id !=0";
		$this->db->query($qry);	
		$this->db->query("DELETE FROM store_accessory WHERE store_id ='$id'");	
		
		// delete from store category		
		//$this->db->query("DELETE FROM store_category WHERE store_id ='$id'");		 
		
		// delete from CMS module	 
		$qry= "DELETE  cl.*,sc.*  FROM cms_link cl INNER JOIN  store_cms sc
		             WHERE  cl.id=sc.cms_id AND sc.store_id='$id' AND cms_type='L'";	 
		$this->db->query($qry);
		
		$qry= "DELETE  cp.*,sc.*  FROM cms_page cp INNER JOIN  store_cms sc
		             WHERE  cp.id=sc.cms_id AND sc.store_id='$id' AND cms_type='P'";
		$this->db->query($qry);	
		
		$this->db->query("DELETE FROM store_cms WHERE store_id ='$id'");	
		
		//shipping 
		$this->db->query("DELETE FROM flat_rate_shipping WHERE store_id ='$id'");
		
		//payment
		$this->db->query("DELETE FROM store_payment_details WHERE store_id ='$id'");
		
		//cart
		$qry= "DELETE  a.*,b.*  FROM cart a INNER JOIN cart_accessory b WHERE  a.id=b.cart_id  AND a.store_id='$id'";
		$this->db->query($qry);	
		$this->db->query("DELETE FROM cart WHERE store_id ='$id'");	
		
		//order
		$qry= "DELETE  a.*,b.*  FROM orders a INNER JOIN order_products b WHERE  a.id=b.order_id AND a.store_id='$id'";
		$this->db->query($qry);	
		$this->db->query("DELETE FROM orders WHERE store_id ='$id'");	
		
		$this->db->query("DELETE FROM store WHERE id='$id'");
	}

	
}//End class
?>