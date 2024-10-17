<?php
/**
* Class Album Search 
* Author   : Aneesh Aravindan
* Created  : 20/Nov/2007
* Modified : 20/Nov/2007 By Aneesh Aravindan
*/

class Search extends FrameWork {
    
	var	$errorMessage;
		
    function Search() {
        $this->FrameWork();
    }

	
	#  get Search Destinations	
	function getDestination ($serchKey,$limits )	{
		$sql1	=	"SELECT distinct(location_city) FROM flyer_data_contact WHERE location_city LIKE '$serchKey%' LIMIT $limits";
		$rs = $this->db->get_results($sql1,ARRAY_A);    # ARRAY_A
		$res = array();
		foreach ($rs as $newRs){
			$res[] = $newRs['location_city'];
		}
		return $res;
	}
	
	
	#  get Search Destinations	
	function getDestinationMultiple ($serchKey)	{
		
			
		$sql11	=	"SELECT location_city FROM flyer_data_contact WHERE location_city LIKE '$serchKey%'";
		$rs = $this->db->get_results($sql11,ARRAY_A);    # ARRAY_A
		$res 		= array();
		$firstVal 	= "";
		
		foreach ($rs as $newRs){
			if (!in_array( strtoupper($newRs['location_city']),$res) ) {
				$res[ trim($newRs['location_city']) ] = strtoupper($newRs['location_city']);
				
				if ( trim($firstVal)=="" )
				$firstVal	=	$newRs['location_city'];
			}
			
		}
		
		
		return array($res,$firstVal);
	}
	
	
	
	# Function get All Amenties Group	 
	function getAmentiesGroup ()	{
		$sql1	=	"SELECT * FROM flyer_form_attribute_groups WHERE active='Y'";
		$rs = $this->db->get_results($sql1,ARRAY_A);    # ARRAY_A
		return $rs;
	}
	
	
	# Function get All Property Type
	function getPropertyType ()	{
		$sql1	=	"SELECT * FROM flyer_form_master WHERE active='Y'";
		$rs = $this->db->get_results($sql1,ARRAY_A);    # ARRAY_A
		return $rs;
	}
	
	
	function parseToXML($htmlStr) 
	{ 
	$xmlStr=str_replace('<','&lt;',$htmlStr); 
	$xmlStr=str_replace('>','&gt;',$xmlStr); 
	$xmlStr=str_replace('"','&quot;',$xmlStr); 
	$xmlStr=str_replace("'",'&#39;',$xmlStr); 
	$xmlStr=str_replace("&",'&amp;',$xmlStr); 
	return $xmlStr; 
	} 
	
	function getCordinatesByAlbumId($albm_id=0,$address="")	{
		#include_once(FRAMEWORK_PATH."/modules/map/lib/G_Maps.class.php");
	    #$gmap	 		= 	 new G_Maps();
		if ( $albm_id>0 ) {
			$sql = "SELECT * FROM  album_map_position where album_id='$albm_id'";
       		$rs  = $this->db->get_row($sql, ARRAY_A);
       		if (count($rs)) {
       		$retVal = explode(",",substr(trim($rs['lat_lon']),1,strlen(trim($rs['lat_lon']))-2));
       		return $retVal;
       		}/*else{
       			$latlon = $gmap->geoGetCoords($address);
       			if($latlon!=false)
       			$retVal = explode(",",trim($latlon));
       			return $retVal;
       		}*/
		}
		return false;
	}
	
	function makeXmlMapList ($rs) {
		
		//header("Content-type: text/xml");
		$strXML = "";
		// Start XML file, echo parent node
		$centerPoint ='';

		$strXML = '<markers>';
		
		// Iterate through the rows, printing XML nodes for each
		foreach ($rs as $res){
		  
		  // ADD TO XML DOCUMENT NODE
		  #$strCoord = $this->getCordinatesByAlbumId($res->album_id,$res->location_zip);
		  $strCoord = explode(",",substr(trim( $res->lat_lon ),1,strlen(trim( $res->lat_lon ))-2));	
		  
		  if ( $strCoord[0]>0 && $centerPoint=='')
		  $centerPoint = $strCoord[0] . ',' . $strCoord[1];
		  		  
		  $strXML .= '<marker ';
		  $strXML .= 'name="' . $this->parseToXML( trim($res->flyer_name) ) . '" ';
		  $strXML .= 'address="' . $this->parseToXML( trim($res->location_city) . " " . trim($res->location_country) ) . '" ';
		  $strXML .= 'lat="' . $strCoord[0] . '" ';
		  $strXML .= 'lng="' . $strCoord[1] . '" ';
		  $strXML .= 'type="' .$this->parseToXML( trim($res->flyer_name) ) . '" ';
		  $strXML .= '/>';
		}
		
		// End XML file
		$strXML .= '</markers>';

			

		return array($strXML,$centerPoint);
	}
	
	
	
	
	function makeXmlMapAdvList ($rs) {
		
		
		/* Check If Record Exist */
		if ( count($rs) < 1 ) {
			return false;
		}
		/* Check If Record Exist */
		
		/* Get Location Information of Properties */
		$LcationCityArr		=	array();
		$LcationStateArr	=	array();
		foreach ($rs as $res){
			if ( !in_array( "'".trim($res->location_city)."'", $LcationCityArr) && trim($res->location_city) ) { $LcationCityArr[]	=  "'".trim($res->location_city)."'"; }
			if ( !in_array( "'".trim($res->location_state)."'", $LcationStateArr) && trim($res->location_state) ) { $LcationStateArr[]	=  "'".trim($res->location_state)."'"; }
		}
		
		$LcationCityArr		=	implode(",",$LcationCityArr);
		$LcationStateArr	=	implode(",",$LcationStateArr);
			
		
		/* Get Location Information of Properties */
		
		if ( trim($LcationCityArr) == "" )
		return false;
		
		/*
		# UPDATE BARRED ADVERTISEMENTS
		$SQLUPDATE	=	"UPDATE advertiser_master SET barred = 'N' WHERE date_barred < '".date("Y:m:d")."'";
		$this->db->query($SQLUPDATE);
		# UPDATE BARRED ADVERTISEMENTS
		*/
				
		/* Fetch Advertisement Near Location */
		$SQLADV	=	"SELECT advertiser_master.*,map_icons.title as icontitle,map_icons.icon_path,map_icons.icon_spath
						FROM advertiser_master
						LEFT JOIN map_icons ON advertiser_master.adv_map_icon = map_icons.id
						WHERE ( advertiser_master.city IN ($LcationCityArr) OR advertiser_master.state IN ($LcationStateArr) )
						AND ( advertiser_master.active = 'Y' AND advertiser_master.publish = 'Y' AND advertiser_master.barred = 'N' AND advertiser_master.flyer_id = 0 ) ";

		$ADVRES = 	$this->db->get_results($SQLADV,ARRAY_A);
		/* Fetch Advertisement Near Location */
		
		#header("Content-type: text/xml");
		$strXML = "";
		# Start XML file, echo parent node
		$centerPoint ='';

		$strXML = '<markers>';
		
		# Iterate through the rows, printing XML nodes for each
		foreach ($ADVRES as $res){
				
		  # ADD TO XML DOCUMENT NODE
		    
	  		$custIcon = array();
			
			if ( trim($res['custom_icon']) ) {
			$custIcon[0]	= SITE_URL .'/modules/advertiser/icons/thumb/' . $req['id'] . '.' . $req['custom_icon'];
			$custIcon[1]	= SITE_URL .'/modules/advertiser/icons/thumb/' . $req['id'] . 's' . '.' . $req['custom_sicon'];
			
			} else {
			$custIcon[0]	= $res['icon_path'];
			$custIcon[1]	= $res['icon_spath'];
			}
		
			$imgRes			= getimagesize($custIcon[0]);
			$imgsRes		= getimagesize($custIcon[1]);
					
			$custIcon[2]	= $imgRes[0];				#Main Image Width
			$custIcon[3]	= $imgRes[1];	 			#Main Image Height
			$custIcon[4]	= $imgsRes[0];				#Shadw Image Width
			$custIcon[5]	= $imgsRes[1];	 			#Shadw Image Height

		  
			$res['adv_description']	=	str_replace("<br>","",$res['adv_description']);
			$advDescription	=	wordwrap(substr($res['adv_description'],0,60), 30, "<br>", true);
			$advDescription = substr($res['adv_description'],0,100);
		  	$advDescription	= str_replace(chr(13), "", $advDescription);
			$advDescription = str_replace(array("\r\n", "\r", "\n"), "", $advDescription);
		  
			$retVal = explode(",",substr(trim($res['lat_lon']),1,strlen(trim($res['lat_lon']))-2));
			$strCoord = $retVal;
			
			if ( $strCoord[0]>0 && $centerPoint=='')
			$centerPoint = $strCoord[0] . ',' . $strCoord[1];
					  
			$strXML .= '<marker ';
			$strXML .= 'adv_id="' . $this->parseToXML( trim($res['id']) ) . '" ';
			$strXML .= 'adv_img="' . $this->parseToXML( trim($res['adv_image']) ) . '" ';
			$strXML .= 'custIcon0="' . $this->parseToXML( $custIcon[0] ) . '" ';
			$strXML .= 'custIcon1="' . $this->parseToXML( $custIcon[1] ) . '" ';
			$strXML .= 'custIcon2="' . $this->parseToXML( $custIcon[2] ) . '" ';
			$strXML .= 'custIcon3="' . $this->parseToXML( $custIcon[3] ) . '" ';
			$strXML .= 'custIcon4="' . $this->parseToXML( $custIcon[4] ) . '" ';
			$strXML .= 'custIcon5="' . $this->parseToXML( $custIcon[5] ) . '" ';
			$strXML .= 'adv_title="' . $this->parseToXML( trim($res['adv_title']) ) . '" ';
			$strXML .= 'adv_description="' . $this->parseToXML( trim($advDescription) ) . '" ';
			$strXML .= 'adv_url="' . $this->parseToXML( trim($res['adv_url']) ) . '" ';
			$strXML .= 'address="' . $this->parseToXML( trim($res['city']) . " " . trim($res['country']) ) . '" ';
			$strXML .= 'zoom="' . $this->parseToXML( trim($res['zoom']) ) . '" ';
			$strXML .= 'lat="' . $strCoord[0] . '" ';
			$strXML .= 'lng="' . $strCoord[1] . '" ';
			$strXML .= '/>';
		}
		
		# End XML file
		$strXML .= '</markers>';

		return array($strXML);
	}
	
	
	
