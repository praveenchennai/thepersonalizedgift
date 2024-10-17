<?

class Stock extends FrameWork
{

	function Stock()
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
	function listAllProductStock($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy)
	{
		$qry		=	"select p.*,ps.product_quantity as product_quantity,p.name as product_name from  products p LEFT Join(product_stock ps) ON (p.id=ps.product_id )";
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	function GetStock($product_id=0)
	{
		if($description_id>0)
		{
			$rs = $this->db->get_row("SELECT * FROM product_stock WHERE product_id='{$product_id}'", ARRAY_A);
			return $rs;
		}
	}
	function sockAddEdit(&$req)
	{
		extract($req);
		$this->deleteStock($product_id);
		if(trim($product_quantity))
		{
			if($product_id)
			{
				//echo "description_id: ".$description_id."product_quantity".$product_quantity;
				$array 		= 	array("product_quantity"=>$product_quantity,"product_id"=>$product_id);
				$this->db->insert("product_stock", $array);
			}
		}
		return true;
	}
	function deleteStock($product_id=0)
	{
		if($product_id>0)
		{
			$this->db->query("DELETE FROM product_stock WHERE product_id='$product_id'");
			return $message;
		}
	}

}

?>
