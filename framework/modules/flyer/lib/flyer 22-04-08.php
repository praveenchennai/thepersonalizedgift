<?
session_start();
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/includes/class.framework.php");
include_once(FRAMEWORK_PATH."/modules/callendar/lib/class.calendartool.php");
include_once(FRAMEWORK_PATH."/modules/map/lib/G_Maps.class.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.photo.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.property.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.payment.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.search.php");
include_once(FRAMEWORK_PATH."/modules/callendar/lib/class.calendarevents.php");

$fw				=	new FrameWork();
$user           =   new User();
$email			= 	new Email();
$flyer			=	new	Flyer();
$gmap	 		= 	new G_Maps();
$album			=	new Album();
$photo			=	new Photo();
$property 		=	new Property();
$payment        =   new Payment();
$search         =   new Search();
$objCalendar 	=	new CalendarTool ();
$objEVENTS		= 	new CalendarEvents();

$objCategory	=	new Category();
$objFlyer		=	new Flyer();
$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$keysearch		=	$_REQUEST["keysearch"] ? $_REQUEST["keysearch"] : "N";
$flyer_search	=	$_REQUEST["flyer_search"] ? $_REQUEST["flyer_search"] : "";
$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "title";
$parent_id 		= 	$_REQUEST["parent_id"] ? $_REQUEST["parent_id"] : "0";
$status_id	=	$_REQUEST['status_id'] ? $_REQUEST['status_id'] : "";
$param			=	"mod=$mod&pg=$pg&act=list&orderBy=$orderBy&parent_id=$parent_id&status_id=$status_id&flyer_search=$flyer_search&keysearch=$keysearch&sId=$sId&fId=$fId";

