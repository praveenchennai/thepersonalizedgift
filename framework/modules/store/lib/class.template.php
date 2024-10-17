<?php
class Template extends FrameWork {
  var $template_name;
  var $template_desc;
  var $template_folder;  
  var $active;
  function Sitemap($template_name="",$template_desc="",$template_folder="",$active="") {
	$this->template_name 	= 	$template_name;
	$this->template_desc 	= 	$template_desc;
	$this->template_folder	=	$template_folder;		
	$this->active			=	$active;	
	$this->FrameWork();
  }	
	/**
	 *Listing All Templates and css
	 *
	 * return array
	 */
	function templateList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
		$sql	= "SELECT * FROM store_template WHERE 1";	
		list($rs,$numpad) = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);		
		if($rs){
			foreach ($rs as $key=>$row){		
				$temp_id= $row->id;											
				$rs[$key]->cssList= $this->getcssList($temp_id);									
			}
		}		
		return array($rs);		
	}
	
	/**
	 *	Selecting All CSS under specified Template
	 * @param <temp_id> $temp_id
	 * return Array	 
	 */
	 function getcssList($temp_id){
	 	$sql	=	"SELECT a.id as css_id,a.* FROM store_css a WHERE a.template_id=$temp_id";		
		$rs = $this->db->get_results($sql);	
		return $rs;
	 }
	 /**
	 *Listing All Templates and css
	 *
	 * return array
	 */
	function cssList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
		$sql	= 	"SELECT a.id as temp_id,a.*,b.* FROM store_template a,store_css b WHERE a.id = b.template_id and a.active='Y'";	
		$rs		= 	$this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);		
		return $rs;		
	}
	 /**
	 *	getting CSS details based on given id
	 *	@param <id> $id
	 * 	return array
	 */
	 function getCss ($id) {
		$rs 	= 	$this->db->get_row("SELECT a.id as temp_id,a.*,b.* FROM store_template a,store_css b WHERE a.id = b.template_id AND b.template_id='{$id}'", ARRAY_A);
		return $rs;
		
	}
	 /**
	 *	Assigning CSS to specified store
	 *	@param <id,css_id> $store_id,$css_id
	 * 	return array
	 */
	function assignCss($store_id,$temp_id,$avator=""){
	
		if($avator=="")
		{		$query	=	"UPDATE store SET template_id='$temp_id',active='Y' WHERE id='$store_id'";
			}
		else	
		{
			if($temp_id=="") {
				$query	=	"UPDATE store SET avator=$avator, active='Y' WHERE  id = '$store_id'";
			}
			else{	
				$query	=	"UPDATE store SET template_id='$temp_id',active='Y', avator=$avator WHERE id='$store_id'";
			}
		}			
			
		$assign	=	$this->db->query($query);
		if($assign){
			return true;
		}
	}
	 /**
	 *	Getting STORE details by specified id
	 *	@param <id> $id
	 * 	return array
	 */
	function storeGet ($id) {
		$rs = $this->db->get_row("SELECT * FROM store WHERE id='{$id}'", ARRAY_A);
		return $rs;
	}
}
?>