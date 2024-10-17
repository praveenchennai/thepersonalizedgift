<?php
include_once(SITE_PATH."/modules/banner/lib/class.banner.php");
$banner = new Banner();
checkLogin();
switch($_REQUEST['act']) {
    case "list":		
		list($rs, $numpad) = $banner->campaignList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
    	$framework->tpl->assign("CAMPAIGN_LIST", $rs);
        $framework->tpl->assign("CAMPAIGN_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/banner/tpl/campaign_list.tpl");
        break;
    case "form":			
		//$framework->tpl->assign("SECTION_LIST", $banner->campSectionList());		
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $req = &$_REQUEST;				
            if( ($message = $banner->campaignAddEdit($req)) === true ) {
                redirect(makeLink(array("mod"=>"banner", "pg"=>"banner_campaign"),"act=list"));
            }
            $framework->tpl->assign("MESSAGE", $message);
        }
        if($message) {
            $framework->tpl->assign("CAMPAIGN", $_POST);
        } elseif($_REQUEST['id']) {													
            $framework->tpl->assign("CAMPAIGN", $banner->getCampaign($_REQUEST['id']));
        }
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/banner/tpl/campaign_form.tpl");
        break;
    case "delete":
        $banner->campaignDelete($_REQUEST['id']);
        redirect(makeLink(array("mod"=>"banner", "pg"=>"banner_campaign"), "act=list"));
        break;
}
$framework->tpl->display("default/inner.tpl");
?>