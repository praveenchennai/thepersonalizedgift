<?php
/**
 * **********************************************************************************
 * @package    Member
 * @name       Referral
 * @version    1.0
 * @author     Shinu
 * @copyright  2007 Newagesmb (http://www.newagesmb.com), All rights reserved.
 * Created on  14-May-2007
 * 
 * This script is a part of NewageSMB Framework. This Framework is not a free software.
 * Copying, Modifying or Distributing this software and its documentation (with or 
 * without modification, for any purpose, with or without fee or royality) is not
 * permitted.
 * 
 ***********************************************************************************/

class Referral extends FrameWork
{
	var $ip_det = null;
	/*
	constructor
	*/
	function Referral()
	{
		$this->FrameWork();
	}

	/**
  	 * Adding Referral details
  	 * Author   : Shinu
  	 * Created  : 14/May/2007
  	 * Modified : 14/May/2007 By Shinu
  	 */
	function AddReferralDetails($ref_name,$reg_pack)
	{
		$qry	=	"SELECT id,reg_pack,email FROM member_master WHERE username='$ref_name'";
		$rs = $this->db->get_row($qry, ARRAY_A);
		if(count($rs)>0)
		{
			$extendeddate	=	"";
			$member_id		=	$rs['id'];
			$member_pack_id	=	$rs['reg_pack'];
			$rs2 = $this->db->get_row("SELECT MAX(id) as current_id from member_master", ARRAY_A);
			$current_id		=	$rs2['current_id'];
			$new_member_id	=	$current_id+1;
			$userArray = array("user_id"=>$member_id,"reg_pack"=>$reg_pack,"new_member_id"=>$new_member_id,"status"=>"Y");
			$this->db->insert("referral_details", $userArray);


			// start checking for the referral benefits
			$rs3 = $this->db->get_row("SELECT * from referral_criteria where reg_pack_id='$reg_pack'", ARRAY_A);
			if(count($rs3)>0)
			{
				$required_count	=	$rs3['count'];
				$bonus_period	=	$rs3['reward_count'];
				$bonus_type		=	$rs3['type'];
				// starts checking wether user qualified for the referal benefits
				$rs4 = $this->db->get_row("SELECT count(*) as current_count from referral_details where user_id='$member_id' AND status='Y'", ARRAY_A);
				$current_count	=	$rs4['current_count'];

				if($current_count==$required_count)
				//if($current_count==1)
				{
					// inserting subscription details to the member_subscription details
					$cur_date				=	date("Y-m-d H:i:s");
					$subscription_enddate	=	$cur_date;

					$rs5 = $this->db->get_row("SELECT * from member_subscription where user_id='$member_id' ORDER BY `enddate` DESC LIMIT 0,1 ", ARRAY_A);
					if(count($rs5>0))
					{
						$subscription_enddate	=	$rs5['enddate'];
					}
					list($y,$m,$df)	=	split("-",$subscription_enddate);
					$d	=	substr($df, 0, 2);
					if($bonus_type=="D"){
						$nextdayUTI  	= 	mktime(0, 0, 0, $m , $d+$bonus_period, $y);
					}
					if($bonus_type=="M") {
						$nextdayUTI  	= 	mktime(0, 0, 0, $m+$bonus_period , $d, $y);
					}
					if($bonus_type=="Y") {
						$nextdayUTI  	= 	mktime(0, 0, 0, $m , $d, $y+$bonus_period);
					}
					$extendeddate	=	date("Y-m-d H:i:s",$nextdayUTI);

					$SubsArray = array("user_id"=>$member_id,"subscr_id"=>$member_pack_id,"startdate"=>$subscription_enddate,"enddate"=>$extendeddate);
					$this->db->insert("member_subscription", $SubsArray);
					$this->db->query("update referral_details set status='N' where user_id ='$member_id' AND reg_pack='$reg_pack'");
					// end subscription details to the member_subscription details
				}
				// end checking wether user qualified for the referal benefits
			}


			// end checking for the referral benefits
		}

		return true;
	}

