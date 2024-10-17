<?php 
session_start();
$uid=$_SESSION['memberid'];
include_once(FRAMEWORK_PATH."/modules/forum/lib/class.forumclient.php");
$forum = new Forum();
switch($_REQUEST['act']) {
    case "list":
		
		$framework->tpl->assign("TITLE_HEAD", "Discussion Forum");
		$postArr = &$_REQUEST;	
		$framework->tpl->assign("THREADS", $_REQUEST);	
		$framework->tpl->assign("SECTION_LIST", $forum->menuTopicList());
		$sort = $_REQUEST["sort"]?$_REQUEST["sort"]:"posted_date";
		$sort_order = $_REQUEST["sort_order"]?$_REQUEST["sort_order"]:"DESC";
		$orderBy=$sort.":".$sort_order;
		$framework->tpl->assign("ID", $_REQUEST['id']);	
		$framework->tpl->assign("TOPIC_LIST", $forum->getTopic($_REQUEST['id']));
		$AdditionalVar="sort=$sort&sort_order=$sort_order&orderBy=$orderBy&id=".$_REQUEST['id']."&topic_id=".$_REQUEST['topic_id'];		
		list($rs, $numpad) = $forum->threadList($_REQUEST['id'],$_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&$AdditionalVar", OBJECT,$orderBy);
		//print_r($rs);
		 $framework->tpl->assign("THREAD_LIST", $rs);
        $framework->tpl->assign("THREAD_NUMPAD", $numpad);
		
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/forum/tpl/clientthreads_list.tpl");
        break;
	
	case "form":
		checkLogin(); //Redirecting to the login page if the user has not logged in
		$framework->tpl->assign("TITLE_HEAD", "Discussion Forum");
		$Date=date("Y-m-d H:i:s");
		$topic_id=$_REQUEST['topic_id'];
		$framework->tpl->assign("CUR_DATE", $Date);
		$framework->tpl->assign("USER_ID", $uid);	
		$framework->tpl->assign("TOPIC_ID", $_REQUEST['topic_id']);	
		$framework->tpl->assign("TOPICSCAT", $forum->getTopic($_REQUEST['topic_id']));
		$userDetails=$forum->getUser($uid);
		$fname=$userDetails['first_name'];
		$lname=$userDetails['last_name'];
		$Name=$fname."  ".$lname;
		$framework->tpl->assign("USER_NAME", $Name);
		if($_SERVER['REQUEST_METHOD'] == "POST") {		
			$req = &$_REQUEST;
			$fname=basename($_FILES['image']['name']);
			$ftype=$_FILES['image']['type'];
			$tmpname=$_FILES['image']['tmp_name'];	
			if( ($message = $forum->threadAddEdit($req,$fname,$tmpname)) === true ) {
				redirect(makeLink(array("mod"=>"forum", "pg"=>"clientthreads"), "act=details&id=$topic_id"));
			}
			$framework->tpl->assign("MESSAGE", $message);
		}
		if($message) {
			$framework->tpl->assign("THREADS", $_POST);
		} elseif($_REQUEST['id']) {
			$framework->tpl->assign("THREAD", $forum->getThreads($_REQUEST['id']));
		}
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/forum/tpl/clientthread_form.tpl");
		break;
	case "details":
	
		if($_REQUEST["fn"]=="joingroup")
		 {
			checkLogin();
			$getId=$_SESSION["memberid"];
			$grpid=$_REQUEST["group_id"];
			$req_user= $getId;
			$arrData  = array();
			if(!$objUser->checkGroupMember($grpid,$req_user,1))
			{
				$grpDet=$objUser->getGroupDetails($grpid);
				$grOwner=$objUser->getUserDetails($grpDet["user_id"]);
				$arrData["subject"]  = $_REQUEST["subject"];
						
						
				$grOwnerEmail=$grOwner['email'];
						
				$req_user= $getId;
				$req_user_det=$objUser->getUserDetails($getId);
						
				$array=array();
				$array["group_id"] = $_REQUEST["group_id"];
				$array["user_id"]  = $_SESSION["memberid"];
				$array["joindate"] = date("Y-m-d G:i:s");
				$array['active']   ='N';
				$objUser->setArrData($array);
				$upid=$objUser->joinGroup();
				if($id)
				{
						
					$message="<div style='padding-left: 25px; padding-right: 25px;'>";
					$message=$message."<h5> User ".$req_user_det['username']." request for joining ".$grpDet['groupname']."  group<br></h5>";
					$message=$message."<a href=\"".SITE_URL."/".makeLink(array("mod"=>"forum", "pg"=>"clientthreads"), "act=details&upid=$upid&user_id=$req_user&id=$grpid&fn=creator_res&invite=accept")."\">Accept</a>&nbsp;&nbsp;&nbsp;";
					$message=$message."<a href=\"".SITE_URL."/".makeLink(array("mod"=>"forum", "pg"=>"clientthreads"), "act=details&user_id=$req_user&id=$grpid&fn=creator_res&invite=decline")."\">Decline</a>";
					$message=$message."<p>Thanks,<br>";
					$message=$message. $req_user_det["first_name"]. " " . $req_user_det["last_name"] . "</p>";
					$message=$message."</div>";
					mimeMail($grOwnerEmail,$arrData["subject"],$message,'','','scihat.com <'.$framework->config['admin_email'].'>');
					$framework->tpl->assign("MESSAGE","Request is sent to creator of group ".$grpDet['groupname']."");
				}
						///echo "mimeMail(".$grOwnerEmail.",".$arrData['subject'].",".$message.",'','','scihat.com <'.$framework->config['admin_email'].'>')";
				}
				else
				{
					$framework->tpl->assign("MESSAGE","You are already sent a request.");
				}
		}
		else if($_REQUEST["fn"]=="invite")
			{
				checkLogin();
				$userinfo = $objUser->getUserdetails($_SESSION["memberid"]);
				$contact  = $objUser->listContacts($userinfo["username"]);
				$framework->tpl->assign("CONTACT", $contact);

			}
		else if($_REQUEST["fn"]=="creator_res")	
			{
				
				if(!$objUser->checkGroupMember($_REQUEST['id'],$_REQUEST["user_id"],0))
				{
					if($_REQUEST['invite']=='accept' && $_REQUEST['upid'])
					{
						$objUser->UpdateGroupMember($_REQUEST['upid']);
						$framework->tpl->assign("MESSAGE","Request approved successfully.");
					}	
					else if($_REQUEST["id"] && $_REQUEST["user_id"])
					{
							$array=array();
							$array["group_id"] = $_REQUEST["id"];
							$array["user_id"]  = $_REQUEST["user_id"];
							$objUser->setArrData($array);
							$objUser->leaveGroup();
							$framework->tpl->assign("MESSAGE","Request Rejected.");
					}	
				}
				else
				$framework->tpl->assign("MESSAGE","You are already member of this group.");	
						
		}
		else if($_REQUEST["fn"]=="leave")
				{
					checkLogin();
					$array=array();
					$array["group_id"] = $_REQUEST["id"];
					$array["user_id"]  = $_SESSION["memberid"];
					$objUser->setArrData($array);
					if($objUser->leaveGroup())
					{
						redirect(makeLink(array("mod"=>"forum", "pg"=>"clientthreads"), "act=details&id=".$array["group_id"]));
					}
					else
					{
						$framework->tpl->assign("MESSAGE", $objUser->getErr());
					}
				}
							
					
		if ($_SESSION["memberid"])
			{
				if($objUser->checkGroupMember($_REQUEST["id"],$_SESSION["memberid"]))
				{
					$framework->tpl->assign("MEM_FLG","Y");	
					
				}
				else
				{
					$framework->tpl->assign("MEM_FLG","N");	
				}
				
			}
			else
			{
				$framework->tpl->assign("MEM_FLG","N");
			}
		if($_REQUEST["fn"]=="invite")
		{
			$framework->tpl->assign("ID", $_REQUEST['id']);
			$framework->tpl->assign("USERDET",$objUser->getUserDetails($_SESSION["memberid"]));
			$framework->tpl->assign("main_tpl", SITE_PATH."/forum/forum/tpl/group_invite.tpl");		
		}
		else{	
		$framework->tpl->assign("OWN_FLG","Y");	
		$framework->tpl->assign("TITLE_HEAD", "Discussion Forum");
		$postArr = &$_REQUEST;	
		$framework->tpl->assign("THREADS", $_REQUEST);	
		$framework->tpl->assign("SECTION_LIST", $forum->menuTopicList());
		$sort = $_REQUEST["sort"]?$_REQUEST["sort"]:"posted_date";
		$sort_order = $_REQUEST["sort_order"]?$_REQUEST["sort_order"]:"DESC";
		$orderBy=$sort.":".$sort_order;
		$framework->tpl->assign("ID", $_REQUEST['id']);	
		$framework->tpl->assign("TOPIC_LIST", $forum->getTopic($_REQUEST['id']));
		$AdditionalVar="sort=$sort&sort_order=$sort_order&orderBy=$orderBy&id=".$_REQUEST['id']."&topic_id=".$_REQUEST['topic_id'];		
		list($rs, $numpad) = $forum->threadList($_REQUEST['id'],$_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&$AdditionalVar", OBJECT,$orderBy);
		if($rs)
		{
			foreach($rs as $key=>$value)
			{
				$rs[$key]->replay=$forum->getPostList($value->id);
			}
		}	
		//print_r($rs);
		 $framework->tpl->assign("THREAD_LIST", $rs);
        $framework->tpl->assign("THREAD_NUMPAD", $numpad);
		
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/forum/tpl/clientthreads_list.tpl");
		}
        break;
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");

?>