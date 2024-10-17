<?php
/**
* Class Advertiser 
* Author   : Aneesh Aravindan
* Created  : 20/Nov/2007
* Modified : 20/Nov/2007 By Aneesh Aravindan
*/

class Advertiser extends FrameWork {
    
	var 	$errorMessage;
		
    function Advertiser() {
        $this->FrameWork();
    }


    function getConfiguration () {

		$sql		= "SELECT * FROM advertiser_config WHERE editable='Y' AND active='Y'";

		$rs = $this->db->get_results($sql,ARRAY_A);    # ARRAY_A


		if ($rs) {
			for ($i=0; $i<count($rs); $i++) {
				if ($rs[$i]['possible_values']) {
					$var = explode("|", $rs[$i]['possible_values']);
					unset($rs[$i]['possible_values']);
					if ($var) {
						for ($j = 0; $j < count($var); $j++) {
							$temp = explode("^", $var[$j]);
							if(!$temp[1])$temp[1] = $temp[0];
							$k = $temp[0];
							$rs[$i]['possible_values'][$k] = $temp[1];
						}
					}
				}
			}
		}
		return $rs;
    }

    # Update Advertisement Status active,barred,publish

    function updateAdvertisementStatus ($ADVID,$FIELD,$STATUS) {
    	
    	if ( trim($STATUS) && trim($FIELD) && trim($ADVID) )	{
    		$this->db->update("advertiser_master", array($FIELD=>$STATUS), "id='$ADVID'");
    		return true;
    	}else{
    		return false;
    	}
    	
    }
    
     function updateAdvertisementDateStatus ($ADVID,$FIELD,$DATES) {
    	
    	if ( trim($DATES) && trim($FIELD) && trim($ADVID) )	{
    		$this->db->update("advertiser_master", array($FIELD=>$DATES), "id='$ADVID'");
    		return true;
    	}else{
    		return false;
    	}
    	
    }
    
   
    function getConfigurationByfield ( $FIELDVAL="" ) {
		$sql		= "SELECT * FROM advertiser_config WHERE field='$FIELDVAL'";
		$rs		=	$this->db->get_row($sql,ARRAY_A);

		if ( count($rs>0) )
		return $rs;
		else
		return false;
	}
	


	
	function getFlyerIdByAdvertisement(	$ADVID )	{
		
		if ($ADVID >0)	{
			$sql		= "SELECT * FROM advertiser_master WHERE id={$ADVID}";
			$rs		=	$this->db->get_row($sql,ARRAY_A);
			if ( count($rs>0) ) 
			return $rs;
			else
			return false;
		}else {
			return false;
		}
	}
	

	function editConfiguration (&$req) {				
        extract($req);

		foreach ($req as $key=>$val) {
			$this->db->update("advertiser_config", array("value"=>$val), "field='$key'");
		}
		return true;
	}



	# Begin display the advertisement for all user
	function listAdvertiser($keysearch='N',$flyer_search='',$pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy="1")
	{	
		$qry		=	"SELECT advertiser_master.id as aID,advertiser_master.flyer_id,advertiser_master.adv_title,advertiser_master.user_id,advertiser_master.active,member_master.id,member_master.username,flyer_data_basic.flyer_name,flyer_data_basic.flyer_id as fddb_fid FROM advertiser_master".
		" LEFT JOIN member_master ON advertiser_master.user_id = member_master.id ".
		" LEFT JOIN flyer_data_basic ON advertiser_master.flyer_id = flyer_data_basic.album_id";
		
		//if($keysearch=='Y' && $flyer_search)
		//$qry		.=	" and (T1.title LIKE '%$flyer_search%' OR T3.username LIKE '%$flyer_search%' OR
						 //T3.first_name LIKE '%$flyer_search%' OR T3.last_name LIKE '%$flyer_search%' OR T3.email LIKE '%$flyer_search%' )";
		
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output,$orderBy);
			
