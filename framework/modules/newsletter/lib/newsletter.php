<?php 
/**
 * Newsletter
 *
 * @author sajith
 * @package defaultPackage
 */
 ini_set("display_errors",0);
error_reporting(E_ALL ^ E_NOTICE);

if($_REQUEST['manage']=="manage"){
	authorize_store();
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("PG","newsletter_newsletter") ;
    $framework->tpl->assign("STORE_PERMISSION",$store->getStorePermissionById($store_id,$module_menu_id)) ;
}else{
	authorize();
	$framework->tpl->assign("MOD","newsletter") ;
	$framework->tpl->assign("PG","newsletter") ;
	$STORE = array('hide'=>'Y','add'=>'Y','edit'=>'Y','delete'=>'Y');
	$framework->tpl->assign("STORE_PERMISSION",$STORE) ;
}

include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.newsletter.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");

$user = new User();
$newsletter = new Newsletter();

switch($_REQUEST['act']) {


    case "list":
        list($rs, $numpad) = $newsletter->newsletterList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&orderBy=".$_REQUEST['orderBy'], OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("NEWSLETTER_LIST", $rs);
        $framework->tpl->assign("NEWSLETTER_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/newsletter/tpl/newsletter_list.tpl");
        break;
    case "form":
        include_once(SITE_PATH."/includes/areaedit/include.php");
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $req = &$_REQUEST;
            if( ($message = $newsletter->newsletterAddEdit($req)) === true ) {
                if ($_REQUEST['id']) {
                    redirect(makeLink(array("mod"=>"newsletter", "pg"=>"newsletter"), "act=list"));
                } else {
                    redirect(makeLink(array("mod"=>"newsletter", "pg"=>"newsletter"), "act=form&id={$newsletter->db->insert_id}&template_id=".$req['template_id']));
                }
            }
            setMessage($message);
        }
        if($message) {
            $row = $_POST;
        } elseif($_REQUEST['id']) {
            $row = $newsletter->newsletterGet($_REQUEST['id']);
            if ($_REQUEST['template_id']) {
            	$templateRS = $newsletter->templateGet($_REQUEST['template_id']);
            	if($templateRS['body_html'])$row['body_html'] = $templateRS['body_html'];
            	if($templateRS['body_text'])$row['body_text'] = $templateRS['body_text'];
            }
        }
        $framework->tpl->assign("NEWSLETTER", $row);
        if ($_REQUEST['id'] && $row['format']!='T') {
            editorInit('body_html');
        }
        $framework->tpl->assign("TEMPLATE", $newsletter->templateCombo());
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/newsletter/tpl/newsletter_form.tpl");
        break;
   
   /**
    * Email Nesletter
  	* Author   : 
  	* Created  : 
  	* Modified : 09/Apr/2008 By Vipin
  	* Modified : 10/Apr/2008 By Vipin //for sending email newsletter with batch mail and time delay features
  	*/	
   
    case "send":
		ini_set("display_errors",1);
error_reporting(E_ALL ^ E_NOTICE);
    	set_time_limit(0);
        $msgArr = array(1=>"Search Members", 2=>"Newsletter Details", 3=>"Finish");
        $req = &$_REQUEST;
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            if ($req['step'] == 1) {
			
                if( is_numeric($message = $newsletter->newsletterCount($req)) ) {
                    if( $message > 0 ) {
                        redirect(makeLink(array("mod"=>"newsletter", "pg"=>"newsletter"), "act=send&id={$req['id']}&list_id={$req['list_id']}&reg_pack={$req['reg_pack']}&sub_pack={$req['sub_pack']}&email={$req['email']}&format={$req['format']}&confirmed={$req['confirmed']}&active={$req['active']}&country={$req['country']}&all_users={$req['all_users']}&date_from={$req['date_from']}&date_to={$req['date_to']}&step=2&count=$message&active_store={$req['active_store']}"));
                    } else {
                        $message = "Your search returned 0 members.";
                    }
                }
            } elseif ($req['step'] == 2) { 
				$req['batch_nos'] = $req['batch_email'];
				 $req['time_interval'] = $req['delayed_seconds'];
                if( is_numeric($message = $newsletter->newsletterScheduleAdd($req)) ) {
                    redirect(makeLink(array("mod"=>"newsletter", "pg"=>"newsletter"), "act=send&id={$message}&batch_email={$req['batch_email']}&delayed_seconds={$req['delayed_seconds']}&step=3"));
                }
            }
            setMessage($message);
        }
        switch ($req['step']) {
            default:
            case 1:
				$mailing_list_enable = $newsletter->checkMailingListisEnabled();
				$framework->tpl->assign("MAILINGLIST_ENABLE", $mailing_list_enable['active']);
				$store_module_enabled = $newsletter->checkStoreEnabled();
				$framework->tpl->assign("STORE_ENABLE", $store_module_enabled['active']);
                $news = $newsletter->newsletterGet($_REQUEST['id']);
                $framework->tpl->assign("MAILINGFORMAT", $news['format']);
                $framework->tpl->assign("MAILINGLIST", $newsletter->listCombo());
				$framework->tpl->assign("REGPACKG",$user ->loadRegPack2());
                break;
            case 2:
                $newsletterRS = $newsletter->newsletterGet($req['id']);
                $framework->tpl->assign("NEWSLETTER", $newsletterRS['name']);
                $mailingListRS = $newsletter->mailingListGet($req['list_id']);
                $framework->tpl->assign("OWNER", array("name"=>$mailingListRS['owner_name'], "email"=>$mailingListRS['owner_email']));
                break;
            case 3:
            	
                $rs = $newsletter->newsletterScheduleGet($req['id']);
				
				
				
                $framework->tpl->assign("SCHEDULE", $rs);
                break;
        }
		$framework->tpl->assign("COUNTRY_LIST", $user->listCountry());
		$framework->tpl->assign("STEP", $req['step'] ? $req['step'] : 1);
        $framework->tpl->assign("STEP_MESSAGE", $msgArr);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/newsletter/tpl/newsletter_send.tpl");
        break;
		
	case "view_newsletter_emails": 
		$emails = $newsletter->newsletterEmails($_REQUEST['country'],$_REQUEST['active'],$_REQUEST['confirmed'],$_REQUEST['reg_pack'],$_REQUEST['sub_pack'],$_REQUEST['date_from'],$_REQUEST['date_to'],$_REQUEST['email'],$_REQUEST['active_store']);
		$framework->tpl->assign("EMAILS", $emails);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/newsletter/tpl/email_list.tpl");
       	$framework->tpl->display($global['curr_tpl']."/adminPopup.tpl");
		exit;
	break;
    case "delete":
        $newsletter->newsletterDelete($_REQUEST['id']);
        setMessage("Newsletter Deleted Successfully!", MSG_SUCCESS);
        redirect(makeLink(array("mod"=>"newsletter", "pg"=>"newsletter"), "act=list"));
        break;
	
		
}
if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}
?>