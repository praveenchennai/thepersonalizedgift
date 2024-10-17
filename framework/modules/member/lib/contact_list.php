<?
include_once(FRAMEWORK_PATH."/modules/event/lib/calendar.config.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
$user =new User;

	$userinfo = $objUser->getUserdetails($_SESSION["memberid"]);
	
	$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act'];
	
    if ($_REQUEST["stxt"])
	{
			$par = $par."&stxt=".$_REQUEST['stxt'];
	}
	
	
	list($rs, $numpad)  = $user->getListContacts($_REQUEST['pageNo'],10, $par, OBJECT, $_REQUEST['orderBy'],$_REQUEST['stxt'],$userinfo["username"]);
	
    $framework->tpl->assign("CONTACT", $rs);
	$framework->tpl->assign("UNMPAD", $numpad);
	
	$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/contact_list.tpl");
	$framework->tpl->display($global['curr_tpl']."/popup.tpl");

?>
