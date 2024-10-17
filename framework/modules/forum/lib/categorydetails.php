<?php 

//authorize();

include_once(FRAMEWORK_PATH."/modules/forum/lib/class.forum.php");
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
$objCms 	 = new Cms();
$forum = new Forum();	
//include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.newsletter.php");
if(isset($_SESSION['chps1']))
	{
	 $framework->tpl->assign("chps1",$_SESSION['chps1']);
	}
	if($_SESSION['chps1']==1){
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
		$rightmenu=$objCms->linkList("social_community_right");
	}elseif($_SESSION['chps1']==2){
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("dating_right"));// to select the right menu of this page from database.
		$rightmenu=$objCms->linkList("dating_right");
	}elseif($_SESSION['chps1']==3){
	        
			$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("rest_right"));// to select the right menu of this page from database.
			$rightmenu=$objCms->linkList("dating_right");
	}
	elseif($_SESSION['chps1']==4){
	
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("rest_right"));// to select the right menu of this page from database.
		$rightmenu=$objCms->linkList("dating_right");
	}
	elseif($_SESSION['chps1']==5){
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("dating_right"));// to select the right menu of this page from database.
		$rightmenu=$objCms->linkList("dating_right");
	}
	
	else{
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
	}
	if($_REQUEST["pid"]!=""){
$framework->tpl->assign("pid", $_REQUEST["pid"]);	
	}

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
    case "details":
	//print_r($_REQUEST);
	     if($_REQUEST['cat_id'])
		    {
		   list($rs_health, $numpad1) = $forum->UserTopicList($_REQUEST['cat_id'],$_REQUEST['pageNo'], '10', "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&cat_id=".$_REQUEST['cat_id'], OBJECT, $_REQUEST['orderBy']);
            $framework->tpl->assign("USERTOPIC_LIST", $rs_health);
		    $framework->tpl->assign("USERTOPIC_NUMPAD", $numpad1);
			$test=$forum->TopicGet($_REQUEST['cat_id']);
			$framework->tpl->assign("CRT",$_REQUEST['cat_id']);			
            $framework->tpl->assign("TOPICS", $forum->TopicGet($_REQUEST['cat_id']));
		    }
     
		//echo $_SESSION['chps1'];
		if($_SESSION['chps1']==3){
		     
		       $framework->tpl->assign("category", Health);  
			   list($rs_health, $numpad) = $forum->TopicList(23,1, '', "mod=".$_REQUEST['mod']."&pg=".$_REQUEST['pageNo']."&act=".$_REQUEST['act']."&cat_id=".$_REQUEST['cat_id'], OBJECT, $_REQUEST['orderBy']);
               $framework->tpl->assign("TOPIC_LIST", $rs_health);
			   $framework->tpl->assign("left_tpl", SITE_PATH."/modules/forum/tpl/health_left.tpl");	}
			if($_SESSION['chps1']==4){
		   $framework->tpl->assign("category", Wealth);  	
			   list($rs_health, $numpad) = $forum->TopicList(42,$_REQUEST['pageNo'], '', "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&cat_id=".$_REQUEST['cat_id'], OBJECT, $_REQUEST['orderBy']);
               $framework->tpl->assign("TOPIC_LIST", $rs_health);
			  
			    $framework->tpl->assign("left_tpl", SITE_PATH."/modules/forum/tpl/health_left.tpl");}
		//print_r( $forum->TopicGet($_REQUEST['cat_id']));
		$framework->tpl->assign("TITLE_HEAD", "Discussion Forum");
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/forum/tpl/category_details.tpl");
        break;
	 case "mytopic":
	//print_r($_REQUEST);
	
	     if($_REQUEST['cat_id'])
		    {$getId=$_SESSION["memberid"];
		   list($rs_health1, $numpad1) = $forum->MyTopicList($_REQUEST['cat_id'],$_REQUEST['pageNo'], '10', "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&cat_id=".$_REQUEST['cat_id'], OBJECT, $_REQUEST['orderBy'],$getId);
           $framework->tpl->assign("USERTOPIC_LIST", $rs_health1);
		    $framework->tpl->assign("USERTOPIC_NUMPAD", $numpad1);
		    }
        if($_REQUEST['cat_id']) {
			$test=$forum->TopicGet($_REQUEST['cat_id']);
			$framework->tpl->assign("CRT",$_REQUEST['cat_id']);			
            $framework->tpl->assign("TOPICS", $forum->TopicGet($_REQUEST['cat_id']));
        }
		
	
		if($_SESSION['chps1']==3){ 
		
		       $framework->tpl->assign("category", Health);  
			   list($rs_health, $numpad) = $forum->TopicList(23,$_REQUEST['pageNo'], 10, "mod=".$_REQUEST['mod']."&pg=".$_REQUEST['pageNo']."&act=".$_REQUEST['act']."&cat_id=".$_REQUEST['cat_id'], OBJECT, $_REQUEST['orderBy']);
               $framework->tpl->assign("TOPIC_LIST",$rs_health);
			   //print_r( $rs_health);
			   $framework->tpl->assign("left_tpl", SITE_PATH."/modules/forum/tpl/health_left.tpl");	}
		
		if($_SESSION['chps1']==4){
		   $framework->tpl->assign("category", Wealth);  	
			   list($rs_health, $numpad) = $forum->TopicList(42,$_REQUEST['pageNo'], '', "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&cat_id=".$_REQUEST['cat_id'], OBJECT, $_REQUEST['orderBy']);
               $framework->tpl->assign("TOPIC_LIST", $rs_health);
			    $framework->tpl->assign("left_tpl", SITE_PATH."/modules/forum/tpl/health_left.tpl");}
		//print_r( $forum->TopicGet($_REQUEST['cat_id']));
		$framework->tpl->assign("TITLE_HEAD", "Discussion Forum");
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/forum/tpl/mytopics.tpl");
        break;
    case "delete":
        $forum->topicDelete($_REQUEST['id']);
        redirect(makeLink(array("mod"=>"forum", "pg"=>"topics"), "act=list"));
        break;
	  case "erase":
	   
        $forum->topicDelete($_REQUEST['id']);
        redirect(makeLink(array("mod"=>"forum", "pg"=>"categorydetails"), "act=mytopic&cat_id=".$_REQUEST['cat_id']));
        break;
			
		
		case "form":
       // include_once(SITE_PATH."/includes/areaedit/include.php");
	   if($_REQUEST['cat_id']) {
			$test=$forum->TopicGet($_REQUEST['cat_id']);
				$framework->tpl->assign("CRT",$_REQUEST['cat_id']);			
            //$framework->tpl->assign("TOPICS", $forum->TopicGet($_REQUEST['cat_id']));
        }
		/////////////////////
		if($_REQUEST['id']!="")
		{
		$test=$forum->TopicGet($_REQUEST['id']);
		$framework->tpl->assign("TOPICS",$test);			
		
		}
		/////////////////////
		$getId=$_SESSION["memberid"];
		$framework->tpl->assign("OWN","Y");
		if($_SESSION['chps1']==3){ $framework->tpl->assign("left_tpl", SITE_PATH."/modules/forum/tpl/health_left.tpl");	
			   list($rs_health, $numpad) = $forum->TopicList(23,$_REQUEST['pageNo'], '', "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
               $framework->tpl->assign("TOPIC_LIST", $rs_health);}
		
		if($_SESSION['chps1']==4){ $framework->tpl->assign("left_tpl", SITE_PATH."/modules/forum/tpl/health_left.tpl");	
			   list($rs_health, $numpad) = $forum->TopicList(42,$_REQUEST['pageNo'], '', "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
               $framework->tpl->assign("TOPIC_LIST", $rs_health);}
		$framework->tpl->assign("SECTION_LIST", $forum->menuSectionList());
		//editorInit('body_html');
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $req = &$_REQUEST;
			$fname=basename($_FILES['image']['name']);
			$ftype=$_FILES['image']['type'];
			$tmpname=$_FILES['image']['tmp_name'];
			
				if( ($message = $forum->topicAddEdit($req,$fname,$tmpname,$_REQUEST['cat_id'],$getId)) === true ) {
				
				   if($_REQUEST['id']!="")
				   {
				    redirect(makeLink(array("mod"=>"forum", "pg"=>"categorydetails"), "act=mytopic&cat_id=".$_REQUEST['cat_id'].""));
				   }else
				   {
				
                redirect(makeLink(array("mod"=>"forum", "pg"=>"categorydetails"), "act=details&cat_id=".$_REQUEST['cat_id'].""));
				}
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
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/forum/tpl/add_topic.tpl");
        break;
		
	
							
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>