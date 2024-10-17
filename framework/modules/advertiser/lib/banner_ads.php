<?php
ob_start();
include_once(FRAMEWORK_PATH."/modules/advertiser/lib/class.banner.php");
$banner = new Banner();
switch($_REQUEST['act']) {
    case "list":
		$user_id	=	$_REQUEST['user_id'];		
	 	$framework->tpl->assign("ERROR", $_REQUEST['stat']);	
		$framework->tpl->assign("REQ_USER", $user_id);
		list($rs, $numpad) = $banner->adsList($user_id,$_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
    	$framework->tpl->assign("ADS_LIST", $rs);
        $framework->tpl->assign("ADS_NUMPAD", $numpad);
		$framework->tpl->assign("CHKURL",$global['hide_banner']);
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/advertiser/tpl/ads_list.tpl");
        break;
    case "form":	
			if($_REQUEST['admin_banner']){
				$admin_banner	=	$_REQUEST['admin_banner'];
				$framework->tpl->assign("ADMIN_USER",$admin_banner);
			}
			$req_val = $_REQUEST;				
			include_once(SITE_PATH."/includes/colorPicker/include.php");			
			$framework->tpl->assign("SELECTION_LIST", $banner->campSectionList());			
			$framework->tpl->assign("FILE_LIST", $banner->fileSectionList());
			$framework->tpl->assign("FONT_LIST", $banner->fontSectionList());			
			if($req_val['camp_id']&&$req_val['file_type']){
				$framework->tpl->assign("PLAN_LIST", $banner->planSectionList($req_val['camp_id'],$req_val['file_type']));
			}
			
		$framework->tpl->assign("ADS", $_REQUEST);
        if($_SERVER['REQUEST_METHOD'] == "POST") {            		
			$fname		=	basename($_FILES['image']['name']);
			$ftype		=	$_FILES['image']['type'];
			$tmpname	=	$_FILES['image']['tmp_name'];	
			if($_REQUEST['btn_save']!=""){
				$req	=	$_REQUEST;			
				if( ($message = $banner->adsAddEdit($req,$fname,$tmpname)) === true ) {
					if($_SESSION['mem_type']==0){
						redirect(makeLink(array("mod"=>"advertiser", "pg"=>"banner_ads"),"act=list"));
					}else{
						redirect(makeLink(array("mod"=>"advertiser", "pg"=>"myaccount"),"act=list"));
					}
				}
			}
            $framework->tpl->assign("MESSAGE", $message);
        }
		
        if($message) {
            $framework->tpl->assign("ADS", $_POST);
        } elseif($_REQUEST['id']) {
			$plan_array		=	$banner->getAds($_REQUEST['id']);			
			$file_types		=	$plan_array['file_types'];			
			$file_typearr	=	explode(",", $file_types);
			$countFiletypes	=	count($file_typearr);
			
				
			for($i=0;$i<$countFiletypes;$i++){
				if($file_typearr[$i]!=""){
					$fileTypeval[$i]	=	$file_typearr[$i];
					$dispLoadType[$i]	=	loadFiletypes($file_typearr[$i]);
				}
			}	
			
			$check['type_val']		=	$fileTypeval;
			$check['type_display']	=	$dispLoadType;														
			$framework->tpl->assign("FILE_TYPES", $check);			
			$framework->tpl->assign("ADS", $banner->getAds($_REQUEST['id']));
			if($_SERVER['REQUEST_METHOD']!="POST"){			
				$_REQUEST=$banner->getAds($_REQUEST['id']);
			}else if($_POST['file_type']=='txt' || $_POST['file_type']=='html'){	
				$_REQUEST['bg_color']			=	$plan_array['bg_color'];
				$_REQUEST['border']				=	$plan_array['border'];
				$_REQUEST['border_color']		=	$plan_array['border_color'];
				$_REQUEST['text_font']			=	$plan_array['text_font'];
				$_REQUEST['text_font_size']		=	$plan_array['text_font_size'];
				$_REQUEST['text_color']			=	$plan_array['text_color'];
				$_REQUEST['txtlink_nrl']		=	$plan_array['txtlink_nrl'];
				$_REQUEST['txtlink_nrl_uline']	=	$plan_array['txtlink_nrl_uline'];
			}
        }
		$framework->tpl->assign("CHKURL",$global['hide_banner']);
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/advertiser/tpl/ads_form.tpl");
        break;
    case "delete":
        	$banner->banneradsDelete($_REQUEST['id']);
        	if($_SESSION['mem_type']==0){
                redirect(makeLink(array("mod"=>"advertiser", "pg"=>"banner_ads"),"act=list"));
			}else{
				redirect(makeLink(array("mod"=>"advertiser", "pg"=>"myaccount"),"act=list"));
			}
        break;
		case "user":
			checkLogin();			
			$framework->tpl->assign("USER", $banner->getUser($_REQUEST['user_id']));
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/banner/tpl/ads_user.tpl");
		break;
		 case "updatehit":
			 $id	=	$_REQUEST['id'];
       		 $status= $banner->updateClick($id);
        break;
		 case "sus":
		 	 checkLogin();
			 $user_id	=	$_REQUEST['user_id'];
       		 $status= $banner->bannerSuspent($_REQUEST['id'],$_REQUEST['status']);					
        	 redirect(makeLink(array("mod"=>"banner", "pg"=>"banner_ads"), "act=list&stat=$status&user_id=$user_id"));
        break;
		 case "search":		 	
			$framework->tpl->assign("SELECTION_LIST", $banner->campSectionList());
			$framework->tpl->assign("FILE_LIST", $banner->fileSectionList());
			$req = &$_REQUEST;			
			if($_POST['camp_id']){
				$camp_id		=	$_POST['camp_id'];
				$req['camp_id']	=	$_POST['camp_id'];				
			}else{
				$camp_id=$_REQUEST['camp_id'];
			}
			$additionalVar="camp_id=".$camp_id."&btn_search=".$_REQUEST['btn_search'];  		
					
		if($_REQUEST['btn_search']!="") {		
			$framework->tpl->assign("POST", $req);
			$framework->tpl->assign("CAMP_ID", $camp_id);				
			list($rs, $numpad) = $banner->adssearchList($req,'Y',$_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&$additionalVar", OBJECT, $orderBy);
				for($i=0;$i<sizeof($rs);$i++){				
					$extension	=	$rs[$i]->file_type;
					if($extension=="agif"){
						$file_extension	=	"gif";
					}else{
						$file_extension=$extension;
					}
						$file_path	=	$rs[$i]->content;
						$width		=	$rs[$i]->camp_width;
						$height		=	$rs[$i]->camp_height;
						$url		=	$rs[$i]->url;
						$ban_id		=	$rs[$i]->ban_id;				
						$fileinfo	=	getBannerfileDetails($file_extension,$width,$height,$file_path,$url,$ban_id,$view="");
						$rs[$i]->file_info=$fileinfo;
				}	
				$framework->tpl->assign("SEARCH_LIST", $rs);
				$framework->tpl->assign("SEARCH_NUMPAD", $numpad);
			}		
		  $framework->tpl->assign("main_tpl", SITE_PATH."/modules/banner/tpl/search_plan.tpl");      
		  break;		  
		case "banner":
			$ban_id		=	$_REQUEST['ban_id'];	
			$ban_array	=	$banner->getBanners($_REQUEST['ban_id']);
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
			$fileinfo=getBannerfileDetails($file_extension,$width,$height,$file_path,$url,$ban_id,$view="",1);
			$framework->tpl->assign("BANNER_DETAILS",$ban_array);
			$framework->tpl->assign("FILE_INFO", $fileinfo);
			$framework->tpl->assign("CHKURL",$global['hide_banner']);
			$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/advertiser/tpl/ban_popup.tpl");	
		break;
		case "viewads":
			$ban_id		=	$_REQUEST['ban_id'];	
			$ban_array	=	$banner->getBanners($_REQUEST['ban_id']);					
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
			$framework->tpl->assign("FILE_INFO", $fileinfo);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/banner/tpl/ads_popup.tpl");
		break;
		case "adsscript":			
			$ban_array=$banner->getBanners($_REQUEST['ban_id']);					
			$extension=$ban_array['file_type'];			
				if($extension=="agif"){
					$file_extension	=	"gif";
				}else{
					$file_extension=$extension;
				}
					$file_path	=	$ban_array['content'];
					$width		=	$ban_array['camp_width'];
					$height		=	$ban_array['camp_height'];
					$url		=	$ban_array['url'];
					$view		=	'Y';						
					$fileinfo=getBannerfileDetails($file_extension,$width,$height,$file_path,$url,$ban_id,$view);										
					print "phpadsbanner = '$fileinfo';\n";
					print "document.write(phpadsbanner);\n";
					exit;				
				break;				
				case "banlink":
					$ban_id	=	$_REQUEST['ban_id'];
					$view	=	$_REQUEST['view'];					
					if($ban_id && $view=='Y'){
						$banner->updateClick($ban_id);
					}					
					$url	=	$_REQUEST['url'];					
					header("location:$url");				
				break;
				case "expiry":
					checkLogin();					
					$ban_array=$banner->getBanners($_REQUEST['ban_id']);
					$framework->tpl->assign("BAN_ARRAY", $ban_array);
					if($_SERVER['REQUEST_METHOD'] == "POST"){
						  $req 		=	 &$_REQUEST;
						  $status	=	$banner->extendDate($req);
						  if($status){
							  $framework->tpl->assign("MESSAGE", $status);
						  }
					}
					$framework->tpl->assign("main_tpl", SITE_PATH."/modules/banner/tpl/exp_edit.tpl");							
				break;
				
}
if($_REQUEST['act']=="user" || $_REQUEST['act']=="banner" || $_REQUEST['act']=="expiry" || $_REQUEST['act']=="viewads"){
	$framework->tpl->display("default/popup.tpl");
}else if($_REQUEST['act']=="adsscript"){
	exit;
}else{
	//$framework->tpl->display("default/inner.tpl");
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}
?>