<?
session_start();
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.widget.php");
include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");

$user           =   new User();
$email			= 	new Email();
$flyer			=	new	Flyer();
$widget			=	new Widget();

$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$keysearch		=	$_REQUEST["keysearch"] ? $_REQUEST["keysearch"] : "N";
$category_search=	$_REQUEST["category_search"] ? $_REQUEST["category_search"] : "";
$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "name";
$parent_id 		= 	$_REQUEST["parent_id"] ? $_REQUEST["parent_id"] : "0";
//$param			=	"mod=$mod&pg=$pg&act=list&orderBy=$orderBy&parent_id=$parent_id&category_search=$category_search&keysearch=$keysearch&sId=$sId&fId=$fId";


switch($_REQUEST['act']) {
	case "list":
	checkLogin();
			$orderBy	=	$_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : "title";
			$status_id	=	$_REQUEST['status_id'] ? $_REQUEST['status_id'] : "";
			if($status_id=="all") { $status_id="";}
			list($rs, $numpad, $cnt, $limitList)	= 	$widget->listMyWidgets('U',$pageNo,$limit,$param,OBJECT, $orderBy,$status_id);
			
			$cur_date	=	date('Y-m-d');
			$framework->tpl->assign("CUR_DATE", $cur_date);
			$framework->tpl->assign("STATUS_ID", $status_id);
			$framework->tpl->assign("WIDGET_LIST", $rs);
			$framework->tpl->assign("ACT", "form");
			$framework->tpl->assign("FLYER_NUMPAD", $numpad);
			$framework->tpl->assign("FLYER_LIMIT", $limitList);
			$framework->tpl->assign("FLYER_SEARCH_TAG", $flyer_search);
			$framework->tpl->assign("FORMS",$flyer->getallForms()); 
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/widget_list.tpl");
	break;
	
	case "delete": 
		extract($_POST);
		$status_id	=	$_REQUEST['status_id'] ? $_REQUEST['status_id'] : "";
		if($status_id == "all") { $status_id= "";}
		if(count($category_id)>0) 		{
		$message=true;
		foreach ($category_id as $widget_id)
			{  
				if($widget->gadgetDelete($widget_id)==false)
				$message=false;
			}
		}
		if($message==true)
			setMessage("Gadget(s) Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage("Gadget(s) Can not Deleted!");
		redirect(makeLink(array("mod"=>"flyer", "pg"=>"widget"), "act=list&pageNo=$pageNo&limit=$limit&status_id=$status_id"));
		break;
		
		case "del_widget":
		$status_id	=	$_REQUEST['status_id'] ? $_REQUEST['status_id'] : "";
		$widget_id	=	$_REQUEST['widget_id'] ? $_REQUEST['widget_id'] : "";
		
		if($widget->gadgetDelete($widget_id)==false)
			$message=false;
			setMessage("Gadget(s) Deleted Successfully!", MSG_SUCCESS);
		/*if($message==true)
			setMessage("Gadget(s) Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage("Gadget(s) Can not Deleted!");*/
		redirect(makeLink(array("mod"=>"flyer", "pg"=>"widget"), "act=list&pageNo=$pageNo&limit=$limit&status_id=$status_id"));

		break;	
		
		case "new_widget":
		checkLogin();
		$widget_id		=	$_REQUEST['widget_id'] ? $_REQUEST['widget_id'] : "0";
		$status_id		=	$_REQUEST['status_id'] ? $_REQUEST['status_id'] : "";
		$widget_type	=	$_REQUEST['widget_type'] ? $_REQUEST['widget_type'] : "G";
		$flyer_id		=	$_REQUEST['flyer_id'] ? $_REQUEST['flyer_id'] : "0";
		$USER_ID		=	$_SESSION["memberid"];
		$flyer_names	=	$widget->flyerGetDetails();
		if($flyer_id==0)
		{
			$flyer_id	=	$flyer_names['flyer_id'][0]; 
		}
		$USER_DETAILS	=	$user->getUserdetails($USER_ID);
		$FLYER_DETAILS	=	$flyer->GetFlyerData($flyer_id);
		$FLYER_GALLERY	=	$flyer->GetgalleryPhoto($flyer_id);
		
		$myaccount_url	=	$USER_DETAILS['account_url'];
		$myaccount_photo=	$USER_DETAILS['photo'];
		$myaccount_logo =	$USER_DETAILS['logo'];
		$user_fullname	=	$USER_DETAILS['first_name']." ".$USER_DETAILS['last_name'];
		if($widget_type=="L")
		{
			$framework->tpl->assign("FLYER_NAMES", $flyer_names);
			$BasicGal		=	"".$widget->getFileData(SITE_PATH."/modules/flyer/tpl/FlyerWidget.html");
			if($myaccount_url!="") {
				$link_name	=	"http://".$myaccount_url.".".DOMAIN_URL."/".$flyer_id."/index.php";
			}
			else { 
			$link_name	=	SITE_URL."/htmlflyers/".$flyer_id.".html";
			}
		}
		else
		{
			$BasicGal		=	"".$widget->getFileData(SITE_PATH."/modules/flyer/tpl/basicGallery.txt");
			$RowData		=	"".$widget->getFileData(SITE_PATH."/modules/flyer/tpl/BasicGalRowData.txt");
		}
		
		$strSiteHomePath=	SITE_URL;
		$strWidgetPath	=	$strSiteHomePath . "/modules/flyer/tpl/widget/";	
		$strImgPath		=	$strSiteHomePath . "/modules/flyer/images/widget/thumb/";
		$strLogoPath	=	$strSiteHomePath . "/modules/member/images/logos/thumb/";
		$strPhotoPath	=	$strSiteHomePath . "/modules/member/images/photos/thumb/";
		$FlyerGalImgPath=	$strSiteHomePath . "/modules/flyer/images/gallary/thumb/";
		
		$strFontColor	=	"#000000";
		$strBgColor		=	"#FFFFFF";
		$strAltRowColor	=	"#FFFFFF";
		$strRowColor	=	"#EFEFEF";
		$strLinkColor	=	"#20598a";
		$strTitle		=	"MINI GALLERY";
		$listingHome	=	"#";
		$strAddress		=	'<div class="wtext">'.$user_fullname .'</div><div class="wtext">'.$USER_DETAILS['city'].'</div>';
		$strEmail		=	$USER_DETAILS['email'];
		if($USER_DETAILS['telephone'] != "") {
		$strPhone		=	"Phone :".$USER_DETAILS['telephone']; }
		else { $strPhone	=	""; }
	//---Variables for Flyer Gallerey---
	$strFlyerImgPathArray	=	"";
	foreach($FLYER_GALLERY as $value)
	{
		$strFlyerImgPathArray	.=	"[\"". $FlyerGalImgPath .$value['image_name']."\",\"\"],";
	}
	$strFlyerImgPathArray = substr($strFlyerImgPathArray, 0, -1); 
	//$strFlyerImgPathArray='["http://192.168.1.254/sawitonline/modules/flyer/images/medium_gallary/thumb/81_20070912-200004_1.jpg",""],["http://192.168.1.254/sawitonline/modules/flyer/images/medium_gallary/thumb/81_20070912-200004_2.jpg",""],["http://192.168.1.254/sawitonline/modules/flyer/images/medium_gallary/thumb/81_20070912-200004_3.jpg",""],["http://192.168.1.254/sawitonline/modules/flyer/images/medium_gallary/thumb/81_20070912-200004_4.jpg",""]';
	// user photo
	$strImgLeftTopBG=$USER_DETAILS['photo'];
	// user logo
	$strImgLeftMiddleBig=$USER_DETAILS['logo'];
	$strImgLeftMiddleSmall='eeeeeeeeee';
	$strImgLinkUrl=$link_name;
	// main image GetFlyerData($flyer_id)
	$strImage	=	$FLYER_DETAILS['image'];
	$strTxtLink=$link_name;
	$strLinkTextUrl=$FLYER_DETAILS['title'];
	$strdtaText=$widget->getmyFlyerFields($flyer_id);
	$strImgFlyerGalImgs="jjjjjjjjjjjjjj";
	$strFlyerImgStartCount="1";
	$strFlyerImgTotCount=count($FLYER_GALLERY);
	//----------------------------------
		
		$strNewRow		=	$widget->setRowData($RowData,$USER_ID,$myaccount_url);
		$BasicGal 		= 	$widget->setBasicGallery($BasicGal,$strNewRow);
		# Checking the post values #
		if($_SERVER['REQUEST_METHOD'] == "POST") { 
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			if(isset($_REQUEST['btn_save']) )
			{
				$req 			=	&$_POST; 
				$widget->addEditWidget($req,$BasicGal,$widget_type);
				redirect(makeLink(array("mod"=>"flyer", "pg"=>"widget"), "act=list&pageNo=$pageNo&limit=$limit&status_id=$status_id"));
			}
		}
		# Checking the post values #
		if($widget_id!="0" && $_REQUEST['flg']!="Y")
		{
			$widget_data	=	$widget->getWidgetdetails($widget_id);//print_r($widget_data);
			$WidgetCode		=	$widget_data['code'];
			$widget_type	=	$widget_data['type'];
			$flyer_id		=	$widget_data['flyer_id'];
			$widget_name	=	$widget_data['name'];
			$widget_title	=	$widget_data['title'];
			$widget_jsCode	=	"<script src=\"".SITE_URL."/index.php?gadId=".$widget_id."\"></script>";
			$framework->tpl->assign("WIDGET_JSCODE", $widget_jsCode);
			$framework->tpl->assign("WIDGET_DATA", $widget_data);
			
			$framework->tpl->assign("GALLERY_WIDGET", $WidgetCode);
			
		}	
		else
		{
			$widget_name	=	$_REQUEST['name'];
			$widget_title	=	$_REQUEST['title'];
			$framework->tpl->assign("GALLERY_WIDGET", $BasicGal);
		}
		$framework->tpl->assign("MY_FLYER_ID", $flyer_id);
		$framework->tpl->assign("WIDGET_TYPE", $widget_type);
		$framework->tpl->assign("WIDGET_ID", $widget_id);
		$framework->tpl->assign("WIDGET_TITLE", $widget_title);
		$framework->tpl->assign("WIDGET_NAME", $widget_name);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/new_widget.tpl");
	break;

}
$framework->tpl->display($global['curr_tpl']."/widget_inner.tpl");
?>
