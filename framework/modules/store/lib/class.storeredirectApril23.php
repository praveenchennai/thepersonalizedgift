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

	
}
?>