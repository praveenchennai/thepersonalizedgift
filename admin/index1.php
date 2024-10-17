<?php

include_once("../config.php");
include_once(FRAMEWORK_PATH."/includes/class.message.php");
include_once(FRAMEWORK_PATH."/includes/functions.php");
include_once(FRAMEWORK_PATH."/includes/class.framework.php");

session_start();

if ($_REQUEST['sess']) {
	parse_str(base64_decode($_REQUEST['sess']), $req);
	$_REQUEST = array_merge($_REQUEST, $req);
}

authorize();

$framework = new FrameWork();

$global 				= $framework->config;
$global["tpl_url"] 		= SITE_URL."/templates/".$framework->config['curr_tpl'];
$global["mod_url"] 		= SITE_URL."/modules/".$_REQUEST['mod'];
$global["modbase_url"] 	= SITE_URL."/modules/";
$framework->tpl->assign("GLOBAL", $global);

$mod = $_REQUEST['mod'] ? $_REQUEST['mod'] : "admin";
$pg  = $_REQUEST['pg'] ? $_REQUEST['pg'] : "index";

$file = FRAMEWORK_PATH."/modules/{$mod}/lib/{$pg}.php";

if(file_exists($file)) {
	include_once($file);
} else {
	$framework->tpl->display($global['curr_tpl']."/admin.tpl");
}
if(strstr(SITE_URL, "92") && $_REQUEST['sess'])echo base64_decode($_REQUEST['sess']);
?>