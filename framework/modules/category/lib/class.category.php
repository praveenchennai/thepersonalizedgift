<?
class Category extends FrameWork {

	function Category()
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
	
	function listAllCategory2($keysearch='N',$category_search='',$parent_id=0,$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy="1", $store_id="",$id,$parent_change_id = '')
	{
		if($parent_change_id > 0)
		$exqry = " AND category_id <> $parent_change_id";
		
		$qry		=	"select * from master_category where is_private='N' $exqry";
		if($keysearch=='Y' && $category_search)
			$qry	.=	" and category_name LIKE '%$category_search%' ";
		else
			$qry	.=	" and parent_id='$parent_id' ";
		
		if($store_id)
		{
			$qry		=	"SELECT `master_category`.* FROM `store_category` Inner Join `master_category` ON `master_category`.`category_id` = `store_category`.`category_id` where `store_category`.`store_id` =  '$store_id' $exqry
							";
			
			if($keysearch=='Y' && $category_search)
			$qry	.=	" and `master_category`.category_name LIKE '%$category_search%' ";
			else
			$qry	.=	" and `master_category`.parent_id='$parent_id' ";
		}
		if($id){
		//	$qry		=	"SELECT category_name FROM master_category  where category_id=(select category_id  from category_modules where  module_id='$id')";
		if($parent_change_id > 0)
		$exqry = " AND a.category_id <> $parent_change_id";
		
		$qry="select a.*,b.* from master_category a, category_modules b where a.category_id =b.category_id and a.level='0' and b.module_id='$id' $exqry";
		
		
		
		}
			
			
			$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}

	
	
	
	/*
	This function fetch all the active category of the selected owner

	*/
function listAllCategory($keysearch='N',$category_search='',$parent_id=0,$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy="1", $store_id="",$parent_change_id = '')
	{
		if($parent_change_id > 0)
		$exqry = " AND category_id <> $parent_change_id";
		
		$qry		=	"select * from master_category where is_private='N' $exqry";
		if($keysearch=='Y' && $category_search)
			$qry	.=	" and category_name LIKE '%$category_search%' ";
		else
			$qry	.=	" and parent_id='$parent_id' ";
		
		if($store_id)
		{
			$qry		=	"SELECT `master_category`.* FROM `store_category` Inner Join `master_category` ON `master_category`.`category_id` = `store_category`.`category_id` where `store_category`.`store_id` =  '$store_id' $exqry
							";
			
			if($keysearch=='Y' && $category_search)
			$qry	.=	" and `master_category`.category_name LIKE '%$category_search%' ";
			else
			$qry	.=	" and `master_category`.parent_id='$parent_id' ";
		}
		
		/*if($id){
		//	$qry		=	"SELECT category_name FROM master_category  where category_id=(select category_id  from category_modules where  module_id='$id')";
		$qry="select a.*,b.* from master_category a, category_modules b where a.category_id =b.category_id and a.level='0' and b.module_id='$id'";
		}*/		
		
		
			
			$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}

function getCompletePath($category_id, $sId = '', $fId = '' )
	{
		$arr=array();
		$name="";
		if($category_id>0)
		{
			$qry  			= 	"select * from  master_category where category_id='$category_id' order by category_name ASC";
			$row 			= 	$this->db->get_row($qry,ARRAY_A);
			$parent_id		=	$row['parent_id'];
			$name			=	$row['category_name'];
			while($parent_id>0)
			{
				$qry 		= 	"select * from master_category where category_id='$parent_id' order by category_name ASC";
				$row3		=	$this->db->get_row($qry,ARRAY_A);
				$name		=	"<a href=\"".makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']),"act=list&parent_id=$parent_id&sId=$sId&fId=$fId&limit={$_REQUEST['limit']}")."\">".$row3['category_name']."</a>"."  &raquo;  ".$name;
				$parent_id	=	$row3['parent_id'];
			}
			$name			=	"<a href=\"".makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']),"act=list&parent_id=0&sId=$sId&fId=$fId&limit={$_REQUEST['limit']}")."\">Top Level</a> &raquo; ".$name;
		}
		return $name;
	}
	function GetTopLevelCategory($category_id)
		{
		$qry	=	"SELECT parent_id FROM master_category WHERE category_id=".$category_id;
		$row	=	$this->db->get_row($qry,ARRAY_A);
		$parent_id	=	$row['parent_id'];
		while ($parent_id>0)
			{
			$category_id=$parent_id;
			$qry	=	"SELECT parent_id FROM master_category WHERE category_id=".$category_id;
			$row	=	$this->db->get_row($qry,ARRAY_A);
			$parent_id	=	$row['parent_id'];
			}
		return $category_id;
		}
	function getCompletePathforCms($category_id, $sId = '', $fId = '',$area )
	{
		$arr=array();
		$name="";
		if($category_id>0)
		{
			$qry  			= 	"select * from  master_category where category_id='$category_id' order by category_name ASC";
			$row 			= 	$this->db->get_row($qry,ARRAY_A);
			$parent_id		=	$row['parent_id'];
			$name			=	$row['category_name'];
			while($parent_id>0)
			{
				$qry 		= 	"select * from master_category where category_id='$parent_id' order by category_name ASC";
				$row3		=	$this->db->get_row($qry,ARRAY_A);
				$name		=	"<a href=\"".makeLink(array("mod"=>"cms", "pg"=>"link"),"act=list&parent_id=$parent_id&sId=$sId&fId=$fId&area=$area&cms_id={$_REQUEST['cms_id']}&limit={$_REQUEST['limit']}")."\">".$row3['category_name']."</a>"."  &raquo;  ".$name;
				$parent_id	=	$row3['parent_id'];
			}
			$name			=	"<a href=\"".makeLink(array("mod"=>"cms", "pg"=>"link"),"act=list&parent_id=0&sId=$sId&fId=$fId&area=$area&cms_id={$_REQUEST['cms_id']}&limit={$_REQUEST['limit']}")."\">Top Level</a> &raquo; ".$name;
		}
		return $name;
	}
	
	
	
	function CategoryGet($category_id) {
		$rs = $this->db->get_row("SELECT * FROM master_category WHERE category_id='{$category_id}'", ARRAY_A);
		return $rs;
	}
	
	function CategoryGetName($category_id) {
		if ($category_id) {
		$rs = $this->db->get_row("SELECT category_name FROM master_category WHERE category_id='{$category_id}'", ARRAY_A);
		return $rs['category_name'];
		}
	}
	
	function parentGet($category_id) {
		 $rs = $this->db->get_row("SELECT parent_id FROM master_category WHERE category_id='{$category_id}'", ARRAY_A);
		 $pid  			=   $rs['parent_id'];
		 $qry2          =   "select category_name from master_category where category_id='$pid'";
		 $rs2			= 	$this->db->get_row($qry2,ARRAY_A);
		 $cname 		=   $rs2['category_name'];
		 return  $cname;
	}
	
	
	function IscategoryInAccessory($category_id)
		{
		
		$rs = $this->db->get_row("SELECT * FROM master_category mc,category_modules cm,module m WHERE mc.category_id='{$category_id}' and cm.category_id=mc.category_id and cm.module_id=m.id and m.folder='accessory'", ARRAY_A);
		
		if(count($rs)>0)
			return true;
		else
			return false;
		}
	function getLevelofNewcategory($category_id=0)
	{
		$level=0;
		if($category_id>0)
		{
			$qry		=	"select level+1 as level from master_category where category_id='$category_id'";
			$row 		= 	$this->db->get_row($qry,ARRAY_A);
			if($row['level']>0)
			$level	=	$row['level'];

		}
		return $level;

	}
	
	function modifycategory(&$req,$group_id)
		{
		extract($req);
		
		$is_in_ui_stat="Y";
		if($store_owner_manage)
		$store_owner_manage_stat="Y";
		else
		$store_owner_manage_stat="N";
		if($mandatory)
				$mamndatory_stat="Y";
		else
				$mamndatory_stat="N";
		/*if($accessory_category)
				$accessory_category_stat="Y";
		else
				$accessory_category_stat="N";*/
		if(trim($additional_customization_request))
				$status_additional_customization_request="Y";
			else
				$status_additional_customization_request="N";
		if(trim($customization_text_required))
				$status_customization_text_required="Y";
			else
				$status_customization_text_required="N";
		if(trim($is_monogram))
				$status_is_monogram="Y";
			else
				$status_is_monogram="N";
		$parent_id='0';
		$active='y';
		if(!trim($category_name)) {
			$message 				=	"Category name is required";
			} else {
			$array 			= 	array("mandatory"=>$mamndatory_stat,"store_owner_manage"=>$store_owner_manage_stat,"is_in_ui"=>$is_in_ui_stat,"category_name"=>$category_name,"category_description"=>$category_description,"parent_id"=>$parent_id,"active"=>$active,"display_order"=>$display_order,"customization_text_required"=>$status_customization_text_required,"additional_customization_request"=>$status_additional_customization_request,"is_monogram"=>$status_is_monogram);
			$this->db->insert("master_category", $array);
			$category_id = $this->db->insert_id;
			$this->db->insert("product_accessory_group_categories", array('group_id'=>$group_id,'category_id'=>$category_id));
			return $category_id;
			}
		}
	function categoryAddEdit (&$req,$file,$tmpname,$file_m,$tmpname1,$sId='Category',$s_id="") {
		extract($req);		
		
   		if ($file){
			$dir			=	SITE_PATH."/modules/category/images/";
			$file1			=	$dir."thumb/";
			$resource_file	=	$dir.$file;
			$path_parts 	= 	pathinfo($file);

		}
		$file1_type="";
		if ($file_m){
		
			$dir1			=	SITE_PATH."/modules/category/images/";
			$file2	=			$dir1."thumb/";
			$resource_file1	=	$dir1.$file_m;
			$path_parts1 	= 	pathinfo($file_m);
			
			$file1_type=$path_parts1['extension'];
			
		}
		
		if($is_in_ui)
		$is_in_ui_stat="Y";
		else
		$is_in_ui_stat="N";
		if($s_id)
			$is_private="Y";
		else
			$is_private="N";
			
		if($store_owner_manage)
		{
		$store_owner_manage_stat="Y";
		}
		else
		{
			$store_owner_manage_stat="N";
		
		}
		if($mandatory)
				$mamndatory_stat="Y";
		else
				$mamndatory_stat="N";
		/*if($accessory_category)
				$accessory_category_stat="Y";
		else
				$accessory_category_stat="N";*/
		if(trim($additional_customization_request))
				$status_additional_customization_request="Y";
			else
				$status_additional_customization_request="N";
		if(trim($customization_text_required))
				$status_customization_text_required="Y";
			else
				$status_customization_text_required="N";
		if(trim($is_monogram))
				$status_is_monogram="Y";
			else
				$status_is_monogram="N";

		//get the Level of the parent category;
		if(!trim($category_name)) {
			$message 				=	"$sId name is required";
		} else {
			if ($file){
				$array 			= 	array("base_price"=>$base_price,"store_owner_manage"=>$store_owner_manage_stat,"is_in_ui"=>$is_in_ui_stat,"category_name"=>$category_name,"category_description"=>$category_description,"category_image"=>$path_parts['extension'],"active"=>$active,"is_private"=>$is_private,"parent_id"=>$parent_id,"display_order"=>$display_order,"customization_text_required"=>$status_customization_text_required,"additional_customization_request"=>$status_additional_customization_request,"is_monogram"=>$status_is_monogram,"html_desc"=>$html_desc);
			}else{
				$array 			= 	array("base_price"=>$base_price,"mandatory"=>$mamndatory_stat,"store_owner_manage"=>$store_owner_manage_stat,"is_in_ui"=>$is_in_ui_stat,"category_name"=>$category_name,"category_description"=>$category_description,"parent_id"=>$parent_id,"active"=>$active,"is_private"=>$is_private,"display_order"=>$display_order,"customization_text_required"=>$status_customization_text_required,"additional_customization_request"=>$status_additional_customization_request,"is_monogram"=>$status_is_monogram,"html_desc"=>$html_desc);
			}
			if($file_m)
			{
				$array["category_m_over_image"]=$file1_type;
			}
			if($page_title){
				$array["page_title"]=$page_title;
			}
			if($meta_description){
				$array["meta_description"]=$meta_description;
			}
			if($meta_keywords){
				$array["meta_keywords"]=$meta_keywords;
			}
			if($seo_url){
				$array["seo_url"] = $seo_url;
			}
			if($category_id) {
				
				if($_REQUEST['change_parent_id'] != '' && $_REQUEST['change_parent_id'] >0)
				{
				//@ Afsal
				// changing parent id and change the levels
				 	$array["parent_id"] = $_REQUEST['change_parent_id'];
				 	$array["level"]     = $_REQUEST['level']+1;
				 	$level				= $_REQUEST['level']+1;
				}
				if(isset($_REQUEST['chk']))
				{
				//@ Afsal
				// changing parent id and change the levels
					$array["parent_id"] = 0;
					$array["level"]     = 0;
					$level				= 0;
				}
				$array['category_id'] 	= 	$category_id;
				$this->db->update("master_category", $array, "category_id='$category_id'");
				
				if(($_REQUEST['change_parent_id'] != '' && $_REQUEST['change_parent_id'] >0) || isset($_REQUEST['chk']))
				{
				  $this->updateLevel($category_id,$level);
				
				}
				
				
			} else {		
				$array['level'] 	= 	$this->getLevelofNewcategory($parent_id);				
				$this->db->insert("master_category", $array);
				$category_id = $this->db->insert_id;
				
			}
					if($store_owner_manage)
						{
							$this->storeCategoryManage($category_id,$s_id,'Y');
							$this->UpdateParentCategoryStore($category_id,$s_id,'Y');
						}
						else
						{
							$this->storeCategoryManage($category_id,$s_id,'');
						
						}
			$this->removeCategoryToModule($category_id);
			if(count($req['module'])>0)
			{
				foreach ($req['module'] as $id)
				{
					$this->AssignCategoryToModule($category_id, $id);
				}
			}

			if ($file){
				$save_filename	=	$category_id.".".$path_parts['extension'];
				if ( $cat_thumb_height>0 && $cat_thumb_width>0 )
				{
					_upload($dir,$save_filename,$tmpname,1,$cat_thumb_width,$cat_thumb_height);
				}
				else
				{
					_upload($dir,$save_filename,$tmpname,1);
				}
			}
			if ($file_m){
			
				$save_filename1	=	"m_".$category_id.".".$path_parts1['extension'];
				_upload($dir1,$save_filename1,$tmpname1,1);
			}
			
			return true;
		}
		
		return $message;
	}
	
