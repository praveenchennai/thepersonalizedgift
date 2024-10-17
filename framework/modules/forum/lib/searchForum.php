<?php 

include_once(FRAMEWORK_PATH."/modules/forum/lib/class.forumclient.php");
$forum = new Forum();
$dispFlag=0;
if(isset($_REQUEST['btn_search'])){
	$dispFlag=1;
}
switch($_REQUEST['act']) {
    case "list":	
	if((isset($_REQUEST['btn_search']))&&($_REQUEST['btn_search']!="")){
		$req = &$_REQUEST;
		$framework->tpl->assign("DISP_FLAG", $dispFlag);		
		$framework->tpl->assign("INITIALKEYWORD", $req['txtkeywords']);
		$framework->tpl->assign("INITIALEXACT", $req['chkExact']);
		$framework->tpl->assign("INITIALBUTTONVAL", "Search");
		$framework->tpl->assign("TITLE_HEAD", "Discussion Forum");
		$sort=$_REQUEST['sort'];
		$sort_order=$_REQUEST['sort_order'];
		if(isset($_REQUEST['sort'])){
			$orderBy=$sort.":".$sort_order;
		}else{
			$orderBy=$_REQUEST['orderBy'];
		}
		$txtkeywords=$req['txtkeywords'];
		$chkExact=$req['chkExact'];
		$btn_search=$req['btn_search'];
		$AdditionalVar="txtkeywords=$txtkeywords&chkExact=$chkExact&btn_search=$btn_search";				
		list($rs, $numpad) = $forum->threadsearchList($req,$_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&$AdditionalVar", OBJECT, $orderBy);
		$framework->tpl->assign("SEARCH_LIST", $rs);
		$framework->tpl->assign("SEARCH_NUMPAD", $numpad);		
		}
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/forum/tpl/searchforum_form.tpl");
		break;	
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>