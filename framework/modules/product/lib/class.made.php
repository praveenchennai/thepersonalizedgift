<?

class Made extends FrameWork
{

	function Made()
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
	*	This function will return all the brand names.
	*	This function is used for listing brand names in admin side.
	*/
	
	function GetAllMade(){
	$qry		=	"select * from product_zone";
	$rs		= 	$this->db->get_row($qry,ARRAY_A);
	return $rs;
	}
	
	function listAllMades($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy)
	{
		$qry		=	"select * from product_zone";
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	function GetAllMades()
	{
		$qry				=	"select * from product_zone";
		$rs['id'] 	= 	$this->db->get_col($qry, 0);
		$rs['name'] 	= 	$this->db->get_col($qry, 1);
		return $rs;
	}
	function GetMade($id=0) {
		if($id>0)
		{
			$rs = $this->db->get_row("SELECT * FROM product_zone WHERE id='{$id}'", ARRAY_A);
			return $rs;
		}
	}
	function GetMadeName($id=0)
		{
		if($brand_id>0)
			{
				$rs = $this->db->get_row("SELECT name FROM product_zone WHERE id='{$id}'", ARRAY_A);
				return $rs['name'];
			}
		}
	function GetZoneIdOfProduct($product_id=0)
		{
		$output=0;
		if($product_id>0)
			{
			$rs 		= 	$this->db->get_row("SELECT zone_id FROM product_made_in WHERE product_id='$product_id'", ARRAY_A);
			if(count($rs)>0)
				$output		= 	$rs['zone_id'];
			}
		return $output;
		}
	function madeAddEdit(&$req,$file,$tmpname,$sId = 'Zone')
	{
		extract($req);
		if ($file){
			$dir			=	SITE_PATH."/modules/product/images/zone/";
			$file1			=	$dir."thumb/";
			$resource_file	=	$dir.$file;
			$path_parts 	= 	pathinfo($file);

		}
		if(!trim($name))
		{
			$message 				=	"$sId name is required";
		}
		else
		{
			if ($file)
			{
				$array 			= 	array("name"=>$name,"logo_extension"=>".".$path_parts['extension']);
			}
			else{
				$array 				= 	array("name"=>$name);
			}
			if($id)
			{
				$array['id'] 	= 	$id;
				$this->db->update("product_zone", $array, "id='$id'");
			}
			else
			{
				$this->db->insert("product_zone", $array);
				$id = $this->db->insert_id;
			}
			if ($file)
			{
				$save_filename	=	$id.".".$path_parts['extension'];
				_upload($dir,$save_filename,$tmpname,0);
			}
			return true;
		}
		return $message;
	}
	function madeDelete($id,$sId = 'Zone') {
		if($this->checkForPrduct($id))
			{
			$arr=array("status"=>false,"message"=>"The $sId cannot delete simce it is assigned to some product(s)");
			}
		else
			{
			$this->db->query("DELETE FROM product_zone WHERE id='$id'");
			$this->db->query("DELETE FROM product_made_in WHERE zone_id='$id'");
			$arr=array("status"=>true,"message"=>"The $sId is deleted successfully");
			}
		return $arr;
	}
	function checkForPrduct($made_id)
	{
		$qry		=	"select count(*) as number from product_made_in  where zone_id='$made_id'";
		$row 		= 	$this->db->get_row($qry,ARRAY_A);
		if($row['number']>0)
		return true;
		else
		return false;

	}
	
	//functions for USER side
	function GetMadeInForProduct($product_id=0)
		{
		if($product_id>0)
			{
			$qry		=	"SELECT pz.* FROM product_zone AS pz,product_made_in AS pm WHERE pz.id=pm.zone_id AND pm.product_id=".$product_id;
			$row 		= 	$this->db->get_row($qry,ARRAY_A);
			if(count($row)>0)
				return $row;
			else
				return false;
			}//if($product_id>0)
		else
			{
			return false;
			}
		} 
}

?>