		return $rs;
	}
	# End display the advertisement for all user


	# Begin display the advertisement for all user
	function listAdvertisementByUser($userID="", $pageNo=0, $limit = 10, $params='', $output=OBJECT, $orderBy="1")
	{	
		include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");
		include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
		include_once(FRAMEWORK_PATH."/modules/album/lib/class.photo.php");
		
		$flyer		=	new	Flyer();
		$album		=	new Album();
		$photo		=	new Photo();	
		
		$qry		=	"SELECT AM.*,AM.adv_budget-(AM.adv_click_total+AM.adv_view_total) as bal_budget, MM.username 
							FROM advertiser_master AM
						INNER JOIN member_master MM ON AM.user_id ={$userID} 
						 	  AND AM.user_id = MM.id AND AM.active = 'Y'";
		
		
		$rs 		= 	$this->db->get_results_pagewise($qry, $pageNo, $limit, $params, $output,$orderBy);

		
		
		
		foreach ($rs as $key=>$RES)	{
			if ( is_array($RES) ) { 
				foreach ($RES as $key1=>$RES_IND)	{
					if( $RES_IND->flyer_id > 0 )	{
						$FLYERID	=	$flyer->getFlyerIDByAlbum($RES_IND->flyer_id);
						//$RES_IND->flyer_id	=	$flyer->getFlyerBasicFormData($FLYERID);
						 $rs[$key][$key1]->flyer_id = $flyer->getFlyerBasicFormData($FLYERID) ;
						 
						 $rsAlbm		  		=   $album->getAlbumDetails( $rs[$key][$key1]->flyer_id['album_id'] );
						 $rsPhoto				=	$photo->imgExtension($rsAlbm["default_img"]);
						 $rs[$key][$key1]->flyer_id['default_img']		=	$rsAlbm["default_img"];
						 $rs[$key][$key1]->flyer_id['default_img_ext']	=	$rsPhoto;
					}
					
					/*$rs[$key1]->bal_budget		=	$RES_IND->adv_click_total + $RES_IND->adv_view_total;
					echo $key1 . "<br>";*/
				} 
			}
		}
		
		return $rs;
	}
	# End display the advertisement for all user

	# Begin Get Advertisement by ID
	function getAdvertisement (	$AID="" )	{
		if ( $AID )	{
					
			//$sql 	=	"SELECT advertiser_master.*,member_master.username FROM advertiser_master INNER JOIN member_master ON  advertiser_master.user_id = member_master.id AND advertiser_master.id = {$AID}"; # AND advertiser_master.flyer_id<=0
			//$sql 	=	"SELECT advertiser_master.*,member_master.username,map_icons.icon_path FROM advertiser_master INNER JOIN member_master,map_icons ON advertiser_master.user_id = member_master.id AND advertiser_master.adv_map_icon=map_icons.id AND advertiser_master.id = {$AID}"; 
			
			$sql 	=	"SELECT advertiser_master. * , member_master.username,map_icons.title as icontitle,map_icons.icon_path,map_icons.icon_spath
						 FROM advertiser_master
						 LEFT JOIN map_icons ON advertiser_master.adv_map_icon = map_icons.id
						 INNER JOIN member_master ON advertiser_master.user_id = member_master.id
						 AND advertiser_master.id ={$AID}
						";

			$rs		=	$this->db->get_row($sql,ARRAY_A); 

					
			if ( count($rs>0) )	{
				
				if ($rs['flyer_id']>0)	{
					include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");
					$flyer			=	new	Flyer();

					$FLYERID	=	$flyer->getFlyerIDByAlbum($rs['flyer_id']);
					$ALB_DETAILS	=	$flyer->getFlyerBasicFormData($FLYERID);

					$rs['flyer_id']	=	$ALB_DETAILS['flyer_id'];
					$rs['album_id']	=	$ALB_DETAILS['album_id'];
					# Begin Appending Album Details to Advertisement Details
					if (!trim($rs['adv_title']))
					$rs['adv_title']	=	$ALB_DETAILS['flyer_name'];
					
					//if (!trim($rs['adv_url']))
					$rs['adv_url']		=	"index.php?mod=flyer&pg=list&act=property_view&details=det&flyer_id={$ALB_DETAILS['flyer_id']}&propid={$ALB_DETAILS['album_id']}";
					
					if (!trim($rs['adv_description']))
					$rs['adv_description']	=	$ALB_DETAILS['description'];
					
					
					//if (!trim($rs['street']))
					$rs['street']	=	$ALB_DETAILS['location_street_address'];
					
					//if (!trim($rs['city']))
					$rs['city']	=	$ALB_DETAILS['location_city'];
					
					//if (!trim($rs['state']))
					$rs['state']	=	$ALB_DETAILS['location_state'];
					
					//if (!trim($rs['country']))
					$rs['country']	=	$ALB_DETAILS['location_country'];
					
					//if (!trim($rs['zip']))
					$rs['zip']	=	$ALB_DETAILS['location_zip'];
					
					# End Appending Album Details to Advertisement Details
					
				}
				
				return $rs;
			}else{
				return false;
			}
			
		}
	}
	# End Get Advertisement by ID

	
	
	
	
	# Function get All Icons	
	function getMapIcons ()	{
		$sql1	=	"SELECT * FROM map_icons WHERE active='Y'";
		$rs = $this->db->get_results($sql1,ARRAY_A);    # ARRAY_A
		return $rs;
	}
	
	# Begin Edit or Add Advertisement Details By Admin
	function editAdvDetailsByAdmin (&$req) {				
        extract($req);

		if(!trim($id)) {
            $message = "Advertisement ID is required";
        } else {


			$array = array("adv_title"=>$adv_title, "adv_description"=>$adv_description, "adv_url"=>$adv_url, "adv_budget"=>$adv_budget, "active"=>$active);
			

			# Checking Image is Changed Or not
			if ($adv_img_name){
				$dir			=	SITE_PATH."/modules/advertiser/images/";
				$file1			=	$dir."thumb/";
				$resource_file	=	$dir.$adv_img_name;
				$path_parts 	= 	pathinfo($adv_img_name);
				$file1_type		=	$path_parts['extension'];
				$array["adv_image"]=$file1_type;
			}

            if($id) {
                $array['id'] = $id;
                $this->db->update("advertiser_master", $array, "id='$id'");
            } 	

			if ($adv_img_name){
				$save_filename	=	$id.".".$path_parts['extension'];

			
				$Adv_Img_Size 	=   $this->getConfigurationByfield('adv_img_size');


				if ($Adv_Img_Size['value'])
				$Adv_Img_Size	=	explode ( "," , $Adv_Img_Size['value']);


				if ( $Adv_Img_Size[0]>0 && $Adv_Img_Size[1]>0 )
				{
					_upload($dir,$save_filename,$adv_imgtmpname,1, $Adv_Img_Size[0], $Adv_Img_Size[1]);
				}
				else
				{
					_upload($dir,$save_filename,$adv_imgtmpname,1);
				}

			}


			return true;
        }
        return $message;
	}
	# End Edit Advertisement Details By Admin



	function addAlbumAdvertisement (&$req) {				
        extract($req);

		$qry	=	"SELECT * FROM advertiser_master WHERE flyer_id=$propid";
		$rs		=	$this->db->get_row($qry, ARRAY_A);
		if($rs) {
			return array(false,$rs['id']);
		} else {
			
			$SQLFLYER	=	"SELECT * FROM flyer_data_basic WHERE album_id = {$propid}";
			$rsFLYER	=	$this->db->get_row($SQLFLYER, ARRAY_A);
		
			$array['flyer_id'] 			= $propid;
			$array['user_id']  			= $userID;
			
			$array['adv_title']  		= $rsFLYER['flyer_name'];
			$array['adv_description']	= $rsFLYER['description'];
			$array['adv_url']  			= "index.php?mod=flyer&pg=list&act=property_view&details=det&flyer_id={$rsFLYER['flyer_id']}&propid={$rsFLYER['album_id']}";
			
			$rsCONFIG					=	$this->getConfigurationByfield('click_price');
			$array['adv_click_price']   = 	$rsCONFIG['value'];
			
			
			$rsCONFIG					=	$this->getConfigurationByfield('view_price');
			$array['adv_view_price']    = 	$rsCONFIG['value'];
			
			$array['date_added']   		= 	date("Y:m:d g:i:s");
			
			$this->db->insert("advertiser_master", $array);
			$id = $this->db->insert_id;
			return array(true,$id);
		}
	}


	# Begin Edit or Add Advertisement Details By User
	function editAdvDetailsByUser (&$req) {				
        extract($req);
        
        
        
		if(!trim($adv_title)) {
            $message = $this->MOD_VARIABLES['MOD_ERRORS']['ERR_ADV_TITLE'];  
		} elseif (!trim($adv_url)) {
			$message = $this->MOD_VARIABLES['MOD_ERRORS']['ERR_ADV_URL'];  
        } elseif (!trim($adv_budget)) {
			$message = $this->MOD_VARIABLES['MOD_ERRORS']['ERR_ADV_BUDGET'];  
        } else {


			$array = array("user_id"=>$userID,"adv_title"=>$adv_title, "adv_description"=>$adv_description, "adv_url"=>$adv_url,"adv_budget"=>$adv_budget,"adv_daily_budget"=>$adv_daily_budget);
			
			# SET CONFIG DETAILS
			$rsCONFIG					=	$this->getConfigurationByfield('click_price');
			$array['adv_click_price']   = 	$rsCONFIG['value'];
			
			$rsCONFIG					=	$this->getConfigurationByfield('view_price');
			$array['adv_view_price']    = 	$rsCONFIG['value'];
			# SET CONFIG DETAILS
			$array['date_added']   		= 	date("Y:m:d g:i:s");

			# Checking Image is Changed Or not 
			if ($adv_img_name){
				$dir			=	SITE_PATH."/modules/advertiser/images/";
				$file1			=	$dir."thumb/";
				$resource_file	=	$dir.$adv_img_name;
				$path_parts 	= 	pathinfo($adv_img_name);
				$file1_type		=	$path_parts['extension'];
				$array["adv_image"]=$file1_type;
			}
	
            if($id) {
                $array['id'] = $id;
                $this->db->update("advertiser_master", $array, "id='$id'");
            } else {
            	 $array['flyer_id'] = 0;
				 $this->db->insert("advertiser_master", $array);
				 $id = $this->db->insert_id;
			} 	

			if ($adv_img_name){
				$save_filename	=	$id.".".$path_parts['extension'];

				$Adv_Img_Size 	=   $this->getConfigurationByfield('adv_img_size');
				if ($Adv_Img_Size['value'])
				$Adv_Img_Size	=	explode ( "," , $Adv_Img_Size['value']);


				if ( $Adv_Img_Size[0]>0 && $Adv_Img_Size[1]>0 )
				{
					_upload($dir,$save_filename,$adv_imgtmpname,1,$Adv_Img_Size[0],$Adv_Img_Size[1]);
				}
				else
				{
					_upload($dir,$save_filename,$adv_imgtmpname,1);
				}
			}


			return $id;
        }
        return $message;
	}
	# End Edit Advertisement Details By User


	
	# Begin Edit or Add Advertisement Criteria Details By User
	function editAdvCriteriaDetailsByUser (&$req) {				
        extract($req);
		if( !trim($city) ) {
			$message = $this->MOD_VARIABLES['MOD_ERRORS']['ERR_ADV_LOC_CITY'];        
		} elseif (!trim($country)) {
			$message = $this->MOD_VARIABLES['MOD_ERRORS']['ERR_ADV_LOC_COUNTRY'];     
        } elseif (!trim($zip)) {
			$message = $this->MOD_VARIABLES['MOD_ERRORS']['ERR_ADV_LOC_ZIP'];    
        } else {
			$array = array("adv_map_icon"=>$adv_map_icon, "street"=>$street, "city"=>$city, "state"=>$state, "country"=>$country, "zip"=>$zip);


			# Checking Image is Changed Or not 
			if ($custom_icon_name){
				$dir			=	SITE_PATH."/modules/advertiser/icons/";
				$file1			=	$dir."thumb/";
				$resource_file	=	$dir.$custom_icon_name;
				$path_parts 	= 	pathinfo($custom_icon_name);
				$file1_type		=	$path_parts['extension'];
				$array["custom_icon"]      =	$file1_type;
			}
			# Checking Image is Changed Or not 
			if ($custom_sicon_name){
				$dir			=	SITE_PATH."/modules/advertiser/icons/";
				$file1			=	$dir."thumb/";
				$resource_file	=	$dir.$custom_sicon_name;
				$path_parts 	= 	pathinfo($custom_icon_name);
				$file1_type		=	$path_parts['extension'];
				$array["custom_sicon"]     =	$file1_type;
			}

	        if($id) {
                $array['id'] = $id;
                $this->db->update("advertiser_master", $array, "id='$id'");
            } 			


			if ($custom_icon_name){
				$save_filename	=	$id.".".$path_parts['extension'];
				_upload($dir,$save_filename,$custom_icontmpname,1,32,32);
				
			}

			if ($custom_sicon_name){
				$save_filename	=	$id."s.".$path_parts['extension'];
				_upload($dir,$save_filename,$custom_sicontmpname,1,32,32);
			}

			return true;
        }
        return $message;
	}
	# End Edit or Add Advertisement Criteria Details By User


	
	
	
	
	
	# Begin Image Deleting
	function RemoveImage($adv_id,$field)	{

		if($adv_id>0)	{
			$extension	=	$this->GetImageExtenstion($adv_id,$field);
			switch ($field)	{
				case "adv_image":
	
					$image_path		=	SITE_PATH."/modules/advertiser/images/".$adv_id.".".$extension;

					if(file_exists($image_path))
					unlink($image_path);

					$image_path		=	SITE_PATH."/modules/advertiser/images/thumb/".$adv_id.".".$extension;
					if(file_exists($image_path))
					unlink($image_path);
				break;
				case "custom_icon":
	
					$image_path		=	SITE_PATH."/modules/advertiser/icons/".$adv_id.".".$extension;

					if(file_exists($image_path))
					unlink($image_path);

					$image_path		=	SITE_PATH."/modules/advertiser/icons/thumb/".$adv_id.".".$extension;
					if(file_exists($image_path))
					unlink($image_path);
				break;
				case "custom_sicon":
	
					$image_path		=	SITE_PATH."/modules/advertiser/icons/".$adv_id."s.".$extension;

					if(file_exists($image_path))
					unlink($image_path);

					$image_path		=	SITE_PATH."/modules/advertiser/icons/thumb/".$adv_id."s.".$extension;
					if(file_exists($image_path))
					unlink($image_path);
				break;
		}
			$arr	=	array($field=>"");
			$this->db->update("advertiser_master", $arr, "id='$adv_id'");
			return true;
		}
		return false;
	}

	function GetImageExtenstion($adv_id,$field)	{
		$qry	=	"SELECT $field AS field FROM advertiser_master WHERE id=$adv_id";
		$rs		=	$this->db->get_row($qry, ARRAY_A);
		if($rs)
			return $rs['field'];
		else
			return 0;
	}	
	# End Image Deleting


	#  Begin ADD DELETING
	function DeleteAdvertisement ($adv_id="")	{
		if ($adv_id>0)	{
			$this->db->query("DELETE FROM advertiser_master  WHERE  id='$adv_id'");
			$this->RemoveImage ($adv_id,"adv_image");
			return true;
		}
	}
	#  End ADD DELETING

	function totalAdvertisementByUser ( $user_ID = "" )	{
		if ( trim ($user_ID) )	{
			$qry	=	"SELECT count(*) as totalAdv FROM advertiser_master WHERE user_id = {$user_ID} AND active = 'Y'";
			$rs		=	$this->db->get_row($qry, ARRAY_A);
			if($rs)
				return $rs['totalAdv'];
			else
				return false;
		}
	}


	/**
	* * @Author   : Aneesh
	* @Created  : 30/Nov/2007
	Update Map Position in album_map_position
	*/
	function UpdateAdvPositionMap ($req) {
		extract ($req);	

		if ($id>0)	{
			$SQL1	=	"SELECT count(*) as numAdv FROM advertiser_master WHERE id={$id}";
			$rs  = $this->db->get_row($SQL1,ARRAY_A);
			if ($rs['numAdv'] != 0) {	# Update
				$PosArray=array("lat_lon"=>$latlon,"zoom"=>$currZoom);
				$this->db->update("advertiser_master", $PosArray, "id='$id'");
				return true;
			}else{
				return false;
			}
			
		}
		return false;
	}

	
	/**
	* * @Author   : Aneesh
	* @Created  : 30/Nov/2007
	Update Map Position in album_map_position if Latitude and Lontitude are blank when page load
	*/
	function UpdateAdvPositionMaponLoad ($req) {
		extract ($req);	

		if ($id>0)	{
			$SQL1	=	"SELECT lat_lon FROM advertiser_master WHERE id={$id}";
			$rs  = $this->db->get_row($SQL1,ARRAY_A);
			
			if ( trim($rs['lat_lon'])=="" ) {	# If  exist
				$PosArray=array("lat_lon"=>$latlon,"zoom"=>$currZoom);
				$this->db->update("advertiser_master", $PosArray, "id='$id'");
				return true;
			}else{
				return false;
			}
			
		}
		return false;
	}

	/**
	* * @Author   : Aneesh
	* @Created  : 4/Apr/2008
	Update Map Position in album_map_position
	*/
	function CheckAdvPositionMapCustom ($req) {
		extract ($req);	

		if ($id>0)	{
			$SQL1	=	"SELECT lat_lon FROM advertiser_master WHERE id={$id}";
			$rs  = $this->db->get_row($SQL1,ARRAY_A);
			if ( trim($rs['lat_lon']) ) {	# If  exist
				return true;
			}
			return false;
		}
		return false;
	}
	
		
	/* FUNCTION GET TOTAL COST OF ADVERT,CLICK PRICE(DETAILS VIEW) ,VIEW PRICE */
	
	function getTotalCostOfAdvertisement ($ADVID) {
		/* Click VIew Total Cost */
		$SQL1	=	"SELECT (adv_view_price*adv_view_total) as VIEWTOT FROM advertiser_master
						WHERE id = {$ADVID}";

		$RS  = $this->db->get_row($SQL1,ARRAY_A);
		
		if ($RS) {
			$VIEW_TOTAL	=	$RS['VIEWTOT'];
		}else{
			$VIEW_TOTAL	=	0;
		}
		/* Click VIew Total Cost */
		
		/* Click Detail VIew Total Cost */
		$SQL1	=	"SELECT (adv_click_price*adv_click_total) as DETVIEWTOT FROM advertiser_master
						WHERE id = {$ADVID}";
		$RS  = $this->db->get_row($SQL1,ARRAY_A);
		
		if ($RS) {
			$DET_VIEW_TOTAL	=	$RS['DETVIEWTOT'];
		}else{
			$DET_VIEW_TOTAL	=	0;
		}
		/* Click Detail VIew Total Cost */
		
		return array($VIEW_TOTAL,$DET_VIEW_TOTAL);
	}
	
	/* FUNCTION GET TOTAL COST OF ADVERTISEMENT */
	
	
	
	
	/* FUNCTION GET CLICK COUNT AND VIEW COUNT OF A DAY  */
	function getDailyCostByAdvertisement ($ADVID) {
		
		$CURRDATE	=	date("Y:m:d");
		
		$SQL1 = "SELECT COUNT(*) as VIEWCOUNT  FROM advertiser_manage 
					WHERE date_added = '$CURRDATE' AND adv_view = 1";
	
		$RS  = $this->db->get_row($SQL1,ARRAY_A);
		if ($RS) {
			$VIEW_COUNT	=	$RS['VIEWCOUNT'];
		}else{
			$VIEW_COUNT	=	0;
		}
		
		$SQL1 = "SELECT COUNT(*) as DETVIEWCOUNT  FROM advertiser_manage 
					WHERE date_added = '$CURRDATE' AND adv_click = 1";
		$RS  = $this->db->get_row($SQL1,ARRAY_A);
		if ($RS) {
			$DET_VIEW_COUNT	=	$RS['DETVIEWCOUNT'];
		}else{
			$DET_VIEW_COUNT	=	0;
		}
		
		return array($VIEW_COUNT,$DET_VIEW_COUNT);
		
	}
	/* FUNCTION GET COST PER DAY  */
	
	
	/**
	* * @Author   : Aneesh
	* @Created  : 4/Apr/2008
	Update Advertisement Click Details 				:: Listing Add
	*/
	function CheckAdvClickManage ($ADVID) {
		if ($ADVID>0)	{
			
		
			#	Check if Session Already Exist	#	ip_address  $_SERVER['REMOTE_ADDR'];
			$SESSID		=	session_id();
			$IPADDRESS	=	$_SERVER['REMOTE_ADDR'] . date("s");
			
			/*$SQL1	=	"SELECT * FROM advertiser_manage 
							WHERE adv_id = {$ADVID} AND (session_id = '$SESSID' OR ip_address = '$IPADDRESS')  AND adv_click !=0 ";*/
			$SQL1	=	"SELECT * FROM advertiser_manage 
							WHERE adv_id = {$ADVID} AND (ip_address = '$IPADDRESS')  AND adv_view !=0 ";
			$rs1  	= 	 $this->db->get_row($SQL1,ARRAY_A);
			
			if ( count($rs1)>0 )
			return false;   
			#	Check if Session Already Exist	#
			
				
			
			#	GET ADVERTISEMENT DETAILS	#
			$SQL1	=	"SELECT * FROM advertiser_master 
							WHERE id = {$ADVID} AND active = 'Y' AND publish = 'Y' AND barred = 'N'";
			$rs1  	= 	 $this->db->get_row($SQL1,ARRAY_A);
			
			if ( count($rs1)<1 )
			return false;
			# 	GET ADVERTISEMENT DETAILS	#
			
		
			
			#	SET TOTAL BUDGET AND DAILLY BUDGET
			$TOTAL_BUDGET	=	$rs1['adv_budget'];
			$DAILY_BUDGET	=	$rs1['adv_daily_budget'];
			$CLICK_PRICE	=	$rs1['adv_view_price'];	
			$VIEW_PRICE		=	$rs1['adv_click_price'];	
			#$CURRENT_GRP	=	$rs1['current_group'];
			#	SET TOTAL BUDGET AND DAILLY BUDGET	
			
			
						
			
			
			#	CHECK TOTAL BUDGET EXCEED	#
			list($VIEW_TOTAL,$DET_VIEW_TOTAL)	=	$this->getTotalCostOfAdvertisement($ADVID);
			
			if ( $TOTAL_BUDGET <= ($VIEW_TOTAL + $DET_VIEW_TOTAL + $CLICK_PRICE) ) {
				if ( $this->updateAdvertisementStatus( $ADVID,'publish','N') ) { 
					#$this->db->query("UPDATE advertiser_master SET current_group=current_group+1 where id={$ADVID}");
					$this->updateAdvertisementDateStatus( $ADVID,'date_published',date("Y:m:d g:i:s"));
				}
			}
			#	CHECK TOTAL BUDGET EXCEED	#
			
						
			#	CHECK DAILY BUDGET EXCEED 	#

			if ($DAILY_BUDGET>0)	{
				list($VIEW_COUNT,$DET_VIEW_COUNT)	=	$this->getDailyCostByAdvertisement($ADVID);
				$CURR_DAILY_COST	=	($VIEW_COUNT * $CLICK_PRICE) + ($DET_VIEW_COUNT * $VIEW_PRICE);
				$CURR_DAILY_COST	= 	$CURR_DAILY_COST + $CLICK_PRICE;
				
				if ( $DAILY_BUDGET<=$CURR_DAILY_COST ) {
					if ( $this->updateAdvertisementStatus( $ADVID,'barred','Y') )
					$this->updateAdvertisementDateStatus( $ADVID,'date_barred',date("Y:m:d"));
				}
			}
			#	CHECK DAILY BUDGET EXCEED	#
			
			
			
			
			
			# INSERT advertiser_manage #
			$array['adv_id'] 		= $ADVID;
			$array['session_id']  	= $SESSID;
			$array['ip_address']  	= $IPADDRESS;
			#$array['group_id']  	= $CURRENT_GRP;
			$array['adv_view']  	= 1;
			$array['user_id']  		= $_SESSION['memberid'];
			$array['date_added']  	= date("Y:m:d");	#date("Y:m:d g:i:s");
			$this->db->insert("advertiser_manage", $array);
			$id = $this->db->insert_id;
			
			# Update Total List Cost #
			$this->db->query("UPDATE advertiser_master SET adv_view_total=adv_view_total+$CLICK_PRICE where id={$ADVID}");
			# Update Total List Cost #
			
			return true;
			# INSERT advertiser_manage #
			
			
		}
	}
	
	
	
	
	
