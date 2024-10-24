<?php
session_start();

class Health extends FrameWork {
    
	var 	$errorMessage;
	var		$LeftMenuText;
	var		$CategorySelectionJs;
		
    function Cms() {
        $this->FrameWork();
    }
    /**
    * Returns the Unassigned List of all the Customers assigned to the Admin. Sort by Date, Latest on top.
  	* Author   : Ratheesh
  	* Created  : 16/Nov/2007	
  	*/
	function unassignedList ($req,$pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy,$stat)
	{
	
		$sql_medID = "SELECT  MAX( id ) AS medID FROM assign GROUP BY medical_questionnaire_id";
		$rs_medID = $this->db->get_results($sql_medID);
		if(count($rs_medID)>0){
			foreach($rs_medID as $val){
				$med_id[] = $val->medID;
			}
			$med_id = implode(",",$med_id);
		}else{
			$med_id = "0";
		}
		if($req[created_frm]!=""){
			list($m,$d,$y) = explode("-",$req[created_frm]);
			$req[created_frm] = $y."-".$m."-".$d;
		}
		if($req[created_to]!=""){
			list($m,$d,$y) = explode("-",$req[created_to]);
			$req[created_to] = $y."-".$m."-".$d;
		}
		if($req[created_frm]!="" AND $req[created_to]!="")
			$date_criteria ="AND a.assigned_date >"."'$req[created_frm]'"." AND a.assigned_date < "."'$req[created_to]'";
		elseif($req[created_frm]!="" )
			$date_criteria ="AND a.assigned_date >"."'$req[created_frm]'";
		elseif($req[created_to]!="" )
			$date_criteria ="AND a.assigned_date < "."'$req[created_to]'";
		
		$sql = "SELECT a.*,a.id AS assignID,c.* FROM assign a, medical_questionnaire b, member_master c WHERE a.medical_questionnaire_id = b.id
				AND a.id IN ($med_id) AND b.member_id = c.id AND a.status = '$stat' $date_criteria";
				
		//AND c.active='Y'
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}

