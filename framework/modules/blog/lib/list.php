<?
session_start();
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.email.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/blog/lib/class.list.php");
//include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
$objCms = new Cms();
$blog = new Blog();
$framework->tpl->assign("LEFTBOTTOM",'blog');
if($_REQUEST["leftid"]==0){
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("social_community_right"));// to select the right menu of this page from database.
	}elseif($_REQUEST["leftid"]==4){
		$framework->tpl->assign("RIGHT_MENU", $objCms->linkList("rest_right"));// to select the right menu of this page from database.
	}
$user           =   new User();
$email			= 	new Email();
$flyer			=	new	Flyer();
$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
switch($_REQUEST['act']) {
	
		case "flyer_list":
			checkLogin();
			$orderBy	=	$_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : "title";
			list($rs, $numpad, $cnt, $limitList)	= 	$flyer->listMyFlyers('U',$keysearch,$flyer_search,$pageNo,$limit,$param,OBJECT, $orderBy);
			
			$RssURL		=	WEBSITE_URL.'/index.php?mod=flyer&pg=preview&act=rss_flyerlist&member_id='.$_REQUEST['member_id'];
			$RssLink	=	'|&nbsp;&nbsp;<a target="_blank" href="'.$RssURL.'"><img src="'.$global['site_url'].'/templates/blue/images/rsslink.gif" border="0" /></a>&nbsp;&nbsp;|&nbsp;&nbsp; <a class="footerlink" href="'.makeLink(array("mod"=>"flyer", "pg"=>"list"), "act=rss_bookmark&member_id={$_SESSION['memberid']}").'" >RSS Help</a>';
			$framework->tpl->assign("RSS_LINK", $RssLink);
						
			$framework->tpl->assign("FLYER_LIST", $rs);
			$framework->tpl->assign("ACT", "form");
			$framework->tpl->assign("FLYER_NUMPAD", $numpad);
			$framework->tpl->assign("FLYER_LIMIT", $limitList);
			$framework->tpl->assign("FLYER_SEARCH_TAG", $flyer_search);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/flyer/tpl/flyer_list.tpl");
			break;
			
		case "flyer_gallery":
			#$RssURL		=	WEBSITE_URL.'/index.php?mod=flyer&pg=preview&act=rss_flyerlist&member_id='.$_REQUEST['member_id'];
			$heluUrl	=	WEBSITE_URL."/index.php?mod=flyer&pg=list&act=rss_bookmark&member_id=".$_REQUEST['member_id'];
			$RssURL		=	WEBSITE_URL.'/'.makeLink(array("mod"=>"flyer", "pg"=>"preview"), "act=rss_flyerlist&member_id={$_REQUEST['member_id']}");
			$RssLink	=	'|&nbsp;&nbsp;<a target="_blank" href="'.$RssURL.'"><img src="'.$global['site_url'].'/templates/blue/images/rsslink.gif" border="0" /></a>&nbsp;&nbsp;|&nbsp;&nbsp; <a class="footerlink" href="'.$heluUrl.'" >RSS Help</a>';
			$framework->tpl->assign("RSS_LINK", $RssLink);
			$framework->tpl->assign("HEADER_LINKS", $flyer->GetHeaderLinks($_REQUEST["member_id"]));
			$framework->tpl->assign("FOOTER_LINKS", $flyer->GetFooterLinks($_REQUEST["member_id"]));
			$user_id	=	$_REQUEST["member_id"];
			
			#-- for pagination -
				$pageurl		=	"bdetails";
				$id=0;		
				$displayno		=	10;
				$RecordView		=	$displayno;
				$count1			=	$displayno;						   		
				$LLimit			=	0;
				$ULimit			=	$displayno;
				
				if(isset($_REQUEST['id'])) {
				
				$id			=	intval($_REQUEST['id']);
				$LLimit		=	($id*$displayno)-$displayno;
				$RecordView	=	($id*$displayno);
				$idprev		=	$_GET['id']-1;
				$idnext		=	$_GET['id']+1;
				
				} else {
				$id=1;
				$idnext=2;
				}
				
				$i=0;
				
				$conno			=	$flyer->GetFormRssFieldsCount($_REQUEST["member_id"]);
				$numpage		=	ceil($conno/$displayno);
				$framework->tpl->assign("RSS_FLYER_FIELDS", $flyer->GetFormRssFields($_REQUEST["member_id"],$LLimit,$ULimit));
				if($RecordView > $conno) {
				$RecordView=$conno;
				} 
				
				$pnumbers	=	"";
		if($id!=1) { 
			$prevpad	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"index.php?mod=blog&pg=list&act=flyer_gallery&member_id=$user_id&flg=N&id=$idprev&fl=$filter\" class=\"body_links\">Previous </a>&nbsp;&nbsp;";
		} else { 
			$prevpad	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Previous &nbsp;&nbsp;&nbsp;";
		}
		//for($i=1;$i<=$numpage;$i++) { 
		$startCount	=	$id-2;
		$endCount	=	$id+2;
		if($startCount <=1)
		{ 
			 $startCount=1; 
			 $endCount= 5;
		}
		if($endCount >= $numpage)
		{ 
			$endCount=$numpage; 
			$startCount=$id-2;
			if($startCount<=1)
			$startCount=1;
		}
		
		
		for($i=$startCount;$i<=$endCount;$i++) { 
			if($id==$i){
			$pnumbers	.= "<a href=\"index.php?mod=blog&pg=list&act=flyer_gallery&member_id=$user_id&flg=N&id=$i\" class=\"body_red_links\">$i</a>&nbsp;&nbsp;"; }
			else {
			$pnumbers	.= "<a href=\"index.php?mod=blog&pg=list&act=flyer_gallery&member_id=$user_id&flg=N&id=$i\" class=\"body_links\">$i</a>&nbsp;&nbsp;"; }
			
		} if($id!=$numpage) {
			$nextpad = "<a href=\"index.php?mod=blog&pg=list&act=flyer_gallery&member_id=$user_id&flg=N&id=$idnext\" class=\"body_links\">Next  </a> &nbsp;&nbsp;";
		} else {
			$nextpad = "&nbsp;&nbsp;Next ";
		}
			if($numpage =="1")
			{ $framework->tpl->assign("NEXT", ""); }
			else { 
			$framework->tpl->assign("NEXT", $nextpad); }
			if($numpage =="1")
			{ $framework->tpl->assign("PREV", ""); }
			else {
			$framework->tpl->assign("PREV", $prevpad); }
			$framework->tpl->assign("PAGES", $pnumbers);
			#-- end for pagination --
			
			$framework->tpl->assign("FLYER_PATH", SITE_URL."/htmlflyers/");
			$framework->tpl->assign("MEMBER_DETAILS", $user->getUserdetails($_REQUEST["member_id"]));
			$account_url	=	"http://".DOMAIN_URL;
			
			$framework->tpl->assign("ACCOUNT_URL", $account_url);
			$framework->tpl->assign("main_tpl", SITE_PATH."/modules/blog/tpl/flyer_gallary_list.tpl");
			break;
			
			
			case "flyer_preview":
			$flyer_id			=	$_REQUEST['flyer_id']; 
			$rs=$flyer->addFlyerHit($flyer_id);
			require_once(SITE_PATH."/htmlflyers/".$flyer_id.".html");
			
			print '<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
					<script type="text/javascript">
						_uacct = "UA-1030655-5";
						urchinTracker();
					</script>';
			
			exit;
			
			break;
		

}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
//$framework->tpl->display($global['curr_tpl']."/subdomain_inner.tpl");
?>