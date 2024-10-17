<?php
session_start();
//checkLogin();//Redirecting to the login page if the user has not logged in
include_once(FRAMEWORK_PATH."/modules/messaging/lib/class.messaging.php");
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
$email	 		= new Email();
$user = new User();
$objCms = new Cms();
$message= new messagemodule();

$rs				=	$user->getUsernameDetails("admin");

$framework->tpl->assign("LEFTBOTTOM","mail" );
if($_REQUEST["pid"]!=""){
$framework->tpl->assign("PID", $_REQUEST["pid"]);	
	}
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
	else
	{
	$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));}
	
switch($_REQUEST['act'])
{



case "about":

if($_SERVER['REQUEST_METHOD'] == "POST") 
		{
		header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$flag=0;
			if(empty($_REQUEST['name']) or $_REQUEST['name']=='Your Name:')
			{
				$error	=	$MOD_VARIABLES['MOD_ERRORS']['ERR_ENTERNAME'];
				$flag	=	1;
			}
			else if(empty($_REQUEST['email']) or $_REQUEST['email']=='E-mail Address:')
			{
				$error	=	$MOD_VARIABLES['MOD_ERRORS']['ERR_ENTEREMAIL'];
				$flag	=	1;
			}
			
			else if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $_REQUEST['email']))
			{
			$error	=	$MOD_VARIABLES['MOD_ERRORS']['ERR_EMAIL'];
			$flag	=	1;
			}
			else if(empty($_REQUEST['message']) or $_REQUEST['message']=='Your Message:')
			{
					$error	=	$MOD_VARIABLES['MOD_ERRORS']['ERR_ENTERMESSAGE'];
				$flag	=	1;
			}
		
		if($flag==0)
				{
				$mail_header	=	array(	"from"	=>	$_REQUEST['email'],
											"to"	=>	$rs['email']);
				$dynamic_vars 	=	array(	
				"YOUR_NAME"	=>	$_REQUEST['name'],"ADMIN_NAME"=>$rs['first_name'],
											"CONTENT"	=>	trim(stripslashes(nl2br($_REQUEST['message']))));
											
			if($email->send('contact_us', $mail_header, $dynamic_vars))
					{
					setMessage($MOD_VARIABLES['MOD_ERRORS']['ERR_MESSAGESENT'], MSG_SUCCESS);
					//redirect(makeLink(array("mod"=>"messaging", "pg"=>"messaging"),"act=about"));
					}
				}//if($flag==0)
			else
				{
				setMessage($error);
				}
		
		
		
		
		}