   function doctorProcessList ($req,$pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy,$stat)
	{
	
		$sql_medID = "SELECT  MAX( id ) AS medID FROM assign GROUP BY medical_questionnaire_id";
		$rs_medID = $this->db->get_results($sql_medID);
		if(count($rs_medID)>0){
			foreach($rs_medID as $val){
				$med_id[] = $val->medID;
			}
			$med_id = implode(",",$med_id);
		}else{
			$med_id = "0";
		}
		if($req[created_frm]!=""){
			list($m,$d,$y) = explode("-",$req[created_frm]);
			$req[created_frm] = $y."-".$m."-".$d;
		}
		if($req[created_to]!=""){
			list($m,$d,$y) = explode("-",$req[created_to]);
			$req[created_to] = $y."-".$m."-".$d;
		}
		if($req[created_frm]!="" AND $req[created_to]!="")
			$date_criteria ="AND T1.assigned_date >"."'$req[created_frm]'"." AND T1.assigned_date < "."'$req[created_to]'";
		elseif($req[created_frm]!="" )
			$date_criteria ="AND T1.assigned_date >"."'$req[created_frm]'";
		elseif($req[created_to]!="" )
			$date_criteria ="AND T1.assigned_date < "."'$req[created_to]'";
			
		if($stat == "Pharmacy_Assigned" || $stat == "shipped")
			$pharm_doct_conditon = "T1.pharmacy_id";
		else $pharm_doct_conditon = "T1.doctor_id";
		if($stat == "Assigned")
			$stat_conditon = "OR T1.status = 'Doctor_Assigned_Request'";
		else $stat_conditon = "";
		/*$sql = "SELECT a.medical_questionnaire_id,a.assigned_date,a.refill,a.doctor_id,c.* FROM assign a, medical_questionnaire b, member_master c WHERE a.medical_questionnaire_id = b.id 
				 AND b.member_id = c.id AND a.status = '$stat'";*/
	 $sql = "SELECT T2.username AS DoctorName,T4.username AS CustomerName,T1.*,T1.id AS assignID,T4.* FROM assign AS T1 LEFT JOIN member_master AS T2 ON $pharm_doct_conditon = T2.id
				LEFT JOIN medical_questionnaire AS T3 ON T3.id = T1.medical_questionnaire_id LEFT JOIN member_master AS T4 ON T4.id = T3.member_id
				WHERE (T1.status = '$stat' $stat_conditon) AND T1.id IN ($med_id) $date_criteria";
		//AND c.active='Y'
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
   function assignMedicalTo (&$req) {
        extract($req);
		
		if($assign_to == 0 && $assign_to_pharma == 0) {
            $message = "Assign To is required";
        }else {
			$rs_admin = $this->listCustomers(0);
			if($req['stat']=='Accepted' && $assign_to_pharma>0){
				$req['stat'] = "Pharmacy_Assigned";
			}
			if($req['stat']=='Changed_Request'){
				$req['stat'] = "Doctor_Assigned_Request";
			}
			$admin_id = $_SESSION['adminSess']->id;
            $today_time = date("Y-m-d h:i:s");
			$arr_assign = array('admin_id'=>$admin_id,'doctor_id'=>$assign_to,'pharmacy_id'=>$assign_to_pharma,'medical_questionnaire_id'=>$medical_id,'assigned_date'=>$today_time,'status'=>$req['stat'],'refill'=>$refill);
			$this->db->insert("assign", $arr_assign);
			return true;
        }
        return $message;
    }
    
    function reassignDeclinedMedicalTo (&$req) {
        extract($req);
		
		if($assign_to == 0 && $assign_to_pharma == 0) {
            $message = "Assign To is required";
        }else {
			$rs_admin = $this->listCustomers(0);
			if($req['stat']=='Declined'){
				$req['stat'] = "Assigned";
			}
			$admin_id = $_SESSION['adminSess']->id;
            $today_time = date("Y-m-d h:i:s");
			$arr_assign = array('admin_id'=>$admin_id,'doctor_id'=>$assign_to,'pharmacy_id'=>$assign_to_pharma,'medical_questionnaire_id'=>$medical_id,'assigned_date'=>$today_time,'status'=>$req['stat'],'refill'=>$refill);
			$this->db->insert("assign", $arr_assign);
			return true;
        }
        return $message;
    }

    function givePrescription(&$req) {
        extract($req);
		if($medical_id>0) {
			for($j=0;$j<3;$j++){
				if($j>0){
					if($medication[$j] =='' AND $quantity[$j]=='' AND $sig[$j]=='' AND $generic[$j]=='' AND $doct_refill[$j]==''){
					}else{
						$arr_presc2 = array('questionnaire_id'=>$medical_id,'doctor_id'=>$assign_to,'medication'=>"$medication[$j]",'quantity'=>"$quantity[$j]",'sig'=>"$sig[$j]",'generic'=>"$generic[$j]",'doct_refill'=>"$doct_refill[$j]",'refill_no'=>$refill); 
						$this->db->insert("doctor_prescription", $arr_presc2);
					}
				}else{
         			$arr_presc1 = array('questionnaire_id'=>$medical_id,'doctor_id'=>$assign_to,'prescription'=>$prescription,'medication'=>$medication[0],'quantity'=>$quantity[0],'sig'=>$sig[0],'generic'=>$generic[0],'doct_refill'=>$doct_refill[0],'refill_no'=>$refill); 
					$this->db->insert("doctor_prescription", $arr_presc1);
				}
			}
	    }
		return true;
    }
   function giveDescription(&$req) {
        extract($req);
		if($medical_id>0) {
         	$arr_desc = array('questionnaire_id'=>$medical_id,'pharmacy_id'=>$assign_to_pharma,'description'=>'','tracking_no'=>$tracking_no,'refill_no'=>$req[refill]);
			$this->db->insert("pharmacy_shipping", $arr_desc);
	    }
		return true;
    }
	function updateAddcudTotal($req)
	{
		$questionnaire_id=$req['medical_id'];
		$pharmacy_id 	 =$req['pharma_id'];
		$refill_no 		 =$req['refill'];
		$cud_tot 		 =$req['cud_tot'];
		if($questionnaire_id>0) {
			$this->db->query("UPDATE `pharmacy_shipping` SET `cud_tot` = '$cud_tot' where questionnaire_id='$questionnaire_id' AND pharmacy_id='$pharmacy_id' AND refill_no='$refill_no'");
		}
		return true;
	}
	
	function updateMedicalAssign($req)
	{
		$assign_id=$req['assign_id'];
		if($assign_id>0) {
			$this->db->query("UPDATE `assign` SET `status` = 'Void' where id='$assign_id'");
		}
		return true;
	}
	
	
    function getUserHealthDetail ($medical_id) {
    	
		
		/*$sql="SELECT a.id AS medical_id ,a.*,b.*,c.*,b.id as id FROM medical_questionnaire a,`member_master` b LEFT JOIN `member_address` c on
			  b.id=c.user_id and c.addr_type='master'  where a.id = $medical_id AND b.id = a.member_id";
		$rs = $this->db->get_row($sql, ARRAY_A); 
		 */
		 $sql="SELECT T1.id AS medical_id ,T1.*,T2.*,T3.*,T2.id AS id,T4.address1 AS shipping_address1,T4.address2 AS shipping_address2,T4.state AS shipping_state,T4.city AS shipping_city,T4.postalcode AS shipping_postalcode,T4.telephone AS shipping_telephone FROM medical_questionnaire T1,`member_master` T2 LEFT JOIN `member_address` T3 ON
			  T2.id=T3.user_id and T3.addr_type='master' LEFT JOIN member_address AS T4 ON T2.id=T4.user_id and T4.addr_type='shipping'
			   where T1.id = $medical_id AND T2.id = T1.member_id";
		$rs = $this->db->get_row($sql, ARRAY_A); 
		
		
		$sql_pain_indicator = "SELECT a.fields AS pain_indicator,a.id AS pain_id FROM pain_indicator a,member_pain_indicator b WHERE a.id = b.pain_indicator_id AND b.medical_id = $medical_id";
		$rs_pain_indicator = $this->db->get_results($sql_pain_indicator, ARRAY_A);
		if(count($rs_pain_indicator)>0){
			foreach($rs_pain_indicator as $val){
				$new_arrPI[] = $val['pain_indicator'];
				$new_arrPID[] = $val['pain_id'];
			}
			$new_strPI = implode(",",$new_arrPI);
			$new_strPID = implode(",",$new_arrPID);
			$rs['pain'] = $new_strPI;
			$rs['pain_id'] = $new_strPID;
		}
		
		$sql_fedex = "SELECT a.fields AS fedex_shipping FROM fedex_shipping  a,member_fedex_shipping b WHERE a.id = b.fedex_shipping_id AND b.medical_id = $medical_id";
		$rs_fedex = $this->db->get_results($sql_fedex, ARRAY_A);
		if(count($rs_fedex)>0){
			foreach($rs_fedex as $val_fedex){
				$new_arrFDS[] = $val_fedex['fedex_shipping'];
			}
			$new_strFDS = implode(",",$new_arrFDS);
			$rs['fedex'] = $new_strFDS;
		}
		
		if($_REQUEST[assign_id]>0) $sql_extra_cond =" AND T2.id =$_REQUEST[assign_id]";
		
		$sql_prescription = "SELECT T1.cud_tot,T1.tracking_no  FROM  pharmacy_shipping AS T1,assign T2 WHERE T1.questionnaire_id  = $medical_id AND T2.refill=T1.refill_no AND T2.pharmacy_id = T1.pharmacy_id  $sql_extra_cond";
		$rs_prescription = $this->db->get_row($sql_prescription, ARRAY_A);
		if(count($rs_prescription)>0){
			$rs['cud_tot'] = $rs_prescription['cud_tot'];
		    $rs['tracking_no'] = $rs_prescription['tracking_no'];
		}
		
		$sql_doctPresc = "SELECT T1.*  FROM doctor_prescription T1,assign T2  WHERE T1.questionnaire_id  = $medical_id AND T2.refill=T1.refill_no AND T2.doctor_id  = T1.doctor_id  $sql_extra_cond";
		$rs_doctPresc = $this->db->get_results($sql_doctPresc,ARRAY_A);
		if(count($rs_doctPresc)>0){
			$rs['prescription'][0] = $rs_doctPresc[0]['prescription'];
			for($k=0;$k<count($rs_doctPresc);$k++){
				$rs['medication'][$k]   = $rs_doctPresc[$k]['medication'];
		    	$rs['quantity'][$k]     = $rs_doctPresc[$k]['quantity'];
				$rs['sig'][$k]          = $rs_doctPresc[$k]['sig'];
				$rs['generic'][$k]  = $rs_doctPresc[$k]['generic'];
				$rs['doct_refill'][$k]  = $rs_doctPresc[$k]['doct_refill'];
			}
		}
		return $rs;
    }
	
	/**
    * cron function for assign the form after every 28 days.
  	* Author   : Ratheesh Kk
  	* Created  : 08/Nov/2007	
	 * Modified : 08/Nov/2007 by Ratheesh Kk
  	*/
	function getReassignData()
	{
		$sql_medID = "SELECT  MAX( id ) AS medID FROM assign where  status NOT IN('Changed_Request','Doctor_Assigned_Request') GROUP BY medical_questionnaire_id ";
		
		$rs_medID = $this->db->get_results($sql_medID);
		if(count($rs_medID)>0){
			foreach($rs_medID as $val){
				$med_id[] = $val->medID;
			}
			$med_id = implode(",",$med_id);
		}else{
			$med_id = "0";
		}
		$sql = "select * from assign where status='shipped' AND id IN ($med_id) order by assigned_date DESC";
		$rs = $this->db->get_results($sql);
		$tot_shipped = count($rs);

		if($tot_shipped>0)
		{
			$framework 	= 	new FrameWork();
			$global 				=   $framework->config;
			$refillDays = $global['refill_days'];
			for($j=0;$j<$tot_shipped;$j++)
			{
				$date_assigned = $rs[$j]->assigned_date;

				$refill = $rs[$j]->refill;
				$medical_questioner_id = $rs[$j]->medical_questionnaire_id;
				$doctor_id = $rs[$j]->doctor_id;
				$sql_memb_active = "SELECT count(a.id) as cnt  from member_master a,medical_questionnaire b where a.id=b.member_id AND b.id = $medical_questioner_id AND a.active='Y'";
				$rs_memb_active = $this->db->get_row($sql_memb_active);

				if(($rs_memb_active->cnt)>0)
				{

					list($date_assigned, $time) = split(' ', $date_assigned);
					list($year,$month,$day) = split('[/.-]', $date_assigned);
					$new_date = date("Y-m-d", mktime(0, 0, 0, date($month), date($day+$refillDays), date($year)));
					$today = date("Y-m-d");
					$new_refill = $refill+1;

					if($new_date==$today)
					{
						$status = "Unassigned";
						$today_time = date("Y-m-d h:i:s");
						$arr_assign = array('admin_id'=>'','doctor_id'=>$doctor_id,'pharmacy_id'=>'','medical_questionnaire_id'=>$medical_questioner_id,'assigned_date'=>$today_time,'status'=>$status,'refill'=>$new_refill);
						$this->db->insert("assign", $arr_assign);
					}
				}
			}

		}
		return $rs;
	}



	function listCustomers($mem_type)
	{
		$sql = "Select id, username from member_master where active ='Y' AND mem_type = '$mem_type' ";
		$rs['id'] = $this->db->get_col($sql, 0);
		$rs['username'] = $this->db->get_col($sql, 1);
		return $rs;
	}
	function getCustomers($id)
	{
		$sql = "Select username AS name,email,first_name,last_name from member_master where id in($id)";
		$rs = $this->db->get_row($sql, ARRAY_A);
		return $rs;
	}	
	function getMember($medical_id)
	{
		$sql = "Select member_id from medical_questionnaire  where id = $medical_id";
		$rs = $this->db->get_row($sql, ARRAY_A);
		return $rs;
	}	
	function medQuestList($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy,$stat,$uid)
	{
		$sql_medID = "SELECT  MAX( id ) AS medID FROM assign GROUP BY medical_questionnaire_id";
		$rs_medID = $this->db->get_results($sql_medID);
		if(count($rs_medID)>0){
			foreach($rs_medID as $val){
				$med_id[] = $val->medID;
			}
			$med_id = implode(",",$med_id);
		}else{
			$med_id = "0";
		}
		
		$sql = "SELECT a.*,a.id AS assignID,c.* FROM assign a, medical_questionnaire b, member_master c WHERE a.medical_questionnaire_id = b.id
				AND a.id IN ($med_id) AND b.member_id = c.id AND c.id = '$_SESSION[memberid]'";
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
		
	}
	function GetMemType($uname)
	{
		$sql = "SELECT mem_type,id FROM member_master where username='$uname'";
		$rs = $this->db->get_row($sql);
		return $rs;
	}
	function doctorAssignList($uid)
	{
		$sql = "SELECT a.*,b.*,c.* FROM assign a, medical_questionnaire b, member_master c WHERE a.medical_questionnaire_id = b.id
				AND b.member_id = c.id AND a.doctor_id IN ($uid) order by a.assigned_date DESC";
		$rs = $this->db->get_results($sql, ARRAY_A);
		return $rs;
	}
	
	 function doctorAssignedList ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy,$stat)
	{
	
		$sql_medID = "SELECT  MAX( id ) AS medID FROM assign GROUP BY medical_questionnaire_id";
		$rs_medID = $this->db->get_results($sql_medID);
		if(count($rs_medID)>0){
			foreach($rs_medID as $val){
				$med_id[] = $val->medID;
			}
			$med_id = implode(",",$med_id);
		}else{
			$med_id = "0";
		}
		$rs_mem_type = $this->GetMemberType();
		if($rs_mem_type[mem_type]==2)
			$condition_member = "AND T1.doctor_id ='$_SESSION[memberid]'";
		elseif($rs_mem_type[mem_type]==3)
			$condition_member = "AND T1.pharmacy_id  ='$_SESSION[memberid]'";
		if($stat == "Pharmacy_Assigned")
			$pharm_doct_conditon = "T1.pharmacy_id";
		else $pharm_doct_conditon = "T1.doctor_id";
		if($stat == "Assigned")
			$stat_conditon = "OR T1.status = 'Doctor_Assigned_Request'";
		else $stat_conditon = "";
		
 $sql = "SELECT T1.*,T1.id AS assignID,T3.* FROM assign AS T1 LEFT JOIN medical_questionnaire AS T2 ON T2.id = T1.medical_questionnaire_id LEFT JOIN member_master AS T3 ON T3.id = T2.member_id
				WHERE (T1.status = '$stat' $stat_conditon) AND T1.id IN ($med_id) $condition_member";
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
	function doctorAssignedList_new ($pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy,$stat)
	{
		$sql_medID = "SELECT  MAX( id ) AS medID FROM assign GROUP BY medical_questionnaire_id";
		$rs_medID = $this->db->get_results($sql_medID);
		if(count($rs_medID)>0){
			foreach($rs_medID as $val){
				$med_id[] = $val->medID;
			}
			$med_id = implode(",",$med_id);
		}else{
			$med_id = "0";
		}
		$rs_mem_type = $this->GetMemberType();
		if($rs_mem_type[mem_type]==2)
			$condition_member = "AND T1.doctor_id ='$_SESSION[memberid]'";
		elseif($rs_mem_type[mem_type]==3)
			$condition_member = "AND T1.pharmacy_id  ='$_SESSION[memberid]'";
		if($stat == "Pharmacy_Assigned")
			$pharm_doct_conditon = "T1.pharmacy_id";
		else $pharm_doct_conditon = "T1.doctor_id";
		if($stat == "Assigned")
			$stat_conditon = "OR T1.status = 'Doctor_Assigned_Request'";
		else $stat_conditon = "";
		
 		$sql = "SELECT T1.*,T1.id AS assignID,T3.* FROM assign AS T1 LEFT JOIN medical_questionnaire AS T2 ON T2.id = T1.medical_questionnaire_id LEFT JOIN member_master AS T3 ON T3.id = T2.member_id
				WHERE (T1.status = '$stat' $stat_conditon) AND T1.id IN ($med_id) $condition_member";
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
	function GetMemberType()
	{
		$sql = "SELECT mem_type FROM member_master where id='$_SESSION[memberid]'";
		$rs = $this->db->get_row($sql,ARRAY_A);
		return $rs;
	}
	
	function GetPainIndicator($medical_id)
	{
		$sql_pain_indicator = "SELECT a.fields AS pain_indicator FROM pain_indicator a,member_pain_indicator b WHERE a.id = b.pain_indicator_id AND b.medical_id = $medical_id";
		$rs_pain_indicator = $this->db->get_results($sql_pain_indicator, ARRAY_A);
		if(count($rs_pain_indicator)>0){
			foreach($rs_pain_indicator as $val){
				$new_arrPI[] = $val['pain_indicator'];
			}
			$new_strPI = implode(",",$new_arrPI);
			$rs['pain'] = $new_strPI;
			return $rs['pain'];
		}
	}	
	
	function GetFedexShip($medical_id)
	{
		$sql_fedex = "SELECT a.fields AS fedex_shipping FROM fedex_shipping  a,member_fedex_shipping b WHERE a.id = b.fedex_shipping_id AND b.medical_id = $medical_id";
		$rs_fedex = $this->db->get_results($sql_fedex, ARRAY_A);
		if(count($rs_fedex)>0){
			foreach($rs_fedex as $val_fedex){
				$new_arrFDS[] = $val_fedex['fedex_shipping'];
			}
			$new_strFDS = implode(",",$new_arrFDS);
			$rs['fedex'] = $new_strFDS;
			return $rs['fedex'];
		}
	}
	/**
    * Gettling a options of drop down from the database
  	* Author   : Ratheesh
  	* Created  : 30/Nov/2007	
  	*/
	function getDropdownOptions($group_id)
	{
		$sql = "Select drop_down_id,value from drop_down Where group_id = $group_id";
		$rs['drop_down_id'] = $this->db->get_col($sql, 0);
		$rs['value'] = $this->db->get_col($sql, 1);
		return $rs;
	}
	
	/**
    * To get the max assign values to a doctor or pharmacy from config table
  	* Author   : Jeffy
  	* Created  : 26/Dec/2007
  	*/
	function maxAssignValue()
	{
		$sql = "Select value from config Where field = 'total_assign'";
		$rs = $this->db->get_results($sql, ARRAY_A);
		return $rs;
	}
	
	/**
    * To Fetch the total number of assignements for a particular doctor for today
  	* Author   : Jeffy
  	* Created  : 26/Dec/2007
  	*/
	function doctorTotalAssignments($doc_id)
	{
		$start_date = date("Y-m-d")." 00:00:00";
		$end_date = date("Y-m-d")." 23:59:59";
		$sql = "Select * from assign Where doctor_id='$doc_id' and assigned_date between '$start_date' and '$end_date'";
		$rs = $this->db->get_results($sql, ARRAY_A);
		return $rs;
	}
	
	/**
    * To Fetch the total number of assignements for a particular pharmacy for today
  	* Author   : Jeffy
  	* Created  : 26/Dec/2007
  	*/
	function pharmacyTotalAssignments($pharm_id)
	{
		$start_date = date("Y-m-d")." 00:00:00";
		$end_date = date("Y-m-d")." 23:59:59";
		$sql = "Select * from assign Where pharmacy_id='$pharm_id' and assigned_date between '$start_date' and '$end_date'";
		$rs = $this->db->get_results($sql, ARRAY_A);
		return $rs;
	}
	
	 function allProcessList ($req,$pageNo, $limit = 10, $params='', $output=OBJECT, $orderBy,$stat)
	{
	
		$sql_medID = "SELECT  MAX( id )AS medID,medical_questionnaire_id  AS medQstId FROM assign GROUP BY medical_questionnaire_id";
		$rs_medID = $this->db->get_results($sql_medID);
		if(count($rs_medID)>0){
			foreach($rs_medID as $val){
				$med_id[] = $val->medID;
				$old_medQID[] = $val->medQstId;
			}
			$med_id = implode(",",$med_id);
			$old_medQID = implode(",",$old_medQID);
		}else{
			$med_id = "0";
			$old_medQID = "0";
		}
		if($req[created_frm]!=""){
			list($m,$d,$y) = explode("-",$req[created_frm]);
			$req[created_frm] = $y."-".$m."-".$d;
		}
		if($req[created_to]!=""){
			list($m,$d,$y) = explode("-",$req[created_to]);
			$req[created_to] = $y."-".$m."-".$d;
		}
		if($req[created_frm]!="" AND $req[created_to]!="")
			$date_criteria ="assigned_date >"."'$req[created_frm]'"." AND assigned_date < "."'$req[created_to]'";
		elseif($req[created_frm]!="" )
			$date_criteria ="assigned_date >"."'$req[created_frm]'";
		elseif($req[created_to]!="" )
			$date_criteria ="assigned_date < "."'$req[created_to]'";
		if($req[username]!="" ){
			$nameSearch_criteria ="and T4.username like '%"."$req[username]"."%' or T4.first_name like '%"."$req[username]"."%' or T4.last_name like '%"."$req[username]"."%'";
		}
		if($req[userstatus]!="" ){
			$statusSearch_criteria ="and T1.status like '%"."$req[userstatus]"."%'";
		}
		if($req[userdob]!="" ){
			list($m,$d,$y) = explode("-",$req[userdob]);
			$req[userdob] = $y."-".$m."-".$d;
			$dobSearch_criteria ="and T4.dob = '"."$req[userdob]"."'";
		}
		if($date_criteria!=""){
			$sql_createdDate = "SELECT  medical_questionnaire_id  AS medQID_dateCriteria FROM assign where  $date_criteria AND status = 'Unassigned' order by id ASC"  ;
			$rs_createdDate = $this->db->get_results($sql_createdDate);
			if(count($rs_createdDate)>0){
			foreach($rs_createdDate as $valmed){
				$new_medQID[] = $valmed->medQID_dateCriteria;
			}
				$new_medQID = implode(",",$new_medQID);
			}else{
				$new_medQID = "0";
			}	
			$medical_quest_date_criteria = "AND T1.medical_questionnaire_id  IN ($new_medQID)";
		}
		
		
		
	   $sql = "SELECT T4.username AS CustomerName,T1.refill,T1.status,T1.medical_questionnaire_id,T1.id AS assignID,T4.* FROM assign AS T1 
				LEFT JOIN medical_questionnaire AS T3 ON T3.id = T1.medical_questionnaire_id LEFT JOIN member_master AS T4 ON T4.id = T3.member_id
				WHERE  T1.id  IN ($med_id) $medical_quest_date_criteria $nameSearch_criteria $statusSearch_criteria $dobSearch_criteria";
						
		$rs = $this->db->get_results_pagewise($sql, $pageNo, $limit, $params, $output, $orderBy);
		return $rs;
	}
	
	function medCreatedDate($medical_id)
	{
		$sql = "Select assigned_date  from assign Where medical_questionnaire_id  = $medical_id AND status = 'Unassigned' order by id ASC limit 1";
		$rs = $this->db->get_results($sql);
		return $rs;
	}
	
	 function customerReportDetails ($medical_id)
	{
	
		
		 $sql = "SELECT T1.*,T2.username AS doctor_name,T3.username AS pharmacy_name,T4.cud_tot,T4.tracking_no
		 		FROM assign AS T1 LEFT JOIN member_master AS T2 ON T2.id = T1.doctor_id 
				LEFT JOIN member_master AS T3 ON T3.id = T1.pharmacy_id 
				LEFT JOIN pharmacy_shipping AS T4 ON T4.questionnaire_id  =  T1.medical_questionnaire_id AND  T1.refill=T4.refill_no AND T1.pharmacy_id = T4.pharmacy_id
				WHERE T1.medical_questionnaire_id=$medical_id order by T1.id ASC";
		
		/*LEFT JOIN doctor_prescription AS T5 ON T5.questionnaire_id  =  T1.medical_questionnaire_id AND  T1.refill=T5.refill_no AND T1.doctor_id = T5.doctor_id
				*/
		$rs = $this->db->get_results($sql,ARRAY_A);
		if(count($rs>0)){
			foreach($rs as $key=>$val){
			if($val[status]=="Accepted"){
				$sql_doctPresc = "SELECT * FROM doctor_prescription  WHERE questionnaire_id = $medical_id AND refill_no=$val[refill] AND doctor_id = $val[doctor_id]";
				$rs_doctPresc = $this->db->get_results($sql_doctPresc,ARRAY_A);
				if(count($rs_doctPresc)>0){
					foreach($rs_doctPresc as $keyP=>$valP){
						$rs[$key][$keyP]['prescription'] = $valP[prescription];
						$rs[$key][$keyP]['medication'] = $valP[medication];
						$rs[$key][$keyP]['quantity'] = $valP[quantity];
						$rs[$key][$keyP]['sig'] = $valP[sig];
						$rs[$key][$keyP]['doct_refill'] = $valP[doct_refill];
						$rs[$key][$keyP]['generic'] = $valP[generic];
					}
				}
				
			}else{
				$rs[$key][$keyP]['prescription'] =  "";
				$rs[$key][0]['medication'] = "";
				$rs[$key][0]['quantity'] = "";
				$rs[$key][0]['sig'] = "";
				$rs[$key][0]['doct_refill'] = "";
				$rs[$key][0]['generic'] = "";
			}
			}
		}
		
		
		return $rs;
	}
	
	/**
    * To Upload file for Medical Questnaire
  	* Author   : Ratheesh
  	* Created  : 07/Jan/2008
  	*/
	
	function uploadMedicalFile (&$req,$pdf_file,$tmp_pdf_file) {
        extract($req);
		$dir			=	SITE_PATH."/modules/healthcare/uploaded_files/";
		if($pdf_file){
			$path_parts 	= 	pathinfo($pdf_file);
			$image_extension = substr($pdf_file, strrpos($pdf_file, '.') + 1);
			/*if($image_extension!="pdf") {
				$message 			=	"Only PDF file can be Uploaded";
				return $message;
				exit;
			}else{*/
				$arr_attach =array();
				$arr_attach = array('medical_questioner_id'=>$req['id'],'subject'=>$req['subject'],'image_extension'=>$image_extension);
				$this->db->insert("medical_attachment", $arr_attach);					
				$attached_id		= 	$this->db->insert_id;
				if($attached_id>0){
					$save_filename	=	$attached_id.".".$image_extension;
					_upload($dir,$save_filename,$tmp_pdf_file,0,0,0);
				}
				$pdf_file ="";
				unset($_POST);
			//}
		}
		return $message;
    }
	/**
    * To Get uploaded files for Medical Questnaire
  	* Author   : Ratheesh
  	* Created  : 07/Jan/2008
  	*/
	function getFileAttach($medical_id)
	{
		$sql = "SELECT * FROM medical_attachment WHERE medical_questioner_id = $medical_id";
		$rs = $this->db->get_results($sql);
		return $rs;
	}
	
	/**
    * To Remove uploaded files for Medical Questnaire
  	* Author   : Ratheesh
  	* Created  : 07/Jan/2008
  	*/
	function removeAttach($attach_id)
	{	
		$file_path			=	SITE_PATH."/modules/healthcare/uploaded_files/".$attach_id;
		$this->db->query("DELETE FROM medical_attachment WHERE id='$attach_id'");
		unlink($file_path);
		return true;
	}
	  function getDoctor_User_Detail($medical_id,$doct_id,$assign_id) {
    	
			$sql="SELECT T1.id AS medical_id ,T1.*,T2.*,T3.*,T2.id AS id,T4.address1 AS shipping_address1,T4.address2 AS shipping_address2,T4.state AS shipping_state,T4.city AS shipping_city,T4.postalcode AS shipping_postalcode,T4.telephone AS shipping_telephone,
				 T5.first_name AS doct_first_name,T5.last_name AS doct_last_name,
				 T7.address1 AS doct_home_address1,T7.address2 AS doct_home_address2,T7.state AS doct_home_state,T7.city AS doct_home_city,T7.postalcode AS doct_home_postalcode,T7.telephone AS doct_home_telephone
				 FROM medical_questionnaire T1,`member_master` T2 LEFT JOIN `member_address` T3 ON
			  T2.id=T3.user_id and T3.addr_type='master' LEFT JOIN member_address AS T4 ON T2.id=T4.user_id and T4.addr_type='shipping'
			  LEFT JOIN member_master AS T5 ON T5.id = $doct_id LEFT JOIN `member_address` T6 ON
			  T5.id=T6.user_id and T6.addr_type='master' LEFT JOIN member_address AS T7 ON T5.id=T7.user_id and T7.addr_type='master'
			  where T1.id = $medical_id AND T2.id = T1.member_id";
		$rs = $this->db->get_row($sql, ARRAY_A); 
		
		
		if($assign_id>0) $sql_extra_cond =" AND T2.id =$assign_id";
		
		$sql_doctPresc = "SELECT T1.*  FROM doctor_prescription T1,assign T2  WHERE T1.questionnaire_id  = $medical_id AND T2.refill=T1.refill_no AND T2.doctor_id  = T1.doctor_id  $sql_extra_cond";
		$rs_doctPresc = $this->db->get_results($sql_doctPresc,ARRAY_A);
		if(count($rs_doctPresc)>0){
			$rs['prescription'][0] = $rs_doctPresc[0]['prescription'];
			for($k=0;$k<count($rs_doctPresc);$k++){
				if($rs_doctPresc[$k+1]['medication']!=""){
					$rs['medication'][$k]   = $rs_doctPresc[$k]['medication'].",";
				}else{
					$rs['medication'][$k]   = $rs_doctPresc[$k]['medication'];
		    	}
				if($rs_doctPresc[$k+1]['quantity']!=""){
					$rs['quantity'][$k]     = $rs_doctPresc[$k]['quantity'].",";
				}else{
					$rs['quantity'][$k]     = $rs_doctPresc[$k]['quantity'];
				}
				if($rs_doctPresc[$k+1]['sig']!=""){
					$rs['sig'][$k]          = $rs_doctPresc[$k]['sig'].",";
				}else{
					$rs['sig'][$k]          = $rs_doctPresc[$k]['sig'];
				}
				if($rs_doctPresc[$k+1]['doct_refill']!=""){
					$rs['doct_refill'][$k]  = $rs_doctPresc[$k]['doct_refill'].",";
				}else{
					$rs['doct_refill'][$k]  = $rs_doctPresc[$k]['doct_refill'];
				}
			}
		}
		return $rs;
    }
    
    /**
    * To retrieve the Medical Questionnaire id status
  	* Author   : Jeffy
  	* Created  : 15/Jan/2008
  	*/
	function checkProfile ($medId) {
		$sql_medID = "SELECT  MAX( id ) AS medID FROM assign WHERE medical_questionnaire_id=".$medId;
		$rs_medID = $this->db->get_row($sql_medID, ARRAY_A); 
		$sql_medIdStat = "SELECT STATUS FROM assign WHERE id=".$rs_medID['medID'];
		$rs_medIdStat = $this->db->get_row($sql_medIdStat, ARRAY_A);
		return $rs_medIdStat['STATUS'];
	}
	
	/**
    * To Fetch the comments for a medical questionnaire
  	* Author   : Jeffy
  	* Created  : 18/Jan/2008
  	*/
	function fetchComments ($medId) {
		$sql = "SELECT t1.*, t2.username FROM media_comments t1, member_master t2 WHERE t1.file_id=$medId and t1.user_id=t2.id";
		$rs_comments = $this->db->get_results($sql);
		return $rs_comments;
	}
	
	/**
    * To insert comments for a medical questionnaire
  	* Author   : Jeffy
  	* Created  : 18/Jan/2008
  	*/
	function insertComments ($req) {
		$arr_comments = array('file_id'=>$req['id'],'user_id'=>$req['user_id'],'comment'=>$req['comment'],'postdate'=>$req['postdate'],'type'=>$req['type']);
		$this->db->insert("media_comments", $arr_comments);
		return true;
	}	
}

?>