	/* Get Advertise Property For Listing as Add */
	function getRandAdvertiseProperty ($flyer,$objAdvertiser,$lmt=5) {
		# UPDATE BARRED ADVERTISEMENTS
		$SQLUPDATE	=	"UPDATE advertiser_master SET barred = 'N' WHERE date_barred < '".date("Y:m:d")."'";
		$this->db->query($SQLUPDATE);
		# UPDATE BARRED ADVERTISEMENTS
		
		$SQLADVPRP	=   "SELECT * FROM advertiser_master WHERE flyer_id>0 AND publish = 'Y' AND barred = 'N'  ORDER BY RAND() limit 0,{$lmt}";
		$ADVPRPRES  = 	$this->db->get_results($SQLADVPRP,ARRAY_A);
		
		
		foreach ($ADVPRPRES as $key=>$RES)	{
			if ( is_array($RES) ) { 
					if( $RES['flyer_id'] > 0 )	{
						 $FLYERID	=	$flyer->getFlyerIDByAlbum( $RES['flyer_id'] );
						 $ADVPRPRES[$key]['flyer_id'] = $flyer->getFlyerBasicFormData($FLYERID) ;
						 $RESPONDS	=	$objAdvertiser->CheckAdvClickManage( $RES['id'] );
					}
			}
		}

		
		if ( $ADVPRPRES>0 ) {
			return $ADVPRPRES;
		}else{
			return false;
		}
	}
	/* Get Advertise Property For Listing as Add */
	
	
	
	function basicAlbumInfo($albumId=0)	{
		if($albumId>0){
			$sqlALB = "SELECT * FROM flyer_data_basic WHERE album_id = {$albumId}";
			$rs	= $this->db->get_row($sqlALB,ARRAY_A);
			if ($rs)
			return $rs;
		}
		return false;		
	}
	
	
	
	
	
	
	
