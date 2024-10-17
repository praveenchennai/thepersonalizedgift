<?
session_start();
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");
include_once(FRAMEWORK_PATH."/modules/flyer/lib/class.flyer.php");

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
switch($_REQUEST['act']) {
	case "list":
		
		
		break;
		
		case "delete":
			extract($_POST);
			if(count($category_id)>0) 		{
			$message=true;
			foreach ($category_id as $flyer_id)
				{  
				
				if($objFlyer->flyerDelete($flyer_id)==false)
					$message=false;
				}
			}
			if($message==true)
				setMessage($_REQUEST["sId"]."(s) Deleted Successfully!", MSG_SUCCESS);
			if($message==false)
				setMessage($_REQUEST["sId"]."(s) Can not Deleted!");
			redirect(makeLink(array("mod"=>"flyer", "pg"=>"flyer"), "act=list&cat_id={$_REQUEST['cat_id']}&pageNo=$pageNo&limit=$limit&sId=".$_REQUEST["sId"]."&fId=".$_REQUEST["fId"]."&mId=".$_REQUEST["mId"]));
			break;
		
	
}

$framework->tpl->display($global['curr_tpl']."/admin.tpl");

?>