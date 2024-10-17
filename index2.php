<?php
ob_start();
session_start();


ini_set("display_errors", "off");

error_reporting(E_ALL ^ E_NOTICE);

include_once("config.php");

include_once(FRAMEWORK_PATH."/includes/functions.php");
include_once(FRAMEWORK_PATH."/includes/class.framework.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.product.php");
include_once(FRAMEWORK_PATH."/modules/category/lib/class.category.php");
include_once(FRAMEWORK_PATH."/modules/store/lib/class.store.php");
include_once(FRAMEWORK_PATH."/modules/cms/lib/class.cms.php");
include_once(FRAMEWORK_PATH."/modules/cart/lib/class.cart.php");
include_once(FRAMEWORK_PATH."/modules/store/lib/class.template.php");
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/product/lib/class.price.php");
include_once(FRAMEWORK_PATH."/modules/order/lib/class.paymenttype.php");

//print($_SERVER['QUERY_STRING']);




//error_reporting(E_WARNING);
$framework 	= 	new FrameWork();
$objUser	=	new User();
$objPrice	=	new Price();
$product    =   new Product();
$paymenttypeObj	= 	new paymentType();
$objStore 			= 	new Store();

// To make SEO URL for Produt Details 
// Author - Retheesh
// Date - 12/Aug/2008
// Start

//print $product->getProductDetails(494) ;

#LABEL MANAGMENT
$MOD_VARIABLES = $framework->MOD_VARIABLES;
$COM_VARIABLES = $framework->COM_VARIABLES;
$framework->tpl->assign("MOD_VARIABLES", $MOD_VARIABLES);
$framework->tpl->assign("COM_VARIABLES", $COM_VARIABLES);



// End
//echo 1;
//print_r($_SESSION);

if ($_REQUEST['sess']) {
	parse_str(base64_decode($_REQUEST['sess']), $req);
	$_REQUEST = array_merge($_REQUEST, $req);
}

if ($_SESSION["memberid"]) {	
	$framework->tpl->assign("MEMBER", $objUser->getUserdetails($_SESSION["memberid"]));
	$framework->tpl->assign("LASTLOGIN", $objUser->getLastSession($_SESSION["memberid"]));
}

$storename	=	$_REQUEST['storename'] 	? 	$_REQUEST['storename'] 	: 	 "";
$storedet=	$objStore->storeGetByName1($storename);



#----- for redirecting to the store on 03-07-07 -------------
include_once(FRAMEWORK_PATH."/modules/store/lib/class.storeredirect.php");
$objRedir	=	new StoreRedirect();
$domain	=	$_SERVER["HTTP_HOST"];
$domainpath = $_SERVER['REQUEST_URI'];
$domain_name1	= HTTP_PROTOCOL.WWW_STR.$domain;
$domain_name2	= HTTP_PROTOCOL.$domain;
$domain_name3	= $domain;
$domain_name4	= str_replace("www.","",$domain);
$redirectStorename	=	$objRedir->GetStorenameforRedirect($domain_name1);
if($redirectStorename=="")
{
	$redirectStorename	=	$objRedir->GetStorenameforRedirect($domain_name2);
}
if($redirectStorename=="")
{
	$redirectStorename	=	$objRedir->GetStorenameforRedirect($domain_name3);
}
/*if($redirectStorename=="")
{
$redirectStorename	=	$objRedir->GetStorenameforRedirect($domain_name4);
}*/

//print($domain);exit;


if($redirectStorename!="" && $domainpath=="/")
{
	$NewDomain=str_replace("www.","",$domain_name2);
	//$redirectURL	=	SITE_URL."/".$redirectStorename."/";
	$redirectURL = HTTP_PROTOCOL.WWW_STR.$_SERVER["HTTP_HOST"]."/";
	//$redirectURL	=	$domain_name2."/".$redirectStorename;
	//$redirectURL	=	$NewDomain."/".$redirectStorename;
	$redirectStatus	=	0;
	$redirectStatus	=	$objRedir->isURLexist(SITE_URL);
	if($redirectStatus==0)
	{
		header("Location: $redirectURL");
	}

}





$objCss 			= 	new Template();
$global 				= $framework->config;
$storename	=	$_REQUEST['storename'] 	? 	$_REQUEST['storename'] 	: 	 "";
if($storename=="")
{ $storename	=	$objRedir->getStoreName(SITE_URL); }
$domain	=	$_SERVER["HTTP_HOST"];
$domain_name1	= HTTP_PROTOCOL.WWW_STR.$domain;
$domain_name2	= HTTP_PROTOCOL.$domain;
if($storename=="")
{ $storename	=	$objRedir->getStoreName($domain_name1); }
if($storename=="")
{ $storename	=	$objRedir->getStoreName($domain_name2); }

$storeDetails			=	$objStore->storeGetByName1($storename);
$_REQUEST['storename']=$storename;

if($_REQUEST['storename'])
$_REQUEST['web_store_url']=$_REQUEST['storename'];
else
$_REQUEST['web_store_url']='rootstore';



$store_name = $storename;
$store_id				=	$storeDetails['id'];
$payment_owner			=	$storeDetails['payment_receiver'];
$cssDetails				=	$objCss->getCss($storeDetails['template_id']);

