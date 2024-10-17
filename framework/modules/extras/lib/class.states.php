<?php
class States extends FrameWork {
  var $id;
  var $code;
  var $name;  
  var $country_id;
  var $tax;
function States($id=0) {
	if($id>0)
		{
		$rs 				=	 $this->db->get_row("SELECT * FROM state_code WHERE id='{$id}'", ARRAY_A);
		$this->id 			= 	$rs['id'];
		$this->code 		= 	$rs['code'];
		$this->name			=	$rs['name'];	
		$this->country_id	=	$rs['country_id'];
		$this->tax 			= 	$rs['tax'];
		}
	$this->FrameWork();
  }
	/**
	 * Listing states with pagination of the country
	 * @param <Page Number> $pageNo
	 * return array
	 */
	function listAllSates($country_id, $pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy){	
		$qry		=	"SELECT * FROM state_code ";
		if($country_id>0)
			$qry	.=	"  WHERE  country_id='$country_id'";
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	/**
	 * getting state based on given id
	 * @param <id> $id	
	 * return row as array	
	 */
	function stateGet ($id) {
		$rs 	=	 $this->db->get_row("SELECT * FROM state_code WHERE id='{$id}'", ARRAY_A);
		return $rs;
	}
	/**
	 * Deleting state_code based on given id
	 * @param <id> $id	
	 * return boolean Message
	 */
	function stateDelete ($id) {
		$true	=	$this->db->query("Delete from state_code WHERE id='$id'");
		if($true){
			return true;
		}
	}
	/**
	 * Add Edit Coupons
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message	 
	 */
	function stateAddEdit (&$req) {
		extract($req);						
		if(!trim($name)) {
			$message = "State Name is Required";
		} 
		else if(!trim($code))
			{
			$message = "State Code is Required";
			}
		else if($this->CheckForUniqueCode(strtoupper($code),$id))
			{
			$message = "This State Code '$code' is already used. Please try with another State Code";
			}
		else {				
			$array = array("code"=>strtoupper($code),"name"=>$name, "country_id"=>$country_id, "tax"=>$tax);
			
			if ( isset ($title) )
			$array["title"] = $title;
			
			if($id) {
				$array['id'] 	= 	$id;
				$this->db->update("state_code", $array, "id='$id'");
			} else {
				$this->db->insert("state_code", $array);
				$id 	= 	$this->db->insert_id;
			}			
			return true;
		}
		return $message;
	}	
	function CheckForUniqueCode($code,$id=0)
		{
		$qry		=	"select count(*) as number from state_code where code='$code' ";
		if($id>0)
			$qry	.=	" AND id<>$id";
		$row 	= 	$this->db->get_row($qry,ARRAY_A);
		if($row['number']>0)
			return true;
		else
			return false;
		}
	function GetAllStates($country_id)
		{
		$qry		=	"SELECT * FROM state_code WHERE country_id='$country_id' ORDER BY `name` ASC";
		$rs 		= 	$this->db->get_results($qry,ARRAY_A);
		return $rs;
		}
		
		function GetAllStates1($country_id)
		{
		$qry		=	"SELECT name FROM state_code WHERE country_id='$country_id' ORDER BY `name` ASC";
		$rs 		= 	$this->db->get_results($qry,ARRAY_A);
		return $rs;
		}
}//end class

?>