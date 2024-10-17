<?php

if($_SERVER['REQUEST_METHOD'] == "POST") {
	include_once(FRAMEWORK_PATH."/modules/admin/lib/class.admin.php");
	$admin = new Admin($_POST['username'], $_POST['password']);
	if($admin->authenticate()) {
		redirect("index.php");
	} else {
		setMessage($admin->errorMessage);
	}
}

			//echo "<pre>";
			//print_r($_SESSION['adminSess']);

$framework->tpl->display($global['curr_tpl']."/admin_login.tpl");

?>