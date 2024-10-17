<?

class Type extends FrameWork
{

	function Type()
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
	function listAllOptions($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy)
	{
		$qry		=	"select * from options ";
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	function GetType($id=0) {
		if($id>0)
		{
			$rs = $this->db->get_row("SELECT * FROM options WHERE id='{$id}'", ARRAY_A);
			return $rs;
		}
	}
	function typeAddEdit(&$req,$file,$tmpname)
	{
		extract($req);
		if(!trim($name))
		{
			$message 				=	"Brand name is required";
		}
		else
		{
			$array 				= 	array(	"name"=>$name,
			"type"=>$type,
			"description"=>$description,
			"required"=>$required,
			);
			if($id)
			{
				$array['id'] 	= 	$id;
				$this->db->update("options", $array, "id='$id'");
			}
			else
			{
				$this->db->insert("options", $array);
				$id = $this->db->insert_id;
			}
			return true;
		}
		return $message;
	}
	function typeDelete($id=0) {
		if($id>0)
		{
			$this->db->query("DELETE FROM options WHERE id='$id'");
			return $message;
		}
	}

}

?>
