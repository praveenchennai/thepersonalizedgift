<?
session_start();
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");
include_once(FRAMEWORK_PATH."/includes/class.framework.php");
include_once(FRAMEWORK_PATH."/modules/callendar/lib/class.calendartool.php");
include_once(FRAMEWORK_PATH."/modules/map/lib/G_Maps.class.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.photo.php");

$fw				=	new FrameWork();
$user           =   new User();
$email			= 	new Email();
$flyer			=	new	Flyer();
$gmap	 		= 	new G_Maps();
$album			=	new Album();
$photo			=	new Photo();

$objCalendar = new CalendarTool ();
$framework->tpl->assign("COUNTRY_LIST", $user->listCountry());

#---------------
$keysearch		=	$_REQUEST["keysearch"] ? $_REQUEST["keysearch"] : "N";
$category_search=	$_REQUEST["category_search"] ? $_REQUEST["category_search"] : "";
$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
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

				$mail_header	=	array(	"from"	=>	$framework->config['admin_email'],
											"to"	=>	$framework->config['admin_email']);
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
			
			
			
			$RssLink	=	'|&nbsp;&nbsp;<a target="_blank" href="'.makeLink(array("mod"=>"flyer", "pg"=>"preview"), "act=rss_flyerlist&member_id={$_SESSION['memberid']}").'"><img src="'.$global['site_url'].'/templates/blue/images/rsslink.gif" border="0" /></a>&nbsp;&nbsp;|&nbsp;&nbsp; <a class="footerlink" href="'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=rss_bookmark&member_id={$_SESSION['memberid']}").'" >RSS Help</a>';
			$framework->tpl->assign("RSS_LINK", $RssLink);
			$framework->tpl->assign("PAGE_NO", $pageNo);
			$cur_date	=	date('Y-m-d');
			$framework->tpl->assign("CUR_DATE", $cur_date);
			$framework->tpl->assign("STATUS_ID", $status_id);
			$framework->tpl->assign("FLYER_LIST", $rs);
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
				$flyer->getFlyerClone($flyer_id);
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
		
		if($flyer_id == '') {
			$arrID		=	$flyer->createBasicFlyer($_REQUEST);
			$flyer_id 	=  	$arrID[0];
			$prop_id	=	$arrID[1];
			
			redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_form&flyer_id=$flyer_id&propid=$prop_id&status=new"));
		}
						
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$ValStatus	=	$flyer->validateFlyerDataBasicForm($_REQUEST);
			if($ValStatus === true) {
				$SaveStatus	=	$flyer->saveFlyerBasicData($_REQUEST);
				if($SaveStatus === TRUE) {
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
		redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=features_form&flyer_id=$flyer_id&propid=$prop_id"));
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
		
		if($_REQUEST['type'] == "bid" && $_REQUEST['disb']){
			
			$flyer->updateBidDisableEnable($_REQUEST['propid'],$_REQUEST['disb']);
			redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=property_quantity&flyer_id=$flyer_id&propid=$prop_id&#bid"));
		}
		
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			//$flyer->saveQuantityAndRelated($_REQUEST);	
			$flyer->saveBasicSpecificDatewisePrice($_REQUEST,$_SESSION['memberid']);
			$flyer->saveAdvanceQtyBookAndBlockQty($_REQUEST,$_SESSION['memberid']);
			$flyer->saveSpecificDatePrice($_REQUEST);
			$flyer->saveSpecificPricing($_REQUEST);
			$flyer->saveBidPrice($_REQUEST);
			redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=map&flyer_id=$flyer_id&propid=$prop_id"));
		
		}
		
		$StepsHTML		=	$flyer->getPropertyCreatonStepsHTML(3, $flyer_id,$prop_id);
		list($quantityTitle,$quantityTitleCombo)	=	$flyer->getQuantityTitle($prop_id);
		$PropertyList	=	$flyer->getFlayerListCombo($_SESSION['memberid'],$flyer_id);
		$relatedProp	=	$flyer->getRelatedProperty($prop_id);
		$rsFlbasic		=	$flyer->getFlyerBasicFormData($flyer_id);
		$advance_book_days				=	$flyer->getFlyerBasicFormData($flyer_id);
		$block_quantity_array			=	$flyer->getPropertyBlockQuantity($prop_id,$_SESSION['memberid']);
		$rsSpecificDates				=	$flyer->getSpecificDatesPriceList($prop_id);
		list($listBlockQty,$theVal) 	= 	$flyer->listEditPartBlockQuantity($block_quantity_array,$objCalendar,$quantityTitle);
		list($listSpcDtPric,$theVal1)	=	$flyer->listEditPartSpecificPriceDate($rsSpecificDates,$objCalendar);
		$rsSpecificPrice 				=	$flyer->getAlbumSpecificPrice($flyer_id);
		list($listSpecificPrice,$theVal2) 	=	$flyer->listEditPartSpecificPrice($rsSpecificPrice,$objCalendar);
		
		
		
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
		$framework->tpl->assign("SPECIFIC_DATES_PRICE_LIST",$listSpcDtPric);
		$framework->tpl->assign("THEVAL1",$theVal1);
		$framework->tpl->assign("SPECIFIC_PRICE",$flyer->getSpecificPriceList());
		$framework->tpl->assign("SPECIFIC_PRICE_LIST",$listSpecificPrice);
		$framework->tpl->assign("THEVAL2",$theVal2);
		$framework->tpl->assign("BIDPRICE",$flyer->getBidPriceList($prop_id));
		$framework->tpl->assign("DURATION_TYPE",array("Day" => "Day","Week" => "Week","Month" => "Month","Year" => "Year"));
		$framework->tpl->assign("QUANTITY_TITLE",$quantityTitleCombo);
		
		
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/property_quantity.tpl");
		break;
	case 'map':
		checkLogin();

		$flyer_id		=	$_REQUEST['flyer_id'];
		$prop_id		=	$_REQUEST['propid'];
		$StepsHTML		=	$flyer->getPropertyCreatonStepsHTML(4, $flyer_id,$prop_id);
		
		$framework->tpl->assign("STEPS_HTML", $StepsHTML);
		$FormDetails	=	$flyer->getContactInfoOfFlyer($flyer_id);
		$FlyerBasicData	=	$flyer->getFlyerBasicFormData($flyer_id);

		$gmap->ELEMENT_ID    = 'property_map_add';
		$gmap->MAP_ZOOM      = 17;
		
		if (  $FormDetails['location_zip'] )
		$query	=	$FormDetails['location_zip'];
		elseif (  $FormDetails['location_city'] )
		$query	=	$FormDetails['location_city'];
		elseif (  $FormDetails['location_state'] )
		$query	=	$FormDetails['location_state'];
		elseif (  $FormDetails['location_country'] )
		$query	=	$FormDetails['location_country'];


		//$query  = $FormDetails['location_city'] + " " + $FormDetails['location_state'] + " " + $FormDetails['location_country'] + " " + $FormDetails['location_zip']#street+" "+city+" "+country+" "+post

		//$popStr	=	"<br>" . $FlyerBasicData['title'] . "<br>" . $FormDetails['location_city'] . ',' .$FormDetails['location_state'] . "<br>" . $FormDetails['location_country'] . "</span>";
		
		
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

		$framework->tpl->assign("album_id",$FormDetails['flyer_id']);
		//$framework->tpl->assign("prop_id",$prop_id);
		

		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/property_map.tpl");
		break;
	case 'updateAlbumMapPos':
		
		$flyer_id		=	$_REQUEST['album_ID'];
		$prop_id		=	$_REQUEST['propid'];
		if ( $flyer->UpdateAlbumPositionMap($_REQUEST) == true ) 
		echo "location.href='".makeLink(array("mod"=>"album", "pg"=>"photo"), "act=upload&flyer_id=$flyer_id&propid=$prop_id&crt=M2")."';";
		else
		echo "location.href='".makeLink(array("mod"=>"album", "pg"=>"photo"), "act=upload&flyer_id=$flyer_id&propid=$prop_id&crt=M2")."';";

		exit;
		break;
	case 'deleteAlbumMapPos':
		if ( $flyer->DeleteAlbumPositionMap($_REQUEST['album_ID']) == true ) 
		echo "yes";
		else
		echo "no";
		
		exit;
		break;

	

	case 'property_view':
		checkLogin();
		
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
		$gmap->MAP_ZOOM      = 14;
		
		
		
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
		$framework->tpl->assign("MAP", SITE_PATH."/modules/flyer/tpl/property_map_list_view.tpl");
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/property_preview.tpl");
		
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
		
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>