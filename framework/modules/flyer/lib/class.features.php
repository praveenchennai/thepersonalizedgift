<?
//
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
class Features extends FrameWork {

	function Features()
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
	

	function listAllFeatures($keysearch='N',$features_search='',$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy="1")
	{	
			$qry		=	"select * from flyer_form_features where flyer_id='0'";
			
		if($keysearch=='Y' && $features_search)
			$qry	.=	" and label LIKE '%$features_search%' ";
								
			$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
	function FeatureGet($feature_id) {
		$rs = $this->db->get_row("SELECT * FROM flyer_form_features WHERE feature_id='$feature_id'", ARRAY_A);
		return $rs;
	}
	
	function FeatureAddedit($req,$sId)
	{
		extract($req);
		unset($req['sess'],$req['sId'],$req['fId'],$req['mId'],$req['PHPSESSID'],$req['limit']);
		
		if(trim($required))
				$required="Y";
			else
				$required="N";
		if(trim($active))
				$active="Y";
			else
				$active="N";
	
		if(!trim($label)) 
		{
			$message 				=	"$sId name is required";
			return $message;
			}
			else {
					$array 			= 	array("label"=>$label,"type"=>$type,"length"=>$length,"position"=>$position,"required"=>$required,"active"=>$active);
			}
			
			if($feature_id) { 
				$array['feature_id'] 	= 	$feature_id;
				$this->db->update("flyer_form_features", $array, "feature_id='$feature_id'");
			} else {			
				$this->db->insert("flyer_form_features", $array);
				$feature_id = $this->db->insert_id;
				$_SESSION['feature_id'] = $feature_id;
			}
	return true;
	}
	
	function FeatureOptionAdd($req,$featureId)
	{
		extract($req);
		if(count($Value)>0)
		{
			foreach($Value as $option)
			{
				if($option!="")
				{
					$array 			= 	array("feature_id"=>$featureId,"name"=>$option,"active"=>"Y");
					$this->db->insert("flyer_form_features_values", $array);
				}
			}
		}
		return true;
	}

	function featuresDelete($features_id)
	{
		$qry	=	"delete from flyer_form_features where feature_id='$features_id'";
		$this->db->query($qry);
		return true;
	}
	function GetAllFeatureOptions($feature_id)
	{
		$qry				=	"select * from flyer_form_features_values where feature_id='$feature_id' and active='Y'";
		$rs['option_id'] 	= 	$this->db->get_col($qry, 2);
		$rs['option_name'] 	= 	$this->db->get_col($qry, 2);
		return $rs;
	}
	function listAllFeatureItems($feature_id='0',$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy="1")
	{
		$qry	=	"select * from flyer_form_features_values where feature_id='$feature_id'";
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
	function AttributeItemDelete($item_id)
	{
		$qry	=	"delete from flyer_form_features_values where value_id='$item_id'";
		$this->db->query($qry);
		return true;
	}
	
	function FeatureOptionedit($req,$sId)
	{
		extract($req);
		unset($req['sess'],$req['sId'],$req['fId'],$req['mId'],$req['PHPSESSID'],$req['limit']);
		$array 			= 	array("name"=>$name,"feature_id"=>$feature_id);
		//$array['value_id'] 	= 	$item_id;
		$this->db->update("flyer_form_features_values", $array, "value_id='$item_id'");
			
		return true;
	}
	
	
	function FeatureOptionGet($id)
	{
		$rs = $this->db->get_row("SELECT * FROM flyer_form_features_values WHERE value_id='$id'", ARRAY_A);
		return $rs;
	}
	
	
	/**
	 * @description	-- The following method is used for combo filling the all the features
	 *
	 *
	 *
	 * @author vimson@newagesmb.com
	 */
	function getAllFeaturesForComboFilling()
	{
		
		$FeatureCombo	=	array();
		$Qry			=	"SELECT feature_id, label FROM flyer_form_features WHERE active = 'Y' AND flyer_id = '0'";
		
		$FeatureCombo['feature_id']	=	$this->db->get_col($Qry, 0);
		$FeatureCombo['label']		=	$this->db->get_col($Qry, 1);
		
		return $FeatureCombo;
	}
	
	
	
	
	
	
	
	
}

?>