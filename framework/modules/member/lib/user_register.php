<?php
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
//$framework->tpl->assign("DNM_LIST", $objUser->getDenominations());
$framework->tpl->assign("QN_LST", $objUser->getQns());
$framework->tpl->assign("MEM_TYPE",$objUser->loadMemTypeCmb("0,4"));
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
		$maxy=date("Y");
		$maxy=$maxy+1;
		//print_r($maxy);
		for($i=1900;$i<$maxy;$i++){
			$yearlist[]=$i;
		}
		//print_r($yearlist);
		/*if ($getId)
		{
		
			$udet = $objUser->getUserdetails($getId);
			if ($udet)
			{
					
				$_REQUEST += $udet;
				$framework->tpl->assign("EDITFLG",true);
				$framework->tpl->assign("USERINFO",$udet);
			}
			
		}*/
		
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
					
					if ($fname)
					{
						$_POST["image"]='Y';
					}
					else
					{
						$_POST["image"]='N';
					}	
					$_POST['newsletter'] = 'N';
					$_POST['addr_type']  = 'master';
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
	
						/*if ($_POST["sub_pack"])
						{
							$sub_pack = $_POST["sub_pack"];
							if ($sub_pack!=0)
							{
								$_POST["subscription"]="Y";
							}	
						}*/
						
						if ($_POST["txt_payment"])
						{
							$payment = $_POST["txt_payment"];
							$_POST["amt_paid"] ="N";
						}
						else 
						{
							$payment = "N";
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
						if($global["searchstyle"]=='1'){
							$_POST["dob"]=$_POST["month"]."-".$_POST["day"]."-".$_POST["year"];
							$_REQUEST['dob']=$_POST["dob"];
						}
						$_POST["dob"]=$_POST["month"]."-".$_POST["day"]."-".$_POST["year"];
							$_REQUEST['dob']=$_POST["dob"];
						
						/**/
						if(array_key_exists("show_format",$global))
						{
							if($global["show_format"] !="")
							$_POST['dob'] = $objUser->dateInsertViewFormat($_REQUEST['dob']);
							
						}
						
						
												
						if ($validate==1)	
						{
							unset($_POST["confirm_pass"],$_POST["btn_save"],$_POST["valStr"],$_POST["store_name"],$_POST["x"],$_POST["y"],$_POST["txt_payment"],$_POST["redirect_url"],$_POST["heading"],$_POST["store_logo"],$_POST["store_center"],$_POST["txtNumber"],$_POST["month"],$_POST["day"],$_POST["year"]);
							$objUser->setArrData($_POST);
							$myId=$objUser->insert();
						}
						//print_r($_POST);exit;
					
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
								$str_id = $store->addStore($strArr);	
								if ($st_image)
								{
									//uploading the file
									
									$dir=SITE_PATH."/modules/store/images/";
									$thumbdir=$dir."thumb/";
									uploadImage($_FILES['store_logo'],$dir,$_FILES['store_logo']['name'],1);
									chmod($dir.$_FILES['store_logo']['name'],0777);
									thumbnail($dir,$thumbdir,$_FILES['store_logo']['name'],100,100,"","$str_id.jpg");
									chmod($thumbdir."$str_id.jpg",0777);
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
								$arr["user_id"] = 20;
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
								//redirect(makeLink(array("mod"=>"member", "pg"=>"login"),"act=y"));
								redirect(makeLink(array("mod"=>"member", "pg"=>"user"),"act=list"));
							}
							else 
							{
						
									redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=reg_pay&user_id=$myId"));
								
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
					if ($proceed==1)	
					{
						unset($_POST["confirm_pass"],$_POST["store_id"],$_POST["x"],$_POST["y"],$_POST["btn_save"],$_POST["txt_payment"]);
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
					if($global['searchstyle'] != "1"){
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
			if($global['searchstyle'] != "1"){
				$framework->tpl->assign("REGPACKG",$objUser->loadRegPack2());
				
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
			if($global['health_care'] == "1"){
				$framework->tpl->assign("HEALTH_CARE",1);
				$framework->tpl->assign("STATE_LIST",$objUser->listdropdown('6'));
			}
			if($_REQUEST['retailer']==1)
			{	$framework->tpl->assign("TITLE_HEAD","Retailer Registration");
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/reg_retailar.tpl");
			}else
			{	
				$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/member/tpl/reg.tpl");
			}
			
			
			$framework->tpl->assign("year_list",$yearlist);
			if($global["inner_change_reg"]=="yes"){
				$framework->tpl->display($global['curr_tpl']."/inner_ch.tpl");
				exit;
			}
			
        break;
}
$framework->tpl->display($global['curr_tpl']."/admin.tpl");
?>