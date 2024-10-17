<?
//
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
class Flyer extends FrameWork {

	function Category()
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
	
	function getFlyerId()
	{
			$cur_date	=	date('Y-m-d');
			$flyerArray = array("modified_date"=>$cur_date,"expire_date"=>$cur_date,"publish"=>"N","active"=>"N");
			$this->db->insert("flyer_data_basic", $flyerArray);
			$flyer_id = $this->db->insert_id;
			return $flyer_id;
	}
	function GetTemplates()
	{
		$sql		= 	"SELECT  template_id, template_name FROM flyer_templates WHERE active='Y' ";
		$rs['template_id'] 	= 	$this->db->get_col($sql, 0);
		$rs['template_name'] = 	$this->db->get_col($sql, 1);
		return $rs;
	}
	
		function GetHeaderLinks($user_id)
	{
		$sql = "select * from member_links  where user_id='$user_id'";
		$rs = $this->db->get_row($sql,ARRAY_A);
		$header_link	=	"";
		if($rs['head_title1'] != "" && $rs['head_link1'] != "")
		{
		$header_link	.='<a href="'.$rs["head_link1"].'" class="body_links">'.$rs["head_title1"].'</a>  | ';
		}
		if($rs['head_title2'] != "" && $rs['head_link2'] != "")
		{
		$header_link	.='<a href="'.$rs["head_link2"].' "class="body_links">'.$rs["head_title2"].'</a>  | ';
		}
		if($rs['head_title3'] != "" && $rs['head_link3'] != "")
		{
		$header_link	.='<a href="'.$rs["head_link3"].' "class="body_links">'.$rs["head_title3"].'</a>   ';
		}
		return $header_link;
	
	}
	
	function GetFooterLinks($user_id)
	{
		$sql = "select * from member_links  where user_id='$user_id'";
		$rs = $this->db->get_row($sql,ARRAY_A);
		$footer_link	=	"";
		if($rs['footer_title1'] != "" && $rs['footer_link1'] != "")
		{
		$footer_link	.='<a href="'.$rs["footer_link1"].'" class="body_links">'.$rs["footer_title1"].'</a>  | ';
		}
		if($rs['footer_title2'] != "" && $rs['footer_link2'] != "")
		{
		$footer_link	.='<a href="'.$rs["footer_link2"].' "class="body_links">'.$rs["footer_title2"].'</a>  | ';
		}
		if($rs['footer_title3'] != "" && $rs['footer_link3'] != "")
		{
		$footer_link	.='<a href="'.$rs["footer_link3"].' "class="body_links">'.$rs["footer_title3"].'</a>   ';
		}
		return $footer_link;
	
	}
		

	
	function GetmemberImages()
	{
		$user_id		=	$_SESSION["memberid"];
		$rs = $this->db->get_row("SELECT photo,logo,email,username FROM member_master WHERE id=$user_id", ARRAY_A);
		return $rs;
	}
	