	function storeCategoryManage($id,$storeid="",$Flag="")
	{
		$this->db->query("DELETE FROM store_category WHERE category_id='$id'");
		
		if(!$storeid && $Flag=='Y')
		{
			$qry		=	"select * from store where active='Y'";
			$array 		= 	$this->db->get_results($qry,ARRAY_A);
				foreach ($array as $row)
				{
					$this->db->query("INSERT INTO store_category (store_id, category_id) VALUES ('$row[id]','$id')");
					$this->db->query("UPDATE master_category set store_owner_manage ='Y' where category_id='$id' ");
				}
		}
		else
		{
			$this->db->query("INSERT INTO store_category (store_id, category_id) VALUES ('$storeid','$id')");
		}
	}

	function UpdateParentCategoryStore($category_id,$storeid)
		{
		$qry	=	"SELECT parent_id FROM master_category WHERE category_id=".$category_id;
		$row	=	$this->db->get_row($qry,ARRAY_A);
		$parent_id	=	$row['parent_id'];
		if($parent_id>0)
		{
		$this->storeCategoryManage($parent_id,$storeid,'Y');
		}
		while ($parent_id>0)
			{
			$category_id=$parent_id;
			$qry	=	"SELECT parent_id FROM master_category WHERE category_id=".$category_id;
			$row	=	$this->db->get_row($qry,ARRAY_A);
			$parent_id	=	$row['parent_id'];
			if($parent_id>0)
				{
				$this->storeCategoryManage($parent_id,$storeid,'Y');
				}
			}
		//return $category_id;
		}
	function categoryDelete($category_id, $sId = 'category') {
	
		
		$message		="";
		if($this->checkForChildcategories($category_id))
		$message 	=	"Please delete the subcategoies of the $sId ".$this->getCategoryname($category_id);
		else if($this->checkForPrduct($category_id))
		$message 	=	"Please delete the product under the  $sId ".$this->getCategoryname($category_id);
		else if($this->checkForAccessory($category_id))
		$message 	=	"Please delete the accessories under the  $sId ".$this->getCategoryname($category_id);
		else
		{
			$this->db->query("DELETE FROM master_category WHERE category_id='$category_id'");
			$this->db->query("DELETE FROM category_modules WHERE category_id='$category_id'");
			$this->db->query("DELETE FROM category_product WHERE category_id='$category_id'");
			$this->db->query("DELETE FROM category_store WHERE category_id='$category_id'");
			$this->db->query("DELETE FROM category_accessory WHERE category_id='$category_id'");
		
			//========================================================================
			$sql        =   "select id,url from cms_link";
			$rs			= 	$this->db->get_results($sql,ARRAY_A);
			//print "<pre>";
			for($i=0;$i<count($rs);$i++){
			     $id    =   $rs[$i]['id'];
				 $sess	=	strstr($rs[$i]['url'], 'sess');
				 $sess	=	substr($sess,5);
				
				if($sess) {
					$RequestArray	=	array();
					parse_str(base64_decode($sess), $RequestArray);
					//print_r($RequestArray);
					$catid  = $RequestArray[cat_id];
					if($catid==$category_id)
					{
					  $this->db->query("DELETE FROM cms_link WHERE id='$id'");
					}
				} else {
					//print "No Catid found<br>";
				}
			} 
			//=========================================================================
	
		}
		return $message;
	}

	/* check for the child categories of the selected category*/
	function checkForChildcategories($category_id) {
		$qry	=	"select count(*) as number from master_category where parent_id='$category_id'" ;
		$row 	= 	$this->db->get_row($qry,ARRAY_A);
		if($row['number']>0)
			return true;
		else
			return false;
	}

	function getChildCategories ($parent_id=0, $is_in_ui='N') {
	global $store_id;
	$qry	=	"SELECT * FROM master_category WHERE parent_id='$parent_id' AND is_in_ui='$is_in_ui'";
		// this block is commanted by shinu 18-07-07 for showing all category in store cms page of unionshop 
		/*if($store_id){			
			$qry	=	"SELECT a.* FROM master_category a,store_category b WHERE a.category_id=b.category_id AND a.parent_id='$parent_id' AND a.is_in_ui='$is_in_ui' AND b.store_id=".$store_id;
		}else{
			$qry	=	"SELECT * FROM master_category WHERE parent_id='$parent_id' AND is_in_ui='$is_in_ui'";
		}	*/
			$rs 	= 	$this->db->get_results($qry);
			return $rs;
	}

	/* Return category name*/
	function getCategoryname($category_id) {
		$qry	=	"select category_name from master_category where category_id='$category_id'";
		$row 	= 	$this->db->get_row($qry,ARRAY_A);
		return $row['category_name'];
	}
	/* Return category html description*/
	function getCategoryhtmlDescription($category_id) {
		$qry	=	"select html_desc from master_category where category_id='$category_id'";
		$row 	= 	$this->db->get_row($qry,ARRAY_A);
		return $row['html_desc'];
	}
	
	/* Return category details*/
	function getCategoryDetails($category_id) {
		if ( $category_id )	{
			$qry	=	"select * from master_category where category_id='$category_id'";
			$row 	= 	$this->db->get_row($qry,ARRAY_A);
			return $row;
		}	
	}
	
	/* check for the prodcuts under the selected category*/
	function checkForPrduct($category_id)
	{
		$qry		=	"select count(*) as number from category_product where category_id='$category_id'";
		//print_r($qry);exit;
		$row 		= 	$this->db->get_row($qry,ARRAY_A);
		if($row['number']>0)
		return true;
		else
		return false;

	}
	/* check for the accessory under the selected category*/
	function checkForAccessory($category_id)
	{
		$qry		=	"select count(*) as number from category_accessory where category_id='$category_id'";
		//print_r($qry);exit;
		$row 		= 	$this->db->get_row($qry,ARRAY_A);
		if($row['number']>0)
		return true;
		else
		return false;

	}

	function getModules($category_id=0,$s_id="")
	{
		// $qry		=	"select * from module";
		if(!$s_id)
		{
			$qry		=	"select * from module where show_category_menu='Y'";
		}else
		{
			$qry		=	"select m.* from module m, store_permission s where m.show_category_menu='Y' and m.id=s.module_id and s.store_id='$s_id' and module_menu_id=0 and s.hide='N'";
			//echo $qry;
		}
		
		$array 		= 	$this->db->get_results($qry,ARRAY_A);
		$rs = array();
		if($category_id>0)
		{
			$i=0;
			foreach ($array as $row)
			{
				$rs[$i]['id'] 		= 	$row['id'];
				$rs[$i]['name'] 	= 	$row['name'];
				$rs[$i]['checked'] 	= 	$this->CheckforCategoryToModule($category_id,$row['id']);
				$i++;
			}
		}
		else
		$rs		 = 	$array;
		return $rs;
		
	}

	function removeCategoryToModule($category_id=0)
	{
		if($category_id>0)
		$this->db->query("DELETE FROM category_modules WHERE category_id='$category_id'");
	}

	function AssignCategoryToModule($category_id=0, $id=0)
	{
		$array 		= 	array("category_id"=>$category_id,"module_id"=>$id);
		if($category_id>0 && $id>0)
		$this->db->insert("category_modules", $array);
	}

