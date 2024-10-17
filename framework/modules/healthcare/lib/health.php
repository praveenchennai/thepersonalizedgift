<?php 
/**
* HealthCare Module 
*
* @author Ratheesh
* @package defaultPackage
*/

if($_REQUEST['manage']=="manage"){
	authorize_store();
}else{
	authorize();
}
include_once(FRAMEWORK_PATH."/modules/healthcare/lib/class.health.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");

$user           = new User();
$email	 		= new Email();
$healthObj		= new Health();

$req = &$_REQUEST;

$framework->tpl->assign("MEDICATION_LIST", $healthObj->getDropdownOptions(1));
$framework->tpl->assign("QUANTITY_LIST", $healthObj->getDropdownOptions(2));
$framework->tpl->assign("SIG_LIST", $healthObj->getDropdownOptions(3));
$framework->tpl->assign("GENERIC_LIST", $healthObj->getDropdownOptions(4));
$framework->tpl->assign("REFILL_LIST", $healthObj->getDropdownOptions(5));

switch($_REQUEST['act']) {
	case "unassigned_list":
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "assigned_date DESC";
		$orderBy = $orderBy.",assigned_date DESC";
		$mem_type = $_REQUEST["mem_type"];
		$stat = "Unassigned";
		if($_SERVER['REQUEST_METHOD']=="POST"){	$req = &$_REQUEST;}
		
	    list($rs, $numpad,$cnt_rs, $limitList) = $healthObj->unassignedList($req,$_REQUEST['pageNo'],$_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&fId=$fId&sId=$sId&id=".$_REQUEST['id'], OBJECT, $orderBy,$stat);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/healthcare/tpl/unassigned_list.tpl");
		break;
	case "doctor_assigned":
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "assigned_date DESC";
		$orderBy = $orderBy.",assigned_date DESC";
		
		$mem_type = $_REQUEST["mem_type"];
		$stat = "Assigned";
		if($_SERVER['REQUEST_METHOD']=="POST"){	$req = &$_REQUEST;}
		
	    list($rs, $numpad,$cnt_rs, $limitList) = $healthObj->doctorProcessList($req,$_REQUEST['pageNo'],$_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&fId=$fId&sId=$sId&id=".$_REQUEST['id'], OBJECT, $orderBy,$stat);
      		    
	   /* if(count($rs)>0){
			foreach($rs as $key=>$val){
				#$rs1[] = $healthObj->getCustomers($val->doctor_id);
				$rs[$key]->doctor	=	 $healthObj->getCustomers($val->doctor_id);
			}
		}
		*/
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/healthcare/tpl/doctor_assigned.tpl");
		break;
	case "doctor_accepted":
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "assigned_date DESC";
		$orderBy = $orderBy.",assigned_date DESC";
		
		$mem_type = $_REQUEST["mem_type"];
		$stat = "Accepted";
		
		if($_SERVER['REQUEST_METHOD']=="POST"){	$req = &$_REQUEST;}
		
	    list($rs, $numpad,$cnt_rs, $limitList) = $healthObj->doctorProcessList($req,$_REQUEST['pageNo'],$_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&fId=$fId&sId=$sId&id=".$_REQUEST['id'], OBJECT, $orderBy,$stat);
      	$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/healthcare/tpl/doctor_assigned.tpl");
		break;
	case "doctor_declined":
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "assigned_date DESC";
		$orderBy = $orderBy.",assigned_date DESC";
		
		$mem_type = $_REQUEST["mem_type"];
		$stat = "Declined";
		if($_SERVER['REQUEST_METHOD']=="POST"){	$req = &$_REQUEST;}
		
	    list($rs, $numpad,$cnt_rs, $limitList) = $healthObj->doctorProcessList($req,$_REQUEST['pageNo'],$_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&fId=$fId&sId=$sId&id=".$_REQUEST['id'], OBJECT, $orderBy,$stat);
      	$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/healthcare/tpl/doctor_assigned.tpl");
		break;
	case "pharmacy_assigned":
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "assigned_date DESC";
		$orderBy = $orderBy.",assigned_date DESC";
		
		$mem_type = $_REQUEST["mem_type"];
		$stat = "Pharmacy_Assigned";
		if($_SERVER['REQUEST_METHOD']=="POST"){	$req = &$_REQUEST;}
		
	    list($rs, $numpad,$cnt_rs, $limitList) = $healthObj->doctorProcessList($req,$_REQUEST['pageNo'],$_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&fId=$fId&sId=$sId&id=".$_REQUEST['id'], OBJECT, $orderBy,$stat);
    	$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/healthcare/tpl/pharmacy_assigned.tpl");
		break;
	case "shipped":
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "assigned_date DESC";
		$orderBy = $orderBy.",assigned_date DESC";
		
		$mem_type = $_REQUEST["mem_type"];
		$stat = "shipped";
		if($_SERVER['REQUEST_METHOD']=="POST"){	$req = &$_REQUEST;}
		
	    list($rs, $numpad,$cnt_rs, $limitList) = $healthObj->doctorProcessList($req,$_REQUEST['pageNo'],$_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&fId=$fId&sId=$sId&id=".$_REQUEST['id'], OBJECT, $orderBy,$stat);
    	$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/healthcare/tpl/pharmacy_assigned.tpl");
		break;
	case "changed_request":
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "assigned_date DESC";
		$orderBy = $orderBy.",assigned_date DESC";
		$stat = "changed_request";
		if($_SERVER['REQUEST_METHOD']=="POST"){	$req = &$_REQUEST;}
		
	    list($rs, $numpad,$cnt_rs, $limitList) = $healthObj->doctorProcessList($req,$_REQUEST['pageNo'],$_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&fId=$fId&sId=$sId&id=".$_REQUEST['id'], OBJECT, $orderBy,$stat);
    	$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/healthcare/tpl/doctor_assigned.tpl");
		break;
	case "void":
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "assigned_date DESC";
		$orderBy = $orderBy.",assigned_date DESC";
		$stat = "void";
		if($_SERVER['REQUEST_METHOD']=="POST"){	$req = &$_REQUEST;}
		
	    list($rs, $numpad,$cnt_rs, $limitList) = $healthObj->unassignedList($req,$_REQUEST['pageNo'],$_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&fId=$fId&sId=$sId&id=".$_REQUEST['id'], OBJECT, $orderBy,$stat);
      	$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/healthcare/tpl/unassigned_list.tpl");
		break;
	case "view":
		if($_REQUEST['remove_id']>0)
		{	
			if( ($message = $healthObj->removeAttach($_REQUEST['remove_id'])) === true ) {
					setMessage("Medical Attachment Removed Successfully", MSG_SUCCESS);
					redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=$_REQUEST[act]&id=$_REQUEST[id]&assign_id=$_REQUEST[assign_id]&refill=$_REQUEST[refill]&doct_id=$_REQUEST[doct_id]&pharma_id=$_REQUEST[pharma_id]&stat=$_REQUEST[stat]"));
			}
		}
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
				$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
				$dynamic_vars["ORDER_NUMBER"]  = $rs_health["tracking_no"];
				$dynamic_vars["CUD_TOTAL"]  = $rs_health["cud_tot"];
			
			if($req['btn_upload'] =='Upload'){
				//For pdf file
				$pdf_file		=	basename($_FILES['image_extension']['name']);
				$tmp_pdf_file	=	$_FILES['image_extension']['tmp_name'];
				if( ($message = $healthObj->uploadMedicalFile($req,$pdf_file,$tmp_pdf_file)) === true ) {
            		setMessage("Medical Attachment Uploaded Successfully", MSG_SUCCESS);
				}
			}elseif(($req['assign_id']>0) AND ($req['btn_void'] =='Void')){
				if( ($message = $healthObj->updateMedicalAssign($req)) === true ) {
					setMessage("Medical Questionnaire is Voided Successfully", MSG_SUCCESS);
					$email->send("void_user",$mail_header,$dynamic_vars);
					redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=unassigned_list"));
				}
			}elseif($req['stat']=='Assigned' || $req['stat']=='Changed_Request'){
				if( ($message = $healthObj->assignMedicalTo($req)) === true ) {
					setMessage("Medical Questionnaire is Re-Assigned Successfully", MSG_SUCCESS);
					//$rs_email = $healthObj->getCustomers($req['assign_to']);
					//$mail_header["to"]   = $rs_email["email"];
					$email->send("doctor_assigned",$mail_header,$dynamic_vars);
					redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=doctor_assigned"));
				}
			}elseif($req['stat']=='Declined'){
				if( ($message = $healthObj->reassignDeclinedMedicalTo($req)) === true ) {
					setMessage("Medical Questionnaire is Re-Assigned Successfully", MSG_SUCCESS);
					//$rs_email = $healthObj->getCustomers($req['assign_to']);
					//$mail_header["to"]   = $rs_email["email"];
					$email->send("doctor_assigned",$mail_header,$dynamic_vars);
					redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=doctor_declined"));
				}
			}elseif($req['stat']=='Accepted'){
				$pharm_id = $req['assign_to_pharma'];
				$max_assign1 = $healthObj->maxAssignValue();
				$max_assign = $max_assign1['0']['value'];
				$total_assign = $healthObj->pharmacyTotalAssignments($pharm_id);
				if($total_assign < $max_assign){
					if( ($message = $healthObj->assignMedicalTo($req)) === true ) {
						setMessage("Medical Prescription is Assigned Successfully", MSG_SUCCESS);
						//$rs_email = $healthObj->getCustomers($req['assign_to_pharma']);
						//$mail_header["to"]   = $rs_email["email"];
						$email->send("pharmacy_assigned",$mail_header,$dynamic_vars);
						redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=doctor_accepted"));
					}
				}else{
					setMessage("Exceeded Total Number of Assignments for the day", MSG_ERROR);
					redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=doctor_accepted"));
				}
			}elseif($req['stat']=='Pharmacy_Assigned'){
				if( ($message = $healthObj->assignMedicalTo($req)) === true ) {
					setMessage("Medical Prescription is Re-Assigned Successfully", MSG_SUCCESS);
					//$rs_email = $healthObj->getCustomers($req['assign_to_pharma']);
					//$mail_header["to"]   = $rs_email["email"];
					$email->send("pharmacy_assigned",$mail_header,$dynamic_vars);
					redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=pharmacy_assigned"));
				}
			}elseif($req['stat']=='Shipped' && $req['btn_cud'] =='Submit'){
				if( ($message = $healthObj->updateAddcudTotal($req)) === true ) {
					setMessage("CUD Total Added Successfully", MSG_SUCCESS);
					$email->send("cud_total",$mail_header,$dynamic_vars);
					redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=shipped"));
				}
			}elseif($req['refil_mod']%3>0 && $req['refil_mod']>0){
				$req['stat'] = 'Pharmacy_Assigned';
				if( ($message = $healthObj->assignMedicalTo($req)) === true ) {
            		setMessage("Medical Prescription is Assigned Successfully", MSG_SUCCESS);
					//$rs_email = $healthObj->getCustomers($req['assign_to_pharma']);
					//$mail_header["to"]   = $rs_email["email"];
					$email->send("pharmacy_assigned",$mail_header,$dynamic_vars);
                	redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=pharmacy_assigned"));
            	}
			}else{
				if($req['stat'] <> 'Doctor_Assigned_Request') $req['stat'] = 'Assigned';
				$doctor_id = $req['assign_to'];
				$max_assign1 = $healthObj->maxAssignValue();
				$max_assign = $max_assign1['0']['value'];
				$total_assign = count($healthObj->doctorTotalAssignments($doctor_id));
				if($total_assign < $max_assign){
					if( ($message = $healthObj->assignMedicalTo($req)) === true ) {
						setMessage("Medical Questionnaire is Assigned Successfully", MSG_SUCCESS);
						//$rs_email = $healthObj->getCustomers($req['assign_to']);
						//$mail_header["to"]   = $rs_email["email"];
						$email->send("doctor_assigned",$mail_header,$dynamic_vars);
						redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=unassigned_list"));
					}
				}else{
					setMessage("Exceeded Total Number of Assignments for the day", MSG_ERROR);
					redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>$_REQUEST['pg']), "act=unassigned_list"));
				}
			}
            setMessage($message);
        }
		
		$medical_id	=	$_REQUEST['id'];
		
		$rs = $healthObj->getUserHealthDetail($medical_id);
		$rs['refill'] = $_REQUEST['refill'];
		$rs_all_doctor = $healthObj->listCustomers(2);
		$rs_all_Pharmacy = $healthObj->listCustomers(3);
		$framework->tpl->assign("STAT",$req['stat']);
		$rs_doctor_name = $healthObj->getCustomers($req['doct_id']);
		$framework->tpl->assign("DOCTOR_NAME",$rs_doctor_name['name']);
		
		$rs_pharmacy_name = $healthObj->getCustomers($req['pharma_id']);
		$rs_pdf_attach = $healthObj->getFileAttach($medical_id);
		
		$framework->tpl->assign("PDF_ATTACH",$rs_pdf_attach);
		$framework->tpl->assign("PHARMACY_NAME",$rs_pharmacy_name['name']);
		$framework->tpl->assign("DOCTOR_LIST",$rs_all_doctor);
		$framework->tpl->assign("PHARMACY_LIST",$rs_all_Pharmacy);
		$framework->tpl->assign("USER_DETAILS",$rs);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/healthcare/tpl/med_quest_details.tpl");
		break;
	case "medq_report":
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "assigned_date DESC";
		$orderBy = $orderBy.",assigned_date DESC";
		if($_SERVER['REQUEST_METHOD']=="POST"){	$req = &$_REQUEST;}
		
		list($rs, $numpad,$cnt_rs, $limitList) = $healthObj->allProcessList($req,$_REQUEST['pageNo'],$_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&fId=$fId&sId=$sId&id=".$_REQUEST['id'], OBJECT, $orderBy,$stat);
    	
		if(count($rs)>0){
			foreach($rs as $key=>$value){
				 $value1 = $healthObj->medCreatedDate($rs[$key]->medical_questionnaire_id);
				  $rs[$key]->assigned_date = $value1[0]->assigned_date;
			}
		}
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/healthcare/tpl/medical_report.tpl");
		break;
		
	case "report_view":
		$medical_id	=	$_REQUEST['id'];
		$rs_member = $healthObj->getMember($medical_id);
		$framework->tpl->assign("DOCTOR_NAME",$rs_doctor_name['name']);
		$rs_customer = $healthObj->getCustomers($rs_member[member_id]);
		$framework->tpl->assign("CUSTOMER_DET",$rs_customer);
		$rs = $healthObj->customerReportDetails($medical_id);
		if($_SERVER['REQUEST_METHOD']=="POST"){
			$req = &$_REQUEST;
			$req['user_id'] = $_SESSION['adminSess']->id;
			$req['postdate'] = date('Y-m-d H:i:s');
			$healthObj->insertComments($req);
			redirect(makeLink(array("mod"=>$req['mod'], "pg"=>$req['pg']), "act=".$req['act']."&id=".$req['id']."&assign_id=".$req['assign_id']."&refill=".$req['refill']."&doct_id=".$req['doct_id']."&pharma_id=".$req['pharma_id']."&stat=".$req['stat']."&sId=".$req['sId']."&fId=".$req['fId']));
		}
		
		$rs_comment = $healthObj->fetchComments($medical_id);
		$framework->tpl->assign("COMMENT_COUNT",count($rs_comment));
		$framework->tpl->assign("USER_DETAILS",$rs);
		$framework->tpl->assign("COMMENT_LIST",$rs_comment);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/healthcare/tpl/medical_report_view.tpl");
		break;
	}

		$framework->tpl->assign("SECTION_LIST", $mem_type);
		$framework->tpl->assign("USER_LIST", $rs);
		$framework->tpl->assign("USER_NUMPAD", $numpad);
		$framework->tpl->assign("LIMIT_LIST",$limitList);
		$framework->tpl->assign("SEARCH_BY_DATE",$stat);
if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}

?>