	/**
  	 * Fetching Referral Userid
  	 * Author   : Shinu
  	 * Created  : 14/May/2007
  	 * Modified : 14/May/2007 By Shinu
  	 */
	function getRefUserId($ref_name)
	{
		$qry_extuser	=	"SELECT * FROM member_master WHERE username='$ref_name'";
		$rs_extuser 	= $this->db->get_row($qry_extuser, ARRAY_A);
		$extmember_id	=	$rs_extuser["id"];
		return $extmember_id;
	}

	/**
  	 * Getting subscription end date
  	 * Author   : Shinu
  	 * Created  : 14/May/2007
  	 * Modified : 14/May/2007 By Shinu
  	 */
	function getSubEnd($user_id)
	{
		$rs6 = $this->db->get_row("SELECT * from member_subscription where user_id='$user_id' ORDER BY `enddate` DESC LIMIT 0,1 ", ARRAY_A);
		if(count($rs6>0))
		{
			$usersubscription_enddate	=	$rs6['enddate'];
		}
		if($usersubscription_enddate=="")
		{ $usersubscription_enddate="NA"; }
		return $usersubscription_enddate;
	}

	/**
  	 * sending mail while referal registration
  	 * Author   : Shinu
  	 * Created  : 14/May/2007
  	 * Modified : 14/May/2007 By Shinu
  	 */
	function referralRegistered($ref_name,$reg_pack,$myId)
	{
		include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
		include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
		$email	 		= new Email();
		$objUser		= new User();

		$qry_extuser	=	"SELECT * FROM member_master WHERE username='$ref_name'";
		$rs_extuser 	= $this->db->get_row($qry_extuser, ARRAY_A);
		$extmember_id	=	$rs_extuser["id"];

		$refQry	=	"SELECT * FROM member_master WHERE id ='$myId'";
		$refRs = $this->db->get_row($refQry, ARRAY_A);

		$rs6 = $this->db->get_row("SELECT * from member_subscription where user_id='$extmember_id' ORDER BY `enddate` DESC LIMIT 0,1 ", ARRAY_A);
		if(count($rs6>0))
		{
			$usersubscription_enddate	=	$rs6['enddate'];
		}

		$mail_header = array();
		$mail_header['from'] 	= 	$framework->config['admin_email'];
		$mail_header["to"]   = $rs_extuser['email'];
		$dynamic_vars = array();
		$dynamic_vars["USER_NAME"] 	 		= $rs_extuser["username"];
		$dynamic_vars["NEWUSER_USER_NAME"] 	= $refRs["username"];
		$dynamic_vars["NEWUSER_USER_EMAIL"] = $refRs["email"];
		$dynamic_vars["SUB_END"]  			= $usersubscription_enddate;
		//$dynamic_vars["EXT_SUB_END"]  		= $extendeddate;
		$dynamic_vars["NEWUSER_FIRST_NAME"] = $refRs["first_name"];
		$dynamic_vars["NEWUSER_LAST_NAME"]  = $refRs["last_name"];
		$dynamic_vars["SITE_NAME"]  		= $framework->config['site_name'];
		$dynamic_vars["PACKAGE_NAME"]  		= $reg_pack;
		if($dynamic_vars["PACKAGE_NAME"]>0){
			$package_detail = $objUser->getPackageDetails($dynamic_vars["PACKAGE_NAME"]);
			$package_name = $package_detail["package_name"];
			$dynamic_vars["PACKAGE_NAME"]  = $package_name;
		}

		$email->send("referral_registered",$mail_header,$dynamic_vars);


	}


	/**
  	 * fetching subscription packages
  	 * Author   : Shinu
  	 * Created  : 14/May/2007
  	 * Modified : 14/May/2007 By Shinu
  	 */
	function getSubscriptionPack()
	{
		$sql		= 	"SELECT id, name FROM subscription_master WHERE active='Y'";
		$rs['id'] 	= 	$this->db->get_col($sql, 0);
		$rs['name'] = 	$this->db->get_col($sql, 1);
		return $rs;
	}

	/**
  	 * getting registration package
  	 * Author   : Shinu
  	 * Created  : 14/May/2007
  	 * Modified : 14/May/2007 By Shinu
  	 */
	function getRegistrationPack()
	{
		$sql				= 	"SELECT id, package_name FROM reg_package WHERE active='Y'";
		$rs['id'] 			= 	$this->db->get_col($sql, 0);
		$rs['package_name'] = 	$this->db->get_col($sql, 1);
		return $rs;
	}

