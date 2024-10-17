<?php


include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.video.php");
include_once(SITE_PATH."/includes/flashPlayer/include.php");
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");

$cms = new Cms();
$objAlbum = new Album();
$objUser  = new User();
$objVideo = new Video();
//print_r($_REQUEST);
if($global["home_content"]=="home"){
	$dt=$cms->getContent("home");
	$framework->tpl->assign("HOME_CONTENT",$dt["content"]);
}
if($global["show_property"] == 1)  //realestate tube
{
	
	   if ($_REQUEST["filter"]=="recent")
            { 
                $pheader="Most Recent";
                $field="postdate desc";
            }
            elseif ($_REQUEST["filter"]=="viewed")
            {
                $pheader="Most Viewed";
                $field="prop_views desc";
            }
            elseif ($_REQUEST["filter"]=="discussed")
            {
                $pheader="Most Discussed";
                $field="cmcnt desc";
            }
            elseif ($_REQUEST["filter"]=="rated")
            {
                $pheader="Top Rated";
                $field="rating desc";
            }
            elseif ($_REQUEST["filter"]=="favorites")
            {
                $pheader="Top Favorites";
                $field="favcnt desc";
            }
			else
			{
				$pheader="Most Recent";
                $field="postdate desc";
			}
	list($rs,$NUM_PAD) = $objVideo->propertyList($_REQUEST['pageNo'], 24, "filter={$_REQUEST['filter']}",OBJECT, $field,0,0,0,1,0,'default_vdo','0','>');
	$framework->tpl->assign("RECENTLIST",$rs);
	$framework->tpl->assign("RATEVAL",$rs[0]->rate*20);
	$framework->tpl->assign("NUMPAD",$NUM_PAD);
	$framework->tpl->assign("HEADER",$pheader);
	
}

if($global["searchstyle"] == "2") {//for p1musicbox.
	if($_SERVER['REQUEST_METHOD']=="POST"){
		$param="mod=member&pg=default&resultpage=search_member_result";
		$_REQUEST["pageNo"]=1;
		list($rs, $numpad) = $objUser->profileList($_REQUEST['pageNo'], 10, $param, OBJECT, $_REQUEST['orderBy'],$_POST["keyword"],$_SESSION["memberid"]);
		$framework->tpl->assign("PROFILE_LIST", $rs);
		$framework->tpl->assign("PROFILE_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/search_member_result.tpl");
		$framework->tpl->display($global['curr_tpl']."/inner.tpl");
		$framework->tpl->assign("TITLE_HEAD","Search Results");
		exit;

	}elseif($global['searchstyle'] == "2" && $_REQUEST['resultpage']=="search_member_result"){
		$param="mod=$mod&pg=$pg&resultpage=search_member_result";
		list($rs, $numpad) = $objUser->profileList($_REQUEST['pageNo'], 10, $param, OBJECT, $_REQUEST['orderBy'],$_REQUEST["txtuname"],$_SESSION["memberid"]);
		//print_r($numpad);exit;
		$framework->tpl->assign("PROFILE_LIST", $rs);
		$framework->tpl->assign("TITLE_HEAD","Search Results");
		$framework->tpl->assign("PROFILE_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/search_member_result.tpl");
		$framework->tpl->display($global['curr_tpl']."/inner.tpl");
		exit;

	}elseif($global['searchstyle'] == "2" && $_REQUEST['alphabetic']){
		if($_REQUEST["type"]){
			$param="mod=$mod&pg=$pg&alphabetic=".$_REQUEST['alphabetic']."&type=".$_REQUEST['type'];
		}else{
			$param="mod=$mod&pg=$pg&alphabetic=".$_REQUEST['alphabetic'];
		}
			list($rs, $numpad) = $objUser->profileList($_REQUEST['pageNo'], 10, $param, OBJECT, $_REQUEST['orderBy'],$_REQUEST['alphabetic'],$_SESSION["memberid"]);
		//print_r($numpad);exit;
		$st="Search Results on ".$_REQUEST['alphabetic'];
		$framework->tpl->assign("TITLE_HEAD",$st);
		$framework->tpl->assign("PROFILE_LIST", $rs);
		$framework->tpl->assign("PROFILE_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/search_member_result.tpl");
		$framework->tpl->display($global['curr_tpl']."/inner.tpl");
		exit;
	}elseif($global['searchstyle'] == "2" && $_REQUEST['keyword']){
	
		if($_REQUEST["type"]){
			$param="mod=$mod&pg=$pg&alphabetic=".$_REQUEST['keyword']."&type=".$_REQUEST['type'];
		}else{
			$param="mod=$mod&pg=$pg&alphabetic=".$_REQUEST['keyword'];
		}
			list($rs, $numpad) = $objUser->profileList($_REQUEST['pageNo'], 10, $param, OBJECT, $_REQUEST['orderBy'],$_REQUEST['keyword'],$_SESSION["memberid"]);
		//print_r($numpad);exit;
		$st1="Search Results on ".$_REQUEST['keyword'];
		$framework->tpl->assign("TITLE_HEAD",$st1);
		$framework->tpl->assign("PROFILE_LIST", $rs);
		$framework->tpl->assign("PROFILE_NUMPAD", $numpad);
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/search_member_result.tpl");
		$framework->tpl->display($global['curr_tpl']."/inner.tpl");
		exit;
	}
}
$new_members=$objUser->getNewMembers();
$framework->tpl->assign("NEW_MEM", $new_members);
$pict = $objUser->loadHomeMediaDet('album_music','postdate','audio_type','V');
$pict[0]["description"]=substr($pict[0]["description"],0,75);
$pict[0]["title"]=substr($pict[0]["title"],0,25);
$framework->tpl->assign("PICT",$pict);
$mv = $objUser->loadHomeMediaDet('album_video','postdate');
$mv[0]["description"]=substr($mv[0]["description"],0,75);
$mv[0]["title"]=substr($mv[0]["title"],0,25);
$framework->tpl->assign("MV",$mv);
$framework->tpl->display($global['curr_tpl']."/index.tpl");

?>