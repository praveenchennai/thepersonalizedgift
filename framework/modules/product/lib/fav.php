<?
session_start();
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/extras/lib/class.extras.php");
$objProduct		=	new Product();
$objUser		=	new User();
$objExtras		=	new Extras();
checkLogin();

switch($_REQUEST['act']) {
	case "add":
		$id 		= 	$_REQUEST["id"] ? $_REQUEST["id"] : "";
		$cat_id 	= 	$_REQUEST["cat_id"] ? $_REQUEST["cat_id"] : "";
		$stat		=	$objProduct->addFavorite($id,$_SESSION["memberid"]);
		if($stat == true)
		{
		setMessage("Product Successfully Added to Favourite List", MSG_SUCCESS);
		}
		else
		{
		setMessage("Product Already Present in Favourite List");
		}
		redirect(makeLink(array("mod"=>"product", "pg"=>"list"),"act=desc&id=$id&cat_id=$cat_id"));
		break;
	case "list":
		$param				=	"mod=product&pg=fav&act=list";
		list($res, $numpad, $cnt, $limitList)	=	$objProduct->GetFavList($_SESSION["memberid"],$storename,$pageNo,20,$param,OBJECT, $orderBy);
		$framework->tpl->assign("PRODUCTS",$res);
		$framework->tpl->assign("PRODUCT_NUMPAD",$numpad);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/fav_list.tpl");
		break;
	case "remove":
		$product_id 		= 	$_REQUEST["id"] ? $_REQUEST["id"] : "";
		$stat		=	$objProduct->Removefav($product_id,$_SESSION["memberid"]);
		if($stat == true)
		{
		setMessage("Product Successfully Deleted", MSG_SUCCESS);
		}
		else
		{
		setMessage("Product cannot delete");
		}
		redirect(makeLink(array("mod"=>"product", "pg"=>"fav"),"act=list"));
		break;
	case "last_purchase":
		$pageNo 		= 	$_REQUEST["pageNo"] ? $_REQUEST["pageNo"] : "1";
		$limit			=	$_REQUEST["limit"] ? $_REQUEST["limit"] : "20";
		
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			header('Expires: ' . gmdate("D, d M Y H:i:s", time()+1000) . ' GMT');
			header('Cache-Control: Private');
			$_REQUEST["from"]		=	$_POST["from"] ? $_POST["from"] : "";
			$_REQUEST["to"]			=	$_POST["to"] ? $_POST["to"] : "";
		}
		$from			=	$_REQUEST["from"] ? $_REQUEST["from"] : "";
		$to				=	$_REQUEST["to"] ? $_REQUEST["to"] : "";
		$param			=	"mod=product&pg=fav&act=last_purchase&limit=$limit&from=$from&to=$to";
		list($res, $numpad, $cnt, $limitList)	=	$objProduct->GetLastPurchase($_SESSION["memberid"],$from,$to,$storename,$pageNo,20,$param,OBJECT);
		$framework->tpl->assign("PRODUCTS",$res);
		$framework->tpl->assign("PRODUCT_NUMPAD",$numpad);
		$framework->tpl->assign("SUBMITBUTTON", createButton("SUBMIT","#","check()"));
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/last_order.tpl");
		break;
	case "gift":
		$gift =	$objExtras->giftByuserid($_SESSION["memberid"]);
		$framework->tpl->assign("GIFT",$gift);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/fav_gift.tpl");
		break;
	case "coupons":
		$coupon =	$objExtras->couponByuserid($_SESSION["memberid"]);
		$framework->tpl->assign("COUPONS",$coupon);
		$framework->tpl->assign("main_tpl", SITE_PATH."/modules/product/tpl/fav_coupons.tpl");
		break;
}
$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>