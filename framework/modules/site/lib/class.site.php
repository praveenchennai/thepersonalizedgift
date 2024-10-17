<?php

class Site extends FrameWork {
	var $username;
	var $password;
	var	$site_id;
	var $errorMessage;
	function Site($username="", $password="",$site_id="") {
		$this->username = $username;
		$this->password = $password;
		$this->site_id 	= $site_id;
		$this->FrameWork();
	}
	function authenticate() {	
	global $site_id;	
		$sql	=	"SELECT 
							a.id,
							a.username,
							a.password,
							a.first_name as name,
							a.email,
							b.sitename,
							b.modules  
						FROM `member_master` a,
		  					site  b 
						WHERE a.username='$this->username'
						 AND a.password='$this->password' 
						 AND b.id='$site_id' 
						 AND a.id=b.user_id"; 
		$checkRS = $this->db->get_results($sql);
		$module_arr		=	$checkRS[0]->modules;	
		$modules		=	explode(',',$module_arr);
		$module_name	=	array();
		for($i=0;$i<count($modules);$i++){
			$query		=	"SELECT * 
								FROM module 
							WHERE id=".$modules[$i];
			$rsmodule 	=	$this->db->get_row($query, ARRAY_A);
			$module_name[$i]	=	$rsmodule;	
		}
			$module_name		=	array_reverse($module_name);			
		if(count($checkRS) > 0) {				
			$_SESSION['siteSess'] 		= 	$checkRS;
			$_SESSION['moduleSess']		=	$module_name;					
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
		$sql	=	"SELECT 
						a.id,
						a.username,
						a.password,
						a.first_name as name,
						a.email 
						FROM `member_master` a
					 INNER JOIN `member_types` b ON a.mem_type=b.id 
				 WHERE 	a.username='{$_SESSION['adminSess']->username}'
				 	AND a.password='{$old_password}'
					AND b.type='admin'";		
		if(count($this->db->get_row($sql))) {
			$this->db->update("member_master", array("password" => $new_password), "username='{$_SESSION['adminSess']->username}'");
			return true;
		} else {
			return "The Old Password is not correct";
		}
	}
	/**
	 * getting site details based on sitename
	 * @param <sitename> $siteName	
	 * return row as array	
	 */
	function  getSiteByName($siteName){
		$sql	=	"SELECT * 
						FROM site 
					 WHERE sitename='$siteName'";				 	
		$rs 	=	 $this->db->get_row($sql, ARRAY_A);		
		return $rs;
	}
	/**
	 * getiing all SiteModule	 
	 * return row as array	
	 */
	function allModules(){
		$sql	=	"SELECT * FROM module";		
		$rs		=	$this->db->get_results($sql);
		return $rs;
	}
	function updateModules(&$req){
		global $site_id;		
		extract($req);		
		$countModule	=	count($modules);
		$moduleString	="";
		for($i=0;$i<$countModule;$i++){
			if($moduleString){
				$moduleString	=	$moduleString.",".$modules[$i];
			}else{
				$moduleString	=	$modules[$i];	
			}
		}		
		$array			=	 array("modules"=>$moduleString);
		$this->db->update("site", $array, "id='$site_id'");
		return true;
	}	
}
?>