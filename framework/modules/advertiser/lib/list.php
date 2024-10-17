<?php
include_once(FRAMEWORK_PATH."/modules/advertiser/lib/class.advertiser.php");

$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "member_master.id";

$param			=	"mod=$mod&pg=$pg&act=list&orderBy=$orderBy&sId=$sId&fId=$fId";

$objAdvertiser	=	new Advertiser();

switch($_REQUEST['act']) {
	case "list":
		
		list($rs, $numpad, $cnt, $limitList)	= 	$objAdvertiser->listAdvertiser($keysearch,$adv_search,$pageNo,$limit,$param,OBJECT, $orderBy);
		$framework->tpl->assign("ADVERTISE_LIST", $rs);
		$framework->tpl->assign("ADVERTISE_NUMPAD", $numpad);
		$framework->tpl->assign("ADVERTISE_LIMIT", $limitList);

	
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/advertiser/tpl/advertiser_list.tpl");
		break;
	
	case "form":
		
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			$req = &$_REQUEST;

			if ( $_FILES['adv_image']['name'] )	{
				$req['adv_img_name']	=	basename($_FILES['adv_image']['name']);
				$req['adv_img_type']	=	$_FILES['adv_image']['type'];
				$req['adv_imgtmpname']	=	$_FILES['adv_image']['tmp_name'];
				
			}



			if( ($message = $objAdvertiser->editAdvDetailsByAdmin ( $req )) === true ) {
				setMessage("Advertisement Updated Successfully", MSG_SUCCESS);
				redirect(makeLink(array("mod"=>"advertiser", "pg"=>"list"), "act=list&sId=List"));
			} else {
				setMessage($message);
			}
				
		}	


		$rs		= 	$objAdvertiser->getAdvertisement($_REQUEST['id']);

		if (!$rs)
		break;		

		$framework->tpl->assign("ADVERTISE_DETAILS", $rs);
	
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/advertiser/tpl/advertiser_form.tpl");
		break;

	
		case "mylist":
		
		list($rs, $numpad, $cnt, $limitList)	= 	$objAdvertiser->listAdvertiser($keysearch,$adv_search,$pageNo,$limit,$param,OBJECT, $orderBy);
		
		$framework->tpl->assign("ADVERTISE_LIST", $rs);
		$framework->tpl->assign("ADVERTISE_NUMPAD", $numpad);
		$framework->tpl->assign("ADVERTISE_LIMIT", $limitList);

	
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/advertiser/tpl/advertisement_list.tpl");
		break;
}

$framework->tpl->display($global['curr_tpl']."/admin.tpl");

?>