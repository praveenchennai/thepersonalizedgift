<?

class Brand extends FrameWork
{

	function Brand()
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
	function listAllBrands($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy)
	{
		$qry		=	"select * from brands ";
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	function GetBrand($brand_id=0) {
		if($brand_id>0)
		{
			$rs = $this->db->get_row("SELECT * FROM brands WHERE brand_id='{$brand_id}'", ARRAY_A);
			return $rs;
		}
	}
	function GetBranName($brand_id=0)
		{
		if($brand_id>0)
			{
				$rs = $this->db->get_row("SELECT brand_name FROM brands WHERE brand_id='{$brand_id}'", ARRAY_A);
				return $rs['brand_name'];
			}
		}
	function brandAddEdit(&$req,$file,$tmpname,$sId = '')
	{
		extract($req);
		if ($file){
			$dir			=	SITE_PATH."/modules/product/images/brand/";
			$file1			=	$dir."thumb/";
			$resource_file	=	$dir.$file;
			$path_parts 	= 	pathinfo($file);

		}
		if(!trim($brand_name))
		{
			$message 				=	"$sId name is required";
		}
		else
		{
			if ($file)
			{
				$array 			= 	array("brand_name"=>$brand_name,"brand_description"=>$brand_description,"brand_logo"=>".".$path_parts['extension'],"company_name"=>$company_name);
			}
			else{
				$array 				= 	array("brand_name"=>$brand_name,"brand_description"=>$brand_description,"company_name"=>$company_name);
			}
			if($brand_id)
			{
				$array['brand_id'] 	= 	$brand_id;
				$this->db->update("brands", $array, "brand_id='$brand_id'");
			}
			else
			{
				$this->db->insert("brands", $array);
				$brand_id = $this->db->insert_id;
			}
			if ($file)
			{
				$save_filename	=	$brand_id.".".$path_parts['extension'];
				_upload($dir,$save_filename,$tmpname,1);
			}
			return true;
		}
		return $message;
	}
	function brandDelete($brand_id,$sId = '') {
		if($this->checkForPrduct($brand_id))
			{
			$arr=array("status"=>false,"message"=>"The $sId cannot delete simce it is assigned to some product");
			}
		else
			{
			$this->db->query("DELETE FROM brands WHERE brand_id='$brand_id'");
			$arr=array("status"=>true,"message"=>"The $sId is deleted successfully");
			}
		
		return $arr;
	}
	function checkForPrduct($brand_id)
	{
		$qry		=	"select count(*) as number from products where brand_id='$brand_id'";
		$row 		= 	$this->db->get_row($qry,ARRAY_A);
		if($row['number']>0)
		return true;
		else
		return false;

	}

	function brandMassUpdate(&$req)
	{
		extract($req);

		if(count($brand_id)>0)
		{

			$array = array();

			if(trim($company_name))
			{
				$newarray=array("company_name"=>$company_name);
				$array=array_merge($array,$newarray);
			}
			foreach ($brand_id as $brand_id)
			{
				$this->db->update("brands", $array, "brand_id='$brand_id'");

			}//foreach ($category_id as $cat_id)
			return true;
		}//if(count($category_id)>1)

	}

}

?>
