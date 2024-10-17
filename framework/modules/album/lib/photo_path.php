<?
header("Content-type: image/jpeg");

$image1 = imagecreatefromgif(SITE_PATH."/modules/ajax_editor/images/mat_1.gif");
imagegif($image1);
exit;
?>
