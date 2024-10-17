<?php
include_once(SITE_PATH."/modules/banner/lib/class.banner.php");
$banner = new Banner();
checkLogin();
switch($_REQUEST['act']) {
    case "list":		
		if($_REQUEST['stat']!=""){
			$status		=	$_REQUEST['stat'];
			$framework->tpl->assign("MESSAGE", $status);
		}
		list($rs, $numpad) = $banner->accountList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
    	$framework->tpl->assign("ACCOUNT_LISYT", $rs);
        $framework->tpl->assign("ACCOUNT_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/banner/tpl/account_list.tpl");
        break;
    case "view":			
		$ban_id=$_REQUEST['ban_id'];		
		$ban_array=$banner->getBanners($ban_id);
		$extension	=	$ban_array['file_type'];			
				if($extension=="agif"){
					$file_extension="gif";
				}else{
					$file_extension=$extension;
				}
					$file_path	=	$ban_array['content'];
					$width		=	$ban_array['camp_width'];
					$height		=	$ban_array['camp_height'];
					$url		=	$ban_array['url'];						
			$fileinfo=getBannerfileDetails($file_extension,$width,$height,$file_path,$url,$ban_id,$view="");										
			$framework->tpl->assign("BANNER_DETAILS",$ban_array);
			$framework->tpl->assign("FILE_INFO", $fileinfo);		
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/banner/tpl/ban_view.tpl");
        break;
    case "delete":
        $banner->bannerplanDelete($_REQUEST['id']);
        redirect(makeLink(array("mod"=>"banner", "pg"=>"banner_plans"), "act=list"));
        break;
	case "plan":			
			$plan_array=$banner->getPlans($_REQUEST['plan_id']);
			$file_types=$plan_array['file_types'];			
			$file_typearr=explode(",", $file_types);			
			$dispFile="";
			for($i=0;$i<sizeof($file_typearr);$i++){
				if ($file_typearr[$i]!=""){
					$dispFile=$dispFile."&nbsp;&nbsp;".$file_typearr[$i];
				}
			}
			if($plan_array['plan_example']!=""){
				$file_extension=explode(".",$plan_array['plan_example']);
				$file_path= "/modules/banner/images/plans/".$plan_array['plan_example'];							
				$fileinfo=getBannerfileDetails($file_extension[1],$plan_array['camp_width'],$plan_array['camp_height'],$file_path,$url="",$ban_id="");				
				$framework->tpl->assign("FILEINFO",$fileinfo);
			}	
			$framework->tpl->assign("PLANDETAILS",$plan_array);
			$framework->tpl->assign("FILE_TYPE", $dispFile);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/banner/tpl/plan_popup.tpl");
	break;
	case "renew":
		$today			=	date('Y-m-d H:i:s');		
		$ban_id			=	$_REQUEST['ban_id'];		
		$bannerDetails	=	$banner->getBanners($ban_id);
		$start_date		=	$bannerDetails['start_date'];
		$end_date		=	$bannerDetails['end_date'];
		$duration		=	$bannerDetails['duration'];
		$duration_type	=	$bannerDetails['duration_type'];
		if($duration_type=='M' || $duration_type=='m'){
			$noDays		=	30*$duration;
		}else{
			$noDays		=	$duration;
		}
		if($end_date=="" || $end_date<$today){
			$ban_date	=	$today;
		}else{
			$ban_date	=	$end_date;
		}
		$message	=	$banner->renewAccount($ban_id,$ban_date,$noDays);		
        redirect(makeLink(array("mod"=>"banner", "pg"=>"myaccount"), "act=list&stat=$message"));
	break;
}
if($_REQUEST['plan_id']!=""){
	$framework->tpl->display("default/popup.tpl");
}else{
	$framework->tpl->display("default/inner.tpl");
}
?>