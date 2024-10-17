<?php
	session_start();
	include_once(FRAMEWORK_PATH."/modules/album/lib/class.photo.php");
	include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
	include_once(FRAMEWORK_PATH."/modules/album/lib/class.album.php");
	
	$album= new Album();
	$objPhoto=new Photo();
	$objUser=new User();
	
	$framework->tpl->assign("main_tpl", SITE_PATH."/modules/album/tpl/photo_details.tpl");	
	$framework->tpl->display($global['curr_tpl']."/inner.tpl");
?>