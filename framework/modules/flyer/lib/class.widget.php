<?
class Widget extends FrameWork {

	function Widget()
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
	


	
	// display the widgets of a user
	function listMyWidgets($flag,$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy="1",$status_id='')
	{	
			$user_id	=	$_SESSION['memberid'];
			$qry		=	"select wb.*,mm.first_name,mm.last_name,mm.username  from widget_basic wb,member_master mm where wb.active='Y' AND wb.user_id=mm.id";
			if($flag=="U")
				$qry	.=	" AND wb.user_id='$user_id' "; 
			if($status_id!='')
			$qry	.=	" AND wb.type='$status_id' "; 
			
			
			$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
	
		return $rs;
		
	}
	
	/*
	function listMyWidgets($flag,$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy="1",$status_id='')
	{	
			$user_id	=	$_SESSION['memberid'];
			$qry		=	"select * from widget_basic where active='Y'";
			if($flag=="U")
			$qry	.=	" AND user_id='$user_id' "; 
			if($status_id!='')
			$qry	.=	" AND type='$status_id' "; 
			$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output, $orderBy);
	
		return $rs;
		
	}
	*/
	
	function addEditWidget($req,$widegetCode,$widget_type)
	{
		extract($req);
		$user_id	=	$_SESSION["memberid"];
		$cur_date	=	date('Y-m-d');
		$template_id=	"";
		$create_date=	"";
		$widgetArray = array("user_id"=>$user_id,"title"=>$title,
		"type"=>$widget_type,"name"=>$name,"flyer_id"=>$wid_flyer_id,
		"code"=>addslashes($widegetCode),"create_date"=>$create_date,"modified_date"=>$cur_date,
		"active"=>"Y");
		
		if($widget_id=="" || $widget_id=="0") {
			$this->db->insert("widget_basic", $widgetArray);
		}
		else {
			$this->db->update("widget_basic", $widgetArray, "widget_id='$widget_id '");
		}
		return true;
	}
	
	function gadgetDelete($gadget_id)
	{	
		$qry	=	"update widget_basic set active='N' where widget_id  ='$gadget_id'";
		$this->db->query($qry);
		return true;
	}
	
	// ### for widgets ###
	function getFileData($myFile)
	{
		$fh = fopen($myFile, 'r');
		$theData = fgets($fh);
		fclose($fh);
		return $theData;
	}
	
	function getWidgetdetails($widget_id)
	{
		$rs = $this->db->get_row("SELECT * FROM widget_basic WHERE widget_id=$widget_id", ARRAY_A);
		return $rs;
	}
	
	function setRowData($strNewRow,$user_id,$myaccount_url)
	{
		$RowLinkTextUrl=$this->getmyWidgetNames($user_id);
		# images path
		$RowdtaText		=	$this->getmyWidgetFields($user_id);
		$RowImgLinkUrl	=	$this->getmyWidgetImagesUrl($user_id,$myaccount_url);
		$RowImage		=	$this->getmyWidgetImages($user_id);
		$RowTxtLink		=	$this->getmyWidgetImagesUrl($user_id,$myaccount_url); 
		#$RowImgText		=	$this->getmyWidgetImages($user_id);
		for($cnt=0;$cnt<sizeof($RowImgLinkUrl);$cnt++)
		{
			$strNewRow1=$strNewRow1.$strNewRow;
			$strNewRow1=str_replace("#ImgLinkUrl#", $RowImgLinkUrl[$cnt], $strNewRow1);
			$strNewRow1=str_replace("#Image#", $GLOBALS['strImgPath'].$RowImage[$cnt], $strNewRow1);
			$strNewRow1=str_replace("#ImgText#", $GLOBALS['strImgPath'].$RowImgText[$cnt], $strNewRow1);
			$strNewRow1=str_replace("#TxtLink#", $RowTxtLink[$cnt], $strNewRow1);
			$strNewRow1=str_replace("#LinkTextUrl#", $RowLinkTextUrl[$cnt], $strNewRow1);
			$strNewRow1=str_replace("#dtaText#", $RowdtaText[$cnt], $strNewRow1);
			if($cnt%2==0)$strNewRow1=str_replace("#listingrow#", "listing-row", $strNewRow1);
			else $strNewRow1=str_replace("#listingrow#", "listing-alt-row", $strNewRow1);
		}
		return $strNewRow1;
	}

