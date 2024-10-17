<?php
include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
$album       = new Album();
checkLogin();
if($_REQUEST['crt'] == "M1") {
	$type = "video";
	include_once(FRAMEWORK_PATH."/modules/album/lib/class.video.php");
	$obj = new Video();
} else {
	$type = "music";
	include_once(FRAMEWORK_PATH."/modules/album/lib/class.music.php");
	$obj = new Music();
}
$obj->incrementDownload($_REQUEST['id']);
$media = $album->mediaDetailsGet($_REQUEST['id'], $type);
$path = SITE_PATH."/modules/album/$type/";
if ($media['filetype']=="")
{
	$media['filetype'] = "mp3";
}
if ($media['media_encrypt_name']!="")
{
	$file = $media['media_encrypt_name'].".".$media['filetype'];
}
else 
{
	$file = $_REQUEST['id'].".".$media['filetype'];
}

$sh_id = $_REQUEST["sh_id"];
if ($sh_id)
{
	$arr = array();
	$arr["download_status"] = "Y";
	$album->setArrData($arr);
	$album->updateShopDetails($sh_id);
}
header('Content-Disposition: attachment; filename="'.$file.'"');
readfile($path.$file);
exit;
?>