	function getBasicSearchResult_js ($req)	{
	
			
		include_once(FRAMEWORK_PATH."/modules/album/lib/class.flyer.php");
		include_once(FRAMEWORK_PATH."/modules/album/lib/class.photo.php");
		include_once(FRAMEWORK_PATH."/modules/callendar/lib/class.calendarevents.php");
		include_once(FRAMEWORK_PATH."/modules/map/lib/G_Maps.class.php");
		
	    $gmap	 		= 	new G_Maps();
		$album			=	new Album();
		$photo			=	new Photo();
		$flyer			=	new	Flyer();
		$cevents		=	new	CalendarEvents();
				
		
		
		# Availabe Product within the date range
		$bidSearch = 0;
		if ( trim( $req['check_in'] ) && trim( $req['check_out'] ) ) {
			$req['check_out']	=	date("Y-m-d",strtotime("{$req['check_out']} -1 day"));
			
			$checkinflex  =  $req['checkinout_flex'];
			$checkindate  =  $req['check_in'];
			$checkoutdate =  $req['check_out'];
			$bidSearch	  =  $req['chkBidSearch'];
			
				
			$BLOCKEDID =  $this->searchPropertyAvialable($req['check_in'],$req['check_out'],$checkinflex,$bidSearch);
			
			if(count($BLOCKEDID)>0) {
				$BLOCKEDID = implode(",",$BLOCKEDID);
			}else{
				$BLOCKEDID = "";
			}	
		}else{
			if ( trim($req['duration_type']) ) {
				$SQLUNIT = " AND unit = '{$req['duration_type']}'";
			}else{
				$SQLUNIT = "";
			}
		}
		
		
		
			
		# Setting Price Case
		$sqlPrice = "";
		
		if ($bidSearch==0) {
		$sqlPrice = " AND PP.price>0";
		}
		
		
		# Setting Propery Type
		$sqlPrpty ="";
		if ($req['prptyType']!="" ) {
		$prp=$req['prptyType'];
			$sqlPrpty = " AND form_id ='$prp'";
		}
		
		 
		# Setting Amenties
		$sqlAmties = "";
		
		if ( count($req['amentyGrp'])>0 ) {
		   $rsAttrib=$req['amentyGrp'];
		   $formid=$req['form_id'];
			
		   $ALLATTRIB = array();
		   foreach ($rsAttrib as $items)	{
				$ALLATTRIB[]	=	"'". "C" . $items. "'";
		   }
			
		   if ( count($ALLATTRIB)>0 ) {
				 $sqlAmtAlb	= "SELECT distinct(flyer_id) FROM flyer_data_checkbox_values WHERE checkbox_id IN (". implode(",",$ALLATTRIB) .")";
				$rsAmtAlb   = $this->db->get_results($sqlAmtAlb,ARRAY_A);
				$ALLATTRIBFLY = array();
				foreach ($rsAmtAlb as $items)	{
					$ALLATTRIBFLY[]	=	$items['flyer_id'];
				}
		   }
			
		   if ( count($ALLATTRIBFLY)>0 )	{
				$sqlAmties = " AND (FDB.flyer_id IN (". implode(",",$ALLATTRIBFLY) ."))";
		   }
		
		}
		
			
	
		# Setting Destination Search
		if ( trim($req['qryDest']) ) {
			$sqlDest = " AND FDC.location_city = '". trim($req['qryDest']) ."'";
		}
		
		
		
		# Setting Blocked Ids
		if (trim($BLOCKEDID))
		$sqlBlocked = " AND FDB.album_id NOT IN($BLOCKEDID)";
		
				

		if ($bidSearch==1) {
			$default_val_opt = "AND PP.default_val = 'N'";
		}else{
			$default_val_opt = "AND PP.default_val = 'Y'";
		}
		
		
		$currDateForm	=	date("Y-m-d");
		
		if ($bidSearch==0) {
			$SQLQUERY	=	"SELECT FDB.flyer_id,FDB.form_id,FDB.flyer_name,FDB.title,FDB.description,FDB.quantity,
			 				FDB.basic_price,FDB.image,FDB.album_id,FDB.score,FDB.rank,FDB.publish,FDB.active,
			 				FDC.location_street_address,FDC.location_city,FDC.location_state,FDC.location_country,
			 				PP.price,PP.duration,PP.unit,PP.booking_price,PP.default_val,PP.min_duration,
						 	PP.min_units,PP.start_date,PP.rental_end_date,PP.auction,PP.start_bid,PP.reserve_bid,
			 				PP.auction_ends,PP.auction_close,
			 				AMP.lat_lon
						FROM flyer_data_basic AS FDB
							LEFT JOIN flyer_data_contact AS FDC ON FDC.flyer_id = FDB.flyer_id
							LEFT JOIN album_map_position AS AMP ON AMP.album_id = FDB.album_id
							LEFT JOIN property_pricing AS PP ON PP.album_id = FDB.album_id $default_val_opt
							WHERE FDB.publish = 'Y' AND FDB.active = 'Y'";

		}else{
			$SQLQUERY	=	"SELECT DISTINCT (FDB.flyer_id),FDB.flyer_id,FDB.form_id,FDB.flyer_name,FDB.title,FDB.description,FDB.quantity,
			 				FDB.basic_price,FDB.image,FDB.album_id,FDB.score,FDB.rank,FDB.publish,FDB.active,
			 				FDC.location_street_address,FDC.location_city,FDC.location_state,FDC.location_country,
			 				AMP.lat_lon
						FROM flyer_data_basic AS FDB
							LEFT JOIN flyer_data_contact AS FDC ON FDC.flyer_id = FDB.flyer_id
							LEFT JOIN album_map_position AS AMP ON AMP.album_id = FDB.album_id
							LEFT JOIN property_pricing AS PP ON PP.album_id = FDB.album_id $default_val_opt
							WHERE FDB.publish = 'Y' AND FDB.active = 'Y' AND PP.auction = 'Y' AND PP.auction_close = '0' AND PP.auction_ends<'$currDateForm' ";
		}

				

		$SQLQUERY	=   $SQLQUERY . $sqlDest;		
		$SQLQUERY	=   $SQLQUERY . $sqlAmties;
		$SQLQUERY	=   $SQLQUERY . $sqlPrpty;
		$SQLQUERY	=   $SQLQUERY . $sqlPrice;
		$SQLQUERY	=   $SQLQUERY . $sqlBlocked;
			
		/*$orderBy    =   str_replace("isnull ASC,fds_id ASC,","",$orderBy);
		$orderBy    =   "isnull ASC,fds_id ASC,". $orderBy;*/
		$orderBy    =   $orderBy;
		
		//$limit
		$rs = 	$this->db->get_results($SQLQUERY,ARRAY_A);

		
			
		# Appending default Image
		foreach ($rs as $key=>$albRec){
			$rsAlbm	   =   $album->getAlbumDetails($albRec['album_id']); 
		
			/* Miles From Land Mark */
			if ( abs($req['CityLatitude']) > 0 ) {
				$Source_strCoord = explode(",",substr(trim( $albRec['lat_lon'] ),1,strlen(trim( $albRec['lat_lon'] ))-2));	
				if ( $Source_strCoord[0] > 0 ) {
					$albRec['LandMarkMiles'] = $gmap->getDistanceBetweenPointsNew( $req['CityLatitude'],$req['CityLongitude'],$Source_strCoord[0],$Source_strCoord[1] );
				}
			}
			/* Miles From Land Mark */
			
			
			if (trim($rsAlbm["default_img"])!="")
				$def_img_ext = $photo->imgExtension($rsAlbm["default_img"]);
			
			if ( trim($def_img_ext) &&  trim($rsAlbm["default_img"]) )
				$albRec['def_img'] = $rsAlbm["default_img"].$def_img_ext;
			
			/* Add Price */
			if ( trim( $req['check_in'] ) && trim( $req['check_out'] ) && $bidSearch==0 ) {
				$rs[$key]['final_price'] = $this->albumPriceCalculation($albRec->album_id,$req['check_in'],$req['check_out'],$cevents);
				$rs[$key]['final_unit']  = "";
				$rs[$key]['get_rate']    = 1;
				$rs[$key]['price']       = $albRec['final_price'];
			}
			else if ( trim( $req['check_in'] ) && trim( $req['check_out'] ) && $bidSearch==1 ) {
				$rs[$key]['auction']       = 'Y';
				$rs[$key]['auction_close'] = '0';
			}
			else {
				$rs[$key]['final_price'] = $albRec['price'];     //$flyer->albumMinMaxPrice($albRec->album_id);
				$rs[$key]['final_unit']  = $albRec['duration'] . " " . $albRec['unit'];
				$rs[$key]['get_rate']    = 0;
			}
	
		}
		
		return $rs;
	}
	
	
	
	
	
