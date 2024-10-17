<?
session_start();
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.search.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");
include_once(FRAMEWORK_PATH."/includes/class.framework.php");
include_once(FRAMEWORK_PATH."/modules/callendar/lib/class.calendartool.php");
include_once(FRAMEWORK_PATH."/modules/map/lib/G_Maps.class.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.photo.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.property.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");

$fw				=	new FrameWork();
$user           =   new User();
$flyer			=	new	Flyer();
$gmap	 		= 	new G_Maps();
$album			=	new Album();
$photo			=	new Photo();
$property 		=	new Property();
$objCalendar	=   new CalendarTool ();
$objSearch	    =   new Search();
$email	 		=   new Email();

$albm_id		=	$_REQUEST["albm_id"] 		? $_REQUEST["albm_id"] 			: "";
$img_id			=	$_REQUEST["img_id"] 		? $_REQUEST["img_id"] 			: "";
$imgNo 			= 	$_REQUEST["imgNo"] 			? $_REQUEST["imgNo"] 			: "";
$defid 			= 	$_REQUEST["defid_img"] 			? $_REQUEST["defid_img"] 			: "";

$content		=	$photo->changePhotos($albm_id,$img_id,$imgNo,$defid);
switch($_REQUEST['act']) {
	case "":
		list($img_id,$img_ext) = $content;
		echo $albm_id."|".$img_id."|".$img_ext."|".($imgNo+2);
		exit;
		break;
	case "place_bid":
		$arr = array();
		$arr['user_id'] = $_REQUEST['user_id'];
		$arr['pricing_id'] = $_REQUEST['pricing_id'];
		$arr['bid_amount'] = $_REQUEST['bid_amount'];
		$flyer->setArrData($arr);
		if ($msg=$flyer->placeBid())
		{
			$pricing_det = $flyer->getPricingDetails($arr['pricing_id']);
			$user_det    = $user->getUserDetails($arr['user_id']);
			$flyer_id    = $flyer->getFlyerIDByAlbum($pricing_det->album_id);
			$flyer_det   = $flyer->GetFlyerData($flyer_id);
			$mail_header = array();
			$mail_header['from'] 	= 	$framework->config['site_name']."<".$framework->config['admin_email'].">";
			$mail_header["to"]   = $pricing_det->email;
			$dynamic_vars = array();
			$dynamic_vars['BID_USER'] = $user_det['username'];
			($flyer_det['flyer_name']!="")? $prop_name = $flyer_det['flyer_name'] : $prop_name = $flyer_det['title'];
			
			$dynamic_vars['OWNER_NAME']    = $pricing_det->first_name . " " . $pricing_det->last_name;
			$dynamic_vars['PROPERTY_NAME'] = $prop_name;
			$dynamic_vars['PROPERTY_DESC'] = $flyer_det['description'];
			$dynamic_vars['START_DATE'] = date($framework->config['date_format_new'],strtotime($pricing_det->start_date));
			$dynamic_vars['END_DATE'] = date($framework->config['date_format_new'],strtotime($pricing_det->rental_end_date));
			$dynamic_vars['DURATION'] = $pricing_det->duration;
			$dynamic_vars['UNIT'] = $pricing_det->unit;
			
			$dynamic_vars['BID_AMT'] = "$".$arr['bid_amount'];
			$dynamic_vars['BID_DATE'] = date($framework->config['date_format_new']);
			
			if ($msg[1]=="new")
			{
				$email->send("Property_Auction_New_Bid_Owner",$mail_header,$dynamic_vars);
			}
			elseif($msg[1]=="update")
			{
				$dynamic_vars['PRE_BID_AMT'] = "$".$msg[2];
				$email->send("Property_Auction_Modify_Bid_Owner",$mail_header,$dynamic_vars);
			}
			
			$max_bid = $flyer->getMaximumBid($arr['pricing_id']);
			echo $msg[0]."|1"."|".$max_bid."|".$user_det['username']."|".number_format($arr['bid_amount'],2)."|".date($framework->config['date_format_new']);
		}
		else 
		{
			echo ($flyer->getErr()."|0");
		}
		exit;
		break;	
	case "select_bid":
		$arr = array();
		$arr['bid_id'] = $_REQUEST['bid_id'];
		$arr['pricing_id'] = $_REQUEST['pricing_id'];
		$arr['status'] = "Y";
		
		////////
		if($_REQUEST['auction_delete']=='y'){
			$arr['auction_delete'] = "Y";
		}	
		////////////////////
		
		
		$flyer->setArrData($arr);
		
		
		if ($msg=$flyer->select_bid())
		{
			$pricing_det = $flyer->getPricingDetails($arr['pricing_id']);
			$bid_det  = $flyer->getBidDetails($arr['bid_id']);
			$bid_user_det    = $user->getUserDetails($bid_det['user_id']);
			$flyer_id    = $flyer->getFlyerIDByAlbum($pricing_det->album_id);
			$flyer_det   = $flyer->GetFlyerData($flyer_id);
			
			$mail_header = array();
			$mail_header['from'] 	= 	$framework->config['site_name']."<".$framework->config['admin_email'].">";
			$mail_header["to"]   = $bid_user_det['email'];
			$dynamic_vars = array();
			$dynamic_vars['BID_USER'] = $bid_user_det['username'];
			($flyer_det['flyer_name']!="")? $prop_name = $flyer_det['flyer_name'] : $prop_name = $flyer_det['title'];
			
			$dynamic_vars['PROPERTY_NAME'] = $prop_name;
			$dynamic_vars['PROPERTY_DESC'] = $flyer_det['description'];
			$dynamic_vars['START_DATE'] = date($framework->config['date_format_new'],strtotime($pricing_det->start_date));
			$dynamic_vars['END_DATE'] = date($framework->config['date_format_new'],strtotime($pricing_det->rental_end_date));
			$dynamic_vars['DURATION'] = $pricing_det->duration;
			$dynamic_vars['UNIT'] = $pricing_det->unit;
			$dynamic_vars['BID_AMT'] = "$".$bid_det['bid_amount'];
			$dynamic_vars['BID_DATE'] = date($framework->config['date_format_new'],strtotime($bid_det['bid_date']));
			$dynamic_vars['LINK']     =  "<a href=\"".SITE_URL."/".makeLink(array("mod"=>"album", "pg"=>"booking"), "act=bid_payment&bid_id={$arr['bid_id']}")."\"><u><strong>Click Here</strong></u></a>";
			$dynamic_vars['CANCEL_LINK']   =  "<a href=\"".SITE_URL."/".makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=bid_reject&bid_id={$arr['bid_id']}")."\"><u><strong>Cancel Payment</strong></u></a>";
			
			if ($msg['type']=="new")
			{
				    $email->send("Property_Auction_Win",$mail_header,$dynamic_vars);
			}
			elseif ($msg['type']=="update")
			{
				$email->send("Property_Auction_Win",$mail_header,$dynamic_vars);
				
				$lost_bids = $flyer->getAllBidsByPricing($arr['pricing_id']);
				 $pre_bid   = $msg['pre_bid'];
				
				for($i=0;$i<sizeof($lost_bids);$i++)
				{
					if ($arr['bid_id']!=$lost_bids[$i]['id'])
					{
						$lost_bid_det = $user->getUserdetails($lost_bids[$i]['user_id']);
						$dynamic_vars['BID_USER'] = $lost_bid_det['username'];
						$dynamic_vars['BID_AMT'] = "$".$lost_bids[$i]['bid_amount'];
						$dynamic_vars['BID_DATE'] = date($framework->config['date_format_new'],strtotime($lost_bids[$i]['bid_date']));

						$mail_header["to"]   = $lost_bid_det['email'];
						
						if ($pre_bid==$lost_bids[$i]['id'])
						{
							$email->send("Property_Auction_Winner_Replace",$mail_header,$dynamic_vars);
						}
						
					}	
				}
			}
			elseif ($msg['type']=="delete")
			{
				if($msg['status']==0)
				{
					$lost_bids = $flyer->getAllBidsByPricing($arr['pricing_id']);
					
					for($i=0;$i<sizeof($lost_bids);$i++)
					{
						$lost_bid_det = $user->getUserdetails($lost_bids[$i]['user_id']);
						$dynamic_vars['BID_USER'] = $lost_bid_det['username'];
						$dynamic_vars['BID_AMT'] = "$".$lost_bids[$i]['bid_amount'];
						$dynamic_vars['BID_DATE'] = date($framework->config['date_format_new'],strtotime($lost_bids[$i]['bid_date']));
						$mail_header["to"]   = $lost_bid_det['email'];
						$email->send("Property_Auction_delete",$mail_header,$dynamic_vars);
						$flyer->bidDelete($lost_bids[$i]['id']);
					}
					$flyer->auctionDelete($arr['pricing_id']);
				}
				else
				{
					echo $msg['msg'];
					exit;
				}
	
			}
			echo trim($arr['pricing_id']);
		}
		exit;
		break;	
	case "delete_bid":
		if ($flyer->deleteBidAjax($_REQUEST['pricing_id'],$_REQUEST['user_id']))
		{
			$msg = $framework->MOD_VARIABLES['MOD_MSGS']['MSG_BID_DEL']."|"."1";
		}
		else 
		{
			$msg = $flyer->getErr()."|"."0";
		}
		echo $msg;
		exit;
		break;	
	case "get_bids_list":
		$userid = $_REQUEST['user_id'];
		$rs=$flyer->getBidCountByUser($userid);
		$bid_count=$flyer->getRejectedBidCountByOwner($userid);
		$bid_count1=$flyer->getRejectedBidCountByUser($userid);
		$bid_count2=$flyer->getBidsWonCount($userid);
		if(count($rs) > 0)
		{
			$str.='
			<div style="float:left;width:24%" class="bodytext" align="left"><strong>'.$MOD_VARIABLES["MOD_LABELS"]["LBL_BID_COUNT"].'</strong></div>
			<div style="width:2%;float:left" class="bodytext">&nbsp;</div>
			<div style="float:left;width:28%" class="bodytext"><strong>'.$MOD_VARIABLES["MOD_LABELS"]["LBL_BIDS_REJECTED_OWNER"].'</strong></div>
			<div style="width:2%;float:left" class="bodytext">&nbsp;</div>
			<div style="float:left;width:28%" class="bodytext"><strong>'.$MOD_VARIABLES["MOD_LABELS"]["LBL_BIDS_REJECTED_USER"].'</strong></div>
			<div style="width:2%;float:left" class="bodytext">&nbsp;</div>
			<div style="float:left" class="bodytext"><strong>'.$MOD_VARIABLES["MOD_LABELS"]["LBL_BIDS_WON"].'</strong></div>
			<div style="clear:both"><!-- --></div>';
			
				$str.='<div style="width:100%">
					<div style="width:2%;float:left" class="bodytext">&nbsp;</div>
					<div align="center" style="float:left;width:13%; ">'.$rs['bid_count'].'</div>
					<div style="width:2%;float:left" class="bodytext">&nbsp;</div>
					<div align="center" style="float:left;width:38%; ">'.$bid_count['bid_count'].'</div>
					<div style="width:2%;float:left" class="bodytext">&nbsp;</div>
					<div align="center" style="float:left;width:30%; ">'.$bid_count1['bid_count'].'</div>
					<div style="width:2%;float:left" class="bodytext">&nbsp;</div>
					<div align="center" style="float:left;width:2%; ">'.$bid_count2['bid_count'].'</div>
					</div>';
			  $str.='<div style="clear:both"><!-- --></div>
					 <div style="clear:both">&nbsp;</div>';	
		}
		echo $str;
		break;
	case "reject_offer":
		$bid_id = $_REQUEST['bid_id'];
		$flyer->rejectOffer($bid_id);
		 $bid_det  			= $flyer->getBidDetails($bid_id);
		 $pricing_det 		= $flyer->getPricingDetails($bid_det['pricing_id']);
		 $bid_user_det   	= $objUser->getUserDetails($bid_det['user_id']);
		 $flyer_id    		= $flyer->getFlyerIDByAlbum($pricing_det->album_id);
		 $flyer_det   		= $flyer->GetFlyerData($flyer_id);
		 $AlbumDetails		= $album->getAlbumDetails($pricing_det->album_id);
		 $userDetails		= $user->getUserdetails($AlbumDetails['user_id']);
		 
		 $mail_header = array();
		 $mail_header['from'] 	= $framework->config['site_name']."<".$framework->config['admin_email'].">";
		 $mail_header["to"]   	= $userDetails['email'];
		 $dynamic_vars = array();
		 $dynamic_vars['BID_USER'] = $bid_user_det['username'];
		 ($flyer_det['flyer_name']!="")? $prop_name = $flyer_det['flyer_name'] : $prop_name = $flyer_det['title'];
			
		 $dynamic_vars['PROPERTY_NAME'] = $prop_name;
		 $dynamic_vars['PROPERTY_DESC'] = $flyer_det['description'];
		 $dynamic_vars['START_DATE'] = date($framework->config['date_format_new'],strtotime($pricing_det->start_date));
		 $dynamic_vars['END_DATE'] = date($framework->config['date_format_new'],strtotime($pricing_det->rental_end_date));
		 $dynamic_vars['DURATION'] = $pricing_det->duration;
		 $dynamic_vars['UNIT'] = $pricing_det->unit;
		 $dynamic_vars['BID_AMT'] = "$".$bid_det['bid_amount'];
		 $dynamic_vars['BID_DATE'] = date($framework->config['date_format_new'],strtotime($bid_det['bid_date']));
		 $dynamic_vars['OWNER_EMIL'] = $userDetails['email'];
		 $dynamic_vars['OWNER_FIRSTNAME'] = $userDetails['first_name'];
		 $dynamic_vars['OWNER_LASTNAME'] = $userDetails['last_name'];
		
		 $dynamic_vars['BID_USER'] = $bid_user_det['username'];
		 $dynamic_vars['BID_AMT'] =  "$".$bid_det['bid_amount'];
		 $dynamic_vars['BID_DATE'] = date($framework->config['date_format_new'],strtotime($lost_bids['bid_date']));
		 $email->send("Property_Bid_Payment_Rejected",$mail_header,$dynamic_vars);
		echo trim($bid_id);
		exit;
		break;	
	case "bid_cancel":
		$bid_id = $_REQUEST['bid_id'];
		$flyer->cancelBid($bid_id);
		$bid_det  			= $flyer->getBidDetails($bid_id);
		 $pricing_det 		= $flyer->getPricingDetails($bid_det['pricing_id']);
		 $bid_user_det   	= $objUser->getUserDetails($bid_det['user_id']);
		 $flyer_id    		= $flyer->getFlyerIDByAlbum($pricing_det->album_id);
		 $flyer_det   		= $flyer->GetFlyerData($flyer_id);
		 $AlbumDetails		= $album->getAlbumDetails($pricing_det->album_id);
		 $userDetails		= $user->getUserdetails($AlbumDetails['user_id']);
		 
		 $mail_header = array();
		 $mail_header['from'] 	= $framework->config['site_name']."<".$framework->config['admin_email'].">";
		 $mail_header["to"]   	= $userDetails['email'];
		 $dynamic_vars = array();
		 $dynamic_vars['BID_USER'] = $bid_user_det['username'];
		 ($flyer_det['flyer_name']!="")? $prop_name = $flyer_det['flyer_name'] : $prop_name = $flyer_det['title'];
			
		 $dynamic_vars['PROPERTY_NAME'] = $prop_name;
		 $dynamic_vars['PROPERTY_DESC'] = $flyer_det['description'];
		 $dynamic_vars['START_DATE'] = date($framework->config['date_format_new'],strtotime($pricing_det->start_date));
		 $dynamic_vars['END_DATE'] = date($framework->config['date_format_new'],strtotime($pricing_det->rental_end_date));
		 $dynamic_vars['DURATION'] = $pricing_det->duration;
		 $dynamic_vars['UNIT'] = $pricing_det->unit;
		 $dynamic_vars['BID_AMT'] = "$".$bid_det['bid_amount'];
		 $dynamic_vars['BID_DATE'] = date($framework->config['date_format_new'],strtotime($bid_det['bid_date']));
		 $dynamic_vars['OWNER_EMIL'] = $userDetails['email'];
		 $dynamic_vars['OWNER_FIRSTNAME'] = $userDetails['first_name'];
		 $dynamic_vars['OWNER_LASTNAME'] = $userDetails['last_name'];
		
		 $dynamic_vars['BID_USER'] = $bid_user_det['username'];
		 $dynamic_vars['BID_AMT'] =  "$".$bid_det['bid_amount'];
		 $dynamic_vars['BID_DATE'] = date($framework->config['date_format_new'],strtotime($lost_bids['bid_date']));
		 $email->send("Property_Bid_Payment_Rejected",$mail_header,$dynamic_vars);
		echo trim($bid_id);
		exit;
		break;	
	case "end_bidding":
		$id = $_REQUEST['pricing_id'];
		$flyer->updateAuction($id);
		echo trim($id);
		exit;
	case "restart_auction":
		$id = $_REQUEST['pricing_id'];
		$flyer->restartAuction($id);
		echo trim($id);
		exit;	
		break;		
		
}
?>