	function CheckforCategoryToModule($category_id=0,$id=0)
	{
		$qry		=	"select count(*) as number from category_modules where category_id='$category_id' and module_id='$id'";
		if($category_id>0 && $id>0)
		{
			$row 	= 	$this->db->get_row($qry,ARRAY_A);
			if($row['number']>0)
			return "checked";
			else
			return "";
		}
	}
	function GetBulkofCategory($id=0,$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy) 
	{	
		if($id>0)
		{
			$rs = $this->db->get_results_pagewise("SELECT * FROM category_bulk_price WHERE category_id='{$id}' ", $pageNo, $limit, $params, $output, $orderBy);
			return $rs;
		}
	}
	function GetCategoryBulk($id=0) {
		if($id>0)
		{
			$rs = $this->db->get_row("SELECT * FROM category_bulk_price WHERE id='{$id}'", ARRAY_A);
			return $rs;
		}
	}
	function BulkDelete($id=0,$category_id=0) {
		if($id>0)
			{
			$this->db->query("DELETE FROM category_bulk_price WHERE id='$id'");
			}
		else if($category_id>0)
			{
			$this->db->query("DELETE FROM category_bulk_price WHERE category_id='$category_id'");
			}
		else
			{
			return false;
			}
		return true;
	}
	function bulkAddEditCategory(&$req)
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
			$array 					= 	array("min_qty"=>$min_qty,"max_qty"=>$max_qty,"unit_price"=>$unit_price,"category_id"=>$id,"active"=>"Y");
			if($bid)
				{
					$this->db->update("category_bulk_price", $array, "id='$bid'");
				}
			else
				{
					$bid	=	$this->db->insert("category_bulk_price", $array);
				}
			return true;
		}
		return $message;
	}
	function getCategoryTree(&$arr, $parent_id = 0, $level = 0,$from = 'USER_SIDE', $s_id="") {
		/*if($store_id>0)
			$rs = $this->db->get_results("SELECT * FROM master_category mc, store_category sc where mc.parent_id = '$parent_id' and mc.is_in_ui<>'Y' and mc.active='y' and mc.accessory_category<>'Y' and mc.category_id=sc.category_id  and sc.store_id=$store_id");
		else*/
		if($from=='USER_SIDE')
			{
			$qry	=	"SELECT * FROM master_category WHERE parent_id = '$parent_id' and is_in_ui<>'Y' and active='y'";
			}
		else if($from=='PRODUCT_SIDE')
			{
			
			$qry	=	"	SELECT mc.* FROM 
							master_category mc,
							module m,
							category_modules cm
			 			WHERE 
			 		mc.parent_id = '$parent_id' and 
					mc.is_in_ui !='Y' and 
					mc.active='y' and
					mc.category_id=cm.category_id and
					cm.module_id=m.id and
					m.folder='product' and 
					m.active='Y'
					GROUP BY mc.display_order, mc.category_name"; 
					
			}
		else
			{
			$qry	=	"SELECT * FROM master_category WHERE parent_id = '$parent_id' and is_in_ui<>'Y' and active='y'";
			}
			
			$rs = $this->db->get_results($qry);
			
			
		if($rs) {
			foreach ($rs as $row) {
			if($s_id!="")
			{
		
				if($this->checkCategoryInStore($row->category_id,$s_id))
				{
					$arr['category_id'][] 	= $row->category_id;
					$arr['category_name'][] = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level)."-".ucwords($row->category_name);
					$this->getCategoryTree($arr, $row->category_id, $level+1,$from,$s_id);
				}
				/*else{
				$arr['category_id'][] 	= $row->category_id;
					$arr['category_name'][] = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level)."-".ucwords($row->category_name);
					$this->getCategoryTree($arr, $row->category_id, $level+1,$from,$s_id);
				}*/
			}else
			{
					$arr['category_id'][] 	= $row->category_id;
					$arr['category_name'][] = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level)."-".ucwords($row->category_name);
					$this->getCategoryTree($arr, $row->category_id, $level+1,$from,$s_id);
			}
			}
		} else {
			return ;
		}
	}
	
		function getCategoryTree1(&$arr, $parent_id = 0, $level = 0,$from = 'USER_SIDE', $s_id="") {
		
		/*if($store_id>0)
			$rs = $this->db->get_results("SELECT * FROM master_category mc, store_category sc where mc.parent_id = '$parent_id' and mc.is_in_ui<>'Y' and mc.active='y' and mc.accessory_category<>'Y' and mc.category_id=sc.category_id  and sc.store_id=$store_id");
		else*/
		if($from=='USER_SIDE')
			{
			$qry	=	"SELECT * FROM master_category WHERE parent_id = '$parent_id' and is_in_ui<>'Y' and active='y'";
			
			}
		else if($from=='PRODUCT_SIDE')
			{
			$qry	=	"	SELECT mc.* FROM 
							master_category mc,
							module m,
							category_modules cm
			 			WHERE 
			 		mc.parent_id = '$parent_id' and 
					mc.is_in_ui !='Y' and 
					mc.active='y' and
					mc.category_id=cm.category_id and
					cm.module_id=m.id and
					m.folder='store' and 
					m.active='Y'
					GROUP BY mc.display_order, mc.category_name"; 
					
					
			}
		else
			{
			$qry	=	"SELECT * FROM master_category WHERE parent_id = '$parent_id' and is_in_ui<>'Y' and active='y'";
			
			}
			//print $qry;	
			$rs = $this->db->get_results($qry);
		if($rs) {
			foreach ($rs as $row) {
			if($s_id!="")
			{
				if($this->checkCategoryInStore($row->category_id,$s_id))
				{
					
					$arr['category_id'][] 	= $row->category_id;
					$arr['category_name'][] = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level)."-".ucwords($row->category_name);
					$this->getCategoryTree($arr, $row->category_id, $level+1,$from,$s_id);
				}
			}else
			{
					$arr['category_id'][] 	= $row->category_id;
					$arr['category_name'][] = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level)."-".ucwords($row->category_name);
					$this->getCategoryTree($arr, $row->category_id, $level+1,$from,$s_id);
			}
			}
		} else {
			return ;
		}
	}
	
	/********  Aneesh   10Sep 07 ************ START */
	function get_category_tree($p_id = '0', $excl = '', $cat_tree_array = '') {
	
		if (!is_array($cat_tree_array)) $cat_tree_array = array();
		if ( (sizeof($cat_tree_array) < 1) && ($excl != '0') ) $cat_tree_array = array();

		#category_id,category_name,parent_id
		$sql1="SELECT * FROM master_category WHERE parent_id = '" . (int)$p_id . "' and active='y' ORDER BY display_order ";
		$categories_query = $this->db->get_results($sql1,ARRAY_A);
		
		foreach ($categories_query as $categories) {
			if ($excl != $categories['category_id'])  {
				$cat_tree_array[] = $categories;
			}	
			$cat_tree_array = $this->get_category_tree($categories['category_id'], $excl, $cat_tree_array);
		}
		return $cat_tree_array;
	}
	
	/********  Aneesh   10Sep 07 ************ END*/
	
	
	
	function checkCategoryInStore($id,$store_id)
	{
		$qry	=	"select * from store_category where store_id='$store_id' and category_id='$id'";
		//echo $qry;
		$rs = $this->db->get_row($qry,ARRAY_A);
		if(count($rs)>0)
			return true;
		else
			return false;
	}
	
	function GetTopLevelCategories($parent_id=0,$storename='')
		{
		$qry	=	" SELECT mc.* FROM ";
		
		if($storename)
		$qry	.=	"			store_category		AS	sc,
								store				AS 	s,";
		$qry	.=	" master_category mc where ";
		
		if($storename)
		$qry	.=" 			sc.category_id=mc.category_id	AND
								sc.store_id=s.id 				AND
								s.name='$storename'				AND ";
								
		$qry	.=	" mc.parent_id='$parent_id' AND mc.is_in_ui<>'Y' AND mc.active='y' ORDER BY mc.display_order";
		$rs 	= 	$this->db->get_results($qry);
		return $rs;
		}
	function getCategoryMenuTree (&$arr,$storename='', $parent_id = 0, $level = 0) {
		$qry	=	"	SELECT 	mc.* 
					FROM 	";
		if($storename)
		$qry	.=" 			store_category		AS	sc,
								store				AS 	s,";
		
		$qry	.=" 			master_category 	AS 	mc
								
						WHERE ";	
		if($storename)
		$qry	.=" 			sc.category_id=mc.category_id	AND
								sc.store_id=s.id 				AND
								s.name='$storename'				AND ";
		
		$qry	.=" 			mc.parent_id = '$parent_id'  	AND 
								mc.is_in_ui<>'Y' AND
								mc.active='y'				
								ORDER BY display_order";
								//echo $qry;
		$rs = $this->db->get_results($qry);
		if($rs) {
			foreach ($rs as $row) {
				$arr .= str_repeat("    ", $level)."[null, '".addslashes(ucwords($row->category_name))."', '".makeLink(array("mod"=>"product", "pg"=>"list"), "act=list&cat_id=".$row->category_id)."', null, '',";
				$this->getCategoryMenuTree($arr,$storename='', $row->category_id, $level+1);
			}
			$arr = substr($arr, 0, -1)."],";
		} else {
			$arr = substr($arr, 0, -1)."],";
			return ;
		}
	}
/*	
	function getAllaccessoryCategory(&$arr, $parent_id = 0, $level = 0) {
		$qry	=	"SELECT * FROM master_category WHERE parent_id = '$parent_id' and active='y' and (accessory_category='Y' OR is_in_ui='Y')";
		$rs = $this->db->get_results($qry);
		if($rs) {
			foreach ($rs as $row) {
				$arr['category_id'][] 	= $row->category_id;
				$arr['category_name'][] = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level)."-".ucwords($row->category_name);
				$this->getAllaccessoryCategory($arr, $row->category_id, $level+1);
			}
		} else {
			return ;
		}
	}
*/
	function getAllaccessoryCategory(&$arr, $parent_id = 0, $level = 0,$mod_id,$store_id='') {
		global $global;
		if($level =='0'){
			$name = $this->getCategoryNameById($parent_id);
				 $arr['category_id'][] 	= $parent_id;
				 $arr['category_name'][] = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level)."".$name[0]['category_name'];
		}
		if($store_id)
		{
			
			$qry	=	"SELECT a.*,b.store_id,b.category_id
		             FROM master_category a,store_category b 
					 WHERE a.parent_id = '$parent_id' 
					 and a.active='y' 
					 and b.store_id=$store_id 
					 and a.category_id = b.category_id";
							 
		}else
		{
			$qry	=	"SELECT * FROM master_category WHERE parent_id = '$parent_id' and active='y' ORDER BY display_order,category_name";
		}
		
		
		$rs = $this->db->get_results($qry);
				
		if($rs) {
			foreach ($rs as $row) {
			$cat_id = $row->category_id;
			
				$qry_mod = "SELECT * FROM category_modules WHERE category_id = '$cat_id' and module_id = '$mod_id'";
				//echo $qry_mod;
				$rs1 = $this->db->get_results($qry_mod);
				if($rs1)
				{
					$arr['category_id'][] 	= $row->category_id;
					$arr['category_name'][] = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level)."-".ucwords($row->category_name);
					$this->getAllaccessoryCategory($arr, $row->category_id, $level+1,$mod_id,$store_id);
				}	
				
			}
		} else {
			return ;
		}
	}
	function getAllaccessoryCategory_1(&$arr, $parent_id = 0, $level = 0,$mod_id,$store_id='') {
		global $global;
			
		if($store_id)
		{
			if ($global['single_prod'] == 1)//This is for personalizedgift
			{
				$qry	=	"SELECT * FROM master_category WHERE parent_id = '$parent_id' and active='y' ORDER BY display_order,category_name";
			}
			else {
			$qry	=	"SELECT a.*,b.store_id,b.category_id
		             FROM master_category a,store_category b 
					 WHERE a.parent_id = '$parent_id' 
					 and a.active='y' 
					 and b.store_id=$store_id 
					 and a.category_id = b.category_id";
			}
					 
		}else
		{
			$qry	=	"SELECT * FROM master_category WHERE parent_id = '$parent_id' and active='y' ORDER BY display_order,category_name";
		}
		
		$name = $this->getCategoryNameById($parent_id);
				 $arr['category_id'][] 	= $parent_id;
				 $arr['category_name'][] = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level)."-".$name[0]['category_name'];
		$rs = $this->db->get_results($qry);
				
		if($rs) {
			foreach ($rs as $row) {
			$cat_id = $row->category_id;
			
				$qry_mod = "SELECT * FROM category_modules WHERE category_id = '$cat_id' and module_id = '$mod_id'";
				//echo $qry_mod;
				$rs1 = $this->db->get_results($qry_mod);
				if($rs1)
				{
					$arr['category_id'][] 	= $row->category_id;
					$arr['category_name'][] = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level)."--".ucwords($row->category_name);
					//$this->getAllaccessoryCategory($arr, $row->category_id, $level+1,$mod_id,$store_id);
				}	
				
			}
		} else {
				 /*$name = $this->getCategoryNameById($parent_id);
				 $arr['category_id'][] 	= $parent_id;
				 $arr['category_name'][] = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level)."-".$name[0]['category_name'];*/
			return ;
		}
		//print_r($arr);exit;
	}

	########################################################################################
	// for displaying parent first
