<?
session_start();
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.widget.php");

$act			=	$_REQUEST["act"] ? $_REQUEST["act"] : "";
$keysearch		=	$_REQUEST["keysearch"] ? $_REQUEST["keysearch"] : "N";
$category_search=	$_REQUEST["category_search"] ? $_REQUEST["category_search"] : "";
$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
$orderBy 		= 	$_REQUEST["orderBy"] ? $_REQUEST["orderBy"] : "title";
$parent_id 		= 	$_REQUEST["parent_id"] ? $_REQUEST["parent_id"] : "0";
$param			=	"mod=$mod&pg=$pg&act=list&orderBy=$orderBy&parent_id=$parent_id&category_search=$category_search&keysearch=$keysearch&sId=$sId&fId=$fId";
$objCategory	=	new Category();
$objFlyer	=	new Flyer();
$widget		=	new Widget();
switch($_REQUEST['act']) {
		case "list":
		$parent_id		=	isset($_REQUEST["cat_id"])?trim($_REQUEST["cat_id"]):"0";
		if($parent_id=="")
		$parent_id	=	0;
		list($rs, $numpad, $cnt, $limitList)	= 	$widget->listMyWidgets('A',$pageNo,$limit,$param,OBJECT, $orderBy,$status_id);
		$framework->tpl->assign("WIDGET_LIST", $rs);
		$framework->tpl->assign("ACT", "form");
		$framework->tpl->assign("WIDGET_NUMPAD", $numpad);
		$framework->tpl->assign("WIDGET_LIMIT", $limitList);
		$framework->tpl->assign("WIDGET_SEARCH_TAG", $flyer_search);
		$framework->tpl->assign("WIDGET_PATH", SITE_URL."/index.php?gadId=");
		if($parent_id>0)
		{
			$framework->tpl->assign('SELECT_DEFAULT', '--- '.$objCategory->getCategoryname($parent_id).' ---');
		}
		else
		{
			$framework->tpl->assign('SELECT_DEFAULT', "");
		}
		
		$framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/flyer/tpl/widget_list.tpl");

		
		break;
		
		case "delete":
			extract($_POST);
			if(count($category_id)>0) 		{
			$message=true;
			foreach ($category_id as $wid_id)
				{  
				
				if($widget->gadgetDelete($wid_id)==false)
					$message=false;
				}
			}
			if($message==true)
				setMessage($_REQUEST["sId"]."(s) Deleted Successfully!", MSG_SUCCESS);
			if($message==false)
				setMessage($_REQUEST["sId"]."(s) Can not Deleted!");
			redirect(makeLink(array("mod"=>"flyer", "pg"=>"wid_admin"), "act=list&cat_id={$_REQUEST['cat_id']}&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
			break;
		
	
}

$framework->tpl->display($global['curr_tpl']."/admin.tpl");

?>