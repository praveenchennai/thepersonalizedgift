<?php 

authorize();

include_once(FRAMEWORK_PATH."/modules/forum/lib/class.forum.php");
//include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.newsletter.php");

$forum = new Forum();		
switch($_REQUEST['act']) {
    case "list":	
	
	
		$framework->tpl->assign("SECTION_LIST", $forum->menuSectionList()); 
		$framework->tpl->assign("TITLE_HEAD", "Discussion Forum");
		list($rs, $numpad) = $forum->TopicList($_REQUEST['section_id'],$_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("TOPIC_LIST", $rs);
        $framework->tpl->assign("TOPIC_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/forum/tpl/topic_list.tpl");
        break;
    case "form":
	 include_once(SITE_PATH."/includes/areaedit/include.php");
		$framework->tpl->assign("SECTION_LIST", $forum->menuSectionList());
		editorInit('body_html');
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $req = &$_REQUEST;
			$fname=basename($_FILES['image']['name']);
			$ftype=$_FILES['image']['type'];
			$tmpname=$_FILES['image']['tmp_name'];			
            if( ($message = $forum->topicAddEdit($req,$fname,$tmpname)) === true ) {
                redirect(makeLink(array("mod"=>"forum", "pg"=>"topics"), "act=list"));
            }
            $framework->tpl->assign("MESSAGE", $message);
        }
        if($message) {
            $framework->tpl->assign("TOPICS", $_POST);
        } elseif($_REQUEST['id']) {
			$test=$forum->TopicGet($_REQUEST['id']);			
            $framework->tpl->assign("TOPICS", $forum->TopicGet($_REQUEST['id']));
        }
		  
		$framework->tpl->assign("TITLE_HEAD", "Discussion Forum");
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/forum/tpl/topic_form.tpl");
        break;
	
    case "delete":
        $forum->topicDelete($_REQUEST['id']);
        redirect(makeLink(array("mod"=>"forum", "pg"=>"topics"), "act=list"));
        break;
		
	
							
}
$framework->tpl->display($global['curr_tpl']."/admin.tpl");
?>