/*	
		function getAllaccessoryCategoryParentFirst(&$arr, $parent_id , $level = 0) {
		$qry	=	"SELECT * FROM master_category WHERE parent_id = '$parent_id' and active='y' and (accessory_category='Y' OR is_in_ui='Y')";
		$rs = $this->db->get_results($qry);
		if($rs) {
			foreach ($rs as $row) {
				$arr['category_id'][] 	= $row->category_id;
				$arr['category_name'][] = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level)."-".ucwords($row->category_name);
				//$this->getAllaccessoryCategory($arr, $row->category_id, $level+1);
			}
		} else {
				$arr['category_id'][] 	="";
				$arr['category_name'][] = "    --- No SubCategory ---";
			return ;
		}
	}
*/
		function getAllaccessoryCategoryParentFirst(&$arr, $parent_id , $level = 0,$mod_id) {
		$qry	=	"SELECT * FROM master_category WHERE parent_id = '$parent_id' and active='y'";
		$rs = $this->db->get_results($qry);
		if($rs) {
			foreach ($rs as $row) {
			    $cat_id = $row->category_id;
				$qry_module = "SELECT * FROM category_modules WHERE category_id = '$cat_id' and module_id = '$mod_id'";
				$rs1 = $this->db->get_results($qry_module);
				if($rs1)
				{
				$arr['category_id'][] 	= $row->category_id;
				$arr['category_name'][] = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level)."-".ucwords($row->category_name);
				//$this->getAllaccessoryCategory($arr, $row->category_id, $level+1);
				}
			}
		} else {
				$arr['category_id'][] 	="";
				$arr['category_name'][] = "    --- No SubCategory ---";
			return ;
		}
	}
	
	
	# for displaying the parent accessory categories first
	function getAccessoryCategoryTreeParentLevel($parent,$mod_id,$s_id="") {
		$catParent	=	0;
		$catChild	=	0;
		if($parent=="0" || $parent=="")
		{ $positionStr="";}
		else{ $positionStr	=	"";}//print($parent);
		if($parent=="0" || $parent=="") 
		{ if(!$s_id)
			{
			$rs = $this->db->get_results("SELECT * FROM master_category WHERE parent_id = '0'  and active='y' and is_private='N'"); 
			}else
			{
			//$rs = $this->db->get_results("SELECT * FROM master_category WHERE parent_id = '0'  and active='y' "); 
			$rs = $this->db->get_results("SELECT `master_category`.* FROM `store_category` Inner Join `master_category` ON `master_category`.`category_id` = `store_category`.`category_id` where `store_category`.`store_id` =  '$s_id' and `master_category`.parent_id = '0'  and `master_category`.active='y'"); 
			}
			
		}
		else
		{
			$rs 	= $this->db->get_results("SELECT * FROM master_category WHERE parent_id = '$parent'  and active='y' ");
		}
		if($rs) {
			foreach ($rs as $row) {
				$cat_id = $row->category_id;
				
					$qry_module = "SELECT * FROM category_modules WHERE category_id = '$cat_id' and module_id = '$mod_id'";
					$rs1 = $this->db->get_results($qry_module);
					if(count($rs1)>0)
					{
							$arr['category_id'][] 	= $row->category_id;
						$arr['category_name'][] = ucwords($row->category_name);
					}
				
						
			}
		} 
		else 
		{ 
			$arr['category_id'][] 	= "";
			$arr['category_name'][] = "&nbsp;&nbsp;&nbsp;&nbsp; --- No Sub category ---"; 
		}
		return $arr;
	}
	# for displaying the parent accessory categories first
	
	
	
	function getCategoryPathAccessory($category_id=0,$limit,$param='')	
	{
		$arr=array();
		$name="";
		if($category_id>0)
		{
			$qry  			= 	"select * from  master_category where category_id='$category_id' order by category_name ASC";
			$row 			= 	$this->db->get_row($qry,ARRAY_A);
			$parent_id		=	$row['parent_id'];
			$name			=	"<a href=\"".makeLink(array("mod"=>"accessory", "pg"=>"accessory"),"act=list".$param."&cat_id=".$row['category_id']."")."&limit=$limit\"  class='titleLink'>".$row['category_name']."</a>";
			while($parent_id>0)
			{
				$qry 		= 	"select * from master_category where category_id='$parent_id' order by category_name ASC";
				$row3		=	$this->db->get_row($qry,ARRAY_A);
				$name		=	"<a href=\"".makeLink(array("mod"=>"accessory", "pg"=>"accessory"),"act=list".$param."&cat_id=".$row3['category_id']."")."&limit=$limit\"  class='titleLink'>".$row3['category_name']."</a>"."  &raquo;  ".$name;
				$parent_id	=	$row3['parent_id'];
			}
			$name			=	"<a href=\"".makeLink(array("mod"=>"accessory", "pg"=>"accessory"),"act=list".$param."")."\"  class='titleLink'>Top</a> &raquo; ".$name;
		}
		return $name;
	}
	
	
	function getCategoryPathAccessoryExclude($category_id=0,$limit,$param='')	
	{
		$arr=array();
		$name="";
		if($category_id>0)
		{
			$qry  			= 	"select * from  master_category where category_id='$category_id' order by category_name ASC";
			$row 			= 	$this->db->get_row($qry,ARRAY_A);
			$parent_id		=	$row['parent_id'];
			$name			=	"<a href=\"".makeLink(array("mod"=>"accessory", "pg"=>"accessory"),"act=settingsAdd".$param."&cat_id=".$row['category_id']."")."&limit=$limit\"  class='titleLink'>".$row['category_name']."</a>";
			while($parent_id>0)
			{
				$qry 		= 	"select * from master_category where category_id='$parent_id' order by category_name ASC";
				$row3		=	$this->db->get_row($qry,ARRAY_A);
				$name		=	"<a href=\"".makeLink(array("mod"=>"accessory", "pg"=>"accessory"),"act=settingsAdd".$param."&cat_id=".$row3['category_id']."")."&limit=$limit\"  class='titleLink'>".$row3['category_name']."</a>"."  &raquo;  ".$name;
				$parent_id	=	$row3['parent_id'];
			}
			$name			=	"<a href=\"".makeLink(array("mod"=>"accessory", "pg"=>"accessory"),"act=settingsAdd".$param."")."\"  class='titleLink'>Top</a> &raquo; ".$name;
		}
		return $name;
	}

	
	
	##########################################################################################
function	getModuleTreeParentFirst($parent,$s_id) {
	$qry	=	"	SELECT id,name  FROM 
							module";
							$rs = $this->db->get_results($qry); 
							return $rs;
							}
	
