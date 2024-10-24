<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

header ("Expires: ".gmdate("D, d M Y H:i:s", time())." GMT");  
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  
header ("Cache-Control: no-cache, must-revalidate");  
header ("Pragma: no-cache");


ob_start();
session_start();

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

//print_r($MOD_VARIABLES);


$framework->tpl->assign("MOD_VARIABLES", $MOD_VARIABLES);
$framework->tpl->assign("COM_VARIABLES", $COM_VARIABLES);




// End
//echo 1;
//print_r($_SESSION);
$objUser->updateSession(); //updates the time as last time in session table

if ($_REQUEST['sess']) {
	parse_str(base64_decode($_REQUEST['sess']), $req);
	$_REQUEST = array_merge($_REQUEST, $req);
}

if ($_SESSION["memberid"]) {	
	$framework->tpl->assign("MEMBER", $objUser->getUserdetails($_SESSION["memberid"]));
	$framework->tpl->assign("LASTLOGIN", $objUser->getLastSession($_SESSION["memberid"]));
}

$storename	=	$_REQUEST['storename'] 	? 	$_REQUEST['storename'] 	: 	 "";
//echo '<!-- storename='.$storename.'-->';
$storedet=	$objStore->storeGetByName1($storename);
//echo '<!-- storename='.print_r($storedet).'-->';
if($storedet['deleted'] == 'Y' ){
	//redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=invalid_store"));
	$reURL=SITE_URL.'/'.'index.php?mod=member&pg=register&act=invalid_store';
	redirect($reURL);
}



#----- for redirecting to the store on 03-07-07 -------------
include_once(FRAMEWORK_PATH."/modules/store/lib/class.storeredirect.php");
$objRedir	=	new StoreRedirect();
$domain	=	$_SERVER["HTTP_HOST"];
$domainpath = $_SERVER['REQUEST_URI'];
$domain_name1	= HTTP_PROTOCOL. WWW_STR .$domain;
$domain_name2	= HTTP_PROTOCOL. $domain;
$domain_name3	= $domain;
$domain_name4	= str_replace("www.","",$domain);
$redirectStorename	=	$objRedir->GetStorenameforRedirect($domain_name1);
/*echo '<!-- domain='.$domain.' , domainpath='.$domainpath.', $domain_name1 = '.$domain_name1.',
$domain_name2='.$domain_name2.', $domain_name3='.$domain_name3.', $domain_name4='.$domain_name4.', -->';
*/
if($redirectStorename=="")
{
	$redirectStorename	=	$objRedir->GetStorenameforRedirect($domain_name2);
}
if($redirectStorename=="")
{
	$redirectStorename	=	$objRedir->GetStorenameforRedirect($domain_name3);
}
//echo '<!-- redirectStorename = '.$redirectStorename.' -->';
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
	echo '<!-- inside'.$redirectURL.' --- '. $redirectStatus .'-->';
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

if($storeDetails['deleted'] == 'Y' ){
	$reURL=SITE_URL.'/'.'index.php?mod=member&pg=register&act=invalid_store';
	redirect($reURL);
}

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



if($_REQUEST['storename'] !='' && $storeDetails == '' ){
//	This will redirect to main site if the store URL following the site name is not present .
	$framework->tpl->assign("INVALID_STORE",$_SERVER['REQUEST_URI']);
	$_SESSION['invalid_store_url']=SITE_URL.$_SERVER['REQUEST_URI'];
//	redirect(makeLink(array("mod"=>"member", "pg"=>"register"),"act=invalid_store"));
	//redirect(SITE_URL);

	$reURL=SITE_URL.'/'.'index.php?mod=member&pg=register&act=invalid_store';
	redirect($reURL);


}

$currencydet=$paymenttypeObj->getCurrencyStore($store_id);
if(! count($currencydet))
	$currencydet=$paymenttypeObj->getDefaultCurrency();

$analytics_code = $paymenttypeObj->getAnalyticsCode($store_id);
$framework->tpl->assign("ANALYTICS_CODE",$analytics_code['analytics_code']);