if ($_REQUEST['seo_name'])
{
	$product_det = $product->getSeoProductDetails($_REQUEST['seo_name'],$_REQUEST['storename']);
	$_REQUEST['id'] = $product_det['id'];
	$_REQUEST['cat_id'] = $product_det['category_id'];
}


//print_r($cssDetails	);

if($_REQUEST['storename'] !='' && $storeDetails == '' ){
//	This will redirect to main site if the store URL following the site name is not present .
	$framework->tpl->assign("INVALID_STORE",$_SERVER['REQUEST_URI']);
	$_SESSION['invalid_store_url']=SITE_URL.$_SERVER['REQUEST_URI'];
	redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=invalid_store"));
	//redirect(SITE_URL);
	

	
}






if($cssDetails['css_name']){
	$css_name			= 	$cssDetails['css_name'];
}else{
	$css_name			= 	"style.css";
}
if($cssDetails['template_folder']){
	$global['curr_tpl']	= $cssDetails['template_folder'];
}
$global['store_id']		= $store_id;
if($store_id){
	$framework->tpl->assign("PAYMENT_OWNER",$payment_owner);
}	

//if($_SERVER['HTTPS'] == on)
if(HTTP_PROTOCOL == 'https://')
{	$global["tpl_url"] 		= SSL_SITE_URL."/templates/".$global['curr_tpl'];
	$global["modbase_url"] 	= SSL_SITE_URL."/modules/";
}else
{	$global["tpl_url"] 		= SITE_URL."/templates/".$global['curr_tpl'];
	$global["modbase_url"] 	= SITE_URL."/modules/";
}
$global["mod_url"] 		= SITE_URL."/modules/".$_REQUEST['mod'];
$global["sslsite_url"] 		= SSL_SITE_URL."/templates/".$global['curr_tpl'];

$global["ssl_url"] 	= SSL_SITE_URL."/modules/";
$global["sslsiteretrn_url"] 		= SSL_SITE_URL;





$framework->tpl->assign("GLOBAL", $global);

$mod 		= 	$_REQUEST['mod'] 		? 	$_REQUEST['mod'] 		: 	"";
$pg  		=	$_REQUEST['pg'] 		? 	$_REQUEST['pg'] 		: 	"default";
$storename	=	$_REQUEST['storename'] 	? 	$_REQUEST['storename'] 	: 	 "";

if($mod=="product" && $pg=="list" && $_REQUEST['act']=="desc")
	$product_id	=	$_REQUEST['id'];
if($mod && $pg)
	$no_right_column=6;
else
	$no_right_column=8;
########Featured items
$FeaturedProducts	=	new Product();
$rs = $FeaturedProducts->GetFeaturedItems2($storename);




if (count($rs)<6)
$count = count($rs);
else
$count = 6;

		for($i=0;$i<$count;$i++){
		$rs[$i]['discount_price'] = $objPrice->GetPriceOfProduct($rs[$i]['id']);
		$rs[$i]['description']=trim($rs[$i]['description']);
		}
//print_r($rs);
$framework->tpl->assign("FEATURED_ITEMS",$rs);
######################
if(count($rs[0]['data'])>0)
$num_rows=ceil((count($rs[0]['data']))/2);
if($num_rows==""){
	$num_rows=3;
}
$framework->tpl->assign("DEFAULT_ROWS",$num_rows);		
/*set_time_limit(0);
ini_set("display_errors", "on");
error_reporting(E_ALL);
$FeaturedProducts->AllProduct();*/

		/**/
//$framework->tpl->assign("FEATURED_TITLE", $FeaturedProducts->GetFeaturedTitle());
//$rs	=	$FeaturedProducts->GetFeaturedItems($storename);
//$framework->tpl->assign("FEATURED_ITEMS",$rs);
//Featured items ends

// ---  Menu ---
$objCategory = new Category();
$objCategory->getCategoryMenuTree($catTree,$storename);

$framework->tpl->assign("CAT_MENU", substr($catTree, 0, -2));

$objCms = new Cms();

//global variable for getting full path from cms
$global['fullpath']=1;



	
$framework->tpl->assign("STORE_COMBO", $objStore->storeCombo());
// --- /Shops ---

// ---  Cart ---
$objCart = new Cart();
$framework->tpl->assign("CART_BOX", $objCart->getCartBox());


//for store by  robin
$framework->tpl->assign("MENU_ALIGN",$global['menu_style']); 
if ( $_REQUEST['mod'] == "store" )  {
  $mod_Rep = explode ("_", $_REQUEST['pg'] );
  $framework->tpl->assign("MOD_REP", $mod_Rep[0]);
} else {
  $framework->tpl->assign("MOD_REP", $_REQUEST['mod']);
}






$file = FRAMEWORK_PATH."/modules/{$mod}/lib/{$pg}.php";
$framework->tpl->assign("SITE_URL", SITE_URL);
//print $file;





$file = FRAMEWORK_PATH."/modules/{$mod}/lib/{$pg}.php";
$framework->tpl->assign("SITE_URL", SITE_URL);



if(file_exists($file)) {
	include_once($file);
} else {
	$framework->tpl->display($global['curr_tpl']."/index_div.tpl");
}

//echo "<pre>";
//print_r($_SESSION);
//print_r($global);
if(strstr(SITE_URL, "192"))echo base64_decode($_REQUEST['sess']);
?>
