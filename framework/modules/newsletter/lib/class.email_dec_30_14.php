<?php

class Email extends FrameWork {

	function Email() {
		$this->FrameWork();
	}

	function generalList ($pageNo, $limit=10, $params='', $output=OBJECT, $orderBy) {
	
		$sql		= "SELECT * FROM email_config  where bit_type='1'";

		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	//==========================
	function messageList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
		$sql		= "SELECT * FROM email_config where bit_type='2'";

		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
   //============================
    /**
           * This is used for updating email_config
           * Author   : Unknown
           * Created  : Unknown
           * Modified : 28/Nov/2007 By Salim
		   * Description : This will check whether the field 'bcc' is present in the table.
           */

	function generalEdit (&$req) {
	
		$checkbcc = $this->ChkForFields('email_config','bcc');

		extract($req);

		if(!trim($subject)) {
			$message = "Email Subject is required";
		} elseif (!trim($body)) {
			$message = "Email Body is required";
		} else {
					if ($checkbcc==1){
						$array = array("subject"=>$subject, "body"=>$body, "bcc"=>$bcc);
						}
					else{			
						$array = array("subject"=>$subject, "body"=>$body);
						}
			$this->db->update("email_config", $array, "id='$id'");
			return true;
		}
		return $message;
	}
	function generalEditMsg (&$req) {

		extract($req);
		if(!trim($subject)) {
			$message = "Message Subject is required";
		}elseif (!trim($body)) {
			$message = "Message Body is required";
		}elseif (!trim($description)) {
			$message = "Message Description is required";
		} else {
				$array = array("description"=>$description,"subject"=>$subject, "body"=>$body);
				$this->db->update("email_config", $array, "id='$id'");
				return true;
		}
		return $message;
	}
	
	function generalGet ($id) {
		$sql		= "SELECT * FROM email_config WHERE id = '$id'";

		$rs = $this->db->get_row($sql, ARRAY_A);
		return $rs;
	}
	
	function generalGetByName($name=''){	
		$sql		= "SELECT * FROM email_config WHERE name = '$name'";			
		$rs = $this->db->get_row($sql, ARRAY_A);
		return $rs;
	}
	
	    /**
           * This is used for sending out email
           * Author   : Unknown
           * Created  : Unknown
           * Modified : 28/Nov/2007 By Salim
		   * Description : This will check whether the field 'bcc' is present in the table.
		   * 			   If yes, will check whether the status to send out bcc to administrator.
           */

	function send($email_name, $email_header, $dynamic_vars,$store_id='') {	
		
		$rs = $this->generalGetByName($email_name);	
		$subject = $rs['subject'];
		$body	 = $rs['body'];
		
		
		if (is_array($dynamic_vars)) {
			foreach ($dynamic_vars as $key=>$val) {
				$body = str_replace("%_{$key}_%", $val, $body);
				$subject = str_replace("%_{$key}_%", $val, $subject);
			}			
		}
		
	
		/*
		for taking email from the admin for adding Bcc
		*/
		//==============
		$sql="select id from member_types  where type='admin'";
		$rs = $this->db->get_row($sql, ARRAY_A);
		$id 	= 	$rs['id'];
		
		$sql="select email from member_master where mem_type='$id'";
		$rs = $this->db->get_row($sql, ARRAY_A);
	
		
		$email 	= 	$rs['email'];
		
		if($store_id !=''){
		
			$res = $this->db->get_row("SELECT * FROM store WHERE id='$store_id'", ARRAY_A);
			if($res['email'] !='')
	  			$store_email=	$res['email'];
			else{
				$sql	=	"select T1.email from member_master T1,store T2 where T1.id = T2.user_id and T2.id = ".$store_id ;
				$res = $this->db->get_row($sql, ARRAY_A);
				$store_email=	$res['email'];
			}
			$email=$store_email;
		}
		
		

		
		$checkbcc = $this->ChkForFields('email_config','bcc');
			if ($checkbcc==1){
				$sql_qry="select bcc from email_config where name = '$email_name'";
				$rs = $this->db->get_row($sql_qry, ARRAY_A);
					if($rs['bcc']!='Y'){
						$email 	= 	'';
					}
			}

		

		
		//=========================
		//echo "mimeMail(".$email_header['to'], $subject, $body, strip_tags($body), '', $email_header['from'],'', $email,'',''.")";
		if($body!=''){
		return mimeMail($email_header['to'], $subject, $body, strip_tags($body), '', $email_header['from'],'', $email,'','');
		}
	}
	
	/**
           * This is used check whether a field is present in a table
           * Author   : Salim	
           * Created  : 28/Nov/2007
           * Modified : 28/Nov/2007 By Salim
           */

	function ChkForFields($table,$chkfields) {
	$fields = mysql_list_fields(DB_NAME, $table);
		$columns = mysql_num_fields($fields);
		
		for ($i = 0; $i < $columns; $i++) {
			if(mysql_field_name($fields, $i)==$chkfields){
				$checkvar = 1;
				return $checkvar;//If passed field is present in the table this will return 1.
				}
		}
	}
	
	
	/**
           * This function used to send an email when various actions are done
           * Author   : vinoy	
           * Created  : 17/jan/2008
         
           */
	function mailSend($type,$MemberId,$req)
	{
	     include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
		 include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
		 include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");
		 include_once(FRAMEWORK_PATH."/modules/album/lib/class.property.php");
		 $user           =   new User();
		 $album			=	new Album();
		 $flyer			=	new	Flyer();
		 $property 		=	new Property();
		 
		if($type=="publish")
		{
			foreach ($req['category_id'] as $key=>$album_id)
			{
			$propid=$album_id;
			}
			$rsAlbm = $flyer-> getPropertyDetails($propid);
			$member         =   $user->getUserDetails($MemberId);
			$mail_header = array();
			$mail_header['from'] 	= 	$this->config['admin_email'];
		    $mail_header["to"]      =   $member['email'];
			$dynamic_vars = array();
			$dynamic_vars['FIRST_NAME']  =$member['name'];
		    $dynamic_vars['PROPERTY_NAME']   =$rsAlbm['flyer_name'];
			$dynamic_vars['PROPERTY_DISC']= $rsAlbm['description'];

			$this->send("Property Publish Confirmation",$mail_header,$dynamic_vars);
		}
    	if($type=="property_upload")
		{
		 $fid=$req['flyer_id'];
		 $content	    =	'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
											<html xmlns="http://www.w3.org/1999/xhtml">
											<head>
											<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
											<title>Untitled Document</title>
											</head>
											<body>
												<table>
												    <tr>
													     <td><b>'.$req['flyer_name'].'</b></td>
													</tr>
													 <tr>
														<td>'.$req['description'].'</td>
													</tr>
													
												</table>
											</body>
											</html>';
		
		 
		                $mail_header = array();
					 	$mail_header['from'] 	= 	$this->config['admin_email'];
						$mail_header["to"]      =   $req['contact_email'];
						$dynamic_vars = array();
						$dynamic_vars['FIRST_NAME']  =$req['contact_name'];
						$dynamic_vars['PROPERTY_NAME']=$req['flyer_name'];
						$dynamic_vars['PROPERTY_DISC']=$content;
						//dynamic_vars['LINK']= "<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"flyer", "pg"=>"list"),"act=property_view&flyer_id=$fid&propid=$fid")."\">Click here to view property details page</a>";
						$this->send("Property_Upload_Confirmation",$mail_header,$dynamic_vars);
						
		}
		if($type=="payment")
		{
		          
					$member         =   $user->getUserDetails($MemberId);
					$mail_header = array();
					$mail_header['from'] 	= 	$this->config['admin_email'];
					$mail_header["to"]      =   $member['email'];
					$dynamic_vars = array();
					$dynamic_vars['FIRST_NAME']     =  $req['fname'];
					$dynamic_vars['EMAIL_ADDRESS']  =  $req['billing_email'];
					$dynamic_vars['ADDRESS']        =  $req['address1'];
					$dynamic_vars['CITY']           =  $req['city'];
					$dynamic_vars['STATE']          =  $req['state'];
					$dynamic_vars['CONTACT']        =  $req['telephone'];
					$dynamic_vars['CARD_TYPE']      =  $req['card_type'];
					$dynamic_vars['CARD_NO']        =  $req['card_number'];
					//$dynamic_vars['PROPERTY_NAME']=$content;
					
					$this->send("Payment_Update_Confirmation",$mail_header,$dynamic_vars);
					
		
		}
		if($type=="upload_advertisement")
		{
		
			 include_once(FRAMEWORK_PATH."/modules/advertiser/lib/class.advertiser.php");
			 $objAdvertiser	=	new Advertiser();
					$adv=	$objAdvertiser->getAdvertisement ($req['id'] );
					$member         =   $user->getUserDetails($MemberId);
					$mail_header = array();
					$mail_header['from'] 	= 	$this->config['admin_email'];
					$mail_header["to"]      =   $member['email'];
					
					$dynamic_vars = array();
					$dynamic_vars['FIRST_NAME']  =$member['name'];
					$dynamic_vars['ADV_TITLE']   =$adv['adv_title'];
					$dynamic_vars['ADV_DISC']    =$adv['adv_description'];
					$dynamic_vars['ADV_URL']     =$adv['adv_url'];
					
					$this->send("Advertisement_Upload_Confirmation",$mail_header,$dynamic_vars);
		
		}
		if($type=="assignPropertyToBroker")
		{
		            $brokid=$_REQUEST['BrokerId'];
					$propid=$_REQUEST['PropertyId'];
					//$rsFlbasic		=	$flyer->getFlyerBasicFormData($flyer_id);
					$rsAlbm	        =   $album->getAlbumDetails($propid); 
					
		            $member         =   $user->getUserDetails($MemberId);
					$broker         =   $user->getUserDetails($brokid);
					/*$tomail=array('$member['email']','$broker['email']');
					$countmail=count($tomail);
					for($i=0;$i<$countmail;$i++)
		            {
					*/
					$mail_header = array();
					$mail_header['from'] 	= 	$this->config['admin_email'];
					$mail_header["to"]      =   $member['email'];
		            $dynamic_vars = array();
					$dynamic_vars['FIRST_NAME']  =$member['name'];
					$dynamic_vars['BROKER_NAME']   =$broker['name'];
					$dynamic_vars['PROPERTY_NAME']   =$rsAlbm['title'];
					
					$this->send("Assign_property_to_Broker",$mail_header,$dynamic_vars);
		}
		
		if($type=="assignPropertyToManager")
		{
		            $mgrid=$_REQUEST['manager_id'];
					$propid=$_REQUEST['PropertyId'];
					//$rsFlbasic		=	$flyer->getFlyerBasicFormData($flyer_id);
					$rsAlbm	        =   $album->getAlbumDetails($propid); 
					
		            $member         =   $user->getUserDetails($MemberId);
					$manager         =   $user->getUserDetails($mgrid);
					/*$tomail=array('$member['email']','$manager['email']');
					$countmail=count($tomail);
					for($i=0;$i<$countmail;$i++)
		            {
					*/
					$mail_header = array();
					$mail_header['from'] 	= 	$this->config['admin_email'];
					$mail_header["to"]      =   $member['email'];
		            $dynamic_vars = array();
					$dynamic_vars['FIRST_NAME']  =$member['name'];
					$dynamic_vars['BROKER_NAME']   =$broker['name'];
					$dynamic_vars['PROPERTY_NAME']   =$rsAlbm['title'];
					
					$this->send("Assign_property_to_Broker",$mail_header,$dynamic_vars);
		}

        if($type=="PropertyBookingOwner")
		{


		            $PropertyId			=	$_SESSION['PropertyId'];
					$CheckIn			=	$_SESSION['CheckIn'];
					$CheckOut			=	$_SESSION['CheckOut'];
					$MemberId			=	$_SESSION['memberid'];
					//$rsFlbasic		=	$flyer->getFlyerBasicFormData($flyer_id);
					//$rsAlbm	        =   $album->getAlbumDetails($PropertyId); 
					$rsAlbm = $flyer->getPropertyDetails($PropertyId);
	

					$seller         =   $user->getUserDetails($rsAlbm['user_id']);

					$buyer         =   $user->getUserDetails($MemberId);

					$managerId = $property ->getAssignedPropertyUserIdManager($PropertyId);
					if($managerId)
					{
						$manager         =   $user->getUserDetails($managerId[0]);
					}
			    	$brokerId = $property ->getAssignedPropertyUserId($PropertyId);

					if($brokerId)
					{
					    $broker         =   $user->getUserDetails($brokerId[0]);
					}
					
					$mail_header = array();
					$mail_header['from'] 	= 	$this->config['admin_email'];
					$mail_header["to"]      =   $seller['email'].",".$email['email'];
		            $dynamic_vars = array();
			    	$dynamic_vars['FIRST_NAME']  =$seller['first_name'];
			    	$dynamic_vars['BUYER_NAME']  =$buyer['first_name'];
			        $dynamic_vars['BROKER_NAME']  =$broker['first_name'];
					$dynamic_vars['MANAGER_NAME']  =$manager['first_name'];
					$dynamic_vars['PROPERTY_NAME']   =$rsAlbm['flyer_name'];
					$dynamic_vars['PROPERTY_DISC']= $rsAlbm['description'];
					$dynamic_vars['PROPERTY_BOOK_PRICE']= $rsAlbm['booking_price'];
					$dynamic_vars['PROPERTY_EXP_DATE']= $rsAlbm['expire_date'];
				
					$this->send("Property_Booking_Owner",$mail_header,$dynamic_vars);
		}
		
		
		if($type=="PropertyBookingBuyer")
		{
		            $PropertyId			=	$_SESSION['PropertyId'];
					$CheckIn			=	$_SESSION['CheckIn'];
					$CheckOut			=	$_SESSION['CheckOut'];
					$MemberId			=	$_SESSION['memberid'];
					//$rsFlbasic		=	$flyer->getFlyerBasicFormData($flyer_id);
					//$rsAlbm	        =   $album->getAlbumDetails($PropertyId); 
					$rsAlbm = $flyer-> getPropertyDetails($PropertyId);
		
					$seller         =   $user->getUserDetails($rsAlbm['user_id']);
					$buyer         =   $user->getUserDetails($MemberId);
					//$manager         =   $user->getUserDetails($mgrid);
					
					$managerId = $property ->getAssignedPropertyUserIdManager($PropertyId);
					if($managerId)
					{
						$manager         =   $user->getUserDetails($managerId[0]);
					}
					$brokerId = $property ->getAssignedPropertyUserId($PropertyId);
					if($brokerId)
					{
					    $broker         =   $user->getUserDetails($brokerId[0]);
					}
					$mail_header = array();
					$mail_header['from'] 	= 	$this->config['admin_email'];
					$mail_header["to"]      =   $buyer['email'].",".$email['email'];
		            $dynamic_vars = array();
					$dynamic_vars['FIRST_NAME']  =$seller['first_name'];
					$dynamic_vars['BUYER_NAME']  =$buyer['first_name'];
					$dynamic_vars['BROKER_NAME']  =$broker['first_name'];
					$dynamic_vars['MANAGER_NAME']  =$manager['first_name'];
					$dynamic_vars['PROPERTY_NAME']   =$rsAlbm['flyer_name'];
					$dynamic_vars['PROPERTY_DISC']= $rsAlbm['description'];
					$dynamic_vars['PROPERTY_BOOK_PRICE']= $rsAlbm['booking_price'];
					$dynamic_vars['PROPERTY_EXP_DATE']= $rsAlbm['expire_date'];
					$this->send("Property_Booking_Buyer",$mail_header,$dynamic_vars);
		}
		
		if($type=="PropertyBookingBroker")
		{
		            $PropertyId			=	$_SESSION['PropertyId'];
					$CheckIn			=	$_SESSION['CheckIn'];
					$CheckOut			=	$_SESSION['CheckOut'];
					$MemberId			=	$_SESSION['memberid'];
					//$rsFlbasic		=	$flyer->getFlyerBasicFormData($flyer_id);
					//$rsAlbm	        =   $album->getAlbumDetails($PropertyId); 
					$rsAlbm = $flyer-> getPropertyDetails($PropertyId);
		
					$seller         =   $user->getUserDetails($rsAlbm['user_id']);
					$buyer         =   $user->getUserDetails($MemberId);
					//list($AssignedUserId, $Role) = $property ->getAssignedPropertyUserId($PropertyId);
					$brokerId = $property ->getAssignedPropertyUserId($PropertyId);
					if($brokerId)
					{
					
						$broker         =   $user->getUserDetails($brokerId[0]);
						//$email=$album->getEmailid($propid);
						
						$mail_header = array();
						$mail_header['from'] 	= 	$this->config['admin_email'];
						$mail_header["to"]      =   $broker['email'].",".$email['email'];
						$dynamic_vars = array();
						$dynamic_vars['FIRST_NAME']  =$seller['first_name'];
						$dynamic_vars['BUYER_NAME']  =$buyer['first_name'];
						$dynamic_vars['BROKER_NAME']  =$broker['first_name'];
						$dynamic_vars['MANAGER_NAME']  =$manager['first_name'];
						$dynamic_vars['PROPERTY_NAME'] =$rsAlbm['flyer_name'];
						$dynamic_vars['PROPERTY_DISC'] = $rsAlbm['description'];
						$dynamic_vars['PROPERTY_BOOK_PRICE']= $rsAlbm['booking_price'];
						$dynamic_vars['PROPERTY_EXP_DATE']= $rsAlbm['expire_date'];
						
						$this->send("Property_Booking_Broker",$mail_header,$dynamic_vars);
				   }
		}
		
		if($type=="PropertyBookingManager")
		{
		            $PropertyId			=	$_SESSION['PropertyId'];
					$CheckIn			=	$_SESSION['CheckIn'];
					$CheckOut			=	$_SESSION['CheckOut'];
					$MemberId			=	$_SESSION['memberid'];
					//$rsFlbasic		=	$flyer->getFlyerBasicFormData($flyer_id);
					//$rsAlbm	        =   $album->getAlbumDetails($PropertyId); 
					$rsAlbm = $flyer-> getPropertyDetails($PropertyId);
		
					$seller         =   $user->getUserDetails($rsAlbm['user_id']);
					$buyer         =   $user->getUserDetails($MemberId);
					//list($AssignedUserId, $Role) = $property ->getAssignedPropertyUserIdManager($PropertyId);
					$managerId = $property ->getAssignedPropertyUserIdManager($PropertyId);
					if($managerId)
					{
						$manager         =   $user->getUserDetails($managerId[0]);
						//$email=$album->getEmailid($propid);
						
						$mail_header = array();
						$mail_header['from'] 	= 	$this->config['admin_email'];
						$mail_header["to"]      =   $manager['email'].",".$email['email'];
						$dynamic_vars = array();
						$dynamic_vars['FIRST_NAME']  =$seller['first_name'];
						$dynamic_vars['BUYER_NAME']  =$buyer['first_name'];
						$dynamic_vars['MANAGER_NAME']  =$manager['first_name'];
						$dynamic_vars['PROPERTY_NAME']   =$rsAlbm['flyer_name'];
						$dynamic_vars['PROPERTY_DISC']= $rsAlbm['description'];
						$dynamic_vars['PROPERTY_BOOK_PRICE']= $rsAlbm['booking_price'];
						$dynamic_vars['PROPERTY_EXP_DATE']= $rsAlbm['expire_date'];
						
						$this->send("Property_Booking_Manager",$mail_header,$dynamic_vars);
					}
		}
		


	
	}
	
	
}
?>