function getCategoryTreeParentFirst($parent,$s_id) {
		$catParent	=	0;
		$catChild	=	0;
		if($parent=="0" || $parent=="")
		{ $positionStr="";}
		else{ $positionStr	=	"";}
		if($parent=="0" || $parent=="")
		{ 
		$qry	=	"	SELECT DISTINCT mc.* FROM 
							master_category mc,
							module m,
							category_modules cm
			 			WHERE 
			 		mc.parent_id = '0' and 
					mc.is_in_ui !='Y' and 
					mc.active='y' and
					mc.category_id=cm.category_id and
					cm.module_id=m.id and
					m.folder='product' and 
					m.active='Y'";
			 
			
		}
		else
		{
			$qry	=	"	SELECT DISTINCT mc.* FROM 
							master_category mc,
							module m,
							category_modules cm
			 			WHERE 
			 		mc.parent_id = '$parent' and 
					mc.is_in_ui !='Y' and 
					mc.active='y' and
					mc.category_id=cm.category_id and
					cm.module_id=m.id and
					m.folder='product' and 
					m.active='Y'";
					
			
		}
		
		$rs = $this->db->get_results($qry); 
		if($rs) {
			if($s_id)
			{
				foreach ($rs as $row) {
						if($this->checkCategoryInStore($row->category_id,$s_id))
						{
						$arr['category_id'][] 	= $row->category_id;
						$arr['category_name'][] = $positionStr.ucwords($row->category_name);}
					}
			}else
			{
			foreach ($rs as $row) {
				$arr['category_id'][] 	= $row->category_id;
				$arr['category_name'][] = $positionStr.ucwords($row->category_name);
				}
			}
		} 
		else 
		{ 
			$arr['category_id'][] 	= "";
			$arr['category_name'][] = "&nbsp;&nbsp;&nbsp;&nbsp; --- No Sub category ---"; 
		}
		return $arr;
	}
	
	# for displaying the parent categories first
	function getCategoryTreeParentLevel($parent) {
		$catParent	=	0;
		$catChild	=	0;
		if($parent=="0" || $parent=="")
		{ $positionStr="";}
		else{ $positionStr	=	"";}
		if($parent=="0" || $parent=="")
		{ 
			$rs = $this->db->get_results("SELECT * FROM master_category WHERE parent_id = '0' and is_in_ui !='Y' and active='y' "); 
		}
		else
		{
			$rs 	= $this->db->get_results("SELECT * FROM master_category WHERE parent_id = '$parent' and is_in_ui !='Y' and active='y' ");
		}
		if($rs) {
			foreach ($rs as $row) {
				$arr['category_id'][] 	= $row->category_id;
				$arr['category_name'][] = $positionStr.ucwords($row->category_name);
				}
		} 
		else 
		{ 
			$arr['category_id'][] 	= "";
			$arr['category_name'][] = "&nbsp;&nbsp;&nbsp;&nbsp; --- No Sub category ---"; 
		}
		return $arr;
	}
	# for displaying the parent categories first
	
	
	
	
	function categoryMassUpdate(&$req,$file,$tmpname,$s_id="")
	{
		$array= array();
		extract($req);
		if(count($category_id)>0)
		{
		
			if(trim($append))
			{
				if(trim($active))
					{
						$newarray=array("active"=>"y");
						$array=array_merge($array,$newarray);
					}
					
					if(trim($is_in_ui))
					{
						$newarray=array("is_in_ui"=>"Y");
						$array=array_merge($array,$newarray);
					}
					
					if(trim($mandatory))
					{
						$newarray=array("mandatory"=>"Y");
						$array=array_merge($array,$newarray);
					}
					
					if(trim($customization_text_required))
					{
						$newarray=array("customization_text_required"=>"Y");
						$array=array_merge($array,$newarray);
					}
					
					if(trim($additional_customization_request))
					{
						$newarray=array("additional_customization_request"=>"Y");
						$array=array_merge($array,$newarray);
					}
					
					if(trim($is_monogram))
					{
						$newarray=array("is_monogram"=>"Y");
						$array=array_merge($array,$newarray);
					}
					//
					if(trim($store_owner_manage))
					{
						$newarray=array("store_owner_manage"=>"Y");
						$array=array_merge($array,$newarray);
					}
					if(trim($accessory_category))
					{
						$newarray=array("accessory_category"=>"Y");
						$array=array_merge($array,$newarray);
					}
					if(trim($gender))
					{
						$newarray=array("gender"=>$gender);
						$array=array_merge($array,$newarray);
					}
					if(trim($firstname))
					{
						$newarray=array("firstname"=>$firstname);
						$array=array_merge($array,$newarray);
					}
					if(trim($nickname))
					{
						$newarray=array("nickname"=>$nickname);
						$array=array_merge($array,$newarray);
					}
					if(trim($sentiment_line_2))
					{
						$newarray=array("sentiment_line_2"=>$sentiment_line_2);
						$array=array_merge($array,$newarray);
					}
					if(trim($sentiment_line_1))
					{
						$newarray=array("sentiment_line_1"=>$sentiment_line_1);
						$array=array_merge($array,$newarray);
					}
					if(trim($base_price))
					{
						$newarray=array("base_price"=>$base_price);
						$array=array_merge($array,$newarray);
					}
					   
  
  
  

					
			}
			else
			{
					if(trim($active))
					{
						$newarray=array("active"=>"y");
						$array=array_merge($array,$newarray);
					}
					else
					{
						$newarray=array("active"=>"n");
						$array=array_merge($array,$newarray);
					}
					
					if(trim($is_in_ui))
					{
						$newarray=array("is_in_ui"=>"Y");
						$array=array_merge($array,$newarray);
					}
					else
					{
						$newarray=array("is_in_ui"=>"N");
						$array=array_merge($array,$newarray);
					}
					if(trim($mandatory))
					{
						$newarray=array("mandatory"=>"Y");
						$array=array_merge($array,$newarray);
					}
					else
					{
						$newarray=array("mandatory"=>"N");
						$array=array_merge($array,$newarray);
					}
					if(trim($customization_text_required))
					{
						$newarray=array("customization_text_required"=>"Y");
						$array=array_merge($array,$newarray);
					}
					else
					{
						$newarray=array("customization_text_required"=>"N");
						$array=array_merge($array,$newarray);
					}
					if(trim($additional_customization_request))
					{
						$newarray=array("additional_customization_request"=>"Y");
						$array=array_merge($array,$newarray);
					}
					else
					{
						$newarray=array("additional_customization_request"=>"N");
						$array=array_merge($array,$newarray);
					}
					if(trim($is_monogram))
					{
						$newarray=array("is_monogram"=>"Y");
						$array=array_merge($array,$newarray);
					}
					else
					{
						$newarray=array("is_monogram"=>"N");
						$array=array_merge($array,$newarray);
					}
					//
					if(trim($store_owner_manage))
					{
						$newarray=array("store_owner_manage"=>"Y");
						$array=array_merge($array,$newarray);
					}else{
						$newarray=array("store_owner_manage"=>"N");
						$array=array_merge($array,$newarray);
					}
					
					if(trim($accessory_category))
					{
						$newarray=array("accessory_category"=>"Y");
						$array=array_merge($array,$newarray);
					}else{
					$newarray=array("accessory_category"=>"N");
						$array=array_merge($array,$newarray);
					}
					if(trim($gender))
					{
						$newarray=array("gender"=>$gender);
						$array=array_merge($array,$newarray);
					}
					if(trim($firstname))
					{
						$newarray=array("firstname"=>$firstname);
						$array=array_merge($array,$newarray);
					}else
					{
					$newarray=array("firstname"=>"");
						$array=array_merge($array,$newarray);
					}
					
					if(trim($nickname))
					{
						$newarray=array("nickname"=>$nickname);
						$array=array_merge($array,$newarray);
					}else
					{
					$newarray=array("nickname"=>"");
						$array=array_merge($array,$newarray);
					}
					if(trim($sentiment_line_2))
					{
						$newarray=array("sentiment_line_2"=>$sentiment_line_2);
						$array=array_merge($array,$newarray);
					}else
					{
					$newarray=array("sentiment_line_2"=>"");
						$array=array_merge($array,$newarray);
					}
					if(trim($sentiment_line_1))
					{
						$newarray=array("sentiment_line_1"=>$sentiment_line_1);
						$array=array_merge($array,$newarray);
					}else
					{
						$newarray=array("sentiment_line_1"=>"");
						$array=array_merge($array,$newarray);
					}
					if(trim($base_price))
					{
						$newarray=array("base_price"=>$base_price);
						$array=array_merge($array,$newarray);
					}else
					{
						$newarray=array("base_price"=>"");
						$array=array_merge($array,$newarray);
					}
					
			}
			foreach ($category_id as $cat_id)
			{
			
				
				$this->db->update("master_category", $array, "category_id='$cat_id'");
					
					if($store_owner_manage)
						{
							$this->storeCategoryManage($cat_id,$s_id,'Y');
							$this->UpdateParentCategoryStore($cat_id,$s_id,'Y');
						}
						else
						{
							$this->storeCategoryManage($cat_id,$s_id,'');
						
						}
				
				if($is_update_module=="Y")
				{
					if(!trim($append))
					{
						$this->removeCategoryToModule($cat_id);
					}
					if(count($req['module'])>0)
					{
						foreach ($req['module'] as $id)
						{
							$this->AssignCategoryToModule($cat_id, $id);
						}
					}
				}
			}//foreach ($category_id as $cat_id)
		}//if(count($category_id)>1)
		
	}//function categoryMassUpdate(&$req,$file,$tmpname)
	function getAllCategory_is_in_ui()
	{
		$qry		=	"SELECT * FROM master_category WHERE is_in_ui = 'Y' and active='y' order by category_name ASC";
		$rs['category_id'] 		= 	$this->db->get_col($qry, 0);
		$rs['category_name'] 	= 	$this->db->get_col($qry, 2);
		return $rs;
	}
	function getAllCategory_is_in_ui_normally()
	{
		$rs = $this->db->get_results("SELECT ms. *
										FROM master_category ms
									   WHERE ms.is_in_ui = 'Y'
									     AND ms.active = 'y'
									ORDER BY ms.category_name", ARRAY_A);
		return $rs;
	}

function getCategoryByProduct($product_id=0) {
	if($product_id>0) {
			$qry5  			= 	"select category_id from  category_product where product_id='$product_id'";
			$row5 			= 	$this->db->get_row($qry5,ARRAY_A);
			$category_id	=	$row5['category_id'];
			return $category_id;
   } else {
			return 0;
   }
}

//user side functions function getCompletePath($category_id)
function getCategoryPath($category_id=0,$product_id=0)	
	{
		global $store_id;

		$arr=array();
		$name="";
		if($category_id==0 && $product_id>0)
			{
			$qry5  			= 	"select category_id from  category_product where product_id='$product_id'";
			$row5 			= 	$this->db->get_row($qry5,ARRAY_A);
			$category_id	=	$row5['category_id'];
			}
		if(empty($category_id))
				$category_id=0;
		if($category_id>0)
		{
			$qry  			= 	"select * from  master_category where category_id='$category_id' order by category_name ASC";
			$row 			= 	$this->db->get_row($qry,ARRAY_A);
			$parent_id		=	$row['parent_id'];
			if ($this->config["accessory_in_list"]!="N")
			{
				$name			=	"<a href=\"".makeLink(array("mod"=>"product", "pg"=>"list"),"act=list&cat_id=".$row['category_id']."")."\"  class='whiteboltext'>".$row['category_name']."</a>";
			}
			else 
			{
				$name			=	"<a href=\"".makeLink(array("mod"=>"product", "pg"=>"list"),"act=list&add_accessory=N&cat_id=".$row['category_id']."")."\"  class='whiteboltext'>".$row['category_name']."</a>";
			}
			while($parent_id>0)
			{
				$qry 		= 	"select * from master_category where category_id='$parent_id' order by category_name ASC";
				$row3		=	$this->db->get_row($qry,ARRAY_A);
				if ($this->config["accessory_in_list"]!="N")
				{
					$name		=	"<a href=\"".makeLink(array("mod"=>"product", "pg"=>"list"),"act=list&cat_id=".$row3['category_id']."")."\"  class='whiteboltext'>".$row3['category_name']."</a>"."  &raquo;  ".$name;
				}
				else 
				{
					$name		=	"<a href=\"".makeLink(array("mod"=>"product", "pg"=>"list"),"act=list&add_accessory=N&cat_id=".$row3['category_id']."")."\"  class='whiteboltext'>".$row3['category_name']."</a>"."  &raquo;  ".$name;
				}
				
				$parent_id	=	$row3['parent_id'];
			}

			include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
			$objStore 		= 	new Store();
			
			if ($store_id) {
				if(stristr($_SERVER['HTTP_HOST'].''.$_SERVER['PHP_SELF'], 'thepersonalizedgift'))
					$url = SITE_URL . '/' .  $_REQUEST['storename'];
				else{
						$STORE_DETAILS	=	$objStore->storeGet($store_id);
						$url = $STORE_DETAILS['redirect_url'];
					}
			} else {
					$url = SITE_URL;
			}
			$name			=	"<a href=\"".$url."\")  class='whiteboltext'>Home</a> &raquo; ".$name;
		}
		return $name;
	}
function getCategoryPathAdmin($array,$category_id=0,$limit)	
	{
		$arr=array();
		$name="";
		if($category_id>0)
		{
			$qry  			= 	"select * from  master_category where category_id='$category_id' order by category_name ASC";
			$row 			= 	$this->db->get_row($qry,ARRAY_A);
			$parent_id		=	$row['parent_id'];
			$name			=	"<a href=\"".makeLink(array("mod"=>$array[0], "pg"=>$array[1]),"act=list&category_id=".$row['category_id']."").$array[2]."&limit=$limit\"  class='titleLink'>".$row['category_name']."</a>";
			while($parent_id>0)
			{
				$qry 		= 	"select * from master_category where category_id='$parent_id' order by category_name ASC";
				$row3		=	$this->db->get_row($qry,ARRAY_A);
				$name		=	"<a href=\"".makeLink(array("mod"=>$array[0], "pg"=>$array[1]),"act=list&category_id=".$row3['category_id']."").$array[2]."&limit=$limit\"  class='titleLink'>".$row3['category_name']."</a>"."  &raquo;  ".$name;
				$parent_id	=	$row3['parent_id'];
			}
			$name			=	"<a href=\"".makeLink(array("mod"=>$array[0], "pg"=>$array[1]),"act=list").$array[2]."\"  class='titleLink'>Top</a> &raquo; ".$name;
		}
		return $name;
	}
	
	
	
	
	
function getAccessCategoryPath($category_id=0,$accessory_id=0)	
	{
		$arr=array();
		$name="";
		if($category_id==0 && $accessory_id>0)
			{
			$qry5  			= 	"select category_id from  category_accessory where accessory_id='$accessory_id'";
			$row5 			= 	$this->db->get_row($qry5,ARRAY_A);
			$category_id	=	$row5['category_id'];
			}
		if(empty($category_id))
				$category_id=0;
		if($category_id>0)
		{
			$qry  			= 	"select * from  master_category where category_id='$category_id' order by category_name ASC";
			$row 			= 	$this->db->get_row($qry,ARRAY_A);
			$parent_id		=	$row['parent_id'];
			$name			=	"<a href=\"".makeLink(array("mod"=>"product", "pg"=>"list"),"act=list&cat_id=".$row['category_id']."")."\"  class='whiteboltext'>".$row['category_name']."</a>";
			while($parent_id>0)
			{
				$qry 		= 	"select * from master_category where category_id='$parent_id' order by category_name ASC";
				$row3		=	$this->db->get_row($qry,ARRAY_A);
				$name		=	"<a href=\"".makeLink(array("mod"=>"product", "pg"=>"list"),"act=list&cat_id=".$row3['category_id']."")."\"  class='whiteboltext'>".$row3['category_name']."</a>"."  &raquo;  ".$name;
				$parent_id	=	$row3['parent_id'];
			}
			$name			=	"<a href=\"".SITE_URL."/\")  class='whiteboltext'>Home</a> &raquo; ".$name;
		}
		return $name;
	}
function GetAllCategoryoftheStore(&$arr, $storename	=	'',$parent_id = 0, $level = 0)
	{
	$qry	=	"	SELECT 	mc.* 
					FROM 	";
	if($storename)
	$qry	.=" 			store_category		AS	sc,
							store				AS 	s,";
	
	$qry	.=" 			master_category 	AS 	mc
							
					WHERE ";	
	if($storename)
	$qry	.=" 			sc.category_id=mc.category_id	AND
							sc.store_id=s.id 				AND
							s.name='$storename'				AND ";
	
	$qry	.=" 			mc.parent_id = '$parent_id'  	AND 
							mc.is_in_ui<>'Y' 				AND
							mc.active='y'
							";
	//echo $qry;
	$rs = $this->db->get_results($qry);
		if($rs) {
			foreach ($rs as $row) {
				$arr['category_id'][] 	= $row->category_id;
				$arr['category_name'][] = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level)."-".ucwords($row->category_name);
				$this->GetAllCategoryoftheStore($arr, $storename,$row->category_id, $level+1);
			}
		} else {
			return ;
		}
		
		
		
	}
