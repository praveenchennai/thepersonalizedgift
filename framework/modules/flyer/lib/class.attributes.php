<?php


class Attributes extends FrameWork
{
		
	/**
	 * Constructor	
	 */
	function Attributes()
	{
		$this->FrameWork();
	}
	
	
	/**
	 * The following method returns all the custom attributes related flyers
	 *
	 */
	function listAllAttributes($keysearch='N',$forms_search='',$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy="1") {
		$qry		=	"select * from flyer_form_attribute_groups ";
			
		if($keysearch=='Y' && $forms_search)
			$qry	.=	" where group_name LIKE '%$forms_search%' ";
			$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
	function AttributeAddedit($req,$sId)
	{
		extract($req);
		unset($req['sess'],$req['sId'],$req['fId'],$req['mId'],$req['PHPSESSID'],$req['limit']);
		if(trim($active))
				$active="Y";
			else
				$active="N";
	
		if(!trim($group_name)) 
		{ 
			$message 				=	"$sId title is required";
			return $message;
			}
			else {
					$array 			= 	array("group_name"=>$group_name,"active"=>$active);
			}
			
			if($attribute_id) { 
					$this->db->update("flyer_form_attribute_groups", $array, "attr_group_id ='$attribute_id'");
			} else {			
				$this->db->insert("flyer_form_attribute_groups", $array);
				$attribute_id = $this->db->insert_id;
				$_SESSION['attribute_id'] = $attribute_id;
		   }
	return true;
	}
	
	function CheckboxAdd($req,$attribute_id)
	{
		extract($req);
		if(count($Value)>0)
		{
			foreach($Value as $option)
			{
				if($option!="")
				{
					$array 			= 	array("attr_group_id"=>$attribute_id,"item_name"=>$option,"active"=>"Y");
					$this->db->insert("flyer_form_attribute_item", $array);
				}
			}
		}
		return true;
	}
	
	function AttributeGet($attribute_id) {
		$rs = $this->db->get_row("SELECT * FROM flyer_form_attribute_groups WHERE attr_group_id ='$attribute_id'", ARRAY_A);
		return $rs;
	}
	
	function AttributeDelete($attr_id)
	{
		$qry	=	"delete from flyer_form_attribute_groups where attr_group_id='$attr_id'";
		$this->db->query($qry);
		return true;
	}
	
	function listAllAttributeItems($group_id='0',$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy="1")
	{
		$qry	=	"select * from flyer_form_attribute_item where attr_group_id='$group_id' and flyer_id='0'";
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
	function AttributeItemDelete($item_id)
	{
		$qry	=	"delete from flyer_form_attribute_item where item_id='$item_id'";
		$this->db->query($qry);
		return true;
	}
	
	function AttributeOptionedit($req,$sId)
	{
		extract($req);
		unset($req['sess'],$req['sId'],$req['fId'],$req['mId'],$req['PHPSESSID'],$req['limit']);
		$array 			= 	array("item_name"=>$name,"attr_group_id"=>$attribute_id);
		//$array['value_id'] 	= 	$item_id;
		$this->db->update("flyer_form_attribute_item", $array, "item_id='$item_id'");
			
		return true;
	}
		
	function AttributeOptionGet($id)
	{
		$rs = $this->db->get_row("SELECT * FROM flyer_form_attribute_item WHERE item_id='$id'", ARRAY_A);
		return $rs;
	}

}


?>