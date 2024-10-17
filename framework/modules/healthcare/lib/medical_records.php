<?php 
/*
* HealthCare Module 
* @author Ratheesh
* @package defaultPackage
*/
include_once(FRAMEWORK_PATH."/modules/healthcare/lib/class.health.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");

$user = new User();
$email	 		= new Email();
$healthObj		= new Health();

$req = &$_REQUEST;

$framework->tpl->assign("MEDICATION_LIST", $healthObj->getDropdownOptions(1));
$framework->tpl->assign("QUANTITY_LIST", $healthObj->getDropdownOptions(2));
$framework->tpl->assign("SIG_LIST", $healthObj->getDropdownOptions(3));
$framework->tpl->assign("GENERIC_LIST", $healthObj->getDropdownOptions(4));
$framework->tpl->assign("REFILL_LIST", $healthObj->getDropdownOptions(5));

switch($_REQUEST['act']) {
		case "med_quest_list":
			$uid = $_REQUEST[uid];
			$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "assigned_date DESC";
			$orderBy = $orderBy.",assigned_date DESC";
			list($rs, $numpad,$cnt_rs, $limitList) = $healthObj->medQuestList($_REQUEST['pageNo'],$_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&fId=$fId&sId=$sId&id=".$_REQUEST['id'], OBJECT, $orderBy,$stat,$uid);
			$framework->tpl->assign("UID",$rs[0]->id);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/healthcare/tpl/med_quest_list.tpl");
		break;
		case "doctor_assigned":       
		    $orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "assigned_date ASC";
			$orderBy = $orderBy.",assigned_date ASC";
			$stat = "Assigned";
			list($rs, $numpad,$cnt_rs, $limitList) = $healthObj->doctorAssignedList($_REQUEST['pageNo'],$_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&fId=$fId&sId=$sId&id=".$_REQUEST['id'], OBJECT, $orderBy,$stat);
							
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/healthcare/tpl/doctor_assigned.tpl");
		break;
		case "doctor_accepted":
			$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "assigned_date ASC";
			$orderBy = $orderBy.",assigned_date ASC";
			if($_REQUEST['assign'] != ''){
				$stat = $_REQUEST['assign'];
			}else{
				$stat = "Accepted";
			}
			list($rs, $numpad,$cnt_rs, $limitList) = $healthObj->doctorAssignedList_new($_REQUEST['pageNo'],$_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&fId=$fId&sId=$sId&id=".$_REQUEST['id'], OBJECT, $orderBy,$stat);
			$framework->tpl->assign("STAT",$stat);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/healthcare/tpl/doctor_accepted.tpl");
		break;
		case "doctor_declined":
			$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "assigned_date ASC";
			$orderBy = $orderBy.",assigned_date ASC";
			$stat = "Declined";
			list($rs, $numpad,$cnt_rs, $limitList) = $healthObj->doctorAssignedList($_REQUEST['pageNo'],$_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&fId=$fId&sId=$sId&id=".$_REQUEST['id'], OBJECT, $orderBy,$stat);
			$framework->tpl->assign("STAT",$stat);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/healthcare/tpl/doctor_accepted.tpl");
		break;
		case "pharmacy_assigned":
			$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "assigned_date ASC";
			$orderBy = $orderBy.",assigned_date ASC";
			$stat = "Pharmacy_Assigned";
	    	list($rs, $numpad,$cnt_rs, $limitList) = $healthObj->doctorAssignedList($_REQUEST['pageNo'],$_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&fId=$fId&sId=$sId&id=".$_REQUEST['id'], OBJECT, $orderBy,$stat);
			$stat_pharma = "Assigned";
			
			$framework->tpl->assign("STAT",$stat_pharma);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/healthcare/tpl/doctor_accepted.tpl");
		break;
		case "shipped":
			$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "assigned_date ASC";
			$orderBy = $orderBy.",assigned_date ASC";
			$stat = "Shipped";
	    	list($rs, $numpad,$cnt_rs, $limitList) = $healthObj->doctorAssignedList_new($_REQUEST['pageNo'],$_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&fId=$fId&sId=$sId&id=".$_REQUEST['id'], OBJECT, $orderBy,$stat);
			$stat_pharma = "Shipped";
			
			$framework->tpl->assign("STAT",$stat_pharma);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/healthcare/tpl/doctor_accepted.tpl");
		break;
		case "med_quest_view":
			$medical_id	=	$_REQUEST['mqid'];
			$rs = $healthObj->getUserHealthDetail($medical_id);
			$rs['refill'] = $_REQUEST['refill'];
			$rs_all_doctor = $healthObj->listCustomers(2);
			$rs_all_Pharmacy = $healthObj->listCustomers(3);
			$framework->tpl->assign("STAT",$req['stat']);
			$rs_doctor_name = $healthObj->getCustomers($req['doct_id']);
			if($rs_doctor_name[first_name] != ""){
				$framework->tpl->assign("DOCTOR_NAME",$rs_doctor_name[first_name]."&nbsp;".$rs_doctor_name[last_name]);
			}
			
			$rs_pharmacy_name = $healthObj->getCustomers($req['pharma_id']);
			if($rs_pharmacy_name[first_name] != ""){
				$framework->tpl->assign("PHARMACY_NAME",$rs_pharmacy_name[first_name]."&nbsp;".$rs_pharmacy_name[last_name]);
			}
			$framework->tpl->assign("DOCTOR_LIST",$rs_all_doctor);
			$framework->tpl->assign("PHARMACY_LIST",$rs_all_Pharmacy);
			$framework->tpl->assign("USER_DETAILS",$rs);
			$framework->tpl->assign("MQID",$medical_id);
			$framework->tpl->assign("UID",$_REQUEST['uid']);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/healthcare/tpl/med_quest_details.tpl");
		break;
		case "med_quest_doctor":
		if($_SERVER['REQUEST_METHOD']=="POST")
		{	
			$req = &$_REQUEST;
			//Dynamic varibles for sending emails
				if($_REQUEST['id']>0){
					$rs_health = $healthObj->getUserHealthDetail($_REQUEST['id']);
				}
				$mail_header = array();
				$mail_header['from'] 	= 	$framework->config['admin_email'];
				$mail_header["to"]   = $rs_health["email"];
				$dynamic_vars = array();
				$dynamic_vars["USER_NAME"]  = $rs_health["username"];
				$dynamic_vars["FIRST_NAME"] = $rs_health["first_name"];
				$dynamic_vars["LAST_NAME"]  = $rs_health["last_name"];
				$dynamic_vars["HEIGHT"]  = $rs_health["height"];
				$dynamic_vars["WEIGHT"]  = $rs_health["weight"];
				$dynamic_vars["CHIEF_COMPLAINT"]  = $rs_health["chief_complaint"];
				$dynamic_vars["CURRENT_MEDICATION"]  = $rs_health["current_medication"];
				$dynamic_vars["LAST_EXAM_DATE"]  = $rs_health["last_exam_date"];
				$dynamic_vars["ALLERGIES"]  = $rs_health["allergies"];
				$dynamic_vars["SMOKE"]  = $rs_health["smoke"];
				$dynamic_vars["ALCOHOL"]  = $rs_health["alcohol"];
				$dynamic_vars["PAIN"]  = $rs_health["pain"];
				$dynamic_vars["FEDEX"]  = $rs_health["fedex"];
				$dynamic_vars["PRESCRIPTION"]  = $rs_health["prescription"];
				$dynamic_vars["ORDER_NUMBER"]  = $rs_health["tracking_no"];
				$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
				
			if($req['btn_submit']=='Accepted'){
				$req['stat'] = 'Accepted';
				if( ($message = $healthObj->assignMedicalTo($req)) === true ) {
					$doctorPrescription = $healthObj->givePrescription($req);
					setMessage("Medical Questionnaire Accepted Successfully", MSG_SUCCESS);
					$mail_header["to"]   = $rs_health["email"];
					$email->send("doctor_accepted",$mail_header,$dynamic_vars);
					redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=doctor_assigned"));
				}
			}
			if($req['btn_submit']=='Declined'){
				$req['stat'] = 'Declined';
				if( ($message = $healthObj->assignMedicalTo($req)) === true ) {
					$doctorPrescription = $healthObj->givePrescription($req);
					setMessage("Medical Questionnaire Declined Successfully", MSG_SUCCESS);
					$mail_header["to"]   = $rs_health["email"];
					$email->send("doctor_declined",$mail_header,$dynamic_vars);
					if($_REQUEST['act'] == "med_quest_doctor"){
						redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=doctor_accepted"));
					}else{
						redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=doctor_assigned"));
					}
				}
			}
			if($req['btn_submit']=='Shipped'){
				$req['stat'] = 'Shipped';
				if( ($message = $healthObj->assignMedicalTo($req)) === true ) {
					$pharmaDescription = $healthObj->giveDescription($req);
					setMessage("Medical Questionnaire Shipped Successfully", MSG_SUCCESS);
					$mail_header["to"]   = $rs_health["email"];
					$email->send("shipped",$mail_header,$dynamic_vars);
					redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=pharmacy_assigned"));
				}
			}
			if($req['btn_submit']=='Assign'){
				if( ($message = $healthObj->assignMedicalTo($req)) === true ) {
					setMessage("Medical Prescription is Re-Assigned Successfully", MSG_SUCCESS);
					//$rs_email = $healthObj->getCustomers($req['assign_to_pharma']);
					//$mail_header["to"]   = $rs_email["email"];
					$email->send("pharmacy_assigned",$mail_header,$dynamic_vars);
					redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=doctor_accepted"));
				}
			}
            setMessage($message);
        }
		
		$medical_id	=	$_REQUEST['id'];
		
		$rs_checkProfile = $healthObj->checkProfile($medical_id);
		
		if($rs_checkProfile == 'Accepted'){
			$framework->tpl->assign("DISP_OPT",'1');
		}else{
			$framework->tpl->assign("DISP_OPT",'0');
		}
		$rs = $healthObj->getUserHealthDetail($medical_id);
		$rs['refill'] = $_REQUEST['refill'];
		$rs_all_doctor = $healthObj->listCustomers(2);
		$rs_all_Pharmacy = $healthObj->listCustomers(3);
		$framework->tpl->assign("STAT",$req['stat']);
		$rs_doctor_name = $healthObj->getCustomers($req['doct_id']);
		$framework->tpl->assign("DOCTOR_NAME","one");
		$rs_pdf_attach = $healthObj->getFileAttach($medical_id);
		
		$framework->tpl->assign("PDF_ATTACH",$rs_pdf_attach);
		$rs_pharmacy_name = $healthObj->getCustomers($req['pharma_id']);
		$framework->tpl->assign("PHARMACY_NAME",$rs_pharmacy_name['name']);
		$framework->tpl->assign("DOCTOR_LIST",$rs_all_doctor);
		$framework->tpl->assign("PHARMACY_LIST",$rs_all_Pharmacy);
		$framework->tpl->assign("USER_DETAILS",$rs);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/healthcare/tpl/med_quest_view.tpl");
		break;
		
		case "add_med_quest":
			$uid = $_REQUEST[uid];
			if($_SERVER['REQUEST_METHOD']=="POST"){
				$medi_arr = array();
				$unassign = $objUser->GetUnsetFields();
				foreach ($unassign as $value) {
					$medi_arr[$value[field]] =  $_POST[$value[field]];
				}
				$objUser->unsetFields();
				$medi_arr['member_id'] =  $uid;
				$objUser->setArrData($medi_arr);
				$objUser->med_insert();
				redirect(makeLink(array("mod"=>healthcare, "pg"=>medical_records), "act=med_quest_list&uid=$uid"));
			}
			$framework->tpl->assign("PAIN_INDICATOR",$objUser->ListPainIndicator());
			$framework->tpl->assign("FEDEX_SHIPPING",$objUser->ListFedexShipping());
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/healthcare/tpl/med_quest_add.tpl");
		break;
		case "edit_med_quest":
			if($_SERVER['REQUEST_METHOD']=="POST"){
				$medi_arr = array();
				$unassign = $objUser->GetUnsetFields();
				foreach ($unassign as $value) {
					$medi_arr[$value[field]] =  $_POST[$value[field]];
				}
				$objUser->unsetFields();
				$medi_arr['member_id'] =  $_REQUEST[uid];
				$medi_arr['mqid'] =  $_REQUEST[mqid];
				$objUser->setArrData($medi_arr);
				$objUser->med_insert();
				redirect(makeLink(array("mod"=>healthcare, "pg"=>medical_records), "act=med_quest_list&uid=$uid"));
			}
			
			$mqid = $_REQUEST['mqid'];
			$med_rs = $healthObj->getUserHealthDetail($mqid);
			$framework->tpl->assign("MED_PAIN",split('[,]', $med_rs[pain]));
			$framework->tpl->assign("MED_QUEST",$med_rs);
			$all_pain_indicator = $objUser->ListPainIndicator();
			if(count($all_pain_indicator)>0){
				foreach($all_pain_indicator as $key=>$arrval){
					if($med_rs[pain_id]!="")
						$med_pain_id = explode(",",$med_rs[pain_id]);
					if(in_array($arrval['id'], $med_pain_id)){
					  $all_pain_indicator[$key]['selected'] =$arrval['id'];
					}else{
					   $all_pain_indicator[$key]['selected']="";
					}
				}
			
			}
			$framework->tpl->assign("PAIN_INDICATOR",$all_pain_indicator);
			$framework->tpl->assign("FEDEX_SHIPPING",$objUser->ListFedexShipping());
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/healthcare/tpl/med_quest_edit.tpl");
		break;		
		case "print_view":
		
			if($_SERVER['REQUEST_METHOD']=="GET"){
				if($_GET['sess']!=""){
					$decode = base64_decode($_GET['sess']);
					$decode_arr = explode("&",$decode);
					foreach($decode_arr as $arr_val){
						$splited     = explode("=",$arr_val);
						$splited_key = $splited[0];
						$splited_val = $splited[1];
						$_GET[$splited_key] = $splited_val;
					}
				}
					
				$medi_arr = array();
				$med_id = $_GET['id'];
				$doct_id = $_GET['doct_id'];
				$assign_id = $_GET['assign_id'];
				$med_usr_print = $healthObj->getDoctor_User_Detail($med_id,$doct_id,$assign_id);
			}
			$date_today = date("F-d-Y");
			$framework->tpl->assign("TODAY",$date_today);
			$framework->tpl->assign("PRINT",$_GET['print']);
			$framework->tpl->assign("PRINT_VIEW_DETAILS",$med_usr_print);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/healthcare/tpl/print_view.tpl");
		break;
	}
	$framework->tpl->assign("SECTION_LIST", $mem_type);
	$framework->tpl->assign("USER_LIST", $rs);
	$framework->tpl->assign("USER_NUMPAD", $numpad);
	$framework->tpl->assign("LIMIT_LIST",$limitList);
	if($_REQUEST['act']=="print_view"){
		$framework->tpl->display($global['curr_tpl']."/userPopup.tpl");
	}else{
		$framework->tpl->display($global['curr_tpl']."/inner.tpl");	
	}
?>