/**
	* * @Author   : Aneesh
	* @Created  : 4/Apr/2008
	Update Advertisement Click Details 		:: Viwing Details Page 
	*/
	function CheckAdvViewManage ($ADVID) {
		if ($ADVID>0)	{
		
			#	GET ADVERTISEMENT DETAILS	#
			$SQL1	=	"SELECT * FROM advertiser_master 
							WHERE id = {$ADVID} AND active = 'Y' AND publish = 'Y' AND barred = 'N'";
			$rsDet 	= 	 $this->db->get_row($SQL1,ARRAY_A);
			
			if ( count($rsDet)<1 )
			return false;
			
			
			
			$ADVURL	=	$rsDet['adv_url'];
			
			if ( trim($ADVURL) == "" )
			return false;
			# GET ADVERTISEMENT DETAILS	#
			
			
			
			#	Check if Session Already Exist	#
			$SESSID		=	session_id();
			$IPADDRESS	=	$_SERVER['REMOTE_ADDR']. date("s");
			/*$SQL1	=	"SELECT * FROM advertiser_manage 
							WHERE adv_id = {$ADVID} AND (session_id = '$SESSID' OR ip_address = '$IPADDRESS')  AND adv_click !=0 ";*/
			$SQL1		=	"SELECT * FROM advertiser_manage 
								WHERE adv_id = {$ADVID} AND (ip_address = '$IPADDRESS')  AND adv_click !=0 ";
			$rs1  		= 	 $this->db->get_row($SQL1,ARRAY_A);
			
			
			if ( count($rs1)>0 )
			return $ADVURL;   
			#	Check if Session Already Exist	#
			
								
			
						
			
			#	SET TOTAL BUDGET AND DAILLY BUDGET
			$TOTAL_BUDGET	=	$rsDet['adv_budget'];
			$DAILY_BUDGET	=	$rsDet['adv_daily_budget'];
			$CLICK_PRICE	=	$rsDet['adv_view_price'];	
			$VIEW_PRICE		=	$rsDet['adv_click_price'];
			#$CURRENT_GRP	=	$rsDet['current_group'];
			#	SET TOTAL BUDGET AND DAILLY BUDGET	
			
			
			#	CHECK TOTAL BUDGET EXCEED	#
			list($VIEW_TOTAL,$DET_VIEW_TOTAL)	=	$this->getTotalCostOfAdvertisement($ADVID);
			if ( $TOTAL_BUDGET <= ($VIEW_TOTAL + $DET_VIEW_TOTAL + $VIEW_PRICE) ) {
				if ( $this->updateAdvertisementStatus( $ADVID,'publish','N') ) {
					#$this->db->query("UPDATE advertiser_master SET current_group=current_group+1 where id={$ADVID}");
					$this->updateAdvertisementDateStatus( $ADVID,'date_published',date("Y:m:d g:i:s"));
				}
			}
			#	CHECK TOTAL BUDGET EXCEED	#
			
						
			#	CHECK DAILY BUDGET EXCEED 	#

			if ($DAILY_BUDGET>0)	{
				list($VIEW_COUNT,$DET_VIEW_COUNT)	=	$this->getDailyCostByAdvertisement($ADVID);
				$CURR_DAILY_COST	=	($VIEW_COUNT * $CLICK_PRICE) + ($DET_VIEW_COUNT * $VIEW_PRICE);
				$CURR_DAILY_COST	= 	$CURR_DAILY_COST + $VIEW_PRICE;
				
				if ( $DAILY_BUDGET<=$CURR_DAILY_COST ) {
					if ( $this->updateAdvertisementStatus( $ADVID,'barred','Y') )
					$this->updateAdvertisementDateStatus( $ADVID,'date_barred',date("Y:m:d"));
				}
			}
			#	CHECK DAILY BUDGET EXCEED	#
			
			
			
			# INSERT advertiser_manage #
			$array['adv_id'] 		= $ADVID;
			$array['session_id']  	= $SESSID;
			$array['ip_address']  	= $IPADDRESS;
			#$array['group_id']  	= $CURRENT_GRP;
			$array['adv_click']  	= 1;
			$array['user_id']  		= $_SESSION['memberid'];
			$array['date_added']  	= date("Y:m:d");
			$this->db->insert("advertiser_manage", $array);
			$id = $this->db->insert_id;
			
			# Update Total Details Cost #
			$this->db->query("UPDATE advertiser_master SET adv_click_total=adv_click_total+$VIEW_PRICE where id={$ADVID}");
			# Update Total Details Cost #
			
			return $ADVURL;
			# INSERT advertiser_manage #
			
			
		}
	}

	
	
	
	/**
* Class Advertiser 
* Author   : 
* Created  : 20/Nov/2007
* Modified : 28/Apr/2008 By Vipin
*/
	
	
	
	function saveAdvertisementInfo($POST, $AdvId, $MemberId, $PaymentObj)
	{
		
		$LogFileName	=	SITE_PATH.'/tmp/logs/'.'adv_paypal_'.date('Y').date('m').'.log';

		$req = 'cmd=_notify-validate';
		foreach ($POST as $key => $value) {
			$value = urlencode(stripslashes($value));
			$req .= "&$key=$value";
		}
		$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
		#$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
		$fp = fsockopen ($this->config['paypal_socket_address'], 80, $errno, $errstr, 30);
						
		$ItemName			=	$POST['item_name'];
		$PaymentStatus		=	$POST['payment_status'];
		$PaymentAmount		=	$POST['mc_gross'];
		$TransactionId		=	$POST['txn_id'];
		$ReceiverMail		=	$POST['receiver_email'];
		$PayerMail			=	$POST['payer_email'];
		
		
		if (!$fp) {
			;
		} else {
			fputs ($fp, $header . $req);
			while (!feof($fp)) {
				$res	= 	fgets ($fp, 1024);
				if (strcmp ($res, "VERIFIED") == 0) {
					$fpp = fopen($LogFileName, "a+");
					fwrite($fpp, $_SESSION['memberid']."VERIFIED:payment_status:".$PaymentStatus."|"."txn_id:".$TransactionId."|"."payment_amount:".$PaymentAmount."|".$ItemName." - ".date("d-m-Y H:i:s")."\n".$req."\n");
					fclose($fpp);
					
					if($PaymentStatus == 'Completed') {
						
						$ADVARR				=	$this->getAdvertisement($AdvId);
						$CurrentTime		=	date('Y-m-d H:i:s');
						
						$UpdateArray		=	array('publish'=>'Y');
						$this->db->update('advertiser_master',$UpdateArray,"id=$AdvId");
						
					/*	$TransInsArray	=	array(
												'user_id'			=>	$MemberId,
												'adv_id'			=>  $AdvId, 
												'transaction_id'	=>	$TransactionId,
												'transaction_date'	=>	$CurrentTime,
												'transaction_amount'=>	$PaymentAmount,
												'transaction_details'=>	$ItemName,
												);
						$this->db->insert('advertiser_payment', $TransInsArray);
						
					*/	
					$InsArray	=	array(		'album_id'		=>	$AdvId, 
												'created_time'	=>	$CurrentTime,
												'invoice_amount'=>	$PaymentAmount,
												'invoice_type'  =>  'ADVERTISEMENT'
												);
					$this->db->insert('invoices', $InsArray);
						$TransInsArray	=	array(
												'member_id'			=>	$MemberId,
												'sentto_id'			=>	'NULL',
												'transaction_date'	=>	$CurrentTime,
												'trans_description'	=>	'ADVERTISEMENT',
												'trans_amount'		=>	$PaymentAmount,
												'transaction_id'	=>	$TransactionId
											);
						$this->db->insert('member_transactiondetails', $TransInsArray);
						# Clear Old Details From History , advertiser_manage
											
								
						if ( $AdvId>0 ) {
						$this->db->query("DELETE FROM advertiser_manage WHERE adv_id={$AdvId}");
						}
				
						return TRUE;
						
					}#Close if Completed
					
				} else if (strcmp ($res, "INVALID") == 0) {
					$fpp = fopen($LogFileName, "a+");
					$PostStr	=	var_export($POST, true)."\n".date('Y-m-d H:i:s')."\n".$res."\n";
					fwrite($fpp, $PostStr);
					fclose($fpp);
				}
			}
			fclose ($fp);
		}
	}
	