$framework->tpl->assign("main_tpl", SITE_PATH."/modules/newsletter/tpl/contact_us.tpl");
		break;






	/**
      * Author   : Unknown
      * Created  : 
      * Modified : 7/Apr/2008 By Vipin
    */
	case "inbox":
		if(!$_REQUEST['id'])
		{	
		$orderBy=$_REQUEST['orderBy'];
		if(!$orderBy){
			$orderBy='datetime:DESC';
		}
		list($rs, $numpad) = $message->Inbox($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $orderBy);
		$framework->tpl->assign("TITLE_HEAD", "Inbox");
		//print_r($rs);exit;
		
		$count=0;
		foreach($rs as $res1)
		{
			$stat=$res1->status;
			if($stat=='U')
			$count++;
		}
		
		$framework->tpl->assign("MSG_COUNT", $count);
		$framework->tpl->assign("INBOX_LIST", $rs);
		$framework->tpl->assign("PID", $_REQUEST["pid"]);	
		$framework->tpl->assign("INBOX_NUMPAD", $numpad);
		$framework->tpl->assign("message_button",SITE_PATH."/modules/messaging/tpl/message_button.tpl");
		if ($framework->config['profile_inner']=="Y")
		{
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_main.tpl");
			$framework->tpl->assign("profile_tpl",SITE_PATH."/modules/messaging/tpl/inbox.tpl");
		}
		else
		{
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/messaging/tpl/inbox.tpl");
		}	
		}
		else{
						
			if($_REQUEST['act1']=='delete')
			{
				$message->Delete($_REQUEST['id'],'message');
				redirect(makeLink(array("mod"=>"messaging", "pg"=>"messaging"),"act=inbox&pid=$pid"));
			}
			//for delete all or more than one message
			if($_REQUEST['act1']=='deleteAll')
			{
				extract($_POST);
			if(count($inbox_del)>0) 		{
				foreach ($inbox_del as $inbox_id)
				{  
					$message->Delete($inbox_id,'message');
				}
				
			}
				redirect(makeLink(array("mod"=>"messaging", "pg"=>"messaging"),"act=inbox&pid=$pid"));
			}//
			
			if($_REQUEST['act1']=='reply'){
				$framework->tpl->assign("TYPE", 'R');
			}
			
			$rs= $message->Read($_REQUEST['id']);
			$count=0;
			foreach($rs as $res1)
			{
				$stat=$res1->status;
				if($stat=='U')
				$count++;
			}
			
			$framework->tpl->assign("MSG_COUNT", $count);
			$framework->tpl->assign("INBOX_LIST", $rs);
			$framework->tpl->assign("message_button",SITE_PATH."/modules/messaging/tpl/message_button.tpl");
			if ($framework->config['profile_inner']=="Y")
			{
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_main.tpl");
				$framework->tpl->assign("profile_tpl",SITE_PATH."/modules/messaging/tpl/inbox_view.tpl");
			}
			else
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/messaging/tpl/inbox_view.tpl");
				
			$framework->tpl->assign("TITLE_HEAD", "Read Message");
			if($_SERVER['REQUEST_METHOD'] == "POST")
			{
				$req = &$_REQUEST;
				
				$framework->tpl->assign("PID", $_REQUEST["pid"]);	
				    $cid=$_SESSION['memberid'];
			        $user_det = $user->getUserdetails($cid);
			        $fdet = $user->getUsernameDetails($_REQUEST["to"]);
				 #################Added by jinson####################
						#################on Feb 26 ####################
						#######To send Reminder mail in Link54 #############
						
						if($global['show_private']=='Y')
						{
						        $mail_header = array();
								$mail_header["from"] = $framework->config['admin_email'];
								$mail_header["to"]   = $fdet["email"];
						        $dynamic_vars = array();
								$dynamic_vars["FRIENDNAME"]  = $fdet["screen_name"];
								$dynamic_vars["USER_NAME"] =  $user_det["screen_name"];
								$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
								$linkvar="/index.php?sess=";
								$linkvar1="mod=messaging&pg=messaging&act=inbox";
								$linkvar1=base64_encode($linkvar1);
								$linkvar.=$linkvar1;
								$qr_str = "http://".$_SERVER['HTTP_HOST'].$linkvar;
								$dynamic_vars["LINK"]       = "<b><a target='_blank' href=\"".$qr_str."\">http://www.link54.com/mail</a></b>";
								$email->send("compose_mail",$mail_header,$dynamic_vars);
												
						}
						
				
							
				if( ($message1 = $message->messageSent($req)) === true ) {
				  	setMessage("Message sent successfully.", MSG_SUCCESS);
					redirect(makeLink(array("mod"=>"messaging", "pg"=>"messaging"),"act=inbox"));
				}
				
			}

		}
		break;
	case "sent":
					

		if(!$_REQUEST['id'])
		{
			$orderBy=$_REQUEST['orderBy'];
			if(!$orderBy){
				$orderBy='datetime:DESC';
			}
			list($rs, $numpad) = $message->Sent($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $orderBy);
			$framework->tpl->assign("PID", $_REQUEST["pid"]);	
			$framework->tpl->assign("SENT_LIST", $rs);
			$framework->tpl->assign("SENT_NUMPAD", $numpad);
			$framework->tpl->assign("message_button",SITE_PATH."/modules/messaging/tpl/message_button.tpl");
			if ($framework->config['profile_inner']=="Y"){
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_main.tpl");
				$framework->tpl->assign("profile_tpl",SITE_PATH."/modules/messaging/tpl/sent.tpl");
			}
			else
			{
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/messaging/tpl/sent.tpl");
			}
			$framework->tpl->assign("TITLE_HEAD", "Sent Items");
		}
		else{
			if($_REQUEST['act1']=='delete')
			{
				$message->Delete($_REQUEST['id'],'message_sent');
				redirect(makeLink(array("mod"=>"messaging", "pg"=>"messaging"),"act=sent&pid=$pid"));
			}
			
			if($_REQUEST['act1']=='deleteAll')
			{
				extract($_POST);
				
			if(count($inbox_del)>0) 		{
				foreach ($inbox_del as $inbox_id)
				{  
					$message->Delete($inbox_id,'message_sent');
				}
				
			}
				redirect(makeLink(array("mod"=>"messaging", "pg"=>"messaging"),"act=sent&pid=$pid"));
			}//
			
			
			
			$framework->tpl->assign("PID", $_REQUEST["pid"]);	
			$rs= $message->ReadSent($_REQUEST['id']);
			$framework->tpl->assign("INBOX_LIST", $rs);
			$framework->tpl->assign("message_button",SITE_PATH."/modules/messaging/tpl/message_button.tpl");
			if ($framework->config['profile_inner']=="Y"){
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_main.tpl");
				$framework->tpl->assign("profile_tpl",SITE_PATH."/modules/messaging/tpl/sent_view.tpl");
			}
			else
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/messaging/tpl/sent_view.tpl");
			$framework->tpl->assign("TITLE_HEAD", "Read Sent Message");
		}
		break;
	case "draft":
			$framework->tpl->assign("TITLE_HEAD", "Drafts");
		if(!$_REQUEST['id'])
		{
			list($rs, $numpad) = $message->Draft($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
			$framework->tpl->assign("PID", $_REQUEST["pid"]);	
			$framework->tpl->assign("DRAFT_LIST", $rs);
			$framework->tpl->assign("DRAFT_NUMPAD", $numpad);
			$framework->tpl->assign("message_button",SITE_PATH."/modules/messaging/tpl/message_button.tpl");
			if ($framework->config['profile_inner']=="Y"){
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_main.tpl");
				$framework->tpl->assign("profile_tpl",SITE_PATH."/modules/messaging/tpl/draft.tpl");
			}
			else
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/messaging/tpl/draft.tpl");
		}
		else
		{
			if($_REQUEST['act1']=='delete')
			{
				$message->Delete($_REQUEST['id'],'message_draft');
				redirect(makeLink(array("mod"=>"messaging", "pg"=>"messaging"),"act=draft&pid=$pid"));
			}
			
			if($_REQUEST['act1']=='deleteAll')
			{
				extract($_POST);
			if(count($inbox_del)>0) 		{
				foreach ($inbox_del as $inbox_id)
				{  
					$message->Delete($inbox_id,'message_draft');
				}
				
			}
				redirect(makeLink(array("mod"=>"messaging", "pg"=>"messaging"),"act=draft&pid=$pid"));
			}//
			
			$framework->tpl->assign("PID", $_REQUEST["pid"]);	
			$rs= $message->ReadDraft($_REQUEST['id']);
			$framework->tpl->assign("INBOX_LIST", $rs);
			if ($framework->config['profile_inner']=="Y"){
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_main.tpl");
				$framework->tpl->assign("profile_tpl",SITE_PATH."/modules/messaging/tpl/draft_view.tpl");
			}
			else
			
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/messaging/tpl/draft_view.tpl");
			if($_SERVER['REQUEST_METHOD'] == "POST")
			{
				$req = &$_REQUEST;
				if( ($message1 = $message->messageSent($req)) === true ) {
					redirect(makeLink(array("mod"=>"messaging", "pg"=>"messaging"),"act=msg&pid=$pid"));
				}
			}
			$framework->tpl->assign("MESSAGE", $message1);
		}
		break;
	case "contacts":
		if($_REQUEST['act1']=='delete')
		{
			$message->Delete($_REQUEST['id'],'message_contacts');

		}
		if($_REQUEST['act1']=='deleteAll')
			{
				extract($_POST);
			if(count($inbox_del)>0) 		{
				foreach ($inbox_del as $inbox_id)
				{  
					$message->Delete($inbox_id,'message_contacts');
				}
				
			}
				
			}//
			
		if($_REQUEST['act1']=='add')
		{
				$framework->tpl->assign("TITLE_HEAD", "Add Contacts");

			if($_SERVER['REQUEST_METHOD'] == "POST")
			{
				$req = &$_REQUEST;
				if( ($message1 = $message->addNewContacts($req)) === true ) {
					setMessage("Contact Added  successfully.", MSG_SUCCESS);
					redirect(makeLink(array("mod"=>"messaging", "pg"=>"messaging"),"act=contacts&pid=$pid"));
				}else{
				setMessage("Member  already added or not a valid member.", MSG_INFO);
				
				}
				
			}
			if ($framework->config['profile_inner']=="Y"){
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_main.tpl");
				$framework->tpl->assign("profile_tpl",SITE_PATH."/modules/messaging/tpl/contacts_add.tpl");
			}
			else
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/messaging/tpl/contacts_add1.tpl");

		}
		else
		{
			$framework->tpl->assign("TITLE_HEAD", "Contacts");
			
			if ($framework->config['profile_inner']=="Y"){
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_main.tpl");
				$framework->tpl->assign("profile_tpl",SITE_PATH."/modules/messaging/tpl/contacts.tpl");
			}
			else
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/messaging/tpl/contacts.tpl");
		}
		$framework->tpl->assign("PID", $_REQUEST["pid"]);	
		$framework->tpl->assign("message_button",SITE_PATH."/modules/messaging/tpl/message_button.tpl");
		$framework->tpl->assign("ERROR_MSG", $message1);
		if($global["new_album_functions"]==1){
			list($rs, $numpad) = $user->getFriendDetailsAll($_REQUEST['pageNo'],10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy'],'',$_SESSION['memberid']);
			//print_r($rs);exit;
		}else{
			list($rs, $numpad) = $message->findContacts($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
		}
		
		###to list private users on the search result (Link 54)
						### Modified on 4 Jan 2008.
		                ### Modified By Jinson.
						if($global['show_private']=='Y')
						{ for ($i=0;$i<sizeof($rs);$i++)
										{
										$medet=$objUser->getUsernameDetails($rs[$i]->username);
										if($medet["user_id"]==$_SESSION["memberid"])
										{$rs[$i]->owner="Y";}
										if($medet["mem_type"]==3){
													if($medet["friends_can_see"]=='Y')
													            {
										                     if($objUser->isFriends($medet["user_id"],$_SESSION["memberid"])==true)
															 {$rs[$i]->show_profile="Y";}
															 else{$rs[$i]->show_profile="N";}
										                         }
																			else{
															$rs[$i]->show_profile="N";
																				}
																				if($medet["user_id"]==$_SESSION["memberid"]){
						                                                        $rs[$i]->show_profile="Y";
					                                                             }
																				
																 }
																 else{$rs[$i]->show_profile="Y";}										
	
										}
						    }//if
					    //////////////////
						//print_r($rs);
		$framework->tpl->assign("PID", $_REQUEST["pid"]);	
		$framework->tpl->assign("INBOX_LIST", $rs);
		$framework->tpl->assign("INBOX_NUMPAD", $numpad);
		break;
	case "compose":
			$framework->tpl->assign("TITLE_HEAD", "Compose Message");
			
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			$req = &$_REQUEST;
		    $cid=$_SESSION['memberid'];
			$user_det = $user->getUserdetails($cid);
			$fdet = $user->getUsernameDetails($_REQUEST["to"]);
			if($_POST['draft']!=1){
				if( ($message1 = $message->messageSent($req)) === true ) {
				        #################Added by jinson####################
						#################on Feb 26 ####################
						#######To send Reminder mail in Link54 #############
						
						if($global['show_private']=='Y')
						{
						
						        $mail_header = array();
								$mail_header["from"] = $framework->config['admin_email'];
								$mail_header["to"]   = $fdet["email"];
						        $dynamic_vars = array();
								$dynamic_vars["FRIENDNAME"]  = $fdet["screen_name"];
								$dynamic_vars["USER_NAME"] =  $user_det["screen_name"];
								$dynamic_vars["SITE_NAME"]  = $framework->config['site_name'];
								$linkvar="/index.php?sess=";
								$linkvar1="mod=messaging&pg=messaging&act=inbox";
								$linkvar1=base64_encode($linkvar1);
								$linkvar.=$linkvar1;
								$qr_str = "http://".$_SERVER['HTTP_HOST'].$linkvar;
								$dynamic_vars["LINK"]       = "<b><a target='_blank' href=\"".$qr_str."\">http://www.link54.com/mail</a></b>";
								$email->send("compose_mail",$mail_header,$dynamic_vars);
												
						}
						
						
						
				     

					redirect(makeLink(array("mod"=>"messaging", "pg"=>"messaging"),"act=msg&pid=$pid"));
				}
			}
			else{
				if( ($message1 = $message->messageDraft($req)) === true ) {
					redirect(makeLink(array("mod"=>"messaging", "pg"=>"messaging"),"act=draft&pid=$pid"));
				}
			}

		}
		if($global["new_album_functions"]==1){
			$rs = $user->viewFriends($_SESSION['memberid']);
			
		}else{
			$rs= $message->viewContacts();
		}
		$framework->tpl->assign("PID", $_REQUEST["pid"]);	
		$framework->tpl->assign("MESSAGE", $message1);
		$framework->tpl->assign("CONTACT_LIST", $rs);
		$framework->tpl->assign("message_button",SITE_PATH."/modules/messaging/tpl/message_button.tpl");
		if ($framework->config['profile_inner']=="Y"){
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_main.tpl");
			$framework->tpl->assign("profile_tpl",SITE_PATH."/modules/messaging/tpl/compose.tpl");
		}
		else{
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/messaging/tpl/compose.tpl");
		}
		break;
	case "block":
	
		$block_id=$_REQUEST['id'];
	    $framework->tpl->assign("PID", $_REQUEST["pid"]);	
		$message->blockUser($block_id);
		redirect(makeLink(array("mod"=>"messaging", "pg"=>"messaging"),"act=inbox&pid=$pid"));
	break;
	case "blockList":
		$framework->tpl->assign("TITLE_HEAD", "Blocked List");
	if($_REQUEST['act1']=='delete')
		{
			$message->Delete($_REQUEST['id'],'message_blocked_user');

		}
		list($rs, $numpad) = $message->findBlocked($_REQUEST['pageNo'], 10, "mod=$mod&pg=$pg&act=".$_REQUEST['act'], OBJECT, $_REQUEST['orderBy']);
		
		//print_r($rs);
		
		$framework->tpl->assign("PID", $_REQUEST["pid"]);	
		$framework->tpl->assign("INBOX_LIST", $rs);
		$framework->tpl->assign("INBOX_NUMPAD", $numpad);
		$framework->tpl->assign("message_button",SITE_PATH."/modules/messaging/tpl/message_button.tpl");
		if ($framework->config['profile_inner']=="Y"){
				$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_main.tpl");
				$framework->tpl->assign("profile_tpl",SITE_PATH."/modules/messaging/tpl/blocked.tpl");
		}
		else
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/messaging/tpl/blocked.tpl");
	break;
	case "msg":
		$framework->tpl->assign("PID", $_REQUEST["pid"]);	
		$framework->tpl->assign("TITLE_HEAD", "Message Sent");
		if ($framework->config['profile_inner']=="Y"){
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/profile_main.tpl");
			$framework->tpl->assign("profile_tpl",SITE_PATH."/modules/messaging/tpl/confirmation_msg.tpl");
		}
		else
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/messaging/tpl/confirmation_msg.tpl");
	break;
	default:
	$framework->tpl->assign("PID", $_REQUEST["pid"]);	
		$framework->tpl->assign("message_button",SITE_PATH."/modules/messaging/tpl/message_button.tpl");
		$framework->tpl->assign("main_tpl",SITE_PATH."/modules/messaging/tpl/inbox.tpl");
		$framework->tpl->assign("TITLE_HEAD", "Messaging");
		break;
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>