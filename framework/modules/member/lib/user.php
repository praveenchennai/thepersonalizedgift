<?php 
/**
 * **********************************************************************************
 * @package    Member
 * @name       user.php
 * @version    1.0
 * @author     Retheesh Kumar
 * @copyright  2007 Newagesmb (http://www.newagesmb.com), All rights reserved.
 * Created on  21-Aug-2006
 * 
 * This script is a part of NewageSMB Framework. This Framework is not a free software.
 * Copying, Modifying or Distributing this software and its documentation (with or 
 * without modification, for any purpose, with or without fee or royality) is not
 * permitted.
 * 
 ***********************************************************************************/
if($_REQUEST['manage']=="manage")
{
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("PG","member_user") ;
	$permission_array	=	array(	'add'		=>	$objAdmin->GetmenuPermission($store_id,$fId,'add'),
	'edit'		=>	$objAdmin->GetmenuPermission($store_id,$fId,'edit'),
	'delete'	=>	$objAdmin->GetmenuPermission($store_id,$fId,'delete'));
	$framework->tpl->assign("STORE_PERMISSION",$permission_array) ;
	//authorize_store();
}
else
{
	authorize();
	$framework->tpl->assign("MOD","member") ;
	$framework->tpl->assign("PG","user") ;
	$permission_array	=	array(	'add'		=>	$objAdmin->GetmenuPermission(0,$fId,'add'),
	'edit'		=>	$objAdmin->GetmenuPermission(0,$fId,'edit'),
	'delete'	=>	$objAdmin->GetmenuPermission(0,$fId,'delete'));
	$framework->tpl->assign("STORE_PERMISSION",$permission_array) ;
}

include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/includes/payment/authorize.net/AuthorizeNet.php");
include_once(FRAMEWORK_PATH."/includes/payment/authorize.net/arb_auth.class.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.payment.php");
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");

$user = new User();
$authObj		= new AuthorizeNet();
$arbObj			= new ARBAuthnet();
$PaymentObj		= new Payment();
$objCategory	= new Category();
$email	 		= new Email();

