<?php
/**
* Advertiser 
* Author   : Aneesh Aravindan
* Created  : 20/Nov/2007
* Modified : 20/Nov/2007 By Aneesh Aravindan
*/

include_once(FRAMEWORK_PATH."/modules/advertiser/lib/class.advertiser.php");
include_once(FRAMEWORK_PATH."/modules/map/lib/G_Maps.class.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.payment.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");

$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "flyer_id:DESC";


$param			=	"mod=$mod&pg=$pg&act=mylist&orderBy=$orderBy&sId=$sId&fId=$fId";

$objAdvertiser	=	new Advertiser();
$gmap	 		= 	new G_Maps();
$flyer			=	new	Flyer();
$email			= 	new Email();
$PaymentObj		=	new Payment();
$user           =   new User();
checkLogin();




switch($_REQUEST['act']) {

 		case "mylist":
            
 			$userID			=	$_SESSION["memberid"];
	
			list($rs, $numpad, $cnt, $limitList)	= 	$objAdvertiser->listAdvertisementByUser($userID,$pageNo,3,$param,OBJECT, $orderBy);
			
		
			$framework->tpl->assign("ADVERTISE_LIST", $rs);
			$framework->tpl->assign("ADVERTISE_NUMPAD", $numpad);
			$framework->tpl->assign("ADVERTISE_LIMIT", $limitList);
	
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/advertiser/tpl/advertisement_list.tpl");
		break;

		case "myform_add":

			$userID			=	$_SESSION["memberid"];
			$DEFAULT_ACTIVE	=	'step1NewAdd';

			$Adv_Img_Size 	=   $objAdvertiser->getConfigurationByfield('adv_img_size');
			$Adv_Img_Size	=	explode ( "," , $Adv_Img_Size['value']);
			
			
			if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_REQUEST['Submit1']) )	{
				
				$ADV_ARRAY		=	$objAdvertiser->getFlyerIdByAdvertisement(	$_REQUEST['id'] );
				$req = &$_REQUEST;

				
				
				
				if ( $_FILES['adv_file']['name'] )	{
					$req['adv_img_name']	=	basename($_FILES['adv_file']['name']);
					$req['adv_img_type']	=	$_FILES['adv_file']['type'];
					$req['adv_imgtmpname']	=	$_FILES['adv_file']['tmp_name'];
				}
	
				$req['userID']				=	$userID;

				if( ($message = $objAdvertiser->editAdvDetailsByUser ( $req )) > 0 ) {
					if ( $ADV_ARRAY['flyer_id'] < 1 )	{
					$DEFAULT_ACTIVE	=	'step2NewAdd';
					redirect(makeLink(array("mod"=>"advertiser", "pg"=>"mylist"), "act=myform_criteria&id={$message}"));
					}else{
						if ( $ADV_ARRAY['publish'] == 'N' )	{ # Checking Current Status is Published
							redirect(makeLink(array("mod"=>"advertiser", "pg"=>"mylist"), "act=add_publish&id={$message}"));	}else{
							redirect(makeLink(array("mod"=>"advertiser", "pg"=>"mylist"), "act=mylist"));
						}
					}
				} else {
					$framework->tpl->assign("ADVERTISE_DATA", $req);
					setMessage($message);
				}
			}

				
			# Begin Advertisement Edit Section
			if ($_REQUEST['id'] && !empty($_REQUEST['id']))	{
				$req	=	$objAdvertiser->getAdvertisement ( $_REQUEST['id'] );
				if ($req) {
					$framework->tpl->assign("ADVERTISE_DATA", $req);
					$framework->tpl->assign("EDIT_FLAG", 1);
				} else {
					redirect(makeLink(array("mod"=>"advertiser", "pg"=>"mylist"), "act=mylist"));
				}
			}
			# End Advertisement Edit Section
			
			
			$framework->tpl->assign("Adv_Img_Size", $Adv_Img_Size);
			$framework->tpl->assign("DEFAULT_ACTIVE", $DEFAULT_ACTIVE);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/advertiser/tpl/advertisement_form.tpl");
		break;

	case "myform_criteria":

			$userID			=	$_SESSION["memberid"];
			$DEFAULT_ACTIVE	=	'step2NewAdd';
			
			
			if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_REQUEST['Submit2']) )	{
				$req = &$_REQUEST;		
				
				if ( $_FILES['custom_icon']['name'] )	{
					$req['custom_icon_name']	=	basename($_FILES['custom_icon']['name']);
					$req['custom_icon_type']	=	$_FILES['custom_icon']['type'];
					$req['custom_icontmpname']	=	$_FILES['custom_icon']['tmp_name'];
				}

				if ( $_FILES['custom_sicon']['name'] )	{
					$req['custom_sicon_name']	=	basename($_FILES['custom_sicon']['name']);
					$req['custom_sicon_type']	=	$_FILES['custom_sicon']['type'];
					$req['custom_sicontmpname']	=	$_FILES['custom_sicon']['tmp_name'];
				}
				
				  $type="upload_advertisement";
				  $email->mailSend($type,$userID,$_REQUEST);
								
				if( ($message = $objAdvertiser->editAdvCriteriaDetailsByUser ( $req )) === true ) {
					$DEFAULT_ACTIVE	=	'step3NewAdd';
					redirect(makeLink(array("mod"=>"advertiser", "pg"=>"mylist"), "act=myform_map&id={$req['id']}"));
				} else {
					$framework->tpl->assign("ADVERTISE_DATA", $req);
					setMessage($message);
				}
				
				              
			}
	

			# Begin Advertisement Edit Section
			if ($_REQUEST['id'] && !empty($_REQUEST['id']))	{
				$req	=	$objAdvertiser->getAdvertisement ( $_REQUEST['id'] );
				if ($req) {
					$framework->tpl->assign("ADVERTISE_DATA", $req);

					if ( trim ( $req['adv_types'] ) )
					$framework->tpl->assign("ADVERTISE_TYPES",explode(",",$req['adv_types']) );

					$framework->tpl->assign("EDIT_FLAG", 1);
				} else {
					redirect(makeLink(array("mod"=>"advertiser", "pg"=>"mylist"), "act=mylist"));
				}
			}else {
					redirect(makeLink(array("mod"=>"advertiser", "pg"=>"mylist"), "act=mylist"));
			}
			# End Advertisement Edit Section

			
			$framework->tpl->assign("FORMS",$flyer->getallForms()); 
			$framework->tpl->assign("COUNTRY_LIST", $user->listCountry());
			$framework->tpl->assign("MAP_ICONS", $objAdvertiser->getMapIcons());			
			$framework->tpl->assign("DEFAULT_ACTIVE", $DEFAULT_ACTIVE);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/advertiser/tpl/advertise_list_criteria_form.tpl");
		break;

	case "myform_map":

		
		$userID			=	$_SESSION["memberid"];
		$DEFAULT_ACTIVE	=	'step3NewAdd';

		# Begin Map Tab
		$gmap->ELEMENT_ID    = 'adv_map_add';
		$gmap->HEIGHT    	 = '550px';
		$gmap->MAP_TYPE 	 = 'G_NORMAL_MAP';
		$gmap->MAP_ZOOM		 =  14;
		# End Map Tab
		
		
		/*if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_REQUEST['Submit3']) )	{
			$req = &$_REQUEST;				
										
			if( ($message = $objAdvertiser->editAdvMapDetailsByUser ( $req )) === true ) {
				$DEFAULT_ACTIVE	=	'step1NewAdd';
				redirect(makeLink(array("mod"=>"advertiser", "pg"=>"mylist"), "act=mylist"));
			} else {
				$framework->tpl->assign("ADVERTISE_DATA", $req);
				setMessage($message);
			}
		}*/
		
		
		# Begin Advertisement Edit Section
		if ($_REQUEST['id'] && !empty($_REQUEST['id']))	{
			$req	=	$objAdvertiser->getAdvertisement ( $_REQUEST['id'] );
			if ($req) {
				
				
				$framework->tpl->assign("ADVERTISE_DATA", $req);
				$framework->tpl->assign("EDIT_FLAG", 1);

				

				$query	=	'"' . $req['street'] . '"' .',' .'"' . $req['zip'] .'"' . ',' .'"' . $req['city'] .'"' . ',' .'"' . $req['state'] .'"' . ',' .'"' . $req['country'] . '"';

				
									
				
				$req['adv_description']	=	str_replace("<br>","",$req['adv_description']);
				$advDescription	=	wordwrap(substr($req['adv_description'],0,60), 30, "<br>", true);
				
				if (strlen($req['adv_description'])>60)
				$advDescription .= $advDescription . "...";
				
				
				$advDescription	= str_replace(chr(13), "", $advDescription);
				$advDescription = str_replace(array("\r\n", "\r", "\n"), "", $advDescription);
				
				# Begin PopUp Contents on Map #
				$popStr	=	"";
				$popStr	.= "<div align=\"center\" style=\"width:200px\" class=\"border\">";
				
				if(trim($req['adv_title']))
				$popStr	.= "<div align=left class=smalltext3  style=width:80%;text-align:justify><b>" . $req['adv_title'] . "</b></div>";
				
			
				
				if(trim($req['adv_image']))
				$popStr	.= "<div align=center style=\"width:80%;text-align:justify\"><img  src=". SITE_URL . "/modules/advertiser/images/thumb/" . $req['id'] . "." . $req['adv_image'] ."></div><br>";	
					
				if(trim($advDescription))
				$popStr	.= "<div align=\"center\" class=\"meroonsmall\" style=width:80%;text-align:justify>" . $advDescription . "</div>";	
				
				
				
				if(trim($req['adv_url']))
				$popStr	.= "<div align=\"left\" style=\"width:80%;\"><a target=_blank href=".$req['adv_url'].">View Details</a><br></div>";
				
				$popStr	.= "</div>";
				# End PopUp Contents on Map #
				


				$custIcon = array();

				if ( trim($req['custom_icon']) ) {
					$custIcon[0]	= SITE_URL .'/modules/advertiser/icons/thumb/' . $req['id'] . '.' . $req['custom_icon'];
					$custIcon[1]	= SITE_URL .'/modules/advertiser/icons/thumb/' . $req['id'] . 's' . '.' . $req['custom_sicon'];

				} else {
					$custIcon[0]	= $req['icon_path'];
					$custIcon[1]	= $req['icon_spath'];
				}

				
				
				$imgRes			= getimagesize($custIcon[0]);
				$imgsRes		= getimagesize($custIcon[1]);


				$custIcon[2]	= $imgRes[0];				#Main Image Width
				$custIcon[3]	= $imgRes[1];	 			#Main Image Height
				$custIcon[4]	= $imgsRes[0];				#Shadw Image Width
				$custIcon[5]	= $imgsRes[1];	 			#Shadw Image Height

				if($req['lat_lon'])	{
					$mapSetPosStr	=	"new GLatLng{$req['lat_lon']}";
					$framework->tpl->assign("mapSetPosStr", $mapSetPosStr);
				}
				
				$framework->tpl->assign("CUST_ICONS", $custIcon);
				$framework->tpl->assign("query", $query);
				$framework->tpl->assign("popStr", $popStr);

			} else {
				redirect(makeLink(array("mod"=>"advertiser", "pg"=>"mylist"), "act=mylist"));
			}
		}else {
				redirect(makeLink(array("mod"=>"advertiser", "pg"=>"mylist"), "act=mylist"));
		}
		# End Advertisement Edit Section
		
		
		$framework->tpl->assign("My_Map", $gmap->showMap());
		$framework->tpl->assign("DEFAULT_ACTIVE", $DEFAULT_ACTIVE);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/advertiser/tpl/advertise_list_map_form.tpl");
			
		break;	

		
		
	case "add_publish":
		
 			$userID			=	$_SESSION["memberid"];
			$Adv_ID			=	$_REQUEST['id'];
	
 			if ( $userID > 0 && $Adv_ID> 0 ) {
				checkLogin();
				
				$RS	=	$objAdvertiser->getAdvertisement($Adv_ID);
				
				/*CHECK IF BUDGET SET*/
				if ( $RS['adv_budget']<1 ) {
				setMessage("ADVERTISEMENT BUDGET REQUIRED");
				redirect(makeLink(array("mod"=>"advertiser", "pg"=>"mylist"), "act=myform_add&id=$Adv_ID"));
				break;	
				}
				/*CHECK IF BUDGET SET*/
				
				
				$_SESSION['AdvSessId']	=	$Adv_ID;
				$EncodedSessionId		=	$PaymentObj->encodeSession();
 				
 				
 				$framework->tpl->assign("ADVERTISE_DATA", $RS);
 				$framework->tpl->assign('ENCODED_SESSION_ID', $EncodedSessionId);
 				
 				
 				$MemberDetails			=	$user->getUserdetails($_SESSION['memberid']);
				$Country2LetterCode		=	$user->getCountry2LetterCode($MemberDetails['country']);
 				
 				$framework->tpl->assign('COUNTRY2_CODE', $Country2LetterCode);
				$framework->tpl->assign('MEMBER_DETAILS', $MemberDetails);
 				
 				
 				
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/advertiser/tpl/advertisement_publish.tpl");
 			} else {
 				redirect(makeLink(array("mod"=>"advertiser", "pg"=>"mylist"), "act=mylist"));
 			}
			
		break;	

	case "add_report":
            $Params					=	"mod=$mod&pg=$pg&act={$_REQUEST['act']}";
    		$_REQUEST['limit'] 		= 	$_REQUEST['limit'] ? $_REQUEST['limit'] : 20;
    		$_REQUEST['orderBy'] 	= 	$_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : "T1.id:DESC";
 			$userID			=	$_SESSION["memberid"];
			$Adv_ID			=	$_REQUEST['id'];
			$s_date			=	$_REQUEST['startdate'];
			if ($s_date!='')
			$s_date = date("Y-m-d",strtotime($s_date));
			$en_date		=	$_REQUEST['enddate'];
			if ($en_date!='')
			$en_date = date("Y-m-d",strtotime($en_date));
			$Rs = $objAdvertiser->get_adv_date_info($Adv_ID,$s_date,$en_date);
			//print_r($Rs);
			if (count($Rs)>0){
				$arrayIndex = 0;
				foreach ($Rs as $date_details){
					$ip_details[] = $objAdvertiser->get_adv_ip_info($Adv_ID,$date_details['date_added']);
					//$user_detail = $user->getUserdetails($_SESSION['memberid']);
					
				}
			}
			/*if (count($ip_details)>0){
				foreach (array_keys($ip_details) as $key)
				{
					$array1 = array("first_name"=>"Vipin");
					//echo	 count($ip_details[$key]);
					array_merge($ip_details[$key],$array1);
				}
			}*/
			//print_r($ip_details);
			$framework->tpl->assign("ADVERTISE_REPORT_DATE", $Rs);
 			$framework->tpl->assign('IP_DETAIL', $ip_details);
 			if ( $userID > 0 && $Adv_ID> 0 ) {
 			
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/advertiser/tpl/advertisement_report.tpl");
 			} else {
 				redirect(makeLink(array("mod"=>"advertiser", "pg"=>"mylist"), "act=mylist"));
 			}
			
		break;		
		
		
	case "removeAdd":
			if ( $objAdvertiser->DeleteAdvertisement($_REQUEST['adv_ID']) == true ) 
			echo "location.href='".makeLink(array("mod"=>"advertiser", "pg"=>"mylist"), "act=mylist")."';";
			else
			echo "location.href='".makeLink(array("mod"=>"advertiser", "pg"=>"mylist"), "act=mylist")."';";
			
			exit;
		break;

	case 'updateAdvMapPos':

		if ( $objAdvertiser->UpdateAdvPositionMap($_REQUEST) == true ) { 
			$ADVID	=	$_REQUEST['id'];
			$ADV_ARRAY		=	$objAdvertiser->getFlyerIdByAdvertisement(	$ADVID );
			if ( $ADV_ARRAY['publish'] == 'N' )	{ # Checking Current Status is Published
				echo "location.href='".makeLink(array("mod"=>"advertiser", "pg"=>"mylist"), "act=add_publish&id={$ADVID}")."';";
			}else{
				echo "location.href='".makeLink(array("mod"=>"advertiser", "pg"=>"mylist"), "act=mylist")."';";		}
		}else{
			echo "location.href='".makeLink(array("mod"=>"advertiser", "pg"=>"mylist"), "act=mylist")."';";
		}
		
		exit;
		break;
		
	case 'updateAdvMapPosonLoad':
		$id	=	$_REQUEST['id'];
		if ( $objAdvertiser->UpdateAdvPositionMaponLoad($_REQUEST) == true ) 
		echo "yes";
		else
		echo "no";

		exit;
		break;	

	case 'clearAdvMapPos':
		$id	=	$_REQUEST['id'];
		if ( $objAdvertiser->UpdateAdvPositionMap($_REQUEST) == true ) 
		echo "yes";
		else
		echo "no";
		
		exit;
		break;

	case 'checkAdvMapPosCustomSet':
		$id	=	$_REQUEST['id'];
		if ( $objAdvertiser->CheckAdvPositionMapCustom($_REQUEST) == true ) 
		echo "yes";
		else
		echo "no";
		
		exit;
		break;	

	case 'addAlbumAdvertise':

		$prop_id		=	$_REQUEST['propid'];
		
		list ($ResAdded,$newAddID)			=	$objAdvertiser->addAlbumAdvertisement($_REQUEST);
		
		//echo "document.getElementById('divError').innerHTML=\"This Property Already Advertised, <a class=smalltext3 href='".makeLink(array("mod"=>"advertiser", "pg"=>"mylist"), "act=myform_criteria&id=$newAddID")."'>Click here to manage</a>\";";


		if ( $ResAdded != false ) 
		echo "location.href='".makeLink(array("mod"=>"advertiser", "pg"=>"mylist"), "act=myform_add&id=$newAddID")."';";
		else
		echo "location.href='".makeLink(array("mod"=>"advertiser", "pg"=>"mylist"), "act=myform_add&id=$newAddID")."';";
		exit;
		break;
		
		
		
}

$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>