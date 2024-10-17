<?php
/***************************************************************************************
*  Author Robin James <robin@newagesmb.com>
*  Wriiten 0n 04-oct-2006
*  Description : Class used for performing message operations operation
*  Copyright notice
*  © 2006 Newagesmb [http://www.newagesmb.com]
*  All rights reserved
*  This script is part of the newagesmb project. The newagesmb project
*  is not a free software; you should not redistribute it and/or modify it
*  Permission to copy, modify, and distribute this software and its documentation,
*  with or without modification, for any purpose, with or without fee or royalty is
*  not permitted
***************************************************************************************/
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");

class messagemodule extends FrameWork {
	var $id;
	var $subject;
	var $comment;

	function messagemodule($id="", $subject="",$comment="") {
		$this->id = $id;
		$this->subject = $subject;
		$this->comment=$comment;
		$this->FrameWork();
	}
	/**
	 * Inbox 
	 *
	 * @param <Page Number> $pageNo
	 */
	function blockUser($id) {
		$cid=$_SESSION['memberid'];
		$uname=$this->getUserName($cid);
		$array = array("username"=>$uname,"block_name"=>$id);
			$this->db->insert("message_blocked_user", $array);
	}
	/**
	 * Inbox 
	 *
	 * @param <Page Number> $pageNo
	 */
	function Inbox($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
	$objUser		= new User();
		$cid=$_SESSION['memberid'];
		$uname=$this->getUserName($cid);
		$sql		= "SELECT a.* FROM `message` a left join `message_blocked_user` b on (a.`from`=b.block_name) where b.id is null and a.`to`='$uname'";
		
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		for($i=0;$i<1;$i++)
		{	
			for($j=0;$j<sizeof($rs[$i]);$j++)
			{
				
				$friendName= $rs[$i][$j]->from;
				$rsUser=$objUser->getUsernameDetails($friendName);
				if ($this->config['member_screen_name']=='Y')
		{
				$rs[$i][$j]->screen_name=$rsUser[screen_name];
				$rs[$i][$j]->mem_type=$rsUser[mem_type];
		}
			
				$rs[$i][$j]->screen_name=$rsUser[screen_name];
				$rs[$i][$j]->first_name=$rsUser[first_name];
				$rs[$i][$j]->last_name=$rsUser[last_name];
				$rs[$i][$j]->nick_name=$rsUser[nick_name];
			}
			
		}
		
		
		return $rs;
		
	}

	/**
	 * Sent 
	 *
	 * @param <Page Number> $pageNo
	 */
	function Sent($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
	$objUser		= new User();
		$cid=$_SESSION['memberid'];
		$uname=$this->getUserName($cid);
		$sql		= "SELECT * FROM message_sent where `from`='$uname'";
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		for($i=0;$i<1;$i++)
		{	
			for($j=0;$j<sizeof($rs[$i]);$j++)
			{
				
				$friendName= $rs[$i][$j]->to;
				$rsUser=$objUser->getUsernameDetails($friendName);
				$rs[$i][$j]->screen_name=$rsUser[screen_name];
				$rs[$i][$j]->first_name=$rsUser[first_name];
				$rs[$i][$j]->last_name=$rsUser[last_name];
				$rs[$i][$j]->nick_name=$rsUser[nick_name];
			}
			
		}
	
		return $rs;
		
	}


	/**
	 * Draft 
	 *
	 * @param <Page Number> $pageNo
	 */
	function Draft($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
		$cid=$_SESSION['memberid'];
		$uname=$this->getUserName($cid);
		$sql		= "SELECT * FROM message_draft where `from`='$uname'";
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		$objUser		= new User();
		for($i=0;$i<1;$i++)
		{	
			for($j=0;$j<sizeof($rs[$i]);$j++)
			{
				
				$friendName= $rs[$i][$j]->to;
				$rsUser=$objUser->getUsernameDetails($friendName);
				
				$rs[$i][$j]->screen_name=$rsUser[screen_name];
				$rs[$i][$j]->first_name=$rsUser[first_name];
				$rs[$i][$j]->last_name=$rsUser[last_name];
				$rs[$i][$j]->nick_name=$rsUser[nick_name];
			}
			
		}
		return $rs;
	}