	/* SEARCH RESULTS */
	
	function getBasicSearchResult ($req, $pageNo=0, $limit = 15, $params='', $output=OBJECT, $orderBy="1")	{
	
			
		include_once(FRAMEWORK_PATH."/modules/album/lib/class.flyer.php");
		include_once(FRAMEWORK_PATH."/modules/album/lib/class.photo.php");
		include_once(FRAMEWORK_PATH."/modules/callendar/lib/class.calendarevents.php");
		include_once(FRAMEWORK_PATH."/modules/map/lib/G_Maps.class.php");
	    $gmap	 		= 	 new G_Maps();
		
		$album			=	new Album();
		$photo			=	new Photo();
		$flyer			=	new	Flyer();
		$cevents		=	new	CalendarEvents();
		
		
		
		
		
		### FILTER FROM TEMPORARY TABLE
		if ( $req['optFilter'] == 1 ) {
			$orderByNew	=	explode(".",$orderBy);
			if (count($orderByNew)>1) {
			$orderBy = $orderByNew[1];
			}
						
			if ( $req['LandMarkMiles'] > 0 ) {
				$SQLLANDMILES = " AND LandMarkMiles <= {$req['LandMarkMiles']}";
			}
			
			if ($req['chkBidSearch']==0) {
				if ( $req['sliderprice_start'] == 0 && $req['sliderprice_end'] == 10000 ) {
				$SQLPRICEFILT = " AND (price>0)";
				}else{
					if ($req['sliderprice_end'] == 10000)
					$SQLPRICEFILT = " AND (price>={$req['sliderprice_start']})";
					else
					$SQLPRICEFILT = " AND (price>={$req['sliderprice_start']} AND price<={$req['sliderprice_end']})";
				}
			}else{
				$SQLPRICEFILT = "";
			}
		
		
			
			$SessID	=	session_id();
			$SQLQUERY = "SELECT * FROM flyer_search_tmp_results WHERE sess_id = '{$SessID}' $SQLLANDMILES $SQLPRICEFILT";
			

			$rs = 	$this->db->get_results_pagewise_ajax($SQLQUERY, $pageNo, $limit, $params, $output,$orderBy);  //$limit
			return $rs;
		}
		### FILTER FROM TEMPORARY TABLE
		
		
		
		
		
		# Availabe Product within the date range
		$bidSearch = 0;
		if ( trim( $req['check_in'] ) && trim( $req['check_out'] ) ) {
			$req['check_out']	=	date("Y-m-d",strtotime("{$req['check_out']} -1 day"));
			
			$checkinflex  =  $req['checkinout_flex'];
			$checkindate  =  $req['check_in'];
			$checkoutdate =  $req['check_out'];
			$bidSearch	  =  $req['chkBidSearch'];
			
				
			$BLOCKEDID =  $this->searchPropertyAvialable($req['check_in'],$req['check_out'],$checkinflex,$bidSearch);
			
			if(count($BLOCKEDID)>0) {
				$BLOCKEDID = implode(",",$BLOCKEDID);
			}else{
				$BLOCKEDID = "";
			}	
		}else{
			if ( trim($req['duration_type']) ) {
				$SQLUNIT = " AND unit = '{$req['duration_type']}'";
			}else{
				$SQLUNIT = "";
			}
		}
		
		
		
			
		# Setting Price Case
		$sqlPrice = "";
		
		if ($bidSearch==0) {
		$sqlPrice = " AND PP.price>0";
		}
		
		
		# Setting Propery Type
		$sqlPrpty ="";
		if ($req['prptyType']!="" ) {
		$prp=$req['prptyType'];
			$sqlPrpty = " AND form_id ='$prp'";
		}
		
		 
		# Setting Amenties
		$sqlAmties = "";
		
		if ( count($req['amentyGrp'])>0 ) {
		   $rsAttrib=$req['amentyGrp'];
		   $formid=$req['form_id'];
			
		   $ALLATTRIB = array();
		   foreach ($rsAttrib as $items)	{
				$ALLATTRIB[]	=	"'". "C" . $items. "'";
		   }
			
		   if ( count($ALLATTRIB)>0 ) {
				 $sqlAmtAlb	= "SELECT distinct(flyer_id) FROM flyer_data_checkbox_values WHERE checkbox_id IN (". implode(",",$ALLATTRIB) .")";
				$rsAmtAlb   = $this->db->get_results($sqlAmtAlb,ARRAY_A);
				$ALLATTRIBFLY = array();
				foreach ($rsAmtAlb as $items)	{
					$ALLATTRIBFLY[]	=	$items['flyer_id'];
				}
		   }
			
		   if ( count($ALLATTRIBFLY)>0 )	{
				$sqlAmties = " AND (FDB.flyer_id IN (". implode(",",$ALLATTRIBFLY) ."))";
		   }
		
		}
		
			
	
		# Setting Destination Search
		if ( trim($req['qryDest']) ) {
			$sqlDest = " AND FDC.location_city = '". trim($req['qryDest']) ."'";
		}
		
		
		
		# Setting Blocked Ids
		if (trim($BLOCKEDID))
		$sqlBlocked = " AND FDB.album_id NOT IN($BLOCKEDID)";
		
		
		/*($SQLQUERY	=	"SELECT *,IF(flyer_data_search.fds_id IS NULL,1,0) AS isnull, flyer_data_basic.flyer_id as flyer_id
		                 FROM flyer_data_basic 
						 LEFT JOIN flyer_data_contact 
						 ON 
						 flyer_data_contact.flyer_id=flyer_data_basic.flyer_id
						 LEFT JOIN flyer_data_search
						 ON
						 flyer_data_search.fds_album_id=flyer_data_basic.album_id
						 LEFT JOIN (SELECT album_id,MAX(price) AS max_price,MIN(price) AS min_price
						  FROM property_pricing
						  GROUP BY album_id) AS p2
						 ON
						 p2.album_id=flyer_data_basic.album_id";*/
		
		
		
		
		/*$SQLQUERY	=	"SELECT *,flyer_data_basic.flyer_id as flyer_id,album_map_position.lat_lon
		                 FROM flyer_data_basic 
						 LEFT JOIN flyer_data_contact 
						 ON 
						 flyer_data_contact.flyer_id=flyer_data_basic.flyer_id
						 LEFT JOIN album_map_position
						 ON album_map_position.album_id=flyer_data_basic.album_id
						 LEFT JOIN property_pricing as PP
						 ON
						 PP.album_id=flyer_data_basic.album_id AND PP.default_val = 'Y'";*/

		if ($bidSearch==1) {
			$default_val_opt = "AND PP.default_val = 'N'";
		}else{
			$default_val_opt = "AND PP.default_val = 'Y'";
		}
		
		
		$currDateForm	=	date("Y-m-d");
		
		if ($bidSearch==0) {
			$SQLQUERY	=	"SELECT FDB.flyer_id,FDB.form_id,FDB.flyer_name,FDB.title,FDB.description,FDB.quantity,
			 				FDB.basic_price,FDB.image,FDB.album_id,FDB.score,FDB.rank,FDB.publish,FDB.active,
			 				FDC.location_street_address,FDC.location_city,FDC.location_state,FDC.location_country,
			 				PP.price,PP.duration,PP.unit,PP.booking_price,PP.default_val,PP.min_duration,
						 	PP.min_units,PP.start_date,PP.rental_end_date,PP.auction,PP.start_bid,PP.reserve_bid,
			 				PP.auction_ends,PP.auction_close,
			 				AMP.lat_lon
						FROM flyer_data_basic AS FDB
							LEFT JOIN flyer_data_contact AS FDC ON FDC.flyer_id = FDB.flyer_id
							LEFT JOIN album_map_position AS AMP ON AMP.album_id = FDB.album_id
							LEFT JOIN property_pricing AS PP ON PP.album_id = FDB.album_id $default_val_opt
							WHERE FDB.publish = 'Y' AND FDB.active = 'Y'";

		}else{
			$SQLQUERY	=	"SELECT DISTINCT (FDB.flyer_id),FDB.flyer_id,FDB.form_id,FDB.flyer_name,FDB.title,FDB.description,FDB.quantity,
			 				FDB.basic_price,FDB.image,FDB.album_id,FDB.score,FDB.rank,FDB.publish,FDB.active,
			 				FDC.location_street_address,FDC.location_city,FDC.location_state,FDC.location_country,
			 				AMP.lat_lon
						FROM flyer_data_basic AS FDB
							LEFT JOIN flyer_data_contact AS FDC ON FDC.flyer_id = FDB.flyer_id
							LEFT JOIN album_map_position AS AMP ON AMP.album_id = FDB.album_id
							LEFT JOIN property_pricing AS PP ON PP.album_id = FDB.album_id $default_val_opt
							WHERE FDB.publish = 'Y' AND FDB.active = 'Y' AND PP.auction = 'Y' AND PP.auction_close = '0' AND PP.auction_ends<'$currDateForm' ";
		}

				

		$SQLQUERY	=   $SQLQUERY . $sqlDest;		
		$SQLQUERY	=   $SQLQUERY . $sqlAmties;
		$SQLQUERY	=   $SQLQUERY . $sqlPrpty;
		$SQLQUERY	=   $SQLQUERY . $sqlPrice;
		$SQLQUERY	=   $SQLQUERY . $sqlBlocked;
			
		/*$orderBy    =   str_replace("isnull ASC,fds_id ASC,","",$orderBy);
		$orderBy    =   "isnull ASC,fds_id ASC,". $orderBy;*/
		$orderBy    =   $orderBy;
		
		//$limit
		$rs = 	$this->db->get_results_pagewise_ajax($SQLQUERY, $pageNo, 1000, $params, $output,$orderBy);

		
		$rsnew = array();
				
		
		# Appending default Image
		foreach ($rs[0] as $albRec){
			$rsAlbm	   =   $album->getAlbumDetails($albRec->album_id); 
			
			$TmpObj	=	$albRec;
			
			
			/* Miles From Land Mark */
			if ( abs($req['CityLatitude']) > 0 ) {
				$Source_strCoord = explode(",",substr(trim( $albRec->lat_lon ),1,strlen(trim( $albRec->lat_lon ))-2));	
				if ( $Source_strCoord[0] > 0 ) {
					$TmpObj->LandMarkMiles = $gmap->getDistanceBetweenPointsNew( $req['CityLatitude'],$req['CityLongitude'],$Source_strCoord[0],$Source_strCoord[1] );
				}
			}
			/* Miles From Land Mark */
			
			
			if (trim($rsAlbm["default_img"])!="")
				$def_img_ext = $photo->imgExtension($rsAlbm["default_img"]);
			
			if ( trim($def_img_ext) &&  trim($rsAlbm["default_img"]) )
				$TmpObj->def_img = $rsAlbm["default_img"].$def_img_ext;
			
			/* Add Price */
			if ( trim( $req['check_in'] ) && trim( $req['check_out'] ) && $bidSearch==0 ) {
				$TmpObj->final_price = $this->albumPriceCalculation($albRec->album_id,$req['check_in'],$req['check_out'],$cevents);
				$TmpObj->final_unit  = "";
				$TmpObj->get_rate    = 1;
				$TmpObj->price       = $TmpObj->final_price;
			}
			else if ( trim( $req['check_in'] ) && trim( $req['check_out'] ) && $bidSearch==1 ) {
				$TmpObj->auction       = 'Y';
				$TmpObj->auction_close = '0';
			}
			else {
				$TmpObj->final_price = $albRec->price;     //$flyer->albumMinMaxPrice($albRec->album_id);
				$TmpObj->final_unit  = $albRec->duration . " " . $albRec->unit;
				$TmpObj->get_rate    = 0;
			}
			
			
			
			$rsnew[]	=	$TmpObj;
		}
	
		$rs[0] = $rsnew;
		
	
	  
		## TEMPORARY TABLE FOR MODIFY SEARCH ##
		$SessID	=	session_id();
		$this->db->query("DELETE FROM flyer_search_tmp_results WHERE sess_id='{$SessID}'");
		$RESULTSET_ARR = array();
		foreach ( $rs[0] as $RESULTSET ) {
			 foreach ( $RESULTSET as $key=>$RES ){
			 	if ( trim($key) !="id" )
			 	$RESULTSET_ARR[$key] 	= addslashes($RES);
			 }
			 $RESULTSET_ARR['sess_id'] 	= $SessID;
			 $this->db->insert("flyer_search_tmp_results", $RESULTSET_ARR);
			 $RESULTSET_ARR = array();
		}
		$orderByNew	=	explode(".",$orderBy);
		
		if ( $req['LandMarkMiles'] > 0 ) {
				$SQLLANDMILES = " AND LandMarkMiles <= {$req['LandMarkMiles']}";
		}
		
		
		if ($bidSearch==0) {
			if ( $req['sliderprice_start'] == 0 && $req['sliderprice_end'] == 10000 ) {
				$SQLPRICEFILT = " AND (price>0)";
			}else{
				if ($req['sliderprice_end'] == 10000)
				$SQLPRICEFILT = " AND (price>={$req['sliderprice_start']})";
				else
				$SQLPRICEFILT = " AND (price>={$req['sliderprice_start']} AND price<={$req['sliderprice_end']})";
			}
		}
		
		
		
		if (count($orderByNew)>1) {
			$orderBy = $orderByNew[1];
		}
		$SQLQUERY = "SELECT * FROM flyer_search_tmp_results WHERE sess_id = '{$SessID}' $SQLLANDMILES $SQLPRICEFILT $SQLUNIT";
		$rs = 	$this->db->get_results_pagewise_ajax($SQLQUERY, $pageNo, $limit, $params, $output,$orderBy);
		## TEMPORARY TABLE ##
		
		
		
		
		return $rs;
	}
	
/**
* Default image Search 
* Author   : vinoy jacob
* Created  : 27/dec/2007
* Modified : 08/jan/2008
*/
	function searchDefaultImage($prpArr = array('11', '2', '18', '22','210'))
	{
		
		include_once(FRAMEWORK_PATH."/modules/album/lib/class.photo.php");
		$album			=	new Album();
		$photo			=	new Photo();
		$prop_id =  $prpArr;
		$countId=count($prop_id);
		for($i=0;$i<$countId;$i++)
		{
			  $rsAlbm[$i]	   =   $album->getAlbumDetails($prop_id[$i]); 
			  $rsExt           =   $photo->imgExtension($rsAlbm[$i]["default_img"]);
			  $rsAlbm[$i]["img_extension"]=$rsExt;
			  $sql      =  	"SELECT * FROM album_photos JOIN album_files ON album_photos.id = album_files.file_id AND album_files.album_id = $prop_id[$i] AND album_files.type ='photo'";
			  $rs = $this->db->get_results($sql,ARRAY_A); 
			  $cnt[$i]=count($rs);
				
		}
		return array($rsAlbm,$cnt);
		//return array($rsAlbm,$rsExt,$cnt);
	}
	
	
	/**
* Default video Search 
* Author   : vinoy jacob
* Created  : 08/jan/2008
* Modified : 08/jan/2008
*/
	
