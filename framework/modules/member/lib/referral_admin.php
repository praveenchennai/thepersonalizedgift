<?php 
checkLogin(1);

include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.referral.php");

$user = new User();
$ref = new Referral();
$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$keysearch		=	$_REQUEST["keysearch"] ? $_REQUEST["keysearch"] : "N";
$category_search=	$_REQUEST["category_search"] ? $_REQUEST["category_search"] : "";
$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "ref_id";
$param			=	"mod=$mod&pg=$pg&act=list&orderBy=$orderBy&keysearch=$keysearch&sId=$sId&fId=$fId";


switch($_REQUEST['act']) {


	case "list":
		list($rs, $numpad, $cnt, $limitList)	= 	$ref->listAllRefCriteria($keysearch,$category_search,$pageNo,$limit,$param,OBJECT, $orderBy);
		$framework->tpl->assign("REF_CRITERIA_LIST", $rs);
		$framework->tpl->assign("ACT", "form");
		$framework->tpl->assign("REF_CRITERIA_NUMPAD", $numpad);
		$framework->tpl->assign("REF_CRITERIA_LIMIT", $limitList);
		$framework->tpl->assign("REF_CRITERIA_SEARCH_TAG", $category_search);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/member/tpl/referral_list.tpl");

	break;

	
	case "form":
		$ref_id	=	$_REQUEST["ref_id"]	? $_REQUEST["ref_id"]: "0";
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$req 			=	&$_POST;  
			$ref->referralPackAddEdit($req);
			redirect(makeLink(array("mod"=>"member", "pg"=>"referral_admin"), "act=list&action=$action&sId=$sId&fId=$fId&limit=$limit"));
		}
		$framework->tpl->assign("REF_ID",$ref_id);
		$framework->tpl->assign("REF_RESULT",$ref->getReferralPackDetails($ref_id));
		//$framework->tpl->assign("SUB_PACK_NONZERO",$ref->getSubscriptionPacknonZero($ref_id));
		//$framework->tpl->assign("SUB_PACK",$ref->getSubscriptionPack());
		$framework->tpl->assign("SUB_PACK",$ref->getRegistrationPack());
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/member/tpl/referral_form.tpl");
        break;
	
	case "delete":
		extract($_POST);
		if(count($category_id)>0)
		{
		$message=true;
		foreach ($category_id as $ref_id)
			{  
			if($ref->refCriteriaDelete($ref_id)==false)
				$message=false;
			}
		}
		if($message==true)
			setMessage($_REQUEST["sId"]." Criteria(s) Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
			setMessage($_REQUEST["sId"]." Criteria(s) Can not Deleted!");
		redirect(makeLink(array("mod"=>"member", "pg"=>"referral_admin"), "act=list&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));

	break;	
	

}
$framework->tpl->display($global['curr_tpl']."/admin.tpl");
?>