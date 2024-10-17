<?php

class Admin extends FrameWork {
	var $username;
	var $password;
	var $errorMessage;

	function Admin($username="", $password="") {
		$this->username = $username;
		$this->password = $password;
		$this->FrameWork();
	}

	function authenticate() {	
		$sql="SELECT a.id,a.username,a.password,a.first_name as name,a.email,a.mem_type as member_type  FROM `member_master` a
		 inner join `member_types` b on a.mem_type=b.id WHERE a.username='$this->username' AND  
		 a.password='$this->password' AND (b.type='admin' or b.type='supplier')";
		// echo $_SERVER['SERVER_ADDR'];
		// if($_SERVER['SERVER_ADDR'] == '182.72.193.85' || $_SERVER['SERVER_ADDR'] == '216.154.209.42' )
		// 	echo $sql;
		$checkRS = $this->db->get_results($sql);				
		if(count($checkRS) > 0) {
			if ( $this->username == "admin" ) {
				$sql = "SELECT * FROM module WHERE show_admin_menu = 'Y' AND active = 'Y' ORDER BY position";
				$qry1	=	"select * from module where active='Y' order by position";
				$rs1 	= 	$this->db->get_results($qry1);			
				
								$arr	=	array();
				for($i=0;$i<count($rs1);$i++)
				{

					$qry2	=	"select * from module_menu where module_id=".$rs1[$i]->id." AND active='Y' AND display_admin ='Y' order by display_order";
					$rs2 	= 	$this->db->get_results($qry2, ARRAY_A);
					$arr[$i]	=	$rs1[$i];
					$arr[$i]->menu	=	$rs2;
					
				}
			} else {
				$sql = "SELECT m.*
						  FROM module m, module_permission p 
						 WHERE m.id = p.module_id 
						   AND m.active = 'Y' 
						   AND m.show_admin_menu = 'Y' 
						   AND p.admin_id = '{$checkRS[0]->id}'
					  ORDER BY position";
				$rs1 = $this->db->get_results($sql);				
				$arr	=	array();
				for($i=0;$i<count($rs1);$i++){		
					$qry2	=	"select * from module_menu where module_id=".$rs1[$i]->id." and active='Y' ";
					if($checkRS[0]->member_type==3){
						$qry2	=	$qry2. " AND display_supplier='Y'";
					}
					$qry2	=	$qry2. " order by display_order";
					$rs2 	= 	$this->db->get_results($qry2, ARRAY_A);
					$arr[$i]		=	$rs1[$i];
					$arr[$i]->menu	=	$rs2;
				}
			}
			$_SESSION['adminSess'] = $checkRS[0];			
			$_SESSION['adminSess']->modules = $arr;			
			return true;
		} else {
			$this->errorMessage = "Invalid Username or Password";
			return false;
		}
	}

	/**
	 * Change Password
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message
	 */
	function changePassword(&$req) {

		$old_password  = $req['old_password'];
		$new_password  = $req['new_password'];
		$conf_password = $req['conf_password'];

		if(!trim($old_password) || !trim($new_password) || !trim($conf_password)) {
			return "All fields are required";
		} elseif ($new_password != $conf_password) {
			return "New Passwords doesn't match";
		}
		$sql="SELECT a.id,a.username,a.password,a.first_name as name,a.email FROM `member_master` a
		 inner join `member_types` b on a.mem_type=b.id WHERE 
		 a.username='{$_SESSION['adminSess']->username}' AND a.password='{$old_password}' AND 
		 b.type='admin'";
		if(count($this->db->get_row($sql))) {
			$this->db->update("member_master", array("password" => $new_password), "username='{$_SESSION['adminSess']->username}'");
			return true;
		} else {
			return "The Old Password is not correct";
		}
	}

