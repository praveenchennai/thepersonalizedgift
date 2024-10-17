<?php
/**
 * **********************************************************************************
 * @package    Member
 * @name       User
 * @version    1.0
 * @author     Retheesh Kumar
 * @copyright  2007 Newagesmb (http://www.newagesmb.com), All rights reserved.
 * Created on  14-Aug-2006
 * 
 * This script is a part of NewageSMB Framework. This Framework is not a free software.
 * Copying, Modifying or Distributing this software and its documentation (with or 
 * without modification, for any purpose, with or without fee or royality) is not
 * permitted.
 * 
 ***********************************************************************************/
class Userdb3 extends FrameWork
{
	var $ip_det = null;
	/*
	constructor
	*/
	function Userdb3()
	{
		$this->FrameWork();
	}


	function getStudentList($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$id)
	{
		$sql="SELECT b.*,c.gender_c from test_parention_contacts a right join contacts b on a.contacts_idb=b.id left join contacts_cstm c on c.id_c=b.id where a.test_parentgroupregistration_ida='".$id."'";
		$rs = $this->db1->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
}
?>