	function searchVideos($prpArr = array('11', '2', '18', '22','210'))
	{
	    include_once(FRAMEWORK_PATH."/modules/album/lib/class.photo.php");
	    include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
	    $album			=	new Album();
		$photo			=	new Photo();
	    $prop_id =  $prpArr;
		$countId=count($prop_id);
		for($i=0;$i<$countId;$i++)
		{
			$rsAlbm[$i]	   =   $album->getAlbumDetails($prop_id[$i]); 
			$rsExt           =   $photo->imgExtension($rsAlbm[$i]["default_vdo"]);
			//$rsVdo[$i] = $album->propertyList("album_video","video",0,$prop_id[$i],$_SESSION["memberid"]);
			 $sql      =  	"SELECT * FROM album_video JOIN album_files ON album_video.id = album_files.file_id AND album_files.album_id =$prop_id[$i] AND album_files.type='video'";
             $rs = $this->db->get_results($sql,ARRAY_A); 
			 $cnt[$i]=count($rs);
		}
		return array($rsAlbm,$cnt);
	}
	
	/**
   * This function is for getting the  property name  .
   * Author   : vinoy
   * Created  :07/Jan/2008
   * Modified : 
   */		
	function propertyName($albm_id)
	{
	    $sql = "SELECT * FROM  flyer_data_basic where album_id='$albm_id'";
        $rs  = $this->db->get_row($sql, ARRAY_A);	
        return $rs;
	}
	
