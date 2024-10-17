<?php
/**
 * **********************************************************************************
 * @package    Member
 * @name       register.php
 * @version    1.0
 * @author     Retheesh Kumar
 * @copyright  2007 Newagesmb (http://www.newagesmb.com), All rights reserved.
 * Created on  17-Aug-2006
 * 
 * This script is a part of NewageSMB Framework. This Framework is not a free software.
 * Copying, Modifying or Distributing this software and its documentation (with or 
 * without modification, for any purpose, with or without fee or royality) is not
 * permitted.
 * 
 ***********************************************************************************/
$framework->tpl->assign("STORE_ID", $store_id);
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user_db2.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.referral.php");
include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.payment.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.paymenttype.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.music.php");
include_once(FRAMEWORK_PATH."/includes/payment/authorize.net/AuthorizeNet.php");
include_once(FRAMEWORK_PATH."/modules/extras/lib/class.extras.php");
//include_once(FRAMEWORK_PATH."/modules/home/lib/language/en_us.lang.php");


$framework->tpl->assign("HD_LEGALINFORMATION",$MOD_HEADS['HD_LEGALINFORMATION']);
/*include_once(FRAMEWORK_PATH."/includes/payment/paypal/class.webpaypalpro.php");
include_once(FRAMEWORK_PATH."/includes/payment/paypal/PaypalPro.php");*/

$email	 		= new Email();
$objUser		= new User();
$db2User		= new Userdb2();
$ref 			= new Referral();
$store  		= new Store();
$album  		= new Album();
$objCms  		= new Cms();
$music  		= new Music();
$PaymentObj		= new Payment();
$typeObj 		= new paymentType();
//$webpayObj      = new WebsitePaypalPro ();
//$payObj			= new PaypalPro();
$authObj		= new AuthorizeNet();
$ExtrasObj		= new Extras();

$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
if(!$global['dynamic_member_list']){
$framework->tpl->assign("QN_LST", $objUser->getQns());
}
$framework->tpl->assign("MEM_TYPE",$objUser->loadMemTypeCmb("0,3"));
$framework->tpl->assign("MEM_TYPE_TAKING_ART",$objUser->loadMemTypeCmb("0,2,3,5"));
	if($global["inner_change_reg"]=="yes")
							{
                $dt=$objCms->getContent("registrationnews");
				$subst=substr($dt["content"],0,500);
				$framework->tpl->assign("registration_news",$subst);
				}

$recentmemdetails= $objUser->allUsers('image','Y','','m.joindate desc','',1);
$dt=date("Y");
$rmemdob=strtotime($recentmemdetails[0]->dob);
$dyf=getdate($rmemdob);
$agf=$dt-$dyf["year"];
//print_r($dyf["year"]);
$recentmemdetails[0]->age=$agf;
$framework->tpl->assign("RECENTPROFILE",$recentmemdetails[0]);