	// this function is used to track the activity of a flyer
	function addFlyerHit($flyer_id)
	{ 
		$sql		=	"select * from flyer_activity where flyer_id='$flyer_id'";
		$rs 		= 	$this->db->get_row($sql, ARRAY_A);
		
		if(count($rs)>0)
		{
			$hit	=	$rs['hit'];
			$hit	=	$hit+1;
			$this->db->query("update flyer_activity set hit='$hit' where flyer_id ='$flyer_id'");
		}
		else
		{
			$HitArray=	array("hit"=>"1","flyer_id"=>$flyer_id);
			$this->db->insert("flyer_activity", $HitArray);
		}
		return true;
	
	}	


	
	// display the flyers of a user
	function listMyFlyers($flag,$keysearch='N',$flyer_search='',$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy="1")
	{	
			$user_id	=	$_SESSION['memberid'];
			$qry		=	"select fb.*,fm.form_title from flyer_data_basic fb,flyer_form_master fm  where fb.active='Y' AND fb.form_id=fm.form_id ";
			//$qry		=	"select fb.*,fm.form_title from flyer_data_basic fb,flyer_form_master fm where fb.active='Y' AND fb.form_id=fm.category_name";
			if($flag=="U")
			$qry	.=	" AND fb.user_id='$user_id' ";
			if($keysearch=='Y' && $flyer_search)
			$qry	.=	" and fb.title LIKE '%$flyer_search%' "; 
			$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
	
	
	function GetFieldValue($field_id,$flyer_id)
	{
		$rs = $this->db->get_row("SELECT value FROM flyer_data_field_values  WHERE flyer_id ='$flyer_id' and field_id='$field_id'", ARRAY_A);
		return $rs['value'];
	}
	
	
	function GetFlyerData($flyer_id)
	{
		$rs = $this->db->get_row("SELECT * FROM flyer_data_basic fb,flyer_data_contact fc WHERE fb.flyer_id='$flyer_id' and fc.flyer_id='$flyer_id'", ARRAY_A);
		return $rs;
	}
	
	function GetFlyerCheckboxData($flyer_id)
	{
		$rs = $this->db->get_results("SELECT checkbox_id FROM flyer_data_checkbox_values  WHERE flyer_id ='$flyer_id'", ARRAY_A);
		return $rs;
	}
	


	/**
	 *
	 * The following method returns the flyer data for both preview and flyet HTML file creation
	 *
	 * @author v@newagesmb.com
	 */
	function getFlyerDataForPreview($flyer_id)
	{
		$FlyerData	=	array();
		
		$Qry1	=	"SELECT * FROM flyer_data_basic WHERE flyer_id = '$flyer_id'";
		$Row1	=	$this->db->get_row($Qry1, ARRAY_A);
		
		$FlyerData['flyer_id']		=	$Row1['flyer_id'];
		$FlyerData['user_id']		=	$Row1['user_id'];
		$FlyerData['form_id']		=	$Row1['form_id'];
		$FlyerData['title']			=	$Row1['title'];
		$FlyerData['description']	=	$Row1['description'];
		$FlyerData['template_id']	=	$Row1['template_id'];
		$FlyerData['expire_date']	=	$Row1['expire_date'];
		$FlyerData['modified_date']	=	$Row1['modified_date'];
		$FlyerData['image']			=	$Row1['image'];
		$FlyerData['publish']		=	$Row1['publish'];
		$FlyerData['active']		=	$Row1['active'];
		$FlyerData['template_id']	=	$Row1['template_id'];
		
		$FlyerData['blocks']		=	array();
		
		$Qry11	=	"SELECT show_reply, show_email FROM flyer_data_contact WHERE flyer_id = '$flyer_id' ";
		$Row11	=	$this->db->get_row($Qry11, ARRAY_A);
		$FlyerData['show_reply']	=	$Row11['show_reply'];
		$FlyerData['show_email']	=	$Row11['show_email'];
		
		$Qry2	=	"SELECT block_id, block_title, block_position FROM flyer_form_blocks WHERE form_id = '{$Row1['form_id']}' ORDER BY display_order ASC ";
		$Rows2	=	$this->db->get_results($Qry2, ARRAY_A);

		foreach($Rows2 as $Row2) {
			$BlockData		=	array();
			$block_id		=	$Row2['block_id'];
			$block_title	=	$Row2['block_title'];

			$Qry3		=	"SELECT 
								T1.feature_id AS feature_id, 
								T2.label AS label, 
								T3.value AS value 
							FROM flyer_map_form_feature AS T1 
							LEFT JOIN flyer_form_features AS T2 ON T2.feature_id = T1.feature_id 
							LEFT JOIN flyer_data_field_values AS T3 ON T3.field_id = T1.feature_id 
							WHERE 
							T3.flyer_id = '$flyer_id' AND T1.block_id = '$block_id'";
			$Rows3		=	$this->db->get_results($Qry3, ARRAY_A);
			
			foreach($Rows3 as $Row3) {
				$label	=	$Row3['label'];
				$value	=	$Row3['value'];

				$BlockData[$label]	=	$value;
			}
			
			$FlyerData['blocks'][$block_title]['features']			=	$BlockData;
			$FlyerData['blocks'][$block_title]['block_position']	=	$Row2['block_position'];
			
			$FlyerAttributes	=	array();
			$Qry4	=	"SELECT checkbox_id FROM flyer_data_checkbox_values WHERE flyer_id = '$flyer_id'";
			$Rows4	=	$this->db->get_results($Qry4, ARRAY_A);
			foreach($Rows4 as $Row4)
				$FlyerAttributes[]	=	substr($Row4['checkbox_id'],1);
			
			$Qry5	=	"SELECT 
							T1.attr_group_id AS attr_group_id, 
							T2.group_name AS group_name 
						FROM flyer_map_form_option_groups AS T1 
						LEFT JOIN flyer_form_attribute_groups AS T2 ON T1.attr_group_id = T2.attr_group_id 
						WHERE T1.block_id = $block_id";
			$Rows5	=	$this->db->get_results($Qry5, ARRAY_A);
			
			foreach($Rows5 as $Row5) {
				$group_name				=	$Row5['group_name'];
				$attr_group_id			=	$Row5['attr_group_id'];
				$CheckedFlyerAttributes	=	array();
			
				$Qry6	=	"SELECT item_id,item_name FROM flyer_form_attribute_item WHERE attr_group_id = '$attr_group_id'";
				$Rows6	=	$this->db->get_results($Qry6, ARRAY_A);
				foreach($Rows6 as $Row6) {
					if(in_array($Row6['item_id'],$FlyerAttributes))
						$CheckedFlyerAttributes[]	=	$Row6['item_name'];
				}
				
				if(count($CheckedFlyerAttributes) > 0)
					$FlyerData['blocks'][$block_title]['attributes'][$group_name]	=	$CheckedFlyerAttributes;
			
			} # Close foreach $Rows5
			
		} # Close foreach $Rows2
		
		
		
		$Links		=	array();			
		$Qry7		=	"SELECT link_title, link_url FROM flyer_links WHERE flyer_id = '$flyer_id'";
		$Rows7		=	$this->db->get_results($Qry7, ARRAY_A);
		$ArrIndx	=	0;
		foreach($Rows7 as $Row7) {
			$Links[$ArrIndx]['link_title']	=	$Row7['link_title'];
			$Links[$ArrIndx]['link_url']	=	$Row7['link_url'];
			$ArrIndx++;
		}
		$FlyerData['links']		=	$Links;
		

		$Gallary	=	array();
		$Qry8		=	"SELECT image_name, caption FROM flyer_data_gallary WHERE flyer_id = '$flyer_id'";
		$Rows8		=	$this->db->get_results($Qry8, ARRAY_A);
		$ArrIndx	=	0;
		foreach($Rows8 as $Row8) {
				$image_name		=	$Row8['image_name'];
				$caption		=	$Row8['caption'];
				if(file_exists(SITE_PATH.'/modules/flyer/images/gallary/'.$image_name)) {
					$Gallary[$ArrIndx]['image_name']	=	$image_name;
					$Gallary[$ArrIndx]['caption']		=	$caption;
					$ArrIndx++;
				}
		}	
		$FlyerData['gallary']		=	$Gallary;
		
		
		$Qry9		=	"SELECT * FROM flyer_data_contact WHERE flyer_id = '$flyer_id'";
		$Rows9		=	$this->db->get_results($Qry9, ARRAY_A);
		$FlyerData['ContactInfo']	=	$Rows9;
		
		return $FlyerData;
	}
	
	
	
	/**
	 * The following method returns the template details associated with a template id
	 * 
	 * @author v@newagesmb.com
	 *
	 */
	function getTemplateDetails($template_id)
	{
		$Qry1	=	"SELECT * FROM flyer_templates WHERE template_id = '$template_id'";
		$Row1	=	$this->db->get_row($Qry1, ARRAY_A);
		return $Row1;
	}
	
	
	
	
	/**
	 *	The following method returns the form title related with a form_id
	 *
	 * 	@author v@newagesmb.com
	 */
	function getFormTitleFromFormId($form_id)
	{
		$Qry	=	"SELECT form_title FROM flyer_form_master WHERE form_id = '$form_id'";
		$Row	=	$this->db->get_row($Qry, ARRAY_A);
		return $Row['form_title'];
	}

	
	/**
	 *
	 *	The following method return N if the flyer is not published else return Y
	 *
	 *	@author v@newagesmb.com
	 */
	function getFlyerPublishStatus($flyer_id)
	{
		$Qry	=	"SELECT publish FROM flyer_data_basic WHERE flyer_id = '$flyer_id'";
		$Row	=	$this->db->get_row($Qry, ARRAY_A);
		return $Row['publish'];
	}
	
	
	/**
	 *	The following method changes the template_id associated with a particular flyer
	 *
	 *
	 *	@author v@newagesmb.com
	 */	
	function updateTemplateIdOfFlyer($flyer_id, $template_id)	
	{
		$Qry	=	"UPDATE flyer_data_basic SET template_id = '$template_id' WHERE flyer_id = '$flyer_id'";
		$this->db->query($Qry);
	}
	
	
	/**
	 *	The following method returns the form_id related with a flyer
	 *
	 *	@author v@newagesmb.com
	 */	
	function getFormIdOfFlyer($flyer_id) 
	{
		$Qry	=	"SELECT form_id FROM flyer_data_basic WHERE flyer_id = '$flyer_id'";
		$Row	=	$this->db->get_row($Qry, ARRAY_A);
		return $Row['form_id'];
	}
	
	
	
	/**
	 *	This method used for creating the flyer code as HTML page and saved in the htmlflyers folder in the hosting directoyr
	 *
	 *
	 *
	 */
	function generateFlyerForPublish($flyer_id)
	{
		$flyer_tpl			=	$this->tpl;
		$FLYER_DATA			=	$this->getFlyerDataForPreview($flyer_id);
		$TemplateDetails	=	$this->getTemplateDetails($FLYER_DATA['template_id']);
		
		$global["mod_url"] 		= SITE_URL."/modules/".$_REQUEST['mod'];
		$global["modbase_url"] 	= SITE_URL."/modules/";
		$global["site_url"] 	= SITE_URL;
		$flyer_tpl->assign("GLOBAL", $global);
		
		$flyer_tpl->assign("CSS_FOLDER", $TemplateDetails['template_dir']);
		$flyer_tpl->assign("DATE", date("H:i:s"));
		$flyer_tpl->assign("FLYER_DATA", $FLYER_DATA);
		$flyer_tpl->assign("FLYER_ID", $flyer_id);
		$flyer_tpl->assign("SITE_NAME", $this->config['site_name']);
		$FLYER_CONTENT	=	$flyer_tpl->fetch(SITE_PATH."/html/templates/".$TemplateDetails['template_dir']."/template.tpl");
		
		$FileContent	=	"<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"
							\"http://www.w3.org/TR/html4/loose.dtd\"><html><head>
							<style type=\"text/css\">
							<!--
							body {
								margin-left: 0px;
								margin-top: 0px;
								margin-right: 0px;
								margin-bottom: 0px;
							}
							-->
							</style>
							</head><body>$FLYER_CONTENT</body></html>";
		$Filename	=	SITE_PATH."/htmlflyers/".$flyer_id.'.html';	
		
		if(file_exists($Filename))
			@unlink($Filename);

		$fp			=	fopen($Filename,'w');
		fwrite($fp, $FileContent);
		fclose($fp);
		# start creating the PDF format flayer
		  require('html_to_pdf.inc.php');
  		  $htmltopdf = new HTML_TO_PDF();
		  $ConvertFilename	=	SITE_URL."/htmlflyers/".$flyer_id.'.html';	
		  $PDF_File	=	SITE_PATH."/pdfflyers/".$flyer_id.'.pdf';	
		   		  if(file_exists($PDF_File))
			@unlink($PDF_File);
			
		 $htmltopdf->saveFile($PDF_File);
		 $result = $htmltopdf->convertURL($ConvertFilename);
		// if($result==false)
       // echo $htmltopdf->error();
		# end creating the PDF format flayer
	}
	 
	
	/**
	 *	
	 *	The following method checks whether a flyer exists for a particular flyer_id
	 *
	 *	@return TRUE  if a flyer exists with the corresponding flyer_id
	 *	@return FALSE if a flyer exists not with the corresponding flyer_id
	 */
	function checkFlyerExists($flyer_id)
	{
		$Qry1		=	"SELECT COUNT(*) AS TotCount FROM flyer_data_basic WHERE flyer_id = '$flyer_id' AND publish = 'Y' AND active = 'Y'";
		$Row		=	$this->db->get_row($Qry1, ARRAY_A);
		$TotCount	=	$Row['TotCount'];
		
		if($TotCount == 0)
			return FALSE;
		
		$FileName	=	SITE_PATH."/htmlflyers/".$flyer_id.'.html';	
		if(file_exists($FileName))
			return TRUE;
		else
			return FALSE;	
			
	}
	
	function GetFormRssFieldsCount($member_id = '')
	{
		
		if(empty($member_id))
			$user_id	=	$_SESSION['memberid']; 
		else
			$user_id	=	$member_id; 
			
		
		$option_array			=	array();
		$gallery_array			=	array();
		
		$qry		=	"select fb.*,fm.form_title from flyer_data_basic fb,flyer_form_master fm  where fb.active='Y'  AND fb.form_id=fm.form_id AND fb.publish = 'Y'";
		$qry		.=	" AND fb.user_id='$user_id' ";
		$rs		=	$this->db->get_results($qry,ARRAY_A);
		$count	=	count($rs);
		return $count;
	}
	
	

	function GetFormRssFields($member_id = '',$LLimit='0',$ULimit='20')
	{
		
		if(empty($member_id))
			$user_id	=	$_SESSION['memberid']; 
		else
			$user_id	=	$member_id; 
			
		
		$option_array			=	array();
		$gallery_array			=	array();
		
		$qry		=	"select fb.*,fm.form_title from flyer_data_basic fb,flyer_form_master fm  where fb.active='Y'  AND fb.form_id=fm.form_id AND fb.publish = 'Y'";
		$qry		.=	" AND fb.user_id='$user_id' LIMIT $LLimit,$ULimit ";
		$rs		=	$this->db->get_results($qry,ARRAY_A);
		$count	=	count($rs);
		for($i=0;$i<$count;$i++)
		{		
				$flyer_id		=	"";  
				$image			=	""; 
				$title			=	""; 
				$form_name		=	""; 
				$form_id 		=	""; 
				$feature_option_array	=	array();
		
				$flyer_id		=	$rs[$i]['flyer_id'];  
				$image			=	$rs[$i]['image']; 
				$title			=	$rs[$i]['title']; 
				$form_name		=	$rs[$i]['form_title']; 
				$form_id 		=	$rs[$i]['form_id']; 
				$flyer_descr	=	$rs[$i]['description'];  
								
				// selecting the features for the block
				$qry2	=	"select * from flyer_rss_fields where from_id='$form_id' ";
				$rs2	=	$this->db->get_results($qry2,ARRAY_A);
				$count2	=	count($rs2); 
				for($j=0;$j<$count2;$j++)
				{		
					$field_id		=	"";
					$field_value	=	"";
					$option_array	=	array();
					$field_id	=	$rs2[$j]['field_id']; 
					// getting the field name
					$qry5	=	"select label from flyer_form_features where feature_id='$field_id'";
					$rs5 = $this->db->get_row($qry5, ARRAY_A);
					$field_name	=	$rs5['label'];
					// getting field value
					$qry3	=	"select * from flyer_data_field_values where field_id='$field_id' and flyer_id='$flyer_id' ";
					$rs3	=	$this->db->get_row($qry3,ARRAY_A);
					$field_value	=	$rs3['value'];	
					if($field_value != "")
					{ 	
						$option_array=array($field_name=>$field_value); 
						$feature_option_array=array_merge($feature_option_array,$option_array);
					}
				
				}
				$subblock_temp	=	array();
				$subblock_temp	=	 array($flyer_id, $image,$title, $form_name,$feature_option_array,$flyer_descr);
				$gallery_array[$i]	=	$subblock_temp;
		}
		return $gallery_array;
	}
	
	
	/**
	 * @description	The following method is used for fetching the gallary image related with a flyer
	 *
	 *
	 * @return An array of images with caption and image name
	 */
	function getGallaryImagesOfFlyer($flyer_id)
	{
		$Gallary	=	array();
		$Qry1		=	"SELECT image_name, caption FROM flyer_data_gallary WHERE flyer_id = '$flyer_id'";
		$Rows1		=	$this->db->get_results($Qry1, ARRAY_A);
		$ArrIndx	=	0;
		foreach($Rows1 as $Row1) {
				$image_name		=	$Row1['image_name'];
				$caption		=	$Row1['caption'];
				if(file_exists(SITE_PATH.'/modules/flyer/images/gallary/'.$image_name)) {
					$Gallary[$ArrIndx]['image_name']	=	$image_name;
					$Gallary[$ArrIndx]['caption']		=	$caption;
					$ArrIndx++;
				}
		}	
	
		return $Gallary;
	}
	
	

}

?>
