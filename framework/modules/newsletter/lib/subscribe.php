<?php

checkLogin();

include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.newsletter.php");

$newsletter = new Newsletter();

if($_SERVER['REQUEST_METHOD'] == "POST") {
    include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
    $user = new User();
    $userDetails = $user->getUserdetails($_SESSION['memberid']);
    
    $newsletter->subscribe($_SESSION['memberid'], $userDetails['email'], $_REQUEST['list_id']);
    
    redirect(makeLink(array("mod"=>"member", "pg"=>"home")));
}

$listRS = $newsletter->mailingListSubsList($_SESSION['memberid']);

$framework->tpl->assign("MAILING_LIST", $listRS);

$framework->tpl->assign("main_tpl",SITE_PATH."/modules/newsletter/tpl/subscribe.tpl");
$framework->tpl->display($global['curr_tpl']."/inner.tpl");

?>