	function videoName($video_id)
	{
	  $sql = "SELECT * FROM  album_video where id='$video_id'";
        $rs  = $this->db->get_row($sql, ARRAY_A);	
        return $rs;
	}
	
	
	
	
	
	
	
	function newfn()
	{
	$strs	.= '<tr>';
			/* foreach($PHOTO as item)
			   {  
			    if ($smarty.foreach.foo.index is div by 3)
			    {*/
				  
				$strs   .= '</tr><tr>';
				//}
				
				$strs	.= '<td>';
				$strs	.= '<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">';
				$strs	.= '<tr>';
				$strs	.= '<td height="3" colspan="5"><img src="'.$GLOBAL.tpl_url.'/images/spacer3.gif" width="1" height="7" /></td>';
				$strs	.= '</tr>';
				$strs	.= '<tr>';
				$strs	.= '<td width="208" align="left" valign="top">';
				$strs	.= '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="border">';
				$strs	.= '<tr>';
				$strs	.= '<td align="center" valign="middle">';
				$strs	.= '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
				$strs	.= '<tr>';
				$strs	.= '<td height="20"><table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">';
				$strs	.= '<tr>';
				$strs	.= '<td><span class="meroon">$105</span> <span class="bodytext1">';
				
				//$strs	.= $item.album_name|truncate:41:"...":true;
				
				$strs	.= '</span></td>';
				$strs	.= '</tr>';
				$strs	.= '</table></td>';
				$strs	.= '</tr>';
				$strs	.= '<tr>';
				$strs	.= '<td align="center" valign="middle">';
				
				//if $item.default_img.jpg!=""
				
				$strs	.= '<img id="'.$item.id.'" src="'.$smarty.xonst.SITE_URL.'/modules/album/photos/'.$item.default_img.''.$DEFAULT_IMG_EXT.'" width="190" height="135" />';
				
				//}else{
				
				$strs	.= '<img src="'.$GLOBAL.tpl_url.'/images/nophoto.jpg" width="190" height="135" />';
				
				//}
				
				$strs	.= '</td>';
				$strs	.= '</tr>';
				$strs	.= '<tr>';
				$strs	.= '<td width="100%" height="20"><table width="100%" height="10" border="0" align="center" cellpadding="0" cellspacing="0">';
				$strs	.= '<tr>';
				$strs	.= '<td width="38%" align="center"><img src="'.$GLOBAL.tpl_url.'/images/starb.jpg" width="13" height="13" /><img src="'.$GLOBAL.tpl_url.'/images/starb.jpg" width="13" height="13" /><img src="'.$GLOBAL.tpl_url.'/images/starb.jpg" width="13" height="13" /><img src="'.$GLOBAL.tpl_url.'/images/starb.jpg" width="13" height="13" /><img src="'.$GLOBAL.tpl_url.'/images/starb.jpg" width="13" height="13" /></td>';
				$strs	.= '<td>';
				$strs	.= '<table width="90%"  border="0" align="left" cellpadding="0" cellspacing="0">';
				$strs	.= '<tr>';
				$strs	.= '<td align="center"><img id="img1_'.$item.id.'" src="'.$GLOBAL.tpl_url.'/images/previous1.jpg" onClick="chgPhoto('.$item.id.','.$item.id.',1,'.$COUNT.')" a align="absmiddle"></td>';
				$strs	.= '<td align="center" class="smalltext4"><span id="startID_'.$item.id.'" style=" ">1</span> of <span id="endID_'.$item.id.'" >'.$COUNT.'</span></td>';
				$strs	.= '<td align="left"><img id="img2_'.$item.id.'" src="'.$GLOBAL.tpl_url.'/images/next1.jpg" onClick="chgPhoto('.$item.id.','.$item.id.',1,'.$COUNT.')" align="absmiddle"></td>';
				$strs	.= '</tr>';
				$strs	.= '</table></td>';
				$strs	.= '<td width="18%"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
				$strs	.= '<tr>';
				
				//if $item.default_img.jpg!=""
				//{
				
				$strs	.= '<td width="43%" align="center" valign="middle"><a href="#"><img src="'.$GLOBAL.tpl_url.'/images/message1.gif" width="14" height="10" border="0" /></a></td>';
				$strs	.= '<td width="24%" align="center" valign="middle"><img src="'.$GLOBAL.tpl_url.'/images/love.jpg" width="14" height="10" /></td>';
				$strs	.= '<td width="33%" align="center" valign="middle"><img src="'.$GLOBAL.tpl_url.'/images/myphoto.gif" width="16" height="18" /></td>';
				
				//}else{
				
				$strs	.= '<td class="smalltext4" align="center">No photos</td>';
				
				//}
				$strs	.= '</tr>';
				$strs	.= '</table></td>';
				$strs	.= '</tr>';
				$strs	.= '</table></td>';
				$strs	.= '</tr>';
				$strs	.= '</table></td>';
				$strs	.= '</tr>';
				$strs	.= '</table>';
				$strs	.= '</td>';
				$strs	.= '<td width="9" align="left" valign="top">&nbsp;</td>';
				$strs	.= '</tr>';
				$strs	.= '<tr>';
				$strs	.= '<td height="5" colspan="5" align="left" valign="top"><img src="'.$GLOBAL.tpl_url.'/images/spacer.gif" width="3" height="3" /></td>';
				$strs	.= '</tr>';
				$strs	.= '</table>';
				$strs	.= '</td>';
				
				//}
				
				$strs	.= '</tr>';

	}
	
	
	
	
	
