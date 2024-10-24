<?php
class StoreRedirect extends FrameWork {

	function StoreRedirect()
	{
		$this->FrameWork();
	}

	function setArrData($szArrData)
	{
		$this->arrData 	= 	$szArrData;
	}

	function getArrData()
	{
		return $this->arrData;
	}

	function setErr($szError)
	{
		$this->err 		.= 	"$szError";
	}

	function getErr()
	{
		return $this->err;
	}

	/**
	 * GetStorenameforRedirect
	 *
	 * @return the redirect storename
	 */
	function GetStorenameforRedirect($url) {
	$storename		=	"";
	$qry			=	"select name from store where redirect_url LIKE '%".$url."%' ";
	//$qry			=	"select name from store where redirect_url = '$url' ";
	$row 			= 	$this->db->get_row($qry,ARRAY_A);
	$storename		=	$row['name'];
	return $storename;
	
	}
	/**
  	 * This function is used wether the URL is exist or not
  	 * Author   : Shinu
  	 * Created  : 07/Jan/2007
  	 * Modified : 07/Jan/2007 By Shinu
  	 */
	function isURLexist($url) {
		$url1 = 'https://'.str_ireplace('http://','',str_ireplace('https://','',$url));
		$url2 = 'http://'.str_ireplace('http://','',str_ireplace('https://','',$url));
	$status			=	0;
	$qry			=	"select name from store where redirect_url ='$url1' OR redirect_url ='$url2'";
	$row 			= 	$this->db->get_results($qry,ARRAY_A);
	if(count($row)>0){
	 $status			=	1;
	 }
	return $status;
	}
	/**
  	 * This function is used get the store name of the site url
  	 * Author   : Shinu
  	 * Created  : 07/Jan/2007
  	 * Modified : 07/Jan/2007 By Shinu
  	 */
	function getStoreName($url) {
	$storename		=	"";
		$url1 = 'https://'.str_ireplace('http://','',str_ireplace('https://','',$url));
		$url2 = 'http://'.str_ireplace('http://','',str_ireplace('https://','',$url));
	$qry			=	"select name from store where redirect_url ='$url1' OR redirect_url ='$url2'";
	$row 			= 	$this->db->get_row($qry,ARRAY_A);
	$storename		=	$row['name'];
	return $storename;
	}
	function getStoreField($store_name) {
	$metaverify	=	"";
	$qry			=	"select * from store where name ='$store_name' ";
	$row 			= 	$this->db->get_row($qry,ARRAY_A);
	$metaverify		=	$row['meta_verify-v1'];
	return $metaverify;
	}

	
}
?>