if(count($currencydet))
{
	$currency_symbol=$currencydet['symbol'];
	$framework->tpl->assign("CURRENCY_SYMBOL",$currencydet['symbol']);
	$framework->tpl->assign("CURRENCY_CODE",$currencydet['currency_code']);
	$framework->tpl->assign("CURRENCY_DISPLAYTXT",$currencydet['currency_shorttxt']);
	$framework->tpl->assign("CURRENCY_CODE",$currencydet['currency_code']);
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
//echo "<!--".print_r($global,true)."-->";

//=========This code is added  for getting / after storename=========

$domainpath = $_SERVER['REQUEST_URI'];
$domain_name1	=HTTP_PROTOCOL.WWW_STR.$domain;
$domain_name2	=HTTP_PROTOCOL.$domain;
$rest1 = substr("$domainpath", -1);
$str = $_SERVER['QUERY_STRING'];
$str=substr($str,0,3);
//echo $domainpath;
$checkSplit	=	explode("/",$domainpath);
$rootURL	=	$checkSplit[count($checkSplit)-1];
if($rest1 != '/' && $str!='mod' && $_REQUEST['mod']=='' && $rootURL!='index.php')
{


	$domain1=$domain_name2.$domainpath.'/';
	//echo $domain1;
	header("Location: $domain1");
}
//====================================================================




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


/*if($_REQUEST['storename']=='newhome' or $_REQUEST['storename']=='personaltouch' ){
	$rs = $FeaturedProducts->GetFeaturedItems3($storename);
}
else{
	$rs = $FeaturedProducts->GetFeaturedItems2($storename);
}*/
$rs = $FeaturedProducts->GetFeaturedItems3($storename);

if(count($rs) == 0){ // this is done because adarsh asked to do.
	$rs = $FeaturedProducts->GetFeaturedItems2($storename);}


if (count($rs)<8)
	$count = count($rs);
else
	$count = 8;

for($i=0;$i<$count;$i++){

	if($rs[$i]['gift']	=='pgift')
	{
		$pgift_det= $product->getPredefinedGiftDetails($rs[$i]['id']);

		if($pgift_det['product_sale_price'] >0 ){

			$rs[$i]['discount_price'] = $pgift_det['product_sale_price'];
		}
		else{
			$rs[$i]['discount_price'] = $pgift_det['product_basic_price'];
		}

		$rs[$i]['description']	=	trim($rs[$i]['description']);
		$rs[$i]['pid']			=	$pgift_det['product_id'];
	}
	else{
		$rs[$i]['discount_price'] = $objPrice->GetPriceOfProduct($rs[$i]['id']);
		$rs[$i]['description']	=	trim($rs[$i]['description']);
	}
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

$childcategories = $objCategory->getChildCategoriesListById2(282,$store_id);
$framework->tpl->assign("CATEGORY_PGIFT",$childcategories);

if($storename=="")#if no store is present
{
	$framework->tpl->assign("TOP_MENU", $objCms->linkList("top"));
	$framework->tpl->assign("LEFT_SUB_MENU1", $objCms->linkList("left_sub1","",1));
	$framework->tpl->assign("LEFT_SUB_MENU2", $objCms->linkList("left_sub2","",1));
	$framework->tpl->assign("LEFT_MENU", $objCms->linkList("left","",1));
	$framework->tpl->assign("BOTTOM_MENU", $objCms->linkList("bottom","",1));
	$framework->tpl->assign("BOTTOM_SUB_MENU", $objCms->linkList("bottomsub","",1));
	$framework->tpl->assign("DATE", $date = date("l M j, Y"));
	$storeDetails['heading1']=$global['heading1'];
	$storeDetails['heading2']=$global['heading2'];
	$framework->tpl->assign("WELCOME_MESSAGE_STORE", $storeDetails);

	$framework->tpl->assign("WELCOME_MESSAGE", $objCms->getPageDet('welcome'));
	$framework->tpl->assign("INNER_TOP_MENU", $objCms->linkList("inner_top_menu","",1));


}
else#if some store is present
{
	$domainpath = $_SERVER['REQUEST_URI'];
	$checkSplit	=	explode("/",$domainpath);

	if($checkSplit[2]!=$storename)
	{

		$framework->tpl->assign("TOP_MENU", $objCms->linkList("store_top",$storename));
		$framework->tpl->assign("LEFT_SUB_MENU1", $objCms->linkList("store_left_sub1",$storename));
		$framework->tpl->assign("LEFT_SUB_MENU2", $objCms->linkList("store_left_sub2",$storename));
		#$framework->tpl->assign("LEFT_MENU", $objCms->linkList("left"));
		#$framework->tpl->assign("BOTTOM_MENU", $objCms->linkList("bottom"));
		#$framework->tpl->assign("BOTTOM_SUB_MENU", $objCms->linkList("bottomsub"));
		#$framework->tpl->assign("INNER_TOP_MENU", $objCms->linkList("inner_top_menu"));
		$framework->tpl->assign("BOTTOM_MENU", $objCms->linkList("store_bottom",$storename));

		$framework->tpl->assign("WELCOME_MESSAGE_STORE", $storeDetails);
		$framework->tpl->assign("INNER_TOP_MENU", $objCms->linkList("store_inner_top_menu",$storename));
		$framework->tpl->assign("STOREFLAG", 'Y');
		$framework->tpl->assign("STORE_NAME", $storename);


	}else
	{


		//print_r($objCms->linkList("store_top"));
		$framework->tpl->assign("TOP_MENU", $objCms->linkList("store_top"));
		$framework->tpl->assign("LEFT_SUB_MENU1", $objCms->linkList("store_left_sub1"));
		$framework->tpl->assign("LEFT_SUB_MENU2", $objCms->linkList("store_left_sub2"));
		#$framework->tpl->assign("LEFT_MENU", $objCms->linkList("left"));
		#$framework->tpl->assign("BOTTOM_MENU", $objCms->linkList("bottom"));
		#$framework->tpl->assign("BOTTOM_SUB_MENU", $objCms->linkList("bottomsub"));
		#$framework->tpl->assign("INNER_TOP_MENU", $objCms->linkList("inner_top_menu"));
		$framework->tpl->assign("BOTTOM_MENU", $objCms->linkList("store_bottom"));


		$framework->tpl->assign("WELCOME_MESSAGE_STORE", $storeDetails);
		$framework->tpl->assign("INNER_TOP_MENU", $objCms->linkList("store_inner_top_menu"));
		$framework->tpl->assign("STOREFLAG", 'Y');
	}
	$framework->tpl->assign("WELCOME_MESSAGE", $objCms->getPageDet('welcome'));


}
//print_r($storeDetails);

// --- /Menu ---
//print_r($objCms->linkList("left_sub1"));
// ---  Shops ---
//print_r($objCms->getPageDet('invalid_store_url'));


$framework->tpl->assign("STORE_COMBO", $objStore->storeCombo());
// --- /Shops ---

// ---  Cart ---
$objCart = new Cart();
$framework->tpl->assign("CART_BOX", $objCart->getCartBox());
// --- /Cart ---
if($mod == "admin") {
	redirect(SITE_URL);
}
$framework->tpl->assign("INVALID_STORE_MSG", $objCms->getPageDet('invalid_store_url'));

//for store by  robin
$framework->tpl->assign("MENU_ALIGN",$global['menu_style']);
if ( $_REQUEST['mod'] == "store" )  {
	$mod_Rep = explode ("_", $_REQUEST['pg'] );
	$framework->tpl->assign("MOD_REP", $mod_Rep[0]);
} else {
	$framework->tpl->assign("MOD_REP", $_REQUEST['mod']);
}

// end store
if ($_REQUEST['storename'] && $_REQUEST['manage'] == "manage" && $mod!="store") {
	redirect(SITE_URL."/".$_REQUEST['storename']."/manage/".makeLink(array("mod"=>"store", "pg"=>"storeIndex"), ""));
}

//modified by adarsh for meta tag dispaly

$page_title = ($storeDetails['page_title']) ? $storeDetails['page_title'] : $global['page_title'];
$meta_keywords = ($storeDetails['meta_keywords']) ? $storeDetails['meta_keywords'] : $global['meta_keywords'];
$meta_description = ($storeDetails['meta_description']) ? $storeDetails['meta_description'] : $global['meta_description'];

$framework->tpl->assign("PAGE_TITLE",$page_title);
$framework->tpl->assign("META_KEYWORD",$meta_keywords);
$framework->tpl->assign("META_DESCRIPTION",$meta_description);

//modified by adarsh for meta tag dispaly ends



$file = FRAMEWORK_PATH."/modules/{$mod}/lib/{$pg}.php";
$framework->tpl->assign("SITE_URL", SITE_URL);
//print $file;


$month = array (1 => 'JAN',2 => 'FEB',3 => 'MAR',4 => 'APR',5 => 'MAY',6 => 'JUN',7 => 'JUL',8 => 'AUG',
                9 => 'SEPT',10 => 'OCT',11 => 'NOV',12 => 'DEC');

$framework->tpl->assign("LOGO_MONTH",$month[date("n")]);
$framework->tpl->assign("LOGO_DAY",strtoupper(date("d")));


$file = FRAMEWORK_PATH."/modules/{$mod}/lib/{$pg}.php";
$framework->tpl->assign("SITE_URL", SITE_URL);




if(file_exists($file)) {
	include_once($file);
} else {
	$framework->tpl->display($global['curr_tpl']."/index_div.tpl");
}

//print_r($_REQUEST);

//echo "<pre>";
//print_r($_SESSION);
//print_r($global);

if(strstr(SITE_URL, "192"))echo base64_decode($_REQUEST['sess']);
?>

