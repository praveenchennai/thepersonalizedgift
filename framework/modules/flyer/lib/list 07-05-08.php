<?
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");
include_once(FRAMEWORK_PATH."/includes/class.framework.php");
include_once(FRAMEWORK_PATH."/modules/callendar/lib/class.calendartool.php");
include_once(FRAMEWORK_PATH."/modules/map/lib/G_Maps.class.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.photo.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.property.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.payment.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.search.php");
include_once(FRAMEWORK_PATH."/modules/callendar/lib/class.calendarevents.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.broker.php");

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
$broker         =   new Broker();

$framework->tpl->assign("COUNTRY_LIST", $user->listCountry());

#---------------
$keysearch		=	$_REQUEST["keysearch"] ? $_REQUEST["keysearch"] : "N";
$category_search=	$_REQUEST["category_search"] ? $_REQUEST["category_search"] : "";
$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "0";
$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "title";
$parent_id 		= 	$_REQUEST["parent_id"] ? $_REQUEST["parent_id"] : "0";
$status_id	=	$_REQUEST['status_id'] ? $_REQUEST['status_id'] : "";
$param			=	"mod=$mod&pg=$pg&act=flyer_list&status_id=$status_id&category_search=$category_search&keysearch=$keysearch";


#-----------------
$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";

