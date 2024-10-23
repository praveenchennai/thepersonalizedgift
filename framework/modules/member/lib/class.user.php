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

class User extends FrameWork
{

	// ini_set('display_errors', 1);
	// ini_set('display_startup_errors', 1);
	// error_reporting(E_ALL);

	var $ip_det = null;
	/*
	constructor
	*/
	function User()
	{
		$this->FrameWork();
	}


	function getCountryName1($id){
	
		$sql="SELECT countryname from country_master where country_id=".$id;
		$RS = $this->db->get_row($sql);
	}

	/**
  	 * This function is used for changing password
  	 * Author   : Retheesh
  	 * Created  : 14/Aug/2006
  	 * Modified : 25/Sep/2007 By Retheesh
  	 */
	function changePassword($old_pass,$new_pass,$uid){
		$sql = "select * from member_master where id=$uid and password='$old_pass'";
		$rs  = $this->db->get_row($sql);
		if(count($rs)>0)
		{
			$arr=array();
			$arr["password"]=$new_pass;
			$this->db->update("member_master",$arr,"id=$uid");
			return true;
		}
		else
		{
			$this->setErr("Old password is incorrect");
			return false;
		}
	}

  
	/**
    * Setting post Array Data
  	* Author   : Retheesh
  	* Created  : 14/Aug/2006
  	* Modified : 25/Sep/2007 By Retheesh
  	*/
	function setArrData($szArrData)
	{
		$this->arrData = $szArrData;
	}

	/**
    * Return post Array Data
  	* Author   : Retheesh
  	* Created  : 14/Aug/2006
  	* Modified : 25/Sep/2007 By Retheesh
  	*/
	function getArrData()
	{
		return $this->arrData;
	}

	/**
    * Setting error text
  	* Author   : Retheesh
  	* Created  : 14/Aug/2006
  	* Modified : 25/Sep/2007 By Retheesh
  	*/
	function setErr($szError)
	{
		$this->err .= "$szError";
	}

	/**
    * Getting error text
  	* Author   : Retheesh
  	* Created  : 14/Aug/2006
  	* Modified : 25/Sep/2007 By Retheesh
  	*/
	function getErr()
	{
		return $this->err;
	}


	/**
    * Membership validations
  	* Author   : Retheesh
  	* Created  : 14/Aug/2006
  	* Modified : 25/Sep/2007 By Retheesh
  	* Modified : 11/01/2007 By Retheesh
  	* Validation for Duplicate email Registraion
  	*/
	function isValid($mode)
	{
	   
		$arrData = $this->getArrData();
		$date_array=explode("-",$arrData["dob"]);

		if (($mode=="insert") || ($mode=="update"))
		{
		
		if ($this->config['member_screen_name']=='Y')
		     {
			////////////////////date validation	
				if($date_array[1]=='04' or $date_array[1]=='06' or $date_array[1]=='09' or  $date_array[1]=='11'   )	
				 {
				    if($date_array[2]>30)
					
					{
					  $this->setErr("Invalid Day!");
					  return false;
					}
				 }	
				 //exit;
				///////////////////////////////////////////
			if($date_array[0] % 4 == 0 && ($date_array[0] % 100 != 0 || $date_array[0] % 400 == 0)) 
					{
					
					    if($date_array[1]=='02')
						{
						 if($date_array[2]>29)
						 {
						 $this->setErr("Invalid Day!");
					     return false;
						 }
						}
					}
					else
					{
					   if($date_array[1]=='02')
						{
						 if($date_array[2]>28)
						 {
						 $this->setErr("Invalid Day!");
					     return false;
						 }
						}
					}	 
			 }
          ////////////////////////////for checking if screen_name already exists
		if ($mode=="insert")
			{

				if ($this->config['member_screen_name']=='Y')
		     {

					$uname=$arrData["screen_name"];
					$Rs= $this->db->get_results("SELECT * from `member_master` WHERE `screen_name`='$uname'");
					if(count($Rs)>0)
					{
						$this->setErr("Screen Name Already Exists!");
						return false;
					
				   	}
				
					  
			  }

			}
         ///////////////////////////
			if ($mode=="insert")
			{

				if ($arrData["username"])
				{

					$uname=$arrData["username"];
					if($this->config['email_username']!='Y'){
						if(!ctype_alnum($uname)) 
						{
							$this->setErr("Use only alphanumeric characters in Username!");
							return false;
						}	
					}					
					$sql="SELECT * from `member_master` WHERE `username`='$uname'";
					
					//code modified by Robin 
					//Date 29-5-2008
					//Purpose : same username@storename
							if ($this->config['payment_receiver']=='store'){
								
									$store	=	$arrData["from_store"];	
									$sql =$sql. " AND from_store = $store";
								
							}
							
							
						$Rs= $this->db->get_results($sql);
	
						if(count($Rs)>0)
						{
							if ($this->config["registration_unique_field"])
							{
								$unique_field = $this->config["registration_unique_field"];
								$this->setErr("$unique_field already Exists!");
							}
							else
							{
								$this->setErr("Username Already Exists!");
							}
							return false;
						}
				}

			}

			$email=$arrData["email"];
			/*
			* This is for personalizedgift[for the purpose of same email for multiple stores].
			*/
			$condition = "";
			if ($this->config['single_prod']=='1'){
				//if($arrData["from_store"] != ''){	
					$store	=	$arrData["from_store"];	
					$condition = " AND from_store = $store";
				//}
			}
			
			$qry="SELECT id from `member_master` WHERE `email`='$email'";
			$qry.=$condition;
			$Rs= $this->db->get_results($qry);
			
			if(count($Rs)>0){

				if($this->config['duplicate_email_registration']!="Y") //Addded for allowing duplicate Email Registration
				{
					if ($mode=="update")
					{
						if ($Rs[0]->id!=$_SESSION["memberid"])
						{
							$this->setErr("Email already Exists!");
							return false;
						}
						else
						{
							return true;
						}
					}
					else
					{
						$this->setErr("Email Already Exists!");
						return false;
					}
				}
				else{

					return true;
				}

			}
			else
			{
				return true;
			}

		}
		return true;
	}

	/**
    * This function will split the fields of Master table and Address table into two arrays;
  	* Author   : Retheesh
  	* Created  : 14/Aug/2006
  	* Modified : 25/Sep/2007 By Retheesh
  	*/
	function getAddrFields($arr_master,$table_name)
	{

		$rs=$this->db->query("select * from $table_name");
		
		$arr=$this->db->col_info;
		for($i=0;$i<sizeof($arr);$i++)
		{
			$key=$arr[$i]->name;
			
			$value=$arr[$i]->name;
			if(isset($arr_master[0][$value]))
			{
				$arr_addr[$key]=$arr_master[0]["$value"];
				unset($arr_master[0][$value]);
			}
		}
		
		$arr_master[2]=$arr_addr;
		
		return $arr_master;
	}

	/**
    * Inserting values to member master and custom fields
  	* Author   : Retheesh
  	* Created  : 14/Aug/2006
  	* Modified : 25/Sep/2007 By Retheesh
  	*/
	function insert()
	{
		
		$arr_master = $this->getArrData();
		
		if (!$arr_master["addr_type"])
		{
			$arr_master["addr_type"] ="master";
		}

		$arr_master=$this->splitFields($arr_master,'member_master');   // Custom fields are seperated to another array from member master fields
		$arr_master=$this->getAddrFields($arr_master,'member_address'); //Member Address fields are seperated here
		
		if($this->config["health_care"]){
			$arr_shipping["address1"] = $arr_master[0]["shipping_address1"];
			$arr_shipping["address2"] = $arr_master[0]["shipping_address2"];
			$arr_shipping["city"] = $arr_master[0]["shipping_city"];
			$arr_shipping["state"] = $arr_master[0]["shipping_state"];
			$arr_shipping["postalcode"] = $arr_master[0]["shipping_postalcode"];
			$arr_shipping["telephone"] = $arr_master[2]["telephone"];
			$arr_shipping["mobile"] = $arr_master[2]["mobile"];
			unset($arr_master[0]["shipping_address1"],$arr_master[0]["shipping_address2"],$arr_master[0]["shipping_city"],$arr_master[0]["shipping_state"],$arr_master[0]["shipping_postalcode"]);
		}

		$membermaster['username'] = $arr_master[0]["username"];
		$membermaster['password'] = $arr_master[0]["password"];

		$membermaster['first_name'] = $arr_master[0]["first_name"];
		$membermaster['last_name'] = $arr_master[0]["last_name"];

		$membermaster['email'] = $arr_master[0]["email"];
		$membermaster['joindate'] = $arr_master[0]["joindate"];
		$membermaster['from_store'] = $arr_master[0]["from_store"];

		print_r($membermaster);
	
		$this->db->insert("member_master", $membermaster);

		$id = $this->db->insert_id;
		$arr_master[1]["table_key"]=$id;
		$arr_master[2]["user_id"]=$id;

		if ($arr_master[1]["table_id"]>0)
		{
			$this->db->insert("custom_fields_list", $arr_master[1]);
		}
		$this->db->insert("member_address", $arr_master[2]);
		if($this->config["health_care"]){
			$arr_shipping["addr_type"] = "shipping";
			$arr_shipping["user_id"] = $id;
			$this->db->insert("member_address", $arr_shipping);
		}
		return $id;
	}

	/**
    * Updating values to member master and custom fields
  	* Author   : Retheesh
  	* Created  : 14/Aug/2006
  	* Modified : 25/Sep/2007 By Retheesh
  	*/
	function update($posid='',$num=0)
	{
	 
		
	    $arr_master = $this->getArrData();
		$arr_bill	= $this->getArrData();
		// print_r($arr_master);
		// exit;
			
		$id = $arr_master['id'];
		
		if($num==""){	
		///////////////for link54
			if($this->config["inner_change_reg"]=="yes"){
						
				if($posid!="")
				{
				 
					$arr_master=array('chatposition'=>$posid);
					$id = $_REQUEST['id'];
				}
				
				//else
				//{
					//$arr_master=array('chatposition'=>1);
					//$id = $_REQUEST['id'];
				//}
			}
		}
		$newRecord = false;
	   	if ( $arr_master['addr_type']){
			$addr_type = $arr_master['addr_type'];
		}else{
			$newRecord = true;
			$addr_type = "master";
		}
		unset($arr_master['id']);
		$arr_master=$this->splitFields($arr_master,'member_master');
		
		if($this->config["health_care"]){
			$arr_shipping["address1"] = $arr_master[0]["shipping_address1"];
			$arr_shipping["address2"] = $arr_master[0]["shipping_address2"];
			$arr_shipping["city"] = $arr_master[0]["shipping_city"];
			$arr_shipping["state"] = $arr_master[0]["shipping_state"];
			$arr_shipping["postalcode"] = $arr_master[0]["shipping_postalcode"];
			unset($arr_master[0]["shipping_address1"],$arr_master[0]["shipping_address2"],$arr_master[0]["shipping_city"],$arr_master[0]["shipping_state"],$arr_master[0]["shipping_postalcode"]);
		}
		
		$arr_master=$this->getAddrFields($arr_master,'member_address');

		$arr_master[2]["address1"] = $arr_master[0]["address1"];
		$arr_master[2]["city"] = $arr_master[0]["city"];
		$arr_master[2]["state"] = $arr_master[0]["state"];
		$arr_master[2]["country"] = $arr_master[0]["country"] == '---Select a Country---' ? 'UNITED STATES' : $arr_master[0]["country"];
		$arr_master[2]["postalcode"] = $arr_master[0]["postalcode"];
		$arr_master[2]["telephone"] = $arr_master[0]["telephone"];
		$arr_master[2]["mobile"] = $arr_master[0]["mobile"];
		$arr_master[2]["addr_type"] = 'master';

		unset(
			$arr_master[0]["address1"],
			$arr_master[0]["city"],
			$arr_master[0]["state"],
			$arr_master[0]["country"],
			$arr_master[0]["postalcode"], 
			$arr_master[0]["telephone"], 
			$arr_master[0]["mobile"]
		);

		
		if($arr_master[0]){
			$this->db->update("member_master",$arr_master[0] ,"id=$id");
		}
		//print_r($arr_master);exit;
		$table_id=$arr_master[1]["table_id"];
		
								
		unset($arr_master[1]["table_id"]);
		
		if ($table_id>0)
		{    
		    if($posid=="")
			{
			$sql_check = "select id from custom_fields_list where table_key=$id and table_id=$table_id";

			$rs_custom = $this->db->get_row($sql_check,0);
			}
			
			
			if (count($rs_custom)>0)
			{
				
				
				if (count($arr_master[1])>0)
				{
					$this->db->update("custom_fields_list",$arr_master[1] ,"table_key=$id and table_id=$table_id");
				}
			}
			else
			{
				
				$arr_master[1]["table_key"] = $id;
				$arr_master[1]["table_id"]  = $table_id;
				
				$this->db->insert("custom_fields_list", $arr_master[1]);
			}
		}

		
		if($arr_master[2])
		{
			
			if($newRecord){
				//print_r($arr_master);exit;
				$this->db->insert("member_address", $arr_master[2]);
			} else {
				$this->db->update("member_address",$arr_master[2] ,"user_id=$id and addr_type='$addr_type'");
			}
			
			
		}
		if($arr_shipping)
		{
			$this->db->update("member_address",$arr_shipping ,"user_id=$id and addr_type='shipping'");
		}
		
		
	

		if(DB_NAME=="calsilkscreen")
		{
			$sql="select * from member_address where user_id=$id and addr_type='billing' ";
			$RS = $this->db->get_results($sql,ARRAY_A);
			if(count($RS)<1){
				if($arr_master[2])
				{
					$arr_master[2]["user_id"]=$id;
					$arr_master[2]["addr_type"]="billing";
					$this->setArrData($arr_master[2]);
					$this->insertAddress();

				}
			}
		}

		return true;
	}

	/**
    * Deleting a user by userid
  	* Author   : Retheesh
  	* Created  : 14/Aug/2006
  	* Modified : 25/Sep/2007 By Retheesh
  	* Modified : 29/Jan/2008 By Retheesh
	* Modified : 30/Jan/2008 By Jinson
  	* Added related Table deletion using Recursion
  	*/
	function userDelete($id)
	{
		$arr = $this->getCustomFields('member_master');
		$table_id=$arr[1];
		
		$user_det = $this->getUserdetails($id);
		
		$this->db->query("DELETE FROM `member_address` where user_id=$id");
		$this->db->query("DELETE FROM `custom_fields_list` where table_id=$table_id and table_key=$id");
		
		
		if ($this->config['member_related_delete']=='Y')
		{     
				 /* Recursive method to Delete records from module relation table*/
		        $this->relationDelete($table_id,$id); 
			    
		}
		$this->db->query("DELETE FROM `member_master` WHERE id=$id");
		
		if($user_det['mem_type']==2 && $user_det['from_store']==0)
		{
			$this->db->query("DELETE FROM `store` WHERE user_id=$id");
		}
		
		return true;
	}
	
	
	
	
	function drawuserDelete(){
		$this->db->query("DELETE FROM `custom_fields_list` where table_id=1 and field_2!='' ");
		return true;
	}
	

	/**
    * Deleting a user by userid
  	* Author   : Retheesh
  	* Created  : 14/Aug/2006
  	* Modified : 25/Sep/2007 By Retheesh
  	*/
	
	
	
	
	function changeActive($current_act,$id)
	{
		if ($current_act=='Y')
		{
			$this->db->query("update member_master set `active`='N' WHERE id=$id");
			return true;
		}
		else
		{
			$this->db->query("update member_master set `active`='Y' WHERE id=$id");
			return true;
		}
	}

	/**
    * Function used for user authenticattion
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006
  	* Modified : 30/Oct/2007 By Retheesh
  	* Modified : 19/Nov/2007 By Retheesh
  	* Bug fixing for blank usernames
  	* Modified : 13/Feb/2008 By Retheesh
  	* Email activation modification
  	*/
	function authenticate($uname,$password,$store_id=-1)
	{
	
		$sql="select a.*,b.type from `member_master` a inner join `member_types` b on
		 a.mem_type=b.id where a.`username`='$uname' and a.`password`='$password' and a.mem_type=1";

		if  ($store_id>=0)
		{
			$sql.= " and a.from_store='$store_id'";

		}
		$checkRS = $this->db->get_results($sql);
		$u_det = $this->getUsernameDetails($uname);
		
		
		 if($_SESSION['email_confirm']== "N" && $u_det["active"] == 'N'){
		 			
			 setMessage("Invalid username or password! Try again",MSG_INFO);
		 	redirect(makeLink(array("mod"=>"member", "pg"=>"login") ));
			exit;
		   }
		if ( (count($checkRS)>0) && (trim($uname)!=""))
		{

			if ($u_det["reg_pack"]>0)
			{
				$sess_id = $this->startSession($checkRS[0]->id);
				$_SESSION["memberid"] = $checkRS[0]->id;
				$_SESSION["mem_type"] = $checkRS[0]->type;
				$_SESSION["mem_sess_id"] = $sess_id;
				$_SESSION["amt_paid"] = $checkRS[0]->amt_paid;
				$_SESSION["mem_active"]   = $checkRS[0]->active;
				if ($store_id>=0)
				{
					$_SESSION["from_store"] = $checkRS[0]->from_store;
				}

				if ($u_det["sub_pack"]>0)
				{
					if ($this->checkSubscription($checkRS[0]->id))
					{
						$_SESSION["sub_renew"] = "Y";
					}
					else
					{
						$_SESSION["sub_renew"] = "N";


					}
				}
				return true;
			}
			else
			{

				$sess_id = $this->startSession($checkRS[0]->id);
				$_SESSION["memberid"]=$checkRS[0]->id;
				$_SESSION["mem_type"]=$checkRS[0]->type;
				$_SESSION["mem_sess_id"] = $sess_id;
				$_SESSION["mem_active"]   = $checkRS[0]->active;
				if ($store_id>=0)
				{
					$_SESSION["from_store"] = $checkRS[0]->from_store;
				}
				return true;

			}
			
			
			
		}
		else
		{
		($this->config['registration_unique_field']) ? $unique_field = $this->config['registration_unique_field'] : $unique_field= "Username";
		
		$this->setErr("Invalid $unique_field or password! Try again");
		 /*if ($this->config['member_screen_name']=='Y')
		     {
			 if($u_det["active"] == 'N')
			 $this->setErr("Please Check your Email Id  for the Verification");
			 else
			  $this->setErr("Invalid $unique_field or password! Try again");
			 }else{
				$this->setErr("Invalid $unique_field or password! Try again");
		          }*/
		return false;
		}

	}

	/**
    * This function will change the status of flyers created by the user 
    * when deactivating a user, associated flyers name will be changed
  	* Author   : Shinu
  	* Created  : 16/Aug/2007
  	* Modified : 30/Oct/2007 By Retheesh
  	*/
	function changeFlyerStatus($current_act,$id)
	{
		$sql	=	"select flyer_id from flyer_data_basic where user_id='$id'";
		$rs = $this->db->get_results($sql);
		for($i=0;$i<count($rs);$i++)
		{
			$flyer_id			=	$rs[$i]->flyer_id;
			$act_flyer_name		=	$flyer_id.".html";
			$Deact_flyer_name	=	$flyer_id."XXX.html";
			$Active_File		=	SITE_PATH."/htmlflyers/".$act_flyer_name;
			$Deactive_File		=	SITE_PATH."/htmlflyers/".$Deact_flyer_name;
			if($current_act=='Y')
			{
				if(file_exists($Active_File))
				{ 	rename($Active_File, $Deactive_File); }
			}
			else
			{
				if(file_exists($Deactive_File))
				{ 	rename($Deactive_File, $Active_File); }
			}

		}
		return true;
	}


	/**
    * inserting to Profile Tables
  	* Author   : Retheesh
  	* Created  : 17/Aug/2006
  	* Modified : 30/Oct/2007 By Retheesh
  	*/
	function addEditProfile($uid,$table_name)
	{
		$sql="Select id from member_master where id=$uid";
		if (count($this->db->get_results($sql)>0))
		{
			
			$sql = "Select user_id from $table_name where user_id=$uid";
			$rs  = $this->db->get_results($sql);
			if (count($rs)==0)
			{
				$arrData = $this->getArrData();
				$arrData["user_id"]=$uid;
				$this->db->insert("$table_name",$arrData);
				return true;
			}
			else
			{
				
				$arrData = $this->getArrData();
				$this->db->update("$table_name",$arrData,"user_id=$uid");
				return true;

			}
		}
		else
		{
			$this->setErr("Session Expired!!.Please Re-Login to continue");
			return false;
		}
	}

	/**
    * Gettling a list of all Friend Type from the database
  	* Author   : Jipson Thomas
  	* Created  : 28/09/2007
  	* Modified : 28/09/2007 By Jipson Thomas
  	*/
	function getFriendType()
	{
		$sql = "Select type_id,type_name from friend_type";
		$rs= $this->db->get_results($sql,ARRAY_A);
		return $rs;
	}

	/**
    * Gettling a list of Countries from the database
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
	* Modified : 12/Dec/2007 By Shinu added  ORDER BY `country_name` ASC 
  	*/
	function listCountry()
	{
		$sql = "Select country_id, country_name, country_2_code, country_3_code from country_master ORDER BY `country_name` ASC ";
		$rs['country_id'] = $this->db->get_col($sql, 0);
		$rs['country_name'] = $this->db->get_col($sql, 1);
		$rs['country_2_code'] = $this->db->get_col($sql, 2);
		$rs['country_3_code'] = $this->db->get_col($sql, 3);
		return $rs;
	}

	/**
    * Gettling a list of States in U.S.A
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/
	function listStateUS()
	{
		$sql = "Select id,name from state_code where country_id = 840";
		$rs['id'] = $this->db->get_col($sql, 0);
		$rs['name'] = $this->db->get_col($sql, 1);
		return $rs;
	}

	/**
    * Getting as list of Ages from 18 to 99
  	* Author   : Jipson
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/
	function getAgeList()
	{
		$ag=array();
		for($i=18;$i<100;$i++)
		{
			$ag[]=$i;
		}
		return $ag;
	}

	/**
    * Fetching Country name from Country Id
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/
	function getCountryName($cid)
	{
		$sql = "Select country_name from country_master where country_id='$cid'";
		$rs= $this->db->get_results($sql,ARRAY_A);
		return $rs[0];
	}
	/**
    * Fetching Country2Code from Country Id
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/
	function getCountry2LetterCode($cid)
	{
		$sql = "SELECT country_2_code FROM country_master WHERE country_id='$cid'";
		return $this->db->get_var($sql);
	}
	/**
    * Fetching Details from Member Master and Custom fields using Userid
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/
	function getUserdetails($id)
	{
		list($qry,$table_id,$join_qry)=$this->generateQry('member_master','d','m');
		$sql="SELECT m.*,c.country_name,ma.*,$qry,m.id as id FROM `member_master` m LEFT JOIN `member_address` ma on
		      m.id=ma.user_id and ma.addr_type='master' left join country_master c 
			  ON(ma.country = c.country_id) $join_qry WHERE m.id='$id'";
		// print_r($sql);
		// exit;
		$RS = $this->db->get_results($sql,ARRAY_A);

	// print_r($sql);
		return $RS[0];
	}

	/**
    * Fetching Details from Member Master and Custom fields using Username
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/
	function getUsernameDetails($uname,$store_id='')
	{
		list($qry,$table_id)=$this->generateQry('member_master','d');

		$sql="SELECT m.*,ma.*,$qry,m.id as id FROM `member_master` m LEFT JOIN `member_address` ma on
		m.id=ma.user_id and ma.addr_type='master' left join country_master c 
		ON(ma.country = c.country_id) left join `custom_fields_list` d on m.id=d.table_key and d.table_id=$table_id WHERE   m.username='$uname'";
	
		if($this->config['payment_receiver']=='store' && $store_id!='')
		{
			$sql=$sql." and m.from_store='$store_id'";
		}
		$RS = $this->db->get_results($sql,ARRAY_A);
		return $RS[0];
	}

	function getEmailDetails($uname,$store_id='')
	{
		list($qry,$table_id)=$this->generateQry('member_master','d');

		$sql="SELECT * FROM member_master WHERE email='$uname'";

		$RS = $this->db->get_results($sql,ARRAY_A);
		return $RS[0];
	}
function getScreennameDetails($uname)
	{
		list($qry,$table_id)=$this->generateQry('member_master','d');

		$sql="SELECT m.*,ma.*,$qry,m.id as id FROM `member_master` m LEFT JOIN `member_address` ma on
		m.id=ma.user_id and ma.addr_type='master' left join country_master c 
		ON(ma.country = c.country_id) left join `custom_fields_list` d on m.id=d.table_key and d.table_id=$table_id WHERE   m.screen_name='$uname'";

		$RS = $this->db->get_results($sql,ARRAY_A);
		return $RS[0];
	}

	
	/**
    * add/edit links for a user
  	* Author   : Shinu
  	* Created  : 16/Aug/2006	
  	* Modified : 19/Nov/2007 By Shinu
  	*/
	function addeditLinks($req)
	{
		$user_id	=	$_SESSION["memberid"];
		$this->db->query("DELETE FROM member_links where user_id=$user_id");
		// appending http:// to urls
		$head_link1	=	$_POST['head_link1'];
		$pos1 = strpos($head_link1, "http://");
		if ($pos1 === false) {
			$head_link1 = str_replace("www.", "http://www.", $head_link1);
		}

		$head_link2	=	$_POST['head_link2'];
		$pos2 = strpos($head_link2, "http://");
		if ($pos2 === false) {
			$head_link2 = str_replace("www.", "http://www.", $head_link2);
		}

		$head_link3	=	$_POST['head_link3'];
		$pos3 = strpos($head_link3, "http://");
		if ($pos3 === false) {
			$head_link3 = str_replace("www.", "http://www.", $head_link3);
		}

		$footer_link1	=	$_POST['footer_link1'];
		$pos4 = strpos($footer_link1, "http://");
		if ($pos4 === false) {
			$footer_link1 = str_replace("www.", "http://www.", $footer_link1);
		}
		$footer_link2	=	$_POST['footer_link2'];
		$pos5 = strpos($footer_link2, "http://");
		if ($pos5 === false) {
			$footer_link2 = str_replace("www.", "http://www.", $footer_link2);
		}
		$footer_link3	=	$_POST['footer_link3'];
		$pos6 = strpos($footer_link3, "http://");
		if ($pos6 === false) {
			$footer_link3 = str_replace("www.", "http://www.", $footer_link3);
		}

		// end


		$array 			= 	array("head_title1"=>$_POST['head_title1'],"head_title2"=>$_POST['head_title2'],"head_title3"=>$_POST['head_title3'],
		"head_link1"=>$head_link1,"head_link2"=>$head_link2,"head_link3"=>$head_link3,
		"footer_title1"=>$_POST['footer_title1'],"footer_title2"=>$_POST['footer_title2'],"footer_title3"=>$_POST['footer_title3'],
		"footer_link1"=>$footer_link1,"footer_link2"=>$footer_link2,"footer_link3"=>$footer_link3,
		"user_id"=>$_SESSION["memberid"]);
		//print_r($array);exit;
		$this->db->insert("member_links", $array);
		return true;

	}