 	/**
      * Album Price Calculation
      * Author   : Aneesh
      * Created  : 14/Feb/2008
      * Modified : 4/Feb/2008 By Aneesh
      * Price Will vary against check-In and check-Out
    */

 	function albumPriceCalculation($album_id="",$check_in="",$check_out="",$cevents) {
  		 		
 		include_once(FRAMEWORK_PATH."/modules/map/lib/class.property.php");
 		
 		$objProperty    =	new Property();
 		
 		list($freeFloatDate,$singleFreeFloatDate) = $objProperty->getFreeFloatingDays($check_in,$check_out,$album_id);
 		$freeFloat = $objProperty->getFreefloatingPrice($album_id);
 		$check_out = $objProperty->setTheFreefloatingDays($freeFloatDate,$singleFreeFloatDate,$freeFloat["duration"],$freeFloat["unit"],$check_in,$album_id,$check_out);
 		
 		$rsFixed = $objProperty->getFixedBlockDates($album_id,$check_in,$check_out,$cevents);
		list($rsFree,$rsFreeSing)	 =	$objProperty->getFreeFloatingDays($check_in,$check_out,$album_id);
		
 		
 		$sing_date_array_price = $objProperty->calculateSingleFreeFloatingPrice($rsFreeSing,$freeFloat["duration"],$freeFloat["unit"],$freeFloat["price"]);
 		
 		
 		
 		$FREEFLOAT_RATE = $sing_date_array_price + count($rsFree) * $freeFloat["price"];
 		
 		
 		
 		
 		if ( count($rsFixed) ) {
 			$FIX_PRICE_ARRAY =array();
 			foreach ( $rsFixed as $RES ) {
 				$FIX_PRICE_ARRAY[] = $RES["price"];
 			}
 		}

 		$TotalAveragePrice	=   0;	
 		$TotalAveragePrice	=   array_sum($FIX_PRICE_ARRAY) + $FREEFLOAT_RATE;
 		$TotalAveragePrice	=   round(($TotalAveragePrice),2);		
 		
 		return $TotalAveragePrice;
 		exit;
 		
 	}

 	/* End Function */

 	
 	
