<?php
/**
 * **********************************************************************************
 * @package    Flyer
 * @name       manager.php
 * @version    1.0
 * @author     Retheesh Kumar
 * @copyright  2008 Newagesmb (http://www.newagesmb.com), All rights reserved.
 * Created on  15-Jan-2008
 * 
 * This script is a part of NewageSMB Framework. This Framework is not a free software.
 * Copying, Modifying or Distributing this software and its documentation (with or 
 * without modification, for any purpose, with or without fee or royality) is not
 * permitted.
 * 
 ***********************************************************************************/
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.manager.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.photo.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");

$FlyerObj		=	new	Flyer();
$AlbumObj		=	new Album();
$MangerObj		=	new Manager($AlbumObj);
$PhotoObj		=	new Photo();
$email			= 	new Email();

$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
$pageNo 		=	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$MemberId		=	$_SESSION['memberid'];

switch($_REQUEST['act']) 
{
	case 'manager_search':
		/**
	  	 * Manager Search page
	  	 * Author   : Retheesh
	  	 * Created  : 15/Jan/2008
	  	 * Modified : 15/Jan/2008 By Retheesh
	  	 */
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/flyer/tpl/manager_search.tpl");
		break;
	case "rating"	:
		if ($_REQUEST['rate'])
		{
			$go_back_url = fetchPreURL(); 
			($_REQUEST['role'])? $role = $_REQUEST['role'] : $role = 'manager';
			$msg = $AlbumObj->AddRating('member_master',$_REQUEST['rating_user_id'],$role,$_REQUEST['rate'],$_SESSION['memberid'],1,'media_rating');
			setMessage($msg);
			if ($go_back_url)
			{
				redirect($go_back_url);
			}
		}
		break;
	case "managersearch_results":	
		/**
	  	 * Manager Search Results page
	  	 * Author   : Retheesh
	  	 * Created  : 15/Jan/2008
	  	 * Modified : 15/Jan/2008 By Retheesh
	  	 */
		
		checkLogin();
		
		$limit	=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "5";
		$Params	=	"mod=$mod&pg=$pg&act=managersearch_results&criteria={$_REQUEST['criteria']}";
		list($manager_det,$numpad,$cnt_rs,$limitList) = $MangerObj->getManagerSearchDetails($pageNo,$limit,$Params,'',$_REQUEST['criteria'],$MemberId, $_REQUEST['commisionminimum'], $_REQUEST['commisionmaximum']);
		$Start	=	($pageNo - 1) * $limit + 1;
		$end	=	($pageNo - 1) * $limit + $limit;
		if($end > $cnt_rs)
			$end	=	$cnt_rs;
		
		$framework->tpl->assign("SEARCH_RESULTS", $manager_det);
		$framework->tpl->assign("BROKER_COUNT", count($manager_det));
		$framework->tpl->assign("Start", $Start);
		$framework->tpl->assign("End", $end);
		$framework->tpl->assign("TotalFound", $cnt_rs);
		$framework->tpl->assign("PAGE_NUMBERS", $numpad);
		
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/managersearchresults.tpl");
		break;
		
	case 'my_assigned_properties':
		$limit	=	10;
		list($AssignedProps,$numpad,$cnt_rs, $limitList)	=	$MangerObj->getMyAssignedPropertiesOfManager($MemberId, $pageNo, $limit, $params, '', $AlbumObj, $PhotoObj, $objUser);
		//print_r($AssignedProps);//exit;		
		$framework->tpl->assign('BROKER_PROPERTIES', $AssignedProps);
		$framework->tpl->assign('BROKER_NUMPAD', $numpad);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/mypropertiesassigned_managerlist.tpl");
		break;
		
	case 'asyncmanagersearch_results':
		$limit	=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "5";
		
		$Params			=	"mod=$mod&pg=$pg&act=managersearch_results&criteria={$_REQUEST['criteria']}&commisionminimum={$_REQUEST['commisionminimum']}&commisionmaximum={$_REQUEST['commisionmaximum']}";
		$AsyncOutput	=	$MangerObj->getAsynchronousOutputForManagerSearchResults($_REQUEST['criteria'],$pageNo,$limit,$Params,'',$MemberId, $_REQUEST['commisionminimum'], $_REQUEST['commisionmaximum']);
		print $AsyncOutput;
		exit;		
		
	case 'assign_property':
		/**
		 * @desc The following case used for assigning properties to a broker
		 * 
		 * Author 	:	vimson@newagesmb.com
		 * 
		 */
		checkLogin();
		
		$Action		=	trim($_REQUEST['Action']);
		$managerId	=	trim($_REQUEST['manager_id']);
		$framework->tpl->assign("manager_id", $managerId);
		if($_SERVER['REQUEST_METHOD'] == 'POST' && $Action == 'Assign') 
		{
			
			$arr = array();
			$arr['property_id'] = $_POST['PropertyId'];
			$arr['property_owner_id'] = $_SESSION['memberid'];
			$arr['assigned_user_id'] = $managerId;
			$arr['assigned_role'] = 'PROP_MANAGER';
			$arr['request_time'] = date('Y-m-d H:i:s');
			$arr['request_description'] = $_POST['request_description'];
			$status = $MangerObj->assignPropertyToManager($arr);
			if($status==true)
			{
			  $type="assignPropertyToManager";
			  $MemberId=$_SESSION['memberid'];
				  $email->mailSend($type,$MemberId,$_POST);
			
			}
			
			//redirect(makeLink(array("mod"=>"flyer", "pg"=>"manager"), "act=assign_property&manager_id=$managerId"));
		}
		
	
		if($managerId== '') {
			redirect(makeLink(array("mod"=>"flyer", "pg"=>"manager"), "act=manager_search"));
		}
		
		$limit	 	=	10;
		$Params		=	"mod=$mod&pg=$pg&act=assign_property&manager_id=$managerId";
		list($Properties,$numpad,$cnt,$limitList )	=	$FlyerObj->getPropertiesOfUser($_SESSION['memberid'], $pageNo, $limit, $Params, $AlbumObj, $PhotoObj,'PROP_MANAGER');
					//print_r($Properties);
		$ManagerDetails	=	$objUser->getUserDetails($managerId);
		$UserDetails		=	$objUser->getUserDetails($MemberId);
		$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
		$framework->tpl->assign("MEM_DET", $UserDetails);
		$framework->tpl->assign("PROPERTIES", $Properties);
		$framework->tpl->assign("PAGE_NUMBERS", $numpad);
		$framework->tpl->assign("Broker", $ManagerDetails);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/assign_property_manager.tpl");
		break;
		
}		
$framework->tpl->display($global['curr_tpl']."/inner.tpl");	
?>