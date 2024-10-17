<?
//
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
class Forms extends FrameWork {

	function Features()
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
	
	function listAllForms($keysearch='N',$forms_search='',$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy="1")
	{	
			/*$qry		=	"select * from flyer_form_master ";
			if($keysearch=='Y' && $forms_search)
			$qry	.=	" where form_title LIKE '%$forms_search%' ";*/
			
			$qry		=	"select fm.*,mc.category_name as cat_name from flyer_form_master fm,master_category mc where fm.category_name=mc.category_id ";
			if($keysearch=='Y' && $forms_search)
			$qry	.=	" and form_title LIKE '%$forms_search%' ";
								
			$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
	function listAllBlocks($keysearch='N',$block_search='',$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy="1",$forms_id)
	{	
		$qry		=	"select * from flyer_form_blocks where form_id='$forms_id'";
		if($keysearch=='Y' && $block_search)
			$qry	.=	" and block_title LIKE '%$block_search%' ";
								
			$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
	function FormsGet($forms_id) {
		$rs = $this->db->get_row("SELECT * FROM flyer_form_master WHERE form_id='$forms_id'", ARRAY_A);
		return $rs;
	}
	
	function BlockGet($block_id) {
		$rs = $this->db->get_row("SELECT * FROM flyer_form_blocks WHERE block_id='$block_id'", ARRAY_A);
		return $rs;
	}
	
	function FormsAddedit($req,$sId)
	{
		extract($req);
		unset($req['sess'],$req['sId'],$req['fId'],$req['mId'],$req['PHPSESSID'],$req['limit']);
		//print_r($req);
		$published	=	"N";
		if(trim($active))
				$active="Y";
			else
				$active="N";
				
				
				if(trim($show_accomodation))
				$show_accomodation="Y";
			else
				$show_accomodation="N";
				
				if(trim($show_Qty))
				$show_Qty="Y";
			else
				$show_Qty="N";
				
				
		if(!trim($form_title)) 
		{ 
			$message 				=	"$sId title is required";
			return $message;
			}
			else {
					$array 			= 	array("form_title"=>$form_title,"category_name"=>$category_name,"published"=>$published,"active"=>$active,"show_accomodation"=>$show_accomodation,"show_Qty"=>$show_Qty);
			}
			if($forms_id) { 
				$array['form_id'] 	= 	$forms_id;
				$this->db->update("flyer_form_master", $array, "form_id='$forms_id'");
			} else {			
				$this->db->insert("flyer_form_master", $array);
				$feature_id = $this->db->insert_id;
		   }
	return true;
	}
	
	function BlockAddedit($req,$sId,$forms_id)
	{
		extract($req);
		unset($req['sess'],$req['sId'],$req['fId'],$req['mId'],$req['PHPSESSID'],$req['limit']);
		if(trim($active))
				$active="Y";
			else
				$active="N";
	
		if(!trim($block_title)) 
		{
			$message 				=	"$sId title is required";
			return $message;
			}
			else {
					$array 			= 	array("block_title"=>$block_title,"form_id"=>$forms_id,"active"=>$active,"display_order"=>$display_order, "block_position"=>$block_position);
			}
			
			if($block_id) { 
				$array['block_id'] 	= 	$block_id;
				$this->db->update("flyer_form_blocks", $array, "block_id='$block_id'");
			} else {		 	
				$this->db->insert("flyer_form_blocks", $array);
				$block_id = $this->db->insert_id;
				$_SESSION['block_id'] = $block_id;
		   }
	return true;
	}
	function form_rssAdd($featureId,$form_id)
	{
		$array 			= 	array("field_id"=>$featureId,"from_id"=>$form_id);
		$this->db->insert("flyer_rss_fields", $array);
		return true;
	}
	function form_rssDelete($form_id)
	{
		$qry	=	"delete from flyer_rss_fields where from_id='$form_id'";
		$this->db->query($qry);
		return true;
	}
	
	function formsDelete($forms_id)
	{
		$qry	=	"delete from flyer_form_master where form_id='$forms_id'";
		$this->db->query($qry);
		return true;
	}
	function blockDelete($block_id)
	{
		$qry	=	"delete from flyer_form_blocks where  block_id='$block_id'";
		$this->db->query($qry);
		$this->db->query("delete from flyer_map_form_option_groups where block_id='$block_id'");
		$this->db->query("delete from flyer_map_form_feature where block_id='$block_id'");
		return true;
	}
	function blockAttrDelete($block_id)
	{
		$this->db->query("delete from flyer_map_form_option_groups where block_id='$block_id'");
		return true;
	}
	function blockFesDelete($block_id)
	{
		$this->db->query("delete from flyer_map_form_feature where block_id='$block_id'");
		return true;
	}
	function mapAttributes($attributeId,$block_id)
	{
		$array 			= 	array("block_id"=>$block_id,"attr_group_id"=>$attributeId);
		$this->db->insert("flyer_map_form_option_groups", $array);
		return true;
	}
	
	function mapFeatures($featureId,$block_id)
	{
		$array 			= 	array("block_id"=>$block_id,"feature_id"=>$featureId);
		$this->db->insert("flyer_map_form_feature", $array);
		return true;
	}
	
	function GetAttributes()
	{
		$qry	=	"SELECT * FROM flyer_form_attribute_groups WHERE active='Y' ORDER BY group_name";
		$rs = $this->db->get_results($qry, ARRAY_A);
		return $rs;
	
	}
	function GetFeatures()
	{
		$qry	=	"SELECT * FROM flyer_form_features WHERE active='Y' and flyer_id='0' ORDER BY label";
		$rs = $this->db->get_results($qry, ARRAY_A);
		return $rs;
	
	}
	
	function GetSelectedRssFeatures($form_id)
	{
		$qry		=	"SELECT field_id FROM `flyer_rss_fields` fr
												WHERE 
												fr.from_id =  $form_id  ";
			$rs['form_id'] 	= 	$this->db->get_col($qry, 0);
			return $rs;
	}
	
	function GetAllSelectedFeatures($block_id=0)
	{
		if($block_id>0)
		{
			$qry		=	"SELECT feature_id FROM `flyer_map_form_feature` fmf
												WHERE 
												fmf.block_id =  $block_id  ";
			$rs['feature_id'] 	= 	$this->db->get_col($qry, 0);
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
	
	function GetAllSelectedAttributes($block_id=0)
	{
		if($block_id>0)
		{
			$qry		=	"SELECT attr_group_id FROM `flyer_map_form_option_groups` fmf
												WHERE 
												fmf.block_id =  $block_id ";
			$rs['attribute_id'] 	= 	$this->db->get_col($qry, 0);
			//$rs['accessory_id']=array(2,3,4,5);
			return $rs;
		}
		
	}
	function GetAllSecondLevelCategory()
	{
		$qry				=	"select * from master_category where level='0' and active='y'";
		$rs['cat_id'] 	= 	$this->db->get_col($qry, 0);
		$rs['cat_name'] 	= 	$this->db->get_col($qry, 2);
		return $rs;
	}
	
	
}

?>