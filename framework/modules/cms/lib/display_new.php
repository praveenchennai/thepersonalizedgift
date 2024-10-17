<?php
session_start();
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
//include_once(FRAMEWORK_PATH."/modules/member/lib/language/en_us.lang.php");

//print_r($_REQUEST);


$cms = new Cms();
$FeaturedProducts	=	new Product();

extract($_REQUEST);



if($data) {
	$menuRS = $cms->menuGetByURL($data);
	$menu   = $menuRS['id'];
}


# Set Language Variable

//print_r($MOD_VARIABLES);
# Set Language Variable 

$linktitle=$cms->getcmslinktitle($data);
//print_r($linktitle);
 /*$featured = $linktitle["featured"];
 $framework->tpl->assign("LINK_NAME", $linktitle["title"]);
 $framework->tpl->assign("LINK_ID", $linktitle["id"]);
 $framework->tpl->assign("FEATURED", $featured);
*/
if($linktitle){
	foreach ($linktitle as $newarray){
		if ($newarray['featured'] == '1') {
			 $featured = $newarray["featured"];
			 $framework->tpl->assign("LINK_NAME", $linktitle["title"]);
			 $framework->tpl->assign("LINK_ID", $linktitle["id"]);
			 $framework->tpl->assign("FEATURED", $featured);
		}
	 }
}

/*
*---vinoy-----
*
*/
if($featured=="1"){
$rs = $FeaturedProducts->GetFeaturedItems2($_REQUEST['storename']);
$framework->tpl->assign("FEATURED_ITEMS",$rs);
//print_r($rs);exit;
}



$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
	$framework->tpl->assign("advertisement",1);
	
if($page) {
    $pageRS = $cms->pageGet($page, OBJECT);
	

    $menu   = $pageRS->menu_id;
}


if($menu) {
    $menuRS = $cms->menuGet($menu);
    $section = $menuRS['section_id'];
    $framework->tpl->assign("MENU_NAME", $menuRS['name']);
}

$framework->tpl->assign("SECTION", $cms->sectionGet($section));
$menuList = $cms->menuList($section);

$framework->tpl->assign("MENU", $menuList);

if ($page) {
    $framework->tpl->assign("PAGE", array($pageRS));
	$framework->tpl->assign("PAGE_NUMPAD", "");
    $framework->tpl->assign("PAGE_COUNT", 0);
} elseif($menuList) {
    $menu = $menu ? $menu : $menuList[0]->id;
    list($pageRS, $numpad, $count) = $cms->pageList($menu, $_REQUEST['pageNo'], 1);
 
    if(!$data)$data = $menuList[0]->seo_url;
    $pageNo = $pageNo ? $pageNo : 1;
    if($pageNo == 1) {
    	$numpad = '&laquo; Prev &nbsp;| &nbsp;';
    } else {
    	$numpad = '<a href="'.$data.($pageNo==2?'':'-'.($pageNo-1)).'.php">&laquo; Prev</a> &nbsp;| &nbsp;';
    }
    for ($i=1; $i<=$count; $i++) {
    	if($i == $pageNo) {
    		$numpad .= $i.'&nbsp;&nbsp;';
    	} else {
    		$numpad .= '<a href="'.$data.($i==1?'':'-'.$i).'.php">'.$i.'</a>&nbsp;&nbsp;';
    	}
    }
    if($pageNo == $count) {
    	$numpad .= '| &nbsp;Next &raquo;';
    } else {
    	$numpad .= '| &nbsp;<a href="'.$data.'-'.($pageNo+1).'.php">Next &raquo;</a> &nbsp;';
    }
	//==============
	
	/*if($menu) {
    $content = $cms->content($menu);
	}*/
	
	
	/*echo  $sql="select * from cms_page where menu_id='$menu'";
	
	exit;
	$res=mysql_query($sql);
	while($row=mysql_fetch_array($res))
	{
   $content=$row['content'];
    $title=$row['title'];
   
	}*/
	//echo $content=$row=mysql_fetch_array($res);
	 // $rs = $this->db->get_row($sql, $ARRAY_A);
	
	
	
	//=============
	//$framework->tpl->assign("CON",  $content);
	//$framework->tpl->assign("TIT",  $title);
	
	//print_r($pageRS[0]->new_window);
	
	$title   = $pageRS[0]->title;
	//print_r($pageRS);
  $framework->tpl->assign("PAGE", $pageRS);
   /*$framework->tpl->assign("title", $pageRS[0]->title);*/
   $framework->tpl->assign("page_name", $pageRS[0]->page_name);
 /* $framework->tpl->assign("meta_description", $pageRS[0]->meta_description);
  $framework->tpl->assign("meta_keywords", $pageRS[0]->meta_keywords);*/
	//$framework->tpl->assign("TITLE_HEAD", $pageRS[0]->title);
    $framework->tpl->assign("PAGE_NUMPAD", $numpad);
    $framework->tpl->assign("PAGE_COUNT", $count);
	
	$page_title     	= $pageRS[0]->title;
	$meta_keywords  	= $pageRS[0]->meta_keywords;
	$meta_description 	= $pageRS[0]->meta_description;
	$page_name			= $pageRS[0]->page_name;
	$page_det=$cms->getPageDet($page_name);
	
	if($store_id){
	
		if($page_title ==''){ 
			 if($storeDetails['page_title'] !=''){
				 $page_title   = $storeDetails['page_title'];			
			}
			else
				 $page_title     	= $global['page_title'];			
		}
		
		if($meta_keywords ==''){ 
			 if($storeDetails['meta_keywords'] !=''){
				 $meta_keywords     	= $storeDetails['meta_keywords'];			
			}
			else
				 $meta_keywords     	= $global['meta_keywords'];			
		}
		
		if($meta_description ==''){ 
			 if($storeDetails['meta_description'] !=''){
				 $meta_description     	= $storeDetails['meta_description'];			
			}
			else
				 $meta_description     	= $global['meta_description'];			
		}
	}
	else{
		if($page_title ==''){ 
			$page_title     	= $global['page_title'];	
		}
		if($meta_keywords ==''){ 
			 $meta_keywords     	= $global['meta_keywords'];	
		}
		if($meta_description ==''){ 
			 $meta_description     	= $global['meta_description'];		
		}
	}
	
	$framework->tpl->assign("PAGE_TITLE", $page_title);
	$framework->tpl->assign("META_KEYWORD", $meta_keywords);
	$framework->tpl->assign("META_DESCRIPTION", $meta_description);
	
	
}

if ($_REQUEST['admin_cms'])
{
	$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/cms/tpl/display.tpl");
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}
else 
{
		if($_REQUEST['print']=='y')
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/cms/tpl/dispalyfaq.tpl");
		else
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/cms/tpl/display.tpl");
		if($global["cms_new_window"]=="Y"){
	 		if($pageRS[0]->new_window=='Y'){
				$framework->tpl->display($global['curr_tpl']."/inner_separate.tpl");
				exit;
			}
		}
 
	/* Modified By Aneesh :: if stateOpt = popup then It will Open as PopUp   */
	if ($stateOpt=="popup")
	$framework->tpl->display($global['curr_tpl']."/popupPage.tpl"); 
	elseif($data=="terms"|| $data=="faq")
	$framework->tpl->display($global['curr_tpl']."/userPopup.tpl"); 
	elseif($data=="travel")
	$framework->tpl->display($global['curr_tpl']."/inner_login.tpl"); 
	else
	$framework->tpl->display($global['curr_tpl']."/inner.tpl");
	
	
	
	
}	

?>