<?php 
/**
 * Newsletter
 *
 * @author sajith
 * @package defaultPackage
 */


authorize();
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
include_once(FRAMEWORK_PATH."/modules/newsletter/lib/class.newsletter.php");

$newsletter = new Newsletter();
$objUser		=	new User();

switch($_REQUEST['act']) {
    case "list":
        if($_SERVER['REQUEST_METHOD'] == "POST") {
           		 
				$req = &$_REQUEST;
				$proceed = 1;
				
			    if($_POST["email2"])
				{
					if($objUser->checkEmail2($_POST["email2"]))
					{
						$proceed =0;
						setMessage("Email already Exists");
					}
				}
				
				if($_POST["username"])
				{
					if($objUser->validUsername($_POST["username"]))
					{
						$proceed =0;
						setMessage("Username already Exists");
					}
				}
				
				if($proceed==1)
				{
					$arr["username"]=$_REQUEST["username"];
					$arr["password"]=$_REQUEST["password"];
					$arr["email"]=$_REQUEST["email2"];
					$arr["first_name"]=$_REQUEST["first_name"];
					$arr["last_name"]=$_REQUEST["last_name"];
					$objUser->setArrData($arr);
					$myId=$objUser->insert();
					setMessage($objUser->getErr());
					
					$name=stripcslashes($_REQUEST["username"]);
					if($myId)
					{
					
						echo "<script language='javascript'>";
						echo "opener.document.getElementById('memberName').innerHTML = '".$name."';";
						//echo "opener.document.subFrm.email.value = email;";
						echo "opener.document.subFrm.member_id.value = ".$myId.";";
						//opener.document.getElementById("newuser").style.display = "none";
						echo "window.close();";
						echo "</script>";
					}
				}
				
			}
        $framework->tpl->assign("main_tpl", FRAMEWORK_PATH."/modules/store/tpl/newuser.tpl");
        break;
}
$framework->tpl->display($global['curr_tpl']."/adminPopup.tpl");

?>