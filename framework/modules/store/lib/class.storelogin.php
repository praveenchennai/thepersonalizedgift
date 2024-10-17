<?php

class Storelogin extends FrameWork {
	var $username;
	var $password;
	var	$store_id;
	var $errorMessage;
	function Storelogin($username="", $password="",$store_id) {
		$this->username = $username;
		$this->password = $password;
		$this->store_id = $store_id;
		$this->FrameWork();
	}
	function authenticate() {	
		$sql	=	"SELECT a.id,a.username,a.password,a.first_name as name,b.heading,a.email,b.name FROM `member_master` a,
		  			store b WHERE a.username='$this->username' AND a.password='$this->password' AND b.id='$this->store_id' AND a.id=b.user_id"; 
		$checkRS = $this->db->get_results($sql);
							
		if(count($checkRS) > 0) {			
		
				//$qry1	=	"select * from module where active='Y' order by position";
				$qry1 = "SELECT a.*,b.module_id,b.store_id,b.module_menu_id,b.hide FROM module a,store_permission b WHERE a.active='Y' AND a.id = b.module_id AND b.store_id='$this->store_id' AND b.module_menu_id=0 AND b.hide = 'N' ORDER BY a.position";
                //echo $qry1."<br>";
				$rs1 	= 	$this->db->get_results($qry1);
				$arr	=	array();
				for($i=0;$i<count($rs1);$i++)
					{
					
					//$qry2	=	"select * from module_menu where module_id=".$rs1[$i]->id." and active='Y' order by display_order";
					$qry2 = "SELECT a.*,b.module_menu_id,b.store_id,b.hide FROM module_menu a,store_permission b WHERE a.active='Y' AND a.module_id=".$rs1[$i]->id." AND a.id = b.module_menu_id AND  b.store_id='$this->store_id' AND b.hide = 'N' ORDER BY a.display_order";
					//echo $qry2."<br>";
					$rs2 	= 	$this->db->get_results($qry2, ARRAY_A);
					$arr[$i]	=	$rs1[$i];
					$arr[$i]->menu	=	$rs2;
					}
			//modified by robin
			if(count($arr)>0)
			 $checkRS["modules"] = $arr;
			 else
			 {
			 	$qry1 = "SELECT a.*,b.module_id,b.store_id,b.module_menu_id,b.hide FROM module a,store_permission b WHERE a.active='Y' AND a.id = b.module_id AND b.store_id='0' AND b.module_menu_id=0 AND b.hide = 'N' ORDER BY a.position";
                //echo $qry1."<br>";
				$rs1 	= 	$this->db->get_results($qry1);
				$arr	=	array();
				for($i=0;$i<count($rs1);$i++)
					{
					
					//$qry2	=	"select * from module_menu where module_id=".$rs1[$i]->id." and active='Y' order by display_order";
					$qry2 = "SELECT a.*,b.module_menu_id,b.store_id,b.hide FROM module_menu a,store_permission b WHERE a.active='Y' AND a.module_id=".$rs1[$i]->id." AND a.id = b.module_menu_id AND  b.store_id='0' AND b.hide = 'N' ORDER BY a.display_order";
					//echo $qry2."<br>";
					$rs2 	= 	$this->db->get_results($qry2, ARRAY_A);

					//******* This code added by 5-09-2008 for adding extra parameter in manage content link
							if($i==0){
								for($j=0;$j<count($rs2);$j++)
								{
								
									if($rs2[$j][menu]=='Manage Content')
									{	$section_id=$this->defaultSection();
										$rs2[$j][other]=$rs2[$j][other]."&section_id=".$section_id;
									}
									
								}
							}//***************end 5-09-2008


					$arr[$i]	=	$rs1[$i];
					$arr[$i]->menu	=	$rs2;
					}
					
					
					
					 $checkRS["modules"] = $arr;
					 
				
			 }
			 
			
			$_SESSION['storeSess'] 	= 	$checkRS;
			
			//$_SESSION['store_id']	=	$this->store_id;			
			
			return true;
		} else {
			$this->errorMessage = "Invalid Username or Password";
			return false;
		}
	}


	function defaultSection()
	{
		
		$sql	=	"SELECT id,name FROM cms_section WHERE active='Y' and show_sub_store='Y'";	
	
		
       $rs = $this->db->get_row($sql, ARRAY_A);
	     return $rs[id];
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

	
}
?>