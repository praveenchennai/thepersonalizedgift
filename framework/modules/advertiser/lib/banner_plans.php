<?php
include_once(SITE_PATH."/modules/banner/lib/class.banner.php");
$banner = new Banner();
checkLogin();
switch($_REQUEST['act']) {
    case "list":		
		list($rs, $numpad) = $banner->planList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
    	$framework->tpl->assign("PLAN_LIST", $rs);
        $framework->tpl->assign("PLAN_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/banner/tpl/plan_list.tpl");
        break;
    case "form":
			include_once(SITE_PATH."/includes/colorPicker/include.php");		
			$framework->tpl->assign("SECTION_LIST", $banner->campSectionList());
			$framework->tpl->assign("FILE_LIST", $banner->fileSectionList());
			$framework->tpl->assign("FONT_LIST", $banner->fontSectionList());		
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $req 		= 	&$_REQUEST;		
			$fname		=	basename($_FILES['image']['name']);
			$ftype		=	$_FILES['image']['type'];
			$tmpname	=	$_FILES['image']['tmp_name'];	
			if($_REQUEST['btn_save']!=""){
				if( ($message = $banner->planAddEdit($req,$fname,$tmpname)) === true ) {
					redirect(makeLink(array("mod"=>"banner", "pg"=>"banner_plans"),"act=list"));
				}
			}
            $framework->tpl->assign("MESSAGE", $message);
        }
        if($message) {
            $framework->tpl->assign("PLANS", $_POST);
        } elseif($_REQUEST['id']) {		
			$plan_array=$banner->getPlans($_REQUEST['id']);
			
			if($_SERVER['REQUEST_METHOD']!="POST"){				
				$_REQUEST=$plan_array;
			}else if($_POST['dmo_filetype']=='txt' || $_POST['dmo_filetype']=='html'){				
				$_REQUEST['dmo_bg']				=	$plan_array['dmo_bg'];
				$_REQUEST['dmo_border']			=	$plan_array['dmo_border'];
				$_REQUEST['dmo_borderclr']		=	$plan_array['dmo_borderclr'];
				$_REQUEST['dmo_font']			=	$plan_array['dmo_font'];
				$_REQUEST['dmo_fontclr']		=	$plan_array['dmo_fontclr'];
				$_REQUEST['dmo_fontsize']		=	$plan_array['dmo_fontsize'];
				$_REQUEST['dmo_linkcolor']		=	$plan_array['dmo_linkcolor'];
				$_REQUEST['dmo_uline']			=	$plan_array['dmo_uline'];
				$_REQUEST['dmo_content']		=	$plan_array['plan_example'];
			}
			$framework->tpl->assign("DEMOCONTENT", $plan_array['plan_example']);
			$file_types=$plan_array['file_types'];			
			$file_typearr=explode(",", $file_types);					
			$framework->tpl->assign("FILE_TYPES", $file_typearr);					
        }
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/banner/tpl/plan_form.tpl");
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
				if($plan_array['dmo_filetype']=="agif"){					
					$extension	=	"gif";
				}else{
					$extension	=	$plan_array['dmo_filetype'];
				}
				if($plan_array['dmo_filetype']=="txt" || $plan_array['dmo_filetype']=="html" ){
					$file_path	= $plan_array['plan_example'];	
				}else{
					$file_path= "/modules/banner/images/plans/".$plan_array['plan_example'];	
				}			
					$fileinfo=getplanfileDetails($extension,$plan_array['camp_width'],$plan_array['camp_height'],$file_path,$plan_array['dmo_url'],$_REQUEST['plan_id']);
					
								
					$framework->tpl->assign("FILEINFO",$fileinfo);
			}	
			$framework->tpl->assign("PLANDETAILS",$plan_array);
			$framework->tpl->assign("FILE_TYPE", $dispFile);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/banner/tpl/plan_popup.tpl");
	break;
}
if($_REQUEST['plan_id']!=""){
	$framework->tpl->display("default/popup.tpl");
}else{
	$framework->tpl->display("default/inner.tpl");
}
?>