 	/* Creating Flexible Queries For 1 Day & 2 Day */
 	function createSearchFlexibleQueries ($FLEX_DAYS,$CHECK_IN,$CHECK_OUT) {
 		 		
 		if ( $FLEX_DAYS == 1 ) {
			# array(CheckIn,CheckOut)
 			$CombArry			=	 array ( array("-1","-1") ); #, array("0","-1"), array("1","-1"), array("1","0"), array("1","1")
			$CombBookingString  =    "";
			$CombBlockString 	=    "";
			$CombAutionString   =    "";
 		}else if ( $FLEX_DAYS == 2 ) {
 			# array(CheckIn,CheckOut)
 			$CombArry			=	 array ( array("-2","-2") );
			$CombBookingString  =    "";
			$CombBlockString 	=    "";
			$CombAutionString   =    "";
 		}	
 		
 		$ORG_CHRCKIN	=	$CHECK_IN;
 		$ORG_CHRCKOUT	=	$CHECK_OUT;
 		
 		if ( $FLEX_DAYS == 1 ||  $FLEX_DAYS == 2 ) {
 		
 			foreach ( $CombArry as $key=>$CombRes ) {
 				
 				if ( $CombRes[0] !=0 )
	 			$CHECK_IN	=	date("Y-m-d",strtotime("$ORG_CHRCKIN {$CombRes[0]} day"));
	 			
	 			if ( $CombRes[1] !=0 )
	 			$CHECK_OUT	=	date("Y-m-d",strtotime("$ORG_CHRCKOUT {$CombRes[1]} day"));
	 			
	 			
	 			
	 			$CombBookingString .=  "AND 
				 			((
								(DATE_FORMAT(start_date,'%Y-%m-%d') between  '$CHECK_IN' AND '$CHECK_OUT')  
								OR 
								(DATE_FORMAT(end_date,'%Y-%m-%d') between '$CHECK_IN' AND '$CHECK_OUT') 
							)
							OR
							(
								('$CHECK_IN' between  DATE_FORMAT(start_date,'%Y-%m-%d') AND DATE_FORMAT(end_date,'%Y-%m-%d'))
								OR 
								('$CHECK_OUT' between DATE_FORMAT(start_date,'%Y-%m-%d') AND DATE_FORMAT(end_date,'%Y-%m-%d'))
							))
							";
	 			
	 			$CombBlockString  .= "AND 
				 			(( 
								(DATE_FORMAT(from_date,'%Y-%m-%d') between  '$CHECK_IN' AND '$CHECK_OUT')  
								OR
								(DATE_FORMAT(to_date,'%Y-%m-%d') between '$CHECK_IN' AND '$CHECK_OUT') 
							)
							OR
							(
								('$CHECK_IN' between  DATE_FORMAT(from_date,'%Y-%m-%d') AND DATE_FORMAT(to_date,'%Y-%m-%d'))
								OR 
								('$CHECK_OUT' between DATE_FORMAT(from_date,'%Y-%m-%d') AND DATE_FORMAT(to_date,'%Y-%m-%d'))
							))
	 			
	 						";
	 			
	 			
	 		 	 
	 			
	 			
	 			
	 			$CombAutionString   .=    "AND
	 						 (((auction ='Y') AND
							 ( 
								(start_date between  '$CHECK_IN' AND '$CHECK_OUT')  
								OR 
								(rental_end_date between '$CHECK_IN' AND '$CHECK_OUT') 
							 ))
							 OR
							 ((auction ='Y') AND
							 (
								('$CHECK_IN' between  start_date AND rental_end_date)  
								OR 
								('$CHECK_OUT' between start_date AND rental_end_date) 
							 )))
	 			             ";
	 			
	 			            
 			}
 			
 			
 			
 			return array($CombBookingString,$CombBlockString,$CombAutionString);
 			
 		}
 		
 		
 	}
 	/* Creating Flexible Queries */
 	
 	
 	
 	
 	
 	
 	function searchPropertyAvialable($frmDate,$toDate,$flexDays=0,$bidSearch=0){
 		   
  		
 		
 		    if ( $flexDays > 0 ) {
 		    	list ($bookStr,$blockStr,$auctStr) = $this->createSearchFlexibleQueries ($flexDays,$frmDate,$toDate);
 		    }
 		
 		
 		
			$sql1 = "SELECT DISTINCT 'propertyBook' AS eventType,b.album_id,f.quantity,
					 count(b.album_id) FROM album_booking AS b
					 INNER JOIN flyer_data_basic AS f ON f.album_id = b.album_id
			
			
					 WHERE 
					((
						(DATE_FORMAT(start_date,'%Y-%m-%d') between  '$frmDate' AND '$toDate')  
						OR 
						(DATE_FORMAT(end_date,'%Y-%m-%d') between '$frmDate' AND '$toDate') 
					)
					OR
					
					(
						('$frmDate' between  DATE_FORMAT(start_date,'%Y-%m-%d') AND DATE_FORMAT(end_date,'%Y-%m-%d'))  
						OR 
						('$toDate' between DATE_FORMAT(start_date,'%Y-%m-%d') AND DATE_FORMAT(end_date,'%Y-%m-%d')) 
					)) " . $bookStr . "
					GROUP BY b.album_id HAVING quantity <= count(b.album_id)";
			
		
			$rs1  =  $this->db->get_results($sql1,ARRAY_A);
			
			// PropertyBlock Checking Here
			$sql2 = "SELECT DISTINCT 'propertyBlock' AS eventType,b.album_id FROM property_blocked AS b
					 
					
					WHERE 
					(( 
						(DATE_FORMAT(from_date,'%Y-%m-%d') between  '$frmDate' AND '$toDate')  
						OR 
						(DATE_FORMAT(to_date,'%Y-%m-%d') between '$frmDate' AND '$toDate') 
					)
					OR
					
					(
						('$frmDate' between  DATE_FORMAT(from_date,'%Y-%m-%d') AND DATE_FORMAT(to_date,'%Y-%m-%d'))  
						OR 
						('$toDate' between DATE_FORMAT(from_date,'%Y-%m-%d') AND DATE_FORMAT(to_date,'%Y-%m-%d')) 
					)) " . $blockStr . "
					ORDER BY from_date";
			
			
			$rs2  =  $this->db->get_results($sql2,ARRAY_A);
			
			// Property Auction Check
			if ( $bidSearch == 0 ) {
				$sql3 = " SELECT DISTINCT 'propertyAuction' AS eventType,b.album_id FROM property_pricing AS b
					 
					 WHERE 
					 (((auction ='Y') AND
					 ( 
						(start_date between  '$frmDate' AND '$toDate')  
						OR 
						(rental_end_date between '$frmDate' AND '$toDate') 
					 ))
					 OR
					 ((auction ='Y') AND
					 (
						('$frmDate' between  start_date AND rental_end_date)  
						OR 
						('$toDate' between start_date AND rental_end_date) 
					 ))) $auctStr 
					 ";
				$rs3  =  $this->db->get_results($sql3,ARRAY_A);
			
			}else{
				$rs3  = array();
			}
			
			
			if ( $bidSearch == 0 ) {
				if (count($rs1) >0 && count($rs2) >0 && count($rs3) >0)
				$rs = array_merge($rs1,$rs2,$rs3);
				elseif(count($rs1) >0)
				$rs	= $rs1;
				elseif(count($rs2) >0)
				$rs	= $rs2;
				elseif(count($rs3) >0)
				$rs	= $rs3;
			}else{
				if (count($rs1) >0 && count($rs2) >0)
				$rs = array_merge($rs1,$rs2);
				elseif(count($rs1) >0)
				$rs	= $rs1;
				elseif(count($rs2) >0)
				$rs	= $rs2;
			}


			//$rs = array_merge($rs1,$rs2,$rs3);
			
			$propidArray = array();
			
			
			if(count($rs)){
				
				foreach ($rs as $propid){
					$propidArray[] = $propid["album_id"];
				}
			}
			

			if(count($propidArray))
			return 	$propidArray;
			else 
			return false;
	}
	
	
	
	function oldAlbumPriceClac() {
		 		
 		
 		
  		/* Make sure Album Id Exist */
 		if ( trim($album_id) == "" )
 		return false;

 		 		 		
 		/* make sure Check_in date is Less than Check_out date */
 		if ( strtotime($check_in) > strtotime($check_out)  )
 		return false;

 		
 		//$check_in 	= date("Y-m-d",strtotime("$check_in -1 day"));
 		//$check_out	= date("Y-m-d",strtotime("$check_out +1 day"));
 		
 		
 		
 		
 		 		
 		
 		
 		 		 		

		if ($FloatUnit == 'Week'){
			$FloatDuration  =   $FloatDuration * 7;
		}elseif ($FloatUnit == 'Month') {
			$FloatDuration  =   $FloatDuration * 28;
		}elseif ($FloatUnit == 'Year') {
			$FloatDuration  =   $FloatDuration * 336;
		}

 		
		echo $FloatDuration;
 		/* End Free Floating Price*/

 		
 		

 		/* Start Fixed Rates Price */
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


 		$check_out	=	date("Y-m-d",strtotime("{$check_out} +1 day"));
 		
 		
 		/* Count of Days */
 		$TotDaysDurArray = get_time_difference($check_in,$check_out);
 		$TotDaysDuration = $TotDaysDurArray['days'];

 		
 		
 				
 		$blockTotalDuration = 0;
 		$blockTotalPrice    = 0;


 		foreach ($rs as $rsFixed) {

 			$blockTotArray = get_time_difference($rsFixed['start_date'],$rsFixed['rental_end_date']);
 			$blockTotDays  = $blockTotArray['days'] + 1;



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
 				
 				if ( $blockTotDays % $blockDuration == 0) {
 					$divisionVal 		= 	intval($blockTotDays/$blockDuration);
 				}else{
 					$divisionVal 		= 	intval($blockTotDays/$blockDuration) + 1;
 				}
 				
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
 			
 			if ( $finalFreeFloatDays % $FloatDuration == 0) {
 				$divisionVal 		= 	intval($finalFreeFloatDays/$FloatDuration);
 			}else{
 				$divisionVal 		= 	intval($finalFreeFloatDays/$FloatDuration) + 1;
 			}
 			
  			$blockTotalPrice 	=	$blockTotalPrice 	+ ($FloatPrice*$divisionVal);
 			$blockTotalDuration	=	$blockTotalDuration	+ $finalFreeFloatDays;
 		}
 		/* End Adding Price to Free Floating Dates */

 		
		

 		// print $blockTotalPrice;

 		/*   Average Price = (Total Price + Booking price)/2      */
 		$TotalAveragePrice	=	0;
 		//print $blockTotalPrice;
 		//$TotalAveragePrice	=   round(($blockTotalPrice +  $FloatBooking) / $TotDaysDuration,2);
 		
 		$TotalAveragePrice	=   round(($blockTotalPrice),2);
 		/*   Average Price   */


 		return $TotalAveragePrice;
 		//return $blockTotalPrice;
	}
	
}

?>