//Find out All Categories of the current store.
//category should be active
//category should be No for show_in_ui
function GetAllProductCategories($storename)
	{
	
	
	}
function GetCategoryGroupName($category_id)
	{
	$category_name=$this->getCategoryname($category_id);
	$qry	=	"select * from product_accessory_group as pag,product_accessory_group_categories as pagc where pag.parameter='Y' and pagc.group_id=pag.id and pagc.category_id=".$category_id;
	$rs = $this->db->get_row($qry,ARRAY_A);
	if(count($rs)>0)
		$category_name=$rs['group_name'];
	return $category_name;
	}
	function GetAccessoryName($acc_id)
	{
	
	$qry	=	"select m.category_name, m.category_id from master_category m inner join category_accessory ca on  ca.category_id=m.category_id and ca.accessory_id=".$acc_id;
	$rs = $this->db->get_row($qry,ARRAY_A);
	
			return $this->CategoryGetName($this->GetTopLevelCategory($rs['category_id']));
		
	//return $rs['category_name'];
	
	}
	
	function GetAccessoryName2($acc_id)
	{
	
	$qry	=	"select m.category_name, m.category_id from master_category m inner join category_accessory ca on  ca.category_id=m.category_id and ca.accessory_id=".$acc_id;
	$rs = $this->db->get_row($qry,ARRAY_A);
	
			//return $this->CategoryGetName($this->GetTopLevelCategory($rs['category_id']));
		
	return $rs['category_name'];
	
	}
	
function CheckCategoryInGroup($category_id)
	{
	$qry	=	"select * from product_accessory_group as pag,product_accessory_group_categories as pagc where pag.parameter='Y' and pagc.group_id=pag.id and pagc.category_id=".$category_id;
	$rs = $this->db->get_row($qry,ARRAY_A);
	if(count($rs)>0)
		return true;
	else
		return false;
	}
function GetCategoryInGroupID($category_id)
	{
	$qry	=	"select pag.id from product_accessory_group as pag,product_accessory_group_categories as pagc where pag.parameter='Y' and pagc.group_id=pag.id and pagc.category_id=".$category_id;
	$rs 	= 	$this->db->get_row($qry,ARRAY_A);
	return $rs['id'];
	}
function GetProductCategory($product_id)
	{
	$arr=array();
	$qry="select DISTINCT category_id from product_availabe_accessory where product_id=".$product_id;
	$rs = $this->db->get_results($qry);
	if(count($rs)>0)
		{
		$i=0;
		foreach ($rs as $row)
			{
			$category_id=$row->category_id;
			$cat	=	$this->CategoryGet($row->category_id);
			if($cat['parent_id']>0)
				$parent	=	$this->CategoryGet($cat['parent_id']);
			if($cat['is_monogram']=='Y' && $parent['is_monogram']=='Y')
			$category_id=$cat['parent_id'];
			
			if($this->CheckCategoryInGroup($category_id))
				{
				$group_id=$this->GetCategoryInGroupID($category_id);
				$arr[$i]=array('group_id'=>$group_id,"category_id"=>$category_id);
				$i++;
				}
			}
		}
	return $arr;
	}	
	
	
	function getSubcategories($category_id=0, $pageNo=0, $limit = 9, $params='', $output=OBJECT,$orderBy='display_order', $pageVarName,$s_id="")
	{	
	$arr=array();
			if(!$s_id)
			{
			 	 $qry5  			= 	"select * from  master_category where parent_id='$category_id' and is_in_ui<>'Y' and active='y' and is_private='N'";
				
				
				// $qry5  			= 	"select * from  category_accessory where category_id ='$category_id'";
			 }
			 else
			 {
			 	 $qry5  			= 	"select m.* from  master_category m , store_category s where s.category_id=m.category_id and s.store_id='$s_id' and m.parent_id='$category_id' and m.is_in_ui<>'Y' and m.active='y' ";
				
			 }
			 
			 //echo $qry5;
			 $row5 			= 	$this->db->get_results_pagewise($qry5, $pageNo, $limit, $params, ARRAY_A,$orderBy,'1',$pageVarName);;
			
			//$category_name	=	$row5['category_name'];
			// return $category_name;
	       	return $row5;
	}	
	
	
	function getDiscription($category_id)
	{
	$arr=array();
	
			
		  $qry5 ="select html_desc from  master_category where category_id='$category_id'";
		  $res=mysql_query($qry5);
		  while($row=mysql_fetch_array($res)){
		  $discription	=	$row['html_desc'];
		   return $discription;
		   }
			//$row5 			= 	$this->db->get_row($qry5,ARRAY_A);
			//$category_name	=	$row5['category_name'];
			
	
	}	//
	function getCatId($category_id)
	{
	$arr=array();
			
			  $qry5  			= 	"select category_id from  master_category where parent_id='$category_id'";
			
			  $row5 			= 	$this->db->get_results($qry5,ARRAY_A);
			 
			//$category_name	=	$row5['category_name'];
			// return $category_name;
	       	return $row5;
	}
	function getCategoryId($name)	
	{
	
	$qry = "SELECT * FROM master_category WHERE category_name = '$name'";
	$rs = $this->db->get_results($qry);
	return $rs;
	}
	function getSubCat(&$arr,$cat_id,$level = 0,$base_cat='',$act='',$store_id='',$disorder=0)
	{		
	//if($act!='product_list'){
		//$act='list';
	//}
	if(!$store_id){
	//$sql	=	"SELECT a.*,b.cms_type FROM cms_link a,store_cms b WHERE a.id=b.cms_id AND (b.cms_type='L' OR b.cms_type = 'C') AND a.area='store_left' AND b.store_id='0' ORDER BY a.position";

		$qry = "SELECT * FROM master_category WHERE parent_id = '$cat_id' AND is_private='N'";	
	}else{	
	
		$qry		=	"SELECT `master_category`.* FROM `store_category` 
						Inner Join `master_category` ON `master_category`.`category_id` = `store_category`.`category_id` 
						WHERE `store_category`.`store_id` =  '$store_id' AND `master_category`.parent_id='$cat_id'" ;
	}	
		$rs = $this->db->get_results($qry);
		if($rs) {
			foreach ($rs as $row) {
				$arr .= str_repeat("    ", $level)."[null, '".addslashes(ucwords($row->category_name))."', '".makeLink(array("mod"=>"product", "pg"=>"list"), "act=".$act."&base_cat=".$base_cat."&disorder=$disorder&cat_id=".$row->category_id)."', null, '',";
				$this->getSubCat($arr,$row->category_id, $level+1,$base_cat,$act,$store_id);
			}
			$arr = substr($arr, 0, -1)."],";
		} else {
			$arr = substr($arr, 0, -1)."],";
			return ;
		}

	}
