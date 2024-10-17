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
include_once(FRAMEWORK_PATH."/modules/member/lib/class.referral.php");
include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.payment.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.music.php");
include_once(FRAMEWORK_PATH."/includes/payment/authorize.net/AuthorizeNet.php");
include_once(FRAMEWORK_PATH."/includes/payment/authorize.net/arb_auth.class.php");

$email	 		= new Email();
$objUser		= new User();
$ref 			= new Referral();
$store  		= new Store();
$album  		= new Album();
$objCms  		= new Cms();
$music  		= new Music();
$PaymentObj		= new Payment();
$authObj		= new AuthorizeNet();
$arbObj			= new ARBAuthnet();

$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
$framework->tpl->assign("QN_LST", $objUser->getQns());
$framework->tpl->assign("MEM_TYPE",$objUser->loadMemTypeCmb("0,3"));

switch($_REQUEST['act']) {
	case "add_edit":
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
			for($i=1900;$i<$maxy;$i++){
				$yearlist[]=$i;
			}
		}

		if($_SERVER['REQUEST_METHOD']=="POST")
		{
			// start saving the referral details 20-09-07 shinu
			if(isset($_REQUEST['referral_name']))
			{
				$myReferralName	=	$_REQUEST['referral_name'];
				if($global["inner_change_reg"]=="")
				$ref->AddReferralDetails($_REQUEST['referral_name'],$_REQUEST['reg_pack']);
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
				if($_POST["store_name"])
				{
					if(!$objUser->validStore($_POST["store_name"]))
					{
						$proceed = 0;
						setMessage("Store name already Exists");
					}
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
					if(isset($_POST['txtNumber']))
					{

						$number   = $_POST['txtNumber'];

						if (md5($number) != $_SESSION['image_random_value'])
						{
							$validate =0;
							$objUser->setErr("Please Enter Correct Code");
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
					
					if ($validate==1)
					{
						unset($_POST["confirm_pass"],$_POST["btn_save"],$_POST["valStr"],$_POST["store_name"],$_POST["x"],$_POST["y"],$_POST["txt_payment"],$_POST["redirect_url"],$_POST["heading"],$_POST["store_logo"],$_POST["store_center"],$_POST["txtNumber"],$_POST["month"],$_POST["day"],$_POST["year"]);
						$objUser->setArrData($_POST);
						$myId=$objUser->insert();
					}
					
					if($global['health_care'] == "1"){
						$medi_arr['member_id'] =  $myId;
						$objUser->setArrData($medi_arr);
						$objUser->med_insert();
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
							$str_id = $store->addStore($strArr,&$insert_id);
							if ($st_image)
							{
								//uploading the file

								$dir=SITE_PATH."/modules/store/images/";
								$thumbdir=$dir."thumb/";
								if($global['artist_selection']=='Yes'){
								uploadImage($_FILES['store_logo'],$dir,$insert_id.".".$st_ftype_ext[1],1);
								chmod($dir.$_FILES['store_logo']['name'],0777);
								}else{
								uploadImage($_FILES['store_logo'],$dir,$_FILES['store_logo']['name'],1);
								chmod($dir.$_FILES['store_logo']['name'],0777);
								thumbnail($dir,$thumbdir,$_FILES['store_logo']['name'],100,100,"","$str_id.jpg");
								chmod($thumbdir."$str_id.jpg",0777);
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

						if ($sub_pack!="")
						{

							$arr = array();
							//$arr["user_id"] = 20;
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


							$dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";
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
							redirect(makeLink(array("mod"=>"member", "pg"=>"login"),"act=y"));
						}
						else
						{
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
		if($global['date_convertion'] != "1"){
			$framework->tpl->assign("REGPACKG",$objUser->loadRegPack2());
		}
		if($global['health_care'] == "1"){
			$framework->tpl->assign("PAIN_INDICATOR",$objUser->ListPainIndicator());
			$framework->tpl->assign("FEDEX_SHIPPING",$objUser->ListFedexShipping());
		}
		if($_REQUEST["reff_id"]){
			$framework->tpl->assign("REFFERAL_ID",$_REQUEST["reff_id"]);
		}
		$framework->tpl->assign("SUBMITBUTTON1", createImagebutton_Div("Submit","#","submit_form()"));
		$framework->tpl->assign("CANCEL", createImagebutton_Div("Cancel","#","history.go(-1)"));

		$framework->tpl->assign("SUBMITBUTTON", createButton("SUBMIT","#","checkLength()"));
		$framework->tpl->assign("RESETBUTTON", createButton("CANCEL","#","history.go(-1)"));
		if($global["mem_type"] == 1){
			$framework->tpl->assign("MEMB_TYPE", $objUser->getmem_type());
		}
		
		if($_REQUEST['retailer']==1)
		{
			$framework->tpl->assign("TITLE_HEAD","Retailer Registration");
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/reg_retailar.tpl");
		}else
		{
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/reg.tpl");
		}


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
			$sub_pack	=	urlencode($_REQUEST['sub_pack']);
			redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=sub_credpayment&user_id={$_REQUEST['user_id']}&tot_amt={$_REQUEST['tot_amt']}&sub_pack=$sub_pack&action=registration_pay"));
		}
		
		if ($_REQUEST["user_id"])
		{

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
				$package_id = $user_det["reg_pack"];
				$subscr_id  = $user_det["sub_pack"];

				//echo $subscr_id;

				if ($subscr_id>0)
				{
					$enddate = $objUser->getEndDate($subscr_id);
				}
				if ($enddate!="")
				{
					$framework->tpl->assign("ENDDATE",$enddate);
				}
				if ( ($package_id>0)|| ($subscr_id>0))
				{

					$pack_det   = $objUser->getPackageDetails($package_id);


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
					$tot_amt = $pack_det["fee"]+ $subscr_det["fees"];
					$framework->tpl->assign("TOT_AMT",$tot_amt);


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
								$mail_header["from"] = $framework->config['site_name'].'<'.$framework->config['admin_email'].'>';
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
		$framework->tpl->assign("UDET",$user_det );
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
	$_SESSION['thnx_setup'] = $_REQUEST['thnx_setup'];
		$ConfExists		=	$PaymentObj->configurationExistsForStore('0');
		$PaymentMethod	=	$PaymentObj->getActivePaymentGateway('0');  #Paypal Pro, Authorize.Net, LinkPoint Central 0 --> Store Owned by admin, function prototype getActivePaymentGateway($StoreName)
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
			/// for arb

			$authDetails	=  $authObj->getAuthorizeNet(0, 'admin');
			if($PaymentMethod === 'Authorize.Net' && $authDetails['auth_net_arb']=='Y')
			{
				$Params						 =	array();
				$Params['loginname']         =  $authDetails['auth_net_login_id'];
				$Params['transactionkey']    =  $authDetails['auth_net_tran_key'];
				$Params['refId']        	 =  $UserDetails['id'];
				$Params['name']        	 	 =  $UserDetails['username'];
				$SubAmount					 =	$arbObj->getSubpackFee($UserDetails['sub_pack']);
				$Params['trialAmount']      =  $_REQUEST['tot_amt'];
				$Params['amount']        	 =  $_REQUEST['tot_amt'];//$SubAmount;
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
				if ($_REQUEST["flag"]=="U")
				{
					$arb_subDet	=	$arbObj->getSubscriptionId($UserDetails['id']);
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
						redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=upgrade&user_id={$_REQUEST['user_id']}&tot_amt={$_REQUEST['tot_amt']}&sub_pack=$sub_pack&reg_pack={$_REQUEST['reg_pack']}&action=renewal&transactionid=$subscriptionId"));
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
			else
			{
			
				$Result			=	$PaymentObj->processPaymentRequest('0',$Params);

				if($Result['Approved'] == 'No') {
					setMessage($Result['Message']);
				} else {
					$TransactionId		=	$Result['TransactionId'];
					if ($_REQUEST["flag"]=="U")
					{
						redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=upgrade&user_id={$_REQUEST['user_id']}&tot_amt={$_REQUEST['tot_amt']}&sub_pack=$sub_pack&reg_pack={$_REQUEST['reg_pack']}&action=renewal&transactionid=$TransactionId"));
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
			}


		} # Close if Submit

		$CreditCards 	=   $PaymentObj->getCreditCardsOfStore('0');
		$CCDetails		=	$PaymentObj->getCreditCardDetailsOfStores('0');
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
			$msg_display  = "<u><b>Member Details</b> </u><br>";
			$msg_display .= "Username: {$user_det["username"]}<br>";
			$msg_display .= "Email: {$user_det["email"]} (<b>Unverified</b>)<br>";

			setMessage($msg_display,MSG_INFO);
			if ($_SERVER['REQUEST_METHOD']=="POST")
			{
				$mail_header = array();
				$mail_header["from"] = $framework->config['site_name'].'<'.$framework->config['admin_email'].'>';
				$mail_header["to"]   = $user_det["email"];
				$dynamic_vars = array();
				$dynamic_vars["USER_NAME"]  = $user_det["username"];
				$dynamic_vars["FIRST_NAME"] = $user_det["first_name"];
				$dynamic_vars["LAST_NAME"]  = $user_det["last_name"];
				$dynamic_vars["PASSWORD"]   = $user_det["password"];
				$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
				$dynamic_vars["LINK"]       = "<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";

				$site_var1 = SITE_URL."/index.php";

				$site_var2 = SITE_URL."/";

				$qr_str = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

				$email->send("registration_confirmation",$mail_header,$dynamic_vars);
				setMessage("An activation link has been sent to {$user_det['email']}",MSG_SUCCESS);
			}
		}
		else
		{
			setMessage("Unknown User");
		}
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/email_verify.tpl");
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
	case "upgrade":
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
					$expire_uti = mktime(0, 0, 0, date("m"), date("d") + $duration,   date("Y"));
				}
				elseif ($type=="M")
				{
					$expire_uti = mktime(0, 0, 0, date("m") + $duration, date("d") ,   date("Y"));
				}
				else
				{
					$expire_uti = mktime(0, 0, 0, date("m"), date("d"),   date("Y") + $duration);

				}
				$expire_date = date("Y-m-d H:i:s",$expire_uti);
			  $objUser->UpgradePackageSubscription($_REQUEST["user_id"],$pck_id,$startDate,$expire_date);
			// end for putting end date 15-11
			
			//$objUser->renewSubscription($startDate,$active);

			// end update subscription


		}
		$reg  = $objUser->loadRegPack();
		$mem_det = $objUser->getUserDetails($_SESSION["memberid"]);

		if($_SERVER['REQUEST_METHOD']=="POST")
		{
			//print_r($_REQUEST['list_id']);
			$reg_pack_id=$_REQUEST['list_id'];
			$reg_pack_det  = $objUser->getPackageDetails($reg_pack_id);
			$reg_sub_det = $objUser->loadSubscriptions($reg_pack_id);
			if($reg_pack_det['fee']=="0.00" and count($reg_sub_det)==0){
				setMessage("Unable to complete your request");
			}else{
				setMessage("");
				redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=get_subpack&user_id=$_SESSION[memberid]&reg_pack=$reg_pack_id&upgrade=t"));
			}
		}
		$framework->tpl->assign("mem_det",$mem_det);
		$framework->tpl->assign("reg_plan",$reg);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/upgrade.tpl");
		break;

	case "get_subpack":
		$user_id	=	$_SESSION['memberid'];
		$reg_pack	=	$_REQUEST['reg_pack'];
		if ($_SERVER['REQUEST_METHOD']=="POST") {
			//$sub_pack	=	urlencode($_REQUEST['sub_pack']);
			$sub_packs	=	$_REQUEST['sub_pack'];
			list($subpack,$amount)	=	split("~",$sub_packs);
			redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=sub_credpayment&user_id=$user_id&tot_amt=$amount&reg_pack=$reg_pack&flag=U&sub_pack=$subpack&action=renewal"));
		}
		$userDetails	=	$objUser->getUserdetails($user_id);
		$subPacks	=	$objUser->getSubpacks($reg_pack);
		$framework->tpl->assign("SUB_PACKS",$subPacks);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/subscription_pay.tpl");
		break;
	case "billing_det":
		checkLogin();
		$address = $objUser->getAddress('',$_SESSION['memberid'],'billing');
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
		$framework->tpl->assign("SUBMITBUTTON1", createImagebutton_Div("Submit","#","pagesub()"));
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
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>