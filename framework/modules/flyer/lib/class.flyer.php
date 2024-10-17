 <?
 include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
 class Flyer extends FrameWork {

 	var	$FreeRegnPackages;

 	function Flyer()
 	{
 		$this->FrameWork();
 		$this->FreeRegnPackages	=	array(1);
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
 		$currentDate1  	= 	mktime(0, 0, 0, date("m")  , date("d")+30, date("Y"));
 		$expair_date	=	date("Y-m-d",$currentDate1);
 		$flyerArray = array("modified_date"=>$cur_date,"expire_date"=>$expair_date,"publish"=>"N","active"=>"N");
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

 	function getFlyerViewCount($flyer_id)
 	{
 		$sql		=	"select * from flyer_activity where flyer_id='$flyer_id'";
 		$rs 		= 	$this->db->get_row($sql, ARRAY_A);
 		if(count($rs)>0)		{
 			$hit	=	$rs['hit'];
 		}
 		else {
 			$hit	=	"0";
 		}
 		return $hit;
 	}


 	function getFlyerClone($flyer_id)
 	{
 		// updating the flyer_data_basic table
 		$currentDate1  	= 	mktime(0, 0, 0, date("m")  , date("d")+30, date("Y"));
 		$expair_date	=	date("Y-m-d",$currentDate1);
 		$cur_date		=	date("Y-m-d");
 		$qry		=	"select * from flyer_data_basic where flyer_id='$flyer_id'";
 		$rs 		= 	$this->db->get_row($qry, ARRAY_A);
 		$flyerArray = array("title"=>addslashes($rs['title']),"description"=>addslashes($rs['description']),
 		"user_id"=>$rs['user_id'],"form_id"=>$rs['form_id'],"image"=>$rs['image'],
 		"template_id"=>$rs['template_id'],"modified_date"=>$cur_date,
 		"expire_date"=>$expair_date,"active"=>"Y");
 		$new_flyer_id	=	$this->db->insert("flyer_data_basic", $flyerArray);

 		//updating flyer_data_contact
 		$qry2		=	"select * from flyer_data_contact where flyer_id='$flyer_id'";
 		$rs2 		= 	$this->db->get_row($qry2, ARRAY_A);
 		$contactArray = array("flyer_id"=>$new_flyer_id,"contact_name"=>$rs2['contact_name'],
 		"contact_phone"=>$rs2['contact_phone'],"contact_email"=>$rs2['contact_email'],"show_reply"=>$rs2['show_reply'],
 		"show_email"=>$rs2['show_email'],"location_street_address"=>$rs2['location_street_address'],
 		"location_city"=>$rs2['location_city'],"location_state"=>$rs2['location_state'],"show_address"=>$rs2['show_address'],
 		"location_zip"=>$rs2['location_zip'],"show_map"=>$rs2['show_map'],"location_neighborhood"=>$rs2['location_neighborhood'] );
 		$this->db->insert("flyer_data_contact", $contactArray);

 		//updating flyer_data_field_values
 		$qry3		=	"select * from flyer_data_field_values where flyer_id='$flyer_id'";
 		$rs3 		= 	$this->db->get_results($qry3, ARRAY_A);
 		for($i=0;$i<count($rs3);$i++)
 		{
 			$field_id	=	$rs3[$i]['field_id'];
 			$value		=	$rs3[$i]['value'];
 			$fieldArray=	array("field_id"=>$field_id,"value"=>$value,"flyer_id"=>$new_flyer_id);
 			$this->db->insert("flyer_data_field_values", $fieldArray);
 		}

 		//updating flyer_data_gallary
 		$qry4		=	"select * from flyer_data_gallary where flyer_id='$flyer_id'";
 		$rs4 		= 	$this->db->get_results($qry4, ARRAY_A);
 		for($j=0;$j<count($rs4);$j++)
 		{
 			$image_name	=	$rs4[$j]['image_name'];
 			$caption	=	$rs4[$j]['caption'];
 			$galleryArray=	array("image_name"=>$image_name,"caption"=>$caption,"flyer_id"=>$new_flyer_id);
 			$this->db->insert("flyer_data_gallary", $galleryArray);
 		}

 		//updating flyer_data_checkbox_values
 		$qry5		=	"select * from flyer_data_checkbox_values where flyer_id='$flyer_id'";
 		$rs5 		= 	$this->db->get_results($qry5, ARRAY_A);
 		for($k=0;$k<count($rs5);$k++)
 		{
 			$checkbox_id	=	$rs5[$k]['checkbox_id'];
 			$value			=	$rs5[$k]['value'];
 			$cbArray		=	array("checkbox_id"=>$checkbox_id,"value"=>$value,"flyer_id"=>$new_flyer_id);
 			$this->db->insert("flyer_data_checkbox_values", $cbArray);
 		}
 		return true;

 	}

 	function listAllFlyer($keysearch='N',$flyer_search='',$parent_id=0,$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy="1")
 	{
 		$objProduct		=	new Product();
 		$categories	=	"'0'";
 		if($parent_id != "0" || $parent_id != "" )
 		$categories	=	$objProduct->getChildCategories($parent_id,$categories);
 		$categories	= $categories.",'".$parent_id."'";

 		$qry		=	"select fm.*,mc.category_name from flyer_master fm,master_category mc where fm.active='Y' AND fm.cat_id=mc.category_id";
 		if($parent_id!=0)
 		{$qry	.=	" and fm.cat_id IN($categories) ";}

 		if($keysearch=='Y' && $flyer_search)
 		$qry	.=	" and name LIKE '%$flyer_search%' ";

 		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
 		return $rs;
 	}

 	function flyerSetPriority($album_id=0,$flag="SET")
 	{
 		if ($album_id>0) {
 			$SQLCHECK = "SELECT * FROM flyer_data_search WHERE fds_album_id =".$album_id;
 			$numrows	=	$this->db->query($SQLCHECK);

 			if ($numrows<1 && $flag == "SET") {
 				$array 			= 	array("fds_album_id"=>$album_id);
 				$this->db->insert("flyer_data_search", $array);
 			}elseif($numrows>0 && $flag == "UNSET") {
 				$this->db->query("DELETE FROM flyer_data_search  WHERE fds_album_id='$album_id'");
 			}

 		}

 		return true;
 	}



 	function flyerDelete($flyer_id)
 	{
 		$qry	=	"update flyer_data_basic set active='N',publish='N' where flyer_id='$flyer_id'";
 		$this->db->query($qry);
 		return true;
 	}

 	function flyerUnpublished($flyer_id)
 	{
 		$qry	=	"update flyer_data_basic set publish='N' where flyer_id='$flyer_id'";
 		$this->db->query($qry);
 		return true;
 	}
 	function flyerPublished($flyer_id)
 	{
 		$qry	=	"update flyer_data_basic set publish='Y' where flyer_id='$flyer_id'";
 		$this->db->query($qry);
 		return true;
 	}

 	/*
 	Created:Vinoy
 	Date:10-April-2008
 	for property deleting ,publishing and unpublish
 	*/
 	//($pageNo, $limit = 10, $params='', $output=OBJECT,$uid)

 	function propertyDelete($album_id)
 	{
 		$qry	=	"update flyer_data_basic set active='N',publish='N' where album_id ='$album_id'";
 		$this->db->query($qry);
 		return true;
 	}

 	function propertyUnpublished($album_id)
 	{
 		$qry	=	"update flyer_data_basic set publish='N' where album_id ='$album_id'";
 		$this->db->query($qry);
 		return true;
 	}
 	function propertyPublished($album_id)
 	{
 		$qry	=	"update flyer_data_basic set publish='Y' where album_id ='$album_id'";
 		$this->db->query($qry);
 		return true;
 	}
 	//====vinoy end======

 	function activeFlyerCount($member_id)
 	{
 		$qry	=	"select count(*) as count from  flyer_data_basic where user_id ='$member_id' AND publish='Y' AND form_id != '0' AND active='Y' ";
 		$rs = $this->db->get_row($qry, ARRAY_A);
 		return $rs['count'];
 	}


 	function allFlyerCount($member_id)
 	{
 		$qry	=	"select count(*) as count from  flyer_data_basic where user_id ='$member_id' AND form_id != '0' AND active='Y' ";
 		$rs = $this->db->get_row($qry, ARRAY_A);
 		return $rs['count'];
 	}

 	function memberUploadPhoto($req,$file,$tmpname)
 	{
 		extract($req);
 		if ($file){
 			$user_id		=	$_SESSION["memberid"];
 			list($thumb_width,$thumb_height)	=	split(',',$this->config['member_photo']);
 			$dir			=	SITE_PATH."/modules/member/images/photos/";
 			$resource_file	=	$dir.$file;
 			$path_parts 	= 	pathinfo($file);
 			$save_filename	=	$user_id.".".$path_parts['extension'];
 			_upload($dir,$save_filename,$tmpname,1,$thumb_width,$thumb_height);
 			$photoArray=array("photo"=>$save_filename);
 			$this->db->update("member_master", $photoArray, "id='$user_id'");
 		}
 		return true;
 	}

 	function GetmemberImages()
 	{
 		$user_id		=	$_SESSION["memberid"];
 		$rs = $this->db->get_row("SELECT photo,logo,email,username FROM member_master WHERE id=$user_id", ARRAY_A);
 		return $rs;
 	}

 	function memberUploadLogo($req,$file,$tmpname)
 	{
 		extract($req);
 		if ($file){
 			$user_id		=	$_SESSION["memberid"];
 			list($thumb_width,$thumb_height)	=	split(',',$this->config['photo_logo_image']);
 			$dir			=	SITE_PATH."/modules/member/images/logos/";
 			$resource_file	=	$dir.$file;
 			$path_parts 	= 	pathinfo($file);
 			$save_filename	=	$user_id.".".$path_parts['extension'];
 			_upload($dir,$save_filename,$tmpname,1,$thumb_width,$thumb_height);
 			$photoArray=array("logo"=>$save_filename);
 			$this->db->update("member_master", $photoArray, "id='$user_id'");
 		}
 		return true;
 	}

 	function FlyeruploadPhoto($req,$file,$tmpname,$type,$no,$captions)
 	{
 		extract($req);
 		if ($file){
 			$cur_time		=	date("Ymd-His");
 			if($type=="M")
 			{
 				list($thumb_width1,$thumb_height1)	=	split(',',$this->config['photo_main_image']);
 				list($thumb_width2,$thumb_height2)	=	split(',',$this->config['gallery_widget_image']);
 				$dir			=	SITE_PATH."/modules/flyer/images/";
 				$dir2			=	SITE_PATH."/modules/flyer/images/widget/";
 				$resource_file	=	$dir.$file;
 				$path_parts 	= 	pathinfo($file);
 				$save_filename	=	$flyer_id."_".$cur_time.".".$path_parts['extension'];
 				_upload($dir,$save_filename,$tmpname,1,$thumb_width1,$thumb_height1);
 				_upload($dir2,$save_filename,$tmpname,1,$thumb_width2,$thumb_height2);
 				$photoArray=array("image"=>$save_filename);
 				$this->db->update("flyer_data_basic", $photoArray, "flyer_id='$flyer_id'");
 			}
 			if($type=="G")
 			{
 				list($thumb_width1,$thumb_height1)	=	split(',',$this->config['photo_gallery_image']);
 				list($thumb_width2,$thumb_height2)	=	split(',',$this->config['gallery_second_image']);
 				$dir			=	SITE_PATH."/modules/flyer/images/gallary/";
 				$dir2			=	SITE_PATH."/modules/flyer/images/medium_gallary/";
 				$resource_file	=	$dir.$file;
 				$path_parts 	= 	pathinfo($file);
 				$save_filename	=	$flyer_id."_".$cur_time."_".$no.".".$path_parts['extension'];
 				_upload($dir,$save_filename,$tmpname,1,$thumb_width1,$thumb_height1);
 				_upload($dir2,$save_filename,$tmpname,1,$thumb_width2,$thumb_height2);
 				$photoArray=array("image_name"=>$save_filename,"caption"=>$captions,"flyer_id"=>$flyer_id);
 				$this->db->insert("flyer_data_gallary", $photoArray);
 			}
 		}
 		return true;
 	}
 	function GetmainPhoto($flyer_id)
 	{
 		$rs = $this->db->get_row("select image from flyer_data_basic where flyer_id='$flyer_id'",ARRAY_A);
 		return $rs['image'];
 	}

 	function GetLinks($flyer_id)
 	{
 		$rs = $this->db->get_results("select * from flyer_links where flyer_id='$flyer_id'",ARRAY_A);
 		return $rs;
 	}

 	function GetgalleryPhoto($flyer_id)
 	{
 		$rs = $this->db->get_results("select * from flyer_data_gallary where flyer_id='$flyer_id'",ARRAY_A);
 		return $rs;
 	}

 	function flyerCustomeFieldDelete($feature_id)
 	{
 		$this->db->query("DELETE FROM flyer_form_features  WHERE feature_id='$feature_id'");
 		$this->db->query("DELETE FROM flyer_map_form_feature  WHERE  feature_id ='$feature_id'");
 		$this->db->query("DELETE FROM flyer_data_field_values  WHERE  field_id='$feature_id'");
 		return true;
 	}

 	function FlyerUnpublish($flyer_id)
 	{
 		$this->db->query("UPDATE flyer_data_basic set publish='N' WHERE flyer_id='$flyer_id'");
 		return true;
 	}

 	function FlyerPublish($flyer_id)
 	{
 		$this->generateFlyerForPublish($flyer_id);
 		$expire_uti = mktime(0, 0, 0, date("m"), date("d")+ $this->config['expire_day'],   date("Y"));
 		$expire_date = date("Y-m-d",$expire_uti);
 		$this->db->query("UPDATE flyer_data_basic set publish='Y',expire_date = '$expire_date' WHERE flyer_id='$flyer_id'");
 		return true;
 	}


 	function getallForms()
 	{
 		$qry	=	"select * from master_category where active='y' ORDER BY display_order";
 		$rs	=	$this->db->get_results($qry,ARRAY_A);
 		$count	=	count($rs);
 		if($count>0)
 		{
 			/*for($j=0;$j<$count;$j++)
 			{
 			$cat_id				=	$rs[$j]['category_id'];
 			$cat_name			=	$rs[$j]['category_name'];
 			$rs['form_id'][]	= 	"";
 			$rs['form_name'][] 	= 	$cat_name;
 			$qry2	=	"select * from flyer_form_master where category_name='$cat_id' and active='Y'";
 			$rs2	=	$this->db->get_results($qry2,ARRAY_A);
 			$count2	=	count($rs2);
 			if($count2>0)
 			{
 			for($k=0;$k<$count2;$k++)
 			{
 			$rs['form_id'][]		= 	$rs2[$k]['form_id'];
 			$rs['form_name'][] 		= 	"&nbsp;&nbsp;&nbsp;".$rs2[$k]['form_title'];
 			}
 			}
 			}*/
 			$C_array	=	array();
 			for($j=0;$j<$count;$j++)
 			{
 				$cat_id				=	$rs[$j]['category_id'];
 				$cat_name			=	$rs[$j]['category_name'];
 				$c2					=	"xxx".$rs[$j]['category_id'];
 				$C_array[$c2] 	= 	$cat_name;
 				$rs['form_id'][]	= 	"";
 				$rs['form_name'][] 	= 	$cat_name;
 				$qry2	=	"select * from flyer_form_master where category_name='$cat_id' and active='Y'";
 				$rs2	=	$this->db->get_results($qry2,ARRAY_A);
 				$count2	=	count($rs2);
 				if($count2>0)
 				{
 					for($k=0;$k<$count2;$k++)
 					{
 						$rs['form_id'][]		= 	$rs2[$k]['form_id'];
 						$rs['form_name'][] 		= 	"&nbsp;&nbsp;&nbsp;".$rs2[$k]['form_title'];
 						$v1						=	$rs2[$k]['form_id'];
 						$v2						=	"&nbsp;&nbsp;&nbsp;".$rs2[$k]['form_title'];
 						$C_array[$v1] = $v2;
 					}
 				}
 			}

 		}
 		//return $rs;
 		return $C_array;
 	}

 	function GetFlyerForm($form_id,$flyer_id)
 	{
 		$array					=	array();
 		$option_array			=	array();
 		$feature_array			=	array();
 		$subblock_array			=	array();
 		$newArray				=	array();
 		//$form_id				=	"38";
 		$qry	=	"select * from flyer_form_blocks where form_id='$form_id' and active='Y' ORDER BY display_order";
 		$rs		=	$this->db->get_results($qry,ARRAY_A);
 		$count	=	count($rs);
 		for($i=0;$i<$count;$i++)
 		{
 			$block_name		=	$rs[$i]['block_title'];
 			$block_id		=	$rs[$i]['block_id'];
 			$subblock_array	=	array();
 			// selecting the features for the block
 			//$qry2	=	"select * from flyer_map_form_feature where block_id='$block_id' ";
 			$qry2	=	"select * from flyer_map_form_feature fm,flyer_form_features ff where fm.block_id='$block_id' and fm.feature_id=ff.feature_id ORDER BY ff.position";
 			$rs2	=	$this->db->get_results($qry2,ARRAY_A);
 			$count2	=	count($rs2);
 			for($j=0;$j<$count2;$j++)
 			{
 				$feature_id	=	$rs2[$j]['feature_id'];
 				$mand_required = $rs2[$j]['mand_required'];
 				$qry3	=	"select * from flyer_form_features where feature_id='$feature_id' and active='Y' and (flyer_id='0' or flyer_id='$flyer_id')";
 				$rs3	=	$this->db->get_results($qry3,ARRAY_A);
 				$count3	=	count($rs3);
 				if($count3>0)
 				{
 					$custome_flag	=	"N";
 					$field_type	=	$rs3[0]['type'];
 					$label		=	$rs3[0]['label'];
 					$length		=	$rs3[0]['length'];
 					$flyer_fld	=	$rs3[0]['flyer_id'];

 					$options_sorting		=	$rs3[0]['options_sorting'];
 					$useroptions_allowed	=	$rs3[0]['useroptions_allowed'];

 					$OptionsFilter	=	'';
 					if($options_sorting == 'Y')
 					$OptionsFilter	=	' ORDER BY name ASC';


 					if($flyer_fld!="0")
 					$custome_flag	=	"C";
 					$flyer_field_value=	$this->GetFieldValue($feature_id,$flyer_id);
 					if($field_type=="Dropdown")
 					{
 						$feature_option_array	=	array();
 						$qry4	=	"select * from flyer_form_features_values where feature_id='$feature_id' and active='Y' and (flyer_id='0' or flyer_id='$flyer_id')  $OptionsFilter ";
 						$rs4	=	$this->db->get_results($qry4,ARRAY_A);
 						$count4	=	count($rs4);
 						if($count4>0)
 						{
 							for($k=0;$k<$count4;$k++)
 							{
 								$feature_option_id	=	"D".$rs4[$k]['value_id'];
 								$feature_option_val	=	$rs4[$k]['name'];
 								$option_array=array($feature_option_id=>$feature_option_val);
 								$feature_option_array=array_merge($feature_option_array,$option_array);
 								unset($option_array);
 							}
 						}
 					}
 					$subblock_temp	=	array();
 					$subblock_temp	=	array($label => array($feature_id,'F', $field_type,$feature_option_array, $flyer_field_value, $length,$custome_flag,$mand_required,$useroptions_allowed));
 					$subblock_array	=	array_merge($subblock_array,$subblock_temp);
 					unset($feature_option_array);
 				}
 				// End selecting the features for the block
 			}
 			// selecting the check box attributes for the block
 			$attr_subblock_array	=	array();
 			$qry5	=	"select * from flyer_map_form_option_groups where block_id='$block_id' ";
 			$rs5	=	$this->db->get_results($qry5,ARRAY_A);//print($qry5);
 			$count5	=	count($rs5);
 			for($p=0;$p<$count5;$p++)
 			{
 				$group_id	=	$rs5[$p]['attr_group_id'];//print($group_id);
 				$qry6	=	"select * from flyer_form_attribute_groups where attr_group_id='$group_id' and active='Y'";
 				$rs6	=	$this->db->get_results($qry6,ARRAY_A);
 				$count6	=	count($rs6);
 				if($count6>0)
 				{
 					$group_name 	=	$rs6[0]['group_name'];
 					$group_id 		=	$rs6[0]['attr_group_id'];
 					$attr_checkbox_array	=	array();
 					$qry7	=	"select * from flyer_form_attribute_item where attr_group_id='$group_id' and active='Y' and (flyer_id='0' or flyer_id='$flyer_id') ";
 					$rs7	=	$this->db->get_results($qry7,ARRAY_A);
 					$count7	=	count($rs7);
 					if($count7>0)
 					{
 						for($q=0;$q<$count7;$q++)
 						{
 							$check_option_id	=	"C".$rs7[$q]['item_id'];
 							$check_option_val	=	$rs7[$q]['item_name'];
 							$checkbox_array		=	array($check_option_id=>$check_option_val);
 							$attr_checkbox_array=	array_merge($attr_checkbox_array,$checkbox_array);
 							unset($checkbox_array);
 						}
 					}
 					$attr_subblock_temp		=	array();
 					$attr_subblock_temp		=	array($group_name => array($group_id,'A', 'checkbox',$attr_checkbox_array, '', '0','custome_flag'));
 					$attr_subblock_array	=	array_merge($attr_subblock_array,$attr_subblock_temp);
 					unset($attr_checkbox_array);
 					// end selecting the check box attributes for the block
 				}
 			}
 			// setting the array
 			$newArray		=	array_merge($subblock_array,$attr_subblock_array);
 			$block_array	=	array($block_name=>$newArray);
 			$feature_array	=	array_merge($feature_array,$block_array);
 			unset($newArray);
 			unset($attr_subblock_array);
 		}

 		return $feature_array;
 	}

 	function addUserFields($req,$flyer_id,$form_id)
 	{
 		extract($req);
 		if($user_field_label!="")
 		{
 			$array 			= 	array("label"=>$user_field_label,"type"=>$user_field_type,"length"=>"20","required"=>"N","active"=>"Y","flyer_id"=>$flyer_id);
 			$this->db->insert("flyer_form_features", $array);
 			$feature_id 	= 	$this->db->insert_id;
 			// inserting the new field in the maping table

 			$rs = $this->db->get_row("select block_id from flyer_form_blocks where block_title='$hid_block_name' and form_id='$form_id' and active='Y'",ARRAY_A);
 			$block_id		=	$rs['block_id'];

 			$mapArray 			= 	array("block_id"=>$block_id,"feature_id"=>$feature_id);
 			$this->db->insert("flyer_map_form_feature", $mapArray);
 		}
 		return true;
 	}

 	function addUserCheckbox($req,$flyer_id,$form_id)
 	{
 		extract($req);
 		if($user_checkbox_label!="")
 		{
 			$rs = $this->db->get_row("select attr_group_id from flyer_form_attribute_groups where group_name='$hid_checkbox_name' and active='Y'",ARRAY_A);
 			$group_id		=	$rs['attr_group_id'];

 			$array 			= 	array("item_name"=>$user_checkbox_label,"attr_group_id"=>$group_id,"active"=>"Y","flyer_id"=>$flyer_id);
 			$this->db->insert("flyer_form_attribute_item", $array);
 			$check_id 	= 	$this->db->insert_id;
 			// inserting the new field in the data storing table
 			$mapArray 			= 	array("flyer_id"=>$flyer_id,"checkbox_id"=>"C".$check_id,"value"=>"1");
 			$this->db->insert("flyer_data_checkbox_values", $mapArray);
 		}
 		return true;
 	}

 	function addLinks($req,$flyer_id,$form_id)
 	{
 		extract($req);
 		if($user_link_url!="" && $user_link_title!="")
 		{
 			// appending http:// to urls
 			$pos1 = strpos($user_link_url, "http://");
 			if ($pos1 === false) {
 				$user_link_url = str_replace("www.", "http://www.", $user_link_url);
 			}
 			// end
 			$array 			= 	array("flyer_id"=>$flyer_id,"link_title"=>$user_link_title,"link_url"=>$user_link_url);
 			$this->db->insert("flyer_links", $array);
 		}
 		return true;
 	}

 	function addUserDropdown($req,$flyer_id,$form_id)
 	{
 		extract($req);
 		if($user_dropdown!="")
 		{
 			$hid_dropdown	=	str_replace("d","",$hid_dropdown);
 			$array 			= 	array("feature_id"=>$hid_dropdown,"name"=>$user_dropdown,"active"=>"Y","flyer_id"=>$flyer_id);
 			$this->db->insert("flyer_form_features_values", $array);
 			$check_id 	= 	$this->db->insert_id;

 			// inserting the new field in the data storing table
 			$this->db->query("DELETE FROM flyer_data_field_values  WHERE field_id ='$hid_dropdown' and flyer_id='$flyer_id'");
 			$mapArray 			= 	array("flyer_id"=>$flyer_id,"field_id"=>$hid_dropdown,"value"=>$user_dropdown);
 			$this->db->insert("flyer_data_field_values", $mapArray);
 		}
 		return true;
 	}

 	function FlyerAddEdit(&$req)
 	{

 		extract($req);

 		if(isset($_REQUEST['btn_publish_x']))
 		{
 			$priview_status	=	"Y";
 		}
 		if(isset($_REQUEST['btn_draft_x']))
 		{ 			$priview_status	=	"N"; 		}

 		unset($req['sId'],$req['fId'],$req['btn_publish_x'],$req['btn_publish_y'],$req['btn_draft_x'],$req['btn_draft_y']);

 		if(!trim($title))
 		{
 			$message="Title required";
 		}
 		else
 		{
 			if($form_template=="0" || $form_template=="")
 			{ $form_template="1"; }
 			$cur_date	=	date('Y-m-d');
 			$flyerArray = array("title"=>$title,"description"=>$description,
 			"user_id"=>$_SESSION['memberid'],"form_id"=>$flyer_form_id,
 			"template_id"=>$form_template,"modified_date"=>$cur_date,
 			"active"=>"Y","mand_req"=>$mand_req);
 			if($priview_status	==	"Y")
 			{
 				$statusArray=array("publish"=>"Y");
 				$flyerArray=array_merge($flyerArray,$statusArray);
 			}
 			if($priview_status	==	"N")
 			{
 				$statusArray=array("publish"=>"N");
 				$flyerArray=array_merge($flyerArray,$statusArray);
 			}
 			if($expire_date!="")
 			{
 				if($expire_date==$cur_date || $expire_date < $cur_date )
 				{
 					$expire_uti = mktime(0, 0, 0, date("m"), date("d")+ $this->config['expire_day'],   date("Y"));
 					$expire_date = date("Y-m-d",$expire_uti);
 				}
 				$expArray=array("expire_date"=>$expire_date);
 				$flyerArray=array_merge($flyerArray,$expArray);
 			}


 			if($flyer_id)
 			{
 				$flyerArray['flyer_id'] 	= 	$flyer_id;
 				$this->db->update("flyer_data_basic", $flyerArray, "flyer_id='$flyer_id'");
 			}
 			else
 			{
 				$this->db->insert("flyer_data_basic", $flyerArray);
 				$flyer_id = $this->db->insert_id;
 			}
 			// inserting the contact details to the flyer_data_contact
 			$this->db->query("DELETE FROM flyer_data_contact  WHERE flyer_id='$flyer_id'");
 			$array	= array("flyer_id"=>$flyer_id);
 			if(trim($contact_name)) {
 				$newarray=array("contact_name"=>$contact_name);
 				$array=array_merge($array,$newarray);

 			}
 			if(trim($contact_phone)) {
 				$newarray=array("contact_phone"=>$contact_phone);
 				$array=array_merge($array,$newarray);

 			}
 			if(trim($contact_email)) {
 				$newarray=array("contact_email"=>$contact_email);
 				$array=array_merge($array,$newarray);

 			}
 			if(trim($location_country)) {
 				$newarray=array("location_country"=>$location_country);
 				$array=array_merge($array,$newarray);

 			}
 			if(trim($show_reply)) {
 				$show_reply	=	"Y";
 				$newarray=array("show_reply"=>$show_reply);
 				$array=array_merge($array,$newarray);
 				unset($req['show_reply']);
 			}
 			else {
 				$show_reply	=	"N";
 				$newarray=array("show_reply"=>$show_reply);
 				$array=array_merge($array,$newarray);
 			}
 			if(trim($show_email)) {
 				$show_email	=	"Y";
 				$newarray=array("show_email"=>$show_email);
 				$array=array_merge($array,$newarray);
 				unset($req['show_email']);
 			}
 			else {
 				$show_email	=	"N";
 				$newarray=array("show_email"=>$show_email);
 				$array=array_merge($array,$newarray);

 			}
 			if(trim($location_street_address)) {
 				$newarray=array("location_street_address"=>$location_street_address);
 				$array=array_merge($array,$newarray);

 			}
 			if(trim($location_city)) {
 				$newarray=array("location_city"=>$location_city);
 				$array=array_merge($array,$newarray);

 			}
 			if(trim($location_state)) {
 				$newarray=array("location_state"=>$location_state);
 				$array=array_merge($array,$newarray);

 			}
 			if(trim($location_zip)) {
 				$newarray=array("location_zip"=>$location_zip);
 				$array=array_merge($array,$newarray);
 			}
 			if(trim($show_address)) {
 				$show_address="Y";
 				$newarray=array("show_address"=>$show_address);
 				$array=array_merge($array,$newarray);
 				unset($req['show_address']);
 			}
 			else {
 				$show_address="N";
 				$newarray=array("show_address"=>$show_address);
 				$array=array_merge($array,$newarray);
 			}
 			if(trim($show_map)) {
 				$show_map="Y";
 				$newarray=array("show_map"=>$show_map);
 				$array=array_merge($array,$newarray);
 				unset($req['show_map']);
 			}
 			else {
 				$show_map="N";
 				$newarray=array("show_map"=>$show_map);
 				$array=array_merge($array,$newarray);
 			}
 			if(trim($location_neighborhood)) {
 				$newarray=array("location_neighborhood"=>$location_neighborhood);
 				$array=array_merge($array,$newarray);

 			}

 			// deleting field values from the array
 			unset($req['contact_name'],$req['contact_email'],$req['location_street_address'],$req['location_city'],
 			$req['location_state'],$req['location_neighborhood'],$req['title'],$req['description'],$req['contact_phone'],
 			$req['expire_date'],$req['flyer_id'],$req['flyer_form_id'],$req['form_template'],$req['keyFeatures'],$req['keyAttributes'],$req['keyPhotographs']);

 			$this->db->insert("flyer_data_contact", $array);

 			// saving the field datas in to the
 			// flyer_data_field_values - for saving the field values
 			// and flyer_data_checkbox_values - for saving the selected check box
 			$this->db->query("DELETE FROM flyer_data_field_values  WHERE flyer_id='$flyer_id'");
 			$this->db->query("DELETE FROM flyer_data_checkbox_values  WHERE flyer_id='$flyer_id'");

 			foreach($req as $key=>$field_value)
 			{
 				$pos =	1;
 				$pos = strpos($key, "d");
 				if ($pos === 0) {
 					$key	=	str_replace("d","",$key);
 					$fieldArray	=	array("flyer_id"=>$flyer_id,"field_id"=>$key,"value"=>$field_value);
 					$this->db->insert("flyer_data_field_values", $fieldArray);
 				}
 				else
 				{
 					if(is_int($key))  {
 						// inserting the data in to the flyer_data_field_values
 						if($field_value!="") {
 							$fieldArray	=	array("flyer_id"=>$flyer_id,"field_id"=>$key,"value"=>$field_value);
 							$this->db->insert("flyer_data_field_values", $fieldArray);
 						}
 					}
 					else {
 						// inserting the data in to the flyer_data_checkbox_values
 						$fieldArray	=	array("flyer_id"=>$flyer_id,"checkbox_id"=>$key,"value"=>$field_value);
 						$this->db->insert("flyer_data_checkbox_values", $fieldArray);
 					}
 				}

 			}
 			$this->db->query("DELETE FROM flyer_data_checkbox_values  WHERE value!='1'");
 			// end inserting the contact details to the flyer_data_contact
 			// deleting the link fields from the flyer_data_checkbox_values table
 			//$this->db->query("DELETE FROM flyer_data_checkbox_values  WHERE checkbox_id LIKE '%L%'");
 		}

 	}

 	// display the flyers of a user
 	function listMyFlyers($flag,$keysearch='N',$flyer_search='',$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy="1",$status_id='')
 	{
 		$user_id	=	$_SESSION['memberid'];
 		$cur_date	=	date('Y-m-d');
 		//$qry		=	"select fb.*,fm.form_title,mm.first_name,mm.last_name,mm.username from flyer_data_basic fb,flyer_form_master fm,member_master mm  where fb.active='Y' AND fb.user_id=mm.id  AND fb.form_id=fm.form_id ";
 		$qry	=	"SELECT T1.*,
						T2.form_title AS form_title, 
						T3.first_name AS first_name, 
						T3.last_name AS last_name, 
						T3.username AS username 
						FROM flyer_data_basic AS T1
						LEFT JOIN flyer_form_master AS T2 ON T2.form_id = T1.form_id
						LEFT JOIN member_master AS T3 ON T3.id = T1.user_id 
						WHERE T1.active='Y' ";

 		if($flag=="U")
 		{
 			$qry	.=	" AND T1.user_id='$user_id' ";

 		}
 		if($status_id != '')
 		{
 			if($status_id=="publish")
 			$qry	.=	" and T1.publish ='Y' and T1.expire_date > '$cur_date'";
 			if($status_id=="draft")
 			$qry	.=	" and T1.publish ='N' and T1.expire_date > '$cur_date'";
 			if($status_id=="expire")
 			{
 				$qry	.=	" and T1.expire_date < '$cur_date' ";
 			}
 		}
 		/*}
 		else
 		{
 		$qry	.=	" and fb.publish ='Y' ";
 		}*/

 		if($keysearch=='Y' && $flyer_search)
 		$qry	.=	" and (T1.title LIKE '%$flyer_search%' OR T3.username LIKE '%$flyer_search%' OR
							 T3.first_name LIKE '%$flyer_search%' OR T3.last_name LIKE '%$flyer_search%' OR T3.email LIKE '%$flyer_search%' )";
 		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);


 		return $rs;
 	}



 	function flyerPhotoDelete($img_id,$type)
 	{
 		if($type=="G")
 		{
 			$rs = $this->db->get_row("SELECT * FROM flyer_data_gallary  WHERE gallery_id ='$img_id'", ARRAY_A);
 			$image_name		=	$rs['image_name'];
 			$Galleryimage		=	SITE_PATH."/modules/flyer/images/gallary/".$image_name;
 			$Galleryimage2		=	SITE_PATH."/modules/flyer/images/medium_gallary/".$image_name;
 			$GalleryimageThumb	=	SITE_PATH."/modules/flyer/images/gallary/thumb/".$image_name;
 			$GalleryimageThumb2	=	SITE_PATH."/modules/flyer/images/medium_gallary/thumb/".$image_name;
 			$this->db->query("DELETE FROM flyer_data_gallary  WHERE gallery_id ='$img_id'");
 		}
 		else
 		{
 			//print("hai");
 			$rs = $this->db->get_row("SELECT image FROM flyer_data_basic  WHERE flyer_id ='$img_id'", ARRAY_A);
 			$image_name	=	$rs['image'];
 			$Galleryimage	=	SITE_PATH."/modules/flyer/images/".$image_name;
 			$GalleryimageThumb	=	SITE_PATH."/modules/flyer/images/thumb/".$image_name;
 			$this->db->query("UPDATE flyer_data_basic  set image='' WHERE flyer_id ='$img_id'");
 		}

 		if($Galleryimage)
 		unlink($Galleryimage);
 		if($GalleryimageThumb)
 		unlink($GalleryimageThumb);
 		if($Galleryimage2)
 		unlink($Galleryimage2);
 		if($GalleryimageThumb2)
 		unlink($GalleryimageThumb2);
 		return true;

 	}

 	function GetFieldValue($field_id,$flyer_id)
 	{
 		$rs = $this->db->get_row("SELECT value FROM flyer_data_field_values  WHERE flyer_id ='$flyer_id' and field_id='$field_id'", ARRAY_A);
 		return $rs['value'];
 	}

 	function cron_listingExpired()
 	{
 		$cur_date	=	date("Y-m-d");
 		//$qry		=	"select * from flyer_data_basic where form_id!=0 and publish='Y' and active='Y' and expire_date ='$cur_date'";
 		$qry		=	"select fd.*,mm.username,mm.first_name,mm.last_name,mm.email
						 from flyer_data_basic fd,member_master mm
						 where fd.form_id!=0 and fd.publish='Y' and fd.active='Y' 
						 and fd.expire_date ='$cur_date' and fd.user_id=mm.id and fd.user_id='21'";

 		$Row		=	$this->db->get_results($qry, ARRAY_A);
 		return $Row;
 	}



 	function GetFlyerData($flyer_id)
 	{
 		$sql	=	"SELECT T1.*, T2.*
					FROM flyer_data_basic AS T1 
					LEFT JOIN flyer_data_contact AS T2 ON T2.flyer_id = T1.flyer_id 
					WHERE T1.flyer_id='$flyer_id' AND T2.flyer_id='$flyer_id'";
 		//$rs = $this->db->get_row("SELECT * FROM flyer_data_basic fb,flyer_data_contact fc WHERE fb.flyer_id='$flyer_id' and fc.flyer_id='$flyer_id'", ARRAY_A);
 		$rs = $this->db->get_row($sql, ARRAY_A);

 		return $rs;
 	}

 	function GetFlyerCheckboxData($flyer_id)
 	{
 		$rs = $this->db->get_results("SELECT checkbox_id FROM flyer_data_checkbox_values  WHERE flyer_id ='$flyer_id'", ARRAY_A);
 		return $rs;
 	}



 	/**
	 *
	 * The following method returns the flyer data for both 
	 *  and flyet HTML file creation
	 *
	 * @author vimson@newagesmb.com
	 */
 	function getFlyerDataForPreview($flyer_id)
 	{
 		$FlyerData	=	array();

 		$Qry1		=	"SELECT * FROM flyer_data_basic WHERE flyer_id = '$flyer_id'";
 		$Row1		=	$this->db->get_row($Qry1, ARRAY_A);
 		$user_id	=	$Row1['user_id'];

 		$Qry11		=	"SELECT photo, logo FROM member_master WHERE id = '$user_id'";
 		$Row11		=	$this->db->get_row($Qry11, ARRAY_A);

 		$Qry111		=	"SELECT form_title FROM flyer_form_master WHERE form_id = '{$Row1['form_id']}'";
 		$Row111		=	$this->db->get_row($Qry111, ARRAY_A);

 		$objUser			=	new User();
 		$AccountDetails		=	$objUser->getUserdetails($user_id);

 		$subDomain_name		=	trim($AccountDetails['account_url']);
 		if($subDomain_name == '')
 		$subDomain_name 	= 	$AccountDetails['username'];

 		$flyer_url		=	'http://'.$subDomain_name.'.'.DOMAIN_URL.'/'.$flyer_id.'/';
 		$listing_url	=	'http://'.$subDomain_name.'.'.DOMAIN_URL.'/';

 		if(trim($Row1['image']) != '')
 		$image_url		=	SITE_URL.'/modules/flyer/images/thumb/'.$Row1['image'];
 		else
 		$image_url		=	'';

 		if($Row11['photo'] != '') {
 			if(file_exists(SITE_PATH.'/modules/member/images/photos/thumb/'.$Row11['photo']))
 			$FlyerData['photo']			=	$Row11['photo'];
 		}

 		if($Row11['logo'] != '') {
 			if(file_exists(SITE_PATH.'/modules/member/images/logos/thumb/'.$Row11['logo']))
 			$FlyerData['logo']			=	$Row11['logo'];
 		}

 		$FlyerData['image_url']		=	$image_url;
 		$FlyerData['listing_url']	=	$listing_url;
 		$FlyerData['flyer_url']		=	$flyer_url;
 		$FlyerData['category']		=	$Row111['form_title'];
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

 		$Qry11	=	"SELECT show_reply, show_email,show_map, show_address FROM flyer_data_contact WHERE flyer_id = '$flyer_id' ";
 		$Row11	=	$this->db->get_row($Qry11, ARRAY_A);
 		$FlyerData['show_reply']	=	$Row11['show_reply'];
 		$FlyerData['show_email']	=	$Row11['show_email'];
 		$FlyerData['show_map']		=	$Row11['show_map'];
 		$FlyerData['show_address']	=	$Row11['show_address'];

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
							T3.flyer_id = '$flyer_id' AND T1.block_id = '$block_id' ORDER BY T2.position ASC ";
 			$Rows3		=	$this->db->get_results($Qry3, ARRAY_A);

 			foreach($Rows3 as $Row3) {
 				$label	=	$Row3['label'];
 				$value	=	$Row3['value'];

 				$BlockData[$label]	=	$value;
 			}

 			$FlyerData['blocks'][$block_title]['features']			=	$BlockData;
 			$FlyerData['blocks'][$block_title]['block_position']	=	$Row2['block_position'];

 			$FlyerAttributes	=	array();
 			$Qry4	=	"SELECT checkbox_id FROM flyer_data_checkbox_values WHERE flyer_id = '$flyer_id' AND checkbox_id LIKE 'C%'";
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

 		// For Filtering the checked links
 		$Qry10	=	"SELECT checkbox_id from flyer_data_checkbox_values where flyer_id='$flyer_id' and checkbox_id LIKE 'L%'";
 		$Rows10	=	$this->db->get_results($Qry10, ARRAY_A);
 		$chk_ids="'0'";
 		for($lk=0;$lk<count($Rows10);$lk++)
 		{
 			$Lchk_id	=	str_replace("L","",$Rows10[$lk]['checkbox_id']);
 			$chk_ids	=	$chk_ids. " , '".$Lchk_id."'";
 		}

 		// End For Filtering the checked links

 		$Links		=	array();
 		$Qry7		=	"SELECT link_title, link_url FROM flyer_links WHERE flyer_id = '$flyer_id' AND  link_id IN ($chk_ids)";
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
 		$Rows9		=	$this->db->get_row($Qry9, ARRAY_A);
 		$FlyerData['ContactInfo']	=	$Rows9;

 		return $FlyerData;
 	}



 	/**
	 *
	 * The following method returns the flyer data for creation of feeds
	 *
	 * @author vimson@newagesmb.com
	 */
 	function getFlyerDataForFeedCreation($flyer_id, $feed_id = '')
 	{
 		$FlyerData	=	array();

 		$Qry1		=	"SELECT * FROM flyer_data_basic WHERE flyer_id = '$flyer_id'";
 		$Row1		=	$this->db->get_row($Qry1, ARRAY_A);
 		$user_id	=	$Row1['user_id'];

 		$Qry11		=	"SELECT photo, logo FROM member_master WHERE id = '$user_id'";
 		$Row11		=	$this->db->get_row($Qry11, ARRAY_A);

 		$Qry111		=	"SELECT form_title FROM flyer_form_master WHERE form_id = '{$Row1['form_id']}'";
 		$Row111		=	$this->db->get_row($Qry111, ARRAY_A);

 		/*$Qry1111		=	"SELECT username FROM member_master WHERE id = '$user_id'";
 		$Row1111		=	$this->db->get_row($Qry1111, ARRAY_A);
 		$SubDomainName	=	$Row1111['username'];*/

 		$objUser			=	new User();

 		#$AccountDetails		=	$objUser->getUserdetails($_SESSION["memberid"]);
 		$AccountDetails		=	$objUser->getUserdetails($user_id);


 		$subDomain_name		=	trim($AccountDetails['account_url']);
 		if($subDomain_name == '')
 		$subDomain_name 	= 	$AccountDetails['username'];

 		$flyer_url		=	'http://'.$subDomain_name.'.'.DOMAIN_URL.'/'.$flyer_id.'/';
 		$listing_url	=	'http://'.$subDomain_name.'.'.DOMAIN_URL.'/';

 		if(trim($Row1['image']) != '')
 		$image_url		=	SITE_URL.'/modules/flyer/images/thumb/'.$Row1['image'];
 		else
 		$image_url		=	'';

 		if($Row11['photo'] != '') {
 			if(file_exists(SITE_PATH.'/modules/member/images/photos/thumb/'.$Row11['photo']))
 			$FlyerData['photo']			=	$Row11['photo'];
 		}

 		if($Row11['logo'] != '') {
 			if(file_exists(SITE_PATH.'/modules/member/images/logos/thumb/'.$Row11['logo']))
 			$FlyerData['logo']			=	$Row11['logo'];
 		}

 		$FlyerData['image_url']		=	$image_url;
 		$FlyerData['listing_url']	=	$listing_url;
 		$FlyerData['flyer_url']		=	$flyer_url;
 		$FlyerData['category']		=	$Row111['form_title'];
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

 		$FlyerData['agent_name']	=	$AccountDetails['first_name'];
 		$FlyerData['agent_email']	=	$AccountDetails['email'];
 		$FlyerData['agent_phone']	=	$AccountDetails['telephone'];
 		$FlyerData['agent_fax']		=	$AccountDetails['fax'];
 		$FlyerData['agent_company']	=	$AccountDetails['company_name'];
 		$FlyerData['agent_profile']	=	$AccountDetails['profile'];


 		$FlyerData['blocks']		=	array();

 		$Qry11	=	"SELECT show_reply, show_email,show_map, show_address FROM flyer_data_contact WHERE flyer_id = '$flyer_id' ";
 		$Row11	=	$this->db->get_row($Qry11, ARRAY_A);
 		$FlyerData['show_reply']	=	$Row11['show_reply'];
 		$FlyerData['show_email']	=	$Row11['show_email'];
 		$FlyerData['show_map']		=	$Row11['show_map'];
 		$FlyerData['show_address']	=	$Row11['show_address'];

 		$Qry2	=	"SELECT block_id, block_title, block_position FROM flyer_form_blocks WHERE form_id = '{$Row1['form_id']}' ORDER BY display_order ASC ";
 		$Rows2	=	$this->db->get_results($Qry2, ARRAY_A);

 		foreach($Rows2 as $Row2) {
 			$BlockData		=	array();
 			$block_id		=	$Row2['block_id'];
 			$block_title	=	$Row2['block_title'];

 			$Qry3		=	"SELECT
								T1.feature_id AS feature_id, 
								T2.label AS label, 
								T3.value AS value,
								T4.newoption_value AS NewValue
							FROM flyer_map_form_feature AS T1 
							LEFT JOIN flyer_form_features AS T2 ON T2.feature_id = T1.feature_id 
							LEFT JOIN flyer_data_field_values AS T3 ON T3.field_id = T1.feature_id 
							LEFT JOIN feeddropdown_mapping AS T4 ON (T4.dropdown_id = T1.feature_id AND T4.feed_id = '$feed_id' AND T4.option_value = T3.value) 
							WHERE T3.flyer_id = '$flyer_id' AND T1.block_id = '$block_id' ORDER BY T2.position ASC ";
 			$Rows3		=	$this->db->get_results($Qry3, ARRAY_A);

 			foreach($Rows3 as $Row3) {
 				$label	=	$Row3['label'];

 				if(trim($Row3['NewValue']) != '') #If there exists an option mapping for this dropdown feature
 				$value	=	$Row3['NewValue'];
 				else
 				$value	=	$Row3['value'];

 				$BlockData[$label]	=	$value;
 			}

 			$FlyerData['blocks'][$block_title]['features']			=	$BlockData;
 			$FlyerData['blocks'][$block_title]['block_position']	=	$Row2['block_position'];

 			$FlyerAttributes	=	array();
 			$Qry4	=	"SELECT checkbox_id FROM flyer_data_checkbox_values WHERE flyer_id = '$flyer_id' AND checkbox_id LIKE 'C%' ";
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

 		// For Filtering the checked links
 		$Qry10	=	"SELECT checkbox_id from flyer_data_checkbox_values where flyer_id='$flyer_id' and checkbox_id LIKE 'L%'";
 		$Rows10	=	$this->db->get_results($Qry10, ARRAY_A);
 		$chk_ids="'0'";
 		for($lk=0;$lk<count($Rows10);$lk++)
 		{
 			$Lchk_id	=	str_replace("L","",$Rows10[$lk]['checkbox_id']);
 			$chk_ids	=	$chk_ids. " , '".$Lchk_id."'";
 		}

 		// End For Filtering the checked links

 		$Links		=	array();
 		$Qry7		=	"SELECT link_title, link_url FROM flyer_links WHERE flyer_id = '$flyer_id' AND  link_id IN ($chk_ids)";
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
 		$Rows9		=	$this->db->get_row($Qry9, ARRAY_A);
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
	 * @description The following method returns the template details related with a flyer id
	 *
	 *
	 * @return An array which contains details about the selected template
	 */
 	function getTemplateDetailsFromFlyerId($flyer_id)
 	{
 		$Qry1	=	"SELECT
						T2.template_dir AS template_dir, 
						T2.template_id AS template_id, 
						T2.template_name AS template_name,
						T2.template_name AS author
					FROM flyer_data_basic AS T1 
					LEFT JOIN flyer_templates AS T2 ON T1.template_id = T2.template_id 
					WHERE T1.flyer_id = '$flyer_id'";
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
 	function generateFlyerForPublish($flyer_id, $SubdomainName)
 	{
 		$flyer_tpl			=	$this->tpl;
 		$FLYER_DATA			=	$this->getFlyerDataForPreview($flyer_id);
 		$TemplateDetails	=	$this->getTemplateDetails($FLYER_DATA['template_id']);
 		$ShowLargeImgGallry	=	$this->getLargeImageGallaryShowStatusOnFlyer($_SESSION["memberid"]);

 		$global["mod_url"] 		= SITE_URL."/modules/".$_REQUEST['mod'];
 		$global["modbase_url"] 	= SITE_URL."/modules/";
 		$global["site_url"] 	= SITE_URL;
 		$DomainURL				=	"http://".$SubdomainName.".".DOMAIN_URL."/";

 		$flyer_tpl->assign("IMAGEGALLARY_STATUS", $ShowLargeImgGallry);
 		$flyer_tpl->assign("GLOBAL", $global);
 		$flyer_tpl->assign("SUBDOMAIN_URL", $DomainURL);
 		$flyer_tpl->assign("CSS_FOLDER", $TemplateDetails['template_dir']);
 		$flyer_tpl->assign("DATE", date("H:i:s"));
 		$flyer_tpl->assign("FLYER_DATA", $FLYER_DATA);
 		$flyer_tpl->assign("FLYER_ID", $flyer_id);
 		$flyer_tpl->assign("SITE_NAME", $this->config['site_name']);
 		$flyer_tpl->assign("LABEL_FLYER", 'LISTING ');
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
							<title>{$FLYER_DATA['title']}</title>
							</head><body>$FLYER_CONTENT</body></html>";
 		$Filename	=	SITE_PATH."/htmlflyers/".$flyer_id.'.html';

 		if(file_exists($Filename))
 		@unlink($Filename);

 		$fp			=	fopen($Filename,'w');
 		fwrite($fp, $FileContent);
 		fclose($fp);


 		//commented by vipin on 25/03/2008
 		/*# start creating the PDF format flayer code added by shinu
 		require('html_to_pdf.inc.php');
 		$htmltopdf = new HTML_TO_PDF();
 		$ConvertFilename	=	SITE_URL."/htmlflyers/".$flyer_id.'.html';
 		$PDF_File	=	SITE_PATH."/pdfflyers/".$flyer_id.'.pdf';
 		if(file_exists($PDF_File))
 		@unlink($PDF_File);

 		$htmltopdf->saveFile($PDF_File);
 		$result = $htmltopdf->convertURL($ConvertFilename);
 		if($result==false)
 		echo $htmltopdf->error();
 		# end creating the PDF format flayer*/
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

 	/**
	 *	The following method used for generating code for craiglist
	 *
	 *	
	 */
 	function getCraigsListHtmlCode($flyer_id)
 	{
 		$flyer_tpl			=	$this->tpl;
 		$FLYER_DATA			=	$this->getFlyerDataForPreview($flyer_id);
 		$TemplateDetails	=	$this->getTemplateDetails($FLYER_DATA['template_id']);
 		$ShowLargeImgGallry	=	$this->getLargeImageGallaryShowStatusOnFlyer($_SESSION["memberid"]);

 		$global["mod_url"] 		= SITE_URL."/modules/".$_REQUEST['mod'];
 		$global["modbase_url"] 	= SITE_URL."/modules/";
 		$global["site_url"] 	= SITE_URL;
 		$flyer_tpl->assign("GLOBAL", $global);

 		$flyer_tpl->assign("IMAGEGALLARY_STATUS", $ShowLargeImgGallry);
 		$flyer_tpl->assign("FLYER_DATA", $FLYER_DATA);
 		$flyer_tpl->assign("FLYER_ID", $flyer_id);
 		$flyer_tpl->assign("SITE_NAME", $this->config['site_name']);
 		$flyer_tpl->assign("LABEL_FLYER", 'LISTING ');

 		$FLYER_CONTENT	=	$flyer_tpl->fetch(SITE_PATH."/html/templates/".$TemplateDetails['template_dir']."/craigslist.tpl");

 		$whitespace = array("\n", "\t", "\r");
 		$FLYER_CONTENT = str_replace($whitespace, '', $FLYER_CONTENT);

 		return $FLYER_CONTENT;
 	}


 	/**
	 *	The following method returns the HTML code for both Ebay and for other sites
	 *
	 *
	 */
 	function getHTMLCodeForEbayAndOthersSites($flyer_id)
 	{
 		$flyer_tpl			=	$this->tpl;
 		$FLYER_DATA			=	$this->getFlyerDataForPreview($flyer_id);
 		$TemplateDetails	=	$this->getTemplateDetails($FLYER_DATA['template_id']);
 		$ShowLargeImgGallry	=	$this->getLargeImageGallaryShowStatusOnFlyer('', $flyer_id);

 		$global["mod_url"] 		= SITE_URL."/modules/".$_REQUEST['mod'];
 		$global["modbase_url"] 	= SITE_URL."/modules/";
 		$global["site_url"] 	= SITE_URL;
 		$flyer_tpl->assign("GLOBAL", $global);

 		$flyer_tpl->assign("IMAGEGALLARY_STATUS", $ShowLargeImgGallry);
 		$flyer_tpl->assign("FLYER_DATA", $FLYER_DATA);
 		$flyer_tpl->assign("FLYER_ID", $flyer_id);
 		$flyer_tpl->assign("SITE_NAME", $this->config['site_name']);
 		$flyer_tpl->assign("LABEL_FLYER", 'LISTING ');

 		$FLYER_CONTENT	=	$flyer_tpl->fetch(SITE_PATH."/html/templates/".$TemplateDetails['template_dir']."/ebayhtml.tpl");

 		$whitespace = array("\n", "\t", "\r");
 		$FLYER_CONTENT = str_replace($whitespace, '', $FLYER_CONTENT);

 		return $FLYER_CONTENT;

 	}




 	function GetFormRssFields($member_id = '')
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


 	/**
	 * @description The following method returns the contact information related with a flyer
	 *
	 *
	 */
 	function getContactInfoOfFlyer($flyer_id)
 	{
 		$Qry		=	"SELECT * FROM flyer_data_contact WHERE flyer_id = '$flyer_id'";
 		$Row		=	$this->db->get_row($Qry, ARRAY_A);
 		return $Row;
 	}



 	/**
	 * @description		The following method send contact details of a visitor to the listing owner
	 *
	 *
	 * @author vimson@newagesmb.com
	 */
 	function sendContactInformation($REQUEST)
 	{
 		extract($REQUEST);
 		$flyer_tpl		=	$this->tpl;
 		$ContactArray	=	array();

 		$Qry1				=	"SELECT contact_name, contact_email FROM flyer_data_contact WHERE flyer_id = '$flyer_id'";
 		$Row1				=	$this->db->get_row($Qry1, ARRAY_A);
 		$contact_name		=	$Row1['contact_name'];
 		$contact_email		=	$Row1['contact_email'];
 		$Subject			=	'Contact Information from '.$name.', Regarding the Listing No:'.$flyer_id;

 		$ContactArray['SendersName']	=	$name;
 		$ContactArray['SendersEmail']	=	$email;
 		$ContactArray['SendersPhone']	=	$phone;
 		$ContactArray['SendersMessage']	=	$message;
 		$ContactArray['contact_name']	=	$contact_name;
 		$ContactArray['contact_email']	=	$contact_email;
 		$flyer_tpl->assign("CONTACT_INFO", $ContactArray);
 		$EMAIL_CONTENT	=	$flyer_tpl->fetch(SITE_PATH."/modules/flyer/tpl/contactusmail.tpl");

 		$MailDetails			=	array();
 		$MailDetails['content']	=	$EMAIL_CONTENT;
 		$MailDetails['from']	=	$email;
 		$MailDetails['to']		=	$contact_email;
 		$MailDetails['subject']	=	$Subject;

 		return $MailDetails;

 	}


 	#salim
 	function deleteUserImage ($img_name,$type)
 	{
 		$user_id		=	$_SESSION["memberid"];
 		if ($type=='M'){
 			$qry		=	"update member_master set photo='' where id ='$user_id' LIMIT 1";
 			$path		=	SITE_PATH."/modules/member/images/photos/thumb/".$img_name;
 		}

 		else{
 			$qry		=	"update member_master set logo='' where id ='$user_id' LIMIT 1";
 			$path		=	SITE_PATH."/modules/member/images/logos/thumb/".$img_name;
 		}

 		$this->db->query($qry);
 		return true;
 		echo unlink($path);exit;
 	}


 	/**
	 * @description The following method validates the forward listing form 
	 *
	 * @author	vimson@newagesmb.com
	 *
	 */
 	function sendForwardListingDetails($REQUEST)
 	{
 		extract($REQUEST);
 		$msg						=	'';
 		$MailDetails				=	array();
 		$MailDetails['sendstatus']	=	TRUE;

 		if(trim($name) == '') {

 			$msg						=	'Name required<br>';
 			$MailDetails['sendstatus']	=	FALSE;
 		}

 		if(trim($email) == '') {
 			$msg						.=	'Your Email address required<br>';
 			$MailDetails['sendstatus']	=	FALSE;
 		}

 		if(trim($email) != '') {
 			if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email))
 			$msg						.=	'Valid Mail address required<br>';
 		}

 		if(trim($emailaddresses) == '') {
 			$msg						.=	'Receiver Mail address required<br>';
 			$MailDetails['sendstatus']	=	FALSE;
 		}

 		if(trim($emailaddresses) != '') {
 			$msg	=	'Listings are forwaded';
 			$MailDetails['sendstatus']	=	TRUE;
 		}


 		/*
 		* The following is the condition when the form validation is true.
 		*
 		*/
 		if($MailDetails['sendstatus'] === TRUE) {
 			$mail_tpl		=	$this->tpl;
 			$FLYER_DATA	 	= 	$this->getHTMLCodeForEbayAndOthersSites($flyer_id);

 			$mail_tpl->assign("FLYER_CONTENT", $FLYER_DATA);
 			$mail_tpl->assign("SENDER", $name);
 			$mail_tpl->assign("MESSAGE", $message);
 			$EMAIL_CONTENT	=	$mail_tpl->fetch(SITE_PATH."/modules/flyer/tpl/forwardmail.tpl");

 			$MailDetails['mailids']		=	$emailaddresses;
 			$MailDetails['content']		=	'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
											<html xmlns="http://www.w3.org/1999/xhtml">
											<head>
											<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
											<title>Untitled Document</title>
											</head>
											<body>'.$EMAIL_CONTENT.
											'</body>
											</html>';
											$MailDetails['from']		=	$email;
											$MailDetails['subject']		=	'sawitonline.com - '.$name.' has sent you this listing which might be of your interest';
 		}

 		$MailDetails['message']		=	$msg;

 		return $MailDetails;

 	}


 	function sendForwardMailDetails($REQUEST)
 	{

 		extract($REQUEST);
 		$msg						=	'';
 		$MailDetails				=	array();
 		$MailDetails['sendstatus']	=	TRUE;

 		if(trim($name) == '') {
 			$msg						=	'Name required<br>';
 			$MailDetails['sendstatus']	=	FALSE;
 		}

 		if(trim($email) == '') {
 			$msg						.=	'Your Email address required<br>';
 			$MailDetails['sendstatus']	=	FALSE;
 		}

 		if(trim($email) != '') {
 			if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email))
 			$msg						.=	'Valid Mail address required<br>';
 		}

 		if(trim($emailaddresses) == '') {
 			$msg						.=	'Receiver Mail address required<br>';
 			$MailDetails['sendstatus']	=	FALSE;
 		}

 		if(trim($emailaddresses) != '') {
 			$msg	=	'Listings are forwaded';
 			$MailDetails['sendstatus']	=	TRUE;
 		}
 		$MailDetails['message']		=	$msg;
 		return $MailDetails;

 	}

 	/**
	 * @description	 The following method returns the status of the Subscription package whether the provision for large 
	 * 				 image gallary exists or not	
	 *
	 * @return String	'Show' --> Show the large image gallary in Flyer. 'Hide' --> Hide the Large image gallary in site
	 */
 	function getLargeImageGallaryShowStatusOnFlyer($MemberId = '', $FlyerId = '')
 	{
 		$ShowStatus		=	'';

 		if(trim($MemberId) != '')
 		$Qry			=	"SELECT reg_pack FROM member_master WHERE id = '$MemberId'";

 		if(trim($FlyerId) != '')
 		$Qry			=	"SELECT
									T2.reg_pack AS reg_pack
								FROM flyer_data_basic AS T1 
								LEFT JOIN member_master AS T2 ON T2.id = T1.user_id 
								WHERE T1.flyer_id = '$FlyerId' ";

 		$Row			=	$this->db->get_row($Qry, ARRAY_A);
 		$reg_pack		=	$Row['reg_pack'];

 		if(in_array($reg_pack, $this->FreeRegnPackages))
 		$ShowStatus	=	'Hide';
 		else
 		$ShowStatus	=	'Show';

 		return $ShowStatus;

 	}



 	/**
	 * @description Unpublish the flyer
	 *
	 */
 	function unPublishFlyer($flyer_id)
 	{
 		$Qry	=	"UPDATE flyer_data_basic SET publish = 'N' WHERE flyer_id = '$flyer_id'";
 		$this->db->query($Qry);
 		return TRUE;
 	}





 	#####################################################################################
 	# 																					#
 	#	@The following Section contains methods which are used for Bayard Properties	#
 	#																					#
 	#####################################################################################



 	/**
	 * @description The following method generates a flyer  vimson@newagesmb.com
	 *
	 * @return generated flyer_id
	 */
 	function createBasicFlyer($REQUEST)
 	{
 		extract($REQUEST);
 		$InsertArray		=	array();
 		$DaysAfterExpire	=	$this->config['daysafter_propertyexpire'];
 		$CurrDate			=	date('Y-m-d');
 		$ExpireDate			=	date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")+30, date("Y")));
 		$UserId				=	$_SESSION["memberid"];

 		$AlbumInsertArray	=	array('user_id' => $UserId);
 		$this->db->insert("album", $AlbumInsertArray);
 		$album_id			=	$this->db->insert_id;

 		$InsertArray		=	array('user_id' => $UserId, 'form_id' => $form_id, 'modified_date' => $CurrDate, 'expire_date' => $ExpireDate, 'active' => 'N','album_id' => $album_id);
 		$this->db->insert("flyer_data_basic", $InsertArray);
 		$flyer_id	=		$this->db->insert_id;

 		return array($flyer_id,$album_id);
 	}


 	/**
	 * @desc The following method Validates the flyer basic form
	 * /**
	 * Author :
	 * Created:27/Feb/2008
	 * Modified :08/Apr/2008 by vipin
	 */

 	function validateFlyerDataBasicForm($REQUEST)
 	{
 		extract($_REQUEST);
 		$msg	=	'';

 		if($flyer_name == '')
 		$msg	=	'Property Name Required';
 		elseif (isset($quantity) and ($quantity == '' or $quantity<=0))
 		$msg	=	'Quantity Required';
 		/*if($title == '')
 		$msg	.=	'<br>Property Title Required';*/

 		if($msg == '')
 		return TRUE;
 		else
 		return $msg;
 	}


 	/**
	 * @desc The following method stores the flyer basic data
	 * 
	 * @return return TRUE if the save operation is successful
	 */
 	function saveFlyerBasicData($REQUEST,$QTY_DISPLAY='')
 	{
 		extract($REQUEST);
 		$UpdateArray	=	array('flyer_name' => $flyer_name, 'title' => $title, 'description' => $description,'quantity' => $quantity, 'active' => 'Y');
 		$this->db->update("flyer_data_basic", $UpdateArray, " flyer_id='$flyer_id' ");

 		# The following section saves contact and location information
 		$Qry1	=	"DELETE FROM flyer_data_contact WHERE flyer_id = '$flyer_id'";
 		$this->db->query($Qry1);

 		$ContactArray	=	array('flyer_id' => $flyer_id, 'contact_name' => $contact_name, 'contact_phone' => $contact_phone, 'contact_email' => $contact_email, 'location_street_address' => $location_street_address, 'location_city' => $location_city, 'location_state' => $location_state, 'location_country' => $location_country, 'location_neighborhood' => $location_neighborhood,'location_zip' => $location_zip);

 		if($show_reply == 'Y')
 		$ContactArray	=	array_merge($ContactArray, array('show_reply' => 'Y'));
 		else
 		$ContactArray	=	array_merge($ContactArray, array('show_reply' => 'N'));

 		if($show_email == 'Y')
 		$ContactArray	=	array_merge($ContactArray, array('show_email' => 'Y'));
 		else
 		$ContactArray	=	array_merge($ContactArray, array('show_email' => 'N'));

 		if($show_address == 'Y')
 		$ContactArray	=	array_merge($ContactArray, array('show_address' => 'Y'));
 		else
 		$ContactArray	=	array_merge($ContactArray, array('show_address' => 'N'));

 		if($show_map == 'Y')
 		$ContactArray	=	array_merge($ContactArray, array('show_map' => 'Y'));
 		else
 		$ContactArray	=	array_merge($ContactArray, array('show_map' => 'N'));

 		$this->db->insert("flyer_data_contact", $ContactArray);

 		/*
 		*Save record to album_quantity_title table
 		*Author:Afsal Ismail
 		*Created:06-12-2007
 		*/

 		if($QTY_DISPLAY == "Y"){

 			if(count($qtytitle)){

 				$this->db->query("DELETE FROM album_quantity_title WHERE album_id=$propid");

 				for($i=0;$i<count($qtytitle);$i++){

 					$qtytitleAray = array("album_id" => $propid,"title" => $qtytitle[$i]);
 					$this->db->insert("album_quantity_title",$qtytitleAray);
 				}
 			}else{
 				$this->db->query("DELETE FROM album_quantity_title WHERE album_id=$propid");

 				$qtytitleAray = array("album_id" => $propid,"title" => 'Quantity Title1');
 				$this->db->insert("album_quantity_title",$qtytitleAray);
 			}
 		}

 		return TRUE;
 	}


 	/**
	 * @desc The following method returns the Flyer basic data related with particular flyer id
	 * 
	 * @return An array which contains data for Flyer basic form
	 */
 	function getFlyerBasicFormData($flyer_id)
 	{
 		$FlyerBasicData	=	array();
 		/*
 		$Qry1			=	"SELECT * FROM flyer_data_basic WHERE flyer_id = '$flyer_id'";
 		$Row1			=	$this->db->get_row($Qry1, ARRAY_A);
 		$FlyerBasicData	=	array_merge($FlyerBasicData,$Row1);

 		$Qry2			=	"SELECT * FROM flyer_data_contact WHERE flyer_id = '$flyer_id'";
 		$Row2			=	$this->db->get_row($Qry2, ARRAY_A);
 		$FlyerBasicData	=	array_merge($FlyerBasicData,$Row2);

 		//print_r($FlyerBasicData);
 		*/
 		$Qry1			=	"SELECT f1.*,f2.* FROM flyer_data_basic AS f1 INNER JOIN flyer_data_contact AS f2 ON f1.flyer_id = f2.flyer_id WHERE f1.flyer_id = '$flyer_id'";

 		$FlyerBasicData	=	$this->db->get_row($Qry1, ARRAY_A);

 		return $FlyerBasicData;
 	}

 	function getFlyerContactInfo($flyer_id)
 	{
 		$Qry2			=	"SELECT location_street_address,location_city,location_state,location_country,location_zip FROM flyer_data_contact WHERE flyer_id = '$flyer_id'";
 		$Row2			=	$this->db->get_row($Qry2, ARRAY_A);
 		return $Row2;
 	}

 	/**
	 * @desc The following method returns the form details associated with a flyer
	 * 
	 * 
	 * @return An array which containg form details and data
	 * 
	 * @author vimson@newagesmb.com
	 * 	 
	 */
 	function getFlyerFeaturesAndAttributes($flyer_id)
 	{
 		$BlockDetails	=	array();

 		$Qry1			=	"SELECT form_id FROM flyer_data_basic WHERE flyer_id = '$flyer_id'";
 		$Row1			=	$this->db->get_row($Qry1, ARRAY_A);
 		$form_id		=	$Row1['form_id'];

 		$Qry2			=	"SELECT block_id, block_title FROM flyer_form_blocks WHERE form_id = '$form_id' AND active = 'Y' ORDER BY display_order ASC";
 		$Rows2			=	$this->db->get_results($Qry2,ARRAY_A);


 		$GroupArray	=	array();
 		$GroupIndx	=	0;
 		$ArrIndx	=	0;
 		foreach($Rows2 as $Row2) {
 			$block_id		=	$Row2['block_id'];
 			$block_title	=	$Row2['block_title'];

 			$BlockDetails['blocks'][$ArrIndx]['BlockTitle']	=	$block_title;
 			$BlockDetails['blocks'][$ArrIndx]['BlockId']		=	$block_id;


 			$Qry3	=	"SELECT T2.*
						FROM flyer_map_form_feature AS T1 
						LEFT JOIN flyer_form_features AS T2 ON T2.feature_id = T1.feature_id 
						WHERE T1.block_id = '$block_id' AND (T2.flyer_id = 0 OR T2.flyer_id = '$flyer_id') AND T2.active = 'Y' ORDER BY T2.position,T2.feature_id ASC";
 			$Rows3	=	$this->db->get_results($Qry3,ARRAY_A);

 			$BlockDetails[$ArrIndx]['features']	=	array();

 			$ArrIndx3	=	0;
 			foreach($Rows3 as $Row3) {
 				$tmp_flyer_id	=	$Row3['flyer_id'];
 				$field_type		=	$Row3['type'];
 				$feature_id		=	$Row3['feature_id'];

 				if($tmp_flyer_id == 0)
 				$CustomField	=	'No';
 				else
 				$CustomField	=	'Yes';

 				$BlockDetails['blocks'][$ArrIndx]['features'][$ArrIndx3]['feature_id']		=	$feature_id;
 				$BlockDetails['blocks'][$ArrIndx]['features'][$ArrIndx3]['field_type']		=	$field_type;
 				$BlockDetails['blocks'][$ArrIndx]['features'][$ArrIndx3]['field_name']		=	'F'.$feature_id;
 				$BlockDetails['blocks'][$ArrIndx]['features'][$ArrIndx3]['label']			=	$Row3['label'];
 				$BlockDetails['blocks'][$ArrIndx]['features'][$ArrIndx3]['custom_field']	=	$CustomField;

 				$OptionsArr	=	array();
 				if($field_type == 'Dropdown') {
 					$Qry4		=	"SELECT name FROM flyer_form_features_values
									WHERE feature_id = '$feature_id' AND active = 'Y' AND (flyer_id = 0 OR flyer_id = '$flyer_id') ";
 					$Rows4		=	$this->db->get_results($Qry4,ARRAY_A);

 					$ArrIndx2	=	0;
 					foreach($Rows4 as $Row4) {
 						$OptionsArr[$ArrIndx2]	=	$Row4['name'];
 						$ArrIndx2++;
 					}
 				}
 				$BlockDetails['blocks'][$ArrIndx]['features'][$ArrIndx3]['Options']	=	$OptionsArr;

 				$Qry5	=	"SELECT value FROM flyer_data_field_values WHERE field_id = '$feature_id' AND flyer_id = '$flyer_id'";
 				$Row5	=	$this->db->get_row($Qry5, ARRAY_A);

 				$BlockDetails['blocks'][$ArrIndx]['features'][$ArrIndx3]['feature_value']	=	$Row5['value'];

 				$ArrIndx3++;
 			}

 			$BlockDetails[$ArrIndx]['attributes']	=	array();

 			$Qry6		=	"SELECT T1.attr_group_id, T2.group_name
							FROM  flyer_map_form_option_groups AS T1 
							LEFT JOIN flyer_form_attribute_groups AS T2 ON T2.attr_group_id = T1.attr_group_id 
							WHERE T1.block_id = '$block_id' AND T2.active = 'Y'";
 			$Rows6		=	$this->db->get_results($Qry6,ARRAY_A);

 			$ArrIndx4	=	0;
 			foreach($Rows6 as $Row6) {
 				$attr_group_id	=	$Row6['attr_group_id'];
 				$group_name		=	$Row6['group_name'];

 				$GroupArray[$GroupIndx]['group_id']		=	$attr_group_id;
 				$GroupArray[$GroupIndx]['group_label']	=	'G'.$attr_group_id;
 				$GroupIndx++;


 				$BlockDetails['blocks'][$ArrIndx]['attributes'][$ArrIndx4]['group_id']		=	$attr_group_id;
 				$BlockDetails['blocks'][$ArrIndx]['attributes'][$ArrIndx4]['group_name']	=	$group_name;

 				$Qry7		=	"SELECT * FROM flyer_form_attribute_item
								WHERE attr_group_id = '$attr_group_id' AND active = 'Y' AND (flyer_id = 0 OR flyer_id = '$flyer_id') ORDER BY item_id ASC ";
 				$Rows7		=	$this->db->get_results($Qry7,ARRAY_A);


 				$ItemArray			=	array();
 				$ArrIndx5			=	0;
 				foreach($Rows7 as $Row7) {
 					$item_name	=	$Row7['item_name'];
 					$item_id	=	$Row7['item_id'];
 					$field_name	=	'C'.$Row7['item_id'];

 					$ItemArray[$ArrIndx5]['label']			=	$Row7['item_name'];
 					$ItemArray[$ArrIndx5]['id']				=	$Row7['item_id'];
 					$ItemArray[$ArrIndx5]['field_name']		=	$field_name;

 					$Qry8		=	"SELECT COUNT(*) AS TotCount FROM flyer_data_checkbox_values
									WHERE checkbox_id = '$field_name' AND flyer_id = '$flyer_id' AND value = '1'";
 					$Row8		=	$this->db->get_row($Qry8, ARRAY_A);
 					$TotCount	=	$Row8['TotCount'];

 					if($TotCount > 0)
 					$ItemArray[$ArrIndx5]['checked']		=	'Yes';
 					else
 					$ItemArray[$ArrIndx5]['checked']		=	'No';

 					$ArrIndx5++;
 				}
 				$BlockDetails['blocks'][$ArrIndx]['attributes'][$ArrIndx4]['Items']			=	$ItemArray;
 				$BlockDetails['blocks'][$ArrIndx]['attributes'][$ArrIndx4]['SelItems']		=	$this->getSelectedAttributes($ItemArray, $flyer_id);
 				$ArrIndx4++;
 			}

 			$ArrIndx++;

 		} # Close foreach $Rows2

 		$BlockDetails['groups']		=	$GroupArray;

 		return $BlockDetails;

 	}


 	/**
	 * @desc The following method used for saving attributes at the time of Drag and Drop operation
	 * 
	 * 
	 * 
	 */
 	function saveFlyerAttributes($AttributeIds, $flyer_id)
 	{
 		if($AttributeIds == 'NoValue') #In case No change made by the user in the Drag and Drop form
 		return;

 		$Qry1	=	"DELETE FROM flyer_data_checkbox_values WHERE flyer_id = '$flyer_id'";
 		$this->db->query($Qry1);

 		if($AttributeIds == '')
 		return TRUE;

 		$Ids	=	explode(',',$AttributeIds);
 		$Posn	=	1;
 		foreach($Ids as $Id){
 			$Qry2	=	"INSERT INTO flyer_data_checkbox_values (flyer_id,checkbox_id,position,value)
						VALUES ('$flyer_id','C{$Id}','$Posn','1')";
 			$this->db->query($Qry2);
 			$Posn++;
 		}

 		return TRUE;
 	}


 	/**
	 * @desc The following method returns the selected items r
	 * 
	 * @param The Items which are associated with a particular group as parameter
	 * 
	 * @return The items selected by the flyer
	 *  
	 */
 	function getSelectedAttributes($Items, $flyer_id = '')
 	{
 		$SelItems	=	array();

 		$ItemFilter	=	'';
 		foreach($Items as $Item)
 		$ItemFilter	.=	"'{$Item['field_name']}',";

 		if($ItemFilter != '')
 		$ItemFilter	=	' AND checkbox_id IN ('.substr($ItemFilter,0,-1).') ';
 		else
 		$ItemFilter	=	" AND checkbox_id IN ('') ";

 		$Qry1	=	"SELECT checkbox_id FROM flyer_data_checkbox_values WHERE 1 AND flyer_id = '$flyer_id' $ItemFilter ORDER BY position ASC";
 		$Rows1	=	$this->db->get_results($Qry1,ARRAY_A);

 		$ArrIndx	=	0;
 		foreach($Rows1 as $Row1) {
 			$checkbox_id	=	trim($Row1['checkbox_id']);
 			$id				=	substr($checkbox_id,1);

 			$Qry2		=	"SELECT item_name FROM flyer_form_attribute_item WHERE item_id = '$id'";
 			$Row2		=	$this->db->get_row($Qry2, ARRAY_A);
 			$item_name	=	$Row2['item_name'];

 			$SelItems[$ArrIndx]['label']	=	$item_name;
 			$SelItems[$ArrIndx]['id']		=	$id;
 			$ArrIndx++;
 		}

 		return $SelItems;
 	}


 	/**
	 * @desc Following method saves the flyer information
	 * 
	 *  
	 */
 	function saveFlyerFeatures($REQUEST)
 	{
 		extract($REQUEST);
 		$this->saveFlyerAttributes($SelAttributeIds, $flyer_id);

 		foreach($REQUEST as $field_name => $field_value) {
 			$FPos	=	strpos($field_name, "F");

 			if($FPos === FALSE)
 			continue;

 			$field_id	=	substr($field_name,1);
 			$Qry1		=	"SELECT value_id FROM flyer_data_field_values WHERE field_id = '$field_id' AND flyer_id = '$flyer_id'";
 			$Row1		=	$this->db->get_row($Qry1, ARRAY_A);
 			$value_id	=	trim($Row1['value_id']);

 			if($value_id != '') {
 				$UpdArr		=	array('flyer_id' => $flyer_id, 'field_id' => $field_id, 'value' => $field_value);
 				$this->db->update("flyer_data_field_values", $UpdArr, "value_id='$value_id'");
 			} else {
 				$InsdArr	=	array('flyer_id' => $flyer_id, 'field_id' => $field_id, 'value' => $field_value);
 				$this->db->insert("flyer_data_field_values", $InsdArr);
 			}

 		} #Close foreach
 	}

 	/**
	 * @desc The following method saves the custom fields created by the users
	 * 
	 */
 	function saveCustomField($REQUEST)
 	{
 		extract($REQUEST);

 		if(trim($user_field_label) != '') {
 			$InsArr		= 	array("label"=>$user_field_label,"type"=>$user_field_type,"length"=>"20","required"=>"N","active"=>"Y","flyer_id"=>$flyer_id);
 			$this->db->insert("flyer_form_features", $InsArr);
 			$feature_id = 	$this->db->insert_id;
 			$mapArray 	= 	array("block_id" => $BlockId,"feature_id" => $feature_id);
 			$this->db->insert("flyer_map_form_feature", $mapArray);
 		}
 		return TRUE;
 	}

 	/**
	 * 
	 * @desc The follwing method adds an attribute item
	 * 
	 * 
	 */
 	function saveAttributeItem($REQUEST)
 	{
 		extract($REQUEST);

 		if(trim($user_checkbox_label) != "")
 		{
 			$InsArray	= 	array("item_name" => $user_checkbox_label,"attr_group_id" => $GroupId,"active" => "Y","flyer_id" => $flyer_id);
 			$this->db->insert("flyer_form_attribute_item", $InsArray);
 			$check_id 	= 	$this->db->insert_id;

 			//$DataArray 			= 	array("flyer_id" => $flyer_id,"checkbox_id" => "C".$check_id,"value" => "1");
 			//$this->db->insert("flyer_data_checkbox_values", $DataArray);
 		}
 		return TRUE;
 	}


 	/**
	 * @desc The following method saves a option with the feature
	 * 
	 * 
	 */
 	function saveOption($REQUEST)
 	{
 		extract($REQUEST);

 		if(trim($user_dropdown) != '') {
 			$InsArray	= 	array("feature_id" => $FeatureId,"name" => $user_dropdown,"active" => "Y","flyer_id" => $flyer_id);
 			$this->db->insert("flyer_form_features_values", $InsArray);

 			$this->db->query("DELETE FROM flyer_data_field_values  WHERE field_id ='$FeatureId' and flyer_id='$flyer_id'");

 			$DataArr	= 	array("flyer_id" => $flyer_id,"field_id" => $FeatureId,"value" => $user_dropdown);
 			$this->db->insert("flyer_data_field_values", $DataArr);

 		}
 	}


 	/**
	 * The following method saves contact and location information in flyer data basic table
	 *
	 * @param Array $REQUEST
	 * @return boolean 
	 */
 	function saveContactAndLocationInfo($REQUEST)
 	{
 		extract($REQUEST);

 		$Qry1	=	"DELETE FROM flyer_data_contact WHERE flyer_id = '$flyer_id'";
 		$this->db->query($Qry1);

 		$ContactArray	=	array('flyer_id' => $flyer_id, 'contact_name' => $contact_name, 'contact_phone' => $contact_phone, 'contact_email' => $contact_email, 'location_street_address' => $location_street_address, 'location_city' => $location_city, 'location_state' => $location_state, 'location_country' => $location_country, 'location_neighborhood' => $location_neighborhood);

 		if($show_reply == 'Y')
 		$ContactArray	=	array_merge($ContactArray, array('show_reply' => 'Y'));
 		else
 		$ContactArray	=	array_merge($ContactArray, array('show_reply' => 'N'));

 		if($show_email == 'Y')
 		$ContactArray	=	array_merge($ContactArray, array('show_email' => 'Y'));
 		else
 		$ContactArray	=	array_merge($ContactArray, array('show_email' => 'N'));

 		if($show_address == 'Y')
 		$ContactArray	=	array_merge($ContactArray, array('show_address' => 'Y'));
 		else
 		$ContactArray	=	array_merge($ContactArray, array('show_address' => 'N'));

 		if($show_map == 'Y')
 		$ContactArray	=	array_merge($ContactArray, array('show_map' => 'Y'));
 		else
 		$ContactArray	=	array_merge($ContactArray, array('show_map' => 'N'));

 		$this->db->insert("flyer_data_contact", $ContactArray);

 		return TRUE;
 	}


 	/**
	 * The following method returns the sttaus of the flyer, whether it is active or not
	 *
	 * @param int $flyer_id
	 * @return TRUE
	 */
 	function getStatusOfFlyer($flyer_id)
 	{
 		$Qry1		=	"SELECT active FROM flyer_data_basic WHERE flyer_id = '$flyer_id'";
 		$Row1		=	$this->db->get_row($Qry1, ARRAY_A);
 		$active		=	$Row1['active'];
 		if($active == 'Y')
 		return TRUE;
 		else
 		return FALSE;
 	}

 	function getPropertyCreatonStepsHTML($StepNumber = 0, $flyer_id,$prop_id)
 	{
 		$FlyerStatus	=	$this->getStatusOfFlyer($flyer_id);

 		if($FlyerStatus === FALSE) {
 			$StepsHTLTML	=	'<div class="stepactivestyle">Step1</div>
								<div class="stepinactivestyle">Step2</div>
								<div class="stepinactivestyle">Step3</div>
								<div class="stepinactivestyle">Step4</div>
								<div class="stepinactivestyle">Step5</div>
								<div class="stepinactivestyle">Step6</div>
								<div class="stepinactivestyle">Step7</div>';
 			return $StepsHTLTML;
 		}

 		if($StepNumber == 1) {
 			$StepsHTLTML	=	'<div class="stepactivestyle">Step1</div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=features_form&flyer_id=$flyer_id&propid=$prop_id").'">Step2</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_quantity&flyer_id=$flyer_id&propid=$prop_id").'">Step3</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=map&flyer_id=$flyer_id&propid=$prop_id").'">Step4</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"album", "pg"=>"photo"), "act=upload&propid=$flyer_id&crt=M2&flyer_id=$flyer_id&propid=$prop_id").'">Step5</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"album", "pg"=>"video"), "act=upload&flyer_id=$flyer_id&propid=$prop_id").'">Step6</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_view&flyer_id=$flyer_id&propid=$prop_id").'">Step7</a></div>';
 			return $StepsHTLTML;
 		}

 		if($StepNumber == 2) {
 			$StepsHTLTML	=	'<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_form&flyer_id=$flyer_id&propid=$prop_id").'">Step1</a></div>
								<div class="stepactivestyle">Step2</div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_quantity&flyer_id=$flyer_id&propid=$prop_id").'">Step3</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=map&flyer_id=$flyer_id&propid=$prop_id").'">Step4</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"album", "pg"=>"photo"), "act=upload&crt=M2&flyer_id=$flyer_id&propid=$prop_id").'">Step5</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"album", "pg"=>"video"), "act=upload&flyer_id=$flyer_id&propid=$prop_id").'">Step6</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_view&flyer_id=$flyer_id&propid=$prop_id").'">Step7</a></div>';
 			return $StepsHTLTML;
 		}

 		if($StepNumber == 3) {
 			$StepsHTLTML	=	'<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_form&flyer_id=$flyer_id&propid=$prop_id").'">Step1</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=features_form&flyer_id=$flyer_id&propid=$prop_id").'">Step2</a></div>
								<div class="stepactivestyle">Step3</div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=map&flyer_id=$flyer_id&propid=$prop_id").'">Step4</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"album", "pg"=>"photo"), "act=upload&crt=M2&flyer_id=$flyer_id&propid=$prop_id").'">Step5</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"album", "pg"=>"video"), "act=upload&flyer_id=$flyer_id&propid=$prop_id").'">Step6</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_view&flyer_id=$flyer_id&propid=$prop_id").'">Step7</a></div>';
 			return $StepsHTLTML;
 		}
 		if($StepNumber == 4) {
 			$StepsHTLTML	=	'<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_form&flyer_id=$flyer_id&propid=$prop_id").'">Step1</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=features_form&flyer_id=$flyer_id&propid=$prop_id").'">Step2</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_quantity&flyer_id=$flyer_id&propid=$prop_id").'">Step3</a></div>
								<div class="stepactivestyle">Step4</div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"album", "pg"=>"photo"), "act=upload&crt=M2&&flyer_id=$flyer_id&propid=$prop_id").'">Step5</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"album", "pg"=>"video"), "act=upload&flyer_id=$flyer_id&propid=$prop_id").'">Step6</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_view&flyer_id=$flyer_id&propid=$prop_id").'">Step7</a></div>';
 			return $StepsHTLTML;
 		}
 		if($StepNumber == 5) {
 			$StepsHTLTML	=	'<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_form&flyer_id=$flyer_id&propid=$prop_id").'">Step1</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=features_form&flyer_id=$flyer_id&propid=$prop_id").'">Step2</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_quantity&flyer_id=$flyer_id&propid=$prop_id").'">Step3</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=map&flyer_id=$flyer_id&propid=$prop_id").'">Step4</a></div>
								<div class="stepactivestyle">Step5</div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"album", "pg"=>"video"), "act=upload&flyer_id=$flyer_id&propid=$prop_id").'">Step6</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_view&flyer_id=$flyer_id&propid=$prop_id").'">Step7</a></div>';
 			return $StepsHTLTML;
 		}
 		if($StepNumber == 6) {
 			$StepsHTLTML	=	'<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_form&flyer_id=$flyer_id&propid=$prop_id").'">Step1</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=features_form&flyer_id=$flyer_id&propid=$prop_id").'">Step2</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_quantity&flyer_id=$flyer_id&propid=$prop_id").'">Step3</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=map&flyer_id=$flyer_id&propid=$prop_id").'">Step4</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"album", "pg"=>"photo"), "act=upload&crt=M2&flyer_id=$flyer_id&propid=$prop_id").'">Step5</a></div>
								<div class="stepactivestyle">Step6</div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_view&flyer_id=$flyer_id&propid=$prop_id").'">Step7</a></div>';
 			return $StepsHTLTML;
 		}

 		if($StepNumber == 7) {
 			$StepsHTLTML	=	'<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_form&flyer_id=$flyer_id&propid=$prop_id").'">Step1</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=features_form&flyer_id=$flyer_id&propid=$prop_id").'">Step2</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_quantity&flyer_id=$flyer_id&propid=$prop_id").'">Step3</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=map&flyer_id=$flyer_id&propid=$prop_id").'">Step4</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"album", "pg"=>"photo"), "act=upload&crt=M2&flyer_id=$flyer_id&propid=$prop_id").'">Step5</a></div>
								<div class="stepinactivestyle"><a class="stepstyle" href="'.SITE_URL.'/'.makeLink(array("mod"=>"album", "pg"=>"video"), "act=upload&flyer_id=$flyer_id&propid=$prop_id").'">Step6</a></div>
								<div class="stepactivestyle">Step7</div>';
 			return $StepsHTLTML;
 		}

 	}

 	/**
	* Get the flyerid and flyername
	* @Author   : Afsal
	* @Created  : 14/Nov/2007
	* 
	*/
 	function getFlayerListCombo($memberid,$propid){

 		$sql = "SELECT flyer_id,title FROM flyer_data_basic WHERE active = 'Y'
		        AND user_id=$memberid AND flyer_id <> $propid";
 		$rs 		= $this->db->get_row($sql);

 		if (count($rs)){

 			$propArr[0] = $this->db->get_col($sql,0);
 			$propArr[1] = $this->db->get_col($sql,1);
 			return $propArr;
 		}
 		else{
 			return  0;
 		}

 	}
 	/**
	* Update quantity to flyer_basic_data AND insert related property if selected from the select box
	* @Author   : Afsal
	* @Created  : 14/Nov/2007
	*/
 	function saveQuantityAndRelated($req){

 		$this->db->update("flyer_data_basic",array("quantity" => $req["quantity"]) ,"flyer_id={$req['flyer_id']}");
 		$this->db->query("DELETE FROM album_related WHERE album_id={$req['propid']}");

 	}
 	/**
	* Get the related property details from album_related
	* @Author   : Afsal
	* @Created  : 14/Nov/2007
	*/
 	function getRelatedProperty($propid){

 		$sql	 = "SELECT album_relt_id FROM album_related WHERE album_id=$propid";
 		$rs		 = $this->db->get_col($sql,0);
 		return $rs;
 	}
 	/**
	* Update quantity to flyer_basic_data AND insert related property if selected from the select box
	* @Author   : Afsal
	* @Created  : 19/Nov/2007
	*/
 	function saveAdvanceQtyBookAndBlockQty($req,$membrid){

 		/* One more checking will come here for finding the current ownership of the property*/
 		if(count($req) && $membrid > 0){


 			if($req["prop_blck_id"] >0){

 				$blockQtyArray = array("album_id" => $req['propid'],"from_date" => $req["start_date_blck"],
 				"to_date" => $req["rent_end_date_blck"],"current_user_id" =>$membrid,"color_code"=> $req["color_code"]);

 				return $this->db->update("property_blocked",$blockQtyArray,"id={$req["prop_blck_id"]}");

 			}else{

 				$blockQtyArray = array("album_id" => $req['propid'],"from_date" => $req["start_date_blck"],
 				"to_date" => $req["rent_end_date_blck"],"current_user_id" =>$membrid,"color_code"=> $req["color_code"]);
 				return $this->db->insert("property_blocked",$blockQtyArray);
 			}
 		}
 	}
 	/**
	* Get quantity of property which user have already blocked
	* @Author   : Afsal
	* @Created  : 19/Nov/2007
	*/
 	function getPropertyBlockQuantity($propid,$memberid){

 		$sql = "SELECT id,date_format(from_date, '%Y-%m-%d' )AS from_date ,date_format(to_date,'%Y-%m-%d') AS to_date,
		        album_quantity_title_id,current_user_id,color_code FROM property_blocked WHERE album_id = $propid ORDER BY id ASC";


 		$rs  = $this->db->get_results($sql,ARRAY_A);
 		return $rs;
 	}
 	/**
	* List the Blocking Quantity in upload property page
	* @Author   : Afsal
	* @Created  : 19/Nov/2007
	*/
 	function listEditPartBlockQuantity($qtyArray,$objCalendar,$quantityTitle = '',$qty){

 		if (count($qtyArray)){

 			$num = 0;
 			$global["tpl_url"] = SITE_URL."/templates/".$this->config['curr_tpl'];

 			foreach($qtyArray as $row){

 				$strs	.= '<div id="my'.$num.'Div">';
 				$strs	.= '<div class="divSpc"></div>';
 				$strs	.= '<div class="floatleft">';
 				$strs	.= '<div class="bodytext">From</div>';
 				$strs   .= '<div class="floatleft"><input type="text" class="inputelement" name="fromB[]" id="txt'.$num.'" value="'.$row["from_date"].'" onClick="javascript:MovetoCallendar('.$num.');" size="15" readonly></div>';
 				$strs	.= "</div>";
 				$strs   .= 	$objCalendar->DrawCalendarLayer(date('Y'),date('m'),date('d'),$num,array('FCase'=>'','TimeShow'=>'YES'));

 				$num	 = $num + 1;
 				$strs	.= '<div class="floatleft">&nbsp;&nbsp;</div>';
 				$strs	.= '<div class="floatleft">';
 				$strs	.= '<div class="bodytext">To</div>';
 				$strs   .= '<div class="floatleft"><input type="text" class="inputelement" name="toB[]" id="txt'.$num.'" value="'.$row["to_date"].'" onClick="javascript:MovetoCallendar('.$num.');"  size="15" readonly></div>';
 				$strs	.= '</div>';

 				$strs	.= '<div class="floatleft">&nbsp;&nbsp;</div>';
 				$strs	.= '<div class="floatleft">';
 				if($qty =='Y'){
 					$strs	.= '<div class="bodytext">Quantity</div>';
 					$strs   .= '<div class="floatleft">';
 					$strs   .= '<select name="bqty[]">';
 					$strs   .= '<option>--Select--</option>';

 					foreach($quantityTitle as $rowTitle){

 						if($row["album_quantity_title_id"] == $rowTitle["id"])
 						{
 							$strs   .= 	'<option value='.$rowTitle["id"].' selected>'.$rowTitle["title"].'</option>';
 						}else{
 							$strs   .= 	'<option value='.$rowTitle["id"].'>'.$rowTitle["title"].'</option>';
 						}

 					}
 					$strs   .= '</select>';
 					$strs   .= '</div>';
 				}else{
 					$strs	.= '<div class="bodytext">&nbsp;&nbsp;</div>';
 					$strs	.= '<div class="floatleft">&nbsp;</div>';
 				}
 				$strs   .= '<div class="floatleft">&nbsp;</div>';

 				if($num !=1){
 					$strs	.= '<div class="floatleft"><a href="javascript:;" onclick="removeEvent(\'my'.($num-1).'Div\')"><img src="'.$global["tpl_url"].'/images/icon_delete.gif" border="0"></a></div>';
 				}
 				$strs	.= '</div>';

 				$strs	.= '<div style="height:8px"><!-- --></div>';
 				$strs   .= 	$objCalendar->DrawCalendarLayer(date('Y'),date('m'),date('d'),$num,array('FCase'=>'','TimeShow'=>'YES') );
 				$strs   .= '</div>';

 				$num = $num + 1;

 			}
 			return array($strs,$num);
 		}

 	}
 	/**
	* Save Basic price details int flyer_data_basic
	* @Author   : Afsal
	* @Created  : 20/Nov/2007
	*/
 	function saveBasicSpecificDatewisePrice($req,$memberid){
 		extract($req);
 		$this->db->update("flyer_data_basic",array("basic_price" => $req["basic_price"],"booking_price" => $req["booking_price"],"basic_price_duration" => $duration,"basic_price_duration_type" => $duration_type) ,"album_id={$req['propid']}");

 	}
 	/**
	* List the Specific Dates Price
	* @Author   : Afsal
	* @Created  : 26/Nov/2007
	*/
 	function getSpecificDatesPriceList($propid){

 		$sql = "SELECT date_format(from_date,'%Y-%m-%d') AS from_date,date_format(to_date,'%Y-%m-%d') AS to_date,priceperc,
				album_id,type FROM property_special_price_specific WHERE album_id =$propid ORDER BY id ASC";
 		$rs = $this->db->get_results($sql,ARRAY_A);
 		return $rs;
 	}
 	/**
	* List the Specific Dates Price with control
	* @Author   : Afsal
	* @Created  : 26/Nov/2007
	*/
 	function listEditPartSpecificPriceDate($qryArray,$objCalendar){


 		if (count($qryArray)){

 			$num = 0;
 			$global["tpl_url"] = SITE_URL."/templates/".$this->config['curr_tpl'];

 			foreach($qryArray as $row){

 				$price = 0;
 				$percentage = 0;

 				if($row["type"] == "pr")
 				$price = $row["priceperc"];
 				else
 				$percentage = $row["priceperc"];

 				$strs  .= '<div id="myp'.$num.'div">';
 				$strs  .= '<div class="divSpc"></div>';
 				$strs  .= '<div class="floatleftwidth120">';
 				$strs  .= '<div class="bodytext" style="text-align:left">From</div>';
 				$strs  .= '<div>';
 				$strs  .= '<input type="text" class="inputelement" name="spFrom[]" id="txtp'.$num.'" value="'.$row["from_date"].'" onClick="javascript:MovetoCallendar(\'p'.$num.'\');" size="15" readonly>';
 				$strs  .= '</div>';
 				$strs  .= '</div>';
 				$strs  .= 	$objCalendar->DrawCalendarLayer(date('Y'),date('m'),date('d'),"p".$num,array('FCase'=>'') );

 				$num	= ($num + 1);

 				$strs  .= '<div class="floatleftwidth120">';
 				$strs  .= '<div class="bodytext" style="text-align:left">To</div>';
 				$strs  .= '<div><input type="text" class="inputelement" name="spTo[]" id="txtp'.$num.'" value="'.$row["to_date"].'" onClick="javascript:MovetoCallendar(\'p'.$num.'\');" size="15" readonly></div>';
 				$strs  .= '</div>';
 				$strs  .= 	$objCalendar->DrawCalendarLayer(date('Y'),date('m'),date('d'),"p".$num,array('FCase'=>'') );

 				$strs  .= '<div class="floatleft">';
 				$strs  .= '<div class="bodytext" style="text-align:left">Price<b>$</b></div>';
 				$strs  .= '<div class="floatleft"><input type="text" class="inputelement" name="spPrice[]" id="spPrice" value="'.$price.'" size="5"></div>';
 				$strs  .= '</div>';

 				$strs  .= '<div class="floatleft">';
 				$strs  .= '<div class="bodytext">&nbsp;</div>';
 				$strs  .= '<div class="bodytext">&nbsp;<b>or</b>&nbsp;</div>';
 				$strs  .= '</div>';

 				$strs  .= '<div class="floatleft" style="text-align:inherit"></div>';
 				$strs  .= '<div class="floatleft">';
 				$strs  .= '<div class="bodytext" style="text-align:left"><b>%</b></div>';
 				$strs  .= '<div class="floatleft"><input type="text" class="inputelement" name="spPerc[]" value="'.$percentage.'" id="spPerc" size="5"></div>';
 				$strs  .= '<div class="floatleft">&nbsp;</div>';
 				if($num !=1){
 					$strs  .= '<div class="floatleft"><a href="javascript:;" onclick="removeEvent(\'myp'.($num-1).'div\',\'1\')"><img src="'.$global['tpl_url'].'/images/icon_delete.gif" border="0"></a></div>';
 				}
 				$strs  .= '</div>';
 				$strs  .= '</div>';

 				$num	= ($num + 1);
 			}

 			return array($strs,$num);
 		}
 	}
 	/**
	* Insert specific dates price in to album_pricing_specific
	* @Author   : Afsal
	* @Created  : 26/Nov/2007
	*/
 	/*
 	function saveSpecialPrice($req){


 	if (count($req['spFrom'])){

 	$this->db->query("DELETE FROM album_pricing_specific WHERE album_id ={$req["propid"]}");

 	for ($i=0;$i<count($req['spFrom']);$i++){

 	if(abs($req["spPrice"][$i]) > 0){
 	$priceperc = $req["spPrice"][$i];
 	$type 	   = "pr";
 	}
 	else{
 	$priceperc = $req["spPerc"][$i];
 	$type 	   = "pe";
 	}

 	$arraySpecific = array("from_date" => $req["spFrom"][$i],"to_date"=>$req["spTo"][$i],
 	"priceperc" => $priceperc,"type" => $type,"album_id" => $req["propid"]);

 	$this->db->insert("album_pricing_specific",$arraySpecific);
 	}
 	}

 	}
 	*/
 	/**
	* Get the different type of pricelist from 
	* @Author   : Afsal
	* @Created  : 26/Nov/2007
	*/
 	function getSpecificPriceList(){

 		$sql = "SELECT id,title FROM album_special_pricing_master WHERE active = 'yes'";
 		$arrSpPrice[]  = $this->db->get_col($sql,0);
 		$arrSpPrice[]  = $this->db->get_col($sql,1);

 		return $arrSpPrice;
 	}
 	/**
	* Insert Specific price to album_pricing table
	* @Author   : Afsal
	* @Created  : 26/Nov/2007
	*/
 	function saveSpecialPrice($req){

 		$this->db->query("DELETE FROM property_special_price WHERE album_id ={$req["propid"]}");

 		if(abs($req["spePrice"]) >0 || abs($req["spePerc"]) >0)
 		{

 			if(abs($req["spePrice"]) > 0){

 				$priceperc = $req["spePrice"];
 				$type	   = "pr";

 			}else{

 				$priceperc = $req["spePerc"];
 				$type	   = "pe";
 			}

 			$arraySpec = array("specific_id" => $req['specific_id'],"priceperc" => $priceperc,
 			"album_id" => $req['propid'],"type" => $type);

 			$this->db->insert("property_special_price",$arraySpec);

 		}
 	}
 	/**
	* For combooxlisting
	* @Author   : Afsal
	* @Created  : 27/Nov/2007
	*/
 	function specifiPriceForCombo(){

 		$sql = "SELECT id,title FROM album_special_pricing_master WHERE active = 'yes'";
 		$rs = $this->db->get_results($sql,ARRAY_A);
 		return $rs;
 	}
 	/**
	* List specific price (Edit Part)
	* @Author   : Afsal
	* @Created  : 27/Nov/2007
	*/
 	function listEditPartSpecificPrice($qryArray,$objCalendar){

 		if (count($qryArray)){

 			$num = 0;
 			$global["tpl_url"] = SITE_URL."/templates/".$this->config['curr_tpl'];

 			$rsSpecicArr = $this->specifiPriceForCombo();

 			foreach($qryArray as $sprow){

 				$price = 0;
 				$percentage = 0;

 				if($sprow["type"] == "pr")
 				$price = $sprow["priceperc"];
 				else
 				$percentage = $sprow["priceperc"];

 				$strs  .= '<div id="mysp'.$num.'Div">';
 				$strs  .= '<div class="divSpc"></div>';
 				$strs  .= '<div class="floatleftwidth120">';
 				$strs  .= '<div class="bodytext">&nbsp;</div>';
 				$strs  .= '<div class="floatleft">';
 				$strs  .= '<select name="specific_id[]" id="combo" disabled>';
 				$strs  .= '<option>--Select--</option>';
 				foreach ($rsSpecicArr as $row){
 					if($row["id"] == $sprow["specific_id"]){
 						$strs  .= '<option value='.$row["id"].' selected>'.$row["title"].'</option>';
 					}else{
 						$strs  .= '<option value='.$row["id"].'>'.$row["title"].'</option>';
 					}
 				}
 				$strs  .= '</select>';
 				$strs  .= '</div>';
 				$strs  .= '</div>';

 				$strs  .= '<div class="floatleftwidth50">';
 				$strs  .= '<div class="bodytext">Price<b>$</b></div>';
 				$strs  .= '<div class="floatleft"><input type="text" class="inputelement" name="spePrice[]" value='.$price.'  size="5"></div>';
 				$strs  .= '</div>';

 				$num	= ($num + 1);

 				$strs  .= '<div class="floatleft">';
 				$strs  .= '<div class="bodytext">&nbsp;</div>';
 				$strs  .= '<div class="bodytext">&nbsp;<b>or</b>&nbsp;</div>';
 				$strs  .= '</div>';

 				$strs  .= '<div class="floatleftwidth80">';
 				$strs  .= '<div class="bodytext"><b>%</b></div>';
 				$strs  .= '<div class="floatleft"><input type="text" class="inputelement" name="spePerc[]" value='.$percentage.' size="5"></div>';
 				$strs  .= '<div class="floatleft">&nbsp;</div>';
 				if($num !=1){
 					$strs  .= '<div class="floatleft"><a href="javascript:;" onclick="removeEvent(\'mysp'.($num-1).'Div\',\'2\')"><img src="'.$global['tpl_url'].'/images/icon_delete.gif" border="0"></a></div>';
 				}
 				$strs  .= '</div>';
 				$strs  .= '</div>';
 			}
 		}
 		return array($strs,$num);

 	}
 	/**
	* List specific price
	* @Author   : Afsal
	* @Created  : 27/Nov/2007
	*/
 	function getPropertySpecialPrice($albumID){
 		if($albumID){
 			$sql = "SELECT * FROM property_special_price WHERE album_id = $albumID ORDER BY id ASC";
 			$rs = $this->db->get_results($sql,ARRAY_A);
 			return $rs;
 		}
 	}
 	/**
	* Insert bid price details to album_bid
	* @Author   : Afsal
	* @Created  : 28/Nov/2007
	*/
 	function saveBidPrice($req){

 		$this->db->query("DELETE FROM album_bid WHERE album_id = {$req['propid']}");

 		$bidArr = array("album_id" => $req["propid"],"minimum_bid" => $req["minimumBid"],
 		"maximum_bid" => $req["maximumBid"],"expdays" => $req["expdays"]);
 		$this->db->insert("album_bid",$bidArr);
 	}
 	/**
	* Get the bid price details
	* @Author   : Afsal
	* @Created  : 28/Nov/2007
	*/
 	function getBidPriceList($albumID){

 		if($albumID > 0){

 			$sql = "SELECT * FROM album_bid WHERE album_id=$albumID";
 			$rs  = $this->db->get_row($sql,ARRAY_A);
 			return $rs;

 		}
 	}

 	/**
	* Get the bid price details
	* @Author   : Aneesh
	* @Created  : 30/Nov/2007
	Update Map Position in album_map_position
	*/
 	function UpdateAlbumPositionMap ($req) {
 		extract ($req);

 		if ($album_ID>0)	{
 			$SQL1	=	"SELECT count(*) as numAlb FROM album_map_position WHERE album_id={$album_ID}";
 			$rs  = $this->db->get_row($SQL1,ARRAY_A);
 			if ($rs['numAlb'] != 0) {	# Update
 				$PosArray=array("lat_lon"=>$latlon,"zoom"=>$currZoom);
 				$this->db->update("album_map_position", $PosArray, "album_id='$album_ID'");
 			} else	{	# Insert
 				$PosArray = array("album_id"=>$album_ID,"lat_lon"=>$latlon,"zoom"=>$currZoom);
 				$this->db->insert("album_map_position", $PosArray);
 			}
 			return true;
 		}
 		return false;
 	}

 	/**
	* Get the bid price details
	* @Author   : Aneesh
	* @Created  : 30/Nov/2007
	Getting Album Position if Exist
	*/
 	function fetchAlbumPositiononMap	($album_ID="")	{
 		if ($album_ID>0)	{
 			$SQL1	=	"SELECT * FROM album_map_position WHERE album_id={$album_ID}";
 			$rs  = $this->db->get_row($SQL1,ARRAY_A);
 			return $rs;
 		} else {
 			return false;
 		}

 	}
 	/**
	* Delete album position from the map
	* @Author   : Aneesh
	* @Created  : 30/Nov/2007
	*Delete Album Position if Exist
	*/
 	function DeleteAlbumPositionMap	($album_ID="")	{
 		if ($album_ID>0)	{
 			$this->db->query("DELETE FROM album_map_position  WHERE  album_id='$album_ID'");
 			return true;
 		}
 	}
 	/**
	* Get the bid price details
	* @Author   : Afsal
	* @Created  : 30/Nov/2007
	*Delete Album Position if Exist
	*/
 	function getAlbumID($flyer_id){

 		$sql = "SELECT album_id FROM flyer_data_basic WHERE flyer_id=$flyer_id";
 		$rs = $this->db->get_row($sql);
 		return $rs["album_id"];

 	}
 	function getFlyerIDByAlbum($album_id){

 		$sql = "SELECT flyer_id FROM flyer_data_basic WHERE album_id=$album_id";
 		$rs = $this->db->get_row($sql,ARRAY_A);
 		return $rs["flyer_id"];

 	}
 	/**
	* Get the bid price details
	* @Author   : Afsal
	* @Created  : 05/Dec/2007
	*Delete Album Position if Exist
	*/
 	function saveBasicQuantity($req){
 		$qtyArry = array("album_id" =>$req["propid"]);
 		//$sql = "";
 	}
 	/**
	* @Author   : Afsal
	* @Created  : 05/Dec/2007
	* Get the each quan
	*/
 	function getQuantityTitle($propid){
 		if($propid > 0){

 			$sql = "SELECT * FROM album_quantity_title WHERE album_id=$propid ORDER BY id ASC";
 			$rs  = $this->db->get_results($sql,ARRAY_A);
 			$titleArray["id"] = $this->db->get_col($sql,0);
 			$titleArray["title"] = $this->db->get_col($sql,2);
 			return array($rs,$titleArray);
 		}
 	}
 	/**
	* @Author   : Afsal
	* @Created  : 07/Dec/2007
	* Update Bid table
	*/
 	function updateBidDisableEnable($propid,$act){

 		if($act == "yes")
 		$type = "no";
 		else
 		$type = "yes";

 		$arrB = array("active" => $type);
 		$this->db->update("album_bid",$arrB,"album_id=$propid");

 		if($type == "yes")
 		setMessage("Bid has been enabled",MSG_SUCCESS);
 		else
 		setMessage("Bid has been disabled",MSG_INFO);
 	}
 	/**
	* @Author   : Afsal
	* @Created  : 10/Dec/2007
	* Get the each quan
	*/
 	function getSpecialPriceDetails($propid){

 		$sql = "SELECT am.title,ap.* FROM album_special_pricing_master AS am INNER JOIN property_special_price ap
		        ON am.id = ap.specific_id AND ap.album_id = $propid";
 		$rs = $this->db->get_results($sql,ARRAY_A);
 		return $rs;
 	}
 	/**
	* @Author   : Afsal
	* @Created  : 10/Dec/2007
	* Get the each quan
	*/
 	function getPropertyBlockQuantityTitle($propid,$memberid){

 		$sql = "SELECT date_format(a.from_date, '%Y-%m-%d' )AS from_date ,date_format(a.to_date,'%Y-%m-%d') AS to_date,
		        a.album_quantity_title_id,a.current_user_id,b.title,color_code FROM property_blocked AS a INNER JOIN album_quantity_title AS b
		        ON a.album_quantity_title_id = b.id AND a.album_id = $propid
				AND a.current_user_id=$memberid ORDER BY a.id ASC";

 		$rs = $this->db->get_results($sql,ARRAY_A);
 		return $rs;
 	}



 	/**
	 * The following method returns the properties associated with a particular user
	 *
	 * @author vimson@newagesmb.com
	 * @param Int $MemberId
	 * @return Array
	 * Modified by Retheesh on 17/Jan/2008
	 * Added a paramet assigned_role which has a default value as 'PROP_BROKER'
	 * Modified by Retheesh on 23/Jan/2008
	 * Modified query to list properties managed by that user
	 * Modified by Retheesh on 09/May/2008
	 * removed managing properties list while assigning to managers
	 * 
	 */
 	function getPropertiesOfUser($MemberId,$pageNo = 0,$limit = 10,$params,$AlbumObj = '',$PhotoObj = '',$AssignedRole='PROP_BROKER')
 	{
 		$UserProperties		=	array();
 		if ($AssignedRole=='PROP_MANAGER')
 		{
 			$Qry1	=	"SELECT T1.*, T4.*,
						T2.form_title AS form_title, 
						T3.first_name AS first_name, 
						T3.last_name AS last_name, 
						T3.username AS username,
						T6.addr_type,T6.address1,T6.city,T6.state,T6.postalcode,
						T7.country_3_code as mem_country,T7.country_id,
						T3.username as mem_username,
						T3.first_name as mem_first_name,
						T3.last_name as mem_last_name    
					FROM flyer_data_basic AS T1 
					LEFT JOIN flyer_form_master AS T2 ON T2.form_id = T1.form_id 
					LEFT JOIN member_master AS T3 ON T3.id = T1.user_id 
					LEFT JOIN member_address as T6 on T1.user_id = T6.user_id and T6.addr_type='master' 
					LEFT JOIN country_master as T7 on T6.country = T7.country_id 
					LEFT JOIN flyer_data_contact AS T4 ON T4.flyer_id = T1.flyer_id
					LEFT JOIN propertyassign_relation T5 ON  (T1.album_id=T5.property_id and T5.assigned_user_id=$MemberId and T5.assigned_role='PROP_MANAGER' and T5.accepted='Y')
					WHERE T1.active='Y' AND (T1.user_id='$MemberId') AND T1.publish ='Y' ORDER BY T1.flyer_id DESC ";

 		}
 		else
 		{

 			$Qry1	=	"SELECT T1.*, T4.*,
						T2.form_title AS form_title, 
						T3.first_name AS first_name, 
						T3.last_name AS last_name, 
						T3.username AS username,
						T5.assigned_role as role,
						T6.addr_type,T6.address1,T6.city,T6.state,T6.postalcode,
						T7.country_3_code as mem_country,T7.country_id,
						T3.username as mem_username,
						T3.first_name as mem_first_name,
						T3.last_name as mem_last_name    
					FROM flyer_data_basic AS T1 
					LEFT JOIN flyer_form_master AS T2 ON T2.form_id = T1.form_id 
					LEFT JOIN member_master AS T3 ON T3.id = T1.user_id 
					LEFT JOIN member_address as T6 on T1.user_id = T6.user_id and T6.addr_type='master' 
					LEFT JOIN country_master as T7 on T6.country = T7.country_id 
					LEFT JOIN flyer_data_contact AS T4 ON T4.flyer_id = T1.flyer_id
					LEFT JOIN propertyassign_relation T5 ON  (T1.album_id=T5.property_id and T5.assigned_user_id=$MemberId and T5.assigned_role='PROP_MANAGER' and T5.accepted='Y')
					WHERE T1.active='Y' AND (T1.user_id='$MemberId' OR T5.assigned_user_id=$MemberId) AND T1.publish ='Y' ORDER BY T1.flyer_id DESC ";
 		}


 		list($Properties,$numpad,$cnt,$limitList) 	= 	$this->db->get_results_pagewise($Qry1, $pageNo, $limit, $params, ARRAY_A, $orderBy);

 		$ArrIndx	=	0;
 		foreach($Properties as $Property) {
 			$UserProperties[$ArrIndx]	=	$Property;
 			$PropertyId					=	$Property['flyer_id'];
 			$AlbumId					=	$Property['album_id'];

 			$Qry2	=	"SELECT
							T1.accepted AS accepted, 
							T1.declined AS declined,
							T2.username AS username, 
							T2.first_name AS first_name, 
							T2.last_name AS last_name 
						FROM propertyassign_relation AS T1
						LEFT JOIN member_master AS T2 ON T2.id = T1.assigned_user_id 
						WHERE property_id = '$AlbumId' AND assigned_role = '$AssignedRole'";
 			$Row2	=	$this->db->get_row($Qry2, ARRAY_A);

 			$Status	=	'NOTASSIGNED';
 			if($Row2['accepted'] == 'N') {
 				$Status									=	'NOTACCEPTED';
 				$UserProperties[$ArrIndx]['username']	=	$Row2['username'];
 				$UserProperties[$ArrIndx]['first_name']	=	$Row2['first_name'];
 				$UserProperties[$ArrIndx]['last_name']	=	$Row2['last_name'];
 			}
 			if($Row2['accepted'] == 'Y') {
 				$Status	=	'ACCEPTED';
 				$UserProperties[$ArrIndx]['username']	=	$Row2['username'];
 				$UserProperties[$ArrIndx]['first_name']	=	$Row2['first_name'];
 				$UserProperties[$ArrIndx]['last_name']	=	$Row2['last_name'];
 			}
 			$UserProperties[$ArrIndx]['STATUS']		=	$Status;

 			$AlbumDetails		=	$AlbumObj->getAlbumDetails($AlbumId);
 			$DefaultImage		=	$AlbumDetails["default_img"];
 			$DefImageExtn		=	$PhotoObj->imgExtension($AlbumDetails["default_img"]);
 			$DefaultImgName		=	$DefaultImage.'_thumb2'.$DefImageExtn;

 			if(!file_exists(SITE_PATH.'/modules/album/photos/thumb/'.$DefaultImgName))
 			$DefaultImgName		=	'';

 			$UserProperties[$ArrIndx]['DefaultImage']	=	$DefaultImgName;
 			$prrate=$AlbumObj->getPropertyRate($AlbumId);
 			$rate_show= $prrate['rate']*20;
 			$UserProperties[$ArrIndx]['prrate']	=	$prrate['rate'];
 			$UserProperties[$ArrIndx]['proprate']	=	$rate_show;
 			$ArrIndx++;
 		}


 		//print_r($UserProperties);
 		return array($UserProperties,$numpad,$cnt,$limitList );
 	}


 	/**
	* This function is for adding properties as favorite item 
	* Author   : Vinoy
	* Created  :15/Jan/2007
	*/		

 	function addFavorite($array)
 	{
 		$type    = $array["type"];
 		$albumid = $array["file_id"] ;
 		$userid  = $array["userid"] ;
 		$sql="SELECT * FROM media_favorites  WHERE type='$type' AND file_id='$albumid' AND userid='$userid'";
 		$rs  = $this->db->get_results($sql);

 		if(count($rs)>0)
 		{
 			return "false";
 		}else{

 			$add = $this->db->insert("media_favorites", $array);
 			return "true";
 		}
 	}
 	/**
	* This function is for selecting the favotite items  
	* Author   : Vinoy
	* Created  :26/Jan/2007
	*/		

 	function selectFavorite($array,$pageNo=0, $limit=10,$params , $output, $orderBy)
 	{

 		include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
 		include_once(FRAMEWORK_PATH."/modules/album/lib/class.photo.php");
 		$album			=	new Album();
 		$photo		=	new Photo();
 		$type    = $array["type"];
 		$albumid = $array["file_id"] ;
 		$userid  = $array["userid"] ;

 		$sql     =    "SELECT f.title,f.description,f.album_id,f.modified_date FROM
							flyer_data_basic AS f INNER JOIN
							media_favorites AS m ON f.album_id = m.file_id AND m.userid ='$userid' AND 
							m.type = 'album' ";

 		list($rs,$numpad)      = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, ARRAY_A, $orderBy);

 		$i=0;
 		foreach ($rs as $res){

 			$rsAlbm = $album->getAlbumDetails($res["album_id"]);
 			$rsExt           =   $photo->imgExtension($rsAlbm["default_img"]);
 			$rs[$i]["img_extension"]=$rsExt;

 			$rs[$i]["default_img"]	=	$rsAlbm["default_img"];

 			$i++;

 		}

 		return array($rs,$numpad);
 		// return array($rsAlbm,$rs);
 	}


 	function removeFavoriteAlbum($propid,$userid)
 	{

 		$Qry1	=	"DELETE FROM media_favorites WHERE file_id='$propid' AND type ='album'";
 		$this->db->query($Qry1);

 	}

 	/**
	* This function is used to get the Stock image names.
	* Author   : Jeffy
	* Created  :11/Jan/2007
	*/				
 	function stockImages()	{
 		$sql = "SELECT * FROM cms_image";
 		$rs = $this->db->get_results($sql,ARRAY_A);
 		return $rs;
 	}

 	/**
	* This function is used to edit the profile photo
	* Author   : Jeffy
	* Created  :14/Jan/2007
	*/		
 	function editPopupPhoto($flyer_id,$dest_name) {
 		$sql		=	"select * from flyer_data_basic where flyer_id='$flyer_id'";
 		$rs 		= 	$this->db->get_row($sql, ARRAY_A);
 		if(count($rs)>0) {
 			$this->db->query("update flyer_data_basic set image='$dest_name' where flyer_id ='$flyer_id'");
 		}
 		return true;
 	}

 	/**
	* This function is used to add gallery photo
	* Author   : Jeffy
	* Created  :14/Jan/2007
	*/		

 	function addPopupgalleryPhoto($flyer_id,$dest_name) {
 		$Qry	=	"INSERT INTO flyer_data_gallary (image_name,flyer_id)
					VALUES ('$dest_name','$flyer_id')";
 		$this->db->query($Qry);
 	}
 	/**
	* This function is for getting the acoomodation and Quantity 
	* Author   : Vinoy
	* Created  :24/Jan/2007
	*/		
 	function getFlyercat($pid)
 	{
 		$sql		=	"SELECT show_accomodation,show_Qty from  flyer_form_master
						 JOIN flyer_data_basic 
						 ON flyer_data_basic.form_id= flyer_form_master.form_id 
						 AND flyer_data_basic.album_id='$pid'";
 		$rs 		= 	$this->db->get_row($sql, ARRAY_A);
 		return $rs;
 	}

 	function getFlyerFormAttributeGroupsAndItems($FormId)
 	{
 		$Groups		=	array();

 		$Qry1	=	"SELECT
						T1.attr_group_id AS attr_group_id,
						T2.group_name AS group_name
					FROM flyer_map_form_option_groups AS T1 
					LEFT JOIN flyer_form_attribute_groups AS T2 ON T2.attr_group_id = T1.attr_group_id 
					WHERE T1.block_id IN (SELECT block_id FROM flyer_form_blocks WHERE form_id = '$FormId')";
 		$Rows1	=	$this->db->get_results($Qry1, ARRAY_A);

 		$GroupIndx	=	0;
 		foreach($Rows1 as $Row1) {
 			$attr_group_id	=	$Row1['attr_group_id'];
 			$group_name		=	$Row1['group_name'];

 			$Qry2	=	"SELECT item_id,item_name FROM flyer_form_attribute_item WHERE attr_group_id = '$attr_group_id' AND flyer_id='0'";
 			$Rows2	=	$this->db->get_results($Qry2, ARRAY_A);

 			$GroupItems		=	array();
 			$ItemIndx		=	0;
 			foreach($Rows2 as $Row2) {
 				$item_id	=	$Row2['item_id'];
 				$item_name	=	$Row2['item_name'];

 				$GroupItems[$ItemIndx]['ItemId']		=	$item_id;
 				$GroupItems[$ItemIndx]['ItemName']		=	$item_name;

 				$ItemIndx++;
 			}




 			$Groups[$GroupIndx]['GroupId']		=	$attr_group_id;
 			$Groups[$GroupIndx]['GroupName']	=	$group_name;
 			$Groups[$GroupIndx]['Items']		=	$GroupItems;
 			$GroupIndx++;

 			/* $GroupsID[$GroupIndx]['GroupId']		=	$attr_group_id;
 			$GroupsName[$GroupIndx]['GroupName']	=	$group_name;
 			$GroupsItems[$GroupIndx]['Items']		=	$GroupItems;
 			$GroupIndx++;*/

 		}
 		//return array($GroupsName,$GroupsItems);

 		return $Groups;
 	}

 	/**
	* This function is for listng the attribute items of particular property type
	* Author   : Vinoy
	* Created  :23/Jan/2007
	*/		
 	function getGroupItems($group)
 	{

 		foreach($group as $groups)
 		{
 			$groupname= $groups["GroupName"];

 		}
 		if($groupname!='')
 		{
 			$strs	.='<div  class="border bodytext"  align="center" >';


 			foreach($group as $groups)
 			{

 				$strs	.='<div align="left"><strong>'.$groups["GroupName"].'</strong></div>';
 				$strs	.='<div id="AmtTypeCont" class="border bodytext" style="width:98%;text-align:center" align="center">';

 				foreach($groups['Items'] as $newgroups)
 				{
 					// print_r($newgroups);
 					$strs	.='<div style="float:left;width:25%;height:25px;text-align:left;" align="left">';
 					$strs	.='<div><input name="amentyGrp[]" id="amentyGrp" type="checkbox" value="'.$newgroups["ItemId"].'">'.$newgroups["ItemName"].'</div>';
 					$strs	.='</div>';

 				}

 				$strs	.='<div style="clear:both"></div>';
 				$strs	.='</div>';
 				$strs	.='<div style="height:5px"><!-- --></div>';

 			}
 			$strs	.='</div>';
 		}
 		return $strs;


 	}
 	/**
	* This function is for listng the attribute items of particular property type in the search listing page
	* Author   : Vinoy
	* Created  :29/Jan/2007
	*/		
 	function getPropertyItems($group,$req)
 	{
 		$checkval =0;
 		$strs = "";
 		foreach($group as $groups)	{
 			$strs .= "<div align=\"left\" class=\"searchBigLeftTab grayboltext\" onClick=\"upDownDivContent(this,'vd_{$groups['GroupId']}')\"><b>&nbsp;".$groups["GroupName"]."</b></div>";
 			$strs .='<div id="vd_'.$groups["GroupId"].'" style="width:100%;text-align:center;">';
 			foreach($groups['Items'] as $newgroups){
 				$checkval=0;
 				foreach ($req as $items){

 					if ($newgroups["ItemId"] ==$items){
 						$checkval=1;
 					}
 				}

 				$strs	.='<div style="height:25px;text-align:left;" align="left"><input name="amentyGrp[]" id="amentyGrp" type="checkbox" value="'.$newgroups["ItemId"].'"  onClick="sortbycase();" ';
 				if($checkval ==1)  {
 					$strs	.='checked';
 				}
 				$strs	.=' >'.$newgroups["ItemName"].'</div>';

 			}

 			$strs .='</div>';
 			$strs	.='<div style="clear:both"></div>';

 		}
 		$strs	.='<div style="clear:both"></div>';
 		return $strs;

 	}


 	/**
	 * The following method returns the combo box for state 
	 *
	 * @param String $CountryName
	 * @param String $SelectedStateName
	 * 
	 */
 	function getStateComboForFlyerForm($CountryName, $SelectedStateName = '')
 	{
 		$OutputElement	=	'';

 		$Qry1		=	"SELECT
							T1.name AS StateName, T2.country_name AS CountryName 
						FROM state_code AS T1 
						LEFT JOIN country_master AS T2 ON  T1.country_id = T2.country_id 
						WHERE T2.country_name = '$CountryName'";
 		$States	=	$this->db->get_results($Qry1, ARRAY_A);

 		$StateCount	=	count($States);
 		if($StateCount == 0)
 		$OutputElement	=	'<input type="text" name="location_state" size="17" value="'.$SelectedStateName.'">';

 		if($StateCount > 0) {
 			$OutputElement	=	'<select name="location_state" style="width:120px;">';
 			foreach($States as $State) {
 				if($State['StateName'] == $SelectedStateName)
 				$OutputElement	.=	'<option value="'.$State['StateName'].'" selected>'.$State['StateName'].'</option>';
 				else
 				$OutputElement	.=	'<option value="'.$State['StateName'].'">'.$State['StateName'].'</option>';
 			}
 			$OutputElement	.=	'</select>';
 		}

 		return $OutputElement;
 	}
 	/*
 	Created:Afsal Ismail
 	Date :04-02-2008
 	Feature:print the
 	*/
 	function printBidAuctionField($divid,$objCalendar,$type="ajax",$rs="",$disable=false){

 		$strHtm  = '<div class="floatleft">';
 		$strHtm .= '<div class="bodytext" style="text-align:left;">Starting Bid$</div>';

 		if($disable == true)
 		$strHtm .= '<div><input type="text" class="inputelement" name="minimumBid[]" value="'.$rs["start_bid"].'" id="minimumBid'.$divid.'" size="12" disabled></div>';
 		else
 		$strHtm .= '<div><input type="text" class="inputelement" name="minimumBid[]" value="'.$rs["start_bid"].'" id="minimumBid'.$divid.'" size="12"></div>';

 		$strHtm .= '</div>';
 		$strHtm .= '<div class="floatleft">&nbsp;</div>';
 		$strHtm .= '<div class="floatleft">';
 		$strHtm .= '<div class="bodytext" style="text-align:left;">Reserve Bid$</div>';

 		if($disable == true)
 		$strHtm .= '<div><input type="text" class="inputelement" name="reserve_bid[]" value="'.$rs["reserve_bid"].'" size="12" id="reserve_bid'.$divid.'" disabled></div>';
 		else
 		$strHtm .= '<div><input type="text" class="inputelement" name="reserve_bid[]" value="'.$rs["reserve_bid"].'" id="reserve_bid'.$divid.'"  size="12"></div>';

 		$strHtm .= '</div>';
 		$strHtm .= '<div class="floatleft">&nbsp;</div>';
 		$strHtm .= '<div class="floatleft">';
 		$strHtm .= '<div class="bodytext" style="text-align:left;">Expires Date</div>';

 		$auction_end_date = $this->revertToPhpFormat($rs["auction_ends"]);

 		if($disable == true)
 		$strHtm .= '<div><input type="text" class="inputelement" name="fixed_bid_expires[]" id="txtauct'.$divid.'" value="'.$auction_end_date.'" size="12" onFocus="javascript:MovetoCallendar(\'auct'.$divid.'\');" onClick="javascript:MovetoCallendar(\'auct'.$divid.'\');" disabled readonly></div>';
 		else
 		$strHtm .= '<div><input type="text" class="inputelement" name="fixed_bid_expires[]" id="txtauct'.$divid.'" value="'.$auction_end_date.'" size="12" onFocus="javascript:MovetoCallendar(\'auct'.$divid.'\');" onClick="javascript:MovetoCallendar(\'auct'.$divid.'\');" readonly></div>';

 		$strHtm .= '</div>';
 		$strHtm .= '<div class="sepertor5px"><!-- --></div>';
 		$strHtm .= 	$objCalendar->DrawCalendarLayer(date('Y'),date('m'),date('d'),"auct".$divid,array('FCase'=>'') );

 		if($type == "ajax")
 		return $strHtm."|".$divid;
 		else
 		return $strHtm;
 	}
 	/*
 	Created:Afsal Ismail
 	Date :05-02-2008
 	Feature:Insert the Floating price details
 	*/
 	function saveFloatingPricing($req,$propid){

 		if($propid > 0){

 			$arrayFloat = array("album_id" =>$propid,"price" => $req["basic_price"],"duration" => $req["duration_type"],
 			"duration" => $req["duration"],"unit" => $req["duration_type"],
 			"booking_price" => $req["booking_price"],"default_val" => "Y","min_duration" => $req["min_duration"],
 			"min_units" => $req["min_units"],"modified_date" => date("Y-m-d"));

 			if($req["float_price_id"] >0){

 				$this->db->update("property_pricing",$arrayFloat,"id={$req["float_price_id"]}");

 			}else{

 				$this->db->insert("property_pricing",$arrayFloat);
 			}
 		}

 	}
 	/*
 	Created:Afsal Ismail
 	Date :11-02-2008
 	Feature:Insert the Floating price details
 	*/
 	function fixedCalendarRates($req,$propid){


 		if(count($req) && $propid !=""){

 			if(trim($req["bidEnable"]) == "1" || trim($req["bidEnable"]) == 1){

 				$req["b_id"] = $this->saveBidFromCalendar($req,$propid);


 			}

 			if(trim($req["b_id"]) > 0){

 				$arryFixed = array("album_id" => $propid,"price" => $req["fixed_price"],"duration" => $req["fixed_duration"],
 				"unit" => $req["fixed_unit"],"start_date" => $req["start_date"],
 				"rental_end_date" => $req["rental_end_date"],"color_code" => $req["color_code"],"modified_date" =>date("Y-m-d"));

 				return $this->db->update("property_pricing",$arryFixed,"id={$req["b_id"]}");

 			}else{

 				$arryFixed = array("album_id" => $propid,"price" => $req["fixed_price"],"duration" => $req["fixed_duration"],
 				"unit" => $req["fixed_unit"],"start_date" => $req["start_date"],
 				"rental_end_date" => $req["rental_end_date"],"color_code" => $req["color_code"],"modified_date" =>date("Y-m-d"));

 				return $this->db->insert("property_pricing",$arryFixed);

 			}

 		}
 	}
 	/*
 	Created:Afsal Ismail
 	Date :11-02-2008
 	Feature:Insert the Floating price details
 	*/
 	function printFixedCalendarRateBlock($propid,$global_tpl,$objCalendar,$objEvents){

 		if($propid){

 			$SQL = "SELECT p.*,day(p.start_date) AS startDate,day(p.rental_end_date) AS endDate,start_bid,reserve_bid FROM property_pricing AS p
					WHERE album_id =$propid AND default_val = 'N' ORDER BY id DESC";

 			$rs = $this->db->get_results($SQL,ARRAY_A);

 			//print "COUNT :".count($rs);
 			if(count($rs)){
 				$i=1;
 				foreach ($rs as $row){

 					$strHtm .= '<div id="my'.$i.'Div" style="width:100%;">';
 					$strHtm .= '<div style="border:1px solid #b5b5b6;background-color:#F4F2F0;" id="bgc'.$i.'">';
 					$strHtm .= '<div class="floatright" style="text-align:right;"><a href="javascript:void(0);" onClick="javascript:removeEvent(\'my'.$i.'Div\','.$row["id"].')"><img src="'.$global_tpl.'/images/delete_price.jpg" border="0"></a></div>';
 					$strHtm .= '<div class="divSpc"><!-- --></div>';
 					$strHtm .= '<div style="padding:5px;">';
 					if($row["auction"] == "N"){

 						$strHtm .= '<div class="label">Price/Duration :&nbsp;<span class="bodytext"><input type="text" class="inputelement" name="fixed_price_rate[]" id="fixed_price_rate'.$i.'" value="'.$row["price"].'" size="10"><b>&nbsp;$ For '.$row["duration"].' '.$row["unit"].'</b></span></div>';

 					}else{

 						$strHtm .= '<div><input type="hidden" class="inputelement" name="fixed_price_rate[]" id="fixed_price_rate'.$i.'" value="'.$row["price"].'" size="10"></div>';
 					}
 					$strHtm .= '<div class="floatleft" id="fixedLb'.$i.'"></div>';
 					$strHtm .= '<div class="sepertor5px"><!-- --></div>';
 					$strHtm .= '<div class="floatleft"><span class="bodytext"><b>NUMBER OF BLOCKS</b></span></div>';
 					$strHtm .= '<div class="floatleft"><span class="bodytext">&nbsp;&nbsp;&nbsp;<b>START DATE</b></span></div>';
 					$strHtm .= '<div class="floatleft">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></div>';
 					$strHtm .= '<div class="floatleft"><span class="bodytext">&nbsp;<b>RENTAL END DATE</b></span></div>';
 					$strHtm .= '<div style="clear:both;"><!-- --></div>';

 					if($row["auction"] == "N"){
 						$strHtm .= '<div style="float:left;width:5%;cursor:pointer;font-size:11px;" align="center" id="plusminusPriceDiv_'.$row["id"].'" onclick="javascript:showPlusBlockedCont_Price('.$row["id"].');">[+]</div>';
 					}else{
 						$strHtm .= '<div style="float:left;width:5%;cursor:pointer;font-size:11px;" align="center" id="plusminusPriceDiv_'.$row["id"].'">[-]</div>';
 					}
 					$strHtm .= '<div class="floatleft" style="margin-left:70px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>';

 					if($row["auction"] == "Y")
 					$strHtm .= '<div class="floatleft"><span class="bodytext">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.date("j, M  Y",strtotime($row["start_date"])).'</span><input type="hidden" name="start_date[]" value="'.$row["start_date"].'" id="start_date'.$i.'"></div>';
 					else
 					$strHtm .= '<div class="floatleft"><span class="bodytext">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.date("j, M  Y",strtotime($row["start_date"])).'</span><input type="hidden" name="start_date[]" value="'.$row["start_date"].'" id="start_date'.$i.'" disabled></div>';

 					$strHtm .= '<div class="floatleft">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></div>';
 					$strHtm .= '<div class="floatleft"><span class="bodytext">&nbsp;&nbsp;'.date("j, M  Y",strtotime($row["rental_end_date"])).'</span></div>';
 					$strHtm .= '<div class="sepertor5px"><!-- --></div>';

 					$BLOCK = $objEvents->checkBlockDatesDetails($row["start_date"],$row["rental_end_date"],$row["unit"],$row["duration"],1);
 					
 					$j=1;
 					$strHtm .= '<div id="splitBlockPriceDiv_'.$row["id"].'" style="display:none">';

 					foreach ($BLOCK as $rowB){

 						$strHtm .= '<div class="bodytext" align="left" style="border:1px solid #999999;height:20px;">';
 						$strHtm .= '<div style="float:left;" align="center">'.$j.' ]&nbsp;</div>';
 						$strHtm .= '<div style="float:left;" align="left">'. $rowB.'</div>';
 						$strHtm .= '<div style="clear:both"></div>';
 						$strHtm .= '</div>';
 						$strHtm .= '<div style="height:3px"><!-- --></div>';
 						$j++;
 					}

 					$strHtm .= '</div>';
 					//{/foreach}
 					$strHtm .= '<div class="sepertor5px"><!-- --></div>';

 					if($row["auction"] == "Y"){

 						$strHtm .= '<div class="floatleft" id="auimg'.$i.'"><a href="javascript:void(0);" onClick="hideAuctionField('.$i.',\'Y\')"><img src="'.$global_tpl.'/images/bidOn.gif" border="0" title="Enable"></a></div>';
 						$strHtm .= '<div class="floatleft" id="aulbl'.$i.'"><span class="bodytext">Auction Enabled</span></div>';
 						$strHtm .= '<div class="sepertor5px"><!-- --></div>';
 						$strHtm .= '<div class="floatleft"><span id="lodaut'.$i.'" style="display:none;"><img src="'.$global_tpl.'/images/loading16.gif"></span></div>';
 						$strHtm .= '<div class="sepertor5px"><!-- --></div>';
 						$strHtm .= '<div class="floatleft"><input name="auction_bid[]" type="hidden" value="N"/><input name="st_duration[]" type="hidden" class="inputelement" id="st_duration'.$i.'" size="10" value="'.$row["duration"].'"/></div>';
 						$strHtm .= '<div class="sepertor5px"><!-- --></div>';
 						$strHtm .= '<div id="my'.$i.'Bid">'.$this->printBidAuctionField($i,$objCalendar,"",$row).'</div>';

 					}elseif($row["auction"] == "N" && $row["start_bid"] > 0  &&  $row["reserve_bid"] > 0){

 						$strHtm .= '<div class="floatleft" id="auimg'.$i.'"><a href="javascript:void(0);" onClick="enableAuction('.$i.',\'Y\')"><img src="'.$global_tpl.'/images/bidOff.gif" border="0" title="Enable"></a></div>';
 						$strHtm .= '<div class="floatleft" id="aulbl'.$i.'"><span class="bodytext">Auction Disabled</span></div>';
 						$strHtm .= '<div class="sepertor5px"><!-- --></div>';
 						$strHtm .= '<div class="floatleft"><span id="lodaut'.$i.'" style="display:none;"><img src="'.$global_tpl.'/images/loading16.gif"></span></div>';
 						$strHtm .= '<div class="sepertor5px"><!-- --></div>';
 						$strHtm .= '<div class="floatleft"><input name="auction_bid[]" type="hidden" value="N"/><input name="st_duration[]" type="hidden" class="inputelement" id="st_duration'.$i.'" size="10" value="'.$row["duration"].'"/></div>';
 						$strHtm .= '<div class="sepertor5px"><!-- --></div>';
 						$strHtm .= '<div id="my'.$i.'Bid">'.$this->printBidAuctionField($i,$objCalendar,"",$row,true).'</div>';

 					}else{

 						$strHtm .= '<div class="floatleft" id="auimg'.$i.'"><a href="javascript:void(0);" onClick="printAuctionField('.$i.')"><img src="'.$global_tpl.'/images/bidOff.gif" border="0" title="Enable"></a></div>';
 						$strHtm .= '<div class="floatleft" id="aulbl'.$i.'"><span class="bodytext">Auction Disabled</span></div>';
 						$strHtm .= '<div class="sepertor5px"><!-- --></div>';
 						$strHtm .= '<div class="floatleft"><span id="lodaut'.$i.'" style="display:none;"><img src="'.$global_tpl.'/images/loading16.gif"></span></div>';
 						$strHtm .= '<div class="floatleft"><input name="auction_bid[]" type="hidden" value="N"/><input name="st_duration[]" type="hidden" class="inputelement" id="st_duration'.$i.'" size="10" value="'.$row["duration"].'"/></div>';
 						$strHtm .= '<div class="sepertor5px"><!-- --></div>';
 						$strHtm .= '<div id="my'.$i.'Bid"></div></div>';
 						
 						

 					}

 					$strHtm .= '<div class="sepertor5px"><!-- --></div>';

 					$strHtm .= '<div>';
 					$strHtm .= '<div id="rgb'.$i.'_dragOptDiv" style="float:right;height:20px;width:25px;cursor:pointer;background-color:'.$row["color_code"].'" onClick="setBlckID('.$i.');setColorPicker_dragOpt(\'rgb'.$i.'_dragOpt\','.$i.',\'bgc'.$i.'\');" title="Select Color"></div>';
 					$strHtm .= '<input type="hidden" name="rgb'.$i.'_dragOpt" id="rgb'.$i.'_dragOpt" value="'.$row["color_code"].'" readonly>';
 					if($row["auction"] == "Y")
 					$strHtm .= '<input type="hidden" name="block_id[]" id="block_id'.$i.'" value="'.$row["id"].'">';
 					else
 					$strHtm .= '<input type="hidden" name="block_id[]" id="block_id'.$i.'" value="'.$row["id"].'" disabled>';

 					$strHtm .= '<input type="hidden" name="price_id[]" id="price_id'.$i.'" value="'.$row["id"].'">';
 					
 					$strHtm .= '<div style="clear:both"></div>';

 					$strHtm .= '</div>';
 					$strHtm .= '<div class="sepertor5px"><!-- --></div>';
 					$strHtm .= '</div>';
 					$strHtm .= '<div class="sepertor5px"><!-- --></div>';
 					$strHtm .= '</div>';
 					
 					

 					$i++;

 				}//end foreach
 				return  array($strHtm,$rs,$i);
 			}// end if

 		}//end if

 	}//end function

 	/**
      * Album Price Calculation
      * Author   : Aneesh
      * Created  : 14/Feb/2008
      * Modified : 4/Feb/2008 By Aneesh
      * Price Will vary against check-In and check-Out
    */

 	function albumPriceCalculation($album_id="",$check_in="",$check_out="") {

 		/* Make sure Album Id Exist */
 		if ( trim($album_id) == "" )
 		return false;

 		/* make sure Check_in date is Less than Check_out date */
 		if ( strtotime($check_in) > strtotime($check_out)  )
 		return false;


 		/* Count of Days */
 		$TotDaysDurArray = get_time_difference($check_in,$check_out);
 		$TotDaysDuration = $TotDaysDurArray['days'];


 		/* Start Free Floating Price*/
 		$SQL		=	"SELECT * FROM property_pricing WHERE album_id = '{$album_id}' AND default_val = 'Y'";
 		$rs 		= 	$this->db->get_row($SQL, ARRAY_A);

 		$FloatDuration	=	$rs['duration'];
 		$FloatUnit		=	$rs['unit'];
 		$FloatPrice		=	$rs['price'];
 		$FloatBooking	=	$rs['booking_price'];
 		/* End Free Floating Price*/




 		/* Strat Fixed Rates Price */
 		$SQL 		=   "SELECT * FROM property_pricing WHERE
						 ((album_id = $album_id AND default_val='N') AND(
						 
								(start_date between  '$check_in' AND '$check_out')  
								OR 
								(rental_end_date between '$check_in' AND '$check_out') 
							))
							OR
							
							((album_id = $album_id AND default_val='N') AND
							(
								('$check_in' between  start_date AND rental_end_date)  
								OR 
								('$check_out' between start_date AND rental_end_date) 
							))    ORDER BY start_date";

 		$rs 		= 	$this->db->get_results($SQL, ARRAY_A);


 		$blockTotalDuration = 0;
 		$blockTotalPrice    = 0;


 		foreach ($rs as $rsFixed) {

 			$blockTotArray = get_time_difference($rsFixed['start_date'],$rsFixed['rental_end_date']);
 			$blockTotDays  = $blockTotArray['days'];



 			$blockPrice    = $rsFixed['price'];
 			$blockUnit	   = $rsFixed['unit'];

 			$blockDuration = $rsFixed['duration'];

 			if ($blockUnit == 'Week'){
 				$nextDay		=	date("Y-m-d",strtotime("+$blockDuration week",strtotime( $rsFixed['start_date'] )));
 				$weekArr		=   get_time_difference($rsFixed['start_date'],$nextDay);
 				$blockDuration  =   $weekArr['days'];
 			}elseif ($blockUnit == 'Month') {
 				$nextDay		=	date("Y-m-d",strtotime("+$blockDuration month",strtotime( $rsFixed['start_date'] )));
 				$monthArr		=   get_time_difference($rsFixed['start_date'],$nextDay);
 				$blockDuration  =   $monthArr['days'];
 			}elseif ($blockUnit == 'Year') {
 				$nextDay		=	date("Y-m-d",strtotime("+$blockDuration year",strtotime( $rsFixed['start_date'] )));
 				$yearArr		=   get_time_difference($rsFixed['start_date'],$nextDay);
 				$blockDuration  =   $yearArr['days'];
 			}


 			if ($blockTotDays <=  $blockDuration) {    # Block Minimum Duration WITH Total Block Duration
 				$blockTotalPrice 	=	$blockTotalPrice 	+ $blockPrice;

 				$blockTotalDuration	=	$blockTotalDuration	+ $blockTotDays;
 			} else {
 				$divisionVal 		= 	intval($blockTotDays/$blockDuration) + 1;
 				$blockTotalPrice 	=	$blockTotalPrice 	+ ($blockPrice*$divisionVal);
 				$blockTotalDuration	=	$blockTotalDuration	+ $blockTotDays;
 			}
 		}
 		/* End Fixed Rates Price*/


 		/* Begin Adding Price to Free Floating Dates */
 		$finalFreeFloatDays	=	$TotDaysDuration - $blockTotalDuration; // Total Days - Count of All Fixed Dates
 		if ($finalFreeFloatDays <= $FloatDuration) {
 			$blockTotalPrice 	=	$blockTotalPrice 	+ $FloatPrice;
 			$blockTotalDuration	=	$blockTotalDuration	+ $finalFreeFloatDays;
 		} else {
 			$divisionVal 		= 	intval($finalFreeFloatDays/$FloatDuration) + 1;
 			$blockTotalPrice 	=	$blockTotalPrice 	+ ($FloatPrice*$divisionVal);
 			$blockTotalDuration	=	$blockTotalDuration	+ $finalFreeFloatDays;
 		}
 		/* End Adding Price to Free Floating Dates */



 		// print $blockTotalPrice;

 		/*   Average Price = (Total Price + Booking price)/2      */
 		$TotalAveragePrice	=	0;
 		//print $blockTotalPrice;
 		//$TotalAveragePrice	=   round(($blockTotalPrice +  $FloatBooking) / $TotDaysDuration,2);

 		$TotalAveragePrice	=   round(($blockTotalPrice +  $FloatBooking),2);
 		/*   Average Price   */


 		return $TotalAveragePrice;
 		//return $blockTotalPrice;

 	}

 	/* End Function */

 	/**
      * Album Minimum
      * Author   : Aneesh
      * Created  : 14/Feb/2008
      * Modified : 4/Feb/2008 By Aneesh
    */

 	function albumMinMaxPrice ($album_id="") {

 		/* Make sure Album Id Exist */
 		if ( trim($album_id) == "" )
 		return false;

 		$SQL = "SELECT MAX(price) AS max_price,MIN(price) AS min_price FROM property_pricing where album_id='{$album_id}' GROUP BY album_id";
 		$rs 		= 	$this->db->get_row($SQL, ARRAY_A);

 		if ($rs)
 		return $rs['min_price'] . "-" . $rs['max_price'];

 	}

 	/**
      * Album Minimum
      * Author   : Aneesh
      * Created  : 21/Feb/2008
      * Modified : 21/Feb/2008 By Afsal
      * Return the flating price details
    */
 	function getFlatingPriceResults($propid){

 		if($propid > 0){

 			$SQL = "SELECT * FROM property_pricing WHERE album_id=$propid AND default_val='Y'";

 			$rs  = $this->db->get_row($SQL,ARRAY_A);
 			return $rs;
 		}

 	}
 	/**
	 * Save minimum booking length and maximum Booking length
	 * Author :Afsal
	 * Created:21/Feb/2008
	 * Modified :21/Feb/2008
	 */
 	function saveMinimumMaximumBookLength($propid,$minimum_booking_days,$maximum_booking_days){

 		if($propid > 0){

 			$array = array("minimum_booking_days" => $minimum_booking_days,"maximum_booking_days" => $maximum_booking_days);
 			$this->db->update("flyer_data_basic",$array,"album_id=$propid");

 		}
 	}
 	/**
	 * Save Bid details
	 * Author :Afsal
	 * Created:22/Feb/2008
	 * Modified :22/Feb/2008
	 */
 	function updateBid($propid,$request){

 		if($propid > 0){



 			if(count($request)){

 				for($i=0;$i<count($request['minimumBid']);$i++){

 					$auction_end_dat = $this->convertToMySqlFormat($request["fixed_bid_expires"][$i]);
 					$array = array("start_bid" => $request["minimumBid"][$i],"reserve_bid"=>$request["reserve_bid"][$i],
 					"auction_ends" => $auction_end_dat,"id" => $request["block_id"][$i],"auction" => "Y");

 					$this->db->update("property_pricing",$array,"id={$request["block_id"][$i]} AND album_id=$propid");

 				}// end foreach
 			}// end if
 		}//end if
 	}

 	function deleteFixedBlock($block_id){

 		if($block_id >0){

 			$SQL = "DELETE FROM property_pricing WHERE id=$block_id";
 			$this->db->query($SQL);
 			return "deleted";

 		}
 	}


 	/* Function Get Fixed and Flat Pricing */

 	function getFixedFlatResults ($album_id="",$flag="N",$user_id="") {
 		if ( trim($album_id) ){
 			//$qry	=	"SELECT *,date_format(start_date, '%d %M %Y' ) AS start_date_format,date_format(rental_end_date, '%d %M %Y' ) AS rental_end_date_format,date_format(auction_ends, '%d %M %Y')AS auctionFormat_date FROM property_pricing WHERE default_val='$flag' ORDER BY start_date";
 			$qry	=	"SELECT *,date_format(start_date, '%d %M %Y' ) AS start_date_format,date_format(rental_end_date, '%d %M %Y' ) AS rental_end_date_format,date_format(auction_ends, '%d %M %Y')AS auctionFormat_date FROM property_pricing WHERE default_val='$flag' AND album_id = '$album_id' ORDER BY start_date";
 			$rs	=	$this->db->get_results($qry,ARRAY_A);

 			$count	=	count($rs);
 			if($count>0) {

 				/* Creating Blocks */
 				if ($flag == "N") {
 					include_once(FRAMEWORK_PATH."/modules/callendar/lib/class.calendarevents.php");
 					$cevtObj = new CalendarEvents();
 					foreach ($rs as $k=>$rows){
 						$rs[$k]["Blocks"] =  $cevtObj->checkBlockDatesDetails($rows['start_date'],$rows['rental_end_date'],$rows['unit'],$rows['duration'],1);
 						$rs[$k]["max_bid"] = $this->getMaximumBid($rs[$k]["id"]);
 						if ($user_id)
 						{
 							$last_bid_det = $this->getLastBid($user_id,$rs[$k]["id"]);
 							$rs[$k]["last_bid"] = $last_bid_det['bid_amount'];
 							$rs[$k]["last_bid_det"] = $last_bid_det;
 						}
 						if ($rs[$k]["auction"]=="Y")
 						{
 							$auction_stat = $this->getAuctionStatus($rs[$k]['id']);
 							$rs[$k]['select_bid'] = $auction_stat['bid_id'];
 							$sel_bid = $this->selectedBidDetails($user_id,$rs[$k]['id']);


 							if ($rs[$k]['auction_close']==1)
 							{
 								$rs[$k]['auction_closed'] = "Y";
 								$rs[$k]['auction_close_log'] = $this->MOD_VARIABLES['MOD_MSG']['MSG_BID_DT_BUYER_WAIT'];
 							}
 							elseif(date("Y-m-d H:i:s")>$rs[$k]["auction_ends"])
 							{
 								$rs[$k]["auction_closed"] = "Y";
 								$rs[$k]["auction_close_log"] = $this->MOD_VARIABLES['MOD_MSG']['MSG_BID_DT_CLOSE'];
 							}

 							if ($sel_bid['status'] =="Y")
 							{
 								$rs[$k]['selected_bid_user']= "Y";
 								$rs[$k]['auction_close_log'] = $this->MOD_VARIABLES['MOD_MSG']['MSG_BUYER_SELECTED'];
 							}
 							elseif ($sel_bid['status'] =="R")
 							{
 								$rs[$k]['auction_close_log'] = $this->MOD_VARIABLES['MOD_MSG']['MSG_BUYER_REJECT'];
 							}

 						}
 					}
 				}

 				//print_r($rs);
 				/* Creating Blocks */
 				return $rs;
 			}
 		}
 	}
 	/**
	 * GET the different blocks
	 * Author :Afsal
	 * Created:26/Feb/2008
	 * Modified :26/Feb/2008
	 */
 	function getFixedPriceBlocks($propid,$color_code){

 		if($propid > 0 && trim($color_code) != ""){

 			$SQL = "SELECT start_date,rental_end_date,price,duration FROM property_pricing WHERE album_id=$propid AND color_code=$color_code";
 			$rs = $this->db->get_results($SQL,ARRAY_A);
 			return $rs;

 		}
 	}
 	/**
	 * GET the different all color under a property for loading purppose when the step 3 page showing
	 * Author :Afsal
	 * Created:27/Feb/2008
	 * Modified :27/Feb/2008
	 */
 	function getAllColor($propid){

 		if($propid > 0){

 			$SQL = "SELECT	color_code FROM property_pricing WHERE album_id =$propid AND default_val='N'";
 			$rs = $this->db->get_results($SQL,ARRAY_A);
 			$k = 0;

 			if(count($rs)){

 				foreach ($rs as $row) {
 					$selColor[$k]	   = $row["color_code"];
 					$k++;
 				}
 				$extBlckColor = $this->exterNalBlcokColor();

 				$resultsArray = array_merge($selColor,$extBlckColor);
 				$selColor =	implode(",",$resultsArray);

 				return $selColor;
 			}
 		}

 	}
 	/**
	 * GET the External colors
	 * Author :Afsal
	 * Created:28/Feb/2008
	 * Modified :28/Feb/2008
	 */
 	function exterNalBlcokColor(){

 		$arryBlckColor = array();
 		$arryBlckColor = array("#FF0000");
 		return $arryBlckColor;
 	}
 	/**
	 * Delete property blocked dates
	 * Author :Afsal
	 * Created:29/Feb/2008
	 * Modified :29/Feb/2008
	 */
 	function deletePropertyBlockedDate($id){

 		if($id > 0){

 			$SQL = "DELETE FROM property_blocked WHERE id=$id";
 			$this->db->query($SQL);
 			return "deleted";
 		}
 	}
 	/**
	 * Display the blocked property details
	 * Author :Afsal
	 * Created:29/Feb/2008
	 * Modified :29/Feb/2008
	 */
 	function printBlockedPropertyDate($prop_id,$global_tpl){

 		$rs = $this->getPropertyBlockQuantity($prop_id,$_SESSION['memberid']);
 		if(count($rs)){
 			$k =1;
 			foreach ($rs as $row){


 				$strHtm .= '<div class="bodytext" align="left" style="border:1px solid #999999">';
 				$strHtm .= '<div style="float:left;width:10%" align="center">'.$k.'</div>';
 				$strHtm .= '<div style="float:left;width:30%" align="left">'.date("j, F  Y",strtotime($row["from_date"])).'</div>';
 				$strHtm .= '<div style="float:left;width:30%" align="left">'.date("j, F  Y",strtotime($row["to_date"])).'</div>';
 				$strHtm .= '<div style="float:left;width:10%;background-color:'.$row["color_code"].'" align="right">&nbsp;</div>';
 				$strHtm .= '<div style="float:left;width:15%;" align="right"><a href="javascript:void(0);" onClick="javascript:deletePropertyBlockedDate('.$row["id"].')"><img src="'.$global_tpl.'/images/icon.gif" border="0"></a></div>';
 				$strHtm .= '<div style="clear:both"></div>';
 				$strHtm .= '</div>';
 				$strHtm .= '<div style="height:3px"><!-- --></div>';
 				$k++;
 			}

 			return $strHtm;
 		}
 	}
 	function disableAuction($id,$auct){

 		if($id > 0){

 			$arrayAuction = array("auction" => "$auct");
 			$this->db->update("property_pricing",$arrayAuction,"id =$id");
 			return "disabled";

 		}

 	}


 	/**
	 * The following method checks whether the flyer viewed publically or not
	 *
	 * @param unknown_type $FlyerId
	 * @return unknown
	 */
 	function getFlyerPublicStatus($FlyerId)
 	{
 		$status	=	TRUE;

 		$Qry		=	"SELECT COUNT(*) AS RowCount
						FROM flyer_data_basic 
						WHERE active = 'Y' AND publish = 'Y' 
						AND expire_date >= CURDATE() AND flyer_id = '$FlyerId'";
 		$Row		=	$this->db->get_row($Qry,ARRAY_A);
 		$RowCount	=	$Row['RowCount'];

 		if($RowCount <= 0)
 		$status	=	FALSE;
 		else
 		$status	=	TRUE;

 		return $status;
 	}
 	/**
	 * Update the Fixed rate as Group
	 * Author :Afsal
	 * Created:04/Feb/2008
	 * Modified :04/Feb/2008
	 */
 	function updateFixedRateAsGroup($reqFixedRate,$reqFixedId){

 		if(count($reqFixedRate) && count($reqFixedId)){

 			for($i=0;$i<count($reqFixedRate);$i++){

 				$arrayFixedPrice = array("price" => $reqFixedRate[$i]);
 				$this->db->update("property_pricing",$arrayFixedPrice,"id={$reqFixedId[$i]}");
 			}
 		}
 	}
 	function deleteAllBlockedProperty($propid){

 		if($propid >0){
 			$this->db->query("DELETE FROM property_blocked WHERE album_id=$propid");
 			return "deleted";
 		}
 	}
 	function countPropertyFixedPricing($propid){

 		if($propid > 0){
 			$SQL = "SELECT count(id) As cntR FROM property_pricing WHERE album_id=$propid AND default_val='N'";
 			$rs = $this->db->get_row($SQL,ARRAY_A);
 			return $rs["cntR"];
 		}
 	}

 	/**
	 * Update to publish all expired data
	 * Author :Vipin
	 * Created:25/Mar/2008
	 * Modified :25/Mar/2008
	 */

 	function listMyFlyersexpired($flag,$keysearch='N',$flyer_search='',$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy="1",$status_id='')
 	{
 		$user_id	=	$_SESSION['memberid'];
 		$cur_date	=	date('Y-m-d');
 		//$qry		=	"select fb.*,fm.form_title,mm.first_name,mm.last_name,mm.username from flyer_data_basic fb,flyer_form_master fm,member_master mm  where fb.active='Y' AND fb.user_id=mm.id  AND fb.form_id=fm.form_id ";
 		$qry	=	"SELECT T1.*,
						T2.form_title AS form_title, 
						T3.first_name AS first_name, 
						T3.last_name AS last_name, 
						T3.username AS username 
						FROM flyer_data_basic AS T1
						LEFT JOIN flyer_form_master AS T2 ON T2.form_id = T1.form_id
						LEFT JOIN member_master AS T3 ON T3.id = T1.user_id 
						WHERE T1.active='Y' ";

 		if($flag=="U")
 		{
 			$qry	.=	" AND T1.user_id='$user_id' ";

 		}
 		$qry	.=	" and T1.expire_date < '$cur_date' ";

 		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, 'All', $params, $output, $orderBy);


 		return $rs;
 	}
 	/* Format Convert date to mysql format
 	* Created:Afsal
 	* Date:31/03/2008
 	*/
 	function convertToMySqlFormat($date){

 		$case = $this->config["calendar_date_format"];

 		switch ($case){

 			/* MM/dd/yyyy to Y-m-d */
 			case "MM/dd/yyyy":

 				if($date !=""){

 					$dateArr = explode("/",$date);
 					$date_f  = $dateArr[2]."-".$dateArr[0]."-".$dateArr[1];
 					$revertDate = date('Y-m-d', strtotime("$date_f"));
 					return  $revertDate;
 				}
 				break;
 			case "d/M/y":
 				if($date !=""){

 					$dateArr = explode("/",$date);
 					$date_f  = $dateArr[2]."-".$dateArr[1]."-".$dateArr[0];
 					$revertDate = date('Y-m-d', strtotime("$date_f"));
 					return $revertDate;
 				}
 				break;
 				if($date !=""){

 				}
 			case "d-MMM-y":
 				if($date !=""){

 					$dateArr = explode("-",$date);
 					$date_f  = $dateArr[2]."-".$dateArr[1]."-".$dateArr[0];
 					$revertDate = date('Y-m-d', strtotime("$date_f"));
 					return $revertDate;
 				}
 				break;
 		}
 	}
 	/**
	*  Format revert to php format
 	* Created:Afsal
 	* Date:31/03/2008
 	* Modified:31/03/2008 Afsal
 	*/
 	function revertToPhpFormat($date){

 		$case = $this->config["calendar_date_format"];

 		switch ($case){

 			/* MM/dd/yyyy to Y-m-d */
 			case "MM/dd/yyyy":

 				if($date !=""){

 					$dateArr = explode("-",$date);
 					$date_f  = $dateArr[1]."/".$dateArr[2]."/".$dateArr[0];
 					$revertDate = $date_f;
 					return  $revertDate;
 				}
 				break;
 			case "d/M/y":
 				if($date !=""){

 					$dateArr = explode("-",$date);
 					$date_f  = $dateArr[2]."/".$dateArr[1]."/".$dateArr[0];
 					$revertDate = $date_f;
 					return $revertDate;
 				}
 				break;
 				if($date !=""){

 				}
 			case "d-MMM-y":
 				if($date !=""){

 					$dateArr = explode("-",$date);
 					$date_f  = $dateArr[2]."-".$dateArr[1]."-".$dateArr[0];
 					$revertDate = date('d-F-Y', strtotime("$date_f"));
 					return $revertDate;
 				}
 				break;
 		}
 	}

 	/**
  	 * This function places a bid
  	 * Author   : Retheesh
  	 * Created  : 31/Mar/2008
  	 * Modified : 01/Apr/2008 By Retheesh
  	 * Modified : 02/Apr/2008 By Retheesh
  	 * Bid modification added
  	 * Modified : 03/Apr/2008 By Retheesh
  	 * Property owner bidding blocked
  	 */
 	function placeBid()
 	{
 		$arr = $this->getArrData();
 		$arr['bid_date'] = date("Y-m-d H:i:s");
 		$sql = "select b.user_id from property_pricing a left join album b on
		a.album_id=b.id where a.id={$arr['pricing_id']}";
 		$rs = $this->db->get_row($sql);
 		if (count($rs)>0)
 		{
 			if ($rs->user_id==$arr['user_id'])
 			{
 				$this->setErr($this->MOD_VARIABLES['MOD_ERRORS']['ERR_OWNER_BID']);
 				return false;
 			}
 		}
 		$sql = "select * from property_bid where user_id={$arr['user_id']} and pricing_id={$arr['pricing_id']}";
 		$rs  = $this->db->get_row($sql);
 		$arr_ret = array();
 		if (count($rs)>0)
 		{
 			$sql = "select bid_id from property_bid_select where bid_id={$rs->id} and staus='Y'";
 			$rs_sel = $this->db->get_row($sql,ARRAY_A);
 			if (count($rs_sel)>0)
 			{
 				$this->setErr($this->MOD_VARIABLES['MOD_ERRORS']['ERR_BID_DEL']);
 				return false;
 			}
 			$previous_bid = $rs->bid_amount;
 			$bid_upd_msg = $this->MOD_VARIABLES['MOD_MSG']['MSG_BID_UPDATE']; //Fetching error message from Label Management Variable
 			$this->db->update("property_bid",$arr,"id={$rs->id}");
 			$arr_ret[0] = $bid_upd_msg;
 			$arr_ret[1] = "update";
 			$arr_ret[2] = $previous_bid;
 			return $arr_ret;
 		}

 		$bid_new_msg =  $this->MOD_VARIABLES['MOD_MSG']['MSG_BID_SUCCESS'];
 		$this->db->insert("property_bid",$arr);
 		$arr_ret[0] = $bid_new_msg;
 		$arr_ret[1] = "new";
 		return $arr_ret;
 	}

 	function createPropertyImagebutton($text,$href='#',$onclick='')
 	{
 		global $global;
 		$msg='<table border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td><img src="'.$global["tpl_url"] .'/images/buttons1/left.jpg" width="3" height="24"></td>
						<td background="'.$global["tpl_url"] .'/images/buttons1/mid.jpg" style="width:90px;text-align:center;"><a href="'.$href.'"';
 		if($onclick)
 		$msg.=' onclick="'.$onclick.'"';
 		$msg.=' class="bodytext" style="color:#FFFFFF;font-weight:bold;">'.$text.'</a></td>
						<td><img src="'.$global["tpl_url"] .'/images/buttons1/right.jpg" width="3" height="24"></td>
					  </tr>
					</table>';
 		return $msg;
 	}
 	/**
  	 * This function retrievs the maximum bid for a property
  	 * Author   : Retheesh
  	 * Created  : 01/Apr/2008
  	 * Modified : 01/Apr/2008 By Retheesh
  	 */
 	function getMaximumBid($pricing_id)
 	{
 		$sql = "select max(b.bid_amount) as max_bid from  property_bid b where b.pricing_id=$pricing_id" ;
 		$rs  = $this->db->get_row($sql);
 		return $rs->max_bid;
 	}

 	/**
  	 * This function last bid of a user for a particualr section
  	 * Author   : Retheesh
  	 * Created  : 02/Apr/2008
  	 * Modified : 02/Apr/2008 By Retheesh
  	 * Modified : 02/Apr/2008 By Retheesh
  	 * Added username in query
  	 */
 	function getLastBid($user_id,$pricing_id)
 	{
 		$sql = "select p.*,m.username from property_bid p left join member_master m
 		on p.user_id=m.id where p.user_id=$user_id and p.pricing_id=$pricing_id";
 		$rs	 = $this->db->get_row($sql,ARRAY_A);
 		return $rs;
 	}

 	/**
  	 * This function retrieves the pricing details
  	 * Author   : Retheesh
  	 * Created  : 03/Apr/2008
  	 * Modified : 03/Apr/2008 By Retheesh
  	 */
 	function getPricingDetails($id)
 	{
 		$sql = "select a.*,c.* from property_pricing a left join album b on
		a.album_id=b.id left join member_master c on b.user_id=c.id where a.id=$id";
 		$rs = $this->db->get_row($sql);
 		return $rs;
 	}

 	/**
  	 * This function retrieves all bidding sections
  	 * Author   : Retheesh
  	 * Created  : 04/Apr/2008
  	 * Modified : 04/Apr/2008 By Retheesh
  	 */
 	function getFixedBidResults($pageNo, $limit, $params, $output,$orderBy,$user_id="")
 	{
 		$orderBy='a.start_date';

 		$qry	=	"SELECT a.*,date_format(a.start_date, '%d %M %Y' ) AS start_date_format,
			date_format(a.rental_end_date, '%d %M %Y' ) AS rental_end_date_format,
			date_format(a.auction_ends, '%d %M %Y')AS auctionFormat_date FROM property_pricing a left 
			join album b on a.album_id=b.id where a.auction='Y' and user_id=$user_id ";
 		//$rs	=	$this->db->get_results($qry,ARRAY_A);
 		list($rs ,$numpad)= $this->db->get_results_pagewise($qry,$pageNo, $limit, $params, $output, $orderBy);
 		$count	=count($rs);

 		if($count>0) {

 			/* Creating Blocks */

 			foreach ($rs as $k=>$rows){
 				$rs[$k]['Blocks'] = $this->getAllBidsByPricing($rs[$k]['id']);
 				if ($user_id)
 				{
 					$last_bid_det  = $this->getLastBid($user_id,$rs[$k]["id"]);
 					$rs[$k]["last_bid_det"] = $last_bid_det;
 					$rs[$k]["last_bid"] = $last_bid_det['bid_amount'];

 				}

 				$auction_stat = $this->getAuctionStatus($rs[$k]['id']);
 				$rs[$k]['select_bid'] = $auction_stat['bid_id'];

 				$sel_bid = $this->selectedBidDetails($user_id,$pricing_id);
 				if ($sel_bid['status'] =="Y")
 				{
 					$rs[$k]['selected_bid_user']= "Y";
 					$rs[$k]['auction_close_log'] = $this->MOD_VARIABLES['MOD_MSG']['MSG_BUYER_SELECTED'];
 				}
 				//elseif ()
 				if ($rs[$k]['auction_close']==1)
 				{
 					$rs[$k]['auction_closed'] = "Y";
 					$rs[$k]['auction_close_log'] = $this->MOD_VARIABLES['MOD_MSG']['MSG_BID_DT_CLOSE'];
 				}
 				elseif (date("Y-m-d H:i:s")>$rs[$k]["auction_ends"])
 				{
 					$rs[$k]["auction_closed"] = "Y";
 					$rs[$k]["auction_close_log"] = $this->MOD_VARIABLES['MOD_MSG']['MSG_BID_AUCTION_EXP'];
 				}
 				foreach($rs[$k]['Blocks'] as $key=>$val)
 				{
 					if($val['status']=='Y')
 					{

 						$rs[$k]["end_bidding"] = "Y";
 						$rs[$k]['auction_close_log'] = $this->MOD_VARIABLES['MOD_MSG']['MSG_BID_DT_BUYER_WAIT1'];
 					}
 				}
 				if (date("Y-m-d H:i:s")<$rs[$k]["auction_ends"])
 				{
 					if ($rs[$k]['auction_close']==1)
 					{
 						$rs[$k]['auction_restart']="Y";
 					}
 				}

 				$rs[$k]['property'] = $this->getPropertyDetails($rs[$k]['album_id']);
 			}

 			//print_r($rs);
 			/* Creating Blocks */
 			$arr[0]=$rs;
 			$arr[1]=$numpad;
 			return $arr;
 		}
 	}

 	/**
  	 * This function retrieves all bids on a particular section
  	 * Author   : Retheesh
  	 * Created  : 04/Apr/2008
  	 * Modified : 04/Apr/2008 By Retheesh
  	 */
 	function getAllBidsByPricing($pricing_id)
 	{
 		$sql = "select a.*,b.username,p.status,p.owner_log from property_bid a left join member_master b
		on a.user_id=b.id left join property_bid_select p on a.id=p.bid_id where a.pricing_id='$pricing_id' Order by a.bid_date desc ";
 		$rs = $this->db->get_results($sql,ARRAY_A);
 		return $rs;
 	}

 	/**
  	 * This function retrieves Property Details
  	 * Author   : Retheesh
  	 * Created  : 07/Apr/2008
  	 * Modified : 07Apr/2008 By Retheesh
  	 */

 	function getPropertyDetails($prop_id)
 	{
 		$sql = "select * from flyer_data_basic where album_id=$prop_id";
 		$rs  = $this->db->get_row($sql,ARRAY_A);
 		return $rs;
 	}


 	/* Created:Vinoy
 	Date:08-April-2008
 	to add rating and feedback of the property and seller
 	*/

 	function checkPropRating($bookid,$uid)
 	{

 		$SQL = "SELECT * FROM media_rating WHERE diff_userid='$bookid' AND type='property' AND member_id='$uid'";
 		$rs  = $this->db->get_row($SQL, ARRAY_A);
 		return $rs;

 	}

 	/* Created:Vinoy
 	Date:08-April-2008
 	to add rating and feedback of the property and seller
 	*/
 	function checkSellerRating($bookid,$uid)
 	{
 		$SQL = "SELECT * FROM media_rating WHERE diff_userid='$bookid'  AND type='seller' AND member_id='$uid'";
 		$rs  = $this->db->get_row($SQL, ARRAY_A);
 		return $rs;
 	}

 	function checkPropComments($bookid,$uid)
 	{
 		$SQL = "SELECT * FROM media_comments WHERE diff_userid='$bookid' AND type='property' AND user_id='$uid'";
 		$rs  = $this->db->get_row($SQL, ARRAY_A);
 		return $rs;
 	}
 	function checkSellerComments($bookid,$uid)
 	{
 		$SQL = "SELECT * FROM media_comments WHERE diff_userid='$bookid' AND type='seller' AND user_id='$uid'";
 		$rs  = $this->db->get_row($SQL, ARRAY_A);
 		return $rs;
 	}

 	/* Created:Vinoy
 	Date:08-April-2008
 	to add rating and feedback of broker or property manager
 	*/

 	function checkComments($fileid,$bmuid,$memberID,$type)
 	{
 		$SQL = "SELECT * FROM media_comments WHERE file_id='$bmuid' AND diff_userid='$fileid' AND type='$type' AND user_id='$memberID'";
 		$rs  = $this->db->get_row($SQL, ARRAY_A);
 		return $rs;
 	}
 	function checkRating($fileid,$bmuid,$memberID,$type)
 	{
 		$SQL = "SELECT * FROM media_rating WHERE file_id='$bmuid' AND diff_userid='$fileid'  AND type='$type' AND member_id='$memberID'";
 		$rs  = $this->db->get_row($SQL, ARRAY_A);
 		return $rs;
 	}
 	/* Created:Vinoy
 	Date:08-April-2008
 	to add rating and feedback of the property and seller
 	*/

 	function addRatingAndFeedback($req)
 	{


 		$date=date("Y-m-d");
 		extract($req);
 		// print_r($req);
 		// exit;
 		$uid= $_SESSION['memberid'];
 		if($seller_rate!="")
 		{

 			$sellerRateArray=array("type"=>seller,"file_id"=>$sellerid,"diff_userid"=>$bookingid,"member_id"=>$uid,"postdate"=> $date,"mark"=>$seller_rate);
 			$sellerRate = $this->db->insert("media_rating",$sellerRateArray);
 		}
 		if($Property_rate!="")
 		{
 			$propRateArray=array("type"=>property,"file_id"=>$propid,"diff_userid"=>$bookingid,"member_id"=>$uid,"postdate"=> $date,"mark"=>$Property_rate);
 			$propRate = $this->db->insert("media_rating",$propRateArray);
 		}
 		if($sellercomment!="")
 		{

 			$sellerCommentArray=array("type"=>seller,"file_id"=>$sellerid,"diff_userid"=>$bookingid,"user_id"=>$uid,"comment"=>$sellercomment,"postdate"=>$date);
 			$sellerComment = $this->db->insert("media_comments",$sellerCommentArray);
 		}
 		if($propcomment!="")
 		{
 			$propCommentArray=array("type"=>property,"file_id"=>$propid,"diff_userid"=>$bookingid,"user_id"=>$uid,"comment"=>$propcomment,"postdate"=>$date);
 			$propComment = $this->db->insert("media_comments", $propCommentArray);
 		}
 		if($comment!="")
 		{
 			$commentArray=array("type"=>$type,"file_id"=>$bmid,"diff_userid"=>$fileid,"user_id"=>$uid,"comment"=>$comment,"postdate"=>$date);
 			$comment = $this->db->insert("media_comments", $commentArray);
 		}

 		if($rate!="")
 		{
 			$rateArray=array("type"=>$type,"file_id"=>$bmid,"diff_userid"=>$fileid,"member_id"=>$uid,"postdate"=> $date,"mark"=>$rate);
 			$rate = $this->db->insert("media_rating",$rateArray);
 		}


 		return true;

 	}

 	/**
  	 * This function selects a bid
  	 * Author   : Retheesh
  	 * Created  : 09/Apr/2008
  	 * Modified : 09Apr/2008 By Retheesh
	 * Modified : 15Apr/2008 By Adarsh
  	 */
 	function select_bid()
 	{
 		$arr = $this->getArrData();
 		$sql = "select bid_id from property_bid_select where pricing_id={$arr['pricing_id']} and status='Y'";
 		$rs  = $this->db->get_row($sql);
 		$arr_ret = array();

 		if($arr['auction_delete']=='Y'){
 			$arr_ret['type']     = "delete";
 			$arr_ret['status']   = count($rs);
 			if(count($rs)==0){
 				$arr_ret['msg']	= $this->MOD_VARIABLES['MOD_MSG']['MSG_AUCTION_DELETE'];
 			}
 			else{
 				$arr_ret['msg']	= $this->MOD_VARIABLES['MOD_MSG']['MSG_AUCTION_DELETE_ERROR'];
 			}
 			return $arr_ret;
 		}

 		$arr_ret['win_user'] = $arr['user_id'];
 		if (count($rs)>0)
 		{
 			$sql = "update property_bid_select set status='N' where pricing_id={$arr['pricing_id']}";
 			$this->db->query($sql);
 			$arr_ret['type']     = "update";
 			$arr_ret['pre_bid']  = $rs->bid_id;
 			$arr_ret['msg']      =  $this->MOD_VARIABLES['MOD_MSGS']['MSG_BID_CHANGE'];
 		}
 		else
 		{
 			$arr_ret['type']     = "new";
 			$arr_ret['msg']		 = $this->MOD_VARIABLES['MOD_MSGS']['MSG_BID_SELECT'];
 		}

 		$this->db->insert("property_bid_select",$arr);
 		return $arr_ret;
 	}


 	/**
  	 * This function retrieves auction status
  	 * Author   : Retheesh
  	 * Created  : 09/Apr/2008
  	 * Modified : 09Apr/2008 By Retheesh
  	 */
 	function getAuctionStatus($pricing_id)
 	{
 		$sql = "select bid_id from property_bid_select where status='Y' and pricing_id=$pricing_id";
 		$rs  = $this->db->get_row($sql,ARRAY_A);
 		return $rs;
 	}

 	##### Function name : getStatebyCity........................................................
 	##### The function to retrieve  State name from flyer_data_contact according to City Name...
 	##### Created on 09 April 2008..............................................................
 	##### Created By Jipson Thomas..............................................................
 	##### Modified on 09 April 2008.............................................................
 	##### Modified By Jipson Thomas.............................................................
 	function getStatebyCity($city)
 	{
 		$sql="select location_state from flyer_data_contact where location_city='$city'";
 		$rs = $this->db->get_row($sql,ARRAY_A);
 		return $rs["location_state"];
 	}

 	#### End of function getStatebyCity..

 	##### Function name : getStateCodebyName....................................................
 	##### The function to retrieve  State code from state_code according to State Name..........
 	##### Created on 09 April 2008..............................................................
 	##### Created By Jipson Thomas..............................................................
 	##### Modified on 09 April 2008.............................................................
 	##### Modified By Jipson Thomas.............................................................
 	function getStateCodebyName($state){
 		$sql="select code from state_code where name='$state'";
 		$rs = $this->db->get_row($sql,ARRAY_A);
 		return $rs["code"];

 	}

 	#### End of function getStateCodebyName.............................
 	function getBookingDetails($bookid)
 	{
 		$SQL ="select* from album_booking  where id='$bookid'";
 		$rs  = $this->db->get_row($SQL, ARRAY_A);
 		return $rs;

 	}
 	function getBookId($propid)
 	{
 		$SQL ="select id from album_booking  where album_id='$propid'";
 		$rs = $this->db->get_results($SQL,ARRAY_A);
 		return $rs;
 	}
 	/**
  	 * This function retrieves Bid Details
  	 * Author   : Retheesh
  	 * Created  : 11/Apr/2008
  	 * Modified : 11/Apr/2008 By Retheesh
  	 */
 	function getBidDetails($id)
 	{
 		$sql = "select * from property_bid where id=$id";
 		$rs  = $this->db->get_row($sql,ARRAY_A);
 		return $rs;
 	}
 	/**
	* This function retrieves the bids created by a user 
	* Author   : Jipson Thomas
	* Created  : 15/Apr/2008
	* Modified : 15/Apr/2008 By Retheesh
	*/
 	function getMyBids($pageNo, $limit, $params, $output,$uid)
 	{
 		$sql="select a.*,a.id as bid_id,date_format(a.bid_date, '%d %M %Y' ) AS bid_date_format,b.*,date_format(b.start_date, '%d %M %Y' ) AS start_date_format,date_format(b.rental_end_date, '%d %M %Y' ) AS rental_end_date_format,c.flyer_name from property_bid a left join property_pricing b on b.id=a.pricing_id left join flyer_data_basic c on c.album_id=b.album_id where a.user_id=$uid";
 		//$rs  = $this->db->get_results($sql,ARRAY_A);
 		$rs = $this->db->get_results_pagewise($sql,$pageNo, $limit, $params, $output, $orderBy);
 		return $rs;
 	}
 	/**
	* This function deletes the bids created by a user 
	* Author   : Jipson Thomas
	* Created  : 15/Apr/2008
	* Modified : 15/Apr/2008 By Jipson
	*/
 	function deleteBids($bid)
 	{
 		$id=$bid;
 		$sql="SELECT count(*) as cnt from property_bid_select a inner join property_bid b on a.pricing_id=b.pricing_id where b.id=$bid and a.status='Y'";
 		//
 		$rs  = $this->db->get_row($sql,ARRAY_A);
 		if($rs["cnt"]>0){
 			setMessage($this->MOD_VARIABLES['MOD_ERRORS']['ERR_NOT_DELETE_BID'],MSG_INFO);
 			return false;
 		}else{
 			$sql1="delete from property_bid where id=$id";

 			$this->db->query($sql1);
 			setMessage($this->MOD_VARIABLES['MOD_MSG']['MSG_BID_DELETE_SUCCESS'],MSG_SUCCESS);
 			return true;
 		}
 	}
 	/**
	* This function retieves the owner details of a property through a bid id
	* Author   : Jipson Thomas
	* Created  : 15/Apr/2008
	* Modified : 15/Apr/2008 By Retheesh
	*/
 	function getPropertyOwnerthroughBid($bid)
 	{
 		$sql="SELECT d. *,c.flyer_name,c.description,b.start_date,b.rental_end_date,b.duration,b.unit,a.bid_date,a.bid_amount
FROM member_master d
INNER JOIN flyer_data_basic c ON d.id = c.user_id
INNER JOIN property_pricing b ON c.album_id = b.album_id
INNER JOIN property_bid a ON b.id = a.pricing_id
WHERE a.id =$bid";
 		//
 		$rs  = $this->db->get_row($sql,ARRAY_A);
 		return $rs;
 	}
 	/**
	* This function retieves the owner details of a bid through a bid id
	* Author   : Jipson Thomas
	* Created  : 15/Apr/2008
	* Modified : 15/Apr/2008 By Retheesh
	*/
 	function getbidOwnerthroughBid($bid)
 	{
 		$sql="SELECT a. *
FROM member_master a
INNER JOIN property_bid b ON a.id = b.user_id
WHERE b.id =$bid";
 		//
 		$rs  = $this->db->get_row($sql,ARRAY_A);
 		return $rs;
 	}

 	/**
	* This function retieves the bids posted by a user 
	* Author   : adarsh
	* Created  : 16/Apr/2008
	*/
 	function getBidCountByUser($userid)
 	{
 		$sql="select count(*) as bid_count from property_bid where user_id='$userid'";
 		$rs  = $this->db->get_row($sql, ARRAY_A);
 		return $rs;
 	}
 	/**
	* This function used to get the count of rejected using user id
	* Author   : adarsh
	* Created  : 16/Apr/2008
	*/
 	function getBidRejectedCount($userid)
 	{
 		$sql="select count(*) as bid_rejected_count from property_bid a left join property_bid_select b on a.id=b.bid_id where user_id='$userid' and b.status='N'";
 		$rs  = $this->db->get_row($sql, ARRAY_A);
 		return $rs;
 	}
 	/**
  	 * This function retrieves all bidding sections using the property id
  	 * Author   : Adarsh	
  	 * Created  : 11/Apr/2008
  	 */
 	function getFixedBidResultsByPropid($propid="")
 	{
 		$qry	=	"SELECT a.*,date_format(a.start_date, '%d %M %Y' ) AS start_date_format,
			date_format(a.rental_end_date, '%d %M %Y' ) AS rental_end_date_format,
			date_format(a.auction_ends, '%d %M %Y')AS auctionFormat_date FROM property_pricing a left 
			join album b on a.album_id=b.id where a.auction='Y' and album_id='$propid' ORDER BY a.start_date";
 		$rs	=	$this->db->get_results($qry,ARRAY_A);
 		//print $qry;
 		$count	=	count($rs);
 		if($count>0) {

 			/* Creating Blocks */

 			foreach ($rs as $k=>$rows){
 				$rs[$k]['Blocks'] = $this->getAllBidsByPricing($rs[$k]['id']);
 				if ($user_id)
 				{
 					$last_bid_det  = $this->getLastBid($user_id,$rs[$k]["id"]);
 					$rs[$k]["last_bid"] = $last_bid_det['bid_amount'];
 				}
 				$auction_stat = $this->getAuctionStatus($rs[$k]['id']);
 				$rs[$k]['select_bid'] = $auction_stat['bid_id'];
 				if (count($auction_stat)>0)
 				{
 					$rs[$k]['auction_closed'] = "Y";
 					$rs[$k]['auction_close_log'] = $this->MOD_VARIABLES['MOD_MSG']['MSG_BID_DT_BUYER_WAIT'];
 				}
 				elseif (date("Y-m-d H:i:s")>$rs[$k]["auction_ends"])
 				{
 					$rs[$k]["auction_closed"] = "Y";
 					$rs[$k]["auction_close_log"] = $this->MOD_VARIABLES['MOD_MSG']['MSG_BID_DT_CLOSE'];
 				}
 				if (count($auction_stat)>0)
 				{
 					$rs[$k]['auction_closed'] = "Y";
 					$rs[$k]['auction_close_log'] = $this->MOD_VARIABLES['MOD_MSG']['MSG_BID_DT_BUYER_WAIT'];
 				}

 				$rs[$k]['property'] = $this->getPropertyDetails($rs[$k]['album_id']);
 			}

 			//print_r($rs);
 			/* Creating Blocks */
 			return $rs;
 		}
 	}

 	/**
	* This function deletes the bids by prcing id and userid (AJAX)
	* Author   : Retheesh Kumar
	* Created  : 18/Apr/2008
	* Modified : 18/Apr/2008 By Retheesh Kumar
	*/
 	function deleteBidAjax($pricing_id,$user_id)
 	{
 		$rs  = $this->getBidDetailsByPricing($pricing_id,$user_id);
 		$sql = "select bid_id from property_bid_select where bid_id={$rs['id']} and staus='Y'";
 		$rs_sel = $this->db->get_row($sql,ARRAY_A);
 		if (count($rs_sel)>0)
 		{
 			$this->setErr($this->MOD_VARIABLES['MOD_ERRORS']['ERR_BID_DEL']);
 			return false;
 		}
 		$sql = "delete from property_bid where pricing_id=$pricing_id and user_id=$user_id";
 		$this->db->query($sql);
 		return true;
 	}

 	/**
	* This function retrieves details of the bids by prcing id and userid
	* Author   : Retheesh Kumar
	* Created  : 18/Apr/2008
	* Modified : 18/Apr/2008 By Retheesh Kumar
	*/
 	function getBidDetailsByPricing($pricing_id,$user_id)
 	{
 		$sql = "select id from property_bid where pricing_id=$pricing_id and user_id=$user_id";
 		$rs  = $this->db->get_row($sql,ARRAY_A);
 		return $rs;
 	}

 	/**
	* This function Retrieves Selected Bid Details
	* Author   : Retheesh Kumar
	* Created  : 18/Apr/2008
	* Modified : 18/Apr/2008 By Retheesh Kumar
	*/
 	function selectedBidDetails($user_id,$pricing_id)
 	{
 		$bid_det  = $this->getBidDetailsByPricing($pricing_id,$user_id);

 		if ($bid_det)
 		{
 			$sql  = "select * from property_bid_select where bid_id={$bid_det['id']}";
 			$rs   = $this->db->get_row($sql,ARRAY_A);
 			return $rs;
 		}
 	}

 	/**
	* This function is to delete the auction 
	* Author   : Adarsh
	* Created  : 18/Apr/2008
	* 
	*/

 	function auctionDelete($pricing_id)
 	{
 		$this->db->query("UPDATE property_pricing SET auction='N' WHERE id=$pricing_id");
 	}
 	/**
	* This function is to delete the bid 
	* Author   : Adarsh
	* Created  : 18/Apr/2008
	* 
	*/
 	function bidDelete($bid_id)
 	{
 		//$this->db->query("DELETE FROM  property_bid WHERE id=$bid_id");
 	}

 	/**
	* This function rejects offer for hiring a property
	* Author   : Retheesh Kumar
	* Created  : 21/Apr/2008
	* Modified : 21/Apr/2008 By Retheesh Kumar
	*/
 	function rejectOffer($bid_id)
 	{
 		$sql = "update property_bid_select set status='R' where bid_id=$bid_id";
 		$this->db->query($sql);
 		return true;
 	}

 	/**
	* This function retieves the bids count count rejected by the owner 
	* Author   : adarsh
	* Created  : 21/Apr/2008
	*/

 	function getRejectedBidCountByOwner($uid)
 	{
 		$sql="select count(*) as bid_count from property_bid_select a inner join property_bid b
			 on b.id=a.bid_id where b.user_id='$uid' and a.status='N'";
 		$rs   = $this->db->get_row($sql,ARRAY_A);
 		return $rs;
 	}

 	/**
	* This function retieves the bids count  rejected by the user 
	* Author   : adarsh
	* Created  : 21/Apr/2008
	*/
 	function getRejectedBidCountByUser($uid)
 	{
 		$sql="select count(*) as bid_count from property_bid_select a inner join property_bid b on b.id=a.bid_id
			where b.user_id='$uid'  and a.status='R'";
 		$rs   = $this->db->get_row($sql,ARRAY_A);
 		return $rs;

 	}
 	/**
	* This function retieves the count of bids won by a user 
	* Author   : adarsh
	* Created  : 21/Apr/2008
	*/
 	function getBidsWonCount($uid)
 	{
 		$sql="select count(*) as bid_count from property_bid_select a inner join property_bid b on b.id=a.bid_id
			 where b.user_id='$uid'  and a.status='Y'";
 		$rs   = $this->db->get_row($sql,ARRAY_A);
 		return $rs;
 	}
 	/**
  	 * This function is used to get all the bid posted by the user
  	 * Author   : Adarsh
  	 * Created  : 04/Apr/2008
  	 * Modified : 21/Apr/2008 By Retheesh
  	 */
 	function getBidsByUserId($pageNo, $limit, $params, $output,$orderBy,$user_id="")
 	{
 		$orderBy='b.start_date';

 		$qry="SELECT a.*,b.*,date_format(b.start_date, '%d %M %Y' ) AS start_date_format,
			date_format(b.rental_end_date, '%d %M %Y' ) AS rental_end_date_format,c.status,c.bid_id as aid,
			date_format(b.auction_ends, '%d %M %Y')AS auctionFormat_date,a.id as bid_id,b.id as pricing_id,
			d.bid_id as paymement_bid_id FROM property_bid a
			inner join property_pricing b ON a.pricing_id=b.id LEFT JOIN property_bid_select c ON a.id=c.bid_id 
			left join property_bid_payments d ON d.bid_id=c.bid_id	WHERE a.user_id='$user_id' and b.auction='Y' ";

 		list($rs ,$numpad)= $this->db->get_results_pagewise($qry,$pageNo, $limit, $params, $output, $orderBy);
 		$count	=count($rs);
 		if($count>0) {

 			/* Creating Blocks */

 			foreach ($rs as $k=>$rows){
 				$rs[$k]['Blocks'] = $this->getAllBidsByPricing($rs[$k]['id']);
 				if ($user_id)
 				{
 					$last_bid_det  = $this->getLastBid($user_id,$rs[$k]["id"]);
 					$rs[$k]["last_bid_det"] = $last_bid_det;
 					$rs[$k]["last_bid"] = $last_bid_det['bid_amount'];

 				}
 				$auction_stat = $this->getAuctionStatus($rs[0][$k]['id']);
 				$rs[$k]['select_bid'] = $auction_stat['bid_id'];
 				$sel_bid = $this->selectedBidDetails($user_id,$pricing_id);
 				if ($sel_bid['status'] =="Y")
 				{
 					$rs[$k]['selected_bid_user']= "Y";
 					$rs[$k]['auction_close_log'] = $this->MOD_VARIABLES['MOD_MSG']['MSG_BUYER_SELECTED'];
 				}
 				//elseif ()
 				if ($rs[$k]['auction_close']==1)
 				{
 					$rs[$k]['auction_closed'] = "Y";
 					$rs[$k]['auction_close_log'] = $this->MOD_VARIABLES['MOD_MSG']['MSG_BID_DT_CLOSE'];
 				}
 				elseif (date("Y-m-d H:i:s")>$rs[$k]["auction_ends"])
 				{
 					$rs[$k]["auction_closed"] = "Y";
 					$rs[$k]["auction_close_log"] = $this->MOD_VARIABLES['MOD_MSG']['MSG_BID_DT_CLOSE'];
 				}
 				if ($rs[$k]['status']=="Y")
 				{
 					$rs[$k]['auction_close_log'] = $this->MOD_VARIABLES['MOD_MSG']['MSG_BUYER_SELECTED'];
 				}
 				else if($rs[$k]['status']=='R')
 				{
 					$rs[$k]['auction_close_log'] = $this->MOD_VARIABLES['MOD_MSG']['MSG_BUYER_REJECT'];
 				}

 				$pay_det=$this->getBidPaymentStatus($rs[$k]['pricing_id']);

 				if(count($pay_det)>0)
 				{
 					if($pay_det['user_id']==$user_id)
 					{
 						$rs[$k]['auction_close_log'] = $this->MOD_VARIABLES['MOD_MSG']['MSG_BID_PAYMENT_MADE'];
 					}
 					else
 					{
 						$rs[$k]['auction_close_log'] = "This section of the property has been awarded to somebody else";
 					}

 				}

 				$rs[$k]['property'] = $this->getPropertyDetails($rs[$k]['album_id']);
 			}

 			/* Creating Blocks */
 			//print_r($rs);
 			$arr[0]=$rs;
 			$arr[1]=$numpad;
 			return $arr;
 		}
 	}
 	/**
 	 * 
 	 * 
 	 * 
 	 * 
 	*/
 	function saveBidFromCalendar($req,$propid){


 		$auction_end_dat = $this->convertToMySqlFormat($req["fixed_bid_expires"]);

 		$array = array("album_id" => $propid,"start_bid" => $req["minimum_bid_s"],"reserve_bid"=>$req["reserv_bid_s"],
 		"start_date" => $req["start_date"],"rental_end_date" => $req["rental_end_date"],"color_code" => $req["color_code"],
 		"auction_ends" => $auction_end_dat,"auction" => "Y");


 		if($req["b_id"] >0){

 			$this->db->update("property_pricing",$array,"id={$req["b_id"]} AND album_id=$propid");
 			return $req["b_id"];

 		}else{

 			$this->db->insert("property_pricing",$array);
 			$pid = $this->db->insert_id;
 			return $pid;
 		}

 	}
 	/**
  	 * This function is used to delete the selected bid for payment
  	 * Author   : Adarsh
  	 * Created  : 24/Apr/2008
  	 */
 	function cancelBid($bid_id)
 	{
 		$this->db->query("DELETE FROM property_bid_select WHERE bid_id='$bid_id'");
 	}
 	/**
  	 * This function is used to set the auction closed
  	 * Author   : Adarsh
  	 * Created  : 24/Apr/2008
  	 */
 	function updateAuction($id)
 	{
 		$array=array("auction_close"=>1);
 		$this->db->update("property_pricing",$array,"id='$id'");
 	}
 	/**
  	 * This function is used to set the auction open
  	 * Author   : Adarsh
  	 * Created  : 24/Apr/2008
  	 */
 	function restartAuction($id)
 	{
 		$array=array("auction_close"=>0);
 		$this->db->update("property_pricing",$array,"id='$id'");
 	}

 	function getBidPaymentDet($bid_id)
 	{
 		$sql		=	"select * from  property_bid_payments where bid_id='$bid_id'";
 		$rs 		= 	$this->db->get_row($sql, ARRAY_A);
 		return $rs;
 	}

 	function getBidPaymentStatus($pricing_id)
 	{
 		$sql="select a.*,b.user_id from property_bid_payments a inner join property_bid b on a.bid_id=b.id where b.pricing_id='$pricing_id' ";
 		$rs = $this->db->get_row($sql, ARRAY_A);
 		return $rs;
 	}

 	/**
    * Searching whether a broker or manger has assigned roles.
  	* Author   : Retheesh Kumar
  	* Created  : 08/May/2008	
  	* Modified : 08/May/2008 By Retheesh Kumar.
  	*/
 	function checkAssigned($user_id,$role)
 	{
 		$sql = "select * from propertyassign_relation where assigned_user_id=$user_id and assigned_role='$role'";
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
	function addStream($state,$zip,$miles,$content,$uid)
	{
		$streamArray = array("user_id"=>$uid,"state"=>$state,"zip"=>$zip,"miles"=>$miles,"content"=>$content);
		$this->db->insert("main_stream", $streamArray);
 		$stream_id = $this->db->insert_id;
 		return $stream_id;
	
	}
	
	
	function getStreamDetails($pageNo=0, $limit = 1, $params='',$output=ARRAY_A,$orderBy,$zip,$state,$miles)
	{
			if($zip!='')
				{
					$qry .= " AND zip LIKE '$zip%'";
				}
				
				if($state!='')
				{
					$qry1 .= " AND state LIKE '$state%'";
				}
				
				if($miles!='')
				{
					$qry2 .= " AND miles LIKE '$miles%'";
				}
				if($zip!='' || $state!='' || $miles!='')
				{
	     			 $SQL    =	"SELECT content from main_stream where 1 $qry $qry1 $qry2";
				}else{
		     		 $SQL    =	"SELECT content from main_stream";
		  		}
				//exit;
		  $rs 	 = 	$this->db->get_results_pagewise_ajax($SQL, $pageNo, 1, $params, $output,$orderBy,'','','');
		
		  return $rs;
	}
	
 }
?> 