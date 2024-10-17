<?
session_start();
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.rss.php");



$user           =   new User();
$email			= 	new Email();
$flyer			=	new	Flyer();
$RssObj			=	new RSS($flyer, $user);

$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";

switch($_REQUEST['act']) {

		case "flyer_preview":
			checkLogin();
			$flyer_id			=	$_REQUEST['flyer_id'];
			$form_id			=	$_REQUEST['form_id'];
			$btnaction			=	$_REQUEST['btnaction'];
			
			if($btnaction	==	'changeTheme') {
				$template_id	=	$_REQUEST['template_id'];
				$flyer->updateTemplateIdOfFlyer($flyer_id, $template_id);
			}
			
			if(isset($_REQUEST['btn_return_x'])) {
				$form_id	=	$flyer->getFormIdOfFlyer($flyer_id); 
				redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_form&flyer_id=$flyer_id&form_id=$form_id"));
			}

			if(isset($_REQUEST['btn_save_draft_x'])) {
				$form_id	=	$flyer->getFormIdOfFlyer($flyer_id);
				$flyer->unPublishFlyer($flyer_id);
				redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_list"));
				#redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_form&flyer_id=$flyer_id&form_id=$form_id"));
			}
			
			$FLYER_DATA			=	$flyer->getFlyerDataForPreview($flyer_id);
			$TemplateDetails	=	$flyer->getTemplateDetails($FLYER_DATA['template_id']);
			$ShowLargeImgGallry	=	$flyer->getLargeImageGallaryShowStatusOnFlyer($_SESSION["memberid"]);
		
			
			if(isset($_REQUEST['btn_republish_x'])) {
				$flyer->generateFlyerForPublish($flyer_id);
				$form_id	=	$flyer->getFormIdOfFlyer($flyer_id); 
				//redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_list&form_id=$form_id"));
				redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=publish_sucess&form_id=$form_id&flyer_id=$flyer_id"));

			}
			
			if(isset($_REQUEST['btn_publish_x'])) {
				$flyer->generateFlyerForPublish($flyer_id);
				$form_id	=	$flyer->getFormIdOfFlyer($flyer_id); 
				redirect(makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=flyer_list&form_id=$form_id"));
			}
			
			$AllTemplates		=	$flyer->GetTemplates();
			
			$framework->tpl->assign("CSS_FOLDER", $TemplateDetails['template_dir']);
			$framework->tpl->assign("DATE", date("H:i:s"));
			$framework->tpl->assign("FLYER_DATA", $FLYER_DATA);
			$framework->tpl->assign("TEMPLATES", $AllTemplates);
			$framework->tpl->assign("FLYER_ID", $flyer_id);
			$framework->tpl->assign("FORM_ID", $form_id);
			$framework->tpl->assign("SITE_NAME", $flyer->config['site_name']);
			$framework->tpl->assign("LABEL_FLYER", 'LISTING ');
			$framework->tpl->assign("IMAGEGALLARY_STATUS", $ShowLargeImgGallry);
			$PREVIEW_CODE	= $framework->tpl->fetch(SITE_PATH."/html/templates/".$TemplateDetails['template_dir']."/template.tpl");
			$framework->tpl->assign("FLYER_PREVIEW", $PREVIEW_CODE);
			
			$framework->tpl->assign("FORM_TITLE", $flyer->getFormTitleFromFormId($FLYER_DATA['form_id']));
			$framework->tpl->assign("PUBLISH_STATUS", $flyer->getFlyerPublishStatus($flyer_id));
			$framework->tpl->assign("template_id", $FLYER_DATA['template_id']);
			
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/flyer_preview.tpl");
			break;
		
		case "rss_flyerlist":
			$member_id			=	$_REQUEST['member_id'];
			$RssFlyerDetails	=	$flyer->GetFormRssFields($member_id);
			$RSSFeedData		=	$RssObj->generateRSSFeed($member_id);
			header("Content-Type: text/xml");
			print $RSSFeedData;
			exit;
			break;
			
		case "html_preview":
			$description	=	$_REQUEST['description'];
			$framework->tpl->assign("PREVIEW_CODE", stripslashes($description));
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/html_preview.tpl");
			break;
		
		
		case "slideshow":
			$flyer_id		=	$_REQUEST['flyer_id'];
			$Gallery		=	$flyer->getGallaryImagesOfFlyer($flyer_id);
			$TemplateDtls	=	$flyer->getTemplateDetailsFromFlyerId($flyer_id);
			$SlideShowFile	=	SITE_PATH.'/html/templates/'.$TemplateDtls['template_dir'].'/slideshow.tpl';
			$framework->tpl->assign("CSS_FOLDER", $TemplateDtls['template_dir']);
			$framework->tpl->assign("GALLARY", $Gallery);
			$framework->tpl->assign("FIRST_IMAGE", $Gallery[0]['image_name']);
			$framework->tpl->assign("SITE_NAME", $flyer->config['site_name']);
			$framework->tpl->assign("FLYER_ID", $flyer_id);
			$framework->tpl->assign("main_tpl", $SlideShowFile);
			break;
		
		case "showmap":	
			$flyer_id		=	$_REQUEST['flyer_id'];
			
			$ContactInfo	=	$flyer->getContactInfoOfFlyer($flyer_id);
			$TemplateDtls	=	$flyer->getTemplateDetailsFromFlyerId($flyer_id);
			$MapFile		=	SITE_PATH.'/html/templates/'.$TemplateDtls['template_dir'].'/googlemap.tpl';
			$ShowAddress	=	$ContactInfo['location_street_address'].', '.$ContactInfo['location_city'].', '.$ContactInfo['location_state'];
			
			$framework->tpl->assign("ADDRESS", $ShowAddress);
			$framework->tpl->assign("CSS_FOLDER", $TemplateDtls['template_dir']);
			$framework->tpl->assign("GOOGLEMAP_KEY", $flyer->config['googlemap_key']);
			$framework->tpl->assign("FIRST_IMAGE", $Gallery[0]['image_name']);
			$framework->tpl->assign("SITE_NAME", $flyer->config['site_name']);
			$framework->tpl->assign("FLYER_ID", $flyer_id);
			$framework->tpl->assign("main_tpl", $MapFile);
			break;

		case "contactform":
			$flyer_id		=	$_REQUEST['flyer_id'];
			$Submit			=	$_REQUEST['Submit'];
			
			if($Submit == 'Submit'  && $_SERVER['REQUEST_METHOD'] == 'POST' ) {
				$MailDetails	=	$flyer->sendContactInformation($_REQUEST);
				$MailStatus		=	mimeMail($MailDetails['to'], $MailDetails['subject'], $MailDetails['content'],'','',$MailDetails['from']);
				
				if($MailStatus == TRUE) 	
					$Msg	=	"Contact Information send.";
				else
					$Msg	=	"Contact Information not send. Please try once again.";

				$framework->tpl->assign("MESSAGE", $Msg);
			} 
			
			$ContactInfo	=	$flyer->getContactInfoOfFlyer($flyer_id);
			$TemplateDtls	=	$flyer->getTemplateDetailsFromFlyerId($flyer_id);
			$ContactFile	=	SITE_PATH.'/html/templates/'.$TemplateDtls['template_dir'].'/contactform.tpl';
			
			$framework->tpl->assign("CSS_FOLDER", $TemplateDtls['template_dir']);
			$framework->tpl->assign("SITE_NAME", $flyer->config['site_name']);
			$framework->tpl->assign("FLYER_ID", $flyer_id);
			$framework->tpl->assign("main_tpl", $ContactFile);
			break;
			
		case "forwardform":
			$flyer_id		=	$_REQUEST['flyer_id'];
			$Submit			=	$_REQUEST['Submit'];
			
			if($Submit == 'Submit'  && $_SERVER['REQUEST_METHOD'] == 'POST' ) {
				$MailDetails	=	$flyer->sendForwardListingDetails($_REQUEST);
				if($MailDetails['sendstatus'] === FALSE) {
					$framework->tpl->assign("FORM_VALUES", $_REQUEST);
					$framework->tpl->assign("MESSAGE", $MailDetails['message']);
				} else if($MailDetails['sendstatus'] === TRUE){
					mimeMail($MailDetails['mailids'], $MailDetails['subject'], $MailDetails['content'],'','',$MailDetails['from'], '', '', FALSE);
					$framework->tpl->assign("MESSAGE", 'Listing Forwaded');
				}
			}
			
			$TemplateDtls	=	$flyer->getTemplateDetailsFromFlyerId($flyer_id);
			$ForwardFile	=	SITE_PATH.'/html/templates/'.$TemplateDtls['template_dir'].'/forwardform.tpl';

			$framework->tpl->assign("CSS_FOLDER", $TemplateDtls['template_dir']);
			$framework->tpl->assign("SITE_NAME", $flyer->config['site_name']);
			$framework->tpl->assign("FLYER_ID", $flyer_id);
			$framework->tpl->assign("main_tpl", $ForwardFile);
			break;
		
			
		default: break;	
}

$framework->tpl->display($global['curr_tpl']."/preview.tpl");

?>