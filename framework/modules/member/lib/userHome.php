<?php
session_start();
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user_db2.php");
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");

$db2User=new Userdb2();
$framework->tpl->assign("COUNTRY_LIST", $db2User->listCountry());

switch($_REQUEST["act"])
{
	case "change_pass":
	
		
		if($_SERVER['REQUEST_METHOD']=='POST'){
		
			$guid=$_SESSION['memberid'];
			if($db2User->changePassword($_POST["old_password"],$_POST["new_password"],$guid))
			{
				setmessage("Password changed successfully.",MSG_SUCCESS);
				redirect(makeLink(array('mod'=>"member",'pg'=>"userHome")));
			}
			else
			{
				setmessage($db2User->getErr());
			}
		}
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/change_password.tpl");
		break;


	case "my_account":

		
		break;
	case "myaccount":
		$framework->tpl->assign("USR",$usr);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/myhome.tpl");
		break;
	case "student":
		$sid=$_REQUEST['sid'];
		$guid=$_SESSION['memberid'];	
		
		//pagenation start
		 $userDetails=$db2User->getParentDetails($guid);
		 $framework->tpl->assign("firstname",$userDetails->first_name);
		
		if (isset($_GET['pageno'])) {
   			$pageno = $_GET['pageno'];
		} else {
  			$pageno = 1;
		} 
		$numrows=$db2User->getStudentcount($guid);
		//$numrows=4;
		$rows_per_page = 1;
		$lastpage      = ceil($numrows/$rows_per_page);
		
		$pageno = (int)$pageno;
		if ($pageno > $lastpage) {
		   $pageno = $lastpage;
		} // if
		if ($pageno < 1) {
		   $pageno = 1;
		} // if

		$limit = '	LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
		
		$rs=$db2User->getStudentList1($guid,$limit);
		
		if ($pageno == 1) {
			   $prev= "javascript:void(0)";
			   $framework->tpl->assign("MS_OVER",false);
			   
		} else {
			  $prevpage = $pageno-1;
			  $prev=makeLink(array('mod'=>"member",'pg'=>"userHome"),'act=student&pageno='.$prevpage);
			  
		} 
		$str	=" ($pageno of $lastpage) ";
		
		if ($pageno == $lastpage) {
		  	 $next= "javascript:void(0)";
		     $framework->tpl->assign("MS_NEXT",false);
		} else {
		   $nextpage = $pageno+1;
		   $next=makeLink(array('mod'=>"member",'pg'=>"userHome"),'act=student&pageno='.$nextpage);
		}
		
		
		$details_to_show_in_subpanel	=	$db2User->getDataFromScrequestTable($sid);
		$pay_details_to_show_in_subpanel	=	$db2User->getPaymentDetOfStudent($sid);
		$totalpayment = 0;
		 foreach ($pay_details_to_show_in_subpanel as $payd){
		 	if($payd['status'] == 'Processed'){
		 		$totalpayment = $totalpayment + $payd['amount'];
		 	}
		 }
		 $framework->tpl->assign("PAYMENT_TOTAL",$totalpayment);
		 
		//pagenation end
		$framework->tpl->assign("PAGENO",$pageno);
		$framework->tpl->assign("PREV",$prev);
		$framework->tpl->assign("STR",$str);
		$framework->tpl->assign("NEXT",$next);
		if($sid){
				$student_det=$db2User->getStudentAllDet($sid);
				$framework->tpl->assign("STUDENTDET",$student_det);
				$framework->tpl->assign("SID",$sid);
				$framework->tpl->assign("DATATODISPLAY",$details_to_show_in_subpanel);
				$framework->tpl->assign("PAY_DETAIL",$pay_details_to_show_in_subpanel);
			}
		else{
			$framework->tpl->assign("STUDENTDET",$rs);
			$framework->tpl->assign("SID",$rs['id']);
		}
		$defaultSemester = $db2User->getDefaultSemester();
		$framework->tpl->assign("DEFAULT_SEMESTER",$defaultSemester);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/student_myaccount.tpl");
		break;	
	case "edit":
		checkLogin();
		include_once(SITE_PATH."/common/db_connect.php"); 
		$sid=$_REQUEST['sid'];
		$flag=$_REQUEST['flag'];
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
			$studentid=$_POST['studentid'];
			unset($_POST['studentid']);
			$db2User->setArrData($_POST);
			
			
			if($studentid)
			{
				$db2User->studentUpdate($studentid);
				if($flag)
					redirect(makeLink(array('mod'=>"member",'pg'=>"home")));
				else
					redirect(makeLink(array('mod'=>"member",'pg'=>"home"),"act=student&sid=$sid"));
			}	
			else{
					$myId=$db2User->InsertStudentDetails();
					redirect(makeLink(array('mod'=>"member",'pg'=>"home")));
			}
		}
		if($sid)
		{
			$student_det=$db2User->getStudentDet($sid);
			$_REQUEST=$student_det;
			$framework->tpl->assign("SID",$sid);
		}	
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/regone1.tpl");
		break;
		
		
		
	case "enrollment_info":
		//$scr_id	=	$_REQUEST['aid'];
		$stu_id	=	$_REQUEST['sid'];
		$count		=	$_REQUEST['count'] ? $_REQUEST['count']-1 : $_SESSION['count'];
		$_SESSION['count'] = $count;
		/*$back	=	$_REQUEST['back'];
		$front	=	$_REQUEST['front'];
		
		if (isset($back)){
			$count	=	$count-$back;
		}
		if(isset($front)){
			$count	=	$count+$front;
		}
		
		
		
		
		$total	=	count($sc_req_ids);
		
		if($count<0){
			$count	=	0;
		}
		if($count>$total){
			$count	=	$total;
		}*/
		$sc_req_ids		=	$db2User->getIDFromScrequestTable($stu_id);
		$sc_id	=	$sc_req_ids[$count]['name'];
		
		$details_enrollment			=	$db2User->getEnrollmentDetails($sc_id);
		$details_enrollment_class	=	$db2User->getClassLists($sc_id);
		$details_screquest			=	$db2User->getDataFromScrequestTable($stu_id);
		/*$start	=	$count+1;
		$str	=	"(".$start." of ".$total.")";
		$framework->tpl->assign("STR",$str);*/
		
		$framework->tpl->assign("DATA",$details_enrollment);//Main section
		$framework->tpl->assign("CLASS_DATA",$details_enrollment_class);//For subpanel
		$framework->tpl->assign("CLASS_DATA_CHK",$details_screquest);//For subpanel approve button chk
		$framework->tpl->assign("TOTAL",$total);//Total number of SC Requests
		
		
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/enrollment.tpl");
		break;
		
	case "approve":
		$sid	=	$_REQUEST['sid'];
		$class_id	=	$_REQUEST['cid'];
		$screq_name	=	$_REQUEST['aid'];
		
		//$db2User->approveClasses($class_id,$sid);
		$db2User->approveClasses($class_id,$screq_name);
		$class_name_newly_approved	=	$db2User->getClassNameById($class_id);
		
		$framework->tpl->assign("CLASSNAME",$class_name_newly_approved);
		
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/approve.tpl");
		break;
		
	case "class_det":
		//$class_id	=	$_REQUEST['clid'];
		$scr_id		=	$_REQUEST['aid'];
		$count		=	$_REQUEST['count'] ? $_REQUEST['count']-1 : $_SESSION['count'];
		$back		=	$_REQUEST['back'];
		$front		=	$_REQUEST['front'];
		
		$ids_class		=	$db2User->getClassIDs($scr_id); //Get All Classes
		$total			=	count($ids_class);//Total number of classes
		
		//Browse thru the records START
		if (isset($back)){
			$count	=	$count-$back;
			
			if($count<0){
			$count	=	0;
			}
		}
		if(isset($front)){
			$count	=	$count+$front;
			
			if($count>=$total-1){
			$count	=	$total-1;
			}
		}
		
		
		$start	=	$count+1;	
		
		$str	=	"(".$start." of ".$total.")";
		$_SESSION['count'] = $count;
		//END
		
		$class_id		=	$ids_class[$count]['class_id'];//Id of the currently displayed class
		$details_class	=	$db2User->getClassDetails($class_id);//Get the details
		
		$framework->tpl->assign("DATA",$details_class);//Main section
		$framework->tpl->assign("STR",$str);
		
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/class_det.tpl");
		break;	
		
	default:
		 checkLoginUser();
		 $par = "mod=$mod&pg=$pg&act=".$_REQUEST['act'];
	     $guid=$_SESSION['memberid'];
		 $framework->tpl->assign("member",$guid);	
		 $userDetails=$db2User->getParentDetails($guid);
		 $framework->tpl->assign("firstname",$userDetails->first_name);
		 $userDetails->cardnumber=base64_decode($userDetails->cardnumber);
		 $payment_det =  $db2User->getPaymentDetOfLoggedUser($guid);
		 ///$paydet	=	$db2User->getPaymentDetOfLoggedUser($guid);
		 $totalpayment = 0;
		 foreach ($payment_det as $payd){
		 	if($payd['status'] == 'Processed'){
		 		$totalpayment = $totalpayment + $payd['amount'];
		 	}
		 }
		 
		 $framework->tpl->assign("PAYMENT_LIST",$payment_det);
		 $framework->tpl->assign("PAYMENT_TOTAL",$totalpayment);
		 
		 
		// $student_list=$db2User->getStudentList($guid);
		 list($rs,$numpad)=$db2User->getStudentList2($_REQUEST['pageNo'],50, $par, OBJECT, $_REQUEST['orderBy'],$guid);
		 $framework->tpl->assign("GUID",$guid);
		 $framework->tpl->assign("USERDET",$userDetails);
		 $framework->tpl->assign("STUDENT_LIST",$rs);
		 $framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/myaccount.tpl");
		
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>