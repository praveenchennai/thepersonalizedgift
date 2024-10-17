<?php

session_start();
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");

$admin = new Admin();
$objUser=new User();
$objCms = new Cms();
$objEmail 	= 	new Email();
checkLogin();
$usr=$objUser->getUserDetails($_SESSION["memberid"]);
$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
$framework->tpl->assign("CAT_LIST", $objUser->getCategoryCombo($_REQUEST["mod"]));
$framework->tpl->assign("CAT_ARR", $objUser->getCategoryArr($_REQUEST["mod"]));
$framework->tpl->assign("chat_tpl",SITE_PATH."/templates/green/tpl/chat.tpl");// Code for including chat.......
$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("dating_right"));// to select the right menu of this page from database.
$framework->tpl->assign("advertisement",1);
$framework->tpl->assign("inner_content",1);
$topsubmenu=$objCms->linkList("topmembersub");
//$_REQUEST +=$usr;
//$_REQUEST+=$usr;
if($_REQUEST["pid"])
{
	$pid=$_REQUEST["pid"];
}else{
	$pid=$topsubmenu[0]->id;
}
//print_r($pid);exit;
$domain = $_SERVER['HTTP_HOST'];
$framework->tpl->assign("DOMAINNAME",$domain);
$framework->tpl->assign("TOPSUB_MENU", $topsubmenu);
$framework->tpl->assign("PID", $pid);
if(!session_register('chps1'))
{
	session_register('chps1');
}
if($_REQUEST['act']=='list')
{
	$_SESSION['chps1']=1;
}
elseif($_REQUEST['act']=='dating')
{
	$_SESSION['chps1']=2;
}
elseif($_REQUEST['act']=='health')
{
	$_SESSION['chps1']=3;
}
elseif($_REQUEST['act']=='finance')
{
	$_SESSION['chps1']=4;
}
elseif($_REQUEST['act']=='swapshop')
{
	$_SESSION['chps1']=5;
}
elseif($_REQUEST['act']=='coupon')
{
	$_SESSION['chps1']=6;
}
$framework->tpl->assign("search_memtype",$_SESSION['chps1']);
switch($_REQUEST["act"])
{
	case "solitaire":
		/**
		 * This is used for Integrating Solitaire Game
		 * Author   : Jipson Thomas
		 * Created  : 29/04/2008
		 * Modified : 29/04/2008 By Jipson.
		 */
			checkLogin();
			if($_SESSION['chps1']==1){
				$title="SOCIAL ";
				$link="Social ";
			}elseif($_SESSION['chps1']==2){
				$title="DATING ";
				$link="Dating ";
			}elseif($_SESSION['chps1']==3){
				$link="Health";
				$title="HEALTH";
			}else{
				$link="Wealth";
				$title="WEALTH";
			}
			$framework->tpl->assign("LINK",$link);
			$framework->tpl->assign("TITLE",$title);
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/solitaire.tpl");
	break;
		
	case "freecell":
		/**
		 * This is used for Integrating Freecell Game
		 * Author   : Jipson Thomas
		 * Created  : 29/04/2008
		 * Modified : 29/04/2008 By Jipson.
		 */
			checkLogin();
			if($_SESSION['chps1']==1){
				$title="SOCIAL ";
				$link="Social ";
			}elseif($_SESSION['chps1']==2){
				$title="DATING ";
				$link="Dating ";
			}elseif($_SESSION['chps1']==3){
				$link="Health";
				$title="HEALTH";
			}else{
				$link="Wealth";
				$title="WEALTH";
			}
			$framework->tpl->assign("LINK",$link);
			$framework->tpl->assign("TITLE",$title);
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/freecell.tpl");
	break;
	case "speedtetris":
		/**
		 * This is used for Integrating speedtetris Game
		 * Author   : Jipson Thomas
		 * Created  : 29/04/2008
		 * Modified : 29/04/2008 By Jipson.
		 */
			checkLogin();
			if($_SESSION['chps1']==1){
				$title="SOCIAL ";
				$link="Social ";
			}elseif($_SESSION['chps1']==2){
				$title="DATING ";
				$link="Dating ";
			}elseif($_SESSION['chps1']==3){
				$link="Health";
				$title="HEALTH";
			}else{
				$link="Wealth";
				$title="WEALTH";
			}
			$framework->tpl->assign("LINK",$link);
			$framework->tpl->assign("TITLE",$title);
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/speedtetris.tpl");
	break;
	
	case "betobeto":
		/**
		 * This is used for Integrating betobeto Game
		 * Author   : Jipson Thomas
		 * Created  : 29/04/2008
		 * Modified : 29/04/2008 By Jipson.
		 */
			checkLogin();
			if($_SESSION['chps1']==1){
				$title="SOCIAL ";
				$link="Social ";
			}elseif($_SESSION['chps1']==2){
				$title="DATING ";
				$link="Dating ";
			}elseif($_SESSION['chps1']==3){
				$link="Health";
				$title="HEALTH";
			}else{
				$link="Wealth";
				$title="WEALTH";
			}
			$framework->tpl->assign("LINK",$link);
			$framework->tpl->assign("TITLE",$title);
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/betobeto.tpl");
	break;
	default:
		/**
		 * This is used for Integrating  Games Main Page
		 * Author   : Jipson Thomas
		 * Created  : 29/04/2008
		 * Modified : 29/04/2008 By Jipson.
		 */
			checkLogin();
			if($_SESSION['chps1']==1){
				$title="SOCIAL ";
				$link="Social ";
			}elseif($_SESSION['chps1']==2){
				$title="DATING ";
				$link="Dating ";
			}elseif($_SESSION['chps1']==3){
				$link="Health";
				$title="HEALTH";
			}else{
				$link="Wealth";
				$title="WEALTH";
			}
			$framework->tpl->assign("LINK",$link);
			$framework->tpl->assign("TITLE",$title);
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/game.tpl");
	break;
}

$framework->tpl->display($global['curr_tpl']."/inner_webchat.tpl");
?>