/**
*  Author   :Vipin 
* Created  : 28/Apr/2008 By Vipin
* Modified : 28/Apr/2008 By Vipin
*/
	function advertisement_publish_mail($adv_id,$MemberId)
	{		
		//$fpp = fopen($LogFileName, "a+");
		//fwrite($fpp, $adv_id."advid:memberid:".$MemberId);
		//fclose($fpp);
		$LogFileName	=	SITE_PATH.'/tmp/logs/test.txt';
		include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
		include_once(FRAMEWORK_PATH."/modules/member/lib/class.email.php");
		$user           =   new User();
		$emailInfo		=   new Email();
		$advertise_detail = $this->getAdvertisement($adv_id);
		
		$fpp = fopen($LogFileName, "a+");
		fwrite($fpp, "ADV: ".$adv_id . "Mem: ".$MemberId  );
		fclose($fpp);
		
		//sending mail to advertiser
		$member         =   $user->getUserDetails($MemberId);
		$mail_header = array();
		$mail_header['from'] 	= 	"vipina2z@gmail.com";
		$mail_header["to"]      =   "vipina2z@gmail.com";
		$dynamic_vars = array();
		$dynamic_vars['FIRST_NAME']  	= $member['name'].$member['email'];
		$dynamic_vars['ADVERTISEMENT_TITLE'] = $advertise_detail['adv_title'];		
		$dynamic_vars['ADV_DESCRIPTION']= $advertise_detail['adv_description'];
		$dynamic_vars['ADV_URL']		= $advertise_detail['adv_url'];
		$dynamic_vars['DATE_PUBLISHED']	= $advertise_detail['date_published'];
		$dynamic_vars['ADV_CLICK_PRICE']= $advertise_detail['adv_click_price'];
		$dynamic_vars['ADV_VIEW_PRICE']	= $advertise_detail['adv_view_price'];
		
		
		//$dynamic_vars['PROPERTY_NAME']=$content;
		$emailInfo->send("Advertisement_publish_confirmation",$mail_header,$dynamic_vars);
		
	}
	/** This function will return total clicks and views of all dates
*  Author   :Vipin 
* Created  : 28/Apr/2008 By Vipin
* Modified : 28/Apr/2008 By Vipin
*/
	function get_adv_date_info($adv_id,$start_date='',$end_date='')
	{
		$Qry = "SELECT T1.adv_title,T2.id,T2.date_added, sum(T2.adv_click) AS adv_click,sum(T2.adv_view ) AS adv_view,T1.adv_click_price,T1.adv_view_price,(
sum( T2.adv_view ) * T1.adv_view_price
) + ( sum( T2.adv_click ) * T1.adv_click_price ) AS total
			FROM `advertiser_master` AS T1, advertiser_manage AS T2
			WHERE T1.id = T2.adv_id
			AND T1.current_group = T2.group_id
			AND T1.id = '$adv_id'";
		if ($start_date!="" and $end_date!="") 	
			$Qry .= "AND T2.date_added between '$start_date' and '$end_date'";
			
		$Qry.= "GROUP BY T2.date_added";	
		
		$rs = $this->db->get_results($Qry,ARRAY_A);
		 //$rs = $this->db->get_results_pagewise($Qry, $pageNo, $limit, $params, $output,$orderBy='');
		return $rs;
	}
	/** This function is to return all the clicks and views of a particular date
*  Author   :Vipin 
* Created  : 28/Apr/2008 By Vipin
* Modified : 28/Apr/2008 By Vipin
*/
	function get_adv_ip_info($adv_id,$date_added)
	{
		 $Qry = "SELECT T2.ip_address, sum( T2.adv_click ) AS adv_click,
		  sum( T2.adv_view ) AS adv_view, 
		  (T1.adv_click_price*sum(T2.adv_click)) as total_click_price,
		  (T1.adv_view_price*sum(T2.adv_view)) as total_view_price,T2.user_id, T3.first_name,T3.last_name
			FROM `advertiser_master` AS T1, advertiser_manage AS T2, member_master AS T3
			WHERE T1.id = T2.adv_id
			AND T1.current_group = T2.group_id
			AND T1.id ='$adv_id'
			AND T2.date_added = '$date_added' AND (T1.user_id = T3.id or T1.user_id = 0) 
			GROUP BY T2.ip_address";	
		$rs = $this->db->get_results($Qry,ARRAY_A);
		return $rs;
	}
	
}

?>