switch($_REQUEST['act']) {
	case "flyer_form":
		checkLogin();
		$form_id	=	$_REQUEST['form_id'];
		$flyer_id	=	$_REQUEST['flyer_id'] ? $_REQUEST['flyer_id'] : "0";
		if($flyer_id=="0" || $flyer_id=="")
		{ 
			// checking the flyer restriction for the user
			if($fw->maxRecordLimit($_SESSION["memberid"],'flyer_data_basic','user_id','form_id!=\'0\' AND active=\'Y\'') === TRUE)
			{
				setMessage("You have reached the published Listing limit for this account. Please upgrade to create more Listing.");
				redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_list&form_id=$form_id&flyer_id=$flyer_id"));
			}
		
			$flyer_id =$flyer->getFlyerId();
			redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_form&form_id=$form_id&flyer_id=$flyer_id"));
		}
		
		
		// end Giving the flyer id 
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			// add new feature -cancel button
			if(isset($_REQUEST['btn_user_field_cancel_x']) )
			{
				$req 			=	&$_POST; 
				//$message 		= 	$flyer->FlyerAddEdit($req);
				redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_form&form_id=$form_id&flyer_id=$flyer_id"));
			}
			// saving the user created drop down values
			if(isset($_REQUEST['btn_user_dropdown_x']) )
			{
				$req 			=	&$_POST;
				$message 		= 	$flyer->FlyerAddEdit($req);
				$flyer->addUserDropdown($req,$flyer_id,$form_id);
				redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_form&form_id=$form_id&flyer_id=$flyer_id"));
			}
			// saving tdraft
			if(isset($_REQUEST['btn_draft_x']) )
			{
				
				$req 			=	&$_POST;  
				$message 		= 	$flyer->FlyerAddEdit($req);
				$flyer->addUserDropdown($req,$flyer_id,$form_id);
				redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_list&form_id=$form_id&flyer_id=$flyer_id"));
			}
			// saving the user created fields
			if(isset($_REQUEST['btn_user_field_add_x']) )
			{
				$req 			=	&$_POST;  
				$message 		= 	$flyer->FlyerAddEdit($req);
				$flyer->addUserFields($req,$flyer_id,$form_id);
				redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_form&form_id=$form_id&flyer_id=$flyer_id"));
			}
			// saving user created checkboxes
			if(isset($_REQUEST['btn_user_checkbox_add_x']) )
			{
				$req 			=	&$_POST;  
				$message 		= 	$flyer->FlyerAddEdit($req);
				$flyer->addUserCheckbox($req,$flyer_id,$form_id);
				redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_form&form_id=$form_id&flyer_id=$flyer_id"));
			}
			// saving the links for the flyer
			if(isset($_REQUEST['btn_link_add_x']) )
			{
				$req 			=	&$_POST;  
				$message 		= 	$flyer->FlyerAddEdit($req);
				$flyer->addLinks($req,$flyer_id,$form_id);
				redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_form&form_id=$form_id&flyer_id=$flyer_id"));
			}
			
			if(isset($_REQUEST['btn_publish_x']) )
			{
				$req 			=	&$_POST;  
				$message 		= 	$flyer->FlyerAddEdit($req);
				$flyer->generateFlyerForPublish($flyer_id);
				$link_name	=	"http://".$my_subDomain_name.".".DOMAIN_URL."/".$flyer_id."/index.php";
				$adminEmail=	$email->getAdminEmail();	
				$mail_header	=	array(	"from"	=>	$framework->config['admin_email'],
											"to"	=>	$adminEmail);
				$dynamic_vars 	=	array(	"LINK"		=>	$link_name);
				$email->send('listing_published', $mail_header, $dynamic_vars);
			
				if($keyPhotographs=="Y") {
					redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_photographs&form_id=$form_id&flyer_id=$flyer_id"));
				}
				else {
					redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=publish_sucess&form_id=$form_id&flyer_id=$flyer_id"));
				}
			}
			if($keyPhotographs=="Y") {
				$req 			=	&$_POST;  
				$message 		= 	$flyer->FlyerAddEdit($req);
				redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_photographs&form_id=$form_id&flyer_id=$flyer_id"));

			
			}
			
			if(isset($_REQUEST['btn_preview_x'])) {
				$req 			=	&$_POST;  
				$message 		= 	$flyer->FlyerAddEdit($req);
				redirect(makeLink(array("mod"=>"flyer", "pg"=>"preview"), "act=flyer_preview&flyer_id=$flyer_id"));
			}
			
			
		}
		if($flyer_id) {
			$framework->tpl->assign("FLYER_VALUE", $flyer->GetFlyerData($flyer_id));
			$framework->tpl->assign("CHECKBOX_VALUE", $flyer->GetFlyerCheckboxData($flyer_id));
			$framework->tpl->assign("LINK_VALUE", $flyer->GetFlyerCheckboxData($flyer_id)); 
		}
		// checking wether publish or not
		$publish_status	=	$flyer->getFlyerPublishStatus($flyer_id);
		// checking wether publish or not
		
		//print_r($flyer->GetFlyerForm($form_id,$flyer_id));
		
		$framework->tpl->assign("PUBLISH_STATUS",  $flyer->getFlyerPublishStatus($flyer_id));
		$framework->tpl->assign("TEMPLATES",  $flyer->GetTemplates());
		$framework->tpl->assign("FORM_ARRAY", $flyer->GetFlyerForm($form_id,$flyer_id));
		$framework->tpl->assign("FORM_ID", $form_id);
		$framework->tpl->assign("FLYER_ID", $flyer_id);
		$framework->tpl->assign("FLYER_MAIN_PHOTO", $flyer->GetmainPhoto($flyer_id));
		$framework->tpl->assign("MEMBER_IMAGES", $objUser->getUserdetails($_SESSION["memberid"]));
		$framework->tpl->assign("FLYER_LINKS", $flyer->GetLinks($flyer_id));
		$framework->tpl->assign("FLYER_GALLERY_PHOTO", $flyer->GetgalleryPhoto($flyer_id));
		
		#$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/flyer_form.tpl");
		
		$topsubmenu=$objCms->linkList("flyer_list");
		if($_REQUEST["pid"])
		{
			$pid=$_REQUEST["pid"];
		}else{
			$pid=$topsubmenu[0]->id;
		}
		
		$framework->tpl->assign("TOPSUB_MENU", $topsubmenu);
		$framework->tpl->assign("PID", $pid);
		$framework->tpl->assign("form_file",SITE_PATH."/modules/flyer/tpl/flyer_form.tpl");
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/flyer_form_display.tpl");
		break;
		
		case "publish_sucess":
		$flyer_id	=	$_REQUEST['flyer_id'] ? $_REQUEST['flyer_id'] : "0";
		$form_id	=	$_REQUEST['form_id'] ? $_REQUEST['form_id'] : "0"; 
		$flyer_url	=	"http://".$my_subDomain_name.".".DOMAIN_URL."/".$flyer_id."/index.php";
		$Code		=	$flyer->getCraigsListHtmlCode($flyer_id);
		$framework->tpl->assign("DOWNLOAD_PDF",SITE_URL."/pdfflyers/".$flyer_id.".pdf");
		$framework->tpl->assign("FLYER_URL", $flyer_url);
		$framework->tpl->assign("CRAIGLIST_CODE", $Code);
		$framework->tpl->assign("FLYER_ID", $flyer_id);
		$framework->tpl->assign("FORM_ID", $form_id);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/flyer_published.tpl");
		break;
		
		case "publish_sucess_ebay":
		$flyer_id	=	$_REQUEST['flyer_id'] ? $_REQUEST['flyer_id'] : "0";
		$form_id	=	$_REQUEST['form_id'] ? $_REQUEST['form_id'] : "0"; 
		$flyer_url	=	"http://".$my_subDomain_name.".".DOMAIN_URL."/".$flyer_id."/index.php";
		$Code		=		$flyer->getHTMLCodeForEbayAndOthersSites($flyer_id);
		$framework->tpl->assign("DOWNLOAD_PDF",SITE_URL."/pdfflyers/".$flyer_id.".pdf");
		$framework->tpl->assign("FLYER_URL", $flyer_url);
		$framework->tpl->assign("CRAIGLIST_CODE", $Code);
		$framework->tpl->assign("FLYER_ID", $flyer_id);
		$framework->tpl->assign("FORM_ID", $form_id);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/flyer_published_ebay.tpl");
		break;
		
		case "publish_sucess_html":
		$flyer_id	=	$_REQUEST['flyer_id'] ? $_REQUEST['flyer_id'] : "0";
		$form_id	=	$_REQUEST['form_id'] ? $_REQUEST['form_id'] : "0"; 
		$flyer_url	=	"http://".$my_subDomain_name.".".DOMAIN_URL."/".$flyer_id."/index.php";
		$Code		=		$flyer->getHTMLCodeForEbayAndOthersSites($flyer_id);
		$framework->tpl->assign("DOWNLOAD_PDF",SITE_URL."/pdfflyers/".$flyer_id.".pdf");
		$framework->tpl->assign("FLYER_URL", $flyer_url);
		$framework->tpl->assign("CRAIGLIST_CODE", $Code);
		$framework->tpl->assign("FLYER_ID", $flyer_id);
		$framework->tpl->assign("FORM_ID", $form_id);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/flyer_published_html.tpl");
		break;
		
		
		case "flyer_list":
			checkLogin();
			
			$orderBy	=	$_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : "title";
			$status_id	=	$_REQUEST['status_id'] ? $_REQUEST['status_id'] : "";
			list($rs, $numpad, $cnt, $limitList)	= 	$flyer->listMyFlyers('U',$keysearch,$flyer_search,$pageNo,$limit,$param,OBJECT, $orderBy,$status_id);
			list($rs_expired, $numpad_e, $cnt_e, $limitList_e)	= 	$flyer->listMyFlyersexpired('U',$keysearch,$flyer_search,$pageNo,$limit,$param,OBJECT, $orderBy,$status_id);				
			
			$RssLink	=	'|&nbsp;&nbsp;<a target="_blank" href="'.makeLink(array("mod"=>"flyer", "pg"=>"preview"), "act=rss_flyerlist&member_id={$_SESSION['memberid']}").'"><img src="'.$global['site_url'].'/templates/blue/images/rsslink.gif" border="0" /></a>&nbsp;&nbsp;|&nbsp;&nbsp; <a class="footerlink" href="'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=rss_bookmark&member_id={$_SESSION['memberid']}").'" >RSS Help</a>';
			$framework->tpl->assign("RSS_LINK", $RssLink);
			$framework->tpl->assign("PAGE_NO", $pageNo);
			$cur_date	=	date('Y-m-d');
			$framework->tpl->assign("CUR_DATE", $cur_date);
			$framework->tpl->assign("STATUS_ID", $status_id);
			$framework->tpl->assign("FLYER_LIST", $rs);
			$framework->tpl->assign("FLYER_LIST_EXPIRED", $rs_expired);
			$framework->tpl->assign("ACT", "form");
			$framework->tpl->assign("FLYER_NUMPAD", $numpad);
			$framework->tpl->assign("FLYER_LIMIT", $limitList);
			$framework->tpl->assign("FLYER_SEARCH_TAG", $flyer_search);
			$framework->tpl->assign("FORMS",$flyer->getallForms()); 
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/flyer_list.tpl");
			break;
			
		case "flyer_gallery":
					
			$RssLink	=	'|&nbsp;&nbsp;<a target="_blank" href="'.makeLink(array("mod"=>"flyer", "pg"=>"preview"), "act=rss_flyerlist&member_id={$_SESSION['memberid']}").'"><img src="'.$global['site_url'].'/templates/blue/images/rsslink.gif" border="0" /></a>&nbsp;&nbsp;|&nbsp;&nbsp; <a class="footerlink" href="'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=rss_bookmark&member_id={$_SESSION['memberid']}").'" >RSS Help</a>';
			$framework->tpl->assign("HEADER_LINKS", $flyer->GetHeaderLinks($_SESSION["memberid"]));
			$framework->tpl->assign("FOOTER_LINKS", $flyer->GetFooterLinks($_SESSION["memberid"]));
			$framework->tpl->assign("RSS_LINK", $RssLink);
			$framework->tpl->assign("FLYER_PATH", SITE_URL."/htmlflyers/");
			$framework->tpl->assign("RSS_FLYER_FIELDS", $flyer->GetFormRssFields());
			$framework->tpl->assign("MEMBER_DETAILS", $user->getUserdetails($_SESSION["memberid"]));
			$account_url	=	"http://".$my_subDomain_name.".".DOMAIN_URL;
			$framework->tpl->assign("ACCOUNT_URL", $account_url);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/flyer_gallary_list.tpl");
			break;
		
		case "logos":
		checkLogin();
		
		$form_id	=	$_REQUEST['form_id'];
		$flyer_id	=	$_REQUEST['flyer_id'] ? $_REQUEST['flyer_id'] : "0";
		if($_SERVER['REQUEST_METHOD'] == "POST") { 
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$message 		= 	$flyer->FlyerAddEdit($req);
			if(isset($_REQUEST['btn_main_photo_x']) )
			{
				$req 			=	&$_POST; 
				$fname			=	basename($_FILES['file_main_photo']['name']);
				$ftype			=	$_FILES['file_main_photo']['type'];
				$tmpname		=	$_FILES['file_main_photo']['tmp_name'];
				$message 		= 	$flyer->memberUploadPhoto($req,$fname,$tmpname);
			}
			if(isset($_REQUEST['btn_logo_x']) )
			{
				$req 			=	&$_POST; 
				if($file_logo)
				{
					$fname			=	basename($_FILES['file_logo']['name']);
					$ftype			=	$_FILES['file_logo']['type'];
					$tmpname		=	$_FILES['file_logo']['tmp_name'];
					$message 		= 	$flyer->memberUploadLogo($req,$fname,$tmpname);
				}
			}
		}
		$topsubmenu=$objCms->linkList("topmembersub");
		if($_REQUEST["pid"])
		{
			$pid=$_REQUEST["pid"];
		}else{
			$pid=$topsubmenu[0]->id;
		}
		
		$ftype	=	$_REQUEST['ftype'] ? $_REQUEST['ftype'] : "A";
		$framework->tpl->assign("FORM_ID", $form_id);
		$framework->tpl->assign("FLYER_ID", $flyer_id);
		$framework->tpl->assign("FLAG_ID", $ftype);
		$framework->tpl->assign("TOPSUB_MENU", $topsubmenu);
		$framework->tpl->assign("PID", $pid);
		$framework->tpl->assign("DATE", date("H:i:s"));
		$framework->tpl->assign("MEMBER_IMAGES", $flyer->GetmemberImages());
		 $userDetails	=	$objUser->getUserdetails($_SESSION["memberid"]);
		 if($userDetails['reg_pack']=='1' || $userDetails['reg_pack']=='0')
		 {
			 $framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/noaccess.tpl");
		 }
		 else {
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/mylogos.tpl");
		}
		break;
		
		
		case "flyer_unpublish":
		$form_id	=	$_REQUEST['form_id'];
		$flyer_id	=	$_REQUEST['flyer_id'] ? $_REQUEST['flyer_id'] : "0";
		if($flyer_id!="0")
		$flyer->FlyerUnpublish($flyer_id);
		redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_list"));
		break;
		
		case "flyer_publish":
		$form_id	=	$_REQUEST['form_id'];
		$flyer_id	=	$_REQUEST['flyer_id'] ? $_REQUEST['flyer_id'] : "0";
		if($flyer_id!="0")
		$flyer->FlyerPublish($flyer_id);
		redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_list"));
		break;
		
			/**
		 * Update to publish all data
		 * Author :Vipin
		 * Created:25/Mar/2008
		 * Modified :25/Mar/2008
		 */
		case "flyer_publish_all":
		extract($_POST);
		if(count($expired_values)>0 and $_REQUEST['expired']=='yes')
		     $category_id = $expired_values;
				
		if(count($category_id)>0) 		{
		$message=true;
		foreach ($category_id as $flyer_id)
		  {  
			if($flyer_id!="0")
			$flyer->FlyerPublish($flyer_id);
		  }	
		 } 	
		redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_list"));
		break;
		
		case "flyer_del_list":
		$form_id	=	$_REQUEST['form_id'];
		$flyer_id	=	$_REQUEST['flyer_id'] ? $_REQUEST['flyer_id'] : "0";
		if($flyer_id!="0")
		$flyer->flyerDelete($flyer_id);
		redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_list"));
		break;
		
	case "flyer_photographs":
		checkLogin();
				
		$form_id	=	$_REQUEST['form_id'];
		$flyer_id	=	$_REQUEST['flyer_id'] ? $_REQUEST['flyer_id'] : "0"; 
		if($_SERVER['REQUEST_METHOD'] == "POST") { 
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			if(isset($_REQUEST['btn_main_image_x']) )
			{
				$req 			=	&$_POST; 
				$fname			=	basename($_FILES['file_main_image']['name']);
				$ftype			=	$_FILES['file_main_image']['type'];
				$tmpname		=	$_FILES['file_main_image']['tmp_name'];
				$caption		=	"";
				$message 		= 	$flyer->FlyeruploadPhoto($req,$fname,$tmpname,'M','0',$caption);
			}
			if(isset($_REQUEST['btn_gallery_image_x']) )
			{
				if($fw->maxRecordLimit($_SESSION["memberid"],'flyer_data_gallary','flyer_id','',$flyer_id) === TRUE)
				{
						setMessage("You reached the limit of photos in this account. Upgrade to upload more photos");
						redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_photographs&form_id=$form_id&flyer_id=$flyer_id"));
				}
				$req 			=	&$_POST; 
				# uploading 1 image
				if($file_gallery_image)
				{
					$fname			=	basename($_FILES['file_gallery_image']['name']);
					$ftype			=	$_FILES['file_gallery_image']['type'];
					$tmpname		=	$_FILES['file_gallery_image']['tmp_name'];
					$caption		=	$_REQUEST['caption'];
					$message 		= 	$flyer->FlyeruploadPhoto($req,$fname,$tmpname,'G','1',$caption);
				}
				# uploading 2 image
				if($file_gallery_image2)
				{
					$fname2			=	basename($_FILES['file_gallery_image2']['name']);
					$ftype2			=	$_FILES['file_gallery_image2']['type'];
					$tmpname2		=	$_FILES['file_gallery_image2']['tmp_name'];
					$caption2		=	$_REQUEST['caption2'];
					$message 		= 	$flyer->FlyeruploadPhoto($req,$fname2,$tmpname2,'G','2',$caption2);
				}
				# uploading 3 image
				if($file_gallery_image3)
				{
					$fname3			=	basename($_FILES['file_gallery_image3']['name']);
					$ftype3			=	$_FILES['file_gallery_image3']['type'];
					$tmpname3		=	$_FILES['file_gallery_image3']['tmp_name'];
					$caption3		=	$_REQUEST['caption3'];
					$message 		= 	$flyer->FlyeruploadPhoto($req,$fname3,$tmpname3,'G','3',$caption3);
				}
				# uploading 4 image
				if($file_gallery_image4)
				{
					$fname4			=	basename($_FILES['file_gallery_image4']['name']);
					$ftype4			=	$_FILES['file_gallery_image4']['type'];
					$tmpname4		=	$_FILES['file_gallery_image4']['tmp_name'];
					$caption4		=	$_REQUEST['caption4'];
					$message 		= 	$flyer->FlyeruploadPhoto($req,$fname4,$tmpname4,'G','4',$caption4);
				}
				# uploading 5 image
				if($file_gallery_image5)
				{
					$fname5			=	basename($_FILES['file_gallery_image5']['name']);
					$ftype5			=	$_FILES['file_gallery_image5']['type'];
					$tmpname5		=	$_FILES['file_gallery_image5']['tmp_name'];
					$caption5		=	$_REQUEST['caption5'];
					$message 		= 	$flyer->FlyeruploadPhoto($req,$fname5,$tmpname5,'G','5',$caption5);
				}
			}
			if($message==true)
			setMessage("Photo Uploaded Successfully!", MSG_SUCCESS);
			if($message==false)
			setMessage("Photo Can not Uploaded!");
			redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_photographs&form_id=$form_id&flyer_id=$flyer_id"));
		}
		$framework->tpl->assign("DATE", date("H:i:s"));
		$framework->tpl->assign("FLYER_MAIN_PHOTO", $flyer->GetmainPhoto($flyer_id));
		$framework->tpl->assign("FLYER_GALLERY_PHOTO", $flyer->GetgalleryPhoto($flyer_id));
		$framework->tpl->assign("FORM_ID", $form_id);
		$framework->tpl->assign("FLYER_ID", $flyer_id);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/flyer_photograph.tpl");
		break;
		
	case "email_flyer":
		checkLogin();
		$AttachList	=	array();
		$form_id	=	$_REQUEST['form_id'];
		$flyer_id	=	$_REQUEST['flyer_id'] ? $_REQUEST['flyer_id'] : "0"; 
		$my_flyer	=	$flyer_id.".html";
		$topsubmenu=$objCms->linkList("flyer_list");
		if($_REQUEST["pid"])
		{
			$pid=$_REQUEST["pid"];
		}else{
			$pid=$topsubmenu[0]->id;
		}
		if($_SERVER['REQUEST_METHOD'] == "POST") { 
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			if(isset($_REQUEST['btn_send_x']) )
			{
				$mail_details 	= 	$flyer->GetmemberImages();
				$mail_from		=	$mail_details['email'];
				$mail_user		=	$my_subDomain_name; 
				
				//$mail_user		=	$mail_details['username']; // must be changed to the sub domain name
				$mail_to   		= 	$_POST["txt_email_to"];
				$email_type   	= 	$_POST["email_type"];
				//http://shinu.vflyer.com/2/index.html
				$subject		=	$_REQUEST['txt_subject'];
				// sending html flyer
				if($email_type=="flyer") {
					$flyer_code = $flyer->getHTMLCodeForEbayAndOthersSites($flyer_id);
					
					$flyer_content	=	'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
										<html xmlns="http://www.w3.org/1999/xhtml">
										<head>
										<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
										<title>Untitled Document</title>
										<style type="text/css">
										<!--
										body {
											margin-left: 0px;
											margin-top: 0px;
											margin-right: 0px;
											margin-bottom: 0px;
										}
										-->
										</style>
										</head><body>'.$flyer_code.'
										</body>
										</html>';
				mimeMail($mail_to,$subject,$flyer_content,'','',$mail_from,'','',FALSE, $AttachList);
					
				}
				//sending PDF
				if($email_type=="pdf") 	{
					$flyer_content = SITE_PATH."/pdfflyers/".$flyer_id.".pdf";
					$AttachList = array('FlyerPDF' => $flyer_content);
					# sending mail with attachment
							// Read POST request params into global vars
							$to      = $mail_to;
							$from    = $mail_from;
							$subject = $subject;
							$message = $txt_message;
							// Obtain file upload vars
							$fileatt      = SITE_PATH."/pdfflyers/".$flyer_id.".pdf";
							$fileatt_type = "pdf";
							$fileatt_name = "PDF Listing";
							$headers = "From: $from";
							 
							 if (file_exists($fileatt)) {
							 // Read the file to be attached ('rb' = read binary)
							 $file = fopen($fileatt,'rb');
							 $data = fread($file,filesize($fileatt));
							 fclose($file);
							 }
						  // Generate a boundary string
							 $semi_rand = md5(time());
							 $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
							 
							 // Add the headers for a file attachment
							 $headers .= "\nMIME-Version: 1.0\n" .
										 "Content-Type: multipart/mixed;\n" .
										 " boundary=\"{$mime_boundary}\"";
						 // Add a multipart boundary above the plain message
							 $message = "This is a multi-part message in MIME format.\n\n" .
										"--{$mime_boundary}\n" .
										"Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
										"Content-Transfer-Encoding: 7bit\n\n" .
										$message . "\n\n"; 
						  // Base64 encode the file data
							 $data = chunk_split(base64_encode($data));
							 
						 // Add file attachment to the message
							 $message .= "--{$mime_boundary}\n" .
										 "Content-Type: application/pdf;\n" .
										 " name=\"{$fileatt_name}\"\n" .
										 "Content-Disposition: attachment;\n" .
										 " filename=\"Listing.pdf\"\n" .
										 "Content-Transfer-Encoding: base64\n\n" .
										 $data . "\n\n" .
										 "--{$mime_boundary}--\n"; 
										 
										 // Send the message
							$ok = @mail($to, $subject, $message, $headers);
 					# end sending mail with attachment
					
				}
				// sending Link
				if($email_type=="link")	{
				$message = $txt_message;
					$link_name	=	$mail_user.".".DOMAIN_URL."/".$flyer_id."/index.php";
					$flyer_content ='<html>	<body> <table width="100%" border="0"> 
					<tr><td> '.$message.'  </td></tr>
					 <tr>
										<td> <a href="http://'.$link_name.'">http://'.$link_name.'</a><br></td>
									  </tr>  <tr>
										<td>If you do not see the link, or if you click on the link and it appears broken, please copy and paste the URL into a new browser window. </td>
									  </tr>	</table> </body> </html>';
									//  print($flyer_content);exit;
				mimeMail($mail_to,$subject,$flyer_content,'','',$mail_from,'','',FALSE, $AttachList);
				
				/*$mail_header	=	array(	"from"	=>	$mail_from,
											"to"	=>	mail_to);
				$dynamic_vars 	=	array(	"LINK"		=>	$link_name);
				$email->send('send_listing', $mail_header, $dynamic_vars);*/
				
				}
				
				setMessage("Listing has been forwarded sucessfully");
				redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=email_flyer&form_id=$form_id&flyer_id=$flyer_id"));
			}
		}
		
		
	
		
		$framework->tpl->assign("TOPSUB_MENU", $topsubmenu);
		$framework->tpl->assign("PID", $pid);
		$framework->tpl->assign("FORM_ID", $form_id);
		$framework->tpl->assign("FLYER_ID", $flyer_id);
		$framework->tpl->assign("tp_file",SITE_PATH."/modules/flyer/tpl/email.tpl");
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/email_flyer.tpl");
		break;
		
		case "pdf_flyer":
		checkLogin();
		$form_id	=	$_REQUEST['form_id'];
		$flyer_id	=	$_REQUEST['flyer_id'] ? $_REQUEST['flyer_id'] : "0"; 
		$topsubmenu=$objCms->linkList("flyer_list");
		if($_REQUEST["pid"])
		{
			$pid=$_REQUEST["pid"];
		}else{
			$pid=$topsubmenu[0]->id;
		}
		
		$framework->tpl->assign("TOPSUB_MENU", $topsubmenu);
		$framework->tpl->assign("PID", $pid);
		$framework->tpl->assign("FORM_ID", $form_id);
		$framework->tpl->assign("FLYER_ID", $flyer_id);
		$framework->tpl->assign("DOWNLOAD_PDF",SITE_URL."/pdfflyers/".$flyer_id.".pdf");
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/pdf_flyer.tpl");
		break;
		
		case "flyer_activity":
		checkLogin();
		$form_id	=	$_REQUEST['form_id'];
		$flyer_id	=	$_REQUEST['flyer_id'] ? $_REQUEST['flyer_id'] : "0"; 
		$viewCount	=	$flyer->getFlyerViewCount($flyer_id);
		$framework->tpl->assign("VIEW_COUNT", $viewCount);
		$framework->tpl->assign("FORM_ID", $form_id);
		$framework->tpl->assign("FLYER_ID", $flyer_id);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/flyer_activity.tpl");
		break;
		
		
		case "delete": 
		extract($_POST);
		if(count($category_id)>0) 		{
		$message=true;
		foreach ($category_id as $flyer_id)
			{  
			
			if($flyer->flyerDelete($flyer_id)==false)
				$message=false;
			}
		}
		if($message==true)
			setMessage("Listing(s) Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage("Listing(s) Can not Deleted!");
		redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_list&cat_id={$_REQUEST['cat_id']}&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
		break;
		
		
		case "property_delete": 
		extract($_POST);
		if(count($category_id)>0) 		{
		$message=true;
		foreach ($category_id as $album_id)
			{  
			
			if($flyer->propertyDelete($album_id)==false)
				$message=false;
			}
		}
		if($message==true)
			setMessage("Listing(s) Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage("Listing(s) Can not Deleted!");
		redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_list&cat_id={$_REQUEST['cat_id']}&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
		break;
		
		
		
		
		
		case "setSearchPriority":
			extract($_POST);
			if(count($category_id)>0)	{
				foreach ($category_id as $album_id)	{  
					$flyer->flyerSetPriority($album_id,"SET");
				}
			}
			setMessage("Advertise Set Successfully!", MSG_SUCCESS);
			redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_list&cat_id={$_REQUEST['cat_id']}&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
		break;	
		
		case "unSetSearchPriority":
			extract($_POST);
			if(count($category_id)>0)	{
				foreach ($category_id as $album_id)	{  
					$flyer->flyerSetPriority($album_id,"UNSET");
				}
			}
			setMessage("Advertise UnSet Successfully!", MSG_SUCCESS);
			redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_list&cat_id={$_REQUEST['cat_id']}&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
		break;	
		
		
		
		case "unpublish": 
		extract($_POST);
		if(count($category_id)>0) 		{
		$message=true;
		foreach ($category_id as $album_id)
			{  
			
			if($flyer->propertyUnpublished($album_id)==false)
				$message=false;
			}
		}
		if($message==true)
			setMessage("Listing(s)unpublished Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage("Listing(s) Can not unpublished!");
		redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_list&cat_id={$_REQUEST['cat_id']}&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
		break;
		
		
		case "publish":
		extract($_POST);
		
		if($_SERVER['REQUEST_METHOD'] == "GET"){
		 $category_id=$_REQUEST['album_id'];
		
		}
		   $MemberId=$_SESSION['memberid'];
			
		   $check=$payment->whetherCreditCardVarifiedOrNot($MemberId);
			
			   if($check==true)
			   {
			  
			         if(count($category_id)>0) 		{
														 
						$message=true;
							 if(!is_array($category_id))
							 {
							 if($flyer->propertyPublished($category_id)==false)
									$message=false;
							 }
							foreach ($category_id as $album_id)
								{  
								
								if($flyer->propertyPublished($album_id)==false)
									$message=false;
								}
						}
						if($message==true)
						{
						  $type="publish";
							  $email->mailSend($type, $MemberId,$_REQUEST);
													
							setMessage("Listing(s)published Successfully!", MSG_SUCCESS);
							}
			   			   redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_list&cat_id={$_REQUEST['cat_id']}&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));

			   }else{
			   
			   $ReturnURL				=	array('mod' => 'flyer', 'pg' => 'list', 'act' => 'flyer_list');
			   $_SESSION['ReturnURL']	=	$ReturnURL;
			   setMessage('Verify Credit Card in order to publish properties.');
			   redirect(makeLink(array("mod"=>"member", "pg"=>"account"), "act=billinginfo_form"));
			   
			   }
			   
		break;
		
		
		
		
		case "clone": 
		extract($_POST);
		if(count($category_id)>1) 
		{
			setMessage("Select only one Listing for clone !", MSG_SUCCESS);
			redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_list&cat_id={$_REQUEST['cat_id']}&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
		}
		elseif(count($category_id)==0) 
		{
			setMessage("Select one Listing for clone !", MSG_SUCCESS);
			redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_list&cat_id={$_REQUEST['cat_id']}&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
		}
		else
		{
			foreach ($category_id as $flyer_id)
			{  
			#------------------21-1-08------
			// checking the flyer restriction for the user
			if($fw->maxRecordLimit($_SESSION["memberid"],'flyer_data_basic','user_id','form_id!=\'0\' AND active=\'Y\'') === TRUE)
			{
				setMessage("You have reached the published Listing limit for this account. Please upgrade to create more Listing.");
				redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_list&form_id=$form_id&flyer_id=$flyer_id"));
			}
			$flyer->getFlyerClone($flyer_id);		
		#------------------21-1-08------
				
			}
			setMessage("Clone Created sucessfully !", MSG_SUCCESS);
			redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_list&cat_id={$_REQUEST['cat_id']}&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));

		}
		break;
		
		
		
		
		case "delete_custome":
		
		$form_id	=	$_REQUEST['form_id'];
		$flyer_id	=	$_REQUEST['flyer_id'] ? $_REQUEST['flyer_id'] : "0"; 
		$feature_id	=	$_REQUEST['field_id'];
		$flyer->flyerCustomeFieldDelete($feature_id);
		
		redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_form&form_id=$form_id&flyer_id=$flyer_id"));
		
		break;
		
		
		case "delete_image":
		$form_id	=	$_REQUEST['form_id'];
		$flyer_id	=	$_REQUEST['flyer_id'] ? $_REQUEST['flyer_id'] : "0"; 
		$image_id	=	$_REQUEST['img_id'];
		$type		=	$_REQUEST['type'];
		$message=true;
		if($flyer->flyerPhotoDelete($image_id,$type)==false)
				$message=false;
		if($message==true)
			setMessage("Photo Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage("Photo Can not Deleted!");
		redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_photographs&form_id=$form_id&flyer_id=$flyer_id"));
		break;

		
		case "flyer_preview":
			
			$FLYER_DATA	=	$flyer->getFlyerDataForPreview($flyer_id);
			$framework->tpl->assign("CSS_FILE", 'styles_001.php');
			$framework->tpl->assign("DATE", date("H:i:s"));
			$framework->tpl->assign("FLYER_DATA", $FLYER_DATA);
			
			$PREVIEW_CODE	= $framework->tpl->fetch(SITE_PATH."/html/templatetpls/template1.tpl");	
			$framework->tpl->assign("FLYER_PREVIEW", $PREVIEW_CODE);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/flyer_preview.tpl");
			break;
			
		case "search":
			$flyer_id		=	$_REQUEST['search_flyer_id'];
			$FlyerStatus	=	$flyer->checkFlyerExists($flyer_id);
			if($FlyerStatus == TRUE) {
				$FileName	=	SITE_URL.'/htmlflyers/'.$flyer_id.'.html';
				header("Location: $FileName");
				exit;
			} else {
				setMessage("The Listing associated to this ID no longer available.");
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/flyer_search.tpl");
			
			}
			
			break;
			
		case "craigslist_htmlcode":
			$flyer_id	=	$_REQUEST['flyer_id'];
			$form_id	=	$_REQUEST['form_id'];
			$framework->tpl->assign("FORM_ID", $form_id);
			$Code		=	$flyer->getCraigsListHtmlCode($flyer_id);
			$framework->tpl->assign("CRAIGLIST_CODE", $Code);
			$framework->tpl->assign("FLYER_ID", $flyer_id);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/craigslist_code.tpl");
			break;
		
		case "ebay_flyercode":
			$flyer_id	=	$_REQUEST['flyer_id'];
			$form_id	=	$_REQUEST['form_id'];
			$framework->tpl->assign("FORM_ID", $form_id);
			$Code		=		$flyer->getHTMLCodeForEbayAndOthersSites($flyer_id);
			$framework->tpl->assign("CRAIGLIST_CODE", $Code);
			$framework->tpl->assign("FLYER_ID", $flyer_id);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/ebay_flyercode.tpl");
			break;
		
		case "html_flyercode":
			$flyer_id	=	$_REQUEST['flyer_id'];
			$form_id	=	$_REQUEST['form_id'];
			$framework->tpl->assign("FORM_ID", $form_id);
			$Code		=	$flyer->getHTMLCodeForEbayAndOthersSites($flyer_id);
			$framework->tpl->assign("CRAIGLIST_CODE", $Code);
			$framework->tpl->assign("FLYER_ID", $flyer_id);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/html_flyercode.tpl");
			break;		
		
		case "rss_bookmark":
			$member_id			=	$_REQUEST['member_id'];
			require_once SITE_PATH.'/includes/Rss/class.rsspublish.php';
			$RsspublisherObj	=	new Rsspublisher();
			$FeedURL			=	SITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"preview"), "act=rss_flyerlist&member_id=$member_id");
			$framework->tpl->assign("SITE_URL", SITE_URL."/");
			$WidgetHTML			=	$RsspublisherObj->getWidget('ALL', $FeedURL);
			$framework->tpl->assign("BOOKMARK_WIDGET", $WidgetHTML);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/rssbookmark.tpl");
			break;
			
		case "delete_user_image":
			 $form_id   =   $_REQUEST['form_id'];
			 $flyer_id  =   $_REQUEST['flyer_id'];
			 $ftype     =   $_REQUEST['ftype'];
			 $pid       =   $_REQUEST['pid'];
			 $image_id	=	$_REQUEST['img_id'];
			 $type		=	$_REQUEST['type'];
				$message=true;
				if($flyer->deleteUserImage($image_id,$type)==false)
				$message=false;
				if($message==true)
				setMessage("Photo Deleted Successfully!", MSG_SUCCESS);
				if($message==false)
				setMessage("Photo Can not Deleted!");
				if($ftype=='L')
				redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=logos&ftype=$ftype&pid=$pid&form_id=$form_id&flyer_id=$flyer_id"));
				else
				redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=logos&pid=$pid"));
		break;
	
	
	case 'property_form':
		checkLogin();
		$flyer_id	=	trim($_REQUEST['flyer_id']);
		$prop_id	=	$_REQUEST['propid'];
		
		$status		=	$_REQUEST['status'];
		//$cat	    =	$_REQUEST['cat'];
		/* */
		
		$red=$_REQUEST['red'];
		if($red==1){
		$errmsg=$framework->MOD_VARIABLES[MOD_ERRORS][ERR_LOCATION];
		setMessage($errmsg, MSG_INFO);
		}
		$Flyercat	=	$flyer->getFlyercat($prop_id);
		
		if($flyer_id == '') {
			$arrID		=	$flyer->createBasicFlyer($_REQUEST);
			$flyer_id 	=  	$arrID[0];
			$prop_id	=	$arrID[1];
			
			redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_form&flyer_id=$flyer_id&propid=$prop_id&status=new"));
		}
						
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		$flyer_id=$_REQUEST['flyer_id'];
		$prop_id=$_REQUEST['propid'];
		
		/*if($_REQUEST['location_city']=="" || $_REQUEST['location_state']=="" || $_REQUEST['location_country']=="" || $_REQUEST['location_zip']=="" )
		{
		$errmsg=$framework->MOD_VARIABLES[MOD_ERRORS][ERR_LOCATION];
		setMessage($errmsg, MSG_INFO);
		redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"),"act=property_form&flyer_id=$flyer_id&propid=$prop_id"));
		}*/
		/*  [location_street_address] 
          [location_city] 
          [location_state] 
          [location_country] 
          [location_zip]*/
		
		 $MemberId=$_SESSION['memberid'];
		  $fid=$_REQUEST['flyer_id'];
		  $type="property_upload";
		  $email->mailSend($type, $MemberId,$_REQUEST);
		   
			$ValStatus	=	$flyer->validateFlyerDataBasicForm($_REQUEST);
			if($ValStatus === true) {
				$SaveStatus	=	$flyer->saveFlyerBasicData($_REQUEST,$Flyercat["show_Qty"]);
				if($SaveStatus === TRUE) {
				setMessage();
					redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=features_form&flyer_id=$flyer_id&propid=$prop_id"));
				} else {
					setMessage('Please try once again');
					redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_form&flyer_id=$flyer_id&propid=$prop_id"));
				}
			} else {
				setMessage($ValStatus);
				redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_form&flyer_id=$flyer_id&propid=$prop_id"));
			}
			
		}
		
		$StepsHTML			=	$flyer->getPropertyCreatonStepsHTML(1, $flyer_id,$prop_id);
		
		$FlyerBasicData		=	$flyer->getFlyerBasicFormData($flyer_id);
		list($quantityTitle)		=	$flyer->getQuantityTitle($prop_id);
		
		if($status == 'new') {
			$ContactDetails		=	$user->getUserdetails($_SESSION['memberid']);
			/*print "<pre>";
			print_r($ContactDetails);*/
			$FlyerBasicData['contact_name']			=	$ContactDetails['first_name'].' '.$ContactDetails['last_name'];
			$FlyerBasicData['contact_phone']		=	$ContactDetails['telephone'];
			$FlyerBasicData['contact_email']		=	$ContactDetails['email'];
		}

		$framework->tpl->assign("STEPS_HTML", $StepsHTML);
		$framework->tpl->assign("FLYER_BASIC_DATA", $FlyerBasicData);
		$framework->tpl->assign("FLYER_SHOW", $Flyercat);
		$framework->tpl->assign("FLYER_ID", $flyer_id);
		$framework->tpl->assign("QUANTITY_TITLE",$quantityTitle);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/property_form.tpl");
		break;

		
	case 'features_form':
		checkLogin();
		
		$flyer_id		=	$_REQUEST['flyer_id'];
		$prop_id		=	$_REQUEST['propid'];
		$Action			=	$_REQUEST['Action'];
		
		if($Action == 'Save') {
			$flyer->saveFlyerFeatures($_REQUEST);
			redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_quantity&flyer_id=$flyer_id&propid=$prop_id"));
		}
				
		if($Action == 'AddFeature') {
			$flyer->saveFlyerFeatures($_REQUEST);
			$flyer->saveCustomField($_REQUEST);
		}

		if($Action == 'AddAttribute') {
			$flyer->saveFlyerFeatures($_REQUEST);
			$flyer->saveAttributeItem($_REQUEST);
		}

		if($Action == 'AddOption') {
			$flyer->saveFlyerFeatures($_REQUEST);
			$flyer->saveOption($_REQUEST);
		}
		
		$StepsHTML		=	$flyer->getPropertyCreatonStepsHTML(2, $flyer_id,$prop_id);						
		$FormDetails	=	$flyer->getFlyerFeaturesAndAttributes($flyer_id);
		$framework->tpl->assign("STEPS_HTML", $StepsHTML);
		$framework->tpl->assign("CONFIG", $flyer->config);
		$framework->tpl->assign("FORM_DATA", $FormDetails);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/property_features.tpl");
		break;

	case 'save_attributes':
		$flyer_id		=	$_REQUEST['flyer_id'];
		$AttributeIds	=	trim($_REQUEST['AttributeIds']);
		$flyer_id		=	$_REQUEST['flyer_id'];
		$flyer->saveFlyerAttributes($AttributeIds, $flyer_id);
		exit;
		break;
	
	case 'remove_customfeature':
		extract($_REQUEST);
		$flyer->flyerCustomeFieldDelete($feature_id);
		redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=features_form&flyer_id=$flyer_id&propid=$propid"));
		break;	
	
	case 'property_contlocform':
		checkLogin();
		$flyer_id		=	$_REQUEST['flyer_id'];
		$prop_id		=	$_REQUEST['propid'];
		
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$flyer->saveContactAndLocationInfo($_REQUEST);
			redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_quantity&flyer_id=$flyer_id&propid=$prop_id"));
			
		}
		
		$StepsHTML		=	$flyer->getPropertyCreatonStepsHTML(3, $flyer_id,$prop_id);
		$FormDetails	=	$flyer->getContactInfoOfFlyer($flyer_id);
		$framework->tpl->assign("STEPS_HTML", $StepsHTML);
		$framework->tpl->assign("FLYER_BASIC_DATA", $FormDetails);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/property_contctloctnform.tpl");
		
		break;
		
	case 'property_quantity':
		
		checkLogin();
		
		$flyer_id		=	$_REQUEST['flyer_id'];
		$prop_id		=	$_REQUEST['propid'];
		$red=$_REQUEST['red'];
		if($red==2){
		$errmsg=$framework->MOD_VARIABLES[MOD_ERRORS][ERR_PRICE];
		setMessage($errmsg, MSG_INFO);
		}
		$Flyercat	=	$flyer->getFlyercat($prop_id);
		
		/*Calendar START HERE */
		
		$datesY  	= isset($_REQUEST['year']) ? $_REQUEST['year'] : date('Y');
		$datesM 	= isset($_REQUEST['month']) ? $_REQUEST['month'] : date('m'); 
		$datesD   	= isset($_REQUEST['day']) ? $_REQUEST['day'] : date('d'); 
	
		/* Event Days */
		
		
		$days = $objEVENTS->setBackGroundColor($prop_id,$datesM,$datesY);
		
		$array = array('CUSTOM_FUNCTION' => 'dispMonthView',"memberid" =>$_SESSION['memberid'],"propid" => $_REQUEST['propid'],"flyer_id" => $_REQUEST['flyer_id'],"event_arry" => $days);
		$CALENDAR_MONTH = $objCalendar->dragMonth($datesY,$datesM,$datesD,'calendarList',$array);
		list($START_DATE,$END_DATE,$BACK_COLOR) = $objEVENTS->fillDragCalendarJavascriptArray($_REQUEST['propid'],$datesM,$datesY);
		$ALL_COLOR = $flyer->getAllColor($prop_id);
	
		
		// AJAX- CALENDAR NAVIGATION PART START HERE*****************************************************************--------
		if($_REQUEST['type'] == "ajax"){	
				$strHtm = '<div id="load_cal" style="position:absolute;margin-top:0px;margin-left:0px;width:431px;height:295px;" class="calendar_disb"></div>';
				echo $strHtm.$CALENDAR_MONTH."|".$START_DATE."|".$END_DATE."|".$BACK_COLOR."|".$ALL_COLOR;
				$framework->tpl->assign("MONTH",$datesM);
				$framework->tpl->assign("YEAR",$datesY);
				exit;
		}else
		{
			//$strHtm = '<div id="load_cal" style="position:absolute;margin-top:150px;margin-left:210px;width:100px;height=25;" class="border"><img src="'.$global['tpl_url'].'/images/loadingB.gif" id="loadCal"></div>';
				$framework->tpl->assign("MONTH",$datesM);
				$framework->tpl->assign("YEAR",$datesY);
				$framework->tpl->assign("CALENDAR_MONTH",$CALENDAR_MONTH);
		}
		
		$qty= $Flyercat['show_Qty'];
		
		
		if($_SERVER['REQUEST_METHOD'] == "POST") 
		{
			$qtyenable=$_REQUEST['qty'];
		
			
			$flyer->saveFloatingPricing($_REQUEST,$prop_id);
			$flyer->saveMinimumMaximumBookLength($prop_id,$_REQUEST['minimum_booking_days'],$_REQUEST['maximum_booking_days']);
			$flyer->saveSpecialPrice($_REQUEST);
			
			
			/* Update the fiexd price*/
			$flyer->updateFixedRateAsGroup($_REQUEST["fixed_price_rate"] ,$_REQUEST["price_id"]);
			
			if(count($_REQUEST['minimumBid']) && count($_REQUEST['reserve_bid']) && count($_REQUEST['fixed_bid_expires'])){
					
				$flyer->updateBid($prop_id,$_REQUEST);
			}
			setMessage("");
			redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=map&flyer_id=$flyer_id&propid=$prop_id"));
		
		}
		
		/*Calendar END HERE */
		
		$StepsHTML		=	$flyer->getPropertyCreatonStepsHTML(3, $flyer_id,$prop_id);
		list($quantityTitle,$quantityTitleCombo)	=	$flyer->getQuantityTitle($prop_id);
		$PropertyList	=	$flyer->getFlayerListCombo($_SESSION['memberid'],$flyer_id);
		$relatedProp	=	$flyer->getRelatedProperty($prop_id);
		$rsFlbasic		=	$flyer->getFlyerBasicFormData($flyer_id);
		$advance_book_days				=	$flyer->getFlyerBasicFormData($flyer_id);
		$block_quantity_array			=	$flyer->getPropertyBlockQuantity($prop_id,$_SESSION['memberid']);
		$rsSpecificDates				=	$flyer->getSpecificDatesPriceList($prop_id);
	
		$rsSpecificPrice 				=	$flyer->getPropertySpecialPrice($prop_id);
		//print_r($rsSpecificPrice);
		
		
		$FloatingPrice = $flyer->getFlatingPriceResults($prop_id);/* Floating price */
		
		if(count($rsSpecificPrice)){
			$framework->tpl->assign("W_ID", 1);
				foreach ($rsSpecificPrice as $row){
					if($row['type']=='pr')
					{
					$framework->tpl->assign("W_PRICE", $row['priceperc']);
					}else{
					$framework->tpl->assign("W_PERCENTAGE", $row['priceperc']);
					
					}
				}
		}
	
		//$framework->tpl->assign("COLORPICKER",$this->getcolorPicker(array("name"=>"rgb1_dragOpt"))
		list($PRINT_FIX_CAL_RAT,$rs,$val) = $flyer->printFixedCalendarRateBlock($prop_id,$global['tpl_url'],$objCalendar,$objEVENTS);
		
		
		
		if($val<=0)
		$val =0;
		
		$framework->tpl->assign("THE_VALUE",$val);
		$framework->tpl->assign("PRINT_FIXED_CALENDAR_RATES",$PRINT_FIX_CAL_RAT);
		$framework->tpl->assign("PRINT_FIXED_CALENDAR_RECORD",$calendar_values);
		$framework->tpl->assign("QTY", $qty);
		$framework->tpl->assign("STEPS_HTML", $StepsHTML);
		$framework->tpl->assign("PROP_LIST",$PropertyList);
		$framework->tpl->assign("RELATE_PORP",$relatedProp);
		$framework->tpl->assign("BASICPRICEINFO",$rsFlbasic);
		
		$framework->tpl->assign("MINIMUM_BOOK_DAYS",$advance_book_days["minimum_booking_days"]);
		$framework->tpl->assign("MAXIMUM_BOOK_DAYS",$advance_book_days["maximum_booking_days"]);
		$framework->tpl->assign("BLOCK_QUANTITY_LIST",$listBlockQty);
		$framework->tpl->assign("THEVAL",$theVal);
		$framework->tpl->assign("SpF",$objCalendar->DrawCalendarLayer(date('Y'),date('m'),date('d'),"SpF",array('FCase'=>'')));
		$framework->tpl->assign("SpT",$objCalendar->DrawCalendarLayer(date('Y'),date('m'),date('d'),"SpT",array('FCase'=>'')));
		$framework->tpl->assign("CAL1",$objCalendar->DrawCalendarLayer(date('Y'),date('m'),date('d'),"0",array('FCase'=>'')));
		$framework->tpl->assign("CAL2",$objCalendar->DrawCalendarLayer(date('Y'),date('m'),date('d'),"1",array('FCase'=>'')));
		//$framework->tpl->assign("SPECIFIC_DATES_PRICE_LIST",$listSpcDtPric);
		$framework->tpl->assign("THEVAL1",$theVal1);
		$framework->tpl->assign("SPECIFIC_PRICE",$flyer->getSpecificPriceList());
		$framework->tpl->assign("SPECIFIC_PRICE_LIST",$listSpecificPrice);
		$framework->tpl->assign("THEVAL2",$theVal2);
		$framework->tpl->assign("BIDPRICE",$flyer->getBidPriceList($prop_id));
		$framework->tpl->assign("DURATION_TYPE",array("Day" => "Day","Week" => "Week","Month" => "Month","Year" => "Year"));
		//print_r($FloatingPrice);
		if($FloatingPrice["min_units"]){
			$val=$FloatingPrice["min_units"];
		}else{
			$val=$FloatingPrice["unit"];
		}
		$framework->tpl->assign("DURATION_TYPE_SAMP",array("$val" =>"$val"));
		$framework->tpl->assign("QUANTITY_TITLE",$quantityTitleCombo);
		
		$framework->tpl->assign("START_DATE",$START_DATE);
		$framework->tpl->assign("END_DATE",$END_DATE);
		$framework->tpl->assign("BACK_COLOR",$BACK_COLOR);
		
		$framework->tpl->assign("RENTAL_END_DATE_CAL",$objCalendar->DrawCalendarLayer(date('Y'),date('m'),date('d'),"rental_end_date",array('FCase'=>'')));
		$framework->tpl->assign("BID_EXP_DATE",$objCalendar->DrawCalendarLayer(date('Y'),date('m'),date('d'),"fixed_bid_expires",array('FCase'=>'')));
	
		$framework->tpl->assign("FLOATING_PRICE",$FloatingPrice);
		$framework->tpl->assign("CURRENT_YEAR",$datesY);
		$framework->tpl->assign("CURRENT_MONTH",$datesM);
		$framework->tpl->assign("ALL_BACK_COLOR",$ALL_COLOR);
		$framework->tpl->assign("BLOCK_COLOR",implode(",",$flyer->exterNalBlcokColor()));
		$framework->tpl->assign("BLOCKD_QUANTITY",$block_quantity_array);
		
		$framework->tpl->assign("MINIMUM_UNIT",$FloatingPrice["unit"]);
		
		
		if($FloatingPrice["duration"] != 1 || $FloatingPrice["unit"] != "Day"){
		$framework->tpl->assign("SPECIAL_PRICE_DISABLE","disable");
	
		}
		
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/property_quantity.tpl");
		break;
	case 'map':
		checkLogin();

		$flyer_id		=	$_REQUEST['flyer_id'];
		$prop_id		=	$_REQUEST['propid'];
		$flyerInfo      =   $flyer->getFlyerBasicFormData($_REQUEST['flyer_id']);
		if($global['Location_price_info']=='N'){
			if($flyerInfo['location_city']=="" || $flyerInfo['location_state']=="" || $flyerInfo['location_country']=="" || $flyerInfo['location_zip']=="" )
			{
				redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"),"act=property_form&flyer_id=$flyer_id&propid=$prop_id&red=1"));
			}
		}
		$StepsHTML		=	$flyer->getPropertyCreatonStepsHTML(4, $flyer_id,$prop_id);
		
		$framework->tpl->assign("STEPS_HTML", $StepsHTML);
		$FormDetails	=	$flyer->getContactInfoOfFlyer($flyer_id);
		$FlyerBasicData	=	$flyer->getFlyerBasicFormData($flyer_id);

		$gmap->ELEMENT_ID    = 'property_map_add';
		$gmap->MAP_TYPE 	 = 'G_NORMAL_MAP';
		$gmap->MAP_ZOOM      = 14;
		
		/*(if (  $FormDetails['location_zip'] )
		$query	=	$FormDetails['location_zip'];
		elseif (  $FormDetails['location_city'] )
		$query	=	$FormDetails['location_city'];
		elseif (  $FormDetails['location_state'] )
		$query	=	$FormDetails['location_state'];
		elseif (  $FormDetails['location_country'] )
		$query	=	$FormDetails['location_country'];
*/

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

		//$popStr	=	"<br>" . $FlyerBasicData['title'] . "<br>" . $FormDetails['location_city'] . ',' .$FormDetails['location_state'] . "<br>" . $FormDetails['location_country'] . "</span>";
		
		
		$flyDescription	=	wordwrap(substr($FlyerBasicData['description'],0,120), 40, "<br>", true);
		
		if (strlen($FlyerBasicData['description'])>120)
		$flyDescription = $flyDescription . "...";
		
		$flyDescription	= str_replace(chr(13), "", $flyDescription);
		$flyDescription = str_replace(array("\r\n", "\r", "\n","<br>"), "", $flyDescription);
		$flyDescription = addslashes($flyDescription);
		
				
		$popStr	=	"";
		$popStr	.= "<div align=center class=\"bodytext border\" style=\"width:350px;text-align:justify\">";
		
				
		if(trim($FlyerBasicData['title']))
		$popStr	.= "<div align=left style=width:80%;text-align:justify><b>" . addslashes($FlyerBasicData['title']) . "</b></div>";
		
		if(trim($FlyerBasicData['description']))
		$popStr	.= "<div align=center class=\"meroonsmall\" style=width:80%;text-align:justify>" . $flyDescription . "</div>";	
				
		
		if(trim($FlyerBasicData['location_city']))
		$popStr	.= "<span>" . addslashes($FlyerBasicData['location_city']) . "</span>, ";	

		if(trim($FlyerBasicData['location_country']))
		$popStr	.= "<span>" . addslashes($FlyerBasicData['location_country']) . "</span>";	
			
		$popStr	.=	"</div>";
		

		
		$getPos	=	$flyer->fetchAlbumPositiononMap ($FlyerBasicData['album_id']);
		if($getPos['lat_lon'])	{
			$mapSetPosStr	=	"new GLatLng{$getPos['lat_lon']}";
			$framework->tpl->assign("mapSetPosStr", $mapSetPosStr);
		}

		$framework->tpl->assign("popStr", $popStr);
		$framework->tpl->assign("query", $query); #$_REQUEST['query']
		$framework->tpl->assign("My_Map", $gmap->showMap());

		
		
		$framework->tpl->assign("album_id",$FlyerBasicData['album_id']);
		//$framework->tpl->assign("prop_id",$prop_id);
		

		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/property_map.tpl");
		break;
	case 'updateAlbumMapPos':
		
		$flyer_id		=	$_REQUEST['album_ID'];
		$prop_id		=	$_REQUEST['propid'];
		
		$flyer_id_url		=	$_REQUEST['flyer_id'];
		
		if ( $flyer->UpdateAlbumPositionMap($_REQUEST) == true ) 
		echo "location.href='".makeLink(array("mod"=>"album", "pg"=>"photo"), "act=upload&flyer_id=$flyer_id_url&propid=$prop_id&crt=M2")."';";
		else
		echo "location.href='".makeLink(array("mod"=>"album", "pg"=>"photo"), "act=upload&flyer_id=$flyer_id_url&propid=$prop_id&crt=M2")."';";

		exit;
		break;
	case 'deleteAlbumMapPos':
		if ( $flyer->DeleteAlbumPositionMap($_REQUEST['album_ID']) == true ) 
		echo "yes";
		else
		echo "no";
		exit;
		break;
    case 'avil_calender':
		$check          =   $_REQUEST['details'];
		$flyer_id		=	$_REQUEST['flyer_id'];
		$prop_id		=	$_REQUEST['propid'];
		// CALENDAR DISPLAY PART
		
		list($CALENDARLIST,$CALENDAR_NAVIG)= $objEVENTS->printCalendarValues($_REQUEST,$objCalendar,$_SESSION["memberid"],"MONTH",$FlyerBasicData["title"]);
		$MONTH_VIEW = $objEVENTS->printCalendar($CALENDAR_NAVIG,$CALENDARLIST,"MONTH");
		$framework->tpl->assign("MONTH_VIEW",$MONTH_VIEW);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/avail_calender.tpl");
	break;
    case "bid_owner":
    	checkLogin();
    	//$prop_id =1;
    	//$flyer_id = 1;
    	if ($_req['bid_select'])
    	{
    		
    	}
		$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act'];
    	$FIXED_BLOCK= $flyer->getFixedBidResults($_REQUEST['pageNo'], 12, $par, ARRAY_A, $_REQUEST['orderBy'],$_SESSION['memberid']);
		//print_r($FIXED_BLOCK);

		$framework->tpl->assign("FIXED_BLOCK",$FIXED_BLOCK[0]);
		$framework->tpl->assign("FIXED_BLOCK_NUMPAD",$FIXED_BLOCK[1]);
	
		
		
		$sprice             =   $flyer->getPropertySpecialPrice($prop_id);
		$rsFlbasic			=	$flyer->getFlyerBasicFormData($flyer_id);
		$rsAlbm		  		=   $album->getAlbumDetails($prop_id); 
		$rsFlvFeAt			=	$flyer->getFlyerDataForPreview($flyer_id);// Get the features and Attributes
		list($quantityTitle)		=	$flyer->getQuantityTitle($prop_id);
		$rsBlockPropList	=	$flyer->getPropertyBlockQuantityTitle($prop_id,$_SESSION['memberid']);// This part may be change(Based on the current user)

		//$framework->tpl->assign("FIXED_BLOCK",$FIXED_BLOCK);
    	$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/property_bid_det_owner.tpl");
    break;
	case 'property_view':
		//checkLogin();
		//print_r($_SESSION);
		 $check          =   $_REQUEST['details'];
		$flyer_id		=	$_REQUEST['flyer_id'];
		$prop_id		=	$_REQUEST['propid'];
		$flyerInfo      =   $flyer->getFlyerBasicFormData($_REQUEST['flyer_id']);
		
		$FloatingPrice  =   $flyer->getFlatingPriceResults($prop_id);
		
		if($global['Location_price_info']=='N'){
			if($check=="")
			{
				if($flyerInfo['location_city']=="" || $flyerInfo['location_state']=="" || $flyerInfo['location_country']=="" || $flyerInfo['location_zip']=="" )
				{
					redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"),"act=property_form&flyer_id=$flyer_id&propid=$prop_id&red=1"));
				}
				if($FloatingPrice['price']<1 || $FloatingPrice['duration']<1 || $FloatingPrice['unit']=="" )
				{
					 redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"),"act=property_quantity&flyer_id=$flyer_id&propid=$prop_id&red=2"));
				}
			}	
		}
		
		$StepsHTML		=	$flyer->getPropertyCreatonStepsHTML(7, $flyer_id,$prop_id);
 		
		
		$framework->tpl->assign("STEPS_HTML", $StepsHTML);
		$FormDetails	=	$flyer->getContactInfoOfFlyer($flyer_id);
		$FlyerBasicData	=	$flyer->getFlyerBasicFormData($flyer_id);	
		
		
		# Begin Map SECTION #
		# Begin Map SECTION #
		# Begin Map SECTION #
		

		/*if (  $FormDetails['location_zip'] )
		$query	=	$FormDetails['location_zip'];
		elseif (  $FormDetails['location_city'] )
		$query	=	$FormDetails['location_city'];
		elseif (  $FormDetails['location_state'] )
		$query	=	$FormDetails['location_state'];
		elseif (  $FormDetails['location_country'] )
		$query	=	$FormDetails['location_country'];*/
		
		
		
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
		$flyDescription = addslashes($flyDescription);
				
		$popStr	=	"";
		$popStr	.= "<div align=center class=\"bodytext border\" style=\"width:350px;text-align:justify\">";
						
		if(trim($FlyerBasicData['title']))
		$popStr	.= "<div align=left style=width:80%;text-align:justify><b>" . addslashes($FlyerBasicData['title']) . "</b></div>";
		
		if(trim($FlyerBasicData['description']))
		$popStr	.= "<div align=center class=\"meroonsmall\" style=width:80%;text-align:justify>" . $flyDescription . "</div>";	
				
		
		if(trim($FlyerBasicData['location_city']))
		$popStr	.= "<span>" . addslashes($FlyerBasicData['location_city']) . "</span>, ";	

		if(trim($FlyerBasicData['location_country']))
		$popStr	.= "<span>" . addslashes($FlyerBasicData['location_country']) . "</span>";	
			
		$popStr	.=	"</div>";
		

		$getPos	=	$flyer->fetchAlbumPositiononMap ($FlyerBasicData['album_id']);
		
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
		//print_r($FLAT_BLOCK);
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
		$rsPhoto = $album->propertyList("album_photos","photo",0,$_REQUEST["propid"]);
		
	  	$pcount  = count($rsPhoto);
		/*PHOTO LISTING PART ---- END */
		
		/*
		VIDEO LISTING
		*/
		$rsVdo = $album->propertyList("album_video","video",0,$_REQUEST["propid"]);
		$block_quantity_array			=	$flyer->getPropertyBlockQuantity($prop_id,$_SESSION['memberid']);
		

		
		$button_caption = $MOD_VARIABLES["MOD_BUTTONS"]["BT_RENT"]; 
		
		// CALENDAR DISPLAY PART
		
		list($CALENDARLIST,$CALENDAR_NAVIG)= $objEVENTS->printCalendarValues($_REQUEST,$objCalendar,$_SESSION["memberid"],"MONTH",$FlyerBasicData["title"]);
		$MONTH_VIEW = $objEVENTS->printCalendar($CALENDAR_NAVIG,$CALENDARLIST,"MONTH");
		
		//print_r($publish);
		// FEEDBACK LISTING PART
		if($check==det)
		{
		  $propid=$_REQUEST['propid'];
		  $bkid=$flyer->getBookId($propid);
		  $orderBy = "postded_date";
		  list($rs, $numpad, $cnt, $limitList,$pag_total,$prenext_link,$next_link)	= 	$album->getPropertyCommentDetails($bkid,$pageNo,10,$param,ARRAY_A, $orderBy);
     	  $framework->tpl->assign("COUNTS",$cnt);
		  $framework->tpl->assign("COMMENTS",$rs);
		  $framework->tpl->assign("LIST_NUMPAD", $numpad);
		  //get ratings 
		  $prrate=$album->getPropertyRate($propid);
		  $framework->tpl->assign("PRORATE",$prrate['rate']);
		  $rate_show= $prrate['rate']*20;
		  $framework->tpl->assign("PRATE",$rate_show);
		  //get earnings
		  $yearearn=$album->getPropertyYearlyEarnings($propid);
		  $framework->tpl->assign("YEAREARN",$yearearn);
		
		  $totearn=$album->getPropertyEarnings($propid);
		   $framework->tpl->assign("TOTEARN",$totearn);
		/*   $score=$prrate['rate']*$yearearn['yearamt'];
		   $score=round($score);*/
			   
		   $rank=$album->getRankofproperty($propid);
		   
		   $framework->tpl->assign("SCORE",$rank['score']);
		   
		   $framework->tpl->assign("RANK", $rank['rank']);
		}
		
		
		// END OF FEEDBACK LISTING PART
		$publish=$search->basicAlbumInfo($_REQUEST["propid"]);
		
		$framework->tpl->assign("PUBLISH",$publish);
		$user=$user->getUserdetails($rsFlbasic['user_id']);
		//print_r($user);
		//echo $user['name'];
		$framework->tpl->assign("USERDET",$user);
		$framework->tpl->assign("SPRICE",$sprice);
		$framework->tpl->assign("PHOTO_LIST",$rsPhoto);
		$framework->tpl->assign("PCOUNT",$pcount);
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
		$framework->tpl->assign("MAP", SITE_PATH."/modules/flyer/tpl/property_map_list_view.tpl");
		$framework->tpl->assign("CALENDAR",SITE_PATH."/modules/album/tpl/mycalendar_search.tpl");
		if($_REQUEST['details']==book)
		{
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/property_detail.tpl");
		}else{
			$framework->tpl->assign("photo_tpl", SITE_PATH."/modules/flyer/tpl/property_preview_photo.tpl");
			$framework->tpl->assign("location_tpl", SITE_PATH."/modules/flyer/tpl/property_preview_location.tpl");
			$framework->tpl->assign("features_tpl", SITE_PATH."/modules/flyer/tpl/property_preview_features.tpl");
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/property_preview.tpl");
		}
		//
		$framework->tpl->assign("BLOCKD_QUANTITY",$block_quantity_array);
		$framework->tpl->assign("MONTH_VIEW",$MONTH_VIEW);
		
		$framework->tpl->assign("BUTTON_RENT",$flyer->createPropertyImagebutton($button_caption,$href='javascript:movetoAvialable();'));
		break;
	case 'video':
		include_once(SITE_PATH."/includes/flashPlayer/include.php");
		$prop_id = $_REQUEST['propid'];

		if($_REQUEST['vdoid'] > 0){
			$vdoid 		=	$_REQUEST['vdoid'];
		}
		else{
			$rsAlbm		  		=   $album->getAlbumDetails($prop_id); 
			$vdoid				=	$rsAlbm["default_vdo"];
		}
		
		$framework->tpl->assign("VDO_ID",$vdoid);
		$framework->tpl->assign("TEST","This is test text");
		$framework->tpl->display(SITE_PATH."/modules/album/tpl/video.tpl");
		exit;
		break;
	case 'video_show':
		
		 $albm_id  = $_REQUEST['albm_id'];
		 $video_id = $_REQUEST['vdoid'];
	     $vdoName  = $search->videoName($video_id);
		 $proName  = $search->propertyName($albm_id);
		 $rsVdo    = $album->videoPopUp("album_video","video",0,$albm_id,$_SESSION["memberid"]);
		 $framework->tpl->assign("VIDEO_NAME",$vdoName);
		 $framework->tpl->assign("PROPERTY_NAME",$proName);
		 $framework->tpl->assign("VIDEO_LIST",$rsVdo);
		 $framework->tpl->assign("VIDEO",$video_id);  
	     $framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/videoPopup.tpl");
		 $framework->tpl->display($global['curr_tpl']."/popup.tpl");
		 exit;
		break;
		
	/**
	* 
	* @Author   :
	* @Created  : 
	  modified : Vipin on 17/april/2008
	*/
	case 'favorite':
			    //$orderBy	=	$_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : "title";
				
				checkLogin();
				$array=array();
				$array["type"]    = "album";
				$array["file_id"] = $_REQUEST["propid"];
				
				$array["userid"]  = $_SESSION["memberid"];
				
					if($_SERVER['REQUEST_METHOD'] == "POST"){
							$val=$flyer->addFavorite($array);
							if($val=="true"){
							   setMessage("Property added to your favorite list",MSG_SUCCESS);
							}
							else{
							// redirect(makeLink(array("mod"=>"flyer", "pg"=>"album_search"),"act=video_search"));
							setMessage("Property is already added to your favorite list",MSG_SUCCESS);
							//$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/video_listing.tpl");
							}
							 redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"),"act=favorite"));
					}
					//$favList=$flyer->selectFavorite($array,$pageNo,$limit,$param,OBJECT, $orderBy);
					
						 list($favList, $numpad, $cnt, $limitList)=$flyer->selectFavorite($array,$pageNo,$limit,$param,ARRAY_A, $orderBy);
						
						$i=0;
						foreach ($favList as $dt){
							$st=date($global["date_format_new"],strtotime($dt['modified_date']));
							$favList[$i]['modified_date']=$st;
							$i++;
						}
						 $framework->tpl->assign("PHOTO",$favList);
						  $framework->tpl->assign("PHOTO_NUMPAD",$numpad);
				
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/myfavalbum.tpl");
				
				
        	
	break;
	
	case 'favaur':
	            $par = "mod=$mod&pg=$pg&act=".$_REQUEST['act'];
			 	$tbl="album";
				if (($_REQUEST["action1"]) || ($_REQUEST["action2"]))
				{	
					if (($_REQUEST["action1"]=="delete") || ($_REQUEST["action2"]=="delete"))
					{
						 $files=$_REQUEST["chk"];
						
						$length=sizeof($files);
						for($i=0;$i<$length;$i++)
						{
							$album->favoritePropDelete($files[$i], $_SESSION['memberid']);
						}
						setMessage("");
					}
				}
				list($rs, $numpad) = $album->propertySearch($_REQUEST['pageNo'], 10, $par, ARRAY_A, $orderBy,"user_id",$_SESSION["memberid"],'album',$criteria='=',$category='',$listAllSub='no',$group_id='','yes');
				
			$framework->tpl->assign("PHOTO_LIST", $rs);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/myfavalbum.tpl");
	break;
	
	case 'removeFavorite':
	
	
	           $flyer->removeFavoriteAlbum($_REQUEST['id'], $_SESSION["memberid"]);
			   setMessage("Property has been removed",MSG_SUCCESS);
			   redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"),"act=favorite"));
			   //$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/myfavalbum.tpl");
			   
	break;
		/**
		 * To delete favorites
		 * Author :Vipin
		 * Created:08/Apr/2008
		 * Modified :08/Apr/2008
		 */
	case 'removeFavoriteAll':
				extract($_POST);
				if(count($chk)>0) {
					$message=true;
					foreach ($chk as $album_id)
					{  
						$flyer->removeFavoriteAlbum($album_id, $_SESSION["memberid"]);
						setMessage("Property has been removed",MSG_SUCCESS);
					}
				}
	            redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"),"act=favorite"));
			   //$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/myfavalbum.tpl");
			   
	break;
	
	
	case 'searchlist':
			$form_id		=	$_REQUEST["form_id"] 		? $_REQUEST["form_id"] 			: "";
			$groupitems		=	$flyer->getFlyerFormAttributeGroupsAndItems($form_id);
			$content		   =	$flyer ->getGroupItems($groupitems);
			
			$cnt=count($content);
			
			echo $cnt."|".$content;
			exit;
			
    break;
    
    
	case 'loadstates':
		$CountryName			=	$_REQUEST['CountryName'];
		$SelectedStateName		=	$_REQUEST['SelectedStateName'];
		$Output	=	$flyer->getStateComboForFlyerForm($CountryName, $SelectedStateName);
		print $Output;
		exit;
		break;
		
    case "send_mail":
	            $img_id         =   $_REQUEST['imgid'];
				$prop_id		=	$_REQUEST['propid'];
				$framework->tpl->assign("PID", $prop_id);
				$framework->tpl->assign("IMG_ID",  $img_id);
				if($Submit == 'Submit'  && $_SERVER['REQUEST_METHOD'] == 'POST' ) {
								
				    $MailDetails	=	$flyer->sendForwardMailDetails($_REQUEST);
					if($MailDetails['sendstatus'] === FALSE) {
					
						$framework->tpl->assign("FORM_VALUES", $_REQUEST);
						$framework->tpl->assign("MESSAGE", $MailDetails['message']);
						
				    } else if($MailDetails['sendstatus'] === TRUE){
						$userid         =   $_SESSION["memberid"];
						$prop_id		=	$_REQUEST['prop_id'];
						$img_id		    =	$_REQUEST['img_id'];
						
						//$fid=171;
						//$pid=21;
						//$member         =   $user->getUserDetails($userid);
						$flyer_id       =   $flyer->getFlyerIDByAlbum($prop_id);
						$rsFlbasic		=	$flyer->getFlyerBasicFormData($flyer_id);
						$rsAlbm         =   $album->getAlbumDetails($prop_id); 
						
						$discription    =   $rsFlbasic['description'];
						
						$defImage       =   $rsAlbm['default_img'];
						
		                $imgExt         =   $photo->imgExtension($rsAlbm["default_img"]);
						
						$content	    =	'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
											<html xmlns="http://www.w3.org/1999/xhtml">
											<head>
											<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
											<title>Untitled Document</title>
											</head>
											<body>
												<table>
												    <tr>
													     <td><b>'.$rsFlbasic['title'].'</b></td>
													</tr>
													 <tr>
														 <td><img src="'.SITE_URL.'/modules/album/photos/'.$defImage.''.$imgExt.'"> </td>
														 <td>'.$discription.'</td>
													</tr>
													<tr>
													     <td>'.$_REQUEST['message'].'</td>
													</tr>
													
												</table>
											</body>
											</html>';
											
						$mail_header = array();
						$mail_header['from'] 	= 	$_REQUEST['email'];
						$mail_header["to"]      =   $_REQUEST['emailaddresses'];
						$dynamic_vars = array();
						$dynamic_vars['first_name']  =$_REQUEST['name'];
						$dynamic_vars['property_body']=$content;
						
						$email->send("property_information",$mail_header,$dynamic_vars);
						$framework->tpl->assign("MESSAGE", 'Listing Forwaded');
					}	
				}
	$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/mail_form.tpl");
	break;
    case "saveFixedCalendarRate":
    	
    	checkLogin();
    	
    	$req 	= $_REQUEST;
    	$propid = $_REQUEST['propid'];
		$rental_end_date = $flyer->convertToMySqlFormat($req["rental_end_date"]);
		

    	$val = $objEVENTS->checkBlockDatesDetails($req["start_date"],$rental_end_date,$req["fixed_unit"],$req["fixed_duration"],0,$propid,"",$req["b_id"],"booked");

    	if($val == "true"){
    		
    		$req["rental_end_date"] = $rental_end_date;
    		
    		$excuteVal = $flyer->fixedCalendarRates($req,$propid);
    		list($PRINT_FIX_CAL_RAT,$rs,$hid_val) = $flyer->printFixedCalendarRateBlock($propid,$global['tpl_url'],$objCalendar,$objEVENTS);
    		echo $excuteVal."|".$PRINT_FIX_CAL_RAT."|".$hid_val;
    		exit;
    	}else{
    		echo $val;
    		exit;
    	}
    break;
    case "deletFixedRate":
    	
    	$id = $_REQUEST["bid"];
    	$propid = $_REQUEST["propid"];
    	if($id > 0){
    		$val=  $flyer->deleteFixedBlock($id);
    		$CNT =$flyer->countPropertyFixedPricing($propid);
    		echo $val."|".$CNT;
    	}
    	exit;
    break;
    case "saveupdatePropertyBlckDate":
  
    	$req = $_REQUEST;
    	
    		$val = $objEVENTS->pastDateCheckValidation($req["start_date_blck"]);
    		
			if($val == "LESSTHAN" || $val == "EQUAL"){
				echo "Past or Current dates are not allowed for  blocking";
				exit;
			}else{
				
				$propid = $_REQUEST["propid"];
				$val =  $flyer->saveAdvanceQtyBookAndBlockQty($req,$_SESSION["memberid"]);
				$blocked_details = $flyer->printBlockedPropertyDate($propid,$global['tpl_url']);
				echo $val."|".$blocked_details;
	    	exit;
			}
    break;
    case "deletePropertyBlocked":
    	
    	$id = $_REQUEST["id"];
    	$propid = $_REQUEST["propid"];
    	$val = $flyer->deletePropertyBlockedDate($id);
    	$blocked_details = $flyer->printBlockedPropertyDate($propid,$global['tpl_url']);
    	
    	echo $val."|".$blocked_details;
    	exit;
    	break;
    case "disableAuction":
    	$id = $_REQUEST["id"];
    	$propid = $_REQUEST["propid"];
    	$auct = $_REQUEST["auct"];
    	
    	$val = $flyer->disableAuction($id,$auct);
    	list($PRINT_FIX_CAL_RAT,$rs,$hid_val) = $flyer->printFixedCalendarRateBlock($propid,$global['tpl_url'],$objCalendar,$objEVENTS);
    	echo $val."|".$PRINT_FIX_CAL_RAT."|".$auct;
    	exit;
    	break;
    case "deleteAllPropertyBlocked":
    	
    	$propid = $_REQUEST["propid"];
    	
    	$val = $flyer->deleteAllBlockedProperty($propid);
    	$blocked_details = $flyer->printBlockedPropertyDate($propid,$global['tpl_url']);
    	echo $val."|".$blocked_details;
    	exit;
    	
    	break;
		
	case 'property_det':
		checkLogin();
		$memberID = $_SESSION['memberid'];
		$check          =   $_REQUEST['details'];
		$flyer_id		=	$_REQUEST['flyer_id'];
		$prop_id		=	$_REQUEST['propid'];
		$bookid			=	$_REQUEST['bookid'];
		$sellerid		=	$_REQUEST['sellerid'];
		
		$framework->tpl->assign("PROPID",$prop_id);
		$framework->tpl->assign("FLYERID",$flyer_id);
		
		$framework->tpl->assign("BOOKID",$bookid);
		$chkPropRate  =   $flyer->checkPropRating($bookid,$memberID);
		$framework->tpl->assign("RATEPID",$chkPropRate);
		$chkPropComts =   $flyer->checkPropComments($bookid,$memberID);
		//print_r($chkPropComts);
		$framework->tpl->assign("COMTPID",$chkPropComts);
		
		
		
		//$propCommentDetails =   $album->getPropertyCommentDetails($bookid);
		//$framework->tpl->assign("PCOMMENTS",$propCommentDetails);
		
		$user=$user->getUserdetails($sellerid);
		$framework->tpl->assign("USERDET",$user);
		//$chkSelRate  =   $flyer->checkSellerRating($sellerid,$memberID);
		$chkSelRate  =   $flyer->checkSellerRating($bookid,$memberID);
		
		$framework->tpl->assign("RATESID",$chkSelRate);
		//$chkSelerComts =   $flyer->checkSellerComments($sellerid,$memberID);
		$chkSelerComts =   $flyer->checkSellerComments($bookid,$memberID);
		//print_r($chkSelerComts);
		$framework->tpl->assign("COMTSID",$chkSelerComts);
		
		
		// getting feedback of seller and property
		$sellerCommentDetails =   $album->getSellerCommentDetails($sellerid);
		$framework->tpl->assign("SCOMMENTS",$sellerCommentDetails);
		// end getting feedback of seller and property
		$total =   $flyer->getBookingDetails($bookid);
		
		$framework->tpl->assign("TOTAMT",$total['totalamount']);
		$flyerInfo      =   $flyer->getFlyerBasicFormData($_REQUEST['flyer_id']);
		$FloatingPrice  =   $flyer->getFlatingPriceResults($prop_id);
		$FormDetails	=	$flyer->getContactInfoOfFlyer($flyer_id);
		$FlyerBasicData	=	$flyer->getFlyerBasicFormData($flyer_id);	
		
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
		
		$flyer_id		=	$_REQUEST['flyerid'];
		$prop_id		=	$_REQUEST['propid'];
		$bookid			=	$_REQUEST['bookingid'];
		$sellerid		=	$_REQUEST['sellerid'];
		
		    $addrate  =   $flyer->addRatingAndFeedback($_REQUEST);
			
			redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_det&details=book&flyer_id=$flyer_id&propid=$prop_id&bookid=$bookid&sellerid=$sellerid"));
						
		}
		
		
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
		
				$gmap->ELEMENT_ID    = 'property_map_view';
		$gmap->MAP_TYPE 	 = 'G_NORMAL_MAP';
		$gmap->MAP_ZOOM      = 14;
		$gmap->SHOW_LOCALSEARCH = true;
		$gmap->HEIGHT = '400px';
				
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
		

		$getPos	=	$flyer->fetchAlbumPositiononMap ($FormDetails['album_id']);
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
		$rsFlbasic			=	$flyer->getFlyerBasicFormData($flyer_id);
		$rsAlbm		  		=   $album->getAlbumDetails($prop_id); 
		$rsFlvFeAt			=	$flyer->getFlyerDataForPreview($flyer_id);// Get the features and Attributes
		list($quantityTitle)		=	$flyer->getQuantityTitle($prop_id);
		$rsBlockPropList	=	$flyer->getPropertyBlockQuantityTitle($prop_id,$_SESSION['memberid']);// This part may be change(Based on the current user)
		$framework->tpl->assign("DEFAULT_IMG",$rsAlbm["default_img"]);
		$framework->tpl->assign("DEF_IMG_EXT",$photo->imgExtension($rsAlbm["default_img"]));
		$framework->tpl->assign("FLYER_BASIC",$rsFlbasic);
		
		$framework->tpl->assign("FLYER_ATTRIBUTES",$rsFlvFeAt["blocks"]["ATTRIBUTES"]["attributes"]);
		$framework->tpl->assign("album_id",$FormDetails['flyer_id']);
		$framework->tpl->assign("MAP", SITE_PATH."/modules/flyer/tpl/property_map_list_view.tpl");
		
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/property_detail.tpl");
				
		$framework->tpl->assign("BUTTON_RENT",$flyer->createPropertyImagebutton($button_caption,$href='javascript:movetoAvialable();'));
		break;
		
	case 'feedback_list_ajax': 
			$act			=	$_POST["act"] ? $_POST["act"] : "";
			$limit			=	$_POST["limit"] ? $_POST["limit"] : "10";
			$pageNo 		= 	$_POST["pageNo"] ? $_POST["pageNo"] : "0";
			
		  $propid=$_POST['prptyid'];
		  $bkid=$flyer->getBookId($propid);
		  $orderBy = "postded_date";
		  list($rs, $numpad, $cnt, $limitList,$pag_total,$prenext_link,$next_link)	= 	$album->getPropertyCommentDetails($bkid,$pageNo,$limit,$param,ARRAY_A, $orderBy);
		  $togIndex = 1;
		 // print_r($rs);
		 foreach($rs as $Group) {
		
		  $rsecho.="<div class=\"sepertor10px\"><!-- --></div>";
		  $rsecho.=" <div style=\"border:1px solid #afafaf;\">";
		  $rsecho.="<div class=\"sepertor10px\"><!-- --></div>";
		  $rsecho.="<div  align=\"left\" class=\"browntext\" style=\"float:left; width:300px; background-color: #F3F3F3;margin-left:10px;\" >Posted By : <span class=\"bodytext\">".$Group['fname']."</span></div>";
		  $rsecho.="<div  align=\"left\" class=\"browntext\" style=\"background-color:#F3F3F3\">Posted On : <span class=\"bodytext\">".$Group['postded_date']."</span></div>";
		  $rsecho.="<div class=\"sepertor10px\"><!-- --></div>";
		  $rsecho.="<div  align=\"left\" class=\"browntext\"  style=\"margin-left:10px;\">Comments : <span class=\"bodytext\">" .$Group['comment']."</span></div>";
		  $rsecho.="<div class=\"sepertor10px\"><!-- --></div>";
		  $rsecho.="</div>";
		  $togIndex++;
		 		  
		  } 
		 $rsecho.="<div style=\"float:right;width:70%;text-align:right\"><span id=\"searchResNum\">".$numpad."</span>&nbsp;</div>";
		 $rsecho.="<div style=\"float:left;width:10%\" align=\"center\">&nbsp;</div>";
		 $rsecho.="<div style=\"clear:both\"></div>";
		
		echo $rsecho;
		exit;
	break;
	
   case "my_bids":
   		checkLogin();
		if($_REQUEST["dele"]=="delete_bid"){
			$owndet=$flyer->getPropertyOwnerthroughBid($_REQUEST['bidid']);
			$bidowndet=$flyer->getbidOwnerthroughBid($_REQUEST['bidid']);
			//print_r($owndet);exit;
			if($flyer->deleteBids($_REQUEST['bidid'])===true){
				$mail_header = array();
				$mail_header['from'] 	= 	$framework->config['site_name']."<".$framework->config['admin_email'].">";
				$mail_header["to"]   = $owndet["email"];
				$dynamic_vars = array();
				$dynamic_vars['PROPERTY_OWNER']    = $owndet["first_name"] . " " . $owndet["last_name"];
				$dynamic_vars['PROPERTY_NAME'] = $owndet["flyer_name"] ;
				$dynamic_vars['PROPERTY_DESC'] = $owndet["description"] ;
				$dynamic_vars['START_DATE'] = $owndet["start_date"] ;
				$dynamic_vars['END_DATE'] = $owndet["rental_end_date"] ;
				$dynamic_vars['BID_DATE']    = $owndet["bid_date"] ;
				$dynamic_vars['BID_AMT']    = $owndet["bid_amount"] ;
				$dynamic_vars['DURATION']    = $owndet["duration"] ;
				$dynamic_vars['UNIT']    = $owndet["unit"] ;
				$email->send("Property_Auction_Buyer_Delete",$mail_header,$dynamic_vars);
			}
		}
		$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
		$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
		$param			=	"mod=$mod&pg=$pg&act=My_Bids";
		
		list($MY_BIDS, $numpad) = $flyer->getMyBids($pageNo, $limit, $param, ARRAY_A,$_SESSION["memberid"]);
    	//$MY_BIDS = $flyer->getMyBids($_SESSION['memberid']);
		//print_r($MY_BIDS);
		$framework->tpl->assign("MY_BIDS",$MY_BIDS);
		$framework->tpl->assign("NUMPAD",$numpad);
    	$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/my_bids.tpl");
	break;
	
	case "bid_reject":
	
		 $bid_id=$_REQUEST['bid_id'];
		 $flyer->rejectOffer($bid_id);
		 $bid_det  			= $flyer->getBidDetails($bid_id);
		 $pricing_det 		= $flyer->getPricingDetails($bid_det['pricing_id']);
		 $bid_user_det   	= $objUser->getUserDetails($bid_det['user_id']);
		 $flyer_id    		= $flyer->getFlyerIDByAlbum($pricing_det->album_id);
		 $flyer_det   		= $flyer->GetFlyerData($flyer_id);
		 $AlbumDetails		= $album->getAlbumDetails($pricing_det->album_id);
		 $userDetails		= $user->getUserdetails($AlbumDetails['user_id']);
		 
		 $mail_header = array();
		 $mail_header['from'] 	= $framework->config['site_name']."<".$framework->config['admin_email'].">";
		 $mail_header["to"]   	= $userDetails['email'];
		 $dynamic_vars = array();
		 $dynamic_vars['BID_USER'] = $bid_user_det['username'];
		 ($flyer_det['flyer_name']!="")? $prop_name = $flyer_det['flyer_name'] : $prop_name = $flyer_det['title'];
			
		 $dynamic_vars['PROPERTY_NAME'] = $prop_name;
		 $dynamic_vars['PROPERTY_DESC'] = $flyer_det['description'];
		 $dynamic_vars['START_DATE'] = date($framework->config['date_format_new'],strtotime($pricing_det->start_date));
		 $dynamic_vars['END_DATE'] = date($framework->config['date_format_new'],strtotime($pricing_det->rental_end_date));
		 $dynamic_vars['DURATION'] = $pricing_det->duration;
		 $dynamic_vars['UNIT'] = $pricing_det->unit;
		 $dynamic_vars['BID_AMT'] = "$".$bid_det['bid_amount'];
		 $dynamic_vars['BID_DATE'] = date($framework->config['date_format_new'],strtotime($bid_det['bid_date']));
		 $dynamic_vars['OWNER_EMIL'] = $userDetails['email'];
		 $dynamic_vars['OWNER_FIRSTNAME'] = $userDetails['first_name'];
		 $dynamic_vars['OWNER_LASTNAME'] = $userDetails['last_name'];
		
		 $dynamic_vars['BID_USER'] = $bid_user_det['username'];
		 $dynamic_vars['BID_AMT'] =  "$".$bid_det['bid_amount'];
		 $dynamic_vars['BID_DATE'] = date($framework->config['date_format_new'],strtotime($lost_bids['bid_date']));
		 $email->send("Property_Bid_Payment_Rejected",$mail_header,$dynamic_vars);
		  
	break;
	case "bid_posted":
		checkLogin();
		$FIXED_BLOCK= $flyer->getBidsByUserId($_REQUEST['pageNo'], 12, $par, ARRAY_A, $_REQUEST['orderBy'],$_SESSION['memberid']);
		$framework->tpl->assign("FIXED_BLOCK",$FIXED_BLOCK[0]);
		$framework->tpl->assign("FIXED_BLOCK_NUMPAD",$FIXED_BLOCK[1]);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/property_bid_other.tpl");
	break;
	
	