	/**
    * Getting Link Details of a user
  	* Author   : Unknown
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Unknown
  	*/
	function getLinkDetails($user_id)
	{
		$sql = "select * from member_links  where user_id='$user_id'";
		$rs = $this->db->get_row($sql);
		return $rs;
	}

	/**
    * Checking whether a particular user is valid or not by giving userid
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/
	function validUser($uid)
	{
		$sql="SELECT * FROM `member_master` where `id`=$uid";
		$count=count($this->db->get_results($sql));
		if($count==0)
		{
			$this->setErr("Invalid User");
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
    * Checking whether a particular user is valid or not by giving username
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/
	function validUsername($uname)
	{
		$sql="SELECT * FROM `member_master` where `username`='$uname'";
		$count=count($this->db->get_results($sql));
		if($count==0)
		{
			$this->setErr("Invalid User");
			return false;
		}
		else
		{
			return true;
		}
	}
	
	
	function validScreenname($uname)
	{
		$sql="SELECT * FROM `member_master` where `screen_name`='$uname'";
		$count=count($this->db->get_results($sql));
		if($count==0)
		{
			$this->setErr("Invalid User");
			return false;
		}
		else
		{
			return true;
		}
	}

	

	/**
    * Fetching Details from Profile Related Tables
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/
	function getPrfDetails($uid,$table_name)
	{
	
		$RS = $this->db->get_results("SELECT * FROM `$table_name` where `user_id`=$uid",ARRAY_A);
		return $RS[0];
	}

	/**
    * Returning all users (not as list) including custom fields. You can also search values by
    * giving those as paramerters.Search fields can be from custom fields also
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	* Modified : 21/Nov/2007 by Retheesh
  	* Fixed an error in Order by (Link 54 project)
  	*/
	function allUsers($search_fields='',$search_values='',$criteria='',$orderBy='',$orderCrt='',$limit='')
	{
		list($qry,$table_id)=$this->generateQry('member_master','d');
		if($search_fields)
		{
			list($qry_cs)=$this->getCustomQry('member_master',$search_fields,$search_values,$criteria,'m','d');
		}
        	if (isset($this->config['member_screen_name'])=='Y')
		{
		$sql="SELECT distinct(m.id), m.*,ma.*,$qry,c.country_name,m.id as id FROM `member_master` m LEFT JOIN `member_address` ma on
		m.id=ma.user_id and ma.addr_type='master' and m.username!='admin'  left join country_master c 
		ON(ma.country = c.country_id) left join `custom_fields_list` d on m.id=d.table_key and d.table_id=$table_id";
		}
		else
		{
		$sql="SELECT distinct(m.id), m.*,ma.*,$qry,c.country_name,m.id as id FROM `member_master` m LEFT JOIN `member_address` ma on
		m.id=ma.user_id and ma.addr_type='master' left join country_master c 
		ON(ma.country = c.country_id) left join `custom_fields_list` d on m.id=d.table_key and d.table_id=$table_id";
		}
		if($qry_cs)
		{
			$wh =retWhere($sql);
			$sql=$sql." $wh $qry_cs";
		}
			if (isset($this->config['member_screen_name'])=='Y')
		{
		$active="and m.active='Y'";
		$sql=$sql." $active";
		
		}
		if($orderBy)
		{
			$orderByAr  = explode(",",$orderBy);
			$orderCrt = explode(",",$orderCrt);
			list($search,$fields)= $this->getCustomFields('member_master',$orderByAr);
			
			for($i=0;$i<sizeof($fields);$i++)
			{
				($i>0) ? $cm = "," : $cm="";
				$orderQr = $orderQr.$cm."d.".$fields[$i]." ".$orderCrt[$i];
			}
			if (isset($orderQr)!='')
			{
				$sql=$sql." Order By ". $orderQr;
			}
			else 
			{
				$sql.=" ORDER BY ".$orderBy;
			}

		}
		if($limit)
		{
			$sql=$sql." limit ".$limit;
		}
		$rs = $this->db->get_results($sql);
		return $rs;

	}
	/**
    * Returning all users AS A LIST including custom fields. You can also search values by
    * giving those as paramerters.Search fields can be from custom fields also
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
	* Modified : 08/Nov/2007 By Salim
	* Description : Modified the query to get the store from which user has registered.
	* Modified : 01/Jan/2008 By Ratheesh kk modified the query of search with respect to  fields
  	*/
	function userList($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy,$stxt,$search_fields='',$search_values='',$criteria="=",$search_criteria='',$mem_type='')
	{	
		list($qry,$table_id,$join_qry)=$this->generateQry('member_master','d');
		
		 
		if($search_fields)
		{
			list($qry_cs)=$this->getCustomQry('member_master',$search_fields,$search_values,$criteria,'m','d');
		}
		
		
		if($stxt=="")
		{
			 $sql="SELECT m.*, m.email as emailaddress, ma.telephone as phone, ma.*,$qry,c.country_name,m.id as id,s.name AS storename,s.id as sid,s.txn_id FROM `member_master` m LEFT JOIN `member_address` ma on
			m.id=ma.user_id and ma.addr_type='master' left join country_master c 
			ON(ma.country = c.country_id) LEFT JOIN store s on s.user_id=m.id left join member_subscription ms ON ms.user_id=m.id $join_qry 
			";

		}
		else
		{
			if($search_criteria!=""){
				$search_by_fields= $search_criteria;
			}else{
				$search_by_fields=  "m.`username` like '%$stxt%' OR m.`first_name` like '%$stxt%' OR m.`last_name` like '%$stxt%' OR m.`email` like '%$stxt%'";
			}
			$sql="SELECT m.*, m.email as emailaddress, ma.telephone as phone, ma.*,$qry,c.country_name,m.id as id,s.name AS storename,s.id as sid,s.txn_id FROM `member_master` m LEFT JOIN `member_address` ma on
			m.id=ma.user_id and ma.addr_type='master' left join country_master c 
			ON(ma.country = c.country_id) LEFT JOIN store s on s.user_id =m.id left join member_subscription ms ON ms.user_id=m.id $join_qry
			 where ($search_by_fields)";
			
		}

		if($qry_cs)
		{
			$wh =retWhere($sql);
			$sql=$sql." $wh $qry_cs";
		}
		if($mem_type=='2')
		{
			$sql.="and s.deleted='N'";
		}
		//print_r($sql);exit;
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}

	/**
  	 * This function retrieves a fixed number of users in random
  	 * Author   : Retheesh
  	 * Created  : 25/Sep/2007
  	 * Modified : 25/Sep/2007 By Retheesh
  	 */
	function getRandomMemberList($mem_type='')
	{
		list($qry,$table_id)=$this->generateQry('member_master','d');

		$sql = "select m.*,$qry from member_master m LEFT JOIN `member_address` ma on
		m.id=ma.user_id and ma.addr_type='master' left join country_master c 
		ON(ma.country = c.country_id) left join `custom_fields_list` d on m.id=d.table_key and d.table_id=$table_id";

		if ($mem_type)
		{
			$sql .= " where m.mem_type=$mem_type and m.active='Y'";
		}

		$limit = $this->config["featured_mem_cnt"];

		if ($limit=="") $limit=10;

		$sql .= " order by rand() limit $limit";

		$rs  = $this->db->get_results($sql);

		for ($i=0;$i<sizeof($rs);$i++)
		{
			if($rs[$i]->genre)
			{
				$genres = explode(",",$rs[$i]->genre);

				$fav_genres = "";
				for ($j=0;$j<sizeof($genres);$j++)
				{
					$sql = "select category_name from master_category where category_id='{$genres[$j]}'";

					$rs1 = $this->db->get_row($sql);

					if ($rs1->category_name)
					{
						if ($j>0)
						{
							$fav_genres.=" / ".$rs1->category_name;
						}
						else
						{
							$fav_genres = $rs1->category_name;
						}
					}
				}
				if ($fav_genres=="") $fav_genres = ".";
				$rs[$i]->fav_genre = $fav_genres;
			}
			else
			{
				$rs[$i]->fav_genre = ".";
			}
		}
		return $rs;
	}

	/**
    * This function is used for member Searching.It checks for various criterias.
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/
	function profileList ($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$stxt,$uid='',$other='',$uname='',$dirFlg='',$search_field='',$search_value='',$tbindex=1,$crt="=")
	{
		list($qry,$table_id)=$this->generateQry('member_master','d');
		if (($_REQUEST["criteria"]=="username") || (!$_REQUEST["criteria"]))
		{
			$mem_type_condition = "and m.mem_type=1 AND m.active='Y'";
		
			$sql="SELECT m.*,ma.*,$qry,c.country_name,m.id as id FROM `member_master` m LEFT JOIN `member_address` ma on
			m.id=ma.user_id and ma.addr_type='master' left join country_master c 
			ON(ma.country = c.country_id) left join `custom_fields_list` d on m.id=d.table_key and d.table_id=$table_id where (m.`username` like '%$stxt%')  $mem_type_condition";
			if($dirFlg!='')
			{
				$sql=$sql. " and (d.dirflg=1)";
			}

		}
		elseif($_REQUEST["criteria"]=="name")
		{
		
		if ($this->config['member_screen_name']=='Y')
				{
					if($_SESSION['chps1']==2)
				  {
				  $mem_type_condition = "and m.mem_type=1 AND m.active='Y' ";
				  }
				  else
				  {
					$mem_type_condition = "and m.mem_type!=1 AND m.active='Y' ";
					}
				}
			$sql="SELECT m.*,ma.*,$qry,c.country_name,m.id as id FROM `member_master` m LEFT JOIN `member_address` ma on
			m.id=ma.user_id and ma.addr_type='master' left join country_master c 
			ON(ma.country = c.country_id) left join `custom_fields_list` d on m.id=d.table_key and d.table_id=$table_id
			where (m.`first_name` like '%$stxt%' or m.`last_name` like '%$stxt%') $mem_type_condition";
			if($dirFlg!='')
			{
				$sql=$sql. " and (d.dirflg=1)";
			}

		}
		elseif($_REQUEST["criteria"]=="email")
		{
		
		    if ($this->config['member_screen_name']=='Y')
				{
					if($_SESSION['chps1']==2)
				  {
				  $mem_type_condition = "AND m.mem_type=1 AND m.active='Y' ";
				  }
				  else
				  {
					$mem_type_condition = "AND m.mem_type!=1  AND m.active='Y' ";
					}
				}
			$sql="SELECT m.*,ma.*,$qry,c.country_name,m.id as id FROM `member_master` m LEFT JOIN `member_address` ma on
			m.id=ma.user_id and ma.addr_type='master' left join country_master c 
			ON(ma.country = c.country_id) left join `custom_fields_list` d on m.id=d.table_key and d.table_id=$table_id
			where (m.`email` like '%$stxt%') $mem_type_condition";
			if($dirFlg!='')
			{
				$sql=$sql. " and (d.dirflg=1)";
			}
		}
		

		if ($_REQUEST['influence'])
		{
			if ($search_field!='')
			{
				$field_arr = explode(",",$search_field);
				$crt_arr   = explode(",",$crt);
				for($i=0;$i<sizeof($field_arr);$i++)
				{
					if ($crt_arr[$i]=="")
					{
						$crt_arr[$i] = "=";
					}
				}
				$crt =  implode(",",$crt_arr);
				$search_field.= ",influence";
				$search_value.= ",".$_REQUEST['influence'];
				$crt .=",like";
			}
			else
			{
				$search_field = "influence";
				$search_value = $_REQUEST['influence'];
				$crt="like";
			}
		}
		if($search_field!='')
		{
			list($qry_cs)=$this->getCustomQry('member_master',$search_field,$search_value,$crt,'m','d');
			$sql = $sql." AND $qry_cs";

		}

		if($_REQUEST["frage"]>0)
		{
			$frage=$_REQUEST["frage"];
			$toage=$_REQUEST["toage"];
			$sql=$sql." AND (FLOOR((TO_DAYS(now()) - TO_DAYS(m.dob))/365)>=$frage and FLOOR((TO_DAYS(now()) - TO_DAYS(m.dob))/365)<=$toage)";
		}

		if($_REQUEST["country"]>0)
		{
			$cntid=$_REQUEST["country"];
			$sql=$sql." AND (ma.country=$cntid)";
		}

		if($_REQUEST["gender"]!='')
		{
			$gender=$_REQUEST["gender"];
			$sql=$sql. "AND (m.gender='$gender')";
		}
		if($_REQUEST["city"]!='')
		{
			$city=$_REQUEST["city"];
			$sql=$sql. "AND (ma.city='$city')";
		}
		if($_REQUEST["state"]!='')
		{
			$state=$_REQUEST["state"];
			$sql=$sql. "AND (ma.state='$state')";
		}
		if($_REQUEST["zip"]!='')
		{
			$zip=$_REQUEST["zip"];
			$sql=$sql. "AND (ma.postalcode='$zip')";
		}
		if($_REQUEST["exp_rate"]!='')
		{
			$exp_rate=$_REQUEST["exp_rate"];
			$sql=$sql. "AND (d.rate_exp=$exp_rate)";
		}

		if($_REQUEST["rel_status"]!='')
		{
			$rel_stat=$_REQUEST["rel_status"];
			$sql=$sql. "AND (d.rel_status='$rel_stat')";
		}
		if($_REQUEST["clb_flg"]!='')
		{
			$clb_flg=$_REQUEST["clb_flg"];
			$sql=$sql. "AND (d.clb_flg=1)";
		}

		if($_REQUEST["photo"])
		{
			$sql=$sql. " AND (m.image='Y')";
		}
		
		if($uid!='')
		{
			$sql=$sql. " AND (m.id!=$uid)";
		}
		if($_REQUEST['alphabetic'])
		{
			$sql=$sql. " AND m.`username` like'".$_REQUEST['alphabetic']."%'";
		}
		if($_REQUEST['screen_name'])
		{
			$sql=$sql. " AND m.`screen_name` like'".$_REQUEST['screen_name']."%'";
		}
		/*if($_REQUEST['type'])
		{
			$sql=$sql. " AND m.`mem_type` =".$_REQUEST['type'];
		}*/
		
		if (!$_REQUEST["criteria"])
		{
			$sql=$sql. " order by m.id desc";
		}
    	
