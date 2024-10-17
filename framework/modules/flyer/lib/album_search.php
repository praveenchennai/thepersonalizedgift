<?php
session_start();
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.search.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");
include_once(FRAMEWORK_PATH."/includes/class.framework.php");
include_once(FRAMEWORK_PATH."/modules/callendar/lib/class.calendartool.php");
include_once(FRAMEWORK_PATH."/modules/map/lib/G_Maps.class.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.photo.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.property.php");
include_once(FRAMEWORK_PATH."/modules/map/lib/G_Maps.class.php");
include_once(FRAMEWORK_PATH."/modules/advertiser/lib/class.advertiser.php");

$gmap	 		= 	new G_Maps();
$fw				=	new FrameWork();
$user           =   new User();
$flyer			=	new	Flyer();
$gmap	 		= 	new G_Maps();
$album			=	new Album();
$photo			=	new Photo();
$property 		=	new Property();
$objCalendar	=   new CalendarTool ();
$objSearch	    =   new Search();
$objAdvertiser	=	new Advertiser();

switch($_REQUEST['act']) {
	
	/*
		SHOWING SEARCH OPTIONS
	*/
	case "search_form":
		
	    $framework->tpl->assign("FORMS",$flyer->getallForms()); 			
		$framework->tpl->assign("AMENTIES_GRP", $objSearch->getAmentiesGroup());
		$framework->tpl->assign("PROPERTY_TYPES", $objSearch->getPropertyType());
		$framework->tpl->assign("DURATION_TYPE",array("Day" => "Day","Week" => "Week","Month" => "Month","Year" => "Year"));
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/search_form.tpl");
	break;
	case "search_form_list":
			
		$framework->tpl->assign("AMENTIES_GRP", $objSearch->getAmentiesGroup());
		$framework->tpl->assign("FORMS",$flyer->getallForms()); 			
		$framework->tpl->assign("PROPERTY_TYPES", $objSearch->getPropertyType());
		$framework->tpl->assign("DURATION_TYPE",array("Day" => "Day","Week" => "Week","Month" => "Month","Year" => "Year"));
		
		$act			=	$_POST["act"] ? $_POST["act"] : "";
		$limit			=	$_POST["limit"] ? $_POST["limit"] : "15";
		$pageNo 		= 	$_POST["pageNo"] ? $_POST["pageNo"] : "0";
		$param			=	"mod=$mod&pg=$pg&act=search_form_list&orderBy=$orderBy";
		
		
		if ( $_SERVER['REQUEST_METHOD'] == 'POST')	{
		
				
			########This Part of Code is added to fid the Neighborhood city , latitude&longitude...
			######## Code Added by Jipson Thomas...................................................
			######## Date Added 10 April 2008......................................................
			######## Code Modified by Jipson Thomas................................................
			######## Date Modified 10 April 2008...................................................
			$FIND_LANDMARK = false;
			
			$CITY_CENTERDET	=	$gmap->getLatLonGeocode($_POST["qryDest"]);	
			
			if ( is_array($CITY_CENTERDET) ) {
				if ( $CITY_CENTERDET[0] == 200 ) {
					$_POST['CityLatitude']		=	$CITY_CENTERDET[2];
					$_POST['CityLongitude']		=	$CITY_CENTERDET[3];
					$FIND_LANDMARK = true;
				}
			}
			

			if($FIND_LANDMARK == true) {
	
				
			try {	
			$apikey=$global["trulia_api_key"];
			$city=$_POST["qryDest"];
			$state=$flyer->getStatebyCity($city);
			$stcode=$flyer->getStateCodebyName($state);
			$url= "http://api.trulia.com/webservices.php?library=LocationInfo&function=getNeighborhoodsInCity&city=" . urlencode($city) . "&state=" . urlencode($stcode) . "&apikey=$apikey";
			$ch = curl_init();
	        $timeout = 5; // set to zero for no timeout
	        curl_setopt ($ch, CURLOPT_URL, $url);
	        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

	        $file_contents = curl_exec($ch);
	        curl_close($ch);
			
			$gfile=$file_contents;
			####### Area Using SimpleXML
			} catch (Exception $e) {
    			;
			}
			
			
			try {
				$xml = new SimpleXMLElement($gfile);
				$i=0;
				foreach($xml->response->LocationInfo->neighborhood as $resp){
					$neighbor[$i]["name"]=(string)$resp->name;
					$neighbor[$i]["longitude"]=(string)$resp->longitude;
					$neighbor[$i]["latitude"]=(string)$resp->latitude;
					$i++;
				}
			} catch (Exception $e) {
    			;
			}	
			
			
			#######End SimpleXML Parser..
			$Arrindx	=	1;
			$Methods[0]['dbvalue']	=	'Name^'.$_POST["qryDest"].'(City Center)|Longitude^'.$CITY_CENTERDET[3].'|Latitude^'.$CITY_CENTERDET[2].'';
			$Methods[0]['label']	=	$_POST["qryDest"]."(City Center)";
			foreach($neighbor as $nei){
				$value				=	'Name^'.$nei['name'].'|Longitude^'.$nei['longitude'].'|Latitude^'.$nei['latitude'];
				$label				=	$nei['name'];
				$Methods[$Arrindx]['dbvalue']	=	$value;
				$Methods[$Arrindx]['label']		=	$label;
				$Arrindx++;
			}
			$framework->tpl->assign("DIST_LANDMARK", $Methods);
			######## End of the code for Neighborhood Cities.......................................
			}else {
				$Methods[0]['dbvalue']	=	'0';
				$Methods[0]['label']	=	'-SELECT-';
				$framework->tpl->assign("DIST_LANDMARK", $Methods);
				$framework->tpl->assign("DIST_LANDMARK_STATUS", "NULL");
			}
			
			
			
			
			
			
			
			$orderBy = "title:ASC";
			
				
			/* Begin Queries 
			***
			*/
			$req = $_POST;

			//exit;
			
			/*
			 **  End Queries
			 */
						
			list($rs, $numpad, $cnt, $limitList,$pag_total,$prenext_link,$next_link)	= 	$objSearch->getBasicSearchResult($_POST,$pageNo,15,$param,OBJECT, $orderBy);
			
							
			
			/*$JsonObj	=	new Services_JSON();
			$EncodedStr	=	$JsonObj->encode($rs);
			$framework->tpl->assign("resJson", $EncodedStr);*/
			//print_r($rs);
			
			$framework->tpl->assign("res", $rs);
			$framework->tpl->assign("LIST_NUMPAD", $numpad);
			$framework->tpl->assign("LIST_LIMIT", $limitList);
			$framework->tpl->assign("PREVNEXT_CONT", $prenext_link);
			$framework->tpl->assign("LIST_COUNT", $cnt);
			$framework->tpl->assign("sliderprice_start", $req['sliderprice_start']);
			$framework->tpl->assign("sliderprice_end", $req['sliderprice_end']);
			$framework->tpl->assign("amentyGrp", $req['amentyGrp']);
			$framework->tpl->assign("prptyType", $req['prptyType']); 
			$framework->tpl->assign("qryDest", $req['qryDest']);
			$framework->tpl->assign("check_in", $req['check_in']);
			$framework->tpl->assign("check_out", $req['check_out']);
			$framework->tpl->assign("duration_type", $req['duration_type']);
			
			$groupitems		=	$flyer->getFlyerFormAttributeGroupsAndItems($req['prptyType']);
			$content 		=	$flyer ->getPropertyItems($groupitems,$req['amentyGrp']);
			$framework->tpl->assign("CONTENT", $content);
			
			
			
			
			list($xmlStr,$centerP)	= $objSearch->makeXmlMapList(&$rs);
			$framework->tpl->assign("xmlStr",$xmlStr);
			if (trim($centerP)) {
			$mapSetPosStr	=	"new GLatLng({$centerP}),7";
			}else {
			$mapSetPosStr	=	"nil";
			}
			$framework->tpl->assign("mapSetPosStr",$mapSetPosStr);
					
			
			
			
			#   Advertisement Property List 
			$ADVRES_PRP_ARRAY	=	$objSearch->getRandAdvertiseProperty();
			$framework->tpl->assign("ADVRES_PRP_ARRAY",$ADVRES_PRP_ARRAY);
			#   Advertisement Property List 
			
			
			
			# AdvertiseMent List
			list($xmlAdvStr)	= 	$objSearch->makeXmlMapAdvList($rs);
			$framework->tpl->assign("xmlAdvStr",$xmlAdvStr);
			# AdvertiseMent List
			
			
			$gmap->ELEMENT_ID    = 'map_prp_view';
		    $gmap->MAP_TYPE 	 = 'G_NORMAL_MAP';
			$gmap->HEIGHT = '500px';
		    $framework->tpl->assign("My_Map", $gmap->showMap());
		    
		 
		    
		    /*
			BEGIN PHOTO
			*/
		    $prpArray = array();
		 	foreach ($rs as $Group) {
		 		$prpArray[] = $Group->album_id;
				//$prpArray[] = $Group->final_price;
		 	}
		 	
		 	
			$albumres = $objSearch->searchDefaultImage($prpArray);
			
			list($a,$c) = $albumres;
			for($i=0;$i<count($a);$i++){
			
				$a[$i]['count']=count($a[$i]);
			}

			foreach($a as $k=>$aArray)	{
				$a[$k]['details'] = $objSearch->basicAlbumInfo($aArray['id']);
				
			}
			
			foreach ($rs as $k=>$Group) {
		 		$a[$k]['final'] = $Group->final_price;
				
		 	}
			//print_r($a);
			$framework->tpl->assign("PHOTO",  $a);
			$framework->tpl->assign("COUNT",$c);
			/*
			BEGIN PHOTO
			*/

			
			/*
			BEGIN VIDEO
			*/
	

			$albumVideo = $objSearch->searchVideos($prpArray);
			list($a,$c) = $albumVideo;
	

			foreach($a as $k=>$aArray)	{
				$a[$k]['details'] = $objSearch->basicAlbumInfo($aArray['id']);
			}
			foreach ($rs as $k=>$Group) {
		 		$a[$k]['final'] = $Group->final_price;
				
		 	}
			$VIDEO_LIST =  $a;
			$COUNT =  $c;
			$framework->tpl->assign("VIDEO_LIST",$a);
			$framework->tpl->assign("COUNT1",$c);
			
			
			
			/*
		if($_REQUEST['dates']) {
			$datesArr	=	explode("-",$_REQUEST['dates']);
			$datesY		=	$datesArr[0];
			$datesM		=	$datesArr[1];
			$datesD		=	$datesArr[2];
		} else {
			$datesY		=	date('Y');
			$datesM		=	date('m');
			$datesD		=	date('d');
		}
		
		
	        $framework->tpl->assign("calendarTo",$objCalendar->DrawCalendarLayer($datesY, $datesM,$datesD,'calendarTo',array('FCase'=>'property_booking') ));*/
			
			
				
		
		
			
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/search_big_form.tpl");		
		}else{
			redirect(makeLink(array("mod"=>"flyer", "pg"=>"album_search"),"act=search_form"));
		}
		
	break;	
	case "search_form_Item":
	$req = $_POST;
	extract($req);
	
		if ( trim($req['amentyGrp']) ) {
			$req['amentyGrp'] = explode(",",$req['amentyGrp']);
		}else{
			$req['amentyGrp'] = array();
		}
		
	
	 $contents="";
		    $groupitems		=	$flyer->getFlyerFormAttributeGroupsAndItems($req['prptyType']);
			$contents 		=	$flyer ->getPropertyItems($groupitems,$req['amentyGrp']);
	echo $contents;
	exit;
	break;
	
	case "search_form_list_ajax":
		$act			=	$_POST["act"] ? $_POST["act"] : "";
		$limit			=	$_POST["limit"] ? $_POST["limit"] : "15";
		$pageNo 		= 	$_POST["pageNo"] ? $_POST["pageNo"] : "0";
		
		
		/* Begin Queries 
			***
			*/
		$req = $_REQUEST;
		extract($req);
		
		
		//$req['CityLatitude']		=	$CITY_CENTERDET[2];
		//$req['CityLongitude']		=	$CITY_CENTERDET[3];
		
		
		
		if ( trim($req['amentyGrp']) ) {
			$req['amentyGrp'] = explode(",",$req['amentyGrp']);
		}else{
			$req['amentyGrp'] = array();
		}
		
		
		
		
		list($rs, $numpad, $cnt, $limitList,$pag_total,$prenext_link,$next_link)	= 	$objSearch->getBasicSearchResult($req,$pageNo,$limit,$param,OBJECT, $orderBy);
		

		
		if ($req['mapPage']=='YES') {
			list($xmlStr,$centerP)	= $objSearch->makeXmlMapList(&$rs);
			$framework->tpl->assign("xmlStr",$xmlStr);
			
			if (trim($centerP)) {
			$mapSetPosStr	=	"new GLatLng({$centerP}),7";
			}else {
			$mapSetPosStr	=	"nil";
			}
			
			$framework->tpl->assign("mapSetPosStr",$mapSetPosStr);
		} else{
			$mapSetPosStr = "";
			$xmlStr	= "";
		}
		
		$rsecho = "";
		
		
		
		
		
		/*
		$togIndex = 1;
		foreach($rs as $Group) {
			$tblColor = '';
			if($Group->fds_id) $tblColor = '#efefef';
			
			
			$rsecho .= "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\" bgcolor=\"{$tblColor}\">";
			$rsecho .= "<tr>";
			$rsecho .= "<td height=\"3\" colspan=\"4\"><img src=\"{$global[tpl_url]}/images/spacer3.gif\" width=\"1\" height=\"7\" /></td>";
			$rsecho .= "</tr>";
		
			$rsecho .= "<tr>";
			
			$rsecho .= "<td width=\"25%\" align=\"left\" valign=\"top\"><span class=\"blackboldtext3\"><a href=\"#\" class=\"grayboltext\">$".$Group->final_price ."</a></span>";
				if( $Group->get_rate== 0 )
				$rsecho .= "<br /><a id=\"rate_{$Group->album_id}\" class=\"orangebold\" href=\"javascript:void(0);\" onclick=\"javascript:popupPosition_dragOpt('nameFieldPopup',event);hideShow();\" >Get Rates</a>";
			$rsecho .= "</td>";

			$rsecho .= "<td width=\"20%\" align=\"left\" valign=\"top\">";
				if(!trim($Group->def_img)) $rsecho .= "<div style=\"height:75px;width:75px;background-color:#efefef;\">";
				if(trim($Group->def_img)) $rsecho .= "<div style=\"height:75px;width:75px\">";
				if(trim($Group->def_img)){
				$rsecho .= "<img id=\"togImg{$togIndex}\" style=\"display:none;\" src=". SITE_URL .  "/modules/album/photos/".$Group->def_img ." width=\"69\" height=\"69\" class=\"imageborder2\" />";
				}
				$rsecho .= "</div>";
			$rsecho .= "</td>";
			
						
			$rsecho .= "<td width=\"30%\" align=\"left\" valign=\"top\" class=\"meroon\"><p>".substr($Group->flyer_name,0,20)."<br />";
				$rsecho .= "<a href=\"#\" class=\"toplink\">".substr($Group->description,0,50)."</a><br />";
			$rsecho .= "<span>".substr($Group->location_city,0,10)."&nbsp;" .$Group->location_country. "</span></p></td>";
		
			
			
			$rsecho .= "<td width=\"25%\" align=\"left\" valign=\"top\"><table width=\"95%\" border=\"0\" align=\"right\" cellpadding=\"0\" cellspacing=\"0\">";
			$rsecho .= "<tr>";
			$rsecho .= "<td>&nbsp;</td>";
			$rsecho .= "</tr>";
			$rsecho .= "<tr>";
			$rsecho .= "<td align=\"center\"><a class=\"orangebold\" href=\"".makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_view&details=det&flyer_id={$Group->flyer_id}&propid={$Group->album_id}") ."\">Details</a></td>";
			$rsecho .= "</tr>";
			$rsecho .= "<tr>";
			$rsecho .= "<td>&nbsp;</td>";
			$rsecho .= "</tr>";
			$rsecho .= "<tr>";
			$rsecho .= "<td align=\"center\"><a class=\"orangebold\" href=\""."javascript:gofavor({$Group->album_id});"."\">Add to Favorite</a></td>";
			$rsecho .= "</tr>";
			$rsecho .= "</table></td>";
			$rsecho .= "</tr>";
		
		
			$rsecho .= "</table>";			
			
			
			
			
			$rsecho .= "<div class=\"SearchTabSpc\"><!----></div>";
			$togIndex++;
		}
		*/
		
		
		
		$togIndex = 1;
		foreach($rs as $Group) {
			
			$rsecho .= "<table width=\"100%\" border=\"0\" class=\"border\">";
				$rsecho .= "<tr>";
					$rsecho .= "<td width=\"110\" class=\"grayboltext\" valign=\"middle\" align=\"center\">";
					
						if ( $Group->final_price<1 && $Group->final_unit == "" )  {
							$rsecho .= "<a class=\"meroon\" href=\"".makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_view&details=det&flyer_id={$Group->flyer_id}&propid={$Group->album_id}") ."\" target=\"blank\">BID NOW</a>";
						}else {
							$rsecho .= "$".$Group->final_price;
							if(trim($Group->final_unit)) {
							$rsecho .= "<br />";
							$rsecho .= "For";
							$rsecho .= "<br />";
							$rsecho .= $Group->final_unit;
							}
						}
						
						
					$rsecho .= "</td>";
					$rsecho .= "<td width=\"80\" rowspan=\"2\" valign=\"middle\">";
						$rsecho .= "<div style=\"height:75px;width:75px\">";
						
						if(trim($Group->def_img)) {
						$rsecho .= "<img id=\"togImg{$togIndex}\" src=". SITE_URL .  "/modules/album/photos/thumb/".$Group->def_img ." width=\"69\" height=\"69\" class=\"imageborder2\" />";
						} else {
						$rsecho .= "<img id=\"togImg{$togIndex}\" src=". $global['tpl_url'] . "/images/no_image.jpg" ." width=\"69\" height=\"69\" />";
						}				
						$rsecho .= "</div>";	
								
					$rsecho .= "</td>";
					$rsecho .= "<td width=\"240\" rowspan=\"2\" valign=\"top\" align=\"left\" class=\"meroon\">";
						$rsecho .= "<a class=\"meroon\" href=\"".makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_view&details=det&flyer_id={$Group->flyer_id}&propid={$Group->album_id}") ."\" target=\"blank\">".substr($Group->flyer_name,0,50)."</a><br />";
						$rsecho .= "<a href=\"".makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_view&details=det&flyer_id={$Group->flyer_id}&propid={$Group->album_id}") ."\" target=\"blank\" class=\"toplink\">".substr($Group->description,0,60)."</a><br />";
						$rsecho .= "<span>".substr($Group->location_city,0,40)."&nbsp;" .$Group->location_country. "</span>";
					$rsecho .= "</td>";
					
				$rsecho .= "</tr>";
				$rsecho .= "<tr>";
					$rsecho .= "<td width=\"110\" class=\"blackboldtext3\" valign=\"bottom\" align=\"center\"><a class=\"orangebold\"href=\""."javascript:gofavor({$Group->album_id});"."\">Add to Favorite</a></td>";
				$rsecho .= "</tr>";
			$rsecho .= "</table>";	
			
			
			$rsecho .= "<div class=\"SearchTabSpc\"><!----></div>";
			$togIndex++;
		}
		
		
		
		
		
		
		$rsechomap = "";
		$togIndex = 1;
		foreach($rs as $Group) {
		$rsechomap .= "<div style=\"float:left;text-align:left;width:20%\" class=\"orange4\">".$togIndex."</div>";
		$rsechomap .= "<div style=\"float:left;text-align:left;width:30%;cursor:pointer\" class=\"orange4\" onclick=\"popUpHtmlBlockMap(LocPointMapPos[".($togIndex-1)."],HtmlLocPointMapPos[".($togIndex-1)."]);\"   >".substr($Group->flyer_name,0,20) ."&nbsp;</div>";
		$rsechomap .= "<div style=\"float:left;text-align:left;width:25%\" class=\"orange4\">" . $Group->final_price . "</div>";
		$rsechomap .= "<div style=\"float:left;text-align:left;width:20%\" class=\"orange4\">" . $Group->LandMarkMiles . "</div>";
		$rsechomap .= "<div style=\"clear:both\"></div>";
		$rsechomap .= "<div class=\"SearchTabSpc\"><!----></div>";
		$togIndex++;
		}

		
		/* AdvertiseMent List */
			list($xmlAdvStr)	= $objSearch->makeXmlMapAdvList($rs);
			$framework->tpl->assign("xmlAdvStr",$xmlAdvStr);
		/* Advertisement */
		
		
		
		/*
		BEGIN PHOTO
		*/
	    $prpArray = array();
	 	foreach ($rs as $Group) {
	 		$prpArray[] = $Group->album_id;
	 	}
		$albumres = $objSearch->searchDefaultImage($prpArray);
		list($a,$c) = $albumres;

		foreach($a as $k=>$aArray)	{
			$a[$k]['details'] = $objSearch->basicAlbumInfo($aArray['id']);
		}
		foreach ($rs as $k=>$Group) {
		 		$a[$k]['final'] = $Group->final_price;
				
		 	}
		$PHOTO =  $a;
		$COUNT =  $c;
		/*
		BEGIN PHOTO
		*/	
		
		$rsechophoto = "";
		$togIndex = 0;
		
		if (count($PHOTO) > 0) {		
		$rsechophoto .= "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"border\">";
		$rsechophoto .= "<tr>";
		foreach($PHOTO as $item) {
			
			if ($togIndex%3==0)	{
			$rsechophoto .= "</tr><tr>";
			}
			$rsechophoto .= "<td>";
			$rsechophoto .= "<table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
			$rsechophoto .= "<tr>";
			$rsechophoto .= "<td height=\"3\" colspan=\"6\"><img src=\"{$global[tpl_url]}/images/spacer3.gif\" width=\"1\" height=\"4\" /></td>";
			$rsechophoto .= "</tr>";
			$rsechophoto .= "<tr>";
			$rsechophoto .= "<td width=\"4\" align=\"left\" valign=\"top\">&nbsp;</td>";
			$rsechophoto .= "<td width=\"208\" align=\"left\" valign=\"top\">";
			$rsechophoto .= "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">";
			$rsechophoto .= "<tr>";
			$rsechophoto .= "<td align=\"center\" valign=\"middle\">";
			
			$rsechophoto .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
			$rsechophoto .= "<tr>";
			$rsechophoto .= "<td height=\"20\"><table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">";
			$rsechophoto .= "<tr>";
			$rsechophoto .= "<td align=\"left\"><span class=\"bodytext\"><strong>".substr(trim($item['details']['flyer_name']),0,12). "</strong></span></td>";
			$rsechophoto .= "<td align=\"right\"><span class=\"orange4\">&nbsp;$".$item['final']."</span></td>";
			$rsechophoto .= "</tr>";
			$rsechophoto .= "</table></td>";
			$rsechophoto .= "</tr>";
			
			$rsechophoto .= "<tr>";
			$rsechophoto .= "<td align=\"center\" valign=\"middle\">";
	        if($COUNT[$togIndex]!="") {
				$rsechophoto .= "<img id=img_".$item['id']." src=". SITE_URL ."/modules/album/photos/thumb/".$item['default_img'].$item['img_extension'] ." width=\"190\" height=\"135\" />";
			}else{
				$rsechophoto .= "<img src=\"{$global[tpl_url]}/images/no_photo.jpg\" width=\"190\" height=\"135\" />";
			}
			$rsechophoto .= "</td>";
			
			$rsechophoto .= "</tr>";
			$rsechophoto .= "<tr>";
			$rsechophoto .= "<td width=\"100%\" height=\"20\"><table width=\"100%\" height=\"10\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">";
			$rsechophoto .= "<tr>";
			$rsechophoto .= "<td width=\"38%\" align=\"center\"></td>";

			//$rsechophoto .= "<td width=\"38%\" align=\"center\"><img src=\"{$global[tpl_url]}/images/starb.jpg\" width=\"13\" height=\"13\" /><img src=\"{$global[tpl_url]}/images/starb.jpg\" width=\"13\" height=\"13\" /><img src=\"{$global[tpl_url]}/images/starb.jpg\" width=\"13\" height=\"13\" /><img src=\"{$global[tpl_url]}/images/starb.jpg\" width=\"13\" height=\"13\" /><img src=\"{$global[tpl_url]}/images/starb.jpg\" width=\"13\" height=\"13\" /></td>";
			$rsechophoto .= "<td>";
			
			$rsechophoto .= "<table width=\"90%\"  border=\"0\" align=\"left\" cellpadding=\"0\" cellspacing=\"0\">";
			if($COUNT[$togIndex]!="") {
			$rsechophoto .= "<tr>";
			$rsechophoto .= "<td align=\"center\"><img id=\"img1_{$item['id']}\" src=\"{$global[tpl_url]}/images/previous1.jpg\" onClick=\"chgPhoto({$item['id']},{$item['id']},-1,{$COUNT[$togIndex]},{$item['default_img']},'{$item['img_extension']}');\" align=\"absmiddle\" style=\"display:none;cursor:pointer\"></td>";
			$rsechophoto .= "<td align=\"center\" class=\"smalltext4\"><span id=\"startID_{$item['id']}\">1</span> of <span id=\"endID_{$item['id']}\" >{$COUNT[$togIndex]}</span></td>";
			$rsechophoto .= "<td align=\"left\">";
			if($COUNT[$togIndex]>1) {
			$rsechophoto .= "<img id=\"img2_{$item['id']}\" src=\"{$global[tpl_url]}/images/next1.jpg\" onClick=\"chgPhoto({$item['id']},{$item['id']},1,{$COUNT[$togIndex]},{$item['default_img']},'{$item['img_extension']}');\" style=\"cursor:pointer\" align=\"absmiddle\">";
			}
			$rsechophoto .= "</td>";
			$rsechophoto .= "</tr>";
			}else{
			$rsechophoto .= "<tr><td class=\"smalltext4\" align=\"center\">No photos</td></tr>";
			}
			$rsechophoto .= "</table></td>";                                          
			$rsechophoto .= "<td width=\"18%\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
			$rsechophoto .= "<tr>";
			$rsechophoto .= "<td width=\"43%\" align=\"center\" valign=\"middle\"></td>";

			//$rsechophoto .= "<td width=\"43%\" align=\"center\" valign=\"middle\"><a href=\"#\"><img src=\"{$global[tpl_url]}/images/message1.gif\" width=\"14\" height=\"10\" border=\"0\" /></a></td>";
			$rsechophoto .= "<td width=\"24%\" align=\"center\" valign=\"middle\"><img src=\"{$global[tpl_url]}/images/love.jpg\" width=\"14\" height=\"10\" onClick=\"gofavor({$item['id']});\" style=\"cursor:pointer\" alt=\"Add to Favorite\"/></td>";
			$rsechophoto .= "<td width=\"33%\" align=\"center\" valign=\"middle\"></td>";
			//$rsechophoto .= "<td width=\"33%\" align=\"center\" valign=\"middle\"><img src=\"{$global[tpl_url]}/images/myphoto.gif\" width=\"16\" height=\"18\" /></td>";
							 
			$rsechophoto .= "</tr>";
			$rsechophoto .= "</table></td>";
			$rsechophoto .= "</tr>";	
			$rsechophoto .= "</table></td>";
			$rsechophoto .= "</tr>";
			$rsechophoto .= "</table>";
			
			$rsechophoto .= "</td>";
			$rsechophoto .= "</tr>";
			$rsechophoto .= "</table>";
			$rsechophoto .= "</td>";
			$rsechophoto .= "<td width=\"4\" align=\"left\" valign=\"top\">&nbsp;</td>";
			
			$rsechophoto .= "</tr>";
			
			$rsechophoto .= "</table>";
			$rsechophoto .= "</td>";
			$togIndex++;
		}
		$rsechophoto .= "</tr>";
		$rsechophoto .= "<tr>";
		$rsechophoto .= "<td>";
		$rsechophoto .= "</td>";
		$rsechophoto .= "</tr>";
		$rsechophoto .= "</table>";
		}
		
		/*else{
		$rsechophoto .= "<div style=\"height:10px\"><!-- --></div>";
        $rsechophoto .= "<div class=\"orange4\" align=\"center\">No Records</div>";
		$rsechophoto .= "<div style=\"height:10px\"><!-- --></div>";
		}*/

		
		
		
		/*
		BEGIN VIDEO
		*/
	    $prpArray = array();
	 	foreach ($rs as $Group) {
	 		$prpArray[] = $Group->album_id;
	 	}
		$albumVideo = $objSearch->searchVideos($prpArray);
		list($a,$c) = $albumVideo;
		
	

		foreach($a as $k=>$aArray)	{
			$a[$k]['details'] = $objSearch->basicAlbumInfo($aArray['id']);
		}
		foreach ($rs as $k=>$Group) {
		 		$a[$k]['final'] = $Group->final_price;
				
		 	}
		$VIDEO_LIST =  $a;
		$COUNT =  $c;
		
		/*
		BEGIN VIDEO
		*/	
		
		$rsechovideo = "";
		$togIndex = 0;	
			
		
		
		
		
			if (count($VIDEO_LIST) > 0) {	
			$rsechovideo .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"border\">";
			
			$rsechovideo .= "<tr>";
			foreach($VIDEO_LIST as $item) {
			
			if ($togIndex%3==0)	{
			$rsechovideo .= "</tr><tr>";
			}
			
			$rsechovideo .= "<td>";
			$rsechovideo .= "<table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
			$rsechovideo .= "<tr>";
			$rsechovideo .= "<td height=\"3\" colspan=\"5\"><img src=\"{$global[tpl_url]}/images/spacer3.gif\" width=\"1\" height=\"4\" /></td>";
			$rsechovideo .= "</tr>";
			$rsechovideo .= "<tr>";
			$rsechovideo .= "<td width=\"208\" align=\"left\" valign=\"top\">";
			$rsechovideo .= "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">";
			$rsechovideo .= "<tr>";
			$rsechovideo .= "<td align=\"center\" valign=\"middle\">";
			
			$rsechovideo .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
			$rsechovideo .= "<tr>";
			$rsechovideo .= "<td height=\"20\"><table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">";
			$rsechovideo .= "<tr>";
			$rsechovideo .= "<td align=\"left\"><span class=\"bodytext\"><strong>".substr(trim($item['details']['flyer_name']),0,12)."</strong></span></td>";
			$rsechovideo .= "<td align=\"right\"><span class=\"orange4\">&nbsp;$".$item['final']."</span></td>";
			$rsechovideo .= "</tr>";
			$rsechovideo .= "</table></td>";
			$rsechovideo .= "</tr>";
			$rsechovideo .= "<tr>";
			//$rsechovideo .= "<td><input name=\"videoId\" id=\"vd_{$item['id']}\" type=\"hidden\" value=\"{$item['default_vdo']}\"></td>";  
			$rsechovideo .= "<td><input name=\"videoId\" id=\"vdr_{$item['id']}\" type=\"hidden\" value=\"{$item['default_vdo']}\"></td>";  
			$rsechovideo .= "</tr>";
			$rsechovideo .= "<tr>";
			$rsechovideo .= "<td align=\"center\" valign=\"middle\">";
			if($COUNT[$togIndex]!="") {
			$rsechovideo .= "<img id=vdo_".$item['id']." src=". SITE_URL . "/modules/album/video/thumb/" . $item['default_vdo'] . ".jpg" . " width=\"190\" height=\"135\" onClick=\"popUp3({$item['id']})\" style=\"cursor:pointer\"/>";
			}else{
			$rsechovideo .= "<img src=\"{$global[tpl_url]}/images/no_photo.jpg\" width=\"190\" height=\"135\" />";	
			}
			$rsechovideo .= "</td>";
			
			
			$rsechovideo .= "</tr>";
			$rsechovideo .= "<tr>";
			$rsechovideo .= "<td width=\"100%\" height=\"20\"><table width=\"100%\" height=\"10\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">";
			$rsechovideo .= "<tr>";
			
			$rsechovideo .= "<td width=\"38%\" align=\"center\"></td>";
			$rsechovideo .= "<td>";
			$rsechovideo .= "<table width=\"90%\"  border=\"0\" align=\"left\" cellpadding=\"0\" cellspacing=\"0\">";
			if($COUNT[$togIndex]!="") {
			$rsechovideo .= "<tr>";
			$rsechovideo .= "<td align=\"center\"><img id=\"vdo1_{$item['id']}\" src=\"{$global[tpl_url]}/images/previous1.jpg\" onClick=\"chgVideo({$item['id']},{$item['id']},-1,{$COUNT[$togIndex]},{$item['default_vdo']})\" align=\"absmiddle\" style=\"display:none\"></td>";
			$rsechovideo .= "<td align=\"center\" class=\"smalltext4\"><span id=\"startVID_{$item['id']}\">1</span> of <span id=\"endVID_{$item['id']}\" >{$COUNT[$togIndex]}</span></td>";
			$rsechovideo .= "<td align=\"left\">";
			if($COUNT[$togIndex]>1) {
			$rsechovideo .= "<img id=\"vdo2_{$item['id']}\" src=\"{$global[tpl_url]}/images/next1.jpg\" onClick=\"chgVideo({$item['id']},{$item['id']},1,{$COUNT[$togIndex]},{$item['default_vdo']})\" align=\"absmiddle\" style=\"cursor:pointer\">";
			}
			$rsechovideo .= "</td>";
			$rsechovideo .= "</tr>";
			}else{
			$rsechovideo .= "<tr><td class=\"smalltext4\" align=\"center\">&nbsp;</td> no videos</tr>";
			}
			$rsechovideo .= "</table></td>";
			$rsechovideo .= "<td width=\"18%\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
			$rsechovideo .= "<tr>";
			$rsechovideo .= "<td width=\"43%\" align=\"center\" valign=\"middle\"></td>";
			$rsechovideo .= "<td width=\"24%\" align=\"center\" valign=\"middle\"><img name=\"pro_submit\" src=\"{$global[tpl_url]}/images/love.jpg\" width=\"14\" height=\"10\" onClick=\"gofavor({$item['id']});\" style=\"cursor:pointer\"></td>";
			$rsechovideo .= "<td width=\"33%\" align=\"center\" valign=\"middle\"></td>";
			
			$rsechovideo .= "</tr>";
			$rsechovideo .= "</table></td>";
			$rsechovideo .= "</tr>";
			$rsechovideo .= "</table></td>";
			$rsechovideo .= "</tr>";
			$rsechovideo .= "</table>";
			
			$rsechovideo .= "</td>";
			$rsechovideo .= "</tr>";
			$rsechovideo .= "</table>";
			$rsechovideo .= "</td>";
			$rsechovideo .= "<td width=\"4\" align=\"left\" valign=\"top\">&nbsp;</td>";
			
			$rsechovideo .= "</tr>";
			
			$rsechovideo .= "</table>";
			$rsechovideo .= "</td>";
			$togIndex++;
			
			}
			$rsechovideo .= "</tr>";
			$rsechovideo .= "<tr>";
			$rsechovideo .= "<td>";
			$rsechovideo .= "</td>";
			$rsechovideo .= "</tr>";
			$rsechovideo .= "</table>";
			}
			
			
			/*else{
			$rsechovideo .= "<div style=\"height:10px\"><!-- --></div>";
			$rsechovideo .= "<div class=\"orange4\" align=\"center\">No Records</div>";
			$rsechovideo .= "<div style=\"height:10px\"><!-- --></div>";
			}*/	


		echo $rsecho."|".$numpad."|".$cnt."|".$limitList."|".$pag_total."|".$prenext_link."|".$next_link . "|" . $xmlStr . "|".$mapSetPosStr . "|" . $rsechomap . "|" . $rsechophoto . "|" . $rsechovideo . "|" . $xmlAdvStr;
		exit;
	break;	
	
	//=======vinoy start==========
	case 'photo_list':
	        $albumres = $objSearch->searchDefaultImage();
		    list($a,$c) = $albumres;
			$framework->tpl->assign("PHOTO",  $a);
			$framework->tpl->assign("COUNT",$c);
			
			
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/search_listing.tpl");
	break;
	//========vinoy End===========
	case 'video_search':
	
	       $albumVideo = $objSearch->searchVideos();
		   list($a,$c) = $albumVideo;
		  $framework->tpl->assign("VIDEO_LIST",$a);
		  $framework->tpl->assign("COUNT",$c);
		  $framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/video_listing.tpl");
		  //  $framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/video_display.tpl");
	break;
	
	case 'video_list':
	
			$albm_id		=	$_REQUEST["albm_id"] 		? $_REQUEST["albm_id"] 			: "";
			$img_id			=	$_REQUEST["img_id"] 		? $_REQUEST["img_id"] 			: "";
			$vdoNo 			= 	$_REQUEST["vdoNo"] 			? $_REQUEST["vdoNo"] 			: "";
			$defid 			= 	$_REQUEST["defid"] 			? $_REQUEST["defid"] 			: "";
//print $vdoNo;
			//echo $defid;
			$content		=	$photo->changeVideos($albm_id,$img_id,$vdoNo,$defid);
			//print_r($content);
			//echo $albm_id."|".$content."|".($vdoNo+1);
			echo $albm_id."|".$content."|".($vdoNo+2);
			exit;
    break;		
	
	
	
	
	
	case 'video_show':
		 $albm_id  = $_REQUEST['albm_id'];
		 $video_id = $_REQUEST['vdoId'];
		 
	      $framework->tpl->assign("VIDEO",$video_id);  
		  $rsVdo = $album->propertyList("album_video","video",0,$_REQUEST["propid"],$_SESSION["memberid"]);
		  
		 $framework->tpl->display($global['curr_tpl']."/videoPopup.tpl");
		 exit;
	break;
	
	case 'details':
	     $flyer_id		=	$_REQUEST['flyer_id'];
		 $prop_id		=	$_REQUEST['propid'];

		$StepsHTML		=	$flyer->getPropertyCreatonStepsHTML(7, $flyer_id,$prop_id);
		
		$framework->tpl->assign("STEPS_HTML", $StepsHTML);
		$FormDetails	=	$flyer->getContactInfoOfFlyer($flyer_id);
		$FlyerBasicData	=	$flyer->getFlyerBasicFormData($flyer_id);	
		
		# Begin Map SECTION #
		# Begin Map SECTION #
		# Begin Map SECTION #
		

		if (  $FormDetails['location_zip'] )
		$query	=	$FormDetails['location_zip'];
		elseif (  $FormDetails['location_city'] )
		$query	=	$FormDetails['location_city'];
		elseif (  $FormDetails['location_state'] )
		$query	=	$FormDetails['location_state'];
		elseif (  $FormDetails['location_country'] )
		$query	=	$FormDetails['location_country'];


		//$query  = $FormDetails['location_city'] + " " + $FormDetails['location_state'] + " " + $FormDetails['location_country'] + " " + $FormDetails['location_zip']#street+" "+city+" "+country+" "+post
		$gmap->ELEMENT_ID    = 'property_map_view';
		$gmap->MAP_TYPE 	 = 'G_NORMAL_MAP';
		$gmap->MAP_ZOOM      = 14;
		$gmap->SHOW_LOCALSEARCH = true;
		$gmap->HEIGHT = '400px';
		
		
		//$popStr	=	"<span class =bodytext><b>" . $FlyerBasicData['flyer_name'] . "</b><br>" . $FlyerBasicData['title'] . "<br>" . $FormDetails['location_city'] . ',' .$FormDetails['location_state'] . "<br>" . $FormDetails['location_country'] . "</span>";

		
		$flyDescription	=	wordwrap(substr($FlyerBasicData['description'],0,120), 40, "<br>", true);
		
		if (strlen($FlyerBasicData['description'])>120)
		$flyDescription = $flyDescription . "...";
		
		$flyDescription	= str_replace(chr(13), "", $flyDescription);
		$flyDescription = str_replace(array("\r\n", "\r", "\n","<br>"), "", $flyDescription);
		
				
		$popStr	=	"";
		$popStr	.= "<div align=center class=\"bodytext border\" style=\"width:350px;text-align:justify\">";
						
		if(trim($FlyerBasicData['title']))
		$popStr	.= "<div align=left style=width:80%;text-align:justify><b>" . $FlyerBasicData['title'] . "</b></div>";
		
		if(trim($FlyerBasicData['description']))
		$popStr	.= "<div align=center class=\"meroonsmall\" style=width:80%;text-align:justify>" . $flyDescription . "</div>";	
				
		
		if(trim($FlyerBasicData['location_city']))
		$popStr	.= "<span>" . $FlyerBasicData['location_city'] . "</span>, ";	

		if(trim($FlyerBasicData['location_country']))
		$popStr	.= "<span>" . $FlyerBasicData['location_country'] . "</span>";	
			
		$popStr	.=	"</div>";
		

		$getPos	=	$flyer->fetchAlbumPositiononMap ($FormDetails['flyer_id']);
		if($getPos['lat_lon'])	{
			$mapSetPosStr	=	"new GLatLng{$getPos['lat_lon']}";
			$framework->tpl->assign("mapSetPosStr", $mapSetPosStr);
		}

		$framework->tpl->assign("popStr", $popStr);
		$framework->tpl->assign("query", $query); #$_REQUEST['query']
		$framework->tpl->assign("My_Map", $gmap->showMap());

		# End Map SECTION #
		# End Map SECTION #
		# End Map SECTION #
		
		
		$rsFlbasic			=	$flyer->getFlyerBasicFormData($flyer_id);
		$rsAlbm		  		=   $album->getAlbumDetails($prop_id); 
		$rsFlvFeAt			=	$flyer->getFlyerDataForPreview($flyer_id);// Get the features and Attributes
		list($quantityTitle)=	$flyer->getQuantityTitle($prop_id);
		$rsBlockPropList	=	$flyer->getPropertyBlockQuantityTitle($prop_id,$_SESSION['memberid']);// This part may be change(Based on the current user)
		
		/*PHOTO LISTING PART ---- START*/
		
		$tbl="album_photos";
		$type="photo";
		
		/*
		Get the List of photo details
		*/
		$rsPhoto = $album->propertyList("album_photos","photo",0,$_REQUEST["propid"],$_SESSION["memberid"]);
	
		/*PHOTO LISTING PART ---- END */
		
		/*
		VIDEO LISTING
		*/
		$rsVdo = $album->propertyList("album_video","video",0,$_REQUEST["propid"],$_SESSION["memberid"]);
		
		
		$framework->tpl->assign("PHOTO_LIST",$rsPhoto);
		$framework->tpl->assign("VIDEO_LIST",$rsVdo);
		$framework->tpl->assign("DEFAULT_IMG",$rsAlbm["default_img"]);
		$framework->tpl->assign("DEF_IMG_EXT",$photo->imgExtension($rsAlbm["default_img"]));
		$framework->tpl->assign("FLYER_BASIC",$rsFlbasic);
		$framework->tpl->assign("SPECIAL_PRICE",$flyer->getSpecialPriceDetails($prop_id));
		$framework->tpl->assign("BIDPRICE",$flyer->getBidPriceList($prop_id));
		
		$framework->tpl->assign("BLOCK_QUANTITY_TITLE",$rsBlockPropList);
		$framework->tpl->assign("QUANTITY_TITILE",$quantityTitle);
		$framework->tpl->assign("SPECIFIC_DATE_PRICE",$flyer->getSpecificDatesPriceList($prop_id));
		$framework->tpl->assign("FLYER_FEATURES",$rsFlvFeAt);
		$framework->tpl->assign("FLYER_ATTRIBUTES",$rsFlvFeAt["blocks"]["ATTRIBUTES"]["attributes"]);
		$framework->tpl->assign("album_id",$FormDetails['flyer_id']);
		$framework->tpl->assign("MAP", SITE_PATH."/modules/flyer/tpl/property_map_view.tpl");
		$framework->tpl->assign("CALENDAR",SITE_PATH."/modules/album/tpl/mycalendar_search.tpl");
	    $framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/album_details.tpl");
	break;
	
	
	
			
	case 'setAdvertisementClickView':
		
		$adv_id = $_POST['adv_id'];
		
		if ( $adv_id>0 )	{
			$res = $objAdvertiser->CheckAdvClickManage($adv_id);
			echo $res;
		}
		
		exit;
		break;
		
	case 'setAdvertisementDetailsView':
		
		$adv_id = $_POST['adv_id'];
		
		if ( $adv_id>0 )	{
			$res = $objAdvertiser->CheckAdvViewManage($adv_id);
			echo $res;
		}
		
		exit;
		break;		
		
	case 'checkMultipleDestination':
		
		
		$searchKey 	= $_POST['searchKey'];
		$input 		= strtolower( trim($searchKey) );
		
		list($RESULTS,$FVAL)	=	$objSearch->getDestinationMultiple($input);
		
		
		if ( count($RESULTS) == 1  ) {	
			echo "TRUE|".$FVAL;							# Only one Reults
		}elseif (  count($RESULTS) == 0 ) {
			echo "NIL";								# UnRecognized Cities
		}elseif (  count($RESULTS) > 1 ) {
			//$RESULTS 	= 	array_keys($RESULTS);
			echo "TRUE|".$FVAL;	
			/*$JsonObj	=	new Services_JSON();
			$EncodedStr	=	$JsonObj->encode($RESULTS);
			echo $EncodedStr;*/
		}
		
		
		exit;
		break;			
	
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>