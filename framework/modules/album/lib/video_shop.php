<?php
session_start();
include_once(FRAMEWORK_PATH."/modules/album/lib/class.video.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");

$album= new Album();
$video=new Video();
$user=new User();

$memberID = $_SESSION['memberid'];

	$framework->tpl->assign("CAT_LIST", $objUser->getCategoryCombo($_REQUEST["mod"]));	
	$framework->tpl->assign("CAT_ARR", $objUser->getCategoryArr($_REQUEST["mod"]));

switch($_REQUEST['act'])
{
	default:
	case "list":
		if ($_REQUEST["cat_id"])
		{
			$catname=$user->getCatName($_REQUEST["cat_id"]);
			$framework->tpl->assign("FILTER",$_REQUEST["cat_id"]);
			$framework->tpl->assign("PH_HEADER", $catname["cat_name"]);
			list($rs, $numpad) = $video->videoList($_REQUEST['pageNo'], 15, "mod={$mod}&pg={$pg}&act=list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}&cat_id={$_REQUEST["cat_id"]}", OBJECT, "id desc", $_REQUEST["cat_id"], $_REQUEST['txtSearch'], 1);
		}
		elseif ($_REQUEST["filter"])
		{
			if ($_REQUEST["filter"]=="recent")
			{
				$pheader="Most Recent";
				$field="postdate desc";
			}
			elseif ($_REQUEST["filter"]=="viewed")
			{
				$pheader="Most Viewed";
				$field="views desc";
			}
			elseif ($_REQUEST["filter"]=="discussed")
			{
				$pheader="Most Discussed";
				$field="cmcnt desc";
			}
			elseif ($_REQUEST["filter"]=="downloaded")
			{
				$pheader="Most Downloaded";
				$field="downloads desc";
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

			$framework->tpl->assign("FILTER",$_REQUEST["filter"]);
			$framework->tpl->assign("PH_HEADER", $pheader);
			list($rs, $numpad) = $video->videoList($_REQUEST['pageNo'], 15, "mod={$mod}&pg={$pg}&act=list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}", OBJECT, $field,0,$_REQUEST['txtSearch'], 1);
		}
		else
		{
			$framework->tpl->assign("PH_HEADER", "All Movies");
			list($rs, $numpad) = $video->videoList($_REQUEST['pageNo'], 15, "mod={$mod}&pg={$pg}&act=list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}", OBJECT, 'id desc',0,$_REQUEST['txtSearch'], 1);
		}
		$framework->tpl->assign("VIDEO_LIST", $rs);
		$framework->tpl->assign("VIDEO_NUMPAD", $numpad);

		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/video_shop.tpl");
		break;
}

$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>