case "update_members_rank":
	
	 		
   		      //   $memberid =    $_SESSION["memberid"];
			  $isbroker='isbroker';
			  $isseller='isseller';
			  $ispopertymanager='ispopertymanager';
			  
			  //this section for setting score to the broker
			  
				 $brokerrate = $user->allUsers($isbroker,'Y');
				if(count($brokerrate ))
				{
				 foreach ($brokerrate as $brate)
		         {
				 
				     $type="Broker";
				     $byearearn=$broker->getBrokerOrManagerYearlySum($brate->id,'BROKER_COMMISION');
					 		    
					 $bmrate=$album->getBrokerRate($brate->id,$type);
					
					// echo $bmrate['rate'];
					 $score=$bmrate['rate']*$byearearn['year_amount'];
					 $score=round($score);
					 
					 $scores=array();
					 $scores["id"]= $brate->id;
					 $scores["broker_score"]= $score;
									 
					  $user->setArrData($scores);
					  $user->update();
				 }
				}
				
				 //this section for setting rank to the broker
				  $brokers = $user->allUsers($isbroker,'Y','','broker_score Desc');
				  
				  if(count($brokers))
				  {
				    $i=0;
				     foreach ($brokers as $brank)
					 {
					  $bscore=$brank->broker_score;
					 
					  
					  $rank=array();
					  $rank["id"]= $brank->id;
					   if($bscore==0)
					   {
					    $rank["broker_rank"]= 0;
					   }
					   elseif($bscore[$i-1]==$bscore)
					   {
					   $rank["broker_rank"]=$i;
					   }
					   else
					   {
					    $rank["broker_rank"]= $i+1;
					   }
		  			  $user->setArrData($rank);
					  $user->update();
					  $i++;
					 }
					 
				  }
				
				 //this section for setting score to the manager   
				 $managerrate = $user->allUsers($ispopertymanager,'Y');
				if(count($managerrate))
				{
				 foreach ($managerrate as $mrate)
		         {
				     $type="Manager";
				     $myearearn=$broker->getBrokerOrManagerYearlySum($mrate->id,'MANAGER_COMMISION');
					 $bmrate=$album->getBrokerRate($mrate->id,$type);
					 $score=$bmrate['rate']*$myearearn['year_amount'];
					 $score=round($score);
					 
					 $scores=array();
					 $scores["id"]= $mrate->id;
					 $scores["manager_score"]= $score;
									 
					 $user->setArrData($scores);
					  $user->update();
							   
				 }
				}   
				
				 //this section for setting rank to the manager
				  $manager = $user->allUsers($ispopertymanager,'Y','','manager_score Desc');
				  if(count($manager))
				  {
				    $i=0;
				     foreach ($manager as $mrank)
					 {
					 $mscore=$mrank->manager_score;
					  $rank=array();
					  $rank["id"]= $mrank->id;
					   if($mscore==0)
					   {
					    $rank["manager_rank"]= 0;
						}
					    elseif($mscore[$i-1]==$mscore)
					   {
					   $rank["manager_rank"]=$i;
					   }
						else{
					  $rank["manager_rank"]= $i+1;
					  }
		  			  $user->setArrData($rank);
					  $user->update();
					  $i++;
					 }
				  }
				
				 //this section for setting score to the seller
				 
				 $sellerrate = $user->allUsers($isseller,'Y');
				if(count($sellerrate))
				{
				 foreach ($sellerrate as $srate)
		         {
				     $type="seller";
				     $syearearn=$broker ->getBrokerOrManagerYearlySum($srate->id,$type);
					 $bmrate=$album->getBrokerRate($srate->id,$type);
					 $score=$bmrate['rate']*$syearearn['year_amount'];
					 $score=round($score);
					 
					 $scores=array();
					 $scores["id"]= $srate->id;
					 $scores["seller_score"]= $score;
									 
					 $user->setArrData($scores);
					 $user->update();
							   
				 }
				 
				} 
				
				 //this section for setting rank to the manager
				  $seller = $user->allUsers($isseller,'Y','','seller_score Desc');
				  if(count($seller))
				  {
				    $i=0;
				     foreach ($seller as $srank)
					 {
					   $sscore=$srank->seller_score;
					   $rank=array();
					   $rank["id"]= $srank->id;
					   if($sscore==0)
					   {
					    $rank["seller_rank"]= 0;
					   }
						elseif($sscore[$i-1]==$sscore)
					   {
					   $rank["seller_rank"]=$i;
					   }
						else{
					   $rank["seller_rank"]= $i+1;
					   }
		  			   $user->setArrData($rank);
					   $user->update();
					   $i++;
					 }
				  }
              
			$LogFileName = SITE_PATH.'/tmp/logs/'.'rate_score_'.date('Y').date('m').'.log';
				$fpp = fopen($LogFileName, "a+");
				fwrite(date('Y').date('m'));
				fclose($fpp);
				 
				
	    break;
		
		case "update_property_rank":
		
		   $propid = $album->getalbumid();
		   if (count($propid)){
			   foreach ($propid as $albmid)
			   {
			   
				 $prrate=$album->getPropertyRate($albmid['album_id']);
				 $yearearn=$album->getPropertyYearlyEarnings($albmid['album_id']);
				 $score=$prrate['rate']*$yearearn['yearamt'];
				 $score=round($score);
				 $property->updatePropertyScore($albmid['album_id'], $score);
				 
			   }
			   			  
			    $property->updatePropertyRank();
			    
			   
		   }
		 
		break;
	
	
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>