	/**
  	 * getting non-zero subscription packages
  	 * Author   : Shinu
  	 * Created  : 14/May/2007
  	 * Modified : 14/May/2007 By Shinu
  	 */
	function getSubscriptionPacknonZero()
	{
		$sql		= 	"SELECT id, name FROM subscription_master WHERE active='Y' AND fees != '0'";
		$rs['id'] 	= 	$this->db->get_col($sql, 0);
		$rs['name'] = 	$this->db->get_col($sql, 1);
		return $rs;
	}

	/**
  	 * getting Referral pack details
  	 * Author   : Shinu
  	 * Created  : 15/May/2007
  	 * Modified : 15/May/2007 By Shinu
  	 */
	function getReferralPackDetails($ref_id)
	{
		$rs = $this->db->get_row("SELECT * FROM `referral_criteria` where `ref_id`=$ref_id",ARRAY_A);
		return $rs;
	}

	/**
  	 * For deleting record from referral criteria
  	 * Author   : Shinu
  	 * Created  : 15/May/2007
  	 * Modified : 15/May/2007 By Shinu
  	 */
	function refCriteriaDelete($ref_id)
	{
		$this->db->query("DELETE from referral_criteria WHERE ref_id='$ref_id'");
		return true;
	}

	/**
  	 * List all Referenec Criteria
  	 * Author   : Shinu
  	 * Created  : 15/May/2007
  	 * Modified : 15/May/2007 By Shinu
  	 */
	function listAllRefCriteria($keysearch='N',$features_search='',$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy="1")
	{
		$qry		=	"select rc.*,rp.package_name from referral_criteria rc,reg_package rp where
						  rc.reg_pack_id=rp.id ";

		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}

	/**
  	 * Add/Edit Referral Pack
  	 * Author   : Shinu
  	 * Created  : 15/May/2007
  	 * Modified : 15/May/2007 By Shinu
  	 */
	function referralPackAddEdit($req)
	{
		$ref_id				=	$_REQUEST["ref_id"]	? $_REQUEST["ref_id"]: "0";
		$reg_pack_id		=	$_REQUEST["sub_pack_id"]	? $_REQUEST["sub_pack_id"]: "0";
		$count1				=	$_REQUEST["count1"]	? $_REQUEST["count1"]: "0";
		$type				=	$_REQUEST["type"]	? $_REQUEST["type"]: "D";
		$count2				=	$_REQUEST["count2"]	? $_REQUEST["count2"]: "0";
		$active				=	$_REQUEST["active"]	? $_REQUEST["active"]: "N";

		$array 			= 	array("reg_pack_id"=>$reg_pack_id,"count"=>$count1,"type"=>$type,"reward_count"=>$count2,"active"=>$active);

		if($ref_id != "0" && $ref_id != "" ) {
			$array['ref_id'] 	= 	$ref_id;
			$this->db->update("referral_criteria", $array, "ref_id='$ref_id'");
		} else {
			$this->db->insert("referral_criteria", $array);
			$feature_id = $this->db->insert_id;

		}
		return true;
	}

