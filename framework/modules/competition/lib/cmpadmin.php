<?php
	session_start();
	include_once(FRAMEWORK_PATH."/modules/competition/lib/class.cmp.php");
	include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
	
	authorize();

	$objCmp=new Cmp();
	$objUser=new User();
	
	$framework->tpl->assign("CAT_LIST", $objUser->getCategoryCombo($_REQUEST["mod"]));	
	switch($_REQUEST['act']) 
	{
		case "create":
			$date=date("Y-m-d");
			$framework->tpl->assign("DT",$date);

			if ($_SERVER['REQUEST_METHOD']=="POST")
			{
				$_POST["user_id"]=$_SESSION['adminSess']->id;
				$_POST["owner_type"]="admin";
				$_POST["enddate"]=$_POST["enddate"]." 23:59:59";
				
				$fr_size = $_POST["fr_size"] * 60;
				$to_size = $_POST["to_size"] * 60;
				
				$_POST["fr_size"] = $fr_size;
				$_POST["to_size"] = $to_size;

				
				$objCmp->setArrData($_POST);
				if ($objCmp->insertCmp())
				{
					redirect(makeLink(array("mod"=>"competition", "pg"=>"cmpadmin"),"act=list&filter=1"));
				}
				else
				{
					$framework->tpl->assign("MESSAGE",$objCmp->getErr());		
				}
			}
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/competition/tpl/cmp_create_admin.tpl");
			break;
		case "list":
			
			$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&filter=".$_REQUEST["filter"];
			if($_REQUEST["fn"]=="del")
			{
				if($objCmp->cmpDelete($_REQUEST["cmp_id"]))
				{
					redirect(makeLink(array("mod"=>"competition", "pg"=>"cmpadmin"),"act=list&filter=".$_REQUEST["filter"]));	
				}
				else
				{
					
					$framework->tpl->assign("MESSAGE",$objCmp->getErr());
				}	
			}	

			if($_REQUEST["filter"]==1)
			{
				$framework->tpl->assign("HEADER", "Running Competitions");
			}
			elseif($_REQUEST["filter"]==2)
			{
				$framework->tpl->assign("HEADER", "Finished Competitions");
			}
			elseif($_REQUEST["filter"]==3)
			{
				$framework->tpl->assign("HEADER", "Scheduled Competitions");
			}
			elseif($_REQUEST["filter"]==4)
			{
				$framework->tpl->assign("HEADER", "Archived Competitions");
			}
			
			list($rs, $numpad) = $objCmp->cmpList($_REQUEST['pageNo'], 5, $par, OBJECT, $_REQUEST['orderBy'],0,$stxt,0,0,$_REQUEST["filter"],1);
			$framework->tpl->assign("CMP_LIST", $rs);
			$framework->tpl->assign("CMP_NUMPAD", $numpad);

			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/competition/tpl/cmp_list_admin.tpl");
			break;
		case "pay_list":
			if($_REQUEST["fn"]=="del")
			{
				$objCmp->cmpPayDel($_REQUEST["id"]);
			}
			$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act'];
			list($rs, $numpad) = $objCmp->cmpPayList($_REQUEST['pageNo'], 5, $par, OBJECT, $_REQUEST['orderBy']);

			$framework->tpl->assign("CMP_PAY_LIST", $rs);
			$framework->tpl->assign("CMP_PAY_NUMPAD", $numpad);

			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/competition/tpl/cmp_pay_list.tpl");
			break;
		case "details":
			$cmp_id=$_REQUEST["cmp_id"];
			$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&cmp_id=".$_REQUEST["cmp_id"];

			if ($_REQUEST["fn"]=="join")
			{
				checkLogin();
				if ($objCmp->checkCmpMember($cmp_id,$_SESSION["memberid"]))
				{
					$framework->tpl->assign("ERROR_MSG","You are already a member of this Competition");
				}
				else
				{
					redirect(makeLink(array("mod"=>"competition", "pg"=>"cmp"),"act=join&cmp_id=".$cmp_id));
				}

								
			}
			
			
			if($_SESSION["memberid"])
			{
				if ($objCmp->checkCmpMember($cmp_id,$_SESSION["memberid"]))
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
			$cmp_det=$objCmp->getCmpDetails($cmp_id);
			list($rs, $numpad) = $objCmp->cmpMemList($_REQUEST['pageNo'], 5, $par, OBJECT, $_REQUEST['orderBy'],$cmp_id);
			$framework->tpl->assign("CMP_MEM_LIST", $rs);
			$framework->tpl->assign("CMP_MEM_NUMPAD", $numpad);
			
			$framework->tpl->assign("CMP_DET",$cmp_det);
			$tot_fee=$cmp_det["active_users"] * $cmp_det["join_fee"];
			$framework->tpl->assign("TOT_FEE",$tot_fee);
			$framework->tpl->assign("main_tpl",SITE_PATH."/modules/competition/tpl/cmp_details_admin.tpl");
			break;
			
		case "media":
			$cmp_id=$_REQUEST["cmp_id"];
			$framework->tpl->assign("MEDIA_CNT",$objCmp->getMediaCnt($cmp_id));
			$cmp_det=$objCmp->getCmpDetails($cmp_id);
			$framework->tpl->assign("FILE_ID",$cmp_det["media"]."_id");
			$framework->tpl->assign("MPATH",$cmp_det["media"]);
			$par = "mod=$mod&pg=$pg&act=".$_REQUEST['act']."&cmp_id=".$_REQUEST["cmp_id"]."&filter=".$_REQUEST["filter"];

			if($_REQUEST["fn"]=="join")
			{
				checkLogin();
				if(!$objCmp->joinCmp($cmp_id,$_SESSION["memberid"]))
				{
					$framework->tpl->assign("MESSAGE",$objCmp->getErr());			
				}	
			}
			
			if($_SESSION["memberid"])
			{
				if ($objCmp->checkCmpMember($cmp_id,$_SESSION["memberid"]))
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
			$cmp_det=$objCmp->getCmpDetails($cmp_id);
			list($rs, $numpad) = $objCmp->cmpMediaList($_REQUEST['pageNo'], 5, $par, OBJECT, $_REQUEST['orderBy'],$cmp_id,$cmp_det["media"]);
			$framework->tpl->assign("PHOTO_LIST", $rs);
			$framework->tpl->assign("PHOTO_NUMPAD", $numpad);
			
			$framework->tpl->assign("CMP_DET",$cmp_det);
			$tot_fee=$cmp_det["active_users"] * $cmp_det["join_fee"];
			$framework->tpl->assign("TOT_FEE",$tot_fee);


			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/competition/tpl/cmp_media_admin.tpl");
			break;		
		case "add_point":
			
			if($_SERVER['REQUEST_METHOD']=="POST")
			{
				if ($objCmp->addPoints($_REQUEST["file_id"],$_REQUEST["mark"]))
				{
					
					redirect(makeLink(array("mod"=>"competition", "pg"=>"cmpadmin"),"act=media&cmp_id=".$_REQUEST["cmp_id"]));
				}
				
				
			}
			
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/competition/tpl/cmp_add_points.tpl");
			break;		
		case "move":
			$objCmp->moveArchive($_REQUEST["cmp_id"]);
			redirect(makeLink(array("mod"=>"competition", "pg"=>"cmpadmin"),"act=list&filter=".$_REQUEST["filter"]));	
			break;
		case "tra":
			if($_SERVER['REQUEST_METHOD']=="POST")
			{
				$objCmp->setArrData($_POST);
				$objCmp->insertFee();
			}
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/competition/tpl/cmp_tra_fee.tpl");			
			break;
	}
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");

?>