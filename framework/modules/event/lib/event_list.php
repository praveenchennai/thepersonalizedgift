<?
include_once(FRAMEWORK_PATH."/modules/event/lib/calendar.config.php");
include_once(FRAMEWORK_PATH."/modules/event/lib/class.event.php");

	$objEvent = new Event ();

	switch( $_REQUEST['act'])
	{
		case "event_list":
		
			/*
			search events
			*/
			
			if(isset($_REQUEST['stat']))
			{
				$objEvent->eventActiveDeactive($_REQUEST['stat'],$_REQUEST['eventid']);
				
				if($_REQUEST['type'] == "featured")
				redirect(makeLink(array("mod"=>"event", "pg"=>"event_list"), "sId=".$_REQUEST['sId']."&fId=".$_REQUEST['fId']."&act=featured"));
				else
				redirect(makeLink(array("mod"=>"event", "pg"=>"event_list"), "sId=".$_REQUEST['sId']."&fId=".$_REQUEST['fId']."&act=event_list"));
			}
			
			list($rs, $numpad,$cnt_rs, $limitList) = $objEvent->eventPosted($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act']."","", $_REQUEST['orderBy'],$_REQUEST["txtsearch"]);
			$framework->tpl->assign("ACTIVE", "");
			$framework->tpl->assign("EVENT_LIST", $rs);
			$framework->tpl->assign("USER_NUMPAD", $numpad);
			$framework->tpl->assign("LIMIT_LIST",$limitList);
			
			$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/event/tpl/event_list.tpl");
			
		break;
		case "details":
			
			if(count($_POST))
			{
			
				if($_REQUEST['type'] == 'featured')
				{
					$objEvent->removeFeatured($_REQUEST['eventid']);
					redirect(makeLink(array("mod"=>"event", "pg"=>"event_list"), "sId=".$_REQUEST['sId']."&fId=".$_REQUEST['fId']."&act=featured"));
				}
				else
				{
					if($objEvent->insertFeatured())
					{
					redirect(makeLink(array("mod"=>"event", "pg"=>"event_list"), "sId=".$_REQUEST['sId']."&fId=".$_REQUEST['fId']."&act=featured"));
					}
				}
			}
			
			$framework->tpl->assign("EVENT_DET",$objEvent->eventDetails($_REQUEST['eventid']));
			$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/event/tpl/event_form.tpl");
		
		break;
		
		case "featured":
			
			list($rs, $numpad,$cnt_rs, $limitList) = $objEvent->featuredEvent($_REQUEST['pageNo'], $_REQUEST['limit'], "mod=$mod&pg=$pg&act=".$_REQUEST['act'],"", $_REQUEST['orderBy'],$_REQUEST["txtsearch"]);
			$framework->tpl->assign("EVENT_LIST", $rs);
			$framework->tpl->assign("USER_NUMPAD", $numpad);
			$framework->tpl->assign("LIMIT_LIST",$limitList);
			$framework->tpl->assign("main_tpl",FRAMEWORK_PATH."/modules/event/tpl/event_list_featured.tpl");

		break;
	}
	
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");

?>
