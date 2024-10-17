<?php
	session_start();
	include_once(FRAMEWORK_PATH."/modules/competition/lib/class.cmp.php");
	include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
	include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");

	$album= new Album();
	$objCmp=new Cmp();
	$objUser=new User();

	$framework->tpl->assign("CAT_LIST", $objUser->getCategoryCombo($_REQUEST["mod"]));	
	$framework->tpl->assign("CAT_ARR", $objUser->getCategoryArr($_REQUEST["mod"]));
	
	switch($_REQUEST['act']) 
	{
		case "create":
			checkLogin();
			$date=date("Y-m-d");
			$framework->tpl->assign("DT",$date);
			
			if ($_SERVER['REQUEST_METHOD']=="POST")
			{
			
				unset($_POST["x"],$_POST["y"],$_POST["cmp_pass2"],$_POST["terms"]);
				$_POST["user_id"]=$_SESSION['memberid'];
				$_POST["owner_type"]="user";
				$_POST["enddate"]=$_POST["enddate"]." 23:59:59";
				if ($_POST["pay_mode"]=="secure")
				{
					if($_POST["price"]>0)
					{	
						$pay_mode="secure";
						$_POST["pay_mode"]="nonsecure";
					}
					else
					{
						$pay_mode="nonsecure";
					}	
				}
				$fr_size = $_POST["fr_size"] * 60;
				$to_size = $_POST["to_size"] * 60;
				
				$_POST["fr_size"] = $fr_size;
				$_POST["to_size"] = $to_size;
				
				// code for inserting image
				
				$fname=basename($_FILES['image']['name']);
				$ftype=$_FILES['image']['type'];
				$tmpname=$_FILES['image']['tmp_name'];
				if ($fname)
				{
					$_POST["image"]='Y';
				}
				else
				{
					$_POST["image"]='N';
				}	
				
				//end 
				
				
				$objCmp->setArrData($_POST);
				if ($cmp_id=$objCmp->insertCmp())
				{
					
					//code for inserting image
					
						if ($fname)
						{
		
							//uploading the file
						$dir=SITE_PATH."/modules/competition/images/";
						$thumbdir=$dir."thumb/";
						uploadImage($_FILES['image'],$dir,$_FILES['image']['name'],1);
						chmod($dir.$_FILES['image']['name'],0777);
						thumbnail($dir,$thumbdir,$_FILES['image']['name'],100,100,"","$cmp_id.jpg");
						chmod($thumbdir."$cmp_id.jpg",0777);
						//@unlink(SITE_PATH."/modules/member/images/userpics/".$_FILES['image']['name']);
						}
					
					//end	
					
				
					
					if($pay_mode=="secure")
					{
						redirect(makeLink(array("mod"=>"competition", "pg"=>"cmp"),"act=payment&cmp_id=$cmp_id"));
					}
					else
					{
						redirect(makeLink(array("mod"=>"competition", "pg"=>"cmp"),"act=mycmp&filter=own"));
					}	
				}
				else
				{
					$framework->tpl->assign("MESSAGE",$objCmp->getErr());		
				}
			}
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/competition/tpl/cmp_create.tpl");
			break;
		case "payment":
			checkLogin();
			$cmp_id=$_REQUEST["cmp_id"];
			$cmp_det=$objCmp->getCmpDetails($cmp_id);
			list($cmn,$tot_price) = $objCmp->getLastPrize($cmp_det["price"]);
			
			$framework->tpl->assign("PRIZE",$cmp_det["price"]);
			$framework->tpl->assign("COMMISSION",$cmn);
			
			$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
			$framework->tpl->assign("TOTAL_PRICE", $tot_price);
			$framework->tpl->assign("CMP_NAME",$cmp_det["name"]);
			$framework->tpl->assign("CMP_ID",$cmp_id);


			/*if ($cmp_det["pay_mode"]=="secure")
			{
				$framework->tpl->assign("ERROR_MSG","You are already a member of this Competition");
			}*/

			$framework->tpl->assign("RETURN_URL", makeLink(array("mod"=>"competition", "pg"=>"cmp"),"act=mycmp&filter=own"));
			$framework->tpl->assign("NOTIFY_URL", makeLink(array("mod"=>"competition", "pg"=>"cmp"),"act=payment_process"));
			$framework->tpl->assign("PROFILE", $objUser->getUserdetails($_SESSION["memberid"]));
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/competition/tpl/cmp_pay.tpl");
			
			break;
		case "payment_process":
			// read the post from PayPal system and add 'cmd'
			$req = 'cmd=_notify-validate';
	
			foreach ($_POST as $key => $value) {
				$value = urlencode(stripslashes($value));
				$req .= "&$key=$value";
			}
	
			// post back to PayPal system to validate
			$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
			$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
			$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
	
			// assign posted variables to local variables
			$item_name = $_POST['item_name'];
			$item_number = $_POST['item_number'];
			$payment_status = $_POST['payment_status'];
			$payment_amount = $_POST['mc_gross'];
			$payment_currency = $_POST['mc_currency'];
			$txn_id = $_POST['txn_id'];
			$receiver_email = $_POST['receiver_email'];
			$payer_email = $_POST['payer_email'];
			list($memberID,$cmp_id) = explode("-", $item_number);
	
			
			$cmp_det=$objCmp->getCmpDetails($cmp_id);

	
			if (!$fp) {
				// HTTP ERROR
			} else {
				fputs ($fp, $header . $req);
				while (!feof($fp)) {
					$res = fgets ($fp, 1024);
					if (strcmp ($res, "VERIFIED") == 0) {
						$fpp = fopen("tmp/txt.txt", "a+");
						fwrite($fpp, $_SESSION['memberid']."VERIFIED:payment_status:".$payment_status."|"."txn_id:".$txn_id."|"."payment_amount:".$payment_amount."|".$item_name." - ".date("d-m-Y H:i:s")."\n".$req."\n");
						fclose($fpp);
						
						// check the payment_status is Completed
						if ($payment_status == 'Completed') {
							// check that payment_amount/payment_currency are correct
							list($cmn,$tot_price) = $objCmp->getLastPrize($cmp_det["price"]);
							if ($payment_amount == $tot_price) {
								// process payment
								$arr1=array();
								$arr1["cmp_id"]=$cmp_id;
								$arr1["type"]="secure";
								$arr1["transfer_fee"]=$cmn;
								$arr1["pay_date"]=date("Y-m-d H:i:s");
								$arr1["total_amount"]=$tot_price;
								$objCmp->setArrData($arr1);
								$objCmp->insertPayTrack();
								$arr=array();
								$arr["pay_mode"]="secure";
								$objCmp->setArrData($arr);
								$objCmp->updateCmp($cmp_id);
								//$album->orderItems($_REQUEST, array('TransactionID'=>$txn_id, 'Amount'=>$payment_amount, 'response'=>$req), $memberID);
							}
							
						}
						
						// check that txn_id has not been previously processed
						// check that receiver_email is your Primary PayPal email
					}
					else if (strcmp ($res, "INVALID") == 0) {
						$fpp = fopen("tmp/txt.txt", "a+");
						fwrite($fpp, "INVALID:payment_status:".$payment_status."|"."txn_id:".$txn_id."|"."payment_amount:".$payment_amount."|".$item_name." - ".date("d-m-Y H:i:s")."\n".$req."\n");
						fclose($fpp);
						// log for manual investigation
					}
				}
				fclose ($fp);
			}
			exit;
			break;		
		case "list":
			if ($_REQUEST["cat_id"])
			{
				$catname=$objUser->getCatName($_REQUEST["cat_id"]);
				$framework->tpl->assign("FILTER",$_REQUEST["cat_id"]);
				$framework->tpl->assign("PH_HEADER", $catname["cat_name"]);
				list($rs, $numpad) = $objCmp->cmpList($_REQUEST['pageNo'], 5, "mod={$mod}&pg={$pg}&act=list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}&cat_id={$_REQUEST["cat_id"]}", OBJECT, $_REQUEST['orderBy'], $_REQUEST["cat_id"], $_REQUEST['txtSearch']);
			}
			else
			{
				$pheader="Running Competitions";
				$filter=1;
				if ($_REQUEST["filter"]=="complete")
				{
					$filter=2;
					$pheader="Completed Competitions";
				}
				elseif ($_REQUEST["filter"]=="schedule")
				{
					$filter=3;
					$pheader="Scheduled Competitions";
				}
				
	
				$framework->tpl->assign("FILTER",$_REQUEST["filter"]);
				$framework->tpl->assign("PH_HEADER", $pheader);
				list($rs, $numpad) = $objCmp->cmpList($_REQUEST['pageNo'], 5, "mod={$mod}&pg={$pg}&act=list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}", OBJECT, "id desc",0,$_REQUEST['txtSearch'],0,0,$filter,0,$_REQUEST["type"]);
			}
				
		
			$framework->tpl->assign("CMP_LIST", $rs);
			$framework->tpl->assign("CMP_NUMPAD", $numpad);

			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/competition/tpl/cmp_list.tpl");
			break;
		case "mycmp":
			checkLogin();
			if ($_REQUEST["cat_id"])
			{
				$catname=$objUser->getCatName($_REQUEST["cat_id"]);
				$framework->tpl->assign("FILTER",$_REQUEST["cat_id"]);
				$framework->tpl->assign("PH_HEADER", $catname["cat_name"]);
				list($rs, $numpad) = $objCmp->cmpList($_REQUEST['pageNo'], 5, "mod={$mod}&pg={$pg}&act=mycmp&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}&cat_id={$_REQUEST["cat_id"]}", OBJECT, "id desc", $_REQUEST["cat_id"], $_REQUEST['txtSearch'],1,$_SESSION["memberid"]);
			}
			else
			{
				$pheader="Running Competitions";
				$filter=1;
				if ($_REQUEST["filter"]=="complete")
				{
					$filter=2;
					$pheader="Completed Competitions";
				}
				elseif ($_REQUEST["filter"]=="schedule")
				{
					$filter=3;
					$pheader="Scheduled Competitions";
				}
				elseif ($_REQUEST["filter"]=="own")
				{
					$filter=5;
					$pheader="Competitions you own";
				}


	
				$framework->tpl->assign("FILTER",$_REQUEST["filter"]);
				$framework->tpl->assign("PH_HEADER", $pheader);
				list($rs, $numpad) = $objCmp->cmpList($_REQUEST['pageNo'], 5, "mod={$mod}&pg={$pg}&act=mycmp&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}", OBJECT, "id desc",0,$_REQUEST['txtSearch'],1,$_SESSION["memberid"],$filter);
			}
		
		
			$framework->tpl->assign("CMP_LIST", $rs);
			$framework->tpl->assign("CMP_NUMPAD", $numpad);

			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/competition/tpl/my_cmp.tpl");
			break;
			
		case "join":
			checkLogin();
			$cmp_id=$_REQUEST["cmp_id"];
			$cmp_det=$objCmp->getCmpDetails($cmp_id);
			
			$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
			$framework->tpl->assign("TOTAL_PRICE", $cmp_det["join_fee"]);
			$framework->tpl->assign("CMP_NAME",$cmp_det["name"]);
			$framework->tpl->assign("CMP_ID",$cmp_id);


			if ($objCmp->checkCmpMember($cmp_id,$_SESSION["memberid"]))
			{
				$framework->tpl->assign("ERROR_MSG","You are already a member of this Competition");
			}

			$framework->tpl->assign("RETURN_URL", makeLink(array("mod"=>"competition", "pg"=>"cmp"),"act=details&cmp_id=".$cmp_id));
			$framework->tpl->assign("NOTIFY_URL", makeLink(array("mod"=>"competition", "pg"=>"cmp"),"act=paymentnotify"));
			$framework->tpl->assign("PROFILE", $objUser->getUserdetails($_SESSION["memberid"]));
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/competition/tpl/cmp_payment.tpl");
			break;
		case "paymentnotify":
			// read the post from PayPal system and add 'cmd'
			$req = 'cmd=_notify-validate';
	
			foreach ($_POST as $key => $value) {
				$value = urlencode(stripslashes($value));
				$req .= "&$key=$value";
			}
	
			// post back to PayPal system to validate
			$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
			$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
			$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
	
			// assign posted variables to local variables
			$item_name = $_POST['item_name'];
			$item_number = $_POST['item_number'];
			$payment_status = $_POST['payment_status'];
			$payment_amount = $_POST['mc_gross'];
			$payment_currency = $_POST['mc_currency'];
			$txn_id = $_POST['txn_id'];
			$receiver_email = $_POST['receiver_email'];
			$payer_email = $_POST['payer_email'];
	
			list($memberID,$cmp_id) = explode("-", $item_number);
			
			$cmp_det=$objCmp->getCmpDetails($cmp_id);

	
			if (!$fp) {
				// HTTP ERROR
			} else {
				fputs ($fp, $header . $req);
				while (!feof($fp)) {
					$res = fgets ($fp, 1024);
					if (strcmp ($res, "VERIFIED") == 0) {
						$fpp = fopen("tmp/txt.txt", "a+");
						fwrite($fpp, $_SESSION['memberid']."VERIFIED:payment_status:".$payment_status."|"."txn_id:".$txn_id."|"."payment_amount:".$payment_amount."|".$item_name." - ".date("d-m-Y H:i:s")."\n".$req."\n");
						fclose($fpp);
						
						// check the payment_status is Completed
						if ($payment_status == 'Completed') {
							// check that payment_amount/payment_currency are correct
							if ($payment_amount == $cmp_det["join_fee"]) {
								// process payment
								$objCmp->joinCmp($cmp_id,$memberID);
								//$album->orderItems($_REQUEST, array('TransactionID'=>$txn_id, 'Amount'=>$payment_amount, 'response'=>$req), $memberID);
							}
							
						}
						
						// check that txn_id has not been previously processed
						// check that receiver_email is your Primary PayPal email
					}
					else if (strcmp ($res, "INVALID") == 0) {
						$fpp = fopen("tmp/txt.txt", "a+");
						fwrite($fpp, "INVALID:payment_status:".$payment_status."|"."txn_id:".$txn_id."|"."payment_amount:".$payment_amount."|".$item_name." - ".date("d-m-Y H:i:s")."\n".$req."\n");
						fclose($fpp);
						// log for manual investigation
					}
				}
				fclose ($fp);
			}
			exit;
			break;	
		case "invite":
			$cmp_id=$_REQUEST["cmp_id"];
			$userinfo = $objUser->getUserdetails($_SESSION["memberid"]);
			$contact  = $objUser->listContacts($userinfo["username"]);
			$framework->tpl->assign("USERDET", $userinfo);
			$framework->tpl->assign("CONTACT", $contact);
			if($_SESSION["memberid"])
			{
				if ($objCmp->checkCmpMember($cmp_id,$_SESSION["memberid"]))
				{
					$framework->tpl->assign("MEM_FLG","Y");
				}
				else
				{
					$framework->tpl->assign("MEM_FLG","N");
				}
			}
			else
			{
				$framework->tpl->assign("MEM_FLG","N");
			}	
			$cmp_det=$objCmp->getCmpDetails($cmp_id);
			
			if ($_SERVER['REQUEST_METHOD']=="POST")
			{
					$arr=explode(",",$_POST["friends"]);
					for($i=0;$i<sizeof($arr);$i++)
					{	if($arr[$i])
						{
							if(!$objUser->validUsername($arr[$i]))
							{
								if($invalid!='')
								{
									$invalid=$invalid."<br>".$arr[$i]." (Unknown user)";
								}
								else
								{
									$invalid=$arr[$i]." (Unknown user)";
								}
							}
						}
					}
					
					if($invalid=='')
					{
						for($i=0;$i<sizeof($arr);$i++)
						{	if($arr[$i])
							{
								if($objUser->validUsername($arr[$i]))
								{
	
									$arrData  = array();
									
									$arrData["from"]     = $userinfo["username"];
									$arrData["to"]       = $arr[$i];
									$arrData["subject"]  = $_REQUEST["subject"];
									$arrData["datetime"] = date("Y-m-d G:i:s");
									$arrData["status"]   = "U";
									
									$touser   = $objUser->getUsernameDetails($arr[$i]);
									$touserid = $touser["id"];
									$grpid    = $_REQUEST["group_id"];
									$from     = $userinfo["username"];
									
									$comment = $_REQUEST["comments"]."<br><br>";
									$comment = $comment . "<strong>Password for this Competition: ". $cmp_det["cmp_pass"] . "</strong><br><br>";
									$comment = $comment."To join this Competition click on the Link below <br><br>";
									$comment = $comment . "<a href=\"".SITE_URL."/".makeLink(array("mod"=>"competition", "pg"=>"cmp"), "act=details&fn=join&cmp_id=$cmp_id")."\"><strong>Join Competition</strong></a>";
									$arrData["comments"] = $comment;
									$objUser->setArrData($arrData);
									$objUser->sendMessage(1);
									$arr1=array();
									$arr1["cmp_id"]=$cmp_id;
									$arr1["to_user"]=$touserid;
									$objCmp->setArrData($arr1);
									$objCmp->insertInvite();
	
								}	
							}	
						}
						redirect(makeLink(array("mod"=>"competition", "pg"=>"cmp"), "act=details&cmp_id=$cmp_id"));
					}
					else
					{
						$framework->tpl->assign("MESSAGE",$invalid);
					}	

			}

			$framework->tpl->assign("CMP_DET",$cmp_det);
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/competition/tpl/cmp_invite.tpl");
			break;
			
		case "pass":
			checkLogin();
			$cmp_id=$_REQUEST["cmp_id"];
			$cmp_det=$objCmp->getCmpDetails($cmp_id);
			$framework->tpl->assign("CMP_DET",$cmp_det);
			if ($_SERVER['REQUEST_METHOD']=="POST")
			{
				if ($objCmp->passValidate($cmp_id,$_SESSION["memberid"],$_REQUEST["pass"]))
				{
					
					redirect(makeLink(array("mod"=>"competition", "pg"=>"cmp"),"act=details&fn=join&pass_val=1&cmp_id=".$cmp_id));
				}	
				else
				{
					$framework->tpl->assign("MESSAGE",$objCmp->getErr());
				}
			}
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/competition/tpl/cmp_password.tpl");
			break;	
		case "details":
			$cmp_id=$_REQUEST["cmp_id"];
			$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&cmp_id=".$_REQUEST["cmp_id"];
			$cmp_det=$objCmp->getCmpDetails($cmp_id);	
			
			if ($_REQUEST["fn"]=="join")
			{
				checkLogin();
				if ($objCmp->checkCmpMember($cmp_id,$_SESSION["memberid"]))
				{
					$framework->tpl->assign("MESSAGE","You are already a member of this Competition");
				}
				elseif (($cmp_det["active_users"]>0) && ($cmp_det["numusers"]<=$cmp_det["active_users"]))
				{
					$framework->tpl->assign("MESSAGE","Maximum user limit has reached for this competition");
				}
				elseif ( ($cmp_det["cmp_type"]=="private") && ($_REQUEST["pass_val"]!=1))
				{
					redirect(makeLink(array("mod"=>"competition", "pg"=>"cmp"),"act=pass&cmp_id=".$cmp_id));
				}
				else
				{	
					if ($cmp_det["join_fee"]!=0)
					{
						redirect(makeLink(array("mod"=>"competition", "pg"=>"cmp"),"act=join&cmp_id=".$cmp_id));
					}
					else
					{
						if(!$objCmp->joinCmp($cmp_id,$_SESSION["memberid"]))
						{
							$framework->tpl->assign("MESSAGE",$objCmp->getErr());			
						}
					}	
				}

								
			}
			
			
			if($_SESSION["memberid"])
			{
				if ($objCmp->checkCmpMember($cmp_id,$_SESSION["memberid"]))
				{
					$framework->tpl->assign("MEM_FLG","Y");
				}
				else
				{
					$framework->tpl->assign("MEM_FLG","N");
				}
			}
			else
			{
				$framework->tpl->assign("MEM_FLG","N");
			}	
			$cmp_det=$objCmp->getCmpDetails($cmp_id);
			list($rs, $numpad) = $objCmp->cmpMemList($_REQUEST['pageNo'], 5, $par, OBJECT, $_REQUEST['orderBy'],$cmp_id);
			$framework->tpl->assign("CMP_MEM_LIST", $rs);
			$framework->tpl->assign("CMP_MEM_NUMPAD", $numpad);
			
			$framework->tpl->assign("CMP_DET",$cmp_det);
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/competition/tpl/cmp_details.tpl");
			break;
		case "upload":
			checkLogin();
			$cmp_id=$_REQUEST["cmp_id"];
			$cmp_det=$objCmp->getCmpDetails($cmp_id);
			$framework->tpl->assign("CMP_DET",$cmp_det);
			if ($objCmp->checkCmpMember($cmp_id,$_SESSION["memberid"]))
			{
				$framework->tpl->assign("MEM_FLG","Y");
				
			}
			else
			{
				$framework->tpl->assign("MEM_FLG","N");
			}
				
			$memId=$_SESSION["memberid"];
			$userDet=$objUser->getUserdetails($memId);
			$framework->tpl->assign("USERINFO", $userDet);//getting details from member_master
			
			if($_REQUEST["fn"]=="upload")
			{
				
				if($mem_rs=$objCmp->getCmpMem($cmp_id,$_SESSION["memberid"]))
				{
				
					if($_REQUEST["crt"]=="M1")
					{
						
						$type="video";
						$med_det=$objCmp->getMediaDetails("album_video",$_REQUEST["file_id"]);
					}
					elseif($_REQUEST["crt"]=="M2")
					{
						$type="photo";
						$med_det=$objCmp->getMediaDetails("album_photos",$_REQUEST["file_id"]);
					}
					else
					{
						$type="music";
						$med_det=$objCmp->getMediaDetails("album_music",$_REQUEST["file_id"]);
					}
					
					$upload=1;
					
					if(date("Y-m-d H:i:s")>$cmp_det["enddate"])
					{
						$upload=0;
						$framework->tpl->assign("MESSAGE","This Competition is not active");
					}
					
					if($cmp_det["media"]!=$type)
					{
					/*
						if($type=="video")
						{
							$upload=0;
							$framework->tpl->assign("MESSAGE","Please upload a Music file for this Competition");
						}
						else
						{
							$upload=0;
							$framework->tpl->assign("MESSAGE","Please upload a Movie file for this Competition");
						}*/
							$upload=0;
							$framework->tpl->assign("MESSAGE","Please upload a ".$cmp_det["media"]. " file for this Competition");
						
					}
					if($cmp_det["to_size"]!=0)
					{
						if(($med_det["length"]>=$cmp_det["fr_size"]) && ($med_det["length"]<=$cmp_det["to_size"]))
						{
							
						}
						else
						{
							$upload=0;
							$framework->tpl->assign("MESSAGE","Length of the media is invalid for this competition");
						}
					}
					
					/*if($cmp_det["cat_id"]!=$mem_det["cat_id"])
					{
						$upload=0;
						$framework->tpl->assign("MESSAGE","Invalid Category for this Competition");
					}*/
					
					
					if($upload==1)
					{
						
						$arrData=array();
						$arrData["cmp_mem_id"] = $mem_rs[0]->id;
						$tp=$type;
						if ($tp=="video")
						{ 
						$tp="movie";
						}
						
						$arrData["type"]       = $tp;
						$arrData["file_id"]    = $_REQUEST["file_id"];
						$arrData["postdate"]   = date("Y-m-d H:i:s");
						$objCmp->setArrData($arrData);
						$num_files=0;
						
						if($cmp_det["user_files"]!=0)
						{
							$file_cnt=$objCmp->getFileCnt($arrData["cmp_mem_id"]);
							if ($file_cnt>=$cmp_det["user_files"])
							{	
								$num_files=1;
							}
						}
						if ($num_files==0)
						{
							if($objCmp->cmpUpload())
							{
								redirect(makeLink(array("mod"=>"competition", "pg"=>"cmp"),"act=media&cmp_id=".$cmp_id));
							}
							else
							{
								$framework->tpl->assign("MESSAGE",$objCmp->getErr());
							}
						}
						else
						{
							$framework->tpl->assign("MESSAGE","You are not allowed to upload more than $file_cnt file(s) to this competition");
						}	
					}	
				}
				else
				{
					$framework->tpl->assign("MESSAGE",$objCmp->getErr());
				}				
						
			}
		
			
			$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&owner=".$_REQUEST["owner"]."&cmp_id=".$_REQUEST["cmp_id"];
			if ($_REQUEST["crt"])
			{
				$par = $par."&crt=".$_REQUEST['crt'];
			}
	
			$framework->tpl->assign("ALB_NM","All Albums");
			if ($_REQUEST["alb_id"])
			{
				$par = $par."&alb_id=".$_REQUEST['alb_id'];
				$framework->tpl->assign("ALB_NM","Album '".$album->getAlbumName($_REQUEST['alb_id'])."'");
			}
	
			$media="Music";
			$tbl = "album_music";
	
	
			$alb=0;
			if($_POST["txtSearch"])
			{
				$stxt=$_POST["txtSearch"];
				$framework->tpl->assign("STXT",$stxt);
	
			}
			else
			{
				if(!$_REQUEST["stxt"])
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
			if($_REQUEST["alb_id"])
			{
				$alb = $_REQUEST["alb_id"];
				$framework->tpl->assign("ALB_ID",$alb);
			}
	
			if ($_REQUEST["crt"])
			{
				$crt = $_REQUEST["crt"];
				$framework->tpl->assign("CRT",$crt);
	
			}
			if ($crt=="M1")
			{
				$media="Movie";
				$tbl="album_video";
	
				$mpath="video/thumb/";
				$framework->tpl->assign("MPATH",$mpath);
	
				$framework->tpl->assign("PGFILE","video");
				$framework->tpl->assign("FILE_ID","video_id");
	
				list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title:ASC',$tbl,'video',$stxt,$alb,$memId);
	
			}
			elseif ($crt=="M2")
			{
				$media="Photo";
				$tbl="album_photos";
	
				$mpath="photos/thumb/";
				$framework->tpl->assign("MPATH",$mpath);
	
				$framework->tpl->assign("PGFILE","photo");
				$framework->tpl->assign("FILE_ID","photo_id");
	
				list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title:ASC',$tbl,'photo',$stxt,$alb,$memId);
			}
			else
			{
				$tbl="album_music";
	
				$mpath="music/thumb/";
				$framework->tpl->assign("MPATH",$mpath);
	
				$framework->tpl->assign("PGFILE","music");
				$framework->tpl->assign("FILE_ID","music_id");
	
				list($rs, $numpad) = $album->mediaList($_REQUEST['pageNo'], 5,$par, OBJECT, 'title:ASC',$tbl,'music',$stxt,$alb,$memId);
			}
	
			$phCount=$album->getMediaCount($memId,'album_photos');
			$msCount=$album->getMediaCount($memId,'album_music');
			$mvCount=$album->getMediaCount($memId,'album_video');
	
			$framework->tpl->assign("phCount",$phCount);
			$framework->tpl->assign("msCount",$msCount);
			$framework->tpl->assign("mvCount",$mvCount);
	
			$framework->tpl->assign("MEDIA",$media);
	
			$framework->tpl->assign("PHOTO_LIST", $rs);
			$framework->tpl->assign("PHOTO_NUMPAD", $numpad);
	
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/competition/tpl/cmp_upload.tpl");
			break;
		case "media":
			$cmp_id=$_REQUEST["cmp_id"];
			$framework->tpl->assign("MEDIA_CNT",$objCmp->getMediaCnt($cmp_id));
			$cmp_det=$objCmp->getCmpDetails($cmp_id);
			$framework->tpl->assign("FILE_ID",$cmp_det["media"]."_id");
				if($cmp_det["media"]=='photo')
				{
					$mpath='photos';
				}
				else
				{
					$mpath=$cmp_det["media"];
				}
			$framework->tpl->assign("MPATH",$cmp_det["media"]);
			$framework->tpl->assign("MPATH1",$mpath);
			$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&cmp_id=".$_REQUEST["cmp_id"]."&filter=".$_REQUEST["filter"];
			/*if($_REQUEST["fn"]=="join")
			{
				checkLogin();
				if(!$objCmp->joinCmp($cmp_id,$_SESSION["memberid"]))
				{
					$framework->tpl->assign("MESSAGE",$objCmp->getErr());			
				}	
			}*/
			
			if($_SESSION["memberid"])
			{
				if ($objCmp->checkCmpMember($cmp_id,$_SESSION["memberid"]))
				{
					$framework->tpl->assign("MEM_FLG","Y");
				}
				else
				{
					$framework->tpl->assign("MEM_FLG","N");
				}
			}
			else
			{
				$framework->tpl->assign("MEM_FLG","N");
			}	
			
			$cmp_det=$objCmp->getCmpDetails($cmp_id);
			list($rs, $numpad) = $objCmp->cmpMediaList($_REQUEST['pageNo'], 5, $par, OBJECT, $_REQUEST['orderBy'],$cmp_id,$cmp_det["media"]);
			$framework->tpl->assign("PHOTO_LIST", $rs);
			$framework->tpl->assign("PHOTO_NUMPAD", $numpad);
			
			$framework->tpl->assign("CMP_DET",$cmp_det);

			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/competition/tpl/cmp_media.tpl");
			break;
		case "winners":
			if ($_REQUEST["cat_id"])
			{
				$framework->tpl->assign("FILTER",$_REQUEST["cat_id"]);
				$catname=$objUser->getCatName($_REQUEST["cat_id"]);
				$framework->tpl->assign("PH_HEADER", $catname["cat_name"]);
				$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&filter=".$_REQUEST["cat_id"];
				$framework->tpl->assign("FILTER",$_REQUEST["cat_id"]);
				list($rs, $numpad) = $objCmp->cmpWinnerList($_REQUEST['pageNo'], 5, $par, OBJECT, $_REQUEST['orderBy'],$_REQUEST["cat_id"]);
			}
			else
			{
				$framework->tpl->assign("PH_HEADER", "All Categories");
				$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act'];
				list($rs, $numpad) = $objCmp->cmpWinnerList($_REQUEST['pageNo'], 5, $par, OBJECT, $_REQUEST['orderBy']);
			}	
			$framework->tpl->assign("WIN_LST",$rs);
			$framework->tpl->assign("WIN_NUMPAD",$numpad);
			
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/competition/tpl/cmp_winners.tpl");
			break;
		case "auto":
			$lst=$objCmp->recrList();
			for($i=0;$i<sizeof($lst);$i++)
			{
				$objCmp->autoCmp($lst[$i]);
			}
			$fsh_lst=$objCmp->cmpFinishList();
			for($j=0;$j<sizeof($fsh_lst);$j++)
			{
				$uname=$objUser->getUserDetails($fsh_lst[$j]["user_id"]);
				$objCmp->sendFinishMsg($fsh_lst[$j]["name"],$uname["username"],$fsh_lst[$j]["id"]);
				
				$rs1=$objCmp->getCmpWinner($fsh_lst[$j]["id"],$fsh_lst[$j]["media"]);
				$preMark=0;
				for($k=0;$k<sizeof($rs1);$k++)
				{
					
					if ($rs1[$k]["mark"]==0) break;
					$arr=array();
					$arr["cmp_id"]  = $rs1[$k]["cmp_id"];
					$arr["user_id"] = $rs1[$k]["user_id"];
					$arr["file_id"] = $rs1[$k]["file_id"];
					$arr["type"]    = $rs1[$k]["media"]; 
					$arr["mark"]    = $rs1[$k]["mark"]; 
					if($k>0)
					{
						if($rs1[$k]["mark"]==$preMark)
						{
							$unm=$objUser->getUserDetails($arr["user_id"]);
							$cmp_nm=$rs1[$k]["name"];
							$objCmp->insertWinner($arr,$cmp_nm,$unm);
						}
					}
					else
					{
							$unm=$objUser->getUserDetails($arr["user_id"]);
							$cmp_nm=$rs1[$k]["name"];
							$objCmp->insertWinner($arr,$cmp_nm,$unm);
					}					
					
					$preMark=$rs1[$k]["mark"];
					
				}
			}
			
			break;			
	
	}
	$framework->tpl->display($global['curr_tpl']."/inner.tpl");


?>