	/**
  	 * Add Referral
  	 * Author   : Shinu
  	 * Created  : 15/May/2007
  	 * Modified : 15/May/2007 By Shinu
  	 */
	function addReferral($req)
	{
		include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
		include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
		$email	 	= new Email();
		$objUser	=new User();
		$member_id	=	$_SESSION["memberid"];
		$to_email	=	"";
		$cur_date	=	date("Y-m-d");
		if($_REQUEST['email1'] != '') {
			$to_email .=	$_REQUEST['email1'].",";
			$mailArray = array("user_id"=>$member_id,"email"=>$_REQUEST['email1'],"send_date"=>$cur_date);
			$this->db->insert("referral_email", $mailArray);
		}
		if($_REQUEST['email2'] != '') {
			$to_email .=	$_REQUEST['email2'].",";
			$mailArray = array("user_id"=>$member_id,"email"=>$_REQUEST['email2'],"send_date"=>$cur_date);
			$this->db->insert("referral_email", $mailArray);
		}
		if($_REQUEST['email3'] != '') {
			$to_email .=	$_REQUEST['email3'].",";
			$mailArray = array("user_id"=>$member_id,"email"=>$_REQUEST['email3'],"send_date"=>$cur_date);
			$this->db->insert("referral_email", $mailArray);
		}
		if($_REQUEST['email4'] != '') {
			$to_email .=	$_REQUEST['email4'].",";
			$mailArray = array("user_id"=>$member_id,"email"=>$_REQUEST['email4'],"send_date"=>$cur_date);
			$this->db->insert("referral_email", $mailArray);
		}
		if($_REQUEST['email5'] != '') {
			$to_email .=	$_REQUEST['email5'].",";
			$mailArray = array("user_id"=>$member_id,"email"=>$_REQUEST['email5'],"send_date"=>$cur_date);
			$this->db->insert("referral_email", $mailArray);
		}
		if($_REQUEST['email6'] != '') {
			$to_email .=	$_REQUEST['email6'].",";
			$mailArray = array("user_id"=>$member_id,"email"=>$_REQUEST['email6'],"send_date"=>$cur_date);
			$this->db->insert("referral_email", $mailArray);
		}
		if($_REQUEST['email7'] != '') {
			$to_email .=	$_REQUEST['email7'].",";
			$mailArray = array("user_id"=>$member_id,"email"=>$_REQUEST['email7'],"send_date"=>$cur_date);
			$this->db->insert("referral_email", $mailArray);
		}
		if($_REQUEST['email8'] != '') {
			$to_email .=	$_REQUEST['email8'].",";
			$mailArray = array("user_id"=>$member_id,"email"=>$_REQUEST['email8'],"send_date"=>$cur_date);
			$this->db->insert("referral_email", $mailArray);
		}
		if($_REQUEST['email9'] != '') {
			$to_email .=	$_REQUEST['email9'].",";
			$mailArray = array("user_id"=>$member_id,"email"=>$_REQUEST['email9'],"send_date"=>$cur_date);
			$this->db->insert("referral_email", $mailArray);
		}
		if($_REQUEST['email10'] != '') {
			$to_email .=	$_REQUEST['email10'].",";
			$mailArray = array("user_id"=>$member_id,"email"=>$_REQUEST['email10'],"send_date"=>$cur_date);
			$this->db->insert("referral_email", $mailArray);
		}

		if($_REQUEST['txt_email_to'] != '') {
			$to_email .=	$_REQUEST['txt_email_to'].",";
			$mailArray = array("user_id"=>$member_id,"email"=>$_REQUEST['txt_email_to'],"send_date"=>$cur_date);
			$this->db->insert("referral_email", $mailArray);
		}

		$rs	=	$objUser->getUserdetails($_SESSION["memberid"]);
		$links		=	SITE_URL."/index.php?sess=";
		$link		=	"mod=member&pg=register&act=add_edit&referral_name=".$rs['username'];
		$enLink		=	base64_encode($link);
		$newLink	=	$links.$enLink;
		$mail_header = array();
		$mail_header["from"] = $_REQUEST['email'];
		$mail_header["to"]   = $to_email;
		$dynamic_vars = array();
		if ($this->config['member_screen_name']=='Y')
		{
		$dynamic_vars["YOUR_NAME"]  =$_REQUEST['email'];
		}else
		{
		$dynamic_vars["YOUR_NAME"]  =$_REQUEST['first_name'];
		}
		$dynamic_vars["CONTENT"]  =$_REQUEST['my_message'];
		$dynamic_vars["LINK"]       = "<b><a target='_blank' href=".$newLink." >Click Here</a></b>";
		
		//print_r($dynamic_vars["LINK"]);exit;
		$email->send("sent_to_friend",$mail_header,$dynamic_vars);
		//setMessage("Mail Send sucessfully",MSG_SUCCESS);
		/*
		$subject	=	"Refer a Friend - sawitonline.com";
		$mail_content ='<html>	<body> <table width="100%" border="0">  <tr>
		<td>'.$_REQUEST['my_message'].'</td>
		</tr>  <tr>
		<td><a href="'.$newLink.'" target="_blank">Click Here</a> to register with sawitonline.com</td>
		</tr>	</table> </body> </html>';
		mimeMail($to_email,$subject,$mail_content,'','',$_REQUEST['email'],'','',FALSE, $AttachList);

		*/

		return true;
	}
}//End class
?>