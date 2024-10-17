<?php
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
$objUser=new User();

if($global["fluent_member"]=="1")
{
unset($_SESSION['guid']);
unset($_SESSION['memberid']);
header("location:index.php");
exit;
}

//print_r($_SESSION["mem_sess_id"]);exit;
if ($_SESSION["mem_sess_id"])
{
	$objUser->logoutSession($_SESSION["mem_sess_id"]);
	
}
//////unset chat  session//link54

		if($global["inner_change_reg"]=="yes")
							{
		                unset($_COOKIE["phpfreechat"]);
		        /* if (isset($_COOKIE[session_name()])) {
		             setcookie(session_name(), '', time()-42000, '/');
		                                               }*/
							}
/////////unset chat  session
//session_regenerate_id();
session_unset();
session_destroy();
redirect(makeLink(array('mod'=>"member", "pg"=>"login")));
?>