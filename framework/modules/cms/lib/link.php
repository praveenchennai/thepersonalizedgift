<?php 
/**
* CMS Module Add Link
*
* @author sajith
* @package defaultPackage
*/


if($_REQUEST['manage']=="manage"){
	authorize_store();
}else{
	authorize();
}
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
$cms = new Cms();
$cat = new Category();
$req = &$_REQUEST;
$cms_cat_id	=	$_REQUEST["parent_id"] ? $_REQUEST["parent_id"] : "0";

switch($_REQUEST['act']) {
	case "delete":
		$cms->linkDelete($_REQUEST['id']);
		setMessage("CMS Link Deleted Successfully!", MSG_SUCCESS);
		redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>"link"), "cms_id=".$_REQUEST['cms_id']."&parent_id=".$_REQUEST['parent_id']."&area=".$_REQUEST['area']));
		break;
	case "sort":
		parse_str($_REQUEST['sortOrder'], $sortOrder);
		$linkOrder = $sortOrder['linkOrder'];
		$cms->sortLinks($linkOrder);
		// updating store_cms for all stores 20-07-07
		if(isset($_POST['btn_updateexisting']))
		{
			$cms->updateStore($_REQUEST['area']);
		}
		#-------------------------------------------------------
		setMessage("Menu Ordered Successfully", MSG_SUCCESS);
		redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>"link"), "cms_id=".$_REQUEST['cms_id']."&parent_id=".$_REQUEST['parent_id']."&area=".$_REQUEST['area']));
		break;
	
	case "add":
	
		if($_REQUEST['menu_id']) {
			$menuDetails 	= $cms->menuGet($_REQUEST['menu_id']);
			$req['title'] 	= $menuDetails['name'];
			$req['url'] 	= $menuDetails['seo_url'].".php";
		} elseif ($_REQUEST['cat_id']) {
		
			$req['title'] 	= $cat->getCategoryname($_REQUEST['cat_id']);
			$req['url'] 	= makeLink(array("mod"=>"product", "pg"=>"list"), "act=list&cat_id=".$req['cat_id']);
			
		}
	default:
	
		if($_SERVER['REQUEST_METHOD']=="POST" || $_REQUEST['menu_id'] || $_REQUEST['cat_id']) {
				
		$counter = $_REQUEST['count'] ? $_REQUEST['count'] : 2;		
			
		for($i=1;$i<$counter;$i++)
		{
		
			$fname			=	basename($_FILES['link_image'.$i]['name']);
			$ftype			=	$_FILES['link_image'.$i]['type'];
			$tmpname		=	$_FILES['link_image'.$i]['tmp_name'];
			
			$mfname			=	basename($_FILES['link_oimage'.$i]['name']);
			$mftype			=	$_FILES['link_oimage'.$i]['type'];
			$mtmpname		=	$_FILES['link_oimage'.$i]['tmp_name'];
		
			$req['fname']	=	$fname;
			$req['tmpname']	=	$tmpname;
			$req['mfname']	=	$mfname;
			$req['mtmpname']=	$mtmpname;
			
			
			
			if ( $global ['cms_thumb_image1'] )	{
				list( $req['cms_thumb_width1'] , $req['cms_thumb_height1'] ) = explode ( "," , $global ['cms_thumb_image1'] );
			}
			
			if ( $global ['cms_thumb_image2'] )	{
				list( $req['cms_thumb_width2'] , $req['cms_thumb_height2'] ) = explode ( "," , $global ['cms_thumb_image2'] );
			}
			
			
			if($i==1){
			$req['template']='';
			$cms_id = $cms->linkAddEdit($req);
			}
			
			$req['template']=	$_REQUEST['template_id'.$i]."_";
					
			
			if( ($message = $cms->linkAddEditMultiTemp($req,$cms_id)) === true ) {
			$action = $req['id'] ? "Updated" : "Added";
			setMessage("CMS Link $action Successfully", MSG_SUCCESS);
			}#ends the if loop
		}#ends the for loop
		
		
			redirect(makeLink(array("mod"=>$_REQUEST['mod'], "pg"=>"link"), "cms_id=".$_REQUEST['cms_id']."&parent_id=".$_REQUEST['parent_id']."&area=".$req['area']));
			
			
			
			setMessage($message);
		}
		if($message) {
			$framework->tpl->assign("LINK", $_POST);
		} elseif($_REQUEST['id']) {
		
			$framework->tpl->assign('LINK', $cms->linkGet($_REQUEST['id']));
		}
		
		 $featured=$cms->getFeatured();
		//echo $featured['value'];
		$framework->tpl->assign('FEATURED',$featured);
		//========= this code is for getting multilple image upload options in cms =============
		  $temp =$cms->template();
		  $values1 = array();
		  $var4= array();
		  $values1	=	explode('|',$temp);
			for($i=0;$i<count($values1);$i++){
				list($var1,$var2)=explode('^',$values1[$i]);
					 $var4[$i]=$var1;
				 }
				$framework->tpl->assign("TEMPLATE",  $var4);
		//=======================================================================================
		
		$framework->tpl->assign("AREA", $_REQUEST['area']);
		
		$framework->tpl->assign("LINK_LIST", $cms->linkList($_REQUEST['area']));
		
		//print_r($cms->linkList($_REQUEST['area']));
}

$framework->tpl->assign("DISPLAY_PATH", $cat->getCompletePathforCms($cms_cat_id, $sId, $fId,$_REQUEST['area']));
$framework->tpl->assign("LINK_AREA_LIST", $cms->sectionLinkArea ()); 



$framework->tpl->assign("CAT_LIST", $cat->getChildCategories($cms_cat_id));//print_r($cat->getChildCategories($cms_cat_id));
$framework->tpl->assign("CMS_LIST", $cms->sectionCombo());
$menuList = $cms->menuList($_REQUEST['cms_id']);

$framework->tpl->assign("MENU_LIST", $menuList);
// for showing update for all stores 20-07-07
$areaFlag	=	0;
if($_REQUEST['area']=="store_top") { $areaFlag=1; }
if($_REQUEST['area']=="store_topsub") { $areaFlag=1; }
if($_REQUEST['area']=="store_left") { $areaFlag=1; }
if($_REQUEST['area']=="store_bottom") { $areaFlag=1; }
if($_REQUEST['area']=="store_bottomsub") { $areaFlag=1; }
$framework->tpl->assign("STORE_AREA_FLAG", $areaFlag);//print_r($_REQUEST['area']);
// end for showing update for all stores 20-07-07


/* 
	LIMIT OF LINK AREA 
	11-Feb-2008
	Aneesh Aravindan
*/
$framework->tpl->assign("LINK_AREA_LIMITS",$cms->getLimitLinkArea($_REQUEST['area']) );


$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/cms/tpl/link.tpl");
if($_REQUEST['manage']=="manage"){
	$framework->tpl->display($global['curr_tpl']."/store.tpl");
}else{
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}

?>