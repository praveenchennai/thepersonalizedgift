<?php
ob_start();
include_once(SITE_PATH."/modules/banner/lib/class.banner.php");
$banner = new Banner();
switch($_REQUEST['act']) {
	case "generatescript":
			$req	=	&$_REQUEST;		
			list($rs, $numpad) = $banner->adsscriptList($req,'Y',$_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $orderBy);				
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
						$fileinfo=getBannerfileDetails($file_extension,$width,$height,$file_path,$url,$ban_id,'Y');										
						print "phpadsbanner = '$fileinfo';\n";
						print "document.write(phpadsbanner);\n";
						exit;		
				}
	break;	
	case "selscript":
			checkLogin();
			$framework->tpl->assign("SELECTION_LIST", $banner->campSectionList());	
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/banner/tpl/genscript.tpl");      
	break;	
	case "generatescriptall":
		$req	=	&$_REQUEST;		
			list($rs, $numpad) = $banner->adsscriptListall($req,'Y',$_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $orderBy);				
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
						$fileinfo=getBannerfileDetails($file_extension,$width,$height,$file_path,$url,$ban_id,'Y');										
						print "phpadsbanner = '$fileinfo';\n";
						print "document.write(phpadsbanner);\n";
						exit;		
				}
				exit;
	break;	
}
 if($_REQUEST['act']=="generatescript"){
	exit;
}else{
	$framework->tpl->display("default/inner.tpl");
}
?>