switch($_REQUEST['act']) {
	case "invite":
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/member/tpl/user_list.tpl");
		break;
	case "referalldetails":
		if (!$_REQUEST["referer"])
		{
			list($rs, $numpad,$cnt_rs, $limitList) = $user->userList($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&mem_type=".$_REQUEST["mem_type"]."&fId=$fId&sId=$sId", OBJECT, $orderBy,"","referer","{$_REQUEST['referer']}");
		}
		else
		{
			list($rs, $numpad,$cnt_rs, $limitList) = $user->userList($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&mem_type=".$_REQUEST["mem_type"]."&fId=$fId&sId=$sId", OBJECT, $orderBy,"","referer","{$_REQUEST['referer']}");
		}
		$framework->tpl->assign("ACTIVE", "");
		$framework->tpl->assign("USER_LIST", $rs);
		$framework->tpl->assign("USER_NUMPAD", $numpad);
		$framework->tpl->assign("LIMIT_LIST",$limitList);
		$framework->tpl->assign("NO_LNAME",SHOW_FORMS);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/member/tpl/drawmember_list.tpl");
		break;
	case "referlist":
		if (!$_REQUEST["mem_type"])
		{

			list($rs, $numpad,$cnt_rs, $limitList) = $user->userGrList($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&mem_type=".$_REQUEST["mem_type"]."&fId=$fId&sId=$sId", OBJECT, $orderBy,$_REQUEST["txtsearch"],'mem_type,referer','1','=,!=');
		}
		else
		{

			list($rs, $numpad,$cnt_rs, $limitList) = $user->userGrList($_REQUEST['pageNo'],$_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&mem_type=".$_REQUEST["mem_type"]."&fId=$fId&sId=$sId", OBJECT, $orderBy,$_REQUEST["txtsearch"],'mem_type,referer','$mem_type');
		}
		$framework->tpl->assign("ACTIVE", "");
		$framework->tpl->assign("USER_LIST", $rs);
		$framework->tpl->assign("USER_NUMPAD", $numpad);
		$framework->tpl->assign("LIMIT_LIST",$limitList);
		$framework->tpl->assign("NO_LNAME",SHOW_FORMS);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/member/tpl/draw_list.tpl");
		break;
		
		
		
	case "favour_list":
	
	    $orderBy=$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "username";
		$param			=	"mod=$mod&pg=$pg&act=favour_list";
		list($rs, $numpad, $cnt, $limitList)	= 	$user->listfavourProducts($pageNo,$_REQUEST['limit'],$param,OBJECT, $orderBy);
		$framework->tpl->assign("SETTINGS_LIST", $rs);
		$framework->tpl->assign("SETTINGS_NUMPAD", $numpad);
		$framework->tpl->assign("SETTINGS_LIMIT", $limitList);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/member/tpl/favourlist.tpl");
		break;
		case "favour_detail":
		$user_id=$_REQUEST['user_id'];
		$rs	= 	$user->FavourProductDetail($user_id);
		foreach( $rs as $value)
		{
		$framework->tpl->assign("USER", $value['username']);
		}
		$framework->tpl->assign("SETTINGS_LIST", $rs);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/member/tpl/favour_detail.tpl");
		break;
	#### End...................................................................................................
		
		
		
	case "list":
		/**
	  	 * This is used for listing members
	  	 * Author   : Retheesh
	  	 * Created  : 14/Aug/2006
	  	 * Modified : 07/Nov/2007 By Retheesh
		 * Modified : 12/Nov/2007 By Jeffy (Assigned value for Member Type Drop down)
		
		 
	  	 */
		// print_r($_REQUEST);
		
		if($_POST['txtsearch'])
		{
	$_REQUEST['txtsearch']=$_POST['txtsearch'];
		}
		
	if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			$req	=	$_POST;
		
		redirect(makeLink(array("mod"=>"member", "pg"=>"user"), "act=list&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&pageNo={$_REQUEST['pageNo']}&mem_type={$_REQUEST['mem_type']}&txtsearch={$_POST['txtsearch']}&limit={$_REQUEST['limit']}"));
		exit;
			
			
		}
	
		 if($_REQUEST['manage']){
			authorize_store();
		}
		
		if($_REQUEST['action'] == 1)
		setMessage("User Details Updated Successfully",MSG_SUCCESS);
		 
		if ($store_id)
		{
			$chkVar = ",from_store";
			$chkVal = ",$store_id";
		}
		else
		{
			$chkVar = ",from_store";
			$chkVal = ",0";
		}
		
		if($_REQUEST['mem_type'] !='')
			$memtype=$_REQUEST['mem_type'];
		else
			$memtype=1;
	
		$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "joindate DESC";
		// for ARB subscription cancel
		if($global["inner_change_reg"]==""){
			$authDetails	=  $authObj->getAuthorizeNet(0, 'admin');
			$PaymentMethod	=	$PaymentObj->getActivePaymentGateway('0');
		}
		if($PaymentMethod === 'Authorize.Net' && $authDetails['auth_net_arb']=='Y')
		{
			$rs	=	""; $numpad	=	""; $cnt_rs	=	""; $limitList	=	"";
			$framework->tpl->assign("ARB", "Y");
			list($rs, $numpad,$cnt_rs, $limitList) = $arbObj->userList($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&mem_type=".$_REQUEST["mem_type"]."&fId=$fId&sId=$sId&txtsearch=".$_REQUEST['txtsearch']."", OBJECT, $orderBy,$_REQUEST["txtsearch"],"mem_type$chkVar","1$chkVal");
		}
		// end for ARB subscription cancel
		else{
			if ($framework->config['dynamic_member_list']=="Y")// For Dynamic user searching fields - added by Ratheesh on 01/Jan/2008
			{	
				$fields_search =$user->searchFields('member_master');
				if(count($fields_search)>0){
					for($inc =0 ; $inc<count($fields_search);$inc++){
						if($inc == (count($fields_search)-1)){
							$search_criteria = $search_criteria."m.".$fields_search[$inc][field_name]." like '%$_REQUEST[txtsearch]%'";
						}else{
				 			$search_criteria = $search_criteria."m.".$fields_search[$inc][field_name]." like '%$_REQUEST[txtsearch]%' OR ";
						}
					}
				}
			}
			if (!$_REQUEST["mem_type"])
			{
				if($global["inner_change_reg"]=="yes")
				{
					list($rs, $numpad,$cnt_rs, $limitList) = $user->userList($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&mem_type=".$_REQUEST["mem_type"]."&fId=$fId&sId=$sId&txtsearch=".$_REQUEST['txtsearch']."", OBJECT, $orderBy,$_REQUEST["txtsearch"],"mem_type","0","!=",$search_criteria);

				}
				else
				{
  
					list($rs, $numpad,$cnt_rs, $limitList) = $user->userList($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&mem_type=".$_REQUEST["mem_type"]."&fId=$fId&sId=$sId&txtsearch=".$_REQUEST['txtsearch']."", OBJECT, $orderBy,$_REQUEST["txtsearch"],"mem_type$chkVar","$memtype$chkVal",'',$search_criteria,'',$_REQUEST["mem_type"]);
				}
			}
			else
			{
			
				 $mem_type = $_REQUEST["mem_type"];
				
					
				if($global["inner_change_reg"]=="yes")
				{
					list($rs, $numpad,$cnt_rs, $limitList) = $user->userList($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&mem_type=".$_REQUEST["mem_type"]."&fId=$fId&sId=$sId&txtsearch=".$_REQUEST['txtsearch']."", OBJECT, $orderBy,$_REQUEST["txtsearch"],"mem_type","0","!=");

				}
				
				else
				{ 
					list($rs, $numpad,$cnt_rs, $limitList) = $user->userList($_REQUEST['pageNo'],$_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&mem_type=".$_REQUEST["mem_type"]."&fId=$fId&sId=$sId&txtsearch=".$_REQUEST['txtsearch']."", OBJECT, $orderBy,$_REQUEST["txtsearch"],"mem_type$chkVar",$mem_type.$chkVal,'','',$_REQUEST["mem_type"]);
				}
			}
		}
		
		if($framework->config["reg_mem_type"]=="N")
		{
		list($rs, $numpad,$cnt_rs, $limitList) = $user->userList($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&mem_type=".$_REQUEST["mem_type"]."&fId=$fId&sId=$sId&txtsearch=".$_REQUEST['txtsearch']."", OBJECT, $orderBy,$_REQUEST["txtsearch"],"mem_type","0","!=");

		}
		if ($framework->config['dynamic_member_list']=="Y")// For Dynamic user fields - added by Retheesh on 31/Dec/2007
		{
			$fields_cs =$user->fetchFields("list_order",'member_master');
			for ($i=0;$i<sizeof($rs);$i++)
			{
				for ($j=0;$j<sizeof($fields_cs);$j++)
				{
					$val = $fields_cs[$j]->field_name;
					$rs1[$i][$j] =  $rs[$i]->{$val};
				}
			}
			for($i=0;$i<sizeof($rs);$i++){
				for($j=0;$j<sizeof($fields_cs);$j++){
					 if($fields_cs[$j]->showInList=="Y" && $fields_cs[$j]->is_date=='Y'){
					 	//if($rs1[$i][$j]!="0000-00-00" || $rs1[$i][$j]!=" "){
							$dt=explode('-',$rs1[$i][$j]);
							//if($dt[0]<1902){
								if($dt[1]=="01")
									$dt[1]="January";
								elseif($dt[1]=="02")
									$dt[1]="February";
								elseif($dt[1]=="03")
									$dt[1]="March";
								elseif($dt[1]=="04")
									$dt[1]="April";
								elseif($dt[1]=="05")
									$dt[1]="May";
								elseif($dt[1]=="06")
									$dt[1]="June";
								elseif($dt[1]=="07")
									$dt[1]="July";
								elseif($dt[1]=="08")
									$dt[1]="August";
								elseif($dt[1]=="09")
									$dt[1]="September";
								elseif($dt[1]=="10")
									$dt[1]="October";
								elseif($dt[1]=="11")
									$dt[1]="November";
								elseif($dt[1]=="12")
									$dt[1]="December";
									
								$st=$dt[1]."-".$dt[2]."-".$dt[0];
							//}else{
								//$str=strtotime($rs1[$i][$j]);
								//$st=date($global['date_format'],$str);
							//}
							$rs1[$i][$j]=$st;
						//}
					 }
				}
			}//exit;
			$framework->tpl->assign("FIELDS_VALS",$rs1);
			$framework->tpl->assign("FIELDS_CS",$fields_cs);
		}
	
		if($global["member_type"]){
			$mem_type = $user->loadMemTypeCmb();
			array_shift($mem_type[id]);
			array_shift($mem_type[type]);
			$framework->tpl->assign("SECTION_LIST", $mem_type);
		}

		if($global["inner_change_reg"]=="yes")
		{
			$framework->tpl->assign("SCREENAME", "1");
		}
		$framework->tpl->assign("ACTIVE", "");
		
		if($_REQUEST['mem_type'] == 2){
			$rs= $user->getSubsStatus($rs);
		}
		
		
		//print_r($rs);exit;
		$framework->tpl->assign("USER_LIST", $rs);
		
		$framework->tpl->assign("USER_NUMPAD", $numpad);
		$framework->tpl->assign("LIMIT_LIST",$limitList);
		$framework->tpl->assign("NO_LNAME",SHOW_FORMS);
		if($global['health_care']=='1'){
			$framework->tpl->assign("HEALTH_CARE",$global['health_care']);
		}
		//For hiding some columns in project::clas product.
		if($global["redirect_to_search"]=="Y"){
			$framework->tpl->assign("CLAS_PRODUCT",$global['redirect_to_search']);
		}
		if ($framework->config['dynamic_member_list']=="Y") // For Dynamic user fields - added by Retheesh on 31/Dec/2007
		{
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/member/tpl/user_dynamic_list.tpl");
		}
		else 
		{  
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/member/tpl/user_list.tpl");
		}	
		break;
	case "view":
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			$req	=	$_POST;
			
			if($_POST['account_name'] != ""){
				$user->updateCustomeFieldValue($req,'account_name','field_2','1');
			}
			$user->adminUpgradeRegPack($req);
			$user->adminUpgradeSubPack($req);
			$user->adminUpgradeSubPack2($req);
			
			if($_POST['stop_subs'])
			$stop_subs='Y';
			else
			$stop_subs='N';
			$memarray=array('stop_subs'=>$stop_subs,'id'=>$_POST['id']);
			$user->setArrData($memarray);
			$user->update();
			
			// For insert into artist group
			
			if($global['artist_selection']=='Yes')
			{
				if($_POST["artist_id"]!="1"){
					$_POST["artist_id"]=0;
				}
				$user->setArrData($_POST);
				$mynew=$user->update();
			}
			redirect(makeLink(array("mod"=>"member", "pg"=>"user"), "act=list&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&pageNo={$_REQUEST['pageNo']}&mem_type={$_REQUEST['mem_type']}&txtsearch={$_REQUEST['txtsearch']}"));
		}
		$rs = $user->getUserDetails($_REQUEST["id"]);
		$framework->tpl->assign("USER",$rs);
		$framework->tpl->assign("ARTIST_GROUP",$global['artist_selection']);
		$framework->tpl->assign("DOMAIN_URL",DOMAIN_URL);
		$framework->tpl->assign("PROJECT_NAME",PROJECT);
		$framework->tpl->assign("NO_DOB",SHOW_FORMS);
		$framework->tpl->assign("MOD_HEALTH",$global['health_care']);
		if(SHOW_FORMS=="Y") {
			$subScrId	=	$user->getARBSubscriptionID($_REQUEST["id"]);
			$framework->tpl->assign("ARB_ID",$subScrId);
		}
		if ($global["redirect_to_search"]=="Y"){
				$framework->tpl->assign("NO_DOB",N);
			}
		$curYear	=	date("Y");
		$years	=	array($curYear,$curYear+1,$curYear+2,$curYear+3,$curYear+4,$curYear+5,$curYear+6,$curYear+7,$curYear+8,$curYear+9,$curYear+10);
		$days	=	array('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');
		$framework->tpl->assign("YEARS",$years);
		$framework->tpl->assign("DAYS",$days);
		$framework->tpl->assign("REGPACKG",$user->getRegpacks());
		
		if ($rs["sub_pack"]>0)
		{
		
			$sub=$user->getSubscrDetails($rs["sub_pack"]);
			
			if ($sub['type']=='Y')
			{
				($sub['duration'] >1)? $type = " Years" : $type = " Year";				
			}
			elseif ($sub['type']=='M')
			{
				($sub['duration'] >1)? $type = " Months" : $type = " Month";
			}
			elseif ($sub['type']=='D')
			{
				($sub['duration'] >1)? $type = " Days" : $type = " Day";
			}
			if ($sub['fees']==0)
				$sub['fees']="Free";
			else{
					$sub['fees'] = round($sub['fees'], 1);
					$sub['fees']="$".$sub['fees'];
			}
			$framework->tpl->assign("SUBS_PACK_SEL",$sub['name'].'&nbsp;('.$sub['duration'].$type.')&nbsp;('.$sub['fees'].')');
		}	
		
		if ($rs["reg_pack"]>0)
		{
			$pck = $user->getPackageDetails($rs["reg_pack"]);
			$framework->tpl->assign("PCK",$pck);
		}
		$subpackage = $user->loadSubscriptions($rs["reg_pack"]);		
		$framework->tpl->assign("SUB_PACKAGE",$subpackage);
		
		$framework->tpl->assign("SUB_PACK",$rs["sub_pack"]);
		
		
		
		if ($rs["sub_pack"]>0)
		{
			$sb = $user->getSubscriptionDays($_REQUEST["id"]);
			
			if ($sb)
			{
				$subenddate = date("Y-m-d",strtotime($sb["enddate"]));
				list($y,$m,$d)	=	split("-",$subenddate);
				$framework->tpl->assign("SUB_EYEAR",$y);
				$framework->tpl->assign("SUB_EMONTH",$m);
				$framework->tpl->assign("SUB_EDATE",$d);

				if ($sb["diff"]<0)
				{
					$enddate = date("M d, Y",strtotime($sb["enddate"]));
					$sub_status = "Expired on ". $enddate;
				}
				elseif ($sb["diff"]==0)
				{
					$enddate = date("M d, Y",strtotime($sb["enddate"]));
					$sub_status = "Expires Today (". $enddate.")";
				}
				elseif ($sb["diff"]>0)
				{
					$enddate = date("M d, Y",strtotime($sb["enddate"]));
					$sub_status = "Expires On ". $enddate;
				}
			}
			else
			{
				$sub_status="None";
			}
			$framework->tpl->assign("SUB_STATUS",$sub_status);

		}
		if($global['special_discount_field'] == "Y"){
			$framework->tpl->assign("SPECIAL_DISCOUNT","Y");
		}
		$Subscription_Startdate = $user->getUserSubscription($_REQUEST['id']);
		
		$framework->tpl->assign("START_DATE",$Subscription_Startdate[0]['startdate']);
		
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/member/tpl/user_form.tpl");
		break;
	case "drawuser_delete":	
	$user->drawuserDelete();
	break;	
		
	case "user_delete":
		/*modified by vipin on 14-07-2007*/
		extract($_POST);
		if(count($user_id)>0)
		{
			$message=true;
			foreach ($user_id as $id)
			{
				if($user->userDelete($id)==false)
				$message=false;
			}
		}
		if($message==true)
		setMessage($_REQUEST["sId"]."(s) Deleted Successfully!", MSG_SUCCESS);
		if($message==false)
		setMessage($_REQUEST["sId"]."(s) Can not Deleted!");
		if ($_REQUEST['sId'] == 'Suppliers'){
			redirect(makeLink(array("mod"=>"member", "pg"=>"user"), "act=supplier_list&sId={$_REQUEST['sId']}&pageNo={$_REQUEST['pageNo']}&fId={$_REQUEST['fId']}&mem_type={$_REQUEST['mem_type']}&txtsearch={$_REQUEST['txtsearch']}&limit={$_REQUEST['limit']}"));
		}else{
			redirect(makeLink(array("mod"=>"member", "pg"=>"user"), "act=list&sId={$_REQUEST['sId']}&pageNo={$_REQUEST['pageNo']}&fId={$_REQUEST['fId']}&mem_type={$_REQUEST['mem_type']}&txtsearch={$_REQUEST['txtsearch']}&limit={$_REQUEST['limit']}"));
		}
		break;
	case "user_modify":
		if ($_REQUEST["id"])
		{
			$udet = $user->getUserdetails($_REQUEST["id"]);
			
			$framework->tpl->assign("COUNTRY_LIST", $user->listCountry());
			$framework->tpl->assign("NO_DOB",SHOW_FORMS);
			$framework->tpl->assign("MEM_TYPE",$udet[mem_type]);
			if($global['health_care'] == "1"){
				$udet = $user->getUsersAlldetails($_REQUEST["id"]);
				$framework->tpl->assign("HEALTH_CARE",1);
				$framework->tpl->assign("STATE_LIST",$user->listdropdown('6'));
			}
			if($global['special_discount_field'] == "Y"){
				$framework->tpl->assign("SPECIAL_DISCOUNT","Y");
			}
			if ($global["redirect_to_search"]=="Y"){
				$framework->tpl->assign("NO_DOB",Y);
			}
			if ($udet)
			{
				$_REQUEST+= $udet;
			}
			if ($_SERVER['REQUEST_METHOD']=="POST"){
				// print_r($_REQUEST);
				// exit;
				$proceed=1;
				if($_POST["email"])
					{
						if($user->checkEmail($_POST['email'],$_POST['user_id']))
						{
							$proceed = 0;
							setMessage("Email name already Exists");
							
						}
				}
				if($proceed==1){
				
				
					$_POST["id"] = $_REQUEST["id"];
					$_POST["addr_type"] = $_REQUEST["addr_type"];
					//print_r($_POST);exit;
					$user->setArrData($_POST);
					$user->update();
					if($_REQUEST['manage'] == 'manage'){
						redirect(makeLink(array("mod"=>"store", "pg"=>"member_user"), "act=list&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&mem_type={$_REQUEST['mem_type']}&action=1&txtsearch={$_REQUEST['txtsearch']}&limit={$_REQUEST['limit']}&pageNo={$_REQUEST['pageNo']}"));
					}else{					
						redirect(makeLink(array("mod"=>"member", "pg"=>"user"), "act=list&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&mem_type={$_REQUEST['mem_type']}&action=1&txtsearch={$_REQUEST['txtsearch']}&limit={$_REQUEST['limit']}&pageNo={$_REQUEST['pageNo']}"));
					}
				}
			}
		}
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/member/tpl/user_entry.tpl");
		break;
	case "active":
		$user->changeActive($_REQUEST["stat"],$_REQUEST['id']);

		if(SHOW_FORMS=="Y")
		{
			$user->changeFlyerStatus($_REQUEST["stat"],$_REQUEST['id']);// activating and deactivating the flyers created by the user
		}

		if ($_REQUEST["done"]=="supplier")
		{
			redirect(makeLink(array("mod"=>"member", "pg"=>"user"), "act=supplier_list&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&pageNo={$_REQUEST['pageNo']}&limit={$_REQUEST['limit']}"));
		}
		else
		{
			redirect(makeLink(array("mod"=>"member", "pg"=>"user"), "act=list&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}&mem_type={$_REQUEST['mem_type']}&txtsearch={$_REQUEST['txtsearch']}&pageNo={$_REQUEST['pageNo']}&limit={$_REQUEST['limit']}"));
		}
		break;
	case "delete":
		$user->userDelete($_REQUEST['id']);
		redirect(makeLink(array("mod"=>"member", "pg"=>"user"), "act=list"));
		break;
	case "arb_delete":
		$user_id 		= 	$_REQUEST["id"] ? $_REQUEST["id"] : "0";
		$arb_sub_id 	= 	$_REQUEST["sub_id"] ? $_REQUEST["sub_id"] : "0";
		$authDetails	=  $authObj->getAuthorizeNet(0, 'admin');
		$arbObj->cancelSubscription($authDetails['auth_net_login_id'],$authDetails['auth_net_tran_key'],$user_id,$arb_sub_id);
		redirect(makeLink(array("mod"=>"member", "pg"=>"user"), "act=list&sId={$_REQUEST['sId']}&pageNo={$_REQUEST['pageNo']}&fId={$_REQUEST['fId']}&mem_type={$_REQUEST['mem_type']}"));
		break;
	case "session":
		list($rs,$numpad,$lm,$sess_limit) = $user->mainSessList($_REQUEST['pageNo'], $_REQUEST["limit"], "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy'],$_REQUEST["txtsearch"]);
		$framework->tpl->assign("SESS_LIST",$rs);
		$framework->tpl->assign("SESS_NUMPAD",$numpad);
		$framework->tpl->assign("SESS_LIMIT",$sess_limit);
		$framework->tpl->assign("LIMIT_LIST",$sess_limit);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/member/tpl/sess_main.tpl");
		break;
	case "session_det":
		if($_REQUEST['orderBy']=="")
		{
			$_REQUEST['orderBy'] = "id desc";
		}
		list($rs,$numpad,$cnt_rs, $limitList) = $user->sessionList($_REQUEST['pageNo'], $_REQUEST["limit"], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&username=".$_REQUEST["username"]."&user_id=".$_REQUEST["user_id"], OBJECT, $_REQUEST['orderBy'],$_REQUEST["user_id"]);
		$framework->tpl->assign("SESS_LIST",$rs);
		$framework->tpl->assign("SESS_NUMPAD",$numpad);
		$framework->tpl->assign("LIMIT_LIST",$limitList);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/member/tpl/sess_list.tpl");
		break;
	case "pack_list":
		list($rs,$numpad,$cnt_rs, $limitList) = $user->packageList($_REQUEST['pageNo'], $_REQUEST["limit"], "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
		$framework->tpl->assign("PACK_LIST",$rs);
		$framework->tpl->assign("PACK_NUMPAD",$numpad);
		$framework->tpl->assign("LIMIT_LIST",$limitList);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/member/tpl/package_list.tpl");
		break;
	case "pack_form":
	
		include_once(SITE_PATH."/includes/areaedit/include.php");
		editorInit('content_f','content_s');
		
		if ($_REQUEST['id'])
		{
			$pck_det = $user->getPackageDetails($_REQUEST['id']);
			if($pck_det!="")
			{
				$_REQUEST=$_REQUEST+$pck_det;
			}
			$type_list= $user->loadMemberTypePackage($_REQUEST['id'],"0,3");
		}
		else
		{
			$type_list = $user->loadMemTypeCmb("0,3",0);
		}
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{

			$m_type = $_POST['mem_type'];
			($_POST['active']=='Y')? $_POST['active']='Y':$_POST['active']='N';
			($_POST['enable_one']=='Y')? $_POST['enable_one']='Y':$_POST['enable_one']='N';
			($_POST['enable_two']=='Y')? $_POST['enable_two']='Y':$_POST['enable_two']='N';
			for($i=0;$i<sizeof($type_list);$i++)
			{
				for($j=0;$j<sizeof($m_type);$j++)
				{
					if ($m_type[$j]==$type_list[$i]->id)
					{
						$type_list[$i]->chk=1;
						break;
					}
					else
					{
						$type_list[$i]->chk='';

					}
				}
			}

			if($_REQUEST['id'])
			{
				$_POST['id'] = $_REQUEST['id'];
				$user->setArrData($_POST);
				if ($user->insertRegPackage(1))
				{
					setMessage("Package details updated successfully",MSG_INFO);
					redirect(makeLink(array("mod"=>"member", "pg"=>"user"), "act=pack_list&test=$test"));
				}
				else
				{
					setMessage($user->getErr());
				}
			}
			else
			{
				$user->setArrData($_POST);
				
				
				if ($user->insertRegPackage())
				{
					setMessage("Package details saved successfully",MSG_INFO);
					redirect(makeLink(array("mod"=>"member", "pg"=>"user"), "act=pack_list"));
				}
				else
				{
					setMessage($user->getErr());
				}
			}

		}

		$framework->tpl->assign("TYPE_LIST",$type_list);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/member/tpl/package_form.tpl");
		break;
	case "restriction_name_list";
	//list($rs,$numpad,$cnt_rs, $limitList) = $user->restrictionList($_REQUEST['pageNo'], $_REQUEST["limit"], "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
	$framework->tpl->assign("RESTRICTION_LIST",$rs);

	$framework->tpl->assign("PACK_NUMPAD",$numpad);
	$framework->tpl->assign("LIMIT_LIST",$limitList);
	$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/member/tpl/restriction_name_list.tpl");
	break;
	case "restriction_name_form":
		if ($_REQUEST['id'])
		{
			$pck_det = $user->getrestrictionDetails($_REQUEST['id']);
			if($pck_det!="")
			{
				$_REQUEST=$_REQUEST+$pck_det;
			}
			$type_list= $user->loadMemberTypePackage($_REQUEST['id'],"0,3");
		}
		else
		{
			$type_list = $user->loadMemTypeCmb("0,3",0);
			$upload_type_list = $user->uploadTypeList();
			$reg_pack_list = $user->regPackList();
		}
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			$m_type = $_POST['mem_type'];
			($_POST['active']=='Y')? $_POST['active']='Y':$_POST['active']='N';

			for($i=0;$i<sizeof($type_list);$i++)
			{
				for($j=0;$j<sizeof($m_type);$j++)
				{
					if ($m_type[$j]==$type_list[$i]->id)
					{
						$type_list[$i]->chk=1;
						break;
					}
					else
					{
						$type_list[$i]->chk='';

					}
				}
			}

			if($_REQUEST['id'])
			{
				$_POST['id'] = $_REQUEST['id'];
				$user->setArrData($_POST);
				if ($user->insertRegPackage(1))
				{
					setMessage("Package details updated successfully",MSG_INFO);
					redirect(makeLink(array("mod"=>"member", "pg"=>"user"), "act=pack_list&test=$test"));
				}
				else
				{
					setMessage($user->getErr());
				}
			}
			else
			{
				$user->setArrData($_POST);
				if ($user->insertRestrictionName())
				{

					setMessage("Restriction Name details saved successfully",MSG_INFO);
					redirect(makeLink(array("mod"=>"member", "pg"=>"user"), "act=restriction_name_list"));
				}
				else
				{
					setMessage($user->getErr());
				}
			}

		}

		$framework->tpl->assign("REG_PACK_LIST",$reg_pack_list);
		$framework->tpl->assign("UPLOAD_TYPE_LIST",$upload_type_list);
		$framework->tpl->assign("TYPE_LIST",$type_list);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/member/tpl/restriction_name_form.tpl");
		break;
	case "sub_list":
		list($rs,$numpad,$cnt_rs, $limitList) = $user->subscriptionList($_REQUEST['pageNo'],$_REQUEST["limit"], "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy'],$_REQUEST["user_id"]);
		$framework->tpl->assign("SUB_LIST",$rs);
		$framework->tpl->assign("SUB_NUMPAD",$numpad);
		$framework->tpl->assign("LIMIT_LIST",$limitList);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/member/tpl/subscr_list.tpl");
		break;
	case "sub_form":
		
		if ($_REQUEST['id'])
		{
			$sub_det = $user->getSubscrDetails($_REQUEST['id']);
			if($sub_det!="")
			{
				$_REQUEST=$_REQUEST+$sub_det;
				
			}
			$reg_list= $user->loadSubscr_package($_REQUEST['id']);

		}
		else
		{
			$reg_list = $user->loadSubscr_package();
		}
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			$r_type = $_POST['reg_type'];
			($_POST['active']=='Y')? $_POST['active']='Y':$_POST['active']='N';

			for($i=0;$i<sizeof($reg_list);$i++)
			{
				for($j=0;$j<sizeof($r_type);$j++)
				{
					if ($r_type[$j]==$reg_list[$i]->id)
					{
						$reg_list[$i]->chk=1;
						break;
					}
					else
					{
						$reg_list[$i]->chk='';

					}

				}
			}

			if($_REQUEST['id'])
			{
				$_POST['id'] = $_REQUEST['id'];
				
				$user->setArrData($_POST);
				
				if ($user->insertSubscrPackage(1))
				{
                    
					setMessage("Subscription details updated successfully",MSG_INFO);
					redirect(makeLink(array("mod"=>"member", "pg"=>"user"), "act=sub_list"));
				}
				else
				{
					setMessage($user->getErr());
				}
			}
			else
			{
				$user->setArrData($_POST);
				
				if ($user->insertSubscrPackage())
				{


					setMessage("Subscription details saved successfully",MSG_INFO);
					redirect(makeLink(array("mod"=>"member", "pg"=>"user"), "act=sub_list"));
				}
				else
				{
					setMessage($user->getErr());
				}
			}

		}
		$framework->tpl->assign("REG_LIST",$reg_list);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/member/tpl/subscr_form.tpl");
		break;
	case "reports":// Reports on user details
	$report_key	=	$_REQUEST["report_key"]	? $_REQUEST["report_key"]: "0";
	if (!$_REQUEST["mem_type"])
	{
		list($rs, $numpad,$cnt_rs, $limitList) = $user->userDetailsforReports($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=member&pg=user&act=".$_REQUEST['act']."&report_key=$report_key&mem_type=".$_REQUEST["mem_type"]."&fId=$fId&sId=$sId", OBJECT, $_REQUEST['orderBy'],$_REQUEST["txtsearch"],'mem_type','1',$report_key);
	}
	else
	{
		$mem_type = $_REQUEST["mem_type"];
		list($rs, $numpad,$cnt_rs, $limitList) = $user->userDetailsforReports($_REQUEST['pageNo'],$_REQUEST['limit'], "mod=member&pg=user&act=".$_REQUEST['act']."&mem_type=".$_REQUEST["mem_type"]."&report_key=$report_key&fId=$fId&sId=$sId", OBJECT, $_REQUEST['orderBy'],$_REQUEST["txtsearch"],'mem_type',$mem_type,$report_key);
	}

	$framework->tpl->assign("ACTIVE", "");
	$framework->tpl->assign("REPORT_KEYS",$user->getReportKey());
	$framework->tpl->assign("REQID", $report_key);
	$framework->tpl->assign("USER_LIST", $rs);
	$framework->tpl->assign("USER_NUMPAD", $numpad);
	$framework->tpl->assign("LIMIT_LIST",$limitList);
	$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/member/tpl/user_reports.tpl");
	break;

	case "mem_price_list":
		list($rs,$numpad,$cnt,$limitList) = $user->loadMemTypeList($_REQUEST['pageNo'],$_REQUEST["limit"], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&fId=$fId&sId=$sId", OBJECT, $_REQUEST['orderBy'],"0,3");

		$framework->tpl->assign("TYPE_LIST",$rs);
		$framework->tpl->assign("TYPE_NUM",$numpad);
		$framework->tpl->assign("LIMIT_LIST",$limitList);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/member/tpl/memtype_price_list.tpl");
		break;
	case "mem_price":
		if ($_REQUEST["id"])
		{
			$mem_type = $user->getMemTypeDetails($_REQUEST["id"]);
			if ($mem_type!="")
			{
				if ($mem_type["percentage"]==100)
				{
					$mem_type["p_type"] = "";
					$mem_type["percentage"] = "";
				}
				elseif ($mem_type["percentage"]>100)
				{
					$mem_type["p_type"] = "More";
					$mem_type["percentage"] = $mem_type["percentage"] - 100;
				}
				else
				{
					$mem_type["p_type"] = "Less";
					$mem_type["percentage"] = 100 - $mem_type["percentage"];
				}

				$_REQUEST+=$mem_type;
			}
			if ($_SERVER['REQUEST_METHOD']=="POST")
			{
				if (($_POST["percentage"]>0))
				{
					if ($_POST["p_type"]=="More")
					{
						$percentage = 100 + $_POST["percentage"];
					}
					else
					{
						$percentage = 100 - $_POST["percentage"];
					}
					$arr = array();
					$arr["id"] = $_REQUEST["id"];
					$arr["percentage"] =  $percentage;
					$user->setArrData($arr);
					$user->updateMemType();

					//setMessage("Details Updated Suceessfully");
				}
				else
				{
					$arr = array();
					$arr["id"] = $_REQUEST["id"];
					$arr["percentage"] =  100;
					$user->setArrData($arr);
					$user->updateMemType();
				}
				redirect(makeLink(array("mod"=>"member", "pg"=>"user"), "act=mem_price_list&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}"));
			}
		}
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/member/tpl/memtype_price.tpl");
		break;
	case "supplier_list":
		if($global['artist_selection']=='Yes')
		{
			$mem_type = 5;
		}
		else
		{
			$mem_type = 3;
		}
		list($rs, $numpad,$cnt_rs, $limitList) = $user->userList($_REQUEST['pageNo'], $_REQUEST["limit"], "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy'],$_REQUEST["txtsearch"],'mem_type',$mem_type);
		$framework->tpl->assign("USER_LIST", $rs);
		$framework->tpl->assign("USER_NUMPAD", $numpad);
		$framework->tpl->assign("LIMIT_LIST",$limitList);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/member/tpl/supplier_list.tpl");
		break;
	case "supplier_view":
		if ($_REQUEST["id"])
		{
			$udet= $user->getUserdetails($_REQUEST["id"]);
			if ($udet)
			{
				$_REQUEST += $udet;
			}
			if ($_SERVER['REQUEST_METHOD']=="POST")
			{
				unset($_POST["rep_pass"]);
				$_POST["id"] =  $_REQUEST["id"];
				$user->setArrData($_POST);
				$user->update();
				redirect(makeLink(array("mod"=>"member", "pg"=>"user"), "act=supplier_list&sId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}"));
			}
		}
		else
		{
			if ($_SERVER['REQUEST_METHOD']=="POST")
			{
				unset($_POST["rep_pass"]);
				$_POST["mem_type"] = 3;
				$_POST["Active"]   = "Y";
				$user->setArrData($_POST);
				if ($id = $user->insert())
				{
					$user->addSupplierModules($id);
					redirect(makeLink(array("mod"=>"member", "pg"=>"user"), "act=supplier_listsId={$_REQUEST['sId']}&fId={$_REQUEST['fId']}"));
				}
				else
				{
					setMessage($user->getErr());
				}
			}
		}
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/member/tpl/supplier_form.tpl");
		break;
	case "res_list":
		list($rs,$numpad,$cnt_rs, $limitList) = $user->restrictionList($_REQUEST['pageNo'],$_REQUEST["limit"], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"], OBJECT, $_REQUEST['orderBy']);
		$framework->tpl->assign("SUB_LIST",$rs);
		$framework->tpl->assign("SUB_NUMPAD",$numpad);
		$framework->tpl->assign("LIMIT_LIST",$limitList);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/member/tpl/restrictions_list.tpl");
		break;
	case "type_res_list":
		list($rs,$numpad,$cnt_rs, $limitList) = $user->typeRestrictionList($_REQUEST['pageNo'],$_REQUEST["limit"], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&mem_type=".$_REQUEST["mem_type"]."&type_name=".$_REQUEST["type_name"], OBJECT, $_REQUEST['orderBy'],$_REQUEST["mem_type"]);
		$framework->tpl->assign("SUB_LIST",$rs);
		$framework->tpl->assign("SUB_NUMPAD",$numpad);
		$framework->tpl->assign("LIMIT_LIST",$limitList);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/member/tpl/type_restrictions_list.tpl");
		break;
	case "res_form":
		if ($_REQUEST["id"])
		{
			$res_det = $user->getRestrictionDetails($_REQUEST["id"]);
			if ($res_det)
			{
				$_REQUEST += $res_det;
			}
		}
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			$_POST["link_id"] = $_REQUEST["link_id"];
			$user->setArrData($_POST);
			$id = $_REQUEST["id"];
			if ($user->addEditRestriction($id))
			{
				redirect(makeLink(array("mod"=>"member", "pg"=>"user"), "act=type_res_list&mem_type=".$_REQUEST["link_id"]));
			}
			else
			{
				setMessage($user->getErr());
			}
		}
		$heading_lbl = "Manage Restrictions ->". $_REQUEST["type_name"];
		$framework->tpl->assign("HEADING",$heading_lbl);
		$rs = $user->getRestrictionSection($link_id);
		$framework->tpl->assign("SECTION_LIST",$rs);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/member/tpl/restriction_form.tpl");
		break;
	case "res_new":
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			$user->setArrData($_POST);
		}
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/member/tpl/restriction_form.tpl");
		break;

	case "sub_delete":
		$user->subscriptionDelete($_REQUEST['id']);
		redirect(makeLink(array("mod"=>"member", "pg"=>"user"), "act=sub_list"));
		break;

	case "pack_delete":
		if ($user->checksubpack($_REQUEST['id']))
		{
			setMessage("Subscription package exists for this Registration package",MSG_INFO);
		}
		else
		{
			$user->packageDelete($_REQUEST['id']);
		}
		redirect(makeLink(array("mod"=>"member", "pg"=>"user"), "act=pack_list"));
		break;
	case "create":
		$framework->tpl->assign("CAT_LIST", $user->getCategories());
		$framework->tpl->assign("CAT_ARR", $user->listCategories());
		if ($_SERVER['REQUEST_METHOD']=="POST")
		{
			///checkLogin();
			$_POST['user_id']=$getId;
			unset($_POST["x"],$_POST["y"]);
			$image=$_FILES["image"]["name"];
			if($image)
			{
				$_POST["image"]="Y";
			}
			$_POST["createdate"]=date("Y-m-d G:i:s");
			$user->setArrData($_POST);
			if ($user->createGroup())
			{
				$framework->tpl->assign("MESSAGE","Discussion group added successfully.");

			}
			else
			{
				$framework->tpl->assign("MESSAGE", $user->getErr());
			}
		}
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/member/tpl/group_create.tpl");
		break;
	case "listgroups":
		$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act'];
		if ($_REQUEST["crt"])
		{
			$par = $par."&crt=".$_REQUEST['crt'];
		}
		if ($_REQUEST["cat_id"])
		{
			$par = $par."&cat_id=".$_REQUEST['cat_id'];
		}
		if($_POST["txtSearch"])
		{
			$stxt=$_POST["txtSearch"];
			$_REQUEST["pageNo"]=1;
			$framework->tpl->assign("STXT",$stxt);

		}
		else
		{	if(!$_REQUEST["stxt"])
		{
			$stxt=0;
		}
		else
		{
			$stxt=$_REQUEST["stxt"];
			$framework->tpl->assign("STXT",$stxt);
		}
		}
		$par = $par."&stxt=".$stxt;
		if ($_REQUEST["cat_id"])
		{
			$catname=$objUser->getCatName($_REQUEST["cat_id"]);
			$framework->tpl->assign("CRT",$_REQUEST["cat_id"]);
			$framework->tpl->assign("GRP_HEADER", $catname["cat_name"]);
			list($rs, $numpad) = $user->groupList($_REQUEST['pageNo'], 5,$par, OBJECT, $_REQUEST['orderBy'],$_REQUEST["cat_id"],0,0,0,$stxt);
		}
		elseif ($_REQUEST["crt"])
		{
			if ($_REQUEST["crt"]=="M1")
			{
				$sort="createdate desc";
				$gheader="Recently Added";
			}
			elseif ($_REQUEST["crt"]=="M2")
			{
				$sort="members desc";
				$gheader="Most Members";
			}
			elseif ($_REQUEST["crt"]=="M3")
			{
				$sort="discussion desc";
				$gheader="Most Discussed";
			}
			$framework->tpl->assign("CRT",$_REQUEST["crt"]);
			$framework->tpl->assign("GRP_HEADER", $gheader);
			list($rs, $numpad) = $user->groupList($_REQUEST['pageNo'], 5, $par, OBJECT, $sort,0,0,0,0,$stxt);
		}
		else
		{

			$framework->tpl->assign("GRP_HEADER", "All Groups");
			list($rs, $numpad) = $user->groupList($_REQUEST['pageNo'], 5, $par, OBJECT, $_REQUEST['orderBy'],0,0,0,0,$stxt);
		}

		if($global["show_property"] == 1) // Afsal created on 17-09-2007 fore realestatetube
		$framework->tpl->assign("group_left", FRAMEWORK_PATH."/modules/member/tpl/group_left.tpl");

		$framework->tpl->assign("GROUP_LIST", $rs);
		$framework->tpl->assign("GROUP_NUMPAD", $numpad);

		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/member/tpl/group_list.tpl");
		break;
	case "nom_list":
		$framework->tpl->assign("TITLE_HEAD","Weekly Nomination List");
		$week_id   = date("Wy");
		$iWeekNum  = date("W");
		$iYear     = date("Y");
		$sStartTS = WeekToDate ($iWeekNum, $iYear);
		$sStartDate = date ("F d, Y", $sStartTS);
		//$sEndDate = date ("F d, Y", $sStartTS + (6*24*60*60));
		$framework->tpl->assign("SDATE",$sStartDate);
		$par = "mod=".$_REQUEST['mod']."&pg=".$_REQUEST['pg']."&act=".$_REQUEST['act']."&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"];
		list($rs, $numpad,$cnt_rs,$limitList) =$user->nominationList($_REQUEST['pageNo'], $_REQUEST["limit"],$par, OBJECT,$orderBy,$week_id);
		$framework->tpl->assign("NOM_LIST",$rs);
		$framework->tpl->assign("NUMPAD",$numpad);
		$framework->tpl->assign("LIMIT_LIST",$limitList);
		$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/member/tpl/nomination_list.tpl");
		break;
	case "profile_question_list":
		$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act'];
		list($rs, $numpad)=$user->getProfileQuestions($_REQUEST['pageNo'], 25, $par, ARRAY_A, $_REQUEST['orderBy'],0,0,0,0,0);
		$framework->tpl->assign("QLIST",$rs);
		$framework->tpl->assign("QLIST_NUMPAD",$numpad);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/member/tpl/question_list.tpl");
		break;	
	case "add_profile_question":
	
		$req = &$_REQUEST;
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			if( ($message = $user->addEditProfileQuestion($req)) === true ) {
                 redirect(makeLink(array("mod"=>"member", "pg"=>"user"), "act=profile_question_list"));
            } 
			setMessage($message);
		}
		if($message){
          	$row = $_POST;
    	} 
		elseif($_REQUEST['id']) {
    		 $row = $user->getProfileQuestionDetails($_REQUEST['id']);
        }
		$framework->tpl->assign("OPTION", $row);	
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/member/tpl/add_question.tpl");
		break;		
	case "profile_question_delete":
		$user->deleteProfileQuestion($_REQUEST['id']);		
		redirect(makeLink(array("mod"=>"member", "pg"=>"user"), "act=profile_question_list"));
		break;		
		
		
		
		
			
	case "openion_question_list":
		$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act'];
		list($rs, $numpad)=$user->getOenionQuestions($_REQUEST['pageNo'], 25, $par, ARRAY_A, $_REQUEST['orderBy'],0,0,0,0,0);
		$framework->tpl->assign("QLIST",$rs);
		$framework->tpl->assign("QLIST_NUMPAD",$numpad);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/member/tpl/openion_question_list.tpl");
		break;	
		
	case "add_openion_question":
	
		$req = &$_REQUEST;
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			if( ($message = $user->addEditOpenionQuestion($req)) === true ) {
                 redirect(makeLink(array("mod"=>"member", "pg"=>"user"), "act=openion_question_list"));
            } 
			setMessage($message);
		}
		if($message){
          	$row = $_POST;
    	} 
		elseif($_REQUEST['id']) {
    		 $row = $user->getOpenionQuestionDetails($_REQUEST['id']);
        }
		$framework->tpl->assign("OPTION", $row);	
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/member/tpl/add_openion_question.tpl");
		break;		
		
	case "openion_question_delete":
		$user->deleteOpenionQuestion($_REQUEST['id']);		
		redirect(makeLink(array("mod"=>"member", "pg"=>"user"), "act=openion_question_list"));
		break;
	case "confirm_payment":	
	
		if($framework->config['payment_receiver'] == 'store') {
			$PaypalBusinessAccount	=	$PaymentObj->getPayflowLinkDetailsFromStoreName('0');
			$test_mode_paypal		=	$PaymentObj->getPayflowtest_modeFromStoreName($_REQUEST['storename']);
			$rec_billing            =	$PaymentObj->getPayflowRec_billFromStoreName(0);
		} else {
			$PaypalBusinessAccount	=	$typeObj->getPaypalAccountEmail();
			$rec_billing            =	$PaymentObj->getPayflowRec_billFromStoreName(0);
		}
		$framework->tpl->assign("PAYPAL_REC_BILL", $rec_billing);
		$framework->tpl->assign("PAYPAL_ACCOUNT_MAIL", $PaypalBusinessAccount);
		$framework->tpl->assign("PAYPAL_TEST_MODE", $test_mode_paypal);
		$sub = $objUser->loadSubscriptions();
		
		$framework->tpl->assign("SUB_PACKS",$sub);
		$expire = $_REQUEST['exp'];
		$user_det = $objUser->getUserdetails($_REQUEST["user_id"]);
		$store    = $objUser->getStore($_REQUEST["user_id"]);		
		$framework->tpl->assign("STORE_DET", $store);
		//$user_sub = $objUser->loadSubscriptions($user_det["reg_pack"]);
		//$user_sub_details = $objUser->getSubpacks($user_det["reg_pack"]);
		//$framework->tpl->assign("SUB_PACKS",$user_sub_details);
		if ($user_det["amt_paid"]=="Y" and !isset($_REQUEST['upgrade']) and $expire != 1)
		{
			setMessage("This user has already remitted Registration Payments");
		}
		else
		{
				setMessage("Please select a subscription to activate your webstore.",MSG_INFO);
				if($_REQUEST['reg_pack']){
					$package_id = $_REQUEST['reg_pack'];
				}else{
					$package_id = $user_det["reg_pack"];
				}
				if($_REQUEST['sub_pack']){
					$subscr_id = $_REQUEST['sub_pack'];
				}else{
					$subscr_id  = $user_det["sub_pack"];
				}
				

				//echo $subscr_id;
				#------- 21-1-08 -------------------
				if($_REQUEST['no_reg_flg'] == 'Y')
				{ 
					$oldSubEndDt	=	$objUser->getSubscriptionEndDate($_SESSION['memberid']);
				}
				#------- 21-1-08 -------------------
				if ($subscr_id>0)
				{
					$enddate = $objUser->getEndDate($subscr_id,$oldSubEndDt->enddate);
				}
				if ($enddate!="")
				{
					$framework->tpl->assign("ENDDATE",$enddate);
				}
				if ( ($package_id>0)|| ($subscr_id>0))
				{

					$pack_det   	=	$objUser->getPackageDetails($package_id);
					
					if ($subscr_id>0)
					{
						$subscr_det = $objUser->getSubscrDetails($subscr_id);
					}
					$pack = array();
					$pack[0] = $pack_det["package_name"];
					$pack[1] = $pack_det["fee"];
					if ($subscr_id>0)
					{
						if ($subscr_det["type"]=="D")
						{
							$type = " Days";
						}
						elseif ($subscr_det["type"]=="M")
						{
							$type = " Month";
						}
						elseif ($subscr_det["type"]=="Y")
						{
							$type = " Year";
						}
						$pack[2] = $subscr_det["name"];
						$pack[3] = $subscr_det["fees"];
						$pack[4] = "For ".$subscr_det["duration"].$type;
			
					}
					#------------------- 21-1-08---
					if($_REQUEST['no_reg_flg'] != 'Y')
						$tot_amt = $pack_det["fee"]+ $subscr_det["fees"];
					else
						$tot_amt =  $subscr_det["fees"];
					#------------------- 21-1-08---
					$framework->tpl->assign("TOT_AMT",$tot_amt);
					$framework->tpl->assign("UPGRADE_TOT_AMT",$subscr_det["fees"]);
					$framework->tpl->assign("PACK",$pack);
					$framework->tpl->assign("SUB_PACK",$subscr_id);

					
				}
				else
				{
					setMessage("This user has a Free Membership");
				}
			}


					
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/member/tpl/payment_info.tpl");
		break;
		
		case "make_payemnt":
		
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/member/tpl/make_payment.tpl");
			break;
			
		case "paymentnotify":
			$objUser->updatePaypalStatus($_REQUEST['user_id']);
			exit;
			break;	
		case "email_verify":
		
		
			
		
			if($_REQUEST['user_id']){
			
			
			$user_det = $user->getUserdetails($_REQUEST["user_id"]);
			$myId     = $user_det["id"];
			$msg_display  = "You have not verified your email address. Your E-Mail address in our records are <b>{$user_det['email']}</b>. If you did not receive the activation link, please click on the link below to resend Activation Link";
			
			/*$msg_display .= "Username: {$user_det["username"]}<br/>";
			$msg_display .= "Email: {$user_det["email"]} (<b>Unverified</b>)<br/>";*/

			setMessage($msg_display,MSG_INFO);
			
			if ($_SERVER['REQUEST_METHOD']=="POST")
			{
				
				$user_det = $objUser->getUserdetails($_REQUEST["user_id"]);
				$store    = $objUser->getStore($_REQUEST["user_id"]);
				
				
				$mail_header = array();
				
				$mail_header["to"]   = $user_det["email"];
				$dynamic_vars = array();
				$dynamic_vars["USER_NAME"]  = $user_det["username"];
				$dynamic_vars["FIRST_NAME"] = $user_det["first_name"];
				$dynamic_vars["LAST_NAME"]  = $user_det["last_name"];
				$dynamic_vars["PASSWORD"]   = $user_det["password"];
				$mail_header["from"]		= $framework->config['admin_email'];
				$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
				
			$dynamic_vars["STORE_LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".$store->name."\">".SITE_URL."/".$store->name."</a>";
			
			$dynamic_vars["STORE_LINK_MANAGE"]       = "<a target='_blank' href=\"".SITE_URL."/".$store->name.'/manage'."\">".SITE_URL."/".$store->name.'/manage'."</a>";
			
		        $dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";
					
				$mail_header["from"] = $framework->config['admin_email'];
				$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
				
				$site_var1 = SITE_URL."/index.php";

				$site_var2 = SITE_URL."/";

				$qr_str = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

				$email->send("store_creation_confirmation",$mail_header,$dynamic_vars);
				setMessage("A registration confirmation email is being sent to: <b>{$user_det['email']}</b>. Please click on the activation link provided in that email to complete your registration.",MSG_SUCCESS);
			}
		
			
			
			}
			
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/member/tpl/email_varify.tpl");
			break;	
			
			case 'reacivationmail':
			
			include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
			$store  		= new Store();
		
			if($_REQUEST['user_id']){
			
				$user_det = $user->getUserdetails($_REQUEST["user_id"]);
				$storedet    = $user->getStore($_REQUEST["user_id"]);
				$mail_header = array();
				$mail_header["to"]   = $user_det["email"];
				$dynamic_vars = array();
				$dynamic_vars["USER_NAME"]  = $user_det["username"];
				$dynamic_vars["FIRST_NAME"] = $user_det["first_name"];
				$dynamic_vars["LAST_NAME"]  = $user_det["last_name"];
				$dynamic_vars["PASSWORD"]   = $user_det["password"];
				
				$mail_header["from"]		= $framework->config['admin_email'];
				$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
				
				$myId=$_REQUEST['user_id'];
				
				if(count($storedet)){
				
						$dynamic_vars["STORE_LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".$storedet->name."\">".SITE_URL."/".$storedet->name."</a>";
			$dynamic_vars["STORE_LINK_MANAGE"]       = "<a target='_blank' href=\"".SITE_URL."/".$storedet->name.'/manage'."\">".SITE_URL."/".$storedet->name.'/manage'."</a>";
			
		        $dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";
					
				$site_var1 = SITE_URL."/index.php";
				$site_var2 = SITE_URL."/";
				$qr_str = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
				$email->send("store_creation_confirmation",$mail_header,$dynamic_vars);
				setMessage("A registration confirmation email is being sent to: <b>{$user_det['email']}</b>. Please click on the activation link provided in that email to complete your registration.",MSG_SUCCESS);
				
				}
				else{
					if($user_det["from_store"]>0){
					$storeDetails = $store->storeGet($user_det['from_store']);
					
					if($storeDetails['email'] != ''){
						$mail_header['from'] = 	$storeDetails['email'];
					}	
					else{
						$res = $store->GetStoreOwnerEmailByStoreId($user_det['from_store']);
	  					$mail_header['from'] 	= 	$res[0]['email'];
					}	
					$dynamic_vars["SITE_NAME"]  = $storeDetails['heading'];
					
					$dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".$storeDetails["name"]."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";
					
				}
				else{
					$dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";
				}
					$email->send("registration_confirmation",$mail_header,$dynamic_vars);
				}
			}
			setMessage("A registration confirmation email is being sent to: <b>{$user_det['email']}</b>. ",MSG_SUCCESS);
			redirect( $_SERVER['HTTP_REFERER']);
			
			break;	
			
		case 'change_email';
		
			//echo $_SERVER['HTTP_REFERER'];
			
			if($_SERVER['REQUEST_METHOD']=='POST'){
			
				//print_r($_POST);
				$proceed=1;
				
				if(!checkEmail($_POST['new_email'])){
				
					$msg='Invalid New E-mail Address';
					$proceed=0;
				}
				else if($proceed==1)
				{
					if(!checkEmail($_POST['new_email_confirm'])){
					
						$msg='Invalid Confirm New E-mail Address ';
						$proceed=0;
					}
				}
				
				if($proceed==1)
				{
					
					
					if(strcmp(trim($_POST['new_email']),trim($_POST['new_email_confirm'])) == 0)
					{
						if($_POST['user_id'])
						{
							$retailer=0;
							$user_id	=	$_POST['user_id'];
							$user_det   = 	$user->getUserdetails($user_id);
							$email		=   $user_det['email'];
							
							if($user_det['from_store'] == 0 && $user_det['reg_pack'] > 0){
								$retailer=1;
							}
							
							if(!$user->checkEmail3($_POST['new_email'],'',$user_det['from_store'],$retailer)){
								
								$array=array('email'=>$_POST['new_email'],'id'=>$user_id);
								$user->setArrData($array);
								$user->update();
								redirect($_SERVER['HTTP_REFERER']);
							}
							else
								setMessage('Email already exists',MSG_ERROR);
						}		
					}
					else
						setMessage('E-mail Address are not matching',MSG_ERROR);
							
				}
				else{	
					setMessage($msg,MSG_ERROR);
				}
			
			}
			
			$framework->tpl->assign("USERDET", $user->getUserdetails($_REQUEST['user_id']));
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/member/tpl/change_email.tpl");
			break;	
		case "store_expired":
		setMessage("Your store is expired. Please renew the subscription package",MSG_ERROR);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/member/tpl/store_expired.tpl");
		break;		
			
		case "subscr_log":
		$id = $_REQUEST['id'];
		
		$rs1 = $user->getAllUserSubscription($id,$_REQUEST['keyword'], $_REQUEST['pageNo'], $_REQUEST['limit'], "mod=member&pg=user&act={$_REQUEST['act']}&orderBy={$_REQUEST['orderBy']}&keyword={$_REQUEST['keyword']}&from={$_REQUEST['from']}&id={$_REQUEST['id']}", OBJECT, $_REQUEST['orderBy'],'yes','null');
    	list($rs, $numpad, $cnt, $limitList) = $user->getAllUserSubscription($id,$_REQUEST['keyword'], $_REQUEST['pageNo'], $_REQUEST['limit'], "mod=member&pg=user&act={$_REQUEST['act']}&orderBy={$_REQUEST['orderBy']}&keyword={$_REQUEST['keyword']}&from={$_REQUEST['from']}&id={$_REQUEST['id']}", OBJECT, $_REQUEST['orderBy'],'no','null');
		//$rs_array = array_merge($rs1,$rs);
		
		if (count($rs>0)){
		
		    if ($rs[0]->startdate == $rs1->startdate)
			{
			  unset($rs1);
			
			}else{
			  unset($rs);
			list($rs, $numpad, $cnt, $limitList) = $user->getAllUserSubscription($id,$_REQUEST['keyword'], $_REQUEST['pageNo'], $_REQUEST['limit'], "mod=member&pg=user&act={$_REQUEST['act']}&orderBy={$_REQUEST['orderBy']}&keyword={$_REQUEST['keyword']}&from={$_REQUEST['from']}&id={$_REQUEST['id']}", OBJECT, $_REQUEST['orderBy'],'no',$rs1->startdate);
			
			}
		
		
		}
		
		
		$framework->tpl->assign("MEMBER_LIST1", $rs1);
		$framework->tpl->assign("MEMBER_LIST", $rs);
        $framework->tpl->assign("MEMBER_NUMPAD", $numpad);
        $framework->tpl->assign("MEMBER_LIMIT", $limitList);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/member/tpl/subscr_log.tpl");
       	$framework->tpl->display($global['curr_tpl']."/adminPopup.tpl");
		exit;
		break;
	
		

}
//
//$framework->tpl->display($global['curr_tpl']."/admin.tpl");
if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}
	?>