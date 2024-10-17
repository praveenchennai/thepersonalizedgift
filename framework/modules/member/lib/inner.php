<?php
session_start();
include_once(FRAMEWORK_PATH."/modules/member/lib/class.user.php");
$objUser=new User();
$framework->tpl->assign("COUNTRY_LIST", $objUser->listCountry());
if((isset($_POST['btn_save']))&&($_POST['btn_save']!=""))
{
    if(isset($_POST['newsletter']))
    {
        $newsletter=$_POST['newsletter'];
    }
    if(isset($_POST['first_name']))
    {
        $first_name=$_POST['first_name'];
    }
    if(isset($_POST['last_name']))
    {
        $last_name=$_POST['last_name'];
    }
    $name=$first_name."".$last_name;
    if(isset($_POST['email']))
    {
        $email=$_POST['email'];
    }

    /*if($newsletter=="yes")
    {
    $InsertNewsLetter="INSERT INTO mailinglist(type,name,email)VALUES('ML','$name','$email')";
    $db->Execute($InsertNewsLetter);
    }*/
}
$getId=$_SESSION["memberid"];



if($_SERVER["REQUEST_METHOD"]=="POST")
{

    //Image checking
    $errorMessage = '';
    // first check if the number submitted is correct
    $fail=0;
    if(isset($_POST['txtNumber']))
    {
        $number   = $_POST['txtNumber'];
        //echo( $_SESSION['image_random_value']);
        if (md5($number) != $_SESSION['image_random_value'])
        {
            $fail=2;
        }
    }

    //isValid("insert");
    $fname=basename($_FILES['image']['name']);
    $ftype=$_FILES['image']['type'];
    $tmpname=$_FILES['image']['tmp_name'];
	if ($fname)
	{
    	$_POST["image"]='Y';
	}
	else
	{
		$_POST["image"]='N';
	}	

    if($_POST["password"]!==$_POST["repassword"])
    {
        $mesg="Password doesnt match";
        $framework->tpl->assign("MESSAGE", $mesg);
    }
    else if($fail==2)
    {
        $mesg="Please Enter Correct code";
        $framework->tpl->assign("MESSAGE", $mesg);
    }
    else
    {

        if (!$getId)
        {

            $_POST['newsletter'] = 'N';
            $objUser->setArrData($_POST);
            $myId=$objUser->insert();
            if(!$myId)
            {
                $framework->tpl->assign("MESSAGE",$objUser->getErr());
            }
            $_SESSION["userId"]=$myId;
            if ($myId)
            {
                //setting Default page setting

                //End default page setting
                if ($fname)
                {

                    //uploading the file
				$dir=SITE_PATH."/modules/member/images/userpics/";
				$thumbdir=$dir."thumb/";
				uploadImage($_FILES['image'],$dir,$_FILES['image']['name'],1);
				chmod($dir.$_FILES['image']['name'],0777);
				thumbnail($dir,$thumbdir,$_FILES['image']['name'],100,100,"","$myId.jpg");
				chmod($thumbdir."$myId.jpg",0777);
				@unlink(SITE_PATH."/modules/member/images/userpics/".$_FILES['image']['name']);
                }
				
				$message="<div style='padding-left: 25px; padding-right: 25px;'>";
				$message=$message."<h4>Industrypage.com New Account Activation</h4>";
				$message=$message."<br>Username: ". $_POST["username"] . "<br>";
				$message=$message."First Name: ". $_POST["first_name"] . "<br>";
				$message=$message."Last Name: ". $_POST["last_name"] . "<br>";
				$message=$message."<p>Please click on the link below to activate you account</p>";
				$message=$message."<a target='_blank' href=\"".SITE_URL."/".makeLink(array("mod"=>"member", "pg"=>"login"), "fn=active&user_id=$myId")."\">Activate your account now</a>";
				$message=$message. "<p>". $_REQUEST["comments"] . "</p>";
				$message=$message."<p>Thanks,<br>";
				$message=$message. "Industrypage.com</p>";
				$message=$message."</div>";
				
				mimeMail($_POST["email"],"New Account Activation",$message,'','','Industrypage.com <'.$framework->config['admin_email'].'>');

                redirect(makeLink(array("mod"=>"member", "pg"=>"login"),"act=y"));
                //_redirect("login.php");
            }


        }
        else
        {
		
            $_POST["id"]=$getId;
            $objUser->setArrData($_POST);
            $upId=$objUser->update($getId);
            redirect(makeLink(array("mod"=>"member", "pg"=>"home")));
            //_redirect("MessageSent.php?msg=update");
        }
    }
}
if($getId)
{
    $editflg="true";
    $framework->tpl->assign("EDITFLG", $editflg);
    $userDet=$objUser->getUserdetails($getId);
    $framework->tpl->assign("USERINFO", $userDet);

}
$framework->tpl->assign("main_tpl",SITE_PATH."/modules/member/tpl/reg.tpl");
$framework->tpl->display($global['curr_tpl']."/inner.tpl");

?>