	/**
	 * for senting private messages
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message
	 */
	function messageSent(&$req) {
		extract($req);
		
		if(!trim($to)) {
			$message = "Recepient Username should not be blank";
		} else if(!trim($subject)){
		$message = "Subject field should not be blank";
		}else if(!trim($comments)){
		$message = "Message field should not be blank";
		}
		else if($this->checkValid($to)==1){
		$message = "Invalid username";
		}
		else
		{
	
			$cid=$_SESSION['memberid'];
			$from=$this->getUserName($cid);
			$date=date("Y-m-d H:i:s");
			$status='U';
			$array = array("from"=>$from,"to"=>$to,"subject"=>$subject,"comments"=>$comments,"datetime"=>$date,"status"=>$status);
			
			$this->db->insert("message", $array);
			$id = $this->db->insert_id;
			
			if($id)
			{
				$this->db->insert("message_sent", $array);
			}
			if($chkContacts)
			{
				$flag=$this->checkValid($to);
				if($flag==0)
				{
					$cid=$_SESSION['memberid'];
					$from=$this->getUserName($cid);
					$array = array("userid"=>$from,"friedid"=>$to);
					$this->db->insert("message_contacts", $array);
					return true;
				}

			}

			return true;
		}
		return $message;
	}

	/**
	 * add new contacts 
	 *
	 * @param <Page Number> $pageNo
	 */
	function addNewContacts(&$req) {
		extract($req);
		if(!trim($to)) {
			$message = "User name should not be blank";
		} else
		{
			$flag=$this->checkValid($to);
			if($flag==0)
			{
				$cid=$_SESSION['memberid'];
				$from=$this->getUserName($cid);
				$array = array("userid"=>$from,"friedid"=>$to);
				$this->db->insert("message_contacts", $array);
				return true;
			}else
			{
				if($flag==1)
				{
					$msg="$to is not a registered user";
				}
				else{
					$msg="$to is already in your contacts";
				}
				return $msg;
			}
		}
		return $message;
	}
	/**
  /**
	 * sending message as draft
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message
	 */

	function messageDraft(&$req) {
		extract($req);
		if(!trim($to)) {
			$message = "User name should not be blank";
		}
		else if(!trim($subject)){
		$message = "Subject field should not be blank";
		}else if(!trim($comments)){
		$message = "Message field should not be blank";
		}
		else if($this->checkValid($to)==1){
		$message = "Invalid username";
		} else {
			$cid=$_SESSION['memberid'];
			$from=$this->getUserName($cid);
			$date=date("Y-m-d H:i:s");
			$status='U';
			$array = array("from"=>$from,"to"=>$to,"subject"=>$subject,"comments"=>$comments,"datetime"=>$date,"status"=>$status);
			$this->db->insert("message_draft", $array);

			return true;
		}
		return $message;
	}

	/**
	 * Inbox 
	 *
	 * @param <Page Number> $pageNo
	 */
	function Read($id) {
	   $objUser		= new User();
		$sql		= "SELECT * FROM message where id=$id";
		$rs = $this->db->get_row($sql,ARRAY_A);
		
		$rsUser=$objUser->getUsernameDetails($rs[from]);
		$rs[from_nick]=$rsUser[nick_name];
		$rs[screen_name1]=$rsUser[screen_name];
		$rsUser=$objUser->getUsernameDetails($rs[to]);
		$rs[to_nick]=$rsUser[nick_name];
		$rs[screen_name2]=$rsUser[screen_name];
		$status='R';
		$array = array("status"=>$status);
		$this->db->update("message", $array, "id='$id'");
		return $rs;
	}
	/**
/**
	 * Inbox 
	 *
	 * @param <Page Number> $pageNo
	 */
	function checkAvailable($id,$friendid) {
		$sql		= "SELECT * FROM message where id=$id";
		$rs = $this->db->get_row($sql,ARRAY_A);
		$status='R';
		$array = array("status"=>$status);
		$this->db->update("message", $array, "id='$id'");
		return $rs;
	}
	/**
/**
	 * Read sent items 
	 *
	 * @param <Page Number> $pageNo
	 */
	function ReadSent($id) {
	$objUser		= new User();
		$sql		= "SELECT * FROM message_sent where id=$id";
		$rs = $this->db->get_row($sql,ARRAY_A);
		
		$rsUser=$objUser->getUsernameDetails($rs[from]);
		$rs[from_nick]=$rsUser[nick_name];
		$rs[screen_name1]=$rsUser[screen_name];
		$rsUser=$objUser->getUsernameDetails($rs[to]);
		$rs[to_nick]=$rsUser[nick_name];
		$rs[screen_name2]=$rsUser[screen_name];
		$status='R';
		$array = array("status"=>$status);
		$this->db->update("message_sent", $array, "id='$id'");
		
		return $rs;
		
	}
	/**

/**
	 * Read Draft items 
	 *
	 * @param <Page Number> $pageNo
	 */
	function ReadDraft($id) {
	$objUser		= new User();
		$sql		= "SELECT * FROM message_draft where id=$id";
		$rs = $this->db->get_row($sql,ARRAY_A);
		$rsUser=$objUser->getUsernameDetails($rs[from]);
		$rs[from_nick]=$rsUser[nick_name];
		$rs[screen_name1]=$rsUser[screen_name];
		$rsUser=$objUser->getUsernameDetails($rs[to]);
		$rs[to_nick]=$rsUser[nick_name];
		$rs[screen_name2]=$rsUser[screen_name];
		$status='R';
		$array = array("status"=>$status);
		$this->db->update("message_draft", $array, "id='$id'");
		return $rs;
	}
	/**

/**
	 * Read Draft items 
	 *
	 * @param <Page Number> $pageNo
	 */
	function findContacts($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
	$objUser		= new User();
		$cid=$_SESSION['memberid'];
		$from=$this->getUserName($cid);
		$sql		= "Select
					message_contacts.id,
					message_contacts.friedid,
					member_master.first_name,
					member_master.id as user_id,
					member_master.image,
					member_master.last_name
					From
					message_contacts
					Inner Join member_master ON member_master.username = message_contacts.friedid
					Where
					message_contacts.userid ='$from'";

		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		
		for($i=0;$i<1;$i++)
		{	
			for($j=0;$j<sizeof($rs[$i]);$j++)
			{
				
				$friendName= $rs[$i][$j]->friedid;
				$rsUser=$objUser->getUsernameDetails($friendName);
				
				
				$rs[$i][$j]->screen_name=$rsUser[screen_name];
				$rs[$i][$j]->nick_name=$rsUser[nick_name];
			}
			
		}
		
		return $rs;
	}
	/**


/**
	 * Read Draft items 
	 *
	 * @param <Page Number> $pageNo
	 */