	/**
	 * Admin List
	 *
	 * @param <Page Number> $pageNo
	 */
	function adminList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
		$sql		= "SELECT a.id,a.username,a.password,a.first_name as name,a.email FROM `member_master` a
						inner join `member_types` b on a.mem_type=b.id where b.type='admin'";

		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}

	/**
	 * Get Admin Record for given ID
	 *
	 * @param <id> $id
	 * @return Array
	 */
	function adminGet ($id) {
		$sql="SELECT a.id,a.username,a.password,a.first_name as name,a.email FROM
		`member_master` a WHERE a.id='{$id}'";
		$rs = $this->db->get_row($sql, ARRAY_A);
		return $rs;
	}

	/**
	 * Admin Module List
	 *
	 * @param <POST/GET Array> $req
	 */
	function adminModuleList ($id) {
		$sql="Select username from `member_master` where id='$id'";
		list($username) = $this->db->get_row($sql, ARRAY_N);
		if($username != "admin") {
			$rs = $this->db->get_results("SELECT id, name, module_id FROM module m LEFT JOIN module_permission p ON (m.id = p.module_id AND admin_id='{$id}') ORDER BY m.position");
		}

		return $rs;
	}

	/**
	 * Add Edit Admin Users
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message
	 */
	function adminAddEdit (&$req) {
	
		extract($req);
		if(!trim($username)) {
			$message = "Username is required";
		} elseif (!trim($password)) {
			$message = "Password is required";
		} elseif ($password != $conf_password) {
			$message = "Passwords dont match";
		} else {
			$mem_type=$this->db->get_row("select id from `member_types` where type='admin'");
			
			$array = array("username"=>$username, "password"=>$password, "first_name"=>$name, "email"=>$email,"mem_type"=>$mem_type->id);
			if($id) {
				$array['id'] = $id;
				
				$this->db->update("member_master", $array, "id='$id'");
			} else {
				$this->db->insert("member_master", $array);
				$id = $this->db->insert_id;
			}
			$this->db->query("DELETE FROM module_permission WHERE admin_id='$id'");
			if($req['module']) {
				foreach ($req['module'] as $module) {
					$this->db->insert("module_permission", array("admin_id"=>$id, "module_id"=>$module));
				}
			}
			return true;
		}
		return $message;
	}

	/**
	 * Delete Admin Users
	 *
	 * @param <POST/GET Array> $req
	 * @param [Error Message] $message
	 */
	function adminDelete ($id) {
		$this->db->query("DELETE FROM member_master WHERE id='$id'");
	}


	/**
	 * List Modules
	 *
	 * @param <POST/GET Array> $req
	 */
	function moduleList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
		$sql		= "SELECT * FROM module WHERE 1";

		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}

	/**
	 * Get Module for given ID
	 *
	 * @param <id> $id
	 * @return Array
	 */
	function moduleGet ($id) {
		$rs = $this->db->get_row("SELECT * FROM module WHERE id='{$id}'", ARRAY_A);
		return $rs;
	}

	/**
	 * Module Add Edit
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message
	 */
	function moduleAddEdit (&$req) {

		extract($req);
		$show_admin_menu = $show_admin_menu ? 'Y' : 'N';
		$active = $active ? 'Y' : 'N';
		if(!trim($name)) {
			$message = "Module Name is required";
		} elseif (!trim($folder)) {
			$message = "Folder Name is required";
		} else {
			$array = array("name"=>$name, "folder"=>$folder, "position"=>$position, "show_admin_menu"=>$show_admin_menu, "active"=>$active);
			if($id) {
				$array['id'] = $id;
				$this->db->update("module", $array, "id='$id'");
			} else {
				$this->db->insert("module", $array);
				$id = $this->db->insert_id;
			}
			unset($_SESSION['adminSess']->modules);
			$this->username = $_SESSION['adminSess']->username;
			$this->password = $_SESSION['adminSess']->password;
			$this->authenticate();
			return true;
		}
		return $message;
	}

	/**
	 * Delete Module
	 *
	 * @param [ID] $id
	 */
	function moduleDelete ($id) {
		$this->db->query("DELETE FROM module WHERE id='$id'");
	}

	/**
	 * Get the email address
	 * 
	 **/
	 
	 
	 function getDrawID()
	 {
	$sql="select MAX(setting_id) as id from drawsettings ";
	$rs = $this->db->get_results($sql,ARRAY_A);
		if(count($rs>0))
		{
			foreach ($rs as $value)
			{
				return $value['id'];
			}
		} 
	 }
	function getEmail($id=0){
		if($id>0)
		{
			$sql		= "SELECT email FROM member_master WHERE id=$id";
		}
		else
		{
			$sql		= "SELECT email FROM member_master WHERE id=".$_SESSION['adminSess']->id;
		}
		$rs = $this->db->get_results($sql,ARRAY_A);
		if(count($rs>0))
		{
			foreach ($rs as $value)
			{
				return $value['email'];
			}
		}
	}
	function changeEmail(&$req){
		extract($req);
		if(!trim($email)) {
			$message = "Email is required";
		}
		else if(!$this->validateemail($email)){
			$message = "Email format is invalid";
		}
		else {
			$array = array("email"=>$email);
			$id=$_SESSION['adminSess']->id;
			if($id) {
				$this->db->update("member_master", $array, "id='$id'");
				$message = "Email set successfully";
			}
		}
		return $message;
	}
	function validateemail($email)
	{
		if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email))
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	function configList () {
		$sql		= "SELECT * FROM config WHERE editable='Y' ORDER BY category";

		$rs = $this->db->get_results($sql);
		if ($rs) {
			for ($i=0; $i<count($rs); $i++) {
				if ($rs[$i]->possible_values) {
					$var = explode("|", $rs[$i]->possible_values);
					unset($rs[$i]->possible_values);
					if ($var) {
						for ($j = 0; $j < count($var); $j++) {
							$temp = explode("^", $var[$j]);
							if(!$temp[1])$temp[1] = $temp[0];
							$k = $temp[0];
							$rs[$i]->possible_values[$k] = $temp[1];
						}
					}
				}
			}
		}
		return $rs;
	}

	function configEdit (&$req) {

		foreach ($req as $key=>$val) {
			$this->db->update("config", array("value"=>$val), "field='$key'");
		}

		return true;
	}
	function DrawvaluesEdit (&$req) {
	
        	$this->db->update("drawsettings", $req);
	}	
	function GetFields($menu_id='')
	{
		if($menu_id)
		{
			$qry		=	"	SELECT * FROM module_fields WHERE menu_id=".$menu_id." order by display_order";
			$rs		 	= 	$this->db->get_col($qry, 5);
			//print $qry	;exit;
			return $rs;
		}
		return false;
	}

	//to get details when id is given
	function getModuleMenu($id)
	{
		$sql ="SELECT * FROM module_menu WHERE module_id = $id AND active = 'Y'";
		$rs = $this->db->get_results($sql);
		return $rs;
	}
	function getModule()
	{
		$sql ="SELECT * FROM module WHERE active = 'Y' ORDER BY position";
		$rs = $this->db->get_results($sql);
		return $rs;
	}
	function getDrawsettingvalues()
	{
		$sql		= "SELECT * FROM  drawsettings ";
    	$rs = $this->db->get_row($sql);
		return $rs;
		
	}
	function getMenuById($id)
	{
		$sql ="SELECT * FROM module_menu WHERE active = 'Y' AND id = '$id' ";
		$rs = $this->db->get_row($sql,ARRAY_A);
		return $rs;
	}
	function moduleExists($name)
	{
		$sql = "select active from module where folder='$name'";
		$rs  = $this->db->get_row($sql);
		if ($rs->active=='Y')
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function GetmenuPermission($store_id=0,$menu_id,$field)
	{
		
		$chkStoreQry	=	"SELECT DISTINCT store_id FROM `store_permission` ";
		$record	=	$this->db->get_col($chkStoreQry,0);
		if(in_array($store_id,$record)){
			$qry	=	"SELECT * FROM store_permission WHERE store_id=".$store_id." AND module_menu_id=".$menu_id." AND `$field`='Y'";
		}
		else {
			$qry	=	"SELECT * FROM store_permission WHERE store_id=0 AND module_menu_id=".$menu_id." AND `$field`='Y'";
		}

		if($store_id>0)
		{
//			$qry	=	"SELECT * FROM store_permission WHERE store_id=".$store_id." AND module_menu_id=".$menu_id." AND `$field`='Y'";
			//echo $qry."<br><br>";
			$rs  = $this->db->get_row($qry);
			if($rs)
			return "Y";
			else
			return "N";
		}
		else
		{
			return "Y";
		}
	}
	function getStoreEditable($menu_id='')
	{
		if($menu_id)
		{
			$qry		=	"	SELECT * FROM module_fields WHERE menu_id=".$menu_id." order by display_order";
			$rs		 	= 	$this->db->get_col($qry, 6);
			//print_r($rs);exit;
			return $rs;
		}
		return false;
	}
	
	
}




?>