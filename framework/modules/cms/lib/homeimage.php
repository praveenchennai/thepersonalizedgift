<?php 
/**
 * CMS Module Add Images For Home Page
 *
 * @author Jipson Thomas
 * @package defaultPackage
 */
// echo SITE_URL;

error_reporting(0);
switch($_REQUEST['act']) {
	case "xmlecho":  
	#ob_start(); 

	header("Content-Type:text/xml");
	$_xml ="<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r\n";
	$_xml .="<xml>\r\n";

	$_xml .="<calsilkscreen>\r\n";
		$_xml .="<bimg id='1' src='".SITE_URL."/modules/cms/images/thumb/home_img_1.jpg' link='".SITE_URL."/index.php?mod=product&amp;pg=list&amp;act=quote' />\r\n";
		$_xml .="<bimg id='2' src='".SITE_URL."/modules/cms/images/thumb/home_img_2.jpg' link='".SITE_URL."/index.php?mod=product&amp;pg=list&amp;act=quote' />\r\n";
		$_xml .="<bimg id='3' src='".SITE_URL."/modules/cms/images/thumb/home_img_3.jpg' link='".SITE_URL."/index.php?mod=product&amp;pg=list&amp;act=quote' />\r\n";
	$_xml .="</calsilkscreen>\r\n";
	$_xml .="</xml>";
	echo $_xml;
	
	exit;
 	#ob_end_clean();
	break;
}
if($_REQUEST['manage']=="manage"){
	authorize_store();
	$framework->tpl->assign("MOD","store") ;
	$framework->tpl->assign("PG","cms_menu") ;
	$framework->tpl->assign("STORE_PERMISSION",$store->getStorePermissionById($store_id,$module_menu_id)) ;
}else{
	authorize();
	$framework->tpl->assign("MOD","cms") ;
	$framework->tpl->assign("PG","menu") ;
	$STORE = array('hide'=>'Y','add'=>'Y','edit'=>'Y','delete'=>'Y');
	$framework->tpl->assign("STORE_PERMISSION",$STORE) ;
	
	
}
############################################################
if($_SERVER['REQUEST_METHOD']=="POST"){
	if($_FILES["file1"]['name']!=''){
		$fname			=	basename($_FILES['file1']['name']);
		$ftype			=	$_FILES['file1']['type'];
		$tmpname		=	$_FILES['file1']['tmp_name'];
		$dir			=	SITE_PATH."/modules/cms/images/";
		$file1			=	$dir."thumb/";
		$resource_file	=	$dir.$fname;
		$path_parts 	= 	pathinfo($fname);
		############################################################
		$save_filename	=	"home_img_1.".$path_parts['extension'];
		$save_filenamet	=	"home_imgt_1.".$path_parts['extension'];
		_upload($dir,$save_filenamet,$tmpname,1,100,150);
		_upload($dir,$save_filename,$tmpname,1,260,675);
	//thumbnail($path,$path,$save_filename,260,675,$mode,$save_filename);
	}
	if($_FILES["file2"]['name']!=''){
		$fname			=	basename($_FILES['file2']['name']);
		$ftype			=	$_FILES['file2']['type'];
		$tmpname		=	$_FILES['file2']['tmp_name'];
		$dir			=	SITE_PATH."/modules/cms/images/";
		$file1			=	$dir."thumb/";
		$resource_file	=	$dir.$fname;
		$path_parts 	= 	pathinfo($fname);
		############################################################
		$save_filename	=	"home_img_2.".$path_parts['extension'];
		$save_filenamet	=	"home_imgt_2.".$path_parts['extension'];
		_upload($dir,$save_filenamet,$tmpname,1,100,150);
		_upload($dir,$save_filename,$tmpname,1,260,675);
	}
	if($_FILES["file3"]['name']!=''){
		$fname			=	basename($_FILES['file3']['name']);
		$ftype			=	$_FILES['file3']['type'];
		$tmpname		=	$_FILES['file3']['tmp_name'];
		$dir			=	SITE_PATH."/modules/cms/images/";
		$file1			=	$dir."thumb/";
		$resource_file	=	$dir.$fname;
		$path_parts 	= 	pathinfo($fname);
		############################################################
		$save_filename	=	"home_img_3.".$path_parts['extension'];
		$save_filenamet	=	"home_imgt_3.".$path_parts['extension'];
		_upload($dir,$save_filenamet,$tmpname,1,100,150);
		_upload($dir,$save_filename,$tmpname,1,260,675);
	}

}
###########################################################

include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");

$cms = new Cms();

$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/cms/tpl/homeimg.tpl");
if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}

?>