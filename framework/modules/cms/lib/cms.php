<?php 

include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");

$cms = new Cms();

switch($_REQUEST['act']) {
    case "stickers":
       
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/cms/tpl/about_stickers.tpl");
        break;
	case "requirement":
       
        $framework->tpl->assign("main_tpl", SITE_PATH."/modules/cms/tpl/art_requirement.tpl");
        break;
	
	case "popup":
	   $static = trim($_REQUEST['static']);
	  $contents = $cms->getContent($static);		
	 $framework->tpl->assign("CONTENTS",$contents);
	 $framework->tpl->display(SITE_PATH."/modules/cms/tpl/popup.tpl");
	exit;
	break;
	case "urlpopup":
	   $static = trim($_REQUEST['static']); 
	   $url = $_REQUEST['url'];
	  $contents = $cms->getContent($static);	
	
	  $content.="<font size='2' face='Arial'><span  >Redirect Url </span></font>:<input type='text' style='width:269px;' value='$url'><br/>";
	   $content.=$contents['content'];
	 $framework->tpl->assign("CONTENTS",$content);
	 $framework->tpl->display(SITE_PATH."/modules/cms/tpl/popup.tpl");
	exit;
	break;
	
	}
	
	
	
	
$framework->tpl->display($global['curr_tpl']."/inner.tpl");

?>