	function setBasicGallery($BasicGal,$strNewRow)
	{
		$BasicGal = str_replace("#FontColor#", $GLOBALS['strFontColor'], $BasicGal);
		$BasicGal = str_replace("#BgColor#", $GLOBALS['strBgColor'], $BasicGal);
		$BasicGal = str_replace("#AltRowColor#", $GLOBALS['strAltRowColor'], $BasicGal);
		$BasicGal = str_replace("#RowColor#", $GLOBALS['strRowColor'], $BasicGal);
		$BasicGal = str_replace("#LinkColor#", $GLOBALS['strLinkColor'], $BasicGal);
		$BasicGal = str_replace("#Title#", $GLOBALS['strTitle'], $BasicGal);
		$BasicGal = str_replace("#Email#", $GLOBALS['strEmail'], $BasicGal);
		$BasicGal = str_replace("#Phone#", $GLOBALS['strPhone'], $BasicGal);
		$BasicGal = str_replace("#RowData#", $GLOBALS['strNewRow'], $BasicGal);
		$BasicGal = str_replace("#HomeURL#", $GLOBALS['listingHome'], $BasicGal);
		$BasicGal = str_replace("#WidgetPath#", $GLOBALS['strWidgetPath'], $BasicGal);
		$BasicGal = str_replace("#Address#", $GLOBALS['strAddress'], $BasicGal);
				
		//---Variables to be replaced for Flyer Gallerey---
		$BasicGal = str_replace("#FlyerImgPathArray#", $GLOBALS['strFlyerImgPathArray'], $BasicGal);
		$BasicGal = str_replace("#FlyerdtaText#", $GLOBALS['strFlyerdtaText'], $BasicGal);
		// user photo
		$BasicGal = str_replace("#ImgLeftTopBG#", $GLOBALS['strPhotoPath'].$GLOBALS['strImgLeftTopBG'], $BasicGal);
		// user logo
		$BasicGal = str_replace("#ImgLeftMiddleBig#", $GLOBALS['strLogoPath'].$GLOBALS['strImgLeftMiddleBig'], $BasicGal);
		$BasicGal = str_replace("#ImgLeftMiddleSmall#", $GLOBALS['strImgPath'].$GLOBALS['strImgLeftMiddleSmall'], $BasicGal);
		$BasicGal = str_replace("#ImgLinkUrl#", $GLOBALS['strImgLinkUrl'], $BasicGal);
		$BasicGal = str_replace("#Image#", $GLOBALS['strImgPath'].$GLOBALS['strImage'], $BasicGal);
		$BasicGal = str_replace("#TxtLink#", $GLOBALS['strTxtLink'], $BasicGal);
		$BasicGal = str_replace("#LinkTextUrl#", $GLOBALS['strLinkTextUrl'], $BasicGal);
		$BasicGal = str_replace("#dtaText#", $GLOBALS['strdtaText'], $BasicGal);
		$BasicGal = str_replace("#ImgFlyerGalImgs#", $GLOBALS['strImgPath'].$GLOBALS['strImgFlyerGalImgs'], $BasicGal);
		$BasicGal = str_replace("#FlyerImgStartCount#", $GLOBALS['strFlyerImgStartCount'], $BasicGal);
		$BasicGal = str_replace("#FlyerImgTotCount#", $GLOBALS['strFlyerImgTotCount'], $BasicGal);
		//------------------------------------------------------
		return $BasicGal;
	}

