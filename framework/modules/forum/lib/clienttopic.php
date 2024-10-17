<?php 

include_once(FRAMEWORK_PATH."/modules/forum/lib/class.forumclient.php");
$forum = new Forum();
$getId=$_SESSION["memberid"];
switch($_REQUEST['act']) {
    case "list":
	//if($global['searchstyle'] == "2" && $_REQUEST["user_id"]){
	//	list($cat_rs, $numpad) = $forum->categoryList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
		//print_r($cat_rs);exit;
	//}else{
		$framework->tpl->assign("TITLE_HEAD", "Discussion Forum Topics");
		$framework->tpl->assign("SECTION_LIST", $forum->menuTopicList());
		list($cat_rs, $numpad) = $forum->categoryList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
		//print_r($cat_rs);exit;
		$framework->tpl->assign("CATEGORY_LIST", $cat_rs);
		$framework->tpl->assign("CATEGORY_NUMPAD", $numpad);
		$framework->tpl->assign("TOPIC_LIST", $rsArr);
		$framework->tpl->assign("TOPIC_NUMPAD", $numpad);
	//}
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/forum/tpl/clienttopic_list.tpl");
		break;	
		case "add_topic":
			checkLogin();
			$framework->tpl->assign("CAT_LIST", $objUser->getCategories());
			$framework->tpl->assign("SECTION_LIST", $objUser->listCategories());
			if($_SERVER['REQUEST_METHOD'] == "POST") {
			
				$req = &$_REQUEST;
				
				$req["createdate"]=date("Y-m-d G:i:s");
				$req['user_id']=$_SESSION["memberid"];
				
				$fname=basename($_FILES['image']['name']);
				$ftype=$_FILES['image']['type'];
				$tmpname=$_FILES['image']['tmp_name'];		
				if( ($id= $forum->topicAddEdit($req,$fname,$tmpname))) {
						if($_REQUEST['aid'])
						{
							$arr=array('aid'=>$_REQUEST['aid']);
							$forum->createArticle($arr,$id);
						}
						redirect(makeLink(array("mod"=>"forum", "pg"=>"clienttopic"), "act=topic_list"));
						
					}
					$framework->tpl->assign("MESSAGE", $message);
				}
				$framework->tpl->assign("main_tpl", SITE_PATH."/modules/forum/tpl/group_create.tpl");
		break;
	case "form":
		 $framework->tpl->assign("TOPICS", $forum->TopicGet($_REQUEST['id']));
		 list($rs, $numpad) = $forum->TopicList($_REQUEST['section_id'],$_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
         $framework->tpl->assign("TOPIC_LIST", $rs);
         $framework->tpl->assign("TOPIC_NUMPAD", $numpad);
		  $framework->tpl->assign("main_tpl", SITE_PATH."/modules/member/tpl/group_details.tpl");
		break;	
		
	case "topic_list":
		$framework->tpl->assign("CAT_ARR", $objUser->listCategories());
		$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act'];
		if ($_REQUEST["crt"])
		{
			$par = $par."&crt=".$_REQUEST['crt'];
		}
		if ($_REQUEST["cat_id"])
		{
				$par = $par."&cat_id=".$_REQUEST['cat_id'];
		}
		if ($_REQUEST["aid"])
		{
				$par = $par."&aid=".$_REQUEST['aid'];
		}
		if($_POST["txtSearch"])
		{
				$stxt=$_POST["txtSearch"];
				$_REQUEST["pageNo"]=1;
				$framework->tpl->assign("STXT",$stxt);
				
		}
		else
		{if(!$_REQUEST["stxt"])
			{
				$stxt=0;
			}
			else
			{
				$stxt=$_REQUEST["stxt"];
				$framework->tpl->assign("STXT",$stxt);
			}	
		}
		$par = $par."&stxt=".$stxt;
		if ($_REQUEST["crt"])
		{
			checkLogin();
			$framework->tpl->assign("CRT",$_REQUEST["crt"]);
			$framework->tpl->assign("GRP_HEADER", "Topics you own");
			list($rs, $numpad) = $forum->getTopicListDet($_REQUEST['pageNo'],10,$par, OBJECT, $_REQUEST['orderBy'],$_REQUEST["cat_id"],1,1,$_SESSION["memberid"],$stxt);				
		}
		else if ($_REQUEST["cat_id"])
		{	
				$framework->tpl->assign("CRT",$_REQUEST["cat_id"]);
				$catname=$objUser->getCatName($_REQUEST["cat_id"]);
				$framework->tpl->assign("GRP_HEADER", $catname["category_name"]);
				list($rs, $numpad) = $forum->getTopicListDet($_REQUEST['pageNo'],10, $par, OBJECT, $_REQUEST['orderBy'],$_REQUEST["cat_id"],0,0,0,$stxt);				
		}
		else if ($_REQUEST["aid"])
		{	
				
				list($rs, $numpad) = $forum->getTopicListDet($_REQUEST['pageNo'],10, $par, OBJECT, $_REQUEST['orderBy'],$_REQUEST["cat_id"],0,0,0,$stxt,$_REQUEST["aid"]);				
		}		
		else
		{
			list($rs, $numpad) = $forum->getTopicListDet($_REQUEST['pageNo'],10, $par, OBJECT, $_REQUEST['orderBy'],0,0,0,0,$stxt);
			$framework->tpl->assign("GRP_HEADER", "All Topics");
		}
        $framework->tpl->assign("TOPIC_LIST", $rs);
        $framework->tpl->assign("TOPIC_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/forum/tpl/topics_list.tpl");
        break;
}
		$framework->tpl->display($global['curr_tpl']."/inner.tpl");

?>