function GetChildMonogramCategories($category_id)
	{
	$qry	=	"select * from master_category where parent_id=".$category_id." and active='y' and is_monogram='Y'";
	$rs = $this->db->get_results($qry);
	if($rs)
		return array('status'=>true,'data'=>$rs);
	else
		return array('status'=>false);
		
	}
    function getProducts($category_id)	
    {
		$check_table = "SELECT * FROM module where folder = 'product' AND active = 'Y'";
		$check_rs = $this->db->get_results($check_table);
		if(count($check_rs)>0)
		{
	
		/*
			$qry = "SELECT a.*,b.* 
					FROM category_product a,products b 
					WHERE  a.category_id = '$category_id'
					AND    a.product_id = b.id
					ORDER BY b.name";
					//echo $qry;*/
					
			$qry = "select b.name, d.name as storename, b.price, b.active,b.name from category_product a
			inner join products b on a.product_id = b.id
			inner join product_store c on b.id = c.product_id
			inner join store d on c.store_id = d.id 
			where a.category_id = '$category_id'	";



			$rs = $this->db->get_results($qry);
			if(count($rs)>0)
			{
				foreach($rs as $key=>$v)
				{
				$brand_id = $v->brand_id;
				$product_id = $v->id;
				$qry_brand = "SELECT * FROM brands where brand_id = '$brand_id'";
				$brand = $this->db->get_results($qry_brand);
				$rs[$key]->brand_name = $brand[0]->brand_name;				
				//made
				$qry_made = "SELECT m.*,n.* FROM product_made_in m,product_zone n WHERE m.product_id = '$product_id' AND m.zone_id = n.id";
				$made = $this->db->get_results($qry_made);
				$rs[$key]->Made = $made[0]->name;
		
				}
			}

		}
		else
		{
			$rs = array();
		}

		//echo "<pre>";
	    //print_r($rs);

	
	return $rs;
	}
    function getAccessories($category_id)	
    {
		$check_table = "SELECT * FROM module where folder = 'accessory' AND active = 'Y'";
		$check_rs = $this->db->get_results($check_table);
		if(count($check_rs)>0)
		{
		/*$qry = "SELECT a.*,b.*
					FROM category_accessory a,product_accessories b 
					WHERE  a.category_id = '$category_id'
					AND    a.accessory_id = b.id
					ORDER BY b.name";*/
					//echo $qry;
					
			$qry = "select b.id, b.name, b.adjust_price, b.active from category_accessory a
			inner join product_accessories b on a.accessory_id = b.id
			inner join store_accessory c on b.id = c.accessory_id
			inner join store d on c.store_id = d.id
			where a.category_id = '$category_id'
			group by b.id";

			$rs = $this->db->get_results($qry);
		}
		else
		{
			$rs = array();
		}
		return $rs;
	}
	function getPath($category_id=0,$product_id=0,$base_cat)
	{
		$name="";
		if($category_id==0 && $product_id>0)
			{
			$qry5  			= 	"select category_id from  category_product where product_id='$product_id'";
			$row5 			= 	$this->db->get_row($qry5,ARRAY_A);
			$category_id	=	$row5['category_id'];
			}
		if(empty($category_id))
				$category_id=0;
		if($category_id>0)
		{
			$qry  			= 	"select * from  master_category where category_id='$category_id' order by category_name ASC";
			$row 			= 	$this->db->get_row($qry,ARRAY_A);
			$parent_id		=	$row['parent_id'];
			if ($this->config["accessory_in_list"]!="N")
			{
				$name			=	"<a href=\"".makeLink(array("mod"=>"product", "pg"=>"list"),"act=list&cat_id=".$row['category_id']."")."\"  class='whiteboltext'>".$row['category_name']."</a>";
			}
			else 
			{
				$name			=	"<a href=\"".makeLink(array("mod"=>"product", "pg"=>"list"),"act=list&add_accessory=N&cat_id=".$row['category_id']."")."\"  class='whiteboltext'>".$row['category_name']."</a>";
				
			}
			
			
			if($category_id!=$base_cat)
			{
				while($parent_id>0)
				{
				    
					if($parent_id==$base_cat)
					{
					$qry 		= 	"select * from master_category where category_id='$parent_id' order by category_name ASC";
					$row3		=	$this->db->get_row($qry,ARRAY_A);
					if ($this->config["accessory_in_list"]!="N")
					{
						$name		=	"<a href=\"".makeLink(array("mod"=>"product", "pg"=>"list"),"act=list&cat_id=".$row3['category_id']."")."\"  class='whiteboltext'>".$row3['category_name']."</a>"."  &raquo;  ".$name;
					}
					else 
					{
						$name		=	"<a href=\"".makeLink(array("mod"=>"product", "pg"=>"list"),"act=list&add_accessory=N&cat_id=".$row3['category_id']."")."\"  class='whiteboltext'>".$row3['category_name']."</a>"."  &raquo;  ".$name;
						
					}
					
					$parent_id = 0;
					}
					else
					{
					$qry 		= 	"select * from master_category where category_id='$parent_id' order by category_name ASC";
					$row3		=	$this->db->get_row($qry,ARRAY_A);
					if ($this->config["accessory_in_list"]!="N")
					{
						$name		=	"<a href=\"".makeLink(array("mod"=>"product", "pg"=>"list"),"act=list&cat_id=".$row3['category_id']."")."\"  class='whiteboltext'>".$row3['category_name']."</a>"."  &raquo;  ".$name;
					}
					else 
					{
						$name		=	"<a href=\"".makeLink(array("mod"=>"product", "pg"=>"list"),"act=list&add_accessory=N&cat_id=".$row3['category_id']."")."\"  class='whiteboltext'>".$row3['category_name']."</a>"."  &raquo;  ".$name;
						
					}
					
					$parent_id	=	$row3['parent_id'];

					}
				}
			}
			$name			=	"<a href=\"".SITE_URL."/\")  class='whiteboltext'>Home</a> &raquo; ".$name;
		}
		return $name;
	
	}
	
	function getCategoryPathTaking($category_id=0,$product_id=0)	
	{
		$arr=array();
		$name="";
		if($category_id==0 && $product_id>0)
			{
			$qry5  			= 	"select category_id from  category_product where product_id='$product_id'";
			$row5 			= 	$this->db->get_row($qry5,ARRAY_A);
			$category_id	=	$row5['category_id'];
			}
		if(empty($category_id))
				$category_id=0;
		if($category_id>0)
		{
			$qry  			= 	"select * from  master_category where category_id='$category_id' order by category_name ASC";
			$row 			= 	$this->db->get_row($qry,ARRAY_A);
			$parent_id		=	$row['parent_id'];			
				$name			=	"<a href=\"".makeLink(array("mod"=>"product", "pg"=>"list"),"act=product_list&cat_id=".$row['category_id']."")."\"  class='whiteboltext'>".$row['category_name']."</a>";
			
			while($parent_id>0)
			{
				$qry 		= 	"select * from master_category where category_id='$parent_id' order by category_name ASC";
				$row3		=	$this->db->get_row($qry,ARRAY_A);
				
					$name		=	"<a href=\"".makeLink(array("mod"=>"product", "pg"=>"list"),"act=product_list&cat_id=".$row3['category_id']."")."\"  class='whiteboltext'>".$row3['category_name']."</a>"."  &raquo;  ".$name;
				
				$parent_id	=	$row3['parent_id'];
			}
			$name			=	"<a href=\"".SITE_URL."/\")  class='whiteboltext'>Home</a> &raquo; ".$name;
		}
		
		return $name;
	}
	function getArtCategoryPath($category_id=0,$accessory_id=0)	
	{
		$arr=array();
		$name="";
		if($category_id==0 && $accessory_id>0)
			{
			$qry5  			= 	"select category_id from  category_accessory where accessory_id='$accessory_id'";
			$row5 			= 	$this->db->get_row($qry5,ARRAY_A);
			$category_id	=	$row5['category_id'];
			}
		if(empty($category_id))
				$category_id=0;
		if($category_id>0)
		{
			$qry  			= 	"select * from  master_category where category_id='$category_id' order by category_name ASC";
			$row 			= 	$this->db->get_row($qry,ARRAY_A);
			$parent_id		=	$row['parent_id'];
			
			
			$name			=	"<a href=\"".makeLink(array("mod"=>"product", "pg"=>"list"),"act=list_accessories&cat_id=".$row['category_id']."&disorder=3")."\"  class='whiteboltext'>".$row['category_name']."</a>";
			
			while($parent_id>0)
			{
				$qry 		= 	"select * from master_category where category_id='$parent_id' order by category_name ASC";
				$row3		=	$this->db->get_row($qry,ARRAY_A);
				
				$name		=	"<a href=\"".makeLink(array("mod"=>"product", "pg"=>"list"),"act=list_accessories&cat_id=".$row3['category_id']."&disorder=3")."\"  class='whiteboltext'>".$row3['category_name']."</a>"."  &raquo;  ".$name;
				
				$parent_id	=	$row3['parent_id'];
			}
			$name			=	"<a href=\"".SITE_URL."/\")  class='whiteboltext'>Home</a> &raquo; ".$name;
		}
		return $name;
	}
	
	

	/*function listModuleCategory($keysearch='N',$category_search='',$parent_id=0,$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy="1", $store_id="",$id)
      {
	$qry = "SELECT a.*,b.*
					FROM master_category a,category_modules b 
					WHERE  a.category_id = '$category_id'
					AND    a.accessory_id = b.id
					ORDER BY b.name";
	}*/
	function updateLevel($id,$level)
	{
	//@ Afsal
		$qry = "SELECT category_id FROM master_category WHERE parent_id=$id";
		$rs  = $this->db->get_results($qry,ARRAY_A);
		foreach($rs as $row)
		{
			$var = $level +1;
	 
			if($this->hasChild($row["category_id"]))
			{
			$this->updateLevel($row["category_id"],$var);
			}
			
			$this->db->query("UPDATE master_category SET level=$var WHERE category_id={$row["category_id"]}");
			
			
		}
	}
	function hasChild($id)
	{
	//@ Afsal
		$sql = "SELECT category_id FROM master_category WHERE parent_id=$id";
		$rs  = $this->db->get_row($sql);
		
		if(count($rs) >0)
			return true;
		else
			return false;
	}
	##### @ Afsal
	##### Created on 16-10-2007
	#### Return all child categries based on Parent categoryname passed as parameter
	
	function getChildCategories2 ($parent_name, $is_in_ui='N') {
		
		if($parent_name){
		
			$qry1	=   "SELECT category_id FROM master_category WHERE category_name='$parent_name'";
			$rs_id	=	$this->db->get_row($qry1);
			$qry2	=	"SELECT * FROM master_category WHERE parent_id ={$rs_id->category_id} AND is_in_ui='$is_in_ui'";
			$rs 	= 	$this->db->get_results($qry2);
			return $rs;
		}
	}
	
	##### @ Afsal
	##### Created on 16-10-2007
	#### Return all child categries based on Parent categoryname passed as parameter (For List box)

	function getChildCategoriesList($parent_name)
	{
		if($parent_name){
		
			$qry1	=   "SELECT category_id FROM master_category WHERE category_name='$parent_name' ORDER BY category_name ASC";
			$rs_id	=	$this->db->get_row($qry1);

			$qry2 = "Select category_id AS id,category_name AS cat_name from master_category
			         WHERE parent_id={$rs_id->category_id} ORDER BY category_name ASC";
					
			$rs['category_id'] 		= $this->db->get_col($qry2, 0);
        	$rs['category_name'] = $this->db->get_col($qry2, 1);
			
			return $rs;
			
		}
	}
	/**
           * This is to get the child level of an accessory by passing id
           * Author   : Salim	
           * Created  : 22/Nov/2007
           * Modified : 
           */

	function getChildCategoriesListById($parent_id)
	{
			
			$qry = "Select category_id,parent_id,category_name,category_display_name from master_category WHERE parent_id=$parent_id ORDER BY display_order,category_name";
			$rs 	= 	$this->db->get_results($qry);			
			return $rs;
			
	}
	function getChildCategoriesListById3($parent_id,$store_id)
	{
			if($store_id){
				$qry = "select m.category_id,m.parent_id,m.category_name,m.category_display_name  from master_category m
						left join category_accessory a on a.category_id=m.category_id 
						left join product_accessories pa on pa.id= a.accessory_id
						left join store_accessory sa on sa.accessory_id= pa.id
			 			where m. parent_id = '$parent_id' and sa.active ='Y' and sa.store_id='$store_id' group by a.category_id";
			}
			else{
				$qry = "select m.category_id,m.parent_id,m.category_name,m.category_display_name  from master_category m left join category_accessory a on a.category_id=m.category_id 
						left join product_accessories pa on pa.id= a.accessory_id
						where m. parent_id = '$parent_id' and pa.active ='Y' group by a.category_id";
			}		
			//$qry = "Select category_id,parent_id,category_name,category_display_name from master_category WHERE parent_id=$parent_id AND active='Y' ORDER BY display_order,category_name";
			$rs 	= 	$this->db->get_results($qry);			
			return $rs;
	}
	
	function getChildCategoriesListById2($parent_id,$store_id)
	{
	
			if($store_id){
			/* $qry = "Select a.category_id,a.parent_id,a.category_name from master_category
					 a inner join product_predefined b on a.category_id=b.category
					 inner join product_predefined_store c on b.id=c.product_id
					 WHERE a.parent_id=$parent_id and c.active='Y' and c.store_id='$store_id' group by a.category_id ORDER BY a.display_order,a.category_name";*/
			 
			   $qry = "Select a.category_id,a.parent_id,a.category_name from master_category	 a 
			  		 left join product_predefined b on a.category_id=b.category
					 left join product_predefined_store c on b.id=c.product_id 
					 WHERE a.parent_id=$parent_id  and (( c.active='Y' and c.store_id='$store_id') or (a.category_description !='' and  a.active='Y') ) group by a.category_id ORDER BY a.display_order,a.category_name";
					 
			 }
			 else
			 {
				/*	 $qry = "select a.category_id,a.parent_id,a.category_name from master_category a 
			 		 inner join product_predefined b on a.category_id=b.category
			 		 WHERE a.parent_id=$parent_id and b.productive='Y'  group by a.category_id ORDER BY a.display_order,a.category_name";*/
					 
				    $qry = "select a.category_id,a.parent_id,a.category_name from master_category a 
						  left join product_predefined b on a.category_id=b.category
			 			  WHERE a.parent_id=$parent_id and (( b.product_active='Y'  and b.store_id=0 ) or (a.category_description !='' and  a.active='Y') )
						  group by a.category_id ORDER BY a.display_order,a.category_name";
			}		 
					 
			$rs 	= 	$this->db->get_results($qry);			
			return $rs;
			
	}
	
	function getCategoryNameById($parent_id)
	{
			
			$qry = "Select category_name from master_category WHERE category_id = $parent_id";
			$rs 	= 	$this->db->get_results($qry,ARRAY_A);			
			return $rs;
			
	}
	
	/*
		These four functions are used in deletion of categories along with their sub categories
        Author   : Jeffy
        Created  : 20/May/2008
        Modified : 
	*/
	
	function categoryDeleteOne($category_id, $sId = 'category') {
		$message = "";
		if($this->checkForChildcategories($category_id))
		$message = "Sub Categories, ";
		elseif($this->checkCategoryExistence($category_id)){
			$message_intro = $this->checkCategoryExistence($category_id);
			$message = substr_replace($message_intro," ",-2);
		}else{
			$this->db->query("DELETE FROM master_category WHERE category_id='$category_id'");
			$sql        =   "select id,url from cms_link";
			$rs			= 	$this->db->get_results($sql,ARRAY_A);
			for($i=0;$i<count($rs);$i++){
			     $id    =   $rs[$i]['id'];
				 $sess	=	strstr($rs[$i]['url'], 'sess');
				 $sess	=	substr($sess,5);
				if($sess){
					$RequestArray	=	array();
					parse_str(base64_decode($sess), $RequestArray);
					$catid  = $RequestArray[cat_id];
					if($catid==$category_id){
					  $this->db->query("DELETE FROM cms_link WHERE id='$id'");
					}
				}else{
					//print "No Catid found<br>";
				}
			}
		}
		return $message;
	}
	
	function categoryDeleteTwo($category_id) {
		$this->categoryRelatedDelete($category_id);	
		$this->db->query("DELETE FROM master_category WHERE category_id='$category_id'");
			$sql        =   "select id,url from cms_link";
			$rs			= 	$this->db->get_results($sql,ARRAY_A);
			for($i=0;$i<count($rs);$i++){
			     $id    =   $rs[$i]['id'];
				 $sess	=	strstr($rs[$i]['url'], 'sess');
				 $sess	=	substr($sess,5);
				if($sess){
					$RequestArray	=	array();
					parse_str(base64_decode($sess), $RequestArray);
					$catid  = $RequestArray[cat_id];
					if($catid==$category_id){
					  $this->db->query("DELETE FROM cms_link WHERE id='$id'");
					}
				}else{
					//print "No Catid found<br>";
				}
			} 
	}
	
	function checkCategoryExistence($category_id){
		$qry = "SELECT b.* From category_modules a, module b WHERE a.category_id = '$category_id' and a.module_id = b.id";
		$rs = $this->db->get_results($qry,ARRAY_A);
		foreach ($rs as $key => $value){
			$qry2 = "SELECT * From custom_fields_table WHERE table_id = $value[category_relate_table_id]";
			$rs2 = $this->db->get_row($qry2,ARRAY_A);			
			$qry3 = "SELECT * From $rs2[table_name] WHERE $value[category_relate_field_id] = $category_id";
			$rs3 = $this->db->get_results($qry3,ARRAY_A);
			if($rs3 > 0){
				$rse .= $value[name].", ";
			}			
		}
		return $rse;
	}
	
	function categoryRelatedDelete($category_id){
		$qry = "SELECT b.* From category_modules a, module b WHERE a.category_id = '$category_id' and a.module_id = b.id";
		$rs = $this->db->get_row($qry,ARRAY_A);		
		foreach( $rs as $key => $value){
			$qry2 = "SELECT table_name FROM custom_fields_table WHERE table_id = '$rs[category_relate_table_id]'";
			$rs2 = $this->db->get_row($qry2,ARRAY_A);
			if($rs[categroy_relate_type] == "1tm"){
				$this->db->query("DELETE FROM $rs2[table_name] WHERE $rs[category_relate_field_id] = $category_id");
			}elseif($rs[categroy_relate_type] == "1t1"){
				$this->db->query("UPDATE $rs2[table_name] SET $rs[category_relate_field_id] = '' WHERE $rs[category_relate_field_id] = $category_id");
			}
		}
		$this->db->query("DELETE FROM category_modules WHERE category_id='$category_id'");
		return $rs;
	}
	
	#-------------------------- ends ----------------

	/**
  	 * This function retrieves all categories in a particular module
  	 * Author   : Retheesh
  	 * Created  : 05/Jun/2008
  	 * Modified : 05/Jun/2008 By Retheesh
  	 */
	function getCategoryByModule($mod,$combo=0)
	{
		if ($mod)
		{
			$sql = "select id from module where folder='$mod'";
			$rs  = $this->db->get_row($sql);
			$module_id = $rs->id;
			if ($module_id)
			{
				$sql = "select b.category_id,b.category_name from category_modules a inner join master_category b
				 on a.category_id=b.category_id where a.module_id=$module_id";
				if ($combo==1)
				{
					$rs1['category_id'] = $this->db->get_col($sql,0);
					$rs1['category_name'] = $this->db->get_col($sql,1);
				}
				else 
				{
					$rs1 = $this->db->get_results($sql,ARRAY_A);
				}	
				return $rs1;
			}	
		}	
	}
	function CategoryGetBySeoUrl($seo_url)
	{
		$sql 	=	"SELECT category_id FROM master_category WHERE seo_url = '$seo_url'";
		$rs1 	=   $this->db->get_row($sql,ARRAY_A);
		return $rs1['category_id'];
	}
	function getProductPredefined($id)
	{
		$qry	=	    "SELECT PP.*,MC.category_name FROM product_predefined PP 
				 		 LEFT JOIN master_category MC ON PP.category = MC.category_id
				 		 WHERE PP.id = '$id' 
						 ";
		$rs1 	= $this->db->get_row($qry,ARRAY_A);
		return $rs1;
	}


	//user side functions function getCompletePath($category_id)
