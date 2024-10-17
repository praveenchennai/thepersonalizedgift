<?php
session_start();
include_once(FRAMEWORK_PATH."/modules/album/lib/class.video.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.payment.php");

$album			= 	new Album();
$video			=	new Video();
$user			=	new User();
$email	 		=	new Email();
$PaymentObj		=	new Payment();

$memberID = $_SESSION['memberid'];

$fileError = array(
	1=>"The uploaded file exceeds the maximum allowed file size",
	2=>"The uploaded file exceeds the maximum allowed file size",
	3=>"The uploaded file was only partially uploaded",
	4=>"No file was uploaded",
	6=>"Missing a temporary folder"
);

	$framework->tpl->assign("CAT_LIST", $objUser->getCategoryCombo($_REQUEST["mod"]));	
	$framework->tpl->assign("CAT_ARR", $objUser->getCategoryArr($_REQUEST["mod"]));

switch ($_REQUEST['act']) {
	case "sell_uploaded":
		checkLogin();
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			$req = $_REQUEST;

			$item_type = $_REQUEST['item_type'];
			$item_id   = $_REQUEST['item_id'];
			
			$fname	 =	$_FILES['videoFile']['name'];
			$ftype	 =	$_FILES['videoFile']['type'];
			$fsize	 =	$_FILES['videoFile']['size'];
			$ferror	 =	$_FILES['videoFile']['error'];
			$tmpname =	$_FILES['videoFile']['tmp_name'];
			
			$fileext=strtolower($album->file_extension($fname));

			$dir=SITE_PATH."/modules/album/".$item_type."/";
			
			$allowed = array('mov', 'wmv', 'mpg', 'avi', '3gp', 'dat', 'asx');
			
			if ($item_type == "music") {
				$allowed[] = "mp3";
				$allow_msg = "MP3, ";
			}
			if(!$ferror){
				if(in_array($fileext, $allowed)) {
					
					$req['filetype'] = $fileext;	
					$album->setArrData($req);			
					$album->editMediaDetails($item_id,"album_$item_type",$item_type);
					
					move_uploaded_file($tmpname, $dir.$id.".".$fileext);
					chmod($dir.$id.".".$fileext, 0777);
					
					redirect(makeLink(array("mod"=>"album", "pg"=>"$item_type"), "act=details&{$item_type}_id=$item_id"));
				} else {
					$message="You have to select one file. We support MOV, {$allow_msg}WMV, MPG, 3GP, DAT, ASX and AVI files.";
				}
			} else {
				$message = $fileError[$ferror];
			}
		}
		$item = $album->mediaDetailsGet($_REQUEST['item_id'], $_REQUEST['item_type']);
		
		$framework->tpl->assign("ITEMTYPE", $_REQUEST['item_type']=="music"?"Music":"Movie");

		$framework->tpl->assign("ITEM", $item);
		$framework->tpl->assign("ERROR_MSG", $message);
		$framework->tpl->assign("SECTION_LIST", $album->albumSectionList());
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/sell_uploaded.tpl");
		break;
	case "video":
		checkLogin();
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			$req = &$_REQUEST;

			$fname	 =	$_FILES['videoFile']['name'];
			$ftype	 =	$_FILES['videoFile']['type'];
			$fsize	 =	$_FILES['videoFile']['size'];
			$ferror	 =	$_FILES['videoFile']['error'];
			$tmpname =	$_FILES['videoFile']['tmp_name'];

			$fileext=$album->file_extension($fname);

			$dir=SITE_PATH."/modules/album/video/";

			if(!$ferror && !$_FILES['videoFileOriginal']['error']){
				if(in_array(strtolower($fileext), array('mov', 'wmv', 'mpg', 'avi', '3gp', 'dat', 'asx')))
				{
					$mov = new ffmpeg_movie($tmpname);
					$req['dimension_width']  =  $mov->getFrameWidth();
					$req['dimension_width']  =  $req['dimension_width'] ? $req['dimension_width'] : 320;

					$req['dimension_height'] =  $mov->getFrameHeight();
					$req['dimension_height'] =  $req['dimension_height'] ? $req['dimension_height'] : 240;

					$req['filesize']         =  $fsize;
					$req['filetype']         =  strtolower($album->file_extension($_FILES['videoFileOriginal']['name']));
					$req['length']			 =  $mov->getDuration();

					$id = $album->insertVideoDetails($req);

					if(strtolower($fileext) == "3gp") {
						$_3gp_fix = " -an"; // right now 3gp is not supporting audio format, so it will work only if we strip the audio
					} else {
						$_3gp_fix = "";
					}

					shell_exec("ffmpeg -i {$tmpname} -ab 56 -ar 22050 -f flv{$_3gp_fix} {$dir}{$id}.flv");
					chmod("${dir}{$id}.flv",0777);
					move_uploaded_file($_FILES['videoFileOriginal']['tmp_name'], $dir.$id.".".strtolower($album->file_extension($_FILES['videoFileOriginal']['name'])));
					chmod($dir.$id.".".strtolower($album->file_extension($_FILES['videoFileOriginal']['name'])), 0777);

					$mov = new ffmpeg_movie("${dir}{$id}.flv");
					$frame = $mov->getFrame(10);
					if($frame) {
						$frame->resize(110, 80);
						$image = $frame->toGDImage();
						imagejpeg($image, "${dir}thumb/{$id}.jpg", 100);
					}
					redirect(makeLink(array("mod"=>"album", "pg"=>"video"), "act=details&video_id=$id"));
				}
				else
				{
					$message="You have to select one movie file. We support MOV, WMV, MPG, 3GP, DAT, ASX and AVI files.";
				}
			} else {
				$message = $fileError[$ferror];
			}

		}
		$framework->tpl->assign("ERROR_MSG", $message);
		$framework->tpl->assign("SECTION_LIST", $album->albumSectionList());
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/sell_video.tpl");
		break;
	case "track_upload":
		checkLogin();
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			$_POST["user_id"] = $_SESSION["memberid"];
			$featured = $_POST["featured"];
			unset($_POST["submit"], $_POST["featured"]);
			
			
			$req = $_POST;;

			$fname	 =	$_FILES['videoFile']['name'];
			$ftype	 =	$_FILES['videoFile']['type'];
			$fsize	 =	$_FILES['videoFile']['size'];
			$ferror	 =	$_FILES['videoFile']['error'];
			$tmpname =	$_FILES['videoFile']['tmp_name'];
			
			$conv_to_flv = $_REQUEST["convert"];
			
		
			$fileext=$album->file_extension($fname);
			
			$dir=SITE_PATH."/modules/album/music/";
			
			if ($framework->config["sell_music_extensions"])
			{
				$chk_files = explode(",",$framework->config["sell_music_extensions"]);				
			}
			else 
			{
				$st = "mp3,wav,mov,wmv,mpg,avi,3gp,dat,asx";
				$chk_files = explode(",",$st);
			}
			
			
			if(!$ferror && !$_FILES['videoFile']['error']) {
				
				if(in_array(strtolower($fileext), $chk_files))
				{
				
/*					$mov = new ffmpeg_movie($tmpname);
					$req['dimension_width']  =  $mov->getFrameWidth();
					$req['dimension_width']  =  $req['dimension_width'] ? $req['dimension_width'] : 320;

					$req['dimension_height'] =  $mov->getFrameHeight();
					$req['dimension_height'] =  $req['dimension_height'] ? $req['dimension_height'] : 0;*/

					$req['filesize']         =  $fsize;
					$req['filetype']         =  strtolower($album->file_extension($_FILES['videoFile']['name']));
					//$req['length']			 =  $mov->getDuration();
					if(strtolower($fileext) == "mp3" || strtolower($fileext) == "wav") {
						$req['audio_type']	=  'A';
					} else {
						$req['audio_type']	=  'V';
					}
					$album->setArrData($req);
					$id = $album->insertMusicDetails();
					
					if ($featured=="Y")
					{
						$arr1 = array();
						$arr1["featured_song_id"] = $id;
						$arr1["id"] = $_SESSION["memberid"];
						$user->setArrData($arr1);
						$user->update();
					}
					
					if ($conv_to_flv!="N")
					{
						if(strtolower($fileext) == "3gp") {
							$_3gp_fix = " -an"; // right now 3gp is not supporting audio format, so it will work only if we strip the audio
						} else {
							$_3gp_fix = "";
						}
	
	                    if(strtolower($fileext) == "mp3" || strtolower($fileext) == "wav") {
	                    	shell_exec("ffmpeg -i {$tmpname} -ar 44100 -ab 128 -f flv {$dir}{$id}.flv");
	                    	chmod("${dir}{$id}.flv", 0777);
	                    } else {
	                    	shell_exec("ffmpeg -i {$tmpname} -ab 56 -ar 22050 -f flv{$_3gp_fix} {$dir}{$id}.flv");
	                    	chmod("${dir}{$id}.flv", 0777);
	
	                    	$mov = new ffmpeg_movie("${dir}{$id}.flv");
	                    	$frame = $mov->getFrame(10);
	                    	if($frame) {
	                    		$frame->resize(110, 80);
	                    		$image = $frame->toGDImage();
	                    		imagejpeg($image, "${dir}thumb/{$id}.jpg", 100);
	                    	}
	                    }
					}    
                    move_uploaded_file($_FILES['videoFile']['tmp_name'], $dir.$id.".".strtolower($album->file_extension($_FILES['videoFile']['name'])));
                    chmod($dir.$id.".".strtolower($album->file_extension($_FILES['videoFile']['name'])), 0777);
					setMessage("Track uploaded successfully");
					redirect(makeLink(array("mod"=>"member", "pg"=>"home")));
				}
				else
				{
					$message="You have to select one music file. We support MP3, WAV, MOV, WMV, MPG, 3GP, DAT, ASX and AVI files.";
				}
			} else {
				if($ferror)$message = $fileError[$ferror];
				if($_FILES['videoFileOriginal']['error'])$message = $fileError[$_FILES['videoFileOriginal']['error']];
			}

		}
		
		$framework->tpl->assign("ERROR_MSG", $message);
		$framework->tpl->assign("SECTION_LIST", $album->albumSectionList());
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/sell_music.tpl");
		break;	
	case "music":
		checkLogin();
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			$req = &$_REQUEST;

			$fname	 =	$_FILES['videoFile']['name'];
			$ftype	 =	$_FILES['videoFile']['type'];
			$fsize	 =	$_FILES['videoFile']['size'];
			$ferror	 =	$_FILES['videoFile']['error'];
			$tmpname =	$_FILES['videoFile']['tmp_name'];
			
			$conv_to_flv = $_REQUEST["convert"];
			
			
			
			
			
			$fileext=$album->file_extension($fname);
			
			

			$dir=SITE_PATH."/modules/album/music/";
			
			if ($framework->config["sell_music_extensions"])
			{
				$chk_files = explode(",",$framework->config["sell_music_extensions"]);				
			}
			else 
			{
				$st = "mp3,wav,mov,wmv,mpg,avi,3gp,dat,asx";
				$chk_files = explode(",",$st);
			}
			
			
			if(!$ferror && !$_FILES['videoFileOriginal']['error']) {
				
				if(in_array(strtolower($fileext), $chk_files))
				{
				
					$mov = new ffmpeg_movie($tmpname);
					$req['dimension_width']  =  $mov->getFrameWidth();
					$req['dimension_width']  =  $req['dimension_width'] ? $req['dimension_width'] : 320;

					$req['dimension_height'] =  $mov->getFrameHeight();
					$req['dimension_height'] =  $req['dimension_height'] ? $req['dimension_height'] : 0;

					$req['filesize']         =  $fsize;
					$req['filetype']         =  strtolower($album->file_extension($_FILES['videoFileOriginal']['name']));
					$req['length']			 =  $mov->getDuration();

					if(strtolower($fileext) == "mp3" || strtolower($fileext) == "wav") {
						$req['audio_type']	=  'A';
					} else {
						$req['audio_type']	=  'V';
					}

					$id = $album->insertMusicDetails($req);
					
					if ($conv_to_flv!="N")
					{
						if(strtolower($fileext) == "3gp") {
							$_3gp_fix = " -an"; // right now 3gp is not supporting audio format, so it will work only if we strip the audio
						} else {
							$_3gp_fix = "";
						}
	
	                    if(strtolower($fileext) == "mp3" || strtolower($fileext) == "wav") {
	                    	shell_exec("ffmpeg -i {$tmpname} -ar 44100 -ab 128 -f flv {$dir}{$id}.flv");
	                    	chmod("${dir}{$id}.flv", 0777);
	                    } else {
	                    	shell_exec("ffmpeg -i {$tmpname} -ab 56 -ar 22050 -f flv{$_3gp_fix} {$dir}{$id}.flv");
	                    	chmod("${dir}{$id}.flv", 0777);
	
	                    	$mov = new ffmpeg_movie("${dir}{$id}.flv");
	                    	$frame = $mov->getFrame(10);
	                    	if($frame) {
	                    		$frame->resize(110, 80);
	                    		$image = $frame->toGDImage();
	                    		imagejpeg($image, "${dir}thumb/{$id}.jpg", 100);
	                    	}
	                    }
					}    
                    move_uploaded_file($_FILES['videoFileOriginal']['tmp_name'], $dir.$id.".".strtolower($album->file_extension($_FILES['videoFileOriginal']['name'])));
                    chmod($dir.$id.".".strtolower($album->file_extension($_FILES['videoFileOriginal']['name'])), 0777);

					redirect(makeLink(array("mod"=>"album", "pg"=>"music"), "act=details&music_id=$id"));
				}
				else
				{
					$message="You have to select one music file. We support MP3, WAV, MOV, WMV, MPG, 3GP, DAT, ASX and AVI files.";
				}
			} else {
				if($ferror)$message = $fileError[$ferror];
				if($_FILES['videoFileOriginal']['error'])$message = $fileError[$_FILES['videoFileOriginal']['error']];
			}

		}
		$framework->tpl->assign("ERROR_MSG", $message);
		$framework->tpl->assign("SECTION_LIST", $album->albumSectionList());
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/sell_music.tpl");
		break;
	case "commission":
		echo $album->getCommission($_REQUEST['cat_id'], $_REQUEST['type']);
		exit;
		break;
	case "addtocart":
		checkLogin();
		$item_rs = $album->mediaDetailsGet($_REQUEST['item_id'], $_REQUEST['item_type']);
		$album->addToCart($memberID, $_REQUEST['item_id'], $_REQUEST['item_type'], $item_rs['total_price'], $item_rs['cat_id']);
		redirect(makeLink(array("mod"=>"album", "pg"=>"shop"), "act=viewcart"));
		break;
	case "viewcart":
		checkLogin();
		$tp=$album->cartList($memberID);
		$framework->tpl->assign("CARTLIST",$tp );
		$framework->tpl->assign("TITLE_HEAD","MY CART" );
		$framework->tpl->assign("RETURN_URL", makeLink(array("mod"=>"album", "pg"=>"shop"), "act=paymentsuccess"));
		foreach($tp as $fl){
			if($fl->file_type=="album")
				$file_type="album";	
		}
		if($file_type=="album"){
			$framework->tpl->assign("TOTAL_PRICE",$global['album_price']);
		}else{
			$framework->tpl->assign("TOTAL_PRICE", $album->cartTotal($memberID));
		}
		$framework->tpl->assign("PROFILE", $user->getUserdetails($memberID));
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/shoppingcart.tpl");
		if ($_SERVER['REQUEST_METHOD']=="POST") {
		//print_r($_POST);exit;
				$sub_pack	=	urlencode($_REQUEST['sub_pack']);
				redirect(makeLink(array("mod"=>"album", "pg"=>"shop"),"act=sub_credpayment&user_id=$memberID&tot_amt={$_POST['amount']}&action=shopcart_pay"));
			}
		break;
	case "removefromcart":
		checkLogin();
		$album->removeFromCart($memberID, $_REQUEST['id']);
		redirect(makeLink(array("mod"=>"album", "pg"=>"shop"), "act=viewcart"));
		break;
	case "paymentform":
		checkLogin();
		$user=new User();
		$framework->tpl->assign("COUNTRY_LIST", $user->listCountry());
		$framework->tpl->assign("RETURN_URL", makeLink(array("mod"=>"album", "pg"=>"shop"), "act=paymentsuccess"));
		$framework->tpl->assign("TOTAL_PRICE", $album->cartTotal($memberID));
		$framework->tpl->assign("PROFILE", $user->getUserdetails($memberID));
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/paymentform.tpl");
		
		break;
	case "sub_credpayment" :
			$PaymentObj		=	new Payment();
			$ConfExists		=	$PaymentObj->configurationExistsForStore('0');
			
			if($ConfExists === FALSE) {
				# The control here indicate that the configuration settings are not available
				setMessage('Payment Configuration not Entered at Admin side');
				$framework->tpl->assign("INACTIVE",'disabled');
			}
			 
			$btn_save		=	$_REQUEST['btn_save'];
			
			if($btn_save == 'Submit') {
		
				$PaymentMethod	=	$PaymentObj->getActivePaymentGateway('0');  #Paypal Pro, Authorize.Net, LinkPoint Central
										# 0 --> Store Owned by admin, function prototype getActivePaymentGateway($StoreName)
				
				$UserDetails				 =		  $objUser->getUserdetails($_REQUEST['user_id']);
				
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
				
				$Result			=	$PaymentObj->processPaymentRequest('0',$Params);
				
				if($Result['Approved'] == 'No') {
					setMessage($Result['Message']);
				} else {
					$TransactionId		=	$Result['TransactionId'];
					$album->orderItems($_REQUEST, array('TransactionID'=>$TransactionId, 'Amount'=>$_REQUEST['tot_amt']),$memberID);
					redirect(makeLink(array("mod"=>"album", "pg"=>"shop"),"act=paymentsuccess&user_id={$_REQUEST['user_id']}&tot_amt={$_REQUEST['tot_amt']}&sub_pack=$sub_pack&action=renewal&transactionid=$TransactionId"));
					
				}
			} # Close if Submit
			
			$CreditCards 	=   $PaymentObj->getCreditCardsOfStore('0');
			$CCDetails		=	$PaymentObj->getCreditCardDetailsOfStores('0');
			$framework->tpl->assign("CREDITCARDLOGO",$CCDetails);
			$framework->tpl->assign("CREDITCARD",$CreditCards);
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/payment_credit_card.tpl");
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

		list($memberID) = explode("-", $item_number);

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
						if ($payment_amount == $album->cartTotal($memberID)) {
							// process payment
							$album->orderItems($_REQUEST, array('TransactionID'=>$txn_id, 'Amount'=>$payment_amount, 'response'=>$req), $memberID);
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
	case "paymentsuccess":
		checkLogin();
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/album/tpl/paymentsuccess.tpl");
		break;
	case "abuse":
		checkLogin();
		$arr = array();
		$arr["file_id"]   = $_REQUEST["item_id"];
		$arr['file_type'] = $_REQUEST['item_type'];
		$arr['user_id']   = $_SESSION['memberid'];
		$arr['postdate']  = date("Y-m-d H:i:s");
		$album->setArrData($arr);
		if ($album->reportAbuse())
		{
			setMessage("You remark has been stored",MSG_INFO);
		}
		else 
		{
			setMessage($album->getErr(),MSG_ERROR);
		}
		redirect(makeLink(array("mod"=>"member", "pg"=>"browse"),"act=tracks&pid=".$_REQUEST['pid']));
		break;	
}

$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>