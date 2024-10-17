<?
include_once(FRAMEWORK_PATH."/modules/album/lib/class.video.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");

	$album= new Album();
	$video=new Video();
	$user=new User();
	
$memberID = $_SESSION['memberid'];
switch($_REQUEST['act'])
{
    default:
 case "list":

        if ($_REQUEST["cat_id"])
        {
            $catname=$user->getCatName($_REQUEST["cat_id"]);
            $framework->tpl->assign("FILTER",$_REQUEST["cat_id"]);
            $framework->tpl->assign("PH_HEADER", $catname["category_name"]);
            list($rs, $numpad) = $video->videoList($_REQUEST['pageNo'], 5, "mod={$mod}&pg={$pg}&act=list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}&cat_id={$_REQUEST["cat_id"]}", OBJECT, "id desc", $_REQUEST["cat_id"], $_REQUEST['txtSearch']);
        }
        elseif ($_REQUEST["filter"])
        {
			include_once(SITE_PATH."/includes/flashPlayer/include.php");

            if ($_REQUEST["filter"]=="recent")
            { 
                $pheader="Most Recent";
                $field="postdate desc";
				/*
				Jewish
				*/
				$phdet = $video->getVideoDetails($_REQUEST["video_id"]);
				$height = $phdet['dimension_height']+20;
				$link=SITE_URL."/".makeLink(array("mod"=>"album", "pg"=>"album"), "act=embed&video_id={$phdet['id']}");
				$embed_url = "<iframe frameborder='0'  marginheight='0' marginwidth='0'  width='400' height='350' src='$link' scrolling='no'></iframe>";
				$framework->tpl->assign("EMBED_URL",$embed_url);
				$v_url =SITE_URL."/index.php?". $_SERVER['QUERY_STRING'];
				$framework->tpl->assign("VISIBLE_URL",$v_url);
				/*
				Jewish
				*/

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
            list($rs, $numpad) = $video->videoList($_REQUEST['pageNo'], 5, "mod={$mod}&pg={$pg}&act=list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}", OBJECT, $field,0,$_REQUEST['txtSearch']);
			
			/* new */ 
			if($rs[0]->cat_id > 0)
			list($rscat, $numpad) = $video->videoList($_REQUEST['pageNo'], 5, "mod={$mod}&pg={$pg}&act=list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}", OBJECT, $field,$rs[0]->cat_id,$_REQUEST['txtSearch']);

        }
        else
        {
            $framework->tpl->assign("PH_HEADER", "All Movies");
            list($rs, $numpad) = $video->videoList($_REQUEST['pageNo'], 5, "mod={$mod}&pg={$pg}&act=list&filter={$_REQUEST['filter']}&txtSearch={$_REQUEST['txtSearch']}", OBJECT, 'id desc',0,$_REQUEST['txtSearch']);
        }
		if(count($rs)>0){
			foreach($rs as $row)
			{
				$dur[] = implode(":",$album->secs2hms($row->length));// calculate duration video clips
			}
		}
		
		$framework->tpl->assign("DURATION",$dur);
        $framework->tpl->assign("VIDEO_LIST", $rs);
		$framework->tpl->assign("VIDEO_LIST_CAT", $rscat);
		if($rs[0]->cat_id > 0)
			$rscat = $user->getCatName($rs[0]->cat_id) ;
        $framework->tpl->assign("VIDEO_NUMPAD", $numpad);
		$framework->tpl->assign("CAT_NAME",$rscat["category_name"]);
		//$framework->tpl->assign("RIGT_TPL", SITE_PATH."/modules/album/tpl/right_top_btn.tpl");
        //$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/album_list.tpl");
        break;
}
if($_REQUEST['ext'] == "cat")
$framework->tpl->display(SITE_PATH."/modules/album/tpl/album_list_cat.tpl");
else
$framework->tpl->display(SITE_PATH."/modules/album/tpl/album_list.tpl");
exit;
?>