<?php 

authorize();

include_once(FRAMEWORK_PATH."/modules/forum/lib/class.forum.php");
$forum = new Forum();
switch($_REQUEST['act']) {
    case "list":
        $framework->tpl->assign("TITLE_HEAD", "Discussion Forum");
		list($rs, $numpad) = $forum->CategoryList($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
        $framework->tpl->assign("CATEGORY_LIST", $rs);
        $framework->tpl->assign("CATEGORY_NUMPAD", $numpad);
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/forum/tpl/category_list.tpl");
        break;
    case "form":
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $req = &$_REQUEST;
			$fname=basename($_FILES['image']['name']);
			$ftype=$_FILES['image']['type'];
			$tmpname=$_FILES['image']['tmp_name'];									
            if( ($message = $forum->categoryAddEdit($req,$fname,$tmpname)) === true ) {
                redirect(makeLink(array("mod"=>"forum", "pg"=>"forumcategory"), "act=list"));
            }
            $framework->tpl->assign("MESSAGE", $message);
        }
        if($message) {
            $framework->tpl->assign("FORUMCATEGORY", $_POST);
        } elseif($_REQUEST['id']) {
            $framework->tpl->assign("FORUMCATEGORY", $forum->CategoryGet($_REQUEST['id']));
        }
		 $framework->tpl->assign("TITLE_HEAD", "Discussion Forum");
		 $framework->tpl->assign("CHECK", "checked");
		 $framework->tpl->assign("main_tpl", SITE_PATH."/modules/forum/tpl/category_form.tpl");
        break;
    case "delete":
        $forum->categoryDelete($_REQUEST['id']);
        redirect(makeLink(array("mod"=>"forum", "pg"=>"forumcategory"), "act=list"));
        break;
}
$framework->tpl->display($global['curr_tpl']."/admin.tpl");

?>