	// ### end for widgets ###
	
	// return the name of the widget
	function getmyWidgetNames($user_id)
	{
		$myArray	=	array();
		$qry	=	"select * from flyer_data_basic where publish ='Y' and form_id !='0' and active='Y' and user_id=$user_id";
		$row	=	$this->db->get_results($qry);
		for($i=0;$i<count($row);$i++)	{
			$title	=	 $row[$i]->title;
			$myArray[$i]	=	$title;
		}
		return $myArray;
	}
	// return the name of the widget
	function getmyWidgetImages($user_id)
	{
		$myArray	=	array();
		$qry	=	"select * from flyer_data_basic where publish ='Y' and form_id !='0' and active='Y' and user_id=$user_id";
		$row	=	$this->db->get_results($qry);
		for($i=0;$i<count($row);$i++)	{
			$image	=	 $row[$i]->image;
			if($image=="") 
			{	$image="noimage.jpg";	}
			$myArray[$i]	=	$image;
		}
		return $myArray;
	}
	//  Get
	function flyerGetDetails () {
		$user_id		=	$_SESSION["memberid"];
		$sql			= 	"SELECT flyer_id, title FROM flyer_data_basic WHERE user_id=$user_id AND form_id !='0' AND publish='Y' AND active='Y'";
		$rs['flyer_id'] = 	$this->db->get_col($sql, 0);
		$rs['title'] 	= 	$this->db->get_col($sql, 1);
		return $rs;
	}
	
	// return the name of the widget
	function getmyWidgetImagesUrl($user_id,$myaccount_url)
	{
		$myArray	=	array();
		$qry	=	"select * from flyer_data_basic where publish ='Y' and form_id !='0' and active='Y' and user_id=$user_id";
		$row	=	$this->db->get_results($qry);
		for($i=0;$i<count($row);$i++)	{
			$flyer_id	=	 $row[$i]->flyer_id;
			if($myaccount_url!="") {
				$link_name	=	"http://".$myaccount_url.".".DOMAIN_URL."/".$flyer_id."/index.php";
			}
			else { 
			$link_name	=	SITE_URL."/htmlflyers/".$flyer_id.".html";
			}
			$myArray[$i]	=	$link_name;
		}
		return $myArray;
	}
	// return the field for display
	function getmyWidgetFields($user_id)
	{
		$myArray	=	array();
		$qry	=	"select * from flyer_data_basic where publish ='Y' and active='Y' and form_id !='0' and user_id=$user_id";
		$row	=	$this->db->get_results($qry);
		for($i=0;$i<count($row);$i++)	{
			$string		=	"";
			$form_id	=	 $row[$i]->form_id;
			$flyer_id	=	 $row[$i]->flyer_id;
			#-------------------------------------------------
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
					if($field_name != "" && $field_value !="")
					{ 	
						$string	=$string.	"<br /><b>".$field_name.":</b> ".$field_value;
					}
				}
			$myArray[$i]	=	$string;
		}
		return $myArray;
	}
	
	// return the field  for display of a single flyer
	function getmyFlyerFields($flyer_id)
	{
		$myArray	=	array();
		// gstart etting the form id
		$qry_frm	=	"select form_id from flyer_data_basic where flyer_id='$flyer_id'";
		$rs_frm		=	$this->db->get_row($qry_frm,ARRAY_A);
		$form_id	=	$rs_frm['form_id'];	
		// end getting the form id
		
		$qry	=	"select * from flyer_data_basic where publish ='Y' and form_id !='0' and active='Y' and user_id=$user_id";
		$row	=	$this->db->get_results($qry);
		
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
					if($field_name != "" && $field_value !="")
					{ 	
						$string	=$string.	"<br /><b>".$field_name.":</b> ".$field_value;
					}
				}
			//$myArray[0]	=	$string;
		return $string;
	}
	
	
	
	
	
	
	
}

?>