function getCategoryPathOther($category_id=0,$product_id=0)	
	{
		global $store_id;

		$arr=array();
		$name="";
		if($category_id==0 && $product_id>0)
			{
			$qry5  			= 	"select category_id from  category_product where product_id='$product_id'";
			$row5 			= 	$this->db->get_row($qry5,ARRAY_A);
			$category_id	=	$row5['category_id'];
			}
		if(empty($category_id))
				$category_id=0;
		if($category_id>0)
		{
			$qry  			= 	"select * from  master_category where category_id='$category_id' order by category_name ASC";
			$row 			= 	$this->db->get_row($qry,ARRAY_A);
			$parent_id		=	$row['parent_id'];
			if ($this->config["accessory_in_list"]!="N")
			{
				$name			=	"<a href=\"".makeLink(array("mod"=>"product", "pg"=>"list"),"act=other_gifts&cat_id=".$row['category_id']."")."\"  class='whiteboltext'>".$row['category_name']."</a>";
			}
			else 
			{
				$name			=	"<a href=\"".makeLink(array("mod"=>"product", "pg"=>"list"),"act=other_gifts&add_accessory=N&cat_id=".$row['category_id']."")."\"  class='whiteboltext'>".$row['category_name']."</a>";
			}
			while($parent_id>0)
			{
				$qry 		= 	"select * from master_category where category_id='$parent_id' order by category_name ASC";
				$row3		=	$this->db->get_row($qry,ARRAY_A);
				if ($this->config["accessory_in_list"]!="N")
				{
					$name		=	"<a href=\"".makeLink(array("mod"=>"product", "pg"=>"list"),"act=other_gifts&cat_id=".$row3['category_id']."")."\"  class='whiteboltext'>".$row3['category_name']."</a>"."  &raquo;  ".$name;
				}
				else 
				{
					$name		=	"<a href=\"".makeLink(array("mod"=>"product", "pg"=>"list"),"act=other_gifts&add_accessory=N&cat_id=".$row3['category_id']."")."\"  class='whiteboltext'>".$row3['category_name']."</a>"."  &raquo;  ".$name;
				}
				
				$parent_id	=	$row3['parent_id'];
			}

			include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
			$objStore 		= 	new Store();
			
			if ($store_id) {
				if(stristr($_SERVER['HTTP_HOST'].''.$_SERVER['PHP_SELF'], 'thepersonalizedgift'))
					$url = SITE_URL . '/' .  $_REQUEST['storename'];
				else{
						$STORE_DETAILS	=	$objStore->storeGet($store_id);
						$url = $STORE_DETAILS['redirect_url'];
					}
			} else {
					$url = SITE_URL;
			}
			$name			=	"<a href=\"".$url."\")  class='whiteboltext'>Home</a> &raquo; ".$name;
		}
		return $name;
	}	
}

?>