		if($other!='')
		{
		   if ($this->config['member_screen_name']=='Y')
				{
					 if($_SESSION['chps1']==2)
				  {
				  $mem_type_condition = "AND m.mem_type=1  AND m.active='Y'";
				  }
				  else
				  {
					$mem_type_condition = "AND m.mem_type!=1 AND m.active='Y'";
					}
					
					
				}
		
			$sql="SELECT b.*,c.country_name FROM (`message_contacts`a inner join `member_master` b on
			a.friedid=b.username) LEFT JOIN `member_address` ma on 
			b.id=ma.user_id and ma.addr_type='master' left join country_master c 
			ON(ma.country = c.country_id) left join `custom_fields_list` d on b.id=d.table_key and d.table_id=$table_id
			where a.userid='$uname' $mem_type_condition";
		}
		
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}

	/**
    * Returns a list of contacts for a particular user.
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/
	function listContacts($uname)
	{
		$sql = "Select friedid from message_contacts where userid='$uname'";
		$rs['contact'] = $this->db->get_col($sql, 0);
		return $rs;
	}

	/**
    * Adding a contact for a particular user
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/	
	function addContact($uid,$table_name)
	{
		$sql="Select id from member_master where id=$uid";
		if (count($this->db->get_results($sql)>0))
		{
			$arrData = $this->getArrData();
			$sql="Select id from message_contacts where userid='".$arrData["userid"] ."' and friedid='".$arrData['friedid']."'";

			$count=count($this->db->get_results($sql));
			if ($count==0)
			{
				$this->db->insert("$table_name",$arrData);
				return true;
			}
			else
			{
				$this->setErr("This username already exists in your contact list");
				return false;
			}
		}
		else
		{
			$this->setErr("Session Expired!!.Please Re-Login to continue");
			return false;
		}
	}

	/**
    * Sending an internal message.
  	* Author   : Robin
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/	
	function sendMessage($copysent=0)
	{
		$arrData = $this->getArrData();
     	$this->db->insert("message",$arrData);
		$id = $this->db->insert_id;
		if($copysent==0)
		{
			$this->db->insert("message_sent",$arrData);
		}
		return $id;
	}

	/**
    * Fetching the count of Unread and total messages from Database.
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/	
	function getMsgCount($uname)
	{
		$RS = $this->db->get_row("SELECT count(id) as inbox FROM `message` where `to`='$uname' and status='U'",ARRAY_A);

		$RS1 = $this->db->get_row("SELECT count(id) as total FROM `message` where `to`='$uname'",ARRAY_A);

		$msg = $RS["inbox"] . " / " . $RS1["total"];
		return $msg;
	}

	/**
    * Fetching all categories for population a combo.
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/	
	function getCategories()
	{
		$sql = "Select category_id AS id,category_name AS cat_name from master_category";
		$rs['id'] = $this->db->get_col($sql, 0);
		$rs['cat_name'] = $this->db->get_col($sql, 1);
		return $rs;
	}


 function fluentSemesterlist()
	{
		$sql = "Select name from test_semester where deleted=0 and active=1";
		
		$rs['id'] = $this->db1->get_col($sql, 0);
		$rs['name'] = $this->db1->get_col($sql, 0);
		//$rs['cat_name'] = $this->db->get_col($sql, 1);
		//print_r($rs);
	    return $rs;
	}

function getfluentSemesterDate($str)
	{
		$sql = "Select CONCAT(startdate,' -> ', enddate) as date from test_semester where name='".$str."'";
	
		$rs = $this->db1->get_row($sql,ARRAY_A);
		//$rs['cat_name'] = $this->db->get_col($sql, 1);
       
		return $rs;
	}

	/**
    * Listing all categories.
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/	
	function listCategories()
	{
		$sql = "Select category_id AS id,category_name AS cat_name from master_category";
		$rs= $this->db->get_results($sql,ARRAY_A);
		return $rs;
	}

	/**
    * Listing all categories.
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/	
	function getCatName($catid)
	{
		$RS = $this->db->get_row("SELECT * FROM `master_category` where `category_id`=$catid",ARRAY_A);
		return $RS;
	}


	/**
    * Creating a Group.
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
	* Modified : 29/Nov/2007 By Jipson
  	*/	
	function createGroup($width='',$height='')
	{
		if($height==''){
			$height=100;
		}
		if($width==''){
			$width=100;	
		}
		$status='true';
		$msg=="";
		if(!$_POST['groupname'])
		{
			$status='false';
			$msg="Enter the group name";
		}
		if(!$_POST['description'])
		{
			$status='false';
			$msg="Enter the description";
		}
		if(!$_POST['category_id'])
		{
			$status='false';
			$msg="Please select the category";
		}
		
		if($status=='true')
		{
			$arrData = $this->getArrData();
			$sql="Select id from group_master where groupname='".$arrData["groupname"] ."'";

			$count=count($this->db->get_results($sql));
			if ($count==0)
			{
				$arr1=array();
				if ($_FILES["image"]["name"])
				{
					if (pictureFormat($_FILES["image"]["type"]))
					{
						$id=$this->db->insert("group_master",$arrData);
						$arr1["user_id"]=$arrData["user_id"];
						$arr1["joindate"]=$arrData["createdate"];
						$arr1["group_id"]=$id;
						$this->db->insert("group_members",$arr1);
						
						$extension = strstr($_FILES["image"]["name"],".");
						
						$dir=SITE_PATH."/modules/member/images/groupimages/";
						$thumbdir=$dir."thumb/";
						$redir=$dir."resized/";
						
						//uploadImage($_FILES['image'],$dir,$_FILES['image']['name'],1);
						
						uploadImage($_FILES['image'],$dir,"$id".$extension,1);
						chmod($dir."$id".$extension,0777);
						
						//print_r($dir.$_FILES['image']['name']);exit;
						//chmod($dir.$_FILES['image']['name'],0777);
						
						//thumbnail($dir,$dir,$_FILES['image']['name'],$width,$height,"","$id.jpg");
						//chmod($dir."$id.jpg",0777);
						
						thumbnail($dir,$thumbdir,"$id".$extension,100,100,"","$id".$extension);
						chmod($thumbdir."$id".$extension,0777);
						
						//@unlink($dir.$_FILES['image']['name']);
						return true;

					}
					else
					{
						$this->setErr("Unknown Picture Format!!");
						return false;
					}
				}
				else
				{

					$grid=$this->db->insert("group_master",$arrData);
					$arr1["user_id"]=$arrData["user_id"];
					$arr1["joindate"]=$arrData["createdate"];
					$arr1["group_id"]=$grid;
					$this->db->insert("group_members",$arr1);
					return true;
				}
			}
			else
			{
				$this->setErr("Group name already exists!!");
				return false;
			}
		}
		else
		{
			$this->setErr($msg);
			return false;
		}

	}
	/**
    * Returns list of groups.
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/	
	function groupList ($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$catid=0,$mgroup=0,$own=0,$userid=0,$stxt=0,$other='',$uid=0)
	{
	
		if ($mgroup==0)
		{
			$sort=$orderBy;
			$grpBy = false;
			if($sort=="discussion desc")
			{
				$tbl   = "group_discussion";
				$fldnm = "discussion";
				$fld   = "count(b.topicid)";
				$sql = "select a.*,$fld as $fldnm from `group_master` a inner join `$tbl` b on a.id=b.group_id";
				$grpBy = true;
			}
			elseif($sort=="members desc")
			{
				$tbl   = "group_members";
				$fldnm = "members";
				$fld   = "count(b.id)";
				$sql = "select a.*,$fld as $fldnm from `group_master` a inner join `$tbl` b on a.id=b.group_id";
				$grpBy = true;
			}
			elseif($sort=="groupname" || $sort=="createdate" || $sort=="b.username")
			{
				$tbl   = "member_master";
				$fldnm = "members";
				$fld   = "count(b.id)";
				$sql = "select a.*,c.category_name,$fld as $fldnm from `group_master` a left join `$tbl` b on a.user_id=b.id inner join master_category c on c.category_id=a.category_id";
				$grpBy = true;

			}
			else
			{

				$sql="select * from `group_master` a";
			}


			if($other!='')
			{
				$sql = "select a.* from `group_master` a inner join `group_members` b on a.id=b.group_id and b.user_id=$uid";

			}

			if($catid>0)
			{

				$sql=$sql." where a.category_id=$catid and a.type!='private'";
				if ($stxt!='')
				{
					$sql=$sql. " and (a.tags like '%$stxt%' or a.groupname like '%$stxt%')";
				}
			}
			else
			{
				$sql=$sql." where a.type!='private'";
				if ($stxt!='')
				{
					$sql=$sql. " and (a.tags like '%$stxt%' or a.groupname like '%$stxt%')";
				}
			}

		}
		else
		{

			if($own==0)
			{
				$sql = "select group_members.*,group_master.* from group_members inner join ";
				$sql = $sql. "group_master on group_members.group_id=group_master.id where " ;
				$sql = $sql. " group_members.user_id=$userid and group_members.active='Y'";
			}
			else
			{
				$sql = "select group_members.*,group_master.* from group_members inner join ";
				$sql = $sql. "group_master on group_members.group_id=group_master.id where " ;
				$sql = $sql. " group_master.user_id=$userid and group_members.user_id=$userid";

			}
		}

		if($grpBy == true)
		$sql .= " group by a.id";
		
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);

		foreach ($rs as $value)
		{
			for($i=0;$i<sizeof($value);$i++)
			{
				if($value[$i]->id>0)
				{
					$member=$this->getGroupMembers($value[$i]->id);
					$rs[0][$i]->members=$member["members"];
				}
			}
		}
		foreach ($rs as $value)
		{
			for($i=0;$i<sizeof($value);$i++)
			{
				if($value[$i]->id>0)
				{
					$dis=$this->getGroupDiscussions($value[$i]->id);
					$rs[0][$i]->discussions=$dis["discussions"];
				}
			}
		}
		
		return $rs;
		
	}

	/**
    * Fetching group details by group id.
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/	
	function getGroupDetails($grid)
	{
		$sql="select * from group_master where id=$grid";
		$RS = $this->db->get_results("select * from group_master where id=$grid",ARRAY_A);
		return $RS[0];
	}

	/**
    * Checking whether a group exists or not by taking group id as parameter.
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/	
	function validGroup($gid)
	{
		$sql="SELECT * FROM `group_master` where `id`=$gid";
		$count=count($this->db->get_results($sql));
		if($count==0)
		{
			$this->setErr("Invalid Group");
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
    * Fetching count of groups for a userid.
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/	
	function getGroupCount($uid)
	{
		$sql = "select b.id from `group_master` a inner join `group_members` b on a.id=b.group_id and b.user_id=$uid where a.type!='private'";
		$RS = $this->db->get_results($sql,ARRAY_A);
		return count($RS);
	}

	/**
    * Fetching count of contacts for a userid.
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/	
	function getContactCount($uname)
	{
		$sql = "select a.id from `message_contacts` a where a.userid='$uname'";
		$RS = $this->db->get_results($sql,ARRAY_A);
		return count($RS);
	}

	/**
    * Fetching count of contacts for a userid.
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/	
	function getGroupMembers($grid)
	{
		$sql="select group_master.id,group_master.groupname,count(group_members.user_id) ";
		$sql=$sql."as members from group_master left join group_members on ";
		$sql=$sql."group_master.id=group_members.group_id where group_master.id=$grid and group_members.active='Y' group by group_master.id";

		$RS = $this->db->get_results($sql,ARRAY_A);
		return $RS[0];
	}

	/**
    * Fetching count of contacts for a userid.
  	* Author   : Retheesh
  	* Created  : 16/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/	
	function getGroupDiscussions($grid)
	{
		$sql="select group_master.id,group_master.groupname,count(group_discussion.topic) ";
		$sql=$sql."as discussions from group_master left join group_discussion on ";
		$sql=$sql."group_master.id=group_discussion.group_id where group_master.id=$grid group by group_master.id";
		$RS = $this->db->get_results($sql,ARRAY_A);
		return $RS[0];
	}

	/**
    * Fetching details of a Discussion topic.
  	* Author   : Retheesh
  	* Created  : 21/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/	
	function getTopicDetails($tpid)
	{
		$sql="select group_discussion.*,member_master.username from ";
		$sql=$sql."group_discussion INNER JOIN member_master ON group_discussion.user_id=";
		$sql=$sql."member_master.id where group_discussion.topicid=$tpid  group by group_discussion.topicid";
		//print_r($sql);exit;
		$RS = $this->db->get_results($sql,ARRAY_A);
		return $RS[0];
	}

	/**
    * Fetching a list of all Topics.
  	* Author   : Retheesh
  	* Created  : 21/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/	
	function topicList ($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$grid)
	{
	
		if ($this->config['member_screen_name']=='Y')
		{
			$member_search_fields = "member_master.screen_name as author";
		}
		else 
		{				$member_search_fields = "member_master.username as author ";
		
		}

	
		$sql = "SELECT group_discussion.topicid,group_discussion.topic,group_discussion.group_id, $member_search_fields ";
		$sql = $sql.",Count(group_reply.content) AS posts,group_discussion.lastpost FROM ";
		$sql = $sql."(group_master INNER JOIN (group_discussion INNER JOIN group_reply ON ";
		$sql = $sql."group_discussion.topicid = group_reply.topicid) ON group_master.id = group_discussion.group_id) ";
		$sql = $sql."INNER JOIN member_master ON group_discussion.user_id = member_master.Id ";
		$sql = $sql."where group_discussion.group_id=$grid GROUP BY group_discussion.topicid, member_master.first_name";
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);

		return $rs;
	}

	/**
    * Creating a topic.
  	* Author   : Retheesh
  	* Created  : 21/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/	
	function createTopic()
	{
		$arrData= $this->getArrData();
		
		unset($arrData['content']);

		$sql="Select topicid from group_discussion where group_id=".$arrData["group_id"] ." and topic='".$arrData['topic']."'";
		$count=count($this->db->get_results($sql));
		if($count==0)
		{
			$topicid=$this->db->insert("group_discussion",$arrData);
			$arrData= $this->getArrData();
			unset($arrData['topic'],$arrData['group_id']);
			$arrData['topicid']=$topicid;
			$this->db->insert("group_reply",$arrData);
			return true;
		}
		else
		{
			$this->setErr("A Topic with same name already exists in this Group!!");
		}
	}

	/**
    * Fetching list of replies of a topic.
  	* Author   : Retheesh
  	* Created  : 21/Aug/2006	
  	* Modified : 12/Nov/2007 By Jipson Thomas.
  	*/	
	function replyList ($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$tpid)
	{
		$sql="select group_reply.*,member_master.* from ";
		$sql=$sql."group_reply INNER JOIN member_master ON group_reply.user_id=";
		$sql=$sql."member_master.id where group_reply.topicid=$tpid";
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}

	/**
    * Posting a reply.
  	* Author   : Retheesh
  	* Created  : 21/Aug/2006	
  	* Modified : 30/Oct/2007 By Retheesh
  	*/	
	function postReply()
	{
		$arrData= $this->getArrData();
		$this->db->insert("group_reply",$arrData);
		return true;
	}

	/**
    * Posting a reply.
  	* Author   : Retheesh
  	* Created  : 21/Aug/2006	
  	* Modified : 02/Nov/2007 By Retheesh
  	*/	
	function memberList ($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$grpid)
	{
		list($qry,$table_id,$joinqry)=$this->generateQry('member_master','d');
		//print_r($joinqry);
		$sql="SELECT m.*,a.joindate,$qry FROM `group_members` a inner join `member_master` m ";
		$sql=$sql." on a.user_id=m.id $joinqry where a.group_id=$grpid order by a.joindate desc";
//print_r($sql);exit;
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}

	/**
    * Join a group
  	* Author   : Retheesh
  	* Created  : 21/Aug/2006	
  	* Modified : 02/Nov/2007 By Retheesh
  	*/	
	function joinGroup()
	{
		$arrData= $this->getArrData();
		
		if($this->checkGroupMember($arrData['group_id'],$arrData['user_id'],1))
		{
			$this->setErr("You are already a member of this Group");
			return false;
		}
		else
		{
			$id=$this->db->insert("group_members",$arrData);
			return $id;
		}
	}

	/**
    * Checking Group Owner
  	* Author   : Retheesh
  	* Created  : 21/Aug/2006	
  	* Modified : 02/Nov/2007 By Retheesh
  	*/	
	function checkGroupOwner($grid,$uid)
	{
		$sql="Select id from group_master where id=$grid and user_id=$uid";
		$count=count($this->db->get_results($sql));
		if ($count==0)
		{
			return false;
		}
		else
		{
			return true;
		}

	}

	/**
    * Checking whether that user id is a member of that group
  	* Author   : Retheesh
  	* Created  : 21/Aug/2006	
  	* Modified : 02/Nov/2007 By Retheesh
  	*/	
	function checkGroupMember($grid,$uid,$act=0)
	{
		if ($act==0)
		{
			$sql="Select id from group_members where group_id=$grid and user_id=$uid and active='Y'";
		}
		else
		{
			$sql="Select id from group_members where group_id=$grid and user_id=$uid";
		}
		$count=count($this->db->get_results($sql));
		if ($count==0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
    * To leave a group
  	* Author   : Retheesh
  	* Created  : 21/Aug/2006	
  	* Modified : 02/Nov/2007 By Retheesh
  	*/	
	function leaveGroup()
	{
		$arrData= $this->getArrData();
		$sql="DELETE from group_members where group_id=". $arrData['group_id']." and user_id=".$arrData['user_id'];
		$this->db->query($sql);
		return true;
	}

	/**
    * Accept an Invite to join a group 
  	* Author   : Retheesh
  	* Created  : 21/Aug/2006	
  	* Modified : 02/Nov/2007 By Retheesh
  	*/	
	function acceptInvite($userid,$grid)
	{
		$arrData = array();
		$arrData["user_id"]  = $userid;
		$arrData["group_id"] = $grid;
		$arrData["joindate"] = date("Y-m-d G:i:s");
		$arrData["active"]   = "Y";
		if($this->checkGroupMember($arrData['group_id'],$arrData['user_id']))
		{
			$this->setErr("You are already a member of this Group");
			return false;
		}
		else
		{
			$this->db->insert("group_members",$arrData);
			return true;
		}
	}

	/**
    * Accept a request from a user to join group
  	* Author   : Retheesh
  	* Created  : 21/Aug/2006	
  	* Modified : 02/Nov/2007 By Retheesh
  	*/	
	function acceptRequest($userid,$grid,$id)
	{
		$arrData = array();
		$arrData["active"]   = "Y";
		if($this->checkGroupMember($grid,$userid,1))
		{
			$this->db->update("group_members",$arrData ,"id=$id");
			return true;
		}
		else
		{
			$this->setErr("Join Request could not be found");
			return false;
		}
	}

	/**
    * Change display picture of a member
  	* Author   : Retheesh
  	* Created  : 21/Aug/2006	
  	* Modified : 02/Nov/2007 By Retheesh
  	*/	
	function changePhoto()
	{
		$image=basename($_FILES['image']['name']);
		if ($image)
		{
			if (pictureFormat($_FILES["image"]["type"]))
			{
				$myId=$_SESSION["memberid"];
				$dir=SITE_PATH."/modules/member/images/userpics/";
				$thumbdir=$dir."thumb/";
				uploadImage($_FILES['image'],$dir,$_FILES['image']['name'],1);
				chmod($dir.$_FILES['image']['name'],0777);
				thumbnail($dir,$thumbdir,$_FILES['image']['name'],100,100,"","$myId.jpg");
				chmod($thumbdir."$myId.jpg",0777);
				$arrData=array();
				$arrData["image"]="Y";
				$this->db->update("member_master",$arrData ,"id=$myId");
				@unlink(SITE_PATH."/modules/member/images/userpics/".$_FILES['image']['name']);
				return true;
			}
			else
			{
				$this->setErr("Unknown Picture Format");
			}
		}
		else
		{
			$this->setErr("Please select an Image to Upload");
		}

	}

	/**
    * removing display picture of a member
  	* Author   : Retheesh
  	* Created  : 21/Aug/2006	
  	* Modified : 02/Nov/2007 By Retheesh
  	*/		
	function removePicture()
	{
		$myId=$_SESSION["memberid"];
		$arrData=array();
		$arrData["image"]="N";
		$this->db->update("member_master",$arrData ,"id=$myId");
		return true;
	}

	/**
    * Loading Home Media Details
  	* Author   : Jipson
  	* Created  : 22/Aug/2007	
  	* Modified : 22/Aug/2007 By Jipson
  	*/	
	function loadHomeMediaDet($tbl_name,$sort_field,$fld='',$value='')
	{
		if ($fld)
		{
			$sql = "Select * from `$tbl_name` where privacy='public' and $fld='$value' order by $sort_field desc limit 4";
		}
		else
		{
			$sql = "Select * from `$tbl_name` where privacy='public' order by $sort_field desc limit 4";
		}
		$RS = $this->db->get_results($sql,ARRAY_A);
		return $RS;
	}

	/**
    * Fetching user details by username or email
  	* Author   : Retheesh
  	* Created  : 22/Aug/2006	
  	* Modified : 02/Nov/2007 By Retheesh
  	*/	
	function getUserPass($uname,$email,$store_id='',$manage='',$storename='')
	{
		
		if($store_id>0){
			$condition = " AND from_store = $store_id";
		}
		else
		{
			$condition = " AND from_store =0 ";
		}
		
		if($uname) {
			 $sql = "Select * from `member_master` where `username`='$uname' and email='$email'"; }
			else {
			   if($manage!='')
					$sql="select b.* from store a inner join member_master b on a.user_id=b.id  where a.id='$store_id' and b.email='$email' ";
				else if($store_id!='')
					$sql="Select * from `member_master` where email='$email' and from_store='$store_id' and mem_type=1";
				else
					$sql = "Select * from `member_master` where email='$email' ".$condition;
				 }
				$RS = $this->db->get_results($sql,ARRAY_A);
				return $RS[0];
	}

	/**
    * Making a member active
  	* Author   : Retheesh
  	* Created  : 22/Aug/2006	
  	* Modified : 02/Nov/2007 By Retheesh
  	*/	
	function makeActive($uid)
	{
		$arr=array();
		$arr["active"]="Y";
		$this->db->update("member_master",$arr,"id=$uid");
		return true;
	}

	/**
    * Fetching caregories as an Array
  	* Author   : Retheesh
  	* Created  : 22/Aug/2006	
  	* Modified : 02/Nov/2007 By Retheesh
  	*/	
	function getCategoryArr($module='', $orderBy=0)
	{
		if($orderBy==0)
		{
			$sort = "a.category_id";
		}
		else
		{
			$sort = "a.category_name";
		}

		if ($module!='')
		{
			$sql = "SELECT id FROM module WHERE name = '$module'";
			$rs = $this->db->get_row($sql,ARRAY_A);
			$sql = "Select a.category_id AS id,a.category_name AS cat_name from master_category a inner join category_modules b on ";
			$sql = $sql . " a.category_id=b.category_id and b.module_id ='{$rs["id"]}' order by $sort";
		}
		else
		{
			$sql = "Select a.category_id AS id,a.category_name AS cat_name from master_category a order by $sort";
		}
		$rs= $this->db->get_results($sql,ARRAY_A);
		return $rs;
	}

	/**
    * Fething latest members of that website
  	* Author   : Unknown
  	* Created  : 22/Aug/2006	
  	* Modified : 02/Nov/2007 By Retheesh
  	*/	
	function getNewMembers($limit=4)
	{
		$sql = "select * from member_master order by id DESC limit 0, $limit";
		$rs= $this->db->get_results($sql,ARRAY_A);
		return $rs;
	}

	/**
    * to get the different type of members
  	* Author   : Unknown
  	* Created  : 22/Aug/2006	
  	* Modified : 02/Nov/2007 By Unknown
  	*/	
	function getmembertype($type)
	{
		$sql = "select * from member_master where mem_type = $type order by id DESC ";
		$rs= $this->db->get_results($sql,ARRAY_A);
		return $rs;
	}
	/**
    * Fetching competition winners from competition tables
  	* Author   : Retheesh
  	* Created  : 23/Aug/2006	
  	* Modified : 02/Nov/2007 By Retheesh
  	*/	
	function competitionWinners($id)
	{
		$sql = "SELECT
				`competition_winners`.`cmp_id`,
				`competition_winners`.`user_id`,
				`competition_winners`.`id`,
				`member_master`.`image`
				FROM
				`member_master`
				Inner Join `competition_winners` ON `competition_winners`.`user_id` = `member_master`.`id`
				Inner Join `competition_head` ON `competition_winners`.`cmp_id` = `competition_head`.`id`
				WHERE
				`competition_head`.`category_id` =  '$id'
				ORDER BY `competition_winners`.`id` DESC
				LIMIT 0, 1";

		$rs= $this->db->get_results($sql,ARRAY_A);
		return $rs;
	}
	/**
    * Fething categories to populate combo box
  	* Author   : Retheesh
  	* Created  : 23/Aug/2006	
  	* Modified : 02/Nov/2007 By Retheesh
  	*/	
	function getCategoryCombo($module='', $orderBy=0)
	{
		if($orderBy==0)
		{
			$sort = "a.category_id";
		}
		else
		{
			$sort = "a.category_name";
		}

		if ($module!='')
		{
			$sql = "SELECT id FROM module WHERE folder = '$module'";
			$rs = $this->db->get_row($sql,ARRAY_A);


			$sql = "Select a.category_id AS id,a.category_name AS cat_name from master_category a inner join category_modules b on ";
			$sql = $sql . " a.category_id=b.category_id and b.module_id ='{$rs['id']}' order by $sort";
		}
		else
		{
			$sql = "Select a.category_id AS id,a.a.category_name AS cat_name from master_category a order by $sort";
		}

		$rs['id'] = $this->db->get_col($sql, 0);
		$rs['cat_name'] = $this->db->get_col($sql, 1);
		return $rs;
	}

	/**
    * Inserting Profile Comments
  	* Author   : Robin
  	* Created  : 23/Aug/2006	
  	* Modified : 23/Aug/2006 By Robin
	* Modified : 15/july/2008 By Adarsh
  	*/
	function insertComment($req, $Id,$type='')
	{
		extract($req);
		if($type==''){
			$type='profile';
		}
		else{
			$type='updates';
		}
		$cid=$_SESSION['memberid'];
		$date=date("Y-m-d H:i:s");
		$array = array("type"=>$type,"file_id"=>$Id,"user_id"=>$cid,"comment"=>$comment,"postdate"=>$date);
		$this->db->insert("media_comments", $array);
    	return true;

	}

	/**
    * Inserting a Message to Tips Table
  	* Author   : Retheesh
  	* Created  : 23/Aug/2006	
  	* Modified : 23/Aug/2006 By Retheesh
  	*/
	function insertTip()
	{
		$arr=$this->getArrData();
		$this->db->insert("tip_msg",$arr);
		return true;
	}

	/**
    * Fetching a message from Tips table
  	* Author   : Retheesh
  	* Created  : 23/Aug/2006	
  	* Modified : 23/Aug/2006 By Retheesh
  	*/
	function getTip($start=0,$end=5,$uid) {
		$sql		= "Select a.*,b.username,b.image from tip_msg a inner join member_master b
					  on a.user_id=b.id where b.id = $uid order by a.id desc limit $start,$end";	
		$count = $this->db->query("Select a.*,b.username from tip_msg a inner join member_master b
					  on a.user_id=b.id ");			

		$rs = $this->db->get_results($sql);
		return array($rs,$count);
	}

	/**
    * Fetching comments from Media Comments Table
  	* Author   : Retheesh
  	* Created  : 23/Aug/2006	
  	* Modified : 23/Aug/2006 By Retheesh
  	* Modified : 10/dec/2007 By Retheesh
  	* Added field member_master.image in query
  	*/
	function getMessage($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy,$uid ) {
	
	if ($this->config['member_screen_name']=='Y')
		{
			$member_search_fields = "`member_master`.`username`,`member_master`.`screen_name`,`member_master`.`image`,`member_master`.`mem_type`,";
		}
		else 
		{				$member_search_fields = "`member_master`.`username`,";
		
		}
	
		$cid=$_SESSION['memberid'];
		$sql		= "SELECT
						$member_search_fields
						`member_master`.`image`,
						`media_comments`.`id`,
						`media_comments`.`type`,
						`media_comments`.`file_id`,
						`media_comments`.`user_id`,
						`media_comments`.`comment`,
						`media_comments`.`postdate`
						FROM
						`member_master`
						Inner Join `media_comments` ON `member_master`.`id` = `media_comments`.`user_id`
						WHERE `media_comments`.`type`='profile' and `media_comments`.`file_id`='$uid' order by `media_comments`.`postdate` DESC";


		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}

	/**
	* Fetching a message from Tips table
	* Author   : Retheesh
	* Created  : 23/Aug/2006	
	* Modified : 23/Aug/2006 By Retheesh
	*/
	function getDenominations()
	{
		$sql = "Select a.id,a.dnm_det from denomination_master a order by a.dnm_det";
		$rs['id'] = $this->db->get_col($sql, 0);
		$rs['dnm_det'] = $this->db->get_col($sql, 1);
		return $rs;
	}

	/**
	* Getting denomination from denomination table
	* Author   : Retheesh
	* Created  : 23/Aug/2006	
	* Modified : 23/Aug/2006 By Retheesh
	*/
	function getDnm($id)
	{
		$sql = "select dnm_det from `denomination_master` where id=$id";
		$rs = $this->db->get_col($sql,0);
		return $rs[0];
	}

	/**
	* Fetching questions from Secret Questions
	* Author   : Retheesh
	* Created  : 23/Aug/2006	
	* Modified : 23/Aug/2006 By Retheesh
	*/
	function getQns()
	{
		$sql="select id,qn from `secret_qn`";
		$rs['id']=$this->db->get_col($sql,0);
		$rs['qn']=$this->db->get_col($sql,1);
		return $rs;

	}

	/**
	* Add/Edit Credit card Details
	* Author   : Unknown
	* Created  : Unknown
	* Modified : Unknown By Unknown
	*/
	function addeditCreditcard($cArray)
	{
		$user_id = $_SESSION["memberid"];
		$sql = "select * from member_creditcard_details where user_id='$user_id' ";
		$rs = $this->db->get_row($sql);

		if(count($rs)>0)
		{
			$this->db->update("member_creditcard_details",$cArray,"user_id='$user_id'");
			return true;
		}
		else
		{
			$id=$this->db->insert("member_creditcard_details",$cArray);
			return true;
		}
	}

	/**
	* Getting credit card details
	* Author   : Unknown
	* Created  : Unknown
	* Modified : Unknown By Unknown
	*/
	function getCcardDetails($user_id)
	{
		$rs = $this->db->get_row("SELECT * FROM member_creditcard_details WHERE user_id=$user_id", ARRAY_A);
		return $rs;
	}

	/**
	* Inserting address to member Address table
	* Author   : Retheesh
	* Created  : 23/Aug/2006
	* Modified : 23/Aug/2006 By Retheesh
	*/	
	function insertAddress()
	{
		$arr=$this->getArrData();
		$user_id = $arr["user_id"];
		$type    = $arr["addr_type"];
		$sql = "select * from member_address where user_id='$user_id' and
		addr_type='$type'";
		$rs = $this->db->get_row($sql);
		if(count($rs)>0)
		{
			$this->db->update("member_address",$arr,"user_id='$user_id' and addr_type='$type'");
			return true;
		}
		else
		{
			$id=$this->db->insert("member_address",$arr);
			//print_r($arr);
			return $id;
		}

	}

	/**
	* Getting address from Member Address
	* Author   : Retheesh
	* Created  : 24/Aug/2006
	* Modified : 24/Aug/2006 By Retheesh
	*/	
	function getAddress($id='',$uid='',$type='master')
	{
		if($id)
		{
			$sql = "select a.*,b.country_name from `member_address` a inner join `country_master` b on
					a.country=b.country_id where id=$id";
		}
		else
		{
			$sql = "select a.*,b.country_name from `member_address` a inner join `country_master` b on
					a.country=b.country_id where addr_type='$type' and user_id=$uid";
		}
		$rs  = $this->db->get_results($sql,ARRAY_A);
		return $rs[0];
	}

	/**
	* Deleting address from Member Address
	* Author   : Retheesh
	* Created  : 24/Aug/2006
	* Modified : 24/Aug/2006 By Retheesh
	*/	
	function delAddress($id)
	{
		$sql = "delete from `member_address` where id=$id and addr_type!='master'";
		$this->db->query($sql);
		return true;
	}

	/**
	* unset fields 
	* Author   : Shinu
	* Created  : 13/NOV/2007
	* Modified : 13/NOV/2007 By Shinu
	*/	
	function insertDetails($UserAddr,$BillingAddr,$ShippingAddr)
	{
		$myId	=	$this->db->insert("member_master", $UserAddr);
		$BillingAddr["user_id"]		=	$myId;
		$BillingAddr["addr_type"]	=	"master";

		$this->db->insert("member_address", $BillingAddr);
		$BillingAddr["addr_type"]	=	"billing";
		$this->db->insert("member_address", $BillingAddr);
		$ShippingAddr["user_id"]	=	$myId;
		$this->db->insert("member_address", $ShippingAddr);
		return $myId;
	}

	/**
	* unset fields 
	* Author   : Shinu
	* Created  : 12/NOV/2007
	* Modified : 12/NOV/2007 By Shinu
	*/	
	function unsetFields()
	{
		$sql = "select field  from `unset_fields` ";
		$rs = $this->db->get_results($sql,ARRAY_A);
		if(count($rs)>0)
		{
			for($i=0;$i<count($rs);$i++) {
				$Field	=	$rs[$i]['field'];
				if(isset($_REQUEST[$Field]))
				{
					unset($_POST[$Field	]);
				}
			}
		}

		return true;
	}


	/**
	* Updating Member Address
	* Author   : Retheesh
	* Created  : 24/Aug/2006
	* Modified : 24/Aug/2006 By Retheesh
	*/	
	function updateAddress($id='',$uid='')
	{
		$arr = $this->getArrData();
		if ($id!='')
		{
			$this->db->update("member_address",$arr,"id=$id");
			return true;
		}
		else
		{
			$this->db->update("member_address",$arr,"addr_type='master' and user_id=$uid");
			return true;
		}
	}

	/**
	* Generate Hours
	* Author   : Retheesh
	* Created  : 24/Aug/2006
	* Modified : 24/Aug/2006 By Retheesh
	*/	
	function generateHrs()
	{
		$arr = array();
		for($i=1;$i<=12;$i++)
		{
		($i<10) ? $val="0".$i : $val=$i	;
		$arr[]= $val;
		}

		return $arr;
	}

	/**
	* Generating Minutes
	* Author   : Retheesh
	* Created  : 24/Aug/2006
	* Modified : 24/Aug/2006 By Retheesh
	*/	
	function generateMinutes()
	{
		$arr=array();
		for($i=0;$i<60;$i++)
		{
		($i<10) ? $val="0".$i : $val=$i	;
		$arr[] = $val;
		}
		return $arr;
	}

	/**
	* Inserting payment track
	* Author   : Retheesh
	* Created  : 24/Aug/2006
	* Modified : 24/Aug/2006 By Retheesh
	*/	
	function insertPaymentTrack()
	{
		$arr=$this->getArrData();
		$this->db->insert("payment_track",$arr);
		return true;
	}

	/**
	* Fetching addresses for a Paricular user
	* Author   : Retheesh
	* Created  : 24/Aug/2006
	* Modified : 24/Aug/2006 By Retheesh
	*/	
	function getAddresses($uid, $type="")
	{
		$sql = "select * from `member_address` where user_id=$uid";
		if($type) {
			$sql .= " and addr_type='$type'";
		}
		$rs  = $this->db->get_results($sql,ARRAY_A);
		return $rs;
	}

	/**
	* Returning time difference(Days,Hours,Minutes,seconds) from Start and End time
	* Author   : Retheesh
	* Created  : 24/Aug/2006
	* Modified : 24/Aug/2006 By Retheesh
	*/	
	function get_time_difference( $start, $end,$daysToHrs=0)
	{
		$uts['start']      =    strtotime( $start );
		$uts['end']        =    strtotime( $end );
		if( $uts['start']!==-1 && $uts['end']!==-1 )
		{

			if( $uts['end'] >= $uts['start'] )
			{
				$diff    =    $uts['end'] - $uts['start'];
				if( $days=intval((floor($diff/86400))) )
				$diff = $diff % 86400;
				if( $hours=intval((floor($diff/3600))) )
				$diff = $diff % 3600;
				if( $minutes=intval((floor($diff/60))) )
				$diff = $diff % 60;
				$diff    =    intval( $diff );

				if ($daysToHrs==0)
				{
					return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
				}
				else
				{
					if($days>0)
					{
						$hr = $days * 24;
						$hours = $hours + $hr;
					}
					return( array('hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
				}
			}
			else
			{
				$this->setErr( "Ending date/time is earlier than the start date/time");
			}
		}
		else
		{
			$this->setErr("Invalid date/time data detected");
		}
		return( false );
	}

	/**
	* Starting a session
	* Author   : Retheesh
	* Created  : 24/Aug/2006
	* Modified : 24/Aug/2006 By Retheesh
	*/	
	function startSession($user_id)
	{
	($this->ip_det['country']=="")? $this->ip_det["country"]="Unknown" : $this->ip_det["country"]= $this->ip_det["country"];
	($this->ip_det['city']=="")? $this->ip_det["city"]="Unknown" : $this->ip_det["city"]= $this->ip_det["city"];
	$this->db->query("update session_det set status='N' where user_id=$user_id");
	$arr = array();
	$arr["user_id"]    = $user_id;
	$arr["ip_address"] = $_SERVER['REMOTE_ADDR'];
	$arr["login_time"] = date("Y-m-d H:i:s");
	$arr["country"]    = $this->ip_det["country"];
	$arr["city"]       = $this->ip_det["city"];
	$arr["status"]     = "Y";
	$id = $this->db->insert("session_det",$arr);
	return $id;
	}

	/**
	* Starting a session
	* Author   : Retheesh
	* Created  : 24/Aug/2006
	* Modified : 24/Aug/2006 By Retheesh
	*/	
	function updateSession()
	{
		$sess_id = $_SESSION["mem_sess_id"];
		if($sess_id)
		{
			$sql = "select * from session_det where id='$sess_id'";
			$rs  = $this->db->get_row($sql);
			if($rs->status=='Y')
			{
				$arr = array();
				$arr["logout_time"] = date("Y-m-d H:i:s");
				$this->db->update("session_det",$arr,"id=$sess_id");
			}
			else
			{
				setMessage('You have been logged out because you logged in to other computer');
				unset($_SESSION["memberid"],$_SESSION["mem_type"],$_SESSION["mem_sess_id"]);

			}
		}
	}

	/**
	* This function is triggered when a user logs out
	* Author   : Retheesh
	* Created  : 24/Aug/2006
	* Modified : 24/Aug/2006 By Retheesh
	*/	
	function logoutSession($sess_id)
	{
		$this->updateSession($sess_id);
		$sql = "update session_det set status='N' where id=$sess_id";
		$this->db->query($sql);
		return true;
	}

	/**
	* This fetches current status and last login time
	* Author   : Retheesh
	* Created  : 24/Aug/2006
	* Modified : 24/Aug/2006 By Retheesh
	*/	
	function getLastSession($user_id)
	{
		$sql = "select * from session_det where user_id=$user_id order by id desc limit 2";
		$rs  = $this->db->get_results($sql);

		$arr = array();

		if($rs[0]->status=='Y')
		{
			$last_time = $rs[0]->logout_time;
			$diff = $this->get_time_difference($last_time,date("Y-m-d H:i:s"),1);
			if($diff["hours"]>0)
			{
				$arr["status"] = "Offline";
				$arr["last_login"] =  $rs[0]->login_time;
			}
			elseif($diff["minutes"]>$this->config["max_idle_time"])
			{
				$arr["status"] = "Offline";
				$arr["last_login"] =  $rs[0]->login_time;
			}
			else
			{
				$arr["status"] = "Online";
				$arr["last_login"] =  $rs[1]->login_time;
			}
		}
		else
		{
			$arr["status"] = "Offline";
			$arr["last_login"] =  $rs[0]->login_time;
		}
		return $arr;
	}

	/**
	* Function for getting the last login details of a user
	* Author   : Jipson Thomas
	* Created  : 18/Oct/2007
	* Modified : 18/Oct/2007 By Jipson Thomas
	*/	
	function lastloginDetailsUser($uid){
		$sql = "select * from session_det where user_id=$uid and status!='Y' order by logout_time desc ";
		$rs = $this->db->get_row($sql,ARRAY_A);
		return $rs;
	}

	/**
	* Fething Day by day Session list
	* Author   : Retheesh 
	* Created  : 24/Aug/2006
	* Modified : 24/Aug/2006 By Retheesh
	*/	
	function sessionList($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy,$uid=0)
	{
		$sql = "select a.*,m.username,TIMEDIFF(a.logout_time, a.login_time ) as duration from session_det a inner join member_master m
			   on a.user_id=m.id";
		if($uid>0)
		{
			$sql.=" where a.user_id=$uid";
		}
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		foreach ($rs as $value)
		{
			for($i=0;$i<sizeof($value);$i++)
			{
				if($value[$i]->id>0)
				{
					$diff=$this->get_time_difference($rs[0][$i]->logout_time, date("Y-m-d H:i:s"),1);
					if($rs[0][$i]->status=="Y")
					{
						if($diff["hours"]>0)
						{

							$rs[0][$i]->status="N";
						}
						elseif($diff["minutes"]>$this->config["max_idle_time"])
						{

							$rs[0][$i]->status="N";
						}
						else
						{
							$rs[0][$i]->logout_time="";
							$rs[0][$i]->duration="";
						}
					}
				}
			}
		}

		return $rs;
	}

	/**
	* Fething Detailed session list
	* Author   : Retheesh 
	* Created  : 24/Aug/2006
	* Modified : 24/Aug/2006 By Retheesh
	*/	
	function mainSessList($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy,$stxt)
	{

		$sql = "SELECT a.user_id,b.username,HOUR( SEC_TO_TIME( SUM( TIME_TO_SEC( TIMEDIFF( a.logout_time, a.login_time ) ) ) ) ) AS duration, MINUTE( SEC_TO_TIME( SUM( TIME_TO_SEC( TIMEDIFF( a.logout_time, a.login_time ) ) ) ) ) AS duration2, SECOND( SEC_TO_TIME( SUM( TIME_TO_SEC( TIMEDIFF( a.logout_time, a.login_time ) ) ) ) ) AS duration3
					FROM session_det a INNER JOIN member_master b ON a.user_id = b.id ";
		if($stxt)
		{
			$sql.=" where b.username like '$stxt%'";
		}
		$sql.="GROUP BY a.user_id";

		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);

		foreach ($rs as $value)
		{
			for($i=0;$i<sizeof($value);$i++)
			{
				if($value[$i]->user_id>0)
				{
					$st = $this->getLastSession($rs[0][$i]->user_id);
					($st["status"]=="Offline") ? $rs[0][$i]->image="N" : $rs[0][$i]->image="Y";
					$rs[0][$i]->duration = $rs[0][$i]->duration.":".$rs[0][$i]->duration2.":".$rs[0][$i]->duration3;
					$dur = explode(":",$rs[0][$i]->duration);

					$dr_str = $dur[0]." hours, ".$dur[1]." minutes, ".$dur[2]." seconds";
					$rs[0][$i]->dur_str = $dr_str;
				}
			}
		}
		return $rs;
	}

	/**
	* Loading Membertypelist
	* Author   : Retheesh 
	* Created  : 24/Aug/2006
	* Modified : 24/Aug/2006 By Retheesh
	*/	
	function  loadMemTypeList($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy,$exclude="")
	{
		if($exclude!="")
		{
			$ids = explode(",",$exclude);
		}

		for($i=0;$i<sizeof($ids);$i++)
		{
		($i>0)? $str=$str." and " : $str=" where ";
		$str = $str." id!=".$ids[$i];

		}
		$sql = "select id,type,(100-percentage) as percent from member_types";
		if($str!="")
		{
			$sql = $sql.$str;
		}
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}

	/**
	* Getting Mebertpe Details
	* Author   : Retheesh 
	* Created  : 24/Aug/2006
	* Modified : 24/Aug/2006 By Retheesh
	*/	
	function getMemTypeDetails($id)
	{
		$sql ="select * from member_types where id=$id";
		$rs = $this->db->get_row($sql,ARRAY_A);
		return $rs;
	}
	/**
	* Updating values of MemberType table
	* Author   : Retheesh 
	* Created  : 24/Aug/2006
	* Modified : 24/Aug/2006 By Retheesh
	*/	
	function updateMemType()
	{
		$arr = $this->getArrData();
		$id = $arr["id"];
		$this->db->update("member_types",$arr,"id=$id");
		return true;
	}

	/**
	* Loading MemberType Combo
	* Author   : Retheesh 
	* Created  : 24/Aug/2006
	* Modified : 24/Aug/2006 By Retheesh
	*/	
	function loadMemTypeCmb($exclude='',$cmb=1)
	{
		if($exclude!="")
		{
			$ids = explode(",",$exclude);
		}

		for($i=0;$i<sizeof($ids);$i++)
		{
		($i>0)? $str=$str." and " : $str=" where ";
		$str = $str." id!=".$ids[$i];

		}
		$sql = "select * from member_types";
		if($str!="")
		{
			$sql = $sql.$str;
		}
		if ($cmb==1)
		{
			$rs['id'] = $this->db->get_col($sql,0);
			$rs['type'] = $this->db->get_col($sql,1);
		}
		else
		{
			$rs = $this->db->get_results($sql);
		}
		return $rs;
	}

	/**
	* Checking for a storename
	* Author   : Retheesh 
	* Created  : 25/Aug/2006
	* Modified : 25/Aug/2006 By Retheesh
	*/	
	function validStore($store_name)
	{
		$sql ="select * from store where name='$store_name'";
		$rs = $this->db->get_results($sql);
		if (count($rs)>0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	* Checking for an email address from member master
	* Author   : Retheesh 
	* Created  : 25/Aug/2006
	* Modified : 25/Aug/2006 By Retheesh
	* Modified : 31/Jan/2008 By Ratheesh kk (Added checking w.r.t user's id)
	*/	
	function checkEmail($email,$user_id='',$store_id='',$retailer='')
	{	
		if($user_id>0){
			$condition = " AND id!= $user_id";
		}
		if($store_id>0){
			$condition = " AND from_store = $store_id";
		}
		if($retailer==1){
			$sql="select a.id from store a inner join member_master b on a.user_id=b.id where b.email='$email' ";
		} else {
			if(isset($condition)){
				$sql = "select * from member_master where email='$email' AND mem_type=1 ".$condition;
			} else {
				$sql = "select * from member_master where email='$email' AND mem_type=1 ";
			}
			
		}
		
		
		//print_r($retailer);exit;
		$rs = $this->db->get_row($sql);
		if (count($rs)>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	/**
	* Checking for an email address from member master
	* Author   : Retheesh 
	* Created  : 25/Aug/2006
	* Modified : 25/Aug/2006 By Retheesh
	* Modified : 31/Jan/2008 By Ratheesh kk (Added checking w.r.t user's id)
	*/	
	function checkUsername($uname)
	{	
		if($user_id>0){
			$condition = " AND id!= $user_id";
		}
		if($store_id>0){
			$condition .= " AND from_store = $store_id";
		}
		if($retailer==1)
		$sql="select a.id from store a inner join member_master b on a.user_id=b.id where b.email='$email' ";
		else
		$sql = "select * from member_master where email='$email' AND mem_type=1 ".$condition;
		//print_r($retailer);exit;
		$rs = $this->db->get_results($sql);
		if (count($rs)>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	/**
	* Checking for an email address from member master for projects that have multiple stores and its own member base.
	* Author   : Salim 
	* Created  : 22/May/2008
	* Modified : 
	* Modified : 
	*/	
	function checkEmailForMultiStores($email,$user_id='',$store_id='')
	{	
		if($user_id>0){
			$condition = " AND id!= $user_id";
		}
		
			$condition .= " AND from_store = $store_id";
		
		$sql = "select * from member_master where email='$email' AND mem_type=1 ".$condition;
		//print_r($sql);exit;
		$rs = $this->db->get_results($sql);
		if (count($rs)>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	* Loading registration packages
	* Author   : Retheesh 
	* Created  : 25/Aug/2006
	* Modified : 25/Aug/2006 By Retheesh
	*/	
	function loadRegPack($mem_type='')
	{
		if ($mem_type!="")
		{
			$sql = "SELECT b.* FROM `package_mem_type` a  inner join reg_package b
			on a.package_id=b.id where a.mem_type=$mem_type and b.active='Y'";
		}
		else
		{
			$sql = "select * from reg_package where active='Y'";
		}
		$rs = $this->db->get_results($sql);
		return $rs;
	}

	/**
	* Loading registration packages
	* Author   : Jipson Thomas
	* Created  : 18/Oct/2007
	* Modified : 18/Oct/2007 By Jipson Thomas
	*/	
	function loadRegPack2($mem_type='')
	{
		if ($mem_type!="")
		{
			$sql = "SELECT b.* FROM `package_mem_type` a  inner join reg_package b
			on a.package_id=b.id where a.mem_type=$mem_type and b.active='Y'";
		}
		else
		{
			$sql = "select * from reg_package where active='Y'";
		}
		$regpack = array();
		$rs = $this->db->get_results($sql);
		if($rs)
		{
			foreach($rs as $val)
			{
				$regpack[$val->id] = $val->package_name;
			}
		}
		return $regpack;
	}

	/**
	* Loading subscription packages
	* Author   : Retheesh
	* Created  : 28/Aug/2006
	* Modified : 28/Aug/2006 By Retheesh
	*/	
	function loadSubscriptions($reg_pack='')
	{
		if ($reg_pack!="")
		{
			$sql ="SELECT c.*,a.*,c.id as sub_id,c.fees as sub_fee FROM `reg_package` a inner join package_subscription b
			on a.id=b.package_id inner join subscription_master c 
			on c.id=b.subscr_id where a.id=$reg_pack and c.active='Y'";

		}
		else
		{
			$sql = "select * from subscription_master";
		}
		
		$rs = $this->db->get_results($sql);

		return $rs;

	}

	/**
	* Loading subscription packages in a registration combo box
	* Author   : Retheesh	
	* Created  : 28/Aug/2007
	* Modified : 28/Aug/2007 By Retheesh
	*/
	function getSubpacks($reg_pack)
	{
		$sql	=	"select CONCAT(sm.id,'~',sm.fees),sm.name from subscription_master sm,reg_package rp,package_subscription ps
					where sm.id=ps.subscr_id and rp.id=ps.package_id and rp.id='$reg_pack'	";
		//$sql	=	"select sm.id,sm.name from subscription_master sm,reg_package rp,package_subscription ps
		//where sm.id=ps.subscr_id and rp.id=ps.package_id and rp.id='$reg_pack'	";
		$rs['id'] 	= 	$this->db->get_col($sql, 0);
		$rs['name'] 	= 	$this->db->get_col($sql, 1);
		return $rs;

	}

	/**
	* Loading Registration packages in a registration combo box
	* Author   : Retheesh	
	* Created  : 28/Aug/2007
	* Modified : 28/Aug/2007 By Retheesh
	*/
	function getRegpacks()
	{
		$sql = "select id,CONCAT(package_name,'  ($',fee,')') as name from reg_package where active='Y'";
		$rs['id'] 	= 	$this->db->get_col($sql, 0);
		$rs['name'] 	= 	$this->db->get_col($sql, 1);
		return $rs;

	}

	/**
	* Updating Registration packages from admin side
	* Author   : Unknown	
	* Created  : Unknown
	* Modified : Unknown By Unknown
	*/
	function adminUpgradeRegPack($req)
	{
		$package	=	$_REQUEST['reg_pack'];
		$user_id	=	$_REQUEST['id'];
		if($user_id != '' && $package != '') {
			$this->db->query("UPDATE `member_master` SET `reg_pack` = '$package' where id='$user_id'");
		}
		return true;
	}
	/**
	* Updating Upgrading account name
	* Author   : Shinu	
	* Created  : 10-Oct-2007
	* Modified : 05-Nov-2007
	*/
	function updateCustomeFieldValue($req,$field_title,$position,$table_id)
	{
		$field_value	=	$_REQUEST[$field_title];
		$user_id	=	$_REQUEST['id'];
		if($user_id != '' && $field_value != '') {
			// checking field value already exist
			$sql	=	"SELECT id from  `custom_fields_list` where `$position` = '$field_value' and  table_id=$table_id";
			$rs = $this->db->get_row($sql, 0);
			$count	=	count($rs);
			if($count == "0")
			{
				$this->db->query("UPDATE `custom_fields_list` SET `$position` = '$field_value' where table_key='$user_id' and table_id=$table_id");
			}

		}
		return true;
	}

	/**
	* Updating Registration packages from admin side
	* Author   : Unknown	
	* Created  : Unknown
	* Modified : Unknown By Unknown
	*/
	function adminUpgradeSubPack($req)
	{
		$user_id	=	$_REQUEST['id'];
		$y			=	$_REQUEST['end_year'];
		$m			=	$_REQUEST['end_month'];
		$d			=	$_REQUEST['end_day'];
		if($y !="" && $m != "" && $d != "" && $user_id!= "")
		{
			$curDate	=	date("Y-m-d H:i:s");
			$new_endDate	=	$y."-".$m."-".$d." 00:00:00";
			$sql	=	"SELECT * FROM `member_subscription` where user_id='$user_id' ORDER BY `enddate` DESC LIMIT 0 , 1";
			$chk = $this->db->get_row($sql,ARRAY_A);
			if(count($chk) > 0){
				$sub_id	=	$chk['id'];
				$sql2	=	"UPDATE `member_subscription` SET `enddate` = '$new_endDate' where id='$sub_id' AND user_id='$user_id'";
				$this->db->query($sql2);
			}
			else
			{
				$array 			= 	array("user_id"=>$user_id,"subscr_id"=>"1","startdate"=>$curDate,"enddate"=>$new_endDate);
				if($new_endDate > $curDate)
				{	//if($new_endDate != $current_endDate)
					//{
					$this->db->insert("member_subscription", $array);
					$this->db->query("UPDATE `member_master` SET `sub_pack` = '1' where id='$user_id'");
					//}
				}
			}
		}

		return true;
	}

	/**
	* This function inserts/Modifies Registration Package Details
	* Author   : Retheesh		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Retheesh
	*/
	function insertRegPackage($update='')
	{
		$exist=0;
		$arr = $this->getArrData();
		$id = $arr['id'];
		unset($arr['id']);
		$p_name = $arr['package_name'];
		$sql = "select * from reg_package where package_name='$p_name'";
		$chk = $this->db->get_row($sql);

		if (count($chk)>0)
		{

			if($update=='')
			{
				$exist = 1;
			}
			else
			{

				if ($chk->id!=$id)
				{
					$exist = 1;
				}

			}
			if ($exist==1)
			{
				$this->setErr("Package name already exists");
				return false;
			}
		}


		if ($arr["mem_type"])
		{
			$mem_type = $arr['mem_type'];
			unset($arr['mem_type']);
			$arr_new = array();
			if ($update!='')
			{

				$sql= "delete from package_mem_type where package_id='$id'";
				$this->db->query($sql);
				$pack_id = $this->db->update("reg_package",$arr,"id=$id");
				$arr_new['package_id'] = $id;
			}
			else
			{
				
				$pack_id = $this->db->insert("reg_package",$arr);
				$arr_new['package_id'] = $pack_id;
			}

			for ($i=0;$i<sizeof($mem_type);$i++)
			{
				$arr_new['mem_type'] = $mem_type[$i];
				$this->db->insert("package_mem_type",$arr_new);
			}
			return true;
		}
		else
		{
			$this->setErr("Please assign member type");
			return false;
		}
	}

	/**
	* This function returns a list of Registration Packages
	* Author   : Retheesh		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Retheesh
	*/
	function packageList($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy,$stxt='')
	{
		$sql ="select * from reg_package";
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}

	/**
	* This function will return the details of a particular Registration Package
	* Author   : Retheesh		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Retheesh
	*/
	function getPackageDetails($id)
	{
		if ($id!="")
		{
			$sql = "select * from reg_package where id=$id";
			$rs  = $this->db->get_results($sql,ARRAY_A);
			return $rs[0];
		}
		else
		{
			return false;
		}
	}

	/**
	* This function will return the details of a particular Registration Package
	* Author   : Retheesh		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Retheesh
	*/
	function loadMemberTypePackage($pck_id,$exclude='')
	{
		if($exclude!="")
		{
			$ids = explode(",",$exclude);
		}

		for($i=0;$i<sizeof($ids);$i++)
		{
		($i>0)? $str=$str." and " : $str=" where ";
		$str = $str." a.id!=".$ids[$i];

		}
		$sql = "SELECT a.*,b.package_id as chk FROM member_types a left join package_mem_type b on a.id=b.mem_type and b.package_id=$pck_id";
		if($str!="")
		{
			$sql = $sql.$str;
		}
		$rs = $this->db->get_results($sql);
		return $rs;
	}

	/**
	* This function retrieves Member Type restriction List
	* Author   : Retheesh		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Retheesh
	*/
	function restrictionList($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy,$stxt='')
	{
		if ( ($this->config['restriction_table_field']) && ($this->config['restriction_table']))
		{
			$sql = "select count(mr.table_id) as cnt,mt.{$this->config['restriction_table_field']} as table_field,mt.id as type_id from {$this->config['restriction_table']} mt  left join member_restrictions mr
			on mr.link_id=mt.id  group by mt.{$this->config['restriction_table_field']}";

			$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
			return $rs;
		}
	}

	/**
	* Membertypewise Restriction List
	* Author   : Retheesh		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Retheesh
	*/
	function typeRestrictionList($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy,$mem_type)
	{
		if ($this->config['restriction_table'])
		{
			$sql = "select mr.*,ct.* from member_restrictions mr left join custom_fields_table ct
			on mr.table_id=ct.table_id left join {$this->config['restriction_table']} mt on mr.link_id=mt.id where mr.link_id=$mem_type";

			$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
			return $rs;
		}
	}

	/**
	* This function retrieves the list of Restriction Types
	* Author   : Retheesh		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Retheesh
	*/
	function getRestrictionSection()
	{
		$sql = "select table_id,display_name from custom_fields_table where display_admin_side='Y'";
		$rs ['id'] = $this->db->get_col($sql,0);
		$rs["display_name"] = $this->db->get_col($sql,1);
		return $rs;
	}

	/**
	* This function retrieves the Restriction details
	* Author   : Retheesh		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Retheesh
	*/
	function getRestrictionDetails($id)
	{
		$sql = "select * from member_restrictions where id='$id'";
		$rs  = $this->db->get_row($sql,ARRAY_A);
		return $rs;
	}

	/**
	* This function updates Member Restriction Details
	* Author   : Retheesh		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Retheesh
	*/
	function addEditRestriction($id='')
	{
		$arr = $this->getArrData();
		$sql = "select * from member_restrictions where table_id=".$arr["table_id"].
		" and link_id=".$arr["link_id"];
		$rs = $this->db->get_row($sql,ARRAY_A);

		if (count($rs)>0)
		{
			$msg=0;
			if ($id)
			{
				if ($rs["id"]!=$id)
				{
					$msg=1;
				}
			}
			else
			{
				$msg=1;
			}
			if ($msg==1)
			{
				$this->setErr("A restriction already exists for this section");
				return false;
			}
		}

		if ($id)
		{
			$this->db->update("member_restrictions",$arr,"id=$id");
		}
		else
		{
			$this->db->insert("member_restrictions",$arr);
		}
		return true;

	}

	/**
	* Fetch maximum record limit for a p
	* Author   : Retheesh		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Retheesh
	*/
	function getMaxLimit($type_id,$table_id)
	{
		$sql	=	"select max_records_user from member_restrictions where link_id='$type_id' and table_id='$table_id' ";
		$rs = $this->db->get_row($sql,ARRAY_A);
		return $rs['max_records_user'];
	}

	/**
	* This function returns a list of Subscription Pacakges
	* Author   : Retheesh		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Retheesh
	*/
	function subscriptionList($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy,$stxt='')
	{
		$sql = "select * from subscription_master";
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}

	/**
	* This function returns all Registration packages against a particular subscription id
	* Author   : Retheesh		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Retheesh
	*/
	function loadSubscr_package($sub_id='')
	{

		if ($sub_id!='')
		{
			$sql = "SELECT a.*,b.package_id as chk FROM reg_package a left join package_subscription
			b on a.id=b.package_id  and b.subscr_id=$sub_id";
		}
		else
		{
			$sql = "select * from reg_package";
		}

		$rs = $this->db->get_results($sql);
		return $rs;
	}

	/**
	* This function inserts/Modifies Subscription Package Details
	* Author   : Retheesh		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Retheesh
	*/
	function insertSubscrPackage($update='')
	{
		$exist=0;
		$arr = $this->getArrData();
		$id = $arr['id'];
		unset($arr['id']);
		$name = $arr['name'];
		$sql = "select * from subscription_master where name='$name'";
		$chk = $this->db->get_row($sql);
		if (count($chk)>0)
		{

			if($update=='')
			{
				$exist = 1;
			}
			else
			{

				if ($chk->id!=$id)
				{
					$exist = 1;
				}

			}
			if ($exist==1)
			{
				$this->setErr("Subscription package name already exists");
				return false;
			}
		}


		if ($arr["reg_type"])
		{
			$reg_type = $arr['reg_type'];
			unset($arr['reg_type']);
			$arr_new = array();
			if ($update!='')
			{
				$sql= "delete from package_subscription where subscr_id='$id'";
				$this->db->query($sql);
				$this->db->update("subscription_master",$arr,"id=$id");
				$arr_new['subscr_id'] = $id;
			}
			else
			{
				$sub_id = $this->db->insert("subscription_master",$arr);
				$arr_new['subscr_id'] = $sub_id;
			}

			for ($i=0;$i<sizeof($reg_type);$i++)
			{
				$arr_new['package_id'] = $reg_type[$i];
				$this->db->insert("package_subscription",$arr_new);
			}
			return true;
		}
		else
		{
			$this->setErr("Please assign registration packages");
			return false;
		}
	}

	/**
	* This function will return the Subscription details against a subscription id
	* Author   : Retheesh		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Retheesh
	*/
	function getSubscrDetails($id)
	{
		if ($id!="")
		{
			$sql = "select * from subscription_master where id=$id";
			$rs  = $this->db->get_results($sql,ARRAY_A);
			return $rs[0];
		}
		else
		{
			return false;
		}
	}

	/**
	* This Function will add subscription details
	* Author   : Retheesh		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Retheesh
	*/
	function addSubscription()
	{
		$arr = $this->getArrData();
		$subscr_id = $arr["subscr_id"];
		$sub_det = $this->getSubscrDetails($subscr_id);

		if ($arr["startdate"])
		{
			$date = $arr["startdate"];
		}
		else
		{
			$date = date("Y-m-d H:i:s");
		}



		$type = $sub_det["type"];
		if ($type=="D")
		{
			$type = "DAY";
		}
		elseif ($type=="M")
		{
			$type = "MONTH";
		}
		elseif ($type=="Y")
		{
			$type="YEAR";
		}

		$interval = $sub_det["duration"];
		$sql = "select date_add('$date',INTERVAL $interval $type) as endDate";

		$rs  = $this->db->get_row($sql);
		$arr["startdate"] = $date;
		$arr["enddate"]   = $rs->endDate;
		$this->db->insert("member_subscription",$arr);
		return true;
	}

	/**
	* This function will check whether a user has an active subscription
	* Author   : Retheesh		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Retheesh
	*/
	function checkSubscription($user_id)
	{
		$date =  date("Y-m-d H:i:s");
		$sql  =  "select * from member_subscription where startdate<='$date' and enddate>='$date' and user_id=$user_id";
		$rs   = $this->db->get_row($sql);
		if (count($rs)>0)
		{
			return $rs;
		}
		else
		{
			return false;
		}
	}

	/**
	* This function will return the Subscription End Date
	* Author   : Retheesh		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Retheesh
	*/
	function getSubscriptionEndDate($user_id)
	{
		//$sql  = "select * from member_subscription where user_id='$user_id' order by id desc";
		$sql  = "select max(enddate) as enddate from member_subscription where user_id=$user_id";
		$rs   = $this->db->get_row($sql);
		if (count($rs)>0)
		{
			return $rs;
		}
		else
		{
			return false;
		}
	}

	/**
	* This function will renew subscription
	* Author   : Retheesh		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Retheesh
	*/
	function renewSubscription($startDate='',$active=0)
	{
		$arr = $this->getArrData();
		if ($active==1)
		{
			$sql = "select date_add('$startDate',INTERVAL 1 DAY) as nextDt";
			$rs=$this->db->get_row($sql);
			$startDate=substr($rs->nextDt,0,10)." 00:00:00";
		}
		$arr["startdate"] = $startDate;
		$sub_det  = $this->getSubscrDetails($arr["subscr_id"]);

		$type	  = $sub_det["type"];
		$duration = $sub_det["duration"];
		if ($type=="D")
		{
			$type ="DAY";
		}
		elseif ($type=="M")
		{
			$type ="MONTH";
		}
		else
		{
			$type = "YEAR";
		}
		$sql = "select date_add('$startDate',INTERVAL $duration $type) as nextDt";
		$rs=$this->db->get_row($sql);
		$endDate=substr($rs->nextDt,0,10)." 23:59:59";
		$arr["enddate"] =  $endDate;
		$this->db->insert("member_subscription",$arr);
		return true;
	}

	/**
	* Fetching subscription end date
	* Author   : Retheesh		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Retheesh
	* Modified : 21/Jan/2008 By Shinu
	*/
	function getEndDate($sub_id,$oldEndDate='')
	{
	if($oldEndDate=='')
		$startDate = date("Y-m-d H:i:s");
	else
		$startDate =	$oldEndDate;
		$sub_det  = $this->getSubscrDetails($sub_id);
		//print $sub_id;
		//print_r($sub_det);
		$type	  = $sub_det["type"];
		$duration = $sub_det["duration"];
		if ($type=="D")
		{
			$type ="DAY";
		}
		elseif ($type=="M")
		{
			$type ="MONTH";
		}
		else
		{
			$type = "YEAR";
		}
		$sql = "select date_add('$startDate',INTERVAL $duration $type) as nextDt";
		//print $sql;
		//exit;
		$rs=$this->db->get_row($sql);

		$endDate=substr($rs->nextDt,0,10)." 23:59:59";

		return $endDate;
	}

	/**
	* This function will generate subscription alert for users
	* Author   : Retheesh		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Retheesh
	*/
	function subAlert($user_id)
	{
		$sql = "select max(enddate) as enddate from member_subscription where user_id=$user_id";
		$rs  = $this->db->get_row($sql);

		if ($det=$this->getSubscriptionDays($user_id))
		{
			$diff = $det["diff"];
			$enddate = $det["enddate"];
			if ($diff<=$this->config["sub_reminder_days"])
			{
				$link = makeLink(array("mod"=>member,"pg"=>register),"act=sub_renew&user_id=$user_id");
				$link =  "<a href=$link class='lbClass'> Click here to renew your subscription </a>";

				$enddate = date("M d, Y",strtotime($enddate));

				if ($diff==0)
				{
					setMessage("<b>Your subscription will expire TODAY ($enddate)</b> <br>".$link,MSG_ERROR);
				}
				else
				{
					setMessage("<b>Your subscription will expire in $diff days (On $enddate) </b> <br>".$link,MSG_ERROR);
				}
				return true;
			}
			else
			{
				return false;
			}
		}
	}

	/**
	* This Function wii return the remaining days of a user's subscription
	* Author   : Retheesh		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Retheesh
	*/
	//This Function wii return the remaining days of a user's subscription
function getSubscriptionDays($user_id)
{
	$sql = "select max(enddate) as enddate from member_subscription where user_id='$user_id'";
	$rs  = $this->db->get_row($sql);

	if (!is_null($rs->enddate))
	{
		$enddate = $rs->enddate;
		$date =  date("Y-m-d H:i:s");
		$sql  = "SELECT DATEDIFF('$enddate','$date') as diff";
		$rs1  = $this->db->get_row($sql);
		$diff = $rs1->diff;
		$arr  = array();
		$arr["diff"] = $diff;
		$arr["enddate"] = $enddate;
		return $arr;
	}
	else 
	{
		return false;
	}
}

	/**
	* Adding Supplier Modules
	* Author   : Unknown		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Unknown
	*/
	function addSupplierModules($id)
	{
		//THIS FUNCTION NEEDS TO BE REVISED
		$arr = array();
		$arr["admin_id"] = $id;
		$arr["module_id"] = 18;
		$this->db->insert("module_permission",$arr);
		$arr["module_id"] = 26;
		$this->db->insert("module_permission",$arr);
		$arr["module_id"] = 25;
		$this->db->insert("module_permission",$arr);
		return true;
	}

	/**
	* For Loading Supplier in combo
	* Author   : Ajith		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Ajith
	*/
	function loadSupplier()
	{
		if ($this->config['artist_selection']=='Yes') $mem_type=5;
		else $mem_type=3;
		
		$sql = "SELECT id,CONCAT(first_name,' ',last_name) as name FROM member_master  WHERE mem_type=$mem_type";
		$rs['id'] = $this->db->get_col($sql, 0);
		$rs['name'] = $this->db->get_col("", 1);
		return $rs;
	}

	/**
	* Getting store details of a user
	* Author   : Ajith		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Ajith
	*/
	function getStore($uid)
	{
		$sql = "select * from store where user_id=$uid";
		$rs  = $this->db->get_row($sql);
		return $rs;
	}

	/**
	* for reports of user
	* Author   : Unknown		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Unknown
	*/
	function userDetailsforReports($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy,$stxt,$search_fields='',$search_values='',$report_key)
	{

		$curdate	=	date('Y-m-d');
		$UlastMonth  = 	mktime(0, 0, 0, date("m")  , date("d")-30, date("Y"));
		$lastMonth	=	date("Y-m-d",$UlastMonth);
		$UlastTwoMonth  = 	mktime(0, 0, 0, date("m")  , date("d")-60, date("Y"));
		$lastTwoMonth	=	date("Y-m-d",$UlastTwoMonth);
		$fromdate		=	"1900-01-01";

		$sql	=	"SELECT * from member_master";
		if($report_key=="1")
		{
			$sql=$sql." where joindate LIKE '%$curdate%' ";
		}
		// inactive members
		if($report_key=="2")
		{
			$sql=$sql." where active ='N' ";
		}
		// active for last 30 days
		if($report_key=="3")
		{
			$sql=$sql." where active ='Y' and joindate BETWEEN '$lastMonth' AND '$curdate'  ";
		}
		// active for last 60 days
		if($report_key=="4")
		{
			$sql=$sql." where active ='Y' and joindate BETWEEN '$lastTwoMonth' AND '$curdate'  ";
		}

		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}

	/**
	* Getting details from Report Keys
	* Author   : Unknown		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Unknown
	*/
	function getReportKey()
	{
		$qry				=	"select * from report_keys";
		$rs['id'] 	= 	$this->db->get_col($qry, 0);
		$rs['name'] 	= 	$this->db->get_col($qry, 1);
		return $rs;
	}

	/**
	* Date inserting format
	* Author   : Afsal		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Afsal
	*/
	function dateInsertViewFormat($date,$type='')
	{
		if($date)
		{
			$format = $this->config["show_format"];
			$arry1 = explode("-",$format);
			$arry2 = explode("-",$date);
			$i = 0;
			//print_r($arry1);
			foreach($arry1 as $val)
			{
				$combine[$val] =  $arry2[$i];
				$i++;
			}

			if($type == "view")
			{

				return implode("-",$combine);
			}
			else
			{
				return $combine["y"]."-".$combine["m"]."-".$combine["d"];
			}
		}

	}

	/**
	* Fetching U.S.A State code
	* Author   : Nirmal		
	* Created  : 29/Aug/2006
	* Modified : 29/Aug/2006 By Nirmal
	*/
	function getUSStateCodeFromStateName($state)
	{
		$Qry	=	"SELECT code FROM state_code WHERE country_id = 840 AND name = '$state'";
		$Row	=	$this->db->get_row($Qry, ARRAY_A);
		return $Row['code'];
	}

	/**
	* Function for Insert values into invite history
	* Author   : Jipson Thomas	
	* Created  : 04/Sep/2007
	* Modified : 04/Sep/2007 By Jipson Thomas
	*/
	function InsertInviteHistory($arr)
	{
		$mail=$arr["friend_email"];
		$sql="select * from invite_history where friend_email='$mail'";
		$rs = $this->db->get_results($sql);
		if(count($rs)>0){
			$sql="select * from invite_history where friend_email='$mail' and status='pending'";
			$rs1 = $this->db->get_results($sql);
			if(count($rs1)>0){
				$arr["attempt"]			=	$rs1[0]->attempt+1;
				$arr["date"]			=	date("Y-m-d h:i:s");
				$this->db->update("invite_history",$arr,$rs1[0]->id);
				return true;
			}else{
				return false;
			}
		}else{
			$arr["status"]			=	"pending";
			$arr["date"]			=	date("Y-m-d h:i:s");
			$this->db->insert("invite_history", $arr);
			return true;
		}
	}

	/**
	* Function for Insert values into Friend_list
	* Author   : Jipson Thomas	
	* Created  : 04/Sep/2007
	* Modified : 04/Sep/2007 By Jipson Thomas
	*/
	function InsertFriendList($arr)
	{
		$arr["date"]			=	date("Y-m-d h:i:s");
		$this->db->insert("friend_list", $arr);
		return true;
	}

	/**
	* Function for Update values of invite history
	* Author   : Jipson Thomas	
	* Created  : 04/Sep/2007
	* Modified : 04/Sep/2007 By Jipson Thomas
	*/
	function updateInviteHistoryStatus($uid,$mail)
	{
		$sql="update invite_history set status='joined' where user_id=$uid and friend_email='$mail'";
		$this->db->query($sql);
		return true;
	}

	/**
	* Function for getting values from invite history
	* Author   : Jipson Thomas	
	* Created  : 04/Sep/2007
	* Modified : 04/Sep/2007 By Jipson Thomas
	*/
	function getInviteHistoryDetails($pageNo,$limit,$params,$memid,$output=OBJECT)
	{
		$sql="SELECT *  FROM invite_history  WHERE user_id=$memid order by date desc";
		$RS =$this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $RS;
	}

	/**
	* Function for getting values from invite history
	* Author   : Jipson Thomas	
	* Created  : 04/Sep/2007
	* Modified : 04/Sep/2007 By Jipson Thomas
	*/
	function getInviteDetails($id)
	{
		$sql="SELECT *  FROM invite_history  WHERE id=$id ";
		$rs = $this->db->get_results($sql,ARRAY_A);
		return $rs[0];
	}

	/**
	* Function for Deleting values from invite history
	* Author   : Jipson Thomas	
	* Created  : 04/Sep/2007
	* Modified : 04/Sep/2007 By Jipson Thomas
	*/
	function deleteInviteDetails($id)
	{
		$sql="DELETE FROM invite_history  WHERE id=$id ";
		$this->db->query($sql);
		return true;
	}

	/**
	* Function for getting values of Friend Request...........
	* Author   : Jipson Thomas	
	* Created  : 05/Sep/2007
	* Modified : 05/Sep/2007 By Jipson Thomas
	*/
	function getFriendRequest($pageNo,$limit,$params,$memid,$output=OBJECT)
	{
		$sql="select fl.`user_id` as id,fl.`date` as date,mm.* FROM friend_list fl inner join member_master mm on fl.user_id=mm.id WHERE 	fl.`friend_id` =$memid AND fl.`approve` = 'waiting'";
		$RS =$this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $RS;
	}

	/**
	* Function for getting values of Friend Request...........
	* Author   : Jipson Thomas	
	* Created  : 05/Sep/2007
	* Modified : 05/Sep/2007 By Jipson Thomas
	*/
	function getSentRequest($pageNo,$limit,$params,$memid,$output=OBJECT)
	{
		$sql="select fl.`friend_id` as id,fl.`date` as date,mm.* FROM friend_list fl inner join member_master mm on fl.friend_id=mm.id WHERE 	fl.`user_id` =$memid AND fl.`approve` = 'waiting'";
		$RS =$this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $RS;
	}

	/**
	* Function for addAsFriend...........
	* Author   : Jipson Thomas	
	* Created  : 05/Sep/2007
	* Modified : 05/Sep/2007 By Jipson Thomas
	*/
	function addAsFriend($uid,$fid,$apv='waiting',$type=0)
	{
		$sql="select * from friend_list where friend_id=$fid and user_id=$uid";
		$rs = $this->db->get_results($sql);
		if(count($rs)>0)
		{
			if($rs[0]->approve=="waiting")
			$msg="You are already sent a friend request and is waiting for approval.";
			elseif($rs[0]->approve=="approved")
			$msg="This member is present in your friend list.";
			else
			{
				$arr=array();
				$arr["user_id"]		=	$uid;
				$arr["friend_id"]	=	$fid;
				$arr["approve"]		=	$apv;
				$arr["friend_type"]	=	$type;
				$arr["date"]		=	date("Y-m-d h:i:s");
				$this->db->insert("friend_list", $arr);
				$msg="A friend request is sent and is waiting for approval.";
			}
		}
		else
		{

			$arr=array();
			$arr["user_id"]		=	$uid;
			$arr["friend_id"]	=	$fid;
			$arr["approve"]		=	$apv;
			$arr["date"]		=	date("Y-m-d h:i:s");
			$arr["friend_type"]	=	$type;
			$this->db->insert("friend_list", $arr);
			$msg="A friend request is sent and is waiting for approval.";
		}
		return $msg;

	}
    
function checkFriendcount($uid,$fid){	
	$sql="select * from friend_list where friend_id=$fid and user_id=$uid";
	
	
	$rs = $this->db->get_results($sql);
	$value=count($rs);
	
	return $value;
	
	}
	/**
	* Function for Change Approve of Friend_List
	* Author   : Jipson Thomas	
	* Created  : 05/Sep/2007
	* Modified : 05/Sep/2007 By Jipson Thomas
	*/
	function changeApproveFriendlist($fid,$uid,$aprov)
	{
		$sql="update friend_list set approve='$aprov' where user_id=$uid and friend_id=$fid";
		$this->db->query($sql);
		return true;
	}

	/**
	* Function for get friends details
	* Author   : Jipson Thomas	
	* Created  : 06/Sep/2007
	* Modified : 06/Sep/2007 By Jipson Thomas
	*/
	function getFriendDetails($pageNo, $limit, $params, $output,$orderBy,$stxt,$uid,$type=0,$memtype=0)
	{
		list($qry,$table_id)=$this->generateQry('member_master','d');
		if($memtype==0){
			$sql = "SELECT b.*,c.country_name FROM (`friend_list` a inner join `member_master` b on
			  a.friend_id=b.id) LEFT JOIN `member_address` ma on 
			  b.id=ma.user_id and ma.addr_type='master' left join country_master c 
			  ON(ma.country = c.country_id) left join `custom_fields_list` d on b.id=d.table_key and d.table_id=$table_id
			  where a.user_id='$uid' and a.approve='approved' and a.friend_type=$type ORDER BY a.date DESC ";
		}else{
			$sql = "SELECT b.*,c.country_name FROM (`friend_list` a inner join `member_master` b on
				  a.friend_id=b.id) LEFT JOIN `member_address` ma on 
				  b.id=ma.user_id and ma.addr_type='master' left join country_master c 
				  ON(ma.country = c.country_id) left join `custom_fields_list` d on b.id=d.table_key and d.table_id=$table_id
				  where a.user_id='$uid' and a.approve='approved' and a.friend_type=$type and b.mem_type !=$memtype ORDER BY a.date DESC ";
		}
			// print_r($sql);exit;
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}

	/**
	* Function for get friends detailsAll
	* Author   : Jipson Thomas	
	* Created  : 06/Sep/2007
	* Modified : 06/Sep/2007 By Jipson Thomas
	*/
	function getFriendDetailsAll($pageNo, $limit, $params, $output,$orderBy,$stxt,$uid)
	{
		//list($qry,$table_id)=$this->generateQry('member_master','d');
		list($qry,$table_id,$join_qry)=$this->generateQry('member_master','d','b');
		//print_r($join_qry);exit;
		$sql = "SELECT b.*,c.country_name,$qry FROM (`friend_list` a inner join `member_master` b on
			   a.friend_id=b.id) LEFT JOIN `member_address` ma on 
			   b.id=ma.user_id and ma.addr_type='master' left join country_master c 
			   ON(ma.country = c.country_id) $join_qry where a.user_id='$uid' and a.approve='approved'";
			// print_r($sql);exit;
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}

	/**
	* Fetching Friends of a user id
	* Author   : Jipson Thomas	
	* Created  : 06/Sep/2007
	* Modified : 06/Sep/2007 By Jipson Thomas
	*/
	function viewFriends($uid)
	{
		$sql= "SELECT * FROM `friend_list` WHERE `user_id`=$uid and approve='approved'";

		$rs = $this->db->get_results($sql);
		for($i=0;$i<sizeof($rs);$i++)
		{
			$friendName= $rs[$i]->friend_id;
			$rsUser=$this->getUserDetails($friendName);
			$rs[$i]->first_name=$rsUser[first_name];
			$rs[$i]->last_name=$rsUser[last_name];
			$rs[$i]->nick_name=$rsUser[nick_name];
			$rs[$i]->user_name=$rsUser[username];
			$rs[$i]->screen_name=$rsUser[screen_name];
		}
		return $rs;
	}

	/**
	* Changing Friend type
	* Author   : Jipson Thomas	
	* Created  : 06/Sep/2007
	* Modified : 06/Sep/2007 By Jipson Thomas
	*/	
	function changeFriendType($id,$fid,$type=0)
	{
		$sql="update friend_list set friend_type='$type' where friend_id=$fid and user_id=$id";
		$this->db->query($sql);
		return true;
	}

	/**
	* Function for get friends Count
	* Author   : Jipson Thomas	
	* Created  : 06/Sep/2007
	* Modified : 06/Sep/2007 By Jipson Thomas
	*/	
	function getFriendsCount($uid,$show_private='no')
	{
	if($show_private=='no'){
		//
		$sql="Select
	friend_list.id
	From
	member_master
	Inner Join friend_list ON member_master.id = friend_list.friend_id
	Where
	friend_list.user_id = '$uid' AND
	friend_list.approve = 'approved' AND
	member_master.mem_type != '3'";
	// member_type of private member =3.

	}else{
	$sql = "select a.id from `friend_list` a where a.user_id='$uid' and a.approve='approved'";
	}
		//print_r($sql);exit;
		$RS = $this->db->get_results($sql,ARRAY_A);
		return count($RS);
	}

	/**
	* Function for Deleting values from friend_list
	* Author   : Jipson Thomas	
	* Created  : 06/Sep/2007
	* Modified : 06/Sep/2007 By Jipson Thomas
	*/	
	function removeFriend($id,$fid)
	{
		$sql="DELETE FROM friend_list where friend_id=$fid and user_id=$id";
		$this->db->query($sql);
		$sqls="DELETE FROM friend_list where friend_id=$id and user_id=$fid";
		$this->db->query($sqls);
		return true;
	}

	/**
	/**
	* Function for Checking whether two members are friends
	* Author   : Jipson Thomas	
	* Created  : 04/Jan/2008
	* Modified : 04/Jan/2008 By Jipson Thomas
	*/	
	function isFriends($uid,$fid)
	{
		$sql="SELECT id FROM friend_list WHERE user_id =$uid AND friend_id =$fid AND approve = 'approved'";
		$RS = $this->db->get_results($sql,ARRAY_A);
		if(count($RS)>0){
			return true;
		}else{
			return false;
		}
	}

	/**
	* Used to check ->whether a registration package exists for the 
	* selected member type and if exists to check whether it is active or not
	* Author   : Jeffy	
	* Created  : 20/Sep/2007
	* Modified : 20/Sep/2007 By Jeffy
	*/	
	function getmem_type()
	{
		$MemberPackages	=	array();

		$sql = "select * from member_types";
		$RS = $this->db->get_results($sql,ARRAY_A);

		$ArrIndx	=	0;
		foreach($RS as $Row) {
			$sql="select * from package_mem_type WHERE mem_type = {$Row['id']}";
			$rs = $this->db->get_results($sql);
			$count = count($rs);
			if($count>0){
				for($i=0;$i<$count;$i++){
					$pack_id = $rs[$i]->package_id;
					$sql2="select * from reg_package WHERE id = '$pack_id' and active = 'Y'";
					$rs2 = $this->db->get_results($sql2);
					if(count($rs2)>0){
						$PackageExists	=	'Yes';
					}else if ($PackageExists != 'Yes'){
						$PackageExists	=	'No';
					}
				}
			}else{
				$PackageExists	=	'No';
			}
			$MemberPackages[$ArrIndx]['id']				=	$Row['id'];
			$MemberPackages[$ArrIndx]['PackageExists']	=	$PackageExists;

			$ArrIndx++;
		}
		return $MemberPackages;
	}

	/**
	* Used for deleting Subscription Packages
	* Author   : Jeffy	
	* Created  : 20/Sep/2007
	* Modified : 20/Sep/2007 By Jeffy
	*/	
	function subscriptionDelete($id)
	{
		$this->db->query("DELETE FROM `subscription_master` where id=$id");
		$this->db->query("DELETE FROM `package_subscription` where subscr_id=$id");
		return true;
	}

	/**
	* Used for deleting Registration Packages
	* Author   : Jeffy	
	* Created  : 20/Sep/2007
	* Modified : 20/Sep/2007 By Jeffy
	*/	
	function packageDelete($id)
	{
		$this->db->query("DELETE FROM `reg_package` where id=$id");
		$this->db->query("DELETE FROM `package_mem_type` where package_id=$id");
		return true;
	}

	/**
	* Used for Checking whether a subscription package exist for the selected registration package
	* Author   : Jeffy	
	* Created  : 20/Sep/2007
	* Modified : 20/Sep/2007 By Jeffy
	*/	
	function checksubpack($id)
	{
		$sql = "SELECT * FROM package_subscription where package_id=$id";
		$RS = $this->db->get_results($sql,ARRAY_A);
		return count($RS);
	}

	/**
	* Function is used to upgrade the user subscription package
	* Author   : Shinu	
	* Created  : 15/Nov/2007
	* Modified : 15/Nov/2007 By Shinu
	*/	
	function UpgradePackageSubscription($userID,$sub_id,$start_date,$end_date)
	{
		$this->db->query("DELETE FROM `member_subscription` where user_id=$userID");
		$Array 			= 	array("user_id"=>$userID,"subscr_id"=>$sub_id,"startdate"=>$start_date,"enddate"=>$end_date);
		$this->db->insert("member_subscription", $Array);
		return true;
	}


	/**
	* Function is used to update User registration pack
	* Author   : Jeffy	
	* Created  : 20/Sep/2007
	* Modified : 20/Sep/2007 By Jeffy
	*/	
	function updateUserregpack($userID,$reg_pack_id,$sub_pack)
	{
		if ($userID>0)
		{
			$this->db->query("update member_master set `reg_pack`=$reg_pack_id,`sub_pack`=$sub_pack WHERE id=$userID");
			return true;
		}

	}
	function updatePaypalStatus($userID)
	{
		if ($userID>0)
		{
			$this->db->query("update member_master set amt_paid='Y'  WHERE id=$userID");
			return true;
		}

	}
	

	/**
	* Used to add topics in  Discussion Group
	* Author   : Adarsh	
	* Created  : 01/Oct/2007
	* Modified : 01/Oct/2007 By Adarsh
	*/	
	function addTopic()
	{
		$arrData= $this->getArrData();
		unset($arrData['content']);

		$sql="Select topicid from group_discussion where  topic='".$arrData['topic']."'";
		$count=count($this->db->get_results($sql));
		if($count==0)
		{
			$topicid=$this->db->insert("group_discussion",$arrData);
			$arrData= $this->getArrData();
			unset($arrData['topic']);
			$arrData['topicid']=$topicid;
			//$this->db->insert("group_reply",$arrData);
			return true;
		}
		else
		{
			$this->setErr("A Topic with same name already exists.!!");
		}
	}

	/**
	* Used to get topics from group dicussion
	* Author   : Adarsh	
	* Created  : 03/Oct/2007
	* Modified : 03/Oct/2007 By Adarsh
	*/	
	function getTopicList ($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$grid)
	{
	 
	if ($this->config['member_screen_name']=='Y')
		{
			$member_search_fields = "member_master.screen_name as author ";
		}
		else 
		{
			$member_search_fields = "member_master.username as author";
		}	
	
	
		$sql = "SELECT group_discussion.topicid,group_discussion.topic, $member_search_fields  ";
		$sql = $sql.",Count(group_reply.content) AS posts,group_discussion.lastpost FROM ";
		$sql = $sql."group_discussion LEFT JOIN group_reply ON group_discussion.topicid=group_reply.topicid ";
		$sql = $sql."INNER JOIN member_master ON group_discussion.user_id = member_master.Id ";
		$sql = $sql."GROUP BY group_discussion.topicid, member_master.first_name";

		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}

	/**
	* Fetching nomination count of a user for a particular week
	* Author   : Retheesh	
	* Created  : 05/Oct/2007
	* Modified : 05/Oct/2007 By Retheesh
	*/	
	function getNominationCount($week,$user_id)
	{
		$sql = "select count(id) as cnt from member_nomination where nominated_by='$user_id' and nom_week='$week'";
		$rs = $this->db->get_row($sql);
		return $rs->cnt;
	}

	/**
	 * This function is used for weekly nomination functionality
	 * Author   : Retheesh
	 * Created  : 05/Oct/2007
	 * Modified : 15/Oct/2007 by Retheesh
	 */
	function nominate()
	{
		$arr = $this->getArrData();
		$nom_user  = $arr["nominated_by"];
		$user_id   = $arr["user_id"];
		$week_id   = date("Wy");
		$nom_limit = $this->config["nomination_limit"] ;
		$arr["nom_week"] = $week_id;
		$cnt = $this->getNominationCount($week_id,$nom_user);

		if ($cnt>=$nom_limit)
		{
			$this->setErr("You have used all of your nominations for this week");
			return false;
		}
		elseif ($nom_user==$user_id)
		{
			$this->setErr("You are not allowed to nominate yourself");
			return false;
		}
		else
		{
			$this->db->insert("member_nomination",$arr);
			return true;
		}
	}

	/**
	 * This function fetches the list of nomination for Artists
	 * Author   : Retheesh
	 * Created  : 08/Oct/2007
	 * Modified : 09/Oct/2007 by Retheesh
	 */
	function nominationList($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$week_id)
	{
		$sql = "select count(mn.id) as cnt,mn.user_id,mn.nom_week from member_nomination mn
		where mn.nom_week='$week_id' group by mn.user_id order by cnt desc";
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);

		for($i=0;$i<sizeof($rs[0]);$i++)
		{
			$udet = $this->getUserdetails($rs[0][$i]->user_id);
			$rs[0][$i]->username = $udet["username"];
			$rs[0][$i]->nick_name = $udet["nick_name"];
			$rs[0][$i]->first_name = $udet["first_name"];
			$rs[0][$i]->image = $udet["image"];
		}

		return $rs;
	}

	/**
	 * This function update the satatus of member group member
	 * Author   : Adarsh
	 * Created  : 10/Oct/2007
	 * Modified : 10/Oct/2007 by Adarsh
	 */
	function UpdateGroupMember($id)
	{
		$arrData= array("active"=>'Y');
		if($this->db->update("group_members",$arrData,"id='$id'"))
		{
			return $id;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Get the count of properties under group
	 * Author   : Afsal
	 * Created  : 11/Oct/2007
	 * Modified : 11/Oct/2007 by Afsal
	 */
	function countGroupProperties($array)
	{
		if(count($array))
		{
			$i=0;
			foreach($array as $val){
				$sql = "SELECT count(group_id) AS properties FROM group_album WHERE group_id=$val->id";
				$rs = $this->db->get_row($sql,ARRAY_A);
				$cntProp[$i] = $rs["properties"];
				$i++;
			}
			return $cntProp;
		}

	}

	/**
	 * Getting details by profile URL
	 * Author   : Retheesh
	 * Created  : 11/Oct/2007
	 * Modified : 11/Oct/2007 by Retheesh
	 */
	function profileCheck($name)
	{
		$sql = "select * from member_master where profile_url='$name'";
		$rs = $this->db->get_results($sql,ARRAY_A);
		return $rs;
	}

	//+++++++ done by Jeffy on 26th Oct 2007 +++++++++++++++
	//These functions need to be changed

	function getCategoryMetaDetails($category_id){
		$sql = "select page_title, meta_description, meta_keywords from master_category where category_id=".$category_id;
		$rs = $this->db->get_row($sql,ARRAY_A);
		return $rs;
	}

	function getProductMetaDetails($product_id){
		$sql = "select page_title, meta_description, meta_keywords from products where id=".$product_id;
		$rs = $this->db->get_row($sql,ARRAY_A);
		return $rs;
	}
	function getAccessoryMetaDetails($product_id){
		$sql = "select page_title, meta_description, meta_keywords from product_accessories where id=".$product_id;
		$rs = $this->db->get_row($sql,ARRAY_A);
		return $rs;
	}

	//+++++++ done by Jeffy on 26th Oct 2007 +++++++++++++++


	function getGoogleAnalyticsDetails($store_name){
		$sql = "select google_analytics from store where name='$store_name'";
		$rs = $this->db->get_row($sql,ARRAY_A);
		return $rs;
	}

	//This function is under modification -- RETHEESH
	function userGrList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy,$stxt,$search_fields='',$search_values='',$criteria="=")
	{
		list($qry,$table_id,$join,$group_qr)=$this->generateQryAggr('member_master','count','id','d');
		if($search_fields)
		{
			list($qry_cs)=$this->getCustomQry('member_master',$search_fields,$search_values,$criteria,'m','d');
		}
		$sql="SELECT count(d.table_id) as ref_count,d.field_2 as email FROM  `custom_fields_list` d  where d.field_2!='' and d.table_id=1 group by d.field_2";
		if($qry_cs)
		{
			$wh =retWhere($sql);
			//$sql=$sql." $wh $qry_cs";
		}
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		//print_r($rs);
		return $rs;
	}
	function userGrMemberList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy,$stxt,$search_fields='',$search_values='',$criteria="=")
	{		list($qry,$table_id,$join,$group_qr)=$this->generateQryAggr('member_master','count','id','d');


	if($search_fields)
	{
		list($qry_cs)=$this->getCustomQry('member_master',$search_fields,$search_values,$criteria,'m','d');
	}
	$sql="SELECT count(d.table_id) as ref_count,d.field_2 as email FROM  `custom_fields_list` d   ";
	if($qry_cs)
	{
		$wh =retWhere($sql);
		$sql=$sql." $wh $qry_cs";
	}
	$sql=$sql."  group by d.field_2";
	$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
	//print_r($rs);
	return $rs;
	}	//++++++++++++++++++++++ End +++++++++++++++++++++++++

	/*
	* Sending menber invitations
	* Author   : adarsh
	* Created  : 11/Oct/2007
	* Modified : 11/Oct/2007 by adarsh
	*/
	function sentInvite($frnds,$email,$comments,$objEmail='')
	{

		$userinfo = $this->getUserdetails($_SESSION["memberid"]);
		$contact  = $this->listContacts($userinfo["username"]);

		$arr=explode(",",$frnds);

		for($i=0;$i<sizeof($arr);$i++)
		{	if($arr[$i])
		{
			if(!$this->validUsername($arr[$i]))
			{
				if($invalid!='')
				{
					$invalid=$invalid."<br>".$arr[$i]." (Unknown user)";
				}
				else
				{
					$invalid=$arr[$i]." (Unknown user)";
				}
			}
		}
		}

		$arr1=explode(",",$email);
		for($i=0;$i<sizeof($arr1);$i++)
		{	if($arr1[$i])
		{
			if(!checkEmail($arr1[$i]))
			{
				if($invalid!='')
				{
					$invalid=$invalid."<br>".$arr1[$i]." (Invalid Email Id)";
				}
				else
				{
					$invalid=$arr1[$i]." (Invalid Email Id)";
				}
			}
		}
		}

		if($invalid=='')
		{
			if($frnds!='' )
			{
				for($i=0;$i<sizeof($arr);$i++)
				{	if($arr[$i])
				{
					$mail_header = array();
					$touser=$this->getUsernameDetails($arr[$i]);
					$mail_header['from'] = 	$userinfo['email'];
					$mail_header["to"]   =  $touser["email"];
					$linkurl = $comment . "<a href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"register"), "act=add_edit&group_id=$grpid&touserid=$touserid&from=$from&invite=accept")."\">Join Group</a>";

					$dynamic_vars = array();
					$dynamic_vars["CONTENT"] 	= $comments;
					$dynamic_vars["LINK"] 		= $linkurl;
					$dynamic_vars["YOUR_NAME"] 	= $userinfo["username"];

					if($objEmail->send('invite_friends', $mail_header,$dynamic_vars))
					{
						setMessage("Your message  sent successfully.", MSG_SUCCESS);
					}
					else
					{
						setMessage($error);
					}

				}
				}

			}


			if($email !='')
			{
				for($i=0;$i<sizeof($arr1);$i++)
				{	if($arr1[$i])
				{
					$mail_header = array();
					$mail_header['from'] = 	$userinfo['email'];
					$mail_header["to"]   =  $arr1[$i];

					$linkurl = $comment . "<a href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"register"), "act=add_edit&group_id=$grpid&touserid=$touserid&from=$from&invite=accept")."\">Join Group</a>";

					$dynamic_vars = array();

					$dynamic_vars["CONTENT"] 	= $comments;
					$dynamic_vars["LINK"] 		= $linkurl;
					$dynamic_vars["YOUR_NAME"] 	= $userinfo["username"];

					if($objEmail->send('invite_friends', $mail_header,$dynamic_vars))
					{
						setMessage("Your message  sent successfully.", MSG_SUCCESS);
					}
					else
					{
						setMessage($error);
					}

				}
				}

			}
			return true;
		}
		else
		return $invalid;

	}
	/**
    * Returns a list of contacts for a particular user pagewise.
  	* Author   : adarsh
  	* Created  : 07/Nov/2007	
	 * Modified : 07/Nov/2007 by adarsh
  	*/
	function getListContacts($pageNo, $limit, $params='', $output=OBJECT, $orderBy,$stxt,$uname)
	{

		if($stxt)
		{
			$sql= "SELECT * from member_master WHERE username IN (select friedid  from message_contacts WHERE userid='$uname') AND (first_name Like '$stxt%' OR last_name Like '$stxt%'
                    OR email Like '$stxt%')";
		}
		else
		$sql = "SELECT *  from member_master  WHERE username IN (select friedid  from message_contacts WHERE userid='$uname')";

		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}

	/**
    * Returns user id of all active users for particular member type.
  	* Author   : Ratheesh Kk
  	* Created  : 08/Nov/2007	
	 * Modified : 08/Nov/2007 by Ratheesh Kk
  	*/
	function allActiveUsers($mem_type='')
	{
		if ($mem_type!="")
		{
			$condition = "AND mem_type=$mem_type";
		}

		$sql = "select * from member_master where active='Y' $condition";

		$rs = $this->db->get_results($sql);
		return $rs;
	}




	////////////////////////////////////chat online user list/////////////////

	//9th praameter was $criteria
	function chatSessList($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy,$stxt,$search_fields,$search_values,$criteria='=')
	{

		list($qry,$table_id,$join_qr)=$this->generateQry('member_master','d','b');
		if($search_fields)
		{
			list($qry_cs)=$this->getCustomQry('member_master',$search_fields,$search_values,$criteria,'b','d');
		}
		$sql = "SELECT a.*,b.*,$qry
					FROM session_det a INNER JOIN member_master b ON a.user_id = b.id and a.status='Y' $join_qr";
		if($stxt)
		{
			$sql.=" where b.username like '$stxt%'";
		}
		if($qry_cs)
		{
			$wh =retWhere($sql);
			$sql=$sql." $wh $qry_cs";
		}
		$sql.=" GROUP BY a.user_id";
			$rs = $this->db->get_results_pagewise($sql, $pageNo, 100, $params, $output, $orderBy);

		foreach ($rs as $value)
		{
			for($i=0;$i<sizeof($value);$i++)
			{
				if($value[$i]->user_id>0)
				{
					$st = $this->getLastSession($rs[0][$i]->user_id);
					if($st["status"]=="Offline")
					unset($rs[0][$i]);
					/*($st["status"]=="Offline") ? $rs[0][$i]->image="N" : $rs[0][$i]->image="Y";
					$rs[0][$i]->duration = $rs[0][$i]->duration.":".$rs[0][$i]->duration2.":".$rs[0][$i]->duration3;
					$dur = explode(":",$rs[0][$i]->duration);
					$dr_str = $dur[0]." hours, ".$dur[1]." minutes, ".$dur[2]." seconds";
					$rs[0][$i]->dur_str = $dr_str;*/
				}
			}
		}

		return $rs;
	}

	/**
    * Returns the Unassigned List of all the Customers assigned to the Admin. Sort by Date, Latest on top.
  	* Author   : Jeffy
  	* Created  : 16/Oct/2007	
  	*/

	function unassignedList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy,$stxt,$search_fields='',$search_values='',$criteria="=")
	{
		if($stxt=="")
		{
			$sql = "SELECT a.*
					FROM member_master a, medical_questionnaire b, assign c
					WHERE a.id = b.member_id
					AND b.id = c.medical_questionnaire_id and c.status='Unassigned'";
		}
		else
		{
			$sql = "SELECT a.*
					FROM member_master a, medical_questionnaire b, assign c
					WHERE a.id = b.member_id
					AND b.id = c.medical_questionnaire_id and c.status='Unassigned' and (a.`username` like '%$stxt%' 
					OR a.`first_name` like '%$stxt%' OR a.`last_name` like '%$stxt%' OR a.`email` like '%$stxt%')";
		}
		if($qry_cs)
		{
			$wh =retWhere($sql);
			$sql=$sql." $wh $qry_cs";
		}
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}

	/**
    * Returns the Values from table pain_indicator
  	* Author   : Jeffy
  	* Created  : 14/Nov/2007
  	*/
	function ListPainIndicator()
	{
		$sql    = "SELECT * FROM pain_indicator";
		$rscat  = $this->db->get_results($sql,ARRAY_A);
		return $rscat;
	}

	/**
    * Returns the Values from table fedex_shipping
  	* Author   : Jeffy
  	* Created  : 14/Nov/2007
  	*/
	function ListFedexShipping()
	{
		$sql    = "SELECT * FROM fedex_shipping";
		$rscat  = $this->db->get_results($sql,ARRAY_A);
		return $rscat;
	}

	/**
    * Inserts Health Care Values
  	* Author   : Jeffy
  	* Created  : 14/Nov/2007
  	*/
	function med_insert()
	{
		$arr_master = $this->getArrData();
		$med_quest_arr = array();
		$med_quest_arr['member_id'] =  $arr_master['member_id'];
		//$med_quest_arr['height_foot'] =  $arr_master['height_foot'];
		$med_quest_arr['height'] =  $arr_master['height'];
		$med_quest_arr['weight'] =  $arr_master['weight'];
		$med_quest_arr['chief_complaint'] =  $arr_master['chief_complaint'];
		$med_quest_arr['current_medication'] =  $arr_master['current_medication'];
		$med_quest_arr['last_exam_date'] =  $arr_master['last_exam'];
		$med_quest_arr['recent_surgeries'] =  $arr_master['recent_surgeries'];
		$med_quest_arr['allergies'] =  $arr_master['allergies'];
		$med_quest_arr['smoke'] =  $arr_master['smoking'];
		$med_quest_arr['alcohol'] =  $arr_master['alcohol'];
		if($arr_master['mqid']){
			$med_quest_id = $this->db->update("medical_questionnaire", $med_quest_arr, "id=$arr_master[mqid]");
		}else{
			$med_quest_id = $this->db->insert("medical_questionnaire", $med_quest_arr);
		}

		$count_pain_indicator = count($arr_master['pain_indicator']);
		$pain_indicator_arr = array();
		if($arr_master['mqid']){
			$this->db->query("delete from `member_pain_indicator` where medical_id=$arr_master[mqid]");
			for($i=0;$i<$count_pain_indicator;$i++){
				$pain_indicator_arr['medical_id'] =  $arr_master['mqid'];
				$pain_indicator_arr['pain_indicator_id'] =  $arr_master['pain_indicator'][$i];
				$this->db->insert("member_pain_indicator", $pain_indicator_arr);
			}
		}else{
			for($i=0;$i<$count_pain_indicator;$i++){
				$pain_indicator_arr['medical_id'] =  $med_quest_id;
				$pain_indicator_arr['pain_indicator_id'] =  $arr_master['pain_indicator'][$i];
				$this->db->insert("member_pain_indicator", $pain_indicator_arr);
			}
		}

		$fedex_ship_arr = array();
		$fedex_ship_arr['medical_id'] =  $med_quest_id;
		$fedex_ship_arr['fedex_shipping_id'] =  $arr_master['fedex_shipping'];
		if($arr_master['mqid']){
			$this->db->query("delete from member_fedex_shipping where medical_id=$arr_master[mqid]");
			$fedex_ship_arr['medical_id'] =  $arr_master['mqid'];
		}
		$this->db->insert("member_fedex_shipping", $fedex_ship_arr);
		
		if(!$arr_master['mqid']){
			$referal_arr = array();
			$referal_arr['medical_id'] =  $med_quest_id;
			$referal_arr['refered_by'] =  $arr_master['refered_by'];
			$referal_arr['who'] =  $arr_master['who'];
			$referal_arr['hear_aboutus'] =  $arr_master['hear_aboutus'];
			$referal_arr['terms'] =  $arr_master['terms'];
			$this->db->insert("referal_info", $referal_arr);
		}
		
		if($arr_master['mqid']){
			$tomorrow = mktime(0,0,0,date("m"),date("d"),date("Y"));
			$rsassign = $this->latestMedQuest($arr_master['mqid']);
			$assign_arr = array();
			$assign_arr['admin_id'] =  $rsassign[0][admin_id];
			$assign_arr['doctor_id'] =  $rsassign[0][doctor_id];
			$assign_arr['pharmacy_id'] =  $rsassign[0][pharmacy_id];
			$assign_arr['medical_questionnaire_id'] =  $arr_master['mqid'];
			$assign_arr['assigned_date'] = date("Y-m-d H:i:s");
			$assign_arr['status'] =  "Changed_Request";
			$assign_arr['refill'] =  $rsassign[0][refill];
			$this->db->insert("assign", $assign_arr);
		}else{
			$tomorrow = mktime(0,0,0,date("m"),date("d"),date("Y"));
			$assign_arr = array();
			$assign_arr['admin_id'] =  0;
			$assign_arr['doctor_id'] =  0;
			$assign_arr['pharmacy_id'] =  0;
			$assign_arr['medical_questionnaire_id'] =  $med_quest_id;
			$assign_arr['assigned_date'] = date("Y-m-d H:i:s");
			$assign_arr['status'] =  "Unassigned";
			$assign_arr['refill'] =  0;
			$this->db->insert("assign", $assign_arr);
		}
		return $med_quest_id;
	}
	/**
    * Returns the records in desc order from the assign table for a particular medical questionnaire
  	* Author   : Jeffy
  	* Created  : 04/Dec/2007
  	*/
	function latestMedQuest($mqid)
	{
		$sql    = "SELECT * FROM `assign` WHERE medical_questionnaire_id =$mqid ORDER BY `id` DESC";
		$rs  = $this->db->get_results($sql,ARRAY_A);
		return $rs;
	}	
	/**
	* insertProductShipMap
	* Author   : Jipson Thomas
	* Created  : 15/Nov/2007
	* Modified : 
	*/
	function insertProductShipMap($pid,$mid)
	{
		$arr["order_products_id"]=$pid;
		$arr["member_address_id"]=$mid;
		//print_r($arr);exit;
		$this->db->insert("product_shipping_map",$arr);
		return true;
	}
	/**
	* getProductShipMap
	* Author   : Jipson Thomas
	* Created  : 15/Nov/2007
	* Modified : 
	*/
	function getProductShipMap($pid)
	{
		$sql="select member_address_id from product_shipping_map where order_products_id=$pid";
		$rs  = $this->db->get_results($sql,ARRAY_A);
		return $rs[0]['member_address_id'];
	}

	/**
	* Get Values from Table unset_fields
	* Author   : Jeffy
	* Created  : 15/11/2007
	*/	
	function GetUnsetFields()
	{
		$sql = "select field from unset_fields";
		$rs = $this->db->get_results($sql,ARRAY_A);
		return $rs;
	}
	
	/**
	* Get ARB Subscription id for a user
	* Author   : Shinu
	* Created  : 21/11/2007
	*/	
	function getARBSubscriptionID($user_id)
	{
		$sql = "select * from member_authorize_details where user_id='$user_id'";
		$rs = $this->db->get_row($sql,ARRAY_A);
		return $rs['subscription_id'];

	}

	///////////////////////////////
	/**
	* Get Username   by passing screen name
	* Author   : Jipson Thomas
	* Created  : 18/12/2007
	*/	
	function getUnamebyscreenname($sname)
	{
		$sql = "select * from member_master where screen_name='$sname'";
		$rs = $this->db->get_row($sql,ARRAY_A);
		//print_r($rs);exit;
		return $rs;

	}
	
	///////////////////////////////
	/**
	* Get Drop down values by passing drop down id
	* Author   : Jeffy
	* Created  : 18/12/2007
	*/	
	function listdropdown($id)
	{
		$sql = "select * from drop_down where group_id='$id' order by value ASC";
		$rs = $this->db->get_results($sql,ARRAY_A);
		//print_r($rs);exit;
		return $rs;

	}


	
	/**
	 * The following method manages different roles assigned to a particular user in bayard project [Ex broker, property manager etc...]
	 *
	 * Author 	:	vimson@newagesmb.com
	 * Created	:	18/12/2007
	 * 
	 * @param Array $REQUEST
	 * @param int $MemberId
	 * @return boolean
	 * 
	 */
	function setUserPropertyRoles($REQUEST, $MemberId)
	{					
		extract($REQUEST);
							
		$UpdArray		=	array();
		$UpdArray		=	array_merge($UpdArray, array('id' => $MemberId));
		
		if($isbroker == 'Y') {
			$UpdArray		=	array_merge($UpdArray, array('isbroker' => 'Y'));
			$UpdArray		=	array_merge($UpdArray, array('brokerdescription' => $brokerdescription));
			$UpdArray		=	array_merge($UpdArray, array('broker_commision' => (float)$broker_commision));
			$UpdArray		=	array_merge($UpdArray, array('broker_depositfee' => (float)$broker_depositfee));
			
			if((int)$brkrbilling_duration > 0) {
				$UpdArray		=	array_merge($UpdArray, array('brkrbilling_duration' => (int)$brkrbilling_duration));
				$UpdArray		=	array_merge($UpdArray, array('brkrbilling_period' => $brkrbilling_period));
			} else {
				$UpdArray		=	array_merge($UpdArray, array('brkrbilling_duration' => ''));
				$UpdArray		=	array_merge($UpdArray, array('brkrbilling_period' => ''));
			}
		} else {
			$UpdArray		=	array_merge($UpdArray, array('isbroker' => 'N'));
			$UpdArray		=	array_merge($UpdArray, array('brokerdescription' => ''));
			$UpdArray		=	array_merge($UpdArray, array('broker_commision' => ''));
			$UpdArray		=	array_merge($UpdArray, array('broker_depositfee' => ''));
			$UpdArray		=	array_merge($UpdArray, array('brkrbilling_duration' => ''));
			$UpdArray		=	array_merge($UpdArray, array('brkrbilling_period' => ''));
		}	
		
		if($ispopertymanager == 'Y') {
			$UpdArray		=	array_merge($UpdArray, array('ispopertymanager' => 'Y'));
			$UpdArray		=	array_merge($UpdArray, array('propmanagerdescription' => $propmanagerdescription));
			$UpdArray		=	array_merge($UpdArray, array('manager_depositfee' => (float)$manager_depositfee));
			
			if((float)$manager_commision > 0) {
					$UpdArray		=	array_merge($UpdArray, array('manager_commision' => (float)$manager_commision));
					$UpdArray		=	array_merge($UpdArray, array('mngrfixed_amount' 	=> 	''));
					$UpdArray		=	array_merge($UpdArray, array('mngrfixed_duration' 	=> 	''));
					$UpdArray		=	array_merge($UpdArray, array('mngrfixed_period' 	=>	 ''));
			} else if((float)$mngrfixed_amount > 0) {
				$UpdArray		=	array_merge($UpdArray, array('mngrfixed_amount' 	=> 	(float)$mngrfixed_amount));
				$UpdArray		=	array_merge($UpdArray, array('mngrfixed_duration' 	=> 	(int)$mngrfixed_duration));
				$UpdArray		=	array_merge($UpdArray, array('mngrfixed_period' 	=>	 $mngrfixed_period));
				$UpdArray		=	array_merge($UpdArray, array('manager_commision' => ''));
			} else {
				$UpdArray		=	array_merge($UpdArray, array('manager_commision' => ''));
				$UpdArray		=	array_merge($UpdArray, array('mngrfixed_amount' 	=> 	''));
				$UpdArray		=	array_merge($UpdArray, array('mngrfixed_duration' 	=> 	''));
				$UpdArray		=	array_merge($UpdArray, array('mngrfixed_period' 	=>	 ''));
			}
			
			
			if((int)$mngrbilling_duration > 0) {
				$UpdArray		=	array_merge($UpdArray, array('mngrbilling_duration' =>  $mngrbilling_duration));
				$UpdArray		=	array_merge($UpdArray, array('mngrbilling_period' 	=> 	$mngrbilling_period));
			} else {
				$UpdArray		=	array_merge($UpdArray, array('mngrbilling_duration' => ''));
				$UpdArray		=	array_merge($UpdArray, array('mngrbilling_period' 	=> ''));
			}
			
		} else {
			$UpdArray		=	array_merge($UpdArray, array('ispopertymanager' => 'N'));
			$UpdArray		=	array_merge($UpdArray, array('propmanagerdescription' => ''));
			$UpdArray		=	array_merge($UpdArray, array('manager_commision' => ''));
			$UpdArray		=	array_merge($UpdArray, array('manager_depositfee' => ''));
			
			$UpdArray		=	array_merge($UpdArray, array('mngrfixed_amount' 	=> 	''));
			$UpdArray		=	array_merge($UpdArray, array('mngrfixed_duration' 	=> 	''));
			$UpdArray		=	array_merge($UpdArray, array('mngrfixed_period' 	=>	 ''));
		}	
		$UpdArray		=	array_merge($UpdArray, array('popup_active' 	=>	 $popup_active));
				
		$this->setArrData($UpdArray);
		$this->update();

		return true;
	}
	
	
	
	
	/**
	 * The following method returns various roles assigned to a particular user [used in bayard project]
	 *
	 * Author 	:	vimson@newagesmb.com
	 * Created	:	18/12/2007
	 * 
	 * @param int $MemberId
	 * @return Associative array which contains property roles
	 */
	function getUserPropertyRoles($MemberId)
	{
		$Qry		=	"SELECT * FROM member_roles WHERE user_id = '$MemberId'";
		$Roles		=	$this->db->get_row($Qry,ARRAY_A);
		return $Roles;
	}

	
	
	/**
	 * Returns values from stores table
	 * Author 	:	jeffy
	 * Created	:	20/12/2007
	 */
	function getStoreDetails($store_name){
		$sql = "select * from store where name='$store_name'";
		$rs = $this->db->get_row($sql,ARRAY_A);
		return $rs;
	}
	
	/**
	 * Returns values for a particular Accessory from product_accessories table
	 * Author 	:	jeffy
	 * Created	:	20/12/2007
	 */
	function getAccessoryDetails($product_id){
		$sql = "select * from product_accessories where id=".$product_id;
		$rs = $this->db->get_row($sql,ARRAY_A);
		return $rs;
	}
	
	/**
	 * Returns values for a particular product from products table
	 * Author 	:	jeffy
	 * Created	:	20/12/2007
	 */
	function getCompleteProductDetails($product_id){
		$sql = "select * from products where id=".$product_id;
		$rs = $this->db->get_row($sql,ARRAY_A);
		return $rs;
	}
	
	
	/**
    * Fetching All Details including shipping address from Member Master and Custom fields using Userid
  	* Author   : Ratheesh  kk
  	* Created  : 26/Dec/2007	
  	* Modified : 26/Dec/2007	 By Ratheesh K K
  	*/ 
	function getUsersAlldetails($id)
	{
		list($qry,$table_id,$join_qry)=$this->generateQry('member_master','d','m');
		$sql="SELECT m.*,SMA.address1  AS shipping_address1,SMA.address2  AS shipping_address2,SMA.state  AS shipping_state,SMA.city  AS shipping_city,SMA.postalcode  AS shipping_postalcode,ma.*,$qry,m.id as id FROM `member_master` m LEFT JOIN `member_address` ma on
		      m.id=ma.user_id and ma.addr_type='master' LEFT JOIN `member_address` SMA on
		      m.id=SMA.user_id and SMA.addr_type='shipping'  $join_qry WHERE m.id='$id'";
		$RS = $this->db->get_results($sql,ARRAY_A);
		return $RS[0];
	}	
	
	
	/**
    * Fething Dynamic List fields for User
  	* Author   : Retheesh Kumar
  	* Created  : 31/Dec/2007	
  	* Modified : 31/Dec/2007	 By Retheesh Kumar
  	*/ 
	function fetchFields($orderBy,$table_name)
	{	
		$table_id = $this->getCustomId($table_name);
		$sql = "select * from custom_fields_main where table_id=$table_id ";
		$rs = $this->db->get_results($sql,ARRAY_A);
		$fields = array_keys($rs[0]);
		$fields=  implode(",",$fields);
		
		$sql = "SELECT $fields FROM `custom_fields_title` where table_id=$table_id and visible='Y' and showInList='Y' union 
		SELECT $fields FROM `custom_fields_main` where table_id=$table_id and visible='Y' and showInList='Y' order by $orderBy";
		$rs = $this->db->get_results($sql);
		return $rs;
	}
	
	/**
    * For setting search with respect to  Dynamically
  	* Author   : Ratheesh KK
  	* Created  : 01/Jan/2008	
  	* Modified : 01/Jan/2008
  	*/ 
	function searchFields($table_name)
	{	
		$table_id = $this->getCustomId($table_name);
		$sql = "select field_name from custom_fields_main where search='Y' AND table_id=$table_id ";
		$rs = $this->db->get_results($sql,ARRAY_A);
		return $rs;
	}
	
	
	/**
    * Checking wether subscription package is associated with the registeration package
  	* Author   : Shinu
  	* Created  : 25/Feb/2008
  	* Modified : 25/Feb/2008	 By Shinu
  	*/ 
	function getRegSubRelation($reg_id)
	{	
		$status	=	"N";
		$qry	=	"select * from reg_package where id='$reg_id'";
		$rs = $this->db->get_row($qry,ARRAY_A);
		if(count($rs)>0)
		{
			$fee	=	$rs['fee'];
			if($fee > 0)
			{
				$qry2	=	"select * from package_subscription where package_id='$reg_id'";
				$rs2 	= 	$this->db->get_row($qry2,ARRAY_A);
				if(count($rs2==0))
				{
					$status	=	"Y";
				}
			}
		}
		return $status;
	}
	
		
	/**
    * setting unlimited subscription end date for the user
  	* Author   : Shinu
  	* Created  : 25/Feb/2008
  	* Modified : 25/Feb/2008	 By Shinu
  	*/ 
	function setUnlimitSubscription($user_id)
	{	
		$curDate	=	date("Y-m-d H:i:s");
		$endDate	=	"9999-12-30 00:00:00";
		$array 		= 	array("user_id"=>$user_id,"startdate"=>$curDate,"enddate"=>$endDate);
		$this->db->insert("member_subscription", $array);
	}
	
	
	
	
	/**
	 * The following method written for bayard project pending payments by the users
	 * 
	 * Author	:	vimson@newagesmb.com
	 * Created	:	01/01/2008
	 * 
	 * @param int $MemberId
	 * @return Array
	 */
	function checkPendingPayments($MemberId, $REQUEST)
	{
		$Result		=	array();
						
		extract($REQUEST);
		
		require_once FRAMEWORK_PATH.'/modules/order/lib/class.payment.php';
		$PaymentObj		=	new Payment();
		
		$Qry1	=	"SELECT authorized,transaction_id,commnamount_balnce FROM member_paymentdetails WHERE memberid = '$MemberId'";
		$Row1	=	$this->db->get_row($Qry1, ARRAY_A);
		
		$authorized			=	$Row1['authorized'];
		$transaction_id		=	$Row1['transaction_id'];
		$commnamount_balnce	=	$Row1['commnamount_balnce'];
		$CreditMaxLimit		=	$this->config['broker_credit_maxlimit'];
		$CreditMinLimit		=	$this->config['broker_credit_minlimit'];
						
		if($authorized == 'NO' && $commnamount_balnce > $CreditMaxLimit ) {
			if(($mod == 'member' && $pg == 'account' && $act == 'billinginfo_form') || ($mod == 'member' && $pg == 'logout') )
				return TRUE;
			redirect(SITE_URL.'/'.makeLink(array("mod"=> "member","pg"=> "account"),"act=billinginfo_form&paymsg=Please Make Pending Payments"));
		}
		
		if($authorized == 'YES' && $commnamount_balnce > $CreditMinLimit ) {
			$PaymentObj->withdrawCommisionAmount($commnamount_balnce, $MemberId, "Amount Paid at the Login time {$commnamount_balnce}$");
			redirect(SITE_URL.'/'.makeLink(array("mod"=> "member","pg"=> "home"),"act=my_account"));
		}
		
		return TRUE;
	}
	
	
	
	/**
	 * The following method returns a layer for informain to the broker that if did no set up the Broker Fee and paypal account
	 *
	 * @param Int $MemberId
	 * 
	 */
	function checkForBrokerPaymentConfiguration($MemberId)
	{
		$LayerCode	=	'';
		
		$MemberDetails		=	$this->getUserdetails($MemberId);
		$broker_commision	=	(float)$MemberDetails['broker_commision'];
		$isbroker			=	$MemberDetails['isbroker'];
		
		$PaypalLink			=	makeLink(array("mod"=>member,"pg"=>account),"act=paypalinfo_form");
		$HomeLink			=	makeLink(array("mod"=>member,"pg"=>home));
		
		if($isbroker == 'Y') {
			if($broker_commision <= 0)
				$Msg1	=	'<span class="bodytext" style="color:#663333;font-size:11px;">You have not filled your Broker Commission, <a class="bodytext" style="text-decoration:underline;" href="'.$HomeLink.'">Click here to set</a></span><br>';
			
			$Qry				=	"SELECT TRIM(paypal_account) AS paypal_account FROM member_paymentdetails WHERE memberid = '$MemberId'";
			$Row				=	$this->db->get_row($Qry, ARRAY_A);
			$paypal_account		=	$Row['paypal_account'];
			
			if($paypal_account == '')
				$Msg2	=	'<span class="bodytext" style="color:#663333;font-size:11px;">You have not set up your account with PayPal information.<a class="bodytext" style="text-decoration:underline;" href="'.$PaypalLink.'">Click here to provide it</a></span><br>';	
			
			if($broker_commision <= 0 || $paypal_account == '')	
				$LayerCode	=	'<div  style="position:absolute; width:500px; z-index:1; left:300px; top:165px;border: 1px solid #804040;background-color: #CC9966;">'.$Msg1.$Msg2.'</div>';
		}
		
		return $LayerCode;
	}
	
	
	
	/**
	* Retrieves a particular Reg Pack value
	* Author   : Jeffy
	* Created  : 17/Jan/2008
	*/	
	function regPackRetrieve($reg_pack)
	{
		$sql = "select * from reg_package where active='Y' and id='$reg_pack'";
		$rs = $this->db->get_row($sql, ARRAY_A);
		return $rs;
	}
	
	//End
	
	function listfavourProducts($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy)
	{

	$qry		=	"select  A.*,count(A.userid) as Num ,B.username,B.id as user_id  from media_favorites A,member_master B where A.userid=B.id  group by A.userid";
	$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
	//print_r($rs);
	return $rs;
	}

	/**
	* php email validation
	* Author   : Jeffy
	* Created  : 04/04/2008
	*/	
	function validateEmail($email)
	{		
		$pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$";     
	    if (eregi($pattern, $email)){
	    	return true;
	 	}else{
	        return false;
		}
	}
	
	/**
	* country id using country name
	* Author   : vipin	
	* Created  : 11/04/2008
	*/
	function getCountryId($name)
	{
		$sql = "Select country_id from country_master where country_name='$name'";
		$rs= $this->db->get_results($sql,ARRAY_A);
		return $rs[0];
	}
	/**
    * Fetching Room name Availability.
  	* Author   : Jipson Thomas
  	* Created  : 05/May/2008	
  	* Modified : 05/May/2008 By Jipson thomas.
  	*/
	function getRoomnameAvailbility($rname)
	{
		$Rs= $this->db->get_results("select * from reserved_Chat_rooms where room_name='$rname'");
		if(count($Rs)>0)
		{
			$this->setErr("Room Name is reserved!");
			return false;
		}else{
			$sql="select owner_id from confer_master where name ='$rname'";
			$Rs= $this->db->get_results($sql,ARRAY_A);
			if(count($Rs)>0)
			{
				$ustat=$this->getLastSession($Rs[0]);
				if($ustat["status"] == "Online"){
					$this->setErr("Room Name is Allocated!");
					return false;
				}else{
					return true;
				}
			}else{
				return true;
			}
		}
	}
	/**
    * ratingsy.
  	* Author   : vinoy
  	* Created  : 05/May/2008	
  	* Modified : 05/May/2008 By vinoy jacob.
  	*/
	
	function getSellerRatings($uid)
	{
	  $SQL =	"select sum(mark)/count(mark) as rate, count(mark) as cnt
	   from  media_rating where type ='seller'  AND file_id='$uid'";
	   $rs  = $this->db->get_row($SQL, ARRAY_A);
	   return $rs; 
	}
	function getBrokerRatings($uid)
	{
	  $SQL =	"select sum(mark)/count(mark) as rate, count(mark) as cnt
	   from  media_rating where type ='broker'  AND file_id='$uid'";
	   $rs  = $this->db->get_row($SQL, ARRAY_A);
	   return $rs; 
	}
	
	function getManagerRatings($uid)
	{
	  $SQL =	"select sum(mark)/count(mark) as rate, count(mark) as cnt
	   from  media_rating where type ='manager'  AND file_id='$uid'";
	   $rs  = $this->db->get_row($SQL, ARRAY_A);
	   return $rs; 
	}
	/**
    * This function used to get the complate list profile questions.
  	* Author   : Adarsh v s
  	* Created  : 01/7/2008	
  	* Modified : 
  	*/ 
	function getProfileQuestions($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy)
	{
		$sql="select * from profile_questions";
		$rs  = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
	    return $rs;
	}
	
	/**
    * This function used to add the complate list profile questions.
  	* Author   : Adarsh v s
  	* Created  : 01/7/2008	
  	* Modified : 
  	*/ 
	function addEditProfileQuestion (&$req) {
	
        extract($req);
        if(!trim($question)) {
            $message = "Question is required";
		}	
        else {
            if($id) {
                $array = array("question"=>$question);
                $this->db->update("profile_questions", $array, "qid='$id'");
            } else {
                $array = array("question"=>$question);
                $this->db->insert("profile_questions", $array);
                $id = $this->db->insert_id;
				$refarray=array("ref_qid"=>$id);
				$this->db->update("profile_questions", $refarray, "qid='$id'");
            }
            return true;
        }
        return $message;
    }
	/**
    * This function used to delete  profile questions.
  	* Author   : Adarsh v s
  	* Created  : 01/7/2008	
  	* Modified : 
  	*/ 
	function deleteProfileQuestion($id)
	{
		$this->db->query("delete from profile_questions where qid=$id");
	}
	/**
    * This function used to get the details of  profile questions.
  	* Author   : Adarsh v s
  	* Created  : 01/7/2008	
  	* Modified : 
  	*/ 
	function getProfileQuestionDetails($id)
	{
		$sql="select * from profile_questions where qid='$id'";
		$rs  = $this->db->get_row($sql, ARRAY_A);
	    return $rs;
	}
	
	/**
    * This function used to get the complate list profile questions using ajax.
  	* Author   : Adarsh v s
  	* Created  : 07/7/2008	
  	* Modified : 
  	*/ 
	function getProfileQuestionsAjax($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy)
	{
		$sql="select * from profile_questions";
		$rs  = $this->db->get_results_pagewise_ajax($sql, $pageNo, $limit, $params, $output, $orderBy,0,0,1);
	    return $rs;
	}
	/**
    * This function used to get the used to check a particule user has selected profile question.
  	* Author   : Adarsh v s
  	* Created  : 01/7/2008	
  	* Modified : 
  	*/ 
	function getProfileQuestionsByUserId($user_id)
	{
		$sql="select id from member_profile_question where user_id='$user_id'";
		$rs  = $this->db->get_results($sql, ARRAY_A);
		if(count($rs)>0)
		return true;
		else
		return false;
	}
	/**
    * This function used to get the complate list profile questions by pagewise.
  	* Author   : Adarsh v s
  	* Created  : 01/7/2008	
  	* Modified : 
  	*/ 
	function getQuestionsListByUsers($user_id,$start,$rowspage)
	{
		$sql="select * from member_profile_question where user_id='$user_id' limit $start ,$rowspage ";
		$rs  = $this->db->get_results($sql, ARRAY_A);
		return $rs;
	}
	/**
    * This function used to add answer to a profile question.
  	* Author   : Adarsh v s
  	* Created  : 01/7/2008	
  	* Modified : 
  	*/ 
	function submitAns($id)
	{
		$arrData = $this->getArrData();
        $this->db->update("member_profile_question", $arrData, "id='$id'");
	}
	
	/**
    * Fetching comments from Media Comments Table
  	* Author   : Adarsh
  	* Created  : 11/july/2008	
  	* Modified :
  	*/
	function getProfileComments($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy,$uid,$type='',$flag_val ) {
		$sql		= "SELECT
						$member_search_fields
						`member_master`.`image`,
						`media_comments`.`id`,
						`media_comments`.`type`,
						`media_comments`.`file_id`,
						`media_comments`.`user_id`,
						`media_comments`.`comment`,
						`media_comments`.`postdate`,
						`member_master`.first_name,
						`member_master`.last_name,
						date_format(media_comments.postdate,'%b %d %Y') as pdate
						FROM
						`member_master`
						Inner Join `media_comments` ON `member_master`.`id` = `media_comments`.`user_id`
						WHERE `media_comments`.`type`='$type' and `media_comments`.`file_id`='$uid' order by `media_comments`.`postdate` DESC";

		$rs = $this->db->get_results_pagewise_ajax($sql, $pageNo, $limit, $params, $output, $orderBy,0,0,$flag_val);
		return $rs;
	}
	
	/**
    * This function used to get the complate list openions questions.
  	* Author   : vinoy
  	* Created  : 11/7/2008	
  	* Modified : 
  	*/ 
	function getOenionQuestions($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy)
	{
		$sql="select * from openion_question";
		$rs  = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
	    return $rs;
	}
	
	/*function getOpenionQuestionsAjax($pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy)
	{
		$sql="select * from openion_question";
		$rs  = $this->db->get_results_pagewise_ajax($sql, $pageNo, $limit, $params, $output, $orderBy);
	    return $rs;
	}*/
	function getOpenionQuestions()
	{
		$sql="select * from openion_question ";
		$rs  = $this->db->get_results($sql, ARRAY_A);
		return $rs;
	}
	function getOpenionQuestionsAjax($start,$rowspage)
	{
		$sql="select * from openion_question  limit $start ,$rowspage ";
		$rs  = $this->db->get_results($sql, ARRAY_A);
		return $rs;
	}
	
	/**
    * This function used to add the complate list profile questions.
  	* Author   :vinoyjacob
	* Created  : 11/7/2008	
  	* Modified : 
  	*/ 
	function addEditOpenionQuestion (&$req) {
	
        extract($req);
        if(!trim($question)) {
            $message = "Question is required";
		}	
        else {
            if($id) {
                $array = array("question"=>$question);
                $this->db->update("openion_question", $array, "id='$id'");
            } else {
                $array = array("question"=>$question);
                $this->db->insert("openion_question", $array);
				
                $id = $this->db->insert_id;
            }
            return true;
        }
        return $message;
    }
	/**
    * This function used to delete  openion questions.
  	* Author   : vinoy jacob
  	* Created  : 11/7/2008	
  	* Modified : 
  	*/ 
	
	function deleteOpenionQuestion($qid)
	{
		$this->db->query("delete from openion_question where id=$qid");
	}
	/**
    * This function used to get the details of  profile questions.
  	* Author   : vinoy jacob
  	* Created  : 11/7/2008	
  	* Modified : 
  	*/ 
	function getOpenionQuestionDetails($qid)
	{
		$sql="select * from openion_question where id='$qid'";
		$rs  = $this->db->get_row($sql, ARRAY_A);
	    return $rs;
	}
	
	function firstQuestion()
	{
		$sql="select * from openion_question LIMIT 0,1";
		$rs  = $this->db->get_row($sql, ARRAY_A);
	    return $rs;
	}
	function getQuestion($qid)
	{
	$sql="select * from openion_question WHERE id='$qid'";
		$rs  = $this->db->get_row($sql, ARRAY_A);
	    return $rs;
	}
	function addAnswer($answer,$qid,$uid)
	{
	
	
	$arr=array();
				$arr["quest_id"]=	$qid;
				$arr["user_id"]	=	$uid;
				$arr["answer"]		=	$answer;
				
				$this->db->insert("openion_answer", $arr);
				 $id = $this->db->insert_id;
	
	}
	function firstAnswerDetailsAjax($pageNo=0, $limit =4, $params='', $output=OBJECT, $orderBy,$qid)
	//function firstAnswerDetails($qid)
	{
		$sql="select t1.user_id as user_id ,t1.answer as answer,t2.first_name as first_name,t2.last_name as last_name,t2.image,t3.id as qustid,t3.question as question from  openion_answer as t1
		LEFT JOIN member_master as t2 ON t1.user_id=t2.id 
		LEFT JOIN openion_question as t3 ON t1.quest_id=t3.id
		where t1.quest_id='$qid'";
		/*$sql="select user_id,answer,first_name,last_name from  openion_answer as t1
		LEFT JOIN member_master as t2 ON t1.user_id=t2.id 
		where t1.quest_id='$qid'";*/
		 $rs  = $this->db->get_results_pagewise_ajax($sql, $pageNo, 4, $params, $output, $orderBy);
		//$rs  = $this->db->get_results($sql, ARRAY_A);
	    return $rs;
	
	}
	
	function AnswerDetailsAjax($pageNo=0, $limit =4, $params='', $output=OBJECT, $orderBy,$qid)
	//function firstAnswerDetails($qid)
	{
		  $sql="select t1.user_id as user_id ,t1.answer as answer,t2.first_name as first_name,t2.last_name as last_name,t2.image,t3.id as qustid,t3.question as question from  openion_answer as t1
		LEFT JOIN member_master as t2 ON t1.user_id=t2.id 
		LEFT JOIN openion_question as t3 ON t1.quest_id=t3.id
		where t1.quest_id='$qid' ORDER BY t1.id DESC";
		/*$sql="select user_id,answer,first_name,last_name from  openion_answer as t1
		LEFT JOIN member_master as t2 ON t1.user_id=t2.id 
		where t1.quest_id='$qid'";*/
		 $rs  = $this->db->get_results_pagewise_ajax($sql, $pageNo, 4, $params, $output, $orderBy);
		//$rs  = $this->db->get_results($sql, ARRAY_A);
	    return $rs;
	
	}
	
	/**
    * This function used to get friends list of particular user by pagewise.
  	* Author   : Adarsh v s
  	* Created  : 01/7/2008	
  	* Modified : 
  	*/ 
	function getFriendsListByUsers($user_id,$start,$rowspage)
	{
		$sql="select a.*,m.first_name,m.image from friend_list a inner join member_master m on a.friend_id=m.id where a.user_id='$user_id' and (a.approve='approved' or a.approve='waiting') limit $start ,$rowspage";
		$rs  = $this->db->get_results($sql, ARRAY_A);
		return $rs;
	}
	
	/**
    * This function is used get the profile list using ajax
  	* Author   : Adarsh
  	* Created  : 21/july/2008,$
  	* Modified : 30/Oct/2007 By Retheesh
  	*/
	function profileListByAjax ($pageNo, $limit, $params='', $output=OBJECT, $orderBy ,$search_field='',$search_value='',$stxt='',$age_from='',$age_to='',$martial_status='',$ethnicity='',$stxt6='',$stxt7='',$stxt8='',$stxt9='',$stxt10='',$stxt_state='',$stxt_zip,$image_flag='',$stxt_occupation,$br_stxt)
	{
		list($qry,$table_id)=$this->generateQry('member_master','d');
		$mem_type_condition = " and  (m.mem_type=1 or m.mem_type=2)  AND m.active='Y' ";
		
		$sql="SELECT m.*,ma.*,$qry,c.country_name,m.id as id FROM `member_master` m LEFT JOIN `member_address` ma on
			  m.id=ma.user_id and ma.addr_type='master' left join country_master c 
			  ON(ma.country = c.country_id) left join   member_profile_about p on p.user_id=m.id left join member_address a on a.user_id=m.id
			  left join  `custom_fields_list` d on m.id=d.table_key and d.table_id=$table_id 
			  where 1  $mem_type_condition ";
			  
		if($search_field !=='' && $search_value !=='' ){
			if($search_field=='name'){
				 $sql.=" and (m.first_name like '%$search_value%' or m.last_name like '%$search_value%')";
			}
			else if($search_field=='email')
			{
				 $sql.=" and m.email like '%$search_value%'";
			}
		}
		
		if($stxt!='')
		{
			if($stxt=='M'){
				$sql.=" and m.gender='m'";
			}
			else if($stxt=='F'){
				$sql.=" and m.gender='f'";
			}
			else if($stxt=='All'){
				$sql.=" and (m.gender='f' or m.gender='m')";
			}

		}
		if($age_from!='' && $age_to!='')	  {
			$sql.=" and (YEAR(CURDATE())-YEAR(m.dob))- (RIGHT(CURDATE(),5)<RIGHT(m.dob,5)) between '$age_from' and '$age_to' ";
		}
		
		if($martial_status!='')
		{
			$sql.=" and m.marital_status='$martial_status'";
		}
		if ($ethnicity!='')	{
			$sql.=" and p.ethnicity='$ethnicity'";
		}
		if ($stxt6!='')	{
			$sql.=" and p.body_type='$stxt6'";
		}
		
		if ($stxt6!='')	{
			$sql.=" p.body_type='$stxt6'";
		}
		if ($stxt_state!='')	{
			$sql.=" and a.state='$stxt_state'";
		}
		if ($stxt_zip!='')	{
			$sql.=" and a.postalcode='$stxt_zip'";
		}
		if ($image_flag !=''){
			$sql.=" and m.image='Y'";
		}
		if($stxt_occupation!=''){
			$sql.=" and p.occupation='$stxt_occupation'";
		}
		if($br_stxt!=''){
			$sql.=" and p.business_relation='$br_stxt'";
		}
		$rs = $this->db->get_results_pagewise_ajax($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}

	/**
	* Checking for an email address from member master
	* Author   : adarsh 
	* Created  : 31/july/2008
	
	*/	
	function checkEmail2($email,$user_id='',$store_id='')
	{	
		$sql = "select * from member_master where email='$email' AND from_store =0 and mem_type='1'";
		$rs = $this->db->get_results($sql);
		if (count($rs)>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function vaildStoreMember($id)
	{
		$sql="select * from store where user_id='$id'";
		$rs  = $this->db->get_row($sql, ARRAY_A);
		if (count($rs)>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
//**
		function listMessage($dynamic_vars,$type) {
			$rs=array();
			$sql ="SELECT body FROM email_config WHERE name='$type'";
			$body=$this->db->get_row($sql,ARRAY_A);					
			foreach ($dynamic_vars as $key=>$val) {								
				$body=str_replace("%_".$key."_%",$val,$body);								
			}	
			$rs=$body;	
			return $rs;
	    }	
	
	
	//**
	
	/**
	* Getting store details of a user
	* Author   : adarsh		
	* Created  : 02/sep/2008
	* Modified : 
	*/
	function getStoreDetById($id)
	{
		$sql = "select * from store where id=$id";
		$rs  = $this->db->get_row($sql,ARRAY_A);
	
		return $rs;
	}
	
	function getUserDetByType($type)
	{
		list($qry,$table_id,$join_qry)=$this->generateQry('member_master','d','m');
		$sql="SELECT m.*,c.country_name,ma.*,$qry,m.id as id FROM `member_master` m LEFT JOIN `member_address` ma on
		      m.id=ma.user_id and ma.addr_type='master' left join country_master c 
			  ON(ma.country = c.country_id) $join_qry WHERE m.mem_type='$type'";
		$RS = $this->db->get_results($sql,ARRAY_A);
		// print_r($sql);
		return $RS[0];
	}
	
	function getMembers()
	{
		$hour1=$this->config['deact_warning_hours1'];
		$hour2  =$this->config['deact_warning_hours2'];
		
		$qry='SELECT s.name,m.email,m.first_name,m.last_name FROM store s INNER JOIN  member_master m ON s.user_id=m.id  WHERE 	HOUR( TIMEDIFF( now(),m.joindate)) >= '.$hour1.'  AND   HOUR( TIMEDIFF( now(),m.joindate ))< '.$hour2.' AND amt_paid="N" ';		
		$rs = $this->db->get_results($qry,ARRAY_A);
		return $rs;
	}
	
	function getStoreForDeletion()
	{
		$hour=$this->config['store_deactivation_time'];
		
		 $qry='SELECT s.id,s.name,s.description,s.heading,m.email,m.first_name,m.last_name,ma.telephone,ma.address1,
		 DATE_FORMAT(m.joindate,"%b %d %Y %H :%i") as regdate,  DATE_FORMAT( NOW(),"%b %d %Y %H :%i") as cancelDate FROM store s 	         INNER JOIN  member_master m ON s.user_id=m.id  INNER JOIN  member_address ma ON ma.user_id =m.id
		 WHERE HOUR( TIMEDIFF(now(),m.joindate)) > '.$hour.'   AND amt_paid="N" ';		
		
		$rs = $this->db->get_results($qry,ARRAY_A);
		return $rs;
	}
	
	/**
	* Checking for an email address from member master
	* Author   : Retheesh 
	* Created  : 25/Aug/2006
	* Modified : 25/Aug/2006 By Retheesh
	* Modified : 31/Jan/2008 By Ratheesh kk (Added checking w.r.t user's id)
	*/	
	function checkEmail3($email,$user_id='',$store_id='',$retailer='')
	{	
		if($user_id>0){
			$condition = " AND b.id!= $user_id";
		}
		if($store_id>0){
			$condition .= " AND from_store = $store_id";
		}
		if($retailer==1)
		$sql="select a.id from store a inner join member_master b on a.user_id=b.id where b.email='$email' and  b.from_store=0 ";
		else
		$sql = "select * from member_master b where b.email='$email' AND b.mem_type=1 ".$condition;
		

		//print_r($retailer);exit;
		$rs = $this->db->get_results($sql);
		
		if (count($rs)>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function decodeSession($id="") {
	
		$qry		=	"select sess_value from order_session_value  where id='$id'";
		$row 		= 	$this->db->get_row($qry,ARRAY_A);
		$sess_str	=	$row['sess_value'];
		$sess_str	=	base64_decode($sess_str);
		session_decode($sess_str);
	}
	
	function encodeSession()
	{
		$Sess_str	=	session_encode();
		$Sess_str   = base64_encode($Sess_str);
		$arrayValue		=	array("sess_value"	=>	$Sess_str);
		$this->db->insert("order_session_value", $arrayValue);
		return $this->db->insert_id;
	}
	
	
	// cron for upgrading the subscription end date
	function upgradeUserSubscription()
	{
		
		include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
		$objEmail	 		= new Email();
		
		
						
		$sql="SELECT m.id,m.sub_pack,m.first_name,m.last_name,m.email,m.stop_subs,s.name,s.heading FROM `member_master` m inner join store s on m.id=s.user_id where m.mem_type=2 ";
	
		$rs 	=	$this->db->get_results($sql, ARRAY_A);	
		
		
		
		
		for($i=0;$i<count($rs);$i++)
		{
			$cur_date			=	date("Y-m-d");
			$subscriptionj_id	=	$rs[$i]['sub_pack'];
			$member_id			=	$rs[$i]['id'];
			$email				=	$rs[$i]['email'];
			$first_name			=	$rs[$i]['first_name'];
			$last_name			=	$rs[$i]['last_name'];
			$store_url			=	$rs[$i]['name'];
			$store_heading		=	$rs[$i]['heading'];
			$stop_subs			=	$rs[$i]['stop_subs'];
			
			// retriving subscription details
				$sql2  = "SELECT * FROM `member_subscription` where user_id='$member_id' ORDER BY `enddate` DESC LIMIT 0 , 1";
				$rs2   = $this->db->get_row($sql2, ARRAY_A);
				
				if (count($rs2)>0)
				{
					$cur_enddate	=	$rs2['enddate'];
					$old_sub_id		=	$rs2['id'];
					list($y,$m,$df)	=	split("-",$cur_enddate);
					$d	=	substr($df, 0, 2);
					$endDate	=	$y."-".$m."-".$d;
					
					//print($cur_date);print($endDate);

					
					if($endDate==$cur_date){
					
					
						if($stop_subs =='Y'){
							$mail_header = array();
							$mail_header['from'] 	= 	$this->config['admin_email'];
							$mail_header["to"]   	= $email;
							$dynamic_vars = array();
							$dynamic_vars["FIRST_NAME"]  = $first_name;
							$dynamic_vars["LAST_NAME"]   = $last_name;
							$dynamic_vars["STORE_NAME"]  = $store_heading;
							$dynamic_vars["STORE_URL"]   = $store_url;
							$dynamic_vars["SITE_NAME"]   = $this->config['site_name'];
							$dynamic_vars["STORE_LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".$store_url."\">".SITE_URL."/".$store_url."</a>";
							$objEmail->send("store_expired",$mail_header,$dynamic_vars);
						}
						else{
							$duration	=	0;
							$sql3		=	"select * from subscription_master where id='$subscriptionj_id'";
							$rs3  	 	= 	$this->db->get_row($sql3, ARRAY_A);
							$type		=	$rs3['type'];
							$duration	=	$rs3['duration'];
							if($type=="Y")
							{ 
								$nextday  		= 	mktime(0, 0, 0, $m , $d ,$y + $duration ); 
							}
							elseif($type=="M")
							{ 
								$nextday  		= 	mktime(0, 0, 0, $m + $duration , $d ,$y ); 
							}
							elseif($type=="D")
							{ 
								$nextday  		= 	mktime(0, 0, 0, $m , $d + $duration ,$y ); 
							}
							else
							{
								$nextday  		= 	mktime(0, 0, 0, $m , $d  ,$y );
							}
													
							$new_enddate	=	date("Y-m-d H:i:s",$nextday);
							
							$sql4	=	"UPDATE `member_subscription` SET `enddate` = '$new_enddate' where id='$old_sub_id' AND user_id='$member_id'";
						
							$this->db->query($sql4);

						}
					}
				}
			// end retriving subscription details
		
		}		
		//print_r($rs); exit;
		//return $count;
	}
// cron for upgrading the subscription end date

/* Function that send email to the store owner whos expire date is one less than the cron run date */
/* Created By Arun */
function sendEmailtoExpiredCustomer()
{
	include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
	$objEmail	 		= new Email();
		
		
						
	$sql="SELECT m.id,m.sub_pack,m.first_name,m.last_name,m.email,m.stop_subs,s.name,s.heading FROM `member_master` m inner join store s on m.id=s.user_id where m.mem_type=2 ";
	
	$rs 	=	$this->db->get_results($sql, ARRAY_A);
	for($i=0;$i<count($rs);$i++)
	{
		$cur_date			=	date("Y-m-d");
		$subscriptionj_id	=	$rs[$i]['sub_pack'];
		$member_id			=	$rs[$i]['id'];
		$email				=	$rs[$i]['email'];
		$first_name			=	$rs[$i]['first_name'];
		$last_name			=	$rs[$i]['last_name'];
		$store_url			=	$rs[$i]['name'];
		$store_heading		=	$rs[$i]['heading'];
		$stop_subs			=	$rs[$i]['stop_subs'];
			
		// retriving subscription details
		$sql2  = "SELECT * FROM `member_subscription` where user_id='$member_id' ORDER BY `enddate` DESC LIMIT 0 , 1";
		$rs2   = $this->db->get_row($sql2, ARRAY_A);
		if (count($rs2)>0)
		{
			$cur_enddate	=	$rs2['enddate'];
			$old_sub_id		=	$rs2['id'];
			list($y,$m,$df)	=	split("-",$cur_enddate);
			$d	=	substr($df, 0, 2);
			$endDate	=	$y."-".$m."-".$d;
			
			$yesterday = date('Y-m-d', mktime(0, 0, 0, date("m") , date("d") - 1, date("Y")));
			
			if($endDate==$yesterday)
			{
					$mail_header = array();
					$mail_header['from'] 	= 	$this->config['admin_email'];
					$mail_header["to"]   	= $email;
					$dynamic_vars = array();
					$dynamic_vars["FIRST_NAME"]  = $first_name;
					$dynamic_vars["LAST_NAME"]   = $last_name;
					$dynamic_vars["STORE_NAME"]  = $store_heading;
					$dynamic_vars["STORE_URL"]   = $store_url;
					$dynamic_vars["SITE_NAME"]   = $this->config['site_name'];
					$dynamic_vars["STORE_LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".$store_url."/manage"."\">".SITE_URL."/".$store_url."/manage"."</a>";
					$objEmail->send("store_expired_fix",$mail_header,$dynamic_vars);
			}
		}// End of if (count($rs2)>0)
	}
}
/*End of code for send email*/

	function getSubsStatus($req)
	{
		
		$rs = $req;
		
		
		foreach($rs as $k=>$val)
		{
			$member_id=$val->user_id;
			$stop_subs=$val->stop_subs;
			// retriving subscription details
			
			$sql2  = "SELECT * FROM `member_subscription` where user_id='$member_id' ORDER BY `enddate` DESC LIMIT 0 , 1";
			$rs2   = $this->db->get_row($sql2, ARRAY_A);
			$cur_date			=	date("Y-m-d");
			
			$cur_enddate	=	$rs2['enddate'];
			list($y,$m,$df)	=	split("-",$cur_enddate);
			$d	=	substr($df, 0, 2);
			$endDate	=	$y."-".$m."-".$d;
			
			if($endDate >= $cur_date && $stop_subs =='Y')
			  $subs_status='To be canceled on '.$endDate;
			else if ($endDate >= $cur_date && $stop_subs!='Y')
		  	  $subs_status='Active';
			else if ($endDate < $cur_date )
			  $subs_status='Canceled';
			
			$rs[$k]->subs_status = $subs_status;
			
			$rs[$k]->subs_status = $subs_status;
			
			//print_r($rs);
		}
		return $rs;
	}
	
	
	
	
	function adminUpgradeSubsEnddate($req)
	{
		
		extract($req);
		
		$y			 =	$req['end_year'];
		$m			 =	$req['end_month'];
		$d			 =	$req['end_day'];
		
		$curDate	 =	date("Y-m-d H:i:s");
		$new_endDate =	$y."-".$m."-".$d." 00:00:00";
		
		$array 			= 	array("user_id"=>$user_id,"subscr_id"=>"1","startdate"=>$curDate,"enddate"=>$new_endDate);
		$this->db->insert("member_subscription", $array);
	}
	
	/**
	* Updating Registration packages from admin side
	* Author   : Unknown	
	* Created  : Unknown
	* Modified : Unknown By Unknown
	*/
	function adminUpgradeSubPack2($req)
	{
		$sub_pack	=	$_REQUEST['sub_pack'];
		$user_id	=	$_REQUEST['id'];
		if($user_id != '' && $sub_pack != '') {
			$this->db->query("UPDATE `member_master` SET `sub_pack` = '$sub_pack' where id='$user_id'");
		}
		return true;
	}
	/**
	* Getting the first payment value from the temporary table subscription_fix
	* Author   : Arundas	
	* Created  : 19-Aug-2009
	* 
	*/
	function getSubscrFixval($user_id)
	{
		$sql = "SELECT * FROM subscription_fix WHERE user_id = $user_id";
		$rs   = $this->db->get_row($sql, ARRAY_A);
		if(count($rs) > 0)
		{
			return $rs['first_payment'];
		}
		else
		{
			return 0;
		}
	}
	
	function updateSubscriptionEnddate($sub_id,$user_id)
	{
		$sql2  = "SELECT * FROM `member_subscription` where user_id='$user_id' ORDER BY `enddate` DESC LIMIT 0 , 1";
		$rs2   = $this->db->get_row($sql2, ARRAY_A);
				
		if (count($rs2)>0)
		{
			//$cur_enddate	=	$rs2['enddate'];
			$cur_enddate	=	date("Y-m-d G:i:s");
			
			$old_sub_id		=	$rs2['id'];
			list($y,$m,$df)	=	split("-",$cur_enddate);
			$d	=	substr($df, 0, 2);
			$endDate	=	$y."-".$m."-".$d;
			$duration	=	0;
			$sql3		=	"select * from subscription_master where id='$sub_id'";
			$rs3  	 	= 	$this->db->get_row($sql3, ARRAY_A);
			$type		=	$rs3['type'];
			$duration	=	$rs3['duration'];
			
			if($type=="Y")
			{ 
				$nextday  		= 	mktime(0, 0, 0,  date("m") , date("d") ,date("Y")+ $duration ); 
			}
			elseif($type=="M")
			{ 
				$nextday  		= 	mktime(0, 0, 0,  date("m") + $duration , date("d") ,date("Y") ); 
			}
			elseif($type=="D")
			{ 
				$nextday  		= 	mktime(0, 0, 0,  date("m") , date("d") + $duration ,date("Y") ); 
			}
			else
			{
				$nextday  		= 	mktime(0, 0, 0,  date("m") , date("d")  ,date("Y") );
			}
			
			
			$new_enddate	=	date("Y-m-d H:i:s",$nextday);
			$new_startdate  =  date("Y-m-d G:i:s");
			$sql4	=	"UPDATE `member_subscription` SET `enddate` = '$new_enddate',`subscr_id` = '$sub_id',`startdate` = '$new_startdate',`subscr_eot` = 'N' where id='$old_sub_id' AND user_id='$user_id'";
			
			$this->db->query($sql4);
			
		}
	}
	
	function deleteUserFromSubscriptionFix($user_id)
	{
		$sql = "delete from `subscription_fix` where user_id=$user_id";
		$this->db->query($sql);
		
	}
	
	
	
function sendEmailtoExpiredStores()
{
	include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
	$objEmail	 		= new Email();
				
	 $sql='SELECT a.*,c.*,b.payment_status FROM store a INNER JOIN ipn_log b ON a.txn_id=b.item_number
	       INNER JOIN member_master c ON a.user_id=c.id WHERE b.payment_status !="" AND b.txn_id !=""
		   GROUP BY b.item_number ORDER BY log_time DESC ';
		   
	$rs 	=	$this->db->get_results($sql, ARRAY_A);
	
	
	for($i=0;$i<count($rs);$i++)
	{
		$cur_date			=	date("Y-m-d");
		$subscriptionj_id	=	$rs[$i]['sub_pack'];
		$member_id			=	$rs[$i]['id'];
		$email				=	$rs[$i]['email'];
		$first_name			=	$rs[$i]['first_name'];
		$last_name			=	$rs[$i]['last_name'];
		$store_url			=	$rs[$i]['name'];
		$store_heading		=	$rs[$i]['heading'];
		$storeid			=	$rs[$i]['storeid'];
		$stop_subs			=	$rs[$i]['stop_subs'];
		$payment_status		=	$rs[$i]['payment_status'];
		
		$status_array=array('Refunded','Denied','Failed','Voided');
		
		if(in_array($payment_status,$status_array))
	    {
		
			// retriving subscription details
			$sql2  = "SELECT * FROM `member_subscription` where user_id='$member_id' ORDER BY `enddate` DESC LIMIT 0 , 1";
			$rs2   = $this->db->get_row($sql2, ARRAY_A);
			
			$cur_enddate	=	$rs2['enddate'];
			$old_sub_id		=	$rs2['id'];
			list($y,$m,$df)	=	split("-",$cur_enddate);
			$d	=	substr($df, 0, 2);
			$endDate	=	$y."-".$m."-".$d;
				
			$yesterday = date('Y-m-d', mktime(0, 0, 0, date("m") , date("d") - 1, date("Y")));
				
			$qry="UPDATE `member_subscription` SET `enddate` = '$yesterday' where id='{$rs2['id']}'";
			$this->db->query($qry);
		}
	}
}

	/**
    * Membership validations
  	* Author   : Adarsh
  	* Created  : 16/Sep/2009
  	
  	* Validation for Duplicate email Registraion
  	*/
	function isValid2($mode)
	{
	   
		$arrData = $this->getArrData();
		$date_array=explode("-",$arrData["dob"]);

		if (($mode=="insert") || ($mode=="update"))
		{
		
		if ($this->config['member_screen_name']=='Y')
		     {
			////////////////////date validation	
				if($date_array[1]=='04' or $date_array[1]=='06' or $date_array[1]=='09' or  $date_array[1]=='11'   )	
				 {
				    if($date_array[2]>30)
					
					{
					  $this->setErr("Invalid Day!");
					  return false;
					}
				 }	
				 //exit;
				///////////////////////////////////////////
			if($date_array[0] % 4 == 0 && ($date_array[0] % 100 != 0 || $date_array[0] % 400 == 0)) 
					{
					
					    if($date_array[1]=='02')
						{
						 if($date_array[2]>29)
						 {
						 $this->setErr("Invalid Day!");
					     return false;
						 }
						}
					}
					else
					{
					   if($date_array[1]=='02')
						{
						 if($date_array[2]>28)
						 {
						 $this->setErr("Invalid Day!");
					     return false;
						 }
						}
					}	 
			 }
          ////////////////////////////for checking if screen_name already exists
if ($mode=="insert")
			{

				if ($this->config['member_screen_name']=='Y')
		     {

					$uname=$arrData["screen_name"];
					$Rs= $this->db->get_results("SELECT * from `member_master` WHERE `screen_name`='$uname'");
					if(count($Rs)>0)
					{
						$this->setErr("Screen Name Already Exists!");
						return false;
					
				   	}
				
					  
			  }

			}
         ///////////////////////////
			if ($mode=="insert")
			{

				if ($arrData["username"])
				{

					$uname=$arrData["username"];
					if($this->config['email_username']!='Y'){
						if(!ctype_alnum($uname)) 
						{
							$this->setErr("Use only alphanumeric characters in Username!");
							return false;
						}	
					}					
					$sql="SELECT * from `member_master` WHERE `username`='$uname'";
					
						$Rs= $this->db->get_results($sql);
	
						if(count($Rs)>0)
						{
							if ($this->config["registration_unique_field"])
							{
								$unique_field = $this->config["registration_unique_field"];
								$this->setErr("$unique_field already Exists!");
							}
							else
							{
								$this->setErr("Username Already Exists!");
							}
							return false;
						}
				}

			}

			$email=$arrData["email"];
			/*
			* This is for personalizedgift[for the purpose of same email for multiple stores].
			*/
			$condition = "";
			if ($this->config['single_prod']=='1'){
				//if($arrData["from_store"] != ''){	
					$store	=	$arrData["from_store"];	
					$condition = " AND from_store = $store";
				//}
			}
			
			/*	
			$qry="SELECT id from `member_master` WHERE `email`='$email'";
			$qry.=$condition;
			$Rs= $this->db->get_results($qry);
			
		if(count($Rs)>0){

				if($this->config['duplicate_email_registration']!="Y") //Addded for allowing duplicate Email Registration
				{
					if ($mode=="update")
					{
						if ($Rs[0]->id!=$_SESSION["memberid"])
						{
							$this->setErr("Email already Exists!");
							return false;
						}
						else
						{
							return true;
						}
					}
					else
					{
						$this->setErr("Email Already Exists!");
						return false;
					}
				}
				else{

					return true;
				}

			}
			else
			{
				return true;
			}*/

		}
		return true;
	}
	
	
	
	/**
    * Inserting values to member master and custom fields
  	* Author   : Adarsh
  	* Created  : 16/Sep/2009
  	* 
  	*/
	function insert2()
	{
		
		$arr_master = $this->getArrData();
		
		if (!$arr_master["addr_type"])
		{
			$arr_master["addr_type"] ="master";
		}

		$arr_master=$this->splitFields($arr_master,'member_master');   // Custom fields are seperated to another array from member master fields
		$arr_master=$this->getAddrFields($arr_master,'member_address'); //Member Address fields are seperated here
		
		if($this->config["health_care"]){
			$arr_shipping["address1"] = $arr_master[0]["shipping_address1"];
			$arr_shipping["address2"] = $arr_master[0]["shipping_address2"];
			$arr_shipping["city"] = $arr_master[0]["shipping_city"];
			$arr_shipping["state"] = $arr_master[0]["shipping_state"];
			$arr_shipping["postalcode"] = $arr_master[0]["shipping_postalcode"];
			$arr_shipping["telephone"] = $arr_master[2]["telephone"];
			$arr_shipping["mobile"] = $arr_master[2]["mobile"];
			unset($arr_master[0]["shipping_address1"],$arr_master[0]["shipping_address2"],$arr_master[0]["shipping_city"],$arr_master[0]["shipping_state"],$arr_master[0]["shipping_postalcode"]);
		}
		//	print_r($arr_master[0]);exit;
	
		$this->db->insert("member_master", $arr_master[0]);

		$id = $this->db->insert_id;
		$arr_master[1]["table_key"]=$id;
		$arr_master[2]["user_id"]=$id;

		if ($arr_master[1]["table_id"]>0)
		{
			$this->db->insert("custom_fields_list", $arr_master[1]);
		}
		$this->db->insert("member_address", $arr_master[2]);
		if($this->config["health_care"]){
			$arr_shipping["addr_type"] = "shipping";
			$arr_shipping["user_id"] = $id;
			$this->db->insert("member_address", $arr_shipping);
		}
		return $id;
	}
	
	function getStoreByTxnId($id)
	{
		$qry="select * from store where txn_id='$id' ";
		$Rs= $this->db->get_row($qry);
		return $Rs;
		
	}
	
	function updateSubscriptionDet($userid)
	{
		$qry	= "select * from member_master where id='$userid'";	
		$member_det   = $this->db->get_row($qry, ARRAY_A);
		
		$sql2  = "SELECT * FROM `member_subscription` where user_id='$userid' ORDER BY `enddate` DESC LIMIT 0 , 1";
		$subs_det   = $this->db->get_row($sql2, ARRAY_A);
				
		if (count($subs_det)>0)
		{
			$cur_enddate	=	$subs_det ['enddate'];
			$old_sub_id		=	$subs_det ['id'];
			
			list($y,$m,$df)	=	split("-",$cur_enddate);
			$d	=	substr($df, 0, 2);
			$endDate	=	$y."-".$m."-".$d;
			$duration	=	0;
			$sub_id=$member_det['sub_pack'];
			
			$sql3		=	"select * from subscription_master where id='$sub_id'";
			$rs3  	 	= 	$this->db->get_row($sql3, ARRAY_A);
			$type		=	$rs3['type'];
			$duration	=	$rs3['duration'];
							
			
			if($type=="Y")
			{ 
				$nextday  		= 	mktime(0, 0, 0,  date("m") , date("d") ,date("Y")+ $duration ); 
			}
			elseif($type=="M")
			{ 
				$nextday  		= 	mktime(0, 0, 0,  date("m") + $duration , date("d") ,date("Y") ); 
			}
			elseif($type=="D")
			{ 
				$nextday  		= 	mktime(0, 0, 0,  date("m") , date("d") + $duration ,date("Y") ); 
			}
			else
			{
				$nextday  		= 	mktime(0, 0, 0,  date("m") , date("d")  ,date("Y") );
			}
			
			$new_enddate	=	date("Y-m-d H:i:s",$nextday);
			$new_startdate  =  date("Y-m-d G:i:s");
			
			$sql4	=	"UPDATE `member_subscription` SET `enddate` = '$new_enddate',`subscr_id` = '$sub_id',`startdate` = '$new_startdate',`subscr_eot` = 'N' where id='$old_sub_id' AND user_id='$userid'";
			
			$this->db->query($sql4);
	}
}
	
	
	/**
    * Fetching Details from Member Subscription
  	* Author   : Vipin
  	* Created  : 12/Oct/2009	
  
  	*/
	function getUserSubscription($id)
	{
		
		$sql="SELECT * FROM `member_subscription` WHERE user_id = '$id' order by startdate DESC";
		$RS = $this->db->get_results($sql,ARRAY_A);
		
	// print_r($sql);
		return $RS;
	}
	
	
	/**
    * Fetching Details from Member Master and Custom fields using Userid
  	* Author   : Vipin
  	* Created  : 12/Oct/2009	
  
  	*/
	function getAllUserSubscription($id,$keyword, $pageNo, $limit = 20, $params='', $output=OBJECT, $orderBy,$datestatus='yes',$datecheck)
	{
		if ($datestatus=='yes'){
			   $sql="SELECT DATE_FORMAT(joindate,'%Y-%m-%d') as startdate from member_master WHERE id = '$id'";
			   //$orderBy = "startdate:DESC";
			   $rs = $this->db->get_row($sql);	  
		}else{
			  $sql="SELECT IF (d.type='M',concat(d.duration,' ', 'MONTH'),concat(d.duration,' ','YEAR')) as duration,
				b.payment_status,DATE_FORMAT(b.log_time,'%Y-%m-%d') as startdate,d.type,
				IF (d.type='M',DATE_FORMAT(DATE_ADD(b.log_time,INTERVAL d.duration MONTH),'%Y-%m-%d'),DATE_FORMAT(DATE_ADD(b.log_time,INTERVAL d.duration YEAR),'%Y-%m-%d')) as enddate
				FROM store a INNER JOIN ipn_log b ON (a.txn_id=b.item_number or a.txn_id=b.subscr_id)
				INNER JOIN member_master c on (a.user_id=c.id) 
				INNER JOIN subscription_master d on (d.id=c.sub_pack)
				WHERE b.txn_type ='subscr_payment' AND b.txn_id !='' AND a.user_id = '$id'";
				
				if ($datecheck!='null')
					$sql .= "AND b.log_time >DATE_ADD('$datecheck',INTERVAL 10 DAY)";
				
				$sql .= "GROUP BY b.txn_id";
				
				
				
				
		 $rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		 }
		
        return $rs;
		
	
	}


	function changeStopSubsStatus($id)
	{
		$this->db->query("update member_master set `stop_subs`='N' WHERE id=$id");
		return true;
	}
	
	
	function ipnLogDet($txnid)
	{
		$sql="select * from ipn_log where txn_id='$txnid'";


		$RS = $this->db->get_results($sql,ARRAY_A);
		return $RS;
	}
	
	
	function updateSubscriptionEot($userid)
	{
		$qry	= "select * from member_master where id='$userid'";	
		$member_det   = $this->db->get_row($qry, ARRAY_A);
		
		$sql2  = "SELECT * FROM `member_subscription` where user_id='$userid' ORDER BY `enddate` DESC LIMIT 0 , 1";
		$subs_det   = $this->db->get_row($sql2, ARRAY_A);
				
		if (count($subs_det)>0)
		{
			$cur_enddate	=	$subs_det ['enddate'];
			$old_sub_id		=	$subs_det ['id'];
			$sql4	=	"UPDATE `member_subscription` SET `subscr_eot` = 'N' where id='$old_sub_id' AND user_id='$userid'";
			$this->db->query($sql4);
	}
}
	

	
}
?>
