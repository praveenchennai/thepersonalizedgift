<?php

/**
 * @description 	The following method used for processing the dropdowns created in the entire application	
 *
 *
 * @author vimson@newagesmb.com
 *
 */



class Dropdown extends FrameWork 
{


	/**
	 * @description		Constructor	
	 *
	 */	
	function Dropdown() 
	{
		$this->FrameWork();
	}	
	
	
	/**
	 * @description	The following method will returns the dropdown names
	 *
	 *
	 */
	function getDropDowns($pageNo, $limit, $params='', $output=OBJECT, $orderBy)
	{
		$Query		= 	'SELECT id,dropdown_name FROM dropdowns WHERE deleted = 0 ';
		$rs 		= 	$this->db->get_results_pagewise($Query, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
	
	/**
	 * @description The following method validates the dropdown add form
	 *
	 *
	 */
	 function validateDropdownAddForm($REQUEST)
	 {
	 	extract($REQUEST);
	 	$msg	=	'';

	 	if($dropdownname == '') {
			$msg	=	'Dropdown name required';
		} else {
			$Qry		=	"SELECT COUNT(*) AS TotCount FROM dropdowns WHERE dropdown_name = '$dropdownname'";
			$Row		=	$this->db->get_row($Qry, ARRAY_A);
			$TotCount	=	$Row['TotCount'];
			if($TotCount > 0)
				$msg	=	'Dropdown exists with the same name';
		}
		
		if($msg	=	'')
			return TRUE;
		else
			return $msg;
	 }
	 
	 
	/**
	 * @description The following method  insert a new drop down record	
	 *
	 *
	 */
	function addEditDropdown($REQUEST) 
	{
		$msg	=	'';
		extract($REQUEST);
		
		if($id != '') {
			$Qry	=	"UPDATE dropdowns SET dropdown_name = '' WHERE id = '$id'";
			$msg	=	'Dropdown added successfully';
		} else {
			$Qry	=	"UPDATE dropdowns SET dropdown_name = '' WHERE id = '$id'";
			$msg	=	'Dropdown Updated successfully';
		}
		$this->db->query($Qry);
		return $msg;
	}
	
	
	
	/** 
	 * @desription The following method returns the options associated with a dropdown
	 *
	 *
	 *
	 */
	function getOptionsOfDropdown($dropdownid, $pageNo, $limit, $params, $output=OBJECT, $orderBy)
	{
		$Query		= 	"SELECT * FROM dropdownvalues WHERE dropdown_id = '$dropdownid' AND deleted = '0' ";
		$rs 		= 	$this->db->get_results_pagewise($Query, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
	
	/**
	 * @description The followijng method validates the options enytered
	 *
	 *
	 *
	 */
	function validateOption($REQUEST) 
	{
		$msg	=	'';	
		extract($REQUEST);
		
		if($label == '') 
			$msg	.=	'Option Label empty<br>';
		
		if($dbvalue == '') 
			$msg	.=	'Option Value empty<br>';
		
		if($msg == '')
			return TRUE;
		else
			return $msg;
	}

	
	
	/**
	 * @description the following method adds and edits the options
	 *
	 *
	 */
	 function addEditOption($REQUEST)
	 {
	 	$msg	=	'';
		extract($REQUEST);
		
		if($optionid != '') {
			$Qry	=	"UPDATE dropdownvalues SET label = '$label', dbvalue = '$dbvalue' WHERE id = '$optionid'";
			$msg	=	'Option Updated successfully.';	
		} else {
			$Qry	=	"INSERT INTO dropdownvalues (dropdown_id, label, dbvalue) 
						VALUES ('$id','$label','$dbvalue')";
			$msg	=	'Option Added successfully.';	
		}
		
		$this->db->query($Qry);
		
		return $msg;
	 }
	
	
	/**
	 * @description The following method returns the option details corresponding to an option id
	 *
	 *
	 */
	function getOptiondetailsFromId($optionid)
	{
		$Qry		=	"SELECT * FROM dropdownvalues WHERE id = '$optionid'";
		$Row		=	$this->db->get_row($Qry, ARRAY_A);
		return $Row;
	}
	

	
	/**
	 * @description The following method removes an option from the dropdown
	 *
	 *
	 */
	function removeOption($optionid)
	{
		$Qry	=	"UPDATE dropdownvalues SET deleted = '1' WHERE id = '$optionid'";
		$this->db->query($Qry);
		return TRUE;
	}

	/**
	 * @description The following method would return two dimensional array of labels and dbvalues for combo filling 
	 *
	 *
	 */
	function getOptionsForComboFilling($dropdownid)
	{
		$Qry	=	"SELECT label, dbvalue FROM dropdownvalues WHERE deleted = '0' AND dropdown_id = '$dropdownid'";
		$Combo['label']		=	$this->db->get_col($Qry, 0);
		$Combo['dbvalue']	=	$this->db->get_col($Qry, 0);
		return $Combo;
	}
	




}




?>