	function viewContacts() {
		$objUser		= new User();
		$cid=$_SESSION['memberid'];
		$from=$this->getUserName($cid);
		$sql= "SELECT * FROM `message_contacts` WHERE `userid`='$from'";
		
		$rs = $this->db->get_results($sql);
		for($i=0;$i<sizeof($rs);$i++)
		{
			$friendName= $rs[$i]->friedid;
			$rsUser=$objUser->getUsernameDetails($friendName);
			$rs[$i]->first_name=$rsUser[first_name];
			$rs[$i]->last_name=$rsUser[last_name];
			$rs[$i]->nick_name=$rsUser[nick_name];
			
		}
		return $rs;
	}
/**
	 * Read Draft items 
	 *
	 * @param <Page Number> $pageNo
	 */
	function findBlocked($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
		$cid=$_SESSION['memberid'];
		
		if ($this->config['member_screen_name']=='Y')
		{
			$member_search_fields = "member_master.username,
					member_master.first_name,
					member_master.last_name,member_master.screen_name";
		}
		else 
		{
			$member_search_fields = "member_master.username,
					member_master.first_name,
					member_master.last_name";
		}	
		
		$from=$this->getUserName($cid);
		$sql		= "Select
					message_blocked_user.id,
					message_blocked_user.block_name,
					$member_search_fields
					From
					message_blocked_user
					Inner Join member_master ON member_master.username = message_blocked_user.block_name
					Where
					message_blocked_user.username ='$from'";

		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		
		return $rs;
	}
	/**
 /**
	 * Get UserName 
	 *
	 * @param <Page Number> $pageNo
	 */
	function getUserName($id) {
		$sql= "select username from member_master where id=$id";
		$rs = $this->db->get_results($sql);
		$username=$rs[0]->username;
		return $username;
	}

	/**
 /**
	 * Delete 
	 *
	 * @param <Page Number> $pageNo
	 */
	function Delete($id,$table) {
		$sql= "Delete  FROM $table where id=$id";
		$this->db->query($sql);
	}
	/**
	 *function to check the avilabilty of username
	 *
	 * @param <POST/GET Array> $req
	 * @param [Error Message] $message
	 */
	function checkValid($friendId) {
		$cid=$_SESSION['memberid'];
		$userId=$this->getUserName($cid);
		$flag=0;
		$sql		= "SELECT * FROM member_master WHERE username='$friendId' and active='Y'";
		$rs = $this->db->get_results($sql);
		if(count($rs)==0)
		{
			$flag=1;
			return $flag;
		}else
		{
			$sql		= "SELECT * FROM message_contacts WHERE friedid='$friendId' and userid='$userId'";
			$rs = $this->db->get_results($sql);
			if(count($rs)>0)
			{
				$flag=2;
				return $flag;
			}
		}
		return $flag;
	}


}
?>