switch($_REQUEST['act']) {


	case "add_edit":
	
	
	$retailer= $_REQUEST['retailer'];	
	 $_REQUEST["storename"];
	
		if($_REQUEST["pg_type"]=="church")
		{
			if ((isset($_SESSION["mem_type"])) && ($_SESSION["mem_type"]!="church"))
			{
				unset($_SESSION["memberid"],$_SESSION["mem_type"]);
			}
		}
		$getId=$_SESSION["memberid"];
		if($global["year_generate"]==1){
			$maxy=date("Y");
			$maxy=$maxy+1;
			for($i=1902;$i<$maxy;$i++){
				$yearlist[]=$i;
			}
		}
         if($global["member_screen_name"]=="Y")
		{
		 for($j=1;$j<=31;$j++){
				$daylist[]=$j;
			    }
				$framework->tpl->assign("daylist",$daylist);
				}
		if($_SERVER['REQUEST_METHOD']=="POST")
		{
				
			// start saving the referral details 20-09-07 shinu
			if(isset($_REQUEST['referral_name']) && $_REQUEST['referral_name']!= $_REQUEST['username'])
			{
				$myReferralName	=	$_REQUEST['referral_name'];
				if($global["inner_change_reg"]==""){
					#$ref->AddReferralDetails($_REQUEST['referral_name'],$_REQUEST['reg_pack']);
				}
			}
			
			// end saving the referral details
			$refferal_id=$_POST["refferal_id"];
			unset($_POST["refferal_id"],$_POST["chk_accept"]);
			if (!$getId)
			{
				if ($_REQUEST["storename"]!="")
				{
					$fr_store = $store->storeGetByName($_REQUEST["storename"]);
					$_POST["from_store"] = $fr_store["id"];
				}

				$fname=basename($_FILES['image']['name']);

				$ftype=$_FILES['image']['type'];
				$tmpname=$_FILES['image']['tmp_name'];

				$st_image=basename($_FILES['store_logo']['name']);
				$st_ftype=$_FILES['store_logo']['type'];
				$st_tmpname=$_FILES['store_logo']['tmp_name'];

				//for storing center logo
				//$st_store_image=basename($_FILES['store_center']['name']);
				//$st_store_ftype=$_FILES['store_center']['type'];
				//$st_store_tmpname=$_FILES['store_center']['tmp_name'];
				//

				//If Image field is not blank setting Image flag in database as Y
				if ($fname)
				{
					$_POST["image"]='Y';
				}
				else
				{
					$_POST["image"]='N';
				}

				$_POST['newsletter'] = 'N';
				$_POST['addr_type']  = 'master'; //Default address type is master
				$_POST['joindate']   = date("Y-m-d H:i:s");

				$proceed =1;
				//START Added for taking_art
				if ($_POST["company_name"])
				{
						$rs = $objUser->allUsers("company_name",$_POST['company_name']);
						if(count($rs))
						{
							$proceed = 0;
							setMessage("Company name already exits");
						}
				}
				if ($_POST["tax_id"])
				{			
					if(!ctype_alnum($_POST['tax_id'])) 
						{
						$proceed = 0;
						setMessage("Use only alphanumeric characters");
						}
							
						$rs = $objUser->allUsers("tax_id",$_POST['tax_id']);
						if(count($rs))
						{
							$proceed = 0;
							setMessage("Tax Id already exits");
						}
				}
				//END Added for taking_art
				if($_POST["store_name"])
				{
					$_POST["store_name"]= ereg_replace(" ", "", $_POST["store_name"]);
					
					if (preg_match ("/[&<>%\*\,\.]/i",$_POST["store_name"])) { 
						$proceed = 0;
						setMessage("Invalid store URL");
					}
					
					if(!$objUser->validStore($_POST["store_name"]))
					{
						$proceed = 0;
						setMessage("Store name already Exists");
					}
				}
				
				if(IsEmpty($_POST["telephone"]) || strlen(trim($_POST["telephone"]))< 6 )
				{
				    $proceed = 0;
					setMessage("Please enter a valid telephone.  Minimum 6 characters ");
				}
				  if(IsEmpty($_POST["postalcode"]) || strlen(trim($_POST["postalcode"]))< 2 )
				{
				    $proceed = 0;
					setMessage("Please enter a valid postalcode.  Minimum 2 characters ");
				}
				
			    if(IsEmpty($_POST["city"]) || strlen(trim($_POST["city"]))< 2 )
				{
				    $proceed = 0;
					setMessage("Please enter a valid city.  Minimum 2 characters ");
				}
				
				if(IsEmpty($_POST["country"]))
				{
				    $proceed = 0;
					setMessage("Please select the country.");
				}
				
				if($_POST["email"])
				{
					if($objUser->checkEmail($_POST["email"],0,$_REQUEST['store_id'],$retailer))
					{
						$proceed = 0;
						setMessage("Email already Exists");
					}
				}
				
					
				if($_POST["username"])
				{
					if($objUser->getUsernameDetails($_REQUEST["username"],$_REQUEST['store_id']))
					{
						$proceed = 0;
						setMessage("Username already Exists");
					}
				}
				if(IsEmpty($_POST["address1"]) ||  strlen(trim($_POST["address1"]))< 4)
				{
				    $proceed = 0;
					setMessage("Please enter a valid Address. Minimum 4 characters");
				}
				
				if(IsEmpty($_POST["password"])||  strlen(trim($_POST["password"]))< 6)
				{
				    $proceed = 0;
					setMessage("Please enter a valid password.  Minimum 6 characters ");
				}
				
				 if(IsEmpty($_POST["username"]) ||  strlen(trim($_POST["username"]))< 4)
				{
				    $proceed = 0;
					setMessage("Please enter a valid username. Minimum 4 characters");
				}
				
				
				if(!checkEmail(trim($_POST["email"])))
				{
						$proceed = 0;
						setMessage("Invalid email address.");
				}
				
				if(IsEmpty($_POST["last_name"]) ||  strlen(trim($_POST["last_name"]))< 2)
				{
				    $proceed = 0;
					setMessage("Please enter a valid last name.  Minimum 2 characters ");
				}
				
				if(IsEmpty($_POST["first_name"]) ||  strlen(trim($_POST["first_name"]))< 2)
				{
				    $proceed = 0;
					setMessage("Please enter a valid first name. Minimum 2 characters");
				}
				
				if ($_POST['profile_url'])
				{
					$rs = $objUser->profileCheck($_POST['profile_url']);
					if (count($rs)>0)
					{
						$proceed = 0;
						setmessage("Profile URL name already taken, please change it");
					}
				}
				
				if($proceed==1)
				{
					$strArr = array();
					$strArr["name"] = $_POST["store_name"];
					//$strArr["redirect_url"] = $_POST["redirect_url"];
					$strArr["heading"] = $_POST["heading"];
					$storeheading = $_POST["heading"];
					$strArr['image_extension'] = $st_ftype;
					//added by vipin for taking art free registration
					if($global['artist_selection']=='Yes')
					{
						$st_ftype_ext = split('/',$st_ftype);
						$strArr['image_extension'] = $st_ftype_ext[1];
							if ($_POST["txt_payment"]== 'Y')
							{
							//$payment = $_POST["txt_payment"];
							$_POST["amt_paid"] = "N";
							
							}else{
							$_POST["amt_paid"] = "Y";
							$payment = "N";
							}
					}//
					else
					{
					
						if ($_POST["txt_payment"])
						{
							$payment = $_POST["txt_payment"];
							$_POST["amt_paid"] ="N";
						}
						else
						{
							$payment = "N";
						}
					}
					$validate = 1;
					
						if(isset($_POST['day']))
					{

						$number   = $_POST['day'];

						if ($number<1 || $number>31 )
						{
							$validate =0;
							$objUser->setErr("Invalid Day");
						}
					}
					else if(isset($_POST['txtNumber']))
					{

						$number   = $_POST['txtNumber'];

						if (md5($number) != $_SESSION['image_random_value'])
						{  
							$validate =0;
							$objUser->setErr("Please Enter Correct Code");
							setMessage("Please Enter Correct Code");
						}
					}
				
				
				
					/*
					Afsal
					*/
					if($global["date_convertion"]=='1'){
					
						$_POST["dob"]=$_POST["month"]."-".$_POST["day"]."-".$_POST["year"];
						$_REQUEST['dob']=$_POST["dob"];

					}

					/**/
					if(array_key_exists("show_format",$global))
					{
						if($global["show_format"] !="")
						$_POST['dob'] = $objUser->dateInsertViewFormat($_REQUEST['dob']);
					}
					
					/* Code by Jeffy on 14th Nov 2007.
					Assign health care values to med_arr and unset health care values from $_POST*/
					if($global['health_care'] == "1"){
						$medi_arr = array();
						$unassign = $objUser->GetUnsetFields();
						foreach ($unassign as $value) {
							$medi_arr[$value[field]] =  $_POST[$value[field]];
						}
						$objUser->unsetFields();
					}
					// Ends here
			//				   print_r($validate);
			//exit;

			$heading1	=	$_POST["heading1"];
			$heading2	=	$_POST["heading2"];		
			if ($validate==1)
					{//if a new field is added that is not required in the member tables should be unset here .[eg for store].
						unset($_POST["heading1"],$_POST["heading2"],$_POST["confirm_pass"],$_POST["btn_save"],$_POST["valStr"],$_POST["store_name"],$_POST["x"],$_POST["y"],$_POST["txt_payment"],$_POST["redirect_url"],$_POST["heading"],$_POST["store_logo"],$_POST["store_center"],$_POST["txtNumber"],$_POST["month"],$_POST["day"],$_POST["year"],$_POST["terms"]);
						
						if($_REQUEST['retailer'])
						$_POST['mem_type']=2;
						else
						$_POST['mem_type']=1;	
						
						$objUser->setArrData($_POST);
						$myId=$objUser->insert();
					}
					
					/*----------------------------
					 * To Add Referral Points to point_system table
					 * Author   : Jeffy
					 * Created  : 26/Feb/2008
					 */
					if($global['seethebook'] == "1" && $_REQUEST['referral_code'] != "" && $myId != ""){
						$points_arr['member_id'] = $myId;
						$referal_id = $objUser->getUserId($_REQUEST['referral_name']);
						$points_arr['referal_id'] = $referal_id[id];
						$points_arr['points'] = $_REQUEST['referral_code'];
						$points_arr['date_created'] = date("Y-m-d H:i:s");
						$objUser->insertPoints($points_arr);
					}
					#------------End------------
					if($global['health_care'] == "1"){
						unset($_POST[same_address]);
						$medi_arr['member_id'] =  $myId;
						$objUser->setArrData($medi_arr);
						$medId=$objUser->med_insert();
						$mail_header = array();
						$mail_header['from'] 	= 	$framework->config['admin_email'];
						$mail_header["to"]   = $_POST["email"];
						$dynamic_vars = array();
						$dynamic_vars["USER_NAME"]  = $_POST["username"];
						$dynamic_vars["FIRST_NAME"] = $_POST["first_name"];
						$dynamic_vars["LAST_NAME"]  = $_POST["last_name"];
						$dynamic_vars["HEIGHT"]  = $medi_arr["height_foot"]." ".$medi_arr["height_inch"];
						$dynamic_vars["WEIGHT"]  = $medi_arr["weight"];
						$dynamic_vars["CHIEF_COMPLAINT"]  = $medi_arr["chief_complaint"];
						$dynamic_vars["CURRENT_MEDICATION"]  = $medi_arr["current_medication"];
						$dynamic_vars["LAST_EXAM_DATE"]  = $medi_arr["last_exam"];
						$dynamic_vars["RECENT_SURGERIES"]  = $medi_arr["recent_surgeries"];						
						$dynamic_vars["ALLERGIES"]  = $medi_arr["allergies"];
						$dynamic_vars["SMOKE"]  = $medi_arr["smoking"];
						$dynamic_vars["ALCOHOL"]  = $medi_arr["alcohol"];
						$dynamic_vars["PAIN"]  = $healthObj->GetPainIndicator($medId);
						$dynamic_vars["FEDEX"]  = $healthObj->GetFedexShip($medId);
						$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
						$email->send("unassigned",$mail_header,$dynamic_vars);
					}
					
					if(!$myId)
					{
						setMessage($objUser->getErr());

						$framework->tpl->assign("MESSAGE",$objUser->getErr());
					}

					if ($myId)
					{
					
						if($refferal_id){
							$arr["user_id"]			=	$myId;
							$arr["friend_id"]		=	$refferal_id;
							$arr["approve"]			=	"approved";
							$arr1["user_id"]		=	$refferal_id;
							$arr1["friend_id"]		=	$myId;
							$arr1["approve"]		=	"approved";
							$usdeta					=	$objUser->getUserdetails($myId);
							$mail					=	$usdeta["email"];
							$objUser->InsertFriendList($arr);
							$objUser->InsertFriendList($arr1);
							$objUser->updateInviteHistoryStatus($refferal_id,$mail);
						}
						

						if ($strArr["name"])
						{
							$strArr["user_id"] = $myId;
							
							if (!$_FILES['store_logo']['error']) {
								$image_extension = substr($_FILES['store_logo']['name'], strrpos($_FILES['store_logo']['name'], ".")+1);
								$strArr['image_extension']=$image_extension;
							}
							
							$strArr['heading1']	=	$heading1;
							$strArr['heading2']	=	$heading2;
							$str_id = $store->addStore($strArr);
							
							if ($st_image)
							{
								//uploading the file

								$dir=SITE_PATH."/modules/store/images/";
								$thumbdir=$dir."thumb/";
								if($global['artist_selection']=='Yes'){
								$insert_id	=	$store->db->insert_id;
								uploadImage($_FILES['store_logo'],$dir,$str_id.".".$image_extension,1);
								chmod($dir.$_FILES['store_logo']['name'],0777);
								}else{
								
								uploadImage($_FILES['store_logo'],$dir,$str_id.".".$image_extension,1);
								chmod($dir.$str_id.".".$image_extension,0777);
								thumbnail($dir,$thumbdir,$str_id.".".$image_extension,100,100,"",$str_id.".".$image_extension);
								chmod($thumbdir.$str_id.".".$image_extension,0777);
								}
							}
							

							//for uploading center image
							/*if ($st_center_image)
							{
							//uploading the file
							$dir=SITE_PATH."/modules/store/images/";
							$thumbdir=$dir."thumb/";
							uploadImage($_FILES['store_center'],$dir,$_FILES['store_center']['name'],1);
							chmod($dir.$_FILES['store_center']['name'],0777);
							thumbnail($dir,$thumbdir,$_FILES['store_center']['name'],100,100,"","$str_id.jpg");
							chmod($thumbdir."$str_id.jpg",0777);
							}*/
							//
						}
						
						if ($_POST["sub_pack"])
						{
							$sub_pack = $_POST["sub_pack"];
						}
						
						if ($sub_pack!="")
						{
							$arr = array();
							$arr["user_id"] = $myId;
							$arr["subscr_id"] = $sub_pack;
							$objUser->setArrData($arr);
							$objUser->addSubscription();
						}

						if ($fname)
						{
							//uploading the file
							$dir=SITE_PATH."/modules/member/images/userpics/";
							$thumbdir=$dir."thumb/";
							// Ratheesh kk updated for original image
							if(array_key_exists("display_imagetype",$global) && $global["display_imagetype"]=="original" ){
								uploadImage($_FILES['image'],$dir,"$myId.jpg",1);
								chmod($dir."$myId.jpg",0777);
								thumbnail($dir,$thumbdir,"$myId.jpg",100,100,"","$myId.jpg");
								chmod($thumbdir."$myId.jpg",0777);
							}else{
								uploadImage($_FILES['image'],$dir,$_FILES['image']['name'],1);
								chmod($dir.$_FILES['image']['name'],0777);
								thumbnail($dir,$thumbdir,$_FILES['image']['name'],100,100,"","$myId.jpg");
								chmod($thumbdir."$myId.jpg",0777);
								@unlink(SITE_PATH."/modules/member/images/userpics/".$_FILES['image']['name']);
								exit;
							}//

						}


						if ($payment=="N")
						{
							$mail_header = array();
							//$mail_header["from"] = $framework->config['site_name'].'<'.$framework->config['admin_email'].'>';
							$mail_header['from'] 	= 	$framework->config['admin_email'];
							$mail_header["to"]   = $_POST["email"];
							$dynamic_vars = array();
							$dynamic_vars["USER_NAME"]  = $_POST["username"];
							$dynamic_vars["FIRST_NAME"] = $_POST["first_name"];
							$dynamic_vars["LAST_NAME"]  = $_POST["last_name"];
							$dynamic_vars['PASSWORD']   = $_POST["password"];
							$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
							$dynamic_vars["REFERRAL_NAME"]  = $_POST["referral_name"];
							$dynamic_vars["HEAR_ABOUTUS"]  = $_POST["hear_about_us"];
							$dynamic_vars["PACKAGE_NAME"]  = $_POST["reg_pack"];

							if($dynamic_vars["PACKAGE_NAME"]>0){
								$package_detail = $objUser->getPackageDetails($dynamic_vars["PACKAGE_NAME"]);
								$package_name = $package_detail["package_name"];
								$dynamic_vars["PACKAGE_NAME"]  = $package_name;
							}
							if ($_REQUEST["storename"]!="")
							{
								$storeDetails = $store->storeGet($_REQUEST['store_id']);
								if($storeDetails['email'] != ''){
									$mail_header['from'] = 	$storeDetails['email'];
								}	
								else{
									$res = $store->GetStoreOwnerEmailByStoreId($_REQUEST['store_id']);
	  								$mail_header['from'] 	= 	$res[0]['email'];
								}	
								
								$dynamic_vars["SITE_NAME"]  = $storeDetails['heading'];
								
								$dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".$_REQUEST["storename"]."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";
							}else{
								$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
								$dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";
							}
						    $email->send("registration_confirmation",$mail_header,$dynamic_vars);
							// send an alert message to the admin
							
							
							$mail_header = array();
							$mail_header["from"] = $framework->config['admin_email'];
							$mail_header["to"]   =$framework->config['admin_email'];
							$email->send("new_user_registerd",$mail_header,$dynamic_vars);
							// end send an alert message to the admin
							//For scihat addd by adarsh


							#  ********* sending mail to the referral ***********
							if($myReferralName != "")
							{
								if($myId)
								{
									//$ref->referralRegistered($_REQUEST['referral_name'],$_REQUEST['reg_pack'],$myId);
									$ref_memId		=	$ref->getRefUserId($_REQUEST['referral_name']);
									$rs_extuser		=	$objUser->getUserdetails($ref_memId);
									$usersubscription_enddate=$ref->getSubEnd($ref_memId);
									$refRs			=	$objUser->getUserdetails($myId);
									$mail_header = array();
									$mail_header['from'] 	= 	$framework->config['admin_email'];
									$mail_header["to"]   = $rs_extuser['email'];
									
									$dynamic_vars = array();
									$dynamic_vars["USER_NAME"] 	 		= $rs_extuser["username"];
									$dynamic_vars["NEWUSER_USER_NAME"] 	= $refRs["username"];
									$dynamic_vars["NEWUSER_USER_EMAIL"] = $refRs["email"];
									$dynamic_vars["SUB_END"]  			= $usersubscription_enddate;
									//$dynamic_vars["EXT_SUB_END"]  		= $extendeddate;
									$dynamic_vars["NEWUSER_FIRST_NAME"] = $refRs["first_name"];
									$dynamic_vars["NEWUSER_LAST_NAME"]  = $refRs["last_name"];
									$dynamic_vars["SITE_NAME"]  		= $framework->config['site_name'];
									$dynamic_vars["PACKAGE_NAME"]  		= $refRs["reg_pack"];
									if($dynamic_vars["PACKAGE_NAME"]>0){
										$package_detail = $objUser->getPackageDetails($dynamic_vars["PACKAGE_NAME"]);
										$package_name = $package_detail["package_name"];
										$dynamic_vars["PACKAGE_NAME"]  = $package_name;
									}
									$email->send("referral_registered",$mail_header,$dynamic_vars);
								}
							}
							# *********** end of sending mail to the referral **********************


							if($_REQUEST['invite']=='accept')
							{
								$objUser->makeActive($myId);
								$array=array();
								$_SESSION["memberid"]=$myId;
								$array["group_id"] = $_REQUEST["group_id"];
								$array["user_id"]  = $myId;
								$array["joindate"] = date("Y-m-d G:i:s");
								$objUser->setArrData($array);
								if($objUser->joinGroup())
								{
									redirect(makeLink(array("mod"=>"forum", "pg"=>"clientthreads"), "act=details&id=".$array["group_id"]));
								}
							}
							//scihat
							redirect(makeLink(array("mod"=>"member", "pg"=>"login"),"act=y&uid=$myId"));
						}
						else
						{
						
						// **** added on 21-Nov-2007 for sending mail to the premium users and his referral,admin
							$mail_header = array();
							//$mail_header["from"] = $framework->config['site_name'].'<'.$framework->config['admin_email'].'>';
							$mail_header['from'] 	= 	$framework->config['admin_email'];
							$mail_header["to"]   = $_POST["email"];
							
							$dynamic_vars = array();
							$dynamic_vars["USER_NAME"]  = $_POST["username"];
							$dynamic_vars["FIRST_NAME"] = $_POST["first_name"];
							$dynamic_vars["LAST_NAME"]  = $_POST["last_name"];
							$dynamic_vars['PASSWORD']   = $_POST["password"];
							$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
							$dynamic_vars["REFERRAL_NAME"]  = $_POST["referral_name"];
							$dynamic_vars["HEAR_ABOUTUS"]  = $_POST["hear_about_us"];
							$dynamic_vars["PACKAGE_NAME"]  = $_POST["reg_pack"];

							if($dynamic_vars["PACKAGE_NAME"]>0){
								$package_detail = $objUser->getPackageDetails($dynamic_vars["PACKAGE_NAME"]);
								$package_name = $package_detail["package_name"];
								$dynamic_vars["PACKAGE_NAME"]  = $package_name;
							}
							$dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";
							//$email->send("registration_confirmation",$mail_header,$dynamic_vars);

							// send an alert message to the admin
							$mail_header = array();
							$mail_header["from"] = $framework->config['admin_email'];
							$mail_header["to"]   =$framework->config['admin_email'];
							
							$email->send("new_user_registerd",$mail_header,$dynamic_vars);
							// end send an alert message to the admin
							
							#  ********* sending mail to the referral ***********
							if($myReferralName != "")
							{
								if($myId)
								{
									//$ref->referralRegistered($_REQUEST['referral_name'],$_REQUEST['reg_pack'],$myId);
									
									$_SESSION["sess_referral_name"]	=	$_REQUEST['referral_name'];
									$_SESSION["sess_myId"]			=	$myId;
									/*$ref_memId		=	$ref->getRefUserId($_REQUEST['referral_name']);
									$rs_extuser		=	$objUser->getUserdetails($ref_memId);
									$usersubscription_enddate=$ref->getSubEnd($ref_memId);
									$refRs			=	$objUser->getUserdetails($myId);
									$mail_header = array();
									$mail_header['from'] 	= 	$framework->config['admin_email'];
									$mail_header["to"]   = $rs_extuser['email'];
									$dynamic_vars = array();
									$dynamic_vars["USER_NAME"] 	 		= $rs_extuser["username"];
									$dynamic_vars["NEWUSER_USER_NAME"] 	= $refRs["username"];
									$dynamic_vars["NEWUSER_USER_EMAIL"] = $refRs["email"];
									$dynamic_vars["SUB_END"]  			= $usersubscription_enddate;
									//$dynamic_vars["EXT_SUB_END"]  		= $extendeddate;
									$dynamic_vars["NEWUSER_FIRST_NAME"] = $refRs["first_name"];
									$dynamic_vars["NEWUSER_LAST_NAME"]  = $refRs["last_name"];
									$dynamic_vars["SITE_NAME"]  		= $framework->config['site_name'];
									$dynamic_vars["PACKAGE_NAME"]  		= $refRs["reg_pack"];
									if($dynamic_vars["PACKAGE_NAME"]>0){
										$package_detail = $objUser->getPackageDetails($dynamic_vars["PACKAGE_NAME"]);
										$package_name = $package_detail["package_name"];
										$dynamic_vars["PACKAGE_NAME"]  = $package_name;
									}
									$email->send("referral_registered",$mail_header,$dynamic_vars);*/
								}
							}
							# *********** end of sending mail to the referral **********************

			// **** end of added on 21-Nov-2007 for sending mail to the premium users and his referral,admin

	
						
						
							if ($framework->config['registration_ssl']==1)
							{
								redirect(makeLink(array("mod"=>"member", "pg"=>"register","sslval=true"),"act=reg_pay&user_id=$myId"));
							}
							else 
							{
								redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=reg_pay&user_id=$myId"));
							}	
						}

					}
				}
				else
				{

					//setMessage("Store name already Exists");
				}
			}
			else
			{
				$proceed=1;
				if ($_POST['profile_url'])
				{

					$rsPrf = $objUser->profileCheck($_POST['profile_url']);

					if (count($rsPrf)>0)
					{

						$old_mem_id = $rsPrf[0]["id"];

						//print $_POST["id"];
						if ($getId==$old_mem_id)
						{
							$proceed=1;
						}
						else
						{
							$proceed = 0;
							setmessage("Profile URL name already taken, please change it");

						}
					}
					else
					{
						$proceed = 1;
					}
				}
				
				if($global["date_convertion"]=='1'){
					
						$_POST["dob"]=$_POST["year"]."-".$_POST["month"]."-".$_POST["day"];
						$_REQUEST['dob']=$_POST["dob"];
						
					}
				if ($proceed==1)
				{
					unset($_POST["confirm_pass"],$_POST["store_id"],$_POST["x"],$_POST["y"],$_POST["btn_save"],$_POST["txt_payment"],$_POST['month'],$_POST['day'],$_POST['year']);
					$_POST["id"]=$getId;
					$_POST["addr_type"] = "master";
					$objUser->setArrData($_POST);
					$upId=$objUser->update();
					redirect(makeLink(array("mod"=>"member", "pg"=>"home")));
				}
			}
						
			
			
		}
		if($getId)
		{
			$framework->tpl->assign("TITLE_HEAD","Member Details");
			$editflg="true";
			$framework->tpl->assign("EDITFLG", $editflg);
			$us_det = $objUser->getUserdetails($getId);
			if ($us_det)
			{
				if($global['date_convertion'] == "1"){
					$db=explode("-",$us_det['dob']);
					$y=$db[0];
					$m=$db[1];
					$d=$db[2];
					$framework->tpl->assign("yy", $y);
					$framework->tpl->assign("mm", $m);
					$framework->tpl->assign("dd", $d);
				}
				$_REQUEST=$_REQUEST+$us_det;
				$framework->tpl->assign("USERINFO", $us_det);
        //print_r ($us_det);
			}


		}
		else
		{
			$framework->tpl->assign("TITLE_HEAD","Member Registration");
		}

		$domain = $_SERVER['HTTP_HOST'];
		//print_r($domain);exit;
		$framework->tpl->assign("WEBADD",$domain);
		$framework->tpl->assign("DOMAINNAME",DOMAIN_URL);
		if($global['date_convertion'] != "1" && $global['dynamic_member_list'] !='Y'){
			$framework->tpl->assign("REGPACKG",$objUser->loadRegPack2());
		}
		if($global['health_care'] == "1"){
			$framework->tpl->assign("STATE_LIST",$objUser->listdropdown('6'));
			$framework->tpl->assign("HEIGHT_FOOT",$objUser->listdropdown('7'));
			$framework->tpl->assign("HEIGHT_INCH",$objUser->listdropdown('8'));
			$framework->tpl->assign("PAIN_INDICATOR",$objUser->ListPainIndicator());
			$framework->tpl->assign("FEDEX_SHIPPING",$objUser->ListFedexShipping());
		}
		if($_REQUEST["reff_id"]){
			$framework->tpl->assign("REFFERAL_ID",$_REQUEST["reff_id"]);
		}
		$framework->tpl->assign("SUBMITBUTTON1", createImagebutton_Div("Submit","javascript:submit_form();",""));
		$framework->tpl->assign("CANCEL", createImagebutton_Div("Cancel","#","history.go(-1)"));

		$framework->tpl->assign("SUBMITBUTTON", createButton("SUBMIT","#","checkLength()"));
		$framework->tpl->assign("RESETBUTTON", createButton("CANCEL","#","history.go(-1)"));
		if($global["mem_type"] == 1){
			$framework->tpl->assign("MEMB_TYPE", $objUser->getmem_type());
		}
		 $framework->tpl->assign("TERMSTORE", $_REQUEST["storename"]);
		
		if($_REQUEST['retailer']==1)
		{
			$framework->tpl->assign("TERMS_STROE_REG",$objCms->pageGetByName('terms'));
			$framework->tpl->assign("TITLE_HEAD","Retailer Registration");
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/reg_retailar.tpl");
		}else
		{
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/reg.tpl");
		}
		$framework->tpl->assign("IP_ADDR",$ip);
		$framework->tpl->assign("year_list",$yearlist);
		if($global["inner_change_reg"]=="yes"){
			$framework->tpl->display($global['curr_tpl']."/inner_ch.tpl");
			exit;
		}
		break;
	case "reg_store":
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/reg_store.tpl");
		break;
		/*case "yourpay_connect":
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/payment.tpl");
		break;
		case "payment_success":
		print "success";
		break;
		case "payment_failue":
		print "failure";
		break;*/
	case "reg_pay":		
	
		if ($_SERVER['REQUEST_METHOD']=="POST") { 
				$_SESSION["sess_tot_amt"]	=	"";
				$_SESSION["sess_reg_amt"]	=	"";
				$_SESSION["sess_subpack_amt"]	=	"";
				if($_REQUEST["tot_amt"] != "")
					$_SESSION["sess_tot_amt"]	=	$_REQUEST["tot_amt"];
				if($_REQUEST["reg_amt"] != "")
					$_SESSION["sess_reg_amt"]	=	$_REQUEST["reg_amt"];
				if($_REQUEST["subpack_amt"] != "")
					$_SESSION["sess_subpack_amt"]	=	$_REQUEST["subpack_amt"];
			$sub_pack				=	urlencode($_REQUEST['sub_pack']);
			$promo_code				=	$_REQUEST['promo_code'];
			$subscription_discount	=	(float)$_REQUEST['subscription_discount'];
									
			if($promo_code != '' && $subscription_discount > 0) {
				$UpdArr	=	array('promo_code' => $promo_code, 'subscription_discount' => $subscription_discount, 'id' => $_REQUEST['user_id']);
				$ExtrasObj->updatePromoCodeUsage($_REQUEST);
				}
			else {
				$UpdArr	=	array('promo_code' => '', 'subscription_discount' => '', 'id' => $_REQUEST['user_id']);
			}

			$objUser->setArrData($UpdArr);
			$objUser->update();
			
			if($_REQUEST['upgrade'] == '1'){
				redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=sub_credpayment&user_id={$_REQUEST['user_id']}&tot_amt={$_REQUEST['UPGRADE_TOT_AMT']}&reg_pack={$_REQUEST['reg_pack']}&flag=U&sub_pack=$sub_pack&no_reg_flg={$_REQUEST['no_reg_flg']}&action=renewal"));
			}else{
				redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=sub_credpayment&user_id={$_REQUEST['user_id']}&tot_amt={$_REQUEST['tot_amt']}&sub_pack=$sub_pack&no_reg_flg={$_REQUEST['no_reg_flg']}&action=registration_pay"));
			}
		}
		
		if ($_REQUEST["user_id"])
		{
			$framework->tpl->assign("UPGRADE",$_REQUEST['upgrade']);
			$user_det = $objUser->getUserdetails($_REQUEST["user_id"]);
			$store    = $objUser->getStore($_REQUEST["user_id"]);
			if ($store->id>0)
			{
				$website = SITE_URL."/".$store->name;
				$framework->tpl->assign("WEBSITE",$website);
				$framework->tpl->assign("ARTIST","Y");
				//$framework->tpl->assign("WEBSITE",)
			}
			$framework->tpl->assign("USERNAME",$user_det["username"]);
			$framework->tpl->assign("FIRST_NAME",$user_det["first_name"]);
			
			if ($user_det["store_id"]>0)
			{
				$framework->tpl->assign("ARTIST","Y");
			}
			if ($user_det["amt_paid"]=="Y" and !isset($_REQUEST['upgrade']))
			{
				setMessage("This user has already remitted Registration Payments");
			}
			else
			{
				//setMessage("Please make the payments to continue",MSG_INFO);
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
					$registrationAmount	=	$pack_det["fee"];
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
					$framework->tpl->assign("REGISTRATION_AMT",$registrationAmount);
					$framework->tpl->assign("USER_ID",$_REQUEST["user_id"]);
					$framework->tpl->assign("PACKAGE_ID",$package_id);
					$framework->tpl->assign("SUBPACK_ID",$subscr_id);
					

					$framework->tpl->assign("PACK",$pack);

					if ($_REQUEST['action']=="registration_pay")
					{
						$arr = array();
						$arr["id"] = $_REQUEST["user_id"];
						$arr["amt_paid"] = "Y";
						$objUser->setArrData($arr);
						if ($objUser->update())
						{
							if ($_SESSION["memberid"]) $_SESSION["amt_paid"]="Y";
							$arr1 = array();
							$arr1["subscr_id"] = $subscr_id;
							$arr1["user_id"]    = $_REQUEST["user_id"];
							$startDate = date("Y-m-d H:i:s");
							$objUser->setArrData($arr1);
							if ($objUser->renewSubscription($startDate,$active))
							{
								$mail_header = array();
								$mail_header["from"] = $framework->config['admin_email'];
								$mail_header["to"]   = $user_det["email"];
								$myId = $user_det["id"];
								$dynamic_vars = array();
								$dynamic_vars["USER_NAME"]  = $user_det["username"];
								$dynamic_vars["FIRST_NAME"] = $user_det["first_name"];
								$dynamic_vars["LAST_NAME"]  = $user_det["last_name"];
								$dynamic_vars["PASSWORD"]   = $user_det['password'];
								$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
								$dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";
								$email->send("registration_confirmation",$mail_header,$dynamic_vars);
								
								if ($_SESSION["memberid"])
								{

									$_SESSION["sub_renew"]="Y";

									if ($_REQUEST["url"])
									{
										redirect($_REQUEST["url"]);
									}
									else
									{
										redirect(makeLink(array('mod'=>"member",'pg'=>"home")));
									}
									# for showing forms
								}elseif(SHOW_FORMS=="Y"){
									redirect(makeLink(array("mod"=>"member", "pg"=>"login"),"act=y&uid=$myId"));
								} # for showing forms
								else
								{

									//}ese{
									redirect(makeLink(array("mod"=>"member", "pg"=>"login"),"act=y&user_id=".$_REQUEST["user_id"]."&thnx=".$_SESSION['thnx_setup']));
									//	}
								}
							}
						}
					}
				}
				else
				{
					setMessage("This user has a Free Membership");
				}
			}
		}
		else
		{
			setMessage("Unknown user");
		}
		
		if ($_REQUEST["user_id"] && !($_REQUEST['upgrade']))
		{
			$user_det = $objUser->getUserdetails($_REQUEST["user_id"]);
			
			$mail_header = array();
			$mail_header["from"] = $framework->config['site_name'].'<'.$framework->config['admin_email'].'>';
			$mail_header["to"]   = $user_det["email"];
			$myId = $user_det["id"];
			$dynamic_vars = array();
			$dynamic_vars["USER_NAME"]  = $user_det["username"];
			$dynamic_vars["FIRST_NAME"] = $user_det["first_name"];
			$dynamic_vars["LAST_NAME"]  = $user_det["last_name"];
			$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
			$dynamic_vars["PASSWORD"]   = $user_det['password'];
			if ($_REQUEST["storename"]!="")
			{
				$dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".$_REQUEST["storename"]."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";
			}
			else
			{
				$dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";
			}
			
				$dynamic_vars["STORE_LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".$store->name."\">".SITE_URL."/".$store->name."</a>";
			
			$dynamic_vars["STORE_LINK_MANAGE"]       = "<a target='_blank' href=\"".SITE_URL."/".$store->name.'/manage'."\">".SITE_URL."/".$store->name.'/manage'."</a>";
			
			
			$email->send("store_creation_confirmation",$mail_header,$dynamic_vars);
		}
		
		$framework->tpl->assign("UDET",$user_det );
		$framework->tpl->assign("STORE_DET",$store ); 
//	
        $msg_list["FIRST_NAME"]=$user_det["first_name"];
		$msg_list["STORE_NAME"]=$store->name;
		if ($store->id>0)
		{
			$msg_list["STORE_LINK"]=SITE_URL."/".$store->name;
			$msg_list["MANAGE_STORE"]=SITE_URL."/".$store->name."/manage";
		}
		else
		{
			$msg_list["STORE_LINK"]="";
			$msg_list["MANAGE_STORE"]="";
		}
		$msg_list["USER_NAME"]=$user_det["username"];
		$msg_list["PASSWORD"]=$user_det["password"]; 
		$msg_list["PACKAGE_NAME"]=$pack[0]; 
		if($pack[1]>0)
		{
			$msg_list["SET_UP_FEE"]="$".$pack[1]; 
		}
		else
		{
			$msg_list["SET_UP_FEE"]="$"."00.00"; 
		}
		$msg_list["SUBSCRIPTION_NAME"]=$pack[2];
		if($pack[1]>0)
		{
			$msg_list["SUBSCRIPTION_FEE"]="$".$pack[3]; 
			$subscriptinAmount	=	$pack[3];
		}
		else
		{
			$msg_list["SUBSCRIPTION_FEE"]="$"."00.00"; 
			$subscriptinAmount	=	0.00;
		}
       if ($enddate!="")
		{            
			$msg_list["SUBSCRIPTION_DURATION"]=date("M d, Y",strtotime($enddate));
		}	
		else
		{
			$msg_list["SUBSCRIPTION_DURATION"]="";
		}	 
		$framework->tpl->assign("SUBDCRIPTION_AMT",$subscriptinAmount);
        $name="store_reg_details";
		$ms=$objUser->listMessage($msg_list,$name);
		$framework->tpl->assign("MSGBODY",$ms["body"]);
//
		if($_REQUEST["yourpayfailure"]=="hidemsg"){
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/reg_payment_urpay.tpl");
		}else{

			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/reg_payment.tpl");
		}

		break;
	case "sub_renew":
		if ($_SERVER['REQUEST_METHOD']=="POST") {
			$sub_pack	=	urlencode($_REQUEST['sub_pack']);
			redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=sub_credpayment&user_id={$_REQUEST['user_id']}&tot_amt={$_REQUEST['tot_amt']}&sub_pack=$sub_pack&action=renewal"));
		}

		if ($_REQUEST["user_id"])
		{
			$user_det = $objUser->getUserdetails($_REQUEST["user_id"]);
			if ($user_det["sub_pack"]>0)
			{
				$sb = $objUser->getSubscriptionDays($_REQUEST["user_id"]);
				if ($sb)
				{
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
				$msg_display  = "<u><b>Member Details</b> </u><br>";
				$msg_display .= "Username: {$user_det["username"]}<br>";
				$msg_display .= "Current Subscription Status: <b>$sub_status</b><br>";

				setMessage($msg_display,MSG_INFO);
				$user_sub = $objUser->loadSubscriptions($user_det["reg_pack"]);
				$user_sub_details = $objUser->getSubpacks($user_det["reg_pack"]);
				$framework->tpl->assign("SUB_DET",$user_sub);
				$framework->tpl->assign("SUB_PACKS",$user_sub_details);
				if ($_REQUEST['action']=="renewal")
				{

					# The following line of codes added for redirecting to the payment page

					if ($sub=$objUser->getSubscriptionEndDate($_REQUEST["user_id"]))
					{
						$startDate = $sub->enddate;
						$active = 1;
					}
					else
					{
						$startDate = date("Y-m-d H:i:s");
						$active = 0;
					}

					#$pck= $_POST["sub_pack"];
					$pck= $_REQUEST["sub_pack"];
					$pck_id = $pck[0];
					$arr = array();
					$arr["subscr_id"] = $pck_id;
					$arr["user_id"]   = $_REQUEST["user_id"];
					$objUser->setArrData($arr);

					if ($objUser->renewSubscription($startDate,$active))
					{
						$_SESSION["sub_renew"]="Y";
						if ($_REQUEST["url"])
						{

							redirect($_REQUEST["url"]);
						}
						else
						{

							redirect(makeLink(array('mod'=>"member",'pg'=>"home")));
						}
					}
				}
			}
			else
			{
				setMessage("This user has a FREE membership");
			}
		}
		else
		{
			setMessage("Unknown User");
		}
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/subscription_pay.tpl");
		break;


	case "sub_credpayment" :
		
		$user_det = $objUser->getUserdetails($_REQUEST["user_id"]);
		
		$package_id = $user_det["reg_pack"];
		$subscr_id  = $user_det["sub_pack"];
		$framework->tpl->assign("SUBPACK_ID", $subscr_id);		
		$pack_det   	=	$objUser->getPackageDetails($package_id);
		
		if ($subscr_id>0)
		{
			$subscr_det = $objUser->getSubscrDetails($subscr_id);
		}
		
		if($_SESSION["sess_tot_amt"] != "")
		{
			$tot_amt	=	$_SESSION["sess_tot_amt"];
		}
		else
		{
			$tot_amt = $pack_det["fee"];
			
			if($subscr_id>0){
			 $tot_amt +=$subscr_det["fees"];
			}		
		}		
		$framework->tpl->assign("TOTAL_AMT", $tot_amt);	
		if($_SESSION["sess_reg_amt"] != "")
			$framework->tpl->assign("REG_FEE", $_SESSION["sess_reg_amt"]);	
		else
			$framework->tpl->assign("REG_FEE", $pack_det["fee"]);
		if($_SESSION["sess_subpack_amt"] != "")
			$framework->tpl->assign("SUBS_FEE", $_SESSION["sess_subpack_amt"]);	
		else
			$framework->tpl->assign("SUBS_FEE", $subscr_det["fees"]);	
		
		unset($_SESSION["sess_tot_amt"]);
		unset($_SESSION["sess_reg_amt"]);
		unset($_SESSION["sess_subpack_amt"]);
				
	    $_SESSION['thnx_setup'] = $_REQUEST['thnx_setup'];
		$ConfExists		=	$PaymentObj->configurationExistsForStore('0');
		$PaymentMethod	=	$PaymentObj->getActivePaymentGateway('0');  #Paypal Pro, Authorize.Net, LinkPoint Central 0 --> Store Owned by admin, function prototype getActivePaymentGateway($StoreName)
		if($framework->config['payment_receiver'] == 'store') {
			$PaypalBusinessAccount	=	$PaymentObj->getPayflowLinkDetailsFromStoreName('0');
			$test_mode_paypal		=	$PaymentObj->getPayflowtest_modeFromStoreName('0');
			$rec_billing            =	$PaymentObj->getPayflowRec_billFromStoreName(0);
		} else {
			$PaypalBusinessAccount	=	$typeObj->getPaypalAccountEmail();
			$rec_billing            =	$PaymentObj->getPayflowRec_billFromStoreName(0);
		}
		$framework->tpl->assign("PAYPAL_REC_BILL", $rec_billing);
		$framework->tpl->assign("PAYPAL_ACCOUNT_MAIL", $PaypalBusinessAccount);
		$framework->tpl->assign("PAYPAL_TEST_MODE", $test_mode_paypal);
		$UserDetails	=	$objUser->getUserdetails($_REQUEST['user_id']);

		if($ConfExists === FALSE) {
			setMessage('Payment Configuration not Entered at Admin side');	# The control here indicate that the configuration settings are not available
			$framework->tpl->assign("INACTIVE",'disabled');
		}

		$sub_pack		=	urlencode($_REQUEST['sub_pack']);
		$btn_save		=	$_REQUEST['btn_save'];
		if($PaymentMethod === 'YourPay Connect') {

			//if($_SESSION['memberid'] == '')
			//$_SESSION['memberid']	=	$_REQUEST['user_id'];

			$_SESSION['yp_user_id']	=	$_REQUEST['user_id'];
			$_SESSION['tot_amt']	=	$_REQUEST['tot_amt'];
			$_SESSION['sub_pack']	=	$sub_pack;
			$_SESSION['process']	=	'registration';

			redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=yourpayconnect"));
		}
		//print_r($PaymentMethod);exit;
		
		if($btn_save == 'Submit') {
		
		
			if($PaymentMethod === 'Paypal Pro') {

				$Params						 =		  array();
				$Params['firstName']         =        $UserDetails['first_name'];
				$Params['lastName']          =        $UserDetails['last_name'];;
				$Params['creditCardType']    =        $_REQUEST['card_type'];
				$Params['creditCard']        =        $_REQUEST['creditCard'];
				$Params['Expiry_Month']      =        $_REQUEST['Expiry_Month'];
				$Params['Expiry_Year']       =        $_REQUEST['Expiry_Year'];
				$Params['cvc']               =        $_REQUEST['cvc'];
				$Params['address1']          =        $UserDetails['address1'];
				$Params['address2']          =        $UserDetails['address2'];
				$Params['city']              =        $UserDetails['city'];
				$Params['state']             =        $UserDetails['state'];
				$Params['zip']               =        $UserDetails['postalcode'];
				$Params['country']           =        $objUser->getCountry2LetterCode($UserDetails['country']);
				$Params['paid_price']        =        $_REQUEST['tot_amt'];

			} else if($PaymentMethod === 'Authorize.Net') {

				$Params['firstName']         =        $UserDetails['first_name'];
				$Params['lastName']          =        $UserDetails['last_name'];
				$Params['company']       	 =        $UserDetails['company_name'];
				$Params['address1']          =        $UserDetails['address1'];
				$Params['address2']          =        $UserDetails['address2'];
				$Params['city']              =        $UserDetails['city'];
				$Params['state']             =        $UserDetails['state'];
				$Params['zip']               =        $UserDetails['postalcode'];
				$Params['country']           =        $UserDetails['country_name'];
				$Params['phone']			 =		  $UserDetails['telephone'];
				$Params['mail']				 =		  $UserDetails['email'];

				$Params['paid_price']        =        $_REQUEST['tot_amt'];
				$Params['creditCard']        =        $_REQUEST['creditCard'];
				$Params['Expiry_Month']      =        $_REQUEST['Expiry_Month'];
				$Params['Expiry_Year']       =        substr($_REQUEST['Expiry_Year'], -2);
				$Params['cvc']               =        $_REQUEST['cvc'];

			} else if($PaymentMethod === 'YourPay' || $PaymentMethod === 'LinkPoint Central' ) {

				$Params['firstName']         =        $UserDetails['first_name'];
				$Params['lastName']          =        $UserDetails['last_name'];
				$Params['company']       	 =        $UserDetails['company_name'];
				$Params['address1']          =        $UserDetails['address1'];
				$Params['address2']          =        $UserDetails['address2'];
				$Params['city']              =        $UserDetails['city'];
				$Params['state']             =        $UserDetails['state'];
				$Params['zip']               =        $UserDetails['postalcode'];
				$Params['country']           =        $objUser->getCountry2LetterCode($UserDetails['country']);
				$Params['phone']			 =		  $UserDetails['telephone'];
				$Params['mail']				 =		  $UserDetails['email'];

				$Params['paid_price']        =        $_REQUEST['tot_amt'];
				$Params['creditCard']        =        $_REQUEST['creditCard'];
				$Params['Expiry_Month']      =        $_REQUEST['Expiry_Month'];
				$Params['Expiry_Year']       =        substr($_REQUEST['Expiry_Year'], -2);
				$Params['cvc']               =        $_REQUEST['cvc'];

			}
			if ($_REQUEST["flag"]=="U")
			{
				if($_REQUEST['no_reg_flg'] != 'Y')
				  $Params['paid_price']	=	$Params['paid_price']+$arbObj->getRegpackFee($_REQUEST['reg_pack']);
			}
			
				$Result			=	$PaymentObj->processPaymentRequest('0',$Params);
																
				if($Result['Approved'] == 'No') {
					setMessage($Result['Message']);
				} else {
						##### mail sending to the referral modified on 13-03-08 ###
						if($_SESSION["sess_referral_name"] != '' && $_SESSION["sess_myId"] != '') 
						{
								$ref_memId		=	$ref->getRefUserId($_SESSION["sess_referral_name"]);
								$rs_extuser		=	$objUser->getUserdetails($ref_memId);
								$usersubscription_enddate=$ref->getSubEnd($ref_memId);
								$refRs			=	$objUser->getUserdetails($_SESSION["sess_myId"]);
								$mail_header = array();
								$mail_header['from'] 	= 	$framework->config['admin_email'];
								$mail_header["to"]   = $rs_extuser['email'];
								$dynamic_vars = array();
								$dynamic_vars["USER_NAME"] 	 		= $rs_extuser["username"];
								$dynamic_vars["NEWUSER_USER_NAME"] 	= $refRs["username"];
								$dynamic_vars["NEWUSER_USER_EMAIL"] = $refRs["email"];
								$dynamic_vars["SUB_END"]  			= $usersubscription_enddate;
								$dynamic_vars["NEWUSER_FIRST_NAME"] = $refRs["first_name"];
								$dynamic_vars["NEWUSER_LAST_NAME"]  = $refRs["last_name"];
								$dynamic_vars["SITE_NAME"]  		= $framework->config['site_name'];
								$dynamic_vars["PACKAGE_NAME"]  		= $refRs["reg_pack"];
								if($dynamic_vars["PACKAGE_NAME"]>0){
									$package_detail = $objUser->getPackageDetails($dynamic_vars["PACKAGE_NAME"]);
									$package_name = $package_detail["package_name"];
									$dynamic_vars["PACKAGE_NAME"]  = $package_name;
								}
								$email->send("referral_registered",$mail_header,$dynamic_vars);
							}  
							unset($_SESSION["sess_referral_name"]);
							unset($_SESSION["sess_myId"]);
						##### end of mail sending to the referral modified on 13-03-08 ###
				
					$TransactionId		=	$Result['TransactionId'];
					
					/// for arb
						$authDetails	=  $authObj->getAuthorizeNet(0, 'admin');
						if($PaymentMethod === 'Authorize.Net' && $authDetails['auth_net_arb']=='Y')
						{
							$Params						 =	array(); 
							$Params['loginname']         =  $authDetails['auth_net_login_id'];
							$Params['transactionkey']    =  $authDetails['auth_net_tran_key'];
							$Params['refId']        	 =  $UserDetails['id'];
							$Params['name']        	 	 =  $UserDetails['username'];
							
							
							#Added the following 4 lines of code from sawitonline live site for subscription purpose
							if($_REQUEST['sub_pack']!='')
								$SubAmount	=	$arbObj->getSubpackFee($_REQUEST['sub_pack']);
							else
								$SubAmount	=	$arbObj->getSubpackFee($UserDetails['sub_pack']);
							
							
							$totalAmount	=	$_REQUEST['tot_amt'];
							if ($_REQUEST["flag"]=="U")
							{
								$RegPackAmount	=	$arbObj->getRegpackFee($_REQUEST['reg_pack']);
								$SubAmount		=	$arbObj->getSubpackFee($_REQUEST['sub_pack']);
								$totalAmount	=	$totalAmount+$RegPackAmount;	
							}
							//$Params['trialAmount']      = 	$totalAmount;
							$Params['trialAmount']      = 	0;
							$Params['amount']        	 =  $SubAmount - $UserDetails['subscription_discount']; //$_REQUEST['tot_amt'];//
							// setting the length and unit
								$UsersubDetail	=	$arbObj->getArbUnits($UserDetails['sub_pack']);
								if($UsersubDetail['type']=="Y")
								{
									//$Params['length']        =  365*$UsersubDetail['duration'];
									$Params['length']        =  365;
									$Params['unit']        	 =  "days";
								}
								elseif($UsersubDetail['type']=="D")
								{
									$Params['length']        =  $UsersubDetail['duration'];
									$Params['unit']        	 =  "days";
								}
								elseif($UsersubDetail['type']=="M")
								{
									$Params['length']        =  1*$UsersubDetail['duration'];
									$Params['unit']        	 =  "months";
								}
								else
								{
									$Params['length']        =  "1";
									$Params['unit']        	 =  "months";
								}
								
							// end setting the length and unit
							
							$Params['startDate']         =  date("Y-m-d");
							if ($_REQUEST["flag"]=="U" && $_REQUEST['no_reg_flg']=="Y")
							{
								$sub=$objUser->getSubscriptionEndDate($_REQUEST["user_id"]);
								if($sub->enddate != '')
								{
									list($y,$m,$d2)	=	split("-",$sub->enddate);
									$d	=	substr($d2, 0, 2); 
									$Params['startDate']         =  $y."-".$m."-".$d;
								}
								else
								{
									$Params['startDate']         =  date("Y-m-d");
								}
							} //print($Params['startDate']);exit;
							$Params['totalOccurrences']  =  "9999";
							$Params['trialOccurrences']  =  "1";
							$Params['cardNumber']        =  $_REQUEST['creditCard'];
							$Params['cardCode']        	 =  $_REQUEST['cvc'];
							$Params['expirationDate']    =  $_REQUEST['Expiry_Year']."-".$_REQUEST['Expiry_Month'];
							$Params['firstName']         =  $UserDetails['first_name'];
							$Params['lastName']          =  $UserDetails['last_name'];
							$Params['address']        	 =  $UserDetails['address1']." ".$UserDetails['address2'];
							$Params['city']              =  $UserDetails['city'];
							$Params['state']             =  $UserDetails['state'];
							$Params['zip']               =  $UserDetails['postalcode'];
							$Params['country']           =  $UserDetails['country_name'];
							if($Params['lastName'] =="")
							{
								$Params['lastName']          =  $UserDetails['first_name'];
							}

														
							// upgrading the user account with arb
							
								$arb_subDet	=	$arbObj->getSubscriptionId($UserDetails['id']);
								if($arb_subDet != '')
								{
									$arb_sub_id	=	$arb_subDet['subscription_id'];
									if($arb_sub_id != '')
										$arbObj->cancelSubscription($authDetails['auth_net_login_id'],$authDetails['auth_net_tran_key'],$UserDetails['id'],$arb_sub_id);
								}
							
							// upgrading the user account with arb
							$Result			=	$arbObj->createARBAuthSubscription('0',$Params);
																				
							list ($refId, $resultCode, $code, $text, $subscriptionId) =$arbObj->parse_return($Result);
							//print_r($Result);
							
							if($subscriptionId != '')
							{
								$arbObj->saveSubscriptionDetails($UserDetails['id'],$subscriptionId);
							}
							 /* echo " refId: $refId<br>";
							   echo " resultCode: $resultCode <br>";
							   echo " code: $code<br>";
							   echo " text: $text<br>";
							   echo " subscriptionId: $subscriptionId <br><br>";
							exit;*/
							
							
							if($resultCode != 'Ok') 
							{ 
								setMessage($text);
							}
							else
							{
								if ($_REQUEST["flag"]=="U")
								{
										redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=upgrade&user_id={$_REQUEST['user_id']}&tot_amt={$_REQUEST['tot_amt']}&sub_pack=$sub_pack&reg_pack={$_REQUEST['reg_pack']}&action=renewal&no_reg_flg={$_REQUEST['no_reg_flg']}&transactionid=$subscriptionId"));
								}
								elseif ($_REQUEST["action"]=="renewal")
								{
									redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=sub_renew&user_id={$_REQUEST['user_id']}&tot_amt={$_REQUEST['tot_amt']}&sub_pack=$sub_pack&action=renewal&transactionid=$subscriptionId"));
								}
								else 
								{
									redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=reg_pay&user_id={$_REQUEST['user_id']}&tot_amt={$_REQUEST['tot_amt']}&sub_pack=$sub_pack&action=registration_pay&transactionid=$subscriptionId"));
								}
							}
						
						}
						
						/// end for arb
						
				//// for paypal pro recurring billing
					//print_r($_REQUEST);
						$payDetails	=  $payObj->getPaypalPro(0, 'admin');
						if($PaymentMethod === 'Paypal Pro' && $payDetails['paypal_rb']=='Y')
						{
							$Params					   =	array(); 
							$Params['API_UserName']    =  $payDetails['api_username'];
							$Params['API_Password']    =  $payDetails['api_password'];
							$Params['API_Signature']   =  $payDetails['signature'];
							$webpayObj ->WebsitePaypalPro($Params);
						    $Params['METHOD'] 				 =  "CreateRecurringPaymentsProfile";
							$Params['BILLINGPERIOD'] 		 =  "WEEK";
							$Params['BILLINGFREQUENCY']		 =  "52";
							$Params['AMT']               	 =	"100";
							$Params['NOTE']               	 =	"note";
							$Params['PROFILESTARTDATE']      =   date("Y-m-d");
							$Params['SUBSCRIBERNAME']  		 =  $UserDetails['first_name'];
							$Params['ACCT']       			 =  $_REQUEST['creditCard'];
							$Params['EXPDATE']       		 =  "122009";
							$Params['CARDVERIFICATIONVALUE'] =  $_REQUEST['cvc'];
							$Params['FIRSTNAME']       		 =  $UserDetails['first_name'];
							$Params['LASTNAME']       		 =  $UserDetails['last_name'];
							/*$Params['address']        	 =  $UserDetails['address1']." ".$UserDetails['address2'];
							$Params['city']             	 =  $UserDetails['city'];
							$Params['state']          		 =  $UserDetails['state'];
							$Params['zip']            	   	 =  $UserDetails['postalcode'];
							$Params['country']        	     =  $UserDetails['country_name'];*/
							
							$prb_subDet	=	$arbObj->getSubscriptionId($UserDetails['id']);
							if($prb_subDet != '')
								{
									$prb_sub_id	=	$prb_subDet['subscription_id'];
									if($prb_sub_id != '')
										$UpdateResult	=	$webpayObj ->UpdateRecurringPaymentsProfileRequest($Params,$prb_sub_id);
			
								}else{
							$Result			=	$webpayObj ->CreateRecurringPaymentsProfileRequest($Params);
							}
							$subscriptionId = $Result['ProfileId'];
							
							if($subscriptionId != '')
							{
								$payObj->saveSubscriptionDetails($UserDetails['id'],$subscriptionId);
							}
							
							$text="Transaction not successfull";
							
							if($Result['Approved']	!=	'Yes')
							{ 
								setMessage($text);
							}
							else
							{
								if ($_REQUEST["flag"]=="U")
								{
										redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=upgrade&user_id={$_REQUEST['user_id']}&tot_amt={$_REQUEST['tot_amt']}&sub_pack=$sub_pack&reg_pack={$_REQUEST['reg_pack']}&action=renewal&no_reg_flg={$_REQUEST['no_reg_flg']}&transactionid=$subscriptionId"));
								}
								elseif ($_REQUEST["action"]=="renewal")
								{
									redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=sub_renew&user_id={$_REQUEST['user_id']}&tot_amt={$_REQUEST['tot_amt']}&sub_pack=$sub_pack&action=renewal&transactionid=$subscriptionId"));
								}
								else 
								{
									redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=reg_pay&user_id={$_REQUEST['user_id']}&tot_amt={$_REQUEST['tot_amt']}&sub_pack=$sub_pack&action=registration_pay&transactionid=$subscriptionId"));
								}
							}
							
							
						}
				//// for paypal pro recurring billing
						
						
					if ($_REQUEST["flag"]=="U")
					{
							redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=upgrade&user_id={$_REQUEST['user_id']}&tot_amt={$_REQUEST['tot_amt']}&sub_pack=$sub_pack&reg_pack={$_REQUEST['reg_pack']}&action=renewal&no_reg_flg={$_REQUEST['no_reg_flg']}&transactionid=$TransactionId"));
					}
					elseif ($_REQUEST["action"]=="renewal")
					{
						redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=sub_renew&user_id={$_REQUEST['user_id']}&tot_amt={$_REQUEST['tot_amt']}&sub_pack=$sub_pack&action=renewal&transactionid=$TransactionId"));
					}
					else
					{
						redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=reg_pay&user_id={$_REQUEST['user_id']}&tot_amt={$_REQUEST['tot_amt']}&sub_pack=$sub_pack&action=registration_pay&transactionid=$TransactionId"));
					}
				}
			


		} # Close if Submit
		
		$PayflowDetails		=	$PaymentObj->getPayflowLinkStoreAccountDetails($store_id);
		$framework->tpl->assign("PAYMENT_MODE_DET", $PayflowDetails);
		
		
		$CreditCards 	=   $PaymentObj->getCreditCardsOfStore('0');
		$CCDetails		=	$PaymentObj->getCreditCardDetailsOfStores('0');
		$framework->tpl->assign("PAYMENT_MSG", $objCms->pageGet(366));
		$framework->tpl->assign("CREDITCARDLOGO",$CCDetails);
		$framework->tpl->assign("CREDITCARD",$CreditCards);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/payment_credit_card.tpl");
		break;

	case 'yourpayconnect':

		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$_SESSION['yp_user_id']	=	$_REQUEST['user_id'];
			$_SESSION['sub_pack']	=	$_REQUEST['sub_pack'];
			$_SESSION['tot_amt']	=	$_REQUEST['tot_amt'];
			$_SESSION['process']	=	$_REQUEST['process'];

			if($_REQUEST['session_uniqueid'] != '')
			$PaymentObj->decodeSession($_REQUEST['session_uniqueid']);
		}


		$framework->tpl->assign("TITLE_HEAD","Payment Gateway");
		$UserDetails		=	$PaymentObj->getUserDetailsForYourPayConnectForm($_SESSION['yp_user_id']);
		$session_unique_id	=	$PaymentObj->encodeSession();
		$StoreConfig		=	$PaymentObj->getConfigurationDetailsOfStore('0');
		$sub_pack			=	urlencode($_SESSION['sub_pack']);

		if(isset($_REQUEST['status'])) {
			$ErrorMsg	=	'Sorry, Your purchase was unsuccessful, Your account will be pending until your payment have been accepted.';
			if($_REQUEST['process'] == 'checkout') {
				redirect(makeLink(array("mod"=>"album", "pg"=>"shop"),"act=viewcart&paymessage={$_REQUEST['failReason']}"));
			} else if($_REQUEST['process'] == 'registration') {
				setMessage($ErrorMsg);
				redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=reg_pay&user_id={$_REQUEST['user_id']}&yourpayfailure=hidemsg"));
				#redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=reg_pay&user_id={$_REQUEST['user_id']}&tot_amt={$_REQUEST['tot_amt']}&sub_pack=$sub_pack&action=registration_pay&transactionid=$TransactionId"));
			}
		}

		$framework->tpl->assign("PROCESS",$_SESSION['process']);
		$framework->tpl->assign("SESSION_ID",$session_unique_id);
		$framework->tpl->assign("USERDETAILS",$UserDetails);
		$framework->tpl->assign("USERID",$_SESSION['yp_user_id']);
		$framework->tpl->assign("TOTALAMOUNT",$_SESSION['tot_amt']);
		$framework->tpl->assign("SUBSCRPACKAGE",$sub_pack);
		$framework->tpl->assign("STORENAME",$StoreConfig['configfile']);
		$framework->tpl->assign("CHARGE_TOTAL",$_SESSION['tot_amt']);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/yourpayconnectform.tpl");
		break;

	case 'yourpayconnectsuccess':
		$sub_pack			=	urlencode($_REQUEST['sub_pack']);
		$TransactionId		=	urlencode($_REQUEST['oid']);
		$ApprovalCode		=	$_REQUEST['approval_code'];
		$AddressVerified	=	FALSE;

		if(strpos($ApprovalCode,'YYYM') === FALSE) {
			$AddressVerified	=	FALSE;
			$AddressMsg			=	'Sorry, Your purchase was unsuccessful, Your account will be pending until your payment have been accepted.';
		} else {
			$AddressVerified	=	TRUE;
		}

		$PaymentObj->decodeSession($_REQUEST['session_uniqueid']);

		if($_REQUEST['process'] == 'checkout') {
			if($_REQUEST['session_uniqueid'] != '')
			$PaymentObj->decodeSession($_REQUEST['session_uniqueid']);

			if($AddressVerified === FALSE) {
				redirect(makeLink(array("mod"=>"album", "pg"=>"shop"),"act=viewcart&paymessage=$AddressMsg"));
			} else if($AddressVerified === TRUE) {
				$album->orderItems($_REQUEST, array('TransactionID'=>$TransactionId, 'Amount'=>$_REQUEST['tot_amt']),$_SESSION['yp_user_id']);
				redirect(makeLink(array("mod"=>"album", "pg"=>"shop"),"act=paymentsuccess&user_id={$_SESSION['yp_user_id']}&tot_amt={$_REQUEST['tot_amt']}&transactionid=$TransactionId"));
			}
		} else if($_REQUEST['process'] == 'registration') {

			if($AddressVerified === FALSE) {
				setMessage($AddressMsg);
				redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=reg_pay&user_id={$_REQUEST['user_id']}&yourpayfailure=hidemsg"));
			} else if($AddressVerified === TRUE) {
				setmessage("Congratulations! <br> You have successfully signed an E-Distribution deal with ".$framework->config['site_name']);
				redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=reg_pay&user_id={$_REQUEST['user_id']}&tot_amt={$_REQUEST['tot_amt']}&sub_pack=$sub_pack&action=registration_pay&transactionid=$TransactionId"));
			}
		}
		break;

	case "sub_cron" :
		$users = $objUser->allUsers('sub_pack','0','>');
		for ($i=0;$i<sizeof($users);$i++)
		{
			if ($sub=$objUser->getSubscriptionDays($users[$i]->id))
			{
				//print "Differnece: {$sub['diff']} <br>";
				$send_email = 0;
				$diff = $sub["diff"];
				$enddate = date("M d, Y",strtotime($sub["enddate"]));
				$email_gap 	= explode(",",$framework->config['sub_reminder_mail_duration']);
				for ($j=0;$j<sizeof($email_gap);$j++)
				{
					if ($email_gap[$j]==$diff)
					{
						$send_email=1;
						break;
					}
				}
				if ($send_email==1)
				{
					//print $users[$i]->email."<br>";
					$mail_header = array();
					$mail_header["from"] = $framework->config['site_name'].'<'.$framework->config['admin_email'].'>';
					$mail_header["to"]   = $users[$i]->email;
					$dynamic_vars = array();
					$dynamic_vars["CDAYS"]      = $diff;
					$dynamic_vars["FIRST_NAME"] = $users[$i]->first_name;
					$dynamic_vars["LAST_NAME"]  = $users[$i]->last_name;
					$dynamic_vars["EXP_DATE"]   = $enddate;
					$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
					$dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"register"), "act=sub_renew&user_id={$users[$i]->id}")."\">Renew your subscription now</a>";
					$email->send("subscription_reminder",$mail_header,$dynamic_vars);
				}
			}
		}
		break;
	case "email_verify":
	
		if ($_REQUEST["user_id"])
		{
			$user_det = $objUser->getUserdetails($_REQUEST["user_id"]);
			$myId     = $user_det["id"];
			$msg_display  = "You have not verified your email address. Your E-Mail address in our records are <b>{$user_det['email']}</b>. If you did not receive the activation link, please click on the link below to resend Activation Link";
			
			/*$msg_display .= "Username: {$user_det["username"]}<br/>";
			$msg_display .= "Email: {$user_det["email"]} (<b>Unverified</b>)<br/>";*/

			setMessage($msg_display,MSG_INFO);
			
			if ($_SERVER['REQUEST_METHOD']=="POST")
			{
				$mail_header = array();
				
				$mail_header["to"]   = $user_det["email"];
				$dynamic_vars = array();
				$dynamic_vars["USER_NAME"]  = $user_det["username"];
				$dynamic_vars["FIRST_NAME"] = $user_det["first_name"];
				$dynamic_vars["LAST_NAME"]  = $user_det["last_name"];
				$dynamic_vars["PASSWORD"]   = $user_det["password"];
				
				
				if ($_REQUEST["storename"]!="")
				{
					$storeDetails = $store->storeGet($store_id);
					
					if($storeDetails['email'] != ''){
						$mail_header['from'] = 	$storeDetails['email'];
					}	
					else{
						$res = $store->GetStoreOwnerEmailByStoreId($store_id);
	  					$mail_header['from'] 	= 	$res[0]['email'];	
				    }
					$dynamic_vars["SITE_NAME"]  = $storeDetails['heading'];
					
					$dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".$_REQUEST["storename"]."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";
				}
				else{
				
					$dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";
					$mail_header["from"] = $framework->config['admin_email'];
					$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
				}
				$site_var1 = SITE_URL."/index.php";

				$site_var2 = SITE_URL."/";

				$qr_str = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

				$email->send("registration_confirmation",$mail_header,$dynamic_vars);
				setMessage("A registration confirmation email is being sent to: <b>{$user_det['email']}</b>. Please click on the activation link provided in that email to complete your registration.",MSG_SUCCESS);
			}
		}
		else
		{
			setMessage("Unknown User");
		}
		$framework->tpl->assign("SUBMITBUTTON", createImagebutton_Div("Send Activation Link","#" ,"submit_form();return false;"));
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/email_verify.tpl");
		if ($framework->config['inner_login']=="Y")
		{
			$framework->tpl->display($global['curr_tpl']."/inner_login.tpl");
			exit;
		}
		break;
	case "location":
		checkLogin();
		if($_SERVER['REQUEST_METHOD']=="POST")
		{
			unset($_POST["x"],$_POST["y"]);
			$_POST["user_id"]=$_SESSION["memberid"];
			$_POST["addr_type"]="other";
			$objUser->setArrData($_POST);
			if($objUser->insertAddress())
			{
				redirect(makeLink(array('mod'=>"member",'pg'=>"home")));
			}
			else
			{
				$framework->tpl->assign("MESSAGE",$objUser->getErr());
			}
		}
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/church_location.tpl");
		break;
	case "all_location":
		checkLogin();
		$mem_det = $objUser->getUserDetails($_SESSION["memberid"]);
		$addr    = $objUser->getAddresses($_SESSION["memberid"]);
		$framework->tpl->assign("MEM_DET",$mem_det);
		$framework->tpl->assign("DNM",$objUser->getDnm($mem_det["denomination"]));
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/locations.tpl");
		break;
	case "shipping_det":
		checkLogin();
		$address = $objUser->getAddress('',$_SESSION['memberid'],'shipping');
		$framework->tpl->assign("BILLING_ADDRESS",$address);
		if($_SERVER['REQUEST_METHOD']=="POST")
		{
			$framework->tpl->assign("BILLING_ADDRESS",$_POST);
			unset($_POST["x"],$_POST["y"],$_POST["new_state"]);
			$_POST["user_id"]   = $_SESSION["memberid"];
			$_POST["addr_type"] = "shipping";
			$objUser->setArrData($_POST);
			$objUser->insertAddress();
			redirect(makeLink(array("mod"=>"member", "pg"=>"home")));
		}
		$framework->tpl->assign("SUBMITBUTTON1", createImagebutton_Div("Submit","#","pagesub()"));
		$framework->tpl->assign("CANCEL", createImagebutton_Div("Cancel","#","history.go(-1)"));

		$framework->tpl->assign("SUBMITBUTTON", createButton("SUBMIT","#","pagesub()"));
		$framework->tpl->assign("RESETBUTTON", createButton("CANCEL","#","history.go(-1)"));
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/shipping.tpl");
		break;
	
	case "paypal_update":
      
	if($_REQUEST['user_id']!="") // for arb
		{
	
		$update_usr_regpack = $objUser->updatePaypalStatus($_REQUEST["user_id"]);
		
		}
	
	case "upgrade":
	//updated by vipin on 28-12-2007
		//if($_REQUEST['transactionid']!="") // for normal payments
		if($_REQUEST['transactionid']!="") // for arb
		{
			$update_usr_regpack = $objUser->updateUserregpack($_REQUEST["user_id"],$_REQUEST['reg_pack'],$_REQUEST['sub_pack']);
			// upgrading subscription
			if ($sub=$objUser->getSubscriptionEndDate($_REQUEST["user_id"]))
			{
				$startDate = $sub->enddate;
				$active = 1;
			}
			else
			{
				$startDate = date("Y-m-d H:i:s");
				$active = 0;
			}

									#----- 23-1-08-----
						if($_REQUEST['no_reg_flg']=="Y")
						{
							$sub=$objUser->getSubscriptionEndDate($_REQUEST["user_id"]);
							list($y,$m,$d2)	=	split("-",$sub->enddate);
							$d	=	substr($d2, 0, 2);  
						}
						else
						{
							$y	=	date("Y");
							$m	=	date("m");
							$d	=	date("d");
						}
						//print($_REQUEST['no_reg_flg']);
						#----- 23-1-08-----
						
						#$pck= $_POST["sub_pack"];
						$pck= $_REQUEST["sub_pack"];
						$pck_id = $pck[0];
						$arr = array();
						$arr["subscr_id"] = $pck_id;
						$arr["user_id"]   = $_REQUEST["user_id"];
						$objUser->setArrData($arr);
						
						if($startDate=="" || $startDate=="0000-00-00 00:00:00")
						{
							$startDate = date("Y-m-d H:i:s");
						}
						// for putting the end date 15-11
						
						$startDate = date("Y-m-d H:i:s");
						$sub_det  = $objUser->getSubscrDetails($arr["subscr_id"]);
						$type	  = $sub_det["type"];
						
						
						
						$duration = $sub_det["duration"];
						if ($type=="D")
						{
							$expire_uti = mktime(0, 0, 0, $m, $d + $duration,  $y);
						}
						elseif ($type=="M")
						{
							$expire_uti = mktime(0, 0, 0, $m + $duration, $d ,  $y);
						}
						else
						{
							$expire_uti = mktime(0, 0, 0, $m, $d,   $y + $duration);
						
						}
						$expire_date = date("Y-m-d H:i:s",$expire_uti);//print($expire_date);exit;
						$objUser->UpgradePackageSubscription($_REQUEST["user_id"],$pck_id,$startDate,$expire_date);
			// end for putting end date 15-11
			
			//$objUser->renewSubscription($startDate,$active);

			// end update subscription
			
			
								if($_REQUEST["user_id"])
								{
									//$ref->referralRegistered($_REQUEST['referral_name'],$_REQUEST['reg_pack'],$myId);
									$rs_extuser		=	$objUser->getUserdetails($_REQUEST["user_id"]);
									$usersubscription_enddate=$ref->getSubEnd($_REQUEST["user_id"]);
								//	$refRs			=	$objUser->getUserdetails($myId);
									$mail_header = array();
									$mail_header['from'] 	= 	$framework->config['admin_email'];
									$mail_header["to"]   = $rs_extuser['email'];
									$dynamic_vars = array();
									$dynamic_vars["USER_NAME"] 	 		= $rs_extuser["username"];
									//$dynamic_vars["NEWUSER_USER_EMAIL"] = $refRs["email"];
									//$dynamic_vars["SUB_END"]  			= $usersubscription_enddate;
									//$dynamic_vars["EXT_SUB_END"]  		= $extendeddate;
									
									$dynamic_vars["FIRST_NAME"] = $rs_extuser["first_name"];
									$dynamic_vars["LAST_NAME"]  = $rs_extuser["last_name"];
									$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
									$dynamic_vars["PACKAGE_NAME"]  = $rs_extuser["reg_pack"];
									if($dynamic_vars["PACKAGE_NAME"]>0){
										$package_detail = $objUser->getPackageDetails($dynamic_vars["PACKAGE_NAME"]);
										$package_name = $package_detail["package_name"];
										$dynamic_vars["PACKAGE_NAME"]  = $package_name;
									}
									$dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";
									$email->send("upgrade_confirmation",$mail_header,$dynamic_vars);
									
								}
							
						
			redirect(makeLink(array("mod"=>"member", "pg"=>"account"),"act=billing_new&pid=170"));


		}
		$reg  = $objUser->loadRegPack();
		$mem_det = $objUser->getUserDetails($_SESSION["memberid"]);

		if($_SERVER['REQUEST_METHOD']=="POST")
		{
			//print_r($_REQUEST['list_id']);
			// added by shinu on 21-1-08
			if($_REQUEST['list_id']==$mem_det["reg_pack"])
			{
			$no_reg_flg	=	"Y";
			}
			else
			{
				$no_reg_flg	=	"N";
			}	
			// end  by shinu on 21-1-08
			$reg_pack_id=$_REQUEST['list_id'];
			$reg_pack_det  = $objUser->getPackageDetails($reg_pack_id);
			$reg_sub_det = $objUser->loadSubscriptions($reg_pack_id);
			if($reg_pack_det['fee']=="0.00" and count($reg_sub_det)==0){
				setMessage("Unable to complete your request");
			}else{
				setMessage("");
				redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=get_subpack&user_id=$_SESSION[memberid]&reg_pack=$reg_pack_id&upgrade=t&no_reg_flg=$no_reg_flg"));
			}
		}
		$framework->tpl->assign("mem_det",$mem_det);
		$framework->tpl->assign("reg_plan",$reg);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/upgrade.tpl");
		break;

	case "get_subpack":
		$user_id	=	$_SESSION['memberid'];
		$reg_pack	=	$_REQUEST['reg_pack'];
		$upgrade	=	$_REQUEST['upgrade'];
		if ($_SERVER['REQUEST_METHOD']=="POST") {
			//$sub_pack	=	urlencode($_REQUEST['sub_pack']);
			$sub_packs	=	$_REQUEST['sub_pack'];
			$no_reg_flg	=	$_REQUEST['no_reg_flg'];
			list($subpack,$amount)	=	split("~",$sub_packs);
			redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=reg_pay&user_id=$user_id&reg_pack=$reg_pack&sub_pack=$subpack&no_reg_flg=$no_reg_flg&upgrade=1"));
		}
		$userDetails	=	$objUser->getUserdetails($user_id);
		$regPackValue	=	$objUser->regPackRetrieve($reg_pack);
		$subPacks	=	$objUser->getSubpacks($reg_pack);
		$framework->tpl->assign("REG_PACKS",$regPackValue);
		$framework->tpl->assign("UPGRADE",$upgrade);
		$framework->tpl->assign("SUB_PACKS",$subPacks);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/subscription_pay.tpl");
		break;
	case "billing_det":
		checkLogin();
		$address = $objUser->getAddress('',$_SESSION['memberid'],'billing');
		//print_r($address);
		$framework->tpl->assign("BILLING_ADDRESS",$address);
		if($_SERVER['REQUEST_METHOD']=="POST")
		{
			$framework->tpl->assign("BILLING_ADDRESS",$_POST);
			unset($_POST["x"],$_POST["y"],$_POST["new_state"],$_POST["cpg"]);
			$_POST["user_id"]   = $_SESSION["memberid"];
			$_POST["addr_type"] = "billing";


			$objUser->setArrData($_POST);
			$objUser->insertAddress();
			if($cpg=="oderform3"){
				redirect(makeLink(array("mod"=>"product","pg"=>"list"), "act=oderform&act1=3"));
			}else{
				redirect(makeLink(array("mod"=>"member", "pg"=>"home")));
			}
		}
		if($_REQUEST["cpg"]){
			$framework->tpl->assign("CPG",$_REQUEST["cpg"]);
		}
		$framework->tpl->assign("SUBMITBUTTON1", createImagebutton_Div("Submit","javascript:void(0)","pagesub()"));
		$framework->tpl->assign("CANCEL", createImagebutton_Div("Cancel","#","history.go(-1)"));

		$framework->tpl->assign("SUBMITBUTTON", createButton("SUBMIT","#","pagesub()"));
		$framework->tpl->assign("RESETBUTTON", createButton("CANCEL","#","history.go(-1)"));
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/billing.tpl");
		break;
	case "rnd_mem":
		if ($_REQUEST["rnd_uid"])
		{
			$rand_mems = $_SESSION["rand_mems"];
		}
		else
		{
		($_REQUEST["limit_mem"]) ? $limit_mem = $_REQUEST["limit_mem"] : $limit_mem = "0,6";
		if ($_REQUEST["limit_mem"])
		{
			$rand_mems = $_SESSION["rand_mems"];
		}
		else
		{
			$rand_mems = $objUser->getRandomMemberList(2);
			$_SESSION["rand_mems"] = $rand_mems;

		}
		//print_r($rand_mems);
		$framework->tpl->assign("TOT_USERS",count($rand_mems));

		$arr = explode(",",$limit_mem);
		($arr[0]==0)? $start=$arr[0]: $start= $arr[0]+1;

		if ($start>0)
		{
			$framework->tpl->assign("PRE_CNT",$start);
		}
		else
		{
			$framework->tpl->assign("PRE_CNT",0);
		}

		$new_mem = array();
		for ($i=$start;$i<$start+6;$i++)
		{
			if ($rand_mems[$i])
			{
				$new_mem[] = $rand_mems[$i];
			}
		}
		}
		$default_id = $new_mem[0]->id;
		$udet = $objUser->getUserdetails($default_id);

		if ($udet["featured_song_id"]>0)
		{
			$rs[0]  = $music->getMusicDetails($udet["featured_song_id"],1);
			$rs[0]->play_duration = $framework->config["featured_song_duration"];
		}
		//list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title:ASC','album_music','music',$stxt,$alb,$default_id,'','featured_song_id','0','>');
		$_SESSION["xml_arr"] = $rs;
		$framework->tpl->assign("USER_IMG",$new_mem);
		$bound = 6 - count($new_mem);
		$framework->tpl->assign("BOUND",$bound);
		
		$framework->tpl->display(SITE_PATH."/modules/member/tpl/rnd_member.tpl");
		exit;
		break;
	case "mem_photo":
		if ($_REQUEST["rnd_uid"])
		{
			$udet = $objUser->getUserdetails($_REQUEST["rnd_uid"]);
			$framework->tpl->assign("USER_IMG",$udet);
			$default_id = $_REQUEST["rnd_uid"];

			if ($udet["featured_song_id"]>0)
			{
				$rs[0]  = $music->getMusicDetails($udet["featured_song_id"],1);

				if ($rs[0]->id=="")
				{
					$rs[0]->media_encrypt_name = "blank";
					$rs[0]->user_id = $_REQUEST["rnd_uid"];
					$rs[0]->title  = "No Song";
				}
				$rs[0]->play_duration = $framework->config["featured_song_duration"];
				$rs[0]->featured="Y";
			}
			else
			{

				$rs[0]->media_encrypt_name = "blank";
				$rs[0]->user_id = $_REQUEST["rnd_uid"];
				$rs[0]->title  = "No Song";
				$rs[0]->play_duration = $framework->config["featured_song_duration"];
				$rs[0]->featured="Y";
			}

			//list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title:ASC','album_music','music',$stxt,$alb,$default_id,'','featured_song_id','0','>');
			//$_SESSION["xml_arr"] = $rs;

		}

		$_SESSION["xml_arr"] = $rs;
		$framework->tpl->display(SITE_PATH."/modules/member/tpl/mem_photo.tpl");
		break;
		case "register_fluent":
			include_once(FRAMEWORK_PATH."/modules/extras/lib/class.states.php");	
			$states 	= 	new States();
			$rs_country			=	$objUser->getCountryName(840);
        	$rs			=	$states->GetAllStates1(840);
			$framework->tpl->assign("STATE_LIST",  $rs);
			if($_SESSION['memberid']=="") $guid=$_SESSION['guid'];
			else $guid=$_SESSION['memberid']; 
			if(isset($_SESSION['memberid']))
			{
				$framework->tpl->assign("member",$_SESSION['memberid']);}
				include_once(SITE_PATH."/managestudent/custom/include/language/en_us.lang.php");
				$framework->tpl->assign("GENDER_LIST", $GLOBALS['app_list_strings']['gender_0']);
				$framework->tpl->assign("CAT_LIST", $db2User->fluentSemesterlist());
				if($_SERVER['REQUEST_METHOD']=="POST")
				{
	
					$reg1_err = 0;
		
					if($_SESSION['memberid']==""){
		
		 				$condition = '$_REQUEST[email]'.' == "" || '.'$_REQUEST[semester]'.'=="" || ';
	
					}
		
	if($condition.$_REQUEST[first_name] == "" || $_REQUEST[last_name] == ""  || $_REQUEST['phone_home']=="" || $_REQUEST[primary_address_street] == "" || $_REQUEST[gender] == "" || $_REQUEST[primary_address_city] == "" || $_REQUEST[primary_address_state] == "" || $_REQUEST[primary_address_postalcode] == "" ){
				$errorMsg.="<br>";
			    $reg1_err = 1;
				if($_REQUEST[first_name] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_FNAME']."<br>";
				}
				if($_REQUEST['last_name'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_LNAME']."<br>";
				}
				
				
				if($_REQUEST['phone_home'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_PHONE1']."<br>";
				}
					if($_REQUEST['primary_address_street'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_ADDR']."<br>";
				}
				
				
				if($_REQUEST['primary_address_city'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_CITY']."<br>";
				}
				if($_REQUEST['primary_address_state'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_STATEREG']."<br>";
				}
				if($_REQUEST['primary_address_postalcode'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_ZIP_CODE']."<br>";
				}
				if($_SESSION['memberid']==""){
					if($_REQUEST['email'] == '')
					{
						$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_REGEMAIL']."<br>";
					}
					
				}
				if($_REQUEST['gender'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_GEN']."<br>";
				}
			if($_SESSION['memberid']==""){	
				if($_REQUEST['semester'] == '')
					{
						$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_SEMESTER']."<br>";
					}
				}
				setMessage('<u><b>'.$MOD_VARIABLES['MOD_ERRORS']['ERR_VALIDATION_INFO'].'</b></u><br><br>' .$errorMsg);
				
				}
				
		if($reg1_err != 1){
			unset($_POST['select2']);
	    	unset($_POST['submit_x']);
			unset($_POST['submit_y']);
	
			$array=$db2User->setArrData($_POST);
			
			$validEmail = 0;
			if($_SESSION['memberid']==""){
				if($objUser->validateEmail($_REQUEST['email']) == FALSE){
					setMessage("Enter A Valid Email");
					$validEmail = 1;
				}
			}
			
			
			if($validEmail != 1){
				$email_status = $db2User->checkEmail_parent($_REQUEST['email'],$guid);
				if($email_status==false){
				
				    if($_REQUEST['id'])
					{
					$db2User->update_parent($_REQUEST['id']);
					redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=register_one"));
					}
					
					if($_SESSION['memberid']){
						$db2User->update_parent($guid);
						redirect(makeLink(array("mod"=>"member", "pg"=>"userHome")));
					}else{	
					
						$db2User->insert_fluent();
						redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=register_one"));
						
					}
				}else{
				 	setMessage("Email already exits");
				}					
			}	
		}
		}
  	if($_SESSION['memberid'])
	{
		$guid=$_SESSION['memberid'];
		$userDetails=$db2User->getUserDet($guid); 
		$_REQUEST=$userDetails;
		$framework->tpl->assign("EDITFLAG",true);
	}
	
	if($_REQUEST['id'])
	{
	
		$guid=$_REQUEST['id'];
		$userDetails=$db2User->getUserDet($guid);
		//print_r($userDetails);
		$_REQUEST=$userDetails; 
		
	}
	
	$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/reg.tpl");
	break;	

	case "register_one":
	
		include_once(FRAMEWORK_PATH."/modules/extras/lib/class.states.php");	
		$states 	= 	new States();
		$rs_country			=	$objUser->getCountryName(840);
        $rs			=	$states->GetAllStates1(840);
	$framework->tpl->assign("STATE_LIST",  $rs);
	if(isset($_SESSION['memberid']))
	    {
	     $guid=$_SESSION['memberid'];
		 $framework->tpl->assign("member",$guid);
		 }
		if(isset($_SESSION['memberid']))
		{
		$framework->tpl->assign("STD_ADD",1);
		}
		# -------------------------------
		# these two conditions are used to display the child drop down and check box in regone.tpl
		# done by Jeffy on 11th April 2008
		if(isset($_REQUEST['adst'])){
			$framework->tpl->assign("ADST",1);
		}
		if(isset($_REQUEST['scid'])){
			$framework->tpl->assign("SCID",1);
		}
		# -------------------------------
		if($_SESSION['guid']=="") $guid=$_SESSION['memberid'];
		else $guid=$_SESSION['guid'];
	    include_once(SITE_PATH."/managestudent/custom/include/language/en_us.lang.php"); 
		include_once(SITE_PATH."/managestudent/custom/application/Ext/Language/en_us.lang.ext.php");
		$framework->tpl->assign("LANG_LIST", $app_list_strings["language_list"]);
		$framework->tpl->assign("GENDER_LIST", $GLOBALS['app_list_strings']['gender_0']);
		//print_r($GLOBALS['app_list_strings']['Start_time_list']);
		//$framework->tpl->assign("CLASS_LIST", $app_list_strings["ClassType_list"]);
		$framework->tpl->assign("CLASS_LIST", $app_list_strings["CLASS_LIST"]);
		$framework->tpl->assign("AGE_LIST", $app_list_strings["agelevel_list"]);
		$framework->tpl->assign("DAY_LIST", $GLOBALS['app_list_strings']['Days_list']);
		$framework->tpl->assign("TIME_LIST",$GLOBALS['app_list_strings']['Start_time_list']);
		$framework->tpl->assign("TIME_LIST1",$GLOBALS['app_list_strings']['End_time_list']);
		
		if($_REQUEST['sid']=="")
		{$parentDetails=$db2User->getParentDetails($guid);
		$framework->tpl->assign("PARENT_LIST", $parentDetails);
		$_REQUEST[]=$parentDetails;
		}
	
		if(isset($_SESSION['memberid'])){
			$childDetails=$db2User->getChildDetails($_SESSION['memberid']);
			$framework->tpl->assign("CHILD_LIST", $childDetails);
		}		
		$sid=$_REQUEST['sid'];
		$childActive = 0;
		if(!isset($_REQUEST['btn_submit']) and !isset($_REQUEST['btn_addStudent'])){
			if($_REQUEST['child'] == 1){
				$framework->tpl->assign("CHILD_DET", 1);
			}else{
				$childDet = $db2User->getChildDet($_REQUEST['child']);
				$framework->tpl->assign("CHILD_DET", $childDet);
			}			
			$childActive = 1;
		}
		//echo $childActive;
		if($sid!=""){
			
			if($_SERVER['REQUEST_METHOD']=="POST"){
			$reg2_err = 0;
		 // print_r($_REQUEST);
			if($_REQUEST[first_name] == '' or $_REQUEST[last_name] == ''  or $_REQUEST[primary_address_street] == '' or $_REQUEST[gender_c] == '' or $_REQUEST[primary_address_city] == '' or $_REQUEST[primary_address_state] == '' or $_REQUEST[primary_address_postalcode] == ''  or $_REQUEST[birthdate] == '' ){
				$errorMsg.="<br>";
				if($_REQUEST[first_name] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_FNAME']."<br>";
				}
				if($_REQUEST['last_name'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_LNAME']."<br>";
				}
				
				if($_REQUEST['primary_address_street'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_ADDR']."<br>";
				}
				if($_REQUEST['gender_c'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_GEN']."<br>";
				}
				if($_REQUEST['primary_address_city'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_CITY']."<br>";
				}
				if($_REQUEST['primary_address_state'] == '' )
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_STATEREG']."<br>";
				}
				if($_REQUEST['primary_address_postalcode'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_ZIP_CODE']."<br>";
				}
				
				if($_REQUEST['birthdate'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_DB']."<br>";
				}
			     setMessage('<u><b>'.$MOD_VARIABLES['MOD_ERRORS']['ERR_VALIDATION_INFO'].'</b></u><br><br>' .$errorMsg);
				 $REG_ARRAY=$_POST;
				$framework->tpl->assign("REG_ARRAY",$REG_ARRAY); 
				$reg2_err = 1;
				$framework->tpl->assign("ERROR_VAR",$reg2_err); 
			}
		
				if($reg2_err != 1){
				$db2User->setArrData($_POST);
			  	$db2User->studentUpdate($sid);
			  redirect(makeLink(array("mod"=>"member", "pg"=>"userHome")));
			  }
			 }
			else
		{
		    $student_det=$db2User->getStudentDetails($sid);
			//print_r( $student_det);
			$_REQUEST=$student_det;
			//print_r($_REQUEST);
			$framework->tpl->assign("PARENT_LIST", $student_det);
			$framework->tpl->assign("firstname", $parentDetails->first_name);
			$_REQUEST['primary_address_country']=$student_det->primary_address_country;
			
		} 
			 $framework->tpl->assign("SID",$sid);
			// print_r($framework->MOD_VARIABLES["MOD_LABELS"]["LBL_A_FN"] );exit;
			 //$framework->tpl->assign("UPDATE1", createImagebutton($framework->MOD_VARIABLES['MOD_LABELS']['LBL_UPDATE'],"#","document.registration.submit()"));
			 //$framework->tpl->assign("UPDATE2", createMouseOverImagebutton("Update","#","document.registration.submit()"));
		}
		
		if($_REQUEST['uid'] != '' and $_REQUEST['scid'] != ''){
			$studentDetails = $db2User->getChildDet($_REQUEST['uid']);
			$framework->tpl->assign("STUDENT_LIST", $studentDetails);
#			$_REQUEST['primary_address_country']=$student_det->primary_address_country;
		}
		if($_SERVER['REQUEST_METHOD']=="POST" and $childActive != 1){
			$reg2_err = 0;
			if($_REQUEST[first_name] == '' or $_REQUEST[last_nam.e] == ''  or $_REQUEST[primary_address_street] == '' or $_REQUEST[gender] == '' or $_REQUEST[primary_address_city] == '' or $_REQUEST[primary_address_state] == '' or $_REQUEST[primary_address_postalcode] == ''  or $_REQUEST[birthdate] == '' or $_REQUEST[language] == '' or $_REQUEST[classtype] == '' or $_REQUEST[agelevel] == '' or $_REQUEST[Sunday] == '' or $_REQUEST[Monday] == '' or $_REQUEST[Tuesday] == '' or $_REQUEST[Wednesday] == '' or $_REQUEST[Thursday] == '' or $_REQUEST[Friday] == '' or $_REQUEST[Saturday] == ''){
				$errorMsg.="<br>";
				if($_REQUEST[first_name] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_FNAME']."<br>";
				}
				if($_REQUEST['last_name'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_LNAME']."<br>";
				}
				
				if($_REQUEST['primary_address_street'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_ADDR']."<br>";
				}
				if($_REQUEST['gender'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_GEN']."<br>";
				}
				if($_REQUEST['primary_address_city'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_CITY']."<br>";
				}
				if($_REQUEST['primary_address_state'] == '' )
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_STATEREG']."<br>";
				}
				if($_REQUEST['primary_address_postalcode'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_ZIP_CODE']."<br>";
				}
				
				if($_REQUEST['birthdate'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_DB']."<br>";
				}
				if($_REQUEST['language'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_LAN']."<br>";
				}
				if($_REQUEST['classtype'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_CLASS_TYPE']."<br>";
						$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_PKG']."<br>";
				}
				if($_REQUEST['agelevel'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_AGE_LEVEL']."<br>";
				
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_ABILITY']."<br>";
				}
				
				
				if($_REQUEST['agelevel']!= '')
				
				{
				  if($_REQUEST['abilitylevel']=="")
				  $errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_ABILITY']."<br>";
				}
				
				if($_REQUEST['classtype'] != '')
				{   if($_REQUEST['package']=="") 
					
						$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_PKG']."<br>";
				}
				
				if($_REQUEST['Sunday'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_SUNDAY']."<br>";
				}
				if($_REQUEST['Monday'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_MONDAY']."<br>";
				}
				if($_REQUEST['Tuesday'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_TUESDAY']."<br>";
				}
				if($_REQUEST['Wednesday'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_WDAY']."<br>";
				}	
				if($_REQUEST['Thursday'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_TDAY']."<br>";
				}	
				if($_REQUEST['Friday'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_FDAY']."<br>";
				}		
				if($_REQUEST['Saturday'] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_SDAY']."<br>";
				}			
				setMessage('<u><b>'.$MOD_VARIABLES['MOD_ERRORS']['ERR_VALIDATION_INFO'].'</b></u><br><br>' .$errorMsg);
				 $REG_ARRAY=$_POST;
				// print("<pre>");
				// print_r($REG_ARRAY);
				// print("<pre>");
				$framework->tpl->assign("REG_ARRAY",$REG_ARRAY); 
				 
				$reg2_err = 1;
				$framework->tpl->assign("ERROR_VAR",$reg2_err); 
			}
			if($reg2_err != 1){
				$new_str="";
				foreach($app_list_strings["Days_list"] as $day){
					if($_POST[$day] == 'Y'){
						$new_str .= $day." - ".$_POST["from_".$day]." To ".$_POST["to_".$day] . "\n";
					}			
				unset($_POST[$day]);
				unset($_POST["from_".$day]);
				unset($_POST["to_".$day]);
				}
	
				$_POST['available_daystimes']=$new_str;
				unset($_POST[countryname]);
				$array=$db2User->setArrData($_POST);
				if(isset($_REQUEST['child']) and $_REQUEST['child'] != 1){
					$myId = $_REQUEST['child'];
				}else{
					$myId=$db2User->studentInsert();					
				}
				if($myId!=""){
					$detail_id=$db2User->studentDetailInsert($myId);
					$semCont_id=$db2User->Insertsemestercontacts($myId,$guid);
				}

		   		if($_REQUEST['add']==1){
					redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=register_one"));
				}else{
					if($_SESSION['memberid']){
						redirect(makeLink(array("mod"=>"member", "pg"=>"userHome")));
					}else{
						redirect(makeLink(array("mod"=>"member", "pg"=>"register","sslval"=>"true"),"act=register_two"));
					}
				}	
			}
		}
	  
	if($sid!=""){
	$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/regone1.tpl");
	}else
	{
	$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/regone.tpl");
	}
		
	
		break;	
		case "register_two":
		
			if(isset($_SESSION['memberid']))
	    	{
	
		 		$guid=$_SESSION['memberid'];
		 		$framework->tpl->assign("member",$guid);
		 	}
			include_once(SITE_PATH."/managestudent/custom/include/language/en_us.lang.php"); 
			include_once(SITE_PATH."/managestudent/custom/application/Ext/Language/en_us.lang.ext.php");
		  
		  
		     //print_r($GLOBALS['app_list_strings']['paymentfre_list']);
			$framework->tpl->assign("PAYMENT_LIST",$app_list_strings["payment_type_list"]);
			$framework->tpl->assign("EXP_MONTH_LIST", $app_list_strings["expirationmonth_list"]);
			$framework->tpl->assign("EXP_YEAR_LIST", $app_list_strings["expirationyear_list"]);
			$framework->tpl->assign("PAYMENTFEE_LIST", $GLOBALS['app_list_strings']['paymentfre_list']);
			 
			if($_SERVER['REQUEST_METHOD']=="POST"){
				$reg3_err = 0;
				//print_r($_REQUEST);
				if(!isset($_SESSION['memberid']))
				{
						if($_REQUEST[nameoncard] == '' or $_REQUEST[cardnumber] == '' or $_REQUEST[paymentfre] == '')
						{
							if($_REQUEST[nameoncard] == '')
							{
								$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_NAME_CARD']."<br>";
							}
							if($_REQUEST[cardnumber] == '')
							{
								$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_CARD_NUMBER']."<br>";
							}
							if($_REQUEST[paymentfre] == '')
							{
							$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_PAY_OPTION']."<br>";	
							
							}
							
							setMessage('<u><b>'.$MOD_VARIABLES['MOD_ERRORS']['ERR_VALIDATION_INFO'].'</b></u><br><br>' .$errorMsg);
							$reg3_err = 1;
						}
				
				}
				
				else
				{
					$framework->tpl->assign("PAYOPTION",1);
					$framework->tpl->assign("EDITFLAG",true);
			
						if($_REQUEST[nameoncard] == '' or $_REQUEST[cardnumber] == '' )
						{
							if($_REQUEST[nameoncard] == '')
							{
								$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_NAME_CARD']."<br>";
							}
							if($_REQUEST[cardnumber] == '')
							{
								$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_CARD_NUMBER']."<br>";
							}
						setMessage('<u><b>'.$MOD_VARIABLES['MOD_ERRORS']['ERR_VALIDATION_INFO'].'</b></u><br><br>' .$errorMsg);
						$reg3_err = 1;
						}
				}
					
				/*if(isset($_REQUEST[cardnumber]) and $_REQUEST[cardnumber] != ''){
				$var=trim($_REQUEST[cardnumber]);
				
					if(strlen($var)!=14)
					{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_VALID_CARD']."<br>";
					setMessage('<u><b>'.$MOD_VARIABLES['MOD_ERRORS']['ERR_VALIDATION_INFO'].'</b></u><br>' .$errorMsg);
						$reg3_err = 1;
					}
					}*/
					/*if(substr($_REQUEST[cardnumber],0,14) == 'xxxx xxxx xxxx'){
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_VALID_CARD']."<br>";
						setMessage('<u><b>'.$MOD_VARIABLES['MOD_ERRORS']['ERR_VALIDATION_INFO'].'</b></u><br>' .$errorMsg);
						$reg3_err = 1;
					}
					*/
						
					//}	
					//
				
				
				/*if(isset($_REQUEST[cardnumber]) and $_REQUEST[cardnumber] != ''){
					if(substr($_REQUEST[cardnumber],0,14) == 'xxxx xxxx xxxx'){
						setMessage("Please Enter Card Number");
						$reg3_err = 1;
					}
				}*/
				/*if($_REQUEST[nameoncard] == '' or $_REQUEST[cardnumber] == ''){
					setMessage("Please Enter all Fields");
					$reg3_err = 1;
				}				
				#--------------------------
				# Payment free will not be set in some cases, to counter that, the below validation is implemented
				# By Jeffy on 10th April 08
				if(isset($_REQUEST[paymentfre]) and $_REQUEST[paymentfre] == ''){
					setMessage("Please Enter all Fields");
					$reg3_err = 1;
				}
				#--------------------------
				# Credit Card Number edit validation
				# By Jeffy on 10th April 08*/
				/*if(isset($_REQUEST[cardnumber]) and $_REQUEST[cardnumber] != ''){
					if(substr($_REQUEST[cardnumber],0,14) == 'xxxx xxxx xxxx'){
						setMessage("Please Enter Card Number");
						$reg3_err = 1;
					}
				}*/
				#--------------------------
				if($reg3_err != 1){
				   	if($_SESSION['guid']=="") $myId=$_SESSION['memberid'];
				   	else $myId=$_SESSION['guid'];
					$array=$db2User->setArrData($_POST);
					$db2User->update_fluent($myId);	
					$db2User->update_fluentscrequest($myId);	
					
					if($_SESSION['memberid']){
						redirect(makeLink(array("mod"=>"member", "pg"=>"userHome")));
					}
					redirect(makeLink(array("mod"=>"member", "pg"=>"register","sslval"=>"false"),"act=register_three"));
				}
	 		}
			else
			{
				 if(isset($_SESSION['memberid']))
				 {
					$framework->tpl->assign("PAYOPTION",1);
					$framework->tpl->assign("EDITFLAG",true);
					$guid=$_SESSION['memberid'];
					$userDetails=$db2User->getUserDet($guid);
					$userDetails['cardnumber']=base64_decode($userDetails['cardnumber']); 
					$_REQUEST=$userDetails;
				 }
				 
				
				//$framework->tpl->assign("EDITFLAG",true);
			
			}
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/regthree.tpl");
			$framework->tpl->display($global['curr_tpl']."/ssl_inner.tpl");
				exit;
		break;
		
		case "register_three":
		include_once(SITE_PATH."/managestudent/custom/include/language/en_us.lang.php"); 
			
		$framework->tpl->assign("HEAR_LIST", $GLOBALS['app_list_strings']['hear_about_us']);		
		if(isset($_SESSION['memberid']))
	    {
		 $guid=$_SESSION['memberid'];
		 $framework->tpl->assign("member",$guid);
		 }
			$user_det=$db2User->getUserDet($_SESSION['guid']);
			
			
			
			if($_SERVER['REQUEST_METHOD']=="POST"){
			
			
				$reg4_err = 0;
				if($_REQUEST[hear_about_us] == 'Other')
				{
				
					$framework->tpl->assign("HEAR",1);
				}
				
				
				if($_REQUEST[agree] == '' or $_REQUEST[password] == '' or $_REQUEST[cpassword] == ''){
				
				{
				if($_REQUEST[agree] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_VALID_AGREE']."<br>";
				}
				if($_REQUEST[password] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_VALID_PASSWORD']."<br>";
				}
				if($_REQUEST[cpassword] == '')
				{
					$errorMsg.=$MOD_VARIABLES['MOD_LABELS']['LBL_VALID_CPASSWORD']."<br>";
				}
				
				
				}
				
					setMessage('<u><b>'.$MOD_VARIABLES['MOD_ERRORS']['ERR_VALIDATION_INFO'].'</b></u><br>' .$errorMsg);
					$reg4_err = 1;
				}			
				if($reg4_err != 1){
					$passwordMatch = 0;
					if($_REQUEST[password] != $_REQUEST[cpassword]){
						setMessage("Passwords Are Not Matching");
						$passwordMatch = 1;
					}
					#--------------------------
					# PHP validation for terms and conditions "I AGREE" clause
					# By Jeffy on 10th April 08
					if(strcasecmp($_REQUEST[agree],"I AGREE") != 0){						
						setMessage("Please Enter \"I AGREE\" to terms and conditions");
						$passwordMatch = 1;
					}
					#--------------------------
					if($passwordMatch != 1){
						$array=$db2User->setArrData($_POST);
						$myId=$db2User->update_fluent($_SESSION['guid']);	
						/////send email start
						$myId = $_SESSION['guid'];
						
						if($global['email_confirm'] !='N'){
							$mail_header = array();
							$mail_header["from"] = $framework->config['admin_email'];
							$mail_header["to"]   = $user_det["email"];
							$dynamic_vars = array();
							$dynamic_vars["USER_NAME"]  = $user_det["username"];
							$dynamic_vars["FIRST_NAME"] = $user_det["first_name"];
							$dynamic_vars["LAST_NAME"]  = $user_det["last_name"];
							$dynamic_vars["PASSWORD"]   = $user_det["password"];
							$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
							$dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"chkLogin"), "fn=activeparent&user_id=$myId")."\">Activate your account now</a>";
							$email->send("registration_confirmation",$mail_header,$dynamic_vars);
						}
					  	
						unset($_SESSION['guid']);
						header('Location: ./registration_thanks.php');
						exit;
						if($global['email_confirm'] =='N'){
							redirect(makeLink(array("mod"=>"member", "pg"=>"chkLogin"),"act=scus&user_id=$myId"));
						}else{
							redirect(makeLink(array("mod"=>"member", "pg"=>"chkLogin"),"act=firstreg&user_id=$myId"));
						}
					}
				}
			}
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/regfour.tpl");
			break;
			
			case "add_screquest":
				//print_r($_SESSION);
				if(isset($_SESSION['memberid']))
				{
				$framework->tpl->assign("STD_ADD",1);
				 $guid=$_SESSION['memberid'];
		 $framework->tpl->assign("member",$guid);
				}
				if($_SESSION['guid']=="") $guid=$_SESSION['memberid'];
				else $guid=$_SESSION['guid'];
				include_once(SITE_PATH."/managestudent/custom/include/language/en_us.lang.php"); 
				include_once(SITE_PATH."/managestudent/custom/application/Ext/Language/en_us.lang.ext.php");
				$framework->tpl->assign("LANG_LIST", $app_list_strings["language_list"]);
				
				$framework->tpl->assign("CLASS_LIST", $app_list_strings["classtype_list"]);
				$framework->tpl->assign("AGE_LIST", $app_list_strings["agelevel_list"]);
				$framework->tpl->assign("DAY_LIST", $GLOBALS['app_list_strings']['Days_list']);
				$framework->tpl->assign("TIME_LIST",$GLOBALS['app_list_strings']['Start_time_list']);
				$parentDetails=$db2User->getParentDetails($guid);
				$framework->tpl->assign("PARENT_LIST", $parentDetails);
				$sid=$_REQUEST['sid'];
				
				if($_SERVER['REQUEST_METHOD']=="POST"){
					$new_str="";
					foreach($app_list_strings["Days_list"] as $day){
						if($_POST[$day] == 'Y'){
							$new_str .= $day." - ".$_POST["from_".$day]." To ".$_POST["to_".$day] . "\n";
						}
					
					unset($_POST[$day]);
					unset($_POST["from_".$day]);
					unset($_POST["to_".$day]);
				}
				$_POST['available_daystimes']=$new_str;
				unset($_POST[countryname]);
				$array=$db2User->setArrData($_POST);
				$detail_id=$db2User->studentDetailInsert($sid);
				$semCont_id=$db2User->Insertsemestercontacts($sid,$guid);
				
			   if($_REQUEST['add']==1)
				{
					redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=add_screquest"));
				}else
				{
					if($_SESSION['memberid']){
						redirect(makeLink(array("mod"=>"member", "pg"=>"userHome")));
					}else{
						redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=add_screquest"));
					}
				}	
			  }
			
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/add_sc_request.tpl");
			
			break;	
						
		case "register_four":
			if(isset($_SESSION['memberid']))
				{
				$framework->tpl->assign("STD_ADD",1);
				 $guid=$_SESSION['memberid'];
		 $framework->tpl->assign("member",$guid);
				}
			if($_SERVER['REQUEST_METHOD']=="POST")
			{
				$array=$db2User->setArrData($_POST);
				$myId=$db2User->update_fluent($_SESSION['guid']);
				//unset($_SESSION['guid']);	
				redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=register_four"));	
			 }
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/regfive.tpl");
		break;
		case "parentemail_verify":
		if ($_REQUEST["user_id"])
		{
			$user_det = $db2User->getUserDet($_REQUEST["user_id"]);
			$myId     = $user_det["id"];
			$msg_display  = "<u><b>Member Details</b> </u><br>";
			$msg_display .= "Username: {$user_det["username"]}<br>";
			$msg_display .= "Email: {$user_det["email"]} (<b>Unverified</b>)<br>";

				//setMessage($msg_display,MSG_INFO);
				$mail_header = array();
				$mail_header["from"] = $framework->config['site_name'].'<'.$framework->config['admin_email'].'>';
				$mail_header["to"]   = $user_det["email"];
				$dynamic_vars = array();
				$dynamic_vars["USER_NAME"]  = $user_det["username"];
				$dynamic_vars["FIRST_NAME"] = $user_det["first_name"];
				$dynamic_vars["LAST_NAME"]  = $user_det["last_name"];
				$dynamic_vars["PASSWORD"]   = $user_det["password"];
				$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
				
				$dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"chkLogin"), "fn=activeparent&user_id=$myId")."\">Activate your account now</a>";
				
				$site_var1 = SITE_URL."/index.php";

				$site_var2 = SITE_URL."/";

				$qr_str = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
				$email->send("registration_confirmation",$mail_header,$dynamic_vars);
				redirect(makeLink(array("mod"=>"member", "pg"=>"chkLogin")));	
		}
		
		break;
		case "show_date":
		$semname=$_REQUEST['opt_name'];
		$rs		=$objUser->getfluentSemesterDate($semname);
		echo $rs['date'];	
		exit;
		break;
		
							
		
	case "left_menu":
		$framework->tpl->assign("HOME_MENU", $objCms->homeLink("home"));
		$framework->tpl->display(SITE_PATH."/modules/member/tpl/left_menu.tpl");
		exit;
		break;
	case "nomination":
		checkLogin();
		$arr = array();
		$arr["user_id"]  = $_REQUEST["uid"];
		$arr["nominated_by"] = $_SESSION["memberid"] ;
		$objUser->setArrData($arr);
		if ($objUser->nominate())
		{
			setMessage("Your nomination has been stored");
		}
		else
		{

			setMessage($objUser->getErr());
		}

		redirect(makeLink(array("mod"=>"member","pg"=>"register"), "act=nomination_list"));
		break;
	case "nomination_list":
		$framework->tpl->assign("TITLE_HEAD","Weekly Nomination List");
		$week_id   = date("Wy");
		$iWeekNum  = date("W");
		$iYear     = date("Y");
		$sStartTS = WeekToDate ($iWeekNum, $iYear);
		$sStartDate = date ("F d, Y", $sStartTS);
		$framework->tpl->assign("SDATE",$sStartDate);
		$par = "mod=".$_REQUEST['mod']."&pg=".$_REQUEST['pg']."&act=".$_REQUEST['act'];
		list($rs, $numpad) =$objUser->nominationList($_REQUEST['pageNo'], 5,$par, OBJECT,$orderBy,$week_id);
		$framework->tpl->assign("NOM_LIST",$rs);
		$framework->tpl->assign("NUMPAD",$numpad);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/nomination_list.tpl");
		break;
		
		case "med_billing_det":
		checkLogin();
		$address = $objUser->getAddresses($_SESSION['memberid'],'master');
		$framework->tpl->assign("BILLING_ADDRESS",$address);
		if($_SERVER['REQUEST_METHOD']=="POST")
		{
			$framework->tpl->assign("BILLING_ADDRESS",$_POST);
			unset($_POST["x"],$_POST["y"],$_POST["new_state"],$_POST["cpg"]);
			$_POST["user_id"]   = $_SESSION["memberid"];
			$_POST["addr_type"] = "master";
			$objUser->setArrData($_POST);
			$objUser->insertAddress();

			if($cpg=="oderform3"){
				redirect(makeLink(array("mod"=>"product","pg"=>"list"), "act=oderform&act1=3"));
			}elseif($_REQUEST[mqid]){
				redirect(makeLink(array("mod"=>"healthcare", "pg"=>"medical_records"), "act=med_quest_view&mqid=$_REQUEST[mqid]"));
			}else{
				redirect(makeLink(array("mod"=>"member", "pg"=>"home")));
			}
		}
		if($_REQUEST["cpg"]){
			$framework->tpl->assign("CPG",$_REQUEST["cpg"]);
		}
		$framework->tpl->assign("SUBMITBUTTON1", createImagebutton_Div("Submit","#","pagesub()"));
		$framework->tpl->assign("CANCEL", createImagebutton_Div("Cancel","#","history.go(-1)"));
		$framework->tpl->assign("STATE_LIST",$objUser->listdropdown('6'));
		$framework->tpl->assign("SUBMITBUTTON", createButton("SUBMIT","#","pagesub()"));
		$framework->tpl->assign("RESETBUTTON", createButton("CANCEL","#","history.go(-1)"));
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/billing.tpl");
		break;
		
		case "med_shipping_det":
		checkLogin();
		$address = $objUser->getAddresses($_SESSION['memberid'],'shipping');
		$framework->tpl->assign("SHIPPING_ADDRESS",$address);
		if($_SERVER['REQUEST_METHOD']=="POST")
		{
			$framework->tpl->assign("SHIPPING_ADDRESS",$_POST);
			unset($_POST["x"],$_POST["y"],$_POST["new_state"],$_POST["cpg"]);
			$_POST["user_id"]   = $_SESSION["memberid"];
			$_POST["addr_type"] = "shipping";
			$objUser->setArrData($_POST);
			$objUser->insertAddress();
			if($cpg=="oderform3"){
				redirect(makeLink(array("mod"=>"product","pg"=>"list"), "act=oderform&act1=3"));
			}elseif($_REQUEST[mqid]){
				redirect(makeLink(array("mod"=>"healthcare", "pg"=>"medical_records"), "act=med_quest_view&mqid=$_REQUEST[mqid]"));
			}else{
				redirect(makeLink(array("mod"=>"member", "pg"=>"home")));
			}
		}
		if($_REQUEST["cpg"]){
			$framework->tpl->assign("CPG",$_REQUEST["cpg"]);
		}
		$framework->tpl->assign("SUBMITBUTTON1", createImagebutton_Div("Submit","#","pagesub()"));
		$framework->tpl->assign("CANCEL", createImagebutton_Div("Cancel","#","history.go(-1)"));
		$framework->tpl->assign("STATE_LIST",$objUser->listdropdown('6'));
		$framework->tpl->assign("SUBMITBUTTON", createButton("SUBMIT","#","pagesub()"));
		$framework->tpl->assign("RESETBUTTON", createButton("CANCEL","#","history.go(-1)"));
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/shipping.tpl");
		break;
	case "invalid_store":
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/invalid_store.tpl");
		break;	
		
	case 'retailar_reg':
	

		 if($framework->config['payment_receiver'] == 'store') {
			$PaypalBusinessAccount	=	$PaymentObj->getPayflowLinkDetailsFromStoreName('0');
			$test_mode_paypal		=	$PaymentObj->getPayflowtest_modeFromStoreName('0');
			$rec_billing            =	$PaymentObj->getPayflowRec_billFromStoreName(0);
		} else {
			$PaypalBusinessAccount	=	$typeObj->getPaypalAccountEmail();
			$rec_billing            =	$PaymentObj->getPayflowRec_billFromStoreName(0);
		}
		
		// comment when live - thomas
		//$PaypalBusinessAccount = 'thomas.paulson-facilitator@hotmail.com';
		//$test_mode_paypal = 'Y';
		
		$framework->tpl->assign("PAYPAL_REC_BILL", $rec_billing);
		$framework->tpl->assign("PAYPAL_ACCOUNT_MAIL", $PaypalBusinessAccount);
		$framework->tpl->assign("PAYPAL_TEST_MODE", $test_mode_paypal);
		
		$SessInsertId	=	$objUser->encodeSession();
		$framework->tpl->assign("CURR_SESS_ID", $SessInsertId);
		
		
		  $_SESSION['thnx_setup'] = $_REQUEST['thnx_setup'];
		  
		
		$PayflowDetails		=	$PaymentObj->getPayflowLinkStoreAccountDetails($store_id);
		$framework->tpl->assign("PAYMENT_MODE_DET", $PayflowDetails);
		
		 
		if($_REQUEST['retailer']==1)
		{

			$framework->tpl->assign("TERMS_STROE_REG",$objCms->pageGetByName('terms'));
			$framework->tpl->assign("TITLE_HEAD","Retailer Registration");
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/retailar_signup.tpl");
		}else
		{
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/reg.tpl");
		}

		$framework->tpl->assign("IP_ADDR",$ip);
		$framework->tpl->assign("year_list",$yearlist);
		if($global["inner_change_reg"]=="yes"){
			$framework->tpl->display($global['curr_tpl']."/inner_ch.tpl");
			exit;
		}		
		break;
		
		case 'paypal_fix_update':
			$item_name = $_POST['item_name'];
			$item_number = $_POST['item_number'];
			$payment_status = $_POST['payment_status'];	
			if ($payment_status == 'Completed')
			{
				$split_item = split("_",$item_number);
				$user_id = $split_item[0];
				$sub_id = $split_item[1];
				$objUser->updateSubscriptionEnddate($sub_id,$user_id);
				$objUser->deleteUserFromSubscriptionFix($user_id);
			}
		break;
		
		case 'payment_notify':
		
		$item_name = $_POST['item_name'];
		$item_number = $_POST['item_number'];
		$payment_status = $_POST['payment_status'];		 
		
		if ($payment_status == 'Completed') {
			
			
			$objUser->decodeSession($item_number);
			$memberArr=array();
			
			$memberArr['first_name'] = $_SESSION['first_name'];
			$memberArr['last_name']  = $_SESSION['last_name'];
			$memberArr['email']  	 = $_SESSION['email'];
			$memberArr['username']   = $_SESSION['username'];
			$memberArr['password']   = $_SESSION['password'];
			$memberArr['address1']   = $_SESSION['address1'];
			$memberArr['address2']   = $_SESSION['address2'];
		
			$memberArr['city']   	 = $_SESSION['city'];
			$memberArr['country'] 	 = $_SESSION['country'];
			$memberArr['state']  	 = $_SESSION['state'];
			
			$memberArr['postalcode'] = $_SESSION['postalcode'];
			$memberArr['telephone']  = $_SESSION['telephone'];
			$memberArr['from_store'] = 0;
			$memberArr['mem_type']   = 2;
			$memberArr['newsletter'] = 'N';
			$memberArr['addr_type']  = 'master'; //Default address type is master
			
			$memberArr['joindate']   = date("Y-m-d H:i:s");
			
		
			$memberArr['reg_pack']   = $_SESSION['reg_pack'];
			$memberArr['sub_pack']   = $_SESSION['sub_pack'];
			$memberArr['addr_type']  = 'master';
			$memberArr['amt_paid']   = 'Y';
			
			$objUser->setArrData($memberArr);
			$myId=$objUser->insert();
			
			$arr = array();
			$arr["user_id"] = $myId;
			$arr["subscr_id"] = $_SESSION['sub_pack'];
			$objUser->setArrData($arr);
			$objUser->addSubscription();
			
			
			if($myId)
			{				
			$strArr = array();
			$strArr["name"] 			= $_SESSION["store_name"];
			$strArr["heading"] 			= $_SESSION["heading"];
			$strArr["user_id"]		 	= $myId;
			$strArr['heading1']			= $_SESSION['heading1'];
			$strArr['heading2']			= $_SESSION['heading2'];
			$strArr['active']			= 'Y';
			
			$str_id = $store->addStore($strArr);
			
			$mail_header = array();
			$mail_header["from"]		 = $framework->config['site_name'].'<'.$framework->config['admin_email'].'>';
			$mail_header["to"]  		 = $_SESSION['email'];
			
			$dynamic_vars = array();
			$dynamic_vars["USER_NAME"]  = $_SESSION['username'];
			$dynamic_vars["FIRST_NAME"] = $_SESSION['first_name'];
			$dynamic_vars["LAST_NAME"]  = $_SESSION['last_name'];
			$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
			$dynamic_vars["PASSWORD"]   =  $_SESSION['password'];
			
			
		    $dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".$_SESSION["store_name"]."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";
			
				$dynamic_vars["STORE_LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".$_SESSION["store_name"]."\">".SITE_URL."/".$_SESSION["store_name"]."</a>";
			
			$dynamic_vars["STORE_LINK_MANAGE"]       = "<a target='_blank' href=\"".SITE_URL."/".$_SESSION["store_name"].'/manage'."\">".SITE_URL."/".$_SESSION["store_name"].'/manage'."</a>";
			
			$email->send("store_creation_confirmation",$mail_header,$dynamic_vars);
			
			}
			
		}
	
	break;	
	
	
	
	
	
	
	
	
	
	
	
	case 're_reg':
	
	
		/* if($framework->config['payment_receiver'] == 'store') {
			$PaypalBusinessAccount	=	$PaymentObj->getPayflowLinkDetailsFromStoreName('0');
			$test_mode_paypal		=	$PaymentObj->getPayflowtest_modeFromStoreName('0');
			$rec_billing            =	$PaymentObj->getPayflowRec_billFromStoreName(0);
		} else {
			$PaypalBusinessAccount	=	$typeObj->getPaypalAccountEmail();
			$rec_billing            =	$PaymentObj->getPayflowRec_billFromStoreName(0);
		}
		$framework->tpl->assign("PAYPAL_REC_BILL", $rec_billing);
		$framework->tpl->assign("PAYPAL_ACCOUNT_MAIL", $PaypalBusinessAccount);
		$framework->tpl->assign("PAYPAL_TEST_MODE", $test_mode_paypal);
		
		$SessInsertId	=	$objUser->encodeSession();
		$framework->tpl->assign("CURR_SESS_ID", $SessInsertId);
		
		
		  $_SESSION['thnx_setup'] = $_REQUEST['thnx_setup'];
		  
		
		$PayflowDetails		=	$PaymentObj->getPayflowLinkStoreAccountDetails($store_id);
		$framework->tpl->assign("PAYMENT_MODE_DET", $PayflowDetails);
		*/
		if($_SERVER['REQUEST_METHOD']=="POST")
		{
		   $memberArr=array();
			
			$memberArr['first_name'] = $_POST['first_name'];
			$memberArr['last_name']  = $_POST['last_name'];
			$memberArr['email']  	 = $_POST['email'];
			$memberArr['username']   = $_POST['username'];
			$memberArr['password']   = $_POST['password'];
			$memberArr['address1']   = $_POST['address1'];
			$memberArr['address2']   = $_POST['address2'];
		
			$memberArr['city']   	 = $_POST['city'];
			$memberArr['country'] 	 = $_POST['country'];
			$memberArr['state']  	 = $_POST['state'];
			
			$memberArr['postalcode'] = $_POST['postalcode'];
			$memberArr['telephone']  = $_POST['telephone'];
			$memberArr['from_store'] = 0;
			$memberArr['mem_type']   = 2;
			$memberArr['newsletter'] = 'N';
			$memberArr['addr_type']  = 'master'; //Default address type is master
			
			$memberArr['joindate']   = date("Y-m-d H:i:s");
			
		
			$memberArr['reg_pack']   = $_POST['reg_pack'];
			$memberArr['sub_pack']   = $_POST['sub_pack'];
			$memberArr['addr_type']  = 'master';
			$memberArr['amt_paid']   = 'Y';
			
			$objUser->setArrData($memberArr);
			 $myId=$objUser->insert();
			
			$arr = array();
			$arr["user_id"] = $myId;
			$arr["subscr_id"] = $_POST['sub_pack'];
			$objUser->setArrData($arr);
			$objUser->addSubscription();
			
			
			if($myId)
			{				
			$strArr = array();
			$strArr["name"] 			= $_POST["store_name"];
			$strArr["heading"] 			= $_POST["heading"];
			$strArr["user_id"]		 	= $myId;
			$strArr['heading1']			= $_POST['heading1'];
			$strArr['heading2']			= $_POST['heading2'];
			$strArr['active']			= 'Y';
			
			$str_id = $store->addStore($strArr);
			
			$mail_header = array();
			$mail_header["from"]		 = $framework->config['site_name'].'<'.$framework->config['admin_email'].'>';
			$mail_header["to"]  		 = $_POST['email'];
			
			$dynamic_vars = array();
			$dynamic_vars["USER_NAME"]  = $_POST['username'];
			$dynamic_vars["FIRST_NAME"] = $_POST['first_name'];
			$dynamic_vars["LAST_NAME"]  = $_POST['last_name'];
			$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
			$dynamic_vars["PASSWORD"]   =  $_POST['password'];
			$framework->tpl->assign("FIRST_NAME",$_POST['first_name']);
					$framework->tpl->assign("LAST_NAME", $_POST['last_name']);
					
					$framework->tpl->assign("SURL",$_POST["store_name"]);
					$framework->tpl->assign("AMOUNT",$amount);
			
		    $dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".$_POST["store_name"]."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";
			
				$dynamic_vars["STORE_LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".$_POST["store_name"]."\">".SITE_URL."/".$_POST["store_name"]."</a>";
			
			$dynamic_vars["STORE_LINK_MANAGE"]       = "<a target='_blank' href=\"".SITE_URL."/".$_POST["store_name"].'/manage'."\">".SITE_URL."/".$_POST["store_name"].'/manage'."</a>";
			
			$email->send("store_creation_confirmation",$mail_header,$dynamic_vars);
			
			}
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/thnk_rereg.tpl");
			$framework->tpl->display($global['curr_tpl']."/inner.tpl");
			exit;
		}
		 
		
			$framework->tpl->assign("TERMS_STROE_REG",$objCms->pageGetByName('terms'));
			$framework->tpl->assign("TITLE_HEAD","Retailer Registration");
			$framework->tpl->assign("SUBMITBUTTON1", createImagebutton_Div("Submit","",""));
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/re_registration.tpl");
		
		$framework->tpl->assign("IP_ADDR",$ip);
		$framework->tpl->assign("year_list",$yearlist);
		$framework->tpl->display($global['curr_tpl']."/inner.tpl");
			exit;
	
	
	
	
	
	
	case 'payment_succes':
	
		
		$test_mode_paypal		=	$PaymentObj->getPayflowtest_modeFromStoreName('0');
		
		
		$req = 'cmd=_notify-synch';
		
		$tx_token = $_GET['tx'];
		$auth_token = "XmO8yepYJfO5QgRnshWxywyAPC5COu7JwjpPbyBunBewZ5DVrXu_s8r2JR4";
		$req .= "&tx=$tx_token&at=$auth_token";
		
		// post back to PayPal system to validate
		/*
		$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
		*/
		
		$test_mode_paypal = 'Y';
		// edited by thomas 23 Sept 2013
		$header .= "POST /cgi-bin/webscr HTTP/1.1\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

		if($test_mode_paypal=='Y'){
			$header .="Host: www.sandbox.paypal.com\r\n";
			$fp = fsockopen ('www.sandbox.paypal.com', 80, $errno, $errstr, 30);
		}
		else{			
			$header .="Host: www.paypal.com\r\n"; 
			$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);
		}
		$header .="Connection: close\r\n\r\n";
		//endof edited by thomas 23 Sept 2013
		
		
		
		if($test_mode_paypal=='Y')
		$fp = fsockopen ('www.sandbox.paypal.com', 80, $errno, $errstr, 30);
		else
		$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);

		if (!$fp) {
		// HTTP ERROR
		}
		else{
			fputs ($fp, $header . $req);
			// read the body data
			$res = '';
			$headerdone = false;
			while (!feof($fp)) {
				$line = fgets ($fp, 1024);
				if (strcmp($line, "\r\n") == 0) {
				// read the header
				$headerdone = true;
				}
				else if ($headerdone)
				{
				// header has been read. now read the contents
				$res .= $line;
				}
			}

			// parse the data
			$lines = explode("\n", $res);
			$keyarray = array();
			if (strcmp ($lines[0], "SUCCESS") == 0) {
				for ($i=1; $i<count($lines);$i++){
				list($key,$val) = explode("=", $lines[$i]);
				$keyarray[urldecode($key)] = urldecode($val);
				}
				// check the payment_status is Completed
				// check that txn_id has not been previously processed
				// check that receiver_email is your Primary PayPal email
				// check that payment_amount/payment_currency are correct
				// process payment
				
				$firstname = $keyarray['first_name'];
				$lastname = $keyarray['last_name'];
				$itemname = $keyarray['item_name'];
				$amount = $keyarray['payment_gross'];
				$payment_status = $keyarray['payment_status'];
				
				
				 if($payment_status=='Completed'){
				 
				 	$framework->tpl->assign("FIRST_NAME",$firstname);
					$framework->tpl->assign("LAST_NAME",$lastname);
					$framework->tpl->assign("ITEM_NAME",$itemname);
					$framework->tpl->assign("AMOUNT",$amount);
				 
				 }
		  }
			else if (strcmp ($lines[0], "FAIL") == 0) {
				// log for manual investigation
			}
		}
			
			
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/thnx.tpl");
		break;
		
		
		
		case 'validate_payment':		
		
		$test_mode_paypal =	$PaymentObj->getPayflowtest_modeFromStoreName('0');
		
		$item_name = $_POST['item_name'];
		$item_number = $_POST['item_number'];
		$payment_status = $_POST['payment_status'];
		$payment_amount = $_POST['mc_gross'];
		$payment_currency = $_POST['mc_currency'];
		$txn_id = $_POST['txn_id'];
		$receiver_email = $_POST['receiver_email'];
		$payer_email = $_POST['payer_email'];
		
		$subs_renewal=1; //for checking the subscription renewal



		//$test_mode_paypal = 'Y';	// comment when live	
		
		
			// STEP 1: read POST data
			 
			// Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
			// Instead, read raw POST data from the input stream. 
			$raw_post_data = file_get_contents('php://input');
			$raw_post_array = explode('&', $raw_post_data);
			$myPost = array();
			foreach ($raw_post_array as $keyval) {
			  $keyval = explode ('=', $keyval);
			  if (count($keyval) == 2)
				 $myPost[$keyval[0]] = urldecode($keyval[1]);
			}
			// read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
			$req = 'cmd=_notify-validate';
			if(function_exists('get_magic_quotes_gpc')) {
			   $get_magic_quotes_exists = true;
			} 
			foreach ($myPost as $key => $value) {        
			   if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
					$value = urlencode(stripslashes($value)); 
			   } else {
					$value = urlencode($value);
			   }
			   $req .= "&$key=$value";
			}
			 
			$header .= "POST /cgi-bin/webscr HTTP/1.1\r\n";
			$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";	


			// Step 2: POST IPN data back to PayPal to validate
			
			if($test_mode_paypal == 'Y') 
				$ch = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
			else
				$ch = curl_init('https://www.paypal.com/cgi-bin/webscr');			
			
			$header .="Connection: close\r\n\r\n";

			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
			 
			// In wamp-like environments that do not come bundled with root authority certificates,
			// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set 
			// the directory path of the certificate as shown below:
			// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
			if( !($res = curl_exec($ch)) ) {
				// error_log("Got " . curl_error($ch) . " when processing IPN data");

				curl_close($ch);
				exit;
			}
			curl_close($ch);
					
		
	// inspect IPN validation result and act accordingly
 
	if (strcmp ($res, "VERIFIED") == 0) {
    // The IPN is verified, process it
	
		//mail('thomas@newagesmb.com','inside2 ipn','body');

         //Add log entry

         $new_status = '';
         $brief_description = '';
		 
		 if ($_POST['payment_status'] == "Reversed") {
			$new_status = "Chargeback";
			$brief_description = 'Chargeback notice received.  We will process this immediately.';
		 } elseif ($_POST['payment_status'] == "Denied" || $_POST['payment_status'] == "Refunded") {
			$new_status = "Refunded";
			$brief_description = 'We have refunded your payment.';
		 } elseif ($_POST['payment_status'] == "Failed" || $_POST['payment_status'] == "Voided") {
			$new_status = "Voided";
		 if ($_GET['status'] == 'void') {
			  $brief_description = 'Invoice voided by admin.';
			} else {
			  $brief_description = 'The transaction did not clear with PayPal and PayPal has reported that it will not clear.';
			}
		  } elseif ($_POST['txn_type'] == "eot") {
			$new_status = "Completed";
			$brief_description = 'PayPal has reported that the installment plan is completed.';
		  } elseif ($_POST['txn_type'] == "subscr_cancel") {
			$new_status = "Refund";
			$brief_description = 'Your payment plan has been cancelled.';
		  } elseif ($_POST['txn_type'] == "subscr_failed") {
			$new_status = "Refund";
			$brief_description = 'PayPal has reported that the installment payment has failed.';
		  } elseif ($_POST['txn_type'] == "subscr_signup") {
			$new_status = "Other";
			$brief_description = 'PayPal notified us that you signed up for an installment plan. Invoice updated to include installment convenience charge.';
		  } elseif ($_POST['txn_type'] == "subscr_payment") {
			$new_status = "Completed";
			$brief_description = 'We have received an installment paym.ent of $'.$_POST['payment_gross'].'.';
		  } elseif ($_POST['payment_status'] == "Pending") {
			$new_status = "Pending";
			$brief_description = 'Your order has been processed and is pending.';
		  } elseif ($_POST['payment_status'] == "Completed" || $_POST['payment_status'] == "Processed") {
			$new_status = "Completed";
			$brief_description = 'We have received payment.';
		  } elseif (strlen($new_status) == 0) {
			$new_status = "Other";
			$brief_description = 'The transaction reported a condition we did not expect.  Please contact customer support at support at teratask dot com.';
		  }

		 $item_number							=	$_POST['item_number'];

		 $ipn_status_array=array();
		 
		 
		 $ipn_status_array['log_time']			=  date("Y-m-d H:i:s");
		 $ipn_status_array['payment_status']    = $_POST['payment_status'];
		 $ipn_status_array['txn_id']			= $_POST['txn_id'];
		 $ipn_status_array['receiver_email']    = $_POST['receiver_email'];
		 $ipn_status_array['payment_amount']	= $_POST['payment_gross'];
		 $ipn_status_array['payment_currency']	= $_POST['mc_currency'];
		 $ipn_status_array['payer_email']		= $_POST['payer_email'];		 
		 
		 
		 $ipn_status_array['txn_type']			= $_POST['txn_type'];
		 $ipn_status_array['recurring']		    = $_POST['recurring'];
		 $ipn_status_array['reattempt']		    = $_POST['reattempt'];
		 $ipn_status_array['recur_times']		= $_POST['recur_times'];
		 $ipn_status_array['subscr_id']			= $_POST['subscr_id'];
		 
		 $ipn_status_array['reason_code']		= $_POST['reason_code'];
		 $ipn_status_array['pending_reason']	= $_POST['pending_reason'];
		 $ipn_status_array['payment_type']  	= $_POST['payment_type'];
		  
		  
		 $ipn_status_array['item_name']			= $_POST['item_name'];
		 $ipn_status_array['item_number']		= $_POST['item_number'];
		 
		 $ipn_status_array['description']		= $brief_description;
		 $ipn_status_array['status']			= $new_status;
		 $ipn_status_array['from_ipn']			= 'signup';
		 $regSessionValues						=	" store_name->".$_SESSION["store_name"]." first_name->".$_SESSION['first_name']." last_name->".$_SESSION['last_name']." email->".$_SESSION['email']." username->".$_SESSION['username']." password->".$_SESSION['password']." address1->".$_SESSION['address1']." city->".$_SESSION['city']." country->".$_SESSION['country']." state->".$_SESSION['state']."";
		 $ipn_status_array['reg_session_values']	= $regSessionValues;
		 
		 $objUser->db->insert("ipn_log", $ipn_status_array);
		 
		// check the payment_status is Completed
		// check that txn_id has not been previously processed
		// check that receiver_email is your Primary PayPal email
		// check that payment_amount/payment_currency are correct
		// process payment
		
		 $status_array=array('Refunded','Denied','Failed','Voided');
				 
		if (!in_array($payment_status, $status_array) && strlen($payment_status) > 0 && $_POST['txn_type'] == "subscr_payment")
		{
					$objUser->decodeSession($item_number);
					$memberArr=array();
					
				if($objUser->validStore($_SESSION["store_name"]) && $_SESSION["store_name"] !='')
				{	
					$memberArr['first_name'] = $_SESSION['first_name'];
					$memberArr['last_name']  = $_SESSION['last_name'];
					$memberArr['email']  	 = $_SESSION['email'];
					$memberArr['username']   = $_SESSION['username'];
					$memberArr['password']   = $_SESSION['password'];
					$memberArr['address1']   = $_SESSION['address1'];
					$memberArr['address2']   = $_SESSION['address2'];
				
					$memberArr['city']   	 = $_SESSION['city'];
					$memberArr['country'] 	 = $_SESSION['country'];
					$memberArr['state']  	 = $_SESSION['state'];
					
					$memberArr['postalcode'] = $_SESSION['postalcode'];
					$memberArr['telephone']  = $_SESSION['telephone'];
					$memberArr['from_store'] = 0;
					$memberArr['mem_type']   = 2;
					$memberArr['newsletter'] = 'N';
					$memberArr['addr_type']  = 'master'; //Default address type is master
					
					$memberArr['joindate']   = date("Y-m-d H:i:s");
					
				
					$memberArr['reg_pack']   = $_SESSION['reg_pack'];
					$memberArr['sub_pack']   = $_SESSION['sub_pack'];
					$memberArr['addr_type']  = 'master';
					$memberArr['amt_paid']   = 'Y';
					
					$objUser->setArrData($memberArr);
					$myId=$objUser->insert2();
					
					if($myId)
					{	
						$arr = array();
						$arr["user_id"] = $myId;
						$arr["subscr_id"] = $_SESSION['sub_pack'];
						$objUser->setArrData($arr);
						$objUser->addSubscription();
					}
			
			
				if($myId)
				{				
					$strArr = array();
					$strArr["name"] 			= $_SESSION["store_name"];
					$strArr["heading"] 			= $_SESSION["heading"];
					$strArr["user_id"]		 	= $myId;
					$strArr['heading1']			= $_SESSION['heading1'];
					$strArr['heading2']			= $_SESSION['heading2'];
					$strArr['active']			= 'Y';
					$strArr['txn_id']			=  $item_number;
					
					$str_id = $store->addStore($strArr);
					
					if($str_id)
					{
					$subs_renewal=0; //store creation
					
					$mail_header = array();
					$mail_header["from"]		 = $framework->config['site_name'].'<'.$framework->config['admin_email'].'>';
					$mail_header["to"]  		 = $_SESSION['email'];
					
					$dynamic_vars = array();
					$dynamic_vars["USER_NAME"]  = $_SESSION['username'];
					$dynamic_vars["FIRST_NAME"] = $_SESSION['first_name'];
					$dynamic_vars["LAST_NAME"]  = $_SESSION['last_name'];
					$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
					$dynamic_vars["PASSWORD"]   =  $_SESSION['password'];
					
					$dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".$_SESSION["store_name"]."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";
					
					$dynamic_vars["STORE_LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".$_SESSION["store_name"]."\">".SITE_URL."/".$_SESSION["store_name"]."</a>";
					
					$dynamic_vars["STORE_LINK_MANAGE"]       = "<a target='_blank' href=\"".SITE_URL."/".$_SESSION["store_name"].'/manage'."\">".SITE_URL."/".$_SESSION["store_name"].'/manage'."</a>";
					
					$email->send("store_creation_confirmation",$mail_header,$dynamic_vars);
				   }	
				}
			  }
			}
			
			// chkeck the payemnt staus is completed and tranaction type is subscr_payment
			/// adarsh 25sep 2009
			if($_POST['txn_type'] == "subscr_payment")
			{
				if($subs_renewal==1) // checking subscription renewal 
				{							
					$store_det = $objUser->getStoreByTxnId($item_number);
					if(count($store_det)==0){
						$store_det = $objUser->getStoreByTxnId($_POST['subscr_id']);
					}
					if(count($store_det) >0)
					{
						 $log_det=$objUser->ipnLogDet($txn_id);
						 if(count($log_det)==1)
						 {
						 	$objUser->updateSubscriptionDet($store_det->user_id);
						 }
					}
				}
				$objUser->changeStopSubsStatus($store_det->user_id);
			}
			
			
			if($_POST['txn_type'] == "subscr_eot")
			{
				if($subs_renewal==1) // checking subscription renewal 
				{							
					$store_det = $objUser->getStoreByTxnId($item_number);
					if(count($store_det)==0){
						$store_det = $objUser->getStoreByTxnId($_POST['subscr_id']);
					}
					if(count($store_det) >0)
					{
						 $objUser->updateSubscriptionEot($store_det->user_id);
					}
				}
			}
			
			
		}
		else if (strcmp ($res, "INVALID") == 0) {
		// log for manual investigation
		}
		
	break;	
	
	
	case 'payment_completed':
	
		$test_mode_paypal =	$PaymentObj->getPayflowtest_modeFromStoreName('0');
		
		$req = 'cmd=_notify-synch';
		$tx_token = $_GET['tx'];
		if($test_mode_paypal=='Y'){
			//for sanbox testing account auth_token
			$auth_token = "YoNURn3KKUTNW-c8uHVw9we95ihBQrmLzV0dO7K8_dQSlgl1CyZnb365qL8";
		}
		else{
		 	// clinet paypal account PDT  auth_token
			$auth_token = "XmO8yepYJfO5QgRnshWxywyAPC5COu7JwjpPbyBunBewZ5DVrXu_s8r2JR4";
		}
		$req .= "&tx=$tx_token&at=$auth_token";
		
		// post back to PayPal system to validate
		/*
		$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
		
		if($test_mode_paypal=='Y')
		$fp = fsockopen ('www.sandbox.paypal.com', 80, $errno, $errstr, 30);
		else
		$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
		*/
		
		$test_mode_paypal = 'Y';		
		// edited by thomas 23 Sept 2013
		$header .= "POST /cgi-bin/webscr HTTP/1.1\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

		if($test_mode_paypal=='Y'){
			$header .="Host: www.sandbox.paypal.com\r\n";
			$fp = fsockopen ('www.sandbox.paypal.com', 80, $errno, $errstr, 30);
		}
		else{			
			$header .="Host: www.paypal.com\r\n"; 
			$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);
		}
		$header .="Connection: close\r\n\r\n";
		//endof edited by thomas 23 Sept 2013
		
		

		if (!$fp) {
		// HTTP ERROR
		}
		else{
			fputs ($fp, $header . $req);
			// read the body data
			$res = '';
			$headerdone = false;
			while (!feof($fp)) {
				$line = fgets ($fp, 1024);
				if (strcmp($line, "\r\n") == 0) {
				// read the header
				$headerdone = true;
				}
				else if ($headerdone)
				{
				// header has been read. now read the contents
				$res .= $line;
				}
			}

			// parse the data
			$lines = explode("\n", $res);
			$keyarray = array();
			if (strcmp ($lines[0], "SUCCESS") == 0) {
				for ($i=1; $i<count($lines);$i++){
				list($key,$val) = explode("=", $lines[$i]);
				$keyarray[urldecode($key)] = urldecode($val);
				}
				
				// check the payment_status is Completed
				// check that txn_id has not been previously processed
				// check that receiver_email is your Primary PayPal email
				// check that payment_amount/payment_currency are correct
				// process payment
				
				
				$firstname 		= $keyarray['first_name'];
				$lastname 		= $keyarray['last_name'];
				$itemname 		= $keyarray['item_name'];
				$amount 		= $keyarray['payment_gross'];
				$payment_status = $keyarray['payment_status'];
				$item_number 	= $keyarray['item_number'];
				$txn_id 		= $keyarray['txn_id'];
				$payer_email 	= $keyarray['payer_email'];
				
				$status_array=array('Refunded','Denied','Failed','Voided');
				 
				if (!in_array($payment_status, $status_array) && strlen($payment_status) > 0)
				{
					$objUser->decodeSession($item_number);
					$memberArr=array();
			
				if(!$objUser->validUsername($_SESSION['username']))
				{
					$memberArr['first_name'] = $_SESSION['first_name'];
					$memberArr['last_name']  = $_SESSION['last_name'];
					$memberArr['email']  	 = $_SESSION['email'];
					$memberArr['username']   = $_SESSION['username'];
					$memberArr['password']   = $_SESSION['password'];
					$memberArr['address1']   = $_SESSION['address1'];
					$memberArr['address2']   = $_SESSION['address2'];
				
					$memberArr['city']   	 = $_SESSION['city'];
					$memberArr['country'] 	 = $_SESSION['country'];
					$memberArr['state']  	 = $_SESSION['state'];
					
					$memberArr['postalcode'] = $_SESSION['postalcode'];
					$memberArr['telephone']  = $_SESSION['telephone'];
					$memberArr['from_store'] = 0;
					$memberArr['mem_type']   = 2;
					$memberArr['newsletter'] = 'N';
					$memberArr['addr_type']  = 'master'; //Default address type is master
					
					$memberArr['joindate']   = date("Y-m-d H:i:s");
					
				
					$memberArr['reg_pack']   = $_SESSION['reg_pack'];
					$memberArr['sub_pack']   = $_SESSION['sub_pack'];
					$memberArr['addr_type']  = 'master';
					$memberArr['amt_paid']   = 'Y';
					
					$objUser->setArrData($memberArr);
					$myId=$objUser->insert2();
					
					
					if($myId)
					{	
						$arr = array();
						$arr["user_id"] = $myId;
						$arr["subscr_id"] = $_SESSION['sub_pack'];
						$objUser->setArrData($arr);
						$objUser->addSubscription();
					}
			
			
				if($myId)
				{				
					$strArr = array();
					$strArr["name"] 			= $_SESSION["store_name"];
					$strArr["heading"] 			= $_SESSION["heading"];
					$strArr["user_id"]		 	= $myId;
					$strArr['heading1']			= $_SESSION['heading1'];
					$strArr['heading2']			= $_SESSION['heading2'];
					$strArr['active']			= 'Y';
					$strArr['txn_id']			=  $item_number;
					
					$str_id = $store->addStore($strArr);
					
					if($str_id)
					{
					
					$mail_header = array();
					$mail_header["from"]		 = $framework->config['site_name'].'<'.$framework->config['admin_email'].'>';
					$mail_header["to"]  		 = $_SESSION['email'];
					
					$dynamic_vars = array();
					$dynamic_vars["USER_NAME"]  = $_SESSION['username'];
					$dynamic_vars["FIRST_NAME"] = $_SESSION['first_name'];
					$dynamic_vars["LAST_NAME"]  = $_SESSION['last_name'];
					$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
					$dynamic_vars["PASSWORD"]   =  $_SESSION['password'];
					
					
					$dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".$_SESSION["store_name"]."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";
					
						$dynamic_vars["STORE_LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".$_SESSION["store_name"]."\">".SITE_URL."/".$_SESSION["store_name"]."</a>";
					
					$dynamic_vars["STORE_LINK_MANAGE"]       = "<a target='_blank' href=\"".SITE_URL."/".$_SESSION["store_name"].'/manage'."\">".SITE_URL."/".$_SESSION["store_name"].'/manage'."</a>";
					
					$email->send("store_creation_confirmation",$mail_header,$dynamic_vars);
				
				   }	
				}
					
					
				}	
					if($payment_status == 'Completed'){
						$framework->tpl->assign("TXN_ID",$txn_id);
						$framework->tpl->assign("ITEM_NAME",$itemname);
						$framework->tpl->assign("AMOUNT",$amount);
						$framework->tpl->assign("PAYER_EMAIL",$payer_email);
					}
					
			}
		  }
			else if (strcmp ($lines[0], "FAIL") == 0) {
				// log for manual investigation
			}
		}
			
			
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/signup_thnx.tpl");
		break;	
		
				
		case 'validate_payment2':
		
		
		$test_mode_paypal =	$PaymentObj->getPayflowtest_modeFromStoreName('0');
		
		$item_name = $_POST['item_name'];
		$item_number = $_POST['item_number'];
		$payment_status = $_POST['payment_status'];
		$payment_amount = $_POST['mc_gross'];
		$payment_currency = $_POST['mc_currency'];
		$txn_id = $_POST['txn_id'];
		$receiver_email = $_POST['receiver_email'];
		$payer_email = $_POST['payer_email'];
		
		$subs_renewal=1; //for checking the subscription renewal
		
         //Add log entry
		 
         $new_status = '';
         $brief_description = '';
		 
		 if ($_POST['payment_status'] == "Reversed") {
			$new_status = "Chargeback";
			$brief_description = 'Chargeback notice received.  We will process this immediately.';
		 } elseif ($_POST['payment_status'] == "Denied" || $_POST['payment_status'] == "Refunded") {
			$new_status = "Refunded";
			$brief_description = 'We have refunded your payment.';
		 } elseif ($_POST['payment_status'] == "Failed" || $_POST['payment_status'] == "Voided") {
			$new_status = "Voided";
		 if ($_GET['status'] == 'void') {
			  $brief_description = 'Invoice voided by admin.';
			} else {
			  $brief_description = 'The transaction did not clear with PayPal and PayPal has reported that it will not clear.';
			}
		  } elseif ($_POST['txn_type'] == "eot") {
			$new_status = "Completed";
			$brief_description = 'PayPal has reported that the installment plan is completed.';
		  } elseif ($_POST['txn_type'] == "subscr_cancel") {
			$new_status = "Refund";
			$brief_description = 'Your payment plan has been cancelled.';
		  } elseif ($_POST['txn_type'] == "subscr_failed") {
			$new_status = "Refund";
			$brief_description = 'PayPal has reported that the installment payment has failed.';
		  } elseif ($_POST['txn_type'] == "subscr_signup") {
			$new_status = "Other";
			$brief_description = 'PayPal notified us that you signed up for an installment plan. Invoice updated to include installment convenience charge.';
		  } elseif ($_POST['txn_type'] == "subscr_payment") {
			$new_status = "Completed";
			$brief_description = 'We have received an installment payment of $'.$_POST['payment_gross'].'.';
		  } elseif ($_POST['payment_status'] == "Pending") {
			$new_status = "Pending";
			$brief_description = 'Your order has been processed and is pending.';
		  } elseif ($_POST['payment_status'] == "Completed" || $_POST['payment_status'] == "Processed") {
			$new_status = "Completed";
			$brief_description = 'We have received payment.';
		  } elseif (strlen($new_status) == 0) {
			$new_status = "Other";
			$brief_description = 'The transaction reported a condition we did not expect.  Please contact customer support at support at teratask dot com.';
		  }
		 
		 $ipn_status_array=array();
		 
		 
		 $ipn_status_array['log_time']			=  date("Y-m-d H:i:s");
		 $ipn_status_array['payment_status']    = $payment_status;
		 $ipn_status_array['txn_id']			= $txn_id;
		 $ipn_status_array['receiver_email']    = $receiver_email;
		 $ipn_status_array['payment_amount']	= $payment_amount;
		 $ipn_status_array['payment_currency']	= $payment_currency;
		 $ipn_status_array['payer_email']		= $payer_email;
		 
		 $ipn_status_array['txn_type']			= $_POST['txn_type'];
		 $ipn_status_array['recurring']		    = $_POST['recurring'];
		 $ipn_status_array['reattempt']		    = $_POST['reattempt'];
		 $ipn_status_array['recur_times']		= $_POST['recur_times'];
		 $ipn_status_array['subscr_id']			= $_POST['subscr_id'];
		 
		 $ipn_status_array['reason_code']		= $_POST['reason_code'];
		 $ipn_status_array['pending_reason']	= $_POST['pending_reason'];
		 $ipn_status_array['payment_type']  	= $_POST['payment_type'];
		  
		  
		 $ipn_status_array['item_name']			= $item_name;
		 $ipn_status_array['item_number']		= $item_number;
		 
		 $ipn_status_array['description']		= $brief_description;
		 $ipn_status_array['status']			= $new_status;
		 $ipn_status_array['from_ipn']			= 'signup';
		 $regSessionValues						=	" store_name->".$_SESSION["store_name"]." first_name->".$_SESSION['first_name']." last_name->".$_SESSION['last_name']." email->".$_SESSION['email']." username->".$_SESSION['username']." password->".$_SESSION['password']." address1->".$_SESSION['address1']." city->".$_SESSION['city']." country->".$_SESSION['country']." state->".$_SESSION['state']."";
		 $ipn_status_array['reg_session_values']	= $regSessionValues;
		 
		 $objUser->db->insert("ipn_log", $ipn_status_array);
		 
		// check the payment_status is Completed

		// check that txn_id has not been previously processed
		// check that receiver_email is your Primary PayPal email
		// check that payment_amount/payment_currency are correct
		// process payment
		
		 $status_array=array('Refunded','Denied','Failed','Voided');
				 
		if (!in_array($payment_status, $status_array) && strlen($payment_status) > 0 && $_POST['txn_type'] == "subscr_payment")
		{
					$objUser->decodeSession($item_number);
					$memberArr=array();
					
				if($objUser->validStore($_SESSION["store_name"]) && $_SESSION["store_name"] !='' )
				{	
					$memberArr['first_name'] = $_SESSION['first_name'];
					$memberArr['last_name']  = $_SESSION['last_name'];
					$memberArr['email']  	 = $_SESSION['email'];
					$memberArr['username']   = $_SESSION['username'];
					$memberArr['password']   = $_SESSION['password'];
					$memberArr['address1']   = $_SESSION['address1'];
					$memberArr['address2']   = $_SESSION['address2'];
				
					$memberArr['city']   	 = $_SESSION['city'];
					$memberArr['country'] 	 = $_SESSION['country'];
					$memberArr['state']  	 = $_SESSION['state'];
					
					$memberArr['postalcode'] = $_SESSION['postalcode'];
					$memberArr['telephone']  = $_SESSION['telephone'];
					$memberArr['from_store'] = 0;
					$memberArr['mem_type']   = 2;
					$memberArr['newsletter'] = 'N';
					$memberArr['addr_type']  = 'master'; //Default address type is master
					
					$memberArr['joindate']   = date("Y-m-d H:i:s");
					
				
					$memberArr['reg_pack']   = $_SESSION['reg_pack'];
					$memberArr['sub_pack']   = $_SESSION['sub_pack'];
					$memberArr['addr_type']  = 'master';
					$memberArr['amt_paid']   = 'Y';
					
					$objUser->setArrData($memberArr);
					$myId=$objUser->insert2();
					
					if($myId)
					{	
						$arr = array();
						$arr["user_id"] = $myId;
						$arr["subscr_id"] = $_SESSION['sub_pack'];
						$objUser->setArrData($arr);
						$objUser->addSubscription();
					}
			
			
				if($myId)
				{				
					$strArr = array();
					$strArr["name"] 			= $_SESSION["store_name"];
					$strArr["heading"] 			= $_SESSION["heading"];
					$strArr["user_id"]		 	= $myId;
					$strArr['heading1']			= $_SESSION['heading1'];
					$strArr['heading2']			= $_SESSION['heading2'];
					$strArr['active']			= 'Y';
					$strArr['txn_id']			=  $item_number;
					
					$str_id = $store->addStore($strArr);
					
					if($str_id)
					{
					$subs_renewal=0; //store creation
					
					$mail_header = array();
					$mail_header["from"]		 = $framework->config['site_name'].'<'.$framework->config['admin_email'].'>';
					$mail_header["to"]  		 = $_SESSION['email'];
					
					$dynamic_vars = array();
					$dynamic_vars["USER_NAME"]  = $_SESSION['username'];
					$dynamic_vars["FIRST_NAME"] = $_SESSION['first_name'];
					$dynamic_vars["LAST_NAME"]  = $_SESSION['last_name'];
					$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
					$dynamic_vars["PASSWORD"]   =  $_SESSION['password'];
					
					$dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".$_SESSION["store_name"]."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";
					
					$dynamic_vars["STORE_LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".$_SESSION["store_name"]."\">".SITE_URL."/".$_SESSION["store_name"]."</a>";
					
					$dynamic_vars["STORE_LINK_MANAGE"]       = "<a target='_blank' href=\"".SITE_URL."/".$_SESSION["store_name"].'/manage'."\">".SITE_URL."/".$_SESSION["store_name"].'/manage'."</a>";
					
					$email->send("store_creation_confirmation",$mail_header,$dynamic_vars);
				   }	
				}
			  }
			}
			
			// chkeck the payemnt staus is completed and tranaction type is subscr_payment
			/// adarsh 25sep 2009
			if($_POST['txn_type'] == "subscr_payment")
			{
				if($subs_renewal==1) // checking subscription renewal 
				{							
					$store_det = $objUser->getStoreByTxnId($item_number);
					if(count($store_det)==0){
						$store_det = $objUser->getStoreByTxnId($_POST['subscr_id']);
					}
					if(count($store_det) >0)
					{
						 $log_det=$objUser->ipnLogDet($txn_id);
						 if(count($log_det)==1)
						 {
						 	$objUser->updateSubscriptionDet($store_det->user_id);
						 }
					}
				}
				$objUser->changeStopSubsStatus($store_det->user_id);
			}
			
			
			if($_POST['txn_type'] == "subscr_eot")
			{
				if($subs_renewal==1) // checking subscription renewal 
				{							
					$store_det = $objUser->getStoreByTxnId($item_number);
					if(count($store_det)==0){
						$store_det = $objUser->getStoreByTxnId($_POST['subscr_id']);
					}
					if(count($store_det) >0)
					{
						 $objUser->updateSubscriptionEot($store_det->user_id);
					}
				}
			}
			
			
		
		
	break;	
		
		
		
		
}


if($_REQUEST["act"]=="register_two")
							{
							$framework->tpl->display($global['curr_tpl']."/ssl_inner.tpl");
							}else
							{

$framework->tpl->display($global['curr_tpl']."/inner.tpl");
}
?>