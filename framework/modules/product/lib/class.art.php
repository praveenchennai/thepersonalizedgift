<?php
class Art extends FrameWork {
	var $errorMessage;
	function Art() {
		$this->FrameWork();
	}
	/**
	 * Listing Art with pagination by given user_id
	 * @param <Page Number> $pageNo
	 * return array
	 */
	function artList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy) {
		$sql		= "SELECT * FROM art_work WHERE 1";
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	/**
	 * Getting art based on given id
	 * @param <id> $id
	 * return array
	 */
	function artGet ($id) {
		$rs = $this->db->get_row("SELECT * FROM art_work WHERE id='{$id}'", ARRAY_A);
		return $rs;
	}	
	/**
	 * Add Edit Art
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message	 
	 */
	function artAddEdit (&$req) {
		global $store_id;
		extract($req);
		if (!$_FILES['image']['error']) {
			$image_extension = substr($_FILES['image']['name'], strrpos($_FILES['image']['name'], ".")+1);
		}
		$active = $active ? 'Y' : 'N';
		if(!trim($name)) {
			$message = "Art Name is required";
		}else{
			$array = array("payment_receiver"=>$payment_receiver,"name"=>$name, "heading"=>$heading, "user_id"=>$member_id,"description"=>$description, "image_extension"=>$image_extension, "active"=>$active);
			if($id) {
				$array['id'] = $id;
				$this->db->update("store", $array, "id='$id'");
			} else {
				$this->db->insert("store", $array);
				$id = $this->db->insert_id;
			}
			$this->db->query("DELETE FROM store_category WHERE store_id = '$id'");
			if($req['store_categories']) {
				foreach ($req['store_categories'] as $category_id) {
					$this->db->insert("store_category", array("store_id"=>$id, "category_id"=>$category_id));
				}
			}
			if(!$_FILES['image']['error']) {
				_upload(SITE_PATH."/modules/product/art/images/", $id.".".$image_extension, $_FILES['image']['tmp_name'], 1);
			}
			return true;
		}
		return $message;
	}
	/**
	 * Deleting  store by given id
	 * @param <id> $id
	 * return array
	 */
	function storeDelete ($id) {
		$this->db->query("DELETE FROM store WHERE id='$id'");
	}
	/**
	 * Getting store category based on store id
	 * @param <store_id> $storeID
	 * return array
	 */
	function storeCategoriesGet ($storeID) {
		$sql = "SELECT c.category_id, c.category_name FROM master_category c LEFT JOIN store_category s ON (c.category_id = s.category_id AND s.store_id = '$storeID') WHERE c.parent_id='0' AND c.is_in_ui='N' AND s.category_id IS NULL ORDER BY 2";
		$rs['all']['category_id'] 	= $this->db->get_col($sql, 0);
        $rs['all']['category_name'] = $this->db->get_col("", 1);
        $sql = "SELECT c.category_id, c.category_name FROM master_category c, store_category s WHERE c.category_id = s.category_id AND c.parent_id = '0' AND c.is_in_ui='N' AND s.store_id = '$storeID' ORDER BY 2";

		$rs['store']['category_id'] 	= $this->db->get_col($sql, 0);
        $rs['store']['category_name'] 	= $this->db->get_col("", 1);
		
        return $rs;
	}
	/**
	 * Loading Stores in a combo	 
	 * return array
	 */
	function storeCombo () {
		$sql		= "SELECT name, heading FROM store WHERE 1";
        $rs['name'] = $this->db->get_col($sql, 0);
        $rs['heading'] = $this->db->get_col("", 1);
        return $rs;
	}
	/**
	 * Add Edit Payment
	 *
	 * @param <POST/GET Array> $req
	 * @return Boolean/Error Message	 
	 */
	function paymentAddEdit (&$req) {
		extract($req);
		global $store_id;
		switch($payment_provider){
			case 'R':
				$pay_userid		=	$pay_userid;
			break;			
			case 'A':
				$pay_userid		=	$pay_useridauth;
			break;				
		}	
		if(!trim($payment_provider)) {
			$message = "Select the provider";
		}else {
			$array = array("store_id"=>$store_id,"payment_provider"=>$payment_provider,
							"pay_userid"=>$pay_userid,"pay_password"=>$pay_password,
							"pay_api_signature"=>$pay_api_signature,"pay_transkey"=>$pay_transkey,
							"pay_email"=>$pay_email,"pay_keyfile"=>$pay_keyfile,"pay_configfile"=>$pay_configfile);
			if($id) {
					$array['id'] = $id;
					$this->db->update("store_payment", $array, "id='$id'");
			} else {
					$this->db->insert("store_payment", $array);
					$id = $this->db->insert_id;
			}
			return true;
		}
		return $message;
	}
	/**
	 * Getting payment details by id
	 * @param <id> $id
	 * return array
	 */
	function getPayment($id){		
		$rs = $this->db->get_row("SELECT * FROM store_payment WHERE id = '$id'", ARRAY_A);
		return $rs;
	}
	/**
	 * Getting payment details by given store_id
	 * @param <store_id> $store_id
	 * return array
	 */
	function getPaymentbystoreid($store_id){		
		$rs 	= 	$this->db->get_row("SELECT * FROM store_payment WHERE store_id = '$store_id'", ARRAY_A);
		return $rs;
	}
	
	// Update by Retheesh Kumar for Taking Art
	function addStore($data)
	{
		$sql = "select * from store where name='".$data['name']."'";
		$rs = $this->db->get_results($sql);
		if (count($rs)>0)		
		{
			return false;
		}
		else 
		{
			print "yes";
			print_r($data);	
			$this->db->insert("store",$data);
			return true;
		}
	}
	
	//Taking Art End
}
?>