switch($_REQUEST['act']) {
	case "list":
		
		$parent_id		=	isset($_REQUEST["cat_id"])?trim($_REQUEST["cat_id"]):"0";
		$status_id	=	$_REQUEST['status_id'] ? $_REQUEST['status_id'] : "";
		if($parent_id=="")
		$parent_id	=	0;
		list($rs, $numpad, $cnt, $limitList)	= 	$objFlyer->listMyFlyers('A',$keysearch,$flyer_search,$pageNo,$limit,$param,OBJECT, $orderBy,$status_id);//print_r($rs);exit;
		$cur_date	=	date('Y-m-d');
		$framework->tpl->assign("CUR_DATE", $cur_date);
		//print_r($rs->modified_date);exit;
		//$framework->tpl->assign("DATENEW",$global["date_format_new"]);
			$i=0;
		foreach ($rs as $dt){
			$st=date($global["date_format_new"],strtotime($dt->modified_date));
			$rs[$i]->modified_date=$st;
			$i++;
		}
		//print_r($rs[0]->modified_date);exit;
		$framework->tpl->assign("FLYER_LIST", $rs);
		$framework->tpl->assign("STATUS_ID", $status_id);
		$framework->tpl->assign("ACT", "form");
		$framework->tpl->assign("FLYER_NUMPAD", $numpad);
		$framework->tpl->assign("FLYER_LIMIT", $limitList);
		$framework->tpl->assign("FLYER_SEARCH_TAG", $flyer_search);
		$framework->tpl->assign("FLYER_PATH", SITE_URL."/htmlflyers/");
		if($parent_id>0)
		{
			$framework->tpl->assign('SELECT_DEFAULT', '--- '.$objCategory->getCategoryname($parent_id).' ---');
		}
		else
		{
			$framework->tpl->assign('SELECT_DEFAULT', "");
		}
		
		
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/flyer/tpl/flyer_list.tpl");
		break;
		##### Code is Added To Show The property details  in admin side........
		##### Modified By Jipson Thomas	
		##### Dated 7 April 008.
		
		case 'property_view':
		//checkLogin();
		$check          =   $_REQUEST['details'];
		$flyer_id		=	$_REQUEST['flyer_id'];
		$prop_id		=	$_REQUEST['propid'];
		$flyerInfo      =   $flyer->getFlyerBasicFormData($_REQUEST['flyer_id']);
		$FloatingPrice  =   $flyer->getFlatingPriceResults($prop_id);
		$FormDetails	=	$flyer->getContactInfoOfFlyer($flyer_id);
		$FlyerBasicData	=	$flyer->getFlyerBasicFormData($flyer_id);	
		
		# Begin Map SECTION #
		# Begin Map SECTION #
		# Begin Map SECTION #
		
		$query	= "";
		
			
		if (  $FormDetails['location_street_address'] )
		$query	.=	$FormDetails['location_street_address']. ','; 
		if (  $FormDetails['location_zip'] )
		$query	.=	$FormDetails['location_zip'] . ',';
		if (  $FormDetails['location_city'] )
		$query	.=	$FormDetails['location_city'] . ',';
		if (  $FormDetails['location_state'] )
		$query	.=	$FormDetails['location_state'] . ',';
		if (  $FormDetails['location_country'] )
		$query .= $FormDetails['location_country'] . ',';
		
		
		
		$query = substr($query,0,-1);
		


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
        $framework->tpl->assign("CHECK", $check);
		# End Map SECTION #
		# End Map SECTION #
		# End Map SECTION #
		
		/* Flat and Fixed rate Array */
		$FIXED_BLOCK = $flyer->getFixedFlatResults($prop_id,"N",$_SESSION['memberid']);

		$framework->tpl->assign("FIXED_BLOCK",$FIXED_BLOCK);
		
		$FLAT_BLOCK = $flyer->getFixedFlatResults($prop_id,"Y");
		$framework->tpl->assign("FLAT_BLOCK",$FLAT_BLOCK);
		
		
		$sprice             =   $flyer->getPropertySpecialPrice($prop_id);
		$rsFlbasic			=	$flyer->getFlyerBasicFormData($flyer_id);
		$rsAlbm		  		=   $album->getAlbumDetails($prop_id); 
		$rsFlvFeAt			=	$flyer->getFlyerDataForPreview($flyer_id);// Get the features and Attributes
		list($quantityTitle)		=	$flyer->getQuantityTitle($prop_id);
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
		$block_quantity_array			=	$flyer->getPropertyBlockQuantity($prop_id,$_SESSION['memberid']);
		
		$button_caption = $MOD_VARIABLES["MOD_BUTTONS"]["BT_RENT"]; 
		
		// CALENDAR DISPLAY PART
		
		list($CALENDARLIST,$CALENDAR_NAVIG)= $objEVENTS->printCalendarValues($_REQUEST,$objCalendar,$_SESSION["memberid"],"MONTH",$FlyerBasicData["title"]);
		$MONTH_VIEW = $objEVENTS->printCalendar($CALENDAR_NAVIG,$CALENDARLIST,"MONTH");
		$publish=$search->basicAlbumInfo($_REQUEST["propid"]);
		//print_r($publish);
		$framework->tpl->assign("PUBLISH",$publish);
		
		$framework->tpl->assign("SPRICE",$sprice);
		$framework->tpl->assign("PHOTO_LIST",$rsPhoto);
		$framework->tpl->assign("VIDEO_LIST",$rsVdo);
		$framework->tpl->assign("DEFAULT_IMG",$rsAlbm["default_img"]);
		$framework->tpl->assign("DEF_IMG_EXT",$photo->imgExtension($rsAlbm["default_img"]));
		$framework->tpl->assign("FLYER_BASIC",$rsFlbasic);
		$framework->tpl->assign("SPECIAL_PRICE",$flyer->getSpecialPriceDetails($prop_id));
		$framework->tpl->assign("BIDPRICE",$flyer->getBidPriceList($prop_id));
		/////////////////\
		$FIXED_BLOCK1 = $flyer->getFixedBidResultsByPropid($_REQUEST['propid']);
		//print_r($FIXED_BLOCK);

		$framework->tpl->assign("FIXED_BLOCK1",$FIXED_BLOCK1);
		
		//////////////////
		$framework->tpl->assign("BLOCK_QUANTITY_TITLE",$rsBlockPropList);
		$framework->tpl->assign("QUANTITY_TITILE",$quantityTitle);
		$framework->tpl->assign("SPECIFIC_DATE_PRICE",$flyer->getSpecificDatesPriceList($prop_id));
		$framework->tpl->assign("FLYER_FEATURES",$rsFlvFeAt);
		$framework->tpl->assign("FLYER_ATTRIBUTES",$rsFlvFeAt["blocks"]["ATTRIBUTES"]["attributes"]);
		$framework->tpl->assign("album_id",$FormDetails['flyer_id']);
		$framework->tpl->assign("MAP", FRAMEWORK_PATH."/modules/flyer/tpl/property_map_list_view.tpl");
		$framework->tpl->assign("CALENDAR",SITE_PATH."/modules/album/tpl/mycalendar_search.tpl");
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/flyer/tpl/property_preview.tpl");
		$framework->tpl->assign("BLOCKD_QUANTITY",$block_quantity_array);
		$framework->tpl->assign("MONTH_VIEW",$MONTH_VIEW);
		
		$framework->tpl->assign("BUTTON_RENT",$flyer->createPropertyImagebutton($button_caption,$href='javascript:movetoAvialable();'));
		break;
		
		#### End of the code
		case "delete":
			extract($_POST);
			if(count($category_id)>0) 		{
			$message=true;
			foreach ($category_id as $flyer_id)
				{  
				
				if($objFlyer->flyerDelete($flyer_id)==false)
					$message=false;
				}
			}
			if($message==true)
				setMessage($_REQUEST["sId"]."(s) Deleted Successfully!", MSG_SUCCESS);
			if($message==false)
				setMessage($_REQUEST["sId"]."(s) Can not Deleted!");
			redirect(makeLink(array("mod"=>"flyer", "pg"=>"flyer"), "act=list&cat_id={$_REQUEST['cat_id']}&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
			break;
		
		 /*
    		Created:Vipin
  			Date:15-April-2008
   			get  the property details of a property
	 	 */
		
		case "view_property":
			$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "0";
			$albumID = $_REQUEST['propid'];
			list($rs, $numpad) = $album->getBookingPropertyDetails( $pageNo,10,'',ARRAY_A,$_REQUEST["orderBy"],$albumID);
			$i=0;
			foreach ($rs as $dt){
			$st=date($global["date_format_new"],strtotime($dt['bookdate']));
			$gt=date($global["date_format_new"],strtotime($dt['edate']));
			$mt=date($global["date_format_new"],strtotime($dt['sdate']));
			$rs[$i]['bookdate']=$st;
			$rs[$i]['edate']=$gt;
			$rs[$i]['sdate']=$mt;
			$i++;
			}
			$framework->tpl->assign("FLYER_LIST",$rs);
			$framework->tpl->assign("FLYER_NUMPAD",$numpad);
			$framework->tpl->assign("PAGE_NO",$pageNo);
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/flyer/tpl/property_view.tpl");
		break;
	
}

$framework->tpl->display($global['curr_tpl']."/admin.tpl");

?>